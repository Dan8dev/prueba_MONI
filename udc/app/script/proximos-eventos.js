meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$(document).ready(function(){
	proximos_eventos();
});

function proximos_eventos(){
	$.ajax({
		url: "../../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: {action:'consultar_eventos_memoria', tipo:'proximos'},
		//contentType: false,
		//processData:false,
		beforeSend : function(){
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				html = '';
				json = json.data;
				for (i = 0; i < json.length; i++) {
					/*html+=`<div class="col-xl-6 col-sm-6 mb-4">
              <div class="card bd-0">
                <div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
                  <h6 class="mg-b-3"><a href="" class="text-white">${json[i].titulo}</a></h6>
                  <span class="tx-12 "><a class="text-white" href="https://cismac.com.mx/" target="_blank">Más información</a></span><br>
<span class="tx-12"><a class="text-white" href="#" >Pagar ahora</a></span>
                </div><!-- card-body -->
                <img class="card-img-bottom img-fluid" src="https://conacon.org/moni/assets/images/generales/flyers/${json[i].imagen}" alt="Image">
              </div><!-- card -->
            </div>`
				}*/
				fecha_inicio = "";
				if(json[i].fechaE != null){
					$for_inicio = json[i].fechaE.split(' ')[0].split('-');
					fecha_inicio = "Fecha del evento "+$for_inicio[2]+" de "+meses[parseInt($for_inicio[1])-1]+" "+$for_inicio[0];
				}
				if(json[i].idInstitucion == 20){
					html+=`<div class="col-xl-6 col-sm-6 mb-4">
						<div class="card bd-0">
							<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
								<h6 class="mg-b-3 text-white">${json[i].titulo}</h6>
								<span class="tx-12 text-white mb-10">${fecha_inicio}</span><br>
								<button class="btn btn-block btn-primary active btn-with-icon" onclick="inscribir_a_evento(${json[i].idEvento},'${json[i].nombreClave}')">
									<div class="ht-40 justify-content-between">
										<span class="pd-x-15">Registrate Ahora</span>
										<span class="icon wd-40"><i class="fa fa-globe"></i></span>
									</div>
								</button>
							</div><!-- card-body -->
							<img class="card-img-bottom img-fluid" src="../../assets/images/generales/flyers/${json[i].imagen}" alt="Image">
						</div><!-- card -->
						</div>`;
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

function inscribir_a_evento(evento, nombrec){
	swal({
		icon:'info',
		title:'Confirmar registro',
		text:'Desea registrarse al evento '+nombrec,
		buttons:['Cancelar', 'Confirmar']
	}).then( (response)=>{
		if(response){
			$.ajax({
				url: "../../assets/data/Controller/eventos/eventosControl.php",
				type: "POST",
				data: {action:'inscribirse_evento', id_evento:evento},
				//contentType: false,
				//processData:false,
				beforeSend : function(){
				},
				success: function(data){
					try{
						resp = JSON.parse(data);
						if(resp.estatus == 'ok'){
							swal({
								icon:'success',
								title:'Registro exitoso',
								text:'Proceda a realizar los pagos correspondientes al registro.',
							})
						}else{
							swal({
								icon:'info',
								text:resp.info
							})
						}
						console.log(resp)
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
	})

}