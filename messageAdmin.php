<?php
    function error($errno, $errstr){
        echo "Error - [" + $errno + '] ' + $errstr;
    }
    set_error_handler("error");
    if(strlen($_COOKIE['keyword']) == 21){
        if(!is_dir('USERFILES/'.$_COOKIE['keyword'])){
            mkdir('USERFILES/'.$_COOKIE['keyword']);
        }
        
        if(file_exists('messageUsernames/n_'.$_COOKIE['keyword'].'.txt')){
            if(strpos(file_get_contents('messageUsernames/n_'.$_COOKIE['keyword'].'.txt'), '{ADMIN}') !== 0){
                echo 'Error - You are not an admin.';
                die();
            }
        }else{
            echo 'Error - You are not an admin.';
            die();
        }
        
        if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')){
            if(strlen(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')) === 64){
                unlink('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt');
            }else{
                if(strlen(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')) !== 60){
                    $passbc = password_hash(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt'), PASSWORD_BCRYPT);
                    $passfile = fopen('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt', 'w');
                    fwrite($passfile, $passbc);
                    fclose($passfile);
                }
            }
            if(isset($_COOKIE['password'])){
                if(!password_verify($_COOKIE['password'], file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt'))){
                    echo 'Error - Password incorrect.';
                    die();
                }
            }else{
                echo 'Error - Password not provided.';
                die();
            }
        }
    }else{
        echo "Error - User ID malformed, or your user ID is incorrect. ".$_COOKIE['keyword'];
        die();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            .message{
                margin-top:16px;
                padding-left:8px;
                border-left:1px solid #000;
                border-radius:5px;
                font-family:monospace;
            }
        </style>
        <script defer>
            function getId(target){
                return document.getElementById(target);
            }
            function cleanStr(str){
                return str.split('&').join('&amp;').split('<').join('&lt;').split('>').join('&gt;');
            }
            
            var tempDate;
            var date;
            var skipKey;
            var tempDayt;
            var dateDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var dateForms = {
                D: function(){ // day number
                    tempDate += date.getDate();
                },
                d: function(){ // day of week
                    tempDate += dateDays[date.getDay()];
                },
                y: function(){ // 2-digit year
                    tempDate += String(date.getFullYear() - 2000);
                },
                Y: function(){ // 4-digit year
                    tempDate += date.getFullYear();
                },
                h: function(){ // 12-hour time
                    if(date.getHours() > 12){
            			tempDayt = String((date.getHours()) - 12);
            		}else{
            			tempDayt = String(date.getHours());
            		}
            		if(tempDayt === "0"){
            			tempDate += "12";
            		}else{
            			tempDate += tempDayt;
            		}
                },
                H: function(){ // 24-hour time
                    tempDate += String(date.getHours());
                },
                s: function(){ // milliseconds
                    if(date.getMilliseconds() < 10){
            		    tempDate += '00' + date.getMilliseconds();
            	    }else if(date.getMilliseconds() < 100){
            	        tempDate += '0' + date.getMilliseconds();
            	    }else{
            	        tempDate += date.getMilliseconds();
            	    }
                },
                S: function(){ // seconds
                    tempDayt = String(date.getSeconds());
            		if(tempDayt < 10){
                        tempDate += "0" + tempDayt;
            		}else{
                        tempDate += tempDayt;
            		}
                },
                m: function(){ // minutes
                    tempDayt = date.getMinutes();
            		if(tempDayt < 10){
                        tempDate += "0" + tempDayt;
            		}else{
                        tempDate += tempDayt;
            		}
                },
                M: function(){ // month
                    tempDate += String(date.getMonth() + 1);
                },
                "-": function(){ // escape character
                    
                }
            };
            function formDate(ms, dateStr){
            	tempDate = "";
            	date = new Date(ms);
            	skipKey = 0;
            	for(var dateKey in dateStr){
            		if(skipKey){
            			skipKey = 0;
            		}else{
            		    if(dateForms[dateStr[dateKey]]){
            		        dateForms[dateStr[dateKey]]();
            		    }else{
            		        tempDate += dateStr[dateKey];
            		    }
            		}
            	}
            	return tempDate;
            }
            
            var xhttp;
            var fd;
            
            var messages = {};
            
            function getAll(){
                xhttp = new XMLHttpRequest();
                xhttp.open('POST', 'messageAdminAction.php');
                
                xhttp.onreadystatechange = recieveAllMessages;
                
                fd = new FormData();
                fd.append('action', 'getMessages');
                
                xhttp.send(fd);
            }
            function recieveAllMessages(){
                if(xhttp.readyState === 4){
                    if(xhttp.status === 200){
                        if(xhttp.responseText.indexOf('Error:') === 0){
                            alert(xhttp.responseText);
                        }else{
                            messages = JSON.parse(xhttp.responseText);
                            for(var i in messages){
                                messages[i] = JSON.parse(messages[i]);
                            }
                            displayMessages();
                        }
                    }else{
                        alert("Network Error " + xhttp.status);
                    }
                }
            }
            
            var currentEdit = '';
            
            function setContent(targetMsg){
                currentEdit = targetMsg;
                
                xhttp = new XMLHttpRequest();
                xhttp.open('POST', 'messageAdminAction.php');
                
                xhttp.onreadystatechange = recieveSetContent;
                
                fd = new FormData();
                fd.append('action', 'setContent');
                fd.append('target', targetMsg);
                fd.append('content', getId('content_' + targetMsg).value);
                
                xhttp.send(fd);
            }
            function recieveSetContent(){
                if(xhttp.readyState === 4){
                    if(xhttp.status === 200){
                        if(xhttp.responseText.indexOf('Error:') === 0){
                            alert(xhttp.responseText);
                        }else{
                            messages[currentEdit  + '.txt'].c = xhttp.responseText;
                            displayMessages();
                        }
                    }else{
                        alert("Network Error " + xhttp.status);
                    }
                }
            }
            
            var currentAuthorEdit = '';
            
            function setAuthor(targetMsg){
                currentAuthorEdit = targetMsg;
                
                xhttp = new XMLHttpRequest();
                xhttp.open('POST', 'messageAdminAction.php');
                
                xhttp.onreadystatechange = recieveSetAuthor;
                
                fd = new FormData();
                fd.append('action', 'setAuthor');
                fd.append('target', targetMsg);
                fd.append('content', getId('content_' + targetMsg).value);
                
                xhttp.send(fd);
            }
            function recieveSetAuthor(){
                if(xhttp.readyState === 4){
                    if(xhttp.status === 200){
                        if(xhttp.responseText.indexOf('Error:') === 0){
                            alert(xhttp.responseText);
                        }else{
                            messages[currentAuthorEdit  + '.txt'].n = xhttp.responseText;
                            displayMessages();
                        }
                    }else{
                        alert("Network Error " + xhttp.status);
                    }
                }
            }
            
            function displayMessages(){
                var str = '';
                for(var i in messages){
                    var msgDate = formDate(parseInt(messages[i].t), 'M/D/Y H:m:S');
                    str += '<div class="message" id="' + i.split('.')[0] + '">' +
                        'Message: ' + i + ' (' + messages[i].l + ')<br>' +
                        'Timestamp: ' + msgDate + '<br>' +
                        'Author: ' + cleanStr(messages[i].n) + '<br>' +
                        'Content: ' + cleanStr(messages[i].c) + '<br><br>' +
                        
                        'Override Author: <input id="author_' + i.split('.')[0] + '"> <button onclick="setAuthor(\'' + i.split('.')[0] + '\')">Set</button><br>' +
                        'Override Content: <input id="content_' + i.split('.')[0] + '"> <button onclick="setContent(\'' + i.split('.')[0] + '\')">Set</button>' +
                        '</div>';
                }
                getId('messages').innerHTML = str;
            }
        </script>
    </head>
    <body>
        <button onclick="getAll()">Get All Messages</button>
        <div id="messages"></div>
    </body>
</html>