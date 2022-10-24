<!-- MENU Start -->
<div class="navbar-custom">
	<div class="container">
		<div id="navigation">
			<!-- Navigation Menu-->
			<ul class="navigation-menu">
				<li class="has-submenu">
					<a href="index.php"><i class="fas fa-home"></i> Inicio</a>
				</li>
				<li class="has-submenu">
					<a href="proveedor.php"><i class="fas fa-user-tag"></i>Gestor Proveedores</a>
				</li>

				<?php if( $_SESSION["usuario"]['idTipo_Persona'] == 34){	
				

				?>
				<?php if($_SESSION["usuario"]['estatus_acceso'] == 1){	
					

					?>
					<li class="has-submenu">
					<a href="gestorUsuarios.php"><i class="fas fa-user"></i>Gestor Usuarios</a>
					</li>
					
				<?php }} //If usuario-control-escolar?>
				
			
			</ul>
			<!-- End navigation menu -->
		</div>
		<!-- end #navigation -->
	</div>
	<!-- end container -->
</div>
<!-- end navbar-custom -->