<?php
session_start();
if( !isset($_SESSION["alumno_iesm"]) && !isset($_SESSION["usuario"]) ){
    header("Location: ../index.php");
    die();
}

$tipo_usuario=@$_GET["perfil"];

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>MONI</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

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
    <!-- Begin page -->
      <div class="d-flex align-items-center justify-content-center bg-gray-300 ht-500 pd-x-20 pd-xs-x-0">
        <div class="card wd-350 shadow-base">
          <div class="card-body">
            <div class="text-center m-t-20 m-b-30">
              <a href="index.html" class="logo logo-admin"><img src="assets/images/logo-dark.png" alt="" height="34"></a>
            </div>
            <h4 class="text-muted text-center m-t-0"><b>Cambiar contraseña</b></h4>
            <form class="form-horizontal m-t-20" id="formCambiar_pass">
              <div class="form-group">
                <div class="col-12">
                  <label for="new_pass">Nueva contraseña</label>
                  <input class="form-control" type="password" required="" name="new_pass" id="new_pass" placeholder="">
                  <input class="form-control" type="hidden" name="tipousuario" id="tipousuario" value="<?php echo $tipo_usuario ?>">
                </div>
              </div>
              <div class="form-group">
                <div class="col-12">
                  <label for="confirm_pass">Confirmar contraseña</label>
                  <input class="form-control" type="password" required="" name="confirm_pass" id="confirm_pass" placeholder="">
                </div>
              </div>
              <div class="form-group text-center m-t-40">
                <div class="col-12">
                  <button class="btn btn-secondary btn-block btn-lg waves-effect waves-light" type="submit">Guardar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
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
          alert('1.- Click en el botón compartir'+"\n2.- Añadir a pantalla de inicio");
        }

      });

    </script>

    </body>
</html>