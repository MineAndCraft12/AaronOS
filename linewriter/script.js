function toggleCollapse(element){
    element.classList.toggle("collapsed");
    var collapseUI = element.getElementsByClassName("divTitleTri");
    if(collapseUI.length > 0){
        collapseUI[0].innerHTML = (element.classList.contains("collapsed")) ? '&rtrif;' : '&dtrif;';
    }
}

var lineData = {
    sectionFormat: {
        numberOfLines: 3,
        lineFormats: [
            {
                type: "plain",
                color: ""
            },
            {
                type: "plain",
                color: ''
            },
            {
                type: "chunks",
                chunks: [
                    {
                        color: "#7F7F7F"
                    },
                    {
                        color: "#7F7F7F"
                    }
                ]
            }
        ]
    },
    sections: [
        [
            {
                type: "plain",
                color: "",
                content: "Welcome to AaronOS LineWriter!"
            },
            {
                type: "plain",
                color: "",
                content: "Check out a usage example (to study language) in the next section."
            },
            {
                type: "chunks",
                colors: [
                    "#7F7F7F",
                    "#7F7F7F"
                ],
                chunks: [
                    ["You can change section formatting above.", "This tool isn't finished yet!"],
                    ["You can save and load projects below.", "It's a work in progress."]
                ]
            }
        ],
        [
            {
                type: "plain",
                color: "",
                content: "Jack and the Beanstalk"
            },
            {
                type: "plain",
                color: "",
                content: "ジャックと豆の木"
            },
            {
                type: "chunks",
                colors: [
                    "#7F7F7F",
                    "#7F7F7F"
                ],
                chunks: [
                    ["ジャック", "と", "まめのき"],
                    ["Jack", "and", "Beanstalk"]
                ]
            }
        ]
    ]
};

