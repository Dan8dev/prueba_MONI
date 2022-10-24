<?php
if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}

session_start();
if (!isset($_SESSION["alumno"])) {
    header('Location: index.php');
    die();
}
$usr = $_SESSION['alumno'];
require "data/Model/AfiliadosModel.php";
$idusuario = $_SESSION['alumno']['id_afiliado'];
$idusuario1 = $_SESSION['alumno']['id_prospecto'];
$afiliados = new Afiliados();
$usuario = $afiliados->obtenerusuario($idusuario);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Twitter -->
  <meta name="twitter:site" content="@ColegioConacon">
  <meta name="twitter:creator" content="@ColegioConacon">
  <meta name="twitter:card" content="Colegio Nacional de Consejeros">
  <meta name="twitter:title" content="CONACON">
  <meta name="twitter:description" content="Únete a la red más grande de Consejeros">
  <meta name="twitter:image" content="#">
  <!-- Facebook -->
  <meta property="og:url" content="https://www.facebook.com/ColegioConacon/">
  <meta property="og:title" content="CONACON">
  <meta property="og:description" content="Únete a la red más grande de Consejeros">
  <meta property="og:image" content="#">
  <meta property="og:image:secure_url" content="#">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="600">
  <!-- Open Graph data -->
  <meta property="og:title" content="CONACON" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://conacon.org/moni/siscon" />
  <meta property="og:image" content="https://conacon.org/moni/siscon/app/img/logoMetas.png" />
  <meta property="og:description" content="Únete a la red más grande de Consejeros" />
  <!-- Meta -->
  <meta name="description" content="Colegio nacional de consejeros. CONACON">
  <meta name="author" content="CONACON TI">

  <title>CONACON</title>

  <!-- vendor css -->
  <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
  <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link href="../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
  <link href="../lib/highlightjs/github.css" rel="stylesheet">
  <link href="../lib/jquery.steps/jquery.steps.css" rel="stylesheet">

  <link rel="icon" type="imge/png" href="img/favicon.png">

  <!-- Bracket CSS -->
  <link rel="stylesheet" href="../css/bracket.css">
  <?php require 'plantilla/header.php';
    echo '<input type="number" class="d-none" id="id_afiliado" value="'.$idusuario.'">';
    echo '<input type="number" class="d-none" id="id_prospecto" value="'.$idusuario1.'">';
  ?>
  <!-- ########## START: MAIN PANEL ########## -->
  <div class="br-mainpanel">
    <div class="br-pageheader pd-y-12 pd-l-20">
      <nav class="breadcrumb pd-0 mg-0 tx-12">
        <a class="breadcrumb-item" href="panel.php">Panel</a>
        <span class="breadcrumb-item active">Directorio</span>
      </nav>
    </div><!-- br-pageheader -->

    <div class="br-pagebody">
      <div class="row">
        <div class="col-md-8">
          <div class="card  card-body pd-15 bd-0 border ">
            <div class="pd-25 d-flex align-items-center">
              <img class="w-10" id="img_edo">
              <div class="mg-l-15">
                <h4 class="titulojal tx-10 tx-spacing-1 tx-mont tx-inverse tx-20 tx-uppercase mg-b-10">Miembros del Colegio</h4>
                <hr class="linea"></hr>
                <p class="subtjal tx-10 tx-Active tx-spacing-1 tx-mont  tx-20 tx-uppercase mg-b-10" id="edodirec"></p>
              </div>
            </div>

            <div class="capitulojalform tab-pane fade active show">
              <div class="navegadordirec form-group">
                <nav class="breadcrumb pd-0 text-dark ">
                <div class="btn-group filtrojal">
                    <p>Selecciona lo que estas buscando</p>
                    <button id="btn-search-centros">Centros</button>
                    <button id="btn-search-afiliados">Afiliados</button>
                  </div>
                </nav>
              </div>
              <div class="row">
                <div class="col-lg-4 col-md-12 mt-3">
                  <input id="txt-search-input" class="form-control" type="text" placeholder="Ingresa el nombre del centro">
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12">
                  <div class="row">
                    <div class="col-md-12 col-lg-3 mt-3">
                      <button id="#" class="btn btn-primary mx-2"> 
                        <img src="img/directorio/bucar.png"> Buscar
                      </button>
                    </div>
                    <div class="col-md-12 col-lg-3 mt-3">
                      <button id="agregar_comision" class="btn btn-primary mx-2"> 
                        <img src="img/directorio/asignar.png"> Asignar comisión
                      </button>
                    </div>
                    <div class="col-md-12 col-lg-2 mt-3">
                      <button class="btn btn-primary mx-2" id="Crear">
                        <img src="img/directorio/evento.png"> Solicitar evento
                      </button>
                    </div>
                  </div>
                </div>
              </div>
             
              <div class="row">
                <div class="table-responsive table-bordered-none col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <table id = "tableAfiliados" class="table" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                    <thead>
                      <th>Nombre</th>
                      <th>Correo</th>
                      <th>Numero</th>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-4 overflow-auto">
          <div class="card bd-0 border bg-light text-dark">
            <div class="pd-25 d-flex align-items-center col-sm-12 col-xs-12 my-3 ">
              <div class="mg-l-1 tittlealta">
                <h3> <img src="img/expediente.png"> Altas nuevas de centros</h3>
                <div id="notification-container">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    
    <div class="modal fade" id="asiganarComision" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel"> <img src="img/centrosdetratamiento/personal.png"> Asignación de comisión &nbsp;</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form_asignar_jerarquia">
              <div class="form-group ">
                <input class="col-7" placeholder="Ingresa el nombre del consejero" id="inp_buscar"></input>
                <button class="col-4" type="button" id="button_search"><i class="fa fa-search"></i> Buscar</button><br>
                <select class="col-7 select_improvisado" style="display:none" multiple="multiple" name="prospecto" id="select_prosp" onfocus="expand_sel(this)" >
                </select>
                <br>
                <span>La persona tiene que estar registrado en la base de datos de CONACON.</span>
              </div>
              <div class="form-group " id="asignarcomision">
                <!-- <label for="">Santiago Torres Pérez</label> <span>OTA</span> <br> -->
                <select class="col-6" id="select_jerarquias" name="jerarquia">
                </select>
                <button class="col-4" type="submit"> Guardar Comisión</button><br>
              </div>
            </form>
          </div>
          <!-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Send message</button>
          </div> -->
        </div>
      </div>
    </div>


    <div id="ModalCrear" class="modal fade capitulojalform" role="dialog">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-tittle"><img src="img/directorio/evento2.png"> Solicitud de evento</h4>
          </div>
          <form class="form-horizontal" role="form" id="form-crear">
            <div class="modal-body">
              <div class="row form-group col-md-12">
                <div class="col-10">
                  <label for="nevento" class="form-label">Nombre del evento:</label>
                  <input type="text" class="form-control" id="nevento" name="nevento" required>
                </div>
              </div>
              <div class="row">
                <div class="form-group form-inline text-center">
                  <div class="col-4">
                    <label for="" class="form-label">Fecha del evento</label>
                  </div>
                  <div class="col-4">
                    <label for="finicio" class="form-label">Del</label>
                    <input type="date" class="form-control" id="finicio" name= "finicio" required>
                  </div>
                  <div class="col-4">
                    <label for="ffin" class="form-label">Al</label>
                    <input type="date" class="form-control" id="ffin" name="ffin" required>
                  </div>
                </div>
              </div>

              <div class="form-group form-inline text-center">
                <div class="row">
                  <div class="col-3">
                    <label for="" class="form-label">Modalidad del evento</label>
                  </div>
                  <label class="radio-inline col-3">
                    <input type="radio" name="optradio"  value="1" checked>En linea
                  </label>
                  <label class="radio-inline  col-3">
                    <input type="radio" name="optradio" value="2">Presencial
                  </label>
                  <label class="radio-inline  col-2">
                    <input type="radio" name="optradio" value="3">Hibrido
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label for="desevento">Descripción del evento:</label>
                <textarea class="form-control text-justify" id="desevento" placeholder="Favor de inscribir ampliamente la naturaleza del evento para poder realizar la comunicacion e imagen de  tal." rows="3" required name="desevent"></textarea>
              </div>

              <div class="form-group">
                <label for="desevento">Orden del día o programa:</label>
                <textarea class="form-control" id="desevento" placeholder="Ingresa el orden del día" rows="2" required name="cronograma"></textarea>
              </div>
              <!-- <label class="custom-file d-flex">
                <input type="file" id="file1" class="custom-file-input tx-dark  d-none">
                <img class="text-right ml-auto p-2 " src="img/directorio/subirarchivo2.png"> subir archivo (.word / .txt )
              </label> -->

              <div class="form-group">
                <label for="desevento">Dirección:</label>
                <input type="text" class="form-control col-10" id="direccion" name="direvent" required>
              </div>
              <div class="form-group">
                <label for="desevento" id="lcol1">Colaboradores:</label>
                <input type="text" class="form-control col-10" id="col1" name="col1" >
              </div>

              <div class="row form-group col-md-12 d-flex">
                <div class="col-sm-1 col-xs-2 d-inline-flex">
                </div>
              </div>

              <div class="container" id="acoldinamico">      
              </div>
              <button type="button" class="btn btn-primary ml-auto p-2 mx-2 my-2 " id="agregar_datolcol">
                <span class="fa fa-plus"></span>
                <span class="hidden-xs"> Agregar</span>
              </button>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                <span class="glyphicon glyphicon-remove"></span>
                <span class="hidden-xs"> Cerrar</span>
              </button>
              <button type="submit" id="solicitarEvento"  class="btn btn-primary">
                <span class="fa fa-save"></span>
                <span class="hidden-xs"> Solicitar Eventos</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- <div id="ModalAgregardatoscol" class="modal fade capitulojalform" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-tittle"> <img src="img/directorio/evento2.png"> Solicitud de evento</h4>
          </div>
          <form class="form-horizontal" role="form" id="form-agregar">
            <div class="modal-body">
              <div class="form-group col-md-12">
                <div class="headder row">
                  <div class="col-7">
                    <p class="tx-dark tx-dark">Logotipos de los colaboradores &nbsp; </p> <img
                        src="img/directorio/logotipocolaboradores.png" align="right">
                    <span>(Buena resolución y tamaño 500x500 piexeles tamaño minimo).</span>
                  </div>
                  <div class="filescol col-5">
                    <label class="custom-file">
                      <input type="file" id="file" class="custom-file-input tx-dark d-none"><img
                        src="img/directorio/subirarchivo.png"> &nbsp; Subir archivo
                    </label>
                  </div>
                </div>
                <div class="form-group form-inline text-center">
                  <div class="col-5">
                      <label for="" class="form-label tx-dark">Tipo de constancia:</label>
                  </div>
                  <label class="radio-inline col-2 tx-dark">
                      <input type="radio" name="optradio" checked>Digital
                  </label>
                  <label class="radio-inline  col-2 tx-dark">
                      <input type="radio" name="optradio">Física
                  </label>
                  <label class="radio-inline  col-2 tx-dark">
                      <input type="radio" name="optradio">Ambas
                  </label>
                  <span class="col-12">(La impresión del formato va
                      por parte del centro).
                  </span>
                </div>
                <div class="buscarconsejero form-group">
                  <div class="col-10">
                    <label for="" class="form-label">Datos de contacto del
                      organizador del evento:</label>
                    <input type="text" class="form-control" id="nomorgani"
                      placeholder="Nombre">
                    <input type="text" class="form-control" id="telorgani"
                      placeholder="Teléfono">
                    <button class="col-8"><i class="fa fa-search"></i> Buscar</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <span class="glyphicon glyphicon-remove"></span><span class="hidden-xs">
              Cerrar</span>
            </button>
            <button type="button" id="agregarColaborador" class="btn btn-primary">
              <span class="fa fa-save"></span><span class="hidden-xs"> Agregar colaboador</span>
            </button>
            
          </div>
        </div>
      </div>
    </div> -->

    <!-- <div id="ModalAgregarNombre" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-tittle">Agregar archivo</h4>
          </div>
          <form class="form-horizontal" role="form" id="form-agregar">
            <div class="modal-body">
              <div class="form-group col-md-12">
                <label for="agregar_nombre" class="control-label col-sm-4">Selecciona tu archivo </label>
                <div class="col-sm-8">
                  <input type="file" class="form-control" id="agregar_nombre" name="agregar_nombre">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                <span class="glyphicon glyphicon-remove"></span><span class="hidden-xs"> Cerrar</span>
              </button>
              <button type="button" id="GuardarNombre" name="GuardarNombre" class="btn btn-primary">
                <span class="fa fa-save"></span><span class="hidden-xs"> Guardar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div> -->
    <?php require 'plantilla/footer.php';?>
  </div>
  <!-- ########## END: MAIN PANEL ########## -->

  <script src="../lib/jquery/jquery.js"></script>
  <script src="../lib/popper.js/popper.js"></script>
  <script src="../lib/bootstrap/bootstrap.js"></script>
  <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../lib/moment/moment.js"></script>
  <script src="../lib/jquery-ui/jquery-ui.js"></script>
  <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
  <script src="../lib/peity/jquery.peity.js"></script>
  <script src="../lib/highlightjs/highlight.pack.js"></script>
  <script src="../lib/jquery.steps/jquery.steps.js"></script>
  <script src="../lib/parsleyjs/parsley.js"></script>

  <!-- <script src="script/proximos-eventos.js"></script> -->

  <script src="../js/bracket.js"></script>
  <script src="../js/sweetalert.min.js"></script>
  <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../js/directorio.js"></script>

    </body>

</html>
