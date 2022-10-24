$(document).ready(function () {
    init();
});

function init() {
    obteneralumnos();
    $("#boton-crear-promocion").click(function (e) { 
        
        $('#modal-crear-promocion').modal("show");

        $('#selecalumnogeneracion').change(function (e) { 
            var alumnoogeneracion = $('#selecalumnogeneracion').val();
            if (alumnoogeneracion==1) {//alumno
                $('#mostraralumnos').show();
                $('#mostrargeneraciones').show();
                $('#mostrarcarreras').hide();
                // obteneralumnos();
                obtenergeneraciones();
            }
            if (alumnoogeneracion==2) {//generacion
                $('#mostrargeneraciones').show();
                $('#mostraralumnos').hide();
                $('#mostrarcarreras').hide();
                obtenergeneraciones();
            }
            if (alumnoogeneracion==3) {//carrera
                $('#mostrarcarreras').show();
                $('#mostrargeneraciones').hide();
                $('#mostraralumnos').hide();
                obtenercarreras();
            }
            if (alumnoogeneracion==4) {// OFERTA
                obtenergeneraciones();
                $('#mostrargeneraciones').show();
                $('#mostraralumnos').hide();
            }
            obtenerconceptosdepago();
        });

    });

    $("#form-crearpromocion").on('submit', function(e){
        e.preventDefault();
        fdata = new FormData(this)
        fdata.append('action', 'crearpromocion');
        fdata.append('creador_por', usrInfo.idAcceso);
        $.ajax({
            url: '../assets/data/Controller/planpagos/promocionesControl.php',
            type: "POST",
            data: fdata,
            contentType:false,
            processData:false,
            beforeSend : function(){
                $("#loader").css("display", "block")
            },
            success: function(data){
                try{
                    var data = JSON.parse(data);
                    if (data.data>0) {
                        swal({
                            title: 'Promoción creada con éxito',
                            icon: 'success',
                            text: '',
                            confirmButtonText: "Aceptar",
                            timer: 2500,
                        }).then((result)=>{
                            $("#form-crearpromocion")[0].reset();
                            $("#modal-crear-promocion").modal('hide');
                            tablapromociones.ajax.reload();
                        })
                        
                    } else {
                        swal({
                            title: "Error",
                            text: data.hasOwnProperty('mensaje')? data.mensaje : "No se pudo crear la promoción",
                            icon: "info",
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#2ecc71",
                            closeOnConfirm: false
                        });
                        
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
            },
            complete: function(){
                tablapromociones.ajax.reload();
            }
        });
    })  
    
    $("#form-editapromocion").on('submit', function(e){
        e.preventDefault();
        fdata = new FormData(this)
        fdata.append('action', 'editarpromocion');
        fdata.append('creador_por', usrInfo.idAcceso);
        $.ajax({
            url: '../assets/data/Controller/planpagos/promocionesControl.php',
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
                        swal({
                            title: 'Promoción actualizada con éxito',
                            icon: 'success',
                            text: '',
                            confirmButtonText: "Aceptar",
                            timer: 2500,
                        }).then((result)=>{
                            $("#modal-editar-promocion").modal('hide');
                            tablapromociones.ajax.reload();
                        })
                        
                    } else {
                        swal({
                            title: "Error",
                            text: "No se pudo actualizar la promoción",
                            type: "error",
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#2ecc71",
                            closeOnConfirm: false
                        });
                        
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
            },
            complete: function(){
                tablapromociones.ajax.reload();
            }
        });
    })


    $('#tabpromociones').click(function (e) {

        tablapromociones = $("#table-promociones").DataTable({
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
                url: '../assets/data/Controller/planpagos/promocionesControl.php',
                type: 'POST',
                data: {action: 'obtenerPromociones'},
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
        
    });
       

}

function obteneralumnos(alumno = null) {
    $("#listaralumnos").empty();
    var Data = {
       action: "obteneralumnos"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/promocionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#listaralumnos").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $("#listaralumnoseditar").html('<option selected="true" disabled="disabled">Seleccione</option>');
            // $(".input-alumnos").select2('destroy'); // <-----
            $(".input-alumnos").empty();
            nombre_alumno = false;
            $.each(data, function(key,registro){
                $("#listaralumnos").append('<option value='+registro.id_afiliado+'>'+registro.nombre+'</option>');
                $("#listaralumnoseditar").append('<option value='+registro.id_afiliado+'>'+registro.nombre+'</option>');
                $(".input-alumnos").append('<option value='+registro.id_afiliado+'>'+registro.nombre+'</option>');
                if(registro.id_afiliado == alumno && alumno != null){
                    nombre_alumno = registro.nombre;
                }
            });
            $(".input-alumnos").select2(); // <-----
        },
        complete : function(){
            if(alumno != null){
                $("#listaralumnoseditar").val(nombre_alumno);
            }
        }
    });
}

function obtenergeneraciones() {
    $("#listargeneraciones").empty();
    var Data = {
       action: "obtenergeneraciones"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/promocionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#listargeneraciones").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#listargeneraciones").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        complete : function(){

        }
    });
}

