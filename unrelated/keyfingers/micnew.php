<!DOCTYPE html>
<html>
    <head>
        <title>aOS Music Visualizer</title>
        <script defer>
            var boxDiv;
            var loadsReady;
            var loadStr;
            var resetAfter = 0;
            var analyser;
            var ctx;
            var ctxMusic;
            var audio;
            var audioMusic;
            var audioSrc;
            var renderFrame;
            var startSong;
            var frequencyData;
            var makeBig;
            var big;
            var freqDat;
            var boxes;
            window.requestAnimationFrame(function(){
                boxDiv = document.getElementById('audioBoxesDiv');
                for(var i = 0; i < 1024; i++){// id="b' + i + '"
                    boxDiv.innerHTML += '<div class="box" style="left:' + i + 'px"></div>';
                }
                boxes = document.getElementsByClassName('box');
                boxDiv = document.getElementById('audioBoxesDiv');
                big = 0;
                makeBig = function(){
                    if(!big){
                        big = 1;
                        document.getElementById('audioBoxesDiv').style.transform = 'scale(' + (parseInt(window.innerWidth, 10) / 1024) + ')';
                        document.body.style.backgroundColor = "#000";
                    }else{
                        big = 0;
                        document.getElementById('audioBoxesDiv').style.transform = 'scale(1)';
                        document.body.style.backgroundColor = "#FFF";
                    }
                };
                renderFrame = function(){
                    requestAnimationFrame(renderFrame);
                    analyser.getByteFrequencyData(frequencyData);
                    boxDiv.style.display = 'none';
                    for(var i = 0; i < 1024; i++){
                        freqDat = frequencyData[i];
                        boxes[i].style.height = freqDat + 'px';
                        boxes[i].style.backgroundColor = 'rgb(0,' + freqDat + ',' + (255 - freqDat) + ')';
                    }
                    boxDiv.style.display = 'block';
                };
                var microphone;
                startSong = function(){
                    document.getElementById('audioBoxesDiv').style.backgroundColor = '#000000';
                    document.getElementById('loadDiv').style.display = "block";
                    ctx = new window.AudioContext();
                    analyser = ctx.createAnalyser();
                    analyser.fftSize = 32768;
                    analyser.minDecibels = -100;
                    analyser.smoothingTimeConstant = 0;
                    frequencyData = new Uint8Array(analyser.frequencyBinCount);
                    loadsReady = 0;
                    navigator.webkitGetUserMedia({audio:true}, function(stream){
                        microphone = ctx.createMediaStreamSource(stream);
                        microphone.connect(analyser);
                        renderFrame();
                    }, function(){alert('error');});
                }
            });
        </script>
        <style>
            audio{
                display:none;
            }
            #audioBoxesDiv{
                transform-origin:0 0;
                width:1024px;/*690px;*/
                overflow:hidden;
                height:255px;
                background-color:#FFF;
                position:absolute;
                top:30px;
                left:0;
            }
            #loadDiv{
                display:none;
                position:absolute;
                top:27px;
                left:0;
                height:3px;
                background-color:#333;
                width:1024px;
            }
            #loadTotal{
                position:absolute;
                height:3px;
                left:0;
                width:0;
                background-color:#066;
                transition:2s;
                transition-timing-function:linear;
            }
            #loadTime{
                position:absolute;
                height:3px;
                left:0;
                width:0;
                background-color:#0FF;
                transition:2s;
                transition-timing-function:linear;
            }
            .box{
                position:absolute;
                bottom:0;
                height:0;
                width:1px;
                background-color:#F00;
            }
            input, button{
                border:1px solid #000;
                background:none;
                color:#000;
            }
        </style>
    </head>
    <body>
        <audio id="myAudio" src=""></audio>
        <div id="loadDiv">
            <div id="loadTotal"></div>
            <div id="loadTime"></div>
        </div>
        <div id="audioBoxesDiv" onclick="makeBig()"></div>
        <div style="position:absolute;top:0;left:0;z-index:100">
            <button onclick="startSong()">Start</button>
        </div>
        <?php
            if(isset($_GET['random'])){
                echo '<script defer>resetAfter = 1;window.setTimeout(function(){document.getElementById("songname").value = \'music/\' + getRandSong() + \'.mp3\';startSong()}, 1000);</script>';
            }
        ?>
    </body>
</html>