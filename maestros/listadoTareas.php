 <?php
require_once( "cx.php" );

function agregarClaseForm( $idMaestro ){
	$con = conect();
	//$sql = "SELECT maestros_cursos.*, a_carreras.idCarrera AS idCurso, a_carreras.nombre AS nombreCurso FROM maestros_cursos, a_carreras WHERE id_maestro =".$idMaestro." AND a_carreras.idCarrera = maestros_cursos.id_curso";
	$sql = "SELECT materias.id_materia AS idCurso, materias.nombre AS nombreCurso, maestros_carreras.idCarrera, a_carreras.nombre AS nombreCarrera
	FROM materias, maestros_carreras, a_carreras 
	WHERE a_carreras.idCarrera = materias.id_carrera AND maestros_carreras.idCarrera = materias.id_carrera AND maestros_carreras.idMaestro = ".$idMaestro;
	$resultado = $con->query($sql);
	return $resultado;
}//Fin agregarClaseForm

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
	$sql = "SELECT idClase, titulo as nombre, materias.nombre AS nombreCurso, fecha_hora_clase 
	FROM clases, materias 
	WHERE clases.idMateria = materias.id_materia AND clases.idMaestro = $idMaestro ORDER BY nombreCurso ASC;";
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
	/*$sql = "SELECT clases.idClase AS idClase, clases.idMateria AS idCurso, clases.titulo AS nombreClase, clases_tareas.fecha_limite, clases_tareas.idTareas AS idTareas, clases_tareas.titulo AS tituloTarea, a_carreras.nombre AS nombreCurso, clases_tareas.descripcion 
	FROM clases, clases_tareas, a_carreras 
	WHERE clases.idClase = clases_tareas.idClase AND clases_tareas.idMaestro = $idMaestro AND clases.idMaestro = $idMaestro AND a_carreras.idCarrera = clases.idMateria ORDER BY idTareas DESC;";*/
	$sql = "SELECT idTareas, clases_tareas.titulo AS titulo, fecha_limite, clases.titulo AS nombreClase, clases_tareas.idClase, clases.idMateria, materias.nombre AS nombreCurso 
	FROM clases_tareas, clases, materias 
	WHERE clases.idMateria = materias.id_materia AND clases.idClase = clases_tareas.idClase AND clases_tareas.idMaestro = $idMaestro";
	$resultado = $con->query($sql);
	return $resultado;
}//listarTareasMaestros()

function listarClasesMaestros( $idMaestro ){
	$con = conect();
	/*$sql = "SELECT clases.idClase, clases.idMateria, clases.titulo AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso  
	FROM clases, a_carreras  
	WHERE clases.idMateria = a_carreras.idCarrera AND clases.idMaestro = $idMaestro ORDER BY clases.idClase DESC;";*/
	$sql = "SELECT idClase, titulo as nombre, materias.nombre AS nombreCurso, fecha_hora_clase 
	FROM clases, materias 
	WHERE clases.idMateria = materias.id_materia AND clases.idMaestro = $idMaestro ORDER BY nombre DESC;";
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
	/*$sql = "SELECT cursos_examen.idExamen, cursos_examen.idCurso, cursos_examen.nombre AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso, fechaInicio   
	FROM cursos_examen, a_carreras  
	WHERE cursos_examen.idCurso = a_carreras.idCarrera AND cursos_examen.idMaestro = $idMaestro";*/
	$sql = "SELECT cursos_examen.idExamen, cursos_examen.Nombre AS nombre, cursos_examen.fechaInicio, cursos_examen.fechaFin, materias.nombre AS nombreCurso, a_carreras.nombre AS carrera, CONCAT( maestros.aPaterno, ' ', maestros.aMaterno, ', ', maestros.nombres) AS maestro
	FROM cursos_examen, materias, a_carreras, maestros
	WHERE cursos_examen.idCurso = materias.id_materia AND a_carreras.idCarrera = materias.id_carrera AND maestros.id = cursos_examen.idMaestro AND maestros.id = ".$idMaestro;
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