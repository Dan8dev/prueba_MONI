<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 20){
    header("Location: ../index.php");
    die();
}

//include( "pm_utilidades.php" );
include( "cx.php" );
$cx = conect();

if( $_POST['tcancelacion'] != '' ){
    $_POST['comentarios'] = "[***MOTIVO DE CANCELACIÓN: ".$_POST['tcancelacion'].'***] '.$_POST['comentarios'];
    $consulta = "UPDATE pm_expedientes 
                SET
                factualizacion = now(), 
                estado = 8,  
                comentarios = '".$_POST['comentarios']."'  
                WHERE idexp = ".$_GET['idexp'];
}else {

    if( $_POST['estado'] == 4 )
        $tutor = "idtutor = 2, ";
    else $tutor = '';

        /*$consulta = "UPDATE pm_expedientes 
        SET
        factualizacion = now(), 
        estado = ".$_POST['estado'].", ".$tutor." 
        comentarios = '".$_POST['comentarios']."'
        WHERE idexp = ".$_GET['idexp'];*/
        $consulta = "UPDATE pm_expedientes SET factualizacion = now(), estado = ".$_POST['estado'].", comentarios = '".$_POST['comentarios']."' WHERE idexp = ".$_GET['idexp'];
    
}
//echo $consulta;

$resultado = $cx->query($consulta);

if( !$resultado ) header( "Location: index.php?e=0" );
else header( "Location: index.php?p=detallestm&e=1&idexp=".$_GET['idexp']."&tk=".$_GET['tk'] );
?>