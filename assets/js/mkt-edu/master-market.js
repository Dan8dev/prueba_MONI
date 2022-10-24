//Kikicaro82
// regCorreo = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
let list_ejecutivas = [];
$(document).ready(function(){
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
					ejecutivas = JSON.parse(data);
					options = "";
					$("#tabla_ejecutivas").DataTable().clear();
					list_ejecutivas = [];
					for (i = 0; i < ejecutivas.length; i++) {
						list_ejecutivas.push(ejecutivas[i]);
						options+= `<option value="${ejecutivas[i].idPersona}">${ejecutivas[i].apellidoPaterno} ${ejecutivas[i].nombres}</option>`;
						if(ejecutivas[i].prospectos_carreras === null){
							ejecutivas[i].prospectos_carreras = [];
						}
						if(ejecutivas[i].prospectos_eventos === null){
							ejecutivas[i].prospectos_eventos = [];
						}
						$("#tabla_ejecutivas").DataTable().row.add([
							`${ejecutivas[i].apellidoPaterno} ${ejecutivas[i].apellidoMaterno} ${ejecutivas[i].nombres}`,
							// ejecutivas[i].correo,
							ejecutivas[i].fila_prospectos_ca+" para carreras. "+ejecutivas[i].fila_prospectos_ev+" para eventos.",
							((f_refer.toISOString().substr(0, 10) == ejecutivas[i].sesion.substr(0,10)) ? 'En sesión hoy' : '-')
						]);
						
					}

					$("#tabla_ejecutivas").DataTable().draw();
					$("#tabla_ejecutivas").DataTable().columns.adjust();

					$("#n_prosp_personaMk").html(options)
					$("#change_ejecutiva").html(options)

					// tabla_prospectos();
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

function configurar_acceso(ejecutiva){
	
}
// function cargar_instituciones(){
// 	$.ajax({
// 		url: "../assets/data/Controller/instituciones/institucionesControl.php",
// 		type: "POST",
// 		data: {action:'lista_instituciones'},
// 		// contentType: false,
// 		// processData:false,
// 		beforeSend : function(){
// 			$(".outerDiv_S").css("display", "block")
// 		},
// 		success: function(data){
// 			try{
// 				instit = JSON.parse(data);
// 				html_opt = "<option value='0' selected>Si pertenece a una asociación, elijala</option>";
// 				html_ej = "<option value='0' selected>Si pertenece a una asociación, elijala</option>";
// 				if(instit.estatus == 'ok'){
// 					for (i = 0; i < instit.data.length; i++) {
// 						if(instit.data[i].fundacion == '1'){
// 							html_opt+=`<option value="${instit.data[i].id_institucion}">${instit.data[i].nombre}</option>`
// 						}
// 						html_ej+=`<option value="${instit.data[i].id_institucion}">${instit.data[i].nombre}</option>`
// 					}
// 				}
// 				$("#IDOrganizacion").html(html_opt);
// 				$("#edit_pr_institucion").html(html_opt);
// 				$("#edit_pr_institucion_ej").html(html_ej);
// 			}catch(e){
// 				console.log(e);
// 				console.log(data);
// 			}
// 		},
// 		error: function(){
// 		},
// 		complete: function(){
// 			$(".outerDiv_S").css("display", "none")
// 		}
// 	});
// }

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
		if(!comprobar_campo_cedula()){
			swal({
				icon:'info',
				text:'Para la información de cédula profesional llene todos los campos o ninguno'
			});
			return;
		}
		fData = new FormData(this);
		fData.append('action', 'registrar_prospecto');
		fData.append('marketing', true);
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
						asignar_prospecto_ejecutiva(insertar.list_val, tipo_pros, persona_mk, insertar.info, interes);
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
								text: mensaje != '' ? mensaje : insertar.info,
								icon: 'info'
							})
					}
					// listar_ejecutivas()
					reload_tablas()
					$("#v-profile-tab").click();
					$("#form_nuevo_prospecto")[0].reset();
					$("#select_nacionalidad").val(37);
					$("#id_destino").html('');
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
						});
					}else if(info_anterior == 'registrado_como_alumno'){
						swal({
							title: 'Registro exitoso',
							text: 'El alumno deberá recibir un correo con el enlace a su plataforma y sus accesos.',
							icon: 'success'
						});
					}else{
						swal({
							title: 'Asignación a ejecutivo exitoso',
							text: '',
							icon: 'success'
						});
					}
				}else{
					swal({
						title: 'Ocurrió un error al intentar asignar el prospecto a una ejecutiva',
						text: '',
						icon: 'info'
					});
				}
				// listar_ejecutivas()
				$("#v-profile-tab").click();
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
		  if(it.idCarrera != 3 && it.callcenter == 1){
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
		if(list_ejecutivas[i].prospectos_carreras == null){
			list_ejecutivas[i].prospectos_carreras = [];
		}
		if(list_ejecutivas[i].prospectos_eventos == null){
			list_ejecutivas[i].prospectos_eventos = [];
		}
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
				`<button class="btn btn-secondary" onclick="seguimiento('carrera',${list_ejecutivas[i].idPersona}, ${prospecto.idReg},${prospecto.idReg}, '${prospecto.nombre}',${list_ejecutivas[i].idPersona})"><i class="fas fa-users-cog"></i></button>
				<button class="btn btn-secondary" onclick="pago_carrera(${prospecto.idAsistente}, ${prospecto.evento_carrera}, '${prospecto.nombre}',${prospecto.institucion_c})"><i class="fas fa-money-bill-alt"></i></button>`
			]);
			if(prospecto.tipo_atencion == 'carrera' && prospecto.generacion_carrera !== null){
				conteo_inscritos++;
				$("#tabla_inscritos").DataTable().row.add([
					prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre,
					prospecto.correo,
					prospecto.telefono,
					prospecto.fe_reg.substring(0,16),
					`carrera - ${prospecto.titulo_c} [${prospecto.generacion_carrera}]`,
					list_ejecutivas[i].nombres+' '+list_ejecutivas[i].apellidoPaterno,
					`<button class="btn btn-secondary" onclick="seguimiento('carrera',${list_ejecutivas[i].idPersona}, ${prospecto.idReg},${prospecto.idReg}, '${prospecto.nombre}',${list_ejecutivas[i].idPersona})"><i class="fas fa-users-cog"></i></button>
					<button class="btn btn-secondary" onclick="pago_carrera(${prospecto.idAsistente}, ${prospecto.evento_carrera}, '${prospecto.nombre}',${prospecto.institucion_c})"><i class="fas fa-money-bill-alt"></i></button>`
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
				`<button class="btn btn-secondary" onclick="seguimiento('evento',${list_ejecutivas[i].idPersona}, ${prospecto.idReg},${prospecto.idReg}, '${prospecto.nombre}',${list_ejecutivas[i].idPersona})"><i class="fas fa-users-cog"></i></button>
				<button class="btn btn-secondary" onclick="pago_evento(${prospecto.idAsistente}, ${prospecto.evento_carrera}, '${prospecto.nombre}')"><i class="fas fa-money-bill-alt"></i></button>`
			]);
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
					reload_tablas()
				}else{
					swal({
						icon:'info',
						text:'Ha ocurrido algo al reasignar, notifique al administrador.'
					})
				}
				init_data();
				// listar_ejecutivas();
				$("#modal_seguimiento_gral").modal('hide');
				$("#modal_seguimiento").modal('hide');
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
function cons_coment(seguimiento){
	$("#tabla_comentarios").DataTable().destroy();
	$("#tabla_comentarios").DataTable({
		"ajax": {
			url: '../assets/data/Controller/marketing/marketingControl.php',
			type: 'POST',
			data: {action: 'seguimientos', seguimiento:seguimiento},
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
		}
	});
}
function seguimiento(tipo, ejecutiva, reg,relac, nombre, mkt){
	prosp_f = false;
	ejecutiva = list_ejecutivas.find(elm => elm.idPersona == ejecutiva)
	
	if(tipo == 'carrera'){
		prosp_f = ejecutiva.prospectos_carreras.find(elm => elm.idReg == reg)
	}else{
		prosp_f = ejecutiva.prospectos_eventos.find(elm => elm.idReg == reg)
	}
	seguimientos_pros = prosp_f.seguimiento
	// $("#tabla_comentarios").DataTable().clear();
	$("#edit_pr_nombre").val(prosp_f.nombre)
	$("#edit_pr_apaterno").val(prosp_f.aPaterno)
	$("#edit_pr_amaterno").val(prosp_f.aMaterno)
	$("#edit_pr_telefono").val(prosp_f.telefono.replace(/[^0-9]+/g, ''))
	$("#edit_pr_correo").val(prosp_f.correo)
	$("#inp_prospect_edit").val(prosp_f.idAsistente)

	$("#edit_pr_curp").val(prosp_f.opcionCurp);

	if(prosp_f.opcionCurp==null){
		$("#edit_pr_curp").addClass("d-none");
		$("#edit_pr_curp_lbl").addClass("d-none");
	}else{
		$("#edit_pr_curp").removeClass("d-none");
		$("#edit_pr_curp_lbl").removeClass("d-none");
	}

	
	var instit = prosp_f.idAsociacion ? prosp_f.idAsociacion : 0
	$("#edit_pr_institucion").val(instit)

	// for (i = 0; i < seguimientos_pros.length; i++) {
	// 	$("#tabla_comentarios").DataTable().row.add([
	// 		seguimientos_pros[i].fecha,
	// 		seguimientos_pros[i].detalles
	// 		]);
	// }


	// $("#tabla_comentarios").DataTable().draw();
	// $("#tabla_comentarios").DataTable().columns.adjust();

	$("#lbl_prospecto_cambio").html(nombre)
	$("#inp_prospect").val(relac)
	$("#change_ejecutiva").val(mkt)
	// registros de pagos 

	$("#person_pago_adm").val(prosp_f.idAsistente)
	$("#evento_pago_adm").val((prosp_f.idCarrera != null)?prosp_f.idCarrera : prosp_f.idEvento )

	$("#modal_seguimiento_gral").modal('show')
	// "tabla_comentarios"
	cons_coment(relac)
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
					// listar_ejecutivas();
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

$(".special").on('change',function(){
	const str = $(this).val();
	str.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
	$(this).val(str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLocaleUpperCase())
});


function comprobar_campo_cedula(){
	if($('#inp_titulo_estudio').val().trim() != '' && $('#inp_cedula').val().trim() != '' && $("#fecha_egreso").val().trim() != '' && $("#escuela_proc").val().trim() != '' ){
		return true;
	}else if($('#inp_titulo_estudio').val().trim() == '' && $('#inp_cedula').val().trim() == '' && $("#fecha_egreso").val().trim() == '' && $("#escuela_proc").val().trim() == '' ){
		return true;
	}else{
		return false;
	}
}
