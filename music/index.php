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
            <div id="introduction">Choose a FOLDER to use in aOS Music Player. These files will NOT be uploaded to the AaronOS server.<br>
            <input type="file" id="folderInput" webkitdirectory directory multiple onchange="loadFolder()"><br><br>
            Or, if you want to select individual files, do that here.<br>
            <input type="file" id="fileInput" multiple accept="audio/*" onchange="loadFiles()"><br><br>
            If you have a weird filetype or I'm not detecting your files, try and force play it here:<br>
            <input type="file" id="fileWeirdInput" multiple onchange="loadWeirdFiles()"></div>
            <div id="currentlyPlaying" class="disabled">No Song Playing</div>
            <div id="controls" class="disabled">
                <button onclick="play()">Play</button>
                <button onclick="pause()">Pause</button> |
                <button onclick="back()">Back</button>
                <button onclick="next()">Next</button> |
                <button onclick="shuffle()">Shuffle</button> |
                <button onclick="refresh()">Refresh</button>
                <br>
                Visualizer: <select id="visfield" onchange="setVis(this.value)"></select> |
                Color Scheme: <select id="colorfield" onchange="setColor(this.value)"></select> |
                Delay: <input style="width: 50px" type="number" id="delayinput" min="0" max="1" value="0.17" step="0.01" onchange="setDelay(this.value)"> |
                <button onclick="toggleFPS()">FPS</button>
            </div>
            <div id="progressContainer" class="disabled"><div id="progress"></div></div>
            <div id="songList" class="disabled"></div>
            <div id="visualizer" class="disabled"><canvas id="visCanvas" onclick="toggleFullscreen()"></canvas></div>
        </div>
    </body>
    <?php
        echo '<script defer src="script.js?ms='.round(microtime(true) * 1000).'"></script>'
    ?>
</html>