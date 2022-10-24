meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$(document).ready(function(){
	proximos_eventos();
});

function proximos_eventos(){
	$.ajax({
		url: "../../assets/data/Controller/carreras/carrerasControl.php",
		type: "POST",
		data: {action:'listado_carreras',institucion:13},
		//contentType: false,
		//processData:false,
		beforeSend : function(){
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				html = '';
				//json = json.data;
				for (i = 0; i < json.length; i++) {
					
					if(json[i].idCarrera != 3){		
						//<span class="tx-12 text-white mb-10">Inicio de curso 29 de Septiembre 2021</span><br>
						html+=`<div class="col-xl-6 col-sm-6 mb-4">
						  <div class="card bd-0">
							<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
							  	<h6 class="mg-b-3"><a href="https://moni.com.mx/carreras/?e=${json[i].nombre_clave}" target="_blank" class="text-white">${json[i].nombre}</a></h6>
							  	<button onclick="registrar_a_carrera('${json[i].nombre_clave}')" style="cursor:pointer;" class="btn btn-block btn-primary active btn-with-icon">
									<div class="ht-40 justify-content-between">
										<span class="pd-x-15">Regístrate Ahora</span>
										<span class="icon wd-40"><i class="fa fa-globe"></i></span>
									</div>
								</button>
							</div><!-- card-body -->
							<img class="card-img-bottom img-fluid" src="../../assets/images/generales/flyers/${(json[i].imagen != '')? json[i].imagen : 'default_curso.png'}" alt="Image">
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

function registrar_a_carrera(clave_carrera){
	$.ajax({
	  url: "../../assets/data/Controller/marketing/marketingControl.php",
	  type: "POST",
	  data: {action:'registrar_a_carrera', tipo:'carrera', nombre_c:clave_carrera},
	  beforeSend : function(){
	    $(".outerDiv_S").css("display", "block")
	  },
	  success: function(data){
	    try{
	        resp = JSON.parse(data)
	        if(resp.estatus == 'ok'){

				ejecutiva = '';
				if(resp.hasOwnProperty('ejecutiva') && resp.ejecutiva !== null && resp.ejecutiva.telefono.trim() != ''){
					ejecutiva = `<p>O si lo prefieres puedes enviar un mensaje tu mismo.</p>
					<a href='https://api.whatsapp.com/send?phone=+521${resp.ejecutiva.telefono.replace(/\s+/g, '').trim()}' target="_blank" class='text-success'>Clic aquí <i class='fa fa-whatsapp tx-24'></i></a>`;
				}
				mensaje = `<span>
				<h5>Su registro ha sido exitoso</h5>
				<p>En unos momentos su ejecutiva se pondrá en contacto con usted.</p>
				${ejecutiva}
			  	</span>`;
				  
				// document.createElement('span');
				// h5 = document.createElement('h5');
				// h5.te
				// mensaje.append();
	          swal.fire({
	            type:'success',
	            html:mensaje
	          })
	        }else{
	          swal.fire({
	            type:'info',
	            text:resp.info
	          })
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
