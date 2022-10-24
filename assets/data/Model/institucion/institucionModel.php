<?php 
date_default_timezone_set("America/Mexico_City");
	class Institucion {

		public function consultarTodoInstituciones($estat = 1, $acuerdo = 1){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT inst.*, 
						(SELECT idDatos FROM instituciones_datos WHERE id_institucion = inst.id_institucion) as id_responsable FROM `a_instituciones` inst 
						WHERE inst.estatus = :estat AND inst.acuerdo = :acuerdo ORDER BY inst.nombre;"; 

				$statement = $con->prepare($sql); 
				$statement->bindParam(':estat', $estat);			  
				$statement->bindParam(':acuerdo', $acuerdo);			  
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == 0000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}
		
		function busquedaClinicaTotal($pal){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];

			$sql = "SELECT inst.id_institucion, inst.nombre, inst.preautorizacion, inst.estatus, inst.fecha_registro, inst.comentario, dats.email_contacto, dats.responsable_tel
				FROM a_instituciones inst
				JOIN instituciones_datos dats ON dats.id_institucion = inst.id_institucion
				JOIN afiliados_conacon afc ON afc.clinicaResponsable = inst.id_institucion 
				WHERE inst.estatus = 1 AND inst.fundacion = 1 AND inst.acuerdo = 0 AND afc.estatus = 10 AND REPLACE(UPPER(inst.nombre),' ', '') REGEXP :expresion
				ORDER BY inst.preautorizacion ASC, inst.fecha_registro DESC;";
				//$sql = "SELECT * FROM a_prospectos WHERE REPLACE(UPPER(CONCAT(aMaterno, aPaterno, nombre)),' ', '') REGEXP :expresion";
			$stmt = $con->prepare($sql);
			$val = ''.$pal.'+';
  			$stmt->bindParam(':expresion', $val);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		function busqueda_clinica($palabra){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];

			$sql = "SELECT * FROM a_instituciones WHERE 
				REPLACE(UPPER(nombre),' ', '') REGEXP :expresion
			";
			// $sql = "SELECT * FROM a_prospectos WHERE REPLACE(UPPER(CONCAT(aMaterno, aPaterno, nombre)),' ', '') REGEXP :expresion";
			$stmt = $con->prepare($sql);
			$val = ''.$palabra.'+';
			$stmt->bindParam(':expresion', $val);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		function busqueda_clinicaCompleta($palabra){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];

			$sql = "SELECT * FROM a_instituciones WHERE 
				LTRIM(nombre) = LTRIM(:expresion);";
			//$sql = "SELECT * FROM a_prospectos WHERE REPLACE(UPPER(CONCAT(aMaterno, aPaterno, nombre)),' ', '') REGEXP :expresion";
			$stmt = $con->prepare($sql);
			$stmt->bindParam(':expresion', $palabra);
			$stmt->execute();
			// var_dump($sql);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function ActualizarEstatus($idAfiliado){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "UPDATE `afiliados_conacon` SET `estatus` = 10 WHERE id_afiliado = $idAfiliado;"; 

				$statement = $con->prepare($sql); 			  
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == 0000){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

		public function AsociacionProspecto($idusuario,$idAsociacion){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon as ac
				JOIN a_prospectos as ap on ap.idAsistente=ac.id_prospecto
				SET ap.idAsociacion = :id_Asociacion, ac.clinicaResponsable = :id_Asociacion
				WHERE id_afiliado = :id_afiliado;";

				$statement = $con->prepare($sql);

				$statement->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);
				$statement->bindParam(":id_Asociacion", $idAsociacion, PDO::PARAM_INT);
				
				

				$statement->execute();
			}
		}


		public function ActualizarAsociacion($idAfiliado, $idAsociacion){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "UPDATE `a_prospectos` SET `idAsociacion` = idAsociacion WHERE id_afiliado = $idAfiliado;"; 

				$statement = $con->prepare($sql); 			  
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == 0000){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}



		public function insertInst($post){
			
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$data = [
					'namecl'=> $post['name_clinica_cl'],
					'panelurl'=>'',
					'colorn1'=>'',
					'colorn2'=>'',
					'fechaR'=>date('Y-m-d'),
					'statusR'=> 1,
					'fundac'=> 1
				];
			
				$sql1 = "INSERT INTO `a_instituciones`(`nombre`, `panel_url`, `color_n1`, `color_n2`, `fecha_registro`, `estatus`, `fundacion`) 
				VALUES (:namecl,:panelurl,:colorn1,:colorn2,:fechaR,:statusR,:fundac)";

				$statement = $con->prepare($sql1); 			  
				$statement->execute($data);			  
			
				if($statement->errorInfo()[0] == 0000){
					$idIns = $con->lastInsertId();
					
					unset($post['name_clinica_cl']);

					//Validar envio de algun check box
					if(!isset($post['flexRadioDefaultPacientes1'])){
						$post['flexRadioDefaultPacientes1']="";
					}
					if(!isset($post['flexRadioDefaultPacientes2'])){
						$post['flexRadioDefaultPacientes2']="";
					}
					if(!isset($post['flexRadioDefaultPacientes3'])){
						$post['flexRadioDefaultPacientes3']="";
					}

					$post['flexRadioDefaultPacientes'] = $post['flexRadioDefaultPacientes1'].','.$post['flexRadioDefaultPacientes2'].','.$post['flexRadioDefaultPacientes3']; 

					unset($post['flexRadioDefaultPacientes1']);
					unset($post['flexRadioDefaultPacientes2']);
					unset($post['flexRadioDefaultPacientes3']);

					$post['idInst'] = $idIns;
					
					if(!isset($post["estado_cl"])){
						$post["estado_cl"]=0;
					}

					$sql = "INSERT INTO `instituciones_datos` (`id_institucion`, `responsable_nom`, `responsable_paterno`, `responsable_materno`,`responsable_email`,`responsable_tel`,`responsable_curp`, `email_contacto`, `telefono_contacto`,`pais`,`estado`, `direccion`, `ciudad`, `tipo_atencion`, `evento`, `capacidad_atencion`, `cant_last_year`, `cant_pac_last_month`, `reciben_pac`, `otro_tipo`, `hab_indiv`, `cant_hab_indv`, `hab_compart`, `cant_hab_compr`, `promedio_hab_comp`, `are_descanso`, `cant_arera_desc`, `sesion_terape_group`, `sesion_terap_single`, `medico_gral`, `servicios_psiq`, `serv_psic`, `serv_enferm`, `duracion_indiv_person`, `periodo_min_de_trata`, `min_tiempo_tratam_meses`, `maximo_tratam`, `max_tiempo_tratam_meses`, `apartados_ext`, `meses_apartados_extt`, `visita_familiar`) 
						VALUES (:idInst,:name_cl,:paterno_cl,:materno_cl,:emailResp,:telefonoResp,:Curp,:email_cl,:telefono_cl,:pais_cl,:estado_cl,:direccion_cl,:ciudad_cl,:flexRadioDefault,:nombre_clave_destino,:capacidad_cl,:pacientes12_cl,:pacientesMes_cl,:flexRadioDefaultPacientes,:otroTipo,:flexRadioDefaultIndividuales,:numeroHabitacionesIndividuales,:flexRadioDefaultCompartidas,:numeroHabitacionesCompartidas,:promedioPersonasHabitacion,:flexRadioDefaultAreasDescanso,:numeroareasDescanso,:flexRadioDefaultTerapiasGrupo,:flexRadioDefaultTerapiasIndividuales,:flexRadioDefaultMedicina,:flexRadioDefaultPsiquiatria, :flexRadioDefaultPsicologia,:flexRadioDefaultEnfermeria,:flexRadioDefaultTratamiento,:flexRadioDefaultPeriodoMin,:tiempoduraMin,:flexRadioDefaultPeriodoMax,
						        :tiempoduraMax,:flexRadioDefaultExt,:cantmeses,:flexRadioDefaultVisit)";
					$statement = $con->prepare($sql); 
					$statement->execute($post);			  
					
					
					if($statement->errorInfo()[0] == 0000){
						$response = ["estatus"=>"ok", "data"=>$idIns];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}


				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql1];
				}

				
			}

			

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}
		
		function validar_institucion_existente($nombre, $correo){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT inst.* FROM `a_instituciones` inst
						JOIN instituciones_datos dats ON dats.id_institucion = inst.id_institucion
						WHERE inst.nombre = :nombre OR dats.responsable_email = :correo OR dats.email_contacto = :correo;"; 

				$statement = $con->prepare($sql);
				$statement->bindParam(':nombre', $nombre);
				$statement->bindParam(':correo', $correo); 
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == 0000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
				#retornar el arreglo construido
			return $response;
		}

	}
?>
