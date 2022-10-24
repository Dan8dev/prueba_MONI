<?php 
date_default_timezone_set("America/Mexico_City");

class Caja{

	public function registrar_a_caja($datos){
		$conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
		$response = [];

		$sql = "INSERT INTO `caja`(`cliente`, `concepto`, `monto`, `id_usuario`, `comentario`) 
		VALUES (:inp_cliente, :inp_concepto , :inp_monto, :usuario, :inp_comentario);";

		$stmt = $con->prepare($sql);
		$stmt->execute($datos);
		if($stmt->errorInfo()[0] == '00000'){
			$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
		}else{
			$response = ['estatus'=>'error', 'info'=>'Ha ocurrido un error al registrar', 'detalle' => $stmt->errorInfo()];
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}
}