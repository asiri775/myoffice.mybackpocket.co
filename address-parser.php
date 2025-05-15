<?php
$addresses = [
    "2055 Stanley St, Montreal, Quebec H3A 1R7, Canada",
    "695 Manhattan Beach Boulevard, Manhattan Beach, CA, USA",
    "907 Queen St W,907 Queen St W,Toronto,",
    "179 McDermot Ave,179 McDermot Ave,Winnipeg,",
    "7404 King George Blvd ,Surrey,BC",
    "10271 Yonge St #302,,Richmond,ON",
    "1935 Calle Barcelona, #176, The Forum at La Costa, , Carlsbad ,CA",
    "6080 Center Drive, Howard Hughes Center, 6th Floor, ,Los Angeles ,CA",
    "6080 Center Drive, Howard Hughes Center, 6th Floor, ,Inglewood,CA",
    "3220-3250 Hamner Ave., X-Town and Country (right in front of the Post Office and DMV),,Norco,CA",
    "51 Rue Sherbrooke O,51 Rue Sherbrooke O,Montréal,",
    "406 Rue Notre-Dame Est,406 Rue Notre-Dame Est,Montréal,",
    "410 Rue Saint Nicolas ,,,Montréal,QC",
    ",Sacramento,California,United States",
    "1100 Glendon Ave,Los Angeles,CA"
];

foreach ($addresses as $addressData) {

    $address = $city = $state = $country = "";

    $addressData = explode(',', $addressData);
    $addressData = array_filter($addressData, function ($value) {
        return !empty(trim($value));
    });

    $addressData = array_values($addressData);

    if (count($addressData) >= 3) {

        $lastItem = trim(end($addressData));

        $secondLastIndex = count($addressData) - 2;
        $secondLast = $addressData[$secondLastIndex];

        if (strtolower($lastItem) == "canada" || strtolower($lastItem) == "ca") {
            $country = "ca";
        } elseif (strtolower($lastItem) == "united states") {
            $country = "us";
        } elseif (strlen($lastItem) === 3) {
            $country = $lastItem;
        } elseif (strlen($lastItem) === 2) {
            $state = $lastItem;
        } else {
            $state = $lastItem;
        }

        if ($state == null) {
            $state = $secondLast;
        }

        if(count($addressData) == 4){
            $city = trim($addressData[1]);
            $address = trim($addressData[0]);
        }

        if(trim($addressData[0]) === trim($addressData[1])){
            $address = trim($addressData[0]);
        }else{
            if(count($addressData) == 3){
                $city = trim($addressData[1]);
                $address = trim($addressData[0]);
            }elseif(count($addressData) == 5){
                $address = trim($addressData[0]). ", ".trim($addressData[1]). ", ".trim($addressData[2]);
            }
        }
        
        if(trim($city) === trim($state)){
            $city = "";
        }

        echo $address . "  -  " . $city . "  -  " . $state . "  -  " . $country . "    <<<<<<";

        print_r($addressData);

        echo '-------------';

    }

}