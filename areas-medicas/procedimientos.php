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
							<?php if($_SESSION["usuario"]['estatus_acceso'] == 1){?>
								<?php $active = 'active'; 
								$activeNoAccess = '';
								?>
								<li class="nav-item">
									<a class="nav-link active" id="directorio-tab" data-toggle="tab" href="#directorio" role="tab" aria-controls="examenes" aria-selected="true">
									<span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Directorio</span>
									</a>
								</li>
								<li class="nav-item"><!--generar directorio-tap-->
									<a class="nav-link" id="listado-tab" data-toggle="tab" href="#listado" role="tab" aria-controls="directorio" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-list-ol"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-list-ol"></i> Listado</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="procoloTesis-tab" data-toggle="tab" href="#procoloTesis" role="tab" aria-controls="calificaciones" aria-selected="true">
										<span class="d-block d-sm-none"><i class="fas fa-book-medical"></i></span>
										<span class="d-none d-sm-block"><i class="fas fa-book-medical"></i> Procolo de tesis</span>
									</a>
								</li>
								<?php
							}else{ 
								$active = ''; 
								$activeNoAccess = 'active'; 	?>
								<?php } ?>
							</ul>

							<!--TABLA GENERAL DE ALUMNOS-->
							<div class="tab-content bg-light">
								<div class="tab-pane fade <?=$active?> show" id="directorio" role="tabpanel" aria-labelledby="directorio-tab">
									<h2>Directorio</h2>
									<div class="table-responsive">
										<div class="card-body">
											<table class="table" id="table_directorio_areasm">
												<thead>
													<th>Nombre del procedimiento</th>
													<th>costo</th>
													<th>Protocolo de tesis</th>
													<th>Procedimiento</th>
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
				</div><!--end row-->
			</div><!--end card-body-->
		</div><!--end cart-->
	</div><!-- end container-fluid -->	
	
	<div id="modalEditContent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
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
						<input type="file" class="hidden openFile" id="imgpreviewModal" accept=".png, .jpg, .jpeg">
						<button type="button" class="btn btn-primary" id="openFile">Seleccionar una imagen</button>
						<!--<button type="button" class="btn btn-default">Quitar imagen de portada</button>-->
					</div>
					<button class="btn btn-primary mt-2 float-right">Guardar</button>
				</form>
			</div><!-- /.modal-content -->
		</div>
	</div><!--Modal Blog -->

	<!-- Modal modificar plan estudio -->
	
<?php 
include 'partials/footer.php';
?>
