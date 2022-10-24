$(document).ready(function(){

	//cargar_memoria();
	cargar_memoria_premaestria();
})

function cargar_memoria(){
	$.ajax({
		url: "../../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: {action:'consultar_eventos_memoria', tipo:'memoria'},
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
					/*html+=`
					<div class="col-xl-4 col-sm-6 col-md-6">
		              <div class="card mg-b-40">
		                <div class="card-body">
		                  <p class="card-text text-truncate">${json[i].titulo}</p>
		                </div>
		                <a href="reproductor.php?evento=${json[i].nombreClave}"><img class="card-img-bottom img-fluid" src="https://conacon.org/sandbox/videos/imagenes/${json[i].imagen}" alt="Image"></a>
		              </div>
		            </div>
					`;*/
                    links = "";
                    parts = (json[i].video_url !== "") ? JSON.parse(json[i].video_url) : [];
                    for(l = 0; l < parts.length ; l++){
                        links+=`<li><a href="reproductor.php?evento=${json[i].nombreClave}&pt=${l+1}">${parts[l][0]}</a></li>`;
                    }
					/*
					<div class="card-body">
		                  <p class="card-text text-truncate">${json[i].titulo}</p>
		                </div>
					*/
                    html+=`
					<div class="col-xl-4 col-sm-6 col-md-6" contiene="${json[i].titulo}">
		              <div class="card mg-b-40">
		                
                        <img class="card-img-bottom img-fluid" src="https://conacon.org/sandbox/videos/imagenes/${json[i].imagen}" alt="Image">
		                <div class="overlay-evento">
                            <ul class="overlay-evento-content">
                                ${links}
                            </ul>
                        </div>
		              </div>
		            </div>
					`;
				}
				$("#content-events").html(html);
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

function cargar_memoria_premaestria(idgeneracion){
	$.ajax({
		// url: "../../assets/data/Controller/controlescolar/materiasControl.php",
		url:"../../assets/data/Controller/controlescolar/materiasControl.php",
		type: "POST",
		data: {action:'consultar_sesiones_filtro', "generacion":'129',"android_id_afiliado":"21"},
		dataType: "JSON",
		//contentType: false,
		//processData:false,
		beforeSend : function(){
		},
		success: function(data){
			console.log(data);
			try{
				html = '';
				//json = json.data;
				//console.log(json);
				var contVideos = 0;
				$.each(data,(i,el)=>{
                    console.log(el);
					if(el.video != null && el.video != ""){
						html+=`
						<div class="col-xl-4 col-sm-6 col-md-6">
						  <div class="card mg-b-40">
							<div class="card-body bg-dark">
							  <h6><p class="card-text text-white"><b>${el.titulo}</b></p></h6>
							</div>
							<a href="reproductor.php?url=${el.video}&idclass=${el.id_clase}"><img class="card-img-bottom img-responsive" src="img/fondovideos.jpg" heigth="350" width = "1000"></a>
						  </div>
						</div>
						`;
						contVideos++;
					}
                });
				if(contVideos == 0){
					html = "<h3>Sin grabaciones de Sesión</h3>";
				}
				$("#content-events").html(html);
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
