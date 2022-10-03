var aosToolsConnected = 0;

window.aosTools_connectListener = function(){
    aosToolsConnected = 1;
    aosTools.openWindow();
    aosTools.disablePadding();
}

window.aosTools_connectFailListener = function(){
    var newStyle = document.createElement("link");
    newStyle.setAttribute("rel", "stylesheet");
    newStyle.setAttribute("href", "https://aaronos.dev/AaronOS/styleBeta.css");
    document.head.prepend(newStyle);
}

if(typeof aosTools === "object"){
    aosTools.testConnection();
}

function getId(target){
    return document.getElementById(target);
}

var project = {
    aosFlashCards: 1,
    cards: [
        {
            q: "water",
            a: [
                "水",
                "みず",
                "mizu"
            ]
        },
        {
            q: "rice",
            a: [
                "ご飯",
                "ごはん",
                "gohan"
            ]
        },
        {
            q: "tea",
            a: [
                "お茶",
                "おちゃ",
                "ocha"
            ]
        }
    ]
};

var studyFails = 0;
var studyWins = 0;
var studyQueue = [];
var studyIndex = 0;
var studyTotal = 0;
var studyOrder = "shuffle";
var studyOrders = {
    shuffle: {
        title: "Shuffled",
        sort: function(){
            // https://bost.ocks.org/mike/shuffle/compare.html
            var m = studyQueue.length, t, i;
            while (m) {
                i = Math.floor(Math.random() * m--);
                t = studyQueue[m];
                studyQueue[m] = studyQueue[i];
                studyQueue[i] = t;
            }
        }
    },
    alpha: {
        title: "Alphabetical (Q)",
        sort: function(){
            return studyQueue.sort((a, b) => {return a.q.localeCompare(b.q);});
        }
    },
    alphaReverse: {
        title: "Alphabetical (A)",
        sort: function(){
            return studyQueue.sort((a, b) => {return a.a[0].localeCompare(b.a[0]);});
        }
    },
    date: {
        title: "Creation Date",
        sort: function(){
            // it's already sorted by creation date
            return studyQueue;
        }
    }
};
var studyOrderHTML = "";
for(var i in studyOrders){
    studyOrderHTML += '<option value="' + i + '">' + studyOrders[i].title + '</option>';
}
getId("studyOrder").innerHTML = studyOrderHTML;
studyOrderHTML = "";
getId("studyOrder").value = "shuffle";
function setStudyOrder(newOrder){
    studyOrder = newOrder;
    resetStudy();
}

