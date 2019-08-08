<!DOCTYPE html>
<html>
    <head>
        <title>aOS Minesweeper</title>
        <!--<link rel="stylesheet" href="customStyles/Windows98/aosCustomStyle.css">-->
        <link rel="stylesheet" href="" id="minecraft">
        <script defer src="aosTools.js"></script>
        <style>
            @font-face{
                font-family: "aosProFont";
                src:
                    url('ProFont/ProFontOnline.ttf') format('truetype'),
                    url('ProFont/ProFontOnline.woff') format('woff'),
                    url('ProFont/ProFontOnline.eot'),
                    url('ProFont/ProFontOnline.svg') format('svg');
            }
            div{
                position:absolute;
                overflow:hidden;
            }
            button:focus{
                outline:0;
            }
            #MSwShadow{
                pointer-events:none;
                white-space:nowrap;
            }
            #MSwShadow div{
                position:relative;
                display:inline-block;
                width:20px;
                height:20px;
                margin-bottom:-4px;
                background-size: contain;
            }
        </style>
    </head>
    <body style="overflow:hidden">
        <div class="winHTML" style="width:100%;height:100%;left:0;top:0;right:0;bottom:0;border:none;padding:8px;margin:0;overflow:visible">
            <div id="MSwField" style="margin-bottom:3px;"></div>
            <div id="MSwShadow" style="margin-bottom:3px;"></div>
            <div id="MSwControls">
                <button onclick="apps.minesweeper.vars.firstTurn = 1;apps.minesweeper.vars.newGame()">New Game</button>
                <button onclick="apps.minesweeper.vars.difficulty()">Difficulty</button>
                <button onclick="apps.minesweeper.vars.settings()">Settings</button>
                <button onclick="apps.minesweeper.vars.minecraftMode()">Minecraft Mode</button>
                <span style="font-family:aosProFont;font-size:12px;">B: <span id="MSwMines">0</span>, F: <span id="MSwFlags">0</span></span><br>
                Dig = Left Click | Flag = Right Click
            </div>
        </div>
    </body>
    <script defer>
        function getId(target){
            return document.getElementById(target);
        }

        window.aosTools_connectFailListener = function(){
            var aosStylesheet = document.createElement("link");
            aosStylesheet.rel = "stylesheet";
            aosStylesheet.href = "customStyles/Windows98/aosCustomStyle.css";
            document.head.prepend(aosStylesheet);
            console.log("FAILED TO CONNECT TO AOS - APPLYING WIN98 THEME");
        }
        var allSettings = ["grid", "clear", "safe", "easyClear", "xtrGraphics"];
        function recieveSettings(data){
            if(typeof data.content === "string"){
                var jsondata = JSON.parse(data.content);
                for(var i in allSettings){
                    if(typeof jsondata[allSettings[i]] === "number"){
                        apps.minesweeper.vars[allSettings[i]] = jsondata[allSettings[i]];
                    }
                }
            }

            requestAnimationFrame(function(){
                apps.minesweeper.vars.firstTurn = 1;
                apps.minesweeper.vars.newGame();
                console.log("RECIEVED SETTINGS INFORMATION");
                console.log(data.content);
            });
        }
        function saveSettings(){
            var saveData = {};
            for(var i in allSettings){
                saveData[allSettings[i]] = apps.minesweeper.vars[allSettings[i]];
            }
            aosTools.sendRequest({
                action: "fs:write_uf",
                targetFile: "aos_system/apps/minesweeper/settings",
                content: JSON.stringify(saveData)
            }, function(data){console.log(data.content)});
        }
        window.aosTools_connectListener = function(){
            requestAnimationFrame(function(){
                aosTools.sendRequest({
                    action: "fs:read_uf",
                    targetFile: "aos_system/apps/minesweeper/settings"
                }, recieveSettings);
            });
        }
        if(window.aosTools){
            aosTools.testConnection();
        }

        var minecraftMode = 0;
        var apps = {
            minesweeper: {
                vars: {
                    appInfo: 'The Minesweeper clone written for aOS.',
                    dims: [24, 24],
                    area: 576,
                    mines: 99,
                    flags: 0,
                    digs: 0,
                    minefield: [
                        [0, 0],
                        [0, 0]
                    ],
                    flagfield: [
                        [0, 0],
                        [0, 0]
                    ],
                    minecraftMode: function(){
                        getId("minecraft").setAttribute("href", "minecraftsweeper.css?" + Math.random());
                        minecraftMode = 1;
                    },
                    newGame: function(firstX, firstY){
                        if(this.firstTurn){
                            this.flagfield = [];
                            for(var i = 0; i < this.dims[1]; i++){
                                this.flagfield.push([]);
                                for(var j = 0; j < this.dims[0]; j++){
                                    this.flagfield[i].push(0);
                                }
                            }
                            
                            this.digs = 0;
                            this.area = this.dims[0] * this.dims[1];
                            this.minefield = [];
                            for(var i = 0; i < this.dims[1]; i++){
                                this.minefield.push([]);
                                for(var j = 0; j < this.dims[0]; j++){
                                    this.minefield[i].push(0);
                                }
                            }
                            this.flags = 0;
                        }else{
                            this.flags = 0;
                            while(this.flags < this.mines){
                                var tempX = Math.floor(Math.random() * this.dims[0]);
                                var tempY = Math.floor(Math.random() * this.dims[1]);
                                if(!this.minefield[tempY][tempX] && !(tempX === firstX && tempY === firstY && this.safe)){
                                    this.minefield[tempY][tempX] = 1;
                                    this.flags++;
                                }
                            }
                            this.flags = 0;
                        }
                        if(this.firstTurn){
                            var tempHTML = "<br><br><br>";
                            var tempGraphics = "";
                            if(this.xtrGraphics){
                                tempGraphics = "<br><br><br>";
                            }
                            for(var i in this.minefield){
                                tempHTML += "<div style='font-size:0;position:relative;white-space:nowrap;'>";
                                for(var j in this.minefield[i]){
                                    tempHTML += "<button id='MSwB" + j + "x" + i + "' onclick='apps.minesweeper.vars.checkBlock(" + j + "," + i + ")' oncontextmenu='apps.minesweeper.vars.flagBlock(" + j + "," + i + ");event.stopPropagation();return false;' style='width:20px;height:20px;'></button>";
                                    tempHTML += "<div id='MSwF" + j + "x" + i + "' style='position:relative;background:none !important;display:inline-block;width:20px;margin-left:-13px;margin-right:-7px;margin-bottom:1px;font-family:aosProFont;font-size:12px;pointer-events:none;'></div>";
                                    if(this.xtrGraphics){
                                        tempGraphics += "<div id='MSwS" + j + "x" + i + "'></div>";
                                    }
                                }
                                if(this.xtrGraphics){
                                    tempGraphics += "<br>";
                                }
                                tempHTML += "<div style='position:relative;background:none !important;display:inline-block;width:3px;margin:0px;height:3px;pointer-events:none;'></div></div>";
                            }
                            getId("MSwField").innerHTML = tempHTML;
                            getId("MSwMines").innerHTML = this.mines;
                            getId("MSwFlags").innerHTML = this.flags;
                            getId("MSwShadow").innerHTML = tempGraphics;
                        }
                    },
                    dark: 0,
                    darkMode: function(){
                        this.dark = Math.abs(this.dark - 1);
                        document.body.style.filter = 'invert(' + this.dark + ')';
                        if(this.dark){
                            document.body.style.backgroundColor = "#424242";
                        }else{
                            document.body.style.backgroundColor = "#BDBDBD";
                        }
                    },
                    firstTurn: 1,
                    difficulty: function(){
                        var tempScreenWidth = window.innerWidth;
                        var tempScreenHeight = window.innerHeight;
                        var btn = parseInt(prompt("Please choose a difficulty level:\n1: Beginner (8x8, 10)\n2: Intermediate (16x16, 40)\n3: Expert (24x24)\n4: Fill Screen\n5: Custom"));
                        if(btn){
                            switch(btn){
                                case 1:
                                    apps.minesweeper.vars.dims = [8, 8];
                                    apps.minesweeper.vars.mines = 10;
                                    apps.minesweeper.vars.firstTurn = 1;
                                    apps.minesweeper.vars.newGame();
                                    break;
                                case 2:
                                    apps.minesweeper.vars.dims = [16, 16];
                                    apps.minesweeper.vars.mines = 40;
                                    apps.minesweeper.vars.firstTurn = 1;
                                    apps.minesweeper.vars.newGame();
                                    break;
                                case 3:
                                    apps.minesweeper.vars.dims = [24, 24];
                                    apps.minesweeper.vars.mines = 99;
                                    apps.minesweeper.vars.firstTurn = 1;
                                    apps.minesweeper.vars.newGame();
                                    break;
                                case 4:
                                    apps.minesweeper.vars.dims = [
                                        Math.floor((tempScreenWidth - 16) / 20 - 1),
                                        Math.floor((tempScreenHeight - 70) / 20 - 1)
                                    ];
                                    apps.minesweeper.vars.mines = Math.round(apps.minesweeper.vars.dims[0] * apps.minesweeper.vars.dims[1] * 0.17);
                                    apps.minesweeper.vars.firstTurn = 1;
                                    apps.minesweeper.vars.newGame();
                                    break;
                                case 5:
                                    var width = prompt("How wide will your minefield be?");
                                    var height = prompt("How tall will your minefield be?");
                                    var numOfMines = prompt("How many bombs will your minefield contain?\n\nLeave blank for 17% fill.");
                                    if(parseInt(width) > 0 && parseInt(height) > 0 && parseInt(numOfMines || Math.round(parseInt(width) * parseInt(height) * 0.17)) < parseInt(width) * parseInt(height) && parseInt(numOfMines || Math.round(parseInt(width) * parseInt(height) * 0.17)) > 0){
                                        apps.minesweeper.vars.dims = [parseInt(width), parseInt(height)];
                                        apps.minesweeper.vars.mines = parseInt(numOfMines || Math.round(parseInt(width) * parseInt(height) * 0.17));
                                        apps.minesweeper.vars.firstTurn = 1;
                                        apps.minesweeper.vars.newGame();
                                    }else{
                                        alert("Failed to start game, one of your rules is invalid.\n\nWidth: " + parseInt(width) + "\nHeight: " + parseInt(height) + "\nBombs: " + parseInt(numOfMines || Math.round(parseInt(width) * parseInt(height) * 0.17)));
                                    }
                                    break;
                                default:
                                    alert("Error - unknown menu option. Oof.");
                            }
                        }
                    },
                    grid: 1,
                    clear: 1,
                    safe: 1,
                    easyClear: 1,
                    xtrGraphics: 1,
                    settings: function(){
                        var btn = parseInt(prompt("Please choose a setting to toggle:\n1: Omnipresent Grid (" + this.grid + ")\n2: Automatic Clearing (" + this.clear + ")\n3: Safe Mode (" + this.safe + ")\n4: Easy Clear (" + this.easyClear + ")\n5: Extreme Graphics (" + this.xtrGraphics + ") (will regenerate field)\n6: DEBUG"));
                        if(btn){
                            switch(btn){
                                case 1:
                                    this.grid = Math.abs(this.grid - 1);
                                    break;
                                case 2:
                                    this.clear = Math.abs(this.clear - 1);
                                    break;
                                case 3:
                                    this.safe = Math.abs(this.safe - 1);
                                    break;
                                case 4:
                                    this.easyClear = Math.abs(this.easyClear - 1);
                                    break;
                                case 5:
                                    this.xtrGraphics = Math.abs(this.xtrGraphics - 1);
                                    apps.minesweeper.vars.firstTurn = 1;
                                    apps.minesweeper.vars.newGame();
                                    break;
                                case 6:
                                    this.cheat();
                                    break;
                                default:
                                    alert("Error - unknown menu option. Oof.");
                            }
                            requestAnimationFrame(function(){
                                saveSettings();
                            });
                        }
                    },
                    flagBlock: function(x, y){
                        if(!this.firstTurn){
                            if(this.flagfield[y][x]){
                                this.flagfield[y][x] = 0;
                                if(!minecraftMode){
                                    getId("MSwF" + x + "x" + y).innerHTML = "";
                                }else{
                                    getId("MSwB" + x + "x" + y).className = "";
                                }
                                this.flags--;
                            }else{
                                this.flagfield[y][x] = 1;
                                if(!minecraftMode){
                                    getId("MSwF" + x + "x" + y).innerHTML = "F";
                                }else{
                                    getId("MSwB" + x + "x" + y).className = "mcflagoff";
                                }
                                this.flags++;
                            }
                            getId("MSwFlags").innerHTML = this.flags;
                            if(this.flags === this.mines){
                                this.showMines();
                            }
                        }
                    },
                    checkBlock: function(x, y){
                        if(this.firstTurn){
                            this.firstTurn = 0;
                            this.newGame(x, y);
                        }
                        if(this.flagfield[y][x]){
                            /*
                            this.flagfield[y][x] = 0;
                            getId("MSwF" + x + "x" + y).innerHTML = "";
                            this.flags--;
                            */
                        }else{
                            if(!minecraftMode){
                                getId("MSwB" + x + "x" + y).style.opacity = "0." + this.grid;
                            }else{
                                getId("MSwB" + x + "x" + y).className = "mcdirt";
                            }
                            getId("MSwB" + x + "x" + y).style.pointerEvents = "none";
                            if(this.minefield[y][x]){
                                this.showMines();
                                if(minecraftMode){
                                    getId("MSwB" + x + "x" + y).className = "mcstone";
                                }
                            }else{
                                this.digs++;
                                /*
                                if(this.digs === this.area - this.mines){
                                    this.showMines();
                                }else{
                                */
                                    var nearby = 0;
                                    try{
                                        if(this.minefield[y - 1][x - 1]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    try{
                                        if(this.minefield[y - 1][x]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    try{
                                        if(this.minefield[y - 1][x + 1]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    try{
                                        if(this.minefield[y][x - 1]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    try{
                                        if(this.minefield[y][x + 1]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    try{
                                        if(this.minefield[y + 1][x - 1]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    try{
                                        if(this.minefield[y + 1][x]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    try{
                                        if(this.minefield[y + 1][x + 1]){
                                            nearby++;
                                        }
                                    }catch(minefieldEdge){}
                                    if(nearby){
                                        getId("MSwF" + x + "x" + y).innerHTML = nearby;
                                        if(!minecraftMode){
                                            getId("MSwF" + x + "x" + y).style.opacity = "0.5";
                                        }
                                        if(this.easyClear){
                                            getId("MSwB" + x + "x" + y).style.pointerEvents = "";
                                            getId("MSwB" + x + "x" + y).setAttribute("onclick", "apps.minesweeper.vars.eClear(" + x + "," + y + ")");
                                            getId("MSwB" + x + "x" + y).setAttribute("oncontextmenu", "");
                                        }
                                    }else if(this.clear){
                                        if(this.blockModdable(x - 1, y - 1)){
                                            this.checkBlock(x - 1, y - 1);
                                        }
                                        if(this.blockModdable(x, y - 1)){
                                            this.checkBlock(x, y - 1);
                                        }
                                        if(this.blockModdable(x + 1, y - 1)){
                                            this.checkBlock(x + 1, y - 1);
                                        }
                                        if(this.blockModdable(x - 1, y)){
                                            this.checkBlock(x - 1, y);
                                        }
                                        if(this.blockModdable(x + 1, y)){
                                            this.checkBlock(x + 1, y);
                                        }
                                        if(this.blockModdable(x - 1, y + 1)){
                                            this.checkBlock(x - 1, y + 1);
                                        }
                                        if(this.blockModdable(x, y + 1)){
                                            this.checkBlock(x, y + 1);
                                        }
                                        if(this.blockModdable(x + 1, y + 1)){
                                            this.checkBlock(x + 1, y + 1);
                                        }
                                    }
                                    if(this.xtrGraphics){
                                        this.drawShadows(x, y, nearby);
                                    }
                                /*
                                }
                                */
                            }
                        }
                    },
                    eClear: function(x, y){
                        var nearby = 0;
                        try{
                            if(this.flagfield[y - 1][x - 1]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        try{
                            if(this.flagfield[y - 1][x]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        try{
                            if(this.flagfield[y - 1][x + 1]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        try{
                            if(this.flagfield[y][x - 1]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        try{
                            if(this.flagfield[y][x + 1]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        try{
                            if(this.flagfield[y + 1][x - 1]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        try{
                            if(this.flagfield[y + 1][x]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        try{
                            if(this.flagfield[y + 1][x + 1]){
                                nearby++;
                            }
                        }catch(minefieldEdge){}
                        if(nearby === parseInt(getId("MSwF" + x + "x" + y).innerHTML)){
                            if(this.blockModdable(x - 1, y - 1)){
                                this.checkBlock(x - 1, y - 1);
                            }
                            if(this.blockModdable(x, y - 1)){
                                this.checkBlock(x, y - 1);
                            }
                            if(this.blockModdable(x + 1, y - 1)){
                                this.checkBlock(x + 1, y - 1);
                            }
                            if(this.blockModdable(x - 1, y)){
                                this.checkBlock(x - 1, y);
                            }
                            if(this.blockModdable(x + 1, y)){
                                this.checkBlock(x + 1, y);
                            }
                            if(this.blockModdable(x - 1, y + 1)){
                                this.checkBlock(x - 1, y + 1);
                            }
                            if(this.blockModdable(x, y + 1)){
                                this.checkBlock(x, y + 1);
                            }
                            if(this.blockModdable(x + 1, y + 1)){
                                this.checkBlock(x + 1, y + 1);
                            }
                        }
                    },
                    blockModdable: function(x, y){
                        if(x > this.dims[0] - 1 || y > this.dims[1] - 1){
                            return false;
                        }
                        if(x < 0 || y < 0){
                            return false;
                        }
                        if(this.flagfield[y][x]){
                            return false;
                        }
                        if(getId("MSwB" + x + "x" + y).style.pointerEvents === "none"){
                            return false;
                        }
                        return true;
                    },
                    blockExists: function(x, y){
                        if(x > this.dims[0] - 1 || y > this.dims[1] - 1){
                            return false;
                        }
                        if(x < 0 || y < 0){
                            return false;
                        }
                        if(
                            getId("MSwB" + x + "x" + y).style.opacity === "0.1" ||
                            getId("MSwB" + x + "x" + y).style.opacity === "0" ||
                            getId("MSwB" + x + "x" + y).classList.contains("mcdirt")
                        ){
                            return false;
                        }
                        return true;
                    },
                    showMines: function(){
                        for(var i in this.minefield){
                            for(var j in this.minefield[i]){
                                getId("MSwB" + j + "x" + i).style.pointerEvents = "none";
                                if(this.minefield[i][j]){
                                    if(this.flagfield[i][j]){
                                        if(!minecraftMode){
                                            getId("MSwF" + j + "x" + i).innerHTML = "<b>F</b>";
                                            getId("MSwF" + j + "x" + i).style.color = "#0A0";
                                        }else{
                                            getId("MSwB" + j + "x" + i).className = "mcflagon";
                                        }
                                    }else{
                                        if(!minecraftMode){
                                            getId("MSwF" + j + "x" + i).innerHTML = "<b>B</b>";
                                            getId("MSwF" + j + "x" + i).style.color = "#F00";
                                        }else{
                                            getId("MSwB" + j + "x" + i).className = "mctnt";
                                        }
                                    }
                                }
                            }
                        }
                    },
                    cheat: function(){
                        for(var i in this.minefield){
                            for(var j in this.minefield[i]){
                                if(this.minefield[i][j]){
                                    getId("MSwB" + j + "x" + i).style.filter = "contrast(0.5) sepia(1) hue-rotate(-40deg) saturate(3)";
                                }
                            }
                        }
                    },
                    drawShadows: function(x, y, nearby, recursing){
                        var shadowStr = "";
                        for(var j = -1; j < 2; j++){
                            for(var i = -1; i < 2; i++){
                                if(this.blockExists(x + i, y + j)){
                                    shadowStr += "1";
                                }else{
                                    shadowStr += "0";
                                }
                            }
                        }
                        if(shadowStr[4] === "0"){
                            try{
                                getId("MSwS" + x + "x" + y).style.backgroundImage = "url(ms_shadows/s" + shadowStr + ".png)";
                            }catch(err){
                                console.log("could not make shadow");
                            }
                        }
                        if(!recursing){
                            for(var k = -1; k < 2; k++){
                                for(var l = -1; l < 2; l++){
                                    //if(i !== 0 && j !== 0){
                                        var canShadow = 0;
                                        try{
                                            var testVariable = this.minefield[k + i][l + j];
                                            canShadow = 1;
                                        }catch(err){
                                            
                                        }
                                        if(canShadow){
                                            this.drawShadows(x + k, y + l, 1, 1);
                                        }
                                    //}
                                }
                            }
                        }
                    }
                }
            }
        };
        
        // Automatically Set Difficulty to 4
        apps.minesweeper.vars.dims = [
            Math.floor((window.innerWidth - 16) / 20 - 1),
            Math.floor((window.innerHeight - 70) / 20 - 1)
        ];
        apps.minesweeper.vars.mines = Math.round(apps.minesweeper.vars.dims[0] * apps.minesweeper.vars.dims[1] * 0.17);
        apps.minesweeper.vars.firstTurn = 1;
        apps.minesweeper.vars.newGame();

        var allShadowImages = [];
        requestAnimationFrame(function(){
            var allImageUrls = ["s000000000.png", "s001000101.png", "s010001010.png", "s011001111.png", "s100100100.png", "s101101001.png", "s110101110.png", "s000000001.png", "s001000110.png", "s010001011.png", "s011100000.png", "s100100101.png", "s101101010.png", "s110101111.png", "s000000010.png", "s001000111.png", "s010001100.png", "s011100001.png", "s100100110.png", "s101101011.png", "s111000000.png", "s000000011.png", "s001001000.png", "s010001101.png", "s011100010.png", "s100100111.png", "s101101100.png", "s111000001.png", "s000000100.png", "s001001001.png", "s010001110.png", "s011100011.png", "s100101000.png", "s101101101.png", "s111000010.png", "s000000101.png", "s001001010.png", "s010001111.png", "s011100100.png", "s100101001.png", "s101101110.png", "s111000011.png", "s000000110.png", "s001001011.png", "s010100000.png", "s011100101.png", "s100101010.png", "s101101111.png", "s111000100.png", "s000000111.png", "s001001100.png", "s010100001.png", "s011100110.png", "s100101011.png", "s110000000.png", "s111000101.png", "s000001000.png", "s001001101.png", "s010100010.png", "s011100111.png", "s100101100.png", "s110000001.png", "s111000110.png", "s000001001.png", "s001001110.png", "s010100011.png", "s011101000.png", "s100101101.png", "s110000010.png", "s111000111.png", "s000001010.png", "s001001111.png", "s010100100.png", "s011101001.png", "s100101110.png", "s110000011.png", "s111001000.png", "s000001011.png", "s001100000.png", "s010100101.png", "s011101010.png", "s100101111.png", "s110000100.png", "s111001001.png", "s000001100.png", "s001100001.png", "s010100110.png", "s011101011.png", "s101000000.png", "s110000101.png", "s111001010.png", "s000001101.png", "s001100010.png", "s010100111.png", "s011101100.png", "s101000001.png", "s110000110.png", "s111001011.png", "s000001110.png", "s001100011.png", "s010101000.png", "s011101101.png", "s101000010.png", "s110000111.png", "s111001100.png", "s000001111.png", "s001100100.png", "s010101001.png", "s011101110.png", "s101000011.png", "s110001000.png", "s111001101.png", "s000100000.png", "s001100101.png", "s010101010.png", "s011101111.png", "s101000100.png", "s110001001.png", "s111001110.png", "s000100001.png", "s001100110.png", "s010101011.png", "s100000000.png", "s101000101.png", "s110001010.png", "s111001111.png", "s000100010.png", "s001100111.png", "s010101100.png", "s100000001.png", "s101000110.png", "s110001011.png", "s111100000.png", "s000100011.png", "s001101000.png", "s010101101.png", "s100000010.png", "s101000111.png", "s110001100.png", "s111100001.png", "s000100100.png", "s001101001.png", "s010101110.png", "s100000011.png", "s101001000.png", "s110001101.png", "s111100010.png", "s000100101.png", "s001101010.png", "s010101111.png", "s100000100.png", "s101001001.png", "s110001110.png", "s111100011.png", "s000100110.png", "s001101011.png", "s011000000.png", "s100000101.png", "s101001010.png", "s110001111.png", "s111100100.png", "s000100111.png", "s001101100.png", "s011000001.png", "s100000110.png", "s101001011.png", "s110100000.png", "s111100101.png", "s000101000.png", "s001101101.png", "s011000010.png", "s100000111.png", "s101001100.png", "s110100001.png", "s111100110.png", "s000101001.png", "s001101110.png", "s011000011.png", "s100001000.png", "s101001101.png", "s110100010.png", "s111100111.png", "s000101010.png", "s001101111.png", "s011000100.png", "s100001001.png", "s101001110.png", "s110100011.png", "s111101000.png", "s000101011.png", "s010000000.png", "s011000101.png", "s100001010.png", "s101001111.png", "s110100100.png", "s111101001.png", "s000101100.png", "s010000001.png", "s011000110.png", "s100001011.png", "s101100000.png", "s110100101.png", "s111101010.png", "s000101101.png", "s010000010.png", "s011000111.png", "s100001100.png", "s101100001.png", "s110100110.png", "s111101011.png", "s000101110.png", "s010000011.png", "s011001000.png", "s100001101.png", "s101100010.png", "s110100111.png", "s111101100.png", "s000101111.png", "s010000100.png", "s011001001.png", "s100001110.png", "s101100011.png", "s110101000.png", "s111101101.png", "s001000000.png", "s010000101.png", "s011001010.png", "s100001111.png", "s101100100.png", "s110101001.png", "s111101110.png", "s001000001.png", "s010000110.png", "s011001011.png", "s100100000.png", "s101100101.png", "s110101010.png", "s111101111.png", "s001000010.png", "s010000111.png", "s011001100.png", "s100100001.png", "s101100110.png", "s110101011.png", "s001000011.png", "s010001000.png", "s011001101.png", "s100100010.png", "s101100111.png", "s110101100.png", "s001000100.png", "s010001001.png", "s011001110.png", "s100100011.png", "s101101000.png", "s110101101.png"];
            for(var i in allImageUrls){
                allShadowImages[i] = new Image();
                allShadowImages[i].src = "ms_shadows/" + allImageUrls[i];
            }
            console.log(allImageUrls.length + " shadow images preloaded.");
        });
    </script>
</html>