<?php  
session_start();
if (isset($_POST["action"]) && (isset($_SESSION["alumno"]) || isset($_POST['android_id_afiliado']) || isset($_POST['android_id_prospecto']))) {
    date_default_timezone_set("America/Mexico_City");

require "../Model/materiasModel.php";
require "../Model/examenModel.php";
require "../../../../assets/data/Model/planpagos/generacionesModel.php";
require "../../../../assets/data/Model/controlescolar/materiasModel.php";

// $idusuario=isset($_SESSION['alumno']['id_afiliado']) ? $_SESSION['alumno']['id_afiliado'] : $_POST['android_id_afiliado'];
// $idprospecto=isset($_SESSION['alumno']['id_prospecto']) ? $_SESSION['alumno']['id_prospecto'] : $_POST['android_id_prospecto'];

$idusuario=isset($_POST['android_id_afiliado']) ? $_POST['android_id_afiliado'] : $_SESSION['alumno']['id_afiliado'] ;
$idusuario=isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
$idprospecto=isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];

$materiaM = new Materia();
$examenM = new Examen();
$generacionesM = new Generaciones();

$materiasCE = new Materias(); // Modelos de control Escolar

switch ($_POST['action']) {
    case 'cargar_clases':
        $resp = [];
        if(isset($_POST['materia']) && isset($_POST['generacion'])){
            
            $clases = $materiaM->cursoClases($_POST['materia'], $_POST['generacion']);
            for ($i=0; $i < sizeof($clases['data']); $i++) { 
                $clases['data'][$i]['tareas'] = $materiaM->tareasClase($clases['data'][$i]['idClase'])['data'];
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
            $uploads_dir = "../../documents/tareas/clase_".$_POST['clase_tarea'];
            if(!is_dir($uploads_dir)){
                mkdir($uploads_dir, 0777, true);
            }

            $name = basename($_FILES["inp_adjunto_tarea"]["name"]);
            $fileT = explode(".", $_FILES["inp_adjunto_tarea"]["name"])[1];
            $nName = 'A'.$idusuario.'_C'.$_POST['clase_tarea'].'_T'.$_POST['tarea_entrega'].'_'.date("Y-m-d_H-i-s").".".$fileT;
            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");

            // despues de haber movido el archivo se actualiza el nombre del archivo para que sea ruta complenta http
            if($_SERVER['SERVER_NAME'] == 'localhost'){
                $nName = 'http://'.$_SERVER['SERVER_NAME']."/moni/siscon/app/documents/tareas/clase_".$_POST['clase_tarea'].'/'.$nName;
            }else{
                $nName = 'https://'.$_SERVER['SERVER_NAME']."/moni/siscon/app/documents/tareas/clase_".$_POST['clase_tarea'].'/'.$nName;
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
        $examenes = $examenM->cargar_examenes($_POST['curso'])['data'];
        for ($i=0; $i < sizeof($examenes); $i++) { 
            $examenes[$i]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $examenes[$i]['idExamen'])['data'];

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
                            $elm_resp[2] = $value;
                            if($elm_resp[2] == 1){
                                $correctas++;
                            }
                        }
                    }
                    array_push($resultado, $elm_resp);
                    // echo(json_encode($elm_resp));
                }
                $d_insert = ['alumno'=>$alumno, 'examen'=>$examen, 'calificacion'=>((100/sizeof($preguntas_resp))*$correctas),'respuestas'=>json_encode($resultado),'fecha'=>date('Y-m-d H-i-s')];
                $resp = $examenM->finalizar_examen($d_insert);
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
                    $resp = ['estatus'=>'ok', 'data'=>$gen_info['data']];
                }
            }
            echo json_encode($resp);
            break;
    default:
        # code...
        break;
    }

}else{
    header("Location: ../../index.php");
}
