<?php 
date_default_timezone_set("America/Mexico_City");

class Carreras{

  public function getPaises(){
	  $conexion = new Conexion();
	  $con = $conexion->conectar();
	  $response = [];

	  if($con['info'] == 'ok'){
	  $con = $con['conexion'];
	  $sql = "SELECT IDPais, Pais 
		  FROM paises";

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

  public function getInstituciones(){
	$conexion = new Conexion();
	$con = $conexion->conectar();
	$response = [];

	if($con['info'] == 'ok'){
	$con = $con['conexion'];
	$sql = "SELECT id_institucion, nombre 
		FROM a_instituciones
		WHERE estatus=1 AND fundacion=0";

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

  public function getEstados($idpais){
	$conexion = new Conexion();
	$con = $conexion->conectar();
	$response = [];
  
	if($con['info'] == 'ok'){
	$con = $con['conexion'];
	$sql = "SELECT IDEstado, Estado 
			FROM estados
			WHERE IDPais=:idpais";
  
	$statement = $con->prepare($sql);

	$statement->execute($idpais);
  
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

  	public function buscarNombreClave($nom){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM a_carreras WHERE nombre_clave = :crearclavecarrera";

				$statement = $con->prepare($sql);
				$statement->bindParam('crearclavecarrera', $nom, PDO::PARAM_STR);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>errorInfo(), "sql"=>$sql, "data"=>$nom];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
	}
	
  	public function crearCarrera($data){
	$conexion = new Conexion();
	$con = $conexion->conectar();
	$response = [];
  
		if($con["info"] == "ok"){
		$con = $con["conexion"];

		$sql = "INSERT INTO a_carreras 
		(idInstitucion, nombre, nombre_clave, fechaE, tipo, modalidadCarrera, duracionTotal, tipoCiclo, codigoPromocional, direccion, estado, 
		pais, plantilla_bienvenida, imagen, imgFondo, estatus, fecha_inicio, fecha_fin, creador_por, fecha_creado)VALUES(:selectinstitucion, :crearnombrecarrera, :crearclavecarrera, 
		:crearfechainicio, :selecttipo, :selectmodalidad, :selectduracionmeses, :selecttipociclo, :crearcodigopromocional, :creardireccion, :selectestado, :selectpais, 
		:selectplantilla, :imagen, :imgFondo, :estatus, :crearfechainicio, :crearfechafin, :creador_por, :fActual);";
					
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
		}else{
			$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
		}

		$conexion = null;
		$con = null;

		return $response;
		}
	}

	public function obtenerCarreas(){
		$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT carrer.*, pa.ISO3 as pais_nom,est.Estado as estado_nom, inst.nombre as nombreInst
				 FROM `a_carreras` carrer
                 INNER JOIN paises pa ON carrer.pais = pa.IDPais
                 LEFT JOIN estados est ON carrer.estado = est.IDEstado
				 INNER JOIN a_instituciones inst ON carrer.idInstitucion = inst.id_institucion
				 WHERE carrer.estatus = 1";

				$statement = $con->prepare($sql);
				$statement->execute();
			
				$conexion = null;
				$con = null;

				return $statement;
			}
	}

	public function buscarCarrera($carrer){
		$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM a_carreras WHERE idCarrera = :idEditar";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idEditar',$carrer);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql, "data"=>$carrer];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
	}

	public function buscarClaveDev($clave, $id){
		$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM a_carreras WHERE nombre_clave = :devclavecarrera AND idCarrera != :id_carrera";

				$statement = $con->prepare($sql);
				$statement->bindParam(':devclavecarrera', $clave, PDO::PARAM_STR);
				$statement->bindParam(':id_carrera',$id, PDO::PARAM_INT);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$clave];
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

			$sql = "UPDATE a_carreras SET idInstitucion = :devinstitucion, nombre = :devnombrecarrera, nombre_clave = :devclavecarrera, fechaE = :devcrearfechainicio, tipo = :devtipo, 
				modalidadCarrera = :devmodalidad, duracionTotal = :devduracionmeses, tipoCiclo = :devtipociclo, codigoPromocional = :devcodigopromocional, direccion = :devdireccioncarrera, 
				estado = :devestado, pais = :devpais, plantilla_bienvenida = :devplantilla, fecha_inicio = :devcrearfechainicio, fecha_fin = :devcrearfechafin, 
				fecha_actualizacion = :fActualizacion WHERE idCarrera = :id_carrera";

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

	public function modificarClaveImg($modify){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if ($con['info'] == 'ok') {
			$con = $con['conexion'];

			$sql = "UPDATE a_carreras SET idInstitucion = :devinstitucion, nombre = :devnombrecarrera, nombre_clave = :devclavecarrera, fechaE = :devcrearfechainicio, tipo = :devtipo, 
				modalidadCarrera = :devmodalidad, duracionTotal = :devduracionmeses, tipoCiclo = :devtipociclo, codigoPromocional = :devcodigopromocional, direccion = :devdireccioncarrera, 
				estado = :devestado, pais = :devpais, plantilla_bienvenida = :devplantilla, imagen = :nImagen, fecha_inicio = :devcrearfechainicio, fecha_fin = :devcrearfechafin, 
				fecha_actualizacion = :fActualizacion WHERE idCarrera = :id_carrera";

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

			$sql = "UPDATE a_carreras SET idInstitucion = :devinstitucion, nombre = :devnombrecarrera, nombre_clave = :devclavecarrera, fechaE = :devcrearfechainicio, tipo = :devtipo, 
			modalidadCarrera = :devmodalidad, duracionTotal = :devduracionmeses, tipoCiclo = :devtipociclo, codigoPromocional = :devcodigopromocional, direccion = :devdireccioncarrera, 
			estado = :devestado, pais = :devpais, plantilla_bienvenida = :devplantilla, imgFondo = :nImagenF, fecha_inicio = :devcrearfechainicio, fecha_fin = :devcrearfechafin, 
			fecha_actualizacion = :fActualizacion WHERE idCarrera = :id_carrera";

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

	public function modificarCarrera($carrer){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if ($con['info'] == 'ok') {
			$con = $con['conexion'];

			$sql = "UPDATE a_carreras SET idInstitucion = :devinstitucion, nombre = :devnombrecarrera, nombre_clave = :devclavecarrera, fechaE = :devcrearfechainicio, tipo = :devtipo, 
			modalidadCarrera = :devmodalidad, duracionTotal = :devduracionmeses, tipoCiclo = :devtipociclo, codigoPromocional = :devcodigopromocional, direccion = :devdireccioncarrera, 
			estado = :devestado, pais = :devpais, plantilla_bienvenida = :devplantilla, imagen = :nImagen, imgFondo = :nImagenF, fecha_inicio = :devcrearfechainicio, fecha_fin = :devcrearfechafin, 
			fecha_actualizacion = :fActualizacion WHERE idCarrera = :id_carrera";

			$statement = $con->prepare($sql);
			$statement->execute($carrer);

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$carrer];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function eliminarCarrera($del){
		$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE a_carreras SET estatus = 2 WHERE idCarrera = :idEliminar";

				$statement = $con->prepare($sql);
				$statement->execute($del);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$del];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
	}

}