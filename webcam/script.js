function getId(target){
    return document.getElementById(target);
}
window.aosTools_connectListener = function(){
    aosTools.disablePadding();
    if(ready || warned){
        aosTools.openWindow();
    }
}
window.aosTools_connectFailListener = function(){
    console.log("Not loaded in AaronOS. We're standalone.");
}
if(typeof aosTools === "object"){
    aosTools.testConnection();
}

var warned = 0;
function showWarning(text){
    getId("warnings").style.display = "";
    getId("warnings").innerHTML += text + '<br>';
    if(!warned){
        warned = 1;
        if(aosTools){
            aosTools.openWindow();
        }
    }
}

// date func from aOS
var tempDate;
var date;
var skipKey;
var tempDayt;
var dateDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
var dateForms = {
    D: function(){ // day number
        tempDate += date.getDate();
    },
    d: function(){ // day of week
        tempDate += dateDays[date.getDay()];
    },
    y: function(){ // 2-digit year
        tempDate += String(date.getFullYear() - 2000);
    },
    Y: function(){ // 4-digit year
        tempDate += date.getFullYear();
    },
    h: function(){ // 12-hour time
        if(date.getHours() > 12){
			tempDayt = String((date.getHours()) - 12);
		}else{
			tempDayt = String(date.getHours());
		}
		if(tempDayt === "0"){
			tempDate += "12";
		}else{
			tempDate += tempDayt;
		}
    },
    H: function(){ // 24-hour time
        tempDate += String(date.getHours());
    },
    s: function(){ // milliseconds
        if(date.getMilliseconds() < 10){
		    tempDate += '00' + date.getMilliseconds();
	    }else if(date.getMilliseconds() < 100){
	        tempDate += '0' + date.getMilliseconds();
	    }else{
	        tempDate += date.getMilliseconds();
	    }
    },
    S: function(){ // seconds
        tempDayt = String(date.getSeconds());
		if(tempDayt < 10){
            tempDate += "0" + tempDayt;
		}else{
            tempDate += tempDayt;
		}
    },
    m: function(){ // minutes
        tempDayt = date.getMinutes();
		if(tempDayt < 10){
            tempDate += "0" + tempDayt;
		}else{
            tempDate += tempDayt;
		}
    },
    M: function(){ // month
        if(date.getMonth() < 9){
            tempDate += "0" + String(date.getMonth() + 1);
        }else{
            tempDate += String(date.getMonth() + 1);
        }
    },
    "-": function(){ // escape character
        
    }
};
// function to use above functions to form a date string
function formDate(dateStr){
	tempDate = "";
	date = new Date();
	skipKey = 0;
	// loops thru characters and replaces them with the date
	for(var dateKey in dateStr){
		if(skipKey){
			skipKey = 0;
		}else{
		    if(dateForms[dateStr[dateKey]]){
		        dateForms[dateStr[dateKey]]();
		    }else{
		        tempDate += dateStr[dateKey];
		    }
		}
	}
	return tempDate;
}

// alert helper
function makeAlert(text){
    if(aosTools.connected){
        aosTools.alert({
            content: text,
            button: "Okay"
        });
    }else{
        alert(text);
    }
}

