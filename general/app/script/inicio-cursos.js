$(document).ready(function(){
	proximos_eventos();
});

function proximos_eventos(){
	$.ajax({
		url: "../../assets/data/Controller/carreras/carrerasControl.php",
		type: "POST",
		data: {action:'listado_carreras'},
		//contentType: false,
		//processData:false,
		beforeSend : function(){
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				console.log(json)
				html = '';
				//json = json.data;
				for (i = 0; i < json.length; i++) {
					if(json[i].idCarrera == 1){
					/*html+=`<div class="col-xl-6 col-sm-6 mb-4">
						  <div class="card bd-0">
							<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
							  <h6 class="mg-b-3"><a href="https://conacon.org/moni/carreras/?e=${json[i].nombre_clave}" class="text-white">${json[i].nombre}</a></h6>
							  <span class="tx-12 text-white">Inicio de curso 29 de Septiembre 2021</span><br>
							  <span class="tx-12"><a class="text-white" href="https://conacon.org/moni/carreras/?e=operador-terapeutico" target="_blank">Registrate ahora</a></span><br>
								<span class="tx-12"><a class="text-white" href="https://cismac.com.mx/tmp/pagoOTA.html" target="_blank">Pagar ahora</a></span>
							</div><!-- card-body -->
							<img class="card-img-bottom img-fluid" src="https://conacon.org/moni/assets/images/generales/flyers/${json[i].imagen}" alt="Image">
						  </div><!-- card -->
						</div>`;*/
					
						
						html+=`<div class="col-xl-6 col-sm-6 mb-4">
						  <div class="card bd-0">
							<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
							  	<h6 class="mg-b-3"><a href="https://conacon.org/moni/carreras/?e=${json[i].nombre_clave}" target="_blank" class="text-white">${json[i].nombre}</a></h6>
							  	<span class="tx-12 text-white mb-10">Inicio de curso 29 de Septiembre 2021</span><br>
							  	<a href="https://conacon.org/moni/carreras/?e=operador-terapeutico" target="_blank" class="btn btn-block btn-primary active btn-with-icon">
									<div class="ht-40 justify-content-between">
										<span class="pd-x-15">Registrate Ahora</span>
										<span class="icon wd-40"><i class="fa fa-globe"></i></span>
									</div>
								</a>
								<a href="https://cismac.com.mx/tmp/pagoOTA.html" target="_blank" class="btn btn-block btn-primary active btn-with-icon">
									<div class="ht-40 justify-content-between">
										<span class="pd-x-15">Pagar ahora</span>
										<span class="icon wd-40"><i class="fa fa-credit-card"></i></span>
									</div>
								</a>
							</div><!-- card-body -->
							<img class="card-img-bottom img-fluid" src="https://conacon.org/moni/assets/images/generales/flyers/${json[i].imagen}" alt="Image">
						  </div><!-- card -->
						</div>`
					}
				}
				$("#container-proxmios").html(html);
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
		}
	});
}