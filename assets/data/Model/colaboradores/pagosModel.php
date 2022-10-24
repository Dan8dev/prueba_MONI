<?php 
date_default_timezone_set("America/Mexico_City");
	class Pagos {
		public function generarCorteComisionColaborador($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

					unset($data["periodo"]);
				#`idPago`, `idColaborador`, `montoCalculado`, `fechaCorte`, `jsonEC`, `pagado`
				$sql = "INSERT INTO `cc_cortes_comisiones`(`idColaborador`, `montoCalculado`, `fechaCorte`, `jsonEC`, `pagado`) VALUES (:colaborador, :montoTotal, :fechaCorte, :jsonEC, 0)"; 
				
				$statement = $con->prepare($sql); 
				$statement->execute($data);			  
					
				if($statement->errorInfo()[0] == "00000"){
					$last_id = $con->lastInsertId();
					$response = ["estatus"=>"ok", "data"=> $last_id, "update_pagos"=>$this->marcar_depositos_pagados($data["jsonEC"], $last_id)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		public function marcar_depositos_pagados($estadoCuenta, $last_id){
			$ECuenta = json_decode($estadoCuenta, true);
			$f = function($item){
				return $item["id_operacion"];
			};

			$ids = array_map($f, $ECuenta["operaciones"]);

			/* Comienza modelo */
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$strIds = implode(",", $ids);
				$sql = "UPDATE `a_pagos` SET corte = $last_id WHERE id_pago IN ({$strIds})"; 

				$statement = $con->prepare($sql); 
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		
		}
		public function consultarCorteComisionPeriodo($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				#`idPago`, `idColaborador`, `montoCalculado`, `fechaCorte`, `jsonEC`, `pagado`
				$sql = "SELECT * FROM `cc_cortes_comisiones` WHERE DATE(`fechaCorte`) = :fechaF AND `idColaborador` = :colaborador;"; 

				$statement = $con->prepare($sql); 
				$statement->execute($data);			  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		public function consultarTodoCortesColaborador($colaborador){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM `cc_cortes_comisiones` WHERE `idColaborador` = :colaborador ORDER BY `cc_cortes_comisiones`.`fechaCorte` DESC;"; 

				$statement = $con->prepare($sql); 
				$statement->bindParam(":colaborador", $colaborador);
				$statement->execute();
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

	}
?>