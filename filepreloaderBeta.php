<?php
    // error handler
    function error($errno, $errstr, $errfile, $errline){
        echo "alert('Serverside error in $errfile[$errline]: $errstr\n\nContact mineandcraft12@gmail.com or #bug-reports on https://discord.gg/Y5Jytdm\nIf needed, tell the developer in a PRIVATE conversation, your ID is ".$_COOKIE['keyword']."');";
    }
    set_error_handler("error");
    
    // if USERFILES and such does not exist, create it (first time setup)
    if(!is_dir('USERFILES')){
        mkdir('USERFILES');
        mkdir('USERFILES/!ERROR');
        mkdir('USERFILES/!MESSAGE');
        file_put_contents('USERFILES/newUsers.txt', '');
        file_put_contents('USERFILES/.htaccess', 'Deny from all');
        if(!file_exists('USERFILES/!MESSAGE/m0.txt')){
            file_put_contents('USERFILES/!MESSAGE/m0.txt', '{"i":" ","n":" ","c":"This is the beginning of the message history.","t":"1","l":"0"}');
            //file_put_contents('setting.txt', '0');
        }
        if(!is_dir('messageUsernames')){
            mkdir('messageUsernames');
        file_put_contents('messageUsernames/.htaccess', 'Deny from all');
        }
        if(!is_dir('logins')){
            mkdir('logins');
            file_put_contents('logins/.htaccess', 'Deny from all');
        }
        echo 'alert("By hosting AaronOS or otherwise using its code or resources, you are agreeing to the End User License Agreement which will open in a new window/tab when you click anywhere on the aOS desktop.\nAaronOS is provided FREE OF CHARGE. If you paid money for this software, please contact mineandcraft12@gmail.com");window.tosClick=function(){window.open("eula.txt","_blank");window.removeEventListener("click",window.tosClick)};window.addEventListener("click",window.tosClick);';
    }
    
    if(isset($_COOKIE['password'])){
        setcookie('password', '', time() - 3600);
    }
    
    // if the page needs to be refreshed
    $needtorefresh = false;
    $refreshMessage = '';
    // if a user change was requested
    if(isset($changeKey) && isset($changePass)){
        // if the user exists
        if(is_dir('USERFILES/'.$changeKey)){
            // if the user has a password
            if(file_exists('USERFILES/'.$changeKey.'/aOSpassword.txt')){
                // tell the browser the new user name
                //setcookie('keyword', $changeKey, time() + 321408000);// time() + 321408000);
                //setcookie('logintoken', '', time() - 3600);
                //header('Location: askPassword.php');
                //die();
                
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
                    }else{
                        $refreshMessage = 'Failed to swap, incorrect password.';
                    }
                }
            }else{
                $refreshMessage = 'Failed to swap, no password on target. Get help from the developer.';
            }
        }else{
            $refreshMessage = 'Failed to swap, target does not exist.';
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
        while($newcode === 'blank' || is_dir('USERFILES/'.$newcode)){
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
        while($newcode === 'blank' || is_dir('USERFILES/'.$newcode)){
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
        if(isset($_COOKIE['logintoken'])){
            if((require 'checkToken.php') === 0){
                header('Location: askPassword.php');
            }else{
                echo 'localStorage.removeItem("login_failmessage");';
            }
        }else{
            header('Location: askPassword.php');
        }
        /*
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
        */
    }
    // push javascript to set server variables
    echo 'window.SRVRKEYWORD="'.$_COOKIE['keyword'].'";';
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        echo 'window.IPADDRESS="'.$_SERVER['HTTP_X_FORWARDED_FOR'].'";';
    }else{
        echo 'window.IPADDRESS="undefined";';
    }
    // if it needs to be refreshed, tell the client via js
    if($needtorefresh){
        echo 'localStorage.setItem("login_failmessage", "' + $refreshMessage + '");window.location = "aosBeta.php?refreshed="+Math.round(Math.random()*1000);doLog("Moving");';
    }
    // if user folder not exist, create it
    if(!(is_dir('USERFILES/'.$_COOKIE['keyword']))){
        $newUser = 1;
        mkdir('USERFILES/'.$_COOKIE['keyword']);
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
    
    // renew your keyword cookie
    setcookie('keyword', $_COOKIE['keyword'], time() + 321408000);
?>