<?php 
date_default_timezone_set("America/Mexico_City");

class Caja{

	public function registrar_a_caja($datos){
		$conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
		$response = [];
		$str_tipo = ['', ''];
		if(isset($datos['tipo'])){
			$str_tipo = [', `tipo`', ', :tipo'];
		}
		$str_hora = ['', ''];
		if(isset($datos['fecha_conekta'])){
			$str_hora = [', `fecha_registro`', ', :fecha_conekta'];
		}

		$sql = "INSERT INTO `caja`(`cliente`, `instituto`, `concepto`, `monto`, `moneda`, `id_usuario`, `comentario` {$str_tipo[0]} {$str_hora[0]}) 
		VALUES (:inp_cliente, :instituto, :inp_concepto , :inp_monto, :moneda, :usuario, :inp_comentario {$str_tipo[1]} {$str_hora[1]});";

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

	public function consultar_movimientos($estatus = 1){
		$conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
		$response = [];

		$sql = "SELECT * FROM `caja` WHERE estatus = :estatus;";

		$stmt = $con->prepare($sql);
		$stmt->bindParam(':estatus', $estatus);
		$stmt->execute();
		if($stmt->errorInfo()[0] == '00000'){
			$datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($datas as $dat_key => $dat_val){
				if($dat_val['id_usuario'] !== null){
					$datas[$dat_key]['persona'] = $this->get_persona($dat_val['id_usuario']);
				}else{
					$datas[$dat_key]['persona'] = null;
				}
			}
			$response = ['estatus'=>'ok', 'data'=>$datas];
		}else{
			$response = ['estatus'=>'error', 'info'=>'Error al consultar', 'detalle'=>$stmt->errorInfo()];
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	function get_persona($id){
		$conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
		$response = [];

		$sql = "SELECT * FROM `a_accesos` WHERE idAcceso = :acceso;";
		$stmt = $con->prepare($sql);
		$stmt->bindParam(':acceso', $id);
		$stmt->execute();
		$datas = $stmt->fetch(PDO::FETCH_ASSOC);

		if($datas['idTipo_Persona'] == 4 || $datas['idTipo_Persona'] == 6){
			$response = $con->query("SELECT * FROM a_plan_pagos WHERE idPersona = ".$datas['idPersona'])->fetch(PDO::FETCH_ASSOC);
		}
		$conexion = null;
		$con = null;
	
		return $response;
	}

}