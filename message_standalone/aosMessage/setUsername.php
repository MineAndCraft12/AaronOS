<?php
if(!file_exists('passw/u_'.$_COOKIE['msgUser'].'.txt')){
    $userfile = fopen('users/u_'.$_COOKIE['msgUser'].'.txt', 'w');
    if(substr($_POST['u'], 0, 7) === "{ADMIN}"){
        $newUsername = '(nice try, hacker) '.$_POST['u'];
    }else{
        $newUsername = $_POST['u'];
    }
    fwrite($userfile, $newUsername);
    fclose($userfile);
    
    $passbc = password_hash($_POST['p'], PASSWORD_BCRYPT);
    $passfile = fopen('passw/u_'.$_COOKIE['msgUser'].'.txt', 'w');
    fwrite($passfile, $passbc);
    fclose($passfile);
}else{
    echo 'error - profile already exists';
}
?>