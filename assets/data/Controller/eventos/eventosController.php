<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/eventos/creareventosModel.php';

    $eventos = new eventos();
    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }

    switch($_POST['action']){

        case 'buscarPaises':
            unset($_POST['action']);
            $pa = $eventos->buscarPaises();
            $pa = $pa['data'];
            echo json_encode($pa);
            break;

        case 'buscarEstados':
            unset($_POST['action']);
            $estado = $eventos->buscarEstados($_POST['idPais']);
            $estado = $estado['data'];
            echo json_encode($estado);
            break;

        case 'buscarInstituciones':
            unset($_POST['action']);
            $Inst = $eventos->buscarInstituciones();
            $Inst = $Inst['data'];
            echo json_encode($Inst);
            break;

        case 'buscarPlantillas':
            unset($_POST['action']);
            $plan = $eventos->buscarPlantillas();
            $plan = $plan['data'];
            echo json_encode($plan);
            break;

        case 'registrarEvento':
            $resp = $eventos->buscarNombreClave($_POST['nombreClave']);

            if($resp['data'] != 1){
                unset($_POST['action']);
                $formatosImg = array('jpg', 'jpeg', 'png');
                if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK
                    && isset($_FILES['imgFondo']) && $_FILES['imgFondo']['error'] == UPLOAD_ERR_OK){
                    
                        $tmp_name = $_FILES['imagen']['tmp_name'];
                        $uploads_dir = "../../../images/generales/flyers";

                        $tmp_name2 = $_FILES['imgFondo']['tmp_name'];
                        $uploads_dir2 = "../../../images/generales/fondos";

                        //$fileT = explode('.', $_FILES['imagen']['name'])[1];
                        //$fileT2 = explode('.', $_FILES['imgFondo']['name'])[1];
                        $fileT = explode('.', $_FILES['imagen']['name']);
                        $extensionI = $fileT[sizeof($fileT)-1];
                        $fileT2 = explode('.', $_FILES['imgFondo']['name']);
                        $extensionF = $fileT2[sizeof($fileT2)-1];
                        
                        /*
                        $fileT = explode('.', $_FILES['newImagen']['name']);
                        $extensionI = $fileT[sizeof($fileT)-1];

                        $fileT2 = explode('.', $_FILES['newFondo']['name']);
                        $extensionF = $fileT2[sizeof($fileT2)-1];*/

                        if(in_array($extensionI, $formatosImg)){
                            if(in_array($extensionF, $formatosImg)){
                                if(!isset($_POST['estado'])){
                                    //echo 'No';
                                    $_POST['estado'] = "0";
                                }
                                //echo 'Si';
                                //echo '-----';
                                
                                $nName = 'imagenEvento'.rand().'.'.$extensionI;
                                $nNameF = 'fondoEvento'.rand().'.'.$extensionF;
                                //$nName = 'imagenEvento'.rand().'.'.$fileT;
                                //$nNameF = 'fondoEvento'.rand().'.'.$fileT2;
                                //$nNameF = 'fondoEvento'.date('h:i:s').'.'.$fileT2;

                                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                                $statFile2 = move_uploaded_file($tmp_name2, "$uploads_dir2/$nNameF");

                                $_POST['nName'] = $nName;
                                $_POST['nNameF'] = $nNameF;
                                
                                $evento = $eventos->registrarEvento($_POST);
                                echo json_encode($evento);
                            }else{
                                echo 'no_format';
                            }
                        }else{
                            echo 'no_format';
                        }
                    }
            }else{
                echo $resp['data'];
            }
            break;

        
        case 'consultarEventos':
            
            unset($_POST['action']);
            $csul = $eventos->consultarEventos();
            $data = Array();
            while($dato=$csul->fetchObject()){
                $data[]=array(
                0=> $dato->idEvento,
                1=> $dato->tipo,
                2=> $dato->titulo,
                3=> 'https://moni.com.mx/eventos/?e='.$dato->nombreClave,
                4=> $dato->fechaE,
                5=> $dato->fechaDisponible,
                6=> $dato->fechaLimite,
                7=> $dato->limiteProspectos,
                8=> $dato->duracion,
                9=> $dato->tipoDuracion,
                10=> $dato->direccion,
                11=> $dato->estado_nom,
                12=> $dato->pais_nom,
                13=> $dato->codigoPromocional,
                14=> $dato->estatus,
                15=> $dato->modalidadEvento,
                16=> $dato->idInstitucion,
                17=> $dato->imagen,
                18=> $dato->imgFondo,
                19=> $dato->descripcion,
                20=> $dato->plantilla_bienvenida,
                21=>'<a class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="buscarEvento('.$dato->idEvento.')">Modificar</a>',
                22=>'<a class="btn btn-danger" onclick="validarEliminar('.$dato->idEvento.')">Eliminar</a>'
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case 'buscarEvento':
            unset($_POST['action']);
            $bus = $eventos->buscarEvento($_POST['idEditar']);
            $bus['data'][0]['fechaDisponible'] = date("Y-m-d", strtotime($bus['data'][0]['fechaDisponible']));
            $bus['data'][0]['fechaLimite'] = date("Y-m-d", strtotime($bus['data'][0]['fechaLimite']));
            if($bus['data'][0]['video_url'] == ""){
                $bus['data'][0]['video_url'] = "{}";
            }
            echo json_encode($bus);
            break;
        
        case 'modificarEvento':
            unset($_POST['action']);
            $rsp = $eventos->buscarDevClave($_POST['devClave'], $_POST['idModify']);
            $estatus_upd = false;
            if(isset($_POST['upd_estatus'])){
                $estatus_upd = $_POST['upd_estatus'];
                unset($_POST['upd_estatus']);
            }
            if($rsp['data'] == 1){
                echo $rsp['data'];
            }else{
            $formatosImg = array('jpg', 'jpeg', 'png');
                $_POST['enlaces'] = '';
                if(isset($_POST['inp_enlaces_titulo']) && $_POST['inp_enlaces_titulo'] != '' && isset($_POST['inp_enlaces_url']) && $_POST['inp_enlaces_url'] != ''){
                    $_POST['enlaces'] = json_encode([[$_POST['inp_enlaces_titulo'], $_POST['inp_enlaces_url']]]);
                    $_POST['enlaces'] = str_replace('\\u00', '\\\\u00', $_POST['enlaces']);
                }
                unset($_POST['inp_enlaces_titulo']);
                unset($_POST['inp_enlaces_url']);
                if($rsp['data'] != 1){
                    if($_FILES['newImagen']['name'] != '' || $_FILES['newFondo']['name'] != ''){
                        if($_FILES['newImagen']['name'] != '' && $_FILES['newFondo']['name'] != ''){
                            if(isset($_FILES['newImagen']) && $_FILES['newImagen']['error'] == UPLOAD_ERR_OK
                                && isset($_FILES['newFondo']) && $_FILES['newFondo']['error'] == UPLOAD_ERR_OK){
                                    $tmp_name = $_FILES['newImagen']['tmp_name'];
                                    $uploads_dir = "../../../images/generales/flyers";

                                    $tmp_name2 = $_FILES['newFondo']['tmp_name'];
                                    $uploads_dir2 = "../../../images/generales/fondos";

                                    $fileT = explode('.', $_FILES['newImagen']['name']);
                                    $extensionI = $fileT[sizeof($fileT)-1];

                                    $fileT2 = explode('.', $_FILES['newFondo']['name']);
                                    $extensionF = $fileT2[sizeof($fileT2)-1];

                                    if(in_array($extensionI, $formatosImg)){
                                        if(in_array($extensionF, $formatosImg)){
                                            if(!isset($_POST['devEstado'])){
                                                //echo 'No';
                                                $_POST['devEstado'] = "0";
                                            }
                                            $nImagen = 'imagenEvento'.rand().'.'.$extensionI;
                                            $nImagenF = 'fondoEvento'.rand().'.'.$extensionF;

                                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nImagen");
                                            $statFile2 = move_uploaded_file($tmp_name2, "$uploads_dir2/$nImagenF");

                                            $_POST['nImagen'] = $nImagen;
                                            $_POST['nImagenF'] = $nImagenF;

                                            $edit = $eventos->modificarEvento($_POST);
                                            echo json_encode($edit);
                                        }else{
                                            echo 'no_format';
                                        }
                                    }else{
                                        echo 'no_format';
                                    }
                                    
                                    /*
                                    $nImagen = 'imagenEvento'.rand().'.'.$extensionI;
                                    $nImagenF = 'fondoEvento'.rand().'.'.$extensionF;

                                    $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nImagen");
                                    $statFile2 = move_uploaded_file($tmp_name2, "$uploads_dir2/$nImagenF");

                                    $_POST['nImagen'] = $nImagen;
                                    $_POST['nImagenF'] = $nImagenF;

                                    $edit = $eventos->modificarEvento($_POST);
                                    echo json_encode($edit);*/

                                }
                        }else{
                            if($_FILES['newImagen']['name'] != ''){
                                if(isset($_FILES['newImagen']) && $_FILES['newImagen']['error'] == UPLOAD_ERR_OK){

                                    $tmp_name = $_FILES['newImagen']['tmp_name'];
                                    $uploads_dir = "../../../images/generales/flyers";

                                    //$fileT = explode('.',$_FILES['newImagen']['name'])[1];

                                    $fileT = explode('.', $_FILES['newImagen']['name']);
                                    $extensionI = $fileT[sizeof($fileT)-1];

                                    if(in_array($extensionI, $formatosImg)){
                                        if(!isset($_POST['devEstado'])){
                                            //echo 'No';
                                            $_POST['devEstado'] = "0";
                                        }
                                        //$nImagen = 'imagenEvento'.rand().'.'.$fileT;
                                        $nImagen = 'imagenEvento'.rand().'.'.$extensionI;

                                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nImagen");

                                        $_POST['nImagen'] = $nImagen;
                                        unset($_POST['newFondo']);
                                        $edit = $eventos->modificarClaveImg($_POST);

                                        echo json_encode($edit);
                                    }else{
                                        echo 'no_format';
                                    }
                                }
                            }else{
                                if(isset($_FILES['newFondo']) && $_FILES['newFondo']['error'] == UPLOAD_ERR_OK){
                                    $tmp_name = $_FILES['newFondo']['tmp_name'];
                                    $uploads_dir = "../../../images/generales/fondos";

                                    //$fileT = explode('.', $_FILES['newFondo']['name'])[1];
                                    $fileT = explode('.', $_FILES['newFondo']['name']);
                                    $extensionI = $fileT[sizeof($fileT)-1];
                                    if(in_array($extensionI, $formatosImg)){
                                        if(!isset($_POST['devEstado'])){
                                            //echo 'No';
                                            $_POST['devEstado'] = "0";
                                        }
                                        //$nImagenF = 'fondoEvento'.rand().'.'.$fileT;
                                        $nImagenF = 'fondoEvento'.rand().'.'.$extensionI;

                                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nImagenF");

                                        $_POST['nImagenF'] = $nImagenF;
                                        unset($_POST['newImagen']);
                                        $edit = $eventos->modificarClaveFondo($_POST);
                                        echo json_encode($edit);
                                    }else{
                                        echo 'no_format';
                                    }
                                }
                            }
                        }
                    }else{
                        unset($_POST['newFondo']);
                        unset($_POST['newImagen']);
                        if(!isset($_POST['devEstado'])){
                            //echo 'No';
                            $_POST['devEstado'] = "0";
                        }
                        $edit = $eventos->modificarSinImg($_POST);
                        echo json_encode($edit);
                    }
                }else{
                    unset($_POST['newImagen']);
                    unset($_POST['newFondo']);
                    unset($_POST['devClave']);
                    if(!isset($_POST['devEstado'])){
                        //echo 'No';
                        $_POST['devEstado'] = "0";
                    }
                    $edit = $eventos->modificarGeneral($_POST);
                    echo json_encode($edit);
                }
            }
            if($estatus_upd !== false){
                $eventos->actulualizar_campo('estatus', $estatus_upd, $_POST['idModify']);
            }
            break;


        case 'eliminarEvento':
            unset($_POST['action']);
            $del = $eventos->eliminarEvento($_POST['idEliminar']);
            echo json_encode($del);
            break;

        case 'no_session':
            echo 'no_session';
            break;

        default:
        echo "noaction";
            break;
    }

}else{
	header('Location: ../../../../index.php');
}
?>
