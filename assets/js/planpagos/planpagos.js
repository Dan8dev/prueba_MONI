$(document).ready(function () {
    //initPlanPago();
    planpagos();
});

$("#selectAsignacion").change(function (){
    var valor = $("#selectAsignacion").val();
    if(valor==1){
        obtenerAfiliados();
        $("#divCarreras").hide();
        $("#selectCarreras").val('default');
        $("#selectCarreras").selectpicker('refresh');
        $("#divEventos").hide();
        $("#selectEventos").val('default');
        $("#selectEventos").selectpicker('refresh');
        $("#divAfiliados").show();
        $("#divMensualidad").show();
        $("#divReinscripcion").show();
        $("#nMensualidades").attr('required','');
        $("#costoMensualidad").attr('required','');
        $("#nReinscripcion").attr('required','');
        $("#costoReinscripcion").attr('required','');
        $("#evtAct").val(0);
        $("#tipoCarrera").val(0);
    }
    if(valor==2){
        obtenerCarreras();
        $("#divAfiliados").hide();
        $("#selectAfiliados").val('default');
        $("#divEventos").hide();
        $("#selectEventos").val('default');
        $("#divCarreras").show();
        $("#divMensualidad").show();
        $("#divReinscripcion").show();
        $("#divcostotitulacion").show();
        $("#nMensualidades").attr('required','');
        $("#costoMensualidad").attr('required','');
        $("#nReinscripcion").attr('required','');
        $("#costoReinscripcion").attr('required','');
        $("#evtAct").val(0);
    }
    if(valor==4){
        obtenerEventos();
        $("#divAfiliados").hide();
        $("#selectAfiliados").val('default');
        $("#divCarreras").hide();
        $("#selectCarreras").val('default');
        $("#divEventos").show();
        $("#divMensualidad").hide();
        $("#divReinscripcion").hide();
        $("#nMensualidades").removeAttr('required');
        $("#costoMensualidad").removeAttr('required');
        $("#nReinscripcion").removeAttr('required');
        $("#costoReinscripcion").removeAttr('required');
        $("#selectCarreras").removeAttr('required');
        $("#diasdecorte").removeAttr('required');
        $("#costotitulacion").removeAttr('required');
        $("#fechalimitepagotit").removeAttr('required');
        $("#evtAct").val(1);
        $("#tipoCarrera").val(0);
        $("#divcostotitulacion").hide();

        $("#costoInscripcion").keyup(function(){
            obtenerTotalEvent();
        });
    }
})


$("#selectCarreras").change(function(){
    var selectorCarr = [];
    var cer=0;
    var tsu=0;
    $('#selectCarreras option:checked').each(function(){
        selectorCarr = $(this).attr("data-info")
        //console.log(selectorCarr)
    })
    if(selectorCarr == 1){
        $("#divMensualidad").hide();
        $("#divReinscripcion").hide();
        $("#divcostotitulacion").hide();
        $("#nMensualidades").removeAttr('required');
        $("#costoMensualidad").removeAttr('required');
        $("#nReinscripcion").removeAttr('required');
        $("#costoReinscripcion").removeAttr('required');

        $("#diasdecorte").removeAttr('required');
        $("#costotitulacion").removeAttr('required');
        $("#fechalimitepagotit").removeAttr('required');

        swal({
            title: 'Advertencia solo se crear?? concepto de inscripci??n',
            icon: 'info',
            text: 'No tendr?? concepto de mensualidad y reinscripci??n',
            button: false,
            timer: 3500,
        });
        
        $("#costoInscripcion").keyup(function(){
            obtenerTotalCer();
        });
        
    }

    if(selectorCarr == 2){
        $("#divMensualidad").show();
        $("#divReinscripcion").show();
        $("#divcostotitulacion").show();
        $("#nMensualidades").attr('required','');
        $("#costoMensualidad").attr('required','');
        $("#nReinscripcion").attr('required','');
        $("#costoReinscripcion").attr('required','');
    }
    $("#tipoCarrera").val(selectorCarr);
})


