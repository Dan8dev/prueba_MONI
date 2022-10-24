<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 8 && $_SESSION["usuario"]['idTipo_Persona'] != 4){
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
	
	<!-- CSS alerts-->
	<link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">

	<style type="text/css">
        .tab_active{
          color: #aa262c;
          border-bottom: solid #aa262c 2px;
        }
        .page-title > span {
          cursor: pointer;
        }
        .form-control{
          border: 1px solid #c8c8c8;
        }
		.myCustomClass-info .swal2-icon.swal2-info{
    	border-color: #AA262C;
    	color: #AA262C;
		}
    </style>

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
								<a class="navbar-toggle nav-link">
									<div class="lines">
										<span></span>
										<span></span>
										<span></span>
									</div>
								</a>
							</li>
						</ul>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>

			<div class="navbar-custom">
				<div class="container-fluid">
					<div id="navigation">
						<ul class="navigation-menu">
							<li class="has-submenu">
								<a href="../plan-pagos/index.php"><i class="ti-home"></i> Inicio</a>
							</li>

							<li class="has-submenu">
								<a href="../plan-pagos/alumnos.php"><i class="ion ion-md-calendar"></i> Gestionar Alumnos</a>
							</li>

                            <li class="has-submenu">
                                <a href="index.php"><i class="fas fa-plane-departure"></i> Visita Alumnos</a>
                            </li>
						</ul>
					</div>
				</div>
			</div>
		</header>
	</div>

	<div class="wrapper">
		<div class="container-liquid justify-content-center">
			<div class="row card">
				<div class="card-body">
					<div class="col-md-12">
						<ul class="nav nav-tabs" id="MenuPrincipal" role="tablist">
							<li class="nav-item">
								<a class="nav-link tab_active active" id="cortesias-tab" data-toggle="tab" date-target="#cortesias" href="#cortesias" role="tab" aria-controls="cortesias" aria-selected="true">
									<span tab-target="cortesias">
										<i class="fas fa-pen-alt"></i> Administrar Cortesias
									</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="hotel-tab" data-toggle="tab" data-target="#hotel" href="#hotel" role="tab" aria-controls="hotel" aria-selected="false">
									<span tab-target="hotel" >
										<i class="fas fa-hotel"></i> Hotel
									</span> 
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="transporte-tab" data-toggle="tab" data-target="#transporte" href="#transporte" role="tab" aria-controls="transporte" aria-selected="false">
									<span tab-target="transporte" >
										<i class="fas fa-bus"></i> Transporte
									</span> 
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="alimentos-tab" data-toggle="tab" data-target="#alimentos" href="#alimentos" role="tab" aria-controls="alimentos" aria-selected="false">
									<span tab-target="alimentos" >
										<i class="on ion-md-restaurant"></i> Alimentos
									</span> 
								</a>
							</li>
			
							<li class="nav-item">
								<a class="nav-link" id="listGeneral-tab" data-toggle="tab" data-target="#listGeneral" href="#listGeneral" role="tab" aria-controls="listGeneral" aria-selected="false">
									<span tab-target="listGeneral">
										<i class="mdi mdi-notebook"></i> Vista general
									</span> 
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="tab-content bg-light">
				<div class="row tab-pane show active" id="cortesias" role="tabpanel" aria-labelledby="cortesias-tab">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<ul class="nav nav-tabs" id="AdministracionCortesias" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="Creadas-tab" data-toggle="tab" href="#Creadas" role="tab" aria-controls="Creadas" aria-selected="true">
												<span class="d-block d-sm-none"><i class="ion ion-md-time"></i></span>
												<span class="d-none d-sm-block">En curso</span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="Concluidas-tab" data-toggle="tab" data-target="#Concluidas" href="#Concluidas" role="tab" aria-controls="Concluidas" aria-selected="false">
												<span class="d-block d-sm-none"><i class="mdi mdi-check-bold"></i></span>
												<span class="d-none d-sm-block"></span>No disponibles</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="tab-content bg-light">
								<div class="tab-pane show active" id="Creadas" role="tabpanel" aria-labelledby="Creadas-tab">
									<div class="card">
										<div class="card-body">
											<div class="table-responsive">
												<div class="row">
													<div class="col">
														<h4 class="m-b-30 m-t-0">Cortesias En curso</h4>
													</div>
													<div class="col text-right">
														<button class="btn btn-primary" data-toggle="modal" data-target="#ModalCortesias" href="#ModalCortesias">Nueva Cortesia</button>
													</div>
												</div>
												<table id="datatable-cortesias" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
													<thead>
														<tr>
															<th>Nombre</th>
															<th>Informacion</th>
															<th>Fecha Inicio</th>
															<th>Fecha Final</th>
															<th>Tipo</th>
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
								<div class="tab-pane" id="Concluidas" role="tabpanel" aria-labelledby="Concluidas-tab">
									<div class="card">
										<div class="card-body">
											<div class="table-responsive">
												<div class="row">
													<div class="col">
														<h4 class="m-b-30 m-t-0">Cortesias Finalizadas</h4>
													</div>
													<div class="col text-right">
														<!-- <button class="btn btn-primary" data-toggle="modal" data-target="#ModalCortesias" href="#ModalCortesias">Nueva Cortesia</button> -->
													</div>
												</div>
												<table id="datatable-cortesias-finalizadas" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
													<thead>
														<tr>
															<th>Nombre</th>
															<th>Informacion</th>
															<th>Fecha Inicio</th>
															<th>Fecha Final</th>
															<th>Tipo</th>
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
						</div><!--end card-body-->
					</div>
				</div>
				<div class="row tab-pane" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
					<div class="card" id="tab_hotel">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<ul class="nav nav-tabs" id="myTab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="espera-tab" data-toggle="tab" href="#espera" role="tab" aria-controls="espera" aria-selected="true">
												<span class="d-block d-sm-none"><i class="ion ion-md-time"></i></span>
												<span class="d-none d-sm-block">En Espera</span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="concluidos-tab" data-toggle="tab" data-target="#concluidos" href="#concluidos" role="tab" aria-controls="concluidos" aria-selected="false">
												<span class="d-block d-sm-none"><i class="mdi mdi-check-bold"></i></span>
												<span class="d-none d-sm-block">Concluidos</span>
											</a>
										</li>

										<li class="nav-item">
											<a class="nav-link" id="hoteles-tab" data-toggle="tab" data-target="#hoteles" href="#hoteles" role="tab" aria-controls="hoteles" aria-selected="false">
												<span class="d-block d-sm-none"><i class="mdi mdi-check-bold"></i></span>
												<span class="d-none d-sm-block">Administrar Hoteles</span>
											</a>
										</li>
									</ul>

									<div class="tab-content bg-light">
										<div class="row tab-pane show active" id="espera" role="tabpanel" aria-labelledby="espera-tab">
											<div class="card">
												<div class="card-body">
												<div class="table-responsive">
													<div class="row">
														<div class="col">
															<h4 class="m-b-30 m-t-0">Lista en espera de hotel</h4>
														</div>
														<div class="col text-right">
															<button data-toggle="modal" data-target="#ModalSolicitarMatch" href="#ModalSolicitarMatch" class = "btn btn-primary">Nueva solicitud</button>
														</div>
													</div>
													<table id="datatable-esperaHotel" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
															<tr>
																<th>Apellido Paterno</th>
																<th>Apellido Materno</th>
																<th>Nombre</th>
																<th></th>
																<th>Apellido Paterno</th>
																<th>Apellido Materno</th>
																<th>Nombre</th>
																<th></th>
																<th></th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
													</div>	
												</div>
											</div>
										</div> 
										<div class="row tab-pane fade" id="concluidos" role="tabpanel" aria-labelledby="concluidos-tab">	
											<div class="card">
												<div class="card-body">
													<div class="table-responsive text-center">
													<h4 class="m-b-30 m-t-0">Lista final de hotel</h4>
													<table id="datatable-hotel" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
															<tr>
																<th>Apellido Paterno</th>
																<th>Apellido Materno</th>
																<th>Nombre</th>
																<th></th>
																<th>Apellido Paterno</th>
																<th>Apellido Materno</th>
																<th>Nombre</th>
																<th>Hotel</th>
																<th>Número de habitación</th>
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
										<div class="row tab-pane" id="hoteles" role="tabpanel" aria-labelledby="hoteles-tab">
											<div class="card">
												<div class="card-body">
												<div class="table-responsive text-center">
													<div class="row">
														<div class="col-md-6">
															<h4 class="m-b-30 m-t-0">Hoteles registrados</h4>
														</div>
														<div class="col-md-6 text-rigth">
															<button data-toggle="modal" data-target="#ModalAddHotel" href="#ModalAddHotel" class = "btn btn-primary">Nuevo Hotel</button>
														</div>
													</div>
													<table id="datatable-Hoteles" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
														<thead>
															<tr>
																<th>Nombre</th>
																<th>Dirección</th>
																<th></th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
													</div>	
												</div>
											</div>
										</div>
									</div><!--end-tab-content-->

									<div class="modal fade" id="ModalAddHotel" tabindex="-1" role="dialog" aria-labelledby="ModalAddHotel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Añadir nuevo hotel</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
												</div>
												<form id = "form-nuevo-hotel">
													<input type="text" id = "tipoCase" name = "tipoCase" class = "d-none">
													<input type="text" id = "idHotel" name = "idHotel" class = "d-none" value="null">
													<div class="modal-body">
														<div class="row">
															<div class="col-md-12 form-group">
																<label for="newHotel">Nombre:</label>
																<input type="text" class = "form-control" name = "newHotel" id="newHotel" placeholder = "Introduzca el nombre del nuevo Hotel" required>
															</div>
															<div class="col-md-12 form-group">
																<label for="direccion">Dirección:</label>
																<input type="text" class = "form-control" name = "direccion" id="direccion" placeholder = "Introduzca la dirección del Hotel" required>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
														<button type="submit" class="btn btn-primary">Guardar</button>
													</div>
												</form>
											</div>
										</div>
									</div>

									<div class="modal fade" id="ModalSolicitarMatch" tabindex="-1" role="dialog" aria-labelledby="ModalSolicitarMatch" aria-hidden="true">
										<div class="modal-dialog modal-xl" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Solicitud de Match</h4>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
												</div>
												<form id="form-Solicitud-match">
													<div class="modal-body">
														<div class="row form-group">
															<div class="col-md-4">
																<label for="Carrera">Carrera:</label>
																<select class ="form-control" id="Carrera">
																	<option value="" disabled selected>Selecciona una carrera</option>
																</select>
															</div>
															<div class="col-md-4">
																<label for="generacion">Generación:</label>
																<select class ="form-control" id="generacion">
																	<option value="" disabled selected>Selecciona una generacion</option>
																</select>
															</div>

															<div class="col-md-4">
																<label for="cortesia_match">Cortesia:</label>
																<select class = "form-control" name = "idcortesia" id="cortesia_match" required>
																	<option value="" disabled selected>Selecciona una cortesia</option>
																</select>
															</div>
			
														</div>
														<div class="row form-group">
															<div class="col-md-6">
																<label for="idSolic">Alumno Solicitante</label>
																<select class ="form-control" name="idSolic" id="idSolic" required>
																	<option value="" disabled selected>Seleccione al alumno solicitante</option>
																</select>
															</div>
															<div class="col-md-6">
																<label for="idCompa">Alumno para Match</label>
																<select class ="form-control" name="idCompa" id="idCompa" required>
																	<option value="" disabled selected>Seleccione al alumno para hacer match</option>
																</select>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
														<button type="submit" class="btn btn-primary" id = "btn-solicitar-reservacion" disabled>Guardar</button>
													</div>
												</form>
											</div>
										</div>
									</div>

									<div class="modal fade bs-example-modal-lg" id="modalAsignarHtl" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Asignar</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
												</div>
												<div class="modal-body">
													<form id="formAsignarHotel">
														<div class="form-group row">
															<label for="devNom" class="col-sm-3 control-label">Nombre</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devNom" id="devNom" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAPaterno" class="col-sm-3 control-label">Apellido Paterno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAPaterno" id="devAPaterno" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAMaterno" class="col-sm-3 control-label">Apellido Materno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAMaterno" id="devAMaterno" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devNomComp" class="col-sm-3 control-label">Nombre</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devNomComp" id="devNomComp" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAPComp" class="col-sm-3 control-label">Apellido Paterno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAPComp" id="devAPComp" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAMComp" class="col-sm-3 control-label">Apellido Materno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAMComp" id="devAMComp" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="hotelesAsig" class="col-sm-3 control-label">Hotel</label>
															<div class="col-sm-9">
																<select class="form-control" name="hotelesAsig" id="hotelesAsig" required>
																</select>
															</div>
														</div>
														<div class="form-group row">
															<label for="habitacion" class="col-sm-3 control-label">Habitación</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="habitacion" id="habitacion" required>
															</div>
														</div>
														<div class="form-group">
															<div>
																<input type="hidden" name="idAsignarUsu" id="idAsignarUsu">
																<input type="hidden" name="idAsignarComp" id="idAsignarComp">
																<input type="hidden" name="idcortesia" id="idcortesia">
																<button type="submit" name="btnAsignar" id="btnAsignar" class="btn btn-primary waves-effect waves-light" aria-hidden="true">
																	Asignar
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
									
									<div class="modal fade bs-example-modal-lg" id="modalModHtl" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Asignación</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
												</div>
												<div class="modal-body">
													<form id="formModAsignarHtl">
														<div class="form-group row">
															<label for="devNomb" class="col-sm-3 control-label">Nombre</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devNomb" id="devNomb" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAPater" class="col-sm-3 control-label">Apellido Paterno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAPater" id="devAPater" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAMater" class="col-sm-3 control-label">Apellido Materno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAMater" id="devAMater" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devNombComp" class="col-sm-3 control-label">Nombre</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devNombComp" id="devNombComp" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAPCompa" class="col-sm-3 control-label">Apellido Paterno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAPCompa" id="devAPCompa" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devAMCompa" class="col-sm-3 control-label">Apellido Materno</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devAMCompa" id="devAMCompa" disabled>
															</div>
														</div>
														<div class="form-group row">
															<label for="devHoteles" class="col-sm-3 control-label">Hotel</label>
															<div class="col-sm-9">
																<select class="form-control" name="devHoteles" id="devHoteles" required>
																</select>
															</div>
														</div>
														<div class="form-group row">
															<label for="devHabitacion" class="col-sm-3 control-label">Habitación</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" name="devHabitacion" id="devHabitacion" required>
															</div>
														</div>
														<div class="form-group">
															<div>
																<input type="hidden" name="idModAsignarUsu" id="idModAsignarUsu">
																<input type="hidden" name="idModAsignarComp" id="idModAsignarComp">
																<input type="hidden" name="idcortesia" id="idModcortesia">
																<button type="submit" name="btnModAsignar" id="btnModAsignar" class="btn btn-primary waves-effect waves-light" aria-hidden="true">
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
									</div><!--end-modal-->
									
								</div>
							</div>
						</div><!--end card-body-->
					</div>
				</div>
				<div class="row tab-pane" id="transporte" role="tabpanel" aria-labelledby="transporte-tab">
					<div class="card" id="tab_transporte">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<ul class="nav nav-tabs" id="myTab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="listTransporte-tab" data-toggle="tab" date-target="#listTransporte" href="#listTransporte" role="tab" aria-controls="listTransporte" aria-selected="true">
												<span class="d-block d-sm-none"><i class="mdi mdi-bus-clock"></i></span>
												<span class="d-none d-sm-block">En Espera</span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="modTransporte-tab" data-toggle="tab" data-target="#modTransporte" href="#modTransporte" role="tab" aria-controls="modTransporte" aria-selected="false">
												<span class="d-block d-sm-none"><i class="mdi mdi-check-bold"></i></span>
												<span class="d-none d-sm-block">Concluidos</span>
											</a>
										</li>

										<li class="nav-item">
											<a class="nav-link" id="AdminTrans-tab" data-toggle="tab" data-target="#AdminTrans" href="#AdminTrans" role="tab" aria-controls="AdminTrans" aria-selected="false">
												<span class="d-block d-sm-none"><i class="mdi mdi-check-bold"></i></span>
												<span class="d-none d-sm-block">Administrar Transporte</span>
											</a>
										</li>
									</ul>
									
									<div class="tab-content bg-light">
										<div class="row tab-pane show active" id="listTransporte" role="tabpanel" aria-labelledby="listTransporte-tab">
											<div class="card">
												<div class="card-body">
													<div class="table-responsive text-center">
													<h4 class="m-b-30 m-t-0">Lista en espera de transporte</h4>
														<table id="datatable-transporte" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
															<thead>
																<tr>
																	<th>Nombre</th>
																	<th>Apellido Paterno</th>
																	<th>Apellido Materno</th>
																	<th>    </th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										<div class="row tab-pane fade" id="modTransporte" role="tabpanel" aria-labelledby="modTransporte-tab">
											<div class="card">
												<div class="card-body">
													<div class="table-responsive text-center">
													<h4 class="m-b-30 m-t-0">Lista final de transporte</h4>
														<table id="datatable-modTransporte" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
															<thead>
																<tr>
																	<th>Nombre</th>
																	<th>Apellido Paterno</th>
																	<th>Apellido Materno</th>
																	<th>Transporte</th>
																	<th>Número de Asiento</th>
																	<th></th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>	
												</div>
											</div>
										</div>

										<div class="row tab-pane fade" id="AdminTrans" role="tabpanel" aria-labelledby="AdminTrans-tab">
											
											<div class="card">
												<div class="card-body">
													<div class="table-responsive text-center">
														<div class="row">
															<div class="col-md-6">
																<h4 class="m-b-30 m-t-0">Lista transportes disponibles</h4>
															</div>
															<div class="col-md-6 text-right">
																<button data-toggle="modal" data-target="#modalTransporte" href="#modalTransporte" class = "btn btn-primary">Añadir Transporte</button>
															</div>
														</div>
														<table id="datatable-AdminTrans" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
															<thead>
																<tr>
																	<th>Nombre</th>
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
								</div>
							</div>
						</div><!--end card-body-->
					</div>
				</div>
				<div class="row tab-pane" id="alimentos" role="tabpanel" aria-labelledby="alimentos-tab">
					<div class="card">
						<div class="card" id="tab_alimentos">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12">
										<ul class="nav nav-tabs" id="myTab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" id="listaA-tab" data-toggle="tab" date-target="#listaA" href="#listaA" role="tab" aria-controls="listaA" aria-selected="true">
													<span class="d-block d-sm-none"><i class="ion ion-ios-restaurant"></i></span>
													<span class="d-none d-sm-block">Lista General</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="listaUso-tab" data-toggle="tab" data-target="#listaUso" href="#listaUso" role="tab" aria-controls="listaUso" aria-selected="false">
													<span class="d-block d-sm-none"><i class="mdi mdi-check-bold"></i></span>
													<span class="d-none d-sm-block">Lista de Uso</span>
												</a>
											</li>
										</ul>

										<div class="tab-content bg-light">
											<div class="row tab-pane show active" id="listaA" role="tabpanel" aria-labelledby="listA-tab">
												<div class="container col-sm-12 col-lg-12 col-md-12">
													<div class="card">
														<div class="card-body">
															<div class="table-responsive text-center">
															<h4 class="m-b-30 m-t-0">Lista general de comida y cena</h4>
																<table id="datatable-alimentos" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
																	<thead>
																		<tr>
																			<th>Nombre</th>
																			<th>Apellido Paterno</th>
																			<th>Apellido Materno</th>
																			<th>Comida</th>
																			<th>Cena</th>
																			<th>    </th>
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
											<div class="row tab-pane fade" id="listaUso" role="tabpanel" aria-labelledby="listaUso-tab">
												<div class="container col-sm-12 col-lg-12 col-md-12">
													<div class="card">
														<div class="card-body">
															<div class="table-responsive text-center">
															<h4 class="m-b-30 m-t-0">Lista de canjeo de comida y cena</h4>
																<table id="datatable-canjeoAlim" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
																	<thead>
																		<tr>
																			<th>Nombre</th>
																			<th>Apellido Paterno</th>
																			<th>Apellido Materno</th>
																			<th>Comida</th>
																			<th>Cena</th>
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
										</div><!--end-tab-content--><!--end-modal-->
									</div>
								</div>
							</div><!--end card-body-->
						</div>
					</div>
				</div>
				<div class="row tab-pane" id="listGeneral" role="tabpanel" aria-labelledby="listGeneral-tab">
					<div class="card" id="tab_listGeneral">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12">
									<ul class="nav nav-tabs" id="myTab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="listaG-tab" data-toggle="tab" date-target="#listaG" href="#listaG" role="tab" aria-controls="listaG" aria-selected="true">
												<span class="d-block d-sm-none"><i class="ion ion-md-list-box"></i></span>
												<span class="d-none d-sm-block">Lista General</span>
											</a>
										</li>
										<!-- <li class="nav-item">
											<a class="nav-link" id="listaFinal-tab" data-toggle="tab" data-target="#listaFinal" href="#listaFinal" role="tab" aria-controls="listaFinal" aria-selected="false">
												<span class="d-block d-sm-none"><i class="fas fa-exchange-alt"></i></span>
												<span class="d-none d-sm-block">Lista Final</span>
											</a>
										</li> -->
									</ul>

									<div class="tab-content bg-light">
										<div class="row tab-pane show active" id="listaG" role="tabpanel" aria-labelledby="listaG-tab">
											<div class="container col-sm-12 col-lg-12 col-md-12">
												<div class="card">
													<div class="card-body">
														<div class="table-responsive text-center">
														<h4 class="m-b-30 m-t-0">Lista General de asistentes</h4>
															<table id="datatable-listaG" class="table table-striped table-bordered nowrap"  style="border-collapse: collapse; width: 100%;">
																<thead>
																	<tr>
																		<th>Nombre</th>
																		<th>Solicita Hotel</th>
																		<th>Hotel Asigando</th>
																		<th>Habitación</th>
																		<th>Solicita Transporte</th>
																		<th>Transporte Asignado</th>
																		<th>Número de asiento</th>
																		<th>Comida</th>
																		<th>Cena</th>
																		<th></th>
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="card d-none">
													<div class="card-body">
														<div class="container col-sm-12 col-lg-12 col-md-12">
															<h4 class="m-b-30 m-t-0 text-center">Crear compañeros aleatoriamente</h4>
															<div class="alert alert-danger" role="alert">Únicamente utilizar cuando el tiempo de espera haya sido agotado.</div>
															<div class="col-sm-12 col-lg-12 col-md-12">
																<button type="button" name="btnAleatorio" id="btnAleatorio" class="btn btn-primary waves-effect waves-light" aria-hidden="true">
																	Formar Compañeros
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- <div class="row tab-pane fade" id="listaFinal" role="tabpanel" aria-labelledby="listaFinal-tab">
											<div class="container col-sm-12 col-lg-12 col-md-12">
												<div class="card">
													<div class="card-body">
														<div class="table-responsive text-center">
														<h4 class="m-b-30 m-t-0">Lista final de asistentes</h4>
															<table id="datatable-listaFinal" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
																<thead>
																	<tr>
																		<th>Nombre</th>
																		<th>Apellido Paterno</th>
																		<th>Apellido Materno</th>
																		<th>Hotel</th>
																		<th>Habitación</th>
																		<th>Transporte</th>
																		<th>Número de asiento</th>
																		<th>Comida</th>
																		<th>Cena</th>
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>
														</div>	
													</div>
												</div>
											</div>
										</div> -->
									</div><!--end-tab-content-->
								</div>
							</div>
						</div><!--end card-body-->
					</div>
				</div>
			</div>

			<div class="modal fade" id="ModalCortesias" tabindex="-1" role="dialog" aria-labelledby="ModalCortesias" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Cortesias</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<form id="formularioCortesias">
							<div class="modal-body">
								<div class="row form-group">
									<input class="d-none"type="text" name="case" id="casecortesias">
									<input class="d-none"type="text" id="idcortesia">
									<div class="col-md-4">
										<label for="type_i">Tipo:</label>
										<select class = "form-control"name = "typecort_i" id="type_i">
											<option value="" selected disabled required>Seleccione el tipo de cortesia</option>
											<option value="0">Hospedaje</option>
											<option value="1">Transporte</option>
											<option value="2">Alimentos</option>
										</select>
									</div>
									<div class="col-md-4">
										<label for="nombre_i">Nombre:</label>
										<input class = "form-control" type="text" name="nombre_i" id="nombre_i" placeholder="Ingrese el nombre de la cortesía" required>
									</div>
									<div class="col-md-4">
										<label for="informacion_i">Informacion:</label>
										<input class = "form-control" type="text" name="informacion_i" id="informacion_i" placeholder="Agregue la descripción de la cortesía" required>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-6">
										<label for="inicio_i">Inicio:</label>
										<input class = "form-control" type="date" name="inicio_i" id="inicio_i" required>
									</div>
									<div class="col-md-6">
										<label for="fin_i">Final:</label>
										<input class = "form-control" type="date" name="fin_i" id="fin_i" required>
									</div>
								</div>
							</div>
							<input type="hidden" name="idcortesia" id="idcortesia_i">
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="submit" class="btn btn-primary">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
															
			<div class="modal fade bs-example-modal-lg" id="modalModificarAlimentos" tabindex="-1" role="dialog" aria-labelledy="modalModificarAlimentos" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Alimentos</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						</div>
						<div class="modal-body">
							<form id="formModificarAlimentos">
								<div class="form-group row">
									<label for="devNombre" class="col-sm-3 control-label">Nombre</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="devNombre" id="devNombre" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="devAPP" class="col-sm-3 control-label">Apellido Paterno</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="devAPP" id="devAPP" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="devComida" class="col-sm-3 control-label">Comida</label>
									<div class="col-sm-9">
										<select class="form-control" name="devComida" id="devComida" required>
											<option selected="true" disabled="disabled">Seleccione</option>
											<option value="-1">No</option>
											<option value="1">Si</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="devCena" class="col-sm-3 control-label">Cena</label>
									<div class="col-sm-9">
										<select class="form-control" name="devCena" id="devCena" required>
											<option selected="true" disabled="disabled">Seleccione</option>
											<option value="-1">No</option>
											<option value="1">Si</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div>
										<input type="hidden" name="idModificarAlimentos" id="idModificarAlimentos">
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
			<div class="modal fade" id="modalTransporte" tabindex="-1" role="dialog" aria-labelledby="modalTransporte" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Transporte</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<form id ="form-update-transporte">
							<div class="modal-body">
								<div class="row d-none form-group">
									<input type="text" name="case" id="case" value = "add">
								</div>
								<div class="row d-none form-group">
									<div class="col-md-12">
										<input type="text" id="idtransporte">
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-12">
										<label for="nombreTransporte">Nombre del Transporte</label>
										<input class ="form-control" type="text" name = "nombreTransporte" id = "nombreTransporte" placeholder ="Agregue el nombre del transporte">
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="submit" class="btn btn-primary">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div><!--end-tab-content-->
			<div class="modal fade bs-example-modal-lg" id="modalAsigTransporte" tabindex="-1" role="dialog" aria-labelledy="modalAsigTransporte" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Asignar Transporte</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						</div>
						<div class="modal-body">
							<form id="formAsigTransporte">
								<div class="form-group row">
									<label for="nameT" class="col-sm-3 control-label">Nombre</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="nameT" id="nameT" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="aPater" class="col-sm-3 control-label">Apellido Paterno</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="aPater" id="aPater" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="aMater" class="col-sm-3 control-label">Apellido Materno</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="aMater" id="aMater" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="transporteAsign" class="col-sm-3 control-label">Transporte</label>
									<div class="col-sm-9">
										<select class="form-control" name="transporteAsign" id="transporteAsign" required>
											<option selected="true" disabled="disabled">Seleccione</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="asiento" class="col-sm-3 control-label">Número de asiento</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="asiento" id="asiento" required>
									</div>
								</div>
								<div class="form-group">
									<div>
										<input type="hidden" name="idAsignarUsuT" id="idAsignarUsuT">
										<input type="hidden" name="idcortesia" id="idcortesiaT">
										<button type="submit" name="btnAsignarT" id="btnAsignarT" class="btn btn-primary waves-effect waves-light" aria-hidden="true">
											Asignar
										</button>
										<button type="button" id="ocultar" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal" aria-hidden="true">
											Cancelar
										</button>
									</div>
								</div>
							</form>
						</div><!--end-body-->
					</div>
				</div>
			</div>	
			<div class="modal fade bs-example-modal-lg" id="modalModTransporte" tabindex="-1" role="dialog" aria-labelledy="modalModTransporte" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Transporte</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						</div>
						<div class="modal-body">
							<form id="formModTransporte">
								<div class="form-group row">
									<label for="nameMod" class="col-sm-3 control-label">Nombre</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="nameMod" id="nameMod" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="aPaterMod" class="col-sm-3 control-label">Apellido Paterno</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="aPaterMod" id="aPaterMod" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="aMaterMod" class="col-sm-3 control-label">Apellido Materno</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="aMaterMod" id="aMaterMod" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label for="transporteMod" class="col-sm-3 control-label">Transporte</label>
									<div class="col-sm-9">
										<select class="form-control" name="transporteMod" id="transporteMod" required>
											<option selected="true" disabled>Seleccione</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="asientoMod" class="col-sm-3 control-label">Número de asiento</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="asientoMod" id="asientoMod" required>
									</div>
								</div>
								<div class="form-group">
									<div>
										<input type="hidden" name="idModTranspor" id="idModTranspor">
										<input type="hidden" name="idcortesia" id="idModcortesiaTranspors">
										<button type="submit" name="btnModTransporte" id="btnModTransporte" class="btn btn-primary waves-effect waves-light" aria-hidden="true">
											Modificar
										</button>
										<button type="button" id="ocultar" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal" aria-hidden="true">
											Cancelar
										</button>
									</div>
								</div>
							</form>
						</div><!--end-body-->
					</div>
				</div>
			</div>

			<div class="modal fade" id="modelAsignarCortesia" tabindex="-1" role="dialog" aria-labelledby="modelAsignarCortesia" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Asignar Cortesia: <b id="NombreCortesia"></b></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form id="formularioAsignacionCortesias">
							<input class="d-none" type="text" name="idcortesia" id="idcortesias">
							<div class="modal-body">
								<div class="container-fluid">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="carrerasCortesias">Carrera:</label>
												<select class ="form-control" id="carrerasCortesias">
													<option value="" selected disabled>Seleccione una Carrera</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="generacionesCortesias">Generación:</label>
												<select class ="form-control" id="generacionesCortesias">
													<option value="" selected disabled>Seleccione una Generación</option>
												</select>
											</div>
										</div>
									</div>
									<div class="table-responsive">
										<table id="datatable-Alumnos-cortesias" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
											<thead>
												<tr>
													<th>Nombre</th>
													<th>Seleccionar Alumnos Especificos</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" onClick = "AsignarCortesiaCasos('Alumnos')" id="AlumnosEspecificos" class = "btn btn-info d-none">Asignar a Alumnos Seleccionados</button>
								<button type="button" class = "btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button id = "AsignarCortesias" type="submit" class = "btn btn-primary" disabled>Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

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
	<script src="../assets/js/hoteles/hotel.js"></script>

	<script src="../assets/pages/sweet-alert.init.js"></script>

	<!--Responsive examples-->
	<script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
	<!--error<script src="../assets/plugins/datatables/dataTables.responsive.js"></script>-->
	<script src="../assets/plugins/datatables/responsive.bootstrap.min.js"></script>

	<!--Datatable init js-->
	<script src="../assets/pages/datatables.init.js"></script>
	<!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>-->

	<script src="../assets/js/template/app.js"></script>


</body>

</html>
