<?php
    header('Access-Control-Allow-Origin', '*');
    $logInfo = json_encode($_POST);
    if(!is_dir('errors')){
        mkdir('errors');
    }
    file_put_contents('errors/error_'.strval(time()).'.txt', $logInfo);
?>