<?php 
class Examen{
	function cargar_examenes($curso,$edukt){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			
			
			if($edukt != ''){
				$sql = "SELECT * FROM `cursos_examen` WHERE  id_carrera = $curso;";
			}else{
				$sql = "SELECT * FROM `cursos_examen` WHERE  idCurso = $curso and tipo_examen != 2;";
			}

			//$sql = "SELECT * FROM `cursos_examen` WHERE  idCurso = :curso and tipo_examen != 2;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":curso", $curso);
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

	function alumno_examen_respuestas($alumno, $examen){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `curso_examen_alumn_resultado` WHERE  idAlumno = :alumno AND idExamen = :examen  ORDER BY `curso_examen_alumn_resultado`.`idResultado` DESC;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":alumno", $alumno);
			$statement->bindParam(":examen", $examen);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$datas = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($datas as $kData => $valData) {
					$datas[$kData]['calificacion'] = round($datas[$kData]['calificacion'],1, PHP_ROUND_HALF_DOWN);
				}
				$response = ["estatus"=>"ok", "data"=>$datas];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
		
		return $response;
	}

	function cargar_examen_id($examen){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `cursos_examen` WHERE  idExamen = :examen;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":examen", $examen);
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

	function cargar_preguntas_examen($examen){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `cursos_examen_preguntas` WHERE  idExamen = :examen;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":examen", $examen);
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

	function cargar_pregunta_ID($pregunta){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `cursos_examen_preguntas` WHERE  idPregunta = :pregunta;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":pregunta", $pregunta);
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

	public function obtenerusuario($idusuario){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = "SELECT  ac.id_afiliado, ac.id_prospecto, ap.idAsistente,ap.idEvento ,ap.nombre, ap.apaterno, ap.amaterno,ap.correo,ap.codigo, ac.fnacimiento, ac.curp, ac.pais, ac.estado, ac.ciudad, ac.colonia, ac.calle, ac.cp, ac.email, ac.celular, ac.facebook, ac.instagram, ac.twitter, ac.ugestudios, ac.tipoLicenciatura, ac.cedulap, ac.foto, ac.membresia, ac.finmembresia, ac.fechaactivacion, ac.fecha_registro, ac.clase, ac.plantillapp 
						FROM afiliados_conacon as ac
						JOIN a_prospectos as ap on ap.idAsistente=ac.id_prospecto
						WHERE id_afiliado = :id_afiliado;)";

			$statement = $con->prepare($sql);

			$statement->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

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

	/*function consultar_respuesta_pregunta_alumno($pregunta, $alumno){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `cursos_examen_preguntas` WHERE  idExamen = :examen;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":examen", $examen);
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
	}*/

	function finalizar_examen($datos){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "INSERT INTO `curso_examen_alumn_resultado`(`idAlumno`, `idExamen`, `calificacion`, `respuestas`, `fechaPresentacion`) VALUES (:alumno,:examen,:calificacion,:respuestas,:fecha)";

			$statement = $con->prepare($sql);
			$statement->execute($datos);

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
		
		return $response;
	}

	function cargar_alumnos_respuestas_examen($alumno, $examen){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `curso_examen_alumn_resultado` WHERE `idAlumno` = :alumno AND `idExamen` = :examen;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':alumno', $alumno);
			$statement->bindParam(':examen', $examen);
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
	function getExamnExtra($pos,$id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
		
		
		if($id != ''){
			$newWhere = "and ce.idExamen = '$id'";
		}else{
			$newWhere = '';
		}

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT ce.Nombre,ce.idExamen,ce.fechaInicio,ce.fechaFin,(SELECT ac.nombre FROM a_carreras as ac WHERE ac.idCarrera = ce.id_carrera) as nameCr,
			(SELECT ac.imagen FROM a_carreras as ac WHERE ac.idCarrera = ce.id_carrera) as imgCr,
			(SELECT mt.nombre FROM materias as mt WHERE mt.id_materia = ce.idCurso) as nameCurs,
			(SELECT ac.imgFondo FROM a_carreras as ac WHERE ac.idCarrera = ce.id_carrera) as imgCr1,
			(SELECT ag.imagen_generacion FROM a_generaciones as ag WHERE ag.idGeneracion = ce.id_generacion) as imgGen,
			(SELECT ac.idInstitucion FROM a_carreras as ac WHERE ac.idCarrera = ce.id_carrera) as idInst
			FROM a_pagos as ap
			JOIN pagos_conceptos as pc on pc.id_concepto = ap.id_concepto
			JOIN cursos_examen as ce on ce.idExamen = pc.idExamen
			WHERE ap.id_prospecto = '$pos' and ce.tipo_examen = 2 and ap.estatus = 'verificado' $newWhere;";

			$statement = $con->prepare($sql);
			$statement->execute();

			if($statement->errorInfo()[0] === "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC), 'count'=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "data"=>[], "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
		
		return $response;
	}
}
?>
