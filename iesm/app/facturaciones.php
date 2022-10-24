<?php

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
   
    <div class="card card-body pd-x-25" id="container-conceptos-pago">
      <div class="tab-content br-profile-body">
        <div class="tab-pane fade active show" id="posts">
          <!-- CONTENEDOR PAGOS -->
          <div class="row">
            <div class="col-sm-12">
              <h3>Facturación</h3>
              <div class="clave alert alert-info hidden" id="info_datos">Los datos requeridos para emitir facturas <b>CFDI 4.0:</b><br>
                <ul>
                  <li>RFC</li>
                  <li>Nombre completo</li>
                  <li>Código postal de tu domicilio fiscal</li>
                  <li>Régimen en que se tributa</li>
                  <li>Uso fiscal que le darás a la factura</li>
                </ul>
              </div>
              <form id="form-billing">
                <div class="form-group">
                  <label for="">Nacionalidad</label>
                  <select name="nationality" id="onchangeNat" class="form-control" required>
                    <option value="" selected disabled>Selecciona una opción</option>
                    <option value="Mexico">Residente Mexicano</option>
                    <option value="Estados Unidos">Residente Extranjero</option>
                  </select>
                </div>
                <div id="select_Pf_Pm" class="hidden">
                  <div class="form-group">
                    <label for="">Tipo de persona</label>
                    <select name="activity" id="changeAct" class="form-control">
                      <option value="">Selecciona tipo de actividad</option>
                      <option value="persona fisica">Persona física</option>
                      <option value="persona moral">Persona moral</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">Regimen fiscal</label>
                    <select name="regimen" id="reg" class="form-control">
                      <option value="">Selecciona regimen</option>
                     
                    </select>
                  </div>
                  <!--<div class="form-group">
                    <label for="">Usos</label>
                    <select name="usos" id="usos" class="form-control">
                      <option value="">USOS</option>
                     
                    </select>
                  </div>-->
                </div>
                <div class="hidden" id="dataShow">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="">RFC</label>
                    <input id ="rfc" type="text" class="form-control" placeholder="RFC" name="rfc" required  pattern="[A-Za-z0-9]+">
                  </div>
                  <div class="form-group hidden" style="margin-bottom: 10px;" id="idf">
                    <label for="">Número de reg. ID Fiscal</label>
                    <input id ="idfiscal" type="text" class="form-control" placeholder="ID Fiscal" name="idfiscal">
                  </div>
                  <div class="form-group" style="margin-bottom: 10px;" id="rz">
                    <label for="">*Nombre o razón social</label>
                    <div class="clave alert alert-info hidden" id="info_nombre">*Debe registrase tal y como se encuentra en la <b>Cédula de Identificación Fiscal y Constancia de Situación Fiscal</b>, respetando números, espacios y signos de puntuación</div>
                    <input id ="name" type="text" class="form-control" placeholder="Nombre o razón social" name="nrazon" required>
                  </div>
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="">Dirección de facturación(Fiscal)</label>
                    <div class="row">
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Código postal</label>
                      <input id ="cp" type="text" class="form-control" placeholder="C.P." name="cp" required pattern="^[0-9]+" maxlength="5">
                      </div>
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Estado</label>
                      <select id ="state" type="text" class="form-control" placeholder="Estado" name="stateD"  required>
                        <option value="">Selecciona un estado</option>
                      </select>
                      </div>
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Ciudad</label>
                      <select id ="city" type="text" class="form-control" placeholder="Ciudad o Municipio" name="city" required>
                      </select>
                      </div>
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Población</label>
                      <input id ="pob" type="text" class="form-control" placeholder="Población" name="pob" required>
                      </div>
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Colonia</label>
                      <input id ="bd" type="text" class="form-control" placeholder="Colonia" name="bd" required>
                      </div>
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Calle</label>
                      <input id ="street" type="text" class="form-control" placeholder="Calle" name="street" required>
                      </div>
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Número exterior</label>
                      <input id ="numberD" type="text" class="form-control" placeholder="Número exterior" name="numberD" required pattern="[A-Za-z0-9\s]+" maxlength="10">
                      </div>
                      <div class="form-group col-sm-6" style="margin-bottom: 10px;">
                      <label for="">Número interior</label>
                      <input id ="numberI" type="text" class="form-control" placeholder="Número interior" name="numberI" pattern="[A-Za-z0-9\s]+" maxlength="10">
                      </div>
                    </div>
                  </div>
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="">Correo electrónico</label>
                    <input id ="email" type="email" class="form-control" placeholder="Correo electrónico" name="email" required>
                  </div>
                  <div class="form-group hidden" id="div_cfdi" style="margin-bottom: 10px;">
                    <label for="">CFDI</label>
                    <select id ="cfdi" name="cfdi" class="form-control">
                      <option value="">Uso de CFDI</option>
                      
                    </select>
                  </div>
                </div>
                <div class="form-group" id="ConsFis">
                  <div>
                    <p class="d-inline-block">Ejemplo de constancia fiscal</p>
                    <button type="button" id="viewer" class="btn-plus rounded-circle d-inline-block">i</button>
                  </div>
                  <div class="modal viewer">
                    <div class="card example_const">
                      <div class="card-body">
                        <h4>Ejemplo de constancia fiscal</h4>
                        <div><canvas id="my_canvasexample"></canvas></div>
                        <button type="button" class="btn-closed btn-danger btn-plus mt-2 d-block float-end">Cerrar</button>
                      </div>
                    </div>
                  </div>
                  <label for="">Sube tu constancia fiscal</label>
                  <input id="pdf" type="file" class="form-control" accept="application/pdf">
                </div>
                <button type="submit" id="btn_save" class="btn btn-primary" style="margin-left: auto;display:block">Guardar Datos</button>
              </form>
              <form id="inputEnable" class="hidden">
                <h6>¡Aviso importante!</h6>
                  <ul style="margin-top: 10px;">
                    <li>Si cambias información de tu situación fiscal tienes 10 días para solicitarnos dicho cambio en el sistema y tus facturas se generen correctamente.</li>
                    <li>Una vez elaborada tu factura no habra cancelaciones.</li>
                    <li>Solo se elabora la factura dentro del mes en curso.</li>
                  </ul>
                <div class="form-group">
                  <input type="checkbox" name="change_request" id="changed">
                  <label for="">Solicitar cambio de datos fiscales</label>
                </div>
              </form>
            </div>
            <div class="col-sm-12 overflow-auto">
              <h5 class="tx-center mb-3">Facturas a Descargar</h5>
              <section id="generaciones_disp">
              </section>

              <table id="tabla_facturas_desc" class="table table-striped table-bordered dt-responsive">
                <thead>
                  <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Fecha de pago</th>
                    <th>Referencia</th>
                    <th>Método de pago</th>
                    <th>Descargar PDF</th>
                    <th>Descargar XML</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>

              <hr class="d-none d-sm-block">

            </div>
         <!-- tab-pane -->
      </div><!-- br-pagebody -->



    </div><!-- modal -->
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
  <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.0.943/build/pdf.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.18.0/dist/sweetalert2.all.min.js"></script>
  <script src="../../facturacion/design/scripts.js"></script>
  </body>

</html>
