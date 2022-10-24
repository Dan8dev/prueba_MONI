const meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
const currencyF = { style: 'currency', currency: 'USD' };
const moneyFormat = new Intl.NumberFormat('en-US', currencyF);

let conceptos_especiales = ['mensualidad_especial','inscripcion_especial','reinscripcion_especial'];
let plan_pago = null;

let porcent_recargo = 0;

$(document).ready(function(){
	init_datos();
})
function init_datos(){
	revisar_inscripciones();
	consultar_porcentaje_recargo();
	consultar_historial_pago();
}
function revisar_inscripciones(){
	$.ajax({
		url: "../../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: {action:'consultarInscripciones'},
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				resp = JSON.parse(data);
				opc = "<option selected disabled>Seleccion su inscripción.</option>";
				opc_insc = "<option disabled>Seleccione para visualizar su plan de pagos.</option>";
				if(resp.estatus == 'ok'){
					var o_selected = false;
					for (i = 0; i < resp.data.length; i++) {
						if(resp.data[i].hasOwnProperty('idEvento')){
							if(resp.data[i].idEvento != 2){
								opc+= "<option info-e='evento' value='"+resp.data[i].idEvento+"'>"+resp.data[i].titulo+"</option>";
							}
						}else{
							opc+= "<option info-e='carrera' value='"+resp.data[i].idCarrera+"'>"+resp.data[i].nombre+"</option>";
						}
						if(resp.data[i].hasOwnProperty('asignado') && resp.data[i].asignado && resp.data[i].hasOwnProperty('idCarrera')){
							opc_insc+= `<option value='${resp.data[i].idCarrera}' ${!o_selected ? 'selected' : ''}>${resp.data[i].nombre}</option>`;
							o_selected = true;
						}
					}
				}
				$("#select_inscripciones").html(opc);
				$("#select_inscrito").html(opc_insc);
				$("#select_inscrito").change();
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

$("#select_inscrito").on('change', function(){
	ver_mensualidades('alumno', user_info.id_prospecto, $(this).val(), 0, '../../assets/data/Controller/planpagos/pagosControl.php', 'show_plan');
})

let call_b = null;

$("#select_inscripciones").on('change', function(){
	id_carrera=$("#select_inscripciones").val();
	$("#card_pay_now").html('')
	
	if($("#select_inscripciones option:selected").attr('info-e') == 'carrera'){
		$.ajax({
			type: "POST",
			url: "../../assets/data/Controller/planpagos/pagosControl.php",
			data: {action:'obtener_plan_pago_callcenter', inscrito_a : $("#select_inscripciones").val(), instit:13},
			success: function(response) {
				try{
					var d = new Date();
					var fecha_hoy = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
					html_con = "";
					resp_conceptos = JSON.parse(response);
					plan_pago = null;
					if(resp_conceptos.estatus == 'error'){
						swal(resp_conceptos.info);
						$("#lbl_plan_pagos").html("");
						$("#card_pay_now").html('');
						$("#card_pay_now").css("display", "none");
						$("#tabla_conceptos_pagar").DataTable().clear().draw()
					}else{
						planes = resp_conceptos.data;
						asignacion_g = resp_conceptos.data.generaciones.reduce( (acc, it)=>{
							return (it.asignacion.length > 0)? true : acc;
						}, false);
						if(!asignacion_g){
							$("#generaciones_disp").html(`
							<div class="card bg-primary text-white mb-4">
							<div class="card-body">
							<h5>Gracias por inscribirte a <strong>${$("#select_inscripciones option:selected").html()}</strong></h5> <br>Contamos con ${resp_conceptos.data.generaciones.length} opciones disponibles para la oferta educativa seleccionada.<br>
							Lee atentamente y selecciona la opcion que mas te favorezca.
							<br>
							
								</div>
							</div>`);
						}else{
							$("#generaciones_disp").html('')
						}

						$("#lbl_plan_pagos").html(`<b>Pagar Inscripción</b>`);
						plan_pago = planes;
						(planes.tipoPago==2)?tipomoneda = ' USD':tipomoneda = ' MXN';
						$("#tabla_conceptos_pagar").DataTable().clear();
						for (let j = 0; j < planes.pagos_aplicar.length; j++) {
							conteo = "";
							if(planes.pagos_aplicar[j].hasOwnProperty('aplicados')){
								if(planes.pagos_aplicar[j].parcialidades == 2 && planes.pagos_aplicar[j].aplicados.length > 0 && planes.pagos_aplicar[j].aplicados[planes.pagos_aplicar[j].aplicados.length - 1].hasOwnProperty('numero_de_pago')){
									conteo = `(${planes.pagos_aplicar[j].aplicados[planes.pagos_aplicar[j].aplicados.length - 1].numero_de_pago} pagos realizados de ${planes.pagos_aplicar[j].numero_pagos})`
								}else{
									conteo = `(${planes.pagos_aplicar[j].aplicados.length} pagos realizados de ${planes.pagos_aplicar[j].numero_pagos})`
								}
								if(planes.pagos_aplicar[j].numero_pagos <= planes.pagos_aplicar[j].aplicados.length){
									conteo = "<span class='text-primary'>"+conteo+"</span>";
									if(!asignacion_g){
										$("#generaciones_disp").html(`<div class="alert alert-primary">Hemos recibido la información de su pago. <br> Por favor espere a que el personal administrativo confirme su ingreso a la generación solicitada.</div>`)
									}
								}
							}else{
								conteo = `${planes.pagos_aplicar[j].numero_pagos} pagos por aplicar`
							}
							var string_precio = moneyFormat.format(planes.pagos_aplicar[j].precio);

							if(planes.pagos_aplicar[j].parcialidades == 1){
								if(planes.pagos_aplicar[j].aplicados.length > 0){
									//var restan = parseFloat(planes.pagos_aplicar[j].precio) - planes.pagos_aplicar[j].aplicados.reduce( (acc, it)=>{return acc+=parseFloat(it.montopagado)}, 0);
									string_precio = `${moneyFormat.format(planes.pagos_aplicar[j].precio)} (restan: ${moneyFormat.format(planes.pagos_aplicar[j].aplicados[planes.pagos_aplicar[j].aplicados.length - 1].restante)})`
								}
							}
							var info_promo = '-';
							var monto_fin_promo = parseFloat(planes.pagos_aplicar[j].precio);
							if(planes.pagos_aplicar[j].promociones.length > 0 ){
								if(planes.pagos_aplicar[j].parcialidades == 1){
									// if(planes.pagos_aplicar[j].aplicados.length == 0){ /** ESTA PARTE SE COMENTO PARA QUE EL PORCENTAJE DE PROMOCIÓN SALIERA EN LA TABLA DE CONCEPTOS */
										var monto_fin_promo = parseFloat(planes.pagos_aplicar[j].precio);
										for(k in planes.pagos_aplicar[j].promociones){
											monto_fin_promo = monto_fin_promo - (monto_fin_promo * (parseFloat(planes.pagos_aplicar[j].promociones[k].porcentaje)/100));
										}
										info_promo = `<span class="text-success">${moneyFormat.format(monto_fin_promo)} ${tipomoneda}</span>`
									// }
								}else{
									monto_fin_promo = parseFloat(planes.pagos_aplicar[j].precio);
									var current_paynum = 0;
									if(planes.pagos_aplicar[j].aplicados.length > 0){
										current_paynum = Math.max(...planes.pagos_aplicar[j].aplicados.map(elm => parseInt(elm.numero_de_pago)));
									}
									
									for(k in planes.pagos_aplicar[j].promociones){
										if(typeof(planes.pagos_aplicar[j].promociones[k].Nopago) == 'object' || typeof(planes.pagos_aplicar[j].promociones[k].Nopago) == 'array'){
											if(planes.pagos_aplicar[j].promociones[k].Nopago.includes((current_paynum + 1).toString())){
												monto_fin_promo = monto_fin_promo - (monto_fin_promo * (parseFloat(planes.pagos_aplicar[j].promociones[k].porcentaje)/100));
											}
										}else{
											monto_fin_promo = monto_fin_promo - (monto_fin_promo * (parseFloat(planes.pagos_aplicar[j].promociones[k].porcentaje)/100));
										}
									}
									info_promo = `<span class="text-success">${moneyFormat.format(monto_fin_promo)} ${tipomoneda}</span>`
								}
							}

							
							var info_ext = '';
							if(planes.pagos_aplicar[j].generales !== null){
								info_ext = `<i class="fa fa-bookmark" title="Concepto general"></i> `
							}
							if(planes.pagos_aplicar[j].hasOwnProperty('info_gen')){
								fecha_inf = planes.pagos_aplicar[j].info_gen.fecha_inicio.substr(0,10).split('-');
								info_ext += `<b>Inicia el: ${fecha_inf[2]} de ${meses[parseInt(fecha_inf[1])-1]} ${fecha_inf[0]}</b><br>`
							}
							if(planes.pagos_aplicar[j].aplicados.length > 0){
								var num_pago_actual_prorroga = Math.max(...planes.pagos_aplicar[j].aplicados.map(elm => parseFloat(elm.numero_de_pago)));
							}else{
								var num_pago_actual_prorroga = 0;
							}
							var responsePro = solicitar_prorrogaOutClick(planes.idPlan, planes.pagos_aplicar[j].id_concepto, id_carrera, num_pago_actual_prorroga + 1,planes.pagos_aplicar[j].fechalimitepago)

							datePro = responsePro[0].response;
							var st = 0;
							if(responsePro[0].status == 'tardy' || responsePro[0].status == 'duplex' || parseInt(num_pago_actual_prorroga + 1) > parseInt(planes.pagos_aplicar[j].numero_pagos) ){
								tipoboton = 'btn-secondary';	
								if(responsePro[0].st == 'aprobado'){
									st = 1;
								}
							}else if(responsePro[0].status == "current" ){
								tipoboton = 'btn-info';
							}
							
							// if (planes.pagos_aplicar[j].estatus==null) {
							// 	tipoboton = 'btn-info';
							// }
							// if (planes.pagos_aplicar[j].estatus!=null || new Date(fecha_hoy) > new  Date(planes.pagos_aplicar[j].fechalimitepago)) {
							// 	tipoboton = 'btn-secondary';
							// }
							$("#tabla_conceptos_pagar").DataTable().row.add([
								info_ext+planes.pagos_aplicar[j].concepto+` <small id="aplied_${planes.pagos_aplicar[j].id_concepto}">${conteo}</small>`,
								string_precio + tipomoneda,
								info_promo, 
								(planes.pagos_aplicar[j].categoria=='Mensualidad' || planes.pagos_aplicar[j].categoria=='Titulación')? 
									(monto_fin_promo != 0) ? `<button class="btn btn-info btn-sm" onclick="ver_pagos(${planes.idPlan}, ${planes.pagos_aplicar[j].id_concepto}, ${id_carrera},'${datePro}',${st},'${'none'}','${tipomoneda}')">Pagar</button> `+ `<button class="btn ${tipoboton} btn-sm" onclick="solicitar_prorroga(${planes.idPlan}, ${planes.pagos_aplicar[j].id_concepto}, ${id_carrera}, ${num_pago_actual_prorroga + 1}, '${planes.pagos_aplicar[j].fechalimitepago}',${planes.pagos_aplicar[j].numero_pagos})"> Solicitar prorroga </button>`
								: 
									''
								:(monto_fin_promo != 0) ? 
									`<button class="btn btn-info btn-sm" onclick="ver_pagos(${planes.idPlan}, ${planes.pagos_aplicar[j].id_concepto}, ${id_carrera},'${datePro}',${st},'${'none'}','${tipomoneda}')"> Pagar </button> `
								:
									''
							]);
						}
						$("#tabla_conceptos_pagar").DataTable().draw();
						//$("#tabla_conceptos_pagar").DataTable().columns.adjust()
					}
					// $("#content_conep_pago").html(html_con);
					
				}catch(e){
					console.log(e);
					console.log(response);
				}
			},complete:function(){
				if(call_b !== null){
					call_b();
					call_b = null;
				}
			}
		});
	}else{
		$.ajax({
			type: "POST",
			url: "../../assets/data/Controller/planpagos/pagosControl.php",
			data: {action:'obtener_plan_pago_callcenter_eventos', inscrito_a : $("#select_inscripciones").val(), instit:13},
			success: function(response) {
				try{
					var d = new Date();
					var fecha_hoy = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
					html_con = "";
					resp_conceptos = JSON.parse(response);
					plan_pago = null;
					if(resp_conceptos.estatus == 'error'){
						swal(resp_conceptos.info);
						$("#lbl_plan_pagos").html("");
						$("#card_pay_now").html('');
						$("#card_pay_now").css("display", "none");
						$("#tabla_conceptos_pagar").DataTable().clear().draw()
					}else{
						planes = resp_conceptos.data;
						

						$("#lbl_plan_pagos").html(`<b>Pagar Inscripción</b>`);
						plan_pago = planes;
						
						$("#tabla_conceptos_pagar").DataTable().clear();
						for (let j = 0; j < planes.pagos_aplicar.length; j++) {
							conteo = "";
							var string_precio = moneyFormat.format(planes.pagos_aplicar[j].precio);

							if(planes.pagos_aplicar[j].parcialidades == 1){
								if(planes.pagos_aplicar[j].aplicados.length > 0){
									//var restan = parseFloat(planes.pagos_aplicar[j].precio) - planes.pagos_aplicar[j].aplicados.reduce( (acc, it)=>{return acc+=parseFloat(it.montopagado)}, 0);
									string_precio = `${moneyFormat.format(planes.pagos_aplicar[j].precio)} (restan: ${moneyFormat.format(planes.pagos_aplicar[j].aplicados[planes.pagos_aplicar[j].aplicados.length - 1].restante)})`
								}
							}
							var info_promo = '-';
							if(planes.pagos_aplicar[j].promociones.length > 0 ){
								if(planes.pagos_aplicar[j].parcialidades == 1){
									if(planes.pagos_aplicar[j].aplicados.length == 0){
										var monto_fin_promo = parseFloat(planes.pagos_aplicar[j].precio);
										for(k in planes.pagos_aplicar[j].promociones){
											monto_fin_promo = monto_fin_promo - (monto_fin_promo * (parseFloat(planes.pagos_aplicar[j].promociones[k].porcentaje)/100));
										}
										info_promo = `<span class="text-success">${moneyFormat.format(monto_fin_promo)}</span>`
									}
								}else{
									var monto_fin_promo = parseFloat(planes.pagos_aplicar[j].precio);
									for(k in planes.pagos_aplicar[j].promociones){
										monto_fin_promo = monto_fin_promo - (monto_fin_promo * (parseFloat(planes.pagos_aplicar[j].promociones[k].porcentaje)/100));
									}
									info_promo = `<span class="text-success">${moneyFormat.format(monto_fin_promo)}</span>`
								}
							}

							
							var info_ext = '';
							if(planes.pagos_aplicar[j].generales !== null){
								info_ext = `<i class="fa fa-bookmark" title="Concepto general"></i> `
							}
							if(planes.pagos_aplicar[j].hasOwnProperty('info_gen')){
								fecha_inf = planes.pagos_aplicar[j].info_gen.fecha_inicio.substr(0,10).split('-');
								info_ext += `<b>Inicia el: ${fecha_inf[2]} de ${meses[parseInt(fecha_inf[1])-1]} ${fecha_inf[0]}</b><br>`
							}
							var num_pago_actual_prorroga = Math.max(...planes.pagos_aplicar[j].aplicados.map(elm => parseFloat(elm.numero_de_pago)));

							var responsePro = solicitar_prorrogaOutClick(planes.idPlan, planes.pagos_aplicar[j].id_concepto, id_carrera, num_pago_actual_prorroga + 1,planes.pagos_aplicar[j].fechalimitepago)

							datePro = responsePro[0].response;
							var st = 0;
							if(responsePro[0].status == 'tardy' || responsePro[0].status == 'duplex' || parseInt(num_pago_actual_prorroga + 1) > parseInt(planes.pagos_aplicar[j].numero_pagos) ){
								tipoboton = 'btn-secondary';	
								if(responsePro[0].st == 'aprobado'){
									st = 1;
								}
							}else if(responsePro[0].status == "current" ){
								tipoboton = 'btn-info';
							}
							
							// if (planes.pagos_aplicar[j].estatus==null) {
							// 	tipoboton = 'btn-info';
							// }
							// if (planes.pagos_aplicar[j].estatus!=null || new Date(fecha_hoy) > new  Date(planes.pagos_aplicar[j].fechalimitepago)) {
							// 	tipoboton = 'btn-secondary';
							// }
							$("#tabla_conceptos_pagar").DataTable().row.add([
								info_ext+planes.pagos_aplicar[j].concepto+` <small id="aplied_${planes.pagos_aplicar[j].id_concepto}">${conteo}</small>`,
								string_precio,
								info_promo, (planes.pagos_aplicar[j].categoria=='Mensualidad'||planes.pagos_aplicar[j].categoria=='Titulación')? (monto_fin_promo != 0)? `<button class="btn btn-info btn-sm" onclick="ver_pagos(${planes.idPlan}, ${planes.pagos_aplicar[j].id_concepto}, ${id_carrera},'${datePro}',${st})">
								Pagar
							</button> `+ `<button class="btn ${tipoboton} btn-sm" onclick="solicitar_prorroga(${planes.idPlan}, ${planes.pagos_aplicar[j].id_concepto}, ${id_carrera}, ${num_pago_actual_prorroga + 1}, '${planes.pagos_aplicar[j].fechalimitepago}',${planes.pagos_aplicar[j].numero_pagos})">
							Solicitar prorroga
						</button>`:' ': (monto_fin_promo != 0)?`<button class="btn btn-info btn-sm" onclick="ver_pagos(${planes.idPlan}, ${planes.pagos_aplicar[j].id_concepto}, ${id_carrera},'${datePro}',${st}, 'evento')">
							Pagar
						</button> `:''
							]);
							
						}
						$("#tabla_conceptos_pagar").DataTable().draw();
						//$("#tabla_conceptos_pagar").DataTable().columns.adjust()
					}
					// $("#content_conep_pago").html(html_con);
					
				}catch(e){
					console.log(e);
					console.log(response);
				}
			},complete:function(){
				if(call_b !== null){
					call_b();
					call_b = null;
				}
			}
		});
	}
})

function ver_pagos(plan, id_concepto,id_carrera,date_show,st, evento='none',tipomoneda){
	concepto = plan_pago.pagos_aplicar.find(elm => elm.id_concepto == id_concepto);
	fData = {
		action: 'obtener_info_pago_aplicar',
		alumno: user_info.id_prospecto,
		concepto: id_concepto
	}
	$.ajax({
		url: "../../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: fData,
		success: function (data) {
			try {
				var pagar = JSON.parse(data);
				var total_por_pagar = parseFloat(pagar.monto_por_pagar) + parseFloat(pagar.monto_retardo);
				if(total_por_pagar <= 1){
					swal('Ya se ha cubierto el total de los pagos')
				}else if(pagar.hasOwnProperty('porcentaje_promocion') && pagar.porcentaje_promocion == 100){
					swal('Usted cuenta con una beca al 100% sobre este concepto.');
				}else{
					total_por_pagar = moneyFormat.format(total_por_pagar);
					var html_pend = '';
					if(pagar.hasOwnProperty('band_saldo_pendiente')){
						if(pagar.band_saldo_pendiente && concepto.categoria == 'Mensualidad'){
							html_pend = `<small class="text-success">(saldo pendiente del pago anterior de mensualidad)</small>`
						}
					}
					var str_parc = '';
					if(concepto.parcialidades == 1){
						str_parc = `<button class="btn btn-primary" onclick="pagar_otra_cantidad(${id_concepto}, '${total_por_pagar}')">Pagar otra cantidad</button>`
					}
					string_concepto = concepto.concepto;
					if(concepto.categoria == 'Mensualidad' && pagar.numero_de_pago > 0){
						string_concepto = concepto.concepto.slice(0,11)+` [N° ${pagar.numero_de_pago}]`+concepto.concepto.slice(11)
					}
					string_fecha = '';
					if(pagar.fecha_limite_pago != null){
						string_fecha = `<p class="card-text"><i>Fecha limite pago: ${pagar.fecha_limite_pago.substr(0,10)}</i></p>`;
					}
					if(tipomoneda === undefined){
						tipomoneda = 'MXN';
					}
					var card_pago = `
						<div id="card_pay_now" style="display: block;"><div class="text-center">
							<h5>Pagar ahora</h5>
						</div>
						<div class="card text-center mb-4">
							<div class="card-header bg-primary text-light">
								${string_concepto} <i class="float-right"></i>
							</div>
							<div class="card-body">
							${html_pend}
							<h2 class="card-title text-success">${total_por_pagar}${tipomoneda}</h2>
							<p class="card-text">${concepto.concepto}</p>
								${string_fecha}
								<h5 class="card-title text-success mb-0">${(pagar.porcentaje_promocion > 0 ? "- "+parseFloat(pagar.porcentaje_promocion).toFixed(2)+"% ":'')}</h5>
								<h5 class="card-title text-warning mb-0">${(pagar.monto_retardo > 0 ? "+ "+moneyFormat.format(pagar.monto_retardo)+" Por recargo":'')}</h5>
								
								<div class="text-center pd-y-10 col-sm-12" id="content_pagar_otra_cantidad">
								</div>
							<button class="btn btn-primary" onclick="procesar_pago_multi(${id_concepto},${pagar.monto_por_pagar}, ${pagar.monto_retardo}, ${(concepto.categoria == 'Mensualidad' && pagar.numero_de_pago > 0)? pagar.numero_de_pago : null}, '${concepto.descripcion}', '${concepto.concepto}', ${concepto.parcialidades}, ${pagar.monto_promocion},'${tipomoneda}')">Pagar Ahora</button>
							${str_parc}
							<button class="btn btn-info" onclick="notificar_otro_pago(${id_concepto}, '${total_por_pagar}','${tipomoneda}')">Notificar pago realizado por otro medio</button>
							</div>
							<div class="card-footer text-muted bg-primary text-light">
							</div>
							</div>
							<hr></div>
							`;
	
						$("#container_pagos").html(card_pago);
						window.scrollTo(0,document.body.scrollHeight);
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		}
	})
	/*
	$.ajax({
		url:'../../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'obtener_pagos_aplicados', id_concepto:id_concepto},
		success: function(data){
			try{
				aplicados = JSON.parse(data);
				plan_pago.pagos_aplicar.find(elm => elm.id_concepto == id_concepto).aplicados = aplicados;
				
				// verificar si ya se aplico todo el pago
				conteo = `(${aplicados.length} pagos realizados de ${concepto.numero_pagos})`
				$("#aplied_"+id_concepto).html(conteo);
				var final_a_pagar = null;
				final_a_pagar = (aplicados.length > 0) ? parseFloat(aplicados[0].costototal) : null;
				var pagado = aplicados.reduce((acc,it)=>{return acc+=parseFloat(it.montopagado);}, 0);
				pagado = pagado.toFixed(2);
				if((aplicados.length < parseInt(concepto.numero_pagos)||(concepto.parcialidades==1 && (pagado < concepto.precio) && final_a_pagar == null) || (final_a_pagar !== null && final_a_pagar > pagado) ) || parseInt(concepto.generales) == 1){
						console.log(concepto.promociones);
						//if (concepto.promociones[(aplicados.length==0)?aplicados.length:aplicados.length-1].porcentaje<100) {
						if (concepto.promociones.length > 0 && concepto.promociones[0].porcentaje == 100 ) {
							$("#card_pay_now").css('display','block');
							$("#card_pay_now").html('<div class="text-center p-3 py-4 my-4 border text-white bg-primary"><h3>Cuenta con beca al 100%</h3></div>');
							swal('El pago se ha condonado')
						}else{
							agregar_boton_pago(concepto.concepto, concepto.precio, concepto.descripcion, (aplicados.length+1), concepto.promociones, id_concepto, id_carrera,date_show,st, tipo);
						}
				}else{
					$("#card_pay_now").css('display','block');
					$("#card_pay_now").html('<div class="text-center p-3 py-4 my-4 border text-white bg-primary"><h3>Ya se ha cubierto el total de los pagos</h3></div>');
					swal('Ya se ha cubierto el total de los pagos')
				}
				
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	});*/
}

function solicitar_prorroga(plan, id_concepto,id_carrera,numero_de_pago, fechalimite_pago,npayment){
	fechalimite_pago = fechalimite_pago?.substring(0, 10);
	$.ajax({
		url: "../../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: {action:'validar_si_existe_prorroga', id_concepto:id_concepto, numero_de_pago:numero_de_pago},
		success: function(data){
			try{
				resp = JSON.parse(data);
				
				if(parseInt(npayment) < parseInt(numero_de_pago)){
					swal('Ya se ha cubierto el total de los pagos');
				}else{
					var d = new Date();
					var fecha_hoy = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
					if( new Date(fecha_hoy) >  new Date(fechalimite_pago+"T00:00:00") ){
						swal('No puede solicitar prorroga su fecha limite de pago ha vencido');
					}else{
						if(resp.data==false){
							$('#modal_solicitar_prorroga').modal('show');
							$('#id_concepto_prorroga').val(id_concepto);
							$("#numero_de_pago_prorroga").val(numero_de_pago);
							$("#fecha_limite_de_pago_prorroga").text(fechalimite_pago);
						}
						else{
							swal('Ya se ha solicitado una prorroga para este pago');
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
			$(".outerDiv_S").css("display", "none")
		}
	});

}

function solicitar_prorrogaOutClick(plan, id_concepto,id_carrera,numero_de_pago, fechalimite_pago){
	fechalimite_pago = fechalimite_pago?.substring(0, 10);
	var res= "1",
		date = "",
		st = "";
	$.ajax({
		url: "../../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: {action:'validar_si_existe_prorroga', id_concepto:id_concepto, numero_de_pago:numero_de_pago},
		async: false,
		success: function(data){
			resp = JSON.parse(data);
				var d = new Date();
				var fecha_hoy = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
				if( new Date(fecha_hoy) >  new Date(fechalimite_pago+"T00:00:00") ){
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
	return [{"status":res,"response":date,"st":st}];
}

$("#enviar_solicitud_boton").on("click", function() {
	if ($("#descripcion_prorroga").val()=='') {
		swal('Ingrese una descripción');
		return;
	}
	if ( $("#fecha_prorroga").val()=='') {
		swal('Ingrese la nueva fecha de pago');
		return;
	}
		
	
	$.ajax({
		url:'../../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'solicitar_prorroga',
				 id_concepto:$('#id_concepto_prorroga').val(),
				 descripcion_prorroga:$("#descripcion_prorroga").val(),
				 nueva_fecha_prorroga:$("#fecha_prorroga").val(),
				 numero_de_pago:$("#numero_de_pago_prorroga").val(),
				 fecha_limite_pago:$("#fecha_limite_de_pago_prorroga").text()
				},
		success: function(response){
			try{
				response=JSON.parse(response);
				if(response.estatus == 'ok'){
					swal('Solicitud enviada correctamente');
					$('#modal_solicitar_prorroga').modal('hide');
					setTimeout(function() { window.location=window.location;},3000);
				}
				
				
			}catch(e){
				console.log(e);
				console.log(response);
			}
		}
	});

});


let global_concepto = null;

function notificar_otro_pago(concepto, monto,tipomoneda){
	$("#informacionPago").hide();
	$("#form_registrar_pago_alumno")[0].reset()
	global_concepto = null;
	var concep_pagar = plan_pago.pagos_aplicar.find(elm => elm.id_concepto == concepto);
	global_concepto = concep_pagar;
	if(concep_pagar){
		$("#tipo_pago").val(concepto);
		$("#person_pago").val(user_info.id_prospecto);
		consultar_pago_aplicar()
		// calcular_parametros();
		// if(concep_pagar.parcialidades == 1){
			$("#inp_monto_pago").attr('readonly', false);
			$("#inp_monto_pago").maskMoney();
			$("#tipodemonedaporpagar").html('('+tipomoneda+')');
			$("#tipodemonedamontopagado").html('('+tipomoneda+')');
		// 	$("#inp_monto_pago").val(monto);
		// }else{
		// 	$("#inp_monto_pago").attr('readonly', true);
		// 	$("#inp_monto_pago").maskMoney('destroy');
		// }
		$("#modal_notificar_pago").modal('show')
		$("#inp_monto_pago")[0].focus();
	}
}

function consultar_pago_aplicar(){
	fData = {
		action: 'obtener_info_pago_aplicar',
		alumno: $("#person_pago").val(),
		concepto: $("#tipo_pago").val(),
		fecha_pago: $("#inp_fecha_pago").val(),
	}
	$.ajax({
		url: "../../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: fData,
		success: function (data) {
			try {
				var concepto = JSON.parse(data);
				// $("#inp_monto_pago").val(moneyFormat.format(parseFloat(concepto.monto_por_pagar) + parseFloat(concepto.monto_retardo)));
				$("#inp_monto_pago_show").val(moneyFormat.format(parseFloat(concepto.monto_por_pagar) + parseFloat(concepto.monto_retardo)));
				if(concepto.monto_retardo > 0){
					$("#notifica_fechap").html(`<div class="alert alert-danger"> Actualmente presenta un recargo sobre su mensualidad</div>`)
				}else{
					$("#notifica_fechap").html('')
				}
				if(concepto.pago_pendiente.length > 0){
					// $("#notifica_parcialidades").html(`<div class="alert alert-danger">Este concepto de pago aún tiene pagos pendientes por verificar. Corrobore los pagos antes de registrar uno nuevo.</div>`);
					// $("#inp_monto_pago").maskMoney('destroy');
					// $("#inp_monto_pago").attr('readonly',true)
					// $("#form_registrar_pago_alumno button[type='submit']").attr('disabled',true);
				}else{
					// $("#inp_monto_pago").maskMoney();
					// $("#inp_monto_pago").attr('readonly',false)
					// $("#form_registrar_pago_alumno button[type='submit']").attr('disabled',false);
					// $("#notifica_parcialidades").html(``);
				}
			} catch (e) {
				console.log(e);
				console.log(data);
			}
		}
	})
}

function calcular_parametros(){
	/* var fecha_p = $("#inp_fecha_pago").val();
	if (fecha_p != '' && global_concepto != null) {
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
			$("#inp_promos_disp").val('');
			$("#notifica_fechap").html(`<div class="alert alert-danger"> Se está recibiendo un pago posterior a la fecha limite de pago, es posible que se apliquen recargos</div>`)
		} else {
			$("#notifica_fechap").html('')
			// global_concepto.promociones = [];
		}

		if (parseInt(global_concepto.parcialidades) == 1) {
			if (!bandera_retardo && global_concepto.aplicados.length == 0) {
				if(global_concepto.promociones.length > 0){
					var promocion_sel = global_concepto.promociones[0];
					precio_pago = precio_pago - (parseFloat(promocion_sel.porcentaje) / 100) * precio_pago;
				}
			} else {
				$("#inp_promos_disp").val('');
				var suma_pagos_verificados = global_concepto.aplicados.reduce(function (a, b) { return a += (b.estatus == 'verificado') ? parseFloat(b.montopagado) : 0; }, 0);
				precio_pago = parseFloat(global_concepto.aplicados[0].costototal) - suma_pagos_verificados;
			}
			if (!bandera_retardo) {
				$("#inp_promos_disp").val(global_concepto.promociones.length > 0 ? global_concepto.promociones[0].idPromocion : '')
				$("#inp_monto_pago").maskMoney();
				$("#inp_monto_pago").attr('readonly', false)
			} else {
				$("#inp_monto_pago").maskMoney('destroy');
				$("#inp_monto_pago").attr('readonly', true)
			}
		}else{
			$("#inp_monto_pago").maskMoney('destroy');
			$("#inp_monto_pago").attr('readonly', true)
			$("#inp_promos_disp").val(global_concepto.promociones.length > 0 ? global_concepto.promociones[0].idPromocion : '')
			if(global_concepto.promociones.length > 0){
				var promocion_sel = global_concepto.promociones[0];
				precio_pago = precio_pago - (parseFloat(promocion_sel.porcentaje) / 100) * precio_pago;
			}
			if(!bandera_retardo){
			}else{
				var recargo_monto_pago = precio_pago * (porcent_recargo / 100);
				precio_pago = precio_pago + (precio_pago * (porcent_recargo / 100));
			}
		}
		if(global_concepto.aplicados.find(elm => elm.estatus == 'pendiente')){
			$("#notifica_parcialidades").html(`<div class="alert alert-danger">Este concepto de pago aún tiene pagos pendientes por verificar. Corrobore los pagos antes de registrar uno nuevo.</div>`);
		}
		$("#inp_monto_pago").val(moneyFormat.format(precio_pago));
	} */
}

	$("#inp_fecha_pago").on('change', function(){
		// calcular_parametros()
		consultar_pago_aplicar()
	})

// $("#form_registrar_pago_alumno").on('submit', function(e){
// 	e.preventDefault();
// 	fData = new FormData(this);
// 	fData.append('action', 'pago_prospecto');
// 	fData.append('form_alumno', 'Alumno');
// 	$.ajax({
// 		url: "../../assets/data/Controller/planpagos/pagosControl.php",
// 		type: "POST",
// 		data: fData,
// 		contentType: false,
// 		processData:false,
// 		beforeSend : function(){
// 			$("#form_registrar_pago_alumno button[type='submit']").prop('disabled', true);
// 		},
// 		success: function(data){
// 			try{
// 				resp = JSON.parse(data)
// 				if(resp.estatus == 'ok'){
// 					swal({
// 						icon:'success',
// 						title:'Pago noticado',
// 						text:'Espere a que el departamento de cobranza valide su pago. Una vez validado se verá reflejado en su estado de cuenta.'
// 					})
// 				}else{
// 					swal({
// 						icon:'info',
// 						text:'Ocurrió un error al reportar el pago. Contacte con soporte técnico'
// 					})
// 				}
// 				$("#modal_notificar_pago").modal('hide')
//           		console.log(resp)
// 			}catch(e){
// 				console.log(e);
// 				console.log(data);
// 			}
// 			  $("#form_registrar_pago_alumno")[0].reset()
// 		},
// 		error: function(){
// 		},
// 		complete: function(){
// 			$("#form_registrar_pago_alumno button[type='submit']").prop('disabled', false);
// 		}
// 	});
// });
$("#form_registrar_pago_alumno").on('submit', function (e) {
	e.preventDefault();
	if(parseFloat($("#inp_monto_pago").val().replace(/\$|,/g, "")) > 0){
		fData = new FormData(this);
		fData.append("action", 'pago_prospecto')
		fData.append("form_alumno", 'Alumno')
		fData.append("android_id_prospecto", user_info.id_prospecto);

		$.ajax({
			url: "https://moni.com.mx/assets/data/Controller/planpagos/pagosControl.php",
			type: "POST",
			data: fData,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$("#form_registrar_pago_alumno").find('button[type=submit]').attr('disabled', true)
			},
			success: function (data) {
				try {
					resp = JSON.parse(data);
					if (resp.estatus == 'ok') {
						swal({ icon: 'success', title: 'Pago registrado, espere a que contabilidad verifique su pago.' })
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

					$("#modal_notificar_pago").modal('hide')
					$("#home-tab").click();
					$("#carrer-tab").click();
					init_datos();
					$("#form_registrar_pago_alumno")[0].reset()

				} catch (e) {
					console.log(e);
					console.log(data);
				}
			},
			error: function () {
			},
			complete: function () {
				$("#form_registrar_pago_alumno").find('button[type=submit]').attr('disabled', false)
			}
		});
	}else{
		swal({
			icon: 'info',
			title: 'El monto de pago debe ser mayor a 0',
			text: 'Por favor verifique el monto de pago'
		})
	}
})

function procesar_pago_parcial(id_concepto, monto_pago, re, tipo){
	$("#check_domiciliar_pago").hide();
	var montoparcial = $("#montoapagar").val().replace(/\$|,/g, "");
	if(montoparcial == ""){
		montoparcial=monto_pago;
	}
	if(re){
		var concep_pagar = plan_pago.pagos_aplicar.find(elm => elm.id_concepto == id_concepto);
		montoparcial = parseFloat(concep_pagar.precio) + (parseFloat(concep_pagar.precio) * (porcent_recargo / 100));
		$("#montoapagar").attr('readonly', true);
		$("#montoapagar").val(moneyFormat.format(montoparcial));
	}else{
		$("#montoapagar").attr('readonly', false);
	}
	$.ajax({
		url:'../../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'procesar_pago', id_concepto:id_concepto, inscrito_a:$("#select_inscripciones").val(), tipo:tipo},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				pago = JSON.parse(data);
				var precio_retardo = parseFloat(pago.precio);
				if(re){
					precio_retardo = precio_retardo + (precio_retardo * (porcent_recargo / 100));
				}
				if(pago.hasOwnProperty('estatus') && pago.estatus == "error"){
					swal({
						icon:'info',
						text: pago.info
					})
				}else{
					var list_promos = "";
					
					for (var i = 0; i < pago.promociones.length; i++) {
						if(!re){
							list_promos += `<li title="Se ve reflejado al procesar el pago.">${pago.promociones[i].nombrePromocion} ${pago.promociones[i].porcentaje}% -<i></i></li>`
						}
					}
					var descripcionpago = "";
					if(re){
						descripcionpago = `<h3 class="text-warning">Pago fuera de tiempo: <u>${moneyFormat.format(precio_retardo)}</u></h3>`
					}

					html_pago_fin = `
									<div class="card text-center">
										<div class="card-header bg-primary text-light">
											${pago.concepto} <i class="float-right">Pago número ${pago.aplicados.length+1}</i>
										</div>
										<div class="card-body">
											<h2 id="monto_a_pagar" class="card-title text-success">${moneyFormat.format(montoparcial)}</h2>
											${descripcionpago}
											<input type="hidden" id="tipo_pago" name="tipo_pago" value="${id_concepto}">
											<p id="nombre_concepto" class="card-text">${pago.descripcion}</p>
										</div>
										<div class="card-footer bg-primary text-light">
											<div class="row">
												<div class="col">
													${list_promos}
												</div>
												<div class="col">
													<img class="float-right ml-1" width="50px" src="../img/visa_icon.png">
													<img class="float-right ml-1" width="50px" src="../img/mastercard_icon.png">
													<img class="float-right ml-1" width="50px" src="../img/american_icon.png">
												</div>
											</div>
										</div>
									</div>
									`
					var tipo_concepto_mensualidad_separar=pago.concepto;
					var tipo_concepto_mensualidad = tipo_concepto_mensualidad_separar.split("-");
					if(tipo_concepto_mensualidad[0] == "Mensualidad "){
						$("#check_domiciliar_pago").show();
					}

					$("#container-pago-fin").html(html_pago_fin);

					$("#container-conceptos-pago").fadeOut('fast', function(){
						$("#container-form-procesar-pago").fadeIn('fast');
						//css("display", "block");
					})//css("display", "none");
					$("#form-token-tarjeta").append($("<input type='hidden' id='descripcionpago' name='descripcionpago'>").val(pago.descripcion));
					var redondeado = Math.round(montoparcial * 100) / 100
					$("#form-token-tarjeta").append($("<input type='hidden' id='totalapagar' name='totalapagar'>").val(redondeado));
					$("#form-token-tarjeta").append($("<input type='hidden' id='id_concepto' name='id_concepto'>").val(id_concepto));

					id_proms = pago.promociones.reduce( (acc, it) => { acc.push(it.idPromocion); return acc;}, [])
					$("#form-token-tarjeta").append($("<input type='hidden' id='ids_promociones' name='ids_promociones'>").val(id_proms.join(",")));
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
function procesar_pago_multi(id_concepto, costo, retardo, num_p, descript, concept, parcialidad, promocion,tipomoneda){
	reset_formulario_toker();
	var montoparcial = $("#montoapagar").length > 0 ? $("#montoapagar").val().replace(/\$|,/g, "") : (parseFloat(costo) + parseFloat(retardo));
	if(parseFloat(montoparcial) < 15 && $("#montoapagar").length > 0){
		swal('', 'El monto para generar fichas de pago debe ser superior a los $15.00 MXN', 'info');
		return;
	}
	$("#check_domiciliar_pago").hide();
	descripcionpago = ``
	if(retardo > 0){
		descripcionpago = `<h3 class="text-warning">Pago fuera de tiempo: <u id="monto_con_recargo">${moneyFormat.format(parseFloat(costo)+ parseFloat(retardo))}</u></h3>`
	}
	if(promocion > 0){
		descripcionpago = `<p class="text-success"><u>Monto con promoción.</u></p>`+descripcionpago
	}
	show_cost = costo;
	if(parcialidad == 1){
		if($("#montoapagar").length > 0){
			show_cost = $("#montoapagar").val();
		}else{
			show_cost = moneyFormat.format(parseFloat(costo) + parseFloat(retardo));
		}
	}else{
		show_cost = typeof(show_cost) === "number" ? moneyFormat.format(show_cost) : show_cost;
	}
	html_pago_fin = `<div class="card-header bg-primary text-light">
						${concept} <i class="float-right">${(num_p != null?"Pago número: "+num_p : '')}</i>
					</div>
					<div class="card-body">
						<h2 id="monto_a_pagar" class="card-title text-success">${(show_cost)}${tipomoneda}</h2>
						${descripcionpago}
						<input type="hidden" id="tipo_pago" name="tipo_pago" value="${id_concepto}">
						<p id="nombre_concepto" class="card-text">${descript}</p>
					</div>
					<div class="card-footer bg-primary text-light">
						<div class="row">
							<div class="col">
								<img class="float-right ml-1" width="50px" src="../img/visa_icon.png">
								<img class="float-right ml-1" width="50px" src="../img/mastercard_icon.png">
								<img class="float-right ml-1" width="50px" src="../img/american_icon.png">
							</div>
						</div>
					</div>`
	
	var tipo_concepto_mensualidad_separar=concept;
	var tipo_concepto_mensualidad = tipo_concepto_mensualidad_separar.split("-");
	if(tipo_concepto_mensualidad[0] == "Mensualidad "){
		$("#check_domiciliar_pago").show();
	}

	$("#container-pago-fin").html(html_pago_fin);

	$("#container-conceptos-pago").fadeOut('fast', function(){
		$("#container-form-procesar-pago").fadeIn('fast');
	});

	
	$("#form-token-tarjeta")[0].reset();
	
	$("#form-token-tarjeta").append($("<input type='hidden' id='descripcionpago' name='descripcionpago'>").val(descript));
	if(parcialidad == 1){
		$("#form-token-tarjeta").append($("<input type='hidden' id='totalapagar' name='totalapagar' >"));
		if($("#montoapagar").length > 0){
			$("#totalapagar").val($("#montoapagar").val());
		}else{
			$("#totalapagar").val(parseFloat(costo) + parseFloat(retardo));
		}
	}
	$("#form-token-tarjeta").append($("<input type='hidden' id='id_concepto' name='id_concepto'>").val(id_concepto));
}

function pagar_otra_cantidad(id_concepto, monto){
	// $("#container-form-procesar-pago").fadeOut('fast', function(){
	// 	$("#container-conceptos-pago").fadeIn('fast');
	// })

	// $("#montoapagar").prop("type", "tel");
	// $(".moneyFt").maskMoney();
	// if($(".alert.alert-danger").length > 0){
	// 	$(".moneyFt").maskMoney('destroy');
	// 	$("#montoapagar").prop('readonly', true);
	// }
	$("#content_pagar_otra_cantidad").html(`
	<input class="form-control moneyFt" data-prefix="$ " value="${monto}" placeholder="Ingrese el monto a pagar" id="montoapagar">
	`)
	$(".moneyFt").maskMoney();
}

$("#btn-cancel-pay").click(function(){
	$("#container-form-procesar-pago").fadeOut('fast', function(){
		reset_formulario_toker();
		$('#mostrar_pago_tarjeta').trigger( "click" );
		$("#container-conceptos-pago").fadeIn('fast');
		$("#mostrar_ficha_pago_ventanilla").hide();
		$("#mostrar_ficha_pago_spei").hide();
		// $("#foto_ficha_oxxo").html('');
		//css("display", "block");
	});//css("display", "none");
})
//scripts de pago conekta crear tokein_id para enviar aal servidor
$('input.cc-num').payment('formatCardNumber').on("keyup change", function(){
	var type = $.payment.cardType( $(this).val() );
	if(type == "visa"){
		$("#type_card_img").attr('src','../img/visa_icon.png')
		$(".company").html("VISA");
		$(".card_cv").attr("data-type", "visa");
	} else if(type == "mastercard"){
		$("#type_card_img").attr('src','../img/mastercard_icon.png')
		$(".company").html("MASTERCARD");
		$(".card_cv").attr("data-type", "mastercard");
	}
	else if(type == "amex"){
		$("#type_card_img").attr('src','../img/american_icon.png')
		$(".company").html("AMERICAN EXPRESS");
		$(".card_cv").attr("data-type", "amex");
	}
	else{
		$("#type_card_img").attr('src','')
		$(".card_cv").attr("data-type", "desconocido");
		$(".company").html("TIPO DE TARJETA");
	}
});
$('input.cc-exp').payment('formatCardExpiry');
$('input.cc-cvc').payment('formatCardCVC');
$(".cvcbtn").click(function(){
	$(".card_cv").toggleClass("flip");
	$(".cvcbtn").toggleClass("flip");
	if($(".cvcbtn").hasClass("flip")){
		$(".cvcbtn").html("INSERTAR DATOS DE LA TARJETA");
	}else{
		$(".cvcbtn").html("INSERTAR CVC");
	}
});
$("#delegacion_interesado").on("change", function(){
	$("#ciudadfactura").val($(this).val());
});
$("#email").keyup(function(){
	$("#emailfactura").val($(this).val());
});



$("button[name='validartarjeta']").on("click",function(event) {
	event.preventDefault();
	$.ajax({
		url: "../../assets/data/Controller/planpagos/pagosControl.php",
		type: "POST",
		data: {action:'obtenercuentadepago', id_concepto:$('#tipo_pago').val()},
		beforeSend : function(){
			
		},
		success: function(data){
			data=JSON.parse(data);
			console.log(data.api_key_publica);
			Conekta.setPublicKey(data.api_key_publica);
			Conekta.setLanguage("es")
			try{
				if($("#telefonofactura").val().trim() == "" || $("#telefonofactura").val().trim().length < 10){
					swal($("#telefonofactura").val().trim() == '' ? "El teléfono celular es requerido" : "El numero de telefono debe contener al menos 10 caracteres");
				}else{
					var $form = $("#form-token-tarjeta");
					var mes_anio = $(".cc-exp").val().split('/');
					if(mes_anio[0]){
						var mes = mes_anio[0].trim();
						mes = mes.replace(" ", "");
					}
					if(mes_anio[1]){
						var anio = mes_anio[1].trim();
						anio = anio.replace(" ", "");
					}
					$("#mes").val(mes);
					$("#anio").val(anio);
					
					// Previene hacer submit más de una vez
					$form.find("button[name='validartarjeta']").prop("disabled", true);
					
					Conekta.Token.create($form, conektaSuccessResponseHandler, conektaErrorResponseHandler); //v5+
					return false;
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
});

var conektaSuccessResponseHandler = function(token) {
	//console.log(token);
	var $form = $("#form-token-tarjeta");
	referencia = $("#referencia").val();
	/* Inserta el token_id en la forma para que se envíe al servidor html_pago_fin */
	$form.append($("<input type='hidden' id='id_token' name='conektaTokenId'>").val(token.id));//token de la tarjeta del alumno
	$form.append($("<input type='hidden' id='conceptopago' name='conceptopago'>").val(html_pago_fin));
	$valor = html_pago_fin;
	
	Planes($valor, $form);
	reset_formulario_toker()
	consultar_historial_pago()
};
var conektaErrorResponseHandler = function(response) {
	var $form = $("#form-token-tarjeta");

	/* Muestra los errores en la forma */
	swal({
		title: 'ERROR',
		text: response.message_to_purchaser,
		icon: "info",
	  }).then((value)=>{
		window.location.reload();
	})
	//$form.find(".card-errors").text(response.message_to_purchaser);
	$form.find("button[name='validartarjeta']").prop("disabled", false);
	reset_formulario_toker()
	consultar_historial_pago()
};

function reset_formulario_toker(){
	$("#form-token-tarjeta")[0].reset();
	$('#descripcionpago').remove();
	$('#descripcionpago').remove();
	$('#totalapagar').remove();
	$('#id_concepto').remove();
	$('#ids_promociones').remove();
	$('#id_token').remove();
	$('#conceptopago').remove();
	$('#monto_pago_banorte').html("");
	$('#referencia_pago_banorte').html("");
	$('#concepto_de_pago_banorte').html("");
	$('#nombre_alumno_banorte').html("");
}
function Planes(valor, formulario){

	$.ajax({
		type:"POST",
		url:"../../assets/data/Controller/planpagos/pagosControl.php",
		data:{
			action:'realizar_pago',
			token:$("#id_token").val(),
			conceptopago:$("#conceptopago").val(),
			nombretarjeta:$("#nombretarjeta").val(),
			email:$("#email").val(),
			telefonofactura:$("#telefonofactura").val(),
			precio:$("#precio").val(),
			totalapagar:$("#totalapagar").val(),
			promociones:($("#ids_promociones").length > 0) ? $("#ids_promociones").val() : null,
			descripcionpago:$("#descripcionpago").val(),
			id_concepto:$("#id_concepto").val(),
			domiciliar_tarjeta:$('#domiciliar_pago').is(":checked") ? 1 : 0
		},
		success:function(msj){
			if(msj==1){
				swal({
					title: 'Pago realizado con éxito',
					text: 'Tu pago se procesó exitosamente, no necesitas reportarlo',
					icon: "success",
				  }).then((value)=>{
					  window.location.reload();
				  })
			}else{
				swal({
					title: 'Ocurrio un error al procesar el pago',
					text: msj,
					icon: "info",
				  }).then((value)=>{
					  window.location.reload();
				  })				
			}
			formulario.find("button[name='validartarjeta']").prop("disabled", false);
		}
	});

}

function consultar_porcentaje_recargo(){
	$.ajax({
		url: '../../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'consultar_porcentaje_recargo'},
		success: function(data){
			porcent_recargo = parseFloat(data);
		}
	});
}

function consultar_historial_pago(){
	$.ajax({
		url: '../../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'consultar_historial_pago'},
		success: function(data){
			try{
				var aplicados = JSON.parse(data);
				$("#span_saldo").html(``);
				$("#table_pagos_apli").DataTable().clear();
				for (i = 0; i < aplicados.length; i++) {
					if(aplicados[i].estatus == 'verificado'){
						if(aplicados[i].hasOwnProperty('saldo_favor')){
							if(aplicados[i].saldo_favor > 0){
								$("#span_saldo").html(`<div class="alert alert-info" role="alert">
								Usted cuenta con un saldo a favor por <strong class="d-block d-sm-inline-block-force">${moneyFormat.format(aplicados[i].saldo_favor)}</strong> pesos.
							  </div>`);
							}else{
								$("#span_saldo").html(``);
							}
						}else{
							var col_monto_pagar = 0;
							if(aplicados[i].idPromocion != null){
								// monto por pagar menos promocion
								col_monto_pagar = parseFloat(aplicados[i].precio_orig) - (parseFloat(aplicados[i].precio_orig) * (parseFloat(aplicados[i].promocion_info.porcentaje) / 100));
								// // monto por pagar menos aplicados
							}else{
								col_monto_pagar = parseFloat(aplicados[i].precio_orig)
								if(parseInt(aplicados[i].concepto_parcialidades) == 1){
									col_monto_pagar = Math.abs(parseFloat(aplicados[i].costototal) - (parseFloat(aplicados[i].costototal) + parseFloat(aplicados[i].restante) + parseFloat(aplicados[i].montopagado)));
								}
							}
							if(aplicados[i].cargo_retardo > 0){
								// col_monto_pagar = col_monto_pagar + parseFloat(aplicados[i].cargo_retardo);
							}
							var total_a_pagar = ((parseFloat(aplicados[i].montopagado) > parseFloat(aplicados[i].costototal))?parseFloat(aplicados[i].costototal) : parseFloat(aplicados[i].montopagado)) + ( (aplicados[i].restante > 0.5) ? parseFloat(aplicados[i].restante) : 0 ) + parseFloat(aplicados[i].saldo);
							aplicados[i].restante = parseFloat(aplicados[i].restante);
							aplicados[i].saldo = parseFloat(aplicados[i].saldo);
							var real_restante = (aplicados[i].restante > 0.5) ? aplicados[i].restante : 0;
							$("#table_pagos_apli").DataTable().row.add([
								aplicados[i].numOrder,
								`<span title="${aplicados[i].fechapago}">${aplicados[i].fechapago.substr(0,10)}</span>`,
								aplicados[i].concepto_nombre + (parseInt(aplicados[i].concepto_numero_pagos) > 1 ? ` <span>[<b>Mensualidad ${parseInt(aplicados[i].numero_de_pago)}</b> ${aplicados[i].restante > 0 ? ' <small>pago parcial</small>':''}]`:''),
								aplicados[i].detalle_pago.id,
								moneyFormat.format(aplicados[i].precio_orig)+ aplicados[i].tipomoneda,
								(aplicados[i].idPromocion != null) ? `<span class="text-success">- ${parseFloat(aplicados[i].promocion_info.porcentaje).toFixed(2)} %</span>` : "-",
								moneyFormat.format(col_monto_pagar) + aplicados[i].tipomoneda,
								moneyFormat.format(parseFloat(aplicados[i].saldo) + parseFloat(aplicados[i].cargo_retardo)),
								moneyFormat.format(total_a_pagar + parseFloat(aplicados[i].cargo_retardo)) + aplicados[i].tipomoneda,
								`<span class="d-block font-weight-bold">${moneyFormat.format(parseFloat(aplicados[i].montopagado) + parseFloat(aplicados[i].cargo_retardo))} ${aplicados[i].tipomoneda}</span>`,
								`
								<b>Concepto: </b> ${moneyFormat.format(real_restante)+' '+aplicados[i].tipomoneda} <br> 
								<b>Recargo: </b> ${moneyFormat.format(aplicados[i].saldo)+' '+aplicados[i].tipomoneda}
								${(parseFloat(aplicados[i].restante) < -0.5)? "<br><b>Saldo a favor: </b><span class='text-success'>"+moneyFormat.format(Math.abs(aplicados[i].restante))+"</span>":""}
								`,
								((aplicados[i].numero_de_pago == aplicados[i].max_aplicado) || (parseInt(aplicados[i].concepto_numero_pagos) == 1 && (aplicados[i].restante > 0 || aplicados[i].saldo > 0)) ) ? `<button class="btn btn-info btn-sm" onclick="ira_pagos(${aplicados[i].id_concepto}, ${aplicados[i].carrera_id},'${aplicados[i].tipomoneda}')">Pagar</button>` : ''
							]);
						}
					}else if(aplicados[i].estatus == 'rechazado'){
						if(aplicados[i].detalle_pago.id == 'Alumno'){
							$("#table_pagos_apli").DataTable().row.add([
								``,
								`<span title="${aplicados[i].fechapago}" class="text-danger">${aplicados[i].fechapago.substr(0,10)}</span>`,
								'<span class="text-danger"><b>PAGO RECHAZADO</b> '+(aplicados[i].hasOwnProperty('numero_pago_actual') ? `<span title="Pago correspondiente a la mensualidad numero ${parseInt(aplicados[i].numero_pago_actual)+1}">[${parseInt(aplicados[i].numero_pago_actual)+1}] `:'')+aplicados[i].concepto_nombre+` <br>${(aplicados[i].comentario != null && aplicados[i].detalle_pago.id == 'Alumno') ? '<b>Comentario: <i>'+aplicados[i].comentario+'</i></b>' : ''}</span>`,
								`<span class="text-danger">${aplicados[i].detalle_pago.id}</div>`,
								`<span class="text-danger">${moneyFormat.format(aplicados[i].precio_orig)} ${aplicados[i].tipomoneda}</div>`,
								"-",
								"-",
								`<span class="text-danger">-</div>`,
								`-`,
								'-',
								'-',
								''
							]);
						}
					}
				}
				$("#table_pagos_apli").DataTable().draw();
			}catch(e){
				console.log(e);
				console.log(data)
			}

		}
	});
}
function ira_pagos(con, carr, tipomoneda){
	if(carr){
		$("#select_inscripciones").val(carr)
	}else{
		carr = $("#select_inscripciones option")[1].value;
		$("#select_inscripciones").val($("#select_inscripciones option")[1].value)
	}
	$("#select_inscripciones").change()
	call_b = ()=>{
		ver_pagos('-',con, carr,'-','-','-',tipomoneda)
		$("a[href='#posts']").click()
		window.scrollTo(0,document.body.scrollHeight);
	}
}


$("#metododepago1").on('change', function(e){
	var tipoPago = $("#metododepago1").val();
	var aviable = [1, 2, 4, 6];
	if(aviable.includes(parseInt(tipoPago))){
		$("#informacionPago").show();
	}else{
		$("#informacionPago").hide();
	}
})

$("#informacionPago").on('click', function(e){
	var tipoPago = $("#metododepago1").val();
	if(tipoPago.trim() != ''){
			var img = document.createElement('img');
            img.src = '../../assets/images/ejemploComprobantes/'+tipoPago+'.png';
			img.className = 'img_apoyo';
			img.className = 'w-100';
			swal({
				title:'Apoyo visual',
				content:img,
				className:'fullscreen'
			})
		/* switch(tipoPago){
			case '1':
				//console.log(1)
				$("#verEjemploComprobante").attr('src','../../assets/images/ejemploComprobantes/'+tipoPago+'.png');
                $("#verEjemploComprobante").show();
				$("#modalVerEjemploPago").modal('show');
				break;
			case '2':
				//console.log(3)
				$("#verEjemploComprobante").attr('src','../../assets/images/ejemploComprobantes/'+tipoPago+'.png');
                $("#verEjemploComprobante").show();
				$("#modalVerEjemploPago").modal('show');
				break;
			case '4':
				//console.log(3)
				$("#verEjemploComprobante").attr('src','../../assets/images/ejemploComprobantes/'+tipoPago+'.png');
                $("#verEjemploComprobante").show();
				$("#modalVerEjemploPago").modal('show');
				break;
			case '6':
				//console.log(2)
				$("#verEjemploComprobante").attr('src','../../assets/images/ejemploComprobantes/'+tipoPago+'.png');
                $("#verEjemploComprobante").show();
				$("#modalVerEjemploPago").modal('show');
				break;
			default:
				swal('Sin ejemplo');
				break;
		} */
	}else{
		swal('Seleccione primero el método de pago');
	}
})

$("#cancelarEjemploCompronate").on('click',function(){
	$("#modalVerEjemploPago").modal('hide');
})

$('#estado_de_cuenta').click(function (e) {
    tGeneraciones = $("#table_pagos_total_carreras").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            /*extend:"copy",
            className: "btn-success"
        },{
            extend: "csv"
        }, {*/
            extend: "excel",
            className: "btn-primary"
        /*}, {
            extend: "pdf"
        }, {
            extend: "print"*/
        }],
        "ajax": {
            url: '../../assets/data/Controller/planpagos/pagosControl.php',
            type: 'POST',
            data: {action: 'obtener_totales_carrera'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);	
                if(e.responseText == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
            }
        },
        'language':{
            'sLengthMenu': 'Mostrar _MENU_ registros',
            'sInfo': 'Mostrando registro del _START_ al _END_ de un total de _TOTAL_ registros',
            'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros',
            'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
            'sSearch': 'Buscar:',
            'sLoadingRecords': 'Cargando',
            'oPaginate':{
                'sFirst': 'Primero',
                'sLast': 'Último',
                'sNext': 'Siguiente',
                'sPrevious': 'Anterior'
            },
            buttons: {
                copyTitle: 'Tabla Copiada de manera exitósa',
                copySuccess: {
                    _: 'Se copio %d filas',
                    1: 'Se copio1 fila'
                }
            }
        },
        'bDestroy': true,
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ]
    });
    
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
