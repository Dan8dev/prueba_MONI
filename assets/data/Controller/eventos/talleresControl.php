<?php
session_start();
if (isset($_POST["action"])){    
    date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/eventos/eventosModel.php';
    require_once '../../Model/eventos/talleresModel.php';

    require_once '../../Model/prospectos/prospectosModel.php';
    require_once '../../Model/asistentes/asistentesModel.php';

    require_once '../../Model/alumnos/alumnosInstitucionesModel.php';

    $eventoM = new Evento();
    $tallerM = new Taller();

    $prospM = new Prospecto();
    $asistM = new Asistentes();

    $alumnoM = new AccesosAlumnosInstituciones();

    $fecha_hoy = date("Y-m-d");
    // $fecha_hoy = "2022-08-12";

    switch($_POST['action']){
        case 'consultar_alumno':
            unset($_POST['action']);
            $resp = [];
            /* 
            # 1) verificar que el input haya leido un json, si no, pone el valor del input dentro de llaves
            if(substr($_POST['jsonasistencia'], 0, 1) != '{'){
                $_POST['jsonasistencia'] = '{'.$_POST['jsonasistencia'].'}';
            }
            
            # 2) decodificar el json del input
            if(!json_decode($_POST['jsonasistencia'],true)){
                echo json_encode(['estatus'=>'error', 'info' => "Error al decodificar el QR"]);
                die();
            }

            $json_r = json_decode($_POST['jsonasistencia'],true);
            */
            $json_r['alumno'] = intval($_POST['jsonasistencia']);
            $resp['talleres'] = $prospM->asistente_talleres_reservados_evento($json_r['alumno'], $_POST['eventoid'])['data'];
            
            $info_ev = $eventoM->consultarEvento_Id($_POST['eventoid']);

            if(!$info_ev['data']){
                echo json_encode(['estatus'=>'error', 'info' => "No se pudo identificar el evento"]);
                die();
            }
            //
             $fecha_entrega = "";
             $tipo_asistencia = "";
            //

            $resp['persona'] = $prospM->consultar_info_prospecto_afiliado($json_r['alumno'])['data'];
            if(!$resp['persona']){
                echo json_encode(['estatus'=>'error', 'info' => "No se pudo identificar el asistente"]);
                die();
            }
            $resp['persona']['instituciones'] = $prospM->obtener_instituciones_afiliados($json_r['alumno'])['data'];
            $nom_p = $resp['persona']['nombre'].' '.$resp['persona']['aPaterno'].' '.$resp['persona']['aMaterno'];
            /*
             * Una vez entregadas las consultas de pagos a scae se deben remplazar los filtros de acceso por pagos
            */

            # VERIFICAR ACCESO A TALLERES Y ENVIO DE CERTIFICADOS
            if(isset($_POST['clave_taller'])){ // Se está registrando una asistencia a taller
                $resp['taller'] = $eventoM->consultar_taller_clave($_POST['clave_taller'])['data'];
                
                $resp['taller']['acceso'] = false;
                $resp['taller']['mensaje'] = '';
                 $fecha_entrega = substr($resp['taller']['fecha'], 0, 10);
                 $tipo_asistencia = "taller";
                # filtro 1 de acceso, selección de taller
                 
                if(array_search($resp['taller']['nombre'], array_column($resp['talleres'], 'nombre')) !== false){
                    $resp['taller']['acceso'] = true;
                }else{
                    $tipo_alumns_taller = json_decode($resp['taller']['tipos_permitidos'], true);
                    if($tipo_alumns_taller !== null){
                        foreach($resp['persona']['instituciones'] as $institucion => $inst){
                            if(in_array(intval($inst['id_institucion']), $tipo_alumns_taller)){
                                $resp['taller']['acceso'] = true;
                            }
                        }
                        
                        $incluso = json_decode($resp['taller']['incluir'], true);
                        if($incluso !== null){
                            if(in_array($json_r['alumno'], $incluso)){
                                $resp['taller']['acceso'] = true;
                            }
                        }
                        $excluir = json_decode($resp['taller']['excluir'], true);
                        if($excluir !== null){
                            $exluido = in_array($json_r['alumno'], $excluir);
                            if($exluido){
                                $resp['taller']['acceso'] = false;
                            }
                        }
                        if(!$resp['taller']['acceso']){
                            $resp['taller']['mensaje'] = "Su acceso a esta sala no está autorizado";
                        }
                    }else{
                        $resp['taller']['mensaje'] = 'Su acceso a esta sala no está autorizado.';
                    }
                }
                # filtro 2 de acceso verificar fecha de taller
                if(substr($resp['taller']['fecha'], 0, 10) != $fecha_hoy){
                    $resp['taller']['acceso'] = false;
                    $resp['taller']['mensaje'] = 'Hoy no es la fecha para este evento..';
                }
                # filtro 3 de acceso verificar pagos
                $pagado = ['acceso' => true, 'monto_cubierto' => "$ 1,200.00"];
                $resp['taller']['pagado'] = $pagado['monto_cubierto'];
                if(!$pagado['acceso']){
                    $resp['taller']['acceso'] = false;
                    $resp['taller']['mensaje'] = 'Su acceso a esta sala no está autorizado...';
                }

                if($resp['taller']['acceso']){
                    $resp['taller']['asistencia'] = false;
                    $resp['taller']['mensaje'] = 'Bienvenido';
                    #REGISTRAR ASISTENCIA
                        # VALIDAR ASISTENCIA EVENTO
                    $asistencias_taller = $asistM->consultar_asistencia_taller($json_r['alumno'], $resp['taller']['id_taller']);
                    if(sizeof($asistencias_taller['data']) == 0){
                        #registrar nueva asistencia
                        $resp['taller']['asistencia'] = true;
                    }else{
                        $fe_asist = strtotime(substr($asistencias_taller['data'][0]['hora'], 0, 10));
                        if($fe_asist < strtotime($fecha_hoy)){
                            #registrar nueva asistencia
                            $resp['taller']['asistencia'] = true;
                        }
                    }

                    if($resp['taller']['asistencia']){
                        $nombre_rec = "";
                        if(intval($resp['taller']['certificado']) == 1){
                            $nom_reco = $resp['taller']['nombre']."_".str_replace(' ', '_', $nom_p).'_'.$json_r['alumno'].'_'.$resp['taller']['id_taller'];
                            $nombre_rec = generar_constancia($resp['taller']['plantilla_constancia'], $nom_p, change_chars($nom_reco));
                        }

                        $data_ins = [
                            'nombre_reconocimiento' => $nombre_rec,
                            'id_asistente' => $json_r['alumno'],
                            'id_evento' => $_POST['eventoid'],
                            'id_taller' => $resp['taller']['id_taller'],
                            'modalidad' => 'PRESENCIAL',
                            'fecha' => $fecha_hoy.' '.date('H:i:s'),
                            'folio' => ($nombre_rec == '')? '':$json_r['alumno'].$resp['taller']['id_taller']];
                        $asistencia = $asistM->registrarasistencia($data_ins);
                        $resp['taller']['asistencia'] = $asistencia;

                        require_once '../../functions/correos_prospectos.php';
                        
                        $destinatarios = [[$resp['persona']['email'], $resp['persona']['nombre']]];
                        $claves = ['%%TIPO','%%PERSONA_INTERES','%%EVENTO'];
                        $valores = ['Taller',$resp['persona']['nombre'], $resp['taller']['nombre']];

                        //$enviar = ($nombre_rec != '')?enviar_correo_registro("Envío de constancia de asistencia", $destinatarios, 'nueva_plantilla_constancias.html', $claves, $valores, "../../../images/constancias/".$nombre_rec.'.pdf') : '';
                        //$resp['taller']['envio'] = $enviar;
                    }else{
                        // $resp['taller']['mensaje'] = 'Su acceso a esta sala no está autorizado';
                    }
                }
            }else{ // se está registrando una asistencia a evento
                $resp['evento']['acceso'] = false;
                $resp['evento']['asistencia'] = false;
                $fecha_entrega = $info_ev['data']['fechaLimite'];
                $tipo_asistencia = "evento";
                # filtro 1 de acceso evento verificar fecha de evento
                $finicio_e = strtotime($info_ev['data']['fechaE']);
                $ffin_e = strtotime(substr($info_ev['data']['fechaLimite'], 0, 10));
                if(strtotime($fecha_hoy) >= $finicio_e && strtotime($fecha_hoy) <= $ffin_e){
                    $resp['evento']['acceso'] = true;
                }else{
                    $resp['evento']['acceso'] = false;
                    $resp['evento']['mensaje'] = 'Hoy no es la fecha para este evento.';
                }
                # filtro 2 de acceso a evento verificar pagos
                
                 $id = $_POST['jsonasistencia'];
                $mail = $prospM->consultarEmail($id)['data'];
                //$mail = 'angelbonillabaez@gmail.com';
                //$pays = $alumnoM->ConsultarCorreo_pagosSCAE($mail)['data'];
                
                if($_POST['eventoid'] != 72){
                    // consultar si el evento es de paga, consultando si tiene un plan de pagos asignado
                    $pays = $eventoM->validar_evento_de_pago($_POST['eventoid']);
                    if(!$pays){ // si no tiene plan asignado el evento es gratuito
                        $pagado_ev = ['acceso' => true,'monto_cubierto'=> 0];
                    }else{ // si no, verificar el total de pagos aplicados
                        $pago = $eventoM->validar_pago_evento($_POST['eventoid'], $id);
                        if($pago){
                            if(floatval($pago['restante']) <= 1){
                                $pagado_ev = ['acceso' => true, 'monto_cubierto' => floatval($pago['costototal'])];
                            }else{
                                $pagado_ev = ['acceso' => false, 'monto_cubierto' => (floatval($pago['costototal']) - floatval($pago['restante']))];
                            }
                        }else{
                            $pagado_ev = ['acceso' => false, 'monto_cubierto' => 0];
                        }
                    }
                }else{
                	$pagado_ev = ['acceso' => true, 'monto_cubierto' => "$ 1,000.00"];
                }
               
                //$pagado_ev = ['acceso' => true, 'monto_cubierto' => "$ 1,000.00"];
                $resp['evento']['pagado'] = $pagado_ev['monto_cubierto'];
				
                if(!$pagado_ev['acceso']){
                    $resp['evento']['acceso'] = false;
                    $resp['evento']['mensaje'] = 'Su acceso a esta sala no está autorizado';
                }

                if($resp['evento']['acceso']){
                    $resp['evento']['mensaje'] = 'Bienvenido';
                    #REGISTRAR ASISTENCIA
                        # VALIDAR ASISTENCIA EVENTO
                    $asistencias_evento = $asistM->consultar_asistencia_evento($json_r['alumno'], $_POST['eventoid']);
                    if(sizeof($asistencias_evento['data']) == 0){
                        #registrar nueva asistencia
                        $resp['evento']['asistencia'] = true;
                    }else{
                        $fe_asist = strtotime(substr($asistencias_evento['data'][0]['hora'], 0, 10));
                        if($fe_asist < strtotime($fecha_hoy)){
                            #registrar nueva asistencia
                            $resp['evento']['asistencia'] = true;
                        }
                    }
                }

                if($resp['evento']['asistencia']){
                    $nombre_rec = "";
                    // echo ($info_ev['data']['plantilla_constancia'].'!='."'' AND ".$fecha_hoy." == ".substr($info_ev['data']['fechaLimite'], 0, 10));
                    // print_r($info_ev);
                    // die();
                    if($info_ev['data']['plantilla_constancia'] != '' && ($fecha_hoy == substr($info_ev['data']['fechaLimite'], 0, 10) || $_POST['eventoid'] == 75)){
                        $nom_reco = $info_ev['data']['titulo']."_".str_replace(' ', '_', $nom_p).'_'.$json_r['alumno'].'_'.$info_ev['data']['idEvento'];
                        $nombre_rec = generar_constancia($info_ev['data']['plantilla_constancia'], $nom_p, change_chars($nom_reco));
                    }

                    $data_ins = [
                        'nombre_reconocimiento' => $nombre_rec,
                        'id_asistente' => $json_r['alumno'],
                        'id_evento' => $_POST['eventoid'],
                        'id_taller' => null,
                        'modalidad' => 'PRESENCIAL',
                        'fecha' => $fecha_hoy.' '.date('H:i:s'),
                        'folio' => ($nombre_rec == '')? '':$json_r['alumno'].$info_ev['data']['idEvento']];
                    $asistencia = $asistM->registrarasistencia($data_ins);
                    $resp['evento']['asistencia'] = $asistencia;
                    require_once '../../functions/correos_prospectos.php';
                        
                    $destinatarios = [[$resp['persona']['email'], $resp['persona']['nombre']]];
                    $claves = ['%%TIPO','%%PERSONA_INTERES','%%EVENTO'];
                    $valores = [$info_ev['data']['tipo'], $resp['persona']['nombre'], $info_ev['data']['titulo']];

                    $enviar = ($nombre_rec != '')?enviar_correo_registro("Envío de constancia de asistencia", $destinatarios, 'nueva_plantilla_constancias.html', $claves, $valores, "../../../images/constancias/".$nombre_rec.'.pdf') : '';
                    $resp['evento']['envio'] = $enviar;
                }
            }

            echo json_encode($resp);
            break;
        case 'consultar_asistentes':
            $resp = [];
            if(isset($_POST['taller'])){
                $resp = $tallerM->consultar_asistentes_taller($_POST['taller']);
                if(sizeof($resp['data']) == 0){
                    $resp = $tallerM->contultar_asistentes_taller_v2($_POST['taller']); //v2
                }
            }else if(isset($_POST['general']) && $_POST['general'] && isset($_POST['evento'])){
                $resp = $tallerM->consultar_todo_asistentes_taller($_POST['evento']);
            }else{
                $resp = ['estatus'=>'error','info'=>'No se definió el taller a consultar'];
            }

            echo json_encode($resp);
            break;
        case 'consultar_talleres_evento':
            $resp = [];
            if(isset($_POST['evento'])){
                $resp = $tallerM->consultar_talleres_evento($_POST['evento']);
            }else{
                $resp = ['estatus'=>'error','info'=>'No se definió el evento a consultar'];
            }

            echo json_encode($resp);
            break;
	    case 'consultar_ponencias_evento':
	    $resp = [];
	    if(isset($_POST['evento'])){
		$resp = $tallerM->consultar_ponencias_evento($_POST['evento']);
	    }else{
		$resp = ['estatus'=>'error','info'=>'No se definió el evento a consultar'];
	    }

	    echo json_encode($resp);
	    break;
        case 'actualizar_asistencia':
            $resp = [];
            $info_t = $tallerM->consultar_info_taller($_POST['taller']);
            $info_asist = $tallerM->validar_asistencia_taller($_POST['alumno'], $_POST['taller']);
            $_POST['change'] = strtolower($_POST['change']) == 'true' ? true : false;
            if($_POST['change']){
                if(!$info_asist['data']){ // si no existe el registro se creara uno nuevo
                    $resp = $prospM->apartar_talleres(['prospecto'=>$_POST['alumno'], 'taller'=>$_POST['taller'], 'fecha'=>date('Y-m-d H:i:s')]);
                }else if($info_asist['data']['estatus'] == 2){
                    $resp = $tallerM->actualizar_asistencia($_POST['alumno'], $_POST['taller'], true);
                }
            }else{
                if($info_asist['data']['estatus'] == 1){
                    $resp = $tallerM->actualizar_asistencia($_POST['alumno'], $_POST['taller'], false);
                }
            }
            echo json_encode($resp);
            break;
        case 'actualizar_tipos_alumnos':
            $resp = [];
            $info_t = $tallerM->consultar_info_taller($_POST['taller']);
            $_POST['change'] = strtolower($_POST['change']) == 'true' ? true : false;
            $tipos_actuales = json_decode($info_t['data']['tipos_permitidos'], true);
            if(gettype($tipos_actuales) != 'array'){
                $tipos_actuales = [];
            }
            foreach($tipos_actuales as $key => $value){
                $tipos_actuales[$key] = intval($value);
            }

            if($_POST['change']){
                if(!in_array($_POST['tipo'], $tipos_actuales)){
                    $tipos_actuales[] = intval($_POST['tipo']);
                    $resp = $tallerM->actualizar_tipos_alumnos($_POST['taller'], json_encode($tipos_actuales));
                }
            }else{
                if(in_array($_POST['tipo'], $tipos_actuales)){
                    $tipos_actuales = array_diff($tipos_actuales, [$_POST['tipo']]);
                    $resp = $tallerM->actualizar_tipos_alumnos($_POST['taller'], json_encode(array_values($tipos_actuales)));
                }
            }
            echo json_encode($resp);
            break;
        case 'detalles_talleres_priv':
            $resp = [];
            $r_inc = [];
            $r_exc = [];
            if(isset($_POST['taller'])){
                $info_t = $tallerM->consultar_info_taller($_POST['taller']);
                $incluidos = json_decode($info_t['data']['incluir'], true);
                $excluidos = json_decode($info_t['data']['excluir'], true);
                $incluidos = (gettype($incluidos) != 'array')?[] : $incluidos;
                $excluidos = (gettype($excluidos) != 'array')?[] : $excluidos;
                foreach($incluidos as $key => $value){
                    $r_inc = array_merge($r_inc, $alumnoM->buscar_alumno('',$value)['data']);
                }
                foreach($excluidos as $key => $value){
                    $r_exc = array_merge($r_exc, $alumnoM->buscar_alumno('',$value)['data']);
                }
            }
            $resp = ['incluidos'=>$r_inc, 'excluidos'=>$r_exc];
            echo json_encode($resp);
            break;
        case 'agregar_a':
            $resp = [];
            if(isset($_POST['taller']) && isset($_POST['asistente'])){
                $info_t = $tallerM->consultar_info_taller($_POST['taller']);
                $incluidos = json_decode($info_t['data']['incluir'], true);
                $excluidos = json_decode($info_t['data']['excluir'], true);
                $incluidos = (gettype($incluidos) != 'array')?[] : $incluidos;
                $excluidos = (gettype($excluidos) != 'array')?[] : $excluidos;
                $_POST['lista'] = strtolower($_POST['lista']) == 'true' ? true : false;
                if($_POST['lista']){ // agregar a incluidos, quitar de excluidos
                    if(in_array($_POST['asistente'], $excluidos)){
                        $excluidos = array_diff($excluidos, [$_POST['asistente']]);
                    }
                    if(!in_array($_POST['asistente'], $incluidos)){
                        $incluidos[] = intval($_POST['asistente']);
                    }
                }else{ // agregar a excluidos, quitar de incluidos
                    if(in_array($_POST['asistente'], $incluidos)){
                        $incluidos = array_diff($incluidos, [$_POST['asistente']]);
                    }
                    if(!in_array($_POST['asistente'], $excluidos)){
                        $excluidos[] = intval($_POST['asistente']);
                    }
                }
                $resp = $tallerM->actualizar_incluidos_excluidos($_POST['taller'], json_encode(array_values($incluidos)), json_encode(array_values($excluidos)));
            }
            echo json_encode($resp);
            break;
        case 'taller_control':
            $resp = [];
            if(trim($_POST['inp_hora_tall']) != ''){
                $_POST['inp_fecha_e'].=' '.$_POST['inp_hora_tall'];
            }
            unset($_POST['inp_hora_tall']);

            if(isset($_POST['inp_id_taller']) && intval($_POST['inp_id_taller']) > 0){ // editar taller
                // "select_evento_t"
                // "inp_nombre_t"
                // "select_tipo_t"
                // "inp_fecha_e"
                // "select_ciertifica_t"
                // "imagen_cert_t"
                // "inp_costo_t"
                // "select_tipo_pago_t"
                // "inp_cupo_limite"
                // "inp_nombre_salon"
                // $_POST['inp_costo_t'] = substr($_POST['inp_costo_t'], strpos($_POST['inp_costo_t'], "(")-1);
                // var_dump(strpos($_POST['inp_costo_t'], "("));
                if(floatval(str_replace(["$",","," "],"",$_POST["inp_costo_t"])) > 0){

                    if($_POST['select_tipo_pago_t'] == 'mxn'){
                        $_POST['inp_costo_t'] = $_POST['inp_costo_t'] .' (pesos)';
                    }else{
                        $_POST['inp_costo_t'] = $_POST['inp_costo_t'] .' (us dls)';
                    }
                }else{
                    $_POST['inp_costo_t'] = 0;
                }
                unset($_POST['action']);
                unset($_POST['select_tipo_pago_t']);
                $resp = $tallerM->editar_taller($_POST);
                
            }else{ // insertar
                $clave_t = $_POST['inp_nombre_t'];
                $parts = explode(' ', $clave_t);
                $clave_fin = [];
                foreach($parts as $part){
                    if(strlen($part) > 3){
                        $aux_part = $part;
                        $aux_part = str_replace(['á','Á'], 'a', $aux_part);
                        $aux_part = str_replace(['é','É'], 'e', $aux_part);
                        $aux_part = str_replace(['í','Í'], 'i', $aux_part);
                        $aux_part = str_replace(['ó','Ó'], 'o', $aux_part);
                        $aux_part = str_replace(['ú','Ú'], 'u', $aux_part);
                        array_push($clave_fin, strtolower($aux_part));
                    }
                }
                $str_clave = implode('-',$clave_fin);
                $existe_clave = $tallerM->consultar_taller_clave($str_clave);
                if($existe_clave){
                    $clave_distinta = false;
                    $i = 1;
                    while (!$clave_distinta){
                        $tmp_cl = $str_clave.'-'.$i;
                        if(!$tallerM->consultar_taller_clave($tmp_cl)){
                            $clave_distinta = true;
                            $str_clave = $tmp_cl;
                        }
                    }
                }
                $_POST['clave'] = $str_clave;
                if($_POST['select_tipo_pago_t'] == 'mxn'){
                    $_POST['inp_costo_t'] = $_POST['inp_costo_t'] .' (pesos)';
                }else{
                    $_POST['inp_costo_t'] = $_POST['inp_costo_t'] .' (us dls)';
                }
                unset($_POST['action']);
                unset($_POST['select_tipo_pago_t']);
                unset($_POST['inp_id_taller']);
                /**
                 * Reservar este espacio para la imagen
                 */
                $resp = $tallerM->insertar_taller($_POST);
            }
            echo json_encode($resp);
            break;
             case 'ponencia_control':
            $resp = [];
            if(trim($_POST['inp_hora_pon']) != ''){
                $_POST['inp_fecha_e'].=' '.$_POST['inp_hora_pon'];
            }
            unset($_POST['inp_hora_pon']);

            if(isset($_POST['inp_id_ponencia']) && intval($_POST['inp_id_ponencia']) > 0){ // editar taller
                // "select_evento_t"
                // "inp_nombre_t"
                // "select_tipo_t"
                // "inp_fecha_e"
                // "select_ciertifica_t"
                // "imagen_cert_t"
                // "inp_costo_t"
                // "select_tipo_pago_t"
                // "inp_cupo_limite"
                // "inp_nombre_salon"
                // $_POST['inp_costo_t'] = substr($_POST['inp_costo_t'], strpos($_POST['inp_costo_t'], "(")-1);
                // var_dump(strpos($_POST['inp_costo_t'], "("));
                if(floatval(str_replace(["$",","," "],"",$_POST["inp_costo_po"])) > 0){

                    if($_POST['select_tipo_pago_po'] == 'mxn'){
                        $_POST['inp_costo_po'] = $_POST['inp_costo_po'] .' (pesos)';
                    }else{
                        $_POST['inp_costo_po'] = $_POST['inp_costo_po'] .' (us dls)';
                    }
                }else{
                    $_POST['inp_costo_po'] = 0;
                }
                unset($_POST['action']);
                unset($_POST['select_tipo_pago_po']);
                $resp = $tallerM->editar_ponencia($_POST);
                
            }else{ // insertar
                $clave_t = $_POST['inp_nombre_po'];
                $parts = explode(' ', $clave_t);
                $clave_fin = [];
                foreach($parts as $part){
                    if(strlen($part) > 3){
                        $aux_part = $part;
                        $aux_part = str_replace(['á','Á'], 'a', $aux_part);
                        $aux_part = str_replace(['é','É'], 'e', $aux_part);
                        $aux_part = str_replace(['í','Í'], 'i', $aux_part);
                        $aux_part = str_replace(['ó','Ó'], 'o', $aux_part);
                        $aux_part = str_replace(['ú','Ú'], 'u', $aux_part);
                        array_push($clave_fin, strtolower($aux_part));
                    }
                }
                $str_clave = implode('-',$clave_fin);
                $existe_clave = $tallerM->consultar_taller_clave($str_clave);
                if($existe_clave){
                    $clave_distinta = false;
                    $i = 1;
                    while (!$clave_distinta){
                        $tmp_cl = $str_clave.'-'.$i;
                        if(!$tallerM->consultar_taller_clave($tmp_cl)){
                            $clave_distinta = true;
                            $str_clave = $tmp_cl;
                        }
                    }
                }
                $_POST['clave'] = $str_clave;
                // if($_POST['select_tipo_pago_t'] == 'mxn'){
                //     $_POST['inp_costo_t'] = $_POST['inp_costo_t'] .' (pesos)';
                // }else{
                //     $_POST['inp_costo_t'] = $_POST['inp_costo_t'] .' (us dls)';
                // }
                unset($_POST['action']);
                //unset($_POST['select_tipo_pago_t']);
                //unset($_POST['inp_id_taller']);
                /**
                 * Reservar este espacio para la imagen
                 */
                $resp = $tallerM->insertar_ponencia($_POST);
            }
            echo json_encode($resp);
        break;
        case 'info_taller':
                echo json_encode($tallerM->consultar_info_taller($_POST['id_taller']));
            break;
              case 'info_ponencia':
                echo json_encode($tallerM->consultar_info_ponencia($_POST['id_taller']));
            break;

        case 'check-congreso':
                unset($_POST['action']);
                $resp = [];
                /* 
                # 1) verificar que el input haya leido un json, si no, pone el valor del input dentro de llaves
                if(substr($_POST['jsonasistencia'], 0, 1) != '{'){
                    $_POST['jsonasistencia'] = '{'.$_POST['jsonasistencia'].'}';
                }
                
                # 2) decodificar el json del input
                if(!json_decode($_POST['jsonasistencia'],true)){
                    echo json_encode(['estatus'=>'error', 'info' => "Error al decodificar el QR"]);
                    die();
                }
    
                $json_r = json_decode($_POST['jsonasistencia'],true);
                */
                $json_r['alumno'] = intval($_POST['jsonasistencia']);
                $resp['talleres'] = $prospM->asistente_talleres_reservados_evento($json_r['alumno'], $_POST['eventoid'])['data'];
                
                $info_ev = $eventoM->consultarEvento_Id($_POST['eventoid']);
    
                if(!$info_ev['data']){
                    echo json_encode(['estatus'=>'error', 'info' => "No se pudo identificar el evento"]);
                    die();
                }
                //
                 $fecha_entrega = "";
                 $tipo_asistencia = "";
                //
    
                $resp['persona'] = $prospM->consultar_info_prospecto_afiliado($json_r['alumno'])['data'];
                if(!$resp['persona']){
                    echo json_encode(['estatus'=>'error', 'info' => "No se pudo identificar el asistente"]);
                    die();
                }
                $resp['persona']['instituciones'] = $prospM->obtener_instituciones_afiliados($json_r['alumno'])['data'];
                $nom_p = $resp['persona']['nombre'].' '.$resp['persona']['aPaterno'].' '.$resp['persona']['aMaterno'];
                /*
                 * Una vez entregadas las consultas de pagos a scae se deben remplazar los filtros de acceso por pagos
                */
    
                # VERIFICAR ACCESO A TALLERES Y ENVIO DE CERTIFICADOS
                if(isset($_POST['clave_taller'])){ // Se está registrando una asistencia a taller
                    $resp['taller'] = $eventoM->consultar_taller_clave($_POST['clave_taller'])['data'];
                    
                    $resp['taller']['acceso'] = false;
                    $resp['taller']['mensaje'] = '';
                     $fecha_entrega = substr($resp['taller']['fecha'], 0, 10);
                     $tipo_asistencia = "taller";
                    # filtro 1 de acceso, selección de taller
                     
                    if(array_search($resp['taller']['nombre'], array_column($resp['talleres'], 'nombre')) !== false){
                        $resp['taller']['acceso'] = true;
                    }else{
                        $tipo_alumns_taller = json_decode($resp['taller']['tipos_permitidos'], true);
                        if($tipo_alumns_taller !== null){
                            foreach($resp['persona']['instituciones'] as $institucion => $inst){
                                if(in_array(intval($inst['id_institucion']), $tipo_alumns_taller)){
                                    $resp['taller']['acceso'] = true;
                                }
                            }
                            
                            $incluso = json_decode($resp['taller']['incluir'], true);
                            if($incluso !== null){
                                if(in_array($json_r['alumno'], $incluso)){
                                    $resp['taller']['acceso'] = true;
                                }
                            }
                            $excluir = json_decode($resp['taller']['excluir'], true);
                            if($excluir !== null){
                                $exluido = in_array($json_r['alumno'], $excluir);
                                if($exluido){
                                    $resp['taller']['acceso'] = false;
                                }
                            }
                            if(!$resp['taller']['acceso']){
                                $resp['taller']['mensaje'] = "Su acceso a esta sala no está autorizado";
                            }
                        }else{
                            $resp['taller']['mensaje'] = 'Su acceso a esta sala no está autorizado.';
                        }
                    }
                    # filtro 2 de acceso verificar fecha de taller
                    if(substr($resp['taller']['fecha'], 0, 10) != $fecha_hoy){
                        $resp['taller']['acceso'] = false;
                        $resp['taller']['mensaje'] = 'Hoy no es la fecha para este evento..';
                    }
                    # filtro 3 de acceso verificar pagos
                    $pagado = ['acceso' => true, 'monto_cubierto' => "$ 1,200.00"];
                    $resp['taller']['pagado'] = $pagado['monto_cubierto'];
                    if(!$pagado['acceso']){
                        $resp['taller']['acceso'] = false;
                        $resp['taller']['mensaje'] = 'Su acceso a esta sala no está autorizado...';
                    }
    
                    if($resp['taller']['acceso']){
                        $resp['taller']['asistencia'] = false;
                        $resp['taller']['mensaje'] = 'Bienvenido';
                        #REGISTRAR ASISTENCIA
                            # VALIDAR ASISTENCIA EVENTO
                        $asistencias_taller = $asistM->consultar_asistencia_taller($json_r['alumno'], $resp['taller']['id_taller']);
                        if(sizeof($asistencias_taller['data']) == 0){
                            #registrar nueva asistencia
                            $resp['taller']['asistencia'] = true;
                        }else{
                            $fe_asist = strtotime(substr($asistencias_taller['data'][0]['hora'], 0, 10));
                            if($fe_asist < strtotime($fecha_hoy)){
                                #registrar nueva asistencia
                                $resp['taller']['asistencia'] = true;
                            }
                        }
    
                        if($resp['taller']['asistencia']){
                            $nombre_rec = "";
                            if(intval($resp['taller']['certificado']) == 1){
                                $nom_reco = $resp['taller']['nombre']."_".str_replace(' ', '_', $nom_p).'_'.$json_r['alumno'].'_'.$resp['taller']['id_taller'];
                                $nombre_rec = generar_constancia($resp['taller']['plantilla_constancia'], $nom_p, change_chars($nom_reco));
                            }
    
                            $data_ins = [
                                'nombre_reconocimiento' => $nombre_rec,
                                'id_asistente' => $json_r['alumno'],
                                'id_evento' => $_POST['eventoid'],
                                'id_taller' => $resp['taller']['id_taller'],
                                'modalidad' => 'PRESENCIAL',
                                'fecha' => $fecha_hoy.' '.date('H:i:s'),
                                'folio' => ($nombre_rec == '')? '':$json_r['alumno'].$resp['taller']['id_taller']];
                            $asistencia = $asistM->registrarasistencia($data_ins);
                            $resp['taller']['asistencia'] = $asistencia;
    
                            require_once '../../functions/correos_prospectos.php';
                            
                            $destinatarios = [[$resp['persona']['email'], $resp['persona']['nombre']]];
                            $claves = ['%%TIPO','%%PERSONA_INTERES','%%EVENTO'];
                            $valores = ['Taller',$resp['persona']['nombre'], $resp['taller']['nombre']];
    
                            //$enviar = ($nombre_rec != '')?enviar_correo_registro("Envío de constancia de asistencia", $destinatarios, 'nueva_plantilla_constancias.html', $claves, $valores, "../../../images/constancias/".$nombre_rec.'.pdf') : '';
                            //$resp['taller']['envio'] = $enviar;
                        }else{
                            // $resp['taller']['mensaje'] = 'Su acceso a esta sala no está autorizado';
                        }
                    }
                }else{ // se está registrando una asistencia a evento
                    $resp['evento']['acceso'] = false;
                    $resp['evento']['asistencia'] = false;
                    $fecha_entrega = $info_ev['data']['fechaLimite'];
                    $tipo_asistencia = "evento";
                    # filtro 1 de acceso evento verificar fecha de evento
                    $finicio_e = strtotime($info_ev['data']['fechaE']);
                    $ffin_e = strtotime(substr($info_ev['data']['fechaLimite'], 0, 10));
                    if(strtotime($fecha_hoy) >= $finicio_e && strtotime($fecha_hoy) <= $ffin_e){
                        $resp['evento']['acceso'] = true;
                    }else{
                        $resp['evento']['acceso'] = false;
                        $resp['evento']['mensaje'] = 'Hoy no es la fecha para este evento.';
                    }
                    # filtro 2 de acceso a evento verificar pagos
                    
                     $id = $_POST['jsonasistencia'];
                    $mail = $prospM->consultarEmail($id)['data'];
                    //$mail = 'angelbonillabaez@gmail.com';
                    //$pays = $alumnoM->ConsultarCorreo_pagosSCAE($mail)['data'];
                    $pays = $eventoM->validar_evento_de_pago($_POST['eventoid']);
                    if(!$pays){ // si no tiene plan asignado el evento es gratuito
                        $pagado_ev = ['acceso' => true,'monto_cubierto'=> 0];
                    }else{ // si no, verificar el total de pagos aplicados
                        $pago = $eventoM->validar_pago_evento($_POST['eventoid'], $id);
                        if($pago){
                            if(floatval($pago['restante']) <= 1){
                                $pagado_ev = ['acceso' => true, 'monto_cubierto' => floatval($pago['costototal'])];
                            }else{
                                $pagado_ev = ['acceso' => false, 'monto_cubierto' => (floatval($pago['costototal']) - floatval($pago['restante']))];
                            }
                        }else{
                            $pago = $eventoM->validar_evento_paquetes($_POST['eventoid'], 182);
                            if($pago){
                                if(floatval($pago['restante']) <= 1){
                                    $pagado_ev = ['acceso' => true, 'monto_cubierto' => floatval($pago['costototal'])];
                                }else{
                                    $pagado_ev = ['acceso' => false, 'monto_cubierto' => (floatval($pago['costototal']) - floatval($pago['restante']))];
                                }
                            }else{
                                $pagado_ev = ['acceso' => false, 'monto_cubierto' => 0];
                            }
                        }
                    }
                    /*
                    if($_POST['eventoid'] != 72){
                        // consultar si el evento es de paga, consultando si tiene un plan de pagos asignado
                        $pays = $eventoM->validar_evento_de_pago($_POST['eventoid']);
                        if(!$pays){ // si no tiene plan asignado el evento es gratuito
                            $pagado_ev = ['acceso' => true,'monto_cubierto'=> 0];
                        }else{ // si no, verificar el total de pagos aplicados
                            $pago = $eventoM->validar_pago_evento($_POST['eventoid'], $id);
                            if($pago){
                                if(floatval($pago['restante']) <= 1){
                                    $pagado_ev = ['acceso' => true, 'monto_cubierto' => floatval($pago['costototal'])];
                                }else{
                                    $pagado_ev = ['acceso' => false, 'monto_cubierto' => (floatval($pago['costototal']) - floatval($pago['restante']))];
                                }
                            }else{
                                $pagado_ev = ['acceso' => false, 'monto_cubierto' => 0];
                            }
                        }
                    }else{
                        $pagado_ev = ['acceso' => true, 'monto_cubierto' => "$ 1,000.00"];
                    }
                    */
                   
                    //$pagado_ev = ['acceso' => true, 'monto_cubierto' => "$ 1,000.00"];
                    $resp['evento']['pagado'] = $pagado_ev['monto_cubierto'];
                    
                    if(!$pagado_ev['acceso']){
                        $resp['evento']['acceso'] = false;
                        $resp['evento']['mensaje'] = 'Su acceso a esta sala no está autorizado';
                    }
    
                    if($resp['evento']['acceso']){
                        $resp['evento']['mensaje'] = 'Bienvenido';
                        #REGISTRAR ASISTENCIA
                            # VALIDAR ASISTENCIA EVENTO
                        $asistencias_evento = $asistM->consultar_asistencia_evento($json_r['alumno'], $_POST['eventoid']);
                        if(sizeof($asistencias_evento['data']) == 0){
                            #registrar nueva asistencia
                            $resp['evento']['asistencia'] = true;
                        }else{
                            $fe_asist = strtotime(substr($asistencias_evento['data'][0]['hora'], 0, 10));
                            if($fe_asist < strtotime($fecha_hoy)){
                                #registrar nueva asistencia
                                $resp['evento']['asistencia'] = true;
                            }
                        }
                    }
    
                    if($resp['evento']['asistencia']){
                        $nombre_rec = "";
                        // echo ($info_ev['data']['plantilla_constancia'].'!='."'' AND ".$fecha_hoy." == ".substr($info_ev['data']['fechaLimite'], 0, 10));
                        // print_r($info_ev);
                        // die();
                        if($info_ev['data']['plantilla_constancia'] != '' && ($fecha_hoy == substr($info_ev['data']['fechaLimite'], 0, 10) || $_POST['eventoid'] == 75)){
                            $nom_reco = $info_ev['data']['titulo']."_".str_replace(' ', '_', $nom_p).'_'.$json_r['alumno'].'_'.$info_ev['data']['idEvento'];
                            $nombre_rec = generar_constancia($info_ev['data']['plantilla_constancia'], $nom_p, change_chars($nom_reco));
                        }
    
                        $data_ins = [
                            'nombre_reconocimiento' => $nombre_rec,
                            'id_asistente' => $json_r['alumno'],
                            'id_evento' => $_POST['eventoid'],
                            'id_taller' => null,
                            'modalidad' => 'PRESENCIAL',
                            'fecha' => $fecha_hoy.' '.date('H:i:s'),
                            'folio' => ($nombre_rec == '')? '':$json_r['alumno'].$info_ev['data']['idEvento']];
                        $asistencia = $asistM->registrarasistencia($data_ins);
                        $resp['evento']['asistencia'] = $asistencia;
                        require_once '../../functions/correos_prospectos.php';
                            
                        $destinatarios = [[$resp['persona']['email'], $resp['persona']['nombre']]];
                        $claves = ['%%TIPO','%%PERSONA_INTERES','%%EVENTO'];
                        $valores = [$info_ev['data']['tipo'], $resp['persona']['nombre'], $info_ev['data']['titulo']];
    
                        $enviar = ($nombre_rec != '')?enviar_correo_registro("Envío de constancia de asistencia", $destinatarios, 'nueva_plantilla_constancias.html', $claves, $valores, "../../../images/constancias/".$nombre_rec.'.pdf') : '';
                        $resp['evento']['envio'] = $enviar;
                    }
                }
                echo json_encode($resp);
                break;
            break;
        default:
                echo json_encode($_POST);
            break;
    }

}else{
    header("Location: ../../index.php");
}

function generar_constancia($plantilla, $persona, $titulo_rec){
    require_once '../../functions/constancias.php';

    $nombre = $persona;
    $nombre_reconocimiento = $titulo_rec;
    $salida = "../../../images/constancias/";
    $file = generar_pdf_constancia('../../functions/plantillas_constancias/'.$plantilla, $nombre, $nombre_reconocimiento, $salida, 10.5, 25);
    return $file;
}

function change_chars($string){
    $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
    return strtr( $string, $unwanted_array );
}
