<?php

session_start();

if(isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] == 36){

    require_once '../../Model/conexion/conexion.php';
    require_once "../../Model/areasmedicas/areasMedicasModel.php";


    $areas = new areasMedicas();
    $action = $_POST['action'];
    switch($action){

        case 'getGen':
            unset($_POST['action']);

            $datas =  $areas->getGen($_POST,2,0);

            echo json_encode($datas);
        break;
        case 'selectTutor':

            unset($_POST['action']);
            $datas = $areas->consultarTutores();
            $resp = [];
            $resp1 = [];

            foreach($datas as $key=>$alue){
    
                $datas[$key]['carreras'] = $areas->getCarreraMa($datas[$key]['id']);
                $datas[$key]['procedimientos'] = [];
                $datas[$key]['generaciones']  = [];
                
                foreach($datas[$key]['carreras'] as $k=>$vl){

                    $procs = $areas->getCarreraPro($datas[$key]['carreras'][$k]['idCarrera'],2);
                
                        $datas[$key]['procedimientos'] = $procs;
                
                    foreach($datas[$key]['procedimientos'] as $p=>$vp){

                        $content = $areas->getGen($datas[$key]['carreras'][$k]['idCarrera'],1, $datas[$key]['procedimientos'][$p]['idpm'])['data'];
                        
                        $trues = in_array($content,$datas[$key]['generaciones'],true);

                        if(!$trues){
                            $datas[$key]['generaciones'][] = $content;
                        }
                        
                    }
                }
            }

            foreach($datas as $data){

                $telefono = $data['telefono'] == NULL ? 'S/T' : ($data['telefono'] == '' ? 'S/T' : $data['telefono']);
                $celular = $data['celular'] == NULL ? 'S/T' : ($data['celular'] == '' ? 'S/T' : $data['celular']);
                $telefono_trabajo = $data['telefono_trabajo'] == NULL ? 'S/T' : ($data['telefono_trabajo'] == '' ? 'S/T' : $data['telefono_trabajo']);
                $telefono_recados = $data['telefono_recados'] == NULL ? 'S/T' : ($data['telefono_recados'] == '' ? 'S/T' : $data['telefono_recados']);
                
                switch($data['rolem']){
                    case 2:
                        $typeRole = 'Tutor';
                    break;
                    case 3:
                        $typeRole = 'Médico de calidad';
                    break;
                    case 4:
                        $typeRole = 'Tutor / Médico de calidad';
                    break;
                } 

                $options = '<button class="btn btn-secondary" onclick="editResult('.$data['id'].',3)">Editar</button> ';
                $options .= $data['estado'] == 1 ? '<button class="btn btn-primary" onclick="editResult('.$data['id'].',0,1)">Desactivar</button> ' : '<button class="btn btn-success" onclick="editResult('.$data['id'].',1,1)">Activar</button> ';
                $options .= '<button class="btn btn-secondary btnUploadFiles" data-files="CV" data-toggle="modal" data-target="#modalUploadFiles" id="'.$data['id'].'">Subir CV</button> ';
                //$options .= '<button class="btn btn-primary" onclick="AssignCarrer('.$data['id'].')" data-toggle="modal" data-target="#modalAssignC">Asignar Carrera</button> ';
                $carrer = '';
                $proce = '';
                $gen = '';

                if(count($data['carreras']) > 0){
                    
                    foreach($data['carreras'] as $key=>$value){
                        $carrer .= $value['ncarrera'].'<br>';
                    }
                    //$options .= '<button class="btn btn-secondary" data-toggle="modal" data-target="#modalAssignPT">Asignar Protocolo de tesis</button> ';
                }else{
                    $carrer = 'Sin carrera(s) asignada(s).';
                }

                if(count($data['procedimientos']) > 0){
                    
                    foreach($data['procedimientos'] as $key=>$value){
                        $proce .= $value['nprocd'].'<br>';
                    }
                }else{
                    $proce = 'Sin procedimiento(s) asignado(s).';
                }

                if(count($data['generaciones']) > 0){
                    
                    foreach($data['generaciones'] as $key=>$value){
                        
                        //$gen .= '<span class="border-bottom d-block">'.$value['nombre'].'</span><br>';
                        foreach($value as $g=>$v){
                            $gen .= '<span class="border-bottom d-block">'.$v['nombre'].'</span><br>';
                        }
                    }
                }else{
                    $gen = 'Sin generaciones asignadas.';
                }

                $resp[] = array(

                    0 => $data['nombres'].' '.$data['aPaterno'].' '.$data['aMaterno'],
                    1 => $typeRole,
                    2 => '<b>Particular</b> :'.$telefono.'<br> <b>Celular</b> :'.$celular.'<br> <b>Trabajo</b> :'.$telefono_trabajo.'<br> <b>Recados</b> :'.$telefono_recados,
                    3 => $data['email'],
                    4 => $data['link_cv'] != NULL ? '<a href="../assets/files/areasmedicas/lista_cv/'.$data['id'].'/'.$data['link_cv'].'" download>Descargar</a>' : 'No lo ha registrado',
                    5 => '<input class="checkProto" type="checkbox" onclick="AssignCarrer('.$data['id'].')">',
                    6 => $carrer,
                    7 => $proce,
                    8 => $options,
                );

                array_push($resp1,$data);
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $resp ),
                'iTotalDisplayRecords'=>count( $resp ),
                'aData'=>$resp,
                'aaData'=>$resp1
            );
            echo json_encode($result);
        
        break;
        case 'getStu':

            unset($_POST['action']);

            $datas = $areas->consultarStu();
            $resp = [];
            $resp1 = [];

            foreach($datas as $key=>$vals){

                $datas[$key]['procedimientos'] = $areas->getCarreraPro($datas[$key]['idCarrera'],2);
            }


            foreach($datas as $data){

                $telefono = $data['telefono'] == NULL ? 'S/T' : ($data['telefono'] == '' ? 'S/T' : $data['telefono']);
                $carrer = $data['nCar'];
                $gen = $data['nGen'];
                $proce = '';
                if(count($data['procedimientos']) > 0){
                    
                    foreach($data['procedimientos'] as $k=>$v){
                        
                        $proce .= $v['nprocd'].'<br>';

                    }
                    
                }
                $options = '<button class="btn btn-secondary" onclick="datosDirectorio('.$data['idAsistente'].',4)">Editar</button> ';
                $resp[] = array(

                    //0 => '<input class="checkProto" type="checkbox" onclick="AssignCarrer('.$data['idAsistente'].')">',
                    0 => $data['foto'] != NULL ? '<div><img class="img-responsive" /></div>' : ($data['foto'] != '' ? '<div><img class="img-responsive" /></div>' : '<div class="row justify-content-center"><img class="img-responsive" width="200" height="150" src="../assets/images/default-1.png"/></div>'),
                    1 => $data['nombre'].' '.$data['aPaterno'].' '.$data['aMaterno'],
                    2 => $telefono,
                    3 => $data['correo'],
                    4 => $carrer,
                    5 => $gen,
                    6 => $proce,
                    7 => $options,
                );

                array_push($resp1,$data);
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $resp ),
                'iTotalDisplayRecords'=>count( $resp ),
                'aData'=>$resp,
                'aaData'=>$resp1
            );
            echo json_encode($result);
        break;
        case 'getProcedR':

            unset($_POST['action']);

            $datas = $areas->consultarStuProce($_POST['idA'])['data'];
            $resp = [];
            $resp1 = [];

            foreach($datas as $data){

                $resp[] = array(

                    0 => $data['nombre'],
                    1 => $data['ntutor'],
                    2 => $data['paciente'],
                    3 => $data['comentarios'],
                    4 => $data['nsitio'],
                    5 => $data['frealizacion'],
                    6 => 'Sin archivo a descargar',
                );

                array_push($resp1,$data);
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $resp ),
                'iTotalDisplayRecords'=>count( $resp ),
                'aData'=>$resp,
                'aaData'=>$resp1
            );
            echo json_encode($result);
        break;
        case 'getAlumnPro':

            unset($_POST['action']);

            $idA = 0;
            $datas = $areas->consultarStuProce($idA)['data'];
            $resp = [];
            $resp1 = [];

            foreach($datas as $data){

                $tutor = $data['idtutor'] != NULL ? $data['ntutor'] : ($data['idtutor'] != 0 ? $data['ntutor'] :'<button class="btn btn-primary">Asignar tutor</button>');
                $pa = "";

                $resp[] = array(

                    0 => $data['nombreA'],
                    1 => $data['nombre'],
                    2 => $data['paciente'],
                    3 => $data['nsitio'],
                    4 => $data['frealizacion'],
                    5 => $tutor,
                    6 => "<button class = 'btn btn-primary' onClick = 'EditarCirugia({$data['idexp']})'>Editar</button>"
                );

                array_push($resp1,$data);
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $resp ),
                'iTotalDisplayRecords'=>count( $resp ),
                'aData'=>$resp,
                'aaData'=>$resp1
            );
            echo json_encode($result);
        break;
        case 'getProced':
        
            unset($_POST['action']);
            $datas = $areas->consultarProced();
            $resp = [];
            $resp1 = [];

            foreach($datas as $key=>$alue){
    
                $datas[$key]['carreras'] = $areas->getCarreraPro($datas[$key]['idpm'],1);
                $datas[$key]['generaciones']  = [];
                
                foreach($datas[$key]['carreras'] as $k=>$value){
                    
                    $datas[$key]['generaciones'][] = $areas->getGen($datas[$key]['carreras'][$k]['idCarrera'],1,$datas[$key]['idpm'])['data'];
                
                }
            }

            foreach($datas as $data){

                $costo = $data['costo'] == NULL ? 'Costo no asignado' : ($data['costo'] == '' ? 'Costo no asignado' : $data['costo']);
                $options = '<button class="btn btn-secondary mb-2" onclick="editResult('.$data['idpm'].',2)">Editar</button> ';
                $options .= $data['estado'] == 1 ? '<button class="btn btn-primary mb-2" onclick="editResult('.$data['idpm'].',0,2)">Desactivar</button><br>' : '<button class="btn btn-success mb-2" onclick="editResult('.$data['idpm'].',1,2)">Activar</button> ';
                $options .= '<button class="btn btn-secondary btnUploadFiles mb-2" data-files="PRCD" data-toggle="modal" data-target="#modalUploadFiles" id="'.$data['idpm'].'">Subir archivo</button> ';
                $carrer = '';
                $gen = "";

                if($data['archivo'] != null && count(json_decode($data['archivo']))){

                    $data['archivo'] = json_decode($data['archivo']);
                    $data['descripcion'] = json_decode($data['descripcion']);
                    $file = '';
                    foreach(array_keys($data['archivo'])  as $key){
                        $file .= '<a href="../assets/files/areasmedicas/lista_procedimientos/'.$data['idpm'].'/'.$data['archivo'][$key].'" target="_blank">'.$data['archivo'][$key].'<a>'.
                        '<br> <b>Descrpción</b>: '.$data['descripcion'][$key].'<br>';
                    }
                    
                }else{
                    $file =  'Sin archivos para lista de verificación';
                }
                if(count($data['carreras']) > 0){
                    
                    foreach($data['carreras'] as $key=>$value){
                        $carrer .= '<span class="border-bottom d-block">'.$value['ncarrera'].'</span><br>';
                    }
                }else{
                    $carrer = 'Sin carrera(s) asignada(s)';
                }

                if(count($data['generaciones']) > 0){
                    
                    foreach($data['generaciones'] as $key=>$value){
                        
                        foreach($value as $g=>$v){
                            $gen .= '<span class="border-bottom d-block">'.$v['nombre'].'</span><br>';
                        }
                    }
                }else{
                    $gen = 'Sin generaciones asignadas.';
                }

                $resp[] = array(
                    0=> '<input class="checkProto" type="checkbox" onclick="protoTesis('.$data['idpm'].')">',
                    1=> $data['nombre'],
                    2=> $costo,
                    3=> $carrer,
                    4=> $gen,
                    5 => $file,
                    6=> $options,                    
                );

                array_push($resp1,$data);
            }

            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $resp ),
                'iTotalDisplayRecords'=>count( $resp ),
                'aData'=>$resp,
                'aaData'=>$resp1
            );
            echo json_encode($result);
        break;
        case 'saveFiles':

            unset($_POST['action']);

            $id = $_POST['idM'];
            unset($_POST['idM']);

            $filesUp = $_POST['typeFile'];

            unset($_POST['typeFile']);

            switch($filesUp){

                case 'CV':

                    if(!file_exists("../../../files/areasmedicas/lista_cv/$id")){
                        mkdir("../../../files/areasmedicas/lista_cv/$id", 0707);
                    }
        
                    $nName = 0;
                    if(isset($_FILES['pdf']) && $_FILES['pdf'] != 'undefined'){
                        $tmp_name = $_FILES['pdf']['tmp_name'][0];
                        $uploads_dir = "../../../files/areasmedicas/lista_cv/$id";
                        
                        $fileT = explode('.', "{$_FILES['pdf']['name'][0]}");
                        $extensionFI = $fileT[sizeof($fileT)-1];
                        $date = date('Y-m-d');
                        $nName = "{$id}_pdf_{$date}.{$extensionFI}";
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                    }
        
                    $_POST['pdf'] = $nName;
                    $_POST['idM'] = $id;
                    $data = $areas->saveValues($_POST);

                break;
                case 'PRCD':
                    $_POST['idP'] = $_SESSION["usuario"]['idPersona'];
                    $_POST['idM'] = $id; 
                    $idAssign = $_POST['idM'];
                    $arrayNames = [];
                    $arrayDesc = [];

                    if(isset($_FILES["pdf"]['tmp_name'])){
                        foreach($_FILES["pdf"]['tmp_name'] as $key => $tmp_name)
                        {
                                //Validamos que el archivo exista
                                if($_FILES["pdf"]["name"][$key]) {
                                    
                                    $fileT = explode('.', $_FILES["pdf"]["name"][$key]);	 //Obtenemos el nombre original del archivo
                                    $extensionFI = $fileT[sizeof($fileT)-1];
                                    
                                    //$nName = 'pdf_'.$date.'.'.$extensionFI;
                                    $source = $_FILES["pdf"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
                                    
                                    $directorio = '../../../files/areasmedicas/lista_procedimientos/'.$idAssign.'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
                                    
                                    //Validamos si la ruta de destino existe, en caso de no existir la creamos
                                    if(!file_exists($directorio)){
                                        mkdir($directorio, 0777,true) or die("No se puede crear el directorio de extracci&oacute;n");
                                    }
            
                                    $dir=opendir($directorio); //Abrimos el directorio de destino
                                    $target_path = $directorio. $_FILES["pdf"]["name"][$key]; //Indicamos la ruta de destino, así como el nombre del archivo
                                    
                                    //Movemos y validamos que el archivo se haya cargado correctamente
                                    //El primer campo es el origen y el segundo el destino
                                    if(move_uploaded_file($source, $target_path)) {	
                                        array_push($arrayNames, $_FILES["pdf"]["name"][$key]);
                                    }
                                    closedir($dir); //Cerramos el directorio de destino
                                }
                        }
                    }

                    if(isset($_POST['oldfiles']) && $_POST['oldfiles'] != ''){
        
                        $oldfiles = json_decode($_POST['oldfiles']);
                        foreach($oldfiles as $key){
                            array_push($arrayNames,$key);
                        }
                    }
                    if(isset($_POST['olddes']) && $_POST['olddes'] != ''){
        
                        $olddes = json_decode($_POST['olddes']);
                        foreach($olddes as $key){
                            array_push($arrayDesc,$key);
                        }
                    }
                    array_push($arrayDesc,$_POST['descp']);
                    unset($_POST['oldfiles']);
                    unset($_POST['olddes']);
                    
                    $_POST['pdf'] = $arrayNames;
                    $_POST['descp'] = $arrayDesc;

        
                    $data = $areas->saveProced($_POST);
                break;
                default:
                $data = 'no session';
                break;
            }
            
            echo json_encode($data);
        break;

        case 'updateCirugia':
            unset($_POST["action"]);
            $newCirugia = $areas->updateCirugia($_POST);
            echo json_encode($newCirugia);
            break;

        case 'createUs':

            unset($_POST['action']);
            $data = $areas->saveValues($_POST);
            echo json_encode($data);
        break;
        case 'createPro':
            unset($_POST['action']);
            $data = $areas->saveProced($_POST);
            echo json_encode($data);
        break;
        case 'assignPro':
            unset($_POST['action']);

            foreach(array_keys($_POST['idPro']) as $key){
                $_POST['idPros'] = $_POST['idPro'][$key];

                $datas = $areas->assignPro($_POST);
                
                
            }
            echo json_encode($datas);
        break;
        case 'assignTut':
            unset($_POST['action']);

            foreach(array_keys($_POST['idM']) as $key){
                $_POST['idMs'] = $_POST['idM'][$key];

                foreach(array_keys($_POST['carrer']) as $key){
                    $_POST['carrers'] = $_POST['carrer'][$key];
                    $datas = $areas->assignTut($_POST);
                }
            }
            echo json_encode($datas);
        break;
        case 'addBitacoras':
            unset($_POST['action']);

            $datas = $areas->addBitacoras($_POST);

            echo json_encode($datas);
        break;
        default:
            echo json_encode(['status'=>'error']);
        break;
    }

}

?>