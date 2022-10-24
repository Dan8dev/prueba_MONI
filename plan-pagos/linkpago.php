<?php
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
    <!-- Meta -->
    <meta name="description" content="Colegio nacional de consejeros. CONACON">
    <meta name="author" content="CONACON TI">

    <title>CONACON</title>

    <!-- vendor css -->
    <link href="https://conacon.org/moni/siscon/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="https://conacon.org/moni/siscon/lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="https://conacon.org/moni/siscon/lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="https://conacon.org/moni/siscon/lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
    <link href="https://conacon.org/moni/siscon/lib/highlightjs/github.css" rel="stylesheet">
    <link href="https://conacon.org/moni/siscon/lib/jquery.steps/jquery.steps.css" rel="stylesheet">
    
    <link rel="icon" type="imge/png" href="img/favicon.png">

    <!--Datatables-->
    <link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- CANVAS -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Bracket CSS -->
    <link rel="stylesheet" href="https://conacon.org/moni/siscon/css/bracket.css">
    <link href="../assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://conacon.org/moni/siscon/css/alertas.css">

    <link rel="stylesheet" href="https://conacon.org/moni/siscon/lib/datatables/jquery.dataTables.css">
    <style> 
      .btn-primary {
          color: #fff !important;
          background-color: #000 !important;
          border-color: #0a0a0a !important;
      }
      .bg-primary {
          background-color: #b8babd !important;
      }

      .card-header {
            box-shadow: inset -3px -2px 6px -1px;
        }
        .card{
            box-shadow: 3px 1px 7px -2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card bd-0">
            <div class="card-header bg-primary">
                <div class="row mt-3">
                    <div class="col-6">
                        <h1 class="text-dark ml-4"><strong> <?php echo $_GET['nombre_concepto']?></strong></h1>
                    </div>
                    <div class="col-6 text-right">
                        <h2 class="text-dark mr-4"> COSTO <strong>$ <?php echo number_format($_GET['precio'])?> <?php echo strtoupper($_GET['Moneda'])?> </strong></h2>
                    </div>
                </div>
               <!-- <span style="color:black"> <h1> COSTO <strong>$ <?php echo number_format($_GET['precio'])?> <?php echo strtoupper($_GET['Moneda'])?> </strong></h1></span> 
               <div class="text-center">
                <span style="color:black"><h3><strong> <?php echo $_GET['nombre_concepto']?></strong></h3></span> 
               </div> -->
            </div><!-- card-header -->
            <div class="card-body bd bd-t-0 rounded-bottom">
                <div class="text-center">
                    <?php 
                        $logo = '';
                        if($_GET['id_concepto'] == 24){
                            $logo = 'https://conacon.org/moni/siscon/img/logoT.png';
                        }else if($_GET['id_concepto'] == 31){
                            $logo = 'logoTIESM.png';
                        }else{
                            $logo = 'https://moni.com.mx/udc/img/logoT.png';
                        }
                    ?>
                    <img src="<?php echo $logo;?>" alt="" class="img-responsive" width="35%">
                </div>
                <div class="text-center" id="">
                    <button type="button" class="btn btn-primary boton-pago-spei mt-4" id="mostrar-pago-spei">
                   PAGAR MEDIANTE SPEI TRANSFERENCIA ELECTRÓNICA
                    </button>
                </div>
                <div class="text-center" id="no_mostrar_boton_tarjeta">
                    <button type="button" class="btn btn-primary boton-pago-tarjeta mt-4" id="mostrar-tarjeta-credito">
                    Pagar con tarjeta de crédito/débito
                    </button>
                </div>
                <div class="container px-5">
                    <div class="ps py-2" style="display:none" id="mostrar-spei">
                        <div class="ps-header">
                            <div class="ps-reminder">Pago mediante transferencia electrónica SPEI</div>
                            <div class="ps-info">
                                <div class="ps-brand"><img src="https://moni.com.mx/udc/app/img/spei_brand_transferencia.png" alt="Banorte"></div>
                                <div class="ps-amount text-right" style="padding-right: 6%;">
                                    <h3>Monto a pagar</h3>
                                    <h2> <span id="monto_pago_spei"><?php echo $_GET['precio'];?></span> <sup id="tipo_moneda"></sup></h2>
                                    <p style="font-size: 12px;"><strong>Utiliza exactamente esta cantidad al realizar el pago</strong>.</p>
                                </div>
                            </div>
                            <div class="ps-reference">
                                <h3>CLABE</h3>
                                <h1 id="referencia_pago_spei"></h1>
                            </div>
                        </div>
                        <div class="ps-instructions">
                            <h3>Instrucciones</h3>
                            <ol>
                                <li>Accede a tu banca en línea.</li>
                                <li>Da de alta la CLABE en esta ficha. <strong>El banco deberá de ser STP</strong>.</li>
                                <li>Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, <strong>de lo contrario se rechazará el cargo</strong>.</li>
                                <li>Al confirmar tu pago, el portal de tu banco generará un comprobante digital. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
                                <li><strong>los pagos realizados en este medio tendrán que ser notificados con su ejecutiva de callcenter o en tu panel de alumno.</strong></li>
                            </ol>
                            <!-- <div class="ps-footnote">Al completar estos pasos recibirás un correo de <strong>Nombre del negocio</strong> confirmando tu pago.</div> -->
                        </div>
                        <div class="ps-reminder">Ficha digital. No es necesario imprimir.</div>
                    </div>	
                    <form id="form-token-tarjeta" class="ocultar-mostrar-ficha-tarjeta">
                        <div class="text-right">
                            <img src="../assets/images/visamasteramex.png" alt="" class="img-responsive" width="30%">
                        </div>
                        <div class="row">
                        <!-- inputs pay -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="email" placeholder="ingresa tu email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Celular</label>
                                <input type="text" name="telefonofactura" id="telefonofactura" placeholder="10 dígitos" class="form-control onlyNumer" maxlength="10" required value="">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <img src="" alt='' id="type_card_img" width="35">
                                <span class="company">
                                TIPO DE TARJETA
                                </span>
                                <div class="form-group">
                                    <label>Nombre del Tarjetahabiente</label>
                                    <input id="nombretarjeta" type="text" placeholder="Nombre del titular de la tarjeta" class="form-control cc-nombre normalText" name="nombretarjeta" data-conekta="card[name]">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Tarjeta</label>
                                <input type="text" placeholder="16 dígitos" class="form-control cc-num" data-conekta="card[number]">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                <label>CVC</label>
                                <input type="text" placeholder="123" class="form-control cc-cvc" maxlength="4" data-conekta="card[cvc]">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                <label>Fecha de Expiración</label>
                                <input type="text" placeholder="MM/AA" class="form-control cc-exp">
                                <input id="mes" type="hidden" placeholder="mm" data-conekta="card[exp_month]">
                                <input id="anio" type="hidden" placeholder="aa" data-conekta="card[exp_year]">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input type="hidden" id="tipo_pago" name="tipo_pago" value="<?php echo $_GET['id_concepto']?>">
                                <input type="hidden" id="totalapagar" name="totalapagar" value="<?php echo $_GET['precio']?>">
                                <input type="hidden" id="nombre_concepto" name="nombre_concepto" value="<?php echo $_GET['nombre_concepto']?>">
                                <input type="hidden" id="nombreclientespei" name="nombreclientespei" value="<?php echo $_GET['nombreclientespei']?>">
                                <input type="hidden" id="emailclientespei" name="emailclientespei" value="<?php echo $_GET['emailclientespei']?>">
                                <input type="hidden" id="Moneda" name="Moneda" value="<?php echo $_GET['Moneda']?>">
                                <button type="button" class="btn btn-primary btn-block" name="validartarjeta">
                                Realizar pago
                                </button>
                            </div> <!-- end inputs pay -->
                        </div>
                    </form>
                </div>
                <div class="row mt-3">
                    <div class="col text-right">
                        <small class="tx-14 mr-5">Aceptamos todas las tarjetas  </small>
                        <img src="../udc/img/visa_icon.png" width="40px" alt="visa">
                        <img src="../udc/img/american_icon.png" width="40px" alt="visa">
                        <img src="../udc/img/mastercard_icon.png" width="40px" alt="visa">
                    </div>
                </div>
                <div class="col-sm-12">
                </div>
            </div><!-- card-body -->
            <div class="card-footer">
                <!-- <center>
                    <small>CONEKTA 	&copy;</small>
                </center> -->
                <div class="row my-3">
                    <div class="col-6">
                        UNIVERSIDAD DEL CONDE. CONACON. - TI Derechos Reservados <?php echo date('Y'); ?>
                    </div>
                    <div class="col-6 text-right">
                        CONEKTA &copy;
                    </div>
                </div>
            </div>
        </div><!-- card -->
    </div>
    <script type="text/javascript" src="https://conektaapi.s3.amazonaws.com/v0.5.0/js/conekta.js"></script>
    <script src="https://conacon.org/moni/siscon/app/script/jquery.payment.js"></script>
    <!-- jsPDF library -->
    <script src="../assets/plugins/jsPDF/dist/jspdf.min.js"></script>
    <!--Sweet Alert 2-->
    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	<script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../assets/js/planpagos/linkpago.js"></script>

</body>
</html>
