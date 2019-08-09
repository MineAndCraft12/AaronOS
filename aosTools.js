window.aosTools = {
    connected: -1,
    fallbackMessageListener: window.aosTools_fallbackMessageListener || null,
    connectListener: window.aosTools_connectListener || null,
    connectFailListener: window.aosTools_connectFailListener || null,

    testConnection: function(){
        if(window.aosTools_fallbackMessageListener){
            aosTools.fallbackMessageListener = aosTools_fallbackMessageListener;
        }
        if(window.aosTools_connectListener){
            aosTools.connectListener = aosTools_connectListener;
        }
        if(window.aosTools_connectFailListener){
            aosTools.connectFailListener = aosTools_connectFailListener;
        }
        if(window.self !== window.top){
            aosTools.sendRequest({
                action: "getstyle:darkmode"
            }, aosTools.testConnected);
        }else{
            aosTools.connected = 0;
            console.log("Warning - page is not loaded in a frame and aOS is not connected.");
            if(aosTools.connectFailListener){
                aosTools.connectFailListener();
            }
        }
    },
    testConnected: function(data){
        if(typeof data.content === "boolean"){
            aosTools.connected = 1;
            console.log("AaronOS is connected. Updating stylesheet.");
            aosTools.updateStyle();
            if(aosTools.connectListener){
                aosTools.connectListener();
            }
        }else{
            aosTools.connected = 0;
            console.log("Warning - Requests will not reach aOS; top frame does not seem to be aOS.");
            if(aosTools.connectFailListener){
                aosTools.connectFailListener();
            }
        }
    },

    totalRequests: 0,
    sendRequest: function(requestData, callback){
        if(this.connected !== 0){
            if(!requestData.messageType){
                requestData.messageType = "request";
            }
            if(!requestData.conversation){
                this.totalRequests++;
                requestData.conversation = "" + this.totalRequests;
            }
            this.callbacks[this.totalRequests] = callback || function(){};
            window.top.postMessage(requestData);
        }else{
            console.log("Warning - request will not reach aOS; aOS is not connected.");
        }
        if(this.connected === -1){
            console.log("Warning - requests may not reach aOS; connection test not complete.");
        }
    },
    recieveRequest: function(event){
        if(typeof event.data === "object"){
            if(event.data.conversation){
                if(typeof aosTools.callbacks[event.data.conversation] === "function"){
                    aosTools.callbacks[event.data.conversation](event.data);
                    aosTools.callbacks[event.data.conversation] = null;
                }else{
                    if(aosTools.fallbackMessageListener){
                        aosTools.fallbackMessageListener(event);
                    }
                }
            }else if(aosTools.fallbackMessageListener){
                aosTools.fallbackMessageListener(event);
            }
        }else if(aosTools.fallbackMessageListener){
            aosTools.fallbackMessageListener(event);
        }
    },
    callbacks: {},

    requestPermission: function(permission, callback){
        aosTools.sendRequest({
            action: "permission:" + permission
        }, callback);
    },

    exec: function(codeStr, callback){
        aosTools.sendRequest({
            action: "js:exec",
            content: codeStr
        }, callback);
    },

    openWindow: function(callback){
        aosTools.sendRequest({
            action: "appwindow:open_window"
        }, callback);
    },
    closeWindow: function(callback){
        aosTools.sendRequest({
            action: "appwindow:close_window"
        }, callback);
    },
    setCaption: function(newCaption, callback){
        aosTools.sendRequest({
            action: "appwindow:set_caption",
            content: newCaption
        }, callback);
    },
    enablePadding: function(callback){
        aosTools.sendRequest({
            action: "appwindow:enable_padding"
        }, callback);
    },
    disablePadding: function(callback){
        aosTools.sendRequest({
            action: "appwindow:disable_padding"
        }, callback)
    },

    getDarkMode: function(callback){
        aosTools.sendRequest({
            action: "getstyle:darkmode"
        }, callback);
    },

    updateStyle: function(){
        this.sendRequest({
            action: "getstyle:darkmode"
        }, this.recieveDarkMode);
        this.sendRequest({
            action: "getstyle:customstyle"
        }, this.recieveStylesheets);
    },
    recieveDarkMode: function(data){
        if(data.content === true){
            document.body.classList.add("darkMode");
        }else if(data.content === false){
            document.body.classList.remove("darkMode");
        }
    },
    recieveStylesheets: function(data){
        if(document.getElementById("aosTools_helpingStyle") === null){
            var helpingStyleElement = document.createElement("style");
            helpingStyleElement.id = "aosTools_helpingStyle";
            helpingStyleElement.innerHTML = "body{width:100%;height:100%;overflow:hidden;}.winHTML{overflow:auto;width:100%;height:100%;left:0;top:0;bottom:0;right:0;border:none;background:none;box-shadow:none;padding:0;}";
            document.head.prepend(helpingStyleElement);
        }
        
        if(document.getElementById("aosTools_customStyle") !== null){
            document.getElementById("aosTools_customStyle").remove();
        }
        var customStyleElement = document.createElement("style");
        customStyleElement.id = "aosTools_customStyle";
        document.head.prepend(customStyleElement);
        customStyleElement.innerHTML = data.content.customStyle || "";

        var existingStyleElements = document.getElementsByClassName("aosTools_hubStyle");
        for(var i = 0; i < existingStyleElements.length; i++){
            existingStyleElements[i].remove();
        }
        for(var i = data.content.styleLinks.length - 1; i >= 0; i--){
            if(data.content.styleLinks[i][1] === "link"){
                var customElement = document.createElement("link");
                customElement.className = "aosTools_hubStyle";
                customElement.rel = "stylesheet";
                customElement.href = data.content.styleLinks[i][0];
                document.head.prepend(customElement);
            }else if(data.content.styleLinks[i][1] === "literal"){
                var customElement = document.createElement("style");
                customElement.className = "aosTools_hubStyle";
                customElement.innerHTML = data.content.styleLinks[i][0];
                document.head.prepend(customElement);
            }
        }

        if(document.getElementById("aosTools_officialStyle") !== null){
            document.getElementById("aosTools_officialStyle").remove();
        }
        var officialStyleElement = document.createElement("link");
        officialStyleElement.id = "aosTools_officialStyle";
        officialStyleElement.href = "https://aaronos.dev/AaronOS/styleBeta.css";
        officialStyleElement.rel = "stylesheet";
        document.head.prepend(officialStyleElement);
    }
};

aosTools.testConnection();

window.addEventListener("message", aosTools.recieveRequest);