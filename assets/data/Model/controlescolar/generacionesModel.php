<?php 
date_default_timezone_set("America/Mexico_City");

class Generaciones{

	public function crearGeneracion($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
  
		if($con["info"] == "ok"){
		$con = $con["conexion"];

		$sql = "INSERT INTO a_generaciones 
			(idCarrera, secuencia_generacion, nombre, fechaE, modalidadCarrera, tipoCiclo, estatus, fecha_inicio, fechafinal, creadoPor, fechaCreado, actualizado_por, fecha_actualizacion)
			VALUES(:selectCarrer, :numG, :nombreG, :fechainicio, :selectmodalidad, :selecttipociclo, 1, :fechainicio, NULL, :creador_por, :fCreado, NULL, NULL)";

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


    public function obtenerListaCarreras($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$complete = "WHERE estatus = 1 AND idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11";
			if($id == 4){
				$complete = "WHERE estatus = 1 and (idCarrera = 14 or idCarrera = 19)";
			}
			$sql = "SELECT idCarrera, nombre 
				FROM a_carreras 
				{$complete}
				ORDER BY nombre ASC";

			$statement = $con->prepare($sql);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
	}

	public function obtenerGeneraciones($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$complete = '';
			if($id == 4){
				$complete = "and (carr.idCarrera = 14 or carr.idCarrera = 19)";
			}

			$sql = "SELECT gen.*, carr.nombre as nomCarrer, plE.nombre as nombrePlan, plE.numero_ciclos
				FROM `a_generaciones` gen
				INNER JOIN a_carreras carr ON carr.idCarrera = gen.idCarrera
				LEFT JOIN planes_estudios plE ON plE.id_plan_estudio = gen.id_plan_estudio
                WHERE gen.estatus = 1 {$complete}";

			$statement = $con->prepare($sql);
			$statement->execute();
		}
	$conexion = null;
	$con = null;
	return $statement;
	}

	public function eliminarGeneracion($del){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "UPDATE a_generaciones SET estatus = 2 WHERE idGeneracion = :idEliminar";

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


	public function modificarGeneracion($modify){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "UPDATE a_generaciones SET secuencia_generacion = :modNumG, idCarrera = :modselectCarrer, nombre = :modNombreG, fechaE = :modfechainicio,
				 modalidadCarrera = :modselectmodalidad, tipoCiclo = :modtipociclo, fecha_inicio = :modfechainicio,
				 actualizado_por = :actualizado_por, fecha_actualizacion = :fActualizacion
				WHERE idGeneracion = :idG";

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


	public function obtenerListaCarrerasMod($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT idCarrera, nombre FROM a_carreras WHERE estatus = 1 AND idCarrera = :idCarr";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idCarr', $data);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
	}

	public function buscarNumeroGeneracion($dato){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT MAX(secuencia_generacion) as secuencia_generacion
				FROM a_generaciones
				WHERE idCarrera = :idCarrer;";

			$statement  = $con->prepare($sql);
			$statement->execute($dato);

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
	}

	public function obtenerNombreCarrera($idCarrera){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			 $sql = "SELECT nombre
			 	FROM a_carreras
				WHERE idCarrera = :idCarrer";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idCarrer', $idCarrera);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function evitarSecuenciaRepetida($numSecu, $idCarera){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con  = $con['conexion'];

			$sql = "SELECT secuencia_generacion
				FROM a_generaciones
				WHERE idCarrera = :idCarrer AND secuencia_generacion = :noSecuencia AND estatus = 1";

			$statement = $con->prepare($sql);
			$statement->bindParam(':noSecuencia', $numSecu);
			$statement->bindParam(':idCarrer', $idCarera);
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

	public function evitarSecuenciaRepetidaMod($noSecuencia, $idCarrera, $idGeneracion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT secuencia_generacion
			FROM a_generaciones
			WHERE idCarrera = :idCarrera AND secuencia_generacion = :noSecuencia AND idGeneracion != :idGen AND estatus = 1";

			$statement = $con->prepare($sql);
			$statement->bindParam(':noSecuencia',$noSecuencia);
			$statement->bindParam(':idCarrera', $idCarrera);
			$statement->bindParam(':idGen', $idGeneracion);
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
	
	public function avance_generaciones($idGen){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql ="INSERT INTO avance_generaciones
                (id_generacion, ciclo_actual)
                VALUES(:id_gen, 1)";

            $statement = $con->prepare($sql);
            $statement->bindParam(':id_gen',$idGen);
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

	public function crearGeneracionUno($idCarrera, $numGeneracion, $nomGeneracion, $modalidadGeneracion, $tipoCicloGeneracion, $fechaIGeneracion, $creadoPor, $fActual){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO a_generaciones 
			(idCarrera, secuencia_generacion, nombre, fechaE, modalidadCarrera, tipoCiclo, estatus, fecha_inicio, creadoPor, fechaCreado, actualizado_por, fecha_actualizacion)
			VALUES(:idCarr, :numG, :nomG, :fechaIG, :modG, :tipoG, 1, :fechaIG, :creadoPor, :fActual, NULL, NULL)";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idCarr', $idCarrera);
			$statement->bindParam(':numG', $numGeneracion);
			$statement->bindParam(':nomG', $nomGeneracion);
			$statement->bindParam(':modG', $modalidadGeneracion);
			$statement->bindParam(':tipoG', $tipoCicloGeneracion);
			$statement->bindParam(':fechaIG', $fechaIGeneracion);
			//$statement->bindParam(':fechaFG', $fechaFGeneracion);
			$statement->bindParam(':creadoPor', $creadoPor);
			$statement->bindParam(':fActual', $fActual);
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

	public function modificarGeneracionUno($modidCarrera, $modnumGeneracion, $modnomGeneracion, $modmodalidadGeneracion, $modtipoCicloGeneracion, $modfechaIGeneracion, $modificadoPor, $fModificado){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE a_generaciones 
				SET secuencia_generacion = :modNumGen, nombre = :modNomGen, fechaE = :modFechaIG, modalidadCarrera = :modModalidad, tipoCiclo = :modTipoC, fecha_inicio = :modFechaIG, actualizado_por = :modPor, fecha_actualizacion = :fModificado
				WHERE idCarrera = :modIdCarr AND secuencia_generacion = 1";

			$statement = $con->prepare($sql);
			$statement->bindParam(':modIdCarr',$modidCarrera);
			$statement->bindParam(':modNumGen',$modnumGeneracion);
			$statement->bindParam(':modNomGen',$modnomGeneracion);
			$statement->bindParam(':modModalidad',$modmodalidadGeneracion);
			$statement->bindParam(':modTipoC',$modtipoCicloGeneracion);
			$statement->bindParam(':modFechaIG',$modfechaIGeneracion);
			//$statement->bindParam(':modFechaFG',$modfechaFGeneracion);
			$statement->bindParam(':modPor', $modificadoPor);
			$statement->bindParam(':fModificado',$fModificado);
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

	public function validarAsignarPlanEstGen($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT plEst.*
					FROM a_generaciones gen
					INNER JOIN planes_estudios plEst ON plEst.id_carrera = gen.idCarrera
					WHERE plEst.activo = 1 AND gen.idGeneracion = :idGen AND plEst.tipo_ciclo = :tipoC";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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

	public function obtenerPlanesEstudio($datos){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT plEst.id_plan_estudio, plEst.nombre, plEst.numero_ciclos
				FROM a_generaciones gen
				INNER JOIN planes_estudios plEst ON plEst.id_carrera = gen.idCarrera
				WHERE gen.idGeneracion = :id AND plEst.tipo_ciclo = :tipoC";

			$statement = $con->prepare($sql);
			$statement->execute($datos);

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


	public function obDatosAsigGeneracionPE($datos){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT idGeneracion, id_plan_estudio, fechafinal
				FROM a_generaciones
				WHERE idGeneracion = :idGen";

			$statement = $con->prepare($sql);
			$statement->execute($datos);

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function obDatosGeneracionPE($datos){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT gen.fecha_inicio, pl.numero_ciclos, pl.tipo_ciclo
				FROM a_generaciones gen
				INNER JOIN planes_estudios pl ON pl.id_carrera = gen.idCarrera
				WHERE gen.idGeneracion = :idG AND pl.id_plan_estudio = :idPlanE";

			$statement = $con->prepare($sql);
			$statement->execute($datos);

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function asignarPlanEstudioGen($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE a_generaciones SET id_plan_estudio = :asigPlanEst, fechafinal = :fechafinAsigPE
				WHERE idGeneracion = :idGenPlanE;";

			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$data];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function validarMateriasAsignadasPlanE($idPlanE){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT DISTINCT ciclo_asignado
					FROM planes_materias
					WHERE id_plan = :idPlan";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idPlan', $idPlanE);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$idPlanE];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function buscarTipoCarrera($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT tipo, idInstitucion 
				FROM a_carreras
				WHERE idCarrera = :idCarr";

			$statement = $con->prepare($sql);
			$statement->execute($id);

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function obtenerCiclo($idPlanestudios){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT * 
				FROM planes_estudios
				WHERE id_plan_estudio = :id_plan_estudio";

			$statement = $con->prepare($sql);
			$statement->bindParam(':id_plan_estudio', $idPlanestudios);

			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function datosPlanEstudioGeneración($id){
		$conexion = new Conexion;
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT plE.numero_ciclos, plE.tipo_ciclo, gen.nombre, gen.fecha_inicio, gen.fechafinal
				FROM a_generaciones gen
				INNER JOIN planes_estudios plE ON plE.id_plan_estudio = gen.id_plan_estudio
				WHERE gen.idGeneracion = :idGeneracion";

			$statement = $con->prepare($sql);
			$statement->execute($id);

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function obtenerFechasPorCiclo($data){
		$conexion = new Conexion;
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT fecha_inicio, fecha_fin
				FROM fechas_ciclos
				WHERE id_generacion = :idGeneracion AND ciclo = :numeroCiclo";

			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function guardarFechaGeneracion($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO fechas_ciclos
				(id_generacion, ciclo, fecha_inicio, fecha_fin)
				VALUES(:idGeneracion, :numeroDeCiclo, :fechaInicio, :fechaFin)";

			$statement = $con->prepare($sql);
			$statement->execute($data);
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

	public function modificarFechasGeneracion($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE fechas_ciclos SET fecha_inicio = :fechaInicio, fecha_fin = :fechaFin
					WHERE id_generacion = :idGeneracion AND ciclo = :numeroDeCiclo;";

			$statement = $con->prepare($sql);
			$statement->execute($data);
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

	public function obtenerCicloTotalGeneracion($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT plEst.numero_ciclos
				FROM a_generaciones gen
				INNER JOIN planes_estudios plEst ON plEst.id_plan_estudio = gen.id_plan_estudio
				WHERE gen.idGeneracion = :id;";

			$statement = $con->prepare($sql);
			$statement->bindParam('id',$id);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function AnteriorFecha($idGeneracion, $numeroCiclo){
		$conexion = new Conexion();
		$con = 	$conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT fecha_fin
				FROM fechas_ciclos
				WHERE id_generacion = :idGen AND ciclo = :numCiclo";

			$statement = $con->prepare($sql);
			$statement->bindParam('idGen',$idGeneracion);
			$numCiclo = $numeroCiclo-1;
			$statement->bindParam('numCiclo',$numCiclo);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function siguienteFecha($idGeneracion, $numeroCiclo){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT fecha_inicio
			FROM fechas_ciclos
			WHERE id_generacion = :idGen AND ciclo = :numCiclo";

			$statement = $con->prepare($sql);
			$statement->bindParam('idGen',$idGeneracion);
			$numCiclo = $numeroCiclo+1;
			$statement->bindParam('numCiclo',$numCiclo);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$conexion = null;
	$con = null;
	return $response;
	}

	public function obtenerDocumentosGeneracion($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT listDocGen.id_bloqueo, listDoc.nombre_documento, listDocGen.bloqueo_digital, listDocGen.fecha_digital, listDocGen.bloqueo_fisico, listDocGen.fecha_fisico, listDocGen.id_generacion
				FROM listado_documentos_generacion listDocGen
				INNER JOIN listado_documentos listDoc ON listDoc.id_documento = listDocGen.id_documento
				WHERE listDoc.estatus = 1 AND listDocGen.estatus = 1 AND listDocGen.id_generacion = :idGen";

			$statement = $con->prepare($sql);
			$statement->execute($id);
		}
	$con = null;
	$conexion = null;
	return $statement;
	}

	public function obtenerListaDocumentosGeneracion($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT id_documento, nombre_documento 
			FROM listado_documentos
			WHERE estatus = 1";

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

	public function asignarBloqueoDocumento($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE listado_documentos_generacion 
				SET bloqueo_fisico = :selectBloqueoFisico, bloqueo_digital = :selectBloqueoDigital, fecha_fisico = :fecha_fisico, fecha_digital = :fecha_digital
				WHERE id_bloqueo = :idBloqueo";

			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$con = null;
	$conexion = null;
	return $response;
	}

	public function insertarfechalimitedepagoreins($idGeneracion,$fechalimitedepago){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE pagos_conceptos 
				SET fechalimitepago = :fechalimitedepago
				WHERE id_generacion = :idGeneracion and categoria = 'Reinscripción'";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idGeneracion', $idGeneracion);
			$statement->bindParam(':fechalimitedepago', $fechalimitedepago);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$con = null;
	$conexion = null;
	return $response;
	}

	public function obtenerconceptos($idGeneracion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
	
		if($con['info'] == 'ok'){
		$con = $con['conexion'];
		$sql = "SELECT * 
			FROM pagos_conceptos
			WHERE id_generacion = :idGeneracion and categoria = 'Reinscripción'";
	
		$statement = $con->prepare($sql);
		$statement->bindParam(':idGeneracion', $idGeneracion);

		$statement->execute();
	
		if($statement->errorInfo()[0] == 00000){
		  $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
		}else{
			$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
		  }
			$conexion = null;
		$con = null;
	
		return $response;
		  }
	  
	  }

	public function asignarNumeroCiclosGen($idGeneracion,$numero_pagos){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
		$numero_pagos= $numero_pagos-1;
		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE pagos_conceptos 
				SET numero_pagos = :numero_pagos
				WHERE id_generacion = :idGeneracion and categoria = 'Reinscripción'";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idGeneracion', $idGeneracion);
			$statement->bindParam(':numero_pagos', $numero_pagos);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$con = null;
	$conexion = null;
	return $response;
	}

	public function actualizarfechalimitedepago($numerodepago,$id_concepto,$fechalimitedepago){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE a_pagos 
				SET fecha_limite_pago = :fecha_limite_pago
				WHERE id_concepto = :id_concepto and numero_de_pago = :numero_de_pago";

			$statement = $con->prepare($sql);
			$statement->bindParam(':id_concepto', $id_concepto);
			$statement->bindParam(':numero_de_pago', $numerodepago);
			$statement->bindParam(':fecha_limite_pago', $fechalimitedepago);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$con = null;
	$conexion = null;
	return $response;
	}

	public function recuperarAsignarBloqueo($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT bloqueo_fisico, bloqueo_digital, fecha_fisico, fecha_digital 
				FROM listado_documentos_generacion
				WHERE id_bloqueo = :idBloq";

			$statement = $con->prepare($sql);
			$statement->execute($id);

			if($statement->errorInfo()[0] == "00000"){
				$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
			}
		}
	$con = null;
	$conexion = null;
	return $response;
	}



}
