<?php
/*$meses = ["Enero","Febrero","Marzo","Abril","Mayo","Jun.","Jul.","Agosto","Sept.","Oct.","Nov.","Dic."];
$detalles = null;

$solicitud = "amor-con-amor-se-paga";


require '../../assets/data/Model/conexion/conexion.php';
require '../../assets/data/Model/eventos/eventosModel.php';
require '../../assets/data/Controller/eventos/initControler.php';
require '../../assets/data/Model/institucion/institucionModel.php';
  
  $info = getDataEvento($solicitud)['data'];
  
$nextTuesday = strtotime('next thursday');
  $nextTuesday = date("Y-m-d", $nextTuesday);

  if(sizeof($info) > 0){
    $detalles = $info[0];
    $fechaE = explode("-", $nextTuesday);
    $fechaE = $fechaE[2]." de ".$meses[intval($fechaE[1])-1];
  }

$instM = new Institucion();
  $instituciones = $instM->consultarTodoInstituciones();
  $options = "";
  for ($i=0; $i < sizeof($instituciones['data']); $i++) { 
      if($instituciones['data'][$i]['fundacion'] == '1'){
          $options.="<option value='{$instituciones["data"][$i]["id_institucion"]}'>{$instituciones["data"][$i]["nombre"]}</option>";
      }
  }
*/
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
} 

  session_start();
  if (!isset($_SESSION["alumno"])) {
    header('Location: index.php');
    die();
  }
  
  $usr = $_SESSION['alumno'];
	require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $afiliados = new Afiliados();

  $usuario=$afiliados->obtenerusuario($idusuario);
  $Clinicas = $afiliados->obtenerClinica($usuario['data']['correo']);

  

  if($Clinicas["sql"]== "Clinica-Existente"){
      $NombreClinica = $afiliados->obtenerClinicaNombre($Clinicas['data']['id_institucion']);
  }

  /*var_dump($usuario['data']);
  echo "<br><br>";
  var_dump($usuario['data']['correo']);
  echo "<br><br>";
  var_dump($Clinicas);
  echo "<br><br>";
  die();*/
  
  require 'plantilla/header.php';
