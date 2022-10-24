  list_laboral = [];
  list_conocim = [];
  $.extend($.validator.messages, {
    required: "Este campo es requerido"
  });

  $(document).ready(function(){
    'use strict';
    consultar_laboral();
    consultar_conocimiento();
    consultar_grado();
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
           // Step 3 form validation
           if(currentIndex === 2) {
               
            return true;
          
          }
           // Step 4 form validation
           if(currentIndex === 3) {
               
            return true;
          
          }
          // Step 5 form validation
          if(currentIndex === 4) {
               
            return true;
          
          }

        // Always allow step back to the previous step even if the current step is not valid.
        } 
        else { //alrecorrer el wizard de derecha a izquierda

          // Step 3 form validation y envio de informacion al dar clic siguiente
          if(currentIndex === 4) {
               
            return true;
          
          }
          if(currentIndex === 3) {
            
            return true;
          }

          if(currentIndex === 2) {
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

          
        }
      },
      onFinished: function (event, currentIndex){
        if(currentIndex == 1){
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
            } else { email.validate(); }
        }
        swal({
          title: "Exito!",
          text: "Tu información se actualizó correctamente",
          icon: "success",
          });
      }
    });

    $('.fc-datepicker').datepicker({
	  dateFormat:'yy-mm-dd',
      showOtherMonths: true,
      selectOtherMonths: true
    }); 
  });



