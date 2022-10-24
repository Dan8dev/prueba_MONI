<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/planpagosModel.php';
	require_once '../../Model/planpagos/pagosModel.php';

    require_once '../../Model/planpagos/generacionesModel.php';
    $planpagos = new PlanPagos();
	$pagosM = new pagosModel();

    $generacionM = new Generaciones();

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }
    $accion=@$_POST["action"];

    switch ($accion) {
        case 'ValidarPagosAlumnosCertificaciones':
            unset($_POST['action']);
            $obAlumnosCarr = $planpagos->buscarAlumnosCarrera($_POST);

            $data = Array();
            $i=0;
            $LetreroEstatus = "";
                while($dato=$obAlumnosCarr->fetchObject()){
                    switch($dato->estatus){
                        case '1';
                            $LetreroEstatus = "<small>EL ALUMNO SE ENCUENTRA ACTIVO</small>";
                            break;
                        case '2';
                            $LetreroEstatus = "<small>ALUMNO CON BAJA</small>";
                            break;
                        case '3';
                            $LetreroEstatus = "<small>EGRESADO</small>";
                            $i++;
                            $data[]=array(
                                0=> '<b>'.$dato->nombre.'</b><br>'.$LetreroEstatus,
                                1=> 'Alumno con documentos al corriente',
                                2=> "<button class= 'btn btn-secondary' onClick='Validar_Pagos({$dato->idalumno})'> Validar Pagos</button>",
                            );
                            break;
                        case '4';
                            $LetreroEstatus = "<small>TITULADO</small>";
                            break;
                        case '5';
                            $LetreroEstatus = "<small>EXPULSADO</small>";
                            break;
                    }   
                }
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );

            echo json_encode($result);
            break;

        case 'obtenerGeneracionesCarrera':
            unset($_POST['action']);
            $obGen = $planpagos->obtenerGeneracionesCarrera($_POST)['data'];
            if($obGen != []){
                echo json_encode($obGen);
            }else{
                echo 'sin_generaciones';
            }
            break;
    

        case 'Validar_pagos_alumno':
            unset($_POST['action']);
            $validarP = $planpagos->Validar_pagos_alumno($_POST);
            echo json_encode($validarP);
            break;

        case 'obtenerAfiliados':
            unset($_POST['action']);
            $obAfi = $planpagos->obtenerAfiliados()['data'];
            echo json_encode($obAfi);
            break;

        case 'obtenerCarreras':
            unset($_POST['action']);
            $obCarr = $planpagos->obtenerCarreras()['data'];
            if($obCarr==[]){
                echo 'sin_carrera';
            }else{
            echo json_encode($obCarr);
            }
            break;

        case 'obtenerEventos':
            unset($_POST['action']);
            $obEven = $planpagos->obtenerEventos()['data'];
            if($obEven==[]){
                echo 'sin_evento';
            }else{
                echo json_encode($obEven);
            }
            break;

        case 'crearPlanPago':
            /*require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");*/
            unset($_POST['action']);
            $tCarrera = $_POST['tipoCarrera'];
            unset($_POST['tipoCarrera']);
            $evt = $_POST['evtAct'];
            unset($_POST['evtAct']);

            if($evt == 0){
                if($tCarrera==0 || $tCarrera==2|| $tCarrera==5|| $tCarrera==4|| $tCarrera==6|| $tCarrera==3|| $tCarrera==7){
                    $saveG = 1;
                }
                if($tCarrera==1){
                    $saveG = 2;
                }
            }
            if($evt == 1){
                $saveG = 2;
            }
            
            if($saveG == 1){//Crear conceptos mens inscripcion reinscripcion titulacion

                    //nuevo
                    $asignacion = $_POST['selectAsignacion'];
                    unset($_POST['selectAsignacion']);
                    if($asignacion == 1){
                        foreach($_POST as $campo => $valor){
                            if($campo == 'selectAfiliados'){
                                $var1 = $valor;
                            }
                        }
                        unset($_POST['selectAfiliados']);
                    }
                    if($asignacion == 2){
                        $id_carrera= $_POST['selectCarreras'];
                        unset($_POST['selectCarreras']);
                    }

                    $fCreado = date('Y-m-d H:i:s');
                    $_POST['fCreado'] = $fCreado;        
                    $costoI = $_POST['costoInscripcion'];
                    $numeroM = $_POST['nMensualidades'];
                    $costoM = $_POST['costoMensualidad'];
                    $costoMusd = $_POST['costoMensualidadusd'];
                    $numeroR = 1;
                    $costoRusd = $_POST['costoReinscripcionusd'];
                    $costoR = $_POST['costoReinscripcion'];
                    $costoIusd = $_POST['costoInscripcionusd'];
                    $costotitulacion = $_POST['costotitulacion'];
                    $costotitulacionusd = $_POST['costotitulacionusd'];
                    $fechalimitepagoins= $_POST['fechalimitepagoins'];
                    $fechalimitepagotit= $_POST['fechalimitepagotit'];
                    $diadecorte= $_POST['diasdecorte'];
                    if (strlen($diadecorte)==1) {
                        $diadecorte = '0'.$diadecorte;
                    }
                    $fecha='1900-00';
                    $guardafechacorte = date('Y-m-d',strtotime(''.$fecha.'-'.$diadecorte.''));
                    unset($_POST['costoInscripcion']);
                    unset($_POST['nMensualidades']);
                    unset($_POST['costoMensualidad']);
                    unset($_POST['nReinscripcion']);
                    unset($_POST['costoReinscripcion']);
                    unset($_POST['costotitulacion']);
                    unset($_POST['fechalimitepagoins']);
                    unset($_POST['fechalimitepagotit']);
                    unset($_POST['diasdecorte']);
                    
                    unset($_POST['costoMensualidadusd']);
                    unset($_POST['costoReinscripcionusd']);
                    unset($_POST['costoInscripcionusd']);
                    unset($_POST['costotitulacionusd']);
                    unset($_POST['nMensualidadesusd']);
                    
                    $crearP = $planpagos->crearPlanPago($_POST);

                    if($asignacion == 1){
                        for($i=0; $i < count($var1); $i++){
                            $addAfi = $planpagos->addAfiliado($crearP['data'], $var1[$i], $_POST['fCreado']);
                        }
                    }
                    if($asignacion == 2){
                            $addCarr = $planpagos->addCarrera($crearP['data'], $id_carrera, $_POST['fCreado']);
                    }
                    /* crear plan de pago en conekta para concepto de mensualidad */
                    /*$plan = \Conekta\Plan::create(array(
						'name' => $_POST['nombreplan'],
						'amount' => $costoM*100,
						'currency' => "MXN",
						'interval' => 'month',
						'frequency' => 1,
						'expiry_count' => $numeroM
					));

                    $id_plan_conekta=$plan->id;*/

                    $crearCP = $planpagos->crearPlanPagoConcepto($costotitulacion, $costotitulacionusd, $costoI, $costoIusd, $numeroM, $costoM, $costoMusd, $numeroR, $costoR, $costoRusd, $_POST['fCreado'], $_POST['creador_por'], $crearP['data'], $_POST['nombreplan'],$fechalimitepagoins,$fechalimitepagotit,$guardafechacorte);
                    // aqui metio mano chuy
                    // buscar una generacion que se haya asignado a la carrera
                    if(isset($id_carrera)){
                        $generaciones = $generacionM->obtenerListaGeneraciones_carrera($id_carrera);
                        if(sizeof($generaciones['data']) > 0){
                            foreach($generaciones['data'] as $generacion_i){
                                $con_gen = $generacionM->obtener_conceptos_generacion($generacion_i['idGeneracion']);
                                if(!$con_gen){
                                    $generacion = $generacion_i['idGeneracion'];
                                    $nomGen = $generacion_i['nombre'];
                                    $plan_pago_concepto = $planpagos->crearPlanPagoConcepto_Generacion($costotitulacion, $costotitulacionusd,$costoI, $costoIusd, $numeroM, $costoM, $costoMusd, $numeroR, $costoR, $costoRusd, $_POST['fCreado'], $_POST['creador_por'], $generacion, $nomGen,$fechalimitepagoins,$fechalimitepagotit,$guardafechacorte);
                                }
                            }
                        }
                    }

                    echo json_encode($crearCP);
                
            }

            if($saveG == 2){//Crear solo concepto de inscripcion
                if(empty($_POST['costoInscripcion'])){
                    echo 'numero_invalido';
                }else{
                    $asignacion = $_POST['selectAsignacion'];
                    unset($_POST['selectAsignacion']);

                    if($asignacion == 2){//crear carrera ota
                        $id_carrera=$_POST['selectCarreras'];
                        unset($_POST['selectCarreras']);
                    }
                    if($asignacion == 4){//crear evento
                        $id_evento=$_POST['selectEventos'];
                        unset($_POST['selectEventos']);
                    }
                    
                    $fCreado = date('Y-m-d H:i:s');
                    $_POST['fCreado'] = $fCreado;    
                    $costoI = $_POST['costoInscripcion'];
                    $costoIusd = $_POST['costoInscripcionusd'];
                    $fechalimitepagoins= $_POST['fechalimitepagoins'];
                    unset($_POST['costoInscripcion']);
                    unset($_POST['nMensualidades']);
                    unset($_POST['costoMensualidad']);
                    unset($_POST['nReinscripcion']);
                    unset($_POST['costoReinscripcion']);
                    unset($_POST['costotitulacion']);
                    unset($_POST['fechalimitepagotit']);
                    unset($_POST['fechalimitepagoins']);
                    unset($_POST['diasdecorte']);

                    unset($_POST['costoMensualidadusd']);
                    unset($_POST['costoReinscripcionusd']);
                    unset($_POST['costoInscripcionusd']);
                    unset($_POST['costotitulacionusd']);
                    unset($_POST['nMensualidadesusd']);
                    
                    $crearP = $planpagos->crearPlanPago($_POST);
                    if($asignacion == 2){//actualizar id carrera en plan de pago para asignar carrera
                            $addCarr = $planpagos->addCarrera($crearP['data'], $id_carrera);
                    }
                    if($asignacion == 4){//actualizar id evento en plan de pago para asignar evento
                            $addEvent = $planpagos->addEventos($crearP['data'], $id_evento);
                    }
                    $crearCP = $planpagos->crearPlanPagoConceptoCer($costoI,$costoIusd, $_POST['fCreado'], $_POST['creador_por'], $crearP['data'], $_POST['nombreplan'],$fechalimitepagoins);
                    
                    // aqui metio mano chuy
                    // buscar una generacion que se haya asignado a la carrera
                    if(isset($id_carrera)){
                        $generaciones = $generacionM->obtenerListaGeneraciones_carrera($id_carrera);
                        if(sizeof($generaciones['data']) > 0){
                            foreach($generaciones['data'] as $generacion_i){
                                $generacion = $generacion_i['idGeneracion'];
                                $con_gen = $generacionM->obtener_conceptos_generacion($generacion);
                                if(!$con_gen){
                                    $generacion = $generacion_i['idGeneracion'];
                                        $nomGen = $generacion_i['nombre'];
                                    $plan_pago_concepto = $planpagos->crearPlanPagoConceptoCer_Generacion($costoI,$costoIusd, $_POST['fCreado'], $_POST['creador_por'], $generacion, $nomGen,$fechalimitepagoins);
                                }
                            }
                        }
                    }

                    echo json_encode($crearCP);
                }
            }

            break;

        case 'obtenerPlanesPago':
            unset($_POST['action']);
            $obPlan = $planpagos->obtenerPlanesPago();
            $data = Array();
            while($dato= $obPlan->fetchObject()){
                $data[] = array(
                    0=> $dato->nombre,
                    1=> number_format($dato->total),
                    2=> $dato->nombreCarrer,
                    3=> $dato->nombreEvent,
                    4=>'<a class="btn btn-primary" data-toggle="modal" data-target="#modalModPlan" onclick="buscarPlanPago('.$dato->idPlanPago.')">Modificar</a> '
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

        case 'buscarPlan':
            unset($_POST['action']);
            $busPlan = $planpagos->buscarPlan($_POST);
            echo json_encode($busPlan);
            break;

        case 'obtenerAfiliadosMod':
            unset($_POST['action']);
            $obAfiMod = $planpagos->obtenerListaAfiliadosMod($_POST)['data'];
            echo json_encode($obAfiMod);
            break;

        case 'obtenerCarrerasMod':
            unset($_POST['action']);
            $obCarrMod = $planpagos->obtenerListaCarrerasMod($_POST)['data'];
            echo json_encode($obCarrMod);
            break;

        case 'obtenerEventosMod':
            unset($_POST['action']);
            $obEvent = $planpagos->obtenerListaEventosMod($_POST)['data'];
            echo json_encode($obEvent);
            break;

        case 'buscarAfiliadosMod':
            unset($_POST['action']);
            $busAfi = $planpagos->buscarAfiliadosMod($_POST)['data'];
            echo json_encode($busAfi);
            break;

        case 'buscarCarrerasMod':
            unset($_POST['action']);
            $busCarr = $planpagos->buscarCarrerasMod($_POST)['data'];
            echo json_encode($busCarr);
            break;

        case 'buscarEventosMod':
            unset($_POST['action']);
            $busEvent = $planpagos->buscarEventosMod($_POST)['data'];
            echo json_encode($busEvent);
            break;

        case 'modificarPlan':
            unset($_POST['action']);
            
            $tCarreraMod = $_POST['tipoCarreraMod'];
            unset($_POST['tipoCarreraMod']);
            $evtMod = $_POST['evtActMod'];
            unset($_POST['evtActMod']);
            
            if($evtMod == 0){
                if($tCarreraMod==0 || $tCarreraMod==2|| $tCarreraMod==5|| $tCarreraMod==4|| $tCarreraMod==6|| $tCarreraMod==3|| $tCarreraMod==7){
                    $saveGMod = 1;
                }
                if($tCarreraMod==1){
                    $saveGMod = 2;
                }
            }
            if($evtMod == 1){
                $saveGMod = 2;
            }
            
            
            if($saveGMod == 1){//Crear conceptos mens inscripcion reinscripcion titulacion
                
                    $asignacion = $_POST['nuevoSelectAsignacion'];
                                                                                                                                                                                            
                        unset($_POST['nuevoSelectAsignacion']);
                        
                        unset($_POST['selectAnteriorPlan']);
                    
                        $fModificado = date('Y-m-d H:i:s');
                        $_POST['fModificado'] = $fModificado;

                        $idconceptoins = $_POST['idconceptoins'];
                        $idconceptomens = $_POST['idconceptocostomens'];
                        $idconceptoreins = $_POST['idconceptocostoreins'];
                        $idconceptotit = $_POST['idconceptocostotit'];

                        $nuevoCostoI = $_POST['nuevoCostoInscripcion'];
                        $nuevoCostoIusd = $_POST['nuevoCostoInscripcionusd'];
                        $nuevafechalimitdepagoins = $_POST['nuevafechalimitedepagoins'];
                        $nuevoNumeroM = $_POST['nuevoNoMensualidades'];
                        $nuevoCostoM = $_POST['nuevoCostoMensualidad'];
                        $nuevoCostoMusd = $_POST['nuevoCostoMensualidadusd'];
                        $nuevoNumeroR = $_POST['nuevoNoReinscripcion'];
                        $nuevoCostoR = $_POST['nuevoCostoReinscripcion'];
                        $nuevoCostoRusd = $_POST['nuevoCostoReinscripcionusd'];
                        $nuevodiacorte = $_POST['nuevodiasdecorte'];
                        $nuevoCostoTitulacion = $_POST['nuevoCostoTitulacion'];
                        $nuevoCostoTitulacionusd = $_POST['nuevoCostoTitulacionusd'];
                        $nuevafechalimitedepagotit = $_POST['nuevafechalimitedepagotit'];
                        unset($_POST['idconceptoins']);
                        unset($_POST['idconceptocostomens']);
                        unset($_POST['idconceptocostoreins']);
                        unset($_POST['idconceptocostotit']);

                        unset($_POST['nuevafechalimitedepagoins']);
                        unset($_POST['nuevoCostoInscripcion']);
                        unset($_POST['nuevoNoMensualidades']);
                        unset($_POST['nuevoCostoMensualidad']);
                        unset($_POST['nuevodiasdecorte']);
                        unset($_POST['nuevoNoReinscripcion']);
                        unset($_POST['nuevoCostoReinscripcion']);
                        unset($_POST['nuevoCostoTitulacion']);
                        unset($_POST['nuevoCostoInscripcionusd']);
                        unset($_POST['nuevoCostoMensualidadusd']);
                        unset($_POST['nuevoCostoReinscripcionusd']);
                        unset($_POST['nuevoCostoTitulacionusd']);
                        unset($_POST['nuevafechalimitedepagotit']);

                        if (strlen($nuevodiacorte)==1) {
                            $nuevodiacorte = '0'.$nuevodiacorte;
                        }
                        $fecha='1900-00';
                        $nuevodiacorte = date('Y-m-d',strtotime(''.$fecha.'-'.$nuevodiacorte.''));

                        $modPlan = $planpagos->modificarPlanPago($_POST);

                        if($asignacion == 1){
                            #alumno
                        }
                        if($asignacion == 2){//CONCEPTOS DE CARRERAS COMPLETAS
                            $modPlanCIns = $planpagos->modificarPlanPagoConceptosIns($nuevoCostoI,$nuevoCostoIusd, $_POST['fModificado'], $_POST['modificado_por'], $_POST['id'], $_POST['nuevonombre'],$idconceptoins,$nuevafechalimitdepagoins);
                            $modPlanCMen = $planpagos->modificarPlanPagoConceptosMen($nuevoNumeroM, $nuevoCostoM, $nuevoCostoMusd, $_POST['fModificado'], $_POST['modificado_por'], $_POST['id'], $_POST['nuevonombre'],$nuevodiacorte,$idconceptomens);
                            $modPlanCReins = $planpagos->modificarPlanPagoConceptosReins($nuevoNumeroR, $nuevoCostoR, $nuevoCostoRusd, $_POST['fModificado'], $_POST['modificado_por'], $_POST['id'], $_POST['nuevonombre'],$idconceptoreins);
                            $modPlanCTit = $planpagos->modificarPlanPagoConceptosTit($nuevoCostoTitulacion,$nuevoCostoTitulacionusd, $_POST['fModificado'], $_POST['modificado_por'], $_POST['id'], $_POST['nuevonombre'],$idconceptotit,$nuevafechalimitedepagotit);


                        }
                        if($asignacion == 4){
                            #CODE EVENTOS
                        }

                        
                        echo json_encode($modPlanCReins);
                    
                
            }

            if($saveGMod == 2){//Crear concepto inscripcion certificacion o evento
                if(empty($_POST['nuevoCostoInscripcion'])){
                    echo 'numero_invalido';
                }else{
                    unset($_POST['idconceptocostomens']);
                    unset($_POST['idconceptocostoreins']);
                    unset($_POST['idconceptocostotit']);

                    unset($_POST['selectAnteriorPlan']);
                    $asignacion = $_POST['nuevoSelectAsignacion']; //1=carrera de certificacion, 2=evento

                    unset($_POST['nuevoSelectAsignacion']);
                    if($asignacion == 2){//modificar carrera de certificacion
                        $fModificado = date('Y-m-d H:i:s');
                        $_POST['fModificado'] = $fModificado;
                        $nuevoCostoI = $_POST['nuevoCostoInscripcion'];
                        $idconceptoins=$_POST['idconceptoins'];
                        $nuevafechalimitedepagoinsevt=$_POST['nuevafechalimitedepagoins'];
                        unset($_POST['idconceptoins']);
                        unset($_POST['nuevoCostoInscripcion']);
                        unset($_POST['nuevoNoMensualidades']);
                        unset($_POST['nuevoCostoMensualidad']);
                        unset($_POST['nuevoNoReinscripcion']);
                        unset($_POST['nuevoCostoReinscripcion']);
                        unset($_POST['nuevafechalimitedepagoins']);
                        unset($_POST['nuevodiasdecorte']);
                        unset($_POST['nuevoCostoTitulacion']);
                        unset($_POST['nuevafechalimitedepagotit']);

                        $modPlan = $planpagos->modificarPlanPago($_POST);

                        
                        $modPlanCIns = $planpagos->modificarPlanPagoConceptosInsevento($nuevoCostoI, $_POST['fModificado'], $_POST['modificado_por'], $idconceptoins, $_POST['nuevonombre'],$nuevafechalimitedepagoinsevt);
                        
                        echo json_encode($modPlanCIns);
                    }
                    if($asignacion == 4){//modificar evento
                        $fModificado = date('Y-m-d H:i:s');
                        $_POST['fModificado'] = $fModificado;
                        $nuevoCostoI = $_POST['nuevoCostoInscripcion'];
                        $idconceptoins=$_POST['idconceptoins'];
                        $nuevafechalimitedepagoinsevt=$_POST['nuevafechalimitedepagoins'];
                        unset($_POST['idconceptoins']);
                        unset($_POST['nuevoCostoInscripcion']);
                        unset($_POST['nuevoNoMensualidades']);
                        unset($_POST['nuevoCostoMensualidad']);
                        unset($_POST['nuevoNoReinscripcion']);
                        unset($_POST['nuevoCostoReinscripcion']);
                        unset($_POST['nuevafechalimitedepagoins']);
                        unset($_POST['nuevodiasdecorte']);
                        unset($_POST['nuevoCostoTitulacion']);
                        unset($_POST['nuevafechalimitedepagotit']);

                        $modPlan = $planpagos->modificarPlanPago($_POST);

                        
                        $modPlanCIns = $planpagos->modificarPlanPagoConceptosInsevento($nuevoCostoI, $_POST['fModificado'], $_POST['modificado_por'], $idconceptoins, $_POST['nuevonombre'],$nuevafechalimitedepagoinsevt);
                        
                        echo json_encode($modPlanCIns);
                    }
                }

            }
            break;

        case 'eliminarPlan':
            unset($_POST['action']);
            $delPlan = $planpagos->eliminarPlan($_POST);
            echo json_encode($delPlan);
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
