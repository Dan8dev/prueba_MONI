<?php 
session_start();
if (isset($_POST["action"]) && isset($_SESSION['usuario'])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	
	require_once '../../Model/colaboradores/comisionesModel.php';

	$comisionesM = new Comision();
	
	# inicio = aaaa-mm-06 . fin = aaaa-m+-05
	switch ($_POST["action"]) {
		case 'consultar_parametros';
            if(isset($_POST['estatus'])){
                echo json_encode($comisionesM->cargar_parametros_comisiones($_POST['estatus']));
            }else{
                echo json_encode($comisionesM->cargar_parametros_comisiones());
            }
			break;
        case 'consultar_usuarios':
            echo json_encode($comisionesM->cargar_usuarios());
            break;
		case 'consultar_parametro':
			echo json_encode($comisionesM->cargar_parametro($_POST['parametro']));
			break;
		case 'actualizar_parametros':
			$info_comision = $comisionesM->cargar_parametro($_POST['comision_val']);
			$carrera_com = $info_comision['data']['idCarrera'];
			$tipo_com = $_POST['inp_tipo_comision'];
			// var_dump([$info_comision['data']['minimo'] , $_POST['inp_minimo'],'' , $info_comision['data']['maximo'] , $_POST['inp_maximo']]);
			if($info_comision['data']['minimo'] != $_POST['inp_minimo'] || $info_comision['data']['maximo'] != $_POST['inp_maximo']){
				$validar_rango = $comisionesM->validar_rango($carrera_com, $tipo_com, $_POST['inp_minimo'], $_POST['inp_maximo'], $_POST['comision_val']);
				// var_dump($validar_rango);
				if(!empty($validar_rango['data'])){
					echo json_encode(['estatus'=>'error', 'info'=>'Ya hay un porcentaje de comisión asignado para el rango seleccionado']);
					die();
				}else{
					$comisionesM->actualizar_parametros($_POST['comision_val'], $_POST['inp_minimo'], $_POST['inp_maximo'], $_POST['inp_porcentaje'], $tipo_com);
					echo json_encode(['estatus'=>'ok', 'info'=>'Comisión actualizada correctamente']);
				}
			}else{
				$comisionesM->actualizar_parametros($_POST['comision_val'], $_POST['inp_minimo'], $_POST['inp_maximo'], $_POST['inp_porcentaje'], $tipo_com);
				echo json_encode(['estatus'=>'ok', 'info'=>'Comisión actualizada correctamente']);
			}
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
