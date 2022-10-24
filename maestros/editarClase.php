<?php
session_start();
if(isset($_SESSION['usuario'])){
include( "listadoTareas.php" );
editarClase( $_GET['idClase'] );
}else{
    header('Location: index.php');
}
?>
