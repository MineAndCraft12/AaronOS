<!DOCTYPE html>
<html>
    <head>
        <title>Binary Image Converter</title>
    </head>
    <body>
        Use this tool to convert text into either binary text or a binary image.
        <br><br>
        <button onclick="apps.textBinary.vars.textToBW(0)">BW Image (large)</button>&nbsp;
        <button onclick="apps.textBinary.vars.textToGS(0)">GS Image (medium)</button>&nbsp;
        <button onclick="apps.textBinary.vars.textToRGB(0)">RGB Image (small)</button>
        <br>
        <button onclick="apps.textBinary.vars.textToBW(1)">BW Image (invert)</button>&nbsp;
        <button onclick="apps.textBinary.vars.textToGS(255)">GS Image (invert)</button>&nbsp;
        <button onclick="apps.textBinary.vars.textToRGB(255)">RGB Image (invert)</button>
        <br>
        <button onclick="apps.textBinary.vars.textToBin(1)">Plain Binary</button>&nbsp;
        <button onclick="apps.textBinary.vars.textToBin(0)">Plain Binary (no spaces)</button>
        <br>
        <textarea id="textToBinInput" placeholder="Type or paste text here"></textarea>&nbsp;
        <div style="position:relative;display:inline-block">
            <label><input type="checkbox" id="textToBinAlign">Align images to binary</label>&nbsp;
            <label><input type="checkbox" id="textToBinDecode">Decode PNG</label>&nbsp;
            <input type="file" id="textToBinDecodeImg" accept="image/x-png">
            <br>
            <label><input type="checkbox" id="textToBinLineAlign">Align images to newlines</label>
        </div>
        <hr>
        <div id="textToBinOutput"></div>
    </body>
    <script defer>
        function getId(target){
            return document.getElementById(target);
        }
        var apps = {
            textBinary: {
                vars: {
                    appInfo: 'Convert text into binary or an image.',
                    textToBin: function(useSpaces){
                        var binFile = getId('textToBinInput').value;
                        var binFinal = '';
                        for(var byte in binFile){
                            var binStr = binFile.charCodeAt(byte).toString(2);
                            while(binStr.length < 8){
                                binStr = '0' + binStr;
                            }
                            if(useSpaces){
                                binFinal += binStr + ' ';
                            }else{
                                binFinal += binStr;
                            }
                        }
                        getId('textToBinOutput').innerHTML = '<textarea style="width:750px;height:300px;" display="block">' + binFinal + '</textarea>';
                    },
                    textToBW: function(invert){
                        if(getId('textToBinDecode').checked && getId('textToBinDecodeImg').files.length !== 0){
                            this.bwToText(invert);
                        }else{
                            var alignBin = getId('textToBinAlign').checked;
                            var alignLines = getId('textToBinLineAlign').checked;
                            var binFile = getId('textToBinInput').value;
                            var binFinal = [];
                            var binLength = 0;
                            for(var byte in binFile){
                                var binStr = binFile.charCodeAt(byte);
                                binFinal.push(binStr);
                                binLength++;
                            } // using decimals, not binary!
                            getId('textToBinOutput').innerHTML = '(<span id="textToBinImgSize"></span>) Right Click to Copy or Save Image<br><canvas id="textToBinCanvas" oncontextmenu="event.stopPropagation();return true;"></canvas>';
                            var bincnv = getId('textToBinCanvas');
                            var binctx = bincnv.getContext('2d');
                            if(alignLines){
                                var imageSize = [0,0];
                                var lastNewline = 0;
                                for(var i = 0; i < binFinal.length; i++){
                                    if(binFinal[i] === 10){
                                        imageSize[1]++;
                                        if(i - lastNewline > imageSize[0]){
                                            imageSize[0] = i - lastNewline;
                                        }
                                        lastNewline = i;
                                    }
                                }
                                imageSize[0]++;
                                imageSize[1]++;
                                
                                imageSize[0] *= 8;
                            }else{
                                var imageSize = Math.floor(Math.sqrt(binLength * 8) + 1);
                                imageSize = [imageSize,imageSize];
                                if(alignBin && imageSize[0] % 8 !== 0){
                                    imageSize[1] += imageSize[0] % 8;
                                    imageSize[0] -= imageSize[0] % 8;
                                }
                            }
                            getId('textToBinImgSize').innerHTML = imageSize[0] + 'x' + imageSize[1];
                            bincnv.width = imageSize[0];
                            bincnv.height = imageSize[1];
                            bincnv.style.width = imageSize[0] + "px";
                            bincnv.style.height = imageSize[1] + "px";
                            // for each pixel (increment through bytes of string by 3)
                            // make pixel on image equal to the 3 current byte items as rgb
                            var imgRow = 0;
                            var imgColumn = 0;
                            var dontGoDown = 0;
                            for(var byte = 0; byte < binLength; byte++){
                                var currByte = (binFinal[byte] || 0).toString(2);
                                while(currByte.length < 8){
                                    currByte = '0' + currByte;
                                }
                                var brightness = 0;
                                for(var i = 0; i < 8; i++){
                                    if(invert){
                                        brightness = (1 - parseInt(currByte[i])) * 255;
                                    }else{
                                        brightness = (parseInt(currByte[i])) * 255;
                                    }
                                    binctx.fillStyle = 'rgb(' + brightness + ',' + brightness + ',' + brightness + ')';
                                    binctx.fillRect(imgColumn, imgRow, 1, 1);
                                    imgColumn++;
                                    dontGoDown = 0;
                                    if(imgColumn >= imageSize[0]){
                                        dontGoDown = 1;
                                        imgColumn = 0;
                                        imgRow++;
                                    }
                                }
                                if(alignLines && !dontGoDown){
                                    if(binFinal[byte] === 10){
                                        imgColumn = 0;
                                        imgRow++;
                                    }
                                }else if(imgColumn >= imageSize[0] && !dontGoDown){
                                    imgColumn = 0;
                                    imgRow++;
                                }
                            }
                        }
                    },
                    textToGS: function(invert){
                        if(getId('textToBinDecode').checked && getId('textToBinDecodeImg').files.length !== 0){
                            this.gsToText(invert);
                        }else{
                            var alignBin = getId('textToBinAlign').checked;
                            var alignLines = getId('textToBinLineAlign').checked;
                            var binFile = getId('textToBinInput').value;
                            var binFinal = [];
                            var binLength = 0;
                            for(var byte in binFile){
                                var binStr = binFile.charCodeAt(byte);
                                binFinal.push(binStr);
                                binLength++;
                            } // using decimals, not binary!
                            getId('textToBinOutput').innerHTML = '(<span id="textToBinImgSize"></span>) Right Click to Copy or Save Image<br><canvas id="textToBinCanvas" oncontextmenu="event.stopPropagation();return true;"></canvas>';
                            var bincnv = getId('textToBinCanvas');
                            var binctx = bincnv.getContext('2d');
                            if(alignLines){
                                var imageSize = [0,0];
                                var lastNewline = 0;
                                for(var i = 0; i < binFinal.length; i++){
                                    if(binFinal[i] === 10){
                                        imageSize[1]++;
                                        if(i - lastNewline > imageSize[0]){
                                            imageSize[0] = i - lastNewline;
                                        }
                                        lastNewline = i;
                                    }
                                }
                                imageSize[0]++;
                                imageSize[1]++;
                            }else{
                                var imageSize = Math.floor(Math.sqrt(binLength) + 1);
                                imageSize = [imageSize,imageSize];
                            }
                            getId('textToBinImgSize').innerHTML = imageSize[0] + 'x' + imageSize[1];
                            bincnv.width = imageSize[0];
                            bincnv.height = imageSize[1];
                            bincnv.style.width = imageSize[0] + "px";
                            bincnv.style.height = imageSize[1] + "px";
                            // for each pixel (increment through bytes of string by 3)
                            // make pixel on image equal to the 3 current byte items as rgb
                            var imgRow = 0;
                            var imgColumn = 0;
                            for(var byte = 0; byte < binLength; byte++){
                                if(invert){
                                    var brightness = 255 - (binFinal[byte] || 0);
                                }else{
                                    var brightness = (binFinal[byte] || 0);
                                }
                                binctx.fillStyle = 'rgb(' + brightness + ',' + brightness + ',' + brightness + ')';
                                binctx.fillRect(imgColumn, imgRow, 1, 1);
                                imgColumn++;
                                if(alignLines){
                                    if(binFinal[byte] === 10){
                                        imgColumn = 0;
                                        imgRow++;
                                    }
                                }else if(imgColumn >= imageSize[0]){
                                    imgColumn = 0;
                                    imgRow++;
                                }
                            }
                        }
                    },
                    textToRGB: function(invert){
                        if(getId('textToBinDecode').checked && getId('textToBinDecodeImg').files.length !== 0){
                            this.rgbToText(invert);
                        }else{
                            var alignBin = getId('textToBinAlign').checked;
                            var alignLines = getId('textToBinLineAlign').checked;
                            var binFile = getId('textToBinInput').value;
                            var binFinal = [];
                            var binLength = 0;
                            for(var byte in binFile){
                                var binStr = binFile.charCodeAt(byte);
                                binFinal.push(binStr);
                                binLength++;
                            } // using decimals, not binary!
                            getId('textToBinOutput').innerHTML = '(<span id="textToBinImgSize"></span>) Right Click to Copy or Save Image<br><canvas id="textToBinCanvas" oncontextmenu="event.stopPropagation();return true;"></canvas>';
                            var bincnv = getId('textToBinCanvas');
                            var binctx = bincnv.getContext('2d');
                            if(alignLines){
                                var imageSize = [0,0];
                                var lastNewline = 0;
                                for(var i = 0; i < binFinal.length; i++){
                                    if(binFinal[i] === 10){
                                        imageSize[1]++;
                                        if(i - lastNewline > imageSize[0]){
                                            imageSize[0] = i - lastNewline;
                                        }
                                        lastNewline = i;
                                    }
                                }
                                imageSize[0]++;
                                imageSize[1]++;
                                
                                imageSize[0] = Math.floor(imageSize[0] * 0.3) + 1;
                            }else{
                                var imageSize = Math.floor(Math.sqrt(binLength / 3) + 1);
                                imageSize = [imageSize,imageSize];
                            }
                            if(alignBin && imageSize[0] % 3 !== 0){
                                imageSize[1] += imageSize[0] % 3;
                                imageSize[0] -= imageSize[0] % 3;
                            }
                            getId('textToBinImgSize').innerHTML = imageSize[0] + 'x' + imageSize[1];
                            bincnv.width = imageSize[0];
                            bincnv.height = imageSize[1];
                            bincnv.style.width = imageSize[0] + "px";
                            bincnv.style.height = imageSize[1] + "px";
                            // for each pixel (increment through bytes of string by 3)
                            // make pixel on image equal to the 3 current byte items as rgb
                            var imgRow = 0;
                            var imgColumn = 0;
                            for(var byte = 0; byte < binLength; byte += 3){
                                if(invert){
                                    binctx.fillStyle = 'rgb(' + (255 - (binFinal[byte] || 0)) + ',' + (255 - (binFinal[byte + 1] || 0)) + ',' + (255 - (binFinal[byte + 2] || 0)) + ')';
                                }else{
                                    binctx.fillStyle = 'rgb(' + (binFinal[byte] || 0) + ',' + (binFinal[byte + 1] || 0) + ',' + (binFinal[byte + 2] || 0) + ')';
                                }
                                binctx.fillRect(imgColumn, imgRow, 1, 1);
                                imgColumn++;
                                if(alignLines){
                                    if(binFinal[byte] === 10){
                                        imgColumn = 0;
                                        imgRow++;
                                    }else if(binFinal[byte + 1] === 10){
                                        imgColumn = 0;
                                        imgRow++;
                                    }else if(binFinal[byte + 2] === 10){
                                        imgColumn = 0;
                                        imgRow++;
                                    }
                                }else if(imgColumn >= imageSize[0]){
                                    imgColumn = 0;
                                    imgRow++;
                                }
                            }
                        }
                    },
                    bwToText: function(invert){
                        var binFile = getId('textToBinDecodeImg').files[0];
                        var binUrl = URL.createObjectURL(binFile);
                        var binElement = new Image();
                        binElement.src = binUrl;
                        binElement.onload = function(){
                            var bincnv = document.createElement('canvas');
                            var binctx = bincnv.getContext('2d');
                            bincnv.width = this.width;
                            bincnv.height = this.height;
                            binctx.drawImage(binElement, 0, 0);
                            var binData = binctx.getImageData(0, 0, bincnv.width, bincnv.height);
                            var binFinal = binData.data;
                            var binStr = "";
                            for(var i = 0; i < binFinal.length; i += 32){
                                if(binFinal[i + 3] === 255){
                                    var binValue = 0;
                                    for(var j = i; j < i + 32; j += 4){
                                        if(invert){
                                            var brightness = Math.round((255 - (binFinal[j] + binFinal[j + 1] + binFinal[j + 2]) / 3) / 255);
                                        }else{
                                            var brightness = Math.round((binFinal[j] + binFinal[j + 1] + binFinal[j + 2]) / 3 / 255);
                                        }
                                        binValue += brightness * Math.pow(2, 7 - ((j - i) / 4));
                                    }
                                    if(binValue > 0 && binValue < 256){
                                        binStr += String.fromCharCode(binValue);
                                    }
                                }
                            }
                            getId('textToBinOutput').innerHTML = '<textarea style="width:750px;height:300px;" id="binToTextOutput" display="block"></textarea>';
                            getId('binToTextOutput').value = binStr;
                            /*
                            binFinal = null;
                            binData = null;
                            binctx = null;
                            bincnv = null;
                            binElement = null;
                            URL.revokeObjectURL(binUrl);
                            binUrl = null;
                            binFile = null;
                            */
                            URL.revokeObjectURL(this.src);
                        }
                    },
                    gsToText: function(invert){
                        var binFile = getId('textToBinDecodeImg').files[0];
                        var binUrl = URL.createObjectURL(binFile);
                        var binElement = new Image();
                        binElement.src = binUrl;
                        binElement.onload = function(){
                            var bincnv = document.createElement('canvas');
                            var binctx = bincnv.getContext('2d');
                            bincnv.width = this.width;
                            bincnv.height = this.height;
                            binctx.drawImage(binElement, 0, 0);
                            var binData = binctx.getImageData(0, 0, bincnv.width, bincnv.height);
                            var binFinal = binData.data;
                            var binStr = "";
                            for(var i = 0; i < binFinal.length; i += 4){
                                if(binFinal[i + 3] === 255){
                                    if(invert){
                                        var brightness = 255 - (binFinal[i] + binFinal[i + 1] + binFinal[i + 2]) / 3;
                                    }else{
                                        var brightness =  (binFinal[i] + binFinal[i + 1] + binFinal[i + 2]) / 3;
                                    }
                                    if(brightness > 0 && brightness < 256){
                                        binStr += String.fromCharCode(brightness);
                                    }
                                }
                            }
                            getId('textToBinOutput').innerHTML = '<textarea style="width:750px;height:300px;" id="binToTextOutput" display="block"></textarea>';
                            getId('binToTextOutput').value = binStr;
                            /*
                            binFinal = null;
                            binData = null;
                            binctx = null;
                            bincnv = null;
                            binElement = null;
                            URL.revokeObjectURL(binUrl);
                            binUrl = null;
                            binFile = null;
                            */
                            URL.revokeObjectURL(this.src);
                        }
                    },
                    rgbToText: function(invert){
                        var binFile = getId('textToBinDecodeImg').files[0];
                        var binUrl = URL.createObjectURL(binFile);
                        var binElement = new Image();
                        binElement.src = binUrl;
                        binElement.onload = function(){
                            var bincnv = document.createElement('canvas');
                            var binctx = bincnv.getContext('2d');
                            bincnv.width = this.width;
                            bincnv.height = this.height;
                            binctx.drawImage(binElement, 0, 0);
                            var binData = binctx.getImageData(0, 0, bincnv.width, bincnv.height);
                            var binFinal = binData.data;
                            var binStr = "";
                            for(var i = 0; i < binFinal.length; i += 4){
                                if(binFinal[i + 3] === 255){
                                    for(var j = 0; j < 3; j++){
                                        if(invert){
                                            var brightness = 255 - binFinal[i + j];
                                        }else{
                                            var brightness = binFinal[i + j];
                                        }
                                        if(brightness > 0 && brightness < 256){
                                            binStr += String.fromCharCode(brightness);
                                        }
                                    }
                                }
                            }
                            getId('textToBinOutput').innerHTML = '<textarea style="width:750px;height:300px;" id="binToTextOutput" display="block"></textarea>';
                            getId('binToTextOutput').value = binStr;
                            /*
                            binFinal = null;
                            binData = null;
                            binctx = null;
                            bincnv = null;
                            binElement = null;
                            URL.revokeObjectURL(binUrl);
                            binUrl = null;
                            binFile = null;
                            */
                            URL.revokeObjectURL(this.src);
                        }
                    }
                }
            }
        };
    </script>
</html>