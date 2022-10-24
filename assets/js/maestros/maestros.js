$(document).ready(function(){
    tareasCalificar();
    DatosProfesor();
    var idMaestro = $("#idMaestro").val();
    TabladeClases(idMaestro);
    //init_tabla_examen();
})
tTareas = null;

$('#clases-tab').on('click', function(){
    var idMaestro = $("#idMaestro").val();

    TabladeClases(idMaestro);
});

function TabladeClases(idMaestro){
    tClases = $("#datatable_clases").DataTable({
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
            url: '../assets/data/Controller/maestros/maestrosControl.php',
            type: 'POST',
            data: {action: 'obtenerListaClasesMaestro',
                    id: idMaestro},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);	
                if(e.responseText == 'sin_clases'){
                    swal({
                        title: 'Sin Tareas creadas',
                        icon: 'info',
                        button: false,
                        timer: 2500,
                    })
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
            },
            buttons: {
                copyTitle: 'Tabla Copiada de manera exitósa',
                copySuccess: {
                    _: 'Se copio %d filas',
                    1: 'Se copio 1 fila'
                }
            }
        },
        'bDestroy': true,
        'iDisplayLength': 10,
        'order':[
            [2,'desc']
        ],
    });
}


$("#tareas").on('click', function(){
    $("#examenes").removeClass("tab_active");
    $("#PerfilUser").removeClass("tab_active");

    $("#tareas").addClass('tab_active');
        $("#tab_examenes").fadeOut('fast', function(){
            $("#tab_PerfilUser").fadeOut('fast');
            $("#tab_tareas").fadeIn('fast');
                tCalificar.columns.adjust();
        })
});

$("#examenes").on('click', function(){
    $("#tareas").removeClass("tab_active");
    $("#PerfilUser").removeClass("tab_active");

    $("#examenes").addClass('tab_active');
        $("#tab_tareas").fadeOut('fast', function(){
            $("#tab_PerfilUser").fadeOut('fast');
            $("#tab_examenes").fadeIn('fast')
                //tExamen.columns.adjust();
                cargarCarrerasExamen();
        })
});
        
$("#PerfilUser").on('click', function(){
    $("#examenes").removeClass("tab_active");
    $("#tareas").removeClass("tab_active");

    $("#PerfilUser").addClass('tab_active');
        $("#tab_examenes").fadeOut('fast', function(){
            $("#tab_tareas").fadeOut('fast');
            $("#tab_PerfilUser").fadeIn('fast')
              //console.log("Elegido");
        });
});

  $("a[data-toggle=\"tab\"]").on("shown.bs.tab",function(){
    tCalificar.columns.adjust();
    tClases.columns.adjust();
	if(tTareas != null){
		tTareas.columns.adjust();
	}
    //tExamen.columns.adjust();
  });


  

/*function editarExamen(id){
    $("#formularioModificar")[0].reset();
    $("#modalModicarExamen").modal('show')
    //materiasDelDocente(id);
    recuperarExamen(id);
    $.ajax({
        url:'../assets/data/Controller/maestros/maestrosControl.php',
        type:'POST',
        data:{action: 'obtenerDatosExamen',
            id: id},
        success: function(data){
            try{
                pr = JSON.parse(data)
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}*/


$("#cancelarEditarExamen").on('click', function(){
    $("#modalModicarExamen").modal('hide');
})


var $modal = $('#modal_crop');
    var crop_image = document.getElementById('sample_image');
    var cropper;
    $('#upload_image').change(function(event){
        var files = event.target.files;
        var done = function(url){
            crop_image.src = url;
            $modal.modal('show');
        };
        if(files && files.length > 0)
        {
            reader = new FileReader();
            reader.onload = function(event)
            {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });
    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper(crop_image, {
            aspectRatio: 1,
            viewMode: 3,
            preview:'.preview'
        });
    }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper = null;
    });
    $('#crop_and_upload').click(function(){
        canvas = cropper.getCroppedCanvas({
            width:400,
            height:400
        });
        canvas.toBlob(function(blob){
            url = URL.createObjectURL(blob);
            $("#upload_image").text(url);
            

            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function(){
                var base64data = reader.result; 
                console.log(base64data);
                $('#upload_image64').val(base64data);
                $('#modal_crop').modal("hide");
                
            };
        });
    });

  /*$class = $(this).attr('id');
  $imgFile = $(this).prop('files')[0];
  //
  $urlFile = URL.createObjectURL($imgFile);
  console.log($imgFile['name']);
  $("#sample_image").prop('src','../assets/images/maestros/'+$imgFile['name']);*/  

  /*if(!archivos || !archivos.length){
        //No se seleccionan archivps
        image.src = "";
        input.value = "";
  }else if(input.getAttribute('acceppt').split(',').indexOf(extensiones < 0)){
    //'[png][.jpg][.jpeg]'
    alert("debes seleccionar una imagen");
  }else{
    let imagenUrl = URL.createObjectURL(archivos[0]);
    image.src = imagenUrl;
  }*/




function materiasDelDocente(idExamen){
    $("#editCursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'obtenerMateriasDocente',
            id: idExamen
        },
        dataType: 'JSON',
        success: function(data){
            $("#editCursoExamen").html('<option value="" selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#editCursoExamen").append('<option value='+registro.idCurso+'>'+registro.nombreCurso+'</option>');
            });
        }
    });
}

