<?php
if(isset($_GET['dom']) && isset($_GET['target'])){
    $dom = $_GET['dom'];
    $target = $_GET['target'];

    //Initialize cURL.
    $curl = curl_init();

    //Set the URL that you want to GET by using the CURLOPT_URL option.
    curl_setopt($curl, CURLOPT_URL, $target);

    //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    // set the user agent
    curl_setopt($curl, CURLOPT_USERAGENT, 'AaronOS Weather App, from domain '.$dom.', developer contact mineandcraft12@gmail.com');

    //Execute the request.
    $data = curl_exec($curl);

    //Close the cURL handle.
    curl_close($curl);

    //Print the data out onto the page.
    echo $data;
}else{
    echo 'BAD PARAMS';
}
?>