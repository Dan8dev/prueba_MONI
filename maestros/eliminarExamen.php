<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 30){
    header("Location: ../index.php");
    die();
}
include( "cx.php" );

$con = conect();
$sql = "DELETE FROM cursos_examen WHERE idExamen = ".$_GET['idExamen'];
$con->query($sql);
$sql = "DELETE FROM cursos_examen_preguntas WHERE idExamen = ".$_GET['idExamen'];
$con->query($sql);

header( "Location: index.php?e=1&p=aeo" );
?>
