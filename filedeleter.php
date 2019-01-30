<?php
    if(strpos($_SERVER['HTTP_REFERER'], 'https://'.$_SERVER['SERVER_NAME']) === 0){ //aaron-os-mineandcraft12.c9.io'){
        function error($errno, $errstr){
            echo "Error - [" + $errno + '] ' + $errstr;
        }
        set_error_handler("error");
        if(strlen($_POST['k']) == 21 && $_COOKIE['keyword'] === $_POST['k']){
            if(!is_dir('USERFILES/'.$_POST['k'])){
                echo 'Error - user folder does not exist.';
                die();
            }
            if(file_exists('USERFILES/'.$_POST['k'].'/aOSpassword.txt')){
                if(strlen(file_get_contents('USERFILES/'.$_POST['k'].'/aOSpassword.txt')) === 64){
                    unlink('USERFILES/'.$_POST['k'].'/aOSpassword.txt');
                }else{
                    if(strlen(file_get_contents('USERFILES/'.$_POST['k'].'/aOSpassword.txt')) !== 60){
                        $passbc = password_hash(file_get_contents('USERFILES/'.$_POST['k'].'/aOSpassword.txt'), PASSWORD_BCRYPT);
                        $passfile = fopen('USERFILES/'.$_POST['k'].'/aOSpassword.txt', 'w');
                        fwrite($passfile, $passbc);
                        fclose($passfile);
                        //$currPassword = $passbc;
                    }
                }
                if(isset($_COOKIE['password'])){
                    if(!password_verify($_COOKIE['password'], file_get_contents('USERFILES/'.$_POST['k'].'/aOSpassword.txt'))){
                        echo 'Error - Password incorrect.';
                        die();
                    }
                }else{
                    echo 'Error - Password not provided.';
                    die();
                }
            }
            
            $filepath = 'USERFILES/'.$_POST['k'].'/'.str_replace('.', 'X', str_replace('/', 'X', $_POST['f'])).'.txt';
            unlink(realpath($filepath));
        }else{
            echo "Error - User ID malformed, or your user ID is incorrect.";
            die();
        }
    }else{
        echo 'Error - Forbidden request from cross-origin domain.';
        die();
    }
?>