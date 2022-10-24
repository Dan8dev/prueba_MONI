<?php 
function sendPost($prospecto, $carrera){
	$req_uri = explode('/',$_SERVER['REQUEST_URI']);
    $req_uri = $req_uri[sizeof($req_uri)-1];
    $url = 'http://'.$_SERVER['SERVER_NAME'].''.$_SERVER['REQUEST_URI'];
    // $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].''.$_SERVER['REQUEST_URI'];
    $url = str_replace($req_uri, '', $url);
    $url.='../../functions/generar_pdf_plan.php';
    
    $fields = array(
       'prospecto' => $prospecto,
       'carrera' => $carrera
    );
    
    // build the urlencoded data
    $postvars = http_build_query($fields);
    
    // open connection
    $ch = curl_init();
    
    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    
    // execute post
    $result = curl_exec($ch);

    // close connection
    curl_close($ch);
    return $result;
}
 ?>