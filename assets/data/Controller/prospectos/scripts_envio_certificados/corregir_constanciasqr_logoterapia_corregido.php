<?php

	require_once '../../../Model/conexion/conexion.php';
	$conexion = new Conexion();
	$con = $conexion->conectar();
	$response = [];

	$con = $con["conexion"];
	$vacio = "";
	//$constancias = $con->query('SELECT eat.*, et.nombre FROM ev_asistente_talleres as eat join ev_talleres as et on et.id_taller=eat.id_taller WHERE eat.id_asistente not in (535,534,534,482,533,533,478,482,437,478,432,432,437,385,385,373,373,369,369,341,341,337,337,250,284,284,250,238,239,239,216,238,214,216,213,214,212,213,208,208,212,207,207,167,167,161,161,156,157,157,155,156,154,155,153,153,154,152,152,150,150,149,149,148,148,144,147,147,143,144,142,143,140,142,139,139,140,136,136,130,130,129,129,127,128,128,126,127,125,126,122,122,81,121,121,78,81,77,78,70,70,77,66,69,65,65,64,64,61,62,62,48,61,39,48,33,39,11,11,33,10,10,680,680,682,682,688,688,689,689,690,690,692,692,694,694,695,695,697,697,698,698,699,699,700,700,701,701,702,702,703,703,704,704,705,705,706,706,707,708,708,709,709,710,710,711,711,712,712,715,715,718,718,719,719,720,720,721,721,722,722,724,724,725,725,726,726,727,727,728,728,730,730,732,732,735,735,736,736,737,744,744) ORDER BY id_asistente ASC')->fetchAll(PDO::FETCH_ASSOC);
	//$constancias = $con->query('SELECT * FROM `asistentes_eventos` WHERE id = 1539')->fetchAll(PDO::FETCH_ASSOC);
	$constancias = $con->query('SELECT * 
									FROM asistentes_eventos as ae
									JOIN ev_talleres as et on et.id_taller=ae.id_taller
									WHERE ae.id_evento = 2 and ae.id_taller=2')->fetchAll(PDO::FETCH_ASSOC);

		$i = 1; 
	require_once '../../../functions/constancias.php';
	require_once('../../../functions/fpdf183/fpdf.php');
	foreach ($constancias as $row => $values) {
		////////////////////////////////////////////////////////////
		$plantilla = '';

		
			$plantilla = '../../../functions/plantillas_constancias/logoterapia_corregido.jpg';
		
		// echo "B>>".$values['nombre_reconocimiento']."<br>";
		$persona = $con->query('SELECT * FROM a_prospectos WHERE idAsistente = '.$values['id_asistente'])->fetch(PDO::FETCH_ASSOC);
		if($persona){
			$nombre = $persona['nombre'].' '.$persona['aPaterno'].' '.$persona['aMaterno'];
			
			$nombre_file = $persona['aPaterno'].' '.$persona['aMaterno'].' '.$persona['nombre'];
			
			$nombre_reconocimiento = $values['nombre'].'_'.$nombre.'_'.$values['id_evento'].'_'.$values['id_taller'].'_'.$values['id_asistente'];
			// echo "R>>".$nombre."<br>";
		}else{
			echo "No prospecto: ".$values['id_asistente']."<br>";
		}

		$salida = "../../../../images/constancias/";
		$file = generar_pdf_constancia($plantilla, $nombre, $nombre_reconocimiento, $salida);

		$sql = "UPDATE asistentes_eventos set nombre_reconocimiento='$nombre_reconocimiento' WHERE id_asistente='".$values['id_asistente']."' AND id_evento=2 AND id_taller=2";

		$insert = $con->query($sql);

		require_once("../../../functions/mailer1.php");
            //--CORREO--//
				$HTML =
                    "
		            <div style=\"padding: 5%; background-color: #F8F8F8;\">
		                <div style=\"padding: 3%; background-color: #FFFFFF; border: 1px solid #625B55; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;\">
		                    <div style=\"padding: 3%;\">
		                        <p><img src=\"https://conacon.org/moni/assets/plantillas/img/icon_384.png\" width=\"250px\"/></p>
		                        <p style=\"font-family: Verdana; font-size: 26px; color: #625B55;\">Estimado(a):<br/> <b>".$nombre."</b></p>
		                        <p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: justify;\">Queremos agradecerle por formar parte de nuestro Taller <b>".$values['nombre']."</b>, por lo tanto su <b>Constancia de Asistencia</b> ha sido generada, es por esa raz&oacute;n que adjuntamos el archivo para su descarga.</p>
		                        <p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: center;\">A T E N T A M E N T E</p>
		                        <p style=\"font-family: Verdana; font-size: 20px; color: #625B55; text-align: center;\"><b>Comit&eacute; Organizador</b></p>
		                        <p style=\"font-family: Verdana; font-size: 10px; text-align: justify;\">
		                            <b>Alerta de confidencialidad:</b> Este correo electr&oacute;nico contiene informaci&oacute;n que es para uso exclusivo de la persona o entidad 
		                            cuyo nombre aparece al rubro. Si usted no es el destinatario pretendido de esta comunicaci&oacute;n, est&aacute; formalmente notificado de que 
		                            cualquier uso no autorizado, difusi&oacute;n o copiado de esta nota electr&oacute;nica, as&iacute; como de su contenido textual o adjunto(s), queda 
		                            estrictamente prohibido. Si por equivocaci&oacute;n ha recibido esta comunicaci&oacute;n, b&oacute;rrela y avise inmediatamente por correo electr&oacute;nico 
		                            a la persona arriba mencionada. Gracias por su atenci&oacute;n.
		                        </p>
		                 	</div>
		                </div>
		            </div>
					";
            $email=$persona['correo'];
            $destinatarios = [[$email,"Sistemas"]];
            $asunto = "Constancia_".$values['titulo'];
            $message = "greats";
            $adjunto='../../../../images/constancias/'.$nombre_reconocimiento.'.pdf';
            $result=sendEmailOwn($destinatarios, $asunto, $HTML, $adjunto);
		
		////////////////////////////////////////////////////////////
		$i++;
	}
?>