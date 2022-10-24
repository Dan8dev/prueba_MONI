<?php 
date_default_timezone_set('America/Mexico_City');
	require_once 'conexion.php';
    class Eventos{

        public function consultarEvento_Id($clave){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT evt.*, 
					(SELECT COUNT(*) FROM a_prospectos ea
					INNER JOIN a_marketing_atencion ma ON ma.prospecto = ea.idAsistente
					WHERE ma.tipo_atencion = 'evento' AND ea.idEvento = evt.idEvento AND ma.etapa IN (2, 0, 1)) AS numAsistentes
					FROM `ev_evento` evt WHERE evt.`idEvento` = :evento AND evt.`estatus` = 1;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":evento", $clave);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$evento = $statement->fetch(PDO::FETCH_ASSOC);
					if($evento){
						# crear un arreglo con las palabras que forman el titulo del evento
						$arStr = explode(" ", $evento["titulo"]);
						# funcion para convertir el arreglo de palabras en un arreglo con las primeras letras
						# de cada palabra
						$primL = function($item){ return strtoupper(substr($item, 0,1));};
						$arStr = array_map($primL, $arStr);
						$evento['codigo_prospectos'] = implode("", $arStr);
						$evento['next_id'] = $this->consultarUltimoAsistenteID()['data'][0]['Auto_increment'];
					}
					$response = ["estatus"=>"ok", "data"=>$evento];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}	

        public function consultarUltimoAsistenteID(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SHOW TABLE STATUS FROM `moni_prod` WHERE `name` LIKE 'a_prospectos';";
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