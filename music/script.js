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

var analyser;

var visData;

var folderInput = getId("folderInput");
var songList = getId("songList");
var progressBar = getId("progress");
var currentlyPlaying = getId("currentlyPlaying");

var files = [];
var filesAmount = 0;
var fileNames = [];
var filesLength = 0;

var supportedFormats = ['wav', 'mp3', 'ogg', 'flac'];

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
        if(supportedFormats.indexOf(fileName.pop()) !== -1){
            filesLength++;
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
    }
}

var canvas = getId("visCanvas").getContext("2d");

function globalFrame(){
    requestAnimationFrame(globalFrame);
    if(winsize !== [window.innerWidth, window.innerHeight]){
        winsize = [window.innerWidth, window.innerHeight];
        if(fullscreen){
            size = [window.innerWidth, window.innerHeight];
        }else{
            size = [window.innerWidth - 8, window.innerHeight - 81];
        }
        getId("visCanvas").width = size[0];
        getId("visCanvas").height = size[1];
    }
    if(currVis !== "none"){
        canvas.clearRect(0, 0, size[0], size[1]);
        analyser.getByteFrequencyData(visData);
        vis[currVis].frame();
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
    redglow: {
        name: "Red Glow",
        func: function(amount){
            return 'rgb(' + amount + ',0,0)';
        }
    },
    greenglow: {
        name: "Green Glow",
        func: function(amount){
            return 'rgb(0,' + amount + ',0)';
        }
    },
    blueglow: {
        name: "Blue Glow",
        func: function(amount){
            return 'rgb(0,0,' + amount + ')';
        }
    },
    fire: {
        name: "Fire",
        func: function(amount){
            return 'rgb(' +
                amount + ',' +
                ((amount > 200) * amount * 0.25 + (amount > 127) * amount * 0.5 + (amount <= 127) * amount * 0.1) + ',0)';
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
        },
        stop: function(){
            
        }
    },
    spikes2: {
        name: "Spikes 2",
        start: function(){
            
        },
        frame: function(){
            var step = size[0] / 4096;
            var last = -1;
            var heightFactor = size[1] / 255;
            for(var i = 0; i < 4096; i++){
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
        },
        stop: function(){
            
        }
    },
    spectrum: {
        name: "Spectrum",
        start: function(){
            
        },
        frame: function(){
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
};

for(var i in colors){
    getId('colorfield').innerHTML += '<option value="' + i + '">' + colors[i].name + '</option>';
}

for(var i in vis){
    getId('visfield').innerHTML += '<option value="' + i + '">' + vis[i].name + '</option>';
}