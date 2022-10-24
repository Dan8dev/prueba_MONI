<?php 
date_default_timezone_set("America/Mexico_City");

	class ControlEscolar{
		var $retardo = 15;
		var $falta = 31;
		var $estatus_alumno = [
			'1' => 'Activo',
			'2' => 'Baja',
			'3' => 'Egresado',
			'4' => 'Titulado',
			'5' => 'Expulsado'
		];

			
		function consultarDocumentosListaCompleta(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM listado_documentos ld WHERE ld.estatus = 1";

				$statement = $con->prepare($sql);
				$statement->execute();
				
				$conexion = null;
				$con = null;
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		public function CalculoDePromedioGeneraL($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT  alumGen.idalumno, AVG(cal.calificacion) as promedio
					FROM alumnos_generaciones as alumGen
					JOIN calificaciones AS cal ON cal.idProspecto = alumGen.idalumno AND cal.idGeneracion = alumGen.idgeneracion
					WHERE alumGen.idgeneracion = :idGen AND alumGen.idalumno = :idAlum
					GROUP BY alumGen.idalumno;"; 

				$statement = $con->prepare($sql); 	  
				$statement->execute($data);			  
					
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

        public function consultarControlEscolar_ById($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM `controlescolar` WHERE id = :id and estado = 1;"; 

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

		public function VerificarExistenciaCalificacion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			//var_dump($data);
			//die();
			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$id = '';
				$sql = "SELECT * FROM calificaciones WHERE id_materia = :idMat AND numero_ciclo = :idCic AND idProspecto = :idAlu AND idGeneracion = :idGen;";
	
				$statement = $con->prepare($sql);
				$statement->execute($data);
	
				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
				}
			}
			$conexion = null;
			$con = null;
		
			return $response;
		}

		function consultarDocumentosList($usuario){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT * FROM documentos WHERE id_prospectos = :idusuario";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':idusuario', $usuario, PDO::PARAM_INT);
				$statement->execute();
				
				$conexion = null;
				$con = null;
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
		}
	
		public function consultarMaestros($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$complete = "";
				if($id == 4){
					$complete = "JOIN maestros_carreras as mc on mc.idMaestro = ms.id WHERE mc.idCarrera = 14 or mc.idCarrera = 19";
				}
				
				$sql = "SELECT DISTINCT ms.* FROM maestros as ms {$complete}";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarMaestros

		public function consultarsesionesenvivo(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT class.idClase,concat(mtro.nombres,' ',mtro.aPaterno,' ',mtro.aMaterno) as nombre_maestro,agen.nombre as generacion_carrera,class.titulo as nombre_clase, class.fecha_hora_clase, mat.nombre as nombre_materia, asw.id_sesion, asw.contrasena_sesion, asw.estatus 
				FROM clases class 
				JOIN maestros as mtro on mtro.id=class.idMaestro 
				JOIN a_generaciones as agen on agen.idGeneracion=class.idGeneracion 
				INNER JOIN materias mat On mat.id_materia = class.idMateria 
				LEFT JOIN accesos_sesion_webex as asw on asw.id_clase= class.idClase 
				WHERE class.estado != 2 AND class.fecha_hora_clase BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL 1 DAY)";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}

		public function buscarMaestro($buscar){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM maestros WHERE id = $buscar";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)/*, "carreras"=>$statement2->fetchAll(PDO::FETCH_COLUMN)*/];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}//Fin buscarMaestro

		function carrerasActuales( $buscar ){

			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql2 = "SELECT idCarrera FROM maestros_carreras WHERE idMaestro = $buscar";
				$statement2 = $con->prepare($sql2);
				$statement2->execute();

				if($statement2->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "carreras"=>$statement2->fetchAll(PDO::FETCH_COLUMN)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement2->errorInfo(), "sql"=>$sql2, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}

		}//carrerasActuales

		function buscarCarrerasMaestro( $buscar ){

			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT a_carreras.idCarrera AS idCarrera, nombre FROM a_carreras, maestros_carreras WHERE a_carreras.idCarrera = maestros_carreras.idCarrera AND idMaestro = ".$buscar." ORDER BY nombre";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = $statement->fetchAll(PDO::FETCH_ASSOC);
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}

		}//buscarCarrerasMaestro

		function buscarMaterias( $buscar ){

			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT id_materia AS idMateria, nombre FROM materias WHERE id_carrera = ".$buscar." ORDER BY nombre";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = $statement->fetchAll(PDO::FETCH_ASSOC);
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}

		}//buscarCarrerasMaestro

		public function editarMaestro($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				if( isset( $data['resetp'] ) ) {
					include('../../Model/acceso/keys.php');
					$sql = "UPDATE a_accesos SET correo = '".$data['email_em']."', contrasenia = AES_ENCRYPT('abc', '{$DECRYPT_PASS}')  WHERE idPersona = ".$data['idMaestro']." AND idTipo_Persona = 30";
					$statement = $con->prepare($sql);
					$statement->execute();
				}
				
				if( $data['total_carrerasE'] > 0 ){
					$sql = "DELETE FROM maestros_carreras WHERE idMaestro = ".$data['idMaestro'];
					$statement = $con->prepare($sql);
					$statement->execute();

					for( $i = 0; $i < $data['total_carrerasE']; $i++ ){
						if( isset( $data['checkbox_ce'.$i] ) ){
							$sql = "INSERT INTO maestros_carreras (idMaestro, idCarrera) VALUES (".$data['idMaestro'].", ".$data['checkbox_ce'.$i]." )";
							$statement = $con->prepare($sql);
							$statement->execute();
						}
					}
				}

				$sql = "UPDATE maestros SET nombres = '".$data['nombres_em']."', aPaterno = '".$data['aPaterno_em']."', aMaterno = '".$data['aMaterno_em']."', sexo = '".$data['sexo_em']."',  
				email = '".$data['email_em']."', telefono = '".$data['telefono_em']."' WHERE id = ".$data['idMaestro'].";
				UPDATE a_accesos SET correo = '".$data['email_em']."' WHERE idTipo_Persona = 30 AND idPersona = ".$data['idMaestro'];
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//editarMaestro

		public function desactivarMaestro($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE maestros SET estado = :vEstado WHERE id = :idDesactivar";
				$statement = $con->prepare($sql);
				$statement->execute($data);

				
				$pass = $data['vEstado'] == 0 ? 'inactivo1010' : 'abc';
				
				include('../../Model/acceso/keys.php');
				$sql = "UPDATE a_accesos SET contrasenia = AES_ENCRYPT('".$pass."', '{$DECRYPT_PASS}')  WHERE idPersona = ".$data['idDesactivar']." AND idTipo_Persona = 30";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function agregarMaestro($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO maestros (nombres, aPaterno, aMaterno, sexo, email, telefono) VALUES ( '".$data['nombres_am']."', '".$data['aPaterno_am']."', '".$data['aMaterno_am']."', '".$data['sexo_am']."', '".$data['email_am']."', '".$data['telefono_am']."' )";
				$statement = $con->prepare($sql);
				$statement->execute();
				$idMaestro = $con->lastInsertId();

				for( $i = 0; $i < $data['total_carreras']; $i++ ){
					if( isset( $data['checkbox_c'.$i] ) ){
						$sql = "INSERT INTO maestros_carreras (idMaestro, idCarrera) VALUES (".$idMaestro.", ".$data['checkbox_c'.$i]." )";
						$statement = $con->prepare($sql);
						$statement->execute();
					}
				}

				include('../../Model/acceso/keys.php');
				$sql = "INSERT INTO a_accesos (idTipo_Persona, idPersona, correo, contrasenia) VALUES (30, ".$idMaestro.", '".$data['email_am']."', AES_ENCRYPT('abc', '{$DECRYPT_PASS}') )";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//Fin agregarProcedimiento

		public function buscarCarreras($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];
			
			$whr = "WHERE ac.estatus = 1 AND idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11";
			if($id == 4){
				$whr = "WHERE (idCarrera = 14 or idCarrera = 19) and ac.estatus = 1";
			}

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT ac.nombre, ac.idCarrera,ac.imgFondo,ac.imagen,ac.fecha_actualizacion,ac.fecha_creado,ac.descriptionC,ac.title
				FROM a_carreras as ac
				{$whr}
				ORDER BY ac.nombre;";

				$statement = $con->prepare($sql); 		  
				$statement->execute();
				
				$response = $statement->fetchAll(PDO::FETCH_ASSOC);			  
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}//buscarCarreras

		public function buscarCarrerasE($idMaestro,$id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				if($id == 4){
					$whr = "WHERE (idCarrera = 14 or idCarrera = 19) and estatus = 1";
				}else{
					$whr = "";
				}
				
				$sql = "SELECT nombre, idCarrera FROM a_carreras {$whr} ORDER BY nombre";
				$statement = $con->prepare($sql); 		  
				$statement->execute();

				$sql = "SELECT idCarrera FROM maestros_carreras WHERE idMaestro = '$idMaestro'";
				$scarreras = $con->prepare($sql); 		  
				$scarreras->execute();

				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC), "cseleccion"=>$scarreras->fetchAll(PDO::FETCH_ASSOC)];
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}//buscarCarreras

		public function buscarCarrerasAsignacion($idMaestro,$id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$complete = '';
					if($id == 4){
						$complete = "and (a_carreras.idCarrera = 14 or a_carreras.idCarrera = 19)";
					}
					
				if($idMaestro > 0 ){
					
					$sql = "SELECT maestros_carreras.idCarrera AS idCarrera, a_carreras.nombre FROM maestros_carreras, a_carreras  
					WHERE maestros_carreras.idMaestro = $idMaestro 
					AND a_carreras.idCarrera = maestros_carreras.idCarrera {$complete} ORDER BY nombre";
				}else{
					if($id == 4){
						$complete = "WHERE a_carreras.idCarrera = 14 or a_carreras.idCarrera = 19";
					}
					$sql = "SELECT idCarrera, a_carreras.nombre 
					FROM a_carreras  
					{$complete} ORDER BY nombre";
				}
				
				
				$statement = $con->prepare($sql); 		  
				$statement->execute();

				$response = $statement->fetchAll(PDO::FETCH_ASSOC);
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}//buscarCarrerasAsignacion

		public function selectSessions($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$whr = "";
				if($id == 4){
					$whr = " and ac.idCarrera = 14";
				}
				$sql = "SELECT cl.titulo,cl.idClase,cl.fecha_hora_clase,mt.nombre as nMat, ac.nombre as nCar,ag.secuencia_generacion as sGen,
						CONCAT(ms.nombres,' ',ms.aPaterno,' ',ms.aMaterno) as teachers, ms.email as correo
						FROM clases as cl
						JOIN materias as mt on mt.id_materia = cl.idMateria
						JOIN a_generaciones as ag on ag.idGeneracion = cl.idGeneracion
						JOIN a_carreras as ac on ac.idCarrera = ag.idCarrera
						LEFT JOIN maestros as ms on ms.id = cl.idMaestro
						WHERE cl.estado != 2 {$whr}
						ORDER BY cl.fecha_hora_clase DESC;";

				$statement = $con->prepare($sql); 		  
				$statement->execute();

				$response = $statement->fetchAll(PDO::FETCH_ASSOC);
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}
		public function listTeach($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$whr = "";
				if($id == 4){
					$whr = "and mc.idCarrera = 14";
				}
				
				$sql = "SELECT DISTINCT mc.idMaestro as idTeacher, CONCAT(mt.nombres,' ',mt.aPaterno,' ',mt.aMaterno) as nTeacher
						FROM maestros as mt
						JOIN maestros_carreras as mc on mt.id = mc.idMaestro
						WHERE  estado = 1 {$whr};";
				
				$statement = $con->prepare($sql); 		  
				$statement->execute();

				$response = $statement->fetchAll(PDO::FETCH_ASSOC);
				
				$conexion = null;
				$con = null;
				return $response;
			}
		}
		public function addteacher($post){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$upTe = "";
				if(isset($post['selectTeacher'])){
					$upTe = ", `idMaestro`= :selectTeacher";
				}

				$sql = "UPDATE `clases` 
				SET `titulo`= :nameS,`fecha_hora_clase`= :dateS
				{$upTe} WHERE `idClase`= :idSession";

				$statement = $con->prepare($sql); 		  
				$statement->execute($post);

				$response = ['estatus'=>'ok',"data"=>$statement->rowCount()];
				
				$conexion = null;
				$con = null;
				return $response;
			}
		}
		public function listarGeneraciones($idCarrera){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT idGeneracion, nombre, id_plan_estudio,grupos FROM a_generaciones WHERE idCarrera = $idCarrera ORDER BY secuencia_generacion";
				$statement = $con->prepare($sql); 		  
				$statement->execute();

				$response = $statement->fetchAll(PDO::FETCH_ASSOC);
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}//listarGeneraciones

		public function listarCiclos($idPlan){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$sql = "SELECT DISTINCT tipo_ciclo, ciclo_asignado 
				FROM planes_materias, planes_estudios 
				WHERE id_plan = $idPlan 
				AND id_plan = id_plan_estudio 
				ORDER BY ciclo_asignado";
				$statement = $con->prepare($sql); 		  
				$statement->execute();

				$response = $statement->fetchAll(PDO::FETCH_ASSOC);

				$conexion = null;
				$con = null;
				return $response;
			}
		}//listarCiclos

		public function listarMaterias($idCiclo, $idPlan){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];
			
			$where = '';
			$order = '';
			if($idCiclo != ''){
				$where = "and ciclo_asignado = '$idCiclo'";
				$order = 'ORDER BY materias.nombre';
			}
			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT planes_materias.id_materia AS id_materia, materias.nombre,descriptionM,imagen,title FROM materias, planes_materias 
				WHERE id_plan = $idPlan $where 
				AND planes_materias.id_materia = materias.id_materia $order";
				$statement = $con->prepare($sql); 		  
				$statement->execute();

				$response = $statement->fetchAll(PDO::FETCH_ASSOC);

				$conexion = null;
				$con = null;
				return $response;
			}
		}//listarCiclos

		public function consultarAsistenciaEventos(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT DISTINCT asistentes_eventos.id_evento, ev_evento.titulo, ev_evento.fechaE FROM asistentes_eventos, ev_evento WHERE asistentes_eventos.id_evento = ev_evento.idEvento ORDER BY titulo;";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarAsistenciaEventos

		public function consultarAsistenciaTalleres(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT DISTINCT asistentes_eventos.id_taller, ev_talleres.nombre AS taller, asistentes_eventos.id_evento, ev_evento.titulo AS evento, ev_talleres.fecha 
				FROM asistentes_eventos, ev_evento, ev_talleres 
				WHERE asistentes_eventos.id_evento = ev_evento.idEvento AND asistentes_eventos.id_taller=ev_talleres.id_taller;";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarAsistenciaTalleres

		public function consultarAsistenciaClases($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT class.idClase, class.titulo, gen.nombre as nombreGen, class.fecha_hora_clase
				FROM clases class
				INNER JOIN materias mat ON mat.id_materia = class.idMateria
                INNER JOIN a_generaciones gen ON gen.idGeneracion = class.idGeneracion
				WHERE  class.idClase IS NOT NULL AND class.estado = 1 AND gen.idCarrera = :idCarr
				GROUP BY class.idClase";

				/*$sql = "SELECT DISTINCT asisEvent.NumeroClase, asisEvent.hora, aCarr.nombre, class.titulo
				FROM asistentes_eventos asisEvent 
				LEFT JOIN a_carreras aCarr ON aCarr.idCarrera = asisEvent.idCarrera
				LEFT JOIN clases class ON class.idClase = asisEvent.NumeroClase
				WHERE asisEvent.NumeroClase IS NOT NULL";*/

				$statement = $con->prepare($sql); 	
				//$statement->bindParam(':idCarrera',$id);
				$statement->execute($id);			  
			}
		$conexion = null;
		$con = null;
		return $statement;
		}//consultarAsistenciaClases

		public function validarAsistencias($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
					FROM asistentes_eventos
					WHERE NumeroClase = :id";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo()];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function buscarClasesCarrera($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response  = [];

			$Filtro = "";
			$whr = "WHERE idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11";
			if($data['vista'] == 2){
				$Filtro = "AND (idInstitucion = '13' OR (idInstitucion = '20' AND tipo = '2'))";
			}else if($data['vista'] == 4){
				$whr = "WHERE idCarrera = 14 or idCarrera = 19";
			}

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idCarrera, nombre
					FROM a_carreras
					{$whr}
					{$Filtro}
					ORDER BY nombre";

				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql',$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function datosPDFAsistencias($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT class.titulo, class.fecha_hora_clase, asisEvent.hora, UPPER(CONCAT( aPros.aPaterno, ' ', aPros.aMaterno, ', ', aPros.nombre)) as nombre, mat.nombre as nombre_mat, carr.idInstitucion
					FROM asistentes_eventos asisEvent
					INNER JOIN clases class ON class.idClase = asisEvent.NumeroClase
					INNER JOIN materias mat ON mat.id_materia = class.idMateria
					INNER JOIN a_carreras carr ON carr.idCarrera = mat.id_carrera
					INNER JOIN a_prospectos aPros ON aPros.idAsistente = asisEvent.id_asistente
					WHERE asisEvent.NumeroClase = :id
					ORDER BY nombre";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
				$statement->execute();
	
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

		public function datosPDFAsistenciasTalleres($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT taller.nombre as nombreT, evento.idEvento, taller.fecha, UPPER(CONCAT( aPros.aPaterno, ' ', aPros.aMaterno, ', ', aPros.nombre)) as nombre, asisEvent.hora
					FROM asistentes_eventos asisEvent
					INNER JOIN ev_talleres taller ON taller.id_taller = asisEvent.id_taller
					INNER JOIN ev_evento evento ON evento.idEvento = taller.id_evento
					INNER JOIN a_prospectos aPros ON aPros.idAsistente = asisEvent.id_asistente
					WHERE asisEvent.id_taller = :id
					ORDER BY nombre";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
				$statement->execute();
	
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


		public function datosPDFAsistenciaEventos($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT *
				 FROM ev_evento as ev
				WHERE ev.idEvento = :id";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
				$statement->execute();
	
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

		public function ActualizaTipo($data){
			//var_dump($data);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "UPDATE alumnos_generaciones
				SET diplomado = 2 
				WHERE idalumno = :id_Alu AND idgeneracion = :id_Gen;";
	
				$statement = $con->prepare($sql);
				$statement->execute($data);
	
				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function datosPDFExamen($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT  curExam.*,UPPER(CONCAT( aPros.aPaterno, ' ', aPros.aMaterno, ', ', aPros.nombre)) AS nombreAlumno, carr.nombre AS carrera, carr.idInstitucion, CONCAT( maestro.aPaterno, ' ', maestro.aMaterno, ', ', maestro.nombres) AS maestro, mat.nombre AS materia, curExamResultInDos.calificacion, curExamResult.fechaPresentacion
				FROM cursos_examen curExam
				INNER JOIN alumnos_generaciones alumGen ON alumGen.idgeneracion = curExam.id_generacion 
				INNER JOIN a_carreras carr ON carr.idCarrera = curExam.id_carrera
				INNER JOIN maestros maestro ON maestro.id = curExam.idMaestro
				INNER JOIN materias mat ON mat.id_materia = curExam.idCurso
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
				LEFT JOIN (SELECT MAX(curExamResultIn.fechaPresentacion) AS fechaPresentacion, MAX(curExamResultIn.idResultado) AS idResultado,curExamResultIn.calificacion as calificacion, curExamResultIn.idAlumno as idAlumno, curExamResultIn.idExamen as idExamen
                           FROM curso_examen_alumn_resultado as curExamResultIn
                           WHERE curExamResultIn.idExamen = :id AND fechaPresentacion = curExamResultIn.fechaPresentacion
                           GROUP BY curExamResultIn.idAlumno) as curExamResult ON curExamResult.idAlumno = alumGen.idalumno AND curExamResult.idExamen = :id
                           
                LEFT JOIN curso_examen_alumn_resultado as curExamResultInDos ON curExamResult.idResultado = curExamResultInDos.idResultado
				WHERE curExam.idExamen = :id
				ORDER BY nombreAlumno ASC, curExamResult.fechaPresentacion DESC;";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
				$statement->execute();
	
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

		public function consultarAlumnos(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				/*$sql = "SELECT DISTINCT id_afiliado, afiliados_conacon.id_prospecto, nombre, apaterno, amaterno, email, celular, pagos_conceptos.descripcion, a_pagos.id_concepto, 

				(SELECT COUNT(*) FROM documentos WHERE documentos.id_prospectos=id_afiliado) as docs 
				
				FROM afiliados_conacon, a_pagos, pagos_conceptos 
				
				WHERE afiliados_conacon.id_prospecto = a_pagos.id_prospecto 
						AND pagos_conceptos.id_concepto = a_pagos.id_concepto 
						AND pagos_conceptos.id_concepto != 1 
						AND pagos_conceptos.id_concepto != 3 
						AND pagos_conceptos.id_concepto != 4 
						AND pagos_conceptos.id_concepto != 6 
						AND pagos_conceptos.id_concepto != 8 
						AND pagos_conceptos.id_concepto != 10 
						AND pagos_conceptos.id_concepto != 11 
						AND pagos_conceptos.id_concepto != 12 
						AND pagos_conceptos.id_concepto != 13 
						AND pagos_conceptos.id_concepto != 14 
				
				GROUP BY id_afiliado";*/

				/*$sql = "SELECT aPros.correo, aPros.telefono, gen.idGeneracion, gen.nombre AS ngeneracion, alumGen.idalumno AS id_prospecto, 
				aPros.nombre, aPros.aPaterno, aPros.aMaterno, afiliados_conacon.id_afiliado,  
				(SELECT COUNT(*) FROM documentos WHERE documentos.id_prospectos = alumGen.idalumno) as docs 
				FROM afiliados_conacon, a_generaciones gen INNER JOIN alumnos_generaciones alumGen ON alumGen.idgeneracion = gen.idGeneracion 
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno 
				WHERE gen.idCarrera = ".$_GET[ 'idCarrera' ]." AND gen.idGeneracion = ".$_GET[ 'idGeneracion' ]." AND 
				afiliados_conacon.id_prospecto = alumGen.idalumno";*/

				$complete = '';
				if(isset($_GET['groupG']) && $_GET['groupG'] != ''){
					$id = $_GET[ 'groupG' ];
					$complete = "and alumGen.grupo = '$id'";
				}

				$sql = "SELECT aPros.correo, aPros.telefono, gen.idGeneracion, gen.nombre AS ngeneracion, alumGen.idalumno AS id_prospecto, alumGen.diplomado AS diplomado, afiliados_conacon.pais_nacimiento as pais,
				UPPER(aPros.nombre) AS nombre, UPPER(aPros.aPaterno) AS aPaterno, UPPER(aPros.aMaterno) AS aMaterno, afiliados_conacon.id_afiliado, afiliados_conacon.celular as telefono,alumGen.diplomado as Diplomado, alumGen.idalumno as idAlumnoGeneracion,
				(SELECT COUNT(*) 
					FROM documentos 
					WHERE documentos.id_prospectos = afiliados_conacon.id_afiliado) as docs,
				(SELECT COUNT(*) 
					FROM documentos 
					WHERE documentos.id_prospectos = afiliados_conacon.id_afiliado AND validacion = 0) as docs_pendientes,
				(SELECT COUNT(*)
					FROM prorroga_documentos_alumno
					WHERE id_alumno = afiliados_conacon.id_afiliado AND fecha_prorroga_digital IS NOT NULL) as prorrogaDig,
				(SELECT COUNT(*)
					FROM prorroga_documentos_alumno
					WHERE id_alumno = afiliados_conacon.id_afiliado AND fecha_prorroga_fisica IS NOT NULL) as prorrogaFis 
				FROM afiliados_conacon, a_generaciones gen 
				INNER JOIN alumnos_generaciones alumGen ON alumGen.idgeneracion = gen.idGeneracion 
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno 
				WHERE gen.idCarrera = ".$_GET[ 'idCarrera' ]." AND gen.idGeneracion = ".$_GET[ 'idGeneracion' ]." AND 
				afiliados_conacon.id_prospecto = alumGen.idalumno {$complete}";

				$statement = $con->prepare($sql);
				$statement->execute();			  
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarAlumnos

		public function consultarExpediente( $idBuscar ){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			//$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT lista.nombre_documento as nombreDoc, doc.*, afcon.pais AS nacionalidad
				FROM documentos AS doc
				JOIN afiliados_conacon as afcon ON afcon.id_afiliado = :id
				JOIN listado_documentos as lista ON lista.id_documento = doc.id_documento AND lista.estatus = 1
				WHERE doc.id_prospectos = :id;"; 

				$statement = $con->prepare($sql); 
				$statement->bindParam(":id", $idBuscar);			  
				$statement->execute();
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarExpediente		

		public function buscarExpediente($buscar){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM documentos WHERE id_prospectos = $buscar";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)  ];					
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}//Fin buscarExpediente

		public function validarExpediente($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$validacion = ''; $comentario = ''; $separador = " ";

				for( $i = 1; $i <= 24; $i++){
					if( isset( $_POST['select'.$i] ) && $_POST['select'.$i] != 0 ){
							if( isset( $_POST['select'.$i] ) ) $validacion = 'validacion = '.$_POST['select'.$i];
							if( isset( $_POST['comentario'.$i] ) ) $comentario = 'comentario = "'.$_POST['comentario'.$i].'"';
							if( $validacion != '' && $comentario != '' ) $separador = " , "; else $separador = " ";
							if( $validacion != '' || $comentario != '' ){
								$sql = "UPDATE documentos SET ".$validacion." $separador ".$comentario." , fecha_validacion = now() WHERE id=".$_POST['iddocumento'.$i];
								$statement = $con->prepare($sql);
								$statement->execute();
							}
					}//fin if
							$validacion = ''; $comentario = ''; $separador = " ";					
				}//for

				if( isset( $statement ) ){
					if($statement->errorInfo()[0] == "00000"){
						$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
					}
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//Fin validarExpediente
		
		public function consultarExamenes($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT cursos_examen.*, materias.nombre AS materia, a_carreras.nombre AS carrera, CONCAT( maestros.aPaterno, ' ', maestros.aMaterno, ', ', maestros.nombres) AS maestro
				FROM cursos_examen, materias, a_carreras, maestros
				WHERE cursos_examen.tipo_examen != 3 AND cursos_examen.idCurso = materias.id_materia AND a_carreras.idCarrera = materias.id_carrera AND maestros.id = cursos_examen.idMaestro AND cursos_examen.id_generacion = :idGen;";

				$statement = $con->prepare($sql); 		  
				$statement->execute($id);			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarExamenes

		public function consultarExamenesPorID($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT cursos_examen.*, materias.nombre AS materia, a_carreras.nombre AS carrera, CONCAT( maestros.aPaterno, ' ', maestros.aMaterno, ', ', maestros.nombres) AS maestro
				FROM cursos_examen, materias, a_carreras, maestros
				WHERE cursos_examen.tipo_examen != 3 AND cursos_examen.idCurso = materias.id_materia AND a_carreras.idCarrera = materias.id_carrera AND maestros.id = cursos_examen.idMaestro AND cursos_examen.id_generacion = :idGen AND cursos_examen.Examen_ref IS NULL;";

				$statement = $con->prepare($sql); 		  
				$statement->execute($id);
				
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)  ];					
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
				}
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}

		public function asignarClase($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				//Verificación de recursos:
					/*$recursos = '';
					for( $i = 1; $i <= 10; $i++ )
						if( $data["nr$i"] != '' && $data["urlr$i"] != '' ){
							$data["urlr$i"] = str_replace(" ", "%20", $data["urlr$i"]);
							$data["urlr$i"] = str_replace("/", "\/", $data["urlr$i"]);
							$recursos .= '["'.$data["urlr$i"].'","'.$data["nr$i"].'"]';
						}
					if( $recursos != '' ){
						$recursos = "[".str_replace("][", "],[", $recursos)."]";
					}*/

				//Verificación de apoyos:
					/*$apoyos = '';
					for( $i = 1; $i <= 10; $i++ )
						if( $data["urly$i"] != '' && $data["ny$i"] != '' ){
							$data["urly$i"] = str_replace(" ", "%20", $data["urly$i"]);
							$data["urly$i"] = str_replace("/", "\/", $data["urly$i"]);
							$apoyos .= '["'.$data["urly$i"].'","'.$data["ny$i"].'"]';
						}
					if( $apoyos != '' ){
						$apoyos = "[".str_replace("][", "],[", $apoyos)."]";
					}*/ 

				//Para foto de la clase:
				
				$recursos = '';
				$apoyos = '';
				$archivo_foto = '';
				$sql = "INSERT INTO clases (idGeneracion, idMateria, titulo, fecha_hora_clase, idMaestro, video, recursos, apoyo, foto) 
				VALUES ( '".$data['SGeneraciones']."', '".$data['Smaterias']."', '".$data['nombre_clase']."', '".$data['fecha_clase']."', '".$data['idMaestroAsignacion']."', '".$_POST['video']."', '".$recursos."', '".$apoyos."', '".$archivo_foto."' )";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//Fin asignarClase

		public function consultarClasesMaestros( $idMaestro,$id ){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$complete = '';
				if($id == 4){
					$complete = "and (a_carreras.idCarrera = 14 or a_carreras.idCarrera = 19)";
				}

				$sql = "SELECT clases.idClase, clases.titulo, clases.fecha_hora_clase, clases.estado, materias.nombre AS nombreMateria, materias.id_carrera, a_carreras.nombre AS nombreCarrera 
				FROM clases, materias, a_carreras 
				WHERE clases.idMaestro = ".$idMaestro." AND clases.idMateria = materias.id_materia 
				AND materias.id_carrera = a_carreras.idCarrera AND clases.estado != 2 {$complete} ORDER BY clases.fecha_hora_clase desc";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarClasesMaestros

		public function desactivarClase($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE clases SET estado = :vEstado WHERE idClase = :idDesactivar";
				$statement = $con->prepare($sql);
				$statement->execute($data);
				//echo "OK";

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//fin desactivarClase

		public function listarCarrerasExpedientes($idAcc){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				if($idAcc == 4){
					$whr = "WHERE idCarrera = 14 or idCarrera = 19 ";
				}else{
					$whr = "WHERE idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11 ";
				}
				$sql = "SELECT idCarrera, nombre FROM a_carreras {$whr}ORDER BY nombre";

				$statement = $con->prepare($sql); 		  
				$statement->execute();
				
				$response = $statement->fetchAll(PDO::FETCH_ASSOC);			  
					
				$conexion = null;
				$con = null;
				return $response;
			}

		}//listarCarrerasExpedientes

		public function listarGeneracionesExpedientes(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$FiltroGenMed = "";
				if($_SESSION['usuario']['estatus_acceso'] == 4){
					$FiltroGenMed = "and secuencia_generacion >= 13";
				}
				
				$sql = "SELECT idGeneracion, nombre ,grupos
					FROM a_generaciones 
					WHERE idCarrera = ".$_GET['idCarrera']." {$FiltroGenMed}
					ORDER BY secuencia_generacion ASC";

				$statement = $con->prepare($sql);
				$statement->execute();
				
				$response = $statement->fetchAll(PDO::FETCH_ASSOC);			  
					
				$conexion = null;
				$con = null;
				return $response;
			}

		}//listarCarrerasExpedientes

		//mike
		public function crearClase($SGeneraciones, $Smaterias, $nombre_clase, $fecha_clase, $idMaestroAsignacion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];


			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO clases
					(idGeneracion, idMateria, titulo, video, recursos, apoyo, fecha_hora_clase, idMaestro, foto, estado)
					VALUES(:idGen, :idMat , :nomClase, '', '', '', :fechaClase, :idMaestro, '', 1)";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idGen', $SGeneraciones);
				$statement->bindParam(':idMat', $Smaterias);
				$statement->bindParam(':nomClase', $nombre_clase);
				$statement->bindParam(':fechaClase', $fecha_clase);
				$statement->bindParam(':idMaestro', $idMaestroAsignacion);

				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function subirfotoClase($nombreFoto, $idClase){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE clases SET foto = :nameFoto WHERE idClase = :idClass";

				$statement = $con->prepare($sql);
				$statement->bindParam(':nameFoto',$nombreFoto);
				$statement->bindParam(':idClass',$idClase);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function subirRecursosClase($recursos, $idClase){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE clases SET recursos = :recursos WHERE idClase = :idClass";

				$statement = $con->prepare($sql);
				$statement->bindParam(':recursos',$recursos);
				$statement->bindParam(':idClass',$idClase);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function subirApoyosClase($apoyos, $idClase){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE clases SET apoyo = :apoyos WHERE idClase = :idClass";

				$statement = $con->prepare($sql);
				$statement->bindParam(':apoyos', $apoyos);
				$statement->bindParam(':idClass', $idClase);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok' , 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		
		public function obtenerGeneracionesCarrera($idCarrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idGeneracion, nombre FROM a_generaciones
					 WHERE idCarrera = :idCarr AND estatus = 1";
				
				$statement = $con->prepare($sql);
				$statement->execute($idCarrera);

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

		public function obtenerCarrera_ref($idCarrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT plan.id_carrera, exm.idExamen as IDExamen_edit, exm.idCurso, exm.Nombre as Nombre_edit, pm.id_materia, COUNT(cep.idPregunta) as num_preguntas FROM `planes_estudios` as plan
				JOIN planes_materias as pm ON pm.id_plan = plan.id_plan_estudio
				JOIN cursos_examen exm ON exm.idCurso = pm.id_materia
                JOIN cursos_examen_preguntas as cep ON cep.idExamen = exm.idExamen
				WHERE plan.id_carrera = $idCarrera GROUP BY exm.idExamen;";
				
				$statement = $con->prepare($sql);
				$statement->execute($idCarrera);

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

		public function obtenerMaestros($idCarrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT mas.id, CONCAT(mas.nombres,' ',mas.aPaterno,' ',mas.aMaterno) as nombres 
					FROM maestros_carreras maestrosCarr
					INNER JOIN maestros mas ON mas.id = maestrosCarr.idMaestro
					WHERE maestrosCarr.idCarrera = :idCarr";
				
				$statement = $con->prepare($sql);
				$statement->execute($idCarrera);

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

		public function buscarPlanEstudioGeneracion($idGeneracion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT id_plan_estudio
					FROM a_generaciones
					WHERE idGeneracion = :idGen";

				$statement = $con->prepare($sql);
				$statement->execute($idGeneracion);

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

		public function BuscarIdInstitucion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idInstitucion 
				FROM a_carreras as ac 
				WHERE ac.idCarrera = :idCarr";

				$statement = $con->prepare($sql);
				$statement->execute($data);

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
		public function obtenerMaterias($idGeneracion, $idPlanEstudio){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT mat.id_materia, mat.nombre
					FROM planes_materias plMat
					INNER JOIN materias mat ON mat.id_materia = plMat.id_materia
					INNER JOIN avance_generaciones avGen ON avGen.ciclo_actual >= plMat.ciclo_asignado
					WHERE plMat.id_plan = :idPlan AND avGen.id_generacion = :idGen";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idGen', $idGeneracion);
				$statement->bindParam(':idPlan', $idPlanEstudio);
				$statement->execute();

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

		public function crearExamen($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			
			
			$costP = $data['costoPesos'];
			$costU = $data['costoUsd'];
			$nameMat = $data['nameMat'];

			unset($data['costoPesos']);
			unset($data['costoUsd']);
			unset($data['nameMat']);

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO cursos_examen
					(idCurso, Nombre, fechaInicio, fechaFin, idMaestro, id_generacion, id_carrera, preguntas_aplicar, multiple_intento, porcentaje_aprobar,Examen_ref,tipo_examen) VALUES 
					(:cursoExamen, :nombreExamen, :fechaInicioExamen, :fechaFinExamen, :selectMaestros, :examenGeneracion, :examenCarrera, :num_preguntas_retomar, :aplicar_multiple, :inp_porcentaje_aprobar_i, :id_examen_pasado,:aplicar_extraordinario)";
				
				$statement = $con->prepare($sql);
				$statement->execute($data);
				if($statement->errorInfo()[0] == "00000"){
				
				$idLast = $con->lastInsertId();

					if($data['aplicar_extraordinario'] == 2){
						$idCar = $data['examenCarrera'];
						$selectInst = "SELECT idInstitucion FROM a_carreras as ac WHERE ac.idCarrera = '$idCar'";
						$statementIn = $con->prepare($selectInst);
						$statementIn->execute();
						$statementIn->rowCount();
						$idIn = $statementIn->fetch(PDO::FETCH_ASSOC)['idInstitucion'];
						
						$dataConcept = [
							'idExmn'=>$idLast,
							'nombreExamen' => $data['nombreExamen'].'('.$nameMat.')',
							'examenGeneracion'=> $data['examenGeneracion'],
							'dateC'=> date('Y-m-d H:i:s'),
							'costsPesos' => $costP,
							'costsUsd' => $costU,
							'idInst' => $idIn,
						];
	
						$insertConcept = "INSERT INTO pagos_conceptos(`idExamen`,`concepto`, `descripcion`, `precio`, `precio_usd`, `categoria`, `pago_aplicar`, `idPlan_pago`, `id_generacion`, `parcialidades`, `fechalimitepago`, `eliminado`, `fechacreado`, `creado_por`, `actualizado_por`, `fecha_actualizado`, `numero_pagos`, `generales`, `institucion`, `idPlanConekta`) 
						VALUES (:idExmn,:nombreExamen,'pago de examen extraordinario',:costsPesos,:costsUsd,'General','',0,:examenGeneracion,2,NULL,0,:dateC,NULL,NULL,NULL,1,0,:idInst,NULL)";
						$statementConcept = $con->prepare($insertConcept);
						$statementConcept->execute($dataConcept);
	
						if($statementConcept->errorInfo()[0] == "00000"){
	
							$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
						}else{
							$response = ['estatus'=>'error', 'info'=>$statementConcept->errorInfo(), 'sql'=>$sql];
						}
					}else{
						$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
					}
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function Validar_tamanio($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = 'SELECT `idExamen` ,COUNT(*) as Total_preguntas FROM `cursos_examen_preguntas`  WHERE `idExamen`= :idExamen;';
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":idExamen",$data);
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

		public function obtenerDatosExamen($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT ce.*,pc.precio,pc.precio_usd
					FROM cursos_examen as ce
					LEFT JOIN pagos_conceptos as pc on pc.idExamen = ce.idExamen
					WHERE ce.idExamen = :idExamen";

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

		public function editarExamen($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			
			

			if($con['info'] == 'ok'){
				unset($data['costoPesos']);
				unset($data['costoUsd']);
				$con = $con['conexion'];
				$sql = "UPDATE cursos_examen 
					SET idCurso = :editarCursoExamen, Nombre = :editarNombreExamen, fechaInicio = :editarFechaInicioExamen, fechaFin = :editarFechaFinExamen, idMaestro = :editarSelectMaestros, id_generacion = :editarExamenGeneracion, id_carrera = :editarExamenCarrera, multiple_intento = :aplicar_multiple, porcentaje_aprobar = :inp_porcentaje_aprobar, preguntas_aplicar = :num_preguntas_retomar_e, Examen_ref = :id_examen_pasado_e
					WHERE idExamen = :idExamen";

				$statement = $con->prepare($sql);
				$statement->execute($data);

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

		public function crearExamenBanco($data){
			//var_dump($data);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			$costP = $data['costoPesosBanco'];
			$costU = $data['costoUsdBanco'];
			//$costP = 0;
			//$costU = 100;
			$nameMat = $data['nameMatBanco'];

			unset($data['costoPesosBanco']);
			unset($data['costoUsdBanco']);
			unset($data['nameMatBanco']);
			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO cursos_examen
					(idCurso, Nombre, fechaInicio, fechaFin, idMaestro, id_generacion, id_carrera, multiple_intento, porcentaje_aprobar, tipo_examen) VALUES 
					(:cursoExamenBanco, :nombreExamenBanco, :fechaInicioExamenBanco, :fechaFinExamenBanco, :selectMaestrosBanco, :examenGeneracionBanco, :CarreraBanco, :aplicar_multipleBanco, :inp_porcentaje_aprobar_iBanco, :aplicar_extraordinarioBanco)";
				
				$statement = $con->prepare($sql); 	

				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$idLast = $con->lastInsertId();

					if($data['aplicar_extraordinarioBanco'] == 2){
						$idCar = $data['CarreraBanco'];
						$selectInst = "SELECT idInstitucion FROM a_carreras as ac WHERE ac.idCarrera = '$idCar'";
						$statementIn = $con->prepare($selectInst);
						$statementIn->execute();
						$statementIn->rowCount();
						$idIn = $statementIn->fetch(PDO::FETCH_ASSOC)['idInstitucion'];
						
						$dataConcept = [
							'idExmn'=>$idLast,
							'nombreExamen' => $data['nombreExamenBanco'].'('.$nameMat.')',
							'examenGeneracion'=> $data['examenGeneracionBanco'],
							'dateC'=> date('Y-m-d H:i:s'),
							'costsPesos' => $costP,
							'costsUsd' => $costU,
							'idInst' => $idIn,
						];
	
						$insertConcept = "INSERT INTO pagos_conceptos(`idExamen`,`concepto`, `descripcion`, `precio`, `precio_usd`, `categoria`, `pago_aplicar`, `idPlan_pago`, `id_generacion`, `parcialidades`, `fechalimitepago`, `eliminado`, `fechacreado`, `creado_por`, `actualizado_por`, `fecha_actualizado`, `numero_pagos`, `generales`, `institucion`, `idPlanConekta`) 
						VALUES (:idExmn,:nombreExamen,'pago de examen extraordinario',:costsPesos,:costsUsd,'General','',0,:examenGeneracion,2,NULL,0,:dateC,NULL,NULL,NULL,1,0,:idInst,NULL)";
						$statementConcept = $con->prepare($insertConcept);
						$statementConcept->execute($dataConcept);
	
						if($statementConcept->errorInfo()[0] == "00000"){
	
							$response = ['estatus'=>'ok', 'data'=>$idLast];
						}else{
							$response = ['estatus'=>'error', 'info'=>$statementConcept->errorInfo(), 'sql'=>$sql];
						}
					}else{
						$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
					}
					//$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		//Funcionn en el model para insertar preguntas despues de hacer la creacion del exmane banco 
		public function insertarPreguntaExamenBanco($idExamen, $pregunta/*, $opciones*/){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO cursos_examen_preguntas
				(idExamen, pregunta, opciones)
				SELECT '$idExamen', pregunta, opciones
				FROM cursos_examen_preguntas
				WHERE idPregunta = $pregunta;";

				$statement = $con->prepare($sql);
				//$statement->bindParam(':idExam',$idExamen);
				//$statement->bindParam(':question',$pregunta);
				//$statement->bindParam(':options',$opciones);
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


		//fin mike

		public function obtenerExpedienteAlumno($id){
			$conexion = new conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT id_documento FROM documentos WHERE id_prospectos = :idAlumno";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function registrarDocumento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO `documentos`
				(id_prospectos, id_documento, nombre_archivo, tipo_estudio, validacion, fecha_entrega)
				VALUES(:idUsuario, :documento, :nName, :tipoEstudio, 0, :fEntrega)";
			
				$statement = $con->prepare($sql);
				$statement->execute($data);
				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}			
		$conexion = null;
		$con = null;

		return $response;
		}

		public function buscarGrados(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$id = '';

				$sql = "SELECT id_gradoE, nombre FROM grado_estudio";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerPreguntasExamenesRef($idCarrera){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT ce.idExamen, ce.Nombre, cep.pregunta as pregunta, ag.idGeneracion, ag.nombre as nombre_gen, cep.idPregunta as id_pregunta FROM cursos_examen as ce
				JOIN a_generaciones AS ag ON ag.idGeneracion = ce.id_generacion 
				JOIN cursos_examen_preguntas as cep on cep.idExamen = ce.idExamen
				WHERE ce.id_carrera = $idCarrera GROUP BY cep.pregunta;";
				
				$statement = $con->prepare($sql);
				$statement->execute();

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

		function registrarComprobanteEstudio($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "INSERT INTO `documentos`
					(id_prospectos, id_documento, nombre_archivo, tipo_estudio, validacion, fecha_entrega)
					VALUES(:idUsuario, :documento, :nName, :gradoEstudio, 0, :fEntrega)";
				
				$statement = $con->prepare($sql);
				//$statement->bindParam(':nNameI')
				$statement->execute($data);
	
				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}
		$conexion = null;
		$con = null;
	
		return $response;
		}
	public function validarVistaDocumentos($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT carr.idInstitucion, carr.tipo, gen.idGeneracion,carr.idCarrera
					FROM a_generaciones gen
					INNER JOIN a_carreras carr ON carr.idCarrera = gen.idCarrera
					WHERE gen.idGeneracion = :idGen";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}
		// chuy
		function consultar_clase_by_id($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con['conexion'];
			$sql = "SELECT cls.*,car.idCarrera, mat.id_materia, mat.nombre as nombre_materia, gen.idGeneracion, gen.nombre as nombre_generacion FROM `clases` cls  
				JOIN materias mat ON mat.id_materia = cls.idMateria
				JOIN a_carreras car ON mat.id_carrera = car.idCarrera
				JOIN a_generaciones gen ON gen.idCarrera = car.idCarrera WHERE cls.idClase = :clase AND gen.idGeneracion = cls.idGeneracion";
			$statement = $con->prepare($sql);
			$statement->bindParam(':clase', $id);
			$statement->execute();
			$conexion = null;
			$con = null;
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
		function actualizar_datos_clase($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con['conexion'];
			$recurso = '';
			$apoyo = '';
			if(isset($data['recursos'])){
				$recurso = 'recursos = :recursos,';
			}
			if(isset($data['apoyo'])){
				$apoyo = 'apoyo = :apoyo,';
			}
			$sql = "UPDATE `clases` SET titulo = :inp_edit_nombre, video = :inp_edit_link, {$recurso} {$apoyo} fecha_hora_clase = :inp_edit_fecha, idGeneracion = :select_generacion_edit, idMateria = :select_materias_edit WHERE idClase = :inp_edit_clase";
			$statement = $con->prepare($sql);
			$statement->execute($data);
			$conexion = null;
			$con = null;
			return $statement->rowCount();
		}

		public function validarEliminarClase($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT video
					FROM clases
					WHERE idClase = :idClass";

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

		public function eliminarClase($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE clases SET estado = 2 WHERE idClase = :idEliminar";

				$statement = $con->prepare($sql);
				$statement->execute($id);

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

		public function asignarProrrogaAlumnoDocumento($ids){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				/*
				$sql = "SELECT listDoc.id_documento, listDoc.nombre_documento
					FROM listado_documentos_generacion listDocGen
					INNER JOIN documentos doc ON doc.id_documento = listDocGen.id_documento
					INNER JOIN listado_documentos listDoc ON listDoc.id_documento = doc.id_documento
					WHERE listDocGen.id_generacion = :idGen AND doc.id_prospectos = :idAfi AND listDocGen.estatus = 1";*/
				/*bien
				$sql = "SELECT listDocUno.id_documento, listDocUno.nombre_documento
				FROM listado_documentos listDocUno
				INNER JOIN listado_documentos_generacion listDocGenUno ON listDocGenUno.id_documento = listDocUno.id_documento
				WHERE (listDocUno.estatus = 1 AND listDocGenUno.estatus = 1 AND listDocGenUno.id_generacion = :idGen) AND NOT EXISTS(SELECT *
																																FROM documentos
																																WHERE id_prospectos = :idAfi AND id_documento = listDocUno.id_documento)";*/
				$sql = "(SELECT listDocDos.id_documento, listDocDos.nombre_documento
								FROM listado_documentos listDocDos			
								INNER JOIN listado_documentos_generacion listDocGenDos ON listDocGenDos.id_documento = listDocDos.id_documento
								WHERE (listDocDos.estatus = 1 AND listDocGenDos.estatus = 1 AND listDocGenDos.id_generacion = :idGen))
						UNION
						(SELECT listDocUno.id_documento, listDocUno.nombre_documento
								FROM listado_documentos listDocUno
								INNER JOIN listado_documentos_generacion listDocGenUno ON listDocGenUno.id_documento = listDocUno.id_documento
								WHERE (listDocUno.estatus = 1 AND listDocGenUno.estatus = 1 AND listDocGenUno.id_generacion = :idGen))";



				$statement = $con->prepare($sql);
				$statement->execute($ids);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		
		$con = null;
		$conexion = null;
		return $response;
		}

		public function obtenerIdDocumentoAlumnoProrroga($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT id_prorroga, fecha_prorroga_fisica, fecha_prorroga_digital
				FROM prorroga_documentos_alumno
					WHERE id_alumno = :idAlum AND id_documento = :idDoc";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatua'=>'error', 'info'=>$statement->errorInfo(), 'slq'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerDatosProrrogaDigital($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT fecha_prorroga_digital
					FROM prorroga_documentos_alumno
					WHERE id_prorroga = :idProrr";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement, 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function alumnos_examen($idGeneracion, $extra){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			$Selects = "";
			$Joins = "";
			$where = "";
			//lISTA DE EXCEPCIONES PARA ENVIO DE CORREOS 
			$exceptions = "";

			if(is_array($extra)){
				$Selects = ",cal.calificacion, cal.idCalificacion, cal.numero_ciclo, mat.nombre, mat.id_materia";
				$Joins = "JOIN avance_generaciones as avaGen ON avaGen.id_generacion = algen.idgeneracion
						JOIN calificaciones as cal ON cal.idProspecto = ap.idAsistente AND cal.idGeneracion = algen.idgeneracion AND cal.id_materia = {$extra['cursoExamen']}
						JOIN materias as mat ON mat.id_materia = cal.id_materia";
				$where = "AND cal.calificacion < mat.calificacion_min";
			}
				if($con['info'] == 'ok'){
					if($exceptions != ""){
						$exceptions = "AND idAsistente NOT IN (SELECT alg.idalumno FROM alumnos_generaciones alg WHERE alg.idgeneracion = :idGeneracion AND alg.idalumno != {$exceptions})";
						//var_dump($exceptions);
					}
					$con = $con['conexion'];
					$sql = "SELECT concat(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno) as nombre_completo, afi.email,ac.idInstitucion, ap.idAsistente ,ac.nombre {$Selects}
							FROM a_prospectos as ap
							JOIN afiliados_conacon as afi on afi.id_prospecto=ap.idAsistente
							JOIN alumnos_generaciones as algen on algen.idalumno=ap.idAsistente
							JOIN a_generaciones as gen on gen.idGeneracion=algen.idgeneracion {$Joins}
							JOIN a_carreras as ac on ac.idCarrera=gen.idCarrera
							WHERE algen.idgeneracion = :idGeneracion {$where} {$exceptions}";

					$statement = $con->prepare($sql);
					$statement->bindParam(':idGeneracion',$idGeneracion);
					$statement->execute();

					if($statement->errorInfo()[0] == "00000"){
						$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
					}else{
						$response = ['estatus'=>'error', 'info'=>$statement, 'sql'=>$sql];
					}
				}
			$conexion = null;
			$con = null;
			return $response;
		
		}

		public function modificarProrrogaDigital($fecha){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE prorroga_documentos_alumno 
					SET fecha_prorroga_digital = :modificarFechaDigital
					WHERE id_prorroga = :idProrroga";

				$statement = $con->prepare($sql);
				$statement->execute($fecha);

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

		public function asignarFechaProrrogaDigital($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO prorroga_documentos_alumno
					(id_alumno, id_documento, fecha_prorroga_digital)VALUES(:idAlumno , :idDocumento, :fechaDigital)";

				$statement = $con->prepare($sql);
				$statement->execute($data);
				
				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$con = null;
		$conexion = null;
		return $response;
		}

		public function quitarProrrogaDocumento($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE prorroga_documentos_alumno 
					SET fecha_prorroga_digital = NULL
					WHERE id_prorroga = :id";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
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

		public function buscarRegistroProrroga($idAlumno, $idDocumento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
					FROM prorroga_documentos_alumno
					WHERE id_alumno = :idAlum AND id_documento = :idDoc";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->bindParam(':idDoc', $idDocumento);
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

		public function reactivarProrrogaDocumento($fecha, $idProrroga){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE prorroga_documentos_alumno 
					SET fecha_prorroga_digital = :fecha
					WHERE id_prorroga = :idProrroga";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':fecha',$fecha);
				$statement->bindParam(':idProrroga',$idProrroga);
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

		/*
		public function consultarDatos($idProrroga){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT id_alumno 
					FROM prorroga_documentos_alumno";

				$statement = $con->prepare($sql);
				$statement->execute($idProrroga);

				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}*/

		public function obtenerListaDocumentosFisicos($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT listDoc.id_documento, listDoc.nombre_documento, now() as fechaActual
					FROM listado_documentos_generacion listDocGen
					INNER JOIN listado_documentos listDoc ON listDoc.id_documento = listDocGen.id_documento
					WHERE listDocGen.id_generacion = :idGen AND listDoc.estatus = 1";

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

		function documentos_generacion($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "SELECT ldg.*, ld.nacionalidad as nacionalidad
				FROM listado_documentos_generacion as ldg
				JOIN listado_documentos as ld ON ld.id_documento = ldg.id_documento
				WHERE ldg.estatus = 1 AND ldg.id_generacion = :generacion;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":generacion", $id);
			$statement->execute();

			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function validar_documento_alumno($afiliado, $documento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "SELECT * FROM `documentos` WHERE id_prospectos = :afiliado AND id_documento = :documento;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":documento", $documento);
			$statement->bindParam(":afiliado", $afiliado);
			$statement->execute();

			return $statement->fetch(PDO::FETCH_ASSOC);
		}

		function validar_documento_fisico_alumno($afiliado, $documento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "SELECT * FROM `documentos_fisicos` WHERE id_afiliado = :afiliado AND id_documento = :documento;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":documento", $documento);
			$statement->bindParam(":afiliado", $afiliado);
			$statement->execute();

			return $statement->fetch(PDO::FETCH_ASSOC);
		}
		
		function validar_prorroga_documento_alumno($afiliado, $documento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "SELECT * FROM `prorroga_documentos_alumno` WHERE id_alumno = :afiliado AND id_documento = :documento;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":documento", $documento);
			$statement->bindParam(":afiliado", $afiliado);
			$statement->execute();

			return $statement->fetch(PDO::FETCH_ASSOC);
		}

		public function buscarDocumentosFisicos($idDocumento, $idAlumno){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
					FROM documentos_fisicos 
					WHERE id_documento = :idDoc AND id_afiliado = :idAlum";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idDoc', $idDocumento);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->execute();

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
		
		public function validar_existencia_documentos($idDocumento, $idAlumno){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
					FROM documentos_fisicos
					WHERE id_afiliado = :idAlum AND id_documento = :idDoc;";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idDoc', $idDocumento);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function registrarDocumentosFisicos($idDocumento, $idAlumno, $fRegistro, $idAdmin){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO documentos_fisicos
					(id_afiliado, id_documento, fecha_registro, id_admin)VALUES(:idAlum, :idDoc, :idReg, :idAdm)";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idDoc', $idDocumento);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->bindParam(':idReg', $fRegistro);
				$statement->bindParam(':idAdm', $idAdmin);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function reactivarRegistroDocumentoFisico($idDocumento, $idAlumno){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE SET 
					WHERE = :idDoc AND = :idAlum";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idDoc', $idDocumento);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerTodosRegistros($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
					FROM documentos_fisicos 
					WHERE id_afiliado = :idAlum";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idAlum', $id);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$recursos = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function reseteoDocumentosFisicos($idAlumno){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "DELETE FROM documentos_fisicos
				WHERE id_afiliado = :idAlum";


				$statement = $con->prepare($sql);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->execute();

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

		public function reseteoDocumentosEspecificosFisicos($idAlumno,$idDoc){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "DELETE FROM documentos_fisicos
				WHERE id_afiliado = :idAlum AND id_documento = :idDoc";


				$statement = $con->prepare($sql);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->bindParam(':idDoc', $idDoc);
				$statement->execute();

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

		public function recuperarChecksDocumentos($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT docFis.id_documento, docFis.fecha_registro, cont.nombres
					FROM documentos_fisicos docFis
					INNER JOIN a_accesos acc ON acc.idAcceso = docFis.id_admin
					INNER JOIN controlescolar cont ON cont.id = acc.idPersona  
					WHERE docFis.id_documento = :idDoc AND docFis.id_afiliado = :idAlum
					ORDER BY docFis.fecha_registro 	ASC";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function obtenerProrrogasFisica($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT listDocUno.id_documento, listDocUno.nombre_documento
					FROM listado_documentos listDocUno
					INNER JOIN listado_documentos_generacion listDocGenUno ON listDocGenUno.id_documento = listDocUno.id_documento
					WHERE (listDocUno.estatus = 1 AND listDocGenUno.estatus = 1 AND listDocGenUno.id_generacion = :idGen) AND NOT EXISTS(SELECT *
																																FROM documentos_fisicos
																																WHERE id_afiliado = :idAfi AND id_documento = listDocUno.id_documento)";

				$statement = $con->prepare($sql);
				$statement->execute($data);

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

		public function obtenerIdProrrogaDocumentoFisico($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT id_prorroga as idFisica, fecha_prorroga_fisica as fechaFisica
					FROM prorroga_documentos_alumno
					WHERE id_alumno = :idAlum AND id_documento = :idDoc";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatua'=>'error', 'info'=>$statement->errorInfo(), 'slq'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function asignarFechaProrrogaFisico($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO prorroga_documentos_alumno
					(id_alumno, id_documento, fecha_prorroga_fisica)VALUES(:idAlumnoFisico , :idDocumentoFisico, :fechaFisico)";

				$statement = $con->prepare($sql);
				$statement->execute($data);
				
				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$con = null;
		$conexion = null;
		return $response;
		}

		public function reactivarProrrogaDocumentoFisico($fecha, $idProrroga){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE prorroga_documentos_alumno 
					SET fecha_prorroga_fisica = :fecha
					WHERE id_prorroga = :idProrroga";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':fecha',$fecha);
				$statement->bindParam(':idProrroga',$idProrroga);
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

		public function quitarProrrogaDocumentoFisico($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE prorroga_documentos_alumno 
					SET fecha_prorroga_fisica = NULL
					WHERE id_prorroga = :id";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
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
		

		public function obtenerDatosProrrogaFisico($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT fecha_prorroga_fisica
					FROM prorroga_documentos_alumno
					WHERE id_prorroga = :idProrr";

				$statement = $con->prepare($sql);
				$statement->execute($id);

				if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement, 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function modificarProrrogaFisico($fecha){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE prorroga_documentos_alumno 
					SET fecha_prorroga_fisica = :modificarFechaFisico
					WHERE id_prorroga = :idProrrogaFisico";

				$statement = $con->prepare($sql);
				$statement->execute($fecha);

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

		public function obtenerGeneracionesMaterias($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idGeneracion, nombre 
					FROM a_generaciones
					WHERE idCarrera = :idCarrera";

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
		public function actualizarestatusSolicituscred($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE `solicitud_credenciales` 
				SET `estatus` = :estat
				WHERE `solicitud_credenciales`.`idsolicitud` = :idSol;";

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
		public function obtenerSolicitudesCredenciales(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT solicred.idsolicitud, solicred.idalumno, afcon.id_prospecto as idPago,UPPER(CONCAT(apros.aPaterno,' ',apros.aMaterno, ' ', apros.nombre)) as nombre, avagen.ciclo_actual AS cicloActual,
				UPPER(alumgen.nombre) as NombreGen, UPPER(aCarr.nombre) AS nombreCarr, afcon.estatus as estatusAlum, solicred.fecha_solicitud, solicred.fecha_autorizacion, solicred.estatus estatuSol,solicred.idgeneracion, doc.nombre_archivo as foto, 					
				aCarr.idInstitucion as idCarrera,alumgen.idgeneracion,doc.validacion, afcon.matricula as Matricula  
				FROM a_prospectos AS apros
				LEFT JOIN afiliados_conacon AS afcon ON  afcon.id_prospecto = apros.idAsistente
				RIGHT JOIN (Select MAX(solic.idsolicitud) as idsolicitud, solic.idalumno idalumno, afcon.id_prospecto idPros, solic.idgeneracion
                            FROM solicitud_credenciales as solic
                            LEFT JOIN afiliados_conacon AS afcon ON solic.idalumno = afcon.id_afiliado
                            GROUP BY solic.idalumno) AS solc ON afcon.id_afiliado = solc.idalumno
                INNER JOIN solicitud_credenciales AS solicred ON solicred.idsolicitud = solc.idsolicitud
				LEFT JOIN alumnos_generaciones AS algen ON algen.idalumno = solc.idPros AND algen.idgeneracion = solc.idgeneracion
				LEFT JOIN avance_generaciones AS avagen ON avagen.id_generacion = solc.idgeneracion
				LEFT JOIN a_generaciones  AS alumgen ON  alumgen.idGeneracion = solc.idgeneracion
				LEFT JOIN planes_estudios AS planest ON planest.id_carrera = alumgen.idCarrera
				LEFT JOIN a_carreras as aCarr ON aCarr.idCarrera = alumgen.idCarrera
				LEFT JOIN documentos as doc ON doc.id_prospectos = solicred.idalumno  and doc.id_documento = '6'
				GROUP BY afcon.id_afiliado
                ORDER BY solicred.idsolicitud DESC;";

				$statement = $con->prepare($sql);
				$statement->execute();
			}
		return $statement;
		}


		public function consultarHistorialSolicitudes($data){
			$band = $data['band'];
			unset($data['band']);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT sol.*,UPPER(agen.nombre) as nombreGen, UPPER(acarr.nombre) nombreCarrera
				FROM solicitud_credenciales AS sol 
				JOIN a_generaciones AS agen ON agen.idGeneracion = sol.idgeneracion
				JOIN a_carreras AS acarr ON agen.idCarrera = acarr.idCarrera
				WHERE sol.idalumno = :idAlu";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($band == '1'){
					return $statement;
				}

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

		public function obtenerCiclosGeneracion($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT plMat.id_asignacion, plMat.ciclo_asignado, gen.tipoCiclo
				FROM a_generaciones gen
				INNER JOIN planes_materias plMat ON plMat.id_plan = gen.id_plan_estudio
				WHERE gen.idGeneracion = :idGen
				GROUP BY plMat.ciclo_asignado";

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

		public function obtenerCicloSeleccionado($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT ciclo_asignado 
					FROM planes_materias
					WHERE id_asignacion = :id";

				$statement = $con->prepare($sql);
				$statement->bindParam(':id', $id);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function consultarAsistenciaMaterias($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT mat.id_materia, mat.nombre, gen.idGeneracion
				FROM a_generaciones gen
				INNER JOIN planes_materias plMat ON plMat.id_plan = gen.id_plan_estudio AND plMat.ciclo_asignado = :idNumeroCiclo
				INNER JOIN materias mat ON mat.id_materia = plMat.id_materia
				WHERE gen.idGeneracion = :idGen";

				$statement = $con->prepare($sql);
				$statement->execute($id);
			}
		return $statement;
		}

		public function datosPDFAsistenciasMaterias($id, $idGeneracion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT UPPER(class.titulo) as titulo, asisEvent.hora, alumGen.idalumno, UPPER(mat.nombre) as nombre_mat, UPPER(carr.nombre) as nombreCarr, carr.idInstitucion, carr.tipo, UPPER(CONCAT( aPros.aPaterno, ' ', aPros.aMaterno, ' ', aPros.nombre)) as nombre, class.fecha_hora_clase, UPPER(CONCAT(maestro.nombres,' ',maestro.aPaterno,' ',maestro.aMaterno)) as nombreMaestro
					FROM clases class
					INNER JOIN alumnos_generaciones alumGen ON class.idGeneracion = alumGen.idgeneracion
					INNER JOIN materias mat ON  mat.id_materia = class.idMateria
					INNER JOIN maestros maestro ON maestro.id = class.idMaestro
					INNER JOIN a_carreras carr ON carr.idCarrera = mat.id_carrera
					INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
					LEFT JOIN asistentes_eventos asisEvent ON asisEvent.NumeroClase = class.idClase AND asisEvent.id_asistente = alumGen.idalumno
					WHERE class.idGeneracion = :idGen AND class.idMateria = :id AND class.estado != 2 AND alumGen.idalumno != 541 AND alumGen.idalumno != 48
					ORDER BY nombre";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
				$statement->bindParam(':idGen', $idGeneracion);
				$statement->execute();
	
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

		public function listaAlumnosGeneracionPDF($idGeneracion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT UPPER(CONCAT( aPros.aPaterno, ' ', aPros.aMaterno, ' ', aPros.nombre)) as nombre, alumGen.idalumno
				FROM alumnos_generaciones alumGen
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
				WHERE alumGen.idGeneracion = :idGen AND alumGen.idalumno != 541 AND alumGen.idalumno != 48
				ORDER BY nombre";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':idGen', $idGeneracion);
				$statement->execute();
	
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

		public function asistenciasAlumnoMateriaPDF($id, $idGeneracion, $idAlumno){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT asisEvent.hora, class.fecha_hora_clase
					FROM clases class
					LEFT JOIN asistentes_eventos asisEvent ON asisEvent.NumeroClase = class.idClase AND asisEvent.id_asistente = :idAlum
					WHERE class.idGeneracion = :idGen AND class.idMateria = :id AND class.estado = 1
					ORDER BY class.fecha_hora_clase ASC";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':id',$id);
				$statement->bindParam(':idGen', $idGeneracion);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->execute();
	
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

		function consultar_desempenio_alumno($alumno, $materia, $plan_estudios){
			$resp = [
				"tareas"=>[],
				"examenes"=>[]
			];
			$conexion = new Conexion();
			$con = $conexion->conectar()["conexion"];
			// consultar tareas
			$q_tareas = "SELECT trs.titulo, entr.calificacion, entr.idAlumno FROM `clases` cls
				JOIN clases_tareas trs ON trs.idClase = cls.idClase
				JOIN clases_tareas_entregas entr ON entr.idTarea = trs.idTareas
				JOIN a_generaciones gen ON gen.idGeneracion = cls.idGeneracion
				WHERE entr.idAlumno = :alumno AND cls.idMateria = :materia AND gen.id_plan_estudio = :plan_estudios;";
			$stm_tar = $con->prepare($q_tareas);
			$stm_tar->bindParam(':alumno', $alumno);
			$stm_tar->bindParam(':materia', $materia);
			$stm_tar->bindParam(':plan_estudios', $plan_estudios);
			$stm_tar->execute();
			$resp["tareas"] = $stm_tar->fetchAll(PDO::FETCH_ASSOC);
			// consultar examenes
			$q_examenes = "SELECT exm.*, res.* FROM `cursos_examen` exm
				JOIN curso_examen_alumn_resultado res ON res.idExamen = exm.idExamen
				JOIN a_generaciones gen ON gen.idGeneracion = exm.id_generacion
				WHERE res.idAlumno = :alumno AND exm.idCurso = :materia AND gen.id_plan_estudio = :plan_estudios;";
			$stm_exa = $con->prepare($q_examenes);
			$stm_exa->bindParam(':alumno', $alumno);
			$stm_exa->bindParam(':materia', $materia);
			$stm_exa->bindParam(':plan_estudios', $plan_estudios);
			$stm_exa->execute();
			$resp["examenes"] = $stm_exa->fetchAll(PDO::FETCH_ASSOC);
			foreach ($resp["examenes"] as $kData => $valData) {
				$resp["examenes"][$kData]['calificacion'] = round($resp["examenes"][$kData]['calificacion'],1, PHP_ROUND_HALF_DOWN);
			}
			return $resp;
		}

		public function buscarGeneracion_PlanEstudio($idPlan){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
					FROM documentos_fisicos 
					WHERE id_documento = :idDoc AND id_afiliado = :idAlum";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idDoc', $idDocumento);
				$statement->bindParam(':idAlum', $idAlumno);
				$statement->execute();

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


		public function ConsultarCalPorciclo($idGeneracion,$idProspecto,$numero_ciclo){
			/*$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT  mat.id_materia,mat.nombre as nombre, calif.calificacion as cal From calificaciones as calif 
				JOIN materias AS mat ON calif.id_materia = mat.id_materia
				WHERE calif.idProspecto = $idAlumno AND calif.numero_ciclo = $idCiclo;";

				$statement = $con->prepare($sql);
				$statement->execute();

				/*if($statement->errorInfo()[0] == "00000"){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
			$conexion = null;
			$con = null;
			return $statement;*/
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT calif.*, agen.tipoCiclo, mat.nombre
				FROM calificaciones as calif
				JOIN a_generaciones as agen on agen.idGeneracion=calif.idGeneracion
				JOIN materias as mat on mat.id_materia=calif.id_materia
				WHERE calif.idGeneracion=:idGeneracion AND calif.idProspecto=:idProspecto AND calif.numero_ciclo=:numero_ciclo";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':idGeneracion', $idGeneracion);
				$statement->bindParam(':idProspecto', $idProspecto);
				$statement->bindParam(':numero_ciclo', $numero_ciclo);
	
				$statement->execute();
	
				
			}
			$conexion = null;
			$con = null;
			return $statement;
		}

		function verificar_calificacion_materia_alumno($alumno, $materia, $generacion, $ciclo){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "SELECT * FROM `calificaciones` WHERE idProspecto = :alumno AND id_materia = :materia AND idGeneracion = :generacion AND numero_ciclo = :ciclo;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":alumno", $alumno);
			$statement->bindParam(":materia", $materia);
			$statement->bindParam(":generacion", $generacion);
			$statement->bindParam(":ciclo", $ciclo);
			$statement->execute();
			if($statement->errorInfo()[0] == "00000"){
				return $statement->fetch(PDO::FETCH_ASSOC);
			}else{
				return $statement->errorInfo();
			}
		}

		function registrar_calificacion_materia_alumno($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "INSERT INTO `calificaciones` (`idProspecto`, `id_materia`, `idGeneracion`, `numero_ciclo`, `calificacion`,  `fechaRegistro`, `creadoPor`) VALUES (:alumno, :materia, :generacion, :ciclo, :calificacion, NOW(), :quien_registra);";
			$stmt = $con->prepare($sql);
			$stmt->execute($data);
			if($stmt->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$stmt->errorInfo(), 'sql'=>$sql];
			}
			return $response;
		}

		function actualizar_calificacion_materia_alumno($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "UPDATE `calificaciones` SET `calificacion` = :calificacion WHERE `idProspecto` = :alumno AND `id_materia` = :materia AND `idGeneracion` = :generacion AND `numero_ciclo` = :ciclo;";
			$stmt = $con->prepare($sql);
			$stmt->execute($data);
			if($stmt->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$stmt->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$stmt->errorInfo(), 'sql'=>$sql];
			}
			return $response;
		}

		function listar_calificaciones_alumnos($generacion, $materia, $ciclo, $alumnos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			$con = $con['conexion'];
			$sql = "SELECT * FROM `calificaciones` WHERE idGeneracion = :generacion AND id_materia = :materia AND numero_ciclo = :ciclo AND idProspecto IN (".$alumnos.")";
			$stmt = $con->prepare($sql);
			$stmt->bindParam(":generacion", $generacion);
			$stmt->bindParam(":materia", $materia);
			$stmt->bindParam(":ciclo", $ciclo);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function listaMaterias($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT mat.id_materia, mat.nombre, gen.idGeneracion
				FROM a_generaciones gen
				INNER JOIN planes_materias plMat ON plMat.id_plan = gen.id_plan_estudio AND plMat.ciclo_asignado = :idNumeroCiclo
				INNER JOIN materias mat ON mat.id_materia = plMat.id_materia
				WHERE gen.idGeneracion = :idGen";

				$statement = $con->prepare($sql);
				$statement->execute($id);
			}
		return $statement;
		}

		public function consultarCalificaciones($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT UPPER(CONCAT(aPros.aPaterno,' ',aPros.aMaterno, ' ', aPros.nombre)) as nombre, calif.calificacion, calif.idCalificacion, alumGen.idalumno
					FROM alumnos_generaciones alumGen
					INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
					LEFT JOIN calificaciones calif ON calif.idProspecto = alumGen.idalumno AND calif.id_materia = :idMat AND calif.idGeneracion = alumGen.idgeneracion
					WHERE alumGen.idgeneracion = :idGen
					ORDER BY nombre ASC";

				$statement = $con->prepare($sql);
				$statement->execute($data);
			}
		$conexion = null;
		$con = null;
		return $statement;
		}

		public function consultarCalificacionesGenCiclo($data){
			//var_dump($data);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT UPPER(CONCAT(aPros.aPaterno,' ',aPros.aMaterno, ' ', aPros.nombre)) as nombre, alumGen.idalumno as idalumno, afcon.matricula as Matricula
				FROM alumnos_generaciones alumGen
				INNER JOIN a_generaciones as a_gen ON a_gen.idCarrera = :idCarr AND a_gen.idGeneracion = alumGen.idgeneracion
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
				LEFT JOIN calificaciones calif ON calif.idProspecto = alumGen.idalumno AND calif.numero_ciclo = :idCiclo
				JOIN afiliados_conacon AS afcon on  afcon.id_prospecto = alumGen.idalumno
				WHERE alumGen.idgeneracion = :idGen
				GROUP BY alumGen.idalumno  ORDER BY nombre ASC;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
			}

			//var_dump($statement);
			$conexion = null;
			$con = null;
			return $statement;
		}

		public function consultarAlumnosGen($data){
			//ar_dump($data);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT UPPER(CONCAT(aPros.aPaterno,' ',aPros.aMaterno, ' ', aPros.nombre)) as nombre, calif.calificacion , calif.idCalificacion, alumGen.idalumno as idalumno, afcon.Matricula as Matricula, 
				alumGen.fecha_liberacion as fechalib, alumGen.calificacion as calificacionT, alumGen.estatus as estatus, afcon.estatus as AfEstatus
				FROM alumnos_generaciones alumGen
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
				LEFT JOIN calificaciones calif ON calif.idProspecto = alumGen.idalumno
				JOIN afiliados_conacon AS afcon on  afcon.id_prospecto = alumGen.idalumno
				WHERE alumGen.idgeneracion = :idGen
				GROUP BY alumGen.idalumno ORDER BY nombre ASC;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
			}

			//var_dump($statement);
			$conexion = null;
			$con = null;
			return $statement;
		}

		

		public function InsertarComentarioServicio($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO com_documentos_servicio (iddocumento, comentario, autor, fecha) 
				VALUES (:idArchivo, :ComentarioArchivo, 1, NOW());";

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

		public function CambiarEstatusDocumentoServicio($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE doc_alumnos_servicio SET estatus =:estatus  WHERE iddocumento =:idArchivo
				";

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

		public function validarEntregaDoc($data){
			//var_dump($data);
			//unset($data['numEnvio']); 
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT docal.*,docal.iddocumento as iddocumento  FROM doc_alumnos_servicio as docal 
				WHERE docal.idproceso = :idProceso AND docal.idformato = :idFormato AND numEnvio = :numEnvio AND idalumno = :idAlumno ORDER BY docal.iddocumento DESC LIMIT 1;";
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == 0000){
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

		public function ConsultaFormatosRevision($band){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT dps.*,ps.idproceso, ps.nombre AS procNombre FROM doc_procesos_servicio dps
					LEFT JOIN procesos_servicio as ps ON ps.idproceso = dps.idproceso
					ORDER BY dps.idproceso;";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($band != 1 ){
					return $statement;
				} 

				if($statement->errorInfo()[0] == 0000){
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


		public function CambiarestatusServicio($data){
			//var_dump($data);
			//status 1, 2, 3, En solicitud, Solicitar Originales, Concluido 
			$conexion = new Conexion();
				$con = $conexion->conectar();
				$response = [];

				if($con['info']=='ok'){
					$con = $con['conexion'];

					$sql = "UPDATE articulo_alumnos_servicio 
					SET estatus = :estatus
					WHERE idAlumno = :idAlu;";

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
			//return 1;
		
		}
		

		public function consultarDocumentosAlumnosServicio($status){
			//ar_dump($data);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT UPPER(CONCAT(aPros.aPaterno,' ',aPros.aMaterno, ' ', aPros.nombre)) as nombre, alumGen.idalumno as idalumno, afcon.Matricula as Matricula, 
				acarr.nombre as NombreCarr,age.nombre as nombreGen, arServ.idarticulo as Articulo, afcon.id_afiliado as idAfiliado,
                doc_alum.iddocumento,doc_alum.idproceso, doc_alum.idformato, doc_alum.nombre nombredocAlum, doc_alum.intento, docproc.nombre as nombredocproc, proces.nombre as nombreproc, arServ.estatus AS estatusServ
				FROM alumnos_generaciones alumGen
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
				JOIN afiliados_conacon AS afcon on  afcon.id_prospecto = alumGen.idalumno
				LEFT JOIN articulo_alumnos_servicio as arServ ON arServ.idalumno =  alumGen.idalumno
                LEFT JOIN doc_alumnos_servicio AS doc_alum ON doc_alum.idalumno = afcon.id_afiliado
                LEFT JOIN doc_procesos_servicio AS docproc ON docproc.idproceso = doc_alum.idproceso
                INNER JOIN procesos_servicio AS proces ON proces.idproceso = doc_alum.idproceso
                INNER JOIN a_generaciones as age ON age.idGeneracion = alumGen.idgeneracion
                INNER JOIN a_carreras as acarr ON acarr.idCarrera = age.idCarrera
                
				WHERE doc_alum.estatus = {$status}
				GROUP BY alumGen.idalumno ORDER BY nombre ASC;";

				$statement = $con->prepare($sql);
				$statement->execute();
			}

			//var_dump($statement);
			$conexion = null;
			$con = null;
			return $statement;
		}

		public function verComentariosArchivo($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
				FROM com_documentos_servicio
				WHERE iddocumento = :idArch
				ORDER BY fecha DESC;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
			}

			$conexion = null;
			$con = null;
			return $statement;
		}

		public function consultarAlumnosGenAsinados($data){
			//ar_dump($data);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT UPPER(CONCAT(aPros.aPaterno,' ',aPros.aMaterno, ' ', aPros.nombre)) as nombre, calif.calificacion , calif.idCalificacion, alumGen.idalumno as idalumno, afcon.Matricula as Matricula, 
				alumGen.fecha_liberacion as fechalib, alumGen.calificacion as calificacionT, alumGen.estatus as estatus, afcon.estatus as AfEstatus, arServ.idarticulo as Articulo
				FROM alumnos_generaciones alumGen
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
				LEFT JOIN calificaciones calif ON calif.idProspecto = alumGen.idalumno
				JOIN afiliados_conacon AS afcon on  afcon.id_prospecto = alumGen.idalumno
				LEFT JOIN articulo_alumnos_servicio as arServ ON arServ.idalumno =  alumGen.idalumno
				WHERE alumGen.idgeneracion = :idGen
				GROUP BY alumGen.idalumno ORDER BY nombre ASC;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
			}

			//var_dump($statement);
			$conexion = null;
			$con = null;
			return $statement;
		}
		public function actualizarTitulados($data){
			//echo '<br><br>';
			//var_dump($data);
			$conexion = new Conexion();
				$con = $conexion->conectar();
				$response = [];

				if($con['info']=='ok'){
					$con = $con['conexion'];

					$sql = "UPDATE alumnos_generaciones 
					SET fecha_titulacion = :fecha,
					estatus = 4
					WHERE idAlumno = :idAlumno AND idgeneracion = :id_Gen;";

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
			//return 1;
		
		}

		public function ObtenerCalificacionMinima($id){
				$conexion = new Conexion();
				$con = $conexion->conectar();
				$response = [];

				if($con['info']=='ok'){
					$con = $con['conexion'];
					$sql = "SELECT * 
					FROM materias as mat 
					WHERE mat.id_materia = $id;";

					$statement = $con->prepare($sql);
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

		public function CambiarCalificacionMinima($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info']=='ok'){
				$con = $con['conexion'];
				$sql = "UPDATE materias 
				SET calificacion_min = :CalNue 
				WHERE id_materia = :idMat;";

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
	
		public function obtenerCalificacion($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info']=='ok'){
				$con = $con['conexion'];
				$sql = "SELECT *
					FROM calificaciones 
					WHERE idCalificacion = :idCalif";

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

		public function cambiarCalificacion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE calificaciones 
					SET calificacion = :califAlum
					WHERE idCalificacion = :idCalif";

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

		public function insertarCalificacion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO calificaciones
					(id_materia, numero_ciclo, idProspecto, idGeneracion, calificacion, fechaRegistro, creadoPor)VALUES(:idMat, :ciclo, :idAlum, :idGen, :califAlum, :fecha, :idUsuarioRegistro)";

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

		public function buscarCicloMateria($idGeneracion, $idMateria){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT plMat.ciclo_asignado
				FROM a_generaciones gen
				INNER JOIN planes_materias plMat ON plMat.id_plan = gen.id_plan_estudio AND plMat.id_materia = :idMat
				WHERE gen.idGeneracion = :idGen";
			}

			$statement = $con->prepare($sql);
			$statement->bindParam(':idGen', $idGeneracion);
			$statement->bindParam(':idMat', $idMateria);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		function consultar_alumnos_reservaciones($data){
			$conexion = new Conexion();
        	$con = $conexion->conectar()['conexion'];

			$filtroMatch = "";
			if(isset($data["Alumnosolic"])){
				$filtroMatch = "AND prosp.idAsistente != :Alumnosolic";
			}

			$sql = "SELECT gen.*, CONCAT(prosp.aPaterno, ' ', prosp.aMaterno, ' ', prosp.nombre) as nombre_alumno, prosp.idAsistente FROM `alumnos_generaciones` gen 
				JOIN a_prospectos prosp ON prosp.idAsistente = gen.idalumno {$filtroMatch}
				WHERE gen.idgeneracion = :generacion
				ORDER BY nombre_alumno;";
			$statement = $con->prepare($sql);
			$statement->execute($data);
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function consultar_alumnos_generacion($generacion,$groupG,$vista){
			$conexion = new Conexion();
        	$con = $conexion->conectar()['conexion'];

			$whr = '';
			if($vista == 4 && isset($groupG)){
				//echo $groupG;
				$whr = "and gen.grupo = '$groupG'";
			}
			$sql = "SELECT gen.*, CONCAT(prosp.aPaterno, ' ', prosp.aMaterno, ' ', prosp.nombre) as nombre_alumno, prosp.idAsistente FROM `alumnos_generaciones` gen 
				JOIN a_prospectos prosp ON prosp.idAsistente = gen.idalumno
				WHERE gen.idgeneracion = :generacion {$whr}
				ORDER BY nombre_alumno;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':generacion', $generacion);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function actualizarArticuloRelacion($data){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = 'UPDATE articulo_alumnos_servicio 
				SET idarticulo = :id_art 
				WHERE idalumno = :idAlum;';
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql,'data'=>0];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function validarArticuloRelacion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = 'SELECT * 
				FROM articulo_alumnos_servicio
				WHERE idalumno = :idAlum';
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql,'data'=>0];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function insertarArticuloAlumno($data){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = 'INSERT INTO articulo_alumnos_servicio 
				(idalumno, idarticulo, comentario,institucion) 
				VALUES (:idAlum, :id_art, null,:institucion);';
			}

			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		

		function insertaVistaAlumno($data){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO vistas_afiliados
				(idAfiliado, vista, estatus)
				values(:idAlum,(SELECT v.idVista FROM vistas as v WHERE directorio LIKE 'servicioSocial'), '1');";
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function ValidarVistaAlumno($data){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT * 
				FROM vistas_afiliados 
				WHERE idAfiliado = :idAlum AND vista = (SELECT v.idVista FROM vistas as v WHERE directorio LIKE 'servicioSocial');";
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data' => 0];
			}
			$conexion = null;
			$con = null;
			return $response;
		}



		function editarformatoproc($data){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			$SetArchivo = "";
			if($data['archivoformatoEditar']!=""){
				$SetArchivo = ", archivo = :archivoformatoEditar";
			}else{
				unset($data['archivoformatoEditar']);
			}

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE doc_procesos_servicio 
					SET nombre = :nombreformatoEditar $SetArchivo, vecesenvio = :vecesenvioEditar
					WHERE idarchivo = :idFormatoEditar;";
			}


			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function agregarformatoproc($data){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = 'INSERT INTO doc_procesos_servicio 
				(idproceso, nombre, archivo,vecesenvio) 
				VALUES (:formatosexistentes, :nombreformato,:archivoformato, :vecesenvio);';
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		

		function EliminarFormatoRec($data){
			$filtro = 'idarchivo = :idarch';
			if(isset($data['idproc'])){
				$filtro = ' idproceso = :idproc';
			}
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "DELETE FROM doc_procesos_servicio WHERE {$filtro};";
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function eliminar_proceso($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "DELETE FROM procesos_servicio WHERE idproceso = :idproc;";
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function editar_proceso($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE procesos_servicio 
				SET nombre = :EditarNombreProceso, orden = :EditarOrdenProceso
				WHERE idproceso = :idProcesoEditar;";
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function insertar_proceso($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO procesos_servicio 
				(nombre, orden, creador) 
				VALUES (:NombreNuevoProceso, :OrdenNuevoProceso, NULL);";
			}

			$statement = $con->prepare($sql);;
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function consultar_procesos($band){
			$conexion = new Conexion();
        	$con = $conexion->conectar()['conexion'];
			$sql = "SELECT * FROM procesos_servicio";
			$statement = $con->prepare($sql);
			$statement->execute();
			if($band){
				return $statement;
			}else{
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}	
		}

		function consultar_documentos_proceso($data){
			$band = $data['band'];
			unset($data['band']);
			$conexion = new Conexion();
        	$con = $conexion->conectar()['conexion'];
			$sql = "SELECT * 
			FROM `doc_procesos_servicio`
			WHERE idproceso = :idproc";
			$statement = $con->prepare($sql);
			$statement->execute($data);
			if($band){
				return $statement;
			}else{
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}	
		}
		function consultar_alumnos_generacion_listas($generacion,$acceso){

			$complete = "";
			$sl = '';
			if($acceso != 4){
				$complete = "AND al_g.estatus = 4";
			}else{
				$sl = ",al_g.grupo";
			}
			$conexion = new Conexion();
        	$con = $conexion->conectar()['conexion'];
			$sql = "SELECT af_c.id_prospecto,af_c.nombre,af_c.apaterno, af_c.amaterno, af_c.ciudad,af_c.email, af_c.facebook,
			af_c.celular, ag.nombre as nombrecarrera{$sl}
			FROM `afiliados_conacon` af_c 
			JOIN `alumnos_generaciones` al_g 
			ON af_c.id_prospecto=al_g.idalumno
			JOIN `a_generaciones` ag
			ON al_g.idgeneracion= ag.idGeneracion
			WHERE al_g.idgeneracion = $generacion AND al_g.estatus NOT IN (2,4) {$complete}";
			$statement = $con->prepare($sql);
			
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function consultar_asistencia_alumno_clase($alumno, $clase){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];
			$sql = "SELECT * FROM `asistentes_eventos` WHERE id_asistente = :alumno AND NumeroClase = :clase;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':alumno', $alumno);
			$statement->bindParam(':clase', $clase);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC);
		}

		function VerComentariosDirectorio($data){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];
			$sql = "SELECT * FROM `afiliados_conacon` WHERE id_afiliado = :idAf AND notas IS NOT NULL;";
			$statement = $con->prepare($sql);
			$statement->execute($data);
			return $statement;
		}

		function ObtenerControlEscolar(){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];
			$sql = "SELECT Cont.*,access.idAcceso
				FROM controlescolar as Cont
				JOIN a_accesos AS access ON access.idTipo_Persona = 31 AND access.idPersona = Cont.id;";
			$statement = $con->prepare($sql);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		function volcar_alumnos($data){
			$Filtro = "";
			$sl = "";
			$join = "";
			if($data['vista'] == 2){
				$Filtro = "AND (carr.idInstitucion = '13' OR (carr.idInstitucion = '20' AND carr.tipo = '2')) AND carr.estatus = 1";
			}else if($data['vista'] == 4 || $data['ars'] == 36){
				$Filtro = "AND (carr.idCarrera = '14' OR carr.idCarrera = '19')";
			}

			if($data['ars'] == 36){
				$sl = "DISTINCT btp.id_alumno as bitacora,";
				$join = "LEFT JOIN pm_bitacora_tutor_alumno as btp on btp.id_alumno = age.idalumno";
			}
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];
			$sql = "SELECT {$sl} age.*, UPPER(pr.nombre) AS nombre, UPPER(pr.aPaterno) AS aPaterno, UPPER(pr.aMaterno) AS aMaterno, pr.telefono, pr.telefono_casa, pr.telefono_recados, af.celular, af.email, af.pais, af.pais_nacimiento, af.pais_estudio, age.idRelacion,af.id_afiliado, AES_DECRYPT(af.contrasenia, 'SistemasPUE21') as contrasenia, af.ciudad, af.colonia, af.calle, af.cp, af.matricula, 
					(SELECT Pais FROM paises WHERE IDPais = af.pais) as pais_nombre,
					(SELECT Estado FROM estados WHERE IDEstado = af.estado) as estado_nombre,
					carr.nombre as nombre_carrera, gen.nombre as nombre_generacion, carr.idCarrera, age.estatus as estatusGen, af.notas
					FROM `alumnos_generaciones` age 
					JOIN a_prospectos pr ON pr.idAsistente = age.idalumno 
					JOIN afiliados_conacon af ON af.id_prospecto = age.idalumno
					JOIN a_generaciones gen ON gen.idGeneracion = age.idgeneracion
					JOIN a_carreras carr ON carr.idCarrera = gen.idCarrera {$Filtro}
					{$join}
					ORDER BY pr.aPaterno ASC;";
			$statement = $con->prepare($sql);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function TipoDeCiclo($id){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];
			$sql = "SELECT pmat.*, pest.tipo_ciclo FROM `planes_materias` pmat
			JOIN planes_estudios pest On pest.id_plan_estudio = pmat.id_plan
			WHERE id_asignacion = $id";
			$statement = $con->prepare($sql);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		public function obtener_nombre_carrera($idCarrera){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT nombre 
						FROM a_carreras 
						WHERE idCarrera = :idCarrera";

				$statement = $con->prepare($sql); 
				$statement->bindParam(':idCarrera', $idCarrera);
		  
				$statement->execute();
				
				$response = $statement->fetch(PDO::FETCH_ASSOC);			  
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}//buscarCarreras
		public function obtener_maestro($idMaestro){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT concat(nombres,' ',aPaterno) as nombre_completo, email
					FROM maestros
					WHERE id= :idMaestro";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':idMaestro', $idMaestro);

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
		
		function ObtenerInfoAlumno($id){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];
			$sql = "SELECT * FROM `afiliados_conacon` WHERE id_prospecto = $id;";
			$statement = $con->prepare($sql);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		public function buscarCarrerasPorId($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT nombre, idCarrera FROM a_carreras WHERE idCarrera = $id ORDER BY nombre";

				$statement = $con->prepare($sql); 		  
				$statement->execute();
				
				$response = $statement->fetchAll(PDO::FETCH_ASSOC);			  
					
				$conexion = null;
				$con = null;
				return $response;
			}
		}//buscarCarreras

		public function obtener_numero_de_ciclos($idGeneracion,$idProspecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT calif.numero_ciclo, agen.tipoCiclo, COUNT(calif.numero_ciclo) as numero_materias 
						FROM calificaciones as calif 
						JOIN a_generaciones as agen on agen.idGeneracion=calif.idGeneracion 
						JOIN materias as mat on mat.id_materia=calif.id_materia 
						WHERE calif.idGeneracion=:idGeneracion AND calif.idProspecto=:idProspecto 
						GROUP by calif.numero_ciclo;";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':idGeneracion', $idGeneracion);
				$statement->bindParam(':idProspecto', $idProspecto);
	
				$statement->execute();
	
				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function obtener_calificaciones_periodo($idGeneracion,$idProspecto,$numero_ciclo){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
	
				$sql = "SELECT calif.*, agen.tipoCiclo, mat.nombre
				FROM calificaciones as calif
				JOIN a_generaciones as agen on agen.idGeneracion=calif.idGeneracion
				JOIN materias as mat on mat.id_materia=calif.id_materia
				WHERE calif.idGeneracion=:idGeneracion AND calif.idProspecto=:idProspecto AND calif.numero_ciclo=:numero_ciclo";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':idGeneracion', $idGeneracion);
				$statement->bindParam(':idProspecto', $idProspecto);
				$statement->bindParam(':numero_ciclo', $numero_ciclo);
	
				$statement->execute();
	
				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function buscarGeneracion($bus){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				
				$sql = "SELECT gen.*
				FROM a_generaciones gen
				WHERE gen.idGeneracion = :idEditar";
	
	
				$statement = $con->prepare($sql);
				$statement->bindParam(':idEditar',$bus);
				$statement->execute();
	
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$bus];
				}
					
			}
			$conexion = null;
			$con = null;
	
			return $response;
		}

		public function ActualizarGrupo($data){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];
	
			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				unset($data['idAlums']);
				
				$sql = "UPDATE alumnos_generaciones SET grupo = :NomGroup WHERE idalumno = :idAlum AND idgeneracion = :idGen";
	
				$statement = $con->prepare($sql);
				$statement->execute($data);
	
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount(),'group'=>$_POST['NomGroup'],'idG'=>$_POST['idGen']];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
					
			}
			$conexion = null;
			$con = null;
	
			return $response;
		}

		function consultar_generaciones_alumno_carreras($alumno, $carreras){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$con = $con['conexion'];
			$sql = "SELECT ag.*, ag.estatus as estatus_alumno_carrera FROM alumnos_generaciones ag 
			JOIN a_generaciones gen ON gen.idGeneracion = ag.idgeneracion
			WHERE ag.idalumno = :alumno AND gen.idCarrera IN (:string_carr)";
			$statement = $con->prepare($sql);
			$statement->bindParam(':alumno', $alumno);
			$carreras = implode(', ', $carreras);
			$statement->bindParam(':string_carr', $carreras);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

	}
?>
