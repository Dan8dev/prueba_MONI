 <?php 
 header('Access-Control-Allow-Origin: https://conacon.org', false);
if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/institucion/institucionModel.php';
	
	$instM = new Institucion();

	switch ($_POST["action"]) {
		case 'busquedaClinicaTotal':
			unset($_POST["action"]);
			$pal = strtoupper(str_replace(' ', '', $_POST['search']));
			if(strlen($pal) < 3){
				$results = [];
			}else{
				$results = $instM->busquedaClinicaTotal($pal);
			}
			echo json_encode($results);
			break;

		case 'busqueda_clinica':
			unset($_POST['action']);
			$pal = strtoupper(str_replace(' ', '', $_POST['search']));
			if(strlen($pal) < 3){
				$results = [];
			}else{
				$results = $instM->busqueda_clinica($pal);
			}
			echo json_encode($results);
			break;
		
		case 'busqueda_clinicaCompleta':
			unset($_POST['action']);
			$pal = $_POST['search'];
			$resultadosC = $instM->busqueda_clinicaCompleta($pal);
			echo json_encode($resultadosC);
			break;
			
		case 'lista_instituciones':
			echo json_encode($instM->consultarTodoInstituciones());
			break;
		case 'lista_todo_instituciones':
			$response = $instM->consultarTodoInstituciones();
			$response['data'] = array_merge($response['data'], $instM->consultarTodoInstituciones(1, 0)['data']);
			echo json_encode($response);
			break;

		case 'registrar_clinica':
			
			unset($_POST['action']);
			
			if(isset($_POST['idUsuario'])){
				$actualizarEstatus = $_POST['idUsuario'];
				unset($_POST['idUsuario']);
			}
		
			$validar_clinica_exist = $instM->validar_institucion_existente(trim($_POST['name_clinica_cl']), trim($_POST['emailResp']));
			$validar_clinica_exist_dos = $instM->validar_institucion_existente(trim($_POST['name_clinica_cl']), trim($_POST['email_cl']));
			//var_dump($validar_clinica_exist);
			//var_dump($validar_clinica_exist_dos);
			//die();
			if(!empty($validar_clinica_exist['data']) || !empty($validar_clinica_exist_dos['data'])){
				echo json_encode(['estatus'=>'error', 'info'=>'Ya existe una institución con el mismo nombre o correo electrónico']);
				die();
			}else{
				if(isset($actualizarEstatus)){
					$EstatusNuevo = $instM->ActualizarEstatus($actualizarEstatus);
				}
				$datas = $instM->insertInst($_POST);
				if(isset($actualizarEstatus)){
					$EstatusNuevo = $instM->AsociacionProspecto($actualizarEstatus,$datas['data']);
				}
			}

			echo json_encode($datas);
		break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
