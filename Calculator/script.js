window.onerror = function(err){
	alert(err);
}

if(window.location.href.indexOf('GooglePlay=true') > -1){
	document.head.innerHTML += '<style>.btn{line-height:250%;font-size:3vh}</style>';
}

var btns = document.getElementById("buttons");
var chars = document.getElementById("chars");
var screen = document.getElementById("screen");
var descDiv = document.getElementById("desc");

var currRight = 0;
var currBottom = 0;
var currId = 0;
var describe = [];
function btn(text,func,color, desc){
	btns.innerHTML += '<div id="b' + currId + '" style="right:' + currRight + '%;bottom:' + currBottom + '%;background-color:' + color + ';" onClick="' + func + ';descDiv.innerHTML = \'<b>\' + document.getElementById(\'b' + currId + '\').innerHTML + \'</b><br>\' + describe[' + currId + '];" class="btn"><span>' + text + '</span></div>';
	describe.push(desc);
	currRight += 10;
	if(currRight === 100){
		currRight = 0;
		currBottom += 10;
	}
	currId++;
}

function disableBtn(target){
	document.getElementById('b' + target).className = "disabled btn";
}
function enableBtn(target){
	document.getElementById('b' + target).className = "btn";
}

var Ans = 0;
var lastRun = "";
function run(){
	lastRun = screen.innerHTML;
	enableBtn(lastRunId);
	try{
		Ans = eval(screen.innerText);
		clearEverything();
		append(Ans);
		screen.scrollLeft = 0;
		enableBtn(ansId);
	}catch(err){
		clearEverything();
		append(String(err));
		screen.scrollLeft = 0;
	}
}
var runId = currId;
btn("&rarr;", "run()", "#0A0", "Evaluates the equation you have typed.");
disableBtn(runId);
function append(text){
	screen.innerHTML += text;
	chars.innerHTML = screen.innerHTML.length;
	screen.scrollLeft = screen.scrollWidth;
	enableBtn(runId);
	enableBtn(backspaceId);
	enableBtn(clearEverythingId);
	enableBtn(memSaveId);
}
btn("%", "append('%')", "#AA6F00", "x % y<br>Modulo: divide x by y, and return the remainder.");
btn("/", "append('/')", "#AA6F00", "Division.");
btn("*", "append('*')", "#AA6F00", "Multiplication.");
btn("-", "append('-')", "#AA6F00", "Subtraction.");
btn("+", "append('+')", "#AA6F00", "Addition.");
btn("=", "append('=')", "#0AA", "x = y<br>Assignment: sets variable x equal to y.<br><br>x == y <br>Comparison: test if x and y are equal.");
btn("]", "append(']')", "#0AA", "End an array or matrix definition.");
btn(",", "append(',')", "#0AA", "Separate two items in a list, array, or matrix.");
btn("[", "append('[')", "#0AA", "Begin an array or matrix definition.<br>Array: [1, 2, 3]<br>Matrix: [[1, 2, 3], [1, 2, 3]]");
var backspaceId = currId;
function backspace(){
	screen.innerHTML = screen.innerHTML.substring(0, screen.innerHTML.length - 1);
	chars.innerHTML = screen.innerHTML.length;
	if(screen.innerHTML.length === 0){
		disableBtn(backspaceId);
		disableBtn(clearEverythingId);
		disableBtn(memSaveId);
		disableBtn(runId);
	}
}
btn("&larr;", "backspace()", "#A00", "Backspace");
disableBtn(backspaceId);
var ansId = currId;
btn("Ans", "append('Ans')", "#4F00AA", "The answer to the previously evaluated equation.");
disableBtn(ansId);
var mem = "";
var memRecallId = currId;
function memRecall(){
	append(mem);
}
btn("MR", "memRecall()", "#4500AA", "Recall the saved piece of memory.");
disableBtn(memRecallId);
btn(".", "append('.')", "#0AA", "Decimal point. Full stop. Period.");
btn("0", "append('0')", "#005FCC", "Zero.");
btn(")", "append(')')", "#0AA", "End a parenthesis block or function call.");
btn("(", "append('(')", "#0AA", "Start a parenthesis block or function call.");
btn("x", "append('x')", "#4F00AA", "The variable x. Can be set using =.");
btn("y", "append('y')", "#4F00AA", "The variable y. Can be set using =.");
var manEnter = "";
function manual(){
	manEnter = prompt("Manually type an equation.");
	if(manEnter !== null){
		append(manEnter);
	}
}
btn("MAN","manual()","#0A0", "Manually enter an equation. Useful for if the calculator does not have a certain button or a button is not working.");
var clearEverythingId = currId;
var lastClear = "";
function clearEverything(){
	lastClear = screen.innerHTML;
	enableBtn(unclearId);
	screen.innerHTML = "";
	chars.innerHTML = 0;
	disableBtn(backspaceId);
	disableBtn(clearEverythingId);
	disableBtn(memSaveId);
	disableBtn(runId);
}
btn("CE", "clearEverything()", "#A00", "Clear Everything. The display is made blank and the current equation cleared.");
disableBtn(clearEverythingId);
var lastRunId = currId;
function previousRun(){
    clearEverything();
    append(lastRun);
}
btn("PRV","previousRun()","#4F00AA", "Recalls the last equation evaluated.");
disableBtn(lastRunId);
var memClearId = currId;
function memClear(){
	mem = "";
	disableBtn(memClearId);
	disableBtn(memRecallId);
}
btn("MC","memClear()","#4F00AA", "Clears equation saved to memory.");
disableBtn(memClearId)
btn("3","append('3')","#005FCC", "Three.");
btn("2","append('2')","#005FCC", "Two.");
btn("1","append('1')","#005FCC", "One.");
function tan(number){
	return Math.tan(number);
}
btn("tan", "append('tan(')", "#AA0", "tan(x)<br>Calculates the tangent of x.");
function cos(number){
	return Math.cos(number);
}
btn("cos", "append('cos(')", "#AA0", "cos(x)<br>Calculates the cosine of x.");
function sin(number){
	return Math.sin(number);
}
btn("sin", "append('sin(')", "#AA0", "sin(x)<br>Calculates the sine of x.");
function sqrt(number){
	return Math.sqrt(number);
}
btn("&radic;<span style='text-decoration:overline;'>&nbsp;</span>", "append('sqrt(')", "#AA0", "sqrt(x)<br>Calculate the square root of x.");
var unclearId = currId;
function unClear(){
	screen.innerHTML = "";
	backspace();
	append(lastClear);
}
btn("unC", "unClear()", "#4F00AA", "Undoes the Clear action.");
disableBtn(unclearId);
btn("", "", "", "");
var memSaveId = currId;
function memSave(){
	mem = screen.innerHTML;
	enableBtn(memClearId);
	enableBtn(memRecallId);
}
btn("MS", "memSave()", "#4F00AA", "Save the contents of the screen to memory.");
disableBtn(memSaveId);
btn("6", "append('6')", "#005FCC", "Six.");
btn("5", "append('5')", "#005FCC", "Five.");
btn("4", "append('4')", "#005FCC", "Four.");
function Qad(a, b, c){
	// -b +- sqrt(b^2 - 4ac) / 2a
	var d = sqrt(b * b - 4 * a * c);
	return [(-1 * b + d) / (2 * a), (-1 * b - d) / (2 * a)];
}
btn("Qad", "append('Qad(')", "#AA0", "Qad(a, b, c)<br>Performs the quadratic equation on ax&sup2; + bx + c.");
function log(x, b){
	if(b){
		return (Math.log(x) * Math.LOG10E) / (Math.log(b) * Math.LOG10E);
	}else{
		return (Math.log(x) * Math.LOG10E);
	}
};
btn("log", "append('log(')", "#AA0", "log(x, b)<br>Performs logarithm.<br>If b is provided, calculates log<sub>b</sup>x<br>Otherwise, performs log<sub>10</sub>x");
function ln(x){
	return Math.log(x);
}
btn("ln", "append('ln(')", "#AA0", "ln(x)<br>Performs ln on x");
function pow(a, b){
	return Math.pow(a, b);
}
btn("pow", "append('pow(')", "#AA0", "pow(x, y)<br>Raise x to the power of y.");
btn("", "", "", "");
btn("", "", "", "");
btn("", "", "", "");
btn("9", "append('9')", "#005FCC", "Nine.");
btn("8", "append('8')", "#005FCC", "Eight.");
btn("7", "append('7')", "#005FCC", "Seven.");
btn("", "", "", "");
btn("", "", "", "");
const e = Math.E;
btn("e", "append('e')", "#4F00AA", "Euler's constant.");
btn("", "", "", "");