<?php
// send message to client
//if(isset($_COOKIE['keyword'])){
    $dirstuff = array_diff(scandir('USERFILES/!MESSAGE'), array('..', '.'));
    $dirsize = count($dirstuff) - 1;
    if($_GET['l'] === 'none'){
        $last = $dirsize - 3;
    }else{
        $last = $_GET['l'];
    }
    if($last  < 0){
        $last = $dirsize + $last - 1;
    }
    if($last < -1){
        $last = -1;
    }
    if($last < $dirsize){
        $last++;
        $file = fopen('USERFILES/!MESSAGE/m'.$last.'.txt', 'r');
        echo fread($file, filesize('USERFILES/!MESSAGE/m'.$last.'.txt'));
        fclose($file);
    }else{
        echo '';
    }
//}else{
//    echo '';
//}
?>