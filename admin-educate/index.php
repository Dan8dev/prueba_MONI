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
									<input class = "d-none" type="text" name="idUsuarioBand" id="idUsuarioBand" value= "<?php echo $_SESSION["usuario"]['estatus_acceso'];?>">
                    <?php echo $usuario["persona"]["nombres"]; ?> <span class="mdi mdi-chevron-down font-15"></span>
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
							<?php if($_SESSION["usuario"]['estatus_acceso'] == 2){?>
								<?php $active = 'active'; 
								$activeNoAccess = '';
							 }else{ 
								$active = ''; 
								$activeNoAccess = 'active'; 	?>
								<?php } ?>

								<li class="nav-item">
									<a class="nav-link <?=$activeNoAccess?>" id="blogs-tab" data-toggle="tab" href="#blogs" role="tab" aria-controls="blogs" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Ingresar Contenido</span>
									</a>
								</li>
							</ul>

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
													<?php if($_SESSION["usuario"]['estatus_acceso'] != 2){?>
													<th>Dirección</th>
													<?php }?>
													<th>Carrera</th>
													<th>Generación</th>
													<th>Matrícula</th>
													<?php if($_SESSION["usuario"]['estatus_acceso'] != 2){?>
													<th>Contraseña</th>
													<th></th>
													<?php }?>
												</thead>
												<tbody>
												</tbody>
											</table>
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
												<h3>Procesos</h3>
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

												<table id = "tabla-procesos-nuevo" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
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

											<div class="tab-pane fade" id="formatos" role="tabpanel" aria-labelledby="formatos-tab">
												<h3>Formatos</h3>
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
												<table id = "tabla-documentos-proceso" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
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
											 <!--Termina div-TAB de NO ACREDITADOS-->

											<div class="tab-pane fade" id="AsignarAlumnos" role="tabpanel" aria-labelledby="AsignarAlumnos-tab">
												<h3>Asignar Alumnos</h3>
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

												<table id="alumnos-select-servicio" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
													<thead>
													<tr>
														<th>Alumno</th>
														<th>Articulo</th>
														<th>Matrícula</th>
														<th>Seleccionar</th>
													</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div> <!--Termina tab de Reporte-->

											<div class="tab-pane fade" id="Revisiones" role="tabpanel" aria-labelledby="Revisiones-tab">
												<h3>Revisiones</h3>
												<label for="revisiones-select-servicio">Archivos entregados por alumno</label>
												<table id="revisiones-select-servicio" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
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
											</div> <!--Termina tab de Boletas-->

											<div class="tab-pane fade" id="Correcciones" role="tabpanel" aria-labelledby="Correcciones-tab">
												<h3>Correcciones</h3>
												<label for="correcciones-select-servicio">Archivos corregidos por alumno</label>
												<table id="correcciones-select-servicio" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
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

											</div> <!--Termina tab de Kardex-->

											<div class="tab-pane fade" id="DocumentosRec" role="tabpanel" aria-labelledby="DocumentosRec-tab">
												<h2>Documentos recibidos</h2>
												<h3>Correcciones</h3>
												<label for="Solicitar-select-servicio">Archivos corregidos por alumno</label>
												<table id="Solicitar-select-servicio" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
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
											
											<div class="tab-pane fade" id="Reportes" role="tabpanel" aria-labelledby="Reportes-tab">
												<h2> Reportes</h2>
											</div>
											
										</div>
									</div>
								<!--Añadir al ui acordeon-->
								</div>


								<div class="tab-pane fade" id="alum" role="tabpanel" aria-labelledby="alum-tab">
									<div class="container-liquid">
									<h4>Solicitudes de Credenciales</h4>
										<div class="table-responsive">
											<table id="datatable-tablaCredencialesAlumno" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
												<thead>
													<tr>
														<th>Foto</th>
														<th>Alumno</th>
														<th>Ultima Solicitud</th>
														<th>Estatus de Pago</th>
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

								<div class="tab-pane fade" id="asistencias" role="tabpanel" aria-labelledby="asistencias-tab">
									<div class="table-responsive text-left">											
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
									</div>
		
									<div class="table-responsive text-left">												
										<h2>Asistencia a Clases</h2>
										<div class="form-group">
											<label for="selectBuscarAsistencias"><h4><strong>Selecciona la carrera</strong></h4></label>
											<select class="form-control" id="selectBuscarAsistencias" name="selectBuscarAsistencias">
											</select>
										</div>
										<div class="form-group">
											<label for="selectBuscarGeneraciones"><h4><strong>Selecciona la Generación</strong></h4></label>
											<select class="form-control" id="selectBuscarGeneraciones" name="selectBuscarGeneraciones">
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
<<<<<<<< HEAD:admin-educate/index.php
							<div class="tab-pane fade <?=$activeNoAccess?> show" id="blogs" role="tabpanel" aria-labelledby="blogs-tab">
									<h2>Contenido</h2>
									<div class="container col-sm-12 col-lg-12 col-md-12">
										<div class="card" id="cardcontent">
											<div class="card-body">
												<div class="text-left">
													<h2>Carreras</h2>									
												</div>
												<div class="form-group divSearch">
													<i class="fa fa-search"></i>
													<input type="search" placeholder="Buscar carreras" id="searchC" class="form-control">
												</div>
												<div id="carr"></div>
												<!-- <form id="formBlogs">
													<div class="form-group">
														<select id="carr" class="form-control"></select>
													</div>
													<div class="form-group">
														<select id="gn" class="form-control d-none"></select>
													</div>
													<div class="form-group">
														<select id="cic" class="form-control d-none"></select>
													</div>
													<div class="form-group">
														<select id="mt" class="form-control d-none"></select>
													</div>
													<div class="form-group">
														<select id="class" class="form-control d-none"></select>
													</div>
													<div class="form-group">
														<span class="span-note" style="color:red"></span>
													</div>
													<div class="form-group">
														<div id="summernote"><h2>Redacta tu contenido</h2></div>
													</div>
													<div class="form-group">
														<button class="btn btn-danger btn-vine" id="saveBlogs" disabled>Guardar</button>
													</div>
												</form>	 -->
											</div>
										</div>
										<div class="card d-none" id="cardsummer">
											<div class="card-body">
												<a href="#" id="return"><i class="fa fa-angle-left"> Regresar</i></a>
												<form id="formBlogs">
													<div class="form-group" style="max-width: 230px; margin-left: auto;">
													<h3>Imagen de bloque</h3>
													<div class="imgpreviewC" style="background-image:url('../assets/images/generales/flyers/default.png');"></div>
														<p class="mb-0">Por favor usa .jpg o .png sin fondo.</p>
														<input type="file" class="hidden openFileClass" id="imgpreviewC"  accept=".png, .jpg, .jpeg">
														<button type="button" class="btn btn-primary" id="openFileClass">Seleccionar una imagen</button>
														<!--<button type="button" class="btn btn-default">Quitar imagen de portada</button>-->
													</div>
													<div class="text-left">
													<h2>Contenido de bloque</h2>									
													</div>
													<div class="form-group hidden" id="newMClass">
														<label for="">Materia</label>
														<input type="text" id="newtitleClass" readonly class="form-control">
														<input type="hidden" id="newClass" readonly class="form-control">
													</div>
													<div class="form-group">
														<label for="">Título</label>
														<input class="form-control" id="title" placeholder="Título" required>
													</div>
													<div class="form-group">
														<select id="class" class="form-control"></select>
														<select  id="actB" class="form-control hidden"></select>
													</div>
													<div class="form-group">
														<button class="btn-files" type="button" id="btn-updf">Subir archivo / archivos</button>
													<input multiple type="file" class="hidden" id="upload-pdf" class="form-control" accept=".doc,.docx,application/msword,.pptx,.pdf"/>
													<input type="hidden" id="oldfiles">
													</div>
													<div class="form-group">
														<div id="show-elementsServer"></div>
														<div id="show-elements"></div>
													</div>
													<div class="form-group">
														<div id="summernote"><h2>Redacta tu contenido</h2></div>
													</div>
													<div class="form-group">
														<button class="btn btn-danger btn-vine" id="saveBlogs" disabled>Guardar</button>
													</div>
												</form>	
											</div>
										</div>
									</div>
								</div>
			
