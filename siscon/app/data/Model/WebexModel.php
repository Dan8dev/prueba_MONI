<?php 
date_default_timezone_set('America/Mexico_City');
	require_once 'conexion.php';

    class Webex{

        public function obtener_sesion_webexlogo($idsesion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT id_sesion, contrasena_sesion
						FROM accesos_sesion_webex
                        WHERE id=:id_sesion;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":id_sesion", $idsesion);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function obtener_sesion_webexota($idsesion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ses.*, cls.fecha_hora_clase as fecha_clase, gens.idCarrera FROM `accesos_sesion_webex` ses
					JOIN clases cls ON cls.idClase = ses.id_clase
					JOIN a_generaciones gens ON gens.idGeneracion = cls.idGeneracion
					WHERE ses.id =:id_sesion;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":id_sesion", $idsesion);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function obtener_sesion_evento($idsesion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT ses.*, evs.idEvento FROM `accesos_sesion_webex` ses
						JOIN ev_evento evs ON evs.idEvento = ses.id_evento
						WHERE ses.id =:id_sesion;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":id_sesion", $idsesion);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

        public function registrarAsistencia($id_asistente,$id_evento,$modalidad,$fecha,$folio){
            $conexion = new Conexion();
            $con = $conexion->conectar();
            $response = [];
            
            if($con["info"] == "ok"){
                $con = $con["conexion"];
                $sql = "INSERT INTO asistentes_eventos (id_asistente, id_evento, modalidad, hora,folio)
                    VALUES (:id_asistente,:id_evento, :modalidad, :hora, :folio); ";
                            
                $statement = $con->prepare($sql);
                $statement->bindParam(":id_asistente", $id_asistente);
                $statement->bindParam(":id_evento", $id_evento);
                $statement->bindParam(":modalidad", $modalidad);
                $statement->bindParam(":hora", $fecha);
                $statement->bindParam(":folio", $folio);

                $statement->execute();
            
                if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
                }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
                }
            }
            $conexion = null;
            $con = null;
                    
            return $response;
        }

		public function registrarAsistenciayreconocimiento($id_asistente,$id_evento,$nombre_reconocimiento,$modalidad,$fecha,$folio){
            $conexion = new Conexion();
            $con = $conexion->conectar();
            $response = [];
            
            if($con["info"] == "ok"){
                $con = $con["conexion"];
                $sql = "INSERT INTO asistentes_eventos (nombre_reconocimiento,id_asistente, id_evento, modalidad, hora,folio)
                    VALUES (:nombre_reconocimiento,:id_asistente,:id_evento, :modalidad, :hora, :folio); ";
                            
                $statement = $con->prepare($sql);
				$statement->bindParam(":nombre_reconocimiento", $nombre_reconocimiento);
                $statement->bindParam(":id_asistente", $id_asistente);
                $statement->bindParam(":id_evento", $id_evento);
                $statement->bindParam(":modalidad", $modalidad);
                $statement->bindParam(":hora", $fecha);
                $statement->bindParam(":folio", $folio);

                $statement->execute();
            
                if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
                }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
                }
            }
            $conexion = null;
            $con = null;
                    
            return $response;
        }

        public function ya_tieneregistro_hoy($id_asistente,$fechainicial,$fechafinal){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT *
						FROM asistentes_eventos
                        WHERE id_asistente=:id_asistente
                        AND hora BETWEEN :fechainicial AND :fechafinal ;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":id_asistente", $id_asistente);
                $statement->bindParam(":fechainicial", $fechainicial);
                $statement->bindParam(":fechafinal", $fechafinal);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function estatus_sesion($idsesion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT estatus
						FROM accesos_sesion_webex
                        WHERE id=:id_sesion;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(":id_sesion", $idsesion);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

    }
