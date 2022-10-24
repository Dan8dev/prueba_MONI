<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/pagosModel.php';

    require_once '../../Model/planpagos/vistasModel.php';
    require_once '../../Model/planpagos/generacionesModel.php';

    $pagosM = new pagosModel();
    $generacionesM = new Generaciones();

    $vistasM = new Vistas();

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }
    $accion=@$_POST["action"];

    switch ($accion) {
        case 'totalalumnosgeneracion':
                $totalalumnosgeneracion=$pagosM->totalalumnosgeneracion(@$_POST['idGeneracion']);
                echo count($totalalumnosgeneracion['data']);
            break;
        case 'cargar_pagos_alumnos':
            unset($_POST['action']);
            $todo_pagos = $pagosM->cargar_pagos_alumnos();
            foreach($todo_pagos['data'] as $pago => $val){
                if($val['categoria'] == 'Mensualidad'){
                    if(intval($val['numero_de_pago']) - 1 >= 1){
                        $prev_fech = $pagosM->obtener_fecha_limite_pago_anterior($val['id_prospecto'], $val['id_concepto'], intval($val['numero_de_pago']) - 1, $val['id_pago']);
                        if($prev_fech && $prev_fech['fecha_limite_pago'] !== null && $prev_fech['fecha_limite_pago'] != ''){
                            $todo_pagos['data'][$pago]['fecha_limite_pago'] = $prev_fech['fecha_limite_pago'];
                        }
                    }else{
                        $generacion_info = $generacionesM->buscarGeneracion($val['id_generacion'])['data'];
                        $fecha_lim = $val['fecha_limite_pago'];
                        $fecha_lim = substr($generacion_info['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
                        if(strtotime($fecha_lim) < strtotime($generacion_info['fecha_inicio'])){
                            $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
                        }
                        $asign_gen = $generacionesM->buscarAsignacion($val['id_prospecto'], $generacion_info['idGeneracion']);
                        if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
                            if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
                                $fecha_lim = $asign_gen['data'][0]['fecha_primer_colegiatura'];
                            }
                        }
                        $todo_pagos['data'][$pago]['fecha_limite_pago'] = $fecha_lim;
                    }
                }
                $quien_registro = $pagosM->quien_registro($val['quien_registro'])['data'];
                $todo_pagos['data'][$pago]['nombre_callcenter']='';
                if (isset($quien_registro['idTipo_Persona'])==3) {
                    $info_p = $pagosM->nombre_marketing($quien_registro['idPersona']);
                    if($info_p['data']){
                        $todo_pagos['data'][$pago]['nombre_callcenter'] = $pagosM->nombre_marketing($quien_registro['idPersona'])['data']['nombres'];
                    }else{
                        $todo_pagos['data'][$pago]['nombre_callcenter'] = '';
                    }
                }
            }
            echo json_encode($todo_pagos);
            break;
        case 'cargar_pagos_reportados':
            unset($_POST['action']);
            $pagos_report = $pagosM->cargar_pagos_alumnos('pendiente');

            foreach($pagos_report['data'] as $pago => $val){
                if($val['categoria'] == 'Mensualidad'){
                    if(intval($val['numero_de_pago']) - 1 >= 1){
                        $prev_fech = $pagosM->obtener_fecha_limite_pago_anterior($val['id_prospecto'], $val['id_concepto'], intval($val['numero_de_pago']) - 1, $val['id_pago']);
                        if($prev_fech && $prev_fech['fecha_limite_pago'] !== null && $prev_fech['fecha_limite_pago'] != ''){
                            $pagos_report['data'][$pago]['fecha_limite_pago'] = $prev_fech['fecha_limite_pago'];
                        }
                    }else{
                        $generacion_info = $generacionesM->buscarGeneracion($val['id_generacion'])['data'];
                        $fecha_lim = $val['fecha_limite_pago'];
                        $fecha_lim = substr($generacion_info['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
                        if(strtotime($fecha_lim) < strtotime($generacion_info['fecha_inicio'])){
                            $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
                        }
                        $asign_gen = $generacionesM->buscarAsignacion($val['id_prospecto'], $generacion_info['idGeneracion']);
                        if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
                            if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
                                $fecha_lim = $asign_gen['data'][0]['fecha_primer_colegiatura'];
                            }
                        }
                        $pagos_report['data'][$pago]['fecha_limite_pago'] = $fecha_lim;
                    }
                }
                if (!file_exists('../../../files/comprobantes_pago/'.$val['comprobante'])) {
                    $pagos_report['data'][$pago]['comprobante'] = 'https://conacon.org/moni/assets/files/comprobantes_pago/'.$val['comprobante'];
                }
                else {
                    $pagos_report['data'][$pago]['comprobante'] = 'https://moni.com.mx/assets/files/comprobantes_pago/'.$val['comprobante'];
                }
                $quien_registro = $pagosM->quien_registro($val['quien_registro'])['data'];
                $pagos_report['data'][$pago]['nombre_callcenter']='';
                if (isset($quien_registro['idTipo_Persona'])==3) {
                    $info_p = $pagosM->nombre_marketing($quien_registro['idPersona']);
                    if($info_p['data']){
                        $pagos_report['data'][$pago]['nombre_callcenter'] = $pagosM->nombre_marketing($quien_registro['idPersona'])['data']['nombres'];
                    }else{
                        $pagos_report['data'][$pago]['nombre_callcenter'] = '';
                    }
                }
            }
            echo json_encode($pagos_report);
            break;
        case 'cargar_pagos_rechazados':
            unset($_POST['action']);
            $pagos_report = $pagosM->cargar_pagos_alumnos('rechazado');
            foreach($pagos_report['data'] as $pago => $val){
                if($val['categoria'] == 'Mensualidad'){
                    if(intval($val['numero_de_pago']) - 1 >= 1){
                        $prev_fech = $pagosM->obtener_fecha_limite_pago_anterior($val['id_prospecto'], $val['id_concepto'], intval($val['numero_de_pago']) - 1);
                        if($prev_fech && $prev_fech['fecha_limite_pago'] !== null && $prev_fech['fecha_limite_pago'] != ''){
                            $pagos_report['data'][$pago]['fecha_limite_pago'] = $prev_fech['fecha_limite_pago'];
                        }
                    }else{
                        $generacion_info = $generacionesM->buscarGeneracion($val['id_generacion'])['data'];
                        $fecha_lim = $val['fecha_limite_pago'];
                        $fecha_lim = substr($generacion_info['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
                        if(strtotime($fecha_lim) < strtotime($generacion_info['fecha_inicio'])){
                            $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
                        }
                        $asign_gen = $generacionesM->buscarAsignacion($val['id_prospecto'], $generacion_info['idGeneracion']);
                        if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
                            if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
                                $fecha_lim = $asign_gen['data'][0]['fecha_primer_colegiatura'];
                            }
                        }
                        $pagos_report['data'][$pago]['fecha_limite_pago'] = $fecha_lim;
                    }
                }
                $quien_registro = $pagosM->quien_registro($val['quien_registro'])['data'];
                $pagos_report['data'][$pago]['nombre_callcenter']='';
                if (isset($quien_registro['idTipo_Persona'])==3) {
                    $info_p = $pagosM->nombre_marketing($quien_registro['idPersona']);
                    if($info_p['data']){
                        $pagos_report['data'][$pago]['nombre_callcenter'] = $pagosM->nombre_marketing($quien_registro['idPersona'])['data']['nombres'];
                    }else{
                        $pagos_report['data'][$pago]['nombre_callcenter'] = '';
                    }
                }
            }
            echo json_encode($pagos_report);
            break;
        case 'cambiar_estatus_pago':
            unset($_POST['action']);
            $resp = [];
            $sesion = $_POST['sess'];
            if(isset($_POST['id_pago']) && isset($_POST['estatus'])){
                if($_POST['estatus'] == 'verificado'){
                    $info_pago = $pagosM->obtener_informacion_pago_id($_POST['id_pago']);
                    $val_num_g = 0;
                    $carr_id = 0;
                    if(intval($info_pago['data']['id_generacion']) > 0){
                        $carrinf = $pagosM->validar_carrera($info_pago['data']['id_generacion'])['data'];
                        $val_num_g = $carrinf['secuencia_generacion'];
                        $carr_id = $carrinf['idCarrera'];
                    }
                    // habilitar vistas alumno
                    $vistas_relacionadas = $vistasM->vistas_conceptos('categoria', $info_pago['data']['categoria'])['data'];
                    $vistas_relacionadas = array_merge($vistas_relacionadas, $vistasM->vistas_conceptos('concepto', $info_pago['data']['id_concepto'])['data']);
                    $vistasM->habilitar_vistas_afiliados($info_pago['data']['id_prospecto'], array_reduce($vistas_relacionadas, function($acc, $item){
                        $acc[] = $item['idVista'];
                        return $acc;
                    }, []));
                    // fin habilitar
                    $validarpagos = $pagosM->validar_pagos_alumno($info_pago['data']['id_prospecto']);#bievenida alumno tsu
                    
                    /**     VERIFICAR SI YA TIENE ASIGNADA LA GENERACION. SI NO ENVÍAR SU PLAN DE PAGOS ** */
                    // $validar_si_generacion_ya_esta_asignada = $pagosM->validar_si_generacion_ya_esta_asignada($info_pago['data']['id_prospecto'],$info_pago['data']['id_generacion'])['data'];
                    $asignar_generacion = false;
                    /* if(!$validar_si_generacion_ya_esta_asignada){
                        $asignar_generacion= $pagosM->asignar_generacion_alumno($info_pago['data']['id_prospecto'],$info_pago['data']['id_generacion'])['data'];
                        require_once 'sendPost.php';
                        sendPost($info_pago['data']['id_prospecto'], $generacionesM->buscarCarrerasG($info_pago['data']['id_generacion'])['data'][0]['idCarrera']);
                        $validar_si_generacion_ya_esta_asignada= $pagosM->validar_si_generacion_ya_esta_asignada($info_pago['data']['id_prospecto'],$info_pago['data']['id_generacion'])['data'];
                        // verificar si ya cuenta con perfil
                    } */

                    if (!($validarpagos['data']) && $_POST['estatus']=='verificado' && $info_pago['data']['id_generacion'] > 0 ) {
                        require_once '../../functions/correos_prospectos.php';
                        $obtenerdatosprospecto = $pagosM->obtener_datos_prospecto($info_pago['data']['id_prospecto']);
                        $destinatarios = [[$obtenerdatosprospecto['data']['correo'], $obtenerdatosprospecto['data']['nombre_completo']]];
                        
                        if($carr_id == 13){
                            $asunto = "El Departamento de Control Escolar le da la bienvenida";
                            // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
                            $plantilla_c = 'plantilla_tsu_control_escolar.html';
                            $claves = ['%%prospecto','%%secuencia_generacion'];
    
                            $valores = [$obtenerdatosprospecto['data']['nombre_completo'], $val_num_g];
                            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                            #bievenida alumno tsu
                        }
                        $asunto = "Envío de accesos";
                        $plantilla_c = 'carreras/nueva_plantilla_udc_accesos.html';
                        $claves = ['%%prospecto', '%%USUARIO', '%%CONTRASENIA'];
                        $contrasn = $pagosM->contrasenia_correo($obtenerdatosprospecto['data']['correo']);
                        $valores = [
                            $obtenerdatosprospecto['data']['nombre_completo'], 
                            $obtenerdatosprospecto['data']['correo'], 
                            $contrasn !== false ? $contrasn['contrasenia'] : '12345'];
                        
                        $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                    }
                    $pago = $pagosM->cambiar_estatus_pago($_POST['id_pago'], $_POST['estatus'], null, $sesion);
                }else{
                    $motivo = '';
                    if(isset($_POST['motivo'])){
                        $motivo = $_POST['motivo'];
                    }
                    $pago = $pagosM->cambiar_estatus_pago($_POST['id_pago'], $_POST['estatus'], $motivo, $sesion);
                }
            }else{
                $resp['estatus'] = 'error';
                $resp['info'] = 'No se recibieron los datos correctos';
            }
            echo json_encode($resp);
            break;
        case 'obtenerCarrerasGen':
            unset($_POST['action']);
            $carreras = $pagosM->obtenerCarrerasGen();
            echo json_encode($carreras['data']);
            break;
        case 'buscarGeneraciones':
            unset($_POST['action']);
            $generaciones = $pagosM->buscarGeneraciones($_POST['idCarrera']);
            echo json_encode($generaciones['data']);
            break;
        case 'obteneralumnosgeneracionreporte':
            unset($_POST['action']);
            $obPlan = $pagosM->obteneralumnosgeneracionreporte($_POST['idGeneracion']);
            $data = Array();
            while($dato= $obPlan->fetchObject()){
                $detalle_p = '';
                if($dato->referencia != null && $dato->referencia != ''){
                    $detalle_p .= '<b>Referencia:</b> ' . $dato->referencia . '<br>';
                }
                if($dato->codigo_de_autorizacion != null && $dato->codigo_de_autorizacion != ''){
                    $detalle_p .= '<b>Autorización:</b> ' . $dato->codigo_de_autorizacion . '<br>';
                }
                $data[] = array(
                    0=> $dato->nombre_alumno,
                    1=> $dato->concepto,
                    2=> $dato->fechapago,
                    3=> number_format($dato->montopagado),
                    4=> $dato->estatus,
                    5=> strpos($dato->detalle_pago,'conekta')?'CONEKTA':'CALLCENTER',
                    6=>$detalle_p,
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
        case 'obteneralumnosgeneracionnotificarpago':
            unset($_POST['action']);
            $obPlan = $pagosM->obteneralumnosgeneracionnotificarpago($_POST['idGeneracion']);
            $data = Array();
            while($dato= $obPlan->fetchObject()){
                $data[] = array(
                    0=> $dato->nombre_completo,
                    1=> $dato->correo,
                    2=> $dato->nombre_carrera,
                    3=> $dato->nombre_generacion,
                    4=> '<button class="btn btn-primary" onclick="pago_carrera('.$dato->idAsistente.','.$dato->idCarrera.',\''.$dato->nombre_completo.'\','.$dato->idInstitucion.')">Registrar Pago</button> '
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
        case 'buscar_alumno_generacion':
            unset($_POST['action']);
            $obPlan = $pagosM->buscar_alumno_generacion(str_replace(' ', '',$_POST['nombre']));
            $data = Array();
            while($dato= $obPlan->fetchObject()){
                $data[] = array(
                    0=> $dato->nombre_completo,
                    1=> $dato->correo,
                    2=> $dato->nombre_carrera,
                    3=> $dato->nombre_generacion,
                    4=> '<button class="btn btn-primary" onclick="pago_carrera('.$dato->idAsistente.','.$dato->idCarrera.',\''.$dato->nombre_completo.'\','.$dato->idInstitucion.')">Registrar Pago</button> '
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
        case 'modificar_pago':
            unset($_POST['action']);
            $validarfechalimitedepago= $pagosM->validarfechalimitedepago($_POST['id_pago_modificar']); // <- obtener por id
            $concepto = explode(' ',$_POST['enviarconcepto']);
            if ($concepto[0] == 'Mensualidad') {
                if(substr($validarfechalimitedepago['data']['fechapago'], 0, 10) != $_POST['nuevafechadepago']){
                    $nuevafechadepago = strtotime($_POST['nuevafechadepago']);
                    $fechalimitedepago = strtotime($validarfechalimitedepago['data']['fecha_limite_pago']);
                    if ($nuevafechadepago <= $fechalimitedepago) {
                        $fecha_lim_mod = $validarfechalimitedepago['data']['fecha_limite_pago'];
                        if($validarfechalimitedepago['data']['restante'] <= 0.5 && $validarfechalimitedepago['data']['saldo'] > 0){
                            $fecha_lim_mod = date('Y-m-d', strtotime($validarfechalimitedepago['data']['fecha_limite_pago']." + 1 month"));
                        }
                        $todo_pagos = $pagosM->modificar_pago($_POST['id_pago_modificar'],$_POST['nuevafechadepago'],$_POST['metodo_de_pago_1'],$_POST['modificarmedotodepago'],$_POST['modificarbancopago'],$_SESSION['usuario']['idPersona'], $fecha_lim_mod);
                        
                        /* $consultar_pendientes = $pagosM->obtener_pago_pendiente($validarfechalimitedepago['data']['id_prospecto'], $validarfechalimitedepago['data']['id_concepto']);
                        $num_p = false;
                        foreach($consultar_pendientes as $pendiente){
                            if($num_p === false){
                                $num_p = $pendiente['numero_de_pago'];
                            }
                            if($pendiente['numero_de_pago'] == $num_p){
                                if($pendiente['cargo_retardo'] > 0){
                                    $pago_restante = $pendiente['restante'] - $pendiente['cargo_retardo'];
                                    $upd_pago = $pagosM->actualizar_pagos_parciales_pendientes_mensualidades($pendiente['id_pago'], 0, $pago_restante, 0, 0);
                                }
                            }
                        } */
                        //$pagosM->actualizar_pagos_pendientes($_POST['id_pago_modificar']);
                    } else {
                        $saldo_pendiente=floatval(str_replace(['$',',',' '], '', $validarfechalimitedepago['data']['costototal']))*.15;
                        $saldo =$saldo_pendiente;
                        $saldo = $saldo - $validarfechalimitedepago['data']['cargo_retardo'];

                        $fecha_lim_mod = $validarfechalimitedepago['data']['fecha_limite_pago'];
                        $fecha_lim_mod = date('Y-m-d', strtotime($validarfechalimitedepago['data']['fecha_limite_pago']." - 1 month"));
                        
                        $todo_pagos = $pagosM->agregarsaldopendiente($_POST['id_pago_modificar'],$_POST['nuevafechadepago'],$saldo,$_SESSION['usuario']['idPersona'],$fecha_lim_mod);
    
                        /* $consultar_pendientes = $pagosM->obtener_pago_pendiente($validarfechalimitedepago['data']['id_prospecto'], $validarfechalimitedepago['data']['id_concepto']);
                        $num_p = false;
                        foreach($consultar_pendientes as $pendiente){
                            if($num_p === false){
                                $num_p = $pendiente['numero_de_pago'];
                            }
                            if($pendiente['numero_de_pago'] == $num_p){
                                $cargo_ret = $saldo_pendiente - $validarfechalimitedepago['data']['cargo_retardo'] - abs($pendiente['restante']);
                                if($cargo_ret < 0){
                                    $upd_pago = $pagosM->actualizar_pagos_parciales_pendientes_mensualidades($pendiente['id_pago'], 0, $cargo_ret, (abs($pendiente['restante'])+$cargo_ret), $pendiente['montopagado']);
                                }else{
                                    $upd_pago = $pagosM->actualizar_pagos_parciales_pendientes_mensualidades($pendiente['id_pago'], $cargo_ret, 0, (abs($pendiente['restante'])), $pendiente['montopagado']);
                                }
                            }
                        } */
                        //$pagosM->actualizar_pagos_pendientes($_POST['id_pago_modificar']);
                    }
                }else{
					$todo_pagos = $pagosM->modificar_pago_normal($_POST['id_pago_modificar'],$_POST['nuevafechadepago'],$_POST['metodo_de_pago_1'],$_POST['modificarmedotodepago'],$_POST['modificarbancopago'],$_SESSION['usuario']['idPersona']);
                    var_dump($todo_pagos);
                }
            }else {
                $todo_pagos = $pagosM->modificar_pago_normal($_POST['id_pago_modificar'],$_POST['nuevafechadepago'],$_POST['metodo_de_pago_1'],$_POST['modificarmedotodepago'],$_POST['modificarbancopago'],$_SESSION['usuario']['idPersona']);
            }
            echo json_encode($todo_pagos);
            break;
        case 'obtener_concentrado_alumnos':
            require_once '../../Model/controlescolar/controlEscolarModel.php';
            $genM = new ControlEscolar();
            $arr_alumns = [];
            $alumnos = $genM->volcar_alumnos(['vista'=>false, 'ars' => false]);
            foreach ($alumnos as $alumn_k => $alumn_v) {
                $pagos_apl = $pagosM->validar_pagos_alumno($alumn_v['idalumno']);
                if($pagos_apl['data']){
                    if(!in_array($alumn_v['idalumno'], array_keys($arr_alumns))){
                        $arr_alumns[$alumn_v['idalumno']] = $alumn_v;
                        $arr_alumns[$alumn_v['idalumno']]['generaciones_arr'][] = [$alumn_v['idgeneracion'],$alumn_v['nombre_generacion']];
                    }else{
                        $arr_alumns[$alumn_v['idalumno']]['generaciones_arr'][] = [$alumn_v['idgeneracion'],$alumn_v['nombre_generacion']];
                    }
                }
            }
            echo json_encode($arr_alumns);
            break;
        case 'detalles_pago':
            $info_pago = $pagosM->obtener_informacion_pago_id($_POST['id_pago']);
            if($info_pago['data']){
                $info_pago = $info_pago['data'];
                $info_pago['nombre_cobranza'] = $info_pago['quien_verifico'] != null ? $pagosM->nombre_cobranza_idAcceso($info_pago['quien_verifico'])['data']['nombres'] : null;

                if($info_pago['comprobante'] != ''){
                    if(file_exists('../../../files/comprobantes_pago/'.$info_pago['comprobante'])){
                        // echo ('../../../files/comprobantes_pago/'.$info_pago['comprobante']).'<br>';
                        $info_pago['enlace_comp'] = '../assets/files/comprobantes_pago/'.$info_pago['comprobante'];
                    }else{
                        if(@file_get_contents('https://conacon.org/moni/assets/files/comprobantes_pago/'.$info_pago['comprobante'])){
                            $info_pago['enlace_comp'] = 'https://conacon.org/moni/assets/files/comprobantes_pago/'.$info_pago['comprobante'];
                        }else{
                            $info_pago['enlace_comp'] = 'no-found';
                        }
                    }
                }
                $info_pago['nombre_callcenter']='';
                if($info_pago['quien_registro'] > 0){
                    $quien_registro = $pagosM->quien_registro($info_pago['quien_registro'])['data'];
                    if (isset($quien_registro['idTipo_Persona'])==3) {
                        $info_p = $pagosM->nombre_marketing($quien_registro['idPersona']);
                        if($info_p['data']){
                            $info_pago['nombre_callcenter'] = $pagosM->nombre_marketing($quien_registro['idPersona'])['data']['nombres'];
                        }else{
                            $info_pago['nombre_callcenter'] = '';
                        }
                    }
                }
                echo json_encode(['estatus'=>'ok', 'data'=>$info_pago]);
            }else{
                echo json_encode(['estatus'=>'error', 'info'=>'Pago no identificado']);
            }
            break;
        default:
            # code...
            break;
    }
}else {
    header('Location: ../../../../index.php');
}