var dirmode = 0;
var localdir = null;
var dir = null;
var pictureDir = null;
var videoDir = null;
var motionDir = null;
// set output directory
function setOutputDir(){
    if(window.showDirectoryPicker){
        window.showDirectoryPicker().then((handle) => {
            localdir = handle;
            if(localdir.name === "AaronOS Webcam"){
                console.log("User selected the AaronOS Webcam directory, we don't need to use subfolder.");
                dir = localdir;
                dir.getDirectoryHandle("Pictures", {create: 'true'}).then((pichandle) => {
                    pictureDir = pichandle;
                }).catch((err) => {showWarning(err);});
                dir.getDirectoryHandle("Videos", {create: 'true'}).then((vidhandle) => {
                    videoDir = vidhandle;
                }).catch((err) => {showWarning(err);});
                dir.getDirectoryHandle("Motion Camera", {create: 'true'}).then((mothandle) => {
                    motionDir = mothandle;
                }).catch((err) => {showWarning(err);});
            }else{
                console.log("User selected a generic directory, we will use AaronOS Webcam subfolder.");
                localdir.getDirectoryHandle("AaronOS Webcam", {create: 'true'}).then((dirhandle) => {
                    dir = dirhandle;
                    dir.getDirectoryHandle("Pictures", {create: 'true'}).then((pichandle) => {
                        pictureDir = pichandle;
                    }).catch((err) => {showWarning(err);});
                    dir.getDirectoryHandle("Videos", {create: 'true'}).then((vidhandle) => {
                        videoDir = vidhandle;
                    }).catch((err) => {showWarning(err);});
                    dir.getDirectoryHandle("Motion Camera", {create: 'true'}).then((mothandle) => {
                        motionDir = mothandle;
                    }).catch((err) => {showWarning(err);});
                });
            }
            dirmode = 1;
            totalOutputs = 0;
            getId("saveCount").innerHTML = totalOutputs;
            if(!haveOutputted){
                getId("gallerybutton").classList.add("nodisplay");
            }
        }).catch((err) => {showWarning(err);});
    }else{
        makeAlert("Your browser does not support this feature. Try a Chromium-based browser.")
    }
}
/*
function setOutputDir(){
    BrowserFS.install(window);
    BrowserFS.configure({
        fs: 'HTML5FS',
        // HOW THE HELL DO I LOAD A LOCAL DIRECTORY???
        // i'll do this later.
    }, function(e) {
        if (e) {
          makeAlert("An error occurred loading your filesystem");
          throw e;
        }
    });
    var fs = require('fs');
}
*/

var video = getId("video");
var canvas = getId("intermediate");
var canvas2 = getId("intermediate2");
var ctx = canvas.getContext("2d");
var ctx2 = canvas2.getContext("2d");
var size = [0, 0];
var ready = 0;
var camStream = null;
function startCamera(){
    if(navigator.mediaDevices.getUserMedia){
        navigator.mediaDevices.getUserMedia({ video: true, audio: true})
            .then((stream) => {
                camStream = stream;
                video.srcObject = camStream;
            })
            .catch((err) => {
                showWarning("Something went wrong accessing your camera.");
                showWarning(err);
            });
    }else{
        showWarning("Your browser does not have webcam support.");
        showWarning("navigator.mediaDevices.getUserMedia does not exist.");
    }
}

function feedReady(){
    size = [video.videoWidth, video.videoHeight];
    video.width = size[0];
    video.height = size[1];
    canvas.width = size[0];
    canvas.height = size[1];
    canvas2.width = size[0];
    canvas2.height = size[1];
    // VP8 and VP9 cause random corrpution
    /*if(MediaRecorder.isTypeSupported("video/webm;codecs=vp9")){
        recorder = new MediaRecorder(camStream, {mimeType: 'video/webm;codecs=vp9'});
    }else if(MediaRecorder.isTypeSupported("video/webm;codecs=vp8")){
        recorder = new MediaRecorder(camStream, {mimeType: 'video/webm;codecs=vp8'});
    }else */
    if(MediaRecorder.isTypeSupported("video/webm")){
        recorder = new MediaRecorder(camStream, {mimeType: 'video/webm'});
    }else{
        recorder = new MediaRecorder(camStream);
    }
    recorder.addEventListener('dataavailable', recordFrame);
    recorder.addEventListener("stop", recordSave);
    ready = 1;
    if(aosTools){
        aosTools.getBorders((response) => {
            aosTools.setDims({
                x: "auto",
                y: "auto",
                width: size[0] + response.content.left + response.content.right,
                height: size[1] + response.content.top + response.content.bottom
            }, () => {
                aosTools.openWindow();
            });
        });
    }
}

