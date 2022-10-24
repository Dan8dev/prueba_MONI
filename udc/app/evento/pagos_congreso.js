$(document).ready(function(){
  initPayPalButton_acceso_general()
  initPayPalButton_apartar_lugar()
});

function initPayPalButton_acceso_general() {
  paypal.Buttons({
    style: {
      shape: 'pill',
      color: 'blue',
      layout: 'vertical',
      label: 'pay',
      
    },

    createOrder: function(data, actions) {
      return actions.order.create({
        purchase_units: [{"description":"CISMAC GRAL","amount":{"currency_code":"MXN","value":3000 }}]
      });
    },

    onApprove: function(data, actions) {
      return actions.order.capture().then(function(details) {
        // idtransaction
        RegistrarPago(JSON.stringify(details), 'ACC-GENERAL_PRE-CONGRESO');
        //alert('Transaction completed by ' + details.payer.name.given_name + '!');
      });
    },

    onError: function(err) {
      console.log(err);
    }
  }).render('#paypal-button-container-acceso-general');
}

function initPayPalButton_apartar_lugar() {
  paypal.Buttons({
    style: {
      shape: 'pill',
      color: 'blue',
      layout: 'vertical',
      label: 'pay',
      
    },

    createOrder: function(data, actions) {
      return actions.order.create({
        purchase_units: [{"description":"APARTAR-CISMAC","amount":{"currency_code":"MXN","value":500 }}]
      });
    },

    onApprove: function(data, actions) {
      return actions.order.capture().then(function(details) {
        // idtransaction
        RegistrarPago(JSON.stringify(details), 'APARTAR-CISMAC');
        //alert('Transaction completed by ' + details.payer.name.given_name + '!');
      });
    },

    onError: function(err) {
      console.log(err);
    }
  }).render('#paypal-button-container-apartar-lugar');
}

function RegistrarPago(stringPago, plan){
  fData = {
    action: 'registrar_pago',
    evento : usrInfo.id_evento,
    persona : usrInfo.id_asistente,
    detalle : stringPago,
    plan_pago : plan,
    talleres : ''
  }
  $.ajax({
    url: "data/CData/afiliadosControl.php",
    type: "POST",
    data: fData,
    beforeSend : function(){
      $(".outerDiv_S").css("display", "block")
    },
    success: function(data){
      try{
        respuesta = JSON.parse(data);
        if(respuesta.estatus == 'ok'){
          alert('hemos recibido la información de su pago, nos pondremos en contacto con usted brevemente.')
        }else{
          alert('ha ocurrido un error al intentar registrar su pago, contacte a soporte técnico')
        }
        console.log(respuesta)
		  window.location.reload(true);
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
  
  })
}
