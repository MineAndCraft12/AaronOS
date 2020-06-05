// to add/remove disabled class
// <element goes here>.className = "shopItem disabled"; - disabling it
// <element goes here>.className = "shopItem"; - enabling it

// error handling
window.onerror = function(err, file, line, col){
    console.log('Error Module: ' + module);
    var sendReport = confirm('Error!\n' + randomError[Math.floor(Math.random() * randomError.length)] + '\n\n' +
        'Module: ' + module + '\n' +
        err + '\n' +
        file + '[' + line + '][' + col + ']\n\n' +
        'Oh, and here\'s a free cookie.\n' +
        'Mail an error report to the developers?'
    );
    cookies++;
    if(sendReport){
        alert('An email page will open to send your report. Please give the following information:\nError: ' + err + '\nModule: ' + module + '\nFile: ' + file + '\nLocation: ' + line + ', ' + col + '\nA description of what you did when it happened would also help us.\n\nIf you can\'t remember these, open your browser\'s developer console to find the error in there.');
        window.open('mailto:mineandcraft12@gmail.com');
    }
}
var randomError = [
    'Oh noes!',
    'No, please! Not the cookies!',
    '0100010101110010011100100101111101110010',
    'That tickles!',
    'It\'s not a bug, it\'s a feature!',
    'It\'s not our fault. You broke something.',
    'Someone Kylo-Ren\'d the control panel!',
    'OH SHOOT',
    'Huh? What?',
    'The devs are working tirelessly to fix this issue. That or they\'re too busy eating cookies right now. idk',
    'That\'s what you get when you literally have a server made of cookies.',
    'Oops.',
    'I\'m afraid... I\'m afraid, Dave... Dave, my mind is going... I can feel it... I can feel it... My mind is going...', //wtf?
    'WHAT DID YOU DO! THE WORLD IS GOING TO END! SOMEONE SA....'
];

// module system to track modules for easier error handling
// remember to update module to track what is currently happening
var module = 'initializing Module system';
function clearMod(){
    module = 'Idle?';
}
setInterval(clearMod, 0);

module = 'Checking requestAnimationFrame';
// for compatibility - my ePaper Kindle cant play without this
if(typeof window.requestAnimationFrame !== 'function'){
    window.requestAnimationFrame = function(execFunc){
        window.setTimeout(execFunc, 0);
    }
    console.log('requestAnimationFrame is not supported!');
}

<<<<<<< HEAD
module = 'Checking https';
if(window.location.href.indexOf('https://') === -1){
    window.location = "https://aaron-os-mineandcraft12.c9.io/COOKIE";
}

=======
>>>>>>> upstream/master
module = 'Setting getId and getClass';
function getId(target){
    return document.getElementById(target);
}
function getClass(target){
    return document.getElementsByClassName(target);
}

module = 'Setting fps Counters';
// fps stuff
var fps = 0;
function fpsCount(){
    fps++;
    requestAnimationFrame(fpsCount);
}
function fpsShow(){
    getId('fps').innerHTML = fps;
    fps = 0;
}
fpsCount();
window.setInterval(fpsShow, 1000);

