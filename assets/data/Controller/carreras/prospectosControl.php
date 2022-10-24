<?php 
	if (isset($_POST["action"]) && isset($_SESSION['usuario'])) {
		date_default_timezone_set("America/Mexico_City");
		require_once '../../Model/conexion/conexion.php';
		require_once '../../Model/carreras/carrerasModel.php';
		require_once '../../Model/carreras/prospectoCarreraModel.php';
		
		$carrM = new Carrera();
		$prospEM = new ProspectoCarrera();

		switch ($_POST["action"]) {
			default:
				echo json_encode(["estatus"=>"error","info"=>"noaction"]);
				break;
		}
	}else{
		header('Location: ../../../../index.php');
	}
?>
