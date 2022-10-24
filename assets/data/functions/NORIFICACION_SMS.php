<?php

/* 
*/
$detinos = [    
'3324885263',
'2227093940',
'2223589211',
'4411194131',
'9991713917',
'4412494091',
'4433546415',
'4412127607',
'4411158108',
'6681451008',
'6681179590',
'6683208718',
'8123834626',
'8118673026',
'8114652832',
'6682261671',
'3325198477',
'4611385552',
'6681427872',
//'8281481051',
'8119118147',
'8121603245',
'3121557920',
//'3316418565',
'8134207132',
'8111765690',
'3311188826',
'2223713348',
'2222090020'
];

$url = 'https://sigo.work/sandbox/bk/public/api/SMS';
$headers = [
    'Content-Type: application/json'
];


for($i = 0; $i < sizeof($detinos); $i++){
    $data = [
        "phone" => "+52".$detinos[$i],
        "mensaje" => utf8_decode("CONACON: \n Recuerda que hoy martes 12 de Abril a las 17 horas, se imparte la clase para regularizar OTA."),
        "titulo" => "CONACON"
    ];
    $encodedData = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

    // Execute post
    $result = curl_exec($ch);
    // var_dump($result);
    echo "<br>";
}


?>