function DatosProfesor(){
    var idMaestro = $("#id").val();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'ObtenerDatosMaestro',
            id: idMaestro
        },
        dataType: 'JSON',
        success: function(data){
            $.each(data, function(key,registro){
                //$("#editCursoExamen").append('<option value='+registro.idCurso+'>'+registro.nombreCurso+'</option>');
                $("#Nombre").val(registro.nombres);
                $("#ApellidoPaterno").val(registro.aPaterno);
                $("#ApellidoMaterno").val(registro.aMaterno);
                $("#Sexo").val(registro.sexo);
                $("#Email").val(registro.email);
                $("#Telefono").val(registro.telefono);
                $("#Descripcion").val(registro.descripcion);

                $("#NombreUsuarioPerfil").html(registro.nombres+" "+registro.aPaterno+" "+registro.aMaterno);
                var foto = null;
                if(registro.foto != '' && registro.foto != null){
                    foto = registro.foto;
                }else{

                    foto = '../assets/images/maestros/no-user.jpeg';
                }
                $("#FotoUsuarioPerfil").prop('src',foto);
                $("#DescripcionUsuarioPerfil").html(registro.descripcion);
            });
        }
    });
}

function recuperarExamen(idExamen){
    recuperarPreguntasAplicar(idExamen);
    $("#formularioModificar")[0].reset();
    $("#divExamen").empty();
    $("#divAgregar").empty();
    $("#idExamenEditar").val(idExamen);
    $("#modalModicarExamen").modal('show');

    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'obtenerPreguntasExamen',
            id: idExamen
        },
        success: function(data){
            try{
                cont = 1;
                incisos = "ABCD";
                pr = JSON.parse(data);
                var num = Object.keys(pr).length
                /*for(x in num){
                    $('#divExamen').append($('<label>').
                                        text('Pregunta '+pr[x].pregunta)).append("</label>");
                }*/
                for(var x = 0 ;x < num ; x++){
                    var conteo = 0;
                    $("#divExamen").append($("<strong><label>").
                                    text("Pregunta "+cont)).
                                    append("</label></strong><br>").
                                    append($("<input>").
                                    attr('type','text').
                                    attr('class','form-control').
                                    attr('name','pregunta'+x).
                                    attr('id','pregunta'+x).
                                    attr('required', '')).append("</input><br>").
                                    append($("<input>").
                                    attr('type', 'radio').
                                    attr('class', 'Opcion'+x).
                                    attr('name', 'Opcion'+x).
                                    attr('value', 'A').
                                    attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                                    append($("<input>").
                                    attr('type', 'text').
                                    attr('name', 'TextoOpcion'+x+'_A').
                                    attr('id', 'TextoOpcion'+x+'_A').
                                    attr('style', 'border-color: transparent;').
                                    attr('required', '')).
                                    append("</input>").
                                    append($("<input>").
                                    attr('type', 'radio').
                                    attr('class', 'Opcion'+x).
                                    attr('name', 'Opcion'+x).
                                    attr('value', 'B').
                                    attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                                    append($("<input>").
                                    attr('type', 'text').
                                    attr('name', 'TextoOpcion'+x+'_B').
                                    attr('id', 'TextoOpcion'+x+'_B').
                                    attr('style', 'border-color: transparent;')).
                                    append("</input>").
                                    append($("<input>").
                                    attr('type', 'radio').
                                    attr('class', 'Opcion'+x).
                                    attr('name', 'Opcion'+x).
                                    attr('value', 'C').
                                    attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                                    append($("<input>").
                                    attr('type', 'text').
                                    attr('name', 'TextoOpcion'+x+'_C').
                                    attr('id', 'TextoOpcion'+x+'_C').
                                    attr('style', 'border-color: transparent;')).
                                    append("</input>").
                                    append($("<input>").
                                    attr('type', 'radio').
                                    attr('class', 'Opcion'+x).
                                    attr('name', 'Opcion'+x).
                                    attr('value', 'D').
                                    attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                                    append($("<input>").
                                    attr('type', 'text').
                                    attr('name', 'TextoOpcion'+x+'_D').
                                    attr('id', 'TextoOpcion'+x+'_D').
                                    attr('style', 'border-color: transparent;')).
                                    append("</input><br>");
                    $("#pregunta"+x).val(pr[x].pregunta);
                    $("#idExamen").val(idExamen);
                    //console.log(pr[x].opciones);
                    var resp = JSON.parse(pr[x].opciones);
                    //console.log(resp);
                    //console.log(Object.keys(resp).length);
                    for(var k in resp){
                        //console.log(conteo);
                        //console.log("TextoOpcion"+x+"_"+incisos[conteo]);
                        $("#TextoOpcion"+x+"_"+incisos[conteo]).val(k);
                        if(resp[k] == 1){
                            //$("#Opcion"+x).setAttribute('checked', 'checked');
                            //console.log("/////");
                            //console.log(resp[k]);
                            //console.log(incisos[conteo]);
                            //console.log(conteo);
                            //$("#Option"+resp[k]).val();
                            //console.log("/////");
                            //$(".Option"+x+" option[value="+incisos[conteo]+"]").prop('checked', '');
                            $(".Opcion"+x).each(function(index){
                                //$(this).val();
                                //console.log($(this).val());
                                //console.log(incisos[conteo]);
                                if($(this).val() == incisos[conteo]){
                                    $(this).prop('checked', true);
                                }
                            });
                        }
                        //console.log(k, resp[k]);
                        //console.log(k);
                        conteo++;
                    }
                    cont++;
                }
                    $("#divAgregar").append($("<button>").
                                    attr('class','btn btn-dark waves-effect waves-light').
                                    attr('type','button').
                                    attr('id', 'btnAgregarPreguntaEditar').
                                    attr('onclick','agregarPreguntaEditar()').
                                    attr('return', 'false').
                                    text('Agregar Pregunta')).append('</button>');
                    
                    $("#numPreguntas").val(num);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

/**
attr('onclick','agregarPreguntaEditar()').
attr('return', 'false'). */

function agregarPreguntaEditar(){
    var siguiente = $("#numPreguntas").val();
    var tituloSiguiente = siguiente;
    tituloSiguiente++;
    
    $("#divExamen").append($("<strong><label>").
                    text("Pregunta "+tituloSiguiente)).
                    append("</label></strong><br>").
                    append($("<input>").
                    attr('type','text').
                    attr('class','form-control').
                    attr('name','pregunta'+siguiente).
                    attr('id','pregunta'+siguiente).
                    attr('required', '')).append("</input><br>").
                    append($("<input>").
                    attr('type', 'radio').
                    attr('name', 'Opcion'+siguiente).
                    attr('id', 'Opcion'+siguiente).
                    attr('value', 'A').
                    attr('title', 'Marcar ésta opción como la correcta').
                    attr('checked',true)).append("</input>").
                    append($("<input>").
                    attr('type', 'text').
                    attr('name', 'TextoOpcion'+siguiente+'_A').
                    attr('style', 'border-color: transparent;').
                    attr('placeholder', 'Opción A...').
                    attr('required', '')).
                    append("</input>").
                    append($("<input>").
                    attr('type', 'radio').
                    attr('name', 'Opcion'+siguiente).
                    attr('id', 'Opcion'+siguiente).
                    attr('value', 'B').
                    attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                    append($("<input>").
                    attr('type', 'text').
                    attr('name', 'TextoOpcion'+siguiente+'_B').
                    attr('style', 'border-color: transparent;').
                    attr('placeholder', 'Opción B...')).
                    append("</input>").
                    append($("<input>").
                    attr('type', 'radio').
                    attr('name', 'Opcion'+siguiente).
                    attr('id', 'Opcion'+siguiente).
                    attr('value', 'C').
                    attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                    append($("<input>").
                    attr('type', 'text').
                    attr('name', 'TextoOpcion'+siguiente+'_C').
                    attr('style', 'border-color: transparent;').
                    attr('placeholder', 'Opción C...')).
                    append("</input>").
                    append($("<input>").
                    attr('type', 'radio').
                    attr('name', 'Opcion'+siguiente).
                    attr('id', 'Opcion'+siguiente).
                    attr('value', 'D').
                    attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                    append($("<input>").
                    attr('type', 'text').
                    attr('name', 'TextoOpcion'+siguiente+'_D').
                    attr('style', 'border-color: transparent;').
                    attr('placeholder', 'Opción D...')).
                    append("</input><br>");
    
    siguiente++;
    $("#numPreguntas").val(siguiente);
    //console.log(siguiente);
}

/*$("#btnAgregarPreguntaEditar").on('click', function(){
    var siguiente = $("#numPreguntas").val();
    siguiente++;
    $("#numPreguntas").val(siguiente);
    console.log(siguiente);
})*/

$("#formularioModificar").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'editarExamen');
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $("#formularioModificar button[type='submit']").attr("disabled",true);
        },
        success: function(data){
            switch(data){
                case 'preguntas_repetida':
                    swal({
                        title: "Verifique las preguntas.",
                        text: "Una pregunta se encuentra repetida.",
                        icon: "info",
                        timer: 5200
                    });
                    break;
                case 'preguntas_aplicar':
                    swal({
                        title: "Cantidad incorrecta.",
                        text: "La cantidad de preguntas a aplicar debe ser menor o igual a la cantidad de preguntas creadas.",
                        icon: "info",
                        timer: 5200
                    });
                    break;
                case 'fecha_incorrecta':
                    swal({
                        title: "Fecha incorrecta.",
                        text: "La fecha final no puede ser igual o menor a la fecha inicio.",
                        icon: "info",
                        timer: 4000
                    });
                    break;
                default:
                    try{
                        pr = JSON.parse(data);
                        //console.log(pr)
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Editado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                $("#formularioModificar")[0].reset();
                                tExamen.ajax.reload();
                                $("#modalModicarExamen").modal("hide");
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }
                    break;
            }
        },
        complete: function(e){
            $("#formularioModificar button[type='submit']").attr("disabled",false);
        }
    });
})

function editarTarea(idDocente, id){
    $("#formularioModificarTarea")[0].reset();
    $("#modalModicarTarea").modal('show');
    clasesDelDocente(idDocente);
    //recuperarExamen(id);
    $.ajax({
        url:'../assets/data/Controller/maestros/maestrosControl.php',
        type:'POST',
        data:{action: 'obtenerDatosTarea',
            id: id},
        success: function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr);
                $("#editNombreTarea").val(pr.titulo);
                $("#editDescripcionTarea").val(pr.descripcion);
                $("#editFechaLimiteTarea").val(pr.fecha);
                $("#editHoraLimiteTarea").val(pr.hora);
                $("#editClaseTarea").val(pr.idClase);
                $("#idTarea").val(pr.idTareas);
                //$("#editNombreExamen").val(pr.Nombre);
                //    $("#editCursoExamen").val(pr.idCurso);
                //$("#editFechaInicioExamen").val(pr.fechaInicio);
                //$("#editFechaFinExamen").val(pr.fechaFin);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#cancelarEditarTarea").on('click', function(){
    $("#modalModicarTarea").modal('hide');
})

function clasesDelDocente(idDocente){
    $("#editClaseTarea").empty();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'obtenerClasesDocente',
            id: idDocente
        },
        dataType: 'JSON',
        success: function(data){
            $("#editClaseTarea").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                if(registro.titulo != null){
                $("#editClaseTarea").append('<option value='+registro.idClase+'>'+registro.titulo+'</option>');
                }else{
                    $("#editClaseTarea").append('<option value='+registro.idClase+'>'+registro.nomClase+'</option>');
                }
            });
        }
    });
}

