<?php

session_start();

$usuario = $_SESSION["usuario"];


$id = $_SESSION['usuario']['estatus_acceso']; 

if(isset($_SESSION['usuario']) && $id != 2){

    include 'partials/header.php';
?>
<div class="wrapper">

    <div class="mx-4">
        <?php 

        switch($id){
            case 4:
            ?>
                <ul class="list-unstyled pt-5 taps">
                    <li class="d-inline btn-gin active">Aprobadas</li>
                </ul>
            <?php   
                break;
            default:
                ?>
                <ul class="list-unstyled pt-5 taps">
                    <li class="d-inline btn-gin active">Pendientes</li>
                    <li class="d-inline btn-gin">Aprobadas</li>
                    <li class="d-inline btn-gin">Pagadas</li>
                    <li class="d-inline btn-gin">Comparar Factura / Pago</li>
                    <li class="d-inline btn-gin">Archivadas</li>
                    <li class="d-inline btn-gin">Comprobantes incorrectos</li>
                </ul>
                <?php
            break;
        }
        ?>
        <!-- Button trigger modal -->
        <div class="card sreq">
            <h3 class="text-center" id="titleReq">Pendientes</h3>
            <div class="card-body">
                <table id="datatableRes" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre de quien solicita</th>
                            <th>Folio</th>
                            <th>fecha de solicitud</th>
                            <th>fecha de aprobación</th>
                            <th>fecha de pago</th>
                            <th>fecha de rechazo</th>
                            <th>Total</th>
                            <th>Departamento</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="totals"></div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal" id="breakdown">
            <input type="hidden" value="<?=$id?>" id="band">
            <div class="card">
                <button class="btn-closed close">X</button>
                <div class="card-body">
                    <h6 class="mb-0" id="prename">Nombre:</h6>
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
                                <th>Número de movimiento</th>
                                <th>Centro de costo</th>
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
                        <div class="float-end mb-1">
                        <button class="btn btn-gin hidden" id="fileSignApproved" data-toggle="modal" data-target="#comSign">Subir firma de requisición</button>
                        <button class="sendReq btn btn-success" id="approved" disabled>Aprobar</button>
                        <button class="sendReq btn btn-danger" id="declined" disabled>Rechazar</button>
                        </div>
                    </table>
                    <div class="totals"></div>
                    <input id="optionsB" type="hidden"/>
                    <form id="formAdmin">
                    </form>
                    <div class="form-group hidden" id="modalDeclined">
                        <label for="">Motivo de rechazo 1000 caracteres</label>
                        <textarea name="" id="decline_reason" placeholder="redacta el motivo de rechazo" class="form-control"></textarea>
                        <button class="btn btn-danger mt-1" id="sendDeclined">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="comprRq">
            <div class="card">
                <button class="btn-closed close" id="close_save">X</button>
                <div class="card-body">
                    <div class="mt-5">
                        <form id="formComp">
                            <input type="hidden" name="fileSave" value="saveComp">
                            <label for="">Ingresa fecha del pago</label>
                            <input type="date" class="form-control mb-1" id="datePago" name="datePago" placeholder="ingresa la fecha en que se realizo el pago" required>
                            <label for="">Adjunta comprobante del pago</label>
                            <input type="file" class="form-control" name="pdf" id="pdf" accept="application/pdf,image/*" required>
                            <div class="col-sm-12 text-center mt-2"><img src="" alt="" class="hidden" id="previewImg"></div>
                            <div class="col-sm-12 text-center mt-2"><canvas class="hidden" id="previewPDF"></canvas></div>
                            <button class="btn btn-success mt-3">Subir Comprobante</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="comPayBill">
            <div class="card">
                <button class="btn-closed close" id="close_comp">X</button>
                <div class="card-body">
                <div class="comPayBill row">
                    <div class="compPay col-sm-6">
                        <img src="" alt="" class="w-100 hidden" id="previewImg1">
                        <canvas class="hidden" id="previewPDF1"></canvas>
                    </div>
                    <div class="compBill col-sm-6">
                        <img src="" alt="" class="w-100 hidden" id="previewImg2">
                        <canvas class="hidden" id="previewPDF2"></canvas>
                    </div>
                    <div class="mt-2 mb-1">
                    <button class="btn btn-success hidden" id="newSendCom">Subir nuevo archivo</button>
                    <form id="formValidate" class="hidden">
                        <div class="form-group" id="valuesDeclined">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Ingresa motivo de rechazo</label>
                            <textarea name="errorDoc" placeholder="Redacta motivo por el cual esta incorrecto si es el caso" class="form-control" required></textarea>
                        </div>
                        <button type="button" class="valuesD btn btn-success me-2" id="correct">Correcto</button>
                        <button type="button" class="valuesD btn btn-danger" id="uncorrect">incorrecto</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="comSign" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content pb-4">

                            <div class="modal-header">
                                <h3 class="modal-title m-0" id="CustomLabel"></h3>
                                <button type="button" class="close" id="closeSign" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            <form id="formSign">
                                <input type="hidden" name="fileSave" value="saveSigns">
                                <label for="">Ingresa fecha de aprobación</label>
                                <input type="date" class="form-control mb-1" id="datePago" name="datePago" required>
                                <label for="">Adjunta archivo que justifique la requisición</label>
                                <input type="file" class="form-control" name="pdf" id="pdf" accept="application/pdf,image/*" required>
                                <div class="col-sm-12 text-center mt-2"><img src="" alt="" class="hidden" id="previewImg"></div>
                                <button class="btn btn-success mt-3">Subir Comprobante</button>
                            </form>
                        </div><!-- /.modal-content -->
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