var studyType = "quizRandom";
var studyTypes = {
    quiz: {
        title: "Quiz (Q -&gt; A)",
        display: function(){
            var tempHTML = "";
            for(var i = 0; i < studyQueue.length; i++){
                tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                    '<div class="quizQ">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                    '<div class="quizWrapper">' +
                    '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                    '<input id="quizInput_' + i + '" class="quizInput" autocomplete="off" onkeypress="if(event.keyCode === 13){getId(\'quizSubmit_' + i + '\').click();getId(\'quizNext_' + i + '\').focus();}"> ' +
                    '<button onclick="studyTypes.quiz.test(this)" data-quiz-index="' + i + '" id="quizSubmit_' + i + '">Submit</button> &nbsp; ' +
                    '<button onclick="studyNext()" id="quizNext_' + i + '">Next Card</button>' +
                    '</div>' +
                    '<div id="quizResult_' + i + '" class="quizA hidden">' +
                    studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                    '</div>' +
                    '</div>';
            }
            getId("studyContent").innerHTML = tempHTML;
        },
        test: function(elem){
            var cardIndex = parseInt(elem.getAttribute("data-quiz-index"));
            getId("quizInput_" + cardIndex).classList.add("disabled");
            elem.classList.add("disabled");
            var matched = 0;
            for(var i in studyQueue[cardIndex].a){
                if(studyQueue[cardIndex].a[i].toLowerCase().trim() === getId("quizInput_" + cardIndex).value.toLowerCase().trim()){
                    matched = 1;
                }
            }
            if(matched){
                getId("quizResult_" + cardIndex).style.backgroundColor = 'rgba(0, 127, 0, 0.25)';
                getId("quizResult_" + cardIndex).style.color = 'rgb(0, 170, 0)';
                studyWins++;
            }else{
                getId("quizResult_" + cardIndex).style.backgroundColor = 'rgba(127, 0, 0, 0.25)';
                getId("quizResult_" + cardIndex).style.color = 'rgb(170, 0, 0)';
                studyFails++;
            }
            studyProgress();
            getId("quizResult_" + cardIndex).classList.remove("hidden");
        }
    },
    quizReverse: {
        title: "Quiz (A -&gt; Q)",
        display: function(){
            var tempHTML = "";
            for(var i = 0; i < studyQueue.length; i++){
                tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                    '<div class="quizA">' +
                    studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                    '</div>' +
                    '<div class="quizWrapper">' +
                    '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                    '<input id="quizInput_' + i + '" class="quizInput" autocomplete="off" onkeypress="if(event.keyCode === 13){getId(\'quizSubmit_' + i + '\').click();getId(\'quizNext_' + i + '\').focus();}"> ' +
                    '<button onclick="studyTypes.quizReverse.test(this)" data-quiz-index="' + i + '" id="quizSubmit_' + i + '">Submit</button> &nbsp; ' +
                    '<button onclick="studyNext()" id="quizNext_' + i + '">Next Card</button>' +
                    '</div>' +
                    '<div id="quizResult_' + i + '" class="quizQ hidden">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                    '</div>';
            }
            getId("studyContent").innerHTML = tempHTML;
        },
        test: function(elem){
            var cardIndex = parseInt(elem.getAttribute("data-quiz-index"));
            getId("quizInput_" + cardIndex).classList.add("disabled");
            elem.classList.add("disabled");
            if(studyQueue[cardIndex].q.toLowerCase().trim() === getId("quizInput_" + cardIndex).value.toLowerCase().trim()){
                getId("quizResult_" + cardIndex).style.backgroundColor = 'rgba(0, 127, 0, 0.25)';
                getId("quizResult_" + cardIndex).style.color = 'rgb(0, 170, 0)';
                studyWins++;
            }else{
                getId("quizResult_" + cardIndex).style.backgroundColor = 'rgba(127, 0, 0, 0.25)';
                getId("quizResult_" + cardIndex).style.color = 'rgb(170, 0, 0)';
                studyFails++;
            }
            studyProgress();
            getId("quizResult_" + cardIndex).classList.remove("hidden");
        }
    },
    quizRandom: {
        title: "Quiz (random)",
        display: function(){
            var tempHTML = "";
            for(var i = 0; i < studyQueue.length; i++){
                if(Math.round(Math.random())){
                    tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                        '<div class="quizQ">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                        '<div class="quizWrapper">' +
                        '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                        '<input id="quizInput_' + i + '" class="quizInput" autocomplete="off" onkeypress="if(event.keyCode === 13){getId(\'quizSubmit_' + i + '\').click();getId(\'quizNext_' + i + '\').focus();}"> ' +
                        '<button onclick="studyTypes.quiz.test(this)" data-quiz-index="' + i + '" id="quizSubmit_' + i + '">Submit</button> &nbsp; ' +
                        '<button onclick="studyNext()" id="quizNext_' + i + '">Next Card</button>' +
                        '</div>' +
                        '<div id="quizResult_' + i + '" class="quizA hidden">' +
                        studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                        '</div>' +
                        '</div>';
                }else{
                    tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                        '<div class="quizA">' +
                        studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                        '</div>' +
                        '<div class="quizWrapper">' +
                        '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                        '<input id="quizInput_' + i + '" class="quizInput" autocomplete="off" onkeypress="if(event.keyCode === 13){getId(\'quizSubmit_' + i + '\').click();getId(\'quizNext_' + i + '\').focus();}"> ' +
                        '<button onclick="studyTypes.quizReverse.test(this)" data-quiz-index="' + i + '" id="quizSubmit_' + i + '">Submit</button> &nbsp; ' +
                        '<button onclick="studyNext()" id="quizNext_' + i + '">Next Card</button>' +
                        '</div>' +
                        '<div id="quizResult_' + i + '" class="quizQ hidden">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                        '</div>';
                }
            }
            getId("studyContent").innerHTML = tempHTML;
        }
    },
    cards: {
        title: "Cards (Q -&gt; A)",
        display: function(){
            var tempHTML = "";
            for(var i = 0; i < studyQueue.length; i++){
                tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                    '<div id="cardQ_' + i + '" class="cardQ">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                    '<div id="cardA_' + i + '" class="cardA hidden">' +
                    studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                    '</div>' +
                    '<div class="quizWrapper">' +
                    '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                    '<button onclick="studyTypes.cards.flip(this)" data-card-side="q" data-card-index="' + i + '" id="quizInput_' + i + '">Flip Card</button> &nbsp; ' +
                    '<button onclick="studyNext()" id="cardNext_' + i + '">Next Card</button>' +
                    '</div>' +
                    '</div>';
            }
            getId("studyContent").innerHTML = tempHTML;
        },
        flip: function(elem){
            var cardIndex = parseInt(elem.getAttribute("data-card-index"));
            if(elem.getAttribute("data-card-side") === "q"){
                getId("cardQ_" + cardIndex).classList.add("hidden");
                getId("cardA_" + cardIndex).classList.remove("hidden");
                elem.setAttribute("data-card-side", "a");
            }else{
                getId("cardA_" + cardIndex).classList.add("hidden");
                getId("cardQ_" + cardIndex).classList.remove("hidden");
                elem.setAttribute("data-card-side", "q");
            }
        }
    },
    cardsReverse: {
        title: "Cards (A -&gt; Q)",
        display: function(){
            var tempHTML = "";
            for(var i = 0; i < studyQueue.length; i++){
                tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                    '<div id="cardQ_' + i + '" class="cardQ hidden">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                    '<div id="cardA_' + i + '" class="cardA">' +
                    studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                    '</div>' +
                    '<div class="quizWrapper">' +
                    '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                    '<button onclick="studyTypes.cards.flip(this)" data-card-side="a" data-card-index="' + i + '" id="quizInput_' + i + '">Flip Card</button> &nbsp; ' +
                    '<button onclick="studyNext()" id="cardNext_' + i + '">Next Card</button>' +
                    '</div>' +
                    '</div>';
            }
            getId("studyContent").innerHTML = tempHTML;
        }
    },
    cardsRandom: {
        title: "Cards (random)",
        display: function(){
            var tempHTML = "";
            for(var i = 0; i < studyQueue.length; i++){
                if(Math.round(Math.random())){
                    tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                        '<div id="cardQ_' + i + '" class="cardQ">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                        '<div id="cardA_' + i + '" class="cardA hidden">' +
                        studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                        '</div>' +
                        '<div class="quizWrapper">' +
                        '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                        '<button onclick="studyTypes.cards.flip(this)" data-card-side="q" data-card-index="' + i + '" id="quizInput_' + i + '">Flip Card</button> &nbsp; ' +
                        '<button onclick="studyNext()" id="cardNext_' + i + '">Next Card</button>' +
                        '</div>' +
                        '</div>';
                }else{
                    tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                        '<div id="cardQ_' + i + '" class="cardQ hidden">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                        '<div id="cardA_' + i + '" class="cardA">' +
                        studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                        '</div>' +
                        '<div class="quizWrapper">' +
                        '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                        '<button onclick="studyTypes.cards.flip(this)" data-card-side="a" data-card-index="' + i + '" id="quizInput_' + i + '">Flip Card</button> &nbsp; ' +
                        '<button onclick="studyNext()" id="cardNext_' + i + '">Next Card</button>' +
                        '</div>' +
                        '</div>';
                }
            }
            getId("studyContent").innerHTML = tempHTML;
        }
    },
    terms: {
        title: "Terms",
        display: function(){
            var tempHTML = "";
            for(var i = 0; i < studyQueue.length; i++){
                tempHTML += '<div id="studyCard_' + i + '" class="hidden quizCard">' +
                    '<div id="cardQ_' + i + '" class="termsQ">' + studyQueue[i].q.split("<").join("&lt;").split(">").join("&gt;") + '</div>' +
                    '<div id="cardA_' + i + '" class="terms">' +
                    studyQueue[i].a.join('\n').split("<").join("&lt;").split(">").join("&gt;").split('\n').join('<br>') +
                    '</div>' +
                    '<div class="quizWrapper">' +
                    '<button onclick="studyPrev()">Previous Card</button> &nbsp; ' +
                    '<button onclick="studyNext()" id="quizInput_' + i + '">Next Card</button>' +
                    '</div>' +
                    '</div>';
            }
            getId("studyContent").innerHTML = tempHTML;
        }
    },
};
var studyTypesHTML = "";
for(var i in studyTypes){
    studyTypesHTML += '<option value="' + i + '">' + studyTypes[i].title + '</option>';
}
getId("studyType").innerHTML = studyTypesHTML;
studyTypesHTML = "";
getId("studyType").value = "quizRandom";
function setStudyType(newType){
    studyType = newType;
    resetStudy();
}

