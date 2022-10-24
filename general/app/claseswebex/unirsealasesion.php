<?php
session_start();
    if(isset($_SESSION["alumno_iesm"]) && !empty($_POST["ClaveRegistro"]))
    {

        $idusuario=$_SESSION["alumno_iesm"]['id_afiliado'];
        require_once '../data/Model/AfiliadosModel.php';
        $porospM = new Afiliados();
        $usuario = $porospM->obtenerusuario($idusuario);
        $sesion_webex=$porospM->obtener_sesion_webex();

        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="JM" />
            <input type="hidden" name="MK" value="<?PHP echo $sesion_webex['data']['id_sesion']; ?>" />
            <input type="hidden" name="PW" value="<?PHP echo $sesion_webex['data']['contrasena_sesion']; ?>" />
            <input type="hidden" name="RID" value="<?PHP echo utf8_encode($_POST['ClaveRegistro']); ?>" />
            <input type="hidden" name="AN" value="<?PHP echo utf8_encode($usuario['data']['nombre']); ?>" /> <!--Nombre del asistente-->
            <input type="hidden" name="BU" value="https://conacon.org/moni/siscon/app/claseswebex/entraralasesion.php" />
        </form>
        <script>
            document.getElementById("IDForm").action = "https://universidaddelconde.webex.com/universidaddelconde/m.php";
            document.getElementById("IDForm").submit();
        </script>
        <?PHP
    }
