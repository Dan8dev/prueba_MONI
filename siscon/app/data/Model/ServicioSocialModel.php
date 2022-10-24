<?php 
	require_once 'conexion.php';

	class Servicio {
		public function ConsultaFormatos($band){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT dps.*,ps.nombre AS procNombre FROM doc_procesos_servicio dps
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

		public function VerificarEstatusServicio($user){
			//var_dump($user);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT apos.* 
					FROM articulo_alumnos_servicio as apos 
					WHERE apos.idalumno  = $user;";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 0000){
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


		public function ConsultaFormatoCorreccion($band){
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

		public function ActualizarFormatoRevision($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE doc_alumnos_servicio SET 
				nombre = :nombreArchivo, estatus = '1', intento = intento+1
				WHERE iddocumento = :idDoc AND  numenvio = :numEn";
				
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


		public function InsertarComentarioServicio($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO com_documentos_servicio (iddocumento, comentario, autor, fecha) 
				VALUES (:idArchivo, :ComentarioArchivo, 2, NOW());";

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

		public function InsertarFormatoRevision($data){
			//var_dump($data);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "INSERT INTO doc_alumnos_servicio
				(idproceso, idformato, numenvio, idalumno, nombre, estatus, intento)
				VALUES(:idPorc,:idArch,:numEn,:idAlum,:nombreArchivo,'1','1')";
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == 0000){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
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

		public function consultarTodoAlumnos(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT * FROM alumnos;";
				$statement = $con->prepare($sql);
				$statement->execute();


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

		public function validarLogin($datos){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				include "keys.php";

				$sql = "SELECT 
							ac.`id_afiliado`, ac.`id_prospecto`, ac.`nombre`, ac.`apaterno`, ac.`amaterno`, ac.`fnacimiento`, ac.`curp`, ac.`pais`, ac.`estado`, ac.`ciudad`, ac.`colonia`, ac.`calle`, ac.`email`, ac.`foto`, ac.`cp`, ac.`celular`, ac.`facebook`, ac.`instagram`, ac.`twitter`, ac.`ugestudios`, ac.`cedulap`,  ac.estatus
							FROM afiliados_conacon ac WHERE email = :usr_name AND contrasenia = AES_ENCRYPT(:usr_pass, '{$DECRYPT_PASS}');";
				$statement = $con->prepare($sql);
				$statement->execute($datos);


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC), "sql"=>$datos];
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
		public function validarsiexiste($email){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				include "keys.php";
				$sql = "SELECT ac.id_afiliado, ac.nombre, ac.apaterno, ac.amaterno,AES_DECRYPT(ac.contrasenia, '{$DECRYPT_PASS}') as contrasena 
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
		public function cambiarpasw($contrasena,$idusuario){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			include "keys.php";
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET contrasenia=AES_ENCRYPT(:contrasenia, '{$DECRYPT_PASS}')
                                WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);
                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":contrasenia", $contrasena, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);
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
		public function cambiarpaswusermoni($contrasena,$idusuario){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			include "keys.php";
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE a_accesos
						SET contrasenia=AES_ENCRYPT(:contrasenia, '{$DECRYPT_PASS}')
                                WHERE idAcceso=:idAcceso;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);
                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":contrasenia", $contrasena, PDO::PARAM_STR);
				$stmt->bindParam(":idAcceso", $idusuario, PDO::PARAM_INT);
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
	}
?>
