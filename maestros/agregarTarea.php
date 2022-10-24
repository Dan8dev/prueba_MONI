<?php
session_start();
if(isset($_SESSION['usuario'])){
include( "listadoTareas.php" );
agregarTarea( $_GET['idMaestro'] );
}else{
    header('Location: index.php');
}
?>
