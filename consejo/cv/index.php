<?php 
  if(isset($_GET['perfil'])){
    require "../app/data/Model/AfiliadosModel.php";
    $afilM = new Afiliados();

    $persona = $afilM->obtenerusuario($_GET['perfil']);
    // print_r($persona);
    if($persona['data']){
      $persona = $persona['data'];

      $persona['experiencia'] = $afilM->consultar_exp_laboral(['afiliado'=>$persona['id_afiliado']])['data'];
      $persona['conocimiento'] = $afilM->consultar_conocimiento(['afiliado'=>$persona['id_afiliado']])['data'];
      $persona['grados'] = $afilM->consultar_grado(['afiliado'=>$persona['id_afiliado']])['data'];
      // echo "<pre>";
      // print_r($persona);
      // echo "</pre>";
	  if($persona['fnacimiento'] != ""){		  
		  $dia_nacim = explode('-',$persona['fnacimiento']);
		  $edad_num = intval(date("Y")) - intval($dia_nacim[0]);
		  $edad = ((intval($dia_nacim[2]) <= intval(date('d'))) && (intval($dia_nacim[1]) <= intval(date("m"))))? $edad_num: $edad_num-1;
	  }else{
		  $edad = "";
	  }
      // echo "[EDAD: {$edad}]";
    }else{
		$persona['nombre'] = "";
      $persona['apaterno'] = "";
      $persona['estado'] = "";
      $persona['email'] = "";
      $persona['celular'] = "";
      $persona['facebook'] = "";
      $persona['instagram'] = "";
      $persona['twitter'] = "";
      $persona['experiencia'] = [];
      $persona['conocimiento'] = [];
    }
  }else{

  }
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">


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
<meta property="og:image" content="https://conacon.org/moni/siscon/app/img/logoMetas.png">
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


<link href="style.css" rel="stylesheet">
<script src="script.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

</head>
<body>


  <!--MENU LATERAL-->
  <div id="mySidepanel" class="sidepanel">
    <div align="center"><img src="img/logo_conacon.png" width="50%"><br><br></div>
    <a href="javascript:void(0)" class="closebtn" style="background-color: transparent;" onclick="closeNav()">×</a>
    <a href="#"><img src="icons/House-256.png" width="20px"> Presentación</a>
    <a href="#"><img src="icons/Identity-Card-256.png" width="20px"> Trayectoria Personal</a>
    <a href="#"><img src="icons/Gradute-Degree-256 (1).png" width="20px"> Trayectoria laboral</a>
    <a href="#"><img src="icons/Note-01-256 (1).png" width="20px"> Participaciones</a>
    <a href="#"><img src="icons/Chat-256.png" width="20px"> Artículos publicados</a>

    
    <div align="center">
      <span class="lpanel" onclick="javacript:window.open('https://www.facebook.com/ColegioConacon/');"><img src="icons/Facebook-26-2.png" width="20px"></span>
      <span class="lpanel" onclick="javacript:window.open('https://www.instagram.com/colegioconacon');"><img src="icons/instagram2.png" width="20px"></span>
      <span class="lpanel" onclick="javacript:window.open('https://twitter.com/ColegioConacon');"><img src="icons/Twitter-Bird-256-2.png" width="20px"></span>
    </div>

  </div>

  <button class="openbtn" onclick="openNav()">☰</button><br><br>

