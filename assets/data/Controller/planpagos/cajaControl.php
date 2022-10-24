<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/cajaModel.php';
    $cajaM = new Caja();
    

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }
    $accion = isset($_POST["action"]) ? $_POST["action"] : 'no action';
    unset($_POST['action']);
    switch ($accion) {
        case 'registrar_a_caja':
            $_POST['usuario'] = $_SESSION['usuario']['idAcceso'];
            echo json_encode($cajaM->registrar_a_caja($_POST));
        break;
        case 'consultar_movimientos':
            echo json_encode($cajaM->consultar_movimientos());
        break;
        default:
            echo $accion;
        break;
    } 
}else {
    header('Location: ../../../../index.php');
}
