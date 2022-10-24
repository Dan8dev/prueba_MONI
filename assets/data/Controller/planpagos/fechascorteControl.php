<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/fechascorteModel.php';
	require_once '../../Model/planpagos/pagosModel.php';
	$pagosM = new pagosModel();
    $fechascorte = new fechasCorte();

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }
    $accion=@$_POST["action"];

    switch ($accion) {
        case 'obtenerGeneraciones':
            unset($_POST['action']);
            $csulG = $fechascorte->obtenerGeneraciones();
            $data = Array();
            while($dato=$csulG->fetchObject()){
                $data[]=array(
                    //0=> $dato->IDPlanPago,
                    0=> 'Generación '.$dato->secuencia_generacion,
                    1=> $dato->nombrecarrera,
                    2=> $dato->fechaCreado,
                    3=>'<button class="btn btn-primary" data-toggle="modal" data-target="#modalfechacortegeneracion" onclick="buscarGeneracionfechacorte('.$dato->idGeneracion.')">Modificar</button> '
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;
        case 'obteneralumnos':
            unset($_POST['action']);
            $csulG = $fechascorte->obteneralumnos();
            $data = Array();
            while($dato=$csulG->fetchObject()){
                $data[]=array(
                    0=> "Generación ".$dato->generacion,
                    1=> $dato->carrera,
                    2=> $dato->alumno,
                    3=>'<button class="btn btn-primary" data-toggle="modal" data-target="#modalfechacorteporalumno" onclick="buscaralumnofechacorte('.$dato->idAsistente.','.$dato->idGeneracion.')">Modificar</button> '
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;
        case 'buscarGeneracionfechacorte':
            unset($_POST['action']);
            $csulG = $fechascorte->buscarGeneracionfechacorte($_POST['idgeneracion']);
            echo json_encode($csulG['data']);
            break;

        case 'buscaralumnofechacorte':
            unset($_POST['action']);
            $csulG = $fechascorte->buscaralumnofechacorte($_POST['idGeneracion'],$_POST['idAsistente']);
            echo json_encode($csulG['data']);
            break;

        case 'obtenerconceptosoriginales':
            unset($_POST['action']);
            $csulG = $fechascorte->obtenerconceptosoriginales($_POST['idgeneracion']);
            echo json_encode($csulG['data']);
            break;
        case 'formactualizarfechasdecorteporalumno':
            $validar_pagos = $fechascorte->verificar_no_pago($_POST['idGeneracion'],$_POST['idAsistente']);
            if(!empty($validar_pagos)){
                echo json_encode(['estatus'=>'error', 'info'=>'Ya no es posible actualizar este dato, dado que el alumno ya ha realizado pagos de mensualidad.']);
                die();
            }
            $actualizarins = $fechascorte->actualizarconceptofechacortealumno($_POST['idGeneracion'],$_POST['idAsistente'],$_POST['fechaprimercolegiaturamod']);
            echo json_encode($actualizarins);
            break;
        case 'crearconceptosfechascortegen':
            unset($_POST['action']);
            $certificacionoevento=$_POST['certificacionoenevtofechas'];

            if ($certificacionoevento=='si') {//si es certificacion o evento
                $numeropagosins=1;
                $eliminado=1;
                $parcialidades=1;//la inscripcion si acepta parcialidades
                $fechaactualizado=date('Y-m-d H:i:s');
                $consultarconceptoins=$fechascorte->consultarconceptoins($_POST['idgenfechacortemod']);
                if (empty($consultarconceptoins['data'])) {//si la generacion no tiene conceptos personalizados insertar nuevos  conceptos con fechas y costos personalizados
                    $insertarins = $fechascorte->crearconceptofechacortegenins('Inscripción '.$_POST['nombregenfechacorteg'],$_POST['costoInscripcionfechacortegenmod'],'Inscripción','*',$_POST['idgenfechacortemod'],$parcialidades,$_POST['fechalimitepagoinsfechacortemod'],$eliminado,$_POST['creado_por'],$numeropagosins); 
                    echo json_encode(array('estatus'=>'ok'));
                }else { //actualizar conceptos relazionados a la generación
                    $actualizarins = $fechascorte->actualizarconceptofechacortegenins($_POST['idconceptoinsfechacorte'],$_POST['costoInscripcionfechacortegenmod'],$_POST['fechalimitepagoinsfechacortemod'],$_POST['actualizado_por'],$fechaactualizado);
                    echo json_encode($actualizarins);
                }
            }
            else {// si es carrera completa

                $numeropagosins=1;
                $eliminado=1;
                $parcialidades=2;
                $fechaactualizado=date('Y-m-d H:i:s');
                $diadecorte= $_POST['diasdecortefechacortemod'];
                        if (strlen($diadecorte)==1) {
                            $diadecorte = '0'.$diadecorte;
                        }
                $fecha='1900-00';
                $diadecortemens= $fecha.'-'.$diadecorte;
                $diadecortemens = date('Y-m-d',strtotime($diadecortemens));
                $consultarconceptoins=$fechascorte->consultarconceptoins($_POST['idgenfechacortemod']);
            
                if (empty($consultarconceptoins['data'])) {//si la generacion no tiene conceptos personalizados insertar nuevos  conceptos con fechas y costos personalizados


                    $insertarins = $fechascorte->crearconceptofechacortegenins('Inscripción '.$_POST['nombregenfechacorteg'],$_POST['costoInscripcionfechacortegenmod'],$_POST['costoInscripcionfechacortegenmodusd'],'Inscripción','*',$_POST['idgenfechacortemod'],$parcialidades,$_POST['fechalimitepagoinsfechacortemod'],$eliminado,$_POST['creador_por'],$numeropagosins); 
                    $insertarins = $fechascorte->crearconceptofechacortegenins('Mensualidad '.$_POST['nombregenfechacorteg'],$_POST['costoMensualidadfechacortemod'],$_POST['costoMensualidadfechacortemodusd'],'Mensualidad','*',$_POST['idgenfechacortemod'],$parcialidades,$diadecortemens,$eliminado,$_POST['creador_por'],$_POST['nMensualidadesfechacortemod']);
                    $insertarins = $fechascorte->crearconceptofechacortegenins('Reinscripción '.$_POST['nombregenfechacorteg'],$_POST['costoReinscripcionfechacortemod'],$_POST['costoReinscripcionfechacortemodusd'],'Reinscripción','*',$_POST['idgenfechacortemod'],$parcialidades,'0000-00-00 00:00:00',$eliminado,$_POST['creador_por'],$_POST['nReinscripcionfechacortemod']);
                    $insertarins = $fechascorte->crearconceptofechacortegenins('Titulación '.$_POST['nombregenfechacorteg'],$_POST['costotitulacionfechacortemod'],$_POST['costotitulacionfechacortemodusd'],'Titulación','*',$_POST['idgenfechacortemod'],$parcialidades,$_POST['fechalimitepagotitfechacortemod'],$eliminado,$_POST['creador_por'],$numeropagosins);
                    echo json_encode(array('estatus'=>'ok'));
                }else { //actualizar conceptos relazionados a la generación
                    $actualizarins = $fechascorte->actualizarconceptofechacortegenins($_POST['idconceptoinsfechacorte'],$_POST['costoInscripcionfechacortegenmod'],$_POST['costoInscripcionfechacortegenmodusd'],$_POST['fechalimitepagoinsfechacortemod'],$_POST['actualizado_por'],$fechaactualizado);
                    $actualizarins = $fechascorte->actualizarconceptofechacortegenmens($_POST['idconceptomensfechacorte'],$_POST['costoMensualidadfechacortemod'],$_POST['costoMensualidadfechacortemodusd'],$diadecortemens,$_POST['actualizado_por'],$fechaactualizado,$_POST['nMensualidadesfechacortemod']);
                    $actualizarins = $fechascorte->actualizarconceptofechacortegenreins($_POST['idconceptoreinsfechacorte'],$_POST['costoReinscripcionfechacortemod'],$_POST['costoReinscripcionfechacortemodusd'],$_POST['actualizado_por'],$fechaactualizado,$_POST['nReinscripcionfechacortemod']);
                    $actualizarins = $fechascorte->actualizarconceptofechacortegentit($_POST['idconceptotitfechacorte'],$_POST['costotitulacionfechacortemod'],$_POST['costotitulacionfechacortemodusd'],$_POST['fechalimitepagotitfechacortemod'],$_POST['actualizado_por'],$fechaactualizado);
                    $actualizarfechalimitedepagoprorroga=$fechascorte->actualizarfechalimitedepagoprorroga($_POST['idconceptotitfechacorte'],$_POST['fechalimitepagotitfechacortemod']);
                    echo json_encode($actualizarins);

                }
            }
            break;
        
        default:
            # code...
            break;
    }

}else {
    header('Location: ../../../../index.php');
}