function resetStudy(){
    studyFails = 0;
    studyWins = 0;
    studyTotal = project.cards.length;
    studyIndex = 0;
    studyQueue = [...project.cards];
    studyOrders[studyOrder].sort();
    getId("studyContent").innerHTML = "";
    studyTypes[studyType].display();
    getId("studyProgress").style.width = "0%";
    studyProgress();
    if(getId("studyCard_0")){
        getId("studyCard_0").classList.remove("hidden");
    }
    if(getId("quizInput_0")){
        if(!getId("quizInput_0").classList.contains("disabled")){
            getId("quizInput_0").focus();
        }
    }
}

function studyPrev(){
    if(studyIndex > 0){
        getId("studyCard_" + studyIndex).classList.add("hidden");
        studyIndex -= 1;
        getId("studyCard_" + studyIndex).classList.remove("hidden");
        studyProgress();
        if(getId("quizInput_" + studyIndex)){
            if(!getId("quizInput_" + studyIndex).classList.contains("disabled")){
                getId("quizInput_" + studyIndex).focus();
            }
        }
    }
}

function studyNext(){
    if(studyIndex < studyTotal - 1){
        getId("studyCard_" + studyIndex).classList.add("hidden");
        studyIndex += 1;
        getId("studyCard_" + studyIndex).classList.remove("hidden");
        studyProgress();
        if(getId("quizInput_" + studyIndex)){
            if(!getId("quizInput_" + studyIndex).classList.contains("disabled")){
                getId("quizInput_" + studyIndex).focus();
            }
        }
    }else{
        getId("resetStudy").focus();
    }
}

