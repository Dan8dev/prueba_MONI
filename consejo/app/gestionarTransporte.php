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
  $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

  $datetime1 = new DateTime($fechaactual);
  $datetime2 = new DateTime($fechafinmembresia);
  $interval = $datetime1->diff($datetime2);
  $diasrestantes= substr($interval->format('%R%a días'), 1);
  $dias = rtrim($diasrestantes, ' días');
  if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
    header('Location: pagos.php');
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
	<?php require 'plantilla/header.php'; ?>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Gestionar Transporte</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
      <?php if ($dias<31) {
          # code...
        ?>
        <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?> <a class="text-dark" href="pagos.php">Pague aqui</a></strong> 
        </div><!-- alert -->
        <?php } ?>
        <h4 class="tx-gray-800 mg-b-5">Cupones de transporte</h4>
        <p class="mg-b-0">Fecha máxima de reserva: 14 Noviembre 2021</p>
      </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">         
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">LLena correctamente el siguiente formulario</h6>
            <p class="mg-b-25 mg-lg-b-50">Realizada tu selección se genera un código QR.</p>
		        <div class="form-layout form-layout-1" id="layout-transporte" style="display:none;">
              <form id="form-solicitar-transporte">
                <p class="mg-b-0">Resrva transporte</p>
                <div class="row mg-b-25">
                    <div class="col-lg-4">
                        <div class="form-group">
                        <label class="rdiobox">
                            <input name="radio_reserv_transporte" value="si" type="radio">
                            <span>Si</span>
                        </label>
                        </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input name="radio_reserv_transporte" value="no" type="radio">
                                <span>No</span>
                            </label>
                        </div>
                    </div><!-- col-4 -->
                </div><!-- row -->
                <div class="form-layout-footer">
                    <button class="btn btn-info" type="submit">Guardar</button>
                    <!-- <button class="btn btn-secondary" type="button">Cancelar</button> -->
                </div><!-- form-layout-footer -->
              </form>
            </div><!-- form-layout --> 

            <div class="form-layout form-layout-1 mg-t-25" style="display:none;" id="content_qr_transporte">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">ACCESO A TRANSPORTE</h6>
                <div class="row mg-b-25">
                    <div class="col-lg-4">
                        <div class="">
                            <div class="card shadow-base card-body p-0">
                              <div class="card bd-0" id="qrcode">
                                <input  id="text" type="hidden" value=""/>
                                <div class="img_credencial" id="qrcode"></div>
                              </div><!-- card -->
                            </div><!-- card -->
                        </div> 
                    </div><!-- col-4 -->
                    <div class="col-lg-4">
                        <div class="form-group">
                            <p class="mg-b-25 mg-lg-b-50">Datos:</p>
                            <p class="mg-b-25 mg-lg-b-50"><b> Número de transporte: <span id="lbl_transporte"></span></b></p>
                            <!-- Número de transporte 3</b> Valido: 22/10/2021 -->
                            <p class="mg-b-25 mg-lg-b-50"><b> Número de asiento: <span id="lbl_asiento"></span></b></p>
                        </div>
                    </div><!-- col-4 -->
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

    <script src="script/qrcode.js"></script>
    <script src="script/qrcode.min.js"></script>
    
    <!-- Script para esta pantalla -->
    <script src="script/cole_alumno_transporte.js"></script>
    <script src="../js/sweetalert.min.js"></script>

    <script src="../js/bracket.js"></script>
    <script>
      const user_info = <?php echo json_encode($usuario['data']); ?>;
      $(document).ready(function(){
        cargar_transportes();
      })
      function mayusculas(e){
        e.value = e.value.toUpperCase();
      } 
      var qrcode = new QRCode(document.getElementById("qrcode"), {
        width : 110,
        height : 110
      });

      function makeCode () {    
        var elText = document.getElementById("text");
        if (!elText.value) {
          alert("Input a text");
          elText.focus();
          return;
        }
        qrcode.makeCode(elText.value);
      }
      // makeCode();
      // $("#text").
      // on("blur", function () {
      //   makeCode();
      // }).

      // on("keydown", function (e) {
      //   if (e.keyCode == 13) {
      //     makeCode();
      //   }
      // });
    </script> 

  </body>
</html>
