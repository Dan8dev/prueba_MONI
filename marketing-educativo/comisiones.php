<?php
date_default_timezone_set("America/Mexico_City");
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 3 && $_SESSION["usuario"]['idTipo_Persona'] != 4){
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
          margin-left: 10px;
        }
        .form-control{
          border: 1px solid #c8c8c8;
        }
        .notify-item .notify-icon {
          float: left;
          height: 16px;
          width: 16px;
          line-height: 36px;
          text-align: center;
          margin-right: 5px;
          border-radius: 50%;
        }

        .notify-item .notify-details {
          margin-bottom: 0;
          overflow: hidden;
          margin-left: 25px;
          text-overflow: ellipsis;
          white-space: nowrap;
        }

        .nav-tabs .nav-link.disabled {
          background-color: #bfbcbc8f;
        }

        @media (min-width:992px) {
          .modal-lg,
          .modal-xl {
            max-width:900px;
          }
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
                  <li class="list-inline-item dropdown notification-list show">
                    <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light notification-icon-box" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-phone"></i> <span id="span_alert_llamadas" class="badge badge-xs"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg overflow-auto" style="max-height: 85vh;">
                        <li class="list-group" id="list-notification-llamada">
                            
                        </li>
                    </ul>
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
                  <?php
                  $accesos = ['market1@mk.com', 'master-marketing@mk.com', 'marketing.educativo.22@gmail.com'];
                              if(in_array($usuario['correo'],$accesos)):
                  ?>
                  <li class="has-submenu">
                    <a href="gestorEventos.php"><i class="ion ion-md-calendar"></i> Gestor Eventos</a>
                  </li>
                  <li class="has-submenu">
                    <a href="../controlescolar/index.php"><i class="fas fa-book-reader"></i> Control Escolar</a>
                  </li>
                  <?php endif ?>
                  <?php if($_SESSION['usuario']['idTipo_Persona'] == 4): ?>
                    <li class="has-submenu">
                      <a href="alumnos.php"><i class="ion ion-md-calendar"></i> Gestionar Alumnos</a>
                    </li>
                    
                    <li class="has-sumenu">
                      <a href="../hoteles/index.php"><i class="fas fa-plane-departure"></i>Visita Alumnos</a>
                    </li>
                    <?php endif ?>
                  <li class="has-submenu">
                    <a href="#"><i class="fa fa-calculator"></i> Comisiones</a>
                  </li>
                  <li class="has-submenu">
                    <a href="#" onclick="init_data()"><i class="fas fa-sync"></i></a>
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
                  <div class="col-12 mb-2">
                    <h4 class="page-title m-0">
                      <span tab-target="comisiones" class="tab_active">
                        <i class="fas fa-book-reader"></i> Comisiones
                      </span>
                      <?php if($_SESSION["usuario"]['idTipo_Persona'] == 4): ?>
                      <span tab-target="carreras">
                        <i class="fa fa-cog"></i> Carreras
                      </span>
                      <?php endif; ?>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12" id="tab_concentrado_comisiones">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- CONTENEDOR DE TABS -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" id="home-tab" href="#home" role="tab" aria-controls="home"  aria-selected="false" data-target="#home">
                            <span class="d-block d-sm-none"><i class="fa fa-wallet"></i></span>
                            <span class="d-none d-sm-block">Concentrado</span>
                          </a>
                        </li>
                        <li class="nav-item d-none">
                          <a class="nav-link" data-toggle="tab" id="profile-tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true" data-target="#profile">
                            <span class="d-block d-sm-none"><i class="fa fa-history"></i></span>
                            <span class="d-none d-sm-block">Prospectos</span>
                          </a>
                        </li>
                      </ul>

                      <!-- FIN CONTENEDOR DE TABS -->
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <div class="col-sm-12 TBNR table-responsive">
                            <div class="row mb-3">
                              <div class="col">
                                <h4>Ejecutivas</h4>
                              </div>
                              <div class="col bg-dark">
                                <span class="bg-secondary">
                                  <h4 class="text-white">Periodo consultado: <span id="periodo_consultado"></span></h4>
                                </span>
                              </div>
                            </div>

                            <table id="table_ejecutivas" class="table table-striped table-bordered dt-responsive nowrap w-100">
                              <thead>
                                <tr>
                                  <th>Nombre</th>
                                  <th>Comisión</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="row tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              
                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;" data-order='[[ 0, "desc" ]]'>
                                <thead>
                                  <tr>
                                    <th>Registro</th>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Código</th>
                                    <th>Promocional</th>
                                    <th>Pago</th>
                                    <th>Asistencia</th>
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
            <div class="col-sm-12" id="tab_concentrado_carreras" style="display:none;">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12 TBNR table-responsive">
                      <table class="table table w-100" id="tabla_carreras">
                        <thead>
                          <th>Carrera</th>
                          <th>Monto</th>
                          <th></th>
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

      <!-- end wrapper -->

      <!-- todos los modal -->
        <div class="modal fade" id="modal_comision" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h4 class="modal-title">Ver estatus de comisiones</h4>
              </div>
              
              <h4 class="mx-auto mb-0"><b id="lbl_persona_seguimiento"></b></h4>
              
              <div class="modal-body pt-0">
                <div class="row">
                  <div class="col-12">
                    <input type="hidden" id="ejecutiva_id">
                    <label for="">Periodo a consultar</label>
                    <select id="select_periodo_comision" class="form-control">
                      <option selected value=''>Selecciones un periodo</option>
                      
                    </select>
                  </div>

                  <div class="col-6 ml-auto mt-3">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Periodo</th>
                          <th>Estatus</th>
                          <th>Corte</th>
                        </tr>
                      </thead>
                      <tbody id="tbody_comision">
                        <tr>
                          <td>Enero 2021</td>
                          <td>Pendiente</td>
                          <td>$ 200.00</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-12">
                    <table class="table">
                      <thead>
                        <th>Prospecto</th>
                        <th>Interés</th>
                        <th>Comisión</th>
                      </thead>
                      <tbody id="body_table_comisiones">

                      </tbody>
                    </table>
                    <button class="btn btn-block btn-primary d-none" id="btn_corte">
                      <i class="fa fa-file-text-o"></i>
                      Generar corte
                    </button>
                  </div>
                </div>
              
              </div>

              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_configurar" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h4 class="modal-title">Editar monto de comisión.</h4>
              </div>
              
              <h4 class="mx-auto mb-0"><b id="lbl_persona_seguimiento"></b></h4>
              
              <div class="modal-body pt-0">
                <form id="form_actualizar">
                  <div class="row">
                    <div class="col-12">
                      <input type="hidden" name="id_carrera" id="id_carrera">
                      <label>Monto de comisión.</label>
                      <input type="tel" placeholder="Monto de la comisión" name="inp_monto" id="inp_monto" class="form-control moneyFt" data-prefix="$ " >
                    </div>
                  </div>
                  <div class="row text-right">
                    <div class="col-12 mt-3">
                      <button class="btn btn-primary">Guardar</button>
                    </div>
                  </div>
                </form>
              
              </div>

              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

      <!-- fin todos los modal -->

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
        const fecha_actual = new Date("<?php echo(date("Y-m-d"));?>");
        const fecha_otr = new Date("<?php echo(date("Y-m-d H:i:s"));?>");
        const fecha_actual_str = fecha_actual.toLocaleString();

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
    

        $(".onlyNumer").on('keypress',function(evt){
          if (evt.which < 46 || evt.which > 57){
            evt.preventDefault();
          }
        })
      </script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/mkt-edu/comisiones.js"></script>
      <?php if($_SESSION["usuario"]['idTipo_Persona'] == 4): ?>
      <script src="../assets/js/mkt-edu/configuracion.js"></script>
      <?php endif; ?>
      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>
