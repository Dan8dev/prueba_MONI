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
if (isset($_SESSION["alumno_general"])) {
  $usr = $_SESSION["alumno_general"];

  $idusuario=$_SESSION["alumno_general"]['id_afiliado'];
  $usuario=$afiliados->obtenerusuario($idusuario);
  
}else{
      header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require 'plantilla/header.php'; ?>
        <!-- ########## START: MAIN PANEL ########## -->
        <div class="br-mainpanel br-profile-page">
            <div class="card shadow-base bd-0 rounded-0 widget-4">
                <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
                    <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-target="#documentos" href="#documentos" role="tab">Documentos</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-target="#seguimiento" href="#seguimiento" role="tab">Seguimiento</a></li>
                    </ul>
                </div>
            </div>
            <div class="tab-content br-profile-body">
            <?php if ($dias<31) {
                # code...
                ?>
                <div id="alert-pago-anual" class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?> </strong> 
                </div><!-- alert -->
            <?php } ?>
                <div class="row tab-pane fade show active" id="documentos" role="tabpanel">
                    <div class="card p-3 mt-1 mb-3 text-justify">
                        <h3 class="m-b-30 m-t-0">Documentación</h3>
                        <div class="clave alert alert-warning">
                            <strong>Por favor, presta atención a las indicaciones que se encuentran. <br> Cada archivo no debe sobrepasar los 2MB. <br> Formato de imágenes: <br> * .jpg <br>* .jpeg <br>* .png <br> Formato de documentos: <br> * .pdf <br>
                            </strong>
                        </div>
                        <!--<div class="form-control align-items-center">
                            <select class="form-control" name="selDocumentos" id="selDocumentos">
                            </select>
                            <div class="clave alert alert-warning text-right">
                                <strong>Si algún documento no llegaras a tenerlo puedes desmarcarlo en esta sección desplegable
                                    <i class="fa fa-arrow-up"></i>
                                </strong>
                            </div>
                        </div>-->
                        <form id="formDocumentos">
                            <div class="clave alert alert-info">
                                <strong>Identificación.<br>Instrucciones:</strong> Únicamente se aceptara INE.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacionA" class="col-sm-2 control-label text-center">Identificación - Anverso (pdf)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="identificacionA" id="7" required>
                                    <input type="hidden" name="id_identificacionA" value="7">
                                    <!--<span class="custom-file-control custom-file-control-primary"></span>-->
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacionR" class="col-sm-2 control-label text-center">Identificación - Reverso (pdf)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="identificacionR" id="8" required>
                                    <input type="hidden" name="id_identificacionR" value="8">
                                    <!--<span class="custom-file-control custom-file-control-primary"></span>-->
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacion" class="col-sm-2 control-label text-center">Acta de nacimiento (pdf)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="acta" id="2" required>
                                    <input type="hidden" name="id_acta" value="2">
                                    <!--<span class="custom-file-control custom-file-control-primary"></span>-->
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacion" class="col-sm-2 control-label text-center">CURP (pdf)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="curp" id="3" required>
                                    <input type="hidden" name="id_curp" value="3">
                                    <!--<span class="custom-file-control custom-file-control-primary"></span>-->
                                </div>
                            </div> 
                            <div class="clave alert alert-info">
                                <strong>Comprobante de estudíos.<br>Instrucciones:</strong> El comprobante de estudios que presentes debe ser como <strong> mínimo de nivel 
                                        secundaria, siempre y cuando esté sea tu último grado de estudios.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="identificacion" class="col-sm-2 control-label text-center">Comprobante de estudios (pdf)</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="selGrado" id="4" required>
                                    </select>
                                    <div class="col-sm-12">
                                    <input class="form-control" type="file" name="gradoEstudios" id="gradoEstudios" disabled>
                                    <input type="hidden" name="id_gradoEstudios" value="4">
                                    <!--<span class="custom-file-control custom-file-control-primary"></span>-->
                                </div>
                                </div>
                                
                            </div>
                            <div class="clave alert alert-info">
                                <strong>Fotos tamaño ovalo. <br>Instrucciones:</strong> Te informamos que las fotografias que presentes deben de cumplir lo siguiente:<br>
                                * 3 fotografías <br> * Tamaño título <br> * Blanco y negro <br> * Fondo blanco <br> * Con retoque <br>
                                <strong>A continuación, adjunta el archivo digital de tu foto.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="fotosOvalo" class="col-sm-2 control-label text-center">Fotos tamaño ovalo (jpeg, jpg, png)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="fotoOvalo" id="5" required>
                                    <input type="hidden" name="id_fotoOvalo" value="5">
                                    <!--<span class="custom-file-control custom-file-control-primary"></span>-->
                                </div>
                            </div>
                            <div class="clave alert alert-info">
                                <strong>Fotos tamaño infantil. <br>Instrucciones:</strong> Te informamos que las fotografias que presentes deben de cumplir lo siguiente:<br>
                                * 3 fotografías <br> * Blanco y negro <br> * Fondo blanco <br> * Con retoque <br>
                                <strong>A continuación, adjunta el archivo digital de tu foto.</strong>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label for="fotosInfantil" class="col-sm-2 control-label text-center">Fotos tamaño infantil (jpeg, jpg, png)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="fotoInfantil" id="6" required>
                                    <input type="hidden" name="id_fotoInfantil" value="6">
                                    <!--<span class="custom-file-control custom-file-control-primary"></span>-->
                                </div>
                            </div>
                            <div class="form-group justify-item-center">
                                <div>
                                    <input type="hidden" name="idUsuario" value="<?php echo $idusuario; ?>">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" id="Enviar" >Enviar</button>
                                    <button type="reset" class="btn btn-secondary waves-effect waves-light" id="reiniciar" >Cancelar</button>
                                </div>
                            </div>
                        </form>          
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
                                    <input type="file" class="form-control" name="modFile" id="modFile">
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

