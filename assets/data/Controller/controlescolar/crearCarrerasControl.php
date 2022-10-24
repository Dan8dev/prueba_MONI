<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/controlescolar/crearCarrerasModel.php';
    require_once '../../Model/controlescolar/generacionesModel.php';
    $crrer = new Carreras();
    $GunoAdd = new Generaciones();
    $acents = ['Á', 'É', 'Í', 'Ó', 'Ú'];
    $no_acents = ['A', 'E', 'I', 'O', 'U'];
    //var_dump($_POST);
    if (!(isset($_SESSION['usuario']) || isset($_SESSION["alumno"]) || isset($_POST["alumnoAmor"]))){
        $_POST['action'] = 'no_session';
    }
    if(isset($_POST["alumnoAmor"])){
        unset($_POST["alumnoAmor"]);
    }

    $accion=@$_POST["action"];

    switch ($accion) {
        case 'obtenerinstituciones':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $instituciones = $crrer->getInstituciones($id);
            $instituciones = $instituciones['data'];
            echo json_encode($instituciones);
            break;
        case 'crearcarrera':
            unset($_POST['action']);
            $GunoNum = $_POST['numGuno'];
            $GunoNombre = $_POST['nombreGuno'];
            $GunoModalidad = $_POST['selectModalidadGuno'];
            $GunoTipoCiclo = $_POST['selectTipocicloGuno'];
            $GunoFechaInicio = $_POST['fechaInicioGuno'];
            //$GunoFechaFin = $_POST['fechaFinGuno'];
            
            unset($_POST['nombreGuno']);
            unset($_POST['selectModalidadGuno']);
            unset($_POST['selectTipocicloGuno']);
            unset($_POST['fechaInicioGuno']);
            //unset($_POST['fechaFinGuno']);
            unset($_POST['numGuno']);

            $fechaActual = date('Y-m-d H:i:s');
                
            $_POST['fActual'] = $fechaActual;
            $_POST['estatus'] = "1";

            $carrera = $crrer->crearCarrera($_POST);
            
            if($carrera['data']!="0"){
                $GUno = $GunoAdd->crearGeneracionUno($carrera['data'],$GunoNum, $GunoNombre, $GunoModalidad, $GunoTipoCiclo, $GunoFechaInicio, $_POST['creador_por'], $_POST['fActual']);
                if($GUno['data']!=0){
                    $addCicloGuno = $GunoAdd->avance_generaciones($GUno['data']);
                    echo json_encode($addCicloGuno);
                }else{
                    echo 'no_avance';
                }
            }else{
                echo 'no_generacion';
            }
            //echo json_encode($carrera);

            break;

        case 'obtenerCarreras':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $csul = $crrer->obtenerCarreras($id);
            $data = Array();
            while($dato=$csul->fetchObject()){
                $data[]=array(
                    0=> $dato->nombreInst,
                    1=> $dato->nombre,
                    2=> $dato->tipo === '1' ? 'Certificación' : ($dato->tipo === '2' ? 'TSU' : ($dato->tipo === '3' ? 'Diplomado' : ($dato->tipo === '4' ? 'Licenciatura' : ($dato->tipo === '5' ? 'Maestría' : ($dato->tipo === '6' ? 'Doctorado' : ($dato->tipo === '7' ? 'Especialidad' : '')))))),
                    3=> $dato->area,
                    4=> $dato->fecha_creado,
                    5=> '<button class="btn btn-secondary" id="verTablaCarrera" data-toggle="modal" data-target="#modalTablaCarrera" onclick="tablaCarrerasAlumnos('.$dato->idCarrera.',\''.$dato->nombre.'\')">Lista</button> '.
                    '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModifycarrera" onclick="buscarCarrera('.$dato->idCarrera.')">Modificar</button>'/*,
                    5=>'<button class="btn btn-danger" onclick="validarEliminar('.$dato->idCarrera.')">Eliminar</button>'*/
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

        case 'buscarCarrera':
            unset($_POST['action']);
            $bus = $crrer->buscarCarrera($_POST['idEditar']);
            $bus['data']['fecha_inicio'] = date("Y-m-d", strtotime($bus['data']['fecha_inicio']));
            //$bus['data']['fechafinal'] = date("Y-m-d", strtotime($bus['data']['fechafinal']));
            echo json_encode($bus);
            break;

        case 'modificarCarrera':
            unset($_POST['action']);
            $modNomGuno = $_POST['devnombreGuno'];
            $modModalidadGuno = $_POST['devselectModalidadGuno'];
            $modTipoCGuno = $_POST['devselectTipocicloGuno'];
            $modFechaIGuno = $_POST['devfechaInicioGuno'];
            //$modFechaFGuno = $_POST['devfechaFinGuno'];
            $modNumGuno = $_POST['devnumGuno'];
            $modificadoPor = $_POST['modificado_por'];

            unset($_POST['devnombreGuno']);
            unset($_POST['devselectModalidadGuno']);
            unset($_POST['devselectTipocicloGuno']);
            unset($_POST['devfechaInicioGuno']);
            //unset($_POST['devfechaFinGuno']);
            unset($_POST['devnumGuno']);
            unset($_POST['modificado_por']);

            $fActualizacion = date('Y-m-d H:i:s');
            $_POST['fActualizacion'] = $fActualizacion;
            $edit = $crrer->modificarCarrera($_POST);
            $modGUno = $GunoAdd->modificarGeneracionUno($_POST['id_carrera'], $modNumGuno, $modNomGuno, $modModalidadGuno, $modTipoCGuno, $modFechaIGuno, $modificadoPor, $_POST['fActualizacion']);

            echo json_encode($modGUno);
            break;
        
        case 'eliminarCarrera':
            unset($_POST['action']);
            $del = $crrer->eliminarCarrera($_POST);
            echo json_encode($del);
            break;

        case 'obtenerAlumnosCarrera':
            unset($_POST['action']);
            $obAlumCarr = $crrer->obtenerAlumnosCarrera($_POST);
            $data = Array();
            while($dato=$obAlumCarr->fetchObject()){
                $data[]=array(
                    0=> $dato->nombre,
                    1=> $dato->aPaterno,
                    2=> $dato->aMaterno,
                    3=> 'GENERACIÓN '.$dato->secuencia_generacion,
                    4=> $dato->estatus === "1" ? 'ACTIVO' : ($dato->estatus === "2" ? 'BAJA' : ($dato->estatus === "3"  ? 'EGRESADO' : ($dato->estatus === "4" ? 'TITUTLADO' : ($dato->estatus === "5" ? 'EXPULSADO' : '')))),
                    5=> $dato->referencia,
                    6=> $dato->curp,
                    7=> $dato->edad === '0' ? 'SIN ASIGNAR' : $dato->edad,
                    8=> $dato->email,
                    9=> $dato->celular,
                    10=> $dato->Genero === '0' ? 'SIN ASIGNAR' : ($dato->Genero === '1' ? 'M' : ($dato->Genero === '2' ? 'H' : '')),
                    11=> $dato->grado_academico === '0' ? 'SIN ASIGNAR' : ($dato->grado_academico === '1' ? 'SECUNDARIA' : ($dato->grado_academico === '2' ? 'BACHILLERATO' : ($dato->grado_academico === '3' ? 'PREPARATORIA' : ($dato->grado_academico === '4' ? 'TSU' : ($dato->grado_academico === '5' ? 'LICENCIATURA' : ($dato->grado_academico === '6' ? 'MAESTRÍA' : ($dato->grado_academico === '8' ? 'DOCTORADO' : ''))))))),
                    12=> $dato->pais_nom_est,
                    13=> $dato->estado_nom_est,
                    14=> $dato->pais_nom,
                    15=> $dato->estado_nom,
                    16=> $dato->pais_nom_nac,
                    17=> $dato->estado_nom_nac,
                    18=> $dato->notas,
                    19=> ''
                    /*3=> $dato->fecha_creado,
                    4=> '<button class="btn btn-secondary" data-toggle="modal" data-target="#modalTablaCarrera" onclick="tablaCarrerasAlumnos('.$dato->idCarrera.')">Lista</button> '.
                    '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModifycarrera" onclick="buscarCarrera('.$dato->idCarrera.')">Modificar</button>'*/
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

        case 'obtenerDatosAlumnoDirectorio':
            unset($_POST['action']);
            $obDatosAlumDir = $crrer->obtenerDatosAlumnoDirectorio($_POST)['data'];
            echo json_encode($obDatosAlumDir);
            break;

        case 'cargarGeneracionesDirectorio':
            unset($_POST['action']);
            $obGenDir = $crrer->cargarGeneracionesDirectorio($_POST)['data'];
            echo json_encode($obGenDir);
            break;

        case 'cargarEstadosDirectorio':
            unset($_POST['action']);
            $obEstadosDir = $crrer->cargarEstadosDirectorio($_POST)['data'];
            echo json_encode($obEstadosDir);
            break;

        case 'cargarPaisesDirectorio':
            unset($_POST['action']);
            $obPaisesDir = $crrer->cargarPaisesDirectorio($_POST)['data'];
            echo json_encode($obPaisesDir);
            break;

        case 'actualizarDirectorioAlumno':
            unset($_POST['action']);

            if(!isset($_POST['generacionDirectorioGrupo'])){
                $_POST['generacionDirectorioGrupo'] = null;
            }  

            if($_POST['estatusAlumnoDirectorio'] == 4){
                $date = date('Y-m-d H:i:s');
                $data = ['idAlumno'=>$_POST['idAlumno'], 'fecha'=>$date, 'id_Gen'=>$_POST['idGeneracionAntigua'], 'estatus'=> 4,'grupo'=>$_POST['generacionDirectorioGrupo']];
                $crrer->actualizarTitulados($data);
                
            }else{
                if($_POST['estatusAlumnoDirectorio'] == 1){
                    $date = date('Y-m-d H:i:s');
                    $data = ['idAlumno'=>$_POST['idAlumno'], 'fecha'=>$date, 'id_Gen'=>$_POST['idGeneracionAntigua'], 'estatus'=> 1,'grupo'=>$_POST['generacionDirectorioGrupo']];
                    $crrer->actualizarTitulados($data);
                }
            }

            unset($_POST['generacionDirectorioGrupo']);

            if(!isset($_POST['estadoAlumnoDirectorio'])){
                //echo 'No';
                $_POST['estadoAlumnoDirectorio'] = "0";
            }
            if(!isset($_POST['paisNacimientoDirectorio'])){
                $_POST['paisNacimientoDirectorio'] = 0;
            }
            if(!isset($_POST['entidadNacimientoDirectorio'])){
                $_POST['entidadNacimientoDirectorio'] = 0;
            }
            if(!isset($_POST['paisEstudioDirectorio'])){
                $_POST['paisEstudioDirectorio'] = 0;
            }
            if(!isset($_POST['entidadEstudioDirectorio'])){
                $_POST['entidadEstudioDirectorio'] = 0;
            }
            /**
             * $modelo->actualizar_estatus_alumno_generacion($alumno, $generacion, $estatus);
             */

            $_POST['nombreDirectorio'] = str_replace($acents, $no_acents, strtoupper($_POST['nombreDirectorio']));
            $_POST['apellidoPaternoDirectorio'] = str_replace($acents, $no_acents, strtoupper($_POST['apellidoPaternoDirectorio']));
            $_POST['apellidoMaternoDirectorio'] = str_replace($acents, $no_acents, strtoupper($_POST['apellidoMaternoDirectorio']));

            if($_POST['generacionDirectorio'] != $_POST['idGeneracionAntigua']){
                $valOtraGeneracion = $crrer->validarCambioGeneracion($_POST['idRelacion'], $_POST['idAlumno'], $_POST['generacionDirectorio'])['data'];
                if($valOtraGeneracion == 0){
                    //Esto es para ambos servidores
                    $actDirectorioBasico = $crrer->actualizarDirectorioBasicoAlumno($_POST['nombreDirectorio'], $_POST['apellidoPaternoDirectorio'], $_POST['apellidoMaternoDirectorio'], $_POST['sexoAlumnoDirectorio'], $_POST['idAlumno']);
                    $actDirectorioGeneral = $crrer->actualizarDirectorioGeneralAlumno($_POST['telefonoDeCasaDirectorio'],$_POST['telefonoRecadosDirectorio'],$_POST['CedulaProfesionalDirectorio'],$_POST['fechaEgresoEstudioDirectorio'],$_POST['EscuelaEstudioDirectorio'],$_POST['estatusAlumnoDirectorio'], $_POST['curpAlumnoDirectorio'], $_POST['emailAlumnoDirectorio'], $_POST['telefonoAlumnoDirectorio'], $_POST['paisAlumnoDirectorio'], $_POST['estadoAlumnoDirectorio'], $_POST['paisNacimientoDirectorio'], $_POST['entidadNacimientoDirectorio'], $_POST['paisEstudioDirectorio'], $_POST['entidadEstudioDirectorio'], $_POST['gradoUltimoAlumnoDirectorio'], $_POST['notasDirectorio'], $_POST['edadAlumnoDirectorio'], $_POST['idAlumno'], $_POST['inp_ciudad'], $_POST['inp_colonia'], $_POST['inp_calle'], $_POST['inp_cp'], $_POST['inp_matricula'], $_POST['idGeneracionAntigua']);
                    $actDirectorioGeneracion = $crrer->actualizarDirectorioGeneracionAlumno($_POST['generacionDirectorio'], $_POST['idGeneracionAntigua'], $_POST['idAlumno']);
                    echo json_encode($actDirectorioGeneracion);
                }else{
                    echo 'ya_existe_generacion';
                }
            }else{
                $actDirectorioBasico = $crrer->actualizarDirectorioBasicoAlumno($_POST['nombreDirectorio'], $_POST['apellidoPaternoDirectorio'], $_POST['apellidoMaternoDirectorio'], $_POST['sexoAlumnoDirectorio'], $_POST['idAlumno']);
                $actDirectorioGeneral = $crrer->actualizarDirectorioGeneralAlumno($_POST['telefonoDeCasaDirectorio'],$_POST['telefonoRecadosDirectorio'],$_POST['CedulaProfesionalDirectorio'],$_POST['fechaEgresoEstudioDirectorio'],$_POST['EscuelaEstudioDirectorio'],$_POST['estatusAlumnoDirectorio'], $_POST['curpAlumnoDirectorio'], $_POST['emailAlumnoDirectorio'], $_POST['telefonoAlumnoDirectorio'], $_POST['paisAlumnoDirectorio'], $_POST['estadoAlumnoDirectorio'], $_POST['paisNacimientoDirectorio'], $_POST['entidadNacimientoDirectorio'], $_POST['paisEstudioDirectorio'], $_POST['entidadEstudioDirectorio'], $_POST['gradoUltimoAlumnoDirectorio'], $_POST['notasDirectorio'], $_POST['edadAlumnoDirectorio'], $_POST['idAlumno'], $_POST['inp_ciudad'], $_POST['inp_colonia'], $_POST['inp_calle'], $_POST['inp_cp'], $_POST['inp_matricula'],$_POST['idGeneracionAntigua']);
                $actDirectorioGeneracion = $crrer->actualizarDirectorioGeneracionAlumno($_POST['generacionDirectorio'], $_POST['idGeneracionAntigua'], $_POST['idAlumno']);    
                echo json_encode($actDirectorioGeneracion);
            }
            break;

        case 'no_session':
            echo 'no_session';
            break;
        
        default:
            # code...
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}
