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
            Choose a FOLDER to use in aOS Music Player. These files will NOT be uploaded to the AaronOS server.<br>
            <input type="file" id="folderInput" webkitdirectory directory multiple onchange="loadFolder()"><br><br>
            <div id="currentlyPlaying">No Song Playing</div>
            <div id="controls" class="disabled">
                <button onclick="play()">Play</button>
                <button onclick="pause()">Pause</button> |
                <button onclick="back()">Back</button>
                <button onclick="next()">Next</button> |
                <button onclick="shuffle()">Shuffle</button> |
                <button onclick="refresh()">Refresh</button>
            </div>
            <div id="progressContainer"><div id="progress"></div></div>
            <div id="songList"></div>
        </div>
    </body>
    <script defer src="script.js"></script>
</html>