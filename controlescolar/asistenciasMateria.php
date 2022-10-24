<?php

use setasign\Fpdi\Fpdi;

    require ('fpdf183/fpdf.php');
    require_once('fpdf183/autoload.php');
    require_once '../assets/data/Model/conexion/conexion.php';
    require_once '../assets/data/Model/controlescolar/materiasModel.php';
    require_once '../assets/data/Model/controlescolar/controlEscolarModel.php';
    $matM = new Materias();
    $ceM = new ControlEscolar();

    $meses_cortos = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");

    session_start();
    if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 3 ) ){
        header("Location: ../index.php");
        die();
    }
    $materia = isset($_GET['materia']) ? $_GET['materia'] : false;
    $generacion = isset($_GET['generacion']) ? $_GET['generacion'] : false;
    $vista = '';
    $groupG = '';

    if(isset($_GET['groupG']) && $_GET['groupG'] != ''){
        $groupG =  $_GET['groupG'] ;
        $vista = $_GET['vista'];
    }


    if(!$materia) {
        echo "<h1>No se ha especificado la materia</h1>";
        die();
    }
    if(!$generacion) {
        echo "<h1>No se ha especificado la generación</h1>";
        die();
    }
    $sesiones = $matM->consultar_clases_materia($materia, $generacion,$groupG,$vista);
    $consultar_alumnos = $ceM->consultar_alumnos_generacion($generacion,$groupG,$vista);
    $num_sesiones = sizeof($sesiones);
    $nombres_docentes = [];
    $tiempo_retardo = $ceM->retardo;
    $tiempo_falta = $ceM->falta;


    foreach ($sesiones as $key => $value) {
        $arrf = explode('-', substr($value['fecha_hora_clase'],0,10));
        $sesiones[$key]['fecha_corta'] = $arrf[2].' '.$meses_cortos[$arrf[1]-1];
        $sesiones[$key]['timestamp'] = strtotime($value['fecha_hora_clase']);
        
        $sesiones[$key]['timestamp_retardo'] = strtotime("+{$tiempo_retardo} minutes", $sesiones[$key]['timestamp']);
        $sesiones[$key]['timestamp_falta'] = strtotime("+{$tiempo_falta} minutes", $sesiones[$key]['timestamp']);

        $nombre_d = $value['nombres']." ".$value['aPaterno'];
        if(!in_array($nombre_d, $nombres_docentes)) {
            array_push($nombres_docentes, $nombre_d);
        }
    }
    foreach ($consultar_alumnos as $alumno => $datos) {
        $consultar_alumnos[$alumno]['asistencias'] = [];
        $i = 0;
        foreach($sesiones as $sesion => $val){
            $asistencia = $ceM->consultar_asistencia_alumno_clase($datos['idAsistente'], $val['idClase']);
            if($asistencia){
                $hora = substr($asistencia['hora'],11,5);
                $acceso = strtotime($asistencia['hora']);
                 $consultar_alumnos[$alumno]['asistencias'][$i] = $acceso > $val['timestamp_retardo'] ? $acceso > $val['timestamp_falta'] ? 'F' : 'R' : 'A'; 
            }else{
                $consultar_alumnos[$alumno]['asistencias'][$i] = '/';
            }
            $i++;
        }
    }


    $finalTipoCarrera = '';
    $f = 0;
    $countP = 0;

    $obtenerNomclase = "NOMBRE DE LA CLASE";
    if(strlen($obtenerNomclase)>42){
        $result = substr($obtenerNomclase, 0, 42);
        $Nomclase = $result.'...';
    }else{
        $Nomclase = $obtenerNomclase;
    }

    $obtenerNombreMateria = strtoupper(utf8_decode($sesiones[0]['nombre']));
    $nombreMateria = $obtenerNombreMateria;
    /*if(strlen($obtenerNombreMateria)>63){
        $resultado = substr($obtenerNombreMateria, 0, 63);
        $nombreMateria = $resultado.'...';
    }else{
        $nombreMateria = $obtenerNombreMateria;
    }*/
    $original_size_font = 9;

    $nombreCarreraFinal = $sesiones[0]['carrera_nombre'];
    //$nombreCarreraFinal = strlen($nombreCarreraFinal) > 77 ? substr($nombreCarreraFinal, 0, 77)."..." : $sesiones[0]['carrera_nombre'];
    $nombreCarreraFinal = strtoupper(utf8_decode($nombreCarreraFinal));
    $nombreMaestro = "MAESTRO APELLIDO ETC"; 

    $pdf = new FPDF();

    $datos['nombreCarreraFinal'] = $nombreCarreraFinal;
    $datos['nombreMateria'] = $nombreMateria;
    $datos['nombres_docentes'] = $nombres_docentes;
    $datos['id_institucion'] = $sesiones[0]['idInstitucion'];
    $datos['num_sesiones'] = $num_sesiones;
    $datos['sesiones'] = $sesiones;

    add_new_page($pdf, $datos);
    
    $linea = 71;
    $simbolosEje = 124;
    $lineaEje = 78;
    $countEje = 0;
    $inicio = 133;
    $final = 232;
    $espacio = ($final - $inicio) / $num_sesiones;
    $ix = 1;
    foreach($consultar_alumnos as $alumno){
        $linea+=7;
        if($linea <= 176){
            $pdf->Text(14, $linea, $ix);
            $pdf->Text(23, $linea, strtoupper(utf8_decode($alumno['nombre_alumno'])));
            $tmp_inicio_a = 135;
            foreach ($alumno['asistencias'] as $key => $valor) {
                $pdf->Text($tmp_inicio_a, $linea, $valor);
                $tmp_inicio_a += $espacio;
            }
        }else{
            add_new_page($pdf, $datos);
            $linea = 78;
            $pdf->Text(14, $linea, $ix);
            $pdf->Text(23, $linea, strtoupper(utf8_decode($alumno['nombre_alumno'])));
            $tmp_inicio_a = 135;
            foreach ($alumno['asistencias'] as $key => $valor) {
                $pdf->Text($tmp_inicio_a, $linea, $valor);
                $tmp_inicio_a += $espacio;
            }
        }
        $ix++;
    }
    $pdf->output();

    function add_new_page($pdf, $datos){
        $ancho = 276; $alto = 10;
        $original_size_font = 9;
        
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', '', $original_size_font);
        
        $pdf->SetFillColor(255,255,255);
        $pdf->SetDrawColor(0,0,0);
        
        $pdf->Cell($ancho,$alto, 'LISTA DE ASISTENCIA', 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->SetFillColor(255,255,255);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Cell($ancho,30,' ',1,2,'L', true);
        $pdf->Line(135, 20, 135, 50);
        $pdf->Line(95, 20, 95, 50);

        //horizontales
        $pdf->Text(96, 27, 'CARRERA');
        $pdf->SetY(21);
        $pdf->SetX(136);
        $pdf->MultiCell(149, 4, $datos['nombreCarreraFinal'],0);
        $pdf->Text(96, 37, 'MATERIA');
        $pdf->SetY(31);
        $pdf->SetX(136);
        $pdf->MultiCell(149, 4, $datos['nombreMateria'],0);
        //$pdf->Text(136, 37, $datos['nombreMateria']);
        
        $pdf->Text(96, 47, 'DOCENTE');
        
        $pdf->Line(95, 40, 286, 40);
        $pdf->Line(95, 30, 286, 30);
        $pdf->Image('../assets/images/instituciones/planes_estudio/'.$datos['id_institucion'].'.png',35,24);
        $pdf->SetFont('Arial', '', '7');
        $pdf->Text( 5, 290, utf8_decode("Página ".$pdf->PageNo()));
        $pdf->SetFont('Arial', '', $original_size_font);
        
        $cont = 1;
        $contg = 1;
        
        $pdf->Write( 7, ' ' );
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        //verticales
        $pdf->Line(133, 177, 133, 55); // < -------- inicio del espacio de las asistencias
        $pdf->Line(286, 177, 286, 55);
        //$pdf->Line(133, 55, 286, 55);
        $pdf->Line(10, 177, 10, 55);
        $pdf->Line(22, 177, 22, 55);
        $pdf->Line(232, 177, 232, 55); // < -------- fin del espacio de las asistencias

        $todos_docentes = implode(', ', $datos['nombres_docentes']);
        $datos['todos_docentes'] = strtoupper(utf8_decode($todos_docentes));
        $pdf->setXY(136,41);
        $pdf->MultiCell(149, 4, $datos['todos_docentes'],0);
        $espacio_docente = 286 - 135;
        $cabe_nombre_docente = ($pdf->GetStringWidth($todos_docentes) < $espacio_docente);
        
        $tmp_size = $original_size_font;
        while($cabe_nombre_docente == false){
            $tmp_size--;
            $pdf->SetFont('Arial', '', $tmp_size);
            $cabe_nombre_docente = ($pdf->GetStringWidth($todos_docentes) < $espacio_docente) ? true : false;
        }

        $inicio = 133;
        $final = 232;
        $num_sesiones = $datos['num_sesiones'];
        $espacio = ($final - $inicio) / $num_sesiones;
        $sesiones = $datos['sesiones'];
        for($ses = 0; $ses < $num_sesiones; $ses++){ 
            $y = 62.3;// < -------- repartir espacios de sesiones
            $cabe = ($pdf->GetStringWidth($sesiones[$ses]['fecha_corta']) < $espacio - 2) ? true : false;
            $tmp_size = $original_size_font;
            while($cabe == false){
                $tmp_size--;
                $pdf->SetFont('Arial', '', $tmp_size);
                $cabe = ($pdf->GetStringWidth($sesiones[$ses]['fecha_corta']) < $espacio - 2) ? true : false;
            }

            
            $pdf->SetFont('Arial', '', $original_size_font);
            if($espacio>35){
                $pdf->Text($inicio+1, 71, $sesiones[$ses]['fecha_corta'] );
            }else{
                for($i = 0; $i<6 ; $i++){
                    $pdf->SetFont('Arial', '', 7.3);
                    //var_dump($sesiones[$ses]['fecha_corta'][$i]);
                    $pdf->Text(($inicio-1)+($espacio/2), $y, $sesiones[$ses]['fecha_corta'][$i] );
                    $y+=2.15;
                }
            }
            //var_dump($espacio);
            
            $pdf->Line($inicio, 177, $inicio, 60);
            $inicio = $inicio + $espacio;


        }
      

        //horizontales
        $pdf->Line(10, 55, 286, 55);
        $pdf->Line(133, 60, 232, 60);
        $pdf->Line(10, 74, 286, 74);
        $pdf->SetFont('Arial', 'B', '9');
        $pdf->Text(13,65,'NO.');
        $pdf->Text(69,65,'NOMBRE');
        $pdf->Text(166,58.5,'FECHAS DE CLASE');
        $pdf->Text(244,65,'OBSERVACIONES');
        $pdf->SetFont('Arial', '', '9');
        
        //horizontal
        $pdf->Line(10,80,286,80);
        $pdf->Line(10,87,286,87);
        $pdf->Line(10,94,286,94);
        $pdf->Line(10,101,286,101);
        $pdf->Line(10,108,286,108);
        $pdf->Line(10,115,286,115);
        $pdf->Line(10,122,286,122);
        $pdf->Line(10,129,286,129);
        $pdf->Line(10,136,286,136);
        $pdf->Line(10,143,286,143);
        $pdf->Line(10,150,286,150);
        $pdf->Line(10,157,286,157);
        $pdf->Line(10,164,286,164);
        $pdf->Line(10,171,286,171);
        $pdf->Line(10,177,286,177);
    }


?>
