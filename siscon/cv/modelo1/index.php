<?php 
  if(isset($_GET['perfil'])){
    require "../../app/data/Model/AfiliadosModel.php";
    $afilM = new Afiliados();

    $persona = $afilM->obtenerusuario($_GET['perfil']);
    if($persona['data']){
      $persona = $persona['data'];

      $persona['experiencia'] = $afilM->consultar_exp_laboral(['afiliado'=>$persona['id_afiliado']])['data'];
      $persona['conocimiento'] = $afilM->consultar_conocimiento(['afiliado'=>$persona['id_afiliado']])['data'];
      $persona['grados'] = $afilM->consultar_grado(['afiliado'=>$persona['id_afiliado']])['data'];
	  if($persona['fnacimiento'] != ""){		  
		  $dia_nacim = explode('-',$persona['fnacimiento']);
		  $edad_num = intval(date("Y")) - intval($dia_nacim[0]);
		  $edad = ((intval($dia_nacim[2]) <= intval(date('d'))) && (intval($dia_nacim[1]) <= intval(date("m"))))? $edad_num: $edad_num-1;
	  }else{
		  $edad = "";
	  }
    }else{
	    $persona['nombre'] = "";
        $persona['apaterno'] = "";
        $persona['amaterno'] = "";
        $persona['estado'] = "";
        $persona['email'] = "";
        $persona['celular'] = "";
        $persona['facebook'] = "";
        $persona['instagram'] = "";
        $persona['twitter'] = "";
        $persona['experiencia'] = [];
        $persona['conocimiento'] = [];
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/favicon.png" rel="apple-touch-icon">

  <title><?=$persona['nombre'].' '.$persona['apaterno']?> | Consejero Afiliado CONACON</title>

  <link rel="stylesheet" href="assets/css/bootstrap.css">  
  <link rel="stylesheet" href="assets/css/maicons.css">
  <link rel="stylesheet" href="assets/vendor/animate/animate.css">
  <link rel="stylesheet" href="assets/vendor/owl-carousel/css/owl.carousel.css">
  <link rel="stylesheet" href="assets/vendor/fancybox/css/jquery.fancybox.css">
  <link rel="stylesheet" href="assets/css/theme.css">

</head>
<body>

  <!-- Back to top button -->
  <div class="back-to-top"></div>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #325578;">
      <div class="container">
        <a href="#" class="navbar-brand"><img src="img/Logo-Conacon-Positivo.png" width="250px"></a>

        <button style="background-color: #4479ac;" class="navbar-toggler" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="navbarContent">
          <ul class="navbar-nav ml-auto pt-3 pt-lg-0">
            <li class="nav-item">
              <a href="#" class="nav-link">INICIO</a>
            </li>
            <li class="nav-item">
              <a href="#grados" class="nav-link">GRADOS ACAD??MICOS</a>
            </li>
            <li class="nav-item">
              <a href="#laboral" class="nav-link">TRAYECTORIA LABORAL</a>
            </li>
            <li class="nav-item">
              <a href="#contacto" class="nav-link">CONTACTO</a>
            </li>
          </ul>
        </div>
      </div> <!-- .container -->
    </nav> <!-- .navbar -->
    <div><h3 style="background-color: #4478ad; width:100%; COLOR: white; text-align: center; padding: 20px;">CONSEJERO AFILIADO</h3></div>
    </div> <!-- .page-banner -->
  </header>

  <main>
    <div class="page-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 py-3">
            <?php $foto = file_exists('../../app/img/afiliados/'.$persona['foto']) ? '../../app/img/afiliados/'.$persona['foto'] : '../../app/img/afiliados/defaultfoto.jpg'; ?>
            <div class="subhead"><img src="<?= $foto; ?>" width="80%" style="border-radius: 15px;"></div>
          </div>
          <div class="col-lg-6" >
            
              <h2 style="background-color: #325578; text-transform: uppercase; color: white; border-radius: 10px; font-size:xx-large; padding: 15px;" ><b><?=$persona['nombre'].' '.$persona['apaterno']?></b></h2>
              <p style="text-align: justify;">T???e doy la m??s cordial bienvenida a mi p??gina. Para mi es de suma
                importancia incluirte en todo lo que aqu?? comunico y que seas parte
                activa de este espacio y con esto formemos una red profesional robusta,
                para generar conocimiento juntos, que nos sirva para aportar de manera
                m??s contundente al mundo de la salud mental y la prevenci??n de las
                Conductas Antisociales.
                Necesitamos generar una comunicaci??n productiva, en donde tu
                retroalimentaci??n y la m??a sean parte de una comunicaci??n efectiva y
                eficiente, de beneficio para toda esta gran red profesional que estamos
                configurando y sobre todo de beneficio para la sociedad.
                <span style="color: #4478ad;"><b><br><br>Gracias nuevamente por tu presencia.
                ??Bienvenido!</span></b></span></p>              
            </div>
          </div>
        </div>
      </div>
    </div> <!-- .page-section -->

    <div><h3 style="background-color: #859fb6; width:100%; COLOR: white; text-align: center; padding: 20px;"></h3></div>

    <div id="grados" class="page-section">
      <div class="container">
        <h2 style="color: #4478ad;"><b><u>Grados Acad??micos</u></b></h2>
            <div class="row justify-content-left">
                <?php
                    $back[0] = "#ededed"; $back[1] = "#325578"; 
                    $title[0] = "#4478ad"; $title[1] = "white";
                    $text[0] = "black"; $text[1] = "#98bbdd";
                    $filas = 1; $celdas = 1;

                    for ($i=0; $i < sizeof($persona['grados']); $i++){ 
                        $l_g = $persona['grados'][$i];?>
                        <div class="col-md-6 col-lg-4 col-xl-6 py-3 mb-3">
                            <div class="text-center" style="background-color: <?=$back[$i%2]?>; height: 100%; border-radius: 10px; padding: 15px; box-shadow: 5px 5px 15px gray;">
                                <h5 style="color: <?=$title[$i%2]?>; text-align:left;"><b><?=$l_g['grado']?></b></h5>
                                <p style="color: <?=$text[$i%2]?>; text-align:left;"><?=$l_g['titulo'];?></p>
                            </div>
                        </div>
                <?php
                    $celdas++;
                    if( $celdas > 2 ){ $celdas = 1; $filas++; }
                    if( $filas%2 == 0 ){ $back[0] = "#325578"; $back[1] = "#ededed"; $title[0] = "white"; $title[1] = "#4478ad";  $text[0] = "#98bbdd"; $text[1] = "black";}
                    else{ $back[0] = "#ededed"; $back[1] = "#325578"; $title[0] = "#4478ad"; $title[1] = "white"; $text[0] = "black"; $text[1] = "#98bbdd";} 
                }?>
            </div>
      </div> <!-- .container -->
    </div> <!-- .page-section -->

    <hr>

    <div id="laboral" class="page-section">
      <div class="container">
            <h2 style="color: #4478ad;"><b><u>Trayectoria Laboral</u></b></h2>
            <div class="row justify-content-left">
                <?php 
                    $back[0] = "#ededed"; $back[1] = "#325578"; 
                    $title[0] = "#4478ad"; $title[1] = "white";
                    $text[0] = "black"; $text[1] = "#98bbdd";
                    $filas = 1; $celdas = 1;

                    for ($i=0; $i < sizeof($persona['experiencia']); $i++){ 
                        $l_e = $persona['experiencia'][$i];?>
                        <div class="col-md-6 col-lg-4 col-xl-6 py-3 mb-3">
                            <div class="text-center" style="background-color: <?=$back[$i%2]?>; height: 100%; border-radius: 10px; padding: 15px; box-shadow: 5px 5px 15px gray;">
                                <h5 style="color: <?=$title[$i%2]?>; text-align:left;"><b><?=$l_e['empresa'];?></b></h5>
                                <p style="color: <?=$text[$i%2]?>; text-align:left;"><?=$l_e['puesto'];?><br><?=substr($l_e['fechaIngreso'], 8,2).'/'.substr($l_e['fechaIngreso'], 5,2).'/'.substr($l_e['fechaIngreso'], 0,4).''.substr($l_e['fechaIngreso'], 11,8);?></p>
                            </div>
                        </div>
                <?php 
                        $celdas++;
                        if( $celdas > 2 ){ $celdas = 1; $filas++; }
                        if( $filas%2 == 0 ){ $back[0] = "#325578"; $back[1] = "#ededed"; $title[0] = "white"; $title[1] = "#4478ad";  $text[0] = "#98bbdd"; $text[1] = "black";}
                        else{ $back[0] = "#ededed"; $back[1] = "#325578"; $title[0] = "#4478ad"; $title[1] = "white"; $text[0] = "black"; $text[1] = "#98bbdd";}
                    }?>
            </div>
      </div> <!-- .container -->
    </div> <!-- .page-section -->
  </main>

  <footer id="contacto" class="page-footer" style="background-color: #f5f5f5; color:black;">
    <div class="container">
    <h2 style="color: #4478ad;"><b><u>CONTACTO</u></b></h2>
      <div class="row">
        <div class="col-lg-3 py-3">
          <span style="color:#4478ad"><img src="img/tel.png" width="20px"> <b>Tel??fono:</b><br><?=number_format( $persona['celular'], 0, ' ',' ')?></span><br><br>          
          <span style="color:#4478ad"><b>Redes sociales:</b><br></span>
          <?php if($persona['facebook'] != ''):?>
          <a href="<?=$persona['facebook']?>" target="_blank"><img src="img/facebook.png" width="16px"></a>
          <?php endif; ?>
          <?php if($persona['twitter'] != ''):?>
          <a href="<?=$persona['twitter']?>" target="_blank"><img src="img/twitter.png" width="36px"></a>
          <?php endif; ?>
          <?php if($persona['instagram'] != ''):?>
          <a href="<?=$persona['instagram']?>" target="_blank"><img src="img/instagram.png" width="30px"></a>
          <?php endif; ?>
        </div>
        <div class="col-lg-3 py-3">
            <span style="color:#4478ad"><img src="img/mail.png" width="28px"> <b>Email:</b><br><?=$persona['email']?></span><br><br>          
            <span style="color:#4478ad"><img src="img/map.png" width="22px"> <b>Direcci??n:</b><br><?=$persona['estado_nom']?></span><br><br>          
        </div>
        <div class="col-lg-6 py-3">
          <form onsubmit="javascript:document.getElementById('btns').disabled=true; document.getElementById('btns').textContent='Enviando...';" oninput="javascript:document.getElementById('btns').disabled=false;" action="contact.php?email=<?=$persona['email']?>&perfil=<?=$_GET['perfil']?>" method="POST">
              Nombre: <br>
              <input name="nombreF" type="text" style="width:100%; border-radius: 7px; border-color:white;" required></input><br>
              Email: <br>
              <input name="emailF" type="email" style="width:100%; border-radius: 7px; border-color:white;" required></input><br>
              Mensaje: <br>
              <input name="msgF" type="text" style="width:100%; border-radius: 7px; border-color:white;" required></input><br>
              <input name="paraF" type="hidden" value="<?=$persona['email']?>"></input>
            <button id="btns" type="submit" class="btn btn-primary btn-sm mt-2" disabled>Enviar</button>
          </form>
        </div>
      </div>

    </div>
  </footer>

<div style="background-color: #325578; color:white; width:100%; padding:20px; text-align:center;">COPYRIGHT &copy; CONACON</div>
  
<script src="assets/js/jquery-3.5.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/owl-carousel/js/owl.carousel.min.js"></script>
<script src="assets/vendor/wow/wow.min.js"></script>
<script src="assets/vendor/fancybox/js/jquery.fancybox.min.js"></script>
<script src="assets/vendor/isotope/isotope.pkgd.min.js"></script>
<script src="assets/js/google-maps.js"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>

<script>
    function enviar_mensaje( correo ){

        nombre = document.getElementById('nombre').value;
        email = document.getElementById('email').value;
        msg = document.getElementById('msg').value;

        Data = {
            action: 'enviar_mensaje',
            nombreF: nombre,
            emailF: email,
            paraF: correo,
            msgF: msg
        }

        $.ajax({
      url: 'contact.php',
      type: 'POST',
      data: Data,
      success: function(data){
          if(data == 'no_session'){
              swal({
                  title: "Vuelve a iniciar sesi??n!",
                  text: "La informacion no se carg??",
                  icon: "info",
              });
              setTimeout(function(){
                  window.location.replace("index.php");
              }, 2000);
          }
          try{
            alert( "Gracias "+nombre + ", su mensaje ha sido enviado.");         
          }catch(e){
              console.log(e)
              console.log(data)
          }
      },
      error : function(){

      },
      complete : function(){
          $(".outerDiv_S").css("display","none")
      }
  });   

}//fin enviar_mensaje

</script>