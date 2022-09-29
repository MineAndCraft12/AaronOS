<?php
    if(isset($_COOKIE['keyword'])){
        if($_COOKIE['keyword']){
            if(strpos($_COOKIE['keyword'], '.') !== false || strpos($_COOKIE['keyword'], '/') !== false){
                // bad cookie. ignore it
                unset($_COOKIE['keyword']);
                return 0;
            }
        }
    }
    ini_set("open_basedir", "./");

    if(is_dir('logins')){
        if(file_exists('logins/'.$_COOKIE['keyword'].'.txt')){
            if(password_verify($_COOKIE['logintoken'], file_get_contents('logins/'.$_COOKIE['keyword'].'.txt'))){
                return 1;
            }
        }
    }
    return 0;
?>