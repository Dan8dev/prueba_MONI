<?php 
	date_default_timezone_set("America/Mexico_City");
	class Carrera{
		var $tipo_carreras = [
			"1"=>"Certificación",
			"2"=>"TSU",
			"3"=>"Diplomado",
			"4"=>"Licenciatura",
			"5"=>"Maestría",
			"6"=>"Doctorado",
			"7"=>"Especialidad"
		];

		public function listarCarreras($intitucion = null){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				if($intitucion != null){
					$sql = "SELECT cr.*, ai.nombre AS institucion_nombre FROM `a_carreras` cr
					INNER JOIN a_instituciones ai ON ai.id_institucion = cr.idInstitucion 
					WHERE cr.estatus = 1 AND cr.idInstitucion = :institucion;";
				}else{
					$sql = "SELECT cr.*, ai.nombre AS institucion_nombre FROM `a_carreras` cr
					INNER JOIN a_instituciones ai ON ai.id_institucion = cr.idInstitucion 
					WHERE cr.estatus = 1;";
				}
				
				$statement = $con->prepare($sql);
				if($intitucion != null){
					$statement->bindParam(':institucion', $intitucion);
				}
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$datos = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach($datos as $key_d => $val_d){
						if(in_array($val_d['tipo'], array_keys($this->tipo_carreras))){
							$datos[$key_d]["tipo_carrera_text"] = $this->tipo_carreras[$val_d["tipo"]];
						}else{
							$datos[$key_d]["tipo_carrera_text"] = '';
						}
					}
					$response = ["estatus"=>"ok", "data"=>$datos];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}
		
		public function consultarProspectosCarrera($carrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.*, ma.etapa FROM a_prospectos ap
				INNER JOIN a_marketing_atencion ma ON ma.prospecto = ap.idAsistente
				WHERE ma.evento_carrera = :carrera AND ma.tipo_atencion = 'carrera';";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":carrera", $carrera);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function consultarCarreraByID($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM `a_carreras` WHERE  idCarrera = :id;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":id", $id);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		public function consultarCarreraBy_codigo($clave){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM `a_carreras` WHERE  nombre_clave = :clave;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":clave", $clave);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		
		public function registrar_afiliado_conacon($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			//$datos['contrasenia'] = $this->generar_contrasenia();
			$datos['contrasenia'] = '12345';
			
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				require '../../Model/acceso/keys.php';
				$estatus_string = "";
				$estatus_string_data = "";
				if(isset($datos['estatus'])){$estatus_string = ", estatus";$estatus_string_data = ", :estatus";}

				$curp_string = "";
				$curp_string_data = "";
				if(isset($datos['curp'])){$curp_string = ", curp";$curp_string_data = ", :curp";}
				
				$responsable_string = ['',''];
				if(isset($datos['clinicaResponsable'])){$responsable_string = [', clinicaResponsable', ', :clinicaResponsable'];}

				$pais_nacimiento = ['', '']; if(isset($datos['select_nacionalidad'])){$pais_nacimiento = [', pais_nacimiento', ', :select_nacionalidad'];}
				$grado_academico = ['', '']; if(isset($datos['inp_titulo_estudio'] )){$grado_academico = [', grado_academico', ', :inp_titulo_estudio'];}
				$cedulap = ['','']; if(isset($datos['inp_cedula'] )){$cedulap = [', cedulap', ', :inp_cedula'];}

				$sql = "INSERT INTO `afiliados_conacon`(`nombre`,`id_prospecto`, `apaterno`, `amaterno`, `email`, `contrasenia`, `celular`, `foto` {$estatus_string} {$curp_string} {$responsable_string[0]} {$pais_nacimiento[0]} {$grado_academico[0]} {$cedulap[0]}
				)
				 VALUES (:nombre, :id_prospecto, :aPaterno, :aMaterno, :correo, AES_ENCRYPT(:contrasenia, '{$DECRYPT_PASS}'), :telefono, 'defaultfoto.jpg' {$estatus_string_data} {$curp_string_data} {$responsable_string[1]} {$pais_nacimiento[1]} {$grado_academico[1]} {$cedulap[1]});";
				
				$statement = $con->prepare($sql);
				$statement->execute($datos);


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>['id'=>$con->lastInsertId(), 'contrasenia'=>$datos['contrasenia']]];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
                 public function registrar_prueba_gratis($id_prospecto,$id_evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			$id_concepto=8;
			$detalle_pago='{"id":"00","intent":"CAPTURE","status":"COMPLETED","purchase_units":[{"reference_id":"default","amount":{"currency_code":"MXN","value":"00"},"payee":{"email_address":"pagos@universidaddelconde.edu.mx","merchant_id":"AZUHGK3DWV9NC"},"description":"ACC-GENERAL","soft_descriptor":"PAYPAL *UNIVERSIDAD","shipping":{"name":{"full_name":"- - -"},"address":{"address_line_1":"","address_line_2":"","admin_area_2":"","admin_area_1":"","postal_code":"","country_code":"MX"}},"payments":{"captures":[{"id":"00","status":"COMPLETED","amount":{"currency_code":"MXN","value":"00"},"final_capture":true,"seller_protection":{"status":"ELIGIBLE","dispute_categories":["ITEM_NOT_RECEIVED","UNAUTHORIZED_TRANSACTION"]},"create_time":"0000-00-00T16:36:52Z","update_time":"0000-00-00T16:36:52Z"}]}}],"payer":{"name":{"given_name":"-","surname":"-"},"email_address":"-","payer_id":"-","address":{"country_code":"MX"}},"create_time":"0000-00-00T16:36:52Z","update_time":"0000-00-00T16:36:52Z"}';
			$date = date("Y-m-d H:i:s");
			$mod_date=date("Y-m-d H:i:s",strtotime($date."+ 30 days"));
			$vencepago=$mod_date;

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "INSERT INTO a_pagos(id_prospecto,id_concepto,id_evento ,detalle_pago,vencepago) 
						VALUES (:id_prospecto,:id_concepto,:id_evento,:detalle_pago,:vencepago)";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":id_prospecto", $id_prospecto);
				$stmt->bindParam(":id_concepto", $id_concepto);
				$stmt->bindParam(":id_evento", $id_evento);
				$stmt->bindParam(":detalle_pago", $detalle_pago);
				$stmt->bindParam(":vencepago", $vencepago);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$stmt->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}
		
		public function validar_correo_afiliado($correo){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM `afiliados_conacon` WHERE  email = :correo;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":correo", $correo);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function validarCorreo_Carrera($correo, $carrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.* FROM `a_prospectos` ap JOIN a_marketing_atencion aten ON ap.idAsistente = aten.prospecto WHERE aten.tipo_atencion = 'carrera' AND aten.`evento_carrera` = :carrera AND ap.`correo` = :correo;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":correo",	$correo);
				$statement->bindParam(":carrera",	$carrera);

				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function obtenercontrasena($email){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT ac.id_afiliado, ac.nombre, ac.apaterno, ac.amaterno,AES_DECRYPT(ac.contrasenia, 'SistemasPUE21') as contrasena 
				FROM afiliados_conacon ac 
				JOIN a_prospectos as ap on ac.id_prospecto=ap.idAsistente
				WHERE email = :email
				LIMIT 1;";
				$statement = $con->prepare($sql);

				$statement->bindParam(":email", $email, PDO::PARAM_STR);
				
				$statement->execute();
				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}
		
		function generar_contrasenia(){
			$caracteres = '0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$caractereslong = strlen($caracteres);
			$clave = '';
			for($i = 0; $i < 4; $i++) {
				$clave .= $caracteres[rand(0, $caractereslong - 1)];
			}
			return $clave;
		}

   		public function validar_registro_carrera($prospecto, $carrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT * FROM a_marketing_atencion WHERE prospecto = :prospecto AND evento_carrera = :carrera AND tipo_atencion = 'carrera';";
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto);
				$statement->bindParam(":carrera", $carrera);
				
				$statement->execute();
				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function validar_registro_evento($prospecto, $carrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT * FROM a_marketing_atencion WHERE prospecto = :prospecto AND evento_carrera = :carrera AND tipo_atencion = 'evento';";
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto);
				$statement->bindParam(":carrera", $carrera);
				
				$statement->execute();
				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}


		function consultar_inscripciones_afiliado($alumno, $tipo){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$tabla = "";
				$campo = "";
				if($tipo == "carrera"){
					$tabla = "a_carreras";
					$campo = "idCarrera";
				}else if($tipo == "evento"){
					$tabla = "ev_evento";
					$campo = "idEvento";
				}

				if($tabla != ''){
					$sql = "SELECT mk_aten.*, tab.* FROM a_marketing_atencion mk_aten 
						JOIN {$tabla} tab ON tab.{$campo} = mk_aten.evento_carrera
						WHERE mk_aten.prospecto = :alumno AND mk_aten.tipo_atencion = :tipo;";
					$statement = $con->prepare($sql);
					
					$statement->bindParam(":alumno", $alumno);
					$statement->bindParam(":tipo", $tipo);
					$statement->execute();

					if($statement->errorInfo()[0] == 00000){
						$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>"No se encontro la referencia"];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function insertar_generacion_default($alumno, $generacion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$con->query("INSERT INTO alumnos_generaciones (idalumno, idgeneracion, fecha_inscripcion) VALUES ({$alumno}, {$generacion}, NOW());");
		}
		function insertar_vista_cursos_default($alumno, $vista, $estatus){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$con->query("INSERT INTO vistas_afiliados (idAfiliado, vista, estatus) VALUES ({$alumno}, {$vista}, {$estatus});");
		}
		function insertar_membresia_gratis($idProspecto, $vencemembresia){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$con->query("INSERT INTO pagos_membresias (idProspecto, idMembresia, vencemembresia, montopagado) VALUES ({$idProspecto}, {'1'}, {$vencemembresia}, {'0'});");
		}
	}
?>
