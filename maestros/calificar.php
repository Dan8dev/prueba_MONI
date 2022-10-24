<?php
session_start();
include( "listadoTareas.php" );
calificarTarea( $_GET['idEntrega'] );
?>