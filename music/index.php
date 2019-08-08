<!DOCTYPE html>
<html style="width:100%;height:100%;margin:0;padding:0;overflow:hidden">
    <head>
        <title>AaronOS Music Player</title>
        <link rel="stylesheet" href="style.css">
        <script defer src="../aosTools.js"></script>
    </head>
    <body style="margin:0;padding:0;width:100%;height:100%;overflow:hidden;">
        <div class="winHTML" style="width:calc(100% - 8px);height:calc(100% - 6px);margin:0;padding:3px;padding-right:5px;overflow-y:auto;overflow-x:hidden">
            <div id="introduction">
                AaronOS Music Player: Your files are not uploaded; everything stays on your computer.<br><br>
                Load a folder of music files:<br>
                <input type="file" id="folderInput" webkitdirectory directory multiple onchange="loadFolder()"><br><br>
                Load one or multiple individual files:<br>
                <input type="file" id="fileInput" multiple accept="audio/*" onchange="loadFiles()"><br><br>
                Weird filetype or not recognized? Use this one:<br>
                <input type="file" id="fileWeirdInput" multiple onchange="loadWeirdFiles()"><br>
                (mobile users, you might need to use this one)<br><br>
                Or, hook up your microphone to play with the visualizers.<br>
                <button onclick="loadMicrophone()">Microphone</button><br><br><br>
                <i>Visualizations in the list whose thumbnails use the Rainbow color scheme are modeled after the AudioVision app for Android.<br>
                Those visualizations' designs were created by &Scaron;tefan Schindler.</i>
            </div>
            <div id="currentlyPlaying" class="disabled">No Song Playing</div>
            <div id="controls" class="disabled">
                <button onclick="play()">Play</button>
                <button onclick="pause()">Pause</button> |
                <button onclick="back()">Back</button>
                <button onclick="next()">Next</button> |
                <button onclick="shuffle()">Shuffle</button> |
                <button onclick="refresh()">New Songs</button> |
                Delay: <input style="width: 50px" type="number" id="delayinput" min="0" max="1" value="0.25" step="0.01" onchange="setDelay(this.value)">
                <br>
                Visuals: <select id="visfield" onchange="setVis(this.value)" onmousedown="requestAnimationFrame(()=>{this.blur()});" onclick="openVisualizerMenu();"></select>
                <button onclick="toggleSmoke()">Smoke</button> |
                Colors: <select id="colorfield" onchange="setColor(this.value)" onmousedown="requestAnimationFrame(()=>{this.blur()});" onclick="openColorMenu();"></select> |
                <button onclick="toggleFPS()">FPS</button> |
                <button onclick="togglePerformance()">Fast Mode</button>
            </div>
            <div id="progressContainer" class="disabled" onclick="setProgress(event)"><div id="progress"></div></div>
            <div id="songList" class="disabled"></div>
            <div id="visualizer" class="disabled">
                <canvas id="smokeCanvas"></canvas>
                <canvas id="visCanvas" onclick="toggleFullscreen()"></canvas>
            </div>
            <div id="selectOverlay" class="disabled">
                <div id="selectBackground" onclick="closeMenu()"></div>
                <div id="selectContent"></div>
            </div>
        </div>
    </body>
    <?php
        echo '<script defer src="script.js?ms='.round(microtime(true) * 1000).'"></script>'
    ?>
</html>