<?php 
date_default_timezone_set("America/Mexico_City");
	class Evento {
		public function consultarEvento_Clave($clave){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT evt.*, pa.ISO3 as pais_nom,est.Estado as estado_nom , (SELECT COUNT(*) FROM a_prospectos ea INNER JOIN a_marketing_atencion ma ON ma.prospecto = ea.idAsistente WHERE ma.tipo_atencion = 'evento' AND ma.evento_carrera = evt.idEvento AND ma.etapa IN (2, 0, 1)) AS numAsistentes
				 FROM `ev_evento` evt
                 INNER JOIN paises pa ON evt.pais = pa.IDPais
                 INNER JOIN estados est ON evt.estado = est.IDEstado
                 WHERE evt.`nombreClave` LIKE :nombreCl AND evt.`estatus` = 1;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":nombreCl", $clave);
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

		public function consultarEvento_Id($clave){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT evt.*, pa.ISO3 as pais_nom,est.Estado as estado_nom, (SELECT COUNT(*) FROM a_prospectos ea INNER JOIN a_marketing_atencion ma ON ma.prospecto = ea.idAsistente WHERE ma.tipo_atencion = 'evento' AND ma.evento_carrera = evt.idEvento AND ma.etapa IN (2, 0, 1)) AS numAsistentes
				 FROM `ev_evento` evt
                 INNER JOIN paises pa ON evt.pais = pa.IDPais
                 INNER JOIN estados est ON evt.estado = est.IDEstado
				 WHERE evt.`idEvento` = :evento AND evt.`estatus` = 1;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":evento", $clave);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$evento = $statement->fetch(PDO::FETCH_ASSOC);
					if($evento){
						# crear un arreglo con las palabras que forman el titulo del evento
						$arStr = explode(" ", $evento["titulo"]);
						# funcion para convertir el arreglo de palabras en un arreglo con las primeras letras
						# de cada palabra
						$primL = function($item){ return strtoupper(substr($item, 0,1));};
						$arStr = array_map($primL, $arStr);
						$evento['codigo_prospectos'] = implode("", $arStr);
						$evento['next_id'] = $this->consultarUltimoAsistenteID()['data'][0]['Auto_increment'];
					}
					$response = ["estatus"=>"ok", "data"=>$evento];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}	
		
		public function consultarAsistentesEvento($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ea.*, ma.etapa FROM a_prospectos ea
				INNER JOIN a_marketing_atencion ma ON ma.prospecto = ea.idAsistente
				WHERE ma.evento_carrera = :evento AND ma.tipo_atencion = 'evento';";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":evento", $evento);
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
		
		public function consultarUltimoAsistenteID(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SHOW TABLE STATUS FROM `{$conexion->dbname}` WHERE `name` LIKE 'a_prospectos';";
				$statement = $con->prepare($sql);
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
		public function fechaseventos(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT titulo, fechaDisponible, fechaLimite FROM ev_evento WHERE estatus = 1;";
				$statement = $con->prepare($sql);
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


		public function listarEventos($estatus = 3){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$filtro = "";
				if($estatus == 3){
					$filtro = "AND WEEK(ev.fechaE) = WEEK(CURDATE()) AND ev.fechaE >= DATE(NOW())";
					$estatus = 1;
				}		

				$sql = "SELECT ev.* FROM ev_evento AS ev WHERE ev.estatus = :estatus {$filtro};";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':estatus',$estatus);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$datas = $statement->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($datas as $key => $data){
						if($data['tipo'] == "CONGRESO" && strpos($data['nombreClave'], 'congreso_medicina') !== false && $data['idInstitucion'] == 19){
							$new_cong = $datas[$key];
							$new_cong['idInstitucion'] = '20';
							$datas[] = $new_cong;
						}
					}
					
					if($datas == []){
						if($filtro != ''){
							$filtro = "AND WEEK(ev.fechaE) = WEEK(CURDATE())+1 AND ev.fechaE >= NOW()";
						}		
						$sql = "SELECT ev.* FROM ev_evento AS ev WHERE ev.estatus = :estatus {$filtro};";
						
						$statement1 = $con->prepare($sql);
						$statement1->bindParam(':estatus',$estatus);
						$statement1->execute();

						if($statement1->errorInfo()[0] == "00000"){
							$datas = $statement1->fetchAll(PDO::FETCH_ASSOC);
						}
					}

					$response = ["estatus"=>"ok", "data"=>$datas];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}

		public function consultar_evento_clave_no_estatus($clave){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT evt.* FROM `ev_evento` evt WHERE evt.`nombreClave` LIKE :nombreCl;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':nombreCl',$clave);
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

		public function talleres_eventos($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT e.*,(SELECT COUNT(*) FROM ev_asistente_talleres et WHERE et.id_taller = e.id_taller) AS ocupados FROM ev_talleres e WHERE e.id_evento = :evento ";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":evento", $evento);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function consultar_taller_clave($nombreCl){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM ev_talleres WHERE clave LIKE :nombreCl;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":nombreCl", $nombreCl);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function validarCorreo_Evento($correo, $evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.* FROM `a_prospectos` ap JOIN a_marketing_atencion aten ON ap.idAsistente = aten.prospecto WHERE aten.tipo_atencion = 'evento' AND aten.`evento_carrera` = :evento AND ap.`correo` = :correo;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":correo",$correo);
				$statement->bindParam(":evento",$evento);

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

		// agregado para congreso medicina
		public function asistente_talleres_reservados($asistente){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT asist_t.*, tall.nombre FROM ev_asistente_talleres asist_t 
				JOIN ev_talleres tall ON asist_t.id_taller = tall.id_taller
				WHERE asist_t.id_asistente = :asistente;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":asistente", $asistente);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function validar_registro_evento($prospecto, $evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con['conexion'];
			$inscrito = $con->query("SELECT * FROM a_marketing_atencion WHERE tipo_atencion = 'evento' AND prospecto = ".$prospecto." AND evento_carrera = ".$evento.";")->fetch();
			$conteo = $con->query("SELECT COUNT(*) as inscritos FROM a_marketing_atencion WHERE tipo_atencion = 'evento' AND evento_carrera = ".$evento.";")->fetch();
			return [$inscrito, $conteo];
		}

		public function get_talleres_prospecto($prospecto, $evento, $dia){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT ast_t.*, tall.*, (SELECT hora FROM asistentes_eventos WHERE id_asistente = :prospecto AND id_taller = tall.id_taller LIMIT 1) as asistido FROM `ev_asistente_talleres` ast_t 
				JOIN ev_talleres tall ON tall.id_taller = ast_t.id_taller
				WHERE ast_t.id_asistente = :prospecto AND tall.id_evento = :evento AND ast_t.estatus = 1 AND DAY(tall.fecha) = :dia ;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":prospecto", $prospecto);
				$statement->bindParam(":evento", $evento);
				$statement->bindParam(":dia", $dia);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$eventos = $statement->fetchAll(PDO::FETCH_ASSOC);
					
					$response = ["estatus"=>"ok", "data"=>$eventos];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function apartar_talleres($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO ev_asistente_talleres (id_asistente, id_taller, fecha_registro)
				VALUES (:prospecto, :taller, :fecha); ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		
		function listaAsistenciasGeneral($evento){
			$conex = new Conexion();
			$con = $conex->conectar()['conexion'];
			$response = [];
			$sql = "SELECT afc.foto,ea.id, ea.id_asistente, ea.id_evento, ea.hora, CONCAT(pr.nombre, ' ', pr.aPaterno, ' ', pr.aMaterno) as fullname, pr.nombre, pr.aPaterno, pr.aMaterno, pr.correo, pr.telefono, pr.Genero FROM `asistentes_eventos` ea 
				JOIN a_prospectos pr ON pr.idAsistente = ea.id_asistente
				JOIN afiliados_conacon as afc on afc.id_prospecto = pr.idAsistente
				WHERE ea.id_evento = :evento GROUP BY ea.id_asistente  
				ORDER BY ea.`hora`  ASC";
			$statement = $con->prepare($sql);
			$statement->bindParam(':evento', $evento);
			$statement->execute();
			if($statement->errorInfo()[0] == '00000'){
				$response = ['estatus'=>'ok', 'data' => $statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>'internal error'];
			}
			return $response;
		}

		function AsistenciasPersona_evento($evento, $persona, $taller = false){
			$conex = new Conexion();
			$con = $conex->conectar()['conexion'];
			$response = [];
			$asist_t = '';
			if(!$taller){
				$asist_t = 'AND ea.id_taller IS NULL';
			}else{
				$asist_t = 'AND ea.id_taller IS NOT NULL';
			}
			$sql = "SELECT ea.id, ea.id_asistente, ea.id_evento, ea.hora, date(ea.hora) as fecha, ea.nombre_reconocimiento, ea.id_taller FROM `asistentes_eventos` ea 
				JOIN a_prospectos pr ON pr.idAsistente = ea.id_asistente
				WHERE ea.id_evento = :evento AND ea.id_asistente = :persona {$asist_t}";
			$statement = $con->prepare($sql);
			$statement->bindParam(':evento', $evento);
			$statement->bindParam(':persona', $persona);
			$statement->execute();
			if($statement->errorInfo()[0] == '00000'){
				$response = ['estatus'=>'ok', 'data' => $statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>'internal error'];
			}
			return $response;
		}

		function validar_evento_de_pago($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con["conexion"];
			$sql = "SELECT * FROM planes_pagos WHERE idEvento = :evento AND activo = 1;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':evento', $evento);
			$statement->execute();

			return $statement->fetch(PDO::FETCH_ASSOC);
		}
		
		function validar_pago_evento($evento, $prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con["conexion"];
			$sql = "SELECT pgs.* FROM `a_pagos` pgs
			JOIN pagos_conceptos con ON con.id_concepto = pgs.id_concepto
			JOIN planes_pagos plan ON plan.idPlanPago = con.idPlan_pago
			WHERE plan.idEvento = :evento AND pgs.id_prospecto = :prospecto AND pgs.estatus = 'verificado' ORDER BY `pgs`.`restante` ASC;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':evento', $evento);
			$statement->bindParam(':prospecto', $prospecto);
			$statement->execute();

			return $statement->fetch(PDO::FETCH_ASSOC);
		}

		function validar_evento_paquetes($prospecto, $id_concepto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con["conexion"];
			$sql = "SELECT pgs.* FROM `a_pagos` pgs
			JOIN promociones promo ON promo.idPromocion = pgs.idPromocion
			JOIN promo_ofertas po ON po.id_oferta = promo.id_oferta
			WHERE pgs.id_prospecto = :prospecto AND pgs.estatus = 'verificado' AND po.conceptospaquete = $id_concepto ORDER BY `pgs`.`restante` ASC;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':evento', $evento);
			$statement->bindParam(':prospecto', $prospecto);
			$statement->execute();

			return $statement->fetch(PDO::FETCH_ASSOC);
		}
	}
?>
