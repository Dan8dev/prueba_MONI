<?php 
session_start();
if(isset($_SESSION["alumno_general"])){
date_default_timezone_set("America/Mexico_City");

require "../Model/AfiliadosModel.php";

$afiliados = new Afiliados();

$accion = @$_POST["action"];

$nombre = @$_POST["nombre"];
$apaterno = @$_POST["apaterno"];
$amaterno = @$_POST["amaterno"];
$fnacimiento = @$_POST["fnacimiento"];
$curp = @$_POST["curp"];

$pais = @$_POST["pais"];
$estado = @$_POST["estado"];
$ciudad = @$_POST["ciudad"];
$colonia = @$_POST["colonia"];
$calle = @$_POST["calle"];
$codigopostal = @$_POST["codigopostal"];
$email = @$_POST["email"];
$celular = @$_POST["celular"];
$facebook = @$_POST["facebook"];
$instagram = @$_POST["instagram"];
$twitter = @$_POST["twitter"];

$gradoestudios = @$_POST["gradoestudios"];
$cedulap = @$_POST["cedulap"];
// agregado
$tipoLicenciatura = @$_POST['tipoLicen'];
//agregado
$idusuario=$_SESSION["alumno_general"]['id_afiliado'];

switch ($accion) {
    case 'datospersonales':
        $rspta=$afiliados->datospersonales($fnacimiento, $curp, $idusuario);//guardar en tabla a_pospectos
        $rspta2=$afiliados->datospersonalesap($nombre, $apaterno, $amaterno, $idusuario); //guardar en tabla perfil_conacon
        break;
    case 'contacto':
        $rspta=$afiliados->contacto($pais, $estado, $ciudad, $colonia, $calle,$codigopostal, $email, $celular, $facebook, $instagram,$twitter, $idusuario);
        $rspta2=$afiliados->contactoap($email,$idusuario);
        break;
    case 'academico':
        $rspta=$afiliados->academico($gradoestudios, $cedulap,$idusuario, $tipoLicenciatura);
        break;

    case 'subirfoto':
        if (isset($_FILES["file"])){
                $file = $_FILES["file"];
                $name = $file["name"];
                $type = $file["type"];
                $tmp_n = $file["tmp_name"];
                $size = $file["size"];
                $folder = "../../img/afiliados/";
                
                if ($type != 'image/jpg' && $type != 'image/jpeg' && $type != 'image/png' && $type != 'image/gif')
                {
                echo "Error, el archivo no es una imagen"; 
                }
                else
                {
                    $src = $folder.$name;
					
                move_uploaded_file($tmp_n, $src);
                $rspta=$afiliados->editarfoto($name, $idusuario);  
                }
            }
			echo $name;
        break;
        case 'pagosemestral':
            $rspta1=$afiliados->obtenerusuario($idusuario);
            $fechaactivacion = date("Y-m-d H:i:s");
            $fecha=$rspta1['data']['finmembresia'];
            $finmembresia = date("Y-m-d H:i:s",strtotime($fecha."+ 6 month"));
            $rspta=$afiliados->pagosemestral(2, $idusuario,$finmembresia,$fechaactivacion);
            break;
        case 'pagoanual':
            $rspta1=$afiliados->obtenerusuario($idusuario);
            $fechaactivacion = date("Y-m-d H:i:s");
            $fecha=$rspta1['data']['finmembresia'];
            $finmembresia = date("Y-m-d H:i:s",strtotime($fecha."+ 1 year"));
            $rspta=$afiliados->pagoanual(1, $idusuario,$finmembresia,$fechaactivacion);
            break;
        case 'obtenerdatosperfil':
            $rspta2=$afiliados->obtenerusuario($idusuario);
            echo json_encode($rspta2["data"]);
            break;
        case 'obtenerpais':
            $rspta3=$afiliados->obtenerpaises();
            var_dump($rspta3);
            break;
        case 'obtenerestado':
            $idpais=@$_POST['idpais'];
            $rspta4=$afiliados->obtenerestados($idpais);
            echo json_encode($rspta4['data']);
            break;
        case 'talleres_eventos':
            $talleres = $afiliados->talleres_eventos($_POST['evento']);
            $cont = 0;
            foreach ($talleres['data'] as $taller) {
                if(intval($taller['ocupados']) >= intval($taller['cupo'])){
                    unset($talleres['data'][$cont]);
                }
                    $cont++;
            }

            $talleres['data'] = array_values($talleres['data']);
            echo json_encode($talleres);
            break;
        case 'registrar_pago':

            $evento = (isset($_POST['evento'])) ? $_POST['evento'] : null ;
            $persona = (isset($_POST['persona'])) ? $_POST['persona'] : null ;
            $detalle = (isset($_POST['detalle'])) ? $_POST['detalle'] : null ;
            $plan_pago = (isset($_POST['plan_pago'])) ? $_POST['plan_pago'] : null ;

                if($evento !== null && $persona !== null && $detalle !== null && $plan_pago !== null){
                    $resp = $afiliados->registrarPagoEvento($evento, $persona, $detalle, $plan_pago);
                    $id_concepto=$afiliados->id_concepto($plan_pago);
                    $resp2 = $afiliados->registrarPagoapagos($evento, $persona, $detalle, $id_concepto['data']['id_concepto']);
                    $detalleg='{"id":"00","intent":"CAPTURE","status":"COMPLETED","purchase_units":[{"reference_id":"default","amount":{"currency_code":"MXN","value":"00"},"payee":{"email_address":"pagos@universidaddelconde.edu.mx","merchant_id":"AZUHGK3DWV9NC"},"description":"Membresia-gratis","soft_descriptor":"PAYPAL *UNIVERSIDAD","shipping":{"name":{"full_name":"- - -"},"address":{"address_line_1":"","address_line_2":"","admin_area_2":"","admin_area_1":"","postal_code":"","country_code":"MX"}},"payments":{"captures":[{"id":"00","status":"COMPLETED","amount":{"currency_code":"MXN","value":"00"},"final_capture":true,"seller_protection":{"status":"ELIGIBLE","dispute_categories":["ITEM_NOT_RECEIVED","UNAUTHORIZED_TRANSACTION"]},"create_time":"0000-00-00T16:36:52Z","update_time":"0000-00-00T16:36:52Z"}]}}],"payer":{"name":{"given_name":"-","surname":"-"},"email_address":"-","payer_id":"-","address":{"country_code":"MX"}},"create_time":"0000-00-00T16:36:52Z","update_time":"0000-00-00T16:36:52Z"}';
                    if ($id_concepto['data']['id_concepto']==5) {
                        $fecha= date('Y-m-d H:i:s');
                        $finmembresia = date("Y-m-d H:i:s",strtotime($fecha."+ 1 year"));
                        $resp3 = $afiliados->asignarmembresiagratis($evento, $persona, $detalleg, 7,$finmembresia);
                    }
                    if ($id_concepto['data']['id_concepto']==1) {
                        $fecha= date('Y-m-d H:i:s');
                        $finmembresia = date("Y-m-d H:i:s",strtotime($fecha."+ 1 year"));
                        $resp3 = $afiliados->asignarmembresiagratis($evento, $persona, $detalleg, 6,$finmembresia);
                    }
                    if ($id_concepto['data']['id_concepto']==11) {
                        $fecha= date('Y-m-d H:i:s');
                        $finmembresia = date("Y-m-d H:i:s",strtotime($fecha."+ 1 year"));
                        $resp3 = $afiliados->asignarmembresiagratis($evento, $persona, $detalleg, 12,$finmembresia);
                    }
                }else{
                        $resp = ["estatus"=>"error"];
                    }

                echo json_encode($resp);
                // echo json_encode([$evento, $persona, $detalle, $plan_pago]);
            break;
        case 'registrar_pago_afiliacion':
            $detalle = (isset($_POST['detalle'])) ? $_POST['detalle'] : null ;
            $plan_pago = (isset($_POST['plan_pago'])) ? $_POST['plan_pago'] : null ;
            $id_prospecto= $afiliados->obtenerusuario($idusuario);
            $id_prospecto=$id_prospecto['data']['idAsistente'];
            $id_concepto=$afiliados->id_concepto($plan_pago);
            if ($id_concepto['data']['id_concepto']==3) {
                $fechafinmembresia=$afiliados->fechafinmembresia($id_prospecto);
                $fecha= $fechafinmembresia['data']['finmembresia'];
                $finmembresia = date("Y-m-d H:i:s",strtotime($fecha."+ 6 month"));
                $resgistrarpagosemestral=$afiliados->resgistrarpagosemestral($id_prospecto ,3 ,$detalle,$finmembresia);

            }
            if ($id_concepto['data']['id_concepto']==4) {
                $fechafinmembresia=$afiliados->fechafinmembresia($id_prospecto);
                $fecha= $fechafinmembresia['data']['finmembresia'];
                $finmembresia = date("Y-m-d H:i:s",strtotime($fecha."+ 1 year"));
                $resgistrarpagosemestral=$afiliados->resgistrarpagosemestral($id_prospecto ,4 ,$detalle,$finmembresia);

            }
            break;
        case 'apartar_talleres':
            unset($_POST['action']);
            $fecha = date("Y-m-d H:i:s");
            $error = [];
            $inserted = [];
            foreach($_POST as $key => $val){
                if(substr($key, 0, 4) == "chk_"){
                    $info = [
                        'prospecto'=>$_POST['persona'],
                        'taller'=>substr($key, 4),
                        'fecha'=>$fecha
                    ];
                    $apartar = $afiliados->apartar_talleres($info);
                    if($apartar['estatus'] == 'error'){
                        array_push($error, $apartar);
                    }else{
                        array_push($inserted, $apartar);
                    }
                }
            }
                if(empty($error)){
                    $resp = ['estatus'=>'ok', 'insertados'=>$inserted];
                }else{
                    $resp = ['estatus'=>'error', 'fallos'=>$error];
                }
            echo json_encode($resp);
            break;
	case 'guardar_laboral':
        $_POST['afiliado'] = $idusuario;
        unset($_POST['action']);
        echo json_encode($afiliados->registrar_exp_laboral($_POST));
        break;
    case 'consultar_exp':
        unset($_POST['action']);
        if(!isset($_POST['afiliado'])){
            $_POST['afiliado'] = $idusuario;
        }

        echo json_encode($afiliados->consultar_exp_laboral($_POST));
        break;
    case 'guardar_conocimiento':
        $_POST['afiliado'] = $idusuario;
        unset($_POST['action']);
        echo json_encode($afiliados->registrar_conocimiento($_POST));
        break;
    case 'guardar_grado':
        $_POST['afiliado'] = $idusuario;
        unset($_POST['action']);
        echo json_encode($afiliados->registrar_grado($_POST));
        break;
    case 'consultar_conocimiento':
        unset($_POST['action']);
        if(!isset($_POST['afiliado'])){
            $_POST['afiliado'] = $idusuario;
        }

        echo json_encode($afiliados->consultar_conocimiento($_POST));
        break;
    case 'consultar_grado':
        unset($_POST['action']);
        if(!isset($_POST['afiliado'])){
            $_POST['afiliado'] = $idusuario;
        }

        echo json_encode($afiliados->consultar_grado($_POST));
        break;
    case 'actualizar_info_laboral':
        unset($_POST['action']);
        echo json_encode($afiliados->actualizar_info_laboral($_POST));
        break;
    case 'eliminar_reg':
        unset($_POST['action']);
        if($_POST['tipo'] == 'exp_lab'){
            unset($_POST['tipo']);
            echo json_encode($afiliados->eliminar_laboral($_POST));
        }elseif($_POST['tipo'] == 'conocimiento'){
            unset($_POST['tipo']);
            echo json_encode($afiliados->eliminar_conocimiento($_POST));
        }
        elseif($_POST['tipo'] == 'grado'){
            unset($_POST['tipo']);
            echo json_encode($afiliados->eliminar_grado($_POST));
        }
        break;
    case 'actualizar_info_conocimiento':
        unset($_POST['action']);
        echo json_encode($afiliados->actualizar_info_conocimiento($_POST));
        break;
    case 'actualizar_info_grado':
        unset($_POST['action']);
        echo json_encode($afiliados->actualizar_info_grado($_POST));
        break;
    case 'contacto_persona':
        if(isset($_POST['email']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['destino']) && isset($_POST['subject'])){
            require "../../../../assets/data/functions/mailer.php";
			
			$resp = [];
            $persona = $afiliados->obtenerusuario($_POST['destino']);
            if($persona['data']){
                $message = file_get_contents("../../../../assets/plantillas/notificacion_busqueda.html");
                $claves = ['%%PERSONA_INTERES','%%DETALLES','%%CORREO'];
                $valores = [
                    $_POST['firstname']." ".$_POST['lastname'],
                    $_POST['subject'],
                    $_POST['email']
                ];

                for($i = 0; $i < sizeof($claves); $i++){
                    $message = str_replace($claves[$i], $valores[$i], $message);    
                }
                
                $resp = sendEmailOwn([[$persona['data']['email'], $persona['data']['nombre']]], "Notificación de CV SISCON", $message, "pajaro.octavio96@gmail.com");
                //print_r([[[$persona['data']['email'], $persona['data']['nombre']]], "Notificación de CV SISCON", $message, "pajaro.octavio96@gmail.com"]); 
				//$resp = sendEmailOwn([['pajaro.octavio96@
				
            }else{
				$resp = ['estatus'=>'error', 'info'=>'nodestinanario', 'data'=>$_POST];
			}
        }else{
			$resp = ['estatus'=>'error','info'=>'faltan_datos'];
            //echo json_encode(['estatus'=>'error','info'=>'faltan_datos']);
        }
		echo json_encode($resp);
        break;
    default:
        # code...
        break;
}



if (@$_GET['op']=='obtenerpais') {
    $rspta3=$afiliados->obtenerpaises();
            echo json_encode($rspta3['data']);
}
if (@$_GET['op']=='obtenerestado') {
    $idpais=@$_GET['idpais'];
    $rspta4=$afiliados->obtenerestados($idpais);
            echo json_encode($rspta4['data']);
}

}else{
    $array=array("error"=>'no_session');
	echo(json_encode($array));
} 


if(!isset($_SESSION["alumno_general"]) && !isset($_POST['action'])){
    header("Location: ../../index.php");
}
