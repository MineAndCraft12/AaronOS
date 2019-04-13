<!DOCTYPE html>
<html>
    <head>
        <style>
            #letters{
                font-family:monospace;
                font-size:2em;
            }
            span{
                background-color:#CCC;
            }
        </style>
    </head>
    <body>
        <input id="file" placeholder="Enter filepath here"> <button onClick='loadSong()'>Load Song</button>
        <br><br>
        <button onClick="startRecord()">Start Recording</button> <button onClick="stopRecord()">Stop Recording</button> <button onClick="playRecord()">Play Recording</button> <button onClick="pausRecord()">Stop Playing</button>
        <br><br>
        <div id="letters">
            
        </div>
    </body>
    <script defer>
        function getId(trgt){
            return document.getElementById(trgt);
        }
        var recording = 0;
        var keysList = [];
        var playList = [];
        
        //function from stack overflow
        document.onkeypress = function(evt) {
            evt = evt || window.event;
            var charCode = evt.keyCode || evt.which;
            var charStr = String.fromCharCode(charCode);
            getId(charStr).style.backgroundColor = "#f00";
            window.setTimeout("getId('" + charStr + "').style.backgroundColor = '#CCC';", 30);
            if(recording){
                keysList.push([charStr, song.currentTime]);
            }
        };
        var song = null;
        function loadSong(){
            song = new Audio("music/" + getId("file").value + ".mp3");
            song.play();
            window.setTimeout('song.pause();song.currentTime = 0;', 3000);
        }
        function startRecord(){
            keysList = [];
            recording = 1;
            song.currentTime = 0;
            song.play();
        }
        function stopRecord(){
            recording = 0;
            song.pause();
            song.currentTime = 0;
        }
        var theInterval = null;
        function playRecord(){
            playList = [];
            for(var keys in keysList){
                playList.push(keysList[keys]);
            }
            if(recording){
                stopRecord();
            }
            song.currentTime = 0;
            song.play();
            theInterval = window.setInterval(recordFunction, 0);
        }
        function pausRecord(){
            window.clearInterval(theInterval);
            song.pause();
            song.currentTime = 0;
        }
        function recordFunction(){
            if(typeof playList[1] === undefined){
                alert("empty");
                pausRecord();
            }else if(playList[0][1] <= song.currentTime){
                getId(playList[0][0]).style.backgroundColor = "#F00";
                window.setTimeout("getId('" + playList[0][0] + "').style.backgroundColor = '#CCC';", 30);
                if(playList[1][1] === playList[0][1]){
                    getId(playList[1][0]).style.backgroundColor = "#F00";
                    window.setTimeout("getId('" + playList[1][0] + "').style.backgroundColor = '#CCC';", 30);
                    playList.shift();
                }
                playList.shift();
            }
        }
        
        var letters = "1234567890qwertyuiopasdfghjklzxcvbnm";
        for(var letter = 0; letter < 10; letter++){
            getId("letters").innerHTML += '<span id="' + letters[letter] + '">' + letters[letter] + '</span>&nbsp;';
        }
        getId("letters").innerHTML += '<br>&nbsp;';
        for(var letter = 10; letter < 20; letter++){
            getId("letters").innerHTML += '<span id="' + letters[letter] + '">' + letters[letter] + '</span>&nbsp;';
        }
        getId("letters").innerHTML += '<br>&nbsp;&nbsp;';
        for(var letter = 20; letter < 29; letter++){
            getId("letters").innerHTML += '<span id="' + letters[letter] + '">' + letters[letter] + '</span>&nbsp;';
        }
        getId("letters").innerHTML += '<br>&nbsp;&nbsp;&nbsp;';
        for(var letter = 29; letter < 36; letter++){
            getId("letters").innerHTML += '<span id="' + letters[letter] + '">' + letters[letter] + '</span>&nbsp;';
        }
    </script>
</html>