<?php
header('Access-Control-Allow-Origin: https://conacon.org', false);
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/controlescolar/controlEscolarModel.php';
    require_once '../../Model/controlescolar/materiasModel.php';

    $ce = new ControlEscolar();
    $matsM = new Materias();
    if(!isset($_SESSION['usuario']) && !isset($_POST['listas']) ){
        
        $_POST['action'] = 'no_session';
    }

    switch($_POST['action']){

        case 'consultarAsistenciaEventos':
            unset($_POST['action']);
            $loadAlumnos = $ce->consultarAsistenciaEventos();
            $data = Array();
            while($dato=$loadAlumnos->fetchObject()){
                $data[]=array(
                0=> $dato->titulo,
                1=> $dato->fechaE,
                2=> '<a href="lista.php?t=ev&id_evento='.$dato->id_evento.'" target="_blank" class="btn btn-primary">Ver Lista</button>'
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
            echo json_encode($result);
        break;

        case 'consultarMaestros':
            unset($_POST['action']);
            $loadAlumnos = $ce->consultarMaestros();
            $data = Array();
            while($dato=$loadAlumnos->fetchObject()){
                $sexo = $dato->sexo == 'H' ? 'Hombre' : 'Mujer';
                $boton = $dato->estado == 1 ? '<button class="btn btn-primary" onclick="validarDesactivarMaestro('.$dato->id.', 0)">Desactivar</button>': '<button class="btn btn-info" onclick="validarDesactivarMaestro('.$dato->id.', 1)">Activar</button>';
                if( $dato->estado == 1 )
                    $boton = '<button class="btn btn-secondary" onclick="resetCarrerasE(); mostrarMaestro(\''.$dato->id.'\');">Editar</button> '.$boton;
                $boton = '<button id="btnAsignarClaseProfesor" class="btn btn-secondary" onclick="AsignarClase('.$dato->id.',\''.$dato->aPaterno.', '.$dato->nombres.'\');">Asignar clase</button> '.$boton;
                $boton = '<button class="btn btn-secondary" onclick="tablaClases(\''.$dato->id.'\',\''.$dato->aPaterno.', '.$dato->nombres.'\');">Ver clases</button> '.$boton;
                $data[]=array(
                0=> $dato->aPaterno.' '.$dato->aMaterno.' '.$dato->nombres,
                1=> '<a href="mailto:'.$dato->email.'" target="_blank">'.$dato->email.'</a>',
                2=> '<a href="tel:'.$dato->telefono.'" >'.$dato->telefono.'</a>',
                3=> $boton
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
            echo json_encode($result);
        break;

        case 'consultarsesionesenvivo':
            unset($_POST['action']);
            $fechahoymas20minutos = date('Y-m-d H:i:s', strtotime('+20 minutes'));
            $sesionesenvivo = $ce->consultarsesionesenvivo();
            $data = Array();
            while($dato=$sesionesenvivo->fetchObject()){
                $ingresaralasesion=false;
                if (strtotime($dato->fecha_hora_clase) <= strtotime($fechahoymas20minutos)) { //si la fecha de la clase es menor a la fecha actual mas 20 minutos control escolar puede ingrear amonitorear la clase solo se listan las clases de hoy
                    $ingresaralasesion=true;
                }
                $data[]=array(
                    0=> $dato->nombre_clase,
                    1=> $dato->nombre_materia,
                    2=> $dato->nombre_maestro,
                    3=> $dato->generacion_carrera,
                    4=> $dato->fecha_hora_clase,
                    5=> ($ingresaralasesion)?'<a href="claseswebex?id_sesion='.$dato->id_sesion.'"> <button class="btn btn-primary">Ingresar a la clase</button></a>':''
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

        case 'buscarMaestro':
            unset($_POST['action']);
            $busMaestro = $ce->buscarMaestro($_POST['idBuscar']);
            echo json_encode($busMaestro);
            break;

        case 'carrerasActuales':
            unset($_POST['action']);
            $busCarreras= $ce->carrerasActuales($_POST['idBuscar']);
            echo json_encode($busCarreras);
            break;

        case "editarMaestro":
            unset($_POST['action']);
            $editar = $ce->editarMaestro( $_POST );
            echo json_encode($editar);
            break;

        case 'desactivarMaestro':
            unset($_POST['action']);
            $del = $ce->desactivarMaestro($_POST);
            echo json_encode($del);
            break;

        case 'buscarCarrerasMaestro':
            unset($_POST['action']);
            $carreras= $ce->buscarCarrerasMaestro($_POST['idBuscar']);
            echo json_encode($carreras);
            break;
        break;

        case 'listarCarreras':
            unset($_POST['action']);
            $carreras= $ce->buscarCarreras();
            echo json_encode($carreras);
            break;
        break;

        case 'listarCarrerasE':
            unset($_POST['action']);
            $carreras= $ce->buscarCarrerasE($_POST['idBuscar']);
            echo json_encode($carreras);
            break;
        break;

        case 'listarCarrerasAsignacion':
            unset($_POST['action']);
            $carreras= $ce->buscarCarrerasAsignacion($_POST['idBuscar']);
            echo json_encode($carreras);
            break;
        break;

        case 'listarGeneraciones':
            unset($_POST['action']);
            $generaciones= $ce->listarGeneraciones($_POST['idBuscar']);
            echo json_encode($generaciones);
            break;
        break;
        
        case 'listarCiclos':
            unset($_POST['action']);
            $ciclos= $ce->listarCiclos($_POST['idPlan']);
            echo json_encode($ciclos);
            break;
        break;

        case 'listarMaterias':
            unset($_POST['action']);
            $materias= $ce->listarMaterias($_POST['idCiclo'], $_POST['idPlan']);
            echo json_encode($materias);
            break;
        break;

        case 'AsignarClase':
            unset($_POST['action']);
            $saveClass = $ce->crearClase($_POST['SGeneraciones'], $_POST['Smaterias'], $_POST['nombre_clase'], $_POST['fecha_clase'], $_POST['idMaestroAsignacion']);
            echo json_encode($saveClass);
            /*        
            $image = 0;
            $moreDocuments = 0;
            //$j = 0;
            $bandera = 0;
            $banderaApoyos = 0;
            $banderaRecursos = 0;
            $recursos = [];
            $apoyos = [];
            $formatosImg = array('.jpg', '.jpeg', '.png');
            $formatosDoc = array('.jpg', '.jpeg', '.png', '.pdf', '.docx', '.pptx', '.xlsx');
            $gen = explode('-', substr($_POST['SGeneraciones'],0 ,10));
            $_POST['SGeneraciones'] = $gen[0];

            $extension = $_FILES['foto_clase']['name'];
            $ext = substr($extension, strrpos($extension, '.'));
            //validaciones de archivos
            if(in_array($ext, $formatosImg)){
                $image = 1;    
            }
            if(count($_FILES)>1){
                $moreDocuments = 1;
            }
            if($moreDocuments == 1){
                for($i = 0 ; $i < count($_FILES) ; $i++){
                    if(isset($_FILES['archivo'.$i]) && isset($_POST['nombreArchivo'.$i])){
                        if(!empty($_FILES['archivo'.$i] && !empty($_POST['nombreArchivo'.$i]))){
                            //guardar en nuevas variables y validar extensión de archivos
                            $nameFiles[$i] = $_POST['nombreArchivo'.$i];
                            $files[$i] = $_FILES['archivo'.$i];

                            $extDocument = $files[$i]['name'];
                            $extDoc = substr($extDocument, strrpos($extDocument, '.'));
                            if(in_array($extDoc, $formatosDoc)){
                                $documents[$i] = 1;
                            }else{
                                $documents[$i] = 0;
                            }
                        }
                    }

                    if(isset($_FILES['archivoApoyo'.$i]) && isset($_POST['nombreArchivoApoyo'.$i])){
                        if(!empty($_FILES['archivoApoyo'.$i] && !empty($_POST['nombreArchivoApoyo'.$i]))){
                            //guardar en nuevas variables y validar extensión de archivos
                            $nameFilesApoyo[$i] = $_POST['nombreArchivoApoyo'.$i];
                            $filesApoyo[$i] = $_FILES['archivoApoyo'.$i];

                            $extDocumentApoyo = $filesApoyo[$i]['name'];
                            $extDocApoyo = substr($extDocumentApoyo, strrpos($extDocumentApoyo, '.'));
                            if(in_array($extDocApoyo, $formatosDoc)){
                                $documentsApoyo[$i] = 1;
                            }else{
                                $documentsApoyo[$i] = 0;
                            }
                        }
                    }

                }
                if(isset($documents)){
                    for($k = 0; $k < count($documents) ;$k++){
                        if($documents[$k]==1){
                            $banderaRecursos = 1;
                        }else{
                            $banderaRecursos = 2;
                        }
                    }
                }
                if(isset($documentsApoyo)){
                    for($p = 0; $p < count($documentsApoyo) ;$p++){
                        if($documentsApoyo[$p]==1){
                            $banderaApoyos = 1;
                        }else{
                            $banderaApoyos = 2;
                        }
                    }
                }
            }
            //validar caso
            if($image ==  1 && $banderaRecursos == 0 && $banderaApoyos == 0){
                $tmp_name = $_FILES['foto_clase']['tmp_name'];
                $uploads_dir = "../../../../assets/files/clases/fotoClase";

                $fotoExtension = explode('.', $_FILES['foto_clase']['name']);
                $fotoExt = $fotoExtension[sizeof($fotoExtension)-1];
                $saveClass = $ce->crearClase($_POST['SGeneraciones'], $_POST['Smaterias'], $_POST['nombre_clase'], $_POST['fecha_clase'], $_POST['idMaestroAsignacion'], $_POST['video']);
                
                $nName = $saveClass['data'].'-'.$fotoExtension[0].'.'.$fotoExt;
                $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");

                $savePhoto = $ce->subirfotoClase($nName, $saveClass['data']);
                echo json_encode($savePhoto);
            }else{
                if($image == 1 && $banderaRecursos == 1 && $banderaApoyos == 1){
                    $tmp_name = $_FILES['foto_clase']['tmp_name'];
                    $uploads_dir = "../../../../assets/files/clases/fotoClase";

                    $fotoExtension = explode('.', $_FILES['foto_clase']['name']);
                    $fotoExt = $fotoExtension[sizeof($fotoExtension)-1];
                        
                    $saveClass = $ce->crearClase($_POST['SGeneraciones'], $_POST['Smaterias'], $_POST['nombre_clase'], $_POST['fecha_clase'], $_POST['idMaestroAsignacion'], $_POST['video']);
                        
                    //guardar foto
                    $nName = $saveClass['data'].'-'.$fotoExtension[0].'.'.$fotoExt;
                    $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        
                    //guardar recursos
                    $uploads_dir_archivos = "../../../../assets/files/clases/recursos";

                    for($i = 0 ; $i < count($files) ; $i++){
                        $nameFinalRecurso[$i] = $saveClass['data'].'-'.$files[$i]['name'];
                        //$recursos[$i] = [$saveClass['data'].'-'.$files[$i]['name'], $nameFiles[$i]];
                        $tmp_name_archivos[$i] = $files[$i]['tmp_name'];
                        $recursos[$i] = [$nameFinalRecurso[$i], $nameFiles[$i]];
                            
                        move_uploaded_file($tmp_name_archivos[$i], "$uploads_dir_archivos/$nameFinalRecurso[$i]");
                    }
                    //guardar apoyos
                    $uploads_dir_apoyos = "../../../../assets/files/clases/apoyos";
                    for($l = 0 ; $l < count($filesApoyo) ; $l++){
                        //asigno nombre final
                        $nameFinalApoyo[$l] = $saveClass['data'].'-'.$filesApoyo[$l]['name'];

                        $tmp_name_apoyos[$l] = $filesApoyo[$l]['tmp_name'];
                        $apoyos[$l] = [$nameFinalApoyo[$l], $nameFilesApoyo[$l]];

                        move_uploaded_file($tmp_name_apoyos[$l], "$uploads_dir_apoyos/$nameFinalApoyo[$l]");
                    }

                    $recursos = json_encode($recursos);
                    $apoyos = json_encode($apoyos);

                    $savePhoto = $ce->subirfotoClase($nName, $saveClass['data']);
                    $saveRecourse = $ce->subirRecursosClase($recursos, $saveClass['data']);
                    $saveSupports = $ce->subirApoyosClase($apoyos, $saveClass['data']);

                    echo json_encode($saveSupports);
                }else{
                    if($image == 1 && $banderaRecursos == 1 && $banderaApoyos == 0){
                        $tmp_name = $_FILES['foto_clase']['tmp_name'];
                        $uploads_dir = "../../../../assets/files/clases/fotoClase";

                        $fotoExtension = explode('.', $_FILES['foto_clase']['name']);
                        $fotoExt = $fotoExtension[sizeof($fotoExtension)-1];
                        
                        $saveClass = $ce->crearClase($_POST['SGeneraciones'], $_POST['Smaterias'], $_POST['nombre_clase'], $_POST['fecha_clase'], $_POST['idMaestroAsignacion'], $_POST['video']);
                        
                        //guardar foto
                        $nName = $saveClass['data'].'-'.$fotoExtension[0].'.'.$fotoExt;
                        $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                        
                        //guardar archivos
                        $uploads_dir_archivos = "../../../../assets/files/clases/recursos";
                        for($i = 0 ; $i < count($files) ; $i++){
                            $nameFinalArchivo[$i] = $saveClass['data'].'-'.$files[$i]['name'];
                            //$recursos[$i] = [$saveClass['data'].'-'.$files[$i]['name'], $nameFiles[$i]];
                            $tmp_name_archivos[$i] = $files[$i]['tmp_name'];
                            $recursos[$i] = [$nameFinalArchivo[$i], $nameFiles[$i]];
                            
                            move_uploaded_file($tmp_name_archivos[$i], "$uploads_dir_archivos/$nameFinalArchivo[$i]");
                        }
                        $recursos = json_encode($recursos);

                        $savePhoto = $ce->subirfotoClase($nName, $saveClass['data']);
                        $saveRecourse = $ce->subirRecursosClase($recursos, $saveClass['data']);

                        echo json_encode($saveRecourse);
                    }else{
                        if($image == 1 && $banderaRecursos == 0 && $banderaApoyos == 1){
                            $tmp_name = $_FILES['foto_clase']['tmp_name'];
                            $uploads_dir = "../../../../assets/files/clases/fotoClase";

                            $fotoExtension = explode('.', $_FILES['foto_clase']['name']);
                            $fotoExt = $fotoExtension[sizeof($fotoExtension)-1];
                                
                            $saveClass = $ce->crearClase($_POST['SGeneraciones'], $_POST['Smaterias'], $_POST['nombre_clase'], $_POST['fecha_clase'], $_POST['idMaestroAsignacion'], $_POST['video']);
                                
                            //guardar foto
                            $nName = $saveClass['data'].'-'.$fotoExtension[0].'.'.$fotoExt;
                            $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
                                
                            //guardar apoyos
                            $uploads_dir_apoyos = "../../../../assets/files/clases/apoyos";

                            for($l = 0 ; $l < count($filesApoyo) ; $l++){
                                //asigno nombre final
                                $nameFinalApoyo[$l] = $saveClass['data'].'-'.$filesApoyo[$l]['name'];
                                $tmp_name_apoyos[$l] = $filesApoyo[$l]['tmp_name'];

                                $apoyos[$l] = [$nameFinalApoyo[$l], $nameFilesApoyo[$l]];

                                move_uploaded_file($tmp_name_apoyos[$l], "$uploads_dir_apoyos/$nameFinalApoyo[$l]");
                            }
                            $apoyos = json_encode($apoyos);

                            $savePhoto = $ce->subirfotoClase($nName, $saveClass['data']);
                            $saveSupports = $ce->subirApoyosClase($apoyos, $saveClass['data']);

                            echo json_encode($saveSupports);
                        }else{
                            if($image == 1 && $banderaRecursos == 2 && $banderaApoyos == 2){
                                echo 'extensiones_mal';
                            }else{
                                if($image == 1 && $banderaRecursos == 0 && $banderaApoyos == 2 || $image == 1 && $banderaRecursos == 1 && $banderaApoyos == 2){
                                    echo 'apoyo_extension_mal';
                                }else{
                                    if($image == 1 && $banderaRecursos == 2 && $banderaApoyos == 0 || $image == 1 && $banderaRecursos == 2 && $banderaApoyos == 1){
                                        echo 'documento_extension_mal';
                                    }else{
                                        if($image == 0){
                                            echo 'image_extension_mal';
                                        }
                                    }
                                }
                            }
                        }        
                    }    
                }
            }*/
            
        break;

        case 'buscarMaterias':
            unset($_POST['action']);
            $materias= $ce->buscarMaterias($_POST['idBuscar']);
            echo json_encode($materias);
            break;
        break;

        case 'buscarCarreras':
            unset($_POST['action']);
            $loadCarreras = $ce->buscarCarreras();
            $data = Array();
            while($dato=$loadCarreras->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->idCarrera
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

        case "agregarMaestro":
            unset($_POST['action']);
            $agregar = $ce->agregarMaestro( $_POST );
            echo json_encode($agregar);
            break;

        case 'consultarAsistenciaClases':
            unset($_POST['action']);
            $loadAlumnos = $ce->consultarAsistenciaClases($_POST);
            $data = Array();
            while($dato=$loadAlumnos->fetchObject()){
                $data[]=array(
                0=> $dato->nombreGen,
                1=> $dato->titulo,
                2=> $dato->fecha_hora_clase,
                3=> '<button class="btn btn-primary" data-toggle="modal" data-target="#" onclick="verAsistencias('.$dato->idClase.')">Ver Lista</button> '
                );
            }
            //verAsistencias
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case 'validarAsistencias':
            unset($_POST['action']);
            $datosAsis = $ce->validarAsistencias($_POST);
            if($datosAsis['data'] == 0){
                echo 'sin_asistencias';
            }else{
                echo 'con_asistencias';
            }
            //echo json_encode($datosAsis);
            break;

        case 'consultarAsistenciaTalleres':
            unset($_POST['action']);
            $loadAlumnos = $ce->consultarAsistenciaTalleres();
            $data = Array();
            while($dato=$loadAlumnos->fetchObject()){
                $data[]=array(
                0=> $dato->taller,
                1=> $dato->evento,
                2=> $dato->fecha,
                3=> '<a href="asistenciaTalleres.php?id_taller='.$dato->id_taller.'" target="_blank" class="btn btn-primary">Ver Lista</button>'
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
            echo json_encode($result);
        break;
        
        case 'consultarAlumnos':
            unset($_POST['action']);
            $Carrera = $_GET['idCarrera'];
            
            $loadAlumnos = $ce->consultarAlumnos();
            $data = Array();
            while($dato=$loadAlumnos->fetchObject()){
                if($dato->prorrogaDig!=0){
                    $texto = 'Asignar Prorroga D['.$dato->prorrogaDig.']';
                    if($dato->prorrogaFis!=0){
                        $texto = $texto.' F['.$dato->prorrogaFis.']';
                    }
                }else{
                    if($dato->prorrogaFis!=0){
                        $texto = 'Asignar Prorroga F['.$dato->prorrogaFis.']';
                    }else{
                        $texto = 'Asignar Prorroga';
                    }
                }
                if($Carrera == 13){
                    $data[]=array(
                    0=> $dato->aPaterno.' '.$dato->aMaterno.' '.$dato->nombre,
                    1=> $dato->ngeneracion,
                    2=> $dato->correo,
                    3=> $dato->telefono,
                    4=> $dato->docs,
                    5=> '<button class="btn btn-primary" onclick="registrarDocumentacion('.$dato->id_afiliado.','.$dato->idGeneracion.')">Documentación Fisica</button> '.
                        '<button class="btn btn-success" onclick="asignarProrrogaAlumno('.$dato->id_afiliado.','.$dato->idGeneracion.')">'.$texto.'</button> '.
                        '<button class="btn btn-secondary" onclick="validarVistaDocumentos('.$dato->id_afiliado.','.$dato->idGeneracion.')">Subir Documentos</button> '.
                        '<button class="btn btn-primary" onclick="tablaExpediente('.$dato->id_afiliado.', \''.$dato->aPaterno.' '.$dato->aMaterno.', '.$dato->nombre.'\')">Ver Expediente</button>',
                    6=> $dato->Diplomado == 2 ? '<button  class="btn btn-success">Diplomado</button>':' <button  class="btn btn-primary">Alumno TSU</button> <button  class="btn btn-success" onClick = "CambiarTipo('.$dato->idAlumnoGeneracion.')">Cambiar a Diplomado</button>',
                    7=> $dato->docs_pendientes
                    );
                }else{
                    $data[]=array(
                        0=> $dato->aPaterno.' '.$dato->aMaterno.' '.$dato->nombre,
                        1=> $dato->ngeneracion,
                        2=> $dato->correo,
                        3=> $dato->telefono,
                        4=> $dato->docs,
                        5=> '<button class="btn btn-primary" onclick="registrarDocumentacion('.$dato->id_afiliado.','.$dato->idGeneracion.')">Documentación Fisica</button> '.
                            '<button class="btn btn-success" onclick="asignarProrrogaAlumno('.$dato->id_afiliado.','.$dato->idGeneracion.')">'.$texto.'</button> '.
                            '<button class="btn btn-secondary" onclick="validarVistaDocumentos('.$dato->id_afiliado.','.$dato->idGeneracion.')">Subir Documentos</button> '.
                            '<button class="btn btn-primary" onclick="tablaExpediente('.$dato->id_afiliado.', \''.$dato->aPaterno.' '.$dato->aMaterno.', '.$dato->nombre.'\')">Ver Expediente</button>',
                        6=> '<button class="btn btn-success">Alumno</button>',
                        7=> $dato->docs_pendientes
                        );
                }
            }
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
          
            break;

        case 'consultarExpediente':
            unset($_POST['action']);
            $loadAlumnos = $ce->consultarExpediente( $_POST['idBuscar'] );
            
            $data = Array();
            while( $dato = $loadAlumnos->fetchObject()){

                if( $dato->validacion == 1 ) $val = '<div style="text-align: center;"><i class="fas fa-check" style="color:green;" title="Aceptado"></i></div>';
                else if( $dato->validacion == 2 ) $val = '<div style="text-align: center;"><i class="fas fa-times" style="color:red;" title="Rechazado"></i></div>';
                else  $val = '<div style="text-align: center;"><br>
                    <select id="select'.$dato->id_documento.'" name="select'.$dato->id_documento.'" class="form-control" onChange="javascript:requerido(\''.$dato->id_documento.'\')">
                        <option value="0" selected>¿ ?</option>
                        <option value="1">Aceptar</option>
                        <option value="2">Rechazar</option>
                    </select>
                </div>';

                if($dato->validacion == 0 ) $comentario = '<input id="comentario'.$dato->id_documento.'" name="comentario'.$dato->id_documento.'" type="text" size="20" class="form-control" placeholder="Agregue comentario..."></input>';
                else $comentario = $dato->comentario;

                $retroceso_controller = '../../../';
                $link_documento = '../siscon/app/lista_documentos/'.$dato->id_prospectos.'/'.$dato->nombre_archivo;
                if(!file_exists($retroceso_controller.$link_documento)){
                    $link_documento = '../iesm/app/lista_documentos/'.$dato->id_prospectos.'/'.$dato->nombre_archivo;
                }
                if(!file_exists($retroceso_controller.$link_documento)){
                    $link_documento = '../udc/app/lista_documentos/'.$dato->id_prospectos.'/'.$dato->nombre_archivo;
                }
                $boton = '';
                if(file_exists($retroceso_controller.$link_documento)){
                    $boton = '<a href="'.$link_documento.'" target="_blank" class="btn btn-primary" title="Descargar">Visualizar</a>';
                }else{
                    $link_documento = '../../../../siscon/app/lista_documentos/'.$dato->id_prospectos.'/'.$dato->nombre_archivo;
                    if(file_get_contents($link_documento)){
                        $boton = '<a href="'.$link_documento.'" target="_blank" class="btn btn-primary" title="Descargar">Visualizar</a>';
                    }else{
                        $boton = '<button class="btn btn-secondary disabled" disabled title="'.$link_documento.'">Archivo no encontrado.</button>';
                    }
                }
                
                $data[]=array(
                0=> '<input type="hidden" id="iddocumento'.$dato->id_documento.'" name="iddocumento'.$dato->id_documento.'" value="'.$dato->id.'"></input><b>'.nombreDocumento( $dato->id_documento ).'</b><br>'.$comentario,
                1=> $val,
                2=> '<b>Entregado:</b> '.$dato->fecha_entrega." <br><b>Validado:</b> ".$dato->fecha_validacion,
                3=> '<div style="text-align: center;">'.$boton.'</div>'
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

        case "validarExpediente":
            unset($_POST['action']);
            $validar = $ce->validarExpediente( $_POST );
            echo json_encode($validar);
            break;

        case 'consultarClasesMaestros':
            unset($_POST['action']);
            $loadClases = $ce->consultarClasesMaestros( $_POST['idBuscar'] );
            $data = Array();
            while($dato=$loadClases->fetchObject()){
                $boton = '<button class="btn btn-primary" onclick="editar_clase('.$dato->idClase.')">Editar</button> '.
                '<button class="btn btn-primary" onclick="validar_eliminar('.$dato->idClase.')">Eliminar</button>';
                //$boton = '<a href="ficha_clase.php?idClase='.$dato->idClase.'" class="btn btn-secondary" target="_blank">Ver</a> '.$boton;
                $data[]=array(
                0=> $dato->titulo,
                1=> $dato->nombreMateria,
                2=> $dato->nombreCarrera,
                3=> date("d-m-Y H:i:s", strtotime($dato->fecha_hora_clase)),
                4=> $boton
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

            /*case 'consultarAsistenciaTalleres':
                unset($_POST['action']);
                $loadAlumnos = $ce->consultarAsistenciaTalleres();
                $data = Array();
                while($dato=$loadAlumnos->fetchObject()){
                    $data[]=array(
                    0=> $dato->taller,
                    1=> $dato->evento,
                    2=> $dato->fechaE,
                    3=> '<a href="lista.php?idEvento='.$dato->id_taller.'" target="_blank" class="btn btn-primary">Ver Lista</button>'
                    );
                }
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
            break;*/

            case 'consultarExamenes':
                unset($_POST['action']);
                
                $controlButton = false;
                if($_POST['vista']!=2){
                    $controlButton = true;
                }
                unset($_POST['vista']);

                $loadAlumnos = $ce->consultarExamenes($_POST);
                $data = Array();

                
                while($dato=$loadAlumnos->fetchObject()){
                    $Button = " ";
                    if($controlButton){
                        $Button = '<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button>';
                    }

                    $data[]=array(
                    0=> $dato->Nombre,
                    1=> $dato->materia,
                    2=> $dato->maestro,
                    3=> $dato->fechaInicio,
                    4=> $dato->fechaFin,
                    5=> '<a href="resultadoExamen.php?idExamen='.$dato->idExamen.'" target="_blank" class="btn btn-primary">Ver resultados</a> '.$Button.
                        '<button class="btn btn-primary" onclick="revisar_entregas('.$dato->idExamen.')">
                        <i class="fa fa-check-square"></i> Revisar Entregas
                        </button>'
                    );
                }
                //resultados_examen
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
            break;

        case 'consultarExamenesPorID':
            unset($_POST['action']);
            $examenes = $ce->consultarExamenesPorID(['idGen'=>$_POST['idGen']]);

            if($examenes['data'] != []){
                echo json_encode($examenes);
            }else{
                echo 'Sin_examenes';
            }
            break; 

        case 'listarCarrerasExpedientes':
            unset($_POST['action']);
            $carreras= $ce->listarCarrerasExpedientes();
            echo json_encode($carreras);
            break;
        break;

        case 'listarGeneracionesExpedientes':
            unset($_POST['action']);
            $carreras= $ce->listarGeneracionesExpedientes();
            echo json_encode($carreras);
            break;
        break;

        case 'desactivarClase':
            unset($_POST['action']);
            $del = $ce->desactivarClase($_POST);
            echo json_encode($del);
            break;

        case 'buscarClasesCarrera':
            unset($_POST['action']);
            // if(!isset($_POST['vista'])){
            //     $_POST['vista'] = '1';
            // }
            $obClases = $ce->buscarClasesCarrera()['data'];
            echo json_encode($obClases);
            break;
//mike
        case 'obtenerExpedienteAlumno':
            unset($_POST['action']);
            $obExp = $ce->obtenerExpedienteAlumno($_POST);
            echo json_encode($obExp);
            break;

        case 'registrarDocumentoAdmin':
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
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../../../siscon/app/lista_documentos/'.$id)){
                                mkdir('../../../../siscon/app/lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../../../siscon/app/lista_documentos/".$id;
                            
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
        
                            
                            $saveDoc = $ce->registrarComprobanteEstudio($_POST);
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
                }else{
                    unset($_POST['gradoEstudio']);
                    if($_POST['documento']==7){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../../../siscon/app/lista_documentos/'.$id)){
                                mkdir('../../../../siscon/app/lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../../../siscon/app/lista_documentos/".$id;
                            
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
        
                            $saveDoc = $ce->registrarDocumento($_POST);
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
        
                    if($_POST['documento']==8){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../../../siscon/app/lista_documentos/'.$id)){
                                mkdir('../../../../siscon/app/lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../../../siscon/app/lista_documentos/".$id;
                            
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
        
                            
                            $saveDoc = $ce->registrarDocumento($_POST);
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
        
                    if($_POST['documento']==2){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../../../siscon/app/lista_documentos/'.$id)){
                                mkdir('../../../../siscon/app/lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../../../siscon/app/lista_documentos/".$id;
                            
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
        
                            
                            $saveDoc = $ce->registrarDocumento($_POST);
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];

                            echo json_encode($saveDoc);
                        }
                    }
        
                    if($_POST['documento']==3){
                        $extension = $_FILES['file']['name'];
                        $ext = substr($extension, strrpos($extension, '.'));
                        if(in_array($ext, $formatosDoc)){
                            if($_FILES['file']['size'] < $maxSize){
                                $pdfs = 1;
                            }else{
                                $pdfs = 0; 
                            }
                            if($ext == '.jpeg'){
                                $im=1;
                            }else{
                                $im=0;
                            }
                        }else{
                            $pdfs = 0;
                        }
        
                        if($pdfs == 0){
                            echo 'DocInc';
                        }else{
                            if(!file_exists('../../../../siscon/app/lista_documentos/'.$id)){
                                mkdir('../../../../siscon/app/lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../../../siscon/app/lista_documentos/".$id;
                            
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
        
                            
                            $saveDoc = $ce->registrarDocumento($_POST);
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
                            if(!file_exists('../../../../siscon/app/lista_documentos/'.$id)){
                                mkdir('../../../../siscon/app/lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../../../siscon/app/lista_documentos/".$id;

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
        
                            
                            $saveDoc = $ce->registrarDocumento($_POST);
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
                            if(!file_exists('../../../../siscon/app/lista_documentos/'.$id)){
                                mkdir('../../../../siscon/app/lista_documentos/'.$id, 0707);
                            }
        
                            $tmp_name = $_FILES['file']['tmp_name'];
                            $uploads_dir = "../../../../siscon/app/lista_documentos/".$id;

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
        
                            
                            $saveDoc = $ce->registrarDocumento($_POST);
                            $saveDoc['data'] = ['documento'=>$_POST['documento']];
                            
                            echo json_encode($saveDoc);
                        }
                    }
                }
            }
            break;

        case 'cargarGrado':
            unset($_POST['action']);
            $loadGrados = $ce->buscarGrados()['data'];
            echo json_encode($loadGrados);
            break;


        case 'obtenerGeneracionesCarrera':
            unset($_POST['action']);
            $obGen = $ce->obtenerGeneracionesCarrera($_POST)['data'];
            if($obGen != []){
                echo json_encode($obGen);
            }else{
                echo 'sin_generaciones';
            }
            break;

        case 'obtenerMaestros':
            unset($_POST['action']);
            $obMaestros = $ce->obtenerMaestros($_POST)['data'];
            if($obMaestros != []){
                echo json_encode($obMaestros);
            }else{
                echo 'sin_maestros';
            }
            break;

        case 'obtenerMaterias':
            unset($_POST['action']);
            $obPlan = $ce->buscarPlanEstudioGeneracion($_POST)['data'];
            //var_dump($obPlan['id_plan_estudio']);
            $obMat = $ce->obtenerMaterias($_POST['idGen'], $obPlan['id_plan_estudio'])['data'];
            if($obMat != []){
                echo json_encode($obMat);
            }else{
                echo 'sin_materias';
            }
            break;

        case 'crearExamen':
            unset($_POST['action']);
            $_POST['fechaInicioExamen'] = date('Y-m-d H:i:s', strtotime($_POST['fechaInicioExamen'].",".$_POST['horaInicioExamen']));
            $_POST['fechaFinExamen'] = date('Y-m-d H:i:s', strtotime($_POST['fechaFinExamen'].",".$_POST['horaFinExamen']));
            unset($_POST['horaInicioExamen']);
            unset($_POST['horaFinExamen']);
            unset($_POST['Numero_Preguntas']);
            unset($_POST['retomar_preguntas']);
            unset($_POST['datatable-tablaPreguntas2_length']);
            
             if(isset($_POST['aplicar_extraordinario']) && $_POST['aplicar_extraordinario'] == 'on'){
                $_POST['aplicar_extraordinario'] = 2;
            }else{
                if(isset($_POST['aplicar_ordinario']) && $_POST['aplicar_ordinario'] == 'on'){
                    $_POST['aplicar_extraordinario'] = 3;
                    
                }else{
                    $_POST['aplicar_extraordinario'] = 1;

                }
                $_POST['costoPesos'] = 0;
                $_POST['costoUsd'] = 0;
            }
             unset($_POST['aplicar_ordinario']);
            //unset($_POST['examenCarrera']);
            if(isset($_POST['aplicar_multiple']) && $_POST['aplicar_multiple'] == 'on'){
                $_POST['aplicar_multiple'] = 1;
                if(isset($_POST['inp_porcentaje_aprobar_i']) && intval($_POST['inp_porcentaje_aprobar_i']) > 0){
                    $_POST['inp_porcentaje_aprobar_i'] = intval($_POST['inp_porcentaje_aprobar_i']);
                }else{
                    echo json_encode(['estatus'=>'error', 'mensaje'=>'El porcentaje de aprobación debe ser mayor a 0']);
                    die();
                }
            }else{
                $_POST['aplicar_multiple'] = 2;
                $_POST['inp_porcentaje_aprobar_i'] = 0;
            }

            $examen_ref = null;
            //Verifica el envio de un id_correcto
                if(isset($_POST['id_examen_pasado']) && intval($_POST['id_examen_pasado'])>0){
                    $examen_ref = intval($_POST['id_examen_pasado']);
                    //-----
                    $num_preguntas = null; 
                    if(isset($_POST['num_preguntas_retomar']) && intval($_POST['num_preguntas_retomar'])>0){
                        $tam_valido =  $ce->Validar_tamanio($_POST['id_examen_pasado'])['data'];

                        if(intval($_POST['num_preguntas_retomar']) <= $tam_valido['Total_preguntas'] && intval($_POST['num_preguntas_retomar'])>0){
                            $num_preguntas = intval($_POST['num_preguntas_retomar']);
                            //En caso de que el id este correcto
                            //El numero de preguntas sea mayor a 0 y menor igual que el tamaño del examen  
                            $_POST['id_examen_pasado']=$examen_ref;
                            $newExam = $ce->crearExamen($_POST);
                            echo json_encode($newExam);
                            /*correo alumnos*/
                            if(!isset($_POST['aplicar_extraordinario'])){

                            $alumnos_examen = $ce->alumnos_examen($_POST['examenGeneracion'])['data'];
                                    require_once '../../functions/correos_prospectos.php';
                                    $udc_o_conacon=$alumnos_examen[0]['idInstitucion'];
                                    $institucion=($udc_o_conacon==20)?'UNIVERSIDAD DEL CONDE':'COLEGIO NACIONAL DE CONSEJEROS';
                                    $logoinstitucion = ($udc_o_conacon==20)?'https://moni.com.mx/udc/img/logoT.png':'https://conacon.org/moni/siscon/img/logoT.png';
                                    $liga_institucion = ($udc_o_conacon==20)?'https://moni.com.mx/udc/':'https://conacon.org/moni/siscon/';
                                    foreach ($alumnos_examen as $key => $value) {
                                            $asunto = "RECORDATORIO ".$_POST['nombreExamen']." - ".$institucion;
                                            $destinatarios = [[$value['email'], $value['nombre_completo']]];
                                            $plantilla_c = 'plantilla_recordatorio_examen.html';
                                            $claves = ['%%prospecto','%%instituto','%%nombreexamen','%%horainicioexamen','%%horafinexamen','%%nombre_carrera','%%logo_institucion','%%liga_institucion_examen'];
                                            $valores = [$value['nombre_completo'],$institucion,$_POST['nombreExamen'],$_POST['fechaInicioExamen'],$_POST['fechaFinExamen'],$value['nombre'],$logoinstitucion,$liga_institucion];
                                            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                                    }
                            /*correo alumnos*/
                                    /*correo docente*/
                                    $obtener_maestro = $ce->obtener_maestro($_POST['selectMaestros'])['data'];
                                    $nombre_carrera = $ce->obtener_nombre_carrera($_POST['examenCarrera'])['data'];
                                    $asunto = "RECORDATORIO ".$_POST['nombreExamen']." - ".$institucion;
                                            $destinatarios = [[$obtener_maestro['email'], $obtener_maestro['nombre_completo']]];
                                            $plantilla_c = 'plantilla_recordatorio_examen_maestro.html';
                                            $claves = ['%%prospecto','%%instituto','%%nombreexamen','%%horainicioexamen','%%horafinexamen','%%nombre_carrera','%%logo_institucion'];
                                            $valores = [$obtener_maestro['nombre_completo'],$institucion,$_POST['nombreExamen'],$_POST['fechaInicioExamen'],$_POST['fechaFinExamen'],$nombre_carrera['nombre'],$logoinstitucion];
                                            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                                    /*correo docente*/
                            
                                }
                        }else{
                            echo json_encode(['estatus'=>'error', 'mensaje'=>'El numero de preguntas debe ser menor o igual al numero de preguntas']);
                            die();
                        }
                    }
                    else {
                        echo json_encode(['estatus'=>'error', 'mensaje'=>'El numero de preguntas debe ser mayor a 0']);
                        die();
                    }
                    //Se envia num_preguntas = NULL
                   // $_POST['num_preguntas_retomar']=$num_preguntas;                    //-----
                }else{

                    //echo json_encode(['estatus'=>'error', 'mensaje'=>'id examen invalido']);
                    //$num_preguntas = intval($_POST['num_preguntas_retomar']);
                    $_POST['num_preguntas_retomar'] = null;
                    $_POST['id_examen_pasado']=null;
                    $newExam = $ce->crearExamen($_POST);
                    echo json_encode($newExam);
                    /*correo alumnos*/
                    if(!isset($_POST['aplicar_extraordinario'])){
                    $alumnos_examen = $ce->alumnos_examen($_POST['examenGeneracion'])['data'];
                                    require_once '../../functions/correos_prospectos.php';
                                    $udc_o_conacon=$alumnos_examen[0]['idInstitucion'];
                                    $institucion=($udc_o_conacon==20)?'UNIVERSIDAD DEL CONDE':'COLEGIO NACIONAL DE CONSEJEROS';
                                    $logoinstitucion = ($udc_o_conacon==20)?'https://moni.com.mx/udc/img/logoT.png':'https://conacon.org/moni/siscon/img/logoT.png';
                                    $liga_institucion = ($udc_o_conacon==20)?'https://moni.com.mx/udc/':'https://conacon.org/moni/siscon/';
                                    foreach ($alumnos_examen as $key => $value) {
                                            $asunto = "RECORDATORIO ".$_POST['nombreExamen']." - ".$institucion;
                                            $destinatarios = [[$value['email'], $value['nombre_completo']]];
                                            $plantilla_c = 'plantilla_recordatorio_examen.html';
                                            $claves = ['%%prospecto','%%instituto','%%nombreexamen','%%horainicioexamen','%%horafinexamen','%%nombre_carrera','%%logo_institucion','%%liga_institucion_examen'];
                                            $valores = [$value['nombre_completo'],$institucion,$_POST['nombreExamen'],$_POST['fechaInicioExamen'],$_POST['fechaFinExamen'],$value['nombre'],$logoinstitucion,$liga_institucion];
                                            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                                    }
                    /*correo alumnos*/
                                    /*correo docente*/
                                    $obtener_maestro = $ce->obtener_maestro($_POST['selectMaestros'])['data'];
                                    $nombre_carrera = $ce->obtener_nombre_carrera($_POST['examenCarrera'])['data'];
                                    $asunto = "RECORDATORIO ".$_POST['nombreExamen']." - ".$institucion;
                                            $destinatarios = [[$obtener_maestro['email'], $obtener_maestro['nombre_completo']]];
                                            $plantilla_c = 'plantilla_recordatorio_examen_maestro.html';
                                            $claves = ['%%prospecto','%%instituto','%%nombreexamen','%%horainicioexamen','%%horafinexamen','%%nombre_carrera','%%logo_institucion'];
                                            $valores = [$obtener_maestro['nombre_completo'],$institucion,$_POST['nombreExamen'],$_POST['fechaInicioExamen'],$_POST['fechaFinExamen'],$nombre_carrera['nombre'],$logoinstitucion];
                                            $enviar = enviar_correo_registro($asunto, $destinatarios, $plantilla_c, $claves, $valores, "none");
                                    /*correo docente*/
                    //   die();
                    }
                }
            break;

        case 'obtenerDatosExamen':
            unset($_POST['action']);
            $datosExamen = $ce->obtenerDatosExamen($_POST)['data'];
            $datosExamen['horaInicio'] = date("H:i:s", strtotime($datosExamen['fechaInicio']));
            $datosExamen['horaFin'] = date("H:i:s", strtotime($datosExamen['fechaFin']));
            $datosExamen['fechaInicio'] = date("Y-m-d", strtotime($datosExamen['fechaInicio']));
            $datosExamen['fechaFin'] = date("Y-m-d", strtotime($datosExamen['fechaFin']));
            echo json_encode($datosExamen);
            break;

        case 'editarExamen':
            unset($_POST['action']);
            $_POST['editarFechaInicioExamen'] = date('Y-m-d H:i:s', strtotime($_POST['editarFechaInicioExamen'].",".$_POST['editarHoraInicioExamen']));
            $_POST['editarFechaFinExamen'] = date('Y-m-d H:i:s', strtotime($_POST['editarFechaFinExamen'].",".$_POST['editarHoraFinExamen']));
            unset($_POST['editarHoraInicioExamen']);
            unset($_POST['editarHoraFinExamen']);

			if(isset($_POST['retomar_preguntas_e'])){
                $_POST['num_preguntas_retomar_e'] = intval($_POST['num_preguntas_retomar_e']) > 0 ? $_POST['num_preguntas_retomar_e'] : null;
            }else{
                $_POST['num_preguntas_retomar_e'] = null;
            }
            

            if(isset($_POST['retomar_preguntas_e']) && $_POST['retomar_preguntas_e'] == 'on'){
                
                //$_POST['aplicar_multiple'] = 1;
                if(isset($_POST['id_examen_pasado_e']) && intval($_POST['id_examen_pasado_e']) > 0){
                    $_POST['id_examen_pasado_e'] = intval($_POST['id_examen_pasado_e']);
                }else{
                    echo json_encode(['estatus'=>'error', 'mensaje'=>'Debe seleccionar un examen pasado']);
                    die();
                }
            }else{
                $_POST['id_examen_pasado_e'] = NULL;
                //$_POST['num_preguntas_retomar_e'] = NULL;

            }
            // Datos que no se editan
            unset($_POST['retomar_preguntas_e']);
            //unset($_POST['num_preguntas_retomar_e']);
            unset($_POST['datatable-tablaPreguntas3_length']);

            if(isset($_POST['aplicar_multiple']) && $_POST['aplicar_multiple'] == 'on'){
                $_POST['aplicar_multiple'] = 1;
                if(isset($_POST['inp_porcentaje_aprobar']) && intval($_POST['inp_porcentaje_aprobar']) > 0){
                    $_POST['inp_porcentaje_aprobar'] = intval($_POST['inp_porcentaje_aprobar']);
                }else{
                    echo json_encode(['estatus'=>'error', 'mensaje'=>'El porcentaje de aprobación debe ser mayor a 0']);
                    die();
                }
            }else{
                $_POST['aplicar_multiple'] = 2;
                $_POST['inp_porcentaje_aprobar'] = 0;

            }

		
            $editExam = $ce->editarExamen($_POST);
            echo json_encode($editExam);
            break;
            
            case 'CrearExamenBanco':
                unset($_POST['action']);
                $_POST['fechaInicioExamenBanco'] = date('Y-m-d H:i:s', strtotime($_POST['fechaInicioExamenBanco'].",".$_POST['horaInicioExamenBanco']));
                $_POST['fechaFinExamenBanco'] = date('Y-m-d H:i:s', strtotime($_POST['fechaFinExamenBanco'].",".$_POST['horaFinExamenBanco']));
                $_POST['CarreraBanco'];
                unset($_POST['horaInicioExamenBanco']);
                unset($_POST['horaFinExamenBanco']);

                //Correccion en el post de banco para matchear
                if(isset($_POST['aplicar_extraordinarioBanco']) && $_POST['aplicar_extraordinarioBanco'] == 'on'){
                    /*$_POST['nameMat']=$_POST['nameMatBanco'];
                    $_POST['costoPesos']=$_POST['costoPesosBanco'];;
                    $_POST['costoUsd']= $_POST['costoUsdBanco'];*/
                    $_POST['aplicar_extraordinarioBanco'] = 2;
                }else{
                
                    if(isset($_POST['aplicar_ordinarioBanco']) && $_POST['aplicar_ordinarioBanco'] == 'on'){
                    $_POST['aplicar_extraordinarioBanco'] = 3;
                   
	        	}else{
		            $_POST['aplicar_extraordinarioBanco'] = 1;

		        }
                    $_POST['costoPesosBanco'] = 0;
                    $_POST['costoUsdBanco'] = 0;
                    
                }
    
    		 unset($_POST['aplicar_ordinarioBanco']);
                //Preguntas del banco
                //$PreguntasBanco=array();
                $PreguntasBanco = $_POST['id_preguntas'];
                $PreguntasBanco = explode(',',$PreguntasBanco);
                unset($_POST['id_preguntas']);
    
                if(isset($_POST['aplicar_multipleBanco']) && $_POST['aplicar_multipleBanco'] == 'on'){
                    $_POST['aplicar_multipleBanco'] = 1;
                    if(isset($_POST['inp_porcentaje_aprobar_iBanco']) && intval($_POST['inp_porcentaje_aprobar_iBanco']) > 0){
                        $_POST['inp_porcentaje_aprobar_iBanco'] = intval($_POST['inp_porcentaje_aprobar_iBanco']);
                    }else{
                        echo json_encode(['estatus'=>'error', 'mensaje'=>'El porcentaje de aprobación debe ser mayor a 0']);
                        die();
                    }
                }else{
                    $_POST['aplicar_multipleBanco'] = 2;
                    $_POST['inp_porcentaje_aprobar_iBanco'] = 0;
                }
    


                $crearExamBanco = $ce->crearExamenBanco($_POST);
                $id_NuevoBanco = $crearExamBanco['data'];
                

                if(count($PreguntasBanco) == 0){
                    echo json_encode(['estatus'=>'error', 'mensaje'=>'Debe seleccionar preguntas para continuar']);
                    die();   
                }else{
                    //$InsterPreguntasBanco = array();
                    $band = false;
                    for($c=0;$c<count($PreguntasBanco);$c++){
                        $InsterPreguntasBanco=$ce->insertarPreguntaExamenBanco($id_NuevoBanco,$PreguntasBanco[$c]);
                       if($InsterPreguntasBanco['estatus']!='ok'){
                        $band = true;
                       }
                    }
                    if($band){
                        $InsterPreguntasBanco['mensaje'] = 'Se creo el examen pero fallo al insertar una pregunta';
                        echo json_encode($InsterPreguntasBanco);
                    }else{
                        echo json_encode($crearExamBanco);
                    }   
                }
                break;
        case 'ActualizarTitulados':
            unset($_POST['action']);
            $IdAlumnos = $_POST['id_Alumnos'];
            unset($_POST['id_Alumnos']);
            $IdAlumnos = explode(',',$IdAlumnos);

            $date = date('Y-m-d H:i:s');
            $_POST['fecha'] = $date;

            foreach($IdAlumnos as $Alu){
                //Aqui va un update con la fecha y los datos a actualizar
                $_POST['idAlumno']= $Alu;
                $actualizarTitulados=$ce->actualizarTitulados($_POST);
            }
            echo json_encode($actualizarTitulados);
            //$_POST[]
            //echo $date;

            break;

        case 'obtenerAlumnosconBloqueo':  
                unset($_POST['action']);
                $obAlumnosBloqueo =  $ce->obtenerAlumnosconBloqueo($_POST)['data'];
                echo json_encode($obAlumnosBloqueo);
                break;

        case 'validarVistaDocumentos':
            unset($_POST['action']);
            $obDatosVista =  $ce->validarVistaDocumentos($_POST)['data'];
            echo json_encode($obDatosVista);
            break;

        case 'no_session':
            echo 'no_session';
            break;
// chuy
        case 'consultar_by_id':
            $id = $_POST['id'];
            $consultar = $ce->consultar_clase_by_id($id);
            if($consultar && $consultar['video'] != ''){
                $consultar['video'] = urldecode($consultar['video']);
            }
            $consultar['recursos'] = $consultar['recursos'] != '' && $consultar['recursos'] != null ? json_decode($consultar['recursos']) : [];
            $consultar['apoyo'] = $consultar['apoyo'] != '' && $consultar['apoyo'] != null ? json_decode($consultar['apoyo']) : [];
            echo json_encode($consultar);
            break;
        case 'actualiza_clase':
            unset($_POST['action']);
            $response = [];
            $recursos = [];
            $materiales = [];
            foreach($_FILES as $ix => $archivo){
                $document = cargar_documento($archivo, $_POST['input_nombre_'.explode('_',$ix)[1].'_'.explode('_',$ix)[2]],explode('_',$ix)[1]);
                $arr = [$document, $_POST['inp_edit_clase'].'-'.$_POST['input_nombre_'.explode('_',$ix)[1].'_'.explode('_',$ix)[2]]];
                // echo "[".$_POST['inp_edit_clase'].'-'.$_POST['input_nombre_'.explode('_',$ix)[1].'_'.explode('_',$ix)[2]]."]";
                // echo "<br>";
                if(explode('_',$ix)[1] == 'recursos'){
                    array_push($recursos, $arr);
                    // $recursos[] = $arr;
                }else{
                    array_push($materiales, $arr);
                    // $materiales[] = $arr;
                }
                unset($_POST['input_nombre_'.explode('_',$ix)[1].'_'.explode('_',$ix)[2]]);
            }
            if(!empty($recursos)){
                $_POST['recursos'] = json_encode($recursos);
            }
            if(!empty($materiales)){
                $_POST['apoyo'] = json_encode($materiales);
            }

            $_POST['select_generacion_edit'] = explode('-',$_POST['select_generacion_edit'])[0];
            // print_r($_POST);
            // die();
            $_POST['inp_edit_link'] = urlencode($_POST['inp_edit_link']);
            $response = $ce->actualizar_datos_clase($_POST);
            echo json_encode($response);
            break;

        case 'validarEliminarClase':
            unset($_POST['action']);
            $validacionEliminacion = $ce->validarEliminarClase($_POST)['data'];
            if($validacionEliminacion['video'] == ''){
                echo json_encode($validacionEliminacion);
            }else{
                echo 'con_link';
            }
            break;

        case 'eliminarClase':
            unset($_POST['action']);
            $elimClase = $ce->eliminarClase($_POST)['data'];
            echo json_encode($elimClase);
            break;

        case 'asignarProrrogaAlumnoDocumento':
            unset($_POST['action']);
            $asigProrrogaAlumno = $ce->asignarProrrogaAlumnoDocumento($_POST)['data'];
            echo json_encode($asigProrrogaAlumno);
            break;

        case 'obtenerIdDocumentoAlumnoProrroga':
            unset($_POST['action']);
            $obDocumentoProrroga = $ce->obtenerIdDocumentoAlumnoProrroga($_POST)['data'];
            echo json_encode($obDocumentoProrroga);
            break;

        case 'obtenerDatosProrrogaDigital':
            unset($_POST['action']);
            $obDatosProrrogaDig = $ce->obtenerDatosProrrogaDigital($_POST)['data'];
            if($obDatosProrrogaDig['fecha_prorroga_digital'] != null){
                $obDatosProrrogaDig['hora_prorroga_digital'] = date("H:i:s", strtotime($obDatosProrrogaDig['fecha_prorroga_digital']));
                $obDatosProrrogaDig['fecha_prorroga_digital'] = date("Y-m-d", strtotime($obDatosProrrogaDig['fecha_prorroga_digital']));   
            }else{
                $obDatosProrrogaDig = ['fecha_prorroga_digital' => null,
                                        'hora_prorroga_digital' => null];
            }
            echo json_encode($obDatosProrrogaDig);
            break;

        case 'modificarProrrogaDigital':
            unset($_POST['action']);
            $_POST['modificarFechaDigital'] = date('Y-m-d H:i:s', strtotime($_POST['modificarFechaDigital'].",".$_POST['modificarHoraDigital']));
            unset($_POST['modificarHoraDigital']);
            $modAsigProrrogaDigital = $ce->modificarProrrogaDigital($_POST);        
            echo json_encode($modAsigProrrogaDigital);
            break;

        case 'asignarFechaProrrogaDigital':
            unset($_POST['action']);
            $idGen = $_POST['idGeneracion'];
            unset($_POST['idGeneracion']);
            $busRegistroPrevio = $ce->buscarRegistroProrroga($_POST['idAlumno'], $_POST['idDocumento'])['data'];

            if($busRegistroPrevio!=[]){
                $_POST['fechaDigital'] = date('Y-m-d H:i:s', strtotime($_POST['fechaDigital'].",".$_POST['horaDigital']));
                unset($_POST['horaDigital']);
                $asigProrrogaDigital = $ce->reactivarProrrogaDocumento($_POST['fechaDigital'], $busRegistroPrevio['id_prorroga']);
                if($asigProrrogaDigital['estatus'] == 'ok'){
                    $asigProrrogaDigital = ['idAlumno'=>$_POST['idAlumno'],
                    'id_generacion'=>$idGen];
                }else{
                    $asigProrrogaDigital = [];
                }
                echo json_encode($asigProrrogaDigital);
            }else{
                $_POST['fechaDigital'] = date('Y-m-d H:i:s', strtotime($_POST['fechaDigital'].",".$_POST['horaDigital']));
                unset($_POST['horaDigital']);
                $asigProrrogaDigital = $ce->asignarFechaProrrogaDigital($_POST);
                if($asigProrrogaDigital['estatus'] == 'ok'){
                    $asigProrrogaDigital = ['idAlumno'=>$_POST['idAlumno'],
                    'id_generacion'=>$idGen];
                }else{
                    $asigProrrogaDigital = [];
                }
    
                echo json_encode($asigProrrogaDigital);
            }
            /*$idGen = $_POST['idGeneracion'];
            unset($_POST['idGeneracion']);
            $_POST['fechaDigital'] = date('Y-m-d H:i:s', strtotime($_POST['fechaDigital'].",".$_POST['horaDigital']));
            unset($_POST['horaDigital']);
            $asigProrrogaDigital = $ce->asignarFechaProrrogaDigital($_POST);
            if($asigProrrogaDigital['estatus'] == 'ok'){
                $asigProrrogaDigital = ['idAlumno'=>$_POST['idAlumno'],
                'id_generacion'=>$idGen];
            }else{
                $asigProrrogaDigital = [];
            }

            echo json_encode($asigProrrogaDigital); bien*/
            break;

        case 'quitarProrrogaDocumento':
            unset($_POST['action']);
            $delProrrDoc = $ce->quitarProrrogaDocumento($_POST['id']);
            if($delProrrDoc['estatus'] == 'ok'){
                $delProrrDoc = ['idAlumno'=>$_POST['idAlum'],
                'id_generacion'=>$_POST['idGen']];
            }else{
                $delProrrDoc = [];
            }

            echo json_encode($delProrrDoc);
            break;

        case 'obtenerListaDocumentosFisicos':
            unset($_POST['action']);
            $listDocFis = $ce->obtenerListaDocumentosFisicos($_POST)['data'];
            echo json_encode($listDocFis);
            break;
        //mike
        case 'registrarDocumentosFisicos':
            unset($_POST['action']);
            $totalChecks = 0;
            $checks = [];
            $z = 0;

            $fRegistro = date('Y-m-d H:i:s');
            
            $obTodosRegistros = $ce->obtenerTodosRegistros($_POST['idAlumnoDocumentacionFisica'])['data'];
            if($obTodosRegistros!=[]){
                for($l = 0; $l < count($obTodosRegistros); $l++){
                    $ids[$l] = $obTodosRegistros[$l]['id_documento'];
                }
            }else{
                $ids[] = '';
            }
                
            if(count($_POST) == 2){
                if(empty($ids[0])){
                    echo 'selecciona_documento';
                }else{
                    $resetRegistroDoc = $ce->reseteoDocumentosFisicos($_POST['idAlumnoDocumentacionFisica']);
                    echo json_encode($resetRegistroDoc);
                }
            }else{
                foreach($_POST as $key => $value){
                    if($key!='idAlumnoDocumentacionFisica' && $key!='idUsuarioRegistro'){
                        $listChecks[$z] = explode('checkName', $key)[1];
                        $z++;
                    }
                }
                //var_dump(count($listChecks));
                //var_dump($checks);
                //var_dump($listChecks);
                if(array_diff($ids, $listChecks) == array_diff($listChecks, $ids)){
                    echo 'documentos_existentes';
                }else{
                    //echo 'ava';
                    $resetRegistroDoc = $ce->reseteoDocumentosFisicos($_POST['idAlumnoDocumentacionFisica'])['data'];
                    for($y = 0; $y < count($listChecks) ;$y++){
                        $registroDoc = $ce->registrarDocumentosFisicos($listChecks[$y], $_POST['idAlumnoDocumentacionFisica'], $fRegistro, $_POST['idUsuarioRegistro']);    
                    }
                    echo json_encode($registroDoc); 
                }
            
                /*
                for($y = 0; $y < count($listChecks) ;$y++){
                    //var_dump($listChecks[$y]);
                    $busRegistroDoc = $ce->buscarDocumentosFisicos($listChecks[$y], $_POST['idAlumnoDocumentacionFisica'])['data'];
                    if($busRegistroDoc == []){
                        $registroDoc = $ce->registrarDocumentosFisicos($listChecks[$y], $_POST['idAlumnoDocumentacionFisica'], $fRegistro, $_POST['idUsuarioRegistro']);
                        //$idsEnviados[$y] = $busRegistroDoc[0]['id_documento'];
                    }else{
                        //var_dump($busRegistroDoc[0]['id_documento']);
                        $idsEnviados[$y] = $busRegistroDoc[0]['id_documento'];
                    }
                }
                var_dump("///");
                var_dump($idsEnviados);
                var_dump('//separador//');
                $diffTodos = array_diff($ids, $idsEnviados);
                $diffEnviados = array_diff($idsEnviados, $ids);
                var_dump($diffTodos);
                var_dump('///////');
                var_dump($diffEnviados);
                die();
                if($diffTodos!=0){

                }else{
                    echo json_encode($registroDoc);
                }*/
            }
            break;

        case 'recuperarChecksDocumentos';
            unset($_POST['action']);
            $checksDocs = $ce->recuperarChecksDocumentos($_POST)['data'];
            echo json_encode($checksDocs);
            break;
        //fin_mike

        case 'obtenerProrrogasFisica':
            unset($_POST['action']);
            $obProrrogasFisica = $ce->obtenerProrrogasFisica($_POST)['data'];
            echo json_encode($obProrrogasFisica);
            break;

        case 'obtenerIdProrrogaDocumentoFisico':
            unset($_POST['action']);
            $obDocumentoProrroga = $ce->obtenerIdProrrogaDocumentoFisico($_POST)['data'];
            echo json_encode($obDocumentoProrroga);
            break;

        case 'asignarFechaProrrogaFisico':
            unset($_POST['action']);
            $idGen = $_POST['idGeneracionFisico'];
            unset($_POST['idGeneracionFisico']);
            $busRegistroPrevio = $ce->buscarRegistroProrroga($_POST['idAlumnoFisico'], $_POST['idDocumentoFisico'])['data'];

            if($busRegistroPrevio!=[]){
                $_POST['fechaFisico'] = date('Y-m-d H:i:s', strtotime($_POST['fechaFisico'].",".$_POST['horaFisico']));
                unset($_POST['horaFisico']);
                $asigProrrogaFisico = $ce->reactivarProrrogaDocumentoFisico($_POST['fechaFisico'], $busRegistroPrevio['id_prorroga']);
                if($asigProrrogaFisico['estatus'] == 'ok'){
                    $asigProrrogaFisico = ['idAlumno'=>$_POST['idAlumnoFisico'],
                    'id_generacion'=>$idGen];
                }else{
                    $asigProrrogaFisico = [];
                }
                echo json_encode($asigProrrogaFisico);
            }else{
                $_POST['fechaFisico'] = date('Y-m-d H:i:s', strtotime($_POST['fechaFisico'].",".$_POST['horaFisico']));
                unset($_POST['horaFisico']);
                $asigProrrogaFisico = $ce->asignarFechaProrrogaFisico($_POST);
                if($asigProrrogaFisico['estatus'] == 'ok'){
                    $asigProrrogaFisico = ['idAlumno'=>$_POST['idAlumnoFisico'],
                    'id_generacion'=>$idGen];
                }else{
                    $asigProrrogaFisico = [];
                }
    
                echo json_encode($asigProrrogaFisico);
            }
            break;

            case 'quitarProrrogaDocumentoFisico':
                unset($_POST['action']);
                $delProrrDoc = $ce->quitarProrrogaDocumentoFisico($_POST['id']);
                if($delProrrDoc['estatus'] == 'ok'){
                    $delProrrDoc = ['idAlumno'=>$_POST['idAlum'],
                    'id_generacion'=>$_POST['idGen']];
                }else{
                    $delProrrDoc = [];
                }

                echo json_encode($delProrrDoc);
                break;

            case 'obtenerDatosProrrogaFisico':
                unset($_POST['action']);
                $obDatosProrrogaFis = $ce->obtenerDatosProrrogaFisico($_POST)['data'];
                if($obDatosProrrogaFis['fecha_prorroga_fisica'] != null){
                    $obDatosProrrogaFis['hora_prorroga_fisica'] = date("H:i:s", strtotime($obDatosProrrogaFis['fecha_prorroga_fisica']));
                    $obDatosProrrogaFis['fecha_prorroga_fisica'] = date("Y-m-d", strtotime($obDatosProrrogaFis['fecha_prorroga_fisica']));   
                }else{
                    $obDatosProrrogaFis = ['fecha_prorroga_fisica' => null,
                                            'hora_prorroga_fisica' => null];
                }
                echo json_encode($obDatosProrrogaFis);
                break;

        case 'modificarProrrogaFisico':
            unset($_POST['action']);
            $_POST['modificarFechaFisico'] = date('Y-m-d H:i:s', strtotime($_POST['modificarFechaFisico'].",".$_POST['modificarHoraFisico']));
            unset($_POST['modificarHoraFisico']);
            $modAsigProrrogaFisico = $ce->modificarProrrogaFisico($_POST);        
            echo json_encode($modAsigProrrogaFisico);
            break;

        case 'obtenerGeneracionesMaterias':
            unset($_POST['action']);
            $obGenMat = $ce->obtenerGeneracionesMaterias($_POST)['data'];
            echo json_encode($obGenMat);
            break;

        case 'obtenerCiclosGeneracion':
            unset($_POST['action']);
            $obCiclosGen = $ce->obtenerCiclosGeneracion($_POST)['data'];
            echo json_encode($obCiclosGen);
            break;
        
        case 'obtenerCarrera_ref':
                unset($_POST['action']);

                $Examen_ref1 = $ce->obtenerCarrera_ref($_POST)['data'];
                //var_dump($Examen_ref1);
                echo json_encode($Examen_ref1);
                break;
        
        case 'ObtenerCalificacionMinima':
                    unset($_POST['action']);
                    $Calif_minima = $ce->ObtenerCalificacionMinima($_POST['idMat']);
                    //var_dump($_POST['idMat']);
                    echo json_encode($Calif_minima);
                    break;

        case  'CambiarCalificacionMinima':
                unset($_POST['action']);
                $Cambio_minima = $ce->CambiarCalificacionMinima($_POST)['data'];
                //var_dump($_POST['idMat']);
                echo json_encode($Cambio_minima);
                break;
                
        case  'VerificarExistenciaCalificacion':
            unset($_POST['action']);
            
            $obCiclo = $ce->obtenerCicloSeleccionado($_POST['idCic'])['data']['ciclo_asignado'];
            $_POST['idCic'] =  $obCiclo;
            $CalificacionesReg = $ce->consultarCalificaciones(['idMat'=> $_POST['idMat'],'idGen'=> $_POST['idGen']]);
            
            $calBase = Array();
                        while($dato=$CalificacionesReg->fetchObject()){
                            $data[]=array(
                            0=> $dato->nombre,
                            1=> $dato->calificacion,
                            2=> $dato->idalumno,
                            3=> $dato->idCalificacion
                            );
                        }

            $NuevasCal = $_POST['arrEntr'];
            unset($_POST['arrEntr']);

            for($i=0;$i<count($NuevasCal);$i++){
                if($NuevasCal[$i][1]==''){
                    if($data[$i][3]!=null){
                        $obCambiarCalificacion = $ce->cambiarCalificacion(['califAlum'=>"Sin Calificación",'idCalif'=>$data[$i][3]]);
                    }
                }else{
                    if($data[$i][3]!=null){
                        $obCambiarCalificacion = $ce->cambiarCalificacion(['califAlum'=>$NuevasCal[$i][1],'idCalif'=>$data[$i][3]]);
                    }else{
                        $obInsertCalificacion = $ce->insertarCalificacion(['idMat'=>$_POST['idMat'],'ciclo'=> $_POST['idCic'],'idAlum'=>$NuevasCal[$i][0],'idGen'=>$_POST['idGen'],'califAlum'=>$NuevasCal[$i][1],'fecha'=>date('Y-m-d H:i:s'),'idUsuarioRegistro'=>null]);
                    }
                }
            }
            echo json_encode(['estatus'=>'ok', 'data'=>1]);
            break;

        case 'obtenerPreguntasExamenesRef':
                unset($_POST['action']);
                //Preguntas de referencia en banco de preguntas
                $PreguntasRef = $ce->obtenerPreguntasExamenesRef($_POST['idCarr'])['data'];
                //var_dump($Examen_ref1);
                echo json_encode($PreguntasRef);
                break;

        case 'consultarAsistenciaMaterias':
            unset($_POST['action']);
            $obCiclo = $ce->obtenerCicloSeleccionado($_POST['idCiclo'])['data'];
            $_POST['idNumeroCiclo'] = $obCiclo['ciclo_asignado'];
            unset($_POST['idCiclo']);
            $tablaMaterias = $ce->consultarAsistenciaMaterias($_POST);
                $data = Array();
                while($dato=$tablaMaterias->fetchObject()){
                    $data[]=array(
                    0=> $dato->nombre,
                    1=> '<a href="asistenciasMateria.php?id_materia='.$dato->id_materia.'&id_generacion='.$dato->idGeneracion.'" target="_blank" class="btn btn-primary">Ver Asistencias</a> '
                    );
                }
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
            break;

            case 'listaMaterias':
                unset($_POST['action']);
                $obCiclo = $ce->obtenerCicloSeleccionado($_POST['idCiclo'])['data'];
                $_POST['idNumeroCiclo'] = $obCiclo['ciclo_asignado'];
                unset($_POST['idCiclo']);
                $tablaMaterias = $ce->listaMaterias($_POST);
                $data = Array();
                    while($dato=$tablaMaterias->fetchObject()){
                        $data[]=array(
                        0=> $dato->nombre,
                        1=> '<button type="button" class="btn btn-primary" onclick="consultarCalificaciones('.$dato->id_materia.','.$dato->idGeneracion.')">Ver Calificaciones</button>'
                        );
                    }
                    //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                    $result = array(
                        'sEcho'=>1,
                        'iTotalRecords'=>count( $data ),
                        'iTotalDisplayRecords'=>count( $data ),
                        'aaData'=>$data
                    );
                    echo json_encode($result);
                break;

            case 'consultarCalificacionesGenCiclo':
                    unset($_POST['action']);
                    $Genciclo = $ce->consultarCalificacionesGenCiclo($_POST);
                    
                    $data = Array();
                        while($dato=$Genciclo->fetchObject()){
                            $data[]=array(
                            0=> $dato->nombre,
                            1=> $dato->Matricula,
                            2=> '<button type="button" class="btn btn-primary" onclick="consultarCalificacionesCicloGen('.$dato->idalumno.',\''.$dato->nombre.'\')">Ver Calificaciones</button>'
                            );
                        }
                        //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                        $result = array(
                            'sEcho'=>1,
                            'iTotalRecords'=>count( $data ),
                            'iTotalDisplayRecords'=>count( $data ),
                            'aaData'=>$data
                        );
                        echo json_encode($result);
                    break;
            case 'ConsultarCalPorciclo':

                unset($_POST['action']);
                unset($_POST['idCarr']);
                //unset($_POST['idGen']);
                
                $obCiclo = $ce->obtenerCicloSeleccionado($_POST['idCic'])['data'];
                $califPorCiclo= $ce->ConsultarCalPorciclo($_POST['idGen'],$_POST['idAlu'],$obCiclo['ciclo_asignado']);

                $data = Array();
                        while($dato=$califPorCiclo->fetchObject()){
                            $data[]=array(
                            0=> $dato->nombre,
                            1=> $dato->calificacion
                            );
                        }
                        $result = array(
                            'sEcho'=>1,
                            'iTotalRecords'=>count( $data ),
                            'iTotalDisplayRecords'=>count( $data ),
                            'aaData'=>$data
                        );
                        echo json_encode($result);
                //echo json_encode($califPorCiclo);

                break;

                case 'consultarCalificacionesBoletas':
                        unset($_POST['action']);
                        $CarreraB = $_POST['idCarr'];
                        //unset($_POST['Carr']);
                        $Genciclo = $ce->consultarCalificacionesGenCiclo($_POST);
                        $obCiclo = $ce->obtenerCicloSeleccionado($_POST['idCiclo'])['data']['ciclo_asignado'];
                        $CicloIni = $_POST['idCiclo'];
                        //Verificar envio de la variable de Generacion
                        $Generacion = $_POST['idGen'];
                        unset($_POST['idGen']);

                        /*$Carrera= $_POST['Carr'];
                        $Generacion = $_POST['Gen'];
                        $Ciclo = $_POST['Cic'];

                        unset($_POST['Carr']);
                        unset($_POST['Gen']);
                        unset($_POST['Cic']);*/
                        
                        $data = Array();
                            while($dato=$Genciclo->fetchObject()){
                                $NumeroCiclos = $ce->obtener_calificaciones_periodo($Generacion,$dato->idalumno,$obCiclo);
                                if(count($NumeroCiclos['data'])>0){
                                    $data[]=array(
                                        0=> $dato->nombre,
                                        1=> $dato->Matricula,
                                        2=> "<a type='button' target='_blank' href= 'GenerarBoleta.php?idGen=$Generacion&idCarrera=$CarreraB&idAlumno=$dato->idalumno&ciclo=$CicloIni&nombre=$dato->nombre'>Generar Boleta</a>"
                                        );
                                }else{
                                    $data[]=array(
                                        0=> $dato->nombre,
                                        1=> $dato->Matricula,
                                        2=> "<h5>SIN CALIFICACIONES</h5>"
                                        );
                                }
                                
                            }
                            //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                            $result = array(
                                'sEcho'=>1,
                                'iTotalRecords'=>count( $data ),
                                'iTotalDisplayRecords'=>count( $data ),
                                'aaData'=>$data
                            );
                            echo json_encode($result);
                        break;

                case 'consultarAlumnosGen':
                            unset($_POST['action']);
                            $Carrera = $_POST['idCarr'];
                            unset($_POST['idCarr']);

                            $Genciclo = $ce->consultarAlumnosGen($_POST);

                            $Generacion = $_POST['idGen'];
                            unset($_POST['idGen']);

                            
                            $data = Array();
                                while($dato=$Genciclo->fetchObject()){
                                    $NumeroCiclos = $ce->obtener_numero_de_ciclos($Generacion,$dato->idalumno);
                                    if(count($NumeroCiclos['data'])>0){
                                        $data[]=array(
                                            0=> $dato->nombre,
                                            1=> $dato->Matricula,
                                            2=> "<a type='button' target='_blank' href= 'GenerarKardex.php?Generacion=$Generacion&idCarrera=$Carrera&idAlumno=$dato->idalumno&nombre=$dato->nombre'>Generar Kardex</a>"
                                            );
                                    }else{
                                        $data[]=array(
                                            0=> $dato->nombre,
                                            1=> $dato->Matricula,
                                            2=> "<h5>SIN CALIFICACIONES</h5>"
                                            );
                                    }
                                    
                                }
                                //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                                $result = array(
                                    'sEcho'=>1,
                                    'iTotalRecords'=>count( $data ),
                                    'iTotalDisplayRecords'=>count( $data ),
                                    'aaData'=>$data
                                );
                                echo json_encode($result);
                        break;
            case 'ActualizarAsignadosArticulo':
                unset($_POST['action']);
                
                $idAsignados = $_POST['id_asignados'];
                $idAsignados = explode(',',$idAsignados);

                unset($_POST['id_asignados']);
                foreach($idAsignados as $idAlumno){
                    //var_dump($idAlumno);
                    $_POST['idAlum'] = $idAlumno;
                    $VerificarArticuloAlum = $ce->validarArticuloRelacion(['idAlum'=>$_POST['idAlum']]);
                    if($VerificarArticuloAlum['data'] > 0){
                        //var_dump("Cambio",$idAlumno);
                        $updateArticuloAlum = $ce->actualizarArticuloRelacion(['idAlum'=>$_POST['idAlum'],'id_art'=>$_POST['id_art']]);
                    }else{
                        //var_dump("Insertar",$idAlumno);
                        $InsertarArticuloAlum = $ce->insertarArticuloAlumno($_POST);
                        $VerificarVista = $ce->ValidarVistaAlumno(['idAlum'=>$_POST['idAlum']]);
                        //var_dump($VerificarVista['data']);
                        if($VerificarVista['data'] == 0 ){
                            $OtorgarVista = $ce->insertaVistaAlumno(['idAlum'=>$_POST['idAlum']]);
                            //var_dump($OtorgarVista);
                        }   
                    }
                }

                echo json_encode($VerificarArticuloAlum);
                break;

            case 'EditarProcesoBase':
                unset($_POST['action']);
                $EditarProceso = $ce->editar_proceso($_POST);
                echo json_encode($EditarProceso);
                break;
            
            case 'EliminarFormatoRec':
                unset($_POST['action']);

                unlink('../../../files/servicio/documentos/'.$_POST["nombre"].'');
                unset($_POST['nombre']);

                $EliminarFormato = $ce->EliminarFormatoRec($_POST);
                echo json_encode($EliminarFormato);
                break;

            case 'EliminarProcesoRec':
                unset($_POST['action']);

                $EliminarProceso = $ce->eliminar_proceso($_POST);
                echo json_encode($EliminarProceso);
                break;

            case 'agregarformatoproc':
                unset($_POST['action']);

                $nombre = $_FILES['archivoformato']['name'];

                $archivo = (isset($_FILES['archivoformato'])) ? $_FILES['archivoformato'] : null;
                if ($archivo) {
                    $ruta_destino_archivo = "../../../files/servicio/documentos/{$archivo['name']}";
                    $archivo_ok = move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
                }

                unset($_FILES['archivoformato']);
                $_POST['archivoformato'] = $nombre;
                //var_dump($_POST);
                $Nuevo_formato = $ce->agregarformatoproc($_POST);
                echo json_encode($Nuevo_formato);
                break;

            case 'editarformatoproc':
                unset($_POST['action']);

                $nombre = $_FILES['archivoformatoEditar']['name'];

                $archivo = (isset($_FILES['archivoformatoEditar'])) ? $_FILES['archivoformatoEditar'] : null;
                if ($archivo) {
                    $ruta_destino_archivo = "../../../files/servicio/documentos/{$archivo['name']}";
                    $archivo_ok = move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
                }

                unset($_FILES['archivoformatoEditar']);
                $_POST['archivoformatoEditar'] = $nombre;

                $editarFormatoBase = $ce->editarformatoproc($_POST);
                echo json_encode($editarFormatoBase);
                break;                

            case 'consultar_documentos_proceso':
                unset($_POST['action']);
                $_POST['band'] = true;
                $documentosprocesos = $ce->consultar_documentos_proceso($_POST);
                $data = Array();
                while($dato=$documentosprocesos->fetchObject()){
                    $data[]=array(
                        0=> $dato->nombre,
                        1=> '<a type="button" target="_blank" href= "../assets/files/servicio/documentos/'.$dato->archivo.'"> Descargar: '.$dato->nombre.'</a>',
                        2=> $dato->vecesenvio,
                        3=> '<div class = "row  m-0 justify-content-center"><button class = "form-group btn btn-secondary" onClick ="EditarFormato(\''.$dato->idarchivo.'\',\''.$dato->nombre.'\', \''.$dato->archivo.'\', \''.$dato->vecesenvio.'\')"> Editar </button> <button class = "form-group btn btn-primary" onClick ="EliminarFormato(\''.$dato->idarchivo.'\', \''.$dato->archivo.'\')"> Eliminar </button></div>'
                    );
                }
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
                break;

            case 'agregarproceso':
                unset($_POST['action']);
                $nuevoprocesos = $ce->insertar_proceso($_POST);
                echo json_encode($nuevoprocesos);
                break;
            
            case 'consultarProcesoSelect':
                unset($_POST['action']);
                $procesos = $ce->consultar_procesos(false);
                echo json_encode($procesos);
            break;

            case 'consultarProcesos':
                unset($_POST['action']);
                $procesos = $ce->consultar_procesos(true);
                $data = Array();
                $num=0;
                while($dato=$procesos->fetchObject()){
                    $num++;
                    $data[]=array(
                        0=> $num,
                        1=> $dato->nombre,
                        2=> $dato->orden,
                        3=> '<div class = "row  m-0 justify-content-center"><button class = "form-group btn btn-secondary" onClick ="EditarProceso(\''.$dato->idproceso.'\',\''.$dato->nombre.'\', \''.$dato->orden.'\')"> Editar </button>  <button class = "form-group btn btn-primary" onClick ="EliminarProceso(\''.$dato->idproceso.'\')"> Eliminar </button></div>'
                    );
                }
                $num=0;
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
            break;

            

            case 'DocumentosAlumnosServicioCorr':
                unset($_POST['action']);
                $documentosCorregidos = $ce->consultarDocumentosAlumnosServicio(2);
                

                $data = Array();
                while($dato=$documentosCorregidos->fetchObject()){
                    //var_dump($dato);
                    $data[]=array(
                        0=> '<strong>Alumno: </strong>'.$dato->nombre.'<br><strong>Carrera: </strong>'.$dato->NombreCarr.'<br><strong>Generacion: </strong>'.$dato->nombreGen,
                        1=> '<strong>Proceso: </strong>'.$dato->nombreproc.'<br><strong>Formato: </strong> '.$dato->nombredocproc.'<br><strong>Número de formato enviado: </strong>'.$dato->intento.'<br><strong>Archivo Enviado: </strong>'.$dato->nombredocAlum,
                        2=> '<button class ="btn btn-primary form-group" onClick ="agregarObservaciones('.$dato->iddocumento.',\''.$dato->idAfiliado.'\')">Ver Documentos</button>',
                        );
                }
                //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
                break;

            case 'DocumentosAlumnosServicioSolic':
                    unset($_POST['action']);
                    $documentosCorregidos = $ce->consultarDocumentosAlumnosServicio(3);
                    
    
                $data = Array();
                while($dato=$documentosCorregidos->fetchObject()){
                    $ButtonDoc = '<button class ="btn btn-primary" onClick ="agregarObservaciones('.$dato->iddocumento.',\''.$dato->idAfiliado.'\')">Ver Documentos</button>';
                    $ButtonOrig = '<button class ="btn btn-success" onClick ="SolicitarOriginales('.$dato->iddocumento.',\''.$dato->idalumno.'\')">Solicitar Originales</button>';
                    $ButtonConcluir = '<button class ="btn btn-primary" onClick ="VerificarEntrega('.$dato->iddocumento.',\''.$dato->idalumno.'\')">Validar Originales</button>';
                    $data[]=array(
                        0=> '<strong>Alumno: </strong>'.$dato->nombre.'<br><strong>Carrera: </strong>'.$dato->NombreCarr.'<br><strong>Generacion: </strong>'.$dato->nombreGen,
                        1=> '<strong>Proceso: </strong>'.$dato->nombreproc.'<br><strong>Formato: </strong> '.$dato->nombredocproc.'<br><strong>Número de formato enviado: </strong>'.$dato->intento.'<br><strong>Archivo Enviado: </strong>'.$dato->nombredocAlum,
                        2=> $ButtonDoc.' '.$ButtonOrig.' '.$ButtonConcluir
                        );
                }
                //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);
                    break;

            case 'InsertarComentarioServicio':
                unset($_POST['action']);
                $comentarioInsertado = $ce->InsertarComentarioServicio($_POST);
                $cambioestatus = $ce->CambiarEstatusDocumentoServicio(['idArchivo'=>$_POST['idArchivo'],'estatus'=>2]);
                echo json_encode($comentarioInsertado);
                break;

            case 'CambiarestatusServicio':
                //Estatus Solciitar Original
                unset($_POST['action']);
                $_POST['estatus'] = '3';
                $EstatusServicio = $ce->CambiarestatusServicio($_POST);
                echo json_encode($EstatusServicio);
                break;

            case 'CambiarestatusServicioConcluido':
                unset($_POST['action']);
                $_POST['estatus'] = '4';
                $EstatusServicio = $ce->CambiarestatusServicio($_POST);
                echo json_encode($EstatusServicio);
                break;

            case 'consultarComentariosArchivo':
                unset($_POST['action']);
                $ComentariosArchivo = $ce->verComentariosArchivo($_POST);
                $data = Array();
                while($dato=$ComentariosArchivo->fetchObject()){
                    
                    $data[]=array(
                        0=> $dato->autor == 1 ? '<strong>Control Escolar: </strong>'.$dato->comentario: '<strong>Alumno: </strong>'.$dato->comentario,
                        1=> $dato->fecha
                        );
                }
                //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);

                break;

            case 'actualizarEstatusServicio':
                unset($_POST_['action']);
                $NuevoEstatus = $ce->actualizarEstatusServicio($_POST);
                echo json_encode($NuevoEstatus);
                break;

            case 'ConsultaFormatosRevision':
                unset($_POST['action']);
                    $Model2 = $ce->ConsultaFormatosRevision(2);
                    $idUsuario = $_POST['usr'];
                    unset($_POST['usr']);
                    //var_dump($Model2);
                    $data = Array();
                    $count = 0;
                    $datosConsulta = Array();
                        while($dato=$Model2->fetchObject()){

                            //var_dump($dato);
                            for($i=0;$i<$dato->vecesenvio;$i++){
                                $datosConsulta = ['idProceso' => $dato->idproceso, 'idFormato' => $dato->idarchivo, 'numEnvio' => strval($i+1), 'idAlumno'=> $idUsuario];
                                //var_dump($datosConsulta);
                                $Consulta = $ce->validarEntregaDoc($datosConsulta);
                                $ControlDoc = '';
                                $button = '<button class="btn btn-primary" onClick="InsertarDocumentoAlumno(\''.$dato->idproceso.'\',\''.$dato->idarchivo.'\',\''.strval($i+1).'\',\''.$idUsuario.'\')">Enviar Documento</button>';
                                if($Consulta['data'] != []){
                                    $NombreArch = $Consulta['data'][0]['nombre'];
                                    $idDocumento = $Consulta['data'][0]['iddocumento'];
                                    $Archivo = '<a href="../assets/files/servicio/alumnos/'.$NombreArch.'" target="_blank">Visualizar</a>';
                                    //var_dump($Consulta['data'][0]['estatus']);
                                    $Mensaje = null;
                                    switch($Consulta['data'][0]['estatus']){
                                        case '1':
                                            $Mensaje = "Enviado";
                                            break;
                                        case '2':
                                            $Mensaje = "Revision";
                                            break;
                                        case '3':
                                            $Mensaje = "Listo";
                                            break;
                                    }
                                    $Buttons = '<div class = "row  m-0 justify-content-center"><button class ="btn btn-primary form-group" onClick ="formatoListo('.$idDocumento.')">Formato Listo</button> <button class ="btn btn-primary form-group" onClick ="MostrarDocumentosAlumno('.$idDocumento.',\''.$idUsuario.'\')">Hacer Observaciones</button></div>';
                                    $ControlDoc = '<div class = "row text-center form-group"><div class = "col-md-6">'.$Mensaje.'</div><div class = "col-md-6">'.$Archivo.'</div></div>'.$Buttons;
                                    
                                }
                                //var_dump($button);
                                //var_dump($Consulta);
                                $count++;
                                $data[]=array(
                                    0=> $count,
                                    1=> '<b>'.$dato->procNombre.'</b>',
                                    2=> $dato->nombre,
                                    3=> ($i+1).' de '.$dato->vecesenvio,
                                    4=> $ControlDoc == '' ? '<h5>Inactivo<h5>' : $ControlDoc
        
                                );
                            }
                            
                            //var_dump($datosConsulta);
                            $datosConsulta = [];	
                        }	
                        $result = array(
                            'sEcho'=>1,
                            'iTotalRecords'=>count( $data ),
                            'iTotalDisplayRecords'=>count( $data ),
                            'aaData'=>$data
                        );
                    echo json_encode($result);
                break;

            case 'documentoCambioListo':
                unset($_POST['action']);
                $cambioestatusListo = $ce->CambiarEstatusDocumentoServicio(['idArchivo'=>$_POST['idArchivo'],'estatus'=>3]);
                echo json_encode($cambioestatusListo);
                break;

            case 'consultarDocumentosAlumnosServicio':
                unset($_POST['action']);
                $documentosAlServicio = $ce->consultarDocumentosAlumnosServicio(1);

                $data = Array();
                while($dato=$documentosAlServicio->fetchObject()){
                    
                    $data[]=array(
                        0=> '<strong>Alumno: </strong>'.$dato->nombre.'<br><strong>Carrera: </strong>'.$dato->NombreCarr.'<br><strong>Generacion: </strong>'.$dato->nombreGen,
                        1=> '<strong>Proceso: </strong>'.$dato->nombreproc.'<br><strong>Formato: </strong> '.$dato->nombredocproc.'<br><strong>Número de formato enviado: </strong>'.$dato->intento.'<br><strong>Archivo Enviado: </strong>'.$dato->nombredocAlum,
                        2=> '<div class = "row  m-0 justify-content-center"><button class ="btn btn-primary form-group" onClick ="agregarObservaciones('.$dato->iddocumento.',\''.$dato->idAfiliado.'\')">Ver documentos</button></div>',
                        );
                }
                //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);

                break;

            case 'consultarAlumnosGenAsignar':
                unset($_POST['action']);
                $Carrera = $_POST['idCarr'];
                unset($_POST['idCarr']);

                $Genciclo = $ce->consultarAlumnosGenAsinados($_POST);

                $Generacion = $_POST['idGen'];
                unset($_POST['idGen']);

                
                $data = Array();
                    while($dato=$Genciclo->fetchObject()){
                        $nombre = "Sin Artículo";
                        switch($dato->Articulo){
                            case 1:
                                $nombre = "<td><b><span class='text-success'>Artículo 52 LRART. 5 Const.</span></b></td>";
                                break;
                            case 2:
                                $nombre = "<td><b><span class='text-success'>Artículo 55 LRART. 5 Const.</span></b></td>";
                                break;
                            case 3:
                                $nombre = "<td><b><span class='text-success'>Artículo 91 LRART. 5 Const.</span></b></td";
                                break;
                            case 4:
                                $nombre = "<td><b><span class='text-success'>Artículo 10.</span></b></td";
                                break;
                            case 5:
                                $nombre = "<strong>No aplica.</strong>";
                                break;    
                        }

                        $data[]=array(
                            0=> $dato->nombre,
                            1=> $nombre,
                            2=> $dato->Matricula,
                            3=> "<div class = 'row  m-0 justify-content-center MycheckboxAsignados'><input class='form-check-input' type='checkbox' name ='' value='$dato->idalumno' id='' onclick='obtenerAsignados($dato->idalumno)'/> </div>"
                            );
                    }
                    //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                    $result = array(
                        'sEcho'=>1,
                        'iTotalRecords'=>count( $data ),
                        'iTotalDisplayRecords'=>count( $data ),
                        'aaData'=>$data
                    );
                    echo json_encode($result);
            break;

            case 'actualizarestatusSolicituscred':
                unset($_POST['action']);
                $EstatusSolicitud = $ce->actualizarestatusSolicituscred($_POST);
                echo json_encode($EstatusSolicitud);
                break;

            case 'obtenerSolicitudesCredenciales':
                unset($_POST['action']);
                $SolicitudesCred = $ce->obtenerSolicitudesCredenciales();
                
                $data = Array();
                
                    while($dato=$SolicitudesCred->fetchObject()){
                        $estatus = '';
                        $FinMembresia = '';
                        //var_dump($dato->idPago);
                        $FechaFin = $ce->fechafinmembresia($dato->idPago);
                        if($FechaFin['data'] != false){
                            $FinMembresia = '<b>FIN DE MEMBRESIA: '.$FechaFin['data']['finmembresia'].'</b>';
                        }else{
                            $FinMembresia = '<b>MEMBRESIA NO VIGENTE</b>';
                        }

                        $fotografia = '';
                        $Button = '<br><button class ="btn btn-primary form-group" onClick="tablaCredenciales('.$dato->idalumno.')">Ver Solicitudes</button>';

                        if($dato->idCarrera == '13'){
                            $carpeta = 'siscon';
                            //var_dump("siscon",$dato->idalumno,$dato->foto);
                        }else{
                            $carpeta = 'udc';
                            //var_dump("udc",$dato->idalumno,$dato->foto);
                        }

                        if($dato->foto != null || $dato->foto != ""){
                            $direccion = '../'.$carpeta.'/app/lista_documentos/'.$dato->idalumno.'/'.$dato->foto;
                            //var_dump($direccion);
                            $fotografia = '<img class="img-responsive" width="150" height="180"  src="../'.$carpeta.'/app/lista_documentos/'.$dato->idalumno.'/'.$dato->foto.'">';
                            
                            if($dato->validacion == '2'){
                                $fotografia = 'FOTOGRAFIA NO VALIDADA';
                            }else{
                                if($dato->validacion == '0'){
                                    $fotografia = 'FOTOGRAFIA EN ESPERA DE REVISIÓN';
                                }   
                            }
                        }
                        switch($dato->estatuSol){
                            case 1:
                                $estatus = '<span class="text-warning">SOLICITADA</span>';
                                break;
                            case 2:
                                $estatus = '<span class="text-success">AUTORIZADA</span>';
                                break;
                            case 3:
                                $estatus = '<span class="text-primary">FINALIZADA</span>';
                                break;
                            case 4:
                                $estatus = '<span class="text-danger">RECHAZADA</span>';
                                break;
                        }
                        $Matricula = "SIN MATRICULA ASIGNADA";
                        if($dato->Matricula != null || $dato->Matricula != ""){
                            $Matricula = $dato->Matricula;
                        }
                       
                        $Sol=2;
                        $Sol2=4;
                        $data[]=array(
                            0=> $fotografia !='' ? $fotografia : 'FOTOGRAFIA PENDIENTE',
                            //1=> '<b>ALUMNO: </b><br>'.$dato->nombre.'<br><b>CARRERA: </b><br>'.$dato->nombreCarr.'<br><b>CICLO Y GENERACIÓN: </b><br>'.$dato->cicloActual.' | ' .$dato->NombreGen.'<br><b>MATRICULA: </b><br>'.$Matricula,
                            1=> '<b>ALUMNO: </b><br>'.$dato->nombre.'<b><br>MATRICULA: </b><br>'.$Matricula,
                            2=> '<b>FECHA DE SOLICITUD: </b>'.$dato->fecha_solicitud.'<BR><b>ESTATUS: '.$estatus.'</b>',
                            3=> $FinMembresia,
                            4=> '<div class = "row  m-0 justify-content-center">'.$Button.'</div>'
                            );
                           
                    }
                    //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                    $result = array(
                        'sEcho'=>1,
                        'iTotalRecords'=>count( $data ),
                        'iTotalDisplayRecords'=>count( $data ),
                        'aaData'=>$data
                    );
                    echo json_encode($result);

                break;

            case 'consultarHistorialSolicitudes':
                unset($_POST['action']);
                $_POST['band'] = '1';
                $SolicitudesCred = $ce->consultarHistorialSolicitudes($_POST);
                $data = Array();

             
                while($dato=$SolicitudesCred->fetchObject()){
                    /*$VigenciaPago = $ce->fechafinmembresia($dato->idalumno);
                    var_dump($dato->idalumno);
                    var_dump($VigenciaPago);*/
                    $estatus = "";
                    switch($dato->estatus){
                        case 1:
                            $estatus = '<span class="text-warning">SOLICITADA</span>';
                            break;
                        case 2:
                            $estatus = '<span class="text-success">AUTORIZADA</span>';
                            break;
                        case 3:
                            $estatus = '<span class="text-primary">FINALIZADA</span>';
                            break;
                        case 4:
                            $estatus = '<span class="text-danger">RECHAZADA</span>';
                            break;
                    }
                    $Sol=2;
                    $Sol2=4;
                    $Sol3=3;
                    $data[]=array(
                        0=>  '<b>CARRERA:</b><br>'.$dato->nombreCarrera.'<b><br>GENERACIÓN:</b><br>'.$dato->nombreGen,
                        1=>  '<b>FECHA DE SOLICITUD: </b>'.$dato->fecha_solicitud.'<br> <b>ESTATUS: '.$estatus.'</b>',
                        2=>  '<div class = "row  m-0 justify-content-center"><button class = "btn btn-success form-group" onClick="actualizarestatusSolicituscred('.$dato->idsolicitud.',\''. $Sol.'\')"> Autorizar Descarga</button><br><button class = "btn btn-primary form-group" onClick="actualizarestatusSolicituscred('.$dato->idsolicitud.',\''. $Sol2.'\')">Rechazar Descarga</button><br><button class = "btn btn-secondary form-group" onClick="actualizarestatusSolicituscred('.$dato->idsolicitud.',\''. $Sol3.'\')">Finalizar Descarga</button></div>'
                        );
                }
                //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                $result = array(
                    'sEcho'=>1,
                    'iTotalRecords'=>count( $data ),
                    'iTotalDisplayRecords'=>count( $data ),
                    'aaData'=>$data
                );
                echo json_encode($result);

                break;

            case 'fechafinmembresia':
                unset($_POST['action']);
                $FinMembresia = $ce->fechafinmembresia($_POST);
                echo json_encode($FinMembresia);
                break;
            
            case 'InsertaTipoAlumno':
                unset($_POST['action']);
                $NuevaGeneracionAl = $ce->ActualizaTipo($_POST);
                //var_dump($NuevaGeneracionAl);
                echo json_encode($NuevaGeneracionAl);
                break;

            case 'consultarAlumnosGenTitulados':
                unset($_POST['action']);
                $Carrera = $_POST['idCarr'];
                unset($_POST['idCarr']);

                $Genciclo = $ce->consultarAlumnosGen($_POST);

                $Generacion = $_POST['idGen'];
                unset($_POST['idGen']);

                
                $data = Array();
                    while($dato=$Genciclo->fetchObject()){
                        //if($dato->fechalib != null && $dato->calificacionT > 6){
                        if($dato->estatus != 2){
                            $data[]=array(
                                0=> $dato->nombre,
                                1=> $dato->Matricula,
                                2=> "<div class = 'row  m-0 justify-content-center MycheckboxTitulados'><input class='form-check-input' type='checkbox' name ='' value='$dato->idalumno' id='' onclick='obtenerTitulados($dato->idalumno)'/> </div>"
                                );
                            }else{
                                $data[]=array(
                                    0=> $dato->nombre,
                                    1=> $dato->Matricula,
                                    2=> "<div class = 'row  m-0 justify-content-center'><h5>ALUMNO TITULADO</h5></div>"
                                    );
                            }
                        /*}else{
                            $data[]=array(
                                0=> $dato->nombre,
                                1=> $dato->Matricula,
                                2=> "<h5>Aun no cumple con los requisitos</h5>"
                                );
                        }*/
                            
                    }
                    //<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
                    $result = array(
                        'sEcho'=>1,
                        'iTotalRecords'=>count( $data ),
                        'iTotalDisplayRecords'=>count( $data ),
                        'aaData'=>$data
                    );
                    echo json_encode($result);
            break;

           

            case 'listaMateriasSelect':
                    unset($_POST['action']);
                    $obCiclo = $ce->obtenerCicloSeleccionado($_POST['idCiclo'])['data'];
                    $_POST['idNumeroCiclo'] = $obCiclo['ciclo_asignado'];
                    unset($_POST['idCiclo']);
                    $tablaMaterias = $ce->listaMaterias($_POST);
                    $data = Array();
                        $dato=$tablaMaterias->fetchAll(PDO::FETCH_ASSOC);
                        echo json_encode($dato);
                    break;
            
            case 'consultarCalificaciones':
                unset($_POST['action']);
                $obtenerCalificaciones = $ce->consultarCalificaciones($_POST);
                $data = Array();
                    while($dato=$obtenerCalificaciones->fetchObject()){
                        $data[]=array(
                        0=> $dato->nombre,
                        1=> '<input class="form-control" name = "'.$dato->idCalificacion.'" maxlength="2" onkeyup="checkCalificaciones('.$dato->idalumno.')" id="calificacion'.$dato->idalumno.'" value="'.$dato->calificacion.'">',
                        2=> $dato->idCalificacion === NULL ? '<button class="btn btn-primary" onclick="validarInsertarCalificacion('.$dato->idalumno.')">Cambiar</button>' : '<button class="btn btn-primary"  onclick="validarModificarCalificacion('.$dato->idCalificacion.','.$dato->idalumno.', this)">Cambiar</button>'
                        );
                    }
                    //'<button class="btn btn-primary" onclick="cambiarCalificacion('.$dato->idCalificacion.')">Cambiar</button>'
                    $result = array(
                        'sEcho'=>1,
                        'iTotalRecords'=>count( $data ),
                        'iTotalDisplayRecords'=>count( $data ),
                        'aaData'=>$data
                    );
                    echo json_encode($result);
                break;

            case 'consultarCalificaciones_noAcre':
                    unset($_POST['action']);
                    
                    //var_dump($Calif_Min);
                   $obtenerCalificaciones = $ce->consultarCalificaciones($_POST);
                   
                   $obtenerCalif_Min = $ce->ObtenerCalificacionMinima($_POST['idMat']);
                   $Calif_Min=intval($obtenerCalif_Min['data']['calificacion_min']);
                   
                    $data = Array();
                        while($dato=$obtenerCalificaciones->fetchObject()){
                            if($dato->calificacion < $Calif_Min || $dato->calificacion == 'sin calificacion' || $dato->calificacion == 'N/C'){
                                $data[]=array(       
                                    0=> $dato->nombre,
                                    1=> '<input class="form-control" maxlength="2" onkeyup="checkCalificaciones('.$dato->idalumno.')" id="calificacion'.$dato->idalumno.'" value="'.$dato->calificacion.'">',
                                    2=> $dato->idCalificacion === NULL ? '<button class="btn btn-primary" onclick="validarInsertarCalificacionNoAcre('.$dato->idalumno.')">Cambiar</button>' : '<button class="btn btn-primary" onclick="validarModificarCalificacion('.$dato->idCalificacion.','.$dato->idalumno.', this)">Cambiar</button>'    
                                );
                            }  
                        }
                        //'<button class="btn btn-primary" onclick="cambiarCalificacion('.$dato->idCalificacion.')">Cambiar</button>'
                        $result = array(
                            'sEcho'=>1,
                            'iTotalRecords'=>count( $data ),
                            'iTotalDisplayRecords'=>count( $data ),
                            'aaData'=>$data
                        );
                        echo json_encode($result);
                    break;

            case 'cambiarCalificacion':
                unset($_POST['action']);
                $obCalificacion = $ce->cambiarCalificacion($_POST);
                echo json_encode($obCalificacion);
                break;

            case 'insertarCalificacion':
                unset($_POST['action']);
                $busCiclo = $ce->buscarCicloMateria($_POST['idGen'], $_POST['idMat'])['data'];
                $_POST['ciclo'] = $busCiclo['ciclo_asignado'];
                $_POST['fecha'] = date('Y-m-d H:i:s');
                $obInsertCalificacion = $ce->insertarCalificacion($_POST);
                echo json_encode($obInsertCalificacion);
                break;

            case 'insertarCalificacionenGrupo':
                unset($_POST['action']);
                $busCiclo = $ce->buscarCicloMateria($_POST['idGen'], $_POST['idMat'])['data'];
                $_POST['ciclo'] = $busCiclo['ciclo_asignado'];
                $_POST['fecha'] = date('Y-m-d H:i:s');
                $obInsertCalificacion = $ce->insertarCalificacion($_POST);
                echo json_encode($obInsertCalificacion);
                break;
    
        
                //Camnbios
        case 'consultar_clases_materias':
            $materias_car = $matsM->obtenerMateriasPorCarrera(['idCarrera'=>$_POST['carrera']])->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($materias_car);
            $clases = [];
            foreach($materias_car as $key_m => $materia){
                $cls = $matsM->consultar_clases_materia($materia['id_materia'], $_POST['generacion']);
                if(!empty($cls)){
                    $clases = array_merge($clases, $cls);
                }
            }
            echo json_encode($clases);
            //$materias = $matsM->consultar_clases_materia($materia, $generacion);
            break;
        case 'volcar_alumnos':
            unset($_POST['action']);
            $alumnos = $ce->volcar_alumnos();
            echo json_encode($alumnos);
        break;

        case 'selectuser':
            unset($_POST['action']);
            
            $id = $_SESSION["usuario"]['idPersona'];
            $type = $_SESSION["usuario"]['idTipo_Persona'];
            $loadAlumnos = $matsM->getUsers($id,$type);
            $data = Array();
            $dataN = array();
            //var_dump($loadAlumnos);
            while($dato=$loadAlumnos->fetchObject()){
            
                $boton = '<button class="btn btn-secondary editb" onclick="editUs('.$dato->id.', 3)">Editar</button>';
                $boton .= $dato->estado == 1 ? '<button class="btn btn-primary" onclick="editUs('.$dato->id.', 0)">Desactivar</button>': '<button class="btn btn-info" onclick="editUs('.$dato->id.', 1)">Activar</button>';
               
               if($dato->estatus_acceso == 3){
               	$rol = 'Desarrollador de contenido';
               }else{ $rol = 'Administrativo';}
                $data[]=array(
                0=> $dato->nombres,
                1=> $dato->email,
                2=> $rol,
                3=> $boton
                );
                $dataN[]=array(
                    'idPersona'=>$dato->id,
                    'nombres'=> $dato->nombres,
                    'email'=> $dato->email,
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
            $datas = $matsM->setUsers($_POST);
            $resp = ['estatus'=>'ok', 'data'=>$datas['data']];

            echo json_encode($resp);
        break;
        default:
        echo "noaction";
            break;
    }

}else{
	header('Location: ../../../../index.php');
}

function nombreDocumento( $tipo ){
    switch( $tipo ){
        case 1: return "Formato de inscripción";
        case 2: return "Acta de nacimiento";
        case 3: return "CURP";
        case 4: return "Comprobante de estudios";
        case 5: return "Foto Óvalo";
        case 6: return "Foto Intantil";
        case 7: return "Identificación Anverso";
        case 8: return "Identificación Reverso";
        case 9: return "Carta de motivos";
        case 10: return "Comprobante de pago de inscripción";
        case 11: return "Comprobante de domicilio";
        default: return "";
    }//fin switch
}//nombreDocumento

function cargar_documento($input, $nombre, $ruta){
    if($ruta != 'recursos'){
        $ruta = 'apoyos';
    }
    $tmp_name = $input["tmp_name"];
    $uploads_dir = "../../../files/clases/".$ruta;
    $fileT = explode(".", $input["name"]);
    $fileT = $fileT[sizeof($fileT)-1];
    
    $nName = str_replace(" ","_",$nombre).".".$fileT;
    $statFile = move_uploaded_file($tmp_name, "$uploads_dir/$nName");
    return $statFile ? $nName : '';
}
?>
