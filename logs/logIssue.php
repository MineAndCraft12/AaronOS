<?php
    $logInfo = json_encode($_POST);
    file_put_contents('errors/error_'.strval(time()).'.txt', $logInfo);
?>