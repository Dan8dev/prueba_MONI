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
  if (!isset($_SESSION["alumno_general"])) {
    header('Location: index.php');
    die();
  }else{
    
    $usr = $_SESSION["alumno_general"];
    $idusuario=$_SESSION["alumno_general"]['id_afiliado'];
    require_once 'data/Model/AfiliadosModel.php';
    $porospM = new Afiliados();
    $usuario = $porospM->obtenerusuario($idusuario);
    $fechafinmembresia=$porospM->fechafinmembresia($usuario['data']['idAsistente']);
    $fechaactual= date('Y-m-d H:i:s');
    $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

    $datetime1 = new DateTime($fechaactual);
    $datetime2 = new DateTime($fechafinmembresia);
    $interval = $datetime1->diff($datetime2);
    $diasrestantes= substr($interval->format('%R%a días'), 1);
    $dias = rtrim($diasrestantes, ' días');

}


?>

<!DOCTYPE html>
<html lang="en">
  <?php require 'plantilla/header.php'; ?>

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="evento/panel.php">INICIO</a>
          <span class="breadcrumb-item active">PANEL</span>
        </nav>
      </div><!-- br-pageheader -->

      <div class="br-pagebody">
      <?php
        if (rtrim($interval->format('%R%a días'), ' días')<0) {
          ?>
          <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Tu <strong class="d-block d-sm-inline-block-force"> Membresía</strong> a finalizado <strong class="d-block d-sm-inline-block-force"> renuévala por 1 año </strong> asistiendo al congreso
          </div><!-- alert -->
          <?php  
        }
        else { if($dias<31) {
          # code...
        ?>
        <div id="alert-pago-anual" class="alert alert-info" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?> </strong> 
        </div><!-- alert -->
        <?php } } ?>
        <!-- start you own content here -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <div class="pd-30">
				<h4 class="tx-gray-800 mg-b-5"></h4>
				
				<div class="col-sm-12 mb-4">
					<div class="card bd-0">
						<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
							<h6 class="mg-b-3"><a href="https://congresodecirugiaestetica.com/" target="_blank" class="text-white mb-10">XXIV Congreso CONGRESO INTERNACIONAL DE CIRUGÍA ESTÉTICA, MEDICINA ESTÉTICA Y OBESIDAD 2022</a></h6>
							<a href="https://congresodecirugiaestetica.com/" target="_blank" class="btn btn-block btn-primary active btn-with-icon">
								<div class="ht-40 justify-content-between">
									<span class="pd-x-15">Más información</span>
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
						<img class="card-img-bottom img-fluid" src="img/congresoenero.jpg" alt="Image">
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
  <script src="../../assets/js/template/sweetalert.min.js"></script>
  <script src="../../assets/pages/clipboard.js"></script>
  <script src="../../assets/pages/qrcode.js"></script>
      <script src="../../assets/pages/qrcode.min.js"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=AfcFm_FJBcwJ0-Urg7O8Jb_E0LpsGoO2_Oy6CFCNHWTIrDm09VNo9kCl6VWiYT9GrlT2B_0f-LYwNHQD&currency=MXN" data-sdk-integration-source="button-factory"></script>
  <?php 
  if ($pago['data']['id_asistente']==62) {
    echo "<script src='evento/panel_congreso.js'></script>";
  }
  else {
    
    echo "<script src='evento/pagos_congreso.js'></script>";
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

      <!-- fin scripts -->
      <?php 
      $str = json_encode($pago['data']);
      echo("<script> usrInfo = JSON.parse('{$str}');</script>");
      ?>

</body>
</html>
