<?php 
  session_start();
  if (!isset($_SESSION["alumno"])) {
    header('Location: index.php');
    die();
  }
  require "data/Model/AfiliadosModel.php";
    $afiliados = new Afiliados();
    $usr = $_SESSION['alumno'];
    $idusuario=$_SESSION['alumno']['id_afiliado'];
    $usuario=$afiliados->obtenerusuario($idusuario);
?>
<!DOCTYPE html>
<style>
    .nav-link.active{
        color:#fff;
        font-weight: bold;
    }
</style>
<html lang="en">
  <?php require 'plantilla/header.php'; ?>
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <span class="breadcrumb-item active">Visita</span>
            </nav>
        </div>

        <div class="br-pagebody bg-light">

            <div class="ht-70 pd-x-20 d-flex align-items-center justify-content-center shadow-base bg-secondary">
                <input class = "d-none" type="text" id="Usuario" value="<?php echo $idusuario;?>">
                <h3 class = "text-white">Cortesias Disponibles</h3>
                <!-- <ul class="nav nav-outline active-info flex-row align-items-center " role="tablist">
                    <li class="nav-item">
                        <a class="nav-link text-light active" data-toggle="tab" href="#posts" role="tab">Hospedaje</a>
                    </li>
                    <li class="nav-item" id="estado_de_cuenta">
                        <a class="nav-link text-light" data-toggle="tab" href="#photos" role="tab">Alimentos</a>
                    </li>
                    <li class="nav-item" id="tab_plan_de_pagos">
                        <a class="nav-link text-light" data-toggle="tab" href="#plan_de_pagos" role="tab">Transporte</a>
                    </li>
                </ul> -->
            </div>

            <div class="br-section-wrapper pt-0" id="content-materias-clases">
                <!-- <form id = "SolicitudDelAlumno"> -->
                    <div id = "cortesiasAlumnmo">

                    </div>
                    <!-- <button type="submit">Solicitar Cortesias</button> -->
                <!-- </form> -->
                <!-- <div class="tab-content br-profile-body">
                    <div class="tab-pane pt-4 fade active show" id="posts">
                        <div id="solicitar_reservacion">
                        </div>

                        <div class="form-layout form-layout-1 mg-t-25" style="display:none;">
                            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Usted tiene una solicitud para compartir habitación</h6>
                            <div id="list_solicitudes">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane pt-4 fade" id="photos">
                        <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Da click para solicitar Alimentos y presiona Guardar</h6>
                        <div class="form-layout form-layout-1" id="container_from_alimentos" style="display:none;">
                            <p class="mg-b-0">Selecciona</p>
                            <form id="form_alimentos">
                                <div class="row mg-b-25">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                        <label class="ckbox">
                                            <input name="radio_comida" id="radio_comida" type="checkbox">
                                            <span>Comida</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="ckbox">
                                                <input name="radio_cena" id="radio_cena" type="checkbox">
                                                <span>Cena</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-layout-footer">
                                    <button class="btn btn-info">Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div class="form-layout form-layout-1 mg-t-25" style="display:none;" id="content_qr_comida">
                            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">ACCESO A COMEDOR</h6>
                            <div class="row mg-b-25">
                                <div class="col-lg-4">
                                <div class="">
                                    <div class="card shadow-base card-body p-0">
                                    <div class="card bd-0" id="qrcode">
                                        <input  id="text" type="hidden" value=""/>
                                        <div class="img_credencial" id="qrcode"></div>
                                    </div>
                                    </div>
                                </div> 
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <p class="mg-b-25 mg-lg-b-50">Alimentos reservados:</p>
                                        <p class="mg-b-25 mg-lg-b-50"><b id="lbl_comida"></b></p>
                                        <p class="mg-b-25 mg-lg-b-50"><b id="lbl_cena"></b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane pt-4 fade" id="plan_de_pagos">
                        <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Da click para solicitar Transporte y presiona Guardar</h6>
                        <div class="form-layout form-layout-1" id="layout-transporte" >
                            <form id="form-solicitar-transporte">
                                <p class="mg-b-0">Reserva transporte</p>
                                <div class="row mg-b-25">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                        <label class="rdiobox">
                                            <input name="radio_reserv_transporte" value="si" type="radio">
                                            <span>Si</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="rdiobox">
                                                <input name="radio_reserv_transporte" value="no" type="radio">
                                                <span>No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-layout-footer">
                                    <button class="btn btn-info" type="submit">Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div class="form-layout form-layout-1 mg-t-25" style="display:none;" id="content_qr_transporte">
                            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">ACCESO A TRANSPORTE</h6>
                            <div class="row mg-b-25">
                                <div class="col-lg-4">
                                    <div class="">
                                        <div class="card shadow-base card-body p-0">
                                        <div class="card bd-0" id="qrcode_tr">
                                            <input  id="text_tr" type="hidden" value=""/>
                                            <div class="img_credencial" id="qrcode_tr"></div>
                                        </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <p class="mg-b-25 mg-lg-b-50">Datos:</p>
                                        <p class="mg-b-25 mg-lg-b-50"><b> Número de transporte: <span id="lbl_transporte"></span></b></p>
                                        <p class="mg-b-25 mg-lg-b-50"><b> Número de asiento: <span id="lbl_asiento"></span></b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

        </div>
        <?php require 'plantilla/footer.php'; ?>
    </div>

    <script src="../lib/jquery/jquery.js"></script>
    <script src="../lib/popper.js/popper.js"></script>
    <script src="../lib/bootstrap/bootstrap.js"></script>
    <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
    <script src="../js/bootstrap-filestyle.js"></script>
    <script src="../lib/moment/moment.js"></script>
    <script src="../lib/jquery-ui/jquery-ui.js"></script>
    <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
    <script src="../lib/peity/jquery.peity.js"></script>
    <script src="../lib/highlightjs/highlight.pack.js"></script>
    <script src="../lib/jquery.steps/jquery.steps.js"></script>
    <script src="../lib/parsleyjs/parsley.js"></script>
    
    <script src="script/cole_alumno_matching.js"></script>
    <!-- <script src="script/cole_alumno_transporte.js"></script> -->

    <script src="script/qrcode.js"></script>
    <script src="script/qrcode.min.js"></script>

    <script src="../js/bracket.js"></script>
    <script src="../js/sweetalert.min.js"></script>

    <script>
        const user_info = <?php echo json_encode($usuario['data']); ?>;
        // var qrcode = new QRCode(document.getElementById("qrcode"), {
        //     width : 110,
        //     height : 110
        // });
        // var qrcode_tr = new QRCode(document.getElementById("qrcode_tr"), {
        //     width : 110,
        //     height : 110
        // });

        // function makeCode () {    
        //     var elText = document.getElementById("text");
        //     if (!elText.value) {
        //     // alert("Input a text 1");
        //     elText.focus();
        //     return;
        //     }
        //     qrcode.makeCode(elText.value);

        //     var elText_tr = document.getElementById("text_tr");
        //     if (!elText_tr.value) {
        //     // alert("Input a text 2");
        //     elText_tr.focus();
        //     return;
        //     }
        //     qrcode_tr.makeCode(elText_tr.value);
        // }
    </script> 

  </body>
</html>
