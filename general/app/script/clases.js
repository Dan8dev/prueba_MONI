list_clases = [];
let clave_cuso;
function cargar_clases(curso){
	clave_cuso = curso;
	$.ajax({
		url: "data/CData/materiasControl.php",
		type: "POST",
		data: {action:'cargar_clases',curso:curso},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				clases = JSON.parse(data);
				console.log(clases)
				options = "<option disabled='' selected=''>Seleccione clase</option>";
				if(clases.estatus == 'ok'){
					list_clases = [];
					for (i = 0; i < clases.data.length; i++) {
						list_clases.push(clases.data[i]);
						options+=`<option value="${clases.data[i].idClase}">${clases.data[i].titulo}</option>`
					}
				}
				$("#select-clase").html(options)
				$("#select-clase").val(list_clases[0].idClase)
				$("#select-clase").change()
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

function cargar_cursos_pagos(){
	$.ajax({
		url: "data/CData/materiasControl.php",
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
					cursos_ver = [];
					pagos_ok = [2, 5, 7, 9];
					for (i = 0; i < cursos_disp.data.length; i++) {
						if(pagos_ok.includes(parseInt(cursos_disp.data[i].id_concepto))){
							if(!cursos_ver.includes(cursos_disp.data[i].concepto)){
								html_c+=`<div class="col-sm-6 col-md-6 col-xl-4">
				    				  <div class="card mg-b-40">
				    					<div class="card-body bg-primary">
				    					  <p class="card-text text-truncate text-white">Operador en adicciones y salud mental.</p>
				    					</div>
				    					<a href="clases.php?curso=1"><img class="card-img-bottom img-fluid" src="https://conacon.org/moni/assets/images/generales/flyers/opt.png" alt="Image"></a>
				    				  </div>
				      				</div>`;
									//https://conacon.org/moni/siscon/app/img/clases/Clase1.jpg
									//
				      				cursos_ver.push(cursos_disp.data[i].concepto)
							}
						}
					}

					$("#cursos-container").html(html_c);
				}
				console.log(cursos_disp)
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
	
	$("#boton-tareas").html(`${clase.tareas.length} Tareas para esta clase`)
	$("#boton-tareas").css('display','block')
	
	recursos = (clase.recursos != '')?JSON.parse(clase.recursos) : [];
	lis = '';
	for (j = 0; j < recursos.length; j++) {
		lis += `<li> <a style="color:#4479ab;" href="${recursos[j][0]}" target="_blank">${recursos[j][1]}</a></li>`
	}
	$("#recurso_descargable").html((lis!='')?lis:'<li>No hay recursos disponibles</li>')
	
	apoyo = (clase.apoyo != '')?JSON.parse(clase.apoyo) : [];
	lis = '';
	for (j = 0; j < apoyo.length; j++) {
		lis += `<li> <a style="color:#4479ab;" href="${apoyo[j][0]}" target="_blank">${apoyo[j][1]}</a></li>`
	}
	$("#material_apoyo").html((lis!='')?lis:'<li>No hay apoyo disponibles</li>')

	if(clase.video != ''){
		$("#viedo_link").attr("href","reproductor.php?url="+clase.video)
		$("#clase_video").css('display','block')
		$("#clase_video").attr("src",clase.foto)
	}else{
		$("#clase_video").css('display','none')
		$("#viedo_link").attr("href","#")
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
})

function entregas(clase, idTarea){
	cls = list_clases.find(elm => elm.idClase == clase)
	tarea = cls.tareas.find(elm => elm.idTareas == idTarea)
	html_t = "";
	for (i = 0; i < tarea.entregas.length; i++) {
		t = tarea.entregas[i];
		html_t += `<div class="row py-3 border border-top-0 border-right-0 border-left-0 border-secondary">
                    <div class="col-sm-6 ml-auto">
                      <p class="card-text mb-1">Entregado ${t.fecha_entrega.split(' ')[0]} <i>${t.fecha_entrega.split(' ')[1]}</i> <a href="#"><i class="fa fa-file"></i></a></p>
                      <span>${t.comentario}</span>
                    </div>
                    <div class="col-sm-6 ml-auto">
                        <span>Calificación: ${(t.calificacion == 0)? 'pendiente' : (t.calificacion)}</span>
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: ${(t.calificacion * 10)}%" aria-valuenow="${(t.calificacion * 10)}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
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
		url: "data/CData/materiasControl.php",
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
				cargar_clases(clave_cuso)
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