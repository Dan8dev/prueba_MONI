list_clases = [];
let clave_cuso;
function cargar_clases(materia, generacion){
	cargar_examenes(materia, generacion)
	clave_cuso = materia;
	$.ajax({
		url: "../../udc/app/data/CData/materiasControl.php",
		type: "POST",
		data: {action:'cargar_clases',materia:materia, generacion:generacion},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				clases = JSON.parse(data);
				options = "<option disabled='' selected=''>Seleccione clase</option>";
				if(clases.estatus == 'ok'){
					list_clases = [];
					for (i = 0; i < clases.data.length; i++) {
						list_clases.push(clases.data[i]);
						options+=`<option value="${clases.data[i].idClase}">${clases.data[i].titulo}</option>`
					}
				}
				$("#select-clase").html(options)
				$("#select-clase").val(list_clases.length > 0 ? list_clases[0].idClase : '')
				setTimeout(function(){
					$("#select-clase").change()
				},100)
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#loader").css("display", "none")
		}
	});
}

function cargar_cursos_pagos(){ // consultar carreras con generaciones asignadas al alumno
	$.ajax({
		url: "../../udc/app/data/CData/materiasControl.php",
		type: "POST",
		data: {action:'pago_cursos'},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				cursos_disp = JSON.parse(data);
				if(cursos_disp.estatus == 'ok'){
					html_c = "";
					
					for (i = 0; i < cursos_disp.data.length; i++) {
						var foto_g = '';

						if(cursos_disp.data[i].idInstitucion == 19){
						if(cursos_disp.data[i].imagen_generacion != ''){
							foto_g = `../../assets/images/generales/flyers/${cursos_disp.data[i].imagen_generacion}`
							var xhr = new XMLHttpRequest();
							xhr.open('HEAD', foto_g, false);
							xhr.send();
							if (xhr.status == "404") {
								foto_g = '../../assets/images/generales/flyers/default_curso_udc.png'
							}
						}else{
							foto_g = `../../assets/images/generales/flyers/default_curso_udc.png`
						}
						//mike
						var nombre_generacion = cursos_disp.data[i].nombre_generacion;
						var nombre_gene = nombre_generacion.split(' ');
						var nombre_gen = nombre_gene[0]+' '+nombre_gene[1];
						cardsVisibles(cursos_disp.data[i].idalumno, cursos_disp.data[i].idgeneracion, cursos_disp.data[i].nombre, nombre_gen, foto_g);
						}	
					}
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#loader").css("display", "none")
		}
	});
}