function obtenerTotalCer(){
    if($("#costoInscripcion").val().trim() != ''){
        var cI = parseInt($("#costoInscripcion").val());
        $("#total").val(cI);
    }
}

function obtenerTotalEvent(){
    if($("#costoInscripcion").val().trim() != ''){
        var cI = parseInt($("#costoInscripcion").val());
        $("#total").val(cI);
    }
}

/*

$("#selectCarreras").on('change', function(clickedIndex){
    //var carrTipo = $("#selectCarreras option").eq(clickedIndex).data('info');

    var j;
    var carrTipo = [];
    carrTipo[j] = $("#selectCarreras option:selected").data('info');
    console.log(carrTipo[j]);
    j++;

    //var carr2 = $("#selectCarreras").find('[data-info]').val();
    //var carrTipo = $("#selectCarreras option:selected").data('info');
    
    //var carrTipo = $("#selectCarreras").val();
    
    //console.log(carr2)

    if(carrTipo == 1){
        $("#divMensualidad").hide();
        $("#divReinscripcion").hide();
    }
    if(carrTipo == 2){
        $("#divMensualidad").show();
        $("#divReinscripcion").show();
    }
})*/


function obtenerAfiliados(){
    $("#selectAfiliados").selectpicker('destroy');
    var Data = {
       action: "obtenerAfiliados"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#selectAfiliados").html('<option value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#selectAfiliados").append('<option value='+registro.idAsistente+'>'+registro.nombre+'</option>');
                $("#selectAfiliados").selectpicker('refresh');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
        }
    });
}

function getAfiliadosMod(id){
    var Data = {
       action: "obtenerAfiliadosMod",
       id: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#nuevoSelectAfiliados").html('<option value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#nuevoSelectAfiliados").append('<option value='+registro.idAsistente+'>'+registro.nombre+'</option>');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
        }
    });
}

function getCarrerasMody(id){
    var Data = {
        action: "obtenerCarrerasMod",
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#nuevoSelectCarreras").html('<option value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#nuevoSelectCarreras").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
        }
    });
}


function getEventosModify(id){
    $("#nuevoSelectEventos").selectpicker('destroy');
    var Data = {
        action: "obtenerEventosMod",
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#nuevoSelectEventos").html('<option value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#nuevoSelectEventos").append('<option value='+registro.idEvento+'>'+registro.nombreClave+'</option>');
                $("#nuevoSelectEventos").selectpicker('refresh');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
        }
    });
}

function obtenerCarreras(){
    $('#selectCarreras').empty()
    var Data = {
       action: "obtenerCarreras"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#selectCarreras").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#selectCarreras").append('<option data-info="'+registro.tipo+'" value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            if(xhr.responseText == 'sin_carrera'){
                swal({
                    title: "No hay carreras registradas",
                    text: "Se necesitan dar de alta",
                    icon: "info",
                });
            }
        }
    });
}



function obtenerEventos(){
    $('#selectEventos').empty()
    var Data = {
        action: "obtenerEventos"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success: function(data){
            $("#selectEventos").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data,function(key,registro){
                $("#selectEventos").append('<option value='+registro.idEvento+'>'+registro.nombreClave+'</option>');
            });
        }, 
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            if(xhr.responseText == 'sin_evento'){
                swal({
                    title: "No hay eventos registrados",
                    text: "Se necesitan dar de alta",
                    icon: "info",
                }).then((result)=>{
                    $('#modal-pagos').modal('hide')
                })
            }
        }
    });
}


