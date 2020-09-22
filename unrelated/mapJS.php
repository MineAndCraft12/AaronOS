<!DOCTYPE html>
<html>
<head>
    <style>
        .file{
            padding:2px;
            margin:2px;
            display:inline-block;
            position:relative;
            border:1px solid rgba(0, 0, 0, 0.5);
            font-size:0;
            transition:font-size 0.3s;
            cursor:pointer;
        }
        .file.selected{
            font-size:12px;
        }
        .file.selected > .file{
            font-size:12px;
        }
        body{
            font-family:monospace;
        }
    </style>
    <script defer>
        function getId(targ){
            return document.getElementById(targ);
        }
        var currExpanded = document.getElementsByClassName("selected");
        function doExpand(e){
            if(event.target.classList.contains("file")){
                while(currExpanded.length > 0){
                    try{
                        currExpanded[0].classList.remove("selected");
                    }catch(err){

                    }
                }
                selectAllParents(event.target);
            }
        }
        function selectAllParents(target){
            if(target !== null && target.classList.contains("file")){
                target.classList.add("selected");
                selectAllParents(target.parentElement);
            }
        }
        var filetypes = {
            string: "#00AA00",
            number: "#5555FF",
            object: "#00AAAA",
            boolean: "#AA00AA",
            function: "#AAAA00"
        };
        var totalIterations = 3;
        var currOrigin = null;
        function clean(input){
            return input.split("<").join("&lt;").split("&gt;").join("&gt;").split("'").join("&apos;").split('"').join("&quot;");
        }
        function scan(iterations, target, targetName, isFirst){
            var canSkip = 1;
            if(iterations === undefined){
                iterations = 0;
                target = window.top;
                targetName = "window.top";
                currOrigin = window.top
                canSkip = 0;
            }else if(isFirst){
                currOrigin = target;
            }
            if(iterations > totalIterations || target === currOrigin){
                if(canSkip){
                    return "";
                }
            }

            //if(typeof target === "object" && target !== "null"){
            //    str += "<br>";
            //}
            var str = "<div class='file'";
            var currColor = "#7F7F7F";
            if(filetypes[typeof target]){
                if(target === null){
                    currColor = "#AA0000";
                }else{
                    currColor = filetypes[typeof target];
                }
            }
            str += " style='background:" + currColor + ";'";
            //str += " title='" + targetName + "'>";

            switch(typeof target){
                case "string":
                    if(target.length > 40){
                        str += " title='" + targetName + ": " + clean(target.substring(0, 40)) + "...'";
                    }else{
                        str += " title='" + targetName + ": " + clean(target) + "'";
                    }
                    break;
                case 'number':
                    str += " title='" + targetName + ": " + target + "'";
                    break;
                case 'function':
                    str += " title='" + targetName + ": " + clean(target.toString().substring(0, target.toString().indexOf("{"))) + "'";
                    break;
                default:
                    str += " title='" + targetName + ": " + typeof target + "'";
            }

            str += ">" + clean(targetName) + "<br>";

            if(typeof target === "object" && target !== null){
                for(var i in target){
                    str += scan(iterations + 1, target[i], i);
                }
            }

            str += "</div>";
            //if(typeof target === "object" && target !== null){
            //    str += "<br>";
            //}
            return str;
        }
    </script>
</head>
<body onclick="doExpand(event)">
    <input id="reference" value="apps" placeholder="Scan Target">
    <input id="iterations" value="3" placeholder="Iterations (small numbers work best)">
    <button onclick="if(getId('iterations').value !== ''){totalIterations = parseInt(getId('iterations').value);}if(getId('reference').value !== ''){getId('output').innerHTML = scan(0, eval('window.top.' + getId('reference').value), 'window.top.' + getId('reference').value), 1}else{getId('output').innerHTML = scan()}">Scan</button>
    <div id="output"></div>
</body>
</html>