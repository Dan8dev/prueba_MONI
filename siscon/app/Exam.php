<?php
 session_start();
 if (!isset($_SESSION["alumno"])) {
   header('Location: index.php');
   die();
 }
 $usr = $_SESSION['alumno'];
   require "data/Model/AfiliadosModel.php";
 $idusuario=$_SESSION['alumno']['id_afiliado'];
 $afiliados = new Afiliados();
 $usuario=$afiliados->obtenerusuario($idusuario);
?>

<!DOCTYPE html>
<html lang="en">
  <?php require 'plantilla/header.php'; ?>
<body>


</body>
</html>