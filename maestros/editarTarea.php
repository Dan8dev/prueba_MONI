<?php
session_start();
if(isset($_SESSION['usuario'])){
include( "listadoTareas.php" );
editarTarea( $_GET['idMaestro'] );
}else{
    header('Location: index.php');
}
?>
