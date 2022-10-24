<?php
session_start();
$file = fopen("app/utils/utils.txt","r") or die ("Error Fatal 1");
while(!feof($file)){

    $get = fgets($file);
}
$date = date('d-m-Y');
$usuario = $_SESSION["usuario"];

$id = $_SESSION['usuario']['estatus_acceso']; 
$dateNew = date('Ym');


$consecutive = explode('-',$get);
$fols= $consecutive[0];

if($fols != $dateNew){
    $newfols = $dateNew.'-'.($consecutive[1]+1);
    file_put_contents("app/utils/utils.txt", strval($newfols));
    $get = $newfols;
}



if(isset($_SESSION['usuario']) && $id == 2){
    include 'partials/header.php';
?>

<div class="wrapper">
    <input type="hidden" id="Area" value="<?=$usuario["persona"]["col_area"]?>">
    <div class="mx-4">
        <!-- Button trigger modal -->
        <ul class="list-unstyled pt-5 taps">
            <li class="d-inline btn-gin active">Solicitar Requisición</li>
            <li class="d-inline btn-gin">Pendientes</li>
            <li class="d-inline btn-gin">Aprobadas</li>
            <li class="d-inline btn-gin">Pagadas</li>
            <li class="d-inline btn-gin">Rechazadas</li>
            <li class="d-inline btn-gin">A Facturar</li>
            <li class="d-inline btn-gin">Archivadas</li>
            <li class="d-inline btn-gin">Facturas Incorrectos</li>
        </ul>
        <div class="card req">
            <h3 class="text-center">Requisiciones</h3>
            <div class="card-body">
                <div class="form-group">
                    <p class="mb-0">Tipo de requisición:</p>
                    <input type="radio" id="compra" name="op" value="compra">
                    <label for="compra">Compra</label><br>
                    <input type="radio" id="efectivo" name="op" value="efectivo" checked>
                    <label for="efectivo">Efectivo</label><br>  
                    <input type="radio" id="transferencia" name="op" value="transferencia">
                    <label for="transferencia">Transferencia</label><br>
                    <input type="radio" id="reposicion" name="op" value="reposicion">
                    <label for="reposicion">Reposición</label>
                </div>
                <div class="float-end"><b>Folio</b>: <span><?=$get;?></span></div><br>
                <div class="float-end"><b>Fecha</b>: <span><?=$date;?></span></div><br>
                
                <form id="requestSend">
                    
                    <table id="datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Proveedor</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Unidad</th>
                                <th class="text-center">Concepto</th>
                                <th class="text-center">Modelo</th>
                                <th class="text-center">Marca</th>
                                <th class="text-center">Link de ref / compra</th>
                                <th class="text-center">Precio unitario</th>
                                <th class="text-center">Montos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align:right">SubTotal: <span id="globalsSubT"></span></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="8" style="text-align:right">IVA: <span id="Iva"></span></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="8" style="text-align:right">Total: <span id="globaslT"></span></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    <button type="submit" id="request" class="border-0 p-2 btn btn-success float-end">Enviar Solicitud</button>
                    <button type="button" id="moreConcept" class="border-0 p-2 btn btn-primary">Agregar otro concepto +</button>
                </form>
            </div>
        </div>
        <div class="card sreq hidden">
            <h3 class="text-center" id="titleReq"></h3>
            <div class="card-body">
                <table id="datatableRes" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th class="text-center">Total</th>
                            <th>fecha de solicitud</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
     <!-- Modal -->
<!-- <div class="modal fade" id="proveedor" tabindex="-1" role="dialog" aria-labelledby="proveedorModalCenterTitle" aria-hidden="true">  
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <form id="register">
        <input type="hidden" id="idSelect">
      <div class="modal-header">
        <h5 class="modal-title" id="proveedorModalLongTitle">Registrar a proveedor</h5>
        <button type="button" class="btn-closed close" data-dismiss="modal" aria-label="Close" id="provNew">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label for="">Tipo de actividad</label>
                <select name="activity" id="changeAct" class="form-control" required>
                    <option value="" disabled selected>Selecciona tipo de actividad</option>
                    <option value="persona fisica">Persona física</option>
                    <option value="persona moral">Persona moral</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Regimen fiscal</label>
                <select name="regimen" id="reg" class="form-control" required>
                    <option value="" disabled selected>Selecciona regimen</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Nombre razón o social</label>
                <input name="nrazon" type="text" class="form-control" placeholder="Nombre Razon social" required>
            </div>
            <div class="form-group">
                <label for="">RFC</label>
                <input name ="rfc" type="text" class="form-control" placeholder="Registro federal de contribuyete" required>
            </div>
            <div class="form-group">
                <label for="">calle</label>
                <input type="text" class="form-control" placeholder="Calle" name="street" required>
            </div>
            <div class="form-group">
                <label for="">N° exterior</label>
                <input type="text" class="form-control" placeholder="N° exterior" required pattern="^[0-9]+" maxlength="10" name="numberE">
            </div>
            <div class="form-group">
                <label for="">N° interior</label>
                <input type="text" class="form-control" placeholder="N° interior" required pattern="^[0-9]+" maxlength="10" name="numberI">
            </div>
            <div class="form-group">
                <label for="">Colonia</label>
                <input type="text" class="form-control" placeholder="Colonia" name="neighborhood" required>
            </div>
            <div class="form-group">
                <label for="">Estado</label>
                <select id ="state" type="text" class="form-control" placeholder="Estado" name="stateD"  required>
                    <option value="">Selecciona un estado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Ciudad</label>
                <select id ="city" type="text" class="form-control" placeholder="Ciudad" name="city" required>
                </select>
            </div>
            <div class="form-group">
                <label for="">C.P</label>
                <input type="text" class="form-control" placeholder="C.P" required pattern="^[0-9]+" maxlength="5" name="cp">
            </div>
            <div class="form-group">
                <label for="">Correo electróncio</label>
                <input type="text" class="form-control" id="bank"  placeholder="Banco" name="bank" required>
            </div>
            <div class="form-group">
                <label for="">Teléfono</label>
                <input type="text" class="form-control" id="bank"  placeholder="Banco" name="bank" required>
            </div>
            <div class="form-group">
                <label for="">Nombre del banco</label>
                <input type="text" class="form-control" placeholder="Banco" name="bank" required>
            </div>
            <div class="form-group">
                <label for="">N° de Cuenta</label>
                <input type="text" class="form-control" placeholder="Cuenta" name="acountB" required>
            </div>
            <div class="form-group">
                <label for="">N° de CLABE</label>
                <input type="text" class="form-control" placeholder="CLABE" name="clabeB" required>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
    </div>
  </div>
</div> -->
<div class="modal" id="breakdown">
            <div class="card">
                <button class="btn-closed close">X</button>
                <div class="card-body">
                    <p class="mb-0"><b>Folio</b>: <span id="folio"></span></p>
                    <p id="dateR">Fecha</p>
                    <table id="datatableBKD" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Proveedor</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Concepto</th>
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Link ref</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th># de serie</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align:right">Total:</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <div class="float-end mb-2"><button class="btn btn-danger hidden" id="saveSerie">Guardar n° de serie(s)</button></div>
                        <form id="formSeries">

                        </form>
                    </table>
                    <div class="totals"></div>
                    <input id="optionsB" type="hidden"/>
                </div>
            </div>
        </div>
        <div class="modal" id="comprRq">
            <div class="card">
                <button class="btn-closed close close_save">X</button>
                <div class="card-body">
                    <div class="w-50 mx-auto">
                        <img src="" alt="" class="w-100 hidden" id="previewImg1">
                        <canvas class="hidden" id="previewPDF1"></canvas>
                    </div>
                    <div class="form-group" id="valuesDeclined">
                    </div>
                    <div class="mt-5 hidden" id="compNOK">
                        <form id="formValidate">
                            <label for="">Ingresa Motivo por el cual esta incorrecto</label>
                            <textarea name="errorDoc" placeholder="Redacta motivo por el cual esta incorrecto" class="form-control" required></textarea>
                            <button class="btn btn-success mt-3">Enviar</button>
                        </form>
                    </div>
                    <div class="mt-5 hidden" id="compOK">
                        <form id="formComp">
                            <input type="hidden" name="fileSave" value="saveFac">
                            <label for="">Ingresa Archivos de factura generada PDF y XML</label>
                            <input type="file" class="form-control mb-1" name="pdf" id="pdf" accept="application/pdf" required>
                            <input type="file" class="form-control" name="xml" id="xml" accept="text/xml" required>
                            <div class="col-sm-12 text-center mt-2"><canvas class="hidden" id="previewPDF"></canvas></div>
                            <button class="btn btn-success mt-3">Subir Facturas</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
include 'partials/footer.php';
}else{
    header('Location: ../log-out.php');
}
?>