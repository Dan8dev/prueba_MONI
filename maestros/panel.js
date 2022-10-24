let list_eventos = [];
//let list_carreras = [];

const tipo_duracion = {h:'horas', d:'días', s:'semanas', m:'meses'};
const estatus_seguimiento = ['1-pendiente', '2-en espera', '3-confirmado', '4-rechazado', '5-no interesado'];
$(document).ready(function(){
	cargar_eventos();
	//lista_atencio();
	//cargar_carreras2();

	conceptos_pago();
	//cambioTab();
	$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function(e){
		$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
	});
});

/*
$("a").on("click",function(){
	idtabla = $(this).attr('tab-dest')
	console.log(idtabla)
	if($(this).attr("tab-dest") != 'undefined' && $(this).attr("tab-dest") != false){
		console.log($(this).attr('tab-dest'))
		$('#'+idtabla).DataTable().columns.adjust();
	}
})*/

/*
$("a[data-toggle=\"tab\"]").on("show.bs.tab", function(e){
	$($.fn.dataTable.tables(true)).DataTable.columns.adjust();
	if($(this).hasOwnProperty('tab-dest')){
		idtabla = $(this).hasOwnProperty('tab-dest');
		console.log(idtabla)
		$(`#${idtabla}`).DataTable().columns.adjust();
	}
})*/

function cargar_eventos(){
	list_eventos = [];
	fData = {
		action:"listado_eventos"
	}
	$.ajax({
		url: "listadoTareas.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				events = JSON.parse(data);
				
				$("#table-eventos").DataTable().clear();

				for (i = 0; i < events.length; i++) {
					list_eventos.push(events[i]);

					rows_tab_e = [
						events[i].tipo,
						events[i].titulo,
						events[i].fechaE,
						events[i].duracion+" "+tipo_duracion[events[i].tipoDuracion],
						`<a href="javascript:void(0)" onclick="detalle(${events[i].idEvento})">${events[i].lugares_reserv+"/"+events[i].limiteProspectos}</a>`
					];

					$("#table-eventos").DataTable().row.add(rows_tab_e);
				}

				$("#table-eventos").DataTable().draw();
				$("#table-eventos").DataTable().columns.adjust();
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$(".outerDiv_S").css("display", "none")
		}
	});
}
	function detalle(id){
		ev_r = list_eventos.find(element => element.idEvento == id);
		hsPrp = ev_r.hasOwnProperty('estatus_info');

		$("#lblTitleEvento_confirm").html(ev_r.titulo);

		$(".lblTotalConfirmados").html((hsPrp)?ev_r.estatus_info.confirmado : 0);
		$(".lblTotalPendientes").html((hsPrp)?ev_r.estatus_info.pendientes : 0);
		$(".lblTotalRechazados").html((hsPrp)?ev_r.estatus_info.rechazo : 0);
		
		$("#listado_prospectos").DataTable().clear();

		if(ev_r.hasOwnProperty("prospectos_eventos") && ev_r.prospectos_eventos.length > 0){
			for (i = 0; i < ev_r.prospectos_eventos.length; i++) {
				prosp = ev_r.prospectos_eventos[i];

				asistencia = ''
				
				strNom = prosp.nombre+" "+prosp.aPaterno;
				if(prosp.etapa == 0){
					asistencia = `<div class="row">
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-success but-circle" onclick="confirmar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-check"></i></button>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-primary but-circle" onclick="rechazar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-times"></i></button>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago(${prosp.idAsistente}, ${id}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento(${prosp.idReg})"><i class="fas fa-comments"></i></button>
					</div>
					</div>`
				}else{
					asistencia = estatus_seguimiento[parseInt(prosp.etapa)];
					asistencia+=`<br><button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento(${prosp.idReg})"><i class="fas fa-comments"></i></button> <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago(${prosp.idAsistente}, ${id}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>`
				}

				
				/*if(prosp.etapa == 1){
					asistencia = 'rechazado<br>';
				}else{
					if(prosp.confirmado == 1){
						asistencia = 'confirmado<br>'
					}else{
						strNom = prosp.nombre+" "+prosp.aPaterno;
						asistencia = `<button type="button" class="btn waves-effect btn-success but-circle" onclick="confirmar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-check"></i></button><button type="button" class="btn waves-effect btn-primary but-circle" onclick="rechazar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-times"></i></button>`;
					}
				}*/
				
				console.log(prosp)
				stringPagos = (prosp.hasOwnProperty('pagos_realizados') && prosp.pagos_realizados.length > 0)? `<a href="javascript:void(0)" onclick="ver_detalles_pagos(${id},${prosp.idAsistente})">${prosp.pagos_realizados.length} pagos.<br>${moneyFormat.format(prosp.pagos_realizados.reduce((a,i)=>{return a+parseFloat(i.detalle_pago.purchase_units[0].amount.value)},0))}</a>` : 'sin pagos';
				arrPropectosData = [
					prosp.nombre+' '+prosp.aPaterno+' '+prosp.aMaterno,
					`<small><a href="javascript:void(0)" class="clpb" aria-label="${prosp.telefono}">${prosp.telefono}</a><br>
						<a href="javascript:void(0)" class="clpb" aria-label="${prosp.correo}">${prosp.correo}</a></small>`,
					prosp.codigo,
					prosp.codigo_promocional,
					stringPagos,
					asistencia
				];
				$("#listado_prospectos").DataTable().row.add(arrPropectosData);
			}
		}
		$("#listado_prospectos").DataTable().draw();
		$("#listado_prospectos").DataTable().columns.adjust();

		$("#profile-tab").click();
	}

