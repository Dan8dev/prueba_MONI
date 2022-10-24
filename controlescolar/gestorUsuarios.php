<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 3 ) ){
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
                    <?php echo $usuario["persona"]["nombres"]; ?> <span class="mdi mdi-chevron-down font-15"></span>
                  </span>
								</a>
								<ul class="dropdown-menu">
									<li class="dropdown-divider"></li>
									<li><a href="../editarAccesos.php" class="dropdown-item"> Cambiar contraseña</a></li>
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

		<?php include 'partials/nav.php'; ?>

		</header>
		<!-- End Navigation Bar-->
	</div>
	<!-- header-bg -->

	<div class="wrapper">
		<div class="container-fluid justify-content-center">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-8 col-md-8">
					<div class="page-title-box">
						<div class="row align-items-center">
							<div class="col-md-8">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="tab_alumnos">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								
								<li class="nav-item">
									<a class="nav-link active" id="users-tab" data-toggle="tab" href="#" role="tab" aria-controls="users" aria-selected="true">
										<span class="d-none d-sm-block"><i class="fas fa-chalkboard-teacher"></i>Usuarios</span>
									</a>
								</li>

							</ul>
                            
                            <div class="tab-pane fade active show" id="users" role="tabpanel" aria-labelledby="users-tab">
                                <div class="table-responsive text-left">
                                    <div class="row justify-content-between">
                                        <div class="col">
                                            <h2>Lista de usuarios</h2>
                                        </div>
                                        <div class="col text-right">
                                            <a class="btn btn-primary" id="clickModal" data-toggle="modal" data-target="#modalAgregausers" style="color:white;">
                                                Agregar Usuario
                                            </a>
                                        </div>
                                    </div>
                                    <table id="datatable-tablaUsers" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Nombre(s)</th>
                                            <!--<th>Sexo</th>-->
                                            <th>Correo electrónico</th>
											<th>Rol</th>
                                            <th>Opciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
							<!--FIN TABLA GENERAL DE MAESTROS-->

						</div>
					</div>
				</div>
			</div>
	
			
			
			<!--fin-calificaciones-->
		</div><!--end modal centered-->
    </div>
	<div class="toast-success">Actualización de estatus correcta.</div>
    <!--MODAL AGREGAR-->
    <!-- sample modal content -->
    <div id="modalAgregausers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content pb-4">

                            <div class="modal-header">
                                <h3 class="modal-title m-0" id="CustomLabel"></h3>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            <form id="addU" class="">
								<div class="form-group">
									<label for="">Nombre</label>
									<input type="text" name="names" id="names" class="form-control" placeholder="Nombre(s)" required>
								</div>
								<!-- <div class="form-group">
									<label for="">Apellido paterno</label>
									<input name="apa" id="apa" type="text" class="form-control" placeholder="Apellido paterno" required>
								</div>
								<div class="form-group">
									<label for="">Apellido materno</label>
									<input name="ama" id="ama" type="text" class="form-control" placeholder="Apellido materno" required>
								</div> -->
								<div class="form-group">
									<label for="">Email</label>
									<input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
								</div>
								<input type="hidden" name="dep" value="31">
								<div class="form-group"><label for="">Selecciona el role</label>
									<select name="roles" id="roles" id="" class="form-control" required>
										<option value="" selected>selecciona un role</option>
										<option value="1">Administrador</option>
										<!-- <option value="2">Control escolar UDC</option> -->
										<!-- <option value="3">Areas medicas</option> -->
										<option value="4">Control escolar IESM</option>
										<!-- <option value="3">Desarrollador de contenido</option> -->
									</select>
								</div>
								<button class="btn btn-primary mt-2 float-right">Guardar</button>
							</form>
                        </div><!-- /.modal-content -->
                    </div>
    </div>
    <!--end-modal-->
    <!--FIN MODAL-->
	<!-- Footer -->
	<footer class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
				© <?php echo date('Y'); ?> MONI <span class="d-none d-md-inline-block">IESM-UDC-TSU-CONACON TI</span>
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
	
	<script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	<script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>

	<!--Required datatables js-->
	<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

	<!--Buttons examples-->
	<script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="../assets/plugins/datatables/buttons.bootstrap4.min.js"></script>

	<script src="../assets/plugins/datatables/jszip.min.js"></script>
	<script src="../assets/plugins/datatables/pdfmake.min.js"></script>
	<script src="../assets/plugins/datatables/vfs_fonts.js"></script>
	<script src="../assets/plugins/datatables/buttons.html5.min.js"></script>
	<script src="../assets/plugins/datatables/buttons.print.min.js"></script>
	<script src="../assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
	<script src="../assets/plugins/datatables/dataTables.keyTable.min.js"></script>
	<script src="../assets/plugins/datatables/dataTables.scroller.min.js"></script>
	<script src="../assets/js/template/sweetalert.min.js"></script>
	<script src="../assets/js/controlescolar/controlescolar.js"></script>
	<script src="../assets/js/controlescolar/directorio.js"></script>
	<script src="../assets/pages/sweet-alert.init.js"></script>

	<!--Responsive examples-->
	<script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
	<script src="../assets/plugins/datatables/responsive.bootstrap.min.js"></script>

	<!--Datatable init js-->
	<script src="../assets/pages/datatables.init.js"></script>

	<script src="../assets/js/template/app.js"></script>
	
	
<?php 
  $str = json_encode($usuario);
  echo("<script> usrInfo = JSON.parse('{$str}');</script>");
?>
</body>

</html>
