<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 30){
    header("Location: ../index.php");
    die();
}
    $usuario = $_SESSION["usuario"];
    $info = $_SESSION["usuario"]["persona"];
   
    /*var_dump($info);
    die();*/

    require_once( "cx.php" );
    include( "listadoTareas.php" );

?>
<!doctype html>
<html lang="es">
  <head>
    <link rel="stylesheet" href="../assets/css/cropper.css" />
    <script src="../assets/js/cropper.js"></script> 

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
        .swal-wide{
          width:950px !important;
          
        }

        img {
        display: block;
        max-width: 100%;
        }
        .preview {
            overflow: hidden;
            width: 160px; 
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }
        .modal-lg{
        max-width: 1000px !important;
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
                <ul class="mb-0 nav navbar-right ml-auto list-inline">
                  <li class="list-inline-item notification-list d-none d-sm-inline-block">
                    <a href="#" id="btn-fullscreen"
                        class="waves-effect waves-light notification-icon-box"><i 
                        class="fas fa-expand"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="" class="dropdown-toggle profile waves-effect waves-light" 
                    data-toggle="dropdown" aria-expanded="true">
                      <span class="profile-username">
                       <?php echo $usuario["persona"]["nombres"]; ?><span class="mdi mdi-chevron-down font-15"></span>
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <li class="dropdown-divider"></li>
                      <li><a href="../editarAccesos.php" class="dropdown-item"> Cambiar contrase??a</a></li>
                      <li><a href="../log-out.php" class="dropdown-item"> Cerrar sesi??n</a></li>
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
        <div class="container-fluid justify-content-center">
          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-8 col-md-8">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-sm-8">
                    <h4 class="page-title m-0">
                      <span id="tareas" tab-target="tareas">
                        <i class="ti-briefcase"></i> Tareas
                      </span>                                        
                      |
                      <span id="examenes" tab-target="examenes">
                        <i class="fas fa-user-check"></i> Ex??menes
                      </span>
                      |
                      <span id="PerfilUser" tab-target="PerfilUser">
                        <i class="fas fa-user"></i> Perfil
                      </span>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card" id="tab_PerfilUser"  style="display:none">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <center><span class="d-none d-sm-block"><h3>Perfil del docente</h3></span></center>

                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="PerfilUsuario-tab" data-toggle="tab" href="#PerfilUsuario" role="tab" aria-controls="PerfilUsuario"  aria-selected="true">
                        <span class="d-block d-sm-none"><i class="fa fa-users"></i></span>
                        <span class="d-none d-sm-block" >Perfil</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="EditarPerfil-tab" data-toggle="tab" data-target="#EditarPerfil" href="#EditarPerfil" role="tab" aria-controls="EditarPerfil" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-list"></i></span>
                        <span class="d-none d-sm-block">Editar perfil</span>
                      </a>
                    </li>
                  </ul>

                  <div class="tab-content bg-light">

                    <div class="row tab-pane fade show active" id="PerfilUsuario" role="tabpanel" aria-labelledby="PerfilUsuario-tab">
                      <div class="container col-sm-12 col-lg-12 col-md-12 text-center">
                        <div class="card">
                          <div class="card-body">
                            <div class="row align-items-center">
                              <div class="col-md-6 text-align-center">
                                <h3>Docente <br><label id = "NombreUsuarioPerfil"></label></h3><br>
                                <center><img class="rounded-circle img-fluid img-responsive" id="FotoUsuarioPerfil" src="" width="200" heigth="200"></center>
                              </div>
                              <div class="col-md-6">
                                <h4><label class="text-justify" id = "DescripcionUsuarioPerfil"></label></h4>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row tab-pane fade" id="EditarPerfil" role="tabpanel" aria-labelledby="EditarPerfil-tab">
                      <div class="container col-sm-12 col-lg-12">
                        <div class="card">
                          <div class="card-body">
                            <form id="formularioEditarUsuario" type="post">

                              <input type="text" class="form-control" name="id" id="id" value ="<?php echo $info['id'];?>" style="display:none" required>
                                <div class="form-group row">
                                  <div class="form-group col-md-4 col-sm-6">
                                    <label for="Nombre">Nombre: </label>
                                    <input type="text" class="form-control" name="Nombre" id="Nombre" required>
                                  </div>

                                  <div class="form-group col-md-4 col-sm-6">
                                    <label for="ApellidoPaterno">Apellido Paterno:</label>
                                    <input type="text" class="form-control" name="ApellidoPaterno" id="ApellidoPaterno" required>
                                  </div>

                                  <div class="form-group col-md-4 col-sm-6">
                                    <label for="ApellidoMaterno">Apellido Materno:</label>
                                    <input type="text" class="form-control" name="ApellidoMaterno" id="ApellidoMaterno"  required>
                                  </div>
                          
                                  <div class="form-group col-md-4 col-sm-6">
                                    <label for="Sexo">Sexo: </label>
                                    <!--<input type="text" class="form-control" name="Sexo" id="Sexo" required>-->
                                    <select class="form-control" name="Sexo" id="Sexo" required>
                                      <option value="H">Hombre</option>
                                      <option value="M">Mujer</option>
                                    </select>
                                  </div>

                                  <div class="form-group col-md-4 col-sm-6">
                                    <label for="Email">Email:</label>
                                    <input type="text" class="form-control" name="Email" id="Email" required>
                                  </div>

                                  <div class="form-group col-md-4 col-sm-6">
                                    <label for="Telefono">Tel??fono:</label>
                                    <input type="text" class="form-control" name="Telefono" id="Telefono" maxlength="10" required>
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <div class="form-group col-md-12 container">
                                      <label for="Descripcion"><h5>Agregue una breve descripci??n de su perfil:</h5></label>
                                      <textarea  class="form-control" name="Descripcion" id="Descripcion" required></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group col-md-12">
                                    <div class="image_area">
                                      <label for="img">Fotografia:</label>
                                      <input type="file" name="imgArchivo" class="crop_image" id="upload_image" />

                                      <input type="text" name="img" class="crop_image d-none" id="upload_image64" />
                                    </div>
                                  </div>
                                </div>
                                
                                <div class="form-group col-md-12 text-right">
                                  <button type="submit" class="btn btn-primary waves-effect waves-light" id="crewEx">Guardar Cambios</button>
                                </div>

                            </form>  
                          </div>
                        </div>
                      </div>
                    </div><!--end-editarPerfil-->
                  </div><!--end-tab content-->

                </div><!--end-col-->
              </div><!--end-row-->
            </div><!--end-card-body-->
          </div><!--end-card-->

          <div class="modal fade" id="modal_crop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="container">
              <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title">Modificar Foto de Perfil</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">??</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="img-container">
                              <div class="row">
                                  <div class="col-md-8">
                                      <img src="" id="sample_image" />
                                  </div>
                                  <div class="col-md-4">
                                      <div class="preview"></div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" id="crop_and_upload" class="btn btn-primary">Cortar Foto</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                      </div>
                  </div>
              </div>
            </div>
          </div>    



          <div class="card" id="tab_tareas">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <!-- CONTENEDOR DE TABS -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="alumnos-tab" data-toggle="tab" href="#alumnos" role="tab" aria-controls="alumnos"  aria-selected="true">
                        <span class="d-block d-sm-none"><i class="fa fa-users"></i></span>
                        <span class="d-none d-sm-block" >Tareas a revisar</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="listado-tareas-tab" data-toggle="tab" data-target="#listado-tareas" href="#listado-tareas" role="tab" aria-controls="listado-tareas" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-list"></i></span>
                        <span class="d-none d-sm-block">Listado de tareas</span>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="clases-tab" data-toggle="tab" data-target="#clases" href="#clases" role="tab" aria-controls="clases" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fas fa-folder"></i></span>
                        <span class="d-none d-sm-block">Listado de Clases</span>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" id="calificaciones-tab2" data-toggle="tab" data-target="#calificaciones-tab" href="#calificaciones-tab" role="tab" aria-controls="calificaciones-tab2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                        <span class="d-none d-sm-block">Calificaciones</span>
                      </a>
                    </li>
                  </ul>
                  <!-- FIN CONTENEDOR DE TABS -->

                  <!--TAREAS A CALIFICAR-->
                  <div class="tab-content bg-light">
                    <div class="row tab-pane fade show active" id="alumnos" role="tabpanel" aria-labelledby="alumnos-tab">
                      <div class="container col-sm-12 col-lg-12 col-md-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="table-responsive">
                              <h2 class="m-b-30 m-t-0">??stas son las tareas a revisar y calificar:</h2>
                              <table id="datatable_calificar_tarea" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>TAREA</th>
                                    <th>ALUMNO</th>
                                    <th>MATERIA</th>
                                    <th>GENERACI??N</th>
                                    <th>FECHA DE ENTREGA</th>
                                    <th>RETROALIMENTACI??N</th>
                                    <th>CALIFICACI??N</th>
                                    <th>COMENTARIO DEL ALUMNO</th>
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

                    <div class="row tab-pane fade" id="listado-tareas" role="tabpanel" aria-labelledby="listado-tareas-tab">
                      <div class="container col-sm-12 col-lg-12">
                        <div class="card">
                          <div class="card-body">
                              <div class="text-right">
                                <button id="btn-crear-tarea" type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modalCrearTarea">
                                  Crear Tarea
                                </button>
                              </div>
                            <div class="table-responsive">
                              <h2 class="m-b-30 m-t-0">??stas son las tareas que usted ha subido:</h2>
                              <table id="datatable_listado_tareas" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CURSO</th>
                                    <th>TAREA</th>
                                    <th>CLASE</th>
                                    <th>FECHA L??MITE</th>                                    
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

                    <div class="row tab-pane fade" id="clases" role="tabpanel" aria-labelledby="clases-tab">
                      <div class="container col-sm-12 col-lg-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="table-responsive">
                              <h2 class="m-b-30 m-t-0">??stas son sus clases asignadas:</h2>
                              <table id="datatable_clases" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CLASE</th>
                                    <th>MATERIA</th>
                                    <th>FECHA/HORA</th>
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

                    <div class="row tab-pane fade" id="calificaciones-tab" role="tabpanel" aria-labelledby="calificaciones-tab2">
                      <div class="row">
                        <div class="col-sm-12 col-md-4">
                          <label for="select-carreras">Selecciona una carrera</label>
                          <select id="select-carreras" class="form-control">
                            <option disabled selected>Seleccione una carrera</option>
                          </select>
                        </div>

                        <div class="col-sm-12 col-md-4">
                          <label for="select-generacion">Selecciona una generaci??n</label>
                          <select id="select-generacion" class="form-control">
                            <option disabled selected>Seleccione una generaci??n</option>
                          </select>
                        </div>

                        <div class="col-sm-12 col-md-4">
                          <label for="select_ciclo">Seleccione un ciclo para visualizar las materias</label>
                          <select id="select_ciclo" class="form-control">
                            <option disabled selected>Seleccione un ciclo</option>
                          </select>
                        </div>
                        
                      </div>

                      <div class="col-12 table-responsive">
                        <h3>Alumnos en curso</h3>
                        <h4>Escriba una calificaci??n en un rango del 0 al 10. <br>O en su defecto una '<b><i>s</i></b>' para <b>Sin calificaci??n</b> ?? '<b><i>n</i></b>' para <b>N/C</b> </h4>
                        <table class="table w-100" id="tabla_alumnos">
                          <thead>
                            <th>Nombre</th>
                            <th>Materia</th>
                            <th></th>
                          </thead>
                          <tbody>
                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div><!--end-content-->

                  <div class="modal fade modal-right" id="modalCrearTarea">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title m-0">Formulario - Crear Tarea</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        </div>
                        <div class="modal-body">
                          <form id="formularioCrearTarea" type="post">
                            <div class="form-group">
                              <label for="nombreTarea">Nombre de la Tarea:</label>
                              <input name="nombreTarea" id="nombreTarea" type="text" class="form-control" placeholder="Ingresa el nombre de la tarea..." required>
                            </div>

                            <div class="form-group">
                              <label for="descripcionTarea">Descripci??n de la Tarea (Instrucciones):</label>
                              <textarea name="descripcionTarea" id="descripcionTarea" class="form-control" rows="5" placeholder="Ingresa las instrucciones de la tarea..." required></textarea>    
                            </div>

                            <div class="form-group">
                              <label for="fechaLimiteTarea">Fecha/Hora l??mite para que el alumno entregue la tarea:</label>
                              <input name="fechaLimiteTarea" type="date" class="form-control" id="fechaLimiteTarea" required>
                              <input name="horaLimiteTarea" type="time" class="form-control" id="horaLimiteTarea" required>
                            </div>

                            <div class="form-group">
                              <label for="claseDocente">Clase a la que pertenecer?? la tarea:</label>
                              <select class="form-control" name="clasesDocente" id="clasesDocente" required>
                              </select>
                            </div>

                            <div class="text-right"> 
                              <input type="hidden" id="idMaestro" name="idMaestro" value="<?=$usuario['idPersona']?>">
                              <button type="submit" class="btn btn-primary waves-effect waves-light">Crear</button>
                              <button type="button" id="cerrarCrearTarea" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                            </div>
                          </form>
                        </div><!--end-modal-body-->
                      </div><!--end-content-modal-->
                    </div><!--end modal centered-->
                  </div>

                  <div class="modal fade modal-right" id="modalCalificarTarea">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title m-0">Formulario - Calificar Tarea</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                          </div>
                          <div class="modal-body">
                            <form id="formularioCalificarTarea" type="post">
                              <div class="form-group">
                                <label for="nombreTareaAlumno">Nombre del tarea:</label>
                                <input name="nombreTareaAlumno" id="nombreTareaAlumno" type="text" class="form-control" disabled>
                              </div>

                              <div class="form-group">
                                <label for="nombreAlumno">Nombre del alumno:</label>
                                <input name="nombreAlumno" id="nombreAlumno" type="text" class="form-control" disabled>
                              </div>

                              <div class="form-group">
                                <label for="comentarioAlumno">Comentario del alumno:</label>
                                <input name="comentarioAlumno" id="comentarioAlumno" type="text" class="form-control" disabled>
                              </div>

                              <div class="form-group">
                                <label for="fechaEntregaTarea">Fecha de la entrega:</label>
                                <input name="fechaEntregaTarea" id="fechaEntregaTarea" type="date" class="form-control" disabled>
                              </div>

                              <div class="form-group">
                                <label for="horaEntregaTarea">Hora de la entrega:</label>
                                <input name="horaEntregaTarea" id="horaEntregaTarea" type="time" class="form-control" disabled>
                              </div>

                              <div class="form-group">
                                <label for="retroalimentacionAlumno">Retroalimentaci??n al alumno:</label>
                                <input name="retroalimentacionAlumno" id="retroalimentacionAlumno" type="text" class="form-control" placeholder="Ingresa la retroalimentaci??n al alumno..." required>    
                              </div>

                              <div class="form-group">
                                <label for="calificaciones">Calificar tarea:</label>
                                <select class="form-control" name="calificaciones" id="calificaciones" required>
                                  <option value="" selected="true" disabled="disabled">Seleccione Calificaci??n</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                </select>
                              </div>

                              <div class="text-right">
                                <input type="hidden" id="idEntrega" name="idEntrega">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Calificar</button>
                                <button type="button" id="cerrarCalificarTarea" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                              </div>
                            </form>
                          </div><!--end-modal-body-->
                        </div><!--end-content-modal-->
                    </div><!--end modal-dialog-->
                  </div><!--end-modal-->

                </div><!--end-col-->
              </div><!--end-row-->
            </div><!--end-card-body-->
          </div><!--end-card-->
          <div class="card" id="tab_examenes" style="display:none">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="listado_examenes_tab" data-toggle="tab" data-target="#listExamenes" href="#listExamenes" role="tab" aria-controls="listExamenes" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="far fa-file-alt"></i></span>
                        <span class="d-none d-sm-block">Listado de ex??menes</span>
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content bg-light">
                    <div class="row tab-pane show active" id="resultadoExamenes" role="tabpanel" aria-labelledby="listado_examenes_tab">
                      <div class="container col-sm-12 col-lg-12">
                        <div class="card">
                          <div class="card-body">
                          <!--<div class="text-right">
                              <button id="btn-crear-examen" type="button" class="btn btn-primary waves-effect waves-light">
                                Crear Ex??men
                              </button>
                            </div>-->
                            <div class="table-responsive">
                              <center><h2 class="m-b-30 m-t-0">Listado de Ex??menes:</h2></center>
                              <div class="form-group">
                                <label for="selectListarExamenesCarrera"><h4><strong>Selecciona la carrera</strong></h4></label>
                                <select class="form-control" id="selectListarExamenesCarrera" name="selectListarExamenesCarrera">
                                </select>
                              </div>
                              <table id="tabla_examenes_presentados" class="table table-striped table-bordered nowrap w-100">
                                <thead>
                                  <th>Examen</th>
                                  <th>Fecha Inicio</th>
                                  <th>Fecha Fin</th>
                                  <th>Materia</th>
                                  <th>Generaci??n</th>
                                  <th>Acciones</th>
                                </thead>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!--<div class="modal fade modal-right" id="modalCrearExamen">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title m-0">Formulario - Crear Examen</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                          </div>
                          <div class="modal-body">
                            <form id="formularioCrearExamen" type="post">
                            <div class="form-group">
                              <label for="nombreExamen">Nombre: </label>
                              <input type="text" class="form-control" name="nombreExamen" id="nombreExamen" required>
                            </div>

                            <div class="form-group">
                              <label for="cursoExamen">Curso al que pertenecer?? el examen: </label>
                              <select class="form-control" name="cursoExamen" id="cursoExamen" required>
                              </select>
                            </div>

                            <div class="form-group">
                              <label>Rango de fechas en las que el alumno podr?? presentar el examen: </label>
                              <div class="input-daterange input-group" id="date-range">
                                <input type="date" class="form-control" name="fechaInicioExamen" id="fechaInicioExamen" required>
                                <div class="input-group-append">
                                  <span class="input-group-text bg-primary text-white b-0">al</span>
                                </div>
                                <input type="date" class="form-control" name="fechaFinExamen" id="fechaFinExamen" required>
                              </div>
                            </div>

                            <h3 id="lblTitleEvento_confirm">Preguntas del examen:</h3>
                            <div class="alert alert-warning">
                                <li>Escriba la pregunta, agregue las 4 opciones y seleccione cu??l de ellas es la correcta.</li> 
                                <li>Puede agregar m??s preguntas, s??lo se tomar??n en cuenta las que no est??n vac??as.</li> 
                            </div>

                            <div class="form-group">
                              <strong>Pregunta 1</strong>
                                <input type="text" class="form-control" name="preguntaExamen0" id="preguntaExamen0" required="required">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="A" title="Marcar ??sta opci??n como la correcta" checked>
                                <input type="text" name="TextoOpcionExamen0_A" id="TextoOpcionExamen0_A" style="border-color: transparent;" placeholder="Opci??n A..." required="required">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="B" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen0_B" id="TextoOpcionExamen0_B" style="border-color: transparent;" placeholder="Opci??n B...">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="C" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen0_C" id="TextoOpcionExamen0_C" style="border-color: transparent;" placeholder="Opci??n C...">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="D" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen0_D" id="TextoOpcionExamen0_D" style="border-color: transparent;" placeholder="Opci??n D...">
                            </div>

                            <div class="form-group">
                              <strong>Pregunta 2</strong>
                                <input type="text" class="form-control" name="preguntaExamen1" id="preguntaExamen1" required="required">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="A" title="Marcar ??sta opci??n como la correcta" checked>
                                <input type="text" name="TextoOpcionExamen1_A" id="TextoOpcionExamen1_A" style="border-color: transparent;" placeholder="Opci??n A..." required="required">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="B" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen1_B" id="TextoOpcionExamen1_B" style="border-color: transparent;" placeholder="Opci??n B...">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="C" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen1_C" id="TextoOpcionExamen1_C" style="border-color: transparent;" placeholder="Opci??n C...">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="D" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen1_D" id="TextoOpcionExamen1_D" style="border-color: transparent;" placeholder="Opci??n D...">
                            </div>

                            <div id="divAgregarPregunta">
                            </div>
                            
                            <div class="form-group">
                              <br>
                              <button class="btn btn-dark waves-effect waves-light" type="button" onclick="agregarMasPreguntas()">Agregar Pregunta</button>
                            </div>
                
                            <div class="text-right">
                              <input type="hidden" id="numeroPreguntaExamen" value="2">
                              <input type="hidden" id="idDocente" value="">
                              <button type="submit" class="btn btn-primary waves-effect waves-light">Crear</button>
                              <button type="button" id="cerrarCrearExamen" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                            </div>
                            </form>
                          </div>
                        </div>
                    </div>
                  </div>end-modal-->
                  

                  <div class="modal fade modal-right" id="modalAsignarPreguntas">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title m-0">Formulario - Asignar Preguntas a Examen</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                          </div>
                          <div class="modal-body">
                            <form id="formularioAsignarPreguntasExamen" type="post">
                            <h3 id="lblTitleEvento_confirm">Preguntas del examen:</h3>
                            <div class="alert alert-warning">
                                <li>Escriba la pregunta, agregue las 4 opciones y seleccione cu??l de ellas es la correcta.</li> 
                                <li>Puede agregar m??s preguntas, s??lo se tomar??n en cuenta las que no est??n vac??as.</li>
                                <li>Para crear un examen del tipo "verdadero/falso" solo rellene dos campos de respuestas en cada pregunta.</li>
                            </div>

                            <div class="form-group">
                              <strong>Pregunta 1</strong>
                                <input type="text" class="form-control" name="preguntaExamen0" id="preguntaExamen0" required="required">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="A" title="Marcar ??sta opci??n como la correcta" checked>
                                <input type="text" name="TextoOpcionExamen0_A" id="TextoOpcionExamen0_A" style="border-color: transparent;" placeholder="Opci??n A..." required="required">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="B" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen0_B" id="TextoOpcionExamen0_B" style="border-color: transparent;" placeholder="Opci??n B...">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="C" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen0_C" id="TextoOpcionExamen0_C" style="border-color: transparent;" placeholder="Opci??n C...">
                                <input type="radio" class="OpcionExamen0" name="OpcionExamen0" value="D" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen0_D" id="TextoOpcionExamen0_D" style="border-color: transparent;" placeholder="Opci??n D...">
                            </div>

                            <div class="form-group">
                              <strong>Pregunta 2</strong>
                                <input type="text" class="form-control" name="preguntaExamen1" id="preguntaExamen1" required="required">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="A" title="Marcar ??sta opci??n como la correcta" checked>
                                <input type="text" name="TextoOpcionExamen1_A" id="TextoOpcionExamen1_A" style="border-color: transparent;" placeholder="Opci??n A..." required="required">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="B" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen1_B" id="TextoOpcionExamen1_B" style="border-color: transparent;" placeholder="Opci??n B...">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="C" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen1_C" id="TextoOpcionExamen1_C" style="border-color: transparent;" placeholder="Opci??n C...">
                                <input type="radio" class="OpcionExamen1" name="OpcionExamen1" value="D" title="Marcar ??sta opci??n como la correcta">
                                <input type="text" name="TextoOpcionExamen1_D" id="TextoOpcionExamen1_D" style="border-color: transparent;" placeholder="Opci??n D...">
                            </div>

                            <div id="divAgregarPregunta">
                            </div>
                            
                            <div class="form-group">
                              <br>
                              <button class="btn btn-dark waves-effect waves-light" type="button" onclick="agregarMasPreguntas()">Agregar Pregunta</button>
                            </div>

                            <div class ="form-group row d-flex justify-content-end">
                              <label for="totalPregExamen" class="col-sm-3 control-label">N??mero de preguntas a aplicar del total de preguntas agregadas:</label>
                              <div class="col-sm-4">
                                <input type="number" class="form-control" id="totalPregExamen" name="totalPregExamen">
                              </div>
                            </div>
                
                            <div class="text-right">
                              <input type="hidden" id="numeroPreguntaExamen" value="2">
                              <input type="hidden" id="idExamen" name="idExamen" value="">
                              <input type="hidden" id="idDocente" value="">
                              <button type="submit" class="btn btn-primary waves-effect waves-light">Crear</button>
                              <button type="button" id="cerrarCrearExamen" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                            </div>
                            </form>
                          </div><!--end-modal-body-->
                        </div><!--end-content-modal-->
                    </div><!--end modal-dialog-->
                  </div><!--end-modal-->

                </div>
              </div>
            </div><!--end-card-body-->
          </div><!--end-card-->
        </div><!--end-container-->
      </div><!--end-wrapper-->

          <div class="modal fade bs-example-modal-lg" id="modalModicarExamen" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content col-sm-12 col-lg-12">
                <div class="modal-header">
                  <h4 class="modal-title m-0" id="myLargeModalLabel">Formulario - Editar Examen</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                  <form id="formularioModificar" type="post">
                    <div class="alert alert-warning">
                        <li>Escriba la pregunta, agregue las 4 opciones y seleccione cu??l de ellas es la correcta.</li> 
                        <li>Puede agregar m??s preguntas, s??lo se tomar??n en cuenta las que no est??n vac??as.</li>
                        <li>Para crear un examen del tipo "verdadero/falso" solo rellene dos campos de respuestas en cada pregunta.</li>
                    </div>
                    
                    <div id="divExamen"></div>

                    <div id="divAgregar"></div>

                    <div class ="form-group row d-flex justify-content-end">
                        <label for="editTotalPregExamen" class="col-sm-3 control-label">N??mero de preguntas a aplicar del total de preguntas agregadas:</label>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" id="editTotalPregExamen" name="editTotalPregExamen">
                          </div>
                    </div>

                    <div class="text-right">
                      <input type="hidden" name="idExamen" id="idExamenEditar">
                      <input type="hidden" name="numPreguntas" id="numPreguntas">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Editar</button>
                      <button type="button" name="cancelarEditarExamen" id="cancelarEditarExamen" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade bs-example-modal-lg" id="modalModicarTarea" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content col-sm-12 col-lg-12">
                <div class="modal-header">
                  <h4 class="modal-title m-0" id="myLargeModalLabel">Editar Tarea:</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                  <form id="formularioModificarTarea" type="post">
                    <div class="form-group">
                      <label for="editNombreTarea">Nombre de la Tarea: </label>
                      <input type="text" class="form-control" name="editNombreTarea" id="editNombreTarea" placeholder="Ingresa el nombre de la tarea..." required>
                    </div>

                    <div class="form-group">
                      <label for="editDescripcionTarea">Descripci??n de la Tarea (Instrucciones): </label>
                      <textarea name="editDescripcionTarea" class="form-control" rows="5" id="editDescripcionTarea" placeholder="Ingresa las instrucciones de la tarea..." required></textarea>
                    </div>

                    <div class="form-group">
                      <label for="editFechaLimiteTarea">Fecha/Hora l??mite para que el alumno entregue la tarea: </label>
                      <input type="date" class="form-control" name="editFechaLimiteTarea" id="editFechaLimiteTarea" required></input>
                      <input type="time" class="form-control" name="editHoraLimiteTarea" id="editHoraLimiteTarea" required></input>
                    </div>

                    <div class="form-group">
                      <label for="editClaseTarea">Clase al que pertenecer?? la tarea: </label>
                      <select class="form-control" name="editClaseTarea" id="editClaseTarea" required>
                      </select>
                    </div>

                    <div class="text-right">
                      <input type="hidden" name="idTarea" id="idTarea">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Editar</button>
                      <button type="button" name="cancelarEditarTarea" id="cancelarEditarTarea" class="btn btn-secondary waves-effect m-1-5">Cancelar</button>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>

      <!-- MODAL DE CHUY -->
      <div class="modal fade bs-example-modal-lg" id="modalEntregasExamen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content col-sm-12 col-lg-12">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="myLargeModalLabel">Entregas de examen:</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body">
              <div class="TBNR">
                <table id="tbl_examenes_entregados" class="table table-striped table-bordered nowrap w-100">
                  <thead>
                    <th>Alumno</th>
                    <th>Fecha</th>
                    <th>Calificaci??n</th>
                    <th>Detalle</th>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
		  </div>

      <div id="modalVerClases" class="modal fade bs-example-modal-xl show" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
                <h3 id="tclases" class="modal-title m-0" id="custom-width-modalLabel">Clases de <span id="lbl_nombre_clase"></span></h3>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
            </div>
            <div class="modal-body">
              <form id="form_actualizar_clase" class="mt-3">
                <input type="hidden" name="inp_edit_clase" id="inp_edit_clase">
                <div class="row mb-2">
                  <section class="d-none">
                    <div class="col-sm-12 col-md-6">
                      <label for="">Nombre de la clase</label>
                      <input type="text" class="form-control" name="inp_edit_nombre" id="inp_edit_nombre" placeholder="Nombre de la clase" required>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <label for="">Fecha de la clase</label>
                      <input type="datetime-local" class="form-control" name="inp_edit_fecha" id="inp_edit_fecha" placeholder="Fecha de la clase" required>
                    </div>
                    <div class="col-12">
                      <label for="">Link a video de clase</label>
                      <input type="text" class="form-control" id="inp_edit_link" name="inp_edit_link" placeholder="Link a video de clase">
                    </div>
                    <div class="col-sm-12 col-md-6 my-2">
                      <label for="">Carrera</label>
                      <select id="select_carreras_edit" class="form-control"></select>
                    </div>
                    <div class="col-sm-12 col-md-6 my-2">
                      <label for="">Generaci??n</label>
                      <select id="select_generacion_edit" name="select_generacion_edit" class="form-control"></select>
                    </div>
                    <div class="col-sm-12 col-md-6 my-2">
                      <label for="">Ciclo</label>
                      <select id="select_ciclo_edit" class="form-control"></select>
                    </div>
                    <div class="col-sm-12 col-md-6 my-2">
                      <label for="">Materias</label>
                      <select id="select_materias_edit" name="select_materias_edit" class="form-control"></select>
                    </div>
                  </section>
                </div>
                <div class="row mb-2">
                  <div class="col-12 mb-2">
                    <div class="border p-2">
                      <i class="fa fa-times float-right d-none" id="empty-materiales"></i>
                      <label for=""><b>Lista de materiales de apoyo</b></label>
                      <ul id="list_materiales">
                      </ul>
                      <div id="inputs_materiales">
                        <a class="d-none" href="#" onclick="$('#empty-materiales').click()">
                        <i class="fas fa-upload"></i>
                          <span>Agregar material de apoyo para la clase</span>
                        </a>
                      </div>
                      <button type="button" class="btn btn-dark d-none" id="btn_agregarMaterial" onclick="agregar_elemento('materiales')">
                        <i class="far fa-plus-square"></i>Agregar material
                      </button>
                    </div>
                  </div>
                  <div class="col-12 mb-2">
                    <div class="border p-2">
                      <i class="fa fa-times float-right d-none" id="empty-recursos"></i>
                      <label for=""><b>Lista de recursos descargables</b></label>
                      <ul id="list_recursos">
                      </ul>
                      <div id="inputs_recursos">
                        <a class="d-none" href="#" onclick="$('#empty-recursos').click()">
                        <i class="fas fa-upload"></i>
                          <span>Agregar recursos para la clase</span>
                        </a>
                      </div>
                      <button type="button" class="btn btn-dark d-none" id="btn_agregarRecurso" onclick="agregar_elemento('recursos')">
                        <i class="far fa-plus-square"></i>Agregar recurso
                      </button>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                  </div>
                </div>
              </form>
            </div>
          </div><!-- /.modal-content -->
        </div>
      </div>

      <div class="modal fade bs-example-modal-lg" id="modalVerCalificaciones" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
          <div class="modal-content col-sm-12 col-lg-12">
            <div class="modal-header">
              <h4 class="modal-title m-0" id="myLargeModalLabel">Entregas de calificaciones:</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body">
              <h3 id="lblNombreAlumnos"></h3>
              <!-- <div class="row">
                <div class="col-sm-12 col-md-6">
                  <label for="select_ciclo">Seleccione un ciclo para visualizar las materias</label>
                  <select id="select_ciclo" class="form-control">
                    <option disabled selected>Seleccione un ciclo</option>
                  </select>
                </div>
              </div> -->
              <div class="">
                <table id="tbl_calif_entregados" class="table table-striped table-bordered w-100">
                  <thead>
                    <th>Ciclo</th>
                    <th>Materia</th>
                    <th>Resumen</th>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
		  </div>
      <!-- FIN MODAL DE CHUY -->
      <!-- Footer -->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              ?? 2021 MONI <span class="d-none d-md-inline-block">IESM-UDC-TSU-CONACON TI</span>
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
      <script src="../assets/js/maestros/maestros.js"></script>
      <script src="../assets/js/maestros/calificaciones.js"></script>
      <script type="text/javascript">
        

        $(document).ready(function(){
          agregarClasesDelDocente(<?=$usuario['idPersona']?>);
          materiasDelDocenteCrearExamen(<?=$usuario['idPersona']?>);
          $("#idDocente").val(<?=$usuario['idPersona']?>);
        })
      </script>

    </body>
    </html>
