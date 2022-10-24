<?php 
 session_start();
if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/marketing/marketingModel.php';
	require_once '../../Model/eventos/eventosModel.php';
	require_once '../../Model/carreras/carrerasModel.php';
	
	require_once '../../Model/prospectos/prospectosModel.php';
	//require_once '../../Model/marketing/comisionesModel.php';
	require_once '../../Model/planpagos/pagosModel.php'; // <-- para la funcion de consultar administrativo
	$pagosM = new pagosModel();
	
	$mktM = new Marketing();
	$prospM = new Prospecto();

	$carrM = new Carrera();
	//$comiM = new MarketingComisiones();

	$eventoM = new Evento();

	switch ($_POST["action"]) {
		case 'select_general':
			$eventos = $eventoM->listarEventos(1);
			$carreras = $carrM->listarCarreras();
			echo json_encode(array('eventos' => $eventos['data'], 'carreras' => $carreras['data']));
			break;
		case 'listar_prospectos':
			unset($_POST['action']);
			$seleccion = $_POST['seleccion'];
			$prospectos = [];
			$ejecutiva = false;
			if($_SESSION['usuario']['persona']['rol'] != 2){
				$ejecutiva = $_SESSION['usuario']['persona']['idPersona'];
			}
			foreach($seleccion as $select){
				$id = explode('-', $select);
				if(substr($select, 0, 7) == 'carrera'){
					$prospectos = array_merge($prospectos, $prospM->listar_prospectos_tipo_atencion('carrera', $id[1], 1, $ejecutiva)['data']);
				}else if(substr($select, 0, 6) == 'evento'){
					$prospectos = array_merge($prospectos, $prospM->listar_prospectos_tipo_atencion('evento', $id[1], 1, $ejecutiva)['data']);
				}
			}
			echo json_encode($prospectos);
			break;
		case 'consultar_seguimientos':
				$response = [];
				$ests = $prospM->etapas_num;
				$prospectos = [];
				foreach($ests as $e){
					$prs_cr = $prospM->listar_prospectos_tipo_atencion('carrera', false, $e, false, $_POST['prospecto'])['data'];
					if(!empty($prs_cr)){
						$prospectos = array_merge($prospectos, $prs_cr);
					}
					$prs_ev = $prospM->listar_prospectos_tipo_atencion('evento', false, $e, false, $_POST['prospecto'])['data'];
					if(!empty($prs_ev)){
						$prospectos = array_merge($prospectos, $prs_ev);
					}
				}
				$response['info'] = $prospM->obtenerdatosprospecto($_POST['prospecto'])['data'];
				$response['results'] = $prospectos;
				echo json_encode($response);
			break;
			case 'listar_confirmados':
				unset($_POST['action']);
				$seleccion = $_POST['seleccion'];
				$prospectos = [];
				$ejecutiva = false;
				if($_SESSION['usuario']['persona']['rol'] != 2){
					$ejecutiva = $_SESSION['usuario']['persona']['idPersona'];
				}
				foreach($seleccion as $select){
					$prospectos = array_merge($prospectos, $prospM->listar_prospectos_tipo_atencion('carrera', $select, 3, $ejecutiva)['data']);
				}
				$new_prospectos = [];
				foreach($prospectos as $prospecto){
					if(!array_key_exists($prospecto['prospecto'], $new_prospectos)){
						$new_prospectos[$prospecto['prospecto']] = $prospecto;
						$new_prospectos[$prospecto['prospecto']]['seguimientos'] = [$prospecto];
					}
				}
				$stats = $prospM->etapas_num;
				foreach($new_prospectos as $key => $prospecto){
					foreach($stats as $estatus){
						$otr_aten = $prospM->listar_prospectos_tipo_atencion('carrera', false, $estatus, $ejecutiva, $prospecto['prospecto'])['data'];
						if(!empty($otr_aten)){
							$new_prospectos[$key]['seguimientos'] = array_merge($new_prospectos[$key]['seguimientos'], $otr_aten);
						}
						$otr_aten_ev = $prospM->listar_prospectos_tipo_atencion('evento', false, $estatus, $ejecutiva, $prospecto['prospecto'])['data'];
						if(!empty($otr_aten_ev)){
							$new_prospectos[$key]['seguimientos'] = array_merge($new_prospectos[$key]['seguimientos'], $otr_aten_ev);
						}
					}
				}
				echo json_encode($new_prospectos);
				break;
			case 'listar_otros':
				unset($_POST['action']);
				$seleccion = $_POST['seleccion'];
				$estatus_filt = $_POST['estatus'];
				$prospectos = [];
				$ejecutiva = false;
				if($_SESSION['usuario']['persona']['rol'] != 2){
					$ejecutiva = $_SESSION['usuario']['persona']['idPersona'];
				}
				foreach($seleccion as $select){
					foreach ($estatus_filt as $est) {
						$id = explode('-', $select);
						if(substr($select, 0, 7) == 'carrera'){
							$prospectos = array_merge($prospectos, $prospM->listar_prospectos_tipo_atencion('carrera', $id[1], $est, $ejecutiva)['data']);
						}else if(substr($select, 0, 7) == 'evento'){
							$prospectos = array_merge($prospectos, $prospM->listar_prospectos_tipo_atencion('evento', $id[1], $est, $ejecutiva)['data']);
						}
					}
				}
				$new_prospectos = [];
				foreach($prospectos as $prospecto){
					if(!array_key_exists($prospecto['prospecto'], $new_prospectos)){
						$new_prospectos[$prospecto['prospecto']] = $prospecto;
						$new_prospectos[$prospecto['prospecto']]['seguimientos'] = [$prospecto];
					}
				}
				$stats = $prospM->etapas_num;
				// foreach($new_prospectos as $key => $prospecto){
				// 	foreach($stats as $estatus){
				// 		$otr_aten = $prospM->listar_prospectos_tipo_atencion('carrera', false, $estatus, $ejecutiva, $prospecto['prospecto'])['data'];
				// 		if(!empty($otr_aten)){
				// 			$new_prospectos[$key]['seguimientos'] = array_merge($new_prospectos[$key]['seguimientos'], $otr_aten);
				// 		}
				// 		$otr_aten_ev = $prospM->listar_prospectos_tipo_atencion('evento', false, $estatus, $ejecutiva, $prospecto['prospecto'])['data'];
				// 		if(!empty($otr_aten_ev)){
				// 			$new_prospectos[$key]['seguimientos'] = array_merge($new_prospectos[$key]['seguimientos'], $otr_aten_ev);
				// 		}
				// 	}
				// }
				echo json_encode($new_prospectos);
				break;
		case 'lista_atencion':
			echo json_encode($mktM->atender_listado_eventos());
			break;
		case 'seguimientos':
			$data = $prospM->consultar_historial_seguimientos($_POST['seguimiento'], 'comentario')['data'];
			$resp = [];
			foreach ($data as $key => $value) {
				$ejecutiva_r = '';
				if($value['idEjecutiva'] !== null){
					$quien_registro = $pagosM->quien_registro($value['idEjecutiva'])['data'];
					if (isset($quien_registro['idTipo_Persona'])==3) {
						$info_p = $pagosM->nombre_marketing($quien_registro['idPersona']);
						if($info_p['data']){
							$ejecutiva_r = $info_p['data']['nombres'];
						}
					}
				}
				$data[$key]['ejecutiva_registro'] = $ejecutiva_r;
				if(strpos($value['detalles'], '|')){
					$value['detalles'] = substr($value['detalles'], 0, strpos($value['detalles'], '|'));
					$value['detalles'] .= '	<i class="fa fa-phone-square"></i>';
				}
				array_push($resp, [
					0=>$value['fecha'],
					1=>$value['detalles'],
					2=>$ejecutiva_r
				]);
			}
			$result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($resp),
                'iTotalDisplayRecords'=>count($resp),
                'aaData'=>$resp
            );
            echo json_encode($result);
			break;
		case 'consultar_todo_ejecutivas':
			$ejecutivas = $mktM->consultar_todo_ejecutivas()['data'];
			$especiales = ['(',')','-','_',' ','.'];
			for ($i=0; $i < sizeof($ejecutivas); $i++) { 
				// $ejecutivas[$i]['telefono_noformat'] = str_replace($especiales, '', $ejecutivas[$i]['telefono']);
				// $ejecutivas[$i]['prospectos_eventos'] = $mktM->consultar_todo_fila_atencion_ejecutiva($ejecutivas[$i]['idPersona'],'evento')['data'];
				$ejecutivas[$i]['prospectos_eventos'] = [];

				// $ejecutivas[$i]['prospectos_carreras'] = $mktM->consultar_todo_fila_atencion_ejecutiva($ejecutivas[$i]['idPersona'],'carrera')['data'];
				$ejecutivas[$i]['prospectos_carreras'] = [];
			}
			echo(json_encode($ejecutivas));
			break;
		case 'asignar_prospecto': // se manda a llamar despues de registrar un nuevo prospecto desde el admin de marketing
			unset($_POST['action']);
			if(!isset($_POST['n_prosp_personaMk'])){
				// $_POST['n_prosp_personaMk'] = $_SESSION['usuario']['persona']['idPersona'];
				$_POST['n_prosp_personaMk'] = $mktM->buscar_ultima_ejecutiva($_POST['prospecto']);
				if($_POST['n_prosp_personaMk'] == 0){
					$_POST['n_prosp_personaMk'] = 1;
				}
			}

			$ultimoReg = null;
			
			$asoc = $mktM->set_prospecto_fila($_POST['n_prosp_tipo'], $_POST['prospecto'], $_POST['n_prosp_personaMk'], $_POST['interes']);

			if($asoc['estatus'] == 'ok'){
				$resp = $asoc;
			}else{
				$resp = ['estatus'=>'error', 'info'=>$asoc['info']];
			}

			echo json_encode($resp);
			break;
		case 'reasignar_prospecto':
			unset($_POST['action']);
			$resp = [];
			if(isset($_POST['inp_prospect']) && intval($_POST['inp_prospect']) > 0){
				$resp = $mktM->reasignar_prospecto($_POST['inp_prospect'], $_POST['change_ejecutiva']);
			}else{
				$resp = ['estatus'=>'error','info'=>'Prospecto no valido'];
			}

			echo json_encode($resp);
			break;
		case 'agenda_llamadas':
			unset($_POST['action']);

			if(isset($_SESSION['usuario'])){
				$resp = $mktM->consultar_llamadas_ejecutiva($_SESSION['usuario']['persona']['idPersona']);
			}else{
				$resp = ['estatus'=>'error','info'=>'no_session'];
			}
			echo json_encode($resp);
			break;
		case 'registrar_a_carrera':
			$resp = [];
			$resp['ejecutiva'] = null;
			$error_i = '';
			$id_alumno = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
			
			if(isset($_SESSION['alumno']) || isset($_POST['android_id_prospecto'])){
				$continue = true;
				$info_c = null;

				if(!isset($_POST['tipo'])){
					$continue = false;
					$error_i = "No se definió el de interés.";
				}

				if(isset($_POST['nombre_c'])){
					$info_c = $carrM->consultarCarreraBy_codigo($_POST['nombre_c']);
					if($info_c['data'] == false){
						$continue = false;
						$error_i = "Nombre de carrera no valido.";
					}else{
						$prospecto_inscrito = $carrM->validar_registro_carrera($id_alumno, $info_c['data']['idCarrera']);
						if(sizeof($prospecto_inscrito['data']) > 0){
							$continue = false;
							$error_i = "Ya se encuentra inscrito a esta carrera, espere a que una ejecutiva se ponga en contacto con usted.";
						}
					}
				}else{
					$continue = false;
					$error_i = "No se definió el nombre de la carrera.";
				}
				#verificar no inscripcion 

				if($continue){
					$reset_id = $prospM->reset_carrera_atencion($info_c['data']['idCarrera'], $id_alumno);
					if($reset_id['data'] > 0){
						$meter_a_carrusel = $mktM->actualzar_fila_atencion($id_alumno, 'carrera',$info_c['data']['idCarrera']);
						$id_persona = null;
						if($meter_a_carrusel['estatus'] == 'ok'){
							$id_persona = $meter_a_carrusel['data']['persona_seguimiento'][0];
							$resp = ['estatus'=>'ok','data'=>$meter_a_carrusel['data']];
						}else{
							$ult_ejecutiva_aten = $mktM->buscar_ultima_ejecutiva($id_alumno);
							if($ult_ejecutiva_aten == 0){
								$ult_ejecutiva_aten = 1;
							}
							$id_persona = $ult_ejecutiva_aten;
							$asoc = $mktM->set_prospecto_fila('carrera', $id_alumno, $ult_ejecutiva_aten, $info_c['data']['idCarrera']);
							// $resp = ['estatus'=>'error','info'=>'Error al actualizar al asignar ejecutiva', 'ejecutiva'=>null];
							$resp = ['estatus'=>'ok'];
						}
						$resp['ejecutiva'] = $mktM->consultarPersonaMktng_ById($id_persona)['data'];
					}else{
						$resp = ['estatus'=>'error','info'=>'Error al actualizar la carrera de interés','det'=>'reset_id', 'ejecutiva'=>null];
					}
				}else{
					$resp = ['estatus'=>'error','info'=>$error_i, 'ejecutiva'=>null];
				}

			}else{
				$resp = ['estatus'=>'error','info'=>'No se pudo procesar la petición, intente mas tarde, o verifique vuelva a iniciar sesión', 'ejecutiva'=>null];
			}

			echo json_encode($resp);
			break;
			case 'registrar_a_evento':
				$resp = [];
				$resp['ejecutiva'] = null;
				$error_i = '';
				$id_alumno = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
				
				if(isset($_SESSION['alumno']) || isset($_POST['android_id_prospecto'])){
					$continue = true;
					$info_c = null;
	
					if(!isset($_POST['tipo'])){
						$continue = false;
						$error_i = "No se definió el de interés.";
					}
	
					if(isset($_POST['nombre_c'])){
						$info_c = $eventoM->consultarEvento_Clave($_POST['nombre_c']);
						$info_c['data'] = $info_c['data'][0];

						if($info_c['data'] == false){
							$continue = false;
							$error_i = "Nombre de evento no valido.";
						}else{
							$prospecto_inscrito = $eventoM->validar_registro_evento($id_alumno, $info_c['data']['idEvento']);
							if($prospecto_inscrito[0] !== false){
								$continue = false;
								$error_i = "Ya se encuentra inscrito a este evento, espere a que una ejecutiva se ponga en contacto con usted.";
							}
						}
					}else{
						$continue = false;
						$error_i = "No se definió el nombre del evento.";
					}
					
					#verificar no inscripcion 
	
					if($continue){
						// $reset_id = $prospM->reset_carrera_atencion($info_c['data']['idCarrera'], $id_alumno);
						$meter_a_carrusel = $mktM->actualzar_fila_atencion($id_alumno, 'evento',$info_c['data']['idEvento']);
						$id_persona = null;
						if($meter_a_carrusel['estatus'] == 'ok'){
							$id_persona = $meter_a_carrusel['data']['persona_seguimiento'][0];
							$resp = ['estatus'=>'ok','data'=>$meter_a_carrusel['data']];
						}else{
							$ult_ejecutiva_aten = $mktM->buscar_ultima_ejecutiva($id_alumno);
							if($ult_ejecutiva_aten == 0){
								$ult_ejecutiva_aten = 1;
							}
							$id_persona = $ult_ejecutiva_aten;
							$asoc = $mktM->set_prospecto_fila('evento', $id_alumno, $ult_ejecutiva_aten, $info_c['data']['idEvento']);
							// $resp = ['estatus'=>'error','info'=>'Error al actualizar al asignar ejecutiva', 'ejecutiva'=>null];
							$resp = ['estatus'=>'ok'];
						}
						$resp['ejecutiva'] = $mktM->consultarPersonaMktng_ById($id_persona)['data'];
					}else{
						$resp = ['estatus'=>'error','info'=>$error_i, 'ejecutiva'=>null];
					}
	
				}else{
					$resp = ['estatus'=>'error','info'=>'No se pudo procesar la petición, intente mas tarde, o verifique vuelva a iniciar sesión', 'ejecutiva'=>null];
				}
	
				echo json_encode($resp);
				break;
		case 'estatus_seguimiento':
			echo json_encode($mktM->cargar_estatus_seguimiento());
			break;
		/*case 'ejecutivas_comision':
			$ejecutivas = $mktM->consultar_todo_ejecutivas()['data'];
			$fecha = explode('-', date('Y-m-d'));
			foreach($ejecutivas as $key => $ejecutiva){
				if($ejecutiva['rol'] == 1){
					$comision_p = ["periodo"=>$fecha,"detalles"=>$comiM->consultar_periodo($fecha[1], $fecha[0], $ejecutiva['idPersona'])];
					$ejecutivas[$key]['comision_periodo'] = ($comision_p) ? $comision_p : false;
				}else{
					unset($ejecutivas[$key]);
				}
			}
			$ejecutivas = array_values($ejecutivas);
			echo json_encode($ejecutivas);
			break;
		case 'comision_ejecutiva':
			$resp = null;
			if(isset($_POST['periodo']) && sizeof(explode('-', $_POST['periodo'])) > 1){
				$fecha = explode('-', $_POST['periodo']);
				$ejecutiva = $mktM->consultarPersonaMktng_ById($_POST['ejecutiva'])['data'];
				
				$monto_comision = 0;
				
				// consultar el estatus del periodo, si ya se ha generado un corte o no
				$detalle_periodo = $comiM->consultar_periodo($fecha[1], $fecha[0], $ejecutiva['idPersona']);
				// si el corte ya se ha realizado, devolver el desglose del corte
				if($detalle_periodo){
					$detalle_periodo['jsonEC'] = json_decode($detalle_periodo['jsonEC'], true);
					$comision_p = ["periodo"=>$fecha,"detalles"=>$detalle_periodo];
					
					$resp = ['corte'=>$comision_p, "estatus"=>"Concluido"];

				}else{ // si el corte no se ha realizado entonces se consultaran las atenciones de prospectos de la ejecutiva
					$atenciones = $comiM->consultar_prospectos_confirmados_ejecutiva($ejecutiva['idPersona']);
					$atenciones = $comiM->calcular_comisiones($atenciones, $fecha);
					$atenciones = array_values($atenciones);
					$resp = ["corte"=>["periodo"=>$fecha,"detalles"=>$atenciones], "estatus"=>"Pendiente"];
				}
				
			}else{
				$resp = ['estatus'=>'error','info'=>'Periodo no válido'];
			}
			echo json_encode($resp);
			break;
		case 'generar_corte':
			$resp = null;
			if(isset($_POST['ejecutiva']) && intval($_POST['ejecutiva']) > 0 && isset($_POST['periodo']) && sizeof(explode('-', $_POST['periodo'])) > 1){
				$fecha = explode('-', $_POST['periodo']);
				$ejecutiva = $mktM->consultarPersonaMktng_ById($_POST['ejecutiva'])['data'];
				
				$monto_comision = 0;
				// consultar el estatus del periodo, si ya se ha generado un corte o no
				$detalle_periodo = $comiM->consultar_periodo($fecha[1], $fecha[0], $ejecutiva['idPersona']);
				$comision_p = ["periodo"=>$fecha,"detalles"=>$detalle_periodo];
				// si el corte ya se ha realizado, devolver el desglose del corte
				if($detalle_periodo){
					$resp = ['estatus'=>'error', "info"=>"El periodo ya cuenta con un corte generado."];
				}else{ // si el corte no se ha realizado entonces se consultaran las atenciones de prospectos de la ejecutiva
					$atenciones = $comiM->consultar_prospectos_confirmados_ejecutiva($ejecutiva['idPersona']);
					$atenciones = $comiM->calcular_comisiones($atenciones, $fecha);
					$atenciones = array_values($atenciones);
					$operaciones = [];
					$atenciones_ = []; // ids de la tabla a_marketing_atencion a marcar como procesadas en el corte
					foreach($atenciones as $key => $atencion){
						$monto_comision += $atencion['comision'];
						$atenciones_[] = $atencion['idReg']; 
						$operaciones[] = [
							"pago"=>$atencion['pagos'][0]['id_pago'],
							"idAtencion"=>$atencion['idReg'],
							"prospecto"=>$atencion['nombre_prospecto'],
							"monto_comision"=>$atencion['comision'],
							"fecha"=>date('Y-m-d H:i:s'),
							"tipo_atencion"=>$atencion['tipo_atencion'],
							"interes"=>$atencion['nombre_interes']
						];
					}
					$comision = [
						"total_comision"=>$monto_comision,
						"periodo"=>$fecha[0]."-".$fecha[1],
						"total_operaciones"=>sizeof($operaciones),
						"operaciones"=>$operaciones
					];

					if($monto_comision > 0){
						$insert = [
							'ejecutiva'=>$ejecutiva['idPersona'],
							'monto'=>$monto_comision,
							'fecha_corte'=>$fecha[0]."-".$fecha[1]."-01",
							'json_ec'=>json_encode($comision)
						];
						$generar_corte = $comiM->registrar_corte_ejecutiva($insert);
						if($generar_corte > 0){
							$ids = implode(",", $atenciones_);
							$comiM->marcar_atenciones_en_corte($ids, $generar_corte);
						}
						$resp = ["corte"=>["periodo"=>$fecha,"detalles"=>$atenciones], "estatus"=>$generar_corte];
					}else{
						$resp = ['estatus'=>'error', "info"=>"El periodo no tiene operaciones para generar un corte."];
					}
				}
			}else{
				$resp = ['estatus'=>'error','info'=>'Periodo o ejecutiva no válido'];
			}
			echo json_encode($resp);
			break;
		case 'consultar_comision_carreras':
			echo json_encode($comiM->consultar_carreras());
			break;
		case 'actualizar_monto_comision':
			$_POST['inp_monto'] = str_replace([",","$"," "], "", $_POST['inp_monto']);
			if(isset($_POST['id_carrera']) && intval($_POST['id_carrera']) > 0 && isset($_POST['inp_monto']) && floatval($_POST['inp_monto']) > 0){
				$resp = $comiM->actualizar_monto_comision($_POST['id_carrera'], $_POST['inp_monto']);
				if($resp > 0){
					$resp = ['estatus'=>'ok'];
				}else{
					$resp = ['estatus'=>'error', 'info'=>'No se pudo actualizar el monto de la comisión'];
				}
			}else{
				$resp = ['estatus'=>'error', 'info'=>'Datos no válidos'];
			}
			echo json_encode($resp);
			break;*/
		case 'consultar_clinicas_solicitudes':
			$datos = $mktM->consultar_clinicas_prospectos_responsables();
			$ejecutiva = false;
			if($_SESSION['usuario']['persona']['rol'] != 2){
				foreach ($datos as $dat_k => $dat_v) {
					if($dat_v['idMk_persona'] != $_SESSION['usuario']['idPersona']){
						unset($datos[$dat_k]);
					}
				}
				$datos = array_values($datos);
			}
			echo json_encode($datos);
			break;
		case 'verify_clinic':
			$clinica = $mktM->consultar_info_clinica($_POST['clinic']);
			if(!$clinica){
				echo json_encode(['estatus'=>'error', 'info'=>'No se ha identificado correctamente la clínica']);
				die();
			}
			if($clinica['preautorizacion'] !== null){
				echo json_encode(['estatus'=>'error', 'info'=>'La verificación de esta clínica ya ha sido modificada anteriormente. No es posible volver a cambiarla']);
				die();
			}
			// crear clave clinica
			$clave = '';
			$partes = explode(' ', $clinica['nombre']);
			if(sizeof($partes) >= 3){
				$len = sizeof($partes) < 5 ? 2 : 1;
				foreach ($partes as $palabra) {
					$tmp_pal = $palabra;
					$tmp_pal = preg_replace("/[^a-zA-Z]+/", "", $tmp_pal);
					$clave .= substr($tmp_pal, 0, $len);
				}
			}else{
				$clave = substr($clinica['nombre'], 0, 4);
			}
			$clave.=time();
			$clave = strtoupper($clave);
			echo json_encode($mktM->set_clinica_verified($_POST['clinic'], true, $_POST['comentario'], $clave));
			break;
		case 'non_verify_clinic':
			$clinica = $mktM->consultar_info_clinica($_POST['clinic']);
			if(!$clinica){
				echo json_encode(['estatus'=>'error', 'info'=>'No se ha identificado correctamente la clínica']);
				die();
			}
			if($clinica['preautorizacion'] !== null){
				echo json_encode(['estatus'=>'error', 'info'=>'La verificación de esta clínica ya ha sido modificada anteriormente. No es posible volver a cambiarla']);
				die();
			}
			echo json_encode($mktM->set_clinica_verified($_POST['clinic'], false, $_POST['comentario']));
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
