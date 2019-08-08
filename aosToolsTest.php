<!DOCTYPE html>
<html>
    <head>
        <script defer src="https://aaronos.dev/AaronOS/aosTools.js"></script>
        <script defer>
            window.aosTools_connectListener = function(){
                aosTools.sendRequest({
                    action: "appwindow:open_window"
                }, console.log);
            }
            if(aosTools){
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
            That's a test hr element ^^
        </div>
    </body>
</html>