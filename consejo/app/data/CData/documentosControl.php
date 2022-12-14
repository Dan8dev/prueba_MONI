<?php
session_start();
if (isset($_POST["action"]) && isset($_SESSION["alumno"])){    
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
        
            $docIA = $_FILES['identificacionA'];
            $docIR = $_FILES['identificacionR'];
            $docA = $_FILES['acta'];
            $docC = $_FILES['curp'];
            $docE = $_FILES['gradoEstudios'];
            $docFO = $_FILES['fotoOvalo'];
            $docFI = $_FILES['fotoInfantil'];

            $extension = $_FILES['identificacionA']['name'];
            $extIA = substr($extension, strrpos($extension, '.'));

            $extension = $_FILES['identificacionR']['name'];
            $extIR = substr($extension, strrpos($extension, '.'));
            
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
            
            if(in_array($extIA, $formatosDoc)){
                if($docIA['size'] < $maxSize){
                    if(in_array($extIR, $formatosDoc)){
                        if($docIR['size'] < $maxSize){
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
                if (!file_exists('lista_documentos/'.$id)){
                    mkdir('../../lista_documentos/'.$id, 0707);
                }
                    $tmp_nameIA = $_FILES['identificacionA']['tmp_name'];
                    $uploads_dirIA = "../../lista_documentos/".$id;

                    $tmp_nameIR = $_FILES['identificacionR']['tmp_name'];
                    $uploads_dirIR = "../../lista_documentos/".$id;
        
                    $tmp_nameA = $_FILES['acta']['tmp_name'];
                    $uploads_dirA = "../../lista_documentos/".$id;
        
                    $tmp_nameC = $_FILES['curp']['tmp_name'];
                    $uploads_dirC = "../../lista_documentos/".$id;
                    
                    $tmp_nameE = $_FILES['gradoEstudios']['tmp_name'];
                    $uploads_dirE = "../../lista_documentos/".$id;

                    $tmp_nameFO = $_FILES['fotoOvalo']['tmp_name'];
                    $uploads_dirFO = "../../lista_documentos/".$id;

                    $tmp_nameFI = $_FILES['fotoInfantil']['tmp_name'];
                    $uploads_dirFI = "../../lista_documentos/".$id;
                    
                    $fileT1A = explode('.', $_FILES['identificacionA']['name'])[1];
                    $fileT1R = explode('.',$_FILES['identificacionR']['name'])[1];
                    $fileT2 = explode('.', $_FILES['acta']['name'])[1];
                    $fileT3 = explode('.', $_FILES['curp']['name'])[1];
                    $fileT4 = explode('.', $_FILES['gradoEstudios']['name'])[1];
                    $fileT5 = explode('.', $_FILES['fotoOvalo']['name'])[1];
                    $fileT6 = explode('.', $_FILES['fotoInfantil']['name'])[1];

                    $nNameIA = $id.'_identificacion_anverso'.'.'.$fileT1A;
                    $nNameIR = $id.'_identificacion_reverso'.'.'.$fileT1R;
                    $nNameA = $id.'_acta'.'.'.$fileT2;
                    $nNameC = $id.'_curp'.'.'.$fileT3;
                    $nNameE = $id.'_comprobante_estudios'.'.'.$fileT4;
                    $nNameFO = $id.'_foto_ovalo'.'.'.$fileT5;
                    $nNameFI = $id.'_foto_infantil'.'.'.$fileT6;

                    $statFile = move_uploaded_file($tmp_nameIA, "$uploads_dirIA/$nNameIA");
                    $statFile = move_uploaded_file($tmp_nameIR, "$uploads_dirIR/$nNameIR");
                    $statFile = move_uploaded_file($tmp_nameA, "$uploads_dirA/$nNameA");
                    $statFile = move_uploaded_file($tmp_nameC, "$uploads_dirC/$nNameC");
                    $statFile = move_uploaded_file($tmp_nameE, "$uploads_dirE/$nNameE");
                    $statFile = move_uploaded_file($tmp_nameFO, "$uploads_dirFO/$nNameFO");
                    $statFile = move_uploaded_file($tmp_nameFI, "$uploads_dirFI/$nNameFI");

                    $_POST['nNameIA'] = $nNameIA;
                    $_POST['nNameIR'] = $nNameIR;
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
                1=> $dato->validacion === '1' || $dato->validacion === '2' ? 'Finalizado' : 'En espera de revisi??n',
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
                        $uploads_dir = "../../lista_documentos/".$id."/";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_foto_ovalo'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName']= $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 6){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/".$id."/";
                        
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
                    if($idDoc == 7){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/".$id."/";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_identificacion_anverso'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 8){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/".$id."/";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_identificacion_reverso'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 2){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/".$id."/";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_acta'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 3){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/".$id."/";
                        
                        $file = explode('.', $_FILES['modFile']['name'])[1];
                        $nName = $id.'_curp'.'.'.$file;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName"); 
                        $_POST['nName'] = $nName;
                        
                        $upgrade = $dtos->modificarDocumento($_POST['idDocument'], $_POST['idModify'], $_POST['nName']);
                        echo json_encode($upgrade);
                        
                    }
                    if($idDoc == 4){
                        $tmp_name = $_FILES['modFile']['tmp_name'];
                        $uploads_dir = "../../lista_documentos/".$id."/";
                        
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
