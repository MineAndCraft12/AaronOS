<!DOCTYPE html>
<html style="width:100%;height:100%;margin:0;padding:0;overflow:hidden">
    <head>
        <title>AaronOS Music Player</title>
        <link rel="stylesheet" href="../styleBeta.css">
        <link rel="stylesheet" href="style.css">
        <style id="aosCustomStyle"></style>
    </head>
    <body style="margin:0;padding:0;width:100%;height:100%;overflow:hidden;">
        <div class="winHTML" style="width:calc(100% - 8px);height:calc(100% - 6px);margin:0;padding:3px;padding-right:5px;overflow-y:auto;overflow-x:hidden">
            <div id="introduction">AaronOS Music Player: Your files are not uploaded; everything stays on your computer.<br><br>
            Load a folder of music files:<br>
            <input type="file" id="folderInput" webkitdirectory directory multiple onchange="loadFolder()"><br><br>
            Load one or multiple individual files:<br>
            <input type="file" id="fileInput" multiple accept="audio/*" onchange="loadFiles()"><br><br>
            Weird filetype or not recognized? Use this one:<br>
            <input type="file" id="fileWeirdInput" multiple onchange="loadWeirdFiles()"><br>
            (mobile users, you might need to use this one)<br><br>
            Or, hook up your microphone to play with the visualizers.<br>
            <button onclick="loadMicrophone()">Microphone</button></div>
            <div id="currentlyPlaying" class="disabled">No Song Playing</div>
            <div id="controls" class="disabled">
                <button onclick="play()">Play</button>
                <button onclick="pause()">Pause</button> |
                <button onclick="back()">Back</button>
                <button onclick="next()">Next</button> |
                <button onclick="shuffle()">Shuffle</button> |
                <button onclick="refresh()">New Songs</button>
                <br>
                Visualizer: <select id="visfield" onchange="setVis(this.value)"></select>
                <button onclick="toggleSmoke()">Smoke</button> |
                Color Scheme: <select id="colorfield" onchange="setColor(this.value)"></select> |
                Delay: <input style="width: 50px" type="number" id="delayinput" min="0" max="1" value="0.25" step="0.01" onchange="setDelay(this.value)"> |
                <button onclick="toggleFPS()">FPS</button>
            </div>
            <div id="progressContainer" class="disabled" onclick="setProgress(event)"><div id="progress"></div></div>
            <div id="songList" class="disabled"></div>
            <div id="visualizer" class="disabled">
                <canvas id="smokeCanvas"></canvas>
                <canvas id="visCanvas" onclick="toggleFullscreen()"></canvas>
            </div>
        </div>
    </body>
    <?php
        echo '<script defer src="script.js?ms='.round(microtime(true) * 1000).'"></script>'
    ?>
</html>