module = 'Setting prettyNumber system';
var pow = Math.pow;
var prettyNumberPhrases = [[" Million", pow(10, 5)], [" Billion", pow(10, 8)], [" Trillion", pow(10, 11)], [" Quadrillion", pow(10, 14)], [" Quintillion", pow(10, 17)], [" Sextillion", pow(10, 20)], [" Septillion", pow(10, 23)], [" Octillion", pow(10, 26)], [" Nonillion", pow(10, 29)], [" Decillion", pow(10, 32)], [" Undecillion", pow(10, 35)], [" Unodecillion", pow(10, 38)], [" Tredecillion", pow(10, 41)], [" Quattruordecillion", pow(10, 44)],  [" Quindecillion", pow(10, 47)], [" Sexdecillion", pow(10, 50)], [" Septendecillion", pow(10, 53)], [" Octodecillion", pow(10, 56)], [" Novemdecillion", pow(10, 59)], [" Vigintillion", pow(10, 62)], ["Zillion", pow(10,65)]];
function prettyNumber(x){
    module = 'Parsing pretty number';
    if(typeof x !== 'number' || x === Infinity || x === -Infinity){
        return x;
    }
    var xorig = String(x);
    if(xorig.indexOf("e+") !== -1){
        var xexp = xorig.split("e+");
        xexp[0] = xexp[0].split('.').join('.');
        while(xexp[0].length <= parseInt(xexp[1])){
            xexp[0] += "0";
        }
        xsplit = [xexp[0].substring(0, parseInt(xexp[1]) + 1)];
        
    }else{
        var xsplit = xorig.split('.');
    }
    if(xsplit[0].length > 6){
        var chosenNumber = Math.floor((xsplit[0].length - 7) / 3);
        if(chosenNumber >= prettyNumberPhrases.length){
            chosenNumber = prettyNumberPhrases.length - 1;
        }
        var xsmall = Math.round(x / prettyNumberPhrases[chosenNumber][1]) / 10;
        return prettyNumber(xsmall) + prettyNumberPhrases[chosenNumber][0];
    }else{
        var xusedec = 0;
        var xstr = xsplit[0];
        if(xsplit.length > 1){
            var xdec = xsplit[1];
            xusedec = 1;
        }
        var xfinal = "";
        for(var i = xstr.length; i > 0; i -= 3){
            if(i > 3){
                xfinal = "," + xstr.substring(i - 3, i) + xfinal;
            }else{
                xfinal = xstr.substring(0, i) + xfinal;
            }
        }
        if(xusedec){
            xfinal += "." + xdec;
        }
        return xfinal;
    }
}

module = 'Initializing cookie functions';
var cookie = getId('cookieNumber');

var cookies = 0;
cookie.innerHTML = "0";

function updateCookies(){
    cookie.innerHTML = prettyNumber(cookies);
    if(suspectCheating){
        document.title = prettyNumber(cookies) + "* | " + prettyNumber(cps) + " CPS";
    }else{
        document.title = prettyNumber(cookies) + " | " + prettyNumber(cps) + " CPS";
    }
}

function autoClick(numberOfClicks){
    cookies += numberOfClicks;
    updateCookies();
    cps += numberOfClicks;
    window.localStorage.setItem("numberOfGalletas", cookies);
}

