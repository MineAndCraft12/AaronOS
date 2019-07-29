<?php
    if(is_dir('logins')){
        if(file_exists('logins/'.$_COOKIE['keyword'].'.txt')){
            if(password_verify($_COOKIE['logintoken'], file_get_contents('logins/'.$_COOKIE['keyword'].'.txt'))){
                echo "1";
            }else{
                echo "failed verify<br>";
            }
        }else{
            echo "failed exist<br>";
        }
    }else{
        echo "failed is_dir<br>";
    }
    echo "0";
?>