<?php
session_start();
if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['idTipo_Persona'] != 31)){
    header("Location: ../index.php");
    die();
}else{
    $usuario = $_SESSION["usuario"];
}
use setasign\Fpdi\Fpdi;
require_once '../assets/data/Model/conexion/conexion.php';
require_once '../assets/data/Model/controlescolar/planEstudiosModel.php';
require_once('fpdf183/fpdf.php');
require_once('fpdf183/autoload.php');

class PDF extends FPDF{
    function Header(){
        $plEst = new PlanEstudios();
        $id = $_GET['id_plan'];
        $datosGen = $plEst->datosPDFPlanEstudios($id)['data'];
        foreach($datosGen as $campo => $value){
            $nom = utf8_decode($datosGen[$campo]['nombre']);
            $nomInst = utf8_decode($datosGen[$campo]['nombreInst']);
            $rvoe = utf8_decode($datosGen[$campo]['rvoe']);
            $fechaC = date('d-m-Y', strtotime($datosGen[$campo]['fecha_creado']));
            $nomCarr = utf8_decode($datosGen[$campo]['nombreCarr']);
            $modalidad = utf8_decode($datosGen[$campo]['modalidadCarrera']);
            switch($datosGen[$campo]['tipo_ciclo']){
                case '1':
                    $tipoC = 'Cuatrimestre';
                    break;
                case '2':
                    $tipoC = 'Semestre';
                    break;
                case '3':
                    $tipoC = 'Trimestre';
                    break;
            }
            $numC = $datosGen[$campo]['numero_ciclos'];
            $imgLogo = $datosGen[$campo]['id_institucion'];
            $rvoeF = $rvoe == null ?  '--------' : $rvoe;
            $modalidad = $modalidad == null ? '-----' : $modalidad; 
        }  
        $nombreCarreraFinal=$nomCarr;
        $ancho = 193; $alto = 10;
        $this->SetFont('Arial', 'B', '10');
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->Cell($ancho,$alto, $nom, 1, 0, 'C', true);
        $this->Ln();
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->Cell($ancho,29,' ',1,2,'L', true);
        $this->Line(108, 20, 108, 49);
        $this->Line(60, 20, 60, 49);
        //horizontales
        $this->Line(60, 29, 203, 29);
        $this->Line(60, 39, 203, 39);
        $this->Text( 61, 26, $nomInst);
        $this->setXY(108, 21);
        $this->MultiCell(95,4,"Carrera: ".$nombreCarreraFinal,0,'L',0);
        $this->Text( 61, 35, utf8_decode('F. Creación: ').$fechaC);
        $this->Text( 110, 35, 'RVOE: '.$rvoeF);
        $this->Image('../assets/images/instituciones/planes_estudio/'.$imgLogo.'.png', 18,23);
        $this->SetXY(10,60);
    }

    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I','8');
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
header('Content-type: application/pdf');
$plEst = new PlanEstudios();
$id = $_GET['id_plan'];
$datosGen = $plEst->datosPDFPlanEstudios($id)['data'];
$numC = $datosGen[0]['numero_ciclos'];
switch($datosGen[0]['tipo_ciclo']){
    case '1':
        $tipoC = 'Cuatrimestre';
        break;
    case '2':
        $tipoC = 'Semestre';
        break;
    case '3':
        $tipoC = 'Trimestre';
        break;
}
$ancho = 193; $alto = 10;
$pdf = new PDF();
$pdf->AliasnbPages();
$pdf->AddPage('P');
$pdf->SetFont('Arial', 'B', '10');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$numeroC = 1;
for($i = 0;$i < $numC ; $i++){
    $nomMaterias = [];
    $claveMaterias = [];
    $numCreditosMaterias = [];
    $tipoMaterias = [];
    $materiasGen = $plEst->materiasPDFPlanEstudios($id, $numeroC)['data'];
    foreach($materiasGen as $campo => $value){
        $nomMaterias[$campo] = utf8_decode($materiasGen[$campo]['nombre']);
        $nombreMateriaFinal[$campo] = $nomMaterias[$campo];
        $claveMaterias[$campo] = $materiasGen[$campo]['clave_asignatura'];
        $numCreditosMaterias[$campo] = $materiasGen[$campo]['numero_creditos'];
        $con = $materiasGen[$campo]['idInstitucion'] == 13 ? 1 : 2;
        switch($materiasGen[$campo]['tipo']){
            case '1':
                $tipoMaterias[$campo] = 'Adicional';
                break;
            case '2':
                $tipoMaterias[$campo] =  utf8_decode('Área');
                break;
            case '3':
                $tipoMaterias[$campo] =  utf8_decode('Complementaria');
                break;
            case '4':
                $tipoMaterias[$campo] =  utf8_decode('Obligatoria');
                break;
            case '5':
                $tipoMaterias[$campo] =  utf8_decode('Optativa');
                break;
        }
        
    }
    $contar = count($nomMaterias);
    if($con == 2){
        $pdf->SetXY($pdf->GetX(),$pdf->GetY());
        //La suma debe ser 193 /76////
        $pdf->SetFillColor(192,192,192);
        $pdf->MultiCell(193,6,utf8_decode("$tipoC $numeroC"),1,'C',1);
        $pdf->Ln();
        $pdf->SetFillColor(255,255,255);
        $pdf->SetXY($pdf->GetX(),$pdf->GetY()-6);
        $pdf->MultiCell(81,6,"Materia",1,'C');
        $pdf->SetXY($pdf->GetX()+81,$pdf->GetY()-6);
        $pdf->MultiCell(40,6,"Clave",1,'C');
        $pdf->SetXY($pdf->GetX()+121,$pdf->GetY()-6);
        $pdf->MultiCell(40,6,"Tipo de asignatura",1,'C');
        $pdf->SetXY($pdf->GetX()+161,$pdf->GetY()-6);
        $var = $pdf->MultiCell(32,6,utf8_decode("Núm de créditos"),1,'C');
        if($contar>0){
            $aumento = 0;
            for($l = 0 ; $l < $contar ; $l++){
                $tam = $nombreMateriaFinal[$l] == strtoupper($nombreMateriaFinal[$l]) ? 36 : 41;
                $band = strlen($nombreMateriaFinal[$l]) > $tam ? true : false;

                $pdf->SetXY($pdf->GetX(),$pdf->GetY());
                $TamNom = $pdf->MultiCell(81,5,$nombreMateriaFinal[$l],1,'L');
                $TamNom = $TamNom * 5;
                $pdf->SetXY($pdf->GetX()+81,$pdf->GetY()-$TamNom);
                $pdf->MultiCell(40, $TamNom ,$claveMaterias[$l],1,'L');
                $pdf->SetXY($pdf->GetX()+121,$pdf->GetY()-$TamNom);
                $pdf->MultiCell(40, $TamNom ,$tipoMaterias[$l],1,'C');
                $pdf->SetXY($pdf->GetX()+161,$pdf->GetY()-$TamNom);
                $pdf->MultiCell(32, $TamNom ,utf8_decode($numCreditosMaterias[$l]),1,'C');
            }
            $pdf->Ln();
        }
        $numeroC++;   
    }
    if($con == 1){
        $pdf->SetXY($pdf->GetX(),$pdf->GetY());
        $pdf->MultiCell(193,6,"Materias ($tipoC)",1,'C');

        if($contar>0){
            $aumento = 0;
            for($l = 0 ; $l < $contar ; $l++){
                $pdf->SetX($pdf->GetX()+10);
                $TamNom = $pdf->MultiCell(183,5,$nomMaterias[$l],1,'L');
                $TamNom =  $TamNom * 5;
                $pdf->SetXY($pdf->GetX(),$pdf->GetY()-$TamNom); //Alineacion ya que multicell inserta un espacio en automatico
                $pdf->MultiCell(10,$TamNom,$l+1,1,'C');
            }
        }
        $numeroC++;
    }
}
$pdf->Output();
?>
