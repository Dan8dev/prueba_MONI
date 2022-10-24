$(document).ready(function () {
    carrerasPlan();
    getInstitucionescarrera();
    cargarPaisesDirectorio();
});


    $("#boton-crear-carrera").on("click",function (e) {
        
        obtenerinstituciones();
        $("#divSelectCarrera").hide();
        $("#divSelectArea").hide();
        
        $("#crearcarrera")[0].reset();
        $('#crear-carrera').modal("show");
        

        function obtenerinstituciones() {
            $("#select-institucion").empty();
            var Data = {
               action: "obtenerinstituciones"
            }
            $.ajax({
                url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
                type: 'POST',
                data: Data,
                dataType: 'JSON',
                success : function(data){
                    $("#select-institucion").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key,registro){
                        $("#select-institucion").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
                    });
                },
                complete : function(){
                    
                }
            });
        }

    });

    function carrerasPlan(){

        tCarrera = $("#table-carreras").DataTable({
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
                title:'Carreras_'+new Date().toLocaleDateString().replace(/\//g, '-')
            /*}, {
                extend: "pdf"
            }, {
                extend: "print"*/
            }],
            "ajax": {
                url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
                type: 'POST',
                data: {action: 'obtenerCarreras'},
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

    $("#ocultar").on('click', function(){
        $("#crear-carrera").modal("hide");
        $("#divSelectCarrera").hide();
        $("#divSelectArea").hide();
        $("#crearcarrera")[0].reset();
    })

    $("#ocultar2").on('click', function(){
        $("#modalModifycarrera").modal("hide");
        $("#formModCarrera")[0].reset();
    })

    $("#formModCarrera").on("submit", function(e){
        e.preventDefault();
        $("#devnombreGuno").prop('disabled', false);
        $("#devnumGuno").prop('disabled', false);
        $("#devselectTipocicloGuno").prop('disabled', false);
        fData = new FormData(this);
        fData.append('action', 'modificarCarrera');
        fData.append('modificado_por', usrInfo.idAcceso);
        $.ajax({
            url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
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
                    if(pr.estatus == 'ok'){
                        swal({
                            title: 'Modificado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formModCarrera")[0].reset();
                            tCarrera.ajax.reload(); 
                            $("#modalModifycarrera").modal("hide");
                        })
                    }
                    if(data == 1){
                        Swal.fire({
                            title: 'No se puede repetir el nombre clave',
                            confirmButtonColor: '#AA262C',
                        }).then((result)=>{
                        $(".devMessC").show();
                        })
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            },
            complete : function(){
                $("#devnombreGuno").prop('disabled', true);
                $("#devnumGuno").prop('disabled', true);
            }
        });
    })



$("#crearcarrera").on('submit', function(e){
    $("#nombreGuno").prop('disabled', false);
    $("#numGuno").prop('disabled', false);
    $("#selectTipocicloGuno").prop('disabled',false);
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'crearcarrera');
    fdata.append('creador_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#loader").css("display", "block")
        },
        success: function(data){
            if(data =='no_avance'){
                Swal.fire({
                    title: 'No se pudo crear el avance de la generación',
                    text: 'Hubo un problema al crear.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 4200
                });
            }
            if(data =='no_generacion'){
                Swal.fire({
                    title: 'No se pudo crear la generación',
                    text: 'Hubo un problema al crear.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 4200
                });
            }
            try{
                pr = JSON.parse(data)
                //console.log(data)
                //console.log(pr)
                if (pr.estatus == 'ok') {
                    swal({
                        title: 'Carrera creada con éxito',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#crearcarrera")[0].reset();
                        $("#crear-carrera").modal("hide");
                        $("#divSelectCarrera").hide();
                        $("#divSelectArea").hide();
                        tCarrera.ajax.reload();
                    })
                }
                if (data.estatus == 'error'){
                    swal({
                        title: "Error",
                        text: "No se pudo crear la carrera",
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
            $("#loader").css("display", "none")
            $("#nombreGuno").prop('disabled', true);
            $("#numGuno").prop('disabled', true);
        }
    });
})


function getInstitucionescarrera(){
    Data = {
        action: "obtenerinstituciones"
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success: function(data){
            $("#devinstitucion").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#devinstitucion").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
            });
        },
        complete : function(){

        }
    });
}

function buscarCarrera(id){
    $("#formModCarrera")[0].reset();
    Data = {
        action: 'buscarCarrera',
        idEditar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
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
                $("#devimagencarrera").show();
                $("#devfondocarrera").show();
                $("#modalModifycarrera").modal('show');
                pr = JSON.parse(data);
                $("#devinstitucion").val(pr.data.idInstitucion);
                if(pr.data.idInstitucion == '13'){
                    $("#devtipocarrera").empty();
                    $("#devtipocarrera").append('<option value="" disabled="disabled">Seleccione</option>');
                    $("#devtipocarrera").append('<option value="1">Certificación</option>');
                    $("#devtipocarrera").append('<option value="3">Diplomado</option>');
                    
                    $("#devAreaCarrera").empty();
                    $("#devAreaCarrera").append('<option value="" disabled="disabled">Seleccione</option>');
                    $("#devAreaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');

                    $("#devselectTipocicloGuno").prop('disabled',true);
                }
                if(pr.data.idInstitucion == '19'){
                    $("#devtipocarrera").empty();
                    $("#devtipocarrera").append('<option value="" disabled="disabled">Seleccione</option>');
                    $("#devtipocarrera").append('<option value="7">Especialidad</option>');
                    $("#devtipocarrera").append('<option value="5">Maestría</option>');

                    
                    $("#devAreaCarrera").empty();
                    $("#devAreaCarrera").append('<option value="" disabled="disabled">Seleccione</option>');
                    $("#devAreaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');

                    $("#devselectTipocicloGuno").prop('disabled',false);
                }
                if(pr.data.idInstitucion == '20'){
                    $("#devtipocarrera").empty();
                    $("#devtipocarrera").append('<option value="" disabled="disabled">Seleccione</option>');
                    $("#devtipocarrera").append('<option value="1">Certificación</option>');

                    $("#devtipocarrera").append('<option value="6">Doctorado</option>');
                    $("#devtipocarrera").append('<option value="4">Licenciatura</option>');
                    $("#devtipocarrera").append('<option value="5">Maestría</option>');
                    $("#devtipocarrera").append('<option value="2">TSU</option>');

                    
                    $("#devAreaCarrera").empty();
                    $("#devAreaCarrera").append('<option value="" disabled="disabled">Seleccione</option>');
                    $("#devAreaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');
                    $("#devAreaCarrera").append('<option value="Ciencias Sociales y Humanas">Ciencias Sociales y Humanas</option>');
                    $("#devAreaCarrera").append('<option value="Económico - Administrativo">Económico - Administrativo</option>');
                    
                    $("#devselectTipocicloGuno").prop('disabled',false);
                }
                $("#devnombrecarrera").val(pr.data.nombre);
                $("#devtipocarrera").val(pr.data.tipo);
                $("#devAreaCarrera").val(pr.data.area);
                $("#devnombreGuno").val(pr.data.nombreG);
                $("#devselectModalidadGuno").val(pr.data.modalidadCarrera);
                $("#devselectTipocicloGuno").val(pr.data.tipoCiclo);
                $("#devfechaInicioGuno").val(pr.data.fecha_inicio);
                //$("#devfechaFinGuno").val(pr.data.fechafinal);
                $("#devnumGuno").val(pr.data.secuencia_generacion);

                $("#id_carrera").val(pr.data.idCarrera);
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

function validarEliminar(id){
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
            eliminarCarrera(id);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function eliminarCarrera(id){
    Data = {
        action: "eliminarCarrera",
        idEliminar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
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
                        tCarrera.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}





$("#devinstitucion").on('change', function(){
    var devInsty = $("#devinstitucion").val();
    if(devInsty == '13'){
        $("#devtipocarrera").empty();
        $("#devtipocarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#devtipocarrera").append('<option value="1">Certificación</option>');
        $("#devtipocarrera").append('<option value="3">Diplomado</option>');
                    
        $("#devAreaCarrera").empty();
        $("#devAreaCarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#devAreaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');
    }
    if(devInsty == '19'){
        $("#devtipocarrera").empty();
        $("#devtipocarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#devtipocarrera").append('<option value="7">Especialidad</option>');
        $("#devtipocarrera").append('<option value="5">Maestría</option>');

        $("#devAreaCarrera").empty();
        $("#devAreaCarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#devAreaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');
    }
    if(devInsty == '20'){
        $("#devtipocarrera").empty();
        $("#devtipocarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#devtipocarrera").append('<option value="1">Certificación</option>');

        $("#devtipocarrera").append('<option value="6">Doctorado</option>');
        $("#devtipocarrera").append('<option value="4">Licenciatura</option>');
        $("#devtipocarrera").append('<option value="5">Maestría</option>');
        $("#devtipocarrera").append('<option value="2">TSU</option>');

        $("#devAreaCarrera").empty();
        $("#devAreaCarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#devAreaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');
        $("#devAreaCarrera").append('<option value="Ciencias Sociales y Humanas">Ciencias Sociales y Humanas</option>');
        $("#devAreaCarrera").append('<option value="Económico - Administrativo">Económico - Administrativo</option>');
    }
})

function tablaCarrerasAlumnos(id, nom){
    $("#labelAlumnosCarreras").html('Tabla Alumnos Carrera - '+ nom);
    tAlumnosCarreras = $("#table-alumnos-carreras").DataTable({
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
            extend: "pdf",
            className: "d-none"
        /*}, {
            extend: "print"*/
        }],
        "ajax": {
            url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
            type: 'POST',
            data: {action: 'obtenerAlumnosCarrera',
                    idCarrera: id},
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
    setTimeout(() => {
    tAlumnosCarreras.columns.adjust();
    }, 800);
}


$("#crearnombrecarrera").keyup(function(){
    var nombreC = $("#crearnombrecarrera").val();
    var numeroGen = $("#numGuno").val();
    $("#nombreGuno").val('Generación '+numeroGen+' '+nombreC);
})
//$("#nombreGuno").val();

$("#devnombrecarrera").keyup(function(){
    var devnom = $("#devnombrecarrera").val();
    var devnumG = $("#devnumGuno").val();
    $("#devnombreGuno").val('Generación '+devnumG+' '+devnom);
})

$("#select-institucion").on('change', function(){
    var valInst = $("#select-institucion").val();
    //console.log(valInst);
    if(valInst==13){
        $("#select-tipo").empty();
        $("#select-tipo").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#select-tipo").append('<option value="1">Certificación</option>');
        $("#select-tipo").append('<option value="3">Diplomado</option>');
        $("#divSelectCarrera").show();

        $("#areaCarrera").empty();
        $("#areaCarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#areaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');
        $("#divSelectArea").show();
    }
    if(valInst==19){
        $("#select-tipo").empty();
        $("#select-tipo").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#select-tipo").append('<option value="7">Especialidad</option>');
        $("#select-tipo").append('<option value="5">Maestría</option>');
        $("#divSelectCarrera").show();

        $("#areaCarrera").empty();
        $("#areaCarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#areaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');
        $("#divSelectArea").show();
    }
    if(valInst==20){
        $("#select-tipo").empty();
        $("#select-tipo").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#select-tipo").append('<option value="1">Certificación</option>');
        $("#select-tipo").append('<option value="6">Doctorado</option>');
        $("#select-tipo").append('<option value="4">Licenciatura</option>');
        $("#select-tipo").append('<option value="5">Maestría</option>');
        $("#select-tipo").append('<option value="2">TSU</option>');
        $("#divSelectCarrera").show();

        $("#areaCarrera").empty();
        $("#areaCarrera").append('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $("#areaCarrera").append('<option value="Ciencias Naturales y de la Salud">Ciencias Naturales y de la Salud</option>');
        $("#areaCarrera").append('<option value="Ciencias Sociales y Humanas">Ciencias Sociales y Humanas</option>');
        $("#areaCarrera").append('<option value="Económico - Administrativo">Económico - Administrativo</option>');
        $("#divSelectArea").show();
    }
})

/*
$("#select-institucion").on('change', function(){
    certificacion();
})*/

$("#select-tipo").on('change', function(){
    certificacion();
})

function certificacion(){
    if($("#select-institucion").val().trim() != '' && $("#select-tipo").val().trim() != ''){

        var idInsti = $("#select-institucion").val();
        var selectTipo = $("#select-tipo").val();

        if(idInsti == 13 && selectTipo == 1){
            $("#selectTipocicloGuno").val(3);
            $("#selectTipocicloGuno").prop('disabled', true);
        }else{
            if(idInsti == 13 && selectTipo == 3){
                $("#selectTipocicloGuno").val(1);
                $("#selectTipocicloGuno").prop('disabled', true);
            }else{
                $("#selectTipocicloGuno").val('');
                $("#selectTipocicloGuno").prop('disabled', false);
            }
        }
    }
}

$("#devtipocarrera").on('change',function(){
    certificacionMod();
})

function certificacionMod(){
    if($("#devinstitucion").val().trim() != '' && $("#devtipocarrera").val().trim() != ''){
        var idInstiMod = $("#devinstitucion").val();
        var selectTipoMod = $("#devtipocarrera").val();

        if(idInstiMod == 13 && selectTipoMod == 1){
            $("#devselectTipocicloGuno").val(3);
            $("#devselectTipocicloGuno").prop('disabled', true);
        }else{
            if(idInstiMod == 13 && selectTipoMod == 3){
                $("#devselectTipocicloGuno").val(1);
                $("#devselectTipocicloGuno").prop('disabled', true);
            }else{
                $("#devselectTipocicloGuno").val('');
                $("#devselectTipocicloGuno").prop('disabled', false);
            }
        }
    }
}

/*
$("#verTablaCarrera").on('click', function(){
})*/


function datosDirectorio(idAlumno, idCarrera, idGeneracion, pais, pais_nacimiento, pais_estudio, idRelacion){
//console.log(idAlumno);
$("#formDatosDirectorio")[0].reset();
cargarGeneracionesDirectorio(idCarrera);
cargarEstadosDirectorio(pais);
cargarEstadosPaisNacimiento(pais_nacimiento);
cargarEstadosPaisRadica(pais_estudio);
$.ajax({
    url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
    type: 'POST',
    data: {
        action: 'obtenerDatosAlumnoDirectorio',
        idAlum: idAlumno,
        idGen: idGeneracion
    },
    success : function(data){
        try{
            pr = JSON.parse(data)
            $("#idAlumno").val(pr.idalumno);

            $("#nombreDirectorio").val(pr.nombre);
            $("#apellidoPaternoDirectorio").val(pr.aPaterno);
            $("#apellidoMaternoDirectorio").val(pr.aMaterno);
            $("#idGeneracionAntigua").val(pr.idgeneracion);
            $("#estatusAlumnoDirectorio").val(pr.estatus);
            $("#curpAlumnoDirectorio").val(pr.curp);
            if(pr.edad != 0){
                $("#edadAlumnoDirectorio").val(pr.edad);
            }
            $("#emailAlumnoDirectorio").val(pr.email);
            $("#telefonoAlumnoDirectorio").val(pr.celular);
            $("#gradoUltimoAlumnoDirectorio").val(pr.grado_academico);
            $("#sexoAlumnoDirectorio").val(pr.Genero);
            if(pr.pais!=null){
                if(pr.estado==''){
                    if(pr.pais != "37"){
                        $("#paisAlumnoDirectorio").val(pr.pais);
                    }
                }else{
                    $("#paisAlumnoDirectorio").val(pr.pais);
                }
            }
            if(pr.pais_nacimiento!=0){
                if(pr.estado_nacimiento==0){
                    if(pr.pais_nacimiento != "37"){
                        $("#paisNacimientoDirectorio").val(pr.pais_nacimiento);
                    }
                }else{
                    $("#paisNacimientoDirectorio").val(pr.pais_nacimiento);
                }
            }
            if(pr.pais_estudio!=0){
                if(pr.estado_estudio==0){
                    if(pr.pais_estudio != "37"){
                        $("#paisEstudioDirectorio").val(pr.pais_estudio);
                    }
                }else{
                    $("#paisEstudioDirectorio").val(pr.pais_estudio);
                }
            }
            var estadoDir = pr.estado;
            var estadoNac = pr.estado_nacimiento;
            var estadoRad = pr.estado_estudio;
            setTimeout(() => {
                $("#generacionDirectorio").val(pr.idgeneracion);
                if(estadoDir == 0){
                    $("#estadoAlumnoDirectorio").prop('disabled', true);
                }else{
                    $("#estadoAlumnoDirectorio").prop('disabled', false);
                }
                if(estadoNac == 0){
                    $("#entidadNacimientoDirectorio").prop('disabled', true);
                }else{
                    $("#entidadNacimientoDirectorio").prop('disabled', false);
                }
                if(estadoRad == 0){
                    $("#entidadEstudioDirectorio").prop('disabled', true);
                }else{
                    $("#entidadEstudioDirectorio").prop('disabled', false);
                }
                $("#estadoAlumnoDirectorio").val(pr.estado);
                $("#entidadNacimientoDirectorio").val(pr.estado_nacimiento);
                $("#entidadEstudioDirectorio").val(pr.estado_estudio);
                $("#idRelacion").val(idRelacion);
            }, 1000);
            $("#notasDirectorio").val(pr.notas);
        }catch(e){
            console.log(e)
            console.log(data)
        }
    }
})
}


$("#cerrarEditarDirectorio").on('click', function(){
    $("#modalModificarDatosDirectorio").modal('hide');
    $("#formDatosDirectorio")[0].reset();
    //tAlumnosCarreras.columns.adjust();
    //$("#modalModificarDatosDirectorio").modal('show');
    $("#modalTablaCarrera").modal()
})


function cargarGeneracionesDirectorio(id){
    $("#generacionDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarGeneracionesDirectorio",
            idCarrera: id
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generacionDirectorio").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        }
    });
}

function checkTel(e){
    tecla = (document.all) ? e.keycode : e.which;

    if(tecla == 8){
        return true;
    }

    patron = /[0-9]/;
    tecla__final = String.fromCharCode(tecla);
    return patron.test(tecla__final);
}

function cargarEstadosDirectorio(pais){
    $("#estadoAlumnoDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: pais
        },
        dataType: 'JSON',
        success: function(data){
            $("#estadoAlumnoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#estadoAlumnoDirectorio").append('<option value='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
        }
    })
}

function cargarPaisesDirectorio(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarPaisesDirectorio"
        },
        dataType: 'JSON',
        success: function(data){
            $("#paisAlumnoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#paisNacimientoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#paisEstudioDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#paisAlumnoDirectorio").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
                $("#paisNacimientoDirectorio").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
                $("#paisEstudioDirectorio").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
            });
        }
    });
}

$("#formDatosDirectorio").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'actualizarDirectorioAlumno');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: fData,
        contentType:false,
        processData:false,
        success: function(data){
            if(data == 'ya_existe_generacion'){
                swal({
                    title: 'No se puede modificar la generación.',
                    icon: 'info',
                    text: 'Ya existe su registro en esa generación.',
                    button: false,
                    timer: 4200,
                });
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok') {
                    swal({
                        title: 'Actualizado correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formDatosDirectorio")[0].reset();
                        $("#modalModificarDatosDirectorio").modal("hide");
                        tAlumnosCarreras.ajax.reload();
                        tablacredencialesnuevos.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})

$("#paisAlumnoDirectorio").on('change', function(){
    $("#estadoAlumnoDirectorio").empty();
    idPais = $("#paisAlumnoDirectorio").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
                action: "cargarEstadosDirectorio",
                idPais: idPais
            },
        dataType: 'JSON',
        success : function(data){
            $("#estadoAlumnoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione el Estado</option>');
            $.each(data, function(key,registro){
                $("#estadoAlumnoDirectorio").prop('disabled', false);
                $("#estadoAlumnoDirectorio").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
            if(data == ''){
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#estadoAlumnoDirectorio").prop('disabled', true);
            }
        }
    });
})

function cargarEstadosPaisNacimiento(pais){
    $("#entidadNacimientoDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: pais
        },
        dataType: 'JSON',
        success: function(data){
            $("#entidadNacimientoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#entidadNacimientoDirectorio").append('<option value='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
        }
    })   
}

function cargarEstadosPaisRadica(pais){
    $("#entidadEstudioDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: pais
        },
        dataType: 'JSON',
        success: function(data){
            $("#entidadEstudioDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#entidadEstudioDirectorio").append('<option value='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
        }
    })
}

$("#paisNacimientoDirectorio").on('change', function(){
    $("#entidadNacimientoDirectorio").empty();
    idPais = $("#paisNacimientoDirectorio").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
                action: "cargarEstadosDirectorio",
                idPais: idPais
            },
        dataType: 'JSON',
        success : function(data){
            $("#entidadNacimientoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione el Estado</option>');
            $.each(data, function(key,registro){
                $("#entidadNacimientoDirectorio").prop('disabled', false);
                $("#entidadNacimientoDirectorio").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
            if(data == ''){
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#entidadNacimientoDirectorio").prop('disabled', true);
            }
        }
    });
})

$("#paisEstudioDirectorio").on('change', function(){
    $("#entidadEstudioDirectorio").empty();
    idPais = $("#paisEstudioDirectorio").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
                action: "cargarEstadosDirectorio",
                idPais: idPais
            },
        dataType: 'JSON',
        success : function(data){
            $("#entidadEstudioDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione el Estado</option>');
            $.each(data, function(key,registro){
                $("#entidadEstudioDirectorio").prop('disabled', false);
                $("#entidadEstudioDirectorio").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
            if(data == ''){
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#entidadEstudioDirectorio").prop('disabled', true);
            }
        }
    });
})


$("#curpAlumnoDirectorio").keyup(function(){
    var curp = $("#curpAlumnoDirectorio").val();
    var curpF = curp;
    if(curp.length == 18){
        curpFinal = curpF.slice(4,-8);
        var anio = curpFinal.substr(0,2);
        var mes = curpFinal.substr(2,2);
        var dia = curpFinal.substr(4,2);

/*      miFecha = new Date(anio,mes,dia);

        let hoy = new Date();

        var edad = hoy.getFullYear() - miFecha.getFullYear();
        var m = hoy.getMonth()+1 - miFecha.getMonth();
*/
        var anyo = parseInt(anio)+1900;
        if(anyo<1950) anyo += 100;
        var mounth = parseInt(mes)-1;
        var day = parseInt(dia);
        fechaFinal = new Date(anyo, mounth, day);
        
        let hoy = new Date();

        var edad = hoy.getFullYear() - fechaFinal.getFullYear();
        var m = hoy.getMonth()+1 - fechaFinal.getMonth();

        //console.log(edad)
        //console.log(hoy.getMonth()+1);
        //console.log(miFecha.getMonth());
        //console.log(m);
        //console.log(hoy.getDate());
        //console.log(miFecha.getDate());

        if(m < 0 || (m === 0 && hoy.getDate() < fechaFinal.getDate())){
            edad--;
        }

        $("#edadAlumnoDirectorio").val(edad);
    }
    //5to dato //aa//mm//dd
})


$("#ocultarTablaDirectorio").on('click', function(){
    $("#modalTablaCarrera").modal('hide');
})
