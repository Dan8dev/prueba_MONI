<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');
    if(isset($_SESSION["alumno"]) || isset($_GET['android_id_afiliado']))
    {
        if(isset($_SESSION['alumno']['id_afiliado'])){
            $idusuario=$_SESSION['alumno']['id_afiliado'];
        }else if(isset($_GET['android_id_afiliado'])){
            $_SESSION['alumno']['id_afiliado'] = $_GET['android_id_afiliado'];
            $idusuario=$_SESSION['alumno']['id_afiliado'];
        }else{
            $_SESSION['alumno']['id_afiliado'] = $_GET['alumno'];
        }
        require_once '../data/Model/AfiliadosModel.php';
        require_once '../data/Model/WebexModel.php';


        $porospM = new Afiliados();
        $webex = new Webex();
        if(!isset($_GET['sesion'])){
            echo "No se ha recibido el ID de la sesión";
            exit;
        }
        $usuario = $porospM->obtenerusuario($idusuario);
        if(isset($_GET['evento'])){
            $info_ev = $webex->obtener_sesion_evento($_GET['sesion']);
            $sesion_webex=['data'=>['id_sesion'=>$info_ev['data']['id_sesion'], 'id_clase'=>null, 'idCarrera'=>null]];
        }else{
            $sesion_webex=$webex->obtener_sesion_webexota($_GET['sesion']);
        }
        if(!$sesion_webex['data']){
            echo "El ID de la sesión no existe";
            exit;
        }
        $numero_clase = $sesion_webex['data']['id_clase'];
        $idCarrera    = $sesion_webex['data']['idCarrera'];
        $fecha_ingreso=date('Y-m-d H:i:s');
        $modalidad='EN LINEA';
        $fecha_hoy=date('Y-m-d');
        // registrar asistencia individual
        if(isset($_GET['evento'])){
            $ya_tiene_asistencia_hoy=$porospM->ya_tiene_asistencia_hoy_evento($usuario['data']['idAsistente'],$fecha_hoy,$_GET['evento']);
        }else{
            $ya_tiene_asistencia_hoy=$porospM->ya_tiene_asistencia_hoy($usuario['data']['idAsistente'],$fecha_hoy,$numero_clase,$idCarrera);
        }
        
        if ($ya_tiene_asistencia_hoy['data']==false) {
            if(isset($_GET['evento'])){
                $porospM->registrar_asistencia_evento($modalidad, $_GET['evento'], $usuario['data']['idAsistente'], $fecha_ingreso);
            }else{
                $guardar_asistencia = $porospM->guardar_asistencia($modalidad,$numero_clase,$idCarrera,$usuario['data']['idAsistente'],$fecha_ingreso);
            }
        }

        # Registrar asistencia como clinica para los pacientes 
        if(isset($usuario['data']['estatus']) && $usuario['data']['estatus'] == 10 && isset($_GET['evento']) && isset($_GET['flag']) && $_GET['flag'] == 1){
            // buscar pacientes que esten apuntados para este evento
            $apuntados = $porospM->consultar_pacientes_encargado_evento($usuario['data']['idAsistente'], $_GET['evento']);
            foreach($apuntados as $paciente){
                $asisten_hoy=$porospM->ya_tiene_asistencia_hoy_evento($paciente['prospecto'], $fecha_hoy, $_GET['evento']);
                if($asisten_hoy['data'] === false){
                    $porospM->registrar_asistencia_evento('ENCARGADO', $_GET['evento'], $paciente['prospecto'], $fecha_ingreso);
                }
            }
        }
        //--LO MANDO A REGISTRAR A CISCO WEBEX--//
        ?>
        <form id="IDForm" method="POST">
            <input type="hidden" name="AT" value="RM" />
            <input type="hidden" name="MK" value="<?PHP echo $sesion_webex['data']['id_sesion']; ?>" /> <!--Sesión WebEx-->
            <input type="hidden" name="FN" value="<?PHP echo $usuario['data']['apaterno'].' '.$usuario['data']['amaterno']; ?>" /> <!--Apellidos-->
            <input type="hidden" name="LN" value="<?PHP echo $usuario['data']['nombre']; ?>" /> <!--Nombres-->
            <input type="hidden" name="EM" value="<?PHP echo $usuario['data']['email']; ?>" /> <!--Correo-->
            <input type="hidden" name="JT" value="Asistentes" />
            <input type="hidden" name="CY" value="Alumno" /> <!--Carrera-->
            <input type="hidden" name="BU" value="https://sandbox.conacon.org/siscon/app/claseswebex/respuestawebex.php?sesion=<?php echo $_GET['sesion']; ?>&android_id_afiliado=<?php echo $idusuario; ?><?php echo (isset($_GET['evento']) ? '&evento='.$_GET['evento'] : '');  ?>" />
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
