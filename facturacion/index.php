<?php
session_start();

$us = $_SESSION["usuario"]['idTipo_Persona'];
if(isset($us) && $us == 9){


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="design/css/styles.css">
    <link rel="icon" type="imge/png" href="../assets/images/favicon.ico">
</head>
<body>
   <div class="nav-bar">
        <a href="#" class="logo">
            <img src="../assets/images/logo-light.png" class="logo-lg" alt="" height="26">
        </a>
        <a id="logout" href="../log-out.php">Cerrar Sesión</a>
   </div>
   <div class="wrapper my-5">
       <div class="container master">
           <div class="row">
               <h3>Facturación</h3>
               <div class="col-12">
                   <ul class="list-unstyled pt-5">
                       <li class="d-inline btn-gin active">Por Aprobar</li>
                       <li class="d-inline btn-gin">Solicitudes de Cambios</li>
                       <li class="d-inline btn-gin">Por facturar</li>
                       <li class="d-inline btn-gin">Facturado</li>
                   </ul>
                   <div class="approved hidden">                   
                   </div>
                   <div class="changeRes hidden">
                   </div>
                   <div class="out-billing hidden">
                    <div class="card">
                        <div class="card-body">
                        <div class="text-end mt-2 mb-3"><button id="globals" class="btn-plus p-2 border-0" disabled>Subir factura global</button></div>
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table id="datatable-subirfactura" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Institucion</th>
                                            <th>Razón social</th>
                                            <th>RFC</th>
                                            <th>Concepto</th>
                                            <th>Fecha de pago</th>
                                            <th>Referencia</th>
                                            <th>Forma de pago</th>
                                            <th>Método de pago</th>
                                            <th>Moneda</th>
                                             <th>Monto de pago MXN</th>
                                            <th>Monto de pago USD</th>
                                            <th>Facturas</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <!--<th colspan="10" style="text-align:right"></th>-->
                                               <th colspan="12" style="text-align:right"></th>
                                                <th></th>
                                            </tr>
                                            
                                        </tfoot>
                                    </table>
                                    <div class="totals"></div>
                                    <div class="totalsD"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                   </div>
               </div>
               </div>
           </div>
       </div>

       <div class="modal declined">
           <div class="card">
               <div class="card-header position-relative">
                   <h5>Envia un comentario al usuario por el rechazo de su información fiscal</h5>
                   <button type="button" class="btn-closed position-absolute top-0 end-0 border-0 background-0">X</button>
               </div>
               <div class="card-body">
                   <form id="declined">
                       <div class="form-group">
                           <input type="hidden" name="idConst" id="idConst">
                       </div>
                       <h6>Selecciona ó redacta el motivo de rechazo</h6>
                       <div class="form-group">
                           <select name="reason" id="reason" type="text" class="form-control">
                               <option value="" disabled selected>Selecciona un motivo</option>
                               <option value="El documento enviado no es una constancia de situación fiscal, por favor envíelo nuevamente">El documento enviado no es una constancia de situación fiscal, por favor envíelo nuevamente</option>
                               <option value="El documento enviado tiene errores de lectura y escritura, por favor envíelo nuevamente">El documento enviado tiene errores de lectura y escritura, por favor envíelo nuevamente</option>
                               <option value="Datos fiscales incorrectos, no coinciden con su constancia de situación fiscal, por favor verifique la información y vuelva a enviarla">Datos fiscales incorrectos, no coinciden con su constancia de situación fiscal, por favor verifique la información y vuelva a enviarla</option>
                               <option value="La constancia de situación fiscal debe ser de fecha actual">La constancia de situación fiscal debe ser de fecha actual</option>
                           </select>
                           <div class="mt-3">
                           <textarea  id="reasonTyping" placeholder="Redacta el motivo de rechazo" requiered></textarea>
                           </div>
                           
                       </div>
                       <button type="button" class="btn-closed d-block mt-2 btn-plus btn-danger float-end border-0 m-3 p-2">Revisar de nuevo</button>
                       <button type="button" class="d-block mt-2 float-end btn-success border-0 p-2" id="btn-submit" disabled>Declinar</button>
                   </form>
               </div>
           </div>
       </div>
        <!-- center modal form alta eventos -->
      <div class="modal" id="sendBilling">
                <div class="card">
               <div class="card-header position-relative">
                   <h5>Selecciona los archivos a subir</h5>
                   <button type="button" class="btn-closed position-absolute top-0 end-0 border-0 background-0">X</button>
               </div>
               <div class="card-body">
                        <form id="formularioFactura">
                          <div class="form-group" id="conFac">
                          </div>
                          <div class="form-group row">
                            <label for="imagen" class="col-sm-2 control-label">PDF</label>
                            <div class="col-sm-10">
                              <input type="file" class="form-control" name="pdf" id="pdf" accept="application/pdf" required>
                              <div class="clave alert alert-info">Sube el archivo generado desde el portal de SAT</div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="imagen" class="col-sm-2 control-label">XML</label>
                            <div class="col-sm-10">
                              <input type="file" class="form-control" name="xml" id="xml" accept="text/xml" required>
                              <div class="clave alert alert-info">Sube el archivo generado desde el portal de SAT</div>
                            </div>
                            <div class="col-sm-12 text-center"><canvas id="previewPDF"></canvas></div>
                          </div>
                          <div class="form-group mb-5">
                            <button class="btn btn-success waves-effect waves-light float-end me-2">Enviar</button>
                            <button type="button" class="btn-closed btn btn-plus btn-danger waves-effect waves-light float-end">Cerrar</button>
                          </div>
                        </form>
               </div>
           </div>
      </div>

      <div class="modal" id="DataFact">
                <div class="card">
               <div class="card-header position-relative">
                   <h5>Datos de Facturación</h5>
                   <button type="button" class="btn-closed position-absolute top-0 end-0 border-0 background-0">X</button>
               </div>
               <div class="card-body">
                    <div id="showDatasBill">
                        <h5>Chuck Levien</h5>
                        <p>Domicilio:</p>

                    </div>
               </div>
           </div>
      </div>
      <div class="modal" id="previewsFact">
                <div class="card">
               <div class="card-header position-relative">
                   <h5>Datos de Facturación</h5>
                   <button type="button" class="btn-closed position-absolute top-0 end-0 border-0 background-0">X</button>
               </div>
               <div class="card-body">
                    <div id="showDatasBill">
                        <canvas id="previewBills"></canvas>
                    </div>
                    <button type="button" class="btn btn-closed btn-danger btn-plus me-2 d-inline-block float-end">Revisar de nuevo</button>
                    <form id="deleteFcts" class="d-inline-block float-end">
                        <input type="hidden" id="deletefacts" name="deletefacts">
                    <button class="btn btn-success" type="submit">Sí eliminar</button>
                    </form>
                </div>
           </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.0.943/build/pdf.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
   <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 
   <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script> 
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.18.0/dist/sweetalert2.all.min.js"></script>
   <script src="design/scripts.js"></script>
</body>
</html>

<?php
}else{
    header('Location: ../log-out.php');
}
?>
