//Kikicaro82
const regCorreo = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
let list_ejecutivas = [];
$(document).ready(function(){
	listar_ejecutivas();
	cargar_instituciones();
})
intentos = 0;
function listar_ejecutivas(){
	// if((list_carreras.length > 0 && list_eventos.length > 0) || intentos == 5){
		
		$.ajax({
			url: "../assets/data/Controller/marketing/marketingControl.php",
			type: "POST",
			data: {action:'consultar_todo_ejecutivas'},
			// contentType: false,
			// processData:false,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				try{
					f_refer = new Date();
					ejecutivas = JSON.parse(data)
					options = "";
					$("#tabla_ejecutivas").DataTable().clear();
					list_ejecutivas = [];
					for (i = 0; i < ejecutivas.length; i++) {
						list_ejecutivas.push(ejecutivas[i]);
						options+= `<option value="${ejecutivas[i].idPersona}">${ejecutivas[i].apellidoPaterno} ${ejecutivas[i].nombres}</option>`;
						
						$("#tabla_ejecutivas").DataTable().row.add([
							`<a href="javascript:void(0)" onclick="prospectos_tabla('${ejecutivas[i].nombres}')">${ejecutivas[i].apellidoPaterno} ${ejecutivas[i].apellidoMaterno} ${ejecutivas[i].nombres}</a>`,
							// ejecutivas[i].correo,
							((ejecutivas[i].prospectos_carreras.length<10)?'0'+ejecutivas[i].prospectos_carreras.length:ejecutivas[i].prospectos_carreras.length)+" para carreras. "+((ejecutivas[i].prospectos_eventos.length<10)?'0'+ejecutivas[i].prospectos_eventos.length:ejecutivas[i].prospectos_eventos.length)+" para eventos.",
							((f_refer.toISOString().substr(0, 10) == ejecutivas[i].sesion.substr(0,10)) ? 'En sesión hoy' : '-')
						]);
						
					}

					$("#tabla_ejecutivas").DataTable().draw();
					$("#tabla_ejecutivas").DataTable().columns.adjust();

					$("#n_prosp_personaMk").html(options)
					$("#change_ejecutiva").html(options)

					tabla_prospectos();
				}catch(e){
					console.log(e.message);
					console.log(data);
				}
			},
			error: function(){
			},
			complete: function(){
				$(".outerDiv_S").css("display", "none")
			}
		});
	// }else{
	// 	setTimeout(function(){
	// 		listar_ejecutivas()
	// 		intentos++;
	// 	},300)
	// }
}

