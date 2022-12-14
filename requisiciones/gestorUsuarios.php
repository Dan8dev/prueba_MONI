<?php
session_start();
if( !isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 34 ) ){
    header("Location: ../index.php");
    die();
}
    $usuario = $_SESSION["usuario"];
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
                                            <th>Correo electr??nico</th>
											<th>Rol</th>
											<th>Departamento</th>
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
	<div class="toast-success">Actualizaci??n de estatus correcta.</div>
    <!--MODAL AGREGAR-->
    <!-- sample modal content -->
    <div id="modalAgregausers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content pb-4">

				<div class="modal-header">
					<h3 class="modal-title m-0" id="CustomLabel"></h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="addU" class="">
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
						<label for="">Selecciona un departamento</label>
						<select name="dptouser" id="dptouser" class="form-control" required>
						</select>
					</div>
					<div class="form-group"><label for="">Selecciona el role</label>
						<select name="roles" id="roles" id="" class="form-control" required>
							<option value="" selected>selecciona un role</option>
							<option value="1">Administrador</option>
							<option value="2">Jefe de departamento</option>
							<option value="3">??rea contable</option>
							<option value="4">Gestor de transferencias</option>
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
					?? 2021 UDC-IESM-TSU-CONACON-TI
				</div>
			</div>
		</div>
	</footer>
	<!-- End Footer -->

	
	
	
<?php
	include 'partials/footer.php';
  $str = json_encode($usuario);
  echo("<script> usrInfo = JSON.parse('{$str}');</script>");
?>
</body>

</html>
