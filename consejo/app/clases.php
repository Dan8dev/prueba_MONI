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
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
  $fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
  $fechaactual= date('Y-m-d H:i:s');
  $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

  $datetime1 = new DateTime($fechaactual);
  $datetime2 = new DateTime($fechafinmembresia);
  $interval = $datetime1->diff($datetime2);
  $diasrestantes= substr($interval->format('%R%a días'), 1);
  $dias = rtrim($diasrestantes, ' días');
  if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
    header('Location: pagos.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
  <?php require 'plantilla/header.php'; ?>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">Clases</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <?php if ($dias<31) {
          # code...
        ?>
        <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?> </strong> 
        </div><!-- alert -->
        <?php } ?>
        <!--<h4 class="tx-gray-800 mg-b-5">TRAINING 28 de Septiembre  6:00 PM</h4>
        <p class="mg-b-0">Dá click en el link para iniciar tu sesión en Webex</p>-->
      </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">
		<!-- claseswebex/index.php -->
		<a href="#" style="color: #4479ab;text-decoration: underline;"><h2>Click aqui para entrar a tu clase en vivo</h2></a>
		<p class="my-4 p-2" style="font-size: 11px;text-align: justify; color: black; background-color: #f2f2f2;">Queda prohibida la reproducción total o parcial de este material en cualquier forma, ya sea mediante fotografía, impresión o cualquier otro procedimiento sin el consentimiento por escrito del autor. Hacerlo, representa una infracción en materia de los derechos de autor previstos en el artículo 229 de la Ley Federal de Derechos de Autor.</p>
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
              <h2>Video de clase</h2>
			  <p>Da click en la imagen para reproducir el video.</p>
              <img id="clase_video" src="https://conacon.org/moni/siscon/app/img/ItunesArtwork@2x.png" class="img-fluid" style="display: none;">
              <div class="overlay-evento" >
                <a href="#" target="_blank" id="viedo_link"><img style="width: 25%;margin-left: 40%;margin-top: 25%;" src="https://conacon.org/moni/siscon/app/img/Media-Play-02-256.png" class="img-fluid"></a>
              </div>
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
		    </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->
	  
      <footer class="br-footer">
        <div class="footer-left">
          <div class="mg-b-2">Copyright &copy; 2021. CONACON TI. All Rights Reserved.</div>
        </div>
        <div class="footer-right d-flex align-items-center">
          <span class="tx-uppercase mg-r-10">SÍGUENOS:</span>
          <a target="_blank" class="pd-x-5" href="#"><i class="fa fa-facebook tx-20"></i></a>
          <a target="_blank" class="pd-x-5" href="#"><i class="fa fa-twitter tx-20"></i></a>
        </div>
      </footer>
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
     $("#inp_comentario_tarea").on('keyup change', function(){
      $("#size_comment").html($("#inp_comentario_tarea").val().length+"/255")
     })
      $(document).ready(function(){
        cargar_clases('<?php echo $_GET['curso']; ?>');

        var exampleModal = document.getElementById('exampleModal')
        exampleModal.addEventListener('show.bs.modal', function (event) {
          console.log(event)
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

      })
      function mayusculas(e) {
          e.value = e.value.toUpperCase();
        } 
    </script> 

  </body>
</html>
