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
  $estatus = $_SESSION['alumno']['estatus'];
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
  #$registro_evento = $porospM->validar_registro_evento($_SESSION['alumno']['id_prospecto'], 38);

  $contenQr = '';
  // if($registro_evento[0]){
  //   $contenQr = $_SESSION['alumno']['id_prospecto'].'-38';
  // }
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
    <input type="text" class = "d-none" id = "EsatusClinica" value ="<?php echo $estatus;?>">

    <!-- start you own content here -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
      <div class="py-0">
        <h4 class="tx-gray-800 mg-b-5">AMOR CON AMOR SE PAGA</h4>
        <div class="col-sm-12 mb-4">
          <div class="card bd-0">
            <div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
              <h6 class="mg-b-3 text-white mb-10">Amor con Amor se Paga</h6>
              <a href="#" onclick="registrar_a_evento('amor_con_amor_se_paga')" class="btn btn-block btn-primary active btn-with-icon">
                <div class="ht-40 justify-content-between">
                 <span class="pd-x-15">Registrarme</span>
                 <span class="icon wd-40"><i class="fa fa-globe"></i></span>
                </div>
              </a>
  					</div>
  					<img class="card-img-bottom img-fluid" src="https://moni.com.mx/assets/images/generales/flyers/flyer-amor-con-amor.jpg" alt="Image">
          </div>
        </div>
      </div>

    <h2 class="mv-0 my-4">Programa</h2>
    <!--Codigo carrusel de imagenes-->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <!--<li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>-->
      </ol>
      <div class="carousel-inner">
        <!--<div class="carousel-item active">
          <img class="d-block w-100" src="../../assets/images/generales/flyers/modulo1.jpg" alt="primer slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../../assets/images/generales/flyers/modulo2.jpg" alt="segundo slide">
        </div>-->
        <div class="carousel-item active">
          <img class="d-block w-100" src="../../assets/images/generales/flyers/modulo3.jpg" alt="tercer slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../../assets/images/generales/flyers/modulo4.jpg" alt="cuarto slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../../assets/images/generales/flyers/modulo5.jpg" alt="quinto slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../../assets/images/generales/flyers/modulo6.jpg" alt="sexto slide">
        </div>

      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
<!--Codigo carrusel de imagenes-->
</div>
      <br>
	<div class="br-pagebody mt-2 pd-x-30 card card-body py-3 mb-2">
      <div class="row">
        <div class="col text-center">
          <a href="https://moni.com.mx/assets/files/clases/apoyos/guia-3.pdf" target="_blank" class="text-primary">
            <h3>
              <i class="fa fa-download" aria-hidden="true"></i>
              Descarga tu guía práctica Módulo 3
            </h3>
          </a>
        </div>
      </div>  
    </div>
    <div class="br-pagebody mg-t-5 pd-x-30 card card-body py-5 mb-4">
      <h2 class="">Enlaces de eventos</h2>
	  <p class="bg-light p-2">20 minutos antes de que de inicio tu plática se activará el botón azul con la leyenda <b>Entrar a la sesión</b>. <br> Da clic en el botón para ingresar a la plática.</p>
      <table id = "tabla_enlaces" class="table table-striped" >
        <thead>
          <th>Evento</th>
          <th>Fecha</th>
          <th></th>
        </thead>
        <tbody>
      <!-- <tr>
        <td>LA TRIADA DE LA ADICCIÓN LA RECUPERACIÓN Y LA ABSTINENCIA</td>
        <td>12 de mayo 2022, 17:00 hrs</td>
        <td><a href="/claseswebex/?sesion=119&evento=42" class="btn btn-primary">Entrar a la sesión</a></td>
      </tr> -->
		  <!--<tr>
            <td>¿Qué es y cómo manejar el Síndrome de Abstinencia Post-aguda? La recuperación.</td>
            <td>19 de mayo 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=137&evento=43" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>Entiendiendo la recaída.</td>
            <td>26 de mayo 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=150&evento=44" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>Fases y señales de peligro de la recaída. Plan de prevención</td>
            <td>02 de junio 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=168&evento=45" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>Introducción y manejo de la crisis</td>
            <td>09 de junio 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=181&evento=46" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>Cómo detener mis impulsos de adicción.</td>
            <td>16 de junio 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=198&evento=47" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>Mente sabia: cómo tomar decisiones efectivas</td>
            <td>23 de junio 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=225&evento=48" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>Cómo regularme a través de la atención plena</td>
            <td>30 de junio 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=227&evento=49" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>Hablemos de espiritualidad</td>
            <td>06 de julio 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=241&evento=50" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <!--<tr>
            <td>El auto cuidado y la espiritualidad</td>
            <td>13 de julio 2022, 17:00 hrs</td>
            <td><a href="https://conacon.org/moni/siscon/app/claseswebex/?sesion=256&evento=51" class="btn btn-primary">Entrar a la sesión</a></td>
          </tr>-->
		  <tr>
            <td>-- TEST --</td>
            <td>13 de julio 2022, 17:00 hrs</td>
            <td><a href="claseswebex/?sesion=264&evento=62&&flag=1" class="btn btn-primary">Entrar a la sesión</a></td>
      </tr>
    </tbody>
      </table>
    </div>
      <!-- <div class="br-pagebody mg-t-5 pd-x-30">
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
      </div> -->

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
    //crear_qr()
    // $("#contador_folio").html("2022-<?php #echo($registro_evento[1]['inscritos']); ?>")
  }

  $.ajax({
    url: "../../assets/data/Controller/eventos/eventosControl.php",
    type: "POST",
    data: {action:'listado_eventos', tipo:'amor-con-amor'},
    success: function(data){
      try{
        resp = JSON.parse(data)
        if(resp.length == 0){
          $("#tabla_enlaces").parent().css("display", "none");
        }
        $("#tabla_enlaces tbody").html('')
        for(i in resp){
          $("#tabla_enlaces tbody").append(`<tr><td>${resp[i].titulo}</td><td>${resp[i].fechaE != '' ? resp[i].fechaE.substr(0,10) : ''}</td><td>${resp[i].webex_id != null ? `<button onClick= "EntrarSesion(${resp[i].webex_id},${resp[i].idEvento})"  class="btn btn-primary">Entrar a la sesión</button>` : ''} </td></tr>`);
        }
      }catch(e){
        console.log(e);
        console.log(data);
      }
    }
  });

  function EntrarSesion(idwebex, idevento){
    var estatusClinic = $("#EsatusClinica").val();
    //console.log(estatusClinic);
    if(estatusClinic == '10'){
      Swal.fire({
        text: '¿Ingresar de forma individual?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        input: 'checkbox',
        inputPlaceholder: '<b>¿Acceder como Clínica?</b>',
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar',
    }).then(result=>{
          switch(result.value){
            case 0:
              Swal.fire({type: 'success', text: 'Ingreso de forma individual!'});
              window.open('claseswebex/?sesion='+idwebex+'&evento='+idevento+'&flag=0','_blank');
              break;
            case 1:
              Swal.fire({type: 'success', text: "Ingreso como clinica"});
              window.open('claseswebex/?sesion='+idwebex+'&evento='+idevento+'&flag=1', '_blank');
              break;
            default:
              console.log("Cancel");
              break;
          }
      });
    }else{
      Swal.fire({type: 'success', text: 'Ingreso de forma individual!'});
      window.open('claseswebex/?sesion='+idwebex+'&evento='+idevento+'&flag=0','_blank');
    }
}

</script>
<!-- fin scripts -->


</body>
</html>
