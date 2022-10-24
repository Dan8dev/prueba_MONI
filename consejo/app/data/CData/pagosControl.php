<?php 
date_default_timezone_set("America/Mexico_City");
require "../Model/PagosModel.php";
$Pgs = new Pagos();

switch ($_POST["action"]) {
	case 'estatusPago':
		unset($_POST["action"]);
		switch ($_POST["tipo"]) {
			case 'rg':
				$pagos = $Pgs->PagosRealizadosTipoRecurrentes(["id_plan"=>$_POST['id_pago'], "numero_pago"=>$_POST['id_fechaPago']]);
				break;
			case 'uc':
				$pagos = $Pgs->PagosRealizados(["id_plan"=>$_POST['id_pago']]);
				break;
			
			default:
				$pagos = ["estatus"=>"error", "info"=>"control: case do not match"];
				break;
		}
		echo json_encode($pagos);
		break;
	case 'generarPago':
		unset($_POST["action"]);
		$_POST['numeroPago'] = (($_POST['numeroPago'] == 0)? null : $_POST['numeroPago']);
		$_POST['fechaApl'] = date("Y-m-d");
		$_POST['fechaReg'] = date("Y-m-d");
		$_POST['montoApl'] = 1000;
		$_POST['estatus'] = "PAGADO";
		#:id_plan, :numeroPago, :fechaApl, :fechaReg, :montoApl , :estatus
		$inserted = $Pgs->registrarPago($_POST);
		echo json_encode($inserted);
		break;
	default:
		echo "errorMethod";
		break;
}
//print_r($alumno->consultarTodoAlumnos());


?>
