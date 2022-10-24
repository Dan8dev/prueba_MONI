<?php 
date_default_timezone_set("America/Mexico_City");
	class Medico {
		public function consultarMedico_ById($id){ #Consultar medico por id
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				//$sql = "SELECT * FROM pm_medicos WHERE id = $id;";
				$sql = "SELECT pm_medicos.*, pm_medicos.nombre as nombres FROM pm_medicos WHERE id = $id;";

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

	}
?>