$(document).ready(function () {
    initMaterias();
});


function initMaterias(){
    //selectBuscarMaterias();
    
    $("#btn-crear-materias").on("click",function(e){
        
        $("#divElegirCarreraMateria").hide();
        $("#divNumeroCreditos").hide();
        $("#formMateria")[0].reset();
        function obtenerCarreras(){
            var Data = {
            action: "obtenerCarreras"
            }
            $.ajax({
                url: '../assets/data/Controller/controlescolar/materiasControl.php',
                type: 'POST',
                data: Data,
                dataType: 'JSON',
                success : function(data){
                    $("#selectCarreraAsig").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key,registro){
                        $("#selectCarreraAsig").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                    });
                },
                error: function(xhr){
                    if(xhr.responseText == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se actualizó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                }
            });
        }

        function obtenerCarrerasOficial(){
            var Data = {
            action: "obtenerCarrerasOficial"
            }
            $.ajax({
                url: '../assets/data/Controller/controlescolar/materiasControl.php',
                type: 'POST',
                data: Data,
                dataType: 'JSON',
                success : function(data){
                    $("#selectCarreraAsig").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key,registro){
                        $("#selectCarreraAsig").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                    });
                },
                error: function(xhr){
                    if(xhr.responseText == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se actualizó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                }
            });
        }

        $("#selectOficial").on('click', function (){
            var selOficial = $("#selectOficial").val();
            if(selOficial==1){
                obtenerCarrerasOficial();
                $("#divElegirCarreraMateria").show();
            }
            if(selOficial==2){
                obtenerCarreras();
                $("#divElegirCarreraMateria").show();
            }
        })

    });
/*
    $('#tabmaterias').click(function(){
        tMaterias = $("#table-materias").DataTable({
            responsive: true,
            Processing: true,
            ServerSide: true,
            "dom" :'Bfrtip',
            buttons:[{
                /*extend:"copy",
                className: "btn-success"
            },{
                extend: "csv"
            }, {
                extend: "excel",
                className: "btn-primary"
            /*}, {
                extend: "pdf"
            }, {
                extend: "print"
            }],
            "ajax": {
                url: '../assets/data/Controller/controlescolar/materiasControl.php',
                type: 'POST',
                data: {action: 'obtenerMaterias'},
                dataType: "JSON",
                error: function(e){
                    console.log(e.responseText);	
                    if(e.responseText == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se actualizó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
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
                [0,'des']
            ],
        });
        
    });*/

}

$("#ocultarMaterias").on('click',function(){
    $("#modalmaterias").modal('hide');
})

$("#selectOficial").on('change', function(){
    var slctOf = $("#selectOficial").val();
    if(slctOf == '1'){
        $("#divNumeroCreditos").hide();
        $("#numeroCreditosNoOficial").removeAttr('required');

        //$("#claveMateria").attr('required','');
        $("#selectTipoMateria").attr('required','');
        $("#numeroCreditos").attr('required','');

        //$("#divClaveMateria").show();
        $("#divTipoMateria").show();
        $("#divNumeroCre").show();
    }
    if(slctOf == '2'){
        $("#divNumeroCreditos").show();
        $("#numeroCreditosNoOficial").attr('required', '');
        
        //$("#claveMateria").removeAttr('required');
        $("#selectTipoMateria").removeAttr('required');
        $("#numeroCreditos").removeAttr('required');

        //$("#divClaveMateria").hide();
        $("#divTipoMateria").hide();
        $("#divNumeroCre").hide();
    }
})

$("#formMateria").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'crearMateria');
    fData.append('creado_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/materiasControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success: function(data){
            //console.log(data);

            data = $.trim(data);
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
            if(data == 'no_valido_pdf_carrera'){
                Swal.fire({
                    title: 'El archivo no es un PDF',
                    text: 'Por favor, adjunta un archivo correcto',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3500
                });
            }
            if(data == 'clave_ocupada_materia'){
                Swal.fire({
                    title: 'Clave ocupada',
                    text: 'Ya existe la clave de materia en la base, favor de cambiar.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3500
                });
            }else{
                try{
                    pr = JSON.parse(data)
                    if (pr.estatus == 'ok'){
                        swal({
                            title: 'Materia creada con éxito',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formMateria")[0].reset();
                            $("#modalmaterias").modal("hide");
                            tMaterias.ajax.reload();
                        })
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            }
            
        }
    });
})

