<?php

session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 5 ) ){
    header("Location: ../index.php");
    die();
}else{
    $usuario = $_SESSION["usuario"];
}
use setasign\Fpdi\Fpdi;

require ('../assets/data/functions/fpdf183/fpdf.php');
require_once('../controlescolar/fpdf183/autoload.php');
//require_once '../assets/data/Model/conexion/conexion.php';
require_once '../udc/app/data/Model/WebexModel.php';

    class HistoriaClinica extends FPDF{
				
        function Header(){
            $ancho = 200; $alto = 10;
            $this->SetFont('Arial', 'I', '20');
            $this->SetXY(115,15);
            $this->Cell($ancho, $alto, utf8_decode('Taller en Vivo'), 0, 0, 'L', false);
            //$this->Image("../pm_medicos/img/logoudcpdf.jpg",12,7, 70, 30,'JPG');
            $this->SetXY(10,50);
        }

        function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I','8');
            $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }
    

    $pdf = new HistoriaClinica();

    $listAsist = New Webex();
    $id = "87";
    $datosGen = $listAsist->datosPDFAsistenciaEventos($id)['data'];
    //Seccion 0 =  Datos generales.
    $pdf->AliasnbPages();
    $pdf->AddPage('P');
    //$pdf->Image("../../../../pm_medicos/img/fundacionudcpdf.jpg",135,25, 60, 50,'JPG');
    //info Secciones
    $pdf->SetFont('Arial','B','8');
    $InfodePruebaGeneral = "Evento General";
    $pdf->SetXY(10,40);
    $pdf->MultiCell(120,6,utf8_decode($InfodePruebaGeneral),1,"",0);
    $pdf->Ln(12);
    $pdf->SetXY(10,50);
    $count = 1;
    $pdf->SetFont('Arial','B','10');
        $pdf->SetFillColor(190, 190, 190);
        //$pdf->SetLineWidth(0.5);
        $pdf->MultiCell(10, 6,"#", 1,  'C', 1);
        $pos = $pdf->GetX();
        $pdf->SetY(($pdf->GetY())-6);
        $pdf->SetX($pdf->GetX()+10);
        $pdf->MultiCell(100, 6, utf8_decode("Nombre"), 1,  'L', 1);
        $pdf->SetY(($pdf->GetY())-6);
        $pdf->SetX($pdf->GetX()+110);
        $pdf->MultiCell(40, 6, utf8_decode("Hora de Entrada"), 1,  'C', 1);
        $pdf->SetY(($pdf->GetY())-6);
        $pdf->SetX($pdf->GetX()+150);
        $pdf->MultiCell(30, 6, utf8_decode("Tiempo Total"), 1,  'C', 1);
    
    foreach($datosGen as $Asistente){
        $conversion = intval($Asistente["tiempototal"]/60);
        //var_dump($conversion);
        $sobrantes = $Asistente["tiempototal"]-($conversion*60);
        $cadHoras = "00";
        $cadSeg = "00";
        $cadMin = "00";
        if($conversion>0){
            $minutes =  $sobrantes;
            $cadHoras = $conversion;   
        }else{
            $cadHoras = "00";
        }

        if($minutes<10){
            $cadMin = "0".$minutes;
        }else{
            $cadMin = $minutes;
        }

        if($conversion<10){
            $cadHoras = "0".$conversion;
        }else{
            $cadHoras = $conversion;
        }
        $CadFinal = "$cadHoras:$cadMin:$cadSeg";
        $pdf->SetFont('Arial','B','10');

        $pdf->MultiCell(10, 6, $count, 1,  'C', false);
        $pos = $pdf->GetX();
        $pdf->SetY(($pdf->GetY())-6);
        $pdf->SetX($pdf->GetX()+10);
        $pdf->MultiCell(100, 6, utf8_decode($Asistente["nombre"]), 1,  'L', false);
        $pdf->SetY(($pdf->GetY())-6);
        $pdf->SetX($pdf->GetX()+110);
        $pdf->MultiCell(40, 6, utf8_decode($Asistente["hora"]), 1,  'C', false);
        $pdf->SetY(($pdf->GetY())-6);
        $pdf->SetX($pdf->GetX()+150);
        $pdf->MultiCell(30, 6, utf8_decode($CadFinal), 1,  'C', false);
        
        $count++;
    }
    //Codigo para añadir firmas
    $pdf->Ln(18);
    $pdf->Line(75,$pdf->GetY()+6,135,$pdf->GetY()+6);
    $pdf->Ln(6);
    $pdf->SetXY(80,$pdf->GetY());
    $pdf->Cell(50, 6, utf8_decode('Control Escolar'), 0, 0, 'C', false);
    
    // $pdf->SetY($pdf->GetY()-24);
    // $pdf->Ln(18);
    // $pdf->Line(120,$pdf->GetY()+6,172,$pdf->GetY()+6);
    // $pdf->Ln(6);
    // $pdf->SetXY(120,$pdf->GetY());
    // $pdf->Cell(50, 6, utf8_decode('MÉDICO / RESPONSABLE'), 0, 0, 'C', false);
    $pdf->Output();
?>