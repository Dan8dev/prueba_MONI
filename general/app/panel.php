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
      <!-- Facebook -->
      <meta property="og:url" content="https://www.facebook.com/posgradosiesm">
    <meta property="og:title" content="IESM">
    <meta property="og:description" content="Desde el 2006 el IESM ha buscado profesionalizar la Medicina y Cirugía Estética con programas de posgrado que cuentan con RVOE (registro de validez oficial SEP, otorgando título y cédula profesional)">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="IESM" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.conacon.org/moni/iesm" />
    <meta property="og:image" content="https://www.conacon.org/moni/iesm/app/img/logoMetas.png" />
    <meta property="og:description" content="" />
    <!-- Meta -->
    <meta name="description" content="IESM">
    <meta name="author" content="IESM Desarrollo Tecnológico">



    <title>IESM</title>

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
          <a class="breadcrumb-item" href="index.php">INICIO</a>
          <span class="breadcrumb-item active">PANEL</span>
        </nav>
      </div><!-- br-pageheader -->

      <div class="br-pagebody">
        
        <!-- start you own content here -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <div class="pd-30">
          <img src="img/icon_96.png">
        </div><!-- d-flex -->
        <div class="br-pagebody mg-t-5 pd-x-30">
          <div class="row row-sm">
            <!--<div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="cursos.php"><i class="fa fa-pencil tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="cursos.php">CERTIFICACIONES</a></p>
                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="cursos.php">CURSOS</a></p>
                    <span class="tx-11 tx-roboto tx-white-6"><a href="cursos.php">Ver clases</a></span>
                  </div>
                </div>
              </div>
            </div>--><!-- col-4 -->

            <!--<div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="proximosEventos.php"><i class="ion ion-calendar tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="proximosEventos.php">Calendario</a></p>
                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="proximosEventos.php">EVENTOS</a></p>
                    <span class="tx-11 tx-roboto tx-white-6"><a href="proximosEventos.php">Próximos eventos</a></span>
                  </div>
                </div>
              </div>
            </div>--><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="cismac.php"><i class="fa fa-stethoscope tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="cismac.php">XXIV Congreso</a></p>
					          <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="cismac.php">INTERNACIONAL DE CIRUGÍA ESTÉTICA, MEDICINA ESTÉTICA Y OBESIDAD</a></p>
					          <span class="tx-11 tx-roboto tx-white-6"><a href="cismac.php">2022</a></span>
                  </div>
                </div>
              </div>
            </div><!-- col-4 -->

            <!--<div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="inicioCursos.php"><i class="icon ion-ios-calendar tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="inicioCursos.php">Calendario</a></p>
                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="inicioCursos.php">CURSOS</a></p>
                    <span class="tx-11 tx-roboto tx-white-6"><a href="inicioCursos.php">Próximos cursos</a></span>
                  </div>
                </div>
              </div>
            </div>--><!-- col-4 -->

            <!--<div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="memoriaDigital.php"><i class="ion ion-monitor tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="memoriaDigital.php">Videos</a></p>
                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="memoriaDigital.php">VIDEOTECA</a></p>
                    <span class="tx-11 tx-roboto tx-white-6"><a href="memoriaDigital.php">On line</a></span>
                  </div>
                </div>
              </div>
            </div>--><!-- col-4 -->

            <!--<div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="identidad.php"><i class="fa fa-users tx-50 tx-white op-7"></i></a>
				  
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-t-10 mg-b-0"><a href="identidad.php">Identidad</a></p>
                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="identidad.php">CONACON</a></p>
                    <span class="tx-11 tx-roboto tx-white-6"><a href="identidad.php">Tarjetas / Credenciales</a></span>
                  </div>
                </div>
              </div>
            </div>--><!-- col-4 -->

            <!--<div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="pagos.php"><i class="fa fa-address-card tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="pagos.php">Pagos</a></p>
                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="pagos.php">Membresia</a></p>
                    <span class="tx-11 tx-roboto tx-white-6"><a href="pagos.php">Planes</a></span>
                  </div>
                </div>
              </div>
            </div>--><!-- col-4 -->

            <!--<div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="page-profile.php"><i class="fa fa-user-md tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="page-profile.php">Smart ID</a></p>
                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="page-profile.php">Perfil</a></p>
                    <span class="tx-11 tx-roboto tx-white-6"><a href="page-profile.php">Público</a></span>
                  </div>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="editar-perfil.php">EDITAR</a></p>
                  </div>
                </div>
              </div>
            </div>-->
			
			
          </div><!-- row -->
        </div>
      </div>  
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
  </body>
</html>