?>
<!-- ########## START: MAIN PANEL ########## -->
<html>
  
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
          <nav class="breadcrumb pd-0 mg-0 tx-12">
              <a class="breadcrumb-item" href="panel.php">Panel</a>
              <span class="breadcrumb-item active">Registrar Clinica</span>
          </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
          <!--<h3><a href="#" class="text-dark"><u>Click para ingresar.</u></a></h3>-->

          <!--<h4 class="tx-gray-800 mg-b-5">TRAINING 28 de Septiembre  6:00 PM</h4>
          <p class="mg-b-0">Dá click en el link para iniciar tu sesión en Webex</p>-->
    </div>

    <div style="display:none;">
          <?php print_r($_SESSION); ?>
    </div>

      <!-- br-pagebody -->
      <div class="br-pagebody">
        <div class="card m-3">
            <div class="card-body">
                <section id="form-clinica">
                    <div class="border rounded mt-3 p-3">
                        <div class="contact-form-wrap p-0 ">
                            <h4 class="page-title ">CUESTIONARIO PARA AFILIACIÓN A CONACON CENTRO DE TRATAMIENTO</h4>
                            <form class="" id="formRegisterClinicToEvent">
                                <input type="hidden" name="nombre_clave_destino" value="amor-con-amor-se-paga">
                                <div class="form-group ">
                                    <div class="response ">
                                    </div>
                                </div>
                                <div class="col-12">  
                                    <div class="form-group row">
                                        <h6>  Datos del responsable de la clínica.</h6>
                                        <input class="form-control special" type="text" name="idUsuario"  id="idUsuario"placeholder="id" value = "<?php echo $idusuario;?>" readonly style="display:none;" required>
                                        <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                            <input class="form-control special" type="text" name="name_cl"  id="name_cl"placeholder="Nombre" value = "<?php echo $usuario['data']['nombre'];?>" readonly required>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-lg-6">
                                            <input class="form-control special" type="text" name="paterno_cl" id="paterno_cl" placeholder="Apellido Paterno"  value = "<?php echo $usuario['data']['apaterno'];?>" readonly required>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-lg-6">
                                            <input class="form-control special" type="text" name="materno_cl" id="materno_cl" placeholder="Apellido Materno" value = "<?php echo $usuario['data']['amaterno'];?>" readonly required>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-lg-6">
                                            <input class="form-control special" type="email" name="emailResp" id="emailRes" placeholder="Email"  value = "<?php echo $usuario['data']['correo'];?>" readonly required>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-lg-6">
                                            <input class="form-control special" type="tel" name="telefonoResp" id="telefonoRes" placeholder="Teléfono"  value = "<?php echo $usuario['data']['celular'];?>" readonly required maxlength="10">    
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                            <input class="form-control special" type="text" name="Curp" id="Curp" placeholder="CURP"  value = "<?php echo $usuario['data']['curp'];?>" readonly  maxlength="18">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h3><?php $Nombre = isset($NombreClinica['data']['nombre'])? 'Clinica Registrada' : 'Datos de clinica Nueva';  echo $Nombre;?></h3>
                                    <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                        <input class="form-control special" type="text" name="name_clinica_cl" id ="name_clinica_cl" placeholder="Nombre de la clínica o centro" value = "<?php $Nombre = isset($NombreClinica['data']['nombre'])? $NombreClinica['data']['nombre'] : '';  echo $Nombre;?>" <?php if(isset($NombreClinica['data']['nombre'])){echo "readonly";}?> required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-lg-12 d-none" id = "CoincidenciasClinica">
                                        <label for="name_clinica_clselect">¿Su clinica se encuentra aquí?</label>
                                        <select id ="name_clinica_clselect" class="form-control special" type="text">
                                        </select>
                                    </div>
                                </div>
                                <div id = "FormularioCompleto" class="d-none col-md-12">
                                    <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                        <input class="form-control special" type="email" name="email_cl" id="email_cl" placeholder="Email de la clínica" value = "<?php $Correocl = isset($Clinicas['data']['email_contacto'])? $Clinicas['data']['email_contacto'] : '';  echo $Correocl;?>" <?php if(isset($NombreClinica['data']['nombre'])){echo "readonly";}?> required>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                        <input class="form-control onlyNum" type="tel" name="telefono_cl" id="telefono_cl" placeholder="Teléfono de contacto" required maxlength="10"  value = "<?php $Numerocl = isset($Clinicas['data']['telefono_contacto'])? $Clinicas['data']['telefono_contacto'] : '';  echo $Numerocl;?>" <?php if(isset($NombreClinica['data']['nombre'])){echo "readonly";}?>>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                        <select class= "form-control" name="pais_cl" id="pais_cl" required>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                        <select class= "form-control" name="estado_cl" id="estado_cl" required>
                                            <option selected="true" value="null" disabled="disabled">Seleccione el estado en el que se encuentra la clínica</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                        <input class="form-control special" type="text" name="direccion_cl" id="direccion_cl" placeholder="Dirección" required maxlength="200" value = "<?php $DireccionCl = isset($Clinicas['data']['direccion'])? $Clinicas['data']['direccion'] : '';  echo $DireccionCl;?>" <?php if(isset($NombreClinica['data']['nombre'])){echo "readonly";}?>>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                        <input class="form-control special" type="text" name="ciudad_cl" id="ciudad_cl" placeholder="Ciudad" required maxlength="200" value = "<?php $CiudadCl = isset($Clinicas['data']['ciudad'])? $Clinicas['data']['ciudad'] : '';  echo $CiudadCl;?>" <?php if(isset($NombreClinica['data']['nombre'])){echo "readonly";}?>>
                                    </div>
    
                                    <div id = "identifier"  style="<?php if(isset($NombreClinica['data']['nombre'])){echo "display:none;";}?>">
                                        <div class="col-md-12">
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pb-4 mt-2 pl-3">
                                                <label>Atención a:</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col-md-4">
                                                        <input class="form-group form-check-input" type="radio" name="flexRadioDefault" value="1"  checked="true" id="atencionMixto">
                                                        <label class="form-check-label" for="atencionMixto">Mixto</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input class="form-group form-check-input" type="radio" name="flexRadioDefault" value="3" id="atencionHombres">
                                                        <label class="form-check-label" for="atencionHombres">Hombres</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input class="form-group form-check-input" type="radio" name="flexRadioDefault" value="2" id="atencionMujeres">
                                                        <label class="form-check-label" for="atencionMujeres">Mujeres</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                            <input class="form-control onlyNum" type="number" name="capacidad_cl" id="capacidad_cl" placeholder="¿Cuál es la capacidad máxima de pacientes?" required >
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="pacientes12_cl" placeholder="¿Cuantos pacientes han recibido en los últimos 12 meses?" required >
                                            </div>
    
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="pacientesMes_cl" id="pacientesMes_cl" placeholder="¿Cuántos pacientes han recibido en el último mes?" required >
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pb-4 mt-2 pl-3">
                                            <label>Reciben pacientes con:</label>
                                            <div class="form-group row pl-4">
                                                <div class="col">
                                                    <input class="form-check-input MyInput" type="checkbox" name="flexRadioDefaultPacientes1" value="1" id="atencionSustancias">
                                                    <label class="form-check-label" for="atencionSustancias">Problemas de abuso de sustancias</label>
                                                </div>
                                                <div class="col">
                                                    <input class="form-check-input MyInput" type="checkbox" name="flexRadioDefaultPacientes2" value="2" id="atencionAlcohol">
                                                    <label class="form-check-label" for="atencionAlcohol">Problemas de abuso de alcohol</label>
                                                </div>
                                                <div class="col">
                                                    <input class="form-check-input MyInput" type="checkbox" name="flexRadioDefaultPacientes3" value="3" id="atencionOtro">
                                                    <label class="form-check-label" for="atencionOtro">Otro tipo de abuso</label>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
    
                                        <div class="col-md-12">                   
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control special" type="text" name="otroTipo" id="otroTipo" placeholder="¿Cuál?">
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pb-4 mt-2 pl-3">
                                                <label>¿Dentro del Centro se cuenta con habitaciones individuales?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultIndividuales" value="1"  checked="true" id="habitacionesIndividualesSi">
                                                        <label class="form-check-label" for="habitacionesIndividualesSi">Si</label>
                                                    </div>
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultIndividuales" value="2" id="habitacionesIndividualesNo">
                                                        <label class="form-check-label" for="habitacionesIndividualesNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="numeroHabitacionesIndividuales" required id="numeroHabitacionesIndividuales" placeholder="¿Cuántas?">
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Dentro del Centro se cuenta con habitaciones compartidas?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultCompartidas" value="1"  checked="true" id="habitacionesCompartidasSi">
                                                        <label class="form-check-label" for="habitacionesCompartidasSi">Si</label>
                                                    </div>
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultCompartidas" value="2" id="habitacionesCompartidasNo">
                                                        <label class="form-check-label" for="habitacionesCompartidasNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="numeroHabitacionesCompartidas" required id="numeroHabitacionesCompartidas" placeholder="¿Cuántas?" >
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="promedioPersonasHabitacion" id="promedioPersonasHabitacion" required placeholder="¿Promedio de personas dentro de la habitación?" >
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Dentro del Centro se cuenta con áreas comunes de descanso y recreación?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultAreasDescanso" value="1"  checked="true" id="areasDescansoSi">
                                                        <label class="form-check-label" for="areasDescansoSi">Si</label>
                                                    </div>
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultAreasDescanso" value="3" id="areasDescansoNo">
                                                        <label class="form-check-label" for="areasDescansoNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="numeroareasDescanso" id="numeroareasDescanso" placeholder="¿Cuántas?" required>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Dentro del Centro se realizan sesiones terapéuticas en grupo?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasGrupo" value="1"  checked="" id="TerapiasGrupoSi">
                                                        <label class="form-check-label" for="TerapiasGrupoSi">Si</label>
                                                    </div>
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasGrupo" value="3" id="TerapiasGrupoNo">
                                                        <label class="form-check-label" for="TerapiasGrupoNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Dentro del Centro se realizan sesiones terapéuticas individuales?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasIndividuales" value="1"  checked="true" id="TerapiasIndividualesSi">
                                                        <label class="form-check-label" for="TerapiasIndividualesSi">Si</label>
                                                    </div>
    
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTerapiasIndividuales" value="2" id="TerapiasIndividualesNo">
                                                        <label class="form-check-label" for="TerapiasIndividualesNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Se cuenta con los servicios de medicina general?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultMedicina" value="1"  checked="true" id="medicinasi">
                                                        <label class="form-check-label" for="medicinasi">Si</label>
                                                    </div>
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultMedicina" value="2" id="medicinano">
                                                        <label class="form-check-label" for="medicinano">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Se cuenta con los servicios de psiquiatría?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsiquiatria" value="1"  checked="true" id="PsiquiatriaSi">
                                                        <label class="form-check-label" for="PsiquiatriaSi">Si</label>
                                                    </div>
                                                    <div class="col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsiquiatria" value="2" id="PsiquiatriaNo">
                                                        <label class="form-check-label" for="PsiquiatriaNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Se cuenta con los servicios de psicología?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsicologia" value="1"  checked="true" id="psicologiaSi">
                                                        <label class="form-check-label" for="psicologiaSi">Si</label>
                                                    </div>
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPsicologia" value="2" id="psicologiaNo">
                                                        <label class="form-check-label" for="psicologiaNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Se cuenta con los servicios de enfermería?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultEnfermeria" value="1"  checked="true" id="enfermeriaSi">
                                                        <label class="form-check-label" for="enfermeriaSi">Si</label>
                                                    </div>
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultEnfermeria" value="3" id="enfermeriaNo">
                                                        <label class="form-check-label" for="enfermeriaNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿La duración del tratamiento varía según el caso individual de cada persona?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTratamiento" value="1"  checked="true" id="tratamientosi">
                                                        <label class="form-check-label" for="tratamientosi">Si</label>
                                                    </div>
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultTratamiento" value="3" id="tratamientono">
                                                        <label class="form-check-label" for="tratamientono">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Se cuenta con un período mínimo de tratamiento intensivo?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMin" value="1"  checked="true" id="periodoMinsi">
                                                        <label class="form-check-label" for="periodoMinsi">Si</label>
                                                    </div>
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMin" value="3" id="periodoMinno">
                                                        <label class="form-check-label" for="periodoMinno">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="tiempoduraMin" id="tiempoduraMin" required placeholder="¿Cuánto tiempo dura? (*En meses)" >
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Se cuenta con un período máximo de tratamiento intensivo?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMax" value="1"  checked="true" id="periodoMaxsi">
                                                        <label class="form-check-label" for="periodoMaxsi">Si</label>
                                                    </div>
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultPeriodoMax" value="3" id="periodoMaxno">
                                                        <label class="form-check-label" for="periodoMaxno">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="tiempoduraMax" id="tiempoduraMax" required placeholder="¿Cuánto tiempo dura? (*En meses)"  >
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Los pacientes permanecen apartados del exterior las 24 horas del día durante varios meses?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultExt" value="1"  checked="true" id="tiempoduraMaxsi">
                                                        <label class="form-check-label" for="tiempoduraMaxsi">Si</label>
                                                    </div>
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultExt" value="3" id="tiempoduraMaxno">
                                                        <label class="form-check-label" for="tiempoduraMaxno">No</label>
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <input class="form-control onlyNum" type="number" name="cantmeses" id="cantmeses" placeholder="¿Cuántos meses?" >
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12 pl-3 pb-4 mt-2 ">
                                                <label>¿Se cuenta con disponibilidad para visita por parte de familiares durante ese periodo?</label>
                                                <div class="form-group row pl-4">
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultVisit" value="1"  checked="true" id="VisitSi">
                                                        <label class="form-check-label" for="VisitSi">Si</label>
                                                    </div>
                                                    <div class="form-group col">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefaultVisit" value="3" id="VisitNo">
                                                        <label class="form-check-label" for="VisitNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-12 col-sm-12 col-lg-12">
                                                <p class="mt-30">*Regístrate para más información</p>    
                                                <button class="btn btn-primary" id="submit" type="submit">Regístrate<span></span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $("#name_clinica_clselect").on("change",function(e){
            console.log("Cambios");
            swal({
                title: '¡Clinica registrada anteriormente!',
                icon: 'error',
                text: 'No es necesario registrarla nuevamente o si lo requiere puede ingresar un nombre de clinica distinto'
            }).then(()=>{
                $("#formRegisterClinicToEvent")[0].reset();
                $("#CoincidenciasClinica").addClass("d-none");
                $("#FormularioCompleto").addClass("d-none");
                // $("#name_clinica_clselect").empty();
            });
        });
        
        // $('#name_clinica_cl').bind('mouseover', function(){
        //     $(this).attr('multiple','multiple').
        //     attr('size', $(this).length);   
        // }).bind('mouseout', function(){
        //     $(this).removeAttr('multiple size');
        // });

        $('#name_clinica_cl').on("input",function(e){
            var searching = $(this).val();
            $.ajax({
                type: "POST",
                url: ".../../../../assets/data/Controller/instituciones/institucionesControl.php",
                data: {action:'busqueda_clinica',
                        search: searching},
                dataType: 'JSON',
                success: function (response) {
                    try{
                        if(response.length != 0){
                            $("#CoincidenciasClinica").removeClass("d-none");
                            $("#name_clinica_clselect").empty();
                        }else{
                            $("#CoincidenciasClinica").addClass("d-none");
                            $("#FormularioCompleto").addClass("d-none");
                            $("#name_clinica_clselect").empty();
                        }
                        // var coinc = JSON.parse(response);
                        $("#name_clinica_clselect").html('<option selected="true" value="" disabled="disabled">Seleccione su clinica</option>');
                        $.each(response, function(key, registro){
                            $("#name_clinica_clselect").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
                        });
                        $('#name_clinica_clselect').attr('size',$('#name_clinica_clselect option').length);
                    }catch(e){
                        console.log(e);
                        console.log(response);
                    }
                }
            });
        });

        var el = document.getElementById("name_clinica_cl");
        el.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                var searching = $("#name_clinica_cl").val();
                var bandera = $("#name_clinica_cl").prop("readonly");

                $.ajax({
                    type: "POST",
                    url: "../../assets/data/Controller/instituciones/institucionesControl.php",
                    data: {action:'busqueda_clinicaCompleta',
                            search: searching},
                    dataType: 'JSON',
                    success: function (response) {
                        try{
                            if(response.length != 0){
                                if(!bandera){
                                    swal({
                                        title: '¡El nombre de clinica ya esta registrado!',
                                        icon:'error'
                                    }).then(()=>{
                                        $("#formRegisterClinicToEvent")[0].reset();
                                        $("#FormularioCompleto").addClass("d-none");
                                        $("#CoincidenciasClinica").addClass("d-none");
                                        $("#name_clinica_clselect").empty();
                                    });
                                }
                            }else{
                                $("#FormularioCompleto").removeClass("d-none");
                                $("#CoincidenciasClinica").addClass("d-none");
                                // $("#name_clinica_clselect").empty();
                            }
                        }catch(e){
                            console.log(e);
                            console.log(response);
                        }
                    }
                });
            }
        });

        el.addEventListener("focusout", function(event) {
            if($("#name_clinica_cl").val().length != 0){
                var searching = $("#name_clinica_cl").val();
                var bandera = $("#name_clinica_cl").prop("readonly");
                $.ajax({
                    type: "POST",
                    url: "../../assets/data/Controller/instituciones/institucionesControl.php",
                    data: {action:'busqueda_clinicaCompleta',
                            search: searching},
                    dataType: 'JSON',
                    success: function (response) {
                        try{
                            if(response.length != 0){
                                if(!bandera){
                                    swal({
                                        title: 'El nombre de clinica ya esta registrado!',
                                        icon:'error'
                                    }).then(()=>{
                                        $("#formRegisterClinicToEvent")[0].reset();
                                        $("#FormularioCompleto").addClass("d-none");
                                        $("#CoincidenciasClinica").addClass("d-none");
                                        $("#name_clinica_clselect").empty();
                                    });
                                }
                            }else{
                                $("#FormularioCompleto").removeClass("d-none");
                                // $("#CoincidenciasClinica").addClass("d-none");
                                // $("#name_clinica_clselect").empty();
                            }
                        }catch(e){
                            console.log(e);
                            console.log(response);
                        }
                    }
                });
            }
        });

        $('input[name=flexRadioDefaultPacientes]').on('click',function(){
            if($(this).attr('id') == 'atencionOtro'){
                $('#otroTipo').attr('required','required');
            }else{
                $('#otroTipo').removeAttr('required');
            } 
        });

        $('input[name=flexRadioDefaultIndividuales]').on('click',function(){
            if($(this).attr('id') == 'habitacionesIndividualesNo'){
                $('#numeroHabitacionesIndividuales').removeAttr('required');
            }else{
                $('#numeroHabitacionesIndividuales').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultCompartidas]').on('click',function(){
            if($(this).attr('id') == 'habitacionesCompartidasNo'){
                $('#numeroHabitacionesCompartidas').removeAttr('required');
                $('#promedioPersonasHabitacion').removeAttr('required');
            }else{
                $('#numeroHabitacionesCompartidas').attr('required','required');
                $('#promedioPersonasHabitacion').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultAreasDescanso]').on('click',function(){
            if($(this).attr('id') == 'areasDescansoNo'){
                $('#numeroareasDescanso').removeAttr('required');
            }else{
                $('#numeroareasDescanso').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultMedicina]').on('click',function(){
            if($(this).attr('id') == 'medicinano'){
                $('#numerosemanaMedicina').removeAttr('required');
            }else{
                $('#numerosemanaMedicina').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultPsiquiatria]').on('click',function(){
            if($(this).attr('id') == 'PsiquiatriaNo'){
                $('#numerosemanaPsiq').removeAttr('required');
            }else{
                $('#numerosemanaPsiq').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultPsicologia]').on('click',function(){
            if($(this).attr('id') == 'psicologiaNo'){
                $('#numeroareasPsicologia').removeAttr('required');
            }else{
                $('#numeroareasPsicologia').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultEnfermeria]').on('click',function(){
            if($(this).attr('id') == 'enfermeriaNo'){
                $('#horasemanaEnfe').removeAttr('required');
            }else{
                $('#horasemanaEnfe').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultTratamiento]').on('click',function(){
            if($(this).attr('id') == 'tratamientono'){
                $('#promedioInternados').removeAttr('required');
            }else{
                $('#promedioInternados').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultPeriodoMin]').on('click',function(){
            if($(this).attr('id') == 'periodoMinno'){
                $('#tiempoduraMin').removeAttr('required');
            }else{
                $('#tiempoduraMin').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultPeriodoMax]').on('click',function(){
            if($(this).attr('id') == 'periodoMaxno'){
                $('#tiempoduraMax').removeAttr('required');
            }else{
                $('#tiempoduraMax').attr('required','required');
            }
            
        });
        $('input[name=flexRadioDefaultExt]').on('click',function(){
            if($(this).attr('id') == 'tiempoduraMaxno'){
                $('#cantmeses').removeAttr('required');
            }else{
                $('#cantmeses').attr('required','required');
            }
            
        });
        $('input[name=flexRadioDefaultSentido]').on('click',function(){
            if($(this).attr('id') == 'Sentidono'){
                $('#tipoAct').removeAttr('required');
            }else{
                $('#tipoAct').attr('required','required');
            }
            
        });
        $('input[name=flexRadioDefaultCarac]').on('click',function(){
            if($(this).attr('id') == 'Caracno'){
                $('#example').removeAttr('required');
            }else{
                $('#example').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultRepertorio]').on('click',function(){
            if($(this).attr('id') == 'repertoriono'){
                $('#cualrep').removeAttr('required');
            }else{
                $('#cualrep').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultComun]').on('click',function(){
            if($(this).attr('id') == 'comunno'){
                $('#denominadorSesion').removeAttr('required');
            }else{
                $('#denominadorSesion').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultIntervencion]').on('click',function(){
            if($(this).attr('id') == 'intervencionNo'){
                $('#whointerv').removeAttr('required');
            }else{
                $('#whointerv').attr('required','required');
            }
            
        });

        $('input[name=flexRadioDefaultTerap]').on('click',function(){
            if($(this).attr('id') == 'Terapno'){
                $('#whotera').removeAttr('required');
            }else{
                $('#whotera').attr('required','required');
            }
            
        });

        $("#formRegisterClinicToEvent").on("submit", function(e){
            e.preventDefault();
            fData = new FormData(this);
            fData.append("action","registrar_clinica");
            
            $.ajax({
                url: '../../assets/data/Controller/instituciones/institucionesControl.php',
                type: "POST",
                data: fData,
                contentType: false,
                processData:false,
                beforeSend : function(){
                    $("#formRegisterClinicToEvent button[type='submit']").attr("disabled",true);
                },
                success: function(data){
                    console.log(data);
                    try{
                        var insert = JSON.parse(data);
                        if (insert.estatus == "ok") {
                            swal({
                                title: 'Registro exitoso!',
                                icon:'success'
                            }).then(()=>{
                                create_prosp = {
                                action:'registrar_prospecto',
                                email : $("#emailRes").val(),
                                name : $("#name_cl").val(),
                                paterno : $("#paterno_cl").val(),
                                materno : $("#materno_cl").val(),
                                telefono : $("#telefonoRes").val(),
                                tipo_prospecto : 'evento',
                                id_destino : 39,
                                IDOrganizacion :insert.data
                            };
                                $.ajax({
                                    url: '../../assets/data/Controller/prospectos/prospectoControl.php',
                                    type: "POST",
                                    data: create_prosp,
                                    success: function(data){
                                        console.log(data);
                                    }
                                });
                                window.location.reload()
                            })
                                        }else{
                                            swal({
                                                text: insert.info,
                                                icon: 'info'
                                            }).then(()=>{
                                                window.location.reload()
                                            })
                                        }
                        }catch(e){
                            console.log(e);
                            console.log(data);
                        }
                    },
                error: function(){
                },
                complete: function(){
                    $("#formRegisterClinicToEvent")[0].reset();
                    $("#formRegisterClinicToEvent button[type='submit']").attr("disabled",false);
                }
            })
        });
    </script>
    </div>   
    <?php require 'plantilla/footer.php'; ?>
