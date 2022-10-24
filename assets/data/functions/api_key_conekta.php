<?php

if (isset($_POST['id_tipo_pago_concepto'])) {
    $id_concepto = $_POST['id_tipo_pago_concepto'];
}
if (isset($_POST['id_concepto'])) {
    $id_concepto = $_POST['id_concepto'];
}

$obtener_api_key = $pagosM->obtener_api_key($id_concepto)['data'];
if(!$obtener_api_key){
    $obtener_api_key = $pagosM->obtener_api_key_evento($id_concepto)['data'];
}
if ($obtener_api_key['idInstitucion']==20) {// LLAVES negocio UDC
    $obtener_modo = $pagosM->obtener_modo_udc()['data'];//obtener datos de la cuenta UDC
    if ($obtener_modo['modo']==1) {// modo producción|
        $api_key_privada = $obtener_modo['api_key_private_prod'];
    }
    if ($obtener_modo['modo']==2) {
        $api_key_privada = $obtener_modo['api_key_private_test'];
    }
}
if ($obtener_api_key['idInstitucion']==13) {// LLAVES negocio CONACON
    $obtener_modo = $pagosM->obtener_modo_conacon()['data'];//obtener datos de la cuenta UDC
    if ($obtener_modo['modo']==1) {// modo producción|
        $api_key_privada = $obtener_modo['api_key_private_prod'];
    }
    if ($obtener_modo['modo']==2) {
        $api_key_privada = $obtener_modo['api_key_private_test'];
    }
}

\Conekta\Conekta::setApiKey($api_key_privada);//llave privada de pruebas key_DZoswwFK4NKAxrLRBQXFBQ   llave privada de produccion key_ysGFJo2wRgFgfnAkXqiUsg
\Conekta\Conekta::setLocale('es');
