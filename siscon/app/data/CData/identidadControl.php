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
            $usuario=$afiliados->obtenerusuario($_POST['idusuario']);
            //var_dump($usuario['data']);//ID_AFILIADO
            $vigencia=$afiliados->fechafinmembresia($usuario['data']['id_prospecto']);
            $vigencia2=$afiliados->fechafinGeneracion('22');
            $image = $afiliados->obtenerNombreCredencial($_POST['idusuario']);//ID_AFILIADO
            //$vig=utf8_decode(substr($vigencia['data']['finmembresia'],0,10));
            //$nvig=date("d-m-Y", strtotime($vig));
            $prospectos=utf8_decode($usuario['data']['id_afiliado']);
            $pro=str_pad($prospectos, 6, '0', STR_PAD_LEFT);
            $resp =  $afiliados->obtenerValidacionFoto($_POST['idusuario'])['data'];
            
            
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
                    $pageCount=$pdf->setSourceFile('credencialfondo.pdf');
                    for($i = 1; $i <= $pageCount; $i++){
                        $tplIdx = $pdf->importPage($i);
                        $pdf->AddPage('L',array(90,49.7));
                        $pdf->useTemplate($tplIdx,0,0,90);
                        if($i == 1){
                        $pdf->SetFont('Helvetica','B',12);
                        $pdf->SetTextColor(255,255,255);
                        $pdf->setXY(32,24);
                        $pdf->text(29,20,$name);
                        $pdf->text(29,26,$app.' '.$apm);
                        //bien$pdf->text(32,26,$name.' '.$app.' '.$apm);
                        //$pdf->Write(0, $name.' '.$app.' '.$apm);
                        //$pdf->text(0, $name.' '.$app.' '.$apm);
                        //Validacion de formato de imagen
                        
    
                        $pdf->Image('../../lista_documentos/'.$id.'/'.$img,4.2,7.4,22.3,27.1,$Var[1]);
                        $pdf->SetFont('Helvetica','B',9);
                        $pdf->SetTextColor(68,121,172);
                        $pdf->text(34,40,$pro);
                        $pdf->text(21,44,'10/22');
                        //$pdf->Image('../../img/afiliados/'.$img,7.4,8.1,24,29.3);
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
                $new_pdf->useTemplate($tplIdx,0,0,90,49.7);
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