<div class="container">
  
  <!--NOMBRE-->
  <div>
  <span class="titulo1">
  <img src="https://conacon.org/moni/siscon/app/img/afiliados/<?php echo $persona['foto']; ?>" alt="Avatar" class="avatar" align="left"><?php echo $persona['nombre'].' '.$persona['apaterno']; ?></span><br>
  CONSEJERO AFILIADO.
  </div>  

  <div class="row">      
     <div class="col-3">&nbsp;
     </div>      
     <div class="col-3">&nbsp;
     </div>
   </div>
  

    <div class="row">
  
      <div class="col-sm">
        <!-- <div class="card3"> 
          <p align="center"><img src="qr.png" width="200px"></p>     
        </div> -->
        <div class="card shadow-base card-body pd-25 bd-0 mg-t-20 m-auto mt-5">
          <div class="bd-0 m-auto" id="qrcode">
            <!-- aqui va la cadena de texto que contiene la imagen qr -->
            <?php 
            date_default_timezone_set('America/Mexico_City');
            $contenQr = "MECARD:N:{$persona["nombre"]} {$persona["apaterno"]}; TEL:{$persona["celular"]}; EMAIL:{$persona["email"]};;";
            ?>
            <input  id="text" type="hidden" value="<?php echo($contenQr) ?>"  />
            <div class="img_credencial" id="qrcode"></div>
          </div><!-- card -->
        </div><!-- card -->
      </div>
    
      <div class="col-sm">
        <div class="card3">      
          <img src="icons/User-Profile-256.png" align="left" width="30px">
      &nbsp;Edad: <b><?php echo $edad; ?></b>&nbsp;Estado: <b><?php echo $persona['estado']; ?></b><br><br>
    
       <img src="icons/Message-Mail-256.png" align="left" width="30px">
       &nbsp;Correo: <b>&nbsp;<?php echo $persona['email'] ?></b><br><br>
    
       <img src="icons/IPad-256.png" align="left" width="30px">
       &nbsp;Teléfono: <b>&nbsp; <?php echo $persona['celular']; ?></b><br><br>
    
       <!--<img src="icons/Cursor-256 (1).png" align="left" width="30px">
       &nbsp;Sitio web: <b>&nbsp;conacon.org/moni/siscon/cv/?perfil=<?php echo $_GET['perfil']; ?></b><br><br>-->
        <div class="row">
          <div class="col-6">
			<?php if($persona["facebook"] != '' || $persona["twitter"] != '' || $persona["instagram"] != ''): ?>
            Redes Sociales:
			<?php endif; ?>
          </div>
          <div class="col-6">
            <div class="row">
              <?php 
                if($persona["facebook"] != ''){
                  echo "<div class='col-3'><a href='{$persona['facebook']}' class='tx-white-8 mg-r-5'><img src='icons/Facebook-26.png' width='20px'></a></div>";
                }
                if($persona["twitter"] != ''){
                  echo "<div class='col-3'><a href='{$persona['twitter']}' class='tx-white-8 mg-r-5'><img src='icons/instagram.png' width='20px'></a></div>";
                }
                if($persona["instagram"] != ''){
                  echo "<div class='col-3'><a href='{$persona['instagram']}' class='tx-white-8 mg-r-5'><img src='icons/Twitter-Bird-26.png' width='20px'></a></div>";
                }
              ?>
			  <div class="col-3"><a href="#" width="20px">
				  <a href="#" id="a_shareCode" class="tx-white-8">
					<img src="icons/share_icon.jpeg" width="20px">
				  </a>
			  </div>
            </div>
          </div>
        </div>
          
          
          
          
        </div>
      </div>    

    </div>
<div class="row">
	<div class="card p-3 mt-1 mb-3 text-justify col-11 mx-auto">
	  &nbsp;&nbsp;Te doy la más cordial bienvenida a mi página. Para mi es de suma importancia incluirte en todo lo que aquí comunico y que seas parte activa de este espacio y con esto formemos una red profesional robusta,  para generar conocimiento juntos, que nos sirva para aportar de manera más contundente al mundo de la salud mental  y la prevención de las Conductas Antisociales.<br><br>
	  &nbsp;&nbsp;Necesitamos generar una  comunicación productiva, en donde tu retroalimentación y la mía sean parte de una comunicación efectiva y eficiente, de beneficio  para toda esta gran red profesional que estamos configurando y sobre todo de beneficio para la sociedad . <br><br>
	  Gracias nuevamente  por tu presencia. <br>&nbsp;¡Bienvenido!
	</div>
</div>
<div class="divisor"></div>

<!-- <div class="row">
  
  <div class="col-sm">
    <div class="titulo2">TRAYECTORIA PERSONAL 1</div>
    <div class="card3">      
      <div class="year">2021</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div>
  </div>

  <div class="col-sm">
    <div class="titulo2">TRAYECTORIA PERSONAL 2</div>
    <div class="card3">      
      <div class="year">2021</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div>
  </div>


</div> -->

<div class="row">
  <div class="titulo2">TRAYECTORIA PERSONAL</div>
  <?php 
    for ($i=0; $i < sizeof($persona['experiencia']); $i++) { 
      $l_e = $persona['experiencia'][$i];
  ?>
    <div class="col-sm-4">
      <div class="card3">      
        <div class="year"><?php echo explode('-',$l_e['fechaIngreso'])[0]; ?></div>
        <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> <?php echo $l_e['puesto']; ?></span></p>
        <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> <?php echo $l_e['empresa']; ?></span></p>
        <p><?php echo $l_e['activiadLaboral']; ?></p>
      </div>
    </div>
  <?php
    }
  ?>
  <!-- <div class="col-sm">
    <div class="card3">      
      <div class="year">2021</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div>
  </div>

  <div class="col-sm">
    <div class="titulo2">TRAYECTORIA PERSONAL 4</div>
    <div class="card3">      
      <div class="year">2021</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div>
  </div> -->


</div>

