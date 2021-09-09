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

                document.getElementById("notconnected").style.display = "none";
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
            <h1>Test Page</h1>
            <p>aosTools is<span id="notconnected" style="color:red"> not</span> connected!</p>
            <p>
                <input placeholder="Input Field"><br><br>
                <button>Button</button>
            </p>
        </div>
    </body>
</html>