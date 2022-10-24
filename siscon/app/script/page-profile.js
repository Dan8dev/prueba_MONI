function enviardoc() {
  var usuarioss = $("#usuariosesion").val();
  var docus = $("#identificacion_anverso");


  $("#archivosverifica").on("submit", function(e) {

    fData = new FormData(this);
    fData.append("action", "DocumentosVerificación");
    fData.append("idAlumno", usuarioss);



    e.preventDefault();

    $.ajax({
      type: "POST",
      url: "data/CData/documentosControl.php",
      data: fData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(response) {
        swal(response.info);
        checkVerification(ussuarioss);
        location.reload();
      }
    })

  });
}
var usuarioss = $("#usuariosesion").val();

function fcargar() {
  //console.log("Habilitanding")
  $("#btnform").prop("disabled", false);
}

$(document).ready(function() {

  var usuarioss = $("#usuariosesion").val();
  //console.log(usuarioss);

  checkVerification(usuarioss);

  //console.log('<?php echo $clave_compartir; ?>');
  if (navigator.share) {
    $("#a_shareCode").on("click", function(e) {
      e.preventDefault();
      navigator.share({
        //text: 'https://conacon.org/moni/siscon/cv/?perfil=<?php echo $usuario['data']['id_afiliado']; ?>'
      })
    })
  } else {

    $("#a_shareCode").prop("href", "whatsapp://send?text=https://conacon.org/moni/siscon/cv/?perfil=<?php echo $usuario['data']['id_afiliado']; ?>")
    $("#a_shareCode").attr("data-action", "share/whatsapp/share")
    //href="whatsapp://send?text=<?php #echo($usuario["persona"]["codigo"]); 
    //data-action="share/whatsapp/share"
  }
});

$(function() {
    var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },

    Windows: function() {
        alert(navigator.userAgent.match(/IEMobile/i));
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
    }
    if (isMobile.iOS()) {
    alert('1.- Click en el botón compartir' + "\n2.- Añadir a pantalla de inicio");
    }
});

function enviardoc() {
    var usuarioss = $("#usuariosesion").val();
    var docus = $("#identificacion_anverso");


    $("#archivosverifica").on("submit", function(e) {

    fData = new FormData(this);
    fData.append("action", "DocumentosVerificación");
    fData.append("idAlumno", usuarioss);



    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: fData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: function(response) {
        swal(response.info);
        checkVerification(usuarioss);
        //location.reload();
        }
    });

    });
}

var usuarioss = $("#usuariosesion").val();
var listaDocs = [];

function fcargar(id_doc) {
  var id_afil = $("#id_afiliado").val();
  var doc;
  listaDocs.forEach(element=> {
    if(element.id_documento == id_doc){
      doc = element;
    }
  });
  if(doc!= null){
    var archivo = $("#"+doc.nomenclatura_documento).val();

    if(doc.id != null && doc.validacion == 2){
      var formData = new FormData();
      formData.append('modFile', $('#usuariosesion')[0]);
      formData.append('idDocument',doc.id_documento);
      formData.append('idModify',id_afil);
      formData.append('action','modificarDocumento');

      //update doc
      $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: formData,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        dataType: "JSON",
        success: function(response) {
          //console.log(response);
          swal("Se ha reenviado tu documento, espera su validación");
          $("#"+doc.nomenclatura_documento).val("");
          $("#"+doc.nomenclatura_documento).addClass("hidden");
        }
      });
    }
  }
  //console.log("Habilitanding");
  $("#btnform").prop("disabled", false);
}

function updateDoc(doc){

}

function checkVerification(id_prospecto){
 $.ajax({
    type: "POST",
    url: "data/CData/documentosControl.php",
    data: {
      action: "ConsultaDocVerificacion",
      idAlumno: id_prospecto,
    },
    dataType: "JSON",
    success: function(response) {
      //console.log(response);
      var data = '<div class="row">';
      //console.log("documentos enrtregados");
      var contador =0;
      listaDocs = response;
      response.forEach(element => {
        //console.log(element.nomenclatura_documento);
        var mensaje = "";
        var clase = "";
        switch(element.validacion){
          case null: 
            mensaje= "";
            //console.log("Validacion nula");
            break;
          case '0': 
            mensaje= "Ya haz enviado este documento a revisión";
            clase = "hidden";
           // console.log("Validacion pendiente");
            break;
          case '1':
            mensaje= "Este documento se ha aprobado";
            clase = "hidden";
            //console.log("Validado");
            break;
          case '2':
            mensaje= "Tu documento fué rechazado, debes subirlo nuevamente";
            //console.log("Rechazado");
          break;
        }
        if(!element.letrero){
          element.letrero = "";
        }
        var fil = '';
        if((element.validacion != '0' && element.validacion != '1') || (element.validacion == null)){
          if(element.validacion == 2){
            fil = `<input name="${element.nomenclatura_documento}" type="file" class="${clase} form-control" id="${element.nomenclatura_documento}" oninput="fcargar(${element.id_documento})">`;
          }
          else{
            fil = `<input name="${element.nomenclatura_documento}" type="file" class="${clase} form-control" id="${element.nomenclatura_documento}" oninput="fcargar()">`;
          }
        }
        else{
          clase = "hidden";
          contador++;
        }
        data = data + `
            <div class="mb-5  col-sm-12 col-md-6">
            <label for="formFile" class="form-label"><b>${element.nombre_documento}</b> | ${element.letrero}</label>
            <div class="flex justify-center items-center w-full">
                <div class="flex flex-col justify-center items-center pt-5 pb-6">
                  <i class="fa fa-tasks fa-3x"></i>
                </div>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 ${clase}"><span class="font-semibold ${clase}">Selecciona tu documento</span></p>
                  <p class="${element.nomenclatura_documento} mb-2 text-sm text-gray-400 dark:text-gray-400"><span class="font-semibold"><span>${mensaje}</span></p>
                ${fil}
            </div>
          </div>`;
      data= data;
      });
      var ocultarForm = "";
      if(contador >= response.length){
        ocultarForm = "hidden";
      }
      else{
        ocultarForm = "";
      }
      data = data+`</div>
                  <div> <button id="btnform" type="submit" class="${ocultarForm} btn btn-primary" form="archivosverifica" value="Submit" onclick="enviardoc()">Enviar a revisión</button> </div> `;
      $("#archivosverifica").html(data);

      $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: {
          action: "EstatusVerificacion",
          idAlumno: usuarioss,
          tipo: "consulta"
        },
        dataType: "JSON",
        success: function(response) {
          if(response.data.verificacion!= null){// Llleva un proceso de verificacion
            switch(response.data.verificacion){
              case '1': 
                $("#cnverificando").removeClass("d-none");
              break;
              case '2':
                $("#cverificada").removeClass("d-none");
              break;
              case '3':
                $("#cnverificada").removeClass("d-none");
              break;
              case '4':
                $("#cverificada").removeClass("d-none");
              break;
            }
          }else{ // No se ha iniciado verificacion
            $("#cnverificada").removeClass("d-none");
          }
        }
      });
    }
  });
}

  var qrcode = new QRCode(document.getElementById("qrcode"), {
    width: 210,
    height: 210
  });

  function makeCode() {
    var elText = document.getElementById("text");
    if (!elText.value) {
      alert("Input a text");
      elText.focus();
      return;
    }
    qrcode.makeCode(elText.value);
  }
  makeCode();
  $("#text").
  on("blur", function() {
    makeCode();
  }).

  on("keydown", function(e) {
    if (e.keyCode == 13) {
      makeCode();
    }
  });
