<?php
session_start();
if(!isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 3 )){
    header("Location: ../index.php");
    die();
}
include( "cx.php" );
$con = conect();
$validacion = ''; $comentario = ''; $separador = " ";

for( $i = 1; $i <= $_GET['t']; $i++){   
    if( isset( $_POST['validacion'.$i] ) ) $validacion = 'validacion = '.$_POST['validacion'.$i];
    if( isset( $_POST['comentario'.$i] ) ) $comentario = 'comentario = "'.$_POST['comentario'.$i].'"';
    if( $validacion != '' && $comentario != '' ) $separador = " , "; else $separador = " ";
    if( $validacion != '' || $comentario != '' ){
        $sql = "UPDATE documentos SET ".$validacion." $separador ".$comentario." , fecha_validacion = now() WHERE id=".$_POST['iddocumento'.$i];
        $con->query($sql);
        //echo $sql."<br>";
    }
    $validacion = ''; $comentario = ''; $separador = " ";
    
}//for

echo '<div id="notice" class="alert alert-info alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Sus cambios han sido guardados.</div>';
include( "listarExpediente.php" );

//header( "Location: index.php?e=1&p=vexp&id_prospectos=".$_GET['id_prospectos'] );
?>