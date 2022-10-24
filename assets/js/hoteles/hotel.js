$(document).ready(()=>{
  tablaAlimentos();
  tablaHotel();
  tablaHotelEspera();
  tablaCanjeoAlimentos();
  tablaEspTransporte();
  tablaFinalTransporte();
  tablaListaFinal();
  tablaListaG();
  tablaHoteles();
  tablaTransporte();
  tablaCortesias();
  tablaCortesiasFinalizadas();
})

$("#ModalCortesias").on('show.bs.modal', function () {
    $("#idcortesia_i").removeAttr("name");
    $("#casecortesias").val("insert");
});

$("#ModalCortesias").on('hide.bs.modal', function () {
    $('#formularioCortesias')[0].reset();
});

function editarCortesia(idCortesia,nombre,informacion,inicio,fin,typecort){
    $("#ModalCortesias").modal("show");
    $("#casecortesias").val("update");
    $("#idcortesia_i").attr("name","idcortesia");
    $("#idcortesia_i").val(idCortesia);
    
    $("#nombre_i").val(nombre);
    $("#informacion_i").val(informacion);
    $("#inicio_i").val(inicio);
    $("#fin_i").val(fin);
    $("#type_i").val(typecort);
}

$("#formularioCortesias").on("submit",function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'cargarCortesias');
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/hoteles/hotelControl.php",
        data: fdata,
        contentType:false,
        processData:false,
        dataType: "JSON",
        success: function (response) {
            if(response.estatus == 'ok'){
                swal({
                    title: 'Guardado Correctamente',
                    icon: 'success',
                    text: 'Espere un momento',
                    button: false,
                    timer: 2000,
                }).then((result)=>{
                    $("#ModalCortesias").modal("hide");
                    tCortesias.ajax.reload(null,false);
                    tCortesiasFinalizadas.ajax.reload(null,false);
                });
            }
        }
    });
});

function AsignarCortesia(idCortesia,nombre){
    $("#NombreCortesia").append(nombre);
    cargarCarrerasselect("carrerasCortesias");
    $("#idcortesias").val(idCortesia);
}

var id_asignados_check = [];
function obtenerAsignados(idAlumno){
    //Verificar si idPregunta existe en el array de preguntas
    var Comprobacion = id_asignados_check.indexOf(idAlumno);
 
    if(Comprobacion==-1){
        id_asignados_check.push(idAlumno);
    }else{
        id_asignados_check.splice(id_asignados_check.indexOf(idAlumno),1);
    }

    if(id_asignados_check.length<1){
        $("#AlumnosEspecificos").prop('disabled',true);
        $("#AlumnosEspecificos").addClass('d-none');
    }else{
        $("#AlumnosEspecificos").prop('disabled',false);
        $("#AlumnosEspecificos").removeClass('d-none');
    }
    console.log(id_asignados_check);
}

$('#modelAsignarCortesia').on('hide.bs.modal', function (event) {
    $("#NombreCortesia").html("");
    //Vaciar los elementos de un datatable
    if($("#datatable-Alumnos-cortesias").DataTable().ajax.url() !== null){
        tAlumnosCortesias.clear();
        tAlumnosCortesias.draw();
    }
    $("#AsignarCortesias").attr("disabled",true);
    id_asignados_check = [];
    $("#AlumnosEspecificos").prop('disabled',true);
    $("#AlumnosEspecificos").addClass('d-none');
    $("#formularioAsignacionCortesias")[0].reset();
});

$("#carrerasCortesias").on("change",function(e){
    var idCarrera = $(this).val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionesCortesias").html('<option selected="true" value="" disabled="disabled">Seleccione una Generación</option>');
            $.each(data, function(key, registro){
                $("#generacionesCortesias").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generacionesCortesias").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
                $("#AsignarCortesias").attr("disabled");
            }
        }
    });
    tablaAlumnosCortesiasCarr(idCarrera); 
});


