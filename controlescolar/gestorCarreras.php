<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 31){
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

    <!-- CSS bootstrap -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- iconos fontawesom -->
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <!-- CSS general -->
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">

    <!-- DataTables (Tablas) CSS -->
    <link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	  
    <!-- Sweet Alert 2-->
    <link href="../assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">
	  <link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    
    <style type="text/css">
        .but-circle{
          padding: 1px 4px !important;
          border-radius: 20px;
          max-width: 25px;
          max-height: 25px;
          min-width: 25px;
          margin-right: 3px;
        }
        .but-circle > i{
          /*margin-left: -4px;*/
        }
        .dataTables_filter > label > input {
            width: 80% !important;
        }
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
        .modal.fade.modal-right .modal-dialog{
          /*transform: translate(125%, 0px);*/
          
          transform: translate3d(25%, -25%, 0);
        }
        .modal.show.modal-right .modal-dialog{
          transform: none;
        }
        .border.border-primary.border-asignacion{
          border-color: #AA262C;
          border-top-color: #AA262C;
          border-right-color: #AA262C;
          border-bottom-color: #AA262C;
          border-left-color: #AA262C;
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
                <a href="#" class="logo">
                  <img src="../assets/images/logo-light.png" class="logo-lg" alt="" height="26">
                  <img src="../assets/images/logo-sm.png" class="logo-sm" alt="" height="28">
                </a>
              </div>
              <!-- End Logo-->

              <div class="menu-extras topbar-custom navbar p-0">
                <ul class="list-inline d-none d-lg-block mb-0">
                </ul>

                <ul class="mb-0 nav navbar-right ml-auto list-inline">

                  <li class="list-inline-item notification-list d-none d-sm-inline-block">
                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="fas fa-expand"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                      <span class="profile-username">
                       <?php echo $usuario["persona"]["nombres"]; ?> <span class="mdi mdi-chevron-down font-15"></span>
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <!--<li><a href="javascript:void(0)" class="dropdown-item"> Profile</a></li>-->
                      <li class="dropdown-divider"></li>
                      <li><a href="../editarAccesos.php" class="dropdown-item"> Cambiar contraseña</a></li>
                      <li><a href="../log-out.php" class="dropdown-item"> Cerrar sesión</a></li>
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
         <?php include 'partials/nav.php' ?>
          <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->
      </div>
      <!-- header-bg -->

      <div class="wrapper">
        <div class="mx-4">

          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-6 col-md-12 mb-2">
                    <h4 class="page-title m-0">
                      
                    <?php 
                    $activeTab = '';
                    $activeCar = '';
                    if($_SESSION["usuario"]['estatus_acceso'] == 1) {
                      $activeTab = 'style="display:none"';
                      $active = 'tab_active';
                      $Agn = '';
                      ?>
                    <span tab-target="carreras" class="<?=$active?>" id="tabcarreras">
                        <i class="fas fa-user-graduate"></i> Carreras
                      </span> |
                      <?php }else{
                        $activeCar = 'style="display:none"';
                        $active = '';
                        $Agn = 'tab_active';
                      } ?>
                      <span tab-target="generaciones" class="<?=$Agn?>" id="tabgeneraciones">
                        <i class="fas fa-graduation-cap"></i> Generaciones
                      </span> |
                      <span tab-target="materias" id="tabmaterias">
                        <i class="fas fas fa-book"></i> Materias
                      </span> |
                      <span tab-target="planestudios" id="tabplanestudios">
                        <i class="ion ion-md-paper"></i> Plan de estudios
                      </span>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- SECCION PARA CONCENTRADO PLAN DE ESTUDIOS -->
            <div class="col-sm-12" id="tab_concentrado_planestudios" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="planestudios" role="tabpanel" aria-labelledby="planestudios-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                            <div>
                              <button id="btn-crear-planestudios" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modalPlanEstudios">
                                Crear Plan
                              </button>
                            </div>  
                            <div class="table-responsive text-center">
                              <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado de planes de estudio</h5>
                              <table id="table-planestudios" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                  <thead>
                                    <tr>
                                      <th>NOMBRE</th>
                                      <th>CARRERA</th>
                                      <th>TIPO CICLO</th>
                                      <TH>CICLOS</TH>
                                      <th>CLAVE</th>
                                      <th>TIPO RVOE</th>
                                      <th>RVOE</th>
                                      <th>FECHA CREACION</th>
                                      <th>ACCIONES</th>
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
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE MATERIAS -->
            <div class="col-sm-12" id="tab_concentrado_materias" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="materias" role="tabpanel" aria-labelledby="materias-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                            <div>
                              <button id="btn-crear-materias" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modalmaterias">
                                Crear Materia
                              </button>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="selectBuscarMaterias"><h4><strong>Selecciona la carrera</strong></h4></label>
                                <select class="form-control" id="selectBuscarMaterias" name="selectBuscarMaterias">
                                </select>
                              </div>  
                            <div class="table-responsive text-center">
                              <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado de materias</h5>
                              <table id="table-materias" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                  <thead>
                                    <tr>
                                      <th>OFICIAL</th>
                                      <!--<th>CARRERA</th>-->
                                      <th>NOMBRE</th>
                                      <th>CLAVE DE MATERIA</th>
                                      <th>TIPO</th>
                                      <th>CRÉDITOS</th>
                                      <th>FECHA CREACIÓN</th>
                                      <!--<th>CONTENIDO</th>-->
                                      <th>ACCIONES</th>
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
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE GENERACIONES -->
            <div class="col-sm-12" id="tab_concentrado_generaciones" <?=$activeTab?>>
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="generacion" role="tabpanel" aria-labelledby="generacion-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                            <div>
                              <button id="btn-crear-generacion" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modalGeneracion">
                                Crear Generación
                              </button>
                            </div>  
                            <div class="table-responsive text-center">
                              <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado generaciones</h5>
                              <table id="table-generaciones" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                  <thead>
                                    <tr>
                                      <!--<th>PLAN PAGO</th>-->
                                      <th>NOMBRE</th>
                                      <th>CARRERA</th>
                                      <th>MODALIDAD</th>
                                      <th>PLAN DE ESTUDIO</th>
                                      <th>TIPO DE CICLO</th>
                                      <th>FECHA INICIO</th>
                                      <th>FECHA FIN</th>
                                      <th>FECHA DE CREACION</th>
                                      <th>ACCIONES</th>

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
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE CARRERAS -->
            <div class="col-sm-12" id="tab_concentrado_carreras" <?=$activeCar?>>
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                            <div>
                              <button id="boton-crear-carrera" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal">
                                Crear Carrera
                              </button>
                            </div>
                          </div>
                          <div class="table-responsive text-center">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado de carreras</h5>
                              <table id="table-carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>INSTITUCION</th>
                                    <th>NOMBRE</th>
                                    <th>TIPO</th>
                                    <th>ÁREA</th>
                                    <th>FECHA CREACIÓN</th>
                                    <th>ACCIONES</th>

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
              </div>
            </div>
          </div> <!-- end container-fluid -->
        </div>
      </div>
      <!-- end wrapper -->
      <!-- Modas -->
      
      <!--Modal crear-materia-->
      <div class="modal fade" id="modalPlanEstudios" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Crear Plan De Estudios</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formPlanEstudios" type="post">

                    <div class="form-group">
                      <label for="selectCarreraPlanE">Elegir carrera</label>
                      <select class="form-control" id="selectCarreraPlanE" name="selectCarreraPlanE" required>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="selecTipoPlan">Elegir tipo de plan</label>
                      <select class="form-control" id="selecTipoPlan" required disabled>
                        <option value="NULL" selected>Oficial</option>
                        <option value="1">No Oficial</option>
                      </select>
                    </div>
                    
                    <div class="form-group d-none" id ="PlanReferenciaNone">
                      <label for="PlanReferencia">Nombre del plan de estudios Oficial:</label>
                      <select class ="form-control"name="PlanReferencia" id="PlanReferencia"></select>
                    </div>

                    <div class="form-group">
                      <label for="nombrePlanE">Nombre del plan de estudios:</label>
                      <input type="text" class="form-control" id="nombrePlanE" name="nombrePlanE" placeholder="Ingresa el nombre del plan de estudios" required>
                    </div>

                    <div class="form-group">
                      <label for="clavePlanE">Clave del plan de estudios:</label>
                      <input type="text" class="form-control" id="clavePlanE" name="clavePlanE" placeholder="Ingresa la clave que identificara al plan de estudios" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="selectCicloPlanE">Tipo de ciclo</label>
                      <select class="form-control" id="selectCicloPlanE" name="selectCicloPlanE" required>
                      <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="1">Cuatrimestre</option>
                          <option value="2">Semestre</option>
                          <option value="3">Trimestral</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="numeroCiclosPlanE">Número de ciclos:</label>
                      <input type="number" class="form-control" id="numeroCiclosPlanE" name="numeroCiclosPlanE" placeholder="Ingresa la cantidad que tendrá este plan de estudios" required>
                    </div>

                    <div class="form-group">
                      <label for="tipoRvoeCrear">Tipo de RVOE a ingresar:</label>
                      <select class="form-control" id="tipoRvoeCrear" name="tipoRvoeCrear" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Estatal</option>
                        <option value="2">Federal</option>
                        <option value="0">Ninguno</option>
                      </select>
                    </div>

                    <div class="form-group" id="divRvoeCrear" style="display: none;">
                      <label for="rvoePlanEstudiosCrear">RVOE:</label>
                      <input type="text" class="form-control" id="rvoePlanEstudiosCrear" name="rvoePlanEstudiosCrear" placeholder="Ingresa el RVOE correspondiente">

                      <div class="form-group">
                        <label for="rvoePlanEstudiosCrear">Feche de Registro de RVOE:</label>
                        <input type="date" class="form-control" id="FecharvoePlanEstudiosCrear" name = "FecharvoePlanEstudiosCrear" placeholder="Ingresa la fecha de registro del RVOE">
                      </div>
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Crear</button>
                      <button type="button" name="ocultarPlanEstudio" id="ocultarPlanEstudio" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal-->

      <!-- Modal modificar plan estudio -->
      <div class="modal fade modal-right" id="modalAsigMaterias">
					<div class="modal-dialog modal-xl">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0">Formulario Asignar Materias (Plan De Estudio)</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formAsigPlanEstudio" type="post">
                    <div class="form-group row justify-content-center" id="namePlanE"></div>

                    <div class="form-group" id="avisoPlanE"></div>
                    
                    <!--<div class="form-group" id="divTableAsignado"></div>-->
                    
                    <div class="form-group" id="divAsignar">

                    </div>
                    <!--<div class="form-group" id="divAsignar">

                    </div>-->

                    <div class="text-right"> 
                      <!--<button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>-->
                      <input type="hidden" name="idPlanEstudioAsigMat" id="idPlanEstudioAsigMat">
                      <button type="button" name="btnCerrarPlanE" id="btnCerrarPlanE" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->

      <!-- Modal modificar plan estudio -->
      <div class="modal fade" id="modalModPlanE" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Plan De Estudio</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formModPlanEstudio" type="post">

                  <div class="form-group">

                      <label for="modSelectCarreraPlanE">Elegir carrera</label>
                      <select class="form-control" id="modSelectCarreraPlanE" name="modSelectCarreraPlanE" required>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="selecTipoPlanMod">Elegir tipo de plan</label>
                      <select class="form-control" id="selecTipoPlanMod" required disabled>
                        <option value="null" selected>Oficial</option>
                        <option value="1">No Oficial</option>
                      </select>
                    </div>
                    
                    <div class="form-group d-none" id ="PlanReferenciaNoneMod">
                      <label for="PlanReferenciaMod">Nombre del plan de estudios Oficial:</label>
                      <select class ="form-control"name="PlanReferenciaMod" id="PlanReferenciaMod" disabled></select>
                    </div>

                    <div class="form-group">
                      <label for="modNombrePlanE">Nombre del plan de estudios:</label>
                      <input type="text" class="form-control" id="modNombrePlanE" name="modNombrePlanE" placeholder="Ingresa el nombre del plan de estudios" required>
                    </div>

                    <div class="form-group">
                      <label for="modClavePlanE">Clave del plan de estudios:</label>
                      <input type="text" class="form-control" id="modClavePlanE" name="modClavePlanE" placeholder="Ingresa la clave que identificara al plan de estudios" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="modSelectCicloPlanE">Tipo de ciclo</label>
                      <select class="form-control" id="modSelectCicloPlanE" name="modSelectCicloPlanE" required>
                      <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="1">Cuatrimestre</option>
                          <option value="2">Semestre</option>
                          <option value="3">Trimestral</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="modNumeroCiclosPlanE">Número de ciclos:</label>
                      <input type="number" class="form-control" id="modNumeroCiclosPlanE" name="modNumeroCiclosPlanE" placeholder="Ingresa el número de créditos" required>
                    </div>

                    <div class="form-group">
                    <label for="tipoRvoe">Tipo de RVOE a ingresar:</label>
                      <select class="form-control" id="tipoRvoe" name="tipoRvoe" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Estatal</option>
                        <option value="2">Federal</option>
                        <option value="0">Ninguno</option>
                      </select>
                    </div>

                    <div class="form-group" id="divRvoe" style="display: none;">
                      <label for="rvoePlanEstudios">RVOE:</label>
                      <input type="text" class="form-control" id="rvoePlanEstudios" name="rvoePlanEstudios" placeholder="Ingresa el RVOE proporcionado por la SEP">

                      <div class="form-group">
                        <label for="FecharvoePlanEstudiosEditar">Fecha de Registro de RVOE:</label>
                        <input type="date" class="form-control" id="FecharvoePlanEstudiosEditar" name = "FecharvoePlanEstudiosEditar" placeholder="Ingresa la fecha de registro del RVOE">
                      </div>

                    </div>

                    <div class="text-right">
                      <input type="hidden" name="id_plan_estudio" id="id_plan_estudio">
                      <input type="hidden" name="claveSepAntPlan" id="claveSepAntPlan">    
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
                      <button type="button" name="btnModPlanE" id="btnModPlanE" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div><!--end-modal modificar carrera-->

      <!--Modal crear-materia-->
      <div class="modal fade bs-example-modal-lg" id="modalmaterias" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Crear Materia</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formMateria" type="post">

                    <div class="form-group">
                      <label for="selectOficial">¿Materia oficial?</label>
                      <select class="form-control" id="selectOficial" name="selectOficial" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Sí</option>
                        <option value="2">No</option>
                      </select>
                    </div>

                    <div class="form-group" style="display: none;" id="divElegirCarreraMateria">
                      <label for="selectCarreraAsig">Elegir carrera</label>
                      <select class="form-control" id="selectCarreraAsig" name="selectCarreraAsig" required>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="nombreMateria">Nombre de la materia:</label>
                      <input type="text" class="form-control" id="nombreMateria" name="nombreMateria" placeholder="Ingresa el nombre de la materia" required>
                    </div>

                    <div class="form-group" id="divClaveMateria">
                      <label for="claveMateria">Clave de la asignatura:</label>
                      <input type="text" class="form-control" id="claveMateria" name="claveMateria" placeholder="Ingresa la clave que contendra la asignatura" pattern="[A-Za-z-_0-9]+" onkeypress="return check(event)" required>
                      <div class="clave alert alert-warning">Únicamente se aceptan letras, números y guion bajo o medio.</div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3" id="divTipoMateria">
                        <label for="selectTipoMateria">Tipo de materia:</label>
                        <select class="form-control" id="selectTipoMateria" name="selectTipoMateria" required>
                          <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="1">Adicional</option>
                          <option value="2">Área</option>
                          <option value="3">Complementaria</option>
                          <option value="4">Obligatoria</option>
                          <option value="5">Optativa</option>
                        </select>
                      </div>

                      <div class="col-sm-12 col-md-6 mb-3" id="divNumeroCre">
                        <label for="numeroCreditos">Número de créditos:</label>
                        <input type="number" class="form-control" id="numeroCreditos" name="numeroCreditos" placeholder="Ingresa el número de créditos" required step="any">
                      </div>
                    </div>

                    <div class="form-group" style="display: none;" id="divNumeroCreditos">
                      <label for="numeroCreditosNoOficial">Número de créditos:</label>
                      <input type="number" class="form-control" name="numeroCreditosNoOficial" id="numeroCreditosNoOficial" placeholder="Ingresa el número de créditos" required>
                    </div>

                    <div class="form-group">
                      <label for="contenidoPDF">Adjuntar documento con el contenido de la materia:</label>
                      <input type="file" class="form-control" name="contenidoPDF" id="contenidoPDF" accept=".pdf">
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Crear</button>
                      <button type="button" name="ocultarMaterias" id="ocultarMaterias" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal-->

      <!-- Modal modificar materia -->
      <div class="modal fade" id="modalModMateria" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Materia</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formModMateria" type="post">

                    <!--<div class="form-group" id="divRvoeMateria">
                          <label for="rvoeMateria">RVOE:</label>
                          <input type="text" class="form-control" id="rvoeMateria" name="rvoeMateria" placeholder="Ingresa el RVOE proporcionado por la SEP">
                    </div>-->

                    <div class="form-group">
                      <label for="modSelectOficial">¿Materia oficial?</label>
                      <select class="form-control" id="modSelectOficial" name="modSelectOficial" required>
                        <!--<option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Sí</option>
                        <option value="2">No</option>-->
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="modSelectCarreraAsig">Elegir carrera</label>
                      <select class="form-control" id="modSelectCarreraAsig" name="modSelectCarreraAsig" required>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="modNombreMateria">Nombre de la materia:</label>
                      <input type="text" class="form-control" id="modNombreMateria" name="modNombreMateria" placeholder="Ingresa el nombre de la materia" required>
                    </div>

                    <div class="form-group" id="divModClaveMateria">
                      <label for="modClaveMateria">Clave de la asignatura:</label>
                      <input type="text" class="form-control" id="modClaveMateria" name="modClaveMateria" placeholder="Ingresa la clave que contendra la asignatura" pattern="[A-Za-z-_0-9]+" onkeypress="return check(event)" required>
											<div class="clave alert alert-warning">Únicamente se aceptan letras y guion bajo o medio.</div>
                    </div>
                    
                    <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3" id="divModTipoMateria">
                      <label for="modSelectTipoMateria">Tipo</label>
                      <select class="form-control" id="modSelectTipoMateria" name="modSelectTipoMateria" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Adicional</option>
                        <option value="2">Área</option>
                        <option value="3">Complementaria</option>
                        <option value="4">Obligatoria</option>
                        <option value="5">Optativa</option>
                      </select>
                    </div>

                    
                      <div class="col-sm-12 col-md-6 mb-3" id="divModNumeroCre">
                        <label for="modNumeroCreditos">Número de créditos:</label>
                        <input type="number" class="form-control" id="modNumeroCreditos" name="modNumeroCreditos" placeholder="Ingresa el número de créditos" required step="any">
                      </div>
                    </div>

                    <div class="form-group" style="display: none;" id="divModNumeroCreditos">
                      <label for="modNumeroCreditosNoOficial">Número de créditos:</label>
                      <input type="number" class="form-control" name="modNumeroCreditosNoOficial" id="modNumeroCreditosNoOficial" placeholder="Ingresa el número de créditos" required>
                    </div>      

                    <div class="form-group">
                      <label for="modContenidoPDF">Adjuntar documento con el contenido de la materia:</label>
                      <input type="file" class="form-control" name="modContenidoPDF" id="modContenidoPDF" accept=".pdf">
                    </div>

                    <div class="text-right">
                      <input type="hidden" name="id_materia" id="id_materia">
                      <!--<input type="hidden" name="claveSepAnt" id="claveSepAnt">-->
                      <input type="hidden" name="pdfAnterior" id="pdfAnterior">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
                      <button type="button" name="btnModMateria" id="btnModMateria" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->
      
      <!-- center modal form alta carreras -->
      <div id="crear-carrera" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="crear-carreraLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="crear-carreraLabel">Crear carrera</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para crear la carrera</h4>
                      <form id="crearcarrera" type="post">
                        <div class="form-group">
                          <label for="select-institucion">Selecciona la institución:</label>
                          <select class="form-control" id="select-institucion" name="selectinstitucion" required>
                          </select>
                        </div>

                        <div class="form-group" id="divSelectCarrera" style="display: none;">
                          <label for="select-tipo">Elegir la categoría que tendrá la carrera a crear:</label>
                          <select class="form-control" id="select-tipo" name="selecttipo" required>
                          </select>
                        </div>

                        <div class="form-group" id="divSelectArea" style="display:none;">
                          <label for="areaCarrera">Elegir área a la que pertenecera la carrera:</label>
                          <select class="form-control" name="areaCarrera" id="areaCarrera" required>

                          </select>
                        </div>

                        <div class="form-group">
                          <label for="crearnombrecarrera">Nombre de la carrera:</label>
                          <input type="text" class="form-control" id="crearnombrecarrera" name="crearnombrecarrera" placeholder="Ingresa el nombre de la carrera" required>
                        </div>

                        <div class="form-group">
                          <label for="nombreGuno">Nombre de la Generación:</label>
                          <input type="text" class="form-control" name="nombreGuno" id="nombreGuno" required disabled>
                        </div>

                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="selectModalidadGuno">Modalidad de la generación:</label>
                            <select class="form-control" id="selectModalidadGuno" name="selectModalidadGuno" required>
                              <option selected="true" value="" disabled="disabled">Seleccione</option>
                              <option value="Presencial">Presencial</option>
                              <option value="En linea">En línea</option>
                            </select>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="selectTipocicloGuno">Tipo de ciclo:</label>
                            <select class="form-control" id="selectTipocicloGuno" name="selectTipocicloGuno" required>
                              <option selected="true" value="" disabled="disabled">Seleccione</option>
                              <option value="1">Cuatrimestre</option>
                              <option value="2">Semestre</option>
                              <option value="3">Trimestral</option>
                            </select>
                          </div>
                        </div>

                        <!--<div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="fechaInicioGuno">Fecha de inicio:</label>
                            <input type="date" class="form-control" id="fechaInicioGuno" name="fechaInicioGuno" placeholder="fecha de inicio de la generación" required>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="fechaFinGuno">Fecha fin:</label>
                            <input type="date" class="form-control" id="fechaFinGuno" name="fechaFinGuno" placeholder="fecha de fin de la generación" required>
                          </div>
                        </div>-->

                        <div class="form-group">
                          <label for="fechaInicioGuno">Fecha de inicio:</label>
                          <input type="date" class="form-control" id="fechaInicioGuno" name="fechaInicioGuno" placeholder="fecha de inicio de la generación" required>
                        </div>

                        <div class="text-right">
                          <input type="hidden" class="form-control" id="numGuno" name="numGuno" value="1" placeholder="Ingresa el número de la generación a crear" required disabled>
                          <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Crear</button>
                          <button type="button" name="ocultar" id="ocultar" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal carreras -->

      <!-- Modal modificar carreras -->
      <div class="modal fade bs-example-modal-lg" id="modalModifycarrera" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Carrera</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formModCarrera" type="post">
                    <div class="form-group">
                      <label for="devinstitucion">Selecciona la institución:</label>
                      <select class="form-control" id="devinstitucion" name="devinstitucion" required>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label for="devtipocarrera">Elegir la categoría que tendrá la carrera a crear:</label>
                      <select class="form-control" id="devtipocarrera" name="devtipo" required>
                        <!--<option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Certificación</option>
                        <option value="3">Diplomado</option>
                        <option value="6">Doctorado</option>
                        <option value="4">Licenciatura</option>
                        <option value="5">Maestría</option>
                        <option value="2">TSU</option>-->
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="devAreaCarrera">Elegir área a la que pertenecera la carrera:</label>
                      <select class="form-control" name="devAreaCarrera" id="devAreaCarrera" required>
                        <option value="" selected="true" disabled="disabled">Seleccione</option>
                        
                      </select>
                    </div>
                      
                    <div class="form-group">
                      <label for="devnombrecarrera">Nombre de la carrera:</label>
                      <input type="text" class="form-control" id="devnombrecarrera" name="devnombrecarrera" placeholder="Ingresa el nombre de la carrera" required>
                    </div>

                    <div class="form-group">
                      <label for="devnombreGuno">Nombre de la Generación:</label>
                      <input type="text" class="form-control" name="devnombreGuno" id="devnombreGuno" required disabled>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="devselectModalidadGuno">Modalidad de la generación:</label>
                        <select class="form-control" id="devselectModalidadGuno" name="devselectModalidadGuno" required>
                          <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="Presencial">Presencial</option>
                          <option value="En linea">En línea</option>
                        </select>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="devselectTipocicloGuno">Tipo de ciclo:</label>
                        <select class="form-control" id="devselectTipocicloGuno" name="devselectTipocicloGuno" required>
                          <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="1">Cuatrimestre</option>
                          <option value="2">Semestre</option>
                          <option value="3">Trimestral</option>
                        </select>
                      </div>
                    </div>

                    <!--<div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="devfechaInicioGuno">Fecha de inicio:</label>
                        <input type="date" class="form-control" id="devfechaInicioGuno" name="devfechaInicioGuno" placeholder="fecha de inicio de la generación" required>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="devfechaFinGuno">Fecha fin:</label>
                        <input type="date" class="form-control" id="devfechaFinGuno" name="devfechaFinGuno" placeholder="fecha de fin de la generación" required>
                      </div>
                    </div>-->

                    <div class="form-group">
                      <label for="devfechaInicioGuno">Fecha de inicio:</label>
                      <input type="date" class="form-control" id="devfechaInicioGuno" name="devfechaInicioGuno" placeholder="fecha de inicio de la generación" required>
                    </div>

                    <!--<div class="form-group">
                          <label for="modnombreG">RVOE:</label>
                          <input type="text" class="form-control" id="rvoe" name="rvoe" placeholder="Ingresa el RVOE proporcionado por la SEP">
                    </div>-->

                    <div class="text-right">
                      <input type="hidden" name="id_carrera" id="id_carrera"> 
                      <input type="hidden" class="form-control" id="devnumGuno" name="devnumGuno" value="1" required disabled>   
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
                      <button type="button" name="ocultar2" id="ocultar2" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->

      <!-- Modal modificar carreras -->
      <div class="modal fade" id="modalTablaCarrera" role="dialog" style="overflow-y: scroll;">
					<div class="modal-dialog modal-xl">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="labelAlumnosCarreras"></h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <div class="table-responsive text-center">
                    <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                      <h5>Directorio de alumnos</h5>
                      <table id="table-alumnos-carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                      <thead>
                        <tr>
                          <th>NOMBRE</th>
                          <th>APELLIDO PATERNO</th>
                          <th>APELLIDO MATERNO</th>
                          <th>GENERACIÓN</th>
                          <th>ESTATUS</th>

                          <th>NÚMERO DE REFERENCIA</th>

                          <th>CURP</th>
                          <th>EDAD</th>
                          <th>CORREO</th>
                          <th>TELÉFONO</th>
                          <th>SEXO</th>
                          <th>GRADO ACADÉMICO</th>
                          <th>PAÍS DEL ÚLTIMO GRADO DE ESTUDIÓ</th>
                          <th>ESTADO DEL ÚLTIMO GRADO DE ESTUDIÓ</th>
                          <th>PAÍS DONDE RADICA</th>
                          <th>ESTADO DONDE RADICA</th>
                          <th>PAÍS DE NACIMIENTO</th>
                          <th>ESTADO DE NACIMIENTO</th>

                          <th>NOTAS</th>
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
                    <button type="button" name="ocultarTablaDirectorio" id="ocultarTablaDirectorio" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
                  </div>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->
      
      <!--Modal crear-generacion-->
      <div class="modal fade bs-example-modal-lg" id="modalGeneracion" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Crear Generación</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formGeneracion" type="post">

                    <div class="form-group">
                      <label for="selectCarrer">Selecciona la carrera</label>
                      <select class="form-control" id="selectCarrer" name="selectCarrer" required>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="numG">Número de la generación a crear:</label>
                      <input type="number" class="form-control" id="numG" name="numG" placeholder="Ingresa el número de la generación a crear" required disabled>
                    </div> 

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="select-modalidad">Modalidad de la generación</label>
                        <select class="form-control" id="select-modalidad" name="selectmodalidad" required>
                          <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="Presencial">Presencial</option>
                          <option value="En linea">En línea</option>
                        </select>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="select-tipo-ciclo">Tipo de ciclo:</label>
                        <select class="form-control" id="select-tipo-ciclo" name="selecttipociclo" required>
                          <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="1">Cuatrimestre</option>
                          <option value="2">Semestre</option>
                          <option value="3">Trimestral</option>
                        </select>
                      </div>
                    </div>

                    <!--<div class="form-group" id="divCuatrimestre">
                      <label for="cantidadCiclos">Cantidad de ciclos:</label>
                      <input type="number" class="form-control" id="cantidadCiclos" name="cantidadCiclos" placeholder="Ingresa la cantidad que tendrá esta generación">
                    </div>-->

                    <!--<div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="fechainicio">Fecha de inicio:</label>
                        <input type="date" class="form-control" id="fechainicio" name="fechainicio" placeholder="fecha de inicio de la generación" required>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="fechafin">Fecha fin:</label>
                        <input type="date" class="form-control" id="fechafin" name="fechafin" placeholder="fecha de fin de la generación" required>
                      </div>
                    </div>-->
                    <div class="form-group">
                      <label for="fechainicio">Fecha de inicio:</label>
                      <input type="date" class="form-control" id="fechainicio" name="fechainicio" placeholder="fecha de inicio de la generación" required>
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Crear</button>
                      <button type="button" name="ocultar3" id="ocultar3" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal-->

      <!--Modal crear-generacion-->
      <div class="modal fade bs-example-modal-lg" id="modalModGen" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Generación</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formModGeneracion" type="post">
                    <!--<div class="form-group">
                      <label for="modnombreG">RVOE:</label>
                      <input type="text" class="form-control" id="rvoe" name="rvoe" placeholder="Ingresa el RVOE proporcionado por la SEP">
                    </div>--> 
                    
                    <div class="form-group">
                      <label for="modselectCarrer">Selecciona las carreras</label>
                      <select class="form-control" id="modselectCarrer" name="modselectCarrer" required>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="modNumG">Número de la generación a crear:</label>
                      <input type="number" class="form-control" id="modNumG" name="modNumG" placeholder="Ingresa el número de la generación a crear" required disabled>
                    </div>

                    <!--<div class="form-group">
                      <label for="modnombreG">Nombre de la generación:</label>
                      <input type="text" class="form-control" id="modnombreG" name="modnombreG" placeholder="Ingresa el nombre de la generación" required>
                    </div>-->

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="modModalidad">Modalidad carrera</label>
                        <select class="form-control" id="modModalidad" name="modselectmodalidad" required>
                          <option selected="true" value="" disabled="disabled">Seleccione</option>
                          <option value="Presencial">Presencial</option>
                          <option value="En linea">En línea</option>
                        </select>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="modtipociclo">Tipo de ciclo:</label>
                        <select class="form-control" id="modtipociclo" name="modtipociclo" required>
                          <option selected="true" disabled="disabled">Seleccione</option>
                          <option value="1">Cuatrimestre</option>
                          <option value="2">Semestre</option>
                          <option value="3">Trimestral</option>
                        </select>
                      </div>
                    </div>

                    <!--<div class="form-group" id="divModCiclos">
                      <label for="modcantidadCiclos">Cantidad de ciclos:</label>
                      <input type="number" class="form-control" id="modcantidadCiclos" name="modcantidadCiclos" placeholder="Ingresa la cantidad que tendrá">
                    </div>-->

                    <!--<div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="modfechainicio">Fecha de inicio:</label>
                        <input type="date" class="form-control" id="modfechainicio" name="modfechainicio" placeholder="fecha de inicio de la generación" required>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="modfechafin">Fecha fin:</label>
                        <input type="date" class="form-control" id="modfechafin" name="modfechafin" placeholder="fecha de fin de la generación" required>
                      </div>
                    </div>-->
                    <div class="form-group">
                      <label for="modfechainicio">Fecha de inicio:</label>
                      <input type="date" class="form-control" id="modfechainicio" name="modfechainicio" placeholder="fecha de inicio de la generación" required>
                    </div>
                    
                    <div class="text-right">
                      <input type="hidden" name="idG" id="idG">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
                      <button type="button" name="ocultar4" id="ocultar4" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal-->

      <!--Modal crear-generacion-->
      <div class="modal fade bs-example-modal-lg" id="modalAsigPlanEst" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Asignar Plan De Estudio a Generación</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formAsigPlanEstGen" type="post">
                    <div class="form-group">
                      <label for="asigPlanEst">Selecciona el plan de estudio</label>
                      <select class="form-control" id="asigPlanEst" name="asigPlanEst" required>
                      </select>
                    </div>

                    <div class="form-group">
                        <label for="fechafinAsigPE">Fecha fin:</label>
                        <input type="date" class="form-control" id="fechafinAsigPE" name="fechafinAsigPE" placeholder="fecha de fin de la generación" required>
                    </div>
                    
                    <div class="text-right">
                      <input type="hidden" name="idGenPlanE" id="idGenPlanE">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Asignar Plan Estudio</button>
                      <button type="button" name="cancelAsigPlanE" id="cancelAsigPlanE" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal-->

       <!-- Modal modificar plan estudio -->
       <div class="modal fade modal-right" id="modalAsignarFechas">
					<div class="modal-dialog modal-xl">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0">Formulario Asignar Fechas a Generación</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formAsignarFechas" type="post">

                    <div class="form-group row justify-content-center" id="nameGeneracion"></div>
                    <div class="form-group" id="avisoPlanE">
                      <h4 style="color: #4478AA">Fechas sugeridas por el sistema, Ingrese la fecha correcta y de clic en enviar para guardar.</h4>
                    </div>
                    <!--<div class="form-group" id="avisoPlanE"></div>-->
                    <!--<div class="form-group" id="divTableAsignado"></div>-->
                    
                    <!--<div class="form-group" id="divTipoCiclo"></div>-->
                    <div class="form-group" id="divAsignarFechas"></div>
                    <!--<div class="form-group" id="divAsignar">

                    </div>-->

                    <div class="text-right"> 
                      <!--<button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>-->
                      <input type="hidden" name="idPlanEstudioAsigMat" id="idPlanEstudioAsigMat">
                      <button type="button" name="btnCerrarFechasGen" id="btnCerrarFechasGen" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->

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
                        <input type="text" class="form-control" id="nombreDirectorio" name="nombreDirectorio" placeholder="Ingresa el nombre del alumno">
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="apellidoPaternoDirectorio">APELLIDO PATERNO:</label>
                        <input type="text" class="form-control" id="apellidoPaternoDirectorio" name="apellidoPaternoDirectorio" placeholder="Ingresa el apellido paterno del alumno">
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="apellidoMaternoDirectorio">APELLIDO MATERNO:</label>
                        <input type="text" class="form-control" id="apellidoMaternoDirectorio" name="apellidoMaternoDirectorio" placeholder="Ingresa el apellido materno del alumno">
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
                          <select class="form-control" name="estatusAlumnoDirectorio" id="estatusAlumnoDirectorio">
                            <option value="" disabled="disabled">SELECCIONE EL ESTATUS DEL ALUMNO</option>
                            <option value="1">ACTIVO</option>
                            <option value="2">BAJA</option>
                            <option value="3">EGRESADO</option>
                            <option value="4">TITUTLADO</option>
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

                    <div class="form-group">
                      <center><label for="lugarRadicaDirectorio">LUGAR DONDE RADICA</label></center>
                      <div class="row">
                        <div class="col-sm-12 col-md-6 mb-3">
                          <label for="paisAlumnoDirectorio">PAÍS DONDE RADICA:</label>
                          <select class="form-control" name="paisAlumnoDirectorio" id="paisAlumnoDirectorio">
                          </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                          <label for="estadoAlumnoDirectorio">ESTADO DONDE RADICA:</label>
                          <select class="form-control" name="estadoAlumnoDirectorio" id="estadoAlumnoDirectorio">
                          </select>
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

                    <!--
                    <div class="form-group">
                        <label for="apellidoPaternoDirectorio">APELLIDO PATERNO:</label>
                        <input type="text" class="form-control" id="apellidoPaternoDirectorio" name="apellidoPaternoDirectorio" placeholder="Ingresa el apellido paterno del alumno" required>
                    </div>

                    <div class="form-group">
                        <label for="apellidoMaternoDirectorio">APELLIDO MATERNO:</label>
                        <input type="text" class="form-control" id="apellidoMaternoDirectorio" name="apellidoMaternoDirectorio" placeholder="Ingresa el apellido materno del alumno" required>
                    </div>

                    <div class="form-group">
                        <label for="estatusAlumnoDirectorio">ESTATUS:</label>
                        <select class="form-control" name="estatusAlumnoDirectorio" id="estatusAlumnoDirectorio" required>
                          <option value="" disabled="disabled">SELECCIONE EL ESTATUS DEL ALUMNO</option>
                          <option value="1">ACTIVO</option>
                          <option value="2">BAJA</option>
                          <option value="3">EGRESADO</option>
                          <option value="4">TITUTLADO</option>
                          <option value="5">EXPULSADO</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="generacionDirectorio">GENERACIÓN:</label>
                        <select class="form-control" name="generacionDirectorio" id="generacionDirectorio" required>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="emailAlumnoDirectorio">EMAIL:</label>
                        <input type="email" class="form-control" id="emailAlumnoDirectorio" name="emailAlumnoDirectorio" placeholder="Ingresa el email del alumno" required>
                    </div>

                    <div class="form-group">
                        <label for="paisAlumnoDirectorio">PAÍS:</label>
                        <select class="form-control" name="paisAlumnoDirectorio" id="paisAlumnoDirectorio" required>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estadoAlumnoDirectorio">ESTADO:</label>
                        <select class="form-control" name="estadoAlumnoDirectorio" id="estadoAlumnoDirectorio" required>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="telefonoAlumnoDirectorio">TELÉFONO:</label>
                        <input type="tel" class="form-control" id="telefonoAlumnoDirectorio" name="telefonoAlumnoDirectorio" onkeypress="return checkTel(event)" maxlength="10" placeholder="Ingresa el número de teléfono del alumno">
                    </div>-->

                    <div class="text-right">
                      <input type="hidden" name="idRelacion" id="idRelacion">
                      <input type="hidden" name="idAlumno" id="idAlumno">
                      <input type="hidden" name="idGeneracionAntigua" id="idGeneracionAntigua">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Actualizar</button>
                      <button type="button" name="cerrarEditarDirectorio" id="cerrarEditarDirectorio" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->

      <!-- Modal modificar plan estudio -->
      <div class="modal fade modal-right" id="modalAsignarBloqueo" role="dialog" style="overflow-y: scroll;">
					<div class="modal-dialog modal-xl">
							<div class="modal-content">
								<div class="modal-header">
										<!--<h4 class="modal-title m-0">Formulario Asignar Bloqueo a Generación</h4>-->
										<h4 class="modal-title m-0">Documentos de la Generación</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                    <!--<div class="text-right">
											<button id="btnAsignarDocumentos" type="button" class="btn btn-primary waves-effect waves-light">
												Asignar Documentos
											</button>
										</div>-->
                  <div class="table-responsive text-center">
                    <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                      <h5>Lista De Documentos</h5>
                      <table id="table-bloqueo-documentos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                      <thead>
                        <tr>
                          <th>NOMBRE</th>
                          <th>BLOQUEO DIGITAL</th>
                          <th>FECHA BLOQUEO</th>
                          <th>BLOQUEO FISICO</th>
                          <th>FECHA BLOQUEO</th>
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
                    <input type="hidden" name="idGeneracionBloqueo" id="idGeneracionBloqueo">
                    <button type="button" name="ocultarTablaDocumentosGeneracion" id="ocultarTablaDocumentosGeneracion" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
                  </div>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->

      <!-- Modal modificar plan estudio -->
      <div class="modal fade modal-right" id="modalAsigDocumentosGen">
					<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0">Formulario Asignar Documentos a Generación</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formAsigDocumentosGen" type="post">

                    <div class="form-group">
                        <label for="nombreDirectorio">Seleciones Documentos:</label>
                        <select class="form-control" name="selectAsigDocumentosGen" id="selectAsigDocumentosGen"></select>
                    </div>

                    <div class="text-right">
                      <input type="hidden" name="idRelacion" id="idRelacion">
                      <input type="hidden" name="idAlumno" id="idAlumno">
                      <input type="hidden" name="idGeneracionAntigua" id="idGeneracionAntigua">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Actualizar</button>
                      <button type="button" name="cerrarEditarDirectorio" id="cerrarEditarDirectorio" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->

      <!-- Modal modificar plan estudio -->
      <div class="modal fade modal-right" id="modalDatosBloqueo">
					<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0">Formulario Asignar Datos De Bloqueo</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formAsigBloqueoDocumento" type="post">

                    <div class="form-group">
                      <label for="selectBloqueoDigital">Bloqueo por Documento Digital</label>
                      <select class="form-control" name="selectBloqueoDigital" id="selectBloqueoDigital" required>
                        <option value="" selected="true" disabled="disabled">Seleccione</option>
                        <option value="1">Activado</option>
                        <option value="2">Desactivado</option>
                      </select>
                    </div>

                    <div class="form-group" id="divFechaBloqueoDigital">
                      <label for="fechaBloqueoDigital">Fecha De Inicio De Bloqueo</label>
                      <input class="form-control" type="date" name="fechaBloqueoDigital" id="fechaBloqueoDigital">
                    </div>

                    <div class="form-group" id="divHoraBloqueoDigital">
                      <label for="horaBloqueoDigital">Hora De Inicio De Bloqueo</label>
                      <input class="form-control" type="time" name="horaBloqueoDigital" id="horaBloqueoDigital">
                    </div>

                    <div class="form-group">
                      <label for="selectBloqueoFisico">Bloqueo por Documento Físico</label>
                      <select class="form-control" name="selectBloqueoFisico" id="selectBloqueoFisico" required>
                        <option value="" selected="true" disabled="disabled">Seleccione</option>
                        <option value="1">Activado</option>
                        <option value="2">Desactivado</option>
                      </select>
                    </div>

                    <div class="form-group" id="divFechaBloqueoFisico">
                      <label for="fechaBloqueoFisico">Fecha De Inicio De Bloqueo</label>
                      <input class="form-control" type="date" name="fechaBloqueoFisico" id="fechaBloqueoFisico">
                    </div>

                    <div class="form-group" id="divHoraBloqueoFisico">
                      <label for="horaBloqueoFisico">Hora De Inicio De Bloqueo</label>
                      <input class="form-control" type="time" name="horaBloqueoFisico" id="horaBloqueoFisico">
                    </div>

                    <div class="text-right">
                      <input type="hidden" id="idBloqueo" name="idBloqueo">
                      <!--<input type="hidden" id="idGeneracionBloqueoDoc" name="idGeneracionBloqueoDoc">-->
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Actualizar</button>
                      <button type="button" name="cerrarAsignarBloqueo" id="cerrarAsignarBloqueo" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal modificar carrera-->

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
      <!-- scripts -->

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

      <!--Sweet Alert 2-->
	    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	    <script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>

      <!--  datatable (tablas) js-->
      <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
      <!-- Tabla con botones excel, pdf, imprimir -->
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

      <!-- sweet alert -->
      <script src="../assets/js/template/sweetalert.min.js"></script>

      <!-- Tablas responsivas -->
      <script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
      <script src="../assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

      <script src="../assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.js"></script>

      <!-- Inicializador de tablas init js -->
      <script src="../assets/pages/datatables.init.class.js"></script>
        
      <!-- Latest compiled and minified JavaScript -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


      <script src="../assets/pages/clipboard.js"></script>
      <script type="text/javascript">
        new ClipboardJS('.clpb', {
          text: function(trigger) {
              return trigger.getAttribute('aria-label');
          }
        });
        $(document).ready(function(){
          $("#tabla_seguimientos").DataTable({
              'pageLength': 5,
                  "lengthChange": false,
                  "info":     false
          }).columns.adjust()
          $(".moneyFt").maskMoney();
        })
        $(".page-title").children().on('click', function(){
          if(!$(this).hasClass('tab_active')){
            $("#selectBuscarMaterias").val('');
            
            selectBuscarMaterias();
            enab = $(".tab_active").attr('tab-target');
            trg = $(this).attr('tab-target');

            $("#tab_concentrado_"+enab).fadeOut('fast', function(){
              $("#tab_concentrado_"+trg).fadeIn('fast', function(){
                $("#tab_concentrado_"+trg).find('table').each(function(){
                  $(this).DataTable().columns.adjust()
                })
              })
            })

            $(".tab_active").removeClass("tab_active");
            $(this).addClass("tab_active");

          }
        })
      </script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/controlescolar/carreras.js"></script>
      <script src="../assets/js/controlescolar/generaciones.js"></script>
      <script src="../assets/js/controlescolar/materias.js"></script>
      <script src="../assets/js/controlescolar/planEstudios.js"></script>


      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>
