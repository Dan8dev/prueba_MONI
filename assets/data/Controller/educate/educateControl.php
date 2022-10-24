<?php
session_start();
if(isset($_POST['action'])){

    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/educate/educateModel.php';
    require_once "../../Model/planpagos/generacionesModel.php";
    require_once "../../Model/controlescolar/examenModel.php";
    require_once '../../Model/controlescolar/materiasModel.php';
    require_once '../../Model/controlescolar/controlEscolarModel.php';

    $generacionesM = new Generaciones();
    $blogs = new ContentEducate();
    $examenM = new Examen();
    $mtria = new Materias();
    $ce = new ControlEscolar();
    $ServidorURL = "http://sandboxmoni.com.mx/assets/images/";

    if(!isset($_SESSION['usuario']) && !isset($_SESSION['alumno']) && !isset($_POST['android_id_afiliado'])){
        $_POST['action'] = 'no_session';
    }
    
    $idusuario = 541;
    
    if(isset($_SESSION["alumno"]['id_prospecto'])){
        $idusuario = $_SESSION['alumno']['id_prospecto'];
    }
    
    if(isset($_POST['idalumno']) && $_POST['idalumno'] != null && $_POST['idalumno'] != ''){
        $idusuario = $_POST['idalumno'];
        unset($_POST['idalumno']);
    }
    if(isset($_POST['android_id_afiliado'])){
        $idusuario = $_POST['android_id_afiliado'];
    }

    $accion=@$_POST["action"];
    switch($accion){

        case 'pago_cursos': // consultar carreras con generaciones asignadas al alumno
    
            echo json_encode($generacionesM->generaciones_alumno($idusuario));
        break;
        case 'saveCommits':
            unset($_POST['action']);
            $_POST['idUs'] = $idusuario;
            $_POST['dateC'] = date('Y-m-d H:i:s');
            $datas = $blogs->saveCommits($_POST);
            echo json_encode($datas);
        break;
        case 'getCommits':
            unset($_POST['action']);
            $idClass = $_POST['idClass'];
            $gen = $_POST['gen'];
            $datas = $blogs->getCommits($idClass,$gen);
            echo json_encode($datas);
        break;
        case 'saveBlogs':
            unset($_POST['action']);
            $_POST['dateC'] = date('Y-m-d H:i:s');
            $idAssign = $_POST['idAssign'];
            $date = date('Y-m-d H:i:s');
            $arrayNames = [];

            if(isset($_POST['oldfiles']) && $_POST['oldfiles'] != ''){

                $oldfiles = json_decode($_POST['oldfiles']);
                foreach($oldfiles as $key){
                    array_push($arrayNames,$key);
                }
            }

            if(isset($_FILES["archivo"]['tmp_name'])){
                foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
                {
                        //Validamos que el archivo exista
                        if($_FILES["archivo"]["name"][$key]) {
                            
                            $fileT = explode('.', $_FILES["archivo"]["name"][$key]);	 //Obtenemos el nombre original del archivo
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            
                            //$nName = 'pdf_'.$date.'.'.$extensionFI;
                            $source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
                            
                            $directorio = '../../../files/educate/'.$idAssign.'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
                            
                            //Validamos si la ruta de destino existe, en caso de no existir la creamos
                            if(!file_exists($directorio)){
                                mkdir($directorio, 0777,true) or die("No se puede crear el directorio de extracci&oacute;n");	
                            }
    
                            $dir=opendir($directorio); //Abrimos el directorio de destino
                            $target_path = $directorio. $_FILES["archivo"]["name"][$key]; //Indicamos la ruta de destino, así como el nombre del archivo
                            
                            //Movemos y validamos que el archivo se haya cargado correctamente
                            //El primer campo es el origen y el segundo el destino
                            if(move_uploaded_file($source, $target_path)) {	
                                array_push($arrayNames, $_FILES["archivo"]["name"][$key]);
                            }
                            closedir($dir); //Cerramos el directorio de destino
                        }
                }
            }

            unset($_POST['oldfiles']);
            //var_dump($oldfiles);
            $_POST['files'] = $arrayNames;

            $datas = $blogs->saveBlogs($_POST);

            echo json_encode($datas);
        break;
        case 'deleteFilesBlogs':
            unset($_POST['action']);

            $oldfiles = json_decode($_POST['oldfiles']);
            $index = $_POST['index'];
            foreach($oldfiles as $key){

               unset($oldfiles[$index]);
            }

            array_splice($oldfiles,0,0);
            unset($_POST['index']);
            unset($_POST['oldfiles']);
            $_POST['files'] = json_encode($oldfiles);

            $datas = $blogs->deleteFilesBlogs($_POST); 
            
            //var_dump($_POST);
            echo json_encode($datas);
        break;
        case 'updateCarrerMat':
            unset($_POST['action']);
            $_POST['dateC'] = date('Y-m-d H:i:s');
            $datas = $blogs->updateContent($_POST, $_FILES);
            echo json_encode($datas);
        break;
        case 'terminar_examen':
            $d_insert = [];
            $resp = [];
            if(isset($_POST['tExm'])){
                $tExm = $_POST['tExm'];
            }else{
                $tExm = 1;
            }
            if (isset($_POST['code'])) {
                $respuestas = preg_grep("/^preg_exm/i", array_keys($_POST));
                if(!empty($respuestas)){ // si el examen tiene alguna pregunta respondida
                    $examen = explode('.', $_POST['code'])[0];
                    $alumno = explode('.', $_POST['code'])[1];
                    // consultar si el examen se responde en tiempo
                    $fecha_hoy = date("Y-m-d H:i:s");
                    $examen_info = $examenM->cargar_examen_id($examen)['data'];
                    $exm_preg = intval($examen_info['Examen_ref']) > 0 ? $examen_info['Examen_ref'] : $examen;
    
                    $fecha_f = date($examen_info['fechaFin']);
                    $fh = strtotime($fecha_hoy);
                    $ff = strtotime($fecha_f);
                    if($fh > $ff && $tExm != 3){
                        $resp['estatus'] = 'error';
                        $resp['info'] = 'examen_vencido';
                    }else{
                        // consultar las  respuestas programadas para este examen
                        $preguntas_resp = $examenM->cargar_preguntas_examen($exm_preg)['data'];
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
        case 'validar_examenExtra':
        
            unset($_POST['action']);
            
            $idusuario = $_POST['idAlumno'];

            $examenes = $examenM->getExamnExtra($idusuario,'')['data'];
            
            

                $fecha_hoy = date("Y-m-d H:i:s");
            for ($i=0; $i < sizeof($examenes); $i++) { 
                $examenes[$i]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $examenes[$i]['idExamen'])['data'];
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
            
            $data = ['estatus'=>'ok', 'data'=>$examenes];

            echo json_encode($data);
        
        break;
        case 'updateOrder':
            unset($_POST['action']);
            $datas = $blogs->updateOrder($_POST);
            echo json_encode($datas);
        break;
        case 'deleteContent':

            unset($_POST['action']);
    
            $delete = $blogs->deleteContent($_POST);
    
            echo json_encode($delete);
        break;
        case 'enviar_tarea':
            $resp = [];
            if((isset($_POST['tarea_entrega']) && intval($_POST['tarea_entrega']) > 0) && (isset($_FILES['inp_adjunto_tarea']) && $_FILES['inp_adjunto_tarea']['error'] == 0)){
                $tmp_name = $_FILES["inp_adjunto_tarea"]["tmp_name"];
                $uploads_dir = "../../../files/clases/tareas/clase_".$_POST['clase_tarea'];
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

                    if(isset($_POST['act'])){
                        $type = 'actividad';
                    }else{
                        $type = 'tarea';
                    }
                    $datos = ['tarea'=> $_POST['tarea_entrega'],'alumno'=> $idusuario,'archivo'=> $nName,'comentario'=> $_POST['inp_comentario_tarea'],'type'=>$type];
                    $insert = $mtria->entregar_tareas($datos);
                    $resp = $insert;
                }else{
                    $resp = ['estatus'=>'error', 'info'=>'error_al_adjuntar_tarea'];
                    unlink("$uploads_dir/$nName");
                }
            }
            echo json_encode($resp);
        break;
        case 'cargar_materias':
            $resp = [];
            $examenes = [];
            
            if(isset($_POST['edukt']) && $_POST['edukt'] != ''){
                $edukt = $_POST['edukt'];
            }else{
                $edukt = '';
            }
           $fecha_hoy = date("Y-m-d H:i:s");
            // el post CURSO contiene el id de la generación
            $gen_info = $generacionesM->buscarGeneracion($_POST['curso']);
            if(!$gen_info['data']){
                $resp = ['estatus'=>'error', 'info'=>'No se encontró información referente a la generación'];
            }else{
                if($gen_info['data']['id_plan_estudio'] == 0 || $gen_info['data']['id_plan_estudio'] === null){
                    //$resp = ['estatus'=>'error', 'info'=>'No se encontró información referente al plan de estudio'];
                    $materias_disp = $blogs->consultar_materias_carrera($gen_info['data']['idCarrera'], $gen_info['data']['ciclo_actual']);
                    
                    foreach($materias_disp['data'] as $key => $value){
                        
                        if($materias_disp['data'][$key]['imgc'] != null && $materias_disp['data'][$key]['imgc'] != '' && file_exists('../../../files/educate/'.$materias_disp['data'][$key]['imgc'])){
                            $materias_disp[$key]['imgc'] = $ServidorURL.'educate/'.$carreras[$key]['imagen'];
                        }else{
                            $materias_disp[$key]['imgc'] = $ServidorURL.'default-1.png';
                        }
                         $materias_disp['data'][$key]['clases'] = $mtria->cursoClases($value['id_materia'], $_POST['curso'])['data'];
                         $materias_disp['data'][$key]['extra_clases'] = $blogs->blogs($value['id_materia'])['data'];
                         $materias_disp['data'][$key]['examenes'] = $blogs->getExm($value['id_materia'],'')['data'];
                         $materias_disp['data'][$key]['bloques'] = $blogs->getIDBl($value['id_materia'],'')['data'];
                        for ($j=0; $j < sizeof( $materias_disp['data'][$key]['examenes']); $j++) { 
                            $materias_disp['data'][$key]['examenes'][$j]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $materias_disp['data'][$key]['examenes'][$j]['idExamen'])['data'];
                            $fecha_i = date($materias_disp['data'][$key]['examenes'][$j]['fechaInicio']);
                            $fecha_f = date($materias_disp['data'][$key]['examenes'][$j]['fechaFin']);
                            $h = strtotime($fecha_hoy);
                            $fi = strtotime($fecha_i);
                            $ff = strtotime($fecha_f);
                            if($fi <= $h && $ff >= $h){
                                $materias_disp['data'][$key]['examenes'][$j]['ontime'] = true;
                            }else{
                                $materias_disp['data'][$key]['examenes'][$j]['ontime'] = false;
                            }
                            $materias_disp['data'][$key]['examenes'][$j]['before'] = ($h < $fi) ? true : false;
                        }
                    }
                    $gen_info['data']['materias_ciclo'] = $materias_disp['data'];
                    $gen_info['data']['examenes'] = $blogs->getExm($_POST['curso'],$edukt)['data'];
                    $gen_info['data']['maestros'] = $blogs->getMaestros($_POST['curso'])['data'];
                    //echo count($gen_info['data']['examenes']);
                    	foreach ($gen_info['data']['examenes'] as $key=>$value) {
                            if($value['tipo_examen'] == 2){ 
                                $examenes = $examenM->getExamnExtra($idusuario,$value['idExamen']);
                                if($examenes['count'] <= 0){
                                    unset($gen_info['data']['examenes'][$key]);
                                }
                            }
                        }
                          array_splice($gen_info['data']['examenes'],0,0);
                          //var_dump($gen_info['data']['examenes']);
                       for ($j=0; $j < sizeof( $gen_info['data']['examenes']); $j++) {
                            $gen_info['data']['examenes'][$j]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $gen_info['data']['examenes'][$j]['idExamen'])['data'];
                            $fecha_i = date($gen_info['data']['examenes'][$j]['fechaInicio']);
                            $fecha_f = date($gen_info['data']['examenes'][$j]['fechaFin']);
                            $h = strtotime($fecha_hoy);
                            $fi = strtotime($fecha_i);
                            $ff = strtotime($fecha_f);
                            if($fi <= $h && $ff >= $h){
                                $gen_info['data']['examenes'][$j]['ontime'] = true;
                            }else{
                                $gen_info['data']['examenes'][$j]['ontime'] = false;
                            }
                            $gen_info['data']['examenes'][$j]['before'] = ($h < $fi) ? true : false;
                        }
                    $resp = ['estatus'=>'ok', 'data'=>$gen_info['data'],'vals'=>$examenes];
                }else{
                    if(isset($_POST['panel'])  && $_POST['panel'] == 'moni'){
                        $materias_disp = $blogs->consultar_materias_carrera($gen_info['data']['idCarrera'], $gen_info['data']['ciclo_actual']);  
                    }else{
                        $materias_disp = $mtria->consultar_materias_plan_ciclo($gen_info['data']['id_plan_estudio'], $gen_info['data']['ciclo_actual']);
                    }
                    foreach($materias_disp['data'] as $key => $value){
                        $materias_disp['data'][$key]['clases'] = $mtria->cursoClases($value['id_materia'], $_POST['curso'])['data'];
                         $materias_disp['data'][$key]['extra_clases'] = $blogs->blogs($value['id_materia'])['data'];
                         $materias_disp['data'][$key]['examenes'] = $blogs->getExm($value['id_materia'],'')['data'];
                         $materias_disp['data'][$key]['bloques'] = $blogs->getIDBl($value['id_materia'],'')['data'];   
                        for ($j=0; $j < sizeof( $materias_disp['data'][$key]['examenes']); $j++) { 
                            $materias_disp['data'][$key]['examenes'][$j]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $materias_disp['data'][$key]['examenes'][$j]['idExamen'])['data'];
                            $fecha_i = date($materias_disp['data'][$key]['examenes'][$j]['fechaInicio']);
                            $fecha_f = date($materias_disp['data'][$key]['examenes'][$j]['fechaFin']);
                            $h = strtotime($fecha_hoy);
                            $fi = strtotime($fecha_i);
                            $ff = strtotime($fecha_f);
                            if($fi <= $h && $ff >= $h){
                                $materias_disp['data'][$key]['examenes'][$j]['ontime'] = true;
                            }else{
                                $materias_disp['data'][$key]['examenes'][$j]['ontime'] = false;
                            }
                            $materias_disp['data'][$key]['examenes'][$j]['before'] = ($h < $fi) ? true : false;
                        }
                    }
                    $gen_info['data']['materias_ciclo'] = $materias_disp['data'];
                    $gen_info['data']['examenes'] = $blogs->getExm($_POST['curso'],$edukt)['data'];
                    $gen_info['data']['maestros'] = $blogs->getMaestros($_POST['curso'])['data'];
                    //echo count($gen_info['data']['examenes']);
                    	foreach ($gen_info['data']['examenes'] as $key=>$value) {
                            if($value['tipo_examen'] == 2){
                                $examenes = $examenM->getExamnExtra($idusuario,$value['idExamen']);
                                if($examenes['count'] <= 0){
                                    unset($gen_info['data']['examenes'][$key]);
                                }
                            }
                        }
                          array_splice($gen_info['data']['examenes'],0,0);
                          //var_dump($gen_info['data']['examenes']);
                       for ($j=0; $j < sizeof( $gen_info['data']['examenes']); $j++) {
                            $gen_info['data']['examenes'][$j]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $gen_info['data']['examenes'][$j]['idExamen'])['data'];
                            $fecha_i = date($gen_info['data']['examenes'][$j]['fechaInicio']);
                            $fecha_f = date($gen_info['data']['examenes'][$j]['fechaFin']);
                            $h = strtotime($fecha_hoy);
                            $fi = strtotime($fecha_i);
                            $ff = strtotime($fecha_f);
                            if($fi <= $h && $ff >= $h){
                                $gen_info['data']['examenes'][$j]['ontime'] = true;
                            }else{
                                $gen_info['data']['examenes'][$j]['ontime'] = false;
                            }
                            $gen_info['data']['examenes'][$j]['before'] = ($h < $fi) ? true : false;
                        }
                    $resp = ['estatus'=>'ok', 'data'=>$gen_info['data'],'vals'=>$examenes,""];
                }
            }
            echo json_encode($resp);
        break;
        case 'cargar_clases':
            $resp = [];
            if(isset($_POST['materia']) && isset($_POST['generacion'])){
                $clases = $mtria->cursoClases($_POST['materia'], $_POST['generacion']);
                for ($i=0; $i < sizeof($clases['data']); $i++) { 
                    $clases['data'][$i]['tareas'] = $mtria->tareasClase($clases['data'][$i]['idClase'])['data'];
                    $clases['data'][$i]['examenes'] = $examenM->cargar_examenes($_POST['materia'],'')['data'];
                    for ($j=0; $j < sizeof($clases['data'][$i]['tareas']); $j++) { 
                        //print_r($clases['data'][$i]['tareas'][$j])."\n";
                        if($clases['data'][$i]['tareas'][$j]['fecha_limite'] <  date('Y-m-d H:i:s')){
                            $clases['data'][$i]['tareas'][$j]['flag'] = 'fuera de limite';
                        }else{
                            $clases['data'][$i]['tareas'][$j]['flag'] = 'en tiempo';
                        }
                    }
                    if($clases['data'][$i]['apoyo'] != ''){
                        $clases['data'][$i]['apoyo'] = json_decode($clases['data'][$i]['apoyo']);
                    }else{
                        $clases['data'][$i]['apoyo'] = [];
                    }
                    if($clases['data'][$i]['recursos'] != ''){
                        $clases['data'][$i]['recursos'] = json_decode($clases['data'][$i]['recursos']);
                    }else{
                        $clases['data'][$i]['recursos'] = [];
                    }
                    $fecha_hoy = date("Y-m-d H:i:s");
                    for ($j=0; $j < sizeof($clases['data'][$i]['examenes']); $j++) { 
                        $clases['data'][$i]['examenes'][$j]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $clases['data'][$i]['examenes'][$j]['idExamen'])['data'];
                        $fecha_i = date($clases['data'][$i]['examenes'][$j]['fechaInicio']);
                        $fecha_f = date($clases['data'][$i]['examenes'][$j]['fechaFin']);
                        $h = strtotime($fecha_hoy);
                        $fi = strtotime($fecha_i);
                        $ff = strtotime($fecha_f);
                        if($fi <= $h && $ff >= $h){
                            $clases['data'][$i]['examenes'][$j]['ontime'] = true;
                        }else{
                            $clases['data'][$i]['examenes'][$j]['ontime'] = false;
                        }
                        $clases['data'][$i]['examenes'][$j]['before'] = ($h < $fi) ? true : false;
                    }
                    for ($j=0; $j < sizeof($clases['data'][$i]['tareas']); $j++) { 
                        // print_r($clases['data'][$i]['tareas'][$j])."\n";
                        $clases['data'][$i]['tareas'][$j]['entregas'] = $mtria->obtener_info_tarea_entrega($clases['data'][$i]['tareas'][$j]['idTareas'], $idusuario)['data'];
                    }
                }
                $blogs = $blogs->blogs($_POST['materia'])['data'];
                $resp = ['estatus'=>'ok', 'data'=>$clases['data'],'blogs'=>$blogs];
            }else{
                $resp = ['estatus'=>'error','info'=>'materia_no_valida'];
            }
            echo json_encode($resp);
        break;
        case 'getClass':
	        unset($_POST['action']);
            $idM = $_POST['idBuscar'];
            $mats = $blogs->getClass($idM)['data'];
            $exm = $blogs->getExm($idM,'')['data'];
            $bl = $blogs->getIDBl($idM)['data'];
            $datas = ['estatus'=> 'ok','data'=>['mat'=>$mats,'exm'=>$exm,'bloq'=>$bl]];
            echo json_encode($datas);
        break;
        case 'listarCarrerasEduc':
            unset($_POST['action']);
            $carreras= $blogs->buscarCarrerasEduc();
            foreach($carreras as $key=>$value){
             	$carreras[$key]['idGn'] = $blogs->getGen($value['idCarrera'])['data'];
                $carreras[$key]['exm'] = $blogs->getExm($carreras[$key]['idGn'],1)['data'];
                if(file_exists("../../../images/educate/".$carreras[$key]['imagen'])){
                    $carreras[$key]['imagen'] = $ServidorURL.'educate/'.$carreras[$key]['imagen'];
                }else{
                    $carreras[$key]['imagen'] = $ServidorURL.'default-1.png';
                }
            }
            echo json_encode($carreras);
        break;
        case 'asignarPreguntas':
            unset($_POST['action']);

            $opciones = "ABCD";
            $val = 0;
            $resp = 0;
            $cantidadPreguntas = 0;
            $preguntas = [];
            $respuestas = [];

            for($z = 0; $z <= count($_POST); $z++){
                if(isset($_POST['preguntaExamen'.$z])){
                    $cantidadPreguntas++;
                }
            }
            
            for($k = 0 ; $k < $cantidadPreguntas; $k++){
                if($_POST['preguntaExamen'.$k] != ''){
                    $preguntas[$k] = $_POST['preguntaExamen'.$k];
                    for($l = 0 ; $l < 4 ; $l++){
                        if($_POST['TextoOpcionExamen'.$k.'_'.$opciones[$l]] != ''){
                            if($_POST['OpcionExamen'.$k] == $opciones[$l]){
                                $val = 1;
                            }else{
                                $val = 0;
                            }
                            $respuestas[$k][$_POST['TextoOpcionExamen'.$k.'_'.$opciones[$l]]] = $val;
                        }
                    }
                }
            }

            $obIdPreguntas = $blogs->buscarIdPregunta($_POST['idExamen'])['data'];
            for($p = 0; $p < $cantidadPreguntas; $p++){
                $json = json_encode($respuestas[$p]);

                if(isset($obIdPreguntas[$p])){
                    $datas = $blogs->editarPreguntaExamen($obIdPreguntas[$p]['idPregunta'], $_POST['idExamen'], $preguntas[$p], $json);
                }else{
                    $datas = $blogs->setQuiz($_POST['idExamen'], $preguntas[$p], $json);
                } 
            }

            echo json_encode($datas);
        break;
        case 'agregarQuiz':
            unset($_POST['action']);

            $datas = $blogs->setExm($_POST);
            echo json_encode($datas);
        
        break;
        case 'getQuiz':

            $datas = $blogs->buscarIdPregunta($_POST['idExamen'])['data'];
            echo json_encode($datas);
        break;
        case 'selectuser':
            unset($_POST['action']);
            
            $id = $_SESSION["usuario"]['idPersona'];
            $type = $_SESSION["usuario"]['idTipo_Persona'];
            $loadAlumnos = $blogs->getUsers($id,$type);
            $data = Array();
            $dataN = array();
            //var_dump($loadAlumnos);
            while($dato=$loadAlumnos->fetchObject()){
            
                $boton = '<button class="btn btn-secondary editb" onclick="editUs('.$dato->id.', 3)">Editar</button>';
                $boton .= $dato->estado == 1 ? '<button class="btn btn-primary" onclick="editUs('.$dato->id.', 0)">Desactivar</button>': '<button class="btn btn-info" onclick="editUs('.$dato->id.', 1)">Activar</button>';
               
               if($dato->estatus_acceso == 3){
               	$rol = 'Desarrollador de contenido';
               }else{ $rol = 'Administrativo';}
                $data[]=array(
                0=> $dato->nombres,
                1=> $dato->email,
                2=> $rol,
                3=> $boton
                );
                $dataN[]=array(
                    'idPersona'=>$dato->id,
                    'nombres'=> $dato->nombres,
                    'email'=> $dato->email,
                    'estado'=>$dato->estatus_acceso
                    );
            }

            //var_dump($data);
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data,
                'adata'=>$dataN
            );
            echo json_encode($result);
        break;
        case 'createUs':
            $resp = [];
            unset($_POST['action']);
            $_POST['idUsM'] = $_SESSION["usuario"]['idPersona'];
            $_POST['idTP'] = $_SESSION["usuario"]['idTipo_Persona'];
            if(isset($_POST['dpto'])){
                $_POST['statusD'] = 'active';
            }
            $datas = $blogs->setUsers($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;
        case 'listarMaterias':
            unset($_POST['action']);
            $materias= $blogs->listarMaterias($_POST['idPlan']);
            echo json_encode($materias);
        break;
        case 'no_session':
            echo 'no_session';
            break;

        default:
            # code...
        break;

    }
}




?>