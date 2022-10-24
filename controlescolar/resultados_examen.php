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

$no_tutores = 0;
$no_calidad = 0;
$no_procedimientos = 0;
$total = 0;

//Ancho y Alto de las celdas de las tablas
$ancho = 195; $alto = 10;

$statement = $con->prepare( "SELECT * FROM cursos_examen WHERE idExamen = ".$_GET['idExamen'] );
$statement->execute();
$dataExamen = $statement->fetch(PDO::FETCH_ASSOC);
$dataExamen['fechaInicio'] = substr( $dataExamen['fechaInicio'], 8,2).'/'.substr( $dataExamen['fechaInicio'], 5,2).'/'.substr( $dataExamen['fechaInicio'], 0,4).' '.substr( $dataExamen['fechaInicio'], 11,8);

$statement = $con->prepare( "SELECT idAlumno, calificacion, fechaPresentacion, CONCAT(apaterno, ' ', amaterno, ', ', nombre ) as nombre FROM curso_examen_alumn_resultado, a_prospectos 
WHERE idExamen = ".$_GET['idExamen']." AND curso_examen_alumn_resultado.idAlumno = a_prospectos.idAsistente ORDER BY nombre" );
$statement->execute();

$pdf=new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', '10');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,$alto,'RESULTADOS DE EXAMEN',1,0,'C', true);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Cell($ancho,30,' ',1,2,'L', true);

$pdf->Line(108, 20, 108, 50);
$pdf->Line(70, 20, 70, 50);
$pdf->Text( 71, 28, 'EXAMEN:                      '.utf8_decode( $dataExamen['Nombre'] ) );
$pdf->Line(70, 30, 205, 30);
$pdf->Text( 71, 36, 'FECHA:                         '.$dataExamen['fechaInicio']);
$pdf->Line(70, 40, 205, 40);
$pdf->Text( 71, 46, 'TOTAL ALUMNOS:       '.$statement -> rowCount() );

$pdf->Image('logo_conacon.png', 20,25);
$pdf->SetFont('Arial', '', '7');
$pdf->Text( 5, 290, utf8_decode( "RESULTADOS DE EXAMEN | ".strtoupper(utf8_decode($dataExamen['Nombre']))." | Página ".$pdf->PageNo() ) );
$pdf->SetFont('Arial', '', '10');

$cont = 0;
$contg = 1;
$pdf->Write( 7, ' ' );
$pdf->Ln();

if( $statement -> rowCount() > 0 ){
   
    $pdf->SetFont('Arial', 'B', '10');
    $pdf->Cell(12,7, 'NO.', 1,0,'C', true); $pdf->Cell(100,7, 'NOMBRE' ,1,0,'C', true);$pdf->Cell(50,7, utf8_decode('FECHA DE PRESENTACIÓN') ,1,0,'C', true);$pdf->Cell(30,7, 'RESULTADO' ,1,0,'C', true);
    $pdf->Ln();
    $pdf->SetFont('Arial', '', '10');
                                    
    while( $fila = $statement->fetch(PDO::FETCH_ASSOC) ){
        $cont++;
        $fecha = substr( $fila['fechaPresentacion'], 8,2).'/'.substr( $fila['fechaPresentacion'], 5,2).'/'.substr( $fila['fechaPresentacion'], 0,4).' '.substr( $fila['fechaPresentacion'], 11,8);
        $pdf->Cell(12,7, $contg++, 1,0,'C', true); $pdf->Cell(100,7,utf8_decode( strtoupper($fila['nombre']) ),1,0,'L', true);$pdf->Cell(50,7, $fecha,1,0,'C', true);$pdf->Cell(30,7, $fila['calificacion']/10 ,1,0,'C', true);
        $pdf->Ln();

        if( $cont > 25 || $cont == $statement -> rowCount() ){
            $pdf->Ln();$pdf->Ln();$pdf->Ln();
            $pdf->Cell($ancho,$alto-5,'__________________________________',0,0,'C', true);
            $pdf->Ln();
            $pdf->Cell($ancho,$alto,'NOMBRE Y FIRMA DEL MAESTRO',0,0,'C', true);
            $pdf->Ln();
        }

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
            
            $pdf->Line(108, 20, 108, 50);
            $pdf->Line(70, 20, 70, 50);
            $pdf->Text( 71, 28, $tipo.':                       '.substr( utf8_decode($dataExamen['titulo']), 0, 45) );
            $pdf->Line(70, 30, 205, 30);
            $pdf->Text( 71, 36, 'FECHA:                         '.$dataExamen['fechaE']);
            $pdf->Line(70, 40, 205, 40);
            $pdf->Text( 71, 46, 'TOTAL ASISTENTES:  '.$statement -> rowCount() );            

            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Write( 7, ' ' );            
            $pdf->Ln();
            $pdf->Cell(12,7, 'NO.', 1,0,'C', true); $pdf->Cell(143,7, 'NOMBRE' ,1,0,'C', true);$pdf->Cell(40,7, 'ACCESO' ,1,0,'C', true);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', '10');
            
            $pdf->Image('logo_conacon.png', 20,25);
            $pdf->SetFont('Arial', '', '7');
            $pdf->Text( 5, 290, utf8_decode( "RESULTADOS DE EXAMEN | ".strtoupper(utf8_decode( $dataExamen['Nombre'] ) )." | Página ".$pdf->PageNo() ) );
            $cont = 0;
            $pdf->SetFont('Arial', '', '10');
        }
            
    }//while

}

$pdf->Output();

?>