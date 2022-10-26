<?php
/*
$url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if(false !== strrpos($url,'.php')){
        die('No puedes acceder');
    }*/
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 3){
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
	
	<!-- Sweet Alert -->
    <link href="../assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">
	<link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../assets/css/newStyles.css">  
	
	<!-- CSS alerts-->
	<link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">


</head>


<body>
	<div class="header-bg">
		<!-- Navigation Bar-->
		<header id="topnav">
			<div class="topbar-main">
				<div class="container-fluid">
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

			<!-- MENU Start -->
			<div class="navbar-custom">
				<div class="container-fluid">
					<div id="navigation">
						<!-- Navigation Menu-->
						<ul class="navigation-menu">
                            <li class="has-submenu">
                                <a href="index.php"><i class="ti-home"></i> Inicio</a>
                            </li>
                            <?php
                                $accesos = ['market1@mk.com', 'master-marketing@mk.com', 'marketing.educativo.22@gmail.com'];
                                if (in_array($usuario['correo'], $accesos)): 
                            ?>
                            <li class="has-submenu">
                                <a href="gestorEventos.php"><i class="ion ion-md-calendar"></i> Gestor Eventos</a>
                            </li>
                            <?php endif ?>
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

	<div class="wrapper">
		<div class="container-fluid justify-content-center">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-8 col-md-8">
					<div class="page-title-box">
						<div class="row align-items-center">
							<div class="col-md-8">
								<h4 class="page-title m-0">Evento</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="nuevo-tab" data-toggle="tab" href="#nuevo" role="tab" aria-controls="nuevo" aria-selected="true">
										<span class="d-block d-sm-none"><i class="ion ion-md-add"></i></span>
										<span class="d-none d-sm-block"><i class="ion ion-md-add"></i> Crear Nuevo</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="eventos-tab" data-toggle="tab" data-target="#eventos" href="#eventos" role="tab" aria-controls="eventos" aria-selected="false">
										<span class="d-block d-sm-none"><i class="fas fa-book"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-book"></i> Ver Eventos</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="asistencias-tab" data-toggle="tab" data-target="#asistencias" href="#asistencias" role="tab" aria-controls="asistencias" aria-selected="false">
										<span class="d-block d-sm-none"><i class="fas fa-check"></i></span>
										<span class="d-none d-sm-block"></i><i class="fas fa-check"></i> Ver Asistencia Eventos</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="talleres-tab" data-toggle="tab" data-target="#talleres" href="#talleres" role="tab" aria-controls="talleres" aria-selected="false">
										<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Ver Talleres</span> 
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="ponencias-tab" data-toggle="tab" data-target="#ponencias" href="#ponencias" role="tab" aria-controls="ponencias" aria-selected="false">
										<span class="d-block d-sm-none"><i class="fas fa-user"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-user"></i> Ver Ponencias</span>
									</a>
								</li>
							</ul>
							
							<div class="tab-content bg-light">
								<div class="row tab-pane show active" id="nuevo" role="tabpanel" aria-labelledby="nuevo-tab">
									<div class="container col-sm-8 col-lg-8 col-md-8">
										<div class="card">
											<div class="card-body">
												<h4 class="m-t-0 pb-40">Registrar</h4>
												<!--<br>-->
												<form id="formularioRegistrar">
												<div class="form-group row">
													<label for="imagen" class="col-sm-3 control-label">Imagen *</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" name="imagen" id="imagen" accept=".jpg, .jpeg, .png" required>
														<!--<input type="file" class="form-control" name="imagen" id="imagen" required>-->
														<div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1170 x 520</div>
														<img src="" id="vImagen" class="img-fluid" alt="Responsive image" style="display: none;">
													</div>
												</div>
												<div class="form-group row">
													<label for="imgFondo" class="col-sm-3 control-label">Fondo *</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" name="imgFondo" id="imgFondo" accept=".jpg, .jpeg, .png" required>
														<!--<input type="file" class="form-control" name="imgFondo" id="imgFondo" required>-->
														<div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1920 x 895</div>
														<img src="" id="vFondo" class="img-fluid" alt="Responsive image" style="display: none;">
													</div>
												</div>
												<div class="form-group row">
													<label for="tipo" class="col-sm-3 control-label">Tipo evento *</label>
													<div class="col-sm-9">
														<select class="form-control" name="tipo" id="select_tipo_evento" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="AFILIACION">AFILIACIÓN</option>
															<option value="CONGRESO">CONGRESO</option>
															<option value="CURSO">CURSO</option>
															<option value="FORO">FORO</option>
															<option value="SEMINARIO">SEMINARIO</option>
															<option value="TALLER">TALLER</option>
															<option value="amor-con-amor">AMOR CON AMOR</option>
															<!--<option>Tipo n</option>-->
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="titulo" class="col-sm-3 control-label">Título del evento *</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Nombre del evento" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="nombreClave" class="col-sm-3 control-label">Nombre Clave *</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="nombreClave" name="nombreClave" placeholder="Definirá URL" onkeypress="return check(event)" required>
														<div class="clave alert alert-danger" style="display: none">Cambiar nombre clave</div>
													</div>
												</div>
												<div class="form-group row">
													<label for="fechaE" class="col-sm-3 control-label">Fecha evento *</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" name="fechaE" id="fechaE" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="fechaDisponible" class="col-sm-3 control-label">Fecha disponibilidad *</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" name="fechaDisponible" id="fechaDisponible" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="fechaLimite" class="col-sm-3 control-label">Fecha caducidad *</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" id="fechaLimite" name="fechaLimite" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="limiteProspectos" class="col-sm-3 control-label">Número Asistentes *</label>
													<div class="col-sm-9">
														<input type="text" class="form-control onlyNum" id="limiteProspectos" name="limiteProspectos" placeholder="Número máximo de asistentes" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="duracion" class="col-sm-3 control-label">Duración *</label>
													<div class="col-sm-4">
														<input type="number" class="form-control" id="duracion" name="duracion" placeholder="Numérico" min="1" required>
													</div>
													<div class="col-sm-4">
														<select class="form-control" name="tipoDuracion" id="inptipoDuracion" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="h">Hora</option>
															<option value="d">Día</option>
															<option value="s">Semana</option>
															<option value="m">Mes</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="asistenciasMin" class="col-sm-3 control-label">Asistencias mínimas *</label>
													<div class="col-sm-4">
														<input type="number" class="form-control" id="asistenciasMin" name="asistenciasMin" placeholder="Numérico" min="1" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="pais" class="col-sm-3 control-label">País *</label>
													<div class="col-sm-9">
														<select class="form-control" name="pais" id="pais" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="estado" class="col-sm-3 control-label">Estado *</label>
													<div class="col-sm-9">
														<select class="form-control" name="estado" id="estado" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="direccion" class="col-sm-3 control-label">Dirección</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección del evento" >
													</div>
												</div>
												<div class="form-group row">
													<label for="modalidadEvento" class="col-sm-3 control-label">Modalidad *</label>
													<div class="col-sm-9">
														<select class="form-control" name="modalidadEvento" id="selmodalidadEvento" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="Presencial">Presencial</option>
															<option value="En linea">En línea</option>
															<option value="Mixta">Mixta</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="idInstitucion" class="col-sm-3 control-label">Institución</label>
													<div class="col-sm-9">
														<select class="form-control" name="idInstitucion" id="" required>
															<option value="13">CONACON</option>
															<option value="19">IESM</option>
															<option value="20">UDC</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="codigoPromocional" class="col-sm-3 control-label">Código Promocional</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="codigoPromocional" name="codigoPromocional" >
													</div>
												</div>
												<div class="form-group row">
													<label for="plantilla_bienvenida" class="col-sm-3 control-label">Plantilla</label>
													<div class="col-sm-9">
														<select class="form-control" name="plantilla_bienvenida" id="plantilla_bienvenida" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="descripcion" class="col-sm-3 control-label">Descripción</label>
													<div class="col-sm-9">
														<textarea class="form-control" name="descripcion" id="descripcion" row="4" cols="50" placeholder="Ingresa tu descripción"></textarea>
													</div>
												</div>
												<div class="form-group">
													<div>
														<button type="submit" class="btn btn-primary waves-effect waves-light" id="Enviar">
															Registrar
														</button>
														<button type="reset" id="reiniciar" class="btn btn-secondary waves-effect m-l-5">
															Cancelar
														</button>
													</div>
												</div>
												</form>
											</div>
										</div>
									</div>
								</div> 

								<div class="row tab-pane fade" id="eventos" role="tabpanel" aria-labelledby="eventos-tab">
									<!--col-lg-12-->
									<div class="container col-sm-12 col-lg-12">		
										<div class="card">
											<div class="card-body">
												<div class="table-responsive">
												<h4 class="m-b-30 m-t-0">Lista de eventos</h4>
												<table id="datatable-eventos" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
													<thead>
														<tr>
															<th>ID Evento</th>
															<th>Tipo</th>
															<th>Título</th>
															<th>Link Evento</th>
															<th>Fecha Evento</th>
															<th>Fecha Disponibilidad</th>
															<th>Fecha Caducidad</th>
															<th>Número Asistentes</th>
															<th>Duración</th>
															<th>Tipo De Duración</th>
															<th>Cantidad mínima de asistencias</th>
															<th>Dirección</th>
															<th>Estado</th>
															<th>País</th>
															<th>Código Promocional</th>
															<th>Estatus</th>
															<th>Modalidad</th>
															<th>Institución</th>
															<th>Imagen</th>
															<th>Fondo</th>
															<th>Descripción</th>
															<th>Plantilla</th>
															<th>         </th>
															<th>         </th>
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

								<!-- Tabla asistencias eventos -->

								<div class="tab-pane fade" id="asistencias" role="tabpanel" aria-labelledby="asistencias-tab">
                  					<div class="container-liquid">
                    					<div class="card">
                      						<div class="card-body">
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
                      						</div>
                    					</div>

                  					</div>
                				</div>

								<!-- Fin tabla asistencias eventos -->

								<div class="row tab-pane fade" id="talleres" role="tabpanel" aria-labelledby="talleres-tab">
									<!--col-lg-12-->
									<div class="container col-sm-12 col-lg-12">		
										<div class="card">
											<div class="card-body">
												<button class="btn btn-primary" id="btn-add-taller">Registrar nuevo taller</button>
												<h4 class="m-b-30 m-t-0">Lista de talleres</h4>
												<div class="row">
													<div class="col-sm-12 col-md-6 mb-4">
														<select id="select-eventos-taller" class="form-control">
		
														</select>
													</div>
													<div class="col-sm-12 col-md-6 mb-4">
														<button type="button" id="btn-taller-evento" disabled="true" class="btn btn-primary waves-effect waves-light" onclick="reporte_general()">
															REPORTE GENERAL
														</button>
													</div>
												</div>
												<div class="table-responsive TBNR">
													<table id="datatable-talleres" class="table table-striped table-bordered w-100" data-order='[[2, "desc"]]'>
														<thead>
															<tr>
																<th>Taller</th>
																<th>Salón</th>
																<th>Precio</th>
																<th>Fecha</th>
																<th>Registrados</th>
																<th>Detalles</th>
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

								<div class="row tab-pane fade" id="ponencias" role="tabpanel" aria-labelledby="ponencias-tab">
									<!--col-lg-12-->
									<div class="container col-sm-12 col-lg-12">		
										<div class="card">
											<div class="card-body">
												<!-- <button class="btn btn-primary" id="btn-add-taller">Registrar nuevo ponencia</button> -->
												<h4 class="m-b-30 m-t-0">Lista de ponencias</h4>
												<div class="row">
													<div class="col-sm-12 col-md-6 mb-4">
														<select id="select-eventos-taller-p" class="form-control">
		
														</select>
													</div>
													<!-- <div class="col-sm-12 col-md-6 mb-4">
														<button type="button" id="btn-taller-evento" disabled="true" class="btn btn-primary waves-effect waves-light" onclick="reporte_general()">
															REPORTE GENERAL
														</button>
													</div> -->
												</div>
												<div class="table-responsive TBNR">
													<table id="datatable-ponencias" class="table table-striped table-bordered w-100" data-order='[[2, "desc"]]'>
														<thead>
															<tr>
																<th>Ponencia</th>
																<th>Salón</th>
																<th>Precio</th>
																<th>Fecha</th>
																<th>Detalles</th>
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
								<!-- Modal  Asistencias -->
								<div class="modal fade" id="modalAsistenciasEventos" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
									<input class ="d-none" type="text" id = "idEventos">
									<div class="modal-dialog modal-xl" role="document">
									<div class="modal-content">
										<div class="modal-header">
										<h5 class="modal-title">Asistencia Evento</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
										<div class="row">
											<div class="col-md-12">
												<div class="table-reponsive">
												<table id = "TablaAsistenciaEventos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
													<thead>
													<tr>
														<th>Alumno</th>
														<th>Correo</th>
														<th>Acciones</th>
														<th>Registrar asistencia</th>
													</tr>
													</thead>
													<tbody>

													</tbody>
												</table>
												</div>
											</div>
										</div>
										</div>
										<div class="modal-footer">
										<div class="row">
											<div class="col-md-6">
											<button type="button" id ="BtnEnvioSeleccionarTodos" class="btn btn-primary">Seleccionar Todos</button>
											</div>

											<div class="col-md-6">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
											<button type="button" id ="BtnEnvioCertificados" class="btn btn-primary">Guardar</button>
											</div>
										</div>
										
										</div>
									</div>
									</div>
								</div>
								<!--///////////////////////////////////////--->

							<div class="modal fade bs-example-modal-lg" id="modalModify" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
										</div>
										<div class="modal-body">
											<form id="formularioModificar">
												<div class="form-group row">
													<label for="newImagen" class="col-sm-3 control-label">Imagen</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" name="newImagen" id="newImagen" accept=".jpg, .jpeg, .png">
														<br>
														<img src="" id="devImagen" class="img-fluid" alt="Responsive image">
													</div>
												</div>
												<div class="form-group row">
													<label for="newFondo" class="col-sm-3 control-label">Fondo</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" name="newFondo" id="newFondo" accept=".jpg, .jpeg, .png">
														<br>
														<img src="" id="devFondo" class="img-fluid" alt="Responsive image">
													</div>
												</div>
												<div class="form-group row">
													<label for="upd_estatus" class="col-sm-3 control-label">Estatus del evento</label>
													<div class="col-sm-9">
														<select name="upd_estatus" id="upd_estatus" class="form-control">
															<option value="1">Activo</option>
															<option value="2">En videoteca</option>
															<option value="3">Deshabilitado</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="devTipo" class="col-sm-3 control-label">Tipo evento</label>
													<div class="col-sm-9">
														<select class="form-control" name="devTipo" id="devTipo" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="AFILIACION">AFILIACIÓN</option>
															<option value="CONGRESO">CONGRESO</option>
															<option value="CURSO">CURSO</option>
															<option value="FORO">FORO</option>
															<option value="SEMINARIO">SEMINARIO</option>
															<option value="TALLER">TALLER</option>
															<option value="amor-con-amor">AMOR CON AMOR</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="devTitulo" class="col-sm-3 control-label">Título del evento</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="devTitulo" id="devTitulo" placeholder="Nombre del evento" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="nomClave" class="col-sm-3 control-label">Nombre Clave</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="devClave" id="devClave" placeholder="Definirá URL" onkeypress="return check(event)" required>
														<div class="devMessC alert alert-danger" style="display: none">Cambiar nombre clave</div>
													</div>
												</div>
												<div class="form-group row">
													<label for="devFE" class="col-sm-3 control-label">Fecha evento</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" name="devFE" id="devFE" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="devFD" class="col-sm-3 control-label">Fecha disponibilidad</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" name="devFD" id="devFD" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="devFL" class="col-sm-3 control-label">Fecha caducidad</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" name="devFL"  id="devFL" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="devLimite" class="col-sm-3 control-label">Número Asistentes</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="devLimite" id="devLimite" placeholder="Número máximo de asistentes" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="devDuracion" class="col-sm-3 control-label">Número Sesiones</label>
													<div class="col-sm-4">
														<input type="number" class="form-control" name="devDuracion" id="devDuracion" placeholder="Numérico"  min="" max="" required>
													</div>
													<div class="col-sm-4">
														<select class="form-control" name="devTipoD" id="devTipoD" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="h">Hora</option>
															<option value="d">Día</option>
															<option value="s">Semana</option> 
															<option value="m">Mes</option> 
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="devAsistenciasM" class="col-sm-3 control-label">Asistencias mínimas</label>
													<div class="col-sm-4">
														<input type="number" class="form-control" name="devAsistenciasM" id="devAsistenciasM" placeholder="Numérico"  min="" max="" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="devPais" class="col-sm-3 control-label">País</label>
													<div class="col-sm-9">
														<select class="form-control" name="devPais" id="devPais" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="devEstado" class="col-sm-3 control-label">Estado</label>
													<div class="col-sm-9">
														<select class="form-control" name="devEstado" id="devEstado" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="devDireccion" class="col-sm-3 control-label">Dirección</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="devDireccion" id="devDireccion" placeholder="Dirección del evento" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="devModalidad" class="col-sm-3 control-label">Modalidad</label>
													<div class="col-sm-9">
														<select class="form-control" name="devModalidad" id="devModalidad" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="Presencial">Presencial</option>
															<option value="En linea">En línea</option>
															<option value="Mixta">Mixta</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="devIDInst" class="col-sm-3 control-label">Institución</label>
													<div class="col-sm-9">
														<select class="form-control" name="devIDInst" id="devIDInst" required>
															<option selected="true" disabled="disabled">Seleccione</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="devPromocion" class="col-sm-3 control-label">Código Promocional</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="devPromocion" id="devPromocion" >
													</div>
												</div>
												<div class="form-group border rounded py-3 px-4">
													<label for="">Enlaces de videos</label>
													<div class="row">
														<div class="col-6">
															<input type="text" class="form-control input-sm" name="inp_enlaces_titulo" id="inp_enlaces_titulo" placeholder="Título">
														</div>
														<div class="col-6">
															<input type="text" class="form-control input-sm" name="inp_enlaces_url" id="inp_enlaces_url" placeholder="URL">
														</div>
													</div>
												</div>
												<div class="form-group row">
													<label for="devDescripcion" class="col-sm-3 control-label">Descripción</label>
													<div class="col-sm-9">
														<textarea class="form-control" name="devDescripcion" id="devDescripcion" row="4" cols="50" placeholder="Ingresa tu descripción" required></textarea>
													</div>
												</div>
												<div class="form-group row">
													<label for="newPlantilla" class="col-sm-3 control-label">Plantilla</label>
													<div class="col-sm-9">
														<select class="form-control" name="newPlantilla" id="newPlantilla" required>
															<option selected="true" disabled="disabled">Seleccione</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<div>
														<input type="hidden" name="idModify" id="idModify">
														<button type="submit" name="btnModificar" id="btnModificar" class="btn btn-primary waves-effect waves-light" aria-hidden="true">
															Modificar
														</button>
														<button type="button" id="ocultar" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal" aria-hidden="true">
															Cancelar
														</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>

							<div class="modal fade bs-example-modal-lg" id="modalPersonasTaller" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xl">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title m-0" >Personas registradas al taller</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
										</div>
										<div class="modal-body">
											<button class="btn btn-primary ml-auto mb-3" style="display:none" id="btn_agregar_a_taller" type-e="">
											</button>
											<div class="border border-radious my-3 p-2" id="frm_agregar_alumno" style="display:none">
												<!-- Agregar alumno a taller -->
												<div class="row for_type mb-3 border-bottom">
													<h5>Tipos de alumnos admitidos al taller</h5>
													<div class="col-sm-12 col-md-6 mb-3"  style="max-height: 35vh;overflow: auto;">
														<table id="tipos_alumnos" class="table my-2">
															<thead class="thead-light">
																<th>Tipo</th>
																<th></th>
															</thead>
															<tbody></tbody>
														</table>
													</div>
													<hr>
												</div>

												<div class="row border-bottom">
													<div class="col-sm-12 col-md-6">
														<h5>Busca a un alumno por nombre</h5>
														<div class="input-group mb-3">
															<input type="text" class="form-control border" placeholder="Buscar alumno por nombre" id="buscar-alumno" aria-describedby="button-addon1">
															<button class="btn btn-outline-secondary" type="button" id="button-addon1"><i class="fas fa-search"></i></button>
														</div>

													</div>
													<div class="col-sm-12 col-md-6 mb-3" style="max-height: 35vh;overflow: auto;">
														<h5>Alumnos disponibles</h5>
														<input type="text" class="form-control" id="filtrar_alumnos_disp" placeholder="Buscar alumno" style="display:none">
														<table id="alumnos_coincidencia" class="table table-hover my-2">
															<thead class="thead-light">
																<th>Nombre</th>
																<th></th>
															</thead>
															<tbody>
																<tr><td colspan="2">No hay resultados en la busqueda</td></tr>
															</tbody>
														</table>
													</div>
												</div>
												
												<!-- Permitir un tipo de alumno al taller -->
												
												<div class="row for_type">
													<div class="col-sm-12 col-md-6" style="max-height: 35vh;overflow: auto;">
														<h5>Alumnos incluidos</h5>
														<table class="table" id="taller_priv_alumn_mas">
															<thead class="thead-light">
																<th>Nombre</th>
																<th></th>
															</thead>
															<tbody></tbody>
														</table>
													</div>
													<div class="col-sm-12 col-md-6" style="max-height: 35vh;overflow: auto;">
														<h5>Alumnos excluidos</h5>
														<table class="table" id="taller_priv_alumn_menos">
															<thead class="thead-light">
																<th>Nombre</th>
																<th></th>
															</thead>
															<tbody></tbody>
														</table>
													</div>
												</div>
												
											</div>
											<div class="table-responsive TBNR">
												<input type="hidden" id="taller_view">
												<table id="tabla_asistentes_taller" class="table table-striped table-bordered w-100">
													<thead>
														<tr>
															<th>Persona</th>
															<th>Taller</th>
															<th>Asistencia</th>
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

							<div class="modal fade bs-example-modal-lg" id="modal_taller" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xl">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title m-0"><span id="lbl_action"></span> taller</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
										</div>
										<div class="modal-body">
											<form id="form-taller">
												<input type="hidden" name="inp_id_taller" id="inp_id_taller">
												<div class="row form-group">
													<div class="col-sm-12 col-md-6">
														<label for="">Evento</label>
														<select name="select_evento_t" id="select_evento_t" class="form-control" required>

														</select>
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Nombre de taller</label>
														<input type="text" name="inp_nombre_t" id="inp_nombre_t" class="form-control" required>
													</div>
												</div>
												<div class="row form-group">
													<div class="col-sm-12 col-md-6">
														<label for="">Nombre del ponente</label>
													<input type="text" name="ponente" id="ponente" class="form-control">
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Evento privado</label>
														<select name="select_tipo_t" id="select_tipo_t" class="form-control">
															<option value="2">No</option>
															<option value="1">Si</option>
														</select>
													</div>
													
												</div>

												<div class="row form-group">
												<div class="col-sm-12 col-md-6">
														<label for="">Fecha de taller</label>
														<div class="input-group mb-3">
															<input type="date" name="inp_fecha_e" id="inp_fecha_e" class="form-control" required>
															<input type="time" name="inp_hora_tall" id="inp_hora_tall" class="form-control">
														</div>
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Cupo</label>
														<input type="text" name="inp_cupo_limite" id="inp_cupo_limite" class="form-control onlynum" required>
													</div>
												
												</div>

												<div class="row form-group">
												<div class="col-sm-12 col-md-6">
														<label for="">Salón</label>
														<input type="text" class="form-control" name="inp_nombre_salon" id="inp_nombre_salon">
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Otorga certificado</label>
														<select name="select_ciertifica_t" id="select_ciertifica_t" class="form-control">
															<option value="2">No</option>
															<option value="1">Si</option>
														</select>
													</div>
												
												</div>

												<div class="row form-group">
												<div class="col-sm-12 col-md-6" style="display:none;">
														<label for="">Plantilla de certificado</label>
														<input type="file" class="form-control" name="imagen_cert_t" id="imagen_cert_t" accept=".jpg, .jpeg, .png" required disabled>
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Costo</label>
														<input type="tel" name="inp_costo_t" id="inp_costo_t" class="form-control moneyFt" data-prefix="$ " value="$ 0.00">
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Moneda</label>
														<select name="select_tipo_pago_t" id="select_tipo_pago_t" class="form-control">
															<option value="mxn">MXN</option>
															<option value="usd">USD</option>
														</select>
													</div>
												</div>

												<div class="row my-4">
													<button type="submit" class="btn btn-primary ml-auto">Guardar</button>
													<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
												</div>
											</form>
											
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade bs-example-modal-lg" id="modal_ponencias" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xl">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title m-0"><span id="lbl_action-p"></span> Ponencia</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
										</div>
										<div class="modal-body">
											<form id="form-ponencias">
												<input type="hidden" name="inp_id_ponencia" id="inp_id_ponencia">
												<div class="row form-group">
													<div class="col-sm-12 col-md-6">
														<label for="">Evento</label>
														<select name="select_evento_po" id="select_evento_po" class="form-control" required>

														</select>
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Nombre de la ponencia</label>
														<input type="text" name="inp_nombre_po" id="inp_nombre_po" class="form-control" required>
													</div>
												</div>
												<div class="row form-group">
													<div class="col-sm-12 col-md-6">
														<label for="">Nombre del ponente</label>
													<input type="text" name="ponente" id="ponente_po" class="form-control">
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Evento privado</label>
														<select name="select_tipo_po" id="select_tipo_po" class="form-control">
															<option value="2">No</option>
															<option value="1">Si</option>
														</select>
													</div>
													
												</div>

												<div class="row form-group">
												<div class="col-sm-12 col-md-6">
														<label for="">Fecha de la ponencia</label>
														<div class="input-group mb-3">
															<input type="date" name="inp_fecha_e" id="inp_fecha_ep" class="form-control" required>
															<input type="time" name="inp_hora_pon" id="inp_hora_pon" class="form-control">
														</div>
													</div>
													<div class="col-sm-12 col-md-6">
														<label for="">Cupo</label>
														<input type="text" name="inp_cupo_limite" id="inp_cupo_limite_po" class="form-control onlynum" required>
													</div>
												
												</div>

												<div class="row form-group">
												<div class="col-sm-12 col-md-6">
														<label for="">Salón</label>
														<input type="text" class="form-control" name="inp_nombre_salon" id="inp_nombre_salon_po">
													</div>
													
													<div class="col-sm-12 col-md-6">
														<label for="">Costo</label>
														<input type="tel" name="inp_costo_po" id="inp_costo_po" class="form-control moneyFt" data-prefix="$ " value="$ 0.00">
													</div>
												
												</div>

												<div class="row form-group">
													<!-- <div class="col-sm-12 col-md-6">
														<label for="">Otorga certificado</label>
														<select name="select_ciertifica_t" id="select_ciertifica_t" class="form-control">
															<option value="2">No</option>
															<option value="1">Si</option>
														</select>
													</div> -->
												<!-- <div class="col-sm-12 col-md-6" style="display:none;">
														<label for="">Plantilla de certificado</label>
														<input type="file" class="form-control" name="imagen_cert_t" id="imagen_cert_t" accept=".jpg, .jpeg, .png" required disabled>
													</div> -->
												
													<!-- <div class="col-sm-12 col-md-6">
														<label for="">Moneda</label>
														<select name="select_tipo_pago_t" id="select_tipo_pago_t" class="form-control">
															<option value="mxn">MXN</option>
															<option value="usd">USD</option>
														</select>
													</div> -->
												</div>

												<div class="row my-4">
													<button type="submit" class="btn btn-primary ml-auto">Guardar</button>
													<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
												</div>
											</form>
										</div>
										
									</div>
								</div>
							</div>
							


						</div>
						</div>
					</div>
				</div><!--end card-body-->
			</div><!--end cart-->	
		</div><!-- end container-fluid -->	
	</div>
	<!-- end wrapper -->
	
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
	<script src="../assets/js/template/jquery.maskMoney.js"></script>
	
	<!-- Sweet-Alert  -->
	<script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	<script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>

	<!--Required datatables js-->
	<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<!--error<script src="../assets/plugins/datatables/jquery.dataTables.js"></script>-->
	<script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

	<!--Buttons examples-->
	<script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="../assets/plugins/datatables/buttons.bootstrap4.min.js"></script>

	<script src="../assets/plugins/datatables/jszip.min.js"></script>
	<script src="../assets/plugins/datatables/pdfmake.min.js"></script>
	<script src="../assets/plugins/datatables/vfs_fonts.js"></script>
	<script src="../assets/plugins/datatables/buttons.html5.min.js"></script>
	<script src="../assets/plugins/datatables/buttons.print.min.js"></script>
	<!--<script src="../assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>-->
	<script src="../assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
	<script src="../assets/plugins/datatables/dataTables.keyTable.min.js"></script>
	<script src="../assets/plugins/datatables/dataTables.scroller.min.js"></script>

	<script src="../assets/js/template/sweetalert.min.js"></script>
	<script src="../assets/js/eventos/eventosJs.js"></script>

	<script src="../assets/pages/sweet-alert.init.js"></script>

	<!--Responsive examples-->
	<script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
	<!--error<script src="../assets/plugins/datatables/dataTables.responsive.js"></script>-->
	<script src="../assets/plugins/datatables/responsive.bootstrap.min.js"></script>

	<!--Datatable init js-->
	<script src="../assets/pages/datatables.init.js"></script>
	<script src="../assets/pages/datatables.init.class.js"></script>
	<!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>-->

	<script src="../assets/js/template/app.js"></script>
	<script>
		$(document).ready(function(){
			$(".moneyFt").maskMoney();
		})
		$(".onlynum").keypress(function(e){
			if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)){
				return false;
			}
		});
	</script>
</body>

</html>
