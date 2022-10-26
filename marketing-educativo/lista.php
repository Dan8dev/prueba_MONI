<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 3 ) ){
    header("Location: ../index.php");
    die();
}

$id_evento = $_GET['id_evento'];
//var_dump($id_evento);
// die();

$usuario = $_SESSION["usuario"];

require_once '../assets/data/Model/controlescolar/controlEscolarModel.php';
require_once '../assets/data/Model/conexion/conexion.php';
require ('../assets/data/functions/fpdf183/fpdf.php');
require_once('../controlescolar/fpdf183/autoload.php');

use setasign\Fpdi\Fpdi;

//require_once('fpdf183/autoload.php');

$conexion = new Conexion(); 
$con = $conexion->conectar(); 
$con = $con['conexion'];

$no_tutores = 0;
$no_calidad = 0;
$no_procedimientos = 0;
$total = 0;

//Ancho y Alto de las celdas de las tablas
$ancho = 195; $alto = 10;

if( $_GET['t'] == 'ev' ) $tipo ="EVENTO"; else $tipo = "TALLER";

if( $_GET['t'] == 'ev' )
    $statement = $con->prepare( "SELECT titulo, fechaE FROM ev_evento WHERe idEvento = ".$_GET['id_evento'] );
else
    $statement = $con->prepare( "SELECT asistentes_eventos.id_evento, ev_evento.fechaE, ev_talleres.nombre AS titulo, asistentes_eventos.id_taller FROM ev_evento, ev_talleres, asistentes_eventos WHERE asistentes_eventos.id_taller = ".$_GET['id_evento']." AND ev_talleres.id_taller=".$_GET['id_evento']." AND asistentes_eventos.id_evento = ev_evento.idEvento LIMIT 0,1" );

$statement->execute();
$dataEvento = $statement->fetch(PDO::FETCH_ASSOC);
$dataEvento['fechaE'] = substr( $dataEvento['fechaE'], 8,2).'/'.substr( $dataEvento['fechaE'], 5,2).'/'.substr( $dataEvento['fechaE'], 0,4).' '.substr( $dataEvento['fechaE'], 11,8);

$pdf=new FPDF();
$pdf->AddPage();
if( $_GET['t'] == 'ev' )
    $statement = $con->prepare( "SELECT DISTINCT id_asistente, LTRIM(CONCAT( a_prospectos.aPaterno, ' ', a_prospectos.aMaterno, ', ', a_prospectos.nombre)) as nombre, ev_evento.titulo, hora FROM a_prospectos, asistentes_eventos, ev_evento WHERE a_prospectos.idAsistente = asistentes_eventos.id_asistente AND ev_evento.idEvento = asistentes_eventos.id_evento AND id_evento = ".$_GET['id_evento']." GROUP BY nombre ORDER BY nombre;" );
else    
    $statement = $con->prepare( "SELECT DISTINCT id_asistente, LTRIM(CONCAT( a_prospectos.aPaterno, ' ', a_prospectos.aMaterno, ', ', a_prospectos.nombre)) as nombre, ev_talleres.nombre AS titulo, hora FROM a_prospectos, asistentes_eventos, ev_talleres WHERE a_prospectos.idAsistente = asistentes_eventos.id_asistente AND ev_talleres.id_taller = asistentes_eventos.id_evento AND asistentes_eventos.id_taller = 1 GROUP BY nombre ORDER BY nombre;" );

$statement->execute();

$listAsist = New ControlEscolar();
$NombreAlumno = [];
$fechaAcceso = [];

$datosGen = $listAsist->datosPDFAsistenciaEventos($id_evento)['data'][0]['idInstitucion'];
$logo = "logoudc.png";
$x=20;
$y=20;
$rx=27;
$ry=25;
switch($datosGen){
    case '2':
        $logo = "logoudc.png";
        break;
    case '13':
        $x=40;
        $y=20;
        $rx=20;
        $ry=25;
        $logo = 'logo_conacon.png';
        break;
    case '19':
        $logo = 'logoiesm.jpg';
        break;
}

