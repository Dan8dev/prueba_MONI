<?php 
if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../Model/conexion/conexion.php';
	require_once '../Model/acceso/accesoModel.php';
	
	$acc = new Acceso();
	switch ($_POST["action"]) {
		case 'validarLogin':
			unset($_POST["action"]);
			$resp = ["desd"];
			$_POST['inpCorreo'] = trim($_POST['inpCorreo']);
			if((isset($_POST["inpCorreo"]) && $_POST["inpCorreo"] !== "") && (isset($_POST["inpPassw"]) && $_POST["inpPassw"] !== "")){
				$usrAcc = $acc->consultarAcceso($_POST);
				if($usrAcc["estatus"] == "ok" && sizeof($usrAcc["data"]) > 0){
					if(isset($usrAcc['data'][0]['estatus_acceso']) && $usrAcc['data'][0]['estatus_acceso'] == 2){
						echo json_encode(['estatus'=>'error', 'message' => 'Su acceso ha sido bloqueado, contacte al administrador']);
						die();
					}
					session_start();
					$ur = $usrAcc["data"][0];
					# (1-Alumno, 2-Colaborador)
					switch ($ur["idTipo_Persona"]) {
						case 1:
							/*require_once '../Model/alumnoModel.php';
							$alum = new Alumno();
							$alumnData = $alum->consultarAlumno_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($alumnData["data"]) > 0)? $alumnData["data"][0] : "error";
							$ur["directorio"] = "alumnos";*/
							break;
						case 2:
							require_once '../Model/colaboradores/colaboradorModel.php';
							$clbr = new Colaborador();
							$clbrData = $clbr->consultarColaborador_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($clbrData["data"]) > 0)? $clbrData["data"] : "error";
							$ur["directorio"] = "colaboradores";
							break;
						case 3:
							require_once '../Model/marketing/marketingModel.php';
							$mktM = new Marketing();
							$mktData = $mktM->consultarPersonaMktng_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($mktData["data"]) > 0)? $mktData["data"] : "error";
							if($ur["persona"] != 'error'){
								if($_POST["inpCorreo"] != 'master-marketing@mk.com' && $_POST["inpCorreo"] != 'market1@mk.com' && $_POST["inpCorreo"] != 'marketing.educativo.22@gmail.com'){
									$mktM->actualizarFecha_login($ur["idPersona"]);
								}
							}
							$ur["directorio"] = "marketing-educativo";
							break;
						case 4: //Usuario panel plan de pagos
							require_once '../Model/planpagos/planpagosModel.php';
							$PlanPagos = new PlanPagos();
							$planpagosData = $PlanPagos->consultarPlanpagos_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($planpagosData["data"]) > 0)? $planpagosData["data"] : "error";
							$ur["directorio"] = "plan-pagos";
							break;
						case 6: //Usuario panel plan de pagos - CAJA
							require_once '../Model/planpagos/planpagosModel.php';
							$PlanPagos = new PlanPagos();
							$planpagosData = $PlanPagos->consultarPlanpagos_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($planpagosData["data"]) > 0)? $planpagosData["data"] : "error";
							$ur["directorio"] = "plan-pagos/caja.php";
							break;
						case 5:
							require_once '../Model/adminwebex/AdminWebex.php';
							$htl = new AdminWebex();
							$htlData = $htl->consultarAdmin_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($htlData["data"]) > 0)? $htlData["data"] : "error";
							$ur["directorio"] = "admin-webex";
							break;
						case 8:
							require_once '../Model/hoteles/hotelModel.php';
							$htl = new Hotel();
							$htlData = $htl->consultarHoteles_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($htlData["data"]) > 0)? $htlData["data"] : "error";
							$ur["directorio"] = "hoteles";
							break;
						case 9: // USUARIO DE FACTURACION
							require_once '../Model/planpagos/planpagosModel.php';
							$PlanPagos = new PlanPagos();
							$planpagosData = $PlanPagos->consultarPlanpagos_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($planpagosData["data"]) > 0)? $planpagosData["data"] : "error";
							$ur["directorio"] = "facturacion";
							break;
						case 20: // 20=MÉDICO Y/O TUTOR PARA PRÁCTICAS MÉDICAS
							require_once '../Model/alumnos/medicoModel.php';
							$alum = new Medico();
							$alumnData = $alum->consultarMedico_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($alumnData["data"]) > 0)? $alumnData["data"][0] : "error";
							$ur["directorio"] = "pm_medicos";
							break;
												
						case 21: //ALUMNO PARA PRÁCTICAS MÉDICAS
							require_once '../Model/alumnos/alumnoModelPM.php';
							$alum = new AlumnoPM();
							$alumnData = $alum->consultarAlumnoPM_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($alumnData["data"]) > 0)? $alumnData["data"][0] : "error";
							$ur["directorio"] = "alumnos/apm";
							break;
						case 30:
							require_once '../Model/maestros/maestrosModel.php';
							$maestro = new Maestro();
							$maestroData = $maestro->consultarMaestro_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($maestroData["data"]) > 0)? $maestroData["data"] : "error";
							$ur["directorio"] = "maestros";
							break;
						case 31:
							require_once '../Model/controlescolar/controlEscolarModel.php';
							$controlEscolar = new ControlEscolar();
							$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($ur["idPersona"]);
							$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
							if($ur['persona']){
								$ur["directorio"] = "controlescolar";
							}
						break;
						case 35:
							require_once '../Model/controlescolar/controlEscolarModel.php';
							$controlEscolar = new ControlEscolar();
							$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($ur["idPersona"]);
							$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
							if($ur['persona']){
								$ur["directorio"] = "admineducate";
							}	
						break;
						case 32: //Usuario de Estadísticas
							require_once '../Model/estadisticas/estadisticasModel.php';
							$estadisticas = new Estadisticas();
							$estadisticasData = $estadisticas->consultarEstadisticas_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($estadisticasData["data"]) > 0)? $estadisticasData["data"] : "error";
							$ur["directorio"] = "estadisticas";
							break;
						case 35:
							require_once '../Model/controlescolar/controlEscolarModel.php';
							$controlEscolar = new ControlEscolar();
							$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($ur["idPersona"]);
							$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
							if($ur['persona']){
								$ur["directorio"] = "admineducate";
							}
							break;
						case 36:
							require_once '../Model/controlescolar/controlEscolarModel.php';
							$controlEscolar = new ControlEscolar();
							$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($ur["idPersona"]);
							$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
							if($ur['persona']){
								$ur["directorio"] = "areas-medicas";
							}
							break;
						case 22: //ADMIN PARA PRÁCTICAS MÉDICAS
							require_once '../Model/adminpm/adminpmModel.php';
							$adminpm = new AdminPM();
							$adminpmData = $adminpm->consultarAdminPM_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($adminpmData["data"]) > 0)? $adminpmData["data"] : "error";
							$ur["directorio"] = "adminpm";
							break;
						case 34: // USUARIOS REQUISICIONES
							require_once '../Model/planpagos/planpagosModel.php';
							$PlanPagos = new PlanPagos();
							$planpagosData = $PlanPagos->consultarPlanpagos_ById($ur["idPersona"]);
							$ur["persona"] = (sizeof($planpagosData["data"]) > 0)? $planpagosData["data"] : "error";
							if($ur["persona"]!='error'){
								$_SESSION['col_area'] = $ur["persona"]['col_area'];
							}
							$ur["directorio"] = "requisiciones";
							break;

						default:
							$ur["directorio"] = null;
							break;
					}
					if($ur['persona'] != 'error'){
						$_SESSION["usuario"] = $ur;
						$resp = ["estatus"=>"ok", "data"=>$ur];
					}else{
						$resp = ["estatus"=>"error", "data"=>$ur];
					}
				}else{
					$resp = $usrAcc;
				}
			}else{
				$resp = ["estatus"=>"error", "info"=>"faltan_datos", "message"=>"Por favor, complete el formulario."];
			}
			
			echo json_encode($resp);
			break;
		case 'cambiarPass':
			session_start();
			if(trim($_POST['inpPassw']) != trim($_POST['inpPassw_verify'])){
				echo json_encode(['estatus'=>'error', 'info'=>'Las contraseñas no coinciden.']);
				exit;
			}
			$test_current = $acc->consultarAcceso(['inpCorreo'=>$_SESSION['usuario']['correo'], 'inpPassw'=>$_POST['current_pass']]);
			if($test_current['estatus'] == 'error'){
				echo json_encode(['estatus'=>'error', 'info'=>'Su contraseña actual no es correcta.']);
				exit;
			}
			echo json_encode($acc->cambiarPass($_SESSION['usuario']['idAcceso'], trim($_POST['inpPassw'])));
			// echo json_encode(['estatus'=>'ok']);
			// print_r($_SESSION);
			break;
		case 'recuperar_pass':
			$exist = $acc->verify_mail($_POST['mail_recover']);
			if(!$exist){
				echo json_encode(['estatus'=>'error', 'info'=>'El correo proporcionado no existe o ha sido bloqueado para su acceso']);
				die();
			}
			echo json_encode($acc->recover_pass($exist['idAcceso']));
			break;
		case 'comprobar_token':
			$tok = $acc->dec($_POST['token']);
			if(!json_decode($tok, true)){
				echo json_encode(['estatus'=>'error', 'info'=>'token invalido']);
				die();
			}
			$tok = json_decode($tok, true);
			if(strtotime(date('Y-m-d')) > strtotime($tok['fecha'])){
				echo json_encode(['estatus'=>'error', 'info'=>'Tiempo limite alcanzado, esta solicitud ya ha caducado']);
				die();
			}
			echo json_encode(['estatus'=>'ok']);
			// echo $tok;
			break;
		case 'restablecer_pass':
			$tok = $acc->dec($_POST['token']);
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
				echo json_encode($acc->cambiarPass($tok['id'], trim($_POST['inpPassw'])));
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('Location: ../../../index.php');
}
?>
