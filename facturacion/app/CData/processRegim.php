<?php
if(isset($_POST["regimen"])){
    // Capture selected country
    $regm = $_POST["regimen"];
    //echo $regm.' Holis';

    //$regm = 'claves';
    // Define country and city array     
    $regArr = array( 
        "persona fisica" => array("Selecciona un regimen", "SUELDOS Y SALARIOS E INGRESOS ASIILADOS A SALARIOS",
        "ARRENDAMIENTOS", "REGIMEN DE ENAJENACION O ADQUISICION DE BIENES",
        "DEMAS INGRESOS", "RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTOS PERMANENTE EN MEXICO",
        "INGRESOS POR DIVIDENDOS (SOCIOS Y ACCIONISTAS)", "PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES",    
        "INGRESOS POR INTERESES", "REGIMEN DE LOS INGRESOS POR OBTENCION DE PREMIOS", 
        "SIN OBLIGACIONES FISCALES", "INCORPORACION FISCAL",
        "ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS", "REGIMEN DE LAS ACTIVIDADES EMPRESARIALES CON INGRESOS A TRAVEZ DE PLATAFORMAS",
        "REGIMEN SIMPLICADO DE CONFIANZA"),
        "persona moral"=> array("Selecciona un regimen", "GENERAL DE LEY PERSONAS MORALES",
        "PERSONA MORAL CON FINES NO LUCRATIVOS", "RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTOS PERMANENTE EN MEXICO",
        "SOCIEDADES COOPERATIVAS DE PRODUCCION QUE OPTAN POR DIFERIR SUS INGRESOS", "ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS",
        "OPCIONAL PARA GRUPOS DE SOCIEDADES","COORDINADOS",
        "REGIMEN SIMPLICADO DE CONFIANZA")
        
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