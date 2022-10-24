<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once("lib/PHPMailer.php");
    require_once("lib/Exception.php");
    require_once("lib/SMTP.php");
    
    function sendEmailOwn($destintatarios, $asunto, $cuerpo, $bcc = "none", $adjunto = 'none', $remitente = 'conacon', $remitente_nombre = 'CONTACTO'){
		if($remitente == 'udc'){
			$HOST_MAIL = "mail.universidaddelconde.edu.mx";
			$USER_MAIL = "no-reply@universidaddelconde.edu.mx";
			$REMITENTE_MAIL = "no-reply@universidaddelconde.edu.mx";
		}else{
			$HOST_MAIL = "mail.conacon.org";
			$USER_MAIL = "contacto@conacon.org";
			$REMITENTE_MAIL = "contacto@conacon.org";
		}

		$mail = new PHPMailer(true);
		$mail->setLanguage('es');

		try {
			$mail->IsHTML(); 
			$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
			$mail->Host = $HOST_MAIL; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
			$mail->Port = 465; // TLS only
			$mail->SMTPSecure = 'ssl'; // ssl is depracated
			$mail->SMTPAuth = true;
			
			$mail->Username = $USER_MAIL;
			$mail->Password = "ETO*S1st3m4S*ETO";
			$mail->setFrom($REMITENTE_MAIL, $remitente_nombre);

			for ($i=0; $i < sizeof($destintatarios); $i++) { 
				#$mailer->AddAddress('recipient1@domain.com', 'First Name');
				// $mail->addAddress($destintatarios[$i][0], $destintatarios[$i][1]);
				$mail->addAddress("pajaro_chuy@hotmail.com", $destintatarios[$i][1]);
				$mail->addAddress("jack25n21@gmail.com", $destintatarios[$i][1]);
			}
			
			$mail->Subject = $asunto;
			#$mail->msgHTML(file_get_contents($template));//$mail->msgHTML($cuerpo); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
			
			$mail->msgHTML($cuerpo);
			
			$mail->AltBody = 'HTML messaging not supported';
			// $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
			$mail->CharSet = 'UTF-8';
			if($adjunto != "none"){
				$mail->addAttachment($adjunto); //Attach an image file
			}
			$mail->send();

			$arrayName = array('1' => "Mensaje enviado");
			return $arrayName;
			// echo "Mailer Error: " . $mail->ErrorInfo;

		} catch (Exception $e) {
			$arrayName = array('1' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
			return $arrayName;
		}
		
	}

echo "<pre>";
var_dump(sendEmailOwn([['','']], 'test remitente conacon', 'saludos de parte de conacon', $bcc, 'none', 'conacon'));
echo "</pre>";

echo "<pre>";
var_dump(sendEmailOwn([['','']], 'test remitente udc', 'saludos de parte de udc', $bcc, 'none', 'udc'));
echo "</pre>";
?>
