<?php
session_start();
date_default_timezone_set("America/Mexico_City");
if (isset($_POST["action"])) {

    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/domiciliacionModel.php';


    $accion = $_POST["action"];

    $domM = new Domiciliar();

    switch ($accion) {
        case 'conultar_domiciliacion':
            $id_prospecto = false;
            if(isset($_SESSION['alumno']['id_prospecto'])){
                $id_prospecto = $_SESSION['alumno']['id_prospecto'];
            }else if(isset($_POST['prospecto'])){
                $id_prospecto = $_POST['prospecto'];
            }
            if($id_prospecto && isset($_POST['generacion'])){
                echo json_encode($domM->info_plan($id_prospecto, $_POST['generacion']));
            }else{
                echo json_encode(['estatus'=>'error', 'info'=>'faltan datos']);
            }
            break;
        default:
            echo 'no_action';
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}