function getId(target) {
    return document.getElementById(target);
}

currentNote = 0;
function getNote(noteID) {
    getId("noteEditor").innerHTML = notes[noteID || currentNote].getContent();
}
function setNote(noteID) {
    note[noteID || currentNote].setContent(getId("noteEditor").innerHTML);
    writeNotes();
}

// notes as Note class for manipulation
var notes = [
    new Note("testNote", "Lorem Ipsum", "local", new Date(), "Crablet")
];
// notes formatted as json for saving
var notesJson = [
    {
        name: notes[0].name, // this is just an example for reference purpose O_o ACTUALLY | why take the time to store all the note data, why not just store only the note object? localStorage only accepts strings :P have to convert an object / array to a json string for localStorage
        content: notes[0].content,
        location: notes[0].location,
        creationDate: notes[0].creationDate,
        author: "Crablet", // TODO: get current user 
        lastEdited: notes[0].lastEdited
    }
];

function getNotes(storageLocation) {
    // Loads the list of notes from the server/local storage into memory
    if(localStorage.getItem("notes") === null){
        return;
    }
    
    if(storageLocation === "server") {
        // load from server
        // TODO: Implement a REST API                                            

    } else {
        // load from local

        // loads the json data of notes from storage, turns into object
        notesJson = JSON.parse(localStorage.getItem("notes"));

        // clean the notes array
        notes = [];

        // for each note in the json object
        for(var note in notesJson) {
            // use Note constructor and add it to the end of the array
            notes.push(new Note(
                notesJson[note].name,   // Why not the getters? omfg js
                notesJson[note].content,// this is the json array we're pulling from, they just normal objects :P tbh it's just my retarded saving system
                notesJson[note].location,
                notesJson[note].creationDate,
                notesJson[note].author, // i switched author and lastEdited around in the Note class parameters, as lastEdited is optional
                notesJson[note].lastEdited,
            ));
        }
    }
}

function writeNotes(storageLocation) {
    // saves the notes from memory to storage

    if(storageLocation === "server") {
        // save to server
        // TODO: Implement a REST API                                            

    } else {
        // save to local

        // clean notes array
        notesJson = [];
        
        // for each Note object
        for(var note in notes) {
            // add it as a normal object to json array
            notesJson.push(notes[note].getAsObject());
        }
        
        // turn json array into string and save it
        localStorage.setItem("notes", JSON.stringify(notesJson));
    }
}

var notesDiv = getId('noteSelection');

function displayNotes() {
    // build list of notes in html

    notesDiv.innerHTML = "";

    for(var note in notes){
        var elem = document.createElement('div');
        elem.innerHTML = notes[note].getName() + ' by ' + notes[note].getAuthor();
        elem.setAttribute('onclick', `currentNote = ${note};getNote();`);
        elem.style.cursor = "pointer";
        notesDiv.appendChild(elem);
    }
}

getNotes();
displayNotes();