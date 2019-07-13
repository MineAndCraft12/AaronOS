window.onerror = function(errorMsg, url, lineNumber){
    alert("oof, u got a error\n\n" + url + '[' + lineNumber + ']:\n' + errorMsg)
}

function getId(target){
    return document.getElementById(target);
}

var audio = new Audio();
var audioDuration = 1;
function updateProgress(){
    progressBar.style.width = audio.currentTime / audioDuration * 100 + "%";
    progressBar.style.backgroundColor = getColor(audio.currentTime / audioDuration * 255);
    requestAnimationFrame(updateProgress);
}
requestAnimationFrame(updateProgress);

/*global AudioContext*/

var audioContext;
var mediaSource;

var delayNode;
function setDelay(newDelay){
    delayNode.delayTime.value = (newDelay || 0);
}

var analyser;

var visData;

var microphone;

var folderInput = getId("folderInput");
var fileInput = getId("fileInput");
var fileWeirdInput = getId("fileWeirdInput");
var songList = getId("songList");
var progressBar = getId("progress");
var currentlyPlaying = getId("currentlyPlaying");

var files = [];
var filesAmount = 0;
var fileNames = [];
var filesLength = 0;

var supportedFormats = ['aac', 'aiff', 'wav', 'm4a', 'mp3', 'amr', 'au', 'weba', 'oga', 'wma', 'flac', 'ogg', 'opus', 'webm'];

var URL;

function listSongs(){
    var str = "";
    for(var i in fileNames){
        str += '<div id="song' + i + '" onclick="selectSong(' + i + ')">' + fileNames[i][1] + ": " + fileNames[i][3] + fileNames[i][0] + '</div>';
    }
    songList.innerHTML = str;
}

function loadFolder(event){
    audio.pause();
    currentSong = -1;
    files = folderInput.files;
    filesAmount = files.length;
    filesLength = 0;
    fileNames = [];
    for(var i = 0; i < filesAmount; i++){
        if(files[i].type.indexOf("audio/") === 0){
            var fileName = files[i].name.split('.');
            if(fileName[fileName.length - 1] !== 'mid' && fileName[fileName.length - 1] !== 'midi'){
                var filePath = '';
                if(files[i].webkitRelativePath){
                    filePath = files[i].webkitRelativePath.split('/');
                    filePath.pop();
                    filePath.shift();
                    if(filePath.length > 0){
                        filePath = filePath.join(": ");
                        filePath += ': ';
                    }else{
                        filePath = '';
                    }
                }
                filesLength++;
                if(supportedFormats.indexOf(fileName[fileName.length - 1]) > -1){
                    fileName.pop();
                }
                fileNames.push([fileName.join('.'), i, URL.createObjectURL(files[i]), filePath]);
            }
        }
    }
    listSongs();
    var disabledElements = document.getElementsByClassName('disabled');
    while(disabledElements.length > 0){
        disabledElements[0].classList.remove('disabled');
    }
    if(!smokeEnabled){
        smokeElement.classList.add("disabled");
    }
    
    audioContext = new AudioContext();
    mediaSource = audioContext.createMediaElementSource(audio);
    
    delayNode = audioContext.createDelay();
    delayNode.delayTime.value = 0.25;
    delayNode.connect(audioContext.destination);
    
    analyser = audioContext.createAnalyser();
    analyser.fftSize = 32768;
    analyser.minDecibels = -70;
    analyser.smoothingTimeConstant = 0;
    mediaSource.connect(analyser);
    analyser.connect(delayNode);
    
    visData = new Uint8Array(analyser.frequencyBinCount);
    
    getId("introduction").classList.add('disabled');
    getId("visualizer").classList.add('disabled');
    setVis("none");
    
    winsize = [window.innerWidth, window.innerHeight];
    if(fullscreen){
        size = [window.innerWidth, window.innerHeight];
    }else{
        size = [window.innerWidth - 8, window.innerHeight - 81];
    }
    getId("visCanvas").width = size[0];
    getId("visCanvas").height = size[1];
    
    requestAnimationFrame(globalFrame);
}

function loadFiles(event){
    audio.pause();
    currentSong = -1;
    files = fileInput.files;
    filesAmount = files.length;
    filesLength = 0;
    fileNames = [];
    for(var i = 0; i < filesAmount; i++){
        if(files[i].type.indexOf("audio/") === 0){
            var fileName = files[i].name.split('.');
            if(fileName[fileName.length - 1] !== 'mid' && fileName[fileName.length - 1] !== 'midi'){
                var filePath = '';
                if(files[i].webkitRelativePath){
                    filePath = files[i].webkitRelativePath.split('/');
                    filePath.pop();
                    filePath.shift();
                    filePath.join(": ");
                    filePath += ': ';
                }
                filesLength++;
                if(supportedFormats.indexOf(fileName[fileName.length - 1]) > -1){
                    fileName.pop();
                }
                fileNames.push([fileName.join('.'), i, URL.createObjectURL(files[i]), filePath]);
            }
        }
    }
    listSongs();
    var disabledElements = document.getElementsByClassName('disabled');
    while(disabledElements.length > 0){
        disabledElements[0].classList.remove('disabled');
    }
    
    audioContext = new AudioContext();
    mediaSource = audioContext.createMediaElementSource(audio);
    
    delayNode = audioContext.createDelay();
    delayNode.delayTime.value = 0.25;
    delayNode.connect(audioContext.destination);
    
    analyser = audioContext.createAnalyser();
    analyser.fftSize = 32768;
    analyser.minDecibels = -70;
    analyser.smoothingTimeConstant = 0;
    mediaSource.connect(analyser);
    analyser.connect(delayNode);
    
    visData = new Uint8Array(analyser.frequencyBinCount);
    
    getId("introduction").classList.add('disabled');
    getId("visualizer").classList.add('disabled');
    setVis("none");
    
    winsize = [window.innerWidth, window.innerHeight];
    if(fullscreen){
        size = [window.innerWidth, window.innerHeight];
    }else{
        size = [window.innerWidth - 8, window.innerHeight - 81];
    }
    getId("visCanvas").width = size[0];
    getId("visCanvas").height = size[1];
    
    requestAnimationFrame(globalFrame);
}

function loadWeirdFiles(event){
    audio.pause();
    currentSong = -1;
    files = fileWeirdInput.files;
    filesAmount = files.length;
    filesLength = 0;
    fileNames = [];
    for(var i = 0; i < filesAmount; i++){
        var fileName = files[i].name.split('.');
        var filePath = '';
        if(files[i].webkitRelativePath){
            filePath = files[i].webkitRelativePath.split('/');
            filePath.pop();
            filePath.shift();
            filePath.join(": ");
            filePath += ': ';
        }
        filesLength++;
        if(supportedFormats.indexOf(fileName[fileName.length - 1]) > -1){
            fileName.pop();
        }
        fileNames.push([fileName.join('.'), i, URL.createObjectURL(files[i]), filePath]);
    }
    listSongs();
    var disabledElements = document.getElementsByClassName('disabled');
    while(disabledElements.length > 0){
        disabledElements[0].classList.remove('disabled');
    }
    
    audioContext = new AudioContext();
    mediaSource = audioContext.createMediaElementSource(audio);
    
    delayNode = audioContext.createDelay();
    delayNode.delayTime.value = 0.25;
    delayNode.connect(audioContext.destination);
    
    analyser = audioContext.createAnalyser();
    analyser.fftSize = 32768;
    analyser.minDecibels = -70;
    analyser.smoothingTimeConstant = 0;
    mediaSource.connect(analyser);
    analyser.connect(delayNode);
    
    visData = new Uint8Array(analyser.frequencyBinCount);
    
    getId("introduction").classList.add('disabled');
    getId("visualizer").classList.add('disabled');
    setVis("none");
    
    winsize = [window.innerWidth, window.innerHeight];
    if(fullscreen){
        size = [window.innerWidth, window.innerHeight];
    }else{
        size = [window.innerWidth - 8, window.innerHeight - 81];
    }
    getId("visCanvas").width = size[0];
    getId("visCanvas").height = size[1];
    
    requestAnimationFrame(globalFrame);
}

var microphoneActive = 0;

