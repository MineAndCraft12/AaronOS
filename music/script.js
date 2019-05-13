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
        str += '<div id="song' + i + '" onclick="selectSong(' + i + ')">' + fileNames[i][1] + ": " + fileNames[i][0] + '</div>';
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
        var fileName = files[i].name.split('.');
        if(files[i].type.indexOf("audio/") === 0){
            filesLength++;
            if(supportedFormats.indexOf(fileName[fileName.length - 1]) > -1){
                fileName.pop();
            }
            fileNames.push([fileName.join('.'), i, URL.createObjectURL(files[i])]);
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
    delayNode.delayTime.value = 0.2;
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
        var fileName = files[i].name.split('.');
        if(files[i].type.indexOf("audio/") === 0){
            filesLength++;
            if(supportedFormats.indexOf(fileName[fileName.length - 1]) > -1){
                fileName.pop();
            }
            fileNames.push([fileName.join('.'), i, URL.createObjectURL(files[i])]);
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
    delayNode.delayTime.value = 0.17;
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
        var fileName = files[i].name;
        fileNames.push([fileName, i, URL.createObjectURL(files[i])]);
    }
    listSongs();
    var disabledElements = document.getElementsByClassName('disabled');
    while(disabledElements.length > 0){
        disabledElements[0].classList.remove('disabled');
    }
    
    audioContext = new AudioContext();
    mediaSource = audioContext.createMediaElementSource(audio);
    
    delayNode = audioContext.createDelay();
    delayNode.delayTime.value = 0.17;
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

var currentSong = -1;

function selectSong(id){
    currentSong = id;
    audio.pause();
    audio.currentTime = 0;
    audio.src = fileNames[id][2];
    getId("currentlyPlaying").innerHTML = fileNames[id][1] + ": " + fileNames[id][0];
    try{
        document.getElementsByClassName("selected")[0].classList.remove("selected");
    }catch(err){
        // no song is selected
    }
    getId("song" + id).classList.add("selected");
}

function play(){
    if(currentSong === -1){
        selectSong(0);
    }else{
        audio.play();
    }
}
function pause(){
    audio.pause();
}

function firstPlay(){
    audioDuration = audio.duration;
    play();
}
audio.addEventListener("canplaythrough", firstPlay);

function back(){
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
function next(){
    currentSong++;
    if(currentSong > fileNames.length - 1){
        currentSong = 0;
    }
    selectSong(currentSong);
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
    audio.pause();
    audio.currentTime = 0;
    currentSong = 0;
    shuffleArray(fileNames);
    listSongs();
    selectSong(0);
}

function refresh(){
    window.location = "?refresh=" + (new Date()).getTime();
}

function getStyleInfo(event){
    console.log(event.data);
    if(event.data === "aosreply:success:readsetting:darkmode:true"){
        document.body.classList.add("darkMode");
    }else if(event.data.indexOf("aosreply:success:readsetting:customstyle:") === 0){
        getId("aosCustomStyle").innerHTML = event.data.substring(41, event.data.length);
    }
}
window.addEventListener("message", getStyleInfo);

if(window.self !== window.top){
    window.top.postMessage("aos:readsetting:darkmode");
    window.top.postMessage("aos:readsetting:customstyle");
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
        name: "Blue-Green-Red",
        func: function(amount){
            return 'rgb(' +
                ((amount > 200) * ((amount - 200) * 4.6)) + ',' +
                (amount - ((amount > 220) * ((amount - 220) * 7.2))) + ',' +
                (255 - amount) + ')';
        }
    },
    bluegreen: {
        name: "Blue-Green",
        func: function(amount){
            return 'rgb(0,' + amount + ',' + (255 - amount) + ')';
        }
    },
    fire: {
        name: "Fire",
        func: function(amount){
            return 'rgb(' +
                amount + ',' +
                ((amount > 200) * amount * 0.25 + (amount > 127) * amount * 0.5 + (amount <= 127) * amount * 0.1) + ',0)';
        }
    },
    white: {
        name: "White",
        func: function(amount){
            return '#FFF';
        }
    },
    red: {
        name: "Red",
        func: function(amount){
            return '#A00';
        }
    },
    redglow: {
        name: "Red Glow",
        func: function(amount){
            return 'rgba(255,0,0,' + (amount / 255) + ')';
        }
    },
    green: {
        name: "Green",
        func: function(amount){
            return '#0A0';
        }
    },
    greenglow: {
        name: "Green Glow",
        func: function(amount){
            return 'rgba(0,255,0,' + (amount / 255) + ')';
        }
    },
    blue: {
        name: "Blue",
        func: function(amount){
            return '#00A';
        }
    },
    blueglow: {
        name: "Blue Glow",
        func: function(amount){
            return 'rgba(0,0,255,' + (amount / 255) + ')';
        }
    },
    indigo: {
        name: "Indigo",
        func: function(amount){
            return 'indigo';
        }
    }
}
var getColor = colors.bluegreenred.func;

function setColor(newcolor){
    getColor = (colors[newcolor] || colors.bgr).func;
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
        name: "None",
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
    spikes: {
        name: "Spikes",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            var step = size[0] / 1024;
            var last = -1;
            var heightFactor = size[1] / 255;
            for(var i = 0; i < 1024; i++){
                var strength = 0;
                if(i === 0){
                    strength = visData[i];
                    canvas.strokeStyle = getColor(strength);
                    canvas.beginPath();
                    canvas.moveTo(0.5, size[1]);
                    canvas.lineTo(0.5, (255 - strength) * heightFactor);
                    canvas.stroke();
                }else{
                    var last = Math.floor(step * (i - 1));
                    var curr = Math.floor(step * i);
                    var next = Math.floor(step * (i + 1));
                    if(last < curr - 1){
                        // stretched
                        for(var j = 0; j < curr - last - 1; j++){
                            //strength = ((j + 1) / (curr - last + 1) * visData[i - 1] + (curr - last - j + 1) / (curr - last + 1) * visData[i]);
                            strength = (visData[i] + visData[i - 1]) / 2;
                            canvas.strokeStyle = getColor(strength);
                            canvas.beginPath();
                            canvas.moveTo(curr - j - 0.5, size[1]);
                            canvas.lineTo(curr - j - 0.5, (255 - strength) * heightFactor);
                            canvas.stroke();
                        }
                        strength = visData[i];
                        canvas.strokeStyle = getColor(strength);
                        canvas.beginPath();
                        canvas.moveTo(curr + 0.5, size[1]);
                        canvas.lineTo(curr + 0.5, (255 - strength) * heightFactor);
                        canvas.stroke();
                    }else if(curr === last && next > curr){
                        // compressed
                        for(var j = 0; j < (1 / step); j++){
                            strength += visData[i - j];
                        }
                        strength /= Math.floor(1 / step) + 1;
                        canvas.strokeStyle = getColor(strength);
                        canvas.beginPath();
                        canvas.moveTo(curr + 0.5, size[1]);
                        canvas.lineTo(curr + 0.5, (255 - strength) * heightFactor);
                        canvas.stroke();
                    }else if(last === curr - 1){
                        strength = visData[i];
                        canvas.strokeStyle = getColor(strength);
                        canvas.beginPath();
                        canvas.moveTo(curr + 0.5, size[1]);
                        canvas.lineTo(curr + 0.5, (255 - strength) * heightFactor);
                        canvas.stroke();
                    }
                }
            }
            updateSmoke();
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
            var left = size[0] * 0.1;
            var maxWidth = size[0] * 0.8;
            var barWidth = maxWidth / 96;
            var barSpacing = maxWidth / 64;
            var maxHeight = size[1] * 0.5 - size[1] * 0.2;
            
            for(var i = 0; i < 64; i++){
                var strength = 0;
                for(var j = 0; j < 16; j++){
                    //strength = Math.max(visData[i * 16 + j], strength);
                    //strength += visData[i * 16 + j];
                    //strength += Math.pow(visData[i * 16 + j], 2) / 255;
                    strength += Math.sqrt(visData[i * 16 + j]) * this.sqrt255;
                }
                strength = Math.round(strength / 16);
                
                canvas.fillStyle = getColor(strength);
                canvas.fillRect(
                    Math.round(left + i * barSpacing),
                    Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight),
                    Math.round(barWidth),
                    Math.round(strength / 255 * maxHeight + 5)
                );
            }
            updateSmoke(left, size[1] * 0.2, maxWidth, size[1] * 0.3 + 10);
            canvas.fillStyle = '#FFF';
            canvas.font = (size[1] * 0.25) + 'px aosProFont, sans-serif';
            canvas.fillText((fileNames[currentSong] || ["No Song"])[0].toUpperCase(), Math.round(left), size[1] * 0.75, Math.floor(maxWidth));
        },
        stop: function(){
            
        },
        sqrt255: Math.sqrt(255)
    },/*
    monstercat2: {
        name: "Monstercat 2",
        start: function(){
            if(!getId("monstercat2canvas")){
                getId("visualizer").innerHTML += '<canvas id="monstercat2canvas" width="' + size[0] + '" height="' + size[1] + '" style="z-index:900;width:100%;height:100%;filter:blur(' + Math.round((size[0] + size[1]) / 50) + 'px) brightness(3)"></canvas>';
                //getId("visualizer").style.backgroundColor = "#1A1A1A";
                canvasElement = getId("visCanvas");
                canvas = canvasElement.getContext("2d");
                canvasElement.style.backgroundImage = 'url(smoke_transparent.png)';
                this.smokePos = 0;
                canvasElement.style.backgroundPosition = "0";
                this.mc2ctx = getId("monstercat2canvas").getContext("2d");
            }
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            var left = size[0] * 0.1;
            var maxWidth = size[0] * 0.8;
            var barWidth = maxWidth / 96;
            var barSpacing = maxWidth / 64;
            var maxHeight = size[1] * 0.5 - size[1] * 0.2;
            
            for(var i = 0; i < 64; i++){
                var strength = 0;
                for(var j = 0; j < 16; j++){
                    //strength = Math.max(visData[i * 16 + j], strength);
                    strength += visData[i * 16 + j];
                }
                strength /= 16;
                
                canvas.fillStyle = getColor(strength);
                canvas.fillRect(
                    Math.round(left + i * barSpacing),
                    Math.floor(size[1] / 2) - Math.round(strength / 255 * maxHeight),
                    Math.round(barWidth),
                    Math.round(strength / 255 * maxHeight + 5)
                );
            }
            canvas.fillStyle = '#FFF';
            canvas.font = (size[1] * 0.25) + 'px aosProFont, sans-serif';
            canvas.fillText((fileNames[currentSong] || ["No Song"])[0].toUpperCase(), Math.round(left), size[1] * 0.75, Math.floor(maxWidth));
            this.mc2ctx.clearRect(0, 0, size[0], size[1]);
            this.mc2ctx.putImageData(canvas.getImageData(left, size[1] * 0.2, maxWidth, size[1] * 0.3 + 10), left, size[1] * 0.2);
            this.smokePos++;
            if(this.smokePos >= size[1]){
                smokePos = size[1];
            }
            canvasElement.style.backgroundPosition = (this.smokePos * 2) + "px " + this.smokePos +  "px";
        },
        stop: function(){
            if(getId("monstercat2canvas")){
                this.mc2ctx = null;
                getId("monstercat2canvas").style.filter = "";
                getId("visualizer").removeChild(getId("monstercat2canvas"));
                //getId("visualizer").style.backgroundColor = "";
                canvasElement.style.backgroundImage = "";
                canvasElement.style.backgroundPosition = "";
            }
        },
        sizechange: function(){
            getId("monstercat2canvas").width = size[0];
            getId("monstercat2canvas").height = size[1];
            getId("monstercat2canvas").style.filter = "blur(" + Math.round((size[0] + size[1]) / 50) + "px) brightness(3)";
        },
        smokePos: 0,
        mc2ctx: null
    },*/
    rings: {
        name: "Rings",
        start: function(){
            this.ringPositions = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            var ringHeight = Math.round(Math.min(size[0], size[1]) * 0.8);
            var ringWidth = Math.round(ringHeight * 0.023);
            canvas.lineWidth = ringWidth;
            canvas.lineCap = "round";
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
                this.ringPositions[i] += strength * 5;
                if(this.ringPositions[i] >= 360){
                    this.ringPositions[i] -= 360;
                }
                canvas.strokeStyle = getColor(strength * 255);
                this.degArc(center[0], center[1], ringWidth * 2 * (i + 1), this.ringPositions[i], this.ringPositions[i] + 180);
            }
            updateSmoke(size[0] / 2 - ringHeight / 2, size[1] / 2 - ringHeight / 2, ringHeight, ringHeight);
        },
        stop: function(){
            canvas.lineWidth = 1;
            canvas.lineCap = "butt";
        },
        ringPositions: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        TAU: Math.PI * 2,
        degArc: function(x, y, r, a, b){
            canvas.beginPath();
            canvas.arc(x, y, r, (a / 360) * this.TAU, (b / 360) * this.TAU);
            canvas.stroke();
        }
    },
    spectrogram: {
        name: "Spectrogram",
        start: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
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
        stop: function(){
            
        }
    },
    spectrum: {
        name: "Spectrum",
        start: function(){
            
        },
        frame: function(){
            canvas.clearRect(0, 0, size[0], size[1]);
            var step = size[0] / 1024;
            var last = -1;
            for(var i = 0; i < 1024; i++){
                var strength = 0;
                if(i === 0){
                    strength = visData[i];
                    canvas.strokeStyle = getColor(strength);
                    canvas.beginPath();
                    canvas.moveTo(0.5, size[1]);
                    canvas.lineTo(0.5, 0);
                    canvas.stroke();
                }else{
                    var last = Math.floor(step * (i - 1));
                    var curr = Math.floor(step * i);
                    var next = Math.floor(step * (i + 1));
                    if(last < curr - 1){
                        // stretched
                        for(var j = 0; j < curr - last - 1; j++){
                            //strength = ((j + 1) / (curr - last + 1) * visData[i - 1] + (curr - last - j + 1) / (curr - last + 1) * visData[i]);
                            strength = (visData[i] + visData[i - 1]) / 2;
                            canvas.strokeStyle = getColor(strength);
                            canvas.beginPath();
                            canvas.moveTo(curr - j - 0.5, size[1]);
                            canvas.lineTo(curr - j - 0.5, 0);
                            canvas.stroke();
                        }
                        strength = visData[i];
                        canvas.strokeStyle = getColor(strength);
                        canvas.beginPath();
                        canvas.moveTo(curr + 0.5, size[1]);
                        canvas.lineTo(curr + 0.5, 0);
                        canvas.stroke();
                    }else if(curr === last && next > curr){
                        // compressed
                        for(var j = 0; j < (1 / step); j++){
                            strength += visData[i - j];
                        }
                        strength /= Math.floor(1 / step) + 1;
                        canvas.strokeStyle = getColor(strength);
                        canvas.beginPath();
                        canvas.moveTo(curr + 0.5, size[1]);
                        canvas.lineTo(curr + 0.5, 0);
                        canvas.stroke();
                    }else if(last === curr - 1){
                        strength = visData[i];
                        canvas.strokeStyle = getColor(strength);
                        canvas.beginPath();
                        canvas.moveTo(curr + 0.5, size[1]);
                        canvas.lineTo(curr + 0.5, 0);
                        canvas.stroke();
                    }
                }
            }
        },
        stop: function(){
            
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
                if(visData[i] > 75){
                    avg += visData[i];
                    avgtotal++;
                }
            }
            avg /= (avgtotal || 1);
            //avg /= 1024;
            //avg *= 255;
            canvas.fillStyle = getColor(avg);
            canvas.fillRect(0, 0, size[0], size[1]);
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