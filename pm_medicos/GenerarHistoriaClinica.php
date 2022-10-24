<?php
require_once '../assets/data/Model/conexion/conexion.php';
require_once '../assets/data/Model/controlescolar/controlEscolarModel.php';
require_once '../assets/data/Model/controlescolar/planEstudiosModel.php';

use setasign\Fpdi\Fpdi;

require ('../udc/app/data/functions/fpdf183/fpdf.php');
require_once('../udc/app/data/functions/fpdf183/autoload.php');

class PDF extends FPDF{
    
    function Header(){
        $ancho = 200; $alto = 10;

        $this->SetFont('Arial', 'I', '20');
        $this->SetXY(125,15);
        $this->Cell($ancho, $alto, utf8_decode('HISTORIA CLÍNICA'), 0, 0, 'L', false);
        $this->Image("img/logoudcpdf.jpg",12,7, 70, 30,'JPG');
        //$this->SetXY(10,100);
    }

    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I','8');
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF();

$pdf->AliasnbPages();
$pdf->AddPage('P');

$pdf->Image("img/fundacionudcpdf.jpg",135,25, 60, 50,'JPG');

//info Secciones
$Info = ["ANTECEDENTES HEREDOFAMILIARES","ANTECEDENTES PERSONALES NO PATOLÓGICOS","ANTECEDENTES PERSONALES PATOLÓGICOS","ANTECEDENTES GINECO-OBSTÉTRICOS","PADECIMIENTO ACTUAL","SIGNOS VITALES",
"EXPLORACIÓN FÍSICA", "DIAGNÓSTICO", "TRATAMIENTO","PRONÓSTICO"];
$pdf->SetXY(10,40);
$pdf->SetFont('Arial','B','8');
$InfodePruebaGeneral = "";





$pdf->MultiCell(120,6,utf8_decode($InfodePruebaGeneral),1,"",0);
$pdf->Ln(12);
foreach($Info as $seccion){
    $pdf->SetFont('Arial','B','10');

    //$pdf->SetFillColor(190, 190, 190);
    $pdf->SetLineWidth(0.5);
    $pdf->MultiCell(185, 6, utf8_decode($seccion), 0,  'L', false);

    $pdf->SetFont('Arial','I','8'); 
    $pdf->MultiCell(190, 6, utf8_decode($infoDinamica[$seccion]), 1,'', false);
    $pdf->Ln(6);
}
//Codigo para añadir firmas
// $pdf->Ln(18);
// $pdf->Line(80,$pdf->GetY()+6,130,$pdf->GetY()+6);
// $pdf->Ln(6);
// $pdf->Cell(190, 6, utf8_decode('Control Escolar'), 0, 0, 'C', false);

$pdf->Output();


?>