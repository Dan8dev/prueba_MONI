<?php 
 header('Access-Control-Allow-Origin: https://conacon.org', false);
if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/verificacion/verificacionModel.php';
	require_once '../../Model/prospectos/prospectosModel.php';
	$verM = new Verificacion();
    $prospM = new Prospecto();
    
	// echo $_POST["action"];
	switch ($_POST["action"]) {
		case 'agregar_jerarquia':
			if(!isset($_POST['nivel']) || !isset($_POST['nombre'])){echo json_encode(['estatus'=>'error', 'info'=>'Los datos son requeridos']);die();}
            unset($_POST['action']);
            $results = $verM->registrarJerarquia($_POST);
			echo json_encode($results);
			break;
        case 'asignar_jerarquia':

            if(!isset($_POST['prospecto']) || !isset($_POST['jerarquia'])){echo json_encode(['estatus'=>'error', 'info'=>'Los datos son requeridos']);die();}
            $info_prosp = $prospM->consultar_info_prospecto_afiliado($_POST['prospecto']);
            if($info_prosp['estatus'] != 'ok' || $info_prosp['data'] == false ){
                echo json_encode(['estatus'=>'error', 'info'=>'La informacion del prospecto es incorrecta']);
                die();
            }
            if(intval($info_prosp['data']['estado']) == 0){
                echo json_encode(['estatus'=>'error', 'info'=>'La informacion del estado de residencia de la persona, es requerida']);
                die();
            }
            $info_prosp = $info_prosp['data'];
            /* // verificar que no tenga ya una jerarquia asignada
            $info_p = $verM->consultarJerarquiaProspecto($_POST['prospecto']);
            if($info_p){
                echo json_encode(['estatus'=>'error', 'info'=>'Esta persona ya tiene un cargo asignado']);
                die();
            } */
            // verificar que la jerarquia permita x cargos por estado
            $info_jer = $verM->consultarJerarquiaId($_POST['jerarquia']);
            if(!$info_jer){
                echo json_encode(['estatus'=>'error', 'info'=>'El cargo proporcionado no existe']);
                die();
            }
            $jerarq_estados = $verM->consultarJerarquiaEstado($info_prosp['estado'], $_POST['jerarquia']);
            if(intval($info_jer['por_estado']) > 0 && $info_jer['por_estado'] <= sizeof($jerarq_estados)){
                echo json_encode(['estatus'=>'error', 'info'=>'Este cargo no puede ser ocupado por mas de '.$info_jer['por_estado'].' persona (s) por estado']);
                die();
            }
            $actualizar = $verM->asignarJerarquiaProspecto($_POST['prospecto'], $_POST['jerarquia']);
            if($actualizar > 0){
                echo json_encode(['estatus'=>'ok', 'data' => $actualizar]);
            }else{
                echo json_encode(['estatus'=>'error', 'info' => 'Sin cambios aplicados']);
            }

            break;
        case 'listar_cargos_estado':
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 21;
            echo json_encode($verM->consultarJerarquiaEstado($estado));
            break;
        case 'listar_jerarquias':
            $jerarquias = $verM->consultarJerarquias();
            echo json_encode($jerarquias);
            break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