function loadMicrophone(event){
    audio.pause();
    currentSong = -1;

    var disabledElements = document.getElementsByClassName('disabled');
    while(disabledElements.length > 0){
        disabledElements[0].classList.remove('disabled');
    }
    if(!smokeEnabled){
        smokeElement.classList.add("disabled");
    }
    
    audioContext = new AudioContext();
    
    analyser = audioContext.createAnalyser();
    analyser.fftSize = 32768;
    analyser.minDecibels = -70;
    analyser.smoothingTimeConstant = 0;
    
    visData = new Uint8Array(analyser.frequencyBinCount);
    
    getId("introduction").classList.add('disabled');
    getId("visualizer").classList.add('disabled');
    setVis("none");
    
    winsize = [window.innerWidth, window.innerHeight];
    if(fullscreen){
        size = [window.innerWidth, window.innerHeight];
    }else{
        size = [window.innerWidth - 8, window.innerHeight - 81];
    }
    getId("visCanvas").width = size[0];
    getId("visCanvas").height = size[1];

    navigator.webkitGetUserMedia({audio:true}, function(stream){
        microphone = audioContext.createMediaStreamSource(stream);
        microphone.connect(analyser);
    }, function(){alert('error');});
    
    microphoneActive = 1;
    
    requestAnimationFrame(globalFrame);
}

var currentSong = -1;

function selectSong(id){
    currentSong = id;
    audio.pause();
    audio.currentTime = 0;
    audio.src = fileNames[id][2];
    getId("currentlyPlaying").innerHTML = fileNames[id][1] + ": " + fileNames[id][0];
    document.title = fileNames[id][0] + " | aOS Music Player";
    if(iframeMode){
        window.top.postMessage({
            messageType: "request",
            action: "appWindow:set_caption",
            content: fileNames[id][0] + " | Music Player",
            conversation: "set_caption"
        });
    }
    try{
        document.getElementsByClassName("selected")[0].classList.remove("selected");
    }catch(err){
        // no song is selected
    }
    getId("song" + id).classList.add("selected");
}

function play(){
    if(!microphoneActive){
        if(currentSong === -1){
            selectSong(0);
        }else{
            audio.play();
        }
    }
}
function pause(){
    if(!microphoneActive){
        audio.pause();
    }
}

function firstPlay(){
    audioDuration = audio.duration;
    play();
}
audio.addEventListener("canplaythrough", firstPlay);

function back(){
    if(!microphoneActive){
        if(audio.currentTime < 3){
            currentSong--;
            if(currentSong < 0){
                currentSong = fileNames.length - 1;
            }
            selectSong(currentSong);
        }else{
            audio.currentTime = 0;
        }
    }
}
function next(){
    if(!microphoneActive){
        currentSong++;
        if(currentSong > fileNames.length - 1){
            currentSong = 0;
        }
        selectSong(currentSong);
    }
}
audio.addEventListener("ended", next);

// courtesy Stack Overflow
function shuffleArray(array){
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
}

function shuffle(){
    if(!microphoneActive){
        audio.pause();
        audio.currentTime = 0;
        currentSong = 0;
        shuffleArray(fileNames);
        listSongs();
        selectSong(0);
    }
}

function refresh(){
    window.location = "?refresh=" + (new Date()).getTime();
}

function getStyleInfo(event){
    try{
        console.log(event.data);
        if(event.data.conversation === "darkmode"){
            if(event.data.content === true){
                document.body.classList.add("darkMode");
            }
        }else if(event.data.conversation === "customstyle"){
            getId("aosCustomStyle").innerHTML = event.data.content.customStyle;
            for(var i in event.data.content.styleLinks){
                if(event.data.content.styleLinks[i][1] === "link"){
                    var customElement = document.createElement("link");
                    customElement.setAttribute("rel", "stylesheet");
                    customElement.href = event.data.content.styleLinks[i][0];
                    document.head.appendChild(customElement);
                }else if(event.data.content.styleLinks[i][1] === "literal"){
                    var customElement = document.createElement("style");
                    customElement.innerHTML = event.data.content.styleLinks[i][0];
                    document.head.appendChild(customElement);
                }
            }
        }
    }catch(err){
        console.log("something done did the big oof when i tried to do the style thingy or whatever...");
        console.log(err);
    }
}
window.addEventListener("message", getStyleInfo);

var iframeMode = false;
if(window.self !== window.top){
    iframeMode = true;
    window.top.postMessage({
        messageType: "request",
        action: "getstyle:darkmode",
        conversation: "darkmode"
    });
    window.top.postMessage({
        messageType: "request",
        action: "getstyle:customstyle",
        conversation: "customstyle"
    });
}

var winsize = [window.innerWidth, window.innerHeight];
var size = [window.innerWidth - 8, window.innerHeight - 81];
var fullscreen = 0;
function toggleFullscreen(){
    if(fullscreen){
        size = [window.innerWidth - 8, window.innerHeight - 81];
        getId("visualizer").style.border = "";
        getId("visualizer").style.bottom = "";
        getId("visualizer").style.left = "";
        getId("visualizer").style.width = "";
        getId("visualizer").style.height = "";
        getId("visCanvas").width = size[0];
        getId("visCanvas").height = size[1];
        document.body.style.background = '';
        fullscreen = 0;
        if(currVis !== "none"){
            resizeSmoke();
            if(vis[currVis].sizechange){
                vis[currVis].sizechange();
            }
        }
    }else{
        size = [window.innerWidth, window.innerHeight];
        getId("visualizer").style.border = "none";
        getId("visualizer").style.bottom = "0";
        getId("visualizer").style.left = "0";
        getId("visualizer").style.width = "100%";
        getId("visualizer").style.height = "100%";
        getId("visCanvas").width = size[0];
        getId("visCanvas").height = size[1];
        document.body.style.background = '#000';
        fullscreen = 1;
        if(currVis !== "none"){
            resizeSmoke();
            if(vis[currVis].sizechange){
                vis[currVis].sizechange();
            }
        }
    }
}

var fps = 0;
var currFPS = 0;
var lastSecond = 0;
var fpsEnabled = 0;
function toggleFPS(){
    fpsEnabled = Math.abs(fpsEnabled - 1);
}

var canvasElement = getId("visCanvas");
var canvas = canvasElement.getContext("2d");

var smokeElement = getId("smokeCanvas");
var smoke = smokeElement.getContext("2d");

function globalFrame(){
    requestAnimationFrame(globalFrame);
    if(winsize[0] !== window.innerWidth || winsize[1] !== window.innerHeight){
        winsize = [window.innerWidth, window.innerHeight];
        if(fullscreen){
            size = [window.innerWidth, window.innerHeight];
        }else{
            size = [window.innerWidth - 8, window.innerHeight - 81];
        }
        getId("visCanvas").width = size[0];
        getId("visCanvas").height = size[1];
        if(currVis !== "none"){
            if(smokeEnabled){
                resizeSmoke();
            }
            if(vis[currVis].sizechange){
                vis[currVis].sizechange();
            }
        }
    }
    if(currVis !== "none"){
        analyser.getByteFrequencyData(visData);
        if(smokeEnabled){
            smokeFrame();
        }
        vis[currVis].frame();
        if(fpsEnabled){
            fps++;
            var currSecond = (new Date().getSeconds());
            if(currSecond !== lastSecond){
                currFPS = fps;
                fps = 0;
                lastSecond = currSecond;
            }
            canvas.font = '12px aosProFont, monospace';
            canvas.fillStyle = 'rgba(0, 0, 0, 0.5)';
            canvas.fillRect(1, 1, 14, 9);
            canvas.fillStyle = '#FFF';
            canvas.fillText(String(currFPS), 2.5, 9);
        }
    }
}