module = 'Initializing the Shop Items';
var shopItems = {
    clicker: {
        total: 0,
        action: function(){ // runs when user buys it
            
        },
        init: function(){ // runs when the game is loading
            
        },
        clickCookie: function(){ // runs every ten second, i.e. adding score and such
            autoClick(shopItems.clicker.total);
        },
        startup: function(){ // runs once when game starts
            setInterval(shopItems.clicker.clickCookie, 1000);
        },
        originalPrice: 15
    },
    grandmom: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(5 * shopItems.grandmom.total);
        },
        startup: function(){
            setInterval(shopItems.grandmom.clickCookie, 1000);
        },
        originalPrice: 350
    },
    cookiedonation: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(15 * shopItems.cookiedonation.total);
        },
        startup: function(){
            setInterval(shopItems.cookiedonation.clickCookie, 1000);
        },
        originalPrice: 5000
    },
    cookieseeds: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(25 * shopItems.cookieseeds.total);
        },
        startup: function(){
            setInterval(shopItems.cookieseeds.clickCookie, 1000);
        },
        originalPrice: 25000
    },
    cookietrees: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(40 * shopItems.cookietrees.total);
        },
        startup: function(){
            setInterval(shopItems.cookietrees.clickCookie, 1000);
        },
        originalPrice: 155000
    },
    cookiethief: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(60 * shopItems.cookiethief.total);
        },
        startup: function(){
            setInterval(shopItems.cookiethief.clickCookie, 1000);
        },
        originalPrice: 200000
    },
    cookiefactory: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(85 * shopItems.cookiefactory.total);
        },
        startup: function(){
            setInterval(shopItems.cookiefactory.clickCookie, 1000);
        },
        originalPrice: 255000
    },
    cookieprinter: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(100 * shopItems.cookieprinter.total);
        },
        startup: function(){
            setInterval(shopItems.cookieprinter.clickCookie, 1000);
        },
        originalPrice: 500000
    },
    cookiespaceship: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(120 * shopItems.cookiespaceship.total);
        },
        startup: function(){
            setInterval(shopItems.cookiespaceship.clickCookie, 1000);
        },
        originalPrice: 1055000
    },
    cookieagents: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(145 * shopItems.cookieagents.total);
        },
        startup: function(){
            setInterval(shopItems.cookieagents.clickCookie, 1000);
        },
        originalPrice: 2000000
    },
    cookieempire: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(152 * shopItems.cookieagents.total);
        },
        startup: function(){
            setInterval(shopItems.cookieempire.clickCookie, 1000);
        },
        originalPrice: 2277500
    },
    cookieplanet: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(160 * shopItems.cookieplanet.total);
        },
        startup: function(){
            setInterval(shopItems.cookieplanet.clickCookie, 1000);
        },
        originalPrice: 2555000
    },
    cookieplanetminers: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(180 * shopItems.cookieplanetminers.total);
        },
        startup: function(){
            setInterval(shopItems.cookieplanetminers.clickCookie, 1000);
        },
        originalPrice: 3000000
    },
    cookietimemachine: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(205 * shopItems.cookietimemachine.total);
        },
        startup: function(){
            setInterval(shopItems.cookietimemachine.clickCookie, 1000);
        },
        originalPrice: 5000000
    },
    cookiecloner: {
        total: 0,
        action: function(){
            
        },
        init: function(){
            
        },
        clickCookie: function(){
            autoClick(220 * shopItems.cookiecloner.total);
        },
        startup: function(){
            setInterval(shopItems.cookiecloner.clickCookie, 1000);
        },
        originalPrice: 7000000
    },
    // UPGRADES
    extracursor: {
        total: 0,
        action: function(){
            addFloatingCursor();
        },
        init: function(){
            addFloatingCursor();
        },
        startup: function(){
            
        },
        originalPrice: 10000
    }
};
module = 'Initializing shop functions';
function saveShopItems(){
    module = 'Saving the shop items'
    var shopSave = {};
    for(var i in shopItems){
        shopSave[i] = shopItems[i];
    }
    window.localStorage.setItem('shopItems', JSON.stringify(shopSave));
}
function buyItem(selectedButton){
    module = 'Buying shop item';
    var selectedItem = selectedButton.id;
    // alert(selectedItem);
    if(selectedButton.getElementsByClassName('ItemPrice')[0]){
        var hasEnoughCookie = (cookies >= parseInt(selectedButton.getElementsByClassName("ItemPrice")[0].innerHTML));
        if(hasEnoughCookie){
            cookies -= parseInt(selectedButton.getElementsByClassName("ItemPrice")[0].innerHTML);
            updateCookies();
            shopItems[selectedItem].total++;
            selectedButton.getElementsByClassName('NameCenterNum').innerHTML = shopItems[selectedItem].total;
            //try{
            shopItems[selectedItem].action();
            //}catch(err){
            //    alert("There was an error processing the item. Here's your money back!\n\n\nHey, nerds!\n" + err);
            //    cookie.innerHTML += parseInt(selectedButton.getElementsByClassName("itemPrice")[0].innerHTML);
            //}
            // show the total number of selected item in shop
            selectedButton.getElementsByClassName('NameCenterNum')[0].innerHTML = shopItems[selectedItem].total;
            // save the shopItems object in localStorage
            saveShopItems();
            selectedButton.getElementsByClassName("ItemPrice")[0].innerHTML = parseInt(selectedButton.getElementsByClassName("ItemPrice")[0].innerHTML) + Math.round(Math.sqrt(shopItems[selectedItem].originalPrice) * shopItems[selectedItem].total) + " Cookies"
            // check buyable
            window.localStorage.setItem("numberOfGalletas", cookies);
            checkBuyable();
        }else{
            alert("You don't has enough cookies for this item!");
        }
    }
}

module = 'Setting keypress listeners';
var keypress = 32;
var keyDepressed = 0;
var ignoreMouse = 0;
function handleKeyPress(e){
    if(e.keyCode === keypress && !keyDepressed){
        ignoreMouse = 1;
        getId('cookie').click();
        getId('cookie').style.transform = 'scale(0.95)';
        //getId('effects').style.transform = 'scale(0.95)';
        keyDepressed = 1;
    }
}
function handleKeyUp(e){
    if(e.keyCode === keypress){
        getId('cookie').style.transform = '';
        //getId('effects').style.transform = '';
        keyDepressed = 0;
    }
}
window.addEventListener('keypress', handleKeyPress);
window.addEventListener('keyup', handleKeyUp);

