<?php  

//Set Access-Control-Allow-Origin with PHP
header('Access-Control-Allow-Origin: https://moni.com.mx', false);


session_start();
//if (isset($_POST["action"]) && (isset($_SESSION["alumno"]) || isset($_POST['android_id_afiliado']) || isset($_POST['android_id_prospecto']))) {
    date_default_timezone_set("America/Mexico_City");

require "../Model/materiasModel.php";
require "../Model/examenModel.php";
require "../../../../assets/data/Model/planpagos/generacionesModel.php";
require "../../../../assets/data/Model/controlescolar/materiasModel.php";
require "../../../../assets/data/Model/planpagos/pagosModel.php";
require "../../../../assets/data/Model/controlescolar/controlEscolarModel.php";

// $idusuario=isset($_SESSION['alumno']['id_afiliado']) ? $_SESSION['alumno']['id_afiliado'] : $_POST['android_id_afiliado'];
// $idprospecto=isset($_SESSION['alumno']['id_prospecto']) ? $_SESSION['alumno']['id_prospecto'] : $_POST['android_id_prospecto'];

$idusuario=isset($_POST['android_id_afiliado']) ? $_POST['android_id_afiliado'] : $_SESSION['alumno']['id_afiliado'] ;
$idusuario=isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
$idprospecto=isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];

$materiaM = new Materia();
$examenM = new Examen();
$generacionesM = new Generaciones();

$materiasCE = new Materias(); // Modelos de control Escolar
$pagosM = new pagosModel(); // Modelos de plan de pagos
$controlEscM = new ControlEscolar();

