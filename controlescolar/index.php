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

	<link href="../assets/css/botones.css" rel="stylesheet" type="text/css">
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
								<a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
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

	<div class="wrapper">
		<div class="mx-4">
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
							<?php if($_SESSION["usuario"]['estatus_acceso'] == 1){?>
								<li class="nav-item"><!--generar directorio-tap-->
									<a class="nav-link active" id="directorio-tab" data-toggle="tab" href="#directorio" role="tab" aria-controls="directorio" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Directorio</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link " id="alumnos-tab" data-toggle="tab" href="#alumnos" role="tab" aria-controls="alumnos" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-folder-open"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-folder-open"></i> Expedientes</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="maestros-tab" data-toggle="tab" href="#maestros" role="tab" aria-controls="maestros" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-chalkboard-teacher"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-chalkboard-teacher"></i> Maestros</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="sessions-tab" data-toggle="tab" href="#sessions" role="tab" aria-controls="sessions" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-chalkboard-teacher"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-chalkboard-teacher"></i> Clases</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="alum-tab" data-toggle="tab" href="#alum" role="tab" aria-controls="alum" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-user-graduate"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-user-graduate"></i> Credenciales</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="examenes-tab" data-toggle="tab" href="#examenes" role="tab" aria-controls="examenes" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-user-check"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-user-check"></i> Exámenes</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="banco-examenes-tab" data-toggle="tab" href="#banco-examenes" role="tab" aria-controls="examenes" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Banco de preguntas</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="asistencias-tab" data-toggle="tab" href="#asistencias" role="tab" aria-controls="asistencias" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Asistencias</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="sesionesenvivo-tab" data-toggle="tab" href="#sesionesenvivo" role="tab" aria-controls="sesionesenvivo" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-eye"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-eye"></i> sesiones en vivo</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="calificaciones-tab" data-toggle="tab" href="#calificaciones" role="tab" aria-controls="calificaciones" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-bookmark"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-bookmark"></i> Calificaciones</span>
									</a>
								</li>

								
								<li class="nav-item">
									<a class="nav-link" id="servicio-social-tab" data-toggle="tab" href="#servicio-social" role="tab" aria-controls="servicio-social" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-university"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-university"></i> Servicio Social</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="certificaciones-tab" data-toggle="tab" href="#certificaciones" role="tab" aria-controls="certificaciones" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-stream"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-stream"></i> Certificaciones</span>
									</a>
								</li>
								

								<?php $active = 'active'; 
								$activeNoAccess = '';
							}elseif($_SESSION["usuario"]['estatus_acceso'] == 4){ 
								$active = 'active'; 
								$activeNoAccess = 'active'; 	?>
								<li class="nav-item"><!--generar directorio-tap-->
									<a class="nav-link active" id="directorio-tab" data-toggle="tab" href="#directorio" role="tab" aria-controls="directorio" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Directorio</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="maestros-tab" data-toggle="tab" href="#maestros" role="tab" aria-controls="maestros" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-chalkboard-teacher"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-chalkboard-teacher"></i> Maestros</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="sessions-tab" data-toggle="tab" href="#sessions" role="tab" aria-controls="sessions" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-chalkboard-teacher"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-chalkboard-teacher"></i> Clases</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link " id="alumnos-tab" data-toggle="tab" href="#alumnos" role="tab" aria-controls="alumnos" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-folder-open"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-folder-open"></i> Expedientes</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="examenes-tab" data-toggle="tab" href="#examenes" role="tab" aria-controls="examenes" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-user-check"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-user-check"></i> Exámenes</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="banco-examenes-tab" data-toggle="tab" href="#banco-examenes" role="tab" aria-controls="examenes" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Banco de preguntas</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="asistencias-tab" data-toggle="tab" href="#asistencias" role="tab" aria-controls="asistencias" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Asistencias</span>
									</a>
								</li>
								
								<li class="nav-item">
									<a class="nav-link" id="sesionesenvivo-tab" data-toggle="tab" href="#sesionesenvivo" role="tab" aria-controls="sesionesenvivo" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-eye"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-eye"></i> sesiones en vivo</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="calificaciones-tab" data-toggle="tab" href="#calificaciones" role="tab" aria-controls="calificaciones" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-bookmark"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-bookmark"></i> Calificaciones</span>
									</a>
								</li>
								<!-- <li class="nav-item">
									<a class="nav-link" id="asignargrupos-tab" data-toggle="tab" href="#asignargrupos" role="tab" aria-controls="asignargrupos" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Asignar grupos</span>
									</a>
								</li> -->
								<?php } ?>
							</ul>
							<button class = "ir-arriba fas fa-angle-double-up"></button>
							<!--TABLA GENERAL DE ALUMNOS-->
							<div class="tab-content bg-light">
								<div class="tab-pane fade <?=$active?> show" id="directorio" role="tabpanel" aria-labelledby="directorio-tab">
									<h2>Directorio</h2>
									<div class="table-responsive">
										<div class="card-body">
											<table class="table" id="table_directorio">
												<thead>
													<th>Nombre</th>
													<th>Teléfono</th>
													<th>Correo</th>
													<th>Dirección</th>
													<th for-filter>Carrera</th>
													<th for-filter strict>Generación</th>
													<th>Matrícula</th>
													<th>Contraseña</th>
													<th></th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="tab-pane fade show" id="certificaciones" role="tabpanel" aria-labelledby="certificaciones-tab">
									<div class="col-lg-12">
										<ul class="nav nav-tabs" role="tablist">

											<li class="nav-item">
												<a class="nav-link active" id="expediciones-tab" data-toggle="tab" href="#expediciones" role="tab" aria-controls="expediciones" aria-selected="true">
													<span class="d-block d-sm-none"><i class="fas fa-project-diagram"></i></span>
													<span class="d-none d-sm-block"><i class="fas fa-project-diagram"></i> Expediciones</span>
												</a>
											</li>

											<li class="nav-item">
												<a class="nav-link " id="prospectoscer-tab" data-toggle="tab" href="#prospectoscer" role="tab" aria-controls="prospectoscer" aria-selected="true">
													<span class="d-block d-sm-none"><i class="fas fa-book"></i></span>
													<span class="d-none d-sm-block"><i class="fas fa-book"></i> Prospectos</span>
												</a>
											</li>

											<li class="nav-item">
												<a class="nav-link " id="generarXml-tab" data-toggle="tab" href="#generarXml" role="tab" aria-controls="generarXml" aria-selected="true">
													<span class="d-block d-sm-none"><i class="fas fa-upload"></i></span>
													<span class="d-none d-sm-block"><i class="fas fa-upload"></i> Generar XML</span>
												</a>
											</li>
											
											<li class="nav-item">
												<a class="nav-link " id="descargarXML-tab" data-toggle="tab" href="#descargarXML" role="tab" aria-controls="descargarXML" aria-selected="true">
													<span class="d-block d-sm-none"><i class="fas fa-download"></i></span>
													<span class="d-none d-sm-block"><i class="fas fa-download"></i> Descargar XML</span>
												</a>
											</li>

											<li class="nav-item">
												<a class="nav-link " id="resultadoscer-tab" data-toggle="tab" href="#resultadoscer" role="tab" aria-controls="resultadoscer" aria-selected="true">
													<span class="d-block d-sm-none"><i class="fas fa-certificate"></i></span>
													<span class="d-none d-sm-block"><i class="fas fa-certificate"></i> Resultados</span>
												</a>
											</li>

											<li class="nav-item">
												<a class="nav-link " id="reportesCer-tab" data-toggle="tab" href="#reportesCer" role="tab" aria-controls="reportesCer" aria-selected="true">
													<span class="d-block d-sm-none"><i class="fas fa-chart-pie"></i></span>
													<span class="d-none d-sm-block"><i class="fas fa-chart-pie"></i> Reportes</span>
												</a>
											</li>
										</ul>

										<div class="tab-content bg-light">
											<div class="tab-pane fade show active" id="expediciones" role="tabpanel" aria-labelledby="expediciones">
												<div class="row">
													<div class="col-md-6">
														<h3>Expediciones</h3>
													</div>
													<div class="col-md-6 text-right">
														<button class ="btn btn-primary d-none" id="buttonNuevaFechaCer">Nueva Fecha</button>
													</div>

							 					</div>

												<div class="row">
													<div class="col-md-6">
														<label for="CampusCert">Campus</label>
														<select class ="form-control" name="CampusCert" id="CampusCert" required>
															<option selected disabled>Seleccione una opción</option>
															<option value = "300478">UNIVERSIDAD DEL CONDE</option>
														</select>
													</div>

													<div class="col-md-6">
														<label for="carrerasCert">Carreras Disponibles</label>
														<select class ="form-control" name="carrerasCert" id="carrerasCert" required>
															<option selected disabled>Seleccione una opción</option>
														</select>
													</div>

												</div>

												<div class="table-responsive">
													<div class="card-body">
														<table class="table" id="tableExpedicionesRegistradas" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
															<thead>
																<th>#</th>
																<th>Tipo de Expedición</th>
																<th>Fecha de Expedición</th>
																<th>Firmantes</th>
																<th>Modificar</th>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>

							 				</div>
											
											<div class="tab-pane fade" id="prospectoscer" role="tabpanel" aria-labelledby="prospectoscer">
												<h3>Prospectos</h3>
												<div class="row">
													<div class="col-md-6">
														<label for="carrerasExp">Carreras Disponibles</label>
														<select class ="form-control" name="carrerasExp" id="carrerasExp" required>
															<option selected disabled>Seleccione una opción</option>
														</select>
													</div>

													<div class="col-md-6">
														<label for="fechasExp">Fechas de Expedición</label>
														<select class ="form-control" name="fechasExp" id="fechasExp" required>
															<option selected disabled>Seleccione una opción</option>
														</select>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12">
														<div class="table-responsive">
															<div class="card-body">
																<table class="table" id="tableExpedicionesProspectos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
																	<thead>
																		<th>#</th>
																		<th>Alumno</th>
																		<th>Estatus</th>
																		<th>Acciones</th>
																	</thead>
																	<tbody>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
							 				</div>

											<div class="tab-pane fade" id="generarXml" role="tabpanel" aria-labelledby="generarXml">
											 	<h3>Generar XML</h3>
												 <div class="row">
													<div class="col-md-6">
														<label for="carrerasXmlUp">Carreras Disponibles</label>
														<select class ="form-control" name="carrerasXmlUp" id="carrerasXmlUp" required>
															<option selected disabled>Seleccione una opción</option>
														</select>
													</div>

													<div class="col-md-6">
														<label for="fechasXmlUp">Fechas de Expedición</label>
														<select class ="form-control" name="fechasXmlUp" id="fechasXmlUp" required>
															<option selected disabled>Seleccione una opción</option>
														</select>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12">
														<div class="table-responsive">
															<div class="card-body">
																<table class="table" id="tableExpedicionesXmlUp" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
																	<thead>
																		<th>#</th>
																		<th>Informacion</th>
																		<th>Acciones</th>
																	</thead>
																	<tbody>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>

							 				</div>

											<div class="tab-pane fade" id="descargarXML" role="tabpanel" aria-labelledby="descargarXML">
												<div class="row">
													<div class="col-md-6">
														<h3>Descargar XML</h3>
													</div>
													<div class="col-md-6 text-right">
														<button onClick ="VerificarCreacionZip()" class ="btn btn-primary d-none" id="buttonNuevoZip">Descargar Zip</button>
													</div>

							 					</div>

												<div class="row">
													<div class="col-md-6">
														<label for="carrerasXmlDown">Carreras Disponibles</label>
														<select class ="form-control" name="carrerasXmlDown" id="carrerasXmlDown" required>
															<option selected disabled>Seleccione una opción</option>
														</select>
													</div>

													<div class="col-md-6">
														<label for="fechasXmlDown">Fechas de Expedición</label>
														<select class ="form-control" name="fechasXmlDown" id="fechasXmlDown" required>
															<option selected disabled>Seleccione una opción</option>
														</select>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12">
														<div class="table-responsive">
															<div class="card-body">
																<table class="table" id="tableExpedicionesXmlDown" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
																	<thead>
																		<th>#</th>
																		<th>Alumnos</th>
																		<th>Estatus</th>
																		<th>Folio de control</th>
																	</thead>
																	<tbody>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
							 				</div>

											<div class="tab-pane fade" id="resultadoscer" role="tabpanel" aria-labelledby="resultadoscer">
												<h3>Resultados</h3>
												<div class="row">
													<div class="col-md-12">
														<div class="card-body">
															<table class="table" id="tableReportesCert" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
																<thead>
																	<th>Alumno</th>
																	<th>Informacion</th>
																	<th>Acciones</th>
																	<th>Modificar</th>
																</thead>
																<tbody>
																</tbody>
															</table>
														</div>
													</div>												
												</div>
												<!-- Datatable con la informacion de todos los alumnos /Busqueda por /Alumno/Nombre/Corro -->
							 				</div>

											<div class="tab-pane fade" id="reportesCer" role="tabpanel" aria-labelledby="reportesCer">
												<h3>Reportes</h3>
												<div class="row">
													<div class="col-md-6">
														<label for="carrerasreportesCer">Reportes Disponibles</label>
														<select class ="form-control" name="carrerasreportesCer" id="carrerasreportesCer" required>
															<option selected disabled>Seleccione el tipo de Busqueda</option>
															<option value="1">Carrera</option>
															<option value="2">Generación</option>
															<option value="3">Alumno</option>
															<option value="4">Folio de Control</option>
															<option value="5">Fecha de Expedición</option>
														</select>
													</div>
							 					</div>

												<div class="row">
													<div class="col-md-12">
														<div class="table-responsive">
															<div class="card-body">
																<table class="table" id="tableExpedicionesReportes" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
																	<thead>
																		<th>Alumno</th>
																		<th>Informacion</th>
																		<th>Acciones</th>
																		<th>Modificar</th>
																	</thead>
																	<tbody>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
							 				</div>
							 			</div>
										
									</div>
								</div>

								<div class="tab-pane fade" id="servicio-social" role="tabpanel" aria-labelledby="servicio-social-tab">	
									<div class="col-lg-12">
                                    <ul class="nav nav-tabs" role="tablist">
										<li class="nav-item">
                                            <a class="nav-link active" id="procesos-tab" data-toggle="tab" href="#procesos" role="tab" aria-controls="procesos" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-book"></i></span>
                                                <span class="d-none d-sm-block"><i class="fas fa-book"></i> Procesos</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="formatos-tab" data-toggle="tab" href="#formatos" role="tab" aria-controls="formatos" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-clipboard"></i></span>
                                                <span class="d-none d-sm-block"><i class="fas fa-clipboard"></i> Formatos</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="AsignarAlumnos-tab" data-toggle="tab" href="#AsignarAlumnos" role="tab" aria-controls="AsignarAlumnos" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-address-book"></i></span>
                                                <span class="d-none d-sm-block"><i class="fas fa-address-book"></i> Asignar Alumnos</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="Revisiones-tab" data-toggle="tab" href="#Revisiones" role="tab" aria-controls="Revisiones" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fa fa-file-alt"></i></span>
                                                <span class="d-none d-sm-block"><i class="fa fa-file-alt"></i> Revisiones</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="Correcciones-tab" data-toggle="tab" href="#Correcciones" role="tab" aria-controls="Correcciones" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fa fa-edit"></i></span>
                                                <span class="d-none d-sm-block"><i class="fa fa-edit"></i> Correcciones</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="DocumentosRec-tab" data-toggle="tab" href="#DocumentosRec" role="tab" aria-controls="DocumentosRec" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="far fa-address-card"></i></span>
                                                <span class="d-none d-sm-block"><i class="far fa-address-card"></i> Solicitar Originales</span>
                                            </a>
                                        </li>

										<!--<li class="nav-item">
                                            <a class="nav-link" id="Reportes-tab" data-toggle="tab" href="#Reportes" role="tab" aria-controls="Reportes" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                                <span class="d-none d-sm-block"><i class="fas fa-list"></i> Reportes</span>
                                            </a>
										</li>-->
                                    </ul>
									
										<div class="tab-content bg-light">
											
											<div class="tab-pane fade show active" id="procesos" role="tabpanel" aria-labelledby="procesos-tab">
												<h2>Procesos</h2>
												<form id="formulario-procesos-nuevos">
													<div class="row form-group">
														<div class="col-md-6">
															<label for="NombreNuevoProceso">Nombre:</label>
															<input class = "form-control" type="text" name="NombreNuevoProceso" id="NombreNuevoProceso" placeholder = "Escriba el nombre del nuevo proceso" required>
														</div>
														<div class="col-md-6">
															<label for="OrdenNuevoProceso">Orden:</label>
															<input class = "form-control" type="number" name="OrdenNuevoProceso" id="OrdenNuevoProceso" placeholder ="Escriba el orden del nuevo proceso" onlyNum required>
														</div>
													</div>
													<div class="col text-right form-group">
														<button class = "btn btn-primary waves-effect waves-light" type="submit">Registrar</button>
													</div>
												</form>
												<div class="table-responsive">
													<table id = "tabla-procesos-nuevo" class="table table-striped table-bordered nowrap" style="width: 100%;">
														<thead>
															<tr>
																<th>No.</th>
																<th>Proceso</th>
																<th>Orden</th>
																<th>Opciones</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>

											<div class="tab-pane fade" id="formatos" role="tabpanel" aria-labelledby="formatos-tab">
												<h2>Formatos</h2>
												<form id="formulario-formatos-nuevos">
													<div class="row form-group">
														<div class="col-md-12">
															<h4><label for="formatosexistentes">Seleccione el proceso:</label></h4>
															<select class="form-control" name="formatosexistentes" id="formatosexistentes" required>
																<option selected disabled>Seleccione un proceso</option>
																<!--<option value="1">Para Iniciar</option>-->
																<!--<option value="2">Al Iniciar</option>-->
																<!--<option value="3">Durante</option>-->
																<!--<option value="4">Al concluir</option>-->
															</select>
														</div>
														
													</div>
													
													<div class="row  form-group">
														<div class="col-md-6">
															<h4><label for="NuevosFormatos">Añadir nuevo formato al proceso:</label></h4>
														</div>
														<div class="col text-right">
															<button class = "btn btn-primary waves-effect waves-light" type="submit">Asignar Formato</button>
														</div>
													</div>

													<div class="row form-group" id  ="NuevosFormatos">
														<div class="col-md-4">
															<label for="nombre-formato">Nombre:</label>
															<input class ="form-control" type="text" id ="nombreformato" name ="nombreformato" placeholder ="Escriba el nombre del nuevo formato" required>
														</div>

														<div class="col-md-4">
															<label for="archivoformato">Archivo del Formato:</label>
															<input class ="form-control" type="file" name="archivoformato" id="archivoformato" required>
														</div>
														<div class="col-md-4">
															<label for="archivoformato">Veces que debe enviarse</label>
															<select class ="form-control" name="vecesenvio" id="vecesenvio" required>
																<option value="0" selected disabled>Seleccione una opción</option>
																<option value="1">1</option>
																<option value="6">6</option>
															</select>
														</div>
													</div>
													
												</form>
												<div class="table-responsive">
													<table id = "tabla-documentos-proceso" class="table table-striped table-bordered nowrap" style="width: 100%;">
														<thead>
															<tr>
																<th>Nombre</th>
																<th>Archivo</th>
																<th>No. de envios</th>
																<th>Opciones</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>			
												</div>
											</div>
											 <!--Termina div-TAB de NO ACREDITADOS-->

											<div class="tab-pane fade" id="AsignarAlumnos" role="tabpanel" aria-labelledby="AsignarAlumnos-tab">
												<h2>Asignar Alumnos</h2>
												<form id = "formulario-servicio-alumnos">
													<div class="row form-group">
														<div class="col-md-4 col-sm-6">
															<label for="carrera-servicio">Carrera:</label>
															<select class = "form-control"name="carrera-servicio" id="carrera-servicio" required>
																<option selected disabled>Seleccione una carrera</option>
															</select>
														</div>
														<div class="col-md-4 col-sm-6">
															<label for="generaciones-servicio">Generación:</label>
															<select class = "form-control"name="generaciones-servicio" id="generaciones-servicio" required>
																<option selected disabled>Seleccione una generación</option>
															</select>
														</div>
														<div class="col-md-4 col-sm-6">
															<label for="articulo-servicio">Articulo:</label>
															<select class = "form-control"name="articulo-servicio" id="articulo-servicio" required>
																<option selected disabled>Seleccione el articulo</option>
															</select>
														</div>
													</div>
													<div class="col text-right form-group">
														<button class = "btn btn-primary waves-effect waves-light" type="submit" disabled id="button-servicio">Asignar al servicio social</button>
													</div>
												</form>
												<div class="table-responsive">
													<table id="alumnos-select-servicio" class="table table-striped table-bordered nowrap" style="width: 100%;">
														<thead>
														<tr>
															<th>Alumno</th>
															<th>Matrícula</th>
															<th>Articulo</th>
															<th>Seleccionar</th>
														</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div> <!--Termina tab de Reporte-->

											<div class="tab-pane fade" id="Revisiones" role="tabpanel" aria-labelledby="Revisiones-tab">
												<h2>Revisiones</h2>
												<label for="revisiones-select-servicio">Archivos entregados por alumno</label>
												<div class="table-responsive">
													<table id="revisiones-select-servicio" class="table table-striped table-bordered nowrap" style="width: 100%;">
														<thead>
														<tr>
															<th>Alumnos</th>
															<th>Formato</th>
															<th>Acciones</th>
														</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div> <!--Termina tab de Boletas-->

											<div class="tab-pane fade" id="Correcciones" role="tabpanel" aria-labelledby="Correcciones-tab">
												<h2>Correcciones</h2>
												<label for="correcciones-select-servicio">Archivos corregidos por alumno</label>
												<div class="table-responsive">
													<table id="correcciones-select-servicio" class="table table-striped table-bordered nowrap" style="width: 100%;">
														<thead>
														<tr>
															<th>Alumnos</th>
															<th>Formato</th>
															<th>Acciones</th>
														</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div> <!--Termina tab de Kardex-->

											<div class="tab-pane fade" id="DocumentosRec" role="tabpanel" aria-labelledby="DocumentosRec-tab">
												<h2>Documentos recibidos</h2>
												<label for="Solicitar-select-servicio">Archivos corregidos por alumno</label>
												<div class="table-responsive">
													<table id="Solicitar-select-servicio" class="table table-striped table-bordered nowrap" style="width: 100%;">
														<thead>
														<tr>
															<th>Alumnos</th>
															<th>Formato</th>
															<th>Acciones</th>
														</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>
											
											<div class="tab-pane fade" id="Reportes" role="tabpanel" aria-labelledby="Reportes-tab">
												<h2> Reportes</h2>
											</div>
											
										</div>
									</div>
								<!--Añadir al ui acordeon-->
								</div>
								<div class="tab-pane fade" id="asignargrupos" role="tabpanel" aria-labelledby="asignargrupos-tab">
									<div class="table-responsive text-left">												
										<h2>Asigacion de grupos</h2>
										<form class="hidden float-right mb-3" id="formAddGroup">
											<input type="hidden" name="action" value="ActualizarGrupo"/>
											<input type="hidden" id="NomGroup" name="NomGroup">
											<input type="hidden" id="idGen" name="idGen">
											<button type="submit" class="btn-primary p-2">Guardar</button>
										</form>
										<div class="form-group">
											<label for="selectBuscarAsignarGrupo"><h4><strong>Selecciona la carrera</strong></h4></label>
											<select class="form-control" id="selectBuscarAsignarGrupo" name="selectBuscarAsignarGrupo">
											</select>
										</div>
										<div class="form-group">
											<label for="selectBuscarGeneracionesGrupo"><h4><strong>Selecciona la Generación</strong></h4></label>
											<select class="form-control" id="selectBuscarGeneracionesGrupo" name="selectBuscarGeneracionesGrupo" required>
											</select>
										</div>
										<div class="form-group hidden" id="selectIESMAG">
											<label for="selectBuscarAGrupo"><h4><strong>Selecciona un grupo</strong></h4></label>
											<select class="form-control" id="selectBuscarAGrupo" name="selectBuscarAGrupo">
											</select>
										</div>
										<div class="table-responsive TBNR">
											<table id="datatable-asignar-grupos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
												<thead>
												<tr>
													<th>#</th>
													<th>Nombre</th>
													<th>Grupo</th>
												</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="alum" role="tabpanel" aria-labelledby="alum-tab">
									<div class="container-liquid">
									<h2>Solicitudes de Credenciales</h2>
										<div class="table-responsive">
											<table id="datatable-tablaCredencialesAlumno" class="table table-striped table-bordered nowrap" style="width: 100%;">
												<thead>
													<tr>
														<th>Foto</th>
														<th>Alumno</th>
														<th>Ultima Solicitud</th>
														<th>Opciones</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										
									</div>
								</div>

								<div class="tab-pane fade" id="alumnos" role="tabpanel" aria-labelledby="alumnos-tab">
									<h2>Expedientes</h2>					
									<div class="table-responsive text-left">													
										<div class="row mb-3">
											<div class="col-sm-12 col-md-6">
												<label for="labelBuscarExpedienteMat">Selecciona la carrera</label>
												<div id="S_carrerasExpedientesC"></div>
											</div>
											<div class="col-sm-12 col-md-6">
												<label for="labelBuscarExpedienteMat">Selecciona la generación</label>
												<div id="S_generacionesExpedientesC"> </div>
											</div>
											<!-- <div class="col-sm-12 col-md-6 mt-3">
												<label for="labelBuscarExpedienteMat">Selecciona el grupo</label>
												<div id="groupsG"></div>
											</div> -->
										</div>
										<label for=""><strong>Instrucciones de Busqueda: </strong><br> Para realizar la busqueda de usuarios con Prorroga, en el buscador escriba:</label><br>
				
										<label for="">-Usuarios con prorroga Digital :<strong> D[</strong></label><br>
										<label for="">-Usuarios con prorroga Fisica:<strong> F[</strong></label><br>
										<strong><label for="">(Es necesario escribir la letra indicada y el corchete juntos (sin espacios) para realizar la busqueda correctamente).</label></strong><br><br>
				
										<table id="datatable-tablaAlumnos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
												<tr>
													<th>Nombre(s)</th>
													<th>Generación</th>
													<th>Correo</th>
													<th>Teléfono</th>
													<th>Documentos</th>
													<th>Opciones</th>
													<th>Tipo</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
				
									</div>	
								</div>

							<!--FIN TABLA GENERAL DE ALUMNOS-->
								<div class="tab-pane fade" id="maestros" role="tabpanel" aria-labelledby="maestros-tab">
									<div class="table-responsive text-left">
										<div class="row justify-content-between">
											<div class="col">
												<h2>Maestros</h2>
											</div>
											<div class="col text-right">
												<a class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMaestro" style="color:white;">
													Agregar Maestro
												</a>
											</div>
										</div>
										<table id="datatable-tablaMaestros" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
											<tr>
												<th>Nombre(s)</th>
												<!--<th>Sexo</th>-->
												<th>Correo electrónico</th>
												<th>Teléfono</th>
												<th>Opciones</th>
											</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							<!--FIN TABLA GENERAL DE MAESTROS-->
								<div class="tab-pane fade" id="sessions" role="tabpanel" aria-labelledby="sessions-tab">
									<div class="table-responsive text-left">
										<div class="row justify-content-between">
											<div class="col">
												<h2>Clases</h2>
											</div>
											<div class="col text-right">
												<a class="btn btn-primary" onclick="AsignarClase(0,'carreras');" style="color:white;">
													Agregar clases
												</a>
											</div>
										</div>
										<table id="table-sessions" class="table" style="width: 100%;">
											<thead>
											<tr>
												<th>Nombre clase</th>
												<th>Fecha y hora</th>
												<th>Materia</th>
												<th>Docente</th>
												<th>Correo</th>
												<th>Carrera</th>
												<th>Generación</th>
												<th>Opciones</th>
											</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<div class="tab-pane fade" id="asistencias" role="tabpanel" aria-labelledby="asistencias-tab">
									<!-- <div class="table-responsive text-left">											
										<h2>Asistencia a Eventos</h2>
										<table id="datatable-tablaAsistecias" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
											<tr>
												<th>Evento</th>
												<th>Fecha</th>
												<th>Opciones</th>
											</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
		
									<div class="table-responsive text-left">												
										<h2>Asistencia a Talleres</h2>
										<table id="datatable-tablaAsisteciasTalleres" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
											<tr>
												<th>Taller</th>
												<th>Evento</th>
												<th>Fecha</th>
												<th>Opciones</th>
											</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>-->
									<div class="table-responsive text-left">												
										<h2>Asistencia a Clases</h2>
										<div class="form-group">
											<label for="selectBuscarAsistencias"><h4><strong>Selecciona la carrera</strong></h4></label>
											<select class="form-control" id="selectBuscarAsistencias" name="selectBuscarAsistencias">
											</select>
										</div>
										<div class="form-group">
											<label for="selectBuscarGeneraciones"><h4><strong>Selecciona la Generación</strong></h4></label>
											<select class="form-control" id="selectBuscarGeneraciones" name="selectBuscarGeneraciones" required>
											</select>
										</div>
										<div class="form-group hidden" id="selectIESM">
											<label for="selectBuscarGrupo"><h4><strong>Selecciona un grupo</strong></h4></label>
											<select class="form-control" id="selectBuscarGrupo" name="selectBuscarGrupo">
											</select>
										</div>
										<div class="table-responsive TBNR">
											<table id="datatable-tablaAsisteciasClases" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
												<thead>
												<tr>
													<th>Materia</th>
													<th>Clase</th>
													<th>Fecha</th>
													<th>Opciones</th>
												</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										<br>

										<div class="table-responsive d-none">
											<table id="datatable-asistenciaTransmision" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
												<thead>
												<tr>
													<th>Evento</th>
													<th>Fecha</th>
													<th>Opciones</th>
												</tr>
												</thead>
												<tbody>
													<th>Jornada Académica, 30 de septiembre 2022</th>
													<th>30/09/2022 04:00 p.m</th>
													<th><a class="btn btn-primary" target="_blank" href="asistenciasEnvivo.php">Ver Asistentes</a></th>
												</tbody>
											</table>
										</div>

									</div>
								</div>

								<div class="tab-pane fade" id="sesionesenvivo" role="tabpanel" aria-labelledby="sesionesenvivo-tab">
									<h2>Sesiones en vivo</h2>		
									<div class="table-responsive">
										<table id="datatable-tablasesionesenvivo" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
											<tr>
												<th>CLASE</th>
												<th>MATERIA</th>
												<th>MAESTRO</th>
												<th>GENERACION/CARRERA</th>
												<th>FECHA/HORA</th>
												<th>OPCIONES</th>
											</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>							
								</div>

								<div class="tab-pane fade" id="examenes" role="tabpanel" aria-labelledby="examenes-tab">
									<div class="table-responsive text-left">
										<div class="row justify-content-between">
											<div class="col">
												<h2>Exámenes</h2>
											</div>
											<div class="col text-right">
												<button id="btn-crear-examen" type="button" class="btn btn-primary waves-effect waves-light">
													Crear Examen
												</button>
											</div>
										</div>
										<div class="row mb-3">
											<div class="col-sm-6 col-md-6">
												<label for="selectBuscarExamenesCarrera"><h4><strong>Selecciona la carrera</strong></h4></label>
												<select class="form-control" id="selectBuscarExamenesCarrera" name="selectBuscarExamenesCarrera">
												</select>
											</div>
											<div class="col-sm-6 col-md-6">
												<div id="divGeneracionesTable" style="display: none;">
													<label for="selectBuscarExamenesGeneracion"><h4><strong>Selecciona la generación</strong></h4></label>
													<select class="form-control" id="selectBuscarExamenesGeneracion" name="selectBuscarExamenesGeneracion">
													</select>
												</div>
											</div>
										</div>
										
										<table id="datatable-tablaExamenes" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
												<tr>
													<th>Examen</th>
													<th>Materia</th>
													<th>Maestro</th>
													<th>Fecha Inicio</th>
													<th>Fecha Fin</th>
													<th>Opciones</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
										<b><em>*Sólo el docente puede modificar la información de exámenes.</em></b>
									</div>
								</div>
								<!--FIN TABLA GENERAL DE ASISTENCIAS A EVENTOS-->
								<!--Añadir al ui acordion-->
								<div class="tab-pane fade" id="banco-examenes" role="tabpanel" aria-labelledby="banco-examenes-tab">
									<div class="table-responsive text-left">
										<div class="row justify-content-between">
											<div class="col">
												<h2>Exámenes</h2>
												<h3>Selecciona la carrera para obtener preguntas.</h3>
											</div>
											<div class="col text-right">
												<button id="btn-crear-examen-banco" type="button" class="btn btn-primary waves-effect waves-light" disabled>
													Crear Examen
												</button>
											</div>
										</div>
										<div class="row mb-3">
											<div class="col-sm-6 col-md-6">
												<label for="selectBuscarExamenesCarrera_Banco"><h4><strong>Carreras:</strong></h4></label>
												<select class="form-control" id="selectBuscarExamenesCarrera_Banco" name="selectBuscarExamenesCarrera_Banco">
												</select>
											</div>
										</div>
										
										<table id="Tabla-Banco-preguntas" class="table table-striped table-bordered" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
												<tr>
													<th>Pregunta</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
										<b><em>*Sólo el docente puede modificar la información de exámenes.</em></b>
									</div>
								</div>
								<div class="tab-pane fade" id="calificaciones" role="tabpanel" aria-labelledby="calificaciones-tab">

									<div class="col-lg-12">
                                    <ul class="nav nav-tabs" role="tablist">

										<li class="nav-item">
                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-pen"></i></span>
                                                <span class="d-none d-sm-block"><i class="fas fa-pen"></i> Asignar</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="Reporte-tab" data-toggle="tab" href="#Reporte" role="tab" aria-controls="Reporte" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-clipboard"></i></span>
                                                <span class="d-none d-sm-block"><i class="fas fa-clipboard"></i> Reporte por semestre</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="Boletas-tab" data-toggle="tab" href="#Boletas" role="tab" aria-controls="Boletas" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-scroll"></i></span>
                                                <span class="d-none d-sm-block"><i class="fas fa-scroll"></i> Boletas</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="Kardex-tab" data-toggle="tab" href="#Kardex" role="tab" aria-controls="Kardex" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fa fa-file-alt"></i></span>
                                                <span class="d-none d-sm-block"><i class="fa fa-file-alt"></i> Kardex</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fa fa-archive"></i></span>
                                                <span class="d-none d-sm-block"><i class="fa fa-archive"></i> No acreditados</span>
                                            </a>
                                        </li>

										<li class="nav-item">
                                            <a class="nav-link" id="Certificaciones-tab" data-toggle="tab" href="#Certificaciones" role="tab" aria-controls="Certificaciones" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fa fa-medal"></i></span>
                                                <span class="d-none d-sm-block"><i class="fa fa-medal"></i> Titulados</span>
                                            </a>
                                        </li>

										
                                    </ul>
                                    <div class="tab-content bg-light">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
										<h2>Calificaciones</h2>
											<div class="col-12 row">
												<div class="col-sm-12 col-md-6  col-lg-4">
												<label for="carrerasCalificaciones">Selecciona una carrera</label>
												<select id="carrerasCalificaciones" class="form-control">
													<option disabled selected>Seleccione una carrera</option>
												</select>
												</div>
								
												<div class="col-sm-12 col-md-6 col-lg-4">
												<label for="generacionesCalificaciones">Selecciona una generación</label>
												<select id="generacionesCalificaciones" class="form-control">
													<option disabled selected>Seleccione una generación</option>
												</select>
												</div>

												<div class="col-sm-12 col-md-6 col-lg-4">
												<label for="cicloCalificaciones">Seleccione un ciclo para visualizar las materias</label>
												<select id="cicloCalificaciones" class="form-control">
													<option disabled selected>Seleccione un ciclo</option>
												</select>
												</div>
											</div>
												<div class="col-12 table-responsive mt-3">
												<!--<h3>Alumnos en curso</h3>-->
												<table id="datatable-tablaCalificaciones" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
												<thead>
													<th>Materia</th>
													<th></th>
												</thead>
												<tbody>
													
												</tbody>
												</table>
											</div>
                                        </div>

                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
											<h2>No acreditados</h2>
											<div class="col-12 row">
												<div class="col-sm-12 col-md-6 col-lg-3">
													<label for="carrerasCalificaciones_noAcre">Selecciona una carrera</label>
													<select id="carrerasCalificaciones_noAcre" class="form-control">
														<option disabled selected>Seleccione una carrera</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-6 col-lg-3">
													<label for="generacionesCalificaciones_noAcre">Selecciona una generación</label>
													<select id="generacionesCalificaciones_noAcre" class="form-control">
														<option disabled selected>Seleccione una generación</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-6 col-lg-3">
													<label for="cicloCalificaciones_noAcre">Seleccione un ciclo</label>
													<select id="cicloCalificaciones_noAcre" class="form-control">
														<option disabled selected>Seleccione un ciclo</option>
													</select>
												</div>
											
												<div class="col-sm-12 col-md-6 col-lg-3">
													<label for="MateriaCalificacion_noAcre">Seleccione una materia</label>
													<select id="MateriaCalificacion_noAcre" class="form-control">
														<option disabled selected>Seleccione una materia</option>
													</select>
												</div>
											</div>

												<div class="col-12 row justify-content-between">
														<div class="col-lg-6 col-sm-6 col-md-6 TBNR">
															<!--<h5>Lista de calificaciones</h5>-->
															<h4><label for="">Colocar una <strong>'s'</strong> para: <strong>Sin Calificación</strong></label><br>
															<label for="">Colocar una <strong>'n'</strong> para: <strong>N/C</strong></label><br>
															<label for="">La actualización de calificaciones se asigna una por una.</label></h4>
														</div>

														<div class="col-lg-3 col-sm-3 col-md-3 TBNR">
															<!--<h5>Lista de calificaciones</h5>-->
																<label for="Calificacion_minima"><h4>Calificación Minima aprobatoria</h4></label>
																<input type="number" min="1" max="10" class="form-control" name="Calificacion_minima" id="Calificacion_minima" maxlength="2">
														</div>

														<div class="col-lg-3 col-sm-3 col-md-3 TBNR">
																<h4>Cambiar Calificación minima</h4>
																<button disabled = "true" type="button" class="btn btn-primary m-4" name="CambiarCalifiacionMinima" id="CambiarCalifiacionMinima">Aplicar cambio</button>
														</div>
												</div>
												<div class="table-responsive">
													<div class="col-lg-12 col-sm-12 col-md-12 TBNR ">	
														<table id="table-alumnos-calificacionesNoAcre" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
															<tr>
																<th>NOMBRE</th>
																<th>CALIFICACIÓN</th>
																<th>OPCIONES</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
														</table>
													</div>
												</div>
                                        </div> <!--Termina div-TAB de NO ACREDITADOS-->


										<div class="tab-pane fade" id="Reporte" role="tabpanel" aria-labelledby="Reporte-tab">
											<h2>Reporte por semestre</h2>
											<div class="col-12 row">
												<div class="col-sm-12 col-md-4 ">
													<label for="carrerasCalificacionesReporte">Selecciona una carrera</label>
													<select id="carrerasCalificacionesReporte" class="form-control">
														<option disabled selected>Seleccione una carrera</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-4">
													<label for="generacionesCalificacionesReporte">Selecciona una generación</label>
													<select id="generacionesCalificacionesReporte" class="form-control">
														<option disabled selected>Seleccione una generación</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-4">
													<label for="cicloCalificacionesReporte">Seleccione un ciclo</label>
													<select id="cicloCalificacionesReporte" class="form-control">
														<option disabled selected>Seleccione un ciclo</option>
													</select>
												</div>
											
												<div class="table-responsive">
													<div class="col-lg-12 col-sm-12 col-md-12 TBNR ">	
														<table id="table-alumnos-calificacionesReporte" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
															<tr>
																<th>ALUMNOS</th>
																<th>MATRICULA</th>
																<th>CALIFICACIONES</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
														</table>
													</div>
												</div>
											</div>
                                        </div> <!--Termina tab de Reporte-->

										<div class="tab-pane fade" id="Boletas" role="tabpanel" aria-labelledby="Boletas-tab">
											<h2>Boletas</h2>
											<div class="col-12 row">
												<div class="col-sm-12 col-md-4">
													<label for="carrerasCalificacionesBoletas">Selecciona una carrera</label>
													<select id="carrerasCalificacionesBoletas" class="form-control">
														<option disabled selected>Seleccione una carrera</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-4">
													<label for="generacionesCalificacionesBoletas">Selecciona una generación</label>
													<select id="generacionesCalificacionesBoletas" class="form-control">
														<option disabled selected>Seleccione una generación</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-4">
													<label for="cicloCalificacionesBoletas">Seleccione un ciclo</label>
													<select id="cicloCalificacionesBoletas" class="form-control">
														<option disabled selected>Seleccione un ciclo</option>
													</select>
												</div>
											
												<div class="table-responsive">
													<div class="col-lg-12 col-sm-12 col-md-12 TBNR ">	
														<table id="table-alumnos-calificacionesBoletas" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
															<tr>
																<th>ALUMNOS</th>
																<th>MATRICULA</th>
																<th>IMPRIMIR CALIFICACIONES</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
														</table>
													</div>
												</div>
											</div>
                                        </div> <!--Termina tab de Boletas-->

										<div class="tab-pane fade" id="Kardex" role="tabpanel" aria-labelledby="Kardex-tab">
											<h2>Kardex</h2>
											<div class="col-12 row">
												<div class="col-sm-12 col-md-6">
													<label for="carrerasCalificacionesKardex">Selecciona una carrera</label>
													<select id="carrerasCalificacionesKardex" class="form-control">
														<option disabled selected>Seleccione una carrera</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-6">
													<label for="generacionesCalificacionesKardex">Selecciona una generación</label>
													<select id="generacionesCalificacionesKardex" class="form-control">
														<option disabled selected>Seleccione una generación</option>
													</select>
												</div>
											
												<div class="table-responsive">
													<div class="col-lg-12 col-sm-12 col-md-12 TBNR ">	
														<table id="table-alumnos-calificacionesKardex" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
															<tr>
																<th>ALUMNOS</th>
																<th>MATRICULA</th>
																<th>PROMEDIO GENERAL</th>
																<th>OPCIONES</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
														</table>
													</div>
												</div>
											</div>
                                        </div> <!--Termina tab de Kardex-->

										<div class="tab-pane fade" id="Certificaciones" role="tabpanel" aria-labelledby="Certificaciones-tab">
											<div class="row">
												<div class="col-md-6">
													<h2>Seleccionar Alumnos para titular</h2>
												</div>
												<div class="col-md-6 text-right">
												<button id="Titulados" type="button" class="btn btn-primary waves-effect waves-light" disabled>
													Guardar Cambios
												</button>
											</div>
											</div>
											
											<div class="col-12 row">
												<div class="col-sm-12 col-md-6">
													<label for="carrerasCalificacionesTitulados">Selecciona una carrera</label>
													<select id="carrerasCalificacionesTitulados" class="form-control">
														<option disabled selected>Seleccione una carrera</option>
													</select>
												</div>
							
												<div class="col-sm-12 col-md-6">
													<label for="generacionesCalificacionesTitulados">Selecciona una generación</label>
													<select id="generacionesCalificacionesTitulados" class="form-control">
														<option disabled selected>Seleccione una generación</option>
													</select>
												</div>
											</div>
											<br>

											<div class="table-responsive">
												<div class="col-lg-12 col-sm-12 col-md-12 TBNR ">	
													<table id="table-alumnos-titulados" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
													<thead>
														<tr>
															<th>ALUMNOS</th>
															<th>MATRICULA</th>
															<th>SELECCIONAR</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
													</table>
												</div>
											</div>
											<!--<div class="col-12 row">
												<div class="col-sm-12 col-md-6">
													<label for="FechaInicioCertificaciones">Fecha de Inicio de estudios</label><br>
													<input type="date" class = "form-control" name="FechaInicioCertificaciones" id ="FechaInicioCertificaciones" required>
												</div>

												<div class="col-sm-12 col-md-6">
													<label for="FechaConcluCertificaciones">Fecha de Conclusión de estudios</label><br>
													<input type="date" class = "form-control" name="FechaConcluCertificaciones" id ="FechaConcluCertificaciones" required>
												</div>
											</div>
											<br>
											<div class="col-12 row">
												<div class="col-sm-12 col-md-6">
													<label for="FechaExtraCertificaciones">Fecha de Extraordinario de ultimo ciclo</label><br>
													<input type="date" class = "form-control" name="FechaExtraCertificaciones" id ="FechaExtraCertificaciones" required>
												</div>

												<div class="col-sm-12 col-md-6">
													<label for="FechaEmisCertificaciones">Fecha de Emision del Certificado</label><br>
													<input type="date" class = "form-control" name="FechaEmisCertificaciones" id ="FechaEmisCertificaciones" required>
												</div>
											</div>-->
                                        </div> <!--Termina tab de Certificaciones-->
                                	</div>
								</div>
								<!--Añadir al ui acordeon-->
							</div>	
							<!--fin-calificaciones-->
			
			<!--fin-calificaciones-->
		</div>

		<div class="modal fade modal-right" id="modalCrearExamen">
								<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title m-0">Formulario - Crear Examen</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
									</div>
									<div class="modal-body">
									<form id="formularioCrearExamen" type="post">
										<div class="form-group">
											<label for="nombreExamen">Nombre del examen: </label>
											<input type="text" class="form-control" name="nombreExamen" id="nombreExamen" required>
										</div>

										<div class="form-group">
											<label for="examenCarrera">Selecciona la carrera: </label>
											<select class="form-control" name="examenCarrera" id="examenCarrera" required>
											</select>
										</div>

										<div class="form-group" id="divGeneracion" style="display: none;">
											<label for="examenGeneracion">Selecciona la generación: </label>
											<select class="form-control" name="examenGeneracion" id="examenGeneracion" required>
											</select>
										</div>

										<div class="form-group" id="divMaestros" style="display: none;">
											<label for="selectMaestros">Selecciona el maestro: </label>
											<select class="form-control" name="selectMaestros" id="selectMaestros" required>
											</select>
										</div>

										<div class="form-group" id="divMaterias" style="display: none;">
											<label for="cursoExamen">Materia a la que pertenecerá el examen: </label>
											<select class="form-control" name="cursoExamen" id="cursoExamen" required>
											</select>
										</div>

										<input type="hidden" name="nameMat" id="nameMat">
										<div class="form-group row">
											<div class="col-sm-12 col-md-6">
												<label>Fecha inicio del examen: </label>
												<input type="date" class="form-control" name="fechaInicioExamen" id="fechaInicioExamen" required>
												<input type="time" class="form-control" name="horaInicioExamen" id="horaInicioExamen" required>
											</div>
											<div class="col-sm-12 col-md-6">
												<label>Fecha fin del examen: </label>
												<input type="date" class="form-control" name="fechaFinExamen" id="fechaFinExamen" required>
												<input type="time" class="form-control" name="horaFinExamen" id="horaFinExamen" required>
											</div>
										</div>
										<div class="form-group row d-none" id="costs">
											<div class="col-sm-12 col-md-6">
												<label>Costo en pesos: </label>
												<input type="text" class="form-control" name="costoPesos" pattern="^[0-9]+" placeholder="ingresa la cantidad">
											</div>
											<div class="col-sm-12 col-md-6">
												<label>Costo en dolares: </label>
												<input type="text" class="form-control" name="costoUsd" pattern="^[0-9]+" placeholder="ingresa la cantidad">
											</div>
										</div>
											<div class="form-group row">
												<div class="col-sm-12 col-md-6">
													<div class="checkbox">
														<input id="check_extraordinario" type="checkbox" name="aplicar_extraordinario">
														<label for="check_extraordinario">
															Examen para Extraordinario
														</label>
													</div>

													<div class="checkbox">
														<input id="check_multiple_aplicacion_i" type="checkbox" name="aplicar_multiple">
														<label for="check_multiple_aplicacion_i">
															Aplicar hasta aprobar
														</label>
													</div>
												</div>
												<div class="col-sm-12 col-md-6">
													<div class="checkbox">
														Selecciona Carrera para habilitar esta opción: 
														<input id="check_retomar_preguntas" type="checkbox" name="retomar_preguntas">
														<label for="check_retomar_preguntas">
															Retomar preguntas de examen pasado
														</label>
													</div>
												</div>	
											</div>							
										</div>
										<div class="form-group row">
											<div class="col-sm-12 col-md-6">
												
												<div class="d-none">
													<label class="mt-2" for="inp_porcentaje_aprobar_i">Defina el porcentaje minimo para aprobar el examen</label>
													<input type="text" name="inp_porcentaje_aprobar_i" id="inp_porcentaje_aprobar_i" class="form-control inp_porcentaje_aprobar onlyNum" placeholder="1 - 100">
												</div>
											</div>

											<div class="col-sm-12 col-md-12">
												<div class="d-none">
													<label class="col-sm-12 col-md-12" for="id_examen_pasado">Selecciona el examen Pasado:</label>
													 <select class="form-control id_examen_pasado" name="id_examen_pasado" id="id_examen_pasado" required >
													 </select>

													 <label class="col-sm-12 col-md-12" for="num_preguntas_retomar">Preguntas a retomar:</label>
													 <input type="text" name="num_preguntas_retomar" id="num_preguntas_retomar" class="form-control num_preguntas_retomar onlyNum" placeholder="Numero de preguntas">
													

													<div class="form-group row pt-3 table-responsive" >
														<table id="datatable-tablaPreguntas2" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
																<thead>
																		<tr>
																			<th>Pregunta</th>
																			<th>Opciones</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
													</div>
												
												</div>
											</div>
										</div>

										<div class="form-group row">
										  <div class="col-md-12">
											<div class="text-right">
												<button type="submit" class="btn btn-primary waves-effect waves-light" id="crewEx">Crear</button>
												<button type="button" id="cerrarCrearExamen" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
											</div>
										  </div>
										</div>

										
									</form>
									</div><!--end-modal-body-->
								</div><!--end-content-modal-->
								</div><!--end modal centered-->
							</div>

							<div class="modal fade modal-right" id="modalCrearExamenBanco">
								<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title m-0">Formulario - Crear Examen Banco de Preguntas</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
									</div>
									<div class="modal-body">
									<form id="formularioCrearExamen_Banco" type="post">
										<div class="form-group">
											<label for="nombreExamenBanco">Nombre del examen: </label>
											<input type="text" class="form-control" name="nombreExamenBanco" id="nombreExamen" required>
										</div>

										<div class="form-group" id="divGeneracion">
											<label for="CarreraBanco">Selecciona la Carrera: </label>
											<select class="form-control" name="CarreraBanco" id="CarreraBanco" required>
											</select>
										</div>

										<div class="form-group">
											<label for="examenGeneracionBanco"><h4><strong>Selecciona la Generación</strong></h4></label>
											<select class="form-control" id="examenGeneracionBanco" name="examenGeneracionBanco">
											</select>
										</div>


										<div class="form-group" id="divMaestrosBanco">
											<label for="selectMaestrosBanco">Selecciona el maestro: </label>
											<select class="form-control" name="selectMaestrosBanco" id="selectMaestrosBanco" required>
											</select>
										</div>

										<div class="form-group" id="divMateriasBanco">
											<label for="cursoExamenBanco">Materia a la que pertenecerá el examen: </label>
											<select class="form-control" name="cursoExamenBanco" id="cursoExamenBanco" required>
											</select>
										</div>
										<input type="hidden" name="nameMatBanco" id="nameMatBanco">
										<div class="form-group row">
											<div class="col-sm-12 col-md-6">
												<label>Fecha inicio del examen: </label>
												<input type="date" class="form-control" name="fechaInicioExamenBanco" id="fechaInicioExamenBanco" required>
												<input type="time" class="form-control" name="horaInicioExamenBanco" id="horaInicioExamenBanco" required>
											</div>
											<div class="col-sm-12 col-md-6">
												<label>Fecha fin del examen: </label>
												<input type="date" class="form-control" name="fechaFinExamenBanco" id="fechaFinExamenBanco" required>
												<input type="time" class="form-control" name="horaFinExamenBanco" id="horaFinExamenBanco" required>
											</div>
										</div>
										<div class="form-group row d-none" id="costsb">
											<div class="col-sm-12 col-md-6">
												<label>Costo en pesos: </label>
												<input type="text" class="form-control" name="costoPesosBanco" pattern="^[0-9]+" placeholder="ingresa la cantidad">
											</div>
											<div class="col-sm-12 col-md-6">
												<label>Costo en dolares: </label>
												<input type="text" class="form-control" name="costoUsdBanco" pattern="^[0-9]+" placeholder="ingresa la cantidad">
											</div>
										</div>
										
										<div class="checkbox">
											<input id="check_extraordinarioBanco" type="checkbox" name="aplicar_extraordinarioBanco">
											<label for="check_extraordinarioBanco">
												Examen para Extraordinario
											</label>
										</div>
										</div>
										<div class="form-group row">
											<div class="col">
												<div class="checkbox">
													<input id="check_multiple_aplicacion_iBanco" type="checkbox" name="aplicar_multipleBanco">
													<label for="check_multiple_aplicacion_iBanco">
														Aplicar hasta aprobar
													</label>
												</div>
												
												<div class="d-none">
													<label class="mt-2" for="inp_porcentaje_aprobar_iBanco">Defina el porcentaje minimo para aprobar el examen</label>
													<input type="text" name="inp_porcentaje_aprobar_iBanco" id="inp_porcentaje_aprobar_iBanco" class="form-control inp_porcentaje_aprobar onlyNum" placeholder="1 - 100">
												</div>
											</div>

											<!--<div class="col">
												<div class="d-none">
													<label class="mt-2" for="id_examen_pasado">Selecciona el examen Pasado:</label>
													 <select class="form-control id_examen_pasado" name="id_examen_pasado" id="id_examen_pasado" required >
													 </select>

													 <label class="mt-2" for="num_preguntas_retomar">Preguntas a retomar:</label>
													 <input type="text" name="num_preguntas_retomar" id="num_preguntas_retomar" class="form-control num_preguntas_retomar onlyNum" placeholder="Numero de preguntas">
												</div>	
											</div>-->
										</div>

										<!--<div class="form-group row table-responsive" >
											<div class="d-none">
												<table id="datatable-tablaPreguntas2" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
																<tr>
																	<th>Pregunta</th>
																	<th>Opciones</th>
																</tr>
														</thead>
														<tbody>
														</tbody>
												</table>
											</div>
										</div>-->

										<div class="text-right">
											<button type="submit" class="btn btn-primary waves-effect waves-light" id="crewEx">Crear</button>
											<button type="button" id="cerrarCrearExamen" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
										</div>
									</form>
									</div><!--end-modal-body-->
								</div><!--end-content-modal-->
								</div><!--end modal centered-->
							</div>

							<div class="modal fade modal-right" id="modalEditarExamen">
								<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title m-0">Formulario - Editar Examen</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
									</div>
									<div class="modal-body">
									<form id="formularioEditarExamen" type="post">
										<div class="form-group">
											<label for="editarNombreExamen">Nombre del examen: </label>
											<input type="text" class="form-control" name="editarNombreExamen" id="editarNombreExamen" required>
										</div>

										<div class="form-group">
											<label for="editarExamenCarrera">Selecciona la carrera: </label>
											<select class="form-control" name="editarExamenCarrera" id="editarExamenCarrera" required>
											</select>
										</div>

										<div class="form-group" id="divEditarGeneracion">
											<label for="editarExamenGeneracion">Selecciona la generación: </label>
											<select class="form-control" name="editarExamenGeneracion" id="editarExamenGeneracion" required>
											</select>
										</div>

										<div class="form-group" id="divEditarMaestros">
											<label for="editarSelectMaestros">Selecciona el maestro: </label>
											<select class="form-control" name="editarSelectMaestros" id="editarSelectMaestros" required>
											</select>
										</div>

										<div class="form-group" id="divEditarMaterias">
											<label for="editarCursoExamen">Materia a la que pertenecerá el examen: </label>
											<select class="form-control" name="editarCursoExamen" id="editarCursoExamen" required>
											</select>
										</div>
										
										<div class="form-group">
											<div class="row">
												<div class="col-sm-12 col-md-6">
													<label>Fecha inicio del examen: </label>
													<input type="date" class="form-control" name="editarFechaInicioExamen" id="editarFechaInicioExamen" required>
													<input type="time" class="form-control" name="editarHoraInicioExamen" id="editarHoraInicioExamen" required>
												</div>
												<div class="col-sm-12 col-md-6">
													<label>Fecha fin del examen: </label>
													<input type="date" class="form-control" name="editarFechaFinExamen" id="editarFechaFinExamen" required>
													<input type="time" class="form-control" name="editarHoraFinExamen" id="editarHoraFinExamen" required>
												</div>
											</div>
										</div>
										<div class="form-group row d-none" id="costs">
											<div class="col-sm-12 col-md-6">
												<label>Costo en pesos: </label>
												<input type="text" class="form-control" name="costoPesos" pattern="^[0-9]+" placeholder="ingresa la cantidad">
											</div>
											<div class="col-sm-12 col-md-6">
												<label>Costo en dolares: </label>
												<input type="text" class="form-control" name="costoUsd" pattern="^[0-9]+" placeholder="ingresa la cantidad">
											</div>
										</div>
										<div class="form-group row">
											<div class="col">
												<div class="checkbox">
													<input id="check_multiple_aplicacion" type="checkbox" name="aplicar_multiple">
													<label for="check_multiple_aplicacion">
														Aplicar hasta aprobar
													</label>
												</div>
												<div class="d-none">
													<label class="mt-2" for="inp_porcentaje_aprobar">Defina el porcentaje minimo para aprobar el examen</label>
													<input type="text" name="inp_porcentaje_aprobar" id="inp_porcentaje_aprobar" class="form-control inp_porcentaje_aprobar onlyNum" placeholder="1 - 100">
												</div>
											</div>
											
											<div class="col">
												<div class="checkbox">
													<input id="check_retomar_preguntas_e" type="checkbox" name="retomar_preguntas_e">
													<label for="check_retomar_preguntas_e">
														Retomar preguntas de examen pasado
													</label>
												</div>
											</div>
										</div>
										
										<div class="form-group row d-none">
											<label class="col-md-12" for="id_examen_pasado_e">Selecciona el examen pasado:</label>
											<select class="form-control id_examen_pasado_e" name="id_examen_pasado_e" id="id_examen_pasado_e" >
											</select>

											<label class="col-md-12" for="num_preguntas_retomar_e">Preguntas a retomar:</label>
											<input type="text" name="num_preguntas_retomar_e" id="num_preguntas_retomar_e" class="form-control num_preguntas_retomar_e onlyNum" placeholder="Numero de preguntas" required>
											
											<div class="col-md-12 pt-3">
												<div class="table-responsive">
												
													<table id="datatable-tablaPreguntas3" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
															<thead>
																	<tr>
																		<th>Pregunta</th>
																		<th>Opciones</th>
																	</tr>
															</thead>
															<tbody>
															</tbody>
													</table>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12 form-group text-right">
												<input type="hidden" id="idExamen" name="idExamen">
												<button type="submit" class="btn btn-primary waves-effect waves-light">Editar</button>
												<button type="button" id="cerrarEditarExamen" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
											</div>
										</div>
										
									</form>
									</div><!--end-modal-body-->
								</div><!--end-content-modal-->
								</div><!--end modal centered-->
							</div>

							<!--MODAL VER EXPEDIENTE-->
							<!-- sample modal content -->
							<div id="modalverExpediente" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" >
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 id="divA" class="modal-title m-0" id="custom-width-modalLabel">Expediente</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    
													<form id="formverExpediente" oninput="javascript: document.getElementById('btnEdit').disabled=false;">

														<input type="hidden" id="idExp" name="idExp"></input>
                          
														<table id="datatable-tablaExpediente" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
															<thead>
																<tr>
																	<th>Documento</th>
																	<th>Estado</th>
																	<th>Fechas</th>
																	<th>Opciones</th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
                                                    
														<div class="modal-footer">
															<button id="btnEdit" type="submit" class="btn btn-primary waves-effect waves-light" disabled>Guardar cambios</button>
															<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
														</div>

													</form>
                                				</div><!-- /.modal-content -->
											</div>
							</div>
							<!--end-modal-->
							<!--FIN MODAL MOSTRAR EXPEDIENTE-->

							<!--MODAL MOSTRAR MAESTRO-->
							<!-- sample modal content -->
							<div id="modalMostrarMaestro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h3 class="modal-title m-0" id="custom-width-modalLabel">Editar Maestro</h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    
													<form id="formMostrarMaestro" oninput="javascript: document.getElementById('btnEditMaestroE').disabled=false;">
														<div class="modal-body">
															<b>Nombre(s):</b>
															<input id="nombres_em" name="nombres_em" class="form-control" type="text" required></input><br>
															<b>Apellido paterno:</b>
															<input id="aPaterno_em" name="aPaterno_em" class="form-control" type="text" required></input><br>
															<b>Apellido materno:</b>
															<input id="aMaterno_em" name="aMaterno_em" class="form-control" type="text" required></input><br>
															<b>Sexo:</b>
															<label><input id="sexoH_em" name="sexo_em" type="radio" value="H" checked></input> Hombre</label>
															<label><input id="sexoM_em" name="sexo_em" type="radio" value="M"></input> Mujer</label> 
															&nbsp;&nbsp;<label><input id="resetp" name="resetp" type="checkbox" value="1"></input> Resetear password (abc)</label><br>
															<b>Correo electrónico:</b>
															<input id="email_em" name="email_em" class="form-control" type="email" required></input><br>
															<b>Teléfono a 10 dígitos:</b>
															<input id="telefono_em" name="telefono_em" class="form-control" type="tel" maxlength="10" onkeypress="return check(event)" required></input><br>
															<input id="idMaestro" name="idMaestro" class="form-control" type="hidden" required></input>
															<button type="button" class="btn btn-dark waves-effect waves-light" onclick="listarCarrerasE();" style="width:100%;"><i class="fas fa-angle-down"></i> Reasignar carreras en las que el maestro dará clases</button>
															<div class="modal-body">
																<div id="lista_carrerasE" class="list-group"></div>
															</div>
															<input id="total_carrerasE" name="total_carrerasE" class="form-control" type="hidden" required value="0"></input>
															
														</div>
                                                    
														<div class="modal-footer">
															<button id="btnEditMaestroE" type="submit" class="btn btn-primary waves-effect waves-light" disabled>Editar</button>
															<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
														</div>

													</form>
                                				</div><!-- /.modal-content -->
											</div>
							</div>
							<!--end-modal-->
							<!--FIN MODAL MOSTRAR MAESTRO-->

							<!--MODAL AGREGAR MAESTRO-->
							<!-- sample modal content -->
							<div id="modalAgregarMaestro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h3 class="modal-title m-0" id="custom-width-modalLabel">Agregar Maestro</h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    
													<form id="formAgregarMaestro" oninput="javascript: document.getElementById('btnAddMaestro').disabled=false;">
														<div class="modal-body">
															<b>Nombre(s):</b>
															<input id="nombres_am" name="nombres_am" class="form-control" type="text" required></input><br>
															<b>Apellido paterno:</b>
															<input id="aPaterno_am" name="aPaterno_am" class="form-control" type="text" required></input><br>
															<b>Apellido materno:</b>
															<input id="aMaterno_am" name="aMaterno_am" class="form-control" type="text" required></input><br>
															<b>Sexo:</b>
															<label><input id="sexoH_am" name="sexo_am" type="radio" value="H" checked></input> Hombre</label>
															<label><input id="sexoM_am" name="sexo_am" type="radio" value="M"></input> Mujer</label><br> 
															<b>Correo electrónico:</b>
															<input id="email_am" name="email_am" class="form-control" type="email" required></input><br>
															<b>Teléfono a 10 dígitos:</b>
															<input id="telefono_am" name="telefono_am" class="form-control" type="tel" maxlength="10" onkeypress="return check(event)" required></input><br>
															<button type="button" class="btn btn-dark waves-effect waves-light" onclick="listarCarreras();" style="width:100%;"><i class="fas fa-angle-down"></i> Asignar carreras en las que el maestro dará clases</button>
															<div class="modal-body">
																<div id="lista_carreras" class="list-group"></div>
															</div>
															<input id="total_carreras" name="total_carreras" class="form-control" type="hidden" required></input>															
														</div>
                                                    
														<div class="modal-footer">
															<button id="btnAddMaestro" type="submit" class="btn btn-primary waves-effect waves-light" disabled>Agregar</button>
															<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
														</div>

													</form>
                                				</div><!-- /.modal-content -->
											</div>
							</div>
							<!--end-modal-->
							<!--FIN MODAL MOSTRAR MAESTRO-->

							<!--MODAL ASIGNACIÓN MATERIAS-->
							<!-- sample modal content -->
							<div id="modal_mostrarAsignacionMaterias" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h3 class="modal-title m-0" id="custom-width-modalLabel">Asignación de Materias</h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>

													<b><div id="divA2">-</div></b>
                                                    
													<form id="form_mostrarAsignacionMaterias">
														<div class="modal-body">
															<div id="s_carreras">-</div>
														</div>

														<div class="modal-body">
															<div id="op_materias" class="list-group">-</div>
														</div>
                                                    
														<div class="modal-footer">
															<button id="btn_mostrarAsignacionMaterias" type="submit" class="btn btn-primary waves-effect waves-light" disabled>Guardar</button>
															<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
														</div>

													</form>
                                				</div><!-- /.modal-content -->
											</div>
							</div>
							<!--end-modal-->
							<!--FIN MODAL ASIGNACIÓN MATERIAS-->

							<!--MODAL ASIGNAR CLASE-->
							<!-- sample modal content -->
							<div id="modalAsignarClase" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h3 id="nclase" class="modal-title m-0" id="custom-width-modalLabel">Asignar Clase</h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
													
													<form id="formAsignarClase" >
														<div class="modal-body">

														<!--SELECT PARA CARRERAS-->
														<div class="modal-body">
															<div id="carrerasAsignacion" class="list-group"></div>
														</div>

														<!--SELECT PARA GENERACIONES-->
														<div class="modal-body">
															<div id="carrerasGeneraciones" class="list-group"></div>
														</div>

														<!-- <div class="modal-body">
															<div id="generacionGrupo" class="list-group"></div>
														</div> -->

														<!--SELECT PARA CICLOS-->
														<div class="modal-body">
															<div id="ciclos" class="list-group"></div>
														</div>

														<!--SELECCIONAR MATERIAS-->
														<div class="modal-body">
															<div id="materias_asignacion" class="list-group"></div>
														</div>
                                                    
														<div class="modal-footer">
															<!--<input id="aumentoControlado" type="hidden" value="0"></input>
															<input id="aumentoControladoApoyo" type="hidden" value="0"></input>
															<input id="total_recursos" type="hidden" value="0"></input>
															<input id="total_apoyos" type="hidden" value="0"></input>-->
															<input id="total_materias_asignacion" name="total_materias_asignacion" type="hidden"></input>
															<input id="idMaestroAsignacion" name="idMaestroAsignacion" type="hidden"></input>
															<button id="btnAsignarClase" type="submit" class="btn btn-primary waves-effect waves-light" disabled>Asignar</button>
															<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
														</div>

													</form>
                                				</div><!-- /.modal-content -->
											</div>
							</div>
							<!--end-modal-->
							<!--FIN MODAL ASIGNAR CLASE-->					

						</div>

						<!--MODAL VER CLASES-->
							<!-- sample modal content -->
							<div id="modalVerClases" class="modal fade bs-example-modal-xl show" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h3 id="tclases" class="modal-title m-0" id="custom-width-modalLabel">Clases de @</h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>


													<div class="modal-body">
														
														<ul class="nav nav-tabs" role="tablist">
															<li class="nav-item d-none">
																<a class="nav-link active" id="clases-tab" data-toggle="tab" href="#clases" role="tab" aria-controls="clases" aria-selected="true">
																	<span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
																	<span class="d-none d-sm-block">Home</span>
																</a>
															</li>
															<li class="nav-item d-none">
																<a class="nav-link" id="editarclase-tab" data-toggle="tab" href="#editarclase" role="tab" aria-controls="profile" aria-selected="false">
																	<span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
																	<span class="d-none d-sm-block">Profile</span>
																</a>
															</li>
														</ul>
														<div class="tab-content bg-light">
															<div class="tab-pane fade active show" id="clases" role="tabpanel" aria-labelledby="clases-tab">
																<div class="table-responsive">
																	<table id="datatable-tablaClases" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
																		<thead>
																			<tr>
																				<th>Clase</th>
																				<th>Materia</th>
																				<th>Carrera</th>
																				<th>Fecha</th>
																				<th>Opciones</th>
																			</tr>
																		</thead>
																		<tbody>
																		</tbody>
																	</table>
																</div>
															</div>
															<div class="tab-pane fade" id="editarclase" role="tabpanel" aria-labelledby="editarclase-tab">
																<div class="row">
																	<div class="col-12">
																		<button class="btn btn-info btn-lg" onclick="$('#clases-tab').click()">
																			<i class="fa fa-arrow-left" data-toggle="tab" href="#clases"></i>
																			Regresar
																		</button>
																	</div>
																</div>
																<form id="form_actualizar_clase" class="mt-3">
																	<input type="hidden" name="inp_edit_clase" id="inp_edit_clase">
																	<div class="row mb-2">
																		<div class="col-sm-12 col-md-6">
																			<label for="">Nombre de la clase</label>
																			<input type="text" class="form-control" name="inp_edit_nombre" id="inp_edit_nombre" placeholder="Nombre de la clase" required>
																		</div>
																		<div class="col-sm-12 col-md-6">
																			<label for="">Fecha de la clase</label>
																			<input type="datetime-local" class="form-control" name="inp_edit_fecha" id="inp_edit_fecha" placeholder="Fecha de la clase" required>
																		</div>
																		<div class="col-12">
																			<label for="">Link a video de clase</label>
																			<input type="text" class="form-control" id="inp_edit_link" name="inp_edit_link" placeholder="Link a video de clase">
																		</div>
																		<div class="col-sm-12 col-md-6 my-2 d-none">
																			<label for="">Carrera</label>
																			<select id="select_carreras_edit" class="form-control"></select>
																		</div>
																		<div class="col-sm-12 col-md-6 my-2 d-none">
																			<label for="">Generación</label>
																			<select id="select_generacion_edit" name="select_generacion_edit" class="form-control"></select>
																		</div>
																		<div class="col-sm-12 col-md-6 my-2 d-none">
																			<label for="">Ciclo</label>
																			<select id="select_ciclo_edit" class="form-control"></select>
																		</div>
																		<div class="col-sm-12 col-md-6 my-2 d-none">
																			<label for="">Materias</label>
																			<select id="select_materias_edit" name="select_materias_edit" class="form-control"></select>
																		</div>
																	</div>
																	<!-- <div class="row mb-2">
																		<div class="col-12 mb-2">
																			<div class="border p-2">
																				<i class="fa fa-times float-right" id="empty-materiales"></i>
																				<label for=""><b>Lista de materiales de apoyo</b></label>
																				<ul id="list_materiales">
																				</ul>
																				<div id="inputs_materiales">
																				</div>
																				<button type="button" class="btn btn-dark d-none" id="btn_agregarMaterial" onclick="agregar_elemento('materiales')">
																					<i class="far fa-plus-square"></i>Agregar material
																				</button>
																			</div>
																		</div>
																		<div class="col-12 mb-2">
																			<div class="border p-2">
																				<i class="fa fa-times float-right" id="empty-recursos"></i>
																				<label for=""><b>Lista de recursos descargables</b></label>
																				<ul id="list_recursos">
																				</ul>
																				<div id="inputs_recursos">
																				</div>
																				<button type="button" class="btn btn-dark d-none" id="btn_agregarRecurso" onclick="agregar_elemento('recursos')">
																					<i class="far fa-plus-square"></i>Agregar recurso
																				</button>
																			</div>
																		</div>
																	</div> -->

																	<div class="row">
																		<div class="col">
																			<button type="submit" class="btn btn-success">Guardar cambios</button>
																		</div>
																	</div>
																</form>
															</div>
														</div>
														
													</div>


                                				</div><!-- /.modal-content -->
											</div>
							</div>
							<!--end-modal-->
							<!--FIN MODAL VER CLASES-->

							<!--MODAL ASIGNAR CLASE_e-->
							<!-- sample modal content -->
							<div id="modalAsignarClaseE" class="modal fade" tabindex="-10" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h3 id="nclaseE" class="modal-title m-0" id="custom-width-modalLabel">Editar Clase</h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
													
													<form id="formAsignarClaseE" >
														<div class="modal-body">

														<!--SELECT PARA CARRERAS-->
														<div class="modal-body">
															<div id="carrerasAsignacionE" class="list-group"></div>
														</div>

														<!--SELECT PARA GENERACIONES-->
														<div class="modal-body">
															<div id="carrerasGeneracionesE" class="list-group"></div>
														</div>

														<!--SELECT PARA CICLOS-->
														<div class="modal-body">
															<div id="ciclosE" class="list-group"></div>
														</div>

														<!--SELECCIONAR MATERIAS-->
														<div class="modal-body">
															<div id="materias_asignacionE" class="list-group"></div>
														</div>
                                                    
														<div class="modal-footer">
															<input id="total_recursosE" type="hidden" value="0"></input>
															<input id="total_apoyosE" type="hidden" value="0"></input>
															<input id="total_materias_asignacionE" name="total_materias_asignacion" type="hidden"></input>
															<input id="idMaestroAsignacionE" name="idMaestroAsignacion" type="hidden"></input>
															<button id="btnAsignarClaseE" type="submit" class="btn btn-primary waves-effect waves-light" disabled>Asignar</button>
															<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
														</div>

													</form>
                                				</div><!-- /.modal-content -->
											</div>
							</div>
							<!--end-modal-->
							<!--FIN MODAL ASIGNAR CLASE_e-->
					</div><!--end row-->
				</div><!--end card-body-->
			</div><!--end cart-->
			<!--
			<div class="modal fade" id="modalCambiarCarrera" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Diplomado</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<h4>Informacion:</h4>
									<input type="text" id="idAlumnoCambio">
								</div>
								
							</div>
							
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="button" id = "GuardarCambioCarrera" class="btn btn-primary">Guardar</button>
						</div>
					</div>
				</div>
			</div>-->





			<div class="modal fade" id="modalEditSession" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Editar Clase</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
							<div class="text-right">
								<form id="form-sessions">
								<div class="form-group">
									<label for="nameS">Nombre de la clase</label>
									<input type="text" class="form-control" id="nameS" name="nameS">
								</div>
								<div class="form-group">
									<label for="">Fecha y hora</label>
									<input type="datetime-local" class="form-control" id="dateS" name="dateS">
								</div>
								<div class="form-group">
								<label for="">Buscar maestro</label>
								<input class="form-control" id="searchInput"/>
								<select name="selectTeacher" id="selectTeacher" class="form-control hidden"></select>
								</div>
								<input  type="hidden" id="idSession" name="idSession"/>
								<input type="hidden" name="action" value="addteacher"/>
								<button type="subimit" name="" id="" class="btn btn-secondary waves-effect m-1-5">Actualizar</button>
								</form>
								
							</div>

						</div><!--end-modal-body-->
            		</div><!-- /.modal-content -->
				</div>
			</div>

			<div class="modal fade" id="modalAsignarProrrogaDocumento" role="dialog" style="overflow-y: scroll;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Asignar Prorroga a Alumno (Documentación)</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
						<h5><center><label>Lista De Documentación Faltante Por El Alumno.</label></center></h5>
							<div class="form-group" id="divAsigProrrogaDocumentos">
							</div>

						<!--
						<center><label>Documentación Física</label></center>
							<div class="form-group" id="divAsigProrrogaDocumentosFisicos">
							</div>-->
							
							<div class="text-right">
								<button type="button" name="ocultarAsignarProrroga" id="ocultarAsignarProrroga" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
							</div>

						</div><!--end-modal-body-->
            		</div><!-- /.modal-content -->
				</div>
			</div><!--end-modal-->

			<div class="modal fade" id="modalModFechasProrrogaDocumentoDigital">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Modificar Prorroga a Documento</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
							<form id="formModFechaProrrogaDigital" type="post">

								<div class="form-group">
									<label for="modificarFechaDigital">Fecha límite para entregar <strong>Documento Digital</strong></label>
									<input class="form-control" type="date" name="modificarFechaDigital" id="modificarFechaDigital" required>
								</div>

								<div class="form-group">
									<label for="modificarHoraDigital">Hora límite para entregar<strong> Documento Digital</strong></label>
									<input class="form-control" type="time" name="modificarHoraDigital" id="modificarHoraDigital" required>
								</div>
								
								<div class="text-right">
									<input type="hidden" name="idProrroga" id="idProrroga">
									<button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
									<button type="button" name="ocultarAsignarProrrogaModificar" id="ocultarAsignarProrrogaModificar" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
								</div>

							</form>
						</div><!--end-modal-body-->
            		</div><!-- /.modal-content -->
				</div>
			</div><!--end-modal-->

			<div class="modal fade" id="modalFechasProrrogaDocumentoDigital">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Asignar Prorroga a Documento</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
							<form id="formFechaProrrogaDigital" type="post">

								<div class="form-group">
									<label for="fechaDigital">Fecha límite para entregar <strong>Documento Digital</strong></label>
									<input class="form-control" type="date" name="fechaDigital" id="fechaDigital" required>
								</div>

								<div class="form-group">
									<label for="horaDigital">Hora límite para entregar<strong> Documento Digital</strong></label>
									<input class="form-control" type="time" name="horaDigital" id="horaDigital" required>
								</div>
								
								<div class="text-right">
									<input type="hidden" name="idDocumento" id="idDocumento">
									<input type="hidden" name="idAlumno" id="idAlumno">
									<input type="hidden" name="idGeneracion" id="idGeneracion">
									<button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Asignar</button>
									<button type="button" name="ocultarAsignarProrrogaAsignar" id="ocultarAsignarProrrogaAsignar" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
								</div>

							</form>
						</div><!--end-modal-body-->
            		</div><!-- /.modal-content -->
				</div>
			</div><!--end-modal-->

			<div class="modal fade" id="modalFechasProrrogaDocumentoFisico">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Asignar Prorroga a Documento</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
							<form id="formFechaProrrogaFisico" type="post">

								<div class="form-group">
									<label for="fechaFisico">Fecha límite para entregar <strong>Documento Fisico</strong></label>
									<input class="form-control" type="date" name="fechaFisico" id="fechaFisico" required>
								</div>

								<div class="form-group">
									<label for="horaFisico">Hora límite para entregar<strong> Documento Fisico</strong></label>
									<input class="form-control" type="time" name="horaFisico" id="horaFisico" required>
								</div>
								
								<div class="text-right">
									<input type="hidden" name="idDocumentoFisico" id="idDocumentoFisico">
									<input type="hidden" name="idAlumnoFisico" id="idAlumnoFisico">
									<input type="hidden" name="idGeneracionFisico" id="idGeneracionFisico">
									<button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Asignar</button>
									<button type="button" name="ocultarAsignarProrrogaFisico" id="ocultarAsignarProrrogaFisico" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
								</div>

							</form>
						</div><!--end-modal-body-->
            		</div><!-- /.modal-content -->
				</div>
			</div><!--end-modal-->

			<div class="modal fade" id="modalModFechasProrrogaDocumentoFisico">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Modificar Prorroga a Documento</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
							<form id="formModFechaProrrogaFisico" type="post">

								<div class="form-group">
									<label for="modificarFechaFisico">Fecha límite para entregar <strong>Documento Fisico</strong></label>
									<input class="form-control" type="date" name="modificarFechaFisico" id="modificarFechaFisico" required>
								</div>

								<div class="form-group">
									<label for="modificarHoraFisico">Hora límite para entregar<strong> Documento Fisico</strong></label>
									<input class="form-control" type="time" name="modificarHoraFisico" id="modificarHoraFisico" required>
								</div>
								
								<div class="text-right">
									<input type="hidden" name="idProrrogaFisico" id="idProrrogaFisico">
									<button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
									<button type="button" name="ocultarAsignarProrrogaModificarFisico" id="ocultarAsignarProrrogaModificarFisico" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
								</div>

							</form>
						</div><!--end-modal-body-->
            		</div><!-- /.modal-content -->
				</div>
			</div>
			<!--end-modal-->
			<div class="modal fade" id="modalRegistrarDocumentosFisicos">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Registrar Documentación Física</h4>
							<h4 class="modal-title m-0" id="textoNacionalidad"></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
							<form id="formRegistrarDocumentosFisicos" type="post">
								<div class="row">
									<div class="col-sm-12 col-md-6 mb-3">
										<label for="fechaDocumentoFisico">Fecha para nuevo registro</label>
										<input id="fechaDocumentoFisico" type="date" class="form-control" required>
									</div>
									<div class="col-sm-12 col-md-6 mb-3">
										<label for="nombreAdmin">Usuario:</label>
										<!-- <input id="nombreAdmin" type="text" class="form-control"> -->
										<select class="form-control" id="nombreAdmin" required>
                          				</select>
									</div>
								</div>

								<div class="form-group" id="divDocumentosFisico">
								</div>
								
								<div class="text-right">
									<input type="hidden" name="idAlumnoDocumentacionFisica" id="idAlumnoDocumentacionFisica">
									<button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Guardar</button>
									<button type="button" name="ocultarDocumentacionFisica" id="ocultarDocumentacionFisica" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
								</div>

							</form>
						</div><!--end-modal-body-->
            		</div><!-- /.modal-content -->
				</div>
			</div><!--end-modal-->
			<!-- modal ver respuestas examen -->
			<div class="modal fade bs-example-modal-lg" id="modalEntregasExamen" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-xl">
					<div class="modal-content col-sm-12 col-lg-12">
						<div class="modal-header">
						<h4 class="modal-title m-0" id="myLargeModalLabel">Entregas de examen:</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						</div>
						<div class="modal-body">
						<div class="TBNR table-responsive">
							<table id="tbl_examenes_entregados" class="table table-striped table-bordered nowrap w-100">
							<thead>
								<th>Alumno</th>
								<th>Fecha</th>
								<th>Calificación</th>
								<th>Detalle</th>
							</thead>
							</table>
						</div>
						</div>
					</div>
				</div>
			</div>
			<!-- fin modal ver respuestas examen -->

			<div class="modal fade modal-right" id="modalReporteSemestre">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title m-0">Reporte por semestre</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						</div>
						<div class="modal-body">
							<div id="info"></div>
							<!--Añadir table con la informacion ede los datos del alumno-->
							<div class="TBNR table-responsive">
							<table id= "TableCalReporteSemestre" class="table table-striped table-bordered nowrap w-100">
								<thead>
									<th>Materia</th>
									<th>Calificación</th>
								</thead>
							</table>
						</div>
						</div><!--end-modal-body-->
					</div><!--end-content-modal-->
				</div><!--end modal centered-->
			</div>

			<!-- Modal modificar carreras -->
			<div class="modal fade" id="modalTablaCalificaciones" role="dialog" style="overflow-y: scroll;">
				<div class="modal-dialog modal-xl">
						<div class="modal-content">
							<div class="modal-header">
									<h4 class="modal-title m-0" id="labelAlumnosCalificacion">Lista de Calificaciones</h4>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<div class="col-lg-12 col-sm-12 col-md-12 TBNR">
									<!--<h5>Lista de calificaciones</h5>-->
									<h4><label for="">Colocar una <strong>'s'</strong> para: <strong>Sin Calificación</strong></label><br>
									<label for="">Colocar una <strong>'n'</strong> para: <strong>N/C</strong></label><br>
									<label for="">La actualización de calificaciones se asigna una por una o de forma grupal con el botón Guardar Calificaciones.</label></h4>
									<table id="table-alumnos-calificaciones" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
									<thead>
										<tr>
										<th>NOMBRE</th>
										<th>CALIFICACIÓN</th>
										<th>OPCIONES</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									</table>
									</div>
								</div>
								<div class="text-right">
									<br>
									<input type="hidden" id="calificacionGen">
									<input type="hidden" id="calificacionMat">
									<button type="button" name="Guardar_Calificaciones" id="Guardar_Calificaciones" class="btn btn-primary waves-effect m-1-5">Guardar Calificaciones</button>
									<button type="button" name="ocultarCalificaciones" id="ocultarCalificaciones" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
								</div>
							</div><!--end-modal-body-->
						</div><!--end-content-modal-->
				</div><!--end modal centered-->
			</div>
			<div class="modal fade" id="ModalDocumentacionAlumnoExtranjero" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<input type="text" name="TipoAlumno" id="TipoAlumno" class="d-none" value ="">
				<input type="text" name="IdentificadorNacionalidad" id="IdentificadorNacionalidad" class="d-none" value ="<?php echo $NacionalidadAlumno;?>">
				<input type="text" name="IdentificadorAlumno" id="IdentificadorAlumno" class="d-none">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Documentación Alumno </h3>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>

						<div class="modal-body">
							<div  id = "DocumentacionAlumnoExtranjero" class="card p-3 mt-1 mb-3 text-justify">
								<div class="clave alert alert-warning">
									<strong>
										Por favor, presta atención a las indicaciones que se encuentran. <br> 
										Cada archivo no debe sobrepasar los 5MB y al guardar el documento debe ser uno por uno al dar clic en el botón "Enviar". <br>
										Formato de imágenes:<br>
										* .jpg <br>
										* .jpeg <br>
										* .png <br>
										Formato de documentos: <br>
										* .pdf <br>
										* .jpg <br>
										* .jpeg <br>
										* .png <br>
									</strong>
								</div>

								<form id="formDocumentosExtranjeros">
									<div class="card p-3 mt-1 mb-3 text-justify">
										<div id = "Formulario_dinamico"></div>
									</div>
                        		</form>

								<div class="clave alert alert-info MEXICANO">
                            Deberá presentar la documentación en el orden arriba mencionado, con sus 3 fotocopias cada uno, en un sobre de papel manila color amarillo. <br>
                            Todas las fotografías deberán traer escrito su nombre en la parte trasera con lapicero sin remarcar fuertemente para que no se traspase por el frente ya que si quedan marcadas no sirven.
                            <br><strong>DEBEN</strong> ser tomadas completamente de frente, con el rostro serio, la frente y las orejas completamente descubiertas.
                            <br><br><strong>HOMBRES</strong>
                            <li>Vestimenta formal, saco, camisa y corbata lisos, sin estampados.</li>
                            <li>Bigote recortado por arriba del labio superior.</li>
                            <li>Sin barba, lentes ni pupilentes de ningún color.</li>
                            <br><strong>MUJERES</strong>
                            <li>Vestimenta formal: saco sin estampados, blusa de cuello blanco y sin escote.</li>
                            <li>Cabello recogido hacia atrás.</li>
                            <li>Sin adornos</li>
                            <li>Sin lentes ni pupilentes de ningún color</li>
                            <li>Maquillaje discreto</li>
                            <br>Teléfono Control Escolar: <strong>228-833-45-81 y 228-833-40-31 </strong>
                            <br>Email: <strong>controlescolar@universidaddelconde.edu.mx y udcxal@gmail.com</strong>
                            <br>
                        </div>

                        <div class="clave alert alert-info EXTRANJERO">
                            <br>
                                <b>
                                    Las fotografías le recomendamos tomárselas ya estando en México, para que de ese modo cumplan con el total de especificaciones solicitadas.
                                </b>
                            <br></br>
                            Deberá presentar la documentación en el orden arriba mencionado, con sus 3 fotocopias cada uno, en un sobre de papel manila color amarillo. <br>
                            Todas las fotografías deberán traer escrito su nombre en la parte trasera con lapicero sin remarcar fuertemente para que no se traspase por el frente ya que si quedan marcadas no sirven.
                            <br><strong>DEBEN</strong> ser tomadas completamente de frente, con el rostro serio, la frente y las orejas completamente descubiertas.
                            <br><br><strong>HOMBRES</strong>
                            <li>Vestimenta formal, saco, camisa y corbata lisos, sin estampados.</li>
                            <li>Bigote recortado por arriba del labio superior.</li>
                            <li>Sin barba, lentes ni pupilentes de ningún color.</li>
                            <br><strong>MUJERES</strong>
                            <li>Vestimenta formal: saco sin estampados, blusa de cuello blanco y sin escote.</li>
                            <li>Cabello recogido hacia atrás.</li>
                            <li>Sin adornos</li>
                            <li>Sin lentes ni pupilentes de ningún color</li>
                            <li>Maquillaje discreto</li>
                            <br>Teléfono Control Escolar: <strong><?php  echo (!$HIDE_OPTIONS ? '228-833-45-81 y 228-833-40-31' : '228-8120-697'); ?> </strong>
                            <br>Email: <strong><?php  echo (!$HIDE_OPTIONS ? 'controlescolar@universidaddelconde.edu.mx y udcxal@gmail.com' : 'medicinaestetica@universidaddelconde.edu.mx y medicinaesteticaudc@gmail.com'); ?></strong>
                            <br>
                        </div>          
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div><!--end-modal--><!--end-modal-->
			<!--end-modal modificar carrera-->
		</div>
	</div>
	<!-- Modal modificar plan estudio -->
	<?php include '../partials/modalEditSudents.php'?>
			<!-- Button trigger modal -->
			
			<!-- Button trigger modal -->
			
			<!-- Modal -->
			<div class="modal fade" id="EditarFechaFirmaExpedicion" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Editar Fecha</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>

						<form id="formEditarFechaExpedicion">
							<input type="number" class ="d-none" name = "idFechaExpediente" id = "idFechaExpediente">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12 form-group">
										<label for="EditarFechaExpediente">Nueva Fecha de Expediente</label>
										<input class = "form-control" type="date" name="EditarFechaExpediente" id="EditarFechaExpediente" required>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<table id="tablaEditarFirmantes"  class="table table-striped table-bordered nowrap" style="width: 100%;">
											<thead>
												<th>#</th>
												<th>Firmantes</th>
												<th>Opciones</th>
											</thead>
											<tbody>
												<td>1</td>
												<td>
													<div class = "row align-items-center">
														<div class = col-md-6>
															<b>CONDE PÉREZ MARCO ANTONIO</b>
														</div>
														<div class = col-md-6>
															<input class = "form-group" name = "FirmanteSeleccionadoEdit" id = "FirmanteSeleccionadoEdit" type="checkbox">
														</div>
													</div>
												</td>
												<td>
													<div class = "row align-items-center">
														<div class= "col-md-12">
															<select class ="form-control form-group" name="TipoFirmanteEdit" id="TipoFirmanteEdit" required>
																<option disabled>Seleccione una Opción</option>
																<option value="1">DIRECTOR</option>
																<option value="2">SUBDIRECTOR</option>
																<option value="3">RECTOR</option>
																<option value="4">VICERRECTOR</option>
																<option value="5">RESPONSABLE DE EXPEDICIÓN</option>
															</select>
														</div>
													</div>
												</td>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class=" form-group modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="submit" class="btn btn-primary">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="EditarProceso" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
							<div class="modal-header">
									<h5 class="modal-title">Editar Proceso</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
								</div>
						<form id ="formularioEditarProceso">
							<div class="modal-body">
								<div class="container-fluid">
									<div class="form-group row">
									<input class ="form-control d-none" type="number" name="idProcesoEditar" id="idProcesoEditar" required>
										<div class="col-md-6">
											<label for="EditarNombreProceso">Nombre:</label>
											<input class ="form-control" type="text" name="EditarNombreProceso" id="EditarNombreProceso" required>
										</div>
										<div class="col-md-6">
											<label for="EditarOrdenProceso">Orden:</label>
											<input class ="form-control" type="number" name="EditarOrdenProceso" id="EditarOrdenProceso" onlyNum required>
										</div>
									</div>								
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Guardar</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="modal fade" id="EditarFormato" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
					<div class="modal-content">
							<div class="modal-header">
									<h5 class="modal-title">Editar Formato</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
								</div>
								<!--Trabajando-->
						<form id ="formularioEditarFormato">
							<div class="modal-body">
								<div class="container-fluid">
									<div class="row form-group">
									<input class ="form-control d-none" type="number" id ="idFormatoEditar" name ="idFormatoEditar" onlyNum>
										<div class="col-md-6">
											<label for="nombreformatoEditar">Nombre:</label>
											<input class ="form-control" type="text" id ="nombreformatoEditar" name ="nombreformatoEditar" placeholder ="Escriba el nombre del nuevo formato" required>
										</div>

										<div class="col-md-6">
											<label for="vecesenvioEditar">Veces que debe enviarse</label>
											<select class ="form-control" name="vecesenvioEditar" id="vecesenvioEditar" required>
												<option value="0" selected disabled>Seleccione una opción</option>
												<option value="1">1</option>
												<option value="6">6</option>
											</select>
										</div>
									</div>

									<div class="row form-group">
									<div class="col-md-12">
											<label for="archivoformatoEditar">Archivo del Formato:</label>
											<input class ="form-control" type="file" name="archivoformatoEditar" id="archivoformatoEditar">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Guardar</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Modal -->
			
			<!-- Modal -->
			<div class="modal fade" id="FormatosRevisionAlumno" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl"  role="document">
					<div class="modal-content">
							<div class="modal-header">
									<h3 class="modal-title">DOCUMENTOS DE: <label id = "NombreDocumentos"></label></h3> 
										<button type="button" class="close fromt" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
								</div>
						<div class="modal-body">
							<div class ="row">
								<div class ="table-responsive">
									<div class="col-md-12">
										<div class="container-fluid">
											<div class="card">									  
												<div class="card-body">
												  	<table id = "tabla-revision-formatos" class="table table-striped table-bordered nowrap" style="width: 100%;">
													  <thead>
															<tr>
																<th>#</th>
																<th>Proceso</th>
																<th>Formato</th>
																<th>Numero de envio</th>
																<th>Estatus</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
												  	</table>
											  	</div>
										  	</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="ModalObservacionesDoc">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
							<div class="modal-header">
									<h5 class="modal-title">Observaciones</h5>
										<button type="button" class="close cerrar" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
								</div>
						<div class="modal-body">
							<div class="container-fluid">
								<form id="Comentario-document-alu">
									<div class="table-responsive">
										<div class="row">
											<div class="col-md-12">
												<table id="tablaComentariosArchivo"  class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
													<thead>
														<th>Comentarios</th>
														<th>Fecha</th>
													</thead>
													<tbody>
													</tbody>
												</table>
									<div class="form-group">
										<input class = "form-control d-none" type="number" name="idArchivo" id="idArchivo" required>
										<label for="ComentarioArchivo">Nuevo comentario</label>
										<input class = "form-group form-control" type="text"  name = "ComentarioArchivo" id= "ComentarioArchivo" required>
										<button type="submit" class="form-group btn btn-primary">Comentar</button>
									</div>
											</div>
										</div>
									</div>

								</form>
							</div>
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary cerrar" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

		
			<div class="modal fade" id="HistorialCredenciales" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Solicitudes del Alumno</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
							<div class="container-fluid">
								<div class="table-responsive">
									<div class="row">
										<div class="col-md-12">
											<table id="tablaHistorialCredenciales"  class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
												<thead>
													<th>Información</th>
													<th>Solicitudes</th>
													<th>Acciones</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

		
			<!-- Modal -->
			<div class="modal fade" id="modalNuevaFechaExpedicion" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Nueva Fecha</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<form id="formNuevaFechaExpedicion">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12 form-group">
										<label for="NuevaFechaExpediente">Nueva Fecha de Expediente</label>
										<input class = "form-control" type="date" name="NuevaFechaExpediente" id="NuevaFechaExpediente" required>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<table id="tablaFirmantes"  class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
											<thead>
												<th>#</th>
												<th>Firmantes</th>
												<th>Opciones</th>
											</thead>
											<tbody>
												<td>1</td>
												<td>
													<div class = "row align-items-center">
														<div class = col-md-6>
															<b>CONDE PÉREZ MARCO ANTONIO</b>
														</div>
														<div class = col-md-6>
															<input  class = "form-group" name = "FirmanteSeleccionado" id = "FirmanteSeleccionado" type="checkbox" required>
														</div>
													</div>
												</td>
												<td>
													<div class = "row align-items-center">
														<div class= "col-md-12">
															<select class ="form-control form-group" name="TipoFirmante" id="TipoFirmante" required>
																<option disabled selected>Seleccione una Opción</option>
																<option value="1">DIRECTOR</option>
																<option value="2">SUBDIRECTOR</option>
																<option value="3">RECTOR</option>
																<option value="4">VICERRECTOR</option>
																<option value="5">RESPONSABLE DE EXPEDICIÓN</option>
															</select>
														</div>
													</div>
												</td>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class=" form-group modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="submit" class="btn btn-primary">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			
			</div>
			<!--end-modal modificar carrera-->
	<!-- end wrapper -->

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

	<script type="text/javascript" src="../assets/js/educate/edit-text.js"></script>
	<script src="../assets/js/template/sweetalert.min.js"></script>
	<script src="../assets/js/controlescolar/controlescolar.js"></script>
	
	<script src="../assets/js/certificaciones/certificaciones.js"></script>

	<script src="../assets/js/controlescolar/directorio.js"></script>
	<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
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
