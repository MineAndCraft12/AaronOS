<!DOCTYPE html>
<html>
    <head>
        <title>aOS Music Mixer</title>
        <script defer>
            var audio1;
            var audio2;
            var loadsReady;
            function startSong(){
                audio1 = document.getElementById('audio1');
                audio2 = document.getElementById('audio2');
                audio1.src = document.getElementById('song1').value;
                audio2.src = document.getElementById('song2').value;
                loadsReady = 0;
                audio1.oncanplaythrough = function(){
                    if(loadsReady){
                        audio1.play();
                        audio2.play();
                    }else{
                        loadsReady = 1;
                    }
                };
                audio2.oncanplaythrough = function(){
                    if(loadsReady){
                        audio1.play();
                        audio2.play();
                    }else{
                        loadsReady = 1;
                    }
                };
            }
        </script>
        <style>
            audio{
                display:none;
            }
        </style>
    </head>
    <body>
        Song 1: <input id="song1" value="music/.mp3"><br>
        Song 2: <input id="song2" value="music/.mp3"><br>
        <button onclick="startSong()">Play</button>
        <audio id="audio1"></audio>
        <audio id="audio2"></audio>
    </body>
</html>