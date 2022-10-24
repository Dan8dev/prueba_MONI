<?php 
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
  
  require 'plantilla/header.php';
?>
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="panel.php">Panel</a>
            <span class="breadcrumb-item active">Registrar Alumno</span>
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
        <div class="tab-content bg-light">
          <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
            <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#posts" role="tab">Registrar Alumno</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photos" role="tab">Listado</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#eventos" role="tab">Eventos</a></li>
            </ul>
          </div>
        
          <div class="tab-content br-profile-body">
            <div class="tab-pane fade active show" id="posts">
              <div class="card m-3">
                  <div class="card-body">
                      <h4 class="page-title ">Registrar Alumnos</h4>
                      <form id="form_nuevo_prospecto">
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-4">
                                  <label>Nombre</label>
                                  <input type="text" name="name" id="name" class="form-control special" required>
                              </div>
                              <div class="form-group col-sm-12 col-md-4">
                                  <label>Apellido Paterno.</label>
                                  <input type="text" name="paterno" id="paterno" class="form-control special"
                                      required>
                              </div>
                              <div class="form-group col-sm-12 col-md-4">
                                  <label>Apellido Materno.</label>
                                  <input type="text" name="materno" id="materno" class="form-control special"
                                      required>
                              </div>
                          </div>

                          <div class="row">
                              <div class="form-group col-sm-12 col-md-6">
                                  <label>Teléfono</label>
                                  <input type="tel" name="telefono" id="telefono" class="form-control onlyNum" required maxlength="10" minlength="10">
                              </div>
                              <div class="form-group col-sm-12 col-md-6">
                                  <label>Correo.</label>
                                  <input type="mail" name="email" id="email" class="form-control" required>
                              </div>
                          </div>
                          <input name="IDOrganizacion" id="IDOrganizacion" type="hidden" value="<?php echo (!empty($Clinicas['data']['id_institucion'])) ?  $Clinicas['data']['id_institucion'] : ''; ?>">

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                                <label>Curp</label>
                                <input type="text" maxlength="18" name="Curp" id="Curp" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 mt-2">
                                <!-- <label>Evento</label> -->
                                <select class="form-control d-none" required name="id_destino" id="id_destino" required value ="39">
                                </select>
                            </div>
                          </div>

                          <div class="row mt-4">
                              <div class="ml-auto mr-2">
                                  <button type="submit" class="btn btn-primary">Guardar</button>
                                  <button type="button" class="btn btn-secondary"
                                      onclick="$('#form_nuevo_prospecto')[0].reset()">Cancelar</button>
                              </div>

                          </div>
                      </form>
                  </div>
              </div>
            </div>
            <div class="tab-pane fade table-responsive" id="photos">
              <div class="m-3">
                <table id="datatable-tablaAlumnos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>A. Paterno</th>
                      <th>A. Materno</th>
                      <th>Correo</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div> <!--Fin de Tab-->

            <div class="tab-pane fade table-responsive" id="eventos">
              <div class="m-3">
                <table id="datatable-tablaEventos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div> 
        </div><!-- br-mainpanel -->
        <!-- ########## END: MAIN PANEL ########## -->
    </div><!-- br-pagebody -->

    <?php require 'plantilla/footer.php'; ?>
</div><!-- br-mainpanel -->
<!-- modals -->
<div id="modaldemo1" class="modal fade">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bd-0 tx-14">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Configuración general</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <div class="border p-2"><h6>Datos generales</h6>
          <form id="form-actualizar-registro">
            <div class="row">
              <input type="hidden" name="editar_tipo_moneda" id="editar_tipo_moneda">
              <input type="hidden" name="edit_pr_institucion" id="edit_pr_institucion">
              <input type="hidden" name="inp_prospect_edit" id="inp_prospect_edit">

              <div class="col-md-6 col-sm-12 mb-3">
                  <label>Nombre</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_nombre" name="edit_pr_nombre">
              </div>
              <div class="col-md-6 col-sm-12 mb-3">
                  <label>Apellido Paterno</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_apaterno" name="edit_pr_apaterno">
              </div>
              <div class="col-md-6 col-sm-12 mb-3">
                  <label>Apellido Materno</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_amaterno" name="edit_pr_amaterno">
              </div><div class="col-md-6 col-sm-12 mb-3">
                  <label>Correo</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_correo" name="edit_pr_correo">
              </div><div class="col-md-6 col-sm-12 mb-3">
                  <label>Teléfono</label>
                  <input type="text" class="form-control form-control-sm onlyNum" id="edit_pr_telefono" name="edit_pr_telefono" maxlength="10">
              </div>
            </div>
            <div class="row text-right">
              <div class="col">
                <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
              </div>
            </div>
          </form>
        </div>
        <div class="bg-light rounded p-2">
          <label>Seleccionar otra platica a la que apuntar al alumno</label>
          <div class="row">
            <div class="col">
              <input type="hidden" id="id_alumn_ref">
              <select name="nuevo_evento" id="nuevo_evento" class="form-control form-control-sm">
              </select>
            </div>
            <div class="col">
              <button type="button" class="btn btn-primary btn-sm" onclick="agregar_evento()">Agregar</button>
            </div>
          </div>
        </div>
        <h6 class="mt-4">Lista de platicas a las que está registrado</h6>
        <table class="table table-sm" id="table_eventos_apuntados">
          <thead>
            <th>Evento</th>
            <th>Fecha registro</th>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modaldemo2" class="modal fade">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bd-0 tx-14">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Agregar Asistentes</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <div class="border p-2"><h6>Selecciona los asistentes</h6>
            <input type="hidden" name="inp_ev_ref" id="inp_ev_ref">
              <div class="tab-pane table-responsive" id="registrados">
                <div class="m-3">
                  <table id="datatable-tablaAsistEventos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>A. Paterno</th>
                        <th>A. Materno</th>
                        <th>Correo</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div> 
          </div>
          <div class="modal-footer">
        <button onclick="registrar_muchos()" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div><!-- modal-dialog -->
</div><!-- modal -->

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
    cargar_cursos_pagos();
    institu = <?php echo (!empty($Clinicas['data']['id_institucion'])) ?  $Clinicas['data']['id_institucion'] : '';?>;
    cargar_referidos(institu);
});
$(".onlyNum").on('keypress',function(evt){
    if (evt.which < 46 || evt.which > 57){
        evt.preventDefault();
    }
})
</script>

</body>

</html>
