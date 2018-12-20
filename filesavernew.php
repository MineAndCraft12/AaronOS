<?php
    if(isset($_POST['c']) && substr($_SERVER['HTTP_REFERER'], 0, 37) === 'https://'.$_SERVER['SERVER_NAME']){ //aaron-os-mineandcraft12.c9.io'){
        function error($errno, $errstr){
            echo "Error - [" + $errno + '] ' + $errstr;
        }
        set_error_handler("error");
        if(strlen($_POST['c']) !== 0){
            if(strlen($_POST['k']) == 21 && $_COOKIE['keyword'] === $_POST['k']){
                if(!is_dir('USERFILES/'.$_POST['k'])){
                    mkdir('USERFILES/'.$_POST['k']);
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
                if(isset($_GET['rdp'])){
                    $filepath = 'USERFILES/!RDP/'.$_POST['k'].'.html';
                }else{
                    $filepath = 'USERFILES/'.$_POST['k'].'/'.str_replace('.', 'X', $_POST['f']).'.txt';
                }
                
                $newUsers = fopen('USERFILES/newUsers.txt', 'r');
                if(filesize('USERFILES/newUsers.txt') === 0){
                    $newUsersList = array();
                }else{
                    $newUsersList = explode("\n", fread($newUsers, filesize('USERFILES/newUsers.txt')));
                }
                fclose($newUsers);
                $newList = array();
                $userFound = 0;
                $user = 'none';
                foreach($newUsersList as $user){
                    if((int)substr($user, strpos($user, '=') + 1, strlen($user)) >= round(microtime(true) * 1000) - 120000){
                        array_push($newList, $user);
                        //if(strpos($user, $_COOKIE['keyword']) === 0){
                        //    echo 'Error - Your account is too new to save files, please wait a total of 30 seconds before creating your first file. This is to prevent a flood on the server.';
                        //    $userFound = 1;
                        //}
                    }
                }
                unset($user);
                $newUsers = fopen('USERFILES/newUsers.txt', 'w');
                fwrite($newUsers, join("\n", $newList));
                fclose($newUsers);
                if($userFound){
                    die();
                }
                
                $file = fopen($filepath, 'w');
                //if($file){
                    //if(isset($_GET['rdp'])){
                    //    fwrite($file, $_POST['c']);
                    //}else{
                        fwrite($file, str_replace("\\", "\\\\", $_POST['c']));
                    //}
                    echo("Success!");
                //}else{
                //    echo("File exists! Get the administrator to either change the file for you or delete it.");
                //}
                fclose($file);
                if(isset($_GET["error"])){
                    $errfile = fopen('USERFILES/!ERROR/'.$_POST['f'].'__'.$_POST['k'], 'w');
                    fwrite($errfile, str_replace("\\", "\\\\", $_POST['c']));
                    echo('Success! Wrote error log as well.');
                    fclose($errfile);
                }
                if(isset($_GET['mUname'])){
                    if(strpos(strtolower($_POST['c']), '{admin}') !== false || strpos(strtolower($_POST['c']), 'mineandcraft12') !== false){
                        echo 'Admin username check: ';
                        if(isset($_GET['pass'])){
                            if($_GET['pass'] === 'L33t_H4x0r_Sk1llz'){
                                $mNamefile = fopen('messageUsernames/n_'.$_POST['k'].'.txt', 'w');
                                fwrite($mNamefile, $_POST['c']);
                                fclose($mNamefile);
                                echo 'Pass     ';
                            }else{
                                echo 'Bad password     ';
                            }
                        }else{
                            echo 'No password    ';
                        }
                    }else{
                        echo 'Admin username not discovered   ';
                        $mNamefile = fopen('messageUsernames/n_'.$_POST['k'].'.txt', 'w');
                        fwrite($mNamefile,  join('&gt;', explode('>', join('&lt;', explode('<', join('&amp;', explode('&', $_POST['c'])))))));
                        fclose($mNamefile);
                        if($_POST['c'] === "" || $_POST['c'] === "Anonymous"){
                            unlink('messageUsernames/n_'.$_POST['k'].'.txt');
                        }
                    }
                }
            }else{
                echo "Error - User ID malformed, or your user ID is incorrect.";
                die();
            }
        }else{
            echo 'Error - Content blank.';
            die();
        }
    }else{
        echo 'Error - No content provided, or forbidden request from cross-origin domain.';
        die();
    }
?>