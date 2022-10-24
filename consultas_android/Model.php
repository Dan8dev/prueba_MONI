<?php
	use setasign\Fpdi\Fpdi;
	use setasign\Fpdi\PdfReader; 
//Consulta de apli Android
	class ControlEscolar{
		var $retardo = 20;

		public function eventos_taller_limite($evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT limite_taller FROM `ev_evento` WHERE idEvento = :evento";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":evento", $evento['idEvento']);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "limite_taller"=>$statement->fetch(PDO::FETCH_ASSOC)];
					//var_dump($response);
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
				//SELECT e.*,(SELECT COUNT(*) FROM ev_asistente_talleres et WHERE et.id_taller = e.id_taller  AND estatus = 1) AS ocupados FROM ev_talleres e WHERE e.id_evento = :evento AND e.fecha > NOW();
				$sql = "SELECT e.*,(SELECT COUNT(*) FROM ev_asistente_talleres et WHERE et.id_taller = e.id_taller  AND estatus = 1) AS ocupados FROM ev_talleres e WHERE e.id_evento = :evento   AND e.fecha > SUBTIME(CURRENT_TIME(),'02:00:00')";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":evento", $evento['idEvento']);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "lista_talleres"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function talleres_eventos_inscritos($evento){
			//var_dump($evento);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT evas.id_taller , et.nombre_ponente, et.nombre, et.cupo, et.fecha, et.fecha, et.costo, et.salon, et.nombre_ponente,et.foto 
				FROM `ev_asistente_talleres` AS evas
				JOIN ev_talleres AS et ON et.id_taller = evas.id_taller
				INNER JOIN ev_evento as eve ON eve.idEvento = et.id_evento AND eve.idEvento = :evento
				WHERE evas.id_asistente = :idAsistente AND evas.estatus != 2 AND et.fecha > SUBTIME(CURRENT_TIME(),'02:00:00');";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":evento", $evento['idEvento']);
				$statement->bindParam(":idAsistente", $evento['idAsistente']);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "talleres_inscritos"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		
		public function ponencias_eventos($ponencia){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT e.*
				FROM ev_ponencias AS e 
				WHERE e.id_evento = :evento  AND e.fecha > SUBTIME(CURRENT_TIME(),'02:00:00')
				ORDER BY e.fecha ASC;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":evento", $ponencia['idEvento']);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){	
					$response = ["estatus"=>"ok", "ponencias"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}


		public function obtenerInfoUsuario($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT apros.*, afcon.foto as fotoAf
				FROM a_prospectos as apros
               	LEFT JOIN afiliados_conacon as afcon on afcon.id_prospecto = apros.idAsistente
				WHERE apros.idAsistente = :idAlum;";
				
				$statement = $con->prepare($sql);
				$statement->execute($data);

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

		public function ObtenerPdfCredenccial($data){

			$hex = $data['Color'];
			unset($data['Color']);
			list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");

			//var_dump($data);
			$Nombre_ins =""; 
			switch ($data['Nombre_ins']) {
				case 'IESM':
					$Nombre_ins = "Instituto de Estudios Superiores en Medicina"; 
					break;
				case 'UDC':
					$Nombre_ins = "Universidad del Conde";
					break;
				case 'IESM_ESP':
					$Nombre_ins = "Instituto de Estudios Superiores en Medicina Especialidad";
					break;
				case 'REF':
					$Nombre_ins = "Referido";
					break;
				case 'EX-ALUMNO':
					$Nombre_ins = "Ex Alumno";
					break;
				case 'NUEVO-INGRESO':
					$Nombre_ins = "Nuevo Ingreso";
					break;
				case 'PUBLICO-GENERAL':
					$Nombre_ins = "Publico General";
					break;
				default:
					$Nombre_ins = $_POST['Nombre_ins'];
					break;
			}
			
			unset($data['Nombre_ins']);

			$ce = new ControlEscolar();
	
			$usuario = $ce->obtenerInfoUsuario($data);
			
			$prospectos = utf8_decode($usuario['data']['idAsistente']);

			$img = 'default.jpg';
			$id=$prospectos;
			$name=utf8_decode($usuario['data']['nombre']);
			$app=utf8_decode($usuario['data']['aPaterno']);
			$apm=utf8_decode($usuario['data']['aMaterno']);

			

			//var_dump(file_exists("../udc/app/img/afiliados/".$usuario['data']['foto']));
			if(file_exists("../udc/app/img/afiliados/".$usuario['data']['fotoAf']) && $usuario['data']['fotoAf'] != 'doc.png'){
				$image_mime = image_type_to_mime_type(exif_imagetype("../udc/app/img/afiliados/".$usuario['data']['fotoAf']));
				$Var = explode('/',$image_mime);
				ob_start();
				// require_once('../siscon/app/data/functions/fpdf183/fpdf.php');
				// require_once('../siscon/app/data/functions/src/autoload.php');
				// require_once('../siscon/app/data/functions/phpqrcode/qrlib.php');
					
				require_once('../assets/data/functions/fpdf183/fpdf.php');
				require_once('../assets/data/functions/src/autoload.php');
				require_once('../assets/data/functions/phpqrcode/qrlib.php');
				//require_once('../siscon/app/data/functions/fpdf183/clipping.php');

				$codesDir = "Gafetes/qr/";   
            	$codeFile = $id.'.png';
            	QRcode::png($id, $codesDir.$codeFile, 'H', 5); 

				$pdf = new FPDI('P','mm', array(279.4,215.9));


				$pageCount=$pdf->setSourceFile('GafetePlantilla.pdf');
				for($i = 1; $i <= $pageCount; $i++){
					$tplIdx = $pdf->importPage($i);
					$pdf->AddPage('P',array(107,140));
					$pdf->useTemplate($tplIdx,0,0,107,140);
				
					$pdf->SetFont('Helvetica','B',13);
					$pdf->SetTextColor(26, 188, 156);
					
					$pdf->setXY(20,92);
					$pdf->MultiCell(70, 5,"$name $app $apm",0,'C',0);

					$pdf->setXY($pdf->getX()+10,$pdf->getY());
					$pdf->SetTextColor(21, 67, 96);
					$pdf->SetFont('Helvetica','B',12);
					//$pdf->MultiCell(70, 4,utf8_decode("$Nombre_ins"),0,'C',0);

					$tamano = getimagesize("../udc/app/img/afiliados/".$usuario['data']['fotoAf']);
					json_encode($tamano);
					// var_dump($tamano[0]);
					// var_dump($tamano[1]);

					$ancho = 40;
					$Largo = 40;

					$CentroX = 35;
					$CentroY = 45;

					if($tamano[0]>=$tamano[1]){
						$Proporcion = (100/$tamano[0])*$tamano[1];
						$ancho = 100;
						$largo = $Proporcion;
					}else{
						$Proporcion = (100/$tamano[1])*$tamano[0];
						$largo = 100;
						$ancho = $Proporcion;
					}

					if($ancho == 100){
						$ancho = 40;
						$largo = (40/100)*$largo;
						$CentroY = (140/2) - ($largo/2);
					}else{
						$largo = 40;
						$ancho = (40/100)*$ancho;
						$CentroX = (107/2) - ($ancho/2);
					}

					$imagen = $pdf->Image("../udc/app/img/afiliados/".$usuario['data']['fotoAf'],$CentroX,$CentroY,$ancho,$largo,$Var[1]);
					


					$pdf->Image("Gafetes/qr/".$id.'.png',70,5,30,30);
					$pdf->SetFont('Helvetica','B',9);

					$pdf->SetAutoPageBreak(false,0);
					$pdf->setXY(0,113);
					$pdf->SetFillColor($r, $g, $b);
					$pdf->MultiCell(107, 9,"",0,'C',1);

					$pdf->setXY(0,122);
					$pdf->SetFillColor(0, 0, 0);
					$pdf->MultiCell(107, 1,"",0,'C',1);
					//Vamos a INSERTAR en circulos
					
					// $pdf->StartTransform();
					// // set clipping mask
					// $pdf->StarPolygon(30, 28, 22, 90, 3, 0, 1, 'CNZ');
					// $pdf->Image('Leon.jpg', 8, 6, 44, 44, '', 'URL', '', true, 300);
					// $pdf->StopTransform();
			
				}
				$pdf->Close();
				$pdf->Output('Gafetes/'.$id.'.pdf','F');
				$data = ['estatus'=>'ok', 'url'=>'https://moni.com.mx/consultas_android/Gafetes/'.$id.'.pdf'];
			}else{
				$data = ['estatus'=>'error', 'url'=>null];
			}
			return $data;
		}

	}


?>
