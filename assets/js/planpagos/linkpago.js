$(".onlyNumer").on('keypress', function(evt) {
    if (evt.which < 46 || evt.which > 57) {
      evt.preventDefault();
    }
  });


  $('input.cc-num').payment('formatCardNumber').on("keyup change", function(){
  var type = $.payment.cardType( $(this).val() );
  if(type == "visa"){
      $("#type_card_img").attr('src','../udc/img/visa_icon.png')
      $(".company").html("VISA");
      $(".card_cv").attr("data-type", "visa");
  } else if(type == "mastercard"){
      $("#type_card_img").attr('src','../udc/img/mastercard_icon.png')
      $(".company").html("MASTERCARD");
      $(".card_cv").attr("data-type", "mastercard");
  }
  else if(type == "amex"){
      $("#type_card_img").attr('src','../udc/img/american_icon.png')
      $(".company").html("AMERICAN EXPRESS");
      $(".card_cv").attr("data-type", "amex");
  }
  else{
      $("#type_card_img").attr('src','')
      $(".card_cv").attr("data-type", "desconocido");
      $(".company").html("TIPO DE TARJETA");
  }
});



$('input.cc-exp').payment('formatCardExpiry');
$('input.cc-cvc').payment('formatCardCVC');
$(".cvcbtn").click(function(){
  $(".card_cv").toggleClass("flip");
  $(".cvcbtn").toggleClass("flip");
  if($(".cvcbtn").hasClass("flip")){
      $(".cvcbtn").html("INSERTAR DATOS DE LA TARJETA");
  }else{
      $(".cvcbtn").html("INSERTAR CVC");
  }
});


