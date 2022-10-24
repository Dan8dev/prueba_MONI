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
  if (!isset($_SESSION["alumno_general"])) {
    header('Location: index.php');
    die();
  }
  $usr = $_SESSION["alumno_general"];

  require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION["alumno_general"]['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);

  $usuario['data']['instituciones'] = $afiliados->obtener_instituciones_afiliados($usuario['data']['id_prospecto'])['data'];

  /**/
?>

<!DOCTYPE html>
<html lang="en">
<?php require 'plantilla/header.php'; ?>
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
  <div class="br-pageheader pd-y-15 pd-l-20">
    <nav class="breadcrumb pd-0 mg-0 tx-16">
      <a class="breadcrumb-item" href="index.php">INICIO</a>
      <a hred="javascript:void(0)" class="breadcrumb-item active text-primary">AYUDA</a>
    </nav>
  </div><!-- br-pageheader -->
  <div>
    <h4 class="tx-gray-800 mg-b-5"></h4>
    <center>
      <img class="img-fluid" src="https://conacon.org/moni/assets/images/ayuda-general.jpeg" alt="ayuda1" class="img-fluid">
    </center>
    
    <div class="br-pagebody mt-0" id="sect-talleres">
      
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

  <script src="../js/sweetalert.min.js"></script>
  <script src="../js/bracket.js"></script>

  <script src="../../assets/pages/qrcode.js"></script>
  <script src="../../assets/pages/qrcode.min.js"></script>

  <script>
    const currencyF = { style: 'currency', currency: 'USD' };
    const moneyFormat = new Intl.NumberFormat('en-US', currencyF);
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    userInfo = JSON.parse('<?php echo json_encode($usuario); ?>');

  </script>
  </body>

</html>
