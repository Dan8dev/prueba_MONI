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
										<span class="d-none d-sm-block"><i class="fas fa-chalkboard-teacher"></i>Proveedores</span>
									</a>
								</li>

							</ul>
                            
                            <div class="tab-pane fade active show" id="users" role="tabpanel" aria-labelledby="users-tab">
                                <div class="table-responsive text-left">
                                    <div class="row justify-content-between">
                                        <div class="col">
                                            <h2>Lista de proveedores</h2>
                                        </div>
                                        <div class="col text-right">
                                            <a class="btn btn-primary" id="clickModal" data-toggle="modal" data-target="#proveedor" style="color:white;">
                                                Agregar Proveedor
                                            </a>
                                        </div>
                                    </div>
                                    <table id="datatable-tablaProveedores" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Razón Social</th>
                                            <th>Domicilio</th>
                                            <th>Correo electrónico</th>
											<th>Teléfono</th>
											<th>Banco</th>
                                            <th>Número de cuenta</th>
                                            <th>Número de clabe</th>
                                            <th>Opción</th>
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
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" id="proveedor">  
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title m-0" id="CustomLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <form id="editproveedor" class="mt-4">
                    <div class="form-group">
                        <label for="">Tipo de actividad</label>
                        <select name="activity" id="EdchangeAct" class="form-control" required>
                            <option value="" disabled selected>Selecciona tipo de actividad</option>
                            <option value="persona fisica">Persona física</option>
                            <option value="persona moral">Persona moral</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Regimen fiscal</label>
                        <select name="regimen" id="Edreg" class="form-control" required>
                            <option value="" disabled selected>Selecciona regimen</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nombre razón o social</label>
                        <input name="nrazon" type="text" id="nrazon" class="form-control" placeholder="Nombre Razon social" required>
                    </div>
                    <div class="form-group">
                        <label for="">RFC</label>
                        <input name ="rfc" type="text" id="rfc" class="form-control" placeholder="Registro federal de contribuyete" maxlength="13" required >
                    </div>
                    <div class="form-group">
                        <label for="">calle</label>
                        <input type="text" class="form-control" id="street" placeholder="Calle" name="street" required>
                    </div>
                    <div class="form-group">
                        <label for="">N° exterior</label>
                        <input type="text" class="form-control" id="numberE" placeholder="N° exterior" required pattern="^[0-9]+" maxlength="10" name="numberE">
                    </div>
                    <div class="form-group">
                        <label for="">N° interior</label>
                        <input type="text" class="form-control" id="numberI" placeholder="N° interior" required pattern="^[0-9]+" maxlength="10" name="numberI">
                    </div>
                    <div class="form-group">
                        <label for="">Colonia</label>
                        <input type="text" class="form-control" id="nbd" placeholder="Colonia" name="neighborhood" required>
                    </div>
                    <div class="form-group">
                        <label for="">Estado</label>
                        <select id ="stateE" type="text" id="stateD" class="form-control" placeholder="Estado" name="stateD"  required>
                            <option value="">Selecciona un estado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Ciudad</label>
                        <select id ="cityE" type="text" id="city" class="form-control" placeholder="Ciudad" name="city" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">C.P</label>
                        <input type="text" class="form-control" id="cp" placeholder="C.P" required pattern="^[0-9]+" maxlength="5" name="cp">
                    </div>
                    <div class="form-group">
                        <label for="">Correo electróncio</label>
                        <input type="email" class="form-control" id="email"  placeholder="Correo electrónico" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="">Teléfono</label>
                        <input type="tel" class="form-control" id="tel"  placeholder="Teléfono" name="tel" maxlength="10" onlyNum required>
                    </div>
                    <div class="form-group">
                        <label for="">Nombre del banco</label>
                        <input type="text" class="form-control" id="bank"  placeholder="Banco" name="bank" required>
                    </div>
                    <div class="form-group">
                        <label for="">N° de Cuenta</label>
                        <input type="number" class="form-control" id="acountB" placeholder="Cuenta" name="acountB" required>
                    </div>
                    <div class="form-group">
                        <label for="">N° de CLABE</label>
                        <input type="number" class="form-control" id="clabeB" placeholder="CLABE" name="clabeB" required>
                    </div>
                    <button type="submit" class="btn btn-primary my-5 float-end">Guardar cambios</button>
                </form>
                </div>
            </div>
    </div>
    <!--end-modal-->
    <!--FIN MODAL-->
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

	
	
	
<?php
	include 'partials/footer.php';
  $str = json_encode($usuario);
  echo("<script> usrInfo = JSON.parse('{$str}');</script>");
?>
</body>

</html>
