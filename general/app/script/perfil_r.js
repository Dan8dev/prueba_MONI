  $(document).ready(function(){
    'use strict';

    $('#wizard2').steps({
      headerTag: 'h3',
      bodyTag: 'section',
      autoFocus: true,
		labels: {
        current: "current step:",
        pagination: "Pagination",
        finish: "Finalizar",
        next: "Siguiente",
        previous: "Anterior",
        loading: "Cargando ..."
    },
      titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
      onStepChanging: function (event, currentIndex, newIndex) {
        console.log(currentIndex);
        if(currentIndex < newIndex) {// al recorrer el wizard de izquierda a derecha
          // Step 1 form validation
          if(currentIndex === 0) {
            var fname = $('#firstname').parsley();
            var lname = $('#apaterno').parsley();

            if(fname.isValid() && lname.isValid()) {
              var nombre = $('#firstname').val()
              var apaterno = $('#apaterno').val()
              var amaterno = $('#amaterno').val()
              var fnacimiento = $('#fnacimiento').val()
              var curp = $('#curp').val()
              $.post("data/CData/afiliadosControl.php",{nombre : nombre, apaterno : apaterno,amaterno : amaterno,fnacimiento : fnacimiento,curp : curp, action : 'datospersonales'}, function(data, status)
                    {
                      
                          
                    });

              return true;
            } 
            else {
              fname.validate();
              lname.validate();
            }
          }

          // Step 2 form validation y envio de informacion al dar clic siguiente
          if(currentIndex === 1) {
            var email = $('#email').parsley();
            if(email.isValid()) {
              var pais = $('#pais').val()
              var estado = $('#estado').val()
              var ciudad = $('#ciudad').val()
              var colonia = $('#colonia').val()
              var calle = $('#calle').val()
              var codigopostal = $('#codigopostal').val()
              var email = $('#email').val()
              var celular = $('#celular').val()
              var facebook = $('#facebook').val()
              var instagram = $('#instagram').val()
              var twitter = $('#twitter').val()
              $.post("data/CData/afiliadosControl.php",{pais : pais,estado : estado,ciudad : ciudad,colonia : colonia,calle : calle,codigopostal : codigopostal,email : email,celular : celular,facebook : facebook,instagram : instagram,twitter : twitter,action : 'contacto'}, function(data, status)
                    {
                      
                          
                    });
              return true;
            } else { email.validate(); }
          }
        // Always allow step back to the previous step even if the current step is not valid.
        } 
        else { //alrecorrer el wizard de derecha a izquierda

          // Step 3 form validation y envio de informacion al dar clic siguiente
          if(currentIndex === 2) {
            var gradoestudios = $('#gradoestudios').val()
            var cedulap = $('#cedulap').val()
			var tipoLicen = $('#tipoLicen').val()
            $.post("data/CData/afiliadosControl.php",{tipoLicen : tipoLicen, gradoestudios : gradoestudios,cedulap : cedulap,action : 'academico'}, function(data, status)
                  {
                    
                       console.log(data); 
                  });
            return true;
          }

          if(currentIndex === 1) {
            var email = $('#email').parsley();
            if(email.isValid()) {
              var pais = $('#pais').val()
              var estado = $('#estado').val()
              var ciudad = $('#ciudad').val()
              var colonia = $('#colonia').val()
              var calle = $('#calle').val()
              var codigopostal = $('#codigopostal').val()
              var email = $('#email').val()
              var celular = $('#celular').val()
              var facebook = $('#facebook').val()
              var instagram = $('#instagram').val()
              var twitter = $('#twitter').val()
              $.post("data/CData/afiliadosControl.php",{pais : pais,estado : estado,ciudad : ciudad,colonia : colonia,calle : calle,codigopostal : codigopostal,email : email,celular : celular,facebook : facebook,instagram : instagram,twitter : twitter,action : 'contacto'}, function(data, status)
                    {
                      
                          
                    });
              return true;
            } 
            else { 
              email.validate();
            }
          }
        }
      },
      onFinished: function (event, currentIndex)
      {
        var gradoestudios = $('#gradoestudios').val()
        var cedulap = $('#cedulap').val()
		    var tipoLicen = $('#tipoLicen').val()
        $.post("data/CData/afiliadosControl.php",{tipoLicen : tipoLicen, gradoestudios : gradoestudios,cedulap : cedulap,action : 'academico'}, function(data, status)
              {
                $('#alertaeditarperfil').show();
                setTimeout(function(){ 
                  $('#alertaeditarperfil').hide() }, 
                  4000);
              });
      }
    });

    $('.fc-datepicker').datepicker({
	  dateFormat:'yy-mm-dd',
      showOtherMonths: true,
      selectOtherMonths: true
    }); 
  });
  function mayusculas(e) {
      e.value = e.value.toUpperCase();
    } 

    $("input[name='file']").on("change", function(){
      var formData = new FormData($("#formulario")[0]);
      var ruta = "data/CData/afiliadosControl.php";
      $.ajax({
          url: ruta,
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function(datos)
          {
            $("#imagenperfil").attr("src","img/afiliados/"+datos+"");
          }
      });
  });

  $("#form-semestral").submit(function(e){
    e.preventDefault();
    var formData = new FormData($("#form-semestral")[0]);
    var ruta = "data/CData/afiliadosControl.php";
    $.ajax({
        url: ruta,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          $('#alert-pago-semestral').show();
          setInterval(function(){
            location.reload();
          },5000);
        }
    });
});

  $("#form-anual").submit(function(e){
    e.preventDefault();
    var formData = new FormData($("#form-anual")[0]);
    var ruta = "data/CData/afiliadosControl.php";
    $.ajax({
        url: ruta,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          $('#alert-pago-anual').show();
          setInterval(function(){
            location.reload();
          },5000);
        }
    });
  });

  Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
  };
  $(document).ready(function() {

    $.ajax({
      url: 'data/CData/afiliadosControl.php?op=obtenerpais',
      dataType: 'json',
      success: function(data) {
        var size = Object.size(data);
        var items = "";
        for(var i = 0; i < size; i++)
        {
          items = items + '<option value="' + data[i].pais + '">' + data[i].pais + '</option>';
        } 
        $('#pais').append(items);
      },
      complete: function () {
        
        $.post("data/CData/afiliadosControl.php",{ action: "obtenerdatosperfil" }, function(data1) {
          var data1 = JSON.parse(data1);
          console.log(data1)
          $('#firstname').val(data1.nombre)
          $('#apaterno').val(data1.apaterno)
          $('#amaterno').val(data1.amaterno)
          $('#fnacimiento').val(data1.fnacimiento)
          $('#curp').val(data1.curp)
          $("#pais option[value="+data1.pais+"]").attr("selected",true);
          $('#ciudad').val(data1.ciudad)
          $('#colonia').val(data1.colonia)
          $('#calle').val(data1.calle)
          $('#codigopostal').val(data1.cp)
          $('#email').val(data1.email)
          $('#celular').val(data1.celular)
          $('#facebook').val(data1.facebook)
          $('#instagram').val(data1.instagram)
          $('#twitter').val(data1.twitter)
          $("#gradoestudios option[value="+ data1.ugestudios +"]").attr("selected",true);
          $('#cedulap').val(data1.cedulap)
          $("#tipoLicen").val(data1.tipoLicenciatura)

            $.post("data/CData/afiliadosControl.php",{ action: "obtenerestado", idpais: $("#pais").val() }, function(data) {
              var data = JSON.parse(data);
              var size = Object.size(data);
                  var items = "";
                  for(var i = 0; i < size; i++){
                    items = items + '<option value="' + data[i].Estado + '">' + data[i].Estado + '</option>';
                  }  
                  $('#estado').html("");
                  $('#estado').append(items);
                  setTimeout(() => {
                    $("#estado option[value="+data1.estado+"]").attr("selected",true);
                  }, 1000);
            });    
        }); 
      }
    });

   $('#pais').change(function() {
    $.ajax({
      url: 'data/CData/afiliadosControl.php?op=obtenerestado&idpais=' + $('#pais').val(),
      dataType: 'json',
      success: function(data) {
        var size = Object.size(data);
        var items = "";
        for(var i = 0; i < size; i++)
        {
          items = items + '<option value="' + data[i].Estado + '">' + data[i].Estado + '</option>';
        }  
        $('#estado').html("");
        $('#estado').append(items);
      }
    });      
   });
  });  