var lineTypes = {
    plain: {
        title: "Plain",
        editUI: function(lineNumber){
            return '<span>' +
                'Color: <input value="' + lineData.sectionFormat.lineFormats[lineNumber].color + '" placeholder="CSS Color"> ' +
                '<button onclick="lineTypes.plain.changeColor(' + lineNumber + ', this.parentNode.getElementsByTagName(\'input\')[0].value)">Set</button>' +
                '</span>';
        },
        projectUI: function(secNumber, lineNumber){
            return '<div class="seamless" style="color:' + lineData.sections[secNumber][lineNumber].color + '" ' +
                'contenteditable onblur="lineTypes.plain.setContent(' + secNumber + ', ' + lineNumber + ', this.innerHTML)">' +
            (lineData.sections[secNumber][lineNumber].content || '') + '</div>';
        },
        setContent: function(secNumber, lineNumber, content){
            lineData.sections[secNumber][lineNumber].content = content;
        },
        setupProject: function(lineNumber){
            return {
                type: 'plain',
                color: (lineData.sectionFormat.lineFormats[lineNumber].color || ""),
                content: 'Text'
            };
        },
        setupFormat: function(lineNumber){
            lineData.sectionFormat.lineFormats[lineNumber].type = 'plain';
            if(typeof lineData.sectionFormat.lineFormats[lineNumber].color !== "string"){
                lineData.sectionFormat.lineFormats[lineNumber].color = '';
            }
        },
        changeColor: function(lineNumber, value){
            lineData.sectionFormat.lineFormats[lineNumber].color = value;
        }
    },
    chunks: {
        title: "Chunks",
        editUI: function(lineNumber){
            return '<span>' +
                'Chunk 1 Color: <input value="' + lineData.sectionFormat.lineFormats[lineNumber].chunks[0].color + '" placeholder="CSS Color"> ' +
                '<button onclick="lineTypes.chunks.changeColor(' + lineNumber + ', 0, this.parentNode.getElementsByTagName(\'input\')[0].value)">Set</button><br>' +
                'Chunk 2 Color: <input value="' + lineData.sectionFormat.lineFormats[lineNumber].chunks[1].color + '" placeholder="CSS Color"> ' +
                '<button onclick="lineTypes.chunks.changeColor(' + lineNumber + ', 1, this.parentNode.getElementsByTagName(\'input\')[1].value)">Set</button>' +
                '</span>';
        },
        projectUI: function(secNumber, lineNumber){
            var tempHTML = '<div class="chunks">';
            for(var i = 0; i < lineData.sections[secNumber][lineNumber].chunks[0].length; i++){
                tempHTML += '<div class="chunkwrapper"><div class="seamlesschunkbutton">' +
                        '<span style="color:#0A0" onclick="lineTypes.chunks.addProjectUI(' +
                            secNumber + ', ' + lineNumber + ', ' + i + ');">&nbsp;+&nbsp;</span><br>' +
                        '<span style="color:#A00" onclick="lineTypes.chunks.removeProjectUI(' +
                            secNumber + ', ' + lineNumber + ', ' + i + ');">&nbsp;x&nbsp;</span>' +
                    '</div>';
                tempHTML += '<div class="seamlesschunk">' +
                    '<div class="seamless" style="color:' + lineData.sections[secNumber][lineNumber].colors[0] + '" ' +
                        'contenteditable onblur="lineTypes.chunks.setContent(' +
                            secNumber + ', ' + lineNumber + ', 0, ' + i + ', this.innerHTML)">' +
                        (lineData.sections[secNumber][lineNumber].chunks[0][i] || '') + '</div><br>' +
                    '<div class="seamless" style="color:' + lineData.sections[secNumber][lineNumber].colors[1] + '" ' +
                        'contenteditable onblur="lineTypes.chunks.setContent(' +
                            secNumber + ', ' + lineNumber + ', 1, ' + i + ', this.innerHTML)">' +
                        (lineData.sections[secNumber][lineNumber].chunks[1][i] || '') + '</div>' +
                    '</div></div>';
            }
            tempHTML += '<div class="seamlesschunkbutton">' +
                '<span style="color:#0A0" onclick="lineTypes.chunks.addProjectUI(' +
                    secNumber + ', ' + lineNumber + ', ' + (i + 1) + ');">&nbsp;+&nbsp;</span><br>' +
                '&nbsp;&nbsp;&nbsp;' +
                '</div>';
            tempHTML += '</div>';
            return tempHTML;
        },
        addProjectUI: function(secNumber, lineNumber, chunkNumber){
            lineData.sections[secNumber][lineNumber].chunks[0].splice(chunkNumber, 0, "Chunk");
            lineData.sections[secNumber][lineNumber].chunks[1].splice(chunkNumber, 0, "Chunk");
            loadProject();
        },
        removeProjectUI: function(secNumber, lineNumber, chunkNumber){
            if(confirm("Remove Chunk #" + (chunkNumber + 1) + " from Section " + (secNumber + 1) + ", Line " + (lineNumber + 1) + "?\n\nAre you sure?")){
                lineData.sections[secNumber][lineNumber].chunks[0].splice(chunkNumber, 1);
                lineData.sections[secNumber][lineNumber].chunks[1].splice(chunkNumber, 1);
                loadProject();
            }
        },
        setupProject: function(lineNumber){
            return {
                type: 'chunks',
                colors: [
                    (lineData.sectionFormat.lineFormats[lineNumber].chunks[0].color || ''),
                    (lineData.sectionFormat.lineFormats[lineNumber].chunks[1].color || '')
                ],
                chunks: [
                    ['Chunk', 'Chunk'],
                    ['Chunk', 'Chunk']
                ]
            }
        },
        setContent: function(secNumber, lineNumber, chunkLine, chunkNumber, content){
            lineData.sections[secNumber][lineNumber].chunks[chunkLine][chunkNumber] = content;
        },
        setupFormat: function(lineNumber){
            lineData.sectionFormat.lineFormats[lineNumber].type = 'chunks';
            if(typeof lineData.sectionFormat.lineFormats[lineNumber].chunks !== "object"){
                lineData.sectionFormat.lineFormats[lineNumber].chunks = [
                    {
                        color: ''
                    },
                    {
                        color: ''
                    }
                ]
            }
        },
        changeColor: function(lineNumber, chunkNumber, value){
            lineData.sectionFormat.lineFormats[lineNumber].chunks[chunkNumber].color = value;
        }
    }
}

