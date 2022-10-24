<?php
session_start();
require "../Model/FormacionModel.php";

$formacion = new Formacion();

$accion = @$_POST["action"];

$idusuario=$_SESSION["alumno_iesm"]['id_afiliado'];

switch ($accion) {
    case 'enviarfotoreconocimiento':
        if (isset($_FILES["file"])){
            $file = $_FILES["file"];
            $name = $file["name"];
            $type = $file["type"];
            $tmp_n = $file["tmp_name"];
            $size = $file["size"];
            $folder = "../../img/reconocimientos/";
            
            if ($type != 'image/jpg' && $type != 'image/jpeg' && $type != 'image/png' && $type != 'image/gif')
            {
            echo "Error, el archivo no es una imagen"; 
            }
            else
            {
                $src = $folder.$name;
                
            move_uploaded_file($tmp_n, $src);
            }
        }
        echo $name;
        break;
    
    default:
        # code...
        break;
}