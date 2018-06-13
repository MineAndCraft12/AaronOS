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
            var vctx;
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
            var transformFact = [1, 1, 1025];
            var tempMedian;
            window.requestAnimationFrame(function(){
                /*
                boxDiv = document.getElementById('audioBoxesDiv');
                for(var i = 0; i < 1024; i++){// id="b' + i + '"
                    boxDiv.innerHTML += '<div class="box" style="left:' + i + 'px"></div>';
                }
                boxes = document.getElementsByClassName('box');
                */
                boxDiv = document.getElementById('audioBoxesDiv');
                vctx = boxDiv.getContext('2d');
                vctx.lineWidth = 1;
                vctx.strokeStyle = '#00FF00';
                vctx.fillRect(0, 0, 1024, 255);
                big = 0;
                makeBig = function(sizes){
                    if(sizes){
                        big = 1;
                        document.body.background = '#000';
                        boxDiv.style.top = '0';
                        //boxDiv.style.transform = 'scale(' + (window.innerWidth / 1024) + ',' + (window.innerHeight / 255) + ')';
                        boxDiv.style.width = sizes[0] + 'px';
                        boxDiv.width = sizes[0];
                        boxDiv.style.height = sizes[1] + 'px';
                        boxDiv.height = sizes[1];
                        transformFact = [sizes[0] / 1024, sizes[1] / 255, Math.round(1024 / (boxDiv.width - 1024))];
                        //vctx.lineWidth = Math.floor(transformFact[0]) + 1;
                        document.getElementById('btns').style.display = 'none';
                        document.getElementById('loadDiv').style.display = 'none';
                    }else if(!big){
                        big = 1;
                        document.body.background = '#000';
                        boxDiv.style.top = '0';
                        //boxDiv.style.transform = 'scale(' + (window.innerWidth / 1024) + ',' + (window.innerHeight / 255) + ')';
                        boxDiv.style.width = window.innerWidth + 'px';
                        boxDiv.width = window.innerWidth;
                        boxDiv.style.height = window.innerHeight + 'px';
                        boxDiv.height = window.innerHeight;
                        transformFact = [window.innerWidth / 1024, window.innerHeight / 255, Math.round(1024 / (boxDiv.width - 1024))];
                        //vctx.lineWidth = Math.floor(transformFact[0]) + 1;
                        document.getElementById('btns').style.display = 'none';
                        document.getElementById('loadDiv').style.display = 'none';
                    }else{
                        big = 0;
                        document.body.background = 'none';
                        boxDiv.style.top = '30px';
                        //boxDiv.style.transform = 'scale(1)';
                        boxDiv.style.width = '1024px';
                        boxDiv.width = '1024';
                        boxDiv.style.height = '255px';
                        boxDiv.height = '255';
                        transformFact = [1, 1, 1025];
                        //vctx.lineWidth = 1;
                        document.getElementById('btns').style.display = 'block';
                        document.getElementById('loadDiv').style.display = 'block';
                    }
                };
                renderFrame = function(){
                    requestAnimationFrame(renderFrame);
                    analyser.getByteFrequencyData(frequencyData);
                    vctx.putImageData(vctx.getImageData(0, 0, 1024 * transformFact[0], 255 * transformFact[1]), 0, -1);
                    if(!big){
                        for(var i = 0; i < 1024; i++){
                            //vctx.beginPath();
                            vctx.fillStyle = 'rgb(0, ' + frequencyData[i] + ',' + (255 - frequencyData[i]) + ')';
                            //vctx.moveTo(i + 0.5, 255);
                            //vctx.lineTo(i + 0.5, 254);
                            //vctx.stroke();
                            vctx.fillRect(i, 254, 1, 1);
                        }
                    }else{
                        for(var i = 0; i < 1024; i++){
                            //vctx.beginPath();
                            vctx.fillStyle = 'rgb(0, ' + frequencyData[i] + ',' + (255 - frequencyData[i]) + ')';
                            //vctx.moveTo(Math.round(i * transformFact[0]) + 0.5, 255 * transformFact[1]);
                            //vctx.lineTo(Math.round(i * transformFact[0]) + 0.5, Math.round((255 - frequencyData[i]) * transformFact[1]));
                            //vctx.stroke();
                            vctx.fillRect(Math.round(i * transformFact[0]), Math.round(254 * transformFact[1]), 1, 1);
                            if(Math.round((i + 1) * transformFact[0]) - Math.round(i * transformFact[0]) > 1 && i !== 1023){
                                tempMedian = Math.round((frequencyData[i] + frequencyData[i + 1]) / 2);
                                //vctx.beginPath();
                                vctx.fillStyle = 'rgb(0, ' + tempMedian + ',' + (255 - tempMedian) + ')';
                                //vctx.moveTo(Math.round(i * transformFact[0]) + 1.5, 255 * transformFact[1]);
                                //vctx.lineTo(Math.round(i * transformFact[0]) + 1.5, Math.round((255 - tempMedian) * transformFact[1]));
                                //vctx.stroke();
                                vctx.fillRect(Math.round(i * transformFact[0]) + 1, Math.round(254 * transformFact[1]), 1, 1);
                            }
                        }
                    }
                };
                var microphone;
                startSong = function(){
                    document.getElementById('audioBoxesDiv').style.backgroundColor = '#000000';
                    document.getElementById('loadDiv').style.display = "block";
                    ctx = new window.AudioContext();
                    analyser = ctx.createAnalyser();
                    analyser.fftSize = 32768;
                    analyser.minDecibels = -100
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
        <canvas id="audioBoxesDiv" onclick="makeBig()" width="1024" height="255"></canvas>
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