regCorreo = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
let list_eventos = [];
let list_carreras = [];

let promos_conceptos = [];

let conceptos_pagar = [];

let porcent_recargo = 0;

let monto_promo = 0;

let global_concepto = null;


const tipo_duracion = { h: 'horas', d: 'días', s: 'semanas', m: 'meses' };
// const estatus_seguimiento = ['Pendiente', 'En espera', 'Confirmado', 'No cumple el perfil', 'No está interesado'];
const estatus_llamadas = ['pendiente', 'realizada', 'rechazada', 're-agendada'];

const tipos_carreras = {
	1: "Certificación",
	3: "Diplomado",
	6: "Doctorado",
	7: "Especialidad",
	4: "Licenciatura",
	5: "Maestría",
	2: "TSU"
};
const color_estatus = {
	rechazado:'danger',
	verificado:'success',
	pendiente:'warning fw-bold'
}

function init_data() {
	cargar_eventos();
	cargar_carreras();
	// conceptos_pago();
	consultar_lista_llamadas();
	consultar_porcentaje_recargo();
	reload_tablas();
}
$(document).ready(function () {
	getPaises();
	cargar_instituciones()
	if ($("#tabla_prospectos").length > 0) {
		listar_ejecutivas();
	}
	init_data();
	lista_atencion();
	//cambioTab();
	$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function () {
		$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
	});
});

