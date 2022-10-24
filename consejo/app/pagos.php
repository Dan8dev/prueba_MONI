<!-- Iconografía fontawesom  (fa) https://fontawesome.com/ -->

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
  $fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
  $fechaactual= date('Y-m-d H:i:s');
  $fechafinmembresia1=$fechafinmembresia['data']['finmembresia'];
  $tipodemembresia = $fechafinmembresia['data']['id_concepto'];
  $fechaactivacion=$fechafinmembresia['data']['fechapago'];

  $datetime1 = new DateTime($fechaactual);
  $datetime2 = new DateTime($fechafinmembresia1);
  $interval = $datetime1->diff($datetime2);
  $diasrestantes= substr($interval->format('%R%a días'), 1);
  $dias = rtrim($diasrestantes, ' días');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Bracket">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="http://themepixels.me/bracket/img/bracket-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/bracket">
    <meta property="og:title" content="Bracket">
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

    <meta property="og:image" content="http://themepixels.me/bracket/img/bracket-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/bracket/img/bracket-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

     <!-- Meta -->
    <meta name="description" content="Colegio nacional de consejeros. CONACON">
    <meta name="author" content="CONACON TI">
    <title>CONACON</title>

    <!-- vendor css -->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
	  
	  <link rel="icon" type="imge/png" href="img/favicon.png">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
  <?php require 'plantilla/header.php'; ?>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">INICIO</a>
          <span class="breadcrumb-item active">PAGOS</span>
        </nav>
      </div><!-- br-pageheader -->  

      <div class="br-pagebody">
      <?php
        if (rtrim($interval->format('%R%a días'), ' días')<0||rtrim($interval->format('%R%a días'), ' días')==-0) {
          ?>
          <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Tu <strong class="d-block d-sm-inline-block-force"> Membresía</strong> a finalizado <strong class="d-block d-sm-inline-block-force"> renuevala </strong> 
          </div><!-- alert -->
          <?php  
        }
        else { if($dias<31) {
          # code...
        ?>
        <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?> <a class="text-dark" href="pagos.php">Pague aqui</a></strong> 
        </div><!-- alert -->
        <?php } } ?>
        <!-- ########## START: CARDS INFORMATIVOS ########## -->
        <div class="card-body pd-x-25 pd-b-25 pd-t-0">
            <div class="row no-gutters">
              <div class="col-sm-6 col-lg-4">
                <div class="card card-body rounded-0">
                  <h6 class="tx-inverse tx-14 mg-b-5">MEMBRESIA</h6>
                  <p class="tx-10 tx-uppercase tx-medium mg-b-0 tx-spacing-1">Plan</p>
                  <h2 class="tx-inverse tx-bold tx-lato">
                    <span>
                    <?php if ($tipodemembresia==6||$tipodemembresia==7||$tipodemembresia==4) {
                                echo 'ANUAL';
                          } 
                          if ($tipodemembresia==8) {
                            echo 'GRATIS';
                          }
                          if ($tipodemembresia==3 || $tipodemembresia==13) {
                            echo 'SEMESTRAL';
                          }
                    ?>
                  </span>
                  </h2>
                  <div class="d-flex justify-content-between tx-12">
                    <div>
                      <span class="square-10 bg-info mg-r-5"></span> Fecha activación
                      <h5 class="mg-b-0 mg-t-5 tx-bold tx-inverse tx-lato"><?php echo date('d-m-Y', strtotime($fechaactivacion)); ?></h5>
                    </div>
                  </div><!-- d-flex -->
                </div><!-- card -->
              </div><!-- col-3 -->
              <div class="col-sm-6 col-lg-4 mg-t--1 mg-sm-t-0 mg-lg-l--1">
                <div class="card card-body rounded-0 bd-lg-l-0">
                  <h6 class="tx-inverse tx-14 mg-b-5">VENCIMIENTO</h6>
                  <p class="tx-10 tx-uppercase tx-medium mg-b-0 tx-spacing-1">Membresia</p>
                  <h2 class="tx-inverse tx-bold tx-lato">
                    <span><?php echo date('d-m-Y', strtotime($fechafinmembresia1)); ?></span>
                  </h2>
                  <div class="d-flex justify-content-between tx-12">
                    <div>
                      <span class="square-10 bg-info mg-r-5"></span> Plan
                      <h5 class="mg-b-0 mg-t-5 tx-inverse tx-lato tx-bold">
                        <?php if ($tipodemembresia==6||$tipodemembresia==7||$tipodemembresia==4) {
                                echo 'ANUAL';
                              } 
                              if ($tipodemembresia==8) {
                                echo 'GRATIS';
                              }
                              if ($tipodemembresia==3 || $tipodemembresia==13) {
                                echo 'SEMESTRAL';
                              }  
                        ?>
                      </h5>
                    </div>
                  </div><!-- d-flex -->
                </div><!-- card -->
              </div><!-- col-3 -->
              <div class="col-sm-6 col-lg-4 mg-t--1 mg-lg-t-0 mg-lg-l--1">
                <div class="card card-body rounded-0 bd-lg-l-0">
                  <h6 class="tx-inverse tx-14 mg-b-5">Crece</h6>
                  <p class="tx-10 tx-uppercase tx-medium mg-b-0 tx-spacing-1">Actualiza</p>
                  <h2 class="tx-inverse tx-bold tx-lato">
                    <span>Tu membresia</span>
                  </h2>
                  <div class="d-flex justify-content-between tx-12">
                    <div>
                      <span class="square-10 bg-info mg-r-5"></span> Plan
                      <h5 class="mg-b-0 mg-t-5 tx-inverse tx-lato tx-bold">Anual</h5>
                    </div>
                  </div><!-- d-flex -->
                </div><!-- card -->
              </div><!-- col-3 -->
            </div><!-- row -->
            <!-- ########## FIN: CARDS INFORMATIVOS ########## -->
            <!-- ########## INICIO : CARDS BOTONES DE PAGO ########## -->
            <div class="row row-sm mg-t-20">

              <div class="col-lg-6">
                <div class="widget-2">
                  <div class="card shadow-base overflow-hidden">
                    <div class="card-header">
                      <h4 class="card-title">ADQUIRIR MEMBRESIA SEMESTRAL</h4>
                    </div><!-- card-header -->
                    <div id="alert-pago-semestral" class="alert alert-success" style="display:none" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>

                    </div><!-- alert -->
                    <div class="card-body pd-0 bd-color-gray-lighter">
                      <div class="row no-gutters tx-center">
                        <div class="col-12 col-sm-12 pd-y-20 tx-left">
                          <p class="pd-l-20 tx-12 lh-8 mg-b-0">Botón de pago Paypal</p>
                        </div><!-- col-4 -->
                        <div class="row">
                          <div class="col-sm-6">
                            <p class="card-text"><small class="text-muted">Obtén tu membresia semestral por:</small></p>
                          </div>
                          <div class="col-sm-6">
                            <p class="card-text text-success">$1,000.00 <small>MXN</small></p>
                          </div>
                          <div class="col-12 pd-x-40">
                            <!-- BOTON PARA PAGO -->
                            <div id="smart-button-container">
                              <div style="text-align: center;">
                                <div id="paypal-button-container-plan-semestral"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div><!-- row -->
                    </div><!-- card-body -->
                    <!-- <div class="card-body pd-0">
                      <div id="rickshaw1" class="wd-100p ht-150 rounded-bottom"></div>
                    </div><!-- card-body --> 
                  </div><!-- card -->
                </div><!-- widget-2 -->
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="widget-2">
                  <div class="card shadow-base overflow-hidden">
                    <div class="card-header">
                      <h6 class="card-title">ADQUIRIR MEMBRESIA ANUAL</h6>
                    </div><!-- card-header -->
                    <div id="alert-pago-anual" class="alert alert-success" style="display:none" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>

                    </div><!-- alert -->
                    <div class="card-body pd-0 bd-color-gray-lighter">
                      <div class="row no-gutters tx-center">
                        <div class="col-12 col-sm-12 pd-y-20 tx-left">
                          <p class="pd-l-20 tx-12 lh-8 mg-b-0">Botón de pago Paypal</p>
                        </div><!-- col-4 -->
                        <div class="row">
                          <div class="col-sm-6">
                            <p class="card-text"><small class="text-muted">Obtén tu membresia anual por:</small></p>
                          </div>
                          <div class="col-sm-6">
                            <p class="card-text text-success">$2,000.00 <small>MXN</small></p>
                          </div>
                          <div class="col-12 pd-x-40">
                            <!-- BOTON PARA PAGO -->
                            <div id="smart-button-container">
                              <div style="text-align: center;">
                                <div id="paypal-button-container-plan-anual"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div><!-- row -->
                    </div><!-- card-body -->
                    <!-- <div class="card-body pd-0">
                      <div id="rickshaw1" class="wd-100p ht-150 rounded-bottom"></div>
                    </div><!-- card-body -->
                  </div><!-- card -->
                </div><!-- widget-2 -->
              </div><!-- col-6 -->
            </div><!-- row -->
            <!-- ########## FIN : CARDS BOTONES DE PAGO ########## -->

          </div><!-- card-body -->
        </div><!-- br-pagebody -->
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

    <script src="../js/bracket.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AfcFm_FJBcwJ0-Urg7O8Jb_E0LpsGoO2_Oy6CFCNHWTIrDm09VNo9kCl6VWiYT9GrlT2B_0f-LYwNHQD&currency=MXN" data-sdk-integration-source="button-factory"></script>
    <script src="script/pagoafiliacion.js"></script>
  </body>
</html>
