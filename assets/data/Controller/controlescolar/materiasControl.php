<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/controlescolar/materiasModel.php';
    require_once "../../Model/controlescolar/examenModel.php";
    require_once "../../Model/planpagos/generacionesModel.php";
    require_once "../../Model/controlescolar/controlEscolarModel.php";
    require_once "../../Model/planpagos/pagosModel.php";

    $mtria = new Materias();
    $examenM = new Examen();
    $generacionesM = new Generaciones();
    $controlEscM = new ControlEscolar();
    $pagosM = new pagosModel(); 

    if(!isset($_SESSION['usuario']) && !isset($_SESSION['alumno']) && !isset($_POST['android_id_afiliado'])){
        $_POST['action'] = 'no_session';
    }

    $idusuario = 0;
    
    if(isset($_SESSION["alumno"]['id_prospecto'])){
        $idusuario = $_SESSION['alumno']['id_prospecto'];
    }
    if(isset($_POST['idalumno'])){
        $idusuario = $_POST['idalumno'];
        unset($_POST['idalumno']);
    }
    if(isset($_POST['android_id_afiliado'])){
        $idusuario = $_POST['android_id_afiliado'];
    }
    
    $accion=@$_POST["action"];

    switch($accion){
        case 'obtenerCarreras':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $obCarr = $mtria->obtenerCarreras($id)['data'];
            echo json_encode($obCarr);
            break;

        case 'obtenerCarrerasOficial':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $obCarrOf = $mtria->obtenerCarrerasOficial($id)['data'];
            echo json_encode($obCarrOf);
            break;

        case 'crearMateria':
            unset($_POST['action']);
            $of = $_POST['selectOficial'];
            $busClave = $mtria->buscarClaveMateria($_POST['claveMateria'])['data'];
            if($busClave!=0){
                echo 'clave_ocupada_materia';
            }else{
                if($of == '1'){
                    unset($_POST['numeroCreditosNoOficial']);
                    if($_FILES['contenidoPDF']['name'] == null){
                        unset($_POST['contenidoPDF']);
                        $fcreado = date('Y-m-d H:i:s');
                        $_POST['fcreado'] = $fcreado;
                        $crearMat = $mtria->crearMateriaOficial($_POST);
                        echo json_encode($crearMat);
                    }else{
                        $formatosDoc = array('.pdf');
                        //$maxSize = 5242880;
                        $maxSize = 3145728;
                        $doc = $_FILES['contenidoPDF'];
                        $nam = $_POST['nombreMateria'];
                        $clav = $_POST['claveMateria'];
                        $idCarrer = $_POST['selectCarreraAsig'];

                        $extension = $_FILES['contenidoPDF']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));

                        if(in_array($ext, $formatosDoc)){
                            if($doc['size'] < $maxSize){
                                //clearstatcache();
                                //ini_set("display_errors", 1); 	
                                if(!file_exists('../../../../controlescolar/archivos/materias/'.$idCarrer)){
                                    mkdir('../../../../controlescolar/archivos/materias/'.$idCarrer, 0777, true);
                                }
                                $nam = explode(' ', $nam);
                                $nom_final = [];
                                foreach($nam as $nom){
                                    $nom = str_replace(['á','Á'], 'a', $nom);
                                    $nom = str_replace(['é','É'], 'e', $nom);
                                    $nom = str_replace(['í','Í'], 'i', $nom);
                                    $nom = str_replace(['ó','Ó'], 'o', $nom);
                                    $nom = str_replace(['ú','Ú'], 'u', $nom);
                                    array_push($nom_final, strtolower($nom));
                                }
                                $str_nombre = implode('_', $nom_final);
                                //flush();
                                //error_reporting(E_ALL ^ E_WARNING);
                                $tmp_name = $_FILES['contenidoPDF']['tmp_name'];
                                $uploads_dir = "../../../../controlescolar/archivos/materias/".$idCarrer."/";

                                $file = explode('.', $_FILES['contenidoPDF']['name']);
                                $extensionPdf = $file[sizeof($file)-1];
                                
                                $nName = $str_nombre.'_'.$clav.'.'.$extensionPdf;
                                
                                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                                $_POST['nName'] = $nName;

                                $fcreado = date('Y-m-d H:i:s');
                                $_POST['fcreado'] = $fcreado;
                                $crearMat = $mtria->crearMateriaOficialPDF($_POST);
                                echo json_encode($crearMat);
                            }
                        }else{
                            echo 'no_valido_pdf_carrera';
                        }
                    }
                }else{
                    if($_FILES['contenidoPDF']['name'] == null){
                        unset($_POST['contenidoPDF']);
                        //unset($_POST['claveMateria']);
                        unset($_POST['selectTipoMateria']);
                        unset($_POST['numeroCreditos']);

                        $fcreado = date('Y-m-d H:i:s');
                        $_POST['fcreado'] = $fcreado;
                        $crearMat = $mtria->crearMateriaNoOficial($_POST);
                        echo json_encode($crearMat);
                    }else{
                        //unset($_POST['claveMateria']);
                        unset($_POST['selectTipoMateria']);
                        unset($_POST['numeroCreditos']);
                        $formatosDoc = array('.pdf');
                        //$maxSize = 5242880;
                        $maxSize = 3145728;
                        $doc = $_FILES['contenidoPDF'];
                        $nam = $_POST['nombreMateria'];
                        $clav = $_POST['claveMateria'];

                        $idCarrer = $_POST['selectCarreraAsig'];

                        $extension = $_FILES['contenidoPDF']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($doc['size'] < $maxSize){
                                if(!file_exists('../../../../controlescolar/archivos/materias/'.$idCarrer)){
                                    mkdir('../../../../controlescolar/archivos/materias/'.$idCarrer, 0777, true);
                                }
                                
                                $nam = explode(' ', $nam);
                                $nom_final = [];
                                foreach($nam as $nom){
                                    $nom = str_replace(['á','Á'], 'a', $nom);
                                    $nom = str_replace(['é','É'], 'e', $nom);
                                    $nom = str_replace(['í','Í'], 'i', $nom);
                                    $nom = str_replace(['ó','Ó'], 'o', $nom);
                                    $nom = str_replace(['ú','Ú'], 'u', $nom);
                                    array_push($nom_final, strtolower($nom));
                                }
                                $str_nombre = implode('_', $nom_final);

                                $tmp_name = $_FILES['contenidoPDF']['tmp_name'];
                                $uploads_dir = "../../../../controlescolar/archivos/materias/".$idCarrer."/";

                                $file = explode('.', $_FILES['contenidoPDF']['name']);
                                $extensionPdf = $file[sizeof($file)-1];
                                
                                $nName = 'No_oficial_'.$str_nombre.'_'.$clav.'.'.$extensionPdf;
                                
                                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                                $_POST['nName'] = $nName;

                                $fcreado = date('Y-m-d H:i:s');
                                $_POST['fcreado'] = $fcreado;
                                $crearMat = $mtria->crearMateriaNoOficialPDF($_POST);
                                echo json_encode($crearMat);
                            }
                        }else{
                            echo 'no_valido_pdf_carrera';
                        }
                    }
                }
            }
            break;

        case 'obtenerMaterias':
            unset($_POST['action']);
            $csulM = $mtria->obtenerMaterias();
            $data = Array();
            while($dato=$csulM->fetchObject()){
                //1=> $dato->nombreCarr,  
                $data[]=array(
                    0=> $dato->oficial === '1' ? '<i class="fas fas fa-check" style="color:green"><p hidden>Si</p>' : '<i class="fas fas fa-times" style="color:red"><input type="hidden" value="2"><p hidden>No</p>',
                    1=> $dato->nombre.' <br>'.$dato->nombreCarr,
                    2=> $dato->clave_asignatura,
                    3=> $dato->tipo === '1' ? 'Adicional' : ($dato->tipo === '2' ? 'Área' : ($dato->tipo === '3' ? 'Complementaria' : ($dato->tipo === '4' ? 'Obligatoria' : ($dato->tipo === '5' ? 'Optativa' : '')))),
                    4=> $dato->numero_creditos,
                    5=> date('d-m-Y', strtotime($dato->fecha_creado)),
                    6=> $dato->contenido_pdf === null ? '<button class="btn btn-secondary" disabled>PDF</button> '. '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModMateria" onclick="buscarMateria('.$dato->id_materia.')">Modificar</button> ' : '<button class="btn btn-primary" onclick="verPDFMateria('.$dato->idCarrera.',\''.$dato->contenido_pdf.'\')">PDF</button> '.
                        '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModMateria" onclick="buscarMateria('.$dato->id_materia.')">Modificar</button> '
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

        case 'buscarMateria':
            unset($_POST['action']);
            $busMat = $mtria->buscarMateria($_POST);
            echo json_encode($busMat);
            break;

        case 'modificarMateria':
            unset($_POST['action']);
            
            $ofM = $_POST['modSelectOficial'];
            unset($_POST['modSelectOficial']);
            //
            $busClaveMod = $mtria->buscarClaveMateriaMod($_POST['modClaveMateria'], $_POST['id_materia'])['data'];
            if($busClaveMod!=0){
                echo json_encode('clave_ocupada_materia');
            }else{
            //oficial
            if($ofM == 1){
                unset($_POST['modNumeroCreditosNoOficial']);
                if($_FILES['modContenidoPDF']['name'] != null && $_POST['pdfAnterior'] == 1){
                    unset($_POST['pdfAnterior']);
                    $formatosDoc = array('.pdf');
                    //$maxSize = 5242880;
                    $maxSize = 3145728;

                    
                    $doc = $_FILES['modContenidoPDF'];
                    $nam = $_POST['modNombreMateria'];
                    $clav = $_POST['modClaveMateria'];
                    $idCarrer = $_POST['modSelectCarreraAsig'];
                    
                    $extension = $_FILES['modContenidoPDF']['name'];
                    $ext = substr($extension, strrpos($extension, '.'));

                    if(in_array($ext, $formatosDoc)){
                        if($doc['size'] < $maxSize){
                            if(!file_exists('../../../../controlescolar/archivos/materias/'.$idCarrer)){
                                mkdir('../../../../controlescolar/archivos/materias/'.$idCarrer, 0777, true);
                            }
                            $nam = explode(' ', $nam);
                            $nom_final = [];
                            foreach($nam as $nom){
                                $nom = str_replace(['á','Á'], 'a', $nom);
                                $nom = str_replace(['é','É'], 'e', $nom);
                                $nom = str_replace(['í','Í'], 'i', $nom);
                                $nom = str_replace(['ó','Ó'], 'o', $nom);
                                $nom = str_replace(['ú','Ú'], 'u', $nom);
                                array_push($nom_final, strtolower($nom));
                            }
                            $str_nombre = implode('_', $nom_final);
                            $tmp_name = $_FILES['modContenidoPDF']['tmp_name'];
                            $uploads_dir = "../../../../controlescolar/archivos/materias/".$idCarrer."/";

                            $file = explode('.', $_FILES['modContenidoPDF']['name']);
                            $extensionPdf = $file[sizeof($file)-1];
                            
                            $nName = $str_nombre.'_'.$clav.'.'.$extensionPdf;
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                            $_POST['nName'] = $nName;

                            $fmodificado = date('Y-m-d H:i:s');
                            $_POST['fmodificado'] = $fmodificado;
                            $modMat = $mtria->modificarMateriaOficialPDF($_POST);
                            echo json_encode($modMat);
                        }
                    }else{
                        echo json_encode('no_valido_pdf_carrera');
                    }
                }else{
                    if($_FILES['modContenidoPDF']['name'] != null && $_POST['pdfAnterior'] == 2){
                        unset($_POST['pdfAnterior']);
                        $formatosDoc = array('.pdf');
                        //$maxSize = 5242880;
                        $maxSize = 3145728;
    
                        
                        $doc = $_FILES['modContenidoPDF'];
                        $nam = $_POST['modNombreMateria'];
                        $clav = $_POST['modClaveMateria'];
                        $idCarrer = $_POST['modSelectCarreraAsig'];
                        
                        $extension = $_FILES['modContenidoPDF']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));

                        if(in_array($ext, $formatosDoc)){
                            if($doc['size'] < $maxSize){
                                if(!file_exists('../../../../controlescolar/archivos/materias/'.$idCarrer)){
                                    mkdir('../../../../controlescolar/archivos/materias/'.$idCarrer, 0777, true);
                                }
                                $nam = explode(' ', $nam);
                                $nom_final = [];
                                foreach($nam as $nom){
                                    $nom = str_replace(['á','Á'], 'a', $nom);
                                    $nom = str_replace(['é','É'], 'e', $nom);
                                    $nom = str_replace(['í','Í'], 'i', $nom);
                                    $nom = str_replace(['ó','Ó'], 'o', $nom);
                                    $nom = str_replace(['ú','Ú'], 'u', $nom);
                                    array_push($nom_final, strtolower($nom));
                                }
                                $str_nombre = implode('_', $nom_final);
                                $tmp_name = $_FILES['modContenidoPDF']['tmp_name'];
                                $uploads_dir = "../../../../controlescolar/archivos/materias/".$idCarrer."/";
    
                                $file = explode('.', $_FILES['modContenidoPDF']['name']);
                                $extensionPdf = $file[sizeof($file)-1];
                                
                                $nName = $str_nombre.'_'.$clav.'.'.$extensionPdf;
                                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                                $_POST['nName'] = $nName;
    
                                $fmodificado = date('Y-m-d H:i:s');
                                $_POST['fmodificado'] = $fmodificado;
                                $modMat = $mtria->modificarMateriaOficialPDF($_POST);
                                echo json_encode($modMat);
                            }
                        }else{
                            echo json_encode('no_valido_pdf_carrera');
                        }
                    }else{
                        unset($_POST['pdfAnterior']);
                        unset($_POST['modContenidoPDF']);
                        $fmodificado = date('Y-m-d H:i:s');
                        $_POST['fmodificado'] = $fmodificado;
                        $modMat = $mtria->modificarMateriaOficial($_POST);
                        echo json_encode($modMat);
                    }
                }
            }else{
                //no_oficial
                //unset($_POST['modClaveMateria']);
                unset($_POST['modSelectTipoMateria']);
                unset($_POST['modNumeroCreditos']);
                if($_FILES['modContenidoPDF']['name'] != null && $_POST['pdfAnterior'] == 1){
                    unset($_POST['pdfAnterior']);
                    $formatosDoc = array('.pdf');
                    //$maxSize = 5242880;
                    $maxSize = 3145728;

                    $doc = $_FILES['modContenidoPDF'];
                    $nam = $_POST['modNombreMateria'];
                    $clav = $_POST['modClaveMateria'];
                    $idCarrer = $_POST['modSelectCarreraAsig'];
                    
                    $extension = $_FILES['modContenidoPDF']['name'];
                    $ext = substr($extension, strrpos($extension, '.'));

                    if(in_array($ext, $formatosDoc)){
                        if($doc['size'] < $maxSize){
                            if(!file_exists('../../../../controlescolar/archivos/materias/'.$idCarrer)){
                                mkdir('../../../../controlescolar/archivos/materias/'.$idCarrer, 0777, true);
                            }
                            
                            $nam = explode(' ', $nam);
                            $nom_final = [];
                            foreach($nam as $nom){
                                $nom = str_replace(['á','Á'], 'a', $nom);
                                $nom = str_replace(['é','É'], 'e', $nom);
                                $nom = str_replace(['í','Í'], 'i', $nom);
                                $nom = str_replace(['ó','Ó'], 'o', $nom);
                                $nom = str_replace(['ú','Ú'], 'u', $nom);
                                array_push($nom_final, strtolower($nom));
                            }
                            $str_nombre = implode('_', $nom_final);

                            $tmp_name = $_FILES['modContenidoPDF']['tmp_name'];
                            $uploads_dir = "../../../../controlescolar/archivos/materias/".$idCarrer."/";

                            $file = explode('.', $_FILES['modContenidoPDF']['name']);
                            $extensionPdf = $file[sizeof($file)-1];
                            
                            $nName = 'No_oficial_'.$str_nombre.'_'.$clav.'.'.$extensionPdf;
                            
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                            $_POST['nName'] = $nName;

                            
                            $fmodificado = date('Y-m-d H:i:s');
                            $_POST['fmodificado'] = $fmodificado;
                            $modMat = $mtria->modificarMateriaNoOficialPDF($_POST);
                            //$modMat = $mtria->modificarMateriaNoOficial($_POST);
                            echo json_encode($modMat);
                        }
                    }else{
                        echo 'no_valido_pdf_carrera';
                    }
                }else{
                    if($_FILES['modContenidoPDF']['name'] != null && $_POST['pdfAnterior'] == 2){
                        unset($_POST['pdfAnterior']);
                        $formatosDoc = array('.pdf');
                        //$maxSize = 5242880;
                        $maxSize = 3145728;

                        $doc = $_FILES['modContenidoPDF'];
                        $nam = $_POST['modNombreMateria'];
                        $clav = $_POST['modClaveMateria'];
                        $idCarrer = $_POST['modSelectCarreraAsig'];
                        
                        $extension = $_FILES['modContenidoPDF']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));

                        if(in_array($ext, $formatosDoc)){
                            if($doc['size'] < $maxSize){
                                if(!file_exists('../../../../controlescolar/archivos/materias/'.$idCarrer)){
                                    mkdir('../../../../controlescolar/archivos/materias/'.$idCarrer, 0777, true);
                                }
                                
                                $nam = explode(' ', $nam);
                                $nom_final = [];
                                foreach($nam as $nom){
                                    $nom = str_replace(['á','Á'], 'a', $nom);
                                    $nom = str_replace(['é','É'], 'e', $nom);
                                    $nom = str_replace(['í','Í'], 'i', $nom);
                                    $nom = str_replace(['ó','Ó'], 'o', $nom);
                                    $nom = str_replace(['ú','Ú'], 'u', $nom);
                                    array_push($nom_final, strtolower($nom));
                                }
                                $str_nombre = implode('_', $nom_final);

                                $tmp_name = $_FILES['modContenidoPDF']['tmp_name'];
                                $uploads_dir = "../../../../controlescolar/archivos/materias/".$idCarrer."/";

                                $file = explode('.', $_FILES['modContenidoPDF']['name']);
                                $extensionPdf = $file[sizeof($file)-1];
                                
                                $nName = 'No_oficial_'.$str_nombre.'_'.$clav.'.'.$extensionPdf;
                                
                                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                                $_POST['nName'] = $nName;

                                
                                $fmodificado = date('Y-m-d H:i:s');
                                $_POST['fmodificado'] = $fmodificado;
                                $modMat = $mtria->modificarMateriaNoOficialPDF($_POST);
                                echo json_encode($modMat);
                            }
                        }else{
                            echo 'no_valido_pdf_carrera';
                        }
                    }else{
                        unset($_POST['pdfAnterior']);
                        unset($_POST['modContenidoPDF']);
                        $fmodificado = date('Y-m-d H:i:s');
                        $_POST['fmodificado'] = $fmodificado;
                        $modMat = $mtria->modificarMateriaNoOficial($_POST);
                        echo json_encode($modMat);
                    }
                }
            }
            }
            break;

        case 'eliminarMateria':
            unset($_POST['action']);
            $delMat = $mtria->eliminarMateria($_POST);
            echo json_encode($delMat);
            break;

        case 'obtenerListadoDeCarreras':
            unset($_POST['action']);
            $id = $_SESSION['usuario']['estatus_acceso'];
            $obListCarr = $mtria->obtenerListadoDeCarreras($id)['data'];
            echo json_encode($obListCarr);
            break;

        case 'obtenerMateriasPorCarrera':
            unset($_POST['action']);
            $obMatCarr = $mtria->obtenerMateriasPorCarrera($_POST);
            $data = Array();
            while($dato = $obMatCarr->fetchObject()){
                $data[] = array(
                    0=> $dato->oficial === '1' ? '<i class="fas fas fa-check" style="color:green"><p hidden>Si</p>' : '<i class="fas fas fa-times" style="color:red"><input type="hidden" value="2"><p hidden>No</p>',
                    1=> $dato->nombre,
                    2=> $dato->clave_asignatura,
                    3=> $dato->tipo === '1' ? 'Adicional' : ($dato->tipo === '2' ? 'Área' : ($dato->tipo === '3' ? 'Complementaria' : ($dato->tipo === '4' ? 'Obligatoria' : ($dato->tipo === '5' ? 'Optativa' : '')))),
                    4=> $dato->numero_creditos,
                    5=> date('d-m-Y', strtotime($dato->fecha_creado)),
                    6=> $dato->contenido_pdf === null ? '<button class="btn btn-secondary" disabled>PDF</button> '. '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModMateria" onclick="buscarMateria('.$dato->id_materia.')">Modificar</button> ' : '<button class="btn btn-primary" onclick="verPDFMateria('.$dato->idCarrera.',\''.$dato->contenido_pdf.'\')">PDF</button> '.
                        '<button class="btn btn-primary" data-toggle="modal" data-target="#modalModMateria" onclick="buscarMateria('.$dato->id_materia.')">Modificar</button> '
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            if($result["iTotalRecords"]==0){
                echo 'sin_materias_carrera';
            }else{
                echo json_encode($result);
            }
            break;
        case 'consultar_sesiones':
            $fechahoymas20minutos = date('Y-m-d H:i:s', strtotime('+20 minutes'));
		    $sesiones_de_hoy=$mtria->consultar_sesiones($_POST['generacion']);
		    foreach ($sesiones_de_hoy as $key => $value) {
		        if (strtotime($value['fecha_clase'])>strtotime($fechahoymas20minutos)) {// si la sesion es mayor a 20 minutos de comenzar no se muestra
		            unset($sesiones_de_hoy[$key]);
		        }
		    }
		    echo(json_encode($sesiones_de_hoy));
            break;

        case 'consultar_sesiones_filtro':
            $sesiones_de_hoy=$mtria->consultar_sesiones_sin_filtro($_POST['generacion']);
            $cont = 0;
            foreach($sesiones_de_hoy as $sesions){
                $url = urldecode($sesions["video"]);
                $sesiones_de_hoy[$cont]["video"] = $url;
                $cont++;
            }
            //var_dump($sesiones_de_hoy);
            echo(json_encode($sesiones_de_hoy));
            break;
        
        case 'cargar_respuestas_guardadas':
            $respuestas = [];
            if (isset($_POST['code'])) {
                $examen = explode('.', $_POST['code'])[0];
                $alumno = explode('.', $_POST['code'])[1];
    
                $guardadas = $examenM->alumno_examen_respuestas($idusuario, $examen)['data'];
                if(sizeof($guardadas) > 0 && intval($guardadas[0]['calificacion']) == -1){
                    $guardadas = $guardadas[0];
                    $respuestas_g = json_decode($guardadas['respuestas'], true);
    
                    foreach ($respuestas_g as $key => $value) {
                        array_push($respuestas, [$value[0], $value[1]]);
                    }
                }
            }
            echo json_encode($respuestas);
        break;

        case 'validar_adeudos':
            $alumno = $_POST['idAlumno'];
            $generacion = $_POST['id'];
            if(gettype($alumno) != 'array'){
                echo validar_adeudos($alumno, $generacion);
            }else{
                $arr_adeudos = [];
                foreach($alumno as $alm){
                    $arr_adeudos[] = [$alm, validar_adeudos($alm, $generacion)];
                }
                echo json_encode($arr_adeudos);
            }
        break;
        case 'validar_examenExtra':
        
            unset($_POST['action']);
            
            $idusuario = $_POST['idAlumno'];

            $examenes = $examenM->getExamnExtra($idusuario,'')['data'];
            
            

                $fecha_hoy = date("Y-m-d H:i:s");
            for ($i=0; $i < sizeof($examenes); $i++) { 
                $examenes[$i]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $examenes[$i]['idExamen'])['data'];
                $examenes[$i]['presentaciones'] = $examenM->alumno_examen_respuestas($idusuario, $examenes[$i]['idExamen'])['data'];
                    $fecha_i = date($examenes[$i]['fechaInicio']);
                    $fecha_f = date($examenes[$i]['fechaFin']);
                    $h = strtotime($fecha_hoy);
                    $fi = strtotime($fecha_i);
                    $ff = strtotime($fecha_f);
                    if($fi <= $h && $ff >= $h){
                        $examenes[$i]['ontime'] = true;
                    }else{
                        $examenes[$i]['ontime'] = false;
                    }
                    $examenes[$i]['before'] = ($h < $fi) ? true : false;

            }
            
            $data = ['estatus'=>'ok', 'data'=>$examenes];

            echo json_encode($data);
        
        break;
        case 'no_session':
            echo 'no_session';
            break;

        default:
            # code...
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}
function validar_adeudos($alumno, $generacion){
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once "../../Model/planpagos/pagosModel.php";
    require_once "../../Model/planpagos/generacionesModel.php";
    $generacionesM = new Generaciones();
    $pagosM = new pagosModel(); 
    $controlEscM = new ControlEscolar();

    $fecha_hoy = date("Y-m-d H:i:s");
            $adeudo_i = true;
            $adeudo_m = true;
            $adeudo_r = true;
            $adeudo_doc = false;
            $adeudo_doc_f = false;
            $alumno_becado=false;
            $validarbeca = $generacionesM->validarBecaAlumno($alumno, $generacion)['data'];
            $informacion_alumno = $controlEscM->ObtenerInfoAlumno($alumno)[0];
            
            $validarbecageneracion = $generacionesM->validarBecaGen($alumno, $generacion)['data'];
            $obConceptos = $generacionesM->obtenerConceptosPago($generacion)['data'];
            $promo_cien = false;
            $i=0;
            foreach($validarbecageneracion as $key => $val){
                $promo_alumno = array_search($val['categoria'], array_column($validarbeca, 'categoria'));
                if(!$promo_alumno){
                    array_push($validarbeca, $val);
                }
            }
            
            foreach ($validarbeca as $key => $value) {
                if ($value['categoria']=='Mensualidad' || $value['categoria']=='Inscripción') {
                    if ($value['porcentaje']==100) {
                        if ($value['categoria']=='Mensualidad'){
                            $adeudo_m = false;
                        }
                        if ($value['categoria']=='Inscripción'){
                            $adeudo_i = false;
                            $band_pagado = true;
                        }
                        $i++;
                    }
                }
            }
            if ($i>=2) {
                $alumno_becado = true;
            }
            $paga_mensualidades = false;
            foreach ($obConceptos as $key => $value) {
                if($value['categoria'] == 'Inscripción'){
                    $pagado = $generacionesM->consultar_pagos_concepto_alumno($alumno, $value['id_concepto']);
                    
                    $band_pagado = false;
                    
                    foreach($pagado as $pago){
                        if($pago['promesa_de_pago'] != null || $pago['restante'] <= 1){
                            if($pago['restante'] <= 1){
                                $band_pagado = true;
                            }
                            $adeudo_i = false;
                        }
                        $promo_cien = false;
                        
                        if($pago['idPromocion'] != null && intval($pago['idPromocion']) > 0){
                            $info_promo = $generacionesM->info_promocion($pago['idPromocion']);
                            
                            if($info_promo && $info_promo['porcentaje'] == 100){
                                $adeudo_i = false;
                                $band_pagado = true;
                            }
                        }
                    }
                    if($promo_cien){
                        $adeudo_i = false;
                    }
                }
                
                if($value['categoria'] == 'Reinscripción'){
                    $paga_reincripcion = true;
                    // buscar fechas de ciclos de la generacion
                    $fechas_r = [];
                    for($i = 1; $i<=intval($value['numero_pagos']); $i++){
                        $tmp_f = $pagosM->obtenerfechalimitedepago($generacion, $i+1)['data'];
                        if($tmp_f !== false){
                            if(strtotime(date("Y-m-d")) > strtotime($tmp_f['fecha_inicio'])){
                                // var_dump($tmp_f);
                                $fecha_r[] = $tmp_f;
                                array_push($fechas_r, $tmp_f);
                            }
                        }
                    }
                    if(sizeof($fechas_r)>0){
                        $pagado = $generacionesM->consultar_pagos_concepto_alumno($alumno, $value['id_concepto']);
                        
                        foreach ($fechas_r as $key_fr => $value_fr) {
                            foreach ($pagado as $key_pr => $value_pr) {
                                if($value_pr['numero_de_pago'] == intval($value_fr['ciclo'])-1 && ($value_pr['restante'] + $value_pr['saldo']) < 1){
                                    unset($fechas_r[$key_fr]);
                                }
                            }
                        }
                        if(empty($fechas_r)){
                            $adeudo_r = false;
                        }
                    }else{
                        $adeudo_r = false;
                    }
                }
                if($value['categoria'] == 'Mensualidad'){
                    $paga_mensualidades = true;
                    $f_ini = '';
                    $f_esp = $generacionesM->obtenerFechaAlumnoEspecial($alumno, $generacion)['data'];
                    if($f_esp === false){
                        var_dump($alumno, $generacion);
                    }
                    if($f_esp['fecha_primer_colegiatura'] != null){
                        $f_ini = $f_esp['fecha_primer_colegiatura'];
                    }else{
                        $f_gen = $generacionesM->obtenerFechaGeneracion($generacion)['data']['fecha_inicio'];
                        $f_ini = substr($f_gen,0,8).explode('-', $value['fechalimitepago'])[2];
                        if(strtotime($f_ini) < strtotime($f_gen)){
                            $f_ini = date('Y-m-d', strtotime('+1 month', strtotime($f_ini)));
                        }
                    }
                    $pagado = $generacionesM->consultar_pagos_concepto_alumno($alumno, $value['id_concepto']);
                        // buscar si la promocion es dl 100 % entonces no volvera a hacer mas pagos
                    $promo_cien = false;
                    foreach($pagado as $pago){
                        if($pago['idPromocion'] != null && intval($pago['idPromocion']) > 0){
                            $info_promo = $generacionesM->info_promocion($pago['idPromocion']);
                            if($info_promo && $info_promo['porcentaje'] == 100){
                                $promo_cien = true;
                            }
                        }
                    }
                    $pagos_aplicados = sizeof($pagado);
                    $f_ini = substr($f_ini,0,10);
                    if($pagos_aplicados > 0){
                        $f_ini = date('Y-m-d', strtotime("+{$pagos_aplicados} month", strtotime($f_ini)));
                    }
                        // verificar si tiene una prorroga para el numero de pago actual
                    $consultar_p = $pagosM->validar_si_existe_prorroga($alumno, $value['id_concepto'], $pagos_aplicados + 1);
                    if($consultar_p['estatus'] == 'ok' && $consultar_p['data']){
                            // si el estatus de la prorroga es aprobado sobreescribe la fecha
                        if($consultar_p['data']['estatus'] == 'aprobado'){
                            $f_ini = $consultar_p['data']['nuevafechaaceptada'];
                        }
                    }
                    
                    if($promo_cien){
                        $f_ini = date("Y-m-d");
                    }
                    if(strtotime($f_ini) >= strtotime(date("Y-m-d"))){
                        $adeudo_m = false;
                    }
                }
            }
            if(!$paga_mensualidades){
                $adeudo_m = false;
            }
            
                //add
            $validarbeca = $generacionesM->validarBecaAlumno($alumno, $generacion)['data'];
            foreach ($validarbeca as $key => $value) {
                if ($value['categoria']=='Mensualidad' || $value['categoria']=='Inscripción') {
                    if ($value['porcentaje']==100) {
                        if ($value['categoria']=='Mensualidad'){
                            $adeudo_m = false;
                        }
                        if ($value['categoria']=='Inscripción'){
                            $adeudo_i = false;
                            $band_pagado = true;
                        }
                        $i++;
                    }
                }
                if ($value['categoria']=='Reinscripción'){
                    $adeudo_r = false;
                    $band_pagado = true;
                }
            }
            if(!$paga_reincripcion){
                $adeudo_r = false;
            }
                // add
            $estat_documentos = $controlEscM->documentos_generacion($generacion);
            foreach($estat_documentos as $key => $value){
                if($value['fecha_digital'] != null){
                    if(strtotime($fecha_hoy) > strtotime($value['fecha_digital'])){
                            // consultar si el alumno tiene una prorroga para el documento
                        $prorroga = $controlEscM->validar_prorroga_documento_alumno($informacion_alumno['id_afiliado'], $value['id_documento']);
                        if($prorroga && $prorroga['fecha_prorroga_digital'] != null){
                            if(strtotime($fecha_hoy) > strtotime($prorroga['fecha_prorroga_digital'])){
                                    // consultar si ya hizo entrega del documento
                                $entrega = $controlEscM->validar_documento_alumno($informacion_alumno['id_afiliado'], $value['id_documento']);
                                
                                if(!$entrega || $entrega['validacion'] != 1){
                                    $adeudo_doc = true;
                                }
                            }
                        }else{
                                // consultar si ya hizo entrega del documento
                            $entrega = $controlEscM->validar_documento_alumno($informacion_alumno['id_afiliado'], $value['id_documento']);
                            if(!$entrega || $entrega['validacion'] != 1){
                                $adeudo_doc = true;
                            }
                        }
                    }
                }
                if($value['fecha_fisico'] != null){
                    if(strtotime($fecha_hoy) > strtotime($value['fecha_fisico'])){
                            // consultar si el alumno tiene una prorroga para el documento
                        $prorroga = $controlEscM->validar_prorroga_documento_alumno($informacion_alumno['id_afiliado'], $value['id_documento']);
                        if($prorroga && $prorroga['fecha_prorroga_fisica'] != null){
                            if(strtotime($fecha_hoy) > strtotime($prorroga['fecha_prorroga_fisica'])){
                                    // consultar si ya hizo entrega del documento
                                $entrega = $controlEscM->validar_documento_fisico_alumno($informacion_alumno['id_afiliado'], $value['id_documento']);
                                
                                if(!$entrega ){
                                    $adeudo_doc_f = true;
                                }
                            }
                        }else{
                                // consultar si ya hizo entrega del documento
                            $entrega = $controlEscM->validar_documento_fisico_alumno($informacion_alumno['id_afiliado'], $value['id_documento']);
                            if(!$entrega){
                                $adeudo_doc_f = true;
                            }
                        }
                    }
                }
            }
            if((!$adeudo_i && !$adeudo_m && !$adeudo_r && !$adeudo_doc  && !$adeudo_doc_f) || (!$adeudo_doc  && !$adeudo_doc_f && $alumno_becado)){
                return 'si';
            }else{
                if($adeudo_i){
                    return 'no inscripcion';
                }else if($adeudo_m){
                    return 'no mensualidad';
                }else if($adeudo_r){
                    return 'no reinscripcion';
                }else if($adeudo_doc){
                    return 'no documentos';
                }else if($adeudo_doc_f){
                    return 'no documentos fisicos';
                }
            }
}
