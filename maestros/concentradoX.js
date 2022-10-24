let list_ejecutivas = [];
$(document).ready(function(){
	listar_ejecutivas();
	cargar_instituciones();
})

function listar_ejecutivas(){
	$.ajax({
		url: "concentrado.php",
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
					options+= `<option value="${ejecutivas[i].idPersona}">${ejecutivas[i].nombres} ${ejecutivas[i].apellidoPaterno}</option>`;
					
					$("#tabla_ejecutivas").DataTable().row.add([
						`<a href="javascript:void(0)" onclick="prospectos_tabla('${ejecutivas[i].nombres}')">${ejecutivas[i].apellidoPaterno} ${ejecutivas[i].apellidoMaterno} ${ejecutivas[i].nombres}</a>`,
						ejecutivas[i].correo,
						(ejecutivas[i].prospectos_carreras.length)+" para carreras. <br>"+ejecutivas[i].prospectos_eventos.length+" para eventos.",
						((f_refer.toISOString().substr(0, 10) == ejecutivas[i].sesion.substr(0,10)) ? 'En sesión hoy' : '-')
					]);
					
				}

				$("#tabla_ejecutivas").DataTable().draw();
				$("#tabla_ejecutivas").DataTable().columns.adjust();

				$("#n_prosp_personaMk").html(options)

				tabla_prospectos();
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
	fData = new FormData(this);
	fData.append('action', 'registrar_prospecto');
	tipo_pros = $("#tipo_prospecto option:selected").val();
	persona_mk = $("#n_prosp_personaMk option:selected").val();
	
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
					asignar_prospecto_ejecutiva(insertar.list_val, tipo_pros, persona_mk, insertar.info)
				}else{
					mensaje = '';
					if(insertar.info == 'membresia_existente'){
						mensaje = 'La persona ya cuenta con una membresía a CONACON.'
					}else if(insertar.info == 'limite_cubierto'){
						mensaje = 'El limite de vacantes para este evento ha sido cubierto.'
					}else if(insertar.info == 'correo_existente'){
						mensaje = 'La persona ya está registrada para este evento.'
					}
					swal({
							title: 'Fallo al registrar al prospecto',
							text: mensaje,
							icon: 'info'
						})
				}
				listar_ejecutivas()
				$("#v-profile-tab").click();
				$("#form_nuevo_prospecto")[0].reset()
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

function asignar_prospecto_ejecutiva(id_prospecto, tipo, persona_mk, info_anterior){
	
	fData = {action:'asignar_prospecto', prospecto:id_prospecto, n_prosp_tipo:tipo, n_prosp_personaMk:persona_mk};
	
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
					console.log(insertar)
				}
				listar_ejecutivas()
				$("#v-profile-tab").click();
				$("#form_nuevo_prospecto")[0].reset()
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

function tabla_prospectos(){
	$("#tabla_prospectos").DataTable().clear();
	
	for (i = 0; i < list_ejecutivas.length; i++) {
		
		for (j = 0; j < list_ejecutivas[i].prospectos_carreras.length; j++) {
			prospecto = list_ejecutivas[i].prospectos_carreras[j];
			carrera_f = list_carreras.find(elm => elm.idCarrera == prospecto.idCarrera);
			
			if(carrera_f !== undefined){
				$("#tabla_prospectos").DataTable().row.add([
					prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre,
					prospecto.fecha_registro.substring(0,16),
					`carrera - ${carrera_f.tipo} ${carrera_f.nombre}`,
					list_ejecutivas[i].nombres+' '+list_ejecutivas[i].apellidoPaterno
				]);
			}
		}

		for (h = 0; h < list_ejecutivas[i].prospectos_eventos.length; h++) {
			prospecto = list_ejecutivas[i].prospectos_eventos[h];
			evento_f = list_eventos.find(elm => elm.idEvento == prospecto.idEvento);
			
			if(evento_f !== undefined){
				$("#tabla_prospectos").DataTable().row.add([
					prospecto.aPaterno+" "+prospecto.aMaterno+" "+prospecto.nombre,
					prospecto.fecha_registro.substring(0,16),
					`evento - ${evento_f.tipo} ${evento_f.titulo}`,
					list_ejecutivas[i].nombres+' '+list_ejecutivas[i].apellidoPaterno
				]);
			}
		}

		
	}

	$("#tabla_prospectos").DataTable().draw();
	$("#tabla_prospectos").DataTable().columns.adjust();
}