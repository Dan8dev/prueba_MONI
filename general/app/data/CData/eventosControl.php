<?php
session_start();
if (isset($_POST["action"]) && (isset($_SESSION["alumno_iesm"]) || isset($_SESSION["alumno_udc"]) || isset($_SESSION["alumno_general"]))){    
    date_default_timezone_set("America/Mexico_City");
	require_once '../Model/conexion.php';
    require_once '../Model/EventosModel.php';

    $eventoM = new Eventos();

    switch($_POST['action']){
        case 'eventos_instituciones':
            unset($_POST['action']);
            $eventos = [];
            if(gettype($_POST['institucion']) == 'array'){
                foreach ($_POST['institucion'] as $instituciones => $institucion) {
                    $ev = $eventoM->consultarEvento_Institucion($institucion);
                    $eventos = array_merge($eventos, $ev['data']);
                }
            }else{
                $eventos = $ev['data'];
            }
            
            echo json_encode($eventos);
            break;
        case 'talleres_eventos':
            unset($_POST['action']);
            $resp = [];
            $resp = $eventoM->get_talleres_evento($_POST['evento']);
            
            echo json_encode($resp);
            break;
        case 'seleccion_talleres':
            unset($_POST['action']);
            $resp = [];
            $seleccion = $eventoM->get_talleres_prospecto($_SESSION['alumno_general']['id_prospecto'], $_POST['evento']);
            if(sizeof($seleccion['data']) > 0){
                $resp = ['estatus'=>'error','info'=>'Su seleccion de talleres ya ha sido recibida previamente.'];
            }else{
                if(isset($_SESSION['alumno_general'])){
                    foreach ($_POST as $key => $value) {
                        if(substr($key, 0, 6) == 'taller'){
                            $insert = [
                                'prospecto'=>$_SESSION['alumno_general']['id_prospecto'],
                                'taller'=>$value,
                                'fecha'=>date("Y-m-d H:i:s")
                            ];
                            $apartar = $eventoM->apartar_talleres($insert);
                            $resp = ['estatus'=>'ok'];
                            if($apartar['estatus'] == 'error'){
                                $resp = ['estatus'=>'error','info'=>'Ha ocurrido un error realizar el apartado de taller.', 'detalle'=>$apartar];
                            }
                        }
                    }
                }else{
                    $resp = ['estatus'=>'error','info'=>'Ha ocurrido un error al registrar el acceso. Verifique su sesión.'];
                }
            }
            echo json_encode($resp);
            break;
        case 'seleccion_talleres_prospecto':
            if(isset($_SESSION['alumno_general'])){
                $talleres = $eventoM->get_talleres_prospecto($_SESSION['alumno_general']['id_prospecto'], $_POST['evento']);
                $talleres['data'] = array_merge($talleres['data'], $eventoM->get_talleres_permitidos($_SESSION['alumno_general']['id_prospecto'], $_POST['evento']));
                
                foreach($talleres['data'] as $taller => $valor){
                    $excluidos = json_decode($valor['excluir'], true);
                    if($excluidos){
                        if(in_array($_SESSION['alumno_general']['id_prospecto'], $excluidos)){
                            unset($talleres['data'][$taller]);
                        }
                    }
                }
                $talleres['data'] = array_values($talleres['data']);
                echo json_encode($talleres);
            }else{
                echo json_encode(['estatus'=>'error','info'=>'Verifique su sesión']);
            }
            break;
        default:
                echo json_encode($_POST);
            break;
    }

}else{
    header("Location: ../../index.php");
}
