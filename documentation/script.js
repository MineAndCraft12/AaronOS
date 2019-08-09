window.aosTools_connectListener = function(){
    aosTools.openWindow();
}
if(typeof aosTools === "object"){
    aosTools.testConnection();
}

var allDocuments = [];
var documentElements = document.getElementsByClassName("docPage");
for(var i = 0; i < documentElements.length; i++){
    allDocuments[documentElements[i].id] = {
        name: documentElements[i].getAttribute("data-doc-title"),
        contents: {},
        numOfContents: 0
    };
    var headerElements = documentElements[i].getElementsByClassName("docHeader");
    for(var j = 0; j < headerElements.length; j++){
        allDocuments[documentElements[i].id].contents[headerElements[j].id] = headerElements[j].innerText;
        allDocuments[documentElements[i].id].numOfContents++;
    }
}
var tempContentsText = "";
for(var i in allDocuments){
    tempContentsText += '<li data-target-doc="' + i + '">' + allDocuments[i].name + "</li>";
    if(allDocuments[i].numOfContents){
        tempContentsText += "<ul>";
        for(var j in allDocuments[i].contents){
            tempContentsText += '<li data-target-doc="' + i + '" data-target-header="' + j + '">' + allDocuments[i].contents[j] + '</li>';
        }
        tempContentsText += "</ul>";
    }
}
document.getElementById("mainList").innerHTML = tempContentsText;
tempContentsText = null;
var allItems = document.getElementById("mainList").getElementsByTagName("li");

function selectDocument(e){
    if(e.target.getAttribute("data-target-doc")){
        if(e.target.getAttribute("data-target-header")){
            presentDocument(e.target.getAttribute("data-target-doc"), e.target.getAttribute("data-target-header"));
        }else{
            presentDocument(e.target.getAttribute("data-target-doc"));
        }
        for(var i = 0; i < allItems.length; i++){
            allItems[i].classList.remove("selected");
        }
        e.target.classList.add("selected");
    }
}
function presentDocument(docID, headerID){
    for(var i = 0; i < documentElements.length; i++){
        documentElements[i].classList.remove("visible");
    }
    document.getElementById(docID).classList.add("visible");
    console.log("yee");
    if(headerID){
        requestAnimationFrame(() => {
            document.getElementById(docID).scrollTop = document.getElementById(headerID).offsetTop;
        });
    }else{
        document.getElementById(docID).scrollTop = 0;
    }
}

// select the welcome document
selectDocument({
    target: allItems[0]
});