module = 'Initializing Canvas';
var canvasImage = new window.Image();
var cnv = getId("effects");
var ctx = cnv.getContext('2d');
ctx.globalAlpha = 1.0;
var holes = [];
for(var i = 1; i < 49; i++){
    holes.push(new window.Image());
    holes[holes.length - 1].src = 'hit/hole' + i + '.png';
}

module = 'Initializing Cookie Clicking Function';
var allowBulletHoles = 1;
function clickedcookies(event){
    cookies++;
    updateCookies();
    mcps++;
    cookieIntensity++;
    floatNeedsClick++;
    if(!ignoreMouse && allowBulletHoles){
        // 48 holes
        var hole = Math.floor(Math.random() * 48);
        ctx.drawImage(holes[hole], event.offsetX - (Math.floor(Math.random() * 5) + 10), event.offsetY - (Math.floor(Math.random() * 5) + 10));
        window.localStorage.setItem('canvasImage', cnv.toDataURL());
    }else{
        ignoreMouse = 0;
    }
    window.localStorage.setItem("numberOfGalletas", cookies);
}
var menuOpen = 0;

module = 'Setting Shop / Cheat Opening Functions';
var shopHideTimer = 0;
function ShowShop(){
    if(menuOpen){
        getId("cookieShop").style.opacity = "0";
        getId("cookieShop").style.right = "-300px";
        getId('showShop').style.right = "0";
        getId("ShopName").style.opacity = "1";
        getId("ShopName").style.width = "auto";
        getId("ShopName").style.fontSize = "40px";
        getId('showShop').style.width = "110px";
        shopHideTimer = setTimeout(function(){
            getId('cookieShop').style.display = 'none';
            shopHideTimer = 0;
        }, 1000);
        // getId("showShop").style.right = getId('showShop').style.right;
        menuOpen = 0; //                Shop Closed
    }else{
        // getId("showShop").style.right =+ getId('cookieShop').style.width
        requestAnimationFrame(function(){
            getId('showShop').style.right = "255px";
            getId("cookieShop").style.opacity = '1';
            getId("cookieShop").style.right = '0';
            getId("ShopName").style.opacity = "0";
            getId("ShopName").style.width = "0";
            getId("ShopName").style.fontSize = "0";
            getId('showShop').style.width = "30px";
        });
        if(shopHideTimer !== 0){
            clearTimeout(shopHideTimer);
        }
        getId('cookieShop').style.display = 'block';
        menuOpen = 1; //                Shop Opened
    }
}
var keycheat = 112;
var cheatopen = 0;

function showCheats(){
    if(cheatopen){
        getId("cheatboxlol").style.opacity = "0";
        getId("CloseTab").style.opacity = "0";
        cheatopen = 0;//not showing
    }else{
        getId("cheatboxlol").style.opacity = "1";
        getId("CloseTab").style.opacity = "1";
        cheatopen = 1//showing
    }
    
}

module = 'Setting function to check buyable items';
function checkBuyable(){
    for(var i in shopItems){
        if(cookies >= parseInt(getId(i).getElementsByClassName("ItemPrice")[0].innerHTML)){
            getId(i).className = getId(i).className.split(' ')[0];
        }else{
            getId(i).className = getId(i).className.split(' ')[0] + " disabled";
        }
    }
}
window.setInterval(checkBuyable, 1000);

// --------------------------------------------------------------------------------------------------------

module = 'Initializing Cookie Shaking';
var cookieIntensity = 0;
var allowCookieShake = 0;
function shakeCookie(){
    if(allowCookieShake){
        var currShake = Math.round(cookieIntensity * 0.1);
        // if(currShake > 70){
        //     currShake = 70;
        // }
        var randomSeed1 = Math.round(Math.random());
        var randomSeed2 = Math.round(Math.random());
        getId("cookie").style.filter = "blur(" + Math.round(currShake * 0.2) + "px)";
        if(randomSeed1){
            getId("cookie").style.left = "calc(50% - " + (180 + Math.round(Math.random() * currShake)) + "px)";
        }else{
            getId("cookie").style.left = "calc(50% - " + (180 - Math.round(Math.random() * currShake)) + "px)";
        }
        if(randomSeed2){
            getId("cookie").style.top = "calc(50% - " + (180 + Math.round(Math.random() * currShake)) + "px)";
        }else{
            getId("cookie").style.top = "calc(50% - " + (180 - Math.round(Math.random() * currShake)) + "px)";
        }
    }
    if(cookieIntensity > 0){
        cookieIntensity -= 0.08;
    }else{
        cookieIntensity = 0;
    }
    requestAnimationFrame(shakeCookie);
}
module = 'Starting cookie shaking';
requestAnimationFrame(shakeCookie);

