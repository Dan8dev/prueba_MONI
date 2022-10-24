<?php
    session_start();
    if(isset($_SESSION["alumno"]))
    {
        $idusuario=$_SESSION['alumno']['id_afiliado'];
        require_once '../data/Model/AfiliadosModel.php';
        require_once '../data/Model/WebexModel.php';
        $porospM = new Afiliados();
        $webex = new Webex();
        $usuario = $porospM->obtenerusuario($idusuario);
        $sesion_webex=$webex->obtener_sesion_webexota(1);

        $numero_clase=@$_GET['clase'];
        $idCarrera=@$_GET['carrera'];
        $fecha_ingreso=date('Y-m-d H:i:s');
        $modalidad='EN LINEA';
        $fecha_hoy=date('Y-m-d');
        $ya_tiene_asistencia_hoy=$porospM->ya_tiene_asistencia_hoy($usuario['data']['idAsistente'],$fecha_hoy,$numero_clase,$idCarrera);
        if ($ya_tiene_asistencia_hoy['data']==false) {
            $guardar_asistencia = $porospM->guardar_asistencia($modalidad,$numero_clase,$idCarrera,$usuario['data']['idAsistente'],$fecha_ingreso);
        }

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
