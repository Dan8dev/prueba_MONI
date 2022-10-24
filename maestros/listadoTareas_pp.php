 <?php
//session_start();
if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	// require_once '../Model/usuarioModel.php';
	require_once '../../Model/eventos/eventosModel.php';
	require_once '../../Model/prospectos/prospectosModel.php';
	require_once '../../Model/marketing/marketingModel.php';


    $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
	
	$prospEM = new Prospecto();
	$evt = new Evento();

	$fr = function($acc, $item){
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
		case 'listado_eventos':
			$l_ev = [];
			if(isset($_SESSION['usuario'])){
				$l_ev = $evt->listarEventos()["data"];

				$mktM = new Marketing();

				for ($i=0; $i < sizeof($l_ev); $i++) { 
					$lugares_reservados = $evt->consultarAsistentesEvento($l_ev[$i]["idEvento"])["data"];
					$l_ev[$i]["lugares_reserv"] = sizeof($lugares_reservados);
					
					$info_estatus = ['pendientes'=>0,'espera'=>0,'confirmado'=>0,'rechazo'=>0,'no_interes'=>0];

					if(!empty($lugares_reservados)){
						$l_ev[$i]["estatus_info"] = array_reduce($lugares_reservados, $fr, $info_estatus);
						// consultar pagos de prospectos
						$prosp_evento = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'evento', $l_ev[$i]["idEvento"])['data'];
						
						$l_ev[$i]["prospectos_eventos"] = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'evento', $l_ev[$i]["idEvento"])['data'];
						for ($p=0; $p < sizeof($prosp_evento); $p++) { 
							$prosp_evento[$p]['pagos_realizados'] = $prospEM->consultar_pagos_prospectos($prosp_evento[$p]['idAsistente'], $l_ev[$i]["idEvento"])['data'];
						}
						$l_ev[$i]["prospectos_eventos"] = $prosp_evento;
					}
				}
			}else{
				$l_ev = ["estatus"=>"error", "info"=>"sesion_vencida"];		
			}
			echo(json_encode($l_ev));
			break;
		case 'actualizar_lista_prospectos':
			$resp = [];
			$mktM = new Marketing();
			if(isset($_POST['tipo']) && $_POST['tipo'] == 'evento' && isset($_SESSION['usuario'])){
				$lugares_reservados = $evt->consultarAsistentesEvento($_POST["idInteres"])["data"];
				$objDetalle = $evt->consultarEvento_Id($_POST["idInteres"])['data'];
				$objDetalle["lugares_reserv"] = sizeof($lugares_reservados);
				
				$info_estatus = ['pendientes'=>0,'espera'=>0,'confirmado'=>0,'rechazo'=>0,'no_interes'=>0];

				if(!empty($lugares_reservados)){
					$objDetalle["estatus_info"] = array_reduce($lugares_reservados, $fr, $info_estatus);
					$prosp_evento = $mktM->consultar_fila_atencion_byPersonal($_SESSION['usuario']['idPersona'], 'evento', $_POST["idInteres"])['data'];
					for ($p=0; $p < sizeof($prosp_evento); $p++) { 
						$prosp_evento[$p]['pagos_realizados'] = $prospEM->consultar_pagos_prospectos($prosp_evento[$p]['idAsistente'], $_POST["idInteres"])['data'];
					}
					$objDetalle["prospectos_eventos"] = $prosp_evento;
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
		case 'talleres_eventos':
			$talleres = $evt->talleres_eventos($_POST['evento']);
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
					$apartar = $prospEM->apartar_talleres($info);
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
		case 'consultar_eventos_memoria':
			unset($_POST['action']);
			$tipo = 0;
			$resp = [];
			if(isset($_POST['tipo'])){
				switch ($_POST['tipo']) {
					case 'proximos':
						$tipo = 1;
						break;
					case 'memoria':
						$tipo = 2;
						break;
				}
				
				$resp = $evt->listarEventos($tipo);
				
				if($resp['estatus']=='ok'){
					for ($i=0; $i < sizeof($resp['data']); $i++) { 
						$resp['data'][$i]['short_url'] = urlencode(gzcompress($resp['data'][$i]['video_url'], 0));
						// var_dump(gzcompress($resp['data'][$i]['video_url'], -1));
					}
				}
			}
			echo json_encode($resp);
			break;
		case 'consultarEvento_Clave':
				echo json_encode($evt->consultar_evento_clave_no_estatus($_POST['clave']));
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('HTTP/1.0 403 Forbidden');
}

require_once( "cx.php" );

function agregarClaseForm( $idMaestro ){
	$con = conect();
	$sql = "SELECT maestros_cursos.*, a_carreras.idCarrera AS idCurso, a_carreras.nombre AS nombreCurso FROM maestros_cursos, a_carreras WHERE id_maestro =".$idMaestro." AND a_carreras.idCarrera = maestros_cursos.id_curso";
	$resultado = $con->query($sql);
	return $resultado;
}//Fin agregarTareaForm

function dataTareaEdit( $idTarea ){
	$con = conect();
	$sql = "SELECT * FROM clases_tareas WHERE idTareas = $idTarea";
	$resultado = $con->query($sql);
	return $resultado;
}//fin dataTareaEdit

function dataClaseEdit( $idClase ){
	$con = conect();
	$sql = "SELECT * FROM clases WHERE idClase = $idClase";
	$resultado = $con->query($sql);
	return $resultado;
}//fin dataTareaEdit

function editarTarea( $idMaestro ){
	$con = conect();
	$sql = "UPDATE clases_tareas SET idClase = '".$_POST['idClase']."', titulo = '".$_POST['nombre']."' , descripcion = '".$_POST['descripcion']."', fecha_limite = '".$_POST['fecha_limite']." ".$_POST['hora_limite'].":00', idMaestro = $idMaestro WHERE idTareas = ".$_GET['idTarea'];
	$con->query($sql);
	header( "Location: index.php?e=1&p=editar&idTarea=".$_GET['idTarea'] );
} //Fin agregarTarea

function editarClase( $idClase ){
	$con = conect();
	$sql = "UPDATE clases SET titulo = '".$_POST['nombreClase']."', idMateria = '".$_POST['idCurso']."' WHERE idClase = ".$idClase;
	$con->query($sql);
	header( "Location: index.php?e=1&p=editarClase&idClase=".$idClase );
} //Fin editarClase

function agregarTareaForm( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases.idClase, clases.idMateria, clases.titulo AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso  
	FROM clases, a_carreras 
	WHERE clases.idMateria = a_carreras.idCarrera AND idMaestro = $idMaestro;";
	$resultado = $con->query($sql);
	return $resultado;
}//Fin agregarTareaForm

function agregarTarea( $idMaestro ){
	$con = conect();
	$sql = "INSERT INTO clases_tareas 
	(idClase, titulo, descripcion, idMaestro, fecha_limite) 
	 VALUES 
	 ('".$_POST['idClase']."', '".$_POST['nombre']."', '".$_POST['descripcion']."', $idMaestro, '".$_POST['fecha_limite']." ".$_POST['hora_limite'].":00' )";
	 $con->query($sql);
	header( "Location: index.php?e=1&p=ato" );
} //Fin agregarTarea

function agregarClase( $idMaestro ){
	$con = conect();
	$_POST['recursos'] = str_replace( ',["",""],', ',', $_POST['recursos']);
	$_POST['recursos'] = str_replace( '["",""],', '', $_POST['recursos']);
	$_POST['recursos'] = str_replace( ',["",""]', '', $_POST['recursos']);
	$sql = "INSERT INTO clases 
	(idMateria, titulo, idMaestro, recursos) 
	 VALUES 
	 ('".$_POST['idCurso']."', '".$_POST['nombreClase']."', $idMaestro, '".$_POST['recursos']."' )";
	 $con->query($sql);
	header( "Location: index.php?e=1&p=aco" );
} //Fin agregarClase

function eliminarTarea( $idTarea ){
	$con = conect();
	$sql = "DELETE FROM clases_tareas WHERE idTareas = $idTarea";
	$con->query($sql);
	header( "Location: index.php?e=1&p=eto" );
} //Fin eliminarTarea

function eliminarClase( $idClase ){
	$con = conect();
	$sql = "DELETE FROM clases WHERE idClase = $idClase";
	$con->query($sql);
	header( "Location: index.php?e=1&p=eco" );
} //Fin eliminarTarea

function listarTareasMaestros( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases.idClase AS idClase, clases.idMateria AS idCurso, clases.titulo AS nombreClase, clases_tareas.fecha_limite, clases_tareas.idTareas AS idTareas, clases_tareas.titulo AS tituloTarea, a_carreras.nombre AS nombreCurso, clases_tareas.descripcion 
	FROM clases, clases_tareas, a_carreras 
	WHERE clases.idClase = clases_tareas.idClase AND clases_tareas.idMaestro = $idMaestro AND clases.idMaestro = $idMaestro AND a_carreras.idCarrera = clases.idMateria ORDER BY idTareas DESC;";
	$resultado = $con->query($sql);
	return $resultado;
}//listarTareasMaestros()

function listarClasesMaestros( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases.idClase, clases.idMateria, clases.titulo AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso  
	FROM clases, a_carreras  
	WHERE clases.idMateria = a_carreras.idCarrera AND clases.idMaestro = $idMaestro ORDER BY clases.idClase DESC;";
	$resultado = $con->query($sql);
	return $resultado;
}//listarTareasMaestros()

function listarTareasAlumnos( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases_tareas_entregas.*, afiliados_conacon.nombre, clases_tareas.titulo  
	FROM clases_tareas_entregas, clases_tareas, afiliados_conacon 
	WHERE clases_tareas_entregas.idTarea = clases_tareas.idTareas AND clases_tareas.idMaestro = $idMaestro AND afiliados_conacon.id_afiliado=clases_tareas_entregas.idAlumno
	AND clases_tareas.idTareas = clases_tareas_entregas.idTarea;";
	$resultado = $con->query($sql);
	return $resultado;
}//listarTareasAlumnos()

function calificarTarea( $idEntrega ){
	$con = conect();
	$sql = "UPDATE clases_tareas_entregas SET calificacion = '".$_GET['calificacion']."' WHERE idEtrega = ".$_GET['idEntrega'];
	$con->query($sql);
	header( "Location: index.php?e=1" );
} //Fin editarClase

function listarExamenesMaestros( $idMaestro ){
	$con = conect();
	$sql = "SELECT cursos_examen.idExamen, cursos_examen.idCurso, cursos_examen.nombre AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso, fechaInicio   
	FROM cursos_examen, a_carreras  
	WHERE cursos_examen.idCurso = a_carreras.idCarrera AND cursos_examen.idMaestro = $idMaestro";
	$resultado = $con->query($sql);
	return $resultado;
}//listarTareasMaestros()

function examenInfo( $idExamen ){
	$con = conect();
	$sql = "SELECT *   
	FROM cursos_examen
	WHERE idExamen = $idExamen";
	$resultado = $con->query($sql);
	return $resultado;
}//examenInfo()

function examenPreguntas( $idExamen ){
	$con = conect();
	$sql = "SELECT *   
	FROM cursos_examen_preguntas
	WHERE idExamen = $idExamen";
	$resultado = $con->query($sql);
	return $resultado;
}//examenInfo()

function listarResultadosExamenes( $idMaestro ){
	$con = conect();
	$sql = "SELECT afiliados_conacon.nombre AS alumno, curso_examen_alumn_resultado.*, cursos_examen.Nombre 
	FROM afiliados_conacon, curso_examen_alumn_resultado, cursos_examen 
	WHERE curso_examen_alumn_resultado.idExamen = cursos_examen.idExamen AND cursos_examen.idMaestro = $idMaestro AND afiliados_conacon.id_afiliado = curso_examen_alumn_resultado.idAlumno;";
	$resultado = $con->query($sql);
	return $resultado;
}//examenInfo()
?>