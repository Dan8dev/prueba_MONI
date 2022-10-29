var tabla_directorio = null;
$(document).ready(function () {
    ver_todo_asistencias();
    consultar_eventos();
    init();
});

var id_Certificados = [];
function obtenerCertificados(idAlumno){
    var Comprobacion = id_Certificados.indexOf(idAlumno);

    if(Comprobacion == -1){
        id_Certificados.push(idAlumno);
    }else{
        id_Certificados.splice(id_Certificados.indexOf(idAlumno), 1);
    }

    if(id_Certificados.length<1){
        $("#BtnEnvioCertificados").prop('disabled',true);
    }else{
        $("#BtnEnvioCertificados").prop('disabled',false);
    }
}

$("#BtnEnvioSeleccionarTodos").on("click",function(e){
    id_Certificados = [];

    tAsistencias.cells().every((ix, g)=>{
        if(g == 2 && id_Certificados.length < 50){
            nodelm = tAsistencias.cell({row:ix, column:g}).node();
            
            CalNueva = $(nodelm).find('input').val();
            
            idCompuesto = $(nodelm).find('input').prop("checked", true);

            if(idCompuesto.is(':checked') == true){
                id_Certificados.push(parseInt(CalNueva));
            }
           
        }            
    });
});

$("#BtnEnvioCertificados").on("click",function(e){
    var idEvent = $("#idEventos").val();

    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/adminwebex/enviocertificados.php',
        data:{
            evento: idEvent,
            ids: id_Certificados
        },
        success: function(data){
            //console.log(data);
            json = JSON.parse(data);

            console.log(json);
            if(json.estatus == 'ok'){
               
                swal({
                    title: 'Certificación generada correctamente',
                    type: 'success',
                    text: 'Espere un momento...',
                    timer: 2500,
                }).then((result)=>{
                    //Ponerasd
                    tAsistencias.ajax.reload(null,false);
                    id_Certificados = [];
                    //LlenarTablaServicio();
                });

            }else{

                Swal.fire({
                    title: "Error",
                    text: "No se pudo generar la certficación",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#ef5c6a",
                    confirmButtonText: "ok"
                  });
                
            }
        },
    });
});

function init(params) {
    
    tabla_directorio = $("#table_directorio").DataTable({
        Processing: true,
        ServerSide: true,
        "lengthMenu": [ 10, 25, 50, 75, 100 ],
        "dom" :'Bfrtip',
            buttons:[{
                extend: "excel",
                className: "btn-primary"
            }, {
                extend: "pdf",
                title:'Directorio',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]}
                }, {
                    extend: "print",
                    title:'Directorio',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6],
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
        'order':[
            [0,'asc']
        ],
        "initComplete": function () {
            tablaDirectorio()
        }
        });
        $('#cursoExamen').on('change',function(){

            var value =  $('option:selected', this).text();
             $('#nameMat').val(value);
             });

        $('#check_extraordinario, #check_extraordinarioBanco').on('click', function(){
        
            var check = $(this);
            var id = $(this).attr('id');
            if(id == 'check_extraordinarioBanco' ){
                inputs = 'costsb input';
                divcostos = 'costsb';
                $ordinary = 'check_ordinarioBanco';
            }else{
                inputs = 'costs input';
                divcostos = 'costs';
                $ordinary = 'check_ordinario';
            }
    
            if(check.is(':checked')){
                swal.fire({
                type:'info',
                title:'Seleccionaste examen extraordinario, ¿Es correcto?',
                text:'',
                buttons: {
                cancel: {
                    text: "Cancel",
                    value: false,
                    visible: true,
                    className: "",
                    closeModal: true,
                  },
                   confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                  }
                  },
                }).then((result)=>{
            
                    if(!result){
                    check.prop('checked',false);
                        $('#'+divcostos).addClass('d-none'); 
                        $('#'+$ordinary).attr('disabled',false);    
                    }else{
                       
                        $('#'+divcostos).removeClass('d-none');
                        
                        $('#'+$ordinary).attr('disabled',true);
                    }
                })
                
            }else{
                $('#'+divcostos).addClass('d-none');
                
                $('#'+$ordinary).attr('disabled',false);
            }
        });
        $('#check_ordinario, #check_ordinarioBanco').on('click',function(){
    
            var id = $(this).attr('id');
            if(id == 'check_ordinarioBanco' ){
                inputs = 'check_extraordinarioBanco';
            }else{
                inputs = 'check_extraordinario';
            }
    
            if($(this).is(':checked')){
                
                $('#'+inputs).attr('disabled',true);
                
            }else{
                
                $('#'+inputs).attr('disabled',false);
            }
    });

    tEventos = $("#table-webex").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "ajax": {
            url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
            type: 'POST',
            data: {action: 'listarsesiones'},
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
            },
            buttons: {
                copyTitle: 'Tabla Copiada de manera exitósa',
                copySuccess: {
                    _: 'Se copio %d filas',
                    1: 'Se copio1 fila'
                }
            }
        },
        'bDestroy': true,
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ]
    });

    consultar_carreras()
}


