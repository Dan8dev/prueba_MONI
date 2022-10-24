<?php
if( !session_id() ) session_start();
if(!isset($_SESSION["usuario"]) || ($_SESSION["usuario"]['idTipo_Persona'] != 31 && $_SESSION["usuario"]['idTipo_Persona'] != 3 )){
    header("Location: ../index.php");
    die();
}
require_once( "cx.php" );
require_once( "listadoDocumentos.php" );
$con = conect();
$validacion = ''; $comentario = ''; $separador = " ";

                              if( isset($_GET['p']) && $_GET['p'] == 'vexp' ){
                                $con = conect();
                                //$sql = "SELECT DISTINCT idAsistente, nombre, aPaterno, aMaterno FROM a_prospectos WHERE idAsistente = ".$_GET['id_prospectos'];
                                $sql = "SELECT DISTINCT id_afiliado, nombre, apaterno AS aPaterno, amaterno AS aMaterno FROM documentos, afiliados_conacon 
                                 WHERE documentos.id_prospectos = afiliados_conacon.id_afiliado AND afiliados_conacon.id_afiliado = ".$_GET['id_prospectos'];
                                $resultado = $con->query($sql);
                                $fila = $resultado->fetch_assoc();
                                
                                if( $resultado->num_rows > 0 ) {
                                ?>
                                    <div id="expediente">
                                    <h3><span style="text-transform: uppercase"><?=$fila['aPaterno'].' '.$fila['aMaterno'].', '.$fila['nombre']?></span></h3>
                                      <?php
                                      $resultado = listarExpedienteAlumnos( $_GET['id_prospectos'] );
                                      if( $resultado->num_rows > 0 ){?>
                                      <!--<form method="post" action="validar.php?t=<?=$resultado->num_rows?>&id_prospectos=<?=$_GET['id_prospectos']?>">-->
                                      <form id="myform">
                                      <table id="listado_prospectos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; width: 100%;">
                                      <thead>
                                              <tr>
                                                <th>DOCUMENTO</th>
                                                <th>FECHA DE ENTREGA</th>
                                                <!--<th>TIPO</th>-->
                                                <th>VALIDACIÓN</th>
                                                <th>FECHA DE VALIDACIÓN</th>
                                                <th>COMENTARIO</th>
                                                <th>OPCIONES</th>
                                              </tr>
                                            </thead>
                                        <?php
                                        $i = 1;
                                          while( $fila = $resultado->fetch_assoc() ){
                                          ?>
                                            <tr>
                                                <td><?=nombreDocumento($fila['id_documento'])?><input type="hidden" name="iddocumento<?=$i?>" value="<?=$fila['id']?>"></input></td>
                                                <!--<td><?=$fila['tipo_estudio']?></td>-->
                                                <td><?=$fila['fecha_entrega']?></td>
                                                <td title="">
                                                  <?php
                                                  if( $fila['validacion'] == 0 ){?>
                                                  <select id="validacion<?=$fila['id']?>" name="validacion<?=$i?>" class="form-control" title="">
                                                    <option value="">Sin validar</option>
                                                    <option id="validar" value="1">Aceptado</option>
                                                    <option value="2">Rechazado</option>
                                                  </select>
                                                  <?php } else echo estadosDocumentos( $fila['validacion'] );?>
                                                </td>
                                                <td><?=$fila['fecha_validacion']?></td>
                                                <td><input title="<?=$fila['comentario']?>" type="text" name="comentario<?=$i?>" value="<?=$fila['comentario']?>" <?php if( $fila['validacion'] == 1 ) echo "disabled" ?> class="form-control"></input></td>
                                                <td id='<?php echo $i;?>'><a href="../siscon/app/lista_documentos/<?=$_GET['id_prospectos']?>/<?=$fila['nombre_archivo']?>" class="btn btn-primary waves-effect waves-light" target="_blank"><i class="fas fa-file-download"></i> Descargar</a></td>
                                            </tr>
                                        
                                        <?php $i++; }//while ?>
                                        </table>
                                        <button type="button" onClick="validacion(<?=$_GET['id_prospectos']?>, <?=$resultado->num_rows?>)" class="btn btn-primary waves-effect waves-light">Guardar cambios</button>
                                        <button type="reset" class="btn btn-secondary waves-effect waves-light" onClick="javascript:document.getElementById('expediente').style='display:none'">Cancelar</button>

                                        </form>
                                      <?php }
                                        ?>                                      
                                    </div>
                                  <?php } else echo '<div class="alert alert-danger alert-dismissible fade show">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            El alumno seleccionado no ha subido documentos.
                                        </div>'; ?>
                                    <hr>
                              <?php }?>

