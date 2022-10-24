<?php 
	require_once 'conexion.php';

	class Pagos {
		public function consultarPlanAlumnos($datos){ #espera objeto ["id_alumno"=>1] 
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT pp.`id_plan`, pp.`id_alumno`, pp.`id_concepto`, pp.`total_pagos`, pp.`pagos_cubiertos`, pp.`total_cubrir`, pp.`fecha_program`, pp.`estatus`,
							pc.`regularidad`, pc.`nombre`
							FROM pago_plan pp 
						    INNER JOIN pago_concepto pc ON pc.`id_concepto` = pp.`id_plan` 
						WHERE id_alumno = :id_alumno;";

				$statement = $con->prepare($sql);
				$statement->execute($datos);


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

		public function PagosRealizados($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT `id_pago`, `id_plan`, `plan_numero_pago`, `fecha_aplicacion`, `fecha_registro`, `monto_aplicado`, `estatus` FROM `pagos` WHERE id_plan = :id_plan;";
				$statement = $con->prepare($sql);
				$statement->execute($datos);


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

		public function PagosRealizadosTipoRecurrentes($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT `id_pago`, `id_plan`, `plan_numero_pago`, `fecha_aplicacion`, `fecha_registro`, `monto_aplicado`, `estatus` FROM `pagos` WHERE id_plan = :id_plan AND `plan_numero_pago` = :numero_pago;";
				$statement = $con->prepare($sql);
				$statement->execute($datos);


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

		public function fechas_pagos_recurrentes($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT `id_fechapago`, `id_plan`, `fecha_programada`, `monto`, `estatus` FROM `pago_fechas` WHERE id_plan = :id_plan AND estatus != 1;";
				$statement = $con->prepare($sql);
				$statement->execute($datos);


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

		public function registrarPago($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "INSERT INTO `pagos`
						(`id_pago`, `id_plan`, `plan_numero_pago`, `fecha_aplicacion`, `fecha_registro`, `monto_aplicado`, `estatus`) 
						VALUES (null , :id_plan, :numeroPago, :fechaApl, :fechaReg, :montoApl , :estatus);";
				$statement = $con->prepare($sql);
				$statement->execute($datos);


				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
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