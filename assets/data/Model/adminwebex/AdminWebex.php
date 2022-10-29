<?php 
date_default_timezone_set("America/Mexico_City");
	class AdminWebex{

		public function consultarAsistenciaEventos($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT (asev.id_asistente) AS id_asistente, COUNT(DISTINCT DATE(asev.hora)) as TotalAsistencias, apros.correo, LTRIM(UPPER(CONCAT(apros.aPaterno,' ',apros.aMaterno,' ',apros.nombre))) as nombre,
					asev.folio
					FROM asistentes_eventos AS asev
					LEFT JOIN a_prospectos AS apros ON apros.idAsistente = asev.id_asistente
					WHERE asev.id_evento = :Evento
					GROUP BY asev.id_asistente  
					ORDER BY nombre  ASC;"; 

				$statement = $con->prepare($sql); 			  
				$statement->execute($data);			  
					
				
			}

			$conexion = null;
			$con = null;
			return $statement;
		}

		public function asistenciasMinimas($idEvento){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			
			$sql =  "SELECT cantidad_asis_min FROM ev_evento where idEvento = :idEvento;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(":idEvento", $idEvento);
			$statement->execute();
			
			return $statement->fetch(PDO::FETCH_ASSOC);
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
        
        public function consultarAdmin_ById($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM `a_webex` WHERE idPersona  = :id;"; 

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

        public function listarsesiones(){
            $conexion = new Conexion();
            $con = $conexion->conectar();
            $response = [];
        
            if($con['info'] == 'ok'){
                $con = $con['conexion'];
        
                $sql = "SELECT wbx.*, cls.titulo, cls.fecha_hora_clase, crs.nombre as nombre_carrera, UPPER(CONCAT(maes.nombres,' ', maes.aPaterno,' ', maes.aMaterno)) AS nombreM, maes.email, maes.telefono FROM `accesos_sesion_webex` wbx
				JOIN clases cls on wbx.id_clase = cls.idClase
				JOIN maestros AS maes ON maes.id = cls.idMaestro
				JOIN materias mts on mts.id_materia = cls.idMateria
				JOIN a_carreras crs on crs.idCarrera = mts.id_carrera WHERE cls.estado = 1;";
        
                $statement = $con->prepare($sql);
                $statement->execute();
            
                $conexion = null;
                $con = null;
        
                return $statement;
            }
        }

		function listarsesiones_Eventos(){
			$conexion = new Conexion();
            $con = $conexion->conectar();
			$con = $con['conexion'];
			$sql = "SELECT wbx.*, evs.titulo, evs.fechaE FROM accesos_sesion_webex wbx
				JOIN ev_evento evs ON evs.idEvento = wbx.id_evento 
				WHERE wbx.estatus = 1;";
			$stmt = $con->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

        public function obtenerSesion($idconcepto){
            $conexion = new Conexion();
            $con = $conexion->conectar();
            $response = [];
          
            if($con['info'] == 'ok'){
            $con = $con['conexion'];
			$tipo = $con->query("SELECT id_clase FROM accesos_sesion_webex WHERE id = ".$idconcepto['idsesion'])->fetch(PDO::FETCH_ASSOC);
			if( $tipo['id_clase'] !== null ){
				$sql = "SELECT wbx.*, cls.titulo, cls.fecha_hora_clase, crs.nombre as nombre_carrera, cls.video, cls.foto FROM `accesos_sesion_webex` wbx
					JOIN clases cls on wbx.id_clase = cls.idClase
					JOIN materias mts on mts.id_materia = cls.idMateria
					JOIN a_carreras crs on crs.idCarrera = mts.id_carrera WHERE wbx.id=:idsesion";
			}else{
				$sql = "SELECT * FROM accesos_sesion_webex WHERE id = :idsesion";
			}
          
            $statement = $con->prepare($sql);
        
            $statement->execute($idconcepto);
          
            if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
			}
              $conexion = null;
              $con = null;
              return $response;
            }
          }

          function actualizarSesion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `accesos_sesion_webex` 
				SET `nombre_clase` = :editarnombresesion, `id_sesion` = :editaridsesion, `contrasena_sesion` = :editarcontrasenasesion
				WHERE id = :idsesion;";

				$statement = $con->prepare($sql);
				$upd_video = 0;
				if(isset($data['id_clase']) && $data['id_clase'] > 0 && isset($data['editar_url_clase'])){
					$upd_foto = '';
					if(isset($data['nombre_archivo'])){
						$upd_foto = ", foto = '".$data['nombre_archivo']."' ";
					}
					$upd_video = $con->query("UPDATE `clases` SET `video` = '".urlencode($data['editar_url_clase'])."' ".$upd_foto." WHERE idClase = ".$data['id_clase'].";")->rowCount();
				}
				unset($data['id_clase']);
				unset($data['editar_url_clase']);
				unset($data['nombre_archivo']);
				
				
				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>($statement->rowCount() > 0)?$statement->rowCount():$upd_video];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		function activarSesion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `accesos_sesion_webex` 
				SET `estatus` = 1
				WHERE id = :idsesion;";

				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		function desactivarSesion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `accesos_sesion_webex` 
				SET `estatus` = 2
				WHERE id = :idsesion;";

				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		function consultar_carreras(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT carr.* FROM `a_carreras` carr
				JOIN planes_estudios est ON carr.idCarrera = est.id_carrera WHERE carr.estatus = 1 GROUP BY carr.idCarrera;";
			$statement = $con->prepare($sql);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function consultar_eventos(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT evt.* FROM `ev_evento` evt WHERE evt.idEvento NOT IN (SELECT id_evento FROM accesos_sesion_webex WHERE id_evento IS NOT NULL) ORDER BY `evt`.`idEvento` DESC;";
			$statement = $con->prepare($sql);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function consultar_generaciones_carrera($carrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT * FROM `a_generaciones` WHERE idCarrera = :carrera ORDER BY nombre;";
			$statement = $con->prepare($sql);
			$statement->bindParam(":carrera", $carrera);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function consultar_clases_generaciones($generacion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT cls.*, mat.nombre as nombre_materia, UPPER(CONCAT(maes.nombres,' ', maes.aPaterno,' ', maes.aMaterno)) AS nombreM, maes.email, maes.telefono 
					FROM `clases` cls
					JOIN materias mat ON mat.id_materia = cls.idMateria
					JOIN maestros AS maes ON maes.id = cls.idMaestro
					JOIN materias mts on mts.id_materia = cls.idMateria
					WHERE cls.idClase NOT IN (SELECT ses.id_clase FROM accesos_sesion_webex ses WHERE ses.id_clase IS NOT NULL)
					AND cls.idGeneracion = :generacion AND cls.estado = 1
					ORDER BY nombre_materia, cls.fecha_hora_clase;"; //Funcion
			$statement = $con->prepare($sql);
			$statement->bindParam(":generacion", $generacion);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function registrar_sesion_webex($data){
			$conexion = new Conexion();
			$con = $conexion->conectar()["conexion"];
			$sql = "INSERT INTO accesos_sesion_webex (nombre_clase, id_clase, id_sesion, contrasena_sesion, estatus)
					VALUES (:inp_nombresesion, :select_clases, :inp_idsesion, :inp_contrasenasesion, 1);";
			$statement = $con->prepare($sql);
			$statement->execute($data);
			return $con->lastInsertId();
		}

		function registrar_sesion_webex_evento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar()["conexion"];
			$sql = "INSERT INTO accesos_sesion_webex (nombre_clase, id_sesion, contrasena_sesion, id_evento, estatus)
					VALUES (:inp_nombresesion, :inp_idsesion, :inp_contrasenasesion, :select_evento, 1);";
			$statement = $con->prepare($sql);
			$statement->execute($data);
			$inserted = $con->lastInsertId();
			if($inserted > 0){
				$con->query("UPDATE ev_evento SET webex_id = {$inserted} WHERE idEvento = {$data['select_evento']}");
			}
			return $inserted;
		}

		
    }
