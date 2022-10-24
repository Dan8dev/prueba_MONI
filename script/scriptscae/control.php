<?php 
require 'consulta.php';

// require("Conexion.php");
$Conexion = new Conexion();
$conX = $Conexion->conectar_moni();


// IDS CONCEPTOS MMEL GEN 13
# $conceptos = [    'Inscripción' => 162,    'Mensualidad' => 163,    'Reinscripción' => 164];
// IDS CONCEPTOS MMEL GEN 14
# $conceptos = [    'Inscripción' => 166,    'Mensualidad' => 167,    'Reinscripción' => 168];
// IDS CONCEPTOS MMEL GEN 15
# $conceptos = [    'Inscripción' => 170,    'Mensualidad' => 171,    'Reinscripción' => 172];
// IDS CONCEPTOS MMEL GEN 16
# $conceptos = [    'Inscripción' => 389,    'Mensualidad' => 390,    'Reinscripción' => 391];


// IDS CONCEPTOS TSU GEN 14
#$conceptos = [    'Inscripción' => 393,    'Mensualidad' => 394,    'Reinscripción' => 395];
// IDS CONCEPTOS TSU GEN 15
#$conceptos = [    'Inscripción' => 397,    'Mensualidad' => 398,    'Reinscripción' => 399];
// IDS CONCEPTOS TSU GEN 16
$conceptos = [    'Inscripción' => 401,    'Mensualidad' => 402,    'Reinscripción' => 403];

// MONTOS REGULARES
$montos = [
    'Inscripción' => 1500,
    'Mensualidad' => 3000,
    'Reinscripción' => 0
];
$beca50 = 1500;
#$idBeca50 = 778; #g13
#$idBeca50 = 831; #g14
#$idBeca50 = 975; #g15

#$idBeca50 = 1786; #g14 tsu
#$idBeca50 = 1828; #g15 tsu
$idBeca50 = 1868; #g16 tsu

// 10549
$alumno = $_POST['alumno'];

$pagos = consultar_pagos($alumno);

