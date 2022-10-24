<?php 
// if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
// {
//     //Tell the browser to redirect to the HTTPS URL.
//     //header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
//     //Prevent the rest of the script from executing.
//     exit;
// }

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
  
  require 'plantilla/header.php';
?>
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="panel.php">Panel</a>
            <span class="breadcrumb-item active">Registrar Alumno</span>
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <!--<h3><a href="#" class="text-dark"><u>Click para ingresar.</u></a></h3>-->

        <!--<h4 class="tx-gray-800 mg-b-5">TRAINING 28 de Septiembre  6:00 PM</h4>
        <p class="mg-b-0">Dá click en el link para iniciar tu sesión en Webex</p>-->
    </div>

    <div style="display:none;">
        <?php print_r($_SESSION); ?>
    </div>
    <!-- br-pagebody -->
    <div class="br-pagebody">
        <div class="tab-content bg-light">
          <h3>Esto es un demo. CONACON trabajando para tí.</h3>
          <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
            <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#posts" role="tab">Estudio Socioeconómico</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photos" role="tab">Nota de ingreso</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#eventos" role="tab">Plan de tratamiento</a></li>
            </ul>
          </div>
        
          <div class="tab-content br-profile-body">
            <div class="tab-pane fade active show" id="posts">
              <div class="card m-3">
                  <div class="card-body">
                      <h4 class="page-title ">Estudio Socioeconómico</h4>
                      <form id="form_estudio_socioeconomico">
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-4">
                                  <label>No. Expediente</label>
                                  <input type="text" name="name" id="name" class="form-control special" >
                              </div>
                              <div class="form-group col-sm-12 col-md-4">
                                  <label>Servicio</label>
                                  <input type="text" name="paterno" id="paterno" class="form-control special"
                                      >
                              </div>
                              <div class="form-group col-sm-12 col-md-4">
                                  <label>Fecha</label>
                                  <input type="date" name="materno" id="materno" class="form-control special"
                                      >
                              </div>
                          </div>
                          <h4>Datos Generales del/a paciente</h4>
                          <hr>
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-6">
                                  <label>Nombre</label>
                                  <input type="text" name="telefono" id="telefono" class="form-control"  >
                              </div>
                              <div class="form-group col-sm-12 col-md-2">
                                  <label>Sexo</label>
                                  <label class="rdiobox">
                                    <input name="inp_sexo" type="radio" value="f">
                                    <span>F</span>
                                  </label>
                                  <label class="rdiobox">
                                    <input name="inp_sexo" type="radio" value="m">
                                    <span>M</span>
                                  </label>
                              </div>
                              <div class="form-group col-sm-12 col-md-2">
                                <label for="">Edad</label>
                                <input type="text" class="form-control">
                              </div>
                              <div class="form-group col-sm-12 col-md-2">
                                <label for="">Teléfono</label>
                                <input type="text" class="form-control">
                              </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                                <label>Fecha de nacimiento</label>
                                <input type="date" name="Curp" id="Curp" class="form-control" >
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label>Nivel de estudios</label>
                                <input type="text" name="niv" id="niv" class="form-control">
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Ocupacion</label>
                              <input type="text" name="ocupacion" id="ocupacion" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Estado civil</label>
                              <input type="text" name="civil" id="civil" class="form-control">
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-3">
                              <label>Derechohabiente</label>
                              <label class="rdiobox">
                                <input name="inp_habiente" type="radio" value="si">
                                <span>Si</span>
                              </label>
                              <label class="rdiobox">
                                <input name="inp_habiente" type="radio" value="no">
                                <span>No</span>
                              </label>
                            </div>
                            <div class="form-group col-sm-12 col-md-3">
                              <label>a</label>
                              <input type="text" name="habiente_a" id="habiente_a" class="form-control">
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                              <label>Región</label>
                              <input type="text" name="region_a" id="region_a" class="form-control">
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Idioma o dialecto</label>
                              <input type="text" name="idioma" id="idioma" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Domicilio</label>
                              <input type="text" name="idioma" id="idioma" class="form-control">
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Calle y #</label>
                              <input type="text" name="calle_num" id="calle_num" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Colonia</label>
                              <input type="text" name="colonia" id="colonia" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                              <label>C.P.</label>
                              <input type="text" name="codpost" id="codpost" class="form-control">
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Alcadía o Municipio</label>
                              <input type="text" name="municipio" id="municipio" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                              <label>Estado</label>
                              <input type="text" name="estado_inp" id="estado_inp" class="form-control">
                            </div>
                          </div>
                          <h4>Condiciones económicas</h4>
                          <hr>
                          <div class="row">
                            <div class="col-12">
                              <table class="table table-striped table-sm">
                                <thead>
                                  <th>Personas que Aportan</th>
                                  <th>Ingreso Mensual</th>
                                  <th>Desglose de Gastos</th>
                                  <th>Egreso Mensual</th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Jefe/a de familia</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                    <td>Alimentación/Despensa</td>
                                    <td><input type="number" name="egreso1" id="egreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Esposo/a</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                    <td>Renta/hipoteca</td>
                                    <td><input type="number" name="egreso1" id="egreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Hijo/a</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                    <td>Predio</td>
                                    <td><input type="number" name="egreso1" id="egreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Otros</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                    <td>Agua</td>
                                    <td><input type="number" name="egreso1" id="egreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Total</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                    <td>Luz</td>
                                    <td><input type="number" name="egreso1" id="egreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td rowspan="9" style="vertical-align:middle;">Número total de integrantes</td>
                                    <td rowspan="9" style="vertical-align:middle;"><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Gas</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Teléfono</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Gastos escolares</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Gastos en salud</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Transporte</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Servicios Domésticos</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td>Consumos Adicionales</td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td><b>Total</b></td>
                                    <td><input type="number" name="ingreso1" id="ingreso1" class="form-control"></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row">
                            <div class="form-group col-sm-12">
                              <label>Relación Ingreso - Egreso:</label>
                              <input type="text" name="rel-1" id="rel-1" class="form-control">
                            </div>
                            <div class="form-group col-sm-12">
                              <label>Relación Ingreso - Número dependientes económicos:</label>
                              <input type="text" name="estado_inp" id="estado_inp" class="form-control">
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-sm-12">
                              <label>Situación económica:</label>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Déficit:</label>
                              <input type="text" name="estado_inp" id="estado_inp" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Equilibrio:</label>
                              <input type="text" name="estado_inp" id="estado_inp" class="form-control">
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                              <label>Superávit:</label>
                              <input type="text" name="estado_inp" id="estado_inp" class="form-control">
                            </div>
                            <div class="form-group col-sm-12">
                              <label>Ocupación del principal proveedor económico</label>
                              <input type="text" name="estado_inp" id="estado_inp" class="form-control">
                            </div>
                          </div>
                          <h4>Vivienda</h4>
                          <hr>
                          <div class="form-group col-sm-12">
                            <label>Tipo de Tendencia</label>
                            <div class="row">
                              <div class="col">
                                <label class="rdiobox">
                                  <input name="inp_tendencia" type="radio" value="si">
                                  <span>Propia (3)</span>
                                </label>
                              </div>
                              <div class="col">
                                <label class="rdiobox">
                                  <input name="inp_tendencia" type="radio" value="no">
                                  <span>Prestada (2)</span>
                                </label>
                              </div>
                              <div class="col">
                                <label class="rdiobox">
                                  <input name="inp_tendencia" type="radio" value="no">
                                  <span>Rentada (1)</span>
                                </label>
                              </div>
                              <div class="col">
                                <label class="rdiobox">
                                  <input name="inp_tendencia" type="radio" value="no">
                                  <span>Otro (0)</span>
                                </label>
                              </div>
                              <div class="col">
                                <input type="text" class="form-control">
                              </div>
                            </div>

                            <div class="row">
                              <table class="table table-sm">
                                <thead>
                                  <th>Grupo</th>
                                  <th>Tipo de Vivienda</th>
                                  <th>Puntos</th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Grupo 1</td>
                                    <td>Institución de protección social, cueva, choza, jacal, casa rural, barranca, tugurio o cuarto redondo o sin vivienda</td>
                                    <td>
                                      <label class="rdiobox">
                                        <input name="inp_puntaje" type="radio" value="no">
                                        <span>0</span>
                                      </label>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Grupo 2</td>
                                    <td>Vecindad o cuarto de servicio.</td>
                                    <td>
                                      <label class="rdiobox">
                                        <input name="inp_puntaje" type="radio" value="no">
                                        <span>1</span>
                                      </label>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Grupo 3</td>
                                    <td>Departamento o casa popular, unidades habitacionales (interés social)</td>
                                    <td>
                                      <label class="rdiobox">
                                        <input name="inp_puntaje" type="radio" value="no">
                                        <span>2</span>
                                      </label>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Grupo 4</td>
                                    <td>Departamento o casa clase medio con financiamiento propio o hipoteca. </td>
                                    <td>
                                      <label class="rdiobox">
                                        <input name="inp_puntaje" type="radio" value="no">
                                        <span>3</span>
                                      </label>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Grupo 5</td>
                                    <td>Departamento o casa residencial </td>
                                    <td>
                                      <label class="rdiobox">
                                        <input name="inp_puntaje" type="radio" value="no">
                                        <span>5</span>
                                      </label>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>

                          <h5 class="mt-3">Servicios Públicos</h5>
                          <hr>
                          <div class="row">
                            <div class="col">
                              <ul>
                                <li>Alumbrado Público</li>
                              </ul>
                            </div>

                            <div class="col">
                              <ul>
                                <li>Pavimentación</li>
                              </ul>
                            </div>

                            <div class="col">
                              <ul>
                                <li>Alcantarillado</li>
                              </ul>
                            </div>

                            <div class="col">
                              <ul>
                                <li>Recolección de basura</li>
                              </ul>
                            </div>
                            <div class="col">
                              &nbsp;
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              4 o más (3)
                            </div>
                            <div class="col">
                            3 servicios (2)
                            </div>
                            <div class="col">
                            2 servicios (1)
                            </div>
                            <div class="col">
                            0-1 Servicio (0)
                            </div>
                            <div class="col">
                              <input type="text" class="form-control">
                            </div>
                          </div>

                          <h5 class="mt-3">Servicios Intradomiciliarios</h5>
                          <hr>
                          <div class="row">
                            <div class="col">
                              <ul>
                                <li>Alumbrado Público</li>
                              </ul>
                            </div>

                            <div class="col">
                              <ul>
                                <li>Pavimentación</li>
                              </ul>
                            </div>

                            <div class="col">
                              <ul>
                                <li>Alcantarillado</li>
                              </ul>
                            </div>

                            <div class="col">
                              <ul>
                                <li>Recolección de basura</li>
                              </ul>
                            </div>
                            <div class="col">
                              &nbsp;
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              4 o más (3)
                            </div>
                            <div class="col">
                            3 servicios (2)
                            </div>
                            <div class="col">
                            2 servicios (1)
                            </div>
                            <div class="col">
                            0-1 Servicio (0)
                            </div>
                            <div class="col">
                              <input type="text" class="form-control">
                            </div>
                          </div>

                          <h5 class="mt-3">Material Construcción</h5>
                          <hr>
                          <div class="row">
                            <div class="col">
                            Mampostería (2)
                            </div>
                            <div class="col">
                            Mixta (1)
                            </div>
                            <div class="col">
                            Lamina, madera, material de la región (0) 
                            </div>
                            <div class="col">
                              <input type="text" class="form-control">
                            </div>
                          </div>

                          <h5 class="mt-3">Número de Dormitorios</h5>
                          <hr>
                          <div class="row">
                            <div class="col">
                            5 a más (2)
                            </div>
                            <div class="col">
                            3-4 (1)
                            </div>
                            <div class="col">
                            1-2 (0)
                            </div>
                            <div class="col">
                              <input type="text" class="form-control">
                            </div>
                          </div>

                          <h5 class="mt-3">Número de personas por dormitorio</h5>
                          <hr>
                          <div class="row">
                            <div class="col">
                            1-2 (2)
                            </div>
                            <div class="col">
                            3 (1)
                            </div>
                            <div class="col">
                            4 o más (0)
                            </div>
                            <div class="col">
                              <input type="text" class="form-control">
                            </div>
                          </div>

                          <h4 class="mt-3">Estado de salud</h4>
                          <hr>

                          <div class="row">
                            <div class="col-12">
                              <table class="table">
                                <tr>
                                  <td>
                                  Diagnóstico Médico del/a paciente:
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr> 
                                <tr>
                                  <td>
                                  ¿Desde hace cuento tiempo está enfermo/a?
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>  
                                <tr>
                                  <td>
                                  Menos de 3 meses o sin comorbilidad (2)	De 3 a 6 meses (1)	Más de 6 meses (0)
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>  
                                <tr>
                                  <td>
                                  ¿El/la paciente tiene otros problemas de salud, además del que presenta y por el cual se atiende en otra institución? No (1) Si (0)
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>  
                                <tr>
                                  <td>
                                  ¿Cuál? 
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>  
                                <tr>
                                  <td>
                                  ¿Dónde se atiende?
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>  
                                <tr>
                                  <td>
                                  Estado de Salud de los Integrantes de la familia:
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>  
                                <tr>
                                  <td>
                                  Ningún enfermo (2) Un enfermo (1) Dos y el principal proveedor económico (0):
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>  
                              </table>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-12">
                              <label for="">Familiograma</label>
                              <textarea class="form-control" name="" id="" cols="15" rows="5"></textarea>
                            </div>
                            <div class="col-12 mt-4">
                              <label for="">Diagnóstico Social</label>
                              <textarea class="form-control" name="" id="" cols="15" rows="5"></textarea>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-12 mt-5">
                              <table class="table table-sm">
                                <tr>
                                  <td>
                                  Total de Puntos:
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                  Nivel de Estudio socioeconomico:
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                  Nombre y firma del entrevistado/a:
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                  Nombre y firma del profesional en trabajo social:
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                  Cédula Profesional:
                                  </td>
                                  <td>
                                    <input type="text" class="form-control">
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>

                          <div class="row mt-4">
                              <div class="ml-auto mr-2">
                                  <button type="submit" class="btn btn-primary">Guardar</button>
                                  <button type="button" class="btn btn-secondary"
                                      onclick="$('#form_estudio_socioeconomico')[0].reset()">Cancelar</button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
            </div>
            <div class="tab-pane fade table-responsive" id="photos">
              <div class="m-3">
                <div class="card">
                  <div class="card-body">
                    <form action="">
                      <h4 for="">Datos del usuario/a</h4>
                      <div class="row">
                        <div class="col-12 mb-2">
                          <div class="group-form">
                              <label for="">Nombre Completo</label>
                              <input class="form-control" type="text" placeholder="Nombre completo">
                          </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                          <label for="">Sexo</label>
                          <input class="form-control" type="text" placeholder="Sexo"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Fecha de nacimiento</label>  
                        <input class="form-control" type="text" placeholder="Fecha de nacimiento"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Edad</label>  
                        <input class="form-control" type="number" placeholder="Edad"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                          <label for="">Dirección</label>
                          <input class="form-control" type="text" placeholder="Dirección"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                          <label for="">Teléfonos</label>  
                        <input class="form-control" type="text" placeholder="Teléfonos"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                          <label for="">Nacionalidad</label>
                          <input class="form-control" type="text" placeholder="Nacionalidad"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Estado Civil</label>  
                        <input class="form-control" type="text" placeholder="Estado Civil"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Escolaridad</label>  
                        <input class="form-control" type="text" placeholder="Escolaridad"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Ocupación</label>  
                        <input class="form-control" type="text" placeholder="Ocupación"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">¿Ingresos previos en el establecimiento?"</label>  
                        <input class="form-control" type="text" placeholder="¿Ingresos previos en el establecimiento?"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Fechas</label>  
                        <input class="form-control" type="text" placeholder="Fechas"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                            <label for="">¿Es referido por alguna institución? </label>
                            <div>
                              <label for="">Sí</label>
                            <input type="radio" name="isn" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="isn" value="2">
                            </div>
                        </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">¿Presenta hoja de referencia?</label>  
                        <div>
                              <label for="">Sí</label>
                            <input type="radio" name="isnt" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="isnt" value="2">
                            </div>
                        </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Tipo de ingreso actual: Voluntario</label>  
                        <div>
                              <label for="">Sí</label>
                            <input type="radio" name="isntt" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="isntt" value="2">
                            </div>
                        </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Motivo de consulta</label>  
                        <input type="text" class="form-control" placeholder="Motivo de consulta"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Descripción breve del estado de salud del usuario/a</label>  
                        <input type="text" placeholder="Descripción breve del estado de salud del usuario/a" class="form-control"></div>
                        </div>
                      </div>
                      <h4 class="mt-2">Datos del familiar o representante legal:</h4>
                      <div class="row">
                        <div class="col-12 mb-2">
                        <div class="group-form">
                          <label for="">Nombre Completo</label>
                          <input class="form-control" type="text" placeholder="Nombre completo">
                      </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                      <label for="">Edad</label>  
                      <input class="form-control" type="number" placeholder="Edad"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                      <label for="">Parentesco</label>  
                      <input class="form-control" type="number" placeholder="Parentesco"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                      <label for="">Ocupación</label>  
                      <input class="form-control" type="number" placeholder="Ocupación"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Dirección</label>
                        <input class="form-control" type="text" placeholder="Dirección"></div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                        <label for="">Teléfonos</label>  
                      <input class="form-control" type="text" placeholder="Teléfonos"></div>
                        </div>
                      </div>
                      <h4 class="mt-2">Criterios clínicos de inclusión al tratamiento:</h4>
                      <div class="row">
                        <div class="col-12 mb-2">
                        <div class="group-form">
                      <label for="">El usuario/a es hombre</label>  
                      <div>
                              <label for="">Sí</label>
                            <input type="radio" name="ism" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="ism" value="2">
                            </div>
                      </div>
                        </div>
                        <div class="col-12 mb-2">
                       <div class="group-form">
                      <label for="">El usuario/a es mayor de edad</label>  
                      <div>
                              <label for="">Sí</label>
                            <input type="radio" name="itn" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="itn" value="2">
                            </div>
                          </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                      <label for=""> El usuario/a presenta un nivel de dependencia al alcohol o drogas</label>  
                      <div>
                              <label for="">Sí</label>
                            <input type="radio" name="itm" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="itm" value="2">
                            </div>
                      </div>
                        </div>
                        <div class="col-12 mb-2">
                           
                      <div class="group-form">
                      <label for="">El usuario/a presenta alguna consecuencia asociada al consumo </label>
                      <div>
                              <label for="">Sí</label>
                            <input type="radio" name="itm" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="itm" value="2">
                            </div>
                      </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                      <label for="">El usuario/a presenta algún trastorno mental o de tipo psiquiátrico que le impida beneficiarse del tratamiento</label>  
                      <div>
                              <label for="">Sí</label>
                            <input type="radio" name="imt" value="1">
                            </div>
                            <div>
                              <label for="">No</label>
                            <input type="radio" name="imt" value="2">
                            </div>
                      </div>
                        </div>
                        <div class="col-12 mb-2">
                        <div class="group-form">
                      <label for="">El usuario/a NO cumple con todos los criterios, indicar el lugar al que será referido/a</label>
                      <textarea type="text" class="form-control" placeholder="El usuario/a NO cumple con todos los criterios, indicar el lugar al que será referido/a"></textarea>
                      </div>
                        </div>
                        <div class="ml-auto mr-3 mt-3">
                                  <button type="button" id="saveNotaIn" class="btn btn-primary">Guardar</button>
                                  <button type="button" class="btn btn-secondary">Cancelar</button>
                              </div>
                      </div>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div> <!--Fin de Tab-->

            <div class="tab-pane fade table-responsive" id="eventos">
              <div class="m-3">
                <form id="Plan_de_tratamiento">
                    <h5>Plan de tratamiento<h5>
                    <h6>Datos de Identificación del/a usuaria:</h6>
                    <div class="row">
                      <div class="form-group col-sm-12 col-md-8">
                          <label for ="Nombre">Nombre: </label>
                          <input type = "text" name="Nombre" id="Nombre" class="form-control">
                      </div>
                      <div class="form-group col-sm-12 col-md-4">
                          <label for ="Fecha">Fecha:</label>
                          <input type = "date" name="Fecha" id="Fecha" class="form-control">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-sm-6 col-md-4">
                          <label for ="Sexo">Sexo: </label>
                          <input type = "text" name="Sexo" id="Sexo" class="form-control">
                      </div>
                      
                      <div class="form-group col-sm-6 col-md-4">
                          <label for ="Edad">Edad: </label>
                          <input type = "number" name="Edad" id="Edad" class="form-control" onlyNum>
                      </div>

                      <div class="form-group col-sm-6 col-md-4">
                          <label for = "Expediente">Expediente: </label>
                          <input type ="text" name="Expediente" id="Expediente" class="form-control">
                      </div>
                    </div>
                    <h5>Resultado de las valoraciones (médica, psicológica y de trabajo social)</h5>
                    <div class="row">
                      <div class="form-group col-sm-12 col-md-12">
                          <label for ="Estudios">Estudios Médicos realizados durante la valoración:</label>
                          <input type = "text" name="Estudios" id="Estudios" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for ="Diagnostico">Diagnóstico médico: </label>
                          <input type = "text" name="Diagnostico" id="Diagnostico" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for ="Pruebas">Pruebas aplicadas durante la valoración psicológica:  </label>
                          <input type = "text" name="Pruebas" id="Pruebas" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "psicologico">Diagnóstico psicológico: </label>
                          <input type ="text" name="psicologico" id="psicologico" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "Situacion">Situación familiar, social, laboral y/o escolar:  </label>
                          <input type ="text" name="Situacion" id="Situacion" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "Social">Diagnóstico Social:</label>
                          <input type ="text" name="Social" id="Social" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for ="Fisico">Estado Físico:</label>
                          <input type ="text" name="Fisico" id="Fisico" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for ="Riesgos">Riesgos en salud: depresión, ansiedad, riesgo suicida, diabetes, otros.</label>
                          <input type="text" name="Riesgos" id="Riesgos" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for="Caracteristicas">Características, habilidades y potencialidades del/a paciente:</label>
                          <input type="text" name="Caracteristicas" id="Caracteristicas" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for ="Objetivos">Objetivos terapéuticos:</label>
                          <input type="text" name="Objetivos" id="Objetivos" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "Metas">Metas a corto plazo (Establecer el lapso de tiempo):</label>
                          <input type="text" name="Metas" id="Metas" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "MetasMed">Metas a mediano plazo (Establecer el lapso de tiempo):</label>
                          <input type="text" name="MetasMed" id="MetasMed" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "Vias">Vías para su alcance:</label>
                          <input type ="text" name="Vias" id="Vias" class="form-control">
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "Resultados">Resultados esperados:</label>
                          <input type ="text" name="Resultados" id="Resultados" class="form-control">
                      </div>
                      
                      <div class="form-group col-sm-12 col-md-12">
                          <label for = "Nombre_y_firma">Nombre y firma de las/os profesionales que elaboran:</label>
                          <input type ="text" name="Nombre_y_firma " id="Nombre_y_firma" class="form-control">
                      </div>
                    </div>

                    <div class="row text-right">
                      <div class="col">
                        <button class="btn btn-primary btn-sm" type="submit">Guardar Información</button>
                      </div>
                    </div>
                <form>

                </div>
            </div> 
        </div><!-- br-mainpanel -->
        <!-- ########## END: MAIN PANEL ########## -->
    </div><!-- br-pagebody -->

    <?php require 'plantilla/footer.php'; ?>