function cargar_carreras(){
	fData = {
		action:"listado_carreras"
	}
	$.ajax({
		url: "../assets/data/Controller/carreras/carrerasControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				carreras = JSON.parse(data)
				
				$("#table-carreras").DataTable().clear();
				list_carreras = [];
				for (i = 0; i < carreras.length; i++) {
					list_carreras.push(carreras[i]);

					rows_tab_e = [
						carreras[i].nombre,
						carreras[i].institucion_nombre,
						carreras[i].tipo,
						`<a href="javascript:void(0)" onclick="detalleCarrera(${carreras[i].idCarrera})">ver prospectos...</a>`
					];

					$("#table-carreras").DataTable().row.add(rows_tab_e);
				}

				$("#table-carreras").DataTable().draw();
				$("#table-carreras").DataTable().columns.adjust();
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$(".outerDiv_S").css("display", "none")
		}
	});
}
	
	function detalleCarrera(idC){

		carr_i = list_carreras.find(element => element.idCarrera == idC);
		hsPrp = carr_i.hasOwnProperty('estatus_info');

		$("#lblTitleEvento_confirm").html(carr_i.titulo);

		$(".lblTotalConfirmadosCrr").html((hsPrp)?carr_i.estatus_info.confirmado : 0);
		$(".lblTotalPendientesCrr").html((hsPrp)?carr_i.estatus_info.pendientes : 0);
		$(".lblTotalRechazadosCrr").html((hsPrp)?carr_i.estatus_info.rechazo : 0);
		
		$("#listado_prospectos_carreras").DataTable().clear();

		if(hsPrp && carr_i.prospectos_carrera.length > 0){
			for (i = 0; i < carr_i.prospectos_carrera.length; i++) {
				prosp = carr_i.prospectos_carrera[i];
				strNom = prosp.nombre+' '+prosp.aPaterno+' '+prosp.aMaterno;
				// asistencia = (prosp.etapa == '0')?`<button type="button" class="btn waves-effect btn-success but-circle" onclick="confirmar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-check"></i></button><button type="button" class="btn waves-effect btn-primary but-circle" onclick="rechazar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-times"></i></button>`:estatus_seguimiento[parseInt(prosp.etapa)];
				asistencia=`<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento(${prosp.idReg})"><i class="fas fa-comments"></i></button> <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago(${prosp.idAsistente}, ${idC}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>`
				
				stringPagos = (prosp.hasOwnProperty('pagos_realizados') && prosp.pagos_realizados.length > 0)? `<a href="javascript:void(0)" onclick="ver_detalles_pagos(${idC},${prosp.idAsistente},'c')">${prosp.pagos_realizados.length} pagos.<br>${moneyFormat.format(prosp.pagos_realizados.reduce((a,i)=>{return a+parseFloat(i.detalle_pago.purchase_units[0].amount.value)},0))}</a>` : 'sin pagos';
				
				arrPropectosData = [
					estatus_seguimiento[parseInt(prosp.etapa)],
					strNom,
					`<small><a href="javascript:void(0)" class="clpb" aria-label="${prosp.correo}">${prosp.correo}</a><br>
									<a href="javascript:void(0)" class="clpb" aria-label="${prosp.telefono}">${prosp.telefono}</a></small>`,
					stringPagos,
					asistencia
				];
				
				$("#listado_prospectos_carreras").DataTable().row.add(arrPropectosData);
			}
		}
		$("#listado_prospectos_carreras").DataTable().draw();
		$("#listado_prospectos_carreras").DataTable().columns.adjust();

		$("#prospect-tab").click();
	
	}

