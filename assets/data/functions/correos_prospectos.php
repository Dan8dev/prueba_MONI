<?php 
header('Access-Control-Allow-Origin: *', false);
function enviar_correo_registro($asunto, $destinatarios, $plantilla, $claves, $valores, $adjunto = 'none'){
	require_once 'mailer.php';
	$resp = [];	
	/* if($_SERVER['SERVER_NAME'] == 'localhost'){
		$f_folder = "http://{$_SERVER['SERVER_NAME']}/moni/assets/plantillas/{$plantilla}";
	}else if($_SERVER['SERVER_NAME'] == 'sandbox.conacon.org'){
		$f_folder = "https://{$_SERVER['SERVER_NAME']}/assets/plantillas/{$plantilla}";
	}else if($_SERVER['SERVER_NAME'] == 'conacon.org'){
		$f_folder = "https://{$_SERVER['SERVER_NAME']}/moni/assets/plantillas/{$plantilla}";
	}else if($_SERVER['SERVER_NAME'] == 'moni.com.mx'){
		$f_folder = "https://{$_SERVER['SERVER_NAME']}/assets/plantillas/{$plantilla}";
	} */
	$f_folder = dirname(__FILE__)."/../../plantillas/".$plantilla;
	
	//file_put_contents('log.txt',  dirname(__FILE__));
	//file_put_contents('log2.txt', getcwd()); 
	$message = file_get_contents($f_folder);
	/*if(!$message){
		$message = file_get_contents("../../../plantillas/".$plantilla);
	}*/
	if(sizeof($claves) == sizeof($valores)){
		for($i = 0; $i < sizeof($claves); $i++){
		    $message = str_replace($claves[$i], $valores[$i], $message);    
		}
		if($asunto == 'Envío de accesos'){
			// file_put_contents("alumnos/".$destinatarios[0][0].".html", $message);
		}
		$resp = sendEmailOwn($destinatarios, $asunto, $message, "none", $adjunto);
	}else{
		$resp = ['1'=>'error_remplazando_contenido'];
	}

	return $resp;
}
?>
