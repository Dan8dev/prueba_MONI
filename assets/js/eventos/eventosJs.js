let asistentes_taller = null;
let evento_id = null;
$(document).ready(()=>{
    getPaises();
    getInstituciones();
    getPlantillas();
    vImagen();
    vFondo();
    dataButtons();
    vDevImagen();
    vDevFondo();
    getPaisesMod();
    cambioTab();
    //getEstadosMod();

    select_eventos();
    $("#btn-taller-evento").attr('disabled', true)
})
function check(e){
    tecla = (document.all) ? e.keycode : e.which;

    if(tecla == 8){
        return true;
    }

    patron = /[A-Za-z0-9-_]/;
    tecla__final = String.fromCharCode(tecla);
    return patron.test(tecla__final);
}

function getPaises(){
    $("#pais").empty();
    Data = {
        action: "buscarPaises"
    }
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#pais").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#pais").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
            });
        },
        complete : function(){

        }
    });
}

$("#pais").on('change', function(){
    $("#estado").empty();
    idPais = $("#pais").val();
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: {
                action: "buscarEstados", 
                idPais: idPais
            },
        dataType: 'JSON',
        success : function(data){
            $("#estado").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#estado").prop('disabled', false);
                $("#estado").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
            if(data == ''){
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#estado").prop('disabled', true);
            }
        },
        complete : function (){

        }
    });
})

function getPaisesMod(){
    $("#devPais").empty();
    Data = {
        action: "buscarPaises"
    }
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#devPais").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#devPais").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
            });
        },
        complete : function(){
            
        }
    });
}

function getEstadosMod(){
    //$("#devEstado").empty();
    idPais = $("#devPais").val();
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: {
                action: "buscarEstados", 
                idPais: idPais
            },
        dataType: 'JSON',
        success : function(data){
            $("#devEstado").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#devEstado").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
        },
        complete : function (){

        }
    });
}

$("#devPais").on('change', function(){
    $("#devEstado").empty();
    idPais = $("#devPais").val();
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: {
                action: "buscarEstados", 
                idPais: idPais
            },
        dataType: 'JSON',
        success : function(data){
            $("#devEstado").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#devEstado").prop('disabled', false);
                $("#devEstado").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
            if(data == ''){
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#devEstado").prop('disabled', true);
            }
        },
        complete : function (){

        }
    });
})


function getInstituciones(){
    Data = {
        action: "buscarInstituciones"
    }
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success: function(data){
            $("#idInstitucion").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#idInstitucion").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
                $("#devIDInst").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
            });
        },
        complete : function(){

        }
    });
}

function getPlantillas(){
    Data = {
        action: "buscarPlantillas"
    }
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: "POST",
        data: Data,
        dataType: "json",
        success: function(data){
            $("#plantilla_bienvenida").html('<option selected="true" disabled="disabled">Seleccione</option>');
            //$("#newPlantilla").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                if(registro.plantilla_bienvenida !== ''){
                    //console.log(registro)
                $("#plantilla_bienvenida").append('<option value='+registro.plantilla_bienvenida+'>'+registro.plantilla_bienvenida+'</option>');
                $("#newPlantilla").append('<option value='+registro.plantilla_bienvenida+'>'+registro.plantilla_bienvenida+'</option>');
                }
            });
        }
    });
}

function vImagen(){
    const $seleccionArchivos = document.querySelector("#imagen"),
        $imagenPrevisualizacion = document.querySelector("#vImagen");
        //Escuchar cuando cambie
        $seleccionArchivos.addEventListener("change", () =>{
            const archivos = $seleccionArchivos.files;
            //si no hay archivos salimos de la función y quitamos la imagen
            if(!archivos || !archivos.length){
                $imagenPrevisualizacion.src = "";
                $("#vImagen").hide();  
                return;
            }
            const primerArchivo = archivos[0];
            const objetoURL = URL.createObjectURL(primerArchivo);
            
            $imagenPrevisualizacion.src = objetoURL;
            $("#vImagen").show();     
        });
}


function vFondo(){
    const $seleccionArchivos = document.querySelector("#imgFondo"),
        $imagenPrevisualizacion = document.querySelector("#vFondo");
        //Escuchar cuando cambie
        $seleccionArchivos.addEventListener("change", () =>{
            const archivos = $seleccionArchivos.files;
            //si no hay archivos salimos de la función y quitamos la imagen
            if(!archivos || !archivos.length){
                $imagenPrevisualizacion.src = "";
                $("#vFondo").hide();
                return;
            }
            const primerArchivo = archivos[0];
            const objetoURL = URL.createObjectURL(primerArchivo);
            
            $imagenPrevisualizacion.src = objetoURL;
            $("#vFondo").show();
        });
}

function vDevImagen(){
    const $seleccionArchivos = document.querySelector("#newImagen"),
        $imagenPrevisualizacion = document.querySelector("#devImagen");
        //Escuchar cuando cambie
        $seleccionArchivos.addEventListener("change", () =>{
            const archivos = $seleccionArchivos.files;
            //si no hay archivos salimos de la función y quitamos la imagen
            if(!archivos || !archivos.length){
                $imagenPrevisualizacion.src = "";
                //$("#devFondo").hide();
                return;
            }
            const primerArchivo = archivos[0];
            const objetoURL = URL.createObjectURL(primerArchivo);
            
            $imagenPrevisualizacion.src = objetoURL;
            //$("#devFondo").show();
        });
}

