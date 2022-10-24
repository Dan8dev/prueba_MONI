<?php 
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
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

  require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
  $generaciones_asignadas = $afiliados->cunsultar_si_generacion($_SESSION['alumno']['id_prospecto']);
    /* $fechafinmembresia=$afiliados->fechafinmembresia($usuario['data']['idAsistente']);
    $fechaactual= date('Y-m-d');
    $fechafinmembresia=$fechafinmembresia['data']['finmembresia'];

    $datetime1 = new DateTime($fechaactual);
    $datetime2 = new DateTime($fechafinmembresia);
    $interval = $datetime1->diff($datetime2);
    $diasrestantes= substr($interval->format('%R%a días'), 1);
    $dias = rtrim($diasrestantes, ' días');
    if (rtrim($interval->format('%R%a días'), ' días')<0) {//si los dias restantes de afiliacion terminaron enviar a pagar membresia
      header('Location: pagos.php');
    } */
  $nplantillas = 3;
?>
<!DOCTYPE html>
<html lang="en">
  <?php require 'plantilla/header.php'; ?>
  <!-- CROPPER -->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>        
		<!--<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css"/>-->
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
		<!--<script src="https://unpkg.com/dropzone"></script>-->
		<script src="https://unpkg.com/cropperjs"></script>
  <style>
		.image_area {
		  position: relative;
		}

		.img-perfil {
		  	display: block;
		  	max-width: 100%;
		}

		.preview {
  			overflow: hidden;
  			width: 160px; 
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg-perfil{
  			max-width: 1000px !important;
		}

		.overlay-perfil {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}

		.image_area:hover .overlay-perfil {
		  height: 50%;
		  cursor: pointer;
		}

		.text-perfil {
		  color: #4479AC;
      font-weight: bold;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}
		</style>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <span class="breadcrumb-item active">EDITAR</span>
        </nav>
      </div><!-- br-pageheader -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">SMART ID</h4>
        <p class="mg-b-0">Edita tus datos podras compartir tu perfil y sera visible para cualquier persona</p>
      </div>

      <div class="br-pagebody">

        <div class="br-section-wrapper">         
          <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Editar perfil</h6>
            <div class="container" align="center">
              <br/>
              <h3 align="center">Cargar foto de perfil</h3>
              <br/>
              <div class="col-md-5">
                <div class="image_area"><!--Nuevo-->
                  <form method="post">
                    <label for="upload_image">
                    <?php 
                      $img = file_exists("img/afiliados/".$usuario["data"]["foto"]);
                      $foto = '';
                      if($img){
                        $foto = "img/afiliados/".$usuario["data"]["foto"];
                      }else{
                        $foto = "img/afiliados/defaultfoto.jpg";
                      }
                    ?>
                      <img src="<?php echo $foto; ?>" id="uploaded_image" class="card-img-bottom img-fluid img-responsive img-circle"/>
                        <div class="overlay-perfil">
                          <div class="text-perfil">Click para cargar tu imagen</div>
                        </div>
                      <input type="file" name="image" class="image" id="upload_image" accept=".jpg, .jpeg, .png" style="display:none"/>
                    </label>

                  </form>
                </div>

              </div>
            </div>

          <p class="mg-b-40 tx-gray-600">Llena correctamente el siguiente formulario</p>

          <div id="alert_2" style="display: none;position: fixed;bottom: 20%;z-index: 10;width: 70%;" class="alert" role="alert">
            <strong class="d-block d-sm-inline-block-force" id="alert_text"></strong>
          </div>

          <div id="wizard2">
            <h3>Datos personales</h3>
            <section>
              <p>Da click en el botón siguiente o anterior para continuar.</p>
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Nombre: <span class="tx-danger">*</span></label>
                <?php if($generaciones_asignadas > 0): ?>
                  <input class="form-control special disabled" type="text" disabled id="first_name"  title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR."> <i class="fa fa-info-circle float-right" title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR." aria-hidden="true"></i> 
                  <input id="firstname" name="firstname" type="hidden" readonly>
                <?php else: ?>
                  <input id="firstname" class="form-control special" name="firstname" placeholder="Ingresa Nombre" type="text" required>
                <?php endif; ?>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Apellido Paterno: <span class="tx-danger">*</span></label>
                <?php if($generaciones_asignadas > 0): ?>
                  <input class="form-control special disabled" type="text" disabled id="ape_paterno"  title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR."> <i class="fa fa-info-circle float-right" title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR." aria-hidden="true"></i> 
                  <input id="apaterno" name="apaterno" type="hidden" readonly>
                <?php else: ?>
                   <input id="apaterno" class="form-control special" name="apaterno" placeholder="Ingresa Apellido Paterno  " type="text" required>
                <?php endif; ?>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Apellido Materno: <span class="tx-danger">*</span></label>
                <?php if($generaciones_asignadas > 0): ?>
                  <input class="form-control special disabled" type="text" disabled id="ape_materno"  title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR."> <i class="fa fa-info-circle float-right" title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR." aria-hidden="true"></i> 
                  <input id="amaterno" name="amaterno" type="hidden" readonly>
                <?php else: ?>
                  <input id="amaterno" class="form-control special" name="amaterno" placeholder="Ingresa Apellido Materno" type="text" required>
                <?php endif; ?>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Fecha Nacimiento: <span class="tx-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input id="fnacimiento" type="date" class="form-control" name="fnacimiento" placeholder="AAAA-MM-DD">
                </div>      
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">CURP: <span class="tx-danger">*</span></label>
                <?php if($generaciones_asignadas > 0): ?>
                  <input class="form-control special disabled" type="text" disabled id="c_curp"  title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR."> <i class="fa fa-info-circle float-right" title="ESTE CAMPO HA SIDO BLOQUEADO PARA SU EDICIÓN POR MOTIVOS DE CONTROL ESCOLAR." aria-hidden="true"></i> 
                  <input id="curp" name="curp" type="hidden" readonly>
                <?php else: ?>
                  <input id="curp" class="form-control" name="curp" placeholder="Ingresa CURP" type="text" onkeyup="mayusculas(this);" maxlength="18">
                <?php endif; ?>
              </div><!-- form-group -->        
            </section>
            <h3>Contacto</h3>
            <section>
              <p>Da click en el botón siguiente o anterior para continuar.</p>
              <div class="form-group wd-xs-300">
                <label class="form-control-label">País: <span class="tx-danger">*</span></label>
                <select id="pais" class="form-control" name="pais" placeholder="Ingresa País" type="text" required>
                  
                </select>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Estado: <span class="tx-danger">*</span></label>
                <select id="estado" class="form-control" name="estado" placeholder="Ingresa Estado" type="text" required>
                  
                </select>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
                <input id="ciudad" class="form-control" name="ciudad" placeholder="Ingresa Ciudad" type="text" required>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Colonia: <span class="tx-danger">*</span></label>
                <input id="colonia" class="form-control" name="colonia" placeholder="Ingresa Colonia" type="text" required>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Calle: <span class="tx-danger">*</span></label>
                <input id="calle" class="form-control" name="calle" placeholder="Ingresa Calle" type="text" required>
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">C.P.: <span class="tx-danger">*</span></label>
                <input id="codigopostal" class="form-control onlynum" name="codigopostal" placeholder="Ingresa C.P." type="text" required maxlength="5" onKeyDown="onlynum(event, this)">
              </div><!-- form-group --> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Email: <span class="tx-danger">*</span></label>
                <input id="email" class="form-control" name="email" placeholder="Ingresa Email" type="email" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Célular: <span class="tx-danger">*</span></label>
                <input id="celular" class="form-control onlynum" name="celular" placeholder="Ingresa número de celular" type="text" required maxlength="10" onKeyDown="onlynum(event, this)">
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Facebook: <span class="tx-danger">*</span></label>
                <input id="facebook" class="form-control" onchange="validateurl(this)" name="facebook" placeholder="Ingresa liga a facebook" type="text" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Instagram: <span class="tx-danger">*</span></label>
                <input id="instagram" class="form-control" onchange="validateurl(this)" name="instagram" placeholder="Ingresa liga a instagram" type="text" required>
              </div><!-- form-group -->
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Twitter: <span class="tx-danger">*</span></label>
                <input id="twitter" class="form-control" onchange="validateurl(this)" name="twitter" placeholder="Ingresa liga a twitter" type="text" required>
              </div><!-- form-group -->
            </section>
            <h3>Académico</h3>
            <section>
              <div class="row">  
                <diV class="col-lg-6 col-sm-12 col-md-6">
                  <p>Ingresa tu grado académico</p>
                  <p>Da click en el botón siguiente o anterior para continuar.</p>
                  <form action="guardar_grado">
                    <div class="form-group wd-xs-300">
                      <labe>Selecciona último grado de estudios</label>
                      <select id="gradoestudios" name="gradoestudios" class="form-control select2" data-placeholder="Grado académico">
                        <option value="CURSOS">CURSOS</option>
                        <option value="SECUNDARIA">SECUNDARIA</option>
                        <option value="CERTIFICACIÓN">CERTIFICACIÓN</option>
                        <option value="BACHILLER">BACHILLER</option>
                        <option value="TSU">TSU</option>
                        <option value="PREPARATORIA">PREPARATORIA</option>
                        <option value="LICENCIATURA">LICENCIATURA</option>
                        <option value="POSGRADO">POSGRADO</option>
                      </select>
                    </div>
                      <!-- INPUT AGREGADO TIPO LICENC-->
                    <div class="form-group wd-xs-300">
                      <label class="form-control-label">Nombre: <span class="tx-danger">*En caso de contar con ella</span></label>
                      <input id="tipoLicen" class="form-control" name="tipoLicen" placeholder="Ingresa el nombre" type="text" >
                    </div>	
                    <div class="form-group wd-xs-300">
                      <label class="form-control-label">Cédula Profesional: <span class="tx-danger">*En caso de contar con ella</span></label>
                      <input id="cedulap" class="form-control" name="cedulap" placeholder="Ingresa Cédula profesional" maxlength="8" type="text" >
                    </div><!-- form-group --> 
                  </form>
                  <div class="form-layout-footer">
                    <button class="btn btn-primary" onclick="salvar_datos(this)">Guardar</button>
                    <button class="btn btn-secondary">Cancelar</button>
                  </div> 
                </div>
                <!-- tabla -->
                <div class="col-lg-6 col-sm-12 col-md-6">
                  <p>Grados académicos</p>
                  <p>Para editar o eliminar da clic en el ícono.</p>
                  <table style="box-shadow: -1px 1px 3px;" class="table table-bordered table-colored table-primary">
                    <thead>
                      <tr>
                        <th class="wd-35p">Grado</th>
                        <th class="wd-35p">Titulo</th>
                        <th class="wd-10p">Editar</th>
                        <th class="wd-10p">Eliminar</th>
                      </tr>
                    </thead>
                    <tbody id="table_grado">
                      <tr>
                        <td>Maestria</td>
                        <td>Ciencias de la computacion</td>
                        <td><i class="fa fa-edit"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                      </tr>
                      <tr>
                        <td>Licenciatura</td>
                        <td>Educación primaria</td>
                        <td><i class="fa fa-edit"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- fin tabla -->
              </div>
            </section>
            <h3>Experiencia Laboral</h3>
            <section>
              <div class="row">  
                <diV class="col-lg-6 col-sm-12 col-md-6">
                  <!-- <p>Da click en el botón siguiente o anterior para continuar</p> -->
                  <p>Ingresa tu experiencia laboral</p>
                  <p>Da click en el botón siguiente o anterior para continuar.</p>
                  <form action="guardar_laboral">
                    <div class="form-group wd-xs-300">
                    <label class="form-control-label">Fecha ingreso: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                        <input id="inicio-laboral" type="date" class="form-control" name="inicio_laboral" placeholder="AAAA-MM-DD" required="">
                      </div>  
                    </div>  
                    <div class="form-group wd-xs-300">
                    <label class="form-control-label">Fecha Egreso: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                        <input id="fin-laboral" type="date" class="form-control" name="fin_laboral" placeholder="AAAA-MM-DD" required="">
                      </div>  
                    </div>  
                    <div class="form-group wd-xs-300">
                    <label class="form-control-label">Empresa: <span class="tx-danger">*</span></label>
                      <input id="empresa" class="form-control" name="empresa" placeholder="Nombre Empresa" type="text" required>
                    </div> 
                    <div class="form-group wd-xs-300">
                    <label class="form-control-label">Puesto: <span class="tx-danger">*</span></label>
                      <input id="puesto" class="form-control" name="puesto" placeholder="Puesto que desarrollabas" type="text" required>
                    </div> 
                    <div class="form-group wd-xs-300">
                    <label class="form-control-label">Actividad Laboral: <span class="tx-danger">*</span></label>
                      <textarea id="actividadLaboral" name="actividadLaboral" class="form-control" rows="10" placeholder="Describe tu actividad laboral (Máx. 1000 carácteres)" required=""></textarea>
                    </div> 
                  </form>
                    <div class="form-layout-footer">
                      <button class="btn btn-primary" onclick="salvar_datos(this)">Guardar</button>
                      <button class="btn btn-secondary">Cancelar</button>
                    </div> 
                </div>
                <!-- tabla -->
                <div class="col-lg-6 col-sm-12 col-md-6">
                <p>Mi experiencia laboral</p>
                <p>Para editar o eliminar da clic en el ícono.</p>
                  <table style="box-shadow: -1px 1px 3px;" class="table table-bordered table-colored table-primary">
                    <thead>
                      <tr>
                        <th class="wd-35p">Empresa</th>
                        <th class="wd-10p">Editar</th>
                        <th class="wd-10p">Eliminar</th>
                      </tr>
                    </thead>
                    <tbody id="table_experiencia">
                      <tr>
                        <td>Microsoft</td>
                        <td><i class="fa fa-edit"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                      </tr>
                      <tr>
                        <td>Apple</td>
                        <td><i class="fa fa-edit"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- fin tabla -->
              </div>  
            </section>  
            <h3>Conocimiento Compartido</h3>
            <section>
            <div class="row">  
              <diV class="col-lg-6 col-sm-12 col-md-6">
                <p>Ingresa tu conocimiento compartido</p>
                <p>Da click en el botón siguiente o anterior para continuar.</p>
                <!-- <p>Da click en el botón siguiente o anterior para continuar</p> -->
                <form action="guardar_conocimiento">
                  <div class="form-group wd-xs-300">
                    <label class="form-control-label">Fecha ingreso: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                      <input type="date" class="form-control" name="f_evento" id="f_evento" placeholder="AAAA-MM-DD" required>
                    </div>  
                  </div>
                  <div class="form-group wd-xs-300">
                    <label class="form-control-label">Fecha egreso: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                      <input type="date" class="form-control" name="f_evento_fin" id="f_evento_fin" placeholder="AAAA-MM-DD" required>
                    </div>  
                  </div>  
                  <div class="form-group wd-xs-300">
                    <label class="form-control-label">Nombre del Evento: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="nombre_evento" id="nombre_evento"  type="text" required>
                  </div> 
                  <div class="form-group wd-xs-300">
                    <label class="form-control-label">Función llevada a cabo: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="participacion_evento" id="participacion_evento" placeholder="Ponente, Coordinador, Organizador, Otros" type="text" required>
                  </div> 
                  <div class="form-group wd-xs-300">
                    <label class="form-control-label">Detalles del evento y mi participación: <span class="tx-danger">*</span></label>
                    <textarea name="detalles_evento" id="detalles_evento" class="form-control" rows="10" placeholder="Describe tu participación en el evento (Máx. 1000 carácteres)"></textarea>
                  </div>
                </form>
                <div class="form-layout-footer">
                  <button class="btn btn-primary" onclick="salvar_datos(this)">Guardar</button>
                  <button class="btn btn-secondary">Cancelar</button>
                </div> 
              </div>
              <diV class="col-lg-6 col-sm-12 col-md-6">
                <p>Conocimiento compartido</p>
                <p>Para editar o eliminar da clic en el ícono</p>
                <table class="table table-bordered table-colored table-primary">
                  <thead>
                    <tr>
                      <th class="wd-35p">Evento</th>
                      <th class="wd-10p">Editar</th>
                      <th class="wd-10p">Eliminar</th>
                    </tr>
                  </thead>
                  <tbody id="table_conocimiento">
                    <tr>
                      <td>Microsoft</td>
                      <td><i class="fa fa-edit"></i></td>
                      <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                      <td>Apple</td>
                      <td><i class="fa fa-edit"></i></td>
                      <td><i class="fa fa-trash"></i></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>  
            </section>  

            <h3>Plantilla para perfil público</h3>
            <section>
            <h4>Seleccione una plantilla para mostrar los datos en su perfil público.</h4>
            <form id="guardar_plantilla" method="post">
              <input id="splantilla" name="splantilla" type="hidden" ></input>
              <?php for( $i = 1; $i<=$nplantillas; $i++ ){?>
                <label id="modelo<?=$i?>" onclick="seleccionPlantilla( 'modelo<?=$i?>', <?=$nplantillas?> )" style="cursor: pointer;">
                  <img id="modelo<?=$i?>img" src="img/plantillas/modelo<?=$i?>.png" <?php if( $usuario['data']['plantillapp'] == "modelo$i" ) echo 'style = "border: 15px solid #6fc4e4; border-radius:5px;"'; else echo 'style = "opacity: .2;"'; ?> >
                </label>
              <?php }?>
              <p>
                <a onClick="vistaPreviaPlantilla(<?=$_SESSION['alumno']['id_afiliado']?>)" style="color:white; cursor:pointer;" class="btn btn-info active"><i class="fa fa-eye"></i> Vista previa</a>
                <a onclick="guardar_plantilla(<?=$_SESSION['alumno']['id_afiliado']?>)" class="btn btn-primary" style="cursor: pointer; color:white;">Guardar</a>
              </p>
            </form>
            </section>

          </div>
        </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->
      <?php require 'plantilla/footer.php'; ?>
    </div><!-- br-mainpanel -->

    <div id="modal_editar_grado" class="modal fade" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title m-0">Editar grado academico.</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body" >
            <form id="form_editar_grado">
              <input type="hidden" name="item_grado" id="item_grado">
              <div class="form-group wd-xs-300">
                <labe>Selecciona último grado de estudios</label>
                <select id="gradoestudios_edit" name="gradoestudios_edit" class="form-control select2" data-placeholder="Grado académico">
                  <option value="CURSOS">CURSOS</option>
                  <option value="SECUNDARIA">SECUNDARIA</option>
                  <option value="CERTIFICACIÓN">CERTIFICACIÓN</option>
                  <option value="BACHILLER">BACHILLER</option>
                  <option value="TSU">TSU</option>
                  <option value="PREPARATORIA">PREPARATORIA</option>
                  <option value="LICENCIATURA">LICENCIATURA</option>
                  <option value="POSGRADO">POSGRADO</option>
                </select>
              </div> 
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Tipo Licenciatura: <span class="tx-danger">*En caso de contar con ella</span></label>
                <input id="tipoLicen_edit" class="form-control" name="tipoLicen_edit" placeholder="Ingresa Tipo Licenciatura" type="text" >
              </div>
              <div class="form-group wd-xs-300">
                <label class="form-control-label">Cédula Profesional: <span class="tx-danger">*En caso de contar con ella</span></label>
                <input id="cedulap_edit" class="form-control" name="cedulap_edit" placeholder="Ingresa Cédula profesional" maxlength="8" type="text" >
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
            <button onclick="$('#form_editar_grado').submit();" class="btn btn-primary waves-effect">Continuar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
    <!-- FIN MODAL -->

    <!-- MODAL -->
    <div id="modal_editar_lab" class="modal fade" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title m-0">Editar información laboral.</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body" >
            <form id="form_editar_laboral">
              <input type="hidden" name="item_lab" id="item_lab">
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Fecha ingreso: <span class="tx-danger">*</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input type="date" class="form-control" name="inicio_laboral_edit" id="inicio_laboral_edit" placeholder="YYYY-MM-DD" required="">
                </div>  
              </div>  
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Fecha Egreso: <span class="tx-danger">*</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input type="date" class="form-control" name="fin_laboral_edit" id="fin_laboral_edit" placeholder="YYYY-MM-DD" required="">
                </div>  
              </div>  
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Empresa: <span class="tx-danger">*</span></label>
                <input class="form-control" name="empresa_edit" id="empresa_edit" placeholder="Nombre Empresa" type="text" required="">
              </div> 
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Puesto: <span class="tx-danger">*</span></label>
                <input class="form-control" name="puesto_edit" id="puesto_edit" placeholder="Puesto que desarrollabas" type="text" required="">
              </div> 
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Actividad Laboral: <span class="tx-danger">*</span></label>
                <textarea name="actividadLaboral_edit" id="actividadLaboral_edit" class="form-control" rows="10" placeholder="Describe tu actividad laboral (Máx. 1000 carácteres)" required=""></textarea>
              </div> 
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
            <button onclick="$('#form_editar_laboral').submit();" class="btn btn-primary waves-effect">Continuar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <div id="modal-confirm-elimina" class="modal fade" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title m-0">Borrar registro.</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body" >
            <h3>Desea borrar el registro de <span id="nombre_elimina"></span></h3>
            <input type="hidden" name="tipo_elim" id="tipo_elim">
            <input type="hidden" name="reg_elim" id="reg_elim">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancelar</button>
            <button id="confirma_elimina" class="btn btn-danger waves-effect">Continuar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <div id="modal_editar_conocimiento" class="modal fade" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title m-0">Editar conocimiento compartido.</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body" >
            <form id="form_editar_conocimiento">
              <input type="hidden" name="item_conocim" id="item_conocim">
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Fecha ingreso: <span class="tx-danger">*</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input type="date" class="form-control" name="inicio_conocim_edit" id="inicio_conocim_edit" placeholder="YYYY-MM-DD" required="">
                </div>  
              </div>  
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Fecha Egreso: <span class="tx-danger">*</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input type="date" class="form-control" name="fin_conocim_edit" id="fin_conocim_edit" placeholder="YYYY-MM-DD" required="">
                </div>  
              </div>  
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Nombre del Evento: <span class="tx-danger">*</span></label>
                <input class="form-control" name="evento_nom_edit" id="evento_nom_edit" type="text" required="">
              </div> 
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Funcion: <span class="tx-danger">*</span></label>
                <input class="form-control" name="participacion_edit" id="participacion_edit" type="text" required="">
              </div> 
              <div class="form-group wd-xs-300">
              <label class="form-control-label">Detalles del evento y mi participación: <span class="tx-danger">*</span></label>
                <textarea name="detalle_participacion_edit" id="detalle_participacion_edit" class="form-control" rows="10" required=""></textarea>
              </div> 
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
            <button onclick="$('#form_editar_conocimiento').submit();" class="btn btn-primary waves-effect">Continuar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
    <!-- FIN MODAL -->

    <!-- ########## END: MAIN PANEL ########## -->
    
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg-perfil" role="document">
        <div class="modal-content col-sm-12 col-lg-12">
          <div class="modal-header">
            <h5 class="modal-title m-0">Previsualización y recorte de la foto.</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <div class="container">
                <div class="col-md-8 col-sm-8">
                  <img class="card-img-bottom img-fluid" src="" id="sample_image"/>
                </div>
                <div class="col-md-4 col-sm-4">
                  <div class="preview"></div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="crop" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelImg">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <script src="../lib/jquery/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="../lib/popper.js/popper.js"></script>
    <script src="../lib/bootstrap/bootstrap.js"></script>
    <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
    <script src="../lib/moment/moment.js"></script>
    <script src="../lib/jquery-ui/jquery-ui.js"></script>
    <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
    <script src="../lib/peity/jquery.peity.js"></script>
    <script src="../lib/highlightjs/highlight.pack.js"></script>
    <script src="../lib/jquery.steps/jquery.steps.js"></script>
    <script src="../lib/parsleyjs/parsley.js"></script>

    <script src="../js/bracket.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="script/perfil.js"></script>
    <script>
      const usr_info = JSON.parse('<?php echo json_encode($usuario['data']); ?>');
    function onlynum(evt){
      if ((evt.which < 46 || evt.which > 57) && ![13,8,96,97,98,99,100,101,102,103,104,105].includes(evt.which)){
        evt.preventDefault();
      }
    }
    function validateurl(elm){
      var expression = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi;
      var regex = new RegExp(expression);
      if($(elm).val().trim() != '' && !$(elm).val().trim().match(regex)){
        swal('', 'La url no es válida, por favor ingrese una url en el siguiente formato: \n https://su-red-social.com/username', 'info');
        $(elm).focus();
      }
    }
    </script> 

  </body>
</html>
