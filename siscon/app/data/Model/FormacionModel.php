<?php 
date_default_timezone_set('America/Mexico_City');
	require_once 'conexion.php';

    class Formacion{

        public function registrarreconocimiento($idusuario){
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