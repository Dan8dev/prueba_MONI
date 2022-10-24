
   initPayPalButton_plan_semestral()
   initPayPalButton_plan_anual()
 
function initPayPalButton_plan_semestral() {
    paypal.Buttons({
      style: {
        shape: 'pill',
        color: 'blue',
        layout: 'vertical',
        label: 'pay',
  
      },
      //description
      //monto y tipo moneda
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{"description":"PLAN-SEMESTRAL","amount":{"currency_code":"MXN","value":1000 }}]
        });
      },
  
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
          // if(details.status == "COMPLETED"){
          //   detallePago = {
          //     referencia : details.id
          //   }
          // }
          RegistrarPagoafiliacion(JSON.stringify(details), 'MEMBRESIA-SEMESTRAL');
          //alert('Transaction completed by ' + details.payer.name.given_name + '!');
        });
      },
  
      onError: function(err) {
        console.log(err);
      }
    }).render('#paypal-button-container-plan-semestral');
  }
  
  function initPayPalButton_plan_anual() {
    paypal.Buttons({
      style: {
        shape: 'pill',
        color: 'blue',
        layout: 'vertical',
        label: 'pay',
        
      },
  
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{"description":"PLAN-ANUAL","amount":{"currency_code":"MXN","value":2000 }}]
        });
      },
  
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
          // idtransaction
          RegistrarPagoafiliacion(JSON.stringify(details), 'MEMBRESIA-ANUAL');
          //alert('Transaction completed by ' + details.payer.name.given_name + '!');
        });
      },
  
      onError: function(err) {
        console.log(err);
      }
    }).render('#paypal-button-container-plan-anual');
  }
  
  function RegistrarPagoafiliacion(stringPago, plan){
    if (plan=='MEMBRESIA-ANUAL') {
      var monto = 2000;
    }
    if (plan=='MEMBRESIA-SEMESTRAL') {
      var monto = 1000;
    }
    fData = {
      action: 'registrar_pago_afiliacion',
      detalle : stringPago,
      plan_pago : plan,
      monto : monto
    }
    $.ajax({
      url: "../data/CData/afiliadosControl.php",
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