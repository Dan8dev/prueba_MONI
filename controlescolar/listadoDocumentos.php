 <?php

require_once( "cx.php" );

function agregarClaseForm( $idMaestro ){
	$con = conect();
	$sql = "SELECT maestros_cursos.*, a_carreras.idCarrera AS idCurso, a_carreras.nombre AS nombreCurso FROM maestros_cursos, a_carreras WHERE id_maestro =".$idMaestro." AND a_carreras.idCarrera = maestros_cursos.id_curso";
	$resultado = $con->query($sql);
	return $resultado;
}//Fin agregarTareaForm

function agregarTareaForm( $idMaestro ){
	$con = conect();
	$sql = "SELECT clases.idClase, clases.idMateria, clases.titulo AS nombre, a_carreras.idCarrera, a_carreras.nombre AS nombreCurso  
	FROM clases, a_carreras 
	WHERE clases.idMateria = a_carreras.idCarrera AND idMaestro = $idMaestro;";
	$resultado = $con->query($sql);
	return $resultado;
}//Fin agregarTareaForm

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

function listarDocumentosAlumnos( ){
	$con = conect();
	//$sql = "SELECT DISTINCT id_afiliado, nombre, apaterno, amaterno FROM documentos, afiliados_conacon WHERE documentos.id_prospectos = afiliados_conacon.id_afiliado";
	//$sql = "SELECT DISTINCT idAsistente AS id_afiliado, nombre, aPaterno AS apaterno, aMaterno AS amaterno FROM documentos, a_prospectos WHERE documentos.id_prospectos = a_prospectos.idAsistente";
	//$sql = "SELECT DISTINCT id_afiliado, nombre, apaterno, amaterno FROM documentos, afiliados_conacon WHERE documentos.id_prospectos = afiliados_conacon.id_afiliado";
	/*$sql = "SELECT DISTINCT id_afiliado, afiliados_conacon.id_prospecto, nombre, apaterno, amaterno, id_concepto 
		FROM documentos, afiliados_conacon, a_pagos 
		WHERE documentos.id_prospectos = afiliados_conacon.id_afiliado 
		AND afiliados_conacon.id_prospecto = a_pagos.id_prospecto";*/
	/*$sql = "SELECT DISTINCT id_afiliado, afiliados_conacon.id_prospecto, nombre, apaterno, amaterno, id_concepto 
			FROM afiliados_conacon, a_pagos 
			WHERE afiliados_conacon.id_prospecto = a_pagos.id_prospecto";*/
	/*$sql = "SELECT DISTINCT id_afiliado, afiliados_conacon.id_prospecto, nombre, apaterno, amaterno, pagos_conceptos.descripcion, a_pagos.id_concepto 
	FROM afiliados_conacon, a_pagos, pagos_conceptos WHERE afiliados_conacon.id_prospecto = a_pagos.id_prospecto AND pagos_conceptos.id_concepto = a_pagos.id_concepto;";*/
	/*$sql = "SELECT DISTINCT id_afiliado, afiliados_conacon.id_prospecto, nombre, apaterno, amaterno, email, celular, pagos_conceptos.descripcion, a_pagos.id_concepto 
	FROM afiliados_conacon, a_pagos, pagos_conceptos 
    WHERE afiliados_conacon.id_prospecto = a_pagos.id_prospecto 
    	AND pagos_conceptos.id_concepto = a_pagos.id_concepto
		AND pagos_conceptos.id_concepto != 1
		AND pagos_conceptos.id_concepto != 3    
		AND pagos_conceptos.id_concepto != 4  
		AND pagos_conceptos.id_concepto != 6
        AND pagos_conceptos.id_concepto != 8  
		AND pagos_conceptos.id_concepto != 10  
		AND pagos_conceptos.id_concepto != 11  
		AND pagos_conceptos.id_concepto != 12  
		AND pagos_conceptos.id_concepto != 13  
		AND pagos_conceptos.id_concepto != 14 "; */
	/*$sql = "SELECT DISTINCT id_afiliado, afiliados_conacon.id_prospecto, nombre, apaterno, amaterno, email, celular, pagos_conceptos.descripcion, a_pagos.id_concepto, 


	(SELECT COUNT(*) FROM documentos WHERE documentos.id_prospectos=id_afiliado) as docs
	
		FROM afiliados_conacon, a_pagos, pagos_conceptos 
		WHERE afiliados_conacon.id_prospecto = a_pagos.id_prospecto 
			AND pagos_conceptos.id_concepto = a_pagos.id_concepto
			AND pagos_conceptos.id_concepto != 1
			AND pagos_conceptos.id_concepto != 3    
			AND pagos_conceptos.id_concepto != 4  
			AND pagos_conceptos.id_concepto != 6
			AND pagos_conceptos.id_concepto != 8  
			AND pagos_conceptos.id_concepto != 10  
			AND pagos_conceptos.id_concepto != 11  
			AND pagos_conceptos.id_concepto != 12  
			AND pagos_conceptos.id_concepto != 13  
			AND pagos_conceptos.id_concepto != 14 ";*/

	$sql = "SELECT DISTINCT id_afiliado, afiliados_conacon.id_prospecto, nombre, apaterno, amaterno, email, celular, pagos_conceptos.descripcion, a_pagos.id_concepto, 

	(SELECT COUNT(*) FROM documentos WHERE documentos.id_prospectos=id_afiliado) as docs 
	
	FROM afiliados_conacon, a_pagos, pagos_conceptos 
	
	WHERE afiliados_conacon.id_prospecto = a_pagos.id_prospecto 
			AND pagos_conceptos.id_concepto = a_pagos.id_concepto 
			AND pagos_conceptos.id_concepto != 1 
			AND pagos_conceptos.id_concepto != 3 
			AND pagos_conceptos.id_concepto != 4 
			AND pagos_conceptos.id_concepto != 6 
			AND pagos_conceptos.id_concepto != 8 
			AND pagos_conceptos.id_concepto != 10 
			AND pagos_conceptos.id_concepto != 11 
			AND pagos_conceptos.id_concepto != 12 
			AND pagos_conceptos.id_concepto != 13 
			AND pagos_conceptos.id_concepto != 14 
	
	GROUP BY id_afiliado";
	
	$resultado = $con->query($sql);
	return $resultado;
}//listarDocumentosAlumnos()

function listarExpedienteAlumnos( $id_prospectos ){
	$con = conect();
	$sql = "SELECT * FROM documentos WHERE id_prospectos = $id_prospectos";
	$resultado = $con->query($sql);
	return $resultado;
}//listarDocumentosAlumnos()

function estadosDocumentos( $estado ){
	switch( $estado ){
		case 2: return "<span style='color:red'>Rechazado</span>";
		case 1: return "<span style='color:green'>Aceptado</span>";
		default: return "Sin validar";
	}//fin switch
}//Fin estadosDocumentos

function nombreDocumento( $tipo ){
	switch( $tipo ){
		case 1: return "Identificación";
		case 7: return "Identificación Anverso";
		case 8: return "Identificación Reverso";
		case 2: return "Acta de nacimiento";
		case 3: return "CURP";
		case 4: return "Comprobante de estudios";
		case 5: return "Foto Óvalo";
		case 6: return "Foto Intantil";
		default: return "";
	}//fin switch
}//nombreDocumento

?>