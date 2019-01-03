<!DOCTYPE html>
<html>
    <head>
        <!-- MOVECOORDS2 -->
        <!--
        <style id="positioning">
            body{
                --mx: 0px;
                --my: 0px;
            }
        </style>
        <style id="globalPositioning">
            body{
                --hw: 100px;
                --hh: 100px;
                --tw: 10px;
                --th: 10px;
                --il: 4px;
            }
        </style>
        -->
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
                /*
                width:120%;
                height:120%;
                */
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
        if(window.innerWidth > window.innerHeight){
            for(var i in images){
                html += '<img id="i' + i + '" src="' + images[i] + '" style="width:120%;">';
            }
        }else{
            for(var i in images){
                html += '<img id="i' + i + '" src="' + images[i] + '" style="height:120%;">';
            }
        }
        
        
        var halfWidth = window.innerWidth * 0.5;
        var halfHeight = window.innerHeight * 0.5;
        var tenthWidth = window.innerWidth * 0.1;
        var tenthHeight = window.innerHeight * 0.1;
        var imgLenMinusOne = images.length - 1;
        var imgLength = images.length;
        
        //MOVECOORDS2
        /*
        document.getElementById('globalPositioning').innerHTML = 'img{--hw:' + Math.round(halfWidth) + 'px;--hh:' + Math.round(halfHeight) + 'px;--tw:' + Math.round(tenthWidth) + 'px;--th:' + Math.round(tenthHeight) + 'px;--il:' + Math.round(imgLenMinusOne) + 'px;}'
        */
        
        document.body.innerHTML = html;
        function moveCoords(){
            url = window.location.href;
            coords = url.split("#")[1].split(",");
            for(var i = 0; i < imgLength; i++){
                document.getElementById('i' + i).style.transform = 'translate(' +
                    Math.round(((halfWidth - coords[0]) / halfWidth * tenthWidth) * (i / imgLenMinusOne)) + 'px,' +
                    Math.round(((halfHeight - coords[1]) / halfHeight * tenthHeight) * (i / imgLenMinusOne)) + 'px)';
            }
            requestAnimationFrame(moveCoords);
        }
        var coordsLast = [10000, 10000];
        function moveCoords2(){
            url = window.location.href;
            coords = url.split("#")[1].split(",");
            if(coords !== coordsLast){
                document.getElementById('positioning').innerHTML = 'img{--mx:' + coords[0] + 'px;--my:' + coords[1] + 'px;}';
                
                coordsLast = coords;
            }
            requestAnimationFrame(moveCoords2);
        }
        
        moveCoords();
        
        //MOVECOORDS2
        /*
        for(var i in images){
            document.getElementById('i' + i).style.transform = 'translate( calc( calc( calc( calc(var(--hw) - var(--mx)) / var(--hw) ) * var(--tw) ) * calc( ' + i + 'px / var(--il) ) ),' +
                'calc( calc( calc( calc(var(--hh) - var(--my)) / var(--hh) ) * var(--th) ) * calc( ' + i + 'px / var(--il) ) ))'
        }
        */
    </script>
</html>