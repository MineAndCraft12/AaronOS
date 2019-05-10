<?php
    if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')){
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
            
            if(password_verify($_POST['pass'], $currPassword)){
                // SET LOGIN TOKEN AND SAVE IT
                $newtoken = strval(microtime(TRUE));
                
                if(!is_dir('logins')){
                    mkdir('logins');
                    file_put_contents('logins/.htaccess', 'Deny from all');
                }
                file_put_contents('logins/'.$_COOKIE['keyword'].'.txt', password_hash($newtoken, PASSWORD_BCRYPT));
                echo $newtoken;
            }else{
                echo 'REJECT';
            }
        }
    }else{
        echo 'no password is set';
    }
?>