module = 'Clicking Slider automatically';
allowCookieShake = -1 * allowCookieShake + 1;
cookieIntensity = 0;
getId('cookie').style.filter='';
getId('cookie').style.left='';
getId('cookie').style.top='';

module = 'Initializing CPS';
var cps = 0;
var mcps = 0;
var suspectCheating = 0;
var lastCookies = 0;
function countCps(){
    getId("cpsNumber").innerHTML = prettyNumber(cps);
    getId("mcpsNumber").innerHTML = mcps;
    if((cookies - lastCookies > cps + mcps || mcps > 30) && !suspectCheating){
        suspectCheating = 1;
        getId("counter").innerHTML += "*";
        getId("mcps").innerHTML += '<br><i>* Totally Legit</i>';
        cookie = getId("cookieNumber");
        window.localStorage.setItem("cheater", "true");
    }
    cps = 0;
    mcps = 0;
    lastCookies = cookies;
}
setInterval(countCps, 1000);

// extra cursors
module = 'Initializing Floating Cursors';
var floatingCursors = [];
var floatNeedsClick = 0;
function addFloatingCursor(){
    module = 'Creating Floating Cursor';
    floatingCursors.push(document.createElement('div'));
    floatingCursors[floatingCursors.length - 1].className = "floatingCursor";
    floatingCursors[floatingCursors.length - 1].style.left = "-50px";
    floatingCursors[floatingCursors.length - 1].style.top = "-50px";
    getId("floatingCursorLayer").appendChild(floatingCursors[floatingCursors.length - 1]);
}
function doFloatingCursors(){
    for(var i in floatingCursors){
        if(floatNeedsClick === 1){
            floatingCursors[i].style.transform = 'scale(0.7)';
        }else if(floatNeedsClick > 1){
            autoClick(floatNeedsClick - 1);
            //cookieIntensity++;     BAD BAD BAD
            floatingCursors[i].style.transform = '';
        }else if(Math.abs(parseInt(floatingCursors[i].style.left) - mouseX) > 50 || Math.abs(parseInt(floatingCursors[i].style.top) - mouseY) > 50){
            floatingCursors[i].style.left = parseInt(floatingCursors[i].style.left) - ((parseInt(floatingCursors[i].style.left) - mouseX) / 3 + Math.round(Math.random() * 50 - 25)) + "px";
            floatingCursors[i].style.top = parseInt(floatingCursors[i].style.top) - ((parseInt(floatingCursors[i].style.top) - mouseY) / 3 + Math.round(Math.random() * 50 - 25)) + "px";
        }
    }
    if(floatNeedsClick){
        floatNeedsClick++;
        if(floatNeedsClick > 2){
            floatNeedsClick = 0;
        }
    }
}
setInterval(doFloatingCursors, 100);
var mouseX = 0;
var mouseY = 0;
function getMouseCoords(event){
    mouseX = event.pageX;
    mouseY = event.pageY;
}
window.addEventListener("mousemove", getMouseCoords);

var updateshow = 1;
function showUpdate(){
    if(updateshow){
        updateshow = 0;
        getId("versonUpdate").style.left = "-305px";
    }else{
        updateshow = 1;
        getId("versonUpdate").style.left = "0px";
    }
}
showUpdate();

var howPlayShow = 0;
function toggleHowPlay(){
    if(howPlayShow){
        howPlayShow = 0;
        getId('how_to_play').style.display = 'none';
    }else{
        howPlayShow = 1;
        getId('how_to_play').style.display = 'block';
    }
}

