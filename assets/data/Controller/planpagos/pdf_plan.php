<?php
// use setasign\Fpdi\Fpdi;
require ('../../functions/fpdf183/fpdf.php');

class PDF_MC_Table extends FPDF{
    var $widths;
    var $aligns;

    var $institucion;
    var $nombre_instit;

    var $carrera_nom;

    function setInstitucion($institucion){
        $this->institucion = $institucion;
    }

    function Header(){
        $ancho = 200; $alto = 10;

        $this->SetY(10);
        $this->SetFont('Arial', 'B', '10');
        $nombre_i = '';
        $image = '';
        $wi = 30;
        $xi = 170;
        $hi = 18;
        switch($this->institucion){
            case 13:
                $nombre_i = 'COLEGIO NACIONAL DE CONSEJEROS';
                $this->nombre_instit = 'COLEGIO NACIONAL DE CONSEJEROS';
                $image = 'https://conacon.org/moni/siscon/img/logoT.png';
                $wi = 52;
                $xi = 155;
                $hi = 15;
                break;
            case 19:
                $nombre_i = 'INSTITUTO DE ESTUDIOS SUPERIORES EN MEDICINA';
                $this->nombre_instit = 'INSTITUTO DE ESTUDIOS SUPERIORES EN MEDICINA';
                break;
            default:
                $nombre_i = 'CENTRO DE ESTUDIOS UNIVERSITARIOS DEL CONDE';
                $this->nombre_instit = 'CENTRO DE ESTUDIOS UNIVERSITARIOS DEL CONDE';
                $image = 'https://moni.com.mx/udc/img/logoT.png';
                break;
        }
        $this->Cell($ancho, $alto, utf8_decode($nombre_i), 0, 0, 'C', false);
        $this->Ln(5);
        $this->Cell($ancho, $alto, 'PLAN DE PAGOS', 0, 0, 'C', false);
        $this->Ln(10);
        $this->Image($image, $xi, 7, $wi, $hi,'PNG');
        $this->Ln(10);
    }

