<?php
    // this file picks a random line of code to show in the top of Messaging, for fun.
    $datestr = date("d");
    if(file_exists("lotd.txt")){
        $oldLine = json_decode(file_get_contents("lotd.txt"));
    }else{
        $oldLine = json_decode('["00", 0, "test line"]');
    }
    if($oldLine[0] == $datestr){
        echo json_encode(array($oldLine[1], $oldLine[2]));
    }else{
        $jsLines = file('scriptBeta.js');
        $selectedLine = array_rand($jsLines);
        while(strlen($jsLines[$selectedLine]) < 10){
            $selectedLine = array_rand($jsLines);
        }
        $newLine = array($datestr, $selectedLine + 1, substr($jsLines[$selectedLine], 0, strlen($jsLines[$selectedLine]) - 1));
        file_put_contents("lotd.txt", json_encode($newLine));
        echo json_encode(array($newLine[1], $newLine[2]));
    }
?>