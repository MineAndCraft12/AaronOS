<?php
    function error($errno, $errstr){
        echo "Error - [" + $errno + '] ' + $errstr;
    }
    set_error_handler("error");
    if(strlen($_COOKIE['keyword']) == 21){
        if(!is_dir('USERFILES/'.$_COOKIE['keyword'])){
            mkdir('USERFILES/'.$_COOKIE['keyword']);
        }
        
        if(file_exists('messageUsernames/n_'.$_COOKIE['keyword'].'.txt')){
            if(strpos(file_get_contents('messageUsernames/n_'.$_COOKIE['keyword'].'.txt'), '{ADMIN}') !== 0){
                echo 'Error - You are not an admin.';
                die();
            }
        }else{
            echo 'Error - You are not an admin.';
            die();
        }
        
        if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')){
            if(strlen(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')) === 64){
                unlink('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt');
            }else{
                if(strlen(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')) !== 60){
                    $passbc = password_hash(file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt'), PASSWORD_BCRYPT);
                    $passfile = fopen('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt', 'w');
                    fwrite($passfile, $passbc);
                    fclose($passfile);
                }
            }
            if(isset($_COOKIE['password'])){
                if(!password_verify($_COOKIE['password'], file_get_contents('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt'))){
                    echo 'Error - Password incorrect.';
                    die();
                }
            }else{
                echo 'Error - Password not provided.';
                die();
            }
        }
    }else{
        echo "Error - User ID malformed, or your user ID is incorrect. ".$_COOKIE['keyword'];
        die();
    }
    
    
    if($_POST['action'] === 'getMessages'){
        $allMessages = scandir('USERFILES/!MESSAGE');
        $allMessages = array_filter($allMessages, function($item){
            return strpos($item, '.') !== 0;
        });
        
        usort($allMessages, 'strnatcmp');
        
        $messages = array();
        
        foreach($allMessages as $msg){
            $messages[$msg] = file_get_contents('USERFILES/!MESSAGE/'.$msg);
        }
        
        echo json_encode($messages);
    }else if($_POST['action'] === 'setContent'){
        if(strpos($_POST['target'], '/') !== FALSE || strpos($_POST['target'], '.') !== FALSE){
            echo 'Error: Invalid message name: '.$_POST['target'];
            die();
        }
        
        if(file_exists('USERFILES/!MESSAGE/'.$_POST['target'].'.txt')){
            $message = json_decode(file_get_contents('USERFILES/!MESSAGE/'.$_POST['target'].'.txt'));
            $message->c = $_POST['content'];
            file_put_contents('USERFILES/!MESSAGE/'.$_POST['target'].'.txt', json_encode($message));
            echo 'Success: '.($message->c);
        }else{
            echo 'Error: Message not found: '.$_POST['target'];
            die();
        }
    }else if($_POST['action'] === 'setAuthor'){
        if(strpos($_POST['target'], '/') !== FALSE || strpos($_POST['target'], '.') !== FALSE){
            echo 'Error: Invalid message name: '.$_POST['target'];
            die();
        }
        
        if(file_exists('USERFILES/!MESSAGE/'.$_POST['target'].'.txt')){
            $message = json_decode(file_get_contents('USERFILES/!MESSAGE/'.$_POST['target'].'.txt'));
            $message->n = $_POST['content'];
            file_put_contents('USERFILES/!MESSAGE/'.$_POST['target'].'.txt', json_encode($message));
            echo 'Success: '.($message->n);
        }else{
            echo 'Error: Message not found: '.$_POST['target'];
            die();
        }
    }else{
        echo 'Error: Invalid action: '.$_POST['action'];
    }
?>