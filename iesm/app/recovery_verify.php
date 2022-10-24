<?php
    date_default_timezone_set("America/Mexico_City");
    if(!isset($_GET["token"])){
        header("Location: index.php");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:url" content="https://www.facebook.com/udconde/">
    <meta property="og:title" content="Universidad del Conde">
    <meta property="og:description"
        content="En Universidad del Conde, día con día nos damos a la tarea de sembrar en nuestros alumnos un interés genuino por influir de forma positiva en el mundo, por ser el agente de cambio que nuestro México necesita y trabajando todos los días sin quitar la vista de nuestro objetivo.">
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
    <style>
        .btn-neutro {
            background-color: transparent;
            border: 2px solid #009d9a;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">
        <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
            <div class="tx-center mg-b-60">
                <h2>Reestablecer contraseña</h2>
            </div>

            <form id="recover_pass">
                <input type="hidden" name="token" value="<?php echo ($_GET['token']); ?>">
                <div class="form-group">
                    <label for="inpPassw">Nueva contraseña</label>
                    <input type="password" class="form-control passcontrol" placeholder="********" name="inpPassw" id="inpPassw" autofocus>
                </div>
                <div class="form-group">
                    <label for="inpPassw_verify">Confirmar contraseña</label>
                    <input type="password" class="form-control passcontrol" placeholder="********" name="inpPassw_verify" id="inpPassw_verify" autofocus>
                </div>
                <div class="form-group">
                    <div id="verifypass"></div>
                </div>
                <button type="submit" class="btn btn-neutro btn-block">Recuperar</button>
            </form>

        </div><!-- login-wrapper -->
    </div><!-- d-flex -->

    <div class="loader" id="loader">
        <div class="loadState"></div>
    </div>

    <script src="../lib/jquery/jquery.js"></script>
    <script src="../lib/popper.js/popper.js"></script>
    <script src="../lib/bootstrap/bootstrap.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="script/recovery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: "../../assets/data/Controller/accesoControl.php",
                data: {action:'comprobar_token',token:'<?= $_GET['token'] ?>'},
                success: function (response) {
                    var verif = JSON.parse(response);
                    if(verif.estatus == 'error'){
                        swal({
                            icon:'info',
                            title:verif.info
                        }).then(()=>{
                            window.location.href = './';
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>