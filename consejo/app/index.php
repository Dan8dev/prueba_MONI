  
<?php
session_start();
if (!isset($_SESSION['alumno'])) {
 ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>

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
	  
	  <link rel="apple-touch-icon" href="img/Icon-App-40x40@1x.png">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="CONACON">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

		<link rel="apple-touch-startup-image" href="/img/icon_1024.png">
      <!-- Meta -->
      
      <link rel="icon" type="imge/png" href="img/favicon.png">
      
      <meta name="description" content="Colegio nacional de consejeros. CONACON">
      <meta name="author" content="CONACON TI">

      <title>CONSEJO CERTIFICADOR</title>

      <!-- vendor css -->
      <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
      <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
      <link rel="manifest" href="./manifest.json">
      <script src="./script.js"></script>
      <script src="./sw.js"></script>

      <!-- Bracket CSS -->
      <link rel="stylesheet" href="../css/bracket.css">
      <link href="../css/alertas.css" rel="stylesheet" type="text/css">
    </head>
    <body>
      <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">
        <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
          <div class="tx-center mg-b-60">
            <img src="../img/logoT.png" width="100%" aling="center">
          </div>
          <?php if (isset($_GET['user']) && isset($_GET['psw'])) { ?>
          <form id="formlogin">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Email" name="usr_name" id="usr_name" autofocus value="<?php echo $_GET['user']; ?>">
            </div>
            <div class="form-group">
              <div class="input-group">
                <input type="password" class="form-control" placeholder="Contraseña" name="usr_pass" id="usr_pass" value="<?php echo $_GET['psw']; ?>">
                <div id="vercontrasena" class="input-group-addon">
                  <i id="ocultareye" class="fa fa-eye-slash" aria-hidden="true"></i>
                </div>
              </div>
            </div>
            <div class="form-group">
              <a id="mostrarrecuperar" href="#" class="tx-info tx-12 d-block mg-t-10">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="btn btn-info btn-block">Entrar</button>
          </form>
          <form id="recuperarpasw" style="display:none">
            <div class="form-group">
              <label for="usr_name_recuperar">Restaurar contraseña</label>
              <input type="text" class="form-control" placeholder="Ingresa tu email" name="usr_name_recuperar" id="usr_name_recuperar" autofocus>
            </div>
            <div class="form-group">
              <a id="mostrarlogin" href="#" class="tx-info tx-12 d-block mg-t-10">Iniciar sesión</a>
            </div>
            <button type="submit" class="btn btn-info btn-block">Recuperar</button>
          </form>
          <?php } else { ?>
          <form id="formlogin">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Email" name="usr_name" id="usr_name" autofocus>
            </div>
            <div class="form-group">
              <div class="input-group">
                <input type="password" class="form-control" placeholder="Contraseña" name="usr_pass" id="usr_pass">
                <div id="vercontrasena" class="input-group-addon">
                  <i id="ocultareye" class="fa fa-eye-slash" aria-hidden="true"></i>
                </div>
              </div>
            </div>
            <div class="form-group">
              <a id="mostrarrecuperar" href="#" class="tx-info tx-12 d-block mg-t-10">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="btn btn-info btn-block">Entrar</button>
          </form>
          <form id="recuperarpasw" style="display:none">
            <div class="form-group">
              <label for="usr_name_recuperar">Restaurar contraseña</label>
              <input type="text" class="form-control" placeholder="Ingresa tu email" name="usr_name_recuperar" id="usr_name_recuperar" autofocus>
            </div>
            <div class="form-group">
              <a id="mostrarlogin" href="#" class="tx-info tx-12 d-block mg-t-10">Iniciar sesión</a>
            </div>
            <button type="submit" class="btn btn-info btn-block">Recuperar</button>
          </form>
            <?php } ?>
          <div class="mg-t-60 tx-center">¿No te has regsitrado? <a href="https://conacon.org/moni/carreras/?e=afiliacion-conacon" class="tx-info">Registrarme</a></div>
        </div><!-- login-wrapper -->
      </div><!-- d-flex -->

      <div class="loader" id="loader">
        <div class="loadState"></div>
      </div>

      <script src="../lib/jquery/jquery.js"></script>
      <script src="../lib/popper.js/popper.js"></script>
      <script src="../lib/bootstrap/bootstrap.js"></script>
      <script src="../js/sweetalert.min.js"></script>
      <script src="script/login.js"></script>
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
            //alert('1.- Click en el botón compartir'+"\n2.- Añadir a pantalla de inicio");
          }

        });

      </script>
    </body>
  </html>

  <?php } else {
    header('Location: panel.php');
    exit;
  } 

