<?php 
date_default_timezone_set("America/Mexico_City");

class Carreras{

  	public function getInstituciones($id){
		
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$whr = 'WHERE estatus=1 AND fundacion=0 AND id_institucion = 13 OR id_institucion = 19 OR id_institucion = 20';
			if($id == 4){
				$whr = "WHERE estatus=1 AND fundacion = 0 AND id_institucion = 19";
			}

			$sql = "SELECT id_institucion, nombre 
				FROM a_instituciones
				{$whr}";

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

  	
  	public function crearCarrera($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
  
		if($con["info"] == "ok"){
		$con = $con["conexion"];

		$sql = "INSERT INTO a_carreras 
			(idInstitucion, nombre, fechaE, tipo, area, estatus, creador_por, fecha_creado, nombre_clave)
			VALUES(:selectinstitucion, :crearnombrecarrera, :fActual, :selecttipo, :areaCarrera, :estatus, :creador_por, :fActual, :clave);";
		
		$clave = $data['crearnombrecarrera'];
		$parts = explode(' ', $clave);
		$clave_fin = [];
		foreach($parts as $part){
			if(strlen($part) > 3){
				$aux_part = $part;
				$aux_part = str_replace(['á','Á'], 'a', $aux_part);
				$aux_part = str_replace(['é','É'], 'e', $aux_part);
				$aux_part = str_replace(['í','Í'], 'i', $aux_part);
				$aux_part = str_replace(['ó','Ó'], 'o', $aux_part);
				$aux_part = str_replace(['ú','Ú'], 'u', $aux_part);
				$aux_part = str_replace(['ñ','Ñ'], 'n', $aux_part);
				array_push($clave_fin, strtolower($aux_part));
			}
		}
		$str_clave = implode('_',$clave_fin);
		$statement = $con->prepare($sql);
		$data['clave'] = $str_clave;
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

	public function obtenerCarreras($id){
		$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$complete = "WHERE carrer.estatus = 1 AND idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11";
				if($id == 4){
					$complete = "WHERE (carrer.idCarrera = 14 or carrer.idCarrera = 19)";
				}

				$sql = "SELECT carrer.idCarrera, carrer.nombre, carrer.tipo, carrer.area, carrer.modalidadCarrera, carrer.duracionTotal, carrer.tipoCiclo, carrer.fecha_inicio, carrer.fecha_fin, carrer.fecha_creado, inst.nombre as nombreInst
				 FROM `a_carreras` carrer
				 INNER JOIN a_instituciones inst ON carrer.idInstitucion = inst.id_institucion
				 {$complete}";

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

			$sql = "SELECT carrer.idCarrera, carrer.idInstitucion, carrer.nombre, carrer.tipo, carrer.area, gen.secuencia_generacion, gen.nombre as nombreG, gen.modalidadCarrera, gen.tipoCiclo, gen.fecha_inicio
				FROM a_carreras carrer
				INNER JOIN a_generaciones gen ON gen.idCarrera = carrer.idCarrera
				WHERE carrer.idCarrera = :idEditar AND gen.secuencia_generacion = 1";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idEditar',$carrer);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$carrer];
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

			$sql = "UPDATE a_carreras SET idInstitucion = :devinstitucion, nombre = :devnombrecarrera, tipo = :devtipo,
				area = :devAreaCarrera, fecha_actualizacion = :fActualizacion WHERE idCarrera = :id_carrera";

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

	public function obtenerAlumnosCarrera($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT alumGen.idRelacion, alumGen.idalumno, aPros.nombre, aPros.aPaterno, aPros.aMaterno, aPros.Genero, afiCon.estatus, gen.secuencia_generacion, gen.idCarrera, gen.idGeneracion, aPros.referencia, afiCon.curp, afiCon.email, afiCon.celular, afiCon.pais, afiCon.estado, pa.Pais as pais_nom, est.Estado as estado_nom, afiCon.pais_nacimiento, afiCon.pais_estudio, paNac.Pais as pais_nom_nac, estNac.Estado as estado_nom_nac, paRad.Pais as pais_nom_est, estRad.Estado as estado_nom_est, afiCon.grado_academico, afiCon.notas, afiCon.edad, afiCon.matricula, afiCon.direccion
				FROM a_generaciones gen
				INNER JOIN alumnos_generaciones alumGen ON alumGen.idgeneracion = gen.idGeneracion
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
				INNER JOIN afiliados_conacon afiCon ON afiCon.id_prospecto = aPros.idAsistente
				LEFT JOIN paises pa ON pa.IDPais = afiCon.pais
				LEFT JOIN paises paNac On paNac.IDPais = afiCon.pais_nacimiento
				LEFT JOIN paises paRad On paRad.IDPais = afiCon.pais_estudio
                LEFT JOIN estados est ON est.IDEstado = afiCon.estado
				LEFT JOIN estados estNac ON estNac.IDEstado = afiCon.estado_nacimiento
				LEFT JOIN estados estRad ON estRad.IDEstado = afiCon.estado_estudio
				WHERE gen.idCarrera = :idCarrera
				ORDER BY aPros.aPaterno ASC;";

			$statement = $con->prepare($sql);
			$statement->execute($data);
			/*$sql = "SELECT gen.idGeneracion, alumGen.idalumno, aPros.nombre, aPros.aPaterno, aPros.aMaterno
					FROM a_generaciones gen
					INNER JOIN alumnos_generaciones alumGen ON alumGen.idgeneracion = gen.idGeneracion
					INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno
					WHERE gen.idCarrera = :idCarrera";*/
		}
	$conexion = null;
	$con = null;
	return $statement;
	}

	public function obtenerDatosAlumnoDirectorio($id){
		$conexion = new Conexion();
		$con = $conexion->Conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$cmp = '';
			$join = '';
			$idUS = isset($id['idUs']) ? $id['idUs'] : 0;
			unset($id['idUs']);
			if($idUS  == 4){
				$join = 'LEFT JOIN a_generaciones as ag on ag.idGeneracion = alumGen.idgeneracion';
				$cmp = 'and (ag.idCarrera = 14 or ag.idCarrera = 19)';
			}
			$sql = "SELECT 	aPros.telefono_casa,aPros.telefono_recados,aPros.cedula, aPros.escuela_procedencia, aPros.fecha_egreso, aPros.cedula, aPros.nombre, aPros.aPaterno, aPros.aMaterno, aPros.Genero, alumGen.estatus, aPros.referencia, afiCon.curp, afiCon.edad, afiCon.email, afiCon.celular, afiCon.grado_academico, afiCon.pais, afiCon.estado, afiCon.pais_nacimiento, afiCon.estado_nacimiento, afiCon.pais_estudio, afiCon.estado_estudio, REPLACE(afiCon.notas, '<br>', '\n') as notas, alumGen.idalumno, alumGen.idgeneracion, afiCon.ciudad, afiCon.colonia, afiCon.calle, afiCon.cp, afiCon.matricula, alumGen.estatus as alumgenEstatus, alumGen.grupo
					FROM a_prospectos aPros
					INNER JOIN afiliados_conacon afiCon ON afiCon.id_prospecto = aPros.idAsistente
					INNER JOIN alumnos_generaciones alumGen ON alumGen.idalumno = aPros.idAsistente
					{$join}
					WHERE aPros.idAsistente = :idAlum AND alumGen.idgeneracion = :idGen {$cmp};";



			$statement = $con->prepare($sql);
			$statement->execute($id);

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
				estatus = :estatus,grupo = :grupo
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

	public function cargarGeneracionesDirectorio($id){
		$conexion = new Conexion();
		$con = $conexion->Conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT idGeneracion, nombre,grupos
				FROM a_generaciones
				WHERE idCarrera = :idCarrera";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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

	public function cargarEstadosDirectorio($id){
		$conexion = new Conexion();
		$con = $conexion->Conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT IDEstado, Estado
				FROM estados
				WHERE IDPais = :idPais
				ORDER BY Estado ASC";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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

	public function cargarPaisesDirectorio(){
		$conexion = new Conexion();
		$con = $conexion->Conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT IDPais, Pais
				FROM paises
				ORDER BY Pais ASC";

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

	public function actualizarDirectorioBasicoAlumno($nombre, $aPaterno, $aMaterno, $genero, $idAlumno){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if ($con['info'] == 'ok') {
			$con = $con['conexion'];

			$sql = "UPDATE a_prospectos SET nombre = :nom, aPaterno = :aPat, aMaterno = :aMat, Genero = :gen
				WHERE idAsistente = :idAlum;
				
				UPDATE afiliados_conacon SET nombre = :nom, apaterno = :aPat, amaterno = :aMat
				WHERE id_prospecto = :idAlum;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':nom',$nombre);
			$statement->bindParam(':aPat',$aPaterno);
			$statement->bindParam(':aMat',$aMaterno);
			$statement->bindParam(':gen', $genero);
			$statement->bindParam(':idAlum',$idAlumno);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}


	//Esto debe probasrse en sandbox
	public function actualizarDirectorioGeneralAlumno($NumdeCasa, $NumRecados ,$cedula, $fecha, $escuela, $estatus, $curp, $email, $celular, $pais, $estado, $idPaisNacimiento, $idEntidadNacimiento, $idPaisEstudio, $idEntidadEstudio, $gradoUltimoAlumnoDirectorio, $notas, $edad, $idAlumno, $ciudad, $colonia, $calle, $cp, $matricula, $GeneracionAntigua){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		$count = 0;
		$notas =  str_replace("\n", "<br>", "$notas", $count);
		
		if ($con['info'] == 'ok') {
			$con = $con['conexion'];

			$sql = "UPDATE afiliados_conacon SET curp = :curp, email = :email, celular = :cel, pais = :pais,
				estado = :estado, edad = :edad, pais_nacimiento = :idPaisNac, estado_nacimiento = :idEntNac,
				pais_estudio = :idPaisEst, estado_estudio = :idEntEst, grado_academico = :gradoEst, notas = :notes, ciudad = :ciudad, matricula = :mat, colonia = :col, calle = :calle, cp = :cp
				WHERE id_prospecto = :idAlum;
				UPDATE a_prospectos SET telefono_casa = :NumeroCasa, telefono_recados = :NumeroRecados, correo = :email, telefono = :cel, escuela_procedencia = :escuela, fecha_egreso = :fecha, cedula = :cedula, nacionalidad = :idPaisNac, grado_academico = :gradoEst WHERE idAsistente = :idAlum;
				UPDATE alumnos_generaciones SET estatus = :estatus WHERE idalumno = :idAlum AND idgeneracion = :GenAnt;
				";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':cedula',$cedula);
			$statement->bindParam(':fecha',$fecha);
			$statement->bindParam(':escuela',$escuela);
			$statement->bindParam(':estatus',$estatus);
			$statement->bindParam(':curp',$curp);
			$statement->bindParam(':email',$email);
			$statement->bindParam(':cel',$celular);
			$statement->bindParam(':pais',$pais);
			$statement->bindParam(':estado',$estado);
			$statement->bindParam(':idPaisNac',$idPaisNacimiento);
			$statement->bindParam(':idEntNac',$idEntidadNacimiento);
			$statement->bindParam(':idPaisEst', $idPaisEstudio);
			$statement->bindParam(':idEntEst', $idEntidadEstudio);
			$statement->bindParam(':gradoEst', $gradoUltimoAlumnoDirectorio);
			$statement->bindParam(':notes',$notas);
			$statement->bindParam(':edad',$edad);
			$statement->bindParam(':idAlum',$idAlumno);
			$statement->bindParam(':mat',$matricula);
			$statement->bindParam(':ciudad',$ciudad);
			$statement->bindParam(':col',$colonia);
			$statement->bindParam(':calle',$calle);
			$statement->bindParam(':cp',$cp);
			$statement->bindParam(':GenAnt',$GeneracionAntigua);
			$statement->bindParam(':NumeroCasa',$NumdeCasa);
			$statement->bindParam(':NumeroRecados',$NumRecados);

			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function actualizarDirectorioGeneracionAlumno($idGeneracion, $idAntiguaGeneracion, $idAlumno){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if ($con['info'] == 'ok') {
			$con = $con['conexion'];

			$sql = "UPDATE alumnos_generaciones SET idgeneracion = :idGen
				WHERE idalumno = :idAlum AND idgeneracion = :idGenAnt";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idGen',$idGeneracion);
			$statement->bindParam(':idGenAnt',$idAntiguaGeneracion);
			$statement->bindParam(':idAlum',$idAlumno);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	function validarCambioGeneracion($idRelacion, $idAlumno, $idGeneracion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT *
				FROM alumnos_generaciones
				WHERE idalumno = :idAlum AND idGeneracion = :idGen AND idRelacion != :idRel";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idRel',$idRelacion);
			$statement->bindParam(':idAlum',$idAlumno);
			$statement->bindParam(':idGen',$idGeneracion);
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


}
