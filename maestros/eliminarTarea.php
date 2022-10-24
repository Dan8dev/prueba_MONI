<?php
session_start();
if(isset($_SESSION['usuario'])){
include( "listadoTareas.php" );
eliminarTarea( $_GET['idTarea'] );
}else{
    header('Location: index.php');
}
?>
