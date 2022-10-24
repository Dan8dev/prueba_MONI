$(document).ready(function(){
	cargar_memoria();
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
                        links+=`<li><a href="reproductor.php?evento=${json[i].nombreClave}&pt=${l+1}">${parts[l][0].charAt(0).toUpperCase() + parts[l][0].slice(1)}</a></li>`;
                    }
					/*
					<div class="card-body">
		                  <p class="card-text text-truncate">${json[i].titulo}</p>
		                </div>
					*/
                    html+=`
					<div class="col-xl-4 col-sm-6 col-md-6" contiene="${json[i].titulo}">
		              <div class="card mg-b-40">
		                
                        <img class="card-img-bottom img-fluid" src="https://moni.com.mx/assets/images/generales/videoteca/${json[i].imagen}" alt="Image">
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