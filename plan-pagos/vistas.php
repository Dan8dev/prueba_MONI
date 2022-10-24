<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 4){
    header("Location: ../index.php");
    die();
}
    $usuario = $_SESSION["usuario"];

?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <title>MONI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- CSS bootstrap -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- iconos fontawesom -->
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <!-- CSS general -->
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">

    <!-- DataTables (Tablas) CSS -->
    <link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	  
	  <link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
    
    <style type="text/css">
        .but-circle{
          padding: 1px 4px !important;
          border-radius: 20px;
          max-width: 25px;
          max-height: 25px;
          min-width: 25px;
          margin-right: 3px;
        }
        .but-circle > i{
          /*margin-left: -4px;*/
        }
        .dataTables_filter > label > input {
            width: 80% !important;
        }
        .tab_active{
          color: #aa262c;
          border-bottom: solid #aa262c 2px;
        }
        .page-title > span {
          cursor: pointer;
        }
        .form-control{
          border: 1px solid #c8c8c8;
        }
    </style>

  </head>


    <body>

      <div class="header-bg">
        <!-- Navigation Bar-->
        <header id="topnav">
          <div class="topbar-main">
            <div class="container-fluid">
              <!-- Logo-->
              <div>
                <a href="#" class="logo">
                  <img src="../assets/images/logo-light.png" class="logo-lg" alt="" height="26">
                  <img src="../assets/images/logo-sm.png" class="logo-sm" alt="" height="28">
                </a>
              </div>
              <!-- End Logo-->

              <div class="menu-extras topbar-custom navbar p-0">
                <ul class="list-inline d-none d-lg-block mb-0">
                </ul>

                <ul class="mb-0 nav navbar-right ml-auto list-inline">

                  <li class="list-inline-item notification-list d-none d-sm-inline-block">
                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="fas fa-expand"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                      <span class="profile-username">
                       <?php echo $usuario["persona"]["nombres"]; ?> <span class="mdi mdi-chevron-down font-15"></span>
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <!--<li><a href="javascript:void(0)" class="dropdown-item"> Profile</a></li>-->
                      <li class="dropdown-divider"></li>
                      <li><a href="../siscon/app/editar_acceso.php?perfil=marketing" class="dropdown-item"> Cambiar contraseña</a></li>
                      <li><a href="../log-out.php" class="dropdown-item"> Cerrar sesión</a></li>
                    </ul>
                  </li>
                  <li class="menu-item dropdown notification-list list-inline-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle nav-link">
                      <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                      </div>
                    </a>
                    <!-- End mobile menu toggle-->
                  </li>
                </ul>
              </div>
              <!-- end menu-extras -->
              <div class="clearfix"></div>
            </div>
            <!-- end container -->
          </div>
          <!-- end topbar-main -->

          <!-- MENU Start -->
          <div class="navbar-custom">
            <div class="container-fluid">
              <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                  <li class="has-submenu">
                    <a href="index.php"><i class="ti-home"></i> Inicio</a>
                  </li>

                  <li class="has-submenu">
                    <a href="alumnos.php"><i class="ion ion-md-calendar"></i> Gestionar Alumnos</a>
                  </li>

                  <li class="has-submenu">
                    <a href="vistas.php"><i class="ion ion-md-key"></i> Gestionar vistas</a>
                  </li>
                </ul>
                <!-- End navigation menu -->
              </div>
              <!-- end #navigation -->
            </div>
            <!-- end container -->
          </div>
          <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->
      </div>
      <!-- header-bg -->

      <div class="wrapper">
        <div class="container-fluid">

          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-6 col-md-12 mb-2">
                    <h4 class="page-title m-0">
                      <span tab-target="afiliados" class="tab_active">
                        <i class="ti-briefcase"></i> Concentrado de afiliados
                      </span> 
                      |
                      <span tab-target="vistas">
                        <i class="fas fa-eye"></i> Vistas
                      </span> 
                      <!-- 
                      |
                      <span tab-target="conceptos">
                        <i class="fas fa-money-bill-alt"></i> Conceptos de pago
                      </span> |
                      <span tab-target="promociones">
                        <i class="fas fa-percent"></i> Promociones
                      </span> |
                      <span tab-target="carreras">
                        <i class="fas fa-user-graduate"></i> Carreras
                      </span> -->
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- SECCION PARA CONCENTRADO DE EVENTOS -->
            <div class="col-sm-12" id="tab_concentrado_afiliados">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- FIN CONTENEDOR DE TABS -->
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <!-- <div class="col-lg-12 col-sm-12 col-md-12">
                            <div>
                              <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal">
                                Crear Plan de Pago
                              </button>
                            </div>
                          </div> -->
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado de afiliados</h5>

                              <table id="table-afiliados" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Nombre</th>
                                    <th>Vistas asignadas</th>
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
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE GENERACIONES -->
            <div class="col-sm-12" id="tab_concentrado_vistas" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" role="tabpanel">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                              <div>
                                <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal_registrar_vista">
                                  Registrar vista
                                </button>
                              </div>
                            </div>
                            <h5>Vistas existentes</h5>
                            <table id="table-vistas-exist" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                              <thead>
                                <tr>
                                  <th>Nombre</th>
                                  <th>Descripción</th>
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

                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE CONCEPTOS DE PAGO -->
            <div class="col-sm-12" id="tab_concentrado_conceptos" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                            <h5>Listado Conceptos de pago</h5>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE PROMOCIONES -->
            <div class="col-sm-12" id="tab_concentrado_promociones" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                            <h5>Listado Promociones</h5>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE CARRERAS -->
            <div class="col-sm-12" id="tab_concentrado_carreras" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                            <div>
                              <button id="boton-crear-carrera" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal">
                                Crear Carrera
                              </button>
                            </div>
                          </div>
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado de carreras</h5>
                              <table id="table-carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>INSTITUCION</th>
                                    <th>NOMBRE</th>
                                    <th>NOMBRE CLAVE</th>
                                    <th>TIPO</th>
                                    <th>MODALIDAD</th>
                                    <th>DURACION TOTAL</th>
                                    <th>TIPO CICLO</th>
                                    <th>CODIGO PROMOCIONAL</th>
                                    <th>ESTADO</th>
                                    <th>PLANTILLA BIENVENIDA</th>
                                    <th>ESTATUS</th>
                                    <th>FECHA INICIO</th>
                                    <th>FECHA FIN</th>
                                    <th>FECHA CREACIÓN</th>
                                    <th>ACCIONES</th>
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
              </div>
            </div>
          </div> <!-- end container-fluid -->
        </div>
      </div>
      <!-- end wrapper -->
      <!-- Modas -->
      <!-- center modal form alta plan de pagos -->
      <div id="modal_vistas_asignadas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Listado de vistas asignadas</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <center><h3 id="lbl_afiliado_accesos"></h3></center>
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <form id="form_acceso_vistas">
                        <input type="hidden" name="prospecto_vista_set" id="prospecto_vista_set">
                        <div class="table-responsive">
                          <table id="table-vistas-afiliados" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                            <thead>
                              <tr>
                                <th>Vista</th>
                                <th>Acceso</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>

                          <div>
                            <button class="btn-primary btn" disabled id="button_asignar_vistas" type="submit">Guardar vistas</button>
                          </div>
                        </form>
                      </div>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal plan de pagos -->

      <!-- center modal form alta carreras -->
      <div id="modal_registrar_vista" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0">Registrar Vista</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <form id="form_registrar_vista">
                        <div class="row">
                          <div class="col-sm-6">
                            <label>Vista</label>
                            <select class="form-control select_direct" id="select_direct" name="directorio" required>
                              
                            </select>
                          </div>
                          <div class="col-sm-6">
                            <label>Nombre</label>
                            <input type="text" name="nombre_vista" id="nombre_vista" required class="form-control">
                          </div>
                          <div class="col-sm-12 mt-2">
                            <label>Descripción</label> <small>(opcional)</small>
                            <input type="text" name="descripcion_vista" id="descripcion_vista" class="form-control">
                          </div>
                        </div>
                        <div class="row mt-2">
                          <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
                <!-- <button type="button" class="btn btn-primary waves-effect waves-light">Guardar</button> -->
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal carreras -->

      <div id="modal_editar_registro_v" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0">Editar registro</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <form id="form_actualizar_vista">
                        <input type="hidden" name="editar_vista_i" id="editar_vista_i">
                        <div class="row">
                          <div class="col-sm-6">
                            <label>Vista</label>
                            <select class="form-control select_direct" name="directorio" id="select_edit_v" required>
                              
                            </select>
                          </div>
                          <div class="col-sm-6">
                            <label>Nombre</label>
                            <input type="text" name="nombre_vista" id="nombre_vista_edit" required class="form-control">
                          </div>
                          <div class="col-sm-12 mt-2">
                            <label>Descripción</label> <small>(opcional)</small>
                            <input type="text" name="descripcion_vista" id="descripcion_vista_edit" class="form-control">
                          </div>
                          <div class="col-sm-12 mt-2">
                            <input type="checkbox" id="check_active_vist" name="check_active_vist">
                            <label for="check_active_vist">Habilitada</label>
                          </div>

                        </div>
                        <div class="row mt-2">
                          <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
                <!-- <button type="button" class="btn btn-primary waves-effect waves-light">Guardar</button> -->
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal carreras -->


      <!-- Footer -->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              © 2021 MONI <span class="d-none d-md-inline-block">IESM-UDC-TSU-CONACON TI</span>
            </div>
          </div>
        </div>
      </footer>
      <!-- End Footer -->
      <!-- scripts -->

      <!-- jQuery  -->
      <script src="../assets/js/template/jquery.min.js"></script>
      <script src="../assets/js/template/bootstrap.bundle.min.js"></script>
      <script src="../assets/js/template/modernizr.min.js"></script>
      <script src="../assets/js/template/detect.js"></script>
      <script src="../assets/js/template/fastclick.js"></script>
      <script src="../assets/js/template/jquery.slimscroll.js"></script>
      <script src="../assets/js/template/jquery.blockUI.js"></script>
      <script src="../assets/js/template/waves.js"></script>
      <script src="../assets/js/template/wow.min.js"></script>
      <script src="../assets/js/template/jquery.nicescroll.js"></script>
      <script src="../assets/js/template/jquery.scrollTo.min.js"></script>
      <script src="../assets/js/template/jquery.maskMoney.js"></script>

      <!--  datatable (tablas) js-->
      <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
      <!-- Tabla con botones excel, pdf, imprimir -->
      <script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
      <script src="../assets/plugins/datatables/buttons.bootstrap4.min.js"></script>

      <script src="../assets/plugins/datatables/jszip.min.js"></script>
      <script src="../assets/plugins/datatables/pdfmake.min.js"></script>
      <script src="../assets/plugins/datatables/vfs_fonts.js"></script>
      <script src="../assets/plugins/datatables/buttons.html5.min.js"></script>
      <script src="../assets/plugins/datatables/buttons.print.min.js"></script>
      <script src="../assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
      <script src="../assets/plugins/datatables/dataTables.keyTable.min.js"></script>
      <script src="../assets/plugins/datatables/dataTables.scroller.min.js"></script>

      <!-- sweet alert -->
      <script src="../assets/js/template/sweetalert.min.js"></script>

      <!-- Tablas responsivas -->
      <script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
      <script src="../assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

      <script src="../assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.js"></script>

      <!-- Inicializador de tablas init js -->
      <script src="../assets/pages/datatables.init.class.js"></script>

      <script src="../assets/pages/clipboard.js"></script>
      <script type="text/javascript">
        new ClipboardJS('.clpb', {
          text: function(trigger) {
              return trigger.getAttribute('aria-label');
          }
        });
        $(document).ready(function(){
          $("#tabla_seguimientos").DataTable({
              'pageLength': 5,
                  "lengthChange": false,
                  "info":     false
          }).columns.adjust()
          $(".moneyFt").maskMoney();
        })
        $(".page-title").children().on('click', function(){
          if(!$(this).hasClass('tab_active')){
            enab = $(".tab_active").attr('tab-target');
            trg = $(this).attr('tab-target');

            $("#tab_concentrado_"+enab).fadeOut('fast', function(){
              $("#tab_concentrado_"+trg).fadeIn('fast', function(){
                $("#tab_concentrado_"+trg).find('table').each(function(){
                  $(this).DataTable().columns.adjust()
                })
              })
            })

            $(".tab_active").removeClass("tab_active");
            $(this).addClass("tab_active");

          }
        })
      </script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/planpagos/vistas.js"></script>
      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>