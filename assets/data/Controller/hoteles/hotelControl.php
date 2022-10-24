<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/hoteles/hotelModel.php';

	require_once '../../Model/alumnos/alumnoModel.php';

    $htl = new Hotel();
    if(!isset($_SESSION['usuario']) && !isset($_SESSION['alumno']) && !isset($_POST["android_id_afiliado"])){
        $_POST['action'] = 'no_session';
    }

    switch($_POST['action']){

        case 'cargarCortesiasTab':
            unset($_POST["action"]);

            $loadHtls = $htl->cargarCotesias($_POST);
            $data = Array();
            while($dato=$loadHtls->fetchObject()){
                $buttonEditar = "<button class = 'mr-3 btn btn-primary' onClick = 'editarCortesia($dato->idcortesia,`$dato->nombre`,`$dato->informacion`,`$dato->inicio`,`$dato->fin`,`$dato->typecort`)'>Editar Cortesia</button>";
                $buttonAsignar = "<button class = 'btn btn-primary' onClick='AsignarCortesia($dato->idcortesia,`$dato->nombre`)' data-toggle='modal' data-target='#modelAsignarCortesia' href='modelAsignarCortesia'>Asignar Cortesia</button>";
                $Tipocort = "";
                switch($dato->typecort){
                    case 0:
                        $Tipocort = "Hospedaje";
                        break;
                    case 1:
                        $Tipocort = "Transporte";
                        break;
                    case 2:
                        $Tipocort = "Alimentos";
                        break;
                }
                $data[]=array(
                    0=>$dato->nombre,
                    1=>$dato->informacion,
                    2=>$dato->inicio,
                    3=>$dato->fin,
                    4=>"<b>$Tipocort</b>",
                    5=> $_POST["case"] == "queryAll" ? $buttonEditar.$buttonAsignar : "<b> Finalizada </b>" 
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            //var_dump($result);
            echo json_encode($result);
            break;

        case 'cargarCortesias':
            unset($_POST['action']);
            $loadHtls = $htl->cargarCotesias($_POST);
            echo json_encode($loadHtls);
            break;

        case 'cargarCortesiasHospedaje':
            unset($_POST['action']);
            $loadcort = $htl->cargarCortesiasHospedaje();
            echo json_encode($loadcort);
            break;
        
        case 'cargarAlumnosCortesias':
            unset($_POST['action']);
            $loadHtls = $htl->cargarAlumnosCortesias($_POST);

            $data = Array();
            while($dato=$loadHtls->fetchObject()){
                $data[]=array( 
                    0=>$dato->nombre,
                    1=>"<div class'row justify-content-center'><div class='col-md-12'><input onClick='obtenerAsignados({$dato->idalumno})' class = 'form-control' value = {$dato->idalumno} type='checkbox'></div></div>"
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            //var_dump($result);
            echo json_encode($result);
            break;
        case 'AsignarCortesias':
            unset($_POST["action"]);
            if(isset($_POST['carrerasCortesias'])){
                if(isset($_POST['generacionesCortesias'])){
                    //solamente generacion
                    unset($_POST["carrerasCortesias"]);
                    $_POST["tipoA"] = "1";
                    $_POST["idasignado"] = $_POST["generacionesCortesias"]; 
                    unset($_POST["generacionesCortesias"]);

                }else{
                    //solamente la  carrera 
                    $_POST["tipoA"] = "0";
                    $_POST["idasignado"] = $_POST["carrerasCortesias"];
                    unset($_POST["carrerasCortesias"]);
                }
                $loadHtls = $htl->AsignarCortesias($_POST);
            }else{
                $_POST["tipoA"] = "2";
                $arrayid = $_POST['idsAlumnos'];
                unset($_POST['idsAlumnos']);
                foreach($arrayid as $idAlumno){
                    $_POST['idasignado'] = $idAlumno;
                    $loadHtls = $htl->AsignarCortesias($_POST);
                }
            }
            echo json_encode($loadHtls);
            break;


        case 'ConsultarCortesiasDisponibles':
            unset($_POST["action"]);
            $infoAlumno = $htl->ConsultarCarrGenAlumno($_POST['id_afi']);
            $id_in = [];
            foreach($infoAlumno["data"] as $index => $id){
                foreach($id as $indice => $items){
                    $_POST["indice"] = $indice;
                    $_POST["id"] = $items;
                    $consultadecortesias = $htl->ConsultarCortesiasDisponibles($_POST);
                    if(isset($consultadecortesias["data"]) && count($consultadecortesias["data"])>0){
                        $band = 0;
                        foreach($consultadecortesias["data"] as $last){
                            $index_cort = array_search($last['idcortesia'], array_column($id_in, 'idcortesia'));
                            if($index_cort === false){
                                array_push($id_in, $last);
                            }
                        }
                    }
                }
            }
            echo json_encode($id_in);
            break;

        case 'cargarHoteles':
            unset($_POST['action']);
            $loadHtls = $htl->cargarHoteles()['data'];
            echo json_encode($loadHtls);
            break;
        
        case 'consultarAlimentos':
            unset($_POST['action']);
            $loadA = $htl->consultarAlimentos();
            $data = Array();
            while($dato=$loadA->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->apaterno,
                2=> $dato->amaterno,
                3=> $dato->comida == '-1' ? 'No' : 'Si',
                4=> $dato->cena == '-1' ? 'No' : 'Si',
                5=>/*'<a class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Modificar</a>', */`<button class="btn btn-primary" data-toggle="modal" data-target="#modalModificarAlimentos" onclick="buscarAlimentos({$dato->id_usuario})">Modificar</button>`,
                /*6=>'<button class="btn btn-danger">Eliminar</button>' '<button class="btn btn-danger" onclick="validarEliminar('.$dato->id_usuario.')">Eliminar</button>'*/
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
        
         case 'buscarAlimentos':
            unset($_POST['action']);
            $busAli = $htl->buscarAlimentos($_POST);
            echo json_encode($busAli);
            break;

        case 'modificarAlimentos':
            unset($_POST['action']);
            $modAli = $htl->modificarAlimentos($_POST);
            echo json_encode($modAli);
            break;

        case 'eliminarAlimentos':
            unset($_POST['action']);
            $delAli = $htl->eliminarAlimentos($_POST);
            echo json_encode($delAli);
            break;
        
        case 'consultarHotel':
            unset($_POST['action']);
            $loadHtl = $htl->consultarHotel();
            $data = Array();
            while($dato=$loadHtl->fetchObject()){
                $data[]=array(
                0=> $dato->apaterno,
                1=> $dato->amaterno,
                2=> $dato->nombre,
                3=> '+',
                4=> $dato->apaternoComp,
                5=> $dato->amaternoComp,
                6=> $dato->nombreComp,
                7=> $dato->nombreH,
                8=> $dato->habitacion,
                9=>/*'<button class="btn btn-primary" data-toggle="modal" data-target="#modalListaHotel">Modificar</button>',*/"<button class='btn btn-primary' data-toggle='modal' data-target='#modalModAsig' onclick='modificarAsignacion({$dato->id_usuario},{$dato->id_companiero},{$dato->idcortesia})'>Modificar</button>"."<button class = 'ml-3 btn btn-primary' onclick='validarEliminarUsu({$dato->id_usuario},{$dato->id_companiero},{$dato->idcortesia})'>Eliminar</button>",
                /*'<button class="btn btn-danger">Eliminar</button>'*/
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
        
        case 'consultarEsperaHotel':
            unset($_POST['action']);
            $loadEspHtl = $htl->consultarEsperaHotel();
            $data = Array();
            while($dato=$loadEspHtl->fetchObject()){
                $data[]=array(
                0=> $dato->apaterno,
                1=> $dato->amaterno,
                2=> $dato->nombre,
                3=> '+',
                4=> $dato->apaternoComp,
                5=> $dato->amaternoComp,
                6=> $dato->nombreComp,
                7=>"<button class = 'btn btn-primary' data-toggle = 'modal' data-target='#modalEsperaHotel' onclick = 'asignarUsuarios({$dato->id_usuario},{$dato->id_companiero},{$dato->idcortesia})'>Asignar</button>",
                8=>"<button class = 'btn btn-secondary' onclick = 'validarEliminarComp({$dato->id_usuario},{$dato->id_companiero},{$dato->idcortesia})'>Eliminar</button>"
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
        
        case 'obtenerUsuarios':
            unset($_POST['action']);
            if(!isset($_POST['idComp'])){
                $_POST['idComp'] = NULL; 
            }

            $obUsers = $htl->obtenerUsuarios($_POST);
            echo json_encode($obUsers);
            break;

        case 'asignarHotel':
            unset($_POST['action']);
            $asigHtl = $htl->asignarHotel($_POST);
			  if($asigHtl["estatus"] == "ok"){
                $destinatarios = $htl->correoPorClave(["idAsignarUsu"=>$_POST["idAsignarUsu"]])["data"];

                foreach($destinatarios as $mail){
                    //  var_dump($mail);
                      $Dest = $destinatarios[0]['nombre'];
                      //var_dump($destinatarios, $mail, $Nodest, $Dest);
                      $mensaje = 'Se te ha asignado exitosamente una habitación y hotel, puedes revisar más detalles en tu panel de la plataforma MONI.';
                      $testDest = [];
                      //$testDest['correo']= 'crystallfox@hotmail.es';
                      //$testDest['nombre']= 'Angel Coba Tenorio';
                      $testDest['correo']= $mail['correo'];
                      $testDest['nombre']= $mail['nombre'];
                      $htl->enviar_notificacion($testDest,$mensaje,'Confirmación de hotel y habitación');
                  }
            }
			//aqui se reciben los correos y nombres de los destinatarios
            //var_dump($destinatarios);
            echo json_encode($asigHtl);
            break;
        
        case 'obtenerAsignacion':
            unset($_POST['action']);
            $obAsig = $htl->obtenerUsuarios($_POST);
            echo json_encode($obAsig);
            break;
        
        case 'modAsignarHotel':
            unset($_POST['action']);
            $modAsigHtl = $htl->modAsignarHotel($_POST);
            echo json_encode($modAsigHtl);
            break;

        case 'consultarCanjeoAlim':
            $loadA = $htl->consultarAlimentos();
            $data = Array();
            while($dato=$loadA->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->apaterno,
                2=> $dato->amaterno,
                3=> $dato->comida === '2' ? 'Validado' : ($dato->comida == '-1' ? 'No solícito' : "<button class = 'btn btn-primary' onClick ='canjearAlimentos({$dato->idreservacion})'>Validar</button>"),
                4=> $dato->cena === '2' ? 'Validado' : ($dato->cena == '-1' ? 'No solícito' : "<button class = 'btn btn-primary' onClick = 'canjearCena({$dato->idreservacion})'>Validar</button>")
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
        
        case 'consultarEsperaTransporte':
            unset($_POST['action']);
            $loadTransporte = $htl->consultarEsperaTransporte();
            $data = Array();
            while($dato=$loadTransporte->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->apaterno,
                2=> $dato->amaterno,
                3=>/*'<button class="btn btn-primary" data-toggle="modal" data-target="#modalEsperaTransporte">Asignar</button>'*/"<button class = 'btn btn-primary' data-toggle='modal' data-target='#modalEsperaTransporte' onclick='asignarTransporte({$dato->id_usuario},{$dato->idcortesia})'>Asignar</button>"
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

        case 'asignarTransporte':
            unset($_POST['action']);
            $asigTransporte = $htl->asignarTransporte($_POST);
			  if($asigTransporte["estatus"] == "ok"){
                $destinatarios = $htl->correoPorClaveTransporte(["idAsignarUsu"=>$_POST["idAsignarUsuT"]])["data"];
                foreach($destinatarios as $mail){
                    //  var_dump($mail);
                      $Dest = $destinatarios[0]['nombre'];
                      //var_dump($destinatarios, $mail, $Nodest, $Dest);
                      $mensaje = 'Se te ha asignado un asiento para el transporte a la Universidad, puedes revisar más detalles en tu panel de la plataforma MONI.';
                      $testDest = [];
                      //$testDest['correo']= 'crystallfox@hotmail.es';
                      //$testDest['nombre']= 'Angel Coba Tenorio';
                      $testDest['correo']= $mail['correo'];
                      $testDest['nombre']= $mail['nombre'];
                      $htl->enviar_notificacion($testDest,$mensaje,'Confirmación de asignación de asiento de transporte');
                  }
            }
            echo json_encode($asigTransporte);
            break;

        case 'consultarTransportes':
            unset($_POST['action']);
            $loadHtl = $htl->consultarTransportes();
            $data = Array();
            while($dato=$loadHtl->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->apaterno,
                2=> $dato->amaterno,
                3=> $dato->NombreTransp,
                4=> $dato->numero_asiento,
                5=>/*'<button class="btn btn-primary" data-toggle="modal" data-target="#modalListaTransporte">Modificar</button>',*/"<button class='btn btn-primary' data-toggle='modal' data-target='#modalModTransporte' onclick='modificarTransporte({$dato->id_usuario},{$dato->idcortesia})'>Modificar</button>"
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

        case 'obtenerTransporte':
            unset($_POST['action']);
            $obTransporte = $htl->obtenerTransporte($_POST['idUsu'],$_POST['idcortesia']);
            echo json_encode($obTransporte);
            break;

        case 'modificarTransporte':
            unset($_POST['action']);
            $modTransporte = $htl->modificarTransporte($_POST);
            echo json_encode($modTransporte);
            break;
        
        case 'consultarFinal':
            unset($_POST['action']);
            $loadA = $htl->consultarFinal();
            $data = Array();
            while($dato=$loadA->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->apaterno,
                2=> $dato->amaterno,
                3=> $dato->nombreH,
                4=> $dato->habitacion,
                5=> $dato->transporte,
                6=> $dato->numero_asiento,
                7=> $dato->comida === '0' ? 'No' : 'Si',
                8=> $dato->cena === '0' ? 'No' : 'Si'
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
        
        case 'consultarGeneral':
            unset($_POST['action']);
            $loadA = $htl->consultarGeneral();
            $data = Array();
            while($dato=$loadA->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->id_hotel == null ? 'NO' : 'SI',
                2=> $dato->nombreHotel == null ? 'SIN ASIGNAR' : $dato->nombreHotel,
                3=> $dato->habitacion == null ? 'SIN ASIGNAR' : $dato->habitacion,
                4=> $dato->transporte != null ? 'SI' : 'NO',
                5=> $dato->nombreTransporte == null ? 'SIN ASIGNAR' : $dato->nombreTransporte,
                6=> $dato->numero_asiento == NULL ? 'SIN ASIGNAR' : $dato->numero_asiento,
                7=> $dato->comida != null ? 'SI' : 'NO',
                8=> $dato->cena != null ? 'SI' : 'NO',
                9=> /*'<button class="btn btn-danger">Eliminar</button>'*/"<button class='btn btn-primary' onclick='validarElim({$dato->id_usuario})'>Eliminar</button>"
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

        case 'eliminarGeneral':
            unset($_POST['action']);
            $del = $htl->eliminarGeneral($_POST);
            echo json_encode($del);
            break;

        case 'eliminarUsuarios':
            unset($_POST['action']);
            $del = $htl->eliminarUsuarios($_POST);
            echo json_encode($del);
            break;

        case 'eliminarCompaniero':
            unset($_POST['action']);
            $delComp = $htl->eliminarCompanieros($_POST);
            echo json_encode($delComp);
            break;

        case 'buscarUsuarios':
            unset($_POST['action']);
            $busUsers = $htl->buscarUsuarios()['data'];
            echo json_encode($busUsers);
            break;

        case 'registrarCompanierosA':
            unset($_POST['action']);
            $cont=0;
            $usu=[];
            $comp=[];
            //$resgAlt = $htl->registrarCompanierosA($_POST);
            foreach($_POST as $campo => $valor){
                if($campo=='idUsuario'){
                    $var1 = $valor;
                    //$usu[$cont]=$valor;
                    //$cont++;
                    //var_dump($cont);
                    //var_dump($usu);
                }else if($campo=='idComp'){
                    $var2 = $valor;
                }

            }
            
            for($i=0; $i < count($var1) ;$i++){
                //var_dump($var1[$i]);
                //var_dump($var2[$i]);
                $resgAlt = $htl->registrarCompanierosA($var1[$i], $var2[$i]);
            }

            //$resgAlt = $htl->registrarCompanierosA(5, 15);
            echo json_encode($resgAlt);

            break;
        case "solictar_match_cobranza":
            unset($_POST["action"]);
            break;
        // comienza fuciones para la vista del alumno
        case 'solicitar_match':
            $resp = [];
            //:::
            if($_POST['solicita'] == 'si'){
                $matricula = false;
                $continuar = true;
                $error_info = "";
                
                if(isset($_POST['matricula']) && trim($_POST['matricula']) != ''){
                    if(isset($_POST["idSolic"]) && isset($_POST["idCompa"])){
                        $solicitud = $htl->repetir_solicitud_match($_POST["idSolic"], $_POST["idCompa"], $_POST["idcortesia"]);
						$destinatarios = [];
                        if($solicitud['data'] == 0){
                            $solicitud = $htl->registrar_solicitud_match($_POST["idSolic"], $_POST["idCompa"],1,$_POST["idSolic"],"idcortesia",$_POST["idcortesia"]);
							if($solicitud["estatus"] == "ok"){
                                $infoDest = $htl->CorreoPorRes(["idreservacion"=>$solicitud["data"]]);
                                $destinatarios[] = $infoDest["data"];
                            }
                        }
                        $solicitud = $htl->repetir_solicitud_match($_POST["idCompa"],$_POST["idSolic"], $_POST["idcortesia"]);
                        if($solicitud['data'] == 0){
                            $solicitud = $htl->registrar_solicitud_match($_POST["idCompa"], $_POST["idSolic"],1,$_POST["idSolic"],"idcortesia",$_POST["idcortesia"]);
							if($solicitud["estatus"] == "ok"){
                                $infoDest = $htl->CorreoPorRes(["idreservacion"=>$solicitud["data"]]);
                                $destinatarios[] = $infoDest["data"];
                            }
                        }
						 //estos es el array de los destinatarios
                        //var_dump($destinatarios);
                        $contador = 0;
                       //var_dump($destinatarios);
                        foreach($destinatarios as $mail){
                          //  var_dump($mail);
                            if($contador == 0){
                                $Nodest = $destinatarios[1]['nombre'];
                                $Dest = $destinatarios[0]['nombre'];
                            }
                            else{
                                $Nodest = $destinatarios[0]['nombre'];
                                $Dest = $destinatarios[1]['nombre'];
                            }
                            //var_dump($destinatarios, $mail, $Nodest, $Dest);
                            $mensaje = 'Se ha aprobado tu solicitud para compañero de habitación con '.$Nodest.'. Puedes revisar los detalles en tu plataforma.';
                            $testDest = [];
                            //$testDest['correo']= 'crystallfox@hotmail.es';
                            //$testDest['nombre']= 'Angel Coba Tenorio';
                            $testDest['correo']= $mail['correo'];
                            $testDest['nombre']= $mail['nombre'];
                            $htl->enviar_notificacion($testDest,$mensaje,'Confirmación de Match de compañeros de habitación');
                            $contador = [$destinatarios];
                        }
                        $resp = $solicitud;
                    }else{
                        $alumnoM = new Alumno();
                        $alumno = $alumnoM->validar_matricula($_POST['matricula']);
                        $alumno = $alumno['data'];
                        // if($alumno && $alumno['matricula'] == $_SESSION['alumno']['matricula']){
                        //     $continuar = false;
                        //     $error_info = 'Matricula inválida.';
                        // }
                        if($alumno){ // si la consulta de matricula arroja resultados se procedera a verificar que el companero ese disponible para recibir solicitudes
                            unset($alumno['contrasenia']);
                            // consultar las solicitudes que ha recibido el compa
                            $compa_solicitado = $htl->consultar_solicitud_match($alumno['id_prospecto'],$_POST["idcortesia"]);
                            // consultar  las solicitudes que ha enviado el compa
                            $compa_solicitudes = $htl->consultar_solicitud_match_realizadas($alumno['id_prospecto'],$_POST["idcortesia"]);
                            
                            for ($i=0; $i < sizeof($compa_solicitado['data']); $i++) { 
                                if(intval($compa_solicitado['data'][$i]['match_comp']) == 1){
                                    $continuar = false; // si el compañero ya a confirmado una solicitud se cancela el proceso
                                    $error_info = "El compañero ya ha confirmado una solicitud de emparejamiento";
                                }
                            }
                            for ($i=0; $i < sizeof($compa_solicitudes['data']); $i++) { 
                                if(intval($compa_solicitudes['data'][$i]['match_comp']) == 1){
                                    $continuar = false; // si el compañero ya a confirmado una solicitud se cancela el proceso
                                    $error_info = "El compañero ya ha reservado con otro alumno";
                                }
                                if(intval($compa_solicitudes['data'][$i]['estatus']) == 2){
                                    $continuar = false; // si el compañero ya a confirmado una solicitud se cancela el proceso
                                    $error_info = "El compañero ha indicado que no tomará reservación de hotel";
                                }
                            }
    
                            if($continuar){
                                // si el compa sigue disponible se registra la solicitud
                                //registrar_solicitud_match('solicitante', 'compañero')
								$destinatarios = [];
                                $solicitud = $htl->repetir_solicitud_match($_SESSION['alumno']['id_prospecto'], $alumno['id_prospecto'], $_POST["idcortesia"]);
                                if($solicitud['data'] == 0){
                                    $solicitud = $htl->registrar_solicitud_match($_SESSION['alumno']['id_prospecto'], $alumno['id_prospecto'],0,"","idcortesia",$_POST["idcortesia"]);
									if($solicitud["estatus"] == "ok"){
                                        $infoDest = $htl->CorreoPorRes(["idreservacion"=>$solicitud["data"]]);
                                        $destinatarios[] = $infoDest["data"];
                                        foreach($destinatarios as $mail){
                                            //  var_dump($mail);
                                              $Dest = $destinatarios[0]['nombre'];
                                              //var_dump($destinatarios, $mail, $Nodest, $Dest);
                                              $mensaje = 'Un estudiante ha solicitado compartir habitación de hotel contigo, puedes revisar esta solicitud en tu panel de la plataforma MONI.';
                                              $testDest = [];
                                              //$testDest['correo']= 'crystallfox@hotmail.es';
                                              //$testDest['nombre']= 'Angel Coba Tenorio';
                                              $testDest['correo']= $mail['correo'];
                                              $testDest['nombre']= $mail['nombre'];
                                              $htl->enviar_notificacion($testDest,$mensaje,'Solicitud de compañero de habitación');
                                          }
                                    }
                                }
                                
                                if($solicitud['estatus'] == 'error'){
                                    $continuar = false;
                                    $error_info = 'Error al crear solicitud';
                                }else{
                                    $resp = $solicitud;
                                }
                            }
                        }
                        // si la consulta de matricula no trae resultados $matricula quedara en false
                        $matricula = $alumno;
                        if($matricula === false || !$continuar){
                            $info = "";
                            $info = !$matricula ? 'La matricula es incorrecta' : $info;
                            $info = !$continuar ? $error_info : $info;
                            $resp = ['estatus'=>'error', 'info'=>$info];
                        }
                    }
                }

            }else{
                $resp = $htl->rechazar_reservacion($_SESSION['alumno']['id_prospecto']);
                // $resp = ['se cancelara la reservación'];
            }
            echo json_encode($resp);
            break;
        case 'canjearAlimentos':
            unset($_POST["action"]);
            $alim = $htl->canjearAlimentos($_POST);
			$destinatarios = [];
            if($alim["estatus"] == "ok"){
                $destinatarios = $htl->CorreoPorRes(["idreservacion"=>$_POST["id"]]);
                // foreach($destinatarios as $mail){
                //     //  var_dump($mail);
                //       $Dest = $destinatarios[0]['nombre'];
                //       //var_dump($destinatarios, $mail, $Nodest, $Dest);
                //       $mensaje = 'Se ha confirmado tu solicitud de comida para tu estadía.';
                //       $testDest = [];
                //       //$testDest['correo']= 'crystallfox@hotmail.es';
                //       //$testDest['nombre']= 'Angel Coba Tenorio';
                //       $testDest['correo']= $mail['correo'];
                //       $testDest['nombre']= $mail['nombre'];
                //       $htl->enviar_notificacion($testDest,$mensaje,'Confirmación de solicitud de comida');
                //   }
            }
            echo json_encode($alim);
            break;

        case 'canjearCena':
            unset($_POST["action"]);
            $alim = $htl->canjearCena($_POST);
			if($alim["estatus"]== "ok"){
                $destinatarios = $htl->CorreoPorRes(["idreservacion"=>$_POST["id"]]);
                // foreach($destinatarios as $mail){
                //     //  var_dump($mail);
                //       $Dest = $destinatarios[0]['nombre'];
                //       //var_dump($destinatarios, $mail, $Nodest, $Dest);
                //       $mensaje = 'Se ha confirmado tu solicitud de cena para tu estadía';
                //       $testDest = [];
                //       //$testDest['correo']= 'crystallfox@hotmail.es';
                //       //$testDest['nombre']= 'Angel Coba Tenorio';
                //       $testDest['correo']= $mail['correo'];
                //       $testDest['nombre']= $mail['nombre'];
                //       $htl->enviar_notificacion($testDest,$mensaje,'Confirmación de solicitud de cena');
                //   }
            }
            echo json_encode($alim);
            break;

        case 'CargarTipoTransporte':
            unset($_POST["action"]);
            $resp = $htl->CargarTipoTransporte(1)["data"];
            echo json_encode($resp);
            break;

        case 'CargarTablaTransporte':
            unset($_POST["action"]);
            $resp = $htl->CargarTipoTransporte(2);
            $data = Array();
            while($dato=$resp->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> "<button class = 'btn btn-primary' onClick = 'updateTransporte({$dato->idtransporte},`{$dato->nombre}`)'> Cambiar Nombre </button>"
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

        case 'AddHotel':
            unset($_POST["action"]);
            $resp = $htl->AddHotel($_POST);
            echo json_encode($resp);
            break;
        
        case 'consultarHoteles':
            unset($_POST["action"]);
            $loadA = $htl->consultarHoteles(0);
            $data = Array();
            while($dato=$loadA->fetchObject()){
                $data[]=array(
                0=> $dato->nombre,
                1=> $dato->direccion,
                2=> "<button class = 'btn btn-primary' onClick = 'updateHotel({$dato->id})'> Editar Hotel </button>"
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

        case 'consultarHotelprs':
            unset($_POST["action"]);
            $loadA = $htl->consultarHoteles($_POST["idHotel"])[0];
            echo json_encode($loadA);
            break;

        case 'AddTransporte':
            unset($_POST["action"]);
            $loadTransporte = $htl->AddTransporte($_POST);
            echo json_encode($loadTransporte);
            break;

        case 'consultar_solicitud_match':
            $resp = [];
            if(isset($_POST["idcortesia"])){
                $resp['enviadas'] = $htl->consultar_solicitud_match_realizadas($_POST['alumno'],$_POST["idcortesia"])['data'];
                $resp['recibidas'] = $htl->consultar_solicitud_match($_POST['alumno'],$_POST["idcortesia"])['data'];
            }
            echo json_encode($resp);
            break;
        case 'aprobar_solicitud_match':
            $resp = [];
            $solicitud_d = $htl->consultar_solicitud_match_id($_POST['solicitud']);
            if(sizeof($solicitud_d['data']) > 0){
                if(intval($solicitud_d['data'][0]['match_comp']) == 0){
                    $resp = $htl->aprobar_solicitud($solicitud_d['data'][0]['id_usuario'], $solicitud_d['data'][0]['id_companiero'],$_POST["idcortesia"]);
                }else{
                    $resp = ['estatus'=>'error', 'info'=>'La solicitud no puede ser aprobada.'];
                }
            }else{
                $resp = ['estatus'=>'error', 'info'=>'No hay detalles de la solicitud'];
            }

            echo json_encode($resp);
            break;
            case 'rechazar_solicitud_match':
                $resp = [];
                $solicitud_d = $htl->consultar_solicitud_match_id($_POST['solicitud']);
                if(sizeof($solicitud_d['data']) > 0){
                    if(intval($solicitud_d['data'][0]['match_comp']) == 0){
                        $resp = $htl->rechazar_solicitud($solicitud_d['data'][0]['id_usuario'], $solicitud_d['data'][0]['id_companiero']);
                    }else{
                        $resp = ['estatus'=>'error', 'info'=>'La solicitud no puede ser rechazada.'];
                    }
                }else{
                    $resp = ['estatus'=>'error', 'info'=>'No hay detalles de la solicitud'];
                }
    
                echo json_encode($resp);
                break;
        ///     vista de gestion de ctransportes
        case 'solicitud_transporte':
            $resp = [];
            $solicita = false;
            $_POST['radio_reserv_transporte'] = "si";
            if($_POST['radio_reserv_transporte'] == 'si'){
                $solicita = true;
            }
            
            $solicitudes_hechas = $htl->consultar_solicitud_match_realizadas($_SESSION['alumno']['id_prospecto'],$_POST["idcortesia"]);
            if(sizeof($solicitudes_hechas['data']) == 0){
                //$crear_reg = $htl->registrar_solicitud_match($_SESSION['alumno']['id_prospecto'], null,null,"","idcortesia",$_POST["idcortesia"]);
                $resp = $htl->solicitud_transporte($_SESSION['alumno']['id_prospecto'], $solicita,$_POST["idcortesia"]);
            }
            
            echo json_encode($resp);
            break;
        case 'solicitud_alimentos':
            $resp = [];
            
            $solicitudes_hechas = $htl->consultar_solicitud_match_realizadas($_SESSION['alumno']['id_prospecto'],$_POST["idcortesia"]);
            if(sizeof($solicitudes_hechas['data']) == 0){
                $comida = (isset($_POST['radio_comida']))? 1 : -1;
                $cena = (isset($_POST['radio_cena']))? 1 : -1;
                //&$crear_reg = $htl->registrar_solicitud_match($_SESSION['alumno']['id_prospecto'], null,null,"","idcortesia",$_POST["idcortesia"]);
                $resp = $htl->solicitud_alimentos($_SESSION['alumno']['id_prospecto'], $comida, $cena, $_POST["idcortesia"]);
            }
            echo json_encode($resp);
            break;
        case 'no_session':
            echo 'no_session';
            break;

        default:
        echo "noaction";
            break;
    }

}else{
	header('Location: ../../../../index.php');
}
?>