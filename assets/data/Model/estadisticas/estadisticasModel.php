<?php 
date_default_timezone_set("America/Mexico_City");
	class Estadisticas{
		// funciones login
		public function consultarEstadisticas_ById($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = 
				"SELECT * FROM estadisticas WHERE id = :id;";				
				
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
	}
?>