<?php 
date_default_timezone_set("America/Mexico_City");
	class AccesosAlumnosInstituciones{
		var $tipos_interno = [
			['id_interno'=>19, 'nombre'=>'IESM',         'id_ext' => 1],
			['id_interno'=>20, 'nombre'=>'UDC',          'id_ext' => 6],
			['id_interno'=>21, 'nombre'=>'IESM_ESP',     'id_ext' => 7],
			['id_interno'=>22, 'nombre'=>'REF',          'id_ext' => 3],
			['id_interno'=>23, 'nombre'=>'EX-ALUMNO',    'id_ext' => 2],
			['id_interno'=>24, 'nombre'=>'NUEVO-INGRESO','id_ext' => 8],
			['id_interno'=>25, 'nombre'=>'PUBLICO-GENERAL', 'id_ext' =>9]
		];
		var $internos = [19, 20, 21, 22, 23, 24, 25];

		public function validar_acceso_institucion($usuario, $pwd){ #Consultar alumno por id
			$DECRYPT_PASS = "SistemasPUE21";
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				// echo getcwd();
				// die();
				// require_once('../../Model/acceso/keys.php');
				$check_mail = $con->query("SELECT * FROM afiliados_conacon WHERE email = '".$usuario."';")->fetch();
				if($check_mail){
					$check_institu = $con->query("SELECT * FROM instituciones_afiliados WHERE id_prospecto = '".$check_mail['id_prospecto']."';")->fetch();
					if(!$check_institu){
						$en_scae = $this->ConsultarCorreo_pagosSCAE($usuario);
						if($en_scae['data']){
							$idTipo_interno = array_search(intval($en_scae['data']['IDTipoAsistente']), array_column($this->tipos_interno, 'id_ext'));
							$con->query("INSERT INTO instituciones_afiliados (id_prospecto, id_institucion, fecha_asignacion) VALUES ({$check_mail['id_prospecto']}, {$this->tipos_interno[$idTipo_interno]['id_interno']}, NOW());");
						}
					}
				}
				if($check_mail){
					$sql = "SELECT afil.*, prosp.nombre, prosp.apaterno, prosp.amaterno, alum_i.id_institucion, inst.nombre as nombre_institucion, inst.panel_url, inst.color_n1 FROM afiliados_conacon afil 
					JOIN a_prospectos prosp ON prosp.idAsistente = afil.id_prospecto
					JOIN instituciones_afiliados alum_i ON alum_i.id_prospecto = prosp.idAsistente
					JOIN a_instituciones inst ON inst.id_institucion = alum_i.id_institucion
					WHERE afil.email = :usuario AND afil.contrasenia = AES_ENCRYPT(:usr_pass, '{$DECRYPT_PASS}');";

					$statement = $con->prepare($sql);
					$statement->bindParam(':usuario', $usuario);
					$statement->bindParam(':usr_pass', $pwd);

					$statement->execute();			 
					
					if($statement->errorInfo()[0] == "00000"){
						if($statement->rowCount() > 0){
							$info = $statement->fetch(PDO::FETCH_ASSOC);
							if($info){
								unset($info['contrasenia']);	
								$info['instituciones'] = $con->query("SELECT inal.*, inst.nombre as institucion_n, inst.panel_url FROM instituciones_afiliados inal
								JOIN a_instituciones inst ON inst.id_institucion = inal.id_institucion WHERE inal.id_prospecto = ".$info['id_prospecto'])->fetchAll(PDO::FETCH_ASSOC);
							}
							foreach($info['instituciones'] as $key_ins => $ins_val){
								if(in_array($ins_val['id_institucion'], $this->internos)){
									$actualiza_tipo = $this->ConsultarCorreo_pagosSCAE($info['email']);
									if($actualiza_tipo['data']){
										$real_local = $this->tipos_interno[array_search(intval($actualiza_tipo['data']['IDTipoAsistente']), array_column($this->tipos_interno, 'id_ext'))]['id_interno'];
										if($real_local != $ins_val['id_institucion']){
											$this->actualizar_tipo_actual($ins_val['idInstAfiliado'], $real_local);
											$info['instituciones'][$key_ins]['id_institucion'] = strval($real_local);
										}
									}
								}
							}
							$response = ["estatus"=>"ok", "data"=>[$info]];
						}else{
							$response = ["estatus"=>"error", "info"=>'Usuario o contraseña incorrecto'];
						}
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}
				}else{
					//$response = ["estatus"=>"error", "info"=>"El usuario no existe"];
					$scae_con = $this->ConsultarCorreo_pagosSCAE($usuario);
					
					$response = ["estatus"=>"here", "info"=>$scae_con];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}

		public function actualizar_tipo_actual($idregistro, $cambio){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "UPDATE instituciones_afiliados SET id_institucion = :cambio WHERE idInstAfiliado = :idregistro;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':cambio', $cambio);
			$statement->bindParam(':idregistro', $idregistro);
			$statement->execute();

			return $statement->rowCount();
		}

		public function ConsultarCorreo_pagosSCAE($correo){
			$resp = [];
			$conexion = new Conexion();
			$con = $conexion->ScaeConect();

			if($con){
				$sql = "SELECT ca.FechaRegistro as fechadepago, al.Nombres as nombrealumno, al.ApellidoPaterno as paternoalumno, al.ApellidoMaterno as maternoalumno, al.Matricula,al.Correo, 						al.Genero,al.TelefonoParticular,al.Celular,al.BanderaCarrera as Carrera,al.BanderaGeneracion as Generacion, ca.IDTipoAsistente, c.Congreso,c.NumeroCongreso FROM 					`CongresosAsistentes` as ca JOIN CongresosPagosTerminados AS cpt on cpt.IDCongresoAsistente= ca.IDCongresoAsistente JOIN Alumnos as al on al.IDAlumno=ca.IDalumno JOIN Congresos as c on c.IDCongreso=ca.IDCongreso WHERE al.Correo = :correo AND c.IDCongreso = 12;";
				$stmt = $con->prepare($sql);
				$stmt->bindParam(':correo', $correo);

				$stmt->execute();
					
				if($stmt->errorInfo()[0] == '00000'){
						$resp = ['estatus'=>'ok','data'=>$stmt->fetch(PDO::FETCH_ASSOC)];
					
				}else{
					$resp = ['estatus'=>'error','info'=>'Error en consulta'];
				}
			}else{
				$resp = ['estatus'=>'error','info'=>'Error en conexion con el servidor'];
			}
			$con = null;
			return $resp;
		}

		public function RegistrarAlumnoInstitucion($data, $institucion){
			$DECRYPT_PASS = "SistemasPUE21";
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				

				$sql = "INSERT INTO `a_prospectos`(`idEvento`, `idCarrera`, `nombre`, `aPaterno`, `aMaterno`, `Genero`, `correo`, `telefono`, `fecha_registro`, `tipoPago`) 
				VALUES (:evento, :carrera, :nombre, :paterno, :materno, :genero, :correo, :telefono, :registro, :tipo_moneda_prospecto);";

				$statement = $con->prepare($sql);
				$statement->execute($data);			 
				
				if($statement->errorInfo()[0] == "00000"){
					$lstId = $con->lastInsertId();

					// require_once('../../Model/acceso/keys.php');

					$data['id_institucion'] = $institucion;
					$data['id_prospecto'] = $lstId;
					unset($data["evento"]);
					unset($data["carrera"]);
					unset($data["nombre"]);
					unset($data["paterno"]);
					unset($data["materno"]);
					unset($data["genero"]);
					$sql_afil = "INSERT INTO `afiliados_conacon` (`id_prospecto`, `email`, `contrasenia`, `celular`, `fecha_registro`) VALUES 
									(:id_prospecto, :correo, AES_ENCRYPT('123', '".$DECRYPT_PASS."'), :telefono, :registro);

								INSERT INTO `instituciones_afiliados` (id_prospecto, id_institucion, fecha_asignacion) VALUES 
									(:id_prospecto, :id_institucion, :registro);";
					$stmt = $con->prepare($sql_afil);
					$stmt->execute($data);

					if($stmt->errorInfo()[0] == "00000"){
						$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
					}else{
						$response = ['estatus'=>'error','info'=>'Error al registrar el Alumno'];
					}

				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}
		
		public function getInfo_Institucion($institucion){
			
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$institucion = intval($institucion);
			return $con->query("SELECT * FROM `a_instituciones` WHERE id_institucion = $institucion")->fetch(PDO::FETCH_ASSOC);	
		}

		public function buscar_alumno($nombre, $id = null){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT prosp.*, inst.nombre as nombre_institucion, inst.id_institucion, afil.foto FROM afiliados_conacon afil 
				JOIN a_prospectos prosp ON prosp.idAsistente = afil.id_prospecto
				JOIN instituciones_afiliados inst_a ON inst_a.id_prospecto = prosp.idAsistente
				JOIN a_instituciones inst ON inst.id_institucion = inst_a.id_institucion";
				if($id === null){
					$sql .= " WHERE CONCAT(prosp.nombre,prosp.apaterno,prosp.amaterno) LIKE :nombre";
				}else{
					$sql .= " WHERE prosp.idAsistente = :id";
				}
				$sql .= " GROUP BY prosp.idAsistente";
				$statement = $con->prepare($sql);
				if($id === null){
					$pattern = '%'.$nombre.'%';
					$statement->bindParam(':nombre', $pattern);
				}else{
					$statement->bindParam(':id', $id);
				}

				$statement->execute();			 
				
				if($statement->errorInfo()[0] == "00000"){
					if($statement->rowCount() > 0){
						$info = $statement->fetchAll(PDO::FETCH_ASSOC);
						
						$response = ["estatus"=>"ok", "data"=>$info];
					}else{
						$response = ["estatus"=>"error", "info"=>'Sin resultados', 'data'=>[]];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}

		public function buscar_alumno_afiliado($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT prosp.*, afil.foto 
				FROM afiliados_conacon afil 
				JOIN a_prospectos prosp ON prosp.idAsistente = afil.id_prospecto 
				WHERE afil.id_prospecto = :id";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(':id', $id);

				$statement->execute();			 
				
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}

		function crear_registro_institucion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "INSERT INTO instituciones_afiliados (id_prospecto, id_institucion, fecha_asignacion) VALUES (:prospecto, :institucion, :fecha)";
				$statement = $con->prepare($sql);
				$statement->bindParam(':prospecto', $data["prospecto"]);
				$statement->bindParam(':institucion', $data["institucion"]);
				$f = date('Y-m-d H:i:s');
				$statement->bindParam(':fecha', $f);

				$statement->execute();			 
				
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error','info'=>'Error al asignar a la institución'];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}

		function crear_registro_afiliado($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "INSERT INTO `afiliados_conacon`(`id_prospecto`, `email`, `celular`, `grado_academico`, `pais_nacimiento`, `cedulap`) VALUES
				(:prospecto, :email, :celular, :grado_academico, :pais_nacimiento, :cedulap)";
				$statement = $con->prepare($sql);

				$statement->execute($data);
				
				if($statement->errorInfo()[0] == "00000"){
					$stm = $con->query("UPDATE afiliados_conacon SET contrasenia = AES_ENCRYPT('12345','SistemasPUE21') WHERE id_afiliado = ".$con->lastInsertId().";");
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error','info'=>'Error al asignar a la institución'];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}

		function obtenerDatosAlumno($id){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT aPros.nombre AS nombres, aPros.aPaterno AS apellidoPaterno, aPros.aMaterno AS apellidoMaterno, AfiCon.foto, gen.nombre AS ngeneracion, gen.idCarrera AS idCarrera, carr.nombre AS nombreCarrera
				FROM afiliados_conacon AfiCon
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = AfiCon.id_prospecto
				INNER JOIN alumnos_generaciones alumGen ON alumGen.idalumno = AfiCon.id_prospecto
				INNER JOIN a_generaciones gen ON gen.idGeneracion = alumGen.idgeneracion
				INNER JOIN a_carreras carr ON carr.idCarrera = gen.idCarrera
				WHERE AfiCon.id_prospecto = :id;";

				$statement = $con->prepare($sql);	
				$statement->bindParam(":id", $id);		 
				$statement->execute();
				//echo $sql;			 

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

		function expedientes($idAlumno){
			$conexion = New Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT carrer.idCarrera AS idCarrera, carrer.nombre AS nombreCarrera, inst.nombre AS nombreInstitucion, inst.id_institucion AS idInstitucion 
				FROM alumnos_generaciones alumGen
				INNER JOIN a_generaciones gen ON gen.idGeneracion = alumGen.idgeneracion
				INNER JOIN a_carreras carrer ON carrer.idCarrera = gen.idCarrera
				INNER JOIN a_instituciones inst ON inst.id_institucion = carrer.idInstitucion 
				WHERE carrer.area='Ciencias Naturales y de la Salud' AND alumGen.idalumno = :id";

				$statement = $con->prepare($sql);
				$statement->bindParam(':id', $idAlumno);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		function concentrado_alumnos_institucion(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT prosp.*, inst.nombre as nombre_institucion, inst.id_institucion, inst.color_n1,
				AES_DECRYPT(afil.contrasenia,'SistemasPUE21') as contrasenia, afil.foto
				FROM afiliados_conacon afil 
				JOIN a_prospectos prosp ON prosp.idAsistente = afil.id_prospecto
				JOIN instituciones_afiliados inst_a ON inst_a.id_prospecto = prosp.idAsistente
				JOIN a_instituciones inst ON inst.id_institucion = inst_a.id_institucion";
				$statement = $con->prepare($sql);
				$statement->execute();			 
				
				if($statement->errorInfo()[0] == "00000"){
					if($statement->rowCount() > 0){
						$info = $statement->fetchAll(PDO::FETCH_ASSOC);
						$response = ["estatus"=>"ok", "data"=>$info];
					}else{
						$response = ["estatus"=>"error", "info"=>'Sin resultados'];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}

		function asistentes_scae(){
			$resp = ['saludos'];
			$conexion = new Conexion();
			$con = $conexion->ScaeConect();
			$con -> exec('SET CHARACTER SET utf8');
			if($con){
				$sql = "SELECT ca.FechaRegistro as fechadepago, al.Nombres as nombrealumno, al.ApellidoPaterno as paternoalumno, al.ApellidoMaterno as maternoalumno, al.Matricula,al.Correo, al.IDAlumno, al.Genero,al.TelefonoParticular,al.Celular,al.BanderaCarrera as Carrera,al.BanderaGeneracion as Generacion, ca.IDTipoAsistente, c.Congreso,c.NumeroCongreso , ta.Asistente as nom_tipo_asistente
				FROM `CongresosAsistentes` as ca 
				JOIN CongresosPagosTerminados AS cpt on cpt.IDCongresoAsistente= ca.IDCongresoAsistente 
				JOIN Alumnos as al on al.IDAlumno=ca.IDalumno 
				JOIN Congresos as c on c.IDCongreso=ca.IDCongreso 
				JOIN TiposAsistentes as ta ON ta.IDTipoAsistente = ca.IDTipoAsistente
				WHERE c.IDCongreso = 12;";
				$stmt = $con->prepare($sql);

				$stmt->execute();
					
				if($stmt->errorInfo()[0] == '00000'){
					$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
					
					$resp = ['estatus'=>'ok','data'=>$data];
				}else{
					$resp = ['estatus'=>'error','info'=>'Error en consulta'];
				}
			}else{
				$resp = ['estatus'=>'error','info'=>'Error en conexion con el servidor'];
			}
			$con = null;
			return $resp;
		}
public function validarsitienevistacursos($idProspecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT *
				FROM vistas_afiliados
				WHERE idAfiliado=:idProspecto and vista=2";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(':idProspecto', $idProspecto);

				$statement->execute();			 
				
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;	
		}

		public function insertarvistacursos($idProspecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
		  
			if($con["info"] == "ok"){
			$con = $con["conexion"];
		
			$sql = "INSERT INTO vistas_afiliados 
			(idAfiliado , vista , estatus)
			VALUES(:idalumno, 2, 1)";
				  
			$statement = $con->prepare($sql);
			$statement->bindParam(':idalumno', $idProspecto);

			$statement->execute();
	  
			  if($statement->errorInfo()[0] == '00000'){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			  }else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$concepto];
			  }
			  
			}
		  $conexion = null;
		  $con = null;  
		  
		  return $response;
		  }
	}
?>
