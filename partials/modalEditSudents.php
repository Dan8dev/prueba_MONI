<div class="modal fade modal-right" id="modalModificarDatosDirectorio">
					<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title m-0">Formulario Asignar Datos a Directorio</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								</div>
								<div class="modal-body">
                  <form id="formDatosDirectorio" type="post">

                    <div class="form-group">
                        <label for="nombreDirectorio">NOMBRE:</label>
                        <input type="text" class="form-control upper" id="nombreDirectorio" name="nombreDirectorio" placeholder="Ingresa el nombre del alumno">
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="apellidoPaternoDirectorio">APELLIDO PATERNO:</label>
                        <input type="text" class="form-control upper" id="apellidoPaternoDirectorio" name="apellidoPaternoDirectorio" placeholder="Ingresa el apellido paterno del alumno">
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="apellidoMaternoDirectorio">APELLIDO MATERNO:</label>
                        <input type="text" class="form-control upper" id="apellidoMaternoDirectorio" name="apellidoMaternoDirectorio" placeholder="Ingresa el apellido materno del alumno">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-12">
                        <label for="">Matrícula</label>
                        <input type="text" name="inp_matricula" id="inp_matricula" class="form-control" placeholder="Matrícula">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="generacionDirectorio">GENERACIÓN:</label>
                        <select class="form-control" name="generacionDirectorio" id="generacionDirectorio">
                        </select>
                      </div>
                      <!-- <div class="col-sm-12 col-md-6 mb-3 hidden" id="groupVisible">
                        <label for="generacionDirectorioGrupo">GRUPO:</label>
                        <select class="form-control" name="generacionDirectorioGrupo" id="generacionDirectorioGrupo">
                        </select>
                      </div> -->
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="estatusAlumnoDirectorio">ESTATUS:</label>
                          <select class="form-control" name="estatusAlumnoDirectorio" id="estatusAlumnoDirectorio" required>
                            <option value="" disabled="disabled">SELECCIONE EL ESTATUS DEL ALUMNO</option>
                            <option value="1">ACTIVO</option>
                            <option value="2">BAJA</option>
                            <option value="3">EGRESADO</option>
                            <option value="4">TITULADO</option>
                            <option value="5">EXPULSADO</option>
                            <option value="7">BLOQUEADO</option>
                          </select>
                          
                          <input type="text" id ="estatusAlumnoGeneraciones" class ="d-none">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">

                      <label for="curpAlumnoDirectorio">CURP: <b><em>*Si ya cuenta con CURP presiona la tecla espacio al final de esta, para que se genere la edad.</em></b></label>
                        <input type="text" class="form-control" id="curpAlumnoDirectorio" name="curpAlumnoDirectorio" maxlength="18" placeholder="Ingresa la CURP del alumno">
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                      <label for="edadAlumnoDirectorio">EDAD: <b><em>*Se generará al ingresar completamente la CURP.</em></b></label>
                        <input type="number" class="form-control" id="edadAlumnoDirectorio" name="edadAlumnoDirectorio" placeholder="Edad del alumno">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="emailAlumnoDirectorio">EMAIL:</label>
                        <input type="email" class="form-control" id="emailAlumnoDirectorio" name="emailAlumnoDirectorio" placeholder="Ingresa el email del alumno">
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="telefonoAlumnoDirectorio">TELÉFONO:</label>
                        <input type="tel" class="form-control" id="telefonoAlumnoDirectorio" name="telefonoAlumnoDirectorio" onkeypress="return checkTel(event)" maxlength="10" placeholder="Ingresa el número de teléfono del alumno">
                      </div>

                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="telefonoDeCasaDirectorio">TELÉFONO DE CASA:</label>
                        <input type="tel" class="form-control" id="telefonoDeCasaDirectorio" name="telefonoDeCasaDirectorio" onkeypress="return checkTel(event)" maxlength="10" placeholder="Ingresa el número de teléfono de casa">
                      </div>

                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="telefonoRecadosDirectorio">TELÉFONO PARA RECADOS:</label>
                        <input type="tel" class="form-control" id="telefonoRecadosDirectorio" name="telefonoRecadosDirectorio" onkeypress="return checkTel(event)" maxlength="10" placeholder="Ingresa el número de teléfono para recados">
                      </div>
                      
                    </div>

                    <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="sexoAlumnoDirectorio">SEXO:</label>
                        <select class="form-control" name="sexoAlumnoDirectorio" id="sexoAlumnoDirectorio">
                          <!--<option value="" disabled="disabled">SELECCIONE EL ESTATUS DEL ALUMNO</option>-->
                          <option value="0">SIN ASIGNAR</option>
                          <option value="1">MUJER</option>
                          <option value="2">HOMBRE</option>
                        </select>
                      </div>
                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="gradoUltimoAlumnoDirectorio">ÚLTIMO GRADO ACADÉMICO:</label>
                        <select class="form-control" name="gradoUltimoAlumnoDirectorio" id="gradoUltimoAlumnoDirectorio">
                          <!--<option value="" disabled="disabled">SELECCIONE EL ÚLTIMO GRADO ACADÉMICO</option>-->
                          <option value="0">SIN ASIGNAR</option>
                          <option value="1">SECUNDARIA</option>
                          <option value="2">BACHILLERATO</option>
                          <option value="3">PREPARATORIA</option>
                          <option value="4">TSU</option>
                          <option value="5">LICENCIATURA</option>
                          <option value="6">MAESTRÍA</option>
                          <option value="8">DOCTORADO</option>
                        </select>
                      </div>

                      <div class="col-sm-12 col-md-6 mb-3">
                        <label for="CedulaProfesionalDirectorio">CÉDULA PROFESIONAL</label>
                        <input class="form-control" type="text" name="CedulaProfesionalDirectorio" id="CedulaProfesionalDirectorio">
                      </div>
                    </div>

                    <div class="form-group">
                      <center><label for="lugarRadicaDirectorio">LUGAR DONDE ESTUDIO</label></center>
                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="paisEstudioDirectorio">PAÍS DEL ÚLTIMO GRADO DE ESTUDIÓ:</label>
                            <select class="form-control" name="paisEstudioDirectorio" id="paisEstudioDirectorio">
                            </select>
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="entidadEstudioDirectorio">ESTADO DEL ÚLTIMO GRADO DE ESTUDIÓ:</label>
                            <select class="form-control" name="entidadEstudioDirectorio" id="entidadEstudioDirectorio">
                            </select>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="EscuelaEstudioDirectorio">ESCUELA DE PROCEDENCIA:</label>
                            <input class="form-control" type="text" name="EscuelaEstudioDirectorio" id="EscuelaEstudioDirectorio">
                          </div>
                          <div class="col-sm-12 col-md-6 mb-3">
                            <label for="fechaEgresoEstudioDirectorio">FECHA DE EGRESO:</label>
                            <input class="form-control" type="date" name="fechaEgresoEstudioDirectorio" id="fechaEgresoEstudioDirectorio">
                          </div>
                        </div>
                    </div>
					<div class="border rounded p-2">
						<div class="">
						  <center><label for="lugarRadicaDirectorio">LUGAR DONDE RADICA</label></center>
						  <div class="row">
							<div class="col-sm-12 col-md-6 mb-2">
							  <label for="paisAlumnoDirectorio">PAÍS DONDE RADICA:</label>
							  <select class="form-control" name="paisAlumnoDirectorio" id="paisAlumnoDirectorio">
							  </select>
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
							  <label for="estadoAlumnoDirectorio">ESTADO DONDE RADICA:</label>
							  <select class="form-control" name="estadoAlumnoDirectorio" id="estadoAlumnoDirectorio">
							  </select>
							</div>
						  </div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">Ciudad</label>
								<input type="text" name="inp_ciudad" id="inp_ciudad" class="form-control">
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">Colonia</label>
								<input type="text" name="inp_colonia" id="inp_colonia" class="form-control">
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">Calle</label>
								<input type="text" name="inp_calle" id="inp_calle" class="form-control">
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label for="">CP</label>
								<input type="tel" name="inp_cp" id="inp_cp" onkeypress="return checkTel(event)" class="form-control" maxlength = "5" onlyNum>
							</div>
						</div>
					</div>

                    <div class="form-group">
                      <center><label for="lugarNacimientoDirectorio">LUGAR DE NACIMIENTO</label></center>
                      <div class="row">
                        <div class="col-sm-12 col-md-6 mb-3">
                          <label for="paisNacimientoDirectorio">PAÍS DE NACIMIENTO:</label>
                          <select class="form-control" name="paisNacimientoDirectorio" id="paisNacimientoDirectorio">
                          </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                          <label for="entidadNacimientoDirectorio">ESTADO DE NACIMIENTO:</label>
                          <select class="form-control" name="entidadNacimientoDirectorio" id="entidadNacimientoDirectorio">
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="notasDirectorio">NOTAS:</label>
                      <textarea class="form-control" name="notasDirectorio" id="notasDirectorio" row="4" cols="50" placeholder="Ingresa tus notas"></textarea>
                    </div>

                    <div class="text-right">
                      <input type="hidden" name="idRelacion" id="idRelacion">
                      <input type="hidden" name="idAlumno" id="idAlumno_d">
                      <input type="hidden" name="idGeneracionAntigua" id="idGeneracionAntigua">
                      <button type="submit" class="btn btn-primary waves-effect waves-light" aria-hidden="true">Actualizar</button>
                      <button type="button" name="cerrarEditarDirectorio" id="cerrarEditarDirectorio" class="btn btn-secondary waves-effect m-1-5" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                    </div>
                  </form>
								</div><!--end-modal-body-->
							</div><!--end-content-modal-->
					</div><!--end modal centered-->
			</div>

     
      <!-- Modal -->
      <div class="modal fade" id="ComentariosAfiliado" tabindex="-1" role="dialog" aria-labelledby="ComentariosAfiliado" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Comentarios del Alumno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table id="datatableComentariosAfiliados" class="table table-striped table-bordered nowrap" style="font-size:small; border-collapse: collapse; width: 100%;">
                    <thead>
                    <tr>
                      <th>Nota</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- <script>
        $('#exampleModal').on('show.bs.modal', event => {
          var button = $(event.relatedTarget);
          var modal = $(this);
          // Use above variables to manipulate the DOM
          
        });
      </script> -->