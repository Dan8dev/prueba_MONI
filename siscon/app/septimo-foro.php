<!-- Iconografía fontawesom  (fa) https://fontawesome.com/ -->

<?php 
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
    $registro_evento = $porospM->consultar_registro_evento($_SESSION['alumno']['id_prospecto'], 75);
}


?>

<!DOCTYPE html>
<html lang="en">
<?php require 'plantilla/header.php'; ?>

<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pagebody">

        <!-- start you own content here -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <div >
                <h4 class="tx-gray-800 mg-b-5">Foro 7MO FORO NACIONAL DE PREVENCIÓN, SALUD MENTAL Y ADICCIONES</h4>
                <div class="col-sm-12 mb-4">
                    <div class="card bd-0">
                        <div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
                            <h6 class="mg-b-3"><a href="https://moni.com.mx/eventos/?e=xvii-prevencion-salud-mental-adicciones" target="_blank" class="text-white mb-10">7MO FORO NACIONAL DE PREVENCIÓN, SALUD MENTAL Y ADICCIONES</a></h6>
                            <?php if($registro_evento !== false): ?>
                              <a href="#" class="btn btn-block btn-primary active btn-with-icon disabled">
                                <div class="ht-40 justify-content-between">
                                    <span class="pd-x-15">Ya estás registrado</span>
                                    <span class="icon wd-40"><i class="fa fa-globe"></i></span>
                                </div>
                              </a>  
                            <?php else: ?>
                            <a href="#" onclick="registrar_a_evento('xvii-prevencion-salud-mental-adicciones')" class="btn btn-block btn-primary active btn-with-icon">
                              <div class="ht-40 justify-content-between">
                                  <span class="pd-x-15">Registrarme</span>
                                  <span class="icon wd-40"><i class="fa fa-globe"></i></span>
                              </div>
                            </a>
                            <?php endif; ?>
                        </div>
                        <img class="card-img-bottom img-fluid" src="https://moni.com.mx/assets/images/generales/flyers/imagenEvento797645819.png" alt="Image">
                    </div><!-- card -->
                </div>
            </div><!-- d-flex -->
        </div>
        <?php if($registro_evento !== false): ?>
        <div class="card m-4">
          <div class="card-body">
            <div class="row text-center">
              <div class="col-12"><h2>Qr de acceso</h2></div>
              <div class="col-12">
                <input  id="text" type="hidden" value="<?php echo($_SESSION['alumno']['id_prospecto']) ?>"  />
                <center>
                  <div class="img_credencial" id="qrcode"></div>
                </center>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php require 'plantilla/footer.php'; ?>
    </div>
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

<script src="../js/bracket.js"></script>
<script src="../../assets/js/template/jquery.slimscroll.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.18.0/dist/sweetalert2.all.min.js"></script>
<script src="../../assets/pages/qrcode.js"></script>
<script src="../../assets/pages/qrcode.min.js"></script>
<script src="../../assets/js/template/app.js"></script>
<script>
  function registrar_a_evento(clave_carrera) {
      $.ajax({
          url: "../../assets/data/Controller/marketing/marketingControl.php",
          type: "POST",
          data: {
              action: 'registrar_a_evento',
              tipo: 'evento',
              nombre_c: clave_carrera
          },
          beforeSend: function() {
              $(".outerDiv_S").css("display", "block")
          },
          success: function(data) {
              try {
                  resp = JSON.parse(data)
                  if (resp.estatus == 'ok') {
                      swal.fire({
                          type: 'success',
                          html: 'Su registro se realizó exitosamente'
                      }).then(()=>{
                        window.location.reload();
                      })
                  } else {
                      swal.fire({
                          type: 'info',
                          text: resp.info
                      }).then(()=>{
                        window.location.reload();
                      })
                  }
              } catch (e) {
                  console.log(e);
                  console.log(data);
              }
          },
          error: function() {},
          complete: function() {
              $(".outerDiv_S").css("display", "none")
          }
      });
  }
  <?php if($registro_evento !== false): ?>
    var widtAv = $("#qrcode").width();
    var widthQr = widtAv > 450 ? widtAv * .8 : widtAv;
    widthQr = widtAv > 900 ? widtAv * .6 : widtAv;
    var qrcode = new QRCode(document.getElementById("qrcode"), {
          width : widthQr,
          height : widthQr
        });

    // function makeCode () {    
      var elText = document.getElementById("text");
      if (!elText.value) {
        alert("Input a text");
        elText.focus();
      }else{
        qrcode.makeCode(elText.value);
      }
    // }
  <?php endif; ?>
</script>
<!-- fin scripts -->

</body>

</html>