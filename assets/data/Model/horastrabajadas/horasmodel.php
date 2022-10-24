<?php 
    date_default_timezone_set("America/Mexico_City");
	class Horas{
		function cargar_clases_y_docentes_generacion($generacion, $listar_solo = '', $id = null){
			$conexion = new Conexion(); 
			$group = '';
			switch ($listar_solo) {
				case 'maestros':
					$group = " GROUP BY mts.id ";
					break;
				case 'clases_maestro':
					if($id !== null){
						$group = " AND cls.idMaestro = ".$id." AND cls.idClase NOT IN (SELECT idclase FROM horas_trabajadas) ";
					}
					break;
			}
			$con = $conexion->conectar()['conexion']; 
			$sql = "SELECT mats.nombre, cls.titulo AS titulo_sesion, cls.idClase, cls.fecha_hora_clase, mts.nombres, mts.aPaterno, mts.aMaterno, mts.id as id_maestro FROM `clases` cls
				JOIN materias mats ON mats.id_materia = cls.idMateria
				JOIN maestros mts ON mts.id = cls.idMaestro
				JOIN a_generaciones gns ON gns.idGeneracion = cls.idGeneracion
				WHERE fecha_hora_clase IS NOT NULL AND cls.fecha_hora_clase < NOW() AND cls.idGeneracion = :generacion
				{$group}
				ORDER BY cls.fecha_hora_clase DESC;";
			
			$stmt = $con->prepare($sql);
			$stmt->bindParam(':generacion', $generacion);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function carreras_con_docentes(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar()['conexion']; 
			$sql = "SELECT * FROM `a_carreras` 
				WHERE idCarrera in (SELECT idCarrera FROM `maestros_carreras`) AND estatus = 1
				ORDER BY `a_carreras`.`nombre` ASC;";
			
			$stmt = $con->prepare($sql);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function ListHorasTrabajadas($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
                $idMaestro = "";
				$idHoras = "";
                if(isset($data["idmaestro"])){
                    $idMaestro = " WHERE idmaestro = :idmaestro";
                }
				if(isset($data["idhoras"])){
                    $idHoras = " WHERE idhoras = :idhoras";
                }
				$sql = "SELECT * FROM horas_trabajadas{$idHoras};"; 

				$statement = $con->prepare($sql); 			  
				$statement->execute($data);			  
					
				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
			return $response;
		}

        public function updateHoras($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
                $case = $data["case"];
                unset($data["case"]);
                switch($case){
                    case "insert":
                        $sql = "INSERT INTO horas_trabajadas(idmaestro, idclase, horaentrada, horasalida) 
                                VALUES (:select_maestro_gen, :select_clase_id, :inp_hora_ent, :inp_hora_sal);";
                        break;
                    case "update":
                        $sql = "UPDATE horas_trabajadas SET idmaestro = idmaestro , idclase = :idclase, horaentrada = :horaentrada, horasalida = :horasalida 
                            WHERE idhoras = :idhoras;";
                        break;
                    case "delete":
                        $sql ="DELETE FROM horas_trabajadas WHERE idhoras = :idhoras;";
                        break;
                }

				$statement = $con->prepare($sql); 			  
				$statement->execute($data);			  
					
				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
			return $response;
		}

        public function CalcularHoras($data){
            $conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){
                $con = $con["conexion"];
                $sql = "SELECT TIMEDIFF(ht.horasalida, ht.horaentrada) AS tiempotrabajado 
                    FROM horas_trabajadas as ht 
                    WHERE ht.idhoras = :idhoras;";
                
				$statement = $con->prepare($sql); 			  
				$statement->execute($data);

                if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo()];
				}
            }

            $conexion = null;
			$con = null;
			return $response;
        }
        
		public function ConsultaHorasDocTable(){
            $conexion =  new Conexion();
            $con = $conexion->conectar();
            $response = [];

            if($con["info"] == "ok"){
                $con = $con["conexion"];
                $sql = "SELECT ht.*, UPPER(CONCAT(m.nombres,' ',m.aPaterno,' ',m.aMaterno)) as nombreMaestro, m.email, mat.nombre as nombreMat, TIMEDIFF(ht.horasalida, ht.horaentrada) AS tiempotrabajado, 
						cl.fecha_hora_clase, cl.titulo as titulo_clase, gn.nombre as nombre_generacion, m.cuenta_clave, ac.pago_hora
                        FROM horas_trabajadas as ht
                        LEFT JOIN maestros as m ON m.id = ht.idmaestro
                        LEFT JOIN clases AS cl ON cl.idClase = ht.idclase
                        LEFT JOIN materias AS mat ON mat.id_materia = cl.idMateria
						JOIN a_generaciones AS gn ON gn.idGeneracion = cl.idGeneracion
                        LEFT JOIN a_carreras AS ac ON ac.idCarrera = gn.idCarrera";
				$statement = $con->prepare($sql); 			  
				$statement->execute();
                // var_dump($statement);
            }
            $conexion = null;
			$con = null;
            return($statement);
        }

		public function updatepago($data){
			$conexion =  new Conexion();
            $con = $conexion->conectar();
            $response = [];

            if($con["info"] == "ok"){
                $con = $con["conexion"];
                $sql = "UPDATE horas_trabajadas 
						SET estatus = '1', monto = :cantidadPago, fecha = NOW(), archivo = :nName , registro_pago = :registra
						WHERE idhoras = :idhoras;";
				$statement = $con->prepare($sql); 			  
				$statement->execute($data);

                // var_dump($statement);
				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo()];
				}
				
            }
            $conexion = null;
			$con = null;
            return($response);
		}

		function horas_ya_registradas($docente, $clase){
			$conexion =  new Conexion();
            $con = $conexion->conectar()["conexion"];

			$con = $con;
			$sql = "SELECT * FROM horas_trabajadas WHERE idmaestro = :maestro AND idclase = :clase;";
			$statement = $con->prepare($sql);

			$statement->bindParam(':maestro', $docente);
			$statement->bindParam(':clase', $clase);

			$statement->execute();
				
            return $statement->fetch(PDO::FETCH_ASSOC);
		}
    }
?>
