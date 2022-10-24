<?php
    session_start();
    if(isset($_SESSION["alumno"]) || isset($_GET['android_id_afiliado']) || isset($_SESSION['usuario']))
    {
        //--LO MANDO A REGISTRAR A CISCO WEBEX--//
        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="RM" />
            <input type="hidden" name="MK" value="<?php echo $_GET['id_sesion']; ?>" /> <!--Sesión WebEx-->
            <input type="hidden" name="FN" value="<?php echo $_SESSION['usuario']['persona']['nombres']; ?>" /> <!--Apellidos-->
            <input type="hidden" name="LN" value="<?php echo $_SESSION['usuario']['persona']['aPaterno']; ?>" /> <!--Nombres-->
            <input type="hidden" name="EM" value="<?php echo $_SESSION['usuario']['persona']['email']; ?>" /> <!--Correo-->
            <input type="hidden" name="JT" value="Asistentes" />
            <input type="hidden" name="CY" value="Maestro" /> <!--Carrera-->
            <input type="hidden" name="BU" value="https://sandbox.conacon.org/maestros/claseswebex/respuestawebex.php?sesion=<?php echo $_GET['id_sesion']; ?>" />
        </form>
        <script>
            document.getElementById("IDForm").action = "https://universidaddelconde.webex.com/universidaddelconde/m.php";
            document.getElementById("IDForm").submit();
        </script>
        <?PHP
    }
    else {

        echo 'sin session';
    }
?>