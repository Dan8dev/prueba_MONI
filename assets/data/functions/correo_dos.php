<?php
    require("mailer_dos.php");
    /*$destinatarios = [["sistemas@somosgrupoemprende.com","sistemas"]];
    $asunto = "Prueba de correo";
    
    $message = file_get_contents("plantilla_recordatorio.html");
    $replace = [ ["%SOCIO","JESUS"],["%VENCIMIENTO","14 DE ABRIL"]];
    for($i = 0; $i < sizeof($replace); $i++){
        $message = str_replace($replace[$i][0], $replace[$i][1], $message);    
    }
    
    $cuerpo = $message;

    print_r(sendEmailOwn($destinatarios, $asunto, $cuerpo));*/
    $destinatarios = [["crystallfox@hotmail.es","Sistemas"]];
    $asunto = "Prueba de correo";
    $message = "greats";
    print_r(sendEmailOwn($destinatarios, $asunto, $message, "none", "none", "udc", "CONTACTO"));
?>