$nombre_alu = $pagos['NombreAlumno'];
$corre_alu = $pagos['Correo'];
$moneda_alu = $pagos['Moneda'] == 'MXN' ? 1 : 2;
$otroscorreos = [
    'raulcortescetis@gmail.com'=>'lumei.medical@gmail.com',
    'esther.vite030@gmail.com'=>'esther_vite032@hotmail.com',
    'dr_eserrano@fmposgrado.unam.mx' => 'dr_eserrano@gmposgrado.unam.mx'
];
// if(!in_array($corre_alu, array_keys($otroscorreos))){
//     die();
// }
if(in_array($corre_alu, array_keys($otroscorreos))){
    $corre_alu = $otroscorreos[$corre_alu];
}
$infoAlm = $conX->query("SELECT af.* FROM afiliados_conacon af
        JOIN a_prospectos pr ON pr.idAsistente = af.id_prospecto
        WHERE af.email = '".$corre_alu."'")->fetch(PDO::FETCH_ASSOC);
if(!$infoAlm){
    echo "<h2>No se encontró a: ".$corre_alu."</h2>";
    die();
}
echo "<center><h3>{$pagos['Correo']}</h3></center>";
$idAlumnoMoni = $infoAlm['id_prospecto'];

if($pagos['complicado'] == 0){ // 0:no ; 1:si
    $mens_start = 0;
    foreach($pagos['detallePago'] as $pago){
        $mont_pagado = $pago['SubTotal'];
        $fecha_pago = $pago['Pagos'][sizeof($pago['Pagos'])-1]['FechaPago'];
        $metodo_pago = $pago['Pagos'][sizeof($pago['Pagos'])-1]['FormaPago'];
        $referencia = $pago['Pagos'][sizeof($pago['Pagos'])-1]['IdentificadorPago'];
        $fe_registro = $pago['Pagos'][sizeof($pago['Pagos'])-1]['FechaRegistro'];
        $local_concepto = $pago['Concepto'];

        $fmt_pago = formato_pago('Alumno', $mont_pagado, $fecha_pago, $nombre_alu, '', '', $corre_alu, $local_concepto);
        $promocion = null;
        $reg_pago = "INSERT INTO a_pagos (id_prospecto, id_concepto, detalle_pago, montopagado, cargo_retardo, restante, saldo, costototal, moneda, numero_de_pago, fecha_limite_pago, fechapago, idPromocion, estatus, metodo_de_pago, fecha_registro, fecha_verificacion, referencia) VALUES 
            (:prospecto, :concepto, :detalle_pago, :monto, :retardo, :restante, :saldo, :costototal, :moneda, :numero_pago, :fech_limitepago, :fecha_pago, :promocion, 'verificado', :metodo, :fecha_registro, :fecha_registro, :referencia)";
        $insert_stmt = $conX->prepare($reg_pago);
        $recargo_pagado = 0;
        $id_promocion = null;

        $data_promo = [];
        // var_dump($pago);
        if($pago['Concepto'] == 'Mensualidad'){
            if($pago['SubTotal'] != $beca50){ //  SI EL MONTO QUE VA A PAGAR EL ALUMNO ES DIFERENTE AL LA BECA DEL 50
                // OBTENER EL PORCENTAJE DE LA PROMOCION DEL ALUMNO
                $porcent_promo = ($pago['SubTotal'] * 100) / $montos[$local_concepto];
                $porcent_promo = abs(round($porcent_promo, 2) - 100);
                // CREAR PROMOCION CON EL PORCENTAJE CORRESPONDIENTE

                $registrar_pr = "INSERT INTO promociones (nombrePromocion, tipo, id_concepto, id_prospecto, porcentaje, fechacreado, fechainicio, fechafin) VALUES
                        (:nombre_promo, :tipo, :concepto, :prospecto, :porcentaje, now(), :fecha_inicio, :fecha_fin);";
                $insertar_promo = $conX->prepare($registrar_pr);
                $data_promo = [
                    'nombre_promo' => 'PROMO IMPORT '.$local_concepto.' '.$nombre_alu.' '.round($porcent_promo, 1),
                    'tipo' => 1,
                    'concepto' => $conceptos[$local_concepto],
                    'prospecto' => $idAlumnoMoni,
                    'porcentaje' => $porcent_promo,
                    'fecha_inicio' => $fecha_pago,
                    'fecha_fin' => date("Y-m-d", strtotime("+1 day", strtotime($fecha_pago)))
                ];

                $insertar_promo->execute($data_promo);
                $id_promocion = $conX->lastInsertId();
            }else{ // Si no, la beca es la misma que todos
                $id_promocion = $idBeca50;
            }
        }else{
            if($pago['SubTotal'] != $montos[$local_concepto]){
                // OBTENER EL PORCENTAJE DE LA PROMOCION DEL ALUMNO
                $porcent_promo = ($pago['SubTotal'] * 100) / $montos[$local_concepto];
                $porcent_promo = abs(round($porcent_promo, 2) - 100);
                // CREAR PROMOCION CON EL PORCENTAJE CORRESPONDIENTE

                $registrar_pr = "INSERT INTO promociones (nombrePromocion, tipo, id_concepto, id_prospecto, porcentaje, fechacreado, fechainicio, fechafin) VALUES
                        (:nombre_promo, :tipo, :concepto, :prospecto, :porcentaje, now(), :fecha_inicio, :fecha_fin);";
                $insertar_promo = $conX->prepare($registrar_pr);
                $data_promo = [
                    'nombre_promo' => 'PROMO IMPORT '.$local_concepto.' '.$nombre_alu.' '.round($porcent_promo, 1),
                    'tipo' => 1,
                    'concepto' => $conceptos[$local_concepto],
                    'prospecto' => $idAlumnoMoni,
                    'porcentaje' => $porcent_promo,
                    'fecha_inicio' => $fecha_pago,
                    'fecha_fin' => date("Y-m-d", strtotime("+1 day", strtotime($fecha_pago)))
                ];

                $insertar_promo->execute($data_promo);
                $id_promocion = $conX->lastInsertId();
            }
        }
        // echo "<tr><td> <h3>Promociones:</h3><br>";
        // print_r($data_promo);
        // echo "</td></tr>";
        if($pago['Recargo'] > 0){
            $recargo_pagado = 0;//$pago['Recargo'];
        }
        // $fmt_pago = '';
        // 'conceptonombre'=>$local_concepto,
        $data_insert = [
            'prospecto' => $idAlumnoMoni,
            'concepto' => $conceptos[$local_concepto],
            'detalle_pago' => json_encode($fmt_pago),
            'monto' => $mont_pagado,
            'retardo' => $recargo_pagado,
            'restante' => 0,
            'saldo' => 0,
            'costototal' => $pago['SubTotal'],
            'moneda' => $moneda_alu == 1 ? 'mxn' : 'usd',
            'numero_pago' => $pago['Concepto'] == 'Reinscripción' ? $pago['NumeroPago'] -1 : $pago['NumeroPago'],
            // 'fech_limitepago'=>$pago['Concepto'] == 'Mensualidad' ? $pago['FechaLimitePago'] : $pago['FechaLimitePago'],
            'fech_limitepago'=>$pago['Concepto'] == 'Mensualidad' ? date('Y-m-d', strtotime("+1 month", strtotime($pago['FechaLimitePago']))) : $pago['FechaLimitePago'],
            'fecha_pago' => $fecha_pago,
            'promocion' => $id_promocion,
            'metodo' => $metodo_pago,
            'fecha_registro' => $fe_registro,
            'referencia' => $referencia
        ];
        $insert_stmt->execute($data_insert);
        // echo "<tr><h3>Registro de pago:</h3><br>";
        // print_r($data_insert);
        foreach($data_insert as $col => $td){
            if($col != 'detalle_pago'){
                echo "<td><b>$col :</b><br> $td </td>";
            }
        }
        echo "</tr>";
    }
}else{
        echo "<pre>";
        print_r($pagos);
        echo "</pre>";
    }
// echo json_encode($pagos);