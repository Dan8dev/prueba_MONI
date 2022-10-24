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
  }
  $usr = $_SESSION["alumno_general"];

  require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION["alumno_general"]['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);

  $usuario['data']['instituciones'] = $afiliados->obtener_instituciones_afiliados($usuario['data']['id_prospecto'])['data'];

  /**/
?>

<!DOCTYPE html>
<html lang="en">
<?php require 'plantilla/header.php'; ?>
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
  <div class="br-pageheader pd-y-15 pd-l-20">
    <nav class="breadcrumb pd-0 mg-0 tx-16">
      <a hred="javascript:void(0)" class="breadcrumb-item active text-primary forsection" id="ctr-talleres" sect="talleres">TALLERES</a>
      <a hred="javascript:void(0)" class="breadcrumb-item forsection" id="ctr-programa" sect="programa">PROGRAMA</a>
      <a hred="javascript:void(0)" class="breadcrumb-item forsection" id="ctr-programa-ingles" sect="programa-ingles"><i>PROGRAM</i></a>
    </nav>
  </div><!-- br-pageheader -->
  <div>
    <h4 class="tx-gray-800 mg-b-5"></h4>

    
    <div class="br-pagebody mt-0" id="sect-talleres">
      <img class="card-img-bottom img-fluid" src="img/congresoenero.jpg" alt="Image">

      <!-- start you own content here -->

      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-20">
        <div class="br-pagebody mg-t-5 pd-x-30 card" id="main_view">
          <div class="text-center py-3">
            <h3>Realice su selección de talleres</h3>
          </div>

          <div class="row">
            <div class="col-sm-12 mb-4 mt-4" style="display:none">
              <select class="form-control" id="select-evento">
              </select>
            </div>
          </div>

          <form id="form-seleccionar-talleres">
            <div class="row" id="container-talleres">

            </div>
          </form>
        </div>
        <div class="" id="view-qr" style="display: none;">
          <div class="card shadow-base card-body bd-0 mg-t-20">
            <div class="card bd-0">
              <input id="text-qr" type="hidden" />
              <div class="img_credencial m-auto" id="qrcode"></div>
            </div>
            <hr>
            <div class="col-12">
              <h5>Itinerario.</h5>
              <div class="row" id="lista_talleres_seleccionados">

              </div>
            </div>
            <div class="col-12 card">
              <div class="card-body">

                <h5>Para las ponencias del</h5>
                <h4>DÍA SÁBADO 29 DE ENERO:</h4>
                <table class="table table-sm my-4">
                  <tr>
                    <td>09:20 AM</td>
                    <td class="pl-2"><b>Facial Paralysis</b></td>
                  </tr>
                  <tr>
                    <td>09:40 AM</td>
                    <td class="pl-2"><b>Brow Lift</b></td>
                  </tr>

                  <tr>
                    <td>10:20 AM</td>
                    <td class="pl-2"><b>Rhinoplasty analysus and grafting</b></td>
                  </tr>
                  <tr>
                    <td>10:40 AM</td>
                    <td class="pl-2"><b>Nasal valve reconstruction</b></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>

                <ul class="pl-2">
                  <li>Se estará impartiendo interpretación simultánea del inglés al español mediante la APP: <img
                      class="my-2 img-fluid" src="img/logo.png"></li>
                  <li>Da click para descargala en tu teléfono:</li>
                  <ul>
                    <li><a target="_blank"
                        href="https://play.google.com/store/apps/details?id=com.listentech.ListenEverywhere&hl=es_MX&gl=US"><img
                          class="my-2" src="img/google.png"></a></li>
                    <li><a target="_blank" href="https://apps.apple.com/mx/app/listen-everywhere/id1391914337"><img
                          class="my-2" src="img/app.png"></a></li>
                  </ul>
                  <li>Debes llevar tus propios auriculares.</li>
                  <li>No se estarán distribuyendo receptore el día del evento, tu teléfono será tu receptor.</li>
                  <li>Te notificamos que durante el uso de la App Listen Everywhere, perderás la conectividad Wifi para
                    recibir mensajes o notificaciones.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- br-pagebody -->

    <div class="br-pagebody" id="sect-programa" style="display:none">
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-20">
      <div id="accordion" class="accordion" role="tablist" aria-multiselectable="true">
        <div class="card">
          <div class="card-header" role="tab" id="headingOne">
            <h6 class="mg-b-0">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
              aria-expanded="true" aria-controls="collapseOne" class="tx-gray-800 transition">
              <h5>VIERNES 28 DE ENERO DE 2021 <i class="fa fa-sort-down float-right"></i></h5> 
              </a>
            </h6>
          </div><!-- card-header -->

          <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
            <div class="card-block pd-20">
              <?php require_once("plantilla/viernes_28.html") ?>
            </div>
          </div>
        </div><!-- card -->

        <!-- INICIA CARD 2 -->
        <div class="card">
          <div class="card-header" role="tab" id="headingTwo">
            <h6 class="mg-b-0">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"
              aria-expanded="true" aria-controls="collapseTwo" class="tx-gray-800 transition">
              <h5>SÁBADO 29 DE ENERO DE 2020 <i class="fa fa-sort-down float-right"></i></h5>
              </a>
            </h6>
          </div><!-- card-header -->

          <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="card-block pd-20">
              <?php require_once("plantilla/sabado_29.html") ?>
            </div>
          </div>
        </div><!-- card -->

        <!-- INICIA CARD 3 -->
        <div class="card">
          <div class="card-header" role="tab" id="headingTres">
            <h6 class="mg-b-0">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTres"
              aria-expanded="true" aria-controls="collapseTres" class="tx-gray-800 transition">
              <h5>PLENARIA ALTERNA <i class="fa fa-sort-down float-right"></i></h5>
              </a>
            </h6>
          </div><!-- card-header -->

          <div id="collapseTres" class="collapse" role="tabpanel" aria-labelledby="headingTres">
            <div class="card-block pd-20">
              <?php require_once("plantilla/p_a.html") ?>
            </div>
          </div>
        </div><!-- card -->

        <!-- INICIA CARD 4 -->
        <div class="card">
          <div class="card-header" role="tab" id="headingCuatro">
            <h6 class="mg-b-0">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseCuato"
              aria-expanded="true" aria-controls="collapseCuato" class="tx-gray-800 transition">
              <h5>DOMINGO 30 DE ENERO 2021 <i class="fa fa-sort-down float-right"></i></h5>
              </a>
            </h6>
          </div><!-- card-header -->

          <div id="collapseCuato" class="collapse" role="tabpanel" aria-labelledby="headingCuatro">
            <div class="card-block pd-20">
              <?php require_once("plantilla/domingo_30.html") ?>
            </div>
          </div>
        </div><!-- card -->

        <!-- INICIA CARD 5 -->
        <div class="card">
          <div class="card-header" role="tab" id="headingCinco">
            <h6 class="mg-b-0">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseCinco"
              aria-expanded="true" aria-controls="collapseCinco" class="tx-gray-800 transition">
              <h5>PLENARIA ALTERNA <i class="fa fa-sort-down float-right"></i></h5>
              </a>
            </h6>
          </div><!-- card-header -->

          <div id="collapseCinco" class="collapse" role="tabpanel" aria-labelledby="headingCinco">
            <div class="card-block pd-20">
              <?php require_once("plantilla/p_a2.html") ?>
            </div>
          </div>
        </div><!-- card -->

        <!-- ADD MORE CARD HERE -->
      </div><!-- accordion -->

      </div>
    </div>

    <div class="br-pagebody" id="sect-programa-ingles" style="display:none">
      <img src="plantilla/sabadoP1.jpg" alt="sabado 1" class="img-fluid">
      <img src="plantilla/sabadoP2.jpg" alt="sabado 2" class="img-fluid">
      <img src="plantilla/sabadoP3.jpg" alt="sabado 3" class="img-fluid">
    </div>

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

  <script src="../js/sweetalert.min.js"></script>
  <script src="../js/bracket.js"></script>

  <script src="../../assets/pages/qrcode.js"></script>
  <script src="../../assets/pages/qrcode.min.js"></script>

  <script>
    const currencyF = { style: 'currency', currency: 'USD' };
    const moneyFormat = new Intl.NumberFormat('en-US', currencyF);
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    userInfo = JSON.parse('<?php echo json_encode($usuario); ?>');
    $(document).ready(function () {
      cargar_eventos();

    })

    function cargar_eventos() {
      instituciones = userInfo.data.instituciones.reduce((acc, item) => {
        acc.push(item.id_institucion);
        return acc;
      }, [])

      $.ajax({
        url: "data/CData/eventosControl.php",
        type: "POST",
        data: { action: 'eventos_instituciones', institucion: [19, 20] },
        beforeSend: function () {
        },
        success: function (data) {
          try {
            var resp = JSON.parse(data)
            options_v = "<option selected disabled>Seleccione un evento para ver los talleres</option>"
            for (var i = 0; i < resp.length; i++) {
              options_v += `<option value="${resp[i].idEvento}">${resp[i].titulo}</option>`
            }
            $("#select-evento").html(options_v);
            $("#select-evento").val(35)
            seleccion_talleres_prospecto();
          } catch (e) {
            console.log(e);
            console.log(data);
          }
        },
        complete: function () {
        }
      });
    }

    $("#select-evento").on('change', function () {
      seleccion_talleres_prospecto();

    });

    $("#form-seleccionar-talleres").on('submit', function (e) {
      e.preventDefault();
      if ($(".form-check-input:checked").length == 0) {
        swal('Por favor, seleccione al menos un taller');
      } else {
        fData = new FormData(this);
        fData.append('action', 'seleccion_talleres');
        fData.append('evento', $("#select-evento").val());
        $.ajax({
          url: "data/CData/eventosControl.php",
          type: "POST",
          data: fData,
          contentType: false,
          processData: false,
          beforeSend: function () {
          },
          success: function (data) {
            try {
              resp = JSON.parse(data)
              if (resp.estatus == 'ok') {
                swal({
                  icon: 'success',
                  text: 'Su selección se ha guardado con éxito.'
                }).then((response) => {
                  seleccion_talleres_prospecto()
                })
              } else {
                swal({
                  icon: 'info',
                  text: resp.info
                })
                console.log(resp)
              }
              $("#form-seleccionar-talleres")[0].reset()
            } catch (e) {
              console.log(e);
              console.log(data);
            }
          }
        });
      }
    });

    function seleccion_talleres_prospecto() {
      $.ajax({
        url: "data/CData/eventosControl.php",
        type: "POST",
        data: { action: 'seleccion_talleres_prospecto', evento: $("#select-evento").val() },
        beforeSend: function () {
        },
        success: function (data) {
          try {
            var resp = JSON.parse(data)
            if (resp.estatus == 'ok') {
              if (resp.data.length > 0) {
                $("#main_view").css('display', 'none')
                $("#view-qr").css('display', 'block');
                $("#text-qr").val(userInfo.data.id_prospecto)
                var html_accesos = '';
                for (var i = 0; i < resp.data.length; i++) {
                  privado = (resp.data[i].evento_privado == 1) ? true : false;
                  var asistido = '';
                  if (resp.data[i].asistido != null) {
                    asistido = `<li class="pl-0"><span class="rounded-pill bg-light">
                      <i class="fa fa-check-circle text-success tx-20"></i> Asistido
                    </span></li>`
                  }
                  html_accesos += `<div class="col mb-3">
                    <div class="card shadow-base bd-0">
                      <div class="card-header bg-${privado ? 'primary' : 'light'} d-flex justify-content-between align-items-center">
                        <h6 class="card-title tx-uppercase tx-12 mg-b-0 ${privado ? 'tx-white' : ''}">${((privado) ? 'Evento privado' : 'Taller')}</h6>
                        <span class="tx-12 tx-uppercase ${privado ? 'tx-white' : ''}">${(resp.data[i].salon != '') ? '<i class="fa fa-street-view"></i> <b>Salón: </b>' + resp.data[i].salon : ''} </span>
                      </div><!-- card-header -->
                      <div class="card-body d-xs-flex justify-content-between align-items-center">
                        <h5 class="mg-b-0 tx-inverse tx-lato tx-bold" style="max-height: 6vh;overflow: hidden;" title="${resp.data[i].nombre}">${resp.data[i].nombre}</h5>
                        <ul>
                          <li class="pl-0">
                            <p class="mg-b-0 tx-sm d-flex flex-nowrap"><i class="fa fa-calendar mr-2"></i>${resp.data[i].fecha.substr(0, 10)}</p>
                          </li>
                          <li class="pl-0">
                            <p class="mg-b-0 tx-sm d-flex flex-nowrap"><i class="icon ion-ios-time-outline tx-18 mr-2"></i>${resp.data[i].fecha.substr(11)}</p>
                          </li>
                          ${asistido}
                        </ul>
                      </div><!-- card-body -->
                    </div><!-- card -->
                  </div>`
                }
                $("#lista_talleres_seleccionados").html(html_accesos);
                crear_qr()
              } else {
                $("#main_view").css('display', 'block');
                $("#view-qr").css('display', 'none');
                $.ajax({
                  url: "data/CData/eventosControl.php",
                  type: "POST",
                  data: { action: 'talleres_eventos', evento: $("#select-evento").val() },
                  beforeSend: function () {
                  },
                  success: function (data) {
                    try {
                      var resp = JSON.parse(data)
                      var talleres = resp.data;
					  console.log(talleres)
                      var html_talleres = "<div class='col-sm-12 col-md-6 mb-4'>";
                      if (resp.data.length > 0) {
                        var fecha_e = resp.data[0].fecha.substr(0, 10);
                        // var fecha_e = resp.data[0].fecha.substr(0, 16);// sí cambio
                        fecha_e_split = fecha_e.split('-');
                        var talleres_disp = false;

                        html_talleres += `<div class="col-12"><h5>${meses[parseInt(fecha_e_split[1]) - 1]} ${fecha_e_split[2]}</h5></div>`
                        for (var i = 0; i < resp.data.length; i++) {
                          taller = resp.data[i];
                          if (!parseFloat(taller.costo) > 0 && parseFloat(taller.evento_privado) != 1) {
                            // if(taller.fecha.substr(0, 16) != fecha_e){// sí cambio
                              talleres_disp = true;
                            if (taller.fecha.substr(0, 10) != fecha_e) {
                              // fecha_e = taller.fecha.substr(0, 16);// sí cambio
                              fecha_e = taller.fecha.substr(0, 10);
                              fecha_e_split = fecha_e.split('-');
                              html_talleres += "</div><div class='col-sm-12 col-md-6 mb-4'>" + `<div class="col-12"><h5>${meses[parseInt(fecha_e_split[1]) - 1]} ${fecha_e_split[2]}</h5></div>`
                            }

                            html_talleres += `<div class="form-check px-3">
                              <input class="form-check-input" type="radio" name="taller_${fecha_e.split('-')[2]}" id="RadioDef${i}" value="${taller.id_taller}">
                              <label class="form-check-label d-flex" for="RadioDef${i}">
                                ${taller.nombre} <!--<span class="ml-auto">${moneyFormat.format(taller.costo)}</span>-->
                                <span class="ml-auto" style="white-space: nowrap;"><b>Salón:</b>${taller.salon} ${taller.fecha.substr(11, 5)}hrs.</span>
                              </label>
                            </div> <hr>`
                          }
                        }
                      }
                      if(!talleres_disp){
                        $("#main_view").css('display', 'none')
                        $("#view-qr").css('display', 'block');
                        $("#text-qr").val(userInfo.data.id_prospecto)
                        crear_qr();
                      }
                      html_talleres += `</div><div class="col-12">
                  <div class="">
                    <button class="btn btn-primary" type="submit">
                      Confirmar selección
                    </button>
                  </div>
                </div>`
                      $("#container-talleres").html(html_talleres)
                    } catch (e) {
                      console.log(e);
                      console.log(data);
                    }
                  },
                  complete: function () {
                  }
                });
                // cargar_eventos();
              }
            }
          } catch (e) {
            console.log(e);
            console.log(data);
          }
        },
        complete: function () {
        }
      });
    }

    function crear_qr() {
      if ($("#qrcode").length > 0) {
        var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        var height = (window.innerheight > 0) ? window.innerheight : screen.height;
        vm = 0;
        console.log('width:' + width)
        console.log('height:' + height)
        if (width > height) {
          vm = height;
        } else {
          vm = width;
        }
        if (vm > 800) {
          vm = vm * .6;
        }
        var qrcode = new QRCode(document.getElementById("qrcode"), {
          width: vm * .7,
          height: vm * .7
        });

        function makeCode() {
          var elText = document.getElementById("text-qr");

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

    $(".forsection").on("click", function () {
      var id = $(this).attr("sect");
      var active = $(".forsection.active").attr('sect')
      if(id == 'talleres'){
        $("#ctr-programa").removeClass('active');$("#ctr-programa").removeClass('text-primary');
        $("#ctr-programa-ingles").removeClass('active');$("#ctr-programa-ingles").removeClass('text-primary');
        $("#ctr-talleres").addClass('active')
        $("#ctr-talleres").addClass('text-primary')
        if($("#sect-talleres").css('display') == 'none'){
          $("#sect-"+active).fadeOut('fast', function () {
            $("#sect-talleres").fadeIn('fast');
           })
        }
      }else if(id=='programa'){
        $("#ctr-talleres").removeClass('active');$("#ctr-talleres").removeClass('text-primary');
        $("#ctr-programa-ingles").removeClass('active');$("#ctr-programa-ingles").removeClass('text-primary');
        $("#ctr-programa").addClass('active')
        $("#ctr-programa").addClass('text-primary')
        if($("#sect-programa").css('display') == 'none'){
          $("#sect-"+active).fadeOut('fast', function () {
            $("#sect-programa").fadeIn('fast');
           })
        }
      }else{
        $("#ctr-talleres").removeClass('active');$("#ctr-talleres").removeClass('text-primary');
        $("#ctr-programa").removeClass('active');$("#ctr-programa").removeClass('text-primary');
        $("#ctr-programa-ingles").addClass('active')
        $("#ctr-programa-ingles").addClass('text-primary')
        if($("#sect-programa-ingles").css('display') == 'none'){
          $("#sect-"+active).fadeOut('fast', function () {
            $("#sect-programa-ingles").fadeIn('fast');
           })
        }
      }
    });
  </script>
  </body>

</html>
