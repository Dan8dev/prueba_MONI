		<!-- MENU Start -->
        <div class="navbar-custom">
					<div class="container-fluid">
						<div id="navigation">
							<!-- Navigation Menu-->
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

								<?php if( $_SESSION["usuario"]['idTipo_Persona'] == 35){	
									

								?>
								<?php if($_SESSION["usuario"]['estatus_acceso'] == 1){	
									

									?>
									
								<?php } //If usuario-control-escolar?>
								<!-- <li class="has-submenu">
									<a href="../admin-webex/"><i class="far fa-dot-circle"></i>Gestor Webex</a>
								</li>-->
								<?php if($_SESSION["usuario"]['estatus_acceso'] != 3){	
									
								?>
								<li class="has-submenu">
									<a href="gestorUsuarios.php"><i class="fas fa-user"></i>Gestor Usuarios</a>
								</li>
								<?php } }//If usuario-control-escolar?>

							</ul>
							<!-- End navigation menu -->
						</div>
						<!-- end #navigation -->
					</div>
					<!-- end container -->
				</div>
				<!-- end navbar-custom -->