function loadLine(secNumber, lineNumber){
    var tempHTML = '';
    if(lineTypes.hasOwnProperty(lineData.sections[secNumber][lineNumber].type)){
        tempHTML += lineTypes[lineData.sections[secNumber][lineNumber].type].projectUI(secNumber, lineNumber);
    }else{
        tempHTML += '<div class="warn" style="width:fit-content">' +
            'Warning: Line #' + (secNumber + 1) + '.' + (lineNumber + 1) + ' is of unknown type and cannot be displayed. (index ' + secNumber + '.' + lineNumber + ')</div>';
    }
    return tempHTML;
}

function loadSection(secNumber){
    var tempHTML = '';
    for(var j = 0; j < lineData.sections[secNumber].length; j++){
        tempHTML += '<div class="projFormatLine_' + j + '">';
        tempHTML += loadLine(secNumber, j);
        tempHTML += '</div>';
        // tempHTML += '<br>';
    }
    return tempHTML;
}

function addSection(secNumber){
    lineData.sections.splice(secNumber, 0, []);
    for(var i = 0; i < lineData.sectionFormat.numberOfLines; i++){
        if(lineTypes.hasOwnProperty(lineData.sectionFormat.lineFormats[i].type)){
            lineData.sections[secNumber].push(lineTypes[lineData.sectionFormat.lineFormats[i].type].setupProject(i));
        }else{
            lineData.sections[secNumber].push({type: 'plain', content: 'ERROR! I don\'t know how to make a "' + lineData.sectionFormat.lineFormats[i].type + '" type of line!'});
        }
    }
    loadProject();
}

function removeSection(secNumber){
    if(confirm("Remove Section # " + (secNumber + 1) + " from the project?\n\nAre you sure?")){
        lineData.sections.splice(secNumber, 1);
        loadProject();
    }
}

function loadProject(){
    try{
        var tempHTML = '';
        if(document.getElementById('projectDiv').classList.contains('showEditUI')){
            tempHTML += '<input type="checkbox" id="editUIbtn" onclick="toggleEditUI()" checked><label for="editUIbtn">Show edit UI</label>';
        }else{
            tempHTML += '<input type="checkbox" id="editUIbtn" onclick="toggleEditUI()"><label for="editUIbtn">Show edit UI</label>';
        }
        for(var i = 0; i < lineData.sections.length; i++){
            tempHTML += '<div class="section">' +
                '<div class="sectionBtnContainer">' +
                '<button class="sectionRemoveBtn" onclick="removeSection(' + i + ')">x</button>' +
                '<button class="sectionInsertBtn" onclick="addSection(' + i + ')">+</button>' +
                '</div>';
            tempHTML += loadSection(i);
            tempHTML += '</div>';
        }
        tempHTML += '<button onclick="addSection()">+ Add New Section</button>';
        document.getElementById('projectDiv').innerHTML = tempHTML;
    }catch(e){
        document.getElementById('jsonLoadError').innerHTML += '<br><div style="width:fit-content;">' +
            '<div class="warn">Warning: Error caught while preparing project data:</div>' +
            '<div class="error">' + e + '</div></div>';
        document.getElementById('jsonLoadError').scrollIntoView();
        console.warn("Warning: Error caught while loading project:");
        console.error(e);
    }
}

function editFormatLineType(lineNumber, type){
    lineTypes[type].setupFormat(lineNumber);
    loadFormatEditor();
}

function removeFormatLine(lineNumber){
    if(confirm("Remove Line #" + (lineNumber + 1) + " from formatting guide?\n\nAre you sure?")){
        lineData.sectionFormat.lineFormats.splice(lineNumber, 1);
        lineData.sectionFormat.numberOfLines--;
        loadFormatEditor();
    }
}

function hideFormatLine(lineNumber, elem){
    var elems = document.getElementsByClassName('projFormatLine_' + lineNumber);
    if(elem.checked){
        for(var i = 0; i < elems.length; i++){
            elems[i].classList.add('hidden');
        }
    }else{
        for(var i = 0; i < elems.length; i++){
            elems[i].classList.remove('hidden');
        }
    }
}