function vDevFondo(){
    const $seleccionArchivos = document.querySelector("#newFondo"),
        $imagenPrevisualizacion = document.querySelector("#devFondo");
        //Escuchar cuando cambie
        $seleccionArchivos.addEventListener("change", () =>{
            const archivos = $seleccionArchivos.files;
            //si no hay archivos salimos de la función y quitamos la imagen
            if(!archivos || !archivos.length){
                $imagenPrevisualizacion.src = "";
                //$("#devFondo").hide();
                return;
            }
            const primerArchivo = archivos[0];
            const objetoURL = URL.createObjectURL(primerArchivo);
            
            $imagenPrevisualizacion.src = objetoURL;
            //$("#devFondo").show();
        });
    }

$("#formularioRegistrar").on("submit", function(e){
    e.preventDefault();
    if($("#select_tipo_evento").val() == null){swal('Seleccione el tipo de evento'); return;}
    if($("#inptipoDuracion").val() == null){swal('Seleccione el tipo de duración'); return;}
    if($("#pais").val() == null){swal('Seleccione el país'); return;}
    if($("#estado").val() == null){swal('Seleccione el estado'); return;}
    if($("#selmodalidadEvento").val() == null){swal('Seleccione la modalidad del evento'); return;}
    if($("#plantilla_bienvenida").val() == null){swal('Seleccione la plantilla de correo que recibirán los prospectos'); return;}

    fData = new FormData(this);
    fData.append('action', 'registrarEvento');
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,

        beforeSend : function(){

        },
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
            if (data == 'no_format'){
                swal({
                    title: 'Formato no admitido',
                    icon: 'info',
                    text: 'Verifica que tu imagen sea: JPG, JPEG o PNG.',
                    button: false,
                    timer: 3500,
                });
            }
            try{
                pr = JSON.parse(data);
                if (pr.estatus == 'ok'){
                    //$("#formularioRegistrar")[0].reset();
                    $(".clave").hide();
                    swal({
                        title: 'Registrado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formularioRegistrar")[0].reset();
                        $("#vImagen").attr('src','');
                        $("#vImagen").hide();
                        $("#vFondo").attr('src','');
                        $("#vFondo").hide();
                        $("#estado").empty();
                        //$("#idInstitucion").empty();
                        //$("#plantilla_bienvenida").empty();
                        tEventos.ajax.reload();
                        /*$('html, body').animate({scrollTop:0}, 'slow');*/
                    })
                }else{
                    swal({
                        icon:'info',
                        text:'Por favor rellene todos los campos requeridos (*)'
                    });
                }
                if(data == 1){
                    Swal.fire({
                        title: 'No se puede repetir el nombre clave',
                        confirmButtonColor: '#ef5c6a',
                    }).then((result)=>{
                        //$('html,body').animate({scrollTop:0}, 'slow');
                        $(".clave").show();
                        //$(".clave").css("color","red"); 
                    })       
                }  
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error : function(){
        },
        complete: function(){
            $(".outerDiv_S").css("display","none")
        }
    }); 
})

$("#reiniciar").off('click').on("click", function(){
    $("#formularioRegistrar")[0].reset();
    $('html, body').animate({scrollTop:0}, 'slow');
    $("#vImagen").attr('src','');
    $("#vImagen").hide();
    $("#vFondo").attr('src','');
    $("#vFondo").hide();
})


