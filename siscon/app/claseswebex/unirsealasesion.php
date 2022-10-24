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
        //$sesion_webex=$webex->obtener_sesion_webexota(3);
        if(isset($_GET['evento'])){
            $info_ev = $webex->obtener_sesion_evento($_GET['sesion']);
            $sesion_webex=['data'=>['id_sesion'=>$info_ev['data']['id_sesion'], 'id_clase'=>null, 'idCarrera'=>null, 'contrasena_sesion' => $info_ev['data']['contrasena_sesion']]];
        }else{
            $sesion_webex=$webex->obtener_sesion_webexota($_GET['sesion']);
        }
		
		
        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="JM" />
            <input type="hidden" name="MK" value="<?PHP echo $sesion_webex['data']['id_sesion']; ?>" />
            <input type="hidden" name="PW" value="<?PHP echo $sesion_webex['data']['contrasena_sesion']; ?>" />
            <input type="hidden" name="RID" value="<?PHP echo utf8_encode($_POST['ClaveRegistro']); ?>" />
            <input type="hidden" name="AN" value="<?PHP echo utf8_encode($usuario['data']['nombre']); ?>" /> <!--Nombre del asistente-->
            <input type="hidden" name="BU" value="https://sandbox.conacon.org/siscon/app/claseswebex/entraralasesion.php" />
        </form>
        <script>
            document.getElementById("IDForm").action = "https://universidaddelconde.webex.com/universidaddelconde/m.php";
            document.getElementById("IDForm").submit();
        </script>
        <?PHP
    }
