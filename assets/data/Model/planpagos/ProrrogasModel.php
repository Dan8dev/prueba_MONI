<?php 
date_default_timezone_set("America/Mexico_City");

class Prorrogas{
    public function listar_prorrogas(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT pro.idProrroga, concat(ap.nombre,' ',ap.aMaterno,' ',ap.aPaterno) as nombre_alumno, pc.concepto as nombre_concepto, pro.descripcion, pro.estatus as estatus_prorroga, pro.fechacreado as fecha_solicitud
                    FROM prorrogas as pro
                    JOIN a_prospectos as ap ON ap.idAsistente = pro.idAsistente
                    JOIN pagos_conceptos as pc ON pc.id_concepto = pro.id_concepto";

			$statement = $con->prepare($sql);
			$statement->execute();
			}
		$conexion = null;
		$con = null;
		return $statement;
	}

    public function obtener_informacion_prorroga($idProrroga){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = 
			"SELECT ap.idAsistente,pro.idProrroga, concat(ap.nombre,' ',ap.aMaterno,' ',ap.aPaterno) as nombre_alumno, pc.concepto as nombre_concepto, pro.descripcion, pro.estatus as estatus_prorroga, pro.fechacreado as fecha_solicitud, pro.nuevafechalimitedepago, pro.numero_de_pago, pro.fechalimitepago
            FROM prorrogas as pro
            JOIN a_prospectos as ap ON ap.idAsistente = pro.idAsistente
            JOIN pagos_conceptos as pc ON pc.id_concepto = pro.id_concepto
            WHERE pro.idProrroga= :id;";
			
			$statement = $con->prepare($sql);
            $statement->bindParam(':id', $idProrroga);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

    public function rechazar_prorroga($idProrroga){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE prorrogas 
					SET estatus='rechazado'
					WHERE idProrroga=:idProrroga;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idProrroga', $idProrroga);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

    public function aprobar_prorroga($idProrroga, $nuevafechalimitedepago, $idAsistente){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE prorrogas 
					SET estatus='aprobado', nuevafechaaceptada=:nuevafechalimitedepago
					WHERE idProrroga=:idProrroga AND idAsistente=:idAsistente;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idProrroga', $idProrroga);
			$statement->bindParam(':nuevafechalimitedepago', $nuevafechalimitedepago);
			$statement->bindParam(':idAsistente', $idAsistente);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

    public function obtener_datos_alumno($idAsistente){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = 
			"SELECT concat(nombre,' ',aPaterno,' ',aMaterno) as nombre_completo, correo
            FROM a_prospectos
            WHERE idAsistente= :idAsistente;";
			
			$statement = $con->prepare($sql);
            $statement->bindParam(':idAsistente', $idAsistente);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}
}
