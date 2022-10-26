<?php 
class eventos{
		public function buscarPaises(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT IDPais, Pais FROM paises";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function buscarEstados($idPais){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT IDEstado, Estado FROM estados WHERE IDPais = :idPais";
				$statement = $con->prepare($sql);
				$statement->bindParam(':idPais', $idPais, PDO::PARAM_INT);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function buscarInstituciones(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT id_institucion, nombre FROM a_instituciones WHERE estatus = 1 AND fundacion = 0";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function buscarPlantillas(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con['conexion'];

				$sql = "SELECT DISTINCT plantilla_bienvenida FROM ev_evento UNION SELECT DISTINCT plantilla_bienvenida FROM a_carreras";

				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
				}

				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function buscarNombreClave($nom){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM ev_evento WHERE nombreClave = :nombreClave";

				$statement = $con->prepare($sql);
				$statement->bindParam('nombreClave', $nom, PDO::PARAM_STR);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>errorInfo(), "sql"=>$sql, "data"=>$id];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function registrarEvento($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO ev_evento
				(tipo, titulo, nombreClave, fechaE, fechaDisponible, fechaLimite, limiteProspectos, duracion,tipoDuracion, direccion, estado, pais, 
				codigoPromocional, estatus, modalidadEvento, idInstitucion, imagen, imgFondo, descripcion, plantilla_bienvenida, cantidad_asis_min)VALUES(:tipo, :titulo, :nombreClave, 
				:fechaE, :fechaDisponible,:fechaLimite, :limiteProspectos, :duracion, :tipoDuracion, :direccion, :estado, :pais, :codigoPromocional, 1, 
				:modalidadEvento, :idInstitucion, :nName, :nNameF, :descripcion, :plantilla_bienvenida, :asistenciasMin)";

				$statement = $con->prepare($sql);
				$statement->execute($evento);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "data"=>$evento];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function consultarEventos(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT evt.*, pa.ISO3 as pais_nom, est.Estado as estado_nom, (SELECT COUNT(*) 
				 FROM a_prospectos ea 
				 INNER JOIN a_marketing_atencion ma ON ma.prospecto = ea.idAsistente 
				 WHERE ma.tipo_atencion = 'evento' AND ea.idEvento = evt.idEvento AND ma.etapa IN (2, 0, 1)) AS numAsistentes
				 FROM `ev_evento` evt
                 INNER JOIN paises pa ON evt.pais = pa.IDPais
                 LEFT JOIN estados est ON evt.estado = est.IDEstado";

				/*
				$sql = "SELECT evt.*, pa.ISO3 as pais_nom,est.Estado as estado_nom, (SELECT COUNT(*) 
				FROM a_prospectos ea 
				INNER JOIN a_marketing_atencion ma ON ma.prospecto = ea.idAsistente 
				WHERE ma.tipo_atencion = 'evento' AND ea.idEvento = evt.idEvento AND ma.etapa IN (2, 0, 1)) AS numAsistentes
				FROM `ev_evento` evt
				INNER JOIN paises pa ON evt.pais = pa.IDPais
				INNER JOIN estados est ON evt.estado = est.IDEstado";*/


				$statement = $con->prepare($sql);
				$statement->execute();
			
				$conexion = null;
				$con = null;

				return $statement;
			}
		}

		public function buscarEvento($event){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM ev_evento WHERE idEvento = :idEditar";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idEditar',$event);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql, "data"=>$event];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function buscarDevClave($nom, $idM){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM ev_evento WHERE nombreClave = :devClave AND idEvento != :idModify";

				$statement = $con->prepare($sql);
				$statement->bindParam(':devClave', $nom, PDO::PARAM_STR);
				$statement->bindParam(':idModify',$idM, PDO::PARAM_INT);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$nom];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function modificarEvento($event){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];
				$str_enl = '';
				if(isset($modify['enlaces']) && $modify['enlaces'] != ''){
					$str_enl = ", video_url = '".$modify['enlaces']."'";
				}
				unset($modify['enlaces']);
				$sql = "UPDATE ev_evento SET tipo = :devTipo ,titulo = :devTitulo, nombreClave = :devClave, fechaE = :devFE, fechaDisponible = :devFD,
				 	fechaLimite = :devFL, limiteProspectos = :devLimite, duracion = :devDuracion, tipoDuracion = :devTipoD, direccion = :devDireccion, 
					estado = :devEstado, pais = :devPais, codigoPromocional = :devPromocion, estatus = 1, modalidadEvento = :devModalidad, idInstitucion =
					:devIDInst, imagen = :nImagen, imgFondo = :nImagenF, descripcion = :devDescripcion, plantilla_bienvenida = :newPlantilla {$str_enl}, cantidad_asis_min = :devAsistenciasM WHERE idEvento = :idModify";