var colors = {
    bluegreenred: {
        name: "Default",
        func: function(amount){
            return 'rgba(' +
                ((amount > 200) * ((amount - 200) * 4.6)) + ',' +
                (amount - ((amount > 220) * ((amount - 220) * 7.2))) + ',' +
                (255 - amount) + ',' +
                (amount / 255) + ')';
        }
    },
    beta: {
        name: "Beta",
        func: function(amount){
            return 'rgb(' +
                ((amount > 200) * ((amount - 200) * 4.6)) + ',' +
                (amount - ((amount > 220) * ((amount - 220) * 7.2))) + ',' +
                (255 - amount) + ')';
        }
    },
    alpha: {
        name: "Alpha",
        func: function(amount){
            return 'rgb(0,' + amount + ',' + (255 - amount) + ')';
        }
    },
    intensityGlow: {
        name: "Intensity",
        func: function(amount){
            return 'rgba(' +
                ((amount >= 127) * 255 + (amount < 127) * (amount * 2)) + ',' +
                ((amount < 127) * 255 + (amount >= 127) * ((254.5 - amount) * 2)) + ',' +
                '0,' + (amount / 255) + ')';
        }
    },
    intensity: {
        name: "Intensity Solid",
        func: function(amount){
            return 'rgb(' +
                ((amount >= 127) * 255 + (amount < 127) * (amount * 2)) + ',' +
                ((amount < 127) * 255 + (amount >= 127) * ((254.5 - amount) * 2)) + ',' +
                '0)';
        }
    },
    'SEPARATOR_THEMES" disabled="': {
        name: "---------------",
        func: function(){
            return '#000';
        }
    },
    fire: {
        name: "Fire",
        func: function(amount){
            //return 'rgba(' +
            //    amount + ',' +
            //    ((amount > 200) * amount * 0.25 + (amount > 127) * amount * 0.5 + (amount <= 127) * amount * 0.1) + ',0, ' + (amount / 255) + ')';
            return 'rgba(255,' +
                (Math.pow(amount, 2) / 255) + ',0,' +
                (amount / 255) + ')';
        }
    },
    prideGlow: {
        name: "Pride",
        func: function(amount){
            return 'rgb(' +
                (
                    (amount < 95) * 255 +
                    (amount >= 95 && amount < 159) * (159 - amount) * this.divide255by64 +
                    (amount >= 207) * ((207 - amount) * -1) * this.divide255by96
                ) + ',' +
                (
                    (amount < 95) * amount * this.divide255by96 +
                    (amount >= 95 && amount < 159) * 255 +
                    (amount >= 159 && amount < 207) * (207 - amount) * this.divide255by48
                ) + ',' +
                (
                    (amount >= 159 && amount < 207) * ((159 - amount) * -1) * this.divide255by48 +
                    (amount >= 207) * 255
                ) + ',' + (amount / 255) + ')';
        },
        divide255by96: 255 / 96,
        divide255by64: 255 / 64,
        divide255by48: 255 / 48
    },
    pride: {
        name: "Pride Solid",
        func: function(amount){
            return 'rgb(' +
                (
                    (amount < 95) * 255 +
                    (amount >= 95 && amount < 159) * (159 - amount) * this.divide255by64 +
                    (amount >= 207) * ((207 - amount) * -1) * this.divide255by96
                ) + ',' +
                (
                    (amount < 95) * amount * this.divide255by96 +
                    (amount >= 95 && amount < 159) * 255 +
                    (amount >= 159 && amount < 207) * (207 - amount) * this.divide255by48
                ) + ',' +
                (
                    (amount >= 159 && amount < 207) * ((159 - amount) * -1) * this.divide255by48 +
                    (amount >= 207) * 255
                ) + ')';
        },
        divide255by96: 255 / 96,
        divide255by64: 255 / 64,
        divide255by48: 255 / 48
    },
    'SEPARATOR_GLOWS" disabled="': {
        name: "---------------",
        func: function(){
            return '#000';
        }
    },
    whiteglow: {
        name: "White Glow",
        func: function(amount){
            return 'rgba(255, 255, 255, ' + (amount / 255) + ')';
        }
    },
    redglow: {
        name: "Red Glow",
        func: function(amount){
            return 'rgba(255,0,0,' + (amount / 255) + ')';
        }
    },
    greenglow: {
        name: "Green Glow",
        func: function(amount){
            return 'rgba(0,255,0,' + (amount / 255) + ')';
        }
    },
    blueglow: {
        name: "Blue Glow",
        func: function(amount){
            return 'rgba(0,0,255,' + (amount / 255) + ')';
        }
    },
    electricglow: {
        name: "Electric Glow",
        func: function(amount){
            return 'rgba(0,255,255,' + (amount / 255) + ')';
        }
    },
    indigoglow: {
        name: "Indigo Glow",
        func: function(amount){
            return 'rgba(75, 0, 130, ' + (amount / 255) + ')';
        }
    },
    'SEPARATOR_SOLID_COLORS" disabled="': {
        name: "---------------",
        func: function(){
            return '#000';
        }
    },
    white: {
        name: "Solid White",
        func: function(amount){
            return '#FFF';
        }
    },
    red: {
        name: "Solid Red",
        func: function(amount){
            return '#A00';
        }
    },
    green: {
        name: "Solid Green",
        func: function(amount){
            return '#0A0';
        }
    },
    blue: {
        name: "Solid Blue",
        func: function(amount){
            return '#00A';
        }
    },
    electric: {
        name: "Solid Electric",
        func: function(amount){
            return '#0FF';
        }
    },
    indigo: {
        name: "Solid Indigo",
        func: function(amount){
            return '#4B0082';
        }
    }
}
var currColor = "bluegreenred";

function setColor(newcolor){
    //getColor = (colors[newcolor] || colors.bgr).func;
    if(colors[newcolor]){
        currColor = newcolor;
    }else{
        currColor = "redgreenblue";
    }
}

function getColor(power){
    return colors[currColor].func(power);
}

function setVis(newvis){
    if(currVis){
        vis[currVis].stop();
    }
    if(vis[newvis]){
        currVis = newvis;
    }else{
        currVis = "none";
    }
    if(smokeEnabled){
        resizeSmoke();
    }
    vis[currVis].start();
}

