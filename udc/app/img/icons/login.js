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
					if(json.estatus == "ok"){
						json=json.data
						titulo = "¡Éxito!"
						json.message = "Bienvenido "+json.persona.nombres;
						tipoAlert = "success";
						band = true;
					}else{
						titulo = "Ha ocurrido algo"
						json.message = ((json.hasOwnProperty("message"))?json.message : "Ha ocurrido un error interno, notifique al administrador.");
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