</div><!-- br-mainpanel -->
<!-- modals -->
<div id="modaldemo1" class="modal fade">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bd-0 tx-14">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Configuración general</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <div class="border p-2"><h6>Datos generales</h6>
          <form id="form-actualizar-registro">
            <div class="row">
              <input type="hidden" name="editar_tipo_moneda" id="editar_tipo_moneda">
              <input type="hidden" name="edit_pr_institucion" id="edit_pr_institucion">
              <input type="hidden" name="inp_prospect_edit" id="inp_prospect_edit">

              <div class="col-md-6 col-sm-12 mb-3">
                  <label>Nombre</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_nombre" name="edit_pr_nombre">
              </div>
              <div class="col-md-6 col-sm-12 mb-3">
                  <label>Apellido Paterno</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_apaterno" name="edit_pr_apaterno">
              </div>
              <div class="col-md-6 col-sm-12 mb-3">
                  <label>Apellido Materno</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_amaterno" name="edit_pr_amaterno">
              </div><div class="col-md-6 col-sm-12 mb-3">
                  <label>Correo</label>
                  <input type="text" class="form-control form-control-sm" id="edit_pr_correo" name="edit_pr_correo">
              </div><div class="col-md-6 col-sm-12 mb-3">
                  <label>Teléfono</label>
                  <input type="text" class="form-control form-control-sm onlyNum" id="edit_pr_telefono" name="edit_pr_telefono" maxlength="10">
              </div>
            </div>
            <div class="row text-right">
              <div class="col">
                <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
              </div>
            </div>
          </form>
        </div>
        <div class="bg-light rounded p-2">
          <label>Seleccionar otra platica a la que apuntar al alumno</label>
          <div class="row">
            <div class="col">
              <input type="hidden" id="id_alumn_ref">
              <select name="nuevo_evento" id="nuevo_evento" class="form-control form-control-sm">
              </select>
            </div>
            <div class="col">
              <button type="button" class="btn btn-primary btn-sm" onclick="agregar_evento()">Agregar</button>
            </div>
          </div>
        </div>
        <h6 class="mt-4">Lista de platicas a las que está registrado</h6>
        <table class="table table-sm" id="table_eventos_apuntados">
          <thead>
            <th>Evento</th>
            <th>Fecha registro</th>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modaldemo2" class="modal fade">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bd-0 tx-14">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Agregar Asistentes</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <div class="border p-2"><h6>Selecciona los asistentes</h6>
            <input type="hidden" name="inp_ev_ref" id="inp_ev_ref">
              <div class="tab-pane table-responsive" id="registrados">
                <div class="m-3">
                  <table id="datatable-tablaAsistEventos" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>A. Paterno</th>
                        <th>A. Materno</th>
                        <th>Correo</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div> 
          </div>
          <div class="modal-footer">
        <button onclick="registrar_muchos()" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div><!-- modal-dialog -->
