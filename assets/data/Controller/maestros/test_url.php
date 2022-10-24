<?php

$enlace = "assets/files/clases/tareas/clase_161/A21_C161_T28_2022-04-06_16-48-00.pdf";

$archivo_buscar = '../../../../'.$enlace;

$link = '#';
if(!file_exists($archivo_buscar)){
    if(file_get_contents('https://conacon.org/moni/'.$enlace)){
        echo 'Encontrado en conacon <br>';
        $link = 'https://conacon.org/moni/'.$enlace;
        echo "<a href='".$link."'>".$link."</a>"."<br>-----------------<br>";
    }
}else{
    echo 'Encontrado local <br>';
    $link = '../'.$enlace;
    echo "<a href='".$archivo_buscar."'>".$archivo_buscar."</a>"."<br>-----------------<br>";
}
// $link = $archivo_buscar;
$boton_link = '';
if($link == '#'){
    $boton_link = '<button class="btn btn-secondary waves-effect waves-light" target="_blank"><i class="fas fa-file-download"></i> Ver </button>';
}else{
    $boton_link = '<a class="btn btn-primary waves-effect waves-light" href="'.$link.'" target="_blank"><i class="fas fa-file-download"></i> Ver </a>';
}

echo $boton_link;