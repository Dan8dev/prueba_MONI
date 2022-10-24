<?php 
 header('Access-Control-Allow-Origin: https://conacon.org', false);
if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/verificacion/notificacionModel.php';

	$notf = new Notificacion();
    
	switch ($_POST["action"]) {
		case 'agregar_notificacion':
            $results = $notf->registrarNotificacion(21, 'Titulo', 'Se le comunica que se ha registrado una nueva clinica registrada para el estado que uste\' gestiona', 1);
			echo json_encode($results);
			break;
        case 'listar_notificaciones_prospecto':
            if(!isset($_POST['prospecto'])){
                echo json_encode(['estatus'=>'error', 'info'=>'Falta informacion de la persona']);
                die();
            }
            $valid_status = [0, 1, 2];
            $estatus = (isset($_POST['estatus']) && in_array($_POST['estatus'], $valid_status)) ? $_POST['estatus'] : null;
            echo json_encode($notf->listarNotificacionesProspecto($_POST['prospecto'], $estatus));
            break;
        case 'marcar_como_leido':
            echo json_encode($notf->cambiarEstatusNotificacion($_POST['notificacion'], 1));
            break;
        case 'borrar_notificacion':
            echo json_encode($notf->cambiarEstatusNotificacion($_POST['notificacion'], 2));
            break;
        case 'crearEvento':
            unset($_POST['action']);
            var_dump($_POST);
            $error = [];
            $inserted = [];
            foreach($_POST as $key => $val){               
              $info = [
                'titulo'=> $_POST['nevento'],
                'fechaDisponible' => $_POST['finicio'],
                'fechaLimite' => $_POST['ffin'],
                'direccion' => $_POST['direvent'],
                'descripcion' => $_POST['desevent']
                     ];
            $crear = $notf->crearEvento($info);
                    if($crear['estatus'] == 'error'){
                        array_push($error, $crear);
                    }else{
                        array_push($inserted, $crear);
                    }
            }
            if(empty($error)){
                $resp = ['estatus'=>'ok', 'insertados'=>$inserted];
            }else{
                $resp = ['estatus'=>'error', 'fallos'=>$error];
           }
            echo json_encode($resp);
            break;
        default:
			
        echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;

	}
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
