<?php
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/asistentes/asistentesModel.php';
    $asistentes = new Asistentes();

    $accion=@$_POST["action"];

    if (@$_POST["action"]=='obtenerdatosasistente'||@$_POST["action"]=='obtenertalleresasistente') {
        $codigoqr=@$_POST["codigoqr"];
        $codigoqr=json_decode($codigoqr, true);
        $idalumno =$codigoqr['id_persona'];
        $idevento = $codigoqr['id_evento'];
    }
    
    //$codigoqr=explode('-',$codigoqr);
    //$idalumno=$codigoqr[0];
    //$idevento=$codigoqr[1];

    switch ($accion) {
        case 'obtenerdatosasistente':
            $rspta=$asistentes->obtenerasistente($idalumno, $idevento);
            echo json_encode($rspta['data']);
            break;
        case 'obtenerconstancias':
            $emailobtener=$_POST['emailobtener'];
            $emailasistente=$asistentes->emailasistente(trim($emailobtener));
            if($emailasistente['data']){
                $rspta=$asistentes->obtenerconstancias($emailasistente['data']['idAsistente'], $emailasistente['data']['idEvento']);
                echo json_encode($rspta['data']);
            }else{
                echo json_encode([]);
            }
            break;
        case 'obtenergrados':
            session_start();
            $idusuario=$_SESSION['alumno']['id_afiliado'];
            $rspta=$asistentes->obtenergrados($idusuario);
            echo json_encode($rspta['data']);
            break;
        case 'obtenertalleresasistente':       
            $rspta=$asistentes->obtenertalleresasistente($idalumno, $idevento);
            echo json_encode($rspta['data']);

            $nombreasistente=$asistentes->nombreasistente($idalumno, $idevento);

            $nombre=utf8_decode($nombreasistente['data']['nombre'] . " " . $nombreasistente['data']['aPaterno']. " " . $nombreasistente['data']['aMaterno']);
            $nombre_reconocimiento=utf8_decode($nombreasistente['data']['evento'] . "_" .$nombreasistente['data']['nombre'] . " " . $nombreasistente['data']['aPaterno']. " " . $nombreasistente['data']['aMaterno']. "_" . $idalumno.'_'.$idevento);
            require('../../functions/fpdf183/fpdf.php');
            $pdf = new FPDF('L','cm','Letter');
            $pdf->AddPage();
            $pdf->Image('ConstanciaUDC.png',0,0,28,0,'PNG');
            // Nombre y Apellido
            $pdf->SetFont('helvetica','B',35);
            $pdf->Text(6,10,utf8_decode($nombreasistente['data']['nombre'] . " " . $nombreasistente['data']['aPaterno']. " " . $nombreasistente['data']['aMaterno']));
            $pdf->Output('F', '../../../images/constancias/'.$nombre_reconocimiento.'.pdf');

            require("../../functions/mailer.php");
            //--CORREO--//
				$HTML =
                    "
		            <div style=\"padding: 5%; background-color: #F8F8F8;\">
		                <div style=\"padding: 3%; background-color: #FFFFFF; border: 1px solid #625B55; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;\">
		                    <div style=\"padding: 3%;\">
		                        <p><img src=\"http://iesm.com.mx/SCAE/ArchivosParaCorreos/Logos/\" width=\"250px\"/></p>
		                        <p style=\"font-family: Verdana; font-size: 26px; color: #625B55;\">Estimado(a):<br/> <b>".$nombre."</b></p>
		                        <p style=\"font-family: Verdana; font-size: 16px; color: #625B55; text-align: justify;\">Queremos agradecerle por formar parte de nuestro <b>".$nombreasistente['data']['evento']."</b>, por lo tanto su <b>Constancia de Asistencia</b> ha sido generada, es por esa raz&oacute;n que adjuntamos el archivo para su descarga.</p>
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
            $email=$nombreasistente['data']['email'];
            $destinatarios = [[$email,"Sistemas"]];
            $asunto = "Constancia_".$nombreasistente['data']['evento'];
            $message = "greats";
            $adjunto='../../../images/constancias/'.$nombre_reconocimiento.'.pdf';
            $result=sendEmailOwn($destinatarios, $asunto, $HTML, $adjunto);
            $nombre_reconocimiento=utf8_encode($nombre_reconocimiento);

            $registrarasistencia=$asistentes->registrarasistencia(@$_POST["codigoqr"],$nombre_reconocimiento,$idalumno,$idevento,$idalumno.'_'.$idevento);

            break;
        
        default:
            # code...
            break;
    }

}else{
	header('Location: ../../../../index.php');
}
