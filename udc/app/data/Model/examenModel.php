<?php 
class Examen{
	function cargar_examenes($curso, $generacion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$sql = "SELECT * FROM `cursos_examen` WHERE  idCurso = :curso AND id_generacion = :generacion AND tipo_examen = 1;";

			$statement = $con->prepare($sql);
			$statement->bindParam(":curso", $curso);
			$statement->bindParam(":generacion", $generacion);
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
			$datos['calificacion'] = round($datos['calificacion'], 1, PHP_ROUND_HALF_DOWN);
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
}
?>
