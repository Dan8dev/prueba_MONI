<?php 
	date_default_timezone_set("America/Mexico_City");
	require 'mailer.php';
	
	$claves = ['%%tipoEvento','%%tituloEvento','%%prospecto', '%%CONTRASENIA', '%%USUARIO'];
	
	
	$destinos = [
			['MIKEL','mikelparlare@gmail.com','12345'],
			['JESUS','pajaro.octavio96@gmail.com','12345']
			];
	
	for($i = 0; $i < sizeof($destinos); $i++){
		$plantilla = file_get_contents("../../plantillas/carreras/nueva_plantilla_logoterapia.html");
		
		$valores = ['CERIFICACION','LOGOTERAPIA APLICADA',$destinos[$i][0], $destinos[$i][2], $destinos[$i][1]];
		for($j = 0; $j < sizeof($claves); $j++){
			$plantilla = str_replace($claves[$j], $valores[$j], $plantilla);    
		}
		
		print_r(sendEmailOwn([[ $destinos[$i][1],  $destinos[$i][0]]], 'Prueba de plantilla'.DATE('H:i').'.', $plantilla));
	}
	
?>