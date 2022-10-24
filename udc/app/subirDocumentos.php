<?php 
header('Access-Control-Allow-Origin: https://moni.com.mx', false);
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on"){
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
    $fechaactual= date('Y-m-d H:i:s');
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
            <input type="text" name="TipoAlumno" id="TipoAlumno" class="d-none" value ="">
            <input type="text" name="IdentificadorNacionalidad" id="IdentificadorNacionalidad" class="d-none" value ="<?php echo $NacionalidadAlumno;?>">
            <div class="card shadow-base bd-0 rounded-0 widget-4">
                <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
                    <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-target="#seguimiento" href="#seguimiento" role="tab">Seguimiento</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" data-target="#documentos" href="#documentos" role="tab">Documentos</a></li>
                    </ul>
                </div>
            </div>
            <div class="tab-content br-profile-body">
                <?php // if ($dias<31) {
                # code...
                ?>
                <!-- <div id="alert-pago-anual" class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php //echo $diasrestantes?> </strong> 
                </div> --><!-- alert -->
                <?php //} ?>
                <div class="row tab-pane fade" id="documentos" role="tabpanel">
                    <div class="card p-3 mt-1 mb-3 text-justify">
                        <h3 class="m-b-30 m-t-0">Documentación</h3>
                        <div class="clave alert bg-gray">
                            <div class="tx-bg">
                                Por favor, presta atención a las indicaciones que se encuentran. <br> 
                                Cada archivo no debe sobrepasar los 5MB, para guardar el documento puede ser uno por uno al dar clic en el botón "Enviar" o puede adjuntar multiples archivos y enviarlos dando clic en el botón "Enviar varios archivos". <br>
                                Formato de imágenes:<br>
                                * .jpg <br>
                                * .jpeg <br>
                                * .png <br>
                                Formato de documentos: <br>
                                * .pdf <br>
                                * .jpg <br>
                                * .jpeg <br>
                                * .png <br>
                            </div>
                        </div>
                        <form id="formDocumentosExtranjeros">
                            <div class="card p-3 mt-1 mb-3 text-justify">
                                <div id = "Formulario_dinamico"></div>
                            </div>
                        </form>
                        <div class="documentacion-info">
                            <div class="clave alert alert-info MEXICANO bg-white text-secondary">
                                Deberá presentar la documentación en el orden arriba mencionado, con sus 3 fotocopias cada uno, en un sobre de papel manila color amarillo. <br>
                                Todas las fotografías deberán traer escrito su nombre en la parte trasera con lapicero sin remarcar fuertemente para que no se traspase por el frente ya que si quedan marcadas no sirven.
                                <br><strong>DEBEN</strong> ser tomadas completamente de frente, con el rostro serio, la frente y las orejas completamente descubiertas.
                                <br><h4 class="tx-center mt-2">HOMBRES</h4>
                                <li>Vestimenta formal, saco, camisa y corbata lisos, sin estampados.</li>
                                <li>Bigote recortado por arriba del labio superior.</li>
                                <li>Sin barba, lentes ni pupilentes de ningún color.</li>
                                <br><h4 class="tx-center">MUJERES</h4>
                                <li>Vestimenta formal: saco sin estampados, blusa de cuello blanco y sin escote.</li>
                                <li>Cabello recogido hacia atrás.</li>
                                <li>Sin adornos</li>
                                <li>Sin lentes ni pupilentes de ningún color</li>
                                <li>Maquillaje discreto</li>
                                <br> <a href="https://api.whatsapp.com/send/?phone=522288334581&text&type=phone_number&app_absent=0" class="tx-dark"> <img src="img/whats.png" ><strong>228-833-45-81 </strong></a>
                                <br> <a href="https://api.whatsapp.com/send/?phone=522288334031&text&type=phone_number&app_absent=0" class="tx-dark"> <img src="img/whats.png" ><strong>228-833-40-31 </strong></a>
                                <br> <img src="img/mail.png"> <strong> controlescolar@universidaddelconde.edu.mx</strong>
                                <br> <img src="img/mail.png"><strong>udcxal@gmail.com</strong>
                                <br>
                            </div>

                            <div class="clave alert alert-info EXTRANJERO bg-white text-secondary">
                                <br>
                                    <b>
                                        Las fotografías le recomendamos tomárselas ya estando en México, para que de ese modo cumplan con el total de especificaciones solicitadas.
                                    </b>
                                <br></br>
                                Deberá presentar la documentación en el orden arriba mencionado, con sus 3 fotocopias cada uno, en un sobre de papel manila color amarillo. <br>
                                Todas las fotografías deberán traer escrito su nombre en la parte trasera con lapicero sin remarcar fuertemente para que no se traspase por el frente ya que si quedan marcadas no sirven.
                                <br><strong>DEBEN</strong> ser tomadas completamente de frente, con el rostro serio, la frente y las orejas completamente descubiertas.
                                <br><h4 class="tx-center mt-2">HOMBRES</h4>
                                <li>Vestimenta formal, saco, camisa y corbata lisos, sin estampados.</li>
                                <li>Bigote recortado por arriba del labio superior.</li>
                                <li>Sin barba, lentes ni pupilentes de ningún color.</li>
                                <br><h4 class="tx-center">MUJERES</h4>
                                <li>Vestimenta formal: saco sin estampados, blusa de cuello blanco y sin escote.</li>
                                <li>Cabello recogido hacia atrás.</li>
                                <li>Sin adornos</li>
                                <li>Sin lentes ni pupilentes de ningún color</li>
                                <li>Maquillaje discreto</li>
                                <br> <a href="https://api.whatsapp.com/send/?phone=522288334581&text&type=phone_number&app_absent=0" class="tx-dark"> <img src="img/whats.png" ><strong>228-833-45-81 </strong></a>
                                <br> <a href="https://api.whatsapp.com/send/?phone=522288334031&text&type=phone_number&app_absent=0" class="tx-dark"> <img src="img/whats.png" ><strong>228-833-40-31 </strong></a>
                                <br><img src="img/mail.png"> <strong>controlescolar@universidaddelconde.edu.mx</strong>
                                <br><img src="img/mail.png"></i> <strong>udcxal@gmail.com</strong>
                                <br>
                            </div>          
                        </div>
                    </div>
                </div>
                <div class="row tab-pane fade show active" id="seguimiento" role="tabpanel">
                    <input class="<?php echo $idusuario; ?>" type="hidden" name="id" id="id">
                    <div class="container col-sm-12 col-lg-12">
                        <div class="card p-3 mt-1 mb-3 text-justify">
                            <h3 class="m-b-30 m-t-0">Proceso de Seguimiento</h3>
                            <div class="card shadow-base bd-0 rounded-0 widget-4">
                                <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
                                    <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
                                        <li class="nav-item 2"><a id ="nav1" class="nav-link" data-toggle="tab" data-target="#documentos_digitales" href="#documentos_digitales" role="tab">
                                            <span class="d-block d-md-none"><i class="fa fa-image fa-2xl"></i></span>
                                            <span class="d-none d-md-block"><i class="fa fa-image fa-2xl"></i></i> Documentos digitales</span></a>
                                        </li>
                                        <li class="nav-item 2"><a a id ="nav1" class="nav-link" data-toggle="tab" data-target="#documentos_fisicos" href="#documentos_fisicos" role="tab">
                                            <span class="d-block d-md-none"><i class="fa fa-address-card fa-2xl"></i></span>
                                            <span class="d-none d-md-block"><i class="fa fa-address-card fa-2xl"></i> Documentos fisicos</span></a>
                                        </li>

                                    </ul>
                                        <!-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-target="#documentos_digitales" href="#documentos_digitales" role="tab">Documentos digitales</a></li>
                                        <li class="nav-item"><a id ="nav1" class="nav-link" data-toggle="tab" data-target="#documentos_fisicos" href="#documentos_fisicos" role="tab">Documentos fisicos</a></li> -->
                                </div>
                            </div>
                            
                            <div class="mt-3 tab-content my-5">
                                
                                <div class="tab-pane fade show active" id="documentos_digitales" role="tabpanel">    
                                    <div class="table-responsive">
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
                                <div class="tab-pane fade" id="documentos_fisicos" role="tabpanel">
                                    <div class="table-responsive">
                                        <table id="datatable-seguimiento-fisicos" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Documento</th>
                                                    <th>Fecha de Registro</th>
                                                    <th>Estatus</th>
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
                                    <input type="file" class="form-control" name="modFile" id="modFile" accept=".jpg, .jpeg, .png, .pdf" required>
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
    </script>
</html>