function addFormatLine(lineNumber){
    if(typeof lineNumber !== "number"){
        lineData.sectionFormat.numberOfLines++;
        lineData.sectionFormat.lineFormats.push({
            type: "plain",
            color: ""
        });
        lineNumber = lineData.sectionFormat.numberOfLines;
        loadFormatEditor();
    }else{
        var tempHTML = '';
        tempHTML += 'Line ' + (lineNumber + 1) + ': ' +
            '<button onclick="removeFormatLine(' + lineNumber + ')">Remove</button><br>' +
            '<input type="checkbox" id="formatLine_' + lineNumber + '" onclick="hideFormatLine(' + lineNumber + ', this)"><label for="formatLine_' + lineNumber + '">Hide this line for now</label>' +
            '<div class="formatEditorLine" id="lineFormat_' + lineNumber + '" data-line-number="' + lineNumber + '">' +
            'Line Type: <select onchange="editFormatLineType(this.parentNode.getAttribute(\'data-line-number\'), this.value)">';
        for(var j in lineTypes){
            tempHTML += '<option value="' + j + '"' + ((j === lineData.sectionFormat.lineFormats[lineNumber].type) ? ' selected' : '') + '>' + lineTypes[j].title + '</option>';
        }
        tempHTML += '</select><br>';
        if(lineTypes.hasOwnProperty(lineData.sectionFormat.lineFormats[lineNumber].type)){
            tempHTML += lineTypes[lineData.sectionFormat.lineFormats[lineNumber].type].editUI(lineNumber);
        }else{
            tempHTML += 'Line is of unknown type "' + lineData.sectionFormat.lineFormats[lineNumber].type + '"';
        }
        tempHTML += '</div>';
        return tempHTML;
    }
}

function loadFormatEditor(){
    var tempHTML = '<i>Only newly-created Sections are affected.<br>The Sections you already created will be unchanged.</i><br><br>';
    for(var i = 0; i < lineData.sectionFormat.numberOfLines; i++){
        try{
            tempHTML += addFormatLine(i);
        }catch(e){
            tempHTML += '<div style="width:fit-content;">' +
            '<div class="warn">Warning: Error caught while loading Format Editor for Line #' + (i + 1) + ' (index ' + i + '):</div>' +
            '<div class="error">' + e + '</div></div>';
            console.warn("Warning: Error caught while loading Format Editor for Line #" + (i + 1) + " (index " + i + '):');
            console.error(e);
        }
        tempHTML += '<br>';
    }
    tempHTML += '<button onclick="addFormatLine()">Add Line</button>';
    document.getElementById('lineFormatEditor').innerHTML = tempHTML;
}

loadFormatEditor();

function toggleEditUI(){
    if(document.getElementById('editUIbtn').checked){
        document.getElementById('projectDiv').classList.add('showEditUI');
    }else{
        document.getElementById('projectDiv').classList.remove('showEditUI');
    }
}

function saveToJSON(saveType){
    switch(saveType){
        case 'localStorage':
            if(confirm("Saving to Webpage Storage.\nAny existing Project in storage will be overwritten.\n\nAre you sure?")){
                localStorage.setItem('aosLineWriter_Project', JSON.stringify(lineData));
                document.getElementById("wpstorage").innerHTML = "There is a Project in Webpage Storage!";
            }
            break;
        case 'file':
            var data = JSON.stringify(lineData);
            var file = new Blob([data], {type: 'application/json'});
            var a = document.createElement('a');
            a.href = URL.createObjectURL(file);
            var date = new Date();
            a.download = 'aOS_LWProject_' +
                date.getFullYear() +
                ((date.getMonth() < 9) ? '0' : '') + (date.getMonth() + 1) +
                ((date.getDate() < 10) ? '0' : '') + date.getDate() + '_' +
                ((date.getHours() < 10) ? '0' : '') + date.getHours() +
                ((date.getMinutes() < 10) ? '0' : '') + date.getMinutes() +
                ((date.getSeconds() < 10) ? '0' : '') + date.getSeconds() +
                '.json';
            a.innerHTML = '<button>Download ' + a.download + '</button>';
            document.getElementById('fileDownloadSpan').innerHTML = '';
            document.getElementById('fileDownloadSpan').appendChild(a);
            break;
        default:
            document.getElementById('convertfield').value = JSON.stringify(lineData);
    }
}

