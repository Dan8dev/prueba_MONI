<?php 
date_default_timezone_set("America/Mexico_City");
	class Colaborador {
		const DIA_CORTE = 5;
		public function insertarColaborador($data){
			$conexion = new Conexion(); # invocar al objeto
			$con = $conexion->conectar(); # recibir el arreglo ['conexion'=>Obj, 'info'=>'ok'||'error']
			$response = [];

			if($con["info"] == "ok"){ # validar el info de la consulta
					#En caso de exito sobreescribe el arreglo con el objeto de conexion
				$con = $con["conexion"];

				$sql = "INSERT INTO `cc_colaboradores`(`idInstitucion`, `nombres`, `apellidoPaterno`, `apellidoMaterno`, `tipo`, `correo`, `password`, `celular`, `codigo`, `estatus`, `fechaRegistro`, `idEmpleado`) VALUES (:idInstitucion, :nombres, :apellidoPaterno, :apellidoMaterno, :tipo, :correo, AES_ENCRYPT(:password,'SistemasPUE21'), :telefono, :codigo, 1, :fechaRegistro, :idEmpleado);"; # se almacena la consulta en un string para poder hacer debug en caso de error

				$statement = $con->prepare($sql); # prepara
				$statement->execute($data);			  # ejecuta

					#si el el array errorInfo del objeto statement retorna un codigo "000000"
				if($statement->errorInfo()[0] == "00000"){
						#Se prepara un arreglo de respuesta con el estatus de la consulta con estatus y la informacion obtenida en un arreglo asociativo
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
						#Se prepara un arreglo de respuesta con el estatus de la consulta en error con la informacion del error de la consulta y la consulta
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		public function consultarTodoColaboradores_ByEstatus($estatus){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT clb.*, inst.nombre as institucion_nombre, acc.correo FROM `cc_colaboradores` clb
				JOIN a_instituciones inst ON inst.id_institucion = clb.idInstitucion
				JOIN a_accesos acc ON acc.idPersona = clb.idColaborador AND acc.idTipo_Persona = 2
				WHERE clb.estatus = :estatus;"; 

				$statement = $con->prepare($sql); 
				$statement->bindParam(":estatus", $estatus);			  
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		public function consultarColaborador_ByInstitucion($institucion){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT cl.*, ac.correo FROM `cc_colaboradores` cl
				INNER JOIN a_accesos ac ON ac.idPersona = cl.idColaborador WHERE cl.idInstitucion = :institucion AND ac.idTipo_Persona = 2;"; 

				$statement = $con->prepare($sql); 
				$statement->bindParam(":institucion", $institucion);			  
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		public function consultarColaborador_Alumnos($colaborador, $tipo){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				if($tipo == 1){
					$sql = "SELECT a.* , a_c.idProspecto, a_c.idColaborador, (SELECT CONCAT(cl.nombres, ' ', cl.apellidoPaterno) FROM cc_colaboradores cl WHERE cl.idColaborador = a_c.idColaborador) AS colabNombre
							FROM cc_alumno_colaborador a_c
							INNER JOIN a_prospectos a
							ON a.idAsistente = a_c.idProspecto
							WHERE a_c.idColaborador IN 
							(SELECT cl.idColaborador FROM `cc_colaboradores` cl WHERE `idInstitucion` = 
								(SELECT cll.idInstitucion FROM `cc_colaboradores` cll WHERE cll.idColaborador = :colaborador)
							)";
				}else{
					$sql = "SELECT a.* , a_c.idProspecto, a_c.idColaborador
							FROM cc_alumno_colaborador a_c
							INNER JOIN a_prospectos a
							ON a.idAsistente = a_c.idProspecto
							WHERE a_c.idColaborador = :colaborador;";
				}

				$statement = $con->prepare($sql); 
				$statement->bindParam(":colaborador", $colaborador);			  
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC), "sql" => $sql];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		public function calcularComisionColaborador($idC, $fecha_i, $fecha_f, $band_corte = 0){
			$CONCEPTO_COMISION_TIPO_1 = "mensualidad";
			$CONCEPTO_COMISION_TIPO_2 = "inscripción";

			$COLABORADOR = $this->consultarColaborador_ById($idC)["data"];
			#validar el tipo de colaborador (1|2) y el concepto del deposito ("inscripcion"|"mensualidad")
			
			if($COLABORADOR["tipo"] == 1){
				$baseCalculo = $CONCEPTO_COMISION_TIPO_1;
			}else if($COLABORADOR["tipo"] == 2){
				$baseCalculo = $CONCEPTO_COMISION_TIPO_2;
			}else{
				$baseCalculo = 'notipo';
			}

			$idsAlumnos = $this->consultarColaborador_Alumnos($idC, $COLABORADOR["tipo"]); // Consultar todos los alumnos del colaborador
			$eCuentaJson = null;
			
			if($idsAlumnos["estatus"] == "ok"){
				$idsAlumnos = $idsAlumnos["data"];
				$desglose_calculo = []; # Estrucutra para almacenar el desglose

				$totalMovimientos = []; # Espacio para almacenar TODOS los movimientos de todos los alumnos
				$count_ids = 0;
				
				$almn = new Alumno();
				# - Obtener el total de movimientos
					for ($h=0; $h < sizeof($idsAlumnos); $h++) { 
						$d = ["alumno"=>$idsAlumnos[$h]["idAsistente"],
							 "fecha_i"=>$fecha_i,
							 "fecha_f"=>$fecha_f
							];
						/*
						 *consultar los depositos del alumno
						 *si band_corte = 1 significa que se consultan las operaciones de
						 *un periodo del cual ya se ha generado su corte 
						 *
						*/
						if($band_corte == 1){
							$d['band_corte'] = true;
						}
						$movs = $almn->consultarAlumnos_Depositos($d); // Consultar todo movimientos alumno en rago fecha
						// die();
						$movs = (($movs["estatus"]=="ok")? $movs["data"] : "errorMovimientos");
						if($movs !== "errorMovimientos"){
							# - Quitar movimientos que no afecter al calculo de la comision
							$movs = $this->purgarMovimientos($movs, $baseCalculo);
							// var_dump($movs);
							$idsAlumnos[$h]["movimientos"] = $movs;
							
							$count_ids += (!empty($movs))? 1 : 0;
							
							$totalMovimientos = array_merge($movs, $totalMovimientos);
						}
					}
				$desglose_calculo["total_alumnos_deposito"] = $count_ids;
				$desglose_calculo["total_comision_calculo"] = 0.0;
				$desglose_calculo["total_Movimientos"] = sizeof($totalMovimientos);
				$desglose_calculo["alumnos"] = [];

				for ($h=0; $h < sizeof($idsAlumnos); $h++) { 
					$desglose_calculo["alumnos"][$h] = $idsAlumnos[$h];
					// var_dump($idsAlumnos[$h]);
					// die();
					for ($i=0; $i < sizeof($idsAlumnos[$h]["movimientos"]); $i++) {
						 # Consultar porcentaje de commision por cada movimiento - alumno - carrera, parametros (tipoColaborador(1|2), id_carrera, numeroMovimientosRealizados)
						$cms = $this->consultarColaborador_Comision($COLABORADOR["tipo"], $idsAlumnos[$h]["movimientos"][$i]["id_carrera"], $count_ids);
						// var_dump($cms);
							#si la consulta del porcentaje no tiene error y no esta vacia
						if($cms["estatus"] == "ok" && !empty($cms["data"])){
							$descripComision = $cms["data"];
						}else{
							$descripComision = "fuera_de_rango";
						}

						#{"alumnos":{ "0":"movimientos":["mv1":{"montopagado":"$", "fecha":"aaaa/mm/dd", "comision":[{"min":1,"max":2,"porcent":"5%"}]} , "mvA":{"montopagado":"$", "fecha":"aaaa/mm/dd", "comision":"fuera_de_rango"}]}}
						$desglose_calculo["alumnos"][$h]["movimientos"][$i]["comision"] = $descripComision;

						if($desglose_calculo["alumnos"][$h]["movimientos"][$i]["comision"] != 'fuera_de_rango'){
							#dscMv = {"id_temp","id_alumno","id_carrera","fecha_deposito","fecha_registro","montopagado","concepto","comision":[0:{"porcentaje"},"monto_u"]}
							$dscMv = $desglose_calculo["alumnos"][$h]["movimientos"][$i];

							$monto_u = ($dscMv["montopagado"] * ($dscMv["comision"][0]["porcentaje"]) / 100);
							
							$desglose_calculo["alumnos"][$h]["movimientos"][$i]["comision"]["monto_u"] = $monto_u;
							// echo json_encode([$desglose_calculo["total_comision_calculo"]]);
							if($desglose_calculo["total_comision_calculo"] !== "fuera_de_rango"){
								$desglose_calculo["total_comision_calculo"] += $monto_u; 
							}
						}else{
							$desglose_calculo["total_comision_calculo"] = 'fuera_de_rango';
							$desglose_calculo["alumnos"][$h]["movimientos"][$i]["comision"] = 'fuera_de_rango';
						}
					}
				}
			}else{
				$desglose_calculo = $idsAlumnos;
			}

			return $desglose_calculo;
		}

			private function purgarMovimientos($arrMovs, $conceptoKeep){
				$nArr = [];
				for ($i=0; $i < sizeof($arrMovs); $i++) { 
					if(strtoupper($arrMovs[$i]["concepto"]) == strtoupper($conceptoKeep)){
						array_push($nArr, $arrMovs[$i]);
					}
				}

				return $nArr;
			}

		public function consultarColaborador_Comision($TipoClb, $idCarrera, $numMov){  /*:tipo, :carrera*/
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT `idComision`, `idCarrera`, `tipoColaborador`, `porcentaje`, `minimo`, `maximo` 
							FROM `cc_comision` WHERE `tipoColaborador` = :tipo AND `idCarrera` = :carrera AND 
							(`minimo` <= :numMov AND `maximo` >= :numMov) AND estatus = 1;"; 

				$statement = $con->prepare($sql);
				$statement->bindParam(":tipo", $TipoClb);
				$statement->bindParam(":carrera", $idCarrera);
				$statement->bindParam(":numMov", $numMov);
				// var_dump($statement, [":tipo" => $TipoClb, ":carrera" => $idCarrera, ":numMov" => $numMov]);
				$statement->execute();  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
					//$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "params"=>[$TipoClb, $idCarrera]];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;			
		}

		public function consultarColaborador_ById($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT clb.`idColaborador`, clb.`idInstitucion`, clb.`nombres`, clb.`apellidoPaterno`, clb.`apellidoMaterno`, clb.`tipo`, clb.`celular`, clb.`codigo`, clb.`estatus`, clb.`fechaRegistro`, clb.`idEmpleado`, acs.correo 
				FROM `cc_colaboradores` clb 
				JOIN a_accesos acs ON acs.idPersona = clb.idColaborador AND acs.idTipo_Persona = 2
				WHERE clb.idColaborador = :id;"; 

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
				#retornar el arreglo construido
			return $response;
		}

		/* public function actualizarColaborador($data){
			
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "UPDATE `cc_colaboradores` SET `nombres`= :nombres, `apellidoPaterno`= :apellidoPaterno, `apellidoMaterno`= :apellidoMaterno, `correo`= :correo, `celular`= :celular WHERE `idColaborador` = :idColaborador;"; 

				$statement = $con->prepare($sql); 
				$statement->execute($data);			  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		} */

		public function validarLogin($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT `idColaborador`, `idInstitucion`, `nombres`, `apellidoPaterno`, `apellidoMaterno`, `tipo`, `correo`, `celular`, `codigo`, `estatus` FROM `cc_colaboradores` WHERE correo = :usr_name AND password = AES_ENCRYPT(:usr_pass, 'SistemasPUE21');";
				
				$statement = $con->prepare($sql);
					# se pasa el arreglo de datos que recibe la funcion
					# $datos = [
					#	'usr_name'=>'email@email.com',
					#	'usr_pass'=>'********'
					#	]
				$statement->execute($datos);


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

		public function obtenerPeriodo($fecha){# aaaa-mm-dd
			$strTime = strtotime($fecha);
			$inicio = null;
			$final = null;
			$cnst = sprintf("%02d", $this::DIA_CORTE);
			$cnst_1d = sprintf("%02d", ($this::DIA_CORTE + 1));
			// $cnst_1d = sprintf("%02d", ($this::DIA_CORTE));

			if(intval(date("d", $strTime)) <= 5){
				$inicio = date("Y-m-d", strtotime("-1 month", strtotime(substr($fecha, 0,7)."-".$cnst_1d)));
				$final = substr($fecha, 0,7)."-".$cnst;
			}else{
				$inicio = substr($fecha, 0,7)."-".$cnst_1d;
				$final = date("Y-m-d", strtotime("+1 month", strtotime(substr($fecha, 0,7)."-".$cnst)));
			}

			return ["inicio"=>$inicio, "final"=>$final];
		}

		function consultarAcceso_ByCorreo($correo){
			$response = [];
			$conexion = new Conexion(); 
			$con = $conexion->conectar()['conexion'];
			$sql = "SELECT * FROM a_accesos WHERE correo = :correo AND estatus_acceso = 1;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':correo', $correo);
			$statement->execute();
			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function validar_codigo($codigo){
			$conexion = new Conexion(); 
			$con = $conexion->conectar()['conexion'];
			return $con->query("SELECT * FROM cc_colaboradores WHERE codigo = '".$codigo."'")->fetch(PDO::FETCH_ASSOC);
		}

		function registrarColaborador($data){
			$response = [];
			$conexion = new Conexion(); 
			$con = $conexion->conectar()['conexion'];
			$correo = $data['inp_Correo'];
			unset($data['inp_Correo']);
			$sql = "INSERT INTO cc_colaboradores (`idInstitucion`, `nombres`, `apellidoPaterno`, `apellidoMaterno`, `tipo`, `celular`, `codigo`, `estatus`, `fechaRegistro`, `idEmpleado`) 
			VALUES (:inp_Institucion, :inp_nombre, :inp_aPaterno, :inp_aMaterno, :inp_Tipo, :inp_telefono, :inp_Codigo, 1, NOW(), :sesion);";
			
			$statement = $con->prepare($sql);
			$statement->execute($data);
			if($statement->errorInfo()[0] == "00000"){
				$id = $con->lastInsertId();
				if($id > 0){
					require_once '../../Model/acceso/keys.php';
					$acces = "INSERT INTO `a_accesos`(`idTipo_Persona`, `idPersona`, `correo`, `contrasenia`, `estatus_acceso`) VALUES (2, {$id}, '{$correo}', AES_ENCRYPT('12345', '{$DECRYPT_PASS}'), 1)";
					$stmt2 = $con->prepare($acces);
					$stmt2->execute();
					if($stmt2->errorInfo()[0] != "00000"){
						echo json_encode($stmt2->errorInfo());
					}
				}
				$response = ["estatus"=>"ok", "data"=>$id];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function actualizarColaborador($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$correo = $data['inp_Correo'];
				// unset($data['inp_Correo']);
				$sql = "UPDATE `cc_colaboradores` SET `idInstitucion` = :inp_Institucion, `nombres` = :inp_nombre, `apellidoPaterno` = :inp_aPaterno, `apellidoMaterno` = :inp_aMaterno, `tipo` = :inp_Tipo, `celular` = :inp_telefono
				 WHERE idColaborador = :user_val;
				UPDATE a_accesos SET correo = :inp_Correo WHERE idPersona = :user_val AND idTipo_Persona = 2;"; 

				$statement = $con->prepare($sql);
				$statement->execute($data);
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}
	}
?>