var totalOutputs = 0;
var haveOutputted = 0;
function outputNewline(){
    var newline = document.createElement("br");
    getId("output").appendChild(newline);
    requestAnimationFrame(() => {
        getId("output").scrollTop = getId("output").scrollHeight;
    });
    haveOutputted = 1;
}
function outputText(text){
    var newtext = document.createElement("p");
    newtext.innerHTML = text;
    getId("output").appendChild(newtext);
    totalOutputs++;
    getId("saveCount").innerHTML = totalOutputs;
    requestAnimationFrame(() => {
        getId("output").scrollTop = getId("output").scrollHeight;
    });
    getId("warnings").style.display = "none";
    haveOutputted = 1;
}
function outputIncrement(){
    totalOutputs++;
    getId("saveCount").innerHTML = totalOutputs;
    getId("warnings").style.display = "none";
}
var lastOutput = "";
var outputConflicts = 1;

function takeImage(){
    if(ready){
        var videoname = "aOS " + formDate("Y.M.D H.m.S");
        if(videoname === lastOutput){
            outputConflicts++;
            lastOutput = videoname;
            videoname += " (" + outputConflicts + ")";
        }else{
            lastOutput = videoname;
        }
        ctx.drawImage(video, 0, 0, size[0], size[1]);
        if(dirmode){
            canvas.toBlob((newBlob) => {
                pictureDir.getFileHandle(videoname + ".png", {create: 'true'}).then((handle) => {
                    handle.createWritable().then((writable) => {
                        writable.write(newBlob);
                        writable.close();
                        outputIncrement();
                    }).catch((err) => {showWarning(err);});
                }).catch((err) => {showWarning(err);});
            });
        }else{
            var imgData = canvas.toDataURL('image/png');
            var newImg = document.createElement("img");
            newImg.src = imgData;
            outputText('<a style="" href="' + newImg.src + '" download="' + videoname + '.png">Save as File</a> | ' + videoname);
            getId("output").appendChild(newImg);
        }
    }
}

