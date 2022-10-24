<?php

function colorEstados( $estado ){
    switch ($estado){
        case 1: return "antiquewhite"; //Enviado: Beige
        case 2: return "lightblue"; //En revisión: Azul
        case 3: return "lightpink"; //Con observaciones: Rosa
        case 4: return "#FFDF9E"; //Aceptado: Naranja
        case 5: return "lightsalmon"; //Pospuesto: Rojo claro
        case 6: return "#D7BDE2"; //En proceso: verde claro
        case 7: return "#C8E6C9"; //Finalizado: verde
        case 8: return "#F1B2A5"; //Cancelado: Rojo
        default: return "transparent";
    }//fin switch
}//Fin colorEstados

function nombreEstados( $estado ){
    switch ($estado){
        case 1: return "Enviado";
        case 2: return "En revisión";
        case 3: return "Con observaciones";
        case 4: return "Aceptado, esperando pago";
        case 5: return "Pospuesto";
        case 6: return "En proceso";
        case 7: return "Finalizado";
        case 8: return "Cancelado";
        default: return "-";
    }//fin switch
}//Fin nombreEstados

function historiaClinica( $alumno, $expediente ){
    $archivo = '../alumnos/apm/pm_files/'.$alumno.'/historiaclinica_'.$expediente.'.txt';
    $gestor = fopen($archivo, "r");
    $contenido = fread($gestor, filesize($archivo));
    $contenido = json_decode( $contenido );
    fclose($gestor);
    return $contenido;
}//historiaClinica

?>