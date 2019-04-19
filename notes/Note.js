class Note {
    constructor(name, content, location, creationDate, author, lastEdited) {
        this.name = name;
        this.content = content;
        this.location = location || ""; // Gets saved to local storage, is this even necessary?
        this.creationDate = creationDate;
        this.author = author;
        this.lastEdited = lastEdited || creationDate;                
    }

    // Setters for Name, Content, Location, and Last Edited
    setName(newName) { this.name = newName; }
    setContent(newContent) { this.content = newContent; }
    setLocation(newLocation) { this.location = newLocation; }
    setLastEdited(newLastEdited) { this.lastEdited = newLastEdited; }

    // Getters for Name, Content, Location, Creation, Last Edited, and Author
    getName() { return this.name; }
    getContent() { return this.content; }
    getLocation() { return this.location; }
    getCreationDate() { return this.creationDate; }
    getLastEdited() { return this.lastEdited; }
    getAuthor() { return this.author; }

    save() {
        // Save note to memory from document

    }

    load() {
        // Load the note from memory to document
        
    }

    getJsonArray() {
        // Returns the current note's data as a JSON Array  
        return JSON.stringify(this.getAsObject());// i feel like getAsObject is missing something? in the way i referenced it
    }

    getAsObject() {
        // Returns the current note's data as an object
        // Should be `return this` then? Getting a JSON Array and getting an Object are two very different things in this case O_o
        // `this` would also include all its methods, etc (i think) (actually if that way's right then we could just use `note` instead of `note.getAsObject()`)ahhh i see muy derp
        // Depends on how JS treats objects and fields. Java would return only the object and the object has its own properties. the methods aren't tied to that specific object, but the class in general.
        // Example: A class of Banana has a `length` and a `colour` that are stored with each instance of Banana. but you can Banana.peel() any existing Banana
        return {// imma do an test
            name: this.getName(),
            content: this.getContent(),
            location: this.getLocation(),
            creationDate: this.getCreationDate(),
            lastEdited: this.getLastEdited(),
            author: this.getAuthor()
        }
    }
}