$("#select-clase").on('change', function(){
	clase = list_clases.find(elm => elm.idClase == $("#select-clase option:selected").val())
	
	
	if(clase){
		$("#boton-tareas").html(`${clase.tareas.length} Tareas para esta clase`)
		$("#boton-tareas").css('display','block')
		recursos = clase.recursos;
		lis = '';
		for (j = 0; j < recursos.length; j++) {
			lis += `<li> <a style="color:#4479ab;" href="../../assets/files/clases/recursos/${recursos[j][0]}" target="_blank">${recursos[j][1]}</a></li>`
		}
		$("#recurso_descargable").html((lis!='')?lis:'<li>No hay recursos disponibles</li>')
		
		apoyo = clase.apoyo;
		lis = '';
		for (j = 0; j < apoyo.length; j++) {
			lis += `<li> <a style="color:#4479ab;" href="../../assets/files/clases/apoyos/${apoyo[j][0]}" target="_blank">${apoyo[j][1]}</a></li>`
		}
		$("#material_apoyo").html((lis!='')?lis:'<li>No hay apoyo disponibles</li>')

		if(clase.video != ''){
			$("#viedo_link").attr("href","reproductor.php?url="+clase.video)
			$("#clase_video").attr("src",clase.foto)
			$("#section_video_d").css("display","block")
			$("#section_video_nd").css("display","none")
		}else{
			$("#viedo_link").attr("href","#")
			$("#section_video_d").css("display","none")
			$("#section_video_nd").css("display","block")
		}
		hwks = '';
		for (j = 0; j < clase.tareas.length; j++) {
			
			strentrega = `<a href="#" style="float:right;" class="btn btn-primary btn-sm" onclick="entregar_tarea(${clase.tareas[j].idTareas}, '${clase.tareas[j].titulo}', ${clase.idClase})">Hasta: <small><i>${clase.tareas[j].fecha_limite}</i></small>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-upload"></i> </a>`
			if((new Date()) > (new Date(clase.tareas[j].fecha_limite))){
				strentrega = `<small> Entrega limite: ${clase.tareas[j].fecha_limite}</small>`
			}
			hwks += `<div class="card mb-2">
			<h6 class="card-header row m-0 p-2">
				<div class="col-sm-9 pt-1">
					<b>
						Titulo:
					</b>
					${clase.tareas[j].titulo}
				</div>
				<div class="col-sm-3">
					<button class="btn btn-warning btn-sm" style="float:right" onclick="entregas(${clase.idClase},${clase.tareas[j].idTareas})">${clase.tareas[j].entregas.length} Archivos entregadas</button>
				</div>
			</h6>
			<div class="card-body py-2">
				<div class="row">
					<div class="col-sm-12 col-md-8 ml-auto">
					<b>
							Descripción:
						</b>
						<p class="card-text mb-1">${clase.tareas[j].descripcion}</p>
					</div>
					<div class="col-sm-12 col-md-4 mt-auto">
						${strentrega}
					</div>
				</div>
			</div>
			</div>`
		}

		$("#content_tareas").html((hwks != '')? hwks : 'Aún no hay tareas para esta clase');
		
			if(clase.foto.trim() != ''){
				$("#image-clase").attr("src",'../../assets/files/clases/fotoClase/'+clase.foto)
			}
		
	}else{
		$("#recurso_descargable").html('<li>No hay recursos disponibles</li>')
		$("#material_apoyo").html('<li>No hay material de apoyo disponible</li>')
		$("#clase_video").css('display','none')
		$("#viedo_link").attr("href","#")
		$("#content_tareas").html('Aún no hay tareas para esta clase')
		$("#viedo_link").attr("href","#")
		$("#section_video_d").css("display","none")
		$("#section_video_nd").css("display","block")
	}
})

function entregas(clase, idTarea){
	cls = list_clases.find(elm => elm.idClase == clase)
	tarea = cls.tareas.find(elm => elm.idTareas == idTarea)
	html_t = "";
	if(tarea.entregas.length == 0){
		html_t += `<li>No hay archivos entregados</li>`
	}
	for (i = 0; i < tarea.entregas.length; i++) {
		t = tarea.entregas[i];
		html_t += `<div class="row py-3 border border-top-0 border-right-0 border-left-0 border-secondary">
                    <div class="col-sm-6 ml-auto">
                      <p class="card-text mb-1">Entregado ${t.fecha_entrega.split(' ')[0]} <i>${t.fecha_entrega.split(' ')[1]}</i> <a href="#"><i class="fa fa-file"></i></a></p>
                      <span>${t.comentario}</span>
                    </div>
                    <div class="col-sm-6 ml-auto pt-1">
                        <span>Calificación: ${(t.calificacion == 0)? 'pendiente' : (t.calificacion)}</span>
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: ${(t.calificacion * 10)}%" aria-valuenow="${(t.calificacion * 10)}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
					<div class="col-12 bg-light rounded pt-2">	
                    	<span class="bg-secondary rounded text-white p-1 m-1"> Comentario del profesor: </span>
                    	<p class="mt-2"><i>${(t.retroalimentacion != null) ? t.retroalimentacion : '-'}</i></p>
                    </div>
                  </div>`
	}
	$("#revision_tareas").html(html_t)
	$("#modal_tareas_entregadas").modal('show')
}

function entregar_tarea(id, titulo, clase){
	$("#titulo_tarea").html(titulo);
	$("#tarea_entrega").val(id);
	$("#clase_tarea").val(clase);

	$("#exampleModal").modal('show')
}

