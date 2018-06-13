<!DOCTYPE html>
<html>
    <head>
        <title>aOS Music Visualizer</title>
        <script defer>
            var songs = [
                'Assault on the Control Room', 'Blow Me Away', 'Brothers in Arms', 'Brutes', 'Connected', 'Covenant Dance', 'Dread Intrusion', 'Earth City Piano', 'Earth City', 'Farthest Outpost', 'Flawed Legacy', 'Follow', 'Ghosts of Reach', 'Halo Mjolnir', 'Halo', 'Heavy Price Paid', 'Heretic Hero', 'Impend', 'Last Spartan', 'Leonidas', 'Peril', 'Tsavo Highway', 'Under Cover of Night', 'Unyielding', 'Warthog Run Extended',
                'Awake and Alive', 'Comatose', 'Feel Invincible', 'Never Be Alone', 'Rebirthing', 'Sick of It',
                'Back On Track', 'Base After Base', 'Cant Let Go', 'Dry Out', 'Stereo Madness', 'xStep',
                'Are You Ready', 'Ascent', 'Bass Revolution', 'Earthquake', 'Firepower', 'Flight', 'Flux Pavillion', 'Highscore', 'Senses Overload', 'Shockwave',
                'Bohemian Rhapsody', 'Finale', 'Fireflies', 'Jellyfish Jam', 'Rock You Like a Hurricane', 'Space Oddity', 'Video Game Song',
                'Chaoz Fantasy', 'Fire Aura', 'Heaven',
                'Fallen Kingdom', 'Minecraft Song', 'Revenge', 'TNT',
                'Sonata Facile Allegro', 'Sonata Facile Andante', 'Sonata Facile Rondo',
                'Tetris A', 'Tetris B'
            ];
            var getRandSong = function(){
                return songs[Math.floor(Math.random() * songs.length)];
            };
            var alSoStr = '';
            var alertSongs = function(){
                alSoStr = 'All below will have "music/" before the title and ".mp3" after the title.\n\nI tried to organise them in groups alphabetically (by the first song name) , and within those groups each song is alphabetical.\n\nFor instance, Halo is its own group, as are a group of Misc songs (Queen, Scorpion, Star Wars, Owl City all have only one song so they in misc group).\n\nThe groups are not separated (the script uses this very list to determine its random songs; dont want it to try to load "music/-----.mp3" now, do we?) and can be hard to tell apart. When the alphabetical order in the list goes wrong, you have entered a new group.\n\n';
                for(var song in songs){
                    alSoStr += songs[song] + '\n';
                }
                alert(alSoStr);
            };
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
            var blur;
            var blurctx;
            window.requestAnimationFrame(function(){
                /*
                boxDiv = document.getElementById('audioBoxesDiv');
                for(var i = 0; i < 1024; i++){// id="b' + i + '"
                    boxDiv.innerHTML += '<div class="box" style="left:' + i + 'px"></div>';
                }
                boxes = document.getElementsByClassName('box');
                */
                blur = document.getElementById('audioBlursDiv');
                blurctx = blur.getContext('2d');
                boxDiv = document.getElementById('audioBoxesDiv');
                vctx = boxDiv.getContext('2d');
                vctx.lineWidth = 1;
                vctx.strokeStyle = '#00FF00';
                vctx.fillRect(0, 0, 1024, 255);
                big = 0;
                makeBig = function(sizes){
                    if(sizes){
                        big = 1;
                        boxDiv.style.top = '0';
                        blur.style.top = '0';
                        //boxDiv.style.transform = 'scale(' + (window.innerWidth / 1024) + ',' + (window.innerHeight / 255) + ')';
                        boxDiv.style.width = sizes[0] + 'px';
                        boxDiv.width = sizes[0];
                        boxDiv.style.height = sizes[1] + 'px';
                        boxDiv.height = sizes[1];
                        blur.style.width = boxDiv.style.width;
                        blur.width = boxDiv.width;
                        blur.style.height = boxDiv.style.height;
                        blur.height = boxDiv.height;
                        transformFact = [sizes[0] / 1024, sizes[1] / 255, Math.round(1024 / (boxDiv.width - 1024))];
                        //vctx.lineWidth = Math.floor(transformFact[0]) + 1;
                        document.getElementById('btns').style.display = 'none';
                        document.getElementById('loadDiv').style.display = 'none';
                    }else if(!big){
                        big = 1;
                        boxDiv.style.top = '0';
                        blur.style.top = '0';
                        //boxDiv.style.transform = 'scale(' + (window.innerWidth / 1024) + ',' + (window.innerHeight / 255) + ')';
                        boxDiv.style.width = window.innerWidth + 'px';
                        boxDiv.width = window.innerWidth;
                        boxDiv.style.height = window.innerHeight + 'px';
                        boxDiv.height = window.innerHeight;
                        blur.style.width = boxDiv.style.width;
                        blur.width = boxDiv.width;
                        blur.style.height = boxDiv.style.height;
                        blur.height = boxDiv.height;
                        transformFact = [window.innerWidth / 1024, window.innerHeight / 255, Math.round(1024 / (boxDiv.width - 1024))];
                        //vctx.lineWidth = Math.floor(transformFact[0]) + 1;
                        document.getElementById('btns').style.display = 'none';
                        document.getElementById('loadDiv').style.display = 'none';
                    }else{
                        big = 0;
                        boxDiv.style.top = '30px';
                        blur.style.top = '30px';
                        //boxDiv.style.transform = 'scale(1)';
                        boxDiv.style.width = '1024px';
                        boxDiv.width = '1024';
                        boxDiv.style.height = '255px';
                        boxDiv.height = '255';
                        blur.style.width = boxDiv.style.width;
                        blur.width = boxDiv.width;
                        blur.style.height = boxDiv.style.height;
                        blur.height = boxDiv.height;
                        transformFact = [1, 1, 1025];
                        //vctx.lineWidth = 1;
                        document.getElementById('btns').style.display = 'block';
                        document.getElementById('loadDiv').style.display = 'block';
                    }
                };
                renderFrame = function(){
                    requestAnimationFrame(renderFrame);
                    analyser.getByteFrequencyData(frequencyData);
                    vctx.clearRect(0, 0, 1024 * transformFact[0], 255 * transformFact[1]);
                    if(!big){
                        for(var i = 0; i < 1024; i++){
                            vctx.beginPath();
                            vctx.strokeStyle = 'rgb(0, ' + frequencyData[i] + ',' + (255 - frequencyData[i]) + ')';
                            vctx.moveTo(i + 0.5, 255);
                            vctx.lineTo(i + 0.5, (255 - frequencyData[i]));
                            vctx.stroke();
                        }
                    }else{
                        for(var i = 0; i < 1024; i++){
                            vctx.beginPath();
                            vctx.strokeStyle = 'rgb(0, ' + frequencyData[i] + ',' + (255 - frequencyData[i]) + ')';
                            vctx.moveTo(Math.round(i * transformFact[0]) + 0.5, 255 * transformFact[1]);
                            vctx.lineTo(Math.round(i * transformFact[0]) + 0.5, Math.round((255 - frequencyData[i]) * transformFact[1]));
                            vctx.stroke();
                            if(Math.round((i + 1) * transformFact[0]) - Math.round(i * transformFact[0]) > 1 && i !== 1023){
                                tempMedian = Math.round((frequencyData[i] + frequencyData[i + 1]) / 2);
                                vctx.beginPath();
                                vctx.strokeStyle = 'rgb(0, ' + tempMedian + ',' + (255 - tempMedian) + ')';
                                vctx.moveTo(Math.round(i * transformFact[0]) + 1.5, 255 * transformFact[1]);
                                vctx.lineTo(Math.round(i * transformFact[0]) + 1.5, Math.round((255 - tempMedian) * transformFact[1]));
                                vctx.stroke();
                            }
                        }
                    }
                    blurctx.clearRect(0, 0, 1024 * transformFact[0], 255 * transformFact[1]);
                    blurctx.drawImage(boxDiv, 0, 0);
                };
                document.getElementById("fileInput").onchange = function(){
                    var files = this.files;
                    var file = URL.createObjectURL(files[0]);
                    startSong(file);
                };
                startSong = function(file){
                    document.getElementById('loadDiv').style.display = "block";
                    blur.style.filter = 'blur(' + document.getElementById('blurPx').value + 'px)';
                    if(file){
                        document.getElementById('myAudio').src = file;
                        document.getElementById('msAudio').src = file;
                        document.getElementsByTagName('title')[0].innerHTML = "Local File - aOS MV";
                    }else{
                        document.getElementById('myAudio').src = document.getElementById('songname').value;
                        document.getElementById('msAudio').src = document.getElementById('songname').value;
                        document.getElementsByTagName('title')[0].innerHTML = document.getElementById('songname').value.substring(6, document.getElementById('songname').value.indexOf('.')) + " - aOS MV";
                    }
                    ctx = new window.AudioContext();
                    ctxMusic = new window.AudioContext();
                    audio = document.getElementById('myAudio');
                    audioMusic = document.getElementById('msAudio');
                    audioSrc = ctx.createMediaElementSource(audio);
                    analyser = ctx.createAnalyser();
                    analyser.fftSize = 32768;
                    analyser.minDecibels = parseInt(document.getElementById('db').value, 10);
                    analyser.smoothingTimeConstant = 0;
                    audioSrc.connect(analyser);
                    frequencyData = new Uint8Array(analyser.frequencyBinCount);
                    loadsReady = 0;
                    loadStr = "Local File";
                    audio.oncanplaythrough = function(){
                        if(loadsReady){
                            //document.getElementById('songname').value = loadStr;
                            setTimeout(function(){
                                audioMusic.play();
                            }, parseInt(document.getElementById('delayTime').value, 10));
                            audio.play();
                            document.getElementById('loadTotal').style.width = Math.floor(audioMusic.buffered.end(0) / audioMusic.duration * 1024) + "px";
                            document.getElementById('loadTime').style.width = Math.floor(audioMusic.currentTime / audioMusic.duration * 1024) + "px";
                            setInterval(function(){
                                document.getElementById('loadTotal').style.width = Math.floor(audioMusic.buffered.end(0) / audioMusic.duration * 1024) + "px";
                                document.getElementById('loadTime').style.width = Math.floor(audioMusic.currentTime / audioMusic.duration * 1024) + "px";
                            }, 2000);
                            renderFrame();
                        }else{
                            loadsReady = 1;
                            //document.getElementById('songname').value = 'Loading Audio...';
                        }
                    }
                    audioMusic.oncanplaythrough = function(){
                        if(loadsReady){
                            //document.getElementById('songname').value = loadStr;
                            setTimeout(function(){
                                audioMusic.play();
                            }, parseInt(document.getElementById('delayTime').value, 10));
                            audio.play();
                            document.getElementById('loadTotal').style.width = Math.floor(audioMusic.buffered.end(0) / audioMusic.duration * 1024) + "px";
                            document.getElementById('loadTime').style.width = Math.floor(audioMusic.currentTime / audioMusic.duration * 1024) + "px";
                            setInterval(function(){
                                document.getElementById('loadTotal').style.width = Math.floor(audioMusic.buffered.end(0) / audioMusic.duration * 1024) + "px";
                                document.getElementById('loadTime').style.width = Math.floor(audioMusic.currentTime / audioMusic.duration * 1024) + "px";
                            }, 2000);
                            renderFrame();
                        }else{
                            loadsReady = 1;
                            //document.getElementById('songname').value = 'Loading Audio...';
                        }
                    }
                    //document.getElementById('songname').value = 'Loading Visualizer...';
                }
            });
        </script>
        <style>
            body{
                background-color:#000;
                color:#FFF;
            }
            audio{
                display:none;
            }
            #audioBoxesDiv{
                width:1024px;/*690px;*/
                overflow:hidden;
                height:255px;
                position:absolute;
                top:30px;
                left:0;
            }
            #audioBlursDiv{
                width:1024px;/*690px;*/
                background-color:#000;
                overflow:hidden;
                height:255px;
                position:absolute;
                top:30px;
                left:0;
                filter:blur(5px);
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
            input, button{
                border:1px solid #FFF;
                background:none;
                color:#FFF;
            }
        </style>
    </head>
    <body>
        <audio id="myAudio" src=""></audio>
        <audio id="msAudio" src="" onended="if(resetAfter){window.location='https://aaron-os-mineandcraft12.c9.io/unrelated/keyfingers/cnv.php?random=true';}"></audio>
        <div id="loadDiv">
            <div id="loadTotal"></div>
            <div id="loadTime"></div>
        </div>
        <canvas id="audioBlursDiv" width="1024" height="255"></canvas>
        <canvas id="audioBoxesDiv" onclick="makeBig()" width="1024" height="255"></canvas>
        <div id="btns" style="position:absolute;top:0;left:0;z-index:100">
            <input id="fileInput" type="file" accept="audio/*"></input>
            <button onclick="audio.pause();audioMusic.pause();audio.currentTime = 0;audioMusic.currentTime = 0;setTimeout(function(){audioMusic.play()},400);audio.play();">&larr;</button>
            <!--<button style="background-color:red;" onclick="audio.pause();audioMusic.pause();audio.currentTime -= 5;audioMusic.currentTime -= 5;audio.play();audioMusic.play();">&lt;&lt;</button>-->
            <button onclick="audio.pause();audioMusic.pause();">||</button>
            <button onclick="audio.play();audioMusic.play();">&gt;</button>
            <!--<button style="background-color:red;" onclick="audio.pause();audioMusic.pause();audio.currentTime += 5;audioMusic.currentTime += 5;audio.play();audioMusic.play();">&gt;&gt;</button>-->
            <button onclick="window.location='ultracnv.php'">New</button>
            Delay ms: <input id="delayTime" style="font-family:monospace;" value="200" size="4">
            Min dec: <input id="db" style="font-family:monospace;" value="-70" size="4">
            Blur: <input id="blurPx" style="font-family:monospace;" value="5" size="3">
            <button onclick="window.location='https://aaron-os-mineandcraft12.c9.io/unrelated/keyfingers/ultracnv.php?random=true';">Randomize</button>
            <button onclick="window.location='https://aaron-os-mineandcraft12.c9.io/unrelated/keyfingers/ultra.php';">Old Ultra System</button>
        </div>
        <?php
            if(isset($_GET['random'])){
                echo '<script defer>resetAfter = 1;window.setTimeout(function(){document.getElementById("songname").value = \'music/\' + getRandSong() + \'.mp3\';startSong();makeBig();}, 1000);</script>';
            }
        ?>
    </body>
</html>