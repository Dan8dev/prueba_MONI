const meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
const currencyF = { style: 'currency', currency: 'USD' };
const moneyFormat = new Intl.NumberFormat('en-US', currencyF);


function cargar_pagos(){

	fData = {action:"cargar_pagos",
			id_alumno:alumnoLoged.id_alumno};

	$.ajax({
		url: "data/CData/alumnosControl.php",
		type: "POST",
		data: fData,
		/*contentType: false,
		processData:false,*/
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				console.log(json);
				html = "";
				for (var i = 0; i < json.length; i++) {
					if(json[i].regularidad == 1){
						html+=AcordeonRecurrentes(json[i].nombre, json[i].id_plan,json[i].pendientes);
					}else{
						html+=`<div class="accordion col-12 mg-t-5">
			                    <div class="card pd-20 img-as-btn" onclick="verDetallesUnico(${json[i].id_plan})">
			                      ${json[i].nombre} (${moneyFormat.format(json[i].total_cubrir)})
			                    </div>
			                  </div>`;
					}
				}
				$("#divContentPagos").html(html);
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

}

function AcordeonRecurrentes(titulo, id_plan, arr_pagos){
	html = "";
	if (titulo !== "" & arr_pagos.length > 0) {
		items = ``;
		//arr_pagos[{"id_fechapago": "1","id_plan": "1","fecha_programada": "2021-06-05","monto": "1000","estatus": "0"}]
		for (var i = 0; i < arr_pagos.length; i++) {

			items+=`<div class="card-block pd-20 img-as-btn" onclick="verDetallesRegular(${id_plan},${arr_pagos[i].id_fechapago})">
	                  Pago correspondiente a ${meses[parseInt(arr_pagos[i].fecha_programada.substr(5,2))-1]} (${moneyFormat.format(arr_pagos[i].monto)})
	                </div>`;
		}
		html=`<div id="panelPago${id_plan}" class="accordion col-12" role="tablist" aria-multiselectable="true">
	            <div class="card">
	              <div class="card-header" role="tab" id="headingOne">
	                  <h6 class="mg-b-0">
	                    <a data-toggle="collapse" data-parent="#panelPago${id_plan}" href="#collapsePago${id_plan}"
	                    aria-expanded="true" aria-controls="collapsePago${id_plan}" class="tx-gray-800 transition">
	                    ${titulo}
	                    <i class="fa fa-arrow-down" style="float: right;"></i>
	                  </a>
	                </h6>
	              </div><!-- card-header -->

	              <div id="collapsePago${id_plan}" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
	                ${items}
	              </div>
	            </div>
	          </div>`;
	}

	return html;
}


function verDetallesRegular(id, id_pago){
	descripcionPago(id, "rg", id_pago);
}

function verDetallesUnico(id){
	descripcionPago(id, "uc");
}

function descripcionPago(id, tipo, arg = 0){
	fData = {
		action:"estatusPago",
		tipo: tipo,
		id_pago: id
	}
	if(arg !== 0){
		fData.id_fechaPago = arg;
	}
	$.ajax({
		url: "data/CData/pagosControl.php",
		type: "POST",
		data: fData,
		/*contentType: false,
		processData:false,*/
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				if(json.estatus == "ok" && json.data.length > 0){
					$("#tr-detallePago").html(`<td>${json.data[0].fecha_aplicacion}</td><td>${json.data[0].monto_aplicado}</td><td>${json.data[0].estatus}</td>`)
					$("#btnAplicarPago").attr("disabled", true);
					$("#btnAplicarPago").attr("dataaply", "nodata");
				}else{
					$("#tr-detallePago").html(`<td colspan="3">PENDIENTE</td>`);
					$("#btnAplicarPago").attr("disabled", false);
					$("#btnAplicarPago").attr("dataaply", id+"-"+tipo+"-"+arg);
				}
				
				$("#modalVistaPago").modal("show");
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
}

$("#btnAplicarPago").on("click", function(){
	apply = $("#btnAplicarPago").attr("dataaply");
	if(apply !== "nodata"){
		apply = apply.split("-");
		fData = {
			action:"generarPago",
			id_plan: apply[0],
			numeroPago: apply[2]
		};

		$.ajax({	
		url: "data/CData/pagosControl.php",
		type: "POST",
		data: fData,
		/*contentType: false,
		processData:false,*/
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				json = JSON.parse(data);
				console.log(data);
				if(json.estatus == "ok" && json.data > 0){
					swal({
					  title: "Pago realizado!",
					  text: "Gracias",
					  icon: "success",
					});
				}else{
					swal({
					  title: "Pago no procesado",
					  text: "Ha ocurrido un error",
					  icon: "info",
					});
				}
				cargar_pagos();
				$("#modalVistaPago").modal("hide");
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
	}
})