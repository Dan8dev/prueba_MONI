<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/controlescolar/generacionesModel.php';
    require_once '../../Model/planpagos/planpagosModel.php'; // agrego chuy
    
    $Gnera = new Generaciones();
    $planPM = new PlanPagos(); // agrego chuy

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }

    $accion=@$_POST["action"];

    switch ($accion) {

        case 'obtenerCarreras':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $obCarrer = $Gnera->obtenerListaCarreras($id)['data'];
            echo json_encode($obCarrer);
            break;
        
        case 'crearGeneracion':
            unset($_POST['action']);
            $numSecu = $_POST['numG'];
            $obtenerNoR = $Gnera->evitarSecuenciaRepetida($numSecu, $_POST['selectCarrer']);
            if($obtenerNoR['data']==0){
                $idCarrera = $_POST['selectCarrer'];
                $nomCarrera = $Gnera->obtenerNombreCarrera($idCarrera)['data'];
                $nombreG = 'Generación '.$numSecu.' '.$nomCarrera['nombre'];
                $_POST['nombreG'] = $nombreG;

                $fCreado = date('Y-m-d H:i:s');
                $_POST['fCreado'] = $fCreado;

                $addG = $Gnera->crearGeneracion($_POST);
                // AGREGO CHUY
                if($addG['estatus'] == 'ok' && $addG['data'] > 0){
                    // buscar el plan de pagos de la carrera
                    $planCarrera = $planPM->obtener_plan_pago_carrera($_POST['selectCarrer'])['data'];
                    if($planCarrera){
                        // obtener los conceptos del plan de pagos
                        $conceptos_plan = $planPM->obtener_conceptos_plan($planCarrera['idPlanPago'])['data'];
                        $categorias = ['Inscripción', 'Mensualidad', 'Reinscripción', 'Titulación'];
                        foreach($conceptos_plan as $concepto){
                            if(($concepto['id_generacion']==null || $concepto['id_generacion']==0) && $concepto['generales'] == null && in_array($concepto['categoria'], $categorias)){
                                // replicar el concepto para la generacion
                                if($concepto['fechalimitepago'] != null){
                                    $f_lim_concepto = explode('-', substr($concepto['fechalimitepago'],0 ,10));
                                    
                                    $fechageneracion = explode('-', $_POST['fechainicio']);
                                    $n_fecha_concepto = $fechageneracion[0].'-'.$fechageneracion[1].'-'.$f_lim_concepto[2];
                                    if(strtotime($n_fecha_concepto) < strtotime($_POST['fechainicio'])){
                                        $n_fecha_concepto = date('Y-m-d', strtotime('+1 month', strtotime($n_fecha_concepto)));
                                    }
                                }else{
                                    $n_fecha_concepto = null;
                                }
                                $info_con = [
                                    "nomI" => $concepto['categoria']." - ".$_POST['nombreG'],
                                    "costoIns" => $concepto['precio'],
                                    "categoria" => $concepto['categoria'],
                                    "id" => $addG['data'],
                                    "parcialidades" => $concepto['parcialidades'],
                                    "f_creado" => date('Y-m-d H:i:s'),
                                    "creado_por" => $_POST['creador_por'],
                                    "fechalimitepagoins" => $n_fecha_concepto
                                ];
                                
                                $planPM->agregar_concepto_generacion($info_con);
                            }
                        }
                    }
                    }      
                    //FIN AGREGO CHUY
                    $addCicloA = $Gnera->avance_generaciones($addG['data']);
                    echo json_encode($addCicloA);
                }else{
                    echo 'ya_existe_generacion';
                }

            break;

        case 'obtenerGeneraciones':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $csulG = $Gnera->obtenerGeneraciones($id);
            $data = Array();
            while($dato=$csulG->fetchObject()){
                $data[]=array(
                    //0=> $dato->IDPlanPago,
                    0=> $dato->nombre,
                    1=> $dato->nomCarrer,
                    2=> $dato->modalidadCarrera,
                    3=> $dato->id_plan_estudio === NULL ? '<p style="color:#AA262C">Sin plan de estudio asignado</p>' : $dato->nombrePlan,
                    4=> $dato->tipoCiclo === '1' ? 'Cuatrimestre' : ($dato->tipoCiclo === '2' ? 'Semestre' : 'Trimestre'),
                    5=> date('d-m-Y', strtotime($dato->fecha_inicio)),
                    6=> $dato->fechafinal === null ? $dato->fechafinal : date('d-m-Y', strtotime($dato->fechafinal)),
                    7=> $dato->fechaCreado,
                    8=> $dato->id_plan_estudio === NULL || $dato->numero_ciclos === '1' ? 
                        '<button class="btn btn-success" data-toggle="modal" data-target="#modalAsignarBloqueo" onclick="vistaBloqueoGen('.$dato->idGeneracion.')">Documentos</button> '.
                        '<button class="btn btn-secondary" onclick="vistaAsignarPlanEstGen('.$dato->idGeneracion.','.$dato->tipoCiclo.')">Asignar Plan</button> '.
                        '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModGene" onclick="buscarGeneracion('.$dato->idGeneracion.')">Modificar</button> '.
                        '<button class="btn btn-success" onclick="vistaAsignarFechasGen('.$dato->idGeneracion.')">Asignar Fechas</button> ': 
                        '<button class="btn btn-success" data-toggle="modal" data-target="#modalAsignarBloqueo" onclick="vistaBloqueoGen('.$dato->idGeneracion.')">Documentos</button> '.
                        '<button class="btn btn-secondary" onclick="vistaAsignarPlanEstGen('.$dato->idGeneracion.','.$dato->tipoCiclo.')">Asignar Plan</button> '.
                        '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModGene" onclick="buscarGeneracion('.$dato->idGeneracion.')">Modificar</button> '.
                        '<button class="btn btn-success" onclick="vistaAsignarFechasGen('.$dato->idGeneracion.')">Asignar Fechas</button> '
                        /*,
                    9=>'<button class="btn btn-danger" onclick="validarEliminarGeneracion('.$dato->idGeneracion.')">Eliminar</button>'
                    '<button class="btn btn-success" onclick="vistaAsignarFechasGen('.$dato->idGeneracion.')">Asignar Fechas</button> '*/
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

        case 'eliminarGeneracion':
            unset($_POST['action']);
            $del = $Gnera->eliminarGeneracion($_POST);
            echo json_encode($del);
            break;

        case 'obtenerCarrerasMod':
            unset($_POST['action']);
            $obCarrer = $Gnera->obtenerListaCarrerasMod($_POST['idCarr'])['data'];
            echo json_encode($obCarrer);
            break;

        case 'buscarGeneracion':
            unset($_POST['action']);
            $bus = $Gnera->buscarGeneracion($_POST['idEditar']);
            $bus['data']['fecha_inicio'] = date("Y-m-d", strtotime($bus['data']['fecha_inicio']));
            $bus['data']['fechafinal'] = date("Y-m-d", strtotime($bus['data']['fechafinal']));
            echo json_encode($bus);
            break;

        case 'modificarGeneracion':
            unset($_POST['action']);
            $modNumG = $_POST['modNumG'];
            $obNoRepMod = $Gnera->evitarSecuenciaRepetidaMod($modNumG, $_POST['modselectCarrer'], $_POST['idG']);
            if($obNoRepMod['data']==0){
                $idModCarrera = $_POST['modselectCarrer'];
                $nomCarrera = $Gnera->obtenerNombreCarrera($idModCarrera)['data'];
                $modNombreG = 'Generación '.$modNumG.' '.$nomCarrera['nombre'];
                $_POST['modNombreG'] = $modNombreG;

                $fActualizacion = date('Y-m-d H:i:s');
                $_POST['fActualizacion'] = $fActualizacion;
                $modG = $Gnera->modificarGeneracion($_POST);
                echo json_encode($modG);
            }else{
                echo 'ya_existe_generacion';
            }
            break;

        case 'buscarNumeroGeneracion':
            unset($_POST['action']);
            $searhNumG = $Gnera->buscarNumeroGeneracion($_POST)['data'];
            echo json_encode($searhNumG);
            break;

        case 'validarAsignarPlanEstGen':
            unset($_POST['action']);
            $valAsigPlanEstG = $Gnera->validarAsignarPlanEstGen($_POST)['data'];
            //var_dump($valAsigPlanEstG);
            if($valAsigPlanEstG == 0){
                echo 'no_existe_plan_e';
            }else{

                echo json_encode($valAsigPlanEstG);
                
            }
            break;

        case 'obtenerPlanesEstudio':
            unset($_POST['action']);
            $idPlanE = [];
            $nom = [];
            $totalCiclos = [];
            $numTotalMat = [];
            $finalSelect = [];
            $obPlEst = $Gnera->obtenerPlanesEstudio($_POST)['data'];
            foreach($obPlEst as $campo => $value){
                    $idPlanE[$campo] = $obPlEst[$campo]['id_plan_estudio'];
                    $nom[$campo] = $obPlEst[$campo]['nombre'];
                    $totalCiclos[$campo] = $obPlEst[$campo]['numero_ciclos'];             
            }
            //var_dump($idPlanE);
            //var_dump($nom);
            //var_dump($totalCiclos);
            for($k = 0; $k < count($idPlanE); $k++){
                //var_dump($idPlanE[$k]);
                $valMateriasAsig = $Gnera->validarMateriasAsignadasPlanE($idPlanE[$k])['data'];
                $numTotalMat[$k] = count($valMateriasAsig);
            }
            //var_dump("////");
            for($k = 0; $k < count($idPlanE) ;$k++){
                //var_dump($totalCiclos[$k]);
                //var_dump($numTotalMat[$k]);
                if($totalCiclos[$k]==$numTotalMat[$k]){
                    $finalSelect[$k] = ['id_plan_estudio'=>$idPlanE[$k],
                                    'nombre'=>$nom[$k]];
                }
            }
            if(empty($finalSelect)){
                echo 'falta_materias_asignar';
            }else{
                echo json_encode($finalSelect);
            }
            //echo json_encode($obPlEst);
            break;

        case 'obDatosAsigGeneracionPE':
            unset($_POST['action']);
            unset($_POST['tipoC']);
            $obDatosAsigGeneracion = $Gnera->obDatosAsigGeneracionPE($_POST)['data'];
            if($obDatosAsigGeneracion==false){
                echo 'no_asignacion';
            }else{
                if($obDatosAsigGeneracion['fechafinal'] != null){
                    $obDatosAsigGeneracion['fechafinal'] = date("Y-m-d", strtotime($obDatosAsigGeneracion['fechafinal']));
                }
                echo json_encode($obDatosAsigGeneracion);
            }
            break;

        case 'obDatosGeneracionPE':
            unset($_POST['action']);
            $obDatosGeneracion = $Gnera->obDatosGeneracionPE($_POST)['data'];
            echo json_encode($obDatosGeneracion);
            break;

        case 'asignarPlanEstudioGen':
            unset($_POST['action']);
            $asigPE = $Gnera->asignarPlanEstudioGen($_POST);
            $obtenerciclos = $Gnera->obtenerCiclo($_POST['asigPlanEst']);
            $asignarnumerodeciclosgen = $Gnera->asignarNumeroCiclosGen($_POST['idGenPlanE'],$obtenerciclos['data']['numero_ciclos']);
            echo json_encode($asigPE);
            break;

        case 'buscarTipoCarrera':
            unset($_POST['action']);
            $tipoCarrera = $Gnera->buscarTipoCarrera($_POST)['data'];
            echo json_encode($tipoCarrera);
            break;

        case 'datosPlanEstudioGeneración':
            unset($_POST['action']);
            $obPlanE = $Gnera->datosPlanEstudioGeneración($_POST)['data'];
            $obPlanE['fecha_inicio'] = date("Y-m-d", strtotime($obPlanE['fecha_inicio']));
            $obPlanE['fechafinal'] = date("Y-m-d", strtotime($obPlanE['fechafinal']));
            echo json_encode($obPlanE);
            break;

        case 'obtenerFechasPorCiclo':
            unset($_POST['action']);
            $obFechasCiclo = $Gnera->obtenerFechasPorCiclo($_POST)['data'];
            if($obFechasCiclo!=[]){
                $obFechasCiclo['fecha_inicio'] = date("Y-m-d", strtotime($obFechasCiclo['fecha_inicio']));
                $obFechasCiclo['fecha_fin'] = date("Y-m-d", strtotime($obFechasCiclo['fecha_fin']));
                echo json_encode($obFechasCiclo);
            }else{
                echo json_encode($obFechasCiclo);
            }
            break;

        case 'guardarFechaGeneracion':
            unset($_POST['action']);
            $obTotalCiclos = $Gnera->obtenerCicloTotalGeneracion($_POST['idGeneracion'])['data'];
            if ($_POST['numeroDeCiclo']==2) {//se establece fechalimite de pago primer reinscripcion
                $insertarfechalimitedepagoreins = $Gnera->insertarfechalimitedepagoreins($_POST['idGeneracion'],$_POST['fechaInicio']);
            }
            if ($_POST['numeroDeCiclo']>2) {//establecer fechas limite de pago reinscripcion
                $numerodeciclo= $_POST['numeroDeCiclo']-2;
                $obtenerconceptos = $Gnera->obtenerconceptos($_POST['idGeneracion']);
                $actualizarfechalimitedepago = $Gnera->actualizarfechalimitedepago($numerodeciclo,$obtenerconceptos['data']['id_concepto'],$_POST['fechaInicio']);
            }
            if($_POST['numeroDeCiclo']>1){
                if($_POST['numeroDeCiclo'] == $obTotalCiclos['numero_ciclos']){
                    $consultarAnterior = $Gnera->AnteriorFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                    if($consultarAnterior!=[]){
                        if($_POST['fechaInicio'] != $consultarAnterior['fecha_fin'] && $consultarAnterior['fecha_fin'] < $_POST['fechaInicio']){
                            $saveData = $Gnera->guardarFechaGeneracion($_POST);
                            echo json_encode($saveData);
                        }else{
                            echo 'Rango_Fecha';
                        }
                    }else{
                        $saveData = $Gnera->guardarFechaGeneracion($_POST);
                        echo json_encode($saveData);
                    }
                }else{
                    $consultarAnterior = $Gnera->AnteriorFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                    $consultarSiguiente = $Gnera->siguienteFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                    if($consultarAnterior!=[]){
                        $consultarAnterior['fecha_fin'] = date("Y-m-d", strtotime($consultarAnterior['fecha_fin']));
                    }
                    if($consultarSiguiente!=[]){
                        $consultarSiguiente['fecha_inicio'] = date("Y-m-d", strtotime($consultarSiguiente['fecha_inicio']));
                    }
                    if($consultarAnterior!=[] || $consultarSiguiente!=[]){
                        if($consultarAnterior!=[] && $consultarSiguiente !=[]){
                            if($_POST['fechaFin'] != $consultarSiguiente['fecha_inicio'] && $consultarSiguiente['fecha_inicio'] > $_POST['fechaFin']){
                                if($_POST['fechaInicio'] != $consultarAnterior['fecha_fin'] && $consultarAnterior['fecha_fin'] < $_POST['fechaInicio']){
                                    $saveData = $Gnera->guardarFechaGeneracion($_POST);
                                    echo json_encode($saveData);
                                }else{
                                    echo 'Rango_Fecha';
                                }
                            }else{
                                echo 'Rango_Fecha_Uno';
                            }
                        }else{
                            if($consultarAnterior!=[]){
                                if($_POST['fechaInicio'] != $consultarAnterior['fecha_fin'] && $consultarAnterior['fecha_fin'] < $_POST['fechaInicio']){
                                    $saveData = $Gnera->guardarFechaGeneracion($_POST);
                                    echo json_encode($saveData);
                                }else{
                                    echo 'Rango_Fecha';
                                }
                            }
                            if($consultarSiguiente !=[]){
                                if($_POST['fechaFin'] != $consultarSiguiente['fecha_inicio'] && $consultarSiguiente['fecha_inicio'] > $_POST['fechaFin']){
                                    $saveData = $Gnera->guardarFechaGeneracion($_POST);
                                    echo json_encode($saveData);
                                }else{
                                    echo 'Rango_Fecha_Uno';
                                }
                            }
                        }
                    }else{
                        $saveData = $Gnera->guardarFechaGeneracion($_POST);
                        echo json_encode($saveData);
                    }
                    /*if($_POST['fechaFin'] != $consultarSiguiente['fecha_inicio'] && $consultarSiguiente['fecha_inicio'] > $_POST['fechaFin']){
                        if($_POST['fechaInicio'] != $consultarAnterior['fecha_fin'] && $consultarAnterior['fecha_fin'] < $_POST['fechaInicio']){
                            $saveData = $Gnera->guardarFechaGeneracion($_POST);
                            echo json_encode($saveData);
                        }else{
                            echo 'Rango_Fecha';
                        }
                    }else{
                        echo 'Rango_Fecha_Uno';
                    }*/
                }
            }else{
                $consultarSiguiente = $Gnera->siguienteFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                if($consultarSiguiente!=[]){
                    $consultarSiguiente['fecha_inicio'] = date("Y-m-d", strtotime($consultarSiguiente['fecha_inicio']));
                    if($_POST['fechaFin'] != $consultarSiguiente['fecha_inicio'] && $consultarSiguiente['fecha_inicio'] > $_POST['fechaFin']){
                        $saveData = $Gnera->guardarFechaGeneracion($_POST);
                        echo json_encode($saveData);
                    }else{
                        echo 'Rango_Fecha_Uno';
                    }
                }else{
                    $saveData = $Gnera->guardarFechaGeneracion($_POST);
                    echo json_encode($saveData);
                }
            }
            break;

        case 'modificarFechasGeneracion':
            unset($_POST['action']);
            $obTotalCiclos = $Gnera->obtenerCicloTotalGeneracion($_POST['idGeneracion'])['data'];
            if ($_POST['numeroDeCiclo']==2) {
                $insertarfechalimitedepagoreins = $Gnera->insertarfechalimitedepagoreins($_POST['idGeneracion'],$_POST['fechaInicio']);
            }
            if ($_POST['numeroDeCiclo']>2) {//establecer fechas limite de pago reinscripcion
                $numerodeciclo= $_POST['numeroDeCiclo']-2;
                $obtenerconceptos = $Gnera->obtenerconceptos($_POST['idGeneracion']);
                $actualizarfechalimitedepago = $Gnera->actualizarfechalimitedepago($numerodeciclo,$obtenerconceptos['data']['id_concepto'],$_POST['fechaInicio']);
            }
            if($_POST['numeroDeCiclo']>1){
                if($_POST['numeroDeCiclo'] == $obTotalCiclos['numero_ciclos']){
                    $consultarAnterior = $Gnera->AnteriorFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                    if($consultarAnterior!=[]){
                        if($_POST['fechaInicio'] != $consultarAnterior['fecha_fin'] && $consultarAnterior['fecha_fin'] < $_POST['fechaInicio']){
                            $changeData = $Gnera->modificarFechasGeneracion($_POST);
                            echo json_encode($changeData);
                        }else{
                            echo 'Rango_Fecha';
                        }
                    }else{
                        $changeData = $Gnera->modificarFechasGeneracion($_POST);
                        echo json_encode($changeData);
                    }
                }else{
                    $consultarAnterior = $Gnera->AnteriorFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                    $consultarSiguiente = $Gnera->siguienteFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                    if($consultarAnterior!=[]){
                        $consultarAnterior['fecha_fin'] = date("Y-m-d", strtotime($consultarAnterior['fecha_fin']));
                    }
                    if($consultarSiguiente!=[]){
                        $consultarSiguiente['fecha_inicio'] = date("Y-m-d", strtotime($consultarSiguiente['fecha_inicio']));
                    }
                    if($consultarAnterior!=[] || $consultarSiguiente!=[]){
                        if($consultarAnterior!=[] && $consultarSiguiente !=[]){
                            if($_POST['fechaFin'] != $consultarSiguiente['fecha_inicio'] && $consultarSiguiente['fecha_inicio'] > $_POST['fechaFin']){
                                if($_POST['fechaInicio'] != $consultarAnterior['fecha_fin'] && $consultarAnterior['fecha_fin'] < $_POST['fechaInicio']){
                                    $changeData = $Gnera->modificarFechasGeneracion($_POST);
                                    echo json_encode($changeData);
                                }else{
                                    echo 'Rango_Fecha';
                                }
                            }else{
                                echo 'Rango_Fecha_Uno';
                            }
                        }else{
                            if($consultarAnterior!=[]){
                                if($_POST['fechaInicio'] != $consultarAnterior['fecha_fin'] && $consultarAnterior['fecha_fin'] < $_POST['fechaInicio']){
                                    $changeData = $Gnera->modificarFechasGeneracion($_POST);
                                    echo json_encode($changeData);
                                }else{
                                    echo 'Rango_Fecha';
                                }
                            }
                            if($consultarSiguiente !=[]){
                                if($_POST['fechaFin'] != $consultarSiguiente['fecha_inicio'] && $consultarSiguiente['fecha_inicio'] > $_POST['fechaFin']){
                                    $changeData = $Gnera->modificarFechasGeneracion($_POST);
                                    echo json_encode($changeData);
                                }else{
                                    echo 'Rango_Fecha_Uno';
                                }
                            }
                        }
                    }else{
                        $changeData = $Gnera->modificarFechasGeneracion($_POST);
                        echo json_encode($changeData);
                    }
                }
            }else{
                $consultarSiguiente = $Gnera->siguienteFecha($_POST['idGeneracion'], $_POST['numeroDeCiclo'])['data'];
                if($consultarSiguiente!=[]){
                    $consultarSiguiente['fecha_inicio'] = date("Y-m-d", strtotime($consultarSiguiente['fecha_inicio']));
                    if($_POST['fechaFin'] != $consultarSiguiente['fecha_inicio'] && $consultarSiguiente['fecha_inicio'] > $_POST['fechaFin']){
                        $changeData = $Gnera->modificarFechasGeneracion($_POST);
                        echo json_encode($changeData);
                    }else{
                        echo 'Rango_Fecha_Uno';
                    }
                }else{
                    $changeData = $Gnera->modificarFechasGeneracion($_POST);
                    echo json_encode($changeData);
                }
            }
            break;

        case 'obtenerDocumentosGeneracion':
            unset($_POST['action']);
            $obDocGen = $Gnera->obtenerDocumentosGeneracion($_POST);
            $data = Array();
            while($dato=$obDocGen->fetchObject()){
                $data[]=array(
                    0=> $dato->nombre_documento,
                    1=> $dato->bloqueo_digital === '1' ? 'Activado' : ($dato->bloqueo_digital === '2' ? 'Desactivado' : ''),
                    2=> $dato->fecha_digital,
                    3=> $dato->bloqueo_fisico === '1' ? 'Activado' : ($dato->bloqueo_fisico === '2' ? 'Desactivado' : ''),
                    4=> $dato->fecha_fisico,
                    5=> '<button class="btn btn-success" data-toggle="modal" data-target="#modalDatosBloqueo" onclick="vistaDatosBloqueo('.$dato->id_bloqueo.','.$dato->id_generacion.')">Asignar Bloqueo</button> '
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

        case 'obtenerListaDocumentosGeneracion':
            unset($_POST['action']);
            $obListDocGen = $Gnera->obtenerListaDocumentosGeneracion($_POST);
            echo json_encode($obListDocGen);
            break;

        case 'asignarBloqueoDocumento':
            unset($_POST['action']);
            
            if($_POST['selectBloqueoDigital'] == 1){
                $_POST['fecha_digital'] = date('Y-m-d H:i:s', strtotime($_POST['fechaBloqueoDigital'].",".$_POST['horaBloqueoDigital']));
                unset($_POST['fechaBloqueoDigital']);
                unset($_POST['horaBloqueoDigital']);
            }else{
                $_POST['fecha_digital'] = NULL;
                unset($_POST['fechaBloqueoDigital']);
                unset($_POST['horaBloqueoDigital']);
                //$_POST['fecha_fisico']
            }

            if($_POST['selectBloqueoFisico'] == 1){
                $_POST['fecha_fisico'] = date('Y-m-d H:i:s', strtotime($_POST['fechaBloqueoFisico'].",".$_POST['horaBloqueoFisico']));
                unset($_POST['fechaBloqueoFisico']);
                unset($_POST['horaBloqueoFisico']);
            }else{
                $_POST['fecha_fisico'] = NULL;
                unset($_POST['fechaBloqueoFisico']);
                unset($_POST['horaBloqueoFisico']);
                //$_POST['fecha_fisico']
            }
            //var_dump($_POST);
            //die();

            $asignacionBloqueo = $Gnera->asignarBloqueoDocumento($_POST);
            echo json_encode($asignacionBloqueo);
            break;

        case 'recuperarAsignarBloqueo':
            unset($_POST['action']);
            $recAsigBloq = $Gnera->recuperarAsignarBloqueo($_POST)['data'];
            
            if($recAsigBloq['bloqueo_fisico'] != null){
                $recAsigBloq['fecha_bloqueo_fisico'] = date("Y-m-d", strtotime($recAsigBloq['fecha_fisico']));
                $recAsigBloq['hora_fisico'] = date("H:i:s", strtotime($recAsigBloq['fecha_fisico']));
                unset($recAsigBloq['fecha_fisico']);
            }else{
                $recAsigBloq["fecha_bloqueo_fisico"] = $recAsigBloq["fecha_fisico"];
                $recAsigBloq["hora_fisico"] = $recAsigBloq["fecha_fisico"];
                unset($recAsigBloq['fecha_fisico']);
            }

            if($recAsigBloq['bloqueo_digital'] != null){
                $recAsigBloq['fecha_bloqueo_digital'] = date("Y-m-d", strtotime($recAsigBloq['fecha_digital']));
                $recAsigBloq['hora_digital'] = date("H:i:s", strtotime($recAsigBloq['fecha_digital']));
                unset($recAsigBloq['fecha_digital']);
            }else{
                $recAsigBloq['fecha_bloqueo_digital'] = $recAsigBloq['fecha_digital'];
                $recAsigBloq['hora_digital'] = $recAsigBloq['fecha_digital'];
                unset($recAsigBloq['fecha_digital']);
            }
            //$datosTarea['fecha'] = date("Y-m-d", strtotime($datosTarea['fecha_entrega']));
            //$datosTarea['hora'] = date("H:m:s", strtotime($datosTarea['fecha_entrega']));
            //unset($datosTarea['fecha_entrega']);
            echo json_encode($recAsigBloq);
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
