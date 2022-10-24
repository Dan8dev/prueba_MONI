<?php 
session_start();
if (isset($_POST["action"]) && isset($_SESSION['usuario'])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/colaboradores/colaboradorModel.php';
	
	require_once '../../Model/alumnos/alumnoModel.php';
	require_once '../../Model/conexion/conexion.php';

	require_once '../../Model/colaboradores/pagosModel.php';
	
	require_once '../../Model/prospectos/prospectosModel.php';

	$clb = new Colaborador();
	$periodo = $clb->obtenerPeriodo(date("Y-m-d"));

	function validar_codigo($nombre, $apaterno, $amaterno){
		$clb = new Colaborador();
		$codigo = $nombre[0].$apaterno.$amaterno[0];
		$codigo = strtoupper($codigo);
		$exist = true;
		$i = 0;
		while($exist){
			if($i == 0){
				$comprobar = $clb->validar_codigo($codigo);
			}else{
				$comprobar = $clb->validar_codigo($codigo.$i);
			}
			if($comprobar === false){
				$exist = false;
				$codigo = $codigo.$i;
			}else{
				$i++;
			}
		}
		return $codigo;
	}
	
	# inicio = aaaa-mm-06 . fin = aaaa-m+-05
	switch ($_POST["action"]) {
		case 'consultar_movimientos';
			$resp = [];
			if(isset($_POST["colaborador"])){
				$fchI = $periodo["inicio"];
				$fchF = $periodo["final"];
				$pagos = new Pagos();
				// verificar si existe un corte para el periodo actual
				$corteExiste = $pagos->consultarCorteComisionPeriodo(["fechaF"=> $fchF,"colaborador"=> $_POST["colaborador"]]);
				// si existe un corte para el periodo 
				if($corteExiste["estatus"] == "ok" && !empty($corteExiste["data"])){
					$corte = $clb->calcularComisionColaborador($_POST["colaborador"],$fchI, $fchF, 1);
					$log_file_current = '';
					if(floatval($corte['total_comision_calculo']) != floatval($corteExiste['data'][0]['montoCalculado'])){
						$log_file_current.= "diferencias en corte generado y corte calculado para el periodo: ".$fchI."  ".$fchF."\n";
					}
					$resp['corte_generado'] = $corteExiste;
					$resp['operaciones'] = $corte;
				}else{
					if($corteExiste["estatus"] == "error"){
						$resp = ["estatus"=>"error", "existe_corte"=>$corteExiste];
					}else{
						$resp = $clb->calcularComisionColaborador($_POST["colaborador"],$fchI, $fchF);
			$resp['periodo'] = $periodo;
					}
				}
			}else{
				$resp = ["estatus"=>"error", "info"=>"falta_dato"];
			}

			echo json_encode($resp);
			break;
		case 'generarCorte';
			$resp = [];

			if(isset($_POST["colaborador"])){
				$fchI = $periodo["inicio"];
				$fchFF = ((date("Y-m-d") == $periodo["final"])? date("Y-m-d") : $periodo["final"]);
				$fchF = $periodo["final"];

				$movs = $clb->calcularComisionColaborador($_POST["colaborador"],$fchI, $fchF);
				if($movs["total_comision_calculo"] != "fuera_de_rango"){
					$estadoCuenta = [
							"total_comision"=>$movs["total_comision_calculo"],
							"fecha_corte" => $fchF,
							"total_operaciones"=>$movs["total_Movimientos"]
						];
					$movimientosAlumnos = [];
					for ($i=0; $i < sizeof($movs["alumnos"]); $i++) {
						for ($j=0; $j < sizeof($movs["alumnos"][$i]["movimientos"]); $j++) {
							$info = ["id_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["id_pago"],
									 "fecha_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["fechapago"],
									 "monto_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["montopagado"],
									 "porcentaje_comision"=>$movs["alumnos"][$i]["movimientos"][$j]["comision"][0]["porcentaje"],
									 "comision_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["comision"]["monto_u"],
									 "id_carrera"=>$movs["alumnos"][$i]["movimientos"][$j]["id_carrera"],
									 "alumno"=>$movs["alumnos"][$i]["aPaterno"]." ".$movs["alumnos"][$i]["aMaterno"]." ".$movs["alumnos"][$i]["nombre"]
									];
							array_push($movimientosAlumnos, $info);
						}
					}
					$estadoCuenta["operaciones"] = $movimientosAlumnos;
					$jsonFormat = json_encode($estadoCuenta); # transformar en formato JSON
					# validar no exista corte para este periodo
						$pagos = new Pagos();
					$corteExiste = $pagos->consultarCorteComisionPeriodo(["fechaF"=> $fchF,"colaborador"=> $_POST["colaborador"]]);
					if($corteExiste["estatus"]=="ok" && sizeof($corteExiste["data"])>0){
						$resp = ["estatus"=>"error","info"=>"corte_existente"];
					}else{
						#:colaborador, :montoTotal, :fechaCorte, :jsonEC,
						$insert = [
							"colaborador"=>$_POST["colaborador"],
							"montoTotal"=>$movs["total_comision_calculo"],
							"fechaCorte"=>$fchFF,
							"jsonEC"=>$jsonFormat
						];
						$crearCorte = $pagos->generarCorteComisionColaborador($insert);
						if($crearCorte["estatus"] == "ok"){
							$resp = $crearCorte;
						}else{
							$resp = ["estatus"=>"error", "info"=>"error_crear_corte"];
						}
					}

					// $resp = $estadoCuenta;
				}else{
					$resp = ["estatus"=>"error", "info"=>"comision_no_valida"];
				}


			}else{
				$resp = ["estatus"=>"error", "info"=>"falta_dato"];
			}

			echo json_encode($resp);
			break;
		case 'insertarColaborador':

			$data = ["idInstitucion"=>intval($_POST[""]),
				"nombres"=>$_POST[""],
				"apellidoPaterno"=>$_POST[""],
				"apellidoMaterno"=>$_POST[""],
				"tipo"=>$_POST[""],
				"correo"=>$_POST[""],
				"password"=>$_POST[""],
				"telefono"=>$_POST[""],
				"codigo"=>$_POST[""],
				"fechaRegistro"=>date("Y-m-d h:i:s"),
				"idEmpleado"=>$_POST[""]];

				echo json_encode($data);
			break;
		case 'consultarColaborador_Alumnos':
				echo json_encode($clb->consultarColaborador_Alumnos(1));
			break;
		case 'cargarColaboradores':
			$prospM = new Prospecto();
			
			if(isset($_POST["colaborador"]) && $_POST['colaborador']['persona']['tipo'] == 1){
				$cls = $clb->consultarColaborador_ByInstitucion($_POST['colaborador']['persona']['idInstitucion']);
				$pagos = new Pagos();

				$todos_prospectos = [];

				for ($i=0; $i < sizeof($cls["data"]); $i++) { 
					if($cls["data"][$i]["tipo"] == 2){
						
						$cls["data"][$i]["corteExiste"] = $pagos->consultarCorteComisionPeriodo(["fechaF"=> $periodo["final"],"colaborador"=> $cls["data"][$i]["idColaborador"]])["data"];
						$cls["data"][$i]["alumnos"] = $clb->consultarColaborador_Alumnos($cls["data"][$i]["idColaborador"], $cls["data"][$i]["tipo"])["data"];
					}
					$alumno_byCod = $prospM->consultar_prospecto_by_campo('codigo_promocional', $cls["data"][$i]["codigo"])['data'];
					$alumno_byInst = $prospM->consultar_prospecto_by_campo('idAsociacion', $cls["data"][$i]["idInstitucion"])['data'];
					$todos_prospectos = array_merge($todos_prospectos,$alumno_byInst, $alumno_byCod);
				}
				$todos_prospectos = array_reduce($todos_prospectos, function($acc, $item){
					$find = array_search($item['idAsistente'], array_column($acc, 'idAsistente'));
					// var_dump($find);
					if($find === false){
						array_push($acc, $item);
					}
					return $acc;
				}, []);
				$cls['prospectos'] = $todos_prospectos;
				echo json_encode($cls);
			}else{
				$alumno_byCod = $prospM->consultar_prospecto_by_campo('codigo_promocional', $_POST['colaborador']['persona']["codigo"])['data'];
				echo(json_encode(['mis_prospectos' => $alumno_byCod]));
			}
			break;
		case 'consultarTodoCortesColaborador':
			$pagos = new Pagos();
			echo json_encode($pagos->consultarTodoCortesColaborador($_POST["colaborador"]));
			break;
		case 'consultar_usuarios':
			$estatus = isset($_POST['estatus']) ? $_POST['estatus'] : 1;
			echo json_encode($clb->consultarTodoColaboradores_ByEstatus($estatus));
			break;
		case 'registrar_vocero':
			unset($_POST['action']);
			if(!isset($_POST['user_val']) || intval($_POST['user_val']) == 0){
				$validar_correo = $clb->consultarAcceso_ByCorreo($_POST['inp_Correo']);
				if(!empty($validar_correo['data'])){
					echo json_encode(['estatus'=>'error', 'info'=>'Este correo ya está siendo utilizado por otro correo']);
					die();
				}
				$codigo = validar_codigo($_POST['inp_nombre'], $_POST['inp_aPaterno'], $_POST['inp_aMaterno']);
				$_POST['inp_Codigo'] = $codigo;
				$_POST['sesion'] = $_SESSION['usuario']['idAcceso'];
				$registrar = $clb->registrarColaborador($_POST);
				echo json_encode($registrar);
			}else if(intval($_POST['user_val']) > 0){
				$colab = $clb->consultarColaborador_ById($_POST['user_val']);
				if(strtoupper($_POST['inp_Correo']) != strtoupper($colab['data']['correo'])){
					$validar_correo = $clb->consultarAcceso_ByCorreo($_POST['inp_Correo']);
					if(!empty($validar_correo['data'])){
						echo json_encode(['estatus'=>'error', 'info'=>'Este correo ya está siendo utilizado por otro correo']);
						die();
					}
				}
				$registrar = $clb->actualizarColaborador($_POST);
				echo json_encode($registrar);
			}
			break;
		case 'validar_codigo':
			if(isset($_POST['inp_nombre']) && trim($_POST['inp_nombre']) != '' && isset($_POST['inp_aPaterno']) && trim($_POST['inp_aPaterno']) != '' && isset($_POST['inp_aMaterno']) && trim($_POST['inp_aMaterno'])  != ''){
				echo validar_codigo($_POST['inp_nombre'], $_POST['inp_aPaterno'], $_POST['inp_aMaterno']);
			}
			break;
		case 'consultar_usuario':
			echo json_encode($clb->consultarColaborador_ById($_POST['usr']));
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}

	
}else{
	header('Location: ../../../../index.php');
}
?>
