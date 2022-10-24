<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/ProrrogasModel.php';
    $prorrogas = new Prorrogas();

    $accion=@$_POST["action"];

    switch ($accion) {
        case 'listar_prorrogas':
            unset($_POST['action']);
            $csulG = $prorrogas->listar_prorrogas();
            $data = Array();
            while($dato=$csulG->fetchObject()){
                $data[]=array(
                    0=> $dato->nombre_alumno,
                    1=> $dato->nombre_concepto,
                    2=> ($dato->estatus_prorroga=='pendiente')?'<span class="label label-warning">'.$dato->estatus_prorroga.'</span>':$dato->estatus_prorroga,
                    3=> $dato->fecha_solicitud,
                    4=>'<button class="btn btn-primary" data-toggle="modal" data-target="#modal_ver_prorroga" onclick="obtener_informacion_prorroga('.$dato->idProrroga.')">Ver prorroga</button> '
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;
        case 'obtener_informacion_prorroga':
            $infoprorroga=$prorrogas->obtener_informacion_prorroga($_POST['id_prorroga']);
            echo json_encode($infoprorroga['data']);
            break;
        case 'rechazar_prorroga':
            $rechazar_prorroga=$prorrogas->rechazar_prorroga($_POST['id_prorroga']);
            $idAsistente=$_POST['idAsistente'];
            $obtener_datos_alumno=$prorrogas->obtener_datos_alumno($idAsistente);
            $informacion_concepto=$prorrogas->obtener_informacion_prorroga($_POST['id_prorroga']);

            require_once '../../functions/correos_prospectos.php';
            $asunto = "CONACON NOTIFICACIÓN DE SOLICITUD DE PRORROGA";
            $destinatarios = [[$obtener_datos_alumno['data']['correo'], $obtener_datos_alumno['data']['nombre_completo']]];
            // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
            $plantilla_c = 'notificacion_de_solicitud_de_prorroga_no_aprobado.html';
            $claves = ['%%prospecto','%%nombre_concepto','%%fecha_limite'];
            $porciones = explode("-", $informacion_concepto['data']['nombre_concepto']);
            $porciones[0];
            $porciones[1];
            $valores = [$obtener_datos_alumno['data']['nombre_completo'], $porciones[0].' '.$informacion_concepto['data']['numero_de_pago'].' '.$porciones[1],$informacion_concepto['data']['fechalimitepago']];
            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
            
            echo json_encode($rechazar_prorroga['data']);
            break;
        case 'aprobar_prorroga':
            $aprobar_prorroga=$prorrogas->aprobar_prorroga($_POST['id_prorroga'],$_POST['nuevafechalimitedepago'],$_POST['idAsistente']);

            $idAsistente=$_POST['idAsistente'];
            $obtener_datos_alumno=$prorrogas->obtener_datos_alumno($idAsistente);
            $informacion_concepto=$prorrogas->obtener_informacion_prorroga($_POST['id_prorroga']);
            require_once '../../functions/correos_prospectos.php';
            $asunto = "CONACON NOTIFICACIÓN DE SOLICITUD DE PRORROGA";
            $destinatarios = [[$obtener_datos_alumno['data']['correo'], $obtener_datos_alumno['data']['nombre_completo']]];
            // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
            $plantilla_c = 'notificacion_de_solicitud_de_prorroga.html';
            $claves = ['%%prospecto','%%nombre_concepto','%%fecha_limite'];
            $porciones = explode("-", $informacion_concepto['data']['nombre_concepto']);
            $porciones[0];
            $porciones[1];
            $valores = [$obtener_datos_alumno['data']['nombre_completo'], $porciones[0].' '.$informacion_concepto['data']['numero_de_pago'].' '.$porciones[1],$_POST['nuevafechalimitedepago']];
            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");


            echo json_encode($aprobar_prorroga['data']);
            break;
        default:
            # code...
            break;
    }

}else {
    header('Location: ../../../../index.php');
}
