<!DOCTYPE html>
<html>
    <head>
        <script defer src="../aosTools.js"></script>
        <script defer>
            window.aosTools_connectListener = function(){
                aosTools.enablePadding();
                aosTools.openWindow();
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
                Get your location automatically: <button onclick="setup('automatic')">Auto Setup</button><br><br>
                Or, enter some coordinates:<br>
                <input id="inputLat" placeholder="Latitude (south is negative)"><br>
                <input id="inputLong" placeholder="Longitude (west is negative)"><br>
                <button onclick="setup('manual')">Manual Setup</button>
                <h1>WORK IN PROGRESS</h1>
                <p>Automatic location does not work.</p>
                <p>This is NOT a finished product.</p>
            </div>
            <div id="main" style="display:none">
                <h1 id="locationTitle">No Location</h1>
                <button onclick="getForecast()">Get Forecast</button><br>
                <button onclick="getForecast('hourly')">Get Hourly Forecast</button>
                <div id="output" style="position:relative;"></div>
            </div>
        </div>
    </body>
</html>