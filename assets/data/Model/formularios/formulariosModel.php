<?php
	//echo getcwd() . "\n";
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/controlescolar/controlEscolarModel.php';
	require_once '../../Model/controlescolar/planEstudiosModel.php';

	//use setasign\Fpdi\Fpdi;

	require ('../../../../udc/app/data/functions/fpdf183/fpdf.php');
	require_once('../../../../udc/app/data/functions/fpdf183/autoload.php');
    date_default_timezone_set("America/Mexico_City");
	class formularios{
		public function loadFormsTable(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM forms_dim WHERE estatus = '1';"; 
				$statement = $con->prepare($sql);
                $statement->bindParam(":idform",$idFormulario);	  
				$statement->execute();			  
			}
			$conexion = null;
			$con = null;
			return $statement;
		}

		public function getFormulario($idFormulario){
            $conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM forms_dim WHERE idform = :idform;"; 

				$statement = $con->prepare($sql);
                $statement->bindParam(":idform",$idFormulario);	  
				$statement->execute();			  
					
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

		public function seccionFormulario($idFormulario){
            $conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM form_seccion WHERE idform = :idform;"; 

				$statement = $con->prepare($sql);
                $statement->bindParam(":idform",$idFormulario);	  
				$statement->execute();			  
					
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

		public function itemsSeccion($idSeccion){
            $conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM form_items WHERE idSeccion = :idseccion;"; 
				$statement = $con->prepare($sql);
                $statement->bindParam(":idseccion",$idSeccion);
				$statement->execute();			  
					
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

		public function pdfHistoriaClinica($seccions,$items,$informacionGeneral){
			$pdf = new HistoriaClinica();
			//Seccion 0 =  Datos generales.
			$pdf->AliasnbPages();
			$pdf->AddPage('P');
			$pdf->Image("../../../../pm_medicos/img/fundacionudcpdf.jpg",135,25, 60, 50,'JPG');
			//info Secciones
			$pdf->SetFont('Arial','B','8');
			$InfodePruebaGeneral = "";
			foreach($informacionGeneral  as $cabecera){
				switch($cabecera["tipo"]){
					case '0':
						$InfodePruebaGeneral = "{$InfodePruebaGeneral}{$cabecera['nombre']}:______  ";
						break;
					case '1':
						$InfodePruebaGeneral = "{$InfodePruebaGeneral}{$cabecera['nombre']}  ( ) ";
						break;
					case '2':
						$InfodePruebaGeneral = "{$InfodePruebaGeneral}{$cabecera['nombre']} (x) /";
						break;
				}
				// $InfodePruebaGeneral = $InfodePruebaGeneral.$cabecera["nombre"]."______  ";
			}
			$pdf->SetXY(10,40);
			$pdf->MultiCell(120,6,utf8_decode($InfodePruebaGeneral),1,"",0);
			$pdf->Ln(12);
			$pdf->SetXY(10,80);
			foreach($seccions as $seccion){
				$pdf->SetFont('Arial','B','10');

				//$pdf->SetFillColor(190, 190, 190);
				$pdf->SetLineWidth(0.5);
				$pdf->MultiCell(185, 6, utf8_decode($seccion["nombre"]), 0,  'L', false);
				if($seccion["idseccion"] != 0){
					if($seccion["tipo"] == "0"){
						$content = "";
						foreach($items as $element){
							if($element!=[]){
								foreach($element as $item){
									if($item["idseccion"] == $seccion["idseccion"]){
										switch($item["tipo"]){
											case '0':
												$content = "{$content}{$item['nombre']}:______  ";
												break;
											case '1':
												$content = "{$content}{$item['nombre']}  ( ) ";
												break;
											case '2':
												$content = "{$content}{$item['nombre']} (x) /";
												break;
										}
									}
								}
							}
						}
						$pdf->MultiCell(190, 6, utf8_decode(" $content"),1,'', false);	
					}else{
						$pdf->SetFont('Arial','I','8');
						$renglones = 0;
						$content = "";
						while($renglones < $seccion["tipo"]){
							$content = $content."\n";
							$renglones++;
						}
						$pdf->MultiCell(190, 6, utf8_decode($content),1,'', false);
					}
					$pdf->Ln(6);
				}
			}
			//Codigo para añadir firmas
			$pdf->Ln(18);
			$pdf->Line(30,$pdf->GetY()+6,90,$pdf->GetY()+6);
			$pdf->Ln(6);
			$pdf->SetXY(35,$pdf->GetY());
			$pdf->Cell(50, 6, utf8_decode('PACIENTE / REPRESENTANTE LEGAL'), 0, 0, 'C', false);
			
			$pdf->SetY($pdf->GetY()-24);
			$pdf->Ln(18);
			$pdf->Line(120,$pdf->GetY()+6,172,$pdf->GetY()+6);
			$pdf->Ln(6);
			$pdf->SetXY(120,$pdf->GetY());
			$pdf->Cell(50, 6, utf8_decode('MÉDICO / RESPONSABLE'), 0, 0, 'C', false);
			$pdf->Output();
		}
    }

	class HistoriaClinica extends FPDF{
				
		function Header(){
			$ancho = 200; $alto = 10;
			$this->SetFont('Arial', 'I', '20');
			$this->SetXY(125,15);
			$this->Cell($ancho, $alto, utf8_decode('HISTORIA CLÍNICA'), 0, 0, 'L', false);
			$this->Image("../../../../pm_medicos/img/logoudcpdf.jpg",12,7, 70, 30,'JPG');
			$this->SetXY(10,50);
		}

		function Footer(){
			$this->SetY(-15);
			$this->SetFont('Arial','I','8');
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}
?>
