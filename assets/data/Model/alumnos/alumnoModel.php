<?php 
date_default_timezone_set("America/Mexico_City");
	class Alumno {
		public function consultarAlumno_ById($id){ #Consultar alumno por id
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM a_alumnos WHERE id_prospecto = :alumno;";

				$statement = $con->prepare($sql);
				$statement->execute($data);			 
				
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}
		public function consultarAlumnos_Depositos($data){ 
			/*data = [:alumno,:fecha_i,:fecha_f]*/
			$conexion = new Conexion(); # invocar al objeto
			$con = $conexion->conectar(); # recibir el arreglo ['conexion'=>Obj, 'info'=>'ok'||'error']
			$response = [];

			if($con["info"] == "ok"){ # validar el info de la consulta
					#En caso de exito sobreescribe el arreglo con el objeto de conexion
				$con = $con["conexion"];
				
				$estatus_corte = "IS NULL";
				$extra = "";
				if(isset($data['band_corte'])){
					$estatus_corte = " = ".$data["band_corte"];
					unset($data["band_corte"]);
				}else{
					$extra = "OR (DATE(fechapago) <= :fecha_f AND corte IS NULL AND id_prospecto = :alumno  AND a_pagos.restante <= 1  AND a_pagos.estatus = 'verificado')";
				}
				
				$sql = "SELECT a_pagos.*, a_pagos.costototal as montopagado, ag.idCarrera as id_carrera, pagos_conceptos.categoria as concepto FROM a_pagos 
				JOIN pagos_conceptos ON pagos_conceptos.id_concepto = a_pagos.id_concepto
				JOIN a_generaciones ag ON ag.idGeneracion = pagos_conceptos.id_generacion
				WHERE id_prospecto = :alumno AND (DATE(fechapago) >= :fecha_i AND DATE(fechapago) <= :fecha_f)  AND a_pagos.restante <= 1 AND a_pagos.estatus = 'verificado'
				AND `corte` {$estatus_corte} {$extra}"; # se almacena la consulta en un string para poder hacer debug en caso de error
				$statement = $con->prepare($sql); #
				
				$statement->execute($data);			  
				// var_dump($statement, $sql, $data);
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}
		
		function validar_matricula($matricula){ 
			$conexion = new Conexion(); # invocar al objeto
			$con = $conexion->conectar(); # recibir el arreglo ['conexion'=>Obj, 'info'=>'ok'||'error']
			$response = [];

			if($con["info"] == "ok"){ # validar el info de la consulta
				$con = $con["conexion"];
				
				$sql = "SELECT * FROM afiliados_conacon WHERE matricula = :matricula;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':matricula', $matricula);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		function perfil_academico($prospecto, $generacion = false){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$alumno = [];
			if(gettype($generacion) == 'array'){
				foreach($generacion as $gen){
					$alumno['generacion'][] = $con->query("SELECT * FROM alumnos_generacion WHERE idgeneracion = {$gen} AND idalumno = {$prospecto}")->fetch(PDO::FETCH_ASSOC);
				}
			}else if($generacion === false){
				$alumno['generacion'] = $con->query("SELECT * FROM alumnos_generacion WHERE idalumno = {$prospecto}")->fetchAll(PDO::FETCH_ASSOC);
			}else{
				$alumno['generacion'] = $con->query("SELECT * FROM alumnos_generacion WHERE idgeneracion = {$generacion} AND idalumno = {$prospecto}")->fetch(PDO::FETCH_ASSOC);
			}
		}
	}
?>
