 <?php

function cantidadesPorEstados(){
    $con = conect();
	$sql = "SELECT estado, COUNT(*) as cantidad FROM afiliados_conacon GROUP BY estado ORDER BY cantidad DESC;";
	$resultado = $con->query($sql);
	return $resultado;
}//cantidadesPorEstados

function cantidadesPorEstudios(){
    $con = conect();
	$sql = "SELECT ugestudios, COUNT(*) as cantidad FROM afiliados_conacon GROUP BY ugestudios ORDER BY cantidad DESC;";
	$resultado = $con->query($sql);
	return $resultado;
}//cantidadesPorEstados

function cantidadesPorEdades(){
    $con = conect();
	$sql = "SELECT TIMESTAMPDIFF(YEAR,fnacimiento,CURDATE()) as edad, COUNT(*) as cantidad FROM afiliados_conacon GROUP BY edad ORDER BY cantidad DESC;";
	$resultado = $con->query($sql);
	return $resultado;
}//cantidadesPorEstados

?>