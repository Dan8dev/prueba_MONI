<?php
if (isset($_POST["usos"])) {
    // Aquí no hay nada que ver ... siga su camino
    $usos = $_POST["usos"];
    echo $usos;

    switch ($usos) {
        case "GENERAL DE LEY PERSONAS MORALES":
            $usos='moral';
            break;
        case "PERSONA MORAL CON FINES NO LUCRATIVOS":
            $usos='moral';
            break;
        case "RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTOS PERMANENTE EN MEXICO":
            $usos='sin efectos';
            break;
        case "SOCIEDADES COOPERATIVAS DE PRODUCCION QUE OPTAN POR DIFERIR SUS INGRESOS":
            $usos='moral';
            break;
        case "ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS":
            $usos='moral';
            break;
        case "OPCIONAL PARA GRUPOS DE SOCIEDADES":
            $usos='moral';
            break;
        case "COORDINADOS":
            $usos='moral';
            break;  
        case "REGIMEN SIMPLICADO DE CONFIANZA":
            $usos='moral';
            break;  
        case "ARRENDAMIENTOS":
            $usos='fisica completa';
            break; 
        case "PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES":
            $usos='fisica completa';
            break;
        case "SIN OBLIGACIONES FISCALES":
            $usos='sin efectos';
            break;  
        case "INCORPORACION FISCAL":
            $usos='moral';
            break;     
        case "REGIMEN DE LAS ACTIVIDADES EMPRESARIALES CON INGRESOS A TRAVEZ DE PLATAFORMAS":
            $usos='fisica completa';
            break;     
        case "REGIMEN SIMPLICADO DE CONFIANZA":
            $usos='moral';
            break;
        default:
            $usos='fisica';
            break;                        
    }
    
    // Ya no avance más no tiene caso.. solo es copia de processRegim.php
    
    $regArr = array(
        "moral" => array("SELECCIONA USO", "ADQUISICION DE MERCANCIAS",
        "DEVOLUCIONES, DESCUENTOS O BONIFICACIONES", "GASTOS EN GENERAL",
        "CONSTRUCCIONES", "MOBILIARIO Y EQUIPO DE OFICINA POR INVERSIONES",
        "EQUIPO DE TRANSPORTE", "EQUIPO DE COMPUTO Y ACCESORIOS",
        "DADOS, TROQUELES, MOLDES, MATICES Y HERRAMENTAL", "COMUNICACIONES TELEFONICAS",
        "COMUNICACIONES SATELITALES", "OTRA MAQUINARIA Y EQUIPO",
        "SIN EFECTOS FISCALES", "PAGOS"),

        "sin efectos" => array("SELECCIONA USO", "SIN EFECTOS FISCALES", "PAGOS"),

        "fisica" => array("SELECCIONA USO", "HONORARIOS MEDICOS, DENTALES Y GASTOS HOSPITALARIOS", 
        "GASTOS MEDICOS POR INCAPACIDAD O DISCAPACIDAD",
        "GASTOS FUNERALES", "DONATIVOS",
        "INTERESES REALES EFECTIVAMENTE PAGADOS POR CREDITOS HIPOTECARIOS (CASA HABITACION)", "APORTACIONES VOLUNTARIAS AL SAR",
        "PRIMAS POR SEGUROS DE GASTOS MEDICOS", "GASTOS DE TRANSPORTACION ESCOLAR OBLIGATORIA",
        "DEPOSITOS EN CUENTAS PARA EL AHORRO, PRIMAS QUE TENGAN COMO BASE PLANES DE PENSIONES", "PAGOS POR SERVICIOS EDUCATIVOS (COLEGIATURAS) ",
        "SIN EFECTOS FISCALES", "PAGOS"),

        "fisica completa" => array("SELECCIONA USO", "ADQUISICION DE MERCANCIAS",
        "DEVOLUCIONES, DESCUENTOS O BONIFICACIONES", "GASTOS EN GENERAL",
        "CONSTRUCCIONES", "MOBILIARIO Y EQUIPO DE OFICINA POR INVERSIONES",
        "EQUIPO DE TRANSPORTE", "EQUIPO DE COMPUTO Y ACCESORIOS",
        "DADOS, TROQUELES, MOLDES, MATICES Y HERRAMENTAL", "COMUNICACIONES TELEFONICAS",
        "COMUNICACIONES SATELITALES", "OTRA MAQUINARIA Y EQUIPO",
        "HONORARIOS MEDICOS, DENTALES Y GASTOS HOSPITALARIOS", "GASTOS MEDICOS POR INCAPACIDAD O DISCAPACIDAD",
        "GASTOS FUNERALES", "DONATIVOS",
        "INTERESES REALES EFECTIVAMENTE PAGADOS POR CREDITOS HIPOTECARIOS (CASA HABITACION)", "APORTACIONES VOLUNTARIAS AL SAR",
        "PRIMAS POR SEGUROS DE GASTOS MEDICOS", "GASTOS DE TRANSPORTACION ESCOLAR OBLIGATORIA",
        "DEPOSITOS EN CUENTAS PARA EL AHORRO, PRIMAS QUE TENGAN COMO BASE PLANES DE PENSIONES", "PAGOS POR SERVICIOS EDUCATIVOS (COLEGIATURAS) ",
        "SIN EFECTOS FISCALES", "PAGOS", "NOMINA")
    );

    foreach ($regArr[$usos] as $value) {

        if ($value == 'Selecciona un uso') {
            echo "<option value=''>" . utf8_decode($value) . "</option>";
        } else {
            echo "<option value='" . utf8_decode($value) . "'>" . utf8_decode($value) . "</option>";
        }

    }
}
