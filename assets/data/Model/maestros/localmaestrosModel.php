<?php 
date_default_timezone_set("America/Mexico_City");
	class Maestro{
		// funciones login
		public function consultarMaestro_ById($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = 
				"SELECT * FROM maestros WHERE id = :id;";
				//echo "****";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':id', $id);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;	
		}
		public function obtener_sesion_webexota($idsesion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT contrasena_sesion
						FROM accesos_sesion_webex
					WHERE id_sesion =:id_sesion;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":id_sesion", $idsesion);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		public function actualizarFecha_login($persona){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$fecha_f = date('Y-m-d H:i:s');
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = 
				"UPDATE a_marketing_personal SET sesion = :fecha_t WHERE idPersona = :id;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':id', $persona);
				$statement->bindParam(':fecha_t', $fecha_f);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		public function cerrarSesion($persona){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$fecha_f = date('Y-m-d H:i:s');
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = 
				"UPDATE a_marketing_personal SET sesion = '0000-00-00 00:00:00' WHERE idPersona = :id;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':id', $persona);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		// funciones panel master-merketing
		public function consultar_todo_ejecutivas(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$fecha = date("Y-m-d");
				$sql = 
				"SELECT mp.*, ac.correo FROM `a_marketing_personal` mp
					INNER JOIN a_accesos ac ON ac.idPersona = mp.idPersona
					WHERE mp.estatus = 1 AND ac.idTipo_Persona = 3;;";
				
				$statement = $con->prepare($sql);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
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

		// funciones carrusel
			# consultar prospectos no asignados a una fila de atencion
		public function atender_listado_eventos(){
			/*
			 * consultar listado de prospectos a eventos y prospectos a carreras
			*/
			$porospM = new Prospecto();

			# conseguir prospectos a eventos que no han sido atendidos 
			$eventos_pers_pendientes = $porospM->prospectos_seguimiento(false, 'evento')['data'];
			$carreras_pers_pendientes = $porospM->prospectos_seguimiento(false, 'carrera')['data'];

			$success = [];
			$error = [];
			for ($i=0; $i < sizeof($eventos_pers_pendientes); $i++) { 
				// a cada prospecto de evento no asignado se asigna a una persona de marketing disponible
				$asigna = $this->actualzar_fila_atencion($eventos_pers_pendientes[$i]['idAsistente'], 'evento');
				if($asigna['estatus'] == 'ok'){
					array_push($success, $asigna['data']);
				}else{
					array_push($error, $asigna);
				}
			}
			for ($i=0; $i < sizeof($carreras_pers_pendientes); $i++) { 
				// a cada prospecto de evento no asignado se asigna a una persona de marketing disponible
				if($carreras_pers_pendientes[$i]['idCarrera'] != 3){
					$asigna = $this->actualzar_fila_atencion($carreras_pers_pendientes[$i]['idAsistente'], 'carrera');
					if($asigna['estatus'] == 'ok'){
						array_push($success, $asigna['data']);
					}else{
						array_push($error, $asigna);
					}
				}
			}

			return ['oks'=>$success,'errors'=>$error];
		}
			# consultar numero de prospectos asignados a cada ejecutiva para su seguimiento SOLO SI ESTAN LOGUEADAS
		public function consultarTodoPersonal_listaAtencion(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$fecha = date("Y-m-d");
				$sql = 
				"SELECT mp.*, 
				(SELECT COUNT(*) FROM a_marketing_atencion ma WHERE ma.idMk_persona = mp.idPersona AND ma.etapa IN (0)) AS filaAtencion 
					FROM `a_marketing_personal` mp WHERE mp.estatus = 1 AND DATE(`sesion`) = :fecha ;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':fecha', $fecha);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
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
			# funcion para el recorrido de personas y revision de asignacion de prospectos
		public function actualzar_fila_atencion($entrante, $tipo = ''){
			$resp = ['empty'];
			$todo_personas = $this->consultarTodoPersonal_listaAtencion();
			if($todo_personas['estatus']=='ok' && !empty($todo_personas['data'])){
				$todo_personas = $todo_personas['data'];	
				$max = array_reduce($todo_personas, function($acc, $item){return ($acc < $item["filaAtencion"] && $item["idPersona"] != 2)? $item['filaAtencion'] : $acc;}, 0);
				$min = array_reduce($todo_personas, function($acc, $item){return ($acc > $item["filaAtencion"] && $item["idPersona"] != 2)? $item['filaAtencion'] : $acc;}, $max);

				$entrante_asignado = false;
				$co = 0;
				$asignacion = [];
				$error = [];
				while (!$entrante_asignado && $co < sizeof($todo_personas)) {
					// si el tamanio de la fila de pendientes de la persona $co es igual al tamanio minimo se le asigna un nuevo pendiente
					// $todo_personas es el arreglo de todas las ejecutivas
					// si el id de la persona es distinto de 2 se le asigna un prospecto
					//echo $todo_personas[$co]["filaAtencion"]." - ".$todo_personas[$co]["idPersona"]." [".$min."]\n";
					if($todo_personas[$co]["filaAtencion"] == $min && $todo_personas[$co]["idPersona"] != 2){
						$asignar = $this->set_prospecto_fila($tipo, $entrante, $todo_personas[$co]['idPersona']);
						if($asignar["estatus"] == "ok"){
							$asignacion['id_relacion'] = $asignar["data"];
							$asignacion['persona_seguimiento'] = [$todo_personas[$co]['idPersona'], $todo_personas[$co]['nombres']];
							$entrante_asignado = true;
						}else{
							array_push($error, $asignar);
						}
					}
					$co++;
				}

				$resp = [];
				if($entrante_asignado){
					$resp = ['estatus' => 'ok', 'data'=>$asignacion];
				}else{
					$resp = ['estatus' => 'error', 'info'=>$error];
				}
			}else{
				if($todo_personas['estatus'] == 'error'){
					$resp = $todo_personas;
				}else{
					$resp = ['estatus' => 'error', 'info'=>'no_usuarios_disponibles'];
				}
			}
			return $resp;
		}
			# crear relacion de personal marketing y prospecto
		public function set_prospecto_fila($tipo, $id_propspecto, $id_mkt_persona){
			$resp = [];
			$tabla = '';
			switch (strtoupper($tipo)) {
				case 'EVENTO':
					$tabla = 'evento';
					break;
				case 'CARRERA':
					$tabla = 'carrera';
					break;
				default:
					$tabla = '';
					break;
			}

			if($tabla != ''){
				$con = new Conexion();
				$con = $con->conectar()['conexion'];
				$fecha = date("Y-m-d H:i:s");
				$sql = "INSERT INTO a_marketing_atencion (idMk_persona, tipo_atencion, prospecto, etapa, seguimiento) 
				VALUES (:persona, :tipo, :prospecto, 0, :fecha);
				UPDATE `a_marketing_personal` SET `fila` = (`fila` + 1) WHERE `a_marketing_personal`.`idPersona` = :persona;";

				$statement = $con->prepare($sql);
				
				$statement->bindParam(':persona', $id_mkt_persona);
				$statement->bindParam(':tipo', $tabla);
				$statement->bindParam(':prospecto', $id_propspecto);
				$statement->bindParam(':fecha', $fecha);

				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
					// $sql2 = "UPDATE `a_marketing_personal` SET `fila` = (`fila` + 1) WHERE `a_marketing_personal`.`idPersona` = :persona;";
					// $con->query($sql2);
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql,'datos'=>[':persona'=> $id_mkt_persona,':tipo'=> $tabla,':prospecto'=> $id_propspecto,':fecha'=> $fecha]];
				}
			}else{
				$response = ["estatus"=>"error", "info"=>'tipo_prospecto_no_definido'];
			}
			return $response;
		}
			# listado de personas por atender respectivamente de un (id) de interes (evento | escuela | curso)
		public function consultar_fila_atencion_byPersonal($personaMK, $interes, $id_interes){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$table = '';
				$columnInteres = '';
				$column_ref_pros = '';
				switch ($interes) {
					case 'evento':
						$table = 'a_prospectos';
						$columnInteres = 'idEvento';
						$column_ref_pros = 'idAsistente';
						break;
					case 'carrera':
						$table = 'a_prospectos';
						$columnInteres = 'idCarrera';
						$column_ref_pros = 'idAsistente';
						break;
					default:
						$table = '';
						break;
				}
				/*
					* Buscar traer la informacion de todos los prospectos
					*  que estan interesados (evento | escuela | curso)
					*  de acuerdo a la fila de atencion de determinado ejecutivo de marketing (personaMK)
					* ma = maketing_atencion
				*/
				
				$sql = "SELECT ma.*, tr.* FROM a_marketing_atencion ma 
				INNER JOIN {$table} tr ON ma.prospecto = tr.{$column_ref_pros}
				WHERE ma.idMk_persona = :personaMK AND tr.{$columnInteres} = :id_interes AND ma.tipo_atencion = '{$interes}' ORDER BY ma.etapa ASC;";
				
				
				if($table != '' && $columnInteres != ''){
					$statement = $con->prepare($sql);
					$statement->bindParam(':personaMK', $personaMK);
					$statement->bindParam(':id_interes', $id_interes);
					$statement->execute();

					if($statement->errorInfo()[0] == '00000'){
						$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
						// $response = ["estatus"=>"ok", "data"=>$correo];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>"no_tipo_prospecto"];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;	
		}

		public function consultar_todo_fila_atencion_ejecutiva($personaMK, $interes){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$table = '';
				$columnInteres = '';
				$column_ref_pros = '';
				switch ($interes) {
					case 'evento':
						$table = 'a_prospectos';
						$columnInteres = 'idEvento';
						$column_ref_pros = 'idAsistente';
						break;
					case 'carrera':
						$table = 'a_prospectos';
						$columnInteres = 'idCarrera';
						$column_ref_pros = 'idAsistente';
						break;
					default:
						$table = '';
						break;
				}
				
				$sql = "SELECT ma.*, tr.* FROM a_marketing_atencion ma 
				INNER JOIN {$table} tr ON ma.prospecto = tr.{$column_ref_pros}
				WHERE ma.idMk_persona = :personaMK AND ma.tipo_atencion = '{$interes}' ORDER BY ma.etapa ASC;";
				if($table != '' && $columnInteres != ''){
					$statement = $con->prepare($sql);
					$statement->bindParam(':personaMK', $personaMK);
					$statement->execute();

					if($statement->errorInfo()[0] == '00000'){
						$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
						// $response = ["estatus"=>"ok", "data"=>$correo];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>"no_tipo_prospecto"];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;	
		}


		//mike
		public function obtenerDatosExamen($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT *
					FROM cursos_examen
					WHERE idExamen = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerMateriasDocente($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT mat.id_materia AS idCurso, mat.nombre AS nombreCurso
				FROM cursos_examen exam 
				INNER JOIN maestros_carreras masterCarrera ON exam.idMaestro = masterCarrera.idMaestro
				INNER JOIN materias mat ON mat.id_carrera = masterCarrera.idCarrera
				WHERE exam.idExamen = :id
				ORDER BY nombreCurso";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerPreguntasExamen($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT pregunta, opciones 
					FROM cursos_examen_preguntas
					WHERE idExamen = :id GROUP BY pregunta";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
				}
			}

		$conexion = null;
		$con = null;
		return $response;
		}

		public function editarExamen($NombreExamen, $idCurso, $FechaInicio, $FechaFin, $idExamen){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE cursos_examen SET
					idCurso = :idCurso, Nombre = :nombreExam, fechaInicio = :fechaInicio , fechaFin = :fechaFin
					WHERE idExamen = :idExam";

				$statement = $con->prepare($sql);
				$statement->bindParam(':nombreExam',$NombreExamen);
				$statement->bindParam(':idCurso',$idCurso);
				$statement->bindParam(':fechaInicio',$FechaInicio);
				$statement->bindParam(':fechaFin',$FechaFin);
				$statement->bindParam(':idExam',$idExamen);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$statement = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function insertarPreguntaExamen($idExamen, $pregunta, $opciones){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO cursos_examen_preguntas 
					(idExamen, pregunta, opciones)VALUES(:idExam, :question, :options)";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idExam',$idExamen);
				$statement->bindParam(':question',$pregunta);
				$statement->bindParam(':options',$opciones);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		/*public function insertarPreguntaExamenPasado_Aleatorio($idExamen, $pregunta, $opciones, $idExamenPas){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$response1 = [];
			

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				
				$sql1 = "SELECT * FROM `cursos_examen_preguntas` WHERE idExamen = $idExamenPas";

				$statement1 = $con->prepare($sql);
				$statement1->execute();

				if($statement1->errorInfo()[0] == "00000"){
					$response1 = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response1 = ['estatus'=>'error', 'info'=>$statement1->errorInfo(), $sql=>'sql'];
				}

				$sql = "INSERT INTO cursos_examen_preguntas 
					(idExamen, pregunta, opciones)VALUES(:idExam, :question, :options)";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idExam',$idExamen);
				$statement->bindParam(':question',$pregunta);
				$statement->bindParam(':options',$opciones);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}*/
		//Preguntas basadas en un examen anterior
		public function insertarPreguntaPasadaExamen($idExamenPas){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];


				$sql = "SELECT exm.idExamen, exm.idCurso, exm.Nombre, pm.id_materia, COUNT(cep.idPregunta) as num_preguntas FROM `planes_estudios` as plan
				JOIN planes_materias as pm ON pm.id_plan = plan.id_plan_estudio
				JOIN cursos_examen exm ON exm.idCurso = pm.id_materia
                JOIN cursos_examen_preguntas as cep ON cep.idExamen = exm.idExamen
				WHERE plan.id_carrera = $idExamenPas AND exm.tipo_examen != 3 AND exm.Examen_ref IS NULL
				GROUP BY exm.idExamen;";
				
				$statementPAS = $con->prepare($sql);
				$statementPAS->execute();

				if($statementPAS->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statementPAS->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statementPAS->errorInfo(), 'sql'=>$sql];
				}

			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function Rango_de_Preguntas($idExamenPas){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];


				$sql = "SELECT * FROM `cursos_examen_preguntas` WHERE idExamen = $idExamenPas;";
				
				$statementPAS = $con->prepare($sql);
				$statementPAS->execute();

				if($statementPAS->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statementPAS->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statementPAS->errorInfo(), 'sql'=>$sql];
				}

			}
		$conexion = null;
		$con = null;
		return $response;
		}		

		public function buscarIdPregunta($idExamen){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idPregunta
					FROM cursos_examen_preguntas 
					WHERE idExamen = :idExam
					ORDER BY idPregunta ASC";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idExam',$idExamen);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>errorInfo(), $sql=>'sql'];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function editarPreguntaExamen($idPregunta, $idExamen, $pregunta, $opciones){
			//var_dump($opciones);
			//die();
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE cursos_examen_preguntas SET
					pregunta = :question, opciones = :options
					WHERE idPregunta = :idPregunta AND idExamen = :idExam";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idPregunta',$idPregunta);
				$statement->bindParam(':question',$pregunta);
				
				//$opciones = str_replace('u00', '\u00', $opciones);
				$statement->bindParam(':options',$opciones);
				$statement->bindParam(':idExam',$idExamen);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$statement = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerDatosTarea($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT *
					FROM clases_tareas
					WHERE idTareas = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerClasesDocente($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT cls.idClase, CONCAT(cls.titulo,' - ',cls.fecha_hora_clase, '    [',mt.nombre,']') as titulo, cls.titulo as nomClase 
					FROM clases cls
					JOIN materias mt ON mt.id_materia = cls.idMateria
					WHERE cls.idMaestro = :id AND cls.estado != 2";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function editarTarea($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE clases_tareas SET idClase = :editClaseTarea, titulo = :editNombreTarea, 
					descripcion = :editDescripcionTarea, fecha_limite = :fecha_limite
					WHERE idTareas = :idTarea";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerListaClases($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idClase, CONCAT(titulo,' - ',fecha_hora_clase) as titulo, titulo as nomClase 
					FROM clases
					WHERE idMaestro = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);
				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function crearTarea($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$data['descripcionTarea'] = preg_replace("/[\\\r\\\n\"]/", "", $data['descripcionTarea']);
				$sql = "INSERT INTO clases_tareas
					(idClase, titulo, descripcion, fecha_limite, idMaestro)VALUES(:clasesDocente, :nombreTarea, :descripcionTarea, :fecha_limite, :idMaestro)";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerListaTareas($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT classTarea.idTareas, classTarea.titulo as tarea, classTarea.fecha_limite, class.titulo, mat.nombre
					FROM clases_tareas classTarea
					INNER JOIN clases class ON class.idClase = classTarea.idClase
					INNER JOIN materias mat On mat.id_materia = class.idMateria
					WHERE classTarea.idMaestro = :id";

			$statement = $con->prepare($sql);
			$statement->execute($id);
			}
		$conexion = null;
		$con = null;
		return $statement;
		}

		public function obtenerListaClasesMaestro($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT class.idClase,class.titulo, class.fecha_hora_clase, mat.nombre, asw.id_sesion, asw.contrasena_sesion, asw.estatus
					FROM clases class
					INNER JOIN materias mat On mat.id_materia = class.idMateria
					LEFT JOIN accesos_sesion_webex as asw on asw.id_clase= class.idClase 
					WHERE class.idMaestro = :id AND class.estado != 2";

			$statement = $con->prepare($sql);
			$statement->execute($id);
			}
		$conexion = null;
		$con = null;
		return $statement;
		}

		public function obtenerTareasCalificar($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT classHom.titulo, classEntregas.idEtrega as idEntrega, classEntregas.comentario, classEntregas.fecha_entrega, classEntregas.calificacion, classEntregas.archivo, CONCAT(aPros.nombre,' ',aPros.aPaterno,' ',aPros.aMaterno) as nombre, classEntregas.retroalimentacion
					FROM clases_tareas classHom
					INNER JOIN clases_tareas_entregas classEntregas ON classEntregas.idTarea = classHom.idTareas
					INNER JOIN a_prospectos aPros ON aPros.idAsistente = classEntregas.idAlumno
					WHERE classHom.idMaestro = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);
			}
		$conexion = null;
		$con = null;
		return $statement;
		}

		public function obtenerDatosCalificarTarea($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT classTareasEnt.comentario, classTareasEnt.retroalimentacion, classTareasEnt.fecha_entrega, classTareasEnt.calificacion, CONCAT(aPros.nombre,' ',aPros.aPaterno,' ',aPros.aMaterno) as nombre, classTareasEnt.idEtrega as idEntrega, classTareas.titulo
					FROM clases_tareas_entregas classTareasEnt
					INNER JOIN clases_tareas classTareas ON classTareas.idTareas = classTareasEnt.idTarea
					INNER JOIN a_prospectos aPros ON aPros.idAsistente = classTareasEnt.idAlumno
					WHERE classTareasEnt.idEtrega = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function calificarTarea($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE clases_tareas_entregas SET
					retroalimentacion = :retroalimentacionAlumno, calificacion = :calificaciones
					WHERE idEtrega = :idEntrega";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}


		//chuy
		function consultar_todo_examenes($ids){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con['conexion'];

			$sql = "SELECT exm.*, mt.nombre as nombre_materia, carr.nombre as nombre_carrera, gen.nombre as nombre_generacion
				FROM `cursos_examen` exm 
				JOIN materias mt ON exm.idCurso = mt.id_materia
				JOIN a_carreras carr ON carr.idCarrera = mt.id_carrera
				INNER JOIN a_generaciones gen ON gen.idGeneracion = exm.id_generacion
				WHERE exm.idMaestro = :id AND exm.id_carrera = :idCarr and exm.tipo_examen != 3";
			
			$stmt = $con->prepare($sql);
			$stmt->execute($ids);

			return $stmt;
		}

		function consultar_respuestas_examenes($examen){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con['conexion'];

			$sql = "SELECT res.*, CONCAT(prosp.aPaterno,' ',prosp.aMaterno,' ',prosp.nombre) as alumno_nombre FROM `curso_examen_alumn_resultado` res
				JOIN cursos_examen exm ON exm.idExamen = res.idExamen
				JOIN a_prospectos prosp ON prosp.idAsistente = res.idAlumno
				WHERE exm.idExamen = :examen ORDER BY idResultado DESC, idAlumno;";
			
			$stmt = $con->prepare($sql);
			$stmt->bindParam(':examen', $examen);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		// fin chuy

		public function obtenerMateriasDocenteExamen($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT mat.id_materia, mat.nombre
					FROM maestros_carreras maestroCarr 
					INNER JOIN materias mat ON mat.id_carrera = maestroCarr.idCarrera
					WHERE maestroCarr.idMaestro = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function crearExamen($nombreExamen, $cursoExamen, $fechaInicioExamen, $fechaFinExamen, $idDocente,$examen_ref){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO cursos_examen
					(idCurso, Nombre, fechaInicio, fechaFin, idMaestro)VALUES(:idCurso, :nombre, :inicioExamen, :finExamen, :idDoc)";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':idCurso', $cursoExamen);
				$statement->bindParam(':nombre', $nombreExamen);
				$statement->bindParam(':inicioExamen', $fechaInicioExamen);
				$statement->bindParam(':finExamen', $fechaFinExamen);
				$statement->bindParam(':idDoc', $idDocente);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function cargarCarrerasExamen($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT carr.idCarrera, carr.nombre
					FROM maestros_carreras maestrosCarr
					INNER JOIN a_carreras carr ON carr.idCarrera = maestrosCarr.idCarrera
					WHERE maestrosCarr.idMaestro = :idMaestro AND carr.idCarrera != 3 AND carr.idCarrera != 4 AND carr.idCarrera != 5 AND carr.idCarrera != 10 AND carr.idCarrera != 11 
					ORDER BY nombre";

				$stmt = $con->prepare($sql);
				$stmt->execute($id);

				if($stmt->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$stmt->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$stmt->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function buscarPreguntasExamen($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idPregunta 
					FROM cursos_examen_preguntas
					WHERE idExamen = :idExamen";

				$stmt = $con->prepare($sql);
				$stmt->execute($id);

				if($stmt->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$stmt->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$stmt->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerNombrePregunta($idPregunta){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT pregunta
					FROM cursos_examen_preguntas
					WHERE idPregunta = :idPreg";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idPreg',$idPregunta);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function asignarCantidadPreguntasAplicar($idExamen, $totalPregExamen){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE cursos_examen SET preguntas_aplicar = :cantidadPreg WHERE idExamen = :idExam";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':idExam', $idExamen);
				$statement->bindParam(':cantidadPreg', $totalPregExamen);
				$statement->execute();
	
				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function recuperarPreguntasAplicar($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT preguntas_aplicar
					FROM cursos_examen
					WHERE idExamen = :idExam";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;			
		}

		public function ObtenerDatosMaestro($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT *
					FROM maestros
					WHERE id = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;			
		}

		public function ActualizarDatosMaestro($Datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$imagen = '';
				if($Datos['img'] !='no_image'){
					$imagen = ', foto = :img';
					
				}else{
					unset($Datos['img']);
				}
				

				$sql = "UPDATE maestros
				SET nombres = :Nombre, aPaterno = :ApellidoPaterno, aMaterno= :ApellidoMaterno ,sexo =:Sexo, email = :Email, telefono = :Telefono, descripcion = :Descripcion {$imagen}
				WHERE id = :id;";

				$statement = $con->prepare($sql);
				$statement->execute($Datos);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount(),'sql'=>$sql];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;			
		}
	}
	//fin mike

?>
