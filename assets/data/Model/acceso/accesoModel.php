<?php 
date_default_timezone_set("America/Mexico_City");
	class Acceso {
		public function consultarAcceso($data){ 
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [""];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				require 'keys.php';
				# Tipo Persona (1-Alumno, 2-Colaborador)
				$sql = "SELECT `idAcceso`, `idTipo_Persona`, `idPersona`, `correo`, `estatus_acceso` FROM `a_accesos` WHERE `correo` = :inpCorreo AND AES_DECRYPT(`contrasenia`, '{$DECRYPT_PASS}') = :inpPassw;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
				
				if($statement->errorInfo()[0] == '00000'){
					if($statement->rowCount() > 0){
						$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
					}else{
						$response = ["estatus"=>"error", "info"=>"no_coincidencias", "message"=>"Usuario o password incorrecto, verifique."];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error", "info"=>"error_conexion"];
			}
			$conexion = null;
			$con = null;
			
			return $response;
		}

		function cambiarPass($accesId, $newpass){
			$response = [];
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			require 'keys.php';
			$sql = "UPDATE a_accesos SET contrasenia = AES_ENCRYPT(:newpass, '{$DECRYPT_PASS}') WHERE idAcceso = :acceso;";

			$stmt = $con->prepare($sql);
			$stmt->bindParam(':newpass', $newpass);
			$stmt->bindParam(':acceso', $accesId);
			$stmt->execute();
			
			if($stmt->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info" =>'Error al actualizar' , "detalle"=>$stmt->errorInfo()];
			}
			return $response;
		}

		public function verify_mail($email){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT correo, idAcceso FROM a_accesos WHERE correo = :correo AND estatus_acceso = 1;";
			$stmt = $con->prepare($sql);
			$stmt->bindParam(':correo', $email);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}

		public function recover_pass($accesoId){
			$token_enc = $this->enc(json_encode(['id'=>$accesoId, 'fecha'=>date('Y-m-d')]));
			require_once("../functions/correos_prospectos.php");
			$asunto = "RECUPERACIÓN DE CONTRASEÑA";
			$acs = $this->get_accesoById($accesoId);
			$info = $this->getInfo_ByTipo($acs['data']['idTipo_Persona'], $acs['data']['idPersona']);
			$nombre = '';
			$nombre = isset($info['persona']['nombre']) ? $info['persona']['nombre'] : $nombre;
			$nombre = isset($info['persona']['Nombre']) ? $info['persona']['Nombre'] : $nombre;
			$nombre = isset($info['persona']['nombres']) ? $info['persona']['nombres'] : $nombre;
			$nombre = isset($info['persona']['Nombres']) ? $info['persona']['Nombres'] : $nombre;
			$correo = $acs['data']['correo'];
			$token_enc = urlencode($token_enc);
			$link = "https://moni.com.mx/recovery_verify.php?token=".$token_enc;
			// $link = urlencode($link);
			$cuerpo = '<p>Hemos recibido su solicitud de recuperación de contraseña.</p><p>Para poder continuar con el proceso le solicitamos que de clic en el siguiente enlace:</p><a href="'.$link.'" target="_blank">Cambiar contraseña</a><p>Si no ha sido usted quien solicita el cambio, por favor hacer caso omiso.</p>';

			$destinatarios = [[$correo, $nombre]];
			$plantilla_c = 'plantilla_interno.html';

			$claves = ['%%TITULO', '%%NOMBRE', '%%TODOCONTENIDO'];
			$valores = [$asunto, $nombre, $cuerpo];
			$enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
			if($enviar['1'] == 'Mensaje enviado'){
				return ['estatus'=>'ok'];
			}else{
				return ['estatus'=>'error', 'info' => 'Ocurrió un error al intentar enviar mail al correo '.$correo];
			}
		}

		public function enc($text){
			$simple_string = $text;
			// Store the cipher method
			$ciphering = "AES-128-CTR";
			// Use OpenSSl Encryption method
			$iv_length = openssl_cipher_iv_length($ciphering);
			$options = 0;
			// Non-NULL Initialization Vector for encryption
			$encryption_iv = '1234567891011121';
			// Store the encryption key
			$encryption_key = "SistemasPUE21";
			// Use openssl_encrypt() function to encrypt the data
			$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
			return $encryption;
		}

		public function dec($token){
			$ciphering = "AES-128-CTR";
			// Non-NULL Initialization Vector for decryption
			$decryption_iv = '1234567891011121';
			// Store the decryption key
			$decryption_key = "SistemasPUE21";
			// Use openssl_decrypt() function to decrypt the data
			$options = 0;
			$decryption=openssl_decrypt($token, $ciphering, $decryption_key, $options, $decryption_iv);
			
			return $decryption;
		}

		function getInfo_ByTipo($tipo, $persona){
			$ur = [];
			switch ($tipo) {
				case 2:
					require_once '../Model/colaboradores/colaboradorModel.php';
					$clbr = new Colaborador();
					$clbrData = $clbr->consultarColaborador_ById($persona);
					$ur["persona"] = (sizeof($clbrData["data"]) > 0)? $clbrData["data"] : "error";
					$ur["directorio"] = "colaboradores";
					break;
				case 3:
					require_once '../Model/marketing/marketingModel.php';
					$mktM = new Marketing();
					$mktData = $mktM->consultarPersonaMktng_ById($persona);
					$ur["persona"] = (sizeof($mktData["data"]) > 0)? $mktData["data"] : "error";
					$ur["directorio"] = "marketing-educativo";
					break;
				case 4: //Usuario panel plan de pagos
					require_once '../Model/planpagos/planpagosModel.php';
					$PlanPagos = new PlanPagos();
					$planpagosData = $PlanPagos->consultarPlanpagos_ById($persona);
					$ur["persona"] = (sizeof($planpagosData["data"]) > 0)? $planpagosData["data"] : "error";
					$ur["directorio"] = "plan-pagos";
					break;
				case 5:
					require_once '../Model/adminwebex/AdminWebex.php';
					$htl = new AdminWebex();
					$htlData = $htl->consultarAdmin_ById($persona);
					$ur["persona"] = (sizeof($htlData["data"]) > 0)? $htlData["data"] : "error";
					$ur["directorio"] = "admin-webex";
					break;
				case 8:
					require_once '../Model/hoteles/hotelModel.php';
					$htl = new Hotel();
					$htlData = $htl->consultarHoteles_ById($persona);
					$ur["persona"] = (sizeof($htlData["data"]) > 0)? $htlData["data"] : "error";
					$ur["directorio"] = "hoteles";
					break;
				case 9: // USUARIO DE FACTURACION
					require_once '../Model/planpagos/planpagosModel.php';
					$PlanPagos = new PlanPagos();
					$planpagosData = $PlanPagos->consultarPlanpagos_ById($persona);
					$ur["persona"] = (sizeof($planpagosData["data"]) > 0)? $planpagosData["data"] : "error";
					$ur["directorio"] = "facturacion";
					break;
				case 20: // 20=MÉDICO Y/O TUTOR PARA PRÁCTICAS MÉDICAS
					require_once '../Model/alumnos/medicoModel.php';
					$alum = new Medico();
					$alumnData = $alum->consultarMedico_ById($persona);
					$ur["persona"] = (sizeof($alumnData["data"]) > 0)? $alumnData["data"][0] : "error";
					$ur["directorio"] = "pm_medicos";
					break;
										
				case 21: //ALUMNO PARA PRÁCTICAS MÉDICAS
					require_once '../Model/alumnos/alumnoModelPM.php';
					$alum = new AlumnoPM();
					$alumnData = $alum->consultarAlumnoPM_ById($persona);
					$ur["persona"] = (sizeof($alumnData["data"]) > 0)? $alumnData["data"][0] : "error";
					$ur["directorio"] = "alumnos/apm";
					break;
				case 30:
					require_once '../Model/maestros/maestrosModel.php';
					$maestro = new Maestro();
					$maestroData = $maestro->consultarMaestro_ById($persona);
					$ur["persona"] = (sizeof($maestroData["data"]) > 0)? $maestroData["data"] : "error";
					$ur["directorio"] = "maestros";
					break;
				case 31:
					require_once '../Model/controlescolar/controlEscolarModel.php';
					$controlEscolar = new ControlEscolar();
					$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($persona);
					$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
					if($ur['persona']){
						$ur["directorio"] = "controlescolar";
					}
				break;
				case 35:
					require_once '../Model/controlescolar/controlEscolarModel.php';
					$controlEscolar = new ControlEscolar();
					$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($persona);
					$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
					if($ur['persona']){
						$ur["directorio"] = "admineducate";
					}	
				break;
				case 32: //Usuario de Estadísticas
					require_once '../Model/estadisticas/estadisticasModel.php';
					$estadisticas = new Estadisticas();
					$estadisticasData = $estadisticas->consultarEstadisticas_ById($persona);
					$ur["persona"] = (sizeof($estadisticasData["data"]) > 0)? $estadisticasData["data"] : "error";
					$ur["directorio"] = "estadisticas";
					break;
				case 35:
					require_once '../Model/controlescolar/controlEscolarModel.php';
					$controlEscolar = new ControlEscolar();
					$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($persona);
					$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
					if($ur['persona']){
						$ur["directorio"] = "admineducate";
					}
					break;
					case 36:
						require_once '../Model/controlescolar/controlEscolarModel.php';
						$controlEscolar = new ControlEscolar();
						$controlEscolarData = $controlEscolar->consultarControlEscolar_ById($persona);
						$ur["persona"] = ($controlEscolarData["data"])? $controlEscolarData["data"] : "error";
						if($ur['persona']){
							$ur["directorio"] = "areas-medicas";
						}
						break;
				case 22: //ADMIN PARA PRÁCTICAS MÉDICAS
					require_once '../Model/adminpm/adminpmModel.php';
					$adminpm = new AdminPM();
					$adminpmData = $adminpm->consultarAdminPM_ById($persona);
					$ur["persona"] = (sizeof($adminpmData["data"]) > 0)? $adminpmData["data"] : "error";
					$ur["directorio"] = "adminpm";
					break;
				case 34: // USUARIOS REQUISICIONES
					require_once '../Model/planpagos/planpagosModel.php';
					$PlanPagos = new PlanPagos();
					$planpagosData = $PlanPagos->consultarPlanpagos_ById($persona);
					$ur["persona"] = (sizeof($planpagosData["data"]) > 0)? $planpagosData["data"] : "error";
					$_SESSION['col_area'] = $ur["persona"]['col_area'];
					$ur["directorio"] = "requisiciones";
					break;
				default:
					$ur["directorio"] = null;
					break;
			}
			return $ur;
		}

		function get_accesoById($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [""];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				require 'keys.php';
				# Tipo Persona (1-Alumno, 2-Colaborador)
				$sql = "SELECT `idAcceso`, `idTipo_Persona`, `idPersona`, `correo`, `estatus_acceso` FROM `a_accesos` WHERE `idAcceso` = :id;";

				$statement = $con->prepare($sql);
				$statement->bindParam(':id', $id);
				$statement->execute();
				
				if($statement->errorInfo()[0] == '00000'){
					if($statement->rowCount() > 0){
						$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
					}else{
						$response = ["estatus"=>"error", "info"=>"no_coincidencias", "message"=>"Usuario o password incorrecto, verifique."];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error", "info"=>"error_conexion"];
			}
			$conexion = null;
			$con = null;
			
			return $response;
		}

		function get_info_maestro_correo($correo){
			$conexion = new Conexion();
			$con = $conexion->conectar()["conexion"];
			$response = [];
			$sql = "SELECT * FROM a_accesos acc
			JOIN maestros mst ON mst.id = acc.idPersona AND acc.idTipo_Persona = 30
			WHERE mst.email = :correo";
			$statement = $con->prepare($sql);
			$statement->bindParam(':correo', $correo);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
	}
?>