function studyProgress(){
    getId("studyProgress").style.width = ((studyIndex + 1) / (studyTotal) * 100) + "%";
    getId("studyFails").style.width = ((studyFails + studyWins) / (studyTotal) * 100) + "%";
    getId("studyWins").style.width = (studyWins / (studyTotal) * 100) + "%";
}

var studyLoaded = 0;
var editorLoaded = 0;
var screens = {
    homepage: function(){

    },
    study: function(){
        if(!studyLoaded){
            resetStudy();
            studyLoaded = 1;
        }
    },
    editor: function(){
        if(!editorLoaded){
            var tempHTML = "";
            for(var i in project.cards){
                tempHTML += '<div class="editorCard">' +
                    '<button onclick="removeCard(' + i + ')">x</button> ' +
                    '<input class="cardQuestion" onchange="updateCardQuestion(' + i + ', this.value)" placeholder="Question" value="' + project.cards[i].q.split('"').join('\"') + '"></input>' +
                    '<div class="editorAnswers">';
                for(var j in project.cards[i].a){
                    tempHTML += '<div><button onclick="removeCardAnswer(' + i + ', ' + j + ')">x</button> ' +
                        '<input class="cardAnswer" onchange="updateCardAnswer(' + i + ', ' + j + ', this.value)" placeholder="Answer" value="' + project.cards[i].a[j].split('"').join('\"') + '"></div>'
                }
                tempHTML += '</div><button onclick="addCardAnswer(' + i + ')" style="margin-left:2em;">Add New Answer</button>' +
                    '</div>';
            }
            getId("editorContent").innerHTML = tempHTML;
            editorLoaded = 1;
        }
    }
};