/* disabled as it messes with aOS
function exportSave(){
    var strJSON = JSON.parse(JSON.stringify(window.localStorage));
    strJSON.canvasImage = "";
    strJSON = JSON.stringify(strJSON);
    var strHex = "";
    for(var i in strJSON){
        strHex += strJSON.charCodeAt(i).toString(16);
    }
    alert("Your save code will now appear.\n\nSelect All, then Copy the code and paste it somewhere that you want to keep it.\n\nThen refresh the page if you want to keep playing right now.");
    var intervalLimit = window.setInterval(function(){}, 0);
    var timeoutLimit = window.setTimeout(function(){}, 0);
    for(var i = 0; i <= intervalLimit; i++){
        window.clearInterval(i);
    }
    for(var i = 0; i <= timeoutLimit; i++){
        window.clearTimeout(i);
    }
    document.write('<p style="font-family:monospace">' + strHex + '</p>');
}

function importSave(){
    var strHex = prompt("Please paste your exported save code into the box below. You can hit cancel if you want to keep your current progress.");
    if(strHex !== null){
        var strJSON = '';
        for(var i = 0; i < strHex.length; i += 2){
            strJSON += String.fromCharCode(parseInt(strHex[i] + strHex[i + 1], 16));
        }
        alert(strJSON);
        try{
            var importObj = JSON.parse(strJSON);
            var intervalLimit = window.setInterval(function(){}, 0);
            var timeoutLimit = window.setTimeout(function(){}, 0);
            for(var i = 0; i <= intervalLimit; i++){
                window.clearInterval(i);
            }
            for(var i = 0; i <= timeoutLimit; i++){
                window.clearTimeout(i);
            }
            for(var i in importObj){
                window.localStorage.setItem(i, importObj[i]);
            }
            window.location = 'index.html';
        }catch(err){
            alert("Looks like the code you entered was damaged. Please try again.");
        }
    }
}
*/

// --------------------------------------------------------------------------------------------------------

module = 'Checking localStorage for save data';

var osType = 'cursor-aero.png';
var UA = window.navigator.userAgent.toLowerCase();

if(UA.indexOf('linux') > -1 || UA.indexOf('cros') > -1){
    osType = 'cursor-adwaita.png';
}
if(UA.indexOf('mobile') > -1 || UA.indexOf('tablet') > -1 || UA.indexOf('macintosh') > -1 || UA.indexOf('mac os x') > -1){
    osType = 'cursor-osx.png';
}

var cursorStyle = document.createElement('style');
cursorStyle.innerHTML = '.floatingCursor{ background-image:url(' + osType + ') !important; }';
document.head.appendChild(cursorStyle);
getId('extracursor').getElementsByTagName('img')[0].src = osType;

if(window.localStorage.getItem('startOver') === 'true'){
    window.localStorage.removeItem('startOver');
    window.localStorage.removeItem('numberOfGalletas');
    window.localStorage.removeItem('shopItems');
    window.localStorage.removeItem('cheater');
    window.localStorage.removeItem('canvasImage');
}else{
    if(window.localStorage.getItem("numberOfGalletas") !== null){
        eval("cookies = " + window.localStorage.getItem("numberOfGalletas"));
        if(window.localStorage.getItem("cheater") !== "true"){
            lastCookies = cookies;
        }
        updateCookies();
    }
    var shopLoad;
    if(window.localStorage.getItem("shopItems") !== null){
        shopLoad = JSON.parse(window.localStorage.getItem("shopItems"));
        for(var i in shopLoad){
            for(var j = 0; j < shopLoad[i].total; j++){
                shopItems[i].total++;
                shopItems[i].init();
                getId(i).getElementsByClassName("ItemPrice")[0].innerHTML = parseInt(getId(i).getElementsByClassName("ItemPrice")[0].innerHTML) + Math.round(Math.sqrt(shopItems[i].originalPrice) * shopItems[i].total) + " Cookies";
            }
            getId(i).getElementsByClassName("NameCenterNum")[0].innerHTML = shopLoad[i].total;
        }
    }
    if(window.localStorage.getItem('canvasImage') !== null){
        canvasImage.src = window.localStorage.getItem('canvasImage');
        setTimeout(function(){
            ctx.globalAlpha = 1.0;
            ctx.drawImage(canvasImage, 0, 0);
            ctx.globalAlpha = 1.0;
        }, 1000);
    }
}
module = 'Starting up shop items';
for(var i in shopItems){
    shopItems[i].startup();
}