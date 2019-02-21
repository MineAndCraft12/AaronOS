<?php 
// dirToArray provided by SkyeEverest
function dirToArray($dir) { 
   
   $result = array(); 

   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[substr($value, 0, strrpos($value, '.'))] =  file_get_contents($dir . DIRECTORY_SEPARATOR . $value); 
         } 
      } 
   } 
   
   return $result; 
}

$freturn=dirToArray("message_standalone");
echo json_encode($freturn, JSON_PRETTY_PRINT);
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
                        $jsonResult = json_encode(dirToArray('USERFILES/'.$_COOKIE['keyword']));
                        if($jsonResult == "null"){
                            echo '{}';
                        }else{
                            $jsonResult = str_replace("/script", "\\/script", $jsonResult);
                            echo $jsonResult;
                        }
                    }else{
                        echo '{}';
                    }
                }
            }else{
                echo '{}';
            }
        }else{
            $jsonResult = json_encode(dirToArray('USERFILES/'.$_COOKIE['keyword']));
            if($jsonResult == "null"){
                echo '{}';
            }else{
                $jsonResult = str_replace("/script", "\\/script", $jsonResult);
                echo $jsonResult;
            }
        }
    }else{
        echo '{}';
    }
}else{
    echo '{}';
}
?>