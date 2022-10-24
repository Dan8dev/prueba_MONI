<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 36) ){
    header("Location: ../index.php");
    die();
}
    $usuario = $_SESSION["usuario"];
?>
<!doctype html>
<html lang="es">

<head>
<meta charset="utf-8" />
	<title>MONI</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="Admin Dashboard" name="description" />
	<meta content="ThemeDesign" name="author" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<link rel="shortcut icon" href="../assets/images/favicon.ico">

	<!--Datatables-->
	<link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />

	<!-- CSS bootstrap -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<!-- iconos fontawesom -->
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
	<!-- CSS general -->
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="../assets/css/edit-text.css" rel="stylesheet" type="text/css">
	<link href="../assets/css/summernote.css" rel="stylesheet" type="text/css">
	
	<!-- Sweet Alert -->
    <link href="../assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">
	
	<!-- CSS alerts-->
	<link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../assets/css/newStyles.css">

</head>

<body>
	<div class="header-bg">
		<!-- Navigation Bar-->
		<header id="topnav">
			<div class="topbar-main">
				<div class="container-fluid">
					<!-- Logo-->
					<div>
						<a href="#" class="logo">
							<img src="../assets/images/logo-light.png" class="logo-lg" alt="" height="26">
							<img src="../assets/images/logo-sm.png" class="logo-sm" alt="" height="28">
						</a>
					</div>
					<!-- End Logo-->
					<div class="menu-extras topbar-custom navbar p-0">
						<ul class="mb-0 nav navbar-right ml-auto list-inline">
							<li class="list-inline-item notification-list d-none d-sm-inline-block">
								<a href="#" id="btn-fullscreen"
									class="waves-effect waves-light notification-icon-box"><i
										class="fas fa-expand"></i></a>
							</li>
							<li class="dropdown">
								<a href="" class="dropdown-toggle profile waves-effect waves-light"
									data-toggle="dropdown" aria-expanded="true">
									<span class="profile-username">
									<input class = "d-none" type="text" name="idUsuarioBand" id="idUsuarioBand" value= "<?php echo $_SESSION["usuario"]['estatus_acceso'];?>">
                    <?php echo $usuario["persona"]["nombres"]; ?> <span class="mdi mdi-chevron-down font-15"></span>
                  </span>
								</a>
								<ul class="dropdown-menu">
									<!--<li><a href="javascript:void(0)" class="dropdown-item"> Profile</a></li>-->
									<li class="dropdown-divider"></li>
									<li><a href="../editarAccesos.php" class="dropdown-item my-2"> Cambiar contraseña</a></li>
									<li><a href="../log-out.php" class="dropdown-item my-2"> Cerrar sesión</a></li>
								</ul>
							</li>
							<li class="menu-item dropdown notification-list list-inline-item">
								<!-- Mobile menu toggle-->
								<a class="navbar-toggle nav-link">
									<div class="lines">
										<span></span>
										<span></span>
										<span></span>
									</div>
								</a>
								<!-- End mobile menu toggle-->
							</li>
						</ul>
					</div>
					<!-- end menu-extras -->
					<div class="clearfix"></div>
				</div>
				<!-- end container -->
			</div>
			<!-- end topbar-main -->

			<!-- MENU Start -->
			<?php include 'partials/nav.php'; ?>
			<!-- <div class="navbar-custom">
				<div class="container-fluid">
					<div id="navigation">
						<!- Navigation Menu
						<ul class="navigation-menu">
                            <li class="has-submenu">
                                <a href="index.php"><i class="ti-home"></i> Inicio</a>
                            </li>

							<?php if( $_SESSION["usuario"]['idTipo_Persona'] == 3 ){?>
							<li class="has-submenu">
								<a href="../marketing-educativo/gestorEventos.php"><i class="ion ion-md-calendar"></i> Gestor Eventos</a>
							</li>

							<li class="has-submenu">
								<a href="../marketing-educativo/index.php"><i class="ti-briefcase"></i> Marketing</a>
							</li>
							<?php }//If usuario?>

							<?php if( $_SESSION["usuario"]['idTipo_Persona'] == 31 ){?>
							<li class="has-submenu">
								<a href="gestorCarreras.php"><i class="fas fa-user-graduate"></i>Gestor Carreras</a>
							</li>
							<!- <li class="has-submenu">
								<a href="../admin-webex/"><i class="far fa-dot-circle"></i>Gestor Webex</a>
							</li> 
							<?php }//If usuario-control-escolar?>

						</ul>
						<!- End navigation menu 
					</div>
					<!- end #navigation 
				</div>
				<!- end container 
			</div> -->
			<!-- end navbar-custom -->
		</header>
		<!-- End Navigation Bar-->
	</div>
	<!-- header-bg -->
	
    <div class="toast-success"></div>