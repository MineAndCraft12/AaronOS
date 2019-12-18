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
                <h1 style="text-align:center">AaronOS Music</h1>
                <div style="text-align:center; position:relative; margin-top:-16px; margin-bottom:-16px;">
                    <div class="selectIcon">
                        <input type="file" id="folderInput" webkitdirectory directory multiple onchange="loadFolder()">
                        <img src="icons/folder.png" style="pointer-events:none;">
                        <span>Folder</span>
                    </div>
                    <div class="selectIcon">
                        <input type="file" id="fileInput" multiple accept="audio/*" onchange="loadFiles()">
                        <img src="icons/files.png" style="pointer-events:none;">
                        <span>Files</span>
                    </div>
                    <div class="selectIcon">
                        <input type="file" id="fileWeirdInput" multiple onchange="loadWeirdFiles()">
                        <img src="icons/mobile.png" style="pointer-events:none;">
                        <span>Mobile</span>
                    </div>
                </div>
                <div style="text-align:center;max-width:500px;margin:auto;">
                    Or, hook up your microphone to play with the visualizers.<br><br>
                    <button onclick="loadMicrophone()">Microphone</button><br><br><br>
                    <i>
                        Your files are not uploaded and will not leave your device.
                        <br><br>
                        Visualizations in the list whose thumbnails use the Rainbow color scheme are modeled after the AudioVision app for Android.<br><br>
                        The AudioVision designs were created by &Scaron;tefan Schindler.
                    </i>
                </div>
            </div>
            <div id="currentlyPlaying" class="disabled">No Song Playing</div>
            <div id="controls" class="disabled">
                <button onclick="refresh()">Reset</button> |
                <button onclick="shuffle()">Shuffle</button> |
                <button onclick="back()"><b>|</b>&#9668;</button>
                <button onclick="if(audio.paused){play();}else{pause();}" id="playbutton">&#9658;</button>
                <button onclick="next()">&#9658;<b>|</b></button> |
                Delay: <input style="width: 50px" type="number" id="delayinput" min="0" max="1" value="0.25" step="0.01" onchange="setDelay(this.value)"> |
                <button onclick="toggleFPS()">Debug</button>
                <button onclick="togglePerformance()">Fast Mode</button><span style="display:none" id="tskbrModeButton"> |
                <button onclick="toggleTaskbarMode()">Taskbar Mode</button></span>
                <br>
                Visuals: <select id="visfield" onchange="setVis(this.value)" onmousedown="if(!taskbarMode){requestAnimationFrame(()=>{this.blur()});}" onclick="if(!taskbarMode){openVisualizerMenu();}"></select>
                <button onclick="toggleSmoke()">Smoke</button> |
                Colors: <select id="colorfield" onchange="setColor(this.value)" onmousedown="if(!taskbarMode){requestAnimationFrame(()=>{this.blur()});}" onclick="if(!taskbarMode){openColorMenu();}"></select> |
                Mod: <select id="modfield" onchange="selectMod(this.value)" onmousedown="if(!taskbarMode){requestAnimationFrame(()=>{this.blur()});}" onclick="if(!taskbarMode){openModMenu();}">
                    <option value="null">None</option>
                </select>
            </div>
            <div id="progressContainer" class="disabled" onclick="setProgress(event)"><div id="progress"></div></div>
            <div id="songList" class="disabled"></div>
            <div id="visualizer" class="disabled">
                <canvas id="smokeCanvas"></canvas>
                <div id="smokeScreen1"></div>
                <div id="smokeScreen2"></div>
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