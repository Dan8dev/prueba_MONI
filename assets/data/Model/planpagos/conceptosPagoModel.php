<?php 
date_default_timezone_set("America/Mexico_City");

class ConeceptosPagos{

	public function obtenerConcetosActivos_by($campo, $valor){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];

			$campos = ['id_concepto', 'concepto', 'clave', 'pago_aplicar', 'monto', 'idPlan_pago', 'parcialidades', 'descripcion'];
			if(in_array($campo, $campos)){
				$sql = "SELECT * FROM `vistas_afiliados` WHERE {$campo} = :valor;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':valor', $valor);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ['estatus'=>'error','info'=>'campo_no_valido'];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

}