var currVis = null;
var vis = {
    none: {
        name: "Song List",
        start: function(){
            getId("visualizer").classList.add("disabled");
            getId("songList").classList.remove("disabled");
            //analyser.disconnect(delayNode);
            //mediaSource.disconnect(analyser);
            //mediaSource.connect(delayNode);
        },
        frame: function(){
            
        },
        stop: function(){
            getId("visualizer").classList.remove("disabled");
            getId("songList").classList.add("disabled");
            //mediaSource.disconnect(delayNode);
            //mediaSource.connect(analyser);
            //analyser.connect(delayNode);
        }
    },
    'SEPARATOR_BARS" disabled="': {
        name: '---------------',
        start: function(){

        },
        frame: function(){

        },
        stop: function(){

        }
    },
    monstercat: {
        name: "Monstercat",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
            }
            var left = size[0] * 0.1;
            var maxWidth = size[0] * 0.8;
            var barWidth = maxWidth / 96;
            var barSpacing = maxWidth / 64;
            var maxHeight = size[1] * 0.5 - size[1] * 0.2;
            
            var monstercatGradient = canvas.createLinearGradient(0, Math.round(size[1] / 2) + 4, 0, size[1]);
            monstercatGradient.addColorStop(0, 'rgba(0, 0, 0, 0.9)');
            monstercatGradient.addColorStop(0.1, 'rgba(0, 0, 0, 1)');
            canvas.fillStyle = monstercatGradient;
            canvas.fillRect(0, Math.round(size[1] / 2) + 4, size[0], Math.round(size[1] / 2) - 4);
            
            for(var i = 0; i < 64; i++){
                var strength = 0;
                for(var j = 0; j < 16; j++){
                    //strength = Math.max(visData[i * 16 + j], strength);
                    //strength += visData[i * 16 + j];
                    //strength += Math.pow(visData[i * 16 + j], 2) / 255;
                    strength += Math.sqrt(visData[i * 16 + j]) * this.sqrt255;
                }
                strength = Math.round(strength / 16);
                
                var fillColor = getColor(strength);
                canvas.fillStyle = fillColor;
                canvas.fillRect(
                    Math.round(left + i * barSpacing),
                    Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight),
                    Math.round(barWidth),
                    Math.round(strength / 255 * maxHeight + 5)
                );
                canvas.fillStyle = "#000";
                canvas.fillRect(
                    Math.round(left + i * barSpacing),
                    Math.floor(size[1] / 2) + 5,
                    Math.round(barWidth),
                    Math.round(strength / 255 * maxHeight + 5)
                );
                if(smokeEnabled){
                    smoke.fillStyle = fillColor;
                    smoke.fillRect(
                        Math.round(left + i * barSpacing),
                        Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight),
                        Math.round(barWidth),
                        Math.round((strength / 255 * maxHeight + 5) * 2)
                    );
                }
            }
            //updateSmoke(left, size[1] * 0.2, maxWidth, size[1] * 0.3 + 10);
            canvas.fillStyle = '#FFF';
            canvas.font = (size[1] * 0.25) + 'px aosProFont, sans-serif';
            canvas.fillText((fileNames[currentSong] || ["No Song"])[0].toUpperCase(), Math.round(left) + 0.5, size[1] * 0.75, Math.floor(maxWidth));
        },
        stop: function(){
            
        },
        sqrt255: Math.sqrt(255)
    },
    obelisks: {
        name: "Obelisks",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
            var left = size[0] * 0.1;
            var maxWidth = size[0] * 0.8;
            var barWidth = maxWidth / 96;
            var barSpacing = maxWidth / 64;
            var maxHeight = size[1] * 0.5 - size[1] * 0.2;

            var monstercatGradient = canvas.createLinearGradient(0, Math.round(size[1] / 2) + 4, 0, size[1]);
            monstercatGradient.addColorStop(0, 'rgba(0, 0, 0, 0.9)');
            monstercatGradient.addColorStop(0.1, 'rgba(0, 0, 0, 1)');
            canvas.fillStyle = monstercatGradient;
            canvas.fillRect(0, Math.round(size[1] / 2) + 4, size[0], Math.round(size[1] / 2) - 4);

            for(var i = 0; i < 64; i++){
                var strength = 0;
                for(var j = 0; j < 16; j++){
                    //strength = Math.max(visData[i * 16 + j], strength);
                    //strength += visData[i * 16 + j];
                    //strength += Math.pow(visData[i * 16 + j], 2) / 255;
                    strength += Math.sqrt(visData[i * 16 + j]) * this.sqrt255;
                }
                strength = Math.round(strength / 16);
                
                smoke.fillStyle = getColor(strength);
                smoke.fillRect(
                    Math.round(left + i * barSpacing),
                    Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight),
                    Math.round(barWidth),
                    Math.round((strength / 255 * maxHeight + 5) * 2)
                );
                canvas.fillStyle = "rgba(0, 0, 0, 0.85)";
                canvas.fillRect(
                    Math.round(left + i * barSpacing),
                    Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight),
                    Math.round(barWidth),
                    Math.round((strength / 255 * maxHeight + 5) * 2)
                );
                canvas.fillStyle = "rgba(0, 0, 0, 0.5)";
                canvas.fillRect(
                    Math.round(left + i * barSpacing) + 1,
                    Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight) + 1,
                    Math.round(barWidth) - 2,
                    Math.round((strength / 255 * maxHeight + 5) * 2) - 2
                )
                canvas.fillStyle = "#000";
                canvas.fillRect(
                    Math.round(left + i * barSpacing) + 2,
                    Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight) + 2,
                    Math.round(barWidth) - 4,
                    Math.round((strength / 255 * maxHeight + 5) * 2) - 4
                );
            }
            //updateSmoke(left, size[1] * 0.2, maxWidth, size[1] * 0.3 + 10);
            canvas.fillStyle = '#FFF';
            canvas.font = (size[1] * 0.25) + 'px aosProFont, sans-serif';
            canvas.fillText((fileNames[currentSong] || ["No Song"])[0].toUpperCase(), Math.round(left) + 0.5, size[1] * 0.75, Math.floor(maxWidth));
            if(!smokeEnabled){
                canvas.font = "12px aosProFont, Courier, monospace";
                canvas.fillText("Enable Smoke for this visualizer.", Math.round(left) + 0.5, size[1] * 0.25, Math.floor(maxWidth));
            }
        },
        stop: function(){
            
        },
        sqrt255: Math.sqrt(255)
    },
    spikes1to1: {
        name: "Spikes Classic",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            canvas.fillStyle = "#000";
            canvas.fillRect(0, size[1] / 2 + 127, size[0], size[1] / 2 - 127);
            smoke.clearRect(0, 0, size[0], size[1]);
            var left = size[0] / 2 - 512;
            var top = size[1] / 2 - 128;
            for(var i = 0; i < 1024; i++){
                this.drawLine(i, visData[i], left, top);
            }
            //updateSmoke();
        },
        stop: function(){
            
        },
        drawLine: function(x, h, l, t){
            var fillColor = getColor(h);
            canvas.fillStyle = fillColor;
            canvas.fillRect(l + x, t + (255 - h), 1, h);
            if(smokeEnabled){
                smoke.fillStyle = fillColor;
                smoke.fillRect(l + x, t + (255 - h), 1, h * 2);
            }
        }
    },
    spikes: {
        name: "Spikes Stretch",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
            var step = size[0] / 1024;
            var last = -1;
            var heightFactor = size[1] / 255;
            for(var i = 0; i < 1024; i++){
                var strength = 0;
                if(i === 0){
                    strength = visData[i];
                    this.drawLine(0, strength, heightFactor);
                }else{
                    var last = Math.floor(step * (i - 1));
                    var curr = Math.floor(step * i);
                    var next = Math.floor(step * (i + 1));
                    if(last < curr - 1){
                        // stretched
                        for(var j = 0; j < curr - last - 1; j++){
                            //strength = ((j + 1) / (curr - last + 1) * visData[i - 1] + (curr - last - j + 1) / (curr - last + 1) * visData[i]);
                            strength = (visData[i] + visData[i - 1]) / 2;
                            this.drawLine(curr - j - 1, strength, heightFactor);
                        }
                        strength = visData[i];
                        this.drawLine(curr, strength, heightFactor);
                    }else if(curr === last && next > curr){
                        // compressed
                        for(var j = 0; j < (1 / step); j++){
                            strength += visData[i - j];
                        }
                        strength /= Math.floor(1 / step) + 1;
                        this.drawLine(curr, strength, heightFactor);
                    }else if(last === curr - 1){
                        strength = visData[i];
                        this.drawLine(curr, strength, heightFactor);
                    }
                }
            }
            //updateSmoke();
        },
        stop: function(){
            
        },
        drawLine: function(x, h, fact){
            var fillColor = getColor(h);
            canvas.fillStyle = fillColor;
            canvas.fillRect(x, (255 - h)  * fact, 1, size[1] - (255 - h) * fact);
            if(smokeEnabled){
                smoke.fillStyle = fillColor;
                smoke.fillRect(x, (255 - h)  * fact, 1, size[1] - (255 - h) * fact);
            }
        }
    },
    spikesAccumulate: {
        name: "Spikes Accumulate",
        start: function(){
            this.totals = new Array(1024).fill(0);
            this.max = 0;
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
            var left = size[0] / 2 - 512;
            var top = 0;
            for(var i = 0; i < 1024; i++){
                this.totals[i] += visData[i];
                if(this.totals[i] > this.max){
                    this.max = this.totals[i];
                }
                if(this.max / 255 > size[1]){
                    this.drawLine(i, this.totals[i] / this.max * size[1], left, top, visData[i]);
                }else{
                    this.drawLine(i, this.totals[i] / 255, left, top, visData[i]);
                }
            }
            //updateSmoke();
        },
        stop: function(){
            this.totals = [];
            this.max = 0;
        },
        drawLine: function(x, h, l, t, c){
            var fillColor = getColor(c);
            canvas.fillStyle = fillColor;
            canvas.fillRect(l + x, t + (size[1] - h), 1, h);
            if(smokeEnabled){
                smoke.fillStyle = fillColor;
                smoke.fillRect(l + x, t + (size[1] - h), 1, h * 2);
            }
        },
        max: 0,
        totals: []
    },
    'SEPARATOR_CIRCLES" disabled="': {
        name: '---------------',
        start: function(){

        },
        frame: function(){

        },
        stop: function(){

        }
    },
    rings: {
        name: "Rings",
        start: function(){
            this.ringPositions = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
            }
            var ringHeight = Math.round(Math.min(size[0], size[1]) * 0.8);
            var ringWidth = Math.round(ringHeight * 0.023);
            canvas.lineWidth = ringWidth;
            canvas.lineCap = "round";
            smoke.lineWidth = ringWidth;
            smoke.lineCap = "round";
            var ringPools = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            //var ringAvgs = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var center = [Math.round(size[0] / 2), Math.round(size[1] / 2)];
            for(var i = 0; i < 1024; i++){
                var currPool = Math.floor(i / 102.4);
                ringPools[currPool] = Math.max(visData[i], ringPools[currPool]);
                //ringPools[currPool] += visData[i];
                //ringAvgs[currPool]++;
            }
            //for(var i in ringPools){
            //    ringPools[i] /= ringAvgs[i];
            //}
            for(var i = 0; i < 10; i++){
                var strength = Math.pow(ringPools[i], 2) / 65025;
                var ringColor = getColor(strength * 255);
                this.ringPositions[i] += strength * 5;
                if(this.ringPositions[i] >= 360){
                    this.ringPositions[i] -= 360;
                }
                canvas.strokeStyle = ringColor;
                smoke.strokeStyle = ringColor;
                this.degArc(center[0], center[1], ringWidth * 2 * (i + 1), this.ringPositions[i], this.ringPositions[i] + 180);
                if(smokeEnabled){
                    this.degArcSmoke(center[0], center[1], ringWidth * 2 * (i + 1), this.ringPositions[i], this.ringPositions[i] + 180);
                }
            }
            //updateSmoke(size[0] / 2 - ringHeight / 2, size[1] / 2 - ringHeight / 2, ringHeight, ringHeight);
        },
        stop: function(){
            canvas.lineWidth = 1;
            canvas.lineCap = "butt";
            smoke.lineWidth = "1";
            smoke.lineCap = "butt";
        },
        ringPositions: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        TAU: Math.PI * 2,
        degArc: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.stroke();
        },
        degArcSmoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            smoke.stroke();
        }
    },
    ghostRings: {
        name: "Ghost Rings",
        start: function(){
            this.ringPositions = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
            var ringHeight = Math.round(Math.min(size[0], size[1]) * 0.8);
            var ringWidth = Math.round(ringHeight * 0.023);
            canvas.lineCap = "round";
            smoke.lineWidth = ringWidth;
            smoke.lineCap = "round";
            var ringPools = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            //var ringAvgs = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var center = [Math.round(size[0] / 2), Math.round(size[1] / 2)];
            for(var i = 0; i < 1024; i++){
                var currPool = Math.floor(i / 102.4);
                ringPools[currPool] = Math.max(visData[i], ringPools[currPool]);
                //ringPools[currPool] += visData[i];
                //ringAvgs[currPool]++;
            }
            //for(var i in ringPools){
            //    ringPools[i] /= ringAvgs[i];
            //}
            for(var i = 0; i < 10; i++){
                var strength = Math.pow(ringPools[i], 2) / 65025;
                var ringColor = getColor(strength * 255);
                this.ringPositions[i] += strength * 5;
                if(this.ringPositions[i] >= 360){
                    this.ringPositions[i] -= 360;
                }

                smoke.strokeStyle = ringColor;
                this.degArcSmoke(center[0], center[1], ringWidth * 2 * (i + 1), this.ringPositions[i], this.ringPositions[i] + 180);
                /*
                canvas.lineWidth = ringWidth;
                canvas.strokeStyle = 'rgba(0, 0, 0, 0.85)';
                this.degArc(center[0], center[1], ringWidth * 2 * (i + 1), this.ringPositions[i], this.ringPositions[i] + 180);
                canvas.lineWidth = ringWidth - 4;
                canvas.strokeStyle = 'rgba(0, 0, 0, 0.5)';
                this.degArc(center[0], center[1], ringWidth * 2 * (i + 1), this.ringPositions[i], this.ringPositions[i] + 180);
                canvas.lineWidth = ringWidth - 8;
                canvas.strokeStyle = '#000';
                this.degArc(center[0], center[1], ringWidth * 2 * (i + 1), this.ringPositions[i], this.ringPositions[i] + 180);*/
            }
            if(!smokeEnabled){
                canvas.fillStyle = '#FFF';
                canvas.font = '12px aosProFont, Courier, monospace';
                canvas.fillText("Enable Smoke for this visualizer.", center[0] - ringHeight / 2 + 0.5, center[1] - 6, ringHeight);
            }
            //updateSmoke(size[0] / 2 - ringHeight / 2, size[1] / 2 - ringHeight / 2, ringHeight, ringHeight);
        },
        stop: function(){
            canvas.lineWidth = 1;
            canvas.lineCap = "butt";
            smoke.lineWidth = "1";
            smoke.lineCap = "butt";
        },
        ringPositions: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        TAU: Math.PI * 2,
        degArc: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.stroke();
        },
        degArcSmoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            smoke.stroke();
        }
    },
    circle: {
        name: "Treble Circle",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
            }
            var ringHeight = Math.round(Math.min(size[0], size[1]) * 0.6);
            var ringMaxRadius = ringHeight * 0.5;
            var ringMinRadius = ringHeight * 0.25;
            var ringMaxExpand = Math.round(Math.min(size[0], size[1]) * 0.2);
            var randomShake = Math.round(Math.min(size[0], size[1]) * 0.03);
            var drumStrength = 0; // drums is all under line 150... lmao i didnt even measure that it's just a total guess
            var center = [Math.round(size[0] / 2), Math.round(size[1] / 2)];
            for(var i = 0; i < 180; i++){
                drumStrength += Math.pow(visData[i], 2) / 255;
            }
            drumStrength /= 180;

            var randomOffset = [(Math.random() - 0.5) * drumStrength / 255 * randomShake, (Math.random() - 0.5) * drumStrength / 255 * randomShake];

            for(var i = 0; i < 844; i += 4){
                var strength = (visData[i + 180] + visData[i + 181] + visData[i + 182] + visData[i + 183]) / 4;
                canvas.fillStyle = getColor(strength);
                this.degArc(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    i / 844 * 180 + 90,
                    (i + 4.1) / 844 * 180 + 90
                );
                this.degArc(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    90 - (i + 4.1) / 844 * 180,
                    90 - i / 844 * 180
                );
                if(smokeEnabled){
                    smoke.fillStyle = getColor(strength);
                    this.degArcSmoke(
                        size[0] / 2 + randomOffset[0],
                        size[1] / 2 + randomOffset[1],
                        ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                        i / 844 * 180 + 90,
                        (i + 3.1) / 844 * 180 + 90
                    );
                    this.degArcSmoke(
                        size[0] / 2 + randomOffset[0],
                        size[1] / 2 + randomOffset[1],
                        ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                        90 - (i + 4.1) / 844 * 180,
                        90 - i / 844 * 180
                    );
                }
            }

            canvas.fillStyle = '#000';
            this.degArc2(
                size[0] / 2 + randomOffset[0],
                size[1] / 2 + randomOffset[1],
                ringMinRadius + drumStrength / 255 * ringMaxExpand - 5,
                0,
                360
            );
            if(smokeEnabled){
                smoke.fillStyle = '#000';
                this.degArc2smoke(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + drumStrength / 255 * ringMaxExpand - 5,
                    0,
                    360
                );
            }
            //updateSmoke(size[0] / 2 - ringHeight / 2, size[1] / 2 - ringHeight / 2, ringHeight, ringHeight);
        },
        stop: function(){

        },
        TAU: Math.PI * 2,
        degArc: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.moveTo(size[0] / 2, size[1] / 2);
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.fill();
        },
        degArc2: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.fill();
        },
        degArcSmoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.moveTo(size[0] / 2, size[1] / 2);
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            smoke.fill();
        },
        degArc2smoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            smoke.fill();
        },
    },
    bassCircle: {
        name: "Bass Circle",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
            }
            var ringHeight = Math.round(Math.min(size[0], size[1]) * 0.6);
            var ringMaxRadius = ringHeight * 0.5;
            var ringMinRadius = ringHeight * 0.25;
            var ringMaxExpand = Math.round(Math.min(size[0], size[1]) * 0.2);
            var randomShake = Math.round(Math.min(size[0], size[1]) * 0.03);
            var drumStrength = 0; // drums is all under line 150... lmao i didnt even measure that it's just a total guess
            var center = [Math.round(size[0] / 2), Math.round(size[1] / 2)];
            for(var i = 0; i < 180; i++){
                drumStrength += Math.pow(visData[i], 2) / 255;
            }
            drumStrength /= 180;

            var randomOffset = [(Math.random() - 0.5) * drumStrength / 255 * randomShake, (Math.random() - 0.5) * drumStrength / 255 * randomShake];

            for(var i = 0; i < 180; i++){
                canvas.fillStyle = getColor(visData[i]);
                this.degArc(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + visData[i] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    i + 90,
                    (i + 1.1) + 90
                );
                this.degArc(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + visData[i] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    90 - (i + 1.1),
                    90 - i
                );
                if(smokeEnabled){
                    smoke.fillStyle = getColor(visData[i]);
                    this.degArcSmoke(
                        size[0] / 2 + randomOffset[0],
                        size[1] / 2 + randomOffset[1],
                        ringMinRadius + visData[i] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                        i + 90,
                        (i + 1.1) + 90
                    );
                    this.degArcSmoke(
                        size[0] / 2 + randomOffset[0],
                        size[1] / 2 + randomOffset[1],
                        ringMinRadius + visData[i] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                        90 - (i + 1.1),
                        90 - i
                    );
                }
            }

            canvas.fillStyle = '#000';
            this.degArc2(
                size[0] / 2 + randomOffset[0],
                size[1] / 2 + randomOffset[1],
                ringMinRadius + drumStrength / 255 * ringMaxExpand - 5,
                0,
                360
            );
            if(smokeEnabled){
                smoke.fillStyle = '#000';
                this.degArc2smoke(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + drumStrength / 255 * ringMaxExpand - 5,
                    0,
                    360
                );
            }
            //updateSmoke(size[0] / 2 - ringHeight / 2, size[1] / 2 - ringHeight / 2, ringHeight, ringHeight);
        },
        stop: function(){

        },
        TAU: Math.PI * 2,
        degArc: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.moveTo(size[0] / 2, size[1] / 2);
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.fill();
        },
        degArc2: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.fill();
        },
        degArcSmoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.moveTo(size[0] / 2, size[1] / 2);
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            smoke.fill();
        },
        degArc2smoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            smoke.fill();
        },
    },
    layerCircle: {
        name: "Layered Circle",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
            }
            var ringHeight = Math.round(Math.min(size[0], size[1]) * 0.6);
            var ringMaxRadius = ringHeight * 0.5;
            var ringMinRadius = ringHeight * 0.25;
            var ringMaxExpand = Math.round(Math.min(size[0], size[1]) * 0.2);
            var randomShake = Math.round(Math.min(size[0], size[1]) * 0.03);
            var drumStrength = 0; // drums is all under line 150... lmao i didnt even measure that it's just a total guess
            var center = [Math.round(size[0] / 2), Math.round(size[1] / 2)];
            for(var i = 0; i < 180; i++){
                drumStrength += Math.pow(visData[i], 2) / 255;
            }
            drumStrength /= 180;

            var randomOffset = [(Math.random() - 0.5) * drumStrength / 255 * randomShake, (Math.random() - 0.5) * drumStrength / 255 * randomShake];

            canvas.fillStyle = getColor(127);
            canvas.beginPath();
            canvas.moveTo(size[0] / 2, size[1] / 2);

            if(smokeEnabled){
                smoke.fillStyle = getColor(127);
                smoke.beginPath();
                smoke.moveTo(size[0] / 2, size[1] / 2);
            }

            for(var i = -180; i < 181; i++){
                this.degArc(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + visData[Math.abs(i)] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    i + 90,
                    i + 90,
                    //(i + 1.1) + 90
                );
                //this.degArc(
                //    size[0] / 2 + randomOffset[0],
                //    size[1] / 2 + randomOffset[1],
                //    ringMinRadius + visData[i] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                //    //90 - (i + 1.1),
                //    90 - i,
                //    90 - i
                //);
                if(smokeEnabled){
                    this.degArcSmoke(
                        size[0] / 2 + randomOffset[0],
                        size[1] / 2 + randomOffset[1],
                        ringMinRadius + visData[Math.abs(i)] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                        i + 90,
                        i + 90,
                        //(i + 1.1) + 90
                    );
                    //this.degArcSmoke(
                    //    size[0] / 2 + randomOffset[0],
                    //    size[1] / 2 + randomOffset[1],
                    //    ringMinRadius + visData[i] / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    //    //90 - (i + 1.1),
                    //    90 - i,
                    //    90 - i
                    //);
                }
            }

            canvas.fill();
            if(smokeEnabled){
                smoke.fill();
            }

            canvas.fillStyle = getColor(255);
            canvas.beginPath();
            canvas.moveTo(size[0] / 2, size[1] / 2);

            if(smokeEnabled){
                smoke.fillStyle = getColor(255);
                smoke.beginPath();
                smoke.moveTo(size[0] / 2, size[1] / 2);
            }

            for(var i = -844; i < 845; i += 4){
                var strength = (visData[Math.abs(i) + 180] + visData[Math.abs(i) + 181] + visData[Math.abs(i) + 182] + visData[Math.abs(i) + 183]) / 4;
                
                this.degArc(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    i / 844 * 180 + 90,
                    i / 844 * 180 + 90,
                    //(i + 4.1) / 844 * 180 + 90
                );
                //this.degArc(
                //    size[0] / 2 + randomOffset[0],
                //    size[1] / 2 + randomOffset[1],
                //    ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                //    90 - (i + 4.1) / 844 * 180,
                //    90 - i / 844 * 180
                //);
                if(smokeEnabled){
                    this.degArcSmoke(
                        size[0] / 2 + randomOffset[0],
                        size[1] / 2 + randomOffset[1],
                        ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                        i / 844 * 180 + 90,
                        i / 844 * 180 + 90,
                        //(i + 3.1) / 844 * 180 + 90
                    );
                    //this.degArcSmoke(
                    //    size[0] / 2 + randomOffset[0],
                    //    size[1] / 2 + randomOffset[1],
                    //    ringMinRadius + strength / 255 * ringMinRadius + drumStrength / 255 * ringMaxExpand,
                    //    90 - (i + 4.1) / 844 * 180,
                    //    90 - i / 844 * 180
                    //);
                }
            }

            canvas.fill();
            if(smokeEnabled){
                smoke.fill();
            }

            canvas.fillStyle = '#000';
            this.degArc2(
                size[0] / 2 + randomOffset[0],
                size[1] / 2 + randomOffset[1],
                ringMinRadius + drumStrength / 255 * ringMaxExpand - 5,
                0,
                360
            );
            if(smokeEnabled){
                smoke.fillStyle = '#000';
                this.degArc2smoke(
                    size[0] / 2 + randomOffset[0],
                    size[1] / 2 + randomOffset[1],
                    ringMinRadius + drumStrength / 255 * ringMaxExpand - 5,
                    0,
                    360
                );
            }
            //updateSmoke(size[0] / 2 - ringHeight / 2, size[1] / 2 - ringHeight / 2, ringHeight, ringHeight);
        },
        stop: function(){

        },
        TAU: Math.PI * 2,
        degArc: function(x, y, r, a, b){
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
        },
        degArc2: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.fill();
        },
        degArcSmoke: function(x, y, r, a, b){
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
        },
        degArc2smoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            smoke.fill();
        },
    },
    eclipse: {
        name: "Eclipse",
        start: function(){
            
        },
        frame: function(){
            smoke.clearRect(0, 0, size[0], size[1]);
            var ringHeight = Math.round(Math.min(size[0], size[1]) * 0.8);
            var ringWidth = ringHeight * 0.5;
            var strokeWidth = ringWidth * 0.4;
            var strokePosition = ringWidth * 0.8;
            smoke.lineWidth = strokeWidth;
            var center = [Math.round(size[0] / 2), Math.round(size[1] / 2)];
            for(var i = 510; i > -1; i -= 2){
                var strength = Math.pow(Math.max(visData[i], visData[i + 1]), 2) / 255;
                smoke.strokeStyle = getColor(strength);
                var linePosition = (i * 0.5) * this.ratio_360_1024;
                this.degArcSmoke(center[0], center[1], strokePosition, linePosition + 90, linePosition + 91);
                smoke.stroke();
                linePosition *= -1;
                this.degArcSmoke(center[0], center[1], strokePosition, linePosition + 90, linePosition + 91);
                smoke.stroke();
            }
            for(var i = 512; i < 1024; i += 2){
                var strength = Math.pow(Math.max(visData[i], visData[i + 1]), 2) / 255;
                smoke.strokeStyle = getColor(strength);
                var linePosition = (i * 0.5) * this.ratio_360_1024;
                this.degArcSmoke(center[0], center[1], strokePosition, linePosition + 90, linePosition + 91);
                smoke.stroke();
                linePosition *= -1;
                this.degArcSmoke(center[0], center[1], strokePosition, linePosition + 90, linePosition + 91);
                smoke.stroke();
            }
            //updateSmoke(center[0] - ringWidth - 1, center[1] - ringWidth - 1, ringHeight + 1, ringHeight + 1);
            //smoke.putImageData(smoke.getImageData(center[0], center[1] - ringWidth - 1, -1 * ringWidth - 1, ringHeight), center[0], center[1] - ringWidth - 1);
            var ringGradient = canvas.createRadialGradient(center[0], center[1], (ringWidth) * 0.8, center[0], center[1], ringWidth);
            ringGradient.addColorStop(0, 'rgba(0, 0, 0, 1)');
            ringGradient.addColorStop(0.95, 'rgba(0, 0, 0, 0.9)');
            ringGradient.addColorStop(1, 'rgba(0, 0, 0, 0.8)');
            canvas.clearRect(0, 0, size[0], size[1]);
            canvas.fillStyle = ringGradient;
            this.degArc(center[0], center[1], ringWidth, 0, 360);
            canvas.fill();
            if(!smokeEnabled){
                canvas.fillStyle = "#FFF";
                canvas.font = "12px aosProFont, Courier, monospace";
                canvas.fillText("Enable Smoke for this visualizer.", center[0] - ringWidth + 15.5, center[1] - 6, ringWidth - 30);
            }
        },
        stop: function(){
            smoke.lineWidth = 1;
        },
        TAU: Math.PI * 2,
        sqrt255: Math.sqrt(255),
        degArc: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
        },
        degArcSmoke: function(x, y, r, a, b){
            smoke.beginPath();
            smoke.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
        },
        ratio_360_1024: 360 / 1024
    },
    'SEPARATOR_FULL_SCREEN" disabled="': {
        name: '---------------',
        start: function(){

        },
        frame: function(){

        },
        stop: function(){

        }
    },
    spectrum: {
        name: "Spectrum",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
            var step = size[0] / 1024;
            var last = -1;
            for(var i = 0; i < 1024; i++){
                var strength = 0;
                if(i === 0){
                    strength = visData[i];
                    this.drawLine(0, strength);
                }else{
                    var last = Math.floor(step * (i - 1));
                    var curr = Math.floor(step * i);
                    var next = Math.floor(step * (i + 1));
                    if(last < curr - 1){
                        // stretched
                        for(var j = 0; j < curr - last - 1; j++){
                            //strength = ((j + 1) / (curr - last + 1) * visData[i - 1] + (curr - last - j + 1) / (curr - last + 1) * visData[i]);
                            strength = (visData[i] + visData[i - 1]) / 2;
                            this.drawLine(curr - 1, strength);
                        }
                        strength = visData[i];
                        this.drawLine(curr, strength);
                    }else if(curr === last && next > curr){
                        // compressed
                        for(var j = 0; j < (1 / step); j++){
                            strength += visData[i - j];
                        }
                        strength /= Math.floor(1 / step) + 1;
                        this.drawLine(curr, strength);
                    }else if(last === curr - 1){
                        strength = visData[i];
                        this.drawLine(curr, strength);
                    }
                }
            }
        },
        stop: function(){
            
        },
        drawLine: function(x, colorAmount){
            if(smokeEnabled){
                smoke.fillStyle = getColor(colorAmount);
                smoke.fillRect(x, 0, 1, size[1]);
            }else{
                canvas.fillStyle = getColor(colorAmount);
                canvas.fillRect(x, 0, 1, size[1]);
            }
        }
    },
    solidColor: {
        name: "Solid Color",
        start: function(){
            
        },
        frame: function(){
            var avg = 0;
            var avgtotal = 0;
            for(var i = 0; i < 1024; i++){
                avg += Math.sqrt(visData[i]) * this.sqrt255;
            }
            avg /= 1024;
            //avg /= 1024;
            //avg *= 255;
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
                smoke.fillStyle = getColor(avg);
                smoke.fillRect(0, 0, size[0], size[1]);
            }else{
                canvas.fillStyle = getColor(avg);
                canvas.fillRect(0, 0, size[0], size[1]);
            }
        },
        stop: function(){
            
        },
        sqrt255: Math.sqrt(255)
    },
    bassSolidColor: {
        name: "Bass Solid Color",
        start: function(){
            
        },
        frame: function(){
            var avg = 0;
            var avgtotal = 0;
            for(var i = 0; i < 180; i++){
                avg += Math.sqrt(visData[i]) * this.sqrt255;
            }
            avg /= 180;
            //avg /= 1024;
            //avg *= 255;
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
                smoke.fillStyle = getColor(avg);
                smoke.fillRect(0, 0, size[0], size[1]);
            }else{
                canvas.fillStyle = getColor(avg);
                canvas.fillRect(0, 0, size[0], size[1]);
            }
        },
        stop: function(){
            
        },
        sqrt255: Math.sqrt(255)
    },
    windowRecolor: {
        name: "Window Color",
        start: function(){
            
        },
        frame: function(){
            var avg = 0;
            var avgtotal = 0;
            for(var i = 0; i < 1024; i++){
                avg += Math.sqrt(visData[i]) * this.sqrt255;
            }
            avg /= 1024;
            //avg /= 1024;
            //avg *= 255;
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
                smoke.fillStyle = getColor(avg);
                smoke.fillRect(0, 0, size[0], size[1]);
            }else{
                canvas.fillStyle = getColor(avg);
                canvas.fillRect(0, 0, size[0], size[1]);
            }
            canvas.fillStyle = "#FFF";
            canvas.font = "12px aosProFont, Courier, monospace";
            canvas.fillText("Load this visualizer in AaronOS and your window borders will color themselves to the beat.", 10.5, 20);
            document.title = "WindowRecolor:" + getColor(avg);
        },
        stop: function(){
            document.title = "AaronOS Music Player";
        },
        sqrt255: Math.sqrt(255)
    },
    bassWindowRecolor: {
        name: "Bass Window Color",
        start: function(){
            
        },
        frame: function(){
            var avg = 0;
            var avgtotal = 0;
            for(var i = 0; i < 180; i++){
                avg += Math.sqrt(visData[i]) * this.sqrt255;
            }
            avg /= 180;
            //avg /= 1024;
            //avg *= 255;
            canvas.clearRect(0, 0, size[0], size[1]);
            if(smokeEnabled){
                smoke.clearRect(0, 0, size[0], size[1]);
                smoke.fillStyle = getColor(avg);
                smoke.fillRect(0, 0, size[0], size[1]);
            }else{
                canvas.fillStyle = getColor(avg);
                canvas.fillRect(0, 0, size[0], size[1]);
            }
            canvas.fillStyle = "#FFF";
            canvas.font = "12px aosProFont, Courier, monospace";
            canvas.fillText("Load this visualizer in AaronOS and your window borders will color themselves to the beat.", 10.5, 20);
            document.title = "WindowRecolor:" + getColor(avg);
        },
        stop: function(){
            document.title = "AaronOS Music Player";
        },
        sqrt255: Math.sqrt(255)
    },
    'SEPARATOR_TESTS" disabled="': {
        name: '---------------',
        start: function(){

        },
        frame: function(){

        },
        stop: function(){

        }
    },
    spectrogramClassic: {
        name: "Spectrogram Classic",
        start: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
        },
        frame: function(){
            var left = size[0] / 2 - 512;
            canvas.putImageData(canvas.getImageData(left, 0, left + 1024, size[1]), left, -1);
            var strength = 0;
            for(var i = 0; i < 1024; i++){
                strength = visData[i];
                canvas.fillStyle = getColor(strength);
                canvas.fillRect(left + i, size[1] - 1, 1, 1);
            }
        },
        sizechange: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
        },
        stop: function(){
            
        }
    },
    spectrogram: {
        name: "Spectrogram Stretch",
        start: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
        },
        frame: function(){
            canvas.putImageData(canvas.getImageData(0, 0, size[0], size[1]), 0, -1);
            var step = size[0] / 1024;
            var last = -1;
            var heightFactor = size[1] / 255;
            for(var i = 0; i < 1024; i++){
                var strength = 0;
                if(i === 0){
                    strength = visData[i];
                    canvas.fillStyle = getColor(strength);
                    canvas.fillRect(0, size[1] - 1, 1, 1);
                }else{
                    var last = Math.floor(step * (i - 1));
                    var curr = Math.floor(step * i);
                    var next = Math.floor(step * (i + 1));
                    if(last < curr - 1){
                        // stretched
                        for(var j = 0; j < curr - last - 1; j++){
                            //strength = ((j + 1) / (curr - last + 1) * visData[i - 1] + (curr - last - j + 1) / (curr - last + 1) * visData[i]);
                            strength = (visData[i] + visData[i - 1]) / 2;
                            canvas.fillStyle = getColor(strength);
                            canvas.fillRect(curr - j - 1, size[1] - 1, 1, 1);
                        }
                        strength = visData[i];
                        canvas.fillStyle = getColor(strength);
                        canvas.fillRect(curr, size[1] - 1, 1, 1);
                    }else if(curr === last && next > curr){
                        // compressed
                        for(var j = 0; j < (1 / step); j++){
                            strength += visData[i - j];
                        }
                        strength /= Math.floor(1 / step) + 1;
                        canvas.fillStyle = getColor(strength);
                        canvas.fillRect(curr, size[1] - 1, 1, 1);
                    }else if(last === curr - 1){
                        strength = visData[i];
                        canvas.fillStyle = getColor(strength);
                        canvas.fillRect(curr, size[1] - 1, 1, 1);
                    }
                }
            }
        },
        sizechange: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
        },
        stop: function(){
            
        }
    },
    piano: {
        name: "Piano Test",
        start: function(){
            /*

            1014 = 739.989 = F5#

            957 = 698.456 = F5
            
            ----
            
            47 = 138.591 = C3#
            
            45 = 130.813 = C3
            
            ----
            
            lowest note line = 45
            
            highest note line = 1014
            
            0.0629514964 notes per line on average
            
            57 lines per note @ high
            
            2 lines per note @ low
            
            ----
            
            61 keys in total
            
            1014-45 = 969 screen lines

            left edge = 40

            45 47 50 53 56 59 63 67 71 75 80 84 89 95 100 106 112 119 126 134 142 150 159 169 179 189 200 212 225 238 253 268 284 301 320 339 359 379 402 426 452 478 507 537 569 603 638 676 717 759 808 855 907 960 1018 1078 1142 1210 1282 1358 1439
            
            */
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            smoke.clearRect(0, 0, size[0], size[1]);
            var finalKey = this.keys.length - 1;
            var pianoWidth = finalKey * 20 + finalKey + 22;
            var pianoLeft = size[0] / 2 - Math.floor(pianoWidth / 2);
            var pianoTop = size[1] / 2 - 201;
            canvas.fillStyle = "#7F7F7F";
            canvas.fillRect(pianoLeft, pianoTop, pianoWidth, 402);
            for(var i = 0; i <= finalKey; i++){
                if(i === 0){
                    var leftCount = Math.floor(this.keys[i] - (this.keys[i + 1] - this.keys[i]) * 0.25);
                    var rightCount = Math.round(this.keys[i] + (this.keys[i + 1] - this.keys[i]) * 0.25);
                }else if(i === finalKey){
                    var leftCount = Math.floor(this.keys[i] - (this.keys[i] - this.keys[i - 1]) * 0.25);
                    var rightCount = Math.round(this.keys[i] + (this.keys[i] - this.keys[i - 1]) * 0.25);
                }else{
                    var leftCount = Math.floor(this.keys[i] - (this.keys[i] - this.keys[i - 1]) * 0.25);
                    var rightCount = Math.round(this.keys[i] + (this.keys[i + 1] - this.keys[i]) * 0.25);
                }
                var strength = 0;
                for(var j = leftCount; j < rightCount; j++){
                    //strength += Math.sqrt(visData[j]) * this.sqrt255;
                    //strength += Math.pow(visData[j], 2) / 255;
                    strength += visData[j];
                    //strength = Math.max(strength, visData[j]);
                }
                strength /= rightCount - leftCount;
                if(this.blackKeys[i]){
                    canvas.fillStyle = "#000";
                    canvas.fillRect(pianoLeft + i * 20 + i + 1, pianoTop + 1, 20, 200);
                    canvas.fillStyle = getColor(strength);
                    canvas.fillRect(pianoLeft + i * 20 + i + 1, pianoTop + 1, 20, 200);
                    if(smokeEnabled){
                        smoke.fillStyle = getColor(strength);
                        smoke.fillRect(pianoLeft + i * 20 + i + 1, pianoTop + 1, 20, 200);
                    }
                }else{
                    canvas.fillStyle = "#FFF";
                    canvas.fillRect(pianoLeft + i * 20 + i + 1, pianoTop + 1, 20, 400);
                    canvas.fillStyle = getColor(strength);
                    canvas.fillRect(pianoLeft + i * 20 + i + 1, pianoTop + 1, 20, 400);
                    if(smokeEnabled){
                        smoke.fillStyle = getColor(strength);
                        smoke.fillRect(pianoLeft + i * 20 + i + 1, pianoTop + 1, 20, 400);
                    }
                }
            }
        },
        stop: function(){

        },
        sqrt255: Math.sqrt(255),
        keys: [
            45, 47, 50, 53, 56, 59, 63, 67, 71, 75, 80, 84, 89, 95,
            100, 106, 112, 119, 126, 134, 142, 150, 159, 169, 179, 189,
            200, 212, 225, 238, 253, 268, 284, 301, 320, 339, 359, 379,
            402, 426, 452, 478, 507, 537, 569, 603, 638, 676, 717, 759,
            808, 855, 907, 960, 1018, 1078, 1142, 1210, 1282, 1358, 1439
        ],
        blackKeys: [
            0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 0,
            0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 0,
            0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 0,
            0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 0,
            0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 0, 0
        ]
    },
    bassSplit: {
        name: "Bass Split (&lt;180)",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            canvas.fillStyle = "#000";
            canvas.fillRect(0, size[1] / 2 + 127, size[0], size[1] / 2 - 127);
            smoke.clearRect(0, 0, size[0], size[1]);
            var left = size[0] / 2 - 512;
            var top = size[1] / 2 - 128;
            for(var i = 0; i < 1024; i++){
                this.drawLine(i, visData[i], left + (i >= 180) * 90 - (i < 180) * 90, top);
            }
            //updateSmoke();
        },
        stop: function(){
            
        },
        drawLine: function(x, h, l, t){
            var fillColor = getColor(h);
            canvas.fillStyle = fillColor;
            canvas.fillRect(l + x, t + (255 - h), 1, h);
            if(smokeEnabled){
                smoke.fillStyle = fillColor;
                smoke.fillRect(l + x, t + (255 - h), 1, h * 2);
            }
        }
    },
    colorTest: {
        name: "Color Test",
        start: function(){

        },
        frame: function(){
            smoke.clearRect(0, 0, size[0], size[1]);
            smoke.fillStyle = '#111';
            smoke.fillRect(0, 0, size[0], size[1] * 0.15);
            for(var i = 0; i < size[0]; i++){
                smoke.fillStyle = getColor(i / size[0] * 255);
                smoke.fillRect(i, size[1] * 0.75, 1, size[1] * 0.25);
            }
            //updateSmoke();
            canvas.clearRect(0, 0, size[0], size[1]);
            for(var i = 0; i < size[0]; i++){
                canvas.fillStyle = getColor(i / size[0] * 255);
                canvas.fillRect(i, 0, 1, size[1]);
            }
            canvas.clearRect(0, size[1] * 0.5, size[0], size[1] * 0.25);
        },
        stop: function(){

        }
    }
};

