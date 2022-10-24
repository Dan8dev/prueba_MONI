<?php
session_start();
if (isset($_POST["action"])) {
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/promocionesModel.php';
    require_once '../../Model/planpagos/pagosModel.php';
    require_once '../../Model/planpagos/generacionesModel.php';

    $promociones = new Promociones();
    $pagosM = new pagosModel();
    $generacionM = new Generaciones();

    $accion=@$_POST["action"];
    
    switch ($accion) {
        case 'crearpromocion':
            unset($_POST['action']);
            $carrera = false;
            $fechacreado= date("Y-m-d H:i:s");
            $_POST += [ "fechacreado" => $fechacreado ];
            $alumnogeneracion=$_POST['selecalumnogeneracion'];
            unset($_POST['selecalumnogeneracion']);
            $key_promos = [
                'promoinscripcion'  =>'idconceptopromoinscripcion', 
                'promomensualidades'=>'idconceptopromomensualidades', 
                'promoreinscripciones'=>'idconceptopromoreinscripciones', 
                'promotitulacion'=>'idconceptopromotitulacion'
            ];
            if($alumnogeneracion == 2 && ((isset($_POST['promoinscripcion']) && intval($_POST['promoinscripcion']) > 100) || (isset($_POST['promomensualidades']) && intval($_POST['promomensualidades']) > 100))){
                $tipo = $_POST["selecpromobeca"] == 2 ? "Beca" : "Promoción";
                echo json_encode(['data'=>0, 'mensaje' => "{$tipo} no válida. Para establecer una {$tipo} del 100% es necesario especificar el beneficiario. O puede crear una oferta para que sea seleccionada por Marketing educativo."]);
                die();
            }
            foreach ($key_promos as $key => $value) {
                if(isset($_POST[$key]) && intval($_POST[$key]) > 100){
                    echo json_encode(['data' => 0, 'mensaje' => "Las cantidades de promoción no pueden ser mayores a 100%"]);
                    die();
                }
            }
            // if(intval($_POST['promoinscripcion']) > 100 || intval($_POST['promomensualidades']) > 100 || intval($_POST['promoreinscripciones']) > 100 || intval($_POST['promotitulacion']) > 100){
            //     echo json_encode(['data' => 0, 'mensaje' => "Las cantidades de promoción no pueden ser mayores a 100%"]);
            //     die();
            // }
            // alumnogeneracion tipo 4 es una oferta para la generacion
            if($alumnogeneracion != 4){ // si es distinto es una promocion directa
                $fechainicio=isset($_POST['promofechainicial']) ? $_POST['promofechainicial'] : date('Y-m-d');
                $fechafin=isset($_POST['promofechafinal']) ? $_POST['promofechafinal'] : date('Y-m-d');
                $fechainicio=strtotime(date($fechainicio." 00:00:00"));
                $fechafin=strtotime(date($fechafin." 23:59:59"));
                $fechahoy=strtotime(date("Y-m-d H:i:s"));
                if($fechafin < $fechainicio){
                    echo json_encode(['estatus'=>'error', 'mensaje'=>'La fecha de inicio de la promoción no puede ser posterior a la fecha final']);
                    die();
                }
    
                $omitidos = []; // devolver las mensualidades que no se hayan podido crear porque el numero de pago ya existiera
    
                $estatus = 'activo';
                $i = 0;
                foreach ($_POST as $key => $value) {
                    if(in_array($key, array_keys($key_promos))){
                        $porcentaje = floatval($_POST[$key]);
                        $id_concepto = $_POST[$key_promos[$key]];
                        if($porcentaje > 0){
                            $exist = null;
                            $numero_pagos = false;
                            if($alumnogeneracion == 1){
                                $exist = $promociones->validar_promo_exist($id_concepto, null, $_POST['listaralumnos'])['data'];
                            }else if($alumnogeneracion == 2){
                                $exist = $promociones->validar_promo_exist($id_concepto, $_POST['listargeneraciones'], null)['data'];
                            }
                            $crear = false;
                            $confirm = true;
                            if($key == 'promomensualidades' && isset($_POST['multiple_mensualidades'])){
                                $numero_pagos = $_POST['multiple_mensualidades'];
                            }
                            if($exist !== null){
                                if(empty($exist)){
                                    $crear = true;
                                }else{
                                    // validar si es promocion por rango de fecha o por numero de pago
                                    foreach($exist as $promo => $promo_i){
                                        $promo_e = $promo_i;
                                        if($promo_e['fechainicio'] != null && $promo_e['fechafin'] != null){
                                            // validar si el rango de fechas se encuentra dentro de las fechas de la promocion
                                            $i_promo = strtotime($promo_e['fechainicio']); # <-- CADA PROMOCION
                                            $f_promo = strtotime($promo_e['fechafin']); # <-- CADA PROMOCION

                                            // si esta fuera del rango de fechas de la promocion existente se crea sin problemas
                                            if($fechainicio > $f_promo || $fechafin < $i_promo){
                                                $crear = true;
                                            }else{
                                                // si esta dentro del rango de fecha de la promocion existente se valida si el estatus de la promocion existente es activo
                                                if($promo_e['estatus'] != 'activo'){
                                                    // se crea
                                                    $crear = true;
                                                }else{
                                                    $confirm = false;
                                                }
                                            }
                                        }else if($promo_e['Nopago'] != ''){
                                            // validar que el numero de pago no exista en otra promocion
                                            $numero_pagos_existe = $promo_e['Nopago'];
                                            if($numero_pagos && !empty($numero_pagos)){
                                                foreach($numero_pagos as $np => $numero){
                                                    if(in_array($numero, $numero_pagos_existe)){
                                                        unset($numero_pagos[$np]);
                                                    }
                                                }
                                            }
                                        }
                                        if($numero_pagos !== false){
                                            $crear = true;
                                            $numero_pagos = array_values($numero_pagos);
                                        }
                                    }
                                }
                            }
                            if($crear){
                                if($numero_pagos && !empty($numero_pagos)){
                                    // crear promocion por numero de pago
                                    $tmp_pag = $numero_pagos;
                                    $numero_pagos = json_encode($numero_pagos);
                                    if($alumnogeneracion == 1){
                                        $id_of = isset($_POST['id_oferta']) ? $_POST['id_oferta'] : null;
                                        $carrera = $promociones->crearPromocionalumno_numeroPago($_POST['nombrepromocion'], $id_concepto, $_POST['selecpromobeca'], $_POST['listaralumnos'], $porcentaje, $_POST['creador_por'], $fechacreado, $estatus, $numero_pagos, $id_of);
                                        $info_concep = $pagosM->obtener_concepto($id_concepto);
                                        if(intval($info_concep['data']['id_generacion']) > 0){
                                            if($info_concep['data']['categoria'] == 'Mensualidad' && !empty($tmp_pag)){
                                                // verificar si no hay un pago aprobado por este mismo concepto
                                                $pagos_alumno_concepto = $pagosM->listar_pagos_anteriores($id_concepto, $_POST['listaralumnos']);
                                                $last_num_p = false;
                                                foreach($pagos_alumno_concepto['data'] as $pago){
                                                    if($last_num_p === false){
                                                        $last_num_p = $pago['numero_de_pago'];
                                                    }else{
                                                        if($last_num_p < $pago['numero_de_pago']){
                                                            $last_num_p = $pago['numero_de_pago'];
                                                        }
                                                    }
                                                }
                                                if($last_num_p !== false){
                                                    $last_num_p = intval($last_num_p) + 1;
                                                }else{
                                                    $last_num_p = 1;
                                                }
                                                if(in_array($last_num_p, $tmp_pag) && $porcentaje >= 100){
                                                    // registrar pago en 0 por promocion
                                                    $pag_js = json_encode($pagosM->formato_pago('BECA', '0', date("Y-m-d"), '', '', '', '', $info_concep['data']['categoria']));
                                                    $info_pago = $pagosM->obtener_pago_aplicar($_POST['listaralumnos'], $id_concepto, date("Y-m-d"));

                                                    $dia_regular = $pagosM->consultar_fecha_corte_mensualidad($_POST['listaralumnos'], $id_concepto);
                                                    if($dia_regular){
                                                        $info_pago['fecha_limite_pago'] = substr($info_pago['fecha_limite_pago'], 0, 8).$dia_regular;
                                                    }
                                                    $fecha_lim = date("Y-m-d", strtotime('+1 month', strtotime($info_pago['fecha_limite_pago'])));

                                                    $insert = [
                                                        'id_prospecto' => $_POST['listaralumnos'],  'id_concepto' => $id_concepto,
                                                        'detalle' => $pag_js,                       'montopagado' => 0,
                                                        'cargo_retardo' => 0,                       'restante' => 0,
                                                        'saldo' => 0,                               'costototal' => 0,
                                                        'numero_de_pago' => $last_num_p,            'fecha_limite_pago' => $fecha_lim,
                                                        'fechapago' => date("Y-m-d"),               'comprobante' => '',
                                                        'idPromocion' => $carrera['data'],          'estatus' => 'verificado',
                                                        'como_realizo_pago' => '',                  'metodo_de_pago' => '',
                                                        'banco_de_deposito' => '',                  'quien_registro' => $_POST['creador_por'],
                                                        'codigo_de_autorizacion' => '',             'referencia' => '',
                                                        'order_id' => '',                           'moneda' => null
                                                    ];

                                                    $pagosM->registrar_pago_mult($insert);
                                                }
                                            }
                                        }
                                    }else if($alumnogeneracion == 2){
                                        $carrera = $promociones->crearPromociongeneracion_numeroPago($_POST['nombrepromocion'], $id_concepto, $_POST['selecpromobeca'], $_POST['listargeneraciones'], $porcentaje, $_POST['creador_por'], $fechacreado, $estatus, $numero_pagos);
                                    }
                                }else if($confirm){ 
                                    if($alumnogeneracion == 1){
                                        $info_concep = $pagosM->obtener_concepto($id_concepto);
                                        if($info_concep['data']['categoria'] == 'Inscripción' && intval($porcentaje) >= 100 && intval($info_concep['data']['id_generacion']) > 0){
                                            // se asignara generacion en automatico
                                            $asignacion = $generacionM->buscarAsignacion($_POST['listaralumnos'], $info_concep['data']['id_generacion']);
                                            if(empty($asignacion['data'])){
                                                $generacionM->asignar_generacion_alumno($_POST['listaralumnos'], $info_concep['data']['id_generacion']);
                                                registrar_afiliado($_POST['listaralumnos']);
                                                
                                                require_once '../../functions/correos_prospectos.php';
                                                $obtenerdatosprospecto = $pagosM->obtener_datos_prospecto($_POST['listaralumnos']);
                                                $destinatarios = [[$obtenerdatosprospecto['data']['correo'], $obtenerdatosprospecto['data']['nombre_completo']]];

                                                $asunto = "Envío de accesos";
                                                $plantilla_c = 'carreras/nueva_plantilla_udc_accesos.html';
                                                $claves = ['%%prospecto', '%%USUARIO', '%%CONTRASENIA'];
                                                $contrasn = $pagosM->contrasenia_correo($obtenerdatosprospecto['data']['correo']);
                                                $valores = [
                                                    $obtenerdatosprospecto['data']['nombre_completo'], 
                                                    $obtenerdatosprospecto['data']['correo'], 
                                                    $contrasn !== false ? $contrasn['contrasenia'] : '12345'];
                                                
                                                $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                                                $enviar = enviar_correo_registro("Envío de manual de usuario", $destinatarios, 'carreras/nueva_plantilla_udc_manual.html', $claves, $valores, "Guia_alumnos.pdf");

                                            }
                                        }
                                        $id_of = isset($_POST['id_oferta']) ? $_POST['id_oferta'] : null;
                                        $carrera = $promociones->crearPromocionalumno($_POST['nombrepromocion'], $id_concepto, $_POST['selecpromobeca'], $_POST['listaralumnos'], $porcentaje, $_POST['creador_por'], $fechacreado, $estatus, $_POST['promofechainicial'], $_POST['promofechafinal'], $id_of);
                                    }else if($alumnogeneracion == 2){
                                        $carrera = $promociones->crearPromociongeneracion($_POST['nombrepromocion'], $id_concepto, $_POST['selecpromobeca'], $_POST['listargeneraciones'], $porcentaje, $_POST['creador_por'], $fechacreado, $estatus, $_POST['promofechainicial'], $_POST['promofechafinal']);
                                    }
                                }else if(empty($confirm)){
                                    $carrera = ['data'=>0, 'mensaje'=>'Ya existe una promocion para los numeros de pago seleccionados.'];
                                }
                            }else{
                                if($carrera === false){
                                    $carrera = ['data'=>0, 'mensaje'=>'No se pudo crear la promoción'];
                                }
                            }
                        }
                    }
                    $i++;
                }
                echo json_encode($carrera);
            }else{
                $conceptos_promo = [];
                foreach ($_POST as $key => $value) {
                    if(in_array($key, array_keys($key_promos))){
                        $porcentaje = floatval($_POST[$key]);
                        $id_concepto = $_POST[$key_promos[$key]];
                        if($porcentaje > 0){
                            $numero_pagos = false;
                            if($key == 'promomensualidades' && isset($_POST['multiple_mensualidades'])){
                                $numero_pagos = $_POST['multiple_mensualidades'];
                            }
                            $conceptos_promo[$key] = [
                                'tipo_concepto' => substr($key, 5),
                                'id_concepto'   => $id_concepto,
                                'generacion'    => $_POST['listargeneraciones'],
                                'porcentaje'    => $porcentaje,
                                'fechas'        => $numero_pagos === false ? [$_POST['promofechainicial'], $_POST['promofechafinal']] : false,
                                'numero_pagos'  => $numero_pagos === false ? false : json_encode($numero_pagos)
                            ];
                        }
                    }
                }

                $insert = [
                    'nombre'=> $_POST['nombrepromocion'],
                    'tipo'=> $_POST['selecpromobeca'],
                    'generacion'=> $_POST['listargeneraciones'],
                    'conceptos'=> json_encode($conceptos_promo),
                    'fecha_inicio'=> $_POST['promofechainicial'],
                    'fecha_final'=> $_POST['promofechafinal'],
                    'estatus'=> '1'
                ];
                echo json_encode($promociones->crear_oferta($insert));
            }
            break;
        case 'editarpromocion':
            $fechainicio1=$_POST['promofechainicialeditar'];
            $fechafin1=$_POST['promofechafinaleditar'];
            $fechainicio=strtotime(date($fechainicio1." 00:00:00"));    // <--
            $fechafin=strtotime(date($fechafin1." 23:59:59"));          // <--
            $fechahoy=strtotime(date("Y-m-d H:i:s"));
            // $estatus='inactivo';
            // if($fechainicio<$fechahoy && $fechafin>$fechahoy){
            //     $estatus='activo';
            // }
            $info_promo = $promociones->obtener_promocion($_POST['idpromocioneditar'])['data'];
            if($info_promo === false){
                echo json_encode(['data'=>0, 'mensaje'=>'No se encontro la promocion']);
            }
            $info_concepto = $pagosM->obtener_concepto($info_promo['id_concepto']);
            $porcentaje = floatval($_POST['promoreinscripcioneseditar']);
            // buscar otras promociones para validar que la fecha está disponible
            if($info_promo['id_prospecto'] !== null && $info_promo['id_prospecto'] > 0){ // si la promocion esta asignada a un alumno
                $otras_promos = $promociones->obtenerPromocion_concepto_alumno($info_promo['id_prospecto'], $info_promo['id_concepto']);
                $actualizar_a = true;
                foreach($otras_promos['data'] as $key_pr => $val_pr){
                    if($fechainicio <= strtotime($val_pr['fechafin']) && $_POST['idpromocioneditar'] != $val_pr['idPromocion']){
                        $actualizar_a = false;
                    }
                    if($fechafin >= strtotime($val_pr['fechainicio']) && $_POST['idpromocioneditar'] != $val_pr['idPromocion']){
                        $actualizar_a = false;
                    }
                }
                if(!$actualizar_a){
                    echo json_encode(['estatus'=>'error', 'mensaje'=>'El rango de fecha interfiere con otra promoción/beca.']);
                    die();
                }
            } 

            if($info_concepto['data']['categoria'] == 'Mensualidad'){
                if(isset($_POST['multiple_mensualidades_edit']) && !empty($_POST['multiple_mensualidades_edit'])){
                    $numero_pagos = $_POST['multiple_mensualidades_edit']; 
                    // verificar que no exista una promocion para los numeros de pago seleccionados
                    // $confirm = $promociones->verificarPromocionNumeroPago($_POST['idpromocioneditar'], $numero_pagos);
                    $promociones->actualizar_Nopago_pagos_promocion($_POST['idpromocioneditar'], json_encode($numero_pagos));
                    /** 
                     * Verificar si el siguiente pago de mensualidad es uno que tenga promocion al 100 por ciento
                     */
                    if(intval($_POST['promoreinscripcioneseditar']) >= 100 && intval($info_promo['id_prospecto']) > 0){
                        $tmp_pag = $numero_pagos;
                        $id_concepto = $info_promo['id_concepto'];
                        $pagos_alumno_concepto = $pagosM->listar_pagos_anteriores($id_concepto, $info_promo['id_prospecto']);
                        $last_num_p = false;
                        $moneda = null;
                        foreach($pagos_alumno_concepto['data'] as $pago){
                            if($last_num_p === false){
                                $last_num_p = $pago['numero_de_pago'];
                                $moneda = $pago['moneda'];
                            }else{
                                if($last_num_p < $pago['numero_de_pago']){
                                    $last_num_p = $pago['numero_de_pago'];
                                    $moneda = $pago['moneda'];
                                }
                            }
                        }

                        if($last_num_p !== false){
                            $last_num_p = intval($last_num_p) + 1;
                        }else{
                            $last_num_p = 1;
                        }

                        if(in_array($last_num_p, $tmp_pag) && $porcentaje >= 100){
                            // registrar pago en 0 por promocion
                            $pag_js = json_encode($pagosM->formato_pago('BECA', '0', date("Y-m-d"), '', '', '', '', $info_concepto['data']['categoria']));
                            $info_pago = $pagosM->obtener_pago_aplicar($info_promo['id_prospecto'], $id_concepto, date("Y-m-d"));

                            $dia_regular = $pagosM->consultar_fecha_corte_mensualidad($info_promo['id_prospecto'], $id_concepto);
                            if($dia_regular){
                                $info_pago['fecha_limite_pago'] = substr($info_pago['fecha_limite_pago'], 0, 8).$dia_regular;
                            }
                            $fecha_lim = date("Y-m-d", strtotime('+1 month', strtotime($info_pago['fecha_limite_pago'])));

                            $insert = [
                                'id_prospecto' => $info_promo['id_prospecto'],  'id_concepto' => $id_concepto,
                                'detalle' => $pag_js,                       'montopagado' => 0,
                                'cargo_retardo' => 0,                       'restante' => 0,
                                'saldo' => 0,                               'costototal' => 0,
                                'numero_de_pago' => $last_num_p,            'fecha_limite_pago' => $fecha_lim,
                                'fechapago' => date("Y-m-d"),               'comprobante' => '',
                                'idPromocion' => $_POST['idpromocioneditar'],          'estatus' => 'verificado',
                                'como_realizo_pago' => '',                  'metodo_de_pago' => '',
                                'banco_de_deposito' => '',                  'quien_registro' => $_POST['creador_por'],
                                'codigo_de_autorizacion' => '',             'referencia' => '',
                                'order_id' => '',                           'moneda' => $moneda
                            ];

                            $pagosM->registrar_pago_mult($insert);
                        }
                    }
                    
                    $fechainicio1 = null;
                    $fechafin1 = null;
                }else{
                    $promociones->actualizar_Nopago_pagos_promocion($_POST['idpromocioneditar'], null);
                }
            }else if($info_concepto['data']['categoria'] == 'Inscripción' && intval($info_concepto['data']['id_generacion']) > 0){
                if(intval($porcentaje) >= 100){
                    // se asignara generacion en automatico
                    $asignacion = $generacionM->buscarAsignacion($info_promo['id_prospecto'], $info_concep['data']['id_generacion']);
                    if(empty($asignacion['data'])){
                        $generacionM->asignar_generacion_alumno($info_promo['id_prospecto'], $info_concep['data']['id_generacion']);
                        registrar_afiliado($info_promo['id_prospecto']);

                        require_once '../../functions/correos_prospectos.php';
                        $obtenerdatosprospecto = $pagosM->obtener_datos_prospecto($_POST['listaralumnos']);
                        $destinatarios = [[$obtenerdatosprospecto['data']['correo'], $obtenerdatosprospecto['data']['nombre_completo']]];
                        
                        $asunto = "Envío de accesos";
                        $plantilla_c = 'carreras/nueva_plantilla_udc_accesos.html';
                        $claves = ['%%prospecto', '%%USUARIO', '%%CONTRASENIA'];
                        $contrasn = $pagosM->contrasenia_correo($obtenerdatosprospecto['data']['correo']);
                        $valores = [
                            $obtenerdatosprospecto['data']['nombre_completo'], 
                            $obtenerdatosprospecto['data']['correo'], 
                            $contrasn !== false ? $contrasn['contrasenia'] : '12345'];
                        
                        $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                        $enviar = enviar_correo_registro("Envío de manual de usuario", $destinatarios, 'carreras/nueva_plantilla_udc_manual.html', $claves, $valores, "Guia_alumnos.pdf");
                    }
                }
            }
            $fechaactualizado= date("Y-m-d H:i:s");
            $actualizarpromocion=$promociones->actualizarpromocion($_POST['idpromocioneditar'],$_POST['editarnombrepromocion'],$_POST['editarselecpromobeca'],$_POST['promoreinscripcioneseditar'],$_POST['creador_por'],$fechaactualizado, $fechainicio1, $fechafin1);
            $actualizarpromocion = $actualizarpromocion['data'];
            echo $actualizarpromocion;
            break;
        case 'obtenerPromociones':
            unset($_POST['action']);
            $csul = $promociones->obtenerPromociones();
            $data = Array();
            while($dato=$csul->fetchObject()){
                $fecha_hoy= date("Y-m-d");
                if (strtotime($dato->fechainicio) <= strtotime($fecha_hoy) && strtotime($dato->fechafin) >= strtotime($fecha_hoy)) {
                    $dato->estatus='activo';
                }
                $data[]=array(
                    0=> $dato->nombrePromocion,
                    1=> ($dato->tipo==1)? 'Promoción':'Beca',
                    2=> $dato->id_concepto,
                    3=> $dato->id_generacion,
                    4=> $dato->id_carrera,
                    5=> $dato->id_afiliado,
                    6=> $dato->porcentaje.'%',
                    7=> ($dato->estatus=='activo')?'Activo':'Inactivo',
                    8=> $dato->fechacreado,
                    9=>'<a class="btn btn-primary" data-toggle="modal" data-target="#modal-editar-promocion" onclick="editarpromocion('.$dato->idPromocion.')">Modificar</a> '
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
        case 'obtenerPromocion':
            unset($_POST['action']);
            $rspta=$promociones->obtenerPromocion($_POST);
            if($rspta['data']['Nopago'] === null){
                $rspta['data']['Nopago'] = [];
            }
            echo json_encode($rspta['data']);
            break;
        case 'obteneralumnos':
            unset($_POST['action']);
            $alumnos = $promociones->getAlumnos();
            $alumnos = $alumnos['data'];
            echo json_encode($alumnos);
            break;
        case 'obteneralumnoseditar':
            unset($_POST['action']);
            $alumnos = $promociones->getAlumnoseditar();
            $alumnos = $alumnos['data'];
            echo json_encode($alumnos);
            break;
        case 'obtenergeneraciones':
            unset($_POST['action']);
            $generaciones = $promociones->getGeneraciones();
            $generaciones = $generaciones['data'];
            echo json_encode($generaciones);
            break;
        case 'obtenergeneracioneseditar':
            unset($_POST['action']);
            $generaciones = $promociones->getGeneracioneseditar();
            $generaciones = $generaciones['data'];
            echo json_encode($generaciones);
            break;
        case 'obtenercarreras':
            unset($_POST['action']);
            $careras = $promociones->getCarreras();
            $careras = $careras['data'];
            echo json_encode($careras);
            break;
        case 'obtenercarreraseditar':
            unset($_POST['action']);
            $careras = $promociones->obtenercarreraseditar();
            $careras = $careras['data'];
            echo json_encode($careras);
            break;
        case 'obtenerconceptos':
            unset($_POST['action']);
            $conceptos = $promociones->getConceptos();
            $conceptos = $conceptos['data'];
            echo json_encode($conceptos);
            break;
        case 'obteneralumnoseditar':
            unset($_POST['action']);
            $alumnos = $promociones->getAlumnoseditar();
            $alumnos = $alumnos['data'];
            echo json_encode($alumnos);
            break;
        case 'obtenerconceptoseditar':
            unset($_POST['action']);
            $conceptos = $promociones->getConceptoseditar();
            $conceptos = $conceptos['data'];
            echo json_encode($conceptos);
            break;
        case 'obtenerconceptosgeneracion':
            unset($_POST['action']);
            $conceptos = $promociones->getConceptosGenracion($_POST['id_generacion']);
            
            $conceptos = $conceptos['data'];
            echo json_encode($conceptos);
            break;
        case 'activarpromocion':
            unset($_POST['action']);
            $rspta=$promociones->activarpromocion($_POST);
            echo $rspta['data'];
            break;
        case 'desactivarpromocion':
            unset($_POST['action']);
            $rspta=$promociones->desactivarpromocion($_POST);
            echo $rspta['data'];
            break;
        
        case 'cargar_ofertas':
            echo json_encode($promociones->consultar_ofertas());
            break;
        case 'consultar_promos_aplicadas_oferta':
            echo json_encode($promociones->consultar_promos_aplicadas_oferta($_POST['oferta']));
            break;
        default:
            # code...
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}

function registrar_afiliado($prospecto){
    require_once '../../Model/alumnos/alumnosInstitucionesModel.php';
    require_once '../../Model/prospectos/prospectosModel.php';
    require_once '../../Model/planpagos/vistasModel.php';

    $alumnM = new AccesosAlumnosInstituciones();
    $prospectoM = new Prospecto();
    $vistasM = new Vistas();

    $afiliado = $alumnM->buscar_alumno_afiliado($prospecto);

    $afiliado_alumno = $alumnM->buscar_alumno('',$prospecto);
    if($afiliado['data'] === false){
        // crear el registro del prospecto en la tabla de afiliados
        $info_p = $prospectoM->consultar_info_prospecto_a($prospecto);
        $d = [
            'prospecto' => $info_p['data']['id_prospecto'],
            'email' => $info_p['data']['email'],
            'celular' => $info_p['data']['celular'],
            'grado_academico' => $info_p['data']['grado_academico'],
            'pais_nacimiento' => $info_p['data']['nacionalidad'],
            'cedulap' => $info_p['data']['cedula']
        ];
        $afil = $alumnM->crear_registro_afiliado($d);
        $vistasM->registrar_vista_afiliado($info_p['data']['id_prospecto'], 2); // <-- habilitar vista de cursos
        $vistasM->registrar_vista_afiliado($info_p['data']['id_prospecto'], 7); // <-- habilitar vista de documentacion
    }
}
