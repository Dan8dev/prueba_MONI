<?php

//envío y generacion de cerfificados a los prospectos sin consultar ni guardar registrosen la base de datos
	require_once '../../Model/conexion/conexion.php';
	
	$lista = ['ANTONIO ALEJANDRO FUERTE NUÑO','JOSE MANUEL MONTOYA HERRERA','BRYAN NAZARETH GUITIERREZ GONZALEZ','ALAN ALEJANDRO ORTIZ ALFEREZ','JOSE JUAN FLORES PEREZ'];




	
	//print_r($usuarios);

		$i = 1; 

	require_once '../../functions/constancias.php';
	require_once('../../functions/fpdf183/fpdf.php');
	foreach ($lista as $row => $values) {
		
		////////////////////////////////////////////////////////////
		$plantilla = '';

			$plantilla = '../../functions/plantillas_constancias/reconocimientogeneral.jpg';
		
	
		$nombre_reconocimiento = $values.$i;
		$salida = "../../../images/constancias/";
		$file = generar_pdf_constancia($plantilla, $values, $nombre_reconocimiento, $salida);


		require_once("../../functions/mailer1.php");
            //--CORREO--//
				$HTML =
                    "
		            <div style=\"padding: 5%; background-color: #F8F8F8;\">
		                <div style=\"padding: 3%; background-color: #FFFFFF; border: 1px solid #625B55; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;\">
		                    <div style=\"padding: 3%;\">
		                        <p><img src=\"https://conacon.org/moni/assets/plantillas/img/icon_384.png\" width=\"250px\"/></p>
		                        <p style=\"font-family: Verdana; font-size: 26px; color: #625B55;\">Estimado(a):<br/> <b>".$values."</b></p>
		                        <p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: justify;\">Queremos agradecerle por formar parte de nuestro evento <b>CISMAC 2021</b>, por lo tanto su <b>Constancia de Asistencia</b> ha sido generada, es por esa raz&oacute;n que adjuntamos el archivo para su descarga.</p>
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
            $email="jorgegbp@gmail.com";
            $destinatarios = [[$email,"Sistemas","jrgbp@hotmail.com"]];
            $asunto = "Constancia_".$values;
            $message = "greats";
            $adjunto='../../../images/constancias/'.$nombre_reconocimiento.'.pdf';
            $result=sendEmailOwn($destinatarios, $asunto, $HTML, $adjunto);
		
		////////////////////////////////////////////////////////////
		$i++;
	}
?>