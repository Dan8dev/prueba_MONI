<?php

header('Access-Control-Allow-Origin: *', false);
session_start();
if (isset($_POST["action"]) && isset($_SESSION["alumno"]) || isset($_SESSION["usuario"]) || isset($_POST['idUsuario']) || isset($_POST['idModify']) || isset($_POST['android_id_afiliado'])){
    date_default_timezone_set("America/Mexico_City");
	require_once '../Model/conexion.php';
    require_once '../Model/documentosModel.php';

    $dtos = new documentos();

    switch($_POST['action']){
        case 'cargarGrado':
            unset($_POST['action']);
            
            $loadGrados = $dtos->buscarGrados()['data'];
            echo json_encode($loadGrados);
            break;
        
        case 'consultarDocumentosListaCompleta':
            unset($_POST['action']);
            $infoDocumentos = $dtos->consultarDocumentosListaCompleta();
            echo json_encode($infoDocumentos);
            break;



        case 'RegistroDocumentosExtranjeros':
            unset($_POST['action']);
            $_POST['tipoEstudio'] = 0;
            $_POST['fEntrega'] = date('Y-m-d H:i:s');
            $id = $_POST['idUsuario'];
            $maxSize = 5242880;
            $response = [];
            //Se añade un array con indices con el nombre de la nomenclatura, que es el mismo que los inputs en el index (subirdocumentos)
            $indexNombres = [];
            $indexNombresConsulta = $dtos->consultarDocumentosListaCompleta();
            foreach($indexNombresConsulta as $indexDoc => $infoDoc){
                $indexNombres[$infoDoc["nomenclatura_documento"]] = [$infoDoc["nomenclatura_documento"],$infoDoc["id_documento"]];
            }
            
            //Mandando todos los archivos con sus datos
            foreach($_FILES as $index => $Archivos){
                if($Archivos['tmp_name'] != null && $Archivos['tmp_name'] != ""){
                    if($Archivos['size'] > $maxSize){
                        $response = ["estatus"=>"error","info"=>"tamanioDoc"];
                    }else{
                        $Var = explode('/',$Archivos['type']);
                        $Direccion = "../../lista_documentos/$id/";
                        if(!file_exists($Direccion)){
                            mkdir($Direccion, 0707);
                        }
                        //Otorgar el nombre dependienod de los archivos
                        $nName = "{$id}_{$indexNombres[$index][0]}.{$Var[1]}";
                        $statFile = move_uploaded_file($Archivos['tmp_name'],"{$Direccion}{$nName}");
                        $_POST['nName'] = $nName;
                        //Si el archivo se movio correctamente
                        if($statFile){
                            $response = ["estatus"=>"ok","info"=>"Documentos cargados correctamente"];
                            $_POST['documento'] = $indexNombres[$index][1];
                            $saveDoc = $dtos->registrarDocumento($_POST);
                        }
                    }
                }
            }
            echo json_encode($response);
            break;
        case 'EstatusVerificacion':
            unset($_POST["action"]);
            $Estatus = $dtos->verificacionAlumno($_POST);
            echo json_encode($Estatus);
            break;

        case 'ConsultaDocVerificacion':
            unset($_POST["action"]);
            $id = $_POST["idAlumno"];
            $idAfiliado = $dtos->BuscarIdAlumno($id);
            $id = $idAfiliado["data"]["id_afiliado"];
            //var_dump($id);
            $documentos = $dtos->consultarDocumentosVerificacion($id);
            echo json_encode($documentos);
            break;

        case 'DocumentosVerificación':
            unset($_POST['action']);
            $_POST['tipoEstudio'] = 0;
            $id_prospecto = $_POST['idAlumno'];
            unset($_POST['idAlumno']);
            $statFile = false;
            //$idDoc = $_POST['idDocumento'];
            $maxSize = 5242880;
            $response = [];
            $idAfiliado = $dtos->BuscarIdAlumno($id_prospecto);
            $id = $idAfiliado["data"]["id_afiliado"];
            $_POST["idUsuario"] = $id;
            foreach($_FILES as $index => $Archivos){
                if($Archivos['tmp_name'] != null && $Archivos['tmp_name'] != ""){
                    if($Archivos['size'] > $maxSize){
                        $response = ["estatus"=>"error","info"=>"tamanioDoc"];
                    }else{
                        $index =  str_replace("_", " ", $index);
                        $infoDoc = $dtos->consultarInfoDocumento($index); 
                        $_POST["documento"] = $infoDoc["data"]["id_documento"];
                        $Var = explode('/',$Archivos['type']);
                        $nName = "{$id}_{$infoDoc['data']['nomenclatura_documento']}.{$Var[1]}";
                        
                        $Direccion = "../../lista_documentos/$id/";
                        if(!file_exists($Direccion)){
                            mkdir($Direccion, 0707);
                        }
                        //Otorgar el nombre dependienod de los archivos
                        $statFile = move_uploaded_file($Archivos['tmp_name'],"{$Direccion}{$nName}");
                        $_POST['nName'] = $nName;
                        //Si el archivo se movio correctamente
                        
                        if($statFile){
                            $response = ["estatus"=>"ok","info"=>"Documentos cargados correctamente"];
                            $_POST['fEntrega'] = date("Y-m-d H:i:s");
                            // $_POST['documento'] = $indexNombres[$index][1];
                            $saveDoc = $dtos->registrarDocumento($_POST);
                        }
                    }
                }
            }
            if($statFile){
                unset($_POST['nName']);
                unset($_POST['tipoEstudio']);
                unset($_POST['android_id_afiliado']);
                unset($_POST['documento']);
                unset($_POST['fEntrega']);
                unset($_POST['idUsuario']);
                $_POST['idAlumno'] = $id_prospecto;
                $_POST['estatus'] = "1";
                $_POST['tipo'] = "actualiza";
                $AfiliadoSolic = $dtos->verificacionAlumno($_POST);
                //var_dump($AfiliadoSolic);
            }else{
                $response = ["estatus"=>"error","info"=>"Los docuemntos no fueron cargados correctamente"];
            }
            echo json_encode($response);
            break;

        case 'VerificarDocumentosAlumno':
            unset($_POST['action']);
            $cDatos = $dtos->consultarDocumentosList($_POST['idAlum']);
            echo json_encode($cDatos);
            break;
        case  'consultarDocumentosFisicos':
            unset($_POST['action']);
            $_POST['DocFis'] = 'Fisicos';
            $csulfis = $dtos->consultarDocumentos($_POST);

             $data = Array();
            while($dato=$csulfis->fetchObject()){
                $data[]=array(
                    0 => $dato->nombre_documento,
                    1 => $dato->fecha_registro,
                    2 => "<b>Recibido</b>",
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
        case 'consultarDocumentos':
            unset($_POST['action']);
            $csul = $dtos->consultarDocumentos($_POST['idusuario']);
            $data = Array();
            while($dato=$csul->fetchObject()){
                $data[]=array(
                0=> $dato->nombre_archivo,
                1=> $dato->validacion === '1' || $dato->validacion === '2' ? 'Finalizado' : 'En espera de revisión',
                2=> $dato->validacion === '1' ? '<div class="clave alert alert-info">Aprobado</div>' : ($dato->validacion === '2' ? '<div class="clave alert alert-danger">No aprobado</div>' : ''),
                3=> $dato->comentario,
                4=> $dato->validacion === '2' ? '<button class="btn btn-primary" name="ver" id="ver" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="buscarDocumento('.$dato->id_documento.','.$dato->id_prospectos.')">Ver</a>' : '<button class="btn btn-secondary" name="verS" id="verS" disabled>Ver</a>'
                //4=> '<button class="btn btn-primary" name="ver" id="ver" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="buscarDocumento('.$dato->id_documento.','.$dato->id_prospectos.')">Ver</a>'
                /*21=>'<a class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="verDocumento('.$dato->idEvento.')">Modificar</a>',
                22=>'<a class="btn btn-danger" onclick="validarEliminar('.$dato->idEvento.')">Eliminar</a>'*/
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
        
        case 'BuscarDocumento':
            unset($_POST['action']);
            $bus = $dtos->buscarDocumento($_POST['idDoc'], $_POST['idUsu']);
            
            echo json_encode($bus);
            break;
        
        case 'modificarDocumento':
            unset($_POST['action']);
            //:::
            // $formatosDoc = array('.pdf','.jpg', '.jpeg', '.png');
            // $formatosImg = array('.jpg', '.jpeg', '.png');
            //$maxSize = 2097152;
            $maxSize = 5242880;
            $id = $_POST['idModify'];
            $idDoc = $_POST['idDocument'];

            $var =  $_FILES['modFile']['type'];
            $var = explode("/",$var);
            $extension = $var[1];
            $doc = $_FILES['modFile'];

            $documentosC = $dtos->consultarDocumentosListaCompleta();
            $indexNombres = [];
            foreach($documentosC as $infoDoc){
                $indexNombres[$infoDoc['id_documento']] =  ['id_documento'=>$infoDoc['id_documento'],'nomenclatura_documento' =>$infoDoc['nomenclatura_documento']];
            }
            $response = [];
            
            if($doc['size'] < $maxSize){
                $Nombre = $indexNombres[$idDoc]['nomenclatura_documento'];
                $tmp_name = $_FILES['modFile']['tmp_name'];
                $uploads_dir = "../../lista_documentos/{$id}/";

               
                if(!file_exists($uploads_dir)){
                    mkdir($uploads_dir, 0707);
                }

                $docBorrar = ["jpg", "jpeg", "png", "pdf"];
                foreach($docBorrar as $borrar){
                    $BorrarFile = "{$uploads_dir}{$id}_{$Nombre}.{$borrar}";
                    if(file_exists($BorrarFile)){
                        unlink($BorrarFile);
                    }
                    $BorrarFile = "{$uploads_dir}{$id}{$Nombre}.{$borrar}";
                    if(file_exists($BorrarFile)){
                        unlink($BorrarFile);
                    }
                }

                $nName = "{$id}_{$Nombre}.{$extension}";
                //var_dump($nName);
                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                $_POST['nName'] = $nName;

                $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                echo json_encode($upgrade);
            }else{
                echo json_encode(["estatus"=>"error tamaño superado"]);
            }

            break;

        default:
                # code...
            break;
    }

}else{
    header("Location: ../../index.php");
}
