<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 30){
    header("Location: ../index.php");
    die();
}
include( "cx.php" );

$opciones = "ABCD";
$val = 0;

$con = conect();
$sql = "UPDATE cursos_examen SET idCurso=".$_POST['idCurso'].", Nombre='".$_POST['nombreExamen']."', fechaInicio='".$_POST['fechaInicio']."', fechaFin='".$_POST['fechaFin']."' WHERE idExamen = ".$_GET['idExamen'];
$con->query($sql);
$sql = "DELETE FROM cursos_examen_preguntas WHERE idExamen = ".$_GET['idExamen'];
$idExamen = $_GET['idExamen'];
$con->query($sql);

for( $i = 1; $i <= 30; $i++ ){ //For 1

    if( $_POST["pregunta$i"] != '' ){

        for( $j = 0; $j <= 3; $j++ ){ //For 2
            if( $_POST["Opcion$i"] == $opciones[$j] ) $val = 1; else $val = 0; 
            $clave = $_POST["TextoOpcion$i"."_".$opciones[$j] ];
            $respuestas[ $clave ] = $val ;
        }//Fin for 2
        
        if ( count( $respuestas ) == 4 ){
            $json = json_encode( $respuestas ); 
            $json = str_replace('u00', '\u00', $json);
            $sql = "INSERT INTO cursos_examen_preguntas (idExamen, pregunta, opciones) VALUES ($idExamen, '".$_POST["pregunta$i"]."', '$json')";
            $con->query($sql);
        }
        unset($respuestas);

    }// Fin if

}//Fin for 1

header( "Location: index.php?e=1&p=editarExamen&idExamen=".$idExamen );
?>