$("#generacionesCortesias").on("change",function(e){
    console.log("Por Generacion");
    var idCarrera = $("#carrerasCortesias").val();
    var idGeneracion = $(this).val();
    tablaAlumnosCortesiasCarrGen(idCarrera,idGeneracion);
});

  function cargarHoteles(){
    $.ajax({
        url: '../assets/data/Controller/hoteles/hotelControl.php',
        type: 'POST',
        data: {action: "cargarHoteles"},
        dataType: 'JSON',
        success : function(data){
            try{
                $("#hotelesAsig").html('<option selected="true" disabled="disabled">Seleccione</option>');
                $("#devHoteles").html('<option selected="true" disabled="disabled">Seleccione</option>');
                $.each(data, function(key,registro){
                    $("#hotelesAsig").append('<option value='+registro.id+'>'+registro.nombre+'</option>');
                    $("#devHoteles").append('<option value='+registro.id+'>'+registro.nombre+'</option>');
                });
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
  }

    function cargarCortesiasHospedaje(){
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: {action: 'cargarCortesiasHospedaje'},
            dataType: 'JSON',
            success: function(data){  
                $("#cortesia_match").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data.data, function(key,registro){
                    $("#cortesia_match").append('<option value='+registro.idcortesia+'>'+registro.nombre+'</option>');
                });
            }
        });  
    }

  function tablaAlumnosCortesiasCarrGen(idCarrera,idGeneracion){
    tAlumnosCortesias = $("#datatable-Alumnos-cortesias").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            /*extend:"copy",
            className: "btn-success"
        },{
            extend: "csv"
        }, {*/
            extend: "excel",
            className: "btn-primary"
        }, {
            extend: "pdf"
        }, {
            extend: "print"
        }],
        "ajax": {
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: {action: 'cargarAlumnosCortesias', idcarrera:idCarrera, idgeneracion:idGeneracion},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            complete: function(e){
                var numrows = tAlumnosCortesias.rows().count();
                if(numrows==0){
                    $("#AsignarCortesias").attr("disabled",true);
                }else{
                    //$("#carrerasCortesias").attr("name","carrerasCortesias");
                    $("#generacionesCortesias").attr("name","generacionesCortesias");
                    $("#AsignarCortesias").removeAttr("disabled");
                    $("#AsignarCortesias").text("Asignar a Generación");
                }
            }
        },
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
        'iDisplayLength': 20,
        'order':[
            [0,'asc']
        ]
      });
  }

  function tablaAlumnosCortesiasCarr(idCarrera){
    tAlumnosCortesias = $("#datatable-Alumnos-cortesias").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            /*extend:"copy",
            className: "btn-success"
        },{
            extend: "csv"
        }, {*/
            extend: "excel",
            className: "btn-primary"
        }, {
            extend: "pdf"
        }, {
            extend: "print"
        }],
        "ajax": {
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: {action: 'cargarAlumnosCortesias', idcarrera:idCarrera},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            complete: function(e){
                var numrows = tAlumnosCortesias.rows().count();
                if(numrows==0){
                    $("#AsignarCortesias").attr("disabled",true);
                }else{
                    $("#carrerasCortesias").attr("name","carrerasCortesias");
                    $("#generacionesCortesias").removeAttr("name");
                    $("#AsignarCortesias").removeAttr("disabled");
                    $("#AsignarCortesias").text("Asignar a Carrera");
                }
            }
        },
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
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ]
      });
  }

  function tablaCortesias(){
    tCortesias = $("#datatable-cortesias").DataTable({
      responsive: true,
      Processing: true,
      ServerSide: true,
      "dom" :'Bfrtip',
      buttons:[{
          /*extend:"copy",
          className: "btn-success"
      },{
          extend: "csv"
      }, {*/
          extend: "excel",
          className: "btn-primary"
      }, {
          extend: "pdf"
      }, {
          extend: "print"
      }],
      "ajax": {
          url: '../assets/data/Controller/hoteles/hotelControl.php',
          type: 'POST',
          data: {action: 'cargarCortesiasTab', case:"queryAll"},
          dataType: "JSON",
          error: function(e){
              console.log(e.responseText);
          }
      },
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
      'iDisplayLength': 10,
      'order':[
          [0,'asc']
      ]
    });
}

function tablaCortesiasFinalizadas(){
    tCortesiasFinalizadas = $("#datatable-cortesias-finalizadas").DataTable({
      responsive: true,
      Processing: true,
      ServerSide: true,
      "dom" :'Bfrtip',
      buttons:[{
          /*extend:"copy",
          className: "btn-success"
      },{
          extend: "csv"
      }, {*/
          extend: "excel",
          className: "btn-primary"
      }, {
          extend: "pdf"
      }, {
          extend: "print"
      }],
      "ajax": {
          url: '../assets/data/Controller/hoteles/hotelControl.php',
          type: 'POST',
          data: {action: 'cargarCortesiasTab', case:"queryAllPast"},
          dataType: "JSON",
          error: function(e){
              console.log(e.responseText);
          }
      },
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
      'iDisplayLength': 10,
      'order':[
          [0,'asc']
      ]
    });
}

function AsignarCortesiaCasos($case){
    var cortesia = $("#idcortesias").val();
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:"AsignarCortesias",idsAlumnos: id_asignados_check, idcortesia: cortesia},
        dataType: "JSON",
        success: function (response) {
            if(response.estatus="ok"){
                console.log(response);
                swal({
                    title: response.msj,
                    icon: 'success',
                    text: 'Espere un momento',
                    button: false,
                    timer: 2000,
                }).then((result)=>{
                    $("#modelAsignarCortesia").modal("hide");
                });

            }
        }
    });
}

$("#formularioAsignacionCortesias").on("submit",function(e){
    e.preventDefault();
    $form = new FormData(this);
    //console.log(e);
    var accept = ""
    var infom = "";
    if(e.currentTarget[e.currentTarget.length-1].innerText == "Asignar a Carrera"){
        accept = e.currentTarget[e.currentTarget.length-1].innerText;
        infom = "Al aceptar la cortesia sera visible para todos los alumnos de la carrera seleccionada";
    }else{
        accept = e.currentTarget[e.currentTarget.length-1].innerText;
        infom = "Al aceptar la cortesia sera visible para todos los alumnos de la generación seleccionada";
    }
    
    $form.append('action','AsignarCortesias');
    Swal.fire({
        title: '¿'+accept+'?',
        text: infom,
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){ 
            $.ajax({
                type: 'POST',
                url: '../assets/data/Controller/hoteles/hotelControl.php',
                data: $form,
                contentType:false,
                processData:false,
                dataType: "JSON",
                success: function(data){
                    if(data.estatus = "ok"){
                        console.log(data);
                        swal({
                            title: data.msj,
                            icon: 'success',
                            text: 'Espere un momento',
                            button: false,
                            timer: 2000,
                        }).then((result)=>{
                            $("#modelAsignarCortesia").modal("hide");
                        });
                    }
                }
            });
        }
    });
});


  function tablaAlimentos(){
    tAlimentos = $("#datatable-alimentos").DataTable({
      responsive: true,
      Processing: true,
      ServerSide: true,
      "dom" :'Bfrtip',
      buttons:[{
          /*extend:"copy",
          className: "btn-success"
      },{
          extend: "csv"
      }, {*/
          extend: "excel",
          className: "btn-primary"
      }, {
          extend: "pdf"
      }, {
          extend: "print"
      }],
      "ajax": {
          url: '../assets/data/Controller/hoteles/hotelControl.php',
          type: 'POST',
          data: {action: 'consultarAlimentos'},
          dataType: "JSON",
          error: function(e){
              console.log(e.responseText);
          }
      },
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
      'iDisplayLength': 10,
      'order':[
          [0,'asc']
      ]
    });
  }

  function buscarAlimentos(id_usuario,idcortesia){
    Data = {
        action: 'buscarAlimentos',
        idBuscar: id_usuario,
        idcortesia: idcortesia
    }
    $.ajax({
        url: '../assets/data/Controller/hoteles/hotelControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);
                $("#devNombre").val(pr.data[0].nombre);
                $("#devAPP").val(pr.data[0].apaterno);
                $("#devComida").val(pr.data[0].comida);
                $("#devCena").val(pr.data[0].cena);
                $("#idModificarAlimentos").val(pr.data[0].id_usuario);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display","none")
        }
    });
  }

  $("#formModificarAlimentos").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'modificarAlimentos');
    $.ajax({
        url: '../assets/data/Controller/hoteles/hotelControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                console.log(pr)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Modificado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formModificarAlimentos")[0].reset();
                        tAlimentos.ajax.reload();
                        tCanjeoAlimentos.ajax.reload();
                        tListaFinal.ajax.reload();
                        $("#modalModificarAlimentos").modal("hide");
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
  })
  /*
  function validarEliminar(Alimen){
    swal({
        title: 'Estas seguro de Eliminarlo',
        icon: 'info',
        buttons: {cancel: 'Cancelar',
                  confirm: 'Aceptar'
                },
        dangerMode: true,
    }).then((isConfirm)=>{
        if(isConfirm){
            eliminarAlimentos(Alimen);
        }else{
            swal("Cancelado Correctamente");
        }
    });
  }

  function eliminarAlimentos(Alimen){
    Data = {
        action: "eliminarAlimentos",
        idEliminar: Alimen
    }
    $.ajax({
        url: '../assets/data/Controller/hoteles/hotelControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                if(data != 'no_session'){
                    swal({
                        title: 'Eliminado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    })
                    .then((result)=>{
                      tAlimentos.ajax.reload();
                    })
                }
                //$("#datatableEventos").load('');
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
            
        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
  }
  */

