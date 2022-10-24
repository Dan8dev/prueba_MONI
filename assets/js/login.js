/*
  * Login principal para todo usuario de moni
*/
$("#formLogin").on("submit", function(e){
	e.preventDefault();
	if($("#inpCorreo").val().trim() !== "" && $("#inpPassw").val().trim() !== ""){
		fData = new FormData(this);
		fData.append("action","validarLogin");
		$.ajax({
			url: "assets/data/Controller/accesoControl.php",
			type: "POST",
			data: fData,
			contentType: false,
			processData:false,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				console.log(data)
				try{
					json = JSON.parse(data);
					titulo = "";
					tipoAlert = "";
					band = false;
					if(json.estatus == "ok" && json.data.persona){
						json=json.data
						titulo = "Éxito!"
						json.message = "Bienvenido "+json.persona.nombres;
						tipoAlert = "success";
						band = true;
					}else{
						titulo = "Ha ocurrido algo"
						json.message = ((json.hasOwnProperty("message"))?json.message : "Verifique sus accesos con el administrador.");
						tipoAlert = "info";
						console.log(json)
					}
					swal({
		                title: titulo,
		                text: json.message,
		                icon: tipoAlert,
		              });
					setTimeout(()=>{
						if(json.directorio !== null && json.directorio !== undefined){
							window.location.replace(json.directorio)
						}
					},800)
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
		alert("rellenar los campos");
	}
});

$("#formCambioPass").on('submit', function(e){
	e.preventDefault();
	var val1 = $("#inpPassw").val().trim();
    var val2 = $("#inpPassw_verify").val().trim();
	if(val1 != val2){
		swal({
			text:'La nueva contraseña y la contraseña de confirmación deben coincidir',
			icon:'info'
		});
		return;
	}
	fdata = new FormData(this)
	fdata.append('action', 'cambiarPass');
	$.ajax({
		url: 'assets/data/Controller/accesoControl.php',
		type: "POST",
		data: fdata,
		contentType:false,
		processData:false,
		beforeSend : function(){
			$("#formCambioPass button[type='submit']").attr("disabled", true);
		},
		success: function(data){
			try{
				var resp = JSON.parse(data);
				if(resp.estatus == 'ok'){
					swal({
						icon:'success',
						title:'contraseña actualizada'
					}).then(()=>{
						window.location.replace(`./${$("#directorio").val()}`);
					});
				}else{
					swal({
						icon:'info',
						text:resp.info
					}).then(()=>{
						// window.location.replace(`./${$("#directorio").val()}`);
					});
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$("#formCambioPass button[type='submit']").attr("disabled", false);
		}
	});
})