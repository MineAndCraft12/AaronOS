<!DOCTYPE html>
<html>
    <head>
        <style>
            body{
                position:absolute;
                left:0;
                top:0;
                width:100%;
                height:100%;
                overflow:hidden;
            }
            img{
                width:120%;
                height:120%;
                left:calc(-10% - 8px);
                top:calc(-10% - 8px);
                display:block;
                position:absolute;
                transform:translate(0,0);
            }
        </style>
    </head>
    <body>
        
    </body>
    <script defer>
        var url = window.location.href;
        var images = decodeURIComponent(url.split("?")[1].split("#")[0].split('=')[1]).split(',');
        var coords = url.split("#")[1].split(",");
        var html = '';
        for(var i in images){
            html += '<img id="i' + i + '" src="' + images[i] + '">';
        }
        document.body.innerHTML = html;
        function moveCoords(){
            url = window.location.href;
            coords = url.split("#")[1].split(",");
            //document.getElementById("coords").innerHTML = coords.join(', ');
            
            for(var i = 0; i < images.length; i++){
                document.getElementById('i' + i).style.transform = 'translate(' +
                    Math.round((((window.innerWidth / 2) - coords[0]) / (window.innerWidth / 2) * (window.innerWidth * 0.1)) * (i / (images.length - 1))) + 'px,' + Math.round((((window.innerHeight / 2) - coords[1]) / (window.innerHeight / 2) * (window.innerHeight * 0.1)) * (i / (images.length - 1))) + 'px)';
            }
            requestAnimationFrame(moveCoords);
        }
        moveCoords();
    </script>
</html>