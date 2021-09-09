window.aosTools_connectListener = function(){
    document.getElementById("devdoc_title").style.display = "none";
    document.getElementById("navigate").style.height = "calc(100% - 18px)";
    document.getElementById("content").style.height = "calc(100% - 18px)";
    var allTryButtons = document.getElementsByClassName("aosTools_try");
    for(var i = 0; i < allTryButtons.length; i++){
        allTryButtons[i].style.display = "inline";
    }
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
        searchTerms: documentElements[i].getAttribute("data-search-terms"),
        contents: {},
        numOfContents: 0
    };
    var headerElements = documentElements[i].getElementsByClassName("docHeader");
    for(var j = 0; j < headerElements.length; j++){
        allDocuments[documentElements[i].id].contents[headerElements[j].id] = [headerElements[j].innerText, headerElements[j].getAttribute("data-search-terms")];
        allDocuments[documentElements[i].id].numOfContents++;
    }
}
var tempContentsText = "";
for(var i in allDocuments){
    if(allDocuments[i].searchTerms){
        tempContentsText += '<li class="nav_doc_li cursorPointer" data-target-doc="' + i + '" data-search-terms="' + allDocuments[i].searchTerms + '">' + allDocuments[i].name + "</li>";
    }else{
        tempContentsText += '<li class="nav_doc_li cursorPointer" data-target-doc="' + i + '">' + allDocuments[i].name + "</li>";
    }
    if(allDocuments[i].numOfContents){
        tempContentsText += "<ul>";
        for(var j in allDocuments[i].contents){
            if(allDocuments[i].contents[j][1]){
                tempContentsText += '<li class="nav_header_li cursorPointer" data-target-doc="' + i + '" data-target-header="' + j + '" data-search-terms="' + allDocuments[i].contents[j][1] + '">' + allDocuments[i].contents[j][0] + '</li>';
            }else{
                tempContentsText += '<li class="nav_header_li cursorPointer" data-target-doc="' + i + '" data-target-header="' + j + '">' + allDocuments[i].contents[j][0] + '</li>';
            }
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
    if(headerID){
        requestAnimationFrame(() => {
            document.getElementById(docID).scrollTop = document.getElementById(headerID).offsetTop;
        });
    }else{
        document.getElementById(docID).scrollTop = 0;
    }
}

function search(keyword){
    if(typeof keyword === "string"){
        document.getElementById("searchInput").value = keyword;
    }else{
        keyword = document.getElementById("searchInput").value;
    }
    var allDocs = document.getElementsByClassName("nav_doc_li");
    var allHeaders = document.getElementsByClassName("nav_header_li");
    for(var i = 0; i < allDocs.length; i++){
        if(
            allDocs[i].innerText.toLowerCase().indexOf(keyword.toLowerCase()) > -1 ||
            allDocs[i].getAttribute("data-target-doc").toLowerCase().indexOf(keyword.toLowerCase()) > -1
        ){
            allDocs[i].style.display = "";
        }else{
            if(allDocs[i].getAttribute("data-search-terms")){
                var finalDisplay = "none";
                var allTerms = allDocs[i].getAttribute("data-search-terms").split(",");
                for(var j = 0; j < allTerms.length; j++){
                    if(allTerms[j].toLowerCase().indexOf(keyword.toLowerCase()) > -1){
                        finalDisplay = "";
                        break;
                    }
                }
                allDocs[i].style.display = finalDisplay;
            }else{
                allDocs[i].style.display = "none";
            }
        }
    }
    for(var i = 0; i < allHeaders.length; i++){
        if(
            allHeaders[i].innerText.toLowerCase().indexOf(keyword.toLowerCase()) > -1 ||
            allHeaders[i].getAttribute("data-target-doc").toLowerCase().indexOf(keyword.toLowerCase()) > -1 ||
            allHeaders[i].getAttribute("data-target-header").toLowerCase().indexOf(keyword.toLowerCase()) > -1
        ){
            allHeaders[i].style.display = "";
            allHeaders[i].parentNode.previousSibling.style.display = "";
        }else{
            if(allHeaders[i].getAttribute("data-search-terms")){
                var finalDisplay = "none";
                var allTerms = allHeaders[i].getAttribute("data-search-terms").split(",");
                for(var j = 0; j < allTerms.length; j++){
                    if(allTerms[j].toLowerCase().indexOf(keyword.toLowerCase()) > -1){
                        finalDisplay = "";
                        break;
                    }
                }
                allHeaders[i].style.display = finalDisplay;
                if(finalDisplay === ""){
                    allHeaders[i].parentNode.previousSibling.style.display = "";
                }
            }else{
                allHeaders[i].style.display = "none";
            }
        }
    }
    
}

// select the welcome document
selectDocument({
    target: allItems[0]
});