<?php 
	class Asistentes{

        public function obtenerasistente($idalumno, $idevento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.nombre, ap.aPaterno, ap.aMaterno, ap.correo, ee.titulo as nombreenvento, ee.fechaE as fechaevento, eap.detalle_pago as totalpagado, ac.foto
                        FROM a_prospectos AS ap
						JOIN afiliados_conacon as ac on id_prospecto=ap.idAsistente
                        JOIN ev_asistente_pago as eap on ap.idAsistente= eap.id_asistente
                        JOIN ev_evento as ee on ee.idEvento=eap.id_evento
                        WHERE ap.idAsistente=:idAsistente AND ee.idEvento=:idEvento";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":idAsistente", $idalumno, PDO::PARAM_INT);
				$statement->bindParam(":idEvento", $idevento, PDO::PARAM_INT);

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

        public function obtenertalleresasistente($idalumno, $idevento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT et.nombre
                        FROM ev_talleres AS et
                        JOIN ev_evento AS ee on ee.idEvento=et.id_evento
                        JOIN ev_asistente_talleres as eat on eat.id_taller=et.id_taller
                        JOIN a_prospectos AS ap on ap.idAsistente=eat.id_asistente
                        WHERE ee.idEvento=:idEvento and ap.idAsistente=:idAsistente";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":idAsistente", $idalumno, PDO::PARAM_INT);
				$statement->bindParam(":idEvento", $idevento, PDO::PARAM_INT);

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

		public function nombreasistente($idalumno, $idevento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.nombre, ap.aMaterno, ap.aPaterno, ee.titulo as evento, ap.correo as email
                        FROM a_prospectos as ap
						JOIN ev_asistente_pago as eap on ap.idAsistente=eap.id_asistente
						JOIN ev_evento as ee on ee.idEvento=eap.id_evento
                        WHERE ap.idAsistente=:idAsistente AND ee.idEvento=:idEvento";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":idAsistente", $idalumno, PDO::PARAM_INT);
				$statement->bindParam(":idEvento", $idevento, PDO::PARAM_INT);

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

		public function registrarasistencia($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"]; 
				$sql = "INSERT INTO asistentes_eventos (nombre_reconocimiento, id_asistente, id_evento, id_taller, modalidad, hora, folio) 
						VALUES (:nombre_reconocimiento, :id_asistente,:id_evento,:id_taller, :modalidad, :fecha, :folio)";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.

                $stmt->execute($data);

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

		public function consultar_asistencia_evento($asistente, $evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT * FROM asistentes_eventos WHERE id_asistente = :prospecto AND id_evento = :evento ORDER BY `asistentes_eventos`.`hora` DESC;";
                $stmt = $con->prepare($sql);

                $stmt->bindParam(":prospecto", $asistente);
				$stmt->bindParam(":evento", $evento);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->fetchAll(PDO::FETCH_ASSOC)];
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

		public function consultar_asistencia_taller($asistente, $taller){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT * FROM asistentes_eventos WHERE id_asistente = :prospecto AND id_taller = :taller ORDER BY `asistentes_eventos`.`hora` DESC;";
                $stmt = $con->prepare($sql);

                $stmt->bindParam(":prospecto", $asistente);
				$stmt->bindParam(":taller", $taller);

                $stmt->execute();

				if($stmt->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$stmt->fetchAll(PDO::FETCH_ASSOC)];
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

		public function obtenerconstancias($idalumno, $idevento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.nombre, ap.aMaterno, ap.aPaterno, ee.tipo as tipoevento, ap.correo as email, ee.duracion, COALESCE(edo.Estado, '') as lugarevento, ee.fechaE as fechaevento, ee.titulo as tituloevento, ae.nombre_reconocimiento, ae.id_taller, et.nombre as nombre_taller
				FROM a_prospectos as ap 
				JOIN asistentes_eventos AS ae on ae.id_asistente=ap.idAsistente 
				JOIN ev_evento as ee on ee.idEvento=ae.id_evento 
                LEFT JOIN estados as edo on edo.IDEstado=ee.estado
				LEFT JOIN ev_talleres as et on et.id_taller=ae.id_taller
				WHERE ap.idAsistente=:idAsistente AND ae.nombre_reconocimiento<>''";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":idAsistente", $idalumno, PDO::PARAM_INT);

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

		public function obtenergrados($idafiliado){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT *
						FROM afiliados_grado_estudios
						WHERE idAfiliado=:idAsistente AND estatus =1";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":idAsistente", $idafiliado, PDO::PARAM_INT);

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

		public function emailasistente($email){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.idAsistente, ee.idEvento
						FROM a_prospectos as ap
						JOIN asistentes_eventos AS ae on ae.id_asistente=ap.idAsistente
						JOIN ev_evento as ee on ee.idEvento=ae.id_evento
						WHERE ap.correo=:email";

				$statement = $con->prepare($sql);

				$statement->bindParam(":email", $email);

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

    }
