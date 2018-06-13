<?php
    if(isset($_POST['k']) && isset($_COOKIE['keyword']) && substr($_SERVER['HTTP_REFERER'], 0, 37) === 'https://aaron-os-mineandcraft12.c9.io'){
        if($_POST['k'] === $_COOKIE['keyword']){
            $onlineUsers = fopen('online.txt', 'r');
            if(filesize('online.txt') === 0){
                $onlineList = array();
            }else{
                $onlineList = explode("\n", fread($onlineUsers, filesize('online.txt')));
            }
            fclose($onlineUsers);
            $newList = array();
            $usernames = array();
            $userFound = 0;
            foreach($onlineList as $user){
                if(substr($user, 0, strpos($user, '=')) !== $_POST['k']){
                    if((int)substr($user, strpos($user, '=') + 1, strlen($user)) >= round(microtime(true) * 1000) - 120000){
                        array_push($newList, $user);
                        if(file_exists('messageUsernames/n_'.substr($user, 0, strpos($user, '=')).'.txt')){
                            $usernameFile = fopen('messageUsernames/n_'.substr($user, 0, strpos($user, '=')).'.txt', 'r');
                            $currUsername = fread($usernameFile, filesize('messageUsernames/n_'.substr($user, 0, strpos($user, '=')).'.txt'));
                            fclose($usernameFile);
                        }else{
                            $currUsername = 'Anonymous '.substr($user, 0, 4);
                        }
                        array_push($usernames, join('&lt;', explode("<", join('&gt;', explode('>', $currUsername)))));
                    }
                }else{
                    $userFound = 1;
                    array_push($newList, $_POST['k'].'='.round(microtime(true) * 1000));
                    if(file_exists('messageUsernames/n_'.substr($user, 0, strpos($user, '=')).'.txt')){
                        $usernameFile = fopen('messageUsernames/n_'.substr($user, 0, strpos($user, '=')).'.txt', 'r');
                        $currUsername = fread($usernameFile, filesize('messageUsernames/n_'.substr($user, 0, strpos($user, '=')).'.txt'));
                        fclose($usernameFile);
                    }else{
                        $currUsername = 'Anonymous '.substr($user, 0, 4);
                    }
                    array_push($usernames, join('&lt;', explode("<", join('&gt;', explode('>', $currUsername)))));
                }
            }
            if(!$userFound){
                array_push($newList, $_POST['k'].'='.round(microtime(true) * 1000));
                if(file_exists('messageUsernames/n_'.$_POST['k'].'.txt')){
                    $usernameFile = fopen('messageUsernames/n_'.$_POST['k'].'.txt', 'r');
                    $currUsername = fread($usernameFile, filesize('messageUsernames/n_'.$_POST['k'].'.txt'));
                    fclose($usernameFile);
                }else{
                    $currUsername = 'Anonymous '.substr($_POST['k'], 0, 4);
                }
                array_push($usernames, join('&lt;', explode("<", join('&gt;', explode('>', $currUsername)))));
            }
            unset($user);
            $onlineUsers = fopen('online.txt', 'w');
            fwrite($onlineUsers, join("\n", $newList));
            fclose($onlineUsers);
            echo sizeof($newList).'<br>'.join("<br>", $usernames);
        }
    }
?>