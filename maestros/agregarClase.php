<?php
session_start();
if(isset($_SESSION['usuario'])){
include( "listadoTareas.php" );
agregarClase( $_GET['idMaestro'] );
}else{
    header('Location: index.php');
}
?>
