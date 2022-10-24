<?php
session_start();
$usrs_permiso = [5, 31];
if(!isset($_SESSION["usuario"]) || !in_array($_SESSION["usuario"]['idTipo_Persona'], $usrs_permiso)){
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
    <!-- Sweet Alert -->
    <link href="../assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">
	  <link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../assets/css/newStyles.css">  
    
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
          <div class="navbar-custom">
            <div class="container-fluid">
              <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                <?php if( $_SESSION["usuario"]['idTipo_Persona'] == 31 ){?>
                  <li class="has-submenu">
                    <a href="../controlescolar"><i class="ti-home"></i> Inicio</a>
                  </li>
                
                  <li class="has-submenu">
                    <a href="../controlescolar/gestorCarreras.php"><i class="fas fa-user-graduate"></i>Gestor Carreras</a>
                  </li>
                  
                  <li class="has-submenu last-elements">
                    <a href="#"><i class="far fa-dot-circle"></i>Gestor Webex</a>
                  </li>
                <?php }else{ ?>
                  <li class="has-submenu">
                    <a href="#"><i class="ti-home"></i> Inicio</a>
                  </li>
                <?php } ?>
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
        <div class="container-liquid">

          
          
           
            <!-- SECCION PARA CONCENTRADO DE EVENTOS -->
            
            <div class="container-liquid">
            <!-- Page-Title -->
              <div class="col-lg-12">
                <div class = "card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="page-title-box">
                          <div class="row align-items-center">
                            <div class="col-md-12">
                              <h4 class="page-title m-0">
                                <span tab-target="eventos" class="tab_active">
                                  <i class="ti-briefcase"> </i> Administrar sesiones webex
                                </span>
                              </h4>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li>
                          <a class="nav-link active" id="sesiones-tab" data-toggle="tab" href="#sesiones" role="tab" aria-controls="sesiones" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-play"></i></span>
                            <span class="d-none d-sm-block"><i class="fas fa-play"></i> Sesiones</span>
                          </a>
                        </li>
                      
                        <li>
                          <a class="nav-link" id="directorio-tab" data-toggle="tab" href="#directorio" role="tab" aria-controls="directorio" aria-selected="true">
                              <span class="d-block d-sm-none"><i class="fas fa-list-alt"></i></span>
                              <span class="d-none d-sm-block"><i class="fas fa-list-alt"></i> Directorio</span>
                          </a>
                        </li>

                        <li>
                          <a class="nav-link" id="asistencias-tab" data-toggle="tab" href="#asistencias" role="tab" aria-controls="asistencias" aria-selected="true">
                              <span class="d-block d-sm-none"><i class="fas fa-book"></i></span>
                              <span class="d-none d-sm-block"><i class="fas fa-book"></i> Asistencia</span>
                          </a>
                        </li>
                        
                        <li>
                          <a class="nav-link" id="horas-tab" data-toggle="tab" href="#horas" role="tab" aria-controls="horas" aria-selected="true">
                              <span class="d-block d-sm-none"><i class="fas fa-clock"></i></span>
                              <span class="d-none d-sm-block"><i class="fas fa-clock"></i> Registrar Horas</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-content bg-light">
                <div class="tab-pane fade active show" id="sesiones" role="tabpanel" aria-labelledby="sesiones-tab">
                    <div class="container-liquid">
                    <!--<h2>Sesiones</h2>-->
                    <div class="col-sm-12" id="tab_concentrado_eventos">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-lg-12">
                              <!-- FIN CONTENEDOR DE TABS -->
                              <div class="tab-content bg-light">
                                <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                  <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                                  </div>
                                  <div class="table-responsive">
                                    
                                    <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                                      <div class="m-2 ml-3">
                                        <h2>Listado de sesiones webex</h2>
                                        <button class="btn btn-primary" id="btn_new_sesion">Nueva sesión</button>
                                      </div>
                  
                                      <table id="table-webex" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                        <thead>
                                          <tr>
                                            <th>NOMBRE SESIÓN</th>
                                            <th>TIPO</th>
                                            <th>NOMBRE CLASE / EVENTO</th>
                                            <th>MAESTRO</th>
                                            <th>ID SESION</th>
                                            <th>CONTRASEÑA</th>
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
                </div>
              
                
                <div class="tab-pane fade" id="directorio" role="tabpanel" aria-labelledby="directorio-tab">
                  <div class="col-sm-12" id="tab_directorio_webex">
                  <!--<h2>Directorio</h2>-->
                    <div class="container-liquid">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-lg-12">
                              <!-- FIN CONTENEDOR DE TABS -->
                              <div class="tab-content bg-light">
                                <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                  <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                                  </div>
                                  <div class="table-responsive">
                                    
                                    <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                                      <h2>Directorio</h2>
                                      <div class="tab-pane fade <?=$active?> show" id="directorio" role="tabpanel" aria-labelledby="directorio-tab">
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
                      </div>
                    </div>
                  </div>
                </div>

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
                      </div>
                    </div>

                  </div>
                </div>

                <div class="tab-pane fade" id="horas" role="tabpanel" aria-labelledby="horas-tab">
                  <div class="container-liquid">
                    <div class="card">
                      <div class="card-body">
                        <h2>Registrar horas trabajadas por el docente</h2>
                        <form id="formRegistroHoras">
                          <div class="row mb-3">
                            <div class="col-sm-12 col-md-6">
                              <label for="">Filtrar por carrera</label>
                              <select  id="select_carreras_docentes" class="form-control" required>
                                <option value="">Seleccione</option>
                              </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <label for="">Seleccione Generación</label>
                              <select id="select_generaciones_carreras" class="form-control" required>
                                <option>Seleccione</option>
                              </select>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col-sm-12 col-md-6">
                              <label for="">Seleccione maestro</label>
                              <select name="select_maestro_gen" id="select_maestro_gen" class="form-control" required>
                                <option value="">Seleccione</option>
                              </select>
                            </div>
                            
                            <div class="col-sm-12 col-md-6">
                              <label for="">Seleccione Clase</label>
                              <select name="select_clase_id" id="select_clase_id" class="form-control" required>
                                <option>Seleccione</option>
                              </select>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col-sm-12 col-md-4">
                              <label for="">Fecha de clase</label>
                              <input type="date" class="form-control" value="" id="view_date_class" readonly>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <label for="">Hora entrada</label>
                              <input type="time" class="form-control time_inp" id="inp_hora_ent" name="inp_hora_ent" required>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <label for="">Hora salida</label>
                              <input type="time" class="form-control time_inp" id="inp_hora_sal" name="inp_hora_sal" required>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col">
                              <label for="">Horas total</label>
                              <input type="text" id="inp_hors_total" class="form-control" readonly >
                            </div>
                            <div class="col mt-auto text-center">
                              <button type="submit" class="btn btn-block btn-success">Guardar</button>
                            </div>
                          </div>
                        </form>
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

      <!-- center modal form editar sesion -->
      <div id="modal-editar-concepto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Editar sesion</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para editar la sesión</h4>
                      <div class="row">
                        <div class="col-6 offset-6">
                          <label>Tipo de sesión</label>
                          <input type="text" class="form-control" readonly="" id="tipo_sesion_edit">
                        </div>
                      </div>
                      <form id="form-editarsesion" type="post">
                        <div class="form-group">
                          <label for="editarnombresesion">Nombre de la sesión:</label>
                          <input type="text" class="form-control" id="editarnombresesion" name="editarnombresesion" placeholder="Ingresa el nombre de la sesión">
                        </div>
                        <div class="form-group">
                          <label for="editaridsesion">Id de la sesión:</label>
                          <input type="text" class="form-control" id="editaridsesion" name="editaridsesion" placeholder="Ingresa el id de la sesion">
                        </div>
                        <div class="form-group">
                          <label for="editarcontrasenasesion">Contraseña de la sesión:</label>
                          <input type="text" class="form-control" id="editarcontrasenasesion" name="editarcontrasenasesion" placeholder="Ingresa la contraseña de la sesión">
                        </div>
                        <div class="border rounded p-2" id="container_url">
                          <div class="form-group">
                            <input type="hidden" name="id_clase" id="id_clase">
                            <label for="editar_url_clase">Url del video de la clase:</label>
                            <input type="text" class="form-control" id="editar_url_clase" name="editar_url_clase" placeholder="Ingrese el url de la clase">
                          </div>
                          <div class="form-group">
                            <label for="editar_foto_clase">Foto para la clase:</label><br>
                            <input type="file" id="editar_foto_clase" name="editar_foto_clase" accept="image/*">
                          </div>
                        </div>

                        <div class="form-group">
                          <input type="hidden" class="form-control" id="idsesion" name="idsesion">
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Actualizar</button>
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>
      <!-- Modal -->
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
			</div><!-- /.modal editar sesion -->

      <!-- NUEVA SESION WEBEX -->
      <div id="modal_nueva_sesion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Nueva sesión webex</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <form id="form-nueva-sesion" type="post">
                        <div class="row mb-4">
                          <div class="col">
                            <label for="">Seleccione tipo de sesión</label>
                            <select name="sesion_type" id="sesion_type" class="form-control">
                              <option value="clase">Clase</option>
                              <option value="evento">Evento</option>
                            </select>
                          </div>
                        </div>
                        <div class="border rounded px-3 mb-3 pb-3" id="div_class_inputs"> <!--inputs requeridos para crear sesión para clase-->
                          <center><b>Sesión para clase</b></center>
                          <div class="row mt-3">
                            <div class="form-group col-sm-12 col-md-6">
                              <label for="select_carrera">Selecciona la carrera para filtrar las clases disponibles</label>
                              <select name="select_carrera" id="select_carrera" class="form-control">
                                <option selected>Selecciona una carrera</option>
                              </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                              <label for="select_generaciones">Selecciona la generación para filtrar clases</label>
                              <select name="select_generaciones" id="select_generaciones" class="form-control">
                                <option selected>Selecciona una generación</option>
                              </select>
                            </div>
                          
                            <div class="form-group col-sm-12 col-md-6 mb-0">
                              <label for="select_clases">Selecciona la clase para la sesión</label>
                              <select name="select_clases" id="select_clases" class="form-control">
                                <option selected>Selecciona una clase</option>
                              </select>
                            </div>
                          </div>
                          <br>
                          <div class="row border rounded px-3 mb-3 pb-3 info-maestro d-none">
                            <div class="form-group col-md-12">
                              <center><b>Información Del Docente</b></center>
                            </div>

                            <div class="form-group col-sm-12 col-md-4 mb-0">
                              <label for="maestro_nombre">Nombre</label>
                              <input type="text" id="maestro_nombre" class="form-control" disabled>
                            </div>

                            <div class="form-group col-sm-12 col-md-4 mb-0">
                              <label for="maestro_email">Correo</label>
                              <input type="text" id="maestro_email" class="form-control" disabled>
                            </div>

                            <div class="form-group col-sm-12 col-md-4 mb-0">
                              <label for="maestro_numero">Telefono</label>
                              <input type="text" id="maestro_numero" class="form-control" disabled>
                            </div>
                          </div>
                        </div>

                        <div class="border rounded px-3 mb-3 pb-3" id="div_events_inputs" style="display:none;"><!--inputs requeridos para crear sesión para evento-->
                          <center><b>Sesión para evento</b></center>
                          <div class="row mt-3">
                            <div class="form-group col-sm-12">
                              <label for="select_carrera">Selecciona el evento para asignar sesión</label>
                              <select name="select_evento" id="select_evento" class="form-control">
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="form-group col-sm-12 col-md-6">
                            <label for="inp_nombresesion">Nombre de la sesión:</label>
                            <input type="text" class="form-control" id="inp_nombresesion" name="inp_nombresesion" placeholder="Ingresa el nombre de la sesión">
                          </div>
                          <div class="form-group col-sm-12 col-md-6">
                            <label for="inp_idsesion">Id de la sesión:</label>
                            <input type="text" class="form-control" id="inp_idsesion" name="inp_idsesion" placeholder="Ingresa el id de la sesion">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inp_contrasenasesion">Contraseña de la sesión:</label>
                          <input type="text" class="form-control" id="inp_contrasenasesion" name="inp_contrasenasesion" placeholder="Ingresa la contraseña de la sesión">
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal editar sesion -->

      <!-- Footer -->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              © 2021 MONI <span class="d-none d-md-inline-block">IESM-UDC-TSU-CONACON TI</span>
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

      <!-- Sweet-Alert  -->
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

      <!--<script src="../assets/js/template/.min.js"></script>-->

      <!-- Tablas responsivas -->
      <script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
      <script src="../assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

      
      <script src="../assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.js"></script>

      <!-- Inicializador de tablas init js -->
      <script src="../assets/pages/datatables.init.class.js"></script>
      <!--<script src="../assets/js/template/sweetalert.min.js"></script>-->
      <script src="../assets/js/adminwebex/adminwebex.js"></script>
      <script src="../assets/js/controlescolar/directorio.js"></script>

      <script src="../assets/js/adminwebex/horas_docentes.js"></script>

      <script src="../assets/pages/sweet-alert.init.js"></script>
      <script src="../assets/pages/clipboard.js"></script>
      <script type="text/javascript">
        new ClipboardJS('.clpb', {
          text: function(trigger) {
              return trigger.getAttribute('aria-label');
          }
        });
        $(document).ready(function(){
          $("#sesion_type").val('clase');
          $("#tabla_seguimientos").DataTable({
              'pageLength': 5,
                  "lengthChange": false,
                  "info":     false
          }).columns.adjust()
          $(".moneyFt").maskMoney();
        })
        $(".page-title").children().on('click', function(){
          if(!$(this).hasClass('tab_active')){
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
      

     <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>