$("button[name='validartarjeta']").on("click",function(event) {
  event.preventDefault();
 var error='';
  if($("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {
    error = error+' <li>El correo electrónico introducido no es correcto.</li>';
  }
  if($('#telefonofactura').val().length <10) {
    error = error+' <li>El numero celular debe contener al menos 10 digitos.</li>';
  }

 if ($('#telefonofactura').val().length <10 || $("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {
    Swal.fire({
      type: 'error',
      title: 'ERROR',
      text: 'Se requiere la siguiente información',
      html: error
    })
  } else{
  $.ajax({
      url: "../assets/data/Controller/planpagos/pagosControl.php",
      type: "POST",
      data: {action:'obtenercuentadepago', id_concepto:$('#tipo_pago').val()},
      beforeSend : function(){
          
      },
      success: function(data){
          data=JSON.parse(data);
          console.log(data.api_key_publica);
          Conekta.setPublicKey(data.api_key_publica);
          Conekta.setLanguage("es")
          try{
                  var $form = $("#form-token-tarjeta");
                  var mes_anio = $(".cc-exp").val().split('/');
                  if(mes_anio[0]){
                      var mes = mes_anio[0].trim();
                      mes = mes.replace(" ", "");
                  }
                  if(mes_anio[1]){
                      var anio = mes_anio[1].trim();
                      anio = anio.replace(" ", "");
                  }
                  $("#mes").val(mes);
                  $("#anio").val(anio);
                  
                  // Previene hacer submit más de una vez
                  $form.find("button[name='validartarjeta']").prop("disabled", true);
                  
                  Conekta.Token.create($form, conektaSuccessResponseHandler, conektaErrorResponseHandler); //v5+
                  return false;

              
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
});


var conektaSuccessResponseHandler = function(token) {
  //console.log(token);
  var $form = $("#form-token-tarjeta");
  referencia = $("#referencia").val();
  /* Inserta el token_id en la forma para que se envíe al servidor html_pago_fin */
  $form.append($("<input type='hidden' id='id_token' name='conektaTokenId'>").val(token.id));//token de la tarjeta del alumno
  
  Planes($form);
  reset_formulario_toker()
};
var conektaErrorResponseHandler = function(response) {
  var $form = $("#form-token-tarjeta");

  /* Muestra los errores en la forma */
  Swal.fire({
    type: 'error',
    title: 'ERROR',
    text: response.message_to_purchaser,
  }).then((value)=>{
    window.location.reload();
})


  //$form.find(".card-errors").text(response.message_to_purchaser);
  $form.find("button[name='validartarjeta']").prop("disabled", false);
  reset_formulario_toker()
};



function reset_formulario_toker(){
  $("#form-token-tarjeta")[0].reset();
  $('#descripcionpago').remove();
  $('#descripcionpago').remove();
  $('#totalapagar').remove();
  $('#id_concepto').remove();
  $('#ids_promociones').remove();
  $('#id_token').remove();
  $('#conceptopago').remove();
}


function Planes(formulario){

$.ajax({
  type:"POST",
  url:"../assets/data/Controller/planpagos/linksPagosControl.php",
  data:{
      action:'realizar_pago_link',
      token:$("#id_token").val(),
      nombre_concepto:$("#nombre_concepto").val(),
      nombretarjeta:$("#nombretarjeta").val(),
      nombre_cliente:$("#nombreclientespei").val(),
      email:$("#email").val(),
      telefonofactura:$("#telefonofactura").val(),
      tipo_moneda: $("#Moneda").val(),
      precio:$("#precio").val(),
      totalapagar:$("#totalapagar").val(),
      promociones:($("#ids_promociones").length > 0) ? $("#ids_promociones").val() : null,
      descripcionpago:$("#descripcionpago").val(),
      id_concepto:$("#tipo_pago").val(),
  },
  success:function(msj){
    msj=JSON.parse(msj);
      if(msj.estatus==1){
        Swal.fire({
          type: 'success',
          title: 'El pago se realizo correctamente',
          html: '<div id="content"> <hr><div class="text-center" id="nombre_negocio_pdf"><h3>'+ msj.nombre_negocio +'</h3></div> <br> <div id="datos_pago_pdf"><h4>DATOS DE PAGO:</h4></div><br> <hr> <div id="detalle_pago_pdf"><ul style="text-align: left"> <li><b>Concepto</b>: '+ msj.nombre_concepto+'</b></li> <li><b>Nombre</b>: '+ msj.nombre_cliente+'</li> <li><b>Email</b>: '+ msj.email_cliente+'</li> <li><b>Teléfono</b>: '+ msj.telefono_cliente+'</li> <li><b>Referencia de pago</b>: '+ msj.referencia_pago+'</li> <li><b>Código de autorización</b>: '+ msj.codigo_autorizacion+'</li> <li><b>Orden de compra</b>: '+ msj.order_id+'</li> <li><b>Fecha de pago</b>: '+ msj.fecha_pago+'</li> <li><b>Monto pagado</b>: $'+ msj.monto_pagado+' '+msj.moneda+'</li> <p><b>los pagos realizados en este medio tendrán que ser notificados con su ejecutiva de callcenter o en tu panel de alumno.</b></p> </ul></div> </div> <button class="btn btn-primary btn-block mg-b-10" id="comprobante_pago_pdf">Descargar comprobante de pago</button>',
          showConfirmButton: false,
        }).then((value)=>{
          location.reload();
        })
      }else{
        Swal.fire(
            'Ocurrio un error al procesar el pago',
            msj.error,
            'danger'
          ).then((value)=>{
            location.reload();
          })			
      }
      formulario.find("button[name='validartarjeta']").prop("disabled", false);
  },
  complete: function(){
    $( "#comprobante_pago_pdf" ).click(function() {
      var rutalogopadf ='';
      if($("#tipo_pago").val()==24){
        rutalogopadf='logoTCONACON.png';
      }else if($("#tipo_pago").val()==31){
        rutalogopadf='iesmSL.png';
      }else{
        rutalogopadf='logoTUDC.png';
      }
      var doc = new jsPDF();

      // <<<<<<<<<<<<<< HEADER
       doc.setFillColor(27, 32, 62);
       doc.rect(0, 0, 250, 30, 'F');
       doc.setTextColor(255, 255, 255);
       
       doc.setFontSize(15)
       doc.text(15,15, 'COMPROBANTE DE PAGO');
       
       logoCon = new Image();
       logoCon.src = 'logocongreso.png';
       logoCon.onload = function(){
           doc.addImage(logoCon, 'PNG', 110, 10, 90, 10)
       }
      // <<<<<<<<<<<<<< FIN HEADER

       nombre_negocio_pdf = $('#nombre_negocio_pdf').text();
       datos_pago_pdf = $('#datos_pago_pdf').html();   
       detalle_pago_pdf = $("#detalle_pago_pdf").html();
        // rutalogopadf = 'iesmSL.png';
       /*  var logo1 = new Image();
      logo1.src = rutalogopadf;
      logo1.onload = function() {
        if(rutalogopadf=='iesmSL.png'){
          doc.addImage(logo1, 'PNG', 96, 25, 21,28);
        }else{
          doc.addImage(logo1, 'PNG', 50, 20, 104,28);
        }
      }; */

      
      rutalogcong = 'congreso.png';
      var logoC = new Image();
      logoC.src = rutalogcong;
      logoC.onload = function() {
        doc.addImage(
            logoC, 
            'PNG', 
            doc.internal.pageSize.width-40, 
            doc.internal.pageSize.height-50, 
            30,
            28);
      };


       specialElementHandlers = {
          '#elementH': function (element, renderer) {
              return true;
          }
      };
      doc.setTextColor(0, 0, 0);
      doc.text(((doc_width/2)-(widthTit/2)), 55, nombre_negocio_pdf);
      // var widthTit = doc.getTextWidth(nombre_negocio_pdf);
      var doc_width = doc.internal.pageSize.width;
      // doc.fromHTML(nombre_negocio_pdf, 55, 50, {
      // 'width': 170,
      // 'elementHandlers': specialElementHandlers
      // });   
      doc.text(((doc_width/2)-(widthTit/2)), 55, nombre_negocio_pdf);
      doc.fromHTML(datos_pago_pdf, 85, 70, {
          'width': 170,
          'elementHandlers': specialElementHandlers
      });   
        xy = 100;
        doc.setTextColor(0, 0, 0);
       $("#detalle_pago_pdf li").each((x,y)=>{
           doc.setFontSize(13);
           var lix = y.innerText.split(':');
           var concep_r = doc.getTextWidth(lix[0]);
           doc.text(20, xy, lix[0])
           var concep_width = doc.getTextWidth(lix[1]);
           doc.setFontSize(12);
           doc.text(
               (doc.internal.pageSize.width-10 - concep_width), 
               xy, 
               lix[1]
           )
           doc.line(concep_r+21, xy, (doc.internal.pageSize.width-11 - concep_width), xy);
           xy+=8
       })
        doc.setFontSize(13);
      // footer
         iesm1 = new Image();
        iesm1.src = 'iesmSL.png';
        iesm1.onload = function() {
          doc.addImage(iesm1, 'PNG', 10, doc.internal.pageSize.height-23, 14,18);
        };
         udc1 = new Image();
        udc1.src = 'udcBL.png';
        udc1.onload = function() {
          doc.addImage(udc1, 'PNG', 32, doc.internal.pageSize.height-23, 28,15);
        };
        
        doc.setFontSize(13);
        doc.setFillColor(27, 32, 62);
        doc.rect(0, doc.internal.pageSize.height-30, 250, 30, 'F');
        doc.setTextColor(255, 255, 255);
        doc.text(80,doc.internal.pageSize.height-14, '+52 1 222 806 05 81   congresodecirugiaestetica@gmail.com');
      // end footer

      // Save the PDF
      setTimeout(() => {
        doc.save('Comprobante_pago.pdf');
      }, 1000);
    });
}
});

}

$( "#mostrar-pago-spei" ).click(function() {
    $('#mostrar-spei').show();
    $('.ocultar-mostrar-ficha-tarjeta').hide();
    fData = {
			action : "generar_ficha_spei_link",
			id_tipo_pago_concepto : $("#tipo_pago").val(),
			nombre_concepto : $("#nombre_concepto").val(),
			monto_pago : $("#totalapagar").val(),
      tipo_moneda: $("#Moneda").val().toUpperCase(),
      nombreclientespei : $("#nombreclientespei").val(),
      emailclientespei : $("#emailclientespei").val(),
		}
    $.ajax({
			url: "../assets/data/Controller/planpagos/pagosControl.php",
			type: "POST",
			data: fData,
			beforeSend : function(){
				$(".outerDiv_S").css("display", "block")
			},
			success: function(data){
				try{
					data = JSON.parse(data);
					console.log(data)
					$("#monto_pago_spei").html(data.monto)
					$("#tipo_moneda").html(data.tipo_moneda)
					$("#referencia_pago_spei").html(data.CLABE)
					$("#nombre_alumno_banorte").html(data.nombre_prospecto);
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
  
  });

  $( "#no_mostrar_boton_tarjeta" ).hide();

  $( "#mostrar-tarjeta-credito" ).click(function() {
    $('#mostrar-spei').hide();
    $('.ocultar-mostrar-ficha-tarjeta').show();
  });

  $( ".boton-pago-spei" ).click(function() {
    $('.boton-pago-spei').hide();
    $('.no_mostrar_boton_tarjeta').show();
    $('.boton-pago-tarjeta').show();
    $('#no_mostrar_boton_tarjeta').show();
  });

  $( ".boton-pago-tarjeta" ).click(function() {
    $('.boton-pago-tarjeta').hide();
    $('.boton-pago-spei').show();
  });