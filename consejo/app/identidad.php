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
          <span class="breadcrumb-item active">Identidad Corporativa</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">¡DESCARGA!</h4>
        <p class="mg-b-0">Tu credencial y tus tarjetas de presentación</p>
      </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Credencial</h6>
		  <p>Da click en las imagenes para descargar</p>
          <div class="row">
			<div class="col-sm-6">
				<a onclick="buscarDatosCredencial('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">  
				 <img src="https://conacon.org/moni/siscon/img/credencial1.jpg" class="img-fluid mouse-pointt">
				</a>
			</div>
			<div class="col-sm-6">
				<a onclick="buscarDatosCredencial('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">  
				<img src="https://conacon.org/moni/siscon/img/credencial2.jpg" class="img-fluid mouse-pointt">
				</a>
			</div>
          </div>   
        </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->
	  
	  <div class="br-pagebody">
        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Tarjeta de presentación</h6>
		  <p>Da click en las imagenes para descargar</p>
          <div class="row">
			<div class="col-sm-6">
        <a onclick="buscarDatosTarjeta('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">
       	  <img src="https://conacon.org/moni/siscon/img/tarjetafondo1.jpg" class="img-fluid mouse-pointt">
        </a>
			</div>
			<div class="col-sm-6">
			   <a onclick="buscarDatosTarjeta('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">
       	 <img src="https://conacon.org/moni/siscon/img/tarjetafondo2.jpg" class="img-fluid mouse-pointt">
			   </a>
			</div>
          </div>    
        </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->

      <div class="br-pagebody">
        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Tarjeta de afiliación</h6>
		  <p>Da click en las imagenes para descargar</p>
          <div class="row">
			<div class="col-sm-6">
        <a onclick="buscarAfiliacion('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">
       	  <img src="https://conacon.org/moni/siscon/img/afiliacion1.jpg" class="img-fluid mouse-pointt">
        </a>
			</div>
			<div class="col-sm-6">
			   <a onclick="buscarAfiliacion('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">
       	 <img src="https://conacon.org/moni/siscon/img/afiliacion2.jpg" class="img-fluid mouse-pointt">
			   </a>
			</div>
          </div>    
        </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->
	  
	  <div class="br-pagebody">
        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">PowerPoint</h6>
		  <p>Da click en las imagenes para descargar</p>
          <div class="row">
			<div class="col-sm-4">
				<a href="https://conacon.org/moni/siscon/img/presentacion.pptx" target="_blank" title="click para descargar">
				  <img src="https://conacon.org/moni/siscon/img/ejemplo1.jpg" class="img-fluid">
				</a>
			</div>
			
			<div class="col-sm-4">
			<a href="https://conacon.org/moni/siscon/img/presentacion.pptx" target="_blank" title="click para descargar">  
				<img src="https://conacon.org/moni/siscon/img/ejemplo2.jpg" class="img-fluid">
			</a>
			</div>
			
			<div class="col-sm-4">
			   <a href="https://conacon.org/moni/siscon/img/presentacion.pptx" target="_blank" title="click para descargar">  
				<img src="https://conacon.org/moni/siscon/img/ejemplo3.jpg" class="img-fluid">
			   </a>
			</div>
          </div>    
        </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->
	  
	  
      <footer class="br-footer">
        <div class="footer-left">
          <div class="mg-b-2">Copyright &copy; 2021. CONACON TI. All Rights Reserved.</div>
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
    
    <script src="script/proximos-eventos.js"></script>

    <script src="../js/bracket.js"></script>
    <script src="script/pdf.js"></script>
    
  </body>
</html>
