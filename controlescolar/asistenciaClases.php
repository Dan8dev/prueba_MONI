<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 3 ) ){
    header("Location: ../index.php");
    die();
}else{
    $usuario = $_SESSION["usuario"];
}
use setasign\Fpdi\Fpdi;

require_once '../assets/data/Model/conexion/conexion.php';
require_once '../assets/data/Model/controlescolar/controlEscolarModel.php';
require ('fpdf183/fpdf.php');
require_once('fpdf183/autoload.php');

$listAsist = New ControlEscolar();
$NombreAlumno = [];
$fechaAcceso = [];
$id = $_GET['id_clase'];
$datosGen = $listAsist->datosPDFAsistencias($id)['data'];
$idEvento = '';
foreach($datosGen as $campo => $value){
    $idEvento = $value['idInstitucion'];
    $Nomclase = utf8_decode($datosGen[$campo]['titulo']);
    $NombreAlumno[$campo] = utf8_decode($datosGen[$campo]['nombre']);
    $fecha = date('d-m-y', strtotime($datosGen[$campo]['fecha_hora_clase']));
    $fechaAcceso[$campo] = $datosGen[$campo]['hora'];
}
$total = $datosGen;
//var_dump($datosGen);
//var_dump("////");
//var_dump($Nomclase);
//var_dump("////");
//var_dump($fecha);

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
$pdf->Line(108, 20, 108, 50);
$pdf->Line(70, 20, 70, 50);
$pdf->Text(71, 28, 'CLASE: ');
$pdf->Text(109, 28, $Nomclase);
//horizontales
$pdf->Line(70, 30, 205, 30);
$pdf->Text(71, 36, 'FECHA: ');
$pdf->Text(109, 36, $fecha);
$pdf->Line(70, 40, 205, 40);
$pdf->Text(71, 46, 'TOTAL ASISTENTES: ');
$pdf->Text(109, 46, count($datosGen));
$pdf->Image('../assets/images/instituciones/planes_estudio/'.$idEvento.'.png',20,25);
$pdf->SetFont('Arial', '', '7');
$pdf->Text( 5, 290, utf8_decode("Página ".$pdf->PageNo()));
$pdf->SetFont('Arial', '', '10');

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
            $pdf->Cell(143,7,$NombreAlumno[$i], 1, 0, 'L', true);
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

                $pdf->Line(108, 20, 108, 50);
                $pdf->Line(70, 20, 70, 50);
                $pdf->Text(71, 28, 'CLASE: ');
                $pdf->Text(109, 28, $Nomclase);
                //horizontales
                $pdf->Line(70, 30, 205, 30);
                $pdf->Text(71, 36, 'FECHA: ');
                $pdf->Text(109, 36, $fecha);
                $pdf->Line(70, 40, 205, 40);
                $pdf->Text(71, 46, 'TOTAL ASISTENTES: ');
                $pdf->Text(109, 46, count($datosGen));
                $pdf->Image('../assets/images/instituciones/planes_estudio/'.$idEvento.'.png',20,25);
                $pdf->SetFont('Arial', '', '7');
                $pdf->Text( 5, 290, utf8_decode(" Página ".$pdf->PageNo()));
                $pdf->SetFont('Arial', '', '10');
                
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


$pdf->Output();

?>