var tempFileData = '';

function loadFromJSON(loadType){
    if((loadType !== 'fileFinalize') ? confirm("Loading a new Project.\nYou will lose unsaved changes.\n\nAre you sure?") : true){
        document.getElementById('jsonLoadError').innerHTML = '';
        try{
            var jsonData = {};
            switch(loadType){
                case 'localStorage':
                    jsonData = JSON.parse(localStorage.getItem('aosLineWriter_Project'));
                    break;
                case 'file':
                    var file = document.getElementById('fileUploadInput').files[0];
                    if(file){
                        var reader = new FileReader();
                        reader.readAsText(file, "UTF-8");
                        reader.onload = function (evt) {
                            tempFileData = evt.target.result;
                            loadFromJSON('fileFinalize');
                        }
                        reader.onerror = function (evt) {
                            document.getElementById('jsonLoadError').innerHTML = '<br>' +
                                '<div class="error" style="width:fit-content;">Error: Failed to read file data.</div>';
                            document.getElementById('jsonLoadError').scrollIntoView();
                            console.error("Error: Failed to read file data:");
                            console.error(evt);
                        }
                    }
                    break;
                case 'fileFinalize':
                    jsonData = JSON.parse(tempFileData);
                    tempFileData = '';
                    break;
                default:
                    jsonData = JSON.parse(document.getElementById('convertfield').value);
            }
            if(loadType === 'file'){
                return;
            }
            if(jsonData.hasOwnProperty('sectionFormat') && jsonData.hasOwnProperty('sections')){
                var tempLineData = {};
                try{
                    tempLineData = JSON.stringify(lineData);
                }catch(e){
                    document.getElementById('jsonLoadError').innerHTML += '<br><div style="width:fit-content;">' +
                        '<div class="warn">Warning: Could not back-up current project before loading new project:</div>' +
                        '<div class="error">' + e + '</div></div>';
                    document.getElementById('jsonLoadError').scrollIntoView();
                    console.warn("Warning: Could not back-up current project before loading new project:");
                    console.error(e);
                }
                try{
                    lineData = jsonData;
                    loadProject();
                }catch(e){
                    if(tempLineData != {}){
                        lineData = JSON.parse(tempLineData);
                    }

                    document.getElementById('jsonLoadError').innerHTML += '<br><div style="width:fit-content;">' +
                        '<div class="warn">Warning: Error caught while preparing project data:</div>' +
                        '<div class="error">' + e + '</div></div>';
                    document.getElementById('jsonLoadError').scrollIntoView();
                    console.warn("Warning: Error caught while loading JSON Data:");
                    console.error(e);
                }
                document.getElementById('jsonLoadError').innerHTML = '<br>' +
                    '<div class="info" style="width:fit-content;">Info: Project loaded successfully.</div>';
                if(document.getElementById('projectHeader').classList.contains('collapsed')){
                    toggleCollapse(document.getElementById('projectHeader'));
                }
                document.getElementById('projectTitle').scrollIntoView();
            }else{
                document.getElementById('jsonLoadError').innerHTML = '<br>' +
                    '<div class="warn" style="width:fit-content;">Warning: JSON Data does not seem to contain project data.</div>';
                document.getElementById('jsonLoadError').scrollIntoView();
            }
        }catch(e){
            document.getElementById('jsonLoadError').innerHTML = '<br><div style="width:fit-content;">' +
                '<div class="warn">Warning: Error caught while reading JSON Data:</div>' +
                '<div class="error">' + e + '</div></div>';
            document.getElementById('jsonLoadError').scrollIntoView();
            console.warn("Warning: Error caught while loading JSON Data:");
            console.error(e);
        }
    }
}

loadProject();

if(typeof localStorage.getItem('aosLineWriter_Project') === "string"){
    document.getElementById("wpstorage").innerHTML = "There is a Project in Webpage Storage!";
}