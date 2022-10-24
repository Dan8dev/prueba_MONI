<?php 
date_default_timezone_set('America/Mexico_City');
	require_once 'conexion.php';
    class Eventos{

        public function consultarEvento_Id($clave){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT evt.*, 
					(SELECT COUNT(*) FROM a_prospectos ea
					INNER JOIN a_marketing_atencion ma ON ma.prospecto = ea.idAsistente
					WHERE ma.tipo_atencion = 'evento' AND ea.idEvento = evt.idEvento AND ma.etapa IN (2, 0, 1)) AS numAsistentes
					FROM `ev_evento` evt WHERE evt.`idEvento` = :evento AND evt.`estatus` = 1;";
				
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

        public function consultarUltimoAsistenteID(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SHOW TABLE STATUS FROM `moni_prod` WHERE `name` LIKE 'a_prospectos';";
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

		public function consultarEvento_Institucion($institucion, $status = null){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$estatus_e = ($status !== null)? $status : 1;
				$sql = "SELECT * FROM `ev_evento`WHERE idInstitucion = :institucion AND estatus = :status;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":institucion", $institucion);
				$statement->bindParam(":status", $estatus_e);
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

		public function get_talleres_evento($evento){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT t.*,
				(SELECT COUNT(a_tll.id_reg) FROM `ev_asistente_talleres` a_tll WHERE a_tll.id_taller = t.id_taller AND a_tll.estatus = 1) as num_asist
				FROM `ev_talleres` t 
				WHERE t.id_evento = :evento ORDER BY t.fecha;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":evento", $evento);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$data = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach ($data as $key => $value) {
						if(intval($value['num_asist']) >= intval($value['cupo'])){
							unset($data[$key]);
						}
					}
					$data = array_values($data);
					$eventos = $data;
					
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

		public function get_talleres_prospecto($prospecto, $evento){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT ast_t.*, tall.*, (SELECT hora FROM asistentes_eventos WHERE id_asistente = :prospecto AND id_taller = tall.id_taller LIMIT 1) as asistido FROM `ev_asistente_talleres` ast_t 
				JOIN ev_talleres tall ON tall.id_taller = ast_t.id_taller
				WHERE ast_t.id_asistente = :prospecto AND tall.id_evento = :evento AND ast_t.estatus = 1;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":prospecto", $prospecto);
				$statement->bindParam(":evento", $evento);
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

		public function get_talleres_permitidos($prospecto, $evento){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con["conexion"];
			$instituciones_alm = $con->query("SELECT id_institucion FROM instituciones_afiliados WHERE id_prospecto = $prospecto;")->fetchAll(PDO::FETCH_ASSOC);
			$talleres = [];
			if($instituciones_alm){
				foreach ($instituciones_alm as $key => $value) {
					$instit = $value["id_institucion"];
					$talleres_alm = $con->query("SELECT tall.*, (SELECT hora FROM asistentes_eventos WHERE id_asistente = ".$prospecto." AND id_taller = tall.id_taller LIMIT 1) as asistido FROM ev_talleres tall WHERE tall.tipos_permitidos LIKE '%{$instit}%' AND tall.id_evento = {$evento} AND tall.evento_privado = 1;")->fetchAll(PDO::FETCH_ASSOC);
					if($talleres_alm){
						$talleres = array_merge($talleres, $talleres_alm);
					}
				}
			}
			$talleres_permiso = $con->query("SELECT tall.*, (SELECT hora FROM asistentes_eventos WHERE id_asistente = ".$prospecto." AND id_taller = tall.id_taller LIMIT 1) as asistido FROM ev_talleres tall WHERE tall.incluir LIKE '%{$prospecto}%' AND tall.id_evento = {$evento} AND tall.evento_privado = 1;")->fetchAll(PDO::FETCH_ASSOC);
			if($talleres_permiso){
				$talleres = array_merge($talleres, $talleres_permiso);
			}

			$conexion = null;
			$con = null;
		
			return $talleres;
		}

    }
