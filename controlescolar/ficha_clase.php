<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 3 ) ){
    header("Location: ../index.php");
    die();
}

$usuario = $_SESSION["usuario"];

require_once '../assets/data/Model/conexion/conexion.php';
require ('fpdf183/fpdf.php');

use setasign\Fpdi\Fpdi;

require_once('fpdf183/autoload.php');

$conexion = new Conexion(); 
$con = $conexion->conectar(); 
$con = $con['conexion'];

//Ancho y Alto de las celdas de las tablas
$ancho = 195; $alto = 10;

$statement = $con->prepare( "SELECT clases.*, materias.nombre AS nombreMateria, materias.id_carrera, a_carreras.nombre AS nombreCarrera, 
CONCAT( maestros.aPaterno, ' ' ,maestros.aMaterno, ', ', maestros.nombres ) AS nombreMaestro 
FROM clases, materias, a_carreras, maestros 
WHERE clases.idMateria = materias.id_materia AND materias.id_carrera = a_carreras.idCarrera 
AND clases.idClase=".$_GET['idClase']." AND maestros.id = clases.idMaestro");
$statement->execute();
$data= $statement->fetch(PDO::FETCH_ASSOC);

$data['fecha_hora_clase'] = substr( $data['fecha_hora_clase'], 8,2).'/'.substr( $data['fecha_hora_clase'], 5,2).'/'.substr( $data['fecha_hora_clase'], 0,4).' '.substr( $data['fecha_hora_clase'], 11,8);

$pdf=new FPDF();
$pdf->AddPage();

$statement->execute();

$pdf->SetFont('Arial', 'B', '10');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,$alto, utf8_decode( $data['titulo'].' - '.$data['nombreMateria'] ),1,0,'C', true);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,30,' ',1,2,'L', true);

$pdf->Line(108, 20, 108, 50);
$pdf->Line(70, 20, 70, 50);
$pdf->Text( 71, 28, 'DOCENTE:                      '.substr( utf8_decode($data['nombreMaestro']), 0, 45) );
$pdf->Line(70, 30, 205, 30);
$pdf->Text( 71, 36, 'FECHA:                          '.$data['fecha_hora_clase'] );
$pdf->Line(70, 40, 205, 40);
$pdf->Text( 71, 46, 'CARRERA:                     '.$data['nombreCarrera'] );

$pdf->Image('logoudc.png', 25,23);
$pdf->SetFont('Arial', 'B', '12');
$pdf->Write( 10, 'DETALLES DE LA CLASE: ' );
$pdf->Ln();

$pdf->SetFont('Arial', 'B', '10');
$pdf->Write( 10, 'ESTADO: ' );
$pdf->SetFont('Arial', '', '10');
if( $data['estado'] == 1 )
    $pdf->Write( 10, "ACTIVA" );
else
    $pdf->Write( 10, "INACTIVA");
$pdf->Ln();

$pdf->SetFont('Arial', 'B', '10');
$pdf->Write( 10, 'RECURSOS: ' );
$pdf->SetFont('Arial', '', '10');
$pdf->Ln();
$recursos = explode( "],[", $data['recursos'] );
for( $i = 0; $i < count($recursos); $i++ ){
    $recursos[$i] = str_replace('[', '', $recursos[$i] );
    $recursos[$i] = str_replace(']', '', $recursos[$i] );
    $recursos[$i] = str_replace('"', '', $recursos[$i] );
    $recursos[$i] = str_replace('%20', ' ', $recursos[$i] );
    $pdf->Write( 10, $recursos[$i] );
    $pdf->Ln();
}

$pdf->SetFont('Arial', 'B', '10');
$pdf->Write( 10, 'APOYOS: ' );
$pdf->Ln();
$pdf->SetFont('Arial', '', '10');
$apoyos = explode( "],[", $data['apoyo'] );
for( $i = 0; $i < count($recursos); $i++ ){
    $apoyos[$i] = str_replace('[', '', $apoyos[$i] );
    $apoyos[$i] = str_replace(']', '', $apoyos[$i] );
    $apoyos[$i] = str_replace('"', '', $apoyos[$i] );
    $apoyos[$i] = str_replace('%20', ' ', $apoyos[$i] );
    $pdf->Write( 10, $apoyos[$i] );
    $pdf->Ln();
}

$pdf->SetFont('Arial', 'B', '10');
$pdf->Write( 10, 'FOTO: ' );
$pdf->Ln();
$pdf->Image('../siscon/app/'.$data['foto'], null, null, 80);

$pdf->SetFont('Arial', 'B', '10');
$pdf->Write( 10, 'VIDEO: ' );
$pdf->SetFont('Arial', '', '10');
$pdf->Ln();
$data['video'] = str_replace('%3A', ':', $data['video'] );
$data['video'] = str_replace('%2F', '/', $data['video'] );
$data['video'] = str_replace('%20', ' ', $data['video'] );
$pdf->Write( 10, $data['video'] );

$pdf->Output();
?>