$("#form_entrega_tarea").on('submit', function(e){
	e.preventDefault();

	fdata = new FormData(this)
	fdata.append('action', 'enviar_tarea');

	$.ajax({
		url: "../../udc/app/data/CData/materiasControl.php",
		type: "POST",
		data: fdata,
		contentType:false,
		processData:false,
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				resp = JSON.parse(data)
				console.log(resp)
				if(resp.estatus == 'ok'){
					swal({icon:'success', title:'Tarea enviada', text:'espere calificación'})
				}else{
					if(resp.info == 'error_al_adjuntar_tarea'){
						swal({icon:'info', text:'Ha ocurrido un error al adjuntar el archivo. Verifique los requisitos.'})
					}else{
						swal({icon:'info', text:'Ha ocurrido un error al entregar su tarea'+resp.info})
					}
				}
				$("#form_entrega_tarea")[0].reset();
				$("#exampleModal").modal('hide')
				cargar_clases(clave_cuso, generacion)
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#loader").css("display", "none")
		}
	});
})

function cargar_materias(curso){
	clave_cuso = curso;
	$.ajax({
		url: "../../udc/app/data/CData/materiasControl.php",
		type: "POST",
		data: {action:'cargar_materias',curso:curso},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				materias = JSON.parse(data);
				console.log(materias)
				if(materias.estatus == 'ok'){
					if(materias.data[0].materias_ciclo.length > 0){
						html_materias = '';
						for (i = 0; i < materias.data[0].materias_ciclo.length; i++) {
							var clss = ''
							if(i == 0){
								clss = 'active'
							}
							html_materias += `<li class="nav-item">
												<a class="nav-link bd-0 ${clss} pd-y-8" href="javascript:void(0)" onclick="cargar_clases(${materias.data[0].materias_ciclo[i].id_materia}, ${generacion})">${materias.data[0].materias_ciclo[i].nombre}</a>
											</li>`
						}
						$("#content-materias").html(html_materias)
						if(materias.data[0].materias_ciclo.length > 0){
							$(".nav-tabs_i.card-header-tabs a")[0].click()	
							$("#lbl_nombre-mat").html($(".nav-tabs_i.card-header-tabs a")[0].text.trim())
						}
						$(".nav-tabs_i.card-header-tabs a").on('click', function(){
							$(this).addClass('active');
							var current = $(this).parent('li').index();
							
							$("#lbl_nombre-mat").html($(this).text().trim())
							setTimeout(function(){
								$(".nav-tabs_i.card-header-tabs a").each(function(){
									if($(this).parent().index() != current){
										$(this).removeClass('active');
									}
								})
							}, 50);
						});
					}else{
						swal({icon:'info', text:'No hay materias para este curso'})
					}
				}else{
					swal({
						icon:'info',
						text:materias.info
					})
				}
				
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#loader").css("display", "none")
		}
	});
}
function cargar_examenes(curso, generacion){
	clave_cuso = curso;
	$.ajax({
		url: "../../udc/app/data/CData/materiasControl.php",
		type: "POST",
		data: {action:'cargar_examenes',curso:curso, generacion:generacion},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				examenes_r = JSON.parse(data);
				html_ex = ``;
				if(examenes_r.length == 0){
					html_ex = `Aún no hay examenes para este curso`;
				}
				for (i = 0; i < examenes_r.length; i++) {
					buton_presentar = '';
					if (examenes_r[i].presentaciones.length > 0) {
						buton_presentar = `<span  class="bg-success text-light p-1">${examenes_r[i].presentaciones[0].calificacion}% de aprobación.</span>`
						aplicar_nuevamente = false;
						if(examenes_r[i].multiple_intento == 1){
							aplicar_nuevamente = true;
							for(x in examenes_r[i].presentaciones){
								if(parseInt(examenes_r[i].presentaciones[x].calificacion) >= parseInt(examenes_r[i].porcentaje_aprobar)){
									aplicar_nuevamente = false;
								}
							}
						}
						if(aplicar_nuevamente){
							buton_presentar = `<span  class="bg-warning text-light p-1">No aprobado.</span>`
							if(examenes_r[i].ontime){
								buton_presentar += `<button class="btn btn-sm btn-primary ml-3" onclick="aplicar_examen(${examenes_r[i].idExamen})" title="Mínimo de aprobación: ${examenes_r[i].porcentaje_aprobar}%">Aplicar nuevamente </button>`
							}else{
								buton_presentar += `<button class="btn btn-sm btn-secondary">Examen vencido</button>`
							}
						}else{
							buton_presentar = `<span  class="bg-success text-light p-1">Aprobado.</span>`
							if(examenes_r[i].multiple_intento == 2){
								buton_presentar = `<span  class="bg-success text-light p-1">${examenes_r[i].presentaciones[0].calificacion}% de aprobación.</span>`
							}
						}
					}else{
						if(examenes_r[i].ontime){
							buton_presentar = `<button class="btn btn-sm btn-primary" onclick="aplicar_examen(${examenes_r[i].idExamen})">Aplicar al examen</button>`
						}else{
							if(examenes_r[i].before){
								buton_presentar = `<button class="btn btn-sm btn-secondary">Aún no es hora del examen.</button>`
							}else{
								buton_presentar = `<button class="btn btn-sm btn-secondary">Examen vencido</button>`
							}
						}
					}
					
					html_ex+=`
							<div class="card mb-4">
							<div class="card-body py-3">
							<p class="mg-b-0">${examenes_r[i].Nombre}</p>
							</div><!-- card-body -->
							<div class="card-footer bd-color-gray-lighter py-2 d-flex align-items-center justify-content-between">
							<span>Disponible hasta <u>${examenes_r[i].fechaFin}</u></span>
							<div class="d-flex align-items-end">
								${buton_presentar}
							</div>
							</div><!-- card-footer -->
						</div>`
				}

				$("#info_examenes").html(html_ex)
				
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#loader").css("display", "none")
		}
	});
}
function aplicar_examen(exm){
	var form = document.createElement("form");
  var element1 = document.createElement("input");
  var element2 = document.createElement("input");
  
  if(window.location.pathname.includes('examenes-extra')){
	  loc = 'extra';
  }else{
	  loc = 'normal';
  }

  form.method = "POST";
  form.action = "aplicar_examen.php";   

  element1.value=exm;
  element1.name="examen";
  element2.value=loc;
  element2.name="loc";
  form.appendChild(element1);  
  form.appendChild(element2);
  form.setAttribute('hidden',true)

  document.body.appendChild(form);

  form.submit();
  form.remove();
}

