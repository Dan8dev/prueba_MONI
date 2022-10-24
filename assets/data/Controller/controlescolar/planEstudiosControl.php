<?php
session_start();
if (isset($_POST["action"])){
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/controlescolar/planEstudiosModel.php';
    $plEst = new PlanEstudios();

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }

    $accion=@$_POST["action"];

    switch($accion){
        case 'obtenerCarreras':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $obCarr = $plEst->obtenerCarreras($id)['data'];
            echo json_encode($obCarr);
            break;

        case 'obtenerListaPlanEstudio':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $ListaPlanesEstudios = $plEst->obtenerListaPlanEstudio($id,$_POST);
            echo json_encode($ListaPlanesEstudios['data']);
            break;

        case 'crearPlanEstudios':
            unset($_POST['action']);
            $VerificaClave = $plEst->obtenerClavePlan($_POST['clavePlanE'],1,0);
            
            if($VerificaClave['data'] != "Clave_existente"){
                if($_POST['tipoRvoeCrear']!=0){
                    $fCreado = date('Y-m-d H:i:s');
                    $_POST['fCreado'] = $fCreado;
                    $crearP = $plEst->crearPlanEstudiosRvoe($_POST);
                    echo json_encode($crearP);
                }else{
                    unset($_POST['rvoePlanEstudiosCrear']);
                    unset($_POST['FecharvoePlanEstudiosCrear']);
                    $fCreado = date('Y-m-d H:i:s');
                    $_POST['fCreado'] = $fCreado;
                    $crearP = $plEst->crearPlanEstudios($_POST);
                    echo json_encode($crearP);
                }
                $insertarnumreins = $plEst->insertarNumReinsconceptoscarreras($_POST['selectCarreraPlanE'],$_POST['numeroCiclosPlanE']);
            }else{
                echo json_encode($VerificaClave);   
            }
            $insertarnumreins = $plEst->insertarNumReinsconceptoscarreras($_POST['selectCarreraPlanE'],$_POST['numeroCiclosPlanE']);
            break;

        case 'obtenerPlanesEstudio':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $obPlanEst = $plEst->obtenerPlanesEstudio($id);
            $data = Array();
            while($dato = $obPlanEst->fetchObject()){
                $data[] = array(
                    0=> $dato->nombre,
                    1=> $dato->nombreCarr,
                    2=> $dato->tipo_ciclo === '1' ? 'Cuatrimestre' : ($dato->tipo_ciclo === '2' ? 'Semestre' : 'Trimestre'),
                    3=> $dato->numero_ciclos,
                    4=> $dato->clave_plan,
                    5=> $dato->tipo_rvoe === '1' ? 'Estatal' : ($dato->tipo_rvoe === '2' ? 'Federal' : '<p style="color:#AA262C">Sin Asignar</p>'),
                    6=> $dato->rvoe,
                    7=> date('d-m-Y',strtotime($dato->fecha_creado)),
                    8=>'<button class="btn btn-success" data-toggle="modal" data-target="#" onclick="verPlanEstudio('.$dato->id_plan_estudio.')">Ver</button> '.
                        '<button class="btn btn-secondary" data-toggle="modal" data-target="#modalAsigMaterias" onclick="asignarPlanEstudio('.$dato->id_plan_estudio.')">Asignar</button> '.
                        '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModPlanE" onclick="buscarPlanEstudio('.$dato->id_plan_estudio.')">Modificar</button> '/*.
                        '<button class="btn btn-danger" onclick="validarEliminarPlanEstudio('.$dato->id_plan_estudio.')">Eliminar</button>'*/
                );
            }
            /*
            4478AA
            b3983f*/
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case 'obtenerPlanEstudio':
            unset($_POST['action']);
            $obPlan = $plEst->obtenerPlanEstudio($_POST)['data'];
            echo json_encode($obPlan);
            break;

        case 'modificarPlanEstudios':
        $VerificaClave = $plEst->obtenerClavePlan($_POST['modClavePlanE'],2,$_POST['id_plan_estudio']);
        if($VerificaClave['data']=='Registrar'){
                unset($_POST['action']);
                $clAnt = $_POST['claveSepAntPlan'];
                unset($_POST['claveSepAntPlan']);
                
                if(empty($_POST['rvoePlanEstudios'])){
                    if($_POST['rvoePlanEstudios'] == $clAnt){
                        unset($_POST['rvoePlanEstudios']);
                        unset($_POST['FecharvoePlanEstudiosEditar']);
                        $fModificado = date('Y-m-d H:i:s');
                        $_POST['fModificado'] = $fModificado;
                        $modPlanE = $plEst->modificarPlanEstudiosSinClave($_POST);
                        echo json_encode($modPlanE);
                    }else{
                        unset($_POST['rvoePlanEstudios']);
                        unset($_POST['FecharvoePlanEstudiosEditar']);
                        $fModificado = date('Y-m-d H:i:s');
                        $_POST['fModificado'] = $fModificado;
                        $modPlanE = $plEst->modificarPlanEstudiosNull($_POST);
                        echo json_encode($modPlanE);
                    }
                }
    
                if(!empty($_POST['rvoePlanEstudios'])){
                    $fModificado = date('Y-m-d H:i:s');
                    $_POST['fModificado'] = $fModificado;
                    $modPlanE = $plEst->modificarPlanEstudiosClave($_POST);
                    echo json_encode($modPlanE);
                }
                $obtenergeneracion = $plEst->obtenerGeneracion($_POST['id_plan_estudio']);
                if ($obtenergeneracion['data']) {
                    $insertarnumreins = $plEst->insertarNumReinsconceptos($obtenergeneracion['data']['idGeneracion'],$_POST['modNumeroCiclosPlanE']);
                }
            }else{
                echo json_encode(['estatus'=>'clave_existente','data'=>'0']);
            }

            break;

        case 'eliminarPlanEstudios':
            unset($_POST['action']);
            $delPlanEst = $plEst->eliminarPlanEstudios($_POST);
            echo json_encode($delPlanEst);
            break;

        case 'buscarPlanEstudios':
            unset($_POST['action']);
            $busPlanE = $plEst->buscarPlanEstudios($_POST)['data'];
            /*
            
                foreach($busPlanE as $var => $value){
                    $buscPlanE[$var]['materias'] = $plEst->obtenerMateriasAsignadasPlan($buscPlanE[$var]['id'], $ciclo)
                }
            
            */ 
            echo json_encode($busPlanE);
            break;

        case 'obtenerMateriasSinAsignar':
            unset($_POST['action']);
            $obMatAsignar = $plEst->obtenerMateriasSinAsignar($_POST)['data'];
            if($obMatAsignar==[]){
                echo 'no_materias';
            }else{
                echo json_encode($obMatAsignar);
            }
            break;

        case 'obtenerMateriasSinAsignarConacon':
            unset($_POST['action']);
            $obMatAsignar = $plEst->obtenerMateriasSinAsignarConacon($_POST)['data'];
            if($obMatAsignar==[]){
                echo 'no_materias';
            }else{
                echo json_encode($obMatAsignar);
            }
            break;
        
        case 'obtenerMateriasAsignadasPlan':
            unset($_POST['action']);
            $obAsigCiclo = $plEst->obtenerMateriasAsignadasPlan($_POST)['data'];
            echo json_encode($obAsigCiclo);
            break;

        case 'borrarMateria':
            unset($_POST['action']);
            $borrMat = $plEst->borrarMateria($_POST);
            echo json_encode($borrMat);
            break;

        case 'guardarMateriasAsignadas':
            unset($_POST['action']);
            $idPlan = $_POST['idPlanEstudioAsigMat'];
            unset($_POST['idPlanEstudioAsigMat']);
            if(count($_POST)>1){
                echo 'vaciar_un_campo';
            }else{
                if(empty($_POST)){
                    echo 'asig_vacio';
                }else{
                    foreach($_POST as $nombre=>$valor){
                        $nameAsig = $nombre;
                        $valAsig = $valor;
                    }
                    $cicloSelect = explode('asigMaterias', $nameAsig)[1];
                    for($p = 0 ; $p < count($valAsig) ; $p++){
                        $addAsigPlanMat = $plEst->guardarMateriasAsignadas($idPlan, $cicloSelect, $valAsig[$p]);
                    }
                    $devIdPlan = $plEst->idPlanAsignado($addAsigPlanMat['data']);
                    echo json_encode($devIdPlan);
                }
            }
            /*if(empty($_POST)){
                echo 'asig_vacio';
            }else{
                foreach($_POST as $nombre=>$valor){
                    $nameAsig = $nombre;
                    $valAsig = $valor;
                }
                $cicloSelect = explode('asigMaterias', $nameAsig)[1];
                for($p = 0 ; $p < count($valAsig) ; $p++){
                    $addAsigPlanMat = $plEst->guardarMateriasAsignadas($idPlan, $cicloSelect, $valAsig[$p]);
                }
                $devIdPlan = $plEst->idPlanAsignado($addAsigPlanMat['data']);
                echo json_encode($devIdPlan);
            }*/
            break;

        case 'validarCrearPDFPlanEstudios':
            unset($_POST['action']);
            $valPDFPlanE = $plEst->validarCrearPDFPlanEstudios($_POST)['data'];
            foreach($valPDFPlanE as $campo => $value){
                if($campo='numero_ciclos'){
                    $totalCiclos = $valPDFPlanE['numero_ciclos'];
                }
            }
            $obTotalPlanAsig = $plEst->contarAsignacionesPlanE($_POST)['data'];
            //var_dump($totalCiclos);
            //var_dump(count($obTotalPlanAsig));
            if($totalCiclos == count($obTotalPlanAsig)){
                echo 'asignado';
            }else{
                echo 'falta_asignar';
                //'<a href="plan_estudio.php" target="_blank">';
            }

            break;

        case 'obtenerCarrerasMod':
            unset($_POST['action']);
            $obCarrerMod = $plEst->obtenerCarrerasMod($_POST)['data'];
            echo json_encode($obCarrerMod);
            break;

        case 'validarCarreraCertificacionMod':
            unset($_POST['action']);
            $esCertif = $plEst->validarCarreraCertificacionMod($_POST)['data'];
            echo json_encode($esCertif);
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
