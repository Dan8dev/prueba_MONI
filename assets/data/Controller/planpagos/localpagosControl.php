<?php
if (@$_GET['detalle_pago_oxxo'] == true) {
    $body = @file_get_contents('php://input');
$data = json_decode($body);
$data1='hola respuesta conekta';
http_response_code(200); // Return 200 OK 
    require_once '../../Model/conexion/conexion.php';
    require("../../functions/mailer.php");

    if ($data->type == 'charge.paid'){

								  $destinatarios = [["jorgegbp@gmail.com","Sistemas"]];
								  $asunto = 'RESPUESTA CONEKTA';
								  $message = 'grats';
								  $adjunto='none';
								  $result=sendEmailOwn($destinatarios, $asunto, $data1, $adjunto);
    }
    
    
  

die();
}
session_start();
if (isset($_POST["action"])) {
    setlocale(LC_MONETARY, 'en_US');

    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/carreras/carrerasModel.php';
    require_once '../../Model/eventos/eventosModel.php';
    require_once '../../Model/planpagos/planpagosModel.php';

    require_once '../../Model/planpagos/promocionesModel.php';
    require_once '../../Model/planpagos/pagosModel.php';

    require_once '../../Model/prospectos/prospectosModel.php';
    require_once '../../Model/planpagos/generacionesModel.php';

    require_once '../../Model/planpagos/vistasModel.php';
    require_once '../../Model/alumnos/alumnosInstitucionesModel.php';


    $accion=@$_POST["action"];
    $carreraM = new Carrera();
    $eventoM = new Evento();
    $p_pagosM = new PlanPagos();
    $pagosM = new pagosModel();
    $promoM = new Promociones();

    $prospectoM = new Prospecto();
    $generacionesM = new Generaciones();

    $vistasM = new Vistas();
    $alumnM = new AccesosAlumnosInstituciones();

    if(!isset($_SESSION['usuario']) && !isset($_SESSION['alumno'])){
        $_POST['action'] = 'no_session';
    }

    switch ($accion) {
        case 'consultarInscripciones':
            unset($_POST['action']);
            $resp = [];
            if(isset($_SESSION['alumno'])){
                $resp = $carreraM->consultar_inscripciones_afiliado($_SESSION['alumno']['id_prospecto'], 'carrera');
                $gen_asign = $generacionesM->generaciones_alumno($_SESSION['alumno']['id_prospecto']);
                foreach($gen_asign['data'] as $gen){
                    if(array_search($gen['idCarrera'], array_column($resp['data'], 'idCarrera')) === false){
                        $gen['asignado'] = true;
                        $resp['data'] = array_merge($resp['data'], [$gen]);
                    }else{
                        $resp['data'][array_search($gen['idCarrera'], array_column($resp['data'], 'idCarrera'))]['asignado'] = true;
                    }
                }

                $eventos = $carreraM->consultar_inscripciones_afiliado($_SESSION['alumno']['id_prospecto'], 'evento');
                // var_dump($eventos);
                // die();
                foreach($eventos['data'] as $gen){
                    $plan_asign = $eventoM->validar_evento_de_pago($gen['evento_carrera']);
                    if($plan_asign){
                        $resp['data'] = array_merge($resp['data'], [$gen]);
                    }
                }
            }
            echo json_encode($resp);
            break;
        case 'obtener_plan_pago':
            unset($_POST['action']);
            $resp = [];
            
            $plan_pago = null;

            $carrera_info = $carreraM->consultarCarreraByID($_POST['inscrito_a']);
            if($carrera_info['data']){
                #buscar plan de pago del afiliado
                if(isset($_SESSION['alumno'])){
                    $plan_pago = $p_pagosM->obtener_plan_pagos_('idAfiliado',$_SESSION['alumno']['id_prospecto']);
                    $plan_pago['orig'] = 'Afiliado';
                }
                #buscar plan de pago de la carrera
                if(sizeof($plan_pago['data']) == 0){
                    $plan_pago = $p_pagosM->obtener_plan_pagos_('idCarrera',$_POST['inscrito_a']);
                    $plan_pago['orig'] = 'Carrera';
                }
                #buscar plan de pago de la generacion
                if(sizeof($plan_pago['data']) == 0){
                    if($carrera_info['data']['idGeneracion'] != null){
                        $plan_pago = $p_pagosM->obtener_plan_pagos_('idGeneracion',$carrera_info['data']['idGeneracion']);
                        $plan_pago['orig'] = 'Generacion';
                    }else{
                        $resp = ['estatus'=>'error', 'info'=>'No se encontró el plan de pago de la carrera'];
                    }
                }

                if(sizeof($plan_pago['data']) == 0){
                    $resp = ['estatus'=>'error', 'info'=>'No se encontró el plan de pago.'];
                }else {
                    foreach ($plan_pago['data'] as $key => $value) {
                        $plan_pago['data'][$key]['pagos_aplicar'] = $p_pagosM->obtener_conceptos_plan($value['idPlan'])['data'];
                            foreach ($plan_pago['data'][$key]['pagos_aplicar'] as $key2 => $value2) {
                                $promocionesExist = $promoM->obtenerPromocion_byConcepto($value2['id_concepto'])['data'];
                                $promos_aplicar = [];
                                foreach ($promocionesExist as $promos => $promo_val) {
                                    if(isset($_SESSION['alumno']) && $promo_val['id_prospecto'] == $_SESSION['alumno']['id_prospecto']){
                                        array_push($promos_aplicar, $promo_val);
                                    }elseif($promo_val['id_carrera'] == $_POST['inscrito_a']){
                                        array_push($promos_aplicar, $promo_val);
                                    }elseif($promo_val['id_generacion'] == $carrera_info['data']['idGeneracion']){
                                        array_push($promos_aplicar, $promo_val);
                                    }
                                }
                                $plan_pago['data'][$key]['pagos_aplicar'][$key2]['promociones'] = $promos_aplicar;
                            }
                        if(isset($_SESSION['alumno'])){
                            foreach ($plan_pago['data'][$key]['pagos_aplicar'] as $key2 => $value2) {
                                $plan_pago['data'][$key]['pagos_aplicar'][$key2]['aplicados'] = $p_pagosM->obtener_pagos_aplicados($value2['id_concepto'],$_SESSION['alumno']['id_prospecto'])['data'];
                            }
                        }
                    }
                    $resp = $plan_pago;
                }
            }else{
                $resp = ['estatus'=>'error', 'info'=>'Carrera no identificada', 'data'=>$_POST['inscrito_a']];
            }

            echo json_encode($resp);
            break;
        case 'obtener_pagos_aplicados':
            $resp = [];
            if(isset($_POST['id_concepto']) && isset($_SESSION['alumno'])){
                $pagos = $p_pagosM->obtener_pagos_aplicados($_POST['id_concepto'], $_SESSION['alumno']['id_prospecto']);
                if($pagos['estatus'] == 'ok'){
                    foreach($pagos['data'] as $key => $value){
                        $detalle = json_decode($value['detalle_pago'], true);
                        $pagos['data'][$key]['detalle_pago'] = $detalle;
                        if ($pagos['data'][$key]['idPromocion'] != null && $pagos['data'][$key]['parcialidades'] == 2) {
                            $promo = $promoM->obtenerPromocion_byIDconcepto($pagos['data'][$key]['id_concepto']);
                            $costo_concepto = $pagos['data'][$key]['costototal']/$pagos['data'][$key]['numero_pagos'];
                            $costodescuento=$costo_concepto*$promo['data']['porcentaje']/100;
                            $pagos['data'][$key]['montopagado']=floatval($pagos['data'][$key]['montopagado'])+floatval($costodescuento);
                            $pagos['data'][$key]['porcentajedebecaopromo']=$promo['data']['porcentaje'];
                        }
                    }
                    $resp = $pagos['data'];
                }
            }
            echo json_encode($resp);
            break;
        case 'obtenercuentadepago':
            $obtener_api_key = $pagosM->obtener_api_key($_POST['id_concepto'])['data'];
            // var_dump($obtener_api_key);
            // die();
            if(!$obtener_api_key){
                $obtener_api_key = $pagosM->obtener_api_key_evento($_POST['id_concepto'])['data'];
            }
            if ($obtener_api_key['idInstitucion']==20) {// LLAVES negocio UDC
                $obtener_modo = $pagosM->obtener_modo_udc()['data'];//obtener datos de la cuenta UDC
                if ($obtener_modo['modo']==1) {// modo producción|
                    $api_key_publica = $obtener_modo['api_key_public_prod'];
                }
                if ($obtener_modo['modo']==2) {
                    $api_key_publica = $obtener_modo['api_key_public_test'];
                }
            }
            if ($obtener_api_key['idInstitucion']==13) {// LLAVES negocio CONACON
                $obtener_modo = $pagosM->obtener_modo_conacon()['data'];//obtener datos de la cuenta UDC
                if ($obtener_modo['modo']==1) {// modo producción|
                    $api_key_publica = $obtener_modo['api_key_public_prod'];
                }
                if ($obtener_modo['modo']==2) {
                    $api_key_publica = $obtener_modo['api_key_public_test'];
                }
            }

            echo json_encode(['api_key_publica'=>$api_key_publica]);
            break;
        case 'procesar_pago':
            $resp = [];
            $concepto = $p_pagosM->obtener_concepto_pago_id($_POST['id_concepto'])['data'];
            if($concepto && isset($_SESSION['alumno'])){
                $promos_aplicar = [];
                if(isset($_POST['tipo']) && $_POST['tipo'] == 'evento'){

                }else{
                    $carrera_info = $carreraM->consultarCarreraByID($_POST['inscrito_a']);
                    $generaciones_carrera = $generacionesM->obtenerListaGeneraciones_carrera($carrera_info['data']['idCarrera'])['data'];
                    foreach ($generaciones_carrera as $key => $value) {
                        // buscar las promociones que vienen con la generacion
                        $promos_aplicar = array_merge($promos_aplicar, $promoM->obtenerPromociones_generacion($value['idGeneracion'])['data']);
                    }
                }
                

                $promos_alumno = $promoM->obtenerPromociones_alumno($_SESSION['alumno']['id_prospecto'])['data'];
                foreach ($promos_alumno as $promo) {
                    $ix_promo = array_search($promo['id_concepto'], array_column($promos_aplicar, 'id_concepto'));
                    if($ix_promo !== false){
                        $promos_aplicar[$ix_promo] = $promo;
                    }else{
                        $promos_aplicar = array_merge($promos_aplicar, [$promo]);
                    }
                }

                foreach($promos_aplicar as $key => $value){
                    if($value['id_concepto'] != $_POST['id_concepto']){
                        unset($promos_aplicar[$key]);
                    }
                }
                $promos_aplicar = array_values($promos_aplicar);

                $concepto['aplicados'] = $p_pagosM->obtener_pagos_aplicados($concepto['id_concepto'],$_SESSION['alumno']['id_prospecto'])['data'];
                $concepto['promociones'] = $promos_aplicar;
                $resp = $concepto;
            }else{
                $resp = ['estatus'=>'error'];
                if(!$concepto){
                    $resp['info'] = 'No se encontró el concepto';
                }else{
                    $resp['info'] = 'Se ha interrumpido el proceso de pago. Verifique su sesión.';
                }
            }
            echo json_encode($resp);
            break;
        case 'realizar_pago_respaldo':
            require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");

            $valor=1;
            // $token= (isset($_POST['token'])? $_POST['token'] : null);
            $token = $_POST['token'];

            // INFORMACION DEL CLIENTE
                $nombre_cliente=$_POST['nombretarjeta'];
                $id_afiliado=$_SESSION['alumno']['id_afiliado'];
                $id_prospecto=$_SESSION['alumno']['id_prospecto'];
                $obteneralumno=$promoM->obtener_datos_alumno($id_afiliado);
                $email_cliente=$obteneralumno['data']['email'];
                $telefono_cliente='222 222 2222';
                $nombre_alumno=$obteneralumno['data']['nombre'];
                $apaterno=$obteneralumno['data']['apaterno'];
                $amaterno=$obteneralumno['data']['amaterno'];
                $domiciliar_tarjeta=$_POST['domiciliar_tarjeta'];
                $ord_id=null;
            // FIN INFO
            
            $id_concepto = $_POST['id_concepto'];

            $fechapago=date('Y-m-d H:i:s');

            // bandera parcialidades
            
            $tipo_parcial = false;
            $info_concepto = $pagosM->obtener_concepto($_POST['id_concepto'])['data'];
            
            if(!$info_concepto){
                echo("No se ha podido identificar el concepto de pago");
                die();
            }
            $ya_aplicados = sizeof($pagosM->listar_pagos_anteriores($_POST['id_concepto'], $id_prospecto)['data']);

            // setear nombre formal del concepto
            $nombre_concepto = $info_concepto['concepto'].' ['.$info_concepto['descripcion'];
            
            $tipo_parcial = (intval($info_concepto['parcialidades']) == 1) ? true : false;
            $monto_concepto = floatval($info_concepto['precio']); // el precio que se tiene en base de datos
            
            //BANDERA DE RETARDO
            $retardo = false;
            $monto_retardo = 0;

            if($info_concepto['categoria'] == 'Mensualidad'){
                $pagos_aplicados = sizeof($pagosM->listar_pagos_anteriores($_POST['id_concepto'], $id_prospecto)['data']);
                $generacion_info = [];
                if($info_concepto['id_generacion'] !== null){
                    $generacion_info = $generacionesM->buscarGeneracion($info_concepto['id_generacion'])['data'];
                }else{
                    // buscar el plan de pagos de al que pertenece
                    $plan_p = $p_pagosM->get_info_plan($info_concepto['idPlan_pago']);
                    if(!$plan_p){
                        // echo json_encode(['estatus'=>'error', 'info'=>'No se encontró el plan de pagos']);
                    }
                    $generaciones_d = $generacionesM->obtenerListaGeneraciones_carrera($plan_p['idGeneracion'])['data'];
                    if(sizeof($generaciones_d) == 0){
                        // echo json_encode(['estatus'=>'error', 'info'=>'No se encontró una generación']);
                    }
                    $generacion_info = $generaciones_d[0];
                }
                // obtener la fecha (dia) del plan de pago
                $fecha_lim = $info_concepto['fechalimitepago'];
                // componer la fecha de acuerdo al inicio de la generacion
                $fecha_lim = substr($generacion_info['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
                
                // si la fecha limite de pago es menor a la fecha de inicio de la generacion, se aumenta un mes
                if(strtotime($fecha_lim) < strtotime($generacion_info['fecha_inicio'])){
                    $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
                }
                $asign_gen = $generacionesM->buscarAsignacion($id_prospecto, $generacion_info['idGeneracion']);
                if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
                    if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
                        $fecha_lim = $asign_gen['data'][0]['fecha_primer_colegiatura'];
                    }
                }
                // var_dump($fecha_lim);
                // die();
                if($pagos_aplicados > 0){
                    $fecha_lim = date('Y-m-d', strtotime("+{$pagos_aplicados} month", strtotime($fecha_lim)));
                }
                $info_concepto['fechalimitepago'] = $fecha_lim;
            }

            // Lista de promociones
            $promos_list = [];
            $suma_promos = 0;

            //AJUSTAR EL MONTO DEL CONCEPTO SI NO ES UN CONCEPTO DE PAGO PARCIAL
            $promos_aplica = $_POST['promociones'];
            // verificar que si el pago acepta pagos parciales solo aplique promocion al primer pago
            if(!($tipo_parcial && $ya_aplicados > 0) || !$tipo_parcial){
                if(!$tipo_parcial){
                    $monto_pagado = $monto_concepto;
                }
				$precio_finpromo = $monto_concepto;
                if(intval($promos_aplica) > 0){
                    $promos_list = explode(',', $promos_aplica);

                    foreach ($promos_list as $key => $value) {
                        $promo_info = $promoM->obtenerPromocion(['idpromocion'=>$value])['data'];
                        $suma_promos += ($precio_finpromo * ($promo_info['porcentaje'] / 100));
                        $precio_finpromo = $precio_finpromo - ($precio_finpromo * ($promo_info['porcentaje'] / 100));    
                    }
                }
                if($precio_finpromo < $monto_concepto){
                    $nombre_concepto .= ' -PROMOCION';
                }
                if(!$tipo_parcial){
                    $monto_pagado = $precio_finpromo;
                    $monto_concepto = $precio_finpromo;
                }
            }

            // VERIFICAR SI EL PAGO SE ESTÁ APLICANDO CON RETARDO
            if($info_concepto['fechalimitepago'] !== null){
    		    if($info_concepto['fechalimitepago'] === null){
    		        $info_concepto['fechalimitepago'] = date('Y-m-d');
    		    }
    		    
    		    if((strtotime("+1 day" ,strtotime($info_concepto['fechalimitepago'])) <= strtotime($fechapago)) && $info_concepto['categoria'] != 'Inscripción'){
    		        // si se esta pagando post fecha de vencimiento el recargo se tomara del monto pagado
    		        $retardo = true;
    		        $monto_retardo = floatval($monto_concepto) * ($pagosM->porcentaje_recargo / 100);
    		        $nombre_concepto .= ' +RECARGO';
                    if(!$tipo_parcial){
                        $monto_pagado+=$monto_retardo;
                    }
    		    }
    		}
            
            
            if($tipo_parcial){
                $monto_pagado = floatval($_POST['totalapagar']);
                // var_dump($_POST['totalapagar']);
                // die();
                if($retardo){
                    if($monto_pagado < ($monto_retardo + $monto_concepto)){
                        echo ("El monto recibido es menor al monto a pagar.");
                        die();
                    }
                }else{
                    /*$promos_aplica = $_POST['promociones'];
                    // verificar que si el pago acepta pagos parciales solo aplique promocion al primer pago
                    if(!(intval($info_concepto['parcialidades']) == 1 && $ya_aplicados > 0)){
                        if(intval($promos_aplica) > 0){
                            $promos_list = explode(',', $promos_aplica);
                            $precio_finpromo = $monto_concepto;

                            foreach ($promos_list as $key => $value) {
                                $promo_info = $promoM->obtenerPromocion(['idpromocion'=>$value])['data'];
                                $suma_promos += ($precio_finpromo * ($promo_info['porcentaje'] / 100));
                                $precio_finpromo = $precio_finpromo - ($precio_finpromo * ($promo_info['porcentaje'] / 100));    
                            }

                            if($precio_finpromo < $monto_concepto){
                                $nombre_concepto .= ' -PROMOCION';
                            }
                        }
                    }*/
                }
            }else{
                // $promos_aplica = $_POST['promociones'];
                // $monto_pagado = $monto_concepto;
                if(!$retardo){
                    /*if(intval($promos_aplica) > 0){
                        $promos_list = explode(',', $promos_aplica);
                        $precio_finpromo = $monto_concepto;

                        foreach ($promos_list as $key => $value) {
                            $promo_info = $promoM->obtenerPromocion(['idpromocion'=>$value])['data'];
                            $suma_promos += ($precio_finpromo * ($promo_info['porcentaje'] / 100));
                            $precio_finpromo = $precio_finpromo - ($precio_finpromo * ($promo_info['porcentaje'] / 100));
                        }
                        
                        if($precio_finpromo < $monto_concepto){
                            $nombre_concepto .= ' -PROMOCION';
                        }
                        
                        $monto_pagado = $precio_finpromo;
                    }*/
                }else{
                    // $monto_pagado = $monto_concepto + $monto_retardo;
                }
            }
            
            $nombre_concepto .= ' ]';
            
            // print_r(
            //     [
            //         'concepto'=>$nombre_concepto,
            //         'precio concepto'=>$monto_concepto,
            //         'monto a pagar'=>$monto_pagado, 
            //         'fecha limite de pago'=>$info_concepto['fechalimitepago'], 
            //         'recargos'=>$monto_retardo,
            //         'promociones'=>$suma_promos,
            //     ]);
            
            $descripcion_concepto=$_POST['descripcionpago'];
            
            $orden_pago_exitoso=false;
            $plan_pago_exitoso=false;
            //se crea el cliente 
            if ($info_concepto['categoria'] == 'Mensualidad' && $domiciliar_tarjeta==1) { //si se esta pagando por concepto de mensualidad y se selecciono opcion de domiciliar tarjeta suscribimos al cliente al plan de pagos conekta con el id que tiene el concepto al crear el plan de pagos
                
                /* crear plan de pago en conekta para concepto de mensualidad */
                $plan = \Conekta\Plan::create(array(
                    'name' => $info_concepto['concepto'],
                    'amount' => $monto_pagado*100,
                    'currency' => "MXN",
                    'interval' => 'month',
                    'frequency' => 1,
                    'expiry_count' => $info_concepto['numero_pagos']
                ));

                $id_plan_conekta=$plan->id;


                try{
                    $monto = \Conekta\Plan::find($id_plan_conekta);
                }catch (\Conekta\ProccessingError $error){
                    return $error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                    return $error->getMessage();
                } catch (\Conekta\Handler $error){
                    return $error->getMessage();
                }
                try{
                    $cliente = \Conekta\Customer::create(
                        array(
                            'name' => $nombre_cliente,
                            'email' => $email_cliente,
                            'phone' => $telefono_cliente,
                            'plan_id' => $monto->id,
                            'payment_sources' => array(array(
                                'token_id' => $token,
                                'type' => "card"
                            ))
                        )
                    );
                }catch (\Conekta\ProccessingError $error){
                    return $error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                    return $error->getMessage();
                } catch (\Conekta\Handler $error){
                    return $error->getMessage();
                }

                if ($cliente->subscription->status=="active") {
                    $plan_pago_exitoso=true;
                    $ord_id = $cliente->subscription->last_billing_cycle_order_id;
                }

            }else {//si es pago en una sola exhibición se crea la orden 
                try {
                    $customer = \Conekta\Customer::create(
                        array(
                        "name" => $nombre_cliente,
                        "email" => $email_cliente,
                        "phone" => $telefono_cliente,
                        "payment_sources" => array(
                          array(
                              "type" => "card",
                              "token_id" => $token
                          )
                        )//payment_sources
                      )//customer
                    );
                } catch (\Conekta\ProccessingError $error){
                echo $error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                echo $error->getMessage();
                } catch (\Conekta\Handler $error){
                echo $error->getMessage();
                }
    
                  //se crea la orden y se relaciona con el cliente
                try{
                $final_mont = intval($monto_pagado*100);
                $order = \Conekta\Order::create(
                    array(
                    "line_items" => array(
                        array(
                        "name" => $nombre_concepto,
                        "unit_price" => $final_mont,
                        "quantity" => 1,
                        "metadata" => array(
                            "id_prospecto" => $id_prospecto
                            )
                        )//first line_item
                    ), //line_items
                    "currency" => 'mxn',
                    "customer_info" => array(
                        "customer_id" => $customer->id
                    ), //customer_info
                    "charges" => array(
                        array(
                            "payment_method" => array(
                                    "type" => "default"
                            ) //payment_method - use customer's default - a card
                                //to charge a card, different from the default,
                                //you can indicate the card's source_id as shown in the Retry Card Section
                        ) //first charge
                    ) //charges
                    )//order
                );
                } catch (\Conekta\ProcessingError $error){
                echo $error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                echo $error->getMessage();
                } catch (\Conekta\Handler $error){
                echo $error->getMessage();
                }

                if($order->payment_status=="paid") {
                    $orden_pago_exitoso=true;
                    $ord_id=$order->id;
                }
            }

            
            
              if ($orden_pago_exitoso || $plan_pago_exitoso) {
                  if ($order->charges[0]->payment_method->type=='credit') {
                      $metodo_de_pago = 'Tarjeta de crédito';
                  }
                  if ($order->charges[0]->payment_method->type=='debit') {
                      $metodo_de_pago = 'Tarjeta de débito';
                  }
                    echo $valor;
                    $detalle = $pagosM->formato_pago('conekta', $monto_pagado, $fechapago, $nombre_alumno, $apaterno, $amaterno, $email_cliente, $descripcion_concepto);
                    $validarpagos=$pagosM->leer_pagos_anteriores($id_concepto,$id_prospecto);
                    $obtener_concepto=$pagosM->obtener_concepto($id_concepto);
                    
                    
                    if($validarpagos['data']['fechaultimopago'] != null){
                        $nuevorestante=$validarpagos['data']['restante']-($monto_pagado - $monto_retardo);
                        $nuevorestante = $nuevorestante - $suma_promos;

                        $preciototalconcepto=$obtener_concepto['data']['precio'];

                        $pagos_aplicados = $pagosM->listar_pagos_anteriores($_POST['id_concepto'], $id_prospecto)['data'];
                        $preciototalconcepto = floatval($pagos_aplicados[0]['costototal']);
                        $registrar_pago=$pagosM->registrar_pago_card($id_prospecto,$id_concepto,json_encode($detalle),$fechapago,$nuevorestante,($monto_pagado-$monto_retardo),$preciototalconcepto, '', 'verificado',(sizeof($promos_list)>0?$promos_list[sizeof($promos_list)-1]:null), $monto_retardo,$order->charges[0]->payment_method->auth_code, $ord_id, $metodo_de_pago);
                    }else{
                        $restante = 0;
                        if(intval($obtener_concepto['data']['numero_pagos']) > 1){
                            if(sizeof($promos_list) > 0){
                                // for ($i=0; $i < intval($obtener_concepto['data']['numero_pagos']); $i++) { 
                                //     $restante += floatval($obtener_concepto['data']['precio']) - $suma_promos;
                                // }
                                $restante = floatval($obtener_concepto['data']['precio']) * intval($obtener_concepto['data']['numero_pagos']);
                            }else{
                                $restante = floatval($obtener_concepto['data']['precio']) * intval($obtener_concepto['data']['numero_pagos']);
                            }
                        }else{
                            $restante = floatval($obtener_concepto['data']['precio']);
                        }
                        $nuevorestante = round($restante - (($monto_pagado + $suma_promos) - $monto_retardo), 2);
                        // $nuevorestante = $nuevorestante - $suma_promos;
                        if(intval($obtener_concepto['data']['numero_pagos']) > 1){
                            $sumatotal_concepto = $restante;    
                        }else{
                            $sumatotal_concepto = round($nuevorestante + (($monto_pagado) - $monto_retardo), 2);
                        }

                        $registrar_pago=$pagosM->registrar_pago_card($id_prospecto,$id_concepto,json_encode($detalle),$fechapago,$nuevorestante,($monto_pagado-$monto_retardo),$sumatotal_concepto, '', 'verificado',(sizeof($promos_list)>0?$promos_list[sizeof($promos_list)-1]:null), $monto_retardo,$order->charges[0]->payment_method->auth_code, $ord_id, $metodo_de_pago);
                    }

                    // buscar vistas relacionadas al pago de este concepto
                    $vistas_relacionadas = $vistasM->vistas_conceptos('categoria', $obtener_concepto['data']['categoria'])['data'];
                    $vistas_relacionadas = array_merge($vistas_relacionadas, $vistasM->vistas_conceptos('concepto', $id_concepto)['data']);
                    $vistasM->habilitar_vistas_afiliados($id_prospecto, array_reduce($vistas_relacionadas, function($acc, $item){
                        $acc[] = $item['idVista'];
                        return $acc;
                    }, []));
                    
                }   else {
                    echo "No se pudo realizar el pago";
                } 
            break;
        case 'generar_ficha_spei_link':
            require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");
    
            $nombre_concepto = @$_POST['nombre_concepto'];
            $monto_pago = @$_POST['monto_pago'];
            $nombreclientespei = @$_POST['nombreclientespei'];
            $emailclientespei = @$_POST['emailclientespei'];
            $currency=(isset($_POST['tipo_moneda']) && strtoupper($_POST['tipo_moneda']) == 'MXN') ? 'MXN' : 'USD';
            $telefono_prospecto='222 222 2222';
            try{
                $thirty_days_from_now = (new DateTime())->add(new DateInterval('P30D'))->getTimestamp(); 
                
                $order = \Conekta\Order::create(
                    [
                    "line_items" => [
                        [
                        "name" => $nombre_concepto,
                        "unit_price" => $monto_pago*100,
                        "quantity" => 1,
                        "metadata" => array(
                            "id_prospecto" => 'spei_link_cobranza',
                            "ref_instituto" => @$_POST['id_tipo_pago_concepto']
                            )
                        ]
                    ],
                    "currency" => $currency,
                    "customer_info" => [
                        "name" => $nombreclientespei,
                        "email" => $emailclientespei,
                        "phone" => $telefono_prospecto
                    ],
                    "charges" => [
                        [
                        "payment_method" => [
                            "type" => "spei",
                            "expires_at" => $thirty_days_from_now
                        ]
                        ]
                    ]
                    ]
                );
                } catch (\Conekta\ParameterValidationError $error){
                echo $error->getMessage();
                } catch (\Conekta\Handler $error){
                echo $error->getMessage();
                }

                $CLABE=$order->charges[0]->payment_method->receiving_account_number;
                // llenar array para imprimirlo en json
                $data = [
                    'order_id' => $order->id,
                    'CLABE' => $CLABE,
                    'monto' => '$'.$order->amount/100,
                    'tipo_moneda' => $order->currency,
                    'nombre_producto' => $order->line_items[0]->name,
                    'bank' => $order->charges[0]->payment_method->receiving_account_bank

                ];
                echo json_encode($data);
            break;

        case 'realizar_pago_link':
            require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");
            require_once '../../functions/correos_prospectos.php';
            $token=$_POST['token'];
            $nombre_cliente = $_POST['nombretarjeta'];
            $email = $_POST['email'];
            $telefono = $_POST['telefonofactura'];
            $montopagado = $_POST['totalapagar'];
            $nombre_concepto = $_POST['nombre_concepto'];
            $udc_o_conacon = $_POST['id_concepto'];
            $errors = '';

             try {
                $customer = \Conekta\Customer::create(
                    array(
                    "name" => $nombre_cliente,
                    "email" => $email,
                    "phone" => $telefono,
                    "payment_sources" => array(
                      array(
                          "type" => "card",
                          "token_id" => $token
                      )
                    )//payment_sources
                  )//customer
                );
            } catch (\Conekta\ProccessingError $error){
            $errors.= $error->getMessage();
            } catch (\Conekta\ParameterValidationError $error){
            $errors.= $error->getMessage();
            } catch (\Conekta\Handler $error){
            $errors.= $error->getMessage();
            }


              //se crea la orden y se relaciona con el cliente
              try{
                $montopagado = strval($montopagado*100);
                $order = \Conekta\Order::create(
                    array(
                    "line_items" => array(
                        array(
                        "name" => $nombre_concepto,
                        "unit_price" => $montopagado,
                        "quantity" => 1,
                        "metadata" => array(
                            "id_prospecto" => 'pago mediante link'
                            )
                        )//first line_item
                    ), //line_items
                    "currency" => 'USD',
                    "customer_info" => array(
                        "customer_id" => $customer->id
                    ), //customer_info
                    "charges" => array(
                        array(
                            "payment_method" => array(
                                    "type" => "default"
                            ) //payment_method - use customer's default - a card
                                //to charge a card, different from the default,
                                //you can indicate the card's source_id as shown in the Retry Card Section
                        ) //first charge
                    ) //charges
                    )//order
                );
                } catch (\Conekta\ProcessingError $error){
                $errors.= $error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                $errors.= $error->getMessage();
                } catch (\Conekta\Handler $error){
                $errors.= $error->getMessage();
                }

                $respuesta= ['estatus' => 2,
                        'codigo_autorizacion' => '',
                        'referencia_pago' => '',
                        'nombre_cliente' => '',
                        'email_cliente' => '',
                        'telefono_cliente' => '',
                        'nombre_concepto' => '',
                        'order_id' => '',
                        'monto_pagado' => '',
                        'moneda' => '',
                        'fecha_pago' => '',
                        'nombre_negocio' => '',
                        'error' => $errors
                        ];
                        
                
                
                if($order->payment_status=="paid") {
                    $nombre_negocio = '';
                    ($udc_o_conacon==183 || $udc_o_conacon==24)? $nombre_negocio = 'COLEGIO NACIONAL DE CONSEJEROS' : $nombre_negocio = 'UNIVERSIDAD DEL CONDE';
                    $respuesta=['estatus' => 1,
                        'codigo_autorizacion' => $order->charges[0]->payment_method->auth_code,
                        'referencia_pago' => $order->charges[0]->id,
                        'nombre_cliente' => $nombre_cliente,
                        'email_cliente' => $email,
                        'telefono_cliente' => $telefono,
                        'nombre_concepto' => $nombre_concepto,
                        'order_id' => $order->id,
                        'monto_pagado' => number_format($order->amount/100),
                        'moneda' => $order->currency,
                        'fecha_pago' => date('d-m-Y H:i:s'),
                        'nombre_negocio' => $nombre_negocio,
                        'error' => ''
                    ];
                    ($udc_o_conacon==183 || $udc_o_conacon==24)? $udc_o_conacon='footer_confirmacion_de_pago_red.jpg':$udc_o_conacon='logoT.png';
                    $asunto = "NOTIFICACIÓN DE PAGO UNIVERSIDAD DEL CONDE";
                    $destinatarios = [[$email, $nombre_cliente]];
                    // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
                    $plantilla_c = 'plantilla_confirmacion_pago_conekta_card_link.html';
                    $claves = ['%%prospecto','%%monto_pagado','%%tipo_moneda','%%nombre_concepto','%%email','%%telefono','%%referencia','%%autorizacion','%%orden','%%udc_o_conacon'];
                    $valores = [$nombre_cliente, number_format($order->amount/100),$order->currency,$nombre_concepto,$email,$telefono,$order->charges[0]->id,$order->charges[0]->payment_method->auth_code,$order->id,$udc_o_conacon];
                    $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                }
                echo json_encode($respuesta);
            break;
        case 'realizar_pago':
            require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");
	    require_once '../../functions/correos_prospectos.php';

            $currency='mxn';
            $orden_pago_exitoso=false;
            $plan_pago_exitoso=false;
            
            if(!isset($_POST['token'])){
                echo "No se pudo realizar el pago por token conekta";
                die();
            }
            $token = $_POST['token'];
            $fecha_pago = date("Y-m-d H:i:s");
            if(!isset($_SESSION['alumno'])){
                echo ('No ha iniciado sesión');
                die();
            }
            $id_prospecto = $_SESSION['alumno']['id_prospecto'];
            $info_alumn = $prospectoM->consultar_info_prospecto_a($id_prospecto)['data'];
            $info_alumn['telefono']='222 222 2222';
            if ($info_alumn['tipoPago']==2) {
                $currency='usd';
              }

            $info_pago = $pagosM->obtener_pago_aplicar($id_prospecto, $_POST['id_concepto'], $fecha_pago);
            $info_concepto = $pagosM->obtener_concepto($_POST['id_concepto'])['data'];
            if ($info_alumn['tipoPago']==2) {//si el alumno tienepago en dolares se reeemplaza el precio por el monto en dolares
                $info_concepto['precio'] = $info_concepto['precio_usd'];
              }
            
            $monto_pagado = 0;
            if($info_concepto['parcialidades'] == '1'){
                if(isset($_POST['totalapagar'])){
                    $monto_pagado = floatval(str_replace([',','$',' '], '', $_POST['totalapagar']));
                    if($monto_pagado == 0){
                        echo "El monto pagado no puede ser 0";
                        die();
                    }
                }else{
                    echo "No se ha recibido el monto a pagar";
                    die();
                }
            }else{
                $monto_pagado = $info_pago['monto_por_pagar'] + $info_pago['monto_retardo'];
            }

            $pos = strpos($monto_pagado, '.');
                if($pos){
                    $porciones = explode(".", $monto_pagado);
                    $rest = substr($porciones[1], 0); 
                    if(strlen($rest>2)){
                        if(substr($porciones[1], 0,1)==9){
                        $monto_pagado=round($monto_pagado);
                        }
                        $monto_pagado=number_format($monto_pagado, 2, '.', '');  
                    }
                }


            $orig = 'Conekta';
            $order = [];
            # APLICAR PAGO CONEKTA
            if ($info_concepto['categoria'] == 'Mensualidad' && $_POST['domiciliar_tarjeta']==1 && $info_pago['monto_por_pagar'] == $info_pago['monto_total_concepto']) { //si se esta pagando por concepto de mensualidad y se selecciono opcion de domiciliar tarjeta suscribimos al cliente al plan de pagos conekta con el id que tiene el concepto al crear el plan de pagos
                /* crear plan de pago en conekta para concepto de mensualidad */
                $plan = \Conekta\Plan::create(array(
                    'name' => $info_concepto['concepto'],
                    'amount' => $monto_pagado*100,
                    'currency' => $currency,
                    'interval' => 'month',
                    'frequency' => 1,
                    'expiry_count' => $info_concepto['numero_pagos']
                ));

                $id_plan_conekta=$plan->id;


                try{
                    $monto = \Conekta\Plan::find($id_plan_conekta);
                }catch (\Conekta\ProccessingError $error){
                    return $error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                    return $error->getMessage();
                } catch (\Conekta\Handler $error){
                    return $error->getMessage();
                }
                try{
                    $cliente = \Conekta\Customer::create(
                        array(
                            'name' => $info_alumn['nombre'],
                            'email' => $info_alumn['email'],
                            'phone' => $info_alumn['telefono'],
                            'plan_id' => $monto->id,
                            'payment_sources' => array(array(
                                'token_id' => $token,
                                'type' => "card"
                            ))
                        )
                    );
                }catch (\Conekta\ProccessingError $error){
                    return $error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                    return $error->getMessage();
                } catch (\Conekta\Handler $error){
                    return $error->getMessage();
                }

                if ($cliente->subscription->status=="active") {
                    $plan_pago_exitoso=true;
                    $ord_id = $cliente->subscription->last_billing_cycle_order_id;
                }

            }else {//si es pago en una sola exhibición se crea la orden 
                try {
                    $customer = \Conekta\Customer::create(
                        array(
                        "name" => preg_replace("/[^a-zA-Z]+/", "", $info_alumn['nombre']),
                        "email" => $info_alumn['email'],
                        "phone" => $info_alumn['telefono'],
                        "payment_sources" => array(
                          array(
                              "type" => "card",
                              "token_id" => $token
                          )
                        )//payment_sources
                      )//customer
                    );
                } catch (\Conekta\ProccessingError $error){
                echo 'Customer: '.$error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                echo 'Customer: '.$error->getMessage();
                } catch (\Conekta\Handler $error){
                echo 'Customer: '.$error->getMessage();
                }
    
                  //se crea la orden y se relaciona con el cliente
                try{
                $final_mont = strval($monto_pagado*100);



                $order = \Conekta\Order::create(
                    array(
                    "line_items" => array(
                        array(
                        "name" => $info_concepto['concepto'],
                        "unit_price" => $final_mont,
                        "quantity" => 1,
                        "metadata" => array(
                            "id_prospecto" => $id_prospecto
                            )
                        )//first line_item
                    ), //line_items
                    "currency" => $currency,
                    "customer_info" => array(
                        "customer_id" => $customer->id
                    ), //customer_info
                    "charges" => array(
                        array(
                            "payment_method" => array(
                                    "type" => "default"
                            ) //payment_method - use customer's default - a card
                                //to charge a card, different from the default,
                                //you can indicate the card's source_id as shown in the Retry Card Section
                        ) //first charge
                    ) //charges
                    )//order
                );
                } catch (\Conekta\ProcessingError $error){
                echo "Orden: ".$error->getMessage();
                } catch (\Conekta\ParameterValidationError $error){
                echo "Orden: ".$error->getMessage();
                } catch (\Conekta\Handler $error){
                echo "Orden: ".$error->getMessage();
                }

                if($order->payment_status=="paid") {
                    $orden_pago_exitoso=true;
                    $ord_id=$order->id;
                }
            }
            if ($orden_pago_exitoso || $plan_pago_exitoso) {
                echo "1";
                $detalle = json_encode($pagosM->formato_pago($orig, $monto_pagado, $fecha_pago, $info_alumn['nombre'], $info_alumn['apaterno'], $info_alumn['amaterno'], $info_alumn['email'], $info_concepto['descripcion']));
                $insertar = [
                    'quien_registro'=> $_SESSION['alumno']['id_prospecto'],
                    'id_prospecto' => $id_prospecto,
                    'id_concepto'   => $_POST['id_concepto'],
                    'detalle'       => $detalle,
                    'montopagado'   => '0',
                    'cargo_retardo' => '0',
                    'restante'      => '0',
                    'saldo'         => '0',
                    'costototal'    => ($info_concepto['precio'] - $info_pago['monto_promocion']),
                    'numero_de_pago'=> $info_pago['numero_de_pago'],
                    'fecha_limite_pago'=> '',
                    'idPromocion' => (isset($info_pago['id_promocion'])?$info_pago['id_promocion']:null),
                    'fechapago'     => $fecha_pago,
                    'comprobante'   => '',
                    'metodo_de_pago'=> $order->charges[0]->payment_method->type,
                    'banco_de_deposito'=> isset($_POST['crearbancodedeposito']) ? $_POST['crearbancodedeposito'] : null,
                    'estatus'       => 'verificado',
                    'codigo_de_autorizacion'=>$order->charges[0]->payment_method->auth_code,
                    'moneda'=> $currency
                ];

                if(isset($ord_id)){
                    $insertar['order_id'] = $ord_id;
                }

                if($monto_pagado >= ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo'])){
                    // se está cubriendo el monto del pago requerido
                    $insertar['montopagado'] = $monto_pagado - $info_pago['monto_retardo'];
                    $insertar['cargo_retardo'] = $info_pago['monto_retardo'];
                    $insertar['restante'] = ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo']) - $monto_pagado;
                    if($info_concepto['numero_pagos'] > 1){
                        $dia_regular = $pagosM->consultar_fecha_corte_mensualidad($id_prospecto, $_POST['id_concepto']);
                        if($dia_regular){
                            $info_pago['fecha_limite_pago'] = substr($info_pago['fecha_limite_pago'], 0, 8).$dia_regular;
                        }
                        $insertar['fecha_limite_pago'] = date("Y-m-d", strtotime('+1 month', strtotime($info_pago['fecha_limite_pago'])));
                    }else{
                        $insertar['fecha_limite_pago'] = $info_pago['fecha_limite_pago'];
                    }
                }else{
                    // se está cubriendo menos del monto requerido
                    $sobrante = 0;
                    if($monto_pagado >= $info_pago['monto_por_pagar']){
                        $insertar['montopagado'] = $info_pago['monto_por_pagar'];
                        $sobrante = $monto_pagado - $info_pago['monto_por_pagar'];
                        $insertar['saldo'] = $info_pago['monto_retardo'] - $sobrante;
                        $insertar['cargo_retardo'] = $sobrante;
                        $insertar['restante'] = 0;
                    }else{
                        $insertar['montopagado'] = $monto_pagado;
                        $insertar['restante'] = $info_pago['monto_por_pagar'] - $monto_pagado;
                        $insertar['saldo'] = $info_pago['monto_retardo'];
                    }
                    /* $insertar['montopagado'] = $monto_pagado;
                    $insertar['restante'] = $info_pago['monto_por_pagar'] - $monto_pagado;
                    if($insertar['restante'] < 0){
                        $insertar['saldo'] = $info_pago['monto_retardo'] - abs($insertar['restante']);
                        $insertar['restante'] = 0;
                    }else{
                        $insertar['saldo'] = $info_pago['monto_retardo'];
                    } */
                    $insertar['fecha_limite_pago'] = $info_pago['fecha_limite_pago'];
                }
                if ($info_concepto['categoria']=='Reinscripción') {
                    $obtenerfechalimitedepago = $pagosM->obtenerfechalimitedepago($info_concepto['id_generacion'], $info_pago['numero_de_pago']+2);
                    if ($obtenerfechalimitedepago['data']) {
                        $insertar['fecha_limite_pago'] = $obtenerfechalimitedepago['data']['fecha_inicio'];
                    }else{
                        $insertar['fecha_limite_pago'] = NULL;
                    }
                }

                /*validar si es el primer pago verificado de la generacion para asignarlo en automático*/
                $tiene_pagos_verificados = $pagosM->tiene_pagos_verificados($id_prospecto, $info_concepto['id_generacion'])['data'];
                if (!$tiene_pagos_verificados) {
                    
                    // verificar si ya existe la vista de cursos si no la tiene se asigna
                    $validarsitienevistacursos= $alumnM->validarsitienevistacursos($id_prospecto);
                    if(!$validarsitienevistacursos['data']){
                        $insertarvistacursos= $alumnM->insertarvistacursos($id_prospecto);
                    }
                    // verificar si ya existe la vista de cursos si no la tiene se asigna

                    // verificar si ya existe la generacion asignada si no la tiene se asigna
                    $validar_si_generacion_ya_esta_asignada= $pagosM->validar_si_generacion_ya_esta_asignada($id_prospecto,$info_concepto['id_generacion'])['data'];
                    $asignar_generacion = false;
                    if(!$validar_si_generacion_ya_esta_asignada){
                        $asignar_generacion= $pagosM->asignar_generacion_alumno($id_prospecto,$info_concepto['id_generacion'])['data'];
                        require_once 'sendPost.php';
                        sendPost($id_prospecto, $generacionesM->buscarCarrerasG($info_concepto['id_generacion'])['data'][0]['idCarrera']);
                    }
                    // verificar si ya existe la generacion asig si no la tiene se asigna
                    if ($asignar_generacion) {
                        $validar_carrera= $pagosM->validar_carrera($info_concepto['id_generacion'])['data'];
                        /*si la generacion asignada pertenece a la carrera tsu consejería enviar conrreo de bienvenida con formatos de inscripcion*/
                        if ($validar_carrera['idCarrera']==13) {//si es de carrera tsu enviar correo de bienvenida, si se necesita enviar correo de bienvenida de mas carreras comparar con su correspondiente id
                        $asunto = "El Departamento de Control Escolar le da la bienvenida";
                        $destinatarios = [[$info_alumn['email'], $info_alumn['nombre'].' '.$info_alumn['aPaterno'].' '.$info_alumn['aMaterno']]];
                        $plantilla_c = 'plantilla_tsu_control_escolar.html';
                        $claves = ['%%prospecto','%%secuencia_generacion'];
                        $valores = [$info_alumn['nombre'].' '.$info_alumn['aPaterno'].' '.$info_alumn['aMaterno'], $validar_carrera['secuencia_generacion']];
                        $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                        }
                        /*si la generacion asignada pertenece a la carrera tsu consejería enviar conrreo de bienvenida con formatos de inscripcion*/
                    }
                    
                }

                /*validar si es el primer pago verificado de la generacion para asignarlo en automático*/

                $insert = $pagosM->registrar_pago_mult($insertar);
                if($insert > 0){
                    $resp['estatus'] = 'ok';
                    $resp['info'] = $insert;
                }else{
                    $resp['estatus'] = 'error';
                    $resp['info'] = 'No se pudo registrar el pago';
                }
            }else{
                echo "No se ha podido procesar el pago";
            }
            break;
        case 'obtener_tipo_carrera':
            
            $id_carrera=$_POST['id_carrera'];
            $id_concepto=$_POST['id_concepto'];
            $id_prospecto=$_SESSION['alumno']['id_prospecto'];
            $obtener_tipo_carrera=$pagosM->obtener_tipo_carrera($id_carrera);
            if(isset($_POST['tipo']) && $_POST['tipo'] == 'evento'){
                $obtener_tipo_carrera['data']['tipo'] = '1';
            }
            $obtenerpagos=$pagosM->obtener_pagos_anteriores($id_concepto,$id_prospecto);
            array_push($obtener_tipo_carrera['data'],$obtenerpagos['data']['restante']);
            
            echo json_encode($obtener_tipo_carrera['data']);
            break;
        case 'no_session':
            echo 'no_session';
            break;
        
        case 'obtener_plan_pago_callcenter':
            unset($_POST['action']);
            $alumno_id = null;
            $institucion = $_POST['instit'];
            if(isset($_SESSION['alumno']['id_prospecto']) && !isset($_POST['proced'])){
                $alumno_id = $_SESSION['alumno']['id_prospecto'];
            }elseif(isset($_POST['prospecto'])){
                $alumno_id = $_POST['prospecto'];
            }
            if(!isset($_POST['inscrito_a'])){
                if(isset($_POST['buscar_generacion'])){
                    $_POST['inscrito_a'] = $generacionesM->buscarCarrerasG($_POST['buscar_generacion'])['data'][0]['idCarrera'];
                    unset($_POST['buscar_generacion']);
                }else{
                    echo json_encode(['estatus'=>'error', 'info'=>'No se ha podido obtener la información']);
                    die();
                }
            }            
            $resp = [];
            
            $plan_pago = null;

            $carrera_info = $carreraM->consultarCarreraByID($_POST['inscrito_a']);
            if($carrera_info['data']){
                $carrera_info['data']['generaciones'] = $generacionesM->obtenerListaGeneraciones_carrera($carrera_info['data']['idCarrera'])['data'];
                
                #buscar plan de pago de la carrera
                
                $plan_pago = $p_pagosM->obtener_plan_pago_carrera($_POST['inscrito_a']);
                $plan_pago['orig'] = 'Carrera';
                
                if(!$plan_pago['data']){
                    $resp = ['estatus'=>'error', 'info'=>'No se encontró el plan de pago.'];
                }else {
                    if($alumno_id === null){
                        echo json_encode(['estatus'=>'error', 'info'=>'No se identificó al alumno.']);
                        die();
                    }
                    $generacion_asign = null;
                    
                    $conceptos_p = []; // todos los conceptos a pagar
                    $promos_aplicar = []; // promociones a aplicar
                    
                    /** 1)
                     * buscar los conceptos que pertenecen al plan de pago
                     * , solo se agregan si el concepto no esta asignado a una generacion
                     *  */ 
                    $conceptos_plan = $p_pagosM->obtener_conceptos_plan($plan_pago['data']['idPlanPago'])['data'];
                    // $conceptos_plan = [];
                    // buscar generaciones de la carrera
                    $plan_pago['data']['generaciones'] = $carrera_info['data']['generaciones'];
                    if(sizeof($plan_pago['data']['generaciones']) > 0){
                        foreach ($plan_pago['data']['generaciones'] as $key => $value) {
                            // buscar conceptos de pago de la generacion
                            $conceptos_gen = $pagosM->obtener_conceptos_generacion($value['idGeneracion'], $alumno_id)['data']; // El valor alumno_id no se usa
                            /**Si el plan de pago tiene algun concepto que no este incluido en los pagos de generacion, se agrega */
                            foreach($conceptos_plan as $key_plan => $concepto_plan){
                                /**Si la generación no tiene ningun concepto de pago ó no el que se este refiriendo en el foreach */
                                if(sizeof($conceptos_gen) == 0 || (!in_array($concepto_plan['categoria'], array_column($conceptos_gen, 'categoria')))){
                                    /** Si el concepto es una mensualidad se modificara la fecha limite de pago, con respecto a la fecha que inicie la generacion */
                                    if($concepto_plan['categoria'] == 'Mensualidad'){
                                        $pagos_aplicados = sizeof($pagosM->listar_pagos_anteriores($concepto_plan['id_concepto'], $alumno_id)['data']);
                                        // obtener la fecha (dia) del plan de pago
                                        $fecha_lim = $concepto_plan['fechalimitepago'];
                                        // componer la fecha de acuerdo al inicio de la generacion
                                        $fecha_lim = substr($value['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
                                        // si la fecha limite de pago es menor a la fecha de inicio de la generacion, se aumenta un mes
                                        if(strtotime($fecha_lim) < strtotime($value['fecha_inicio'])){
                                            $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
                                        }
                                        if($pagos_aplicados > 0){
                                            $fecha_lim = date('Y-m-d', strtotime("+{$pagos_aplicados} month", strtotime($fecha_lim)));
                                        }
                                        // $concepto_plan['fechalimitepago'] = $fecha_lim;
                                        // $conceptos_gen[] = $concepto_plan;
                                    }else{
                                        // $concepto_plan['concepto'] = $concepto_plan['concepto'].' ['.$value['nombre'].']';
                                        // $conceptos_gen[] = $concepto_plan;
                                    }
                                    $ix_inscr = array_search('Inscripción', array_column($conceptos_gen, 'categoria'));
                                    if($ix_inscr !== false){
                                        $conceptos_gen[$ix_inscr]['info_gen'] = $value;
                                    }
                                }else{
                                    /** Si la generacion ya tiene ese concepto asignado
                                     * Validar si hay una mensualidad, modificar la fecha limite de pago de acuerdo a la generacion y pagos aplicados
                                     */
                                    $ix_mens = array_search('Mensualidad', array_column($conceptos_gen, 'categoria'));
                                    if($ix_mens !== false){
                                        $tmp_apli = $pagosM->listar_pagos_anteriores($conceptos_gen[$ix_mens]['id_concepto'], $alumno_id)['data'];
                                        // $pagos_aplicados = sizeof($pagosM->listar_pagos_anteriores()['data']);
                                        $conceptos_gen[$ix_mens]['primer_mensualidad'] = '';
                                        $num_p = 0;
                                        $tmp_fecha = '';
                                        $time = 0;
                                        foreach($tmp_apli as $pag){
                                            if($pag['numero_de_pago'] >= $num_p && strtotime($pag['fecha_limite_pago']) > $time){
                                                $num_p = $pag['numero_de_pago'];
                                                $tmp_fecha = $pag['fecha_limite_pago'];
                                                $time = strtotime($pag['fecha_limite_pago']);
                                            }
                                        }
                                        $pagos_aplicados = $num_p;
                                        
                                        // obtener la fecha (dia) del plan de pago
                                        $fecha_lim = $conceptos_gen[$ix_mens]['fechalimitepago'];
                                        // componer la fecha de acuerdo al inicio de la generacion
                                        $fecha_lim = substr($value['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
                                        // si la fecha limite de pago es menor a la fecha de inicio de la generacion, se aumenta un mes
                                        if(strtotime($fecha_lim) < strtotime($value['fecha_inicio'])){
                                            $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
                                        }
                                        // verificar si el alumno tiene una fecha especial para sus mensualidades
                                        $asign_gen = $generacionesM->buscarAsignacion($alumno_id, $value['idGeneracion']);
                                        if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
                                            if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
                                                $fecha_lim = $asign_gen['data'][0]['fecha_primer_colegiatura'];
                                            }
                                            foreach($conceptos_gen as $gen_v){
                                                $prom_concep = $promoM->obtenerPromocion_concepto_alumno($alumno_id, $gen_v['id_concepto'])['data'];
                                                if(!empty($prom_concep)){
                                                    $promos_aplicar = array_merge($promos_aplicar, $prom_concep);
                                                }
                                            }
                                        }
                                        $conceptos_gen[$ix_mens]['primer_mensualidad'] = $fecha_lim;
                                        if($pagos_aplicados > 0){
                                            $fecha_lim = $tmp_fecha;
                                        }
                                        $conceptos_gen[$ix_mens]['fechalimitepago'] = $fecha_lim;
                                    }

                                    $ix_inscr = array_search('Inscripción', array_column($conceptos_gen, 'categoria'));
                                    if($ix_inscr !== false){
                                        $conceptos_gen[$ix_inscr]['info_gen'] = $value;
                                        if(!$ix_mens){
                                            $prom_concep = $promoM->obtenerPromocion_concepto_alumno($alumno_id, $conceptos_gen[$ix_inscr]['id_concepto'])['data'];
                                            if(!empty($prom_concep)){
                                                $promos_aplicar = array_merge($promos_aplicar, $prom_concep);
                                            }
                                        }
                                    }
                                }
                            }

                            $conceptos_p = array_merge($conceptos_p, $conceptos_gen);
                            // buscar las promociones que vienen con la generacion
                            $promos_generacion = $promoM->obtenerPromociones_generacion($value['idGeneracion'])['data'];
                            $plan_pago['data']['generaciones'][$key]['promociones'] = $promos_generacion;
                            $promos_aplicar = array_merge($promos_aplicar, $promos_generacion);
                            
                            // identificar si el alumno ya tiene una generacion asignada
                            $asignado = $generacionesM->buscarAsignacion($alumno_id, $value['idGeneracion'])['data'];
                            $plan_pago['data']['generaciones'][$key]['asignacion'] = $asignado;
                            
                            if(sizeof($asignado) > 0){
                                $generacion_asign = $value['idGeneracion'];
                                $gen_i = $generacionesM->buscarGeneracion($value['idGeneracion'])['data'];
                                if($gen_i){
                                    $plan_pago['data']['generaciones'][$key]['asignacion'][0]['ciclo_actual'] = $gen_i['ciclo_actual'];
                                    $plan_pago['data']['generaciones'][$key]['asignacion'][0]['tipoCiclo'] = $gen_i['tipoCiclo'];
                                }
                            }
                        }
                    }
                    
                    /** 2)
                     * buscar las promociones que pertenecen al alumno unicamente
                     * , sobreescribir promociones que estuviesen a la generacion
                     *  */
                    $promos_aplicar = array_reduce($promos_aplicar, function($promos, $promo){
                        if(array_search($promo['idPromocion'], array_column($promos, 'idPromocion')) === false){
                            if(!isset($_POST['info_alumno'])){
                                if(array_search($promo['id_concepto'], array_column($promos, 'id_concepto')) === false){
                                    $promos[] = $promo;
                                }
                            }else{
                                $promos[] = $promo;
                            }
                        }
                        return $promos;
                    }, []);

                    // consultar conceptos generales    
                    $conceptos_generales = $p_pagosM->obtener_conceptos_generales($institucion);
                    // var_dump($conceptos_generales);
                    if(sizeof($conceptos_generales) > 0){
                    	 //var_dump($conceptos_p, $conceptos_generales);
                        $conceptos_generales = array_filter($conceptos_generales, function($concepto) use ($carrera_info){
                            return $concepto['institucion'] == $carrera_info['data']['idInstitucion'];
                        });
                        $conceptos_p = array_merge($conceptos_p, $conceptos_generales);
                    }
                    
                    /** Si no se ha asignado la generación, quitar conceptos de mensualidades y tutulaciones */
                    if($generacion_asign === null){    
                        foreach($conceptos_p as $key => $concepto){
                            // var_dump($concepto);
                            if($concepto['categoria'] != 'Inscripción' ){
                                unset($conceptos_p[$key]);
                            }
                        }
                    }
                    $conceptos_p = array_values($conceptos_p);

                    $plan_pago['data']['pagos_aplicar'] = $conceptos_p;
                    $plan_pago['data']['promociones'] = $promos_aplicar;
                    $plan_pago['data']['institucion'] = $carrera_info['data']['idInstitucion'];
                        
                    foreach ($plan_pago['data']['pagos_aplicar'] as $key2 => $value2) {
                        $plan_pago['data']['pagos_aplicar'][$key2]['aplicados'] = $p_pagosM->obtener_pagos_aplicados($value2['id_concepto'],$alumno_id)['data'];
                        $plan_pago['data']['pagos_aplicar'][$key2]['rechazados'] = $p_pagosM->obtener_otros_pagos($value2['id_concepto'],$alumno_id)['data'];
                    }
                    
                    $resp = $plan_pago;

                    /**
                     * si la asignación ya se ha hecho y la consulta la está haciendo el alumno
                     * se omitiran los conceptos de pago ed las otras generaciones
                     */
		    
                    //if(isset($_SESSION['alumno']['id_prospecto']) ){
                        foreach ($resp['data']['pagos_aplicar'] as $key => $value) {
                            $resp['data']['pagos_aplicar'][$key]['promociones'] = [];
                            for ($i=0; $i < sizeof($promos_aplicar); $i++) { 
                                if($value['id_concepto'] == $promos_aplicar[$i]['id_concepto']){
                                    array_push($resp['data']['pagos_aplicar'][$key]['promociones'], $promos_aplicar[$i]);
                                }
                            }

                            // validar prorroga de conceptos de titulacion y mensualidad
                            if($value['categoria'] == 'Mensualidad' || $value['categoria'] == 'Titulación'){
                                $consultar_p = $pagosM->validar_si_existe_prorroga($alumno_id, $value['id_concepto'], sizeof($value['aplicados'])+1);
                                if($consultar_p['estatus'] == 'ok' && $consultar_p['data']){
                                    // si el estatus de la prorroga es aprobado sobreescribe la fecha
                                    if($consultar_p['data']['estatus'] == 'aprobado'){
                                        $resp['data']['pagos_aplicar'][$key]['fechalimitepago'] = $consultar_p['data']['nuevafechaaceptada'];
                                    }
                                }
                            }

                            if($value['precio'] == 0){
                                unset($resp['data']['pagos_aplicar'][$key]);
                            }
                        }
                        $resp['data']['pagos_aplicar'] = array_values($resp['data']['pagos_aplicar']);
                    //}
                    foreach ($resp['data']['pagos_aplicar'] as $key => $value) {
                        if($generacion_asign !== null){
                            // si la generacion del concepto es diferente a la generacion asignada
                            // y la el concepto no es un concepto general
                            // y el concepto no es nulo
                            //if($value['id_generacion'] != $generacion_asign && (intval($value['id_generacion']) == 0 && intval($value['id_generacion']) != 0) && sizeof($value['aplicados']) == 0 && $value['generales'] != 1){
                            if($value['id_generacion'] != $generacion_asign && intval($value['generales']) != 1){
                                unset($resp['data']['pagos_aplicar'][$key]);
                            }
                        }
                    }
                    $resp['data']['pagos_aplicar'] = array_values($resp['data']['pagos_aplicar']);
                    $obtenerdatosprospecto=$prospectoM->obtenerdatosprospecto($alumno_id)['data'];
                    if ($obtenerdatosprospecto['tipoPago']==2) {
                        
                        foreach ($resp['data']['pagos_aplicar'] as $key => $value) {
                           $resp['data']['pagos_aplicar'][$key]['precio'] =$resp['data']['pagos_aplicar'][$key]['precio_usd'];
                        }
                    }
                    $resp['data']['tipoPago'] = $obtenerdatosprospecto['tipoPago'];
                }
                if(isset($_POST['info_alumno']) && $_POST['info_alumno'] == 1){
                    $resp['data']['info_alumno'] = $alumnM->buscar_alumno_afiliado($_POST['prospecto'])['data'];
                    // buscar imagen alumno
                    $img = '';
                    $valid = false;
                    $valid = @file_get_contents("https://conacon.org/moni/siscon/app/img/afiliados/".$resp['data']['info_alumno']['foto']);
                    if($valid === false){
                        $valid = @file_get_contents("https://moni.com.mx/udc/app/img/afiliados/".$resp['data']['info_alumno']['foto']);
						if($valid !== false){
							$img = "https://moni.com.mx/udc/app/img/afiliados/".$resp['data']['info_alumno']['foto'];
						}
                    }else{
                        $img = "https://conacon.org/moni/siscon/app/img/afiliados/".$resp['data']['info_alumno']['foto'];
                    }
                    if($img == ''){
                        $img = "https://conacon.org/moni/siscon/app/img/afiliados/defaultfoto.jpg";
                    }
                    $resp['data']['info_alumno']['foto'] = $img;
                }
            }else{
                $resp = ['estatus'=>'error', 'info'=>'Carrera no identificada', 'data'=>$_POST['inscrito_a']];
            }

            echo json_encode($resp);
            break;
        case 'obtener_plan_pago_callcenter_eventos':
            unset($_POST['action']);
            $alumno_id = null;
            if(isset($_SESSION['alumno']['id_prospecto'])){
                $alumno_id = $_SESSION['alumno']['id_prospecto'];
            }elseif(isset($_POST['prospecto'])){
                $alumno_id = $_POST['prospecto'];
            }
            
            $resp = [];
            
            $plan_pago = null;

            $evento_info = $eventoM->consultarEvento_Id($_POST['inscrito_a']);
            if($evento_info['data']){
                        
                #buscar plan de pago de la carrera
                
                $plan_pago = $p_pagosM->obtener_plan_pago_evento($_POST['inscrito_a']);
                $plan_pago['orig'] = 'Evento';
                
                if(!$plan_pago['data']){
                    $resp = ['estatus'=>'error', 'info'=>'No se encontró el plan de pago.'];
                }else {
                    if($alumno_id === null){
                        echo json_encode(['estatus'=>'error', 'info'=>'No se identificó al alumno.']);
                        die();
                    }
                    $generacion_asign = null;
                    
                    $conceptos_p = []; // todos los conceptos a pagar
                    $promos_aplicar = []; // promociones a aplicar
                    
                    /** 1)
                     * buscar los conceptos que pertenecen al plan de pago
                     * , solo se agregan si el concepto no esta asignado a una generacion
                     *  */ 
                    $conceptos_plan = $p_pagosM->obtener_conceptos_plan($plan_pago['data']['idPlanPago'])['data'];
                    

                    /** 2)
                     * buscar las promociones que pertenecen al alumno unicamente
                     * , sobreescribir promociones que estuviesen a la generacion
                     *  */
                    foreach($conceptos_plan as $concepto){
                        $promos_c = $promoM->obtenerPromocion_byConcepto($concepto['id_concepto'])['data'];
                        $promos_aplicar = array_merge($promos_aplicar, $promos_c);
                    }
                    
                    $conceptos_p = $conceptos_plan;
                    $promos_alumno = $promoM->obtenerPromociones_alumno($alumno_id)['data'];
                    foreach ($promos_alumno as $promo) {
                        $ix_promo = array_search($promo['id_concepto'], array_column($promos_aplicar, 'id_concepto'));
                        if($ix_promo !== false){
                            $promos_aplicar[$ix_promo] = $promo;
                        }else{
                            $promos_aplicar = array_merge($promos_aplicar, [$promo]);
                        }
                    }

                    $plan_pago['data']['pagos_aplicar'] = $conceptos_p;
                    $plan_pago['data']['promociones'] = $promos_aplicar;
                        
                    foreach ($plan_pago['data']['pagos_aplicar'] as $key2 => $value2) {
                        $plan_pago['data']['pagos_aplicar'][$key2]['aplicados'] = $p_pagosM->obtener_pagos_aplicados($value2['id_concepto'],$alumno_id)['data'];
                    }
                    
                    $resp = $plan_pago;

                    /**
                     * si la asignación ya se ha hecho y la consulta la está haciendo el alumno
                     * se omitiran los conceptos de pago ed las otras generaciones
                     */

                    
                    if(isset($_SESSION['alumno']['id_prospecto']) ){
                        foreach ($resp['data']['pagos_aplicar'] as $key => $value) {
                            $resp['data']['pagos_aplicar'][$key]['promociones'] = [];
                            for ($i=0; $i < sizeof($promos_aplicar); $i++) { 
                                if($value['id_concepto'] == $promos_aplicar[$i]['id_concepto']){
                                    array_push($resp['data']['pagos_aplicar'][$key]['promociones'], $promos_aplicar[$i]);
                                }
                            }
                        }
                        $resp['data']['pagos_aplicar'] = array_values($resp['data']['pagos_aplicar']);
                    }
                }
            }else{
                $resp = ['estatus'=>'error', 'info'=>'Evento no identificado', 'data'=>$_POST['inscrito_a']];
            }

            echo json_encode($resp);
            break;
        case 'pago_prospecto':
            unset($_POST['action']);
            $currency='mxn';
            if(isset($_POST['tipo_pago']) && isset($_POST['person_pago']) && isset($_POST['inp_monto_pago']) && isset($_POST['inp_fecha_pago'])){
                $fecha_pago = $_POST['inp_fecha_pago'];
                if(!isset($_SESSION['usuario']) && !isset($_SESSION['alumno'])){
                    echo json_encode(['estatus'=>'error', 'info'=>'No ha iniciado sesión']);
                    die();
                }
                $info_alumn = $prospectoM->consultar_info_prospecto_a($_POST['person_pago'])['data'];
                $monto_pagado = floatval(str_replace([',','$'], '', $_POST['inp_monto_pago']));
                $orig = isset($_POST['form_alumno']) ? $_POST['form_alumno'] : 'Callcenter';
                if($orig == 'Cobranza'){
                    $estatus_pago = 'verificado';
                }else{
                    $estatus_pago = 'pendiente';
                }
                $referencia_reportada = isset($_POST['inp_folio_pago']) ? $_POST['inp_folio_pago'] : $orig;
                if($orig != 'Cobranza' && (!isset($_FILES['inp_comprobante_pago']) || $_FILES['inp_comprobante_pago']['size'] == 0)){
                    $resp = ['estatus' => 'error', 'info' => 'Debe adjuntar un comprobante de pago.'];
                    echo json_encode($resp);
                    break;    
                }
                $nom_comprobante = 'autorizaciondulce.jpg';
                if (!empty($_FILES['inp_comprobante_pago']['name'])) {
                    $nom_comprobante = subir_comprobante($_FILES['inp_comprobante_pago'], $_POST['inp_folio_pago']);
                }
                $info_concepto = $pagosM->obtener_concepto($_POST['tipo_pago'])['data'];

                if ($info_alumn['tipoPago']==2) {//si el alumno tienepago en dolares se reeemplaza el precio por el monto en dolares
                    $info_concepto['precio'] = $info_concepto['precio_usd'];
                    $currency='usd';
                }



                $info_pago = $pagosM->obtener_pago_aplicar($_POST['person_pago'], $_POST['tipo_pago'], $fecha_pago);
                $detalle = json_encode($pagosM->formato_pago($orig, $monto_pagado, $fecha_pago, $info_alumn['nombre'], $info_alumn['apaterno'], $info_alumn['amaterno'], $info_alumn['email'], $info_concepto['descripcion']));
                $insertar = [
                    'quien_registro'=> (isset($_SESSION['usuario']) ? $_SESSION['usuario']['idAcceso'] : $_SESSION['alumno']['id_prospecto']),
                    'id_prospecto' => $_POST['person_pago'],
                    'id_concepto'   => $_POST['tipo_pago'],
                    'detalle'       => $detalle,
                    'montopagado'   => '0',
                    'cargo_retardo' => '0',
                    'restante'      => '0',
                    'saldo'         => '0',
                    'costototal'    => round($info_concepto['precio'] - $info_pago['monto_promocion'], 2),
                    'numero_de_pago'=> $info_pago['numero_de_pago'],
                    'fecha_limite_pago'=> '',
                    'idPromocion' => (isset($info_pago['id_promocion'])?$info_pago['id_promocion']:null),
                    'fechapago'     => $fecha_pago,
                    'comprobante'   => $nom_comprobante,
                    'como_realizo_pago'=> $_POST['metodo_de_pago_1'],
                    'metodo_de_pago'=> $_POST['metodo_de_pago'],
                    'banco_de_deposito'=> isset($_POST['crearbancodedeposito']) ? $_POST['crearbancodedeposito'] : null,
                    'estatus'       => $estatus_pago,
                    'codigo_de_autorizacion' => null,
                    'referencia'    => $referencia_reportada,
                    'moneda' =>$currency,
                    'comentario_callcenter' => isset($_POST['comentario_callcenter']) ? $_POST['comentario_callcenter'] : '',
                ];

                if($monto_pagado >= ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo'])){
                    // se está cubriendo el monto del pago requerido
                    $insertar['montopagado'] = $monto_pagado - $info_pago['monto_retardo'];
                    $insertar['cargo_retardo'] = $info_pago['monto_retardo'];
                    $insertar['restante'] = ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo']) - $monto_pagado;
                    if($info_concepto['numero_pagos'] > 1){
                        $dia_regular = $pagosM->consultar_fecha_corte_mensualidad($_POST['person_pago'], $_POST['tipo_pago']);
                        if($dia_regular){
                            $info_pago['fecha_limite_pago'] = substr($info_pago['fecha_limite_pago'], 0, 8).$dia_regular;
                        }
                        $insertar['fecha_limite_pago'] = date("Y-m-d", strtotime('+1 month', strtotime($info_pago['fecha_limite_pago'])));
                    }else{
                        $insertar['fecha_limite_pago'] = $info_pago['fecha_limite_pago'];
                    }
                }else{
                    // se está cubriendo menos del monto requerido
                    $sobrante = 0;
                    if($monto_pagado >= $info_pago['monto_por_pagar']){
                        $insertar['montopagado'] = $info_pago['monto_por_pagar'];
                        $sobrante = $monto_pagado - $info_pago['monto_por_pagar'];
                        $insertar['saldo'] = $info_pago['monto_retardo'] - $sobrante;
                        $insertar['cargo_retardo'] = $sobrante;
                        $insertar['restante'] = 0;
                    }else{
                        $insertar['montopagado'] = $monto_pagado;
                        $insertar['restante'] = $info_pago['monto_por_pagar'] - $monto_pagado;
                        $insertar['saldo'] = $info_pago['monto_retardo'];
                    }
                    /**          */
                    /* if($insertar['restante'] < 0){
                        $insertar['saldo'] = $info_pago['monto_retardo'] - abs($insertar['restante']);
                        $insertar['restante'] = 0;
                    }else{
                        $insertar['saldo'] = $info_pago['monto_retardo'];
                    } */
                    $insertar['fecha_limite_pago'] = $info_pago['fecha_limite_pago'];
                }
				$insertar['restante'] = round($insertar['restante'], 2);
				$insertar['saldo'] = round($insertar['saldo'], 2);
				
                $insert = $pagosM->registrar_pago_mult($insertar);
                if($insert > 0){
                    $resp['estatus'] = 'ok';
                    $resp['info'] = $insert;
                }else{
                    $resp['estatus'] = 'error';
                    $resp['info'] = 'No se pudo registrar el pago';
                }
                echo json_encode($resp);
            }else{
                echo json_encode(['estatus'=>'error', 'info'=>'No se pudo obtener la información del pago.']);
            }
            break;
        case 'asignar_generacion':
            $resp = [];
            // verificar informacion del alumno
            $info_alumn = $prospectoM->consultar_info_prospecto_a($_POST['alumno_generacion']);
            if(!$info_alumn['data']){
                echo json_encode(['estatus'=>'error', 'info'=>'No se encontró el alumno']);
                die();
            }
            // verificar la informacion de la generacion
            $info_generacion = $generacionesM->buscarGeneracion($_POST['select_alumno_gen']);
            if(!$info_generacion['data']){
                echo json_encode(['estatus'=>'error', 'info'=>'No se encontró la generacion']);
                die();
            }
             // verificar si ya existe la vista de cursos
             $validarsitienevistacursos= $alumnM->validarsitienevistacursos($info_alumn['data']['id_prospecto']);
             if(!$validarsitienevistacursos['data']){
                 $insertarvistacursos= $alumnM->insertarvistacursos($info_alumn['data']['id_prospecto']);
             }
            // verificar que no exista una asignacion de generacion
            $info_asignacion = $generacionesM->buscarAsignacion($info_alumn['data']['id_prospecto'], $info_generacion['data']['idGeneracion']);
            if(sizeof($info_asignacion['data']) > 0){
                echo json_encode(['estatus'=>'error', 'info'=>'Ya existe una asignacion de generacion']);
                die();
            }
            // verificar pago realizado y verificado
            $plan_p_alumno = $p_pagosM->obtener_plan_pago_carrera($info_generacion['data']['idCarrera']);
            if(!$plan_p_alumno['data']){
                echo json_encode(['estatus'=>'error', 'info'=>'No se encontró el plan de pagos']);
                die();
            }
            $pagos_realizados = $pagosM->obtener_pagos_plan_alumno($info_alumn['data']['id_prospecto'], $plan_p_alumno['data']['idPlanPago']);
            $pagos_realizados['data'] = array_merge($pagosM->obtener_pagos_generacion_alumno($info_alumn['data']['id_prospecto'], $info_generacion['data']['idGeneracion'])['data'], $pagos_realizados['data']);
            $pago_aplicado = false;
            foreach($pagos_realizados['data'] as $pago){
                if($pago['estatus'] == 'verificado' && $pago['categoria'] == 'Inscripción'){
                    $pago_aplicado = true;
                }
            }
            
            if(!$pago_aplicado){
                echo json_encode(['estatus'=>'error', 'info'=>'No se encontró un pago verificado']);
                die();
            }else{
                $resp = $generacionesM->asignar_generacion_alumno($info_alumn['data']['id_prospecto'], $info_generacion['data']['idGeneracion']);
                // verificar que el usuario exista en la tabla de afiliados (alumnos)
                $afiliado = $alumnM->buscar_alumno_afiliado($info_alumn['data']['id_prospecto']);
                $carrera_inf = $carreraM->consultarCarreraByID($info_generacion['data']['idCarrera']);
                if($afiliado['data']){
                    // si existe el afiliado verificar que el alumno tenga registro de asignación a la institucion
                    $afiliado_alumno = $alumnM->buscar_alumno('',$info_alumn['data']['id_prospecto']);
                    
                    if(sizeof($afiliado_alumno['data']) == 0){
                        // si no existe registro de asignación a la institucion, crearlo
                        // obtener informacion de la carrera apatir de la generacion
                        $d = [
                            "prospecto"=>$info_alumn['data']['id_prospecto'],
                            "institucion"=>$carrera_inf['data']['idInstitucion']
                        ];
                        $nueva_asign = $alumnM->crear_registro_institucion($d);
                        $resp['nuevo_instit'] = $nueva_asign;
                    }else{
                        // verificar que la institucion de la carrera sea una de las que el alumno tiene asignada
                        $con_instit = false;
                        
                        foreach ($afiliado_alumno['data'] as $key => $value) {
                            if($value['id_institucion'] == $carrera_inf['data']['idInstitucion']){
                                $con_instit = true;
                            }
                        }
                        if(!$con_instit){
                            $d = [
                                "prospecto"=>$info_alumn['data']['id_prospecto'],
                                "institucion"=>$carrera_inf['data']['idInstitucion']
                            ];
                            $nueva_asign = $alumnM->crear_registro_institucion($d);
                            $resp['nuevo_instit'] = $nueva_asign;
                        }
                        #si la intitucion de la carrera no es una de las que el alumno tiene asignada se crea otro registro de asignacion
                    }
                }else{
                    // crear el registro del prospecto en la tabla de afiliados
                    $info_p = $prospectoM->consultar_info_prospecto_a($info_alumn['data']['id_prospecto']);
                    $d = [
                        'prospecto' => $info_p['data']['id_prospecto'],
                        'email' => $info_p['data']['email'],
                        'celular' => $info_p['data']['celular']
                    ];
                    $afil = $alumnM->crear_registro_afiliado($d);
                    if($afil['estatus'] == 'ok'){
                        // crear el registro de asignacion de la institucion
                        $d = [
                            "prospecto"=>$info_alumn['data']['id_prospecto'],
                            "institucion"=>$carrera_inf['data']['idInstitucion']
                        ];
                        $nueva_asign = $alumnM->crear_registro_institucion($d);
                        $resp['nuevo_instit'] = $nueva_asign;
                    }
                }
                $resp['persona'] = $info_alumn['data']['id_prospecto'];
                $resp['carrera'] = $carrera_inf['data']['idCarrera'];
            }

            echo json_encode($resp);
            break;
        case 'consultar_porcentaje_recargo':
            echo json_encode($pagosM->porcentaje_recargo);
            break;
        case 'consultar_historial_pago':
            $alumno = false;
            $alumno = (isset($_SESSION['alumno'])? $_SESSION['alumno']['id_prospecto'] : (isset($_POST['idAsistente']) ? $_POST['idAsistente'] : $alumno));
            if($alumno){
                $pagos = $pagosM->obtener_historial_pago($alumno);
                // var_dump($pagos);
                // die();
                $claves_restantes = [];
                $ig = 1;
                foreach($pagos as $key => $pago){
                    $pagos[$key]['numOrder'] = $ig;
                    $pagos[$key]['detalle_pago'] = json_decode($pago['detalle_pago'], true);
                    $clave_operacion = $pago['id_prospecto'].'-'.$pago['id_concepto'];
                    if(!in_array($clave_operacion, array_keys($claves_restantes))){
                        $claves_restantes[$clave_operacion] = floatval($pago['costototal']) - floatval($pago['montopagado']);
                    }else{
                        $claves_restantes[$clave_operacion]-=floatval($pago['montopagado']);
                    }
                    $pagos[$key]['rest_costo_concep'] = $claves_restantes[$clave_operacion];
                    $pagos[$key]['nombre_callcenter']='';
                    if($pago['quien_registro'] > 0){
                        $quien_registro = $pagosM->quien_registro($pago['quien_registro'])['data'];
                        if (isset($quien_registro['idTipo_Persona'])==3) {
                            $info_p = $pagosM->nombre_marketing($quien_registro['idPersona']);
                            if($info_p['data']){
                                $pagos[$key]['nombre_callcenter'] = $pagosM->nombre_marketing($quien_registro['idPersona'])['data']['nombres'];
                            }else{
                                $pagos[$key]['nombre_callcenter'] = '';
                            }
                        }
                    }

                    if($pago['concepto_categoria'] == 'Mensualidad'){
                        if(intval($pago['numero_de_pago']) - 1 >= 1){
                            $prev_fech = $pagosM->obtener_fecha_limite_pago_anterior($pago['id_prospecto'], $pago['id_concepto'], intval($pago['numero_de_pago']) - 1, $pago['id_pago']);
                            if($prev_fech && $prev_fech['fecha_limite_pago'] !== null && $prev_fech['fecha_limite_pago'] != ''){
                                $pagos[$key]['fecha_limite_pago'] = $prev_fech['fecha_limite_pago'];
                            }
                        }else{
                            $generacion_info = $generacionesM->buscarGeneracion($pago['id_generacion'])['data'];
                            $fecha_lim = $pago['fecha_limite_pago'];
                            $fecha_lim = substr($generacion_info['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
                            if(strtotime($fecha_lim) < strtotime($generacion_info['fecha_inicio'])){
                                $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
                            }
                            $asign_gen = $generacionesM->buscarAsignacion($pago['id_prospecto'], $generacion_info['idGeneracion']);
                            if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
                                if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
                                    $fecha_lim = $asign_gen['data'][0]['fecha_primer_colegiatura'];
                                }
                            }
                            $pagos[$key]['fecha_limite_pago'] = $fecha_lim;
                        }
                    }

                    $ig++;
                }
                $favor = $pagosM->obtener_saldo_a_favor_alumno($alumno);
                if(abs(floatval($favor['saldo_favor'])) > 0){
                    array_push($pagos, ['saldo_favor' => abs(floatval($favor['saldo_favor']))]);
                }
                echo json_encode($pagos);
            }else{
                echo json_encode(['estatus'=>'error', 'info'=>'No se encontró el alumno']);
            }
            break;
        case 'consultar_historial_pago_id':
            if(isset($_POST['idAsistente'])){
                $pagos = $pagosM->obtener_historial_pago($_POST['idAsistente']);
                foreach($pagos as $key => $pago){
                    $pagos[$key]['detalle_pago'] = json_decode($pago['detalle_pago'], true);
                }
                $favor = $pagosM->obtener_saldo_a_favor_alumno($_POST['idAsistente']);
                if(abs(floatval($favor['saldo_favor'])) > 0){
                    array_push($pagos, ['saldo_favor' => abs(floatval($favor['saldo_favor']))]);
                }
                echo json_encode($pagos);
            }else{
                echo json_encode(['estatus'=>'error', 'info'=>'No se encontró el alumno']);
            }
            break;
        case 'generar_ficha_oxxo':
            require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");
            $currency='MXN';
            $id_concepto_pago= @$_POST['id_tipo_pago_concepto'];
            $id_promocion= @$_POST['id_promocion'];
            $nombre_concepto = @$_POST['nombre_concepto'];
            $monto_pago = @$_POST['monto_pago'];
            $monto_recargo_pagado = @$_POST['monto_recargo_pagado'];
            $id_prospecto = $_POST['id_prospecto'];



            if ($_POST['id_prospecto']=='sesion_user') {
                $id_prospecto = $_SESSION['alumno']['id_prospecto'];
                $datosprospecto=$pagosM->obtener_datos_prospecto($id_prospecto);
            } else {
                $datosprospecto=$pagosM->obtener_datos_prospecto($_POST['id_prospecto']);
            }
            $info_alumn = $prospectoM->consultar_info_prospecto_a($id_prospecto )['data'];
            if ($info_alumn['tipoPago']==2) {
                $currency='USD';
              }
            if (@$_POST['monto_con_recargo']!='') {
                $id_promocion='';
                $monto_pago=@$_POST['monto_con_recargo'];
                $monto_pago = ltrim($monto_pago, '$');
                $monto_pago = str_replace(',', '', $monto_pago);
                $datos_concepto=$pagosM->obtener_concepto($id_concepto_pago);
                $monto_recargo_pagado=$monto_pago-$datos_concepto['data']['precio'];
            }
            if (@$_POST['monto_con_promocion']!='') {
                $monto_pago=@$_POST['monto_con_promocion'];
            }
            $monto_pago = ltrim($monto_pago, '$');
            $monto_pago = str_replace(['$',' ',','], '', $monto_pago);
            $monto_pago = floatval($monto_pago);
            $monto_pago = round($monto_pago, 2);
            $nombre_prospecto=$datosprospecto['data']['nombre_completo'];
            $correo=$datosprospecto['data']['correo'];
            $telefono_prospecto=$datosprospecto['data']['telefono'];
            if (strlen($telefono_prospecto)<10) {
                $telefono_prospecto='222 222 2222';
            }

            try{
                $thirty_days_from_now = (new DateTime())->add(new DateInterval('P30D'))->getTimestamp(); 
                
                $order = \Conekta\Order::create(
                    [
                    "line_items" => [
                        [
                        "name" => $nombre_concepto,
                        "unit_price" => round($monto_pago*100, 1),
                        "quantity" => 1,
                        "metadata" => [
                            "id_concepto" => $id_concepto_pago,
                            "id_prospecto" => $id_prospecto,
                            "id_promocion" => $id_promocion,
                            "monto_recargo_pago" => $monto_recargo_pagado
                        ]
                        ]
                    ],
                    "currency" => $currency,
                    "customer_info" => [
                        "name" => $nombre_prospecto,
                        "email" => $correo,
                        "phone" => $telefono_prospecto
                    ],
                    "charges" => [
                        [
                        "payment_method" => [
                            "type" => "oxxo_cash",
                            "expires_at" => $thirty_days_from_now
                        ]
                        ]
                    ]
                    ]
                );
                } catch (\Conekta\ParameterValidationError $error){
                echo $error->getMessage();
                } catch (\Conekta\Handler $error){
                echo $error->getMessage();
                }

                $referencia=$order->charges[0]->payment_method->reference;
                $referencia_formateada = "";
                if (strlen($referencia) == 14) {
                    $referencia_formateada.= substr($referencia, 0, 4)."-";
                    $referencia_formateada.= substr($referencia, 4, 4)."-";
                    $referencia_formateada.= substr($referencia, 8, 4)."-";
                    $referencia_formateada.= substr($referencia, 12, 2);
                }
                $archivo_bar_code='urlbar_code_'.date("Y-m-d H-i-s").'.png';
                // llenar array para imprimirlo en json
                $imagen_bar_code = file_get_contents($order->charges[0]->payment_method->barcode_url);
                file_put_contents('../../../images/bar_codes_oxxo/'.$archivo_bar_code, $imagen_bar_code);
                $data = [
                    'order_id' => $order->id,
                    'metodo_de_pago' => $order->charges[0]->payment_method->service_name,
                    'referencia' => $referencia_formateada,
                    'monto' => '$'.$order->amount/100,
                    'tipo_moneda' => $currency,
                    'nombre_producto' => $order->line_items[0]->name,
                    'id_concepto' => $order->line_items[0]->metadata->id_concepto,
                    'url_codigo_barras' => $archivo_bar_code

                ];
                echo json_encode($data);
            break;
        case 'generar_ficha_spei':
            require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");
            
            $id_concepto_pago= @$_POST['id_tipo_pago_concepto'];
            $id_promocion= @$_POST['id_promocion'];
            $nombre_concepto = @$_POST['nombre_concepto'];
            $monto_pago = @$_POST['monto_pago'];
            $monto_recargo_pagado = @$_POST['monto_recargo_pagado'];
            $currency='mxn';



            $id_prospecto = $_POST['id_prospecto'];

            $info_alumn = $prospectoM->consultar_info_prospecto_a($_SESSION['alumno']['id_prospecto'])['data'];
            if ($info_alumn['tipoPago']==2) {//si el prospecto tiene definido pago en dolares
                $currency='usd';
              }

            if ($_POST['id_prospecto']=='sesion_user') {
                $id_prospecto = $_SESSION['alumno']['id_prospecto'];
                $datosprospecto=$pagosM->obtener_datos_prospecto($id_prospecto);
            } else {
                $datosprospecto=$pagosM->obtener_datos_prospecto($_POST['id_prospecto']);
            }
            if (@$_POST['monto_con_recargo']!='') {
                $id_promocion='';
                $monto_pago=@$_POST['monto_con_recargo'];
                $monto_pago = ltrim($monto_pago, '$');
                $monto_pago = str_replace(',', '', $monto_pago);
                $datos_concepto=$pagosM->obtener_concepto($id_concepto_pago);
                $monto_recargo_pagado=$monto_pago-$datos_concepto['data']['precio'];
            }
            // if (@$_POST['monto_con_promocion']!='') {
            //     $monto_pago=@$_POST['monto_con_promocion'];
            // }
            $info_pago = $pagosM->obtener_pago_aplicar($id_prospecto, $id_concepto_pago, date("Y-m-d"));
            if(!isset($_POST['monto_pago'])){
                $monto_pago=$info_pago['monto_por_pagar'];
            }
            $id_promocion=$info_pago['id_promocion'];

            $monto_pago = ltrim($monto_pago, '$');
            $monto_pago = str_replace(['$',' ',','], '', $monto_pago);
            $monto_pago = floatval($monto_pago);
            $monto_pago = round($monto_pago, 2);
            $nombre_prospecto=$datosprospecto['data']['nombre_completo'];
            $correo=$datosprospecto['data']['correo'];
            $telefono_prospecto=$datosprospecto['data']['telefono'];
            if (strlen($telefono_prospecto)<10) {
                $telefono_prospecto='222 222 2222';
            }
            try{
                $thirty_days_from_now = (new DateTime())->add(new DateInterval('P30D'))->getTimestamp(); 
                
                $order = \Conekta\Order::create(
                    [
                    "line_items" => [
                        [
                        "name" => $nombre_concepto,
                        "unit_price" => $monto_pago*100,
                        "quantity" => 1,
                        "metadata" => [
                            "id_concepto" => $id_concepto_pago,
                            "id_prospecto" => $id_prospecto,
                            "id_promocion" => $id_promocion,
                            "monto_recargo_pago" => $monto_recargo_pagado
                        ]
                        ]
                    ],
                    "currency" => $currency,
                    "customer_info" => [
                        "name" => $nombre_prospecto,
                        "email" => $correo,
                        "phone" => $telefono_prospecto
                    ],
                    "charges" => [
                        [
                        "payment_method" => [
                            "type" => "spei",
                            "expires_at" => $thirty_days_from_now
                        ]
                        ]
                    ]
                    ]
                );
                } catch (\Conekta\ParameterValidationError $error){
                echo $error->getMessage();
                } catch (\Conekta\Handler $error){
                echo $error->getMessage();
                }

                $CLABE=$order->charges[0]->payment_method->receiving_account_number;
                // llenar array para imprimirlo en json
                $data = [
                    'order_id' => $order->id,
                    'metodo_de_pago' => 'Spei Conekta',
                    'CLABE' => $CLABE,
                    'monto' => '$'.$order->amount/100,
                    'tipo_moneda' => $order->currency,
                    'nombre_producto' => $order->line_items[0]->name,
                    'id_concepto' => $order->line_items[0]->metadata->id_concepto,
                    'bank' => $order->charges[0]->payment_method->receiving_account_bank

                ];
                echo json_encode($data);
            break;
        case 'generar_ficha_banorte':
            
            $id_concepto_pago= @$_POST['id_tipo_pago_concepto'];
            $id_promocion= @$_POST['id_promocion'];
            $nombre_concepto = @$_POST['nombre_concepto'];
            $monto_pago = @$_POST['monto_pago'];
            $monto_recargo_pagado = @$_POST['monto_recargo_pagado'];



            $id_prospecto = $_POST['id_prospecto'];
            if ($_POST['id_prospecto']=='sesion_user') {
                $id_prospecto = $_SESSION['alumno']['id_prospecto'];
                $datosprospecto=$pagosM->obtener_datos_prospecto($id_prospecto);
            } else {
                $datosprospecto=$pagosM->obtener_datos_prospecto($_POST['id_prospecto']);
            }
            if (@$_POST['monto_con_recargo']!='') {
                $id_promocion='';
                $monto_pago=@$_POST['monto_con_recargo'];
                $datos_concepto=$pagosM->obtener_concepto($id_concepto_pago);
                $monto_recargo_pagado=$monto_pago-$datos_concepto['data']['precio'];
            }
            if (@$_POST['monto_con_promocion']!='') {
                $monto_pago=@$_POST['monto_con_promocion'];
            }
            $nombre_prospecto=$datosprospecto['data']['nombre_completo'];
            $correo=$datosprospecto['data']['correo'];
            $telefono_prospecto=$datosprospecto['data']['telefono'];
            
                $data = [
                    'order_id' => $order->id,
                    'metodo_de_pago' => $order->charges[0]->payment_method->service_name,
                    'referencia' => $datosprospecto['data']['referencia'],
                    'monto' => $monto_pago,
                    'tipo_moneda' => $order->currency,
                    'nombre_producto' => $order->line_items[0]->name,
                    'id_concepto' => $order->line_items[0]->metadata->id_concepto,
                    'url_codigo_barras' => $archivo_bar_code,
                    'nombre_prospecto' => $nombre_prospecto,

                ];
                echo json_encode($data);
            break;
        case 'solicitar_prorroga':
            $id_prospecto = $_SESSION['alumno']['id_prospecto'];
            $fecha_solicitud = date("Y-m-d H:i:s");
            $numero_de_pago = $_POST['numero_de_pago'];
            $fecha_limite_pago = $_POST['fecha_limite_pago'];
            $solicitar_prorroga = $pagosM->solicitar_prorroga($id_prospecto,$_POST['id_concepto'], $_POST['descripcion_prorroga'],$_POST['nueva_fecha_prorroga'], $fecha_solicitud, $numero_de_pago, $fecha_limite_pago);
            echo json_encode($solicitar_prorroga);
            break;
        case 'aplicar_promesa':
            if(isset($_POST['id_promesa'])){
                $info_p = $pagosM->obtener_informacion_registro_pago($_POST['id_promesa']);
                $info_p = $info_p['data'];
                if($info_p){
                    if($info_p['categoria'] == 'Inscripción' && $info_p['estatus'] == 'verificado' && floatval($info_p['restante']) > 0){
                        $aplicar_promesa = $pagosM->aplicar_promesa($_POST['id_promesa']);
                    }else{
                        $aplicar_promesa = ['estatus'=>'error', 'info'=>'El pago no cumple con los requisitos para asignar una promesa de pago.'];
                    }
                }else{
                    $aplicar_promesa = ['estatus'=>'error', 'info'=>'No se pudo obtener información del pago.'];
                }
            }else{
                $aplicar_promesa = ['estatus'=>'error', 'info'=>'No se pudo aplicar la promesa'];
            }
            echo json_encode($aplicar_promesa);
            break;
        case 'validar_si_existe_prorroga':
            if(!isset($_SESSION['alumno']['id_prospecto'])){// si la sesion de lsusuario no existe obtenemos el id del prospecto mediante post
                $id_prospecto = @$_POST['id_prospecto'];    
            }else{
                $id_prospecto = $_SESSION['alumno']['id_prospecto'];
            }
            $id_concepto = @$_POST['id_concepto'];
            $numero_de_pago = @$_POST['numero_de_pago'];
            $validar_si_existe_prorroga = $pagosM->validar_si_existe_prorroga($id_prospecto, $id_concepto, $numero_de_pago);
            $validar_promocion =$promoM->obtenerPromocion_byIDconcepto($id_concepto);
            echo json_encode($validar_si_existe_prorroga);

            break;
            case 'obtener_totales_carrera':
                unset($_POST['action']);
                $idAsistente=$_SESSION['alumno']['id_prospecto'];
                $csul = $pagosM->obtenerTotalesCarreras($idAsistente);
                $data = Array();
                while($dato=$csul->fetchObject()){
                    $montopagado=floatval($dato->costototal)-floatval($dato->restante);
                    if ($dato->restante==0) {
                        $montopagado=0;
                    }
                    if ($dato->restante==0) {
                        $dato->restante=$dato->costototal;
                    }
                    $data[]=array(
                        0=> $dato->nombrecarrera,
                        1=> number_format($dato->costototal),
                        2=> number_format($montopagado),
                        3=> number_format($dato->restante)
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
            case 'obtener_totales_carrera_id':
                unset($_POST['action']);
                $idAsistente=$_POST['idAsistente'];
                $csul = $pagosM->obtenerTotalesCarreras($idAsistente);
                $data = Array();
                while($dato=$csul->fetchObject()){
                    $montopagado=floatval($dato->costototal)-floatval($dato->restante);
                    if ($dato->restante==0) {
                        $montopagado=0;
                    }
                    if ($dato->restante==0) {
                        $restante=$dato->costototal;
                    }
                    $data[]=array(
                        0=> $dato->nombrecarrera,
                        1=> number_format($dato->costototal),
                        2=> number_format($montopagado),
                        3=> number_format($dato->restante)
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
        case 'obtener_info_pago_aplicar':
            if(isset($_POST['concepto']) && isset($_POST['alumno'])){
                $fecha_pago = isset($_POST['fecha_pago']) ? $_POST['fecha_pago'] : date('Y-m-d');
                if(trim($fecha_pago) != ''){
                    $info_pago = $pagosM->obtener_pago_aplicar($_POST['alumno'], $_POST['concepto'], $fecha_pago);
                    $info_pago['pago_pendiente'] = $pagosM->obtener_pago_pendiente($_POST['alumno'], $_POST['concepto']);
                    echo json_encode($info_pago);
                }else{
                    echo json_encode(['estatus'=>'error', 'info'=>'La fecha de pago es requerida.']);
                }
            }else{
                echo json_encode(['estatus'=>'error', 'info'=>'No se pudo obtener la información del pago.']);
            }
            break;
        case 'consultar_ofertas':
            $concepto_info = $pagosM->obtener_concepto($_POST['concepto'])['data'];
            $info_alumn = $prospectoM->consultar_info_prospecto_a($_POST['prospecto'])['data'];
            if(($concepto_info && intval($concepto_info['id_generacion']) > 0) || $concepto_info['descripcion'] == 'Inscripción - PLAN CISMAC 2022'){
                // consultar si ya tiene una oferta asignada de ese concepto, si si, remover oferta
                $promos_arr = $promoM->consultar_ofertas_generacion($concepto_info['id_generacion']);
                foreach($promos_arr as $tmp => $promocion){
                    foreach($promocion['conceptos'] as $info => $concepto_x){
                        $promo_i = $promoM->validar_promo_exist($concepto_x['id_concepto'], null, $_POST['prospecto']);
                        if(!empty($promo_i['data'])){
                            unset($promos_arr[$tmp]['conceptos'][$info]);
                        }else{
                            $info_con = $pagosM->obtener_concepto($concepto_x['id_concepto'])['data'];

                            if ($info_alumn['tipoPago']==2) {//si el alumno tienepago en dolares se reeemplaza el precio por el monto en dolares
                                $info_con['precio'] = $info_con['precio_usd'];
                              }

                            // var_dump($info_con);
                            $promos_arr[$tmp]['conceptos'][$info]['precio_lista'] = $info_con['precio'];
                        }
                    }
                    if(empty($promos_arr[$tmp]['conceptos'])){
                        unset($promos_arr[$tmp]);
                    }
                }
                $promos_arr = array_values($promos_arr);
                echo json_encode($promos_arr);
            }else{
                echo json_encode([]);
            }
        default:
            # code...
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}


function subir_comprobante($archivo, $titulo){
    $titulo = str_replace(["#","&","$","/","*",".","-",",","{","}","[","]","?","¿","_","="," "], '', $titulo);
	$titulo = preg_replace("/[^a-zA-Z0-9]+/", "", $titulo);
    $tmp_name = $archivo["tmp_name"];
    $uploads_dir = "../../../files/comprobantes_pago";
    $name = basename($archivo["name"]);
    $fileT = explode(".", $archivo["name"]);
    $fileT = $fileT[sizeof($fileT)-1];
    
    $nName = $titulo."_".date("Y-m-d H-i-s").".".$fileT;
    $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
    return $statFile ? $nName : '';
}