function newCard(){
    project.cards.push({q: "", a: [""]});
    refreshEditor();
    studyLoaded = 0;
}

function removeCard(card){
    delete project.cards[card];
    refreshEditor();
    studyLoaded = 0;
}

function updateCardQuestion(card, text){
    project.cards[card].q = text;
    studyLoaded = 0;
}

function addCardAnswer(card){
    project.cards[card].a.push("Answer");
    refreshEditor();
    studyLoaded = 0;
}

function updateCardAnswer(card, answer, text){
    project.cards[card].a[answer] = text;
    studyLoaded = 0;
}

function removeCardAnswer(card, answer){
    delete project.cards[card].a[answer];
    refreshEditor();
    studyLoaded = 0;
}

function refreshEditor(){
    editorLoaded = 0;
    screens.editor();
    studyLoaded = 0;
}

function switchScreen(screen){
    var allScreens = document.getElementsByClassName('fullscreen');
    for(var i = 0; i < allScreens.length; i++){
        allScreens[i].classList.add("hidden");
    }
    screens[screen]();
    getId(screen).classList.remove("hidden");
    getId("createButton").classList.add("disabled");
    getId("fileButtons").classList.remove("disabled");
}

function loadFile(){ // func borrowed from LineWriter app
    var file = getId('fileUploadInput').files[0];
    if(file){
        var reader = new FileReader();
        reader.readAsText(file, "UTF-8");
        reader.onload = function (evt) {
            var tempFileData = evt.target.result;
            try{
                if(JSON.parse(tempFileData).aosFlashCards){
                    project = JSON.parse(tempFileData);
                    getId("fileButtons").classList.remove("disabled");
                    getId("loadStatus").innerHTML = file.name + "<br>Loaded " + project.cards.length + " cards from file.";
                    studyLoaded = 0;
                    editorLoaded = 0;
                }else{
                    getId("loadStatus").innerHTML = "ERROR! File is not a Flash Cards Project.";
                }
            }catch(err){
                getId("loadStatus").innerHTML = "ERROR! Project file is corrupted.";
            }
            getId("fileUploadInput").value = null;
        }
        reader.onerror = function (evt) {
            getId("loadStatus").innerHTML = "ERROR! Project file could not be accessed."
            getId("fileUploadInput").value = null;
        }
    }
}

function save(){ // func borrowed from LineWriter app
    var data = JSON.stringify(project);
    var file = new Blob([data], {type: 'application/json'});
    var a = document.createElement('a');
    a.href = URL.createObjectURL(file);
    var date = new Date();
    a.download = 'aOS_FCProject_' +
        date.getFullYear() +
        ((date.getMonth() < 9) ? '0' : '') + (date.getMonth() + 1) +
        ((date.getDate() < 10) ? '0' : '') + date.getDate() + '_' +
        ((date.getHours() < 10) ? '0' : '') + date.getHours() +
        ((date.getMinutes() < 10) ? '0' : '') + date.getMinutes() +
        ((date.getSeconds() < 10) ? '0' : '') + date.getSeconds() +
        '.json';
    a.innerHTML = '<button>Download ' + a.download + '</button>';
    document.getElementById('fileDownloadSpan').innerHTML = ' =&gt; ';
    document.getElementById('fileDownloadSpan').appendChild(a);
}