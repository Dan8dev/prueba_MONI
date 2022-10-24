<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 6 && $_SESSION["usuario"]['idTipo_Persona'] != 4){
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
    <link href="../assets/css/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- DataTables (Tablas) CSS -->
    <link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	  
    <!-- Sweet Alert 2-->
    <link href="../assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">
	  <link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <style>
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
        <div class="mx-4">

          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-6 col-md-12 mb-2">
                    <h4 class="page-title m-0">
                        <i class="ti-briefcase"></i> CAJA
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
                <!-- CONTENEDOR DE TABS -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" id="home-tab" href="#home" role="tab" aria-controls="home"  aria-selected="false" data-target="#home">
                        <span class="d-block d-sm-none"><i class="fa fa-address-book"></i></span>
                        <span class="d-none d-sm-block">Registrar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" id="profile-tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true" data-target="#profile">
                        <span class="d-block d-sm-none"><i class="fa fa-address-card"></i></span>
                        <span class="d-none d-sm-block">Generar Link</span>
                        </a>
                    </li>
                </ul>

                      <!-- FIN CONTENEDOR DE TABS -->
                <div class="tab-content bg-light">
                    <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <!-- <div class="row">
                        <div class="col">
                          <button class="btn btn-primary" id="btn_show" onclick="hide(this)">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                          </button>

                          <button class="btn btn-primary" id="btn_hide" onclick="show(this)" style="display:none">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                          </button>
                        </div>
                      </div> -->
                      <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4 mt-5" id="form_container">
                          <div class="border rounded px-2 py-1" >
                            <form id="nuevo_registro" autocomplete="on">
                                <div class="col-sm-12 mb-2">
                                    <label for="">Concepto</label>
                                    <input type="text" class="form-control special" id="inp_concepto" name="inp_concepto" required maxlength="200">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label for="">Cliente</label>
                                    <input type="text" class="form-control special" id="inp_cliente" name="inp_cliente" required maxlength="200">
                                  </div>
                                  <div class="col-sm-12 mb-2">
                                  <label for="">Instituto</label>
                                  <select class="form-control" name="instituto" id="instituto" required>
                                    <option value="IESM">Instituto de Estudios Superiores en Medicina</option>
                                    <option value="UDC">Universidad del conde</option>
                                    <option value="CONACON">Colegio nacional de consejeros</option>
                                  </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label for="">Monto</label>
                                    <input type="number" class="form-control special" id="inp_monto" name="inp_monto" required step="any">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label for="">Moneda</label>
                                    <select name="moneda" id="moneda" class="form-control">
                                      <option value="MXN">MXN</option>
                                      <option value="USD">USD</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label for="">Comentario</label>
                                    <textarea type="text" class="form-control" id="inp_comentario" name="inp_comentario"> </textarea>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <button class="btn btn-primary btn-block" type="submit">Guardar</button>
                                </div>
                            </form>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-8 table-responsive TBNR">
                            <!-- <div class="row">
                              <div class="col text-center mt-1 mb-4">
                                <button class="btn btn-outline-primary btn_filter" term="MXN">MXN</button>
                                <button class="btn btn-outline-success btn_filter" term="USD">USD</button>
                                <button class="btn btn-outline-secondary btn_filter" term="">TODO</button>
                              </div>
                            </div> -->
                            <table id="tabla_general_registrados" class="table table-striped table-bordered dt-responsive " style="border-collapse: collapse; width: 100%;" data-order='[[0,"desc"]]'>
                                <thead>
                                    <tr>
                                        <th>Num</th>
                                        <th>Instituto</th>
                                        <th>Cliente</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Moneda</th>
                                        <th>Fecha de registro</th>
                                        <th>Comentario</th>
                                        <th>Registró</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                      </div>
                    </div> <!-- FIN TAB HOME -->
                    <div class="row tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <button id="mostrar_formulario_link" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-crear-link" onclick="$('#modalLink')[0].reset()">Crear link de pago</button>
                    </div> <!-- FIN TAB PROFILE -->
                </div>
            </div>

          </div> <!-- end container-fluid -->
        </div>
      </div>
      <!-- end wrapper -->
      <!-- Modals -->
      <div class="modal fade modal-crear-link" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title m-0" id="myLargeModalLabel">Crear link de pago</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                      <div class="card">
                        <div class="card-body">
                            <h4 class="m-t-0 m-b-30">Ingresa la siguiente información</h4>
                            <form id="modalLink" method="GET" action="linkpago.php" target="_blank">
                                <div class="form-group row">
                                    <label for="id_concepto" class="col-sm-3 control-label">Selecciona la cuenta de depósito</label>
                                    <div class="col-sm-9">
                                      <select class="form-control" name="id_concepto" id="id_concepto" required>
                                        <option value="" selected>Seleccione...</option>
                                        <option value="31">Instituto de Estudios Superiores en Medicina (Banorte)</option>
                                        <option value="185">Universidad del conde (Banorte)</option>
                                        <option value="24">Colegio nacional de consejeros  (Inbursa)</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nombrelink" class="col-sm-3 control-label">Nombre del producto</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombrelink" name="nombre_concepto" placeholder="Ingresa el nombre del concepto" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="precio" class="col-sm-3 control-label">Moneda</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="Moneda">
                                          <option value="mxn" selected>MXN</option>
                                          <option value="usd">USD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="precio" class="col-sm-3 control-label">Precio</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio del concepto" required step="any">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nombreclientespei" class="col-sm-3 control-label">Nombre cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombreclientespei" name="nombreclientespei" placeholder="Ingresa el nombre del cliente" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="emailclientespei" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="emailclientespei" name="emailclientespei" placeholder="Ingresa el email del cliente" required>
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Crear link</button>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- card-body -->
                    </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      <!-- End Modals -->

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
      <script src="../assets/js/template/jquery.numeric.js"></script>

      <!--Sweet Alert 2-->
	    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	    <script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>

      <!-- select2 -->
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        
      <!-- Latest compiled and minified JavaScript -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


      <script src="../assets/pages/clipboard.js"></script>
      <script type="text/javascript">
        
      </script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/planpagos/caja.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
      <!-- <script src="../assets/plugins/jsPDF/dist/jspdf.min.js"></script> -->

      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
      <script>
        function hide(elm){
          $(elm).hide();
          $("#btn_hide").show();
          $("#form_container").fadeOut();
        }
        function show(elm){
          $(elm).hide();
          $("#btn_show").show();
          $("#form_container").fadeIn();
        }
        $(".btn_filter").on('click', function(){
          $("#tabla_general_registrados_filter input").val($(this).attr('term'));
          $("#tabla_general_registrados_filter input").keyup();
        });
      </script>
    </body>
    </html>