function obtenercarreras() {
    $("#listarcarreras").empty();
    var Data = {
       action: "obtenercarreras"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/promocionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#listarcarreras").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#listarcarreras").append('<option value='+registro.idCarrera +'>'+registro.nombre+'</option>');
            });
        },
        complete : function(){

        }
    });
}

function obtenerconceptosdepago() {
    $('#mostrarconceptos').show();
    $("#listarconceptosalumnosogeneraciones").empty();
    var Data = {
       action: "obtenerconceptos"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/promocionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#listarconceptosalumnosogeneraciones").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#listarconceptosalumnosogeneraciones").append('<option value='+registro.id_concepto+'>'+registro.descripcion+'</option>');
            });
        },
        complete : function(){

        }
    });
}

function editarpromocion(idpromocion) { 
    $.ajax({
        url: '../assets/data/Controller/planpagos/promocionesControl.php',
        type: "POST",
        data: {action: 'obtenerPromocion', idpromocion: idpromocion},
        beforeSend : function(){
            $("#loader").css("display", "block")
        },
        success: function(data){
            try{
                data = JSON.parse(data);
                console.log(data)
                $('#idpromocioneditar').val(idpromocion);
                //$("#idpromocion").val(data.idpromocion);
                $("#editarnombrepromocion").val(data.nombrePromocion);
                $("#editarselecpromobeca").val(data.tipo);
                $("#editarporcentaje").val(data.porcentaje);
                $("#promoreinscripcioneseditar").val(data.porcentaje);
                $('#tipoconcepto').html(data.nombreconcepto)
                $('#nombregeneracionpromo').html('')
                
                if (data.id_carrera!=null) {
                    $("#editarmostrarcarreras").show();
                    $("#editarmostraralumnos").hide();
                    obtenercarreraseditar(data.id_carrera);
                }
                if (data.id_afiliado!=null) {
                    $("#editarmostrarcarreras").hide();
                    $("#editarmostraralumnos").show();
                    // obteneralumnos(data.id_afiliado);
                    
                    $("#listaralumnoseditar").val($("#listaralumnos option[value='"+data.id_prospecto+"']").text());
                    $("#listaralumnoseditar").change();
                }
                if (data.id_generacion!=null) {//extraer informacion si se selecciona la promocion ligada a una generacion
                    $("#editarmostrarcarreras").hide();
                    $("#editarmostraralumnos").hide();
                    $('#promoreinscripcioneseditar').val(data.porcentaje)
                    $('#nombregeneracionpromo').html(data.nombregeneracion)
                    $('#nombrecarrerapromo').html(data.nombrecarrera)
                }

                $("#check_num_mens_edit").prop('checked', false);
                $("#multiple_mensualidades_edit").parent().addClass('d-none');

                if(data.categoria == 'Mensualidad'){
                    $("#multiple_mensualidades_edit").attr('disabled', false);
                    $("#check_num_mens_edit").parent().removeClass('d-none');
                    if(data.Nopago.length > 0){
                        $("#check_num_mens_edit").prop('checked', true);
                        $("#multiple_mensualidades_edit").parent().removeClass('d-none');
                    }
                    var optc = "";
                    for(i = 1; i <= parseInt(data.numero_pagos); i++){
                        activo = data.Nopago.hasOwnProperty('length') && data.Nopago.indexOf(String(i)) > -1 ? 'selected' : '';
                        optc += '<option value='+i+' '+activo+' >Mensualidad '+i+'</option>';
                    }
                    $("#multiple_mensualidades_edit").html(optc);
                    $("#multiple_mensualidades_edit").selectpicker("refresh")

                    $("#check_num_mens_edit").on('change', function(){
                        if($(this).is(':checked')){
                            $("#multiple_mensualidades_edit").parent().removeClass('d-none');
                            $("#multiple_mensualidades_edit").attr('disabled', false);
                        }else{
                            $("#multiple_mensualidades_edit").parent().addClass('d-none');
                            $("#multiple_mensualidades_edit").attr('disabled', true);
                        }
                        $("#multiple_mensualidades_edit").selectpicker("refresh")
                    })
                }else{
                    $("#multiple_mensualidades_edit").attr('disabled', true);
                    $("#check_num_mens_edit").parent().addClass('d-none');
                }
                $("#promofechainicialeditar").val(data.fechainicio);
                $("#promofechafinaleditar").val(data.fechafin);

                
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

 function obtenercarreraseditar(id_carrera) {
    $("#listarcarreraseditar").empty();
    var Data = {
       action: "obtenercarreraseditar"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/promocionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#listarcarreraseditar").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#listarcarreraseditar").append('<option value='+registro.idCarrera +'>'+registro.nombre+'</option>');
            });
        },
        complete : function(){
            $("#listarcarreraseditar").val(id_carrera);
        }
    });
}

// function obteneralumnoseditar(id_afiliado) {
//     $("#listaralumnoseditar").empty();
//     var Data = {
//        action: "obteneralumnoseditar"
//     }
//     $.ajax({
//         url: '../assets/data/Controller/planpagos/promocionesControl.php',
//         type: 'POST',
//         data: Data,
//         dataType: 'JSON',
//         success : function(data){
//             $("#listaralumnoseditar").html('<option selected="true" disabled="disabled">Seleccione</option>');
//             $.each(data, function(key,registro){
//                 $("#listaralumnoseditar").append('<option value='+registro.id_afiliado+'>'+registro.nombre+'</option>');
//             });
//         },
//         complete : function(){
//             $("#listaralumnoseditar").val(id_afiliado);
//         }
//     });
// }

function obtenergeneracioneseditar(id_generacion) {
    $("#listargeneracioneseditar").empty();
    var Data = {
       action: "obtenergeneracioneseditar"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/promocionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#listargeneracioneseditar").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#listargeneracioneseditar").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        complete : function(){
            $("#listargeneracioneseditar").val(id_generacion);
        }
    });
}

