<?php
    session_start();
    date_default_timezone_set("America/Mexico_City");
    if (isset($_POST["action"]) && (isset($_SESSION['usuario']) || isset($_POST['android_id_afiliado']))) {
        require_once '../../Model/conexion/conexion.php';
        require_once '../../Model/clinicas/clinicasModel.php';
        require_once '../../../../siscon/app/data/Model/documentosModel.php';

        $clinM = new Clinica();
        $docM = new documentos();
    
        switch ($_POST["action"]) {
            case 'consultar_validaciones';
                $valid = $clinM->listar_validaciones();
                foreach ($valid as $key => $var) {
                    $valid[$key]['docs'] = $docM->consultarDocumentosList($var['id_afiliado']);
                }
                echo json_encode($valid);
                break;
            default:
                echo json_encode(["estatus"=>"error","info"=>"noaction"]);
                break;
        }
    
        
    }else{
        header('Location: ../../../../index.php');
    }
?>    