var recorder = null;
var dontSaveVideo = 0;
var recording = 0;
var recordData;
var starttime = 0;
function recordVideo(){
    if(!recording){
        recording = 1;
        recordData = [];
        recorder.start();
        starttime = Date.now();
        if(!dontSaveVideo){
            getId("recordbutton").classList.add("recording");
            getId("recordbutton").setAttribute("onclick", "recordStop()");
            // getId("recordbutton").innerHTML = "Stop Recording";
            getId("motionbutton").classList.add("disabled");
            getId("motionmenubutton").classList.add("disabled");
            getId("photobutton").classList.add("disabled");
        }
    }
}
var totalMotionsRecorded = 0;
function recordFrame(event){
    if(dontSaveVideo){
        if(savingMotion === 2){
            savingMotion = 0;
            totalMotionsRecorded++;
            if(event.data.size > 0){
                recordData.push(event.data);
                var videoLength = Date.now() - starttime;
                ysFixWebmDuration(recordData[0], videoLength, (fixedBlob) => {
                    var videoname = "aOS " + " " + formDate("Y.M.D H.m.S") + " m" + totalMotionsRecorded;
                    if(dirmode){
                        motionSessionDir.getFileHandle(videoname + ".webm", {create: 'true'}).then((handle) => {
                            handle.createWritable().then((writable) => {
                                writable.write(fixedBlob);
                                writable.close();
                                outputIncrement();
                            }).catch((err) => {showWarning(err);});
                        }).catch((err) => {showWarning(err);});
                    }else{
                        var newVideo = document.createElement("video");
                        // newVideo.src = URL.createObjectURL(new Blob(recordData));
                        newVideo.src = URL.createObjectURL(fixedBlob);
                        newVideo.download = videoname + ".webm";
                        newVideo.setAttribute("controls", "true");
                        newVideo.setAttribute("preload", "auto");
                        outputText('<a style="" href="' + newVideo.src + '" download="' + videoname + '.webm">Save as File</a> | ' + videoname);
                        getId("output").appendChild(newVideo);
                    }
                });
            }
        }
    }else{
        if(event.data.size > 0){
            recordData.push(event.data);
        }
    }
}
function recordSave(){
    if(!dontSaveVideo){
        recording = 0;
        var videoLength = Date.now() - starttime;
        ysFixWebmDuration(recordData[0], videoLength, (fixedBlob) => {
            var videoname = "aOS " + formDate("Y.M.D H.m.S");
            if(videoname === lastOutput){
                outputConflicts++;
                lastOutput = videoname;
                videoname += " (" + outputConflicts + ")";
            }else{
                lastOutput = videoname;
            }
            if(dirmode){
                videoDir.getFileHandle(videoname + ".webm", {create: 'true'}).then((handle) => {
                    handle.createWritable().then((writable) => {
                        writable.write(fixedBlob);
                        writable.close();
                        outputIncrement();
                    }).catch((err) => {showWarning(err);});
                }).catch((err) => {showWarning(err);});
            }else{
                var newVideo = document.createElement("video");
                // newVideo.src = URL.createObjectURL(new Blob(recordData));
                newVideo.src = URL.createObjectURL(fixedBlob);
                newVideo.download = videoname + ".webm";
                newVideo.setAttribute("controls", "true");
                newVideo.setAttribute("preload", "auto");
                outputText('<a style="" href="' + newVideo.src + '" download="' + videoname + '.webm">Save as File</a> | ' + videoname);
                getId("output").appendChild(newVideo);
            }
        });
    }else{
        recording = 0;
        if(motionCamInterval){
            recordVideo();
        }else{
        // if(!motionCamInterval){
            dontSaveVideo = 0;
            ctx2.clearRect(0, 0, size[0], size[1]);
        }
    }
    recordData = [];
}
function recordStop(){
    if(recording){
        recorder.stop();
        if(!dontSaveVideo){
            getId("recordbutton").classList.remove("recording");
            getId("recordbutton").setAttribute("onclick", "recordVideo()");
            // getId("recordbutton").innerHTML = "Record Video";
            getId("motionbutton").classList.remove("disabled");
            getId("motionmenubutton").classList.remove("disabled");
            getId("photobutton").classList.remove("disabled");
        }
    }
}

