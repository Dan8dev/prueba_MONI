<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 5 ) ){
    header("Location: ../index.php");
    die();
}else{
    $usuario = $_SESSION["usuario"];
}
use setasign\Fpdi\Fpdi;

require_once '../assets/data/Model/conexion/conexion.php';
require_once '../assets/data/Model/controlescolar/controlEscolarModel.php';
require ('../assets/data/functions/fpdf183/fpdf.php');
require_once('../controlescolar/fpdf183/autoload.php');

$listAsist = New ControlEscolar();
$NombreAlumno = [];
$fechaAcceso = [];
$id = $_GET['id_taller'];
$datosGen = $listAsist->datosPDFAsistenciasTalleres($id)['data'];

foreach($datosGen as $campo => $value){
    $nombreTaller = utf8_decode($datosGen[$campo]['nombreT']);
    //$nombreEvento = utf8_decode($datosGen[$campo]['titulo']);
    $idEvento = utf8_decode($datosGen[$campo]['idEvento']);
    $fecha = $datosGen[$campo]['fecha'];
    $nombreAlumno[$campo] = utf8_decode($datosGen[$campo]['nombre']);
    $fechaAcceso[$campo] = $datosGen[$campo]['hora'];
    if($fecha==null){
        $fechaF = '--------';
    }else{
        $fechaF = date('d-m-y', strtotime($fecha));
    }
}
//var_dump(strlen($nombreTaller));
$nombreTallerFinal = $nombreTaller;
/*if(strlen($nombreTaller)>50){
    $resultado = substr($nombreTaller, 0, 50);
    $nombreTallerFinal = $resultado.'...';
}else{
    $nombreTallerFinal = $nombreTaller;
}*/

header('Content-type: application/pdf');

$ancho = 195; $alto = 10;

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', '10');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,$alto, 'LISTA DE ASISTENCIA', 1, 0, 'C', true);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,30,' ',1,2,'L', true);
$pdf->Line(97, 20, 97, 50);
$pdf->Line(70, 20, 70, 50);
$pdf->Text(71, 25, 'TALLER: ');
$pdf->SetY(21);
$pdf->SetX(98);
$pdf->MultiCell(107, 4, $nombreTallerFinal,0);
$pdf->Ln(25);
//horizontales
$pdf->Line(70, 30, 205, 30);
$pdf->Text(71, 36, 'FECHA: ');
$pdf->Text(98, 36, $fechaF);
$pdf->Line(70, 40, 205, 40);
$pdf->Text(71, 46, 'ASISTENTES: ');
$pdf->Text(98, 46, count($datosGen));
$pdf->Image('../assets/images/eventos/'.$idEvento.'.png',25,23,25,23);

$cont = 1;
$contg = 1;

    $pdf->Write( 7, ' ' );
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', '10');
    $pdf->Cell(12,7, 'NO.', 1,0,'C', true); 
    $pdf->Cell(143,7, 'NOMBRE' ,1,0,'C', true);
    $pdf->Cell(40,7, 'ACCESO' ,1,0,'C', true);
    $pdf->Ln();
    $pdf->SetFont('Arial', '', '10');
    if(count($datosGen) > 0){
        for($i=0;$i < count($datosGen) ;$i++){
           
            $pdf->Cell(12,7, $contg++,1,0, 'C', true);
            $pdf->Cell(143,7,$nombreAlumno[$i], 1, 0, 'L', true);
            $pdf->Cell(40,7,$fechaAcceso[$i], 1 , 0, 'C', true);
            $pdf->Ln();
            $cont++;
            
            if($cont > 25){
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Cell($ancho,$alto, 'LISTA DE ASISTENCIA', 1, 0, 'C', true);
                $pdf->Ln();
                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Cell($ancho,30,' ',1,2,'L', true);

                $pdf->Line(97, 20, 97, 50);
                $pdf->Line(70, 20, 70, 50);
                $pdf->Text(71, 25, 'TALLER: ');
                $pdf->SetY(21);
                $pdf->SetX(98);
                $pdf->MultiCell(107, 4, $nombreTallerFinal,0);
                $pdf->Ln(25);
                //horizontales
                $pdf->Line(70, 30, 205, 30);
                $pdf->Text(71, 36, 'FECHA: ');
                $pdf->Text(98, 36, $fechaF);
                $pdf->Line(70, 40, 205, 40);
                $pdf->Text(71, 46, 'ASISTENTES: ');
                $pdf->Text(98, 46, count($datosGen));
                $pdf->Image('../assets/images/eventos/'.$idEvento.'.png',25,23,25,23);
                
                $pdf->Write( 7, ' ' );
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(12,7, 'NO.', 1,0,'C', true); 
                $pdf->Cell(143,7, 'NOMBRE' ,1,0,'C', true);
                $pdf->Cell(40,7, 'ACCESO' ,1,0,'C', true);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '10');
                $cont=1;
            }
        }      
    }

$pdf->Output('AsistenciaTaller.pdf','I');


?>