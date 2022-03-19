<?php
// messaging is disabled.
echo '';
die();

// send message to client
//if(isset($_COOKIE['keyword'])){
    $files = array_diff(scandir('USERFILES/!MESSAGE'), array('..', '.'));
    usort($files, 'strnatcmp');
    $dirsize = count($files) - 1;
    
    if(!isset($_GET['l'])){
        $last = $dirsize - 1;
    }else if($_GET['l'] === 'none'){
        $last = $dirsize - 3;
    }else{
        $last = $_GET['l'];
    }
    if($last < 0){
        $last = $dirsize + $last - 1;
    }
    if($last < -1){
        $last = -1;
    }
    if($last < $dirsize){
        $last++;
        echo file_get_contents('USERFILES/!MESSAGE/'.$files[$last]);
    }else{
        echo '';
    }
//}else{
//    echo '';
//}
?>