function tablaDirectorio(){
    Band = 5;
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data:{
            action:'volcar_alumnos',
            vista: Band
        },
        success: function (data){
            try{
                var alumnos = JSON.parse(data);
                tabla_directorio.clear();
                for(a in alumnos){
                    alumn = alumnos[a];
                    telefono = alumn.telefono !== null ? alumn.telefono.replace(/[^0-9]+/g,'') : '';
                    celular = alumn.celular !== null ? alumn.celular.replace(/[^0-9]+/g,'') : '';
                    string_tel = '';
                    if(telefono != ''){
                        string_tel = telefono != celular && celular != '' ? `<a href="tel:${telefono}">${telefono}</a> / <a href="tel:${celular}">${celular}</a>` : `<a href="tel:${telefono}">${telefono}</a>`;
                    }
                    tabla_directorio.row.add([
                        `${alumn.aPaterno} ${alumn.aMaterno} ${alumn.nombre}`,
                        `${string_tel}`,
                        ` <a href="mailto:${alumn.email}"> <i class="fa fa-envelope"></i></a> ${alumn.email}`,
                        `${alumn.pais_nombre != '' && alumn.pais_nombre != null ? alumn.pais_nombre+' -' : ''} ${alumn.estado_nombre != '' && alumn.estado_nombre != null ? alumn.estado_nombre : ''},
							${alumn.ciudad.toUpperCase()}, ${alumn.colonia.toUpperCase()}, ${alumn.calle.toUpperCase()}`,
                        `${alumn.nombre_carrera}`,
                        `${alumn.nombre_generacion.substr(0, alumn.nombre_generacion.indexOf(' ',11))}`,
                        `${alumn.matricula}`,
                        ` <i class="fa fa-eye" style="cursor:pointer" onclick="$('#spn_${alumn.idalumno+'-'+a}').fadeToggle()"></i> <span id="spn_${alumn.idalumno+'-'+a}" style="display:none;">${alumn.contrasenia}</span>`
                    ])
                }
                tabla_directorio.draw();
            }catch(e){
                console.log(e);
            }
        }
    })
}

function ver_todo_asistencias(){
    tablaAsistenciaEventos()
    tablaAsistenciaTalleres()
}
 
function verAsistencia(idEvento){
    $("#modalAsistenciasEventos").modal("show");
    $("#idEventos").val(idEvento);

    tAsistencias = $("#TablaAsistenciaEventos").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            extend: "excel",
            className: "btn-primary"
        }, {
            extend: "pdf"
        }, {
            extend: "print"
        }],
        "ajax": {
            url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
            type: 'POST',
            data: {action: 'consultarAsistenciaEventos',
                    Evento:  idEvento},
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
        'iDisplayLength': 20,
        'order':[
            [0,'asc']
        ],
        });
}