function ver_detalles_pagos(id_e, id_p, tipo = "e"){
	if(tipo == "e"){
		ev_r = list_eventos.find(element => element.idEvento == id_e);
		propspecto_cpagos = ev_r.prospectos_eventos.find(pros => pros.idAsistente == id_p)
	}else{
		ev_r = list_carreras.find(element => element.idCarrera == id_e);
		propspecto_cpagos = ev_r.prospectos_carrera.find(pros => pros.idAsistente == id_p)
	}
	
	$("#lbl_persona_pago_nom").html(propspecto_cpagos.nombre)
	total_pagos = 0;
	html = '';
	for (i = 0; i < propspecto_cpagos.pagos_realizados.length; i++) {
		total_pagos += parseFloat(propspecto_cpagos.pagos_realizados[i].detalle_pago.purchase_units[0].amount.value);
		fe_pago = propspecto_cpagos.pagos_realizados[i].detalle_pago.update_time.substr(0,10);
		fecha_parts = (fe_pago != '0000-00-00') ? fe_pago.split("-") : '';
		stringFechaP = (fecha_parts == '')? '' : fecha_parts[2]+" "+meses[parseInt(fecha_parts[1])-1]+" "+fecha_parts[0].substr(2,2);
		html+=`<div class="card">
                <div class="card-heading p-2">
                  <div>
                    <p class="text-muted mb-0 mt-2"><b>Concepto: </b><span class="float-right">${propspecto_cpagos.pagos_realizados[i].plan_pago}</span></p>
                    <p class="mt-2 mb-0 text-muted"><b>Folio: </b><span class="float-right">${propspecto_cpagos.pagos_realizados[i].detalle_pago.id}</span></p>
                    <h4 class="text-success"><small><b>${stringFechaP}</b></small> <span class="float-right">${moneyFormat.format(propspecto_cpagos.pagos_realizados[i].detalle_pago.purchase_units[0].amount.value)}</span></h4>
                  </div>
                </div>
              </div>`;
	}
	$("#total_pagos_pr").html(moneyFormat.format(total_pagos));
	$("#list_pagos_realizados").html(html);
	$("#modal_detalles_pagos").modal("show")
}

function lista_atencion(){
	list_eventos = [];
	fData = {
		action:"lista_atencion"
	}
	$.ajax({
		//../assets/data/Controller/eventos/eventosControl.php
		url: "../assets/data/Controller/marketing/marketingControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			// try{
				//dt = JSON.parse(data);
			 	//console.log(dt);
				
				console.log(data);
			// }catch(e){
			// 	console.log(e);
			//	console.log(data);
			// }
		},
		error: function(){
		},
		complete: function(){
			$(".outerDiv_S").css("display", "none")
		}
	});
}

function confirmar(ev, prs, nombre){
	$("#spanAsist").html(nombre);
	$("#id_asistente").val(prs)
	$("#id_interes").val(ev)
	$("#modalConfirmaAsist").modal("show")
}

function rechazar(ev, prs, nombre){
	$("#spanAsistR").html(nombre);
	$("#id_asistenteRechazo").val(prs)
	$("#id_interesRechazo").val(ev)
	$("#modalRechazarAsist").modal("show")
}

$("#confirmar_asistencia").on("submit", function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append('action', 'confirmar_asistencia');
	$.ajax({
		url: "../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				titulo = '';
				mensaje = '';
				tipoAlert = '';
				if(json.estatus == 'ok'){
					titulo = 'Confirmado';
					tipoAlert = 'success'
				}else{
					titulo = 'Ocurrió un error'
					tipoAlert = 'info'
				}

				swal({
	                title: titulo,
	                text: mensaje,
	                icon: tipoAlert,
	              });
			}catch(e){
				console.log(e);
				console.log(data);
			}
			actualizar_lista_prospectos('evento', $("#id_interes").val());
		},
		error: function(){
		},
		complete: function(){
			$("#modalConfirmaAsist").modal('hide');
			$(".outerDiv_S").css("display", "none")
		}
	});
})

$("#rechazar_asistencia").on("submit", function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append('action', 'rechazar_asistencia');
	$.ajax({
		url: "../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				titulo = '';
				mensaje = '';
				tipoAlert = '';
				if(json.estatus == 'ok'){
					titulo = 'Asistencia a evento cancelada';
					tipoAlert = 'success'
				}else{
					titulo = 'Ocurrió un error'
					tipoAlert = 'info'
				}

				swal({
	                title: titulo,
	                text: mensaje,
	                icon: tipoAlert,
	              });
			}catch(e){
				console.log(e);
				console.log(data);
			}
			actualizar_lista_prospectos('evento', $("#id_interesRechazo").val());
		},
		error: function(){
		},
		complete: function(){
			$("#modalRechazarAsist").modal('hide');
			$(".outerDiv_S").css("display", "none")
		}
	});
})

