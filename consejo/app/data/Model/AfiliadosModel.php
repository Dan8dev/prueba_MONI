<?php 
date_default_timezone_set('America/Mexico_City');
	require_once 'conexion.php';

    class Afiliados
    {
        public function datospersonales($fnacimiento, $curp, $idusuario){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET fnacimiento=:fnacimiento, curp=:curp
                                WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
				$stmt->bindParam(":fnacimiento", $fnacimiento, PDO::PARAM_STR);
				$stmt->bindParam(":curp", $curp, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function datospersonalesap($nombre, $apaterno, $amaterno, $idusuario){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE a_prospectos
						SET nombre=:nombre, aPaterno=:aPaterno, aMaterno=:aMaterno
                                WHERE idAsistente =(SELECT id_prospecto 
													FROM afiliados_conacon
													WHERE id_afiliado=:id_afiliado);";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
				$stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
				$stmt->bindParam(":aPaterno", $apaterno, PDO::PARAM_STR);
				$stmt->bindParam(":aMaterno", $amaterno, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function contacto($pais, $estado, $ciudad, $colonia, $calle,$codigopostal, $email, $celular, $facebook, $instagram,$twitter,$idusuario){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET pais=:pais, estado=:estado, ciudad=:ciudad, colonia=:colonia, calle=:calle, cp=:cp, email=:email, celular=:celular, facebook=:facebook, instagram=:instagram, twitter=:twitter
						WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":pais", $pais, PDO::PARAM_STR);
				$stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
				$stmt->bindParam(":ciudad", $ciudad, PDO::PARAM_STR);
				$stmt->bindParam(":colonia", $colonia, PDO::PARAM_STR);
				$stmt->bindParam(":calle", $calle, PDO::PARAM_STR);
				$stmt->bindParam(":cp", $codigopostal, PDO::PARAM_STR);
				$stmt->bindParam(":email", $email, PDO::PARAM_STR);
				$stmt->bindParam(":celular", $celular, PDO::PARAM_STR);
				$stmt->bindParam(":facebook", $facebook, PDO::PARAM_STR);
				$stmt->bindParam(":instagram", $instagram, PDO::PARAM_STR);
				$stmt->bindParam(":twitter", $twitter, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function contactoap($email,$idusuario){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE a_prospectos
						SET correo=:email
						WHERE idAsistente =(SELECT id_prospecto 
													FROM afiliados_conacon
													WHERE id_afiliado=:id_afiliado);";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
				$stmt->bindParam(":email", $email, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function academico($gradoestudios, $cedulap, $idusuario, $tipoLicen){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET ugestudios=:ugestudios, cedulap=:cedulap, tipoLicenciatura = :tipolicen
						WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":ugestudios", $gradoestudios, PDO::PARAM_STR);
				$stmt->bindParam(":cedulap", $cedulap, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);
				$stmt->bindParam(":tipolicen",$tipoLicen, PDO::PARAM_STR);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function editarfoto($foto, $idusuario){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET foto=:foto
						WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":foto", $foto, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function pagosemestral($tipodemembresia, $idusuario,$finmembresia,$fechaactivacion){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET membresia=:membresia,finmembresia=:finmembresia,fechaactivacion=:fechaactivacion
						WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);
 
                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":membresia", $tipodemembresia, PDO::PARAM_INT);
				$stmt->bindParam(":finmembresia", $finmembresia, PDO::PARAM_STR);
				$stmt->bindParam(":fechaactivacion", $fechaactivacion, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function pagoanual($tipodemembresia, $idusuario,$finmembresia,$fechaactivacion){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET membresia=:membresia,finmembresia=:finmembresia,fechaactivacion=:fechaactivacion
						WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":membresia", $tipodemembresia, PDO::PARAM_INT);
				$stmt->bindParam(":finmembresia", $finmembresia, PDO::PARAM_STR);
				$stmt->bindParam(":fechaactivacion", $fechaactivacion, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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

		public function obtenerusuario($idusuario){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT  ac.id_afiliado, ac.id_prospecto, ap.idAsistente,ap.idEvento ,ap.nombre, ap.apaterno, ap.amaterno,ap.correo,ap.codigo, ac.fnacimiento, ac.curp, ac.pais, ac.estado, ac.ciudad, ac.colonia, ac.calle, ac.cp, ac.email, ac.celular, ac.facebook, ac.instagram, ac.twitter, ac.ugestudios, ac.tipoLicenciatura, ac.cedulap, ac.foto, ac.membresia, ac.finmembresia, ac.fechaactivacion, ac.fecha_registro, ac.clase, ac.matricula
							FROM afiliados_conacon as ac
							JOIN a_prospectos as ap on ap.idAsistente=ac.id_prospecto
							WHERE id_afiliado = :id_afiliado;)";

				$statement = $con->prepare($sql);

				$statement->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function obtenerusuario_cleve($clave){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT ac.* FROM afiliados_conacon as ac 
				WHERE LOWER(REPLACE(CONCAT(ac.nombre,'_',ac.apaterno,'_',ac.amaterno,'_',ac.id_afiliado), ' ', '_')) 
				like _utf8'%{$clave}%' collate utf8_general_ci;)";

				$statement = $con->prepare($sql);

				// $statement->bindParam(":clave", $clave);

				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC), 'sql'=>$sql];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}
		
		public function obtenerpaises(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT pais
							FROM paises;)";

				$statement = $con->prepare($sql);

				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_OBJ)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function obtenerestados($idpais){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT e.Estado
							FROM estados as e 
							JOIN paises as p on e.IDPais=p.IDPais
							WHERE p.Pais=:pais;)";

				$statement = $con->prepare($sql);

				$statement->bindParam(":pais", $idpais, PDO::PARAM_STR);

				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_OBJ)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function validar_acceso_asistente($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ea.*, ev.titulo, (SELECT ma.etapa FROM a_marketing_atencion ma WHERE ma.prospecto = ea.idAsistente AND ma.tipo_atencion = 'evento') AS etapa_seguimiento
				FROM a_prospectos ea
				INNER JOIN ev_evento ev ON ev.idEvento = ea.idEvento
				WHERE ea.correo = :inpCorreo AND ea.codigo = :inpPassw;";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);


				if($statement->errorInfo()[0] == '00000'){
					$infoEv = $statement->fetchAll(PDO::FETCH_ASSOC);
					$response["estatus"] = "ok";
					if(!empty($infoEv)){
						$evtM = new Eventos();
						$infoEv[0]['evento'] = $evtM->consultarEvento_Id($infoEv[0]['idEvento'])['data'];
					}
					$response["debug"]=[$sql, $data];
					$response["data"] = $infoEv;
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		public function consultar_pagos_prospectos($prospecto, $evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM ev_asistente_pago WHERE id_asistente = :prospecto AND id_evento = :evento;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto);
				$statement->bindParam(":evento", $evento);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$pagos = $statement->fetchAll(PDO::FETCH_ASSOC);
					if(!empty($pagos)){
						for ($i=0; $i < sizeof($pagos); $i++) { 
							$pagos[$i]['detalle_pago'] = json_decode($pagos[$i]['detalle_pago'], true);
						}
					}
					$response = ["estatus"=>"ok", "data"=>$pagos];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function asistente_talleres_reservados($asistente){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM ev_asistente_talleres WHERE id_asistente = :asistente ";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":asistente", $asistente);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function talleres_eventos($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT e.*,(SELECT COUNT(*) FROM ev_asistente_talleres et WHERE et.id_taller = e.id_taller) AS ocupados FROM ev_talleres e WHERE e.id_evento = :evento ";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":evento", $evento);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		public function registrarPagoEvento($evento, $persona, $detalle, $plan_pago){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `ev_asistente_pago`(`id_asistente`,`id_evento`, `plan_pago`, `detalle_pago`) VALUES (:persona, :evento, :plan_pago, :detalles)";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(":persona", $persona);
				$statement->bindParam(":evento", $evento);
				$statement->bindParam(":plan_pago", $plan_pago);
				$statement->bindParam(":detalles", $detalle);

				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>'ok', "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function registrarPagoapagos($evento, $persona, $detalle, $plan_pago){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `a_pagos`(`id_prospecto`, `id_evento`, `id_concepto`, `detalle_pago`) VALUES (:persona, :evento, :plan_pago, :detalles)";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(":persona", $persona);
				$statement->bindParam(":evento", $evento);
				$statement->bindParam(":plan_pago", $plan_pago);
				$statement->bindParam(":detalles", $detalle);

				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>'ok', "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function apartar_talleres($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO ev_asistente_talleres (id_asistente, id_taller, fecha_registro)
				VALUES (:prospecto, :taller, :fecha); ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

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

		public function registrar_exp_laboral($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `afiliados_experiencia_laboral`(`idAfiliado`, `fechaIngreso`, `fechaEgreso`, `empresa`, `puesto`, `activiadLaboral`) 
				VALUES (:afiliado, :inicio_laboral, :fin_laboral, :empresa, :puesto, :actividadLaboral); ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

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

		public function consultar_exp_laboral($data, $estatus = 1){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM `afiliados_experiencia_laboral` WHERE idAfiliado = :afiliado AND estatus = {$estatus} ORDER BY fechaIngreso DESC; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function registrar_conocimiento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `afiliados_conocimiento_compartido`(`idAfiliado`, `nombreEvento`, `fechaIngreso`, `fechaEgreso`, `funcion`, `detalles`) VALUES (:afiliado, :nombre_evento, :f_evento, :f_evento_fin, :participacion_evento, :detalles_evento); ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

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

		public function registrar_grado($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `afiliados_grado_estudios`(`idAfiliado`, `grado`, `titulo`, `cedula`) VALUES (:afiliado, :gradoestudios, :tipoLicen, :cedulap); ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

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

		public function consultar_conocimiento($data, $estatus = 1){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM `afiliados_conocimiento_compartido` WHERE idAfiliado = :afiliado AND estatus = {$estatus} ORDER BY fechaIngreso DESC; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function consultar_grado($data, $estatus = 1){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM `afiliados_grado_estudios` WHERE idAfiliado = :afiliado AND estatus = {$estatus}; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function actualizar_info_laboral($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `afiliados_experiencia_laboral` SET 
				 fechaIngreso = :inicio_laboral_edit, fechaEgreso = :fin_laboral_edit, empresa = :empresa_edit, puesto = :puesto_edit, activiadLaboral = :actividadLaboral_edit
				 WHERE idExperiencia = :item_lab; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function eliminar_laboral($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `afiliados_experiencia_laboral` SET 
				 estatus = 0 WHERE idExperiencia = :regid; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function actualizar_info_conocimiento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `afiliados_conocimiento_compartido` SET 
				  nombreEvento = :evento_nom_edit, fechaIngreso = :inicio_conocim_edit, fechaEgreso = :fin_conocim_edit, funcion = :participacion_edit, detalles  = :detalle_participacion_edit
				 WHERE idExperiencia = :item_conocim; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function actualizar_info_grado($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `afiliados_grado_estudios` SET 
				  grado = :gradoestudios_edit, titulo = :tipoLicen_edit, cedula = :cedulap_edit
				 WHERE idGrado = :item_grado; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function eliminar_conocimiento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `afiliados_conocimiento_compartido` SET 
				 estatus = 0 WHERE idExperiencia = :regid; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function eliminar_grado($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `afiliados_grado_estudios` SET 
				 estatus = 0 WHERE idGrado = :regid; ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function consultar_pago_prospecto($usuario){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT eap.plan_pago,eap.id_asistente,eap.id_evento 
				FROM ev_asistente_pago eap 
				JOIN afiliados_conacon as ac on ac.id_prospecto=eap.id_asistente 
				WHERE ac.id_afiliado=:id_afiliado;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":id_afiliado", $usuario, PDO::PARAM_INT);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function asignarmembresiagratis($evento, $persona, $detalle, $plan_pago, $finmembresia){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `a_pagos`(`id_prospecto`, `id_evento`, `id_concepto`, `detalle_pago`,`vencepago` ) VALUES (:persona, :evento, :plan_pago, :detalles, :vencepago)";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(":persona", $persona);
				$statement->bindParam(":evento", $evento);
				$statement->bindParam(":plan_pago", $plan_pago);
				$statement->bindParam(":detalles", $detalle);
				$statement->bindParam(":vencepago", $finmembresia);

				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>'ok', "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function resgistrarpagosemestral($id_prospecto ,$id_concepto ,$detalle,$finmembresia){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `a_pagos`(`id_prospecto`, `id_concepto`, `detalle_pago`,`vencepago` ) 
							VALUES (:id_prospecto, :id_concepto, :detalle_pago, :vencepago)";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(":id_prospecto", $id_prospecto);
				$statement->bindParam(":id_concepto", $id_concepto);
				$statement->bindParam(":detalle_pago", $detalle);
				$statement->bindParam(":vencepago", $finmembresia);

				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>'ok', "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function id_concepto($plan_pago){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM pagos_conceptos WHERE concepto = :concepto ";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":concepto", $plan_pago);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function fechafinmembresia($id_prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT vencepago as finmembresia, id_concepto, fechapago 
						FROM a_pagos WHERE vencepago = (SELECT MAX(vencepago) 
														FROM a_pagos 
														WHERE id_prospecto=:id_prospecto);";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":id_prospecto", $id_prospecto);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function obtener_sesion_webex(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT id_sesion, contrasena_sesion
						FROM accesos_sesion_webex;";
				
				$statement = $con->prepare($sql);

				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		
		public function obtenerNombreCredencial($id_prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "SELECT nombre_archivo FROM documentos 
				WHERE id_prospectos = :id_prospecto AND id_documento = 6";
	
				$statement = $con->prepare($sql);
				$statement->bindParam(":id_prospecto", $id_prospecto);
				$statement->execute();
	
				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC), 'sql'=>$sql];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function nombreasistente($idalumno, $idevento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ap.nombre, ap.aMaterno, ap.aPaterno, ee.titulo as evento, ap.correo as email
                        FROM a_prospectos as ap
						JOIN asistentes_eventos as ae on ae.id_asistente=ap.idAsistente
						JOIN ev_evento as ee on ee.idEvento=ae.id_evento
                        WHERE ap.idAsistente=:idAsistente AND ee.idEvento=:idEvento";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":idAsistente", $idalumno, PDO::PARAM_INT);
				$statement->bindParam(":idEvento", $idevento, PDO::PARAM_INT);

				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}

		public function obtenerusuarios(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT id_asistente, id_evento
                        FROM  asistentes_eventos";
				
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

		public function guardarreconocimiento($idasistente,$idevento,$nombre){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE asistentes_eventos
						SET nombre_reconocimiento=:nombre
                                WHERE id_asistente =:idasistente and id_evento=:idevento";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute(). La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los parámetros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un parámetro de sustitución con nombre o de signo de interrogación correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
				$stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
				$stmt->bindParam(":idasistente", $idasistente, PDO::PARAM_INT);
				$stmt->bindParam(":idevento", $idevento, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
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
	}   
