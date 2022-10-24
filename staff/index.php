<?php 
	// session_start();
	$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
	require '../assets/data/Model/conexion/conexion.php';
	require '../assets/data/Model/eventos/eventosModel.php';
	$evtM = new Evento();
	$id_evento = 77;
	$info_e = $evtM->consultarEvento_Id($id_evento);
	$talleres = $evtM->talleres_eventos($id_evento);
	$info_t = false;
	if(isset($_GET['sub'])){
		$info_t = $evtM->consultar_taller_clave($_GET['sub']);
		if(!$info_t['data']){
			header("Location: ./");
		}
	}

	// $admin = (isset($_SESSION['usuario']));

?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>MONI</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="Admin Dashboard" name="description" />
	<meta content="ThemeDesign" name="author" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<link rel="shortcut icon" href="../assets/images/favicon.ico">

	<!-- camara lectora de qr -->
	<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" ></script>	

	<!-- CSS bootstrap -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<!-- iconos fontawesom -->
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
	<!-- CSS general -->
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css">

</head>


<body>
	<div class="header-bg">
		<!-- Navigation Bar-->
		<header id="topnav">
			<div class="topbar-main">
				<div class="container-fluid">
					<div class="col-12 alert alert-success alert-dismissible fade show text-light" id="alerta_bienvenido" style="display: none; position: fixed;top: 15%;z-index: 10;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        Bienvenido.
                    </div>
					<!-- Logo-->
					<div>
						<a href="index.html" class="logo">
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
										<span class="mdi mdi-chevron-down font-15"></span>
									</span>
								</a>
								<ul class="dropdown-menu">
									<li class="dropdown-divider"></li>
									<li><a href="../log-out.php" class="dropdown-item"> Salir</a></li>
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
			<div class="navbar-custom" id="content-lines">
				<div class="container-fluid">
					<div id="navigation">
						<!-- Navigation Menu-->
						<ul class="navigation-menu">
							<li class="has-submenu">
								<a href="./"><i class="ti-home"></i> Acceso General</a>
							</li>

							<li class="has-submenu">
								<a href="#" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="ti-calendar"></i> Itinerario</a>
							</li>
							
						</ul>
						<!-- End navigation menu -->
					</div>
					<!-- end #navigation -->
				</div>
				<!-- end container -->
			</div>
			<!-- end navbar-custom -->
		</header>
		<!-- End Navigation Bar-->
	</div>
	<!-- header-bg -->

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-0" id="mySmallModalLabel">ITINERARIO</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <?php 
						foreach ($talleres['data'] as $taller => $detalles) {
							$fecha_show = substr($detalles["fecha"], 7);
							$fecha_sp = explode('-', $detalles["fecha"]);
							$mes = $meses[intval($fecha_sp[1])-1];
							$fecha_show = $mes.$fecha_show;
							echo "<a class='btn btn-block btn-light text-left' href='./?sub={$detalles["clave"]}'>
									<i class='fas fa-play-circle'></i> {$detalles["nombre"]} <small class='float-right'><i>{$fecha_show}</i></small>
								</a>";
							// print_r($detalles);
							// echo "<br>";
						}
					?>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

	<div class="wrapper">
		<div class="container-fluid">
			<!-- Page-Title -->
			<?php if ($info_t !== false): ?>
				<div class="text-center pt-4">
					<h1 class="text-primary">ACCESO PARA: <b><?php echo $info_t['data']['nombre']; ?></b></h1>
				</div>
			<?php else: ?>
				<div class="text-center pt-4">
					<h1 class="text-primary">ACCESO GENERAL</h1><h2 class="text-primary"><?= $info_e['data']['titulo'] ?></h2>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="col-sm-12">
					<div class="page-title-box py-2">
						<div class="row align-items-center">
							<div class="col-md-8">
								<h4 class="page-title m-0">Accesos</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="row">
                <div class="col-sm-12">
                    <form id="asistencia-precongreso" method="post">
                    	<?php if ($info_t !== false): ?>
                    		<input type="hidden" name="clave_taller" value="<?php echo $_GET['sub'] ?>">
                    	<?php endif ?>
                        <div class="form-group row">
                            <label for="idusuarioidevento" class="col-sm-3 control-label">Evento</label>
                            <input type="text" class="form-control border border-dark" id="idusuarioidevento" name="jsonasistencia" autofocus>
                        </div>
                    </form>
                </div>
            </div>
			<div class="row">
				<div class="col-sm-12 col-lg-6">
					<div class="card align-items-center">
						<div class="card-body">
							<h4 class="m-t-0">Foto</h4>
							<span class="badge rounded-circle float-right" id="badge-class" style="padding: 10px 14px;">&nbsp;</span>
							<center>
								<?php
								// $foto_ev = @file_get_contents('https://moni.com.mx/assets/generales/images/flyers/'.$info_e['data']['imagen']);
								// if($foto_ev !== false){
									$foto_ev = 'https://moni.com.mx/assets/images/generales/flyers/'.$info_e['data']['imagen'];
								// }else{
									// $foto_ev = '../assets/images/congresos/newlogo.png';
								// }
								?>
								<img id="fotoasistente" class="img-top img-fluid" title="<?= 'https://moni.com.mx/assets/images/generales/flyers/'.$info_e['data']['imagen'] ?>" imagenperfil src="<?= $foto_ev ?>" style="width: 60%;">
							</center>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-lg-6">
					<div class="card">
						<div class="card-body">
							<h4 class="m-t-0">Datos</h4>
							<div>
								<form class="form-horizontal" id="tmp_subm">
									<div class="form-group row">
										<label for="nombreasistente" class="col-sm-3 control-label">Nombre</label>
										<div class="col-sm-9">
											<label type="text" class="form-control" id="nombreasistente"
												placeholder="Usuario">
										</div>
									</div>
									<div class="form-group row">
										<label for="tituloevento" class="col-sm-3 control-label">Taller</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="tituloevento"
												placeholder="Datos Taller" readonly>
										</div>
									</div>
									<div class="form-group row d-none">
										<label for="cantidadpagada" class="col-sm-3 control-label">Pago</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="cantidadpagada"
												placeholder="Status pago" readonly>
										</div>
									</div>

									<div class="form-group row" id="talleres_selec_c" style="display: none;">
										<label for="talleres_selec" class="col-sm-3 control-label">Talleres</label>
										<div class="col-sm-9">
											<div id="talleres_selec">
												<input type="text" class="form-control" readonly placeholder="Talleres seleccionados">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- end container-fluid -->
		</div>
	</div>
	<!-- end wrapper -->

	<!-- Footer -->
	<footer class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					© 2021 UDC-IESM-TSU-CONACON-TI
				</div>
			</div>
		</div>
	</footer>
	<!-- End Footer -->

	<!-- jQuery  -->
	<script src="../assets/js/template/jquery.min.js"></script>
	<script src="../assets/js/template/bootstrap.bundle.min.js"></script>
	<script src="../assets/js/template/modernizr.min.js"></script>
	<script src="../assets/js/template/detect.js"></script>
	<script src="../assets/js/template/fastclick.js"></script>
	<script src="../assets/js/template/jquery.slimscroll.js"></script>
	<script src="../assets/js/template/jquery.blockUI.js"></script>
	<script src="../assets/js/template/waves.js"></script>
	<script src="../assets/js/template/wow.min.js"></script>
	<script src="../assets/js/template/jquery.nicescroll.js"></script>
	<script src="../assets/js/template/jquery.scrollTo.min.js"></script>
	<script src="../assets/js/template/app.js"></script>

	<script src="../assets/js/template/sweetalert.min.js"></script>
	<script src="../assets/js/staff/staff.js"></script>
	
	<script> const eventoid = <?php echo $id_evento; ?></script>
</body>

</html>