function tablaAsistenciaEventos(){
    tAlumnos = $("#datatable-tablaAsistecias").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "dom" :'Bfrtip',
    buttons:[{
        extend: "excel",
        className: "btn-primary"
    }, {
        extend: "pdf"
    }, {
        extend: "print"
    }],
    "ajax": {
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarAsistenciaEventos'},
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
    });
}

function tablaAsistenciaTalleres(){
    tAlumnos = $("#datatable-tablaAsisteciasTalleres").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "dom" :'Bfrtip',
    buttons:[{
        extend: "excel",
        className: "btn-primary"
    }, {
        extend: "pdf"
    }, {
        extend: "print"
    }],
    "ajax": {
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarAsistenciaTalleres'},
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
    });
}

let band_foto = false;
function editarsesion(idsesion) { 
    $.ajax({
        url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
        type: "POST",
        data: {action: 'obtenersesion', idsesion: idsesion},
        beforeSend : function(){
            band_foto = false;
            $("#form-editarsesion")[0].reset();
            $("#editar_url_clase").attr('required', false);
            $("#loader").css("display", "block")
        },
        success: function(data){
            try{
                data = JSON.parse(data);
                // console.log(data)
                $('#editarnombresesion').val(data.nombre_clase);
                $('#editaridsesion').val(data.id_sesion);
                $('#editarcontrasenasesion').val(data.contrasena_sesion);
                $('#idsesion').val(idsesion);
                
                $("#tipo_sesion_edit").val(data.id_clase === null ? 'Evento' : 'Clase');
                if(data.id_clase !== null){
                    $("#container_url").removeClass('d-none');
                    
                    $("#editar_url_clase").val(data.video);
                    $("#id_clase").val(data.id_clase);
                    band_foto = (data.foto.trim() != '');
                    if(data.foto == '' && data.video != ''){
                        $("#editar_foto_clase").attr('required', true);
                    }else{
                        // band_foto = true;
                        $("#editar_foto_clase").attr('required', false);
                    }
                }else{
                    $("#container_url").addClass('d-none');
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $("#loader").css("display", "none")
        }
    });
 }

 $("#editar_url_clase").on("keyup", function(){
    if($("#editar_url_clase").val().trim() == '' && band_foto === true){
        $("#editar_url_clase").attr('required', true);
    }else if($("#editar_url_clase").val().trim() != '' && band_foto === false){
        $("#editar_foto_clase").attr('required', true);
    }else{
        $("#editar_foto_clase").attr('required', false);
        $("#editar_url_clase").attr('required', false);
    }
 });

 $("#editar_foto_clase").on('change',function(){
    $("#editar_url_clase").attr('required', true);
 });
 
 $("#form-editarsesion").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'actualizarsesion');
    $.ajax({
        url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#loader").css("display", "block")
        },
        success: function(data){
            try{
                if (data>0) {
                    Swal.fire({
                        title: "La sesión se actualizó exitosamente",
                        confirmButtonColor: "#ef5c6a"
                      }).then((result)=>{
                        $("#form-editarsesion")[0].reset();
                        $("#modal-editar-concepto").modal("hide");
                        tEventos.ajax.reload();
                    })
                    
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo actualizar la sesión",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#ef5c6a",
                        confirmButtonText: "ok"
                      })
                    
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $("#loader").css("display", "none")
        }
    });
})

function desactivarsesion(idsesion) {

    Swal.fire({
        title: "¿Esta seguro de deshabilitar la sesión?",
        text: "Los alumnos ya no tendrán acceso",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef5c6a",
        cancelButtonColor: "#D8D8D8",
        confirmButtonText: "Aceptar"
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
                type: "POST",
                data: {action: 'desactivarsesion', idsesion: idsesion},
                beforeSend : function(){
                    $("#loader").css("display", "block")
                },
                success: function(data){
                    try{
                        if (data>0) {
                            Swal.fire(
                                {
                                    title: "La sesión fue deshabilitada",
                                    confirmButtonColor: '#ef5c6a'
                                }
                            ).then((result)=>{
                                tEventos.ajax.reload();
                            })
                        } else {
                            Swal.fire(
                                {
                                    title: "No se pudo habilitar la sesión",
                                    confirmButtonColor: '#ef5c6a'
                                }
                            )
                            
                        }
                    }catch(e){
                        console.log(e);
                        console.log(data);
                    }
                },
                error: function(){
                },
                complete: function(){
                    $("#loader").css("display", "none")
                }
            });
        }else{
            $('#customSwitch'+idsesion+'').prop('checked', true)
        }
      })

}

