<?php

session_start();

if(isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] == 34){

    require "../Models/RequisicionesModel.php";

    $action = $_POST['action'];
    $fact = new DataRequest();

    switch($action){

        case 'subirDatos':

            unset($_POST['action']);
            $_POST['idArea'] = $_SESSION['usuario']["persona"]["col_area"];

            // echo  $_POST['idArea']; 
            // die();
            $datas = $fact->registrarProveedores($_POST);

            echo json_encode($datas);
        break;
        case 'updateProv':
            unset($_POST['action']);
            //$_POST['idArea'] = $_SESSION['usuario']["persona"]["col_area"];

            $datas = $fact->updateReqProv($_POST);

            echo json_encode($datas);
        break;
        case 'obtenerProv':
            $resp = [];
            unset($_POST['action']);
            $idA = $_SESSION['usuario']["persona"]["col_area"];
            $datas = $fact->obtenerProveedores($idA);
            $data = array();
            $dataN = array();
            //var_dump($loadAlumnos);
            while($dato=$datas->fetchObject()){
            
                $boton = '<button class="btn btn-secondary editb" onclick=" getProveedores(0,-1,'.$dato->id_prov.')">Editar</button>';
                // $boton .= $dato->estatus == 1 ? '<button class="btn btn-primary" onclick="editUs('.$dato->id_prov.', 0)">Desactivar</button>': '<button class="btn btn-info" onclick="editUs('.$dato->id_prov.', 1)">Activar</button>';

                $data[]=array(
                0=> $dato->nrazon,
                1=> $dato->calle.' '.$dato->n_ext.' '.$dato->n_int.' '.$dato->colonia.' '.$dato->ciudad.' '.$dato->estado.' '.$dato->cp,
                2=> $dato->email,
                3=> $dato->telefono,
                4=> $dato->nombre_banco,
                5=> $dato->num_cuenta,
                6=> $dato->num_clabe,
                7 => $boton
                );
                array_push($dataN,$dato);
            }

            //var_dump($data);
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data,
                'adata'=>$dataN
            );

            echo json_encode($result);
        break;
        case 'subirReq':
            $resp = [];
            $data = [];
            unset($_POST['action']);
            //var_dump($_POST);
            $data['idUs'] = $_SESSION["usuario"]['idPersona'];

            $file = fopen("../utils/utils.txt","r") or die ("Error Fatal 1");
            if(! feof($file)){
                $get = fgets($file);
            }
            $data['folio'] = $get;
            $data['op'] = $_POST['op'];

            foreach(array_keys($_POST['prov']) as $key){

                $data['prov'] = $_POST['prov'][$key];
                $data['cant'] = $_POST['cant'][$key];
                $data['uni'] = $_POST['uni'][$key];
                $data['concp'] = $_POST['concp'][$key];
                $data['model'] = $_POST['model'][$key];
                $data['mark'] = $_POST['mark'][$key];
                $data['linkBuy'] = $_POST['linkBuy'][$key];
                $data['price'] = $_POST['price'][$key];
                $data['subto'] = $_POST['subto'][$key];
                $datas = $fact->subirReq($data);
                    
            }
            
            $resp = ['estatus'=>'ok', 'data'=>$datas];

            echo json_encode($resp);
        break;
        case 'dataReqs':
            $resp = [];
            $respName = [];
            $totals = 0;
            $opcion = str_replace('s','',$_POST['option']);
            unset($_POST['action']);

            $_POST['idUs'] = $_SESSION["usuario"]['idPersona'];

            $datas = $fact->obtenerRequest($_POST);

            while($data= $datas->fetchObject()){
                if(isset($data->d_reason)){
                    $input =  '<b>Motivo de rechazo</b>: '.$data->d_reason;
                }else{
                    $input = '';
                }
                $folio ='<button class="btn_transparent" onClick="showBreakDown(``,`'.$data->folio.'`,`'.$opcion.'`,`'.$data->fecha_req.'`)">'.$data->folio.'</button>';
                $resp[] = array(
                    0=> $folio,
                    1=> $data->total,
                    2=> $data->fecha_req,
                    3=> $data->fecha_apro != NULL ? $data->fecha_apro : 'Sin revisión',
                    4=> $data->fecha_pag != NULL ? $data->fecha_pag : 'Sin revisión',
                    5=> $data->fecha_decl != NULL ? $data->fecha_decl : ($data->fecha_apro != NULL ? 'No aplica' : 'Sin revisión'),
                    6=> $input
                );
                //$respName [] = array(trim($data->nombre).' '.trim($data->apaterno).' '.trim($data->amaterno));
                $totals += $data->total;
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($resp),
                'iTotalDisplayRecords'=>count($resp),
                'aaData'=>$resp,
                'data2'=>number_format($totals,2),
                //'dataNames'=>$respName
            );

            echo json_encode($result);

        break;
        case 'dataReqsA':
            $resp = [];
            $respName = [];
            $totals = 0;
            $opcion = str_replace('s','',$_POST['option']);
            unset($_POST['action']);
            $_POST['idUs'] = $_SESSION["usuario"]['idPersona'];
            $_POST['tAccess'] = $_SESSION["usuario"]['estatus_acceso'];

            $datas = $fact->obtenerRequest($_POST);

            while($data= $datas->fetchObject()){

                if($data->tbraFolio != null && $data->tbraFolio != ''){
                    $up = 1;
                }else{
                    $up = 0;
                }
                $prename = trim($data->nombres).' '.trim($data->apellidoPaterno).' '.trim($data->apellidoMaterno);
                $names = '<button class="btn_transparent" onClick="showBreakDown('.$data->idPersona.',`'.$data->folio.'`,`'.$opcion.'`,`'.$data->fecha_req.'`,`'.$prename.'`,'.$up.')">'.$prename.'</button>';
                $resp[] = array(
                    
                    0=> $names,
                    1=> $data->folio,
                    2=> $data->fecha_req,
                    3=> $data->fecha_apro != NULL ? $data->fecha_apro : 'Sin revisión',
                    4=> $data->fecha_pag != NULL ? $data->fecha_pag : 'Sin revisión',
                    5=> $data->fecha_decl != NULL ? $data->fecha_decl : ($data->fecha_apro != NULL ? 'No aplica' : 'Sin revisión'),
                    6=> $data->total,
                    7=> $data->nombre_area

                );
                //$respName [] = array(trim($data->nombre).' '.trim($data->apaterno).' '.trim($data->amaterno));
                $totals += $data->total;
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($resp),
                'iTotalDisplayRecords'=>count($resp),
                'aaData'=>$resp,
                'data2'=>number_format($totals,2),
                //'dataNames'=>$respName
            );

            echo json_encode($result);

        break;
        case 'showBKD': 
            $resp = [];
            $respName = [];
            $totals = 0;
            $opcion = $_POST['option'];
            $us = $_POST['us'];
            unset($_POST['action']);
            $_POST['idUs'] = $_SESSION["usuario"]['idPersona'];
            $datas = $fact->obtenerBreakdown($_POST);

                //$button = '<button class="btn-success me-1" onClick="">Aprobar</button><button class="btn-danger">Rechazar</button>';
                //$input = '<input type="checkbox">';

            while($data= $datas->fetchObject()){
                if($us != 'admin'){

                    $button = '';
                    $input1 = '';
                    $input = '';
                    if($opcion == 'pagada' || $opcion == 'afacturar'){
                        if($data->num_serie != ''){
                            $button= $data->num_serie;
                        }else{
                            $button = '<input class="input_series form-control" id="'.$data->id_req.'" type="text" placeholder="Ingresa n° de serie">';
                        }
                        if($data->status_req == 'pagada'){
                            $input1 = '<button class="btn btn-success" onclick="saveFiles('.$data->id_req.',`'.$data->link_comp.'`,'.$data->id_com_us.','.$data->id_comp_pago.')">Subir factura</button>';
                        }
                    }else{
                        $input = "";
                        if($opcion == 'rechazada'){
                            $input1 = '<b>Motivo de rechazo</b>: '.$data->d_reason;
                        }
                    }

                    $cases = $data->nrazon;

                }else{  
                    $input = '<input  type="checkbox" id="'.$data->id_req.'" class="checked_box" onclick="payGlobals('.$data->id_req.',this)">';
                    $input1 = '';

                    switch($opcion){

                        case 'pendiente':
                            $button = '';
                            break;
                        case 'pagada':
                            $button = $data->num_mov_pago != NULL ? $data->num_mov_pago : ($data->num_mov_pago != '' ? $data->num_mov_pago : '<input class="form-control" type="text" id="numMovText-'.$data->id_req.'">');
                            $input = '<button class="btn btn-success" onclick="saveFiles('.$data->id_req.')">Subir comprobante</button>';
                            $input1 = $data->centro_costo;
                            break;
                        case 'aprobada':
                            
                            $button = $data->num_mov_pago != NULL ? $data->num_mov_pago : ($data->num_mov_pago != '' ? $data->num_mov_pago : '<input class="form-control" type="text" id="numMovText-'.$data->id_req.'">');
                            $input1 = '<input class="form-control" type="text" id="cCosto-'.$data->id_req.'">';
                            break;
                        case 'rechazada':
                            $button = '';
                            if(isset($data->d_reason)){
                                $input = '<b>Motivo de rechazo</b>: '.$data->d_reason;
                            }else{
                                $input = '';
                            }
                            break;
                        case 'comprobanteincorrecto':
                            $input = '<b>Motivo</b>:'.$data->motivo;
                            $button = '<button class="btn btn-success" onclick="previewDocs('.$data->id_req.',`'.$opcion.'`)">Ver comprobante</button>';
                        break;
                        case 'facturaincorrecto':
                            $input = '<b>Motivo</b>:'.$data->motivo;
                            $button = '<button class="btn btn-success" onclick="previewDocs('.$data->id_req.',`'.$opcion.'`)">Ver factura</button>';
                        break;
                        default:
                            if(isset($data->motivo)){
                                $input = $data->motivo;
                            }else{
                                $input = '';
                            }
                            $button = $data->num_mov_pago;
                        break;
                    }
                    if($data->id_prov > 0){
                        $cases = $data->nrazon;
                    }else{
                        $cases = '<select name="prov[]" id="'.$data->id_req.'" class="form-control listProveedores provDrop" required></select>';
                    }
                  
                }

                $resp[] = array(
                    
                    0=> $cases,
                    1=> $data->cantidad,
                    2=> $data->unidad,
                    3=> $data->concepto,
                    4=> $data->modelo,
                    5=> $data->marca,
                    6=> $data->link_ref_comp,
                    7=> $data->precio,
                    8=> $data->subtotal,
                    9=> $button,
                    10=> $input1,
                    11=> $input
                );
                //$respName [] = array(trim($data->nombre).' '.trim($data->apaterno).' '.trim($data->amaterno));
                $totals += $data->subtotal;
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($resp),
                'iTotalDisplayRecords'=>count($resp),
                'aaData'=>$resp,
                'data2'=>number_format($totals,2),
                //'dataNames'=>$respName
            );

            echo json_encode($result);
        break;
        case 'updateReq':
            $resp = [];
            $status = $_POST['status'];
            $idA = '2';
            $reason = $_POST['reason'];
            unset($_POST['action']);

            foreach(array_keys($_POST['idReq']) as $key){
                $id = $_POST['idReq'][$key];

                $data = [
                    'status'=>$status,
                    'idReq'=>$id,
                    'idAd'=>$idA,
                    'reason'=>$reason,
                    'numMov'=>isset($_POST['numMov'][$key]) ?  $_POST['numMov'][$key] : NULL,
                    'numCosto'=>isset($_POST['numCosto'][$key]) ?  $_POST['numCosto'][$key] : NULL
                ];

                $num = $fact->updateReq($data);
            }
            $resp = ['estatus'=>'ok', 'data'=>$num];

            echo json_encode($resp);
        break;
        case 'saveFile':

            $resp = [];
            unset($_POST['action']);
            $folio = $_POST['idReq'];
            $form = $_POST['fileSave'];
            $id = $_SESSION["usuario"]['idPersona'];


            switch($form){

                case 'saveSigns':

                    $tmp_name = $_FILES['pdf']['tmp_name'];
    
                    $fileT = explode('.', $_FILES['pdf']['name']);
                    $extensionFI = $fileT[sizeof($fileT)-1];
                    $date = date('Y-m-d H:i:s');
    
                    if(!file_exists('../lista_requisiciones/'.$id)){
                        mkdir('../lista_requisiciones/'.$id, 0707);
                    }
                    $uploads_dir = "../lista_requisiciones/".$id;
                    $nName = $folio.'_'.$date.'.'.$extensionFI;
                    
                    move_uploaded_file($tmp_name, "$uploads_dir/$nName");
    
                    $_POST['files'] = $nName;
                    $_POST['idUs'] = $id;

                break;
                case 'saveComp':

                    $_POST['dateReg'] = date('Y-m-d');

                    $tmp_name = $_FILES['pdf']['tmp_name'];
    
                    $fileT = explode('.', $_FILES['pdf']['name']);
                    $extensionFI = $fileT[sizeof($fileT)-1];
                    $date = date('Y-m-d H:i:s');
    
                    if(!file_exists('../lista_comprobantes/'.$id)){
                        mkdir('../lista_comprobantes/'.$id, 0707);
                    }
                    $uploads_dir = "../lista_comprobantes/".$id;
                    $nName = $folio.'_'.$date.'.'.$extensionFI;
                    
                    move_uploaded_file($tmp_name, "$uploads_dir/$nName");
    
                    $_POST['files'] = $nName;
                    $_POST['idUs'] = $id;

                break;
                case 'saveFac':

                    $tmp_name = $_FILES['pdf']['tmp_name'];
                    $tmp_name1 = $_FILES['xml']['tmp_name'];
        
                    $fileT = explode('.', $_FILES['pdf']['name']);
                    $fileT1 = explode('.', $_FILES['xml']['name']);
                    $extensionFI = $fileT[sizeof($fileT)-1];
                    $extensionFI1 = $fileT1[sizeof($fileT1)-1];
                    $date = date('Y-m-d H:i:s');

                    if(!file_exists('../lista_facturas/'.$id)){
                        mkdir('../lista_facturas/'.$id, 0707);
                    }
        
                    $uploads_dir = "../lista_facturas/".$id;
                    $nName = $id.'_pdf_'.$date.'.'.$extensionFI;
                    $nName1 = $id.'_xml:'.$date.'.'.$extensionFI1;
                    
                    move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                    move_uploaded_file($tmp_name1, "$uploads_dir/$nName1");

                    $_POST['file_pdf'] = $nName;
                    $_POST['file_xml'] = $nName1;
                    $_POST['idUs'] = $id;

                break;
                case 'no-option':
                    echo json_encode($resp);
                    die();
                break;
            }

            $datas = $fact->saveFiles($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data'],'idUS'=>$id];

            echo json_encode($resp);
            
        break;
        case 'saveSerie':
            $resp = [];
            $num = '';
            unset($_POST['action']);

            foreach(array_keys($_POST['idReq']) as $key){
                $id = $_POST['idReq'][$key];
                $serie = $_POST['serie'][$key];

                $num = $fact->updateSerie($id,$serie);

            }
            
            $resp = ['estatus'=>'ok', 'data'=>$num,'idUS'=>$_SESSION["usuario"]['idPersona']];

            echo json_encode($resp);
        break;
        case 'linkDoc':
            $resp = [];
            unset($_POST['action']);
            $id = $_POST['idReq'];
            $datas = $fact->obtenerDocs($id);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;
        case 'changeDocs':
            $resp = [];
            unset($_POST['action']);
            unset($_POST['fac']);
            $_POST['idUs'] = $_SESSION["usuario"]['idPersona'];
            if(isset($_POST['factura'])){
                $_POST['comprobante'] = NULL;
                $_POST['tipo'] = 'factura';
            }else{
                $_POST['factura'] = NULL;
                $_POST['tipo'] = 'comprobante';
            }
            $datas = $fact->changeDocs($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp); 
        break;
        case 'selectDpto':
            $resp = [];
            unset($_POST['action']);
            $datas = $fact->obtenerDepto();
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;
        case 'selectuser':
            unset($_POST['action']);
            
            $id = $_SESSION["usuario"]['idPersona'];
            $type = $_SESSION["usuario"]['idTipo_Persona'];
            $loadAlumnos = $fact->getUsers($id,$type);
            $data = Array();
            $dataN = array();
            //var_dump($loadAlumnos);
            while($dato=$loadAlumnos->fetchObject()){
            
                $boton = '<button class="btn btn-secondary editb" onclick="editUs('.$dato->idPersona.', 3)">Editar</button>';
                $boton .= $dato->estatus == 1 ? '<button class="btn btn-primary" onclick="editUs('.$dato->idPersona.', 0)">Desactivar</button>': '<button class="btn btn-info" onclick="editUs('.$dato->idPersona.', 1)">Activar</button>';

                switch($dato->estatus_acceso){
                    case 1:
                        $rol = 'Administrador';  
                    break;
                    case 2:
                        $rol = 'Jefe de departamento';
                    break;
                    case 3:
                        $rol = 'Área contable';
                    break;
                    case 4:
                        $rol = 'Gestor de transferencias';
                    break;
                    default:
                    break;

                }
                $data[]=array(
                0=> $dato->nombres.' '.$dato->apellidoPaterno.' '.$dato->apellidoMaterno,
                1=> $dato->email,
                2=> $rol,
                3=> $dato->nombre_area,
                4=> $boton
                );
                $dataN[]=array(
                    'idPersona'=>$dato->idPersona,
                    'nombres'=> $dato->nombres,
                    'aPaterno'=>$dato->apellidoPaterno,
                    'aMaterno'=>$dato->apellidoMaterno,
                    'email'=> $dato->email,
                    'area' => $dato->col_area,
                    'estado'=>$dato->estatus_acceso
                    );
            }

            //var_dump($data);
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data,
                'adata'=>$dataN
            );
            echo json_encode($result);
        break;
        case 'createUs':
            $resp = [];
            unset($_POST['action']);
            $_POST['idUsM'] = $_SESSION["usuario"]['idPersona'];
            $_POST['idTP'] = $_SESSION["usuario"]['idTipo_Persona'];
            if(isset($_POST['dpto'])){
                $_POST['statusD'] = 'active';
            }
            $datas = $fact->setUsers($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;
        case 'createDpto':
            $resp = [];
            unset($_POST['action']);

            if(isset($_POST['dpto'])){
                $_POST['statusD'] = 'active';
            }
            $datas = $fact->Depto($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;
        case '':
        break;
    }

}
?>