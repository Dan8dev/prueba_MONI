<?php
require_once '../assets/data/Model/conexion/conexion.php';
require_once '../assets/data/Model/controlescolar/controlEscolarModel.php';
require_once '../assets/data/Model/controlescolar/planEstudiosModel.php';

use setasign\Fpdi\Fpdi;

require ('fpdf183/fpdf.php');
require_once('fpdf183/autoload.php');

$carrera=$_GET['idCarrera'];
$idAlumno=$_GET['idAlumno'];
$ciclo=$_GET['ciclo'];
$NombreAl = $_GET['nombre'];
$Generacion = $_GET['idGen'];

$plan = new PlanEstudios();
$infoPlan = $plan->buscarPlanEstudios(['id'=>$carrera]);
$ce = new ControlEscolar();
$TipodeCiclo = $ce->TipoDeCiclo($ciclo);

$infoCarrera = $ce->buscarCarrerasPorId($carrera);
$infoAlumno = $ce->ObtenerInfoAlumno($idAlumno);

$infoGen = $ce->buscarGeneracion($Generacion);
//var_dump($NombreGeneracion['data'][0]['nombre']);
//$NombreGen = $infoGen['data']['nombre'];
//$fecha_inicio = $infoGen['data']['fecha_inicio'];
//$fechafinal = $infoGen['data']['fechafinal'];

//die();

if($infoAlumno[0]["matricula"]==""){
    $Matricula = "S/M";
}else{
    $Matricula = $infoAlumno[0]["matricula"];
}

switch($TipodeCiclo[0]['tipo_ciclo']){
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

$cicloSelect = $ce->obtenerCicloSeleccionado($ciclo);
$califPorCiclo = $ce->ConsultarCalPorciclo($Generacion,$idAlumno,$cicloSelect['data']['ciclo_asignado']);

$data = Array();
while($dato=$califPorCiclo->fetchObject()){
    $data[]=array(
    'nombreMat'=> $dato->nombre,
    'CalMat'=> $dato->calificacion
    );
}



class PDF extends FPDF{
    
    function Header(){

        $ancho = 200; $alto = 10;

        $this->SetY(10);
        $this->SetFont('Arial', 'B', '9');
        $this->Cell($ancho, $alto, 'CENTRO DE ESTUDIOS UNIVERSITARIOS DEL CONDE', 0, 0, 'C', false);
        $this->Ln(5);
        $this->Cell($ancho, $alto, 'BOLETA DE CALIFICACIONES', 0, 0, 'C', false);
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
$pdf->Cell(30, 6, utf8_decode($Matricula), 0, 0, 'L', false);
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
//Añadir las calificaciones
//var_dump($data[0]['nombreMat']);
//var_dump($data[0]['CalMat']);
$pdf->SetFont('Arial','B','8');

$pdf->SetFillColor(190, 190, 190); 
$pdf->Cell(180, 6, utf8_decode($TipCiclo.": ".$cicloSelect['data']['ciclo_asignado']), 1, 0, 'C', true);
$pdf->Ln(6);

$pdf->SetFillColor(229, 229, 229); 
$pdf->Cell(150, 6, utf8_decode('Asignatura'), 1, 0, 'C', true);
$pdf->Cell(30, 6, utf8_decode('Calificación'), 1, 0, 'C', true);
$pdf->Ln(6);

$pdf->SetFont('Arial','I','8');
for($i=0;$i<sizeof($data);$i++){    
    $pdf->Cell(150, 6, utf8_decode($data[$i]['nombreMat']), 1, 0, 'L', false);
    $pdf->Cell(30, 6, utf8_decode($data[$i]['CalMat']), 1, 0, 'C', false);
    $pdf->Ln(6);
}
$pdf->Ln(24);
$pdf->Line(80,$pdf->GetY()+6,130,$pdf->GetY()+6);
$pdf->Ln(6);
$pdf->Cell(190, 6, utf8_decode('Control Escolar'), 0, 0, 'C', false);


$pdf->Output();

?>
