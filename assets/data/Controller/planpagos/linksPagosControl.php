<?php
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/pagosModel.php';
    // require_once '../../Model/planpagos/linkPagoModel.php';
	// $linkM = new linkPagoModel();
	$pagosM = new pagosModel();

    $accion = isset($_POST["action"]) ? $_POST["action"] : 'no action';

    switch ($accion) {
    	case 'realizar_pago_link':
            require_once(__DIR__."/../../functions/conekta-php/lib/Conekta.php");
            require_once("../../functions/api_key_conekta.php");
            require_once '../../functions/correos_prospectos.php';
            $token=$_POST['token'];
            $nombre_cliente = $_POST['nombretarjeta'];
            $nombre_cliente_real = $_POST['nombre_cliente'];
            $email = $_POST['email'];
            $telefono = $_POST['telefonofactura'];
            $montopagado = $_POST['totalapagar'];
            $nombre_concepto = $_POST['nombre_concepto'];
            $udc_o_conacon = $_POST['id_concepto'];
            $currency_t = strtoupper($_POST['tipo_moneda']) == 'USD' ? 'USD' : 'MXN';
            $errors = '';

            try {
                $customer = \Conekta\Customer::create(
                    array(
                    "name" => $nombre_cliente_real,
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
                            "id_prospecto" => 'pago_link_cobranza',
                            "ref_instituto" => $udc_o_conacon
                            )
                        )//first line_item
                    ), //line_items
                    "currency" => $currency_t,
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
            } 
            catch (\Conekta\ProcessingError $error)			{ $errors.= $error->getMessage(); } 
            catch (\Conekta\ParameterValidationError $error){ $errors.= $error->getMessage(); } 
            catch (\Conekta\Handler $error)					{ $errors.= $error->getMessage(); }

                $respuesta = ['estatus' => 2,
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
                    $nombre_negocio = '';
                    if($udc_o_conacon == 24){
                        $nombre_negocio = 'COLEGIO NACIONAL DE CONSEJEROS';
                    }else if($udc_o_conacon==31){
                        $nombre_negocio = 'INSTITUTO DE ESTUDIOS SUPERIORES';
                    }else{
                        $nombre_negocio = 'UNIVERSIDAD DEL CONDE';
                    }
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
                    $tmp = '';
                    if($udc_o_conacon == 24){
                        $tmp = 'logoT.png';
                    }else if($udc_o_conacon==31){
                        $tmp = 'pie-blue-i.jpg';
                    }else{
                        $tmp = 'footer_confirmacion_de_pago_red.jpg';
                    }
                    $asunto = "NOTIFICACIÓN DE PAGO";
                    $destinatarios = [[$email, $nombre_cliente]];
                    // $destinatarios = [['pajaro.octavio96@gmail.com', $resp['persona']['nombre']]];
                    $plantilla_c = 'plantilla_confirmacion_pago_conekta_card_link.html';
                    $claves = ['%%prospecto','%%monto_pagado','%%tipo_moneda','%%nombre_concepto','%%email','%%telefono','%%referencia','%%autorizacion','%%orden','%%udc_o_conacon'];
                    $valores = [$nombre_cliente, number_format($order->amount/100),$order->currency,$nombre_concepto,$email,$telefono,$order->charges[0]->id,$order->charges[0]->payment_method->auth_code,$order->id,$tmp];
                    $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                }
                echo json_encode($respuesta);
            break;
    	case 'variable':
    		// code...
    		break;
    	case 'no action':
    		echo 'no action';
    		break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}

?>
