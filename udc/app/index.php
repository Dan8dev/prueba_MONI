  
<?php
session_start();
if (!isset($_SESSION['alumno'])) {
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
      <link rel="apple-touch-icon" href="img/Icon-App-40x40@1x.png">

      <link rel="apple-touch-startup-image" href="/img/icon_1024.png">
      <link rel="icon" type="imge/png" href="img/favicon.png">

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

        

      </script>
    </body>
  </html>

  <?php } else {
    header('Location: panel.php');
    exit;
  } 