</div><!-- modal -->

<!-- end modals -->

<!-- ########## END: MAIN PANEL ########## -->

<script src="../lib/jquery/jquery.js"></script>
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

<script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<!-- <script src="../../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script> -->

<script src="script/clases.js"></script>

<script src="../js/bracket.js"></script>
<script src="../js/sweetalert.min.js"></script>


<!--<script src=".././assets/js/template/app.js"></script>-->
<script src="script/gestion_alumno1.js"></script>
<script src="script/gestion_alumno2.js"></script>
<script>
let institu = null;
$(document).ready(function() {
    cargar_cursos_pagos();
    institu = <?php echo (isset($usuario['data']['institucion']) && !empty($usuario['data']['institucion'])) ?  $usuario['data']['institucion']['id_institucion'] : ''; ?>;
    cargar_referidos(institu);
  $('#saveNotaIn').on('click',function(e){
    e.preventDefault();
  swal({
      title: 'Capturado Correctamemnte',
      icon: 'success',
      text: 'Espere un momento',
      button: false,
      timer: 2500,
    })
  });
});

$(".onlyNum").on('keypress',function(evt){
    if (evt.which < 46 || evt.which > 57){
        evt.preventDefault();
    }
});
$("#form_estudio_socioeconomico").on('submit', function(e){
  e.preventDefault();
  swal('Capturado Correctamemnte', 'Espere un momento', 'success');
});
$("#Plan_de_tratamiento").on("submit", function(e){
  e.preventDefault();
  swal({
      title: 'Capturado Correctamemnte',
      icon: 'success',
      text: 'Espere un momento',
      button: false,
      timer: 2500,
    })
});
</script>

</body>

</html>