//   $("a[data-toggle=\"tab\"]").on("shown.bs.tab",function(){
//     tAlimentos.columns.adjust();
//     tHotel.columns.adjust();
//     tesperaHotel.columns.adjust();
//     tCanjeoAlimentos.columns.adjust();
//     tEspTransporte.columns.adjust();
//     tModTransporte.columns.adjust();
//     tListaFinal.columns.adjust();
//     tListaG.columns.adjust();
//   });

    function tablaHotel(){
        tHotel = $("#datatable-hotel").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            /*extend:"copy",
            className: "btn-success"
        },{
            extend: "csv"
        }, {*/
            extend: "excel",
            className: "btn-primary"
        /*}, {
            extend: "pdf"
        }, {
            extend: "print"*/
        }],
        "ajax": {
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: {action: 'consultarHotel'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            }
        },
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
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ],
        'fnRowCallback': function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $('td:eq(0)',nRow).css('background-color', '#4478AA');
            $('td:eq(0)',nRow).css('color', 'white');
            $('td:eq(1)',nRow).css('background-color', '#4478AA');
            $('td:eq(1)',nRow).css('color', 'white');
            $('td:eq(2)',nRow).css('background-color', '#4478AA');
            $('td:eq(2)',nRow).css('color', 'white');
            $('td:eq(4)',nRow).css('background-color', '#b3983f');
            $('td:eq(4)',nRow).css('color', 'white');
            $('td:eq(5)',nRow).css('background-color', '#b3983f');
            $('td:eq(5)',nRow).css('color', 'white');
            $('td:eq(6)',nRow).css('background-color', '#b3983f');
            $('td:eq(6)',nRow).css('color', 'white');
        }
        });
    }

    function tablaHotelEspera(){
        tesperaHotel = $("#datatable-esperaHotel").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            /*extend:"copy",
            className: "btn-success"
        },{
            extend: "csv"
        }, {*/
            extend: "excel",
            className: "btn-primary"
        /*}, {
            extend: "pdf"
        }, {
            extend: "print"*/
        }],
        "ajax": {
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: {action: 'consultarEsperaHotel'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            }
        },
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
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ],
        'fnRowCallback': function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $('td:eq(0)',nRow).css('background-color', '#4478AA');
            $('td:eq(0)',nRow).css('color', 'white');
            $('td:eq(1)',nRow).css('background-color', '#4478AA');
            $('td:eq(1)',nRow).css('color', 'white');
            $('td:eq(2)',nRow).css('background-color', '#4478AA');
            $('td:eq(2)',nRow).css('color', 'white');
            $('td:eq(4)',nRow).css('background-color', '#b3983f');
            $('td:eq(4)',nRow).css('color', 'white');
            $('td:eq(5)',nRow).css('background-color', '#b3983f');
            $('td:eq(5)',nRow).css('color', 'white');
            $('td:eq(6)',nRow).css('background-color', '#b3983f');
            $('td:eq(6)',nRow).css('color', 'white');
        }
        });
    }

    function asignarUsuarios(id_usuario, id_companiero, idcortesia){
        //cargarHoteles();
        Data = {
            action: 'obtenerUsuarios',
            idUsu: id_usuario,
            idComp: id_companiero,
            idcortesia: idcortesia
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            success: function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    $("#modalAsignarHtl").modal('show');
                    pr = JSON.parse(data);
                    $("#devNom").val(pr.data[0].nombre);
                    $("#devAPaterno").val(pr.data[0].apaterno);
                    $("#devAMaterno").val(pr.data[0].amaterno);
                    $("#devNomComp").val(pr.data[0].nombreComp);
                    $("#devAPComp").val(pr.data[0].apaternoComp);
                    $("#devAMComp").val(pr.data[0].amaternoComp);
                    $("#idAsignarUsu").val(pr.data[0].id_usuario);
                    $("#idAsignarComp").val(pr.data[0].id_companiero);
                    $("#idcortesia").val(pr.data[0].idcortesia);
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            beforeSend: function(){
                //$("#Enviar").prop('disabled', true);
                $("#hoteles").empty();
                cargarHoteles();
            },
            error : function(){

            },
            complete : function(){
                $(".outerDiv_S").css("display","none")
            }
        });
    }


    $("#ocultar").on('click',function(){
        $("#formAsignarHotel")[0].reset();
        $("#formModAsignarHotel")[0].reset();
        $("#formModificarAlimentos")[0].reset();
        $("#formAsigTransporte")[0].reset();
    })


    $("#formAsignarHotel").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'asignarHotel');
    $.ajax({
        url: '../assets/data/Controller/hoteles/hotelControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                console.log(pr)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Asignado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formAsignarHotel")[0].reset();
                        tesperaHotel.ajax.reload();
                        tHotel.ajax.reload();
                        $("#modalAsignarHtl").modal("hide");
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }
        });
    })

    function modificarAsignacion(id_usuario, id_companiero, idcortesia){
        cargarHoteles();
        Data = {
            action: 'obtenerAsignacion',
            idUsu: id_usuario,
            idComp: id_companiero,
            idcortesia: idcortesia
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            success: function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    //cargarHoteles();
                    $("#modalModHtl").modal('show');
                    pr = JSON.parse(data);
                    $("#devNomb").val(pr.data[0].nombre);
                    $("#devAPater").val(pr.data[0].apaterno);
                    $("#devAMater").val(pr.data[0].amaterno);
                    $("#devNombComp").val(pr.data[0].nombreComp);
                    $("#devAPCompa").val(pr.data[0].apaternoComp);
                    $("#devAMCompa").val(pr.data[0].amaternoComp);
                    $("#devHoteles").val(pr.data[0].id_hotel);
                    $("#devHabitacion").val(pr.data[0].habitacion);
                    $("#idModAsignarUsu").val(pr.data[0].id_usuario);
                    $("#idModAsignarComp").val(pr.data[0].id_companiero);
                    $("#idModcortesia").val(pr.data[0].idcortesia);
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){
    
            },
            complete : function(){
                $(".outerDiv_S").css("display","none")
            }
        });
    }

    $("#formModAsignarHtl").on('submit',function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'modAsignarHotel');
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
            success : function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    pr = JSON.parse(data)
                    console.log(pr)
                    if(pr.estatus == 'ok'){
                        swal({
                            title: 'Modificado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formModAsignarHtl")[0].reset();
                            tHotel.ajax.reload();
                            $("#modalModHtl").modal("hide");
                        })
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){
    
            },
            complete : function(){
                $(".outerDiv_S").css("display", "none")
            }
        });
    })

    function tablaCanjeoAlimentos(){
        tCanjeoAlimentos = $("#datatable-canjeoAlim").DataTable({
          responsive: true,
          Processing: true,
          ServerSide: true,
          "dom" :'Bfrtip',
          buttons:[{
              /*extend:"copy",
              className: "btn-success"
          },{
              extend: "csv"
          }, {*/
              extend: "excel",
              className: "btn-primary"
          }, {
              extend: "pdf"
          }, {
              extend: "print"
          }],
          "ajax": {
              url: '../assets/data/Controller/hoteles/hotelControl.php',
              type: 'POST',
              data: {action: 'consultarCanjeoAlim'},
              dataType: "JSON",
              error: function(e){
                  console.log(e.responseText);
              }
          },
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
          'iDisplayLength': 10,
          'order':[
              [0,'asc']
          ]
        });
    }

    function tablaEspTransporte(){
        tEspTransporte = $("#datatable-transporte").DataTable({
          responsive: true,
          Processing: true,
          ServerSide: true,
          "dom" :'Bfrtip',
          buttons:[{
              /*extend:"copy",
              className: "btn-success"
          },{
              extend: "csv"
          }, {*/
              extend: "excel",
              className: "btn-primary"
          }, {
              extend: "pdf"
          }, {
              extend: "print"
          }],
          "ajax": {
              url: '../assets/data/Controller/hoteles/hotelControl.php',
              type: 'POST',
              data: {action: 'consultarEsperaTransporte'},
              dataType: "JSON",
              error: function(e){
                  console.log(e.responseText);
              }
          },
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
          'iDisplayLength': 10,
          'order':[
              [0,'asc']
          ]
        });
    }

    function tablaTransporte(){
        tabTrans = $("#datatable-AdminTrans").DataTable({
            responsive: true,
            Processing: true,
            ServerSide: true,
            "dom" :'Bfrtip',
            buttons:[{
                /*extend:"copy",
                className: "btn-success"
            },{
                extend: "csv"
            }, {*/
                extend: "excel",
                className: "btn-primary"
            }, {
                extend: "pdf"
            }, {
                extend: "print"
            }],
            "ajax": {
                url: '../assets/data/Controller/hoteles/hotelControl.php',
                type: 'POST',
                data: {action: 'CargarTablaTransporte'},
                dataType: "JSON",
                error: function(e){
                    console.log(e.responseText);
                }
            },
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
            'iDisplayLength': 10,
            'order':[
                [0,'asc']
            ]
        });
    }

    function tablaHoteles(){
        tabHot = $("#datatable-Hoteles").DataTable({
          responsive: true,
          Processing: true,
          ServerSide: true,
          "dom" :'Bfrtip',
          buttons:[{
              /*extend:"copy",
              className: "btn-success"
          },{
              extend: "csv"
          }, {*/
              extend: "excel",
              className: "btn-primary"
          }, {
              extend: "pdf"
          }, {
              extend: "print"
          }],
          "ajax": {
              url: '../assets/data/Controller/hoteles/hotelControl.php',
              type: 'POST',
              data: {action: 'consultarHoteles'},
              dataType: "JSON",
              error: function(e){
                  console.log(e.responseText);
              }
          },
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
          'iDisplayLength': 10,
          'order':[
              [0,'asc']
          ]
        });
    }


    function cargarSelectTransporte(id_usuario,idcortesia){
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: {action: "CargarTipoTransporte"},
            dataType: "JSON",
            success: function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    //transporteMod
                    $("#transporteMod").html('<option selected="true" value="" disabled="disabled">Seleccione un metodo de transporte</option>');
                    $("#transporteAsign").html('<option selected="true" value="" disabled="disabled">Seleccione un metodo de transporte</option>');
                    $.each(data, function(key, data){
                        $("#transporteMod").append('<option value='+data.idtransporte+'>'+data.nombre+'</option>');
                        $("#transporteAsign").append('<option value='+data.idtransporte+'>'+data.nombre+'</option>');
                    });
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){

            },
            complete : function(){
                $(".outerDiv_S").css("display","none")
            }
        });
    }
    function asignarTransporte(id_usuario, idcortesia){
        cargarSelectTransporte(id_usuario,idcortesia);
        Data = {
            action: 'obtenerUsuarios',
            idUsu: id_usuario,
            //idComp: id_companiero,
            idcortesia: idcortesia
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            success: function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    $("#modalAsigTransporte").modal('show');
                    pr = JSON.parse(data);
                    $("#nameT").val(pr.data[0].nombre);
                    $("#aPater").val(pr.data[0].apaterno);
                    $("#aMater").val(pr.data[0].amaterno);
                    $("#idAsignarUsuT").val(pr.data[0].id_usuario);
                    $("#idcortesiaT").val(pr.data[0].idcortesia);
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){

            },
            complete : function(){
                $(".outerDiv_S").css("display","none")
            }
        });
    }

    $("#formAsigTransporte").on('submit',function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'asignarTransporte');
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
            success : function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    pr = JSON.parse(data)
                    console.log(pr)
                    if(pr.estatus == 'ok'){
                        swal({
                            title: 'Asignado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formAsigTransporte")[0].reset();
                            tEspTransporte.ajax.reload();
                            tModTransporte.ajax.reload();
                            $("#modalAsigTransporte").modal("hide");
                        })
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){
    
            },
            complete : function(){
                $(".outerDiv_S").css("display", "none")
            }
        });
    })

    function tablaFinalTransporte(){
        tModTransporte = $("#datatable-modTransporte").DataTable({
          responsive: true,
          Processing: true,
          ServerSide: true,
          "dom" :'Bfrtip',
          buttons:[{
              /*extend:"copy",
              className: "btn-success"
          },{
              extend: "csv"
          }, {*/
              extend: "excel",
              className: "btn-primary"
          }, {
              extend: "pdf"
          }, {
              extend: "print"
          }],
          "ajax": {
              url: '../assets/data/Controller/hoteles/hotelControl.php',
              type: 'POST',
              data: {action: 'consultarTransportes'},
              dataType: "JSON",
              error: function(e){
                  console.log(e.responseText);
              }
          },
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
          'iDisplayLength': 10,
          'order':[
              [0,'asc']
          ]
        });
    }
    

    function modificarTransporte(id_usuario, idcortesia){
        Data = {
            action: 'obtenerTransporte',
            idUsu: id_usuario,
            idcortesia: idcortesia
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            beforeSend: function(data){
                cargarSelectTransporte(id_usuario, idcortesia);
            },
            success: function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    $("#modalModTransporte").modal('show');
                    pr = JSON.parse(data);
                    $("#nameMod").val(pr.data[0].nombre);
                    $("#aPaterMod").val(pr.data[0].apaterno);
                    $("#aMaterMod").val(pr.data[0].amaterno);
                    $("#transporteMod").val(pr.data[0].transporte);
                    $("#asientoMod").val(pr.data[0].numero_asiento);
                    $("#idModTranspor").val(pr.data[0].id_usuario);
                    $("#idModcortesiaTranspor").val(pr.data[0].idcortesia);
                    $("#idModcortesiaTranspors").val(idcortesia);

                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){

            },
            complete : function(){
                $(".outerDiv_S").css("display","none")
            }
        });
    }

    $("#formModTransporte").on('submit',function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'modificarTransporte');
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
            success : function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    pr = JSON.parse(data)
                    console.log(pr)
                    if(pr.estatus == 'ok'){
                        swal({
                            title: 'Modificado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formModTransporte")[0].reset();
                            tModTransporte.ajax.reload();
                            tEspTransporte.ajax.reload();
                            $("#modalModTransporte").modal("hide");
                        })
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){
    
            },
            complete : function(){
                $(".outerDiv_S").css("display", "none")
            }
        });
    })

    

    // function solicitar_match(){
    //     $.ajax({
    //         type: "POST",
    //         dataType: "JSON",
    //         url: "../assets/data/Controller/hoteles/hotelControl.php",
    //         data: {action: "solicitar_match",solicita:"si"},
    //         success: function (response) {
                
    //         }
    //     });
    // }

    function cargarCarrerasTabla(){
        cargarCarrerasselect("Carrera");
    }

    function cargarCarrerasselect(idselect){
        $.ajax({
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'buscarClasesCarrera',
            },
            dataType: 'JSON',
            success: function(data){
                $("#"+idselect).html('<option selected="true" value="" disabled="disabled">Seleccione una Carrera</option>');
                $.each(data, function(key, registro){
                    $("#"+idselect).append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                });
            }
        });
    }

    $('#ModalSolicitarMatch').on('show.bs.modal', function (event) {
        cargarCarrerasTabla();
    });

    $('#ModalSolicitarMatch').on('hide.bs.modal', function (event) {
        $('#form-Solicitud-match')[0].reset();
    });

    $('#ModalAddHotel').on('hide.bs.modal', function (event) {
        $('#form-nuevo-hotel')[0].reset();
    });

    $('#modalTransporte').on('hide.bs.modal', function (event) {
        $('#form-update-transporte')[0].reset();
        $("#case").val("add");
        $("#idtransporte").removeAttr("name");
    });

    $('#ModalAddHotel').on('show.bs.modal', function (event) {
        $('#form-nuevo-hotel')[0].reset();
        $("#tipoCase").val("add");
        $("#idHotel").val("");
    });


    
    $("#Carrera").on("change", function(e){
        $("#generacion").empty();
        $("#btn-solicitar-reservacion").attr("disabled",true);
        $("#generacion").html('<option selected="true" value="" disabled="disabled">Seleccione una generación</option>');
        $("#idSolic").html('<option selected="true" value="" disabled="disabled">Seleccione un Alumno</option>');
        $("#idCompa").html('<option selected="true" value="" disabled="disabled">Seleccione un Alumno</option>');

        var idCarrera = $("#Carrera").val();
        $.ajax({
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'obtenerGeneracionesCarrera',
                idCarr: idCarrera
            },
            dataType: 'JSON',
            success: function(data){
                $("#generacion").html('<option selected="true" value="" disabled="disabled">Seleccione una Generación</option>');
                $.each(data, function(key, registro){
                    $("#generacion").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
                });
            },
            error : function(xhr){
                if(xhr.responseText == 'sin_generaciones'){
                    $("#generacion").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
                }
            }
        })
    });

    $("#generacion").on("change",function(){
        //$("#generacion").empty();
        var gen = $("#generacion").val();
        $("#btn-solicitar-reservacion").attr("disabled",true);
        $("#idSolic").html('<option selected="true" value="" disabled="disabled">Seleccione un Alumno</option>');
        $("#idCompa").html('<option selected="true" value="" disabled="disabled">Seleccione un Alumno</option>');
        $.ajax({
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultar_alumnos_match',
                generacion: gen
            },
            dataType: 'JSON',
            success: function(data){
                $("#idSolic").html('<option selected="true" value="" disabled="disabled">Seleccione un Alumno</option>');
                $.each(data, function(key, registro){
                    $("#idSolic").append('<option value='+registro.idAsistente+'>'+registro.nombre_alumno+'</option>');
                });
                cargarCortesiasHospedaje();
            },
            error : function(xhr){
                if(xhr.responseText == 'sin_generaciones'){
                    $("#idSolic").html('<option selected="true" value="" disabled="disabled">Sin alumnos asignadas</option>');
                }
            }
        });
    });

    $("#idSolic").on("change",function(e){
        $("#btn-solicitar-reservacion").attr("disabled",true);
        var gen = $("#generacion").val();
        var Alumnosolic = $("#idSolic").val();
        $.ajax({
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultar_alumnos_match',
                generacion: gen,
                Alumnosolic: Alumnosolic
            },
            dataType: 'JSON',
            success: function(data){
                $("#idCompa").html('<option selected="true" value="" disabled="disabled">Seleccione un Compañero</option>');
                $.each(data, function(key, registro){
                    $("#idCompa").append('<option value='+registro.idAsistente+'>'+registro.nombre_alumno+'</option>');
                });
            },
            error : function(xhr){
                if(xhr.responseText == 'sin_generaciones'){
                    $("#idCompa").html('<option selected="true" value="" disabled="disabled">Sin alumnos asignadas</option>');
                }
            }
        });
    });

    $("#idCompa").on("change",function(e){
        $("#btn-solicitar-reservacion").attr("disabled",false);
    });

    $("#form-Solicitud-match").on("submit",function(e){
        e.preventDefault();
        
        $form = new FormData(this);
        $form.append('action','solicitar_match');
        $form.append('matricula','Cobranza');
        $form.append('solicita','si');

        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            data: $form,
            dataType: 'JSON',
            contentType:false,
            processData:false,
            success: function(data){
                if(data.estatus == "ok"){
                    swal({
                        title: 'Añadida Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1500,
                    }).then(result=>{
                        $('#ModalSolicitarMatch').modal("hide");
                        tesperaHotel.ajax.reload(null,false);
                    });
                }
            }
        });
    });

    function updateTransporte(idTransporte,nombre){
        $("#modalTransporte").modal("show");
        //console.log(data);
        $("#case").val("update");
        $("#idtransporte").val(idTransporte);
        $("#idtransporte").prop("name","idTransporte");
        $("#nombreTransporte").val(nombre);

    }

    function updateHotel(id){

        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            data: {action: "consultarHotelprs", idHotel: id},
            dataType: 'JSON',
            success: function(data){
                $("#ModalAddHotel").modal("show");
                //console.log(data);
                $("#tipoCase").val("update");
                $("#idHotel").val(id);
                $("#newHotel").val(data.nombre);
                $("#direccion").val(data.direccion);
            }
        });
    }   
    
    $("#form-nuevo-hotel").on("submit",function(e){
        e.preventDefault();
        $form = new FormData(this);
        $form.append("action","AddHotel");
        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            data: $form,
            dataType: 'JSON',
            contentType:false,
            processData:false,
            success: function(data){
                if(data.estatus == "ok"){
                    swal({
                        title: 'Añadida Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1500,
                    }).then(result=>{
                        tabHot.ajax.reload(null,false);
                        $('#ModalAddHotel').modal("hide");
                    });
                }
            }
        });        
    });

  
    $("#form-update-transporte").on("submit",function(e){
        e.preventDefault();
        $form = new FormData(this);
        $form.append("action","AddTransporte");
        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            data: $form,
            dataType: 'JSON',
            contentType:false,
            processData:false,
            success: function(data){
                if(data.estatus == "ok"){
                    swal({
                        title: 'Añadido Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1500,
                    }).then(result=>{
                        tabTrans.ajax.reload(null,false);
                        $('#modalTransporte').modal("hide");
                    });
                }
            }
        });    
    })
    
    function canjearAlimentos(idresrvacion){
        Swal.fire({
            text: '¿Está seguro de validar la reservación de comida del Alumno?',
            type:'info',
            customClass: 'myCustomClass-info',
            showCancelButton: true,
            confirmButtonColor: '#AA262C',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: '#767575',
            cancelButtonText: 'Cancelar'
        }).then(result=>{
            if(result.value){
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "../assets/data/Controller/hoteles/hotelControl.php",
                    data: {action: "canjearAlimentos", id: idresrvacion},
                    success: function (response) {
                        if(response.estatus == "ok"){
                            swal({
                                title: 'Comida Reservada Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 1500,
                            }).then(result=>{
                                tCanjeoAlimentos.ajax.reload(null,false)
                            });
                        }
                    }
                });
            }
        });
    }
    
    function canjearCena(idresrvacion){
        Swal.fire({
            text: '¿Está seguro de validar la reservación de cena del Alumno?',
            type:'info',
            customClass: 'myCustomClass-info',
            showCancelButton: true,
            confirmButtonColor: '#AA262C',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: '#767575',
            cancelButtonText: 'Cancelar'
        }).then(result=>{
            if(result.value){
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "../assets/data/Controller/hoteles/hotelControl.php",
                    data: {action: "canjearCena", id: idresrvacion},
                    success: function (response) {
                        if(response.estatus == "ok"){
                            swal({
                                title: 'Cena Reservada Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 1500,
                            }).then(result=>{
                                tCanjeoAlimentos.ajax.reload(null,false)
                            });
                        }
                    }
                });
            }
        })
    }

    function tablaListaFinal(){
        tListaFinal = $("#datatable-listaFinal").DataTable({
          responsive: true,
          Processing: true,
          ServerSide: true,
          "dom" :'Bfrtip',
          buttons:[{
              /*extend:"copy",
              className: "btn-success"
          },{
              extend: "csv"
          }, {*/
              extend: "excel",
              className: "btn-primary"
          /*}, {
              extend: "pdf"
          }, {
              extend: "print"*/
          }],
          "ajax": {
              url: '../assets/data/Controller/hoteles/hotelControl.php',
              type: 'POST',
              data: {action: 'consultarFinal'},
              dataType: "JSON",
              error: function(e){
                  console.log(e.responseText);
              }
          },
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
          'iDisplayLength': 10,
          'order':[
              [0,'asc']
          ]
        });
    }

    function tablaListaG(){
        tListaG = $("#datatable-listaG").DataTable({
          responsive: true,
          Processing: true,
          ServerSide: true,
          "dom" :'Bfrtip',
          buttons:[{
              /*extend:"copy",
              className: "btn-success"
          },{
              extend: "csv"
          }, {*/
              extend: "excel",
              className: "btn-primary",
              title:'Cortesias_'+new Date().toLocaleDateString().replace(/\//g, '-'),
              exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8]
              }
          /*}, {
              extend: "pdf"
          }, {
              extend: "print"*/
          }],
          "ajax": {
              url: '../assets/data/Controller/hoteles/hotelControl.php',
              type: 'POST',
              data: {action: 'consultarGeneral'},
              dataType: "JSON",
              error: function(e){
                  console.log(e.responseText);
              }
          },
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
          'iDisplayLength': 10,
          'order':[
              [0,'asc']
          ]
        });
    }
