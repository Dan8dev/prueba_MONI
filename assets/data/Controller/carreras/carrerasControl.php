 <?php 
session_start();
if (isset($_POST["action"]) && (isset($_SESSION['usuario']) || isset($_SESSION['alumno']) || isset($_POST['android_id_afiliado']) || isset($_POST['android_id_prospecto']))) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';

	require_once '../../Model/prospectos/prospectosModel.php';
	
	require_once '../../Model/carreras/carrerasModel.php';
	require_once '../../Model/marketing/marketingModel.php';

	require_once '../../Model/planpagos/generacionesModel.php';
	
	$prospEM = new Prospecto();
	$carrM = new Carrera();
	
	$generacionM = new Generaciones();

	$fr = function($acc, $item){
		// print_r($item);
		switch ($item['etapa']) {
			case '0':
				$acc['pendientes']++;
				break;
			case '1':
				$acc['espera']++;
				break;
			case '2':
				$acc['confirmado']++;
				break;
			case '3':
				$acc['rechazo']++;
				break;
			case '4':
				$acc['no_interes']++;
				break;
		}
		return $acc;
	};
	switch ($_POST["action"]) {
		case 'listado_carreras':
			if(isset($_SESSION['usuario'])){
				if(isset($_POST['institucion'])){
					$listCarr = $carrM->listarCarreras($_POST['institucion'])["data"];
				}else{
					$listCarr = $carrM->listarCarreras()["data"];
				}

				$mktM = new Marketing();

				for ($i=0; $i < sizeof($listCarr); $i++) { 
					$lugares_reservados = $carrM->consultarProspectosCarrera($listCarr[$i]["idCarrera"])["data"];
					$listCarr[$i]["lugares_reserv"] = sizeof($lugares_reservados);

					$info_estatus = ['pendientes'=>0,'espera'=>0,'confirmado'=>0,'rechazo'=>0,'no_interes'=>0];

					if(!empty($lugares_reservados)){
						$listCarr[$i]["estatus_info"] = array_reduce($lugares_reservados, $fr, $info_estatus);
						// consultar pagos de prospectos
						$prosp_carrera = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'carrera', $listCarr[$i]["idCarrera"])['data'];
						
						$listCarr[$i]["prospectos_carrera"] = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'carrera', $listCarr[$i]["idCarrera"])['data'];
						for ($p=0; $p < sizeof($listCarr[$i]["prospectos_carrera"]); $p++) { 
							$listCarr[$i]["prospectos_carrera"][$p]['pagos_realizados'] = $prospEM->consultar_pagos_prospectos($prosp_carrera[$p]['idAsistente'], $listCarr[$i]["idCarrera"])['data'];
						}
						// $listCarr[$i]["prospectos_carrera"] = $prosp_carrera;
					}
					
				}
			}else{
				if(isset($_POST['institucion'])){
					$listCarr = $carrM->listarCarreras($_POST['institucion'])["data"];
				}else{
					$listCarr = $carrM->listarCarreras()["data"];
				}
			}
			echo(json_encode($listCarr));
			break;
		case 'actualizar_lista_prospectos':
			$resp = [];
			$mktM = new Marketing();
			if(isset($_POST['tipo']) && $_POST['tipo'] == 'evento' && isset($_SESSION['usuario'])){
				$lugares_reservados = $evt->consultarAsistentesEvento($_POST["idInteres"])["data"];
				$objDetalle = $evt->consultarEvento_Id($_POST["idInteres"])['data'];
				$objDetalle["lugares_reserv"] = sizeof($lugares_reservados);
				
				$info_estatus = ['rechazados'=>0,'confirmados'=>0,'pendientes'=>0];

				if(!empty($lugares_reservados)){
					$objDetalle["estatus_info"] = array_reduce($lugares_reservados, $fr, $info_estatus);
					// consultar pagos de prospectos
					$prosp_evento = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'evento', $_POST["idInteres"])['data'];
					for ($p=0; $p < sizeof($prosp_evento); $p++) { 
						$prosp_evento[$p]['pagos_realizados'] = $prospEM->consultar_pagos_prospectos($prosp_evento[$p]['idAsistente'], $_POST["idInteres"])['data'];
					}
					$objDetalle["prospectos_carrera"] = $prosp_evento;
				}
				$resp = ["estatus"=>'ok','data'=>$objDetalle];
			}else{
				$resp = ["estatus"=>"error"];
			}
			echo json_encode($resp);
			break;
		case 'confirmar_asistencia':
			$inte = $_POST["id_interes"];
			$asis = $_POST["id_asistente"];
			
			echo json_encode($prospEM->confirmar_asistencia_prospecto($inte, $asis));
			break;
		case 'rechazar_asistencia':
			$inte = $_POST["id_interesRechazo"];
			$asis = $_POST["id_asistenteRechazo"];
			
			echo json_encode($prospEM->rechazar_asistencia_prospecto($inte, $asis));
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
