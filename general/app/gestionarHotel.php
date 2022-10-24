<?php
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
} 

  session_start();
  if (!isset($_SESSION["alumno_general"])) {
    header('Location: index.php');
    die();
  }
  $usr = $_SESSION["alumno_general"];
	require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION["alumno_general"]['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
  
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
	<?php require 'plantilla/header.php'; ?>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Gestionar Hotel</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
      
        <h4 class="tx-gray-800 mg-b-5">¡Reserva tu habitación!</h4>
        <p class="mg-b-0">Fecha máxima de reserva: 14 Noviembre 2021</p>

      </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Pasada esta fecha si requieres habitación el sistema la asignará en automatico</h6>
          <p class="mg-b-25 mg-lg-b-50">LLena correctamente el siguiente formualrio</p>
          <div id="solicitar_reservacion">

          </div>

          <div class="form-layout form-layout-1 mg-t-25" style="display:none;">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Usted tiene una solicitud para compartir habitación</h6>
            <div id="list_solicitudes">

            </div>
              
          </div><!-- form-layout --> 

          <div class="form-layout form-layout-1 mg-t-25" id="content_hotel_info" style="display:none;">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">CONFIRMACIÓN DE RESERVACIÓN</h6>
            <div class="row mg-b-25">
              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Hotel: CITY EXPRESS </label>
                  <label class="form-control-label">Habitación doble: <span id="lbl_habitacion"></span> </label>
                  <label class="form-control-label">DIRECCIÓN: Blvrd Cristobal Colon 391, Jardines de las Animas, 91190 Xalapa-Enríquez, Ver. </label>
                  <label class="form-control-label">Compartida con:  </label>
                  <input class="form-control" type="text" name="address" Value="UDC2398-8" readonly="true">
                </div>
                <div class="auto">
                  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3760.614474845213!2d-96.87937918537031!3d19.51521558683737!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85db3231dd155f8d%3A0x628f6c3e9b4a123f!2sCity%20Express%20Xalapa!5e0!3m2!1ses!2smx!4v1634688104858!5m2!1ses!2smx" width="auto" height="auto" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>  
              </div><!-- col-8 -->
            </div><!-- row -->
          </div><!-- form-layout --> 

        </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->
      <footer class="br-footer">
        <div class="footer-left">
          <div class="mg-b-2">Copyright &copy; 2021. CONACON TI. Todos los derechos reservados.</div>
        </div>
        <div class="footer-right d-flex align-items-center">
          <span class="tx-uppercase mg-r-10">SÍGUENOS:</span>
          <a target="_blank" class="pd-x-5" href="#"><i class="fa fa-facebook tx-20"></i></a>
          <a target="_blank" class="pd-x-5" href="#"><i class="fa fa-twitter tx-20"></i></a>
        </div>
      </footer>
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
    <!-- Script para esta pantalla -->
    <script src="script/cole_alumno_matching.js"></script>
    <script src="../js/sweetalert.min.js"></script>

    <script src="../js/bracket.js"></script>
    <script>
      const user_info = <?php echo json_encode($usuario['data']); ?>;
      function mayusculas(e) {
          e.value = e.value.toUpperCase();
        } 
    </script> 

  </body>
</html>
