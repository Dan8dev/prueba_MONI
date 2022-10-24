<?php 
    require_once '../../Model/conexion/conexion.php';
    require_once '../../functions/correos_prospectos.php';
    $conexion = new Conexion();
    $con = $conexion->conectar()['conexion'];
    /** TSU
     * gen 14 = 19
     * gen 15 = 20
     * gen 16 = 21
     */
    /** MEDICINA
     * gen 13 = 52
     * gen 14 = 53
     * gen 15 = 54
     * gen 16 = 129
     */
    $generaciones = [];
    
    // $generaciones = [110];
    $string_gen = implode(', ', $generaciones);
	$prospecto = 21;
	
    echo "Generaciones: (".$string_gen.") <br><br>";
    $alumnos = $con->query("
        SELECT prs.nombre, AES_DECRYPT(afc.contrasenia, 'SistemasPUE21') as contrasendec, prs.correo, afc.id_prospecto  FROM afiliados_conacon afc
        JOIN a_prospectos prs ON prs.idAsistente = afc.id_prospecto
        JOIN alumnos_generaciones age ON age.idalumno = afc.id_prospecto
		
        WHERE prs.idAsistente = ".$prospecto." GROUP BY prs.idAsistente
    ")->fetchAll(PDO::FETCH_ASSOC);
	/*WHERE prs.idAsistente = ".$prospecto." GROUP BY prs.idAsistente*/
    /*WHERE age.idgeneracion IN (".$string_gen.") AND afc.clase = '' LIMIT 60*/
	
    $claves = ['%%prospecto',
            '%%USUARIO',
            '%%CONTRASENIA'];
    $log_envios = file_get_contents('usuario_contrasenias.txt');
    $enviados = "";
    foreach ($alumnos as $key => $value) {
        echo $value['nombre']." ".$value['contrasendec']."<br>";
        $valores = [$value['nombre'], $value['correo'], $value['contrasendec']];
		$adjunto = "Guia_alumnos.pdf";
        enviar_correo_registro('Envío de accesos', [[$value['correo'], $value['nombre']]], 'carreras/nueva_plantilla_udc_manual.html', $claves, $valores, $adjunto);
		
        $con->query("UPDATE afiliados_conacon SET clase = '".date('Y-m-d H:i:s')."' WHERE id_prospecto = ".$value['id_prospecto']);
        $enviados.= "[".$value['id_prospecto'].",\t".$value['contrasendec']."] \n";
    }
    file_put_contents('usuario_contrasenias.txt', $log_envios.$enviados);
?>