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
$sql = "INSERT INTO cursos_examen 
(idCurso, Nombre, fechaInicio, fechaFin, idMaestro) VALUES (".$_POST['idCurso'].", '".$_POST['nombreExamen']."', '".$_POST['fechaInicio']."', '".$_POST['fechaFin']."', ".$_GET['idMaestro'].")";
$con->query($sql);
$idExamen = $con->insert_id;

for( $i = 1; $i <= 30; $i++ ){ //For 1

    if( $_POST["pregunta$i"] != '' ){
        //echo "Pregunta: ".$_POST["pregunta$i"]."<br>";

        for( $j = 0; $j <= 3; $j++ ){ //For 2
            if( $_POST["Opcion$i"] == $opciones[$j] ) $val = 1; else $val = 0; 
            $respuestas[ $_POST["TextoOpcion$i"."_".$opciones[$j] ] ] = $val ;
            //echo " TextoOpcion$i"."_".$opciones[$j]." Opción: ".$_POST["TextoOpcion$i"."_".$opciones[$j] ]."(".$val.") <br> ";
        }//Fin for 2
        
        //print_r( $respuestas );
        if ( count( $respuestas ) == 4 ){
            $json = json_encode( $respuestas );
            $json = str_replace('u00', '\u00', $json);
            $sql = "INSERT INTO cursos_examen_preguntas (idExamen, pregunta, opciones) VALUES ($idExamen, '".$_POST["pregunta$i"]."', '$json')";
            $con->query($sql);
        }
        unset($respuestas);

        //echo "<br><br>";

    }// Fin if

}//Fin for 1

header( "Location: index.php?e=1&p=aeo" );
?>