function dataButtons(){
    tEventos = $("#datatable-eventos").DataTable({
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
            url: '../assets/data/Controller/eventos/eventosController.php',
            type: 'POST',
            data: {action: 'consultarEventos'},
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

}

function cambioTab(){
    /*$('[data-toggle="tab"]').on('click',function(e) {
            dataButtons();
        }*/
        $("a[data-toggle=\"tab\"]").on("shown.bs.tab",function(e){
            tEventos.columns.adjust();
        });
}

function buscarEvento(event){
    $("#formularioModificar")[0].reset();
    Data = {
        action: 'buscarEvento',
        idEditar: event
    }
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
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
                $(".devMessC").hide();
                $("#devImagen").show();
                $("#devFondo").show();
                $("#modalModify").modal('show');
                pr = JSON.parse(data);
                $("#devTipo").val(pr.data[0].tipo);
                $("#upd_estatus").val(pr.data[0].estatus);
                $("#devTitulo").val(pr.data[0].titulo);
                $("#devClave").val(pr.data[0].nombreClave);
                if(pr.data[0].video_url != '' && pr.data[0].video_url != null){
                    url = JSON.parse(pr.data[0].video_url);
                    if(url.length > 0){
                        $("#inp_enlaces_titulo").val(url[0][0]);
                        $("#inp_enlaces_url").val(url[0][1]);
                    }
                }
                $("#devFE").val(pr.data[0].fechaE);
                $("#devFD").val(pr.data[0].fechaDisponible);
                $("#devFL").val(pr.data[0].fechaLimite);
                $("#devLimite").val(pr.data[0].limiteProspectos);
                $("#devDuracion").val(pr.data[0].duracion);
                $("#devTipoD").val(pr.data[0].tipoDuracion);
                $("#devPais").val(pr.data[0].pais);
                getEstadosMod();
                setTimeout(function(){ 
                    var est = pr.data[0].estado;
                    if(est == 0){
                        //console.log('entre')
                        $("#devEstado").prop('disabled', true);
                    }else{
                    //console.log('no ent')
                    $("#devEstado").prop('disabled', false);
                    }
                    $("#devEstado").val(pr.data[0].estado);
                }, 1000);
                $("#devDireccion").val(pr.data[0].direccion);
                $("#devModalidad").val(pr.data[0].modalidadEvento);
                $("#devIDInst").val(pr.data[0].idInstitucion);
                $("#devPromocion").val(pr.data[0].codigoPromocional);
                $("#devImagen").attr('src', '../assets/images/generales/flyers/'+ pr.data[0].imagen);
                $("#devFondo").attr('src', '../assets/images/generales/fondos/' + pr.data[0].imgFondo);
                $("#newPlantilla").val(pr.data[0].plantilla_bienvenida);
                $("#devDescripcion").val(pr.data[0].descripcion);
                $("#idModify").val(pr.data[0].idEvento);
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

$("#ocultar").on("click", function(){
    $("#formularioModificar")[0].reset();
    //$("#formMod").hide();
    $("#devImagen").attr('src','');
    $("#devImagen").hide();
    $("#devFondo").attr('src','');
    $("#devFondo").hide();
})

$("#formularioModificar").on("submit", function(e){
    e.preventDefault();
    tx1 = $("#inp_enlaces_titulo").val().trim();
    tx2 = $("#inp_enlaces_url").val().trim();
    if((tx1 == '' && tx2.trim() != '') || (tx2 == '' && tx1.trim() != '')){
        swal('Por favor, llenar ambos campos del enlace de video del evento.');
    }else{
    fData = new FormData(this);
    fData.append('action', 'modificarEvento');
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
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
            if (data == 'no_format'){
                swal({
                    title: 'Formato no admitido',
                    icon: 'info',
                    text: 'Verifica que tu imagen sea: JPG, JPEG o PNG.',
                    button: false,
                    timer: 3500,
                });
            }
            try{
                pr = JSON.parse(data)
                //console.log(pr)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Modificado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formularioModificar")[0].reset();
                        //$("#devIDInst").empty();
                        //$("#newPlantilla").empty();
                        $(".devMessC").hide();
                        //$("#formMod").hide();
                        $("#devImagen").attr('src','');
                        $("#devImagen").hide();
                        $("#devFondo").attr('src','');
                        $("#devFondo").hide();
                        tEventos.ajax.reload(); 
                        $("#modalModify").modal("hide");
                    })
                }
                if(data == 1){
                    Swal.fire({
                        title: 'No se puede repetir el nombre clave',
                        confirmButtonColor: '#ef5c6a',
                    }).then((result)=>{
                    $(".devMessC").show();
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
    }
})

function validarEliminar(event){
    swal({
        title: 'Estas seguro de Eliminarlo',
        icon: 'info',
        buttons: {cancel: 'Cancelar',
                  confirm: 'Aceptar'
                }, 
        dangerMode: true,
    }).then((isConfirm)=>{
        if(isConfirm){
            eliminarEvento(event);
        }else{
            swal("Cancelado Correctamente");
        }
    });
}

function eliminarEvento(event){
    Data = {
        action: "eliminarEvento",
        idEliminar: event
    }
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosController.php',
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
                        tEventos.ajax.reload();
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

function select_eventos(){
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosControl.php',
        type: "POST",
        data: {action: 'listado_eventos'},
        beforeSend : function(){
        },
        success: function(data){
            try{
                resp = JSON.parse(data)
                var html_opc = "<option selected>Seleccione un evento</option>"
                var fe_hoy = new Date();
                for (var i = 0; i < resp.length; i++){
                    fe_ev = new Date(resp[i].fechaE+" 00:00:00");
                    fin_ev = new Date(fe_ev.setDate(fe_ev.getDate() + parseInt(resp[i].duracion)))
                    
                    if(fe_hoy <= fin_ev){
                        html_opc+=`<option value="${resp[i].idEvento}">${resp[i].titulo}</option>`
                    }
                }
                $("#select-eventos").html(html_opc)
                $("#select-eventos-taller").html(html_opc)
                $("#select-eventos-taller-p").html(html_opc)
                $("#select_evento_t").html(html_opc)
                $("#select_evento_po").html(html_opc)
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
        }
    });
}

$("#select-eventos").on('change', function(){
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosControl.php',
        type: "POST",
        data: {action: 'talleres_eventos', evento: $("#select-eventos").val()},
        beforeSend : function(){
        },
        success: function(data){
            try{
                var talleres = JSON.parse(data)
                console.log(talleres)
                if(talleres.estatus == 'ok'){
                    talleres = talleres.data;
                    $("#tabla_talleres").DataTable().clear();
                    for (var i = 0; i < talleres.length; i++) {
                        $("#tabla_talleres").DataTable().row.add([
                            talleres[i].nombre,
                            talleres[i].fecha,
                            `<button class="btn btn-primary" onclick="ver_asistentes(${talleres[i].id_taller}, '${talleres[i].nombre}', ${talleres[i].id_evento})"><i class="fas fa-list-ul"></i></button>`
                            ])
                            // `<button class="btn btn-primary" onclick="agregar_asistente(${talleres[i].id_taller}, ${talleres[i].id_evento})"><i class="fas fa-plus"></i></button>`
                    }
                    $("#tabla_talleres").DataTable().draw();
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
        }
    });
})

function ver_asistentes(id_taller, taller, evento){
    evento_id = evento
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'consultar_asistentes', taller: id_taller},
        beforeSend : function(){
            asistentes_taller = null;
            $("#apuntados_tab").click();
            $("#tabla_agregar_asistentes").DataTable().clear().draw()
            $("#ref_taller").val(id_taller)
        },
        success: function(data){
            try{
                intent = 0;
                var asist_t = JSON.parse(data)
                if(taller !== false){
                    $("#lbl_taller_nom").html(taller)
                }
                $("#tabla_asistentes_taller").DataTable().clear();
                if(asist_t.estatus == 'ok'){
                    asistentes_taller = asist_t.data; 
                    for (var i = 0; i < asist_t.data.length; i++) {
                        $("#tabla_asistentes_taller").DataTable().row.add([
                            asist_t.data[i].asistente_nom,
                            (parseInt(asist_t.data[i].pagado) == 1)? 'SI':'NO',
                            asist_t.data[i].fecha_registro
                            ]);
                    }
                }

                $("#tabla_asistentes_taller").DataTable().draw();
                $("#modal_asistentes_taller").modal('show')
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
        }
    });
}

$("#agregar_tab").on('click', function(){
    $.ajax({
        url: '../assets/data/Controller/prospectos/prospectoControl.php',
        type: "POST",
        data: {action: 'prospectos_permiso_evento', evento: evento_id},
        beforeSend : function(){
        },
        success: function(data){
            try{
                var agregar = JSON.parse(data)
                if(agregar.estatus == 'ok'){
                    $("#tabla_agregar_asistentes").DataTable().clear()
                    for (var i = 0; i < agregar.data.length; i++) {
                        var ix_asign = asistentes_taller.find( elm => elm.id_asistente == agregar.data[i].id_prospecto)
                        if(!ix_asign){
                            $("#tabla_agregar_asistentes").DataTable().row.add([
                                agregar.data[i].afiliado_nom,
                                `<button class="btn btn-primary" onclick="apuntar_a_taller(${agregar.data[i].id_prospecto})"><i class="fas fa-plus"></i></button>`
                            ]);
                        }
                    }
                    $("#tabla_agregar_asistentes").DataTable().draw()
                }
                
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
        }
    });
});

function apuntar_a_taller(persona){
    $.ajax({
        url: '../assets/data/Controller/eventos/eventosControl.php',
        type: "POST",
        data: {action: 'apartar_tallere_prospecto', persona: persona, taller:$("#ref_taller").val()},
        success: function(data){
            try{
                var agregado = JSON.parse(data)
                
                titulo_a = (agregado.estatus == 'ok')? 'Registro exitoso' : agregado.info;
                
                swal({
                    text:titulo_a
                }).then( (val)=>{
                    ver_asistentes($("#ref_taller").val(), false, evento_id)
                })
                
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

function tabla_talleres(){
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'consultar_talleres_evento', evento: $("#select-eventos-taller").val()},
        success: function(data){
            try{
                var talleres_evnt = JSON.parse(data)
                $("#datatable-talleres").DataTable().clear();
                if(talleres_evnt.estatus == 'ok'){
                    for(var i = 0; i < talleres_evnt.data.length; i++){
                        var string_nom = '';
                        string_nom = talleres_evnt.data[i].nombre;
                        string_nom += talleres_evnt.data[i].evento_privado == 1 ? ' <b><i>(Privado)</i></b>' : '';
                        $("#datatable-talleres").DataTable().row.add([
                            string_nom,
                            talleres_evnt.data[i].salon,
                            (talleres_evnt.data[i].costo == 0)? 'Gratis':talleres_evnt.data[i].costo,
                            talleres_evnt.data[i].fecha.substr(0,16),
                            (talleres_evnt.data[i].asistentes != null)? talleres_evnt.data[i].asistentes : 0,
                            `<button class="btn btn-primary" onclick="ver_asistentes_t(${talleres_evnt.data[i].id_taller}, ${talleres_evnt.data[i].evento_privado}, '${talleres_evnt.data[i].tipos_permitidos}')"><i class="fas fa-list-ul"></i></button>
                            <button class="btn btn-secondary" onclick="editar_taller(${talleres_evnt.data[i].id_taller})"><i class="fas fa-edit"></i></button>
                            `
                            ])
                    }
                }
                $("#datatable-talleres").DataTable().draw();
                $("#datatable-talleres").DataTable().columns.adjust();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

function tabla_ponencias(){
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'consultar_ponencias_evento', evento: $("#select-eventos-taller-p").val()},
        success: function(data){
            try{
                var ponecias_evnt = JSON.parse(data)
                $("#datatable-ponencias").DataTable().clear();
                if(ponecias_evnt.estatus == 'ok'){
                    for(var i = 0; i < ponecias_evnt.data.length; i++){
                        var string_nom = '';
                        string_nom = ponecias_evnt.data[i].nombre;
                        string_nom += ponecias_evnt.data[i].evento_privado == 1 ? ' <b><i>(Privado)</i></b>' : '';
                        $("#datatable-ponencias").DataTable().row.add([
                            string_nom,
                            ponecias_evnt.data[i].salon,
                            (ponecias_evnt.data[i].costo == 0)? 'Gratis':ponecias_evnt.data[i].costo,
                            ponecias_evnt.data[i].fecha.substr(0,16),
                            // (ponecias_evnt.data[i].asistentes != null)? ponecias_evnt.data[i].asistentes : 0,
                            // `<button class="btn btn-primary" onclick="ver_asistentes_t(${ponecias_evnt.data[i].id_ponencia}, ${ponecias_evnt.data[i].evento_privado}, '${talleres_evnt.data[i].tipos_permitidos}')"><i class="fas fa-list-ul"></i></button>
                            `<button class="btn btn-secondary" onclick="editar_ponencia(${ponecias_evnt.data[i].id_ponencia})"><i class="fas fa-edit"></i></button>
                            `
                            ])
                    }
                }
                $("#datatable-ponencias").DataTable().draw();
                $("#datatable-ponencias").DataTable().columns.adjust();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

$("#select-eventos-taller,#select-eventos-taller-p").on('change', function(){

    $select = $(this).attr('id');
    if( $select == 'select-eventos-taller'){
        tabla_talleres()
        if($("#"+$select).val() != 'Seleccione un evento'){
            $("#btn-taller-evento").attr('disabled', false)
        }else{
            $("#btn-taller-evento").attr('disabled', true)
        }
    }else{
        tabla_ponencias();
    }
})
let asistentes_talleres = [];
let tipos_taller = [];
function ver_asistentes_t(taller, tipo, permitidos){
    asistentes_talleres = [];
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'consultar_asistentes', taller: taller},
        success: function(data){
            try{
                var asist_t = JSON.parse(data)
                $("#taller_view").val(taller)
                $("#btn_agregar_a_taller").css('display','block')
                $("#alumnos_coincidencia").find('tbody').html('<tr><td colspan="2">No hay resultados de busqueda</td></tr>')
                $("#buscar-alumno").val('')
                $("#frm_agregar_alumno").css('display','none')
                if(tipo == 1){
                    $("#btn_agregar_a_taller").text('Editar tipos de alumnos')
                    $("#btn_agregar_a_taller").attr('type-e', 'tipo');
                    $(".for_type").css('display','flex')
                }else{
                    $("#btn_agregar_a_taller").text('Agregar alumnos')
                    $("#btn_agregar_a_taller").attr('type-e', 'alumno');
                    $(".for_type").css('display','none')
                }

                $("#tabla_asistentes_taller").DataTable().clear();
                if(asist_t.estatus == 'ok'){
                    if(tipo == 1){
                        tipos_taller = JSON.parse(permitidos);
                        if(tipos_taller === null){
                            tipos_taller = [];
                        }
                        detalles_talleres_priv(taller);
                    }else{
                        asistentes_talleres = asist_t.data;
                    }

                    for (var i = 0; i < asist_t.data.length; i++) {
                        $("#tabla_asistentes_taller").DataTable().row.add([
                            `<span class="d-none">${asist_t.data[i].id_asistente}</span>`+asist_t.data[i].asistente_nom,
                            asist_t.data[i].taller_nom,
                            (asist_t.data[i].hora_asistencia != null)? asist_t.data[i].hora_asistencia : '',
                        ]);
                    }
                }

                $("#tabla_asistentes_taller").DataTable().draw();

                $("#modal_asistentes_taller").modal('show')
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
        }
    });
    $("#modalPersonasTaller").modal('show');
}

function reporte_general(){
    $("#frm_agregar_alumno").css('display','none');
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'consultar_asistentes', general:true, evento: $("#select-eventos-taller").val()},
        success: function(data){
            try{
                var asist_t = JSON.parse(data)
                $("#btn_agregar_a_taller").css('display','none')
                $("#tabla_asistentes_taller").DataTable().clear();
                if(asist_t.estatus == 'ok'){
                    for (var i = 0; i < asist_t.data.length; i++) {
                        $("#tabla_asistentes_taller").DataTable().row.add([
                            `<span class="d-none">${asist_t.data[i].id_asistente}</span>`+asist_t.data[i].asistente_nom,
                            asist_t.data[i].taller_nom,
                            (asist_t.data[i].hora_asistencia != null)? asist_t.data[i].hora_asistencia : '',
                            ]);
                    }
                }

                $("#tabla_asistentes_taller").DataTable().draw();

                $("#modal_asistentes_taller").modal('show')
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
        }
    });
    $("#modalPersonasTaller").modal('show');
}

$("#btn_agregar_a_taller").on('click', function(){
    if($(this).attr('type-e') == 'alumno'){
        $("#frm_agregar_alumno").fadeIn('fast')
    }else if($(this).attr('type-e') == 'tipo'){
        buscar_tipos_alumnos();
        $("#frm_agregar_alumno").fadeIn('fast')
    }
})

$("#buscar-alumno").on('keypress', function(e){
    patron = /[A-Za-z]/;
    special = [13, 32];
    if(!patron.test(String.fromCharCode(e.which)) && !special.includes(e.which)){
        e.preventDefault(); 
    }
    if(e.which == 13 && $("#buscar-alumno").val().trim().length > 2){
        buscar_alumno($("#buscar-alumno").val().trim())
    }
})

$("#button-addon1").on('click', function(){
    if($("#buscar-alumno").val().trim().length > 2){
        buscar_alumno($("#buscar-alumno").val().trim())
    }
})

function buscar_alumno(nombre){
    $.ajax({
        url: '../assets/data/Controller/alumnos/alumnosInstitucionesControl.php',
        type: "POST",
        data: {action: 'buscar_alumno', nombre: nombre},
        beforeSend: function(){
            $("#alumnos_coincidencia").find('tbody').html('<tr><td colspan="2" class="text-center"><i class="fas fa-spinner fa-pulse fa-2x"></i></td></tr>')
        },
        success: function(rdata){
            try{
                var coincid = JSON.parse(rdata)
                $("#alumnos_coincidencia").find('tbody').html('')

                if(coincid.estatus == 'ok'){
                    if(coincid.data.length > 0){
                        $("#filtrar_alumnos_disp").fadeIn('fast')
                    }else{
                        $("#filtrar_alumnos_disp").fadeOut('fast')
                    }
                    for(var i in coincid.data){
                        var input_add = ''
                        if($(btn_agregar_a_taller).attr('type-e') == 'alumno'){
                            var ya_entaller = asistentes_talleres.find(elm => elm.id_asistente == coincid.data[i].idAsistente)
                            input_add = `<input id="check${coincid.data[i].idAsistente}" type="checkbox" ${ya_entaller ? 'checked':''} onchange="actualizar_asistencia(this, ${coincid.data[i].idAsistente}, ${$("#taller_view").val()})">`
                        }else{
                            input_add = `<button class="btn btn-sm btn-primary" onclick="agregar_a(${coincid.data[i].idAsistente}, ${$("#taller_view").val()}, true)"><i class="fas fa-user-plus"></i></button>
                                        <button class="btn btn-sm btn-secondary" onclick="agregar_a(${coincid.data[i].idAsistente}, ${$("#taller_view").val()}, false)"><i class="fas fa-user-minus"></i></button>`
                        }

                        $("#alumnos_coincidencia").find('tbody').append(`
                            <tr>
                                <td>${coincid.data[i].aPaterno} ${coincid.data[i].aMaterno} ${coincid.data[i].nombre}</td>
                                <td>${input_add}</td>
                            </tr>
                        `)
                    }
                }
            }catch(e){
                console.log(e);
                console.log(rdata);
            }
        },
        error: function(){
        },
        complete: function(){
        }
    });
}

$("#filtrar_alumnos_disp").on('keyup', function(){
    $("#alumnos_coincidencia").find('td').each(function(i){
        if($(this).index() == 0){
            if($(this).text().toLowerCase().indexOf($("#filtrar_alumnos_disp").val().toLowerCase()) == -1){
                $(this).parent().css('display','none')
            }else{
                $(this).parent().css('display','table-row')
            }
        }
    });
})

function actualizar_asistencia(nod, alumn, taller){

    var change = nod.checked;
    var ask = (change) ? ['agregar', 'a'] : ['remover', 'de'];
    swal({
        title: `¿Desea ${ask[0]} a este alumno ${ask[1]} este taller?`,
        icon:'info',
        buttons:['Cancelar', 'Aceptar']
    }).then((value)=>{
        if(value){
            $.ajax({
                url: '../assets/data/Controller/eventos/talleresControl.php',
                type: "POST",
                data: {action: 'actualizar_asistencia', alumno: alumn, taller: taller, change: change},
                success: function(rdata){
                    try{
                        var cambio = JSON.parse(rdata)
                        if(cambio.estatus == 'ok'){
                            swal('Se han aplicado los cambios.')
                        }else{
                            swal('Sin cambios.')
                        }
                        $("#modalPersonasTaller").modal('hide')
                    }catch(e){
                        console.log(e);
                        console.log(rdata);
                    }
                    tabla_talleres();
                }
            });
        }else{
            $(nod).prop('checked', !change)
            $(nod).attr('checked', !change)
        }
    })

    
}

function buscar_tipos_alumnos(){
    $.ajax({
        url: '../assets/data/Controller/instituciones/institucionesControl.php',
        type: "POST",
        data: {action: 'lista_instituciones'},
        beforeSend: function(){
            $("#tipos_alumnos").find('tbody').html('<tr><td colspan="2" class="text-center"><i class="fas fa-spinner fa-pulse fa-2x"></i></td></tr>')
        },
        success: function(rdata){
            try{
                var instit = JSON.parse(rdata)
                $("#tipos_alumnos").find('tbody').html('')
                if(instit.estatus == 'ok'){
                    for(var i in instit.data){
                        
                        if(parseInt(instit.data[i].estatus) == 1 && parseInt(instit.data[i].fundacion) == 0){
                            var input_add_t = '';
                            input_add_t = `<input id="check${instit.data[i].id_institucion}" type="checkbox" ${(tipos_taller.includes(parseInt(instit.data[i].id_institucion)) ? 'checked':'')} onchange="actualizar_tipos_alumnos(this, ${instit.data[i].id_institucion}, ${$("#taller_view").val()})">`
                            
                            $("#tipos_alumnos").find('tbody').append(`
                                <tr>
                                    <td>${instit.data[i].nombre}</td>
                                    <td>
                                    ${input_add_t}
                                    </td>
                                </tr>
                            `)
                        }
                    }
                }
            }catch(e){
                console.log(e);
                console.log(rdata);
            }
            tabla_talleres();
        }
    });
}

function actualizar_tipos_alumnos(nod, tipo, taller){
    var change = nod.checked;
    var ask = (change) ? ['agregar', 'a'] : ['remover', 'de'];
    swal({
        title: `¿Desea ${ask[0]} a este tipo de alumno ${ask[1]} este taller?`,
        icon:'info',
        buttons:['Cancelar', 'Aceptar']
    }).then((value)=>{
        if(value){
            $.ajax({
                url: '../assets/data/Controller/eventos/talleresControl.php',
                type: "POST",
                data: {action: 'actualizar_tipos_alumnos', tipo: tipo, taller: taller, change: change},
                success: function(rdata){
                    try{
                        var cambio = JSON.parse(rdata)
                        if(cambio.estatus == 'ok'){
                            swal('Se han aplicado los cambios.')
                        }else{
                            swal('Sin cambios.')
                        }
                        $("#modalPersonasTaller").modal('hide')
                    }catch(e){
                        console.log(e);
                        console.log(rdata);
                    }
                    tabla_talleres();
                }
            });
        }else{
            $(nod).prop('checked', !change)
            $(nod).attr('checked', !change)
        }
    })
}
function detalles_talleres_priv(taller){
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'detalles_talleres_priv', taller: taller},
        beforeSend: function(){
            $("#taller_priv_alumn_mas").find('tbody').html('<tr><td colspan="2" class="text-center"><i class="fas fa-spinner fa-pulse fa-2x"></i></td></tr>')
            $("#taller_priv_alumn_menos").find('tbody').html('<tr><td colspan="2" class="text-center"><i class="fas fa-spinner fa-pulse fa-2x"></i></td></tr>')
        },
        success: function(rdata){
            try{
                var alumnos_d = JSON.parse(rdata)
                $("#taller_priv_alumn_mas").find('tbody').html('')
                $("#taller_priv_alumn_menos").find('tbody').html('')
                for(var i in alumnos_d['incluidos']){
                    $("#taller_priv_alumn_mas").find('tbody').append(`
                        <tr>
                            <td>${alumnos_d['incluidos'][i].aPaterno} ${alumnos_d['incluidos'][i].aMaterno} ${alumnos_d['incluidos'][i].nombre}</td>
                            <td>${alumnos_d['incluidos'][i].nombre_institucion}</td>
                        </tr>
                    `)
                }
                for(var e in alumnos_d['excluidos']){
                    $("#taller_priv_alumn_menos").find('tbody').append(`
                        <tr>
                            <td>${alumnos_d['excluidos'][e].aPaterno} ${alumnos_d['excluidos'][e].aMaterno} ${alumnos_d['excluidos'][e].nombre}</td>
                            <td>${alumnos_d['excluidos'][e].nombre_institucion}</td>
                        </tr>
                    `)
                }
            }catch(e){
                console.log(e);
                console.log(rdata);
            }
            tabla_talleres();
        }
    });
}

function agregar_a(asistente, taller, lista){
    message = lista ? 'incluidos' : 'excluidos';
    swal({
        title: `Desea agregar al alumno a ${message}?`,
        icon:'info',
        buttons:['Cancelar', 'Aceptar']
    }).then((value)=>{
        if(value){
            $.ajax({
                url: '../assets/data/Controller/eventos/talleresControl.php',
                type: "POST",
                data: {action: 'agregar_a', taller: taller, asistente: asistente, lista: lista},
                success: function(rdata){
                    try{
                        var cambio = JSON.parse(rdata)
                        if(cambio.estatus == 'ok'){
                            swal('Se han aplicado los cambios.')
                        }else{
                            swal('Sin cambios.')
                        }
                        $("#modalPersonasTaller").modal('hide')
                    }catch(e){
                        console.log(e);
                        console.log(rdata);
                    }
                    tabla_talleres();
                }
            });
        }
    })
}

$("#btn-add-taller").on('click', function(){
    $("#inp_id_taller").val('');
    $("#modal_taller").modal('show');
    $("#lbl_action").html('Nuevo ');
    $("#form-taller")[0].reset();
    $("#select_ciertifica_t").val('2');
    $("#imagen_cert_t").attr('disabled', true);
    $("#imagen_cert_t").parent().css('display', 'none');
})
$("#select_ciertifica_t").on('change', function(){
    if($(this).val() == '2'){
        $("#imagen_cert_t").attr('disabled', true);
        $("#imagen_cert_t").parent().fadeOut('fast');
    }else{
        $("#imagen_cert_t").attr('disabled', false);
        $("#imagen_cert_t").parent().fadeIn('fast');
    }
})

$("#form-taller").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'taller_control');
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form-taller").find('button[type="submit"]').attr('disabled', true)
        },
        success: function(data){
            try{
                resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
                    if($("#inp_id_taller").val() == ''){
                        swal('Se ha registrado el taller.')
                    }else{
                        swal('Registro actualizado.')
                    }
                }else{
                    swal('Ha ocurrido un error al registrar.')
                }
                $("#modal_taller").modal('hide');
                tabla_talleres();
                console.log(resp);  
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $("#form-taller").find('button[type="submit"]').attr('disabled', false)
        }
    });
})
$("#form-ponencias").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'ponencia_control');
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form-ponencia").find('button[type="submit"]').attr('disabled', true)
        },
        success: function(data){
            try{
                resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
                    if($("#inp_id_ponencia").val() == ''){
                        swal('Se ha registrado la ponencia.')
                    }else{
                        swal('Registro actualizado.')
                    }
                }else{
                    swal('Ha ocurrido un error al registrar.')
                }
                $("#modal_ponencias").modal('hide');
                tabla_talleres();
                console.log(resp);  
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $("#form-ponencia").find('button[type="submit"]').attr('disabled', false)
        }
    });
})

