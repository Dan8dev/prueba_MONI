<?php 
date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/colaboradores/colaboradorModel.php';
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/colaboradores/pagosModel.php';
	require_once '../../Model/alumnos/alumnoModel.php';
$Colab = new Colaborador();
$periodo = $Colab->obtenerPeriodo(date("Y-m-").'05');


$fchI = $periodo["inicio"];
$fchFF = ((date("Y-m-d") == $periodo["final"])? date("Y-m-d") : $periodo["final"]);
$fchF = $periodo["final"];

$colaboradores = $Colab->consultarTodoColaboradores_ByEstatus(1)['data'];
for ($cl=0; $cl < sizeof($colaboradores); $cl++) { 
	$colaborador = $colaboradores[$cl];
	$movs = $Colab->calcularComisionColaborador($colaborador['idColaborador'],$fchI, $fchF);
	if($movs["total_comision_calculo"] != "fuera_de_rango"){
		$estadoCuenta = [
				"total_comision"=>$movs["total_comision_calculo"],
				"fecha_corte" => $fchF,
				"total_operaciones"=>$movs["total_Movimientos"]
			];
		$movimientosAlumnos = [];
		for ($i=0; $i < sizeof($movs["alumnos"]); $i++) {
			for ($j=0; $j < sizeof($movs["alumnos"][$i]["movimientos"]); $j++) {
				$info = ["id_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["id_temp"],
						 "fecha_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["fecha_deposito"],
						 "monto_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["monto"],
						 "porcentaje_comision"=>$movs["alumnos"][$i]["movimientos"][$j]["comision"][0]["porcentaje"],
						 "comision_operacion"=>$movs["alumnos"][$i]["movimientos"][$j]["comision"]["monto_u"],
						 "id_carrera"=>$movs["alumnos"][$i]["movimientos"][$j]["id_carrera"],
						 "alumno"=>$movs["alumnos"][$i]["apellidoPaterno"]." ".$movs["alumnos"][$i]["apellidoMaterno"]." ".$movs["alumnos"][$i]["nombres"]
						];
				array_push($movimientosAlumnos, $info);
			}
		}
		$estadoCuenta["operaciones"] = $movimientosAlumnos;
		$jsonFormat = json_encode($estadoCuenta); # transformar en formato JSON
		# validar no exista corte para este periodo
			$pagos = new Pagos();
		$corteExiste = $pagos->consultarCorteComisionPeriodo(["fechaF"=> $fchF,"colaborador"=> $colaborador['idColaborador']]);
		if($corteExiste["estatus"]=="ok" && sizeof($corteExiste["data"])>0){
			$resp = ["estatus"=>"error","info"=>"corte_existente"];
		}else{
			#:colaborador, :montoTotal, :fechaCorte, :jsonEC,
			$insert = [
				"colaborador"=>$colaborador['idColaborador'],
				"montoTotal"=>$movs["total_comision_calculo"],
				"fechaCorte"=>$fchFF,
				"jsonEC"=>$jsonFormat
			];
			$crearCorte = $pagos->generarCorteComisionColaborador($insert);
			if($crearCorte["estatus"] == "ok"){
				$resp = $crearCorte;
				echo("se ha generado un corte<br>");
			}else{
				$resp = ["estatus"=>"error", "info"=>"error_crear_corte"];
			}
		}

		// $resp = $estadoCuenta;
	}else{
		$resp = ["estatus"=>"error", "info"=>"comision_no_valida"];
	}
}




?>