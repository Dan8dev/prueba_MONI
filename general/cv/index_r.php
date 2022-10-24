<?php 
  if(isset($_GET['perfil'])){
    require "../app/data/Model/AfiliadosModel.php";
    $afilM = new Afiliados();

    $persona = $afilM->obtenerusuario($_GET['perfil']);
    if($persona['data']){
      $persona = $persona['data'];
      echo "<pre>";
      print_r($persona);
      echo "</pre>";
      $dia_nacim = explode('-',$persona['fnacimiento']);
      $edad_num = intval(date("Y")) - intval($dia_nacim[0]);
      $edad = ((intval($dia_nacim[2]) <= intval(date('d'))) && (intval($dia_nacim[1]) <= intval(date("m"))))? $edad_num: $edad_num-1;

      echo "[EDAD: {$edad}]";
    }else{

    }
  }else{

  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" lang="es">
  <meta charset="utf-8">
  <link href="style.css" rel="stylesheet">
  <script src="script.js"></script>
</head>
<body>


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
  
  <div>
    <span class="titulo1">
      <img src="avatar.jpg" alt="Avatar" class="avatar" align="left"> <?php echo $persona['nombre']; ?></span><br>
      CONSEJERO AFILIADO.
    </div>  

    <div class="container" align="center">
      <img src="qr.png" width="200px" >
    </div>

    <p>
      <img src="icons/User-Profile-256.png" align="left" width="30px">
      &nbsp;Edad: <b></b><br>
      &nbsp;Estado: <b>Puebla</b><br><br>

      <img src="icons/Message-Mail-256.png" align="left" width="30px">
      &nbsp;Correo:<br>
      <b>&nbsp;sistemas@gmail.com</b><br><br>

      <img src="icons/IPad-256.png" align="left" width="30px">
      &nbsp;Teléfono:<br>
      <b>&nbsp;222 345 23 23</b><br><br>

      <img src="icons/Cursor-256 (1).png" align="left" width="30px">
      &nbsp;Sitio web:<br>
      <b>&nbsp;www.universidaddelconde.edu</b>

      <div>
       Redes Sociales:<br>
       <a href="#"><img src="icons/Facebook-26.png" width="20px"></a>
       <a href="#"><img src="icons/instagram.png" width="20px"></a>
       <a href="#"><img src="icons/Twitter-Bird-26.png" width="20px"></a>
       <BR><br>
     </div>

     <div class="titulo2">TRAYECTORIA PERSONAL</div>
     <div class="card3">      
      <div class="year">2021</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div> 

    <div class="titulo2">TRAYECTORIA PERSONAL</div>
    <div class="card3">      
      <div class="year">2020</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div> 

    <div class="titulo2">TRAYECTORIA PERSONAL</div>
    <div class="card3">      
      <div class="year">2019</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div> 

    <div class="divisor"></div>

    <div class="titulo2">CAPACITACIÓN ADQUIRIDA</div>
    <div class="card3">      
      <div class="yearsub">2019</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div> 

    <div class="divisor"></div>

    <div class="titulo2">PARTICIPACIONES</div>
    <div class="card3">      
      <div class="yearsub">2019</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div> 

    <div class="divisor"></div>

    <div class="titulo2">ARTÍCULOS PUBLICADOS</div>
    <div class="card3">      
      <div class="yearsub">2019</div>
      <p><img src="icons/User-Profile-256-2.png" align="left" width="30px"><span class="titulo2"> Líder de proyecto</span></p>
      <p><img src="icons/Office-01-256.png" align="left" width="30px"><span class="titulo2"> Microsoft Corporation</span></p>
      <p>México requiere hoy de empresarios, trabajadores, docentes y servidores públicos más competentes para enfrentar los desafíos que el mercado globalizado impone. La Universidad del Conde, a través de El Sistema Nacional de Competencias, facilita los mecanismos para que organizaciones e instituciones públicas y privadas cuenten con personas más competentes.</p>
    </div> 

  </p>

  <span class="titulo2">CONTACTO</span>
  <div class="card3">
    <form action="/action_page.php">
      <input type="text" id="fname" name="firstname" placeholder="Nombre">
      <input type="text" id="lname" name="lastname" placeholder="Apellidos">
      <input type="text" id="email" name="email" placeholder="Email">
      <textarea id="subject" name="subject" placeholder="Mensaje" style="height:200px"></textarea>
      <input type="submit" value="Enviar mensaje">
    </form>
  </div>

  <div class="footer">
    <span class="footer_t">CONACON</span>
    <a href="#"><img src="icons/Facebook-26-2.png" width="20px"></a>
    <a href="#"><img src="icons/instagram2.png" width="20px"></a>
    <a href="#"><img src="icons/Twitter-Bird-256-2.png" width="20px"></a>
  </div>

</body>
</html> 
