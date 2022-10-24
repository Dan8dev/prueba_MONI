<?php
require_once "enviar_plan_de_pagos.php";
$datos = generar();


use setasign\Fpdi\Fpdi;

require ('fpdf183/fpdf.php');


class PDF extends FPDF{
    
    function Header(){
        $ancho = 200; $alto = 10;

        $this->SetY(10);
        $this->SetFont('Arial', 'B', '9');
        $this->Cell($ancho, $alto, 'CENTRO DE ESTUDIOS UNIVERSITARIOS DEL CONDE', 0, 0, 'C', false);
        $this->Ln(5);
        $this->Cell($ancho, $alto, 'KARDEX', 0, 0, 'C', false);
        $this->Ln(10);
        // $this->Image("logoudcpdf.jpg",160,30, 25, 25,'JPG');
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

// WIDTH : 210
// W-MARG : 190
// 70% = 133;
// 60% = 114;
// 50% = 95;
// 30% = 57;
// 20% = 38;

$W100 = 190;
$W70 = $W100 * .7;
$W65 = $W100 * .65;
$W60 = $W100 * .6;
$W50 = ($W100 / 2);
$W35 = $W100 * .35;
$W30 = $W100 * .3;
$W20 = $W100 * .2;
$W10 = $W100 * .1;
// |¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯|
$y = 30;

$pdf->SetXY(10, $y);
$pdf->MultiCell($W20, 5, utf8_decode('ALUMNO:'), 0, 'L', false);
$pdf->SetFont('');

$pdf->SetXY(10+$W20, $y); // <========= SALTO DE LINEA
$pdf->MultiCell($W30, 5, "Jesus Octavio Pajaro Cruz Pajaro Cruz", 0, 'L', false);
// |_________________________________________________________________________________________________|
// |¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯|
$pdf->SetXY(10+$W50, $y); // <========= SALTO DE LINEA

$pdf->SetFont('Arial', 'B', '9');
$pdf->MultiCell($W20, 5, utf8_decode('CARRERA:'), 0, 'L', false);
$pdf->SetFont('');

$pdf->SetXY(10+$W65, $y); // <========= SALTO DE LINEA
$pdf->MultiCell($W35, 5, utf8_decode("Generación 17 Consejería y Educador en Estrategias de Prevención de Conductas Antisociales"), 0, 'L', false);
// |_________________________________________________________________________________________________|

$y+=17;
$pdf->SetXY(10, $y);

// |¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯|
$pdf->SetFont('Arial', 'B', '9');
$pdf->MultiCell($W20, 5, utf8_decode('INICIO DE GENERACIÓN:'), 0, 'L', false);
$pdf->SetFont('');

$pdf->SetXY(10+$W20, $y+2.5); // <========= SALTO DE LINEA
$pdf->MultiCell($W35, 5, "2022/05/16", 0, 'L', false);
// |_________________________________________________________________________________________________|
// |¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯|
$pdf->SetXY(10+$W50, $y); // <========= SALTO DE LINEA

$pdf->SetFont('Arial', 'B', '9');
$pdf->MultiCell($W20, 5, utf8_decode('FECHA PRIMER MENSUALIDAD:'), 0, 'L', false);
$pdf->SetFont('');

$pdf->SetXY(10+$W65, $y+2.5); // <========= SALTO DE LINEA
$pdf->MultiCell($W30, 5, utf8_decode("2022/06/15"), 0, 'L', false);
// |_________________________________________________________________________________________________|


$pdf->SetX(30);
$pdf->SetY(70);



    $pdf->SetFont('Arial','B','8');

    // $pdf->SetFillColor(190, 190, 190); 
    // $pdf->Cell(180, 6, utf8_decode("Materia : "), 1, 0, 'C', true);
    // $pdf->Ln(6);

    $pdf->SetFillColor(229, 229, 229); 
    $pdf->Cell(40, 6, utf8_decode('CONCEPTO'), 1, 0, 'C', true);
    $pdf->Cell(40, 6, utf8_decode('MONTO A PAGAR'), 1, 0, 'C', true);
    $pdf->Cell(40, 6, utf8_decode('BECA / PROMOCIÓN'), 1, 0, 'C', true);
    $pdf->Cell(40, 6, utf8_decode('BECA / PROMOCIÓN'), 1, 0, 'C', true);
    $pdf->Cell(30, 6, utf8_decode('BECA / PROMOCIÓN'), 1, 0, 'C', true);
    
    $pdf->Ln(6);

    
    
    $pdf->SetFont('Arial','I','8');
    $yi = 76;
    
    for($i=0; $i < sizeof($datos); $i++){ 
        $dt = $datos[$i];
        // if(strlen($dt['concepto']) > 95){
        //     $dt['concepto'] = substr($dt['concepto'], 0, 95);
        // }
        // $pdf->Cell(40, 6, utf8_decode($dt['concepto'])       , 1, 0, 'L', false);
        // $pdf->Cell(40, 6, utf8_decode($dt['monto_pagar'])    , 1, 0, 'C', false);
        // $pdf->Cell(40, 6, utf8_decode($dt['promocion'])      , 1, 0, 'C', false);
        // $pdf->Cell(40, 6, utf8_decode($dt['str_fecha_conce_mens']), 1, 0, 'C', false);
        // $pdf->Cell(30, 6, utf8_decode($dt['estatus'])        , 1, 0, 'C', false);
        $total_string_width = $pdf->GetStringWidth($dt['concepto']);
        
        $pdf->SetXY(10, $yi);
        $pdf->MultiCell(40, 5, utf8_decode($dt['concepto']), 1, 'L', false);
        $pdf->SetXY(50, $yi);
        for($kj = $pdf->GetStringWidth($dt['monto_pagar']); $kj < $total_string_width; $kj++ ){
            $dt['monto_pagar'].=' .';
        }
        $pdf->MultiCell(40, 5, utf8_decode($dt['monto_pagar']), 1, 'L', false);
        // $pdf->MultiCell(30, 6, utf8_decode("2022/06/15").': 2:'.$i, 1, 'L', false);
        // $pdf->MultiCell(30, 6, utf8_decode("2022/06/15").': 3:'.$i, 1, 'L', false);
        
        
        $number_of_lines = $total_string_width / (30);
        $number_of_lines = ceil( $number_of_lines );

        $line_height = 5;                             // Whatever your line height is.
        $height_of_cell = $number_of_lines * $line_height; 
        $height_of_cell = ceil( $height_of_cell );
        $yi += $height_of_cell;

        // $pdf->Ln(6);
    }
    $pdf->Ln(6);

$pdf->Ln(18);
$pdf->Line(80,$pdf->GetY()+6,130,$pdf->GetY()+6);
$pdf->Ln(6);
$pdf->Cell(190, 6, utf8_decode('Control Escolar'), 0, 0, 'C', false);



//Datos de la tabla
/*$pdf->SetY(68);
foreach($info as $alumno){
    $pdf->SetFont('Arial', '', '9');
    $pdf->Cell(10, 6, $alumno['numero'], 1, 0, 'C', false);
    $pdf->Cell(113, 6, utf8_decode($alumno['nombre']), 1, 0, 'C', false);
    
    foreach($alumno['asistencias'] as $dia){
        $pdf->Cell(16, 6, utf8_decode($dia), 1, 0, 'C', false);
    }
    $pdf->SetX(229); 
    $pdf->Cell(57, 6, utf8_decode($alumno['observaciones']), 1, 1, 'C', false);
    
}*/

$pdf->Output();


?>
?>