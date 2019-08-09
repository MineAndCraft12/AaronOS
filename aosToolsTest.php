<!DOCTYPE html>
<html>
    <head>
        <!--

            on your own apps, use this line instead:
            <script defer src="https://aaronos.dev/AaronOS/aosTools.js"></script>

            -->
        <script defer src="aosTools.js"></script>
        <script defer>
            // this function will be grabbed by aosTools and run on connection with aOS
            window.aosTools_connectListener = function(){
                // enable padding on the app's window
                // (in the case of this app, we want some padding, but this is up to your own discretion)
                aosTools.enablePadding();

                // open your app's window (if your app uses manualOpen)
                aosTools.openWindow();

                // list all the items in aosTools (ignore this bit)
                for(var i in aosTools){
                    document.getElementById("allItems").innerHTML += "aosTools." + i;
                    switch(typeof aosTools[i]){
                        case "number":
                            document.getElementById("allItems").innerHTML += ": " + aosTools[i];
                            break;
                        case "function":
                            document.getElementById("allItems").innerHTML += ": " + aosTools[i].toString().substring(0, aosTools[i].toString().indexOf("{"));
                            break;
                        case "string":
                            document.getElementById("allItems").innerHTML += ": " + aosTools[i];
                            break;
                        default:
                            document.getElementById("allItems").innerHTML += ": <i>(" + typeof aosTools[i] + ")</i>";
                    }
                    document.getElementById("allItems").innerHTML += "<br><br>";
                }
                // You can stop ignoring now
            }
            // if aosTools was already initialized by the time this script is running
            if(typeof aosTools === "object"){
                // have aosTools reinitialize itself
                aosTools.testConnection();
            }
        </script>
    </head>
    <body>
        <div class="winHTML">
            Test Page<br>
            <button>Test Button</button><br>
            <input placeholder="test input">
            <hr>
            That's a test hr element ^^<br><br>
            Here are all items in the aosTools object:<br><br>
            <pre id="allItems"></pre>
        </div>
    </body>
</html>