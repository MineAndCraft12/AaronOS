<?php
    if(is_dir('logins')){
        if(file_exists('logins/'.$_COOKIE['keyword'].'.txt')){
            if(password_verify($_COOKIE['logintoken'], file_get_contents('logins/'.$_COOKIE['keyword'].'.txt'))){
                echo "1";
            }
        }
    }
    echo "0";
?>