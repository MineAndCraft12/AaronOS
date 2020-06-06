function getId(trgt){
    return document.getElementById(trgt);
}

if(typeof lat !== "number"){
    window.lat = 0;
    window.long = 0;
}

var urlhttp = null;

var weatherURLs = {
    forecast: "",
    forecastHourly: ""
};

var city = "";
var state = "";

var readyStarted = 0;

function setup(type, coords){
    var canContinue = 1;
    if(type === 'automatic'){
        if(coords){
            lat = coords[0];
            long = coords[1];
        }else{
            navigator.geolocation.getCurrentPosition(recieveGeoCoords);
            canContinue = 0;
        }
    }else if(type === 'manual'){
        lat = getId("inputLat").value;
        long = getId("inputLong").value;
    }else if(type === "ready"){
        readyStarted = 1;
        lat = coords.lat;
        long = coords.long;
        weatherURLs = coords.weatherURLs;
        city = coords.city;
        state = coords.state;
        getId("intro").style.display = "none";
        getId("main").style.display = "";
        getId("locationTitle").innerHTML = city + ", " + state;
        canContinue = 0;
    }else{
        alert('wat');
        canContinue = 0;
    }
    if(canContinue){
        urlhttp = new XMLHttpRequest();
        urlhttp.open("GET", "getURLs.php?dom=" + location.origin + "&lat=" + lat + "&lon=" + long);
        urlhttp.onreadystatechange = recieveURLs;
        urlhttp.send();
    }
}

function recieveGeoCoords(geoResponse){
    setup("automatic", [geoResponse.coords.latitude, geoResponse.coords.longitude]);
}

function recieveURLs(){
    if(urlhttp.readyState === 4){
        if(urlhttp.status === 200){
            try{
                var tempjson = JSON.parse(urlhttp.responseText);
                if(tempjson.properties){
                    weatherURLs.forecast = tempjson.properties.forecast;
                    weatherURLs.forecastHourly = tempjson.properties.forecastHourly;
                    if(getId("siUnitChoice").checked === true) {
                        weatherURLs.forecast += "?units=si";
                        weatherURLs.forecastHourly += "?units=si";
                    }
                    else {}
                    city = tempjson.properties.relativeLocation.properties.city;
                    state = tempjson.properties.relativeLocation.properties.state;
                    getId("intro").style.display = "none";
                    getId("main").style.display = "";
                    getId("locationTitle").innerHTML = city + ", " + state;
                    aosTools.sendRequest({
                        action: "fs:write_lf",
                        targetFile: "aos_system/apps/weather/settings",
                        content: JSON.stringify({
                            city: city,
                            state: state,
                            lat: lat,
                            long: long,
                            weatherURLs: {
                                forecast: weatherURLs.forecast,
                                forecastHourly: weatherURLs.forecastHourly
                            }
                        })
                    }, (response) => {
                        console.log(response.content);
                    });
                    aosTools.bgService.check((response) => {
                        if(response !== false){
                            aosTools.bgService.exit((response) => {
                                aosTools.bgService.set("weather/bgsvc.php");
                            });
                        }else{
                            aosTools.bgService.set("weather/bgsvc.php");
                        }
                    });
                }else{
                    alert("Weather didn't send a proper response");
                }
            }catch(err){
                alert("Something broke in response from weather: " + err);
            }
        }else{
            alert("Weather URL API responded with error " + urlhttp.status);
        }
    }
}

var forecasthttp = null;
var forecastType = '';

function getForecast(type){
    var currURL = weatherURLs.forecast;
    forecastType = '';
    if(type === "hourly"){
        currURL = weatherURLs.forecastHourly;
        forecastType = 'hourly';
    }
    forecasthttp = new XMLHttpRequest();
    forecasthttp.open("GET", "getForecast.php?dom=" + location.domain + "&target=" + currURL);
    forecasthttp.onreadystatechange = recieveForecast;
    forecasthttp.send();
}

var recordHigh = 135;
var recordLow = -80;

function getPercentage(temp){
    //var compareNumber = recordHigh - recordLow;
    //var adjustedTemp = temp - recordLow;
    //return adjustedTemp / compareNumber;
    temp -= 32;
    if(temp <= 0){
        return 0;
    }
    if(temp >= 68){
        return 1;
    }
    return temp / 68;
}