function cargar_eventos() {
	list_eventos = [];
	fData = {
		action: "listado_eventos"
	}
	$.ajax({
		url: "../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				events = JSON.parse(data);

				$("#table-eventos").DataTable().clear();

				for (i = 0; i < events.length; i++) {
					list_eventos.push(events[i]);

					rows_tab_e = [
						events[i].tipo,
						events[i].titulo,
						events[i].fechaE,
						events[i].duracion + " " + tipo_duracion[events[i].tipoDuracion],
						`<a href="javascript:void(0)" onclick="detalle(${events[i].idEvento})">${events[i].lugares_reserv + "/" + events[i].limiteProspectos}</a>`
					];

					$("#table-eventos").DataTable().row.add(rows_tab_e);
				}

				$("#table-eventos").DataTable().draw();
				$("#table-eventos").DataTable().columns.adjust();

				
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
}
function detalle(id) {
	ev_r = list_eventos.find(element => element.idEvento == id);
	hsPrp = ev_r.hasOwnProperty('estatus_info');

	$("#lblTitleEvento_confirm").html(ev_r.titulo);

	$(".lblTotalConfirmados").html((hsPrp) ? ev_r.estatus_info.confirmado : 0);
	$(".lblTotalPendientes").html((hsPrp) ? ev_r.estatus_info.pendientes : 0);
	$(".lblTotalRechazados").html((hsPrp) ? ev_r.estatus_info.rechazo : 0);

	$("#listado_prospectos").DataTable().clear();

	if (ev_r.hasOwnProperty("prospectos_eventos") && ev_r.prospectos_eventos.length > 0) {
		for (i = 0; i < ev_r.prospectos_eventos.length; i++) {
			prosp = ev_r.prospectos_eventos[i];

			asistencia = ''

			strNom = prosp.aPaterno + ' ' + prosp.nombre;
			if (prosp.etapa == 1) {
				asistencia = `<div class="row">
					<div class="col-3">
						<div class="btn-group" role="group">
							<button id="btnGroupDrop1" type="button" class="btn waves-effect btn-secondary but-circle dropdown-toggle" aria-expanded="false" data-toggle="dropdown">
							<i class="fas fa-clipboard-check"></i>
							</button>
							<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
								<li><a class="dropdown-item pl-2" href="#" onclick="status_prosp(this, ${id} ,'e')" data-i="${prosp.idReg}" info-stat="confirmado">
									<span class="border-left border-primary">&nbsp;Confirmado</span>
								</a></li>
								<li><a class="dropdown-item pl-2" href="#" onclick="status_prosp(this, ${id} ,'e')" data-i="${prosp.idReg}" info-stat="rechazo">
									<span class="border-left border-primary">&nbsp;No cumple el perfil</span>
								</a></li>
								<li><a class="dropdown-item pl-2" href="#" onclick="status_prosp(this, ${id} ,'e')" data-i="${prosp.idReg}" info-stat="nointeresado">
									<span class="border-left border-primary">&nbsp;No está interesado</span>
								</a></li>

								<li><a class="dropdown-item pl-2" href="#" onclick="cambio_interes(${id}, ${prosp.idReg},'e')" >
									<span class="border-left border-warning">&nbsp;Cambiar evento.</span>
									<i class="fas fa-exchange-alt"></i>
								</a></li>
							</ul>
						</div>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_evento(${prosp.idAsistente}, ${id}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>
					</div>
					<div class="col-3">
						<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento_e(${prosp.idReg},'${prosp.aPaterno.trim()} ${prosp.aMaterno.trim()} ${prosp.nombre.trim()}')"><i class="fas fa-comments"></i></button>
					</div>
					</div>`
			} else {
				asistencia = estatus_seguimiento[parseInt(prosp.etapa)];
				asistencia += `<br><button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento_e(${prosp.idReg},'${prosp.aPaterno.trim()} ${prosp.aMaterno.trim()} ${prosp.nombre.trim()}')"><i class="fas fa-comments"></i></button> <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_evento(${prosp.idAsistente}, ${id}, '${strNom}')"><i class="fas fa-money-bill-wave"></i></button>`
			}

			stringPagos = (prosp.hasOwnProperty('pagos_realizados') && prosp.pagos_realizados.length > 0) ? `<a href="javascript:void(0)" onclick="ver_detalles_pagos(${id},${prosp.idAsistente})">${prosp.pagos_realizados.length} pagos.<br>${moneyFormat.format(prosp.pagos_realizados.reduce((a, i) => { return a += parseFloat(i.detalle_pago.purchase_units[0].amount.value) }, 0))}</a>` : 'sin pagos';
			arrPropectosData = [
				prosp.fecha_registro.substring(0, 10),
				prosp.aPaterno + ' ' + prosp.aMaterno + ' ' + prosp.nombre,
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

function cargar_carreras() {
	fData = {
		action: "listado_carreras"
	}
	$.ajax({
		url: "../assets/data/Controller/carreras/carrerasControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				carreras = JSON.parse(data)

				$("#table-carreras").DataTable().clear();
				list_carreras = [];
				for (i = 0; i < carreras.length; i++) {
					list_carreras.push(carreras[i]);
					if (carreras[i].idCarrera != 3 && carreras[i].callcenter == 1) {
						rows_tab_e = [
							carreras[i].nombre,
							carreras[i].institucion_nombre,
							(carreras[i].tipo.length > 3 || carreras[i].tipo == '') ? carreras[i].tipo : tipos_carreras[carreras[i].tipo],
							`<a href="javascript:void(0)" onclick="detalleCarrera(${carreras[i].idCarrera}, ${carreras[i].idInstitucion})">ver prospectos...</a>`
						];
						$("#table-carreras").DataTable().row.add(rows_tab_e);
					}
				}

				$("#table-carreras").DataTable().draw();
				$("#table-carreras").DataTable().columns.adjust();
				
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
}

function detalleCarrera(idC, inst) {

	carr_i = list_carreras.find(element => element.idCarrera == idC);
	hsPrp = carr_i.hasOwnProperty('estatus_info');

	$("#lblTitleCarrera_confirm").html(carr_i.nombre);

	$(".lblTotalConfirmadosCrr").html((hsPrp) ? carr_i.estatus_info.confirmado : 0);
	$(".lblTotalPendientesCrr").html((hsPrp) ? carr_i.estatus_info.pendientes : 0);
	$(".lblTotalRechazadosCrr").html((hsPrp) ? carr_i.estatus_info.rechazo : 0);

	$("#listado_prospectos_carreras").DataTable().clear();

	if (hsPrp && carr_i.prospectos_carrera.length > 0) {
		for (i = 0; i < carr_i.prospectos_carrera.length; i++) {
			prosp = carr_i.prospectos_carrera[i];
			strNom = prosp.aPaterno + ' ' + prosp.aMaterno + ' ' + prosp.nombre;
			// asistencia = (prosp.etapa == '1')?`<button type="button" class="btn waves-effect btn-success but-circle" onclick="confirmar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-check"></i></button><button type="button" class="btn waves-effect btn-primary but-circle" onclick="rechazar(${id},${prosp.prospecto},'${strNom}')"><i class="fa fa-times"></i></button>`:estatus_seguimiento[parseInt(prosp.etapa)];
				asistencia_c = "<div class='row'>";
			if (prosp.etapa == 1) {
				asistencia_c += `
				<div class="col">
					<div class="btn-group" role="group">
						<button id="btnGroupDrop1" type="button" class="btn waves-effect btn-secondary but-circle dropdown-toggle" aria-expanded="false" data-toggle="dropdown">
						<i class="fas fa-clipboard-check"></i>
						</button>
						<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
							<li><a class="dropdown-item pl-2" href="#" onclick="status_prosp(this, ${idC} ,'c')" data-i="${prosp.idReg}" info-stat="confirmado">
								<span class="border-left border-primary">&nbsp;Confirmado</span>
							</a></li>
							<li><a class="dropdown-item pl-2" href="#" onclick="status_prosp(this, ${idC} ,'c')" data-i="${prosp.idReg}" info-stat="rechazo">
								<span class="border-left border-primary">&nbsp;No cumple el perfil</span>
							</a></li>
							<li><a class="dropdown-item pl-2" href="#" onclick="status_prosp(this, ${idC} ,'c')" data-i="${prosp.idReg}" info-stat="nointeresado">
								<span class="border-left border-primary">&nbsp;No está interesado</span>
							</a></li>

							<li><a class="dropdown-item pl-2" href="#" onclick="cambio_interes(${idC}, ${prosp.idReg},'c')" >
								<span class="border-left border-warning">&nbsp;Cambiar carrera.</span>
								<i class="fas fa-exchange-alt"></i>
							</a></li>
						</ul>
					</div>
				</div>
					`;
			}else{
				asistencia_c += `<div class="col">${estatus_seguimiento[parseInt(prosp.etapa)]}</div>`;
			}
			asistencia_c += `
			<div class="col">
				<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="seguimiento_e(${prosp.idReg},'${prosp.aPaterno.trim()} ${prosp.aMaterno.trim()} ${prosp.nombre.trim()}')"><i class="fas fa-comments"></i></button>
			</div>
			<div class="col">
				<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_carrera(${prosp.idAsistente}, ${idC}, '${strNom}', ${inst})"><i class="fas fa-money-bill-wave"></i></button>
			</div>
			<div class="col">
				<button type="button" class="btn waves-effect btn-secondary but-circle" onclick="editar_prospecto(${prosp.idAsistente})"><i class="fas fa-pencil-alt"></i></button>
			</div>
			</div>`
			stringPagos = '';
			if(prosp.hasOwnProperty('pagos_realizados') && prosp.pagos_realizados.length > 0){
				var monto = prosp.pagos_realizados.reduce((a, i) => { 
					return a += i.estatus == 'verificado' ? parseFloat(i.detalle_pago.purchase_units[0].amount.value) : 0;
				}, 0);
				stringPagos = `<a href="javascript:void(0)" onclick="ver_detalles_pagos(${idC},${prosp.idAsistente},'c')">${prosp.pagos_realizados.length} pagos.<br>${moneyFormat.format(monto)}</a>`;
			}

			arrPropectosData = [
				prosp.fecha_registro.substring(0, 10),
				estatus_seguimiento[parseInt(prosp.etapa)],
				strNom,
				`<small><a href="javascript:void(0)" class="clpb" aria-label="${prosp.correo}">${prosp.correo}</a><br>
									<a href="javascript:void(0)" class="clpb" aria-label="${prosp.telefono}">${prosp.telefono}</a></small>`,
				stringPagos,
				asistencia_c
			];

			$("#listado_prospectos_carreras").DataTable().row.add(arrPropectosData);
		}
	}
	$("#listado_prospectos_carreras").DataTable().draw();
	$("#listado_prospectos_carreras").DataTable().columns.adjust();

	$("#prospect-tab").click();

}

function ver_detalles_pagos(id_e, id_p, tipo = "e") {
	if (tipo == "e") {
		ev_r = list_eventos.find(element => element.idEvento == id_e);
		propspecto_cpagos = ev_r.prospectos_eventos.find(pros => pros.idAsistente == id_p)
	} else {
		ev_r = list_carreras.find(element => element.idCarrera == id_e);
		propspecto_cpagos = ev_r.prospectos_carrera.find(pros => pros.idAsistente == id_p)
	}

	$("#lbl_persona_pago_nom").html(propspecto_cpagos.nombre)
	total_pagos = 0;
	html = '';
	for (i = 0; i < propspecto_cpagos.pagos_realizados.length; i++) {
			if(propspecto_cpagos.pagos_realizados[i].estatus == 'verificado'){
				total_pagos += parseFloat(propspecto_cpagos.pagos_realizados[i].detalle_pago.purchase_units[0].amount.value);
			}
			fe_pago = propspecto_cpagos.pagos_realizados[i].detalle_pago.update_time.substr(0, 10);
			fecha_parts = (fe_pago != '0000-00-00') ? fe_pago.split("-") : '';
			stringFechaP = (fecha_parts == '') ? '' : fecha_parts[2] + " " + meses[parseInt(fecha_parts[1]) - 1] + " " + fecha_parts[0].substr(2, 2);
			var string_pago = '';
			if(propspecto_cpagos.pagos_realizados[i].estatus == 'verificado'){
				string_pago = `<h4 class="text-success">
						<small><b>${stringFechaP}</b></small>
						<span class="float-right">
							${moneyFormat.format(propspecto_cpagos.pagos_realizados[i].detalle_pago.purchase_units[0].amount.value)}
						</span>
					</h4>`;
		  }else if(propspecto_cpagos.pagos_realizados[i].estatus == 'pendiente'){
				string_pago = `<h4 class="text-warning">
						<small><b>${stringFechaP}</b></small>
						<span class="float-right">
							${moneyFormat.format(propspecto_cpagos.pagos_realizados[i].detalle_pago.purchase_units[0].amount.value)}
							<small>Pendiente de verificar</small>
						</span>
					</h4>`;
		  }else{
		  	string_pago = `<h4 class="text-danger">
						<small><b>${stringFechaP}</b></small>
						<span class="float-right">
							Pago rechazado.
						</span>
					</h4>`;
		  }
			html += `<div class="card">
	                <div class="card-heading p-2">
	                  <div>
	                    <p class="text-muted mb-0 mt-2"><b>Concepto: </b><span class="float-right">${propspecto_cpagos.pagos_realizados[i].plan_pago}</span></p>
	                    <p class="mt-2 mb-0 text-muted"><b>Origen: </b><span class="float-right">${propspecto_cpagos.pagos_realizados[i].detalle_pago.id}</span></p>
	                    ${string_pago}
	                  </div>
	                </div>
	              </div>`;
	}
	$("#total_pagos_pr").html(moneyFormat.format(total_pagos));
	$("#list_pagos_realizados").html(html);
	$("#modal_detalles_pagos").modal("show")
}

function lista_atencion() {
	list_eventos = [];
	fData = {
		action: "lista_atencion"
	}
	$.ajax({
		//../assets/data/Controller/eventos/eventosControl.php
		url: "../assets/data/Controller/marketing/marketingControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			asignaciones = JSON.parse(data);
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
}

function status_prosp(elm, id_i, tipo){
	var seguim = elm.getAttribute("data-i");
	var stat = elm.getAttribute("info-stat");
	var txt = elm.text.trim();

	swal({
		text: `¿Cambiar el estatus del prospecto a ${txt}?`,
		icon: 'info',
		buttons: ['Cancelar', 'Aceptar']
	}).then( (value) => {
		if(value){
			$.ajax({
				url: "../assets/data/Controller/prospectos/prospectoControl.php",
				type: "POST",
				data: {action:'cambio_estatus_prospecto', seguim:seguim, stat:stat},
				success: function (data) {
					var resp = JSON.parse(data);
					if(resp.estatus == 'ok' && parseInt(resp.data) > 0){
						swal({
							icon:'success',
							text: 'Estatus se cambiado correctamente'
						}).then(()=>{
							init_data()
						})
					}else{
						swal({
							icon:'info',
							text: resp.info
						})
					}
				}
			});
		}
	} )
}

function cambio_interes(c_e, reg, tipo){
	if(tipo == 'e'){
		var events_options_html = list_eventos.reduce( (a,i)=>{
			a+=(i.idEvento != 35 ? `<option value="${i.idEvento}"> ${i.titulo} </option>` : '');
			return a;
		},'<option selected disabled> Seleccione un evento </option>')
		$("#select_cambio_d").html(events_options_html)
		
		$("#dest_t").val(tipo)
		$("#reg_id").val(reg)
		
		$("#label_cambio").html("Cambio de evento")
		$("#label_cambio_2").html("Seleccione el evento al que desea cambiar")
		$("#modalCambiarDestino").modal("show")
	}else if(tipo == 'c'){
		var carrers_options_html = list_carreras.reduce( (a,i)=>{
			a+=(i.idCarrera != 3 ? `<option value="${i.idCarrera}"> ${i.nombre} </option>` : '');
			return a;
		},'<option selected disabled> Seleccione una carrera</option>')
		$("#select_cambio_d").html(carrers_options_html)
		
		$("#dest_t").val(tipo)
		$("#reg_id").val(reg)
		
		$("#label_cambio").html("Cambio de carrera")
		$("#label_cambio_2").html("Seleccione la carrera a la que desea cambiar")
		$("#modalCambiarDestino").modal("show")
	}
}

$("#form-cambio").on('submit', function(e){
	e.preventDefault();
	fdata = new FormData(this)
	fdata.append('action', 'actualizar_destino');
	$.ajax({
		url: '../assets/data/Controller/prospectos/prospectoControl.php',
		type: "POST",
		data: fdata,
		contentType:false,
		processData:false,
		success: function(data){
			try{
				var resp = JSON.parse(data);
				if(resp.estatus == 'ok'){
					swal({
						icon:'success',
						text: 'Actualizado correctamente'
					}).then( (val)=>{
						$("#modalCambiarDestino").modal("hide")
						init_data()
					})
				}else{
					swal({
						icon:'info',
						text: resp.info
					}).then( (val)=>{
						$("#modalCambiarDestino").modal("hide")
						init_data()
					})
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	});
})

function confirmar(ev, prs, nombre) {
	$("#spanAsist").html(nombre);
	$("#id_asistente").val(prs)
	$("#id_interes").val(ev)
	$("#modalConfirmaAsist").modal("show")
}

function rechazar(ev, prs, nombre) {
	$("#spanAsistR").html(nombre);
	$("#id_asistenteRechazo").val(prs)
	$("#id_interesRechazo").val(ev)
	$("#modalRechazarAsist").modal("show")
}

$("#confirmar_asistencia").on("submit", function (e) {
	e.preventDefault();
	fData = new FormData(this);
	fData.append('action', 'confirmar_asistencia');
	$.ajax({
		url: "../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData: false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				json = JSON.parse(data);
				titulo = '';
				mensaje = '';
				tipoAlert = '';
				if (json.estatus == 'ok') {
					titulo = 'Confirmado';
					tipoAlert = 'success'
				} else {
					titulo = 'Ocurrió un error'
					tipoAlert = 'info'
				}

				swal({
					title: titulo,
					text: mensaje,
					icon: tipoAlert,
				});

				if (json.error == 'no_session') {
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function () {
						window.location.replace("index.php");
					}, 2000);
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
			actualizar_lista_prospectos('evento', $("#id_interes").val());
		},
		error: function () {
		},
		complete: function () {
			$("#modalConfirmaAsist").modal('hide');
			$(".outerDiv_S").css("display", "none")
		}
	});
})

$("#rechazar_asistencia").on("submit", function (e) {
	e.preventDefault();
	fData = new FormData(this);
	fData.append('action', 'rechazar_asistencia');
	$.ajax({
		url: "../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData: false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				json = JSON.parse(data);
				titulo = '';
				mensaje = '';
				tipoAlert = '';
				if (json.estatus == 'ok') {
					titulo = 'Asistencia a evento cancelada';
					tipoAlert = 'success'
				} else {
					titulo = 'Ocurrió un error'
					tipoAlert = 'info'
				}

				swal({
					title: titulo,
					text: mensaje,
					icon: tipoAlert,
				});

				if (json.error == 'no_session') {
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function () {
						window.location.replace("index.php");
					}, 2000);
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
			actualizar_lista_prospectos('evento', $("#id_interesRechazo").val());
		},
		error: function () {
		},
		complete: function () {
			$("#modalRechazarAsist").modal('hide');
			$(".outerDiv_S").css("display", "none")
		}
	});
})

function actualizar_lista_prospectos(tipo, idInteres) {
	fData = {
		action: "actualizar_lista_prospectos",
		tipo: tipo,
		idInteres: idInteres
	}
	$.ajax({
		url: "../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: fData,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				json = JSON.parse(data);
				if (json.estatus == 'ok') {
					if (tipo == 'evento') {
						ix_repl = list_eventos.findIndex(elm => elm.idEvento == idInteres)
						list_eventos[ix_repl] = json.data;
						detalle(idInteres);
					}
				} else {
					window.location.refresh();
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
}

function seguimiento_e(id_reg, numbre = null, idProsp) {
	if(numbre != null){
		$("#lbl_persona_seguimiento").html(numbre[0]);
		$("#lbl_interes_seguimiento").html(numbre[1]);
	}
	$("#container_form_comment").fadeOut();
	fData = {
		action: "historial_seguimientos",
		prospecto: id_reg
	}
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				seguimientos_h = JSON.parse(data);
				deta_u_coment = '<i>no hay comentarios</i>';
				fe_u_coment = '--';
				$("#tipo_nuevo_interes").val('');
				$("#id_nuevo_interes").parent().addClass('d-none');
				$("#btn_add_atencion").attr('disabled', true);
				if (seguimientos_h.estatus == 'ok') {
					editar_prospecto(idProsp);
					seguimientos_h = seguimientos_h.data;
					// información del seguimiento
					if(seguimientos_h.seguim_estatus){
						$("#change_ejecutiva").val(seguimientos_h.seguim_estatus.idMk_persona)
						$("#select_estat_seguimiento").val(seguimientos_h.seguim_estatus.etapa);
						$("#current_prosp_stat").val(seguimientos_h.seguim_estatus.etapa);
						$("#btn_change_estatus_prosp").attr('disabled', true);
						if(seguimientos_h.seguim_estatus.etapa == 3){
							$("#select_estat_seguimiento").attr('disabled', true);
						}else{
							$("#select_estat_seguimiento").attr('disabled', false);
						}
					}

					// desglosar historial comentarios
					if (seguimientos_h.hasOwnProperty('comentarios') && seguimientos_h.comentarios.length > 0) {
						deta_u_coment = seguimientos_h.comentarios[0].fecha;
						fe_u_coment = seguimientos_h.comentarios[0].detalles;
						if (fe_u_coment.includes('|')) {
							fe_u_coment = fe_u_coment.split('|')[0];
							fe_u_coment += `	<i class="fa fa-phone-square"></i>`;
						}

						$("#tabla_seguimientos").DataTable().clear();
						for (i = 0; i < seguimientos_h.comentarios.length; i++) {
							d_coment = seguimientos_h.comentarios[i].detalles;
							if (d_coment.includes('|')) {
								d_coment = d_coment.split('|')[0];
								d_coment += `	<i class="fa fa-phone-square"></i>`;
							}
							if(seguimientos_h.comentarios[i].ejecutiva_registro != ''){
								d_coment += `<span class="inbox-item-time float-right" style="font-family: monospace;"><i>-${seguimientos_h.comentarios[i].ejecutiva_registro}</i></span>`
							}
							$("#tabla_seguimientos").DataTable().row.add([
								seguimientos_h.comentarios[i].fecha,
								d_coment
							]);
						}
						$("#tabla_seguimientos").DataTable().columns.adjust().draw();
					} else {
						$("#tabla_seguimientos").DataTable().clear();
						$("#tabla_seguimientos").DataTable().draw();
					}
					// desglosar historial llamadas

					if (seguimientos_h.hasOwnProperty('llamadas') && seguimientos_h.llamadas.length > 0) {

						$("#tabla_seguimientos_llamadas").DataTable().clear();
						for (i = 0; i < seguimientos_h.llamadas.length; i++) {
							if (seguimientos_h.llamadas[i].estatus == '1') {
								estatus_llamada = `<div><select class="form-control" onchange="select_status_llamada(this)" llamada="${seguimientos_h.llamadas[i].idLlamada}" atencion="${seguimientos_h.llamadas[i].idAtencion}">
                                    <option value="1" selected="">Pendiente</option>
                                    <option value="2">Realizada</option>
                                    <option value="3">Rechazada</option>
                                    <option value="4">Postergada</option>
                                  </select></div>`;
							} else {
								estatus_llamada = estatus_llamadas[parseInt(seguimientos_h.llamadas[i].estatus) - 1];
							}
							comentario_ll = seguimientos_h.llamadas[i].detalles != null ? seguimientos_h.llamadas[i].detalles.substr(0, seguimientos_h.llamadas[i].detalles.indexOf('|')) : '-'
							$("#tabla_seguimientos_llamadas").DataTable().row.add([
								seguimientos_h.llamadas[i].fecha_llamar,
								comentario_ll,
								estatus_llamada
							]);
						}
						$("#tabla_seguimientos_llamadas").DataTable().columns.adjust().draw();
					} else {
						$("#tabla_seguimientos_llamadas").DataTable().clear();
						$("#tabla_seguimientos_llamadas").DataTable().draw();
					}
				}

				$("#detalle_fecha_comment").html(deta_u_coment);
				$("#detalle_ult_comment").html(fe_u_coment);
				$("#id_atencion").val(id_reg);
				$("#prospecto_llamar").val(id_reg);
				$("#inp_prospect").val(id_reg);
			} catch (e) {
				console.log(e);
				console.log(data);
			}
			$("#modal_seguimiento").modal('show');
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
}
let atencion_llam = null;
function select_status_llamada(element) {
	opcion_sel = $(element).find("option:selected").text()
	llamada_id = $(element).attr('llamada');
	atencion_id = $(element).attr('atencion');
	if (atencion_llam == null) {
		atencion_llam = llamada_id;
	} else if (llamada_id != atencion_llam) {
		$("#fecha_llamada").parent().remove()
		$("#comment_llamada").parent().remove()
		atencion_llam = llamada_id;
	}

	atencion_llam = llamada_id;

	if (opcion_sel == 'Realizada') {
		if ($("#fecha_llamada").length > 0) {
			$("#fecha_llamada").parent().remove()
		}
		input_coment = `<div class="form-group dymanic_form">
			<label class="mb-0 mt-2">Agregue un comentario (opcional).</label>
			<input type="text" class="form-control" id="comment_llamada">
			<button class="btn btn-primary btn-sm" onclick="guardar_comentario_llamada(${llamada_id},${atencion_id})">Guardar</button>
		</div>`
		$(element).parent().append(input_coment)
	} else if (opcion_sel == 'Postergada') {
		if ($("#comment_llamada").length > 0) {
			$("#comment_llamada").parent().remove()
		}

		input_postergar = `<div class="form-group dymanic_form">
			<label class="mb-0 mt-2">Fecha para devolver llamada.</label>
			<input type="date" class="form-control" id="fecha_llamada">

			<input type="time" class="form-control" id="hora_llamada">
			<button class="btn btn-primary btn-sm" onclick="reagendar_llamada(${llamada_id},${atencion_id})">Guardar</button>
		</div>`
		$(element).parent().append(input_postergar)
	} else if (opcion_sel == 'Pendiente') {
		$(".dymanic_form").remove();
	} else {
		if ($("#comment_llamada").length > 0) {
			$("#comment_llamada").parent().remove()
		}
		if ($("#fecha_llamada").length > 0) {
			$("#fecha_llamada").parent().remove()
		}

		swal({
			icon: 'info',
			text: 'Desea marcar la llamada como rechazada?',
			buttons: ['No', 'Si']
		}).then((willDelete) => {
			if (willDelete) {
				fdata = { action: 'actualizar_estatus_llamada', idLlamada: llamada_id, estatus_cambio: 3 };
				// if()
				$.ajax({
					url: '../assets/data/Controller/prospectos/prospectoControl.php',
					type: "POST",
					data: fdata,
					beforeSend: function () {
						$("#loader").css("display", "block")
					},
					success: function (data) {
						try {
							resp = JSON.parse(data);
							seguimiento_e($("#id_atencion").val(), null , $("#inp_prospect_edit_ej").val());
							consultar_lista_llamadas();
						} catch (e) {
							console.log(e);
							console.log(data);
						}
					},
					error: function () {
					},
					complete: function () {
						$("#loader").css("display", "none")
					}
				});
			} else {
				$(element).val('1')
			}
		})
	}
}

function guardar_comentario_llamada(idllamada, idatencion) {
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: { action: 'agregar_comentario_llamada', idLlamada: idllamada, idAtencion: idatencion, comentario: $("#comment_llamada").val() },
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				result = JSON.parse(data);
				console.log(result)
				if (result.error == 'no_session') {
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function () {
						window.location.replace("index.php");
					}, 2000);
				}
				seguimiento_e($("#id_atencion").val(), null , $("#inp_prospect_edit_ej").val());
				$("#form-comentario")[0].reset()
				$("#modal_comentario").modal("hide")
				consultar_lista_llamadas()
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
}

function reagendar_llamada(idllamada, idatencion) {
	fecha_ll = $("#fecha_llamada").val().trim()
	hora_ll = $("#hora_llamada").val().trim()
	if (fecha_ll != '' && hora_ll != '') {
		$.ajax({
			url: "../assets/data/Controller/prospectos/prospectoControl.php",
			type: "POST",
			data: { action: 'actualizar_estatus_llamada', estatus_cambio: 4, fecha_llamada: fecha_ll + ' ' + hora_ll, atencion: idatencion, idLlamada: idllamada },
			beforeSend: function () {
				$(".outerDiv_S").css("display", "block")
			},
			success: function (data) {
				try {
					result = JSON.parse(data);
					if (result.estatus == 'ok') {
						swal({
							title: 'Llamada agendada.',
							text: '',
							icon: 'success'
						})
					} else {
						console.log(result)
						swal({
							title: 'Oops',
							text: 'ocurrió un error interno',
							icon: 'info'
						})
					}
					if (result.error == 'no_session') {
						swal({
							title: "Vuelve a iniciar sesión!",
							text: "La informacion no se actualizó",
							icon: "info",
						});
						setTimeout(function () {
							window.location.replace("index.php");
						}, 2000);
					}
					seguimiento_e($("#id_atencion").val(), null , $("#inp_prospect_edit_ej").val());
					consultar_lista_llamadas()
				} catch (e) {
					console.log(e);
					console.log(data);
				}
			},
			error: function () {
			},
			complete: function () {
				$(".outerDiv_S").css("display", "none")
			}
		});
	} else {
		swal({
			text: 'Defina fecha y hora para reasignar la llamada'
		})
	}
}

$("#btn_agregar_comentario").on("click", function () {
	// $("#modal_comentario").modal("show");
	$("#container_form_comment").fadeIn();
});

$("#form-comentario").on("submit", function (e) {
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'agregar_comentario')
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData: false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				result = JSON.parse(data);
				if (result.estatus == 'ok') {
					swal({
						title: 'Agregado',
						text: '',
						icon: 'success'
					})
				} else {
					swal({
						title: 'Oops',
						text: 'ocurrió un error interno',
						icon: 'info'
					})
				}
				if (result.error == 'no_session') {
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function () {
						window.location.replace("index.php");
					}, 2000);
				}
				$("#container_form_comment").fadeOut();
				seguimiento_e($("#id_atencion").val(), null, $("#inp_prospect_edit_ej").val());
				$("#form-comentario")[0].reset()
				$("#modal_comentario").modal("hide")
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
})

$("#form_agendar_llamada").on("submit", function (e) {
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'agendar_llamada')
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData: false,
		beforeSend: function () {
			$(".outerDiv_S").css("display", "block")
		},
		success: function (data) {
			try {
				result = JSON.parse(data);
				if (result.estatus == 'ok') {
					swal({
						title: 'Agregado',
						text: '',
						icon: 'success'
					})
				} else {
					console.log(result)
					swal({
						title: 'Oops',
						text: 'ocurrió un error interno',
						icon: 'info'
					})
				}
				if (result.error == 'no_session') {
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function () {
						window.location.replace("index.php");
					}, 2000);
				}
				seguimiento_e($("#id_atencion").val(), null, $("#inp_prospect_edit_ej").val());
				$("#form_agendar_llamada")[0].reset()
				consultar_lista_llamadas();
				// $("#modal_comentario").modal("hide")
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
			$(".outerDiv_S").css("display", "none")
		}
	});
})

function calcular_parametros() {
	/* var fecha_p = $("#inp_fecha_pago").val();
	$("#notifica_fechap").html('')
	if (fecha_p != '' && global_concepto != null) {
		$("#notifica_parcialidades").html('');
		var precio_pago = parseFloat(global_concepto.precio);

		var bandera_retardo = false;

		var fecha_limite_pago
		if(global_concepto.fechalimitepago !== null){
			fecha_limite_pago = new Date(global_concepto.fechalimitepago.substr(0, 10) + " 00:00:00");
		}else{
			fecha_limite_pago = new Date();
			fecha_limite_pago.setDate(fecha_limite_pago.getDate() + 1);
			fecha_limite_pago = new Date(fecha_limite_pago);
		}

		var fecha_aplicado_pago = new Date($("#inp_fecha_pago").val() + " 00:00:00");

		if (fecha_aplicado_pago > fecha_limite_pago && global_concepto.categoria != 'Inscripción') {
			bandera_retardo = true;
			// $("#inp_promos_disp").val('');
			// $("#inp_promos_disp").attr('disabled', true);
		} else {
			// global_concepto.promociones = [];
		}

		if (parseInt(global_concepto.parcialidades) == 1) {
			if (!bandera_retardo && global_concepto.aplicados.length == 0) {
				if (Boolean($("#inp_promos_disp").val())) {
					var promocion_sel = global_concepto.promociones.find(elm => elm.idPromocion == $("#inp_promos_disp").val());
					precio_pago = precio_pago - (parseFloat(promocion_sel.porcentaje) / 100) * precio_pago;
				}
			} else {
				$("#inp_promos_disp").val('');
				$("#inp_promos_disp").attr('disabled', true);
				var suma_pagos_verificados = global_concepto.aplicados.reduce(function (a, b) { return a += (b.estatus == 'verificado') ? parseFloat(b.montopagado) : 0; }, 0);
				precio_pago = parseFloat(global_concepto.aplicados[0].costototal) - suma_pagos_verificados;
			}


			if (!bandera_retardo) {
				$("#inp_promos_disp").attr('disabled', false);
				$("#notifica_parcialidades").html(`
				<span class="text-success">Este pago se puede realizar en parcialidades. El moto total por cubir es de ${moneyFormat.format(precio_pago)}</span><br>
				`)
				$(".moneyFt").maskMoney();
				
			} else {
				
				
			}
		}else{
			
			
			$("#inp_promos_disp").attr('disabled', false);
			if (Boolean($("#inp_promos_disp").val())) {
				var promocion_sel = global_concepto.promociones.find(elm => elm.idPromocion == $("#inp_promos_disp").val());
				precio_pago = precio_pago - (parseFloat(promocion_sel.porcentaje) / 100) * precio_pago;
			}
			if(!bandera_retardo){
			}else{
				$("#notifica_fechap").html(`<div class="alert alert-danger"> Se está recibiendo un pago posterior a la fecha limite de pago, es posible que se apliquen recargos</div>`)
				var recargo_monto_pago = precio_pago * (porcent_recargo / 100);
				precio_pago = precio_pago + (precio_pago * (porcent_recargo / 100));
				$('#monto_recargo_ficha').val(recargo_monto_pago);
			}
		}
		if(global_concepto.aplicados.find(elm => elm.estatus == 'pendiente')){
			$("#notifica_parcialidades").html(`<div class="alert alert-danger">Este concepto de pago aún tiene pagos pendientes por verificar. Corrobore los pagos antes de registrar uno nuevo.</div>`);
		}
		$("#inp_monto_pago").val(moneyFormat.format(precio_pago));
		$("#inp_monto_pago").on('change', function () {

		})
	} */
}
$("#inp_fecha_pago").on('change', function () {
	// calcular_parametros();
	consultar_pago_aplicar()
})
function consultar_pago_aplicar(){
	fData = {
		action: 'obtener_info_pago_aplicar',
		alumno: $("#person_pago").val(),
		concepto: $("#tipo_pago").val(),
		fecha_pago: $("#inp_fecha_pago").val(),
	}
	$.ajax({
		url: "../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: fData,
		success: function (data) {
			try {
				var concepto = JSON.parse(data);
				// $("#inp_monto_pago").val(moneyFormat.format(parseFloat(concepto.monto_por_pagar) + parseFloat(concepto.monto_retardo)));
				if($("#tipo_pago option:selected").attr('category') == 'Inscripción'){
					$("#inp_button_spei").html(`<button class="btn btn-success" onclick="generar_spei(${$("#tipo_pago").val()})" type="button">Generar SPEI por ${moneyFormat.format(parseFloat(concepto.monto_por_pagar))}</button>`);
				}
				$("#inp_monto_pago_spect").val(moneyFormat.format(parseFloat(concepto.monto_por_pagar) + parseFloat(concepto.monto_retardo)));
				if(concepto.pago_pendiente.length > 0){
					// $("#notifica_parcialidades").html(`<div class="alert alert-danger">Este concepto de pago aún tiene pagos pendientes por verificar. Corrobore los pagos antes de registrar uno nuevo.</div>`);
					// $("#inp_monto_pago").maskMoney('destroy');
					// $("#inp_monto_pago").attr('readonly',true)
					// $("#form_registrar_pago button[type='submit']").attr('disabled', true);
					// $("#generar_ficha_pago_oxxo").attr('disabled', true);
					
				}else{
					$("#inp_monto_pago").maskMoney();
					$("#inp_monto_pago").attr('readonly',false)
					$("#form_registrar_pago button[type='submit']").attr('disabled', false);
					$("#generar_ficha_pago_oxxo").attr('disabled', false);
					
					
					$("#notifica_parcialidades").html(``);
				}
				if(concepto.monto_retardo > 0){
					$("#notifica_fechap").html(`<div class="alert alert-danger"> El alumno presenta un recargo sobre su mensualidad</div>`)
				}else{
					$("#notifica_fechap").html('')
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		}
	})
}
function pago_carrera(id_reg, evento, nombre, inst) {
	$("#container_ficha_spei").html('')
	$("#inp_button_spei").html('');
	$('#mostrar_ficha').hide();
	$('#btnSave2').hide();
	$("#lbl_persona_pago").html(`Registrar pago para: <br> <b id="nombre_prospecto">${nombre}</b>`)
	$("#tipo_pago").html('')
	$("#notifica_fechap").html('')
	$("#notifica_parcialidades").html('')
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
			proced: 'modulo',
			instit:inst
		},
		success: function (data) {
			try {
				var resp = JSON.parse(data);
				if(resp.estatus == 'error'){
					swal(resp.info);
					return;
				}
				$("#form_registrar_pago")[0].reset()
				$("#check_solo_inscripciones").prop('checked', false)
				var opc = `<option value="" selected disabled>Seleccione</option>`
				var opc_generaciones = `<option disabled value="" selected>Seleccione</option>`
				$("#inp_promos_disp").html(`<option value="" selected>Seleccione</option>`)
				$("#tab_registrar_pag").click();
				$("#tabla_pagos_notificados").DataTable().clear();
				$("#tab_generacion_pag").css("display", "none");
				$("#tab_ver_plan_pagos").css('display','none');
				(resp.data.tipoPago==1)?tipomoneda='MXN':tipomoneda='USD';
				
				$("#tipomonedausdmontomkt").html('('+tipomoneda+')')

				$("#tab_generacion_pag").removeClass('disabled')

				if (resp.data) {
					promos_conceptos = resp.data.generaciones;
					conceptos_pagar = resp.data.pagos_aplicar;
					for (var g in resp.data.generaciones) {
						if (resp.data.generaciones[g].asignacion.length > 0) {
							$("#tab_generacion_pag").addClass('disabled')
							$("#tab_ver_plan_pagos").css('display','block')
							$("#tab_ver_plan_pagos").on('click', function(){
								$("#modal_registrar_pago").modal('hide');
								ver_mensualidades("#modal_registrar_pago", id_reg, evento, 0)
								setTimeout(()=>{
									$("#tab_ver_plan_pagos").attr("onclick", "").unbind("click");
									console.log('unbind');
								}, 100)
							})
						}
					}
					for (var i in resp.data.pagos_aplicar) {
						aplicados_aprobados = resp.data.pagos_aplicar[i].aplicados.reduce((acc, it) => {
							if(it.numero_de_pago > acc){
								acc = it.numero_de_pago;
							}
							return acc;
						}, 0);

						var suma_aplicados = 0;
						var final_a_pagar = 0;
						if (resp.data.pagos_aplicar[i].aplicados.length > 0) {
							suma_aplicados = resp.data.pagos_aplicar[i].aplicados.reduce(function (a, b) { return a += (b.estatus == 'verificado' ? parseFloat(b.montopagado) : 0); }, 0);
							final_a_pagar = parseFloat(resp.data.pagos_aplicar[i].aplicados[0].costototal)
						}else{
							final_a_pagar = parseFloat(resp.data.pagos_aplicar[i].precio)
						}
						maxValue = (resp.data.pagos_aplicar[i].aplicados.length > 0) ? Math.max(...resp.data.pagos_aplicar[i].aplicados.map(x=>parseInt(x.numero_de_pago))) : 0;
						var aplicar_mas = ((resp.data.pagos_aplicar[i].parcialidades == 1 && suma_aplicados < final_a_pagar - .5) || (resp.data.pagos_aplicar[i].parcialidades == 2 && (maxValue < resp.data.pagos_aplicar[i].numero_pagos || suma_aplicados < final_a_pagar - .5)));
						if ( aplicar_mas ) {
							var dateNew = solicitar_prorrogaOutClick(undefined, id_reg,resp.data.pagos_aplicar[i].id_concepto,"",resp.data.pagos_aplicar[i].aplicados.length +1, resp.data.pagos_aplicar[i].fechalimitepago);
							if(dateNew[0].response != '' && dateNew[0].status == 'aprobado'){
								dateNew = dateNew[0].response;
								console.log(dateNew);
							}else{
								dateNew = resp.data.pagos_aplicar[i].fechalimitepago;
							}
							var inicio_gen = '';
							if(resp.data.pagos_aplicar[i].categoria == 'Inscripción'){
								inicio_gen = resp.data.pagos_aplicar[i].info_gen.fecha_inicio.split('-');
								inicio_gen = `. Inicia: ${inicio_gen[2].split(' ')[0]} de ${meses[parseInt(inicio_gen[1]-1)]}`;
							}
							opc += `<option value="${resp.data.pagos_aplicar[i].id_concepto}" category="${resp.data.pagos_aplicar[i].categoria}" data-precio="${resp.data.pagos_aplicar[i].precio}">${resp.data.pagos_aplicar[i].concepto + inicio_gen} ${(resp.data.pagos_aplicar[i].fechalimitepago !== null) ? '(' + dateNew.substr(0, 10) + ')' : ''}</option>`
						}
						suma_aplicados_gen = false;
						monto_por_cubrir = false;
						var promesadepago = false;
						promesadepago = resp.data.pagos_aplicar[i].aplicados.find(elm => elm.promesa_de_pago !== null);
						promesadepago = Boolean(promesadepago)
						
						for (var j in resp.data.pagos_aplicar[i].aplicados) {
							aplicado_p = resp.data.pagos_aplicar[i].aplicados[j];
							aplicado_p.detalle_pago = JSON.parse(aplicado_p.detalle_pago)
							
							string_concepto = aplicado_p.concepto_nom;
							if(resp.data.pagos_aplicar[i].categoria == 'Mensualidad' && aplicado_p.numero_de_pago > 0){
								string_concepto = aplicado_p.concepto_nom.slice(0,11)+` [N° ${aplicado_p.numero_de_pago}]`+aplicado_p.concepto_nom.slice(11)
							}
							
							if(aplicado_p.categoria == 'Inscripción' && !monto_por_cubrir){
								monto_por_cubrir = parseFloat(aplicado_p.costototal)
							}
							if(aplicado_p.categoria == 'Inscripción' && aplicado_p.promesa_de_pago != null){
								promesadepago = true;
							}
							suma_aplicados_gen += (aplicado_p.estatus == 'verificado' && aplicado_p.categoria == 'Inscripción') ? parseFloat(aplicado_p.montopagado) : 0;
							var detalle_p = '';
							if(aplicado_p.referencia != null && aplicado_p.referencia != ''){
								detalle_p += '<b>Referencia:</b> ' + aplicado_p.referencia + '<br>';
							}
							if(aplicado_p.codigo_de_autorizacion != null && aplicado_p.codigo_de_autorizacion != ''){
								detalle_p += '<b>Autorización:</b> ' + aplicado_p.codigo_de_autorizacion + '<br>';
							}
							var promesa = '';
							if(!promesadepago && aplicado_p.promesa_de_pago == null && aplicado_p.categoria == 'Inscripción' && aplicado_p.estatus == 'verificado' && parseFloat(aplicado_p.restante) > 0){
								promesa = `<button class="btn btn-sm btn-secondary text-nowrap" onclick="aplicar_promesa(${aplicado_p.id_pago}, '${aplicado_p.concepto_nom}', '${nombre}')">Promesa pago</button>`;
							}else if(aplicado_p.promesa_de_pago != null){
								promesa = `(Promesa de pago)`;
							}
							var string_comentario = '';
							
							if(aplicado_p.comentario_callcenter && aplicado_p.comentario_callcenter!= ''){
								string_comentario += `[<b style="color:#e91e63;">Marketing -</b> ${aplicado_p.comentario_callcenter}]<br>`;
							}
							if(aplicado_p.comentario && aplicado_p.comentario!= ''){
								string_comentario += `[<b style="color:#e91e63;">Cobranza:</b> ${aplicado_p.comentario}]`;
							}
							$("#tabla_pagos_notificados").DataTable().row.add([
								`<span title="${aplicado_p.fechapago}">${aplicado_p.fechapago.substr(0, 10)}</span>`,
								string_concepto,
								`<span class="text-${color_estatus[aplicado_p.estatus]}">${moneyFormat.format((parseFloat(aplicado_p.montopagado) + parseFloat(aplicado_p.cargo_retardo)))}</span>`,
								aplicado_p.detalle_pago.id+' '+((aplicado_p.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_p.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								detalle_p,
								`<span class="text-${color_estatus[aplicado_p.estatus]}">${aplicado_p.estatus.toUpperCase()} ${promesa}</span>`,
								`<span style="font-family: monospace;font-style: oblique;white-space: normal;color:#0000a1;">${string_comentario}</span>`
							])
						}
						
						for (var r in resp.data.pagos_aplicar[i].rechazados) {
							aplicado_r = resp.data.pagos_aplicar[i].rechazados[r];
							aplicado_r.detalle_pago = JSON.parse(aplicado_r.detalle_pago)
							
							string_concepto = aplicado_r.concepto_nom;
							if(resp.data.pagos_aplicar[i].categoria == 'Mensualidad' && aplicado_r.numero_de_pago > 0){
								string_concepto = aplicado_r.concepto_nom.slice(0,11)+` [N° ${aplicado_r.numero_de_pago}]`+aplicado_r.concepto_nom.slice(11)
							}

							var string_comentario = '';
							
							if(aplicado_r.comentario_callcenter && aplicado_r.comentario_callcenter!= ''){
								string_comentario += `<b style="color:#e91e63;">Marketing -</b> ${aplicado_r.comentario_callcenter}<br>`;
							}
							if(aplicado_r.comentario && aplicado_r.comentario!= ''){
								string_comentario += `<b style="color:#e91e63;">Cobranza:</b> ${aplicado_r.comentario}`;
							}

							$("#tabla_pagos_notificados").DataTable().row.add([
								`<span title="${aplicado_r.fechapago}">${aplicado_r.fechapago.substr(0, 10)}</span>`,
								`<span class="">${string_concepto}</span>`,
								`<span class="text-${color_estatus[aplicado_r.estatus]}">${moneyFormat.format((parseFloat(aplicado_r.montopagado) + parseFloat(aplicado_r.cargo_retardo)))}</span>`,
								aplicado_r.detalle_pago.id+' '+((aplicado_r.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_r.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								'',
								`<span class="text-${color_estatus[aplicado_r.estatus]}">${aplicado_r.estatus.toUpperCase()}</span>`,
								`<span style="font-family: monospace;font-style: oblique;white-space: normal;color:#0000a1;">${string_comentario}</span>`
							])
						}
						if (((suma_aplicados_gen !== false && monto_por_cubrir !== false) && (suma_aplicados_gen.toFixed(2) >= monto_por_cubrir.toFixed(2))) || promesadepago) { 
							$("#alumno_generacion").val(id_reg)
							$("#tab_generacion_pag").css("display", "block");
						}
					}

					for (var i in resp.data.generaciones) {
						opc_generaciones += `<option value="${resp.data.generaciones[i].idGeneracion}">${resp.data.generaciones[i].nombre}</option>`
					}
				}
				$("#tabla_pagos_notificados").DataTable().draw();
				$("#tipo_pago").html(opc)
				$("#select_alumno_gen").html(opc_generaciones)
				$("#modal_registrar_pago").modal('show')
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		}
	})

	$('#person_pago').val(id_reg)
	$('#evento_pago').val(evento)
	// $("#modal_registrar_pago").modal('show')
}

function pago_evento(id_reg, evento, nombre) {
	$('#btnSave2').hide();
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
		success: function (data) {
			try {
				var resp = JSON.parse(data);
				$("#form_registrar_pago")[0].reset()
				$("#check_solo_inscripciones").prop('checked', false)
				var opc = `<option value="" selected disabled>Seleccione</option>`

				$("#inp_promos_disp").html(`<option value="" selected>Seleccione</option>`)
				$("#tab_registrar_pag").click();
				$("#tabla_pagos_notificados").DataTable().clear();
				$("#tab_generacion_pag").css("display", "none");

				if (resp.data) {
					promos_conceptos = resp.data.generaciones;
					conceptos_pagar = resp.data.pagos_aplicar;

					for (var i in resp.data.pagos_aplicar) {
						var suma_aplicados = 0;
						var final_a_pagar = 0;
						if (resp.data.pagos_aplicar[i].aplicados.length > 0) {
							suma_aplicados = resp.data.pagos_aplicar[i].aplicados.reduce(function (a, b) { return a += (b.estatus == 'verificado' ? parseFloat(b.montopagado) : 0); }, 0);
							final_a_pagar = parseFloat(resp.data.pagos_aplicar[i].aplicados[0].costototal)
						}
						var aplicar_mas = (suma_aplicados < final_a_pagar);
						if ((parseInt(resp.data.pagos_aplicar[i].aplicados.length) < parseInt(resp.data.pagos_aplicar[i].numero_pagos)) || aplicar_mas) {
							opc += `<option value="${resp.data.pagos_aplicar[i].id_concepto}" category="${resp.data.pagos_aplicar[i].categoria}" data-precio="${resp.data.pagos_aplicar[i].precio}">${resp.data.pagos_aplicar[i].concepto} (${(resp.data.pagos_aplicar[i].fechalimitepago !== null) ? '(' + resp.data.pagos_aplicar[i].fechalimitepago.substr(0, 10) + ')' : ''})</option>`
						}
						for (var j in resp.data.pagos_aplicar[i].aplicados) {
							aplicado_p = resp.data.pagos_aplicar[i].aplicados[j];
							aplicado_p.detalle_pago = JSON.parse(aplicado_p.detalle_pago)
							
							$("#tabla_pagos_notificados").DataTable().row.add([
								aplicado_p.fechapago,
								aplicado_p.concepto_nom,
								moneyFormat.format(aplicado_p.montopagado),
								aplicado_p.detalle_pago.id+' '+((aplicado_p.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_p.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								'',
								`<span class="text-${color_estatus[aplicado_p.estatus]}">${aplicado_p.estatus.toUpperCase()}</span>`,
								''
							])
						}
						for (var r in resp.data.pagos_aplicar[i].rechazados) {
							aplicado_r = resp.data.pagos_aplicar[i].rechazados[r];
							aplicado_r.detalle_pago = JSON.parse(aplicado_r.detalle_pago)
							
							string_concepto = aplicado_r.concepto_nom;
							if(resp.data.pagos_aplicar[i].categoria == 'Mensualidad' && aplicado_r.numero_de_pago > 0){
								string_concepto = aplicado_r.concepto_nom.slice(0,11)+` [N° ${aplicado_r.numero_de_pago}]`+aplicado_r.concepto_nom.slice(11)
							}

							var string_comentario = '';
							
							if(aplicado_r.comentario_callcenter && aplicado_r.comentario_callcenter!= ''){
								string_comentario += `<b style="color:#e91e63;">Marketing -</b> ${aplicado_r.comentario_callcenter}<br>`;
							}
							if(aplicado_r.comentario && aplicado_r.comentario!= ''){
								string_comentario += `<b style="color:#e91e63;">Cobranza:</b> ${aplicado_r.comentario}`;
							}

							$("#tabla_pagos_notificados").DataTable().row.add([
								`<span title="${aplicado_r.fechapago}">${aplicado_r.fechapago.substr(0, 10)}</span>`,
								`<span class="">${string_concepto}</span>`,
								`<span class="text-${color_estatus[aplicado_r.estatus]}">${moneyFormat.format((parseFloat(aplicado_r.montopagado) + parseFloat(aplicado_r.cargo_retardo)))}</span>`,
								aplicado_r.detalle_pago.id+' '+((aplicado_r.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_r.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								'',
								`<span class="text-${color_estatus[aplicado_r.estatus]}">${aplicado_r.estatus.toUpperCase()}</span>`,
								`<span style="font-family: monospace;font-style: oblique;white-space: normal;color:#0000a1;">${string_comentario}</span>`
							])
						}
					}

				}
				$("#tabla_pagos_notificados").DataTable().draw();
				$("#tipo_pago").html(opc)

				$("#modal_registrar_pago").modal('show')
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		}
	})

	$('#person_pago').val(id_reg)
	$('#evento_pago').val(evento)
	// $("#modal_registrar_pago").modal('show')
}

$("#form_asignar_generacion").on('submit', function (e) {
	e.preventDefault();
	fdata = new FormData(this)
	fdata.append('action', 'asignar_generacion');
	$.ajax({
		url: '../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: fdata,
		contentType: false,
		processData: false,
		beforeSend: function () {
		},
		success: function (data) {
			try {
				resp = JSON.parse(data);
				if (resp.estatus == 'ok') {
					swal({
						title: "Registro exitoso",
						text: "Se ha asignado la generación al alumno",
						icon: "success"
					})
				} else {
					swal({
						title: "Error",
						text: resp.info,
						icon: "info"
					})
				}
				$("#form_asignar_generacion")[0].reset();
				$("#modal_registrar_pago").modal('hide')
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
		}
	});
})

$("#tipo_pago").change(function () {
	$("#container_ficha_spei").html('')
	if($("#tipo_pago option:selected").attr('category') == 'Inscripción'){
		consultar_ofertas($(this).val());
	}else{
		$("#inp_button_spei").html('');
	}
	consultar_pago_aplicar();
})

let ofertas = [];

function consultar_ofertas(concepto){
	$.ajax({
		url: '../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {
			action: 'consultar_ofertas',
			concepto: concepto,
			prospecto: $("#person_pago").val()
		},
		beforeSend: function () {
		},
		success: function (data) {
			try {
				ofertas = JSON.parse(data);
				var opc = '<option disabled selected>Seleccione una promoción</option>';
				if(ofertas.length > 0){
					for (var i in ofertas) {
						opc += `<option value="${ofertas[i].id_oferta}">${ofertas[i].nombre}</option>`
					}
				}
				$("#inp_promos_disp").html(opc);
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
		}
	});
}
$("#inp_promos_disp").on('change', function(){
	var promo = ofertas.find( elm => elm.id_oferta == $(this).val() );
	if(promo != undefined){
		info_promos = "";
		promo_info_i = {
			'action':'crearpromocion',
			'selecalumnogeneracion':1,
			'nombrepromocion':promo.nombre,
			'creador_por':(0 - usrInfo.idPersona),
			'listaralumnos':$("#person_pago").val(),
			'selecpromobeca':promo.tipo,
			'id_oferta':promo.id_oferta
		}
		$.each(promo.conceptos, (e)=>{
			if(e == 'promomensualidades'){
				if(promo.conceptos[e].numero_pagos !== false){
					promo_info_i[`multiple_mensualidades`] = promo.conceptos[e].numero_pagos;
				}else{
					promo_info_i[`promofechainicial`] = promo.conceptos[e].fechas[0];
					promo_info_i[`promofechafinal`] = promo.conceptos[e].fechas[1];
				}
			}else{
				if(promo.conceptos[e].fechas !== false){
					promo_info_i[`promofechainicial`] = promo.conceptos[e].fechas[0];
					promo_info_i[`promofechafinal`] = promo.conceptos[e].fechas[1];
				}
			}
			porcent_d = promo.conceptos[e].porcentaje;

			promo_info_i[`promoconcepto_${e}`] = promo.conceptos[e].monto;
			precio_final = parseFloat(promo.conceptos[e].precio_lista) - (parseFloat(promo.conceptos[e].precio_lista) * (parseFloat(porcent_d) / 100));
			info_promos += `${promo.conceptos[e].tipo_concepto}: ${moneyFormat.format(precio_final)} \n`
			promo_info_i[`${e}`] = promo.conceptos[e].porcentaje;
			promo_info_i[`idconcepto${e}`] = promo.conceptos[e].id_concepto;
		})
		swal({
			title: "Aplicar promoción?",
			text: "La promoción aplicará los siguientes descuentos: \n"+info_promos,
			buttons:['Cancelar', 'Aceptar']
		}).then((confirm)=>{
			if(confirm){
				$.ajax({
					url: '../assets/data/Controller/planpagos/promocionesControl.php',
					type: "POST",
					data: promo_info_i,
					success: function(data){
						try{
							var data = JSON.parse(data);
							if (data.data>0) {
								swal({
									title: 'Promoción creada con éxito',
									icon: 'success'
								}).then(()=>{

								})
							} else {
								swal({
									title: "Error",
									text: data.hasOwnProperty('mensaje')? data.mensaje : "No se pudo crear la promoción",
									icon: "info"
								});
							}
							consultar_ofertas($("#tipo_pago").val())
							consultar_pago_aplicar();
						}catch(e){
							console.log(e);
							console.log(data);
						}
					}
				})
			}else{
				$($("#inp_promos_disp option")[0]).prop('selected', true)
			}
		})
	}
})

// $("#inp_promos_disp").change(function () {

// 	calcular_parametros()
// })

/* $("#form_registrar_pago").on('submit', function (e) {
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'pago_prospecto')

	$.ajax({
		url: "../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData: false,
		beforeSend: function () {
			$("#form_registrar_pago").find('button[type=submit]').attr('disabled', true)
		},
		success: function (data) {
			try {
				resp = JSON.parse(data);
				if (resp.estatus == 'ok') {
					swal({ icon: 'success', title: 'Pago registrado' })
				} else {
					swal({ icon: 'info', title: 'Ha ocurrido un error', text: resp.info })
				}
				if (resp.error == 'no_session') {
					swal({
						title: "Vuelve a iniciar sesión!",
						text: "La informacion no se actualizó",
						icon: "info",
					});
					setTimeout(function () {
						window.location.replace("index.php");
					}, 2000);
				}

				$("#modal_registrar_pago").modal('hide')
				$("#home-tab").click();
				$("#carrer-tab").click();
				cargar_eventos();
				cargar_carreras();
				$("#form_registrar_pago")[0].reset()

			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
			$("#form_registrar_pago").find('button[type=submit]').attr('disabled', false)
		}
	});
}) */

$("#form_registrar_pago").on('submit', function (e) {
	e.preventDefault();
	if(parseFloat($("#inp_monto_pago").val().replace(/\$|,/g, "")) > 0){
		fData = new FormData(this);
		fData.append("action", 'pago_prospecto')
		fData.append("form_alumno", 'Callcenter')
	
		$.ajax({
			url: "../assets/data/Controller/planpagos/pagosControl.php",
			type: "POST",
			data: fData,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$("#form_registrar_pago").find('button[type=submit]').attr('disabled', true)
			},
			success: function (data) {
				try {
					resp = JSON.parse(data);
					if (resp.estatus == 'ok') {
						swal({ icon: 'success', title: 'Pago registrado' })
					} else {
						swal({ icon: 'info', title: 'Ha ocurrido un error', text: resp.info })
					}
					if (resp.error == 'no_session') {
						swal({
							title: "Vuelve a iniciar sesión!",
							text: "La informacion no se actualizó",
							icon: "info",
						});
						setTimeout(function () {
							window.location.replace("index.php");
						}, 2000);
					}
	
					$("#modal_registrar_pago").modal('hide')
					$("#home-tab").click();
					$("#carrer-tab").click();
					init_data();
					$("#form_registrar_pago")[0].reset()
	
				} catch (e) {
					console.log(e);
					console.log(data);
				}
			},
			error: function () {
			},
			complete: function () {
				$("#form_registrar_pago").find('button[type=submit]').attr('disabled', false)
			}
		});
	}else{
		swal({ icon: 'info', title: 'Monto incorrecto', text: 'El monto debe ser mayor a 0' })
	}
})

function conceptos_pago() {
	/* $.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: { action: 'conceptosPago' },
		beforeSend: function () {
		},
		success: function (data) {
			try {
				conceptos = JSON.parse(data);
				$("#tipo_pago").html(conceptos.reduce((acc, item) => { return acc += (item.precio != 'GRATIS') ? `<option value="${item.id_concepto}">${item.descripcion}</option>` : '' }, ''));
				$("#tipo_pago_adm").html(conceptos.reduce((acc, item) => { return acc += (item.precio != 'GRATIS') ? `<option value="${item.id_concepto}">${item.descripcion}</option>` : '' }, ''));
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
		}
	}); */
}

function consultar_lista_llamadas() {
	$.ajax({
		url: "../assets/data/Controller/marketing/marketingControl.php",
		type: "POST",
		data: { action: 'agenda_llamadas' },
		beforeSend: function () {
		},
		success: function (data) {
			try {
				llamadas = JSON.parse(data);
				if (llamadas.estatus == 'ok') {
					llamadas = llamadas.data;
					alert_danger = false;
					alert_warning = false;
					alert_success = false;
					fecha_compare = new Date();
					fecha_compare_dos = new Date();
					fecha_compare_dos = fecha_compare_dos.setDate(fecha_compare_dos.getDate() + 2);

					list_alerts = "";

					for (i = 0; i < llamadas.length; i++) {
						if (llamadas[i].estatus == 1) {
							fecha_llamada_r = new Date(llamadas[i].fecha_llamar);
							text_not = ""
							clas_not = "";


							if (fecha_llamada_r <= fecha_compare_dos) {
								text_not = "Llamada proxima para <br>" + llamadas[i].prospecto_llamar;
								clas_not = "warning";
								alert_warning = true;
								if (fecha_llamada_r <= fecha_compare) {
									text_not = "Llamada atrasada para <br>" + llamadas[i].prospecto_llamar;
									clas_not = "danger";
									alert_danger = true;
								}
							} else {
								alert_success = true;
								clas_not = "success";
								text_not = "Llamar a <br>" + llamadas[i].prospecto_llamar;
							}

							list_alerts += `<div class="dropdown-item notify-item mt-2">
	                            <div class="notify-icon bg-${clas_not}">&nbsp;</div>
	                            <p class="notify-details">${text_not}</p>
	                            <i style="float: right;font-size: small; color:gray;">${llamadas[i].fecha_llamar}</i>
	                        </div>`;
						}
					}

					if (alert_success) {
						$("#span_alert_llamadas").addClass('badge-success')
					} else {
						$("#span_alert_llamadas").removeClass('badge-success')
					}

					if (alert_warning) {
						$("#span_alert_llamadas").addClass('badge-warning')
					} else {
						$("#span_alert_llamadas").removeClass('badge-warning')
					}

					if (alert_danger) {
						$("#span_alert_llamadas").addClass('badge-danger')
					} else {
						$("#span_alert_llamadas").removeClass('badge-danger')
					}

					$("#list-notification-llamada").html(list_alerts)
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		},
		error: function () {
		},
		complete: function () {
		}
	});
}

function set_concepto(select) {
	// $("#inp_monto_pago").val(moneyFormat.format($(select).find(":selected").attr('data-precio')));
	$('#mostrar_ficha').hide();
	$('#btnSave2').hide();
	$('#monto_recargo_ficha').val('');
}

$("#check_solo_inscripciones").on('change', function () {
	var statusCh = $(this).prop('checked')
	$("#tipo_pago option").each(function () {
		if (!statusCh) {
			$(this).css('display', 'block')
		} else {
			if ($(this).attr('category') != 'Inscripción') {
				$(this).css('display', 'none')
			}
		}
	})
})

function consultar_porcentaje_recargo() {
	$.ajax({
		url: '../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: { action: 'consultar_porcentaje_recargo' },
		success: function (data) {
			porcent_recargo = parseFloat(data);
		}
	});
}

//on clic des´pegar ficha de pago en oxxo
$("#generar_ficha_pago_oxxo").on('click', function(){
	if ($('#tipo_pago').val() != null) {
		if(parseFloat($("#inp_monto_pago").val().replace(/\$|,/g, "")) > 10000){
			swal('El monto máximo para generar una ficha de oxxo es por $10,000 pesos');
			return;
		}
		$('#btnSave2').show();
		$('#mostrar_ficha').fadeIn( "slow", function() {
			// Animation complete
		  });
		fData = {
			action : "generar_ficha_oxxo",
			id_prospecto : $('#person_pago').val(),
			id_tipo_pago_concepto : $("#tipo_pago").val(),
			id_promocion : $("#inp_promos_disp").val(),
			nombre_concepto : $("#tipo_pago option:selected").text(),
			monto_pago : $("#inp_monto_pago").val(),
			nombre_prospecto : $("#nombre_prospecto").text(),
			monto_recargo_pagado: $('#monto_recargo_ficha').val()
		}
		$.ajax({
			url: "../assets/data/Controller/planpagos/pagosControl.php",
			type: "POST",
			data: fData,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				try{
					data = JSON.parse(data);
					console.log(data)
					$("#monto_pago").html(data.monto)
					$("#tipo_moneda").html(data.tipo_moneda)
					$("#referencia_pago").html(data.referencia)
					$("#concepto_de_pago").html(data.nombre_producto)
					$("#codigo_barras-reference").attr("src", '../assets/images/bar_codes_oxxo/'+data.url_codigo_barras);

					$("#monto_pago_ficha").val(data.monto)
					$("#tipo_moneda_ficha").val(data.tipo_moneda)
					$("#referencia_ficha").val(data.referencia)
					$("#nombre_concepto_ficha").val(data.nombre_producto)
					$("#bar_code_ficha").val(data.url_codigo_barras);
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
			title: 'Error',
			text: 'Seleccione un concepto de pago',
			icon: 'info',
		});

	}

})

function aplicar_promesa(id, concepto, persona){
	swal({
		title:'¿Aplicar promesa de pago?',
		text: `¿Aplicar promesa sobre el concepto ${concepto} a la persona ${persona}?`,
		buttons:['Cancelar','Aplicar']
	}).then((value) => {
		if(value){
			$.ajax({
				url: '../assets/data/Controller/planpagos/pagosControl.php',
				type: "POST",
				data: { action: 'aplicar_promesa', id_promesa: id },
				success: function(data){
					try{
						data = JSON.parse(data);
						if(data.estatus == 'ok'){
							swal({
								title: 'Aplicado',
								text: 'Promesa aplicada correctamente',
								icon: 'success',
							});
						}else{
							swal({
								title: 'Error',
								text: 'No se pudo aplicar la promesa',
								icon: 'error',
							});
						}
						$("#modal_registrar_pago").modal('hide');
					}catch(e){
						console.log(e);
						console.log(data);
					}
				}
			});
		}
	})
}

function solicitar_prorrogaOutClick(plan, id_prospecto,id_concepto,id_carrera,numero_de_pago, fechalimite_pago){

	var res= "1",
		date = "",
		st = "";
	$.ajax({
		url: "../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: {action:'validar_si_existe_prorroga', id_concepto:id_concepto, numero_de_pago:numero_de_pago, id_prospecto:id_prospecto},
		async: false,
		success: function(data){
			resp = JSON.parse(data);
				var d = new Date();
				var fecha_hoy = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
				if( new Date(fecha_hoy) >  new Date(fechalimite_pago) ){
					res= "tardy";
				}else{
					if(resp.data==false){
						res= "current"
					}
					else{
						res= "duplex";
						st = resp.data.estatus;
						date = resp.data.nuevafechalimitedepago;
					}
				}
		},
		error: function(){
		},
		complete: function(){
		
		}
	});
	return [{"status":st,"response":date}];
}

$("#editar_tipo_moneda").on('change', function(){
	if($("#editar_tipo_moneda").val() != $("#tmp_tipomoneda").val()){
		swal({
			icon:'info',
			title:'¿Cambiar tipo de moneda?',
			text:'Esto puede afectar al usuario si tiene un plan de pagos activo.',
			buttons:['Cancelar', 'Aceptar']
		}).then((val)=>{
			if(!val){
				$("#editar_tipo_moneda").val($("#tmp_tipomoneda").val());
			}
		})
	}
});

function editar_prospecto(prospecto){
	$.ajax({
		url: '../assets/data/Controller/prospectos/prospectoControl.php',
		type: "POST",
		data: {action: 'consultar_prospecto', prospecto: prospecto},
		success: function(data){
			try{
				var resp = JSON.parse(data);
				if(resp.estatus == 'ok'){
					resp = resp.data
					$("#inp_prospect_edit_ej").val(resp.idAsistente)
					$("#edit_pr_nombre_ej").val(resp.nombre)
					$("#edit_pr_apaterno_ej").val(resp.aPaterno)
					$("#edit_pr_amaterno_ej").val(resp.aMaterno)
					$("#edit_pr_telefono_ej").val(resp.telefono.replace(/[^0-9]+/g, ''))
					$("#edit_pr_correo_ej").val(resp.correo)
					$("#editar_tipo_moneda").val(resp.tipoPago)
					$("#tmp_tipomoneda").val(resp.tipoPago)

					$("#select_edit_nacionalidad").val(resp.nacionalidad > 0 ? resp.nacionalidad : ''); // <- nuevo
					$("#edit_genero").val(resp.Genero > 0 ? resp.Genero : 0);  // <- nuevo
					$("#inp_edit_titulo_estudio").val(resp.grado_academico);  // <- nuevo
					$("#edit_escuela_proc").val(resp.escuela_procedencia);  // <- nuevo
					$("#edit_fecha_egreso").val(resp.fecha_egreso);  // <- nuevo
					$("#inp_edit_cedula").val(resp.cedula);  // <- nuevo
					$("#edit_pr_telefono_c_ej").val(resp.telefono_casa);
					$("#edit_pr_telefono_rec_ej").val(resp.telefono_recados);

					toggle_options(true);
					if(resp.idAsociacion !== null && resp.idAsociacion !== "" && resp.idAsociacion != 0){
						$("#edit_pr_institucion_ej").val(resp.idAsociacion);
						if(resp.hasOwnProperty('acuerdo_asociacion') && resp.acuerdo_asociacion == 1){
							$("#only_view_inst").addClass('d-none');
							$("#edit_pr_institucion_ej").removeClass('d-none');
							toggle_options(false);
						}else{
							$("#only_view_inst").removeClass('d-none');
							$("#edit_pr_institucion_ej").addClass('d-none');
							$("#only_view_inst").val($("#edit_pr_institucion_ej option:selected").text());
						}
					}else{
						$("#only_view_inst").addClass('d-none');
						$("#edit_pr_institucion_ej").removeClass('d-none');
						$("#edit_pr_institucion_ej").val(0)
						toggle_options(false);
					}

					// $("#modalEditarProspecto").modal("show");
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	});
}
function toggle_options(option = true){
	$("#edit_pr_institucion_ej option").each(function(){
		if($(this).attr('acuerdo') === undefined){
			if(option){
				$(this).removeClass('d-none');
			}else{
				$(this).addClass('d-none');
			}
		}
	})
}

function cargar_instituciones(){
	$.ajax({
		url: "../assets/data/Controller/instituciones/institucionesControl.php",
		type: "POST",
		data: {action:'lista_todo_instituciones'},
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				instit = JSON.parse(data);
				html_opt = "<option value='0' selected>Si pertenece a una asociación, elijala</option>";
				html_ej = "<option value='0' acuerdo='true' selected>Si pertenece a una asociación, elijala</option>";
				if(instit.estatus == 'ok'){
					instit.data.sort((a , b)=>{
						return parseInt(a.acuerdo) + parseInt(b.acuerdo);
					});
					for (i = 0; i < instit.data.length; i++) {
						if(instit.data[i].fundacion == '1'){
							if(instit.data[i].acuerdo == '1'){
								html_opt+=`<option value="${instit.data[i].id_institucion}">${instit.data[i].nombre}</option>`
								html_ej+=`<option acuerdo="true" value="${instit.data[i].id_institucion}">${instit.data[i].nombre}</option>`
							}else{
								html_ej+=`<option value="${instit.data[i].id_institucion}">${instit.data[i].nombre}</option>`
							}
						}
					}
				}
				$("#IDOrganizacion").html(html_opt);
				$("#edit_pr_institucion").html(html_opt);
				$("#edit_pr_institucion_ej").html(html_ej);
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

$("#form-actualizar-prospecto").on('submit', function(e){
	e.preventDefault();
	if(!regCorreo.test(String($("#edit_pr_correo_ej").val().trim()).toLowerCase())){
		alert('el correo no tiene un formato correcto')
	}else{
		fData = new FormData(this);
		fData.append('action', 'actualizar_info_prospecto');
		
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
					actualiza = JSON.parse(data)
					if(actualiza.estatus == 'ok'){
						swal({
							icon:'success',
							title:'Actualización exitosa'
						})
						$("#inp_prospect_edit").val('')
						reload_tablas();
					}else{
						swal({
							icon:'info',
							text: actualiza?.mensaje || 'Ha ocurrido algo al actualizar, notifique al administrador.'
						})
					}
					init_data();
					
					$("#modalEditarProspecto").modal("hide");
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
});
$( "#metododepago1" ).change(function() {


	$('#pagoenefectivo').hide();
	$('#chechenominativo').hide();
	$('#tarjetadecredito').hide();
	$('#tarjetadedebito').hide();
	$('#transferenciaelectronica').hide();
	$('#paypal').hide();

	$('#noselect').prop( "selected", true )


	var metododepago = $( "#metododepago1" ).val();
	$('#mostrarmetododepago').show();
	switch (metododepago) {
		case '1':
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '2':
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '3':
			$('#pagoenefectivo').show();
			$('#tarjetadecredito').show();
			$('#tarjetadedebito').show();
			break;
		case '4':
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '5':
			$('#pagoenefectivo').show();
			$('#tarjetadecredito').show();
			$('#tarjetadedebito').show();
			break;
		case '6':
			$('#transferenciaelectronica').show();
			$('#metododepago').val('Transferencia eletrónica');
			break;
		case '7':
			$('#paypal').show();
			$('#metododepago').val('Paypal');
			break;

		default:
			break;
	}

  });

  
function getPaises(){
    $("#pais").empty();
    Data = {
        action: "buscarPaises"
    }
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#select_nacionalidad").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $("#select_edit_nacionalidad").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#select_nacionalidad").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
                $("#select_edit_nacionalidad").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
            });
			$("#select_nacionalidad").val(37)
        },
        complete : function(){

        }
    });
}
function generar_spei(concepto){
	$.ajax({
        url: '../assets/data/Controller/planpagos/pagosControl.php',
        type: 'POST',
        data: {action:'generarSPEI', 'concepto':concepto, 'persona':$("#person_pago").val()},
        dataType: 'JSON',
        success : function(data){
            if(data.estatus == 'error'){
				swal({
					icon: 'info',
					text: data.info
				});
			}else{
				$("#container_ficha_spei").html(`
				<div class="alert alert-warning mb-0 mt-4 px-5">
					<h5 class="mx-4">Toma una captura de la ficha, verifica que sea legible y que contenga todas las instrucciones de pago, después, compártela con el alumno</h5>
				</div>
				<div class="spei mt-3">
				<div class="ps-header">
				  <div class="ps-reminder">Ficha digital. No es necesario imprimir.</div>
				  <div class="ps-info">
					<div class="ps-brand"><img src="spei_brand_transferencia.png" alt="Banorte"></div>
					<div class="ps-amount">
					  <h3>Monto a pagar</h3>
					  <h2> ${data.monto} <sup>${data.tipo_moneda}</sup></h2>
					  <p>Utiliza exactamente esta cantidad al realizar el pago.</p>
					</div>
				  </div>
				  <div class="ps-reference">
					<h3>CLABE</h3>
					<h1>${data.CLABE}</h1>
				  </div>
				</div>
				<div class="ps-instructions">
				  <h3>Instrucciones</h3>
				  <ol>
					<li>Accede a tu banca en línea.</li>
					<li>Da de alta la CLABE en esta ficha. <strong>El banco deberá de ser STP</strong>.</li>
					<li>Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, <strong>de lo contrario se rechazará el cargo</strong>.</li>
					<li>Al confirmar tu pago, el portal de tu banco generará un comprobante digital. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
				  </ol>
				  <div class="ps-footnote"><strong>Tu pago se verifica en automático no necesitas reportarlo</strong></div>
				</div>
				<br>
  
			  </div>	<!--- fin de mostrar ficha pago transferencia spei-->`);
			}
			elem = document.getElementById('modal_registrar_pago');
    		elem.scrollTop = elem.scrollHeight;
        },
        complete : function(){

        }
    });
}
