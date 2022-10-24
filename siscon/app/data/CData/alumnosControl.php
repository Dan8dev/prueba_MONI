<?php
date_default_timezone_set("America/Mexico_City");
require "../Model/AlumnoModel.php";
require "../Model/PagosModel.php";
require "../../../../assets/data/Model/acceso/accesoModel.php";
$Almn = new Alumno();
$Pgs = new Pagos();
$accM = new Acceso();

switch ($_POST["action"]) {
	case 'recuperarpasw':
		$emailrecuperar=$_POST['usr_name_recuperar'];
		$validarsiexiste=$Almn->validarsiexiste($emailrecuperar);
		if ($validarsiexiste['data']) {
			$token_dec = json_encode(['id_afiliado'=>$validarsiexiste['data']['id_afiliado'], 'fecha'=>date('Y-m-d'), 'site'=>'conacon']);
			$token_enc = $accM->enc($token_dec);
			$link = "https://conacon.org/moni/siscon/app/recovery_verify.php?token=".urlencode($token_enc);

			require("../../../../assets/data/functions/mailer.php");
			//--CORREO--//
			$HTML ="<div style=\"padding: 5%; background-color: #F8F8F8;\"><div style=\"padding: 3%; background-color: #FFFFFF; border: 1px solid #625B55; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;\">
						<div style=\"padding: 3%;\">
						<p style=\"font-family: Verdana; font-size: 26px; color: #625B55; text-align: center;\"><b>Recuperación de contraseña</b><br/></p>
							<p style=\"font-family: Verdana; font-size: 26px; color: #625B55;\">Hola: <b> ".$validarsiexiste['data']['nombre']."</b></p>
							<p style=\"font-family: Verdana; font-size: 16px; color: #625B55; \"></p>
							<p style=\"font-family: Verdana; font-size: 16px; color: #625B55; \">Hemos recibido su solicitud de recuperación de contraseña.</p>
							<p style=\"font-family: Verdana; font-size: 16px; color: #625B55; \">Para poder continuar con el proceso le solicitamos que de clic en el siguiente enlace:</p>
							<a href='".$link."' target='_blank'>Cambiar contraseña</a>
							<p style=\"font-family: Verdana; font-size: 16px; color: #625B55; \">Si no ha sido usted quien solicita el cambio, por favor hacer caso omiso.</p>
							<p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: center;\">A T E N T A M E N T E</p>
							<p style=\"font-family: Verdana; font-size: 20px; color: #625B55; text-align: center;\"><b>CONACON</b></p>
							<p style=\"font-family: Verdana; font-size: 10px; text-align: justify;\">
								<b>Alerta de confidencialidad:</b> Este correo electr&oacute;nico contiene informaci&oacute;n que es para uso exclusivo de la persona o entidad 
								cuyo nombre aparece al rubro. Si usted no es el destinatario pretendido de esta comunicaci&oacute;n, est&aacute; formalmente notificado de que 
								cualquier uso no autorizado, difusi&oacute;n o copiado de esta nota electr&oacute;nica, as&iacute; como de su contenido textual o adjunto(s), queda 
								estrictamente prohibido. Si por equivocaci&oacute;n ha recibido esta comunicaci&oacute;n, b&oacute;rrela y avise inmediatamente por correo electr&oacute;nico 
								a la persona arriba mencionada. Gracias por su atenci&oacute;n.
							</p>
						</div>
					</div></div>";
			$email=$emailrecuperar;
			$destinatarios = [[$email,"Sistemas"]];
			$asunto = 'RECUPERACIÓN DE CONTRASEÑA';
			$message = 'grats';
			$adjunto='none';
			$result=sendEmailOwn($destinatarios, $asunto, $HTML, $adjunto);
			}
			echo json_encode(['estatus'=>'ok']);
		
		break;
	case 'validarLogin':
		unset($_POST["action"]);
		$alumno = $Almn->validarLogin($_POST);

		$mensaje ="";
		foreach($alumno['data'] as $infoAlum){
			if($infoAlum['estatusOf']== "5"){
				$mensaje = "Su acceso a sido bloqueado, contacte a control escolar";
			}
			if($mensaje != ""){
				break;
			}
		}

		if(sizeof($alumno["data"]) > 0){
			if($mensaje != ""){
				$alumno = ['estatus'=>'error', 'info'=>[2=>'Su acceso a sido bloqueado, contacte a control escolar']] ;
			}else{
				session_start();
				$alumno['data'][0]['url_foto'] = 'img/afiliados/'.$alumno['data'][0]['foto'];
				$_SESSION['alumno'] = $alumno["data"][0];
			}
		}
		echo json_encode($alumno);
		break;
	case 'cambiarpasw':
		session_start();
		if (isset($_SESSION['alumno']) || isset($_POST['android_id_afiliado'])) {//sesion afiliado siscon
			$idusuario = isset($_POST['android_id_afiliado']) ? $_POST['android_id_afiliado'] : $_SESSION['alumno']['id_afiliado'];
			unset($_POST["action"]);
			$contrasena=$_POST["new_pass"];
			$confirmarcontrasena=$_POST["confirm_pass"];

			if (strcmp($contrasena, $confirmarcontrasena) !== 0) {
				echo 'La contraseña no coincide intentalo nuevamente';
			}
			else {
				$cambiarpasw=$Almn->cambiarpasw($contrasena,$idusuario);
				echo $cambiarpasw['estatus'];
			}
		}else if(isset($_SESSION['usuario'])) {//sesion usuarios moni
			$idusuario=$_SESSION['usuario']['idAcceso'];
			unset($_POST["action"]);
			$contrasena=$_POST["new_pass"];
			$confirmarcontrasena=$_POST["confirm_pass"];

			if (strcmp($contrasena, $confirmarcontrasena) !== 0) {
				echo 'La contraseña no coincide intentalo nuevamente';
			}
			else {
				$cambiarpasw=$Almn->cambiarpaswusermoni($contrasena,$idusuario);
				echo $_SESSION['usuario']['idTipo_Persona'];
			}
		}
		if ((!isset($_SESSION['alumno']) && !isset($_POST['android_id_afiliado']))&&!isset($_SESSION['usuario'])) {
			echo 'sesion_destroy';
		}
		break;
	case 'cargar_pagos':
		unset($_POST["action"]);
				#CONSULTAR TODO EL PLAN DE PAGOS DEL ALUMNO
			$RplanPagos = $Pgs->consultarPlanAlumnos($_POST);
			if($RplanPagos["estatus"] == "ok"){ 	#SI LA CONSULTA ES OK
				$RplanPagos = $RplanPagos["data"];  #RE ASIGNA LA VARIABLE CON EL ARREGLO DE DATOS
				
				$i = 0;		#RECORRE TODOS LOS PAGOS PLANEADOS DEL ALUMNO
				foreach ($RplanPagos as $plan) {

					#VALIDAR SI EL TIPO DE PAGO ES REGULAR(1) O ÚNICO (0)
					if($RplanPagos[$i]["regularidad"] == 1){

							#SI ES UN PAGO REGULAR BUSCA EL ARREGLO DE FECHAS PROGRAMADAS PARA CADA PAGO
						$fechasPagos = $Pgs->fechas_pagos_recurrentes(["id_plan" => $plan["id_plan"]]);

						if($fechasPagos["estatus"] == "ok"){	  #SI LA CONSULTA ES OK
							$fechasPagos = $fechasPagos["data"];  #RE ASIGNA LA VARIABLE CON EL ARREGLO DE DATOS

							$pagosPendientes = [];

								#RECORRE TODAS LAS FECHAS PROGRAMADAS
							foreach ($fechasPagos as $fecha) {
									#VALIDA LOS PAGOS ANTERIORES A ESTA FECHA O FUTUROS DENTRO DE 30 DIAS Y QUE NO ESTEN PAGADOS (PAGOS PAGADOS = 1)
								if( (strtotime($fecha["fecha_programada"]) <= strtotime(date("Y-m-d")."+ 1 month")) ){
									array_push($pagosPendientes, $fecha); 
								}
							}
							$RplanPagos[$i]["pendientes"] = $pagosPendientes;
						}
					}

					$i++;
				}

			}

			echo json_encode($RplanPagos);
		break;
	case 'restablecer_pass':
		$tok = $accM->dec($_POST['token']);
		// var_dump($_POST['token']);
		if(!json_decode($tok, true)){
			echo json_encode(['estatus'=>'error', 'info'=>'token invalido']);
			die();
		}
		$tok = json_decode($tok, true);
		if(strtotime(date('Y-m-d')) > strtotime($tok['fecha'])){
			echo json_encode(['estatus'=>'error', 'info'=>'Tiempo limite alcanzado, esta solicitud ya ha caducado']);
			die();
		}
		if(trim($_POST['inpPassw']) != trim($_POST['inpPassw_verify'])){
			echo json_encode(['estatus'=>'error', 'info'=>'Las contraseñas no coinciden.']);
			die();
		}
		if(trim($_POST['inpPassw']) == ''){
			echo json_encode(['estatus'=>'error', 'info'=>'La contraseña no puede estar vacía.']);
			die();
		}
		$cambio = $Almn->cambiarpasw(trim($_POST['inpPassw']), $tok['id_afiliado']);
		if($cambio['estatus'] == 'ok'){
			$cambio['panel'] = $tok['site'];
		}
		echo json_encode($cambio);
		break;
	default:
		echo "errorMethod";
		break;
}
//print_r($alumno->consultarTodoAlumnos());


?>
