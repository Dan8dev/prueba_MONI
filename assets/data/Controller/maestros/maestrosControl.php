<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/maestros/maestrosModel.php';

    require_once '../../Model/controlescolar/controlEscolarModel.php';
    require_once '../../Model/controlescolar/planEstudiosModel.php';
    require_once '../../Model/acceso/accesoModel.php';
    $accM = new Acceso();
    $master = new Maestro();
    $ceM = new ControlEscolar();
    $peM = new PlanEstudios();
    
    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }

    switch($_POST['action']){

        case 'obtenerDatosExamen':
            unset($_POST['action']);
            $obExamen = $master->obtenerDatosExamen($_POST)['data'];
            $obExamen['fechaInicio'] = date("Y-m-d", strtotime($obExamen['fechaInicio']));
            $obExamen['fechaFin'] = date("Y-m-d", strtotime($obExamen['fechaFin']));
            echo json_encode($obExamen);
            break;

        case 'obtenerMateriasDocente':
            unset($_POST['action']);
            $obMaterias = $master->obtenerMateriasDocente($_POST)['data'];
            echo json_encode($obMaterias);
            break;

        case 'obtenerPreguntasExamen':
            unset($_POST['action']);
            $obPreguntas = $master->obtenerPreguntasExamen($_POST)['data'];
            echo json_encode($obPreguntas);
            break;

        case 'editarExamen':
            unset($_POST['action']);
            if(empty($_POST['editTotalPregExamen'])){
                $_POST['editTotalPregExamen'] = NULL;
            }
                
                $opciones = "ABCD";
                $val = 0;
                $resp = 0;
                $cantidadPreguntas = 0;
                $preguntas = [];
                $respuestas = [];
                for($z = 0; $z <= count($_POST); $z++){
                    if(isset($_POST['pregunta'.$z])){
                        $cantidadPreguntas++;
                    }
                }

                for($k = 0 ; $k < $cantidadPreguntas; $k++){
                    if($_POST['pregunta'.$k] != ''){
                        $preguntas[$k] = $_POST['pregunta'.$k];
                        for($l = 0 ; $l < 4 ; $l++){
                            if($_POST['TextoOpcion'.$k.'_'.$opciones[$l]] != ''){
                                if($_POST['Opcion'.$k] == $opciones[$l]){
                                    $val = 1;
                                }else{
                                    $val = 0;
                                }
                                $respuestas[$k][$_POST['TextoOpcion'.$k.'_'.$opciones[$l]]] = $val;
                            }
                        }
                    }
                }

                if($_POST['editTotalPregExamen'] <= $cantidadPreguntas){
                    $preguntasAplicar = $master->asignarCantidadPreguntasAplicar($_POST['idExamen'], $_POST['editTotalPregExamen']);
                
                    $obIdPreguntas = $master->buscarIdPregunta($_POST['idExamen'])['data'];
                    for($p = 0; $p < $cantidadPreguntas; $p++){
                        $json = json_encode($respuestas[$p]);
                    
                        if(isset($obIdPreguntas[$p])){
                            $ultimoIdPregunta = $master->editarPreguntaExamen($obIdPreguntas[$p]['idPregunta'], $_POST['idExamen'], $preguntas[$p], $json);
                        }else{
                            //Validar que la pregunta no se repita...
                            $Validacion = $master->insertarPreguntaExamen($_POST['idExamen'], $preguntas[$p], $json,"validar");
                            if(isset($Validacion['data']) && $Validacion['data']>0){
                                $ultimoIdPregunta = 'preguntas_repetida';   
                            }else{
                                $ultimoIdPregunta = $master->insertarPreguntaExamen($_POST['idExamen'], $preguntas[$p], $json,"insertar");
                            }
                        }
                    }
                    if($ultimoIdPregunta == 'preguntas_repetida'){
                        echo $ultimoIdPregunta;
                    }else{
                        echo json_encode($ultimoIdPregunta);
                    }
                }else{
                    echo 'preguntas_aplicar';
                }
            break;

        case 'obtenerDatosTarea':
            unset($_POST['action']);
            $obDataHome = $master->obtenerDatosTarea($_POST)['data'];
            $obDataHome['fecha'] = date('Y-m-d', strtotime($obDataHome['fecha_limite']));
            $obDataHome['hora'] = date('H:i:s', strtotime($obDataHome['fecha_limite']));
            echo json_encode($obDataHome);
            break;

        case 'obtenerClasesDocente':
            unset($_POST['action']);
            $obClass = $master->obtenerClasesDocente($_POST)['data'];
            if($obClass ==  []){
                echo 'sin_clases';
            }else{
                echo json_encode($obClass);
            }
            break;

        case 'editarTarea':
            unset($_POST['action']);
            $_POST['fecha_limite'] = date('Y-m-d H:i:s', strtotime($_POST['editFechaLimiteTarea'].",".$_POST['editHoraLimiteTarea']));
            unset($_POST['editFechaLimiteTarea']);
            unset($_POST['editHoraLimiteTarea']);
            $editTarea = $master->editarTarea($_POST);
            echo json_encode($editTarea);
            break;

        case 'listaClases':
            unset($_POST['action']);
            $obListaClases = $master->obtenerListaClases($_POST)['data'];
            if($obListaClases ==  []){
                echo 'sin_clases';
            }else{
                echo json_encode($obListaClases);
            }
            break;

        case 'crearTarea':
            unset($_POST['action']);
            $_POST['fecha_limite'] = date('Y-m-d H:i:s', strtotime($_POST['fechaLimiteTarea'].",".$_POST['horaLimiteTarea']));
            //var_dump($_POST['fecha_limite']);
            //die();
            unset($_POST['fechaLimiteTarea']);
            unset($_POST['horaLimiteTarea']);
            //var_dump($_POST);
            //die();
            $createHomework = $master->crearTarea($_POST);
            echo json_encode($createHomework);
            break;

        case 'obtenerListaTareas':
            unset($_POST['action']);
            $listaTareas = $master->obtenerListaTareas($_POST);
            $data = Array();
            while($dato=$listaTareas->fetchObject()){
                $data[]=array(
                    0=> $dato->nombre,
                    1=> $dato->tarea,
                    2=> $dato->titulo,
                    3=> $dato->fecha_limite,
                    4=>'<button class="btn btn-primary" onclick="editarTarea('.$_POST['id'].', '.$dato->idTareas.')">Modificar</button>'
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

        case 'obtenerListaClasesMaestro':
            unset($_POST['action']);
            $fechahoymas20minutos = date('Y-m-d H:i:s', strtotime('+20 minutes'));
            $fechainicio= date('Y-m-d').' 00:00:00';
            $fechafin= date('Y-m-d').' 23:59:59';

            $listaClases = $master->obtenerListaClasesMaestro($_POST);
            $data = Array();
            while($dato=$listaClases->fetchObject()){
                $ingresaralasesion=false;
                if (strtotime($dato->fecha_hora_clase) <= strtotime($fechahoymas20minutos) && strtotime($dato->fecha_hora_clase) >= strtotime($fechainicio) && strtotime($dato->fecha_hora_clase) <= strtotime($fechafin)) { //los maestros pueden ver todas sus clases pero aparece el botón de ingresar a la sesión siempre y cuando la hora de inicio de la clase falten 20 minutos o sea menor
                    $ingresaralasesion=true;
                }
                $data[]=array(
                    0=> $dato->titulo,
                    1=> $dato->nombre,
                    2=> $dato->fecha_hora_clase,
                    3=> ($ingresaralasesion)?'<a href="claseswebex?id_sesion='.$dato->id_sesion.'"> <button class="btn btn-primary">Ingresar a la clase</button></a> <button class="btn btn-primary" onclick="editar_clase('.$dato->idClase.',\''.$dato->titulo.'\')">Editar material de apoyo</button>'
                                                    :'<button class="btn btn-primary" onclick="editar_clase('.$dato->idClase.',\''.$dato->titulo.'\')">Editar material de apoyo</button>'
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

        case 'obtenerTareasCalificar':
            unset($_POST['action']);
            $listaTareasCalificar = $master->obtenerTareasCalificar($_POST);
            $data = Array();
            while($dato=$listaTareasCalificar->fetchObject()){
                $valor = $dato->titulo;
                if(strlen($valor) > 30){
                    $resultado = substr($valor, 0, 30);
                    $nombreFinal = $resultado.'...';
                }else{            
                    $nombreFinal = $valor;
                }
				$archivo_buscar = '../../../../'.$dato->archivo;
                $link = '#';
                // if(!file_exists($archivo_buscar)){
                //     if(file_get_contents('https://conacon.org/moni/'.$dato->archivo)){
                //         $link = 'https://conacon.org/moni/'.$dato->archivo;
                //     }
                // }else{
                    $link = '../'.$dato->archivo;
                // }
                // $link = $archivo_buscar;
                $boton_link = '';
                if($link == '#'){
                    $boton_link = '<button class="btn btn-secondary waves-effect waves-light" target="_blank"><i class="fas fa-file-download"></i> Ver </button>';
                }else{
                    $boton_link = '<a class="btn btn-primary waves-effect waves-light" href="'.$link.'" target="_blank"><i class="fas fa-file-download"></i> Ver </a>';
                }
				
                $data[]=array(
                    0=> utf8_encode($nombreFinal),
                    1=> ($dato->nombre),
                    2=> ($dato->NombreMat),
                    3=> ($dato->NombreGen),
                    4=> ($dato->fecha_entrega),
                    5=> ($dato->retroalimentacion),
                    6=> ($dato->calificacion),
                    7=> ($dato->comentario),
                    8=> '<button class="btn btn-primary" data-toggle="modal" data-target="#modalCalificarTarea" onclick="calificarTarea('.$dato->idEntrega.')">Calificar</button> '.$boton_link
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

        case 'obtenerDatosCalificarTarea':
            unset($_POST['action']);
            $datosTarea = $master->obtenerDatosCalificarTarea($_POST)['data'];
            $datosTarea['fecha'] = date("Y-m-d", strtotime($datosTarea['fecha_entrega']));
            $datosTarea['hora'] = date("H:m:s", strtotime($datosTarea['fecha_entrega']));
            unset($datosTarea['fecha_entrega']);
            echo json_encode($datosTarea);
            break;

        case 'calificarTarea':
            unset($_POST['action']);
            $calificado = $master->calificarTarea($_POST);
            echo json_encode($calificado);
            break;

        case 'no_session':
            echo 'no_session';
            break;
        // cases chuy
        case 'consultar_todo_examenes':
            unset($_POST['action']);
            $list_examen = $master->consultar_todo_examenes($_POST);
            $data = Array();
            while($dato=$list_examen->fetchObject()){
                if($dato->Examen_ref>0){
                    $data[]=array(
                        0=> $dato->Nombre,
                        1=> $dato->fechaInicio,
                        2=> $dato->fechaFin,
                        3=> $dato->nombre_materia,
                        4=> $dato->nombre_generacion,
                        5=> '<button class="btn btn-primary" onclick="revisar_entregas('.$dato->idExamen.')">
                                <i class="fa fa-check-square"></i> Revisar Entregas
                                </button> '.
                                '<button type="button" class="btn btn-success" disabled>Examen con Preguntas Preasignadas</button>'
    
                        );
                }else{
                    $data[]=array(
                    0=> $dato->Nombre,
                    1=> $dato->fechaInicio,
                    2=> $dato->fechaFin,
                    3=> $dato->nombre_materia,
                    4=> $dato->nombre_generacion,
                    5=> '<button class="btn btn-primary" onclick="revisar_entregas('.$dato->idExamen.')">
                            <i class="fa fa-check-square"></i> Revisar Entregas
                            </button> '.
                            '<button class="btn btn-primary" onclick="asignarPreguntas('.$dato->idExamen.','.$dato->idMaestro.')">Asignar Preguntas</button>'

                    );
                }
                
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;
        case 'consultar_entregas':
            unset($_POST['action']);
            $respuestasJson = [];
            $validaciones = [];
            $preguntas = [];
            $respuestasFinales = [];
            $respuestas = $master->consultar_respuestas_examenes($_POST['id']);
            foreach ($respuestas as $key => $value) {
                $respuestas[$key]['respuestas'] = json_decode($value['respuestas'], true);
                // $jsonRespuestas = json_decode($value['respuestas'], true);
                foreach($respuestas[$key]['respuestas'] as $i => $resp){
                    $respuestas[$key]['respuestas'][$i][3] = $master->obtenerNombrePregunta($resp[0])['data'];
                }
            }
            echo json_encode($respuestas);
            break;

        case 'obtenerMateriasDocenteExamen':
            unset($_POST['action']);
            $materiasExamen = $master->obtenerMateriasDocenteExamen($_POST)['data'];
            echo json_encode($materiasExamen);
            break;

        case 'crearExamen':
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

            if($cantidadPreguntas != 0){
                $newExam = $master->crearExamen($_POST['nombreExamen'], $_POST['cursoExamen'], $_POST['fechaInicioExamen'], $_POST['fechaFinExamen'], $_POST['idDocente'])['data'];
                for($p = 0; $p < $cantidadPreguntas; $p++){
                    $json = json_encode($respuestas[$p]);
                    $preguntasExamen = $master->insertarPreguntaExamen($newExam, $preguntas[$p], $json,"insertar");
                }
                echo json_encode($preguntasExamen);
            }
            break;

        case 'asignarPreguntas':
            unset($_POST['action']);
            if(empty($_POST['totalPregExamen'])){
                $_POST['totalPregExamen'] = NULL;
            }
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

            if($_POST['totalPregExamen'] <= $cantidadPreguntas){
                $preguntasAplicar = $master->asignarCantidadPreguntasAplicar($_POST['idExamen'], $_POST['totalPregExamen']);
                
                for($p = 0; $p < $cantidadPreguntas; $p++){
                    $json = json_encode($respuestas[$p]);//:::
                    $Validacion = $master->insertarPreguntaExamen($_POST['idExamen'], $preguntas[$p], $json,"validar");
                    if(isset($Validacion['data']) && $Validacion['data']>0){
                        $preguntasExamen = 'preguntas_repetida';   
                    }else{
                        $preguntasExamen = $master->insertarPreguntaExamen($_POST['idExamen'], $preguntas[$p], $json,"insertar");
                    }
                }
                //echo json_encode($preguntasExamen);
            }else{
                $preguntasExamen = 'preguntas_aplicar';
            }
            if($preguntasExamen == 'preguntas_aplicar' || $preguntasExamen == 'preguntas_repetida'){
                echo $preguntasExamen;
            }else{
                echo json_encode($preguntasExamen);
            }
            break;

        case 'cargarCarrerasExamen':
            unset($_POST['action']);
            $obCarreras = $master->cargarCarrerasExamen($_POST)['data'];
            echo json_encode($obCarreras);
            break;

        case 'buscarPreguntasExamen':
            unset($_POST['action']);
            $existPreg = $master->buscarPreguntasExamen($_POST)['data'];
            echo json_encode($existPreg);
            break;
        case 'listar_carreras_profesor':
            $resp = [];
            $id_maestro = false;
            if(isset($_SESSION['usuario']) && $_SESSION['usuario']['idTipo_Persona'] == 30){
                $id_maestro = $_SESSION['usuario']['idPersona'];
            }else if(isset($_POST['maestro'])){
                $id_maestro = $_POST['maestro'];
            }
            if(intval($id_maestro) > 0){
                $resp = $ceM->buscarCarrerasMaestro($id_maestro);
                $resp = ['estatus'=>'ok','data' => $resp];
            }else{
                $resp = ['estatus'=>'error','info' => 'No se encontró el maestro'];
            }
            echo json_encode($resp);
            break;
        case 'listar_generaciones_carrera':
            if(isset($_POST['carrera']) && intval($_POST['carrera']) > 0){
                $resp = $ceM->listarGeneraciones($_POST['carrera']);
                $resp = ['estatus'=>'ok','data' => ['generaciones'=>$resp]];
            }else{
                $resp = ['estatus'=>'error','info' => 'No se encontró la carrera'];
            }
            echo json_encode($resp);
            break;
        case 'listar_alumnos_generacion';
            unset($_POST['action']);
            $loadAlumnos = $ceM->consultarAlumnos();
            // var_dump($loadAlumnos->fetchAll(PDO::FETCH_ASSOC));
            $data = Array();
            while($dato=$loadAlumnos->fetchObject()){
                $data[]=array(
                0=> $dato->aPaterno.' '.$dato->aMaterno.' '.$dato->nombre,
                1=> $dato->ngeneracion,
                2=> $dato->correo,
                3=> $dato->telefono,
                4=> $dato->id_prospecto,
                );
            }
            $plan_estudios = false;
            if(intval($_GET['idGeneracion']) > 0){
                $info_generacion = $ceM->buscarPlanEstudioGeneracion(['idGen'=>$_GET['idGeneracion']])['data'];
                if($info_generacion && intval($info_generacion['id_plan_estudio']) > 0){
                    $plan_estudios = $peM->obtenerPlanEstudio(['id'=>$info_generacion['id_plan_estudio']])['data'];
                    if($plan_estudios){
                        $materias_ciclos = [];
                        for($i = 1; $i <= $plan_estudios['numero_ciclos']; $i++){
                            $consulta = ['planEst'=>$plan_estudios['id_plan_estudio'],'numCiclo'=>$i];
                            $materias_ciclos[$i] = $peM->obtenerMateriasAsignadasPlan($consulta)['data'];
                        }
                        $plan_estudios['materias_ciclos'] = $materias_ciclos;
                    }
                }
            }
            $result = array(
                'aaData'=>$data,
                'plan_estudios'=>$plan_estudios
            );
            echo json_encode($result);
            break;
        case 'consultar_desempenio':
            $resp = $ceM->consultar_desempenio_alumno($_POST['alumno'], $_POST['materia'], $_POST['plan_estudios']);
            echo json_encode($resp);
            break;
        case 'guardar_calificaciones':
            // var_dump($_SESSION);
            if(isset($_SESSION['usuario']) && $_SESSION['usuario']['idTipo_Persona'] == 30){
                if(intval($_POST['generacion']) > 0 && intval($_POST['ciclo']) > 0){
                    $registra = $_SESSION['usuario']['idPersona'];
                    $count_upd = 0;
                    $count_ins = 0;
                    foreach($_POST['calificaciones'] as $calificacion => $valor){
                        $alumno = explode('_', $calificacion)[0];
                        $materia = explode('_', $calificacion)[1];
                        $exist = $ceM->verificar_calificacion_materia_alumno($alumno, $materia, $_POST['generacion'], $_POST['ciclo']);
                        $data = [
                            'calificacion'=> $valor,
                            'alumno'    => $alumno,
                            'materia'   => $materia,
                            'generacion'=> $_POST['generacion'],
                            'ciclo'     => $_POST['ciclo']
                        ];
                        if($exist){
                            #update
                            $actualizar = $ceM->actualizar_calificacion_materia_alumno($data);
                            if($actualizar['estatus'] == 'ok' && $actualizar['data'] > 0){
                                $count_upd++;
                            }
                        }else{
                            #insert
                            $data['quien_registra'] = $registra;
                            $insertar = $ceM->registrar_calificacion_materia_alumno($data);
                            if($insertar['estatus'] == 'ok' && $insertar['data'] > 0){
                                $count_ins++;
                            }
                        }
                    }
                    $message = '';
                    if($count_upd > 0){
                        $message .= $count_upd.' calificaciones actualizadas.';
                    }
                    if($count_ins > 0){
                        $message .= $count_ins.' calificaciones registradas.';
                    }
                    $message = $message == '' ? 'No se aplicaron cambios' : $message;
                    $resp = ['estatus'=>'ok','info'=>$message];
                }else{
                    $resp = ['estatus'=>'error','info' => 'Falta información para registrar la calificacion'];
                }
            }else{
                $resp = ['estatus'=>'error','info' => 'No se encontró el maestro'];
            }
            echo json_encode($resp);
            break;
        case 'listar_calificaciones_alumnos':
            $resp = [];
            $alumnos = implode(',', $_POST['id_alumnos']);
            foreach($_POST['id_materias'] as $materia){
                $alumnos_calif = $ceM->listar_calificaciones_alumnos($_POST['id_generacion'], $materia, $_POST['ciclo'], $alumnos);
                $resp = array_merge($resp, $alumnos_calif);
            }
            echo json_encode($resp);
            break;
        case 'recuperarPreguntasAplicar':
            unset($_POST['action']);
            $preguntasAplicarExamen = $master->recuperarPreguntasAplicar($_POST)['data'];
            echo json_encode($preguntasAplicarExamen);
            break;

        case 'insertarPreguntaPasadaExamen':
            
            //unset($_POST['action']);
            $preguntasAplicarExamen = $master->insertarPreguntaPasadaExamen($_POST['idCarr']);
            echo json_encode($preguntasAplicarExamen);
            break;

        case 'Rango_de_Preguntas':
                //unset($_POST['action']);
            $preguntasAplicarExamen = $master->Rango_de_Preguntas($_POST['idEx']);
            echo json_encode($preguntasAplicarExamen);
            break;

        case 'ObtenerDatosMaestro':
            unset($_POST['action']);
            $DatosProfesor = $master->ObtenerDatosMaestro($_POST);
            echo json_encode($DatosProfesor);
            break;

        case 'ActualizarDatosMaestro':
            unset($_POST['action']);
            unset($_POST['imgArchivo']);

            $maestro = $ceM->buscarMaestro($_POST['id'])['data'][0];
            // var_dump($maestro);
            // var_dump($_POST);
            if($maestro['email'] != $_POST['Email']){
                // validar disponibilidad de correo nuevo
                $verify_mail = $accM->get_info_maestro_correo($_POST['Email']);
                if($verify_mail !== false){
                    echo json_encode(['estatus'=>'error', 'info'=>'Este correo ya está siendo utilizado por otro docente']);
                    die();
                }
            }
           
            $uploads_dir = '../../../images/maestros';
            
            if(($_POST["img"]=="")){
                $_POST['img'] = 'no_image';
            }
        
            $DatosEditar = $master->ActualizarDatosMaestro($_POST);
            echo json_encode($DatosEditar);
            break;
            
        default:
            echo "noaction";
            break;
    }

}else{
	header('Location: ../../../../index.php');
}
?>
