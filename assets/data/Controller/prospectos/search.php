<?php
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/prospectos/prospectosModel.php';

    $prospectM = new Prospecto();
    if(isset($_GET['filt'])){
        if($_GET['filt'] == 'carreras'){
            require_once '../../Model/controlescolar/controlEscolarModel.php';
            $controlM = new ControlEscolar();
        }
    }

    $pal = strtoupper(str_replace(' ', '', $_GET['search']));
    if(strlen($pal) < 5 && (strlen($pal) % 2) == 1){
        die();
    }
    $afiliados = isset($_GET['datatable']);
    $results = $prospectM->busqueda_prospecto($pal, $afiliados);
    $new_re = [];

    $estatus_alumnos_validos = [1, 3, 4];
    foreach($results as $res){
        if(isset($_GET['filt'])){
            if($_GET['filt'] == 'carreras' && sizeof($_GET['data_filt']) > 0){
                $carrs_alumn = $controlM->consultar_generaciones_alumno_carreras($res['idAsistente'], $_GET['data_filt']);
                $add = false;
                if(sizeof($carrs_alumn) > 0){
                    foreach($carrs_alumn as $key_carr => $carrera){
                        if(in_array($carrera['estatus_alumno_carrera'], $estatus_alumnos_validos)){
                            if(isset($_GET['datatable'])){
                                $new_re[] = ['text'=>$res['aPaterno'].' '.$res['aMaterno'].' '.$res['nombre'], 'correo'=>$res['correo'], 'telefono'=>$res['telefono']];
                            }else{
                                $new_re[] = ['id'=>intval($res['idAsistente']), 'text'=>$res['aPaterno'].' '.$res['aMaterno'].' '.$res['nombre'].' ('.$res['correo'].' )'];
                            }
                        }
                    }
                }
            }else{
                if(isset($_GET['datatable'])){
                    $new_re[] = ['text'=>$res['aPaterno'].' '.$res['aMaterno'].' '.$res['nombre'], 'correo'=>$res['correo'], 'telefono'=>$res['telefono']];
                }else{
                    $new_re[] = ['id'=>intval($res['idAsistente']), 'text'=>$res['aPaterno'].' '.$res['aMaterno'].' '.$res['nombre'].' ('.$res['correo'].' )'];
                }
            }
        }else{
            if(isset($_GET['datatable'])){
                $new_re[] = ['text'=>$res['aPaterno'].' '.$res['aMaterno'].' '.$res['nombre'], 'correo'=>$res['correo'], 'telefono'=>$res['telefono']];
            }else{
                $new_re[] = ['id'=>intval($res['idAsistente']), 'text'=>$res['aPaterno'].' '.$res['aMaterno'].' '.$res['nombre'].' ('.$res['correo'].' )'];
            }
        }
    }
    $response = [
        "items" => $new_re
    ];
    echo json_encode($response);
?>