function buscarMateria(id){
    $("#formModMateria")[0].reset();
    Data = {
        action: 'buscarMateria',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/materiasControl.php',
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
                    if(pr.data.oficial == 1){
                        $("#divModNumeroCreditos").hide();
                        $("#modNumeroCreditosNoOficial").removeAttr('required');

                        obtenerCarrerasModOficial();
                        $("#modSelectOficial").empty();
                        $("#modSelectOficial").append('<option value="1">Sí</option>');
                        
                        $("#modClaveMateria").attr('required','');
                        $("#modSelectTipoMateria").attr('required','');
                        $("#modNumeroCreditos").attr('required','');

                        //$("#divRvoeMateria").show();
                        $("#divModClaveMateria").show();
                        $("#divModTipoMateria").show();
                        $("#divModNumeroCre").show();

                        //$("#rvoeMateria").val(pr.data.clave_sep);
                        $("#modSelectOficial").val(pr.data.oficial);
                        //aqui
                        setTimeout(() => {
                            $("#modSelectCarreraAsig").val(pr.data.id_carrera);    
                        }, 300);
                        $("#modNombreMateria").val(pr.data.nombre);
                        $("#modClaveMateria").val(pr.data.clave_asignatura);
                        $("#modSelectTipoMateria").val(pr.data.tipo);
                        $("#modNumeroCreditos").val(pr.data.numero_creditos);
                        $("#claveSepAnt").val(pr.data.clave_sep);
                        if(pr.data.contenido_pdf == null){
                            $("#pdfAnterior").val(2);
                        }else{
                            $("#pdfAnterior").val(1);
                        }
                        //$("#modContenidoPDF").val(pr.data.contenido_pdf);

                    }else{
                        $("#divModNumeroCreditos").show();
                        $("#modNumeroCreditosNoOficial").attr('required', '');

                        obtenerCarrerasModM();
                        $("#modSelectOficial").empty();
                        $("#modSelectOficial").append('<option value="2">No</option>');

                        //$("#modClaveMateria").removeAttr('required');
                        $("#modSelectTipoMateria").removeAttr('required');
                        $("#modNumeroCreditos").removeAttr('required');

                        //$("#divRvoeMateria").hide();
                        //$("#divModClaveMateria").hide();
                        $("#divModTipoMateria").hide();
                        $("#divModNumeroCre").hide();
                        
                        $("#modSelectOficial").val(pr.data.oficial);
                        //aqui
                        setTimeout(() => {
                            $("#modSelectCarreraAsig").val(pr.data.id_carrera);
                        }, 300);
                        $("#modNombreMateria").val(pr.data.nombre);
                        $("#modClaveMateria").val(pr.data.clave_asignatura);
                        $("#modNumeroCreditosNoOficial").val(pr.data.numero_creditos);
                        //$("#modContenidoPDF").val(pr.data.contenido_pdf);

                        if(pr.data.contenido_pdf == null){
                            $("#pdfAnterior").val(2);
                        }else{
                            $("#pdfAnterior").val(1);
                        }
                    }
                    $("#id_materia").val(pr.data.id_materia);

                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            }
    });

}

function obtenerCarrerasModM(){
    var Data = {
    action: "obtenerCarreras"
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/materiasControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#modSelectCarreraAsig").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#modSelectCarreraAsig").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
        }
    });
}

function obtenerCarrerasModOficial(){
    var Data = {
    action: "obtenerCarrerasOficial"
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/materiasControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#modSelectCarreraAsig").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#modSelectCarreraAsig").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
        }
    });
}


$("#formModMateria").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'modificarMateria');
    fData.append('modificado_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/materiasControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Modificado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formModMateria")[0].reset();
                        tMaterias.ajax.reload(); 
                        $("#modalModMateria").modal("hide");
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
            
            try{
                pr = JSON.parse(data)
                if(pr == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }
                if(pr == 'no_valido_pdf_carrera'){
                    Swal.fire({
                        title: 'El archivo no es un PDF',
                        text: 'Por favor, adjunta un archivo correcto',
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 3500
                    });
                }
                if(pr == 'clave_ocupada_materia'){
                    Swal.fire({
                        title: 'Clave ocupada',
                        text: 'Ya existe la clave de materia en la base, favor de cambiar.',
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 3500
                    });
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }

    });
})

function validarEliminarMateria(id){
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
            eliminarMateria(id);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function eliminarMateria(id){
    Data = {
        action: 'eliminarMateria',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/materiasControl.php',
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
                if(data != 'no_session'){
                    swal({
                        title: 'Eliminado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    })
                    .then((result)=>{
                        tMaterias.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#btnModMateria").on('click',function(){
    $("#modalModMateria").modal('hide');
})


function verPDFMateria(idCarrera, pdf){
    window.open('archivos/materias/'+idCarrera+'/'+pdf, '_blank');
}

/*$("#table-materias .paginate_button").on('click', function(){
    //.dataTables_paginate .paginate_button
    //$("#table-materias").DataTable().columns.adjust();
    $("#table-materias").DataTable().columns.adjust();
})*/


/*$("#table-materias .paginate_button").trigger('click', function(){
    $("#table-materias").DataTable().columns.adjust();
})
*/


function check(e){
    tecla = (document.all) ? e.keycode : e.which;

    if(tecla == 8){
        return true;
    }

    patron = /[A-Za-z-_0-9]/;
    tecla__final = String.fromCharCode(tecla);
    return patron.test(tecla__final);
}

function selectBuscarMaterias(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/materiasControl.php',
        type: 'POST',
        data: {action: 'obtenerListadoDeCarreras'},
        dataType: 'JSON',
        success : function(data){
            $("#selectBuscarMaterias").html('<option selected="true" value="" disabled="disabled"><strong>Da clic aqui para ver las materias por carrera</strong></option>');
            $.each(data, function(key,registro){
                $("#selectBuscarMaterias").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
        }
    });
}

$("#selectBuscarMaterias").on('change', function(){
    var idMateriaSeleccionada = $("#selectBuscarMaterias").val();
    //console.log(idMateriaSeleccionada);
    suf_carr = $("#selectBuscarMaterias option:selected").text();
    tMaterias = $("#table-materias").DataTable({
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
            title: "Materias_"+suf_carr,
            className: "btn-primary"
        /*}, {
            extend: "pdf"
        }, {
            extend: "print"*/
        }],
        "ajax": {
            url: '../assets/data/Controller/controlescolar/materiasControl.php',
            type: 'POST',
            data: {action: 'obtenerMateriasPorCarrera',
                    idCarrera: idMateriaSeleccionada},
            dataType: "JSON",
            error: function(e){
                //console.log(e.responseText);	
                if($.trim(e.responseText) === 'sin_materias_carrera'){
                    Swal.fire({
                        title: 'Sin materias',
                        text: 'Es necesario crear materias para esta carrera.',
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 3500
                    });
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
            [5,'desc']
        ],
    });
})

