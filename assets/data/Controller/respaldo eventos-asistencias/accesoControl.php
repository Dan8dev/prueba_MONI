<?php 
	if (isset($_POST["action"])) {
		date_default_timezone_set("America/Mexico_City");
		require_once '../../Model/conexion/conexion.php';
		require_once '../../Model/eventos/eventosModel.php';
		require_once '../../Model/eventos/prospectoEventoModel.php';
		
		$evtM = new Evento();
		$prospEM = new ProspectoEvento();

		switch ($_POST["action"]) {
			case 'validar_asistencia':
				unset($_POST['action']);
				$usuario = $prospEM->validar_acceso_asistente($_POST);
				$resp = [];
				if($usuario["estatus"] == 'ok' && !empty($usuario['data'])){
					$usr = $usuario['data'][0];
					if($usr['confirmado'] == 1 || ($usr['confirmado'] == 0 && $usr['rechazado'] == 0)){
						session_start();
						$usr['perfil'] = $prospEM->consultar_pagos_prospectos($usr['idAsistente'], $usr['idEvento'])['data'];
						$_SESSION['usuario'] = $usr;
						$resp['estatus'] = 'ok';
						$resp['data'] = $usr;
						$resp['relocation'] = 'panel.php';
					}else if($usr['rechazado'] == 1){
						$resp['estatus'] = 'error';
						$resp['info'] = 'prospecto_rechazado';
					}
				}else{
					$resp['estatus'] = "error";
					$resp['info'] = ($usuario['estatus']=='error')? 'error_interno' : 'sin_coincidencias';
					if($resp['info'] == 'error_interno'){
						$resp['data'] = $usuario;
					}
				}
				echo(json_encode($resp));
				break;
			case 'registrar_pago':

				$evento = (isset($_POST['evento'])) ? $_POST['evento'] : null ;
				$persona = (isset($_POST['persona'])) ? $_POST['persona'] : null ;
				$detalle = (isset($_POST['detalle'])) ? $_POST['detalle'] : null ;
				$plan_pago = (isset($_POST['plan_pago'])) ? $_POST['plan_pago'] : null ;

				 if($evento !== null && $persona !== null && $detalle !== null && $plan_pago !== null){
					$resp = $prospEM->registrarPagoEvento($evento, $persona, $detalle, $plan_pago);
				 }else{
				 	$resp = ["estatus"=>"error"];
				 }

				 echo json_encode($resp);
				 // echo json_encode([$evento, $persona, $detalle, $plan_pago]);
				break;
			case 'historial_seguimientos':
				$resp = [];
				if(isset($_POST['prospecto'])){
					if(isset($_POST['tipo_seguimiento'])) {
						$info = $prospEM->consultar_historial_seguimientos($_POST['prospecto'], $_POST['tipo_seguimiento']);
						$resp = $info;
						
					}else{
						$arr = [];
						$arr['comentarios'] = $prospEM->consultar_historial_seguimientos($_POST['prospecto'], 'comentarios')['data'];
						$arr['llamadas'] = $prospEM->consultar_historial_seguimientos($_POST['prospecto'], 'llamadas')['data'];
						$resp = ['estatus'=>'ok', 'data'=>$arr];
					}
				}

				echo(json_encode($resp));
				break;
			case 'agregar_comentario':
				unset($_POST['action']);
				$resp = [];
				if((isset($_POST['id_atencion']) && intval($_POST['id_atencion']) > 0) && (isset($_POST['inp_comentario']) && trim($_POST['inp_comentario']) != '')){
					$_POST['fecha'] = date("Y-m-d H:i:s");
					$resp = $prospEM->registrar_comentario_seguimiento($_POST);
				}
				echo(json_encode($resp));
				break;
			default:
				echo json_encode(["estatus"=>"error","info"=>"noaction"]);
				break;
		}
	}else{
		header('HTTP/1.0 403 Forbidden');
	}
?>