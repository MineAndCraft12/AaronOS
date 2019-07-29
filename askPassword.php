<!DOCTYPE html>
<html>
    <head>
        <title>AaronOS - Password</title>
        <script defer>
            function getId(target){
                return document.getElementById(target);
            }
            var googlePlay = "";
            if(window.location.href.indexOf('GooglePlay=true') > -1){
                googlePlay = '?GooglePlay=true';
            }
            function acceptPassword(token){
                document.cookie = "logintoken=" + token + ';';
                getId("checkBtn").style.opacity = "0.5";
                getId("checkBtn").style.pointerEvents = "none";
                getId("password").style.opacity = "0.5";
                getId("password").style.pointerEvents = "none";
                setTimeout(function(){
                    window.location = 'aosBeta.php' + googlePlay;
                }, 500);
            }
            function rejectPassword(message){
                getId("error").innerHTML = message;
                getId('password').value = '';
            }
            if(localStorage.getItem("login_failmessage")){
                rejectPassword(localStorage.getItem("login_failmessage"));
                localStorage.removeItem("login_failmessage");
            }
            var running = 0;
            function checkPassword(){
                //document.cookie = 'password=' + document.getElementById('password').value + "; Max-Age:315576000";
                //window.location = 'aosBeta.php' + googlePlay;
                if(!running){
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = () => {
                        if(xhttp.readyState === 4){
                            if(xhttp.status === 200){
                                if(xhttp.responseText === 'REJECT'){
                                    rejectPassword("Password incorrect.");
                                }else if(xhttp.responseText.indexOf(' ') === -1 && xhttp.responseText.length === 30 && xhttp.responseText !== ''){
                                    acceptPassword(xhttp.responseText);
                                }else{
                                    rejectPassword("Error: " + xhttp.responseText + '.');
                                }
                            }else{
                                rejectPassword("Connection error " + xhttp.status + ". Try again.");
                            }
                            running = 0;
                        }
                    };
                    var fd = new FormData();
                    fd.append("pass", document.getElementById('password').value);
                    fd.append("loggingInViaUI", "true");
                    xhttp.open('POST', 'checkPassword.php');
                    xhttp.send(fd);
                    running = 1;
                }
            }
            function skipPassword(){
                if(confirm("Are you sure you want to create a new account and potentially lose all the data associated with your old one?")){
                    document.cookie = 'keyword=; Max-Age=-99999999';
                    window.location = 'aosBeta.php' + googlePlay;
                }
            }
            var apps = {
                messaging: {
                    vars: {
                        objTypes: {
                            img: function(str){
                                return '<img onclick="this.classList.toggle(\'MSGdivGrowPic\');this.parentNode.classList.toggle(\'MSGdivGrowPicParent\')" style="max-width:calc(100% - 6px);max-height:400px;padding-left:3px;padding-right:3px;" src="' + str + '">';
                            },
                            url: function(str){
                                if(str.indexOf('http://') !== 0 && str.indexOf('https://') !== 0 && str.indexOf('/') !== 0){
                                    str = 'https://' + str;
                                }
                                return '<a target="_blank" href="' + str + '">' + str + '</a>';
                            },
                            site: function(str){
                                if(str.indexOf('http://') !== 0 && str.indexOf('https://') !== 0 && str.indexOf('/') !== 0){
                                    str = 'https://' + encodeURI(str);
                                }
                                return '<div style="position:relative;display:block;width:100%;border:none;background:#FFF;margin-top:-3px;margin-bottom:-3px;border-radius:10px;box-shadow:inset 0 0 5px #000;height:400px;" onclick="if(event.target.tagName.toLowerCase() === \'button\'){this.outerHTML = \'<iframe src=\\\'\' + this.getAttribute(\'aosMessagingSiteURL\') + \'\\\' style=\\\'\' + this.getAttribute(\'style\') + \'\\\'></iframe>\'}" aosMessagingSiteURL="' + str + '"><p style="margin-top:188px;text-align:center;"><button>Click to load site:<br>' + str + '</button></p></div>';
                            },
                            b: function(str){
                                return '<b>' + str + '</b>';
                            },
                            i: function(str){
                                return '<i>' + str + '</i>';
                            },
                            u: function(str){
                                return '<u>' + str + '</u>';
                            },
                            font: function(str){
                                var strComma = str.indexOf(',');
                                var strCommaSpace = str.indexOf(', ');
                                var strSplit = '';
                                if(strComma > -1){
                                    if(strCommaSpace === strComma){
                                        strSplit = str.split(', ');
                                        return '<span style="font-family:' + strSplit.shift().split(';')[0] + ', monospace;">' + strSplit.join(', ') + '</span>';
                                    }else{
                                        strSplit = str.split(',');
                                        return '<span style="font-family:' + strSplit.shift().split(';')[0] + ', monospace;">' + strSplit.join(',') + '</span>';
                                    }
                                }else{
                                    return '[font]' + str + '[/font]';
                                }
                            },
                            color: function(str){
                                var strComma = str.indexOf(',');
                                var strCommaSpace = str.indexOf(', ');
                                var strSplit = '';
                                if(strComma > -1){
                                    if(strCommaSpace === strComma){
                                        strSplit = str.split(', ');
                                        return '<span style="color:' + strSplit.shift().split(';')[0] + ';">' + strSplit.join(', ') + '</span>';
                                    }else{
                                        strSplit = str.split(',');
                                        return '<span style="color:' + strSplit.shift().split(';')[0] + ';">' + strSplit.join(',') + '</span>';
                                    }
                                }else{
                                    return '[color]' + str + '[/color]';
                                }
                            },
                            glow: function(str){
                                var strComma = str.indexOf(',');
                                var strCommaSpace = str.indexOf(', ');
                                var strSplit = '';
                                if(strComma > -1){
                                    if(strCommaSpace === strComma){
                                        strSplit = str.split(', ');
                                        return '<span style="text-shadow:0 0 5px ' + strSplit.shift().split(';')[0].split(' ')[0] + ';">' + strSplit.join(', ') + '</span>';
                                    }else{
                                        strSplit = str.split(',');
                                        return '<span style="text-shadow:0 0 5px ' + strSplit.shift().split(';')[0].split(' ')[0] + ';">' + strSplit.join(',') + '</span>';
                                    }
                                }else{
                                    return '[glow]' + str + '[/glow]';
                                }
                            },
                            outline: function(str){
                                var strComma = str.indexOf(',');
                                var strCommaSpace = str.indexOf(', ');
                                var strSplit = '';
                                if(strComma > -1){
                                    if(strCommaSpace === strComma){
                                        strSplit = str.split(', ');
                                        strShift = strSplit.shift().split(';')[0].split(' ')[0];
                                        return '<span style="text-shadow:1px 0 0 ' + strShift + ', -1px 0 0 ' + strShift + ', 0 1px 0 ' + strShift + ', 0 -1px 0 ' + strShift + ';">' + strSplit.join(', ') + '</span>';
                                    }else{
                                        strSplit = str.split(',');
                                        strShift = strSplit.shift().split(';')[0].split(' ')[0];
                                        return '<span style="text-shadow:1px 0 0 ' + strShift + ', -1px 0 0 ' + strShift + ', 0 1px 0 ' + strShift + ', 0 -1px 0 ' + strShift + ';">' + strSplit.join(',') + '</span>';
                                    }
                                }else{
                                    return '[outline]' + str + '[/outline]';
                                }
                            },
                            flip: function(str){
                                return '<div style="transform:rotate(180deg);display:inline-block;position:relative">' + str + '</div>';
                            }
                        },
                        objSafe: {
                            img: 0,
                            url: 0,
                            site: 0,
                            b: 1,
                            i: 1,
                            u: 1,
                            font: 1,
                            color: 1,
                            glow: 1,
                            outline: 1,
                            flip: 1
                        },
                        objDesc: {
                            img: 'Embed an image via URL.',
                            url: 'Format your text as a clickable URL.',
                            site: 'Embed a website via URL',
                            b: 'Format your text as bold.',
                            i: 'Format your text as italics.',
                            u: 'Format your text as underlined.',
                            font: 'Format your text with a font.',
                            color: 'Format your text with a color.',
                            glow: 'Format your text with a colorful glow.',
                            outline: 'Format your text with an outline.',
                            flip: 'Flip your text upside-down.'
                        },
                        objExamp: {
                            img: '[img]https://image.prntscr.com/image/jcPyuzbNTNu1cHgg18yaZg.png[/img]',
                            url: '[url]https://duckduckgo.com[/url]',
                            site: '[site]https://bing.com[/site]',
                            b: '[b]This is bold text.[/b]',
                            i: '[i]This is italic text.[/i]',
                            u: '[u]This is underlined text.[/u]',
                            font: '[font]Comic Sans MS, This text has a custom font.[/font]',
                            color: '[color]red, This is red text via name.[/color]<br><br>[color]#00AA00, This is green text via hex.[/color]',
                            glow: '[glow]red, This is glowy red text.[/glow]',
                            outline: '[outline]red, This is red outlined text.[/outline]',
                            flip: '[flip]This is upside-down text.[/flip]'
                        },
                        parseBB: function(text, safe){
                            var tempIn = text;
                            var tempPointer = tempIn.length - 6;
                            while(tempPointer >= 0){
                                var nextObj = tempIn.indexOf('[', tempPointer);
                                if(nextObj > -1){
                                    var nextEnd = tempIn.indexOf(']', nextObj);
                                    if(nextEnd > -1){
                                        var nextType = tempIn.toLowerCase().substring(nextObj + 1, nextEnd);
                                        if(this.objTypes[nextType]){
                                            var nextClose = tempIn.toLowerCase().indexOf('[/' + nextType + ']', nextEnd);
                                            if(nextClose > -1){
                                                if(!(safe && !this.objSafe[nextType])){
                                                    var replaceStr = tempIn.substring(nextEnd + 1, nextClose);
                                                    var newStr = this.objTypes[nextType](replaceStr);
                                                    tempIn = tempIn.substring(0, nextObj) + newStr + tempIn.substring(nextClose + 3 + nextType.length, tempIn.length);
                                                }
                                            }
                                        }
                                    }
                                }
                                tempPointer--;
                            }
                            return tempIn;
                        }
                    }
                }
            };
        </script>
        <?php
            echo '<link rel="stylesheet" href="askPassword.css?ms='.time().'">';
        ?>
    </head>
    <body>
        <?php
            if(isset($_COOKIE['keyword'])){
                if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt')){
                    echo '<div id="background" style="background-image:url('.file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/APP_STN_SETTING_BACKGROUND.txt').');"></div>';
                }else{
                    echo '<div id="background"></div>';
                }
            }else{
                echo '<div id="background"></div>';
            }
        ?>
        <div id="card">
            <h1>AaronOS</h1>
            <hr>
            <?php
                if(isset($_COOKIE['keyword'])){
                    if(file_exists('messageUsernames/n_'.$_COOKIE['keyword'].'.txt')){
                        echo 'Welcome, <span id="name">'.file_get_contents('messageUsernames/n_'.$_COOKIE['keyword'].'.txt').'</span><script>document.getElementById("name").innerHTML = apps.messaging.vars.parseBB(document.getElementById("name").innerHTML, 1)</script>';
                    }else{
                        echo 'Welcome';
                    }
                }else{
                    echo 'Welcome';
                }
            ?><br><br>
            OS ID: <?php echo $_COOKIE['keyword'] ?><br>
            &nbsp;<br>
            <span id="error"></span><br>
            <input type="password" placeholder="Password" onkeypress="if(event.keyCode === 13){checkPassword();}" id="password"> <button id="checkBtn" onclick="checkPassword()">Log In</button><br><br>
            Or, if this isn't your account, <button onclick="skipPassword()">Create a New One</button>.<br><br>
        </div>
    </body>
    <script defer>
        document.getElementById('password').focus();
    </script>
</html>