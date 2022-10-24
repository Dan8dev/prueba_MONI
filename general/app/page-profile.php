<?php 
 function replaceAccents($str) {
  $nstring = "";
  $search = explode(",","á,é,í,ó,ú,à,è,ì,ò,ù,ñ");
  $replace = explode(",","a,e,i,o,u,a,e,i,o,u,n");

  for ($i=0; $i < sizeof($search); $i++) { 
    // echo $search[$i].", ".$replace[$i]."<br>";
    $str=str_replace($search[$i], $replace[$i], $str);
  }
  return $str;
}

if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
  exit;
}

session_start();
require "data/Model/AfiliadosModel.php";
$afiliados = new Afiliados();
if (isset($_SESSION["alumno_general"])) {
  $usr = $_SESSION["alumno_general"];

  $idusuario=$_SESSION["alumno_general"]['id_afiliado'];
  $usuario=$afiliados->obtenerusuario($idusuario);

  
}else{
  header("Location: index.php");
}

$clave_compartir = strtolower($usuario['data']['nombre']."_".$usuario['data']['apaterno']."_".$usuario['data']['amaterno']."_".$usuario['data']['idAsistente']);


// $clave_compartir = replaceAccents($clave_compartir);

$usuario['data']['codigo_compartir'] = $clave_compartir;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Facebook -->
      <meta property="og:url" content="https://www.facebook.com/posgradosiesm">
    <meta property="og:title" content="IESM">
    <meta property="og:description" content="Desde el 2006 el IESM ha buscado profesionalizar la Medicina y Cirugía Estética con programas de posgrado que cuentan con RVOE (registro de validez oficial SEP, otorgando título y cédula profesional)">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="IESM" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.conacon.org/moni/iesm" />
    <meta property="og:image" content="https://www.conacon.org/moni/iesm/app/img/logoMetas.png" />
    <meta property="og:description" content="" />
    <!-- Meta -->
    <meta name="description" content="IESM">
    <meta name="author" content="IESM Desarrollo Tecnológico">

    <title>IESM</title>

    <!-- vendor css -->

    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
    <link rel="icon" type="imge/png" href="img/favicon.png">

    <link rel="manifest" href="./manifest.json">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.0.943/build/pdf.min.js"></script>

	<?php require 'plantilla/header.php'; ?>

    <!-- ########## START: MAIN PANEL ########## -->

    <div class="br-mainpanel br-profile-page">
      <div class="card shadow-base bd-0 rounded-0 widget-4">
        <div class="card-header ht-75">
          <div class="hidden-xs-down">
            <!--<a href="" class="mg-r-10"><span class="tx-medium">498</span> Followers</a>
              <a href=""><span class="tx-medium">498</span> Following</a>-->
            </div>
            <div class="tx-24 hidden-xs-down">
              <a href="" class="mg-r-10"><i class="icon ion-ios-email-outline"></i></a>
              <a href=""><i class="icon ion-more"></i></a>
            </div>
          </div><!-- card-header -->

          <div class="card-body">
            <div class="card-profile-img">
              <img src="img/afiliados/<?php echo $usuario["data"]["foto"]; ?>" alt="">
            </div><!-- card-profile-img -->
            <h4 class="tx-normal tx-roboto tx-white"><?php echo($usuario["data"]["nombre"]." ".$usuario["data"]["apaterno"]." ".$usuario["data"]["amaterno"]); ?></h4>
            <!-- <p class="mg-b-25">OPERADOR TERAPÉUTICO EN ADICCIONES</p> -->
            <!--<p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25">primero</p>-->
            <!--<p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25">Vigencia</p>-->
            <p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25 tx-white mb-2">SMART ID</p>
            <a href="https://www.iesm.com.mx/" target="_blank"><img class="mb-3" src="img/conacon99x67.png"></a>
            <p class="mg-b-0 tx-24">
              <?php 
                if($usuario["data"]["facebook"] != ''){
                  echo "<a href='{$usuario['data']['facebook']}' class='tx-white-8 mg-r-5'><i class='fa fa-facebook-official'></i></a>";
                }
                if($usuario["data"]["twitter"] != ''){
                  echo "<a href='{$usuario['data']['twitter']}' class='tx-white-8 mg-r-5'><i class='fa fa-twitter'></i></a>";
                }
                if($usuario["data"]["instagram"] != ''){
                  echo "<a href='{$usuario['data']['instagram']}' class='tx-white-8 mg-r-5'><i class='fa fa-instagram'></i></a>";
                }
              ?>
              <!--<a href="#" id="a_shareCode" class="tx-white-8">-->
			        <!--<a href="../cv/?perfil=<?php /*echo $usuario['data']['id_afiliado'];*/ ?>" class="tx-white-8" target="_blank">
                <i class="fa fa-eye"></i>-->
              </a>
            </p>
          </div><!-- card-body -->
        </div><!-- card -->

        <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
          <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#posts" role="tab">CONTACTO</a></li>
            <!--<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reconocimientos" role="tab">Experiencia</a></li>-->
          </ul>
        </div>

        <div class="tab-content br-profile-body">
          <?php 
            
            // echo iconv('UTF-8','ASCII//TRANSLIT',$clave_compartir);
          ?>
          <?php if ($dias<31&&isset($_SESSION["alumno_general"])) {
            # code...
          ?>
          <div id="alert-pago-anual" class="alert alert-info" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php echo $diasrestantes?> <a class="text-dark" href="pagos.php">Pague aqui</a></strong> 
          </div><!-- alert -->
          <?php } ?>
          <div class="tab-pane fade active show" id="posts">

            

            <div class="row">

              <div class="col-lg-4 mg-t-30 mg-lg-t-0">
                <div class="card pd-20 pd-xs-30 shadow-base bd-0">
                  <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Datos</h6>
                  <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Tel:</label>
                  <p class="tx-info mg-b-25"> <?php echo $usuario["data"]["celular"]; ?></p>
                  <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Email</label>
                  <p id="emailobtener" class="tx-inverse mg-b-25"><a mailto="contacto@tsuconsejeria.com"> <?php echo $usuario["data"]["email"]; ?></a></p>
                  <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Dirección</label>
                  <p class="tx-inverse mg-b-25"> <?php echo $usuario["data"]["calle"].'  '.$usuario["data"]["colonia"].' '.$usuario["data"]["ciudad"].' '.$usuario["data"]["estado"].', '.$usuario["data"]["pais"]?></p>
                </div>
              </div><!-- col-lg-4 -->


              <div class="col-lg-8">
                <div class="media-list bg-white rounded shadow-base">
                  <div class="media pd-20 pd-xs-30">
                    <div class="media-body mg-l-20">
                      <div class="row mg-t-20 ">
                        <div class="col-lg-4 col-sm-12 text-center">
						            <!--<a href="https://conacon.org/moni/siscon/cv/?perfil=<?php /*echo $usuario['data']['id_afiliado'];*/ ?>" target="_blank">-->
                          <div class="card shadow-base pd-25 bd-0 mg-t-20">
                            <div class="card bd-0" id="qrcode">
                              <!-- aqui va la cadena de texto que contiene la imagen qr -->
                              <?php 
                              date_default_timezone_set('America/Mexico_City');
                              $contenQr = "MECARD:N:{$usuario["data"]["nombre"]} {$usuario["data"]["apaterno"]}; TEL:{$usuario["data"]["celular"]}; EMAIL:{$usuario["data"]["email"]};;";
                              ?>
                              <input  id="text" type="hidden" value="<?php echo($contenQr) ?>"  />
                              <div class="img_credencial" id="qrcode"></div>
                            </div><!-- card -->
                          </div><!-- card -->
						            <!--</a>-->
                        </div> 
                      </div> 
                    </div><!-- media-body -->
                  </div><!-- media -->
                </div><!-- card -->
              </div><!-- col-lg-8 -->
            </div><!-- row -->
          </div><!-- tab-pane -->

          <div class="tab-pane fade" id="reconocimientos">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="media-list bg-white rounded shadow-base">
                  <div class="media pd-20 pd-xs-30">
                    <div class="media-body mg-l-20">
                      <div class="col-lg-12 col-sm-12 text-center mg-t-25 mt-0">
                        <div class="row br-profile-page card-body">
                          <div class="col-sm-12"><h5>Formación académica</h5></div>
                          <div class="col-sm-12 ">
                            <ul id="listar_grado" class="pl-0">
                              <li><?php echo $usuario['data']['ugestudios'].' '.ucfirst(strtolower($usuario['data']['tipoLicenciatura'])).' - '. $usuario['data']['cedulap']; ?></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div id="listar" class="row mg-t-20 ">

                      </div> 
                    </div><!-- media-body -->
                  </div><!-- media -->
                </div><!-- card -->
              </div><!-- col-lg-8 -->

            </div><!-- row -->
          </div><!-- tab-pane -->
        </div><!-- br-pagebody -->
      </div><!-- br-mainpanel -->

      <!-- ########## END: MAIN PANEL ########## -->

      <div class="loader" id="loader">
        <div class="loadState"></div>
      </div>
      <script src="../lib/jquery/jquery.js"></script>
      <script src="../lib/popper.js/popper.js"></script>
      <script src="../lib/bootstrap/bootstrap.js"></script>
      <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
      <script src="../lib/moment/moment.js"></script>
      <script src="../lib/jquery-ui/jquery-ui.js"></script>
      <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
      <script src="../lib/peity/jquery.peity.js"></script>
      <script src="script/qrcode.js"></script>
      <script src="script/qrcode.min.js"></script>
      <script src="../js/bracket.js"></script>
      <script src="../js/sweetalert.min.js"></script>
      <script src="./sw.js"></script>
      <script src="script/credencial.js"></script>
      <script src="script/listarconstancias.js"></script>
      <script type="text/javascript">
        // const alumnoLoged = JSON.parse('<?php echo json_encode($usuario['data']); ?>');

        $(document).ready(function(){
          console.log('<?php echo $clave_compartir; ?>');
          if(navigator.share){
            $("#a_shareCode").on("click", function(e){
              e.preventDefault();
              navigator.share({
                text: 'https://conacon.org/moni/siscon/cv/?perfil=<?php echo $usuario['data']['id_afiliado']; ?>'
              })
            })
          }else{

            $("#a_shareCode").prop("href", "whatsapp://send?text=https://conacon.org/moni/siscon/cv/?perfil=<?php echo $usuario['data']['id_afiliado']; ?>")
            $("#a_shareCode").attr("data-action", "share/whatsapp/share")
                    //href="whatsapp://send?text=<?php #echo($usuario["persona"]["codigo"]); ?>" 
                    //data-action="share/whatsapp/share"
          }
        });
      </script>
      <script type="text/javascript">
        $(function() {
          var isMobile = {
            Android: function() {
              return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
              return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
              return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
              return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
              alert(navigator.userAgent.match(/IEMobile/i));
              return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
              return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
            }
          }
          if( isMobile.iOS() ){
            alert('1.- Click en el botón compartir'+"\n2.- Añadir a pantalla de inicio");
          }
        });
      </script>

      <script type="text/javascript">
        var qrcode = new QRCode(document.getElementById("qrcode"), {
          width : 110,
          height : 110
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
      </script>
    </body>
    </html>

