<?php 
class documentos{
	public function buscarGrados(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT id_gradoE, nombre FROM grado_estudio";
			$statement = $con->prepare($sql);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function registrarDocumentos($doc){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "INSERT INTO `documentos`
			(id_prospectos, id_documento, nombre_archivo, tipo_estudio, validacion,fecha_entrega)VALUES(:idUsuario, :id_identificacionA, :nNameIA, '',0, NOW()),
			(:idUsuario, :id_identificacionR, :nNameIR, '',0,NOW()),
			(:idUsuario, :id_acta, :nNameA, '',0,NOW()),
			(:idUsuario, :id_curp, :nNameC, '',0,NOW()),
			(:idUsuario, :id_gradoEstudios, :nNameE, :selGrado,0,NOW()),
			(:idUsuario, :id_fotoOvalo, :nNameFO, '',0,NOW()),
			(:idUsuario, :id_fotoInfantil, :nNameFI, '',0,NOW())";
			
			$statement = $con->prepare($sql);
			//$statement->bindParam(':nNameI')
			$statement->execute($doc);

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$doc];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	function consultarDocumentos($usuario){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT * FROM documentos WHERE id_prospectos = :idusuario";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idusuario', $usuario, PDO::PARAM_INT);
			$statement->execute();
			
			$conexion = null;
			$con = null;

			return $statement;
		}
	}

	function buscarDocumento($documento,$usuario){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT * FROM documentos WHERE id_prospectos = :idUsu AND id_documento = :idDoc";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idDoc', $documento, PDO::PARAM_STR);
			$statement->bindParam(':idUsu', $usuario, PDO::PARAM_INT);
			$statement->execute();
			
			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql];
			}
			$conexion = null;
			$con = null;

			return $response;
		}
	}

	function modificarDocumento($idDocument, $idModify, $nName){
		
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if ($con['info'] == 'ok') {
			$con = $con['conexion'];

			$sql = "UPDATE documentos SET nombre_archivo = :nom ,validacion = 0, fecha_entrega = NOW() WHERE id_documento = :idDoc  AND id_prospectos = :idMod";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idDoc', $idDocument, PDO::PARAM_INT);
			$statement->bindParam(':idMod', $idModify, PDO::PARAM_INT);
			$statement->bindParam(':nom', $nName, PDO::PARAM_STR);
			$statement->execute();

			if($statement->errorInfo()[0] == "00000"){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	function habilitarInputs($usuario){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if ($con['info'] == 'ok') {
			$con = $con['conexion'];

			$sql = "SELECT id_documento FROM documentos WHERE id_prospectos = :idUsu";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idUsu', $usuario, PDO::PARAM_INT);
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