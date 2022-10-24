$("#formLogin").on('submit', function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append('action','validar_asistencia');
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
				json = JSON.parse(data);
					console.log(json)
				relocation = json.hasOwnProperty('relocation') ? json.relocation : '';
				titulo = "";
				tipoAlert = "";
				if(json.estatus == "ok"){
					json = json.data
					titulo = "Éxito!"
					json.message = "Bienvenido "+json.nombre;
					tipoAlert = "success";
				}else{
					tipoAlert = "info";
					if(json.info == 'prospecto_rechazado') {
						// titulo = "Éxito!"
						json.message = "Parece que tu acceso fue declinado, te recomendamos que contactes a tu ejecutivo de ventas";
					}else if(json.info == 'sin_coincidencias'){
						json.message = "Usuario no reconocide, verifique su usuario y contraseña";
					}
				}
				swal({
	                title: titulo,
	                text: json.message,
	                icon: tipoAlert,
	              });
				setTimeout(()=>{
					if(relocation != ''){
						window.location.replace(relocation)
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
})