<?php
session_start();
if (isset($_POST["action"])) {
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/adminwebex/AdminWebex.php';
    $adminwebex = new AdminWebex();

    $accion=@$_POST["action"];

    switch ($accion) {
        case 'consultarAsistenciaEventos':
            unset($_POST['action']);
            $ceventos = $adminwebex->consultarAsistenciaEventos($_POST);
            $cantidadAsis = $adminwebex->asistenciasMinimas($_POST['Evento']);
            
            //var_dump($duracion[0]['duracion'], $duracion[0]['tipoDuracion']);

            $data = Array();
            while($dato=$ceventos->fetchObject()){
                
                if($dato->folio == "enviado"){
                    $mensaje = "<b>Certificado Enviado</b>";
                }

                //$tipoDuracion = $duracion[0]['tipoDuracion'];
                //$cantidadDuracion = $duracion[0]['duracion'];
                $asistenciasMin = $cantidadAsis[0]['cantidad_asis_min'];

                // switch($tipoDuracion) {

                //     case $tipoDuracion == 'h': 
                //         if(intval($cantidadDuracion < 24)){
                //             if(intval($dato->TotalAsistencias < 1)){
                //                 $mensaje = "<b>Sin asistencias necesarias</b>";
                //             }else if(intval($dato->TotalAsistencias >= 1)){
                //                 $mensaje = "<input type='checkbox' value='{$dato->id_asistente}' onClick = 'obtenerCertificados({$dato->id_asistente})'>";
                //             }
                //         }else if(intval($cantidadDuracion > 24) && intval($cantidadDuracion < 48)){
                //             if(intval($dato->TotalAsistencias < 2)){
                //                 $mensaje = "<b>Sin asistencias necesarias</b>";
                //             }else if(intval($dato->TotalAsistencias >= 2)){
                //                 $mensaje = "<input type='checkbox' value='{$dato->id_asistente}' onClick = 'obtenerCertificados({$dato->id_asistente})'>";
                //             }
                //         }
                //         break;

                //     case $tipoDuracion == 'd':
                //         if(intval($dato->TotalAsistencias < $cantidadDuracion)){
                //             $mensaje = "<b>Sin asistencias necesarias</b>";
                //         }else if(intval($dato->TotalAsistencias >= $cantidadDuracion)){
                //             $mensaje = "<input type='checkbox' value='{$dato->id_asistente}' onClick = 'obtenerCertificados({$dato->id_asistente})'>";
                //         }
                //         break;
                // }

                if(intval($dato->TotalAsistencias < $asistenciasMin)){
                    $mensaje = "<b>Sin asistencias necesarias</b>";
                }else if(intval($dato->TotalAsistencias >= $asistenciasMin)){
                    $mensaje = "<input type='checkbox' value='{$dato->id_asistente}' onClick = 'obtenerCertificados({$dato->id_asistente})'>";
                }
                
                $data[]=array(
                    0=> $dato->nombre,
                    1=> $dato->correo,
                    2=> $mensaje,
                    3=> "<input type='datetime-local' step='1' class='form-control' id='inputDateTime_{$dato->id_asistente}'><button id='btnAdd' class='btn btn-primary' onClick='agregarAsistencia({$dato->id_asistente})'>Registrar</button>"
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

        case 'agregarAsistencia':
            $resp = [];
            unset($_POST["action"]);
            //$_POST['fecha'] = date('Y-m-d H:i:s', strtotime($_POST['fecha'] . " " . $_POST['hora']));
            //unset($_POST['hora']);
            $ceventos = $adminwebex->agregarAsistencia($_POST);
                
            if($ceventos > 0){
                $resp = ['estatus'=>'ok','info'=>'Asistencia registrada correctamente'];
            }else{
                $resp = ['estatus'=>'error','info'=>'Error al registrar la asistencia'];
            }
                
            echo json_encode($resp);
            break;

        case 'listarsesiones':
            unset($_POST['action']);
            $csul = $adminwebex->listarsesiones();
            $data = Array();
            while($dato=$csul->fetchObject()){
                $infoProfesor = "<b>NOMBRE: {$dato->nombreM}</b> <br><b>CORREO:</b> {$dato->email}<br><b>TELÉFONO:</b> ";
                $data[]=array(
                    $dato->nombre_clase,
                    ($dato->id_clase === null)? 'Evento' : 'Clase',
                    $dato->titulo." [".$dato->fecha_hora_clase."]<br><i>".$dato->nombre_carrera."</i>",
                    $infoProfesor.$dato->telefono,
                    $dato->id_sesion,
                    $dato->contrasena_sesion,
                    '<a class="btn btn-primary" data-toggle="modal" data-target="#modal-editar-concepto" onclick="editarsesion('.$dato->id.')">Modificar</a> '
                );
            }
            $sesiones_eventos = $adminwebex->listarsesiones_Eventos();
            foreach($sesiones_eventos as $sesion){
                $data[] = [
                    $sesion['nombre_clase'],
                    ($sesion['id_clase'] === null)? 'Evento' : 'Clase',
                    $sesion['titulo']." [".$sesion['fechaE']."]",
                    "Evento",
                    $sesion['id_sesion'],
                    $sesion['contrasena_sesion'],
                    '<a class="btn btn-primary" data-toggle="modal" data-target="#modal-editar-concepto" onclick="editarsesion('.$sesion['id'].')">Modificar</a> '
                ];
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;
        case 'obtenersesion':
            unset($_POST['action']);
            $rspta=$adminwebex->obtenerSesion($_POST);
            $rspta['data']['video'] = isset($rspta['data']['video']) && $rspta['data']['video'] != '' ? urldecode($rspta['data']['video']) : '';
            echo json_encode($rspta['data']);
            break;
        case 'actualizarsesion':
            unset($_POST['action']);
            if(isset($_FILES['editar_foto_clase']) && $_FILES['editar_foto_clase']['name'] != ''){
                $nombre_archivo = $_FILES['editar_foto_clase']['name'];
                $tipo_archivo = $_FILES['editar_foto_clase']['type'];
                $tamano_archivo = $_FILES['editar_foto_clase']['size'];
                $tmp_archivo = $_FILES['editar_foto_clase']['tmp_name'];
                $archivador = '../../../files/clases/fotoClase/';
                $tipo_a = explode('.',$nombre_archivo);
                $nombre_base = time().'.'.$tipo_a[sizeof($tipo_a)-1];
                $nombre_archivo = $archivador.$nombre_base;
                if(move_uploaded_file($tmp_archivo,$nombre_archivo)){
                    $_POST['nombre_archivo'] = $nombre_base;
                }
            }
            $rspta=$adminwebex->actualizarSesion($_POST);
            echo $rspta['data'];
            break;
        case 'activarsesion':
            unset($_POST['action']);
            $rspta=$adminwebex->activarSesion($_POST);
            echo $rspta['data'];
            break;
        case 'desactivarsesion':
            unset($_POST['action']);
            $rspta=$adminwebex->desactivarSesion($_POST);
            echo $rspta['data'];
            break;
        case 'consultar_carreras':
            $carreras=$adminwebex->consultar_carreras();
            echo json_encode($carreras);
            break;
        case 'consultar_eventos':
            $eventos = $adminwebex->consultar_eventos();
            echo json_encode($eventos);
            break;
        case 'consultar_generaciones_carrera':
            $carreras=$adminwebex->consultar_generaciones_carrera($_POST['carrera']);
            echo json_encode($carreras);
            break;
        case 'consultar_clases_generaciones':
            $carreras=$adminwebex->consultar_clases_generaciones($_POST['generacion']);
            echo json_encode($carreras);
            break;
        case 'agregar_nueva_sesion':
            $resp = [];
            unset($_POST['action']);
            unset($_POST['select_carrera']);
            unset($_POST['select_generaciones']);
            $tipo = $_POST['sesion_type'];
            unset($_POST['sesion_type']);

            if($tipo == 'clase'){
                unset($_POST['select_evento']);
                $rspta=$adminwebex->registrar_sesion_webex($_POST);
                if($rspta > 0){
                    $resp = ['estatus'=>'ok','info'=>'Sesion registrada correctamente'];
                }else{
                    $resp = ['estatus'=>'error','info'=>'Error al registrar la sesion'];
                }
            }else if($tipo == 'evento'){
                unset($_POST['select_clases']);
                $rspta=$adminwebex->registrar_sesion_webex_evento($_POST);
                if($rspta > 0){
                    $resp = ['estatus'=>'ok','info'=>'Sesion registrada correctamente'];
                }else{
                    $resp = ['estatus'=>'error','info'=>'Error al registrar la sesion'];
                }
            }
            echo json_encode($resp);
            break;
        default:
            # code...
            break;
    }
}else {
    header('Location: ../../../../index.php');
}
