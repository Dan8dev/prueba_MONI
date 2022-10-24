<?php
session_start();
if(!isset($_SESSION["usuario"])){
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
      .special1 > tbody > tr > th {
          background-color: #ccc9c9;
      }
      .popover-body{
          max-height: 10vh;
          overflow: auto;
      }
      .popover{
          width: 15%;
      }

      .truncate {
        max-width:100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
                    <a href="#"><i class="ti-home"></i> Inicio</a>
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
        <!-- <?php #print_r($usuario); ?> -->
        <div class="container-fluid">
          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-6 col-md-3 mb-2">
                    <h4 class="page-title m-0"><i class="ti-briefcase"></i> Comisiones</h4>
                  </div>
                  <?php if ($usuario["persona"]["tipo"] == 1): ?>
                    <div class="col-4 col-md-2 mb-2 mx-4">
                      <table style="width:100%">
                        <tr><td>Voceros</td><td><a href="javascript:void(0)" data-toggle="modal" data-target="#modalInfoVocero" id="count-vocer">0</a></td></tr>
                        <tr><td>Alumnos</td><td><p id="count-alumn" class="m-0">0</p></td></tr>
                        <tr><td>Prospectos</td><td><a href="javascript:void(0)" data-toggle="modal" data-target="#mondalInfoProspecto" id="count-prospect">0</a></td></tr>
                      </table>
                    </div>
                  <?php else: ?>
                    <div class="col-4 col-md-2 mb-2 mx-4">
                      <table style="width:100%">
                        <tr><td>Prospectos</td><td><a href="javascript:void(0)" data-toggle="modal" data-target="#mondalInfoProspecto" id="count-prospect">0</a></td></tr>
                      </table>
                    </div>
				  				<?php endif; ?>
                  <div class="col-6 col-md-3 mb-2">
                    <a id="a_shareCode">
                      <h4 class="page-title m-0">
                        <i class="ti-sharethis"></i>Código:<u><?php echo($usuario["persona"]["codigo"]); ?></u>
                      </h4>
                    </a>
                  </div>
                  <div class="col-6 col-md-3 mb-2">
                    <button type="button" onclick="generarCorte()" class="btn btn-primary waves-effect waves-light">Generar corte</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-wallet"></i></span>
                            <span class="d-none d-sm-block">Acumulado</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fa fa-history"></i></span>
                            <span class="d-none d-sm-block">Historial</span>
                          </a>
                        </li>
                      </ul>
                      <div class="tab-content bg-light">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-8">
                              <a id="alumnos_faltos" style="display:none" href="javascript:void(0)">Alumnos faltos de pago</a>
                              <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Carrera</th>
                                    <th>Alumnos</th>
                                    <th>%</th>
                                    <th>Total</th>
                                  </tr>
                                </thead>


                                <tbody id="datatable-buttons-body">
                                </tbody>
                              </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="card">
                                <div class="card-heading p-4">
                                  <div>
                                    <div class="float-right">
                                      <h2 class="text-primary mb-0 text-center"><span id="lblTotalComisionActual"></span></h2>
                                      <p class="text-muted mb-10 mt-2">Total Comisión</p>
                                    </div>
                                    <p class="mt-4 mb-0 text-muted"><b>Fecha: </b><span id="lblFechaSaldoActual"></span></p>
                                    <p class="mt-4 mb-0 text-muted"><b>Total alumnos: </b><span id="lblTotalAlumnosActual"></span></p>
                                    <p class="mt-4 mb-0 text-muted"><b>Estatus: </b><span id="lblEstatusPagoActual">En curso.</span></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="row">
                            <div class="col-lg-6 m-b-10 ">
                              <h5>Fecha de corte</h5>
                              <label>Periodos anteriores</label>
                              <select id="selectPeriodoCuenta" class="form-control form-select">
                                <option disabled selected>Seleccione opción</option>
                              </select>
                              <button class="btn btn-secondary mt-3" id="btnDesglosePeriodoAnterior">Ver desglose</button>

                            </div>
                            <div class="col-sm-12 col-xl-6">
                              <div class="card">
                                <div class="card-heading p-4">
                                  <div>
                                    <div class="float-right">
                                      <h2 class="text-primary mb-0 text-center"><span id="lblTotalComision"></span></h2>
                                      <p class="text-muted mb-10 mt-2">Total Comisión</p>
                                    </div>
                                    <p class="mt-4 mb-0 text-muted"><b>Fecha: </b><span id="lblFechaSaldo"></span></p>
                                    <p class="mt-4 mb-0 text-muted"><b>Total operaciones: </b><span id="lblTotalOperaciones"></span></p>
                                    <p class="mt-4 mb-0 text-muted"><b>Estatus: </b><span id="lblEstatusPago"></span></p>
                                  </div>
                                </div>
                              </div>
                            </div>


                          </div> 
                        </div> 
                      </div>
                    </div>
                    <?php if ($usuario["persona"]["tipo"] == 1): ?> 
                      <div class="col-lg-12">
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="tab-alumnos-mn" data-toggle="tab" href="#alumn-mn" role="tab" aria-controls="alumn-mn" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-wallet"></i></span>
                            <span class="d-none d-sm-block">Alumnos</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="tab-voceros-mn" data-toggle="tab" href="#voceros-tb" role="tab" aria-controls="voceros-tb" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fa fa-history"></i></span>
                            <span class="d-none d-sm-block">Voceros</span>
                          </a>
                        </li>
                      </ul>
                      <div class="tab-content bg-light">
                        <div class="tab-pane fade show active" id="alumn-mn" role="tabpanel" aria-labelledby="tab-alumnos-mn">
                          <div class="row">
                            <div class="col-sm-12 TBNR">
                              <h2>Relación de alumnos</h2>
                              <table id="datatable-tabla-main" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Alumno</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Mensualidad</th>
                                    <th>Vocero</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="voceros-tb" role="tabpanel" aria-labelledby="tab-voceros-mn">
                          <div class="row">
                            <div class="col-sm-12 col-xl-6 TBNR">
                              <h2>Relación de voceros</h2>
                              <table id="datatable-tabla-main-vocer" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Vocero</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Código</th>
                                    <th>Alumnos</th>
                                    <th>Corte actual</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Alumno</td>
                                    <td>Teléfono</td>
                                    <td>Correo</td>
                                    <td>Pago</td>
                                    <td>Vocero</td>
                                    <td>Teléfono</td>
                                  </tr>
                                  <tr>
                                    <td>Alumno</td>
                                    <td>Teléfono</td>
                                    <td>Correo</td>
                                    <td>Pago</td>
                                    <td>Vocero</td>
                                    <td>Teléfono</td>
                                  </tr>
                                  <tr>
                                    <td>Alumno</td>
                                    <td>Teléfono</td>
                                    <td>Correo</td>
                                    <td>Pago</td>
                                    <td>Vocero</td>
                                    <td>Teléfono</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div> 
                        </div> 
                      </div>
                    </div>
                    <?php endif ?>

                  </div>
                </div>
              </div>
            </div>

          </div> <!-- end container-fluid -->
        </div>
      </div>
      <!-- end wrapper -->
      <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="myModalLabel">Desglose de operaciones <span id="lblHeaderModalAlumnoCarrera"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <h4>Listado de operaciones por alumnos</h4>
              <!-- <a href="javascript:void(0)" id="openSecondModal">Ver listado de alumnos referidos</a> -->
              <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; width: 100%;">
                  <thead>
                    <tr>
                      <th>Alumno</th>
                      <th>Fecha mov.</th>
                      <th>Monto</th>
                      <th>%</th>
                      <th>Comisión</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="table-desglose">
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

      <div id="modalCorte" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalCorteLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="modalCorteLabel">Resumen</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <h4>Resumen correspondiente al periodo <span id="lblPeriodoResumen">actual</span></h4>
              <h5><b id="estatusPagoPeriodo"></b></h5>
              <div class="table-responsive">
                <table id="tablaCrearCorte" class="table table-bordered special1" style="border-collapse: collapse; width: 100%; white-space: nowrap;">
                  <tr>
                    <th>Monto total</th>
                    <th id="tdMontoCorte"></th>
                    <th>Fecha corte</th>
                    <th id="fechaCorte"></th>
                    <th></th>
                  </tr>
                  <tr>
                    <td colspan="5">Operaciones</td>
                  </tr>
                  <tr>
                    <th>Fecha</th>
                    <th>%</th>
                    <th>Comision</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                  </tr>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary waves-effect waves-light" id="buttonGenerarCorte">Generar corte</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

      <div id="modalAlumnos_sinPago" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalAlumnos_sp_Label" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="modalAlumnos_sp_Label">Alumnos pendientes</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <!-- <h4>Resumen correspondiente al periodo <span id="lblPeriodoResumen">actual</span></h4> -->
              <div class="table-responsive">
                <table class="table table-bordered special1" style="border-collapse: collapse; width: 100%; white-space: nowrap;">
                  <thead>
                    <th>Nombre</th>
                    <th>Apellido P.</th>
                    <th>Apellido M.</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                  </thead>

                  <tbody id="body-table-alumno_sp">

                  </tbody>
                </table>
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="modalInfoVocero" tabindex="-1" aria-labelledby="Label_modalInfoVocero" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5>Datos de persona</h5>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div id="accordion-test" class="card-box">

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="mondalInfoProspecto" tabindex="-1" aria-labelledby="Label_mondalInfoProspecto" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5>Prospectos</h5>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="table-responsive TBNR">
                <table id="datatable-tabla-prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Correo</th>
                      <th>Fecha</th>
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

      <!-- Inicializador de tablas init js -->
      <script src="../assets/pages/datatables.init.js"></script>
      <script src="../assets/pages/datatables.init.class.js"></script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/colaboradores/panel-colaborador.js"></script>

      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
      <script>
        $(document).ready(function(){
          if(navigator.share){
            $("#a_shareCode").on("click", function(e){
              e.preventDefault();
              navigator.share({
                text: usrInfo.persona.codigo+' Utiliza este código para obtener grandes beneficios.'
              })
            })
          }else{

            $("#a_shareCode").prop("href", "whatsapp://send?text="+usrInfo.persona.codigo+" Utiliza este código para obtener grandes beneficios.")
            $("#a_shareCode").attr("data-action", "share/whatsapp/share")
                    //href="whatsapp://send?text=<?php #echo($usuario["persona"]["codigo"]); ?>" 
                    //data-action="share/whatsapp/share"
                  }
                })
        function copyTexto(elm){

                // document.execCommand("copy")
              }
            </script>
          </body>
          </html>