/*
    function validarElim(id){
        swal({
            title: 'Estas seguro de Eliminarlo',
            icon: 'info',
            buttons: {cancel: 'Cancelar',
                      confirm: 'Aceptar'
                    },
            dangerMode: true,
        }).then((isConfirm)=>{
            if(isConfirm){
                eliminarGeneral(id);
            }else{
                swal("Cancelado Correctamente");
            }
        });
    }*/

    function validarElim(id){
        Swal.fire({
            text: '¿Estas seguro de eliminarlo?',
            type:'info',
            customClass: 'myCustomClass-info',
            showCancelButton: true,
            confirmButtonColor: '#AA262C',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: '#767575',
            cancelButtonText: 'Cancelar'
        }).then(result=>{
            if(result.value){
                eliminarGeneral(id);
            }
        })
    }

    function eliminarGeneral(id){
        Data = {
            action: "eliminarGeneral",
            idEliminar: id
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            success : function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    if(data != 'no_session'){
                        swal({
                            title: 'Eliminado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        })
                        .then((result)=>{
                          tListaG.ajax.reload();
                        })
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
                
            },
            complete : function(){
                $(".outerDiv_S").css("display", "none")
            }
        });
    }
    /*
    function validarEliminarUsu(id, id_comp){
        swal({
            title: 'Estas seguro de Eliminarlo',
            icon: 'info',
            buttons: {cancel: 'Cancelar',
                      confirm: 'Aceptar'
                    },
            dangerMode: true,
        }).then((isConfirm)=>{
            if(isConfirm){
                eliminarUsuarios(id, id_comp);
            }else{
                swal("Cancelado Correctamente");
            }
        });
    }
    */
    function validarEliminarUsu(id, id_comp, idcortesia){
        Swal.fire({
            text: '¿Estas seguro de eliminarlo?',
            type:'info',
            customClass: 'myCustomClass-info',
            showCancelButton: true,
            confirmButtonColor: '#AA262C',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: '#767575',
            cancelButtonText: 'Cancelar'
        }).then(result=>{
            if(result.value){
                eliminarUsuarios(id, id_comp, idcortesia);
            }
        })
    }

    function eliminarUsuarios(id, id_comp, idcortesia){
        Data = {
            action: "eliminarUsuarios",
            idEliminar: id,
            idComp: id_comp,
            idcortesia: idcortesia
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            dataType: "JSON",
            success : function(data){
                //console.log(data);
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    if(data.estatus == "ok"){
                        swal({
                            title: 'Eliminado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        })
                        .then((result)=>{
                            tHotel.ajax.reload();
                            tListaG.ajax.reload();
                        })
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
                
            },
            complete : function(){
                $(".outerDiv_S").css("display", "none")
            }
        });
    }

    
    function validarEliminarComp(id, id_comp, idcortesia){
        Swal.fire({
            text: '¿Estas seguro de eliminarlo?',
            type:'info',
            customClass: 'myCustomClass-info',
            showCancelButton: true,
            confirmButtonColor: '#AA262C',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: '#767575',
            cancelButtonText: 'Cancelar'
        }).then(result=>{
            if(result.value){
                eliminarCompaniero(id, id_comp, idcortesia);
            }else{
                swal("Cancelado Correctamente");
            }
        })
    }

    function eliminarCompaniero(id, id_comp, idcortesia){
        Data = {
            action: "eliminarCompaniero",
            idEliminar: id,
            idComp: id_comp,
            idcortesia: idcortesia
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            success : function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    if(data != 'no_session'){
                        swal({
                            title: 'Eliminado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        })
                        .then((result)=>{
                            tesperaHotel.ajax.reload();
                            tListaG.ajax.reload();
                        })
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
                
            },
            complete : function(){
                $(".outerDiv_S").css("display", "none")
            }
        });
    }

    $("#btnAleatorio").on('click',function(){
        validarAleatorio();
    })

    function validarAleatorio(){
        Swal.fire({
            text: '¿Estas seguro de formar los compañeros?',
            type:'info',
            customClass: 'myCustomClass-info',
            showCancelButton: true,
            confirmButtonColor: '#AA262C',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: '#767575',
            cancelButtonText: 'Cancelar'
        }).then(result=>{
            if(result.value){
                buscarUsuarios();
                
            }else{
                swal("Cancelado Correctamente");
            }
        })
    }

    function buscarUsuarios(){
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: {action: 'buscarUsuarios'},
            success: function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    pr = JSON.parse(data);
                    //console.log(pr);
                    var hombres = new Array();
                    var contH = 0;
                    var contM = 0;
                    var mujeres = new Array();
                    var companieroUno = new Array();
                    var companieroDos = new Array();
                    var compUno = new Array();
                    var compDos = new Array();
                    var fUno = new Array();
                    var fDos = new Array();
                    var h = 0;
                    var ultima;
                    var m = 0;
                    
                    
                    for(x in pr){
                        console.log(x);
                        genero = pr[x].Genero;
                        if(pr[x].Genero == '2'){
                            hombres[x] = pr[x].id_usuario;
                            contH++;
                        }
                        if(pr[x].Genero == '1'){
                            mujeres[x] = pr[x].id_usuario;
                        }
                    }
                    
                    console.log(hombres +"H")
                    //console.log(contH)
                    if(hombres != ''){
                        var y = 0;
                        var j = 1;
                        for(var i = 0; i<=contH; i+=2){
                            if(hombres[i] != null){
                                companieroUno[y] = hombres[i];
                                if(hombres[j] != null){
                                    companieroDos[y] = hombres[j];
                                    y++;
                                    j+=2;
                                }
                            }
                        }

                        //console.log(companieroUno);
                        //console.log(1)
                        //console.log(companieroDos);
                        //console.log(2)
                        
                        h = 1;
                        var elemUno = companieroUno.length;
                        var elemDos = companieroDos.length;
                        //console.log(elemUno);
                        //console.log(elemDos);

                        if(elemUno>elemDos){
                            companieroUno.pop();
                        }
                        //console.log(companieroUno);
                        //console.log(companieroDos);
                    }

                    if(mujeres != ''){
                        var l = 0;
                        var j = 1;
                        for(var i=0; i<=contM; i+=2){
                            if(mujeres[i] != null){
                                compUno[l] = mujeres[i];
                                if(mujeres[j] != null){
                                    compDos[l] = mujeres[j];
                                    l++;
                                    j+=2;
                                }
                            }
                        }
                        m = 1;

                        var elemU = compUno.length;
                        var elemD = compDos.length;

                        if(elemU > elemD){
                            compUno.pop();
                        }
                    }
                          
                    console.log(mujeres +"H")

                    if(h == 1 & m == 1){
                        setTimeout(() => { 
                            var fUno = companieroUno.concat(compUno);
                            var fDos = companieroDos.concat(compDos);
                            registrarCompanierosA(fUno,fDos);
                        }, 1000);
                    }

                    if(h == 1 && m ==0){
                        setTimeout(() => {
                            registrarCompanierosA(companieroUno, companieroDos);
                        }, 1000);
                        //registrarCompanierosA(companieroUno, companieroDos);
                    }

                    if(h == 0 && m == 1){
                        setTimeout(() => {
                            registrarCompanierosA(compUno, compDos);
                        }, 1000);
                        //registrarCompanierosA(compUno, compDos);
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){
    
            },
            complete : function(){
                $(".outerDiv_S").css("display","none")
            }
        });
    }

    function registrarCompanierosA(compUno, compDos){
        Data={
            action: "registrarCompanierosA",
            idUsuario: compUno,
            idComp: compDos
        }
        $.ajax({
            url: '../assets/data/Controller/hoteles/hotelControl.php',
            type: 'POST',
            data: Data,
            success: function(data){
                if(data == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                try{
                    pr = JSON.parse(data)
                    if(pr.estatus == 'ok'){
                        swal({
                            title: 'Asignado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            tListaG.ajax.reload();
                            tesperaHotel.ajax.reload();
                            tEspTransporte.ajax.reload();
                        })
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            error : function(){
    
            },
            complete : function(){
                $(".outerDiv_S").css("display","none")
            }
        });
    }






    
