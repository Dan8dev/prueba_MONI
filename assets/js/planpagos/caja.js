$(".special").on('change',function(){
	const str = $(this).val();
	// str.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
	$(this).val(str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLocaleUpperCase())
});

$(document).ready(()=>{
	listar_movimientos();
});
$("#nuevo_registro").on('submit', function(e){
	e.preventDefault();
	fData = new FormData(this);
	fData.append('action', 'registrar_a_caja');
	$.ajax({
		url: '../assets/data/Controller/planpagos/cajaControl.php',
		type: "POST",
		data: fData,
		contentType: false,
		processData:false,
		beforeSend : function(){
			$(`#nuevo_registro button:submit`).attr("disabled", true);
		},
		success: function(data){
			try{
				resp = JSON.parse(data)
      			if(resp.estatus == 'ok'){
      				swal('', 'Registrado exitosamente', 'success');
      			}else{
      				swal('', resp.info, 'info');
      			}
			  	listar_movimientos();
			  	$("#nuevo_registro")[0].reset();
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
		},
		complete: function(){
			$(`#nuevo_registro button:submit`).attr("disabled", false);
		}
	});
});

function listar_movimientos(){
	$.ajax({
		url: '../assets/data/Controller/planpagos/cajaControl.php',
		type: "POST",
		data: {action:'consultar_movimientos'},
		beforeSend : function(){
		},
		success: function(data){
			try{
				var resp = JSON.parse(data)
      			$("#tabla_general_registrados").DataTable().clear();
      			if(resp.estatus == 'ok'){
      				for(var i in resp.data){
      					var reg = resp.data[i];
      					$("#tabla_general_registrados").DataTable().row.add([
      						`${String(reg.id_registro).padStart(5, '0')}${reg.tipo.substring(0,3)}`,
      						reg.instituto,
      						reg.cliente,
      						`<span style="white-space: normal;">${reg.concepto}</span>`, 
      						moneyFormat.format(reg.monto), 
      						reg?.moneda.toUpperCase(), 
      						reg.fecha_registro.substring(0, 10),
      						`<span style="white-space:normal;">${reg.comentario}</span>`,
      						reg.persona != null ? reg.persona.nombres : '',
							`<button class="btn btn-primary" onclick="generar_comprobante('${reg.concepto.trim()}', '${moneyFormat.format(reg.monto)+' '+reg?.moneda.toUpperCase()}', '${reg.cliente}', '${reg.fecha_registro}', '${String(reg.id_registro).padStart(5, '0')}${reg.tipo.substring(0,3)}')">Generar Comprobante</button>`
      						]);
      				}
      			}
      			$("#tabla_general_registrados").DataTable().draw();
      			$("#tabla_general_registrados").DataTable().columns.adjust();
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		complete: function(){
		}
	});
}

function generar_comprobante(con, cost, cliente, fecha, cod){
	var rutalogopadf ='';
	// ($("#tipo_pago").val()==183)?rutalogopadf='logoTCONACON.png':rutalogopadf='logoTUDC.png';
	var doc = new jsPDF('l', 'mm', 'a5');
	var currentF = doc.getFontSize();
	currentF = currentF - 5;
	// currentF = currentF - 5;
	// <<<<<<<<<<<<<< HEADER
	doc.setFillColor(27, 32, 62);
	doc.rect(0, 0, 250, 30, 'F');
	doc.setTextColor(255, 255, 255);
	
	doc.setFontSize(15)
	doc.text(15,15, 'COMPROBANTE DE PAGO');
	
	logoCon = new Image();
	logoCon.src = 'logocongreso.png';
	doc.addImage(logoCon, 'PNG', 110, 10, 90, 10)
	
   // <<<<<<<<<<<<<< FIN HEADER
	// doc.text(20, 20, 'Comprobante de pago');
	// var imglogo = new Image();
	// imglogo.src = 'congreso.png';
	// doc.addImage(imglogo, 'PNG', 170, 5, 18, 16);
	// imglogo.onload = ()=>{
	// 	doc.addImage(imglogo, 'PNG', 150, 20, 100, 100);
	// };
	doc.setTextColor(0, 0, 0);
	doc.text(150, 40, "FOLIO:   "+cod);
	// doc.text(150, 37, fecha);
	doc.setFontSize(currentF + 1);
	// doc.line(20, 23, 190, 23);
	doc.setFontSize(currentF + 2);
	doc.text(20, 46, 'CONCEPTO:');
	doc.setFontSize(currentF - 1);
	
	var splitTitle = doc.splitTextToSize(con.toLocaleUpperCase(), (doc.internal.pageSize.width * .8));
	doc.text(30, 56, splitTitle);

	// doc.text(30, 50, con.toLocaleUpperCase());
	doc.text(40, 66, "COSTO:   "+cost.toLocaleUpperCase());

	doc.setFontSize(currentF + 2);
	doc.text(20, 76, 'CLIENTE:');
	doc.setFontSize(currentF - 1);
	doc.text(30, 86, cliente);
	rutalogcong = 'congreso.png';
      
	// doc.line(20, 138, 190, 138);
	// doc.setFontSize(currentF - 1);
	// doc.text(130, 142, 'Contacto cobranza:   +52 1 222 806 0581');
	// logos = ['f-con.png', 'f-iesm.png', 'f-udc.png'];
	// m_i = 20;
	// for(i = 0; i < logos.length; i++){
	// 	ch = 7;
	// 	cy = 139;
	// 	var imglogo = new Image();
	// 	imglogo.src = logos[i];
	// 	ch = i==1 ? 9 : 7;
	// 	cy = i==1 ? 138 : 139;
	// 	cw = i==2 ? 11 : 9;
	// 	doc.addImage(imglogo, 'PNG', m_i, cy, cw, ch);
	// 	m_i+=13;
	// }
	// footer
	doc.setFillColor(27, 32, 62);
	doc.rect(0, doc.internal.pageSize.height-30, 250, 30, 'F');
	doc.setTextColor(255, 255, 255);
	doc.setFontSize(currentF + 1);
	doc.text(80,doc.internal.pageSize.height-14, '+52 1 222 806 05 81   congresodecirugiaestetica@gmail.com');
	iesm1 = new Image();
	iesm1.src = 'iesmSL.png';
	// doc.addImage(iesm1, 'PNG', 10, 80, 14,18);
	doc.addImage(iesm1, 'PNG', 10, doc.internal.pageSize.height-23, 14,18);
	udc1 = new Image();
	udc1.src = 'udcBL.png';
	doc.addImage(udc1, 'PNG', 32, doc.internal.pageSize.height-23, 28,15);
	
	var logoC = new Image();
	logoC.src = rutalogcong;
	doc.addImage(logoC, 'PNG', doc.internal.pageSize.width-40, doc.internal.pageSize.height-50, 30, 28);

	doc.setFontSize(13);
  	// end footer
	doc.save(`Comprobante-${cod}.pdf`)
	 

	// var nombre_negocio_pdf = $('#nombre_negocio_pdf').html();
	// var datos_pago_pdf = con;   
	// var detalle_pago_pdf = cost;

	// rutalogopadf = 'logoTUDC.png';
	
	// var logo1 = new Image();
	// logo1.src = rutalogopadf;

	// var totalW = doc.internal.pageSize.width;
	// var imgw = (totalW * .6);
	// var imgh = (logo1.height * imgw) / logo1.width;
	// logo1.onload = function() {
	// 	doc.addImage(logo1, 'PNG', 50, 20, imgw, imgh);
	// };

	// /* var logo = new Image();
	// logo.src = '../assets/images/visamasteramex.png';
	// logo.onload = function() {
	//   doc.addImage(logo, 'PNG', 100, 180,80,20);
	// }; */

	// var specialElementHandlers = {
	// 	'#elementH': function (element, renderer) {
	// 		return true;
	// 	}
	// };

	// doc.fromHTML('Comprobante de pago', 55, 50, {
	// 'width': 170,
	// 'elementHandlers': specialElementHandlers
	// });   
	// doc.fromHTML(con+' '+cost, 85, 70, {
	// 	'width': 170,
	// 	'elementHandlers': specialElementHandlers
	// });   
	// doc.fromHTML('', 20, 90, {
	// 	'width': 170,
	// 	'elementHandlers': specialElementHandlers
	// });  
	// // Save the PDF
	// setTimeout(() => {
	//   doc.save('Comprobante_pago.pdf');
	// }, 1000);
}