function consultar_sesiones(generacion){
	$.ajax({
		url: "../../assets/data/Controller/controlescolar/materiasControl.php",
		type: "POST",
		data: {action:'consultar_sesiones',generacion:generacion},
		success: function(data){
			try{
				var sesiones = JSON.parse(data)
				var html_sesiones = ``;
				for(i in sesiones){
						html_sesiones+=`<p><a style="text-decoration: underline;" class="text-primary" href="claseswebex/?sesion=${sesiones[i].id}"><h3>Click para la sesión de <b>${sesiones[i].nombre_clase} </b> (${sesiones[i].fecha_clase})</h3></a></p>`
				}
				$("#links_clase").html(html_sesiones)
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	})
}

function cardsVisibles(idAlumno, id, nombreCarrera, nombreGeneracion, foto){
$.ajax({
	url: '../../udc/app/data/CData/materiasControl.php',
	type: 'POST',
	data: {action: 'tipoCarrera',
		id: id,
		idAlumno: idAlumno},
	success: function(data){
		/*if(data =='si'){
		html_c+=`<div class="col-sm-6 col-md-6 col-xl-4">
					<div class="card mg-b-40">
						<div class="card-body bg-primary">
						<p class="card-text text-truncate text-white mb-0">${nombreCarrera}</p>
							<small class="card-text text-truncate text-white text-sm">${nombreGeneracion}</small>
						</div>
						<a href="clases.php?curso=${id}"><img class="card-img-bottom img-fluid" src="${foto}" alt="Image"></a>
					</div>
				</div>`;
				$("#cursos-container").html(html_c);
		}*/
		try{
			pr = JSON.parse(data);
			if(pr.tipo!='1'){
				cardsVisiblesUDC(idAlumno, id, nombreCarrera, nombreGeneracion, foto);
			}
		}catch(e){
			console.log(e)
			console.log(data)
		}
	}
})
}

function cardsVisiblesUDC(idAlumno, id, nombreCarrera, nombreGeneracion, foto){
$.ajax({
	url: '../../udc/app/data/CData/materiasControl.php',
	type: 'POST',
	data: {action: 'validar_adeudos',
		id: id,
		idAlumno: idAlumno},
	success: function(data){
		
		if(data.trim() =='si'){
		html_c+=`<div class="col-sm-6 col-md-6 col-xl-4">
					<div class="card mg-b-40">
						<div class="card-body bg-primary">
						<p class="card-text text-truncate text-white mb-0">${nombreCarrera}</p>
							<small class="card-text text-truncate text-white text-sm">${nombreGeneracion}</small>
						</div>
						<a href="clases.php?curso=${id}"><img class="card-img-bottom img-fluid" src="${foto}" alt="Image"></a>
					</div>
				</div>`;
				$("#cursos-container").html(html_c);
		}else{
			var mensaje = '';
			switch(data.trim()){
				case 'no inscripcion':
					mensaje = 'Su acceso se ha bloqueado por no contar con registro de pago de inscripción';
				break;
				case 'no mensualidad':
					mensaje = 'Su acceso se ha bloqueado por no contar con registro de pago de mensualidad';
				break;
				case 'no documentos':
					mensaje = 'Su acceso se ha bloqueado falta de entrega de documentos digitales';
				break;
				case 'no documentos fisicos':
					mensaje = 'Su acceso se ha bloqueado falta de entrega de documentos físicos';
				break;
				case 'Alumno_con_baja':
					mensaje = `<small>${nombreCarrera}</small><br>Su acceso se ha bloqueado ya que se encuentra dado de baja, contacte a control escolar`;
				break;
			}
			if(mensaje != ''){
				$("#cursos-container").html(`<div class="alert alert-bordered alert-warning" role="alert">
					<strong class="d-block d-sm-inline-block-force"></strong> ${mensaje}.
				</div>`);
			}
		}
	}
});
}

function consultar_calificaciones(idgeneracion) {
	$('#mostrar-periodos').empty();
	$('#content-materias-clases').hide();
	$('#content-materias-nav').hide();

	$('#content-calificaciones-materias').show();
	$.ajax({
		url: "../../udc/app/data/CData/materiasControl.php",
		type: "POST",
		data: {action:'consultar_calificaciones',curso:idgeneracion},
		success: function(data){
			try{
				data=JSON.parse(data);
				$.each(data, function(i, item) {
					$('#mostrar-periodos').append("<table class='table table-bordered table-colored table-dark' style='border-spacing: 2px'> <thead> <tr id='materias"+i+"'> </tr></thead> <tbody><tr id='calificacion"+i+"'>  </tr></tbody></table>")

						$('#materias'+i).append("<th class='wd-10p'></th>")
						$.each(data[i].materias_calificadas, function(j, item2) {
						$('#materias'+i).append("<th style='white-space: break-spaces;' class='wd-10p'>"+data[i].materias_calificadas[j].nombre+"</th>")
						}); 
						var semestre = i +1;
						
						$('#calificacion'+i).append("<th class='wd-10p'>Cuatrimestre "+ semestre +"</th>")
						$.each(data[i].materias_calificadas, function(k, item3) {
						$('#calificacion'+i).append("<th style='white-space: break-spaces;' class='wd-10p'>"+data[i].materias_calificadas[k].calificacion+"</th>")
						});
				}); 
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	})
}



