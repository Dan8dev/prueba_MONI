<?php 
date_default_timezone_set("America/Mexico_City");
require "../Model/AlumnoModel.php";
require "../Model/PagosModel.php";
$Almn = new Alumno();
$Pgs = new Pagos();

switch ($_POST["action"]) {
	case 'recuperarpasw':
		$emailrecuperar=$_POST['usr_name_recuperar'];
		$validarsiexiste=$Almn->validarsiexiste($emailrecuperar);
		if ($validarsiexiste['data']) {
			require("../../../../assets/data/functions/mailer.php");
			//--CORREO--//
			$HTML =
			"
			<div style=\"padding: 5%; background-color: #F8F8F8;\">
				<div style=\"padding: 3%; background-color: #FFFFFF; border: 1px solid #625B55; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;\">
					<div style=\"padding: 3%;\">
					<p style=\"font-family: Verdana; font-size: 26px; color: #625B55; text-align: center;\"><b>Recuperación de contraseña</b><br/></p>
						<p style=\"font-family: Verdana; font-size: 26px; color: #625B55;\">Hola:<br/> <b> ".$validarsiexiste['data']['nombre']."</b></p>
						<p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: justify;\">Inicia sesión en la siguiente dirección <b> https://conacon.org/moni/siscon/app/index.php?user=".$emailrecuperar."&psw=".$validarsiexiste['data']['contrasena']."</b>, tu usuario es:<b> ".$emailrecuperar."</b> y la contraseña: <b>".$validarsiexiste['data']['contrasena']."</b></p>
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
				</div>
			</div>
			";
			$email=$emailrecuperar;
			$destinatarios = [[$email,"Sistemas"]];
			$asunto = 'RECUPERACIÓN DE CONTRASEÑA';
			$message = 'grats';
			$adjunto='none';
			$result=sendEmailOwn($destinatarios, $asunto, $HTML, $adjunto);
			}
			echo json_encode($validarsiexiste);
		
		break;
	case 'validarLogin':
		unset($_POST["action"]);
		$alumno = $Almn->validarLogin($_POST);
		if(sizeof($alumno["data"]) > 0){
			session_start();
			$_SESSION['alumno'] = $alumno["data"][0];
		}
		echo json_encode($alumno);
		break;
	case 'cambiarpasw':
		session_start();
		if (isset($_SESSION['alumno'])) {//sesion afiliado siscon
			$idusuario=$_SESSION['alumno']['id_afiliado'];
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
		}
		if (isset($_SESSION['usuario'])) {//sesion usuarios moni
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
		if (!isset($_SESSION['alumno'])&&!isset($_SESSION['usuario'])) {
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
	default:
		echo "errorMethod";
		break;
}
//print_r($alumno->consultarTodoAlumnos());


?>