</div>

  <!-- modals -->
  <!-- end modals -->

  <!-- ########## END: MAIN PANEL ########## -->

  <script src="../lib/jquery/jquery.js"></script>
  <script src="../lib/popper.js/popper.js"></script>
  <script src="../lib/bootstrap/bootstrap.js"></script>
  <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../lib/moment/moment.js"></script>
  <script src="../lib/jquery-ui/jquery-ui.js"></script>
  <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
  <script src="../lib/peity/jquery.peity.js"></script>
  <script src="../lib/highlightjs/highlight.pack.js"></script>
  <script src="../lib/jquery.steps/jquery.steps.js"></script>
  <script src="../lib/parsleyjs/parsley.js"></script>

  <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <!-- <script src="../../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script> -->

  <script src="script/clases.js"></script>

  <script src="../js/bracket.js"></script>
  <script src="../js/sweetalert.min.js"></script>


  <!--<script src=".././assets/js/template/app.js"></script>-->
  <script src="script/gestion_alumno1.js"></script>
  <script src="script/gestion_alumno2.js"></script>
  <script>
    let institu = null;
    $(document).ready(function() {
        cargarPaises();
        cargar_cursos_pagos();
        institu = <?php echo (isset($usuario['data']['institucion']['id_institucion']) && !empty($usuario['data']['institucion'])) ?  $usuario['data']['institucion']['id_institucion'] : -1; ?>;
        if(institu != -1){
            cargar_referidos(institu);
        }
    });
    $(".onlyNum").on('keypress',function(evt){
        if (evt.which < 46 || evt.which > 57){
            evt.preventDefault();
        }
    })

    function cargarPaises(){
        $.ajax({
            url: '../../assets/data/Controller/controlescolar/crearCarrerasControl.php',
            type: 'POST',
            data: {
                action: "cargarPaisesDirectorio"
            },
            dataType: 'JSON',
            success: function (data) {
                $("#pais_cl").html('<option selected="true" value="" disabled="disabled">Seleccione el país</option>');
                $.each(data, function (key, registro) {
                    $("#pais_cl").append('<option value=' + registro.IDPais + '>' + registro.Pais + '</option>');
                });
            },
            error: function(data){
                console.log(data.error());
            }
        });
    }

    $("#pais_cl").on('change', function () {
        $("#estado_cl").empty();
        idPais = $("#pais_cl").val();
        $.ajax({
            url: '../../assets/data/Controller/controlescolar/crearCarrerasControl.php',
            type: 'POST',
            data: {
                action: "cargarEstadosDirectorio",
                idPais: idPais
            },
            dataType: 'JSON',
            success: function (data) {
                $("#estado_cl").html('<option selected="true" value="null" disabled="disabled">Seleccione el estado en el que se encuentra la clínica</option>');
                $.each(data, function (key, registro) {
                    $("#estado_cl").prop('disabled', false);
                    $("#estado_cl").append('<option value =' + registro.IDEstado + '>' + registro.Estado + '</option>');
                });
                if (data == '') {
                    swal({
                        title: 'País sin estados',
                        icon: 'info',
                        text: 'Selecciona otro país, si es el caso.',
                        button: false,
                        timer: 3000,
                    });
                    $("#estado_cl").val("null");
                }
            }
        });
    });
  </script>

  </body>

</html>
