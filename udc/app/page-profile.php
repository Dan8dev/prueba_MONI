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
if (isset($_SESSION["alumno"])) {
  $usr = $_SESSION['alumno'];

  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $usuario=$afiliados->obtenerusuario($idusuario);


}else{
  header("Location: index.php");
}

$clave_compartir = strtolower($usuario['data']['nombre']."_".$usuario['data']['apaterno']."_".$usuario['data']['amaterno']."_".$usuario['data']['idAsistente']);


// $clave_compartir = replaceAccents($clave_compartir);

$usuario['data']['codigo_compartir'] = $clave_compartir;

$ancho = "";
$Largo = "";
if(file_exists("img/afiliados/{$usuario['data']['foto']}")){
  $tamano = getimagesize("img/afiliados/{$usuario['data']['foto']}");
    json_encode($tamano);
    // var_dump($tamano[0]);
    // var_dump($tamano[1]);
    $ancho = 40;
    $Largo = 40;

    $CentroX = 35;
    $CentroY = 45;

    if($tamano[0]>=$tamano[1]){
      $Proporcion = (100/$tamano[0])*$tamano[1];
      $ancho = 100;
      $largo = $Proporcion;
    }else{
      $Proporcion = (100/$tamano[1])*$tamano[0];
      $largo = 100;
      $ancho = $Proporcion;
    }

    if($ancho == 100){
      $ancho = 40;
      $largo = (40/100)*$largo;
      $CentroY = (140/2) - ($largo/2);
    }else{
      $largo = 40;
      $ancho = (40/100)*$ancho;
      $CentroX = (107/2) - ($ancho/2);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:url" content="https://www.facebook.com/udconde/">
    <meta property="og:title" content="Universidad del Conde">
    <meta property="og:description" content="En Universidad del Conde, día con día nos damos a la tarea de sembrar en nuestros alumnos un interés genuino por influir de forma positiva en el mundo, por ser el agente de cambio que nuestro México necesita y trabajando todos los días sin quitar la vista de nuestro objetivo.">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="Universidad del Conde" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://moni.com.mx/UDC" />
    <meta property="og:image" content="https://moni.com.mx/consejo/app/img/logoMetas.png" />
    <meta property="og:description" content="" />
    <!-- Meta -->
    <meta name="description" content="Universidad del Conde">
    <meta name="author" content="Universidad del Conde Desarrollo Tecnológico">

    <title>Universidad del Conde</title>

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

    <div class="br-mainpanel br-profile-page">
      <div class="card shadow-base bd-0 rounded-0 widget-4">
        <div class="card-header ht-75">
          <div class="hidden-xs-down">
            <!--<a href="" class="mg-r-10"><span class="tx-medium">498</span> Followers</a>
              <a href=""><span class="tx-medium">498</span> Following</a>-->
            </div>
            <div class="tx-24 hidden-xs-down">
              <!--<a href="" class="mg-r-10"><i class="icon ion-ios-email-outline"></i></a>
              <a href=""><i class="icon ion-more"></i></a>-->
            </div>
          </div><!-- card-header -->

          <div class="card-body">
              <?php $foto = (file_exists("img/afiliados/".$usuario['data']['foto']) ? "img/afiliados/".$usuario['data']['foto'] : 'https://conacon.org/moni/siscon/img/default.jpg'); ?>
              <img src="<?php echo $foto;?>" width= "<?php echo $ancho;?>" height="<?php echo $largo;?>" id = "foto-Perfil" class= "d-none">
              <center>
                <canvas id="canv" width ="px" height ="px" style ="border-radius:10%;"></canvas>
              </center>
              
            <h4 class="tx-normal tx-roboto tx-white"><?php echo($usuario["data"]["nombre"]." ".$usuario["data"]["apaterno"]." ".$usuario["data"]["amaterno"]); ?></h4>
            <!-- <p class="mg-b-25">OPERADOR TERAPÉUTICO EN ADICCIONES</p> -->
            <!--<p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25">primero</p>-->
            <p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25 tx-white mb-2">SMART ID</p>
            <img class="mb-3" src="img/conacon99x67.png">
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
			  <!--<a href="../cv/<?=$usuario['data']['plantillapp']?>/?perfil=<?php echo $usuario['data']['id_afiliado']; ?>" class="tx-white-8" target="_blank">
                <i class="fa fa-eye"></i>
              </a>-->
            </p>
          </div><!-- card-body -->
        </div>
        <input type="text" id="LargoFoto" value ="<?php echo $largo;?>" class= "d-none">
        <input type="text" id="AnchoFoto" value ="<?php echo $ancho;?>" class= "d-none">
       
        <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
          <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#posts" role="tab">CONTACTO</a></li>
            <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reconocimientos" role="tab">Formación Académica</a></li> -->
          </ul>
        </div>

        <div class="tab-content br-profile-body">
          <div class="tab-pane fade active show" id="posts">


               <div class="card pd-20 pd-xs-30 shadow-base bd-0">
              <div class="row">

                <div class="col-sm-9 mg-t-30 mg-lg-t-0">
                  
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Datos</h6>
                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Tel:</label>
                    <p class="tx-info mg-b-25"> <?php echo $usuario["data"]["celular"]; ?></p>
                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Email</label>
                    <p id="emailobtener" class="tx-inverse mg-b-25"><a mailto="contacto@tsuconsejeria.com"> <?php echo $usuario["data"]["email"]; ?></a></p>
                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Dirección</label>
                    <p class="tx-inverse mg-b-25"> <?php echo $usuario["data"]["calle"].', '.$usuario["data"]["colonia"].', '.$usuario["data"]["ciudad"].', '.$usuario["data"]["estado_nom"].', '.$usuario["data"]["pais_nom"]?></p>
                
                </div><!-- col-lg-4 -->
                <div class="col-sm-3">
                
                            <div class="card shadow-base  pd-25 bd-0 mg-t-20">
                              <div class="card bd-0" id="qrcode">
                                <!-- aqui va la cadena de texto que contiene la imagen qr -->
                                <?php 
                                date_default_timezone_set('America/Mexico_City');
                                $nom=$usuario['data']['nombre'];
                                $app=$usuario['data']['apaterno'];
                                $nom = str_replace(
                                  array('Á','á'),
                                  array('A','a'),
                                  $nom
                                );
                                $app = str_replace(
                                  array('Á','á'),
                                  array('A','a'),
                                  $app
                                );
                                $nom = str_replace(
                                  array('É','é'),
                                  array('E','e'),
                                  $nom
                                );
                                $app = str_replace(
                                  array('É','é'),
                                  array('E','e'),
                                  $app
                                );
                                $nom = str_replace(
                                  array('Í','í'),
                                  array('I','i'),
                                  $nom
                                );
                                $app = str_replace(
                                  array('Í','í'),
                                  array('I','i'),
                                  $app
                                );
                                $nom = str_replace(
                                  array('Ó','ó'),
                                  array('O','o'),
                                  $nom
                                );
                                $app = str_replace(
                                  array('Ó','ó'),
                                  array('O','o'),
                                  $app
                                );
                                $nom = str_replace(
                                  array('Ú','ú'),
                                  array('U','u'),
                                  $nom
                                );
                                $app = str_replace(
                                  array('Ú','ú'),
                                  array('U','u'),
                                  $app
                                );
                                $nom = str_replace(
                                  array('Ñ','ñ'),
                                  array('N','n'),
                                  $nom
                                );
                                $app = str_replace(
                                  array('Ñ','ñ'),
                                  array('N','n'),
                                  $app
                                );
                                //var_dump($nom);
                                //var_dump($app);
                                $contenQr = "MECARD:N:{$nom} {$app}; TEL:{$usuario["data"]["celular"]}; EMAIL:{$usuario["data"]["email"]};;";
                                ?>
                                <input  id="text" type="hidden" value="<?php echo($contenQr) ?>"  />
                                <div class="img_credencial" id="qrcode"></div>
                              </div><!-- card -->
                            </div><!-- card -->
                </div><!-- col-lg-8 -->
              </div>
              
              <!--<div class ="row">
                <div class="col text-right">
                  <button class="btn btn-primary form-control">Solicitar Credencial</button>
                </div>
              </div>-->
              <!-- row -->
            </div>
          </div><!-- tab-pane -->

          <div class="tab-pane fade" id="reconocimientos">
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
                        
                        <div class="row">
                        <h3>Constancias</h3>
                        <div class="col-sm-12" id="listar">
                          
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
      <script src="script/pageprofile.js"></script>
    </body>
    </html>