$("#listargeneraciones").change(function (e) { 
    e.preventDefault();
    var id_generacion = $("#listargeneraciones").val();
    var Data = {
        action: "obtenerconceptosgeneracion",
        id_generacion: id_generacion
     }
     $.ajax({
         url: '../assets/data/Controller/planpagos/promocionesControl.php',
         type: 'POST',
         data: Data,
         dataType: 'JSON',
         success : function(data){
             console.log(data.length);
                if (data.length==0) {
                    Swal.fire(
                        {
                            title: "Para crear una promoción debe establecer un plan de pagos para la carrera de la generación seleccionada",
                            confirmButtonColor: '#ef5c6a'
                        }
                    )
                }
                $("#promoinscripcion").val(0);
                $("#promomensualidades").val(0);
                $("#promoreinscripciones").val(0);
                $("#promotitulacion").val(0);
                if (data.length==1) {
                    $('#divpromoinscripcion').show();
                    $('#divtodosconceptos').hide();
                    $('#idconceptopromoinscripcion').val(data[0].id_concepto);
                    $("#montofininscripcion").val(moneyFormat.format(data[0].precio))
                    $("#montofininscripcion").attr('init-val', data[0].precio)

                    $("#montofininscripcionusd").val(moneyFormat.format(data[0].precio_usd))
                    $("#montofininscripcionusd").attr('init-val', data[0].precio_usd)
                }
                for (let i = 0; i < data.length; i++) {
                    if(data[i].hasOwnProperty('precio_usd') && (data[i].precio_usd == false || data[i].precio_usd == 0 || data[i].precio_usd == null || data[i].precio_usd == '')){
                        data[i].precio_usd = 0;
                    }
                }
                if (data.length==4) {
                    
                    $('#divpromoinscripcion').show();
                    $('#divtodosconceptos').show();
                    $('#idconceptopromoinscripcion').val(data[0].id_concepto);
                    $("#montofininscripcion").val(moneyFormat.format(data[0].precio))
                    $("#montofininscripcion").attr('init-val', data[0].precio)

                    $("#montofininscripcionusd").val(moneyFormat.format(data[0].precio_usd))
                    $("#montofininscripcionusd").attr('init-val', data[0].precio_usd)

                    $("#check_num_mens").prop('checked', false);
                    $("#multiple_mensualidades").parent().addClass('d-none');
                    $("#multiple_mensualidades").attr('disabled', true);
                    var optc = "";
                    for(i = 1; i <= parseInt(data[1].numero_pagos); i++){
                        optc += '<option value='+i+'>Mensualidad '+i+'</option>';
                    }
                    $("#multiple_mensualidades").html(optc);
                    $("#multiple_mensualidades").selectpicker("refresh")

                    $("#check_num_mens").on('change', function(){
                        if($(this).is(':checked')){
                            $("#multiple_mensualidades").parent().removeClass('d-none');
                            $("#multiple_mensualidades").attr('disabled', false);
                        }else{
                            $("#multiple_mensualidades").parent().addClass('d-none');
                            $("#multiple_mensualidades").attr('disabled', true);
                        }
                        $("#multiple_mensualidades").selectpicker("refresh")
                    })
                    

                    $('#idconceptopromomensualidades').val(data[1].id_concepto);
                    $("#montofinmensualidad").val(moneyFormat.format(data[1].precio))
                    $("#montofinmensualidad").attr('init-val', data[1].precio)

                    $("#montofinmensualidadusd").val(moneyFormat.format(data[1].precio_usd))
                    $("#montofinmensualidadusd").attr('init-val', data[1].precio_usd)

                    $('#idconceptopromoreinscripciones').val(data[2].id_concepto);
                    $("#montofinreinscripcion").val(moneyFormat.format(data[2].precio))
                    $("#montofinreinscripcion").attr('init-val', data[2].precio)

                    $("#montofinreinscripcionusd").val(moneyFormat.format(data[2].precio_usd))
                    $("#montofinreinscripcionusd").attr('init-val', data[2].precio_usd)

                    $('#idconceptopromotitulacion').val(data[3].id_concepto);
                    $("#montofintitulacion").val(moneyFormat.format(data[3].precio))
                    $("#montofintitulacion").attr('init-val', data[3].precio)
                    
                    $("#montofintitulacionusd").val(moneyFormat.format(data[3].precio_usd))
                    $("#montofintitulacionusd").attr('init-val', data[3].precio_usd)
                }
         },
         complete : function(){

         }
     });
    
});