$("#formularioEditarUsuario").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action','ActualizarDatosMaestro');
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'error'){
                    swal({
                        icon: 'info',
                        text: pr.info
                    });
                    return;
                }
                if(pr.data > 0){
                    swal({
                        title: 'Perfil Editado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        DatosProfesor();
                        $("#upload_image").val('');
                        $("#upload_image64").val('');  
                    })
                }else{
                    swal({
                        title: 'No se detectaron cambios',
                        icon: 'info',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    });
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
    })

$("#formularioModificarTarea").on('submit', function(e){
e.preventDefault();
fData = new FormData(this);
fData.append('action','editarTarea');
$.ajax({
    url: '../assets/data/Controller/maestros/maestrosControl.php',
    type: 'POST',
    data: fData,
    contentType: false,
    processData: false,
    success : function(data){
        try{
            pr = JSON.parse(data)
            if(pr.estatus == "ok"){
                swal({
                    title: 'Editado Correctamente',
                    icon: 'success',
                    text: 'Espere un momento...',
                    button: false,
                    timer: 2500,
                }).then((result)=>{
                    $("#formularioModificarTarea")[0].reset();
                    tTareas.ajax.reload();
                    //tExamen.ajax.reload();
                    $("#modalModicarTarea").modal("hide");
                })
            }
        }catch(e){
            console.log(e)
            console.log(data)
        }
    }
});
})