// elm = null;
  function salvar_datos(nodo){
    // elm = nodo
    formulario = $(nodo).parent().parent().find("form")
    if(formulario.valid()){
      fData = new FormData(formulario[0]);
      fData.append("action", formulario.attr("action"))
      $.ajax({
        url: "data/CData/afiliadosControl.php",
        type: "POST",
        data: fData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          try{
            hist_lab = JSON.parse(datos);
            if(hist_lab.estatus == "ok"){
              mostrar_alerta("success", "La información ha sido actualizada correctamente.")
              consultar_laboral()
              consultar_conocimiento();
              consultar_grado();
            }else{
              mostrar_alerta("warning", "Ha ocurrido un error, informe al administrador.")
            }
            if(hist_lab.error == 'no_session'){
              swal({
                title: "Vuelve a iniciar sesión!",
                text: "La informacion no se actualizó",
                icon: "info",
                });
                setTimeout(function(){
                  window.location.replace("index.php");
                }, 2000);
            }
          }catch(e){
            console.log(e)
            console.log(data)
          }
          
        }
      });

      formulario[0].reset();
    }
  }

  function consultar_grado(){
    $.ajax({
      url: "data/CData/afiliadosControl.php",
      type: "POST",
      data: {action:'consultar_grado'},
      success: function(datos)
      {
        try{
          gradoe = JSON.parse(datos)
          if(gradoe.estatus == 'ok'){
            list_gradoe = [];
            html_c = "";
            for (i = 0; i < gradoe.data.length; i++) {
              list_gradoe.push(gradoe.data[i]);
              html_c += `<tr order="${gradoe.data[i].idGrado}">
                        <td>${gradoe.data[i].grado}</td>
                        <td>${gradoe.data[i].titulo}</td>
                        <td><a class="text-primary" href="javascript:void(0)" onclick="editar_grado(this, 'edit')"><i class="fa fa-edit"></i></td>
                        <td><a class="text-primary" href="javascript:void(0)" onclick="editar_grado(this, 'elimina')"><i class="fa fa-trash"></i></td>
                      </tr>`
            }

            $("#table_grado").html(html_c)
          }
        }catch(e){
          console.log(e)
          console.log(data)
        }
      }
    });
  }

  function consultar_laboral(){
    $.ajax({
      url: "data/CData/afiliadosControl.php",
      type: "POST",
      data: {action:'consultar_exp'},
      success: function(datos)
      {
        try{
          exp_l = JSON.parse(datos)
          if(exp_l.estatus == 'ok'){
            list_laboral = [];
            html_d = "";
            for (i = 0; i < exp_l.data.length; i++) {
              list_laboral.push(exp_l.data[i]);
              html_d += `<tr order="${exp_l.data[i].idExperiencia}">
                        <td>${exp_l.data[i].empresa} <br> <small><i>Desde:${exp_l.data[i].fechaIngreso} - Hasta: ${exp_l.data[i].fechaEgreso}</i></small></td>
                        <td><a class="text-primary" href="javascript:void(0)" onclick="editar_exp(this, 'edit')"><i class="fa fa-edit"></i></td>
                        <td><a class="text-primary" href="javascript:void(0)" onclick="editar_exp(this, 'elimina')"><i class="fa fa-trash"></i></td>
                      </tr>`
            }

            $("#table_experiencia").html(html_d)
          }
        }catch(e){
          console.log(e)
          console.log(data)
        }
      }
    });
  }

  function consultar_conocimiento(){
    $.ajax({
      url: "data/CData/afiliadosControl.php",
      type: "POST",
      data: {action:'consultar_conocimiento'},
      success: function(datos)
      {
        try{
          conocim = JSON.parse(datos)
          if(conocim.estatus == 'ok'){
            list_conocim = [];
            html_c = "";
            for (i = 0; i < conocim.data.length; i++) {
              list_conocim.push(conocim.data[i]);
              html_c += `<tr order="${conocim.data[i].idExperiencia}">
                        <td>${conocim.data[i].nombreEvento} <small><i>${conocim.data[i].fechaIngreso}</i></small><br><spam style="font-size:x-small;">${conocim.data[i].funcion}</spam></td>
                        <td><a class="text-primary" href="javascript:void(0)" onclick="editar_conocimiento(this, 'edit')"><i class="fa fa-edit"></i></td>
                        <td><a class="text-primary" href="javascript:void(0)" onclick="editar_conocimiento(this, 'elimina')"><i class="fa fa-trash"></i></td>
                      </tr>`
            }

            $("#table_conocimiento").html(html_c)
          }
        }catch(e){
          console.log(e)
          console.log(data)
        }
      }
    });
  }
  function editar_grado(grado_itm, tipo){
    comocim = list_gradoe.find(elm => elm.idGrado == $(grado_itm).parent().parent().attr("order"))
    
    if(tipo == 'edit'){
    $("#item_grado").val(comocim.idGrado);

    $("#gradoestudios_edit").val(comocim.grado);
    $("#tipoLicen_edit").val(comocim.titulo);
    $("#cedulap_edit").val(comocim.cedula);

    $("#modal_editar_grado").modal("show")
  }else if(tipo == 'elimina'){
    $("#tipo_elim").val("grado");
    $("#reg_elim").val(comocim.idGrado);
    $("#nombre_elimina").html(comocim.grado+" "+comocim.titulo);

    $("#modal-confirm-elimina").modal("show")
  }
}

  function editar_exp(exp_itm, tipo){
      item = list_laboral.find(elm => elm.idExperiencia == $(exp_itm).parent().parent().attr("order"))
    if(tipo == 'edit'){
      $("#item_lab").val(item.idExperiencia);
      $("#inicio_laboral_edit").val(item.fechaIngreso);
      $("#fin_laboral_edit").val(item.fechaEgreso);
      $("#empresa_edit").val(item.empresa);
      $("#puesto_edit").val(item.puesto);
      $("#actividadLaboral_edit").val(item.activiadLaboral);

      $("#modal_editar_lab").modal("show")
    }else if(tipo == 'elimina'){
      $("#tipo_elim").val("exp_lab");
      $("#reg_elim").val(item.idExperiencia);
      $("#nombre_elimina").html(item.empresa+" "+item.puesto);

      $("#modal-confirm-elimina").modal("show")
    }
  }

  function editar_conocimiento(conocim_itm, tipo){
      comocim = list_conocim.find(elm => elm.idExperiencia == $(conocim_itm).parent().parent().attr("order"))
    if(tipo == 'edit'){
      $("#item_conocim").val(comocim.idExperiencia);

      $("#inicio_conocim_edit").val(comocim.fechaIngreso);
      $("#fin_conocim_edit").val(comocim.fechaEgreso);
      $("#evento_nom_edit").val(comocim.nombreEvento);
      $("#participacion_edit").val(comocim.funcion);
      $("#detalle_participacion_edit").val(comocim.detalles);

      $("#modal_editar_conocimiento").modal("show")
    }else if(tipo == 'elimina'){
      $("#tipo_elim").val("conocimiento");
      $("#reg_elim").val(comocim.idExperiencia);
      $("#nombre_elimina").html(comocim.nombreEvento+" "+comocim.funcion);

      $("#modal-confirm-elimina").modal("show")
    }
  }

  $("#form_editar_laboral").on("submit", function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action','actualizar_info_laboral');
    $.ajax({
        url: "data/CData/afiliadosControl.php",
        type: "POST",
        data: fData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          try{
            upd_lab = JSON.parse(datos);
            if(upd_lab.estatus == "ok"){
              mostrar_alerta("success", "La información ha sido actualizada correctamente.")
            }else{
              mostrar_alerta("warning", "Ha ocurrido un error, informe al administrador.")
            }
            if(upd_lab.error == 'no_session'){
              swal({
                title: "Vuelve a iniciar sesión!",
                text: "La informacion no se actualizó",
                icon: "info",
                });
                setTimeout(function(){
                  window.location.replace("index.php");
                }, 2000);
            }
          }catch(e){
            console.log(e)
            console.log(data)
          }
          consultar_laboral()
          $("#modal_editar_lab").modal("hide")
          $("#form_editar_laboral")[0].reset()
        }
      });
  })

  $("#confirma_elimina").on("click", function(){
    $.ajax({
        url: "data/CData/afiliadosControl.php",
        type: "POST",
        data: {action:'eliminar_reg', tipo:$("#tipo_elim").val(), regid: $("#reg_elim").val()},
        success: function(datos)
        {
          try{
            elim = JSON.parse(datos)
            if(elim.estatus == 'ok'){
              mostrar_alerta("success", "La información ha sido actualizada correctamente.")
            }
            if(elim.error == 'no_session'){
              swal({
                title: "Vuelve a iniciar sesión!",
                text: "La informacion no se actualizó",
                icon: "info",
                });
                setTimeout(function(){
                  window.location.replace("index.php");
                }, 2000);
            }
          }catch(e){
            console.log(e)
            console.log(data)
          }
        }
      })

    consultar_laboral()
    consultar_conocimiento()
    consultar_grado()
    $("#modal-confirm-elimina").modal("hide")
  });

  $("#form_editar_conocimiento").on("submit", function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action','actualizar_info_conocimiento');
    $.ajax({
        url: "data/CData/afiliadosControl.php",
        type: "POST",
        data: fData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          try{
            upd_lab = JSON.parse(datos);
            if(upd_lab.estatus == "ok"){
              mostrar_alerta("success", "La informaci�n ha sido actualizada correctamente.")
            }else{
              mostrar_alerta("warning", "Ha ocurrido un error, informe al administrador.")
            }
            if(upd_lab.error == 'no_session'){
              swal({
                title: "Vuelve a iniciar sesión!",
                text: "La informacion no se actualizó",
                icon: "info",
                });
                setTimeout(function(){
                  window.location.replace("index.php");
                }, 2000);
            }
          }catch(e){
            console.log(e)
            console.log(data)
          }
          consultar_conocimiento()
          $("#modal_editar_conocimiento").modal("hide")
          $("#form_editar_conocimiento")[0].reset()
        }
      });
  })

  $("#form_editar_grado").on("submit", function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action','actualizar_info_grado');
    $.ajax({
        url: "data/CData/afiliadosControl.php",
        type: "POST",
        data: fData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          try{
            upd_lab = JSON.parse(datos);
            if(upd_lab.estatus == "ok"){
              mostrar_alerta("success", "La información ha sido actualizada correctamente.")
            }else{
              mostrar_alerta("warning", "Ha ocurrido un error, informe al administrador.")
            }
            if(upd_lab.error == 'no_session'){
              swal({
                title: "Vuelve a iniciar sesión!",
                text: "La informacion no se actualizó",
                icon: "info",
                });
                setTimeout(function(){
                  window.location.replace("index.php");
                }, 2000);
            }
          }catch(e){
            console.log(e)
            console.log(data)
          }
          consultar_grado()
          $("#modal_editar_grado").modal("hide")
          $("#form_editar_grado")[0].reset()
        }
      });
  })

  function mostrar_alerta(tipo, mensaje){
    $("#alert_2").removeClass("alert-success");
    $("#alert_2").removeClass("alert-warning");

    $("#alert_2").addClass(`alert-${tipo}`);
    $("#alert_text").html(mensaje);
    $('#alert_2').show();
    
    setTimeout(function(){ 
        $('#alert_2').hide() 
      }, 
      4000
    );
  }

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
		  if(data1.pais != ''){
		  $("#pais option[value="+data1.pais+"]").attr("selected",true);
		  }
          
          $('#ciudad').val(data1.ciudad)
          $('#colonia').val(data1.colonia)
          $('#calle').val(data1.calle)
          $('#codigopostal').val(data1.cp)
          $('#email').val(data1.email)
          $('#celular').val(data1.celular)
          $('#facebook').val(data1.facebook)
          $('#instagram').val(data1.instagram)
          $('#twitter').val(data1.twitter)

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
					  if(data1.estado != ''){
						$("#estado option[value="+data1.estado+"]").attr("selected",true);
					  }
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






