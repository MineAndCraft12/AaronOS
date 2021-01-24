<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Loading aOS Beta...</title>
    <!--<link rel="stylesheet" type="text/css" href="style.css">-->
    <?php
        echo '<link rel="stylesheet" type="text/css" href="styleBeta.css?ms='.round(microtime(true) * 1000).'">';
    ?>
    
    <link rel="icon" href="favicon_si_beta.ico" type="image/x-icon">
    <link rel="manifest" href="manifest.json">
    <style id="windowBorderStyle"></style>
    <style id="smartIconStyle"></style>
    <style id="cursorStyle"></style>
    <style id="aosCustomStyle"></style>

    <svg>
        <defs>
            <filter id="svgblur">
                <feImage xlink:href="images/winimg_disp.png" result="dispImg" width="256px" height="256px" preserveAspectRatio="none"/>
                <feTile in="dispImg" result="dispMap" preserveAspectRatio="none"/>
                <feDisplacementMap id="svgDisplaceMap" in2="dispMap" in="SourceGraphic" scale="5" xChannelSelector="R" yChannelSelector="G"/>
            </filter>
        </defs>
    </svg>
</head>
<body style="background-color:#000" id="pagebody">
    <!-- helps JS find scrollbar stuff -->
    <div id="findScrollSize" style="height:100px; width:100px; overflow:scroll;"></div>
    <div id="bootLanguage" style="display:none"><?php
        if(isset($_COOKIE['keyword'])){
            if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aos_system/language.txt')){
                echo file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aos_system/language.txt');
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
            <div id="widgetMenu" class="darkResponsive" style="opacity:0;pointer-events:none;bottom:-350px">
                <div id="widgetTitle"></div>
                <div id="widgetContent"></div>
                <div class="winExit cursorPointer" onClick="closeWidgetMenu()">x</div>
            </div>
            <div id="notifContainer" style="opacity:0;pointer-events:none;right:-350px;">
                <div id="notifications">

                </div>
            </div>
            <!--
            <div id="notifWindow" class="darkResponsive" style="opacity:0;pointer-events:none;right:-350px">
                <div id="notifTitle">Notification</div>
                <div id="notifContent">Content</div>
                <div id="notifButtons"><button>Button 1</button> <button>Button 2</button></div>
                <img id="notifImage" src="appicons/aOS.png">
                <div class="winExit cursorPointer" onClick="getId('notifWindow').style.opacity='0';getId('notifWindow').style.pointerEvents='none';getId('notifWindow').style.right = '-350px';window.setTimeout(function(){apps.prompt.vars.checkPrompts();}, 300);apps.prompt.vars.currprompt[3](-1);">x</div>
            </div>
            -->
        </div>
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
        <div id="ctxMenu" onclick="getId('ctxMenu').style.display='none'" class="backdropFilterCtxMenu"></div>
        <div id="screensaverLayer"></div>
        <div id="petCursors"></div>
        <!--<div id="aOSloadingBg"></div>-->
        <?php
            if(isset($_COOKIE['keyword'])){
                if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aos_system/desktop/background_image.txt')){
                    if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aos_system/apps/settings/ugly_boot.txt')){
                        if(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aos_system/apps/settings/ugly_boot.txt') == '1'){
                            echo '<div id="aOSloadingBg" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aos_system/desktop/background_image.txt').');opacity:0"></div><script defer>requestAnimationFrame(function(){getId("desktop").style.display = "";getId("taskbar").style.display = "";});window.dirtyLoadingEnabled = 1;</script>';
                        }else{
                            echo '<div id="aOSloadingBg" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aos_system/desktop/background_image.txt').');"></div>';
                        }
                    }else{
                        echo '<div id="aOSloadingBg" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aos_system/desktop/background_image.txt').');"></div>';
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
                    <div id="aOSloadingInfo" class="liveElement" data-live-eval="finishedWaitingCodes / totalWaitingCodes * 100 + '%'" data-live-target="style.width">Initializing...</div>
                </div><br><br>
                &nbsp;<br>
                <a href="?safeMode"><button>Safe Mode</button></a><br><br>
                <button onclick="document.getElementById('aOSisLoading').style.display = 'none';document.getElementById('aOSloadingBg').style.display = 'none';document.getElementById('desktop').style.display = '';document.getElementById('taskbar').style.display = '';">Force Boot</button><br><br>
                <?php if(isset($_COOKIE['keyword'])){echo 'Your OS ID is <span id="aOSloadingKey">'.$_COOKIE['keyword'].'</span>';}else{echo 'You will get a new OS ID.';} ?>
                <img id="aosLoadingImage" src="appicons/ds/aOS.png" style="display:none"><!--<br>-->
            </div>
        </div>
    </div>
    <img style="display:none" id="bgSizeElement" src="images/beta1.png" onload="try{updateBgSize()}catch(err){}">
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
    echo '<script defer>window.lineOfTheDay = ';
    require 'lotd.php';
    echo '</script>';
    echo '<script defer>';
    require 'filepreloaderBeta.php';
    echo '</script>';
    //echo '<script>';
    //require 'test_ssl.php';
    //echo '</script>';
?>
</html>
<?php ob_end_flush(); ?>