function agregarClasesDelDocente(idDocente){
    $("#clasesDocente").empty();
    $("#clasesDocente").val();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {action: 'obtenerClasesDocente',
            id: idDocente},
        dataType: 'JSON',
        success: function(data){
            $("#clasesDocente").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                if(registro.titulo != null){
                $("#clasesDocente").append('<option value='+registro.idClase+'>'+registro.titulo+'</option>');
                }else{
                    $("#clasesDocente").append('<option value='+registro.idClase+'>'+registro.nomClase+'</option>');
                }
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_clases'){
                $("#clasesDocente").html('<option selected="true" value="" disabled="disabled">Sin clases asignadas</option>');
            }
        }
    });
}


$("#formularioCrearTarea").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'crearTarea');
    $.ajax({
        url:'../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus == "ok"){
                    swal({
                        title: 'Creada Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formularioCrearTarea")[0].reset();
                        tTareas.ajax.reload();
                        //tExamen.ajax.reload();
                        $("#modalCrearTarea").modal("hide");
                    })
                }

            }catch(e){
                console.log(data)
                console.log(e)
            }
        }
    });
})

$("#cerrarCrearTarea").on('click', function(){
    $("#modalCrearTarea").modal('hide');
    $("#formularioCrearTarea")[0].reset();
})


$('#listado-tareas-tab').on('click', function(){
    var idMaestro = $("#idMaestro").val();

    tTareas = $("#datatable_listado_tareas").DataTable({
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
            url: '../assets/data/Controller/maestros/maestrosControl.php',
            type: 'POST',
            data: {action: 'obtenerListaTareas',
                    id: idMaestro},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);	
                if(e.responseText == 'sin_materias_carrera'){
                    swal({
                        title: 'Sin Tareas creadas',
                        icon: 'info',
                        button: false,
                        timer: 2500,
                    })
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
            },
            buttons: {
                copyTitle: 'Tabla Copiada de manera exitósa',
                copySuccess: {
                    _: 'Se copio %d filas',
                    1: 'Se copio 1 fila'
                }
            }
        },
        'bDestroy': true,
        'iDisplayLength': 10,
        'order':[
            [3,'desc']
        ],
    });
})



function tareasCalificar(){
    var idMaestro = $("#idMaestro").val();
    
    tCalificar = $("#datatable_calificar_tarea").DataTable({
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
            url: '../assets/data/Controller/maestros/maestrosControl.php',
            type: 'POST',
            data: {action: 'obtenerTareasCalificar',
                    id: idMaestro},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);	
                if(e.responseText == 'sin_clases'){
                    swal({
                        title: 'Sin Tareas creadas',
                        icon: 'info',
                        button: false,
                        timer: 2500,
                    })
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
            },
            buttons: {
                copyTitle: 'Tabla Copiada de manera exitósa',
                copySuccess: {
                    _: 'Se copio %d filas',
                    1: 'Se copio 1 fila'
                }
            }
        },
        'bDestroy': true,
        'iDisplayLength': 10,
        'order':[
            [2,'desc']
        ],
    });
}

