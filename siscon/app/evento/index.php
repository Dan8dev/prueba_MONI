<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>CONACON</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="../assets/images/favicon.ico">

        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
		
		<link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">

    </head>


    <body>

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">
            <div class="card card-pages">

                <div class="card-body">
                    <div class="text-center m-t-20 m-b-30">
                            <a href="index.html" class="logo logo-admin"><img src="../assets/images/logo-cona-dark.png" alt="" height="34"></a>
                    </div>
                    <h4 class="text-muted text-center m-t-0"><b>Acceso</b></h4>

                    <form class="form-horizontal m-t-20" id="formLogin">

                        <div class="form-group">
                            <div class="col-12">
                                <input class="form-control" type="text" required="" name="inpCorreo" id="inpCorreo" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12">
                                <input class="form-control" type="password" required="" name="inpPassw" id="inpPassw" placeholder="Codigo acceso">
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <div class="col-12">
                                <div class="checkbox checkbox-primary">
                                    <input id="checkbox-signup" name="chek-recordar" type="checkbox">
                                    <label for="checkbox-signup">
                                        Recordar contraseña
                                    </label>
                                </div>
                            </div>
                        </div>-->

                        <div class="form-group text-center m-t-40">
                            <div class="col-12">
                                <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">Ingresar</button>
                            </div>
                        </div>

                        <!-- <div class="form-group row m-t-30 m-b-0">
                            <div class="col-sm-7">
                                <a href="recuperarPass.html" class="text-muted"><i class="fa fa-lock m-r-5"></i> ¿Olvidaste tu password?</a>
                            </div>
                        </div> -->
                    </form>
                </div>

            </div>
        </div>



        <!-- jQuery  -->
        <script src="../assets/js/template/jquery.min.js"></script>
        <script src="../assets/js/template/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/template/modernizr.min.js"></script>
        <script src="../assets/js/template/detect.js"></script>
        <script src="../assets/js/template/fastclick.js"></script>
        <script src="../assets/js/template/jquery.slimscroll.js"></script>
        <script src="../assets/js/template/jquery.blockUI.js"></script>
        <script src="../assets/js/template/waves.js"></script>
        <script src="../assets/js/template/wow.min.js"></script>
        <script src="../assets/js/template/jquery.nicescroll.js"></script>
        <script src="../assets/js/template/jquery.scrollTo.min.js"></script>
        <script src="../assets/js/template/sweetalert.min.js"></script>

        <script src="../assets/js/template/app.js"></script>
        <script src="../assets/js/eventos/login.js"></script>

    </body>
</html>