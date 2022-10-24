<?php 
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
session_start();
if (isset($_POST["action"]) && (isset($_SESSION["alumno"]) || isset($_POST["android_id_afiliado"]))) {
    date_default_timezone_set("America/Mexico_City");
    require "../Model/AfiliadosModel.php";
    include('../functions/phpqrcode/qrlib.php');
  

    $afiliados = new Afiliados();
    
    switch ($_POST['action']) {
        case 'obtenerCredencial':
            unset($_POST['action']);
            //Funcion Correcta para obtner los datos de la credenciall sin afectar cursos
            $usuario=$afiliados->obtenerusuariocredenciales($_POST['idusuario']);//ID_AFILIADO
            $vigencia=$afiliados->fechafinmembresia($usuario['data']['id_prospecto']);
            $image = $afiliados->obtenerNombreCredencial($_POST['idusuario']);//ID_AFILIADO
            // $vig=utf8_decode(substr($vigencia['data']['finmembresia'],0,10));
            // $nvig=date("d-m-Y", strtotime($vig));
            $prospectos=utf8_decode($usuario['data']['matricula']);
            $pro=str_pad($prospectos, 6, '0', STR_PAD_LEFT);
            $resp =  $afiliados->obtenerValidacionFoto($_POST['idusuario'])['data'];
            

            $DatosGen = $afiliados->obtenerDatosGeneraciones($usuario['data']['idgeneracion']);
            

            $NombreGen=utf8_decode($DatosGen['data'][0]['NombreGen']);
            $NombreCarr=utf8_decode($DatosGen['data'][0]['NombreCarr']);
            //var_dump($DatosGen['data'][0]);
            //die();
            
            if(!$image['data']){
                echo json_encode(['validacion'=>'Falta de fotografia']);
            }else{    
                $img=utf8_decode($image['data']['nombre_archivo']);
                $id=$usuario['data']['id_afiliado'];
                $name=utf8_decode($usuario['data']['nombre']);
                $app=utf8_decode($usuario['data']['apaterno']);
                $apm=utf8_decode($usuario['data']['amaterno']);
                //$img=utf8_decode($usuario['data']['foto']);
                
                //$img=utf8_decode($image['data']['nombre_archivo']);
                
                ob_start();
                require_once('../functions/fpdf183/fpdf.php');
                require_once('../functions/src/autoload.php');
                $pdf = new FPDI('P','mm', array(279.4,215.9));
                
                header('Content-type: application/pdf');
                //$filename = fopen('https://conacon.org/moni/siscon/img/credencial.pdf', 'rb',false, stream_context_create());
                //$pdf->setSourceFile($filename);
                //$pageCount=$pdf->setSourceFile('credencial.pdf');
                if(file_exists("../../lista_documentos/$id/$img")){
                    $image_mime = image_type_to_mime_type(exif_imagetype("../../lista_documentos/$id/$img"));
                    $Var = explode('/',$image_mime);
                    $pageCount=$pdf->setSourceFile('credencialfondo2.pdf');
                    for($i = 1; $i <= $pageCount; $i++){
                        $tplIdx = $pdf->importPage($i);
                        $pdf->AddPage('L',array(90,55.5));
                        $pdf->useTemplate($tplIdx,0,0,90);
                        if($i == 1){
    
                        $pdf->Image('../../lista_documentos/'.$id.'/'.$img,55.3,9.5,30.3,38,$Var[1]);
                        $pdf->SetFont('Helvetica','B',6.5);
                        $pdf->SetTextColor(202,24,28);
                        $pdf->setXY(5,21.5);
                        
                        $pdf->MultiCell(45,3,$name.' '.$app.' '.$apm,0,'L');
                    
                        $pdf->setXY(5,26);
                        $pdf->text(6,34,$pro);
    
    
                        $pdf->SetFont('Helvetica','B',4);
                        //<---Evita lo saltos de linea automaticos--->
                        $pdf->SetAutoPageBreak(false, 30); 
                        $pdf->SetTextColor(110,17,17);
                        //$pdf->text(6,39,$NombreGen);
                        $pdf->SetXY(5,37.5); 
                        $pdf->MultiCell(45,1.5,$NombreGen,0,'L');
        
                        $pdf->SetXY(5,43.5);
                        $pdf->SetFont('Helvetica','B',5);
                        $pdf->MultiCell(45,1.5,$NombreCarr,0,'L');
                        }
                    }
                    $pdf->Close();
                    $data = $pdf->Output('../../credenciales/'.$id.'-credencial-conacon.pdf','F');
                    $data = base64_encode($data);
                
                    //echo json_encode($data);
                    echo json_encode($resp);
                    ob_end_flush();

                }else{
                    echo json_encode(['validacion'=>'Imagen no encontrada']);
                }
            }

            break;
        
        case 'obtenerTarjeta':
            unset($_POST['action']);
            $usuario=$afiliados->obtenerusuario($_POST['idusuario']);//ID_AFILIADO
    
            $codesDir = "../../documents/qr/";   
            $codeFile = $_POST['idusuario'].'.png';//ID_AFILIADO
            QRcode::png('https://conacon.org/cv/?perfil='.$_POST['idusuario'], $codesDir.$codeFile, 'H', 5); //ID_AFILIADO
               
            $id=$usuario['data']['id_afiliado'];
            $name=utf8_decode($usuario['data']['nombre']);
            $app=utf8_decode($usuario['data']['apaterno']);
            $apm=utf8_decode($usuario['data']['amaterno']);
            $tel=utf8_decode($usuario['data']['celular']);
            $email=utf8_decode($usuario['data']['email']);
            $nameDoc =  $id.'-tarjeta-conacon.pdf';
            
            ob_start();
            require_once('../functions/fpdf183/fpdf.php');
            require_once('../functions/src/autoload.php');
            require_once('../functions/src/Fpdi.php');
            require_once('../functions/src/PdfParser/PdfParser.php');
            $new_pdf = new FPDI('P','mm', array(279.4,215.9));

            header('Content-type: application/pdf');
            //$filename = fopen('https://conacon.org/moni/siscon/img/credencial.new_pdf', 'rb',false, stream_context_create());
            //$new_pdf->setSourceFile($filename);
            $pageCount=$new_pdf->setSourceFile('tarjetapresentacion.pdf');
            for($i = 1; $i <= $pageCount; $i++){
                $tplIdx = $new_pdf->importPage($i);
                $new_pdf->AddPage('L',array(90,49.7));
                $new_pdf->useTemplate($tplIdx,0,0,90);
                if($i == 1){
                $new_pdf->SetFont('Helvetica','B',12);
                $new_pdf->SetTextColor(68,121,172);
                $new_pdf->setXY(39,20);
                $new_pdf->text(39,20,$name);
                $new_pdf->text(39,26,$app.' '.$apm);
                //$new_pdf->Write(0, $name.' '.$app.' '.$apm);
                $new_pdf->SetFont('Helvetica','B',8);
                $new_pdf->SetTextColor(255,255,255);
                $new_pdf->text(63,46,$tel);
                $new_pdf->text(14,46,$email);
                $new_pdf->SetTextColor(0,0,0);
                //$new_pdf->cell(200,200,$new_pdf->Image($codesDir.$codeFile,7.4,20,24,29.3), 0, 0, 'L', false );
                
                //$new_pdf->setXY(32,80);
                }else{
                    $new_pdf->Image($codesDir.$codeFile,7.2,12,22.5,26.5);
                }
            }
            $new_pdf->Close();
            file_put_contents('../../tarjetas/'.$nameDoc, $new_pdf->Output('','S'));
            ob_end_flush();
            
            break;

        case 'obtenerAfiliacion':
                unset($_POST['action']);
                $usuario=$afiliados->obtenerusuario($_POST['idusuario']);//ID_AFILIADO
                
                $vigencia=$afiliados->fechafinmembresia($usuario['data']['id_prospecto']);
                
                $prospectos=utf8_decode($usuario['data']['id_prospecto']);
                $pro=str_pad($prospectos, 6, '0', STR_PAD_LEFT);

                $id=$usuario['data']['id_afiliado'];
                $name=utf8_decode($usuario['data']['nombre']);
                $app=utf8_decode($usuario['data']['apaterno']);
                $apm=utf8_decode($usuario['data']['amaterno']);
                $vig=utf8_decode(substr($vigencia['data']['finmembresia'],0,10));
                $nvig=date("d-m-Y", strtotime($vig));
                
                $nameDoc =  $id.'-tarjeta-afiliacion.pdf';
                
                ob_start();
                require_once('../functions/fpdf183/fpdf.php');
                require_once('../functions/src/autoload.php');
                require_once('../functions/src/Fpdi.php');
                require_once('../functions/src/PdfParser/PdfParser.php');
                $new_pdf = new FPDI('P','mm', array(279.4,215.9));
    
                header('Content-type: application/pdf');
                //$filename = fopen('https://conacon.org/moni/siscon/img/credencial.new_pdf', 'rb',false, stream_context_create());
                //$new_pdf->setSourceFile($filename);
                $pageCount=$new_pdf->setSourceFile('tarejtaafiliacion.pdf');
                for($i = 1; $i <= $pageCount; $i++){
                    $tplIdx = $new_pdf->importPage($i);
                    $new_pdf->AddPage('L',array(90,49.7));
                    $new_pdf->useTemplate($tplIdx,0,0,90);
                    if($i == 1){
                    $new_pdf->SetFont('Helvetica','B',9);
                    $new_pdf->SetTextColor(68,121,172);
                    $new_pdf->setXY(39,20);
                    $new_pdf->text(33,26,$name.' '.$app.' '.$apm);
                    $new_pdf->text(34,33,$pro);
                    $new_pdf->text(19,39,$nvig);
                    }
                }
                $new_pdf->Close();
                file_put_contents('../../tarjeta-afiliacion/'.$nameDoc, $new_pdf->Output('','S'));
                ob_end_flush();
                
                break;

            case "obtenerSolicitudesCredencial":
                unset($_POST['action']);
                $SolicitudCredencial = $afiliados->obtenerSolicitudesCredencial($_POST['idusuario']);
                echo json_encode($SolicitudCredencial);
                break;

            case "InsertarSolicitudCred":
                unset($_POST['action']);
                $insertarSolcitud = $afiliados->InsertarSolicitudCred($_POST);
                echo json_encode($insertarSolcitud);
                break;

        default:
                # code...
            break;
        }
    
}else{
    header("Location: ../../index.php");
}