function calificarTarea(idEntrega){
    //console.log(idEntrega)
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'obtenerDatosCalificarTarea',
            id: idEntrega
        },
        success: function(data){
            try{
                pr = JSON.parse(data);
                $("#nombreTareaAlumno").val(pr.titulo);
                $("#nombreAlumno").val(pr.nombre);
                $("#comentarioAlumno").val(pr.comentario);
                $("#retroalimentacionAlumno").val(pr.retroalimentacion);
                $("#calificaciones").val(pr.calificacion);
                $("#fechaEntregaTarea").val(pr.fecha);
                $("#horaEntregaTarea").val(pr.hora);
                $("#idEntrega").val(pr.idEntrega);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#cerrarCalificarTarea").on('click', function(){
    $("#modalCalificarTarea").modal('hide');
})

$("#formularioCalificarTarea").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'calificarTarea');
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success: function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Calificación asignada',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formularioCalificarTarea")[0].reset();
                        tCalificar.ajax.reload();
                        $("#modalCalificarTarea").modal("hide");
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})

// funciones chuy
let respuestas_examen = [];
function revisar_entregas(examen){
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'consultar_entregas',
            id: examen
        },
        success: function(data){
            try{
                var entregas = JSON.parse(data);
                respuestas_examen = entregas;
                $("#tbl_examenes_entregados").DataTable().clear();
                for(i in entregas){
                    $("#tbl_examenes_entregados").DataTable().row.add([
                        entregas[i].alumno_nombre,
                        entregas[i].fechaPresentacion,
                        parseFloat(entregas[i].calificacion).toFixed(2)+' % ',
                        `<button class="btn btn-primary btn-sm" onclick="ver_examen('${entregas[i].idResultado}')"><i class="fa fa-eye" aria-hidden="true"></i></button>`
                    ]);
                }
                $("#tbl_examenes_entregados").DataTable().draw();
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
    $("#modalEntregasExamen").modal('show');
}

function ver_examen(resultado){
    respuesta = respuestas_examen.find( elm => elm.idResultado == resultado);
    //console.log(respuesta)
    if(respuesta){
        var tabla   = document.createElement("table");
        var tblHead = document.createElement("thead");

        var encabezado = document.createElement("tr");

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Número');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Pregunta');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Respuesta');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Resultado');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);


        tblHead.appendChild(encabezado);

        var tblBody = document.createElement("tbody");
        var j = 1;
        for (var i = 0; i < respuesta.respuestas.length; i++) {
            //console.log(respuesta.respuestas[i][0]);
            var hilera = document.createElement("tr");

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode(j);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode(respuesta.respuestas[i][3].pregunta);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode(respuesta.respuestas[i][1]);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode((respuesta.respuestas[i][2] == 1) ? 'Correcta' : 'Incorrecta');
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            tblBody.appendChild(hilera);
            j++;
        }

        tabla.appendChild(tblHead);
        tabla.appendChild(tblBody);
        tabla.setAttribute("class", "table");

        swal({
            title: 'Respuestas del alumno',
            content: tabla,
            className: 'swal-wide',
        })
    }
}
//


$("#cerrarCrearExamen").on('click', function(){
    $("#formularioCrearExamen")[0].reset();
    $("#modalCrearExamen").modal('hide');
})

function materiasDelDocenteCrearExamen(idDocente){
    $("#cursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'obtenerMateriasDocenteExamen',
            id: idDocente
        },
        dataType: 'JSON',
        success: function(data){
            $("#cursoExamen").html('<option value="" selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#cursoExamen").append('<option value='+registro.id_materia+'>'+registro.nombre+'</option>');
            });
        }
    });
}


