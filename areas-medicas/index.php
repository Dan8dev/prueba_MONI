<?php 

include 'partials/header.php';
?>

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
							<?php if($_SESSION["usuario"]['estatus_acceso'] == 1){
								?>
								<li class="nav-item">
									<a class="nav-link active" id="tutores-tab" data-toggle="tab" href="#tutores" role="tab" aria-controls="tutores" aria-selected="true">
									<span class="d-block d-sm-none"><i class="fas fa-chalkboard-teacher"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-chalkboard-teacher"></i> Tutores-Médico de Calidad</span>
									</a>
								</li>
								<li class="nav-item"><!--generar directorio-tap-->
									<a class="nav-link" id="proce-tab" data-toggle="tab" href="#proce" role="tab" aria-controls="proce" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-book-medical"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-book-medical"></i> Procedimientos</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="alumnos-tab" data-toggle="tab" href="#directorio" role="tab" aria-controls="alumnos" aria-selected="true">
									<span class="d-block d-sm-none"><i class="fas fa-user-graduate"></i></span>
										<span class="d-none d-sm-block"><i class="fa fa-user-graduate"></i> Alumnos</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="cirugia-tab" data-toggle="tab" href="#cirugia" role="tab" aria-controls="cirugia" aria-selected="true">
									<span class="d-block d-sm-none"><i class="fas fa-book-medical"></i></span>
										<span class="d-none d-sm-block"><i class="fa fa-book-medical"></i> Cirugía</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="revisones-tab" data-toggle="tab" href="#revisones" role="tab" aria-controls="revisones" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-edit"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-edit"></i> Revisiones</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="procoloTesis-tab" data-toggle="tab" href="#procoloTesis" role="tab" aria-controls="procoloTesis" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-first-aid"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-first-aid"></i> Procolo de tesis</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-dollar-sign"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-dollar-sign"></i> Pagos</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="formularios-tab" data-toggle="tab" href="#formularios" role="tab" aria-controls="formularios" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-file-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-file-alt"></i> Formularios</span>
									</a>
								</li>

								<?php
							} ?>
							</ul>
							<!--TABLA GENERAL DE ALUMNOS-->
							<div class="tab-content bg-light">
								<div class="tab-pane fade active show" id="tutores" role="tabpanel" aria-labelledby="tutores-tab">
									<h2>Tutores / Médicos de Calidad</h2>
									<div class="table-responsive">
										<div class="hidden btnCarrer">
											<h5 id="LabelCarr">Asignar / Quitar Carreras</h5>
											<form id="addCar" class="">
												<input type="hidden" name="action" value="assignTut">
												<input type="hidden" name="typeOperation" id="typeOperationT">
												<ul id="listCarrer" class="list-unstyled">
													<li><input class="seleCarrer" type="checkbox" name="carrer[]" value="14"><span>Maestría en Medicina Estética y Longevidad</span></li>
													<li><input class="seleCarrer" type="checkbox" name="carrer[]" value="19"><span>Maestría en Cirugía Estética</span></li>
												</ul>
												<button class="btn btn-primary mt-2 float-right" id="asg" onclick="UpdateTuCar(1)">Asignar</button>
												<button class="btn btn-secondary mt-2 mx-2 float-right" id="remv" onclick="UpdateTuCar(0)">Quitar</button>
											</form>
										</div>
										<div class="card-body">
											<table class="table" id="table_tutores">
												<thead>
													<th>Nombre</th>
													<th>Tipo de Rol</th>
													<th>Teléfonos</th>
													<th>Correo</th>
													<th>CV</th>
													<th>Asignar / Remover Bitácoras</th>
													<th>Carrera para Bitácoras</th>
													<th>Protocolo de tesis</th>
													<th>Opciones</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="proce" role="tabpanel" aria-labelledby="proce-tab">
									<h2>Procedimientos</h2>
									<div class="table-responsive">
										<div class="hidden btnsProto">
											<h5>Asignar / Remover a Protocolo de Tesis</h5>
											<form id="protoTes">
												<div class="form-group">
													<select name="idCar" id="idCarrer" class="form-control" required>
														<option value="">Selecciona una carrera</option>
														<option value="14">Maestría en Medicina Estética y Longevidad</option>
														<option value="19">Maestría en Cirugía Estética</option>
													</select>
												</div>
												<div class="form-group hidden" id="idGen">
													<select name="idGen" id="selIdGen" class="form-control" required>
													</select>
												</div>
												<input type="hidden" name="action" value="assignPro">
												<input type="hidden" name="typeOperation" id="typeOperation">
												<button class="btn btn-secondary" id="assign" onclick="UpdateProtoTesis(1)">Asignar</button>
											<button class="btn btn-primary" id="delete" onclick="UpdateProtoTesis(0)">Remover</button>
											</form>
											
										</div>
										<div class="text-right">
										<button class="btn btn-primary" id="clickModal" data-toggle="modal" data-target="#modalAgregaProce" style="color:white;">
                                                Agregar Procedimiento
                                            </button>
										</div>
										<div class="card-body">
											<table class="table" id="tableProcedimientos" style="width: 100%;">
												<thead>
													<th></th>
													<th>Nombre</th>
													<th>Costo</th>
													<th>Carrera(s)</th>
													<th>Generaciones</th>
													<th>Archivo</th>
													<th>Opciones</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="directorio" role="tabpanel" aria-labelledby="directorio-tab">
									<h2>Directorio</h2>
									<div class="table-responsive">
										<div class="card-body">
											<table class="table" id="table_directorio">
												<thead>
													<th>Nombre</th>
													<th>Teléfono</th>
													<th>Correo</th>
													<th>Dirección</th>
													<th>Carrera</th>
													<th>Generación</th>
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
								<div class="tab-pane fade" id="cirugia" role="tabpanel" aria-labelledby="cirugia-tab">
									<div class="row">
										<div class="col-md-6"><h2>Cirugías</h2></div>
										<div class="col-md-6 text-right"><buton class = "btn btn-primary" data-toggle="modal" data-target="#ModalControlCirugia">Crear Nueva Cirugia</button></div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table" id="table_cirugias" style="width: 100%;">
												<thead>
													<th>Nombre Alumno</th>
													<th>Procedimiento</th>
													<th>Paciente</th>
													<th>Sitio</th>
													<th>Fecha</th>
													<th>Tutor</th>
													<th>Opción</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="revisones" role="tabpanel" aria-labelledby="revisones-tab">
									<h2>Revisiones</h2>
									<div class="table-responsive">
										<div class="card-body">
											<table class="table" id="table_revisones" style="width: 100%;">
												<thead>
													<th>Nombre Alumno</th>
													<th>Procedimiento</th>
													<th>Paciente</th>
													<th>Sitio</th>
													<th>Fecha</th>
													<th>Tutor</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="procoloTesis" role="tabpanel" aria-labelledby="procoloTesis-tab">
									<h2>Protocolo de Tesis</h2>
									<div class="table-responsive">
										<div class="card-body">
											<table class="table" id="table_procoloTesis" style="width: 100%;">
												<thead>
													<th>Nombre Alumno</th>
													<th>Tutor</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
									<h2>Pagos</h2>
									<div class="table-responsive">
										<div class="card-body">
											<h5>Buscar por fechas</h5>
											<div class="row form-group">
												<div class="col-md-6">
													<input class= "form-control" type="date" id="FechaInicialPagos">
												</div>
												<div class="col-md-6">
													<input class= "form-control" type="date" id="FechaFinalPagos">
												</div>
											</div>
											<table class="table" id="table_pagos" style="width: 100%;">
												<thead>
													<th>Nombre Alumno</th>
													<th>Procedimiento</th>
													<th>Paciente</th>
													<th>Fecha</th>
													<th>Tutor</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="formularios" role="tabpanel" aria-labelledby="formularios-tab">
									<h2>Formularios</h2>

									<table class="tableForm" id="tableFormularios" style="width: 100%;">
										<thead>
											<th>Nombre</th>
											<th>Descripcion</th>
											<th>Opciones</th>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>	
				</div><!--end row-->
			</div><!--end card-body-->
		</div><!--end cart-->
	</div><!-- end container-fluid -->	
	
	<!-- Modal -->
	
	<div class="modal fade" id="modalFormularios" tabindex="-1" role="dialog" aria-labelledby="modalFormularios" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id = "TituloFormulario">Nombre del formulario: </h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id = "form_dim">
					<div id = "form_dim_container">
					</div>
					<div class="modal-body">
						<div class="container-fluid" id="Content_forms">
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
	
	<div class="modal fade" id="ModalControlCirugia" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Cirugias </h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id = FormCirugias>
					<div class="modal-body">
						<div class="container-fluid">
							<input class = "d-none" type="text" name="idAlumno" id="idAlumno">
							<div class="row form-group">
								<div class="col-md-6">

									<label for="nombreAlumno">Nombre del Alumno:</label>
									<input class = "form-control" type="text" name="nombreAlumno" id="nombreAlumno">
								</div>
								<div class="col-md-6">
									<label for="procedimientoAsignado">Procedimiento:</label>
									<select class = "form-control" name="procedimientoAsignado" id="">
										<option value="" selected disabled>Seleccciona un Procedimiento</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-6">
									<label for="pacienteAsignado">Paciente:</label>
									<input class = "form-control" type="text" name="pacienteAsignado" id="pacienteAsignado">
								</div>
								<div class="col-md-6">
									<label for="sitioAsignado">Sitio:</label>
									<select class = "form-control" name="sitioAsignado" id="sitioAsignado">
										<option value="" selected disabled>Seleccione un sitio</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-6">
									<label for="fechaHoraAsignada">Fecha de Realización:</label>
									<input class = "form-control" type="datetime" name="fechaHoraAsignada" id="fechaHoraAsignada">
								</div>
								<div class="col-md-6">
									<label for="tutorAsigando">Tutor:</label>
									<select class = "form-control" name= "tutorAsigando" id="tutorAsigando">
										<option value="" selected disabled> Selecciona un Tutor</option>
									</select>
								</div>
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
	
	<div id="modalAssignPT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content pb-4">

				<div class="modal-header">
					<h3 class="modal-title m-0" id="CustomLabel"></h3>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				</div>
				<form id="formEditContent" class="">
					<div class="form-group">
						<label for="">Titulo</label>
						<input type="text" name="title" id="titleform" class="form-control" placeholder="Nombre(s)" required>
					</div>
					
					<div class="form-group">
						<label for="">Descripción</label>
						<textarea class="form-control" name="description" id="desc" cols="30" rows="10" placeholder="Agrega una descripción"></textarea>
					</div>
					<div class="form-group">
						<div class="imgpreviewModal" style="background-image:url('../assets/images/default-1.png');"></div>
						<p class="mb-0">Por favor usa .jpg o .png sin fondo.</p>
					</div>
					<div class="form-group">
						<div class="imgpreviewModal" style="background-image:url('../assets/images/default-1.png');"></div>
						<input type="file" class="hidden" id="imgpreviewModal" accept=".png, .jpg, .jpeg">
						<button type="button" class="btn btn-primary" id="">Seleccionar una imagen</button>
						<!--<button type="button" class="btn btn-default">Quitar imagen de portada</button>-->
					</div>
					<button class="btn btn-primary mt-2 float-right">Guardar</button>
				</form>
			</div><!-- /.modal-content -->
		</div>
	</div>
	<div id="modalUploadFiles" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content pb-4">
				<div class="modal-header">
					<h3 class="modal-title m-0" id="CustomLabel"></h3>
					<button type="button" class="close closeUpcv" data-dismiss="modal" aria-hidden="true">x</button>
				</div>
				<form id="formUpCv" class="">
					<input type="hidden" name="typeFile" id="typeFiles">
					<div class="form-group mt-3">
						<div class="hidden previewModal"><img width="15%" src="../assets/icons/files.png"><span id="withoutPreview"></span></div>
						<input type="file" class="hidden openFile" name="pdf[]" id="fileSaves" accept=".doc,.docx,application/msword,.pptx,.pdf">
						<button type="button" class="btn btn-primary" id="openFile">Seleccionar un archivo</button>
						<!--<button type="button" class="btn btn-default">Quitar imagen de portada</button>-->
					</div>
					<div class="form-group" id="divDesc">
						<label for="descp">Descripción</label>
						<textarea class="style-textarea form-control" name="descp" id="descp" placeholder="Ingresa una breve descripción"></textarea>
					</div>
					<div class="oldsFiles">
						<input type="hidden" id="oldfiles">
						<input type="hidden" id="olddes">
					</div>
					<button type="button" class="btn btn-primary mt-2 float-right" id="saveFils">Guardar</button>
				</form>
			</div><!-- /.modal-content -->
		</div>
	</div>
	<div id="modalAgregatutores" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content pb-4">

                            <div class="modal-header">
                                <h3 class="modal-title m-0" id="CustomLabelEdit"></h3>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            <form id="addTu" class="">
								<div class="form-group">
									<label for="">Nombre</label>
									<input type="text" name="names" id="names" class="form-control" placeholder="Nombre(s)" required>
								</div>
								<div class="form-group">
									<label for="">Apellido paterno</label>
									<input name="apa" id="apa" type="text" class="form-control" placeholder="Apellido paterno" required>
								</div>
								<div class="form-group">
									<label for="">Apellido materno</label>
									<input name="ama" id="ama" type="text" class="form-control" placeholder="Apellido materno" required>
								</div>
								<div class="form-group">
									<label for="">Email</label>
									<input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
								</div>
								<div class="form-group">
									<label for="">Teléfono particular</label>
									<input type="text" name="tel" id="tel" class="form-control" placeholder="Teléfono particular" required>
								</div>
								<div class="form-group">
									<label for="">Teléfono celular</label>
									<input type="text" name="cel" id="cel" class="form-control" placeholder="Teléfono celular">
								</div>
								<div class="form-group">
									<label for="">Teléfono de oficina</label>
									<input type="text" name="telt" id="telt" class="form-control" placeholder="Teléfono de oficina">
								</div>
								<div class="form-group">
									<label for="">Teléfono de recados</label>
									<input type="text" name="telr" id="telr" class="form-control" placeholder="Teléfono de recados">
								</div>
								<div class="form-group">
									<label for="">Género</label>
									<select name="gen" id="gen" class="form-control">
										<option value="">Selecciona un género</option>
										<option value="M">Femenino</option>
										<option value="H">Masculino</option>
									</select>
								</div>
								<div class="form-group">
									<label for="">Descripción</label>
									<textarea class="style-textarea form-control" name="descp" id="descp" placeholder="Ingresa una breve descripción"></textarea>
								</div>
								<div class="form-group"><label for="">Selecciona el role</label>
									<select name="roles" id="roles" id="" class="form-control" required>
										<option value="" selected>selecciona un role</option>
										<option value="1">Docente</option>
										<option value="2">Tutor</option>
										<option value="3">Médico de calidad</option>
										<option value="4">Tutor y Médico de calidad</option>
									</select>
								</div>
								<button class="btn btn-primary mt-2 float-right">Guardar</button>
							</form>
                        </div><!-- /.modal-content -->
                    </div>
    </div>
	<div id="modalAgregaProce" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content pb-4">

                            <div class="modal-header">
                                <h3 class="modal-title m-0" id="CustomLabelEditProce"></h3>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            <form id="addProc" class="">
								<div class="form-group">
									<label for="">Nombre procedimiento</label>
									<input type="text" name="name" id="namesP" class="form-control" placeholder="Nombre del procedimiento" required>
								</div>
								<div class="form-group">
									<label for="">Costo</label>
									<input type="text" name="costo" id="costo" class="form-control" placeholder="ingresa el monto" required pattern="^[0-9]+">
								</div>
								<button class="btn btn-primary mt-2 float-right">Guardar</button>
							</form>
                        </div><!-- /.modal-content -->
                    </div>
    </div>
	<div id="modalListProce" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content pb-4">

                            <div class="modal-header">
                                <h3 class="modal-title m-0" id="CustomLabelListP"></h3>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            
							
							<form id="addProc" class="">
							<div class="tab-content bg-light">
								<div class="tab-pane fade active show" id="tutores" role="tabpanel" aria-labelledby="tutores-tab">
									<h2>Bitácora</h2>
									<div class="table-responsive">
										<div class="card-body">
											<table class="table" id="table_procedimientos_r" style="width: 100%;">
												<thead>
													<th>Procedimiento</th>
													<th>Tutor</th>
													<th>Paciente</th>
													<th>Comentario</th>
													<th>Sitio</th>
													<th>Fecha</th>
													<th>#</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>	
								<!-- <button class="btn btn-primary mt-2 float-right">Guardar</button> -->
							</form>
                        </div><!-- /.modal-content -->
                    </div>
    </div>
	<?php include '../partials/modalEditSudents.php'; ?>
	<!-- Modal modificar plan estudio -->
	
<?php 
include 'partials/footer.php';
?>
