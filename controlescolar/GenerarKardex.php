<?php
require_once '../assets/data/Model/conexion/conexion.php';
require_once '../assets/data/Model/controlescolar/controlEscolarModel.php';
require_once '../assets/data/Model/controlescolar/planEstudiosModel.php';

use setasign\Fpdi\Fpdi;

require ('fpdf183/fpdf.php');
require_once('fpdf183/autoload.php');

$Generacion=$_GET['Generacion'];
$idAlumno=$_GET['idAlumno'];
$carrera=$_GET['idCarrera'];
$NombreAl = $_GET['nombre'];

$plan = new PlanEstudios();
$infoPlan = $plan->buscarPlanEstudios(['id'=>$carrera]);
//var_dump($infoPlan['data']);
//die();
$ce = new ControlEscolar();
$infoCarrera = $ce->buscarCarrerasPorId($carrera);
$infoAlumno = $ce->ObtenerInfoAlumno($idAlumno);

$infoGen = $ce->buscarGeneracion($Generacion);

if($infoAlumno[0]["matricula"]==""){
    $Matricula = "S/M";
}else{
    $Matricula = $infoAlumno[0]["matricula"];
}

$Ciclost = $ce->obtenerCiclosGeneracion(['idGen'=>$Generacion]);

switch($Ciclost['data'][0]['tipoCiclo']){
    case 1:
        $TipCiclo = "Cuatrimestre";
        break;
    case 2:
        $TipCiclo = "Semestre";
        break;
    case 3:
        $TipCiclo = "Trimestre";
        break;
}

$NumeroCiclos = $ce->obtener_numero_de_ciclos($Generacion,$idAlumno);


class PDF extends FPDF{
    
    function Header(){
        $ancho = 200; $alto = 10;

        $this->SetY(10);
        $this->SetFont('Arial', 'B', '9');
        $this->Cell($ancho, $alto, 'CENTRO DE ESTUDIOS UNIVERSITARIOS DEL CONDE', 0, 0, 'C', false);
        $this->Ln(5);
        $this->Cell($ancho, $alto, 'KARDEX', 0, 0, 'C', false);
        $this->Ln(10);
        $this->Image("logoudcpdf.jpg",160,30, 25, 25,'JPG');
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

$pdf->SetX(30);
$pdf->SetY(40);

$pdf->Cell(30, 6, utf8_decode('NOMBRE:'), 0, 0, 'L', false);
$pdf->SetFont('');
$pdf->Cell(70, 6, utf8_decode($NombreAl), 0, 0, 'L', false);
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', '9');
$pdf->Cell(30, 6, utf8_decode('MATRICULA:'), 0, 0, 'L', false);
$pdf->SetFont('');
$pdf->Cell(10, 6, utf8_decode($Matricula), 0, 0, 'L', false);
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', '9');
$pdf->Cell(30, 6, utf8_decode('CARRERA:'), 0, 0, 'L', false);
$pdf->SetFont('');
$pdf->Cell(120, 6, utf8_decode($infoCarrera[0]['nombre']), 0, 0, 'L', false);
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', '9');
$pdf->Cell(30, 6, utf8_decode('GENERACIÓN:'), 0, 0, 'L', false);
$pdf->SetFont('');
$pdf->Cell(120, 6, utf8_decode($infoGen['data']['nombre']), 0, 0, 'L', false);
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', '9');
$pdf->Cell(30, 6, utf8_decode('FECHA DE INICIO:'), 0, 0, 'L', false);
$pdf->SetFont('');
$pdf->Cell(120, 6, utf8_decode(substr($infoGen['data']['fecha_inicio'],0,-8)), 0, 0, 'L', false);
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', '9');
$pdf->Cell(30, 6, utf8_decode('FECHA FINAL:'), 0, 0, 'L', false);
$pdf->SetFont('');
$pdf->Cell(120, 6, utf8_decode(substr($infoGen['data']['fechafinal'],0,-8)), 0, 0, 'L', false);
$pdf->Ln(6);

$pdf->SetX(30);
$pdf->SetY(80);


for ($a=0;$a<intval(count($NumeroCiclos['data']));$a++){
    $pdf->SetFont('Arial','B','8');

    $pdf->SetFillColor(190, 190, 190); 
    $pdf->Cell(180, 6, utf8_decode($TipCiclo.": ".($a+1)), 1, 0, 'C', true);
    $pdf->Ln(6);

    $pdf->SetFillColor(229, 229, 229); 
    $pdf->Cell(150, 6, utf8_decode('Asignatura'), 1, 0, 'C', true);
    $pdf->Cell(30, 6, utf8_decode('Calificación'), 1, 0, 'C', true);
    $pdf->Ln(6);

    $pdf->SetFont('Arial','I','8');
    for($i=0;$i<$NumeroCiclos['data'][$a]['numero_materias'];$i++){ 
        $califPorCiclo = $ce->obtener_calificaciones_periodo($Generacion,$idAlumno,$a+1);   
        $pdf->Cell(150, 6, utf8_decode($califPorCiclo['data'][$i]['nombre']), 1, 0, 'L', false);
        $pdf->Cell(30, 6, utf8_decode($califPorCiclo['data'][$i]['calificacion']), 1, 0, 'C', false);
        $pdf->Ln(6);
    }
    $pdf->Ln(6);
}
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
