<?php 
session_start();
session_destroy();

header("Location: evento-asistencia/index.php");
?>