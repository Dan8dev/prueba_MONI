$(document).ready(function() {
    'use strict';
    Tabla_Afiliados();
    $(document).on('click', '#Crear', function() {
      $('#ModalCrear').modal('show');
    });
    
    var count = 0;
    $("#ModalCrear").on('show.bs.modal', function () {
      $("#acoldinamico").html("");
      agregarcol(count);

  });

  $("#ModalCrear").on('hide.bs.modal', function () {
    count = 0;

});

  function agregarcol(count){
   
      $('#acoldinamico').append(`
      <div class="form-group col-md-12">
        <div class="headder row">
          <div class="col-7">
            <p class="tx-dark tx-dark">Logotipos de los colaboradores &nbsp; </p> <img
                src="img/directorio/logotipocolaboradores.png" align="right">
            <span>(Buena resolución y tamaño 500x500 piexeles tamaño minimo).</span>
          </div>
          <div class="filescol col-5">
            <label class="custom-file">
              <input type="file" name="file_`+count+`" class="custom-file-input tx-dark d-none"><img
                src="img/directorio/subirarchivo.png"> &nbsp; Subir archivo
            </label>
          </div>
        </div>
        <div class="form-group form-inline text-center">
          <div class="col-5">
              <label for="" class="form-label tx-dark">Tipo de constancia:</label>
          </div>
          <label class="radio-inline col-2 tx-dark">
              <input type="radio" name="optradio_`+count+`" checked>Digital
          </label>
          <label class="radio-inline  col-2 tx-dark">
              <input type="radio" name="optradio_`+count+`">Física
          </label>
          <label class="radio-inline  col-2 tx-dark">
              <input type="radio" name="optradio_`+count+`">Ambas
          </label>
          <span class="col-12">(La impresión del formato va
              por parte del centro).
          </span>
        </div>
        <div class="buscarconsejero form-group">
          <div class="col-10">
            <label for="" class="form-label">Datos de contacto del
              organizador del evento:</label>
            <input type="text" class="form-control my-2" name="nomorgani_`+count+`"
              placeholder="Nombre">
            <input type="text" class="form-control my-2" name="telorgani_`+count+`"
              placeholder="Teléfono">
            <button class="col-8"><i class="fa fa-search"></i> Buscar</button>
          </div>
        </div>
    </div>`) ;
    
  }

  $('#agregar_datolcol').on('click', function(){
    count++;
    agregarcol(count);
  });

  $('#form-crear').on("submit",function (e) { 
    const imagen = "img/alert.png"
    console.log("cargando informacion");
    e.preventDefault();
    var fdata= new FormData(this);
    fdata.append("action","crearEvento");

    $.ajax({
      type: "POST",
      url: "../../assets/data/Controller/verificacion/notificacionControl.php",
      data: fdata,
      contentType:false,
      processData:false,
      dataType: "JSON",
      success: function (response) {
        console.log(response);
      }
    });
    swal({
      title: "Tu solicitud de evento será revisada y autorizada por el equipo de CONACON. (Tiempo máximo de respuesta 48 horas)",
      icon: imagen,
      buttons: true,
      dangerMode: true,
    });
    $("#ModalCrear").modal('hide');
    
  });

    $(document).on('click', '#agregar_comision', function() {
      $('#asiganarComision').modal('show');
    });
    $(document).on('click', '#agregar_datolcol', function() {
      $('#ModalAgregardatoscol').modal('show');
    });

    $(document).on('click', '#agregar_archivos', function() {
      $('#ModalAgregarNombre').modal('show');
    });
    obtenerdatosperfil();

    // $('#form-crear').on('submit', function(e) {
    //   const imagen = "img/alert.png"
    //   swal({
    //       title: "Tu solicitud de evento será revisada y autorizada por el equipo de CONACON. (Tiempo máximo de respuesta 48 horas)",
    //       icon: imagen,
    //       buttons: true,
    //       dangerMode: true,
    //     })
    //     .then((willDelete) => {
    //       if (willDelete) {
    //         swal("Evento creado exitosamente", {
    //           icon: "success",
    //         });   
    //         console.log("cargando informacion");
    //           e.preventDefault();
    //           var fdata= new FormData(this);
    //           fdata.append("action","crearEvento");

    //           $.ajax({
    //             type: "POST",
    //             url: "../../assets/data/Controller/verificacion/notificacionControl.php",
    //             data: fdata,
    //             contentType:false,
    //             processData:false,
    //             dataType: "JSON",
    //             success: function (response) {
    //               console.log(response);
    //             }
    //           });     
    //           ///Aqui se envian los datos a la base de datos
    //           // setTimeout(function() {
    //           //   window.location.reload();
    //           // }, 900);
    //          } else {
    //           swal("Tu evento NO SE HA CREADO correctamente", {
    //             icon: 'error'
    //           });
    //           // setTimeout(function() {
    //           //   window.location.reload();
    //           // }, 900);
    //         }
    //     });
    // });

    $('#wizard2').steps({
      headerTag: 'h3',
      bodyTag: 'section',
      autoFocus: true,
      titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
      onStepChanging: function(event, currentIndex, newIndex) {
        if (currentIndex < newIndex) {
          // Step 1 form validation
          if (currentIndex === 0) {
            var fname = $('#firstname').parsley();
            var lname = $('#lastname').parsley();

            if (fname.isValid() && lname.isValid()) {
              return true;
            } else {
              fname.validate();
              lname.validate();
            }
          }

          // Step 2 form validation
          if (currentIndex === 1) {
            var email = $('#email').parsley();
            if (email.isValid()) {
              return true;
            } else {
              email.validate();
            }
          }
          // Always allow step back to the previous step even if the current step is not valid.
        } else {
          return true;
        }
      }
    });

    $('.fc-datepicker').datepicker({
      showOtherMonths: true,
      selectOtherMonths: true
    });
    getNotificaciones();
  });

  function mayusculas(e) {
    e.value = e.value.toUpperCase();
  }

  function getNotificaciones(){

    var id_prospecto = $("#id_prospecto").val();
    var id_afiliado = $("#id_afiliado").val();
    console.log("prospect");
    console.log(id_prospecto);
    $.ajax({
      type: "POST",
      url: "../../assets/data/Controller/verificacion/notificacionControl.php",
      data: {
        action: "listar_notificaciones_prospecto",
        prospecto: id_prospecto,
        tipo: "consulta"
      },
      dataType: "JSON",
      success: function(response) {
        if(response != null && response.length > 0){
          //dibuja notificaciones para cada una, si no, se muestra vacío
          var htm = "";
          response.forEach(element => {
            htm = htm+ 
            `<div class="altasncentros">                    
                <div class="card"> 
                    <div class="card-body"  id="notification_${element.id_notificacion}">
                        <h2 align="right">${element.fecha}</h2>
                        <h3 class="card-title">Notificación:</h3>
                        <h4 class="card-subtitle">${element.titulo}</h>
                        <br>
                        <p class="card-text">${element.mensaje} </p>
                      
                        <button id="veralta${element.id_notificacion}" align="right" onclick=setSawNotification(${element.id_notificacion});>Marcar como leído</button>
                    </div>
                </div><!-- card -->
            </div>`;
          } );
          $("#notification-container").html(htm);
        }
        else{
          $("#notification-container").html(`<h3>No tienes nuevas notificaciones</h3>`);
        }
      }
    })
  }

 

  function Tabla_Afiliados(){
    tablaAfiliados = $("#tableAfiliados").DataTable({
      Processing: true,
      ServerSide: true,
      "lengthMenu": [ 10, 25, 50, 75, 100 ],
      "dom" :'Bfrtip',
          buttons:[{
              extend: "excel",
              className: "btn-primary",
              title:'Afiliados_'+new Date().toLocaleDateString().replace(/\//g, '-')
          }, {
          extend: "pdf",
          title:'Afiliados_'+new Date().toLocaleDateString().replace(/\//g, '-'),
          orientation: 'landscape',
          pageSize: 'LEGAL',
          }, {
              extend: "print",
              title:'Afiliados',
              customize: function ( win ) {
              $(win.document.body).find( 'table' )
              .addClass( 'compact' )
              .css( 'font-size', '15px' )
              .addClass('display')
              // .css( 'background-color', 'blue');
              }
          }],
          'language':{
              'sLengthMenu': 'Mostrar _MENU_ registros',
              'sInfo': 'Mostrando registro del _START_ al _END_ de un total de _TOTAL_ registros',
              'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros',
              'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
              'sSearch': 'Buscar:',
              'sLoadingRecords': 'Cargando',
              'oPaginate':{
                  'sFirst': 'Primero',
                  'sLast': 'Último',
                  'sNext': 'Siguiente',
                  'sPrevious': 'Anterior'
              }
            },
          'bDestroy': true,
          'iDisplayLength': 50,
          'order':[
              [0,'asc']
          ],
      });
      //coment
      $("#txt-search-input").on("keyup",function(e){
        if($(this).val().length>3){
          var busqueda = $(this).val();
          $.ajax({
            type: "POST",
            url: "../../assets/data/Controller/instituciones/institucionesControl.php",
            data: {action: 'busquedaClinicaTotal', search: busqueda},
            dataType: "JSON",
            beforeSend: function(){
            },
            success: function (response) {
              //console.log(response);
              tablaAfiliados.clear();
              $.each(response,(i,reg)=>{
                console.log(reg);
                tablaAfiliados.row.add([reg.nombre,reg.email_contacto,reg.responsable_tel]);
              });
              tablaAfiliados.draw();
            },
            complete: function(){
            }
          });  
        }
      });
  }

  function searchByData(){
    tabla_directorio = $("#table_directorio").DataTable({
      Processing: true,
      ServerSide: true,
      "lengthMenu": [ 10, 25, 50, 75, 100 ],
      "dom" :'Bfrtip',
          buttons:[{
              extend: "excel",
              className: "btn-primary",
              title:'Directorio_'+new Date().toLocaleDateString().replace(/\//g, '-')
          }, {
          extend: "pdf",
          title:'Directorio_'+new Date().toLocaleDateString().replace(/\//g, '-'),
          orientation: 'landscape',
          pageSize: 'LEGAL',
          exportOptions: {
              columns: Columnas
          }
          }, {
              extend: "print",
              title:'Directorio',
              exportOptions: {
                  columns: Columnas,
              },
              customize: function ( win ) {
              $(win.document.body).find( 'table' )
              .addClass( 'compact' )
              .css( 'font-size', '15px' )
              .addClass('display')
              // .css( 'background-color', 'blue');
              }
          }],
      'language':{
          'sLengthMenu': 'Mostrar _MENU_ registros',
          'sInfo': 'Mostrando registro del _START_ al _END_ de un total de _TOTAL_ registros',
          'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros',
          'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
          'sSearch': 'Buscar:',
          'sLoadingRecords': 'Cargando',
          'oPaginate':{
              'sFirst': 'Primero',
              'sLast': 'Último',
              'sNext': 'Siguiente',
              'sPrevious': 'Anterior'
          }
      },
      'bDestroy': true,
      'iDisplayLength': 50,
      'order':[
          [0,'asc']
      ],
      "initComplete": function () {
          tablaDirectorio(Band);
      }
  });
  }


  function setSawNotification(id){
    $('#notification_'+id).css('background-color','white');
  }

  $("#btn-search-centros").on('click', () => {
      var searchText = $("#txt-search-input")
      searchByData("centro");
  }); 
  $("#btn-search-afiliados").on('click', () => {
    var searchText = $("#txt-search-input")
    searchByData("centro");
}); 

  $("#agregar_comision").on('click', ( ) => {
    listar_cargos();
  });

  function listar_cargos(){
    $.ajax({
      type: "POST",
      url: "../../assets/data/Controller/verificacion/verificacionControl.php",
      data: {action:'listar_jerarquias'},
      dataType: "JSON",
      success: function (response) {
        $("#select_jerarquias").html(response.map( elm => `<option value="${elm.id_jerarquia}">${elm.nombre_jerarquia}</option>`))
      }
    });
  }
function doSearch(){
  var busqueda = $("#inp_buscar").val().trim();
  if(busqueda.length == 0){
    return;
  }
  $.ajax({
    type: "GET",
    url: "../../assets/data/Controller/prospectos/search.php",
    data: {search:busqueda, filt:'carreras', data_filt:[1, 13, 6]},
    dataType: "JSON",
    beforeSend: function(){
      $("#button_search").attr('disabled', true);
      $("#button_search").append(`<i class="fa fa-spinner fa-spin ml-4" id="loader-search"></i>`)
    },
    success: function (response) {
      if(response.items.length > 0){
        $("#select_prosp").html(response.items.map( elm => `<option value="${elm.id}">${elm.text.substr(0, elm.text.indexOf("("))}</option>`));
        expand_sel($("#select_prosp")[0]);
        $("#select_prosp").show();
      }else{
        $("#select_prosp").hide();
      }
      console.log(response);
    },
    complete: function(){
      $("#button_search").attr('disabled', false);
      $("#loader-search").remove();
    }
  });
}
  $("#button_search").on('click', doSearch);

  function expand_sel(obj){
    obj.size = $(obj).children().length + 2;
  }
  function unexpand_sel(obj){
      obj.size = 1;
  }

  $("#select_prosp").on('change', function(e){
    $("#inp_buscar").val($("#select_prosp option:selected").text());
    $("#select_prosp").hide();
  });

  $("#form_asignar_jerarquia").on('keypress', (e) => {
    var key = e.charCode || e.keyCode || 0;     
    if (key == 13) {
      e.preventDefault();
      doSearch();
      return;
    }
  });

  $("#form_asignar_jerarquia").on('submit', function(e){
    e.preventDefault();

    if(!$("#select_prosp").val()){
      swal('seleccione una persona para el cargo');
      return;
    }
    var fData = new FormData(this);
    fData.append('action', 'asignar_jerarquia');
    $.ajax({
      type: "POST",
      url: "../../assets/data/Controller/verificacion/verificacionControl.php",
      data: fData,
      contentType:false,
      processData:false,
      dataType: "JSON",
      success: function (response) {
        if(response.estatus == 'ok'){
          swal({
            icon:'success',
            title:'Registro actualizado correctamente'
          });
        }else{
          swal({
            icon:'info',
            text:response.info
          });
        }
      }
    });
  })

  // function obtenerpais(){
  //   var usuarioss = $("#id_prospecto").val();
  //   $.ajax({
  //     type: "POST",
  //     url: "../app/Model/AfiliadosControl.php",
  //     data: {
  //       action: obtenerpais,
  //       id_prospecto: usuarioss,
  
  //     },
  //     dataType: "dataType",
  //     success: function (response) {
        
  //     }
  //   });
  // }

  function obtenerdatosperfil(){
    var usuarioss = $("#id_afiliado").val();
    $.ajax({
      type: "POST",
      url: "../app/data/CData/AfiliadosControl.php",
      data: {
        action: 'obtenerdatosperfil',
        id_prospecto: usuarioss,
  
      },
      dataType: "JSON",
      success: function (response) {
        var urlimg ="img/estados/"+response.estado+".png";
        console.log(urlimg);
        $("#img_edo").attr("src",urlimg);
        $("#edodirec").text("CAPITULO "+response.estado_nom);

       
      }
    });
  }


