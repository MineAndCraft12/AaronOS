function getId(target){
    return document.getElementById(target);
}

var n = "-";
var b = "|";

var tab = [
    [
    /*  [E, A, D, G, B, e]
        [b, b, b, b, b, b],
        [n, n, n, n, n, n]   */
    ]
];

var lyrics = [
    ''
];

function convertFromText(){

}

function convertToText(){
    updateTab();
    var tabArray = [];
    for(var staff in tab){
        if(tab[staff].length > 0){
            tabArray.push([
                'e|--',
                'B|--',
                'G|--',
                'D|--',
                'A|--',
                'E|--'
            ]);
            for(var column in tab[staff]){
                for(var i = 0; i < 6; i++){
                    if(tab[staff][column][i].length === 2){
                        tabArray[staff][5 - i] += tab[staff][column][i] + '--';
                    }else if(tab[staff][column][i].length === 1){
                        tabArray[staff][5 - i] += tab[staff][column][i] + '---';
                    }else if(tab[staff][column][i].length === 0){
                        tabArray[staff][5 - i] += '----';
                    }else{
                        tabArray[staff][5 - i] += '?---';
                    }
                }
            }
            if(lyrics[staff].length > 0){
                tabArray[staff] = lyrics[staff] + '\n' + tabArray[staff].join('|\n') + '|';
            }else{
                tabArray[staff] = tabArray[staff].join('|\n') + '|';
            }
        }else{
            tabArray[staff] = lyrics[staff];
        }
    }
    var tabStr = tabArray.join('\n\n');
    getId("text").value = tabStr;
    getId("text").style.height = tabStr.split('\n').length + Math.round(tabStr.split('\n').length / 7) + 'em';
}

function convertToJSON(){
    updateTab();
    getId("text").value = JSON.stringify({"lyrics": lyrics, "tab": tab});
}

function convertFromJSON(){
    updateTab();
    var jsonSuccessful = 0;
    try{
        var jsonIn = JSON.parse(getId("text").value);
        jsonSuccessful = 1;
    }catch(err){
        alert("Error loading JSON. JSON must be invalid. (parse error)\n" + err);
    }
    if(jsonSuccessful){
        if(jsonIn.hasOwnProperty('lyrics') && jsonIn.hasOwnProperty('tab')){
            var oldTab = [...tab];
            var oldLyrics = [...lyrics];
            try{
                tab = [...jsonIn.tab];
                lyrics = [...jsonIn.lyrics];
                drawTab();
            }catch(err){
                tab = [...oldTab];
                lyrics = [...oldLyrics];
                drawTab();
                alert('Error loading JSON. JSON must be invalid. (format 2)\n' + err);
            }
        }else{
            var oldTab = [...tab];
            var oldLyrics = [...lyrics];
            try{
                tab = [...jsonIn];
                lyrics = [];
                for(var staff in tab){
                    lyrics.push('');
                }
                drawTab();
            }catch(err){
                tab = [...oldTab];
                lyrics = [...oldLyrics];
                drawTab();
                alert('Error loading JSON. JSON must be invalid. (format 1)\n' + err);
            }
        }
    }
}

function addStaff(currentStaff){
    var oldStaff = document.getElementsByClassName('activeStaff')[0];
    if(oldStaff){
        oldStaff.classList.remove('activeStaff');
    }
    getId("tab").innerHTML += "<button onclick='insertStaff(event)' data-staff='" + currentStaff + "'>Insert Staff</button><br>" +
    "<input id='lyric" + currentStaff + "' class='lyric' data-staff='" + currentStaff + "' placeholder='Lyrics / Notes'>" +
    "<div class='staff activeStaff'>" +
    "<div class='string'>e|-</div>" +
    "<div class='string'>B|-</div>" +
    "<div class='string'>G|-</div>" +
    "<div class='string'>D|-</div>" +
    "<div class='string'>A|-</div>" +
    "<div class='string'>E|-</div>" +
    "<div class='deletecol'><span class='del' onclick='deleteStaff(event)' data-staff='" + currentStaff + "'>X</span>&nbsp;" +
    "</div>";
}

function newStaff(){
    updateTab();
    tab.push([]);
    lyrics.push('');
    drawTab();
    getId("lyric" + (tab.length - 1)).focus();
}

function insertStaff(event){
    updateTab();
    var targetStaff = parseInt(event.target.getAttribute("data-staff"));
    tab.splice(targetStaff, 0, []);
    lyrics.splice(targetStaff, 0, '');
    drawTab();
    getId("lyric" + targetStaff).focus();
}

