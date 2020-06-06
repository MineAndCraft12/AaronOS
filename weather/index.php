<!DOCTYPE html>
<html>
    <head>
        <script defer src="../aosTools.js"></script>
        <script defer>
            window.needToAutoSetup = 0;
            window.aosTools_connectListener = function(){
                aosTools.sendRequest({
                    action: "fs:read_lf",
                    targetFile: "aos_system/apps/weather/settings"
                }, (response) => {
                    try{
                        if(typeof response.content === "string"){
                            if(response.content[0] === "{"){
                                if(typeof setup === "function"){
                                    setup("ready", JSON.parse(response.content));
                                }else{
                                    window.needToAutoSetup = JSON.parse(response.content);
                                }
                            }
                        }
                    }catch(err){
                        console.log("Error in auto-setup: " + err);
                    }
                    aosTools.enablePadding();
                    aosTools.openWindow();
                });
            }
            if(typeof aosTools === "object"){
                aosTools.testConnection();
            }
        </script>
        <script defer src="wthr.js"></script>
    </head>
    <body>
        <div class="winHTML" style="font-family:sans-serif">
            <div id="intro">
                Welcome to the AaronOS Weather App.<br><br>
                Units:
                <div style="position:relative">
                    <input type="radio" id="usUnitChoice" name="unitChoice" checked onchange="setUnitChoice(this)">
                    <label for="usUnitChoice">US customary (&deg;F)</label><br>
                    <input type="radio" id="siUnitChoice" name="unitChoice" onchange="setUnitChoice(document.getElementById('usUnitChoice'))">
                    <label for="siUnitChoice">SI (&deg;C)</label>
                </div><br>
                Get your location automatically: <button onclick="setup('automatic')">Start with Location</button><br><br>
                Or, enter some coordinates:<br>
                <input id="inputLat" placeholder="Latitude (south is negative)"><br>
                <input id="inputLong" placeholder="Longitude (west is negative)"><br>
                <button onclick="setup('manual')">Start with Coordinates</button>
                <h1>WORK IN PROGRESS</h1>
                <p>This is NOT a finished product. The UI and functionality are still being worked on.</p>
            </div>
            <div id="main" style="display:none;width:100%;">
                <h1 id="locationTitle">No Location</h1>
                <button onclick="getForecast()">Get Forecast</button><br>
                <button onclick="getForecast('hourly')">Get Hourly Forecast</button>
                <button style="display:block;position:absolute;top:24px;right:24px;" onclick="newLocation()">New Location</button>
                <div id="output" style="position:relative;"></div>
            </div>
        </div>
    </body>
</html>