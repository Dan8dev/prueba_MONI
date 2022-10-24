<?php
	require_once '../../Model/conexion/conexion.php';
	$conexion = new Conexion();
	$con = $conexion->conectar();
	$response = [];

	$con = $con["conexion"];
	$idEvento = $_POST['evento'];
	$evento = $con->query("SELECT * FROM ev_evento WHERE idEvento = ".$idEvento)->fetch(PDO::FETCH_ASSOC);
	if(!$evento){
		echo json_encode(['estatus'=>'error', 'info'=>'Evento no identificado']);
		die();
	}
	if(empty($_POST['ids'])){
		echo json_encode(['estatus'=>'error', 'info'=>'No se recibió ningúna parsona para enviar constancia']);
		die();
	}

	$ids = implode(', ', $_POST['ids']);
	$constancias_enviar = $con->query('SELECT * FROM a_prospectos WHERE idAsistente in ('.$ids.')')->fetchAll(PDO::FETCH_ASSOC);
	$i = 1; 

	require_once '../../functions/constancias.php';
	require_once('../../functions/fpdf183/fpdf.php');

	foreach ($constancias_enviar as $row => $values) {
		$plantilla = '';

		$plantilla = '../../functions/plantillas_constancias/'.$evento['plantilla_constancia'];

		$nombre = $values['nombre'].' '.$values['aPaterno'].' '.$values['aMaterno'];
		$nombre_file = $values['aPaterno'].' '.$values['aMaterno'].' '.$values['nombre'];
		$nombre_reconocimiento = $nombre.'_'.$values['idAsistente'].'_'.$evento['nombreClave'];

		$fecha = date('Y-m-d H:i:s');
		$salida = "../../../images/constancias/";

		$file = generar_pdf_constancia($plantilla, $nombre, $nombre_reconocimiento, $salida, 11);
		$id_evento = 2;

		$sql = "UPDATE asistentes_eventos SET nombre_reconocimiento = '{$nombre_reconocimiento}', folio = 'enviado' WHERE id_asistente = {$values['idAsistente']} AND id_evento = {$idEvento}";

		$insert = $con->query($sql);

		require_once("../../functions/mailer1.php");
            //--CORREO--//
				$HTML =
                    "
		            <div style=\"padding: 5%; background-color: #F8F8F8;\">
		                <div style=\"padding: 3%; background-color: #FFFFFF; border: 1px solid #625B55; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;\">
		                    <div style=\"padding: 3%;\">
		                        <p><img src=\"https://www.iesm.com.mx/i/wp-content/uploads/2020/07/Logo_Stick.png\" width=\"250px\"/></p>
		                        <p style=\"font-family: Verdana; font-size: 26px; color: #625B55;\">Estimado(a):<br/> <b>".$nombre."</b></p>
		                        <p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: justify;\">Queremos agradecerle por formar parte de este ". strtolower($evento['tipo'])." ".$evento['titulo'].", por lo tanto su <b>Constancia de Asistencia</b> ha sido generada, es por esa raz&oacute;n que adjuntamos el archivo para su descarga.</p>
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
            // $destinatarios = [ [ $email , $values['nombre'] ] ];
            $destinatarios = [ [ 'pajaro_chuy@hotmail.com' , $values['nombre'] ] ];
            $asunto  = "Constancia ".$values['nombre'];
            $message = "greats";
            $adjunto = $salida.$nombre_reconocimiento.'.pdf';
			file_put_contents(time().$values['idAsistente'].'.html', $HTML);
            $result=sendEmailOwn($destinatarios, $asunto, $HTML, $adjunto);		
		$i++;
	}
	echo json_encode(['estatus'=>'ok','data'=>'Reconocimientos Enviados']);
?>