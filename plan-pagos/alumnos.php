<?php
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

    <!-- DataTables (Tablas) CSS -->
    <link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	  
	  <link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
    
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
        .modal-lg{
          max-width: 90% !important;
        }
        .text-blue{
          color: #5050f1;
          font-weight: bold;
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
                <a href="../index.html" class="logo">
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
                    <a href="../hoteles/index.php"><i class="fas fa-plane-departure"></i> Visita Alumnos</a>
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
                  <div class="col-6 col-md-12 mb-2">
                    <h4 class="page-title m-0">
                      <span tab-target="eventos" class="tab_active">
                        <i class="ti-briefcase"></i> Reportes de Pago Alumnos
                      </span>|
                      <span tab-target="generaciones">
                        <i class="fas fa-graduation-cap"></i> Reportes por Generación
                      </span>|
                      <span tab-target="carreras">
                        <i class="fas fa-fax"></i> Reportes por Carreras
                      </span> |
                      <span id="tab_tabla_prorrogas" tab-target="prorrogas">
                        <i class="fas fa-graduation-cap"></i> Prorrogas
                      </span> |
                      
                      <span id="tab_certificaciones" tab-target="certificaciones">
                        <i class="fas fa-file-alt"></i> Certificaciones
                      </span>
                      <!--<span tab-target="conceptos">
                        <i class="fas fa-money-bill-alt"></i> Reporte de Carreras
                      </span> |
                      <span tab-target="promociones">
                        <i class="fas fa-percent"></i> Reporte de Eventos
                      </span> |
                      <span tab-target="carreras">
                        <i class="fas fa-user-graduate"></i> carreras
                      </span> -->
                    </h4>
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
                    <div class="col-lg-12">
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" id="home-tab" href="#home" role="tab" aria-controls="home"  aria-selected="false" data-target="#home">
                            <span class="d-block d-sm-none"><i class="fas fa-tasks"></i></span>
                            <span class="d-none d-sm-block">Pagos recibidos</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="profile-tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true" data-target="#profile" onclick="cargar_pagos_reportados()">
                            <span class="d-block d-sm-none"><i class="fas fa-list-ul"></i></span>
                            <span class="d-none d-sm-block">Pendientes</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="pendientes-tab" href="#pendientes" role="tab" aria-controls="pendientes" aria-selected="true" data-target="#pendientes" onclick="cargar_pagos_rechazados()">
                            <span class="d-block d-sm-none"><i class="fas fa-list-ul"></i></span>
                            <span class="d-none d-sm-block">Rechazados</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="subirpagos-tab" href="#subirpagos" role="tab" aria-controls="subirpagos" aria-selected="true" data-target="#subirpagos">
                            <span class="d-block d-sm-none"><i class="fas fa-list-ul"></i></span>
                            <span class="d-none d-sm-block">Subir pago</span>
                          </a>
                        </li>
                      </ul>
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <div class="col-sm-12 TBNR table-responsive">
                            <h3>Listado de pagos por alumnos</h3>
                            <!-- <table id="table-alumnos-pagos" class="table table-striped table-bordered dt-responsive nowrap w-100 d-none" >
                              <thead>
                                <tr>
                                  <th>Nombre completo</th>
                                  <th>Concepto</th>
                                  <th>Origen</th>
                                  <th>Ejecutiva</th>
                                  <th>Detalles</th>
                                  <th>Fecha limite de pago</th>
                                  <th>Moneda</th>
                                  <th>Monto por pagar</th>
                                  <th>Fecha pago</th>
                                  <th>Monto pagado / Reportado</th>
                                  <th>Recargo faltante por pagar</th>
                                  <th>Método de pago</th>
                                  <th>A que banco depositó</th>
                                  <th>Comentario</th>
                                  <th>Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              <tfoot class="bg-secondary">
                                <tr class="text-light">
                                  <td class="border border-secondary" id="foot_total_pagos">Total de pagos:</td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                </tr>
                              </tfoot>
                            </table> -->
                            <hr class="my-3">
                            <table id="concentrado-alumnos" class="table table-striped table-bordered dt-responsive nowrap w-100" data-order='[[0, "asc"]]'>
                              <thead>
                                <th>Alumno</th>
                                <th>Contacto</th>
                                <th for-filter>Generación</th>
                                <th>Estado de cuenta</th>
                              </thead>
                              <tbody></tbody>
                            </table>
                          </div>  
                        </div>
                        <div class="row tab-pane fade  TBNR table-responsive" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <h3>Listado de pagos por verificar</h3>
                          <table id="table-pagos-notificados" class="table table-striped table-bordered dt-responsive nowrap w-100" >
                            <thead>
                              <tr>
                                <th>Nombre completo</th>
                                <th>Concepto</th>
                                <th for-filter>Carrera</th>
                                <th for-filter strict>Generación</th>
                                <th>Origen</th>
                                <th>Ejecutiva</th>
                                <th>Fecha limite de pago</th>
                                <th>Moneda</th>
                                <th>Monto por pagar</th>
                                <th>Fecha pago</th>
                                <th>Monto pagado / Reportado</th>
                                <th>Saldo pendiente</th>
                                <th>Comprobante</th>
                                <th>Verificar</th>
                                <th>Método de pago</th>
                                <th>A que banco depositó</th>
                                <th>Comentario</th>
                                <th>Opciones</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot class="bg-secondary">
                                <tr class="text-light">
                                  <td class="border border-secondary" id="foot_total_reportados">Total de pagos:</td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                  <td class="border border-secondary"></td>
                                </tr>
                              </tfoot>
                          </table>
                        </div>
                        <div class="row tab-pane fade TBNR table-responsive" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                        <h3>Listado de pagos rechazados</h3>
                          <table id="table-pagos-rechazados" class="table table-striped table-bordered dt-responsive nowrap w-100" >
                            <thead>
                              <tr>
                                <th>Nombre completo</th>
                                <th>Concepto</th>
                                <th for-filter>Carrera</th>
                                <th for-filter strict>Generación</th>
                                <th>Origen</th>
                                <th>Ejecutiva</th>
                                <th>Fecha pago</th>
                                <th>Moneda</th>
                                <th>Monto pagado</th>
                                <th>Comprobante</th>
                                <th>Observación</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                        <div class="row tab-pane fade  TBNR" id="subirpagos" role="tabpanel" aria-labelledby="subirpagos-tab">
                        <h3>Listado de pagos subir</h3>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label for="">Buscar un alumno por nombre o correo <i>(Presione enter para buscar)</i></label>
                              <input type="text" class="form-control" placeholder="Nombre del alumno" id="inp_busca_alumno">
                            </div>
                          </div>

                          <div class="col-sm-12 col-md-6">
                            <select class="form-control" name="seleccionacarrerasubirpago" id="seleccionacarrerasubirpago">
                            </select>
                          </div>
                          <div class="col-sm-12 col-md-6">
                            <select id="listar-generacion-subirpago" name="listar-generacion-subirpago" class="form-control" style="display:none">
                            </select>
                          </div>
                        </div>
                        <br>
                          <table id="table-pagos-subirpagos" class="table table-striped table-bordered dt-responsive nowrap w-100" >
                            <thead>
                              <tr>
                                <th>NOMBRE COMPLETO</th>
                                <th>CORREO</th>
                                <th>CARRERA</th>
                                <th>GENERACIÓN</th>
                                <th>ACCIONES</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- FIN CONTENEDOR DE TABS -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE REPORTES POR GENERACIONES -->
            <div class="col-sm-12" id="tab_concentrado_generaciones" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div clas="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <select id="list-carrera-gen" name="list-carrera-gen" class="form-control mb-4">

                      </select>
                    </div>
                    <div id="mostrarselectgeneraciones" class="col-lg-4 col-md-6 col-sm-12 m-t-10" style="display:none">
                      <select id="list-generacion-gen" name="list-generacion-gen" class="form-control">

                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h3 class="ml-4">Listado de alumnos</h3>
                               <br>
                              <h5>Total: <span id="totalalumnosgeneracion"></span></h5>

                              <table id="table-alumnos-generacion" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>NOMBRE</th>
                                    <th>CONCEPTO</th>
                                    <th>FECHA PAGO</th>
                                    <th>MONTO PAGADO</th>
                                    <th>ESTATUS PAGO</th>
                                    <th>ORIGEN</th>
                                    <th>DETALLES</th>
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

             <!-- SECCION PARA CONCENTRADO DE REPORTES POR GENERACIONES TOTALES GENERALES-->
             <div class="col-sm-12" id="tab_concentrado_carreras" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div clas="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <select id="list-carrera-totales" name="list-carrera-totales" class="form-control">

                      </select>
                      <br>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h3 class="ml-4">Listado de alumnos</h3>
                              <table id="table-alumnos-carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>NOMBRE</th>
                                    <th>EMAIL</th>
                                    <th>GENERACIÓN</th>
                                    <th>MONTO PAGADO</th>
                                    <th>POR PAGAR</th>
                                    <th>COSTO TOTAL</th>
                                    <th>MONEDA</th>
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

            <!-- SECCION PARA CONCENTRADO DE PRORROGAS -->
            <div class="col-sm-12" id="tab_concentrado_prorrogas" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h3 class="ml-4">Listado de prorrogas</h3>

                              <table id="table-alumnos-prorrogas" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>NOMBRE</th>
                                    <th>CONCEPTO</th>
                                    <th>ESTATUS</th>
                                    <th>FECHA SOLICITUD</th>
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
            <!-- SECCION  PARA CONCENTRADO DE PROSPECTOS PARA VALIDACION EN PLAN-PAGOS -->
            <div class="col-sm-12" id="tab_concentrado_certificaciones" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="table-responsive text-center">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Listado de Prospectos</h5>

                              <div class="row form-group">
                                <div class="col-md-6">
                                  <label for="carrerasCert">Carreras Disponibles</label>
                                  <select class ="form-control" name="carrerasCert" id="carrerasCert" required>
                                    <option selected disabled>Seleccione una opción</option>
                                  </select>
                                </div>

                                <div class="col-md-6">
                                  <label for="genCert">Generaciones Disponibles</label>
                                  <select class ="form-control" name="genCert" id="genCert" required>
                                    <option selected disabled>Seleccione una opción</option>
                                  </select>
                                </div>
                              </div>

                              <table id="table-alumnos-certificaciones" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>NOMBRE</th>
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
            <!-- SECCION PARA CONCENTRADO Reporte de carreras -->
            <div class="col-sm-12" id="tab_concentrado_conceptos" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                            <h5>Reporte de Carreras</h5>
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
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                            <h5>Reporte de Eventos</h5>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE CARRERAS -->
            <div class="col-sm-12" id="tab_concentrado_carreras" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <h5>Reporte de carreras</h5>

                              <table id="table-carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Tipo</th>
                                    <th>Titulo</th>
                                    <th>Fecha evento</th>
                                    <th>Duración</th>
                                    <th>Cupo</th>
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
      <div id="modal_ver_prorroga" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_ver_prorrogaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="modal_ver_prorrogaLabel">Solicitante: <span id="nombre_solicitante_alumno"></span> </h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <h4>Concepto: </h4>
              <p id="concepto_prorroga_solicitante"></p>
              <hr>
              <div>
                <h4>Número de pago: </h4>
                <p id="numero_pago_prorroga_solicitante"></p>
                <hr>
              </div>
              <h4>Motivo de la prorroga: </h4>
              <p id="descripcion_prorroga_solicitante"></p>
              <hr>
              <div class="row">
                <div class="col-sm-12 col-lg-6">
                  <h4>Fecha limite de pago</h4>
                    <p id="fecha_limite_pago_prorroga"></p>
                </div>
                <div class="col-sm-12 col-lg-6">
                  <h4>Fecha solicitada</h4>
                  <p type="date" id="nueva_fecha_pago_alumno"></p>
                  <input type="hidden" id="idprorrogasolicitante">
                  <input type="hidden" id="idAsistente_solocitudprorroga">
                </div>
              </div>
            </div>
            <div class="modal-footer">
            <h4 id="letrero_aceptada_rechazada" style="display:none"></h4>
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
              <div id="mostrar_opciones_prorroga" style="display:none">
                <button id="rechazar_prorroga" type="button" class="btn btn-primary waves-effect waves-light">Rechazar prorroga</button>
                <button id="aceptar_prorroga" type="button" class="btn btn-primary waves-effect waves-light">Aceptar prorroga</button>
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <!--  Modal content for the above example -->
      <div class="modal fade modal-modificar-fechapago" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-0" id="myLargeModalLabel">Modificar pago</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
            <form id="form-modificar-fecha-pago" type="post">
              <div class="row">
                <div class="col-sm-12 col-md-6 mb-3">
                <label for="nuevafechadepago">Fecha de pago:</label>
                  <input type="date" class="form-control" id="nuevafechadepago" name="nuevafechadepago">
                  <input type="hidden" name="id_pago_modificar" id="id_pago_modificar">
                  <input type="hidden" name="enviarconcepto" id="enviarconcepto">
                </div>
                <div class="col-sm-12 col-md-6 mb-3">

                  <label for="modificarbancopago">¿En que banco realizó el pago</label>
                  <select class="form-control" name="modificarbancopago" id="modificarbancopago" required>
                    <option disabled="disabled" value="">Seleccione un banco</option>
                    <option value="Banorte 0823622605">Banorte (082 362 2605)</option>
                    <option value="Inbursa 50060654011">Inbursa 50060654011</option>
                    <option value="No aplica">No aplica</option>
                  </select>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                  <label for="metododepago1modificar">¿Cómo realizó el pago?</label>
                  <select class="form-control" name="metodo_de_pago_1" id="metododepago1modificar" required>
                    <option value="" disabled selected>Seleccione cómo realizó el pago</option>
                    <option value="1">Pago en cuenta referenciada</option>
                    <option value="2">Pago en ventanilla cuenta general</option>
                    <option value="4">Pago en cajero automático</option>
                    <option value="5">Pago en en departamento de cobranza</option>
                    <option value="6">Transferencia eletrónica</option>
                  </select>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                  <label for="modificarmedotodepago">Seleccione el método de pago</label>
                  <select class="form-control" name="modificarmedotodepago" id="modificarmedotodepago" required>
                    <option id="noselect" value="" disabled selected>Seleccione una forma de pago</option>
                    <option id="pagoenefectivo" style="display:none" value="Pago en efectivo">Pago en efectivo</option>
                    <option id="chechenominativo" style="display:none" value="Cheque nominativo">Cheque nominativo</option>
                    <option id="tarjetadecredito" style="display:none" value="Tarjeta de crédito">Tarjeta de crédito</option>
                    <option id="tarjetadedebito" style="display:none" value="Tarjeta de débito">Tarjeta de débito</option>
                    <option id="transferenciaelectronica" style="display:none" value="Transferencia eletrónica">Transferencia electrónica</option>
                    <option id="paypal" style="display:none" value="Paypal">Paypal</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 col-md-6 mb-3">
                  <h5>Monto pagado: <span id="totalpagado"></span></h5>
                </div>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Modificar</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
              </div>
            </form>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <!--  Modal content for the above example -->
      <div class="modal fade modal-consultar-historial" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-0" id="myLargeModalLabel">Pagos aplicados</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive overflow-auto">
                    <h5 class="tx-center mb-3">Pagos aplicados</h5>
                    <span id="span_saldo"></span>
                    <table id="table_pagos_apli" action="obtener_pagos_aplicados" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Fecha</th>
                          <th>Concepto</th>
                          <th>Origen</th>
                          <th>Precio lista</th>
                          <th>Promociones</th>
                          <th>Costo</th>
                          <th>Recargos</th>
                          <th>Total a pagar</th>
                          <th>Pago realizado</th>
                          <th>Saldo pendiente</th>
                          <th></th>
                        </tr>
                      </thead>

                      <tbody>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div><!-- row -->
              <br>
              <br>
              <br>
              <div class="row d-none">
                <div class="col-12">
                  <div class="table-responsive overflow-auto">
                    <h5 class="tx-center mb-3">Resumen parcial por carrera</h5>
                    <table id="table_pagos_total_carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                      <thead>
                        <tr>
                          <th>Carrera</th>
                          <th>Costo total</th>
                          <th>Pagado</th>
                          <th>Restante</th>
                        </tr>
                      </thead>

                      <tbody>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div><!-- row -->
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

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
                    <!-- <li class="nav-item">
                        <a class="nav-link active" id="tab_registrar_pag" data-toggle="tab" href="#registrar_pag_pan" role="tab" aria-controls="registrar_pag_pan" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                            <span class="d-none d-sm-block">Registrar</span>
                        </a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="tab_becas_pag" data-toggle="tab" href="#becas_pag_pan" role="tab" aria-controls="becas_pag_pan" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                            <span class="d-none d-sm-block">Promos / Becas</span>
                        </a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="tab_notificados_pag" data-toggle="tab" href="#notificados_pag_pan" role="tab" aria-controls="notificados_pag_pan" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                            <span class="d-none d-sm-block">Notificados</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_generacion_pag" data-toggle="tab" href="#generaciones_pag_pan" role="tab" aria-controls="generaciones_pag_pan" aria-selected="true" style="display:none;">
                            <span class="d-block d-sm-none"><i class="fas fa-clipboard-list"></i></span>
                            <span class="d-none d-sm-block">Generaciones</span>
                        </a>
                    </li> -->
                  </ul>
                  <div class="tab-content bg-light">
                    <div class="tab-pane fade active show" id="registrar_pag_pan" role="tabpanel" aria-labelledby="tab_registrar_pag">
                      <h3 class=""><b id="lbl_persona_pago"></b></h3>
                      <div>
                        <!-- <label class="ckbox">
                          <input type="checkbox" id="check_solo_inscripciones">
                          <span>Mostrar solo conceptos de inscripciones</span>
                        </label> -->
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
                          </div>

                          <div class="form-group col-sm-12 d-none">
                            <label for="inp_promos_disp">Seleccione una promoción</label>
                            <select class="form-control" name="inp_promos_disp" id="inp_promos_disp">
                              <option value="">Seleccione una promocion</option>
                            </select>
                          </div>
                          <div class="form-group col-sm-12">
                            <label for="metododepago1subir">Seleccione cómo realizó el pago</label>
                            <select class="form-control" name="metodo_de_pago_1" id="metododepago1subir" required>
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
                              <option id="noselectsubir" value="" disabled selected>Seleccione una forma de pago</option>
                              <option id="pagoenefectivosubir" style="display:none" value="Pago en efectivo">Pago en efectivo</option>
                              <option id="chechenominativosubir" style="display:none" value="Cheque nominativo">Cheque nominativo</option>
                              <option id="tarjetadecreditosubir" style="display:none" value="Tarjeta de crédito">Tarjeta de crédito</option>
                              <option id="tarjetadedebitosubir" style="display:none" value="Tarjeta de débito">Tarjeta de débito</option>
                              <option id="transferenciaelectronicasubir" style="display:none" value="Transferencia eletrónica">Transferencia electrónica</option>
                              <option id="paypalsubir" style="display:none" value="Paypal">Paypal</option>
                            </select>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="crearbancodedeposito">¿En que banco realizó el pago</label>
                            <select class="form-control" name="crearbancodedeposito" id="crearbancodedeposito" required>
                              <option value="">Seleccione un banco</option>
                              <option value="Banorte 0823622605">Banorte (082 362 2605)</option>
                              <option value="Inbursa 50060654011">Inbursa 50060654011</option>
                              <option value="No aplica">No aplica</option>
                            </select>
                          </div>
                          <div id="notifica_fechap" class="col-12">

                          </div>
                          <div id="notifica_parcialidades" class="col-12">

                          </div>

                          <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                              <label>Fecha pago</label>
                              <input type="date" name="inp_fecha_pago" id="inp_fecha_pago" class="form-control" required="" max="<?php echo(date('Y-m-d')); ?>" value="<?php echo(date('Y-m-d')); ?>">
                            </div>
                          </div>
                          <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                              <label>Monto <span id="tipomonedausdmontomkt"></span></label>
                              <input type="tel" name="inp_monto_pago" id="inp_monto_pago" class="form-control moneyFt" data-prefix="$ " value="$ 0.00" required="">
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
                        </div>
                        <div class="row">
                          <div class="col-sm-3 ml-auto">
                            <button type="submit" class="btn btn-success">Continuar</button>
                          </div>
                        </div>
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
        <div id="container_info_alumno">
          
        </div>
      <!-- end wrapper -->

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
      </script>

      <script src="../assets/js/template/app.js"></script>
      <script src="../assets/js/alumnos/alumnos.js"></script>
      <script src="../assets/js/alumnos/pagos.js"></script>
      <script src="../assets/js/alumnos/reporteporcarreras.js"></script>
      <script src="../assets/js/controlescolar/ver_plan.js"></script>
      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>