function actualizar_lista_prospectos(tipo, idInteres){
	fData = {
		action:"actualizar_lista_prospectos",
		tipo: tipo,
		idInteres: idInteres
	}
	$.ajax({
		url: "../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				if(json.estatus == 'ok'){
					if(tipo == 'evento'){
						ix_repl = list_eventos.findIndex(elm => elm.idEvento == idInteres)
						list_eventos[ix_repl] = json.data;
						detalle(idInteres);
					}
				}else{
					window.location.refresh();
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$(".outerDiv_S").css("display", "none")
		}
	});
}

function seguimiento(id_reg){

	fData = {
		action : "historial_seguimientos",
		prospecto : id_reg
	}
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				seguimientos_h = JSON.parse(data);
				deta_u_coment = '<i>no hay comentarios</i>';
				fe_u_coment = '--';
				
				if(seguimientos_h.estatus == 'ok'){
					seguimientos_h = seguimientos_h.data;
					// desglosar historial comentarios
					if(seguimientos_h.comentarios && seguimientos_h.comentarios.length > 0){
						deta_u_coment = seguimientos_h.comentarios[0].fecha;
						fe_u_coment = seguimientos_h.comentarios[0].detalles;

						$("#tabla_seguimientos").DataTable().clear();
						for (i = 0; i < seguimientos_h.comentarios.length; i++) {
							$("#tabla_seguimientos").DataTable().row.add([
								seguimientos_h.comentarios[i].fecha,
								seguimientos_h.comentarios[i].detalles
								]);
						}
						$("#tabla_seguimientos").DataTable().columns.adjust().draw();
					}else{
						$("#tabla_seguimientos").DataTable().clear();
						$("#tabla_seguimientos").DataTable().draw();
					}
					// desglosar historial llamadas
				}

				$("#detalle_fecha_comment").html(deta_u_coment);
				$("#detalle_ult_comment").html(fe_u_coment);
				$("#id_atencion").val(id_reg);
			}catch(e){
				console.log(e);
				console.log(data);
			}
				$("#modal_seguimiento").modal('show');
		},
		error: function(){
		},
		complete: function(){
			$(".outerDiv_S").css("display", "none")
		}
	});
}

$("#btn_agregar_comentario").on("click", function(){
	$("#modal_comentario").modal("show")
});

$("#form-comentario").on("submit", function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'agregar_comentario')
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				result = JSON.parse(data);
				if (result.estatus == 'ok') {
					swal({
						title:'Agregado',
						text:'',
						icon:'success'
					})
				}else{
					swal({
						title:'Oops',
						text:'ocurrió un error interno',
						icon:'info'
					})
				}
				seguimiento($("#id_atencion").val());
				$("#form-comentario")[0].reset()
				$("#modal_comentario").modal("hide")
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$(".outerDiv_S").css("display", "none")
		}
	});
})

function pago(id_reg, evento, nombre){
	$("#lbl_persona_pago").html(`Registrar pago para: <br> <b>${nombre}</b>`)
	$('#person_pago').val(id_reg)
	$('#evento_pago').val(evento)
	$("#modal_registrar_pago").modal('show')
}
$("#form_registrar_pago").on('submit', function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'pago_prospecto')

	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$("#form_registrar_pago").find('button[type=submit]').attr('disabled',true)
		},
		success: function(data){
			try{
				resp = JSON.parse(data);
				if(resp.estatus == 'ok'){
					swal({icon:'success', title:'Pago registrado'})
				}else{
					swal({icon:'info', title:'Ha ocurrido un error', text:resp.info})
				}

				$("#modal_registrar_pago").modal('hide')
				$("#home-tab").click();
				$("#carrer-tab").click();
				cargar_eventos();
				cargar_carreras();
				$("#form_registrar_pago")[0].reset()

				console.log(data)
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#form_registrar_pago").find('button[type=submit]').attr('disabled',false)
		}
	});
})

function conceptos_pago(){
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: {action:'conceptosPago'},
		beforeSend : function(){
		},
		success: function(data){
			try{
				conceptos = JSON.parse(data);
				$("#tipo_pago").html(conceptos.reduce((acc, item) => { return acc+=`<option value="${item.id_concepto}">${item.descripcion}</option>`}, ''))
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
		}
	});
}