function editar_taller(taller){
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'info_taller', id_taller: taller},
        success: function(data){
            try{
                resp = JSON.parse(data);
                
                if(resp.data.nombre_ponente != null && resp.data.nombre_ponente != ''){
                    $ponente = resp.data.nombre_ponente;
                   }else{ $ponente = 'Sin ponente'}
                if(resp.estatus == 'ok'){
                    $("#inp_id_taller").val(resp.data.id_taller)
                    $("#select_evento_t").val(resp.data.id_evento)
                    $("#inp_nombre_t").val(resp.data.nombre)
                    $("#select_tipo_t").val(resp.data.evento_privado)
                    $("#ponente").val($ponente)
                    $("#inp_fecha_e").val(resp.data.fecha.substr(0,10))
                    $("#inp_hora_tall").val(resp.data.fecha.substr(11, 5))
                    $("#inp_cupo_limite").val(resp.data.cupo)
                    $("#inp_nombre_salon").val(resp.data.salon)
                    $("#select_ciertifica_t").val(resp.data.certificado)
                    $("#inp_costo_t").val(moneyFormat.format(resp.data.costo.replace(/[^0-9.-]+/g,"")))
                    $("#inp_costo_t").keyup();
                    if(resp.data.costo.indexOf('pesos') > -1){
                        $("#select_tipo_pago_t").val('mxn')
                    }else{
                        $("#select_tipo_pago_t").val('usd')
                    }
                    $("#imagen_cert_t").attr('disabled', true);
                    $("#imagen_cert_t").parent().css('display', 'none');
                    
                    $("#modal_taller").modal('show');
                    $("#lbl_action").html('Editar ');

                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}
function editar_ponencia(taller){
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: {action: 'info_ponencia', id_taller: taller},
        success: function(data){
            try{
                resp = JSON.parse(data);
                
                if(resp.data.nombre_ponente != null && resp.data.nombre_ponente != ''){
                    $ponente = resp.data.nombre_ponente;
                   }else{ $ponente = 'Sin ponente'}
                if(resp.estatus == 'ok'){
                    $("#inp_id_ponencia").val(resp.data.id_ponencia)
                    $("#select_evento_po").val(resp.data.id_evento)
                    $("#inp_nombre_po").val(resp.data.nombre)
                    $("#select_tipo_po").val(resp.data.evento_privado)
                    $("#ponente_po").val($ponente)
                    $("#inp_fecha_ep").val(resp.data.fecha.substr(0,10))
                    $("#inp_hora_po").val(resp.data.fecha.substr(11, 5))
                    $("#inp_cupo_limite_po").val(resp.data.cupo)
                    $("#inp_nombre_salon_po").val(resp.data.salon)
                    // $("#select_ciertifica_t").val(resp.data.certificado)
                    $("#inp_costo_po").val(moneyFormat.format(resp.data.costo.replace(/[^0-9.-]+/g,"")))
                    $("#inp_costo_po").keyup();
                    // if(resp.data.costo.indexOf('pesos') > -1){
                    //     $("#select_tipo_pago_t").val('mxn')
                    // }else{
                    //     $("#select_tipo_pago_t").val('usd')
                    // }
                    // $("#imagen_cert_t").attr('disabled', true);
                    // $("#imagen_cert_t").parent().css('display', 'none');
                    
                    $("#modal_ponencias").modal('show');
                    $("#lbl_action-p").html('Editar ');

                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}