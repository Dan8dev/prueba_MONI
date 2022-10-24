meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$(document).ready(function(){
	proximos_eventos();
});

$(document).ready(function(){
	
	'use strict';

	$('#wizard2').steps({
	  headerTag: 'h3',
	  bodyTag: 'section',
	  autoFocus: true,
	  titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
	  onStepChanging: function (event, currentIndex, newIndex) {
		if(currentIndex < newIndex) {
		  // Step 1 form validation
		  if(currentIndex === 0) {
			var fname = $('#firstname').parsley();
			var lname = $('#lastname').parsley();

			if(fname.isValid() && lname.isValid()) {
			  return true;
			} else {
			  fname.validate();
			  lname.validate();
			}
		  }

		  // Step 2 form validation
		  if(currentIndex === 1) {
			var email = $('#email').parsley();
			if(email.isValid()) {
			  return true;
			} else { email.validate(); }
		  }
		// Always allow step back to the previous step even if the current step is not valid.
		} else { return true; }
	  }
	});

	$('.fc-datepicker').datepicker({
	  showOtherMonths: true,
	  selectOtherMonths: true
	}); 
  });
  function mayusculas(e) {
	  e.value = e.value.toUpperCase();
	} 

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
					if(json[i].idInstitucion == 13 && json[i].tipo != 'amor-con-amor'){
				fecha_inicio = "";
				if(json[i].fechaE != null){
					$for_inicio = json[i].fechaE.split(' ')[0].split('-');
					fecha_inicio = "Fecha del evento "+$for_inicio[2]+" de "+meses[parseInt($for_inicio[1])-1]+" "+$for_inicio[0];
				}
				var button_insc = '';
				if(json[i].hasOwnProperty('registrado') && json[i].registrado == true){
					button_insc = `<button class="btn btn-block btn-primary disabled btn-with-icon" onclick="swal('Ya estás registrado para este evento')">
									<div class="ht-40 justify-content-between">
										<span class="pd-x-15">Ya estás registrado</span>
										<span class="icon wd-40"><i class="fa fa-globe"></i></span>
									</div>
								</button>`;
				}else{
					button_insc = `<button class="btn btn-block btn-primary active btn-with-icon" onclick="inscribir_a_evento(${json[i].idEvento},'${json[i].nombreClave}', '${json[i].titulo.trim()}')">
					<div class="ht-40 justify-content-between">
						<span class="pd-x-15">Registrate Ahora</span>
						<span class="icon wd-40"><i class="fa fa-globe"></i></span>
					</div>
				</button>`;
				}
				html+=`<div class="col-xl-6 col-sm-6 mb-4">
					<div class="card bd-0">
						<div class="card-body bd bd-b-0 bd-color-gray-lighter rounded-top pb-2 bg-primary">
							<h6 class="mg-b-3 text-white">${json[i].titulo}</h6>
							<span class="tx-12 text-white mb-10">${fecha_inicio}</span><br>
							${button_insc}
						</div><!-- card-body -->
						<img class="card-img-bottom img-fluid" src="https://moni.com.mx/assets/images/generales/flyers/${json[i].imagen}" alt="Image">
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

function inscribir_a_evento(evento, nombrec, titulo){
	swal({
		icon:'info',
		title:'Confirmar registro',
		text:'Desea registrarse al evento '+titulo,
		buttons:['Cancelar', 'Confirmar']
	}).then( (response)=>{
		if(response){
			$.ajax({
				url: "../../assets/data/Controller/marketing/marketingControl.php",
				type: "POST",
				data: {action:'registrar_a_evento', tipo:'evento', nombre_c:nombrec},
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
								title:'Registro exitoso'
							})
						}else{
							swal({
								icon:'info',
								text:resp.info
							})
						}
						proximos_eventos();
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

function llenareventos(){

	//var eventos = new Array();
	var reg_events = [];
	$.ajax({
		url: "../../assets/data/Controller/eventos/eventosControl.php",
		type: "POST",
		data: {action:'eventosCalendar', tipo:'proximos'},
		dataType: "JSON",
		success: function (response) {
	
			// $.each(response.data, function(key,registro){
			// 	var eventos = {
			// 		title: registro.titulo,
			// 		start: registro.fechaDisponible.substr(0,9),
          	// 		end: registro.fechaLimite.substr(0,9)
			// 	};
			// 	/* eventos['titl']e= registro.titulo;
			// 	eventos['start']= registro.fechaDisponible.substr(0,9);
			// 	eventos['end']= registro.fechaLimite.substr(0,9); */
	
			// 	reg_events.push(eventos);
	
			// });
	
			console.log(reg_events);
	drwcalendar(response);
			// console.log(eventos);
		},	
	});
}

try {
	function drwcalendar(event){
		var srcCalendarEl = document.getElementById('source-calendar');
		var destCalendarEl = document.getElementById('destination-calendar');
	
		var aux = [];
		aux.push({
			title: 'event1',
			start: '2022-09-11',
			end: '2020-09-11'
		  });
		  aux.push({
			title: 'event2',
			start: '2022-09-13',
			end: '2020-09-13'
		  });
	
	
		var srcCalendar = new FullCalendar.Calendar(srcCalendarEl, {
			eventClick: function(arg) {
				  //arg.event.remove()
				  console.log(arg.event.title);
	
			  },
		  editable: true,
		  initialDate: '2022-10-01',
		  events: event,
		});
	
		var destCalendar = new FullCalendar.Calendar(destCalendarEl, {
		  initialDate: '2022-10-12',
		  editable: false,
		  droppable: true, // will let it receive events!
		  eventReceive: function(info) {
			console.log('event received!', info.event);
		  }
		});
	
		srcCalendar.render();
		destCalendar.render();
	
	}
	
} catch (error) {
	console.log(error);
	
}


document.addEventListener('DOMContentLoaded', function() {
    llenareventos();
  });