function recieveForecast(){
    if(forecasthttp.readyState === 4){
        if(forecasthttp.status === 200){
            try{
                var tempjson = JSON.parse(forecasthttp.responseText);
                var str = "";
                if(forecastType === "hourly"){
                    var numPeriods = tempjson.properties.periods.length;
                    for(var i = 0; i < numPeriods; i++){
                        str += '<div style="position:relative;width:150px;display:inline-block;text-align:center;border:1px solid rgba(0, 0, 0, 0.25);margin:3px;padding:3px"' +
                            ' title="' + tempjson.properties.periods[i].shortForecast + '">';
                        var tempPcnt = getPercentage(tempjson.properties.periods[i].temperature);
                        str += '<div style="left:-3px;top:-3px;width:calc(100% + 3px);height:calc(100% + 3px);opacity:0.5;z-index:-1;">';
                        str += '<div style="left:-3px;top:-3px;background:#007FFF;width:calc(100% + 3px);height:calc(100% + 3px);z-index:-2;"></div>';
                        str += '<div style="left:-3px;top:-3px;background:#FF7F00;width:calc(100% + 3px);height:calc(100% + 3px);opacity:' + tempPcnt + ';z-index:-1;"></div>';
                        str += '</div>';
                        var theDate = new Date(tempjson.properties.periods[i].startTime)
                        var hour = theDate.getHours();
                        var week = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        var day = week[theDate.getDay()];
                        var ampm = "";
                        if(hour < 12){
                            ampm = "a";
                        }else{
                            ampm = "p";
                        }
                        if(hour >= 12){
                            hour -= 12;
                        }
                        if(hour === 0){
                            hour = 12;
                        }
                        if(hour < 10){
                            hour = "0" + hour + ":00";
                        }else{
                            hour = hour + ":00";
                        }
                        str += day + "<br>";
                        str += hour + ampm + "<br>";
                        str += tempjson.properties.periods[i].temperature + "&deg; F<br>";
                        str += '<div style="width:100%;position:relative;text-overflow:ellipsis;white-space:nowrap">' + tempjson.properties.periods[i].shortForecast + '</div>';
                        str += "</div>";
                    }
                }else{
                    var numPeriods = tempjson.properties.periods.length;
                    for(var i = 0; i < numPeriods; i++){
                        str += '<div style="position:relative;border:1px solid rgba(0, 0, 0, 0.25);width:calc(100% - 11px);padding:3px;margin-top:6px;">';
                        var tempPcnt = getPercentage(tempjson.properties.periods[i].temperature);
                        str += '<div style="left:-3px;top:-3px;width:calc(100% + 3px);height:calc(100% + 3px);opacity:0.5;z-index:-1;">';
                        str += '<div style="left:-3px;top:-3px;background:#007FFF;width:calc(100% + 3px);height:calc(100% + 3px);z-index:-2;"></div>';
                        str += '<div style="left:-3px;top:-3px;background:#FF7F00;width:calc(100% + 3px);height:calc(100% + 3px);opacity:' + tempPcnt + ';z-index:-1;"></div>';
                        str += '</div>';
                        str += "<h3>" + tempjson.properties.periods[i].name + ", " +
                            tempjson.properties.periods[i].shortForecast + "</h3>";
                        if(tempjson.properties.periods[i].temperatureTrend === null){
                            str += "<p>Temperature: " + tempjson.properties.periods[i].temperature + "&deg; F</p>";
                        }else{
                            str += "<p>Temperature: " + tempjson.properties.periods[i].temperature + "&deg; F and " +
                                tempjson.properties.periods[i].temperatureTrend + "</p>";
                        }
                        str += "<p>" + tempjson.properties.periods[i].detailedForecast + "</p>";
                        str += "</div>";
                    }
                }
                getId("output").innerHTML = str;
            }catch(err){
                alert("Something broke parsing json: " + err);
            }
        }else{
            alert("Weather API responded with error " + forecasthttp.status);
        }
    }
}

function newLocation(){
    if(aosTools.connected === 1){
        aosTools.bgService.exit();
        aosTools.sendRequest({
            action: "fs:write_lf",
            targetFile: "aos_system/apps/weather/settings",
            content: "reset"
        }, (response) => {
            location.reload();
        });
    }else{
        location.reload();
    }
}

if(typeof needToAutoSetup === "object"){
    setup("ready", needToAutoSetup);
}