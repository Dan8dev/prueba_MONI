<?php 
session_start();
if($_SESSION["usuario"]['idTipo_Persona'] == 3){
	require 'assets/data/Model/conexion/conexion.php';
	require 'assets/data/Model/marketing/marketingModel.php';
	$mktM = new Marketing();
	$mktM->cerrarSesion($_SESSION["usuario"]['idPersona']);
}
if(isset($_SESSION['alumno_udc'])){
    session_destroy();
    header('Location: udc/app/index.php');
}else{
session_destroy();

header("Location: ./");
}
?>
