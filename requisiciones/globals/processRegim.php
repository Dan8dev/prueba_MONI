<?php
if(isset($_POST["regimen"])){
    // Capture selected country
    $regm = $_POST["regimen"];
     
    // Define country and city array     
    $regArr = array( 
        "persona fisica" => array("Selecciona un regimen","Regimen Simplificado De Confianza",
        "Sueldos Y Salarios E Ingresos Asimilados A Salarios",
        "Regimen De Actividades Empresariales Y Profesionales", 
        "Regimen De Incorporacion Fiscal",
        "Enajenacion De Bienes",
        "Regimen De Actividades Empresariales Con Ingresos A Traves De Plataformas Tecnologicas",
        "Regimen De Actividades Agricolas,ganaderas, Silvicolas Y Pesqueras",
        "Regimen De Arrendamiento",
        "Intereses",
        "Obtencion De Premios",
        "Dividendos",
        "Demas Ingresos"),
        "persona moral" => array("Selecciona un regimen","Regimen General",
        "Regimen Con Fines No Lucrativos",
        "Regimen Simplificado De Confianza",
        "Regimen De Actividades Agricolas,ganaderas, Silvicolas Y Pesqueras")
);
     
    foreach($regArr[$regm] as $value){

        if($value == 'Selecciona un regimen'){
            echo "<option value=''>". utf8_decode($value) . "</option>";
        }else{
            echo "<option value='".utf8_decode($value)."'>". utf8_decode($value) . "</option>";
        }
    }
}

?>