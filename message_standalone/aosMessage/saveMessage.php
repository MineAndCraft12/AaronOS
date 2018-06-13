<?php
// recieve message from client
if(isset($_COOKIE['msgUser']) && isset($_POST['c']) && (substr($_SERVER['HTTP_REFERER'], 0, strlen($_SERVER['SERVER_NAME']) + 9) === 'https://'.$_SERVER['SERVER_NAME'].'/' || substr($_SERVER['HTTP_REFERER'], 0, strlen($_SERVER['SERVER_NAME']) + 8) === 'http://'.$_SERVER['SERVER_NAME'].'/')){
    if(file_exists('passw/u_'.$_COOKIE['msgUser'].'.txt')){
        if(isset($_COOKIE['msgPass'])){
            if(!password_verify($_COOKIE['msgPass'], file_get_contents('passw/u_'.$_COOKIE['msgUser'].'.txt'))){
                echo 'Error - Password incorrect.';
                die();
            }
        }else{
            echo 'Error - Password not provided.';
            die();
        }
    }
    
    // old message writer, insecure
    //fwrite($file, '{n:"'.$_GET['n'].'",c:"'.join('&gt;', explode('>', join('&lt;', explode('<',$_GET['c'])))).'",l:"'.$filenumber.'"}');
    $messageUsername = 'Anonymous '.$_COOKIE['msgUser'];
    //if(is_dir('USERFILES/'.$_COOKIE['keyword'])){
    //    if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/APP_MSG_CHATNAME.txt')){
    if(file_exists('users/u_'.$_COOKIE['msgUser'].'.txt')){
        $usernamefile = fopen('users/u_'.$_COOKIE['msgUser'].'.txt', 'r');
        $messageUsername = fread($usernamefile, filesize('users/u_'.$_COOKIE['msgUser'].'.txt'));
        fclose($usernamefile);
    }
    
    $outUsername = join('\'\'', explode('"', join('\\\\', explode('\\', join('&gt;', explode('>', join('&lt;', explode('<', $messageUsername))))))));
    $outMessage = join('&quot;', explode('"', join('\\\\', explode('\\', join('&gt;', explode('>', join('&lt;', explode('<', $_POST['c']))))))));
    $outTime = round(microtime(true) * 1000);
    
    $setting = fopen('messages.txt', 'r');
    $filenumber = strval(intval(fread($setting, filesize('messages.txt'))) + 1);
    fclose($setting);
    
    $lastJSON = file_get_contents('messages/m'.($filenumber - 1).'.txt');
    $lastMessage = json_decode($lastJSON);
    if($lastMessage->n == $outUsername && $lastMessage->c == $outMessage){
        echo 'Error - Message identical to previous message.';
        die();
    }
    
    $setting = fopen('messages.txt', 'w');
    fwrite($setting, $filenumber);
    fclose($setting);
    
    $file = fopen('messages/m'.$filenumber.'.txt', 'w');
    //$file = fopen('USERFILES/!MESSAGE/m'.date('d_m_Y_H_i_s_').$_GET['n'].'.txt', 'w');
    fwrite($file, '{"i":"'.$_COOKIE['msgUser'].'","n":"'.$outUsername.'","c":"'.$outMessage.'","t":"'.$outTime.'","l":"'.$filenumber.'"}');
    fclose($file);
}else{
    echo 'Error - No user specified, or you are not allowed to send messages from your domain ('.$_SERVER['HTTP_REFERER'].').';
}
?>