function agregarMasPreguntas(){
    var numPregExa = $("#numeroPreguntaExamen").val();
    var tituloPregExa = numPregExa;
    tituloPregExa++;
    $("#divAgregarPregunta").append($("<strong><label>").
                            text("Pregunta "+tituloPregExa)).append("</label></strong><br>").
                            append($("<input>").
                            attr('type','text').
                            attr('class','form-control').
                            attr('name','preguntaExamen'+numPregExa).
                            attr('id','preguntaExamen'+numPregExa).
                            attr('required', '')).append("</input><br>").
                            append($("<input>").
                            attr('type', 'radio').
                            attr('name', 'OpcionExamen'+numPregExa).
                            attr('id', 'OpcionExamen'+numPregExa).
                            attr('value', 'A').
                            attr('title', 'Marcar ésta opción como la correcta').
                            attr('checked',true)).append("</input>").
                            append($("<input>").
                            attr('type', 'text').
                            attr('name', 'TextoOpcionExamen'+numPregExa+'_A').
                            attr('style', 'border-color: transparent;').
                            attr('placeholder', 'Opción A...').
                            attr('required', '')).
                            append("</input>").
                            append($("<input>").
                            attr('type', 'radio').
                            attr('name', 'OpcionExamen'+numPregExa).
                            attr('id', 'OpcionExamen'+numPregExa).
                            attr('value', 'B').
                            attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                            append($("<input>").
                            attr('type', 'text').
                            attr('name', 'TextoOpcionExamen'+numPregExa+'_B').
                            attr('style', 'border-color: transparent;').
                            attr('placeholder', 'Opción B...')).
                            append("</input>").
                            append($("<input>").
                            attr('type', 'radio').
                            attr('name', 'OpcionExamen'+numPregExa).
                            attr('id', 'OpcionExamen'+numPregExa).
                            attr('value', 'C').
                            attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                            append($("<input>").
                            attr('type', 'text').
                            attr('name', 'TextoOpcionExamen'+numPregExa+'_C').
                            attr('style', 'border-color: transparent;').
                            attr('placeholder', 'Opción C...')).
                            append("</input>").
                            append($("<input>").
                            attr('type', 'radio').
                            attr('name', 'OpcionExamen'+numPregExa).
                            attr('id', 'OpcionExamen'+numPregExa).
                            attr('value', 'D').
                            attr('title', 'Marcar ésta opción como la correcta')).append("</input>").
                            append($("<input>").
                            attr('type', 'text').
                            attr('name', 'TextoOpcionExamen'+numPregExa+'_D').
                            attr('style', 'border-color: transparent;').
                            attr('placeholder', 'Opción D...')).
                            append("</input><br>");

    numPregExa++;
    $("#numeroPreguntaExamen").val(numPregExa);

}

$("#btn-crear-examen").on('click', function(){
    $("#numeroPreguntaExamen").val("2");
    $("#modalCrearExamen").modal('show');
    $("#divAgregarPregunta").empty();
})

$("#formularioCrearExamen").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'crearExamen');
    fData.append('idDocente', $("#idDocente").val());
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $("#formularioCrearExamen button[type='submit']").attr("disabled",true);
        },
        success: function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus == "ok"){
                    swal({
                        title: 'Creado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formularioCrearExamen")[0].reset();
                        tExamen.ajax.reload();
                        $("#modalCrearExamen").modal("hide");
                    });
                }
                
            }catch(e){
                console.log(data)
                console.log(e)
            }
        },
        complete: function(e){
            $("#formularioCrearExamen button[type='submit']").attr("disabled",false);
        }
    });
})

$("#formularioAsignarPreguntasExamen").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'asignarPreguntas');
    fData.append('idDocente', $("#idDocente").val());
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        beforeSend: function(e){
            $("#formularioAsignarPreguntasExamen button[type='submit']").attr("disabled",true);
        },
        success: function(data){
            switch(data){
                case 'preguntas_repetida':
                    swal({
                        title: "Verifique las preguntas.",
                        text: "Una pregunta se encuentra repetida.",
                        icon: "info",
                        timer: 5200
                    });
                    break;
                case 'preguntas_aplicar':
                    swal({
                        title: "Cantidad incorrecta.",
                        text: "La cantidad de preguntas a aplicar debe ser menor o igual a la cantidad de preguntas creadas.",
                        icon: "info",
                        timer: 5200
                    });
                    break;
                default:
                    try{
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Preguntas Asignadas Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                $("#formularioAsignarPreguntasExamen")[0].reset();
                                tExamen.ajax.reload();
                                $("#modalAsignarPreguntas").modal("hide");
                            })
                        }
                    }catch(e){
                        console.log(data)
                        console.log(e)
                    }
                    break;
            }
        },
        complete: function(e){
            $("#formularioAsignarPreguntasExamen button[type='submit']").attr("disabled",false);
        }
    });
})


$("#selectListarExamenesCarrera").on('change', function(){
    var idMaestro = $("#idMaestro").val();
    var idCarrera = $("#selectListarExamenesCarrera").val();
    
    tExamen = $("#tabla_examenes_presentados").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            extend: "excel",
            className: "btn-primary"
        }],
        "ajax": {
            url: '../assets/data/Controller/maestros/maestrosControl.php',
            type: 'POST',
            data: {action: 'consultar_todo_examenes',
                    id: idMaestro,
                    idCarr: idCarrera},
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
                    1: 'Se copio 1 fila'
                }
            }
        },
        'bDestroy': true,
        'iDisplayLength': 10,
        'order':[
            [1,'asc']
        ],
    });
})

function cargarCarrerasExamen(){
    $("#selectListarExamenesCarrera").empty();
    var idMaestro = $("#idMaestro").val();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'cargarCarrerasExamen',
            idMaestro: idMaestro
        },
        dataType: 'JSON',
        success: function(data){
            $("#selectListarExamenesCarrera").html('<option value="" selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#selectListarExamenesCarrera").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    })
}

function asignarPreguntas(idExamen, idMaestro){
    //console.log(idExamen)
    //console.log(idMaestro)
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'buscarPreguntasExamen',
            idExamen: idExamen
        },
        success: function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr)
                if(pr == "0"){
                    $("#numeroPreguntaExamen").val("2");
                    $("#idExamen").val(idExamen);
                    $("#modalAsignarPreguntas").modal('show');
                    $("#divAgregarPregunta").empty();
                }else{
                    recuperarExamen(idExamen);
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}

