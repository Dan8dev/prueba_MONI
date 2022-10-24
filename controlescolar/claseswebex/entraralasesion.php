<?php
    session_start();
    if(isset($_SESSION["usuario"]))
    {
        //--OBTENGO EL RESULTADO DE CISCO WEBEX--//
        switch($_GET["ST"])
        {
            case "SUCCESS":
                ?> <script>console.log('espera mientras se inicia sisco webex')</script> <?PHP break;
            case "FAIL":
                switch($_GET["RS"])
                {
                    case "DonotSupportAPI": $Resultado = "Su navegador no soporta Cisco WebEx, por favor int&eacute;ntelo con otro navegador"; break;
                    case "InvalidDataFormat": $Resultado = "Una de las variables enviadas es inv&aacute;lida"; break;
                    case "InvalidEmailAddress": $Resultado = "Correo inv&aacute;lido"; break;
                    case "InvalidMeetingKeyOrPassword": $Resultado = "N&uacute;mero de Sesi&oacute;n inv&aacute;lida"; break;
                    case "InvalidRegistrationID": $Resultado = "Su registro es inv&aacute;lido"; break;
                    case "LoginRequired": $Resultado = "El asistente no ha iniciado sesi&oacute;n desde la p&aacute;gina web para unirse a esta reuni&oacute;n."; break;
                    case "MeetingLocked": $Resultado = "La tutor&iacute;a fue bloqueada, comun&iacute;quese con el Departamento de Sistemas"; break;
                    case "MeetingNotInProgress": $Resultado = "La tutor&iacute;a aun no ha sido abierta (espere un momento m&aacute;s) o en otro caso pudo ya haber terminado"; break;
                    case "MeetingScheduleFail": $Resultado = "La tutor&iacute;a no se pudo programar"; break;
                    case "RegistrationIDIsRequired": $Resultado = "No se registr&oacute; para la tutor&iacute;a de hoy"; break;
                    default: $Resultado = "Error desconocido"; break;
                }
            break;
        }
    }