<?php  
    header('Access-Control-Allow-Origin: *', false);
    session_start();
    $android_id_afiliado = @$_POST['android_id_afiliado'];
    if(isset($_SESSION['alumno']) || isset($_POST['android_id_afiliado'])){
        date_default_timezone_set("America/Mexico_City");

        require "../Model/WebexModel.php";

        $Asist = new Webex();

        $accion = @$_POST["action"];

        
        $idusuario=isset($_POST['android_id_afiliado']) ? $_POST['android_id_afiliado'] : $_SESSION['alumno']['id_afiliado'];
        if(isset($_POST['android_id_afiliado'])){
            unset($_POST['android_id_afiliado']);
        }

        switch($accion){
            case 'registrarAsistencia':
                unset($_POST["action"]);
                $id_asistente = $_POST["idAlumno"];
                $id_evento = $_POST["id_evento"];
                $modalidad = "EN LINEA";
                $fecha = date("Y-m-d H:i:s");
                $folio = "";
                $validar = $Asist->ya_tieneregistro_evento($id_asistente, $id_evento);
                if(isset($validar["data"]) && $validar["data"] == 0){
                    $primerAsist=$Asist->registrarAsistencia($id_asistente, $id_evento, $modalidad, $fecha, $folio);
                }else{
                    $primerAsist = ["estatus"=>"error", "info"=>"El alumno ya cuenta con asistenca anterior"];
                }
                echo json_encode($primerAsist);
                break;
            case 'updateTimeEvent':
                unset($_POST["action"]);
                $addTime = $Asist->updateTimeEvent($_POST);
                echo json_encode($addTime);
                break;
                
            default:
                echo "no action";
                break;
        }
    }
?>