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

$statement = $con->prepare( "SELECT cursos_examen.*, materias.nombre AS materia, a_carreras.nombre AS carrera, CONCAT( maestros.aPaterno, ' ', maestros.aMaterno, ', ', maestros.nombres) AS maestro
FROM cursos_examen, materias, a_carreras, maestros
WHERE cursos_examen.idCurso = materias.id_materia AND a_carreras.idCarrera = materias.id_carrera AND maestros.id = cursos_examen.idMaestro AND cursos_examen.idExamen = ".$_GET['idExamen'] );
$statement->execute();
$data= $statement->fetch(PDO::FETCH_ASSOC);

$data['fechaInicio'] = substr( $data['fechaInicio'], 8,2).'/'.substr( $data['fechaInicio'], 5,2).'/'.substr( $data['fechaInicio'], 0,4).' '.substr( $data['fechaInicio'], 11,8);
$data['fechaFin'] = substr( $data['fechaFin'], 8,2).'/'.substr( $data['fechaFin'], 5,2).'/'.substr( $data['fechaFin'], 0,4).' '.substr( $data['fechaFin'], 11,8);

$pdf=new FPDF( 'P', 'mm', 'Legal' );
$pdf->AddPage();

$statement->execute();

$pdf->SetFont('Arial', 'B', '10');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,$alto, utf8_decode( 'EXAMEN '.$data['materia'].' ('.$data['carrera'].')' ),1,0,'C', true);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,30,' ',1,2,'L', true);

$pdf->Line(108, 20, 108, 50);
$pdf->Line(70, 20, 70, 50);
$pdf->Text( 71, 28, 'NOMBRE:                      '.substr( utf8_decode($data['Nombre']), 0, 45) );
$pdf->Line(70, 30, 205, 30);
$pdf->Text( 71, 36, 'FECHA:                          Del '.$data['fechaInicio'].' al '.$data['fechaFin'] );
$pdf->Line(70, 40, 205, 40);
$pdf->Text( 71, 46, 'MAESTRO:                     '.$data['maestro'] );

$pdf->Image('logoudc.png', 25,23);
//$pdf->SetFont('Arial', '', '7');
//$pdf->Text( 5, 350, utf8_decode( strtoupper(utf8_decode($data['Nombre']))." | Página ".$pdf->PageNo() ) );
$pdf->SetFont('Arial', '', '10');

$cont = 0;
$contg = 1;
$pdf->Write( 7, ' ' );
$pdf->Ln();

$pdf->SetFont('Arial', 'B', '10');
$pdf->Cell($ancho,$alto, 'NOMBRE DEL ALUMNO: ', 1,0,'L', true);
$pdf->Ln();
$pdf->Cell($ancho,$alto, utf8_decode('N° DE REACTIVOS:                              N° DE ACIERTOS:                                   CALIFICACIÓN: '), 1,0,'L', true);
$pdf->Ln();
$pdf->SetFont('Arial', 'B', '12');
$pdf->Write( 10, 'INSTRUCCIONES: ' );
$pdf->SetFont('Arial', '', '12');
$pdf->Write( 10, 'Marca con una (X) la respuesta que consideres correcta para cada pregunta.' );

$statement = $con->prepare( "SELECT * FROM cursos_examen_preguntas WHERE idExamen = ".$_GET['idExamen'].' ORDER BY RAND()' );
$statement->execute();
$p = 1;

$pdf->SetFont('Arial', '', '11');

if( $statement -> rowCount() > 0 ){
   
    $pdf->Ln();
    $opL = 'abcd';
    $o = 0;
                                    
    while( $fila = $statement->fetch(PDO::FETCH_ASSOC) ){

        $pdf->SetFont('Arial', 'B', '10');
        $pdf->Write( 5, utf8_decode( $p++.') '.$fila['pregunta'] ) );
        $pdf->Ln();
        $pdf->SetFont('Arial', '', '10');
        $opciones = json_decode( $fila['opciones'] );

        foreach ($opciones as $clave => $valor) {
            $pdf->Write( 5, utf8_decode( '     '.$opL[$o++].') '.$clave.' (   )' ) );
            $pdf->Ln();
        }
        $o = 0;
        $pdf->Ln();
            
    }//while

}//Fin if rowCount

$pdf->Output();
?>