<div class="divisor"></div>

<div class="row">
  <div class="titulo2">GRADOS ACADEMICOS</div>
  <?php 
    for ($i=0; $i < sizeof($persona['grados']); $i++) { 
      $l_g = $persona['grados'][$i];
  ?>
    <div class="col-sm-4">
      <div class="card3">      
        <div class="year"></div>
        <p><img src="icons/birrete.png" align="left" width="30px"><span class="titulo2"> <?php echo $l_g['grado']; ?></span></p>
        <p><img src="icons/togas.png" align="left" width="30px"><span class="titulo2"> <?php echo $l_g['titulo']; ?></span></p>
        <p><?php echo $l_g['cedula']; ?></p>
      </div>
    </div>
  <?php
    }
  ?>
</div>

<div class="divisor"></div>

<div class="row">
  <div class="titulo2">CONOCIMIENTO COMPARTIDO</div>
  <?php 
    for ($i=0; $i < sizeof($persona['conocimiento']); $i++) { 
      $l_c = $persona['conocimiento'][$i];
  ?>
    <div class="col-sm-4">
      <div class="card3">      
        <div class="year"><?php echo explode('-',$l_c['fechaIngreso'])[0]; ?></div>
        <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> <?php echo $l_c['funcion']; ?></span></p>
        <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> <?php echo $l_c['nombreEvento']; ?></span></p>
        <p><?php echo $l_c['detalles']; ?></p>
      </div>
    </div>
  <?php
    }
  ?>
  <!-- <div class="col-sm">
    <div class="titulo2">ARTÍCULOS PUBLICADOS</div>
    <div class="card3">      
      <div class="yearsub">2019</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div> 
  </div> -->
</div>

<div class="divisor"></div>

<div class="row">
  <div class="col-sm-4">&nbsp;</div>
  <div class="col-sm-4">
    <span class="titulo2">CONTACTO</span>
<div class="card3">
  <form id="form-contacto">
    <input type="hidden" name="destino" value="<?php echo $persona['id_afiliado']; ?>">
    <input type="text" id="fname" name="firstname" placeholder="Nombre">
    <input type="text" id="lname" name="lastname" placeholder="Apellidos">
    <input type="text" id="email" name="email" placeholder="Email">
    <textarea id="subject" name="subject" placeholder="Mensaje" style="height:200px"></textarea>
    <input type="submit" value="Enviar mensaje">
  </form>
</div>
  </div>
  <div class="col-sm-4">&nbsp;</div>
</div>

<div class="footer">
  <span class="footer_t">CONACON</span>
  <a href="#"><img src="icons/Facebook-26-2.png" width="20px"></a>
  <a href="#"><img src="icons/instagram2.png" width="20px"></a>
  <a href="#"><img src="icons/Twitter-Bird-256-2.png" width="20px"></a>
</div>

</div>  

</body>
    <script src="../app/script/qrcode.js"></script>
    <script src="../app/script/qrcode.min.js"></script>
	<script src="../lib/jquery/jquery.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
          if(navigator.share){
            $("#a_shareCode").on("click", function(e){
              e.preventDefault();
              navigator.share({
                text: 'https://conacon.org/cv/?perfil=<?php echo $persona['id_afiliado']; ?>'
              })
            })
          }else{

            $("#a_shareCode").prop("href", "whatsapp://send?text=https://conacon.org/cv/?perfil=<?php echo $persona['id_afiliado']; ?>")
            $("#a_shareCode").attr("data-action", "share/whatsapp/share")
                    //href="whatsapp://send?text=<?php #echo($usuario["persona"]["codigo"]); ?>" 
                    //data-action="share/whatsapp/share"
          }
        });
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
              if (e.keyCode == 13){
                makeCode();
              }
            });
      form = document.getElementById('form-contacto');
      form.addEventListener('submit', function(e){
        e.preventDefault();

        const data = new FormData(form);
        data.append('action', 'contacto_persona')
        fetch('../app/data/CData/afiliadosControl.php', {
           method: 'POST',
           body: data
        })
        .then(function(response) {
           if(response.ok) {
               return response.text()
           } else {
               throw "Error en la llamada Ajax";
           }

        })
        .then(function(texto) {
			try{
				resp = JSON.parse(texto);
				if(resp["1"] == "Mensaje enviado"){
					alert('Su mensaje ha sido enviado satisfactoriamente.');
				}else{
					alert('Ha ocurrido un erro al enviar el mensaje, intente mas tarde.');
				}
				form.reset();
			}catch(e){
				console.log(e)
				console.log(texto);
			}
        })
        .catch(function(err) {
           console.log(err);
        });
      })
    </script>
</html> 
