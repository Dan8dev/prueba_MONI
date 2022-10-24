<?php 
	require_once 'conexion.php';

	class Materia {
		function pago_cursos($prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT pag.*, concep.concepto FROM a_pagos pag INNER JOIN pagos_conceptos concep ON concep.id_concepto = pag.id_concepto
				WHERE pag.id_concepto IN (2, 5, 7, 9) AND pag.id_prospecto = :prospecto;";

				$statement = $con->prepare($sql);
				$statement->bindParam(':prospecto',$prospecto);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
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

		function validarAlumnoMateria($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT * FROM clases_incripcion WHERE idMateria = :materia AND idAlumno = :alumno AND estatus = 1;";

				$statement = $con->prepare($sql);
				$statement->execute($datos);


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

		function cursoClases($curso){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT * FROM clases WHERE idMateria = :materia";

				$statement = $con->prepare($sql);
				$statement->bindParam(':materia', $curso);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
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

		function tareasClase($clase){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT * FROM clases_tareas WHERE idClase = :clase";

				$statement = $con->prepare($sql);
				$statement->bindParam(':clase', $clase);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
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

		function entregar_tareas($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$fecha_hora = date('Y-m-d H:i:s');

				$sql = "INSERT INTO `clases_tareas_entregas`(`idTarea`, `idAlumno`, `archivo`, `comentario`, `calificacion`, `fecha_entrega`) 
				VALUES (:tarea, :alumno, :archivo, :comentario, 0, '{$fecha_hora}') ";

				$statement = $con->prepare($sql);
				$statement->execute($datos);


				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
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

		function obtener_info_tarea_entrega($tarea, $alumno){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT * FROM clases_tareas_entregas WHERE idTarea = :tarea AND idAlumno = :alumno";

				$statement = $con->prepare($sql);
				$statement->bindParam(':tarea', $tarea);
				$statement->bindParam(':alumno', $alumno);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
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
	}
?>