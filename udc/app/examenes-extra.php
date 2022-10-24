<?php 

  session_start();
  if (!isset($_SESSION["alumno"])) {
    header('Location: index.php');
    die();
  }
  $usr = $_SESSION['alumno'];
	require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $idusuariop=$_SESSION['alumno']['id_prospecto'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
  
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <style>
      
      .color-success{
        color: green;
        z-index: 99;
        font-size: 26px;
        text-align: center;
        font-weight: 500;
        position: relative;
        top: 50%;
        transform: translateY(-135%);
        max-width: 300px;
        margin: 0 auto;
      }
      .overlay.active{
        position: absolute;
        background: #ffffffc9;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9;
        height: 100%;
      }
    
    </style>
    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
 
      <?php 
       require 'plantilla/header.php';
      ?>

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Cursos</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <!--<h3><a href="#" class="text-dark"><u>Click para ingresar.</u></a></h3>-->

        <!--<h4 class="tx-gray-800 mg-b-5">TRAINING 28 de Septiembre  6:00 PM</h4>
        <p class="mg-b-0">Dá click en el link para iniciar tu sesión en Webex</p>-->
      </div>
	  
	  <div style="display:none;">
		<?php print_r($_SESSION); ?>
	  </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">
		      <!-- <?php #if($usuario['data']['clase'] != ''): ?> -->
			  <h3>Exámenes extraordinarios:</h3>
          <div class="row" id="examn-container">
            </div>   
		      <!-- <?php# endif; ?> -->
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
    
    <script src="script/clases.js"></script>
    <script src="../../assets/js/educate/scripts.js"></script>

    <script src="../js/bracket.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script>
     
      $(document).ready(function(){
        cargar_cursos_pagos();
        examenVisibility(<?=$idusuariop?>);
      });
      
    </script> 

  </body>
</html>
