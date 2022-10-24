<?php
    date_default_timezone_set("America/Mexico_City");
    if(!isset($_GET["afiliado"])){
        header("Location: index.php");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:title" content="Sistema de gastión escolar MONI">

    <meta property="og:image" content="https://moni.com.mx/assets/images/logoMoni.png">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="Sistema de gastión escolar MONI" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://moni.com.mx/assets/images/logoMoni.png" />
    <meta property="og:description" content="" />
    <!-- Meta -->
    <meta name="description" content="Sistema de gastión escolar MONI">

    <title>MONI</title>
    <link rel="apple-touch-icon" href="img/Icon-App-40x40@1x.png">


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
    <div class="align-items-center justify-content-center bg-br-primary ht-100v row">
        <div class="login-wrapper pd-25 pd-xs-40 bg-white rounded shadow-base col-sm-10 col-md-8">
            <div class="tx-center mg-b-60">
                <h3>Solicitud de eliminación de cuenta</h3>
            </div>

            <h6 class="tx-inverse mg-b-25 lh-4">Saludos cordiales.</h6>

            <p>Lamentamos que solicites la eliminación de tu cuenta.</p>

            <p>Sin embargo te recordamos que al ser una plataforma educativa tu solicitud debe pasar por una serie de revisiones antes de proceder con la eliminación.</p>

            <p>Este proceso puede tomar algunos días, por lo que cuando tengamos una respuesta para ti recibirás un correo electrónico.</p>
            <br>
            <p>Si deseas continuar con este proceso, por favor llena el siguiente formulario.</p>
            <div class="border border-secondary rounded p-3">
                <form id="delete_account">
                    <input type="hidden" name="afiliado" value="<?php echo ($_GET['afiliado']); ?>">
                    <div class="form-group">
                        <label for="motivo">Describe brevemente el motivo de tu solicitud</label>
                        <textarea name="motivo" id="motivo" rows="2" class="form-control" maxlength="180" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="inpPassw">Escriba su contraseña</label>
                        <input type="password" class="form-control passcontrol" placeholder="********" name="inpPassw" id="inpPassw" autofocus required>
                    </div>
                    <div class="form-group">
                        <label for="inpPassw_verify">Confirme su contraseña</label>
                        <input type="password" class="form-control passcontrol" placeholder="********" name="inpPassw_verify" id="inpPassw_verify" autofocus required>
                    </div>
                    <div class="form-group">
                        <div id="verifypass"></div>
                    </div>
                    <button type="submit" class="btn btn-neutro btn-block text-secondary">Solicitar eliminación</button>
                </form>
            </div>

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
        let panel = <?= isset($_GET['panel']) ? $_GET['panel'] : null ?>;
        // $(document).ready(function(){
        //     $.ajax({
        //         type: "POST",
        //         url: "../../assets/data/Controller/accesoControl.php",
        //         data: {action:'comprobar_token',afiliado:'<?= $_GET['afiliado'] ?>'},
        //         success: function (response) {
        //             var verif = JSON.parse(response);
        //             if(verif.estatus == 'error'){
        //                 swal({
        //                     icon:'info',
        //                     title:verif.info
        //                 }).then(()=>{
        //                     window.location.href = './';
        //                 });
        //             }
        //         }
        //     });
        // });
    </script>
</body>

</html>