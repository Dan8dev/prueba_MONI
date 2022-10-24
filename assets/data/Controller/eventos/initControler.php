<?php 
	function getDataEvento($event){
		$evt = new Evento();

		return $evt->consultarEvento_Clave($event);
	}
	function getAsistentes($eventID){
		$evt = new Evento();
		
		return $evt->consultarAsistentesEvento($eventID);
	}
?>