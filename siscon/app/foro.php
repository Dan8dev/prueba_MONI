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
  $registro_evento = $porospM->validar_registro_evento($_SESSION['alumno']['id_prospecto'], 38);

  $contenQr = '';
  if($registro_evento[0]){
    $contenQr = $_SESSION['alumno']['id_prospecto'].'-38';
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
      <span class="breadcrumb-item active">FORO</span>
    </nav>
  </div><!-- br-pageheader -->

  <div class="br-pagebody">

    <!-- start you own content here -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
      <div class="py-0">
        <h4 class="tx-gray-800 mg-b-5">Foro 1ER FORO INTERNACIONAL EN SALUD MENTAL Y ADICCIONES</h4>
        <div class="col-sm-12 mb-4">
          <div class="card bd-0">
            <div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
              <h6 class="mg-b-3 text-white mb-10">1er foro internacional en salud mental y adicciones</h6>
              <a href="#" onclick="registrar_a_evento('primer-foro-internacional-salud-mental-y-adicciones')" class="btn btn-block btn-primary active btn-with-icon">
                <div class="ht-40 justify-content-between">
                 <span class="pd-x-15">Registrarme</span>
                 <span class="icon wd-40"><i class="fa fa-globe"></i></span>
                </div>
              </a>
  					</div>
  					<img class="card-img-bottom img-fluid" src="https://moni.com.mx/assets/images/generales/flyers/banner2.png" alt="Image">
          </div>
        </div>
      </div>
      <div class="br-pagebody mg-t-5 pd-x-30">
        <div class="row row-sm">
          <div class="card shadow-base card-body pd-25 bd-0 mg-t-20">
            <div class="card bd-0">
              <input  id="text" type="hidden" value='<?php echo($contenQr) ?>'  />
              <div class="img_credencial m-auto" id="qrcode"></div>
            </div>
          <center>
            <h3 class="mt-3" id="contador_folio"></h3>
          </center>
          </div>
        </div>
      </div>
    </div>  
  </div><!-- br-pagebody -->
  <?php require 'plantilla/footer.php'; ?>
</div><!-- br-mainpanel -->
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
<script src="../../assets/pages/clipboard.js"></script>
<script src="../../assets/pages/qrcode.js"></script>
<script src="../../assets/pages/qrcode.min.js"></script>

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
            /*if(resp.hasOwnProperty('ejecutiva') && resp.ejecutiva !== null && resp.ejecutiva.telefono.trim() != ''){
              ejecutiva = `<p>O si lo prefieres puedes enviar un mensaje tu mismo.</p>
              <a href='https://api.whatsapp.com/send?phone=+521${resp.ejecutiva.telefono.replace(/\s+/g, '').trim()}' target="_blank" class='text-success'>Clic aquí <i class='fa fa-whatsapp tx-24'></i></a>`;
            }*/
            mensaje = `<span>
            <h5>Su registro ha sido exitoso</h5>
            <p></p>
            ${ejecutiva}
            </span>`;

                // document.createElement('span');
                // h5 = document.createElement('h5');
                // h5.te
                // mensaje.append();
                swal.fire({
                  type:'success',
                  html:mensaje
                }).then(()=>{
					location.reload();
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

  function crear_qr(){
    if($("#qrcode").length > 0){
      var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
      var height = (window.innerheight > 0) ? window.innerheight : screen.height;
      vm = 0;
      console.log('width:'+width)
      console.log('height:'+height)
      if(width > height){
        vm = height;
      }else{
        vm = width;
      }
      if(vm > 800){
        vm = vm * .6;
      }
      var qrcode = new QRCode(document.getElementById("qrcode"), {
          width : vm * .7,
          height : vm * .7
        });

        function makeCode () {    
          var elText = document.getElementById("text");
          
          if (!elText.value) {
            alert("Input a text");
            elText.focus();
            return;
          }
          
          qrcode.makeCode(elText.value);
        }

        makeCode();

        $("#text").
        on("blur", function () {
          makeCode();
        }).
        on("keydown", function (e) {
          if (e.keyCode == 13) {
            makeCode();
          }
        });
    }
  }

  if($("#text").val() != ''){
    crear_qr()
    $("#contador_folio").html("2022-<?php echo(rand(100,1000)); ?>")
  }
</script>
<!-- fin scripts -->


</body>
</html>
