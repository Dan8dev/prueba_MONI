<?php 
header('Access-Control-Allow-Origin: *', false);
$log_hist = '';
if(file_exists('logs.txt')){
    $log_hist .= file_get_contents('logs.txt')."\n";
}
$log_hist .= date('Y-m-d H:i:s')." - ".json_encode($_POST)."\n";
file_put_contents('logs.txt', $log_hist);
/* 

*/
/* {
  "Nombres": "DESDE LANDING",
  "AP": "SISTEMAS",
  "AM": "TEST",
  "Celular": "2222090020",
  "Correo": "sistemas@mail.com",
  "Genero": "2",
  "IDPais": "37",
  "IDCarreraContacto": "5",
  "IDDifusion": "13"
} */
$ids = [
    "14" => 23,
    "4" =>  24,
    "3" =>  34,
    "7" =>  29,
    "27" => 6,
    "46" => 9,
    "5" =>  16,
    "6" =>  17,
    "12" => 18,
    "8" =>  13,
    "15" => 35,
    "16" => 36,
    "13" => 37
];

$continue = true;
if(trim($_POST['Nombres']) == '' || trim($_POST['Correo']) == '' || !in_array($_POST['IDCarreraContacto'], array_keys($ids))){
    $continue = false;
}

if($continue){
    $prospecto = [
        'name' => $_POST['Nombres'],
        'paterno' => $_POST['AP'],
        'materno' => $_POST['AM'],
        'email' => $_POST['Correo'],
        'telefono' => $_POST['Celular'],
        'tipo_moneda_prospecto' => 1,
        'tipo_prospecto' => 'carrera',
        'id_destino' => $ids[$_POST['IDCarreraContacto']],
        'genero' => $_POST['Genero'],
        'action'=> 'registrar_prospecto'
    ];
    
    // build the urlencoded data
    $postvars = http_build_query($prospecto);
    
    // open connection
    $ch = curl_init();
    
    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, 'https://moni.com.mx/assets/data/Controller/prospectos/prospectoControl.php');
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    
    // execute post
    $result = curl_exec($ch);

    // close connection
    curl_close($ch);
    echo json_encode($result);
}
?>