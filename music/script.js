function getId(target){
    return document.getElementById(target);
}

var audio = new Audio();
var audioDuration = 1;
function updateProgress(){
    progressBar.style.width = audio.currentTime / audioDuration * 100 + "%";
    requestAnimationFrame(updateProgress);
}
requestAnimationFrame(updateProgress);

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
    console.log(disabledElements);
    while(disabledElements.length > 0){
        disabledElements[0].classList.remove('disabled');
    }
    console.log(disabledElements);
    getId("introduction").classList.add('disabled');
    getId("visualizer").classList.add('disabled');
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