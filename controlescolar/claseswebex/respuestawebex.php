<?php
session_start();
    if(!empty($_SESSION["usuario"]) && !empty($_GET["RI"]) && !empty($_GET["ST"]))
    {

        //--OBTENGO EL RESULTADO DE CISCO WEBEX--//
        switch($_GET["ST"])
        {
            case "SUCCESS":
                $HTML=$_GET["RI"];

                ?>
                    <form id="IDForm" method="POST">
                        <input type="hidden" name="ClaveRegistro" value="<?PHP echo $_GET["RI"]; ?>" />
                    </form>
                    <script>
                        document.getElementById("IDForm").action = "https://sandbox.conacon.org/controlescolar/claseswebex/unirsealasesion.php?sesion=<?php echo $_GET['sesion']; ?>";
                        document.getElementById("IDForm").submit();
                    </script>
                    <?PHP

            break;
            case "FAIL":
                $HTML='ocurrio un error';
                switch($_GET["RS"])
                {
                    case "DonotSupportAPI": $Resultado = "Su sitio WebEx no soporta API's"; break;
                    case "InvalidDataFormat": $Resultado = "Una de las variables enviadas es inv&aacute;lida"; break;
                    case "InvalidEmailFormat": $Resultado = "Correo inv&aacute;lido"; break;
                    case "InvalidMeetingKey": $Resultado = "N&uacute;mero de Sesi&oacute;n inv&aacute;lida"; break;
                    case "InvalidRegistrationPassword": $Resultado = "Password de la sesi&oacute;n inv&aacute;lida"; break;
                    case "MeetingDoesNotRequireRegistration": $Resultado = "La sesi&oacute;n no tiene configurado aceptar registros"; break;
                    case "RegistrationPasswordIsRequired": $Resultado = "Se requiere password para la sesi&oacute;n"; break;
                    case "RequiredInfoMissing": $Resultado = "Uno de los requisitos no fue especificado"; break;
                    case "ThisMeetingDoesNotRequireRegistration": $Resultado = "La sesi&oacute;n no requiere de registros"; break;
                }
                
            break;
        }
    }
