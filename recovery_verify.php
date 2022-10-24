<?php
    date_default_timezone_set("America/Mexico_City");
    if(!isset($_GET["token"])){
        header("Location: index.php");
        die();
    }
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

        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
		<link href="assets/css/alertas.css" rel="stylesheet" type="text/css">
    </head>


    <body>

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">
            <div class="card card-pages">

                <div class="card-body">
                    <h4 class="text-muted text-center m-t-0"><b>Cambio de contraseña</b></h4>

                    <form class="form-horizontal m-t-20" id="formCompleteRecover">
                        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                        <div class="form-group">
                            <div class="col-12">
                                <label for="">Escriba su nueva contraseña</label>
                                <input class="form-control passcontrol" type="password" required="" name="inpPassw" id="inpPassw" placeholder="*******">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <label for="">Confirme su nueva contraseña</label>
                                <input class="form-control passcontrol" type="password" required="" name="inpPassw_verify" id="inpPassw_verify" placeholder="*******">
                            </div>
                        </div>
                        <div id="verifypass"></div>

                        <div class="form-group text-center m-t-40">
                            <div class="col-12">
                                <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">Actualizar</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>



        <!-- jQuery  -->
        <script src="assets/js/template/jquery.min.js"></script>
        <script src="assets/js/template/bootstrap.bundle.min.js"></script>
        <script src="assets/js/template/modernizr.min.js"></script>
        <script src="assets/js/template/detect.js"></script>
        <script src="assets/js/template/fastclick.js"></script>
        <script src="assets/js/template/jquery.slimscroll.js"></script>
        <script src="assets/js/template/jquery.blockUI.js"></script>
        <script src="assets/js/template/waves.js"></script>
        <script src="assets/js/template/wow.min.js"></script>
        <script src="assets/js/template/jquery.nicescroll.js"></script>
        <script src="assets/js/template/jquery.scrollTo.min.js"></script>
        <script src="assets/js/template/sweetalert.min.js"></script>

        <script src="assets/js/template/app.js"></script>
        <script src="assets/js/recovery.js"></script>
        <script>
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: "assets/data/Controller/accesoControl.php",
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
            $(".passcontrol").on('keyup', (e)=>{
                var val1 = $("#inpPassw").val().trim();
                var val2 = $("#inpPassw_verify").val().trim();
                if(val1 == val2 && val1 != ''){
                    $("#verifypass").html(`<div class="alert alert-success text-light"> Las contraseñas coinciden.</div>`);
                }else if(val1 == '' || val2 == ''){
                    $("#verifypass").html('');
                }else{
                    $("#verifypass").html(`<div class="alert alert-warning"> Las contraseñas no coinciden.</div>`);
                }
            });
        </script>

    </body>
</html>