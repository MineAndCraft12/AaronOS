<?php

    // THIS FILE IS DEPRECATED, see filepreloaderBeta.php and fileloaderBeta.php
    
    // error handler
    function error($errno, $errstr){
        echo "alert('Serverside error. [$errno]: $errstr | Contact mineandcraft12@gmail.com or else the file broken and every other file you save from now on will be lost. Reference error code ".$_COOKIE['keyword']." in the email.');";
    }
    set_error_handler("error");
    
    // if USERFILES and such does not exist, create it (first time setup)
    if(!is_dir('USERFILES')){
        mkdir('USERFILES');
        mkdir('USERFILES/!ERROR');
        mkdir('USERFILES/!MESSAGE');
        if(!file_exists('setting.txt')){
            file_put_contents('USERFILES/!MESSAGE/m0.txt', '{"i":" ","n":" ","c":"This is the beginning of the message history.","t":"1","l":"0"}');
            file_put_contents('setting.txt', '0');
        }
        if(!is_dir('messageUsernames')){
            mkdir('messageUsernames');
        }
        echo 'alert("By hosting AaronOS or otherwise using its code or resources, you are agreeing to the End User License Agreement which will open in a new window/tab when you click anywhere on the aOS desktop.");window.tosClick=function(){window.open("terms.txt","_blank");window.removeEventListener("click",window.tosClick)};window.addEventListener("click",window.tosClick);';
    }
    
    // if the page needs to be refreshed
    $needtorefresh = false;
    // if a user change was requested
    if(isset($changeKey) && isset($changePass) && isset($_COOKIE['changingKey'])){
        // if the user exists
        if(is_dir('USERFILES/'.$changeKey)){
            // if the user has a password
            if(file_exists('USERFILES/'.$changeKey.'/aOSpassword.txt')){
                // grab the users password
                $passfile = fopen('USERFILES/'.$changeKey.'/aOSpassword.txt', 'r');
                $targetPass = fread($passfile, filesize('USERFILES/'.$changeKey.'/aOSpassword.txt'));
                fclose($passfile);
                
                // if password is sha256, delete
                if(strlen($targetPass) === 64){
                    unlink('USERFILES/'.$changeKey.'/aOSpassword.txt');
                }else{
                    //ensure password is bcrypt
                    if(strlen($targetPass) !== 60){
                        $passbc = password_hash($targetPass, PASSWORD_BCRYPT);
                        $passfile = fopen('USERFILES/'.$changeKey.'/aOSpassword.txt', 'w');
                        fwrite($passfile, $passbc);
                        fclose($passfile);
                        $targetPass = $passbc;
                    }
                    
                    // if password is correct
                    if(password_verify($changePass, $targetPass)){
                        // tell the browser the new user name
                        setcookie('keyword', $changeKey, time() + 321408000);// time() + 321408000);
                        $_COOKIE['keyword'] = $changeKey;
                    }
                }
            }
        }
        // page needs to be refreshed on clientside
        $needtorefresh = true;
    }
    // alphabet available to make userkeys from
    $newUser = 0;
    $lettertypes = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
    // if user not logged in
    if(!isset($_COOKIE['keyword'])){
        $newUser = 1;
        // create a new userkey
        $newcode = 'blank';
        while(is_dir('USERFILES/'.$newcode)){
            $newcode = '';
            for($i = 0; $i < 21; $i++){ // capacity for 41-trillion, 107-billion, 996-million, 877-thousand, 935-hundred, 680 unique web-browser keys
                $newcode = $newcode.$lettertypes[random_int(0, strlen($lettertypes) - 1)];
            }
        }
        // tell the browser
        setcookie('keyword', $newcode, time() + 321408000);
        $_COOKIE['keyword'] = $newcode;
    }
    // if user has weird keyword
    if(preg_match('/[^A-Za-z0-9_-]/', $_COOKIE['keyword'])){
        $newUser = 1;
        // create a new userkey
        $newcode = 'blank';
        while(is_dir('USERFILES/'.$newcode)){
            $newcode = '';
            for($i = 0; $i < 21; $i++){ // capacity for 41-trillion, 107-billion, 996-million, 877-thousand, 935-hundred, 680 unique web-browser keys
                $newcode = $newcode.$lettertypes[random_int(0, strlen($lettertypes) - 1)];
            }
        }
        // tell the browser
        setcookie('keyword', $newcode, time() + 321408000);
        $_COOKIE['keyword'] = $newcode;
    }
    if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')){
        if(isset($_COOKIE['password'])){
            $passwordFile = fopen('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt', 'r');
            $currPassword = fread($passwordFile, filesize('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt'));
            fclose($passwordFile);
            
            if(strlen($currPassword) === 64){
                unlink('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt');
            }else{
                if(strlen($currPassword) !== 60){
                    $passbc = password_hash($currPassword, PASSWORD_BCRYPT);
                    $passfile = fopen('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt', 'w');
                    fwrite($passfile, $passbc);
                    fclose($passfile);
                    $currPassword = $passbc;
                }
                
                if(!password_verify($_COOKIE['password'], $currPassword)){
                    if(isset($_GET['GooglePlay'])){
                        header('Location: askPassword.php?GooglePlay=true');
                    }else{
                        header('Location: askPassword.php');
                    }
                }
            }
        }else{
            if(isset($_GET['GooglePlay'])){
                header('Location: askPassword.php?GooglePlay=true');
            }else{
                header('Location: askPassword.php');
            }
        }
    }
    // push javascript to begin file loading on client side
    echo 'loadInterval=window.setInterval(function(){if(window.initStatus){m("init fileloader");SRVRKEYWORD="'.$_COOKIE['keyword'].'";IPADDRESS="'.$_SERVER['HTTP_X_FORWARDED_FOR'].'";getId("aOSloadingInfo").innerHTML += "<br>Your OS key is " + SRVRKEYWORD;';
    // if it needs to be refreshed, tell the client via js
    if($needtorefresh){
        echo 'window.location = "aosBeta.php?refreshed="+Math.round(Math.random()*1000);doLog("Moving");';
    }
    // if user folder not exist, create it
    if(!(is_dir('USERFILES/'.$_COOKIE['keyword']))){
        $newUser = 1;
        mkdir('USERFILES/'.$_COOKIE['keyword']);
    }
    // begin grabbing users files
    $directory = scandir('USERFILES/'.$_COOKIE['keyword']);
    foreach($directory as $filename){
        // if not moving back a dir
        if($filename != '.' && $filename != '..'){
            // if the file needs an underscore
            $fileusesunderscore = '';
            // if invalid characters start the filename
            if(strpos('0123456789.,-[]/+=\\`"\'!*:|@#%^&()<>?|;~', $filename[0]) !== false){
                // file needs an underscore
                $fileusesunderscore = '_';
            }
            // grab the file contents and push it to javascript
            $file = fopen('USERFILES/'.$_COOKIE['keyword'].'/'.$filename, 'r');
            echo  'try{USERFILES.'.$fileusesunderscore.pathinfo($filename, PATHINFO_FILENAME).'=`'.str_replace("/script", "\\/script", str_replace("\r\n", '\\n', str_replace('`', '\\`', fread($file, filesize('USERFILES/'.$_COOKIE['keyword'].'/'.$filename))))).'`;}catch(err){alert(err)}';
            fclose($file);
        }
    }
    
    $newUsers = fopen('USERFILES/newUsers.txt', 'r');
    if(filesize('USERFILES/newUsers.txt') === 0){
        $newUsersList = array();
    }else{
        $newUsersList = explode("\n", fread($newUsers, filesize('USERFILES/newUsers.txt')));
    }
    fclose($newUsers);
    $newList = array();
    foreach($newUsersList as $user){
        if((int)substr($user, strpos($user, '=') + 1, strlen($user)) >= round(microtime(true) * 1000) - 120000){
            array_push($newList, $user);
        }
    }
    if($newUser){
        array_push($newList, $_COOKIE['keyword'].'='.round(microtime(true) * 1000));
    }
    unset($user);
    $newUsers = fopen('USERFILES/newUsers.txt', 'w');
    fwrite($newUsers, join("\n", $newList));
    fclose($newUsers);
    
    // finish the loading javascript
    echo 'clearInterval(loadInterval);for(var app in apps){getId("aOSloadingInfo").innerHTML="Loading your files...<br>Your OS key is"+SRVRKEYWORD+"<br>Loading "+app;try{apps[app].signalHandler("USERFILES_DONE");}catch(err){alert(err)}}console.log("Load successful, interval deleted and apps alerted.");}},1);';
?>