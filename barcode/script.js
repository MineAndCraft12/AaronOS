function getId(target){
    return document.getElementById(target);
}
window.aosTools_connectListener = function(){
    aosTools.disablePadding();
    aosTools.openWindow();
}
window.aosTools_connectFailListener = function(){
    console.log("Not loaded in AaronOS. We're standalone.");
}
if(typeof aosTools === "object"){
    aosTools.testConnection();
}

var canvas = getId("barcode");
var ctx = canvas.getContext("2d");

var size = [0, 0];
function generate(type){
    var codeInfo = algorithms[type].verify(getId("codetext").value);

    if(typeof codeInfo === "string"){
        alert(codeInfo);
    }else{
        getId("finalcodetext").innerHTML = codeInfo[3];

        size = [codeInfo[0], codeInfo[1]];
        canvas.width = size[0];
        canvas.height = size[1];
        canvas.style.width = size[0] * 2 + "px";
        canvas.style.height = size[1] * 2 + "px";
        ctx.fillStyle = "#FFF";
        ctx.fillRect(0, 0, size[0], size[1]);
        ctx.fillStyle = "#000";

        algorithms[type].drawCode(codeInfo[2]);
    }
}

var algorithms = {
    codabar: {
        // body must only contain [0-9]-$:/.+
        // if start with one of ABCD, must end with one of ABCD and length 18
        // if start with one of EN*T, must end with one of EN*T and length 18
        // ABCDEN*T must not appear in the body

        // on error, return "error message"

        // returns [w, h, code, html]
        // w, h are width and height
        // code is the final code including control characters
        // html is human readable code, including <span class="control"> for control characters
        verify: function(text){
            console.log(this);
            text = text.toUpperCase();
            if(this.validChars.controlA.indexOf(text[0]) !== -1){
                if(this.validChars.controlA.indexOf(text[17]) === -1){
                    return "Control Start is one of ABCD, Control End must be one of ABCD\n\nExample: A1234B";
                }
            }else if(this.validChars.controlE.indexOf(text[0]) !== -1){
                if(this.validChars.controlE.indexOf(text[17]) === -1){
                    return "Control Start is one of EN*T, Control End must be one of EN*T\n\nExample: E1234N";
                }
            }else{
                text = "A" + text + "B";
            }
            for(var i = 1; i < text.length - 1; i++){
                if(this.validChars.body.indexOf(text[i]) === -1){
                    return "Valid body characters are [0-9] and -$:/.+\n\nExample: 1234";
                }
            }

            var html = '<span class="control">' + text[0] + '</span>' + text.substring(1, text.length - 1) + '<span class="control">' + text[text.length - 1] + '</span>';
            var height = 28;
            var width = 8;
            for(var i = 0; i < text.length; i++){
                width += this.dictionary[text[i]][1] + 1;
            }
            return [width, height, text, html];
        },
        validChars: {
            body: "0123456789-$:/.+",
            controlA: "ABCD",
            controlE: "EN*T"
        },
        drawCode: function(code){
            var x = 4;
            for(var i = 0; i < code.length; i++){
                var char = code[i];
                for(var j = 0; j < 7; j++){
                    if(this.dictionary[char][0][j] === "0"){
                        if(j % 2 === 0){
                            ctx.fillRect(x, 4, 1, 20);
                        }
                        x++;
                    }else{
                        if(j % 2 === 0){
                            ctx.fillRect(x, 4, 3, 20);
                        }
                        x += 3;
                    }
                }
                x++;
            }
        },
        /* 
            Codabar characters are seven symbols; four bars (B) with three spaces (S) between them.
            One "bit" is either zero or one, denoting a standard-length bar/space or a triple-length bar/space.
            One character is encoded as BSBSBSB, and characters are separated by a standard-length space.
            Codabar begins with A and ends with B by default, for simplicity.
            Users can specify another beginning or end if present.
        */
        dictionary: {
            //     BSBSBSB   px
            "0": ["0000011", 11],
            "1": ["0000110", 11],
            "2": ["0001001", 11],
            "3": ["1100000", 11],
            "4": ["0010010", 11],
            "5": ["1000010", 11],
            "6": ["0100001", 11],
            "7": ["0100100", 11],
            "8": ["0110000", 11],
            "9": ["1001000", 11],
            "–": ["0001100", 11],
            "$": ["0011000", 11],
            ":": ["1000101", 13],
            "/": ["1010001", 13],
            ".": ["1010100", 13],
            "+": ["0010101", 13],
            "A": ["0011010", 13], // default start
            "E": ["0011010", 13],
            "B": ["0101001", 13], // default end
            "N": ["0101001", 13],
            "C": ["0001011", 13],
            "*": ["0001011", 13],
            "D": ["0101110", 15],
            "T": ["0101110", 15]
        },
    }
};

getId("codetext").value = "12345";
generate("codabar");
getId("codetext").value = "";