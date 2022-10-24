<?php
    header('Access-Control-Allow-Origin: https://conacon.org', false);
    session_start();
    if (isset($_POST["action"])) {
        date_default_timezone_set("America/Mexico_City");
        require_once '../../Model/conexion/conexion.php';
        require_once '../../Model/certificaciones/certificacionesModel.php';

        if(!isset($_SESSION['usuario']) && !isset($_POST['listas']) ){
            $_POST['action'] = 'no_session';
        }

        $certi = new Certificaciones();
        
        switch($_POST['action']){
            case 'NuevaFecha':
                unset($_POST['action']);
                if(isset($_POST['FirmanteSeleccionado']) && $_POST['FirmanteSeleccionado'] == "on"){
                    $_POST['FirmanteSeleccionado'] = "CONDE PÉREZ MARCO ANTONIO"; 
                    $NuevaFecha = $certi->NuevaFecha($_POST);
                }else{
                    $NuevaFecha = ['estatus'=>'error aun no selecciona un firmante'];
                }
                echo json_encode($NuevaFecha);
                break;

            case 'EditarFecha':
                unset($_POST['action']);
                if(isset($_POST['FirmanteSeleccionadoEdit']) && $_POST['FirmanteSeleccionadoEdit'] == "on"){
                    $_POST['FirmanteSeleccionadoEdit'] = "CONDE PÉREZ MARCO ANTONIO"; 
                    $NuevaFecha = $certi->EditarFecha($_POST);
                }else{
                    $NuevaFecha = ['estatus'=>'error aun no selecciona un firmante'];
                }
                echo json_encode($NuevaFecha);
                break;

            case 'EliminarFechaExpedicion':
                unset($_POST['action']);
                $EliminarFecha = $certi->EliminarFechaExpedicion($_POST)['data'];
                echo json_encode($EliminarFecha);
                break;

            case 'buscarClasesCarreraSelect':
                unset($_POST['action']);
                if(!isset($_POST['tabla'])){
                    $_POST['tabla']='0';
                }
                $obCarrerasSelect = $certi->BuscarFechas($_POST)['data'];
                echo json_encode($obCarrerasSelect);
                break;
            case 'CambiarEstatusAlumno':
                unset($_POST['action']);
                $QuitarPros = $certi->CambiarEstatusAlumno($_POST);
                echo json_encode($QuitarPros);
                break;

            case 'EliminarXmlAlumno':
                unset($_POST['action']);
                $Direccion = "../../../../controlescolar/archivos/certificaciones/{$_POST['idAl']}.xml";
                $estatus = "error";
                $mensaje = "No se pude eliminar el archivo XML";
                if(file_exists($Direccion)){
                    $success = unlink($Direccion);
                    if($success){
                        $estatus = "ok"; 
                        $mensaje = "XML Borrado Correctamente";
                    }
                }
                $response = ["estatus" => $estatus,"info"=>$mensaje];
                echo json_encode($response);
                break;


            case 'buscarClasesCarrera':
                unset($_POST['action']);
                $obClases = $certi->buscarClasesCarrera($_POST)['data'];
                echo json_encode($obClases);
                break;

            case 'ConsultarCarreras':
                unset($_POST['action']);
                $obCarreras = $certi->BuscarFechas($_POST);
                
                $data = Array();
                $i=0;
                    while($dato=$obCarreras->fetchObject()){
                        $ButtonModificar = "<button class = 'btn btn-success btn-lg' onClick = 'Modificar({$dato->idexpedicion})'> Modificar </button>";
                        $ButtonDarBaja = "<button class = 'btn btn-secondary btn-lg' onClick = 'DarDeBaja({$dato->idexpedicion})'> Quitar </button>";
                        $i++;
                        $data[]=array(
                            0=> $i,
                            1=> '<b>Total</b>'/*$dato->tipo*/,
                            2=> $dato->fecha_expedicion,
                            3=> '<b>MARCO ANTONIO CONDE PÉREZ (RECTOR)</b>',
                            4=> "<div class = 'form-group row'><div class = 'col-md-6 col-sm-12'>$ButtonModificar</div><div class = 'col-md-6 col-sm-12'>$ButtonDarBaja</div></div>",
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
            case 'buscarAlumnosCarrera':
                unset($_POST['action']);
                $obAlumnosCarr = $certi->buscarAlumnosCarrera($_POST);
                //var_dump($obAlumnosCarr);
             
                $data = Array();
                $i=0;
                $LetreroEstatus = "";
                    while($dato=$obAlumnosCarr->fetchObject()){
                        switch($dato->estatus){
                            case '1';
                                $LetreroEstatus = "<small>EL ALUMNO SE ENCUENTRA ACTIVO</small>";
                                break;
                            case '2';
                                $LetreroEstatus = "<small>ALUMNO CON BAJA</small>";
                                break;
                            case '3';
                                $LetreroEstatus = "<small>EGRESADO</small>";
                                break;
                            case '4';
                                $LetreroEstatus = "<small>TITULADO</small>";
                                break;
                            case '5';
                                $LetreroEstatus = "<small>EXPULSADO</small>";
                                break;
                            case '6';
                                $LetreroEstatus = "<small>VALIDADO</small>";
                                $MensajeTabla = "Autorizado por cobranza, ya se puede generar su XML";
                                $Button = "<div class ='row text-center'><button class= 'btn btn-secondary' onClick='Quitar({$dato->idalumno},{$dato->idgeneracion})'> Quitar</button></div>";
                                if(file_exists('../../../../controlescolar/archivos/certificaciones/'.$dato->idalumno.'.xml')){
                                    $LetreroEstatus = "XML Activo";
                                    $MensajeTabla = "<row class = 'text-center'>XML Generado Correctamente </row>";
                                    $Button = "<div class ='row text-center'><button class= 'btn btn-primary' onClick='Eliminar_XML({$dato->idalumno},{$dato->idgeneracion})'> Eliminar XML</button></div>";
                                }

                                $Ciclo_actual = "<br><b>Avence Actual </b><br>{$dato->ciclo_actual}° Ciclo";
                                
                                $NumGen = explode(" ",$dato->nombreGen);

                                $NombreGen = "<br><b>Generación <br></b>{$NumGen[1]}° Generación";

                                $i++;
                                $data[]=array(
                                    0=> $i,
                                    1=> "<b>{$dato->nombre}</b><br>{$LetreroEstatus}{$NombreGen}{$Ciclo_actual}",
                                    2=> $MensajeTabla,
                                    3=> $Button
                                );
                                break;
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

                case 'buscarAlumnosCarreraXML':
                    unset($_POST['action']);
                    $obAlumnosCarr = $certi->buscarAlumnosCarrera($_POST);
                    //var_dump($obAlumnosCarr);
                 
                    $data = Array();
                    $i=0;
                        while($dato=$obAlumnosCarr->fetchObject()){
                            //Armar formulario dinamico
                            $id = $dato->idalumno;
                            $idGen = $dato->idgeneracion;
                            $inputDnone = '<input type = "number" name = "idAlumno" class ="d-none" value = "'.$id.'">';
                            $inputDnoneGen = '<input type = "number" name = "idGeneracion" class ="d-none" value = "'.$idGen.'">';  
                            $inputFolio = '<b>Folio de control: </b> <input type="text" name="Folio" class ="form-control form-group" required>';
                            $ButtonSubmit = ' <button type="submit" class= "mt-3 btn btn-success form-group"> Generar Archivo XML</button>';
                           
                            if($dato->estatus == 6){
                                if(!file_exists('../../../../controlescolar/archivos/certificaciones/'.$dato->idalumno.'.xml')){
                                    $i++;
                                    $data[]=array( 
                                    0=> $i,
                                    1=> "<b>{$dato->nombre}</b>",
                                    2=> '<form onsubmit="EnvioXML(event,this)" = "FormXMLalumno">'.$inputDnone.$inputDnoneGen.' <div class = "row"><div class ="col-md-6">'.$inputFolio.'</div><div class ="col-md-6">'.$ButtonSubmit.'</div></div></form>'
                                    );
                                }
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

                case 'buscarAlumnosCarreraXMLDown':
                    unset($_POST['action']);
                    $FechaSelect = $_POST['fechaSelect'];
                    unset($_POST['fechaSelect']);
                    $obAlumnosCarr = $certi->buscarAlumnosCarrera($_POST);
                    $data = Array();
                    $i=0;
                        while($dato=$obAlumnosCarr->fetchObject()){
                            if(file_exists('../../../../controlescolar/archivos/certificaciones/'.$dato->idalumno.'.xml')){
                                //Lectura y manejo de atributos en js
                                $xml = simplexml_load_file('../../../../controlescolar/archivos/certificaciones/'.$dato->idalumno.'.xml');
                                //fechaSelect
                                $child = 'Expedicion';
                                $att = 'fecha';
                                $arreglo = (array) $xml->children()->$child->attributes()->$att;
                                $fechaXml = $arreglo[0];

                                $child = 'Carrera';
                                $att = 'idCarrera';
                                $arreglo = (array) $xml->children()->$child->attributes()->$att;
                                $Carreraxml = $arreglo[0];

                                if($fechaXml == $FechaSelect && $Carreraxml == $_POST['idCarr']){
                                    $att = 'folioControl';
                                    $arreglo = (array) $xml->attributes()->$att;
                                    $folio = $arreglo[0];
    
                                    $id = $dato->idalumno;
                                    $inputDnone = '<input type = "number" name = "idAlumno" class ="d-none" value = "'.$id.'">';
    
                                    $i++;
                                    $data[]=array(
                                    0=> $i.$inputDnone,
                                    1=> "<b>$dato->nombre</b>",
                                    2=> "XML generado correctamente en espera de descarga",
                                    3=> "$folio",
                                    );
                                }
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

                case 'GenerarXml':
                    //Envio de informacion con consultas repoartidas
                    unset($_POST['action']);

                    $fecha = $_POST['fecha'];
                    unset($_POST['fecha']);
                    
                    $DatosAlumno = $certi->BuscarDatosAlumno($_POST);
                    
                    //Se asigna los datos de Generacion, carrera y plan de estudios
                    if(isset($DatosAlumno['data'])){
                        $_POST['DatosAlumno'] = $DatosAlumno['data'][0];
                       
                        $idplan = $_POST['DatosAlumno']['idPlan'];
                        //var_dump($_POST['idAlumno']);
                        $DatosMaterias = $certi->BuscarDatosMateriasAlumno(['idplan'=>$idplan,'idAlumno'=>$_POST['idAlumno']]);

                        $_POST['DatosMaterias'] = $DatosMaterias['data'];
                    }
                    $_POST['fechaCert'] = $fecha;
                    $GenerarCadenaOriginal = $certi->GenerarCadenaOriginal($_POST);
                    if(isset($GenerarCadenaOriginal['sello']) && $GenerarCadenaOriginal['sello']){
                        $_POST['sello'] = $GenerarCadenaOriginal['sello'];
                    }else{
                        $_POST['sello'] = "";
                    }
                    $GenerarXml = $certi->GenerarXml($_POST);
                    echo json_encode($GenerarXml);
                    break;

                case 'CrearZipDeXML':
                    unset($_POST['action']);
                    $GenerarZip = $certi->GenerarZip($_POST);
                    echo json_encode($GenerarZip);
                    break;
                   
            default:
                echo "noaction";
                break;
            
        }
    }
?>
