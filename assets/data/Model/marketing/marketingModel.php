<?php 
date_default_timezone_set("America/Mexico_City");
	class Marketing{
		// funciones login
		public function consultarPersonaMktng_ById($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = 
				"SELECT * FROM a_marketing_personal WHERE idPersona = :id;";
				
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

		public function consultarTodoPersonal_estatus($estatus){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = 
				"SELECT * FROM a_marketing_personal WHERE estatus = :estatus AND rol = 1;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':estatus', $estatus);
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
				"SELECT mp.*, ac.correo, 
					(SELECT COUNT(*) from a_marketing_atencion WHERE idMk_persona = mp.idPersona AND etapa = 1 and tipo_atencion = 'evento') as fila_prospectos_ev,
					(SELECT COUNT(*) from a_marketing_atencion WHERE idMk_persona = mp.idPersona AND etapa = 1 and tipo_atencion = 'carrera') as fila_prospectos_ca
					FROM `a_marketing_personal` mp
					INNER JOIN a_accesos ac ON ac.idPersona = mp.idPersona
					WHERE ac.idTipo_Persona = 3;";
				
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

		public function reasignar_prospecto($relac, $ejecutiva){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$fecha = date("Y-m-d");
				$sql = "UPDATE a_marketing_atencion SET idMk_persona = :ejecutiva WHERE idReg = :relac;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':ejecutiva', $ejecutiva);
				$statement->bindParam(':relac', $relac);

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
				if($eventos_pers_pendientes[$i]['idEvento'] != 39 && $eventos_pers_pendientes[$i]['idEvento'] != 35){
					$asigna = $this->actualzar_fila_atencion($eventos_pers_pendientes[$i]['idAsistente'], 'evento', $eventos_pers_pendientes[$i]['idEvento']);
					if($asigna['estatus'] == 'ok'){
						array_push($success, $asigna['data']);
					}else{
						array_push($error, $asigna);
					}
				}
			}
			for ($i=0; $i < sizeof($carreras_pers_pendientes); $i++) { 
				// a cada prospecto de evento no asignado se asigna a una persona de marketing disponible
				if($carreras_pers_pendientes[$i]['idCarrera'] != 3 && $carreras_pers_pendientes[$i]['idCarrera'] != 22){
					require_once '../../Model/carreras/carrerasModel.php';
					$carrM = new Carrera();
					$carrera_info = $carrM->consultarCarreraByID($carreras_pers_pendientes[$i]['idCarrera']);
					if(array_key_exists('callcenter',$carrera_info['data'])){
						if($carrera_info['data']['callcenter'] == 1){
							$asigna = $this->actualzar_fila_atencion($carreras_pers_pendientes[$i]['idAsistente'], 'carrera', $carreras_pers_pendientes[$i]['idCarrera']);
						}else{
							$asigna['estatus'] = 'error';
						}
					}else{
						$asigna = $this->actualzar_fila_atencion($carreras_pers_pendientes[$i]['idAsistente'], 'carrera', $carreras_pers_pendientes[$i]['idCarrera']);
					}
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
				// $sql = 
				// "SELECT mp.*, 
				// (SELECT COUNT(*) FROM a_marketing_atencion ma WHERE ma.idMk_persona = mp.idPersona AND ma.etapa IN (0)) AS filaAtencion 
				// 	FROM `a_marketing_personal` mp WHERE mp.estatus = 1 AND DATE(`sesion`) = :fecha ;";
				
				$sql = 
				"SELECT mp.*, 
				(SELECT COUNT(*) FROM a_marketing_atencion ma WHERE ma.idMk_persona = mp.idPersona AND ma.etapa IN (1)) AS filaAtencion 
					FROM `a_marketing_personal` mp WHERE mp.estatus = 1;";
				
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
		public function actualzar_fila_atencion($entrante, $tipo = '', $idI){

			$resp = ['empty'];
			$todo_personas = $this->consultarTodoPersonal_listaAtencion();
			if($todo_personas['estatus']=='ok' && !empty($todo_personas['data'])){
				foreach($todo_personas['data'] as $key_ejec => $ejecutiva){
					if($ejecutiva['rol'] == 2){
						unset($todo_personas['data'][$key_ejec]);
					}
				}
				$todo_personas['data'] = array_values($todo_personas['data']);
				$todo_personas = $todo_personas['data'];	
				$max = array_reduce($todo_personas, function($acc, $item){return ($acc < $item["filaAtencion"] )? $item['filaAtencion'] : $acc;}, 0);
				$min = array_reduce($todo_personas, function($acc, $item){return ($acc > $item["filaAtencion"] )? $item['filaAtencion'] : $acc;}, $max);

				$entrante_asignado = false;
				$co = 0;
				$asignacion = [];
				$error = [];
				while (!$entrante_asignado && $co < sizeof($todo_personas)) {
					// si el tamanio de la fila de pendientes de la persona $co es igual al tamanio minimo se le asigna un nuevo pendiente
					// $todo_personas es el arreglo de todas las ejecutivas
					// si el id de la persona es distinto de 2 se le asigna un prospecto
					//echo $todo_personas[$co]["filaAtencion"]." - ".$todo_personas[$co]["idPersona"]." [".$min."]\n";
					if($todo_personas[$co]["filaAtencion"] == $min ){
						$ejecutiva_dest = $todo_personas[$co]['idPersona'];
						
						/** Buscar ultima ejecutica que atendió al prospecto (por id o correo) */
						$busca_ej = $this->buscar_ultima_ejecutiva($entrante);
						if($busca_ej > 0){
							$ejecutiva_dest = $busca_ej;
							$todo_personas[$co]['nombres'] = 'cambio';
						}
						$asignar = $this->set_prospecto_fila($tipo, $entrante, $ejecutiva_dest, $idI);
						if($asignar["estatus"] == "ok"){
							$asignacion['id_relacion'] = $asignar["data"];
							$asignacion['persona_seguimiento'] = [$ejecutiva_dest, $todo_personas[$co]['nombres']];
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
			public function buscar_ultima_ejecutiva($id_prospecto){
				$conexion = new Conexion();
				$con = $conexion->conectar();
				$con = $con["conexion"];
				
				$prospecto_info = $con->query("SELECT * FROM a_prospectos WHERE idAsistente = {$id_prospecto}")->fetch(PDO::FETCH_ASSOC);
				
				$sql = "SELECT prosp.*, aten.* FROM `a_prospectos` prosp 
					JOIN a_marketing_atencion aten ON aten.prospecto = prosp.idAsistente 
					WHERE prosp.correo = :correo 
					ORDER BY `aten`.`idReg` DESC ;";

				$statement = $con->prepare($sql);
				$statement->bindParam(':correo', $prospecto_info['correo']);
				$statement->execute();
				
				if($statement->rowCount() > 0){
					return $statement->fetch(PDO::FETCH_ASSOC)['idMk_persona'];
				}else{
					return 0;
				}
			}
			
			# crear relacion de personal marketing y prospecto
		public function set_prospecto_fila($tipo, $id_propspecto, $id_mkt_persona, $idI){
			$con = new Conexion();
			$con = $con->conectar()['conexion'];
			// validar no duplicar registro atencion
			$no_repeat = $con->query("SELECT * FROM a_marketing_atencion WHERE prospecto = {$id_propspecto} AND tipo_atencion = '{$tipo}' AND evento_carrera = {$idI}")->fetchAll();
			if(!empty($no_repeat)){
				return ['estatus' => 'error', 'info'=>'El prospecto ya se encuentra listado para este producto'];
				die();
			}
			// validar estatus del ejectiv@
			$ejec_info = $this->consultarPersonaMktng_ById($id_mkt_persona);
			if($ejec_info['data']['estatus'] != 1){
				$ejecutivas_disp = $this->consultarTodoPersonal_estatus(1)['data'];
				$rand = array_rand($ejecutivas_disp,1);
				$id_mkt_persona = $ejecutivas_disp[$rand]['idPersona'];
			}
			$response = [];
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
				$fecha = date("Y-m-d H:i:s");
				$sql = "INSERT INTO a_marketing_atencion (idMk_persona, tipo_atencion, prospecto, evento_carrera, etapa, seguimiento) 
				VALUES (:persona, :tipo, :prospecto, :evento_carrera, 1, :fecha);
				UPDATE `a_marketing_personal` SET `fila` = (`fila` + 1) WHERE `a_marketing_personal`.`idPersona` = :persona;";

				$statement = $con->prepare($sql);
				
				$statement->bindParam(':persona', $id_mkt_persona);
				$statement->bindParam(':tipo', $tabla);
				$statement->bindParam(':evento_carrera', $idI);
				$statement->bindParam(':prospecto', $id_propspecto);
				$statement->bindParam(':fecha', $fecha);

				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
					// $sql2 = "UPDATE `a_marketing_personal` SET `fila` = (`fila` + 1) WHERE `a_marketing_personal`.`idPersona` = :persona;";
					// $con->query($sql2);
				}else{
					$response = ["estatus"=>"error", "info"=>'error_asignacion', "sql"=>$sql,'datos'=>[':persona'=> $id_mkt_persona,':tipo'=> $tabla,':prospecto'=> $id_propspecto,':fecha'=> $fecha]];
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
				WHERE ma.idMk_persona = :personaMK AND ma.evento_carrera = :id_interes AND ma.tipo_atencion = '{$interes}' ORDER BY ma.etapa ASC;";
				
				
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
				
				$sql = "SELECT ma.*, ma.seguimiento as fe_reg, tr.*, af_cona.curp as opcionCurp,
				(SELECT CONCAT(ee.tipo,' ',ee.titulo) from ev_evento as ee where ee.idEvento = ma.evento_carrera) as titulo_e, 
				(SELECT CONCAT(cr.tipo,' ',cr.nombre) from a_carreras as cr where cr.idCarrera = ma.evento_carrera) as titulo_c,
				(SELECT crd.idInstitucion from a_carreras as crd where crd.idCarrera = ma.evento_carrera) as institucion_c,
				(SELECT gen.nombre FROM alumnos_generaciones a_gen JOIN a_generaciones gen ON gen.idGeneracion = a_gen.idgeneracion WHERE a_gen.idalumno = ma.prospecto AND gen.idCarrera = ma.evento_carrera LIMIT 1) as generacion_carrera,
				(SELECT AES_DECRYPT(afil.contrasenia,'SistemasPUE21') from afiliados_conacon afil WHERE afil.id_prospecto = ma.prospecto) as contrasen FROM a_marketing_atencion ma 
				INNER JOIN {$table} tr ON ma.prospecto = tr.{$column_ref_pros}
				LEFT JOIN afiliados_conacon af_cona ON af_cona.id_prospecto = ma.prospecto
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

		public function consultar_llamadas_ejecutiva($personaMK){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT aten.*, llam.*, CONCAT(prosp.aPaterno, ' ', prosp.nombre) as prospecto_llamar FROM `a_marketing_atencion` aten 
				INNER JOIN mk_atencion_llamadas llam ON llam.idAtencion = aten.idReg
				JOIN a_prospectos prosp ON prosp.idAsistente = aten.prospecto  
				WHERE idMk_persona = :ejecutiva  ORDER BY `llam`.`fecha_llamar` ASC;";

				$statement = $con->prepare($sql);
				$statement->bindParam(':ejecutiva', $personaMK);
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

		function cargar_estatus_seguimiento(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$response = $con->query("SELECT * FROM a_marketing_catalogo WHERE estatus = 1")->fetchAll(PDO::FETCH_ASSOC);
			return $response;
		}

		function consultar_clinicas_prospectos_responsables(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT inst.id_institucion, inst.nombre, inst.preautorizacion, inst.estatus, inst.fecha_registro, inst.comentario, prs.idAsistente, prs.nombre as responsable_nombre, prs.aPaterno, prs.aMaterno, prs.correo, prs.telefono , atn.idMk_persona, atn.idReg, atn.seguimiento, atn.tipo_atencion, atn.evento_carrera
				FROM `a_instituciones` inst
				JOIN instituciones_datos dats ON dats.id_institucion = inst.id_institucion
				JOIN afiliados_conacon afc ON afc.clinicaResponsable = inst.id_institucion
				JOIN a_prospectos prs ON prs.idAsistente = afc.id_prospecto 
				LEFT JOIN a_marketing_atencion atn ON prs.idAsistente = atn.prospecto AND atn.idReg = (SELECT MAX(t_aten.idReg) FROM a_marketing_atencion t_aten WHERE t_aten.prospecto = atn.prospecto)  
				WHERE inst.estatus = 1 AND inst.fundacion = 1 AND inst.acuerdo = 0 AND afc.estatus = 10
				ORDER BY `inst`.`preautorizacion` ASC, inst.fecha_registro DESC;";
			$stmt = $con->prepare($sql);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		function consultar_info_clinica($clinica){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT * FROM a_instituciones WHERE id_institucion = $clinica;";
			$stmt = $con->prepare($sql);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_ASSOC);
		}

		function set_clinica_verified($clinica, $estatus, $comentario, $clave_verificacion = false){
			$response = [];
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			if($estatus){
				$sql = "UPDATE a_instituciones SET preautorizacion = :clave, comentario = :comentario WHERE id_institucion = $clinica;";
				$statement = $con->prepare($sql);
				$statement->bindParam(':comentario', $comentario);
				$statement->bindParam(':clave', $clave_verificacion);
			}else{
				$sql = "UPDATE a_instituciones SET preautorizacion = 'invalid', comentario = :comentario WHERE id_institucion = $clinica;";
				$statement = $con->prepare($sql);
				$statement->bindParam(':comentario', $comentario);
			}

			$statement->execute();
			if($statement->errorInfo()[0] == '00000'){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>'Internal error'];
			}

			return $response;
		}

		//eventos Marketing
        
		public function duracionEventos($duracion){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			
			$sql =  "SELECT duracion, tipoDuracion FROM ev_evento where idEvento = :idEvento;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(":idEvento", $duracion);
			$statement->execute();
			
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
        
        public function agregarAsistencia($data){
			$conexion = new Conexion();
			$con = $conexion->conectar()["conexion"];

			$sql = "INSERT INTO asistentes_eventos (nombre_reconocimiento, id_asistente, id_evento, hora, folio)
					VALUES ('', :idAsistente, :idEvento, :fecha, '');";
			
			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
			return $response;
		}

	}
?>
