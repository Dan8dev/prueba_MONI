 <?php
header('Access-Control-Allow-Origin: *', false);
session_start();

if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	// require_once '../Model/usuarioModel.php';
	require_once '../../Model/eventos/eventosModel.php';
	require_once '../../Model/prospectos/prospectosModel.php';
	require_once '../../Model/marketing/marketingModel.php';
	require_once '../../Model/alumnos/alumnosInstitucionesModel.php';


    $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
	
	$prospEM = new Prospecto();
	$evt = new Evento();
	$pay = new AccesosAlumnosInstituciones();

	$fr = function($acc, $item){
		switch ($item['etapa']) {
			case '0':
				$acc['pendientes']++;
				break;
			case '1':
				$acc['espera']++;
				break;
			case '2':
				$acc['confirmado']++;
				break;
			case '3':
				$acc['rechazo']++;
				break;
			case '4':
				$acc['no_interes']++;
				break;
		}
		return $acc;
	};

	switch ($_POST["action"]) {
		case 'listado_eventos':
			$l_ev = [];
			if((isset($_SESSION['usuario']) || isset($_POST['android_id_afiliado'])) && !isset($_POST['tipo'])){
				$l_ev = $evt->listarEventos()["data"];

				$mktM = new Marketing();

				for ($i=0; $i < sizeof($l_ev); $i++) { 
					$lugares_reservados = $evt->consultarAsistentesEvento($l_ev[$i]["idEvento"])["data"];
					$l_ev[$i]["lugares_reserv"] = sizeof($lugares_reservados);
					
					$info_estatus = ['pendientes'=>0,'espera'=>0,'confirmado'=>0,'rechazo'=>0,'no_interes'=>0];

					if(!empty($lugares_reservados)){
						$l_ev[$i]["estatus_info"] = array_reduce($lugares_reservados, $fr, $info_estatus);
						// consultar pagos de prospectos
						$prosp_evento = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'evento', $l_ev[$i]["idEvento"])['data'];
						
						$l_ev[$i]["prospectos_eventos"] = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'evento', $l_ev[$i]["idEvento"])['data'];
						for ($p=0; $p < sizeof($prosp_evento); $p++) { 
							$prosp_evento[$p]['pagos_realizados'] = $prospEM->consultar_pagos_prospectos($prosp_evento[$p]['idAsistente'], $l_ev[$i]["idEvento"])['data'];
						}
						$l_ev[$i]["prospectos_eventos"] = $prosp_evento;
					}
				}
				if(isset($_POST['android_id_afiliado']) && intval($_POST['android_id_afiliado']) > 0){
					foreach($l_ev as $key => $value){
						//$l_ev[$key]['pagado'] = 'si';
						$id = $_POST['android_id_afiliado'];
						$mail = $prospEM->consultarEmail($id)['data'];
						//$mail = 'angelbonillabaez@gmail.com';
						$pays = $pay->ConsultarCorreo_pagosSCAE($mail)['data'];
						
						if(!$pays){
						//$l_ev[$key]['pagado1'] = ['email'=>$mail,'data'=>$pays];
						$l_ev[$key]['pagado'] = 'no';
						}else{
						//$l_ev[$key]['pagado2'] = ['email'=>$mail,'data'=>$pays];
						$l_ev[$key]['pagado'] = 'si';
						}
					}
				}
			}else if(isset($_POST['tipo'])){
				$l_ev = $evt->listarEventos()["data"];
				foreach($l_ev as $ev => $val){
					if($val['tipo'] != $_POST['tipo']){
						unset($l_ev[$ev]);
					}
				}
				$l_ev = array_values($l_ev);
			}else{
				$l_ev = ["estatus"=>"error", "info"=>"sesion_vencida"];		
			}
			echo(json_encode($l_ev));
			break;
		case 'actualizar_lista_prospectos':
			$resp = [];
			$mktM = new Marketing();
			if(isset($_POST['tipo']) && $_POST['tipo'] == 'evento' && isset($_SESSION['usuario'])){
				$lugares_reservados = $evt->consultarAsistentesEvento($_POST["idInteres"])["data"];
				$objDetalle = $evt->consultarEvento_Id($_POST["idInteres"])['data'];
				$objDetalle["lugares_reserv"] = sizeof($lugares_reservados);
				
				$info_estatus = ['pendientes'=>0,'espera'=>0,'confirmado'=>0,'rechazo'=>0,'no_interes'=>0];

				if(!empty($lugares_reservados)){
					$objDetalle["estatus_info"] = array_reduce($lugares_reservados, $fr, $info_estatus);
					$prosp_evento = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'evento', $_POST["idInteres"])['data'];
					for ($p=0; $p < sizeof($prosp_evento); $p++) { 
						$prosp_evento[$p]['pagos_realizados'] = $prospEM->consultar_pagos_prospectos($prosp_evento[$p]['idAsistente'], $_POST["idInteres"])['data'];
					}
					$objDetalle["prospectos_eventos"] = $prosp_evento;
				}
				$resp = ["estatus"=>'ok','data'=>$objDetalle];
			}else{
				$resp = ["estatus"=>"error"];
			}
			echo json_encode($resp);
			break;
		case 'confirmar_asistencia':
			if(isset($_SESSION['usuario'])){
			$inte = $_POST["id_interes"];
			$asis = $_POST["id_asistente"];
			
			echo json_encode($prospEM->confirmar_asistencia_prospecto($inte, $asis));
			}else{
				$array=array("error"=>'no_session');
				echo(json_encode($array));
			}
			break;
		case 'rechazar_asistencia':
			if(isset($_SESSION['usuario'])){
			$inte = $_POST["id_interesRechazo"];
			$asis = $_POST["id_asistenteRechazo"];
			
			echo json_encode($prospEM->rechazar_asistencia_prospecto($inte, $asis));
			}else{
				$array=array("error"=>'no_session');
				echo(json_encode($array));
			}
			break;
		case 'talleres_eventos':
			$talleres = $evt->talleres_eventos($_POST['evento']);
			$cont = 0;
			foreach ($talleres['data'] as $taller) {
				if(intval($taller['ocupados']) >= intval($taller['cupo'])){
					unset($talleres['data'][$cont]);
				}
					$cont++;
			}

			$talleres['data'] = array_values($talleres['data']);
			echo json_encode($talleres);
			break;
		case 'seleccion_talleres':
			unset($_POST['action']);
			$id_prospecto = false;
			if(isset($_SESSION['alumno'])){
				$id_prospecto = $_SESSION['alumno']['id_prospecto'];
			}else if(isset($_POST['android_id_prospecto'])){
				$id_prospecto = $_POST['android_id_prospecto'];
			}
			if($id_prospecto == false){
				echo json_encode(['estatus'=>'error','info'=>'Verifique su sesión']);
				die();
			}
			$resp = [];
			$talleres = $evt->talleres_eventos(72);
			

			// if(sizeof($seleccion['data']) > 0){
			// 	$resp = ['estatus'=>'error','info'=>'Su seleccion de talleres ya ha sido recibida previamente.'];
			// }else{
				foreach ($_POST as $key => $value) {
					$k = explode('_',$key);
					if($k[0] == 'taller'){
						$insert = [
							'prospecto'=>$id_prospecto,
							'taller'=>$value,
							'fecha'=>date("Y-m-d H:i:s")
						];
						foreach ($talleres['data'] as $taller) {
							if(intval($taller['ocupados']) < intval($taller['cupo']) && $taller['id_taller'] == $value){
								$seleccion = $evt->get_talleres_prospecto($id_prospecto, 72, $k[1]);
								// var_dump($seleccion);
								// die();
								if(!empty($seleccion['data'])){
									http_response_code(203); 
									echo json_encode(['error'=>'cupo diario cubierto']);
									exit;
								}
								
								$apartar = $evt->apartar_talleres($insert);
								$resp = ['estatus'=>'ok'];
								if($apartar['estatus'] == 'error'){
									$resp = ['estatus'=>'error','info'=>'Ha ocurrido un error realizar el apartado de taller.', 'detalle'=>$apartar];
								}
							}else{
								if(intval($taller['ocupados']) >= intval($taller['cupo'])  && $taller['id_taller'] == $value){
									http_response_code(203);
									echo json_encode(['error'=>'cupo taller lleno']);
									exit;
								}
							}
								// $cont++;
						}
					}
				}
			// }
			echo json_encode($resp);
			break;
		case 'apartar_talleres':
			unset($_POST['action']);
			$fecha = date("Y-m-d H:i:s");
			$error = [];
			$inserted = [];
			foreach($_POST as $key => $val){
				if(substr($key, 0, 4) == "chk_"){
					$info = [
						'prospecto'=>$_POST['persona'],
						'taller'=>substr($key, 4),
						'fecha'=>$fecha
					];
					$apartar = $prospEM->apartar_talleres($info);
					if($apartar['estatus'] == 'error'){
						array_push($error, $apartar);
					}else{
						array_push($inserted, $apartar);
					}
				}
			}
				if(empty($error)){
					$resp = ['estatus'=>'ok', 'insertados'=>$inserted];
				}else{
					$resp = ['estatus'=>'error', 'fallos'=>$error];
				}
			echo json_encode($resp);
			break;
		case 'apartar_tallere_prospecto':
			unset($_POST['action']);
			$fecha = date("Y-m-d H:i:s");
			$info = [
				'prospecto'=>$_POST['persona'],
				'taller'=>$_POST['taller'],
				'fecha'=>$fecha
			];
			$apartar = $prospEM->apartar_talleres($info);
			echo json_encode($apartar);
			break;
		case 'consultar_eventos_memoria':
			unset($_POST['action']);
			$tipo = 0;
			$resp = [];
			if(isset($_POST['tipo'])){
				switch ($_POST['tipo']) {
					case 'proximos':
						$tipo = 1;
						break;
					case 'memoria':
						$tipo = 2;
						break;
				}
				
				$resp = $evt->listarEventos($tipo);
				
				if($resp['estatus']=='ok'){
					for ($i=0; $i < sizeof($resp['data']); $i++) { 
						$resp['data'][$i]['short_url'] = urlencode(gzcompress($resp['data'][$i]['video_url'], 0));
						// var_dump(gzcompress($resp['data'][$i]['video_url'], -1));
					}
				}
			}
			echo json_encode($resp);
			break;
		case 'consultarEvento_Clave':
				echo json_encode($evt->consultar_evento_clave_no_estatus($_POST['clave']));
			break;
		case 'listasAsistencias':
			if(isset($_POST['evento'])){
				$asistencias_gral = $evt->listaAsistenciasGeneral($_POST['evento']);
				foreach($asistencias_gral['data'] as $key_asist => $val_asist){
					// $asistencias_gral['data'][$key_asist]['asistencias_evento'] = $evt->AsistenciasPersona_evento($_POST['evento'], $val_asist['id_asistente'])['data'];
					// $asistencias_gral['data'][$key_asist]['asistencias_taller'] = $evt->AsistenciasPersona_evento($_POST['evento'], $val_asist['id_asistente'], true)['data'];
				}
				echo json_encode($asistencias_gral);
			}else{
				echo json_encode(['estatus'=>'error', 'info'=>'No se ha recibido información del evento']);
			}
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('Location: ../../../../index.php');
}
?>
