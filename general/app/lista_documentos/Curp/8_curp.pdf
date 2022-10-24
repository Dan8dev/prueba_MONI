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
  </head>

  <body>

    <!-- ########## START: LEFT PANEL ########## -->
    <div class="br-logo"><a href=""><span><img src="img/logoT.png" width="90%"></span></a></div>
    <div class="br-sideleft overflow-y-auto">
      <label class="sidebar-label pd-x-15 mg-t-20">Menú</label>
      <div class="br-sideleft-menu">
        <a href="panel.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-home-outline tx-22"></i>
            <span class="menu-item-label">Panel</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
		
		<a href="clases.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-pencil tx-22"></i>
            <span class="menu-item-label">Clases</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
	
        <a href="proximosEventos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-calendar tx-22"></i>
            <span class="menu-item-label">Eventos</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
 	<a href="evento/index2.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-calendar tx-22"></i>
            <span class="menu-item-label">Cismac 2021</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
		
		<a href="inicioCursos.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-calendar tx-22"></i>
            <span class="menu-item-label">Certificaciones</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
		
		
        <a href="memoriaDigital.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-monitor tx-22"></i>
            <span class="menu-item-label">Videoteca</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
		
		<a href="identidad.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-person tx-22"></i>
            <span class="menu-item-label">Identidad CONACON</span>
          </div><!-- menu-item -->
        </a>
		
		
        <a href="editar-perfil.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-compose tx-22"></i>
            <span class="menu-item-label">Editar Perfil</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <a href="page-profile.php" class="br-menu-link">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-person tx-22"></i>
            <span class="menu-item-label">Mi perfil</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->

      </div><!-- br-sideleft-menu -->
    </div><!-- br-sideleft -->
    <!-- ########## END: LEFT PANEL ########## -->

    <!-- ########## START: HEAD PANEL ########## -->
    <div class="br-header">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
      </div><!-- br-header-left -->

      <div class="br-header-right">
        <nav class="nav">
          <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <span class="logged-name hidden-md-down"><?php echo $usr["nombre"]; ?></span>
              <img src="img/afiliados/<?php echo $usuario["data"]["foto"]; ?>" class="wd-32 rounded-circle" alt=""> 
              <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-200">
              <ul class="list-unstyled user-profile-nav">
              <li><a href="log-out.php"><i class="icon ion-power"></i> Cerrar sesion</a></li>
              <li><a href="editar_acceso.php"><i class="icon ion-unlocked"></i> Cambiar Contraseña</a></li>
              </ul>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </nav>
        <div class="navicon-right">
          <a id="btnRightMenu" href="" class="pos-relative">
            <i class="icon ion-ios-calendar"></i>
            <!-- start: if statement -->
            <!-- <span class="square-8 bg-danger pos-absolute t-10 r--5 rounded-circle"></span> -->
            <!-- end: if statement -->
          </a>
        </div><!-- navicon-right -->
      </div><!-- br-header-right -->
    </div><!-- br-header -->
    <!-- ########## END: HEAD PANEL ########## -->

    <!-- ########## START: RIGHT PANEL ########## -->
    <div class="br-sideright">
      <ul class="nav nav-tabs sidebar-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" role="tab" href="#calendar"><i class="icon ion-ios-calendar-outline tx-24"></i></a>
        </li>

      </ul><!-- sidebar-tabs -->

      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane pos-absolute a-0 mg-t-60 overflow-y-auto active" id="calendar" role="tabpanel">
          <label class="sidebar-label pd-x-25 mg-t-25">Hoy</label>
          <div class="pd-x-25">
            <h2 id="brTime" class="tx-white tx-lato mg-b-5"></h2>
            <h6 id="brDate" class="tx-white tx-light op-3"></h6>
          </div>
          <label class="sidebar-label pd-x-25 mg-t-25">Calendario Eventos</label>
          <div class="datepicker sidebar-datepicker"></div>




        </div>
      </div><!-- tab-content -->
    </div><!-- br-sideright -->
    <!-- ########## END: RIGHT PANEL ########## --->

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Clases</span>
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
        <!--<h4 class="tx-gray-800 mg-b-5">TRAINING 28 de Septiembre  6:00 PM</h4>
        <p class="mg-b-0">Dá click en el link para iniciar tu sesión en Webex</p>-->
      </div>
	  
	  <div style="display:none;">
		<?php print_r($usuario); ?>
	  </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">
		  <?php if($usuario['data']['clase'] != ''): ?>
		  <p class="my-4 p-2" style="font-size: 11px;text-align: justify; color: black; background-color: #f2f2f2;">Queda prohibida la reproducción total o parcial de este material en cualquier forma, ya sea mediante fotografía, impresión o cualquier otro procedimiento sin el consentimiento por escrito del autor. Hacerlo, representa una infracción en materia de los derechos de autor previstos en el artículo 229 de la Ley Federal de Derechos de Autor.</p>
		  <h2>CLASES GRABADAS</h2>
		  
		  <div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="col-sm-12 col-md-12">
				  <div class="card mg-b-40">
          
					<div class="card-body bg-primary">
					  <p class="card-text text-truncate text-white">Operador en adicciones y salud mental Clase 1</p>
					</div>
					<a href="reproductor.php?url=<?php echo(urlencode('https://universidaddelconde.edu.mx/eventos/COTA-MOD1-20210929.mp4')); ?>"><img class="card-img-bottom img-fluid" src="https://conacon.org/moni/siscon/app/img/clases/Clase1.jpg" alt="Image"></a>
				  </div>
				</div>
			</div>
			<div class="col-sm-12 col-md-3">
				<h2>DESCARGABLES</h2>
				<ul>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%201%20Entrevista%20inicial.pdf" target="_blank">Entrevista inicial</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%202%20ASSIST.pdf" target="_blank">ASSIST</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%203%20Beck%20Ansiedad.pdf" target="_blank">Beck Ansiedad</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%204%20Beck%20Depresi%C3%B3n.pdf" target="_blank">Beck Depresión</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%205%20Inventario%20SCL-90.pdf" target="_blank">Inventario SCL-90</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%206%20Nota%20de%20Valoracion%20Individual.pdf" target="_blank">Nota de Valoración Individual</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%207%20Hoja%20de%20Referencia.pdf" target="_blank">Hoja de Referencia</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%208%20Nota%20de%20ingreso.pdf" target="_blank">Nota de ingreso</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%209%20Estudio%20Socioecon%C3%B3mico.pdf" target="_blank">Estudio Socioeconómico</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2010%20Consentimiento%20informado%20adultos.pdf" target="_blank">Consentimiento informado adultos</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2011%20Consentimiento%20informado%20involuntario%20o%20menores.pdf" target="_blank">Consentimiento informado involuntario o menores</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2012%20Historia%20Clinica.pdf" target="_blank">Historia Clínica</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2013%20Plan%20de%20tratamiento.pdf" target="_blank">Plan de tratamiento</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2014%20Nota%20de%20Evolucion%20Medica.pdf" target="_blank">Nota de Evolución Médica</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2015%20Nota%20de%20Evolucion%20Psicologica.pdf" target="_blank">Nota de Evolución Psicológica</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2016%20Nota%20de%20Evolucion%20de%20Trabajo%20Social.pdf" target="_blank">Nota de Evolución de Trabajo Social</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2017%20Nota%20de%20Interconsulta.pdf" target="_blank">Nota de Interconsulta</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/Anexo%2020%20Reporte%20de%20Seguimiento.pdf" target="_blank">Reporte de Seguimiento</a></li>
				</ul>
			</div>
			
			<div class="col-sm-12 col-md-3">
				<h2>MATERIAL DE APOYO</h2>
				<ul>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/expedientetalleroperacion.pdf" target="_blank">Guía para la Integración del  Expediente Clínico en los Establecimientos en Adicciones </a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/codigobiomedico.pdf" target="_blank">Código Bioético del Operador Terapéutico</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/tecnicasoperadorresidente.pdf" target="_blank">TÉCNICAS QUE EL OPERADOR GENERA ENTRE ÉL Y EL RESIDENTE</a></li>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/actitudesterapeuticas.pdf" target="_blank">OPERADOR TERAPÉUTICO EN ADICCIONES</a></li>
				</ul>
			</div>
			
			<!--<div class="col-sm-6 mb-4">
				<a href="https://universidaddelconde.webex.com/universidaddelconde/k2/j.php?MTID=tb910c69603e52a09ed01267ef7e21f94" target="_blank">  
				 <img src="https://conacon.org/moni/siscon/img/ImgWEB.jpg" class="img-fluid mb-4">
				</a>
				<a class="text-primary" href="https://universidaddelconde.webex.com/universidaddelconde/k2/j.php?MTID=tb910c69603e52a09ed01267ef7e21f94" target="_blank"><u><h4>Click aquí para unirte a la sesión.</h4></u><a>
			</div>-->
			
			<!--<div class="col-sm-6 mb-4">
				<a href="<?php# echo ($usuario['data']['clase'] != '')? $usuario['data']['clase'] : '#';  ?>">  
				 <img src="https://conacon.org/moni/siscon/img/imgWEB2.jpg" class="img-fluid mb-4">
				</a>
				<a class="text-primary" href="<?php #echo ($usuario['data']['clase'] != '')? $usuario['data']['clase'] : '#';  ?>"><u><h4>Click aquí para unirte a la sesión.</h4></u><a>
			</div>-->
          </div>   
		  
		  <div style="border-bottom: solid .5px #d0d0d0; width: 100%;" class="my-4"></div>
		  
		  <div class="row">
			<div class="col-sm-12 col-md-6">
				<!--<h2>ENTRA A TU CLASE EN VIVO</h2>
				<p>Click en el botón para ingresar.</p>-->
				<div class="col-sm-12 col-md-12">
				  <div class="card mg-b-40">
					<!--<a href="#"> <button>Ingresar a la case en vivo</button></a> -->
					<div class="card-body bg-primary">
					  <p class="card-text text-truncate text-white">Operador en adicciones y salud mental Clase 2</p>
					</div>
					<a href="reproductor.php?url=<?php echo(urlencode('https://universidaddelconde.edu.mx/eventos/COTA%20MOD%202.mp4')); ?>"><img class="card-img-bottom img-fluid" alt="Image" src="https://conacon.org/moni/siscon/app/img/clases/Clase2.jpg"></a>
				  </div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<h2>MATERIAL DE APOYO</h2>
				<ul>
					<li><a style="color:black" href="https://conacon.org/moni/siscon/app/documents/descargablesOTA/modelosyreferencias.pdf" target="_blank">MODELOS Y REFERENCIALES PARA TRATAMIENTO</a></li>
				</ul>
				
			</div>
			<!--<div class="col-sm-6 mb-4">
				<a href="https://universidaddelconde.webex.com/universidaddelconde/k2/j.php?MTID=tb910c69603e52a09ed01267ef7e21f94" target="_blank">  
				 <img src="https://conacon.org/moni/siscon/img/ImgWEB.jpg" class="img-fluid mb-4">
				</a>
				<a class="text-primary" href="https://universidaddelconde.webex.com/universidaddelconde/k2/j.php?MTID=tb910c69603e52a09ed01267ef7e21f94" target="_blank"><u><h4>Click aquí para unirte a la sesión.</h4></u><a>
			</div>-->
			
			<!--<div class="col-sm-6 mb-4">
				<a href="<?php# echo ($usuario['data']['clase'] != '')? $usuario['data']['clase'] : '#';  ?>">  
				 <img src="https://conacon.org/moni/siscon/img/imgWEB2.jpg" class="img-fluid mb-4">
				</a>
				<a class="text-primary" href="https://universidaddelconde.webex.com/universidaddelconde/k2/j.php?MTID=td20de05539d900dec760bd53a26cd0e7"><u><h4>Click aquí para unirte a la sesión.</h4></u><a>
			</div>-->
          </div>
		  
          
		  <?php endif; ?>
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
    
    <script src="script/proximos-eventos.js"></script>

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
    </script> 

  </body>
</html>
