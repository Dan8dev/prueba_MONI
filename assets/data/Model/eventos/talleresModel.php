<?php 
date_default_timezone_set("America/Mexico_City");
	class Taller {
		public function consultar_asistentes_taller($taller){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ast_t.*, CONCAT(prosp.aPaterno,' ',prosp.aMaterno,' ',prosp.nombre) AS asistente_nom, tall.nombre as taller_nom,
				(SELECT hora FROM asistentes_eventos WHERE id_taller = ast_t.id_taller AND id_asistente = ast_t.id_asistente) AS hora_asistencia
				FROM ev_asistente_talleres ast_t 
				JOIN a_prospectos prosp ON prosp.idAsistente = ast_t.id_asistente
				JOIN ev_talleres tall ON ast_t.id_taller = tall.id_taller
				WHERE ast_t.id_taller = :taller AND ast_t.estatus = 1;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":taller", $taller);
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
		/**
		 * contultar_asistentes_taller_v2 se crea para consultar
		 * asistentes a talleres, saltando la tabla de seleccion de talleres
		 * para el caso de los talleres a los que entran alumnos, no por selección
		 * si no por el tipo de taller que permita acceso por tipo de alumno
		 */
		public function contultar_asistentes_taller_v2($taller){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT CONCAT(prosp.aPaterno,' ',prosp.aMaterno,' ',prosp.nombre) AS asistente_nom,
							tall.nombre as taller_nom,
							ast_e.hora,
							(SELECT hora FROM asistentes_eventos WHERE id_taller = ast_e.id_taller AND id_asistente = ast_e.id_asistente) AS hora_asistencia
							FROM `asistentes_eventos` ast_e
						JOIN a_prospectos prosp ON prosp.idAsistente = ast_e.id_asistente
						JOIN ev_talleres tall ON tall.id_taller = ast_e.id_taller
						WHERE ast_e.id_taller = :taller;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":taller", $taller);
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

		public function consultar_talleres_evento($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT t.*, (SELECT COUNT(*) FROM ev_asistente_talleres WHERE id_taller = t.id_taller AND estatus = 1) AS asistentes
				 FROM ev_talleres t WHERE t.id_evento = :evento;";
				
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
		public function consultar_ponencias_evento($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT t.*
				 FROM ev_ponencias t WHERE t.id_evento = :evento;";
				
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

		public function consultar_todo_asistentes_taller($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ast_t.*, CONCAT(prosp.aPaterno,' ',prosp.aMaterno,' ',prosp.nombre) AS asistente_nom, tall.nombre as taller_nom,
				(SELECT hora FROM asistentes_eventos WHERE id_taller = ast_t.id_taller AND id_asistente = ast_t.id_asistente) AS hora_asistencia
				FROM ev_asistente_talleres ast_t 
				JOIN a_prospectos prosp ON prosp.idAsistente = ast_t.id_asistente
				JOIN ev_talleres tall ON ast_t.id_taller = tall.id_taller
				WHERE tall.id_evento = :evento;";
				
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

		public function consultar_info_taller($taller){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT t.* FROM ev_talleres t WHERE t.id_taller = :taller;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":taller", $taller);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function consultar_info_ponencia($taller){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT t.* FROM ev_ponencias t WHERE t.id_ponencia = :taller;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":taller", $taller);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		

		public function validar_asistencia_taller($alumno, $taller){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT * FROM ev_asistente_talleres WHERE id_taller = :taller AND id_asistente = :alumno;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":taller", $taller);
				$statement->bindParam(":alumno", $alumno);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function actualizar_asistencia($alumno, $taller, $estatus){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$stmt = $con['conexion']->prepare("UPDATE ev_asistente_talleres SET estatus = :estatus WHERE id_taller = :taller AND id_asistente = :alumno;");
			$stat = ($estatus)? 1 : 2;
			$stmt->bindParam(":estatus", $stat);
			$stmt->bindParam(":taller", $taller);
			$stmt->bindParam(":alumno", $alumno);
			$stmt->execute();
			if($stmt->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", 'data'=>$stmt->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$stmt->errorInfo()];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function actualizar_tipos_alumnos($taller, $tipos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$stmt = $con['conexion']->prepare("UPDATE ev_talleres SET tipos_permitidos = :tipos WHERE id_taller = :taller");

			$stmt->bindParam(":tipos", $tipos);
			$stmt->bindParam(":taller", $taller);

			$stmt->execute();
			if($stmt->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", 'data'=>$stmt->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$stmt->errorInfo()];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function actualizar_incluidos_excluidos($taller, $incluir, $excluir){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$stmt = $con['conexion']->prepare("UPDATE ev_talleres SET incluir = :incluir, excluir = :excluir WHERE id_taller = :taller");

			$stmt->bindParam(":taller", $taller);
			$stmt->bindParam(":incluir", $incluir);
			$stmt->bindParam(":excluir", $excluir);

			$stmt->execute();
			if($stmt->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", 'data'=>$stmt->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$stmt->errorInfo()];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function consultar_taller_clave($clave){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			return $con['conexion']->query("SELECT * FROM ev_talleres WHERE clave = '".$clave."';")->fetch(PDO::FETCH_ASSOC);
		}

		public function insertar_taller($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO ev_talleres (id_evento, nombre, clave, evento_privado, cupo, salon, certificado, fecha, costo, plantilla_constancia,nombre_ponente) 
				VALUES (:select_evento_t, :inp_nombre_t, :clave, :select_tipo_t, :inp_cupo_limite, :inp_nombre_salon, :select_ciertifica_t, :inp_fecha_e, :inp_costo_t, '',:ponente);";
				
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		public function insertar_ponencia($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `ev_ponencias`(`id_evento`, `nombre`, `evento_privado`, `cupo`, `salon`, `fecha`, `costo`, `nombre_ponente`) 
				VALUES (:select_evento_po, :inp_nombre_po, :select_tipo_po, :inp_cupo_limite_po, :inp_nombre_salon:po, :inp_fecha_ep, :inp_costo_po,:ponente);";
				
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function editar_taller($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE ev_talleres SET id_evento = :select_evento_t, nombre = :inp_nombre_t, evento_privado = :select_tipo_t, cupo = :inp_cupo_limite, 
				salon = :inp_nombre_salon, certificado = :select_ciertifica_t, fecha = :inp_fecha_e, costo = :inp_costo_t, nombre_ponente = :ponente WHERE id_taller = :inp_id_taller;"; 
				
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		public function editar_ponencia($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE ev_ponencias SET id_evento = :select_evento_po, nombre = :inp_nombre_po, evento_privado = :select_tipo_po, cupo = :inp_cupo_limite, 
				salon = :inp_nombre_salon, fecha = :inp_fecha_e, costo = :inp_costo_po, nombre_ponente = :ponente WHERE id_ponencia = :inp_id_ponencia;"; 
				
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
	}
?>
