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
.custom-file-control:lang(en):empty::after {
	content: "Elegir archivo...";
}
		  .custom-file-control:lang(en)::before {
	content: "Buscar";
}
	  </style>
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
            <span class="menu-item-label">Cursos</span>
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
            <span class="menu-item-label">Identidad corporativa</span>
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
              <span class="logged-name hidden-md-down"><?php echo $usuario["data"]["nombre"]; ?></span>
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
            <!--<span class="square-8 bg-danger pos-absolute t-10 r--5 rounded-circle"></span>-->
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
          <span class="breadcrumb-item active">EDITAR</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">SMART ID</h4>
        <p class="mg-b-0">Edita tus datos podras compartir tu perfil y sera visible para cualquier persona</p>
      </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Editar perfil</h6>
          <div class="row">
            <div class="col-sm-12 col-lg-4">
              <div class="card mg-b-40">
                <div class="card-body">

                  <form method="post" id="formulario" enctype="multipart/form-data">
                    <!--<input type="file" name="file">-->
                    <label class="custom-file">
                      <input type="file" name="file" class="custom-file-input" >
                      <span class="custom-file-control custom-file-control-primary"></span>
                    </label>
                    <input type="hidden" name="action" value='subirfoto'>
                  </form>
                </div>
                <img id="imagenperfil" class="card-img-bottom img-fluid" src="img/afiliados/<?php echo $usuario["data"]["foto"]; ?>" alt="Image">
              </div>
            </div>
          </div> 
          <p class="mg-b-40 tx-gray-600">Llena correctamente el siguiente formulario</p>
			<div id="alertaeditarperfil" style="display:none" class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <strong class="d-block d-sm-inline-block-force">Actualizacion exitosa,</strong> los datos se guardaron correctamente
            </div>
          <div id="wizard2">
            <h3>Datos personales</h3>
            <section>
              <p>Da click en el botón siguiente o anterior para continuar.</p>
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Nombre: <span class="tx-danger">*</span></label>
                <input id="firstname" class="form-control" name="firstname" placeholder="Ingresa Nombre" type="text" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Apellido Paterno: <span class="tx-danger">*</span></label>
                <input id="apaterno" class="form-control" name="apaterno" placeholder="Ingresa Apellido Paterno  " type="text" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Apellido Materno: <span class="tx-danger">*</span></label>
                <input id="amaterno" class="form-control" name="amaterno" placeholder="Ingresa Apellido Materno" type="text" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Fecha Nacimiento: <span class="tx-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input id="fnacimiento" type="text" class="form-control fc-datepicker" name="fnacimiento" placeholder="MM/DD/YYYY">
                </div>      
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">CURP: <span class="tx-danger">*</span></label>
                <input id="curp" class="form-control" name="curp" placeholder="Ingresa CURP" type="text" onkeyup="mayusculas(this);" maxlength="18">
              </div><!-- form-group -->        
            </section>
            <h3>Contacto</h3>
            <section>
              <p>Da click en el botón siguiente o anterior para continuar.</p>
              <div class="form-group wd-xs-300">
                <label class="form-control-label">País: <span class="tx-danger">*</span></label>
                <select id="pais" class="form-control" name="pais" placeholder="Ingresa País" type="text" required>
                  
                </select>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Estado: <span class="tx-danger">*</span></label>
                <select id="estado" class="form-control" name="estado" placeholder="Ingresa Estado" type="text" required>
                  
                </select>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
                <input id="ciudad" class="form-control" name="ciudad" placeholder="Ingresa Ciudad" type="text" required>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Colonia: <span class="tx-danger">*</span></label>
                <input id="colonia" class="form-control" name="colonia" placeholder="Ingresa Colonia" type="text" required>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Calle: <span class="tx-danger">*</span></label>
                <input id="calle" class="form-control" name="calle" placeholder="Ingresa Calle" type="text" required>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">C.P.: <span class="tx-danger">*</span></label>
                <input id="codigopostal" class="form-control" name="codigopostal" placeholder="Ingresa C.P." type="number" required>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Email: <span class="tx-danger">*</span></label>
                <input id="email" class="form-control" name="email" placeholder="Ingresa Email" type="email" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Célular: <span class="tx-danger">*</span></label>
                <input id="celular" class="form-control" name="celular" placeholder="Ingresa número de celular" type="number" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Facebook: <span class="tx-danger">*</span></label>
                <input id="facebook" class="form-control" name="facebook" placeholder="Ingresa liga a facebook" type="tel" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Instagram: <span class="tx-danger">*</span></label>
                <input id="instagram" class="form-control" name="instagram" placeholder="Ingresa liga a instagram" type="text" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Twitter: <span class="tx-danger">*</span></label>
                <input id="twitter" class="form-control" name="twitter" placeholder="Ingresa liga a twitter" type="text" required>
              </div><!-- form-group -->
            </section>
            <h3>Académico</h3>
            <section>
              <p>Da click en el botón siguiente o anterior para continuar.</p>
              <div class="form-group wd-xs-300">
                <labe>Selecciona último grado de estudios</label>
                <select id="gradoestudios" name="gradoestudios" class="form-control select2" data-placeholder="Grado académico">
                  <option value="Cursos">Cursos</option>
                  <option value="Secundaria">Secundaria</option>
                  <option value="Bachiller">Bachiller</option>
                  <option value="Preparatoria">Preparatoria</option>
                  <option value="Licenciatura">Licenciatura</option>
                  <option value="Posgrado">Posgrado</option>
                </select>
              </div>
				<!-- INPUT AGREGADO TIPO LICENC-->
			<div class="form-group wd-xs-300">
				<label class="form-control-label">Tipo Licenciatura: <span class="tx-danger">*En caso de contar con ella</span></label>
                <input id="tipoLicen" class="form-control" name="tipoLicen" placeholder="Ingresa Tipo Licenciatura" onkeyup="mayusculas(this);" type="text" >
			</div>	
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Cédula Profesional: <span class="tx-danger">*En caso de contar con ella</span></label>
                <input id="cedulap" class="form-control" name="cedulap" placeholder="Ingresa Cédula profesional" onkeyup="mayusculas(this);" maxlength="8" type="text" >
              </div><!-- form-group --> 
            </section>
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

    <script src="../js/bracket.js"></script>
    <script src="script/perfil.js"></script>
    <script>
     
    </script> 

  </body>
</html>
