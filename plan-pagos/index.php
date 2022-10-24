<?php
date_default_timezone_set("America/Mexico_City");
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 4){
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
    <link href="../assets/css/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
        .ui-autocomplete.ui-front.ui-menu.ui-widget.ui-widget-content{
          background: rgb(221 221 221);
          padding: 10px;
          z-index: 9999;
          list-style: none;
          color: #000;
          border-radius: 5px;
          height: 100%;
          max-height: 90px;
          overflow: auto;
          transform: translate(36.5%, 228%);
        }
        .ui-helper-hidden-accessible{
	        display: none;
        }
        .modal-lg{
          max-width: 90% !important;
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
                  <li class="has-submenu">
                    <a href="index.php"><i class="ti-home"></i> Inicio</a>
                  </li>

                  <li class="has-submenu">
                    <a href="alumnos.php"><i class="ion ion-md-calendar"></i> Gestionar Alumnos</a>
                  </li>

                  <li class="has-sumenu">
                    <a href="../hoteles/index.php"><i class="fas fa-plane-departure"></i>Visita Alumnos</a>
                  </li>

                  <li>
                    <a href="../marketing-educativo/comisiones.php"><i class="fa fa-calculator"></i>Comisiones</a>
                  </li>

                  <!-- <li class="has-submenu">
                    <a href="vistas.php"><i class="ion ion-md-key"></i> Gestionar vistas</a>
                  </li> -->
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
                    <h4 class="page-title m-0">
                      <span tab-target="pagos" class="tab_active" id="tabplanpago">
                        <i class="ti-briefcase"></i> Planes De Pago
                      </span> |
                      <!-- <span tab-target="generaciones" id="tabgeneraciones">
                        <i class="fas fa-graduation-cap"></i> Generaciones
                      </span> | -->
                      <span tab-target="conceptos" id="tabconceptos">
                        <i class="fas fa-money-bill-alt"></i> Conceptos de pago
                      </span> |
                      <span tab-target="promociones" id="tabpromociones">
                        <i class="fas fa-percent"></i> Promociones
                      </span> |
                      <span tab-target="fechascorte" id="tabfechascorte">
                        <i class="far fa-calendar-alt"></i> Fechas de corte por generación
                      </span> |
                      <span tab-target="fechascorteporalumno" id="tabfechascorteporalumno">
                        <i class="fas fa-address-book"></i> Fechas de corte por alumno
                      </span> |
                      <span tab-target="horas_trabajadas" id="tabhoras_trabajadas">
                        <i class="fas fa-comment-dollar"></i> Pago Docentes
                      </span>

                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- SECCION PARA CONCENTRADO DE EVENTOS -->
            <div class="col-sm-12" id="tab_concentrado_pagos">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- FIN CONTENEDOR DE TABS -->
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="tabplanpago" role="tabpanel" aria-labelledby="home-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <div class="row px-4 py-4">
                                <div class="col-sm-12 col-md-6">
                                  <h3>Listado Planes de pago</h3>
                                </div>
                                <div class="col-sm-12 col-md-6 text-right">
                                  <button id="boton-crear-plan" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal-pagos">Crear Plan de Pago</button>
                                </div>
                              </div>

                              <table id="table-pagos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>NOMBRE</th>
                                    <th>TOTAL</th>
                                    <th>CARRERA</th>
                                    <th>EVENTO</th>
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
            <!-- SECCION PARA CONCENTRADO DE GENERACIONES -->
            <div class="col-sm-12" id="tab_concentrado_generaciones" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="generacion" role="tabpanel" aria-labelledby="generacion-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                            <div class="table-responsive text-center">
                              <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                                <div class="row px-4 py-4">
                                  <div class="col-sm-12 col-md-6">
                                    <h3>Listado Generaciones</h3>
                                  </div>
                                  <div class="col-sm-12 col-md-6 text-right">
                                    <button id="btn-crear-generacion" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modalGeneracion">Crear Generación</button>
                                  </div>
                                </div>
                                <table id="table-generaciones" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                  <thead>
                                    <tr>
                                      <!--<th>PLAN PAGO</th>-->
                                      <th>NOMBRE</th>
                                      <th>FECHA INICIO</th>
                                      <th>FECHA FIN</th>
                                      <th>FECHA DE CREACION</th>
                                      <th>ACCIONES</th>
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
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE CONCEPTOS DE PAGO -->
            <div class="col-sm-12" id="tab_concentrado_conceptos" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <div class="row px-4 py-4">
                                <div class="col-sm-12 col-md-6">
                                  <h3>Listado de conceptos</h3>
                                </div>
                                <div class="col-sm-12 col-md-6 text-right">
                                  <button id="boton-crear-concepto" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal-crear-concepto">
                                    Crear concepto
                                  </button>
                                </div>
                              </div>
                              
                              <table id="table-conceptos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>NOMBRE</th>
                                    <th>PRECIO (MXN)</th>
                                    <th>PRECIO (USD)</th>
                                    <th>CATEGORIA</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>INSTITUCIÓN</th>
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
            <!-- SECCION PARA CONCENTRADO DE PROMOCIONES -->
            <div class="col-sm-12" id="tab_concentrado_promociones" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="">
                            <ul class="nav nav-tabs" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="proms-tab" data-toggle="tab" href="#proms" role="tab" aria-controls="proms" aria-selected="true">
                                  <span>Aplicadas</span>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="ofers-tab" data-toggle="tab" href="#ofers" role="tab" aria-controls="ofers" aria-selected="false">
                                  <span>Ofertas</span>
                                </a>
                              </li>
                            </ul>
                            <div class="tab-content bg-light pt-0">
                              <div class="tab-pane fade active show" id="proms" role="tabpanel" aria-labelledby="proms-tab">
                                <div class="table-responsive">
                                  <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                                    <div class="row px-4 py-4">
                                      <div class="col-sm-12 col-md-6">
                                        <h3>Listado de promociones</h3>
                                      </div>
                                      <div class="col-sm-12 col-md-6 text-right">
                                        <button id="boton-crear-promocion" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal">
                                          Crear promoción
                                        </button>
                                      </div>
                                    </div>
                                    <table id="table-promociones" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                      <thead>
                                        <tr>
                                          <th>NOMBRE</th>
                                          <th>TIPO</th>
                                          <th>CONCEPTO</th>
                                          <th>GENERACION</th>
                                          <th>CARRERA</th>
                                          <th>ALUMNO</th>
                                          <th>% DESCUENTO</th>
                                          <th>ESTATUS</th>
                                          <th>FECHA DE CREACIÓN</th>
                                          <th>ACCIONES</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="ofers" role="tabpanel" aria-labelledby="ofers-tab">
                                <div class="table-responsive TBNR">
                                  <table id="table_ofertas" class="table w-100">
                                    <thead>
                                      <th>Nombre</th>
                                      <th for-filter>Carrera</th>
                                      <th for-filter>Generación</th>
                                      <th>Conceptos</th>
                                    </thead>
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
            <!-- SECCION PARA CONCENTRADO DE FECHAS DE CORTE GENERACIONES -->
            <div class="col-sm-12" id="tab_concentrado_fechascorte" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <div class="row px-4 py-4">
                                <div class="col-sm-12 col-md-6">
                                  <h3>Listado de generaciones</h3>
                                </div>
                                <div class="col-sm-12 col-md-6 text-right">
                                  <!-- <button id="boton-crear-plan" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal-pagos">Crear Plan de Pago</button> -->
                                </div>
                              </div>
                              <table id="table-fechascorte" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th for-filter strict>GENERACIÓN</th>
                                    <th for-filter>CARRERA</th>
                                    <th>FECHA DE CREACIÓN</th>
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
            <!-- SECCION PARA CONCENTRADO DE FECHAS DE CORTE POR ALUMNO -->
            <div class="col-sm-12" id="tab_concentrado_fechascorteporalumno" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                          </div>
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <div class="row px-4 py-4">
                                <div class="col-sm-12 col-md-6">
                                  <h3>Listado de fechas de corte por alumno</h3>
                                </div>
                                <div class="col-sm-12 col-md-6 text-right">
                                  <!-- <button id="boton-crear-plan" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal-pagos">Crear Plan de Pago</button> -->
                                </div>
                              </div>

                              <table id="table-fechascorteporalumno" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th for-filter strict>GENERACIÓN</th>
                                    <th for-filter>CARRERA</th>
                                    <th>ALUMNO</th>
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

            <div class="col-sm-12" id="tab_concentrado_horas_trabajadas" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <div class="row px-4 py-4">
                                <div class="col-sm-12 col-md-6">
                                  <h3>Horas trabajadas</h3>
                                </div>
                                <!-- <div class="col-sm-12 col-md-6 text-right">
                                  <button id="boton-crear-concepto" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal-crear-concepto">
                                    Crear concepto
                                  </button>
                                </div> -->
                              </div>
                              
                              <table id="table-horas_trabajadas" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th style="width:25px">MATERIA / CLASE</th>
                                    <th style="width:25px">DOCENTE</th>
                                    <th>FECHA</th>
                                    <th>INICIO</th>
                                    <th>FINAL</th> <!--SE AGREGA NUEVO ELEMENTO -->
                                    <th>TIEMPO</th>
                                    <th>MONTO</th>
                                    <th>ESTATUS</th>
                                    <th>OPCIONES</th>
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
            <!-- SECCION PARA CONCENTRADO DE CARRERAS -->
            <div class="col-sm-12" id="tab_concentrado_carreras"  style="display:none">
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
                                    <th>NOMBRE CLAVE</th>
                                    <th>TIPO</th>
                                    <th>MODALIDAD</th>
                                    <th>DURACION TOTAL</th>
                                    <th>TIPO CICLO</th>
                                    <th>CODIGO PROMOCIONAL</th>
                                    <th>DIRECCION</th>
                                    <th>ESTADO</th>
                                    <th>PAIS</th>
                                    <th>PLANTILLA BIENVENIDA</th>
                                    <th>FECHA INICIO</th>
                                    <th>FECHA FIN</th>
                                    <th>FECHA CREACIÓN</th>
                                    <th>ACCIONES</th>
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
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE EVENTOS -->
            <div class="col-sm-12" id="tab_concentrado_eventos" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                            <div>
                              <button data-target="#custom-width-modal" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal">
                                Crear evento
                              </button>
                            </div>
                          </div>
                          <div class="table-responsive text-center">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado de eventos</h5>
                              <table id="datatable-eventos" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>ID Evento</th>
                                    <th>Tipo</th>
                                    <th>Título</th>
                                    <th>Link Evento</th>
                                    <th>Fecha Evento</th>
                                    <th>Fecha Disponibilidad</th>
                                    <th>Fecha Caducidad</th>
                                    <th>Número Asistentes</th>
                                    <th>Duración</th>
                                    <th>Tipo De Duración</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                    <th>País</th>
                                    <th>Código Promocional</th>
                                    <th>Estatus</th>
                                    <th>Modalidad</th>
                                    <th>Institución</th>
                                    <th>Imagen</th>
                                    <th>Fondo</th>
                                    <th>Descripción</th>
                                    <th>Plantilla</th>
                                    <th>         </th>
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
      <!-- Button trigger modal -->
      <div class="modal fade" id="ModalRegistrarPago" tabindex="-1" role="dialog" aria-labelledby="ModalRegistrarPago" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Comprobante de pago</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
            <form id = "formComprobantedePago">
              <div class="modal-body">
                <div class="container-fluid">
                  <input class = "d-none" type="text" name="idhoras" id="idhoras" onlyNum>

                  <!-- add --> 
                  <div class="row">
                      <div class="col-md-4">
                        <label for="montoSugerido">Monto sugerido:</label>
                        <input class = "form-control" type="text" id="montoSugerido" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="costoHr">Costo por hora:</label>
                        <input class = "form-control" type="text" id="costoHr" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="hrsTutoria">Horas de tutoría:</label>
                        <input class = "form-control" type="text" id="hrsTutoria" readonly>
                      </div>
                    </div>
                    <br>
                  <!-- end add --> 

                    <div class="row">
                      <div class="col-md-6">
                        <label for="cantidadPago">Monto a pagar:</label>
                        <input class = "form-control" type="number" name="cantidadPago" id="cantidadPago" onlyNum required>
                      </div>
                      <div class="col-md-6">
                        <label for="comprobantePago">Comprobante:</label>
                        <input class = "form-control" type="file" accept=".pdf, .jpeg, .jpg, .png" name="comprobantePago" id="comprobantePago" required>
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

      <!-- center modal form alta plan de pagos -->
      <div id="modal-pagos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Crear plan de pago</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para crear el plan de pago</h4>
                      <form id="formPlanPago" type="post">
                        <div class="form-group">
                          <label for="nombreplan">Nombre del plan:</label>
                          <input type="text" class="form-control" id="nombreplan" name="nombreplan" placeholder="Ingresa el nombre del plan" required>
                        </div>
                        <div class="form-group">
                        <label for="selectAsignacion">Asignar:</label>
                          <select class="form-control" name="selectAsignacion" id="selectAsignacion" required>
                            <option value="" selected="true" disabled="disabled">Seleccione</option>
                            <option value="2">Carrera</option>
                            <option value="4">Evento</option>
                          </select>
                        </div>
                        <div class="form-group" style="display:none" id="divCarreras">
                          <label for="selectCarreras">Seleccione la carrera:</label>
                          <select class="form-control" id="selectCarreras" name="selectCarreras" required>
                          </select>
                        </div>
                        <div class="form-group" style="display:none" id="divEventos">
                          <label for="selectEventos">Seleccione el evento:</label>
                          <select class="form-control" id="selectEventos" name="selectEventos">
                          </select>
                        </div>
                        <div class="row" id="divinscripcion">
                          <div class="col-sm-6 col-md-4 mb-3">
                          <label for="costoInscripcion">Costo de Inscripción (MXN):</label>
                            <input type="text" class="form-control" id="costoInscripcion" name="costoInscripcion" step="0.01" min="0.01" placeholder="Ingresa el costo de la inscripción" value ="0" required>
                          </div>
                          <div class="col-sm-6 col-md-4 mb-3">
                          <label for="costoInscripcionusd">Costo de Inscripción (USD):</label>
                            <input type="text" class="form-control" id="costoInscripcionusd" name="costoInscripcionusd" step="0.01" min="0.01" placeholder="Ingresa el costo de la inscripción" value ="0" required>
                          </div>
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="fechalimitepagoins">Fecha limite de pago:</label>
                            <input type="date" class="form-control" id="fechalimitepagoins" name="fechalimitepagoins" step="0.01" min="0.01" placeholder="Ingresa el costo de la inscripción" required>
                          </div>
                        </div>
                        <div class="row" id="divMensualidad">
                          <div class="col-sm-6 col-md-4 mb-3">
                          <label for="costoMensualidad">Costo/mes (MXN):</label>
                            <input type="text" class="form-control" id="costoMensualidad" name="costoMensualidad" step="0.01" min="0.01" placeholder="Ingresa el costo de la mensualidad" value ="0" required>
                          </div>
                          <div class="col-sm-6 col-md-4 mb-3">
                          <label for="costoMensualidadusd">Costo/mes (USD):</label>
                            <input type="text" class="form-control" id="costoMensualidadusd" name="costoMensualidadusd" step="0.01" min="0.01" placeholder="Ingresa el costo de la mensualidad" value ="0" required>
                          </div>
                          <div class="col-sm-12 col-md-2 mb-3">
                          <label for="nMensualidades">No meses:</label>
                            <input type="text" class="form-control" id="nMensualidades" name="nMensualidades" min="1" placeholder="Ingresa el número de mensualidades" value ="0" required>
                            <input type="hidden" class="form-control" id="nMensualidadesusd" name="nMensualidadesusd" min="1" placeholder="Ingresa el número de mensualidades" value ="0" required>
                          </div>
                          <div class="col-sm-12 col-md-2 mb-3">
                          <label for="diasdecorte">día de corte:</label>
                            <input type="text" class="form-control" id="diasdecorte" name="diasdecorte" maxlength="2" required>
                          </div>
                        </div>
                        <div class="row" id="divReinscripcion">
                          <div class="col-sm-6 col-md-4 mb-3">
                          <label for="costoReinscripcion">Costo/reinscripción (MXN):</label>
                            <input type="text" class="form-control" id="costoReinscripcion" name="costoReinscripcion" placeholder="Ingresa el costo de la reinscripción" value ="0" required>
                          </div>
                          <div class="col-sm-6 col-md-4 mb-3">
                          <label for="costoReinscripcionusd">Costo/reinscripción (USD):</label>
                            <input type="text" class="form-control" id="costoReinscripcionusd" name="costoReinscripcionusd" placeholder="Ingresa el costo de la reinscripción" value ="0" required>
                          </div>
                        </div>
                        <div class="row" id="divcostotitulacion">
                          <div class="col-sm-6 col-md-4 mb-3">
                            <label for="costotitulacion">Costo de titulación (MXN):</label>
                            <input type="text" class="form-control" id="costotitulacion" name="costotitulacion" value ="0" required>
                          </div>
                          <div class="col-sm-6 col-md-4 mb-3">
                            <label for="costotitulacionusd">Costo de titulación (USD):</label>
                            <input type="text" class="form-control" id="costotitulacionusd" name="costotitulacionusd" value ="0" required>
                          </div>
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="fechalimitepagotit">Fecha limite de pago:</label>
                            <input type="date" class="form-control" id="fechalimitepagotit" name="fechalimitepagotit" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-6 col-md-6 mb-3">
                            <label for="total">Total (MXN):</label>
                            <input type="number" class="form-control" id="total" name="total" value ="0" disabled>
                          </div>
                          <div class="col-sm-6 col-md-6 mb-3">
                            <label for="totalusd">Total (USD):</label>
                            <input type="number" class="form-control" id="totalusd" name="totalusd" value ="0" disabled>
                          </div>
                        </div>
                        <div class="text-right">
                          <input type="hidden" name="tipoCarrera" id="tipoCarrera" value="0">
                          <input type="hidden" name="evtAct" id="evtAct">
                          <button type="submit" id="btnCrear" class="btn btn-primary waves-effect waves-light">Crear</button>
                          <button type="button" name="ocultarPlan" id="ocultarPlan" name="ocultarPlan" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal plan de pagos -->

      <!-- center modal editar fechas de corte generaciones -->
      <div id="modalfechacortegeneracion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Establecer fechas y días de corte</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para establecer las fechas de corte de la generación</h4>
                      <form id="formcrearnuevosconceptosgeneracion" type="post">
                        <div>
                          <p id="nombregenfechacorte">generacion de ejemplo</p>
                          <input type="hidden" id="idgenfechacortemod" name="idgenfechacortemod">
                          <input type="hidden" id="nombregenfechacorteg" name="nombregenfechacorteg">
                        </div>
                        <div style="" id="divCarrerasfechacorte">
                          <p> Carrera: <span id="nombrecarrerafechacorte"></span></p>
                        </div>
                        <div style="display:none" id="divEventosfechacorte">
                          <label for="selectEventos">Nombre del evento:</label>
                          <input type="text">
                        </div>
                        <div class="row">
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="costoInscripcionfechacortegenmod">Costo de Inscripción (MXN):</label>
                            <input type="number" class="form-control" id="costoInscripcionfechacortegenmod" name="costoInscripcionfechacortegenmod" step="0.01" min="0.01" placeholder="Ingresa el costo de la inscripción" required>
                            <input type="hidden" id="idconceptoinsfechacorte" name="idconceptoinsfechacorte">
                            <input type="hidden" id="certificacionoenevtofechas" name="certificacionoenevtofechas">
                          </div>


                          <!-- CONCEPTO INSCRIPCION USD -->
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="costoInscripcionfechacortegenmodusd">Costo de Inscripción (USD):</label>
                            <input type="number" class="form-control" id="costoInscripcionfechacortegenmodusd" name="costoInscripcionfechacortegenmodusd" placeholder="Ingresa el costo de la inscripción" required>
                            <input type="hidden" id="certificacionoenevtofechas" name="certificacionoenevtofechas">
                          </div>
                          <!-- CONCEPTO INSCRIPCION USD -->

                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="fechalimitepagoinsfechacortemod">Fecha limite de pago:</label>
                            <input type="date" class="form-control" id="fechalimitepagoinsfechacortemod" name="fechalimitepagoinsfechacortemod" placeholder="Ingresa el costo de la inscripción" required>
                          </div>
                        </div>
                        <div class="row" id="divMensualidadfechacortemod" style="display:none">
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="costoMensualidadfechacortemod">Costo/mes (MXN):</label>
                            <input type="number" class="form-control" id="costoMensualidadfechacortemod" name="costoMensualidadfechacortemod" placeholder="Ingresa el costo de la mensualidad" required>
                          </div>


                          <!-- CONCEPTO MENSUALIDAD USD -->
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="costoMensualidadfechacortemodusd">Costo/mes (USD):</label>
                            <input type="number" class="form-control" id="costoMensualidadfechacortemodusd" name="costoMensualidadfechacortemodusd" placeholder="Ingresa el costo de la mensualidad" required>
                          </div>
                          <!-- CONCEPTO INSCRIPCION USD -->

                          <div class="col-sm-12 col-md-2 mb-3">
                          <label for="nMensualidadesfechacortemod">No meses:</label>
                            <input type="number" class="form-control" id="nMensualidadesfechacortemod" name="nMensualidadesfechacortemod" min="1" placeholder="Ingresa el número de mensualidades" required>
                            <input type="hidden" id="idconceptomensfechacorte" name="idconceptomensfechacorte">
                            <input type="hidden" class="form-control" id="nMensualidadesfechacortemodusd" name="nMensualidadesfechacortemodusd" min="1" placeholder="Ingresa el número de mensualidades" value ="0" required>
                          </div>
                          <div class="col-sm-12 col-md-2 mb-3">
                          <label for="diasdecortefechacortemod">día de corte:</label>
                            <input type="text" class="form-control" id="diasdecortefechacortemod" name="diasdecortefechacortemod" maxlength="2" required>
                          </div>
                        </div>
                        <div class="row" id="divReinscripcionfechacortemod" style="display:none">
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="costoReinscripcionfechacortemod">Costo/reinscripción (MXN):</label>
                            <input type="number" class="form-control" id="costoReinscripcionfechacortemod" name="costoReinscripcionfechacortemod" placeholder="Ingresa el costo de la reinscripción" required>
                          </div>


                          <!-- CONCEPTO reinscripción USD -->
                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="costoReinscripcionfechacortemodusd">Costo/reinscripción (USD):</label>
                            <input type="number" class="form-control" id="costoReinscripcionfechacortemodusd" name="costoReinscripcionfechacortemodusd" placeholder="Ingresa el costo de la reinscripción" required>
                          </div>
                          <!-- CONCEPTO reinscripción USD -->

                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="nReinscripcionfechacortemod">Número de Reinscripciónes:</label>
                            <input disabled type="number" class="form-control" id="nReinscripcionfechacortemod" name="nReinscripcionfechacortemod" min="1" placeholder="Ingresa el número de la reinscripción" required>
                            <input type="hidden" id="idconceptoreinsfechacorte" name="idconceptoreinsfechacorte">
                          </div>
                        </div>
                        <div class="row" id="divcostotitulacionfechacortemod" style="display:none">
                          <div class="col-sm-12 col-md-4 mb-3">
                            <label for="costotitulacionfechacortemod">Costo de titulación (MXN):</label>
                            <input type="number" class="form-control" id="costotitulacionfechacortemod" name="costotitulacionfechacortemod">
                            <input type="hidden" id="idconceptotitfechacorte" name="idconceptotitfechacorte">
                          </div>


                          <!-- CONCEPTO reinscripción USD -->
                          <div class="col-sm-12 col-md-4 mb-3">
                            <label for="costotitulacionfechacortemodusd">Costo de titulación (USD):</label>
                            <input type="number" class="form-control" id="costotitulacionfechacortemodusd" name="costotitulacionfechacortemodusd">
                          </div>
                          <!-- CONCEPTO reinscripción USD -->

                          <div class="col-sm-12 col-md-4 mb-3">
                          <label for="fechalimitepagotitfechacortemod">Fecha limite de pago:</label>
                            <input type="date" class="form-control" id="fechalimitepagotitfechacortemod" name="fechalimitepagotitfechacortemod">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="totalfechacorte">Total (MXN):</label>
                            <input type="number" class="form-control" id="totalfechacorte" name="totalfechacorte" disabled>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="totalfechacorteusd">Total (USD):</label>
                            <input type="number" class="form-control" id="totalfechacorteusd" name="totalfechacorteusd" value="0" disabled>
                          </div>
                        </div>
                        <div class="text-right">
                          <button type="submit" id="btnCrearconceptosfechascorte" class="btn btn-primary waves-effect waves-light">Modificar</button>
                          <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal editar fechas de corte generaciones -->

      <!-- center modal editar fechas de corte por alumnos-->
      <div id="modalfechacorteporalumno" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Establecer fecha y día de corte</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para el primer pago mensual</h4>
                      <form id="formactualizarfechasdecorteporalumno" type="post">
                        
                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                          <label for="fechaprimercolegiaturamod">Fecha primer colegiatura:</label>
                            <input type="date" class="form-control" id="fechaprimercolegiaturamod" name="fechaprimercolegiaturamod" required>
                            <input type="hidden" name="idGeneracion" id="idGeneracionfechacortealumno">
                            <input type="hidden" name="idAsistente" id="idAsistentefechacortealumno">
                          </div>
                        </div>
                        <div class="text-right">
                          <button type="submit" class="btn btn-primary waves-effect waves-light">Modificar</button>
                          <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal editar fechas de corte por alumnos-->

      <!--Modal modificar-plan-->
      <div class="modal fade" id="modalModPlan" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar Plan de Pago</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formModPlan" type="post">
                    <div class="form-group">
                      <label for="nuevonombre">Nombre del plan:</label>
                      <input type="text" class="form-control" id="nuevonombre" name="nuevonombre" required>
                    </div>
                    <div class="form-group">
                      <select class="form-control" name="nuevoSelectAsignacion" id="nuevoSelectAsignacion" required>

                      </select>
                      <p class="m-t-10" id="nombrecarreraaevt"></p>
                    </div>
                    <div class="form-group" style="display:none" id="nuevoDivAfiliados"> 
                      <label for="nuevoSelectAfiliados">Seleccione el alumno:</label>
                      <select class="form-control" id="nuevoSelectAfiliados" name="nuevoSelectAfiliados">
                      </select>
                    </div>
                    <div class="form-group" style="display:none" id="nuevoDivCarreras">
                      <label for="nuevoSelectCarreras">Seleccione la carrera:</label>
                      <select class="form-control" id="nuevoSelectCarreras" name="nuevoSelectCarreras">
                      </select>
                    </div>
                    <div class="form-group" style="display:none" id="nuevoDivEventos">
                      <label for="nuevoSelectEventos">Seleccione el evento:</label>
                      <select class="form-control" id="nuevoSelectEventos" name="nuevoSelectEventos">
                      </select>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-4 mb-3">
                        <label for="nuevoCostoInscripcion">Costo de Inscripción (MXN):</label>
                          <input type="text" class="form-control" id="nuevoCostoInscripcion" name="nuevoCostoInscripcion" placeholder="Ingresa el costo de la inscripción" required>
                          <input type="hidden" id="idconceptoins" name="idconceptoins">
                      </div>
                      <div class="col-sm-12 col-md-4 mb-3">
                        <label for="nuevoCostoInscripcionusd">Costo de Inscripción (USD):</label>
                          <input type="text" class="form-control" id="nuevoCostoInscripcionusd" name="nuevoCostoInscripcionusd" placeholder="Ingresa el costo de la inscripción" required>
                      </div>
                      <div class="col-sm-12 col-md-4 mb-3">
                        <label for="nuevafechalimitedepagoins">Fecha limite de pago:</label>
                          <input type="date" class="form-control" id="nuevafechalimitedepagoins" name="nuevafechalimitedepagoins" required>
                      </div>
                    </div>
                    <div class="row" id="divMensualidadMod">
                      <div class="col-sm-12 col-md-4 mb-3">
                      <label for="nuevoCostoMensualidad">Costo/mes (MXN):</label>
                        <input type="text" class="form-control" id="nuevoCostoMensualidad" name="nuevoCostoMensualidad" placeholder="Ingresa el costo de la mensualidad" required>
                        <input type="hidden" id="idconceptocostomens" name="idconceptocostomens">
                      </div>
                      <div class="col-sm-12 col-md-4 mb-3">
                      <label for="nuevoCostoMensualidadusd">Costo/mes (USD):</label>
                        <input type="text" class="form-control" id="nuevoCostoMensualidadusd" name="nuevoCostoMensualidadusd" placeholder="Ingresa el costo de la mensualidad" required>
                      </div>
                      <div class="col-sm-12 col-md-2 mb-3">
                      <label for="nuevoNoMensualidades">No meses:</label>
                        <input type="text" class="form-control" id="nuevoNoMensualidades" name="nuevoNoMensualidades" min="1" placeholder="Ingresa el número de mensualidades" required>
                      </div>
                      <div class="col-sm-12 col-md-2 mb-3">
                      <label for="nuevodiasdecorte">día de corte:</label>
                        <input type="text" class="form-control" id="nuevodiasdecorte" name="nuevodiasdecorte" maxlength="2" required>
                      </div>
                    </div>
                    <div class="row" id="divReinscripcionMod">
                      <div class="col-sm-12 col-md-4 mb-3">
                      <label for="nuevoCostoReinscripcion">Costo/reinscripción (MXN):</label>
                        <input type="text" class="form-control" id="nuevoCostoReinscripcion" name="nuevoCostoReinscripcion" placeholder="Ingresa el costo de la reinscripción" required>
                        <input type="hidden" id="idconceptocostoreins" name="idconceptocostoreins">
                      </div>
                      <div class="col-sm-12 col-md-4 mb-3">
                      <label for="nuevoCostoReinscripcionusd">Costo/reinscripción (USD):</label>
                        <input type="text" class="form-control" id="nuevoCostoReinscripcionusd" name="nuevoCostoReinscripcionusd" placeholder="Ingresa el costo de la reinscripción" required>
                      </div>
                      <div class="col-sm-12 col-md-4 mb-3">
                      <label for="nuevoNoReinscripcion">Número de Reinscripciónes:</label>
                        <input disabled type="text" class="form-control" id="nuevoNoReinscripcion" name="nuevoNoReinscripcion" min="1" placeholder="Ingresa el número de la reinscripción" required>
                      </div>
                    </div>
                    <div class="row" id="nuevodivtitulacion">
                      <div class="col-sm-12 col-md-4 mb-3">
                        <label for="nuevoCostoTitulacion">Costo de titulación (MXN):</label>
                          <input type="text" class="form-control" id="nuevoCostoTitulacion" name="nuevoCostoTitulacion" placeholder="Ingresa el costo de la titulación" required>
                          <input type="hidden" id="idconceptocostotit" name="idconceptocostotit">
                      </div>
                      <div class="col-sm-12 col-md-4 mb-3">
                        <label for="nuevoCostoTitulacionusd">Costo de titulación (USD):</label>
                          <input type="text" class="form-control" id="nuevoCostoTitulacionusd" name="nuevoCostoTitulacionusd" placeholder="Ingresa el costo de la titulación" required>
                      </div>
                      <div class="col-sm-12 col-md-4 mb-3">
                        <label for="nuevafechalimitedepagotit">Fecha limite de pago:</label>
                          <input type="date" class="form-control" id="nuevafechalimitedepagotit" name="nuevafechalimitedepagotit">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="nuevoTotal">Total (MXN):</label>
                        <input type="text" class="form-control" id="nuevoTotal" name="nuevoTotal" disabled>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="nuevoTotalusd">Total (USD):</label>
                        <input type="text" class="form-control" id="nuevoTotalusd" name="nuevoTotalusd" disabled>
                      </div>
                    </div>
                    <div class="text-right">
                      <input type="hidden" name="selectAnteriorPlan" id="selectAnteriorPlan">
                      <input type="hidden" id="id" name="id">
                      
                      <input type="hidden" name="tipoCarreraMod" id="tipoCarreraMod">
                      <input type="hidden" name="evtActMod" id="evtActMod">

                      <button type="submit" id="btnMod" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
                      <button type="button" name="ocultarModPlan" id="ocultarModPlan" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal-->

      <!-- center modal form alta promociones -->
      <div id="modal-crear-promocion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Crear promoción</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para crear la promoción</h4>
                      <form id="form-crearpromocion" type="post">
                        <div class="row">
                          <div class="form-group col-sm-12 col-md-6">
                            <label for="nombrepromocion">Nombre de la promoción/beca:</label>
                            <input type="text" class="form-control" id="nombrepromocion" name="nombrepromocion" placeholder="Ingresa el nombre de la promoción" required>
                          </div>
                          
                          <div class="form-group col-sm-12 col-md-6">
                            <label for="selecpromobeca">Selecciona promoción o beca:</label>
                            <select class="form-control" id="selecpromobeca" name="selecpromobeca" required>
                              <option value="1">Promoción</option>
                              <option value="2">Beca</option>
                            </select>
                          </div>
                        </div> <!-- row 1 -->

                        <div class="row">
                          <div class="form-group col-sm-12 col-md-6">
                            <label for="selecalumnogeneracion">Asignar descuento:</label>
                            <select class="form-control" id="selecalumnogeneracion" name="selecalumnogeneracion" required>
                              <option selected="true" disabled="disabled">Seleccione</option>
                              <option value="1">Alumno</option>
                              <option value="2">Generación</option>
                              <option value="4">Oferta</option>
                            </select>
                          </div>
                          
                          <div class="form-group col-sm-12 col-md-6" style="display:none" id="mostraralumnos">
                            <label for="listaralumnos">Seleccione el alumno</label>
                            <select class="js-example-basic-single input-alumnos js-example-responsive" style="width: 100%" id="listaralumnos" name="listaralumnos">
                            </select>
                          </div>
                        </div>
                        <div class="form-group" style="display:none" id="mostrargeneraciones">
                          <label for="listargeneraciones">Seleccione la generación</label>
                          <select class="form-control" id="listargeneraciones" name="listargeneraciones" required>
                            
                          </select>
                        </div>

                        <div class="border rounded p-2 mb-1" id="divpromoinscripcion"  style="display:none">
                          <b>Inscripción</b>
                          <div class="row">
                            <div class="col-sm-12 col-md-4 mb-4">
                              <label for="promoinscripcion">% de promoción</label>
                                <input type="number" class="form-control onlyNumer" id="promoinscripcion" name="promoinscripcion" value="0" step="any">
                                <input type="hidden" id="idconceptopromoinscripcion" name="idconceptopromoinscripcion">
                            </div>
                            <div class="col-sm-12 col-md-4 mb-4">
                              <label for="montofininscripcion">Monto a pagar (MXN)</label>
                              <input type="tel" class="form-control moneyFt" id="montofininscripcion" data-prefix="$ " value="$ 0.00">
                            </div>
                            <div class="col-sm-12 col-md-4 mb-4">
                              <label for="montofininscripcionusd">Monto a pagar (USD)</label>
                              <input type="tel" class="form-control moneyFt" id="montofininscripcionusd" data-prefix="$ " value="$ 0.00">
                            </div>
                          </div>
                        </div>

                        <div id="divtodosconceptos"  style="display:none">
                          <div class="border rounded p-2 mb-1">
                            <b> Mensualidad:</b>
                            <div class="row" id="divpromomensualidades">
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="promomensualidades">% de promoción en cada mensualidad</label>
                                <input type="number" class="form-control" id="promomensualidades" name="promomensualidades" step="any" step="any">
                                <input type="hidden" id="idconceptopromomensualidades" name="idconceptopromomensualidades">
                              </div>
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="montofinmensualidad">Monto a pagar (MXN)</label>
                                <input type="tel" class="form-control moneyFt" id="montofinmensualidad" data-prefix="$ " value="$ 0.00">
                              </div>
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="montofinmensualidadusd">Monto a pagar(USD)</label>
                                <input type="tel" class="form-control moneyFt" id="montofinmensualidadusd" data-prefix="$ " value="$ 0.00">
                              </div>

                              <div class="col-3">
                                <div class="checkbox">
                                  <input id="check_num_mens" type="checkbox">
                                  <label for="check_num_mens">
                                    Definir en qué pago aplicar
                                  </label>
                                </div>
                              </div>
                              <div class="col">
                              <select id="multiple_mensualidades" name="multiple_mensualidades[]" class="selectpicker" multiple title="Seleccione las mensualidades a aplicar..." required disabled>
                              </select>
                              </div>
                            </div>
                          </div>
                          <div class="border rounded p-2 mb-1">
                            <b> Reinscripcion:</b>
                            <div class="row" id="divpromoreinscripcion">
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="promoreinscripciones">% de promoción en cada reinscripcion</label>
                                <input type="number" class="form-control" id="promoreinscripciones" name="promoreinscripciones" step="any">
                                <input type="hidden" id="idconceptopromoreinscripciones" name="idconceptopromoreinscripciones">
                              </div>
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="montofinreinscripcion">Monto a pagar (MXN)</label>
                                <input type="tel" class="form-control moneyFt" id="montofinreinscripcion" data-prefix="$ " value="$ 0.00">
                              </div>
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="montofinreinscripcionusd">Monto a pagar(USD)</label>
                                <input type="tel" class="form-control moneyFt" id="montofinreinscripcionusd" data-prefix="$ " value="$ 0.00">
                              </div>
                            </div>
                          </div>
                          <div class="border rounded p-2 mb-1">
                            <b>TItulación:</b>
                            <div class="row" id="divpromotitulacion">
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="promotitulacion">% de promoción por titulación</label>
                                <input type="number" class="form-control" id="promotitulacion" name="promotitulacion" step="any">
                                <input type="hidden" id="idconceptopromotitulacion" name="idconceptopromotitulacion">
                              </div>
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="montofintitulacion">Monto a pagar (MXN)</label>
                                <input type="tel" class="form-control moneyFt" id="montofintitulacion" data-prefix="$ " value="$ 0.00">
                              </div>
                              <div class="col-sm-12 col-md-4 mb-4">
                                <label for="montofintitulacionusd">Monto a pagar (USD)</label>
                                <input type="tel" class="form-control moneyFt" id="montofintitulacionusd" data-prefix="$ " value="$ 0.00">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="promofechainicial">Fecha inicio</label>
                            <input type="date" class="form-control" id="promofechainicial" name="promofechainicial" required>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="promofechafinal">Fecha fin</label>
                            <input type="date" class="form-control" id="promofechafinal" name="promofechafinal" required>
                          </div>
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
      </div><!-- /.modal crear promociones -->

      <!-- center modal form alta conceptos -->
      <div class="modal fade" id="modal-crear-concepto" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Crear concepto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para crear el concepto</h4>
                      <form id="formCrearConcepto" type="post">
                        <div class="form-group">
                          <label for="selectInstitucionConcepto">Elige institución:</label>
                          <select class="form-control" name="selectInstitucionConcepto" id="selectInstitucionConcepto" required>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="nombreconcepto">Nombre del concepto:</label>
                          <input type="text" class="form-control" id="nombreconcepto" name="nombreconcepto" placeholder="Ingresa el nombre del concepto" required>
                        </div>
                        <div class="form-group">
                          <label for="precio">Precio (MXN):</label>
                          <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0.01" placeholder="Ingresa el precio" required>
                        </div>
                        <div class="form-group">
                          <label for="precio">Precio (USD):</label>
                          <input type="number" class="form-control" id="precio_usd" name="precio_usd" step="0.01" min="0.01" placeholder="Ingresa el precio en dolares" >
                        </div>
                        <div class="form-group">
                          <label for="selectParcialidades">Parcialidades:</label>
                          <select class="form-control" name="selectParcialidades" id="selectParcialidades" required>
                            <option value="" selected="true" disabled="disabled">Seleccione</option>
                            <option value="1">Sí</option>
                            <option value="2">No</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="descripcion">Descripcion:</label>
                          <textarea class="form-control" id="descripcion" name="descripcion" row="4" cols="50" placeholder="Ingresa tu descripción" required></textarea>
                        </div>
                        <div class="text-right">
                          <button type="submit" class="btn btn-primary waves-effect waves-light">Crear</button>
                          <button type="button" name="ocultarConp" id="ocultarConp" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal crear conceptos -->

      <!-- center modal form editar promociones -->
      <div id="modal-editar-promocion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Editar promoción</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0">Ingresa la información para editar la promoción</h4>
                      <h5 class="m-b-30" id="nombregeneracionpromo"></h5>
                      <input type="hidden" id="tempmount">
                      <form id="form-editapromocion">
                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="editarnombrepromocion">Nombre de la promoción/beca:</label>
                            <input type="text" class="form-control" id="editarnombrepromocion" name="editarnombrepromocion" placeholder="Ingresa el nombre de la promoción" required>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="editarselecpromobeca">Selecciona promoción o beca:</label>
                            <select class="form-control" id="editarselecpromobeca" name="editarselecpromobeca">
                              <option value="1">Promoción</option>
                              <option value="2">Beca</option>
                            </select>
                          </div>
                        </div>
                        
                        <div class="form-group" style="display:none" id="editarmostraralumnos">  
                          <label for="listaralumnoseditar">Alumno</label>
                          <div class="col-12 mb-3">
                            <div class="form-group">
                            <!-- <select style="width: 100%" class="js-example-basic-single input-alumnos js-example-responsive" name="listaralumnoseditar" id="listaralumnoseditar">
                            </select> -->
                            <input type="text" disabled readonly class="form-control" id="listaralumnoseditar">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-3">
                              <p id="nombrecarrerapromo"></p>
                              <p class="bg-muted border p-2" id="tipoconcepto"></p>
                              </div>
                              <div class="col-sm-12 col-md-6 mb-3">
                                <label for="promoreinscripcioneseditar">Editar % de promoción</label>
                                  <input type="number" class="form-control" id="promoreinscripcioneseditar" name="promoreinscripcioneseditar" step="any" required>
                                  <input type="hidden" id="idpromocioneditar" name="idpromocioneditar">

                                  <label class="mt-2" for="">Costo despues de la promoción</label>
                                  <input class="form-control" type="text" readonly disabled id="showporcent">
                              </div>
                          </div>

                          <div class="row">
                            <div class="col-3">
                              <div class="checkbox">
                                <input id="check_num_mens_edit" type="checkbox">
                                <label for="check_num_mens_edit">
                                  Definir en qué pago aplicar
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col">
                            <select id="multiple_mensualidades_edit" name="multiple_mensualidades_edit[]" class="selectpicker" multiple title="Seleccione las mensualidades a aplicar..." disabled>
                            </select>
                          </div>

                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="promofechainicialeditar">Fecha inicio</label>
                            <input type="date" class="form-control" id="promofechainicialeditar" name="promofechainicialeditar" required>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="promofechafinaleditar">Fecha fin</label>
                            <input type="date" class="form-control" id="promofechafinaleditar" name="promofechafinaleditar" required>
                          </div>
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
      </div><!-- /.modal editar promociones -->

      <!-- center modal form editar conceptos -->
      <div id="modal-editar-concepto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="custom-width-modalLabel">Editar concepto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para editar el concepto</h4>
                      <form id="form-editarconcepto" type="post">
                        <div class="form-group">
                          <label for="editarSelectInstitucionConceptos">Elige institución:</label>
                          <select class="form-control" name="editarSelectInstitucionConceptos" id="editarSelectInstitucionConceptos" required>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="editarNombreConcepto">Nombre del concepto:</label>
                          <input type="text" class="form-control" id="editarNombreConcepto" name="editarNombreConcepto" placeholder="Ingresa el nombre de la promoción">
                        </div>
                        <div class="form-group">
                          <label for="editarPrecio">Precio (MXN):</label>
                          <input type="number" class="form-control" id="editarPrecio" name="editarPrecio" step="0.01" min="0.01" placeholder="Ingresa el precio" required>
                        </div>
                        <div class="form-group">
                          <label for="editarPrecio_usd">Precio (USD):</label>
                          <input type="number" class="form-control" id="editarPrecio_usd" name="editarPrecio_usd" step="0.01" min="0.01" placeholder="Ingresa el precio" >
                        </div>
                        <div class="form-group">
                          <label for="editarParcialidades">Parcialidades:</label>
                          <select class="form-control" name="editarParcialidades" id="editarParcialidades" required>
                            <option value="" selected="true" disabled="disabled">Seleccione</option>
                            <option value="1">Sí</option>
                            <option value="2">No</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="editarDescripcion">Descripcion:</label>
                          <textarea class="form-control" id="editarDescripcion" name="editarDescripcion" row="4" cols="50" placeholder="Ingresa tu descripción" required></textarea>
                        </div>
                        <div class="text-right">
                          <input type="hidden" id="idC" name="idC">
                          <button type="submit" class="btn btn-primary waves-effect waves-light">Modificar</button>
                          <button type="button" name="ocultarConpEdit" id="ocultarConpEdit" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                        </div>
                      </form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal editar conceptos -->

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
                      <h4 class="m-t-0 m-b-30">Ingresa la información para crear carrera</h4>
                      <form id="crearcarrera" type="post">
                        <div class="form-group">
                          <label for="select-institucion">Selecciona la institución</label>
                          <select class="form-control" id="select-institucion" name="selectinstitucion" required>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="crearnombrecarrera">Nombre de la carrera:</label>
                          <input type="text" class="form-control" id="crearnombrecarrera" name="crearnombrecarrera" placeholder="Ingresa el nombre de la carrera" required>
                        </div>
                        <div class="form-group">
                          <label for="crearclavecarrera">Nombre clave</label>
                          <input type="text" class="form-control" id="crearclavecarrera" name="crearclavecarrera" placeholder="Ingrese el nombre clave de la carrera" required>
                          <div class="clavecarrera alert alert-danger" style="display: none">Cambiar nombre clave</div>
                        </div>
                        <div class="form-group">
                          <label for="select-tipo">tipo</label>
                          <select class="form-control" id="select-tipo" name="selecttipo" required>
                            <option selected="true" value="" disabled="disabled">Seleccione</option>
                            <option value="1">Certificación</option>
                            <option value="2">TSU</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="select-modalidad">Modalidad carrera</label>
                          <select class="form-control" id="select-modalidad" name="selectmodalidad" required>
                            <option selected="true" value="" disabled="disabled">Seleccione</option>
                            <option value="Presencial">Presencial</option>
                            <option value="En linea">En linea</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="select-duracionmeses">Duracón total (meses)</label>
                          <select class="form-control" id="select-duracionmeses" name="selectduracionmeses" required>
                            <option selected="true" value="" disabled="disabled">Seleccione</option>
                            <option value="1">24 meses</option>
                            <option value="2">30 meses</option>
                            <option value="3">12 meses</option>
                            <option value="4">6 meses</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="select-tipo-ciclo">Tipo de ciclo:</label>
                          <select class="form-control" id="select-tipo-ciclo" name="selecttipociclo" required>
                            <option selected="true" disabled="disabled">Seleccione</option>
                            <option value="1">Cuatrimestre</option>
                            <option value="2">Semestre</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="crearcodigopromocional">Código promocional:</label>
                          <input type="text" class="form-control" id="crearcodigopromocional" name="crearcodigopromocional" placeholder="Ingresa el código promocional" required>
                        </div>
                        <div class="form-group">
                          <label for="creardireccion">Dirección:</label>
                          <input type="text" class="form-control" id="creardireccion" name="creardireccion" placeholder="Ingresa calle número y colonia" required>
                        </div>
                        <div class="form-group">
                          <label for="select-pais">País:</label>
                          <select class="form-control" id="select-pais" name="selectpais" required>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="select-estado">Estado:</label>
                          <select class="form-control" id="select-estado" name="selectestado" required>
                            <option selected="true" disabled="disabled">Seleccione</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="select-plantilla">Plantilla bienvenida:</label>
                          <select class="form-control" id="select-plantilla" name="selectplantilla" required>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="crearimagen">Imagen:</label>
                          <input type="file" class="form-control" name="imagen" id="imagencarrera" accept="image/*" required>
                          <div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1170 x 520</div>
                          <img src="" id="vImagencarrera" class="img-fluid" alt="Responsive image" style="display: none;">
                        </div>
                        <div class="form-group">
                          <label for="crearimagenfondo">Imagen de fondo:</label>
                          <input type="file" class="form-control" name="imgFondo" id="imgFondocarrera" accept="image/*" required>
                          <div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1920 x 895</div>
                          <img src="" id="vFondocarrera" class="img-fluid" alt="Responsive image" style="display: none;">
                        </div>
                        <div class="form-group">
                          <label for="crearfechainicio">Fecha de inicio:</label>
                          <input type="date" class="form-control" id="crearfechainicio" name="crearfechainicio" placeholder="fecha de inicio de cursos" required>
                        </div>
                        <div class="form-group">
                          <label for="crearfechafin">Fecha fin:</label>
                          <input type="date" class="form-control" id="crearfechafin" name="crearfechafin" placeholder="fecha de fin de cursos" required>
                        </div>
                        <div class="text-right">
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

      <!-- center modal form alta eventos -->
      <div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="crear-eventoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="crear-eventoLabel">Crear evento</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- Basic example -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="m-t-0 m-b-30">Ingresa la información para crear el evento</h4>
                      <form id="formularioRegistrar">
												<div class="form-group row">
													<label for="imagen" class="col-sm-3 control-label">Imagen</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" name="imagen" id="imagen" accept="image/*" required>
														<div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1170 x 520</div>
														<img src="" id="vImagen" class="img-fluid" alt="Responsive image" style="display: none;">
													</div>
												</div>
												<div class="form-group row">
													<label for="imgFondo" class="col-sm-3 control-label">Fondo</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" name="imgFondo" id="imgFondo" accept="image/*" required>
														<div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1920 x 895</div>
														<img src="" id="vFondo" class="img-fluid" alt="Responsive image" style="display: none;">
													</div>
												</div>
												<div class="form-group row">
													<label for="tipo" class="col-sm-3 control-label">Tipo evento</label>
													<div class="col-sm-9">
														<select class="form-control" name="tipo" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="CONGRESO">CONGRESO</option>
															<option value="AFILIACION">AFILIACIÓN</option>
															<!--<option>Tipo n</option>-->
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="titulo" class="col-sm-3 control-label">Título del evento</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Nombre del evento" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="nombreClave" class="col-sm-3 control-label">Nombre Clave</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="nombreClave" name="nombreClave" placeholder="Definirá URL" onkeypress="return check(event)" required>
														<div class="clave alert alert-danger" style="display: none">Cambiar nombre clave</div>
													</div>
												</div>
												<div class="form-group row">
													<label for="fechaE" class="col-sm-3 control-label">Fecha evento</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" name="fechaE" id="fechaE" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="fechaDisponible" class="col-sm-3 control-label">Fecha disponibilidad</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" name="fechaDisponible" id="fechaDisponible" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="fechaLimite" class="col-sm-3 control-label">Fecha caducidad</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" id="fechaLimite" name="fechaLimite" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="limiteProspectos" class="col-sm-3 control-label">Número Asistentes</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="limiteProspectos" name="limiteProspectos" placeholder="Número máximo de asistentes" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="duracion" class="col-sm-3 control-label">Duración</label>
													<div class="col-sm-4">
														<input type="number" class="form-control" id="duracion" name="duracion" placeholder="Numérico" required>
													</div>
													<div class="col-sm-4">
														<select class="form-control" name="tipoDuracion" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="h">Hora</option>
															<option value="d">Día</option>
															<option value="s">Semana</option>
															<option value="m">Mes</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="pais" class="col-sm-3 control-label">País</label>
													<div class="col-sm-9">
														<select class="form-control" name="pais" id="pais" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="estado" class="col-sm-3 control-label">Estado</label>
													<div class="col-sm-9">
														<select class="form-control" name="estado" id="estado" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="direccion" class="col-sm-3 control-label">Dirección</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección del evento" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="modalidadEvento" class="col-sm-3 control-label">Modalidad</label>
													<div class="col-sm-9">
														<select class="form-control" name="modalidadEvento" required>
															<option selected="true" disabled="disabled">Seleccione</option>
															<option value="Presencial">Presencial</option>
															<option value="Online">Online</option>
															<option value="Mixta">Mixta</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="idInstitucion" class="col-sm-3 control-label">Institución</label>
													<div class="col-sm-9">
														<select class="form-control" name="idInstitucion" id="idInstitucion" required>
															<!--<option selected="true" disabled="disabled">Seleccione</option>-->
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="codigoPromocional" class="col-sm-3 control-label">Código Promocional</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="codigoPromocional" name="codigoPromocional" required>
													</div>
												</div>
												<div class="form-group row">
													<label for="plantilla_bienvenida" class="col-sm-3 control-label">Plantilla</label>
													<div class="col-sm-9">
														<select class="form-control" name="plantilla_bienvenida" id="plantilla_bienvenida" required>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="descripcion" class="col-sm-3 control-label">Descripción</label>
													<div class="col-sm-9">
														<textarea class="form-control" name="descripcion" id="descripcion" row="4" cols="50" placeholder="Ingresa tu descripción" required></textarea>
													</div>
												</div>
												<div class="form-group row">
													<label for="costoevento" class="col-sm-3 control-label">Costo</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="costoevento" name="costoevento" required>
													</div>
												</div>
												<div class="form-group">
													<div>
														<button type="submit" class="btn btn-primary waves-effect waves-light" id="Enviar">
															Registrar
														</button>
														<button type="reset" id="reiniciar" class="btn btn-secondary waves-effect m-l-5">
															Cancelar
														</button>
													</div>
												</div>
											</form>
                    </div><!-- card-body -->
                  </div> <!-- card -->
                </div> <!-- col-->
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal eventos -->

      <!-- Modal editar evento -->
      <div class="modal fade bs-example-modal-lg" id="modalModify" tabindex="-1" role="dialog" aria-labelledy="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="myLargeModalLabel">Formulario Modificar</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body">
              <form id="formularioModificar">
                <div class="form-group row">
                  <label for="newImagen" class="col-sm-3 control-label">Imagen</label>
                  <div class="col-sm-9">
                    <input type="file" class="form-control" name="newImagen" id="newImagen">
                    <br>
                    <img src="" id="devImagen" class="img-fluid" alt="Responsive image">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="newFondo" class="col-sm-3 control-label">Fondo</label>
                  <div class="col-sm-9">
                    <input type="file" class="form-control" name="newFondo" id="newFondo">
                    <br>
                    <img src="" id="devFondo" class="img-fluid" alt="Responsive image">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devTipo" class="col-sm-3 control-label">Tipo evento</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="devTipo" id="devTipo" required>
                      <option selected="true" disabled="disabled">Seleccione</option>
                      <option value="CONGRESO">CONGRESO</option>
                      <option value="AFILIACION">AFILIACIÓN</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devTitulo" class="col-sm-3 control-label">Título del evento</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="devTitulo" id="devTitulo" placeholder="Nombre del evento" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="nomClave" class="col-sm-3 control-label">Nombre Clave</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="devClave" id="devClave" placeholder="Definirá URL" onkeypress="return check(event)" required>
                    <div class="devMessC alert alert-danger" style="display: none">Cambiar nombre clave</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devFE" class="col-sm-3 control-label">Fecha evento</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" name="devFE" id="devFE" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devFD" class="col-sm-3 control-label">Fecha disponibilidad</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" name="devFD" id="devFD" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devFL" class="col-sm-3 control-label">Fecha caducidad</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" name="devFL"  id="devFL" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devLimite" class="col-sm-3 control-label">Número Asistentes</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="devLimite" id="devLimite" placeholder="Número máximo de asistentes" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devDuracion" class="col-sm-3 control-label">Duración</label>
                  <div class="col-sm-4">
                    <input type="number" class="form-control" name="devDuracion" id="devDuracion" placeholder="Numérico" required>
                  </div>
                  <div class="col-sm-4">
                    <select class="form-control" name="devTipoD" id="devTipoD" required>
                      <option selected="true" disabled="disabled">Seleccione</option>
                      <option value="h">Hora</option>
                      <option value="d">Día</option>
                      <option value="s">Semana</option>
                      <option value="m">Mes</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devPais" class="col-sm-3 control-label">País</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="devPais" id="devPais" required>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devEstado" class="col-sm-3 control-label">Estado</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="devEstado" id="devEstado" required>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devDireccion" class="col-sm-3 control-label">Dirección</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="devDireccion" id="devDireccion" placeholder="Dirección del evento" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devModalidad" class="col-sm-3 control-label">Modalidad</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="devModalidad" id="devModalidad" required>
                      <option selected="true" disabled="disabled">Seleccione</option>
                      <option value="Presencial">Presencial</option>
                      <option value="Online">Online</option>
                      <option value="Mixta">Mixta</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devIDInst" class="col-sm-3 control-label">Institución</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="devIDInst" id="devIDInst" required>
                      <option selected="true" disabled="disabled">Seleccione</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devPromocion" class="col-sm-3 control-label">Código Promocional</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="devPromocion" id="devPromocion" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="devDescripcion" class="col-sm-3 control-label">Descripción</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" name="devDescripcion" id="devDescripcion" row="4" cols="50" placeholder="Ingresa tu descripción" required></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="newPlantilla" class="col-sm-3 control-label">Plantilla</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="newPlantilla" id="newPlantilla" required>
                      <option selected="true" disabled="disabled">Seleccione</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div>
                    <input type="hidden" name="idModify" id="idModify">
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
      </div> <!-- Fin del modal modificar eventos-->

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
                      <label for="devinstitucion">Selecciona la institución</label>
                      <select class="form-control" id="devinstitucion" name="devinstitucion" required>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="devnombrecarrera">Nombre de la carrera:</label>
                      <input type="text" class="form-control" id="devnombrecarrera" name="devnombrecarrera" placeholder="Ingresa el nombre de la carrera" required>
                    </div>
                    <div class="form-group">
                      <label for="devclavecarrera">Nombre clave</label>
                      <input type="text" class="form-control" id="devclavecarrera" name="devclavecarrera" placeholder="Ingrese el nombre clave de la carrera" required>
                      <div class="devMessC alert alert-danger" style="display: none">Cambiar nombre clave</div>
                    </div>
                    <div class="form-group">
                      <label for="devtipocarrera">tipo</label>
                      <select class="form-control" id="devtipocarrera" name="devtipo" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Certificación</option>
                        <option value="2">TSU</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="devmodalidadcarrera">Modalidad carrera</label>
                      <select class="form-control" id="devmodalidadcarrera" name="devmodalidad" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="Presencial">Presencial</option>
                        <option value="En linea">En linea</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="devduracionmeses">Duracón total (meses)</label>
                      <select class="form-control" id="devduracionmeses" name="devduracionmeses" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">24 meses</option>
                        <option value="2">30 meses</option>
                        <option value="3">12 meses</option>
                        <option value="4">6 meses</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="devtipociclo">Tipo de ciclo:</label>
                      <select class="form-control" id="devtipociclo" name="devtipociclo" required>
                      <option selected="true" disabled="disabled">Seleccione</option>
                      <option value="1">Cuatrimestre</option>
                      <option value="2">Semestre</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="devcodigopromocional">Código promocional:</label>
                      <input type="text" class="form-control" id="devcodigopromocional" name="devcodigopromocional" placeholder="Ingresa el código promocional" required>
                    </div>
                    <div class="form-group">
                      <label for="devdireccioncarrera">Dirección:</label>
                      <input type="text" class="form-control" id="devdireccioncarrera" name="devdireccioncarrera" placeholder="Ingresa calle número y colonia" required>
                    </div>
                    <div class="form-group">
                      <label for="devpaiscarrera">País:</label>
                      <select class="form-control" id="devpaiscarrera" name="devpais" required>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="devestadocarrera">Estado:</label>
                      <select class="form-control" id="devestadocarrera" name="devestado" required>
                        <option selected="true" disabled="disabled">Seleccione</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="devplantillacarrera">Plantilla bienvenida:</label>
                      <select class="form-control" id="devplantillacarrera" name="devplantilla" required>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="newimagencarrera">Imagen:</label>
                      <input type="file" class="form-control" name="newimagen" id="newimagencarrera" accept="image/*">
                      <div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1170 x 520</div>
                      <img src="" id="devimagencarrera" class="img-fluid" alt="Responsive image">
                    </div>
                    <div class="form-group">
                      <label for="newfondocarrera">Imagen de fondo:</label>
                      <input type="file" class="form-control" name="newfondo" id="newfondocarrera" accept="image/*">
                      <div class="clave alert alert-info">Las imagenes deben de tener una resolución de 1920 x 895</div>
                      <img src="" id="devfondocarrera" class="img-fluid" alt="Responsive image">
                    </div>
                    <div class="form-group">
                      <label for="devcrearfechainicio">Fecha de inicio:</label>
                      <input type="date" class="form-control" id="devcrearfechainicio" name="devcrearfechainicio" placeholder="fecha de inicio de cursos" required>
                    </div>
                    <div class="form-group">
                      <label for="devcrearfechafin">Fecha fin:</label>
                      <input type="date" class="form-control" id="devcrearfechafin" name="devcrearfechafin" placeholder="fecha de fin de cursos" required>
                    </div>
                    <div class="text-right">
                      <input type="hidden" name="id_carrera" id="id_carrera">    
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
                      <button type="button" name="ocultar2" id="ocultar2" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
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
                    <!--<div class="form-group">
                      <label for="">Selecciona el plan de pagos</label>
                      <select class="form-control" name="selectPago" id="selectPago" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Plan 1</option>
                        <option value="2">Plan 2</option>
                        <option value="3">Plan 3</option>
                      </select>
                    </div>-->
                    <div class="form-group">
                      <label for="nombreG">Nombre de la generación:</label>
                      <input type="text" class="form-control" id="nombreG" name="nombreG" placeholder="Ingresa el nombre de la carrera" required>
                    </div> 
                    <div class="form-group">
                      <label for="selectCarrer">Selecciona las carreras</label>
                      <select class="form-control" id="selectCarrer" name="selectCarrer[]" multiple required><!--selectpicker-->
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="fechainicio">Fecha de inicio:</label>
                      <input type="date" class="form-control" id="fechainicio" name="fechainicio" placeholder="fecha de inicio de la generación" required>
                    </div>
                    <div class="form-group">
                      <label for="fechafin">Fecha fin:</label>
                      <input type="date" class="form-control" id="fechafin" name="fechafin" placeholder="fecha de fin de la generación" required>
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
                      <label for="modselectPago">Selecciona el plan de pagos</label>
                      <select class="form-control" name="modselectPago" id="modselectPago" required>
                        <option selected="true" value="" disabled="disabled">Seleccione</option>
                        <option value="1">Plan 1</option>
                        <option value="2">Plan 2</option>
                        <option value="3">Plan 3</option>
                      </select>
                    </div>-->
                    <div class="form-group">
                      <label for="modnombreG">Nombre de la generación:</label>
                      <input type="text" class="form-control" id="modnombreG" name="modnombreG" placeholder="Ingresa el nombre de la carrera" required>
                    </div> 
                    <div class="form-group">
                      <label for="modselectCarrer">Selecciona las carreras</label>
                      <select class="form-control" id="modselectCarrer" name="modselectCarrer[]" multiple required><!--selectpicker-->
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="modfechainicio">Fecha de inicio:</label>
                      <input type="date" class="form-control" id="modfechainicio" name="modfechainicio" placeholder="fecha de inicio de la generación" required>
                    </div>
                    <div class="form-group">
                      <label for="modfechafin">Fecha fin:</label>
                      <input type="date" class="form-control" id="modfechafin" name="modfechafin" placeholder="fecha de fin de la generación" required>
                    </div>
                    <div class="text-right">
                      <input type="hidden" name="selectAnterior" id="selectAnterior">
                      <input type="hidden" name="idG" id="idG">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Modificar</button>
                      <button type="button" name="ocultar4" id="ocultar4" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div> <!--end-modal-->

      <!-- center modal form alta eventos -->
      <div id="sendBilling" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="crear-eventoLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title m-0" id="crear-eventoLabel">Subir Documentos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <!-- Basic example -->
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="m-t-0 m-b-30">Selecciona los archivos a subir</h4>
                        <form id="formularioFactura">
                          <div class="form-group">
                            <input type="hidden" name="idPayment" id="idPayment">
                          </div>
                          <div class="form-group row">
                            <label for="imagen" class="col-sm-2 control-label">PDF</label>
                            <div class="col-sm-10">
                              <input type="file" class="form-control" name="pdf" id="pdf" accept="application/pdf" required>
                              <div class="clave alert alert-info">Sube el archivo generados desde el portal de SAT</div>
                              <img src="" id="vImagen" class="img-fluid" alt="Responsive image" style="display: none;">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="imagen" class="col-sm-2 control-label">XML</label>
                            <div class="col-sm-10">
                              <input type="file" class="form-control" name="xml" id="xml" accept="text/xml" required>
                              <div class="clave alert alert-info">Sube el archivo generados desde el portal de SAT</div>
                              <img src="" id="vImagen" class="img-fluid" alt="Responsive image" style="display: none;">
                            </div>
                          </div>
                          <button class="btn btn-primary waves-effect waves-light" style="display:block; margin-left:auto;">Guardar</button>
                        </form>
                      </div><!-- card-body -->
                    </div> <!-- card -->
                  </div> <!-- col-->
                </div>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div>

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
      <script src="../assets/js/template/jquery.numeric.js"></script>

      <!--Sweet Alert 2-->
	    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	    <script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>

      <!-- select2 -->
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        $(".onlyNumer").on('keypress',function(evt){
          if (evt.which < 46 || evt.which > 57){
            evt.preventDefault();
          }
        })
      </script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/planpagos/planpagos.js"></script>
      <script src="../assets/js/planpagos/generaciones.js"></script>
      <script src="../assets/js/planpagos/carreras.js"></script>
      <script src="../assets/js/planpagos/promociones.js"></script>
      <script src="../assets/js/planpagos/conceptos.js"></script>
      <script src="../assets/js/eventos/eventosJs.js"></script>
      <script src="../assets/js/planpagos/fechascorte.js"></script>
      <script src="../assets/js/adminwebex/horas_docentes.js"></script>


      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>
