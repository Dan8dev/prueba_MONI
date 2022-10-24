<?php 
	date_default_timezone_set("America/Mexico_City");
	require 'mailer.php';

	$claves = ['%%prospecto','%%USUARIO','%%CONTRASENIA'];


	$destinos = [
			['RAMIREZ AYALA GEORGINA MARÍA','gmra71@hotmail.com','abc']
			];

	for($i = 0; $i < sizeof($destinos); $i++){
		$plantilla = file_get_contents("../../plantillas/plantilla_accesos_admin.html");
		
		$valores = [$destinos[$i][0], $destinos[$i][1], $destinos[$i][2]];
		for($j = 0; $j < sizeof($claves); $j++){
			$plantilla = str_replace($claves[$j], $valores[$j], $plantilla);    
		}
		print_r([sendEmailOwn([[ $destinos[$i][1],  $destinos[$i][0]]], 'Envío de accesos', $plantilla), $destinos[$i][1]]);
	}
?>