function activarsesion(idsesion) {

    Swal.fire({
        title: "¿Está seguro de habilitar la sesión",
        text: "La sesión estará disponible",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef5c6a",
        cancelButtonColor: "#D8D8D8",
        confirmButtonText: "aceptar"
      }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
                    type: "POST",
                    data: {action: 'activarsesion', idsesion: idsesion},
                    beforeSend : function(){
                        $("#loader").css("display", "block")
                    },
                    success: function(data){
                        try{
                            if (data>0) {
                                Swal.fire(
                                    {
                                        title: "La sesión se habilitó",
                                        confirmButtonColor: '#ef5c6a'
                                    }
                                ).then((result)=>{
                                    tEventos.ajax.reload();
                                })
                            } else {
                                Swal.fire(
                                    {
                                        title: "No se pudo habilitar la sesión",
                                        confirmButtonColor: '#ef5c6a'
                                    }
                                )
                                
                            }
                        }catch(e){
                            console.log(e);
                            console.log(data);
                        }
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#loader").css("display", "none")
                    }
                });
            }else{
                $('#customSwitch'+idsesion+'').prop('checked', false)
            }
    });
    
}

$("#btn_new_sesion").on('click', function(e){
    $("#modal_nueva_sesion").modal("show");
})

function consultar_carreras() {
    $.ajax({
        url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
        type: "POST",
        data: {action: 'consultar_carreras'},
        success: function(data){
            try{
                var carreras = JSON.parse(data);
                var options = '<option disabled selected>Selecciona una carrera</option>';
                for (var i = 0; i < carreras.length; i++) {
                    options += '<option value="'+carreras[i].idCarrera+'">'+carreras[i].nombre+'</option>';
                }
                $("#select_carrera").html(options);
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

function consultar_eventos(){
    $.ajax({
        url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
        type: "POST",
        data: {action: 'consultar_eventos'},
        success: function(data){
            try{
                var carreras = JSON.parse(data);
                var options = '<option disabled selected>Selecciona un evento</option>';
                for (var i = 0; i < carreras.length; i++) {
                    options += `<option value="${carreras[i].idEvento}">(${carreras[i].fechaE} - ${carreras[i].tipo}) ${carreras[i].titulo.toUpperCase()}</option>`;
                }
                $("#select_evento").html(options);
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

$("#select_carrera").on('change', function(e){
    var carr = $(this).val();
    $.ajax({
        url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
        type: "POST",
        data: {action: 'consultar_generaciones_carrera', carrera: carr},
        success: function(data){
            try{
                var generaciones = JSON.parse(data);
                var options = '<option disabled selected>Selecciona una generación</option>';
                for (var i = 0; i < generaciones.length; i++) {
                    options += '<option value="'+generaciones[i].idGeneracion+'">'+generaciones[i].nombre+'</option>';
                }
                $("#select_generaciones").html(options);
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
})

$("#select_generaciones").on('change', function(e){
    var gen = $(this).val();
    $.ajax({
        url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
        type: "POST",
        data: {action: 'consultar_clases_generaciones', generacion: gen},
        success: function(data){
            try{
                var clases = JSON.parse(data);
                console.log(clases);
                var options = '<option disabled selected>Selecciona una clase para la sesión</option>';
                for (var i = 0; i < clases.length; i++) {
                    var string_f = clases[i].fecha_hora_clase != null && clases[i].fecha_hora_clase != "" ? `[${clases[i].fecha_hora_clase}] ` : "";
                    options += `<option value="${clases[i].idClase}" nombreProfesor="${clases[i].nombreM}" correoProfesor = ${clases[i].email} numeroProfesor = "${clases[i].telefono}">${string_f}${clases[i].titulo}</option>`;
                }
                $("#select_clases").html(options);
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
})

$("#select_clases").on("change",function(){
    $(".info-maestro").removeClass("d-none");

    $("#maestro_nombre").val($("#select_clases option:selected").attr("nombreProfesor"));
    $("#maestro_email").val($("#select_clases option:selected").attr("correoProfesor"));
    $("#maestro_numero").val($("#select_clases option:selected").attr("numeroProfesor"));
});

$("#modalPlanEstudios").on("hidden.bs.modal", function () {
    $(".info-maestro").addClass("d-none");

    $("#maestro_nombre").val("");
    $("#maestro_email").val("");
    $("#maestro_numero").val("");
});

$("#form-nueva-sesion").on('submit', function(e){
    e.preventDefault();
    var Clase =$("#select_clases").val();
    fdata = new FormData(this)
    fdata.append('action', 'agregar_nueva_sesion');
    fdata.append('select_clases', Clase);

    $.ajax({
        url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form-nueva-sesion button[type='submit']").attr("disabled", true)
        },
        success: function(data){
            try{
                resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
                    swal.fire({
                        title: "La sesión se ha agregado",
                        icon:"success"
                    })
                }else{
                    swal.fire({
                        icon:"info",
                        text:resp.info
                    })
                }
                $("#modal_nueva_sesion").modal("hide");
                tEventos.ajax.reload();
                $("#form-nueva-sesion")[0].reset();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $("#form-nueva-sesion button[type='submit']").attr("disabled", false)
        }
    });
})

$("#inp_idsesion").keyup(function(){              
    var ta      =   $("#inp_idsesion");
    letras      =   ta.val().replace(/ /g, "");
    ta.val(letras)
});
$("#editaridsesion").keyup(function(){              
    var edit      =   $("#editaridsesion");
    editar      =   edit.val().replace(/ /g, "");
    edit.val(editar)
});

$("#sesion_type").on('change', function(){
    if($(this).val() == 'clase'){
        $("#div_events_inputs").fadeOut('fast', ()=>{
            $("#div_class_inputs").fadeIn('fast');
        });
    }else{
        $("#div_class_inputs").fadeOut('fast', ()=>{
            $("#div_events_inputs").fadeIn('fast');
        });
    }
});

function obtenerValorFecha(value){
    $('#fechaA').val(value);
}

function agregarAsistencia(este, events){
    events.preventDefault();
    var idEvento = $("#idEventos").val();
    var fechaA = $("#fechaA").val();

    fData = new FormData($(este)[0]);
    fData.append('action','agregarAsistencia');
    fData.append('idEvento',idEvento);

    if(fechaA != ''){
        $.ajax({
            url: '../assets/data/Controller/adminwebex/adminwebexControl.php',
            type: "POST",
            data: fData,
            contentType: false,
            processData: false,
            success: function(data){
                try{
                    resp = JSON.parse(data);
                    if(resp.estatus == 'ok'){
                        swal.fire({
                            title: "La asistencia se ha registrado",
                            text: 'espere un momento...',
                            type: 'success',
                            timer: 2500
                        }).then(result => {
                            if(result){
                                tAsistencias.ajax.reload(null, false);
                            }
                        })
                    }else{
                        swal.fire({
                            title: "Error",
                            text:resp.info,
                            type: "warning"
                        })
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            }
        });
    }else{
        swal.fire({
            title: "Error",
            text:"Ingresa una fecha y hora válida",
            type: "error",
            timer: 2500
        }).then(result => {
            if(result){
                tAsistencias.ajax.reload(null, false);
            }
        })

    }
}