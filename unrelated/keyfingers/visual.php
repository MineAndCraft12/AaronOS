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
                document.getElementById("fileInput").onchange = function(){
                    var files = this.files;
                    var file = URL.createObjectURL(files[0]);
                    startSong(file);
                };
                startSong = function(file){
                    document.getElementById('audioBoxesDiv').style.backgroundColor = '#000';
                    document.getElementById('loadDiv').style.display = "block";
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
                            document.getElementById('songname').value = loadStr;
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
                            document.getElementById('songname').value = 'Loading Audio...';
                        }
                    }
                    audioMusic.oncanplaythrough = function(){
                        if(loadsReady){
                            document.getElementById('songname').value = loadStr;
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
                            document.getElementById('songname').value = 'Loading Audio...';
                        }
                    }
                    document.getElementById('songname').value = 'Loading Visualizer...';
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
        <audio id="msAudio" src="" onended="if(resetAfter){window.location='https://aaron-os-mineandcraft12.c9.io/unrelated/keyfingers/visual.php?random=true';}"></audio>
        <div id="loadDiv">
            <div id="loadTotal"></div>
            <div id="loadTime"></div>
        </div>
        <div id="audioBoxesDiv" onclick="makeBig()"></div>
        <div style="position:absolute;top:0;left:0;z-index:100">
            <input id="fileInput" type="file" accept="audio/*"></input>
            <button onclick="audio.pause();audioMusic.pause();audio.currentTime = 0;audioMusic.currentTime = 0;setTimeout(function(){audioMusic.play()},400);audio.play();">&larr;</button>
            <!--<button style="background-color:red;" onclick="audio.pause();audioMusic.pause();audio.currentTime -= 5;audioMusic.currentTime -= 5;audio.play();audioMusic.play();">&lt;&lt;</button>-->
            <button onclick="audio.pause();audioMusic.pause();">||</button>
            <button onclick="audio.play();audioMusic.play();">&gt;</button>
            <!--<button style="background-color:red;" onclick="audio.pause();audioMusic.pause();audio.currentTime += 5;audioMusic.currentTime += 5;audio.play();audioMusic.play();">&gt;&gt;</button>-->
            <button onclick="window.location='visual.php'">New</button>
            Delay ms: <input id="delayTime" style="font-family:monospace;" value="150" size="4">
            Min decibels: <input id="db" style="font-family:monospace;" value="-70" size="4">
            <button onclick="window.location='https://aaron-os-mineandcraft12.c9.io/unrelated/keyfingers/visual.php?random=true';">Randomize</button>
            <button onclick="window.location='https://aaron-os-mineandcraft12.c9.io/unrelated/keyfingers/cnv.php';">Normal</button>
        </div>
        <?php
            if(isset($_GET['random'])){
                echo '<script defer>resetAfter = 1;window.setTimeout(function(){document.getElementById("songname").value = \'music/\' + getRandSong() + \'.mp3\';startSong();makeBig();}, 1000);</script>';
            }
        ?>
    </body>
</html>