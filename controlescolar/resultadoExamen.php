<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31  && $_SESSION["usuario"]['idTipo_Persona'] != 35 && $_SESSION["usuario"]['idTipo_Persona'] != 3 ) ){
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

$exam = New ControlEscolar();
$conFechaNull = 0;
$NombreAlumno = [];
$fechaRealizacion = [];
$calificacion = [];
$id = $_GET['idExamen'];
$datosExam = $exam->datosPDFExamen($id)['data'];

foreach($datosExam as $campo => $value){
    $fecha = $datosExam[$campo]['fechaInicio'];
    if($fecha==null){
        $fechaF = '--------';
    }else{
        $fechaF = date('d-m-y', strtotime($fecha));
    }
    $nombre = utf8_decode($datosExam[$campo]['Nombre']);
    $idInstitucion = $datosExam[$campo]['idInstitucion'];

    $nombreAlumno[$campo] = utf8_decode($datosExam[$campo]['nombreAlumno']);
    $calificacion[$campo] = $datosExam[$campo]['calificacion'];
    if($calificacion[$campo]==null){
        $calificacionFinal[$campo] = 'NP';
    }else{
        $calificacionFinal[$campo] = round($calificacion[$campo]/10, 2);
    }

    if($datosExam[$campo]['fechaPresentacion']==null){
        $fechaRealizacion[$campo] = '-----------------------';
        $conFechaNull++;
    }else{
        $fechaRealizacion[$campo] = $datosExam[$campo]['fechaPresentacion'];
    }

}
//var_dump(strlen($nombreTaller));
$nombreFinal = $nombre;
/*if(strlen($nombre)>45){
    $resultado = substr($nombre, 0, 45);
    $nombreFinal = $resultado.'...';
}else{
    $nombreFinal = $nombre;
}*/
if($nombreAlumno[$campo]== null){
    $total = 0;
}else{
    $total = count($datosExam);
}

header('Content-type: application/pdf');

$ancho = 195; $alto = 10;

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', '10');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,$alto, 'LISTA DE EXAMEN', 1, 0, 'C', true);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,30,' ',1,2,'L', true);
$pdf->Line(108, 20, 108, 50);
$pdf->Line(70, 20, 70, 50);
$pdf->Text(71, 28, 'EXAMEN: ');
$pdf->SetY(21);
$pdf->SetX(108);
$pdf->MultiCell(96, 4 , $nombreFinal,0);
$pdf->Ln(20);
//horizontales
$pdf->Line(70, 30, 205, 30);
$pdf->Text(71, 36, 'FECHA: ');
$pdf->Text(109, 36, $fechaF);
$pdf->Line(70, 40, 205, 40);
$pdf->Text(71, 46, 'TOTAL ALUMNOS: ');
$pdf->Text(109, 46, $total);
$pdf->Image('../assets/images/instituciones/planes_estudio/'.$idInstitucion.'.png',25,23);



$cont = 1;
$contg = 1;
$val = 0;

    $pdf->Write( 7, ' ' );
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', '10');
    $pdf->Cell(12,7, 'NO.', 1,0,'C', true); 
    $pdf->Cell(100,7, 'NOMBRE' ,1,0,'C', true);
    $pdf->Cell(50,7, utf8_decode('FECHA DE PRESENTACIÓN') ,1,0,'C', true);
    $pdf->Cell(30,7, 'RESULTADO' ,1,0,'C', true);
    $pdf->Ln();
    $pdf->SetFont('Arial', '', '10');

    
    if($total > 0 && $total != $conFechaNull){
        for($i=0;$i < $total ;$i++){
           
            $pdf->Cell(12,7, $contg++,1,0, 'C', true);
            $pdf->Cell(100,7,$nombreAlumno[$i], 1, 0, 'L', true);
            $pdf->Cell(50,7,$fechaRealizacion[$i], 1 , 0, 'C', true);
            $pdf->Cell(30,7,$calificacionFinal[$i], 1 , 0, 'C', true);
            $pdf->Ln();
            //$pdf->text(40,7,$val);
            //$pdf->text(50,7,$total);
            $cont++;
            $val++;
            
            if($cont > 25 || $val == $total ){
                $pdf->Ln();
                $pdf->Ln();
                $pdf->Ln();
                $pdf->Cell($ancho,$alto-5,'__________________________________',0,0,'C', true);
                $pdf->Ln();
                $pdf->Cell($ancho,$alto,'NOMBRE Y FIRMA DEL MAESTRO',0,0,'C', true);
                $pdf->Ln();
            }

            if($cont > 25){

                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Cell($ancho,$alto, 'LISTA DE EXAMEN', 1, 0, 'C', true);
                $pdf->Ln();
                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Cell($ancho,30,' ',1,2,'L', true);

                $pdf->Line(108, 20, 108, 50);
                $pdf->Line(70, 20, 70, 50);
                $pdf->Text(71, 28, 'EXAMEN: ');
                $pdf->SetY(21);
                $pdf->SetX(108);
                $pdf->MultiCell(96, 4 , $nombreFinal,0);
                $pdf->Ln(20);
                //horizontales
                $pdf->Line(70, 30, 205, 30);
                $pdf->Text(71, 36, 'FECHA: ');
                $pdf->Text(109, 36, $fechaF);
                $pdf->Line(70, 40, 205, 40);
                $pdf->Text(71, 46, 'TOTAL ALUMNOS: ');
                $pdf->Text(109, 46, $total);
                $pdf->Image('../assets/images/instituciones/planes_estudio/'.$idInstitucion.'.png',25,23);
                
                $pdf->Write( 7, ' ' );
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(12,7, 'NO.', 1,0,'C', true); 
                $pdf->Cell(100,7, 'NOMBRE' ,1,0,'C', true);
                $pdf->Cell(50,7, utf8_decode('FECHA DE PRESENTACIÓN') ,1,0,'C', true);
                $pdf->Cell(30,7, 'RESULTADO' ,1,0,'C', true);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '10');
                $cont=1;
            }
        }      
    }

$pdf->Output();


?>
