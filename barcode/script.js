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
        if(aosTools.connected){
            aosTools.alert({
                content: codeInfo.split('\n').join('<br>'),
                button: "Okay"
            }, () => {});
        }else{
            alert(codeInfo);
        }
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

var c128ForceB = 0;

var algorithms = {
    code128: {
        // we are only allowing numbers for now.
        // even str length means Code C unless Code B is forced by global flag.
        // odd str length or global flag means Code B

        // on error, return "error message"

        // returns [w, h, code, html]
        // w, h are width and height
        // code is the final code including control characters
        // html is human readable code, including <span class="control"> for control characters
        verify: function(text){
            if(text.length === 0){
                return "Value is blank.";
            }
            for(var i in text){
                if(this.validChars.numbers.indexOf(text[i]) === -1){
                    c128ForceB = 1;
                    break;
                }
            }
            for(var i in text){
                if(this.validChars.b.indexOf(text[i]) === -1){
                    return "Code-128 generator is in-progress. Currently supported characters:\n\n!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~ and (space)";
                }
            }

            var height = 28;

            if(text.length % 2 === 0 && !c128ForceB){
                var sum = 105;
                for(var i = 0; i < text.length; i += 2){
                    sum += (parseInt(text[i], 10) * 10 + parseInt(text[i + 1], 10)) * (i / 2 + 1);
                }
                var check = sum % 103;
                var width = (text.length / 2 + 3) * 11 + 22;
                var html = '<span class="control">[C</span>' + text + '<span class="control">' + check + ']</span>';
            }else{
                var sum = 104;
                for(var i in text){
                    sum += this.dictionary.b.indexOf(text[i]) * (i + 1);
                }
                var check = sum % 103;
                var width = (text.length + 3) * 11 + 22;
                var html = '<span class="control">[B</span>' +
                    text.split('&').join('&amp;').split('<').join('&lt;').split('>').join('&gt;').split(' ').join('&nbsp;') +
                    '<span class="control">' + check + ']</span>';
            }
            var code = [text, check];
            return [width, height, code, html];
        },
        validChars: {
            numbers: "0123456789",
            b: " !\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~"
        },
        drawChar: function(pos, value){
            var pattern = this.dictionary.bars[value];
            var y = 0;
            for(var j in pattern){
                var barWidth = parseInt(pattern[j], 10);
                if(j % 2 === 0){
                    ctx.fillRect(pos + y, 4, barWidth, 20);
                }
                y += barWidth;
            }
        },
        drawCode: function(code){
            var x = 10;
            if(code[0].length % 2 === 0 && !c128ForceB){
                this.drawChar(x, this.dictionary.control.startC);
                x += 11;
                for(var i = 0; i < code[0].length; i += 2){
                    this.drawChar(x, parseInt(code[0][i], 10) * 10 + parseInt(code[0][i + 1], 10));
                    x += 11;
                }
            }else{
                this.drawChar(x, this.dictionary.control.startB);
                x += 11;
                for(var i in code[0]){
                    this.drawChar(x, this.dictionary.b.indexOf(code[0][i]));
                    x += 11;
                }
            }
            this.drawChar(x, code[1]);
            x += 11;
            this.drawChar(x, this.dictionary.control.stop);
            c128ForceB = 0;
        },
        /*
            one character of the code is called a Symbol.
            One symbol is fixed-width of 11 pixels, except the Stop symbol.
            Each symbol consists of three bars and three spaces of varying widths.
            The total width of the three bars and three spaces always equals 11.
            Each bar or space is between 1 - 4 pixels wide.
            Sum width of bars must be even.
            Sum width of spaces must be odd.
            ex: "0" is 10011101100, where each place is a pixel, a 1 is a bar, and a 0 is a space.
            There are always six "runs", and their lengths are combined together into a Widths value.
            The Widths value always describes the lengths of a pattern of bars (B) and spaces (S).
                       BSBSBS
            ex: "0" is 123122, where each place is a run, and each run is the length of its bar or space in pixels.

            for our purposes, we are encoding Widths in the dictionary.

            CHECKSUM:
            The checksum digit is always included before the stopcode.
            Imagine a barcode body of 15243.

            CODE | VAL | POS | VAL * POS
            srtA   103    1    103
               1    17    1     17
               5    21    2     42
               2    18    3     54
               4    20    4     80
               3    19    5     95
               SUM OF PRODUCTS: 391
                     SUM % 103:  82

            Code C compresses the barcode length by turning adjacent digits directly into a value pair.
            Example: "123456" becomes only three values -- 12, 34, and 56.

            Code B instead has a character set, and the numbers lie from characters 16 through 25.

            FOR THE SAKE OF SIMPLICITY:
            I am only going to implement Code C and the numerals of Code B for now.
        */
        dictionary: {
            control: {
                // VALUE (STOP is not C128 accurate. this is placeholder.)
                startA: 103,
                startB: 104,
                startC: 105,
                stop: 106
            },
            // ASCII characters 00 to 95 (0–9, A–Z and control codes), special characters, and FNC 1–4
            a: {
            },
            // ASCII characters 32 to 127 (0–9, A–Z, a–z), special characters, and FNC 1–4
            b: " !\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~",
            // 128C (Code Set C) – 00–99 (encodes two digits with a single code point) and FNC1
            c: {

            },
            // these are the bar patterns for values.
            bars: [
                "212222", "222122", "222221", "121223", "121322", "131222", "122213", "122312", "132212", "221213", "221312", "231212", "112232", "122132",
                "122231", "113222", "123122", "123221", "223211", "221132", "221231", "213212", "223112", "312131", "311222", "321122", "321221", "312212",
                "322112", "322211", "212123", "212321", "232121", "111323", "131123", "131321", "112313", "132113", "132311", "211313", "231113", "231311",
                "112133", "112331", "132131", "113123", "113321", "133121", "313121", "211331", "231131", "213113", "213311", "213131", "311123", "311321",
                "331121", "312113", "312311", "332111", "314111", "221411", "431111", "111224", "111422", "121124", "121421", "141122", "141221", "112214",
                "112412", "122114", "122411", "142112", "142211", "241211", "221114", "413111", "241112", "134111", "111242", "121142", "121241", "114212",
                "124112", "124211", "411212", "421112", "421211", "212141", "214121", "412121", "111143", "111341", "131141", "114113", "114311", "411113",
                "411311", "113141", "114131", "311141", "411131",
                // START A
                "211412",
                // START B
                "211214",
                // START C
                "211232",
                // STOP
                "2331112"
            ]
        }
    },
    codabar: {
        // body must only contain [0-9]-$:/.+
        // if start with one of ABCD, must end with one of ABCD
        // if start with one of EN*T, must end with one of EN*T
        // if no ABCDEN*T control present, add it automatically

        // on error, return "error message"

        // returns [w, h, code, html]
        // w, h are width and height
        // code is the final code including control characters
        // html is human readable code, including <span class="control"> for control characters
        verify: function(text){
            text = text.toUpperCase();
            if(this.validChars.controlA.indexOf(text[0]) !== -1){
                if(this.validChars.controlA.indexOf(text[text.length - 1]) === -1){
                    return "Control Start is one of ABCD, Control End must be one of ABCD\n\nExample: A1234B";
                }
            }else if(this.validChars.controlE.indexOf(text[0]) !== -1){
                if(this.validChars.controlE.indexOf(text[text.length - 1]) === -1){
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
            Codabar begins with A and ends with B by default; this is how it's commonly used in libraries.
            Users can specify another type of control start or control end if they wish.
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

getId("codetext").value = "1234";
generate("code128");
getId("codetext").value = "";