<?php
    class Certificaciones{

		public function EliminarFechaExpedicion($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "DELETE FROM fechas_expediciones WHERE idexpedicion = :filtroFechaId";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}
        
		public function EditarFecha($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE fechas_expediciones
					SET tipo = :TipoFirmanteEdit, fecha_expedicion = :EditarFechaExpediente, idcarrera = :idCarr, firmante = :FirmanteSeleccionadoEdit
					WHERE idexpedicion = :idFechaExpediente";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}

		public function CambiarEstatusAlumno($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			//Llevar bandera para todos los estatus
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "UPDATE alumnos_generaciones SET estatus = '3' WHERE idgeneracion = :idGen AND idalumno = :idAl;";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;

		}

        public function NuevaFecha($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			
			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "INSERT INTO fechas_expediciones
					(tipo, fecha_expedicion, idcarrera, firmante)
					VALUES(:TipoFirmante, :NuevaFechaExpediente, :idCarr, :FirmanteSeleccionado)";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
				}
			}
		$conexion = null;
		$con = null;
		return $response;
		}


        public function BuscarFechas($data){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			$datosId = ""; 
			if(isset($data["filtroFechaId"]) && $data["filtroFechaId"]>"0"){
				$datosId = "AND idexpedicion = {$data["filtroFechaId"]}"; 
				unset($data["filtroFechaId"]);
			}
            
            $tabla = $data['tabla'];
            unset($data['tabla']);

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				
				$sql = "SELECT * 
                FROM fechas_expediciones 
                WHERE idcarrera = :idCarr {$datosId}";
	
				$statement = $con->prepare($sql);
				$statement->execute($data);				
			}

			$conexion = null;
			$con = null;
           
            if($tabla == '1'){
                return $statement;
            }else{
                if($statement->errorInfo()[0] == 00000){
                    
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql',$sql];
				}
                return $response;
            }
		}

        public function buscarClasesCarrera($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response  = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT idCarrera, nombre
					FROM a_carreras
					WHERE idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11
					ORDER BY nombre";

				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql',$sql];
				}
			}
            $conexion = null;
            $con = null;
            return $response;
		}
		//:::
		public function buscarAlumnosCarrera($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response  = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT al.estatus, al.idalumno, al.idgeneracion, aCarr.idCarrera, UPPER(CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno)) AS nombre, af.id_afiliado, avagen.ciclo_actual, aGen.nombre AS nombreGen
				FROM a_carreras as aCarr
				JOIN a_generaciones AS aGen ON aGen.idCarrera = aCarr.idCarrera
				JOIN avance_generaciones AS avagen ON avagen.id_generacion =  Agen.idGeneracion
				JOIN alumnos_generaciones as al on al.idgeneracion = aGen.idGeneracion
				JOIN a_prospectos AS ap ON ap.idAsistente = al.idalumno
                JOIN afiliados_conacon AS af ON ap.idAsistente = af.id_prospecto
				WHERE aCarr.idCarrera = :idCarr;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
				if(true){
					$conexion = null;
					$con = null;
					return $statement;
				}
				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql',$sql];
				}
			}
            $conexion = null;
            $con = null;
            return $response;
		}

		public function GenerarCadenaOriginal($data){
			$response = "";
			try{
				$CadenaIpes = "20103|300478|30";
				//fecha de nacimiento provisional
				$fecha = date('Y-m-d');

				switch($data['DatosAlumno']['Genero']){
					case '0':
						$data['DatosAlumno']['Genero'] = "";
						break;
					case '1':
						$data['DatosAlumno']['Genero'] = "250";
						break;
					case '2':
						$data['DatosAlumno']['Genero'] = "251";
						break;
					default:
						$data['DatosAlumno']['Genero'] = "";
						break;
				}

				//La fecha de naciemiento se imprime aunque sea vacio
				$CadenaAlumno = "{$data['DatosAlumno']['matricula']}|{$data['DatosAlumno']['curp']}|{$data['DatosAlumno']['nombre']}|{$data['DatosAlumno']['aPaterno']}|{$data['DatosAlumno']['aMaterno']}|{$data['DatosAlumno']['Genero']}|{$data['DatosAlumno']['fnacimiento']}";
				
				$CadenaExpedicion = "5|{$data['fechaCert']}|30";
				
				switch($data['DatosAlumno']['tipo_ciclo']){
					case '1':
						$data['DatosAlumno']['tipo_ciclo'] = "93";
						break;
					case '2':
						$data['DatosAlumno']['tipo_ciclo'] = "91";
						break;
					case '3':
						$data['DatosAlumno']['tipo_ciclo'] = "260";
						break;
					default:
						$data['DatosAlumno']['tipo_ciclo'] = "";
						break;
				}

				switch($data['DatosAlumno']['tipo']){
					case '2':
						$data['DatosAlumno']['tipo'] = "91";
						break;
					// case '3':
					// 	$data['DatosAlumno']['tipo'] = "";
					// 	break;
					case '4':
						$data['DatosAlumno']['tipo'] = "81";
						break;
					case '5':
						$data['DatosAlumno']['tipo'] = "82";
						break;
					case '6':
						$data['DatosAlumno']['tipo'] = "95";
						break;
					default:
						$data['DatosAlumno']['tipo'] = "";
						break;
				}
				//Falta | 

				$CadenaCarrera = "6|10|5|{$data['DatosAlumno']['tipo']}|{$data['DatosAlumno']['clave_plan']}|{$data['DatosAlumno']['tipo_ciclo']}|{$data['DatosAlumno']['idCarrera']}";

				//Faltan todos los datos...
				$CadenaRvoe = "RVOE|{$fecha}";
				
				// calificacion | idTipoAsignatura
				$CadenaAsignatura = "";
				foreach($data['DatosMaterias'] as $Materias){
					$CadenaAsignatura .= "{$Materias['id_materia']}|{$Materias['ciclo_asignado']}|{$Materias['numero_creditos']}|";	
				}

				//Falta el calculo de todas las materias anteriores
				//Calculadora de Totales
				$promedio = 0;
				$numeroCiclos = 0;
				$creditosObtenidos = 0;
				$calificacion = 0;
				$asignadas = 0;
				$total = 0; 

				foreach($data['DatosMaterias'] as $Materias){
					if($Materias['calificacion'] != null && $Materias['calificacion'] != ""){
						$numeroCiclos = $Materias['ciclo_asignado'];
						if(intval($Materias['calificacion'])!=0){
							$calificacion += $Materias['calificacion'];
						}
						if(intval($Materias['numero_creditos'])!=0){
							$creditosObtenidos += $Materias['numero_creditos'];
						}
						$asignadas++;
						$total++;
					}
				}

				if($asignadas==0){
					$promedio = 0;
				}else{
					$promedio = number_format(((float)$calificacion/$asignadas),2);
				}
				
				$CadenaAsignaturas = "{$total}|{$asignadas}|{$promedio}|{$creditosObtenidos}|{$creditosObtenidos}|{$numeroCiclos}";			

				$CadenaResponsable = "COPM780714HVZNRR01|3";

				$cadena_original = "||3.0|5|20103|{$CadenaIpes}|{$CadenaResponsable}|{$CadenaRvoe}|{$CadenaCarrera}{$CadenaAlumno}{$CadenaExpedicion}|{$CadenaAsignaturas}{$CadenaAsignatura}|";
				
				//------------------------------------ Generacion de Sello------------------------------------ //

				$private_file = "../../../files/filesCertificaciones/72_1_Claveprivada_FIEL_COPM780714BA5_20180731_094503.key.pem";  // Ruta al archivo key con contraseña
				//$public_file = "../../../files/filesCertificaciones/72_1_copm780714ba5.cer.pem";
				// Se obtiene la clave privada con la que se va a firmar
				$private_key = openssl_get_privatekey(file_get_contents($private_file)); // $clave es la contraseña del archivo .key
				openssl_sign($cadena_original,$Firma,$private_key, OPENSSL_ALGO_SHA256);
				openssl_free_key($private_key);

				$Sello = base64_encode($Firma);

				$response = ["estatus"=>"ok", "cadena"=> $cadena_original, "sello"=>$Sello];
			}catch(Exception $e){
				$response = ["estatus"=>"error", "cadena"=> "No se pudo generar la cadena original", "info"=>$e->getMessage()];
			}
			return($response);
		}


		public function GenerarXml($data){
			//Modificacion de datos acorde al catalogo SEP IPES
			switch($data['DatosAlumno']['tipo_ciclo']){
				case '1':
					$data['DatosAlumno']['tipo_ciclo'] = "93";
					break;
				case '2':
					$data['DatosAlumno']['tipo_ciclo'] = "91";
					break;
				case '3':
					$data['DatosAlumno']['tipo_ciclo'] = "260";
					break;
				default:
					$data['DatosAlumno']['tipo_ciclo'] = "";
					break;
			}

			//tipo es igual al nivel de estudios 
			switch($data['DatosAlumno']['tipo']){
				case '2':
					$data['DatosAlumno']['tipo'] = "91";
					break;
				// case '3':
				// 	$data['DatosAlumno']['tipo'] = "";
				// 	break;
				case '4':
					$data['DatosAlumno']['tipo'] = "81";
					break;
				case '5':
					$data['DatosAlumno']['tipo'] = "82";
					break;
				case '6':
					$data['DatosAlumno']['tipo'] = "95";
					break;
				default:
					$data['DatosAlumno']['tipo'] = "";
					break;
			}
			
			//Genero del alumno se consulta desde directorio
			switch($data['DatosAlumno']['Genero']){
				case '0':
					$data['DatosAlumno']['Genero'] = "";
					break;
				case '1':
					$data['DatosAlumno']['Genero'] = "250";
					break;
				case '2':
					$data['DatosAlumno']['Genero'] = "251";
					break;
				default:
					$data['DatosAlumno']['Genero'] = "";
					break;
			}

			//Asignacion de letreros para los datos faltantes, {index, letrero}
			$NombresVariables = [
				"nombre"=>"Nombre del Alumno",
				"aPaterno"=>"Apellido Paterno del alumno",
				"aMaterno"=>"Apellido Materno del alumno",
				"NombreGen"=>"Nombre de la Generación",
				"NombreCarr"=>"Nombre de la Carrera",
				"NombrePlan"=>"Nombre del Plan de Estudios",
				"fnacimiento"=>"Fecha de nacimiento",
				"curp"=>"Curp del Alumno",
				"Genero"=>"Genero del Alumno",
				"idPlan"=>"Id Plan de estudios",
				"clave_plan"=>"Clave del plan de estudios",
				"tipo_ciclo"=>"Tipo de Carrera",
				"idCarrera"=>"Id de la carrera",
				"ciclo_actual"=>"Avance de la Generación",
				"tipo"=>"Tipo de Ciclo",
				"matricula"=>"Matrícula",
				"rvoe"=>"Numero de RVOE",
				"fecha_registro_rvoe"=>"Fecha de Registro del RVOE",
				"AvisoSello"=> "Error al generar el sello"
			];
			
			//Crear arreglo con los datos faltantea para notificar en caso de que algun elemento no se pueda generar
			$response  = [];
			$datosFaltantes = [];

			//Verifica que el campo de sello se haya generado correctamente 
			if($data['sello'] == ""|| $data['sello'] == null){
				array_push($datosFaltantes, $NombresVariables['AvisoSello']);	
			}

			//Verificar datos faltantes del alumno
			foreach($data['DatosAlumno'] as $DatosAlumno => $value){
				if($value == "" || $value == null){
					array_push($datosFaltantes, $NombresVariables[$DatosAlumno]);
				}
			}

			//Verificar datos faltantes de materias
			foreach($data['DatosMaterias'] as $DatosMaterias => $value){
				if($value == "" || $value == null){
					array_push($datosFaltantes, $DatosMaterias);
				}
			}
			
			//Si se verifica que no hay datos faltantes se continua con la generación del XML
			if(count($datosFaltantes) == 0){
				try{
					$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><Dec></Dec>");
					//$track = $xml->addChild('Dec');
					$track = $xml;
					$track->addAttribute('xmlns','https://www.siged.sep.gob.mx/certificados/');
					$track->addAttribute('Version','3.0');
					$track->addAttribute('tipoCertificado','5');
					$track->addAttribute('folioControl',$data['Folio']);
					$track->addAttribute('sello',$data['sello']);
					$track->addAttribute('certificadoResponsable','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');
					$track->addAttribute('noCertificadoResponsable','00001000000411682602');
					//Este sello se forma de la cadena original formada, junto con las firmas
	
					$ServFirm = $track->addChild('ServicioFirmante');
					$ServFirm->addAttribute('idEntidad','30');
	
					$Ipes = $track->addChild('Ipes');
					$Ipes->addAttribute('idNombreInstitucion', '20103');
					$Ipes->addAttribute('idCampus', '300478');
					$Ipes->addAttribute('idEntidadFederativa', '30');
	
					$RespIpes = $Ipes->addChild('Responsable');
					$RespIpes->addAttribute('curp','COPM780714HVZNRR01');
					$RespIpes->addAttribute('nombre','MARCO ANTONIO');
					$RespIpes->addAttribute('primerApellido','CONDE');
					$RespIpes->addAttribute('segundoApellido','PÉREZ');
					$RespIpes->addAttribute('idCargo','3');
	
					$Rvoe = $track->addChild('Rvoe');
					$Rvoe->addAttribute('fechaExpedicion',$data['DatosAlumno']['fecha_registro_rvoe']);
					//Validar que la fecha de expedicion del rvoe este registrada en la base de datos
					$Rvoe->addAttribute('numero',$data['DatosAlumno']['rvoe']);
					
					//Validar que todos los datos del alumno esten completos o añadir un array
					$Carrera = $track->addChild('Carrera');
					$Carrera->addAttribute('idCarrera',$data['DatosAlumno']['idCarrera']);
					$Carrera->addAttribute('idTipoPeriodo',$data['DatosAlumno']['tipo_ciclo']);
					$Carrera->addAttribute('clavePlan',$data['DatosAlumno']['clave_plan']);
					$Carrera->addAttribute('idNivelEstudios',$data['DatosAlumno']['tipo']);
					$Carrera->addAttribute('calificacionMaxima','10');
					$Carrera->addAttribute('calificacionMinima','5');
					$Carrera->addAttribute('calificacionMinimaAprobatoria','6.00');
	
					//$track->addChild('Carrera');
					//Validar que el genero del alumno esté registrado en la base de datos.
					$Alumno = $track->addChild('Alumno');
					$Alumno->addAttribute('numeroControl',$data['DatosAlumno']['matricula']);
					$Alumno->addAttribute('curp',$data['DatosAlumno']['curp']);
					$Alumno->addAttribute('nombre',$data['DatosAlumno']['nombre']);
					$Alumno->addAttribute('primerApellido',$data['DatosAlumno']['aPaterno']);
					$Alumno->addAttribute('segundoApellido',$data['DatosAlumno']['aMaterno']);
					$Alumno->addAttribute('idGenero',$data['DatosAlumno']['Genero']);
					$Alumno->addAttribute('fechaNacimiento',$data['DatosAlumno']['fnacimiento']); 
	
					$Expedicion = $track->addChild('Expedicion');
					$Expedicion->addAttribute('idLugarExpedicion','30');
					$Expedicion->addAttribute('fecha',$data['fechaCert']);
					$Expedicion->addAttribute('idTipoCertificacion','79');
	
					//Calculadora de Totales
					$promedio = 0;
					$numeroCiclos = 0;
					$creditosObtenidos = 0;
					$calificacion = 0;
					$asignadas = 0;
					$total = 0; 
					
					foreach($data['DatosMaterias'] as $Materias){
						if($Materias['calificacion'] != null && $Materias['calificacion'] != ""){
							$numeroCiclos = $Materias['ciclo_asignado'];
							if(intval($Materias['calificacion']) != 0){
								$calificacion += $Materias['calificacion'];
							}
							if(intval($Materias['numero_creditos']) != 0){
								$creditosObtenidos += $Materias['numero_creditos'];
							}
							$asignadas++;
							$total++;
						}
					}
	
					if($asignadas == 0){
						$promedio = 0;
					}else{
						if($promedio==10){
							$promedio = number_format(((float)$calificacion/$asignadas),0);
						}else{
							$promedio = number_format(((float)$calificacion/$asignadas),2);
						}
					}
	
					$Asignaturas = $track->addChild('Asignaturas');
					$Asignaturas->addAttribute('total',$total);
					$Asignaturas->addAttribute('asignadas',$asignadas);
					$Asignaturas->addAttribute('promedio',$promedio);
					$Asignaturas->addAttribute('totalCreditos',$creditosObtenidos);
					$Asignaturas->addAttribute('creditosObtenidos',$creditosObtenidos);
					$Asignaturas->addAttribute('numeroCiclos',$numeroCiclos);
					
					//Creacion de todos los chilkd de asignaturas una por una 
					foreach($data['DatosMaterias'] as $Materias){
						if($Materias['calificacion'] != null && $Materias['calificacion'] != ""){
							if(intval($Materias['calificacion'])==10){
								$Materias['calificacion'] = number_format(((float) intval($Materias['calificacion'])),0);
							}else{
								$Materias['calificacion'] = number_format(((float) intval($Materias['calificacion'])),2);
							}
							$AsignaturaChild = $Asignaturas->addChild('Asignatura');
							$AsignaturaChild->addAttribute('idAsignatura',$Materias['id_materia']);
							$AsignaturaChild->addAttribute('ciclo',$Materias['ciclo_asignado']);
							$AsignaturaChild->addAttribute('calificacion',$Materias['calificacion']);
							$AsignaturaChild->addAttribute('idTipoAsignatura','263');
							$AsignaturaChild->addAttribute('creditos',$Materias['numero_creditos']);
							// $AsignaturaChild->addAttribute('idObservaciones','');
						}
					}
					$xml->asXML("../../../../controlescolar/archivos/certificaciones/{$data['idAlumno']}.xml");
					$response = ['estatus'=>'ok', 'data'=>'1',"faltantes" => "0"];
				}catch(Exception $e){
					$response = ['estatus'=>'error', 'info'=>$e->getMessage()];
				}
			}else{
				$response = ['estatus'=>'faltantes', 'data'=>'1', "faltantes"=> $datosFaltantes];
			}
            return $response;
		}

		public function GenerarZip($data){
			//var_dump($data,sizeof($data['BloqueId']));
			try{
				$zip = new ZipArchive();
				$date = date('Y-m-d');
				if(file_exists("../../../../controlescolar/archivos/certificaciones/ZipXml/{$date}.zip")){
					unlink("../../../../controlescolar/archivos/certificaciones/ZipXml/{$date}.zip");
				}

				$zip->open("../../../../controlescolar/archivos/certificaciones/ZipXml/{$date}.zip",ZipArchive::CREATE);
				$dir = '../../../../controlescolar/archivos/certificaciones/';
   
				$zip->addEmptyDir('');
   
				for($i=0;$i<sizeof($data['BloqueId']);$i++){
				   $zip->addFile("{$dir}{$data['BloqueId'][$i]}.xml","{$data['BloqueId'][$i]}.xml");
				   //unlink($dir.$data['BloqueId'][$i].'.xml');
				}
				//$text="Hola";
				header("Content-type: application/zip");
				header("Content-Transfer-Encoding: Binary");
				header("Content-disposition: attachment; filename ={$date}.zip");
			   
			}catch(Exception $e){
				$zip = ['status'=>'1', 'error'=>$e->getMessage()];
			}
            return $zip;
		}

		public function BuscarDatosAlumno($data){
			//unset folio de Post para match en execute
			unset($data['Folio']);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response  = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT UPPER(ap.nombre) as nombre, UPPER(ap.aPaterno) AS aPaterno, UPPER(ap.aMaterno) AS aMaterno, Agen.nombre AS NombreGen, Acarr.nombre AS NombreCarr, pe.nombre AS NombrePlan, af.fnacimiento,
				af.curp, ap.Genero, pe.id_plan_estudio AS idPlan, pe.clave_plan, pe.tipo_ciclo, Acarr.idCarrera, ap.Genero, avagen.ciclo_actual as ciclo_actual, Acarr.tipo, af.matricula, pe.rvoe, pe.fecha_registro_rvoe, pe.tipo_rvoe
				FROM a_prospectos AS ap 
				JOIN a_generaciones AS Agen ON Agen.idGeneracion = :idGeneracion
				JOIN avance_generaciones AS avagen ON avagen.id_generacion =  Agen.idGeneracion
				JOIN a_carreras AS Acarr ON Acarr.idCarrera = Agen.idCarrera
				JOIN planes_estudios AS pe ON pe.id_plan_estudio = Agen.id_plan_estudio
				JOIN afiliados_conacon AS af ON af.id_prospecto = ap.idAsistente
				WHERE ap.idAsistente = :idAlumno;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql',$sql];
				}
			}
            $conexion = null;
            $con = null;
            return $response;
		}

		public function BuscarDatosMateriasAlumno($data){
			//unset folio de Post para match en execute
			unset($data['Folio']);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response  = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				$sql = "SELECT mat.nombre AS NombreMateria, mat.id_materia, mat.calificacion_min, mat.numero_creditos, pm.ciclo_asignado, cal.calificacion
				FROM materias AS mat
				JOIN planes_materias AS pm ON pm.id_materia = mat.id_materia
				LEFT JOIN calificaciones AS cal ON cal.id_materia = mat.id_materia AND cal.numero_ciclo =  pm.ciclo_asignado AND cal.idProspecto = :idAlumno
				WHERE pm.id_plan = :idplan ORDER BY pm.ciclo_asignado ASC;";

				$statement = $con->prepare($sql);
				$statement->execute($data);
				if($statement->errorInfo()[0] == 00000){
					$response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql',$sql];
				}
			}
            $conexion = null;
            $con = null;
            return $response;
		}

    }

?>  