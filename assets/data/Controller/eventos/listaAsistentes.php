<?php 
    require_once '../../Model/conexion/conexion.php';
    $conex = new Conexion();
    $con = $conex->conectar()['conexion'];
    /* $conteoGral = $con->query("SELECT ae.* FROM asistentes_eventos ae
    JOIN instituciones_afiliados iaf ON iaf.id_prospecto = ae.id_asistente
    JOIN a_instituciones ins ON iaf.id_institucion = ins.id_institucion
    WHERE ae.id_evento = 72 AND id_taller IS NULL AND DATE(ae.hora) = '2022-07-12'
    GROUP BY ae.id_asistente;")->fetchAll(PDO::FETCH_ASSOC); */
    $evento = 35;
    $fecha = "2022-01-28";
    $conteoGral = $con->query("SELECT ae.*, ins.nombre, color_n1 FROM asistentes_eventos ae JOIN instituciones_afiliados iaf ON iaf.id_prospecto = ae.id_asistente JOIN a_instituciones ins ON iaf.id_institucion = ins.id_institucion WHERE ae.id_evento = {$evento} AND id_taller IS NULL AND DATE(ae.hora) = '{$fecha}' GROUP BY ae.id_asistente ORDER BY `ins`.`nombre` ASC;")->fetchAll(PDO::FETCH_ASSOC);
    $counter = [];
    $colors = [];
    foreach ($conteoGral as $key => $value) {
        if(!in_array($value['nombre'], array_keys($colors))){
            $colors[$value['nombre']] = $value['color_n1']; 
        }
        if(!in_array($value['nombre'], array_keys($counter))){
            $counter[$value['nombre']] = 1;
        }else{
            $counter[$value['nombre']]++;
        }
    }
    /** */
    
    $fecha = "2022-01-29";
    $conteoGral = $con->query("SELECT ae.*, ins.nombre, color_n1 FROM asistentes_eventos ae JOIN instituciones_afiliados iaf ON iaf.id_prospecto = ae.id_asistente JOIN a_instituciones ins ON iaf.id_institucion = ins.id_institucion WHERE ae.id_evento = {$evento} AND id_taller IS NULL AND DATE(ae.hora) = '{$fecha}' GROUP BY ae.id_asistente ORDER BY `ins`.`nombre` ASC;")->fetchAll(PDO::FETCH_ASSOC);
    $counter_d2 = [];
    foreach ($conteoGral as $key => $value) {
        if(!in_array($value['nombre'], array_keys($counter_d2))){
            $counter_d2[$value['nombre']] = 1;}else{$counter_d2[$value['nombre']]++;}}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <pre>
        <?php #print_r($counter); ?>
    </pre>
    <div class="container">
        <div class="row">
            <div class="col">
                <center><p>12 Julio</p></center>
                <table class="table">
                    <thead>
                        <th>Tipo</th>
                        <th>Conteo</th>
                    </thead>
                    <tbody>
                        <?php $conteoGente = 0; ?>
                        <?php foreach($counter as $valcon => $cont): $conteoGente+=$cont;?>
                        <tr>
                            <td><div class="badge bg-secondary float-right" style="background-color:<?= $colors[$valcon] ?>!important;margin-right:5px;">&nbsp;</div> <?= $valcon ?></td>
                            <td><?= $cont ?></td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td></td>
                            <td><?= $conteoGente ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col">
                <center><p>13 Julio</p></center>
                <table class="table">
                    <thead>
                        <th>Tipo</th>
                        <th>Conteo</th>
                    </thead>
                    <tbody>
                        <?php $conteoGente = 0; ?>
                        <?php foreach($counter_d2 as $valcon => $cont): $conteoGente+=$cont;?>
                        <tr>
                            <td><?= $valcon ?></td>
                            <td><?= $cont ?></td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td></td>
                            <td><?= $conteoGente ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>