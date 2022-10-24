<?php 
	function getDataCarrera($event){
		$evt = new Carrera();

		return $evt->consultarCarreraBy_codigo($event);
	}
	
?>