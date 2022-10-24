<?php
session_start();
if (isset($_POST["action"]) && isset($_SESSION["alumno_iesm"])){    
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

            $formatosDoc = array('.pdf');
            $formatosImg = array('.jpg', '.jpeg', '.png');
            $maxSize = 2097152;

            $id = $_POST['idUsuario'];
        
            $docI = $_FILES['identificacion'];
            $docA = $_FILES['acta'];
            $docC = $_FILES['curp'];
            $docE = $_FILES['gradoEstudios'];
            $docFO = $_FILES['fotoOvalo'];
            $docFI = $_FILES['fotoInfantil'];

            $extension = $_FILES['identificacion']['name'];
            $extI = substr($extension, strrpos($extension, '.'));
            
            $extension = $_FILES['acta']['name'];
            $extA = substr($extension, strrpos($extension, '.'));
            
            $extension = $_FILES['curp']['name'];
            $extC = substr($extension, strrpos($extension, '.'));
            
            $extension = $_FILES['gradoEstudios']['name'];
            $extE = substr($extension, strrpos($extension, '.'));

            $extension = $_FILES['fotoOvalo']['name'];
            $extFO = substr($extension, strrpos($extension, '.'));

            $extension = $_FILES['fotoInfantil']['name'];
            $extFI = substr($extension, strrpos($extension, '.'));
            
            if(in_array($extI, $formatosDoc)){
                if($docI['size'] < $maxSize){
                    if(in_array($extA, $formatosDoc)){
                        if($docA['size'] < $maxSize){
                            if(in_array($extC, $formatosDoc)){
                                if($docC['size'] < $maxSize){
                                    if(in_array($extE, $formatosDoc)){
                                        if($docE['size'] < $maxSize){
                                            $pdfs = 1;    
                                        }else{
                                            $pdfs = 0;    
                                        }
                                    }else{
                                        $pdfs = 0;    
                                    }
                                }else{
                                    $pdfs = 0;    
                                }
                            }else{
                                $pdfs = 0;    
                            }
                        }else{
                            $pdfs = 0;    
                        }
                    }else{
                        $pdfs = 0;    
                    }
                }else{
                    $pdfs = 0;    
                }
            }else{
                $pdfs = 0;    
            }

            if(in_array($extFO, $formatosImg)){
                if($docFO['size'] < $maxSize){
                    if(in_array($extFI, $formatosImg)){
                        if($docFI['size'] < $maxSize){
                            $image =1;
                        }else{
                            $image=0;
                        }
                    }else{
                        $image=0;
                    }
                }else{
                    $image=0;
                }
            }else{
                $image=0;
            }
            
            if($pdfs == 0){
                echo 'DocInc';
            }

            if($image == 0){
                echo 'DocImg';
            }

            if($pdfs == 1 && $image == 1){
                $tmp_nameI = $_FILES['identificacion']['tmp_name'];
                    $uploads_dirI = "../../lista_documentos/Identificaciones";
        
                    $tmp_nameA = $_FILES['acta']['tmp_name'];
                    $uploads_dirA = "../../lista_documentos/Actas_nacimiento";
        
                    $tmp_nameC = $_FILES['curp']['tmp_name'];
                    $uploads_dirC = "../../lista_documentos/Curp";
                    
                    $tmp_nameE = $_FILES['gradoEstudios']['tmp_name'];
                    $uploads_dirE = "../../lista_documentos/Comprobante_estudios";

                    $tmp_nameFO = $_FILES['fotoOvalo']['tmp_name'];
                    $uploads_dirFO = "../../lista_documentos/Fotos_ovalo";

                    $tmp_nameFI = $_FILES['fotoInfantil']['tmp_name'];
                    $uploads_dirFI = "../../lista_documentos/Fotos_infantil";
                    
                    $fileT1 = explode('.', $_FILES['identificacion']['name'])[1];
                    $fileT2 = explode('.', $_FILES['acta']['name'])[1];
                    $fileT3 = explode('.', $_FILES['curp']['name'])[1];
                    $fileT4 = explode('.', $_FILES['gradoEstudios']['name'])[1];
                    $fileT5 = explode('.', $_FILES['fotoOvalo']['name'])[1];
                    $fileT6 = explode('.', $_FILES['fotoInfantil']['name'])[1];

                    $nNameI = $id.'_identificacion'.'.'.$fileT1;
                    $nNameA = $id.'_acta'.'.'.$fileT2;
                    $nNameC = $id.'_curp'.'.'.$fileT3;
                    $nNameE = $id.'_comprobante_estudios'.'.'.$fileT4;
                    $nNameFO = $id.'_foto_ovalo'.'.'.$fileT5;
                    $nNameFI = $id.'_foto_infantil'.'.'.$fileT6;

                    $statFile = move_uploaded_file($tmp_nameI, "$uploads_dirI/$nNameI");
                    $statFile = move_uploaded_file($tmp_nameA, "$uploads_dirA/$nNameA");
                    $statFile = move_uploaded_file($tmp_nameC, "$uploads_dirC/$nNameC");
                    $statFile = move_uploaded_file($tmp_nameE, "$uploads_dirE/$nNameE");
                    $statFile = move_uploaded_file($tmp_nameFO, "$uploads_dirFO/$nNameFO");
                    $statFile = move_uploaded_file($tmp_nameFI, "$uploads_dirFI/$nNameFI");

                    $_POST['nNameI'] = $nNameI;
                    $_POST['nNameA'] = $nNameA;
                    $_POST['nNameC'] = $nNameC;
                    $_POST['nNameE'] = $nNameE;
                    $_POST['nNameFO']= $nNameFO;
                    $_POST['nNameFI']= $nNameFI;
                    
                    $saveDoc = $dtos->registrarDocumentos($_POST);
                    echo json_encode($saveDoc);
            }
            
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
                4=> $dato->validacion === '2' ? '<button class="btn btn-primary" name="ver" id="ver" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="buscarDocumento('.$dato->id_documento.','.$dato->id_prospectos.')">Ver</a>' : '<button class="btn btn-primary" name="verS" id="verS">Ver</a>'
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
            
            $formatosDoc = array('.pdf');
            $formatosImg = array('.jpg', '.jpeg', '.png');
            $maxSize = 2097152;

            $id = $_POST['idModify'];
            $idDoc = $_POST['idDocument'];
            
            $doc = $_FILES['modFile'];

            $extension = $_FILES['modFile']['name'];
            $ext = substr($extension, strrpos($extension, '.'));
            
            if(in_array($ext, $formatosImg)){
                if($doc['size'] < $maxSize){
                    if($idDoc == 5){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/Fotos_ovalo";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_foto_ovalo'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName']= $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 6){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/Fotos_infantil";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName= $id.'_foto_infantil'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName']= $nName;

                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                }
            }
            if(in_array($ext, $formatosDoc)){
                if($doc['size'] < $maxSize){
                    if($idDoc == 1){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/Identificaciones";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_identificacion'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 2){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/Actas_nacimiento";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_acta'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 3){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/Curp";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_curp'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName"); 
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 4){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/Comprobante_estudios";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_comprobante_estudios'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                }
            }
            
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
