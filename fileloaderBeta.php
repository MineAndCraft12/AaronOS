<?php 
function find_all_files($dir) 
{ 
    $root = scandir($dir); 
    foreach($root as $value) 
    { 
        if($value === '.' || $value === '..') {
            continue;
        } 
        if(is_file("$dir/$value")) {
            $result[substr($value, 0, strrpos($value, '.'))] = file_get_contents("$dir/$value");
            continue;
        }
        
    }
    return $result;
}
if(isset($_COOKIE['keyword'])){
    if(is_dir('USERFILES/'.$_COOKIE['keyword'])){
        if(file_exists('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt')){
            if(isset($_COOKIE['password'])){
                $passwordFile = fopen('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt', 'r');
                $currPassword = fread($passwordFile, filesize('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt'));
                fclose($passwordFile);
                
                if(strlen($currPassword) === 64){
                    unlink('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt');
                }else{
                    if(strlen($currPassword) !== 60){
                        $passbc = password_hash($currPassword, PASSWORD_BCRYPT);
                        $passfile = fopen('USERFILES/'.$_COOKIE['keyword'].'/aOSpassword.txt', 'w');
                        fwrite($passfile, $passbc);
                        fclose($passfile);
                        $currPassword = $passbc;
                    }
                    
                    if(password_verify($_COOKIE['password'], $currPassword)){
                        $jsonResult = json_encode(find_all_files('USERFILES/'.$_COOKIE['keyword']));
                        $jsonResult = str_replace("/script", "\\/script", $jsonResult);
                        echo $jsonResult;
                    }else{
                        echo '{}';
                    }
                }
            }else{
                echo '{}';
            }
        }else{
            $jsonResult = json_encode(find_all_files('USERFILES/'.$_COOKIE['keyword']));
            $jsonResult = str_replace("/script", "\\/script", $jsonResult);
            echo $jsonResult;
        }
    }else{
        echo '{}';
    }
}else{
    echo '{}';
}
?>