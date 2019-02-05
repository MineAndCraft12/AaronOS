<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Loading aOS Beta...</title>
    <!--<link rel="stylesheet" type="text/css" href="style.css">-->
    <?php
        echo '<link rel="stylesheet" type="text/css" href="styleBeta.css?ms='.round(microtime(true) * 1000).'">';
    ?>
    
    <link rel="icon" href="faviconBeta.ico" type="image/x-icon">
    <link rel="manifest" href="manifest.json">
    <style id="windowBorderStyle"></style>
    <style id="cursorStyle"></style>
    <style id="aosCustomStyle"></style>

    <!--
    <script defer src="html2canvas.js"></script>
    html2canvas(document.body, {onrendered:function(canvas){getId('winjsCa').style.backgroundImage = 'url(' + canvas.toDataURL('image/png') + ')'}})
    -->
</head>
<body style="background-color:#000" id="pagebody">
    <!-- OLD VERSION AT https://www.codecademy.com/MineAndCraft12/codebits/RfyrKj -->
    <!-- helps JS find scrollbar stuff -->
    <div id="findScrollSize" style="height:100px; width:100px; overflow:scroll;"></div>
    <!-- messaging relies on this -->
    <!--<iframe id="messagingframe" style="display:none"></iframe>-->
    <!-- Allows filesaving -->
    <!--
    <div id="mastersaveframediv" style="display:none">
        <iframe id="mastersaveframe" name="mastersaveframe" src=""></iframe>
        <form action="filesavernew.php" method="POST" target="mastersaveframe">
            <input name="k" value="">
            <input name="f" value="">
            <textarea name="c"></textarea>
            <input type="submit" id="savesubmit">
        </form>
    </div>
    -->
    <div id="bootLanguage" style="display:none"><?php
        if(isset($_COOKIE['keyword'])){
            if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_LANG.txt')){
                echo file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_LANG.txt');
            }
        }else{
            echo 'en';
        }
    ?></div>
    <!-- aOS computer screen and content inside on startup -->
    <div id="monitor" class="cursorDefault">
        <iframe id="liveBackground"></iframe>
        <iframe id="prlxBackground" onload="try{if(apps.settings.vars.prlxBackgroundEnabled){apps.settings.vars.prlxBackgroundUsable = 1}}catch(err){}" onunload="try{apps.settings.vars.prlxBackgroundUsable = 0}catch(err){}"></iframe>
        <div id="desktop" onclick="try{exitFlowMode()}catch(err){}" oncontextmenu="showEditContext(event)">
            <div id="hideall" onClick="toTop({dsktpIcon: 'DESKTOP'}, 1)"></div>
            <!--<div id="dsktpWidgets"></div>-->
            <p id="timesUpdated">Oops!</p>
            <div id="widgetMenu" style="opacity:0;pointer-events:none;bottom:-350px">
                <div id="widgetTitle"></div>
                <div id="widgetContent"></div>
                <div class="winExit cursorPointer" onClick="closeWidgetMenu()">x</div>
            </div>
            <div id="notifWindow" style="opacity:0;pointer-events:none;right:-350px">
                <div id="notifTitle">Notification</div>
                <div id="notifContent">Content</div>
                <div id="notifButtons"><button>Button 1</button> <button>Button 2</button></div>
                <img id="notifImage" src="appicons/aOS.png">
                <div class="winExit cursorPointer" onClick="getId('notifWindow').style.opacity='0';getId('notifWindow').style.pointerEvents='none';getId('notifWindow').style.right = '-350px';window.setTimeout(function(){apps.prompt.vars.checkPrompts();}, 300);apps.prompt.vars.currprompt[3](-1);">x</div>
            </div>
        </div>
        <!-- old window move/resize and icon move elements
        <div id="winmove" onclick="winmove(event)" onmousemove="winmoving(event)"></div>
        <div id="icomove" onclick="icomove(event)" onmousemove="icomoving(event)"></div>
        <div id="winres" onclick="winres(event)" onmousemove="winresing(event)"></div>
        -->
        <div id="winmove" class="cursorOpenHand" onmouseup="winmove(event)" onmousemove="winmoving(event)"></div>
        <div id="icomove" class="cursorOpenHand" onclick="icomove(event)" onmousemove="icomoving(event)"></div>
        <div id="icnmove" class="cursorOpenHand" onclick="icnmove(event)" onmousemove="icnmoving(event)"></div>
        <div id="winres" class="cursorOpenHand" onmouseup="winres(event)" onmousemove="winresing(event)"></div>
        <div id="windowFrameOverlay"></div>
        <div id="taskbar">
            <div id="tskbrAero" class="winAero"></div>
            <div id="tskbrBimg" class="winBimg"></div>
            <div id="time"></div>
            <div id="icons">Loading, please wait.</div>
        </div>
        <canvas id="aDE"></canvas>
        <div id="ctxMenu" onclick="getId('ctxMenu').style.display='none'"></div>
        <div id="customPointer"></div>
        <div id="screensaverLayer"></div>
        <!--<div id="aOSloadingBg"></div>-->
        <?php
            if(isset($_COOKIE['keyword'])){
                if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt')){
                    if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_DIRTYLOAD.txt')){
                        if(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_DIRTYLOAD.txt') == '1'){
                            echo '<div id="aOSloadingBg" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt').');opacity:0"></div><script defer>requestAnimationFrame(function(){getId("desktop").style.display = "";getId("taskbar").style.display = "";});window.dirtyLoadingEnabled = 1;</script>';
                        }else{
                            echo '<div id="aOSloadingBg" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt').');"></div>';
                        }
                    }else{
                        echo '<div id="aOSloadingBg" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt').');"></div>';
                    }
                }else{
                    echo '<div id="aOSloadingBg"></div>';
                }
            }else{
                echo '<div id="aOSloadingBg"></div>';
            }
        ?>
        <div id="aOSisLoading" class="cursorLoadLight">
            <div id="aOSisLoadingDiv">
                <h1>AaronOS</h1>
                <hr>
                <div id="aOSloadingInfoDiv">
                    <div id="aOSloadingInfo" class="liveElement" liveVar="finishedWaitingCodes / totalWaitingCodes * 100 + '%'" liveTarget="style.width">Initializing...</div>
                </div><br><br>
                &nbsp;<br>
                <a href="?safeMode"><button>Safe Mode</button></a><br><br>
                <button onclick="document.getElementById('aOSisLoading').style.display = 'none';document.getElementById('aOSloadingBg').style.display = 'none';document.getElementById('desktop').style.display = '';document.getElementById('taskbar').style.display = '';">Force Boot</button><br><br>
                <?php if(isset($_COOKIE['keyword'])){echo 'Your OS ID is <span id="aOSloadingKey">'.$_COOKIE['keyword'].'</span>';}else{echo 'You will get a new OS ID.';} ?>
                <img id="aosLoadingImage" src="appicons/ds/aOS.png" style="display:none"><!--<br>-->
            </div>
        </div>
    </div>
</body>
<!--<script defer src="script.js"></script>-->
<?php
    if(isset($_GET['dev'])){
        if($_GET['dev'] == '1' || $_GET['dev'] == 'true'){
            echo '<script defer src="scriptDev.js?ms='.round(microtime(true) * 1000).'"></script>';
        }else{
            echo '<script defer src="scriptBeta.js?ms='.round(microtime(true) * 1000).'"></script>';
        }
    }else{
        echo '<script defer src="scriptBeta.js?ms='.round(microtime(true) * 1000).'"></script>';
    }
?>

<?php
    if(isset($_GET['changeKey']) && isset($_GET['changePass'])){
        $changeKey = $_GET['changeKey'];
        $changePass = $_GET['changePass'];
    }
    echo '<script defer>';
    require 'fileloader.php';
    echo '</script>';
?>
</html>
<?php ob_end_flush(); ?>