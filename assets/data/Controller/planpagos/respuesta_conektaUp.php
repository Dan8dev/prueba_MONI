<?php
$body = @file_get_contents('php://input');
$data = json_decode($body);
http_response_code(200); // Return 200 OK 
/**
 * 
 */
// echo json_encode($data->object);
// die();
/** */
$data = (object) ['data' => $data];
require_once '../../functions/correos_prospectos.php';

#ob_start();
//var_dump($data);
#$debug_dump = ob_get_clean();

if(isset($data->data->object->line_items->data[0]->metadata)){
    $id_pr = $data->data->object->line_items->data[0]->metadata->id_prospecto;
    $id_conc = $data->data->object->line_items->data[0]->metadata->ref_instituto;
    if($id_conc == 31){
        $id_conc = 'IESM';
    }else if($id_conc == 24){
        $id_conc = 'CONACON';
    }else{
        $id_conc = 'UDC';
    }
    if(($id_pr == 'pago_link_cobranza' || $id_pr == 'spei_link_cobranza') && $data->data->object->payment_status == 'paid'){
        require_once '../../Model/conexion/conexion.php';
        require_once '../../Model/planpagos/cajaModel.php';
        $cajaM = new Caja();
        $tipo = $data->data->object->charges->data[0]->payment_method->type;

        $fecha_unix=$data->data->object->charges->data[0]->paid_at;
        $fechapago=date('Y-m-d H:i:s', $fecha_unix);

        $insert = [
            "inp_cliente" => $data->data->object->customer_info->name,
            "instituto"    => $id_conc,
            "inp_concepto" => $data->data->object->line_items->data[0]->name,
            "inp_monto"    => $data->data->object->amount/100,
            "usuario"      => null,
            "moneda"       => $data->data->object->currency,
            "fecha_conekta"       => $fechapago,
            "inp_comentario"=>(($id_pr == 'spei_link_cobranza') ? 'Pago procesado por link de pago, SPEI' : 'Pago procesado por link de pago'),
            "tipo" => ($id_pr == 'spei_link_cobranza') ? 'SPEI' : (($tipo == 'credit') ? 'CREDITO' : 'DEBITO')
        ];
        echo json_encode($cajaM->registrar_a_caja($insert));
        die();
    }
}

