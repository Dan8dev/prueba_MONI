<?php

	#envio de certificados a alumnos que estan registrados como prospectos pero no tienen asistencia a eventos ni talleres se manda correo y se guarda el certificado
	require_once '../../Model/conexion/conexion.php';
	$conexion = new Conexion();
	$con = $conexion->conectar();
	$response = [];

	$con = $con["conexion"];

	$constancias = $con->query('SELECT * FROM a_prospectos WHERE idAsistente in (209)')->fetchAll(PDO::FETCH_ASSOC);
	//$constancias = $con->query('SELECT * FROM `asistentes_eventos` WHERE id = 1539')->fetchAll(PDO::FETCH_ASSOC);
	
		$i = 1; 

	require_once '../../functions/constancias.php';
	require_once('../../functions/fpdf183/fpdf.php');
	foreach ($constancias as $row => $values) {
		////////////////////////////////////////////////////////////
		$plantilla = '';

		
			$plantilla = '../../functions/plantillas_constancias/reconocimientogeneral.jpg';
			//diplomatsu.jpg
		
		// echo "B>>".$values['nombre_reconocimiento']."<br>";
			$nombre = $values['nombre'].' '.$values['aPaterno'].' '.$values['aMaterno'];
			
			$nombre_file = $values['aPaterno'].' '.$values['aMaterno'].' '.$values['nombre'];
			
			$nombre_reconocimiento = $nombre.'_'.$values['idAsistente'].'_'.$i.' cismac';
			// echo "R>>".$nombre."<br>";

		$fecha=date('Y-m-d H:i:s');
		$salida = "../../../images/constancias/";
		$file = generar_pdf_constancia($plantilla, $nombre, $nombre_reconocimiento, $salida);
		$id_evento = 2;
		$sql = "INSERT INTO asistentes_eventos (nombre_reconocimiento, id_asistente, id_evento,hora) VALUES ('".$nombre_reconocimiento."','".$values['idAsistente']."','".$id_evento."','".$fecha."')";

		$insert = $con->query($sql);

		require_once("../../functions/mailer1.php");
            //--CORREO--//
				$HTML =
                    "
		            <div style=\"padding: 5%; background-color: #F8F8F8;\">
		                <div style=\"padding: 3%; background-color: #FFFFFF; border: 1px solid #625B55; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;\">
		                    <div style=\"padding: 3%;\">
		                        <p><img src=\"https://conacon.org/moni/assets/plantillas/img/icon_384.png\" width=\"250px\"/></p>
		                        <p style=\"font-family: Verdana; font-size: 26px; color: #625B55;\">Estimado(a):<br/> <b>".$nombre."</b></p>
		                        <p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: justify;\">Queremos agradecerle por formar parte de nuestro evento CISMAC 2021, por lo tanto su <b>Constancia de Asistencia</b> ha sido generada, es por esa raz&oacute;n que adjuntamos el archivo para su descarga.</p>
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
            $email=$values['correo'];
            $destinatarios = [[$email,"Sistemas"]];
            $asunto = "Constancia_".$values['nombre'];
            $message = "greats";
            $adjunto='../../../images/constancias/'.$nombre_reconocimiento.'.pdf';
            $result=sendEmailOwn($destinatarios, $asunto, $HTML, $adjunto);
		
		////////////////////////////////////////////////////////////
		$i++;
	}
?>