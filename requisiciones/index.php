<?php

use FontLib\Table\Type\head;

session_start();

$idUs =  $_SESSION['usuario']['estatus_acceso'];
$idTP = $_SESSION['usuario']['idTipo_Persona'];

if(isset($_SESSION['usuario']) && $idUs == 2 && $idTP == 34){
    header('Location: ./ejecutivo.php');
}else if(isset($_SESSION['usuario']) && $idTP == 34){
    header('Location: ./contabilidad.php');
}else{
    header('Location: ../log-out.php');
}

?>