window.aosTools = {
    light: 0,
    connected: -1,
    fallbackMessageListener: window.aosTools_fallbackMessageListener || null,
    connectListener: window.aosTools_connectListener || null,
    connectFailListener: window.aosTools_connectFailListener || null,

    pageID: "",
    connectionAlreadyTested: 0,
    testConnection: function(){
        if(!aosTools.connectionAlreadyTested){
            console.log("Initializing aosTools...");
            if(document.currentScript){
                if(document.currentScript.getAttribute("data-light") === "true"){
                    aosTools.light = 1;
                }
            }
        }
        if(window.aosTools_fallbackMessageListener){
            aosTools.fallbackMessageListener = aosTools_fallbackMessageListener;
        }
        if(window.aosTools_connectListener){
            aosTools.connectListener = aosTools_connectListener;
        }
        if(window.aosTools_connectFailListener){
            aosTools.connectFailListener = aosTools_connectFailListener;
        }

        if(!aosTools.connectionAlreadyTested){
            var randomIDChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            var tempStr = "";
            for(var i = 0; i < 16; i++){
                tempStr += randomIDChars[Math.floor(Math.random() * randomIDChars.length)];
            }
            aosTools.pageID = tempStr;

            if(window.self !== window.top){
                aosTools.sendRequest({
                    action: "page_id:create",
                    conversation: "aosTools_verify_page_id"
                }, aosTools.testConnected);
            }else{
                aosTools.connected = 0;
                console.log("Warning - page is not loaded in a frame and aOS is not connected.");
                if(aosTools.connectFailListener){
                    aosTools.connectFailListener();
                }
            }
        }else{
            console.log("aosTools already started initializing. Skipping some initialization work.");
            if(aosTools.connected === 1){
                aosTools.connectListener();
            }else if(aosTools.connected === 0){
                aosTools.connectFailListener();
            }
        }
        aosTools.connectionAlreadyTested = 1;
    },
    testConnected: function(data){
        if(data.content === "pending" || data.content === "ignore"){
            return;
        }
        if(typeof data.content === "boolean"){
            aosTools.connected = 1;
            if(data.content === true){
                if(aosTools.light){
                    console.log("AaronOS is connected. Light mode, not updating stylesheet.");
                }else{
                    console.log("AaronOS is connected. Updating stylesheet.");
                }
            }else{
                if(aosTools.light){
                    console.log("AaronOS is connected, but no parent app found! App-Window-related requests may not work. Light mode, not updating stylesheet.");
                }else{
                    console.log("AaronOS is connected, but no parent app found! App-Window-related requests may not work. Updating stylesheet.");
                }
            }
            aosTools.updateStyle();
            if(aosTools.connectListener){
                aosTools.connectListener();
            }
        }else{
            aosTools.connected = 0;
            console.log("Warning - Requests will not reach aOS; parent frame does not seem to be AaronOS.");
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
            requestData.aosToolsFrameID = aosTools.pageID;
            this.callbacks[this.totalRequests] = callback || function(){};
            window.parent.postMessage(requestData, "*");
        }else{
            console.log("Warning - request will not reach aOS; aOS is not connected.");
        }
        if(this.connected === -1 && requestData.action.indexOf("page_id:") !== 0){
            console.log("Warning - requests may not reach aOS; connection test not complete.");
        }
    },
    recieveRequest: function(event){
        if(typeof event.data === "object"){
            if(event.data.conversation){
                if(event.data.conversation === "aosTools_Subscribed_Style_Update"){
                    aosTools.updateStyle();
                }else if(event.data.conversation === "aosTools_get_page_id"){
                    aosTools.sendRequest({action:"page_id:respond", content: event.data.content}, function(){});
                }else if(event.data.conversation === "aosTools_verify_page_id"){
                    aosTools.testConnected(event.data);
                }else{
                    if(typeof aosTools.callbacks[event.data.conversation] === "function"){
                        aosTools.callbacks[event.data.conversation](event.data);
                        aosTools.callbacks[event.data.conversation] = null;
                    }else{
                        if(aosTools.fallbackMessageListener){
                            aosTools.fallbackMessageListener(event);
                        }
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
    maximize: function(callback){
        aosTools.sendRequest({
            action: "appwindow:maximize"
        }, callback);
    },
    unmaximize: function(callback){
        aosTools.sendRequest({
            action: "appwindow:unmaximize"
        }, callback);
    },
    minimize: function(callback){
        aosTools.sendRequest({
            action: "appwindow:minimize"
        }, callback);
    },
    getMaximized: function(callback){
        aosTools.sendRequest({
            action: "appwindow:get_maximized"
        }, callback);
    },
    setDims: function(newDims, callback){
        aosTools.sendRequest({
            action: "appwindow:set_dims",
            x: newDims.x || null,
            y: newDims.y || null,
            width: newDims.width || null,
            height: newDims.height || null
        }, callback);
    },
    getBorders: function(callback){
        aosTools.sendRequest({
            action: "appwindow:get_borders"
        }, callback);
    },
    getScreenDims: function(callback){
        aosTools.sendRequest({
            action: "appwindow:get_screen_dims"
        }, callback);
    },
    takeFocus: function(callback){
        aosTools.sendRequest({
            action: "appwindow:take_focus"
        }, callback);
    },
    blockScreensaver: function(callback){
        aosTools.sendRequest({
            action: "appwindow:block_screensaver"
        }, callback);
    },
    unblockScreensaver: function(callback){
        aosTools.sendRequest({
            action: "appwindow:unblock_screensaver"
        }, callback);
    },
    
    useDefaultContextMenu: true,
    windowWasClicked: function(event){
        aosTools.takeFocus(function(){});
    },
    windowWasRightClicked: function(event){
        if(aosTools.useDefaultContextMenu){
            aosTools.editMenu(event, true);
        }
    },

    alert: function(paramObj, callback){
        aosTools.sendRequest({
            action: "prompt:alert",
            content: paramObj.content,
            button: paramObj.button
        }, callback);
    },
    prompt: function(paramObj, callback){
        aosTools.sendRequest({
            action: "prompt:prompt",
            content: paramObj.content,
            button: paramObj.button
        }, callback);
    },
    confirm: function(paramObj, callback){
        if(!paramObj.buttons && paramObj.button){
            aosTools.sendRequest({
                action: "prompt:confirm",
                content: paramObj.content,
                buttons: [paramObj.button]
            }, callback);
        }else{
            aosTools.sendRequest({
                action: "prompt:confirm",
                content: paramObj.content,
                buttons: paramObj.buttons
            }, callback);
        }
    },
    notify: function(paramObj, callback){
        if(!paramObj.buttons && paramObj.button){
            aosTools.sendRequest({
                action: "prompt:notify",
                content: paramObj.content,
                buttons: [paramObj.button],
                image: paramObj.image
            }, callback);
        }else{
            aosTools.sendRequest({
                action: "prompt:notify",
                content: paramObj.content,
                buttons: paramObj.buttons,
                image: paramObj.image
            }, callback);
        }
    },

    waitingPasteTarget: null,
    waitingPasteRange: null,
    recievePasteCommand: function(data){
        if(data.content === "pasted"){
            if(aosTools.waitingPasteTarget){
                if(typeof aosTools.waitingPasteTarget.value === "string"){
                    if(aosTools.waitingPasteRange){
                        aosTools.waitingPasteTarget.value = (
                            aosTools.waitingPasteTarget.value.substring(0, aosTools.waitingPasteRange[0]) +
                            data.pastedText +
                            aosTools.waitingPasteTarget.value.substring(aosTools.waitingPasteRange[1], aosTools.waitingPasteTarget.value.length)
                        );
                    }else{
                        aosTools.waitingPasteTarget.value = (
                            data.pastedText +
                            aosTools.waitingPasteTarget.value
                        );
                    }
                }
            }
        }
        aosTools.waitingPasteTarget = null;
        aosTools.waitingPasteRange = null;
    },
    contextMenu: function(event, options, callback, positionOverride){
        aosTools.sendRequest({
            action: "context:menu",
            position: positionOverride || event ? [event.pageX, event.pageY] : [0, 0],
            options: options
        }, callback);
        if(event){
            event.preventDefault();
            event.stopPropagation();
        }
    },
    editMenu: function(event, enablePaste, textOverride, positionOverride){
        aosTools.waitingPasteTarget = event ? event.target : null;
        aosTools.waitingPasteRange = event ? (event.target.selectionStart ? [event.target.selectionStart, event.target.selectionEnd] : null) : null;
        aosTools.sendRequest({
            action: "context:text_menu",
            position: positionOverride || event ? [event.pageX, event.pageY] : [0, 0],
            selectedText: textOverride || document.getSelection().toString(),
            enablePaste: (event ? (typeof event.target.value === "string" ? true : false) : false) ? enablePaste : 0
        }, aosTools.recievePasteCommand);
        if(event){
            event.preventDefault();
            event.stopPropagation();
        }
    },
    enableDefaultMenu: function(){
        aosTools.useDefaultContextMenu = true;
    },
    disableDefaultMenu: function(){
        aosTools.useDefaultContextMenu = false;
    },

    bgService: {
        set: function(newURL, callback){
            aosTools.sendRequest({
                action: "bgservice:set_service",
                serviceURL: newURL
            }, callback);
        },
        exit: function(callback){
            aosTools.sendRequest({
                action: "bgservice:exit_service"
            }, callback);
        },
        check: function(callback){
            aosTools.sendRequest({
                action: "bgservice:check_service"
            }, callback);
        }
    },

    getDarkMode: function(callback){
        aosTools.sendRequest({
            action: "getstyle:darkmode"
        }, callback);
    },
    getCustomStyle: function(callback){
        aosTools.sendRequest({
            action: "getstyle:customstyle"
        }, callback);
    },

    updateStyle: function(){
        if(!aosTools.light){
            this.sendRequest({
                action: "getstyle:darkmode"
            }, this.recieveDarkMode);
            this.sendRequest({
                action: "getstyle:customstyle"
            }, this.recieveStylesheets);
        }
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
        document.body.classList.add("cursorDefault");
    }
};

aosTools.testConnection();

window.addEventListener("message", aosTools.recieveRequest);
window.addEventListener("click", aosTools.windowWasClicked);
window.addEventListener("contextmenu", aosTools.windowWasRightClicked);