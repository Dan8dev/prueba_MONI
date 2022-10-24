<!-- Iconografía fontawesom  (fa) https://fontawesome.com/ -->

<?php
if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
  //Tell the browser to redirect to the HTTPS URL.
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
  //Prevent the rest of the script from executing.
  exit;
}

session_start();
if (!isset($_SESSION["alumno"])) {
  header('Location: index.php');
  die();
}
$usr = $_SESSION['alumno'];

require('plantilla/required.php');
$idusuario = $_SESSION['alumno']['id_afiliado'];
$afiliados = new Afiliados();
$usuario = $afiliados->obtenerusuario($idusuario);
/*$fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
$fechaactual= date('Y-m-d H:i:s');

$fechafinmembresia1=$fechafinmembresia['data']['finmembresia'];
$tipodemembresia = $fechafinmembresia['data']['id_concepto'];
$fechaactivacion=$fechafinmembresia['data']['fechapago'];

$datetime1 = new DateTime($fechaactual);
$datetime2 = new DateTime($fechafinmembresia1);
$interval = $datetime1->diff($datetime2);
$diasrestantes= substr($interval->format('%R%a días'), 1);
$dias = rtrim($diasrestantes, ' días');*/
?>

<!DOCTYPE html>
<html lang="en">
<?php require 'plantilla/header.php'; ?>
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
  <div class="br-pageheader pd-y-15 pd-l-20">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
      <a class="breadcrumb-item" href="panel.php">INICIO</a>
      <span class="breadcrumb-item active">PAGOS</span>
    </nav>
  </div><!-- br-pageheader -->

  <div class="br-pagebody">
    <?php # if (rtrim($interval->format('%R%a días'), ' días')<0||rtrim($interval->format('%R%a días'), ' días')==-0) {  
    ?>
    <!-- <div id="alert-pago-anual" class="alert alert-info" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  Tu <strong class="d-block d-sm-inline-block-force"> Membresía</strong> a finalizado <strong class="d-block d-sm-inline-block-force"> renuevala </strong> 
  </div> -->
    <?php # } else { if($dias<31) {  
    ?>
    <!-- <div id="alert-pago-anual" class="alert alert-info" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    Periodo <strong class="d-block d-sm-inline-block-force"> Gratuito</strong> por <strong class="d-block d-sm-inline-block-force"> <?php # echo $diasrestantes
                                                                                                                                    ?> <a class="text-dark" href="pagos.php">Pague aqui</a></strong> 
    </div> -->
    <?php # } } 
    ?>
    <!-- ########## START: CARDS INFORMATIVOS ########## -->
    <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
      <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#posts" role="tab">PAGOS</a></li>
        <li id="estado_de_cuenta" class="nav-item"><a class="nav-link" data-toggle="tab" href="#photos" role="tab">ESTADO DE CUENTA</a></li>
        <li id="tab_plan_de_pagos" class="nav-item"><a class="nav-link" data-toggle="tab" href="#plan_de_pagos" role="tab">PLAN DE PAGOS</a></li>
      </ul>
    </div>

    <div class="card card-body pd-x-25" id="container-conceptos-pago">
      <div class="tab-content br-profile-body">
        <div class="tab-pane fade active show" id="posts">
          <!-- CONTENEDOR PAGOS -->
          <div class="row">
            <!--<div class="col-12">
              <div class="alert alert-warning" role="alert">
                <div class="d-flex align-items-center justify-content-start">
                  <i class="icon ion-alert-circled alert-icon tx-24 mg-t-5 mg-xs-t-0"></i>
                  <span><strong>Atención!</strong> Por el momento estamos realizando cambios en nuestra plataforma, favor de intentar mas tarde.</span>
                </div>
              </div>
            </div>-->
            <div class="col-12 mb-4">
              <label>Inscripciones activas</label>
              <select id="select_inscripciones" class="form-control" >
              </select>
            </div>

            <div class="col-12 mb-3">
              <!-- CONTENEDOR ASIGNACION DE PLAN PAGOS -->
              <div class="text-center">
                <h4 id="lbl_plan_pagos"></h4>
              </div>
            </div>


            <div class="col-sm-12 overflow-auto">
              <h5 class="tx-center mb-3">Conceptos por pagar</h5>
              <section id="generaciones_disp">
              </section>

              <table id="tabla_conceptos_pagar" class="table table-striped table-bordered dt-responsive">
                <thead>
                  <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Monto promoción</th>
                    <th>Pagar ahora</th>
                  </tr>
                </thead>
              </table>

              <hr class="d-none d-sm-block">

            </div>
            <div class="col-sm-12" id="container_pagos">
              <div id="card_pay_now" style="display:none;">

              </div>
            </div>

          </div>
        </div><!-- tab-pane -->
        <div class="tab-pane fade" id="photos">
          <div class="row">
            <div class="col-12">
              <div class="table-responsive overflow-auto">
                <h5 class="tx-center mb-3">Pagos aplicados</h5>
                <span id="span_saldo"></span>
                <table id="table_pagos_apli" action="obtener_pagos_aplicados" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Fecha</th>
                      <th>Concepto</th>
                      <th>Origen</th>
                      <th>Precio lista</th>
                      <th>Promociones</th>
                      <th>Costo</th>
                      <th>Recargos</th>
                      <th>Total a pagar</th>
                      <th>Pago realizado</th>
                      <th>Saldo pendiente</th>
                      <th>Pagar ahora</th>
                    </tr>
                  </thead>

                  <tbody>
                  </tbody>
                </table>

              </div>
            </div>
          </div><!-- row -->
          <br>
          <br>
          <br>
          <!-- <div class="row">
            <div class="col-12">
              <div class="table-responsive overflow-auto">
                <h5 class="tx-center mb-3">Resumen parcial por carrera</h5>
                <table id="table_pagos_total_carreras" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                  <thead>
                    <tr>
                      <th>Carrera</th>
                      <th>Costo total</th>
                      <th>Pagado</th>
                      <th>Restante</th>
                    </tr>
                  </thead>

                  <tbody>
                  </tbody>
                </table>

              </div>
            </div>
          </div>row -->
        </div><!-- tab-pane -->
        <div class="tab-pane fade" id="plan_de_pagos">
          <div class="row">
            <div class="col-sm-12">
              <select id="select_inscrito" class="form-control">
              </select>
            </div>
            <div class="col-sm-12 table-responsive" id="show_plan">

            </div>
          </div>
        </div><!-- tab-pane -->
      </div><!-- br-pagebody -->



    </div>

    <div class="card card-body pd-x-25" style="display:none;" id="container-form-procesar-pago">

      <div class="row">
        <div class="col-sm-12 col-md-6 my-auto">
          <div class="" id="container-pago-fin">

          </div>
          <br>
            <button type="button" class="btn btn-primary btn-block" id="generar_ficha_pago">
            Generar ficha para pago en oxxo
            </button>
          <br>
            <button type="button" class="btn btn-primary btn-block" id="generar_ficha_pago_ventanilla">
            Generar ficha para pago en ventanilla banorte
            </button>
          <br>
            <button type="button" class="btn btn-primary btn-block" id="generar_ficha_pago_spei">
            Pagar por SPEI
            </button>
        </div>

        <div class="col-sm-12 col-md-6">
          <div id="mostrar_ficha" style="display:none">
            <div class="opps" id="foto_ficha_oxxo">
              <div class="opps-header">
                <div class="opps-reminder">
                  Ficha digital. No es necesario imprimir.
                </div>
                <div class="opps-info">
                  <div class="opps-brand">
                    <img src="img/oxxopay_brand.png" alt="OXXOPay">
                  </div>
                  <div class="opps-ammount">
                    <h3>Monto a pagar</h3>
                    <h2> <span id="monto_pago"></span> <sup id="tipo_moneda"></sup></h2>
                    <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
                  </div>
                </div>
                <div class="opps-reference">
                  <h3>REFERENCIA:</h3>
                  <h1 id="referencia_pago">0000-0000-0000-00</h1>
                </div>
                <br>
                <div>
                  <img id="codigo_barras-reference" src="" alt="">
                </div>
                <div class="opps-reference">
                  <h5 id="concepto_de_pago">Inscripción</h5>
                </div>
              </div>
              <div class="opps-instructions">
                  <h3>Instrucciones</h3>
                  <ol>
                    <li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
                    <li>Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
                    <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
                    <li>Realiza el pago correspondiente con dinero en efectivo.</li>
                    <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
                  </ol>
                 <div class="opps-footnote">
                  <strong>Tu pago se verifica en automático no necesitas repotarlo</strong>
                  <br>
                </div>
                <form action="../../marketing-educativo/descargar_ficha_oxxo.php" method="post" target="_blank">
                  <input type="hidden" name="tipo_moneda_ficha" id="tipo_moneda_ficha">
                  <input type="hidden" name="nombre_concepto_ficha" id="nombre_concepto_ficha">
                  <input type="hidden" name="monto_pago_ficha" id="monto_pago_ficha">
                  <input type="hidden" name="referencia_ficha" id="referencia_ficha">
                  <input type="hidden" name="bar_code_ficha" id="bar_code_ficha">
                  <button type="submit" class="btn btn-primary btn-block" id="descargar_ficha_oxxo">Descargar ficha de pago</button>
                </form>
              </div>
            </div>
            <div class="col-sm-12">
              <br>
                <button type="button" class="btn btn-primary btn-block" id="mostrar_pago_tarjeta">
                Pagar con tarjeta de crédito/débito
                </button>
              </div>
          </div><!--- fin de mostrar ficha pago oxxo pay-->

          <div id="mostrar_ficha_pago_ventanilla" style="display:none">
            <div class="opps" id="foto_ficha_oxxo">
              <div class="opps-header">
                <div class="opps-reminder">
                  Ficha digital
                </div>
                <div class="opps-info">
                  <div class="opps-brand">
                    <img src="img/spei_brand.png" alt="OXXOPay">
                  </div>
                  <div class="opps-ammount">
                    <h3>Monto a pagar</h3>
                    <h2> <span id="monto_pago_banorte"></span> <sup id="tipo_moneda">MXN</sup></h2>

                  </div>
                </div>
                <div class="opps-reference">
                  <h3>REFERENCIA:</h3>
                  <h1 id="referencia_pago_banorte">0000-0000-0000-00</h1>
                </div>
                <br>
                <div class="opps-reference">
                <h3>Concepto:</h3>
                  <h5 id="concepto_de_pago_banorte">Inscripción</h5>
                </div>
                <div class="opps-reference">
                  <br>
                  <h5>UNIVERSIDAD DEL CONDE</h5>
                  <br>
                  <h5>CONVENIO: 118868</h5>
                  <br>
                  <h5>CUENTA: 0823622605</h5>
                  <br>
                  <h5>CUENTA CLABE: 072 650 008236226054</h5>
                  <br>
                  <h5>ALUMNO: <span id="nombre_alumno_banorte"></span>
                  </h5>
                </div>
              </div>
              <div class="opps-instructions">
                <h3>Instrucciones</h3>
                <ol>
                  <li>Acudir con tu ficha de pago a cualquiera de nuestros corresponsales.</li>
                  <li>Indicar al cajero que se realizará un <b>pago de servicios</b> y proporcionarle la ficha</li>
                  <li>El cajero solicitará el pago. En corresponsales la única forma de pago permitida es en efectivo. es importante considerar que se cobrará una comisión adicional al importe de pago</li>
                  <li> <strong>Reporta tu pago.</strong></li>
                </ol>
                <div class="opps-footnote text-warning border-warning">
                  <strong>Recuerda reportar este pago dentro de tu plataforma</strong>
                </div>
                <br>
                <form action="../../marketing-educativo/descargar_ficha_oxxo.php" method="post" target="_blank">
                  <input type="hidden" name="tipo_moneda_ficha" id="tipo_moneda_ficha">
                  <input type="hidden" name="nombre_concepto_ficha" id="nombre_concepto_ficha">
                  <input type="hidden" name="monto_pago_ficha" id="monto_pago_ficha">
                  <input type="hidden" name="referencia_ficha" id="referencia_ficha">
                 <!-- <button type="submit" class="btn btn-primary btn-block" id="descargar_ficha_oxxo">Descargar ficha</button>-->
                </form>
                <div>
                  <img src="img/seveneleven.png" alt="">
                  <img src="img/yastas.png" alt="">
                  <img src="img/farmacias.png" alt="">
                  <img src="img/chedraui.png" alt="">
                  <img src="img/delsol.png" alt="">
                  <img src="img/woolwrth.png" alt="">
                  <img src="img/lacomer.png" alt="">
                  <img src="img/telecom.png" alt="">
                </div>
                <form action="../../marketing-educativo/descargar_ficha_banorte.php" method="post" target="_blank">
                  <input type="hidden" name="tipo_moneda_ficha" id="tipo_moneda_ficha_banorte">
                  <input type="hidden" name="nombre_concepto_ficha" id="nombre_concepto_ficha_banorte">
                  <input type="hidden" name="monto_pago_ficha" id="monto_pago_ficha_banorte">
                  <input type="hidden" name="referencia_ficha" id="referencia_ficha_banorte">
                  <input type="hidden" name="nombre_alumno_banorte_pdf" id="nombre_alumno_banorte_pdf">
                  <br>
                  <button type="submit" class="btn btn-primary btn-block" id="descargar_ficha_oxxo">Descargar ficha</button>
                </form>
              </div>
            </div>
            <div class="col-sm-12">
            <br>
              <button type="button" class="btn btn-primary btn-block" id="mostrar_pago_tarjeta_banorte">
              Pagar con tarjeta de crédito/débito
              </button>
            </div>
          </div><!--- fin de mostrar ficha pago ventanilla banorte-->

          <div id="mostrar_ficha_pago_spei" style="display:none"><!--- inicio de mostrar ficha pago transferencia spei-->
            <div class="spei">
              <div class="ps-header">
                <div class="ps-reminder">Ficha digital. No es necesario imprimir.</div>
                <div class="ps-info">
                  <div class="ps-brand"><img src="img/spei_brand_transferencia.png" alt="Banorte"></div>
                  <div class="ps-amount">
                    <h3>Monto a pagar</h3>
                    <h2 id="monto_pago_spei"> <sup id="tipo_moneda"></sup></h2>
                    <p>Utiliza exactamente esta cantidad al realizar el pago.</p>
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
                </ol>
                <div class="ps-footnote"><strong>Tu pago se verifica en automático no necesitas reportarlo</strong></div>
              </div>
              <br>

            </div>	<!--- fin de mostrar ficha pago transferencia spei-->
            <div class="col-sm-12">
            <br>
              <button type="button" class="btn btn-primary btn-block" id="mostrar_pago_tarjeta_spei">
              Pagar con tarjeta de crédito/débito
              </button>
            </div>
          </div>

          <form id="form-token-tarjeta" class="ocultar-mostrar-ficha-tarjeta">
            <div class="row">
              <!-- inputs pay -->
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
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Celular</label>
                  <input type="text" name="telefonofactura" id="telefonofactura" placeholder="10 dígitos" class="form-control onlyNumer" maxlength="10" required value="<?php echo $usr['celular']; ?>">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Tarjeta</label>
                  <input type="text" placeholder="16 dígitos" class="form-control cc-num" data-conekta="card[number]">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>CVC</label>
                  <input type="text" placeholder="123" class="form-control cc-cvc" maxlength="4" data-conekta="card[cvc]">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Fecha de Expiración</label>
                  <input type="text" placeholder="MM/AA" class="form-control cc-exp">
                  <input id="mes" type="hidden" placeholder="mm" data-conekta="card[exp_month]">
                  <input id="anio" type="hidden" placeholder="aa" data-conekta="card[exp_year]">
                </div>
              </div>
              <div class="col-sm-12">
                <label class="ckbox" id="check_domiciliar_pago" style="display:none">
                  <input type="checkbox" name="domiciliar_pago" id="domiciliar_pago">
                  <span>Domiciliar pago a tus colegiaturas mensuales</span>
                </label>
                <br>
                <button type="button" class="btn btn-primary btn-block" name="validartarjeta">
                  Pagar
                </button>
              </div> <!-- end inputs pay -->
              <div class="col-sm-12">
              
              </div>
            </div>
          </form>
        </div>
        <div class="col-12 mt-4">
          <button class="btn btn-secondary btn-block" id="btn-cancel-pay">Cancelar</button>
        </div>
      </div>
    </div>
    <!-- LARGE MODAL -->
    <div id="modal_solicitar_prorroga" class="modal fade">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tx-size-sm">
          <div class="modal-header pd-x-20">
            <h4 class=" lh-3 mg-b-20">Tu solicitud será puesta en validación</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pd-20">
            <h4 class=" lh-3 mg-b-20">Fecha limite de pago: <span id="fecha_limite_de_pago_prorroga"></span></h4>
            <div class="form-layout form-layout-4">
              <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Ingresa los datos a continuación</h6>
              <div class="row">
                <label class="col-sm-4 form-control-label">Nueva fecha de pago: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="date" class="form-control" id="fecha_prorroga">
                  <input type="hidden" id="id_concepto_prorroga">
                  <input type="hidden" id="numero_de_pago_prorroga">
                </div>
              </div><!-- row -->
              <div class="row mg-t-20">
                <label class="col-sm-4 form-control-label">Motivo de la prorroga: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <textarea id="descripcion_prorroga" rows="4" class="form-control" placeholder="Motivo de la prorroga"></textarea>
                </div>
              </div>
            </div><!-- form-layout -->
          </div><!-- modal-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-primary tx-size-xs" id="enviar_solicitud_boton">Enviar solicitud</button>
            <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div id="modal_notificar_pago" class="modal fade">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tx-size-sm">
          <div class="modal-header pd-x-20">
            <h5 class=" lh-3 mg-b-20">Reportar un pago realizado por un medio externo a esta plataforma</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pd-20">

            <form id="form_registrar_pago_alumno">
              <input type="hidden" name="tipo_pago" id="tipo_pago">
              <input type="hidden" name="person_pago" id="person_pago">
              <input type="hidden" name="inp_promos_disp" id="inp_promos_disp">
              <div class="form-layout form-layout-4">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Ingresa los datos a continuación</h6>
                <div id="notificar_pendiente"></div>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Fecha de realización: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="date" class="form-control" id="inp_fecha_pago" name="inp_fecha_pago" max="<?php echo(date("Y-m-d"));?>" value="<?php echo(date("Y-m-d"));?>">
                  </div>
                </div><!-- row -->
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Comprobante de pago: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="file" class="filestyle" data-buttonname="btn-secondary" name="inp_comprobante_pago" id="inp_comprobante_pago" accept="image/*,application/pdf" required>
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label" for="metododepago1">Seleccione cómo realizó el pago</label>
                    <!-- <i class="icon ion-information-circled col-sm-1 fa-lg" id="ejemplometododepago1" title="Clic para ver ejemplo de pago"></i> -->
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" name="metodo_de_pago_1" id="metododepago1" required>
                      <option value="" disabled selected>Seleccione un método de pago</option>
                      <option value="1">Pago en cuenta referenciada</option>
                      <option value="2">Pago en ventanilla cuenta general</option>
                      <option value="4">Pago en cajero automático</option>
                      <option value="5">Pago en en departamento de cobranza</option>
                      <option value="6">Transferencia eletrónica</option>
                    </select>
                  </div>
                </div>
                <div class="row mg-t-20" style="display:none" id="mostrarmetododepago">
                  <label class="col-sm-4 form-control-label" for="metododepago">Seleccione el método de pago</label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" name="metodo_de_pago" id="metododepago" required>
                      <option id="noselect" value="" disabled selected>Seleccione una forma de pago</option>
                      <option id="pagoenefectivo" style="display:none" value="Pago en efectivo">Pago en efectivo</option>
                      <option id="chechenominativo" style="display:none" value="Cheque nominativo">Cheque nominativo</option>
                      <option id="tarjetadecredito" style="display:none" value="Tarjeta de crédito">Tarjeta de crédito</option>
                      <option id="tarjetadedebito" style="display:none" value="Tarjeta de débito">Tarjeta de débito</option>
                      <option id="transferenciaelectronica" style="display:none" value="Transferencia eletrónica">Transferencia electrónica</option>
                      <option id="paypal" style="display:none" value="Paypal">Paypal</option>
                    </select>
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label" for="metododepago">En que banco realizó el pago</label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <select class="form-control" name="crearbancodedeposito" id="crearbancodedeposito" required>
                      <option value="">Seleccione...</option>
                      <option value="Banorte 0823622605">Banorte 0823622605</option>
                      <option value="Inbursa 50060654011">Inbursa 50060654011</option>
                      <option value="No aplica">No aplica</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col text-right">
                    <u style="cursor:pointer;"><p class="mb-0 mt-2 text-info" id="informacionPago" style="display:none" >Clic aquí para ver ejemplo de pago</p></u>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Folio /<br> N° autorización /<br> N° cajero <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input class="form-control" type="text" required name="inp_folio_pago" id="inp_folio_pago"></input>
                  </div>
                </div>
                <div id="notifica_parcialidades" class="col-12">
                </div>
                <div id="notifica_fechap"></div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Monto por pagar: <span id="tipodemonedaporpagar"></span> </label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input class="form-control" type="tel" required id="inp_monto_pago_show" disabled></input>
                  </div>
                </div>
                
                <div class="alert alert-bordered alert-warning mt-3 text-dark" role="alert">
                  <strong class="d-block d-sm-inline-block-force">Atención!</strong> Verifica que el monto que aparece en tu comprobante de pago sea el mismo que el que estás reportando.
                </div>
                
                <div class="row mg-t-15">
                  <label class="col-sm-4 form-control-label">Monto a reportar: </label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input class="form-control" type="tel" id="inp_monto_pago" name="inp_monto_pago"  data-prefix="$ " value="$ 0.00" required></input>
                  </div>
                </div>
              </div><!-- form-layout -->
			  
			  <div class="row justify-content-end mt-4">
				<button type="submit" class="btn btn-primary tx-size-xs">Enviar</button>
				<button type="button" data-dismiss="modal" class="btn btn-secondary tx-size-xs mr-3">Cancelar</button>
			  </div>
            </form>
          </div><!-- modal-body -->
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div class="modal fade modal-left" id="modalVerEjemploPago" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content col-sm-12 col-lg-12">
          <div class="modal-header">
            <h4 class="modal-title m-0" id="myLargeModalLabel">Ejemplo de pago:</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          </div>
          <div class="modal-body">
            <label for="avisoEjemploComprobante"><h5><strong>A continuación se señala el dato a ingresar en el formulario de notificar pago.</strong></h5></label>

            <img class="col-sm-10 center" src="" name="verEjemploComprobante" id="verEjemploComprobante" alt="Responsive image" >

            <div class="text-right">
              <button type="button" name="cancelarEjemploCompronate" id="cancelarEjemploCompronate" class="btn btn-secondary waves-effect m-1-5">Cerrar</button>
            </div>

          </div>
        </div>
      </div>
    </div>
    <?php require 'plantilla/footer.php'; ?>
  </div><!-- br-mainpanel -->
  <!-- ########## END: MAIN PANEL ########## -->

  <script src="../lib/jquery/jquery.js"></script>
  <script src="../lib/popper.js/popper.js"></script>
  <script src="../lib/bootstrap/bootstrap.js"></script>
  <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../lib/moment/moment.js"></script>
  <script src="../lib/jquery-ui/jquery-ui.js"></script>
  <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
  <script src="../lib/peity/jquery.peity.js"></script>

  <script src="../js/sweetalert.min.js"></script>
  <script src="../js/bootstrap-filestyle.js"></script>

  <script src="../lib/datatables/jquery.dataTables.js"></script>
  <script src="script/inicializar_datatable.js"></script>

  <script src="../js/jquery.maskMoney.js"></script>

  <script>
    $(document).ready(function() {
      /*
      [data:{action:'action'}]
      [url]
      [class_i || id_tab]
      [debug:(true || false)]
      */
      dataMaicol({
        id_tab: 'table_pagos_apli',
        url: '../../assets/data/Controller/planpagos/pagosControl.php',
        // data:{action:'obtener_pagos_aplicados'},
        // debug:false
      });
      dataMaicol({
        id_tab: 'tabla_conceptos_pagar'
      })
    })
  </script>

  <script src="../js/bracket.js"></script>
  <script src="script/jquery.payment.js"></script>
  <script type="text/javascript" src="https://conektaapi.s3.amazonaws.com/v0.5.0/js/conekta.js"></script>
  <!-- <script src="https://www.paypal.com/sdk/js?client-id=AfcFm_FJBcwJ0-Urg7O8Jb_E0LpsGoO2_Oy6CFCNHWTIrDm09VNo9kCl6VWiYT9GrlT2B_0f-LYwNHQD&currency=MXN" data-sdk-integration-source="button-factory"></script> -->
  <script>
    const user_info = JSON.parse('<?php echo json_encode($usr); ?>');
    $(".onlyNumer").on('keypress', function(evt) {
      if (evt.which < 46 || evt.which > 57) {
        evt.preventDefault();
      }
    });

    $(".normalText").on('keypress', function(evt) {
      specials = [209, 225, 233, 237, 241, 243, 250, 32];
      if ((evt.which >= 97 && evt.which <= 122) || (evt.which >= 65 && evt.which <= 90) || specials.includes(evt.which)) {} else {
        evt.preventDefault();
      }
    });
  </script>
  <script src="script/pagos_panel.js"></script>
  <script src="script/pagos_oxxo_conekta.js"></script>
  <script src="../../assets/js/controlescolar/ver_plan.js"></script>
  </body>

</html>
