<!-- Iconografía fontawesom  (fa) https://fontawesome.com/ -->

<?php
    if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
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
    $idusuario = $_SESSION['alumno']['id_afiliado'];
    $idProspecto = $_SESSION['alumno']['id_prospecto'];
    $afiliados = new Afiliados();
    $usuario = $afiliados->obtenerusuario($idusuario);

?>

<!DOCTYPE html>
<html lang="en">
<?php require 'plantilla/header.php'; ?>
<!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="panel.php">INICIO</a>
                <span class="breadcrumb-item active">PAGOS</span>
            </nav>
        </div>

        <div class="br-pagebody">
        
            <!-- ########## START: CARDS INFORMATIVOS ########## -->
            <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
                <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
                    <li class="nav-item"><a id = "D1" class="nav-link active" data-toggle="tab" href="#posts" role="tab">DESCARGAR FORMATOS</a></li>
                    <li class="nav-item"><a id = "R1" class="nav-link" data-toggle="tab" href="#photos" role="tab">REVISION DE FORMATOS</a></li>
                    <li class="nav-item"><a id = "E1" class="nav-link" data-toggle="tab" href="#correccion_formatos" role="tab">CORRECCIÓN DE FORMATOS</a></li>
                    <li class="nav-item"><a id = "F1" class="nav-link" data-toggle="tab" href="#enviar_formatos" role="tab">ENVIAR FORMATOS</a></li>
                    <li class="nav-item"><a id = "C1" class="nav-link" data-toggle="tab" href="#concluidos" role="tab">CONCLUIDO</a></li>
                </ul>
            </div>

            <div class="card card-body pd-x-25" id="container-conceptos-pago">
                <div class="tab-content br-profile-body">
                    <input class ="d-none" type="text" name="numeroAfiliado" id="numeroAfiliado" value ="<?php echo $idusuario;?>">
                    <input class ="d-none" type="text" name="numeroProspecto" id="numeroProspecto" value ="<?php echo $idProspecto;?>">
                    <div class="tab-pane fade active show" id="posts">
                        <div class="row">
                            <div class="col-sm-12 overflow-auto">
                                <h5 class="tx-center mb-3">DESCARGAR FORMATOS</h5>
                                <br>Por favor sigue los pasos mostrados a continuación.<br>
                                <b>1. </b>Descarga los formatos para el Servicio Social.<br>
                                <b>2. </b>Llenalos de acuerdo a lo citado o marcado en el mismo.<br>
                                <b>3. </b>Antes de firmar o colocarle los sellos correspondientes, ya sea por ti o por la dependencia, deberás enviarlos a <b>revision</b> en el submenu <b>Revision de Formatos</b><br> 
                                <hr class="d-none d-sm-block">
                                <table id = "tabla-descarga-formatos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Proceso</th>
                                            <th>Formato</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="photos">
                        <div class="row">
                            <div class="col-sm-12 overflow-auto">
                                <h5 class="tx-center mb-3">REVISION DE FORMATOS</h5>
                                <center><b class = "text-center">Recuerda Enviar tus formatos en el orden indicado, de lo contrario control escolar no validará tus documentos</b></center>
                                <br>Por favor sigue las instrucciones.<br> 
                                <b>a. </b>Llena la información solicitada en el formulario enviar (En computadora).<br>
                                <b>b. </b>Adjunta y envía por este medio el Formato que pasará a Revisión.<br>
                                <b>c. </b>Cuando un Formato se encuentre en estatus <b>Aprobado</b> entonces procede con la impresión, firma y/o sello según sea su caso.<br> 
                                <hr class="d-none d-sm-block">
                                <table id = "tabla-revision-formatos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Proceso</th>
                                            <th>Formato</th>
                                            <th>Numero de envio</th>
                                            <th>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    

                    <div class="tab-pane fade" id="correccion_formatos">
                        <div class="row">
                            <div class="col-sm-12 overflow-auto">
                                <h5 class="tx-center mb-3">CORRECCIÓN DE FORMATOS</h5>
                                <br>Por favor sigue las instrucciones.<br> 
                                <b>a. </b>Abre el archivo del formato a corregir (El que enviaste para revisión).<br>
                                <b>b. </b>Corrige las observaciones mostradas.<br>
                                <b>c. </b>Guarda los nuevos cambios.<br>
                                <b>d. </b>Envía desde este mismo modulo el archivo corregido.<br> 
                                <hr class="d-none d-sm-block">
                                <table id = "tabla-correccion-formatos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Proceso</th>
                                            <th>Archivo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="enviar_formatos">
                        <div class="row">
                            <div class="container-fluid">
                                <div class="col-sm-12 overflow-auto">
                                    <h4 class="tx-center mb-3">ENVIAR FORMATOS</h4>
                                    <hr class="d-none d-sm-block">
                                    <font size = "5">
                                        <p>Hemos detectado que tienes todos los formatos del Servicio Social listos, por tal motivo ya puedes enviarlos de manera fisica 
                                            (en original, con firmas, y sellos correspondientes)
                                            <ul>
                                                <li><b>1.- </b>Solicitud</li>
                                                <li><b>2.- </b>Hoja de Control</li>
                                                <li><b>3.- </b>Carta de Aceptación</li>
                                                <li><b>4.- </b>Reporte Mensual</li>
                                                <li><b>5.- </b>Evaluacion Final de Desempeño</li>
                                                <li><b>6.- </b>Informe General</li>
                                                <li><b>7.- </b>Carta de Conclusión</li>
                                            </ul>
                                        </p>
        
                                        <p>Dichos Formatos debes enviarlos a la siguiente dirección</p>
        
                                        <p>
                                            <b>Universidad del Conde</b><br>
                                            Carretera Antigua a Coatepec Kilometro 5+500<br>
                                            Calle Mariano Escobedo S/N <br>
                                            Col. Mariano Escobedo, C.P.91608<br>
                                            Coatepec, Veracruz, Mexico 
                                        </p>
                                    </font>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="concluidos">
                        <div class="row">
                            <div class="col-sm-12 overflow-auto">
                                <h5 class="tx-center mb-3">CONCLUIDO</h5>
                                <hr class="d-none d-sm-block">
                                <div class="tx-center container-fluid">
                                    <font size = "5">
                                        <p>
                                            Hemos recibido tus formatos de <b>Servicio Social</b>, por tal motivo damos por concluido este proceso
                                        </p>
                                    </font>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div><!-- br-pagebody -->
            </div>
        </div>

        
        <!-- Modal -->
        <div class="modal fade" id="ComentariosServicio" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Observaciones</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="tablaComentariosArchivo"  class="table table-striped table-bordered nowrap w-100" style="overflow:auto; width: 100%; display: block;">
                                            <thead>
                                                <th>Comentarios</th>
                                                <th>Fecha</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                                
                            <form id="Comentario-document-alu">
                                <div class="form-group">
                                    <input class = "form-control d-none" type="number" name="idArchivo" id="idArchivo" required>
                                    <label for="ComentarioArchivo">Nuevo comentario</label>
                                    <input class = "form-group form-control" type="text"  name = "ComentarioArchivo" id= "ComentarioArchivo" required>
                                    <button type="submit" class="form-group btn btn-primary">Comentar</button>
                                </div>
                            </form>
					    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'plantilla/footer.php'; ?>
    </div><!-- br-mainpanel -->
  <!-- ########## END: MAIN PANEL ########## -->

  <script src="../lib/jquery/jquery.js"></script>
  <script src="../lib/popper.js/popper.js"></script>
  <script src="../lib/bootstrap/bootstrap.js"></script>
  <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../lib/moment/moment.js"></script>
  <script src="../lib/jquery-ui/jquery-ui.js"></script>
  <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
  <script src="../lib/peity/jquery.peity.js"></script>

  <script src="../js/sweetalert.min.js"></script>
  <script src="../js/bootstrap-filestyle.js"></script>

  <script src="../lib/datatables/jquery.dataTables.js"></script>
  <script src="script/inicializar_datatable.js"></script>

  <script src="../js/jquery.maskMoney.js"></script>


  <script src="../js/bracket.js"></script>
  <script src="../app/script/servicioSocial.js"></script>
  <script src="script/jquery.payment.js"></script>
  <script type="text/javascript" src="https://conektaapi.s3.amazonaws.com/v0.5.0/js/conekta.js"></script>
  <!-- <script src="https://www.paypal.com/sdk/js?client-id=AfcFm_FJBcwJ0-Urg7O8Jb_E0LpsGoO2_Oy6CFCNHWTIrDm09VNo9kCl6VWiYT9GrlT2B_0f-LYwNHQD&currency=MXN" data-sdk-integration-source="button-factory"></script> -->
  </body>

</html>