    function SetWidths($w){
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a){
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data){
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=6*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++){
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,6,utf8_decode($data[$i]),0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h){
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt){
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb){
            $c=$s[$i];
            if($c=="\n"){
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax){
                if($sep==-1){
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    function setCarreraNom($nombre){
        $this->carrera_nom = $nombre;
    }
}
#require_once "consultar_plan_de_pagos.php";

$pdf=new PDF_MC_Table();
$prosp = $_POST['prospecto'];
$carrera = $_POST['carrera'];
// $prosp = 21;
// $carrera = 24;

$_POST['action'] = 'obtener_plan_pago_callcenter';
$_POST['prospecto'] = $prosp;
$_POST['instit'] = 20;
$_POST['info_alumno'] = 1;
$_POST['inscrito_a'] = $carrera;
$_POST['from_funct'] = true;
// $prosp = 21;
// $carrera = 13;
#$datos = generar($prosp, $carrera);
// echo "<pre>";
// var_dump($_POST);
require_once '../../functions/ntest.php';
$datos = $desglose;
// print_r($datos);
// echo "</pre>";
// die();
$alumno = $datos[0][0];
$pdf->setInstitucion($alumno['institucion']);
$pdf->AddPage();
$pdf->SetFont('Arial','',11);

unset($datos[0]);
$datos = array_values($datos);
$new_r = [];
foreach($datos as $key => $vals){
    $datos[$key]['sub_desglose'] = '';
    $sub_r = [];
    $otr = [];
    foreach($vals as $campo => $campo_val){
        $otr = [];
        if($campo != 'sub_desglose'){
            $sub_r[] = $campo_val;
        }
        else{
            $tmp_r = [];
            foreach($campo_val as $key_camp => $camp_value){
                $tmp_r[] = $camp_value;
            }
            $otr = $tmp_r;
        }
    }
    $new_r[] = $sub_r;
    if(sizeof($otr)>0){
        $new_r = array_merge($new_r, $otr);
    }
}

$pdf->SetWidths(array(65,50,30,40,40));

    $W100 = 190;
    $W70 = $W100 * .7;
    $W65 = $W100 * .65;
    $W60 = $W100 * .6;
    $W50 = ($W100 / 2);
    $W45 = $W100 * .47;
    $W40 = $W100 * .4;
    $W35 = $W100 * .35;
    $W30 = $W100 * .3;
    $W20 = $W100 * .2;
    $W10 = $W100 * .1;

    $y = 30;
    $pdf->SetXY(10, $y);
    $pdf->MultiCell($W20, 5, utf8_decode('ALUMNO:'), 0, 'L', false);
    $pdf->SetXY($W20, $y); // <========= SALTO DE LINEA
    $pdf->MultiCell($W20+20, 5, utf8_decode(strtoupper($alumno['aPaterno'].' '.$alumno['aMaterno'].' '.$alumno['nombre'])), 0, 'L', false);

    $pdf->SetXY(10+$W45, $y); // <========= SALTO DE LINEA

    $pdf->MultiCell($W20, 5, utf8_decode('CARRERA:'), 0, 'L', false);
    $pdf->SetXY(10+$W60, $y); // <========= SALTO DE LINEA
    // $alumno['generacion']['carrera_nom'] = 'Administración';
    $pdf->MultiCell($W40, 5, utf8_decode($alumno['generacion']['carrera_nom']), 0, 'L', false);
    $lns = $pdf->NbLines($W40, utf8_decode($alumno['generacion']['carrera_nom']));
    if($lns == 1){
        $lns = 2;
    }
    $y+=($lns*5.5);
    $pdf->SetXY(10, $y);

    $pdf->MultiCell($W20, 5, utf8_decode('INICIO DE GENERACIÓN:'), 0, 'L', false);
    $pdf->SetXY(10+$W20, $y+2.5); // <========= SALTO DE LINEA
    $pdf->MultiCell($W35, 5, utf8_decode(substr($alumno['generacion']['fecha_inicio'], 0, 10)), 0, 'L', false);

    $pdf->SetXY(10+$W45, $y); // <========= SALTO DE LINEA
    if(isset($alumno['primer_mensualidad'])){
        $pdf->MultiCell($W20, 5, utf8_decode('FECHA PRIMER MENSUALIDAD:'), 0, 'L', false);
        $pdf->SetXY(10+$W70, $y+2.5); // <========= SALTO DE LINEA
        $pdf->MultiCell($W30, 5, utf8_decode($alumno['primer_mensualidad']), 0, 'L', false);
    }

    $pdf->SetX(30);
    $pdf->SetY(70);

    $pdf->Row(['CONCEPTO', 'MONTO', 'PROMO / BECA', 'FECHA LIMITE']);
for($i=0; $i < sizeof($new_r); $i++){
    $dt = $new_r[$i];
    $pdf->Row([$dt[0], $dt[1], $dt[2], $dt[3]]);
}
$filename="../../../files/planespago/".$prosp.'_'.$carrera.'_'.date("Y-m-d").".pdf";
$pdf->Output($filename,'F');
// $pdf->Output();
require_once '../../functions/correos_prospectos.php';
$destintatarios = [[$alumno['correo'], $alumno['nombre']]];
$plantilla = '';
if($pdf->institucion == '13'){
    $plantilla = 'plantilla_plan_pagos_conacon.html';
}else if($pdf->institucion == '20'){
    $plantilla = 'plantilla_plan_pagos_udc.html';
}

$claves = ['%%PROSPECTO','%%INSTITUTO','%%OFERTA','%%USUARIO','%%CONTRASENIA'];
$valores = [$alumno['nombre'],$pdf->nombre_instit,$alumno['generacion']['carrera_nom'],$alumno['correo'], '12345'];
if($plantilla != ''){
    enviar_correo_registro('Envío de plan de pago', $destintatarios, $plantilla, $claves, $valores, $filename);
}

?>