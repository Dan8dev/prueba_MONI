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
  
  $usuarioCredenciales =$afiliados->obtenerusuariocredenciales($idusuario);
  $usuario = $afiliados->obtenerusuario($idusuario);

  $NacionalidadAlumno = "Mexicano";
  if($usuario['data']['paisn'] != 37 && $usuario['data']['paisn'] != 0){
      $NacionalidadAlumno = "Extranjero";
  }
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
    <input type="hidden" name="IdentificadorNacionalidad" id="IdentificadorNacionalidad" value ="<?php echo $NacionalidadAlumno;?>">
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <!--<nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Identidad Corporativa</span>
        </nav>-->
      </div><!-- br-pageheader -->

      <div class="br-pagebody">
        <div class="container-liquid">
          <input class ="form-control d-none" type="text" name="idAfiliado" id="idAfiliado" value = "<?php echo $idusuario;?>">
          <div class="br-section-wrapper">
            <div class="row">
              <div class="col-md-6">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Credencial estudiante</h6>
              </div>
              <div class="col-md-6">
                <h6 id ="AvisoEstatus" class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10 "></h6>
              </div>
            </div>
          </div>
        </div>      
           
        <div class="container-liquid d-none" id = "ContenedorCredenciales"> 
          <div class="br-section-wrapper">
            <p>Da clic en las imagenes para descargar <p>
            <div class="row">
              <div class="col-sm-6">
                <a onclick="buscarDatosCredencial('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">  
                <img src="../img/CredencialFrontal.jpg" class="img-fluid mouse-pointt">
                </a>
              </div>
              <div class="col-sm-6">
                <a onclick="buscarDatosCredencial('<?php echo $idusuario; ?>');" target="_blank" title="click para descargar">  
                <img src="../img/credencialBack.jpg" class="img-fluid mouse-pointt">
                </a>
              </div>
            </div>
          </div>   
        </div><!-- br-section-wrapper -->

        <div class="container-liquid d-none" id = "ContenedorSolicitudCredenciales">
          <div class="br-section-wrapper">         
           
           
            <div class="row">
              <div class="col-md-6">
                <!-- <p id ="estatusSolicitud"></p> -->
                <br> Para visualizar tu credencial sube tu foto en el menú <a href="subirDocumentos.php" style="color:#4479AC">"Documentación"</a> y espera la validación de tu fotografía por parte de Control Escolar para poder "Descargar".</p>
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Solicitud de Credencial</h6>
               <button class ="form-control btn btn-primary" id ="BotonSolicitudCredencial">Solicitar Credencial</button>
              </div>
              <div class="col-md-6 text-center">
                <img src="../img/CredencialFrontal.jpg" class="img-fluid mouse-pointt img-responsive">
              </div>
            </div>   
          </div><!-- br-section-wrapper -->
        </div>
      </div>    <!-- br-pagebody -->
      

    <!-- Modal -->
    <div class="modal fade" id="CursosCredencial" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Selecciona el curso</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <h6>¡Recuerda! Si cuentas con un bloqueo por falta de pago, no podrá solicitar su credencial para ese curso.</h6>
            <div class="row" id="cursos-container">

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            
          </div>
        </div>
      </div>
    </div>
	  
	  
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
