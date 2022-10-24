
  <div class="col-md-12">
    <div class="card">
      <div class="card-body ml-10">
        <div class="row mx-4">
          <?php
          require_once 'data/Model/AfiliadosModel.php';
          $porospM = new Afiliados();
          
          $pago = $porospM->consultar_pago_prospecto($idusuario);
          echo "<div style='display:none'><pre>";
          print_r($pago);
          echo "</div></pre>";
	          ?>
            <?php if(3==4||$pago['data']['id_asistente']==62 || $pago['data']['id_asistente']==385|| $pago['data']['id_asistente']==551|| $pago['data']['id_asistente']==552):
              $talleres_reservados = $porospM->asistente_talleres_reservados($pago['data']['id_asistente']);
              if(empty($talleres_reservados['data'])):
                ?>
                <h4 class="mb-3">Completa tu registro seleccionando los talleres a los que te interesa asistir:</h4>
                <form id="form_apartar_talleres">
                  <div class="row" id="content-form-chk">
                    
                  </div>
                  <div class="row">
                    <div class="col-4 ml-auto">
                      <button class="btn btn-success" type="button" id="btn-confirm-talleres">Continuar</button>
                    </div>
                  </div>
                </form>
              <?php else:
                $contenQr = json_encode(['id_persona'=>$pago['data']['id_asistente'], 'id_evento'=>$pago['data']['id_evento']]);
              ?>
                <!-- CODIGO QR -->
                <div class="col-12">
                  <h3 style="color: #4479AC">Este QR será tu llave de acceso para el evento</h3>
                </div>
                <div class="card shadow-base card-body pd-25 bd-0 mg-t-20">
                  <div class="card bd-0">
                      <input  id="text" type="hidden" value='<?php echo($contenQr) ?>'  />
                      <div class="img_credencial m-auto" id="qrcode"></div>
                  </div><!-- card -->
                </div><!-- card -->
              <?php endif; ?>

              <?php else:?>
		  
		      <div class="col-sm-6">
            <div class="card-body">
              <div class="card m-b-30 card-body" >
                <h4 class="text-dark mt-0">CONGRESO + PRE-CONGRESO</h4>
                <h4 class="card-title text-dark mt-0">Acceso general + pre-congreso</h4>
                <p class="card-text"></p>
                <div class="row">
                  <div class="col-sm-6">
                    <p class="card-text"><small class="text-muted">Obten tu lugar por:</small></p>
                  </div>
                  <div class="col-sm-6">
                    <p class="card-text text-success">$3,000.00 <small>MXN</small></p>
                  </div>
                  <div class="col-12">
                    <!-- BOTON PARA PAGO -->
                    <div id="smart-button-container">
                      <div style="text-align: center;">
                        <div id="paypal-button-container-acceso-general"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="card-body">
              <div class="card m-b-30 card-body" >
                <h4 class="text-dark mt-0">APARTAR CONGRESO + PRE-CONGRESO</h4>
                <h4 class="card-title text-dark mt-0">Aparta con $500</h4>
                <p class="card-text"></p>
                <div class="row">
                  <div class="col-sm-6">
                    <p class="card-text"><small class="text-muted">Aparta tu lugar por:</small></p>
                  </div>
                  <div class="col-sm-6">
                    <p class="card-text text-success">$500.00 <small>MXN</small></p>
                  </div>
                  <div class="col-12">
                    <!-- BOTON PARA PAGO -->
                    <div id="smart-button-container">
                      <div style="text-align: center;">
                        <div id="paypal-button-container-apartar-lugar"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

