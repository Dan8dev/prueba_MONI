<?php
session_start();
    if(isset($_SESSION["usuario"]) && !empty($_POST["ClaveRegistro"]))
    {
        //--OBTENGO EL RESULTADO DE CISCO WEBEX--//
        require_once '../../assets/data/Model/conexion/conexion.php';
        require_once '../../assets/data/Model/maestros/maestrosModel.php';
        $maestro = new Maestro();
        //$sesion_webex=$webex->obtener_sesion_webexota(3);
		$sesion_webex=$maestro->obtener_sesion_webexota($_GET['sesion']);
        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="JM" />
            <input type="hidden" name="MK" value="<?PHP echo $_GET['sesion']; ?>" />
            <input type="hidden" name="PW" value="<?PHP echo $sesion_webex['data']['contrasena_sesion']; ?>" />
            <input type="hidden" name="RID" value="<?PHP echo utf8_encode($_POST['ClaveRegistro']); ?>" />
            <input type="hidden" name="AN" value="<?php echo $_SESSION['usuario']['persona']['nombres'].' '.$_SESSION['usuario']['persona']['aPaterno'].' '.$_SESSION['usuario']['persona']['aMaterno']; ?>" /> <!--Nombre del asistente-->
            <input type="hidden" name="BU" value="https://sandbox.conacon.org/controlescolar/claseswebex/entraralasesion.php" />
        </form>
        <script>
            document.getElementById("IDForm").action = "https://universidaddelconde.webex.com/universidaddelconde/m.php";
            document.getElementById("IDForm").submit();
        </script>
        <?PHP
    }
