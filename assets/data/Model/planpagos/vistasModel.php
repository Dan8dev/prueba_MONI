<?php 
date_default_timezone_set("America/Mexico_City");

class Vistas{

	public function vistas_afiliado($id){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = 
			"SELECT * FROM `vistas_afiliados` WHERE idAfiliado = :id;";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':id', $id);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	public function registrar_vista_afiliado($afiliado, $vista){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = "INSERT INTO vistas_afiliados (`idAfiliado`, `vista`) VALUES (:afiliado, :vista);";
			
			$statement = $con->prepare($sql);
			$statement->bindParam(':afiliado', $afiliado);
			$statement->bindParam(':vista', $vista);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	function habilitar_vistas_afiliados($afiliado, $vista){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
		
		if($con["info"] == "ok"){
			$con = $con["conexion"];
			
			$vistas_afil = $this->vistas_afiliado($afiliado)['data'];

			$key_where_afil = "=";
			if(gettype($afiliado) == 'array'){
				$key_where_afil = "IN";
				$afiliado = implode(', ', $afiliado);
			}
			$key_where_vist = "=";
			if(gettype($vista) == 'array'){
				$key_where_vist = "IN";
				foreach($vista as $vist){
					$ix_v = array_search($vist, array_column($vistas_afil, 'vista'));
					if($ix_v === false){
						$this->registrar_vista_afiliado($afiliado, $vist);
					}
				}
				$vista = implode(', ', $vista);
			}else{
				$ix = array_search($vista, array_column($vistas_afil, 'vista'));
				if($ix === false){
					$this->registrar_vista_afiliado($afiliado, $vista);
				}
			}

			$sql = "UPDATE vistas_afiliados SET estatus = 1 WHERE idAfiliado {$key_where_afil} ({$afiliado}) AND vista {$key_where_vist} ({$vista});";
			
			$statement = $con->prepare($sql);
			// $statement->bindParam(':afiliado', $afiliado, PDO::PARAM_STR);
			// $statement->bindParam(':vista', $vista, PDO::PARAM_STR);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount(),"params" =>[[$key_where_afil,$afiliado],[$key_where_vist,$vista]], "sql"=>$sql];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	function des_habilitar_vistas_afiliados($afiliado, $vista){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			
			$key_where_afil = "=";
			if(gettype($afiliado) == 'array'){
				$key_where_afil = "IN";
				$afiliado = implode(', ', $afiliado);
			}
			$key_where_vist = "=";
			if(gettype($vista) == 'array'){
				$key_where_vist = "IN";
				$vista = implode(', ', $vista);
			}

			$sql = "UPDATE vistas_afiliados SET estatus = 0 WHERE idAfiliado {$key_where_afil} ({$afiliado}) AND vista {$key_where_vist} ({$vista});";
			
			$statement = $con->prepare($sql);
			// $statement->bindParam(':afiliado', $afiliado, PDO::PARAM_STR);
			// $statement->bindParam(':vista', $vista, PDO::PARAM_STR);
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount(),"params" =>[[$key_where_afil,$afiliado],[$key_where_vist,$vista]], "sql"=>$sql];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	public function vistas_registradas(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = 
			"SELECT * FROM `vistas` WHERE estatus = 1;";
			
			$statement = $con->prepare($sql);
			$statement->execute();


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	public function registrar_vista($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = 
			"INSERT INTO `vistas` (`nombre`, `directorio`, `descripcion`) VALUES 
				(:nombre_vista, :directorio, :descripcion_vista)";
			
			$statement = $con->prepare($sql);
			$statement->execute($data);


			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}

	public function actualizar_vista($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = 
			"UPDATE `vistas` SET `nombre` = :nombre_vista, `directorio` = :directorio, `descripcion` = :descripcion_vista , `estatus` = :check_active_vist WHERE `idVista` = :editar_vista_i";
			
			$statement = $con->prepare($sql);
			$statement->execute($data);

			if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				// $response = ["estatus"=>"ok", "data"=>$correo];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}

		$conexion = null;
		$con = null;
	
		return $response;	
	}


	// funcion de modelo afiliados
	public function consultar_afiliados(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con["conexion"];
			$sql = "SELECT afil.*, prosp.nombre, prosp.aPaterno, prosp.amaterno FROM afiliados_conacon afil 
					JOIN a_prospectos prosp ON prosp.idAsistente = afil.id_prospecto;";
			$stmt = $con->prepare($sql);

			$stmt->execute();

			if($stmt->errorInfo()[0] == 00000){
				$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$ndat = array_reduce($datos, function($acc, $item){
					unset($item['contrasenia']);
					array_push($acc, $item);
					return $acc;
				},[]);
				$response = ["estatus"=>"ok", "data"=>$ndat];
			}else{
				$response = ["estatus"=>"error", "info"=>$stmt->errorInfo(), "sql"=>$sql];
			}
		}else{
			$response = ["estatus"=>"error","info"=>"error de conexion"];
		}
		$conexion = null;
		$con = null;
		return $response;	
	}

	// funciones con modelo de pagos
	public function vistas_conceptos($tipo, $referencia){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$tipos = ['concepto', 'categoria'];
			if(in_array($tipo, $tipos)){
				$con = $con["conexion"];
				$campo = ($tipo == 'concepto') ? 'id_concepto' : 'categoria_concepto';
				$sql = "SELECT * FROM vistas vist 
						WHERE vist.{$campo} = :referencia;";

				$stmt = $con->prepare($sql); 
				$stmt->bindParam(':referencia', $referencia);
				$stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$response = ["estatus"=>"ok", "data"=>$datos];
				}else{
					$response = ["estatus"=>"error", "info"=>$stmt->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error", "info"=>"tipo de consulta no permitida"];
			}
		}else{
			$response = ["estatus"=>"error","info"=>"error de conexion"];
		}
		$conexion = null;
		$con = null;
		return $response;	
	}
}
