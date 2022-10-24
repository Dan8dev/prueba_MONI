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

  $fechafinmembresia = $afiliados->fechafinmembresia($usuario['data']['idAsistente']);
  $fechaactual = date('Y-m-d H:i:s');
  //var_dump( $fechafinmembresia['data']);
  if(isset($fechafinmembresia['data']) && $fechafinmembresia['data'] != false){

    $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];
    $datetime1 = new DateTime($fechaactual);
    $datetime2 = new DateTime($fechafinmembresia);
    $interval = $datetime1->diff($datetime2);
    $diasrestantes= substr($interval->format('%R%a días'), 1);
    $dias = rtrim($diasrestantes, ' días');
    if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
      header('Location: pagos.php');
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Twitter -->
    <meta name="twitter:site" content="@ColegioConacon">
    <meta name="twitter:creator" content="@ColegioConacon">
    <meta name="twitter:card" content="Colegio Nacional de Consejeros">
    <meta name="twitter:title" content="CONACON">
    <meta name="twitter:description" content="Únete a la red más grande de Consejeros">
    <meta name="twitter:image" content="#">
    <!-- Facebook -->
    <meta property="og:url" content="https://www.facebook.com/ColegioConacon/">
    <meta property="og:title" content="CONACON">
    <meta property="og:description" content="Únete a la red más grande de Consejeros">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="CONACON" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://conacon.org/moni/siscon" />
    <meta property="og:image" content="https://conacon.org/moni/siscon/app/img/logoMetas.png" />
    <meta property="og:description" content="Únete a la red más grande de Consejeros" />
    <!-- Meta -->
    <meta name="description" content="Colegio nacional de consejeros. CONACON">
    <meta name="author" content="CONACON TI">

    <title>CONACON</title>

    <!-- vendor css -->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
    <link href="../lib/highlightjs/github.css" rel="stylesheet">
    <link href="../lib/jquery.steps/jquery.steps.css" rel="stylesheet">
	  
	  <link rel="icon" type="imge/png" href="img/favicon.png">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
    <link rel="stylesheet" href="../css/maincalendar.css">
	<?php require 'plantilla/header.php'; ?>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Eventos</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
      <!-- //$dias -->
      <?php if (0<31) {
          # code...
        ?>
        <!--<div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php //echo $diasrestantes?> <a class="text-dark" href="pagos.php">Pague aqui</a></strong> 
        </div> -->
        <?php } ?>
        <h4 class="tx-gray-800 mg-b-5">¡Asiste!</h4>
        <p class="mg-b-0">Reserva tu lugar</p>
      </div>

      <div class="br-pagebody">
        <div class="row">
          <div class="col ">
          <div id='source-calendar'></div>
            </div>
          <div class="col ">         
              <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Eventos</h6>
              <div class="calendario-peventos" id="container-proxmios"> </div>
           </div><!-- br-section-wrapper -->

        </div>

       </div><!-- br-pagebody -->
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
    <script src="../lib/highlightjs/highlight.pack.js"></script>
    <script src="../lib/jquery.steps/jquery.steps.js"></script>
    <script src="../lib/parsleyjs/parsley.js"></script>
    
    <script src="script/proximos-eventos.js"></script>

    <script src="../js/bracket.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src='script/maincalendar.js'></script>
  </body>
</html>
