<?php
header('Access-Control-Allow-Origin: https://moni.com.mx', false);
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
        
        case 'registrarDocumentos':
            unset($_POST['action']);
            $formatosDoc = array('.pdf','.jpg', '.jpeg', '.png');
            $formatosImg = array('.jpg', '.jpeg', '.png');
            $maxSize = 5242880;
            $id = $_POST['idUsuario'];

            if(isset($_FILES['file'])){
                if(!empty($_POST['gradoEstudio']) && $_POST['gradoEstudio'] != 'undefined'){
                    if($_POST['documento']==4){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;
                            
                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }

                            $nName = $id.'_comprobante_estudios'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarComprobanteEstudio($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
                }else{
                    unset($_POST['gradoEstudio']);

                    if($_POST['documento'] == 19){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;
                            
                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }

                            $nName = $id.'_fotos_ovalo'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['tipoEstudio'] = 0;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarDocumento($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }

                    if($_POST['documento']==7){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;
                            
                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }

                            $nName = $id.'_identificacion_anverso'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['tipoEstudio'] = 0;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarDocumento($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
        
                    if($_POST['documento']==8){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;
                            
                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }
                            $nName = $id.'_identificacion_reverso'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['tipoEstudio'] = 0;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarDocumento($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
        
                    if($_POST['documento']==2){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;
                            
                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }

                            $nName = $id.'_acta'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['tipoEstudio'] = 0;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarDocumento($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
        
                    if($_POST['documento']==3){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;
                            
                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }

                            $nName = $id.'_curp'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['tipoEstudio'] = 0;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarDocumento($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }

                    if($_POST['documento']==5){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosImg)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $image = 1;
                            }else{
                                $image = 0;
                            }
                        }else{
                            $image=0;
                        }
        
                        if($image == 0){
                            echo 'ImgInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;

                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }
                            
                            $nName = $id.'_foto_ovalo'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['tipoEstudio'] = 0;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarDocumento($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }

                    if($_POST['documento']==6){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosImg)){
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                            if($_FILES['file']['size'] < $maxSize){
                                $image = 1;
                            }else{
                                $image = 0;
                            }
                        }else{
                            $image=0;
                        }
        
                        if($image == 0){
                            echo 'ImgInc';
                        }else{
                            if(!file_exists('../../lista_documentos/'.$id)){
                                mkdir('../../lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../lista_documentos/".$id;

                            if($im == 1){
                                $extensionFI = 'jpg';
                            }else{
                            $fileT = explode('.', $_FILES['file']['name']);
                            $extensionFI = $fileT[sizeof($fileT)-1];
                            }
                            
                            $nName = $id.'_foto_infantil'.'.'.$extensionFI;
        
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
        
                            $_POST['nName'] = $nName;
                            $_POST['tipoEstudio'] = 0;
                            $_POST['fEntrega'] = date('Y-m-d H:i:s');
        
                            if(!isset($_POST['copy'])){
                                $saveDoc = $dtos->registrarDocumento($_POST);
                            }
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];
                            
                            echo json_encode($saveDoc);
                        }
                    }
                }
            }
            
            break;

        case 'RegistroDocumentosExtranjeros':
            unset($_POST['action']);
            $_POST['tipoEstudio'] = 0;
            $_POST['fEntrega'] = date('Y-m-d H:i:s');
            $id = $_POST['idUsuario'];
            $maxSize = 5242880;
            $response = [];
            //Iarray con los nhombre de otodos los archivos para registar
            $indexNombres = ["DocMigratorio"=>["documento_migratorio",17],
                            "Actanaciemnto"=>["acta",2],
                            "ComprobanteDomicilio"=>["comprobante_domicilio",11],
                            "CertEstMedicina"=>["comprobante_estudios",4],
                            "CopiaTitulo"=>["copia_notariada_titulo_medicina",13],
                            "DiplomaEspecialidad"=>["copia_notariada_diploma_especialidad",14],
                            "curpEx"=>["curp",3],
                            "CartaMotivos"=>["carta_motivos",9],
                            "DictamenTecnico"=>["dictamen_tecnico",18],
                            "FotosInfantil"=>["foto_infantil",6],
                            "FotosTitulo"=>["foto_titulo_ovalo",19],
                            "FotosCredencial"=>["foto_ovalo",5]];

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
            if(isset($_POST["android_id_afiliado"])){
                unset($_POST["android_id_afiliado"]);
            }
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
            // var_dump($_FILES);
            // die();
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
                        //$index =  str_replace("_", " ", $index);
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
                $response = ["estatus"=>"error","info"=>"Los documentos no fueron cargados correctamente"];
            }
            echo json_encode($response);
            break;

        case 'VerificarDocumentosAlumno':
            unset($_POST['action']);
            $cDatos = $dtos->consultarDocumentosList($_POST['idAlum']);
            echo json_encode($cDatos);
            break;

        case 'consultarDocumentos':
            unset($_POST['action']);
            $csul = $dtos->consultarDocumentos($_POST['idusuario']);
            $data = Array();
            while($dato=$csul->fetchObject()){
                if(isset($_POST['allows'])){
                    if(in_array($dato->id_documento, $_POST['allows'])){
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
                    
                }else{
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
            
            $formatosDoc = array('.pdf','.jpg', '.jpeg', '.png');
            $formatosImg = array('.jpg', '.jpeg', '.png');
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
                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                $_POST['nName']= $nName;

                $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                echo json_encode($upgrade);
            }else{
                echo json_encode(["estatus"=>"error tamaño superado"]);
            }
            
            // $doc = $_FILES['modFile'];

            // $extension = $_FILES['modFile']['name'];
            // $ext = substr($extension, strrpos($extension, '.'));
            
            // if(in_array($ext, $formatosImg)){
            //     if($ext == '.jpeg'){
            //         $imMod=1;
            //     }else{
            //         $imMod=0;
            //     }
            //     if($doc['size'] < $maxSize){
            //         if($idDoc == 5){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName = $id.'_foto_ovalo'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            //             $_POST['nName']= $nName;
                        
            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //         if($idDoc == 6){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName= $id.'_foto_infantil'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            //             $_POST['nName']= $nName;

            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //     }
            // }
            // if(in_array($ext, $formatosDoc)){
            //     if($ext == '.jpeg'){
            //         $imMod=1;
            //     }else{
            //         $imMod=0;
            //     }
            //     if($doc['size'] < $maxSize){
            //         if($idDoc == 7){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName = $id.'_identificacion_anverso'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            //             $_POST['nName'] = $nName;
                        
            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //         if($idDoc == 8){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName = $id.'_identificacion_reverso'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            //             $_POST['nName'] = $nName;
                        
            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //         if($idDoc == 1){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName = $id.'_formato_inscripcion'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            //             $_POST['nName'] = $nName;
                        
            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //         if($idDoc == 2){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName = $id.'_acta'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            //             $_POST['nName'] = $nName;
                        
            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //         if($idDoc == 3){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName = $id.'_curp'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName"); 
            //             $_POST['nName'] = $nName;
                        
            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //         if($idDoc == 4){
            //             $tmp_name = $_FILES['modFile']['tmp_name'];
            //             $uploads_dir = "../../lista_documentos/".$id."/";
            //             if($imMod == 1){
            //                 $extension = 'jpg';
            //             }else{
            //                 $file = explode('.', $_FILES['modFile']['name']);
            //                 $extension = $file[sizeof($file)-1];
            //             }
            //             $nName = $id.'_comprobante_estudios'.'.'.$extension;
            //             $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            //             $_POST['nName'] = $nName;
                        
            //             $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
            //             echo json_encode($upgrade);
                        
            //         }
            //     }
            // }
            
            break;

        case 'habilitarInputs':
            unset($_POST['action']);
            $hab = $dtos->habilitarInputs($_POST['idUsu']);
            echo json_encode($hab);
            break;
        

        default:
                # code...
            break;
    }

}else{
    header("Location: ../../index.php");
}
