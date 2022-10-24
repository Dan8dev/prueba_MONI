<?php
    session_start();
    if(isset($_SESSION["alumno"]))
    {
        $idusuario=$_SESSION['alumno']['id_afiliado'];
        require_once '../data/Model/AfiliadosModel.php';
        $porospM = new Afiliados();
        $usuario = $porospM->obtenerusuario($idusuario);
        $sesion_webex=$porospM->obtener_sesion_webex();

        //--LO MANDO A REGISTRAR A CISCO WEBEX--//
        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="RM" />
            <input type="hidden" name="MK" value="<?PHP echo $sesion_webex['data']['id_sesion']; ?>" /> <!--Sesión WebEx-->
            <input type="hidden" name="FN" value="<?PHP echo $usuario['data']['nombre']; ?>" /> <!--Apellidos-->
            <input type="hidden" name="LN" value="<?PHP echo $usuario['data']['apaterno']; ?>" /> <!--Nombres-->
            <input type="hidden" name="EM" value="<?PHP echo $usuario['data']['email']; ?>" /> <!--Correo-->
            <input type="hidden" name="JT" value="Asistentes" />
            <input type="hidden" name="CY" value="Alumno" /> <!--Carrera-->
            <input type="hidden" name="BU" value="https://conacon.org/moni/siscon/app/claseswebex/respuestawebex.php" />
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