if ($data->data->object->charges->data[0]->payment_method->type=='oxxo' && $data->data->object->charges->data[0]->status=='paid') {//pagos con oxxo
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/pagosModel.php';
    require_once '../../Model/planpagos/promocionesModel.php';
    require_once '../../Model/alumnos/alumnosInstitucionesModel.php';
    require_once '../../Model/prospectos/prospectosModel.php';
    require_once '../../Model/planpagos/generacionesModel.php';
    

    $pagosM = new pagosModel();
    $promocionesM = new Promociones();
    $alumnM = new AccesosAlumnosInstituciones();
    $prospectoM = new Prospecto();
    $generacionesM = new Generaciones();
    
    $id_prospecto=$data->data->object->line_items->data[0]->metadata->id_prospecto;
    $obteneralumno=$pagosM->obtener_datos_prospecto($id_prospecto);
                $email_cliente=$obteneralumno['data']['correo'];
                $nombre_alumno=$obteneralumno['data']['nombre'];
                $apaterno=$obteneralumno['data']['aPaterno'];
                $amaterno=$obteneralumno['data']['aMaterno'];
                $descripcion_concepto=$data->data->object->line_items->data[0]->name;
                $fecha_unix=$data->data->object->charges->data[0]->paid_at;
                $fechapago=date('Y-m-d H:i:s', $fecha_unix);
                $monto_pagado=$data->data->object->amount/100;
                $monto_pagado_correo=$monto_pagado;

    $detalle = json_encode($pagosM->formato_pago('Oxxo', $monto_pagado, $fechapago, $nombre_alumno, $apaterno, $amaterno, $email_cliente, $descripcion_concepto));
    $id_concepto=$data->data->object->line_items->data[0]->metadata->id_concepto;

    $info_pago = $pagosM->obtener_pago_aplicar($id_prospecto, $id_concepto, $fechapago);
	file_put_contents("text_chuy.txt", json_encode($info_pago));
    $info_concepto = $pagosM->obtener_concepto($id_concepto)['data'];
    $referencia=$data->data->object->charges->data[0]->payment_method->reference;
    $currency='mxn';
    $info_alumn = $pagosM->consultar_info_prospecto_a($id_prospecto)['data'];
    if ($info_alumn['tipoPago']==2) {//si el alumno tienepago en dolares se reeemplaza el precio por el monto en dolares
        $info_concepto['precio'] = $info_concepto['precio_usd'];
        $currency='usd';
      }

    $insertar = [
        'quien_registro'=> $id_prospecto,
        'id_prospecto' => $id_prospecto,
        'id_concepto'   => $id_concepto,
        'detalle'       => $detalle,
        'montopagado'   => '0',
        'cargo_retardo' => '0',
        'restante'      => '0',
        'saldo'         => '0',
        'costototal'    => ($info_concepto['precio'] - $info_pago['monto_promocion']),
        'numero_de_pago'=> $info_pago['numero_de_pago'],
        'fecha_limite_pago'=> '',
        'idPromocion' => (isset($info_pago['id_promocion'])?$info_pago['id_promocion']:null),
        'fechapago'     => $fechapago,
        'comprobante'   => '',
        'metodo_de_pago'=> 'Pago en efectivo',
        'banco_de_deposito'=> null,
        'estatus'       => 'verificado',
        'codigo_de_autorizacion'=>null,
        'referencia'    => $referencia,
        'order_id'      => $data->data->object->charges->data[0]->order_id,
        'moneda'        => $currency
    ];

    if($monto_pagado >= ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo'])){
        // se está cubriendo el monto del pago requerido
        $insertar['montopagado'] = $monto_pagado - $info_pago['monto_retardo'];
        $insertar['cargo_retardo'] = $info_pago['monto_retardo'];
        $insertar['restante'] = ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo']) - $monto_pagado;
        //$insertar['fecha_limite_pago'] = date("Y-m-d", strtotime('+1 month', strtotime($info_pago['fecha_limite_pago'])));
        if($info_concepto['numero_pagos'] > 1){
            $dia_regular = $pagosM->consultar_fecha_corte_mensualidad($id_prospecto, $id_concepto);
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
        $insertar['fecha_limite_pago'] = $info_pago['fecha_limite_pago'];
    }
    if ($info_concepto['categoria']=='Reinscripción') {
        $obtenerfechalimitedepago = $pagosM->obtenerfechalimitedepago($info_concepto['id_generacion'], $info_pago['numero_de_pago']+2);
        if ($obtenerfechalimitedepago['data']) {
            $insertar['fecha_limite_pago'] = $obtenerfechalimitedepago['data']['fecha_inicio'];
        }
    }

    /*validar si es el primer pago verificado*/
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
        if(!$validar_si_generacion_ya_esta_asignada){
            $asignar_generacion= $pagosM->asignar_generacion_alumno($id_prospecto,$info_concepto['id_generacion'])['data'];
        }
        /*si no existe el registro en tabla afiliados registrar para que pueda ingresar*/
        $afiliado = $alumnM->buscar_alumno_afiliado($id_prospecto);
        if (!$afiliado['data']) {
            // crear el registro del prospecto en la tabla de afiliados
            $info_p = $prospectoM->consultar_info_prospecto_a($id_prospecto);
            $d = [
                'prospecto' => $info_p['data']['id_prospecto'],
                'email' => $info_p['data']['email'],
                'celular' => $info_p['data']['celular']
            ];
            $afil = $alumnM->crear_registro_afiliado($d);
            if($afil['estatus'] == 'ok'){
                // crear el registro de asignacion de la institucion
                $d = [
                    "prospecto"=>$info_alumn['id_prospecto'],
                    "institucion"=>$carrera_inf['data']['idInstitucion']
                ];
                $nueva_asign = $alumnM->crear_registro_institucion($d);
            }
        }
        /*si no existe el registro en tabla afiliados registrar para que pueda ingresar*/
        // verificar si ya existe la generacion asig si no la tiene se asigna
        if ($asignar_generacion) {
            require_once 'sendPost.php';
            sendPost($id_prospecto, $generacionesM->buscarCarrerasG($info_concepto['id_generacion'])['data'][0]['idCarrera']);
            $validar_carrera= $pagosM->validar_carrera($info_concepto['id_generacion'])['data'];
            /*si la generacion asignada pertenece a la carrera tsu consejería enviar conrreo de bienvenida con formatos de inscripcion*/
            if ($validar_carrera['idCarrera']==13) {//si es de carrera tsu enviar correo de bienvenida, si se necesita enviar correo de bienvenida de mas carreras comparar con su correspondiente id
            $asunto = "El Departamento de Control Escolar le da la bienvenida";
            $destinatarios = [[$email_cliente, $nombre_alumno]];
            $plantilla_c = 'plantilla_tsu_control_escolar.html';
            $claves = ['%%prospecto','%%secuencia_generacion'];
            $valores = [$nombre_alumno.' '.$apaterno.' '.$amaterno, $validar_carrera['secuencia_generacion']];
            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
            }
            /*si la generacion asignada pertenece a la carrera tsu consejería enviar conrreo de bienvenida con formatos de inscripcion*/
        }
        
    }

    /*validar si es el primer pago verificado*/

    /*enviar correo de comprobante de pago oxxo*/
    $tipo_moneda=$data->data->object->charges->data[0]->currency;
    $asunto = "NOTIFICACIÓN DE PAGO UNIVERSIDAD DEL CONDE";
    $destinatarios = [[$email_cliente, $nombre_alumno]];
    // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
    $plantilla_c = 'plantilla_confirmacion_pago_conekta.html';
    $claves = ['%%prospecto','%%monto_pagado','%%tipo_moneda'];
    $valores = [$nombre_alumno.' '.$apaterno.' '.$amaterno, $monto_pagado_correo,$tipo_moneda];
    $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
    /*enviar correo de comprobante de pago oxxo*/


    $insert = $pagosM->registrar_pago_mult($insertar);
    echo json_encode($insert);

}
if ($data->data->object->charges->data[0]->payment_method->type=='spei' && $data->data->object->charges->data[0]->status=='paid') {//si el pago es de spei                          
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/pagosModel.php';
    require_once '../../Model/planpagos/promocionesModel.php';

    // require_once '../../functions/correos_prospectos.php';

    $pagosM = new pagosModel();
    $promocionesM = new Promociones();
    $id_prospecto = $data->data->object->line_items->data[0]->metadata->id_prospecto;
    $obteneralumno= $pagosM->obtener_datos_prospecto($id_prospecto);
                $email_cliente=$obteneralumno['data']['correo'];
                $nombre_alumno=$obteneralumno['data']['nombre'];
                $apaterno=$obteneralumno['data']['aPaterno'];
                $amaterno=$obteneralumno['data']['aMaterno'];
                $descripcion_concepto=$data->data->object->line_items->data[0]->name;
                $fecha_unix=$data->data->object->charges->data[0]->paid_at;
                $fechapago=date('Y-m-d H:i:s', $fecha_unix);
                $monto_pagado=$data->data->object->amount/100;
                $monto_pagado_correo=$monto_pagado;

    $id_concepto=$data->data->object->line_items->data[0]->metadata->id_concepto;

    $info_pago = $pagosM->obtener_pago_aplicar($id_prospecto, $id_concepto, $fechapago);
    $info_concepto = $pagosM->obtener_concepto($id_concepto)['data'];
    $currency='mxn';
    $info_alumn = $pagosM->consultar_info_prospecto_a($id_prospecto)['data'];
    if ($info_alumn['tipoPago']==2) {//si el alumno tienepago en dolares se reeemplaza el precio por el monto en dolares
        $info_concepto['precio'] = $info_concepto['precio_usd'];
        $currency='usd';
      }

    $referencia='646180111812345678';
    $detalle = json_encode($pagosM->formato_pago('Spei conekta', $monto_pagado, $fechapago, $nombre_alumno, $apaterno, $amaterno, $email_cliente, $descripcion_concepto));

    $insertar = [
        'quien_registro'=> $id_prospecto,
        'id_prospecto' => $id_prospecto,
        'id_concepto'   => $id_concepto,
        'detalle'       => $detalle,
        'montopagado'   => '0',
        'cargo_retardo' => '0',
        'restante'      => '0',
        'saldo'         => '0',
        'costototal'    => ($info_concepto['precio'] - $info_pago['monto_promocion']),
        'numero_de_pago'=> $info_pago['numero_de_pago'],
        'fecha_limite_pago'=> '',
        'idPromocion' => (isset($info_pago['id_promocion'])?$info_pago['id_promocion']:null),
        'fechapago'     => $fechapago,
        'comprobante'   => '',
        'metodo_de_pago'=> 'Transferencia',
        'banco_de_deposito'=> null,
        'estatus'       => 'verificado',
        'codigo_de_autorizacion'=>null,
        'referencia'    => $referencia,
        'order_id'      => $data->data->object->charges->data[0]->order_id,
        'moneda'        => $currency
    ];

    if($monto_pagado >= ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo'])){
        // se está cubriendo el monto del pago requerido
        $insertar['montopagado'] = $monto_pagado - $info_pago['monto_retardo'];
        $insertar['cargo_retardo'] = $info_pago['monto_retardo'];
        $insertar['restante'] = ($info_pago['monto_por_pagar'] + $info_pago['monto_retardo']) - $monto_pagado;
        //$insertar['fecha_limite_pago'] = date("Y-m-d", strtotime('+1 month', strtotime($info_pago['fecha_limite_pago'])));
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
        $insertar['fecha_limite_pago'] = $info_pago['fecha_limite_pago'];
    }
    if ($info_concepto['categoria']=='Reinscripción') {
        $obtenerfechalimitedepago = $pagosM->obtenerfechalimitedepago($info_concepto['id_generacion'], $info_pago['numero_de_pago']+2);
        if ($obtenerfechalimitedepago['data']) {
            $insertar['fecha_limite_pago'] = $obtenerfechalimitedepago['data']['fecha_inicio'];
        }
    }
    $insert = $pagosM->registrar_pago_mult($insertar);

    echo json_encode($insert);

    $tipo_moneda=$data->data->object->charges->data[0]->currency;
    $asunto = "NOTIFICACIÓN DE PAGO UNIVERSIDAD DEL CONDE";
    $destinatarios = [[$email_cliente, $nombre_alumno]];
    // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
    $plantilla_c = 'plantilla_confirmacion_pago_spei.html';
    $claves = ['%%prospecto','%%monto_pagado','%%tipo_moneda'];
    $valores = [$nombre_alumno.' '.$apaterno.' '.$amaterno, $monto_pagado_correo,$tipo_moneda];
    $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
}
if ($data->data->object->charges->data[0]->payment_method->object=='card_payment' && $data->data->object->charges->data[0]->status=='paid') {//si el pago es con tarjeta
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/pagosModel.php';
    require_once '../../Model/planpagos/promocionesModel.php';

    //require_once '../../functions/correos_prospectos.php';

    $pagosM = new pagosModel();
    $promocionesM = new Promociones();
    $domiciliar_pago='';
    if ($data->data->object->line_items->has_more===null) {//evento domiciliar pago
        $insertar_codigo_auth=$pagosM->insertar_codigo_auth($data->data->object->charges->data[0]->order_id,$data->data->object->charges->data[0]->payment_method->auth_code);
        $obteneralumno=$pagosM->obtener_datos_prospecto_by_order_id($data->data->object->charges->data[0]->order_id);
 		$email_cliente=$obteneralumno['data']['correo'];
        $nombre_alumno=$obteneralumno['data']['nombre'];
        $apaterno=$obteneralumno['data']['aPaterno'];
        $amaterno=$obteneralumno['data']['aMaterno'];
        $domiciliar_pago=' y <b> domiciliado </b> con éxito';
    }

    if ($data->data->object->line_items->has_more===false) {//evento pago en una sola exhibicion
    $id_prospecto=$data->data->object->line_items->data[0]->metadata->id_prospecto;
    $obteneralumno=$pagosM->obtener_datos_prospecto($id_prospecto);
                $email_cliente=$obteneralumno['data']['correo'];
                $nombre_alumno=$obteneralumno['data']['nombre'];
                $apaterno=$obteneralumno['data']['aPaterno'];
                $amaterno=$obteneralumno['data']['aMaterno'];
    }

                $descripcion_concepto=$data->data->object->line_items->data[0]->name;
                $fecha_unix=$data->data->object->charges->data[0]->paid_at;
                $fechapago=date('Y-m-d H:i:s', $fecha_unix);
                $monto_pagado=$data->data->object->amount/100;
                $monto_pagado_correo=$monto_pagado;
                $tipo_moneda=$data->data->object->currency;

    $asunto = "NOTIFICACIÓN DE PAGO UNIVERSIDAD DEL CONDE";
    $destinatarios = [[$email_cliente, $nombre_alumno]];
    // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
    $plantilla_c = 'plantilla_confirmacion_pago_conekta_card.html';
    $claves = ['%%prospecto','%%monto_pagado','%%tipo_moneda','%%domiciliar_pago'];
    $valores = [$nombre_alumno.' '.$apaterno.' '.$amaterno, $monto_pagado_correo,$tipo_moneda, $domiciliar_pago];
    $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
    echo json_encode([
		'hasmore' => $data->data->object->line_items->has_more,
		'alumno'		=> $obteneralumno,
        'email_enviar' => $destinatarios,
        'valores' => $valores,
        'monto_pagado' => $monto_pagado,
        'moneda' => $tipo_moneda
    ]);
}
    
  
