<!DOCTYPE html>
<html>
    <head>
        <script defer>
            var alertSongs = function(){
                alert(
                    'Song Paths supported:\n' +
                    'music/Assault On The Control Room.mp3\n' +
                    'music/Back On Track.mp3\n' +
                    'music/Base After Base.mp3\n' +
                    'music/BassRevolution.mp3\n' +
                    'music/Blow Me Away.mp3\n' +
                    'music/Bohemian Rhapsody.mp3\n' +
                    'music/Brothers in Arms.mp3\n' +
                    'music/Cant Let Go.mp3\n' +
                    'music/Chaoz Fantasy.mp3\n' +
                    'music/Comatose.mp3\n' +
                    'music/Connected.mp3\n' +
                    'music/Covenant Dance.mp3\n' +
                    'music/Dry Out.mp3\n' +
                    'music/Earth City.mp3\n' +
                    'music/Finale.mp3\n' +
                    'music/Fire Aura.mp3\n' +
                    'music/Follow.mp3\n' +
                    'music/g.wav (a simple sine wave)\n' +
                    'music/Ghosts of Reach.mp3\n' +
                    'music/Halo Mjolnir.mp3\n' +
                    'music/Halo.mp3\n' +
                    'music/haloUnkn3.mp3\n' +
                    'music/Heaven.mp3\n' +
                    'music/Heretic Hero.mp3\n' +
                    'music/Impend.mp3\n' +
                    'music/Last Spartan.mp3\n' +
                    'music/Leonidas.mp3\n' +
                    'music/Minecraft Song.mp3\n' +
                    'music/Peril.mp3\n' +
                    'music/Rebirthing.mp3\n' +
                    'music/Sonata Facile Allegro.mp3\n' +
                    'music/Sonata Facile Andante.mp3\n' +
                    'music/Sonata Facile Rondo.mp3\n' +
                    'music/spessOdyssey.mp3\n' +
                    'music/Stereo Madness.mp3\n' +
                    'music/Tsavo Highway.mp3\n' +
                    'music/Unyielding.mp3\n' +
                    'music/Video Game Song.mp3\n' +
                    'music/Warthog Run Extended.mp3\n' +
                    'music/xStep.mp3'
                );
            };
            var boxDiv;
            var ctx;
            var analyser;
            var vctx;
            var ctxMusic;
            var audio;
            var audioMusic;
            var audioSrc;
            var renderFrame;
            var startSong;
            var frequencyData;
            window.requestAnimationFrame(function(){
                boxDiv = document.getElementById('audioBoxesDiv');
                vctx = boxDiv.getContext('2d');
                vctx.lineWidth = 1;
                vctx.strokeStyle = '#000000';
                vctx.fillRect(0, 0, 1024, 255);
                //prepare boxes here
                renderFrame = function(){
                    requestAnimationFrame(renderFrame);
                    analyser.getByteFrequencyData(frequencyData);
                    vctx.clearRect(0, 0, 2048, 510);
                    vctx.beginPath();
                    for(var i = 0; i < 1024; i++){
                        vctx.moveTo(i, 0);
                        vctx.lineTo(i, (255 - frequencyData[i]));
                    }
                    vctx.stroke();
                };
                document.getElementById("fileInput").onchange = function(){
                    var files = this.files;
                    var file = URL.createObjectURL(files[0]);
                    startSong(file);
                };
                startSong = function(file){
                    document.getElementById('audioBoxesDiv').style.background = '#0F0';
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
                    analyser.smoothingTimeConstant = 0;
                    audioSrc.connect(analyser);
                    // we could configure the analyser: e.g. analyser.fftSize (for further infos read the spec)
                    frequencyData = new Uint8Array(analyser.frequencyBinCount);
                    setTimeout(function(){
                        setTimeout(function(){
                            audioMusic.play();
                        }, parseInt(document.getElementById('delayTime').value));
                        audio.play();
                        renderFrame();
                    }, 5000);
                }
            });
        </script>
        <style>
            audio{
                display:none;
            }
            #audioBoxesDiv{
                width:1024px;/*690px;*/
                overflow:hidden;
                height:255px;
                background-color:#FFF;
                position:absolute;
                top:30px;
                left:0;
            }
            .box{
                position:absolute;
                bottom:0;
                height:0;
                width:1px;
                background-color:#F00;
            }
        </style>
    </head>
    <body>
        <audio id="myAudio" src=""></audio>
        <audio id="msAudio" src=""></audio>
        <canvas id="audioBoxesDiv" width="1024" height="255"></canvas>
        <div style="position:absolute;top:0;left:0;z-index:100">
            <input id="fileInput" type="file" accept="audio/*"></input>
            <button onclick="audio.pause();audioMusic.pause();audio.currentTime = 0;audioMusic.currentTime = 0;setTimeout(function(){audioMusic.play()},400);audio.play();">&larr;</button>
            <button style="background-color:red;" onclick="audio.pause();audioMusic.pause();audio.currentTime -= 5;audioMusic.currentTime -= 5;audio.play();audioMusic.play();">&lt;&lt;</button>
            <button onclick="audio.pause();audioMusic.pause();">||</button>
            <button onclick="audio.play();audioMusic.play();">&gt;</button>
            <button style="background-color:red;" onclick="audio.pause();audioMusic.pause();audio.currentTime += 5;audioMusic.currentTime += 5;audio.play();audioMusic.play();">&gt;&gt;</button>
            <button onclick="window.location='visual.php'">New</button>&nbsp;
            DELAYms: <input id="delayTime" style="font-family:monospace;" value="200" size="4">&nbsp;
        </div>
    </body>
</html>