<?php
session_start();
    if(isset($_SESSION["alumno"]) && !empty($_POST["ClaveRegistro"]))
    {

        $idusuario=$_SESSION['alumno']['id_afiliado'];
        require_once '../data/Model/AfiliadosModel.php';
        require_once '../data/Model/WebexModel.php';
        $porospM = new Afiliados();
        $webex = new Webex();
        $usuario = $porospM->obtenerusuario($idusuario);
        $sesion_webex=$webex->obtener_sesion_webexlogo(2);

        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="JM" />
            <input type="hidden" name="MK" value="<?PHP echo $sesion_webex['data']['id_sesion']; ?>" />
            <input type="hidden" name="PW" value="<?PHP echo $sesion_webex['data']['contrasena_sesion']; ?>" />
            <input type="hidden" name="RID" value="<?PHP echo utf8_encode($_POST['ClaveRegistro']); ?>" />
            <input type="hidden" name="AN" value="<?PHP echo utf8_encode($usuario['data']['nombre']); ?>" /> <!--Nombre del asistente-->
            <input type="hidden" name="BU" value="https://conacon.org/moni/siscon/app/sesioneswebex/entraralasesion.php" />
        </form>
        <script>
            document.getElementById("IDForm").action = "https://universidaddelconde.webex.com/universidaddelconde/m.php";
            document.getElementById("IDForm").submit();
        </script>
        <?PHP
    }
