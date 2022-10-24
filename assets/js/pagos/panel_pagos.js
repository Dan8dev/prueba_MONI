$(document).ready(function(){
	cargar_alumnos_pagos();
})

function cargar_alumnos_pagos(){
	$.ajax({
		url: "../assets/data/Controller/prospectos/prospectoControl.php",
		type: "POST",
		data: {action:'consultar_prospectos_conceptos-pago'},
		beforeSend : function(){
		},
		success: function(data){
			try{
				var resp = JSON.parse(data)
          		$("#table-eventos").DataTable().clear();
          		if(resp.data.length > 0){
	          		var pers = resp.data[0];
	          		var monto_pago = 0;
	          		var rowAdd = [];
	          		var personas = [];
	          		for (var i = 0; i < resp.data.length; i++) {
	          			// if (resp.data[i].idAsistente != pers.idAsistente) {
	          			// 	personas.push(resp.data[i])
	          			// 	pers = resp.data[i]
	          			// }else{
	          			// 	personas.find( elm=>elm.idAsistente == )
	          			// }
	          			if(resp.data[i].idAsistente != pers.idAsistente){
	          				$("#table-eventos").DataTable().row.add([
	          					`<span title="${pers.idAsistente}">`+pers.nombre+' '+pers.aPaterno+' '+pers.aMaterno+`</span>`,
	          					pers.telefono,
	          					pers.correo,
	          					moneyFormat.format(monto_pago)
	          					]);

	          				pers = resp.data[i];
	          				monto_pago = parseFloat(resp.data[i].detalle_pago.purchase_units[0].amount.value);
	          			}else{
	          				monto_pago = monto_pago+parseFloat(resp.data[i].detalle_pago.purchase_units[0].amount.value)
	          				if(i == resp.data.length -1){
	          					$("#table-eventos").DataTable().row.add([
	          					`<span title="${pers.idAsistente}">`+pers.nombre+' '+pers.aPaterno+' '+pers.aMaterno+`</span>`,
	          					pers.telefono,
	          					pers.correo,
	          					moneyFormat.format(monto_pago)
	          					]);
	          				}
	          			}
	          		}
	          		console.log(personas)
          		}
          		$("#table-eventos").DataTable().draw();
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