<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once("lib/PHPMailer.php");
    require_once("lib/Exception.php");
    require_once("lib/SMTP.php");
    
    function sendEmailOwn($destintatarios, $asunto, $cuerpo, $adjunto){
	$mail = new PHPMailer(true);
	$mail->setLanguage('es');

	try {
		$mail->isSMTP(); 
		$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
		$mail->Host = "mail.iesm.com.mx"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
		$mail->Port = 465; // TLS only
		$mail->SMTPSecure = 'ssl'; // ssl is depracated
		$mail->SMTPAuth = true;
		
		$mail->Username = "sistemas@iesm.com.mx";
		$mail->Password = "ETO*S1st3m4S*ETO";
		$mail->setFrom("notificaciones@conacon.org", "GESTION DE EVENTOS CONACON");

		for ($i=0; $i < sizeof($destintatarios); $i++) { 
			#$mailer->AddAddress('recipient1@domain.com', 'First Name');
			$mail->addAddress($destintatarios[$i][0], $destintatarios[$i][1]);
		}
		/* if($bcc !== "none"){
			$mail->addBCC($bcc);
		} */
		
		$mail->Subject = $asunto;
		#$mail->msgHTML(file_get_contents($template));//$mail->msgHTML($cuerpo); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
        
        $mail->msgHTML($cuerpo);
		//$mail ->Body = $html;
        
		$mail->AltBody = 'HTML messaging not supported';
		$mail->addAttachment($adjunto); //Attach an image file
		$mail->CharSet = 'UTF-8';

		// $mail->send();

		$arrayName = array('1' => "Mensaje enviado");
	    return $arrayName;
	    // echo "Mailer Error: " . $mail->ErrorInfo;

	} catch (Exception $e) {
		$arrayName = array('1' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
	    return $arrayName;
	}
	
}

#print_r(scandir('../../../../bunker/correo/src/'));
?>