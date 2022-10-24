<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
session_start();
if(!isset($_SESSION["usuario"])){
  if(!isset($_GET['codigo_acc']) && !isset($_GET['mail'])){
    header("Location: index.php");
    die();
  }else{
      require_once '../assets/data/Model/conexion/conexion.php';
	  require_once '../assets/data/Model/prospectos/prospectosModel.php';
      require_once '../assets/data/Model/eventos/eventosModel.php';
      $prospM = new Prospecto();
      $usuario = $prospM->validar_acceso_asistente([
              'inpCorreo'=> $_GET['mail'],
              'inpPassw' => $_GET['codigo_acc']
            ]);

        $resp = [];
        $estatus_acceso_ok = [0, 1, 2, null];
        if($usuario["estatus"] == 'ok' && !empty($usuario['data']) && in_array($usuario['data'][0]['etapa_seguimiento'], $estatus_acceso_ok)){
          $usr = $usuario['data'][0];

          session_start();
          
          $usr['perfil'] = $prospM->consultar_pagos_prospectos($usr['idAsistente'], $usr['idEvento'])['data'];
          $_SESSION['usuario'] = $usr;
        }else{
          $result['estatus'] = "error";
        }

      if($result['estatus'] == 'error'){
        header("Location: index.php");
      }

  }
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
        border-radius: 20px;
      }
      .hoverable:hover{
        background-color: #eee;
      }
      .prices-list:hover{
        box-shadow: 0px 5px 8px -3px;
      }
      .prices-list{
        cursor: pointer;
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
                  <img src="../assets/images/logo-cona-light.png" class="logo-lg" alt="" height="26">
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
                       <?php echo $usuario["nombre"]; ?> <span class="mdi mdi-chevron-down font-15"></span>
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a href="javascript:void(0)" class="dropdown-item"> Profile</a></li>
                      <li class="dropdown-divider"></li>
                      <li><a href="../log-out-asist.php" class="dropdown-item"> Logout</a></li>
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
        <div class="container-fluid">
          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-sm-12 col-md-6 mb-2">
                    <h4 class="page-title m-0"><i class="fas fa-calendar-day"></i> Evento <u><?php echo $usuario['titulo']; ?></u></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <pre style="display: none;">
            <?php print_r($usuario); ?>
          </pre>
            <?php 
              if(trim($usuario['evento']['nombreClave']) == 'cismac-congreso'){
                require 'html_congreso.php';
              }else if($usuario['evento']['nombreClave'] == 'operador-terapeutico'){
                require 'html_evento_hoy.php';
              }
            ?>
            
          </div> <!-- end container-fluid -->
        </div>
      </div>
      <!-- end wrapper -->


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
      <!-- Modals -->
      <div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="lbl_modal-confirm" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="lbl_modal-confirm">Confirmar talleres seleccionados.</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" >
              <h3>
                Confirma la siguiente lista de talleres para reservar su lugar?
              </h3>
              <ul id="lista_talleres_selected">
                
              </ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
              <button onclick="$('#form_apartar_talleres').submit();" class="btn btn-success waves-effect">Continuar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>
      <!-- End Modals -->
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
      <script src="../assets/pages/datatables.init.class.js"></script>

      <script src="../assets/pages/clipboard.js"></script>

      <script src="../assets/pages/qrcode.js"></script>
      <script src="../assets/pages/qrcode.min.js"></script>

      <script src="https://www.paypal.com/sdk/js?client-id=AfcFm_FJBcwJ0-Urg7O8Jb_E0LpsGoO2_Oy6CFCNHWTIrDm09VNo9kCl6VWiYT9GrlT2B_0f-LYwNHQD&currency=MXN" data-sdk-integration-source="button-factory"></script>
      <?php 
        if($usuario['evento']['nombreClave'] == 'cismac-congreso'){
          if(empty($usuario['perfil'])){ # si tiene un perfil == registro de pago
            echo "<script src='pagos_congreso.js'></script>";
          }else{
            echo "<script src='panel_congreso.js'></script>";
          }
        }elseif ($usuario['evento']['nombreClave'] == 'operador-terapeutico') {
          echo "<script src='pagos_evento.js'></script>";
        }
      ?>

      <script type="text/javascript">
        new ClipboardJS('.clpb', {
          text: function(trigger) {
              return trigger.getAttribute('aria-label');
          }
        });
      </script>

      <script src="../assets/js/template/app.js"></script>
      
      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>