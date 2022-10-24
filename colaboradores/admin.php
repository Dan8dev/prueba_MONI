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
                  <div class="col-6 col-md-3 mb-2">
                    
                  </div>
                  <div class="col-6 col-md-3 mb-2">
                    <!-- <button type="button" onclick="generarCorte()" class="btn btn-primary waves-effect waves-light">Generar corte</button> -->
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
                            <span class="d-none d-sm-block">Parámetros</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fa fa-history"></i></span>
                            <span class="d-none d-sm-block">Embajadores / Voceros</span>
                          </a>
                        </li>
                      </ul>
                      <div class="tab-content bg-light">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <div class="row">
                            <div class="col-sm-12">
                              <a id="alumnos_faltos" style="display:none" href="javascript:void(0)">Alumnos faltos de pago</a>
                              <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Tipo usuario</th>
                                    <th>Carrera</th>
                                    <th>Minimo</th>
                                    <th>Máximo</th>
                                    <th>Porcentaje</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="row">
                            <div class="col-12 text-right">
                              <button type="button" class="btn btn-primary waves-effect waves-light" onclick="agregarUsuario()">Agregar Usuario</button>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 TBNR table-responsive">
                              <table id="datatable_colaboradores" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Tipo usuario</th>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Correo</th>
                                    <th>Celular</th>
                                    <th>Institución</th>
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
            </div>

          </div> <!-- end container-fluid -->
        </div>
      </div>
      <!-- end wrapper -->
      <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="myModalLabel">Datos de usuario</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <form id="form_new_vocero">
                <input type="hidden" name="user_val" id="user_val">
                <div class="row">
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_nombre">Nombre</label>
                    <input type="text" class="form-control for_code" id="inp_nombre" name="inp_nombre" required>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_aPaterno">Apellido Paterno</label>
                    <input type="text" class="form-control for_code" id="inp_aPaterno" name="inp_aPaterno" required>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_aMaterno">Apellido Materno</label>
                    <input type="text" class="form-control for_code" id="inp_aMaterno" name="inp_aMaterno" required>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_Correo">Correo</label>
                    <input type="email" class="form-control" id="inp_Correo" name="inp_Correo" required>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_telefono">telefono</label>
                    <input type="text" class="form-control" id="inp_telefono" name="inp_telefono" minlength="10" maxlength="10" required>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_Institucion">Institucion</label>
                    <select id="inp_Institucion" name="inp_Institucion" class="form-control">

                    </select>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_Tipo">Tipo</label>
                    <select id="inp_Tipo" name="inp_Tipo" class="form-control">

                    </select>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_Codigo">Codigo</label>
                    <input type="text" class="form-control" id="inp_Codigo" readonly>
                  </div>
                  <div class="col-sm-12 text-right mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

      <div id="ComisionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ComisionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="myModalLabel">Parametros de comisión</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <form id="form_comision">
                <input type="hidden" name="comision_val" id="comision_val">
                <div class="row">
                  <div class="col-sm-12">
                    <label for="inp_minimo">Minimo</label>
                    <input type="number" class="form-control onlyNum" id="inp_minimo" name="inp_minimo" required  min="1">
                  </div>
                  <div class="col-sm-12">
                    <label for="inp_maximo">Máximo</label>
                    <input type="number" class="form-control onlyNum" id="inp_maximo" name="inp_maximo" required min="1">
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <label for="inp_tipo_comision">Tipo</label>
                    <select id="inp_tipo_comision" name="inp_tipo_comision" class="form-control">

                    </select>
                  </div>
                  <div class="col-sm-12">
                    <label for="inp_porcentaje">Porcentaje</label>
                    <input type="number" class="form-control onlyNum" id="inp_porcentaje" name="inp_porcentaje" required min="1" step="any">
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 text-right mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
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
      <script src="../assets/js/colaboradores/admin-colaborador.js"></script>

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