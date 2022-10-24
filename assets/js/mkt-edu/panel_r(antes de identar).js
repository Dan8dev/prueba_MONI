let list_eventos = [];
let list_carreras = [];

let promos_conceptos = [];

let conceptos_pagar = [];

let porcent_recargo = 0;

let monto_promo = 0;

 
const tipo_duracion = {h:'horas', d:'días', s:'semanas', m:'meses'};
const estatus_seguimiento = ['1-pendiente', '2-en espera', '3-confirmado', '4-rechazado', '5-no interesado'];
const estatus_llamadas = ['pendiente', 'realizada', 'rechazada', 're-agendada'];

const tipos_carreras = {
	1:"Certificación",
	3:"Diplomado",
	6:"Doctorado",
	4:"Licenciatura",
	5:"Maestría",
	2:"TSU"
};

function init_data(){
	cargar_eventos();
	cargar_carreras();
	conceptos_pago();
	$("#home-tab").click();
	$("#carrer-tab").click();
	consultar_lista_llamadas()
	consultar_porcentaje_recargo()
}
$(document).ready(function(){
	init_data();
	lista_atencion();
	//cambioTab();
	$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function(){
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
				
				if($("#tabla_prospectos").length > 0){
					listar_ejecutivas();
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
				
				strNom = prosp.aPaterno+' '+prosp.nombre;
				if(prosp.etapa == 0){
					asistencia = `<div class="row">
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-success but-circle" onclick="confirmar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-check"></i></button>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-primary but-circle" onclick="rechazar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-times"></i></button>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_evento(${prosp.idAsistente}, ${id}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento_e(${prosp.idReg})"><i class="fas fa-comments"></i></button>
					</div>
					</div>`
				}else{
					asistencia = estatus_seguimiento[parseInt(prosp.etapa)];
					asistencia+=`<br><button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento_e(${prosp.idReg})"><i class="fas fa-comments"></i></button> <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_evento(${prosp.idAsistente}, ${id}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>`
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
				
				stringPagos = (prosp.hasOwnProperty('pagos_realizados') && prosp.pagos_realizados.length > 0)? `<a href="javascript:void(0)" onclick="ver_detalles_pagos(${id},${prosp.idAsistente})">${prosp.pagos_realizados.length} pagos.<br>${moneyFormat.format(prosp.pagos_realizados.reduce((a,i)=>{return a+parseFloat(i.detalle_pago.purchase_units[0].amount.value)},0))}</a>` : 'sin pagos';
				arrPropectosData = [
					prosp.fecha_registro.substring(0,10),
					prosp.aPaterno+' '+prosp.aMaterno+' '+prosp.nombre,
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
					if(carreras[i].idCarrera != 3){
						rows_tab_e = [
							carreras[i].nombre,
							carreras[i].institucion_nombre,
							(carreras[i].tipo.length > 3 || carreras[i].tipo == '') ? carreras[i].tipo : tipos_carreras[carreras[i].tipo],
							`<a href="javascript:void(0)" onclick="detalleCarrera(${carreras[i].idCarrera})">ver prospectos...</a>`
						];
						$("#table-carreras").DataTable().row.add(rows_tab_e);	
					}
				}

				$("#table-carreras").DataTable().draw();
				$("#table-carreras").DataTable().columns.adjust();
				if($("#tabla_prospectos").length > 0){
					listar_ejecutivas();
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
				strNom = prosp.aPaterno+' '+prosp.aMaterno+' '+prosp.nombre;
				// asistencia = (prosp.etapa == '0')?`<button type="button" class="btn waves-effect btn-success but-circle" onclick="confirmar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-check"></i></button><button type="button" class="btn waves-effect btn-primary but-circle" onclick="rechazar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-times"></i></button>`:estatus_seguimiento[parseInt(prosp.etapa)];
				asistencia=`<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento_e(${prosp.idReg})"><i class="fas fa-comments"></i></button> <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_carrera(${prosp.idAsistente}, ${idC}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>`
				
				stringPagos = (prosp.hasOwnProperty('pagos_realizados') && prosp.pagos_realizados.length > 0)? `<a href="javascript:void(0)" onclick="ver_detalles_pagos(${idC},${prosp.idAsistente},'c')">${prosp.pagos_realizados.length} pagos.<br>${moneyFormat.format(prosp.pagos_realizados.reduce((a,i)=>{return a+parseFloat(i.detalle_pago.purchase_units[0].amount.value)},0))}</a>` : 'sin pagos';
				
				arrPropectosData = [
					prosp.fecha_registro.substring(0,10),
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
				asignaciones = JSON.parse(data);
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
				
				if(json.error == 'no_session'){
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function(){
						window.location.replace("index.php");
					}, 2000);
				}
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
				
				if(json.error == 'no_session'){
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function(){
						window.location.replace("index.php");
					}, 2000);
					}
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

function seguimiento_e(id_reg){

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
					if(seguimientos_h.hasOwnProperty('comentarios') && seguimientos_h.comentarios.length > 0){
						deta_u_coment = seguimientos_h.comentarios[0].fecha;
						fe_u_coment = seguimientos_h.comentarios[0].detalles;
						if(fe_u_coment.includes('|')){
							fe_u_coment = fe_u_coment.split('|')[0];
							fe_u_coment+=`	<i class="fa fa-phone-square"></i>`;
						}

						$("#tabla_seguimientos").DataTable().clear();
						for (i = 0; i < seguimientos_h.comentarios.length; i++) {
							d_coment = seguimientos_h.comentarios[i].detalles;
							if(d_coment.includes('|')){
								d_coment = d_coment.split('|')[0];
								d_coment+=`	<i class="fa fa-phone-square"></i>`;
							}
							$("#tabla_seguimientos").DataTable().row.add([
								seguimientos_h.comentarios[i].fecha,
									d_coment
								]);
						}
						$("#tabla_seguimientos").DataTable().columns.adjust().draw();
					}else{
						$("#tabla_seguimientos").DataTable().clear();
						$("#tabla_seguimientos").DataTable().draw();
					}
					// desglosar historial llamadas

					if(seguimientos_h.hasOwnProperty('llamadas') && seguimientos_h.llamadas.length > 0){

						$("#tabla_seguimientos_llamadas").DataTable().clear();
						for (i = 0; i < seguimientos_h.llamadas.length; i++) {
							if(seguimientos_h.llamadas[i].estatus == '1'){
								estatus_llamada = `<div><select class="form-control" onchange="select_status_llamada(this)" llamada="${seguimientos_h.llamadas[i].idLlamada}" atencion="${seguimientos_h.llamadas[i].idAtencion}">
                                    <option value="1" selected="">Pendiente</option>
                                    <option value="2">Realizada</option>
                                    <option value="3">Rechazada</option>
                                    <option value="4">Postergada</option>
                                  </select></div>`;
							}else{
								estatus_llamada = estatus_llamadas[parseInt(seguimientos_h.llamadas[i].estatus)-1];
							}
							comentario_ll = seguimientos_h.llamadas[i].detalles != null ? seguimientos_h.llamadas[i].detalles.substr(0,seguimientos_h.llamadas[i].detalles.indexOf('|')) : '-'
							$("#tabla_seguimientos_llamadas").DataTable().row.add([
									seguimientos_h.llamadas[i].fecha_llamar,
									comentario_ll,
									estatus_llamada
								]);
						}
						$("#tabla_seguimientos_llamadas").DataTable().columns.adjust().draw();
					}else{
						$("#tabla_seguimientos_llamadas").DataTable().clear();
						$("#tabla_seguimientos_llamadas").DataTable().draw();
					}
				}

				$("#detalle_fecha_comment").html(deta_u_coment);
				$("#detalle_ult_comment").html(fe_u_coment);
				$("#id_atencion").val(id_reg);
				$("#prospecto_llamar").val(id_reg);
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
let atencion_llam = null;
function select_status_llamada(element){
	opcion_sel = $(element).find("option:selected").text()
	llamada_id = $(element).attr('llamada');
	atencion_id = $(element).attr('atencion');
	if(atencion_llam == null){
		atencion_llam = llamada_id;
	}else if(llamada_id != atencion_llam){
		$("#fecha_llamada").parent().remove()
		$("#comment_llamada").parent().remove()
		atencion_llam = llamada_id;
	}

	atencion_llam = llamada_id;

	if(opcion_sel == 'Realizada'){
		if($("#fecha_llamada").length > 0){
			$("#fecha_llamada").parent().remove()
		}
		input_coment = `<div class="form-group">
			<label class="mb-0 mt-2">Agregue un comentario (opcional).</label>
			<input type="text" class="form-control" id="comment_llamada">
			<button class="btn btn-primary btn-sm" onclick="guardar_comentario_llamada(${llamada_id},${atencion_id})">Guardar</button>
		</div>`
		$(element).parent().append(input_coment)
	}else if(opcion_sel == 'Postergada'){
		if($("#comment_llamada").length > 0){
			$("#comment_llamada").parent().remove()
		}

		input_postergar = `<div class="form-group">
			<label class="mb-0 mt-2">Fecha para devolver llamada.</label>
			<input type="date" class="form-control" id="fecha_llamada">

			<input type="time" class="form-control" id="hora_llamada">
			<button class="btn btn-primary btn-sm" onclick="reagendar_llamada(${llamada_id},${atencion_id})">Guardar</button>
		</div>`
		$(element).parent().append(input_postergar)
	}else if(opcion_sel == 'Pendiente'){

	}else{
		if($("#comment_llamada").length > 0){
			$("#comment_llamada").parent().remove()
		}
		if($("#fecha_llamada").length > 0){
			$("#fecha_llamada").parent().remove()
		}

		swal({
			icon:'info',
			text:'Desea marcar la llamada como rechazada?',
			buttons:['No','Si']
		}).then((willDelete) => {
            if (willDelete) {
                fdata = {action:'actualizar_estatus_llamada', idLlamada:llamada_id, estatus_cambio :3};
                // if()
                $.ajax({
                    url: '../assets/data/Controller/prospectos/prospectoControl.php',
                    type: "POST",
                    data: fdata,
                    beforeSend : function(){
                        $("#loader").css("display", "block")
                    },
                    success: function(data){
                        try{
                            resp = JSON.parse(data);
                            // if(resp.estatus == 'ok'){
                            //     swal({
                            //         icon:'success',
                            //         text:'La llamada fue marcada como.'
                            //     })
                            // }else{
                            //     swal({icon:'info', text:resp.info})
                            // }
                            seguimiento_e($("#id_atencion").val());
                            consultar_lista_llamadas();
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
            }else{
            	$(element).val('1')
            }
          })
	}
	// $.ajax({
	// 	url: "../assets/data/Controller/prospectos/prospectoControl.php",
	// 	type: "POST",
	// 	data: {action:'cambiar_estatus_llamada', seguimiento:id},
	// 	beforeSend : function(){
	// 		$(".outerDiv_S").css("display", "block")
	// 	},
	// 	success: function(data){
	// 		try{
	// 			resp = JSON.parse(data)
	// 			console.log(resp)
	// 			if(resp.estatus == 'ok'){
	// 				swal({icon:'success',text:'Se cambio el estatus de llamada'});
	// 			}
	// 		}catch(e){
	// 			console.log(e);
	// 			console.log(data);
	// 		}
	// 	},
	// 	error: function(){
	// 	},
	// 	complete: function(){
	// 		$(".outerDiv_S").css("display", "none")
	// 	}
	// });
}

function guardar_comentario_llamada(idllamada, idatencion){
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: {action:'agregar_comentario_llamada', idLlamada:idllamada, idAtencion: idatencion, comentario:$("#comment_llamada").val()},
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				result = JSON.parse(data);
				console.log(result)
				if(result.error == 'no_session'){
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function(){
						window.location.replace("index.php");
					}, 2000);
				}
				seguimiento_e($("#id_atencion").val());
				$("#form-comentario")[0].reset()
				$("#modal_comentario").modal("hide")
				consultar_lista_llamadas()
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

function reagendar_llamada(idllamada, idatencion){
	fecha_ll = $("#fecha_llamada").val().trim()
	hora_ll = $("#hora_llamada").val().trim()
	if(fecha_ll != '' && hora_ll != ''){
		$.ajax({
			url: "../assets/data/Controller/prospectos/prospectoControl.php",
			type: "POST",
			data: {action:'actualizar_estatus_llamada', estatus_cambio:4, fecha_llamada: fecha_ll+' '+hora_ll, atencion:idatencion, idLlamada: idllamada},
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				try{
					result = JSON.parse(data);
					if (result.estatus == 'ok') {
						swal({
							title:'Llamada agendada.',
							text:'',
							icon:'success'
						})
					}else{
						console.log(result)
						swal({
							title:'Oops',
							text:'ocurrió un error interno',
							icon:'info'
						})
					}
					if(result.error == 'no_session'){
						swal({
							title: "Vuelve a iniciar sesión!",
							text: "La informacion no se actualizó",
							icon: "info",
						});
						setTimeout(function(){
							window.location.replace("index.php");
						}, 2000);
					}
					seguimiento_e($("#id_atencion").val());
					consultar_lista_llamadas()
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
	}else{
		swal({
			text:'Defina fecha y hora para reasignar la llamada'
		})
	}
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
				if(result.error == 'no_session'){
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function(){
						window.location.replace("index.php");
					}, 2000);
				}
				seguimiento_e($("#id_atencion").val());
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

$("#form_agendar_llamada").on("submit", function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'agendar_llamada')
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
					console.log(result)
					swal({
						title:'Oops',
						text:'ocurrió un error interno',
						icon:'info'
					})
				}
				if(result.error == 'no_session'){
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function(){
						window.location.replace("index.php");
					}, 2000);
				}
				seguimiento_e($("#id_atencion").val());
				$("#form_agendar_llamada")[0].reset()
				consultar_lista_llamadas();
				// $("#modal_comentario").modal("hide")
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

// function pago(id_reg, evento, nombre){
// 	$("#lbl_persona_pago").html(`Registrar pago para: <br> <b>${nombre}</b>`)
// 	$('#person_pago').val(id_reg)
// 	$('#evento_pago').val(evento)
// 	$("#modal_registrar_pago").modal('show')
// }

function pago_carrera(id_reg, evento, nombre){
	$("#lbl_persona_pago").html(`Registrar pago para: <br> <b>${nombre}</b>`)
	$("#tipo_pago").html('')
	$("#notifica_fechap").html('')
	$("#inp_promos_disp").attr('disabled', false)
	$("#inp_monto_pago").removeClass('text-danger')
	promos_conceptos = [];
	conceptos_pagar = [];
	$.ajax({
		url: "../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: {
			action: 'obtener_plan_pago_callcenter',
			prospecto: id_reg,
			inscrito_a: evento,
			proced: 'modulo'
		},
		success: function(data){
			try{
				var resp = JSON.parse(data);
				//console.log(resp)
				$("#form_registrar_pago")[0].reset()
				$("#check_solo_inscripciones").prop('checked', false)
				var opc = `<option value="" selected disabled>Seleccione</option>`
				var opc_generaciones = `<option disabled value="" selected>Seleccione</option>`
				$("#inp_promos_disp").html(`<option value="" selected>Seleccione</option>`)
				$("#tab_registrar_pag").click();
				$("#tabla_pagos_notificados").DataTable().clear();
				$("#tab_generacion_pag").css("display", "none");

				$("#tab_generacion_pag").removeClass('disabled')

				if(resp.data){
					promos_conceptos = resp.data.generaciones;
					conceptos_pagar = resp.data.pagos_aplicar;
					for(var g in resp.data.generaciones){
						if(resp.data.generaciones[g].asignacion.length > 0 ){
							$("#tab_generacion_pag").addClass('disabled')
						}
					}
					for(var i in resp.data.pagos_aplicar){
						aplicados_aprobados = resp.data.pagos_aplicar[i].aplicados.reduce( (acc, it)=>{
							acc += (it.estatus == 'verificado' || it.estatus == 'pendiente')? 1 : 0;
							return acc;
						}, 0);
						if(aplicados_aprobados < parseInt(resp.data.pagos_aplicar[i].numero_pagos)){
							opc += `<option value="${resp.data.pagos_aplicar[i].id_concepto}" category="${resp.data.pagos_aplicar[i].categoria}" data-precio="${resp.data.pagos_aplicar[i].precio}">${resp.data.pagos_aplicar[i].concepto} ${ (resp.data.pagos_aplicar[i].fechalimitepago !== null)? '('+resp.data.pagos_aplicar[i].fechalimitepago.substr(0,10)+')' : ''}</option>`
						}
						for(var j in resp.data.pagos_aplicar[i].aplicados){
							aplicado_p = resp.data.pagos_aplicar[i].aplicados[j];

							if(aplicado_p.categoria == 'Inscripción' && aplicado_p.estatus == 'verificado'){
								$("#alumno_generacion").val(id_reg)
								$("#tab_generacion_pag").css("display", "block");
							}
							$("#tabla_pagos_notificados").DataTable().row.add([
								`<span title="${aplicado_p.fechapago}">${aplicado_p.fechapago.substr(0,10)}</span>`,
								aplicado_p.concepto_nom,
								moneyFormat.format(aplicado_p.montopagado),
								((aplicado_p.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_p.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								aplicado_p.estatus.toUpperCase()
							])
						}
					}

					for(var i in resp.data.generaciones){
						opc_generaciones+=`<option value="${resp.data.generaciones[i].idGeneracion}">${resp.data.generaciones[i].nombre}</option>`
					}
				}
				$("#tabla_pagos_notificados").DataTable().draw();
				$("#tipo_pago").html(opc)
				$("#select_alumno_gen").html(opc_generaciones)
				$("#modal_registrar_pago").modal('show')
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	})
	
	$('#person_pago').val(id_reg)
	$('#evento_pago').val(evento)
	// $("#modal_registrar_pago").modal('show')
}

function pago_evento(id_reg, evento, nombre){
	$("#lbl_persona_pago").html(`Registrar pago para: <br> <b>${nombre}</b>`)
	$("#tipo_pago").html('')
	$("#notifica_fechap").html('')
	$("#inp_promos_disp").attr('disabled', false)
	$("#inp_monto_pago").removeClass('text-danger')
	promos_conceptos = [];
	conceptos_pagar = [];
	$.ajax({
		url: "../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: {
			action: 'obtener_plan_pago_callcenter_eventos',
			prospecto: id_reg,
			inscrito_a: evento
		},
		success: function(data){
			try{
				var resp = JSON.parse(data);
				console.log(resp)
				$("#form_registrar_pago")[0].reset()
				$("#check_solo_inscripciones").prop('checked', false)
				var opc = `<option value="" selected disabled>Seleccione</option>`
				
				$("#inp_promos_disp").html(`<option value="" selected>Seleccione</option>`)
				$("#tab_registrar_pag").click();
				$("#tabla_pagos_notificados").DataTable().clear();
				$("#tab_generacion_pag").css("display", "none");

				if(resp.data){
					promos_conceptos = resp.data.generaciones;
					conceptos_pagar = resp.data.pagos_aplicar;
					
					for(var i in resp.data.pagos_aplicar){
						if(parseInt(resp.data.pagos_aplicar[i].aplicados.length) < parseInt(resp.data.pagos_aplicar[i].numero_pagos)){
							opc += `<option value="${resp.data.pagos_aplicar[i].id_concepto}" category="${resp.data.pagos_aplicar[i].categoria}" data-precio="${resp.data.pagos_aplicar[i].precio}">${resp.data.pagos_aplicar[i].concepto} (${resp.data.pagos_aplicar[i].fechalimitepago.substr(0,10)})</option>`
						}
						for(var j in resp.data.pagos_aplicar[i].aplicados){
							aplicado_p = resp.data.pagos_aplicar[i].aplicados[j];

							$("#tabla_pagos_notificados").DataTable().row.add([
								aplicado_p.fechapago,
								aplicado_p.concepto_nom,
								moneyFormat.format(aplicado_p.montopagado),
								((aplicado_p.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_p.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								aplicado_p.estatus.toUpperCase()
							])
						}
					}

				}
				$("#tabla_pagos_notificados").DataTable().draw();
				$("#tipo_pago").html(opc)
				
				$("#modal_registrar_pago").modal('show')
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	})
	
	$('#person_pago').val(id_reg)
	$('#evento_pago').val(evento)
	// $("#modal_registrar_pago").modal('show')
}

$("#form_asignar_generacion").on('submit', function(e){
	e.preventDefault();
	fdata = new FormData(this)
	fdata.append('action', 'asignar_generacion');
	$.ajax({
		url: '../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: fdata,
		contentType:false,
		processData:false,
		beforeSend : function(){
		},
		success: function(data){
			try{
				resp = JSON.parse(data);
				if(resp.estatus == 'ok'){
					swal({
						title: "Registro exitoso",
						text: "Se ha asignado la generación al alumno",
						icon: "success"
					})
				}else{
					swal({
						title: "Error",
						text: resp.info,
						icon: "info"
					})
				}
				$("#form_asignar_generacion")[0].reset();
				$("#modal_registrar_pago").modal('hide')
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
})

$("#tipo_pago").change(function(){
	if($("#tipo_pago").val()){
		monto_promo = 0;
		let concepto_p = conceptos_pagar.find( elm => elm.id_concepto == $(this).val() );
		
		var opc_promos = `<option value="" selected>Seleccione</option>`
		var disponibles = 0;
		for(var d in promos_conceptos){
			for(var f in promos_conceptos[d].promociones){
				if(parseFloat(promos_conceptos[d].promociones[f].porcentaje) > 0 && (parseInt(promos_conceptos[d].promociones[f].id_concepto) == parseInt($("#tipo_pago").val()))){
					disponibles++;
					opc_promos += `<option value="${promos_conceptos[d].promociones[f].idPromocion}" data-porcent="${promos_conceptos[d].promociones[f].porcentaje}">${promos_conceptos[d].promociones[f].nombrePromocion}&nbsp;${promos_conceptos[d].promociones[f].porcentaje}%</option>`
				}
			}
		}
		if(disponibles == 0){
			opc_promos = `<option value="">No hay promociones disponibles</option>`
		}
		$("#inp_promos_disp").html(opc_promos)
		// editar el monto a pagar
		if(parseInt(concepto_p.parcialidades) == 1){
			$("#inp_promos_disp").attr('readonly', false)
			$(".moneyFt").maskMoney();
		}else{
			$("#inp_promos_disp").attr('readonly', true)
			$(".moneyFt").maskMoney('destroy');
		}
		// fechas limite de pago
		if($("#inp_fecha_pago").val().trim() !== ''){
			var fecha_aplicado_pago = new Date($("#inp_fecha_pago").val()+" 00:00:00");
			var fecha_limite_pago = new Date(concepto_p.fechalimitepago);
			pago_normal = parseFloat(concepto_p.precio)
			if(fecha_aplicado_pago > fecha_limite_pago && concepto_p.categoria != 'Inscripción'){
				pago_recargos = pago_normal + (pago_normal * (porcent_recargo / 100))
				$("#inp_monto_pago").val(moneyFormat.format(pago_recargos))
				$("#inp_monto_pago").addClass('text-danger')
				$("#notifica_fechap").html(`<div class="alert alert-danger" role="alert"><p>Se está registrando el pago posterior a la fecha de vencimiento, es posible que se apliquen recargos.</p></div>`)
				$("#inp_promos_disp").val('')
				$("#inp_promos_disp").attr('disabled', true)
				monto_promo = 0
			}else{
				$("#inp_monto_pago").removeClass('text-danger')
				$("#notifica_fechap").html('')
				if(monto_promo != 0){
					$("#inp_monto_pago").val(moneyFormat.format(monto_promo))
				}else{
					$("#inp_monto_pago").val(moneyFormat.format(concepto_p.precio))
				}
				
			}
		}
		
		$("#inp_fecha_pago").change(function(){
			if($("#inp_fecha_pago").val().trim() !== ''){
				fecha_aplicado_pago = new Date($("#inp_fecha_pago").val()+" 00:00:00");
				fecha_limite_pago = new Date(concepto_p.fechalimitepago);
				pago_normal = parseFloat(concepto_p.precio)
				if(fecha_aplicado_pago > fecha_limite_pago && concepto_p.categoria != 'Inscripción'){
					pago_recargos = pago_normal + (pago_normal * (porcent_recargo / 100))
					$("#inp_monto_pago").val(moneyFormat.format(pago_recargos))
					$("#inp_monto_pago").addClass('text-danger')
					$("#notifica_fechap").html(`<div class="alert alert-danger" role="alert"><p>Se está registrando el pago posterior a la fecha de vencimiento, es posible que se apliquen recargos.</p></div>`)
					$("#inp_promos_disp").val('')
					$("#inp_promos_disp").attr('disabled', true)
					monto_promo = 0
				}else{
					$("#inp_monto_pago").removeClass('text-danger')
					$("#notifica_fechap").html('')
					if(monto_promo != 0){
						$("#inp_monto_pago").val(moneyFormat.format(monto_promo))
					}else{
						$("#inp_monto_pago").val(moneyFormat.format(concepto_p.precio))
					}
					
				}
			}
		})
	}else{
		$("#form_registrar_pago")[0].reset()
		$("#inp_monto_pago").removeClass('text-danger')
		$("#notifica_fechap").html('')
	}
})
$("#inp_promos_disp").change(function(){
	if($("#inp_promos_disp option:selected").data('porcent')){
		var porcent = parseFloat($("#inp_promos_disp option:selected").data('porcent'))
		var precio = parseFloat($("#tipo_pago option:selected").data('precio'))
		var total = precio - (precio * porcent) / 100
		monto_promo = total;
		$("#inp_monto_pago").val(moneyFormat.format(total))
	}else{
		$("#inp_monto_pago").val(moneyFormat.format($("#tipo_pago option:selected").data('precio')))
	}
})

$("#form_registrar_pago").on('submit', function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'pago_prospecto')

	$.ajax({
		url: "../assets/data/Controller/planpagos/pagosControl.php",
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
				if(resp.error == 'no_session'){
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function(){
						window.location.replace("index.php");
					}, 2000);
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
				$("#tipo_pago").html(conceptos.reduce((acc, item) => { return acc+=(item.precio != 'GRATIS')? `<option value="${item.id_concepto}">${item.descripcion}</option>` : ''}, ''));
				$("#tipo_pago_adm").html(conceptos.reduce((acc, item) => { return acc+=(item.precio != 'GRATIS')? `<option value="${item.id_concepto}">${item.descripcion}</option>` : ''}, ''));
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

function consultar_lista_llamadas(){
	$.ajax({
		url: "../assets/data/Controller/marketing/marketingControl.php",
		type: "POST",
		data: {action:'agenda_llamadas'},
		beforeSend : function(){
		},
		success: function(data){
			try{
				llamadas = JSON.parse(data);
				if(llamadas.estatus == 'ok'){
					llamadas = llamadas.data;
					alert_danger = false;
					alert_warning = false;
					alert_success = false;
					fecha_compare = new Date();
					fecha_compare_dos = new Date();
					fecha_compare_dos = fecha_compare_dos.setDate(fecha_compare_dos.getDate() + 2);

					list_alerts = "";

					for (i = 0; i < llamadas.length; i++) {
						if(llamadas[i].estatus == 1){
							fecha_llamada_r = new Date(llamadas[i].fecha_llamar);
							text_not = ""
							clas_not = "";
							
							
							if(fecha_llamada_r <= fecha_compare_dos){
								text_not = "Llamada proxima para <br>"+llamadas[i].prospecto_llamar;
								clas_not = "warning";
								alert_warning = true;
								if(fecha_llamada_r <= fecha_compare){
									text_not = "Llamada atrasada para <br>"+llamadas[i].prospecto_llamar;
									clas_not = "danger";
									alert_danger = true;
								}
							}else{
								alert_success = true;
								clas_not = "success";
								text_not = "Llamar a <br>"+llamadas[i].prospecto_llamar;
							}

							list_alerts += `<div class="dropdown-item notify-item mt-2">
	                            <div class="notify-icon bg-${clas_not}">&nbsp;</div>
	                            <p class="notify-details">${text_not}</p>
	                            <i style="float: right;font-size: small; color:gray;">${llamadas[i].fecha_llamar}</i>
	                        </div>`;
	          }
					}

					if(alert_success){
						$("#span_alert_llamadas").addClass('badge-success')
					}else{
						$("#span_alert_llamadas").removeClass('badge-success')
					}

					if(alert_warning){
						$("#span_alert_llamadas").addClass('badge-warning')
					}else{
						$("#span_alert_llamadas").removeClass('badge-warning')
					}

					if(alert_danger){
						$("#span_alert_llamadas").addClass('badge-danger')
					}else{
						$("#span_alert_llamadas").removeClass('badge-danger')
					}

					$("#list-notification-llamada").html(list_alerts)
				}
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

function set_concepto(select){
	$("#inp_monto_pago").val(moneyFormat.format($(select).find(":selected").attr('data-precio')));
}

$("#check_solo_inscripciones").on('change', function(){
	var statusCh = $(this).prop('checked')
	$("#tipo_pago option").each(function(){
		if(!statusCh){
			$(this).css('display','block')  
		}else{
			if($(this).attr('category') != 'Inscripción'){
				$(this).css('display','none')
			}
		}
	})
})

function consultar_porcentaje_recargo(){
	$.ajax({
		url: '../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'consultar_porcentaje_recargo'},
		success: function(data){
			porcent_recargo = parseFloat(data);
		}
	});
}
