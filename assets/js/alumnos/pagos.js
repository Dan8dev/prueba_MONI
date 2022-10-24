let global_concepto = null;
let porcent_recargo = 0;
$(document).ready(function(){
	init();
})
function init(){
	consultar_porcentaje_recargo()
}



function pago_carrera(id_reg, evento, nombre, inst) {
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
				$("#form_registrar_pago")[0].reset()
				$("#check_solo_inscripciones").prop('checked', false)
				var opc = `<option value="" selected disabled>Seleccione</option>`
				var opc_generaciones = `<option disabled value="" selected>Seleccione</option>`
				$("#inp_promos_disp").html(`<option value="" selected>Seleccione</option>`)
				$("#tab_registrar_pag").click();
				$("#tabla_pagos_notificados").DataTable().clear();
				$("#tab_generacion_pag").css("display", "none");
				(resp.data.tipoPago==1)?tipomoneda='MXN':tipomoneda='USD';
				$("#tipomonedausdmontomkt").html('('+tipomoneda+')')
				$("#tab_generacion_pag").removeClass('disabled')

				if (resp.data) {
					promos_conceptos = resp.data.generaciones;
					conceptos_pagar = resp.data.pagos_aplicar;
					for (var g in resp.data.generaciones) {
						if (resp.data.generaciones[g].asignacion.length > 0) {
							$("#tab_generacion_pag").addClass('disabled')
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
							}else{
								dateNew = resp.data.pagos_aplicar[i].fechalimitepago;
							}
							
							opc += `<option value="${resp.data.pagos_aplicar[i].id_concepto}" category="${resp.data.pagos_aplicar[i].categoria}" data-precio="${resp.data.pagos_aplicar[i].precio}">${resp.data.pagos_aplicar[i].concepto} ${(resp.data.pagos_aplicar[i].fechalimitepago !== null) ? '(' + dateNew.substr(0, 10) + ')' : ''}</option>`
						}
						suma_aplicados_gen = false;
						monto_por_cubrir = false;
						var promesadepago = false;
						promesadepago = resp.data.pagos_aplicar[i].aplicados.find(elm => elm.promesa_de_pago !== null);
						promesadepago = Boolean(promesadepago)
						for (var j in resp.data.pagos_aplicar[i].aplicados) {
							aplicado_p = resp.data.pagos_aplicar[i].aplicados[j];
							aplicado_p.detalle_pago = JSON.parse(aplicado_p.detalle_pago)
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
							$("#tabla_pagos_notificados").DataTable().row.add([
								`<span title="${aplicado_p.fechapago}">${aplicado_p.fechapago.substr(0, 10)}</span>`,
								aplicado_p.concepto_nom,
								`<span class="text-info">${moneyFormat.format((parseFloat(aplicado_p.montopagado) + parseFloat(aplicado_p.cargo_retardo)))}</span>`,
								aplicado_p.detalle_pago.id+' '+((aplicado_p.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_p.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								detalle_p,
								`<span class="text-info">${aplicado_p.estatus.toUpperCase()} ${promesa}</span>`
							])
						}

						for (var r in resp.data.pagos_aplicar[i].rechazados) {
							aplicado_r = resp.data.pagos_aplicar[i].rechazados[r];
							aplicado_r.detalle_pago = JSON.parse(aplicado_r.detalle_pago)
							$("#tabla_pagos_notificados").DataTable().row.add([
								`<span title="${aplicado_r.fechapago}">${aplicado_r.fechapago.substr(0, 10)}</span>`,
								aplicado_r.concepto_nom,
								`<span class="text-info">${moneyFormat.format((parseFloat(aplicado_r.montopagado) + parseFloat(aplicado_r.cargo_retardo)))}</span>`,
								aplicado_r.detalle_pago.id+' '+((aplicado_r.comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${aplicado_r.comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
								'',
								`<span class="text-info">${aplicado_r.estatus.toUpperCase()}</span>`
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

function set_concepto(select) {
	// $("#inp_monto_pago").val(moneyFormat.format($(select).find(":selected").attr('data-precio')));
	$('#mostrar_ficha').hide();
	$('#btnSave2').hide();
	$('#monto_recargo_ficha').val('');
}

$("#form_registrar_pago").on('submit', function (e) {
	e.preventDefault();
	fData = new FormData(this);
	fData.append("action", 'pago_prospecto')
    fData.append("form_alumno", 'Cobranza')

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
				init_d();
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
			$("#notifica_parcialidades").append(`<div class="alert alert-danger">Este concepto de pago aún tiene pagos pendientes por verificar. Corrobore los pagos antes de registrar uno nuevo.</div>`);
		}
		$("#inp_monto_pago").val(moneyFormat.format(precio_pago));
		$("#inp_monto_pago").on('change', function () {

		})
	} */
}

// $("#tipo_pago").change(function () {
// 	global_concepto = null;
// 	if ($("#tipo_pago").val()) {
// 		monto_promo = 0;
// 		global_concepto = conceptos_pagar.find(elm => elm.id_concepto == $(this).val());

// 		var disponibles = 0;
// 		var opc_promos = "<option value='' selected>Seleccione</option>";
// 		for (var f in global_concepto.promociones) {
// 			if (parseFloat(global_concepto.promociones[f].porcentaje) > 0 && (parseInt(global_concepto.promociones[f].id_concepto) == parseInt($("#tipo_pago").val()))) {
// 				disponibles++;
// 				opc_promos += `<option value="${global_concepto.promociones[f].idPromocion}" data-porcent="${global_concepto.promociones[f].porcentaje}">${global_concepto.promociones[f].nombrePromocion}&nbsp;${global_concepto.promociones[f].porcentaje}%</option>`
// 			}
// 		}

// 		if (disponibles == 0) {
// 			opc_promos = `<option value="">No hay promociones disponibles</option>`
// 		}
// 		$("#inp_promos_disp").html(opc_promos)

// 		calcular_parametros();
// 	} else {
// 		$("#form_registrar_pago")[0].reset()
// 		$("#inp_monto_pago").removeClass('text-danger')
// 		$("#notifica_fechap").html('')
// 	}
// })
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
				$("#inp_monto_pago").val(moneyFormat.format(parseFloat(concepto.monto_por_pagar) + parseFloat(concepto.monto_retardo)));
				if(concepto.monto_retardo > 0){
					$("#notifica_fechap").html(`<div class="alert alert-danger"> El alumno presenta un recargo sobre su mensualidad</div>`)
				}else{
					$("#notifica_fechap").html('')
				}
				if(concepto.pago_pendiente.length > 0){
					// $("#notifica_parcialidades").html(`<div class="alert alert-danger">Este concepto de pago aún tiene pagos pendientes por verificar. Corrobore los pagos antes de registrar uno nuevo.</div>`);
					// $("#inp_monto_pago").maskMoney('destroy');
					// $("#inp_monto_pago").attr('readonly',true)
					// $("#form_registrar_pago button[type='submit']").attr('disabled', true);
					// $("#generar_ficha_pago_oxxo").attr('disabled', true);
					
				}else{
					// $("#inp_monto_pago").maskMoney();
					// $("#inp_monto_pago").attr('readonly',false)
					// $("#form_registrar_pago button[type='submit']").attr('disabled', false);
					// $("#generar_ficha_pago_oxxo").attr('disabled', false);
					
					// $("#notifica_parcialidades").html(``);
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		}
	})
}
$("#tipo_pago").change(function () {
	consultar_pago_aplicar()
})
// $("#inp_promos_disp").change(function () {
// 	calcular_parametros()
// })

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

$("#inp_fecha_pago").on('change', function () {
	// calcular_parametros();
	consultar_pago_aplicar()
})

$( "#metododepago1subir" ).change(function() {

	$('#pagoenefectivosubir').hide();
	$('#chechenominativosubir').hide();
	$('#tarjetadecreditosubir').hide();
	$('#tarjetadedebitosubir').hide();
	$('#transferenciaelectronicasubir').hide();
	$('#paypalsubir').hide();

	$('#noselectsubir').prop( "selected", true )


	var metododepago = $( "#metododepago1subir" ).val();
	$('#mostrarmetododepago').show();
	switch (metododepago) {
		case '1':
			$('#pagoenefectivosubir').show();
			$('#chechenominativosubir').show();
			break;
		case '2':
			$('#pagoenefectivosubir').show();
			$('#chechenominativosubir').show();
			break;
		case '3':
			$('#pagoenefectivosubir').show();
			$('#tarjetadecreditosubir').show();
			$('#tarjetadedebitosubir').show();
			break;
		case '4':
			$('#pagoenefectivosubir').show();
			$('#chechenominativosubir').show();
			break;
		case '5':
			$('#pagoenefectivosubir').show();
			$('#tarjetadecreditosubir').show();
			$('#tarjetadedebitosubir').show();
			break;
		case '6':
			$('#transferenciaelectronicasubir').show();
			$('#metododepago').val('Transferencia eletrónica');
			break;
		case '7':
			$('#paypalsubir').show();
			$('#metododepago').val('Paypal');
			break;
		default:
			break;
	}

  });
