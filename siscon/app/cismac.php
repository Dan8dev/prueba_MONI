<!-- Iconografía fontawesom  (fa) https://fontawesome.com/ -->

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
  }else{
    
    $usr = $_SESSION['alumno'];
    $idusuario=$_SESSION['alumno']['id_afiliado'];
    require_once 'data/Model/AfiliadosModel.php';
    $porospM = new Afiliados();
    $usuario = $porospM->obtenerusuario($idusuario);

    /* $fechafinmembresia=$porospM->fechafinmembresia($usuario['data']['idAsistente']);
    $fechaactual= date('Y-m-d');
    $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

    $datetime1 = new DateTime($fechaactual);
    $datetime2 = new DateTime($fechafinmembresia);
    $interval = $datetime1->diff($datetime2);
    $diasrestantes= substr($interval->format('%R%a días'), 1);
    $dias = rtrim($diasrestantes, ' días');
    if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
      header('Location: pagos.php');
    } */

}


?>

<!DOCTYPE html>
<html lang="en">
  <?php require 'plantilla/header.php'; ?>

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="page-profile.php">INICIO</a>
          <span class="breadcrumb-item active">CISMAC</span>
        </nav>
      </div><!-- br-pageheader -->

      <div class="br-pagebody">

        <!-- start you own content here -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <div class="pd-30">
				<h4 class="tx-gray-800 mg-b-5">Evento CISMAC 2022</h4>
				
				<div class="col-sm-12 mb-4">
					<div class="card bd-0">
						<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
							<h6 class="mg-b-3"><a href="https://cismac.com.mx/" target="_blank" class="text-white mb-10">CISMAC 2022</a></h6>
							<a href="#" onclick="registrar_a_evento('cismac-congreso-22')" class="btn btn-block btn-primary active btn-with-icon">
								<div class="ht-40 justify-content-between">
									<span class="pd-x-15">Registrarme</span>
									<span class="icon wd-40"><i class="fa fa-globe"></i></span>
								</div>
							</a>
							<!--<a href="#" target="_blank" class="btn btn-block btn-primary active btn-with-icon">
								<div class="ht-40 justify-content-between">
									<span class="pd-x-15">Pagar ahora</span>
									<span class="icon wd-40"><i class="fa fa-credit-card"></i></span>
								</div>
							</a>-->
						</div><!-- card-body -->
						<img class="card-img-bottom img-fluid" src="https://conacon.org/moni/assets/images/generales/flyers/cismac.png" alt="Image">
              		</div><!-- card -->
					</div>
			
            </div><!-- d-flex -->
        <div class="br-pagebody mg-t-5 pd-x-30">
          <div class="row row-sm">
                <!-- CODE HERE!!! -->
                <?php
                    require 'evento/html_congreso.php';
                ?>
            <!-- CODE HERE!!! -->
          </div><!-- row -->
        </div>
      </div>  
    </div><!-- br-pagebody -->
    <?php require 'plantilla/footer.php'; ?>
  </div><!-- br-mainpanel -->
  <!-- ########## END: MAIN PANEL ########## -->

  <!-- Modals -->
  <div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="lbl_modal-confirm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title m-0" id="lbl_modal-confirm">Confirmar talleres seleccionados.</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body" >
          <h3>
            Confirma la siguiente lista de talleres para reservar su lugar?
          </h3>
          <ul id="lista_talleres_selected">
            
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
          <button onclick="$('#form_apartar_talleres').submit();" class="btn btn-success waves-effect">Continuar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <!-- End Modals -->

  <script src="../lib/jquery/jquery.js"></script>
  <script src="../lib/popper.js/popper.js"></script>
  <script src="../lib/bootstrap/bootstrap.js"></script>
  <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../lib/moment/moment.js"></script>
  <script src="../lib/jquery-ui/jquery-ui.js"></script>
  <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
  <script src="../lib/peity/jquery.peity.js"></script>

  <script src="../js/bracket.js"></script>
  <script src="../../assets/js/template/jquery.slimscroll.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.18.0/dist/sweetalert2.all.min.js"></script>
  <script src="../../assets/pages/clipboard.js"></script>
  <script src="../../assets/pages/qrcode.js"></script>
      <script src="../../assets/pages/qrcode.min.js"></script>
  <?php 
  if ($pago['data']['id_asistente']==62 || $pago['data']['id_asistente']==385|| $pago['data']['id_asistente']==551|| $pago['data']['id_asistente']==552) {
    echo "<script src='evento/panel_congreso.js'></script>";
  }
  else {
    
    $pago['data']=array('id_asistente'=>$usuario['data']['idAsistente'],
                        'id_evento'=>'2');

  }  
      ?>
      <script type="text/javascript">
              new ClipboardJS('.clpb', {
                text: function(trigger) {
                    return trigger.getAttribute('aria-label');
                }
              });
      </script>
      <script src="../../assets/js/template/app.js"></script>
      <script>
        function registrar_a_evento(clave_carrera){
          $.ajax({
            url: "../../assets/data/Controller/marketing/marketingControl.php",
            type: "POST",
            data: {action:'registrar_a_evento', tipo:'evento', nombre_c:clave_carrera},
            beforeSend : function(){
              $(".outerDiv_S").css("display", "block")
            },
            success: function(data){
              try{
                  resp = JSON.parse(data)
                  if(resp.estatus == 'ok'){

                ejecutiva = '';
                if(resp.hasOwnProperty('ejecutiva') && resp.ejecutiva !== null && resp.ejecutiva.telefono.trim() != ''){
                  ejecutiva = `<p>O si lo prefieres puedes enviar un mensaje tu mismo.</p>
                  <a href='https://api.whatsapp.com/send?phone=+521${resp.ejecutiva.telefono.replace(/\s+/g, '').trim()}' target="_blank" class='text-success'>Clic aquí <i class='fa fa-whatsapp tx-24'></i></a>`;
                }
                mensaje = `<span>
                <h5>Su registro ha sido exitoso</h5>
                <p>En unos momentos su ejecutiva se pondrá en contacto con usted.</p>
                ${ejecutiva}
                  </span>`;
                  
                // document.createElement('span');
                // h5 = document.createElement('h5');
                // h5.te
                // mensaje.append();
                    swal.fire({
                      type:'success',
                      html:mensaje
                    })
                  }else{
                    swal.fire({
                      type:'info',
                      text:resp.info
                    })
                  }
              }catch(e){
                console.log(e);
                console.log(data);
              }
            },
            error: function(){
            },
            complete: function(){
              $(".outerDiv_S").css("display", "none")
            }
          });
        }
      </script>
      <!-- fin scripts -->
      <?php 
      $str = json_encode($pago['data']);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>

</body>
</html>
