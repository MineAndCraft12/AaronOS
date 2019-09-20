<!DOCTYPE html>
<html>
    <head>
        <script src="../aosTools.js" data-light="true"></script>
        <script defer>
            var city = "";
            var state = "";

            window.aosTools_connectListener = function(){
                aosTools.sendRequest({
                    action: "fs:read_lf",
                    targetFile: "aos_system/apps/weather/settings"
                }, (response) => {
                    if(typeof response.content === "string"){
                        if(response.content[0] === "{"){
                            var tempjson = JSON.parse(response.content);
                            if(tempjson.city && tempjson.state){
                                city = tempjson.city;
                                state = tempjson.state;
                                setInterval(checkAlerts, 120000);
                                checkAlerts();
                            }
                        }
                    }
                });
            }
            if(typeof aosTools === "object"){
                aosTools.testConnection();
            }

            var alerthttp = null;

            var lastAlerts = [];

            function checkAlerts(){
                if(city && state){
                    alerthttp = new XMLHttpRequest();
                    alerthttp.open("GET", "getAlerts.php?city=" + city + "&state=" + state + "&dom=" + location.domain);
                    alerthttp.onreadystatechange = recieveAlerts;
                    alerthttp.send();
                }
            }

            var notified = 0;

            function recieveAlerts(){
                if(alerthttp.readyState === 4){
                    if(alerthttp.status === 200){
                        if(!notified){
                            if(alerthttp.responseText[0] === "["){
                                var tempjson = JSON.parse(alerthttp.responseText);

                                if(tempjson != lastAlerts && tempjson.length > 0){
                                    lastAlerts = tempjson;
                                    notified = 1;
                                    aosTools.notify({
                                        content: tempjson.length + " alerts for " + city + ", " + state,
                                        buttons: ['Dismiss', 'View Alerts'],
                                        image: 'appicons/ds/systemApp.png'
                                    }, (response) => {
                                        notified = 0;
                                        if(response.content === 1){
                                            var str = "Active Weather Alerts<br>" + city + ", " + state;
                                            for(var i in tempjson){
                                                str += '<hr>';
                                                str += tempjson[i].properties.event + '<br>';
                                                str += 'Affected Areas: ' + tempjson[i].properties.areaDesc + '<br>';
                                                if(tempjson[i].properties.instruction){
                                                    str += 'Station: ' + tempjson[i].properties.senderName + '<br>';
                                                    str += tempjson[i].properties.instruction + '<br><br>';
                                                }else{
                                                    str += 'Station: ' + tempjson[i].properties.senderName + '<br><br>';
                                                }
                                                str += tempjson[i].properties.headline + '<br><br>';
                                                str += tempjson[i].properties.description;
                                            }
                                            aosTools.alert({
                                                button: 'Okay',
                                                content: str
                                            }, (response) => {
                                                
                                            });
                                        }
                                    });
                                }

                            }else{
                                console.log("alerts is not json");
                            }
                        }
                    }else{
                        console.log("couldn't get alerts: status " + alerthttp.status);
                    }
                }
            }
        </script>
    </head>
    <body>

    </body>
</html>