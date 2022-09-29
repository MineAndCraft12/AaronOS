<?php
    header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_REFERER']);

    if(isset($_COOKIE['keyword'])){
        if($_COOKIE['keyword']){
            if(strpos($_COOKIE['keyword'], '.') !== false || strpos($_COOKIE['keyword'], '/') !== false){
                // bad cookie. ignore it
                unset($_COOKIE['keyword']);
            }
        }
    }
    ini_set("open_basedir", "./");

    if(isset($_GET['id']) && isset($_GET['file']) && (isset($_GET['text']) || isset($_GET['delete']))/* && substr($_SERVER['HTTP_REFERER'], 0, 37) === 'https://aaron-os-mineandcraft12.c9.io'*/){
        function error($errno, $errstr){
            echo "Error - [" + $errno + '] ' + $errstr;
            die();
        }
        set_error_handler("error");
        if(strlen($_GET['text']) !== 0){
            if(isset($_COOKIE['keyword'])){
                if(is_dir('USERFILES/'.$_GET['id']) && strlen($_GET['id']) == 21 && $_COOKIE['keyword'] === $_GET['id']){
                    if(file_exists('USERFILES/'.$_GET['id'].'/aOSpassword.txt')){
                        if(isset($_COOKIE['password'])){
                            if(!password_verify($_COOKIE['password'], file_get_contents('USERFILES/'.$_GET['id'].'/aOSpassword.txt'))){
                                echo 'Error - User password is incorrect.';
                                die();
                            }
                        }else{
                            echo 'Error - User is not logged in. Have your user log in to aOS on this browser.';
                            die();
                        }
                    }else{
                        echo 'Error - User does not have a password. Have your user log into aOS and set a password.';
                        die();
                    }
                    if(strpos(strtolower($_GET['file']), '&') !== false || strpos(strtolower($_GET['file']), '%') !== false || strpos(strtolower($_GET['file']), 'aospassword') !== false || strpos(strtolower($_GET['file']), 'app_stn_trusted_servers') !== false || strpos(strtolower($_GET['file']), '/') !== false || strpos(strtolower($_GET['file']), ' ') !== false || strpos(strtolower($_GET['file']), 'app_bts_bootscript') !== false){
                        echo 'Error - Forbidden string or character used in filename.';
                        die();
                    }
                    if(strlen($_GET['file']) === 0){
                        echo 'Error - Filename cannot be empty.';
                        die();
                    }
                    if(file_exists('USERFILES/'.$_GET['id'].'/APP_STN_trusted_servers.txt')){
                        $trustedservers = explode('\n', file_get_contents('USERFILES/'.$_GET['id'].'/APP_STN_trusted_servers.txt'));
                        $trusted = 0;
                        foreach($trustedservers as $server){
                            if(strpos($_SERVER['HTTP_REFERER'], $server) === 0){
                                $trusted = 1;
                            }
                        }
                        if($trusted === 0){
                            echo 'Error - User has not permitted your access. Have the user add you to their trusted server list.';
                            die();
                        }
                    }else{
                        echo 'Error - User has not permitted your access. Have the user add you to their trusted server list.';
                        die();
                    }
                    
                    if(isset($_GET['delete'])){
                        if($_GET['delete'] === 'true'){
                            $filepath = 'USERFILES/'.$_GET['id'].'/'.str_replace('.', 'X', $_GET['file']).'.txt';
                            if(file_exists($filepath)){
                                unlink($filepath);
                                echo "Success - File deleted.";
                            }else{
                                echo 'Error - File not found, cannot be deleted.';
                            }
                            die();
                        }else{
                            echo 'Error - You set the deletion variable but it is not "true".';
                        }
                    }
                    $filepath = 'USERFILES/'.$_GET['id'].'/'.str_replace('.', 'X', $_GET['file']).'.txt';
                    $file = fopen($filepath, 'w');
                    fwrite($file, str_replace("\\", "\\\\", $_GET['text']));
                    echo "Success - File written.";
                    fclose($file);
                    
                }else{
                    echo "Error - User not found, or user ID malformed, or user ID is incorrect.";
                    die();
                }
            }else{
                echo 'Error - User is not logged in. Have your user log in to aOS on this browser.';
                die();
            }
        }else{
            echo 'Error - Text content is blank.';
            die();
        }
    }else{
        echo 'Error - One or more of these parameters not provided: id, file, text.';
        die();
    }
?>