<?php
date_default_timezone_set("America/Mexico_City");
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 3){
    header("Location: ../index.php");
    die();
}
  $usuario = $_SESSION["usuario"];
  $admin = $usuario['persona']['rol'] == 2 ? true : false;
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	  
	  <link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
    <style>
      .form-control{
        border: 1px solid #c8c8c8;
      }
      .nav-tabs .nav-link.disabled {
        background-color: #bfbcbc8f;
      }
      .autotd{
        white-space: nowrap;
        min-height:120px;
      }
      #subscriptions > tr:hover{
        cursor:pointer;
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
                  <li class="list-inline-item dropdown notification-list show">
                    <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light notification-icon-box" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-phone"></i> <span id="span_alert_llamadas" class="badge badge-xs"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg overflow-auto" style="max-height: 85vh;">
                        <li class="list-group" id="list-notification-llamada">
                            
                        </li>
                    </ul>
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
                  <?php if($admin): ?>
                  <li class="has-submenu">
                    <a href="gestorEventos.php"><i class="ion ion-md-calendar"></i> Gestor Eventos</a>
                  </li>
                  <?php endif ?>
                  <li class="has-submenu">
                    <a href="#" onclick="init_data()"><i class="fas fa-sync"></i> Actualizar</a>
                  </li>
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
        <div class="mx-4">

          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-12 mb-2">
                    <h4 class="page-title m-0"> Gestión de prospectos </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          

          <div class="row">
            <!-- SECCION PARA CONCENTRADO DE EVENTOS -->
            <div class="col-sm-12" id="tab_concentrado_eventos">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-8 col-md-4">
                      <h3>Buscador</h3>
                      <!-- <input type="text" id="input_search" class="form-control"> -->
                      <select id="input_search" class="form-control w-100" name="input_search" >
                      </select>
                      <!-- <div class="">
                        <table class="table table-sm" id="table-search">
                          <thead>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th></th>
                          </thead>
                          <tbody>
                            <tr>
                              <td>JESUS OCTAVIO PAJARO</td>
                              <td>pajaro.octavio96@gmail.com</td>
                              <td>2222222222</td>
                              <td><button class="btn btn-primary">Ver</button></td>
                            </tr>
                          </tbody>
                        </table>
                      </div> -->
                    </div>
                    <div class="col-sm-12 col-md-8">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-6">
                              <p class="mb-1"><b>Nombre</b></p>
                              <p class="mb-2 text-truncate" id="span_nombre"> - </p>
                              <p class="mb-1"><b>Correo</b></p>
                              <p class="mb-2 text-truncate" id="span_correo"> - </p>
                              <p class="mb-1"><b>Teléfono</b></p>
                              <p class="mb-1 text-truncate" id="span_telefono"> - </p>
                            </div>
                            <div class="col-6 table-responsive" style="overflow:auto;max-height: 190px;">
                              <table class="table table-striped mb-0 table-hover">
                                <thead>
                                  <th>Interés</th>
                                  <th>Atiende</th>
                                  <th>Estatus</th>
                                </thead>
                                <tbody id="subscriptions">

                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- CONTENEDOR DE TABS -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" id="home-tab" href="#home" role="tab" aria-controls="home"  aria-selected="false" data-target="#home">
                            <span class="d-block d-sm-none"><i class="fa fa-address-book"></i></span>
                            <span class="d-none d-sm-block">Prospectos</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="profile-tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true" data-target="#profile">
                            <span class="d-block d-sm-none"><i class="fa fa-address-card"></i></span>
                            <span class="d-none d-sm-block">Inscritos</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="otrosestatus-tab" href="#otrosestatus" role="tab" aria-controls="otrosestatus" aria-selected="true" data-target="#otrosestatus">
                            <span class="d-block d-sm-none"><i class="fa fa-address-card"></i></span>
                            <span class="d-none d-sm-block">Otros estatus</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="solicitudesClin-tab" href="#solicitudesClinicas" role="tab" aria-controls="solicitudesClinicas" aria-selected="true" data-target="#solicitudesClinicas" onclick="cargar_solicitudes_clinicas()">
                            <span class="d-block d-sm-none"><i class="fa fa-users"></i></span>
                            <span class="d-none d-sm-block">Verificación clínicas</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="linkdepago-tab" href="#linkdepago" role="tab" aria-controls="linkdepago" aria-selected="true" data-target="#linkdepago">
                            <span class="d-block d-sm-none"><i class="fa fa-link" aria-hidden="true"></i></span>
                            <span class="d-none d-sm-block">Link de pago</span>
                          </a>
                        </li>
                      </ul>

                      <!-- FIN CONTENEDOR DE TABS -->
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <div class="col-12">
                                <label for="listaralumnos">Seleccione evento / carrera / certificación</label>
                              </div>
                              <div class="col-12">
                                <select id="multiple_evento" class="js-example-basic-multiple w-100" name="states[]" multiple="multiple" style="display:none">
                                  
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <table id="tabla_general_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Fecha de registro</th>
                                    <th>Tipo</th>
                                    <th>Interés</th>
                                    <th>Atiende</th>
                                    <th>Operaciones</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div> <!-- FIN TAB HOME -->
                        <div class="row tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12">
                              <div class="col-12">
                                <label for="listaralumnos">Seleccione carrera / certificación</label>
                              </div>
                              <div class="col-12">
                                <select id="multiple_inscritos" class="js-example-basic-multiple w-100" name="carreras[]" multiple="multiple" style="display:none;width:100%;">
                                  
                                </select>
                              </div>
                            </div>
                          </div>
                          
                          <div class="table-responsive TBNR">
                            <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;" data-order='[[ 0, "desc" ]]'>
                              <thead>
                                <tr>
                                  <th>Nombre</th>
                                  <th>Contacto</th>
                                  <th>Resumen</th>
                                </tr>
                              </thead>
                              <tbody>
                                
                              </tbody>
                            </table>
                          </div> 
                        </div> <!-- FIN TAB PROFILE -->
                        <div class="row tab-pane fade" id="otrosestatus" role="tabpanel" aria-labelledby="otrosestatus-tab">
                          <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                              <div class="col-12">
                                <label for="listaralumnos">Seleccione carrera / certificación</label>
                              </div>
                              <div class="col-12">
                                <select id="multiple_otrosestatus" class="js-example-basic-multiple w-100" name="otros[]" multiple="multiple" style="display:none;width:100%;">
                                  
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                              <div class="col-12">
                                <label for="listaralumnos">Seleccione estatus a filtrar</label>
                              </div>
                              <div class="col-12">
                                <select id="multiple_estatus" class="js-example-basic-multiple w-100" name="estatus[]" multiple="multiple" style="display:none;width:100%;">
                                  <option value=""></option>
                                </select>
                              </div>
                            </div>
                            <div class="table-responsive TBNR">
                              <table id="listado_otros_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;" data-order='[[ 0, "desc" ]]'>
                                <thead>
                                  <tr>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Resumen</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div> <!-- FIN TAB OTROS ESTATUS -->
                        <div class="row tab-pane fade" id="solicitudesClinicas" role="tabpanel" aria-labelledby="solicitudesClin-tab">
                          <div class="table-responsive TBNR">
                            <table id="listado_solicitudes_clinicas" class="table table-striped table-bordered dt-responsive w-100" >
                              <thead>
                                <tr>
                                  <th>Clinica</th>
                                  <th>Estatus</th>
                                  <th>Fecha registro</th>
                                  <th>Ejecutiva</th>
                                  <th>Datos Generales</th>
                                </tr>
                              </thead>
                              <tbody>
                                
                              </tbody>
                            </table>
                          </div>
                        </div> <!-- FIN TAB SOLICITUDES CLINICAS -->
                        <div class="row tab-pane fade" id="linkdepago" role="tabpanel" aria-labelledby="linkdepago-tab">
                          <button id="mostrar_formulario_link" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-crear-link">Crear link de pago</button>
                        </div> <!-- FIN TAB LINK DE PAGO -->
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <!-- SECCION PARA CONCENTRADO DE Link-pago -->
            <div class="col-sm-12" id="tab_concentrado_link-pago" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <button id="mostrar_formulario_link" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-crear-link">Crear link de pago</button>
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">

                          </div>
                        </div>

                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div> <!-- end container-fluid -->

          <?php
            if ($admin): 
          ?>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">

                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="v-home-tab" data-toggle="tab" href="#v-home" role="tab" aria-controls="home" aria-selected="false" data-target="#v-home">
                        <span class="d-block d-sm-none"><i class="fas fa-user-plus"></i></span>
                        <span class="d-none d-sm-block">Registrar prospecto</span>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="v-profile-tab" data-toggle="tab" href="#v-profile" role="tab" aria-controls="profile" aria-selected="true" data-target="#v-profile">
                        <span class="d-block d-sm-none"><i class="fas fa-suitcase"></i></span>
                        <span class="d-none d-sm-block">Ejecutivas</span>
                      </a>
                    </li>

                    <!-- <li class="nav-item">
                      <a class="nav-link" id="potospectos-tab" data-toggle="tab" href="#prospectos" role="tab" aria-controls="profile" aria-selected="true" data-target="#prospectos">
                        <span class="d-block d-sm-none"><i class="fas fa-users"></i></span>
                        <span class="d-none d-sm-block">Prospectos</span>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="inscritos-tab" data-toggle="tab" href="#inscritos" role="tab" aria-controls="profile" aria-selected="true" data-target="#inscritos">
                        <span class="d-block d-sm-none"><i class="fas fa-address-book"></i></span>
                        <span class="d-none d-sm-block">Inscritos</span>
                      </a>
                    </li> -->
                  </ul>

                  <div class="tab-content bg-light">
    				        <!-- <h4>Total prospectos: <span id="conteo_prospectos"></span></h4>
                    <h4>Total inscritos: <span id="conteo_inscritos"></span></h4> -->
                    <div class="tab-pane fade show active" id="v-home" role="tabpanel" aria-labelledby="v-home-tab">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="page-title ">Registrar un prospecto</h4>
                          <form id="form_nuevo_prospecto">
                            <div class="row">
                              <div class="form-group col-sm-12 col-md-4">
                                <label>Nombre</label>
                                <input type="text" name="name" id="name" class="form-control special" required>
                              </div>
                              <div class="form-group col-sm-12 col-md-4">
                                <label>Apellido Paterno.</label>
                                <input type="text" name="paterno" id="paterno" class="form-control special" required>
                              </div>
                              <div class="form-group col-sm-12 col-md-4">
                                <label>Apellido Materno.</label>
                                <input type="text" name="materno" id="materno" class="form-control special" required>
                              </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-sm-6 col-md-4">
                                <label>Teléfono</label>
                                <input type="tel" name="telefono" id="telefono" class="form-control onlyNum" required maxlength="10" required>
                              </div>
                              <div class="form-group col-sm-6 col-md-4">
                                <label>Teléfono de casa</label>
                                <input type="tel" name="telefono_casa" id="telefono_casa" class="form-control onlyNum" placeholder="opcional" maxlength="10">
                              </div>
                              <div class="form-group col-sm-6 col-md-4">
                                <label>Teléfono para recados</label>
                                <input type="tel" name="telefono_recados" id="telefono_recados" class="form-control onlyNum" placeholder="opcional" maxlength="10">
                              </div>

                              <div class="form-group col-sm-12 col-md-2">
                                <label>Género.</label>
                                <select name="genero" id="genero" class="form-control">
                                  <option value="0">Definir después</option>
                                  <option value="1">Femenino</option>
                                  <option value="2">Masculino</option>
                                </select>
                              </div>
                              <div class="form-group col-sm-12 col-md-5">
                                <label>Correo.</label>
                                <input type="mail" name="email" id="email" class="form-control" required>
                              </div>
                              <div class="form-group col-sm-12 col-md-5">
                                <label>Nacionalidad.</label>
                                <select name="select_nacionalidad" id="select_nacionalidad" class="form-control">
                                </select>
                              </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-sm-12 col-md-6">
                                <label>Institución</label>
                                <select name="IDOrganizacion" id="IDOrganizacion" class="form-control only_event">
                                    <option value="0" selected="">Si pertenece a una asociación, elijala</option>
                                </select>
                              </div>
                              <div class="form-group col-sm-12 col-md-6">
                                <label>Codigo promocional.</label>
                                <input type="text" name="inp_codigo_pro" id="inp_codigo_pro" class="form-control">
                              </div>
                            </div>

                            <div class="border rounded px-3 pt-1 mb-2">
                              <p>En caso de que el prospecto cuente con una cédula profesional llene los siguientes datos.</p>
                              <div class="row">
                                <div class="form-group col-3">
                                  <label for="inp_titulo_estudio">Último grado de estudio</label>
                                  <!-- <input type="text" class="form-control" id="inp_titulo_estudio" name="inp_titulo_estudio"> -->
                                  <select name="inp_titulo_estudio" id="inp_titulo_estudio" class="form-control">
                                    <option value="" selected>SIN ASIGNAR</option>
                                    <option value="1">SECUNDARIA</option>
                                    <option value="2">BACHILLERATO</option>
                                    <option value="3">PREPARATORIA</option>
                                    <option value="4">TSU</option>
                                    <option value="5">LICENCIATURA</option>
                                    <option value="6">MAESTRÍA</option>
                                    <option value="8">DOCTORADO</option>
                                  </select>
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="escuela_proc">Escuela de procedencia</label>
                                  <input type="text" name="escuela_proc" id="escuela_proc" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="escuela_proc">Fecha de egreso</label>
                                  <input type="date" name="fecha_egreso" id="fecha_egreso" class="form-control">
                                </div>
                                <div class="form-group col-3">
                                  <label for="inp_cedula">Cedula profesional</label>
                                  <input type="text" class="form-control" id="inp_cedula" name="inp_cedula">
                                </div>
                              </div>
                            </div>

                            <h5 class="page-title">Seguimiento del prospecto</h5>
                            <div class="row bg-muted">
                              <div class="form-group col-sm-12 col-md-6 mt-2">
                                <label>Tipo de Prospecto</label>
                                <select class="form-control" required name="tipo_prospecto" id="tipo_prospecto">
                                    <option selected disabled>Seleccione Tipo de prospecto</option>
                                    <option value="evento">Evento</option>
                                    <option value="carrera">Carrera</option>
                                </select>
                              </div>

                              <div class="form-group col-sm-12 col-md-6 mt-2">
                                <label>Destino</label>
                                <select class="form-control" required name="id_destino" id="id_destino">
                                    
                                </select>
                              </div>

                              <div class="form-group col-sm-12 col-md-6 mt-2" style="display:none">
                                <label>Tipo de alumno</label>
                                <select class="form-control" required name="tipo_alumno" id="tipo_alumno" disabled>
                                    
                                </select>
                              </div>

                              <div class="form-group col-12 col-md-6 mt-2">
                                <label>Quién atenderá al prospecto?</label>
                                <select class="form-control" required name="n_prosp_personaMk" id="n_prosp_personaMk">
                                    <option selected disabled>Seleccione a una persona para der seguimiento</option>
                                </select>
                              </div>
                              <div class="form-group col-12 col-md-6 mt-2">
                                <label>Tipo de moneda</label>
                                <select class="form-control" required name="tipo_moneda_prospecto" id="tipo_moneda_prospecto">
                                    <option value="1" selected>MXN</option>
                                    <option value="2">USD</option>
                                </select>
                              </div>
                            </div>

                            <div class="row mt-4">
                              <div class="col-4 ml-auto">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <button type="button" class="btn btn-secondary" onclick="$('#form_nuevo_prospecto')[0].reset()">Cancelar</button>
                              </div>
                              
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane fade show" id="v-profile" role="tabpanel" aria-labelledby="v-profile-tab">
                      <div class="table-responsive">
                        <div class="card">
                          <div class="card-body">
                            <table class="table table-striped table-bordered nowrap dt-responsive" id="tabla_ejecutivas" style="width: 100%;">
                              <thead>
                                <th>Nombre</th>
                                <!--<th>Correo</th>-->
                                <th>Prospectos en fila</th>
                                <th></th>
                              </thead>
                              <tbody>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

    				        <div class="tab-pane fade show" id="prospectos" role="tabpanel" aria-labelledby="prospectos-tab">
                      <div class="card">
                        <div class="card-body TBNR table-responsive">
                          <table class="table table-striped table-bordered nowrap dt-responsive" id="tabla_prospectos" style="width: 100%;" data-order='[[ 3, "desc" ]]'>
                            <thead>
                              <th>Nombre</th>
                              <th>Correo</th>
                              <th>Teléfono</th>
                              <th>Fecha</th>
                              <th>Interés</th>
                              <th>Ejecutiva</th>
                              <th>Operaciones</th>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
    				
    		        		<div class="tab-pane fade show" id="inscritos" role="tabpanel" aria-labelledby="inscritos-tab">
                      <div class="card">
                        <div class="card-body TBNR table-responsive">
                          <table class="table table-striped table-bordered nowrap dt-responsive" id="tabla_inscritos" style="width: 100%;">
                            <thead>
                            <th>Nombre</th>
                              <th>Correo</th>
                              <th>Teléfono</th>
                              <th>Fecha</th>
                              <th>Interés</th>
                              <th>Ejecutiva</th>
                              <th>Operaciones</th>
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
            </div><!--Fin card Body-->
          </div><!--Fin card-->
            
          <?php endif ?>
        </div>
      </div>

      <!-- end wrapper -->

      <!-- todos los modal -->
        <div class="modal fade" id="modalConfirmaAsist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Confirmar asistencia</h5>
              </div>
              <div class="modal-body">
                <h3>Desea confirmar la asistencia de <span id="spanAsist"></span>?</h3>
                <form id="confirmar_asistencia">
                  <input type="hidden" name="id_interes" id="id_interes">
                  <input type="hidden" name="id_asistente" id="id_asistente">
                  <div class="row">
                    <div class="col-sm-6"><button type="button" class="btn btn-danger" onclick='$("#modalConfirmaAsist").modal("hide");'>Cancelar</button></div>
                    <div class="col-sm-6"><button type="submit" class="btn btn-success">Confirmar</button></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!--  Modal content for the above example -->
        <div class="modal fade modal-crear-link" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title m-0" id="myLargeModalLabel">Crear link de pago</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                      <div class="card">
                        <div class="card-body">
                            <h4 class="m-t-0 m-b-30">Ingresa la siguiente información</h4>
                            <form id="" method="GET" action="linkpago.php" target="_blank">
                                <div class="form-group row">
                                    <label for="id_concepto" class="col-sm-3 control-label">Selecciona la cuenta de depósito</label>
                                    <div class="col-sm-9">
                                      <select class="form-control" name="id_concepto" id="id_concepto" required>
                                        <option value="" selected>Seleccione...</option>
                                        <option value="30">Universidad del conde (Banorte)</option>
                                        <option value="24">Colegio nacional de consejeros  (Inbursa)</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nombrelink" class="col-sm-3 control-label">Nombre del producto</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombrelink" name="nombre_concepto" placeholder="Ingresa el nombre del concepto" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="precio" class="col-sm-3 control-label">Precio (USD)</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio del concepto" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nombreclientespei" class="col-sm-3 control-label">Nombre cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombreclientespei" name="nombreclientespei" placeholder="Ingresa el nombre del cliente" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="emailclientespei" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="emailclientespei" name="emailclientespei" placeholder="Ingresa el email del cliente" required>
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Crear link</button>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- card-body -->
                    </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
        <div class="modal fade" id="modalRechazarAsist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Rechazar asistencia</h5>
              </div>
              <div class="modal-body">
              <h3>Desea <b>rechazar</b> la asistencia de <span id="spanAsistR"></span>?</h3>
              <form id="rechazar_asistencia">
                <input type="hidden" name="id_interesRechazo" id="id_interesRechazo">
                <input type="hidden" name="id_asistenteRechazo" id="id_asistenteRechazo">
                <div class="row">
                  <div class="col-sm-6"><button type="button" class="btn btn-danger" onclick='$("#modalRechazarAsist").modal("hide");'>Cancelar</button></div>
                  <div class="col-sm-6"><button type="submit" class="btn btn-success">Confirmar</button></div>
                </div>
              </form>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_detalles_pagos" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Pagos Realizados <span id="total_pagos_pr" class="text-success ml-5"></span></h5>
              </div>
                <h5 class="mx-auto mb-0"><b id="lbl_persona_pago_nom"></b></h5>
              <div class="modal-body" id="list_pagos_realizados">
                <div class="card">
                  <div class="card-heading p-2">
                    <div>
                      <p class="text-muted mb-0 mt-2"><b>Concepto: </b><span class="float-right">ACC-GENERAL</span></p>
                      <p class="mt-2 mb-0 text-muted"><b>Folio: </b><span class="float-right">00753753LL958143H</span></p>
                      <h4 class="text-success"><small><b>8 Agosto 21</b></small> <span class="float-right">$3,000.00</span></h4>
                    </div>
                  </div>
                </div>
              
              </div>
              <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_seguimiento" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h5 class="modal-title">Registro de seguimiento</h5>
              </div>
              
              <h3 class="mb-0"><b id="lbl_persona_seguimiento"></b></h3>
              <h4 class="mb-3"><b id="lbl_interes_seguimiento"></b></h4>
              
              <div class="modal-body pt-0">
                
                <div class="col-sm-12">
                  <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                          <a class="nav-link active" id="tab-comentarios" data-toggle="tab" href="#comentarios_t" role="tab" aria-controls="comentarios_t" aria-selected="true">
                              <span class="d-block d-sm-none"><i class="fas fa-comments"></i></span>
                              <span class="d-none d-sm-block">Comentarios</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" id="tab-llamadas" data-toggle="tab" href="#llamadas_t" role="tab" aria-controls="llamadas_t" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-phone"></i></span>
                              <span class="d-none d-sm-block">Llamadas</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" id="tab-informacion" data-toggle="tab" href="#informacion_t" role="tab" aria-controls="informacion_t" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-user-edit"></i></span>
                              <span class="d-none d-sm-block">Información</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" id="tab-estatus_pr" data-toggle="tab" href="#estatus_pr_t" role="tab" aria-controls="estatus_pr_t" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-check-square"></i></span>
                              <span class="d-none d-sm-block">Estatus</span>
                          </a>
                      </li>
                      <?php if($admin): ?>
                      <li class="nav-item">
                          <a class="nav-link" id="tab-reasignacion" data-toggle="tab" href="#reasignacion_t" role="tab" aria-controls="reasignacion_t" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-retweet"></i></span>
                              <span class="d-none d-sm-block">Reasignación</span>
                          </a>
                      </li>
                      <?php endif; ?>

                  </ul>
                  <div class="tab-content bg-light">
                    <div class="tab-pane fade active show" id="comentarios_t" role="tabpanel" aria-labelledby="tab-comentarios"> <!-- tab de comentarios -->
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="card m-b-30">
                              <h5 class="card-header mt-0">Última actualizacion: <i id="detalle_fecha_comment"><!-- 2021/08/18 --></i></h5>
                              <div class="card-body">
                                  <p class="card-text" id="detalle_ult_comment"> <!-- info --> </p>
                                  <a href="#" class="btn btn-primary" title="Agregar un nuevo comentario del seguimiento" id="btn_agregar_comentario"><i class="fas fa-plus"></i></a>
                                  <!-- <a href="#" class="btn btn-info"><i class="fas fa-history"></i></a> -->
                                  <!-- <a href="#" class="btn btn-info"><i class="fas fa-user-clock"></i></a> --> <!-- boton para agendar llamada -->
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-12" style="display:none;" id="container_form_comment">
                            <form id="form-comentario">
                              <input type="hidden" name="id_atencion" id="id_atencion"> <!-- input del id a insertar comentario -->
                              <div class="row">
                                <div class="col-12">
                                  <div class="form-group">
                                    <label >Comentario</label>
                                    <textarea class="form-control" name="inp_comentario" id="inp_comentario" required=""></textarea>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="form-group">
                                    <input type="text" disabled class="form-control" value="<?php echo(date("Y-m-d"));?>">
                                  </div>
                                </div>
                                <div class="col-6">
                                  <button type="submit" class="btn btn-success">Continuar</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>

                        <div id="accordion-test-2" class="card-box">
                          <div class="card">
                              <div class="card-header bg-secondary p-1" id="headingTwo">
                                  <h5 class="m-0 px-auto card-title">
                                    <a href="" data-toggle="collapse" data-target="#collapseTwo-2" aria-expanded="true" aria-controls="collapseTwo-2" class="">
                                        Historial de seguimiento
                                    </a>
                                  </h5>
                              </div>
                              <div id="collapseTwo-2" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion-test-2" style="">
                                  <div class="card-body">
                                      <table class="table table-striped table-bordered" id="tabla_seguimientos" style="width: 100%;">
                                        <thead>
                                          <th>Fecha</th>
                                          <th>Comentario</th>
                                        </thead>
                                        <tbody>
                                          
                                        </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                        </div>
                    </div> <!-- fin tab de comentarios -->

                    <div class="tab-pane fade" id="llamadas_t" role="tabpanel" aria-labelledby="tab-llamadas">
                      <div class="row">
                        <div class="col-sm-8 TBNR">
                          <table class="table table-striped table-bordered" id="tabla_seguimientos_llamadas" style="width: 100%;" data-order='[[ 0, "desc" ]]'>
                            <thead>
                              <th>Fecha</th>
                              <th>Comentario</th>
                              <th>Estatus</th>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
                        </div>
                        <div class="col-sm-4 border rounded">
                          <h3>Agendar una llamada</h3>
                          <form id="form_agendar_llamada">
                            <input type="hidden" name="prospecto_llamar" id="prospecto_llamar">
                            <div class="row">
                              <div class="col-sm-12 col-md-6 mb-2">
                                <label>Fecha para llamar</label>
                                <input type="date" name="fecha_llamada" class="form-control" required>
                              </div>
                              <div class="col-sm-12 col-md-6 mb-2">
                                <label>Hora para llamada</label>
                                <input type="time" name="hora_llamada" class="form-control" required>
                              </div>
                              <div class="col-sm-12 mb-2">
                                <button class="btn btn-primary" type="submit">Agendar</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="informacion_t" role="tabpanel" aria-labelledby="tab-informacion">
                      <form id="form-actualizar-prospecto">
                        <input type="hidden" name="inp_prospect_edit" id="inp_prospect_edit_ej">
                        <div class="row">
                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Nombre</label>
                            <input type="text" name="edit_pr_nombre" id="edit_pr_nombre_ej" class="form-control special">
                          </div>
                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Apellido paterno</label>
                            <input type="text" name="edit_pr_apaterno" id="edit_pr_apaterno_ej" class="form-control special">
                          </div>
                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Apellido materno</label>
                            <input type="text" name="edit_pr_amaterno" id="edit_pr_amaterno_ej" class="form-control special">
                          </div>

                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Teléfono</label>
                            <input type="tel" name="edit_pr_telefono" id="edit_pr_telefono_ej" class="form-control onlyNum" maxlength="10" minlength="10">
                          </div>
                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Teléfono de casa</label>
                            <input type="tel" name="edit_pr_telefono_c" id="edit_pr_telefono_c_ej" class="form-control onlyNum" maxlength="10" minlength="10">
                          </div>
                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Teléfono de recados</label>
                            <input type="tel" name="edit_pr_telefono_rec" id="edit_pr_telefono_rec_ej" class="form-control onlyNum" maxlength="10" minlength="10">
                          </div>

                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Género</label>
                            <select name="edit_genero" id="edit_genero" class="form-control">
                              <option value="0">Definir después</option>
                              <option value="1">Femenino</option>
                              <option value="2">Masculino</option>
                            </select>
                          </div>
                          <div class="col-sm-12 col-md-4 form-group">
                            <label>Correo</label>
                            <input type="mail" name="edit_pr_correo" id="edit_pr_correo_ej" class="form-control">
                          </div>
                          <div class="col-sm-12 col-md-4 form-group">
                            <label for="select_edit_nacionalidad">Nacionalidad</label>
                            <select name="select_edit_nacionalidad" class="form-control" id="select_edit_nacionalidad">
                            </select>
                          </div>
        
                          <div class="col-sm-12 col-md-6 form-group">
                            <label>Institución</label>
                            <select name="edit_pr_institucion" id="edit_pr_institucion_ej" class="form-control only_event">
                            </select>
                            <input id="only_view_inst" class="form-control d-none" type="text" disabled readonly title="Esta persona tiene asignada una clínica por parte del responsable de la misma, por lo que su modificación no está permitida">
                          </div>
                          <input type="hidden" id="tmp_tipomoneda">
                          <div class="col-sm-12 col-md-6 form-group">
                            <label>Tipo de moneda</label>
                            <select name="editar_tipo_moneda" id="editar_tipo_moneda" class="form-control">
                              <option value="1">MXN</option>
                              <option value="2">USD</option>
                            </select>
                          </div>

                          <div class="col">
                            <div class="border rounded px-3 pt-1 mb-2">
                              <div class="row">
                                <div class="form-group col-3">
                                  <label for="inp_edit_titulo_estudio">Último grado de estudio</label>
                                  <select name="inp_edit_titulo_estudio" id="inp_edit_titulo_estudio" class="form-control">
                                    <option value="" selected>SIN ASIGNAR</option>
                                    <option value="1">SECUNDARIA</option>
                                    <option value="2">BACHILLERATO</option>
                                    <option value="3">PREPARATORIA</option>
                                    <option value="4">TSU</option>
                                    <option value="5">LICENCIATURA</option>
                                    <option value="6">MAESTRÍA</option>
                                    <option value="8">DOCTORADO</option>
                                  </select>
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="edit_escuela_proc">Escuela de procedencia</label>
                                  <input type="text" name="edit_escuela_proc" id="edit_escuela_proc" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="edit_fecha_egreso">Fecha de egreso</label>
                                  <input type="date" name="edit_fecha_egreso" id="edit_fecha_egreso" class="form-control">
                                </div>
                                <div class="form-group col-3">
                                  <label for="inp_edit_cedula">Cedula profesional</label>
                                  <input type="text" class="form-control" id="inp_edit_cedula" name="inp_edit_cedula">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-4">Guardar cambios</button>
                      </form>
                    </div>

                    <div class="tab-pane fade" id="estatus_pr_t" role="tabpanel" aria-labelledby="tab-estatus_pr">
                      <div class="row">
                        <div class="col-sm-12 col-md-6">
                          <div class="border rounded p-2">
                            <h3>Estatus del seguimiento</h3>
                            <input type="hidden" id="current_prosp_stat">
                            <select class="form-control" name="select_estat_seguimiento" id="select_estat_seguimiento">
                              <option value="0">Pendiente</option>
                              <option value="1">Espera</option>
                              <option value="2">Confirmado</option>
                              <option value="3">Rechazado</option>
                              <option value="4">No está interesado</option>
                            </select>
                            <button class="btn btn-success btn-block mt-4" id="btn_change_estatus_prosp" disabled>Guardar cambios</button>
                          </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6">
                          <div class="border rounded p-2">
                            <h3>Apuntar para otro producto</h3>
                            <select class="form-control" id="tipo_nuevo_interes">
                              <option value="" disabled selected>Seleccione una opción</option>
                              <option value="evento">Evento</option>
                              <option value="carrera">Carrera</option>
                            </select>
  
                            <div class="d-none">
                              <h3>Seleccione producto</h3>
                              <select class="form-control" id="id_nuevo_interes">
                                
                              </select>
                            </div>
                            <button class="btn btn-success btn-block mt-4" disabled id="btn_add_atencion">Guardar cambios</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php if($admin): ?>
                    <div class="tab-pane fade" id="reasignacion_t" role="tabpanel" aria-labelledby="tab-reasignacion">
                      <div class="row">
                        <div class="col-sm-12 ">
                          <form id="form_cambio_prospecto">
                            <input type="hidden" name="inp_prospect" id="inp_prospect">
                            <div class="row">
                              <div class="col-sm-12 form-group">
                                <h4>Cambiar ejecutiva que atiende</h4>
                                <select class="form-control" id="change_ejecutiva" name="change_ejecutiva">
                                  
                                </select>
                              </div>

                            </div>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_comentario" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h5 class="modal-title">Agregar comentario</h5>
              </div>
              
              <!-- <h5 class="mx-auto mb-0"><b id="lbl_persona_seguimiento"></b></h5> -->
              <!-- <h4 class="mx-auto mb-0"><b id="lbl_interes_seguimiento"></b></h4> -->
              
              <div class="modal-body pt-0">
                <form id="form-comentario">
                  <input type="hidden" name="id_atencion" id="id_atencion"> <!-- input del id a insertar comentario -->
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label >Comentario</label>
                        <textarea class="form-control" name="inp_comentario" id="inp_comentario" required=""></textarea>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <input type="text" disabled class="form-control" value="<?php echo(date("Y-m-d"));?>">
                      </div>
                    </div>
                    <div class="col-6">
                      <button type="submit" class="btn btn-success">Continuar</button>
                    </div>
                  </div>
                </form>
              
              </div>

              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_registrar_pago" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h4 class="modal-title">Pagos</h4>
              </div>
              
              <h3 class=""><b id="lbl_persona_pago"></b></h3>
              
              <div class="modal-body pt-0">
                <div class="col-sm-12">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab_registrar_pag" data-toggle="tab" href="#registrar_pag_pan" role="tab" aria-controls="registrar_pag_pan" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fa fa-credit-card"></i></span>
                            <span class="d-none d-sm-block">Registrar</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="tab_becas_pag" data-toggle="tab" href="#becas_pag_pan" role="tab" aria-controls="becas_pag_pan" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                            <span class="d-none d-sm-block">Promos / Becas</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" id="tab_notificados_pag" data-toggle="tab" href="#notificados_pag_pan" role="tab" aria-controls="notificados_pag_pan" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fa fa-folder-open"></i></span>
                            <span class="d-none d-sm-block">Notificados</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_generacion_pag" data-toggle="tab" href="#generaciones_pag_pan" role="tab" aria-controls="generaciones_pag_pan" aria-selected="true" style="display:none;">
                            <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                            <span class="d-none d-sm-block">Generaciones</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_ver_plan_pagos" href="#" style="display:none;">
                            <span class="d-block d-sm-none"><i class="fa fa-file"></i></span>
                            <span class="d-none d-sm-block">Ver plan de pagos</span>
                        </a>
                    </li>
                  </ul>
                  <div class="tab-content bg-light">
                    <div class="tab-pane fade active show" id="registrar_pag_pan" role="tabpanel" aria-labelledby="tab_registrar_pag">
                      <h3 class=""><b id="lbl_persona_pago"></b></h3>
                      <div>
                        <label class="ckbox">
                          <input type="checkbox" id="check_solo_inscripciones">
                          <span>Mostrar solo conceptos de inscripciones</span>
                        </label>
                      </div>
                      <form id="form_registrar_pago">
                        <input type="hidden" name="person_pago" id="person_pago"> <!-- input del id a insertar -->
                        <input type="hidden" name="evento_pago" id="evento_pago"> <!-- input del id evento -->
                        
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group">
                                <label>Concepto a pagar</label>
                                <select id="tipo_pago" name="tipo_pago" class="form-control form-select" onchange="set_concepto(this)" required>
                                  
                                </select>
                            </div>
                            <div id="notifica_parcialidades" class="col-12">
                            </div>
                          </div>

                          <div class="form-group col-sm-12">
                            <label for="inp_promos_disp">Seleccione una promoción</label>
                            <select class="form-control" name="inp_promos_disp" id="inp_promos_disp">
                              <option value="">Seleccione una promocion</option>
                            </select>
                          </div>
                          <div class="form-group col-sm-12">
                            <label for="metododepago1">Seleccione cómo realizó el pago</label>
                            <select class="form-control" name="metodo_de_pago_1" id="metododepago1" required>
                              <option value="" disabled selected>Seleccione un método de pago</option>
                              <option value="1">Pago en cuenta referenciada</option>
                              <option value="2">Pago en ventanilla cuenta general</option>
                              <option value="4">Pago en cajero automático</option>
                              <option value="5">Pago en en departamento de cobranza</option>
                              <option value="6">Transferencia eletrónica</option>
                            </select>
                          </div>
                          <div class="form-group col-sm-12" style="display:none" id="mostrarmetododepago">
                            <label for="metododepago">Seleccione el método de pago</label>
                            <select class="form-control" name="metodo_de_pago" id="metododepago" required>
                              <option id="noselect" value="" disabled selected>Seleccione una forma de pago</option>
                              <option id="pagoenefectivo" style="display:none" value="Pago en efectivo">Pago en efectivo</option>
                              <option id="chechenominativo" style="display:none" value="Cheque nominativo">Cheque nominativo</option>
                              <option id="tarjetadecredito" style="display:none" value="Tarjeta de crédito">Tarjeta de crédito</option>
                              <option id="tarjetadedebito" style="display:none" value="Tarjeta de débito">Tarjeta de débito</option>
                              <option id="transferenciaelectronica" style="display:none" value="Transferencia eletrónica">Transferencia electrónica</option>
                              <option id="paypal" style="display:none" value="Paypal">Paypal</option>
                            </select>
                          </div>
                          <div class="form-group col-sm-12">
                            <label for="crearbancodedeposito">En que banco realizó el pago</label>
                            <select class="form-control" name="crearbancodedeposito" id="crearbancodedeposito" required>
                            <option disabled required>Seleccione...</option>
                            <option value="Banorte 0823622605">Banorte 0823622605</option>
                            <option value="Inbursa 50060654011">Inbursa 50060654011</option>
                            <option value="No aplica">No aplica</option>
                            </select>
                          </div>
                          <div id="notifica_fechap" class="col-12">

                          </div>

                          <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                              <label>Fecha pago</label>
                              <input type="date" name="inp_fecha_pago" id="inp_fecha_pago" class="form-control" required="" max="<?php echo(date('Y-m-d')); ?>" value="<?php echo(date('Y-m-d')); ?>">
                            </div>
                          </div>
                          <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                              <label>Monto por pagar</label>
                              <input type="tel" id="inp_monto_pago_spect" class="form-control" disabled >
                            </div>  
                            <div class="form-group">
                              <label>Monto reportado <span id="tipomonedausdmontomkt"></span> </label>
                              <small class="text-info">Capture el monto del pago que reportó el alumno</small>
                              <input type="tel" name="inp_monto_pago" id="inp_monto_pago" class="form-control moneyFt" data-prefix="$ " value="$ 0.00" required="" >
                            </div>
                          </div>
                          <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Comprobante</label>
                                <input type="file" class="filestyle" data-buttonname="btn-secondary" name="inp_comprobante_pago" id="inp_comprobante_pago" accept="image/*,application/pdf" required>
                            </div>
                          </div>
                          <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>folio / n° autorización</label>
                                <input type="text" class="form-control" name="inp_folio_pago" id="inp_folio_pago" required="">
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea class="form-control" name="comentario_callcenter" id="comentario_callcenter" rows="3"></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3 ml-auto">
                            <button type="button" class="btn btn-success" id="generar_ficha_pago_oxxo">Generar ficha</button>
                          </div>
                          <div class="col-sm-3 ml-auto" id="inp_button_spei">
                            
                          </div>
                          <div class="col-sm-3 ml-auto">
                            <button type="submit" class="btn btn-success">Continuar</button>
                          </div>
                        </div>
                      </form>
                      <div id="container_ficha_spei"></div>
                      <div id="mostrar_ficha" contenteditable="true" style="display:none">
                        <div class="opps" id="foto_ficha_oxxo">
                          <div class="opps-header">
                            <div class="opps-reminder">
                              Ficha digital. No es necesario imprimir.
                            </div>
                            <div class="opps-info">
                              <div class="opps-brand">
                                <img src="../assets/images/oxxopay_brand.png" alt="OXXOPay">
                              </div>
                              <div class="opps-ammount">
                                <h3>Monto a pagar</h3>
                                <h2> <span id="monto_pago"></span> <sup id="tipo_moneda">MXN</sup></h2>
                                <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
                              </div>
                            </div>
                            <div class="opps-reference">
                              <h3>REFERENCIA:</h3>
                              <h1 id="referencia_pago">0000-0000-0000-00</h1>
                            </div>
                            <br>
                            <div>
                              <img id="codigo_barras-reference" src="" alt="">
                            </div>
                            <div class="opps-reference">
                              <h5 id="concepto_de_pago">Inscripción</h5>
                            </div>
                          </div>
                          <div class="opps-instructions">
                              <h3>Instrucciones</h3>
                              <ol>
                                <li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
                                <li>Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
                                <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamente en la pantalla de venta.</li>
                                <li>Realiza el pago correspondiente con dinero en efectivo.</li>
                                <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
                              </ol>
                            <!-- <div class="opps-footnote">
                              Al completar estos pasos recibirás un correo de <strong>notificaciones@conacon.org</strong> confirmando tu pago.
                            </div> -->
                          </div>
                        </div>
                      </div><!--- fin de mostrar ficha -->
                      <form action="descargar_ficha_oxxo.php" method="post" target="_blank">
                        <input type="hidden" name="tipo_moneda_ficha" id="tipo_moneda_ficha">
                        <input type="hidden" name="nombre_concepto_ficha" id="nombre_concepto_ficha">
                        <input type="hidden" name="monto_pago_ficha" id="monto_pago_ficha">
                        <input type="hidden" name="referencia_ficha" id="referencia_ficha">
                        <input type="hidden" name="bar_code_ficha" id="bar_code_ficha">
                        <input type="hidden" name="monto_recargo_ficha" id="monto_recargo_ficha">
                        <button type="submit" class="btn btn-success" id="btnSave2">Descargar ficha</button>
                      </form>
                    </div>

                    <!-- <div class="tab-pane fade" id="becas_pag_pan" role="tabpanel" aria-labelledby="tab_becas_pag">
                      <h3>Becas / Promociones disponibles</h3>
                    </div> -->
                    
                    <div class="tab-pane fade" id="notificados_pag_pan" role="tabpanel" aria-labelledby="tab_notificados_pag">
                      <h3>Pagos notificados</h3>
                      <div class="table-responsive TBNR">
                        <table id="tabla_pagos_notificados" class="table w-100">
                          <thead>
                            <tr>
                              <th>Fecha</th>
                              <th>Concepto</th>
                              <th>Monto</th>
                              <th>Comprobante</th>
                              <th>Detalles</th>
                              <th>Estatus</th>
                              <th>Observación</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="generaciones_pag_pan" role="tabpanel" aria-labelledby="tab_generacion_pag">
                      <h3>Asignar Generación</h3>
                      <form id="form_asignar_generacion">
                        <div class="row">
                          <input type="hidden" name="alumno_generacion" id="alumno_generacion">
                          <div class="col-12 mb-4">
                            <label for="select_alumno_gen">Seleccion la generación en la que el alumno entrará</label>
                            <select name="select_alumno_gen" id="select_alumno_gen" class="form-control"></select>
                          </div>
                          <div class="col-12 mb-4">
                            <button class="btn btn-success btn-block">Confirmar asignación</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                
              
              </div>

              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
		
		    <div class="modal fade" id="modal_seguimiento_gral" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Seguimiento a prospectos</h4>
              </div>
              
              <div class="modal-body">
                <h3 id="lbl_prospecto_cambio" class="light"></h3>

                <div class="col-sm-12">
                  <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                          <a class="nav-link active" id="tab-seguimientos" data-toggle="tab" href="#seguimientos_t" role="tab" aria-controls="seguimientos_t" aria-selected="true">
                              <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                              <span class="d-none d-sm-block">Seguimiento</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" id="tab-edit-prospecto" data-toggle="tab" href="#edit_prospecto_t" role="tab" aria-controls="edit_prospecto_t" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-user-edit"></i></span>
                              <span class="d-none d-sm-block">Información</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" id="tab-reasinacion" data-toggle="tab" href="#reasingacion_t" role="tab" aria-controls="reasingacion_t" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-exchange-alt"></i></span>
                              <span class="d-none d-sm-block">Reasignación</span>
                          </a>
                      </li>
                      <!-- <li class="nav-item">
                          <a class="nav-link" id="tab-pagos" data-toggle="tab" href="#adm_pagos_t" role="tab" aria-controls="adm_pagos_t" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-coins"></i></span>
                              <span class="d-none d-sm-block">Pagos</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" id="tab-pagos_agregar" data-toggle="tab" href="#adm_pagos_agregar" role="tab" aria-controls="adm_pagos_agregar" aria-selected="false">
                              <span class="d-block d-sm-none"><i class="fas fa-coins"></i></span>
                              <span class="d-none d-sm-block">Registrar pago</span>
                          </a>
                      </li> -->

                  </ul>
                  <div class="tab-content bg-light">
                    <div class="tab-pane fade active show" id="seguimientos_t" role="tabpanel" aria-labelledby="tab-seguimientos">
                        <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                          <table id="tabla_comentarios" class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; width: 100%;"  data-order='[[ 0, "desc" ]]'>
                            <thead>
                              <tr>
                                <th>fecha y hora</th>
                                <th>Comentario</th>
                                <th>Ejecutiva que comentó</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="edit_prospecto_t" role="tabpanel" aria-labelledby="tab-edit-prospecto">
                      <form id="editar_prospecto">
                        <input type="hidden" name="inp_prospect_edit" id="inp_prospect_edit">
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label>Nombre</label>
                            <input type="text" name="edit_pr_nombre" id="edit_pr_nombre" class="form-control special">
                          </div>
                          <div class="col-sm-12 col-md-6 form-group">
                            <label>Apellido paterno</label>
                            <input type="text" name="edit_pr_apaterno" id="edit_pr_apaterno" class="form-control special">
                          </div>
                          <div class="col-sm-12 col-md-6 form-group">
                            <label>Apellido materno</label>
                            <input type="text" name="edit_pr_amaterno" id="edit_pr_amaterno" class="form-control special">
                          </div>
                          <div class="col-sm-12 col-md-6 form-group">
                            <label>Teléfono</label>
                            <input type="tel" name="edit_pr_telefono" id="edit_pr_telefono" class="form-control onlyNum" maxlength="10" minlength="10">
                          </div>
                          <div class="col-sm-12 col-md-6 form-group">
                            <label>Correo</label>
                            <input type="mail" name="edit_pr_correo" id="edit_pr_correo" class="form-control">
                          </div>

                          <div class="col-sm-12 col-md-6 form-group ">
                            <label for="edit_pr_curp" id="edit_pr_curp_lbl">CURP</label>
                            <input type="Text" name="edit_pr_curp" id="edit_pr_curp" maxlength="18" class="form-control d-none">
                          </div>

                          <div class="col-sm-12 col-md-6 form-group">
                            <label>Institución</label>
                            <select name="edit_pr_institucion" id="edit_pr_institucion" class="form-control only_event">
                
                            </select>
                          </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                      </form>
                    </div>
                    <div class="tab-pane fade" id="reasingacion_t" role="tabpanel" aria-labelledby="tab-reasinacion">
                      <!-- <form id="form_cambio_prospecto">
                        <input type="hidden" name="inp_prospect" id="inp_prospect">
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <h4>Cambiar ejecutiva que atiende</h4>
                            <select class="form-control" id="change_ejecutiva" name="change_ejecutiva">
                              
                            </select>
                          </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                      </form> -->
                    </div>  
                  </div>
                </div>
              
              </div>

              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalCambiarDestino" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="label_cambio"></h3>
              </div>
              <form id="form-cambio">
                <input type="hidden" name="reg_id" id="reg_id">
                <input type="hidden" name="dest_t" id="dest_t">
                <div class="modal-body">
                  <h5 id="label_cambio_2"></h5>
                  <select name="select_cambio_d" id="select_cambio_d" class="form-control">
                  </select>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                  </button>
                  <button type="submit" class="btn btn-primary">Continuar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalVerificarClinica" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="label_cambio">Validar información de clinica</h3>
              </div>
              <div class="modal-body">
                <div id="space-for-alert"></div>
                <div class="row">
                  <div class="col-8 offset-4" id="conainer_folio">
                      <label for="">Folio</label>
                      <input type="text" readonly id="inp_ver_folio" class="form-control">
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col">
                    <label for="inp_ver_nombre_clinica">Clínica</label>
                    <input type="text" class="form-control" id="inp_ver_nombre_clinica" readonly>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col">
                    <label for="">Responsable</label>
                    <input type="text" class="form-control" id="inp_ver_nombre_responsable" readonly>
                  </div>
                  <div class="col">
                    <label for="">Teléfono</label>
                    <input type="text" class="form-control" id="inp_ver_telefono" readonly>
                  </div>
                  <div class="col">
                    <label for="">Correo</label>
                    <input type="text" class="form-control" id="inp_ver_correo" readonly>
                  </div>
                  <div class="col-12 mt-3">
                    <label for="">Agregue un comentario</label>
                    <textarea id="comentario_verificacion" rows="3" placeholder="(opcional)" class="form-control"></textarea>
                  </div>
                </div>
                <input type="hidden" id="clinica_verify">
                <div class="row mt-4">
                  <div class="col text-right" id="buttons_clinic">
                    <button class="btn btn-success" onclick="set_verified()">Marcar como clínica válida</button>
                    <button class="btn btn-warning" onclick="set_non_verified()" style="color:black!important;">Marcar como clínica no válida</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      <!-- fin todos los modal -->

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

      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

      <!-- Inicializador de tablas init js -->
      <script src="../assets/pages/datatables.init.class.js"></script>

      <script src="../assets/pages/clipboard.js"></script>
      <script type="text/javascript">
        $(".special").on('change',function(){
          const str = $(this).val();
          const acentos = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','Á':'A','É':'E','Í':'I','Ó':'O','Ú':'U'}
          $(this).val(str.split('').map( letra => acentos[letra] || letra).join('').toString().toLocaleUpperCase())
        });
        new ClipboardJS('.clpb', {
          text: function(trigger) {
              return trigger.getAttribute('aria-label');
          }
        });
      </script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/mkt-edu/marketing.js"></script>
      <script src="../assets/js/mkt-edu/panel.js"></script>
      
      <script src="../assets/js/controlescolar/ver_plan.js"></script>

      <?php if ($admin): ?>
        <script src="../assets/js/mkt-edu/master-market.js"></script>
        
      <?php endif ?>
      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>


      </script>
    </body>
    </html>