========
>>>>>>>> df1767a227d6648d0e63a5e3f95f329b09c01b6d:controlescolar/index.php
			
			<!--fin-calificaciones-->
		</div>

	</div>
	
	<!-- Modal modificar plan estudio -->
	<div class="modal fade modal-right" id="modalModificarDatosDirectorio">
					<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0">Formulario Asignar Datos a Directorio</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formDatosDirectorio" type="post">

                    <div class="form-group">
                        <label for="nombreDirectorio">NOMBRE:</label>
                        <input type="text" class="form-control upper" id="nombreDirectorio" name="nombreDirectorio" placeholder="Ingresa el nombre del alumno">
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="apellidoPaternoDirectorio">APELLIDO PATERNO:</label>
                        <input type="text" class="form-control upper" id="apellidoPaternoDirectorio" name="apellidoPaternoDirectorio" placeholder="Ingresa el apellido paterno del alumno">
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="apellidoMaternoDirectorio">APELLIDO MATERNO:</label>
                        <input type="text" class="form-control upper" id="apellidoMaternoDirectorio" name="apellidoMaternoDirectorio" placeholder="Ingresa el apellido materno del alumno">
                      </div>
                    </div>

					<div class="row mb-3">
						<div class="col-12">
							<label for="">Matrícula</label>
							<input type="text" name="inp_matricula" id="inp_matricula" class="form-control" placeholder="Matrícula">
						</div>
					</div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="generacionDirectorio">GENERACIÓN:</label>
                        <select class="form-control" name="generacionDirectorio" id="generacionDirectorio">
                        </select>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="estatusAlumnoDirectorio">ESTATUS:</label>
                          <select class="form-control" name="estatusAlumnoDirectorio" id="estatusAlumnoDirectorio" required>
                            <option value="" disabled="disabled">SELECCIONE EL ESTATUS DEL ALUMNO</option>
                            <option value="1">ACTIVO</option>
                            <option value="2">BAJA</option>
                            <option value="3">EGRESADO</option>
                            <option value="4">TITULADO</option>
                            <option value="5">EXPULSADO</option>
                          </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">

                      <label for="curpAlumnoDirectorio">CURP: <b><em>*Si ya cuenta con CURP presiona la tecla espacio al final de esta, para que se genere la edad.</em></b></label>
                        <input type="text" class="form-control" id="curpAlumnoDirectorio" name="curpAlumnoDirectorio" maxlength="18" placeholder="Ingresa la CURP del alumno">
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="edadAlumnoDirectorio">EDAD: <b><em>*Se generará al ingresar completamente la CURP.</em></b></label>
                        <input type="number" class="form-control" id="edadAlumnoDirectorio" name="edadAlumnoDirectorio" placeholder="Edad del alumno">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="emailAlumnoDirectorio">EMAIL:</label>
                        <input type="email" class="form-control" id="emailAlumnoDirectorio" name="emailAlumnoDirectorio" placeholder="Ingresa el email del alumno">
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="telefonoAlumnoDirectorio">TELÉFONO:</label>
                        <input type="tel" class="form-control" id="telefonoAlumnoDirectorio" name="telefonoAlumnoDirectorio" onkeypress="return checkTel(event)" maxlength="10" placeholder="Ingresa el número de teléfono del alumno">
                      </div>
                    </div>

                    <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="sexoAlumnoDirectorio">SEXO:</label>
                        <select class="form-control" name="sexoAlumnoDirectorio" id="sexoAlumnoDirectorio">
                          <!--<option value="" disabled="disabled">SELECCIONE EL ESTATUS DEL ALUMNO</option>-->
                          <option value="0">SIN ASIGNAR</option>
                          <option value="1">MUJER</option>
                          <option value="2">HOMBRE</option>
                        </select>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="gradoUltimoAlumnoDirectorio">ÚLTIMO GRADO ACADÉMICO:</label>
                        <select class="form-control" name="gradoUltimoAlumnoDirectorio" id="gradoUltimoAlumnoDirectorio">
                          <!--<option value="" disabled="disabled">SELECCIONE EL ÚLTIMO GRADO ACADÉMICO</option>-->
                          <option value="0">SIN ASIGNAR</option>
                          <option value="1">SECUNDARIA</option>
                          <option value="2">BACHILLERATO</option>
                          <option value="3">PREPARATORIA</option>
                          <option value="4">TSU</option>
                          <option value="5">LICENCIATURA</option>
                          <option value="6">MAESTRÍA</option>
                          <option value="8">DOCTORADO</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <center><label for="lugarRadicaDirectorio">LUGAR DONDE ESTUDIO</label></center>
                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="paisEstudioDirectorio">PAÍS DEL ÚLTIMO GRADO DE ESTUDIÓ:</label>
                            <select class="form-control" name="paisEstudioDirectorio" id="paisEstudioDirectorio">
                            </select>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="entidadEstudioDirectorio">ESTADO DEL ÚLTIMO GRADO DE ESTUDIÓ:</label>
                            <select class="form-control" name="entidadEstudioDirectorio" id="entidadEstudioDirectorio">
                            </select>
                          </div>
                        </div>
                    </div>
					<div class="border rounded p-2">
						<div class="">
						  <center><label for="lugarRadicaDirectorio">LUGAR DONDE RADICA</label></center>
						  <div class="row">
							<div class="col-sm-12 col-md-6 mb-2">
							  <label for="paisAlumnoDirectorio">PAÍS DONDE RADICA:</label>
							  <select class="form-control" name="paisAlumnoDirectorio" id="paisAlumnoDirectorio">
							  </select>
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
							  <label for="estadoAlumnoDirectorio">ESTADO DONDE RADICA:</label>
							  <select class="form-control" name="estadoAlumnoDirectorio" id="estadoAlumnoDirectorio">
							  </select>
							</div>
						  </div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">Ciudad</label>
								<input type="text" name="inp_ciudad" id="inp_ciudad" class="form-control">
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">Colonia</label>
								<input type="text" name="inp_colonia" id="inp_colonia" class="form-control">
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">Calle</label>
								<input type="text" name="inp_calle" id="inp_calle" class="form-control">
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">CP</label>
								<input type="text" name="inp_cp" id="inp_cp" class="form-control">
							</div>
						</div>
					</div>

                    <div class="form-group">
                      <center><label for="lugarNacimientoDirectorio">LUGAR DE NACIMIENTO</label></center>
                      <div class="row">
                        <div class="col-sm-12 col-md-6 mb-3">
                          <label for="paisNacimientoDirectorio">PAÍS DE NACIMIENTO:</label>
                          <select class="form-control" name="paisNacimientoDirectorio" id="paisNacimientoDirectorio">
                          </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                          <label for="entidadNacimientoDirectorio">ESTADO DE NACIMIENTO:</label>
                          <select class="form-control" name="entidadNacimientoDirectorio" id="entidadNacimientoDirectorio">
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="notasDirectorio">NOTAS:</label>
                      <textarea class="form-control" name="notasDirectorio" id="notasDirectorio" row="4" cols="50" placeholder="Ingresa tus notas"></textarea>
                    </div>

                    <div class="text-right">
                      <input type="hidden" name="idRelacion" id="idRelacion">
                      <input type="hidden" name="idAlumno" id="idAlumno_d">
                      <input type="hidden" name="idGeneracionAntigua" id="idGeneracionAntigua">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Actualizar</button>
                      <button type="button" name="cerrarEditarDirectorio" id="cerrarEditarDirectorio" class="btn btn-secondary waves-effect m-1-5" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div>
			<!-- Button trigger modal -->
			
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
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					<div class="modal-content">
							<div class="modal-header">
									<h5 class="modal-title">Documentos del Alumno</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
								</div>
						<div class="modal-body">
							<div class="container-fluid">
							<table id = "tabla-revision-formatos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
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
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="ModalObservacionesDoc" tabindex="-1" role="dialog" aria-labelledby="ModalObservacionesDoc" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
							<div class="modal-header">
									<h5 class="modal-title">Observaciones</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
											</div>
										</div>
									</div>

									<div class="form-group">
										<input class = "form-control d-none" type="number" name="idArchivo" id="idArchivo" required>
										<label for="ComentarioArchivo">Nuevo comentario</label>
										<input class = "form-group form-control" type="text"  name = "ComentarioArchivo" id= "ComentarioArchivo" required>
										<button type="submit" class="form-group btn btn-primary">Comentar</button>
									</div>
									
								</form>
							</div>
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
			<!--end-modal modificar carrera-->
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
