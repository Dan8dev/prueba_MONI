<?php

session_start();

if(isset($_SESSION['alumno']) || isset($_POST['android_id_afiliado']) || isset($_POST['idAlumno']) || 
isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 4){

    require "../Model/facturacionModel.php";




    $action = $_POST['action'];
    $fact = new DataFactur();

    switch($action){

        case 'subirDatos':
            $resp = [];

            $_POST["id_us"] = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];

            unset($_POST['android_id_prospecto']);
            unset($_POST['action']);

            $datas = $fact->registrardatosfactuacion($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;


        case 'datosPagos';
            $resp = [];
            
            $datas = $fact->datosFacturas($_POST);
            
            $data = Array();
            while($dato= $datas->fetchObject()){
                
                $data[] = array(
                    0=> $dato->nombre,
                    1=> $dato->nombre_rz,
                    2=> $dato->email,
                    3=> $dato->calle.' '.$dato->numero.' '.$dato->colonia.' '.$dato->ciudad.' '.$dato->estado.' '.$dato->cp,
                    4=> number_format($dato->montopagado),
                    5=> $dato->fechapago,
                    6=> $dato->concepto,
                    7=> $dato->referencia,
                    8=> $dato->metodo_de_pago,
                    9=> $dato->rfc,
                    10=> $dato->uso_cfdi,
                    11=> $dato->link_pdf == '' ? '<button data-target="#sendBilling" type="button" class="btn btn-primary waves-effect waves-light send_doc" data-toggle="modal" onClick="target_id('.$dato->id_pago.')">Subir</button>' : '<button disabled data-target="" type="button" class="btn btn-primary waves-effect waves-light send_doc" data-toggle="modal">Subir</button>'
                    
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

        case 'subirFactura':
            $resp = [];
            $id = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
           
            unset($_POST['android_id_prospecto']);
            unset($_POST['action']);

            if(!file_exists('../../lista_facturacion/'.$id)){
                mkdir('../../lista_facturacion/'.$id, 0707);
            }

            $tmp_name = $_FILES['pdf']['tmp_name'];
            $tmp_name1 = $_FILES['xml']['tmp_name'];
            $uploads_dir = "../../lista_facturacion/".$id;
            

            $fileT = explode('.', $_FILES['pdf']['name']);
            $fileT1 = explode('.', $_FILES['xml']['name']);
            $extensionFI = $fileT[sizeof($fileT)-1];
            $extensionFI1 = $fileT1[sizeof($fileT1)-1];
            $date = date('Y-m-d H:i:s');
            $nName = $id.'_pdf_'.$date.'.'.$extensionFI;
            $nName1 = $id.'_xml:'.$date.'.'.$extensionFI1;
            
            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            $statFile = move_uploaded_file($tmp_name1, "$uploads_dir/$nName1");

            $_POST['file_pdf'] = $nName;
            $_POST['file_xml'] = $nName1;
            $_POST['id_us'] = $id;

            $datas = $fact->subirFacturas($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            
            echo json_encode($resp);

        break;

        case 'cargarDatosAfFac':
            $resp = [];
            if(isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto']){
                $id = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
            }else{
                $id = '';
            }
            if($id != ''){
                
                $datas = $fact->cargarDatosAfFac($id);
                $resp = ['estatus'=>'ok', 'data'=>$datas['data']];
            }else{
                $resp = ['estatus'=>'error', 'data'=>['']];
            }
            

            echo json_encode($resp);

        break;
        case 'datosFactura':
            $resp = [];
            if(isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto']){
                $id = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
            }else{
                $id = '';
            }

            $datas = $fact->descargarFacturas($id);
            
            $data = Array();
            while($dato= $datas->fetchObject()){
                
                $data[] = array(
                    0=> $dato->concepto,
                    1=> number_format($dato->montopagado),
                    2=> $dato->fechapago,
                    3=> $dato->referencia,
                    4=> $dato->metodo_de_pago,
                    5=> '<a class="btn btn-primary"  href="../app/lista_facturacion/'.$id.'/'.$dato->link_pdf.'" download>Descargar</a>',
                    6=> '<a class="btn btn-primary"  href="../app/lista_facturacion/'.$id.'/'.$dato->link_xml.'" download>Descargar</a>'
                    
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
    }

}
 
?>