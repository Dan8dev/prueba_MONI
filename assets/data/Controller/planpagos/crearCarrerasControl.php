<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/crearCarrerasModel.php';
    $crrer = new Carreras();

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }

    $accion=@$_POST["action"];

    switch ($accion) {
        case 'obtenerpaises':
            unset($_POST['action']);
            $paises = $crrer->getPaises();
            $paises = $paises['data'];
            echo json_encode($paises);
            break;
        case 'obtenerinstituciones':
            unset($_POST['action']);
            $instituciones = $crrer->getInstituciones();
            $instituciones = $instituciones['data'];
            echo json_encode($instituciones);
            break;
        case 'obtenerestados':
            unset($_POST['action']);
            $estados = $crrer->getEstados($_POST);
            $estados = $estados['data'];
            echo json_encode($estados);
            break;
        case 'crearcarrera':
            $resp = $crrer->buscarNombreClave($_POST['crearclavecarrera']);
            if($resp['data'] != 1){
                unset($_POST['action']);
                $formatosImg = array('jpg', 'jpeg', 'png');
                if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK
                        && isset($_FILES['imgFondo']) && $_FILES['imgFondo']['error'] == UPLOAD_ERR_OK){
                        
                        $tmp_name = $_FILES['imagen']['tmp_name'];
                        $uploads_dir = "../../../images/generales/flyers";

                        $tmp_name2 = $_FILES['imgFondo']['tmp_name'];
                        $uploads_dir2 = "../../../images/generales/fondos";

                        $fileT = explode('.', $_FILES['imagen']['name']);
                        $extensionI = $fileT[sizeof($fileT)-1];
                        $fileT2 = explode('.', $_FILES['imgFondo']['name']);
                        $extensionF = $fileT2[sizeof($fileT2)-1];

                        if(in_array($extensionI, $formatosImg)){
                            if(in_array($extensionF, $formatosImg)){
                                if(!isset($_POST['selectestado'])){
                                    $_POST['selectestado'] = 0;
                                }
                                $fechaActual = date('Y-m-d H:i:s');
                                $nName = 'imagencarrera'.rand().'.'.$extensionI;
                                $nNameF = 'fondocarrera'.rand().'.'.$extensionF;

                                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                                $statFile2 = move_uploaded_file($tmp_name2, "$uploads_dir2/$nNameF");            

                                $_POST['imagen'] = $nName;
                                $_POST['imgFondo'] = $nNameF;
                                $_POST['fActual'] = $fechaActual;
                                $_POST['estatus'] = "1";

                                $carrera = $crrer->crearCarrera($_POST);
                                echo json_encode($carrera);
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

        case 'obtenerCarreas':
            unset($_POST['action']);
            $csul = $crrer->obtenerCarreas();
            $data = Array();
            while($dato=$csul->fetchObject()){
                $data[]=array(
                    0=> $dato->nombreInst,
                    1=> $dato->nombre,
                    2=> $dato->nombre_clave,
                    3=> $dato->tipo === '1' ? 'Certificación' : 'TSU',
                    4=> $dato->modalidadCarrera,
                    5=> $dato->duracionTotal === '1' ? '24 meses' : ($dato->duracionTotal === '2' ? '30 meses' : ($dato->duracionTotal === '3' ? '12 meses' : '6 meses')),
                    6=> $dato->tipoCiclo === '1' ? 'Cuatrimestre' : 'Semestre',
                    7=> $dato->codigoPromocional,
                    8=> $dato->direccion,
                    9=> $dato->estado_nom,
                    10=> $dato->pais_nom,
                    11=> $dato->plantilla_bienvenida,
                    12=> date("d-m-Y", strtotime($dato->fecha_inicio)), 
                    13=> date("d-m-Y", strtotime($dato->fecha_fin)),
                    14=> $dato->fecha_creado,
                    15=>'<button class="btn btn-primary" data-toggle="modal" data-target="#modalModifycarrera" onclick="buscarCarrera('.$dato->idCarrera.')">Modificar</button>',
                    16=>'<button class="btn btn-danger" onclick="validarEliminar('.$dato->idCarrera.')">Eliminar</button>'
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
        
        case 'buscarPlantillas':
            unset($_POST['action']);
            $plan = $crrer->buscarPlantillas();
            $plan = $plan['data'];
            echo json_encode($plan);
            break;

        case 'buscarCarrera':
            unset($_POST['action']);
            $bus = $crrer->buscarCarrera($_POST['idEditar']);
            $bus['data'][0]['fechainicio'] = date("Y-m-d", strtotime($bus['data'][0]['fecha_inicio']));
            $bus['data'][0]['fechafin'] = date("Y-m-d", strtotime($bus['data'][0]['fecha_fin']));
            echo json_encode($bus);
            break;

        case 'modificarCarrera':
            unset($_POST['action']);
            $rsp = $crrer->buscarClaveDev($_POST['devclavecarrera'], $_POST['id_carrera']);
            if($rsp['data'] == 1){
                echo $rsp['data'];
            }else{
                $formatosImg = array('jpg', 'jpeg', 'png');
                if($rsp['data'] != 1){
                    if($_FILES['newimagen']['name'] != '' || $_FILES['newfondo']['name'] != ''){
                        if($_FILES['newimagen']['name'] != '' && $_FILES['newfondo']['name'] != ''){
                            if(isset($_FILES['newimagen']) && $_FILES['newimagen']['error'] == UPLOAD_ERR_OK
                                && isset($_FILES['newfondo']) && $_FILES['newfondo']['error'] == UPLOAD_ERR_OK){
                                    $tmp_name = $_FILES['newimagen']['tmp_name'];
                                    $uploads_dir = "../../../images/generales/flyers";

                                    $tmp_name2 = $_FILES['newfondo']['tmp_name'];
                                    $uploads_dir2 = "../../../images/generales/fondos";

                                    $fileT = explode('.', $_FILES['newimagen']['name']);
                                    $extensionI = $fileT[sizeof($fileT)-1];

                                    $fileT2 = explode('.', $_FILES['newfondo']['name']);
                                    $extensionF = $fileT2[sizeof($fileT2)-1];

                                    if(in_array($extensionI, $formatosImg)){
                                        if(in_array($extensionF, $formatosImg)){
                                            if(!isset($_POST['devestado'])){
                                                $_POST['devestado'] = "0";
                                            }
                                            $nImagen = 'imagencarrera'.rand().'.'.$extensionI;
                                            $nImagenF = 'fondocarrera'.rand().'.'.$extensionF;

                                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nImagen");
                                            $statFile2 = move_uploaded_file($tmp_name2, "$uploads_dir2/$nImagenF");

                                            $_POST['nImagen'] = $nImagen;
                                            $_POST['nImagenF'] = $nImagenF;
                                                
                                            $fActualizacion = date('Y-m-d H:i:s');
                                            $_POST['fActualizacion'] = $fActualizacion;

                                            $edit = $crrer->modificarCarrera($_POST);
                                            echo json_encode($edit);
                                        }else{
                                            echo 'no_format';
                                        }
                                    }else{
                                        echo 'no_format';
                                    }
                                }
                        }else{
                            if($_FILES['newimagen']['name'] != ''){
                                if(isset($_FILES['newimagen']) && $_FILES['newimagen']['error'] == UPLOAD_ERR_OK){

                                    $tmp_name = $_FILES['newimagen']['tmp_name'];
                                    $uploads_dir = "../../../images/generales/flyers";

                                    $fileT = explode('.', $_FILES['newimagen']['name']);
                                    $extensionI = $fileT[sizeof($fileT)-1];

                                    if(in_array($extensionI, $formatosImg)){
                                        if(!isset($_POST['devestado'])){
                                            $_POST['devestado'] = "0";
                                        }
                                        $nImagen = 'imagencarrera'.rand().'.'.$extensionI;

                                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nImagen");

                                        $_POST['nImagen'] = $nImagen;
                                        unset($_POST['newfondo']);
                                                        
                                        $fActualizacion = date('Y-m-d H:i:s');
                                        $_POST['fActualizacion'] = $fActualizacion;
                                        $edit = $crrer->modificarClaveImg($_POST);

                                        echo json_encode($edit);
                                    }else{
                                        echo 'no_format';
                                    }
                                }
                            }else{
                                if(isset($_FILES['newfondo']) && $_FILES['newfondo']['error'] == UPLOAD_ERR_OK){
                                    $tmp_name = $_FILES['newfondo']['tmp_name'];
                                    $uploads_dir = "../../../images/generales/fondos";

                                    $fileT = explode('.', $_FILES['newfondo']['name']);
                                    $extensionI = $fileT[sizeof($fileT)-1];
                                    if(in_array($extensionI, $formatosImg)){
                                        if(!isset($_POST['devestado'])){
                                            $_POST['devestado'] = "0";
                                        }
                                        $nImagenF = 'fondocarrera'.rand().'.'.$extensionI;

                                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nImagenF");

                                        $_POST['nImagenF'] = $nImagenF;
                                        unset($_POST['newimagen']);
                                                        
                                        $fActualizacion = date('Y-m-d H:i:s');
                                        $_POST['fActualizacion'] = $fActualizacion;
                                        $edit = $crrer->modificarClaveFondo($_POST);
                                        echo json_encode($edit);
                                    }else{
                                        echo 'no_format';
                                    }
                                }
                            }
                        }
                    }else{
                        unset($_POST['newfondo']);
                        unset($_POST['newimagen']);
                        if(!isset($_POST['devestado'])){
                            $_POST['devestado'] = "0";
                        }
                        $fActualizacion = date('Y-m-d H:i:s');
                        $_POST['fActualizacion'] = $fActualizacion;
                        $edit = $crrer->modificarSinImg($_POST);
                        echo json_encode($edit);
                    }
                }
            }
            break;
        
        case 'eliminarCarrera':
            unset($_POST['action']);
            $del = $crrer->eliminarCarrera($_POST);
            echo json_encode($del);
            break;

        case 'no_session':
            echo 'no_session';
            break;
        
        default:
            # code...
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}