function desactivarpromocion(idpromocion) {

    Swal.fire({
        title: "¿Esta seguro de deshabilitar la promoción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef5c6a",
        cancelButtonColor: "#D8D8D8",
        confirmButtonText: "Aceptar"
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '../assets/data/Controller/planpagos/promocionesControl.php',
                type: "POST",
                data: {action: 'desactivarpromocion', idpromocion: idpromocion},
                beforeSend : function(){
                    $("#loader").css("display", "block")
                },
                success: function(data){
                    try{
                        if (data>0) {
                            Swal.fire(
                                {
                                    title: "La promoción fue deshabilitada",
                                    confirmButtonColor: '#ef5c6a'
                                }
                            ).then((result)=>{
                                tablapromociones.ajax.reload();
                            })
                        } else {
                            Swal.fire(
                                {
                                    title: "No se pudo habilitar la promoción",
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
            $('#customSwitch'+idpromocion+'').prop('checked', true)
        }
      })

}

function activarpromocion(idpromocion) {

    Swal.fire({
        title: "¿Está seguro de habilitar la promoción",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef5c6a",
        cancelButtonColor: "#D8D8D8",
        confirmButtonText: "aceptar"
      }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '../assets/data/Controller/planpagos/promocionesControl.php',
                    type: "POST",
                    data: {action: 'activarpromocion', idpromocion: idpromocion},
                    beforeSend : function(){
                        $("#loader").css("display", "block")
                    },
                    success: function(data){
                        try{
                            if (data>0) {
                                Swal.fire(
                                    {
                                        title: "La promoción se habilitó",
                                        confirmButtonColor: '#ef5c6a'
                                    }
                                ).then((result)=>{
                                    tablapromociones.ajax.reload();
                                })
                            } else {
                                Swal.fire(
                                    {
                                        title: "No se pudo habilitar la promoción",
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
                $('#customSwitch'+idpromocion+'').prop('checked', false)
            }
    });
    
}

$("#promoinscripcion").on('change', function(){
    var porcent = parseFloat($("#promoinscripcion").val());
    var initval = parseFloat($("#montofininscripcion").attr('init-val'));
    var initvalusd = parseFloat($("#montofininscripcionusd").attr('init-val'));
    if(porcent && initval && (initvalusd != NaN || initvalusd != undefined)){
        $("#montofininscripcion").val(moneyFormat.format(initval - (initval*porcent/100)))
        $("#montofininscripcionusd").val(moneyFormat.format(initvalusd - (initvalusd*porcent/100)))
    }else{
        $("#montofininscripcion").val(moneyFormat.format(initval))
        $("#montofininscripcionusd").val(moneyFormat.format(initvalusd))
    }
})
$("#montofininscripcion").on('change', function(){
    var monto_fin = parseFloat($("#montofininscripcion").val().replace(/\$|,/g, ""));
    var initval = parseFloat($("#montofininscripcion").attr('init-val'));
    if(monto_fin && initval){
        $("#promoinscripcion").val(100 - (monto_fin*100/initval))
    }
})

$("#promomensualidades").on('change', function(){
    var porcent = parseFloat($("#promomensualidades").val());
    var initval = parseFloat($("#montofinmensualidad").attr('init-val'));
    var initvalusd = parseFloat($("#montofinmensualidadusd").attr('init-val'));
    if(porcent && initval && (initvalusd != NaN || initvalusd != undefined)){
        $("#montofinmensualidad").val(moneyFormat.format(initval - (initval*porcent/100)))
        $("#montofinmensualidadusd").val(moneyFormat.format(initvalusd - (initvalusd*porcent/100)))
    }else{
        $("#montofinmensualidad").val(moneyFormat.format(initval))
        $("#montofinmensualidadusd").val(moneyFormat.format(initvalusd))
    }
})
$("#montofinmensualidad").on('change', function(){
    var monto_fin = parseFloat($("#montofinmensualidad").val().replace(/\$|,/g, ""));
    var initval = parseFloat($("#montofinmensualidad").attr('init-val'));
    if(monto_fin && initval){
        $("#promomensualidades").val(100 - (monto_fin*100/initval))
    }
})

$("#promoreinscripciones").on('change', function(){
    var porcent = parseFloat($("#promoreinscripciones").val());
    var initval = parseFloat($("#montofinreinscripcion").attr('init-val'));
    var initvalusd = parseFloat($("#montofinreinscripcionusd").attr('init-val'));
    if(porcent && initval && (initvalusd != NaN || initvalusd != undefined)){
        $("#montofinreinscripcion").val(moneyFormat.format(initval - (initval*porcent/100)))
        $("#montofinreinscripcionusd").val(moneyFormat.format(initvalusd - (initvalusd*porcent/100)))
    }else{
        $("#montofinreinscripcion").val(moneyFormat.format(initval))
        $("#montofinreinscripcionusd").val(moneyFormat.format(initvalusd))
    }
})
$("#montofinreinscripcion").on('change', function(){
    var monto_fin = parseFloat($("#montofinreinscripcion").val().replace(/\$|,/g, ""));
    var initval = parseFloat($("#montofinreinscripcion").attr('init-val'));
    if(monto_fin && initval){
        $("#promoreinscripciones").val(100 - (monto_fin*100/initval))
    }
})

$("#promotitulacion").on('click',function(){
    var porcent = parseFloat($("#promotitulacion").val());
    var initval = parseFloat($("#montofintitulacion").attr('init-val'));
    var initvalusd = parseFloat($("#montofintitulacionusd").attr('init-val'));
    if(porcent && initval && (initvalusd != NaN || initvalusd != undefined)){
        $("#montofintitulacion").val(moneyFormat.format(initval - (initval*porcent/100)))
        $("#montofintitulacionusd").val(moneyFormat.format(initvalusd - (initvalusd*porcent/100)))
    }else{
        $("#montofintitulacion").val(moneyFormat.format(initval))
        $("#montofintitulacionusd").val(moneyFormat.format(initvalusd))
    }
})
$("#montofintitulacion").on('change', function(){
    var monto_fin = parseFloat($("#montofintitulacion").val().replace(/\$|,/g, ""));
    var initval = parseFloat($("#montofintitulacion").attr('init-val'));
    if(monto_fin && initval){
        $("#promotitulacion").val(100 - (monto_fin*100/initval))
    }
})

$("#check_num_mens").on('change', function(){
    if($(this).is(':checked')){
        $("#multiple_mensualidades").removeClass('d-none')
    }else{
        $("#multiple_mensualidades").addClass('d-none')
    }
})

$(".onlyNumer").change(function(){
    $(this).val(parseFloat(($(this).val().trim() != '' ? $(this).val().trim() : 0)));
})
$("input[type='number']").change(function(){
    $(this).val(parseFloat(($(this).val().trim() != '' ? $(this).val().trim() : 0)));
})
