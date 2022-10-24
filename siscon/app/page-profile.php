<?php
function replaceAccents($str)
{
  $nstring = "";
  $search = explode(",", "á,é,í,ó,ú,à,è,ì,ò,ù,ñ");
  $replace = explode(",", "a,e,i,o,u,a,e,i,o,u,n");

  for ($i = 0; $i < sizeof($search); $i++) {
    // echo $search[$i].", ".$replace[$i]."<br>";
    $str = str_replace($search[$i], $replace[$i], $str);
  }
  return $str;
}

if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
  //Tell the browser to redirect to the HTTPS URL.
  //header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
  //Prevent the rest of the script from executing.
  //exit;
}

session_start();
require "data/Model/AfiliadosModel.php";
$afiliados = new Afiliados();
if (isset($_SESSION["alumno"])) {
  $usr = $_SESSION['alumno'];

  $idusuario = $_SESSION['alumno']['id_afiliado'];
  $idusuario1 = $_SESSION['alumno']['id_prospecto'];
  //var_dump($idusuario);
  $usuario = $afiliados->obtenerusuario($idusuario);

  //var_dump($usuario);
  /* $fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
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
} else {
  header("Location: index.php");
}

$clave_compartir = strtolower($usuario['data']['nombre'] . "_" . $usuario['data']['apaterno'] . "_" . $usuario['data']['amaterno'] . "_" . $usuario['data']['idAsistente']);


// $clave_compartir = replaceAccents($clave_compartir);

$usuario['data']['codigo_compartir'] = $clave_compartir;

?>

<!DOCTYPE html>
<html lang="en">
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

    <link rel="manifest" href="./manifest.json">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.0.943/build/pdf.min.js"></script>

    <?php require 'plantilla/header.php'; ?>

    <!-- ########## START: MAIN PANEL ########## -->
    <input type="number" class="d-none" id="usuariosesion" value="<?php echo $idusuario1; ?>">
    <input type="number" class="d-none" id="clave_compartir" value="<?php echo $clave_compartir; ?>">
    <input type="number" class="d-none" id="id_afiliado" value="<?php echo $idusuario; ?>">
    <input type="number" class="d-none" id="id_prospecto" value="<?php echo $idusuario1; ?>">
    <p id="emailobtener" class="tx-inverse mg-b-25" class="hidden"><a mailto="contacto@tsuconsejeria.com"> <?php echo $usuario["data"]["email"]; ?></a></p>

    <div class="br-mainpanel br-profile-page">
      <div class="card shadow-base bd-0 rounded-0 widget-4">
        <div class="card-header ht-75">
          <div class="hidden-xs-down">
            <!--<a href="" class="mg-r-10"><span class="tx-medium">498</span> Followers</a>
              <a href=""><span class="tx-medium">498</span> Following</a>-->
          </div>
        </div><!-- card-header -->

        <div class="card-body">
          <div class="card-profile-img">
            <?php $foto = (file_exists("img/afiliados/" . $usuario['data']['foto']) ? "img/afiliados/" . $usuario['data']['foto'] : 'https://conacon.org/moni/siscon/img/default.jpg'); ?>
            <img src="<?= $foto ?>" alt="">
          </div><!-- card-profile-img -->
          <h4 class="tx-normal tx-roboto tx-white"><?php echo ($usuario["data"]["nombre"] . " " . $usuario["data"]["apaterno"] . " " . $usuario["data"]["amaterno"]); ?></h4>
          <!-- <p class="mg-b-25">OPERADOR TERAPÉUTICO EN ADICCIONES</p> -->
          <!--<p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25">primero</p>-->
          <!--<p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25">Vigencia</p>-->
          <p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25 tx-white mb-2">SMART ID</p>
          <p class="d-none" id="cverificada"> <i class="fa fa-eye"></i> Cuenta verificada</p>
          <p class="d-none" id="cnverificada"><i class="fa fa-close"></i> Cuenta NO verificada</p>
          <p class="d-none" id="cnverificando"><i class="fa fa-spinner"></i> Cuenta en PROCESO de verificación</p>
          <a href="http://conacon.org" target="_blank"><img class="mb-3" src="img/conacon99x67.png"></a>
          <p class="mg-b-0 tx-24">
            <?php
            if ($usuario["data"]["facebook"] != '') {
              echo "<a href='{$usuario['data']['facebook']}' class='tx-white-8 mg-r-5'><i class='fa fa-facebook-official'></i></a>";
            }
            if ($usuario["data"]["twitter"] != '') {
              echo "<a href='{$usuario['data']['twitter']}' class='tx-white-8 mg-r-5'><i class='fa fa-twitter'></i></a>";
            }
            if ($usuario["data"]["instagram"] != '') {
              echo "<a href='{$usuario['data']['instagram']}' class='tx-white-8 mg-r-5'><i class='fa fa-instagram'></i></a>";
            }
            ?>
            <!--<a href="#" id="a_shareCode" class="tx-white-8">-->
            <a href="../cv/<?= $usuario['data']['plantillapp'] ?>/?perfil=<?php echo $usuario['data']['id_afiliado']; ?>" class="tx-white-8" target="_blank">
              <i class="fa fa-eye"></i>
            </a>
          </p>
        </div><!-- card-body -->
      </div><!-- card -->
      <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
        <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#posts" role="tab">
            <span class="d-block d-md-none"><i class="fa fa-address-card fa-2xl"></i></span>
		        <span class="d-none d-md-block"><i class="fa fa-address-card fa-2xl"></i> CONTACTO</span></a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reconocimientos" role="tab">
            <span class="d-block d-md-none"><i class="fa fa-certificate fa-2xl"></i></span>
						<span class="d-none d-md-block"><i class="fa fa-certificate fa-2xl"></i> FORMACIÓN ACADÉMICA</span></a></li>
          <li id="verificacion" class="nav-item"><a class="nav-link" data-toggle="tab" href="#verificadocs" role="tab">
            <span class="d-block d-md-none"><i class="fa fa-check-circle fa-2xl"></i></span>
						<span class="d-none d-md-block"><i class="fa fa-check-circle fa-2xl"></i> VERIFICACIÓN CONACON</span></a></li>
        </ul>
      </div>

      <div class="tab-content br-profile-body">

        <div class="tab-pane fade active show col-20"  id="posts">
          <div class="row row-sm">  
            <!--Palabras Bienvenida -->
            <div class="card col-lg-6 col-md-6 col-sm-12 p-5 mt-1 mb-5 text-justify">
              &nbsp;Te doy la más cordial bienvenida a mi página. Para mi es de suma importancia incluirte en todo lo que aquí comunico y que seas parte activa de este espacio y con esto formemos una red profesional robusta, para generar conocimiento juntos, que nos sirva para aportar de manera más contundente al mundo de la salud mental y la prevención de las Conductas Antisociales.<br><br>
              &nbsp;Necesitamos generar una comunicación productiva, en donde tu retroalimentación y la mía sean parte de una comunicación efectiva y eficiente, de beneficio para toda esta gran red profesional que estamos configurando y sobre todo de beneficio para la sociedad . <br>
              Gracias nuevamente por tu presencia. <br>&nbsp;¡Bienvenido!
              <div class="firma" align="center">
                <img src="img/pageprofile/firma.png" width="40%"><br>
                <b>Lic. Arturo Conde Pérez</b><br>
                Presidente del Colegio Nacional de Consejeros
              </div>
            </div>
            <!--Fin Palabras Bienvenida -->

            <!--inicio datos -->
            <div class="card col-lg-6 col-md-6 col-sm-12 p-5 mt-1 mb-5">
              
                <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 ">Datos</h6>
                <p class="linea"></p>
                <div class="row ">
                  <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                    <div class="bg-white rounded overflow-hidden">
                      <div class="pd-l-10 pd-t-10  d-flex  align-items-center">
                        <img src="img/pageprofile/smartphone1.png" >  <p class="tx-info mg-l-20">Tel: <br> <?php echo $usuario["data"]["celular"]; ?></p>
                      </div>  
                      <div class="pd-l-10 pd-t-10 d-flex  align-items-center">
                        <img src="img/pageprofile/email1.png" > <p class="tx-info mg-l-20">Email: <br> <?php echo $usuario["data"]["email"]; ?></p>
                      </div>  
                      <div class="pd-l-10 pd-t-10 d-flex  align-items-center">
                        <img src="img/pageprofile/mapa.png" > <p class="tx-info mg-l-20">Dirección: <br> <?php echo $usuario["data"]["calle"] . ', '  ?></p>
                      </div>
                    </div>  
                  </div> 
                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
                    <!--Incio QR -->
                      <div class="card shadow-base  pd-10 bd-10 mg-t-10" id="qrcode" >
                        <div class="card bd-20" style=" box-sizing: border-box;">
                          <!-- aqui va la cadena de texto que contiene la imagen qr -->
                          <?php
                          date_default_timezone_set('America/Mexico_City');
                          $nom = $usuario['data']['nombre'];
                          $app = $usuario['data']['apaterno'];
                          $nom = str_replace(
                            array('Á', 'á'),
                            array('A', 'a'),
                            $nom
                          );
                          $app = str_replace(
                            array('Á', 'á'),
                            array('A', 'a'),
                            $app
                          );
                          $nom = str_replace(
                            array('É', 'é'),
                            array('E', 'e'),
                            $nom
                          );
                          $app = str_replace(
                            array('É', 'é'),
                            array('E', 'e'),
                            $app
                          );
                          $nom = str_replace(
                            array('Í', 'í'),
                            array('I', 'i'),
                            $nom
                          );
                          $app = str_replace(
                            array('Í', 'í'),
                            array('I', 'i'),
                            $app
                          );
                          $nom = str_replace(
                            array('Ó', 'ó'),
                            array('O', 'o'),
                            $nom
                          );
                          $app = str_replace(
                            array('Ó', 'ó'),
                            array('O', 'o'),
                            $app
                          );
                          $nom = str_replace(
                            array('Ú', 'ú'),
                            array('U', 'u'),
                            $nom
                          );
                          $app = str_replace(
                            array('Ú', 'ú'),
                            array('U', 'u'),
                            $app
                          );
                          $nom = str_replace(
                            array('Ñ', 'ñ'),
                            array('N', 'n'),
                            $nom
                          );
                          $app = str_replace(
                            array('Ñ', 'ñ'),
                            array('N', 'n'),
                            $app
                          );
                          //var_dump($nom);
                          //var_dump($app);
                          $contenQr = "MECARD:N:{$nom} {$app}; TEL:{$usuario["data"]["celular"]}; EMAIL:{$usuario["data"]["email"]};;";
                          ?>
                          <input id="text" type="hidden" value="<?php echo ($contenQr) ?>" />
                          <div class="img_credencial" id="qrcode">
                          </div>
                        </div><!-- card -->
                      </div>
                    <!--Fin QR -->
                  </div> 
                </div> 
            </div>
            <!--Fin datos -->  
          </div>               
        </div>
        <div class="tab-pane fade" id="verificadocs">
          <div class="card p-3 mt-1 mb-3 text-justify">
            <span><b>VERIFICA TU PERFIL CONACON</b> La verificación del perfil CONACON te otorgará diferentes ventajas, adicionales, solo debes enviar a revision los
              siguientes documentos y listo, ¡Podrás obtener tu perfil verificado!
            </span>
          </div>
          <div class="contenetform" style="text-align: center; ">
            <form id="archivosverifica">
              <!-- <div class="mb-3">
                <label for="formFile" class="form-label">Identificación Reverso (Copia del INE, Credencial de ELector)</label>
                <div class="flex justify-center items-center w-full">
                  <label for="dropzone-file" class="flex flex-col justify-center items-center w-full h-64 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col justify-center items-center pt-5 pb-6">
                      <i class="fa fa-tasks fa-3x"></i>
                      <p id="identificacion_reversoseleccion" class=" mb-2 text-sm text-gray-400 dark:text-gray-400"><span class="font-semibold">Selecciona tu documento</span></p>
                      <p class=" identificacion_reverso mb-2 text-sm text-gray-400 dark:text-gray-400 d-none"><span class="font-semibold">Ya haz enviado este documento a revisión</span></p>
                    </div>
                    <input name="identificacion_reverso" type="file" id="identificacion_reverso" oninput="fcargar()">
                  </label>
                </div>-->
              
            </form>
          </div>
        </div>
                       
        <div class="tab-pane fade" id="reconocimientos" >
        <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="media-list bg-white rounded shadow-base">
                  <div class="media pd-20 pd-xs-30">
                    <div class="container-fluid">
                      <!-- <div class="col-lg-12 col-sm-12 text-center mg-t-25 mt-0"> -->
                        <!-- <div class="bg-white rounded shadow-base overflow-hidden">
                          <div class="pd-x-20 pd-t-20 d-flex align-items-center">
                            <i class="fa fa-graduation-cap tx-80 lh-0 tx-teal op-5"></i>
                            <div class="mg-l-20">
                              <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase mg-b-10">% Unique Visits</p>
                              <p class="tx-32 tx-inverse tx-lato tx-black mg-b-0 lh-1">54.45%</p>
                              <span class="tx-12 tx-roboto tx-gray-600">23% average duration</span>
                            </div>
                          </div> -->
                          <!-- <div class="row">
                            <div class="col-sm-12 col-md-6 mx">
                              <i class="fa fa-graduation-cap tx-80 lh-0 tx-teal op-5"></i>
                            </div>

                          </div>
                        </div>
                      </div>
                      <div id="listar" class="row mg-t-20 ">

                      </div>  -->
                      <h3>Grados académicos</h3>
                      <div class="" id="listar_grado">
                        
                        </div>
                        
                        <div>
                        <h3>Constancias</h3>
                        <div class="row" id="listar">
                          
                        </div>
                      </div>
                    </div><!-- media-body -->
                  </div><!-- media -->
                </div><!-- card -->
              </div><!-- col-lg-8 -->
            </div><!-- row -->
        </div><!-- tab-pane -->

      </div><!-- br-pagebody -->
      <?php require 'plantilla/footer.php'; ?>
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
      <script src="script/page-profile.js"></script>
</html>