var motionCamInterval = null;
var lastMotionTime = 0;
var lastChunkTime = 0;
var motionCamSensitivity = 0;
var motionCamThreshold = 0;
var savingMotion = 0;
var motionSessionDir = null;
var needToStopMotion = 0;
function motionCamStart(){
    if(!recording){
        dontSaveVideo = 1;
        motionCamInterval = setInterval(motionCamFrame, 150);
        lastMotionTime = Date.now();
        lastChunkTime = Date.now();
        ctx.drawImage(video, 0, 0, size[0], size[1]);
        getId("motionmenubutton").classList.add("recording");
        getId("motionbutton").setAttribute("onclick", "motionCamStop()");
        getId("motionbutton").innerHTML = "Stop Monitoring";
        getId("photobutton").classList.add("disabled");
        getId("recordbutton").classList.add("disabled");
        // getId("motionsettings").classList.add("disabled");
        motionCamSensitivity = (100 - parseFloat(getId("motionsensitivity").value)) / 100 * 765;
        motionCamThreshold = parseFloat(getId("motionthreshold").value) / 100 * (size[0] * size[1]);
        recordVideo();
        totalMotionsRecorded = 0;

        if(dirmode){
            motionDir.getDirectoryHandle("Session " + formDate("Y.M.D H.m.S"), {create: 'true'}).then((handle) => {
                motionSessionDir = handle;
            }).catch((err) => {showWarning(err);});
        }
    }
}
function motionCamFrame(){
    var motionAmount = 0;
    var motionDetected = 0;
    
    // this is going to destroy what little mental fortitude i have left
    var img1 = ctx.getImageData(0, 0, size[0], size[1]);
    ctx.drawImage(video, 0, 0, size[0], size[1]);
    var img2 = ctx.getImageData(0, 0, size[0], size[1]);
    var dataLength = img1.data.length;
    for(var i = 0; i < dataLength; i += 4){
        var diffs = [
            Math.abs(img2.data[i] - img1.data[i]),
            Math.abs(img2.data[i + 1] - img1.data[i + 1]),
            Math.abs(img2.data[i + 2] - img1.data[i + 2])
        ];
        var diff = diffs[0] + diffs[1] + diffs[2];
        if(diff >= motionCamSensitivity){
            motionAmount++;
            img2.data[i] = 255;
            img2.data[i + 1] = 0;
            img2.data[i + 2] = 0;
            img2.data[i + 3] = 127;
        }else{
            var visualDiff = diff / motionCamSensitivity * 127;
            img2.data[i] = 0;
            img2.data[i + 1] = 255;
            img2.data[i + 2] = 0;
            img2.data[i + 3] = visualDiff;
        }
    }
    ctx2.putImageData(img2, 0, 0);
    if(motionAmount >= motionCamThreshold){
        motionDetected = 1;
    }
    // end mental fortitude destruction
    // edit: nevermind, getting it to actually record automatically was worse.
    // abandon hope, all ye who attempt to read into that.

    getId("motionamount").innerHTML = Math.round(motionAmount / (size[0] * size[1]) * 1000) / 10 + "%";

    if(motionDetected){
        getId("motionIndicator").style.background = "#F00";
        savingMotion = 1;
        lastMotionTime = Date.now();
        if(needToStopMotion){
            savingMotion = 2;
            lastChunkTime = Date.now();
            recordStop();
            actuallyStopMotionCam();
        }
    }else{
        getId("motionIndicator").style.background = "linear-gradient(0deg, #0F0 " + Math.round(motionAmount / motionCamThreshold * 100) + "%, transparent " + Math.round(motionAmount / motionCamThreshold * 100) + "%)";
        if(savingMotion === 1){
            if(needToStopMotion || Date.now() - lastMotionTime > 1000){
                savingMotion = 2;
                recordStop();
                lastChunkTime = Date.now();
                if(needToStopMotion){
                    actuallyStopMotionCam();
                }
            }
        }else if(savingMotion === 0){
            if(needToStopMotion || Date.now() - lastChunkTime > 1000){
                lastChunkTime = Date.now();
                recordStop();
                if(needToStopMotion){
                    actuallyStopMotionCam();
                }
            }
        }
    }

    // if(recorder.state === "inactive"){
    //     recording = 0;
    //     recordVideo();
    //     lastChunkTime = Date.now();
    // }
}
function motionCamStop(){
    needToStopMotion = 1;
}
function actuallyStopMotionCam(){
    clearInterval(motionCamInterval);
    motionCamInterval = 0;
    // if(recording){
    //    recordStop();
    // }
    getId("motionmenubutton").classList.remove("recording");
    getId("motionbutton").setAttribute("onclick", "motionCamStart()");
    getId("motionbutton").innerHTML = "Motion Camera";
    getId("photobutton").classList.remove("disabled");
    getId("recordbutton").classList.remove("disabled");
    // getId("motionsettings").classList.remove("disabled");
    getId("motionamount").innerHTML = "";
    getId("motionIndicator").style.background = "";
    needToStopMotion = 0;
}

function toggleOutput(){
    getId("output").classList.toggle('nodisplay');
    getId("helpbutton").classList.toggle('disabled');
    requestAnimationFrame(() => {
        getId("output").scrollTop = getId("output").scrollHeight;
    });
}
function toggleHelp(){
    if(!dirmode){
        getId("gallerybutton").classList.toggle('disabled');
    }
    getId("help").classList.toggle('nodisplay');
    getId("folderbutton").classList.toggle('disabled');
}
function toggleMotionMenu(){
    getId("motionmenu").classList.toggle('nodisplay');
}

startCamera();