$pdf->SetFont('Arial', 'B', '10');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,$alto,'LISTA DE ASISTENCIA',1,0,'C', true);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,30,' ',1,2,'L', true);

$pdf->Line(97, 20, 97, 50);

$pdf->Line(70, 20, 70, 50);
$pdf->Text( 71, 26,$tipo);

$pdf->SetY(21);
$pdf->SetX(98);

$pdf->MultiCell( 106,4, utf8_decode($dataEvento['titulo']),0);
$pdf->Line(70, 30, 205, 30);
$pdf->Text( 71, 36, 'FECHA:               '.$dataEvento['fechaE']);
$pdf->Line(70, 40, 205, 40);
$pdf->Text( 71, 46, 'ASISTENTES:     '.$statement -> rowCount() );
$pdf->Ln(20);


$pdf->Image($logo,$rx,$ry,$x,$y);
// $pdf->Image('logo_conacon.png', 20,25);
$pdf->SetFont('Arial', '', '7');
$pdf->Text( 5, 290, utf8_decode( "COLEGIO NACIONAL DE CONSEJEROS | ".strtoupper(utf8_decode($dataEvento['titulo']))." | Página ".$pdf->PageNo() ) );
$pdf->SetFont('Arial', '', '10');

$cont = 0;
$contg = 1;
$pdf->Write( 7, ' ' );
$pdf->Ln();

if( $statement -> rowCount() > 0 ){
   
    $pdf->SetFont('Arial', 'B', '10');
    $pdf->Cell(12,7, 'NO.', 1,0,'C', true); $pdf->Cell(143,7, 'NOMBRE' ,1,0,'C', true);$pdf->Cell(40,7, 'ACCESO' ,1,0,'C', true);
    $pdf->Ln();
    $pdf->SetFont('Arial', '', '10');
                                    
    while( $fila = $statement->fetch(PDO::FETCH_ASSOC) ){
        $cont++;
        $fecha = substr( $fila['hora'], 8,2).'/'.substr( $fila['hora'], 5,2).'/'.substr( $fila['hora'], 0,4).' '.substr( $fila['hora'], 11,8);
        $pdf->Cell(12,7, $contg++, 1,0,'C', true); $pdf->Cell(143,7,utf8_decode( strtoupper($fila['nombre']) ),1,0,'L', true);$pdf->Cell(40,7, $fecha,1,0,'C', true);
        $pdf->Ln();
        if( $cont > 25 ){
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(0,0,0);
            $pdf->Cell($ancho,$alto,'LISTA DE ASISTENCIA',1,0,'C', true);
            $pdf->Ln();
            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(0,0,0);
            $pdf->Cell($ancho,30,' ',1,2,'L', true);
            
            $pdf->Line(97, 20, 97, 50);
            $pdf->Line(70, 20, 70, 50);
            $pdf->Text( 71, 28, $tipo);
            $pdf->SetY(21);
            $pdf->SetX(98);

            $pdf->MultiCell( 106,4, utf8_decode($dataEvento['titulo']),0);
            $pdf->Line(70, 30, 205, 30);
            $pdf->Text( 71, 36, 'FECHA:               '.$dataEvento['fechaE']);
            $pdf->Line(70, 40, 205, 40);
            $pdf->Text( 71, 46, 'ASISTENTES:     '.$statement -> rowCount() );
            $pdf->Ln(20);

            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Write( 7, ' ' );            
            $pdf->Ln();
            $pdf->Cell(12,7, 'NO.', 1,0,'C', true); $pdf->Cell(143,7, 'NOMBRE' ,1,0,'C', true);$pdf->Cell(40,7, 'ACCESO' ,1,0,'C', true);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', '10');
            
            $pdf->Image($logo,$rx,$ry,$x,$y);
            $pdf->SetFont('Arial', '', '7');
            $pdf->Text( 5, 290, utf8_decode( "COLEGIO NACIONAL DE CONSEJEROS | ".strtoupper($dataEvento['titulo'])." | Página ".$pdf->PageNo() ) );
            $cont = 0;
            $pdf->SetFont('Arial', '', '10');
        }
            
    }//while

}
$pdf->Output('ListaAsistenciaEvento.pdf','I');
?>