function editar_clase(id_clase, nombre){
    listarCarreras()

    //$("#inputs_materiales a").addClass('d-none')
    //$("#inputs_recursos a").addClass('d-none')
    $('#form_actualizar_clase input[name="oldFilesMat"]').remove();
    $('#form_actualizar_clase input[name="oldFilesRec"]').remove();
    $("#inputs_materiales").html(`
        <a class="" href="#" onclick="$('#empty-materiales').click()">
            <i class="fas fa-upload"></i>
            <span>Agregar material de apoyo para la clase</span>
        </a>`)
    $("#inputs_recursos").html(`
        <a class="" href="#" onclick="$('#empty-recursos').click()">
            <i class="fas fa-upload"></i>
            <span>Agregar recursos para la clase</span>
        </a>`)

    $("#btn_agregarRecurso").addClass('d-none')
    $("#btn_agregarMaterial").addClass('d-none')
    $("#lbl_nombre_clase").html(nombre);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: {action:'consultar_by_id', id:id_clase},
        success: function(data){
            try{
                var resp = JSON.parse(data);
                var rec = new Array(), 
                apoy = new Array();
                
                $("#inp_edit_clase").val(resp.idClase)
                $("#inp_edit_link").val(resp.video);
                $("#inp_edit_nombre").val(resp.titulo);
                if(resp.fecha_hora_clase != null){
                    // var fech_clase = new Date(resp.fecha_hora_clase);
                    var fech_clase = resp.fecha_hora_clase.replace(' ', 'T');
                    $("#inp_edit_fecha").val(fech_clase);
                }
                $("#select_carreras_edit").val(resp.idCarrera);
                $("#select_carreras_edit").trigger('change');
                setTimeout(function(){
                    // $("#select_generacion_edit").html(`<option value="${resp.idGeneracion}">${resp.nombre_generacion}</option>`);
                    $("#select_generacion_edit").val($("#select_generacion_edit").find('option[value^="'+resp.idGeneracion+'-"]').val());
                }, 500);
                $("#select_materias_edit").html(`<option value="${resp.id_materia}">${resp.nombre_materia}</option>`);

                $("#list_materiales").html('');
                if(resp.apoyo.length == 0){
                    $("#inputs_materiales a").removeClass('d-none')
                    $("#inputs_recursos a").removeClass('d-none')
                }

                for(i in resp.apoyo){
                    // $("#inputs_materiales").html('')
                    $("#list_materiales").append(`<li> <a target="_blank" href="../assets/files/clases/apoyos/${resp.apoyo[i][0]}">${resp.apoyo[i][1]}</a></li>`)
                    apoy.push(`["${resp.apoyo[i][0]}","${resp.apoyo[i][1]}"]`);
                }

                $("#list_recursos").html('')
                if(resp.recursos.length == 0){
                    $("#inputs_recursos a").removeClass('d-none')
                }

                for(i in resp.recursos){
                    // $("#inputs_recursos").html('')
                    $("#list_recursos").append(`<li> <a target="_blank" href="../assets/files/clases/recursos/${resp.recursos[i][0]}">${resp.recursos[i][1]}</a></li>`)
                    rec.push(`["${resp.recursos[i][0]}","${resp.recursos[i][1]}"]`)
                }

                $("#editarclase-tab").trigger('click');
                $("#modalVerClases").modal('show');
                $('#form_actualizar_clase').append('<input type="hidden" name="oldFilesMat">');
                $('#form_actualizar_clase').append('<input type="hidden" name="oldFilesRec">');
                $('#form_actualizar_clase input[name="oldFilesMat"]').val('['+apoy+']');
                $('#form_actualizar_clase input[name="oldFilesRec"]').val('['+rec+']');
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}
function listarCarreras(){


    Data = {
        action: 'listarCarreras',
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }else{
                try{
                    pr = JSON.parse(data);
                    //console.log( pr );
                    opciones = '';
                    var opt = '<option disabled selected>Seleccione una carrera</option>';
                    for( i = 0; i < pr.length; i++ ){
                        opciones += '<label style="width:100%"><a class="list-group-item list-group-item-action"><input name="checkbox_c'+i+'" type="checkbox" id="checkbox_c'+i+'" value="'+pr[i]['idCarrera']+'"> '+pr[i]['nombre']+'</input></a></label>';
                        opt += '<option value="'+pr[i]['idCarrera']+'">'+pr[i]['nombre']+'</option>';
                    }
                    $("#select_carreras_edit").html( opt );
                    
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            }

        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display","none")
        }
    });

}//fin listarCarreras

$("#form_actualizar_clase").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'actualiza_clase');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form_actualizar_clase button[type='submit']").prop('disabled', true);
        },
        success: function(data){
            try{
                var actualizacion = JSON.parse(data);
                if(actualizacion == 1){
                    swal('Actualizado');
                }
                $("#modalVerClases").modal('hide');
                $("#btn_agregarMaterial").addClass("d-none");
                $("#btn_agregarRecurso").addClass("d-none");
                $("#form_actualizar_clase")[0].reset();
                $("#inputs_materiales").html('');
                $("#inputs_recursos").html('');
                $('#form_actualizar_clase input[name="oldFilesMat"]').remove();
                $('#form_actualizar_clase input[name="oldFilesRec"]').remove();
                tClases.ajax.reload(null,false);
                $('#clases-tab').click()
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#form_actualizar_clase button[type='submit']").prop('disabled', false);
        }
    });
})

