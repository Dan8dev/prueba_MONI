<?php
date_default_timezone_set("America/Mexico_City");

if (isset($_POST["action"])) {
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/alumnos/alumnosInstitucionesModel.php';

    require_once '../../Model/eventos/eventosModel.php';

    $asistentesM = new AccesosAlumnosInstituciones();

    $evt = new Evento();

    $tipos_interno = [
        ['id_interno'=>19, 'nombre'=>'IESM',         'id_ext' => 1],
        ['id_interno'=>20, 'nombre'=>'UDC',          'id_ext' => 6],
        ['id_interno'=>21, 'nombre'=>'IESM_ESP',     'id_ext' => 7],
        ['id_interno'=>22, 'nombre'=>'REF',          'id_ext' => 3],
        ['id_interno'=>23, 'nombre'=>'EX-ALUMNO',    'id_ext' => 2],
        ['id_interno'=>24, 'nombre'=>'NUEVO-INGRESO','id_ext' => 8],
        ['id_interno'=>25, 'nombre'=>'PUBLICO-GENERAL', 'id_ext' =>9]
    ];
    
    switch ($_POST['action']) {
        case 'validar_acceso_institucion':
            $resp =[];
            $info_alumn = [];
			$_POST['usr_name'] = strtolower($_POST['usr_name']);
			$_POST['usr_name'] = str_replace(" ", "", $_POST['usr_name']);
            if((isset($_POST['usr_name']) && isset($_POST['usr_pass'])) && (trim($_POST['usr_name']) != '' && trim($_POST['usr_pass']) != '')){
                $info_alumn = $asistentesM->validar_acceso_institucion($_POST['usr_name'], $_POST['usr_pass']);
                if($info_alumn['estatus'] == 'error' && $info_alumn['info'] != 'El usuario no existe'){
                    echo json_encode($info_alumn);
                }else if($info_alumn['estatus'] == 'here'){
                        // consultar a base SCAE
                    //$info_scae = $asistentesM->ConsultarCorreo_pagosSCAE($_POST['usr_name']);
                    
                    $info_scae['data'] = $info_alumn['info']['data'];
                     
                    if($info_scae['data']){
                        // si hay respuesta guardar el alumno
                        $info_scae = $info_scae['data'];
                        $ix_i = array_search(intval($info_scae['IDTipoAsistente']), array_column($tipos_interno, 'id_ext'));
                        $evento_info = $evt->consultarEvento_Clave('xxv_congreso_medicina_2022');

                        // var_dump($evento_info['data'][0]['idEvento']);
                        // die();
                        $data_prosp = [
                            'evento' => $evento_info['data'][0]['idEvento'],
                            'carrera' => null,
                            'nombre' => $info_scae['nombrealumno'],
                            'paterno' => $info_scae['paternoalumno'],
                            'materno' => $info_scae['maternoalumno'],
                            'genero' => $info_scae['Genero'],
                            'correo' => $info_scae['Correo'],
                            'telefono' => (trim($info_scae['Celular']) != '') ? $info_scae['Celular'] : $info_scae['TelefonoParticular'],
                            'registro' => date("Y-m-d H:i:s"),
                            'tipo_moneda_prospecto'=>'MXN'];
                            
                        $registrar = $asistentesM->RegistrarAlumnoInstitucion($data_prosp, $tipos_interno[$ix_i]['id_interno']);
                        
                        $info_alumn = $asistentesM->validar_acceso_institucion($_POST['usr_name'], $_POST['usr_pass']);
                        session_start();

                        if(sizeof($info_alumn['data']['instituciones']) > 0){
                            foreach ($info_alumn['data']['instituciones'] as $key => $value) {
                                $_SESSION['alumno_'.strtolower($info_alumn['data']['panel_url'])] = $info_alumn["data"];
                            }
                        }
                        if(!file_exists('../../../../udc/app/img/afiliados/'.$info_alumn['data'][0]['foto']) || $info_alumn['data'][0]['foto'] == 'defaultfoto.jpg'){
                            $info_alumn['data'][0]['foto'] = 'doc.png';
                        }
                        echo json_encode($info_alumn);
                    }else{
                        $resp = ['estatus'=>'error','info'=>'Aun no se han verificado todos sus pagos'];
                        echo json_encode($resp);
                    }
                }else{
                    $resp = $info_alumn['data'];
                        if($info_alumn["data"]){
                            session_start();
                            if(sizeof($info_alumn['data'][0]['instituciones']) > 0){
                                foreach ($info_alumn['data'][0]['instituciones'] as $key => $value) {
                                    $_SESSION['alumno_'.strtolower($info_alumn['data'][0]['panel_url'])] = $info_alumn["data"];
                                }
                            }
                        }
                        if(!file_exists('../../../../udc/app/img/afiliados/'.$info_alumn['data'][0]['foto']) || $info_alumn['data'][0]['foto'] == 'defaultfoto.jpg'){
                            $info_alumn['data'][0]['foto'] = 'doc.png';
                        }
                        echo json_encode($info_alumn);
                }
            }else{
                $resp = ['estatus'=>'error','info'=>'Faltan campos por rellenar'];
                echo json_encode($resp);
            }
            // echo json_encode($resp);
            break;
        case 'buscar_alumno':
            $resp =[];
            if(isset($_POST['nombre']) && trim($_POST['nombre']) != ''){
                $resp = $asistentesM->buscar_alumno($_POST['nombre']);
            }
            echo json_encode($resp);
            break;
        case 'concentrado_alumnos_institucion':
            echo json_encode($asistentesM->concentrado_alumnos_institucion());
            break;
        case 'asistentes_scae':
            echo json_encode($asistentesM->asistentes_scae());
            break;
        case 'asistentes_scae_match':
            $data1 = $asistentesM->concentrado_alumnos_institucion()['data'];
            $data2 = $asistentesM->asistentes_scae()['data'];
            foreach ($data2 as $key => $value) {
                $ix_c = array_search($value['Correo'], array_column($data1, 'correo'));
                if($ix_c){
                    $data2[$key]['prospecto'] = $data1[$ix_c]['idAsistente'];
                    $data2[$key]['color'] = $data1[$ix_c]['color_n1'];
                }else{
                    $data2[$key]['prospecto'] = null;
                    $data2[$key]['color'] = null;
                }
            }
            echo json_encode($data2);
            break;
        default:
            # code...
            break;
    }

}else{
	header('Location: ../../../../index.php');
}
?>