switch ($_POST['action']) {
    case 'cargar_clases':
        $resp = [];
        if(isset($_POST['materia']) && isset($_POST['generacion'])){
            
            $clases = $materiaM->cursoClases($_POST['materia'], $_POST['generacion']);
            for ($i=0; $i < sizeof($clases['data']); $i++) { 
                $clases['data'][$i]['tareas'] = $materiaM->tareasClase($clases['data'][$i]['idClase'])['data'];
                if($clases['data'][$i]['apoyo'] != ''){
                    $clases['data'][$i]['apoyo'] = json_decode($clases['data'][$i]['apoyo']);
					if(gettype($clases['data'][$i]['apoyo']) == 'array'){
                        $link = '#';
                        foreach($clases['data'][$i]['apoyo'] as $recurso => $valor){
                            $archivo_buscar = '../../../../assets/files/clases/apoyos/'.$valor[0];
                            if(!file_exists($archivo_buscar)){
                                if(file_get_contents('https://moni.com.mx/assets/files/clases/apoyos/'.$valor[0])){
                                    $link = 'https://moni.com.mx/assets/files/clases/apoyos/'.$valor[0];
                                }
                            }else{
                                $link = '../../assets/files/clases/apoyos/'.$valor[0];
                            }
                            $clases['data'][$i]['apoyo'][$recurso][0] = $link;
                        }
                    }
                }else{
					$clases['data'][$i]['apoyo'] = [];
				}
				if($clases['data'][$i]['recursos'] != ''){
                    $clases['data'][$i]['recursos'] = json_decode($clases['data'][$i]['recursos']);
					if(gettype($clases['data'][$i]['recursos']) == 'array'){
                        $link = '#';
                        foreach($clases['data'][$i]['recursos'] as $recurso => $valor){
                            $archivo_buscar = '../../../../assets/files/clases/recursos/'.$valor[0];
                            if(!file_exists($archivo_buscar)){
                                if(file_get_contents('https://moni.com.mx/assets/files/clases/recursos/'.$valor[0])){
                                    $link = 'https://moni.com.mx/assets/files/clases/recursos/'.$valor[0];
                                }
                            }else{
                                $link = '../../assets/files/clases/recursos/'.$valor[0];
                            }
                            $clases['data'][$i]['recursos'][$recurso][0] = $link;
                        }
                    }
                }else{
					$clases['data'][$i]['recursos'] = [];
				}
                for ($j=0; $j < sizeof($clases['data'][$i]['tareas']); $j++) { 
                    // print_r($clases['data'][$i]['tareas'][$j])."\n";
                    $clases['data'][$i]['tareas'][$j]['entregas'] = $materiaM->obtener_info_tarea_entrega($clases['data'][$i]['tareas'][$j]['idTareas'], $idusuario)['data'];
                }
            }
            $resp = ['estatus'=>'ok', 'data'=>$clases['data']];
            
        }else{
            $resp = ['estatus'=>'error','info'=>'materia_no_valida'];
        }

        echo json_encode($resp);
        break;
    case 'pago_cursos': // consultar carreras con generaciones asignadas al alumno
        echo json_encode($generacionesM->generaciones_alumno($idprospecto));
        break;
    case 'enviar_tarea':
        $resp = [];
        if((isset($_POST['tarea_entrega']) && intval($_POST['tarea_entrega']) > 0) && (isset($_FILES['inp_adjunto_tarea']) && $_FILES['inp_adjunto_tarea']['error'] == 0)){
            $tmp_name = $_FILES["inp_adjunto_tarea"]["tmp_name"];
            $uploads_dir = "../../../../assets/files/clases/tareas/clase_".$_POST['clase_tarea'];
            if(!is_dir($uploads_dir)){
                mkdir($uploads_dir, 0777, true);
            }

            $name = basename($_FILES["inp_adjunto_tarea"]["name"]);
            $fileT = explode(".", $_FILES["inp_adjunto_tarea"]["name"])[sizeof(explode(".", $_FILES["inp_adjunto_tarea"]["name"]))-1];
            $nName = 'A'.$idusuario.'_C'.$_POST['clase_tarea'].'_T'.$_POST['tarea_entrega'].'_'.date("Y-m-d_H-i-s").".".$fileT;
            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");

            // despues de haber movido el archivo se actualiza el nombre del archivo para que sea ruta complenta http
            if($_SERVER['SERVER_NAME'] == 'localhost'){
                $nName = "assets/files/clases/tareas/clase_".$_POST['clase_tarea']."/".$nName;
            }else{
                $nName = "assets/files/clases/tareas/clase_".$_POST['clase_tarea']."/".$nName;
            }
            if($statFile){
                $datos = ['tarea'=> $_POST['tarea_entrega'],'alumno'=> $idusuario,'archivo'=> $nName,'comentario'=> $_POST['inp_comentario_tarea']];
                $insert = $materiaM->entregar_tareas($datos);
                $resp = $insert;
            }else{
                $resp = ['estatus'=>'error', 'info'=>'error_al_adjuntar_tarea'];
                unlink("$uploads_dir/$nName");
            }
        }
        echo json_encode($resp);
        break;
    case 'cargar_examenes':
        $examenes = $examenM->cargar_examenes($_POST['curso'], $_POST['generacion'])['data'];
        $fecha_hoy = date("Y-m-d H:i:s");
        for ($i=0; $i < sizeof($examenes); $i++) { 
            $examenes[$i]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $examenes[$i]['idExamen'])['data'];
            $fecha_i = date($examenes[$i]['fechaInicio']);
            $fecha_f = date($examenes[$i]['fechaFin']);
            $h = strtotime($fecha_hoy);
            $fi = strtotime($fecha_i);
            $ff = strtotime($fecha_f);
            if($fi <= $h && $ff >= $h){
                $examenes[$i]['ontime'] = true;
            }else{
                $examenes[$i]['ontime'] = false;
            }
            $examenes[$i]['before'] = ($h < $fi) ? true : false;
        }
        echo json_encode($examenes);
        break;
    case 'salvado_automatico':
        $resp = [];
        if (isset($_POST['code'])) {
            $respuestas = preg_grep("/^preg_exm/i", array_keys($_POST));
            if(!empty($respuestas)){ // si el examen tiene alguna pregunta respondida
                $examen = explode('.', $_POST['code'])[0];
                $alumno = explode('.', $_POST['code'])[1];
                // consultar las  respuestas programadas para este examen
                $preguntas_resp = $examenM->cargar_preguntas_examen($examen)['data'];
                // print_r($preguntas_re8/-*p);
                $resultado = [];
                $correctas = 0;
                for ($i=0; $i < sizeof($respuestas); $i++) { // recorrer todas las preguntas contestadas
                    $elm_resp = [];
                    $elm_resp[0] = explode('-', $respuestas[$i])[1];
                    $elm_resp[1] = $_POST[$respuestas[$i]];
                    // consultar las opciones de la respuesta dada en el post
                    $opc = $examenM->cargar_pregunta_ID(explode('-', $respuestas[$i])[1])['data'];
                    $opc = json_decode($opc['opciones'], true);
                    // echo json_encode($_POST);
                    // die();
                    foreach ($opc as $key => $value) {
                        if( $key == $_POST[$respuestas[$i]]){
                            $elm_resp[2] = 0;
                            /*if($elm_resp[2] == 1){
                                $correctas++;
                            }*/
                        }
                    }
                    array_push($resultado, $elm_resp);
                    // echo(json_encode($elm_resp));
                }
                $d_insert = ['alumno'=>$alumno, 'examen'=>$examen, 'calificacion'=>-1,'respuestas'=>json_encode($resultado),'fecha'=>date('Y-m-d H-i-s')];
                // buscar si hay algun examen sin terminar
                $guardadas = $examenM->alumno_examen_respuestas($idusuario, $examen)['data'];
                if(sizeof($guardadas) > 0){
                    if($guardadas[0]['calificacion'] == -1){
                        $resp = $examenM->finalizar_examen($d_insert);
                    }
                }
                $resp = $d_insert;
            }else{
                $resp['estatus'] = 'error';
                $resp['info'] = 'no_respuestas';
            }
        }
        echo json_encode($resp);
        break;
    case 'cargar_respuestas_guardadas':
        $respuestas = [];
        if (isset($_POST['code'])) {
            $examen = explode('.', $_POST['code'])[0];
            $alumno = explode('.', $_POST['code'])[1];

            $guardadas = $examenM->alumno_examen_respuestas($idusuario, $examen)['data'];
            if(sizeof($guardadas) > 0 && intval($guardadas[0]['calificacion']) == -1){
                $guardadas = $guardadas[0];
                $respuestas_g = json_decode($guardadas['respuestas'], true);

                foreach ($respuestas_g as $key => $value) {
                    array_push($respuestas, [$value[0], $value[1]]);
                }
            }
        }
        echo json_encode($respuestas);
        break;
    case 'terminar_examen':
        $d_insert = [];
        $resp = [];
        if (isset($_POST['code'])) {
            $respuestas = preg_grep("/^preg_exm/i", array_keys($_POST));
            if(!empty($respuestas)){ // si el examen tiene alguna pregunta respondida
                $examen = explode('.', $_POST['code'])[0];
                $alumno = explode('.', $_POST['code'])[1];
                // consultar si el examen se responde en tiempo
                $fecha_hoy = date("Y-m-d H:i:s");
                $examen_info = $examenM->cargar_examen_id($examen)['data'];
                $fecha_f = date($examen_info['fechaFin']);
                $fh = strtotime($fecha_hoy);
                $ff = strtotime($fecha_f);
                if($fh > $ff){
                    $resp['estatus'] = 'error';
                    $resp['info'] = 'examen_vencido';
                }else{
                    // consultar las  respuestas programadas para este examen
                    $preguntas_resp = $examenM->cargar_preguntas_examen($examen)['data'];
                    $numero_preguntas = $examen_info['preguntas_aplicar'] !== null ? $examen_info['preguntas_aplicar'] : sizeof($preguntas_resp);
                    // print_r($preguntas_re8/-*p);
                    $resultado = [];
                    $correctas = 0;
                    for ($i=0; $i < sizeof($respuestas); $i++) { // recorrer todas las preguntas contestadas
                        $elm_resp = [];
                        $elm_resp[0] = explode('-', $respuestas[$i])[1];
                        $elm_resp[1] = $_POST[$respuestas[$i]];
                        // consultar las opciones de la respuesta dada en el post
                        $opc = $examenM->cargar_pregunta_ID(explode('-', $respuestas[$i])[1])['data'];
                        $opc = json_decode($opc['opciones'], true);
                        // echo json_encode($_POST);
                        // die();
                        foreach ($opc as $key => $value) {
                            if( $key == $_POST[$respuestas[$i]]){
                                $elm_resp[2] = $value;
                                if($elm_resp[2] == 1){
                                    $correctas++;
                                }
                            }
                        }
                        array_push($resultado, $elm_resp);
                        // echo(json_encode($elm_resp));
                    }
                    $d_insert = ['alumno'=>$alumno, 'examen'=>$examen, 'calificacion'=>((100/$numero_preguntas)*$correctas),'respuestas'=>json_encode($resultado),'fecha'=>date('Y-m-d H-i-s')];
                    $resp = $examenM->finalizar_examen($d_insert);
                }
            }else{
                $resp['estatus'] = 'error';
                $resp['info'] = 'no_respuestas';
            }
        }
        echo json_encode($resp);
        break;
        case 'cargar_materias':
            $resp = [];
            // el post CURSO contiene el id de la generación
            $gen_info = $generacionesM->buscarGeneracion($_POST['curso']);
            if(!$gen_info['data']){
                $resp = ['estatus'=>'error', 'info'=>'No se encontró información referente a la generación'];
            }else{
                if($gen_info['data']['id_plan_estudio'] == 0 || $gen_info['data']['id_plan_estudio'] === null){
                    $resp = ['estatus'=>'error', 'info'=>'No se encontró información referente al plan de estudio'];
                }else{
                    $materias_disp = $materiasCE->consultar_materias_plan_ciclo($gen_info['data']['id_plan_estudio'], $gen_info['data']['ciclo_actual']);
                    $gen_info['data']['materias_ciclo'] = $materias_disp['data'];
                    $resp = ['estatus'=>'ok', 'data'=>[$gen_info['data']]];
                }
            }
            echo json_encode($resp);
            break;
        case 'consultar_calificaciones':
            $resp = [];
                $obtener_numero_de_ciclos = $materiasCE->obtener_numero_de_ciclos($_POST['curso'], $_SESSION["alumno"]['id_prospecto']);
                $resp = ['estatus'=>'ok', 'data'=>[$obtener_numero_de_ciclos['data']]];
                foreach ($obtener_numero_de_ciclos['data'] as $key => $value) {
                    $materias_calificadas_periodo=$materiasCE->obtener_calificaciones_periodo($_POST['curso'], $_SESSION["alumno"]['id_prospecto'],$value['numero_ciclo']);
                    $resp['data'][0][$key]['materias_calificadas'] = $materias_calificadas_periodo['data'];
                    }
            echo json_encode($resp['data'][0]);
            break;
        case 'tipoCarrera':
            unset($_POST['action']);
            $obTipoCarrera = $generacionesM->obtenerTipoCarrera($_POST['id'])['data'];
            echo json_encode($obTipoCarrera);
            break;  
    	case 'aplicar_examen':
    	        
    	       
    	        
    	        $examenM = new Examen();
    	
		$exm = $examenM->cargar_examen_id($_POST['examen'])['data'];
		$preguntas = $examenM->cargar_preguntas_examen($_POST['examen'])['data'];

		//var_dump($preguntas[0]['opciones']);
  		$response = ["exam"=>[$exm],"ques"=>$preguntas];
  		echo json_encode($response);
    	break; 
    case 'validar_adeudos':
        $alumno = $_POST['idAlumno'];
        $generacion = $_POST['id'];
        $nacionalidad = $_POST['Nacion'];
        unset($_POST['Nacion']);

        $Bloqueosc = $generacionesM->validarBloqueo($alumno, $generacion);
        if($Bloqueosc['data']['estatus']=='2' || $Bloqueosc['data']['estatus']=='7'){
            switch($Bloqueosc['data']['estatus']){
                case '2':
                    echo "Alumno_con_baja";
                    break;
                case '7':
                    echo "Alumno_con_bloqueo";
                    break;
            };
        }else{
            $fecha_hoy = date("Y-m-d H:i:s");
            $adeudo_i = true;
            $adeudo_m = true;
            $adeudo_doc = false;
            $adeudo_doc_f = false;
            $alumno_becado=false;
            $estatus_alumno = $generacionesM->buscarAsignacion($alumno, $generacion);
            // echo json_encode($estatus_alumno['data']);
            // die();
            $baja = $estatus_alumno['data'][0]['estatus'] == "2";
            $validarbeca = $generacionesM->validarBecaAlumno($alumno, $generacion)['data'];
            
            $validarbecageneracion = $generacionesM->validarBecaGen($alumno, $generacion)['data'];
            $obConceptos = $generacionesM->obtenerConceptosPago($generacion)['data'];
            $promo_cien = false;
            $i=0;
            foreach($validarbecageneracion as $key => $val){
                $promo_alumno = array_search($val['categoria'], array_column($validarbeca, 'categoria'));
                if(!$promo_alumno){
                    array_push($validarbeca, $val);
                }
            }
            
            foreach ($validarbeca as $key => $value) {
                if ($value['categoria']=='Mensualidad' || $value['categoria']=='Inscripción') {
                    if ($value['porcentaje']==100) {
                        if ($value['categoria']=='Mensualidad'){
                            $adeudo_m = false;
                        }
                        if ($value['categoria']=='Inscripción'){
                            $adeudo_i = false;
                            $band_pagado = true;
                        }
                        $i++;
                    }
                }
            }
            //var_dump($band_pagado);
            if ($i>=2) {
                $alumno_becado = true;
            }
            // print_r($obConceptos);
            // die();
            $paga_mensualidades = false;
            foreach ($obConceptos as $key => $value) {
                if($value['categoria'] == 'Inscripción'){
                    $pagado = $generacionesM->consultar_pagos_concepto_alumno($alumno, $value['id_concepto']);
                    //var_dump($pagado);
                    $band_pagado = false;
                    foreach($pagado as $pago){
                        if($pago['promesa_de_pago'] != null || $pago['restante'] <= 1){
                            if($pago['restante'] <= 1){
                                $band_pagado = true;
                            }
                            $adeudo_i = false;
                        }
                        $promo_cien = false;
                        
                        if($pago['idPromocion'] != null && intval($pago['idPromocion']) > 0){
                            $info_promo = $generacionesM->info_promocion($pago['idPromocion']);
                            
                            if($info_promo && $info_promo['porcentaje'] == 100){
                                $adeudo_i = false;
                                $band_pagado = true;
                            }
                        }
                    }
                    //if(strtotime(date('Y-m-d')) > strtotime($value['fechalimitepago']) && $band_pagado == false){
                    //    $adeudo_i = true;
                    //}
                    
                    if($promo_cien){
                        $adeudo_i = false;
                    }
                }
                if($value['categoria'] == 'Mensualidad'){
                    $paga_mensualidades = true;
                    $f_ini = '';
                    $f_esp = $generacionesM->obtenerFechaAlumnoEspecial($alumno, $generacion)['data'];
                    if($f_esp['fecha_primer_colegiatura'] != null){
                        $f_ini = $f_esp['fecha_primer_colegiatura'];
                    }else{
                        $f_gen = $generacionesM->obtenerFechaGeneracion($generacion)['data']['fecha_inicio'];
                        $f_ini = substr($f_gen,0,8).explode('-', $value['fechalimitepago'])[2];
                    }
                    $pagado = $generacionesM->consultar_pagos_concepto_alumno($alumno, $value['id_concepto']);
                    // buscar si la promocion es dl 100 % entonces no volvera a hacer mas pagos
                    $promo_cien = false;
                    foreach($pagado as $pago){
                        if($pago['idPromocion'] != null && intval($pago['idPromocion']) > 0){
                            $info_promo = $generacionesM->info_promocion($pago['idPromocion']);
                            if($info_promo && $info_promo['porcentaje'] == 100){
                                $promo_cien = true;
                            }
                        }
                    }
                    $pagos_aplicados = sizeof($pagado);
                    $f_ini = substr($f_ini,0,10);
                    if($pagos_aplicados > 0){
                        $f_ini = date('Y-m-d', strtotime("+{$pagos_aplicados} month", strtotime($f_ini)));
                    }
                    // verificar si tiene una prorroga para el numero de pago actual
                    $consultar_p = $pagosM->validar_si_existe_prorroga($alumno, $value['id_concepto'], $pagos_aplicados + 1);
                    if($consultar_p['estatus'] == 'ok' && $consultar_p['data']){
                        // si el estatus de la prorroga es aprobado sobreescribe la fecha
                        if($consultar_p['data']['estatus'] == 'aprobado'){
                            $f_ini = $consultar_p['data']['nuevafechaaceptada'];
                        }
                    }
    
                    if($promo_cien){
                        $f_ini = date("Y-m-d");
                    }
                    if(strtotime($f_ini) >= strtotime(date("Y-m-d"))){
                        $adeudo_m = false;
                    }
                    // var_dump($f_ini);
                    // var_dump($f_ini.' > '.date("Y-m-d"));
                    // die();
                }
            }
    
            if(!$paga_mensualidades){
                $adeudo_m = false;
            }
            
            //add
            $validarbeca = $generacionesM->validarBecaAlumno($alumno, $generacion)['data'];
            foreach ($validarbeca as $key => $value) {
                if ($value['categoria']=='Mensualidad' || $value['categoria']=='Inscripción') {
                    if ($value['porcentaje']==100) {
                        if ($value['categoria']=='Mensualidad'){
                            $adeudo_m = false;
                        }
                        if ($value['categoria']=='Inscripción'){
                            $adeudo_i = false;
                            $band_pagado = true;
                        }
                        $i++;
                    }
                }
            }
            // add
            $estat_documentos = $controlEscM->documentos_generacion($generacion);
            foreach($estat_documentos as $key => $value){
                if($value['fecha_digital'] != null){
                    if(strtotime($fecha_hoy) > strtotime($value['fecha_digital'])){
                        // consultar si el alumno tiene una prorroga para el documento
                        $prorroga = $controlEscM->validar_prorroga_documento_alumno($_SESSION['alumno']['id_afiliado'], $value['id_documento']);
                        if($prorroga && $prorroga['fecha_prorroga_digital'] != null){
                            if(strtotime($fecha_hoy) > strtotime($prorroga['fecha_prorroga_digital'])){
                                // consultar si ya hizo entrega del documento
                                $entrega = $controlEscM->validar_documento_alumno($_SESSION['alumno']['id_afiliado'], $value['id_documento']);
                                
                                if($nacionalidad == "Mexicano"){
                                    if(!$entrega || $entrega['validacion'] != 1 && ($value['id_documento'] == 0 || $value['id_documento'] == 1)){
                                        $adeudo_doc = true;
                                        console.log("Mexicano");
                                    }
                                }else{
                                    if(!$entrega || $entrega['validacion'] != 1 && ($value['id_documento'] == 0 || $value['id_documento'] == 2)){
                                        $adeudo_doc = true;
                                        console.log("no Mexicano");
                                    }
                                }
                            }
                        }else{
                            // consultar si ya hizo entrega del documento
                            $entrega = $controlEscM->validar_documento_alumno($_SESSION['alumno']['id_afiliado'], $value['id_documento']);
                            if(!$entrega || $entrega['validacion'] != 1){
                                $adeudo_doc = true;
                            }
                        }
                    }
                }
                if($value['fecha_fisico'] != null){
                    if(strtotime($fecha_hoy) > strtotime($value['fecha_fisico'])){
                        // consultar si el alumno tiene una prorroga para el documento
                        $prorroga = $controlEscM->validar_prorroga_documento_alumno($_SESSION['alumno']['id_afiliado'], $value['id_documento']);
                        if($prorroga && $prorroga['fecha_prorroga_fisica'] != null){
                            if(strtotime($fecha_hoy) > strtotime($prorroga['fecha_prorroga_fisica'])){
                                // consultar si ya hizo entrega del documento
                                $entrega = $controlEscM->validar_documento_fisico_alumno($_SESSION['alumno']['id_afiliado'], $value['id_documento']);
                                
                                if(!$entrega ){
                                    $adeudo_doc_f = true;
                                }
                            }
                        }else{
                            // consultar si ya hizo entrega del documento
                            $entrega = $controlEscM->validar_documento_fisico_alumno($_SESSION['alumno']['id_afiliado'], $value['id_documento']);
                            if(!$entrega){
                                $adeudo_doc_f = true;
                            }
                        }
                    }
                }
            }
            //file_put_contents('debug_bloqueo.txt', var_dump([$adeudo_i, !$adeudo_m, !$adeudo_doc, !$adeudo_doc_f, $alumno_becado]));
            // $adeudo_i = false; // descomentese en caso de emergencias
            if(((!$adeudo_i && !$adeudo_m && !$adeudo_doc  && !$adeudo_doc_f) || (!$adeudo_doc  && !$adeudo_doc_f && $alumno_becado)) && !$baja){
                
                if(isset($_POST['android_id_prospecto'])){
                    echo json_encode(["data"=>'si']);
                }else{
                    echo 'si';
                }
            }else{
                if($adeudo_i){
                    if(isset($_POST['android_id_prospecto'])){
                        echo json_encode(["data"=>'no inscripcion']);
                    }else{
                        echo 'no inscripcion';
    
                    }
                }else if($adeudo_m){
                    if(isset($_POST['android_id_prospecto'])){
                        echo json_encode(["data"=>'no mensualidad']);
                    }else{
                        echo 'no mensualidad';
    
                    }
                    echo 'no mensualidad';
                }else if($adeudo_doc){
                    if(isset($_POST['android_id_prospecto'])){
                        echo json_encode(["data"=>'no documentos']);
                    }else{
                        echo 'no documentos';
    
                    }
                    
                }else if($adeudo_doc_f){
                    if(isset($_POST['android_id_prospecto'])){
                        echo json_encode(["data"=>'no documentos fisicos']);
                    }else{
                        echo 'no documentos fisicos';
    
                    }
                }else if($baja){
                    if(isset($_POST['android_id_prospecto'])){
                        echo json_encode(["data"=>'baja']);
                    }else{
                        echo 'baja';
    
                    }
                }
            }
        }

        break;      
    default:
        # code...
        break;
    }

//}else{
//    header("Location: ../../index.php");
//}
