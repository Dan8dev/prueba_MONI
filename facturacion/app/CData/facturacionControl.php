<?php

session_start();

if(isset($_SESSION['alumno']) || isset($_POST['android_id_afiliado']) || isset($_POST['idAlumno']) || 
isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 9){

    require "../Models/facturacionModel.php";

    $action = $_POST['action'];
    $fact = new DataFactura();

    switch($action){

        case 'subirDatos':

            // die();
            $resp = [];
            $id = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
            if(!file_exists('../lista_constancias/'.$id)){
                mkdir('../lista_constancias/'.$id, 0777,true);
            }


            $nName = 0;
            if(isset($_FILES['pdf']) && $_FILES['pdf'] != 'undefined'){
                $tmp_name = $_FILES['pdf']['tmp_name'];
                $uploads_dir = "../lista_constancias/".$id;
                

                $fileT = explode('.', $_FILES['pdf']['name']);
                $extensionFI = $fileT[sizeof($fileT)-1];
                $date = date('Y-m-d H-i-s');
                $nName = $id.'_pdf_'.$date.'.'.$extensionFI;
                
                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            }else{
                unset($_POST['pdf']);
            }


            $_POST['file_pdf'] = $nName;
            $_POST["id_us"] = $id;
            $_POST['changeDa'] = '';
            $_POST['reason'] = '';
            $_POST['statusDa'] = '';

            unset($_POST['android_id_prospecto']);
            unset($_POST['action']);

            $datas = $fact->registrardatosfactuacion($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;
        case 'datosPagos';
            $resp = [];
            
            $datas = $fact->datosFacturas($_POST);
            $totales = 0;
            $totalesD = 0;
            $data = Array();
            $dataNames = Array();
            $mon = 0;
            $monD = 0;
			//var_dump($datas->fetchObject());
            while($dato= $datas->fetchObject()){
                
                if( $dato->link_pdf != ''){
                    if($dato->status_dc == 'enviado'){
                        $input = '<button type="button" class="btn btn-danger btn-plus waves-effect waves-light send_doc" data-toggle="modal" onClick="deleteFac('.$dato->id.','.$dato->idAsistente.',`'.$dato->link_pdf.'`)">Eliminar</button>';
                        $button = '<a class="btn btn-primary d-block mb-2"  href="../../facturacion/app/lista_facturacion/'.$dato->idAsistente."/".$dato->link_pdf.'" download>Descargar PDF</a>'.
                        '<a class="btn btn-primary d-block"  href="../../facturacion/app/lista_facturacion/'.$dato->idAsistente."/".$dato->link_xml.'" download>Descargar XML</a>';
                    }else{
                        $button = '<button type="button" class="btn btn-primary waves-effect waves-light send_doc" data-toggle="modal" onClick="target_id('.$dato->id_pago.','.$dato->idAsistente.')" id="btn-'.$dato->id_pago.'">Subir</button>';
                        $input = '<input type="checkbox" id="'.$dato->id_pago.'" class="checked_box" onclick="facGlobals('.$dato->id_pago.','.$dato->idAsistente.')">';
                    }
                }else{
                    $button = '<button type="button" class="btn btn-primary waves-effect waves-light send_doc" data-toggle="modal" onClick="target_id('.$dato->id_pago.','.$dato->idAsistente.')" id="btn-'.$dato->id_pago.'">Subir</button>';
                    $input = '<input type="checkbox" id="'.$dato->id_pago.'" class="checked_box" onclick="facGlobals('.$dato->id_pago.','.$dato->idAsistente.')">';
                }

                if($dato->apref != ''){
                    $refe = $dato->apref;
                }else{
                    $refe = $dato->codigo_de_autorizacion;
                }
                if($dato->cargo_retardo != NULL){
                    $rec = $dato->cargo_retardo;
                }else{
                    $rec = 0;
                }
               
                if($dato->metodo_de_pago == 'credit'){
                    $fp = "Tarjeta de crédito";
                }else if($dato->metodo_de_pago == 'debit'){
                    $fp = "Tarjeta de débito";
                }else{
                    $fp = $dato->metodo_de_pago;
                }
                
                if($dato->moneda == 'usd'){
                     $monD = $dato->montopagado+$rec;
                    $dollars = $monD;
                    $pesos = 0;
                    
                }else{
                   $mon = $dato->montopagado+$rec;
                    $pesos = $mon;
                    $dollars = 0;
                     
                }

                $data[] = array(

                    0=> $input,
                    1=> '<button type="button" class="btn_name" onClick="showData('.$dato->idAsistente.')">'.trim($dato->nombreA).' '.trim($dato->aPaterno).' '.trim($dato->aMaterno).'</button>',
                    2=> $dato->n_institucion,
                    3=> $dato->nombre_rz,
                    4=> $dato->rfc,
                    5=> $dato->concepto,
                    6=> $dato->fechapago,
                    7=> $refe,
                    8=> $fp,
                    9=> "Pue Pago en una sola exhibición",
                    10=> $dato->moneda,
                    11=> number_format($pesos,2),
                    12=> number_format($dollars,2),
                    13=> $button
                    
                );

                $dataNames[]  =array(
                    "nameA"=>trim($dato->nombre).' '.trim($dato->aPaterno).' '.trim($dato->aMaterno)
                );
                $totales += $pesos;
                $totalesD += $dollars;
            }
           
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data,
                'data2'=>number_format($totales,2),
                'data3'=>number_format($totalesD,2)
                // 'dataNames'=>$dataNames
            );

            echo json_encode($result);

        break;
        case 'subirFactura':
            $resp = [];
            $num = '';
            unset($_POST['action']);
            $tmp_name = $_FILES['pdf']['tmp_name'];
            $tmp_name1 = $_FILES['xml']['tmp_name'];

            $fileT = explode('.', $_FILES['pdf']['name']);
            $fileT1 = explode('.', $_FILES['xml']['name']);
            $extensionFI = $fileT[sizeof($fileT)-1];
            $extensionFI1 = $fileT1[sizeof($fileT1)-1];
            $date = date('Y-m-d H:i:s');

            foreach(array_keys($_POST['idProspecto']) as $key){
                $id = $_POST['idProspecto'][$key];
                $pay = $_POST['idPayment'][$key];

            if(!file_exists('../lista_facturacion/'.$id)){
                mkdir('../lista_facturacion/'.$id, 0707);
            }

            $uploads_dir = "../lista_facturacion/".$id;
            $nName = $id.'_pdf_'.$date.'.'.$extensionFI;
            $nName1 = $id.'_xml:'.$date.'.'.$extensionFI1;
            
            move_uploaded_file($tmp_name, "$uploads_dir/$nName");
            move_uploaded_file($tmp_name1, "$uploads_dir/$nName1");

            $num = $fact->subirFacturas($id,$pay,$nName,$nName1);

            }
            
            $resp = ['estatus'=>'ok', 'data'=>$num];

            echo json_encode($resp);

        break;
        case 'changeResq':
            $resp = [];
            $_POST['id_us'] = isset($_POST['android_id_prospecto']) ? $_POST['android_id_prospecto'] : $_SESSION['alumno']['id_prospecto'];
            $_POST['change'] = 'pendiente';
        
            unset($_POST['action']);
            $datas = $fact->cRequest($_POST);
            
            echo json_encode($datas);
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

                if($datas['data'] != []){
                    $resp = ['estatus'=>'ok', 'data'=>$datas['data']];
                }else{
                    $resp = ['estatus'=>'error', 'data'=>$datas['data']];
                }
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
                if($dato->cargo_retardo != NULL){
                    $rec = $dato->cargo_retardo;
                }else{
                    $rec = 0;
                }
                $mon = $dato->montopagado+$rec;
                $data[] = array(
                    0=> $dato->concepto,
                    1=> number_format($mon,2),
                    2=> $dato->fechapago,
                    3=> $dato->apref,
                    4=> $dato->metodo_de_pago,
                    5=> '<a class="btn btn-primary"  href="https://moni.com.mx/facturacion/app/lista_facturacion/'.$id."/".$dato->link_pdf.'" download>Descargar</a>',
                    6=> '<a class="btn btn-primary"  href="https://moni.com.mx/facturacion/app/lista_facturacion/'.$id."/".$dato->link_xml.'" download>Descargar</a>'
                    
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
        case 'dataBillUS':
            $resp = [];
        
            unset($_POST['action']);
            $datas = $fact->bFacturar($_POST);

            if($datas['estatus'] != 'ok'){
                $resp = ['estatus'=>'error'];
                echo json_encode($resp);
            }else{

                foreach($datas['data'] as $key=>$value){

                    
                    if($datas['data'][$key]['nationality'] == 'Mexico'){
                        $datas['data'][$key]['link_conts'] = 'app/lista_constancias/'.$value['id_prospecto'].'/'.$value['link_conts'];
                    
                        $retroceso_controller = '../../';
                        if(!file_exists($retroceso_controller.$datas['data'][$key]['link_conts'] )){
                            $datas['data'][$key]['link_conts'] = 'https://conacon.org/moni/facturacion/app/lista_constancias/'.$value['id_prospecto'].'/'.$value['link_conts'];
                        }
                    }else{
                        $datas['data'][$key]['link_conts'] = '';
                    }
                   //var_dump($datas['data'][$key]['link_conts']);               
                }
                echo json_encode($datas['data']);
            }
            
        break;
        case 'saveStatus':

            unset($_POST['action']);
            $datas = $fact->saveStatus($_POST);
            echo json_encode($datas);
        break;
        case  'deleteBill':
            unset($_POST['action']);
            $datas = $fact->deleteBill($_POST);
            echo json_encode($datas);
        break; 
    }

}
 
?>
