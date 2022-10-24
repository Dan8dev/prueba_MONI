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
    /* $fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
    $fechaactual= date('Y-m-d');
    $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

    $datetime1 = new DateTime($fechaactual);
    $datetime2 = new DateTime($fechafinmembresia);
    $interval = $datetime1->diff($datetime2);
    $diasrestantes= substr($interval->format('%R%a días'), 1);
    $dias = rtrim($diasrestantes, ' días');
    if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
      header('Location: pagos.php');
    } */
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
	  
	  <link rel="icon" type="imge/png" href="img/favicon.png">
	  
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

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
    <style>
        .overlay-evento{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            opacity: 0;
            transition: .5s ease;
            background-color: #4479ABC4;
        }
        .overlay-evento-content {
            color: white;
            font-size: 16px;
            position: absolute;
            top: 50%;
            left: 46%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            /* text-align: center; */
            width: 100%;
        }
        .overlay-evento:hover{
          opacity: 1;
        }
    </style>
	<?php require 'plantilla/header.php'; ?>

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Videoteca</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">Videos</h4>
        <p class="mg-b-0">Congresos y eventos</p>
      </div>

      <div class="br-pagebody">
        
        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">catálogo</h6>
		  <div class="row">
            <div class="col-sm-4 ml-auto">
              <input type="text" id="buscador" class="form-control mb-4" placeholder="Buscar">
            </div>
          </div>
          <div class="row" id="content-events">
            <!--<div class="col-xl-4 col-sm-6 col-md-6">
              <div class="card mg-b-40">
                <div class="card-body">
                  <p class="card-text">La Familia</p>
                </div>
                <a href="reproductor.php"><img class="card-img-bottom img-fluid" src="../img/img12.jpg" alt="Image"></a>
              </div>
            </div>
            <div class="col-xl-4 col-sm-6">
              <div class="card mg-b-40">
                <div class="card-body">
                  <p class="card-text">Intervención en crisis</p>
                </div>
                <img class="card-img-bottom img-fluid" src="../img/img12.jpg" alt="Image">
              </div>
            </div>
            <div class="col-xl-4 col-sm-6">
              <div class="card mg-b-40">
                <div class="card-body">
                  <p class="card-text">Responder a la vida sin violencia</p>
                </div>
                <img class="card-img-bottom img-fluid" src="../img/img12.jpg" alt="Image">
              </div>
            </div>-->
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

    <script src="script/memoria-digital.js"></script>

    <script src="../js/bracket.js"></script>
    <script>
	
      $(document).ready(function(){
        'use strict';

        $('#wizard2').steps({
          headerTag: 'h3',
          bodyTag: 'section',
          autoFocus: true,
          titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
          onStepChanging: function (event, currentIndex, newIndex) {
            if(currentIndex < newIndex) {
              // Step 1 form validation
              if(currentIndex === 0) {
                var fname = $('#firstname').parsley();
                var lname = $('#lastname').parsley();

                if(fname.isValid() && lname.isValid()) {
                  return true;
                } else {
                  fname.validate();
                  lname.validate();
                }
              }

              // Step 2 form validation
              if(currentIndex === 1) {
                var email = $('#email').parsley();
                if(email.isValid()) {
                  return true;
                } else { email.validate(); }
              }
            // Always allow step back to the previous step even if the current step is not valid.
            } else { return true; }
          }
        });

        $('.fc-datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
        }); 
      });
      function mayusculas(e) {
          e.value = e.value.toUpperCase();
        } 
		
		$(document).ready(function(){
			$("#buscador").on("keydown",function(){
				$("#content-events").find("div").each(function(){
				  if($(this)[0].hasAttribute("contiene")){
					// console.log($(this).attr("contiene")+","+$("#buscador").val())
					if(!$(this).attr("contiene").toLowerCase().includes($("#buscador").val().toLowerCase())){
					  $(this).css("display",'none')
					}else{
					  $(this).css("display",'block')
					}
				  }
				})
			  });
			  $("#buscador").on("keyup",function(){
				$("#content-events").find("div").each(function(){
				  elm = $(this)
				  if($(this)[0].hasAttribute("contiene")){
					if(!$(this).attr("contiene").toLowerCase().includes($("#buscador").val().toLowerCase())){
					  $(this).css("display",'none')
					}else{
					  $(this).css("display",'block')
					}
				  }
				})
			  });
		})
    </script> 

  </body>
</html>
