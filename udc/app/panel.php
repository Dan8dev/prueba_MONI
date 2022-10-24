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
  if (!isset($_SESSION["alumno"])) {
    header('Location: index.php');
    die();
  }
  $usr = $_SESSION['alumno'];
  $usr = $_SESSION['alumno'];
	require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
  /*$fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
  $fechaactual= date('Y-m-d H:i:s');
  $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

  $datetime1 = new DateTime($fechaactual);
  $datetime2 = new DateTime($fechafinmembresia);
  $interval = $datetime1->diff($datetime2);
  $diasrestantes= substr($interval->format('%R%a días'), 1);
  $dias = rtrim($diasrestantes, ' días');
  if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
    header('Location: pagos.php');
  }*/
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
        <?php if ($dias<31) {
          # code...
        ?>
        <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?>  <a class="text-dark" href="pagos.php">Pague aqui</a></strong> 
        </div><!-- alert -->
        <?php } ?>
        <!-- start you own content here -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <div class="pd-30">
          <h4 class="tx-gray-800 mg-b-5">CONACON</h4>
        </div><!-- d-flex -->
        <div class="br-pagebody mg-t-5 pd-x-30">
          <div class="row row-sm">
            <div class="col-sm-6 col-xl-6 mg-b-20">
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
            </div><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
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
            </div><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="evento/index2.php?event=2"><i class="fa fa-calendar tx-60 lh-0 tx-white op-7"></i></a>
                  <div class="mg-l-20">
                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10"><a href="evento/index2.php?event=2">Congreso</a></p>
					          <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1"><a href="evento/index2.php?event=2">CISMAC 2021</a></p>
					          <span class="tx-11 tx-roboto tx-white-6"><a href="evento/index2.php?event=2">Pagar</a></span>
                  </div>
                </div>
              </div>
            </div><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
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
            </div><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
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
            </div><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
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
            </div><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
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
            </div><!-- col-4 -->

            <div class="col-sm-6 col-xl-6 mg-b-20">
              <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                  <a href="page-profile.php"><i class="ion ion-person tx-60 lh-0 tx-white op-7"></i></a>
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
            </div><!-- col-3 -->
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
