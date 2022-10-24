$("#formlogin").on("submit", function(e){
	e.preventDefault();
	if($("#usr_name").val().trim() !== "" && $("#usr_pass").val().trim() !== ""){
		fData = new FormData(this);
		fData.append("action", "validar_acceso_institucion");
		$.ajax({
			url: "../../assets/data/Controller/alumnos/alumnosInstitucionesControl.php",
			type: "POST",
			data: fData,
			contentType: false,
			processData:false,
			beforeSend : function(){
				$("#loader").css("display", "block")
			},
			success: function(data){
				try{
					json = JSON.parse(data);
					//console.log(json)
					if(json.estatus == 'ok'){
						json = json.data;
						console.log(json)
						if(!json){
							swal({
								icon:'info',
								title:'Datos incorrectos',
								text:'Valide su usuario y contraseña.'
							}).then( (val)=>{
								$("#usr_pass").focus()
							})
						}else{
							if(json.instituciones.length > 0){
								if(json.instituciones.length > 1){
									opt_inst = json.instituciones.reduce( (acc, item) => {
												return acc+="<option value='"+item.panel_url+"'>"+item.institucion_n+"</option>"
											}, '' )
									$("#space_for_select").html(
										`<select class="form-control border-succes" id="select-card">
											<option selected>Seleccione el panel a acceder</option>
											${opt_inst}
										</select>`
									)
								}else{
									$("#space_for_select").html(``)
									window.location.replace(`../../${json.instituciones[0].panel_url}/app/talleres.php`)
								}
							}else{
								$("#space_for_select").html(``)
								swal({
									text:'No se especificó su acceso a la plataforma, contacte a soporte técnico'
								})
							}
						}
					}else{
						swal(json.info)
					}

					$("#select-card").focus();
					$("#select-card").on('change', function(){
						window.location.replace(`../../${$(this).val()}`)
					})
					// if(json.estatus == "ok" && json.data.length > 0){
					// 	swal({
					// 	  title: "Exito!",
					// 	  text: "Bienvenido "+json.data[0].nombre,
					// 	  icon: "success",
					// 	});
					// 	setTimeout(function(){
					// 		window.location.replace("page-profile.php");
					// 	}, 200);
					// }else{
					// 	msg = (json.estatus == "error")? ["Error interno", json.info[2]] : ["Datos incorrectos","Valide su usuario y contraseña"];
					// 	swal({
					// 	  title: msg[0],
					// 	  text: msg[1],
					// 	  icon: "info",
					// 	}).then((value)=>{
					// 		$("#usr_name").focus();
					// 	});
					// }
					// console.log(json);
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
		swal({
		  title: "Campos vacios",
		  text: "Por favor complete el formulario",
		  icon: "info",
		});
	}
})

$("#formCambiar_pass").on("submit", function(e){
	e.preventDefault();
	if($("#new_pass").val().trim() !== "" && $("#confirm_pass").val().trim() !== ""){
		fData = new FormData(this);
		fData.append("action", "cambiarpasw");
		$.ajax({
		url: "data/CData/alumnosControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{

				switch (data) {
					case "ok":
						swal({
							title: "Exito!",
							text: "La contraseña se actualizó correctamente ",
							icon: "success",
						  });
						  setTimeout(function(){
	  
							  window.location.replace("page-profile.php");
	  
						  }, 1000);
						break;
					case "sesion_destroy":
						swal({
							title: "Vuelve a iniciar sesión!",
							text: "La informacion no se actualizó",
							icon: "info",
						  });
						  setTimeout(function(){
	  
							  window.location.replace("index.php");
	  
						  }, 2000);
						break;
					case "3":
						swal({
							title: "Exito!",
							text: "La contraseña se actualizó correctamente ",
							icon: "success",
						  });
						  setTimeout(function(){
	  
							  window.location.replace("../../marketing-educativo/");
	  
						  }, 1000);
						break;
				
					default:
						swal({
							title: 'Ocurrió un error',
							text: data,
							icon: "info",
						  }).then((value)=>{
							  $("#new_pass").focus();
						  });
						break;
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
	}else{
		swal({
		  title: "Campos vacios",
		  text: "Por favor complete el formulario",
		  icon: "info",
		});
	}
})

$("#vercontrasena").click(function() {
		var tipo = document.getElementById("usr_pass");
		if(tipo.type == "password"){
			tipo.type = "text";
			$('#ocultareye').removeClass( "fa fa-eye-slash" );
			$('#ocultareye').addClass( "fa fa-eye" );
		}else{
			tipo.type = "password";
			$('#ocultareye').removeClass( "fa fa-eye" );
			$('#ocultareye').addClass( "fa fa-eye-slash" );
		}
	});

$("#mostrarlogin").click(function() {
	$("#recuperarpasw").hide();
	$("#formlogin").show();
});

$("#mostrarrecuperar").click(function() {
	$("#formlogin").hide();
	$("#recuperarpasw").show();
});

$("#recuperarpasw").on("submit", function(e){
	e.preventDefault();
	if($("#usr_name_recuperar").val().trim() !== ""){
		fData = new FormData(this);
		fData.append("action", "recuperarpasw");
		$.ajax({
		url: "data/CData/alumnosControl.php",
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				if(json.data == false){
					msg = (json.estatus == "error")? ["Error interno", json.info[2]] : ["Datos incorrectos","El correo no esta registrado"];
					swal({
					  title: msg[0],
					  text: msg[1],
					  icon: "info",
					}).then((value)=>{
						$("#usr_name_recuperar").focus();
					});
				}else{
					swal({
						title: "Exito!",
						text: "Consulta tu bandeja de entrada te hemos enviado tu contraseña de acceso",
						icon: "success",
					  });
					  setTimeout(function(){
						  window.location.replace("index.php");
					  }, 5000);
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
	}else{
		swal({
		  title: "Campo vacio",
		  text: "Por favor ingresa tu email",
		  icon: "info",
		});
	}
})
