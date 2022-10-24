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



	public function verificacionAlumno($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
		// var_dump($_POST);
		$tipo = $data["tipo"];
		unset($data["tipo"]);
		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			switch($tipo){
				case "consulta":
					$sql = "SELECT ac.verificacion FROM afiliados_conacon ac
					WHERE ac.id_prospecto = :idAlumno;";
					break;
				case "actualiza":
					$sql = "UPDATE afiliados_conacon AS ac SET ac.verificacion = :estatus
					WHERE ac.id_prospecto = :idAlumno;";
					break;
			}
			
			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=> $tipo == "consulta" ?  $statement->fetch(PDO::FETCH_ASSOC) : $statement->rowCount()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	} 

	public function BuscarIdAlumno($idAfiliado){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT ap.idAsistente, ap.nombre, afcon.id_afiliado FROM a_prospectos ap
					JOIN afiliados_conacon afcon ON afcon.id_prospecto = ap.idAsistente
					WHERE ap.idAsistente = :idAfiliado;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':idAfiliado',$idAfiliado);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	public function consultarInfoDocumento($nombreDocumento){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT * FROM listado_documentos WHERE nombre_documento = :nombreDocumento";

			$statement = $con->prepare($sql);
			$statement->bindParam(':nombreDocumento', $nombreDocumento);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
			
			$conexion = null;
			$con = null;

			return $response;
		}
	}

	public function consultarDocumentosVerificacion($idAfiliado){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT ld.id_documento AS idVerificar, ld.nombre_documento, ld.nomenclatura_documento,ld.nacionalidad ,doc.*  FROM listado_documentos ld
					LEFT JOIN documentos as doc ON doc.id_prospectos = :idAfiliado AND ld.id_documento = doc.id_documento
					WHERE ld.verificacion = 1;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':idAfiliado',$idAfiliado);
			$statement->execute();
			
			$conexion = null;
			$con = null;
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	/*public function registrarDocumentos($doc){
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
	}*/

	function consultarDocumentosListaCompleta(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT * FROM listado_documentos ld WHERE ld.estatus = 1";

			$statement = $con->prepare($sql);
			$statement->execute();
			
			$conexion = null;
			$con = null;
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function consultarDocumentosList($usuario){
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
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function consultarDocumentos($usuario){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		
		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$select = "documentos as doc";
			$id = "id_prospectos";
			$join = "";
			$info = "";
			if(is_array($usuario) && isset($usuario['DocFis']) && $usuario['DocFis'] = 'Fisicos'){
				$select = "documentos_fisicos as doc";
				$usuario = $usuario['idUsuario'];
				$id = "id_afiliado";
				$join = "JOIN listado_documentos AS lista ON lista.id_documento = doc.id_documento";
				$info = ",lista.nombre_documento";
			}

			$sql = "SELECT doc.* {$info} FROM {$select} {$join} WHERE doc.{$id} = :idusuario";
			//var_dump($sql);

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
	function registrarDocumento($data){
		if(isset($data["android_id_afiliado"])){
			unset($data["android_id_afiliado"]);
		}
		//var_dump($data);
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "INSERT INTO `documentos`
				(id_prospectos, id_documento, nombre_archivo, tipo_estudio, validacion, fecha_entrega)
				VALUES(:idUsuario, :documento, :nName, :tipoEstudio, 0, :fEntrega)";
			
			$statement = $con->prepare($sql);
			//$statement->bindParam(':nNameI')
			$statement->execute($data);

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
			}
		}
		$conexion = null;
		$con = null;

		return $response;
	}

	function registrarComprobanteEstudio($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "INSERT INTO `documentos`
				(id_prospectos, id_documento, nombre_archivo, tipo_estudio, validacion, fecha_entrega)
				VALUES(:idUsuario, :documento, :nName, :gradoEstudio, 0, :fEntrega)";
			
			$statement = $con->prepare($sql);
			//$statement->bindParam(':nNameI')
			$statement->execute($data);

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
			}
		}
	$conexion = null;
	$con = null;

	return $response;
	}
}
