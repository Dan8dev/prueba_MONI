//on clic des´pegar ficha de pago en oxxo
$("#generar_ficha_pago").on('click', function(){
	var monto_f = parseFloat($('#monto_a_pagar').text().replace(/\$|,/g, "")) + parseFloat($("#monto_con_recargo").text().replace(/\$|,/g, ""));
	if(monto_f < 16) {
		swal('No se puede generar ficha de pago OXXO, el monto a pagar debe ser mayor a $15 MXN');
		return;
	}
	if(monto_f > 10000) {
		swal('No se puede generar ficha de pago OXXO, el monto a pagar debe ser menor a $10,000 MXN');
		return;
	}
	$('#mostrar_ficha_pago_spei').hide();
    $('#mostrar_ficha').fadeIn();
      $('.ocultar-mostrar-ficha-tarjeta').hide();		
		fData = {
			action : "generar_ficha_oxxo",
			id_prospecto : 'sesion_user',
			id_tipo_pago_concepto : $("#tipo_pago").val(),
			nombre_concepto : $("#nombre_concepto").text(),
			monto_pago : $("#monto_a_pagar").text(),
			monto_con_recargo: $("#monto_con_recargo").text(),
			monto_con_promocion: $("#monto_con_promocion").text(),
			id_promocion: $("#ids_promociones").val(),
		}
		$.ajax({
			url: "../../assets/data/Controller/planpagos/pagosControl.php",
			type: "POST",
			data: fData,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				try{
					data = JSON.parse(data);
					console.log(data)
					$("#monto_pago").html(data.monto)
					$("#tipo_moneda").html(data.tipo_moneda)
					$("#referencia_pago").html(data.referencia)
					$("#concepto_de_pago").html(data.nombre_producto)
					$("#codigo_barras-reference").attr("src", '../../assets/images/bar_codes_oxxo/'+data.url_codigo_barras);
					//llenar formulario para enviar a pdf ficha de pago conekta
					$("#monto_pago_ficha").val(data.monto)
					$("#tipo_moneda_ficha").val(data.tipo_moneda)
					$("#referencia_ficha").val(data.referencia)
					$("#nombre_concepto_ficha").val(data.nombre_producto)
					$("#bar_code_ficha").val(data.url_codigo_barras);
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
 

})


$("#generar_ficha_pago_ventanilla").on('click', function(){
    $('#mostrar_ficha_pago_ventanilla').fadeIn();
      $('.ocultar-mostrar-ficha-tarjeta').hide();
	  $('#mostrar_ficha_pago_spei').hide();
	  $('#mostrar_ficha').hide();
		fData = {
			action : "generar_ficha_banorte",
			id_prospecto : 'sesion_user',
			id_tipo_pago_concepto : $("#tipo_pago").val(),
			nombre_concepto : $("#nombre_concepto").text(),
			monto_pago : $("#monto_a_pagar").text(),
			monto_con_recargo: $("#monto_con_recargo").text(),
			monto_con_promocion: $("#monto_con_promocion").text(),
			id_promocion: $("#ids_promociones").val(),
		}
		$.ajax({
			url: "../../assets/data/Controller/planpagos/pagosControl.php",
			type: "POST",
			data: fData,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				try{
					data = JSON.parse(data);
					console.log(data)
					$("#monto_pago_banorte").html(data.monto)
					$("#tipo_moneda").html(data.tipo_moneda)
					$("#referencia_pago_banorte").html(data.referencia)
					$("#nombre_alumno_banorte").html(data.nombre_prospecto);
					if ($('#nombre_concepto').text().includes('-')==true) {
						const myArray = $('#nombre_concepto').text().split("-");
						conceptofix = myArray[0];
					}else{
						conceptofix = $('#nombre_concepto').text()
					}
					$("#concepto_de_pago_banorte").html(conceptofix)
					//llenar formulario para enviar a pdf ficha de pago banorte
					$("#monto_pago_ficha_banorte").val(data.monto)
					$("#tipo_moneda_ficha_banorte").val(data.tipo_moneda)
					$("#nombre_concepto_ficha_banorte").val(conceptofix)
					$("#referencia_ficha_banorte").val(data.referencia)
					$("#nombre_alumno_banorte_pdf").val(data.nombre_prospecto);
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
 

})

$("#generar_ficha_pago_spei").on('click', function(){
	var monto_f = parseFloat($('#monto_a_pagar').text().replace(/\$|,/g, "")) + parseFloat($("#monto_con_recargo").text().replace(/\$|,/g, ""));
	if(monto_f < 16) {
		swal('No se puede generar ficha de pago SPEI, el monto a pagar debe ser mayor a $15 MXN');
	} else {
		$('#mostrar_ficha').hide();
    	$('#mostrar_ficha_pago_spei').fadeIn();
      	$('.ocultar-mostrar-ficha-tarjeta').hide();	
	  	$('#mostrar_ficha_pago_ventanilla').hide();
		fData = {
			action : "generar_ficha_spei",
			id_prospecto : 'sesion_user',
			id_tipo_pago_concepto : $("#tipo_pago").val(),
			nombre_concepto : $("#nombre_concepto").text(),
			monto_pago : $("#monto_a_pagar").text(),
			monto_con_recargo: $("#monto_con_recargo").text(),
			monto_con_promocion: $("#monto_con_promocion").text(),
			id_promocion: $("#ids_promociones").val(),
		}
		$.ajax({
			url: "../../assets/data/Controller/planpagos/pagosControl.php",
			type: "POST",
			data: fData,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				try{
					data = JSON.parse(data);
					$("#monto_pago_spei").html(data.monto)
					$("#tipo_moneda").html(data.tipo_moneda)
					$("#referencia_pago_spei").html(data.CLABE)
					$("#nombre_alumno_banorte").html(data.nombre_prospecto);
					if ($('#nombre_concepto').text().includes('-')==true) {
						const myArray = $('#nombre_concepto').text().split("-");
						conceptofix = myArray[0];
					}else{
						conceptofix = $('#nombre_concepto').text()
					}
					$("#concepto_de_pago_banorte").html(conceptofix)
					//llenar formulario para enviar a pdf ficha de pago banorte
					$("#monto_pago_ficha_banorte").val(data.monto)
					$("#tipo_moneda_ficha_banorte").val(data.tipo_moneda)
					$("#nombre_concepto_ficha_banorte").val(conceptofix)
					$("#referencia_ficha_banorte").val(data.referencia)
					$("#nombre_alumno_banorte_pdf").val(data.nombre_prospecto);
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
})

//on clic des´pegar ficha de pago en oxxo
$("#mostrar_pago_tarjeta").on('click', function(){
    $('.ocultar-mostrar-ficha-tarjeta').fadeIn( "slow", function() {
        // Animation complete
      });
      $('#mostrar_ficha').hide();

})
//on clic ocultar ficha pago banorte y muestra pago con tarjeta
$("#mostrar_pago_tarjeta_banorte").on('click', function(){
    $('.ocultar-mostrar-ficha-tarjeta').fadeIn( "slow", function() {
        // Animation complete
      });
      $('#mostrar_ficha_pago_ventanilla').hide();

})

//on clic ocultar ficha pago spei y muestra pago con tarjeta
$("#mostrar_pago_tarjeta_spei").on('click', function(){
    $('.ocultar-mostrar-ficha-tarjeta').fadeIn( "slow", function() {
        // Animation complete
      });
      $('#mostrar_ficha_pago_spei').hide();
})
