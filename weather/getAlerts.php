<?php
if(isset($_GET['city']) && isset($_GET['state']) && isset($_GET['dom'])){
    $city = $_GET['city'];
    $state = $_GET['state'];
    $dom = $_GET['dom'];
    /*
    $abbr = [
        'Alabama' => 'AL',
        'Alaska' => 'AK',
        'Arizona' => 'AZ',
        'Arkansas' => 'AR',
        'California' => 'CA',
        'Colorado' => 'CO',
        'Connecticut' => 'CT',
        'Delaware' => 'DE',
        'Florida' => 'FL',
        'Georgia' => 'GA',
        'Hawaii' => 'HI',
        'Idaho' => 'ID',
        'Illinois' => 'IL',
        'Indiana' => 'IN',
        'Iowa' => 'IA',
        'Kansas' => 'KS',
        'Kentucky' => 'KY',
        'Louisiana' => 'LA',
        'Maine' => 'ME',
        'Maryland' => 'MD',
        'Massachusetts' => 'MA',
        'Michigan' => 'MI',
        'Minnesota' => 'MN',
        'Mississippi' => 'MS',
        'Missouri' => 'MO',
        'Montana' => 'MT',
        'Nebraska' => 'NE',
        'Nevada' => 'NV',
        'New Hampshire' => 'NH',
        'New Jersey' => 'NJ',
        'New Mexico' => 'NM',
        'New York' => 'NY',
        'North Carolina' => 'NC',
        'North Dakota' => 'ND',
        'Ohio' => 'OH',
        'Oklahoma' => 'OK',
        'Oregon' => 'OR',
        'Pennsylvania' => 'PA',
        'Rhode Island' => 'RI',
        'South Carolina' => 'SC',
        'South Dakota' => 'SD',
        'Tennessee' => 'TN',
        'Texas' => 'TX',
        'Utah' => 'UT',
        'Vermont' => 'VT',
        'Virginia' => 'VA',
        'Washington' => 'WA',
        'West Virginia' => 'WV',
        'Wisconsin' => 'WI',
        'Wyoming' => 'WY',
        'District of Columbia' => 'DC'
    ];

    if(isset($abbr[$state])){
        $state = $abbr[$state];
    }
    */
    $prevtime = intval(file_get_contents('alertTimestamp.txt'));
    $currtime = round(microtime(true) * 1000);

    if($prevtime + 90000 < $currtime){
        file_put_contents('alertTimestamp.txt', $currtime);
        //Initialize cURL.
        $curl = curl_init();

        //Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($curl, CURLOPT_URL, 'https://api.weather.gov/alerts/active?status=actual');

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
        file_put_contents('alerts.json', $data);
    }else{
        $data = file_get_contents('alerts.json');
    }

    if($data){
        $jsondata = json_decode($data, true);
        
        $activeAlerts = array();

        if(isset($jsondata['features'])){
            foreach($jsondata['features'] as $feature){
                $addAlert = false;
                if(
                    strpos($feature['properties']['areaDesc'], $city) !== false &&
                    strpos($feature['properties']['areaDesc'], $state) !== false
                ){
                    $addAlert = true;
                }
                if(
                    strpos($feature['properties']['areaDesc'], $city) !== false &&
                    strpos($feature['properties']['areaDesc'], $state) !== false
                ){
                    $addAlert = true;
                }
                if($addAlert){
                    array_push($activeAlerts, $feature);
                }
            }
            echo json_encode($activeAlerts);
        }else{
            echo 'NO DATA';
        }
    }else{
        echo 'NO DATA';
    }
    
}
?>