				$statement = $con->prepare($sql);
				$statement->execute($event);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$event];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function modificarClaveImg($modify){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];
				$str_enl = '';
				if(isset($modify['enlaces']) && $modify['enlaces'] != ''){
					$str_enl = ", video_url = '".$modify['enlaces']."'";
				}
				unset($modify['enlaces']);
				$sql = "UPDATE ev_evento SET tipo = :devTipo ,titulo = :devTitulo, nombreClave = :devClave, fechaE = :devFE, fechaDisponible = 
					:devFD,fechaLimite = :devFL, limiteProspectos = :devLimite, duracion = :devDuracion, tipoDuracion = :devTipoD, direccion = 
					:devDireccion, estado = :devEstado, pais = :devPais, codigoPromocional = :devPromocion, estatus = 1, modalidadEvento = :devModalidad, 
					idInstitucion = :devIDInst, imagen = :nImagen, descripcion = :devDescripcion, plantilla_bienvenida = :newPlantilla {$str_enl}, cantidad_asis_min = :devAsistenciasM WHERE idEvento = :idModify";
				$statement = $con->prepare($sql);
				$statement->execute($modify);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$modify];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function modificarClaveFondo($modify){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$str_enl = '';
				if(isset($modify['enlaces']) && $modify['enlaces'] != ''){
					$str_enl = ", video_url = '".$modify['enlaces']."'";
				}
				unset($modify['enlaces']);
				$sql = "UPDATE ev_evento SET tipo = :devTipo ,titulo = :devTitulo, nombreClave = :devClave, fechaE = :devFE, fechaDisponible = :devFD, 
				fechaLimite = :devFL, limiteProspectos = :devLimite, duracion = :devDuracion, tipoDuracion = :devTipoD, direccion = :devDireccion, 
				estado = :devEstado, pais = :devPais, codigoPromocional = :devPromocion, estatus = 1, modalidadEvento = :devModalidad, idInstitucion = 
				:devIDInst, imgFondo = :nImagenF, descripcion = :devDescripcion, plantilla_bienvenida = :newPlantilla {$str_enl}, cantidad_asis_min = :devAsistenciasM WHERE idEvento = :idModify";

				$statement = $con->prepare($sql);
				$statement->execute($modify);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$modify];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function modificarSinImg($modify){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$str_enl = '';
				if(isset($modify['enlaces']) && $modify['enlaces'] != ''){
					$str_enl = ", video_url = '".$modify['enlaces']."'";
				}
				unset($modify['enlaces']);
				$sql = "UPDATE ev_evento SET tipo = :devTipo, titulo = :devTitulo, nombreClave = :devClave, fechaE = :devFE, fechaDisponible = :devFD, 
					fechaLimite = :devFL, limiteProspectos = :devLimite, duracion = :devDuracion, tipoDuracion = :devTipoD, direccion = :devDireccion, 
					estado = :devEstado, pais = :devPais, codigoPromocional = :devPromocion, estatus = 1, modalidadEvento = :devModalidad, idInstitucion = :devIDInst, 
					descripcion = :devDescripcion, plantilla_bienvenida = :newPlantilla {$str_enl}, cantidad_asis_min = :devAsistenciasM WHERE idEvento = :idModify";

				$statement = $con->prepare($sql);
				//$statement->bindParam(':idModify', $modify, PDO::PARAM_INT);
				$statement->execute($modify);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$modify];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function modificarGeneral(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE ev_evento SET tipo = :devTipo ,titulo = :devTitulo, fechaE = :devFE, fechaDisponible = :devFD, fechaLimite = :devFL, 
					limiteProspectos = :devLimite, duracion = :devDuracion, tipoDuracion = :devTipoD, direccion = :devDireccion, estado = :devEstado, pais = 
					:devPais, codigoPromocional = :devPromocion, estatus = 1, modalidadEvento = :devModalidad, idInstitucion = :devIDInst, descripcion = :devDescripcion,
					plantilla_bienvenida = :newPlantilla, cantidad_asis_min = :devAsistenciasM WHERE idEvento = :idModify";

				$statement = $con->prepare($sql);
				$statement->execute($modify);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$modify];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function eliminarEvento($event){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "DELETE FROM ev_evento WHERE idEvento = :idEliminar";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idEliminar', $event);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$event];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function actulualizar_campo($campo, $valor, $ref){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE ev_evento SET `{$campo}` = '{$valor}' WHERE idEvento = {$ref}";

				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$event];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}
	}
