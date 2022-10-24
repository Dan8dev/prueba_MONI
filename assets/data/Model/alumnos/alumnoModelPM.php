<?php 
date_default_timezone_set("America/Mexico_City");
	class AlumnoPM {
		public function consultarAlumnoPM_ById($id){ #Consultar alumno por id
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				//$sql = "SELECT * FROM a_alumnos WHERE id_alumno = :alumno;";
				//$sql = "SELECT * FROM a_alumnos WHERE id_alumno = $id;";
				/*$sql = "SELECT aPros.correo, aPros.telefono, gen.idGeneracion, gen.nombre AS ngeneracion, alumGen.idalumno AS id_prospecto, 
				aPros.nombre AS nombres, aPros.aPaterno AS apellidoPaterno, aPros.aMaterno AS apellidoMaterno, afiliados_conacon.id_afiliado, gen.idCarrera, a_carreras.nombre AS nombreCarrera, a_carreras.area
				
                FROM a_carreras, afiliados_conacon, a_generaciones gen INNER JOIN alumnos_generaciones alumGen ON alumGen.idgeneracion = gen.idGeneracion 
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno 
				
                WHERE afiliados_conacon.id_afiliado = alumGen.idalumno 
				AND alumGen.idalumno = $id
				AND a_carreras.idCarrera = gen.idCarrera 
				AND a_carreras.area='Ciencias Naturales y de la Salud'";*/
				
				/*$sql = "SELECT alumnos_generaciones.idgeneracion, a_generaciones.nombre AS ngeneracion, a_generaciones.idCarrera AS idCarrera, 
				a_carreras.nombre AS nombreCarrera, afiliados_conacon.nombre AS nombres, afiliados_conacon.apaterno AS apellidoPaterno, 
				afiliados_conacon.amaterno AS apellidoMaterno 
				FROM afiliados_conacon, alumnos_generaciones, a_generaciones, a_carreras
				WHERE id_afiliado = $id AND alumnos_generaciones.idalumno = $id
				AND a_generaciones.idGeneracion = alumnos_generaciones.idgeneracion 
				AND a_carreras.idCarrera = a_generaciones.idCarrera;";luis*/

				$sql = "SELECT AfiCon.nombre AS nombres, AfiCon.apaterno AS apellidoPaterno, AfiCon.amaterno AS apellidoMaterno, gen.nombre AS ngeneracion, gen.idCarrera AS idCarrera, carr.nombre AS nombreCarrera
				FROM afiliados_conacon AfiCon
				INNER JOIN alumnos_generaciones alumGen ON alumGen.idalumno = AfiCon.id_prospecto
				INNER JOIN a_generaciones gen ON gen.idGeneracion = alumGen.idgeneracion
				INNER JOIN a_carreras carr ON carr.idCarrera = gen.idCarrera
				WHERE Aficon.id_prospecto = :id;";

				$statement = $con->prepare($sql);	
				$statement->bindParam(":id", $id);		 
				$statement->execute();
				//echo $sql;			 
				
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
				//print_r( $response );
			return $response;	
		}
		/*public function consultarAlumnos_Depositos($data){ 
			/*data = [:alumno,:fecha_i,:fecha_f]
			$conexion = new Conexion(); # invocar al objeto
			$con = $conexion->conectar(); # recibir el arreglo ['conexion'=>Obj, 'info'=>'ok'||'error']
			$response = [];

			if($con["info"] == "ok"){ # validar el info de la consulta
					#En caso de exito sobreescribe el arreglo con el objeto de conexion
				$con = $con["conexion"];
				
				$estatus_corte = 0;
				$extra = ";";
				if(isset($data['band_corte'])){
					unset($data["band_corte"]);
					$estatus_corte = 1;
				}else{
					$extra = "OR (DATE(fecha_deposito) <= :fecha_f AND corte = 0 AND id_alumno = :alumno);";
				}
				
				$sql = "SELECT * FROM tmp_depositos WHERE id_alumno = :alumno AND (DATE(fecha_deposito) >= :fecha_i AND DATE(fecha_deposito) <= :fecha_f) AND `corte` = {$estatus_corte} {$extra}"; # se almacena la consulta en un string para poder hacer debug en caso de error
				
				$statement = $con->prepare($sql); #
				$statement->execute($data);			  

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}*/
	}
?>
