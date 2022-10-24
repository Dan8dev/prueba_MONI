<?php 
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
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
  require_once 'data/Model/WebexModel.php';
  $webex = new Webex();
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
  /*$fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
  $fechaactual= date('Y-m-d H:i:s');
  $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

  $datetime1 = new DateTime($fechaactual);
  $datetime2 = new DateTime($fechafinmembresia);
  $interval = $datetime1->diff($datetime2);
  $diasrestantes= substr($interval->format('%R%a días'), 1);
  $dias = rtrim($diasrestantes, ' días');
  if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
    header('Location: pagos.php');
  } */

  $estatus_sesion = $webex->estatus_sesion(3);
?>
<!DOCTYPE html>
<html lang="en">
  <?php require 'plantilla/header.php'; ?>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="cursos.php">Cursos</a>
          <span class="breadcrumb-item active">Clases</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="card-header bg-primary pb-0 infinteTabs" style="overflow-x:auto;overflow-y:hidden">
        <ul class="nav_i nav-tabs_i" id="content-calificaciones">
          <?php if(!$HIDE_OPTIONS): ?>
            <li class="nav-item">
              <a class="nav-link bd-0 pd-y-8" href="javascript:void(0)"> <div onclick="consultar_calificaciones(<?php echo $_GET['curso'];?>)" id="consultar_calificaciones">Calificaciones</div> </a>
            </li>
          <?php endif; ?>
          <li>
            <a class="nav-link active bd-0 pd-y-8" href="clases.php?curso=<?php echo $_GET['curso'];?>">Clases</a>
          </li>
        </ul>
      </div>
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <?php if($_GET['curso'] == 93): #pedagogia?>
          <!--<div class="alert alert-primary" role="alert">
            <strong class="d-block d-sm-inline-block-force">Recordatorio!</strong> 
            <ul class="mb-0">
              <li>
                <a href="#" class="text-primary" onclick="$($('#content-materias li')[0]).find('a').click()">
                  Tienes un examen programado para el 23 de Abril para <b>DIRECCIÓN ESTRATÉGICA</b>
                </a>
              </li>
            </ul>
          </div>-->
        <?php elseif($_GET['curso'] == 22): ?>
          <!--<div class="alert alert-primary" role="alert">
            <strong class="d-block d-sm-inline-block-force">Recordatorio!</strong> 
            <ul class="mb-0">
              <li>
                <a href="#" class="text-primary" onclick="$($('#content-materias li')[3]).find('a').click()">
                  Tienes un examen programado para el 23 de Abril para <b>Tipos de Prevención en Adicciones y Conductas Antisociales.</b>
                </a>
              </li>
            </ul>
          </div>-->
        <?php endif; ?>
      </div>

      <div class="br-pagebody">

        <div class="card-header bg-primary pb-0 infinteTabs" style="overflow-x:auto;overflow-y:hidden" id="content-materias-nav">
          <?php if(!$HIDE_OPTIONS): ?>
          <ul class="nav_i nav-tabs_i card-header-tabs" id="content-materias">  
          </ul>
          <?php endif; ?>

        </div>

        <div class="br-section-wrapper pt-0" id="content-materias-clases">
          <h1 class="pt-4 mb-4" id="lbl_nombre-mat"></h1>
          <!-- claseswebex/index.php -->
          <!--<a href="claseswebex/index.php" style="color: #4479ab;text-decoration: underline;"><h2>Clic aquí para entrar a tu clase en vivo</h2></a>-->
          <div id="links_clase">
            
          </div>

          <?php if(!$HIDE_OPTIONS):?>

            <?php echo (!in_array($_GET['curso'], [19,20,21]))? '<a href="https://educat-platform.mykajabi.com/login" target="_blank" style="color: #c61200;text-decoration: underline;"><h2>Clic aquí para entrar a tu plataforma EDUCAT.</h2></a>' : ''; ?>
	        
		      <p class="my-4 p-3" style="font-size: 11px;text-align: justify; color: black; background-color: #f2f2f2;">Queda prohibida la reproducción total o parcial de este material en cualquier forma, ya sea mediante fotografía, impresión o cualquier otro procedimiento sin el consentimiento por escrito del autor. Hacerlo, representa una infracción en materia de los derechos de autor previstos en el artículo 229 de la Ley Federal de Derechos de Autor.</p>
          <div class="row input-group input-group-sm mb-3">
            <div class="col-sm-6 input-group input-group-sm mt-2">
              <select class="form-control" id="select-clase">
                <option disabled="" selected="">Seleccione clase</option>
              </select>
            </div>
            <div class="col-sm-6 col-md-3 ml-auto mt-2">
              <button class="btn btn-warning btn-sm" style="display:none" id="boton-tareas" onclick='$("html, body").animate({ scrollTop: document.body.scrollHeight }, "slow");'></button>
            </div>
            <div style="border-bottom: solid .5px #d0d0d0; width: 100%;" class="mt-4"></div>
          </div>

          <div class="row">
            <div class="col-sm-12 col-md-3">
              <section id="section_video_d" style="display:none">
                <h2>Video de clase</h2>
                <p>Da clic en la imagen para reproducir el video.</p>
  
                <div class="card mg-b-40">
                    <img class="card-img-bottom img-fluid" id="image-clase" src="img/novideo.jpg" alt="Image">
                    <div class="overlay-evento" >
                      <center>
                        <a href="#" target="_blank" id="viedo_link"><img src="https://conacon.org/moni/siscon/app/img/Media-Play-02-256.png" class="img-fluid w-50 mt-4"></a>
                      </center>
                    </div>
                </div>
              </section>
              <section id="section_video_nd" style="display:none">
                <p>Aún no hay video disponible para esta materia.</p>
              </section>
            </div>
            <div class="col-sm-12 col-md-9">
              <center><h2 class="py-3">Recursos descargables</h2></center>
              <ul class="ul-two-cols" id="recurso_descargable">
                
              </ul>
              <center><h2 class="py-3">Material de apoyo</h2></center>
              <ul class="ul-two-cols" id="material_apoyo">
                
              </ul>
            </div>
            <div style="border-bottom: solid .5px #d0d0d0; width: 100%;" class="mt-4"></div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <h2>  
                Tareas
              </h2>
              <div id="content_tareas">
                
              </div>
            </div>
          </div>
          <?php else: ?>
            <p class="my-4 p-3" style="font-size: 11px;text-align: justify; color: black; background-color: #f2f2f2;">Queda prohibida la reproducción total o parcial de este material en cualquier forma, ya sea mediante fotografía, impresión o cualquier otro procedimiento sin el consentimiento por escrito del autor. Hacerlo, representa una infracción en materia de los derechos de autor previstos en el artículo 229 de la Ley Federal de Derechos de Autor.</p>
            <div class="row">
              <div class="col-md-9" style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/754900401?h=f9ca6a4c93" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe></div>
              <iframe id= "transmision_vivo" class="col-md-3" src="https://vimeo.com/live-chat/754900401/" width="400" height="600" frameborder="0"></iframe>
            </div>

          <?php endif; ?>
          <hr>
          <div class="row">
            <div class="col-sm-12">
              <h2>  
                Exámenes
              </h2>
              <div id="info_examenes">
                
              </div>
            </div>
          </div>
		    </div><!-- br-section-wrapper -->

        <div class="br-section-wrapper pt-0" id="content-calificaciones-materias" style="display:none">

          <div class="container">
            <h1 class="pt-4 mb-4">Calificaciones</h1>
            <div class="table-responsive" id="mostrar-periodos">
              
            </div>
          </div>
		    </div><!-- br-section-wrapper -->

      </div><!-- br-pagebody -->
	  
      <?php require 'plantilla/footer.php'; ?>
    </div><!-- br-mainpanel -->

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Entregar tarea</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">x</button>
          </div>

          <form id="form_entrega_tarea">
            <div class="modal-body">
              <h5 id="titulo_tarea" class="mb-4"></h5>
              <div class="row">
                <div class="col-sm-12">
                  <label>Adjunte el archivo correspondiente a la entrega de su tarea.</label>
                  <input type="hidden" name="tarea_entrega" id="tarea_entrega">
                  <input type="hidden" name="clase_tarea" id="clase_tarea">
                  <div class="bootstrap-filestyle input-group input-group-sm mb-3">
                    <input type="file" class="filestyle" data-buttonname="btn-secondary" name="inp_adjunto_tarea" id="inp_adjunto_tarea" required="" accept=".doc,.docx,application/msword,.pptx,.pdf,.jpg,.jpeg,.png">
                  </div>
                  <label>Comentario (opcional)</label>
                  <div class="input-group mb-3">
                    <textarea class="form-control" name="inp_comentario_tarea" id="inp_comentario_tarea" rows="4" maxlength="255"></textarea>
                  </div>
                    <span style="float: right;" id="size_comment"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_tareas_entregadas" tabindex="-1" aria-labelledby="modal_tareas_entregadas_lbl" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_tareas_entregadas_lbl">Tareas entregadas</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">x</button>
          </div>

          <div class="modal-body">
            <!-- <h5 id="titulo_tarea" class="mb-4"></h5> -->
            <div>
              <div class="card mb-2">
                <div class="card-body py-2" id="revision_tareas">
                  
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>

        </div>
      </div>
    </div>
    <!-- ########## END: MAIN PANEL ########## -->

    <script src="../lib/jquery/jquery.js"></script>
    <script src="../lib/popper.js/popper.js"></script>
    <script src="../lib/bootstrap/bootstrap.js"></script>
    <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
    <script src="../js/bootstrap-filestyle.js"></script>
    <script src="../lib/moment/moment.js"></script>
    <script src="../lib/jquery-ui/jquery-ui.js"></script>
    <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
    <script src="../lib/peity/jquery.peity.js"></script>
    <script src="../lib/highlightjs/highlight.pack.js"></script>
    <script src="../lib/jquery.steps/jquery.steps.js"></script>
    <script src="../lib/parsleyjs/parsley.js"></script>
    
    <script src="script/clases.js"></script>

    <script src="../js/bracket.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script>
      let fecha_actual = new Date('<?php echo date('Y-m-d'); ?>');
      const generacion = <?php echo $_GET['curso']; ?>;
     $("#inp_comentario_tarea").on('keyup change', function(){
      $("#size_comment").html($("#inp_comentario_tarea").val().length+"/255")
     })
     var seconds = 0;
     let HIDE_OPTIONS = Boolean(<?= $HIDE_OPTIONS ?>);
      $(document).ready(function(){
        cargar_materias(generacion);
        consultar_sesiones(generacion);

        var exampleModal = document.getElementById('exampleModal')
        exampleModal.addEventListener('show.bs.modal', function (event) {
          // Button that triggered the modal
          var button = event.relatedTarget
          // Extract info from data-bs-* attributes
          var recipient = button.getAttribute('data-bs-whatever')
          // If necessary, you could initiate an AJAX request here
          // and then do the updating in a callback.
          //
          // Update the modal's content.
          var modalBodyInput = exampleModal.querySelector('#tarea_entrega')

          modalBodyInput.value = recipient
        })
        if(HIDE_OPTIONS){
          var cancel = setInterval(incrementSeconds, 1000);
        }
      })

      function incrementSeconds() {
            seconds += 1;
            console.log("Tiempo de reproducción: " + seconds + " seconds.");
      }

      function mayusculas(e) {
          e.value = e.value.toUpperCase();
      }

      $("#transmision_vivo").ready(function () { //wait for the frame to load
        var cont = $("transmision_vivo").contents().find(".fill");
        cont.onclick = function() { console.log("reproduciendo") };

      });

      $("#transmision_vivo").on("click",function(e){
        $.ajax({
          type: "method",
          url: "url",
          data: "data",
          dataType: "dataType",
          success: function (response) {
            
          }
        });
      })
    </script> 

  </body>
</html>
