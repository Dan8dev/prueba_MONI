<?php 
date_default_timezone_set("America/Mexico_City");

class Domiciliar{

	public function info_plan($prospecto, $generacion){
		$conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
		$response = [];

		$sql = "SELECT plan_domiciliacion, cliente_domiciliacion FROM alumnos_generaciones WHERE idalumno = :prospecto AND idgeneracion = :generacion;";

		$stmt = $con->prepare($sql);
		$stmt->bindParam(':prospecto', $prospecto);
		$stmt->bindParam(':generacion', $generacion);
		$stmt->execute();
		if($stmt->errorInfo()[0] == '00000'){
			$response = ['estatus'=>'ok', 'data'=>$stmt->fetch(PDO::FETCH_ASSOC)];
		}else{
			$response = ['estatus'=>'error', 'info'=>'Ha ocurrido un error al registrar', 'detalle' => $stmt->errorInfo()];
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

    public function actualizar_plan($prospecto, $generacion, $plan, $cliente){
		$conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
		$response = [];

		$sql = "UPDATE alumnos_generaciones SET plan_domiciliacion = :plan, cliente_domiciliacion = :cliente
         WHERE idalumno = :prospecto AND idgeneracion = :generacion;";

		$stmt = $con->prepare($sql);
		$stmt->bindParam(':prospecto', $prospecto);
		$stmt->bindParam(':generacion', $generacion);
		$stmt->bindParam(':plan', $plan);
		$stmt->bindParam(':cliente', $cliente);
		$stmt->execute();

		if($stmt->errorInfo()[0] == '00000'){
			$response = ['estatus'=>'ok', 'data'=>$stmt->rowCount(PDO::FETCH_ASSOC)];
		}else{
			$response = ['estatus'=>'error', 'info'=>'Ha ocurrido un error al registrar', 'detalle' => $stmt->errorInfo()];
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

    public function consultar_plan_cliente($plan, $cliente){
        $conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
        $sql = "SELECT prs.nombre,prs.aPaterno, prs.aMaterno, prs.correo, prs.tipoPago, agn.idalumno, agn.idgeneracion, agn.plan_domiciliacion, agn.cliente_domiciliacion, pcon.*  FROM alumnos_generaciones agn
            JOIN pagos_conceptos pcon ON pcon.id_generacion = agn.idgeneracion
            JOIN a_prospectos prs ON prs.idAsistente = agn.idalumno
            WHERE agn.plan_domiciliacion = :plan AND agn.cliente_domiciliacion = :cliente
            AND pcon.categoria = 'Mensualidad';";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':plan', $plan);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function validar_order_id($order_id){
        $conexion = new Conexion();
		$con = $conexion->conectar()['conexion'];
        $sql = "SELECT * FROM a_pagos WHERE order_id = :order_id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}