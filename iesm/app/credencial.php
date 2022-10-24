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
	require('plantilla/required.php');
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:url" content="https://www.facebook.com/udconde/">
    <meta property="og:title" content="Universidad del Conde">
    <meta property="og:description" content="En Universidad del Conde, día con día nos damos a la tarea de sembrar en nuestros alumnos un interés genuino por influir de forma positiva en el mundo, por ser el agente de cambio que nuestro México necesita y trabajando todos los días sin quitar la vista de nuestro objetivo.">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="Universidad del Conde" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://moni.com.mx/UDC" />
    <meta property="og:image" content="https://moni.com.mx/consejo/app/img/logoMetas.png" />
    <meta property="og:description" content="" />
    <!-- Meta -->
    <meta name="description" content="Universidad del Conde">
    <meta name="author" content="Universidad del Conde Desarrollo Tecnológico">

    <title>Universidad del Conde</title>

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
          <span class="breadcrumb-item active">Identidad Corporativa</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">Solicitar Credencial</h4>
        <p class="mg-b-0">Llena toda la información y da click en Solicitar</p>
      </div>

      
	  <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="clave alert alert-info">
                <strong>Fotos tamaño ovalo. <br>Instrucciones:</strong> Te informamos que las fotografias que presentes deben de cumplir lo siguiente:<br>
                * 12 fotografías <br> * Tamaño título <br> * Blanco y negro <br> * Fondo blanco <br> * (6 x 9 cm.) con retoque en papel mate y adherible. (Vestimenta formal) <br>
                <strong>A continuación, adjunta el archivo digital de tu foto. <br> Mandar foto física a: Carretera Antigua a Xalapa - Coatepec km 5.5 Esquina Mariano Escobedo Coatepec Veracruz.</strong>
            </div>
            <div class="form-group row justify-content-center">
                <label for="fotosOvalo" class="col-sm-2 control-label text-center">Fotos tamaño ovalo (jpeg, jpg, png)</label>
                <div class="col-sm-8">
                    <input class="form-control" type="file" name="fotoOvalo" id="5" accept=".jpeg, .jpg, .png" required>
                </div>
                <div class="col-md-6 col-xl-1" id="spinnerDoc5" style="display: none;">
                    <div class="d-flex bg-gray-20 ht-40 pos-relative align-items-center">
                        <div class="sk-circle">
                        <div class="sk-circle1 sk-child"></div>
                        <div class="sk-circle2 sk-child"></div>
                        <div class="sk-circle3 sk-child"></div>
                        <div class="sk-circle4 sk-child"></div>
                        <div class="sk-circle5 sk-child"></div>
                        <div class="sk-circle6 sk-child"></div>
                        <div class="sk-circle7 sk-child"></div>
                        <div class="sk-circle8 sk-child"></div>
                        <div class="sk-circle9 sk-child"></div>
                        <div class="sk-circle10 sk-child"></div>
                        <div class="sk-circle11 sk-child"></div>
                        <div class="sk-circle12 sk-child"></div>
                        </div>
                    </div><!-- d-flex -->
                </div><!-- col-4 -->
                <button type="button" class="btn btn-primary waves-effect waves-light mr-2" id="btnDoc5" onclick="guardarDocumento(this, 5, <?php echo $idusuario; ?>)">Enviar</button>
                <button type="button" class="btn btn-secondary waves-effect waves-light mr-2" id="btnEnviado5" style="display: none;" disabled>Enviado</button>
            </div>    
        </div><!-- br-section-wrapper -->
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
    <script src="script/qrcode.js"></script>
    <script src="script/qrcode.min.js"></script>
    
    <script src="script/proximos-eventos.js"></script>

    <script src="../js/bracket.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="script/pdf.js"></script>
    
  </body>
</html>
