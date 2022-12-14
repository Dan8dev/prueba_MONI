<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]['idTipo_Persona'] != 30){
    header("Location: ../index.php");
    die();
}
    $usuario = $_SESSION["usuario"];
    require_once( "cx.php" );
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
                      <li><a href="../siscon/app/editar_acceso.php?perfil=marketing" class="dropdown-item"> Cambiar contrase??a</a></li>
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
                      <span tab-target="tab_concentrado_tareas" onClick="javascript:mostrarTareas();">
                        <i class="ti-briefcase"></i> Tareas
                      </span>                                        
                      |
                      <span tab-target="tab_concentrado_examenes" <?php if( isset( $_GET['p'] ) && ($_GET['p'] == 'aeo' || $_GET['p'] == 'editarExamen' || $_GET['p'] == 'detallesExamen'  ) ) echo 'class="tab_active"'; ?> onClick="javascript:mostrarExamenes();">
                        <i class="fas fa-user-check"></i> Ex??menes
                      </span>
                    </h4>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!--
          <div class="alert alert-warning alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">??</span></button>Versi??n de prueba.
          </div>
          -->

          <?php
            /*if( isset( $_GET['e'] ) && $_GET['e'] == 1 ){
                        echo '<div id="notice" class="alert alert-info alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">??</span></button> Sus cambios han sido guardados.</div>';
            }*/
          ?>


        <div id="notice" class="alert alert-info alert-dismissible fade show" <?php if( isset( $_GET['e'] ) && $_GET['e'] == 1 ) echo "style='display:block'"; else echo "style='display:none'";?> ><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">??</span></button> Sus cambios han sido guardados.</div>

          <!--TABS TAREAS-->
          <div class="row">
            <!-- SECCION PARA CONCENTRADO DE TAREAS -->
            <div class="col-sm-12" id="tab_concentrado_tareas" <?php if( isset( $_GET['p'] ) && ($_GET['p'] == 'aeo' || $_GET['p'] == 'editarExamen' || $_GET['p'] == 'detallesExamen' ) ) echo 'style="display:none"'; ?>>
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- CONTENEDOR DE TABS -->
                      <ul class="nav nav-tabs" role="tablist">

                      <li class="nav-item" id="atab">
                          <a class="nav-link <?php if( !isset($_GET['p'] ) || $_GET['p'] == ""  ) echo "active"; else echo ""; ?>" data-toggle="tab" id="alumnos-tab" href="#alumnos" role="tab" aria-controls="alumnos"  aria-selected="false" data-target="#alumnos">
                            <span class="d-block d-sm-none"><i class="fa fa-users"></i></span>
                            <span class="d-none d-sm-block" ><i class="fa fa-users"></i> Tareas a revisar</span>
                          </a>
                        </li>

                      <li class="nav-item">
                          <a class="nav-link <?php if( isset($_GET['p']) && ($_GET['p'] == 'editar' || $_GET['p'] == 'ato' || $_GET['p'] == 'eto') ) echo 'active'; else echo ""; ?>" data-toggle="tab" id="tareas-tab" href="#tareas" role="tab" aria-controls="tareas" aria-selected="true" data-target="#tareas">
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
                          <a class="nav-link <?php if( isset($_GET['p']) && ($_GET['p'] == 'editarClase' || $_GET['p'] == 'aco' || $_GET['p'] == 'eco') ) echo 'active'; ?>" data-toggle="tab" id="clases-tab" href="#clases" role="tab" aria-controls="clases" aria-selected="true" data-target="#clases">
                            <span class="d-block d-sm-none"><i class="fas fa-folder"></i></span>
                            <span class="d-none d-sm-block"><i class="fas fa-folder"></i> Listado de Clases</span>
                          </a>
                        </li>
                        
                        <!--<li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="agregarclase-tab" href="#agregarclase" role="tab" aria-controls="agregarclase" aria-selected="true" data-target="#agregarclase">
                            <span class="d-block d-sm-none"><i class="fas fa-folder-plus"></i></span>
                            <span class="d-none d-sm-block"><i class="fas fa-folder-plus"></i> Agregar Clase</span>
                          </a>
                        </li>-->

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
                      <div class="tab-content bg-light" id="alumnosp">
                        <div class="row tab-pane fade show <?php if( !isset($_GET['p'] ) || $_GET['p'] == "calificarOk"  ) echo "active"; else echo ""; ?>" id="alumnos" role="tabpanel" aria-labelledby="alumnos-tab">
                          <div class="table-responsive">
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">

                              <h2>??stas son las tareas a revisar y calificar:</h2>

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
                                    <td><?=substr($fila['titulo'],0,20)?></td>
                                    <td><?=$fila['nombre']?></td>
                                    <td><?=$fila['fecha_entrega']?></t>
                                    <td><a href="#" ><?=substr($fila['comentario'], 0, 20)?>...</a></td>
                                    <td>
                                      <form method="get">
                                        <select title="Seleccione la calificaci??n a asignar para esta tarea" class="form-control" onChange="MM_jumpMenu('parent',this,0)" id="calificacion" name="calificacion">
                                          <?php for( $i = 5; $i<=10; $i++ ){
                                                  if( $i == $fila['calificacion'] ) $s = "selected"; else $s = '';
                                                  echo '<option '.$s.' value="calificar.php?idEntrega='.$fila['idEtrega'].'&calificacion='.$i.'">'.$i.'</option>';
                                          }//fin for?>
                                        </select>
                                      </form>
                                    </td> 
                                    <td>
                                    <a class="btn btn-primary waves-effect waves-light" href="<?=$fila['archivo']?>" target="_blank"><i class="fas fa-file-download"></i> Descargar</a>  
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

                        <div class="row tab-pane fade show <?php if( isset($_GET['p']) && ($_GET['p'] == 'editar' || $_GET['p'] == 'ato' || $_GET['p'] == 'eto' || $_GET['p'] == 'editarExamen') ) echo 'active'; ?>" id="tareas" role="tabpanel" aria-labelledby="profile-tab">
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
                                      <p>Descripci??n/Instrucciones:<br><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" required><?=$filaEdit['descripcion']?></textarea></p>

                                      <?php
                                        $date = explode( " ", $filaEdit['fecha_limite'] );
                                        $hora = explode( ":", $date[1] );
                                      ?>
                                      <p>Fecha/Hora l??mite para que el alumno entregue la tarea: 
                                      <input name="fecha_limite" type="date" value="<?=$date[0]?>" class="form-control" id="example-text-input" required>
                                      <input name="hora_limite" type="time" value="<?=$hora[0]?>:<?=$hora[1]?>" class="form-control" id="example-text-input" required></p>

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
                              <h2>??stas son las tareas que usted ha subido:</h2>
                              <h3 id="lblTitleEvento_confirm"></h3>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">


                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CURSO</th>
                                    <th>CLASE</th>
                                    <th>TAREA</th>
                                    <th>FECHA L??MITE</th>                                    
                                    <!--<th>DESCRIPCI??N</th>-->
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
                                    <td><?=substr($fila['tituloTarea'],0,20)?></td>                                    
                                    <td><?=$fila['fecha_limite']?></td>                                    
                                    <!--<td><a href="#"><?=substr($fila['descripcion'],0,20)?>...</a></td> -->
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
                              
                              <p><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" placeholder="Descripci??n/Instrucciones..." required></textarea></p>

                              <p>Fecha/Hora l??mite para que el alumno entregue la tarea: 
                              <input name="fecha_limite" type="date" class="form-control" id="example-text-input" required>
                              <input name="hora_limite" type="time" class="form-control" id="example-text-input" required></p>
                              
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

                            </div>
                          </div> 
                        </div> 
                        <!--FIN AGREGAR TAREA-->

                        <!--LISTADO DE CLASES-->
                        <div class="row tab-pane fade show <?php if( isset($_GET['p']) && ($_GET['p'] == 'editarClase' || $_GET['p'] == 'aco' || $_GET['p'] == 'eco') ) echo 'active'; ?>" id="clases" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">

                          <?php 
                          if( !isset($_GET['p'] ) ) $_GET['p'] = 'default';
                          if( $_GET['p'] == 'editarClase' ){ ?>
                              <?php 
                                    $resultado = agregarClaseForm($usuario['idPersona']);
                                    $dataEdit = dataClaseEdit( $_GET['idClase'] );
                                    $filaEdit = $dataEdit->fetch_assoc();
                                    ?>

                                    <form id="feditClase" name="feditClase" class="form-horizontal" method="post" action="editarClase.php?idClase=<?=$_GET['idClase']?>">

                                    <div class="col-sm-12">
                                      <h2>Editar Clase:</h2>
                                    </div>

                                      <p><input name="nombreClase" type="text" class="form-control" value="<?=$filaEdit['titulo']?>" id="example-text-input" required></p>
                                      
                                      <p>Curso al que pertenecer?? la clase:
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
                              <h2>??stas son las clases que usted ha subido:</h2>
                              <h3 id="lblTitleEvento_confirm"></h3>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">


                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CLASE</th>
                                    <th>CURSO</th>
                                    <!--<th>TAREA</th>-->
                                    <!--<th>DESCRIPCI??N</th>-->
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
                                    <a class="btn btn-secondary waves-effect waves-light" href="eliminarClase.php?idClase=<?=$fila['idClase']?>" title="Eliminar">Eliminar</a>
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
                              
                              <!--<p><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" placeholder="Descripci??n de la Clase..." required></textarea></p>-->
                              <!--<p><input name="recurso" type="text" class="form-control" placeholder="URL para descarga de recurso externo..." id="example-text-input"></p>-->

                              <input name="recursos" type="hidden" id="recursos">
                              
                              <p>Curso al que pertenecer?? la clase:<select class="form-control" name="idCurso">
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
                              <h2>A??adir recurso:</h2>
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
                              <button type="button" class="btn btn-dark" id="btn-add">Agregar Recurso</button>
                              <button type="button" class="btn btn-secondary" id="btn-minus" onClick="quitarRecursos();">Quitar los recursos</button><br>
                            <!-- FIN AGREGAR RECURSO-->
                            <hr>

                              <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar clase</button>
                              <button type="reset" class="btn btn-secondary waves-effect waves-light">Cancelar</button>

                            </form>

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
                                  <th>Instituci??n</th>
                                  <th>Clasificaci??n</th>
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
                                <option disabled selected>Seleccione opci??n</option>
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

          <!--TABS EXAMENES-->
          <div class="row">
            <!-- SECCION PARA CONCENTRADO DE EXAMENES -->
            <div class="col-sm-12" id="tab_concentrado_examenes" <?php if( isset( $_GET['p'] ) && ($_GET['p'] == 'aeo' || $_GET['p'] == 'editarExamen' || $_GET['p'] == 'detallesExamen'  ) ) echo 'style="display:block"'; else echo 'style="display:none"';?>>
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <!-- CONTENEDOR DE TABS -->
                      <ul class="nav nav-tabs" role="tablist">

                      <li class="nav-item">
                          <a class="nav-link active<?php if( isset($_GET['p']) && ( $_GET['p'] == "aeo" || $_GET['p'] == "editarExamen" ) ) echo "X";?>" data-toggle="tab" id="resultados-tab" href="#resultados" role="tab" aria-controls="resultados"  aria-selected="false" data-target="#resultados">
                            <span class="d-block d-sm-none"><i class="fas fa-check-double"></i></span>
                            <span class="d-none d-sm-block"><i class="fas fa-check-double"></i> Resultados de ex??menes</span>
                          </a>
                        </li>

                      <li class="nav-item">
                          <a class="nav-link <?php if( isset($_GET['p']) && $_GET['p'] == "aeo" || $_GET['p'] == "editarExamen") echo "active";?>" data-toggle="tab" id="examenes-tab" href="#listadoExamen" role="tab" aria-controls="examenes" aria-selected="true" data-target="#listadoExamen">
                            <span class="d-block d-sm-none"><i class="far fa-file-alt"></i></span>
                            <span class="d-none d-sm-block"><i class="far fa-file-alt"></i> Listado de ex??menes</span>
                          </a>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" id="agregarExamen-tab" href="#agregarExamen" role="tab" aria-controls="agregarExamen" aria-selected="true" data-target="#agregarExamen">
                            <span class="d-block d-sm-none"><i class="fas fa-plus"></i></span>
                            <span class="d-none d-sm-block"><i class="fas fa-plus"></i> Agregar Examen</span>
                          </a>
                        </li>

                        <!--
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
                                  -->


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

                              <h2>??stas son las tareas a revisar y calificar:</h2>

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
                                        <select title="Seleccione la calificaci??n a asignar para esta tarea" class="form-control" onChange="MM_jumpMenu('parent',this,0)" id="calificacion" name="calificacion">
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
                                      
                                      <p>Descripci??n/Instrucciones:<br><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" required><?=$filaEdit['descripcion']?></textarea></p>

                                      <?php
                                        $date = explode( " ", $filaEdit['fecha_limite'] );
                                        $hora = explode( ":", $date[1] );
                                      ?>
                                      <p>Fecha/Hora l??mite para que el alumno entregue la tarea: 
                                      <input name="fecha_limite" type="date" value="<?=$date[0]?>" class="form-control" id="example-text-input" required>
                                      <input name="hora_limite" type="time" value="<?=$hora[0]?>:<?=$hora[1]?>" class="form-control" id="example-text-input" required></p>

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
                              <h2>??stas son las tareas que usted ha subido:</h2>
                              <h3 id="lblTitleEvento_confirm"></h3>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">


                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CURSO</th>
                                    <th>CLASE</th>
                                    <th>TAREA</th>
                                    <th>FECHA L??MITE</th>                                    
                                    <th>DESCRIPCI??N</th>
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
                              
                              <p><textarea name="descripcion" class="form-control" rows="5" id="example-textarea-input" placeholder="Descripci??n/Instrucciones..." required></textarea></p>

                              <p>Fecha/Hora l??mite para que el alumno entregue la tarea: 
                              <input name="fecha_limite" type="date" class="form-control" id="example-text-input" required>
                              <input name="hora_limite" type="time" class="form-control" id="example-text-input" required></p>
                              
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
                                    $resultado = agregarClaseForm($usuario['idPersona']);
                                    $dataEdit = dataClaseEdit( $_GET['idClase'] );
                                    $filaEdit = $dataEdit->fetch_assoc();
                                    ?>

                                    <form id="feditClase" name="feditClase" class="form-horizontal" method="post" action="editarClase.php?idClase=<?=$_GET['idClase']?>">

                                    <div class="col-sm-12">
                                      <h2>Editar Clase:</h2>
                                    </div>

                                      <p><input name="nombreClase" type="text" class="form-control" value="<?=$filaEdit['titulo']?>" id="example-text-input" required></p>
                                      
                                      <p>Curso al que pertenecer?? la clase:
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
                              <h2>??stas son las clases que usted ha subido:</h2>
                              <h3 id="lblTitleEvento_confirm"></h3>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">


                              <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>CLASE</th>
                                    <th>CURSO</th>
                                    <!--<th>TAREA</th>-->
                                    <!--<th>DESCRIPCI??N</th>-->
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

                              <input name="recursos" type="hidden" id="recursos">
                              
                              <p>Curso al que pertenecer?? la clase:<select class="form-control" name="idCurso">
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
                              <h2>A??adir recurso:</h2>
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
                              <button type="button" class="btn btn-dark" id="btn-add">Agregar Recurso</button>
                              <button type="button" class="btn btn-secondary" id="btn-minus" onClick="quitarRecursos();">Quitar los recursos</button><br>
                            <!-- FIN AGREGAR RECURSO-->
                            <hr>

                              <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar clase</button>
                              <button type="reset" class="btn btn-secondary waves-effect waves-light">Cancelar</button>

                            </form>

                            </div>
                          </div> 
                        </div> 
                        <!--FIN AGREGAR CLASE-->


                        <!--AGREGAR EXAMEN-->
                        <div class="row tab-pane fade" id="agregarExamen" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">
                            <div class="col-sm-12">
                              <h1>Agregar Examen: </h1>

                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">

                            <?php $resultado = agregarClaseForm($usuario['idPersona']);?>

                            <form name="form1" class="form-horizontal" method="post" action="agregarExamen.php?idMaestro=<?=$usuario['idPersona']?>">

                              <p><input name="nombreExamen" type="text" class="form-control" placeholder="Nombre del examen..." id="example-text-input" required></p>                              
                              
                              <p>Curso al que pertenecer?? el examen:
                              <select class="form-control" name="idCurso">
                              <?php 
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <option value="<?=$fila['idCurso']?>"><?=$fila['nombreCurso']?></option>
                                    <?php }?>
                                <?php } ?>
                              </select>
                              </p>

                              Rango de fechas en las que el alumno podr?? presentar el examen:                                  
                              <div class="input-daterange input-group" id="date-range">
                                  <input type="date" class="form-control" name="fechaInicio" required>
                                  <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white b-0">al</span>
                                  </div>
                                  <input type="date" class="form-control" name="fechaFin" required>
                              </div>
                              
                            <hr>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar examen</button>
                            <!--<button type="reset" class="btn btn-secondary waves-effect waves-light">Cancelar</button>-->
                            <h3 id="lblTitleEvento_confirm">Preguntas del examen:</h3>

                            <div class="alert alert-warning">
                                <li>Escriba la pregunta, agregue las 4 opciones y seleccione cu??l de ellas es la correcta.</li> 
                                <li>Puede agregar m??s preguntas, s??lo se tomar??n en cuenta las que no est??n vac??as.</li> 
                            </div>
                            
                            <?php
                            for( $i = 1; $i <= 30; $i++ ){?>
                            <div id="p<?=$i?>" <?php if( $i == 1 || $i == 2 ) echo 'style="display:block"'; else echo 'style="display:none"' ?>><!--Pregunta <?=$i?>-->
                              <h4>Pregunta <?=$i?>:</h4> <input name="pregunta<?=$i?>" type="text" class="form-control" placeholder="Escriba la pregunta <?=$i?>..." id="example-text-input"><br>
                              <label> 
                                      <input name="Opcion<?=$i?>" type="radio" value="A" title="Marcar ??sta opci??n como la correcta" checked>
                                      <input name="TextoOpcion<?=$i?>_A" type="text" placeholder="Opci??n A..." id="example-text-input" style="border-color: transparent;"> 
                              </label>
                              <label> <input name="Opcion<?=$i?>" type="radio" value="B" title="Marcar ??sta opci??n como la correcta">
                                      <input name="TextoOpcion<?=$i?>_B" type="text" placeholder="Opci??n B..." id="example-text-input" style="border-color: transparent;">
                              </label>
                              <label> <input name="Opcion<?=$i?>" type="radio" value="C" title="Marcar ??sta opci??n como la correcta">
                                      <input name="TextoOpcion<?=$i?>_C" type="text" placeholder="Opci??n C..." id="example-text-input" style="border-color: transparent;">
                              </label>
                              <label> <input name="Opcion<?=$i?>" type="radio" value="D" title="Marcar ??sta opci??n como la correcta">
                                      <input name="TextoOpcion<?=$i?>_D" type="text" placeholder="Opci??n D..." id="example-text-input" style="border-color: transparent;">
                              </label>
                            </div>
                            <?php } //Fin for?>
                            <a href="javascript:agregarPregunta();" class="btn btn-dark waves-effect waves-light"><i class="fas fa-plus"></i> Agregar otra pregunta</a>
                            <hr>

                              <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar examen</button>
                            </form>

                            </div>
                          </div> 
                        </div> 
                        <!--FIN AGREGAR EXAMEN-->

                        <!--RESULTADOS-->

                        <div class="row tab-pane fade show active<?php if(isset( $_GET['p'] ) && ($_GET['p'] == "editarExamen" || $_GET['p'] == "aeo") ) echo "X"?>" id="resultados" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">

                              <!--DETALLES DE EXAMEN-->
                              <?php if( $_GET['p'] == "detallesExamen" ){?>
                              <div class="col-sm-12">
                                <!--<h1>Detalles del examen presentado... </h1>-->
                                <?php
                                    /*$infoExamen = examenInfo( $_GET[ 'idExamen' ] );
                                    $fInfoExamen = $infoExamen->fetch_assoc();
                                    
                                    echo $fInfoExamen['Nombre'];*/
                                ?>
                              </div>
                              <?php }//Fin if Detalles Examen?>
                              <!--FIN DETALLES DE EXAMEN-->

                            <div class="col-sm-12">
                              <h1>Resultados de Ex??menes: </h1>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">

                            <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>ALUMNO</th>
                                    <th>EXAMEN</th>
                                    <th>CALIFICACI??N</th>
                                    <th>FECHA DE PRESENTACI??N</th>
                                    <!--<th>OPCIONES</th>-->
                                  </tr>
                                </thead>

                                <?php 
                                $resultado = listarResultadosExamenes( $usuario['idPersona'] );
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <tr>
                                    <td><?=$fila['alumno']?></td>
                                    <td><?=$fila['Nombre']?></td>
                                    <td><?=$fila['calificacion']?></td>
                                    <td><?=$fila['fechaPresentacion']?></td>
                                    <!--<td>
                                    <a class="btn btn-primary waves-effect waves-light" href="index.php?p=detallesExamen&idExamen=<?=$fila['idExamen']?>&idResultado=<?=$fila['idResultado']?>" title="Ver detalles">Ver detalles</a>  
                                  </td>-->
                                  </tr>
                                    <?php }?>
                                <?php } ?>                               

                                <tbody>
                                  
                                </tbody>
                              </table>

                            </div>
                          </div> 
                        </div> 
                        
                        <!--fIN RESULTADOS-->

                        <!--LISTADO DE EX??MENES-->
                        <div class="row tab-pane fade <?php if( isset( $_GET['p'] ) && $_GET['p'] == "editarExamen" || $_GET['p'] == 'aeo' ) echo "show active"; ?>" id="listadoExamen" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="table-responsive">
                            <div class="col-sm-12">

                              <?php if( isset( $_GET['p'] ) && $_GET['p'] == "editarExamen" ){?>
                              <div id="editable">
                                <?php
                                $resultado = agregarClaseForm($usuario['idPersona']);
                                $reditable = examenInfo( $_GET['idExamen'] );
                                $feditable = $reditable->fetch_assoc();
                                $feditable['fechaInicio'] = substr($feditable['fechaInicio'], 0, 10);
                                $feditable['fechaFin'] = substr($feditable['fechaFin'], 0, 10);
                                ?>
                                <h1>Editar Examen</h1>

                            <form name="form1" class="form-horizontal" method="post" action="editarExamen.php?idMaestro=<?=$usuario['idPersona']?>&idExamen=<?=$_GET['idExamen']?>">

                              <p><input name="nombreExamen" type="text" class="form-control" value="<?=$feditable['Nombre']?>" id="example-text-input" required></p>                              
                              
                              <p>Curso al que pertenecer?? el examen:
                              <select class="form-control" name="idCurso">
                              <?php 
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <option <?php if( $feditable['idCurso'] == $fila['idCurso'] ) echo "selected"; ?> value="<?=$fila['idCurso']?>"><?=$fila['nombreCurso']?></option>
                                    <?php }?>
                                <?php } ?>
                              </select>
                              </p>

                              Rango de fechas en las que el alumno podr?? presentar el examen:                                  
                              <div class="input-daterange input-group" id="date-range">
                                  <input type="date" class="form-control" name="fechaInicio" value="<?=$feditable['fechaInicio']?>" required>
                                  <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white b-0">al</span>
                                  </div>
                                  <input type="date" class="form-control" name="fechaFin" value="<?=$feditable['fechaFin']?>" required>
                              </div>
                              
                            <hr>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar examen</button>
                            <button type="reset" onClick="javascrip:getElementById( 'editable' ).style.display='none';" class="btn btn-secondary waves-effect waves-light">Cancelar</button>
                            <h3 id="lblTitleEvento_confirm">Preguntas del examen:</h3>

                            <div class="alert alert-warning">
                                <li>Escriba la pregunta, agregue las 4 opciones y seleccione cu??l de ellas es la correcta.</li> 
                                <li>Puede agregar m??s preguntas, s??lo se tomar??n en cuenta las que no est??n vac??as.</li> 
                            </div>
                            
                            <?php

                            $rpreguntas = examenPreguntas( $_GET['idExamen'] );
                            $i = 0; $op = "ABCD";

                            for( $i = 1; $i <= $rpreguntas->num_rows; $i++ ){
                              $fpreguntas = $rpreguntas->fetch_assoc();
                              $respuestas = json_decode( $fpreguntas['opciones'] );?>
                            <div id="pe<?=$i?>"><!--Pregunta <?=$i?>-->
                              <h4>Pregunta <?=$i?>:</h4> <input name="pregunta<?=$i?>" type="text" class="form-control" value="<?=$fpreguntas['pregunta']?>" id="example-text-input"><br>
                              <label>
                              <?php
                                $j= 0;
                                foreach ($respuestas as $clave => $valor) {?>
                                    <!--//echo "{$clave} => {$valor} ";-->
                                    <input name="Opcion<?=$i?>" type="radio" value="<?=$op[$j]?>" title="Marcar ??sta opci??n como la correcta" <?php if( $valor == 1 ) echo "checked"; ?>>
                                    <input name="TextoOpcion<?=$i?>_<?=$op[$j]?>" type="text" value="<?=$clave?>" id="example-text-input" style="border-color: transparent;">
                              <?php  $j++; }
                              ?>                                       
                              </label>
                              
                            </div>
                            <?php } //Fin for?>

                            <?php
                            for( $i = $rpreguntas->num_rows+1; $i <= 30; $i++ ){?>
                            <div id="pe<?=$i?>" style="display:none"><!--Pregunta <?=$i?>-->
                            <h4>Pregunta <?=$i?>:</h4> <input name="pregunta<?=$i?>" type="text" class="form-control" placeholder="Escriba la pregunta <?=$i?>..." id="example-text-input"><br>
                              <label> <input name="Opcion<?=$i?>" type="radio" value="A" title="Marcar ??sta opci??n como la correcta" checked>
                                      <input name="TextoOpcion<?=$i?>_A" type="text" placeholder="Opci??n A..." id="example-text-input" style="border-color: transparent;">
                              </label>
                              <label> <input name="Opcion<?=$i?>" type="radio" value="B" title="Marcar ??sta opci??n como la correcta">
                                      <input name="TextoOpcion<?=$i?>_B" type="text" placeholder="Opci??n B..." id="example-text-input" style="border-color: transparent;">
                              </label>
                              <label> <input name="Opcion<?=$i?>" type="radio" value="C" title="Marcar ??sta opci??n como la correcta">
                                      <input name="TextoOpcion<?=$i?>_C" type="text" placeholder="Opci??n C..." id="example-text-input" style="border-color: transparent;">
                              </label>
                              <label> <input name="Opcion<?=$i?>" type="radio" value="D" title="Marcar ??sta opci??n como la correcta">
                                      <input name="TextoOpcion<?=$i?>_D" type="text" placeholder="Opci??n D..." id="example-text-input" style="border-color: transparent;">
                              </label>
                            </div>
                            <?php }//fin for 2?>
                            <a href="javascript:agregarPreguntaEditable();" class="btn btn-dark waves-effect waves-light"><i class="fas fa-plus"></i> Agregar otra pregunta</a>
                            <hr>

                              <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar examen</button>
                              <button type="reset" onClick="javascrip:getElementById( 'editable' ).style.display='none';" class="btn btn-secondary waves-effect waves-light">Cancelar</button>

                            </form>

                              <?php } //Fin editar examen form?>
                              </div>

                              <h1>Listado de Ex??menes: </h1>

                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 TBNR">

                            <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                <thead>
                                  <tr>
                                    <th>EXAMEN</th>
                                    <th>CURSO</th>
                                    <th>FECHA</th>
                                    <th>OPCIONES</th>
                                  </tr>
                                </thead>

                                <?php 
                                $resultado = listarExamenesMaestros( $usuario['idPersona'] );
                                if( $resultado->num_rows > 0 ){?>
                                  <?php while( $fila = $resultado->fetch_assoc() ){?>
                                    <tr>
                                    <td><?=$fila['nombre']?></td>
                                    <td><?=$fila['nombreCurso']?></td>
                                    <td><?=$fila['fechaInicio']?></td>
                                    <td>
                                    <a class="btn btn-primary waves-effect waves-light" href="index.php?p=editarExamen&idExamen=<?=$fila['idExamen']?>" title="Editar">Editar</a>  
                                    <a class="btn btn-secondary waves-effect waves-light" href="eliminarExamen.php?idExamen=<?=$fila['idExamen']?>" title="Eliminar">Eliminar</a>
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
                        <!--FIN LISTADO DE EX??MENES-->

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
                                  <th>Instituci??n</th>
                                  <th>Clasificaci??n</th>
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
                              <label>Instituci??n</label>
                              <select name="IDOrganizacion" id="IDOrganizacion" class="form-control only_event">
                                  <option value="0" selected="">Si pertenece a una asociaci??n, elijala</option>								  
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
                              <label>Qui??n atender?? al prospecto?</label>
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
                          <th>Inter??s</th>
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
                  <h5 class="card-header mt-0">??ltima actualizacion: <i id="detalle_fecha_comment"><!-- 2021/08/18 --></i></h5>
                  <div class="card-body">
                      <p class="card-text" id="detalle_ult_comment"> <!-- info --> </p>
                      <a href="#" class="btn btn-primary" title="Agregar un nuevo comentario del seguimiento" id="btn_agregar_comentario"><i class="fas fa-plus"></i></a>
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
                          <label>folio / n?? autorizaci??n</label>
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
                if( list_enlaces[i][0] != "" )
                uls+=`<li id="`+i+`"><a href="${list_enlaces[i][0]}" target="_blank">${list_enlaces[i][1]}</a> - <a href="#" onClick='javascript: quitarRecursoOne("`+i+`")'>
                
                <i class="fas fa-trash" style="color:#bd2525" title="Eliminar este recurso"></i>
                
                </a></li>`;
              }

              $("#name_li").val('');
              $("#enlace_li").val('');
              $("#list_recursos").html(uls);
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
              var throwawayNode = d.removeChild(d_nested); //indicar eliminaci??n
            }
            document.getElementById( "recursos" ).value = '';
            list_enlaces = [];
            console.log( document.getElementById( "recursos" ).value );
        }//Fin quitarRecursos

        function quitarRecursoOne( e ){
          var d = document.getElementById("list_recursos"); //contenedor padre
          var d_nested = document.getElementById( e ); //elemento hijo a eliminar
          var throwawayNode = d.removeChild(d_nested); //indicar eliminaci??n          

          list_enlaces[e][0] = '';
          list_enlaces[e][1] = '';
          total = list_enlaces.length; 
          for( i = 0; i < total; i++ ){
              console.log( ">>"+list_enlaces[i][0] );
              console.log( ">>"+list_enlaces[i][1] );
              if( list_enlaces[i][0] == '*' ){
                list_enlaces.splice(list_enlaces[i][0]);
                }
              }
          document.getElementById( "recursos" ).value = JSON.stringify(list_enlaces);
          console.log( "JSN----"+document.getElementById( "recursos" ).value );
        }//quitarRecursoOne

        function mostrarExamenes(){
          document.getElementById( "tab_concentrado_tareas" ).style.display="none";
          document.getElementById( "tab_concentrado_examenes" ).style.display="block";
        }//Function mostrarExamenes

        function mostrarTareas(){
          document.getElementById( "tab_concentrado_tareas" ).style.display="block";
          document.getElementById( "tab_concentrado_examenes" ).style.display="none";          
        }//Function mostrarTareas

        var pi = 3;
        function agregarPregunta(){
          if( pi <= 30 )
            document.getElementById( "p"+pi++ ).style="display:block";
        }//agregarPregunta

        var pie = <?php if( isset($rpreguntas->num_rows) ) echo $rpreguntas->num_rows+1; else echo 1; ?>;
        function agregarPreguntaEditable(){
          if( pie <= 30 )
            document.getElementById( "pe"+pie++ ).style="display:block";
        }//agregarPregunta

      </script>

      <!--<script src="panel.js"></script>-->

      <?php if (in_array($usuario['correo'], $accesos)): ?>
        
      <?php endif ?>
      <!-- fin scripts -->
      <?php 
      $str = json_encode($usuario);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>
    </body>
    </html>