<?php 
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
  exit;
}

session_start();
require "data/Model/AfiliadosModel.php";
$afiliados = new Afiliados();
if (isset($_SESSION["alumno"])) {
  $usr = $_SESSION['alumno'];

  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $usuario=$afiliados->obtenerusuario($idusuario);

  $NacionalidadAlumno = "Mexicano";
  if($usuario['data']['paisn'] != 37 && $usuario['data']['paisn'] != 0){
      $NacionalidadAlumno = "Extranjero";
  }
    /* $fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
    $fechaactual= date('Y-m-d');
    $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

    $datetime1 = new DateTime($fechaactual);
    $datetime2 = new DateTime($fechafinmembresia);
    $interval = $datetime1->diff($datetime2);
    $diasrestantes= substr($interval->format('%R%a días'), 1);
    $dias = rtrim($diasrestantes, ' días');
    if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
      header('Location: pagos.php');
    } */
}else{
      header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require 'plantilla/header.php'; ?>
        <!-- ########## START: MAIN PANEL ########## -->
        <div class="br-mainpanel br-profile-page">
            <input type="text" name="IdentificadorAlumno" id="IdentificadorAlumno" class="d-none" value ="<?php echo $idusuario;?>">
            <input type="text" name="IdentificadorNacionalidad" id="IdentificadorNacionalidad" class="d-none" value ="<?php echo $NacionalidadAlumno;?>">
            <div class="card shadow-base bd-0 rounded-0 widget-4">
                <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
                    <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-target="#documentos" href="#documentos" role="tab">Documentos</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-target="#seguimiento" href="#seguimiento" role="tab">Seguimiento</a></li>
                    </ul>
                </div>
            </div>
            <div class="tab-content br-profile-body">
                
                <div class="row tab-pane fade show active" id="documentos" role="tabpanel">
                    <div id = "DocumentosAlumnoNacional" class="card p-3 mt-1 mb-3 text-justify">
                        <h3 class="m-b-30 m-t-0">Documentación</h3>
                        <div class="clave alert alert-warning">
                            <strong>Por favor, presta atención a las indicaciones que se encuentran. <br> Cada archivo no debe sobrepasar los 5MB. <br> Formato de imágenes: <br> * .jpg <br>* .jpeg <br>* .png <br> Formato de documentos: <br> * .pdf <br>
                            </strong>
                        </div>
                        <form id="formDocumentos">
                            <div class="clave alert alert-info">
                                <strong>Identificación.<br>Instrucciones:</strong> Únicamente se aceptará INE.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacionA" class="col-sm-2 control-label text-center">Identificación - Anverso (pdf, jpeg, jpg, png)</label>
                                <div class="col-sm-8">
                                    <input class="form-control inputfile" type="file" name="identificacionA" id="7" accept=".pdf, .jpeg, .jpg, .png" required>
                                </div>
                                <div class="col-md-6 col-xl-1" id="spinnerDoc7" style="display: none;">
                                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                                        <div class="sk-circle">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                        </div>
                                    </div><!-- d-flex -->
                                </div><!-- col-4 -->
                                <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc7" onclick="guardarDocumento(this, 7, <?php echo $idusuario; ?>)">Enviar</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado7" style="display: none;" disabled>Enviado</button>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacionR" class="col-sm-2 control-label text-center">Identificación - Reverso (pdf, jpeg, jpg, png)</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="file" name="identificacionR" id="8" accept=".pdf, .jpeg, .jpg, .png" required>
                                </div>
                                <div class="col-md-6 col-xl-1" id="spinnerDoc8" style="display: none;">
                                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                                        <div class="sk-circle">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                        </div>
                                    </div><!-- d-flex -->
                                </div><!-- col-4 -->
                                <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc8" onclick="guardarDocumento(this, 8, <?php echo $idusuario; ?>)">Enviar</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado8" style="display: none;" disabled>Enviado</button>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacion" class="col-sm-2 control-label text-center">Acta de nacimiento (.pdf, .jpeg, .jpg, .png)</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="file" name="acta" id="2" accept=".pdf, .jpeg, .jpg, .png" required>
                                </div>
                                <div class="col-md-6 col-xl-1" id="spinnerDoc2" style="display: none;">
                                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                                        <div class="sk-circle">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                        </div>
                                    </div><!-- d-flex -->
                                </div><!-- col-4 -->
                                <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc2" onclick="guardarDocumento(this, 2, <?php echo $idusuario; ?>)">Enviar</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado2" style="display: none;" disabled>Enviado</button>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacion" class="col-sm-2 control-label text-center">CURP (pdf, jpeg, jpg, png)</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="file" name="curp" id="3" accept=".pdf, .jpeg, .jpg, .png" required>
                                </div>
                                <div class="col-md-6 col-xl-1" id="spinnerDoc3" style="display: none;">
                                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                                        <div class="sk-circle">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                        </div>
                                    </div><!-- d-flex -->
                                </div><!-- col-4 -->
                                <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc3" onclick="guardarDocumento(this, 3, <?php echo $idusuario; ?>)">Enviar</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado3" style="display: none;" disabled>Enviado</button>
                            </div> 
                            <div class="clave alert alert-info">
                                <strong>Comprobante de estudios.<br>Instrucciones:</strong> El comprobante de estudios que presentes debe ser como <strong> mínimo de nivel 
                                        secundaria, siempre y cuando esté sea tu último grado de estudios.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacion" class="col-sm-2 control-label text-center">Comprobante de estudios (pdf, jpeg, jpg, png)</label>
                                <div class="col-sm-8">
                                
                                	<div class="form-group">
		                            <select class="form-control" name="selGrado" id="4" required>
		                            </select>
                                    	</div>
                                    
	                            <div class="form-group">
	                                <input class="form-control" type="file" name="gradoEstudios" id="gradoEstudios" accept=".pdf, .jpeg, .jpg, .png" disabled>
	                            </div>
                                </div>
                                <div class="col-md-6 col-xl-1" id="spinnerDoc4" style="display: none;">
                                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                                        <div class="sk-circle">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                        </div>
                                    </div><!-- d-flex -->
                                </div><!-- col-4 -->
                                    <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc4" onclick="guardarDocumento(this, 4, <?php echo $idusuario; ?>)">Enviar</button>
                                    <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado4" style="display: none;" disabled>Enviado</button>
                            </div>
                            <div class="clave alert alert-info">
                                <strong>Fotos tamaño ovalo. <br>Instrucciones:</strong> Te informamos que las fotografias que presentes deben de cumplir lo siguiente:<br>
                                * 3 fotografías <br> * Tamaño título <br> * Blanco y negro <br> * Fondo blanco <br> * Con retoque <br>
                                <strong>A continuación, adjunta el archivo digital de tu foto. <br> Mandar foto física a: Carretera Antigua a Xalapa - Coatepec km 5.5 Esquina Mariano Escobedo Coatepec Veracruz.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="fotosOvalo" class="col-sm-2 control-label text-center">Fotos tamaño ovalo (jpeg, jpg, png)</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="file" name="fotoOvalo" id="5" accept=".jpeg, .jpg, .png" required>
                                </div>
                                <div class="col-md-6 col-xl-1" id="spinnerDoc5" style="display: none;">
                                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                                        <div class="sk-circle">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                        </div>
                                    </div><!-- d-flex -->
                                </div><!-- col-4 -->
                                <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc5" onclick="guardarDocumento(this, 5, <?php echo $idusuario; ?>)">Enviar</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado5" style="display: none;" disabled>Enviado</button>
                            </div>
                            <div class="clave alert alert-info">
                                <strong>Fotos tamaño infantil. <br>Instrucciones:</strong> Te informamos que las fotografias que presentes deben de cumplir lo siguiente:<br>
                                * 3 fotografías <br> * Blanco y negro <br> * Fondo blanco <br> * Con retoque <br>
                                <strong>A continuación, adjunta el archivo digital de tu foto. <br> Mandar foto física a: Carretera Antigua a Xalapa - Coatepec km 5.5 Esquina Mariano Escobedo Coatepec Veracruz.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="fotosInfantil" class="col-sm-2 control-label text-center">Fotos tamaño infantil (jpeg, jpg, png)</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="file" name="fotoInfantil" id="6" accept=".jpeg, .jpg, .png" required>
                                </div>
                                <div class="col-md-6 col-xl-1" id="spinnerDoc6" style="display: none;">
                                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                                        <div class="sk-circle">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                        </div>
                                    </div><!-- d-flex -->
                                </div><!-- col-4 -->
                                <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc6" onclick="guardarDocumento(this, 6, <?php echo $idusuario; ?>)">Enviar</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado6" style="display: none;" disabled>Enviado</button>
                            </div>
                            <!--<div class="form-group justify-item-center">
                                <div>
                                    <input type="hidden" name="idUsuario" id="idUsuario" value="">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" id="Enviar" >Enviar</button>
                                    <button type="reset" class="btn btn-secondary waves-effect waves-light" id="reiniciar" >Cancelar</button>
                                </div>
                            </div>-->
                        </form>          
                    </div>

                    <div  id = "DocumentacionAlumnoExtranjero" class="card p-3 mt-1 mb-3 text-justify">
                        <h3 class="m-b-30 m-t-0">Documentación</h3>
                        <div class="clave alert alert-warning">
                            <strong>
                                Por favor, presta atención a las indicaciones que se encuentran. <br> 
                                Cada archivo no debe sobrepasar los 5MB y al guardar el documento debe ser uno por uno al dar clic en el botón "Enviar". <br>
                                Formato de imágenes:<br>
                                * .jpg <br>
                                * .jpeg <br>
                                * .png <br>
                                Formato de documentos: <br>
                                * .pdf <br>
                                * .jpg <br>
                                * .jpeg <br>
                                * .png <br>
							</strong>
                        </div>

                        <form id="formDocumentosExtranjeros">
                            <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    Para dicho trámite deberá ponerse en contacto con la Oficina de Control Escolar para que se le oriente respecto a este trámite.<br>
                                    <strong>
                                        (FUNDAMENTAL).
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="DocMigratorio"><b>Documento Migratorio</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="DocMigratorio" id="DocMigratorio" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonDocMigratorio')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonDocMigratorio" onClick ="RegistarDocumentoEspecificoExt('DocMigratorio')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    <strong>
                                        3 copias
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="Actanaciemnto"><b>Acta de nacimiento Original, Apostillada</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">    
                                    <input class="form-control" type="file" name="Actanaciemnto" id="Actanaciemnto" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonActanaciemnto')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonActanaciemnto" onClick ="RegistarDocumentoEspecificoExt('Actanaciemnto')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    </strong> El comprobante de estudios que presentes debe ser como <strong> mínimo de nivel 
                                    secundaria, siempre y cuando esté sea tu último grado de estudios.</strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="CertEstMedicina"><b>Comprobante de estudíos.</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="CertEstMedicina" id="CertEstMedicina" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonCertEstMedicina')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonCertEstMedicina" onClick ="RegistarDocumentoEspecificoExt('CertEstMedicina')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    <strong>
                                        Instrucciones: - Clara y legible
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="ComprobanteDomicilio"><b>Comprobante de domicilio.</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="ComprobanteDomicilio" id="ComprobanteDomicilio" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonComprobanteDomicilio')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonComprobanteDomicilio" onClick ="RegistarDocumentoEspecificoExt('ComprobanteDomicilio')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div>

                            <!-- <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    (Carrera de medicina) 
                                    <strong>
                                        Apostillado y Original para cotejo – mas 3 copias.
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="CopiaTitulo"><b>Copia de Título de licenciatura</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="CopiaTitulo" id="CopiaTitulo" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonCopiaTitulo')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonCopiaTitulo" onClick ="RegistarDocumentoEspecificoExt('CopiaTitulo')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div> -->

                            <!-- <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    3 copias
                                    <strong>
                                        (En caso de contar con alguna).
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="DiplomaEspecialidad"><b>Diploma de especialidad Apostillado</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="DiplomaEspecialidad" id="DiplomaEspecialidad" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonDiplomaEspecialidad')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonDiplomaEspecialidad" onClick ="RegistarDocumentoEspecificoExt('DiplomaEspecialidad')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div> -->

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    3 copias.
                                    <strong>
                                        Podrá descargarlo desde la página: (https://www.gob.mx/curp).
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="curpEx"><b>CURP (CLAVE UNICA DE REGISTRO DE POBLACIÓN)</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="curpEx" id="curpEx" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtoncurpEx')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtoncurpEx" onClick ="RegistarDocumentoEspecificoExt('curpEx')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div>

                            <!-- <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    <strong>
                                        Este documento usted lo elabora describiendo las razones por los cuales está interesada en cursar la maestría.
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="CartaMotivos"><b>Carta de motivos</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="CartaMotivos" id="CartaMotivos" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonCartaMotivos')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonCartaMotivos" onClick ="RegistarDocumentoEspecificoExt('CartaMotivos')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div> -->

                            <!-- <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    Para este trámite se requiere la documentación anterior y realizar el pago del costo correspondiente. Para este trámite deberá ponerse en contacto con la Oficina de Control Escolar para que se le oriente respecto a este trámite.
                                    <strong>
                                        (FUNDAMENTAL).
                                    </strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="DictamenTecnico"><b>Dictamen Técnico (Revalidación de Estudios).</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="DictamenTecnico" id="DictamenTecnico" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonDictamenTecnico')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonDictamenTecnico" onClick ="RegistarDocumentoEspecificoExt('DictamenTecnico')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div> -->

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    <strong>
                                        (2.5 x 3 cm.)
                                    </strong>
                                    Fondo blanco, papel mate con retoque *.
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="FotosInfantil"><b>6 Fotografías Tamaño infantil B/N.</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="FotosInfantil" id="FotosInfantil" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonFotosInfantil')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonFotosInfantil" onClick ="RegistarDocumentoEspecificoExt('FotosInfantil')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    <strong>
                                        (6 x 9 cm.)
                                    </strong>
                                    Fondo blanco, papel mate con retoque *.
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="FotosTitulo"><b>6 Fotografías Tamaño Ovalo B/N.</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="FotosTitulo" id="FotosTitulo" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonFotosTitulo')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonFotosTitulo" onClick ="RegistarDocumentoEspecificoExt('FotosTitulo')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div>

                            <!-- <div class="form-group row justify-content-center">
                                <div class="col-md-12 clave alert alert-info">
                                    <strong>
                                        (3.5x 5 cm.)
                                    </strong>
                                        Fondo blanco, papel mate con retoque*.
                                </div>
                                <div class="col-md-2 text-center">
                                    <label for="FotosCredencial"><b>12 Fotografías Tamaño credencial ovaladas B/N.</b><br>(pdf, jpeg, jpg, png)</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="file" name="FotosCredencial" id="FotosCredencial" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('ButtonFotosCredencial')">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id = "ButtonFotosCredencial" onClick ="RegistarDocumentoEspecificoExt('FotosCredencial')" class="btn btn-primary" disabled>Enviar</button>
                                </div>
                            </div> -->
                            <button type="submit" class ="btn btn-primary">Guardar Documentos</button>
                        </form>

                        <div class="clave alert alert-info">
                            <br>
                                <b>
                                    Las fotografías le recomendamos tomárselas ya estando en México, para que de ese modo cumplan con el total de especificaciones solicitadas.
                                </b>
                            <br></br>
                            Deberá presentar la documentación en el orden arriba mencionado, con sus 3 fotocopias cada uno, en un sobre de papel manila color amarillo. <br>
                            Todas las fotografías deberán traer escrito su nombre en la parte trasera con lapicero sin remarcar fuertemente para que no se traspase por el frente ya que si quedan marcadas no sirven.
                            <br><strong>DEBEN</strong> ser tomadas completamente de frente, con el rostro serio, la frente y las orejas completamente descubiertas.
                            <br><br><strong>HOMBRES</strong>
                            <li>Vestimenta formal, saco, camisa y corbata lisos, sin estampados.</li>
                            <li>Bigote recortado por arriba del labio superior.</li>
                            <li>Sin barba, lentes ni pupilentes de ningún color.</li>
                            <br><strong>MUJERES</strong>
                            <li>Vestimenta formal: saco sin estampados, blusa de cuello blanco y sin escote.</li>
                            <li>Cabello recogido hacia atrás.</li>
                            <li>Sin adornos</li>
                            <li>Sin lentes ni pupilentes de ningún color</li>
                            <li>Maquillaje discreto</li>
                            <br><strong>Teléfono Control Escolar 228-833-45-81 y 228-833-40-31</strong>
                            <br><strong>Email: controlescolar@universidaddelconde.edu.mx</strong>
                            <br><strong>udcxal@gmail.com</strong>
                            <br>
                        </div>          
                    </div>

                </div>
                <div class="row tab-pane fade show" id="seguimiento" role="tabpanel">
                    <input class="<?php echo $idusuario; ?>" type="hidden" name="id" id="id">
                    <div class="container col-sm-12 col-lg-12">
                        <div class="table-responsive">
                            <div class="card p-3 mt-1 mb-3 text-justify">    
                            <h3 class="m-b-30 m-t-0">Proceso de Seguimiento</h3>
                                <table id="datatable-seguimiento" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Estatus</th>
                                        <th>Validación</th>
                                        <th>Comentario</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                </table>                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require 'plantilla/footer.php'; ?>
        </div>

        <div class="modal fade bs-example-modal-lg" id="modalModify" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content col-sm-12 col-lg-12">
					<div class="modal-header">
						<h4 class="modal-title m-0" id="myLargeModalLabel">Modificar Archivo</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					</div>
					<div class="modal-body">
						<form id="formularioModificar">
                            <div class="form-group row">
                                <!--bien<embed class="col-sm-6 control-label" src="" id="verDoc" frameborder="0" width="700" height="400" style="display: none;" allowfullscreen>-->
                                <div id="my_pdf_viewer">
                                    <div id="canvas_container">
                                        <canvas id="pdf_renderer"></canvas>
                                    </div>   
                                </div>
                                <img class="col-sm-6" src="" name="verImg" id="verImg" alt="Responsive image" style="display: none;">
                                <div class="col-sm-6">
                                    <input type="file" class="form-control" name="modFile" id="modFile" accept=".pdf, .jpeg, .jpg, .png" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="comentario" class="col-sm-3 control-label">Comentario: </label>
                                <div class="col-sm-9">
                                <input type="text" class="form-control" name="comentario" id="comentario" disabled>
                                </div>
                            </div>
                            <div class="form-group">
								<div>
									<input type="hidden" name="idModify" value="<?php echo $idusuario; ?>">
                                    <input type="hidden" name="idDocument" id="idDocument">
                                    
									<button type="submit" name="btnModificar" id="btnModificar" class="btn btn-primary waves-effect waves-light" aria-hidden="true">
										Modificar
									</button>
									<button type="button" id="ocultar" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal" aria-hidden="true">
										Cancelar
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
      
        <!-- ########## END: MAIN PANEL ########## -->
    
    </body>
    <script src="../lib/jquery/jquery.js"></script>
      <script src="../lib/popper.js/popper.js"></script>
      <script src="../lib/bootstrap/bootstrap.js"></script>
      <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
      <script src="../lib/moment/moment.js"></script>
      <script src="../lib/jquery-ui/jquery-ui.js"></script>
      <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
      <script src="../lib/peity/jquery.peity.js"></script>
      <!--<script src="script/qrcode.js"></script>
      <script src="script/qrcode.min.js"></script>-->

    <!--Required datatables js-->
	<script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<!--error<script src="../assets/plugins/datatables/jquery.dataTables.js"></script>-->
	<script src="../../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

	<!--Buttons examples-->
	<script src="../../assets/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="../../assets/plugins/datatables/buttons.bootstrap4.min.js"></script>

	<script src="../../assets/plugins/datatables/jszip.min.js"></script>
	<script src="../../assets/plugins/datatables/pdfmake.min.js"></script>
	<script src="../../assets/plugins/datatables/vfs_fonts.js"></script>
	<script src="../../assets/plugins/datatables/buttons.html5.min.js"></script>
	<script src="../../assets/plugins/datatables/buttons.print.min.js"></script>
	<!--<script src="../assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>-->
	<script src="../../assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
	<script src="../../assets/plugins/datatables/dataTables.keyTable.min.js"></script>
	<script src="../../assets/plugins/datatables/dataTables.scroller.min.js"></script>
    <script src="../js/bracket.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <!--<script src="./sw.js"></script>-->
    <script src="script/documentos.js"></script>

    <!--Responsive examples-->
	<script src="../../assets/plugins/datatables/dataTables.responsive.min.js"></script>
	<!--error<script src="../assets/plugins/datatables/dataTables.responsive.js"></script>-->
	<script src="../../assets/plugins/datatables/responsive.bootstrap.min.js"></script>

	<!--Datatable init js-->
	<script src="../../assets/pages/datatables.init.js"></script>
    <script type="text/javascript">
    $(document).ready(()=>
    habilitarInputs(<?php echo $idusuario ?>)
    )
    </script>
</html>