$("#empty-materiales").on('click', function(){
    $("#inputs_materiales").html('');
    //$("#list_materiales").html('');
    $("#btn_agregarMaterial").removeClass("d-none");
    if($("#inputs_materiales").children().length == 0){
        $("#inputs_materiales").html(`
            <div class="row border-bottom dinamic_input_materiales pb-2">
                <div class="col-4">
                    <input type="file" name="input_materiales_1" required>
                </div>
                <div class="col-7">
                    <input type="text" name="input_nombre_materiales_1" required class="form-control" placeholder="Titulo del recurso">
                </div>
                <hr>
            </div>
        `);
    }
})
$("#empty-recursos").on('click', function(){
    $("#inputs_recursos").html('');
    //$("#list_recursos").html('');
    $("#btn_agregarRecurso").removeClass("d-none");
    if($("#inputs_recursos").children().length == 0){
        $("#inputs_recursos").html(`
            <div class="row border-bottom dinamic_input_recursos pb-2">
                <div class="col-4">
                    <input type="file" name="input_recursos_1" required>
                </div>
                <div class="col-7">
                    <input type="text" name="input_nombre_recursos_1" required class="form-control" placeholder="Titulo del recurso">
                </div>
            </div>
        `);
    }
})

function agregar_elemento(tipo){
    var agregar_otro = true;
    $(`.dinamic_input_${tipo}`).each(function(index){
        agregar_otro = (!$(this).find(':input[type="file"]').val() || $(this).find(':input[type="file"]').val() == '')? false : agregar_otro; 
        agregar_otro = (!$(this).find(':input[type="text"]').val() || $(this).find(':input[type="text"]').val() == '')? false : agregar_otro; 
    });
    if(agregar_otro){
        $(`#inputs_${tipo}`).append(`
            <div class="row border-bottom dinamic_input_materiales my-2 pb-2">
                <div class="col-4">
                    <input type="file" name="input_${tipo}_${$(`#inputs_${tipo}`).children().length + 1}" required>
                </div>
                <div class="col-7">
                    <input type="text" name="input_nombre_${tipo}_${$(`#inputs_${tipo}`).children().length + 1}" class="form-control" placeholder="Titulo del recurso" required>
                </div>
                <div class="col-1 d-flex align-items-center" onclick="$(this).parent().remove()" style="cursor:pointer;"><i class="fa fa-times float-right"></i></div>
            </div>
        `)
    }else{
        swal('Debes seleccionar un archivo');
    }
}

$("#select_carreras_edit").on('change', function(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'listarGeneraciones',idBuscar: $("#select_carreras_edit").val()},
        success: function(data){
            if(data == 'no_session'){
                swal("Vuelve a iniciar sesión!").then( () => {window.location.replace("index.php");});
            }
            try{
                var carr = JSON.parse(data);
                opciones = '<option disabled selected>Seleccione una Generación</option>';
                for( i = 0; i < carr.length; i++ ){
                    opciones += '<option value="'+carr[i]['idGeneracion']+'-'+carr[i]['id_plan_estudio']+'" class="form-control" >'+carr[i]['nombre']+'</option>';
                }
                $("#select_generacion_edit").html(opciones);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})

$("#select_generacion_edit").on('change', function(){
    var datos = $("#select_generacion_edit").val().split("-");
    Data = {
        action: 'listarCiclos',
        idGeneracion: datos[0],
        idPlan: datos[1]
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal("Vuelve a iniciar sesión!").then( () => {window.location.replace("index.php");});
            }
            try{
                var matr = JSON.parse(data);
                opt = '<option disabled selected>Seleccione un Ciclo</option>';
                for(i in matr){
                    if( pr[i]['tipo_ciclo'] == 1 ) $ciclon = "Cuatrimestre";
                    else if( pr[i]['tipo_ciclo'] == 2 ) $ciclon = "Semestre";
                    else $ciclon = "Trimestre";
                    opt += '<option value="'+matr[i]['ciclo_asignado']+'">'+$ciclon+' '+matr[i]['ciclo_asignado']+'</option>';
                }
                
                $("#select_ciclo_edit").html(opt);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})

$("#select_ciclo_edit").on('change', function(){
    var datos = $("#select_generacion_edit").val().split("-");
    Data = {
        action: 'listarMaterias',
        idCiclo: $("#select_ciclo_edit").val(),
        idPlan: datos[1]
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            try{
                var materias = JSON.parse(data);
                opciones = '<option disabled selected> Seleccione una materia </option>';
                for( i = 0; i < materias.length; i++ ){
                    opciones += '<option value="'+materias[i]['id_materia']+'" class="form-control">'+materias[i]['nombre']+'</option>';
                }
                $("#select_materias_edit").html(opciones);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
});



function recuperarPreguntasAplicar(idExamen){
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: "recuperarPreguntasAplicar",
            idExam: idExamen
        },
        success : function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr)
                //if(pr.preguntas_aplicar !=null){
                    $("#editTotalPregExamen").val(pr.preguntas_aplicar);
                //}
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}

function ingresar_a_la_clase(id_sesion,contrasena_sesion){
    $.ajax({
        url: '../assets/data/Controller/maestros/claseswebex',
        type: 'POST',
        data: {
            id_sesion: id_sesion,
            contrasena_sesion: contrasena_sesion
        },
        success: function(data){
            try{
                
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}