function cargar_instituciones(){
	$.ajax({
		url: "../assets/data/Controller/instituciones/institucionesControl.php",
		type: "POST",
		data: {action:'lista_instituciones'},
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				instit = JSON.parse(data);
				html_opt = "<option value='0' selected>Si pertenece a una asociación, elijala</option>";
				if(instit.estatus == 'ok'){
					for (i = 0; i < instit.data.length; i++) {
						if(instit.data[i].fundacion == '1'){
							html_opt+=`<option value="${instit.data[i].id_institucion}">${instit.data[i].nombre}</option>`
						}
					}
				}
				$("#IDOrganizacion").html(html_opt);
				$("#edit_pr_institucion").html(html_opt);
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

function prospectos_tabla(ejecutiva){
	$("#tabla_prospectos_filter input").val(ejecutiva)
	$("#tabla_prospectos_filter input").keyup()
	$("#potospectos-tab").click()
}

/*function listar_prospectos(id_ejecutiva){
	etv = list_ejecutivas.find(elm => elm.idPersona == id_ejecutiva);
	$("#tabla_prospectos_ejecutivas").DataTable().clear();
	for (i = 0; i < etv.prospectos_eventos.length; i++) {
		$("#tabla_prospectos_ejecutivas").DataTable().row.add([etv.prospectos_eventos[i].tipo_atencion, etv.prospectos_eventos[i].aPaterno+" "+etv.prospectos_eventos[i].aMaterno+" "+etv.prospectos_eventos[i].nombre]);
	}
	for (i = 0; i < etv.prospectos_carreras.length; i++) {
		$("#tabla_prospectos_ejecutivas").DataTable().row.add([etv.prospectos_carreras[i].tipo_atencion, etv.prospectos_carreras[i].apellidoPaterno+" "+etv.prospectos_carreras[i].apellidoMaterno+" "+etv.prospectos_carreras[i].nombre]);
	}
	$("#tabla_prospectos_ejecutivas").DataTable().draw();
	$("#tabla_prospectos_ejecutivas").DataTable().columns.adjust();
}*/

$("#form_nuevo_prospecto").on('submit', function(e){
	e.preventDefault();
	if(!regCorreo.test(String($("#email").val()).toLowerCase())){
		alert('el correo no tiene un formato correcto')
	}else{
		fData = new FormData(this);
		fData.append('action', 'registrar_prospecto');
		tipo_pros = $("#tipo_prospecto option:selected").val();
		persona_mk = $("#n_prosp_personaMk option:selected").val();

		interes = $("#id_destino").val();
		
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
					insertar = JSON.parse(data);
					// console.log(insertar)
					if(insertar.estatus == 'ok'){
						// if(insertar.info == 'correo_no_enviado'){
						// 	swal({
						// 		title: 'Registro de prospecto exitoso',
						// 		text: 'sin embargo ocurrió un error al enviar correo a la persona registrada, contactelo',
						// 		icon: 'info'
						// 	})
						// }else{
						// 	swal({
						// 		title: 'Registro de prospecto exitoso',
						// 		text: '',
						// 		icon: 'success'
						// 	})
						// }
						asignar_prospecto_ejecutiva(insertar.list_val, tipo_pros, persona_mk, insertar.info, interes)
					}else{
						mensaje = '';
						if(insertar.info == 'membresia_existente'){
							mensaje = 'La persona ya cuenta con una membresía a CONACON.'
						}else if(insertar.info == 'limite_cubierto'){
							mensaje = 'El limite de vacantes para este evento ha sido cubierto.'
						}else if(insertar.info == 'correo_existente'){
							mensaje = 'La persona ya está registrada para este evento.'
						}else if(insertar.info == 'telefono_no_valido'){
							mensaje = 'El numero de telefono introducido no es valido, verifique.'
						}
						swal({
								//title: 'Fallo al registrar al prospecto',
								text: mensaje,
								icon: 'info'
							})
					}
					listar_ejecutivas()
					$("#v-profile-tab").click();
					$("#form_nuevo_prospecto")[0].reset()
					$("#tipo_alumno").attr('disabled', true);
					$("#tipo_alumno").html('');
					$("#tipo_alumno").parent().fadeOut('fast');
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

function asignar_prospecto_ejecutiva(id_prospecto, tipo, persona_mk, info_anterior, interes){
	
	fData = {action:'asignar_prospecto', prospecto:id_prospecto, n_prosp_tipo:tipo, n_prosp_personaMk:persona_mk, interes : interes};
	
	$.ajax({
		url: "../assets/data/Controller/marketing/marketingControl.php",
		type: "POST",
		data: fData,
		// contentType: false,
		// processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				asignar = JSON.parse(data);
				if(insertar.estatus == 'ok'){
					if(info_anterior == 'correo_no_enviado'){
						swal({
							title: 'Registro de prospecto exitoso',
							text: 'sin embargo ocurrió un error al enviar correo a la persona registrada, contactelo',
							icon: 'info'
						})
					}else if(info_anterior == 'registrado_como_alumno'){
						swal({
							title: 'Registro exitoso',
							text: 'El alumno deberá recibir un correo con el enlace a su plataforma y sus accesos.',
							icon: 'success'
						})
					}else{
						swal({
							title: 'Asignación a ejecutivo exitoso',
							text: '',
							icon: 'success'
						})
					}
					/*if(insertar.info == 'correo_no_enviado'){
					}else{
						swal({
							title: 'Registro y asignación exitosa',
							icon: 'success'
						})
					}*/
				}else{
					swal({
						title: 'Ocurrió un error al intentar asignar el prospecto a una ejecutiva',
						text: '',
						icon: 'info'
					})
					/*if(insertar.info == 'registro_ok-asignacion_no_ok'){
						swal({
							text:'La persona ha sido registrada exitosamente, sin embargo ha ocurrido un problema al asignarla a la ejecutiva seleccionada',
							icon: 'info'
						})	
					}else{
						swal({
							text:'Ha ocurrido un error al registrar a la persona, verifique',
							icon: 'info'
						})	
					}*/
				}
				listar_ejecutivas()
				$("#v-profile-tab").click();
				// $("#form_nuevo_prospecto")[0].reset()
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

$("#tipo_prospecto").on('change', function(){
	tipoSelect = $("#tipo_prospecto :selected").val();
	str_options = '';
	$("#tipo_alumno").attr('disabled', true);
	$("#tipo_alumno").html('');
	$("#tipo_alumno").parent().fadeOut('fast');
	if(tipoSelect == 'evento'){
		str_options = list_eventos.reduce((acc, it) => {
		  acc+=`<option value="${it.idEvento}">${it.titulo}</option>"`;
		    return acc;
		}, '');
	}
	if(tipoSelect == 'carrera'){
		str_options = list_carreras.reduce((acc, it) => {
		  if(it.idCarrera != 3){
		  		acc+=`<option value="${it.idCarrera}">${it.nombre}</option>"`;
			}
		    return acc;
		}, '');
	}
	$("#id_destino").html("'"+str_options+"'");
});

$("#id_destino").on('change', function(){
	if($("#id_destino").val() == 35){
		$("#tipo_alumno").attr('disabled', false);
		$.ajax({
			url: '../assets/data/Controller/instituciones/institucionesControl.php',
			type: "POST",
			data: {action:'lista_instituciones'},
			beforeSend : function(){
				$("#tipo_alumno").html('<option value="">Cargando...</option>')
			},
			success: function(data){
				try{
					resp = JSON.parse(data);
					var option_html = '<option value="" disabled="" selected>Seleccione una opción</option>';
					if(resp.estatus == 'ok'){
						for(ins in resp.data){
							if(resp.data[ins].estatus == 1 && resp.data[ins].fundacion == 0){
								option_html += `<option value="${resp.data[ins].id_institucion}">${resp.data[ins].nombre}</option>`;
							}
						}
					}
					$("#tipo_alumno").html(option_html);
				}catch(e){
					console.log(e);
					console.log(data);
				}
			}
		});
		$("#tipo_alumno").parent().fadeIn('fast');
	}else{
		$("#tipo_alumno").attr('disabled', true);
		$("#tipo_alumno").html('');
		$("#tipo_alumno").parent().fadeOut('fast');
	}
});

function tabla_prospectos(){
	$("#tabla_prospectos").DataTable().clear();
	$("#tabla_inscritos").DataTable().clear();
	
	conteo_prospectos = 0;
	conteo_inscritos = 0;
	for (i = 0; i < list_ejecutivas.length; i++) {
		conteo_prospectos+=list_ejecutivas[i].prospectos_carreras.length;
		conteo_prospectos+=list_ejecutivas[i].prospectos_eventos.length;
		for (j = 0; j < list_ejecutivas[i].prospectos_carreras.length; j++) {
			prospecto = list_ejecutivas[i].prospectos_carreras[j];
			 // console.log(prospecto)
			// carrera_f = list_carreras.find(elm => elm.idCarrera == prospecto.idCarrera);
			
			// seguim_c = ((prospecto.seguimiento.length > 0 )? `<a href="javascript:void(0)" onclick="seguimiento()"><i class="fas fa-file-alt"></i></a>   ` : '-');
			$("#tabla_prospectos").DataTable().row.add([
				`<span class="${(parseInt(list_ejecutivas[i].idPersona) == parseInt(usrInfo.idPersona)) ? 'prosp_propio' :''}" title="${prospecto.idAsistente}">${prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre}</span>`,
				prospecto.correo,
				prospecto.telefono,
				prospecto.fe_reg.substring(0,16),
				`carrera - ${prospecto.titulo_c}`,
				list_ejecutivas[i].nombres+' '+list_ejecutivas[i].apellidoPaterno,
				`<button class="btn btn-secondary" onclick="seguimiento('carrera',${list_ejecutivas[i].idPersona}, ${prospecto.idReg},${prospecto.idReg}, '${prospecto.nombre}',${list_ejecutivas[i].idPersona})"><i class="fas fa-users-cog"></i></button>`
			]);
			if(prospecto.pagos.length > 0){
				conteo_inscritos++;
				$("#tabla_inscritos").DataTable().row.add([
					prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre,
					prospecto.correo,
					prospecto.telefono,
					prospecto.fe_reg.substring(0,16),
					`carrera - ${prospecto.titulo_c}`,
					list_ejecutivas[i].nombres+' '+list_ejecutivas[i].apellidoPaterno,
					`<button class="btn btn-secondary" onclick="seguimiento('carrera',${list_ejecutivas[i].idPersona}, ${prospecto.idReg},${prospecto.idReg}, '${prospecto.nombre}',${list_ejecutivas[i].idPersona})"><i class="fas fa-users-cog"></i></button>`
				]);
			}
		}

		for (h = 0; h < list_ejecutivas[i].prospectos_eventos.length; h++) {
			prospecto = list_ejecutivas[i].prospectos_eventos[h];
			
			evento_f = list_eventos.find(elm => elm.idEvento == prospecto.idEvento);
			
			// seguim_e = ((prospecto.seguimiento.length > 0 )? `<a href="javascript:void(0)" onclick="seguimiento()"><i class="fas fa-file-alt"></i></a>   ` : '-');
			$("#tabla_prospectos").DataTable().row.add([
				`<span class="${(parseInt(list_ejecutivas[i].idPersona) == parseInt(usrInfo.idPersona)) ? 'prosp_propio' :''}" title="${prospecto.idAsistente}">${prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre}</span>`,
				prospecto.correo,
				prospecto.telefono,
				prospecto.fe_reg.substring(0,16),
				`evento - ${prospecto.titulo_e}`,
				list_ejecutivas[i].nombres+' '+list_ejecutivas[i].apellidoPaterno,
				`<button class="btn btn-secondary" onclick="seguimiento('evento',${list_ejecutivas[i].idPersona}, ${prospecto.idReg},${prospecto.idReg}, '${prospecto.nombre}',${list_ejecutivas[i].idPersona})"><i class="fas fa-users-cog"></i></button>`
			]);
			if(prospecto.pagos.length > 0){
				conteo_inscritos++;
				
				$("#tabla_inscritos").DataTable().row.add([
					prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre,
					prospecto.correo,
					prospecto.telefono,
					prospecto.fe_reg.substring(0,16),
					`evento - ${prospecto.titulo_e}`,
					list_ejecutivas[i].nombres+' '+list_ejecutivas[i].apellidoPaterno,
					`<button class="btn btn-secondary" onclick="seguimiento('evento',${list_ejecutivas[i].idPersona}, ${prospecto.idReg},${prospecto.idReg}, '${prospecto.nombre}',${list_ejecutivas[i].idPersona})"><i class="fas fa-users-cog"></i></button>`
				]);
			}
		}

		
	}

	$("#conteo_prospectos").html(conteo_prospectos);
 	$("#conteo_inscritos").html(conteo_inscritos);

	$("#tabla_prospectos").DataTable().draw();
	$("#tabla_prospectos").DataTable().columns.adjust();

	$("#tabla_inscritos").DataTable().draw();
	$("#tabla_inscritos").DataTable().columns.adjust();

	$("#tabla_prospectos").DataTable().rows().every( function ( rowIdx, tableLoop, rowLoop ) {
        var data = this.data();       
        if(data[0].includes('prosp_propio')){
			$($("#tabla_prospectos").DataTable().row(rowIdx).node()).addClass("bg-warning")
			$($("#tabla_prospectos").DataTable().row(rowIdx).node()).addClass("text-dark")
        }
    });
}

$("#form_cambio_prospecto").on('submit', function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append('action', 'reasignar_prospecto');
	
	$.ajax({
		url: "../assets/data/Controller/marketing/marketingControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$(".outerDiv_S").css("display", "block")
		},
		success: function(data){
			try{
				reasign = JSON.parse(data)
				if(reasign.estatus == 'ok'){
					swal({
						icon:'success',
						title:'Reasignación exitosa'
					})
				}else{
					swal({
						icon:'info',
						text:'Ha ocurrido algo al reasignar, notifique al administrador.'
					})
				}
				init_data();
				listar_ejecutivas();
				$("#modal_seguimiento_gral").modal('hide');
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

function seguimiento(tipo, ejecutiva, reg,relac, nombre, mkt){
	prosp_f = false;
	ejecutiva = list_ejecutivas.find(elm => elm.idPersona == ejecutiva)
	
	if(tipo == 'carrera'){
		prosp_f = ejecutiva.prospectos_carreras.find(elm => elm.idReg == reg)
	}else{
		prosp_f = ejecutiva.prospectos_eventos.find(elm => elm.idReg == reg)
	}
	seguimientos_pros = prosp_f.seguimiento
	$("#tabla_comentarios").DataTable().clear();

	$("#edit_pr_nombre").val(prosp_f.nombre)
	$("#edit_pr_apaterno").val(prosp_f.aPaterno)
	$("#edit_pr_amaterno").val(prosp_f.aMaterno)
	$("#edit_pr_telefono").val(prosp_f.telefono)
	$("#edit_pr_correo").val(prosp_f.correo)
	$("#inp_prospect_edit").val(prosp_f.idAsistente)
	
	$("#edit_pr_institucion").val(prosp_f.idAsociacion)

	for (i = 0; i < seguimientos_pros.length; i++) {
		$("#tabla_comentarios").DataTable().row.add([
			seguimientos_pros[i].fecha,
			seguimientos_pros[i].detalles
			]);
	}

	$("#tabla_pagos_admin").DataTable().clear();
	cont_pago = 0;
	code_curr = ""
	for (i = 0; i < prosp_f.pagos.length; i++) {
		detalle_p = prosp_f.pagos[i].detalle_pago
		cont_pago+=parseFloat(detalle_p.purchase_units[0].amount.value)
		code_curr = detalle_p.purchase_units[0].amount.currency_code
		$("#tabla_pagos_admin").DataTable().row.add([
			detalle_p.create_time.substr(0,10),
			prosp_f.pagos[i].plan_pago,
			moneyFormat.format(detalle_p.purchase_units[0].amount.value)+" "+detalle_p.purchase_units[0].amount.currency_code,
			((prosp_f.pagos[i].comprobante != '')? `<a href="../assets/files/comprobantes_pago/${prosp_f.pagos[i].comprobante}" target="_blank">comprobante</a>` : '')
			]);
	}
		
	$("#lbl_count_pays").html(moneyFormat.format(cont_pago)+" "+code_curr)
	$("#tabla_pagos_admin").DataTable().draw();
	$("#tabla_pagos_admin").DataTable().columns.adjust();

	$("#tabla_comentarios").DataTable().draw();
	$("#tabla_comentarios").DataTable().columns.adjust();

	$("#lbl_prospecto_cambio").html(nombre)
	$("#inp_prospect").val(relac)
	$("#change_ejecutiva").val(mkt)
	// registros de pagos 

	$("#person_pago_adm").val(prosp_f.idAsistente)
	$("#evento_pago_adm").val((prosp_f.idCarrera != null)?prosp_f.idCarrera : prosp_f.idEvento )

	$("#modal_seguimiento_gral").modal('show')
	// "tabla_comentarios"
}

$("#editar_prospecto").on('submit', function(e){
	e.preventDefault();
	if(!regCorreo.test(String($("#edit_pr_correo").val().trim()).toLowerCase())){
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
					}else{
						console.log(actualiza)
						swal({
							icon:'info',
							text:'Ha ocurrido algo al actualizar, notifique al administrador.'
						})
					}
					init_data();
					listar_ejecutivas();
					$("#modal_seguimiento_gral").modal('hide');
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

$("#form_registrar_pago_adm").on('submit', function(e){
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
			$("#form_registrar_pago_adm").find('button[type=submit]').attr('disabled',true)
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
				listar_ejecutivas()
				tabla_prospectos()
				$("#form_registrar_pago_adm")[0].reset()
				$("#modal_seguimiento_gral").modal('hide')
				init_data();

				$("#home-tab").click();
				$("#carrer-tab").click();
				cargar_eventos();
				cargar_carreras();

				console.log(data)
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#form_registrar_pago_adm").find('button[type=submit]').attr('disabled',false)
		}
	});
})