for(var i in colors){
    getId('colorfield').innerHTML += '<option value="' + i + '">' + colors[i].name + '</option>';
}

for(var i in vis){
    getId('visfield').innerHTML += '<option value="' + i + '">' + vis[i].name + '</option>';
}

var smokeEnabled = 0;
var smokePos = [0, 0];
function toggleSmoke(){
    if(smokeEnabled){
        smokeElement.style.filter = "";
        smoke.clearRect(0, 0, size[0], size[1]);
        smokeElement.classList.add("disabled");
        canvasElement.style.backgroundPosition = "";
        canvasElement.style.backgroundImage = "";
        smokeEnabled = 0;
    }else{
        smokeElement.classList.remove("disabled");
        canvasElement.style.backgroundPosition = "0px 0px";
        canvasElement.style.backgroundImage = "url(smoke_transparent.png)";
        smokeEnabled = 1;
        resizeSmoke();
    }
}
function resizeSmoke(){
    smokeElement.width = size[0];
    smokeElement.height = size[1];
    if(smokeEnabled){
        smokeElement.style.filter = "blur(" + Math.round((size[0] + size[1]) / 50) + "px) brightness(3)";
    }
}
function updateSmoke(leftpos, toppos, shortwidth, shortheight){
    if(smokeEnabled){
        smoke.clearRect(0, 0, size[0], size[1]);
        smoke.putImageData(canvas.getImageData(leftpos || 0, toppos || 0, shortwidth || size[0], shortheight || size[1]), leftpos || 0, toppos || 0);
    }
}
function smokeFrame(){
    smokePos[0] += 2;
    smokePos[1]++;
    if(smokePos[0] >= 1000){
        smokePos[0] -= 1000;
    }
    if(smokePos[1] >= 1000){
        smokePos[1] -= 1000;
    }
    canvasElement.style.backgroundPosition = smokePos[0] + "px " + smokePos[1] + "px";
}

resizeSmoke();