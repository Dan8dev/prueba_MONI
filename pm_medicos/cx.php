<?php
function conect(){
    //$mysqli = new mysqli("localhost", "root", "", "moni");
	$mysqli = new mysqli("localhost", "root", "", "moni_dev_market");
    $mysqli->set_charset('utf8');
        if ($mysqli->connect_errno) {
            echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }else return $mysqli;
}//Fin conect
?>