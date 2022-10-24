<?php
function conect(){
    $mysqli = new mysqli("localhost", "moni", "%MoNi_uDC_ieSM%", "moni");
    $mysqli->set_charset('utf8');
	//$mysqli = new mysqli("localhost", "moni", "%MoNi_uDC_ieSM%", "moni");
        if ($mysqli->connect_errno) {
            echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }else return $mysqli;
}//Fin conect
?>