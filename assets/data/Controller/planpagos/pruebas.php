<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        td{
            white-space: nowrap;
        }
        .monosp{
            font-family:monospace;
            font-weight:bold;
        }
    </style>
</head>
<body>
    
    <?php

    require_once '../../Model/conexion/conexion.php';
    $con = new Conexion();
    $cx = $con->conectar();
    $cx = $cx['conexion'];

    $generacion = 87;
    // $generacion = 22;

    $alumnos = $cx->query("SELECT ag.*, pr.nombre, pr.Apaterno, pr.aMaterno, pr.correo FROM alumnos_generaciones ag 
        JOIN a_prospectos pr ON pr.idAsistente = ag.idalumno
        WHERE ag.idgeneracion = $generacion");

    $generacion_info = $cx->query("SELECT ag.* FROM a_generaciones ag WHERE ag.idGeneracion = $generacion")->fetch(PDO::FETCH_ASSOC);
    $mensualidad = $cx->query("SELECT con_c.* FROM pagos_conceptos con_c WHERE con_c.id_generacion = $generacion AND con_c.categoria = 'Mensualidad'")->fetch(PDO::FETCH_ASSOC);
    $inscripcion = $cx->query("SELECT con_c.* FROM pagos_conceptos con_c WHERE con_c.id_generacion = $generacion AND con_c.categoria = 'Inscripción'")->fetch(PDO::FETCH_ASSOC);

    $inicio_gen = $generacion_info['fecha_inicio'].substr(0,10);
    $primer_mens = substr($inicio_gen, 0, 8).explode('-', $mensualidad['fechalimitepago'])[2];

    if(strtotime($primer_mens) < strtotime($inicio_gen)){
        $primer_mens = date('Y-m-d', strtotime('+1 month', strtotime($primer_mens)));
    }

    echo "<h3>{$generacion_info['nombre']}</h3>";
    echo "<h5><b>Inicio de generacion: </b>{$generacion_info['fecha_inicio']}</h5><br>";

    echo '<table>';
    echo "<thead>
        <th>alumno</th>
        <th>correo</th>
        <th>Primer mensualidad</th>
        <th>Ultima con pago</th>
    </thead>";
    foreach ($alumnos as $a_k => $alumno) {
        echo "<tr>";
        echo "<td title='".json_encode($alumno)."'>{$alumno['nombre']} {$alumno['Apaterno']} {$alumno['aMaterno']}</td>";
        echo "<td>{$alumno['correo']}</td>";
        echo "<td>".($alumno['fecha_primer_colegiatura'] !== null ? $alumno['fecha_primer_colegiatura'] : $primer_mens)."</td>";
        
        
        echo "<td> <div style='max-height:100px;overflow:auto;border: solid .5px;'><table>";
            $ult_pago = $cx->query("SELECT MAX(numero_de_pago) as numero_de_pago FROM a_pagos WHERE id_prospecto = {$alumno['idalumno']} AND id_concepto = {$mensualidad['id_concepto']} AND estatus = 'verificado';")->fetch();
            $lim = 0;
            if($ult_pago['numero_de_pago'] !== null){
                $lim = intval($ult_pago['numero_de_pago']);
            }
            if($lim == 0){ echo "<tr><td>Sin pagos</td></tr>";}
            for($i = 1; $i <= $lim; $i++) {
                $pago_del_alumno = $cx->query("SELECT * FROM a_pagos WHERE id_prospecto = {$alumno['idalumno']} AND id_concepto = {$mensualidad['id_concepto']} AND numero_de_pago = {$i} AND estatus = 'verificado';")->fetchAll(PDO::FETCH_ASSOC);
                echo "<tr>
                        <td>Mensualidad $i</td><td><table>";

                        foreach ($pago_del_alumno as $key => $value) {
                            $k = $key + 1;
                            $json_pago = json_decode($value['detalle_pago'], true);
                            $reportado = $json_pago['purchase_units'][0]['amount']['value'];
                            $tmp_val = $value;
                            unset($tmp_val['detalle_pago']);
                            echo "<tr><td title='".json_encode($tmp_val)."'>Pago: {$k}</td>";
                            echo "<td>[<span class='monosp' title='$reportado'> i </span> Monto pagado:{$value['montopagado']}]</td>";
                            echo "<td>[Recargo cobrado:{$value['cargo_retardo']}]</td>";
                            echo "<td>[Restante:{$value['restante']}]</td>";
                            echo "<td>[Recargo pendiente:{$value['saldo']}]</td></tr>";
                        }
                echo "</table></td></tr>";
            }
        echo "</table></div></td>";
        echo "</tr>";
    }
    echo '</table>';
    ?>
</body>
</html>