function insertColumn(event){
    updateTab();
    var targetStaff = parseInt(event.target.getAttribute("data-staff"));
    var targetColumn = parseInt(event.target.getAttribute("data-column"));
    tab[targetStaff].splice(targetColumn, 0, [n, n ,n, n, n, n]);
    drawTab();
    getId("stf" + targetStaff + "_col" + targetColumn + "_str0").focus();
    getId("stf" + targetStaff + "_col" + targetColumn + "_str0").select();
}

function deleteStaff(event){
    if(event.target.classList.contains('delconfirm')){
        updateTab();
        tab.splice(parseInt(event.target.getAttribute('data-staff')), 1);
        lyrics.splice(parseInt(event.target.getAttribute('data-staff')), 1);
        drawTab();
    }else{
        event.target.classList.add('delconfirm');
    }
}

function deleteColumn(event){
    if(event.target.classList.contains('delconfirm')){
        updateTab();
        tab[parseInt(event.target.getAttribute('data-staff'))].splice(parseInt(event.target.getAttribute('data-column')), 1);
        drawTab();
    }else{
        event.target.classList.add('delconfirm');
    }
}

function drawTab(){
    getId("tab").innerHTML = "";
    for(var staff in tab){
        addStaff(staff);
        var staffHTML = document.getElementsByClassName('activeStaff')[0];
        var notesStr = [
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        for(var column in tab[staff]){
            for(var i = 0; i < 6; i++){
                notesStr[5 - i] += ' <input id="stf' + staff + '_col' + column + '_str' + i + '" data-staff="' + staff + '" data-column="' + column + '" data-string="' + i + '" data-staff-end="false" value="' + tab[staff][column][i] + '">-';
                //staffHTML.childNodes[5 - i].innerHTML += ' <input id="stf' + staff + '_col' + column + '_str' + i + '" size="1" data-staff="' + staff + '" data-column="' + column + '" data-string="' + i + '" data-staff-end="false" value="' + tab[staff][column][i] + '">-';
            }
            notesStr[6] += '<span class="ins" onclick="insertColumn(event)" data-staff="' + staff + '" data-column="' + column + '">+</span>&nbsp;<span class="del" onclick="deleteColumn(event)" data-staff="' + staff + '" data-column="' + column + '">x</span>&nbsp;';
        }
        for(var i = 0; i < 6; i++){
            notesStr[5 - i] += ' <input id="stf' + staff + '_col' + tab[staff].length + '_str' + i + '" data-staff="' + staff + '" data-column="' + tab[staff].length + '" data-string="' + i + '" data-staff-end="true" value="-">-|';
            staffHTML.childNodes[5 - i].innerHTML += notesStr[5 - i];
        }
        staffHTML.childNodes[6].innerHTML += notesStr[6];
    }
    for(var staff in lyrics){
        getId('lyric' + staff).value = lyrics[staff];
    }
}

drawTab();

function tabClick(event){
    if(event.target.tagName === "INPUT" && !event.target.classList.contains('lyric')){
        /*
        var targetStaff = parseInt(event.target.getAttribute("data-staff"));
        var targetColumn = parseInt(event.target.getAttribute("data-column"));
        var targetString = parseInt(event.target.getAttribute("data-string"));
        updateTab();
        getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
        getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
        */
        event.target.select();
    }
}

function updateTab(extendStaff){
    var allInputs = getId("tab").getElementsByTagName('input');
    var staffsExtended = [];
    var columnsToBar = [];
    var columnsToUnbar = [];
    if(extendStaff){
        tab[extendStaff].push([n, n, n, n, n, n]);
        staffsExtended.push(extendStaff);
    }
    for(var input = 0; input < allInputs.length; input++){
        if(allInputs[input].classList.contains('lyric')){
            var currStaff = parseInt(allInputs[input].getAttribute('data-staff'));
            lyrics[currStaff] = allInputs[input].value;
        }else{
            var currStaff = parseInt(allInputs[input].getAttribute("data-staff"));
            var currColumn = parseInt(allInputs[input].getAttribute("data-column"));
            var currString = parseInt(allInputs[input].getAttribute("data-string"));
            if(allInputs[input].getAttribute("data-staff-end") === "true"){
                if(allInputs[input].value !== "-" && allInputs[input].value !== ""){
                    if(staffsExtended.indexOf(currStaff) === -1){
                            tab[currStaff].push([n, n, n, n, n, n]);
                            staffsExtended.push(currStaff);
                    }
                }else{
                    continue;
                }
            }
            if(allInputs[input].value === "|" && tab[currStaff][currColumn][currString] !== "|"){
                columnsToBar.push([currStaff, currColumn]);
                /*
                for(var i = 0; i < 6; i++){
                    if(tab[currStaff][currColumn][i] === "-"){
                        tab[currStaff][currColumn][i] = "|";
                    }
                }
                */
            }else if(tab[currStaff][currColumn][currString] === "|" && allInputs[input].value !== "|"){
                columnsToUnbar.push([currStaff, currColumn]);
                /*
                for(var i = 0; i < 6; i++){
                    if(tab[currStaff][currColumn][i] === "|"){
                        tab[currStaff][currColumn][i] = "-";
                    }
                }
                */
            }
            tab[currStaff][currColumn][currString] = allInputs[input].value || "-";
        }
    }
    for(var i in columnsToBar){
        for(var j in tab[columnsToBar[i][0]][columnsToBar[i][1]]){
            if(tab[columnsToBar[i][0]][columnsToBar[i][1]][j] === "-"){
                tab[columnsToBar[i][0]][columnsToBar[i][1]][j] = "|";
            }
        }
    }
    for(var i in columnsToUnbar){
        for(var j in tab[columnsToUnbar[i][0]][columnsToUnbar[i][1]]){
            if(tab[columnsToUnbar[i][0]][columnsToUnbar[i][1]][j] === "|"){
                tab[columnsToUnbar[i][0]][columnsToUnbar[i][1]][j] = "-";
            }
        }
    }
    drawTab();
}

function tabKeyPress(event){
    if(event.keyCode === 13){
        if(event.target.tagName === "INPUT"){
            if(event.target.classList.contains('lyric')){
                var targetStaff = parseInt(event.target.getAttribute("data-staff"));
                updateTab();
                getId("stf" + targetStaff + "_col0_str5").focus();
                getId("stf" + targetStaff + "_col0_str5").select();
            }else{
                var targetStaff = parseInt(event.target.getAttribute("data-staff"));
                var targetColumn = parseInt(event.target.getAttribute("data-column"));
                var targetString = parseInt(event.target.getAttribute("data-string"));
                updateTab();
                targetColumn++;
                if(targetColumn <= tab[targetStaff].length){
                    getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
                    getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
                }else{
                    targetColumn--;
                    getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
                    getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
                }
            }
        }
    }else if(event.keyCode === 38){
        if(event.target.tagName === "INPUT" && !event.target.classList.contains('lyric')){
            var targetStaff = parseInt(event.target.getAttribute("data-staff"));
            var targetColumn = parseInt(event.target.getAttribute("data-column"));
            var targetString = parseInt(event.target.getAttribute("data-string")) + 1;

            if(targetString !== 6){
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
            }else{
                getId("lyric" + targetStaff).focus();
            }
        }
    }else if(event.keyCode === 40){
        if(event.target.tagName === "INPUT"){
            if(event.target.classList.contains('lyric')){
                getId("stf" + event.target.getAttribute("data-staff") + "_col0_str5").focus();
            }else{
                var targetStaff = parseInt(event.target.getAttribute("data-staff"));
                var targetColumn = parseInt(event.target.getAttribute("data-column"));
                var targetString = parseInt(event.target.getAttribute("data-string")) - 1;

                if(targetString !== -1){
                    getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
                    getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
                }else{
                    event.target.select();
                }
            }
        }
    }else if(event.keyCode === 39){
        if(event.target.tagName === "INPUT" && !event.target.classList.contains('lyric')){
            var targetStaff = parseInt(event.target.getAttribute("data-staff"));
            var targetColumn = parseInt(event.target.getAttribute("data-column")) + 1;
            var targetString = parseInt(event.target.getAttribute("data-string"));

            if(targetColumn <= tab[targetStaff].length){
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
            }else{
                updateTab(targetStaff);
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
            }
        }
    }else if(event.keyCode === 37){
        if(event.target.tagName === "INPUT" && !event.target.classList.contains('lyric')){
            var targetStaff = parseInt(event.target.getAttribute("data-staff"));
            var targetColumn = parseInt(event.target.getAttribute("data-column")) - 1;
            var targetString = parseInt(event.target.getAttribute("data-string"));

            if(targetColumn !== -1){
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).focus();
                getId("stf" + targetStaff + "_col" + targetColumn + "_str" + targetString).select();
            }else{
                event.target.select();
            }
        }
    }
}

function toggleZoom(){
    getId("tab").classList.toggle("zoom");
}