$("#formPlanPago").on('submit', function(e){
    $("#total").prop('disabled',false);
    $("#totalusd").prop('disabled',false);
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'crearPlanPago');
    fdata.append('creador_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $('#btnCrear').prop('disabled', true);
        },
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            if(data == 'numero_invalido'){
                swal({
                    title: 'No se admite valor 0',
                    icon: 'info',
                    text: 'Cambia el precio por uno mayor',
                    button: false,
                    timer: 3400,
                });
            }
            try{
                pr = JSON.parse(data)
                if (pr.estatus == 'ok'){
                    swal({
                        title: 'Plan creado con ??xito',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formPlanPago")[0].reset();
                        $("#modal-pagos").modal("hide");
                        tPlan.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#total").prop('disabled',true);
            $("#totalusd").prop('disabled',false);
            $("#btnCrear").prop('disabled',false);
        }
    });
})

$("#formModPlan").on('submit', function(e){
    $("#nuevoTotal").prop('disabled',false);
    $("#nuevoTotalusd").prop('disabled',false);
    $("#nuevoNoReinscripcion").prop('disabled',false);
    $("#btnMod").prop('disabled',true);
    e.preventDefault();
    fdata = new FormData(this);
    fdata.append('action', 'modificarPlan');
    fdata.append('modificado_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            if(data == 'numero_invalido'){
                swal({
                    title: 'No se admite valor 0',
                    icon: 'info',
                    text: 'Cambia el precio por uno mayor',
                    button: false,
                    timer: 3400,
                });
            }
            if(data == 'select_vacio'){
                swal({
                    title: 'Campo vac??o',
                    icon: 'info',
                    text: 'Seleccione la opci??n correspondiente',
                    button: false,
                    timer: 3400,
                });
            }
            try{
                pr = JSON.parse(data)
                if (pr.estatus == 'ok'){
                    swal({
                        title: 'Modificado correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formModPlan")[0].reset();
                        $("#modalModPlan").modal("hide");
                        tPlan.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#nuevoTotal").prop('disabled',true);
            $("#nuevoTotalusd").prop('disabled',true);
             $("#nuevoNoReinscripcion").prop('disabled',true);
            $("#btnMod").prop('disabled',false);
        }
    });
})


function planpagos(){
    tPlan = $("#table-pagos").DataTable({
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
            url: '../assets/data/Controller/planpagos/planpagosControl.php',
            type: 'POST',
            data: {action: 'obtenerPlanesPago'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);	
                /*if(e.responseText == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesi??n!",
                        text: "La informacion no se actualiz??",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }*/
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
                'sLast': '??ltimo',
                'sNext': 'Siguiente',
                'sPrevious': 'Anterior'
            },
            buttons: {
                copyTitle: 'Tabla Copiada de manera exit??sa',
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

function buscarPlanPago(id){
    $("#formModPlan")[0].reset();
    $('#nuevodiasdecorte').numeric();
    //validarId(id);
    Data = {
        action: 'buscarPlan',
        idPlan: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n!",
                    text: "La informacion no se actualiz??",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                
                $('#nuevoCostoInscripcion').numeric();
                $('#nuevoCostoMensualidad').numeric();
                $('#nuevoCostoInscripcionusd').numeric();
                $('#nuevoCostoMensualidadusd').numeric();
                $('#nuevoNoMensualidades').numeric();
                $('#nuevoCostoReinscripcion').numeric();
                $('#nuevoCostoReinscripcionusd').numeric();
                $('#nuevoNoReinscripcion').numeric();
                $('#nuevoCostoTitulacion').numeric();
                $('#nuevoCostoTitulacionusd').numeric();

                pr = JSON.parse(data);
                $("#id").val(id);//idplan
                if (pr.data[0].idCarrera!=null) {//modificar carrera de certificaion
                    $("#nuevoSelectAsignacion").empty();
                    $('#nuevoSelectAsignacion').append($('<option>', {value: '2', text: 'Carrera'}));
                    $('#nuevoSelectAsignacion').val(2);
                    $("#idconceptoins").val(pr.data[0].id_concepto);
                    $('#nombrecarreraaevt').html(pr.data[0].nombreCarrer);
                    $("#evtActMod").val(0);
                    $("#tipoCarreraMod").val(pr.data[0].tipoCarrera);
                }
                else if (pr.data[0].idEvento!=null) {//modificar Evento
                    $("#nuevoSelectAsignacion").empty();
                    $('#nuevoSelectAsignacion').append($('<option>', {value: '4', text: 'Evento'}));
                    $('#nuevoSelectAsignacion').val(4);
                    $("#idconceptoins").val(pr.data[0].id_concepto);
                    $('#nombrecarreraaevt').html(pr.data[0].nombreEvent);
                    $("#evtActMod").val(1);
                    $("#tipoCarreraMod").val(0);
                }
                $('#nuevonombre').val(pr.data[0].nombre);
                $('#nuevoTotal').val(pr.data[0].total);
                $('#nuevoTotalusd').val(pr.data[0].totalusd);
                $('#nuevoCostoInscripcion').val(pr.data[0].precio);

                //costo inscripcion USD
                $('#nuevoCostoInscripcionusd').val(pr.data[0].precio_usd);
                //costo inscripcion USD

                var fechalimitepago = pr.data[0].fechalimitepago.split(' ');
                $('#nuevafechalimitedepagoins').val(fechalimitepago[0])//inscripcion=0
                if (pr.data.length==1) {//si es un plan de pago para onceptos de inscripcion
                    $('#divMensualidadMod').hide();
                    $('#divReinscripcionMod').hide();
                    $('#nuevodivtitulacion').hide();
                    $("#nuevoCostoMensualidad").removeAttr('required');
                    $("#nuevoNoMensualidades").removeAttr('required');
                    $("#nuevodiasdecorte").removeAttr('required');
                    $("#nuevoCostoReinscripcion").removeAttr('required');
                    $("#nuevoNoReinscripcion").removeAttr('required');
                    $("#nuevoCostoTitulacion").removeAttr('required');

                }
                if (pr.data.length==4) {//si es un plan de pago para onceptos de inscripcion mesuallidad reins y tit
                    $('#divMensualidadMod').show();
                    $('#divReinscripcionMod').show();
                    $('#nuevodivtitulacion').show();
                    $('#idconceptocostomens').val(pr.data[1].id_concepto);
                    $('#nuevoNoMensualidades').val(pr.data[1].numero_pagos);
                    $('#nuevoCostoMensualidad').val(pr.data[1].precio);//mensualidad=1

                    //costo inscripcion USD
                    $('#nuevoCostoMensualidadusd').val(pr.data[1].precio_usd);//mensualidad=1
                    //costo inscripcion USD

                    $('#idconceptocostoreins').val(pr.data[2].id_concepto);
                    $('#nuevoNoReinscripcion').val(pr.data[2].numero_pagos);//reinscripcion=2
                    $('#nuevoCostoReinscripcion').val(pr.data[2].precio);

                    //costo reinscripcion USD
                    $('#nuevoCostoReinscripcionusd').val(pr.data[2].precio_usd);
                    //costo reinscripcion USD

                    $('#idconceptocostotit').val(pr.data[3].id_concepto);
                    $('#nuevoCostoTitulacion').val(pr.data[3].precio);//titulacion=3

                    //costo titulaci??n USD
                    $('#nuevoCostoTitulacionusd').val(pr.data[3].precio_usd);//titulacion=3
                    //costo titulaci??n USD

                    var diacorte= pr.data[1].fechalimitepago;
                    $('#nuevodiasdecorte').val(diacorte.substr(-11,2));
                    var fechalimitepagotit = pr.data[3].fechalimitepago.split(' ');
                    $('#nuevafechalimitedepagotit').val(fechalimitepagotit[0])
                }
                tPlan.ajax.reload();
               
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}


$("#ocultarPlan").on('click', function(){
    $("#modal-pagos").modal('hide');
    $("#formPlanPago")[0].reset();
    $("#divAfiliados").hide();
    $("#divCarreras").hide();
})

$("#boton-crear-plan").on('click', function(){
    $("#divAfiliados").hide();
    $("#divCarreras").hide();
    $("#formPlanPago")[0].reset();
    $('#diasdecorte').numeric();
    $('#costoInscripcion').numeric();
    $('#costoMensualidad').numeric();
    $('#costoInscripcionusd').numeric();
    $('#costoMensualidadusd').numeric();
    $('#nMensualidades').numeric();
    $('#costoReinscripcion').numeric();
    $('#costoReinscripcionusd').numeric();
    $('#nReinscripcion').numeric();
    $('#costotitulacion').numeric();
    $('#costotitulacionusd').numeric();
})

$("#ocultarModPlan").on('click', function(){
    $("#modalModPlan").modal('hide');
    $("#formModPlan")[0].reset();
})


function validareliminarplan(id){
    Swal.fire({
        text: '??Estas seguro de eliminarlo?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            eliminarPlanPago(id);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function eliminarPlanPago(id){
    Data = {
        action: 'eliminarPlan',
        idPlan: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/planpagosControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesi??n",
                    text: "La informaci??n no se actualiz??",
                    icon: "info"
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                },2000);
            }
            try{
                if(data != 'no_session'){
                    swal({
                        title: 'Elimando Correctamente',
                        text: 'Espere un momento',
                        icon: 'success',
                        button: false,
                        timer: 2500
                    }).then((result)=>{
                        tPlan.ajax.reload();
                    })
                }
            }catch(e){

            }
        }
    })
}

// begin obtener total mxn
function obtenerTotalusd(){
    if($("#costoMensualidadusd").val().trim() != ''|| $("#nMensualidadesusd").val().trim() != ''|| $("#costoInscripcionusd").val().trim() != ''|| $("#costoReinscripcionusd").val().trim() != ''|| $("#costotitulacionusd").val().trim() != ''){
        var cMusd = parseInt($("#costoMensualidadusd").val());
        var nMusd = parseInt($("#nMensualidadesusd").val());
        var cIusd = parseInt($("#costoInscripcionusd").val());
        var cRusd = parseInt($("#costoReinscripcionusd").val());
        var insusd = parseInt($("#costotitulacionusd").val());
        mulMusd = cMusd * nMusd;
        mulRusd = cRusd;
        $("#totalusd").val(cIusd + mulMusd + mulRusd + insusd);
    }
}
    
$("#costoMensualidadusd").keyup(function(){
    obtenerTotalusd();
});

$("#nMensualidadesusd").keyup(function(){
    obtenerTotalusd();
});

$("#costoInscripcionusd").keyup(function(){
    obtenerTotalusd();
});

$("#costoReinscripcionusd").keyup(function(){
    obtenerTotalusd();
})
$("#costotitulacionusd").keyup(function(){
    obtenerTotalusd();
})
// end obtener total mxn

// begin obtener total usd
function obtenerTotal(){
    if($("#costoMensualidad").val().trim() != ''|| $("#nMensualidades").val().trim() != ''|| $("#costoInscripcion").val().trim() != ''|| $("#costoReinscripcion").val().trim() != ''|| $("#costotitulacion").val().trim() != ''){
        var cM = parseInt($("#costoMensualidad").val());
        var nM = parseInt($("#nMensualidades").val());
        var cI = parseInt($("#costoInscripcion").val());

        var cR = parseInt($("#costoReinscripcion").val());
        var ins = parseInt($("#costotitulacion").val());
        mulM = cM * nM;
        mulR = cR;
        $("#total").val(cI + mulM + mulR + ins);
    }
}
    
$("#costoMensualidad").keyup(function(){
    obtenerTotal();
});

$("#nMensualidades").keyup(function(){
    obtenerTotal();
    obtenerTotalusd();
    $('#nMensualidadesusd').val($("#nMensualidades").val());
});

$("#costoInscripcion").keyup(function(){
    obtenerTotal();
});



$("#costoReinscripcion").keyup(function(){
    obtenerTotal();
})
$("#costotitulacion").keyup(function(){
    obtenerTotal();
})
//end obtener total usd
//modal-modificar

function obtenerTotalMod(){
    if($("#nuevoCostoMensualidad").val().trim() != '' || $("#nuevoNoMensualidades").val().trim() != '' || $("#nuevoCostoInscripcion").val().trim() != '' ||  $("#nuevoNoReinscripcion").val().trim() != '' || $("#nuevoCostoReinscripcion").val().trim() != ''){
        var cM = parseInt($("#nuevoCostoMensualidad").val()) || 0;
        var nM = parseInt($("#nuevoNoMensualidades").val()) || 0;
        var cI = parseInt($("#nuevoCostoInscripcion").val()) || 0;
        var nR = parseInt($("#nuevoNoReinscripcion").val()) || 0;
        var cR = parseInt($("#nuevoCostoReinscripcion").val()) || 0;
        var nuevotit = parseInt($("#nuevoCostoTitulacion").val()) || 0;
        mulM = cM * nM;
        mulR = cR * nR;
        $("#nuevoTotal").val(cI + mulM + mulR + nuevotit);
    }
}

$("#nuevoCostoMensualidad").keyup(function(){
    obtenerTotalMod();
});

$("#nuevoNoMensualidades").keyup(function(){
    obtenerTotalMod();
});

$("#nuevoCostoInscripcion").keyup(function(){
    obtenerTotalMod();
});

$("#nuevoNoReinscripcion").keyup(function(){
    obtenerTotalMod();
})

$("#nuevoCostoReinscripcion").keyup(function(){
    obtenerTotalMod();
})

$("#nuevoCostoTitulacion").keyup(function(){
    obtenerTotalMod();
})

//calculo USD MODIFICAR plan de pago precio de lista
function obtenerTotalModusd(){
    if($("#nuevoCostoMensualidadusd").val().trim() != '' || $("#nuevoNoMensualidades").val().trim() != '' || $("#nuevoCostoInscripcionusd").val().trim() != '' ||  $("#nuevoNoReinscripcion").val().trim() != '' || $("#nuevoCostoReinscripcionusd").val().trim() != ''||  $("#nuevoNoReinscripcion").val().trim() != '' || $("#nuevoCostoTitulacionusd").val().trim() != ''){
        var cM = parseInt($("#nuevoCostoMensualidadusd").val());
        var nM = parseInt($("#nuevoNoMensualidades").val());
        var cI = parseInt($("#nuevoCostoInscripcionusd").val());
        var nR = parseInt($("#nuevoNoReinscripcion").val());
        var cR = parseInt($("#nuevoCostoReinscripcionusd").val());
        var nuevotit = parseInt($("#nuevoCostoTitulacionusd").val());
        mulM = cM * nM;
        mulR = cR * nR;
        $("#nuevoTotalusd").val(cI + mulM + mulR + nuevotit);
    }
}

$("#nuevoCostoMensualidadusd").keyup(function(){
    obtenerTotalModusd();
});

$("#nuevoNoMensualidades").keyup(function(){
    obtenerTotalModusd();
});

$("#nuevoCostoInscripcionusd").keyup(function(){
    obtenerTotalModusd();
});

$("#nuevoNoReinscripcion").keyup(function(){
    obtenerTotalModusd();
})

$("#nuevoCostoReinscripcionusd").keyup(function(){
    obtenerTotalModusd();
})

$("#nuevoCostoTitulacionusd").keyup(function(){
    obtenerTotalModusd();
})
//calculo USD MODIFICAR plan de pago precio de lista


function obtenerTotalCerMod(){
    if($("#nuevoCostoInscripcion").val().trim() != ''){
        var cI = parseInt($("#nuevoCostoInscripcion").val());
        $("#nuevoTotal").val(cI);
    }
}

function obtenerTotalEventMod(){
    if($("#nuevoCostoInscripcion").val().trim() != ''){
        var cI = parseInt($("#nuevoCostoInscripcion").val());
        $("#nuevoTotal").val(cI);
    }
}
