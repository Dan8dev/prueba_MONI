<?php 
date_default_timezone_set("America/Mexico_City");

class PlanPagos{

	public function consultarPlanpagos_ById($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = 
			"SELECT * FROM a_plan_pagos WHERE idPersona = :id;";
			//echo "****";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':id', $id);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				//var_dump($response['data']['estatus']);
				if($response['data']['estatus']=='0'){
					$response = ['estatus'=>'error', 'info'=>'El usuario se encuentra en estatus desactivado','data'=>[]];
				}

				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	public function obtenerAfiliados(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT aPros.idAsistente, concat(aPros.nombre, ' ', aPros.aPaterno,' ' , aPros.aMaterno) as nombre  
					FROM a_prospectos aPros
					WHERE NOT EXISTS(SELECT NULL
					FROM plan_pago_asignacion planAsig
					WHERE aPros.idAsistente = planAsig.idAfiliado)";
			
			/*$sql = "SELECT aPros.idAsistente, concat(aPros.nombre, ' ', aPros.aPaterno,' ' , aPros.aMaterno) as nombre  
					FROM a_prospectos aPros
					WHERE aPros.idAsistente NOT IN(SELECT NULL
					FROM plan_pago_asignacion planAsig
					WHERE aPros.idAsistente = planAsig.idAfiliado)";*/

			/*bien
			$sql = "SELECT aPros.idAsistente, concat(aPros.nombre, ' ', aPros.aPaterno,' ' , aPros.aMaterno) as nombre  
					FROM a_prospectos aPros
					WHERE NOT EXISTS(SELECT NULL
					FROM plan_pago_asignacion planAsig
					WHERE aPros.idAsistente = planAsig.idAfiliado)";*/
					

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

	public function buscarAlumnosCarrera($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response  = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT al.estatus, al.idalumno, al.idgeneracion, aCarr.idCarrera, UPPER(CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno)) AS nombre, af.id_afiliado 
			FROM a_carreras as aCarr
			JOIN a_generaciones AS aGen ON aGen.idCarrera = aCarr.idCarrera
			JOIN alumnos_generaciones as al on al.idgeneracion = aGen.idGeneracion
			JOIN a_prospectos AS ap ON ap.idAsistente = al.idalumno
			JOIN afiliados_conacon AS af ON ap.idAsistente = af.id_prospecto
			WHERE aCarr.idCarrera = :idCarr AND al.idGeneracion  = :idGen;";

			$statement = $con->prepare($sql);
			$statement->execute($data);
			if(true){
				$conexion = null;
				$con = null;
				return $statement;
			}
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

	public function Validar_pagos_alumno($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE alumnos_generaciones SET estatus = 6 WHERE idalumno = :id AND idgeneracion = :idGen;";

			$statement = $con->prepare($sql);
			$statement->execute($data);

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


	public function obtenerCarreras(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT carr.tipo, carr.idCarrera, carr.nombre  
					FROM a_carreras AS carr
					WHERE carr.idCarrera NOT IN(SELECT ac.idCarrera 
												FROM a_carreras as ac 
												JOIN planes_pagos as plp on plp.idCarrera=ac.idCarrera)
					AND carr.estatus = 1 AND carr.tipo != '';";

			/*
			$sql = "SELECT idCarrera, nombre  
					FROM a_carreras 
					WHERE estatus = 1";*/

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



	public function obtenerEventos(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT ev.idEvento, ev.nombreClave FROM ev_evento ev 
			WHERE ev.idEvento NOT IN(
				SELECT pp.idEvento FROM planes_pagos pp WHERE pp.idEvento IS NOT NULL
				) AND estatus = 1 ";
			/*
			$sql = "SELECT idEvento, nombreClave 
					FROM ev_evento 
					WHERE estatus = 1";*/

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


	public function crearPlanPago($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO planes_pagos
			(nombre, total,totalusd, creado_por, fecha_creado, activo)VALUES(:nombreplan, :total,:totalusd, :creador_por, :fCreado, 1);";

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

	public function addAfiliado($idPlan, $idAfiliado, $fCreado){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO plan_pago_asignacion
			(idPlan, idAfiliado, idCarrera, idGeneracion, idEvento, fecha_registro)VALUES(:idPlan, :idAfi, 0, 0, 0, :fCreado);";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idPlan', $idPlan);
			$statement->bindParam(':idAfi', $idAfiliado);
			$statement->bindParam(':fCreado', $fCreado);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function addCarrera($idPlan, $idCarrera){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE planes_pagos 
					SET idCarrera=:idCarr
					WHERE idPlanPago=:idPlan;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idPlan', $idPlan);
			$statement->bindParam(':idCarr', $idCarrera);
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


	public function addEventos($idPlan, $idEvento){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE planes_pagos 
			SET idEvento=:idEvent
			WHERE idPlanPago=:idPlan;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idPlan', $idPlan);
			$statement->bindParam(':idEvent', $idEvento);
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

	public function notAddAfiliado($idPlan, $idAfiliado){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "DELETE FROM plan_pago_asignacion
			WHERE idPlan = :idPlan AND idAfiliado = :idAfi";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idPlan', $idPlan);
			$statement->bindParam(':idAfi', $idAfiliado);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function notAddCarrera($idPlan, $idCarrera){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "DELETE FROM plan_pago_asignacion
			WHERE idPlan = :idPlan AND idCarrera = :idCarr";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idPlan', $idPlan);
			$statement->bindParam(':idCarr', $idCarrera);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}


	public function notAddEvento($idPlan, $idEvento){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "DELETE FROM plan_pago_asignacion
			WHERE idPlan = :idPlan AND idEvento = :idEvent";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idPlan', $idPlan);
			$statement->bindParam(':idEvent', $idEvento);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function crearPlanPagoConcepto($costotitulacion,$costotitulacionusd,$costoInscripcion,$costoInscripcionusd, $numeroMensualidad, $costoMensualidad, $costoMensualidadusd, $numeroReinscripcion, $costoReinscripcion, $costoReinscripcionusd, $fCreado, $creadoPor, $id, $nombre,$fechalimitepagoins,$fechalimitepagotit,$diasdecorte){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO pagos_conceptos
			(concepto, descripcion, precio,precio_usd, categoria, pago_aplicar, idPlan_pago, parcialidades, eliminado, fechacreado, creado_por, actualizado_por, fecha_actualizado, numero_pagos,fechalimitepago)
			VALUES(:nomI , :nomI, :costoIns, :costoInsusd, 'Inscripci??n', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, 1,:fechalimitepagoins),
			(:nomM, :nomM, :costoMen, :costoMenusd, 'Mensualidad', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, :numeroMen,:diadecorte),
			(:nomR, :nomR, :costoReins,:costoReinsusd, 'Reinscripci??n', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, :numeroReins,NULL),
			(:nomTit, :nomTit, :costoTIT, :costoTITusd, 'Titulaci??n', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, 1,:fechalimitepagotit)";
			

			$statement = $con->prepare($sql);
			$statement->bindParam(':costoIns', $costoInscripcion);
			$statement->bindParam(':costoInsusd', $costoInscripcionusd);
			$statement->bindParam(':numeroMen', $numeroMensualidad);
			$statement->bindParam(':costoMen', $costoMensualidad);
			$statement->bindParam(':costoMenusd', $costoMensualidadusd);
			$statement->bindParam(':numeroReins', $numeroReinscripcion);
			$statement->bindParam(':costoReins', $costoReinscripcion);
			$statement->bindParam(':costoReinsusd', $costoReinscripcionusd);
			$statement->bindParam(':costoTIT', $costotitulacion);
			$statement->bindParam(':costoTITusd', $costotitulacionusd);
			$statement->bindParam(':f_creado', $fCreado);
			$statement->bindParam(':creado_por',$creadoPor);
			$statement->bindParam(':id', $id);
			$nomI = 'Inscripci??n - '.$nombre;
			$nomM = 'Mensualidad - '.$nombre;
			$nomR = 'Reinscripci??n - '.$nombre;
			$nomTIT = 'Titulaci??n - '.$nombre;
			$statement->bindParam(':nomI', $nomI);
			$statement->bindParam(':nomM', $nomM);
			$statement->bindParam(':nomR', $nomR);
			$statement->bindParam(':nomTit', $nomTIT);
			$statement->bindParam(':fechalimitepagoins', $fechalimitepagoins);
			$statement->bindParam(':fechalimitepagotit', $fechalimitepagotit);
			$statement->bindParam(':diadecorte', $diasdecorte);

			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function crearPlanPagoConcepto_Generacion($costotitulacion,$costotitulacionusd,$costoInscripcion,$costoInscripcionusd, $numeroMensualidad, $costoMensualidad, $costoMensualidadusd, $numeroReinscripcion, $costoReinscripcion, $costoReinscripcionusd, $fCreado, $creadoPor, $id, $nombre,$fechalimitepagoins,$fechalimitepagotit,$diasdecorte){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO pagos_conceptos
			(concepto, descripcion, precio,precio_usd, categoria, pago_aplicar, id_generacion, parcialidades, eliminado, fechacreado, creado_por, actualizado_por, fecha_actualizado, numero_pagos,fechalimitepago)
			VALUES(:nomI , :nomI, :costoIns, :costoInsusd, 'Inscripci??n', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, 1,:fechalimitepagoins),
			(:nomM, :nomM, :costoMen, :costoMenusd, 'Mensualidad', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, :numeroMen,:diadecorte),
			(:nomR, :nomR, :costoReins, :costoReinsusd, 'Reinscripci??n', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, :numeroReins,NULL),
			(:nomTit, :nomTit, :costoTIT, :costoTITusd, 'Titulaci??n', '*', :id, 2, 1, :f_creado, :creado_por, NULL, NULL, 1,:fechalimitepagotit)";
			

			$statement = $con->prepare($sql);
			$statement->bindParam(':costoIns', $costoInscripcion);
			$statement->bindParam(':costoInsusd', $costoInscripcionusd);
			$statement->bindParam(':numeroMen', $numeroMensualidad);
			$statement->bindParam(':costoMen', $costoMensualidad);
			$statement->bindParam(':costoMenusd', $costoMensualidadusd);
			$statement->bindParam(':numeroReins', $numeroReinscripcion);
			$statement->bindParam(':costoReins', $costoReinscripcion);
			$statement->bindParam(':costoReinsusd', $costoReinscripcionusd);
			$statement->bindParam(':costoTIT', $costotitulacion);
			$statement->bindParam(':costoTITusd', $costotitulacionusd);
			$statement->bindParam(':f_creado', $fCreado);
			$statement->bindParam(':creado_por',$creadoPor);
			$statement->bindParam(':id', $id);
			$nomI = 'Inscripci??n - '.$nombre;
			$nomM = 'Mensualidad - '.$nombre;
			$nomR = 'Reinscripci??n - '.$nombre;
			$nomTIT = 'Titulaci??n - '.$nombre;
			$statement->bindParam(':nomI', $nomI);
			$statement->bindParam(':nomM', $nomM);
			$statement->bindParam(':nomR', $nomR);
			$statement->bindParam(':nomTit', $nomTIT);
			$statement->bindParam(':fechalimitepagoins', $fechalimitepagoins);
			$statement->bindParam(':fechalimitepagotit', $fechalimitepagotit);
			$statement->bindParam(':diadecorte', $diasdecorte);

			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function crearPlanPagoConceptoCer($costoInscripcion,$costoInscripcionusd, $fCreado, $creadoPor, $id, $nombre,$fechalimitepagoins){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO pagos_conceptos
			(concepto, descripcion, precio,precio_usd, categoria, pago_aplicar, idPlan_pago, parcialidades, eliminado, fechacreado, creado_por, actualizado_por, fecha_actualizado, numero_pagos, fechalimitepago)
			VALUES(:nomI , :nomI, :costoIns, :costoInsusd, 'Inscripci??n', '*', :id, 1, 1, :f_creado, :creado_por, NULL, NULL, 1,:fechalimitepagoins)";

			$statement = $con->prepare($sql);
			$statement->bindParam(':costoIns', $costoInscripcion);
			$statement->bindParam(':costoInsusd', $costoInscripcionusd);
			$statement->bindParam(':f_creado', $fCreado);
			$statement->bindParam(':creado_por',$creadoPor);
			$statement->bindParam(':id', $id);
			$nomI = 'Inscripci??n - '.$nombre;
			$statement->bindParam(':nomI', $nomI);
			$statement->bindParam(':fechalimitepagoins', $fechalimitepagoins);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function crearPlanPagoConceptoCer_Generacion($costoInscripcion,$costoInscripcionusd, $fCreado, $creadoPor, $id, $nombre,$fechalimitepagoins){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO pagos_conceptos
			(concepto, descripcion, precio,precio_usd, categoria, pago_aplicar, id_generacion, parcialidades, eliminado, fechacreado, creado_por, actualizado_por, fecha_actualizado, numero_pagos, fechalimitepago)
			VALUES(:nomI , :nomI, :costoIns, :costoInsusd, 'Inscripci??n', '*', :id, 1, 1, :f_creado, :creado_por, NULL, NULL, 1,:fechalimitepagoins)";

			$statement = $con->prepare($sql);
			$statement->bindParam(':costoIns', $costoInscripcion);
			$statement->bindParam(':costoInsusd', $costoInscripcionusd);
			$statement->bindParam(':f_creado', $fCreado);
			$statement->bindParam(':creado_por',$creadoPor);
			$statement->bindParam(':id', $id);
			$nomI = 'Inscripci??n - '.$nombre;
			$statement->bindParam(':nomI', $nomI);
			$statement->bindParam(':fechalimitepagoins', $fechalimitepagoins);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}
	
	public function obtenerPlanesPago(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT pp.idPlanPago,pp.nombre,pp.total, ac.nombre as nombreCarrer, ee.titulo as nombreEvent
			FROM planes_pagos as pp
			LEFT JOIN a_carreras as ac on ac.idCarrera=pp.idCarrera
			LEFT JOIN ev_evento as ee on ee.idEvento=pp.idEvento;";

			/*
			$sql = "SELECT * 
					FROM planes_pagos 
					WHERE activo = 1";
			*/

			$statement = $con->prepare($sql);
			$statement->execute();

			$conexion = null;
			$con = null;

			return $statement;
			
		}
	}

	public function buscarPlan($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT plan.*, pConceptos.*,ac.nombre as nombreCarrer, ee.titulo as nombreEvent,ac.tipo as tipoCarrera
			FROM planes_pagos plan
			LEFT JOIN pagos_conceptos pConceptos ON pConceptos.idPlan_Pago = plan.idPlanPago
			LEFT JOIN a_carreras as ac on ac.idCarrera=plan.idCarrera
			LEFT JOIN ev_evento as ee on ee.idEvento=plan.idEvento
			WHERE plan.idPlanPago = :idPlan AND (categoria = 'inscripcion' OR categoria = 'mensualidad' OR categoria = 'reinscripcion' OR categoria = 'titulaci??n')";

			$statement = $con->prepare($sql);
			$statement->execute($id);

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
			}
		}

		$conexion = null;
		$con = null;
		return $response;
	}

	public function obtenerListaAfiliadosMod($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT aPros.idAsistente, concat(aPros.nombre, ' ', aPros.aPaterno,' ' , aPros.aMaterno) as nombre 
			FROM a_prospectos aPros
			LEFT JOIN plan_pago_asignacion planAsig
			ON aPros.idAsistente = planAsig.idAfiliado
			WHERE planAsig.idPlan = :id OR planAsig.idAfiliado IS NULL";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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

	public function obtenerListaCarrerasMod($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "SELECT carr.idCarrera, carr.nombre  
					FROM a_carreras carr
					LEFT JOIN plan_pago_asignacion planAsig
					ON carr.idCarrera = planAsig.idCarrera
					WHERE planAsig.idPlan = :id AND carr.estatus = 1 OR planAsig.idCarrera IS NULL AND carr.estatus = 1";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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


	public function obtenerListaEventosMod($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT ev.idEvento, ev.nombreClave 
			FROM ev_evento ev
			LEFT JOIN plan_pago_asignacion planAsig
			ON ev.idEvento = planAsig.idEvento 
			WHERE planAsig.idPlan = :id AND ev.estatus = 1 OR planAsig.idEvento IS NULL AND ev.estatus = 1";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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
	

	public function buscarAfiliadosMod($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT aProspectos.idAsistente 
			FROM a_prospectos aProspectos
			JOIN plan_pago_asignacion planAsig
			ON aProspectos.idAsistente = planAsig.idAfiliado 
			WHERE planAsig.idPlan = :id";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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

	public function buscarCarrerasMod($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT carr.idCarrera 
			FROM a_carreras carr
			JOIN plan_pago_asignacion planAsig
			ON carr.idCarrera = planAsig.idCarrera 
			WHERE carr.estatus = 1 AND planAsig.idPlan = :id";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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


	public function buscarEventosMod($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT ev.idEvento 
			FROM ev_evento ev
			JOIN plan_pago_asignacion planAsig
			ON ev.idEvento = planAsig.idEvento 
			WHERE ev.estatus = 1 AND planAsig.idPlan = :id";

			$statement = $con->prepare($sql);
			$statement->execute($id);

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


	public function modificarPlanPago($mod){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE planes_pagos SET nombre = :nuevonombre, total =:nuevoTotal,totalusd =:nuevoTotalusd, actualizado_por = :modificado_por, fecha_actualizado = :fModificado 
			WHERE idPlanPago = :id";

			$statement = $con->prepare($sql);
			$statement->execute($mod);

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$mod];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
	}

	public function modificarPlanPagoConceptosIns($nuevoCostoInscripcion,$nuevoCostoInscripcionusd, $fModificado, $modificadoPor, $id, $nuevonombre,$idconceptoins,$nuevafechalimitdepagoins){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE pagos_conceptos SET concepto = :nomI, descripcion = :nomI, precio = :costoIns,precio_usd = :costoInsusd, actualizado_por = :modificado_por, fecha_actualizado = :f_actualizacion, fechalimitepago=:fechalimitepago
			WHERE idPlan_pago = :id AND id_concepto = :id_concepto";

			$statement = $con->prepare($sql);
			$statement->bindParam(':costoIns', $nuevoCostoInscripcion);
			$statement->bindParam(':costoInsusd', $nuevoCostoInscripcionusd);
			$statement->bindParam(':f_actualizacion', $fModificado);
			$statement->bindParam(':modificado_por',$modificadoPor);
			$statement->bindParam(':id', $id);
			$nomI = 'Inscripci??n - '.$nuevonombre;
			$statement->bindParam(':nomI', $nomI);
			$statement->bindParam(':id_concepto', $idconceptoins);
			$statement->bindParam(':fechalimitepago', $nuevafechalimitdepagoins);

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

	public function modificarPlanPagoConceptosInsevento($nuevoCostoInscripcion, $fModificado, $modificadoPor, $id, $nuevonombre,$fechalimitepagoins){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE pagos_conceptos SET concepto = :nomI, descripcion = :nomI, precio = :costoIns, actualizado_por = :modificado_por, fecha_actualizado = :f_actualizacion, fechalimitepago=:fechalimitepagoinsevt 
			WHERE id_concepto = :id AND categoria = 'Inscripci??n'";

			$statement = $con->prepare($sql);
			$statement->bindParam(':costoIns', $nuevoCostoInscripcion);
			$statement->bindParam(':f_actualizacion', $fModificado);
			$statement->bindParam(':modificado_por',$modificadoPor);
			$statement->bindParam(':id', $id);
			$nomI = 'Inscripci??n - '.$nuevonombre;
			$statement->bindParam(':nomI', $nomI);
			$statement->bindParam(':fechalimitepagoinsevt', $fechalimitepagoins);
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

	public function modificarPlanPagoConceptosMen($nuevoNoMensualidades, $nuevoCostoMensualidad, $nuevoCostoMensualidadusd, $fModificado, $modificadoPor, $id, $nuevonombre, $nuevodiacorte, $idconceptomens){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE pagos_conceptos SET concepto = :nomM, descripcion = :nomM, precio = :costoMen, precio_usd = :costoMenusd, actualizado_por = :modificado_por, fecha_actualizado = :f_actualizacion, numero_pagos = :numeroMen, fechalimitepago=:fechalimitepago
			WHERE idPlan_pago = :id AND id_concepto = :id_concepto";

			$statement = $con->prepare($sql);
			$statement->bindParam(':numeroMen', $nuevoNoMensualidades);
			$statement->bindParam(':costoMen', $nuevoCostoMensualidad);
			$statement->bindParam(':costoMenusd', $nuevoCostoMensualidadusd);
			$statement->bindParam(':f_actualizacion', $fModificado);
			$statement->bindParam(':modificado_por',$modificadoPor);
			$statement->bindParam(':id', $id);
			$nomM = 'Mensualidad - '.$nuevonombre;
			$statement->bindParam(':nomM', $nomM);
			$statement->bindParam(':fechalimitepago', $nuevodiacorte);
			$statement->bindParam(':id_concepto', $idconceptomens);
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

	public function modificarPlanPagoConceptosReins($nuevoNoReinscripcion, $nuevoCostoReinscripcion, $nuevoCostoReinscripcionusd, $fModificado, $modificadoPor, $id, $nuevonombre, $idconceptoreins){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE pagos_conceptos SET concepto = :nomR, descripcion = :nomR, precio = :costoReins, precio_usd = :costoReinsusd, actualizado_por = :modificado_por, fecha_actualizado = :f_actualizacion, numero_pagos = :numeroReins
			WHERE idPlan_pago = :id AND id_concepto = :id_concepto";

			$statement = $con->prepare($sql);
			$statement->bindParam(':numeroReins', $nuevoNoReinscripcion);
			$statement->bindParam(':costoReins', $nuevoCostoReinscripcion);
			$statement->bindParam(':costoReinsusd', $nuevoCostoReinscripcionusd);
			$statement->bindParam(':f_actualizacion', $fModificado);
			$statement->bindParam(':modificado_por',$modificadoPor);
			$statement->bindParam(':id', $id);
			$nomR = 'Reinscripci??n - '.$nuevonombre;
			$statement->bindParam(':nomR', $nomR);
			$statement->bindParam(':id_concepto', $idconceptoreins);
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

	public function modificarPlanPagoConceptosTit($nuevocostoTit,$nuevocostoTitusd, $fModificado, $modificadoPor, $id, $nuevonombre,$idconceptotit,$nuevafechalimitdepagotit){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE pagos_conceptos SET concepto = :nomI, descripcion = :nomI, precio = :costoIns, precio_usd = :costoInsusd, actualizado_por = :modificado_por, fecha_actualizado = :f_actualizacion, fechalimitepago=:fechalimitepago
			WHERE idPlan_pago = :id AND id_concepto = :id_concepto";

			$statement = $con->prepare($sql);
			$statement->bindParam(':costoIns', $nuevocostoTit);
			$statement->bindParam(':costoInsusd', $nuevocostoTitusd);
			$statement->bindParam(':f_actualizacion', $fModificado);
			$statement->bindParam(':modificado_por',$modificadoPor);
			$statement->bindParam(':id', $id);
			$nomI = 'Inscripci??n - '.$nuevonombre;
			$statement->bindParam(':nomI', $nomI);
			$statement->bindParam(':id_concepto', $idconceptotit);
			$statement->bindParam(':fechalimitepago', $nuevafechalimitdepagotit);

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


	public function eliminarPlan($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE planes_pagos SET activo = 2 WHERE idPlanPago = :idPlan";

			$statement = $con->prepare($sql);
			$statement->execute($id);

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
	}

	 # ------- funciones para el modulo de pagos ------- #
	 function obtener_plan_pagos_($fk, $id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$campos = ['idAfiliado','idCarrera','idGeneracion'];
			if(in_array($fk, $campos)){
				$sql = "SELECT ppa.*, pp.* FROM plan_pago_asignacion ppa
					JOIN planes_pagos pp ON pp.idPlanPago = ppa.idPlan
					WHERE ppa.{$fk} = :id;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':id', $id);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error", "info"=>"No se encontro la clave"];
			}
		}

		$conexion = null;
		$con = null;

		return $response;	
	}

	function obtener_conceptos_plan($plan){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `pagos_conceptos` WHERE idPlan_pago = :plan;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':plan', $plan);
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

	function obtener_pagos_aplicados($concepto, $prospecto){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT pags.*, pc.concepto as concepto_nom, pc.categoria,pc.numero_pagos, pc.parcialidades, prom.porcentaje FROM `a_pagos` pags 
			JOIN pagos_conceptos pc ON pc.id_concepto = pags.id_concepto
			LEFT JOIN promociones prom ON pags.idPromocion = prom.idPromocion
			WHERE pags.id_concepto = :concepto AND pags.id_prospecto = :prospecto AND pags.estatus = 'verificado' ORDER by pags.numero_de_pago, pags.fecha_verificacion, pags.id_pago ASC;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':concepto', $concepto);
			$statement->bindParam(':prospecto', $prospecto);
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

	function obtener_otros_pagos($concepto, $prospecto){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT pags.*, pc.concepto as concepto_nom, pc.categoria,pc.numero_pagos, prom.porcentaje FROM `a_pagos` pags 
			JOIN pagos_conceptos pc ON pc.id_concepto = pags.id_concepto
			LEFT JOIN promociones prom ON prom.idPromocion = pags.idPromocion
			WHERE pags.id_concepto = :concepto AND pags.id_prospecto = :prospecto AND pags.estatus != 'verificado';";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':concepto', $concepto);
			$statement->bindParam(':prospecto', $prospecto);
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

	function obtener_concepto_pago_id($concepto){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			
			$sql = "SELECT * FROM pagos_conceptos WHERE id_concepto = :concepto;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':concepto', $concepto);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;

		return $response;	
	}

	function obtener_plan_pago_carrera($carrera){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			
			$sql = "SELECT * FROM planes_pagos WHERE idCarrera = :carrera;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':carrera', $carrera);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;

		return $response;	
	}

	function obtener_plan_pago_evento($evento){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			
			$sql = "SELECT * FROM planes_pagos WHERE idEvento = :evento;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':evento', $evento);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;

		return $response;	
	}
	function get_info_plan($plan){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		return $con['conexion']->query("SELECT * FROM planes_pagos WHERE idPlanPago = $plan")->fetch(PDO::FETCH_ASSOC);
	}

	function obtener_conceptos_generales($institucion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		return $con['conexion']->query("SELECT * FROM pagos_conceptos WHERE institucion = $institucion AND eliminado = 1 AND generales = 1;")->fetchAll(PDO::FETCH_ASSOC);

	}

	function agregar_concepto_generacion($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "INSERT INTO pagos_conceptos
				(concepto, descripcion, precio, categoria, pago_aplicar, id_generacion, parcialidades, eliminado, fechacreado, creado_por, actualizado_por, fecha_actualizado, numero_pagos, fechalimitepago)
			VALUES(:nomI , :nomI, :costoIns, :categoria , '*', :id, :parcialidades, 1, :f_creado, :creado_por, NULL, NULL, 1,:fechalimitepagoins)";

			$statement = $con->prepare($sql);
			
			$statement->execute($data);

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}
}
