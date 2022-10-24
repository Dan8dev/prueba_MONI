<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 30){
    header("Location: ../index.php");
    die();
}
    $usuario = $_SESSION["usuario"];
    include( "listadoTareas.php" );

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
                       <?php echo $usuario["persona"]["nombres"]; ?> (<?=$usuario['idPersona']?>)<span class="mdi mdi-chevron-down font-15"></span>
                      </span>
                    </a>
                    <ul class="dropdown-menu">
                      <!--<li><a href="javascript:void(0)" class="dropdown-item"> Profile</a></li>-->
                      <li class="dropdown-divider"></li>
                      <li><a href="../siscon/app/editar_acceso.php?perfil=marketing" class="dropdown-item"> Cambiar contraseña</a></li>
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
                  <?php
                  //$accesos = ['market1@mk.com', 'master-marketing@mk.com'];
                    //          if(in_array($usuario['correo'],$accesos)):
                  ?>
                  <!--<li class="has-submenu">
                    <a href="gestorEventos.php"><i class="ion ion-md-calendar"></i> Gestor Eventos</a>
                  </li>-->
                  <?php //endif ?>
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
        <div class="container-fluid">

          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row align-items-center">
                  <div class="col-6 col-md-3 mb-2">
                    <h4 class="page-title m-0">
                      <span tab-target="eventos" class="tab_active">
                        <i class="ti-briefcase"></i> Tareas
                      </span>                                        
                      <!-- |
                      <span tab-target="carreras">
                        <i class="fas fa-book-reader"></i> Mis Cursos
                      </span>-->
                    </h4>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>


          <?php
            if( isset( $_GET['e'] ) && $_GET['e'] == 1 ){
                        echo '<div id="notice" class="alert alert-info alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Sus cambios han sido guardados.</div>';
            }
          ?>

          <div class="row">
            <!-- SECCION PARA CONCENTRADO DE TAREAS -->
            <div class="col-sm-12" id="tab_concentrado_eventos">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- CONTENEDOR DE TABS -->
                      <ul class="nav nav-tabs" role="tablist">

                      <li class="nav-item">
                          <a class="nav-link <?php if( !isset($_GET['p'] ) || $_GET['p'] == ""  ) echo "active"; else echo ""; ?>" data-toggle="tab" id="alumnos-tab" href="#alumnos" role="tab" aria-controls="alumnos"  aria-selected="false" data-target="#alumnos">
                            <span class="d-block d-sm-none"><i class="fa fa-users"></i></span>
                            <span class="d-none d-sm-block"><i class="fa fa-users"></i> Tareas a revisar</span>
                          </a>
                        </li>

                      <li class="nav-item">
                          <a class="nav-link <?php if( isset($_GET['p']) && ($_GET['p'] == 'editar' || $_GET['p'] == 'ato') ) echo 'active'; else echo ""; ?>" data-toggle="tab" id="tareas-tab" href="#tareas" role="tab" aria-controls="tareas" aria-selected="true" data-target="#tareas">
                            <span class="d-block d-sm-none"><i class="fa fa-list"></i></span>
                            <span class="d-none d-sm-block"><i class="fa fa-list"></i> Listado de tareas</span>
                          </a>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="agregar-tab" href="#agregar" role="tab" aria-controls="agregar" aria-selected="true" data-target="#agregar">
                            <span class="d-block d-sm-none"><i class="far fa-plus-square"></i></span>
                            <span class="d-none d-sm-block"><i class="far fa-plus-square"></i> Agregar Tarea</span>
                          </a>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link <?php if( isset($_GET['p']) && ($_GET['p'] == 'editarClase' || $_GET['p'] == 'aco') ) echo 'active'; ?>" data-toggle="tab" id="clases-tab" href="#clases" role="tab" aria-controls="clases" aria-selected="true" data-target="#clases">
                            <span class="d-block d-sm-none"><i class="fas fa-folder"></i></span>
                            <span class="d-none d-sm-block"><i class="fas fa-folder"></i> Listado de Clases</span>
                          </a>
                        </li>

                        
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="agregarclase-tab" href="#agregarclase" role="tab" aria-controls="agregarclase" aria-selected="true" data-target="#agregarclase">
                            <span class="d-block d-sm-none"><i class="fas fa-folder-plus"></i></span>
                            <span class="d-none d-sm-block"><i class="fas fa-folder-plus"></i> Agregar Clase</span>
                          </a>
                        </li>
                        


                      </ul>
                      <!--
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false" tab-dest="table-eventos">
                            <span class="d-block d-sm-none"><i class="fa fa-wallet"></i></span>
                            <span class="d-none d-sm-block">Concentrado</span>
                          </a>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true" tab-dest="listado_prospectos">
                            <span class="d-block d-sm-none"><i class="fa fa-history"></i></span>
                            <span class="d-none d-sm-block">Prospectos</span>
                          </a>
                        </li>
                      </ul>-->
                      <!-- FIN CONTENEDOR DE TABS -->


                      <!--TAREAS A CALIFICAR-->
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show <?php if( !isset($_GET['p'] ) || $_GET['p'] == "calificarOk"  ) echo "active"; else echo ""; ?>" id="alumnos" role="tabpanel" aria-labelledby="alumnos-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">

                              <h2>Éstas son las tareas a revisar y calificar:</h2>

                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>TAREA</th>
                                    <th>ALUMNO</th>
                                    <th>FECHA DE ENTREGA</th>
                                    <th>COMENTARIO DEL ALUMNO</th>
                                    <th>CALIFICACION</th>
                                    <th>OPCIONES</th>
                                  </tr>
                                </thead>

                                <?php 
                                $resultado = listarTareasAlumnos( $usuario['idPersona'] );
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <tr>
                                    <td><?=$fila['titulo']?></td>
                                    <td><?=$fila['nombre']?></td>
                                    <td><?=$fila['fecha_entrega']?></t>
                                    <td><a href="#" ><?=$fila['comentario']?>...</a></td>
                                    <td>
                                      <form method="get">
                                        <select title="Seleccione la calificación a asignar para esta tarea" class="form-control" onChange="MM_jumpMenu('parent',this,0)" id="calificacion" name="calificacion">
                                          <?php for( $i = 5; $i<=10; $i++ ){
                                                  if( $i == $fila['calificacion'] ) $s = "selected"; else $s = '';
                                                  echo '<option '.$s.' value="calificar.php?idEntrega='.$fila['idEtrega'].'&calificacion='.$i.'">'.$i.'</option>';
                                          }//fin for?>
                                        </select>
                                      </form>
                                    </td> 
                                    <td>
                                    <a class="btn btn-primary waves-effect waves-light" href="<?=$fila['archivo']?>" target="_blank"><i class="fas fa-file-download"></i> Descargar</a>  
                                    <!--<a class="btn btn-primary waves-effect waves-light" href="index.php?p=calificar&idTarea=<?=$fila['idTarea']?>">Calificar</a>  -->
                                    <!--<a class="btn btn-secondary waves-effect waves-light" href="eliminarTarea.php?idTarea=<?=$fila['idTarea']?>">Eliminar</a>-->
                                    </td>
                                  </tr>
                                    <?php }?>
                                <?php } ?>                               

                                <tbody>
                                  
                                </tbody>
                              </table>


                            </div>
                          </div>
                        </div>  
                        <!--FIN TAREAS A CALIFICAR-->                   

                        <div class="row tab-pane fade show <?php if( isset($_GET['p']) && ($_GET['p'] == 'editar' || $_GET['p'] == 'ato') ) echo 'active'; ?>" id="tareas" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">


                          <?php 
                          if( !isset($_GET['p'] ) ) $_GET['p'] = 'default';
                          if( $_GET['p'] == 'editar' ){ ?>
                              <?php 
                                    $resultado = agregarTareaForm($usuario['idPersona']);
                                    $dataEdit = dataTareaEdit( $_GET['idTarea'] );
                                    $filaEdit = $dataEdit->fetch_assoc();
                                    ?>

                                    <form id="fedit" name="form1" class="form-horizontal" method="post" action="editarTarea.php?idTarea=<?=$_GET['idTarea']?>&idMaestro=<?=$usuario['idPersona']?>">

                                    <div class="col-sm-12">
                                      <h2>Editar tarea:</h2>
                                    </div>

                                      <p>Nombre de la tarea:<br><input name="nombre" type="text" class="form-control" value="<?=$filaEdit['titulo']?>" id="example-text-input" required></p>
                                      
                                      <p>Descripción/Instrucciones:<br><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" required><?=$filaEdit['descripcion']?></textarea></p>

                                      <p>URL para descarga de recurso externo:<br><input name="recurso" type="text" class="form-control" value="<?=$filaEdit['archivo']?>" id="example-text-input"></p>

                                      <p>Curso y clase a la que pertenece la tarea:
                                      <select class="form-control" name="idClase">
                                      <?php 
                                        if( $resultado->num_rows > 0 ){?>
                                          <?php while( $fila = $resultado->fetch_assoc() ){?>
                                            <option value="<?=$fila['idClase']?>" <?php if( $fila['idClase'] == $filaEdit['idClase'] ) echo "selected"?> ><?=$fila['nombreCurso']?> - <?=$fila['nombre']?></option>
                                            <?php }?>
                                        <?php } ?>

                                      </select>
                                      </p>

                                      <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                                      <button type="reset" style="color:white;" onClick="javascript: document.getElementById( 'fedit' ).style.display='none';" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                                      
                                      <hr>
                                    </form>                                   

                            <?php }?>


                            <div class="col-sm-12">
                              <h2>Éstas son las tareas que usted ha subido:</h2>
                              <h3 id="lblTitleEvento_confirm"></h3>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">


                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CURSO</th>
                                    <th>CLASE</th>
                                    <th>TAREA</th>
                                    <th>FECHA LÍMITE</th>                                    
                                    <th>DESCRIPCIÓN</th>
                                    <th>OPCIONES</th>
                                  </tr>
                                </thead>

                                <?php 
                                $resultado = listarTareasMaestros( $usuario['idPersona'] );
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <tr>
                                    <td><?=$fila['nombreCurso']?></td>
                                    <td><?=$fila['nombreClase']?></td>
                                    <td><?=$fila['tituloTarea']?></td>                                    
                                    <td><?=$fila['fecha_limite']?></td>                                    
                                    <td><a href="#"><?=$fila['descripcion']?>...</a></td> 
                                    <td>
                                    <a class="btn btn-primary waves-effect waves-light" href="index.php?p=editar&idTarea=<?=$fila['idTareas']?>" title="Editar">Editar</a>  
                                    <a class="btn btn-secondary waves-effect waves-light" href="eliminarTarea.php?idTarea=<?=$fila['idTareas']?>" title="Eliminar">Eliminar</a></td>
                                  </tr>
                                    <?php }?>
                                <?php } ?>                               

                                <tbody>
                                  
                                </tbody>
                              </table>
                            </div>
                          </div> 
                        </div> 


                        <!--AGREGAR TAREA-->
                        <div class="row tab-pane fade" id="agregar" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">
                            <div class="col-sm-12">
                              <h1>Agregar tarea: </h1>
                              <h3 id="lblTitleEvento_confirm"></h3>

                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">

                            <?php $resultado = agregarTareaForm($usuario['idPersona']);?>

                            <form name="form1" class="form-horizontal" method="post" action="agregarTarea.php?idMaestro=<?=$usuario['idPersona']?>">

                              <p><input name="nombre" type="text" class="form-control" placeholder="Nombre de la tarea..." id="example-text-input" required></p>
                              
                              <p><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" placeholder="Descripción/Instrucciones..." required></textarea></p>

                              <p><input name="recurso" type="text" class="form-control" placeholder="URL para descarga de recurso externo..." id="example-text-input"></p>
                              
                              <p>Clase:<select class="form-control" name="idClase">
                              <?php 
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <option value="<?=$fila['idClase']?>"><?=$fila['nombreCurso']?> - <?=$fila['nombre']?></option>
                                    <?php }?>
                                <?php } ?>

                              </select>
                              </p>

                              <button type="submit" class="btn btn-primary waves-effect waves-light">Agregar</button>
                              <button type="reset" class="btn btn-secondary waves-effect waves-light">Cancelar</button>

                            </form>
                              
                             <!--<table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Código</th>
                                    <th>Promocional</th>
                                    <th>Pago</th>
                                    <th>Asistencia</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                              </table>-->

                            </div>
                          </div> 
                        </div> 
                        <!--FIN AGREGAR TAREA-->

                        <!--LISTADO DE CLASES-->
                        <div class="row tab-pane fade show <?php if( isset($_GET['p']) && ($_GET['p'] == 'editarClase' || $_GET['p'] == 'aco') ) echo 'active'; ?>" id="clases" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">

                          <?php 
                          if( !isset($_GET['p'] ) ) $_GET['p'] = 'default';
                          if( $_GET['p'] == 'editarClase' ){ ?>
                              <?php 
                                    //$resultado = agregarTareaForm($usuario['idPersona']);
                                    $resultado = agregarClaseForm($usuario['idPersona']);
                                    $dataEdit = dataClaseEdit( $_GET['idClase'] );
                                    $filaEdit = $dataEdit->fetch_assoc();
                                    ?>

                                    <form id="feditClase" name="feditClase" class="form-horizontal" method="post" action="editarClase.php?idClase=<?=$_GET['idClase']?>">

                                    <div class="col-sm-12">
                                      <h2>Editar Clase:</h2>
                                    </div>

                                      <p><input name="nombreClase" type="text" class="form-control" value="<?=$filaEdit['titulo']?>" id="example-text-input" required></p>
                                      
                                      <p>Curso al que pertenecerá la clase:
                                        <select class="form-control" name="idCurso">
                                      <?php 
                                        if( $resultado->num_rows > 0 ){?>
                                          <?php while( $fila = $resultado->fetch_assoc() ){?>
                                            <option value="<?=$fila['idCurso']?>"><?=$fila['nombreCurso']?></option>
                                            <?php }?>
                                        <?php } ?>

                                      </select>
                                      </p>

                                      <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                                      <button type="reset" class="btn btn-secondary waves-effect waves-light" onClick="javascript: document.getElementById( 'feditClase' ).style.display='none';">Cancelar</button>

                                      <hr>
                                    
                                    </form>

                            <?php }?>                            


                            <div class="col-sm-12">
                              <h2>Éstas son las clases que usted ha subido:</h2>
                              <h3 id="lblTitleEvento_confirm"></h3>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">


                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CLASE</th>
                                    <th>CURSO</th>
                                    <!--<th>TAREA</th>-->
                                    <!--<th>DESCRIPCIÓN</th>-->
                                    <th>OPCIONES</th>
                                  </tr>
                                </thead>

                                <?php 
                                $resultado = listarClasesMaestros( $usuario['idPersona'] );
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <tr>
                                    <td><?=$fila['nombre']?></td>
                                    <td><?=$fila['nombreCurso']?></td>
                                    <!--<th><?=$fila['tituloTarea']?></th>-->
                                    <!--<th><?=$fila['descripcion']?>...</th> -->
                                    <td>
                                    <a class="btn btn-primary waves-effect waves-light" href="index.php?p=editarClase&idClase=<?=$fila['idClase']?>" title="Editar">Editar</a>  
                                    <a class="btn btn-secondary waves-effect waves-light" href="#" title="Eliminar">Eliminar</a>
                                    <!--<a class="btn btn-secondary waves-effect waves-light" href="eliminarClase.php?idClase=<?=$fila['idClase']?>" title="Eliminar">Eliminar</a>-->
                                  </td>
                                  </tr>
                                    <?php }?>
                                <?php } ?>                               

                                <tbody>
                                  
                                </tbody>
                              </table>
                            </div>
                          </div> 
                        </div> 
                        <!--FIN LISTADO DE CLASES-->

                        <!--AGREGAR CLASE-->
                        <div class="row tab-pane fade" id="agregarclase" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">
                            <div class="col-sm-12">
                              <h1>Agregar Clase: </h1>
                              <h3 id="lblTitleEvento_confirm"></h3>

                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">

                            <?php $resultado = agregarClaseForm($usuario['idPersona']);?>

                            <form name="form1" class="form-horizontal" method="post" action="agregarClase.php?idMaestro=<?=$usuario['idPersona']?>">

                              <p><input name="nombreClase" type="text" class="form-control" placeholder="Nombre de la Clase..." id="example-text-input" required></p>
                              
                              <!--<p><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" placeholder="Descripción de la Clase..." required></textarea></p>-->
                              <!--<p><input name="recurso" type="text" class="form-control" placeholder="URL para descarga de recurso externo..." id="example-text-input"></p>-->

                              <input name="recursos" type="hidden" id="recursos">
                              
                              <p>Curso al que pertenecerá la clase:<select class="form-control" name="idCurso">
                              <?php 
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <option value="<?=$fila['idCurso']?>"><?=$fila['nombreCurso']?></option>
                                    <?php }?>
                                <?php } ?>

                              </select>
                              </p>
                              
                              <hr>
                              <!--AGREGAR RECURSOS-->
                              <h2>Añadir recurso:</h2>
                              <div class="row">
                                <div class="col-sm-12">
                                    <ul id="list_recursos">
                                    </ul>
                                </div>

                                <div class="col-sm-4">
                                  <input type="text" id="name_li" placeholder="Nombre del recurso" class="form-control">
                                </div>

                                <div class="col-sm-8">
                                  <input type="text" id="enlace_li" placeholder="Enlace al recurso" class="form-control">
                                </div>
                              </div>

                              <br>
                              <button type="button" class="btn btn-primary" id="btn-add">Agregar Recurso</button>
                              <button type="button" class="btn btn-secondary" id="btn-minus" onClick="quitarRecursos();">Quitar los recursos</button><br>
                            <!-- FIN AGREGAR RECURSO-->
                            <hr>

                              <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar clase</button>
                              <button type="reset" class="btn btn-secondary waves-effect waves-light">Cancelar</button>

                            </form>
                              
                             <!--<table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Código</th>
                                    <th>Promocional</th>
                                    <th>Pago</th>
                                    <th>Asistencia</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                              </table>-->

                            </div>
                          </div> 
                        </div> 
                        <!--FIN AGREGAR CLASE-->


                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- SECCION PARA CONCENTRADO DE CARRERAS -->
            <div class="col-sm-12" id="agregar" style="display:none">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="carrer-tab" data-toggle="tab" href="#carrer" role="tab" aria-controls="home" aria-selected="false" data-target="#carrer">
                            <span class="d-block d-sm-none"><i class="fa fa-wallet"></i></span>
                            <span class="d-none d-sm-block">Concentrado</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="prospect-tab" data-toggle="tab" href="#prosp_carrer" role="tab" aria-controls="profile" aria-selected="true" data-target="#prosp_carrer">
                            <span class="d-block d-sm-none"><i class="fa fa-history"></i></span>
                            <span class="d-none d-sm-block">Prospectos</span>
                          </a>
                        </li>
                      </ul>
                      <div class="tab-content bg-light">
                        <div class="row tab-pane fade show active" id="carrer" role="tabpanel" aria-labelledby="carrer-tab">
                          <div class="col-lg-12 col-sm-12 col-md-12 TBNR table-responsive">
                            <h5>Listado Carreras</h5>

                            <table id="table-carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                              <thead>
                                <tr>
                                  <th>Carrera</th>
                                  <th>Institución</th>
                                  <th>Clasificación</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>

                          </div>
                        </div>
                        <div class="row tab-pane fade" id="prosp_carrer" role="tabpanel" aria-labelledby="prospect-tab">
                          <div class="table-responsive">
                            <div class="col-sm-12">
                              <h5>Listado Prospectos</h5>
                              <h3 id="lblTitleEvento_confirm"></h3>
                              <p class="mt-2 mb-0 text-muted">
                                Confirmados: <span class="lblTotalConfirmadosCrr"></span>
                              </p>
                              <p class="mt-2 mb-0 text-muted">
                                Pendientes: <span class="lblTotalPendientesCrr"></span>
                              </p>
                              <p class="mt-2 mb-2 text-muted">
                                Rechazados: <span class="lblTotalRechazadosCrr"></span>
                              </p>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">
                              <!-- <label>Periodos anteriores</label>
                              <select id="selectPeriodoCuenta" class="form-control form-select">
                                <option disabled selected>Seleccione opción</option>
                              </select> -->
                              <table id="listado_prospectos_carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>Estatus</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Telefonos</th>
                                    <th>Seguimiento</th>
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

          <?php
            $accesos = ['market1@mk.com', 'master-marketing@mk.com'];
            if (in_array($usuario['correo'], $accesos)): 
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

                <li class="nav-item">
                  <a class="nav-link" id="potospectos-tab" data-toggle="tab" href="#prospectos" role="tab" aria-controls="profile" aria-selected="true" data-target="#prospectos">
                    <span class="d-block d-sm-none"><i class="fas fa-users"></i></span>
                    <span class="d-none d-sm-block">Prospectos</span>
                  </a>
                </li>
              </ul>

              <div class="tab-content bg-light">
                <div class="tab-pane fade show active" id="v-home" role="tabpanel" aria-labelledby="v-home-tab">
                  <div class="card">
                      <div class="card-body">
                        <h4 class="page-title ">Registrar un prospecto</h4>
                        <form id="form_nuevo_prospecto">
                          <div class="row">
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Nombre</label>
                              <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Apellido Paterno.</label>
                              <input type="text" name="paterno" id="paterno" class="form-control" required>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Apellido Materno.</label>
                              <input type="text" name="materno" id="materno" class="form-control" required>
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Telefono</label>
                              <input type="tel" name="telefono" id="telefono" class="form-control" required>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Correo.</label>
                              <input type="text" name="email" id="email" class="form-control" required>
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Institución</label>
                              <select name="IDOrganizacion" id="IDOrganizacion" class="form-control only_event">
                                  <option value="0" selected="">Si pertenece a una asociación, elijala</option>
                                  <!--<option value="13">AVE FENIX SANACIÓN INTEGRAL </option>
                                  <option value="2">CASA DE LA ESPERANZA </option>
                                  <option value="10">COTAI </option>
                                  <option value="8">CRREAD ZONA 1 </option>
                                  <option value="5">FUNDACIÓN DEL CONDE </option>
                                  <option value="3">GENTE DESPERTAR </option>
                                  <option value="12">MI VIDA ES MEJOR A.C. </option>
                                  <option value="1">RENAPRE A.C. </option>
                                  <option value="4">RED RCP </option>
                                  <option value="11">SAWABONA </option>-->
								  
                              </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Codigo promocional.</label>
                              <input type="text" name="inp_codigo_pro" id="inp_codigo_pro" class="form-control">
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

                            <div class="form-group col-12">
                              <label>Quién atenderá al prospecto?</label>
                              <select class="form-control" required name="n_prosp_personaMk" id="n_prosp_personaMk">
                                  <option selected disabled>Seleccione a una persona para der seguimiento</option>
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
                            <th>Correo</th>
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
                      <table class="table table-striped table-bordered nowrap dt-responsive" id="tabla_prospectos" style="width: 100%;">
                        <thead>
                          <th>Nombre</th>
                          <th>Fecha</th>
                          <th>Interés</th>
                          <th>Ejecutiva</th>
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
          </div>
            
          <?php endif ?>
        </div>
      </div>

      <!-- end wrapper -->

      <!-- todos los modal -->
        <div class="modal fade" id="modalConfirmaAsist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
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
        
        <div class="modal fade" id="modalRechazarAsist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
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
          <div class="modal-dialog modal-sm">
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
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h5 class="modal-title">Registro de seguimiento</h5>
              </div>
              
              <h5 class="mx-auto mb-0"><b id="lbl_persona_seguimiento"></b></h5>
              
              <div class="modal-body pt-0">
                <div class="card m-b-30">
                  <h5 class="card-header mt-0">Última actualizacion: <i id="detalle_fecha_comment"><!-- 2021/08/18 --></i></h5>
                  <div class="card-body">
                      <p class="card-text" id="detalle_ult_comment"> <!-- info --> </p>
                      <a href="#" class="btn btn-primary" title="Agregar un nuevo comentario del seguimiento" id="btn_agregar_comentario"><i class="fas fa-plus"></i></a>
                      <!-- <a href="#" class="btn btn-info"><i class="fas fa-history"></i></a> -->
                      <!-- <a href="#" class="btn btn-info"><i class="fas fa-user-clock"></i></a> --> <!-- boton para agendar llamada -->
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
              
              </div>
              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_comentario" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h5 class="modal-title">Agregar comentario</h5>
              </div>
              
              <h5 class="mx-auto mb-0"><b id="lbl_persona_seguimiento"></b></h5>
              
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
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header pb-0">
                <h4 class="modal-title">Registrar pago</h4>
              </div>
              
              <h3 class=""><b id="lbl_persona_pago"></b></h3>
              
              <div class="modal-body pt-0">
                <form id="form_registrar_pago">
                  <input type="hidden" name="person_pago" id="person_pago"> <!-- input del id a insertar -->
                  <input type="hidden" name="evento_pago" id="evento_pago"> <!-- input del id evento -->
                  <div class="row">
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                        <label>Fecha pago</label>
                        <input type="date" name="inp_fecha_pago" id="inp_fecha_pago" class="form-control" required="" max="<?php echo(date('Y-m-d')); ?>">
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                        <label>Monto</label>
                        <input type="tel" name="inp_monto_pago" id="inp_monto_pago" class="form-control moneyFt" data-prefix="$ " value="$ 0.00" required="">
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                          <label>Comprobante</label>
                          <input type="file" class="filestyle" data-buttonname="btn-secondary" name="inp_comprobante_pago" id="inp_comprobante_pago">
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                          <label>folio / n° autorización</label>
                          <input type="text" class="form-control" name="inp_folio_pago" id="inp_folio_pago" required="">
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                          <label>Tipo Pago</label>
                          <select id="tipo_pago" name="tipo_pago" class="form-control form-select">
                            
                          </select>
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

              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
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
                  "info":     false,
          }).columns.adjust()
          $(".moneyFt").maskMoney();
        })
        $(".page-title").children().on('click', function(){
          if(!$(this).hasClass('tab_active')){
            enab = $(".tab_active").attr('tab-target');
            trg = $(this).attr('tab-target');

            $("#tab_concentrado_"+enab).fadeOut('fast', function(){
              $("#tab_concentrado_"+trg).fadeIn('fast')
            })

            $(".tab_active").removeClass("tab_active");
            $(this).addClass("tab_active");

          }
        })
      </script>

      <script language="JavaScript" type="text/JavaScript">
      <!--
      function MM_jumpMenu(targ,selObj,restore){ //v3.0
        eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
        if (restore) selObj.selectedIndex=0;
      }
      //-->

      var intervalID = window.setInterval(cerrarNotice, 5000 );
      function cerrarNotice() {
        document.getElementById( "notice" ).style.display="none";
        clearInterval(intervalID);
      }
      </script>

      <script>
        let list_enlaces = [];

        $("#btn-add").on('click', function(){
          if($("#name_li").val() != '' && $("#enlace_li").val() != ''){
            list_enlaces.push([$("#enlace_li").val(), $("#name_li").val()]);
            uls = "";
              for (i = 0; i < list_enlaces.length; i++) {
                //uls+=`<li id="`+i+`"><a href="${list_enlaces[i][0]}" target="_blank">${list_enlaces[i][1]}</a> - <a href="#" onClick='javascript: quitarRecursoOne(`+i+`)'>[Eliminar]</a></li>`;
                uls+=`<li id="`+list_enlaces[i][1]+`"><a href="${list_enlaces[i][0]}" target="_blank">${list_enlaces[i][1]}</a> - <a href="#" onClick='javascript: quitarRecursoOne("`+list_enlaces[i][1]+`")'>[Eliminar]</a></li>`;
              }

              $("#name_li").val('');
              $("#enlace_li").val('');
              $("#list_recursos").html(uls);
              //console.log(JSON.stringify(list_enlaces))// <- ESTE ES EL QUE SE GUARDA EN LA BASE
              //document.getElementById( "recursos" ).value=document.getElementById( "recursos" ).value + JSON.stringify(list_enlaces);
              document.getElementById( "recursos" ).value = JSON.stringify(list_enlaces);
              console.log( document.getElementById( "recursos" ).value );
            }else{
                alert('Favor de llenar los campos.'); 
                  }
            })

        function quitarRecursos(){
            total = list_enlaces.length;           
            var d = document.getElementById("list_recursos"); //contenedor padre
            for( i = 0; i < total; i++ ){
              var d_nested = document.getElementById( i ); //elemento hijo a eliminar
              var throwawayNode = d.removeChild(d_nested); //indicar eliminación
            }
            document.getElementById( "recursos" ).value = '';
            list_enlaces = [];
            console.log( document.getElementById( "recursos" ).value );
            //list_enlaces.splice(0, list_enlaces.length);
        }//Fin quitarRecurso

        function quitarRecursoOne( e ){
          var d = document.getElementById("list_recursos"); //contenedor padre
          var d_nested = document.getElementById( e ); //elemento hijo a eliminar
          var throwawayNode = d.removeChild(d_nested); //indicar eliminación
          //list_enlaces.splice(e, 1);
          console.log( list_enlaces.indexOf(e) );
          list_enlaces.splice(list_enlaces.indexOf(e), 1);
          document.getElementById( "recursos" ).value = JSON.stringify(list_enlaces);
          console.log( e+"----"+document.getElementById( "recursos" ).value );
        }//quitarRecursoOne
      </script>

      <!--<script src="../assets/js/template/app.js"></script>-->
      <!--<script src="../assets/js/mkt-edu/panel.js"></script>-->
      <script src="panel.js"></script>

      <?php if (in_array($usuario['correo'], $accesos)): ?>
        <!--<script src="../assets/js/mkt-edu/master-market.js"></script>-->
        <!--<script src="concentrado.js"></script>-->
        
      <?php endif ?>
      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>