$(document).ready(function () {
    initGeneraciones();
});

function initGeneraciones() {
    
    if($('#tabgeneraciones').hasClass('tab_active')){
        
        
        SelectGeneration();
    }

    $("#btn-crear-generacion").on("click",function (e) {
        getCarreras();
        $("#formGeneracion")[0].reset();
        function getCarreras(){
            var Data = {
            action: "obtenerCarreras"
            }
            $.ajax({
                url: '../assets/data/Controller/controlescolar/generacionesControl.php',
                type: 'POST',
                data: Data,
                dataType: 'JSON',
                success : function(data){
                    $("#selectCarrer").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key,registro){
                        $("#selectCarrer").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
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

    });

    $('#tabgeneraciones').click(function (e) {
        SelectGeneration();
    });

}

function SelectGeneration(){
    tGeneraciones = $("#table-generaciones").DataTable({
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
            title:'Generaciones_'+new Date().toLocaleDateString().replace(/\//g, '-')
        /*}, {
            extend: "pdf"
        }, {
            extend: "print"*/
        }],
        "ajax": {
            url: '../assets/data/Controller/controlescolar/generacionesControl.php',
            type: 'POST',
            data: {action: 'obtenerGeneraciones'},
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
$("#formModGeneracion").on("submit", function(e){
    $("#modNumG").prop('disabled', false);
    $("#modtipociclo").prop('disabled', false);
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'modificarGeneracion');
    fData.append('actualizado_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
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
            if(data == 'ya_existe_generacion'){
                Swal.fire({
                    title: 'Ya existe la generación.',
                    text: 'Por favor, coloque un número correcto',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3500
                });
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
                        tGeneraciones.ajax.reload(); 
                        $("#modalModGen").modal("hide");
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }, complete : function(){
            $("#modNumG").prop('disabled', true);
        }
    });
})

$("#formGeneracion").on('submit', function(e){
    $("#numG").prop('disabled', false);
    $("#select-tipo-ciclo").prop('disabled',false);
    e.preventDefault();
    fdata = new FormData(this);
    fdata.append('action', 'crearGeneracion');
    fdata.append('creador_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
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
            if(data == 'ya_existe_generacion'){
                Swal.fire({
                    title: 'Ya existe la generación.',
                    text: 'Por favor, coloque un número correcto',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3500
                });
            }
            try{
                pr = JSON.parse(data)
                if (pr.estatus == 'ok') {
                    swal({
                        title: 'Generación creada con éxito',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formGeneracion")[0].reset();
                        $("#modalGeneracion").modal("hide");
                        tGeneraciones.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete : function(){
            $("#numG").prop('disabled', true);
	$("#select-tipo-ciclo").prop('disabled',true);
        }
    });
})


function validarEliminarGeneracion(id){
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
            eliminarGeneracion(id);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function eliminarGeneracion(id){
    Data = {
        action: "eliminarGeneracion",
        idEliminar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
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
                        tGeneraciones.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });

}

function buscarGeneracion(id){
    $("#formModGeneracion")[0].reset();
    Data = {
        action: 'buscarGeneracion',
        idEditar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        success: function(response){

            $data = $.trim(response)
            if($data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }else{
                pr = JSON.parse(response);
            }

            if(pr.estatus == 'ok'){

                try{
                    $("#modalModGen").modal('show');

                    idC = pr.data.idCarrera;
            
                    getCarrerasMod(pr.data.idCarrera);
                    buscarCarreraTipoConacon(pr.data.idCarrera);
    
                    setTimeout(() => {
                        $("#modselectCarrer").val(idC);
                    }, 300);
    
                    
                    $("#modNumG").val(pr.data.secuencia_generacion);
                    //$("#modnombreG").val(pr.data.nombre);
                    $("#modModalidad").val(pr.data.modalidadCarrera);
                    $("#modtipociclo").val(pr.data.tipoCiclo);
                    //$("#modcantidadCiclos").val(pr.data.cantidadCiclos);
                    $("#modfechainicio").val(pr.data.fecha_inicio);
                    //$("#modfechafin").val(pr.data.fechafinal);
                    $("#idG").val(pr.data.idGeneracion);
                }catch(e){
                    console.log(e)
                    console.log(data)
                }

            }
        }
    });
}


$("#ocultar3").on('click', function(){
    $("#formGeneracion")[0].reset();
    $("#modalGeneracion").modal("hide");
})

$("#ocultar4").on('click', function(){
    $("#formModGeneracion")[0].reset();
    $("#modalModGen").modal("hide");
})



$("#selectCarrer").on('change', function(){
    var carreraSeleccionada = $("#selectCarrer").val();
    buscarNumeroGeneracion(carreraSeleccionada);
})

$("#modselectCarrer").on('change', function(){
    var modCarreraSeleccionada = $("#modselectCarrer").val();
    buscarNumeroGeneracionMod(modCarreraSeleccionada);
})

function buscarNumeroGeneracion(idCarrera){
    var Data = {
        action: 'buscarNumeroGeneracion',
        idCarrer: idCarrera
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                //console.log(data.Max.secuencia_generacion)
                //console.log('////')
                pr = JSON.parse(data);
                //console.log(pr)
                //console.log(pr.secuencia_generacion)
                if(pr.secuencia_generacion==null){
                    var numSecuencia = 1;
                }else{
                    var numSecuencia = parseInt(pr.secuencia_generacion) + 1;
                }
                $("#numG").val(numSecuencia);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function buscarNumeroGeneracionMod(idCarrera){
    var Data = {
        action: 'buscarNumeroGeneracion',
        idCarrer: idCarrera
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                //console.log(data.Max.secuencia_generacion)
                //console.log('////')
                pr = JSON.parse(data);
                //console.log(pr)
                //console.log(pr.secuencia_generacion)
                if(pr.secuencia_generacion==null){
                    var numSecuencia = 1;
                }else{
                    var numSecuencia = parseInt(pr.secuencia_generacion);
                }
                $("#modNumG").val(numSecuencia);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function getCarrerasMod(idCarrera){
    var Data = {
    action: "obtenerCarrerasMod",
    idCarr: idCarrera
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#modselectCarrer").html('<option value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#modselectCarrer").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    });
}


function vistaAsignarPlanEstGen(idGeneracion, tipoCiclo){
    //console.log(tipoCiclo)
    //console.log(idGeneracion)
    Data = {
        action: 'validarAsignarPlanEstGen',
        idGen: idGeneracion,
        tipoC: tipoCiclo
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data=='no_existe_plan_e'){
                Swal.fire({
                    title: 'No existe un plan de estudios para esta carrera.',
                    text: 'Verifica que coincidan el tipo de ciclo (cuatrimestre, semestre o trimestre) entre el plan de estudios y la generación creada.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 7000
                });
            }
            try{
                pr = JSON.parse(data);
                if(data>0){
                    $("#formAsigPlanEstGen")[0].reset();
                    obtenerPlanesEstudio(idGeneracion, tipoCiclo);
                    //validarPlanEstAsignado(idGeneracion, tipoCiclo);
                    obDatosGeneracionPE(idGeneracion, tipoCiclo);
                    $("#modalAsigPlanEst").modal('show');
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#cancelAsigPlanE").on('click', function(){
    $("#formAsigPlanEstGen")[0].reset();
    $("#modalAsigPlanEst").modal('hide');
})

function obtenerPlanesEstudio(id, tipo_ciclo){
    //console.log(id);
    //console.log(tipo_ciclo);
    var Data = {
    action: "obtenerPlanesEstudio",
    id: id,
    tipoC: tipo_ciclo
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#asigPlanEst").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#asigPlanEst").append('<option value='+registro.id_plan_estudio+'>'+registro.nombre+'</option>');
                //$("#asigPlanEst").selectpicker('refresh');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'falta_materias_asignar'){
                Swal.fire({
                    title: 'Faltan materias por asignar',
                    text: 'Por favor, asigna las materias correspondientes al plan de estudios.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 4300
                });
                $("#asigPlanEst").html('<option selected="true" value="" disabled="disabled">Sin planes de estudio</option>');
            }
        }
    });
}

function obDatosGeneracionPE(id, tipoCiclo){
    Data = {
        action: 'obDatosAsigGeneracionPE',
        idGen: id,
        tipoC: tipoCiclo
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                pr = JSON.parse(data);
                //console.log(pr);
                //console.log('///');
                //console.log(data);
                //console.log(pr.fecha_inicio);
                //console.log(pr.numero_ciclos);

                $("#asigPlanEst").val(pr.id_plan_estudio);
                $("#fechafinAsigPE").val(pr.fechafinal);
                $("#idGenPlanE").val(pr.idGeneracion);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}


$("#asigPlanEst").on('change', function(){
    var idG = $("#idGenPlanE").val();
    var idPlanGAsig = $("#asigPlanEst").val();
    fechaAutomatica(idG, idPlanGAsig);
})

function fechaAutomatica(idGeneracion, idPlanEstudio){
    Data = {
        action: 'obDatosGeneracionPE',
        idG: idGeneracion,
        idPlanE: idPlanEstudio
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: Data,
        success : function (data){
            try{
                pr = JSON.parse(data)
                //console.log(pr);
                if(pr.tipo_ciclo == 1){
                    tCiclo = 4;
                }
                if(pr.tipo_ciclo == 2){
                    tCiclo = 6;
                }
                if(pr.tipo_ciclo == 3){
                    tCiclo = 3;
                }
                var fIni = pr.fecha_inicio;
                var fechaOb = new Date(fIni);
                //console.log(fechaOb);
                fechaOb.setMonth(fechaOb.getMonth()+(pr.numero_ciclos*tCiclo));
                //console.log('/////');
                //console.log(fechaOb);
                var fechaF = fechaOb.getFullYear() + '-' + (fechaOb.getMonth()+1) + '-' + (fechaOb.getDate());
                var dateChange = new Date(fechaF);
                let ye = new Intl.DateTimeFormat('en', { year: 'numeric'}).format(dateChange);
                let mo = new Intl.DateTimeFormat('en', { month: '2-digit'}).format(dateChange);
                let da = new Intl.DateTimeFormat('en', { day: '2-digit'}).format(dateChange);
                //var result = dateChange.getFullYear() + '-' + (dateChange.getMonth()) + '-' + (dateChange.getDate());
                var result = `${ye}-${mo}-${da}`;
                //console.log(fechaF);
                $("#fechafinAsigPE").val(result);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })

}

$("#formAsigPlanEstGen").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'asignarPlanEstudioGen');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: fData,
        contentType:false,
        processData:false,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus== 'ok'){
                    swal({
                        title: 'Plan de estudio asignado correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formAsigPlanEstGen")[0].reset();
                        $("#modalAsigPlanEst").modal("hide");
                        tGeneraciones.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})


/*$("#selectCarrer").on('change',function(){
    var carreraBuscar = $("#selectCarrer").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: {action: "buscarTipoCarrera",
                "idCarr": carreraBuscar},
        success: function(data){
            try{
                pr = JSON.parse(data);
                //console.log(pr.tipo);
                if(pr.tipo == '1' && pr.idInstitucion == '13'){
                    $("#select-tipo-ciclo").val(3);
                    $("#select-tipo-ciclo").prop('disabled', true);
                }else{
                    $("#select-tipo-ciclo").val('');
                    $("#select-tipo-ciclo").prop('disabled', false);
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})*/
$("#selectCarrer").on('change',function(){
    var carreraBuscar = $("#selectCarrer").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: {action: "buscarTipoCarrera",
                "idCarr": carreraBuscar},
        success: function(data){
            try{
                pr = JSON.parse(data);
                //console.log(pr.tipo);
                if(pr.tipo == '1' && pr.idInstitucion == '13'){
                    $("#select-tipo-ciclo").val(3);
                    $("#select-tipo-ciclo").prop('disabled', true);
                }else{
                    if(pr.tipo == '3' && pr.idInstitucion == '13'){
                        $("#select-tipo-ciclo").val(1);
                        $("#select-tipo-ciclo").prop('disabled', true);
                    }else{
                        $("#select-tipo-ciclo").val('');
                        $("#select-tipo-ciclo").prop('disabled', false);
                    }
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})



function buscarCarreraTipoConacon(id){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: {action: "buscarTipoCarrera",
                "idCarr": id},
        success: function(data){
            try{
                pr = JSON.parse(data);
                if(pr.idInstitucion == '13'){
                    $("#modtipociclo").prop('disabled', true);
                }else{
                    $("#modtipociclo").prop('disabled', false);
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}


function vistaAsignarFechasGen(id){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: {action: "datosPlanEstudioGeneración",
                "idGeneracion": id},
        success: function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr);
                if(pr.numero_ciclos!=null){
                    $("#modalAsignarFechas").modal('show');
                    $('#nameGeneracion').empty();
                    $("#divAsignarFechas").empty();

                    if(pr.tipo_ciclo == 1){
                        Ciclo = 'Cuatrimestre';
                    }
                    if(pr.tipo_ciclo == 2){
                        Ciclo = 'Semestre';
                    }
                    if(pr.tipo_ciclo == 3){
                        Ciclo = 'Trimestre';
                    }

                    $("#nameGeneracion").append($('<h4><strong><label>').
                                text(pr.nombre)).append('</label></strong></h4>');

                    for(var j=1 ; j <= pr.numero_ciclos ; j++){
                        obtenerFechasPorCiclo(id, j, pr.tipo_ciclo, pr.numero_ciclos, pr.fecha_inicio, pr.fechafinal);

                        $("#divAsignarFechas").append($('<h4><strong><label>').
                                        text(Ciclo+': '+ j)).append('</label></strong></h4>');

                        $("#divAsignarFechas").append($('<div>').
                                        attr('class', "form-group").
                                        append($('<label>').
                                        attr('class', 'control-label mr-2').
                                        text('Fecha Inicio:')).append('</label>').
                                        append($('<input>').
                                        attr('class', 'col-sm-4').
                                        attr('type', 'date').
                                        attr('name', 'fechaInicioGen'+j).
                                        attr('id', 'fechaInicioGen'+j)).append('</input>').
                                        append($('<label>').
                                        attr('class', 'control-label mr-2 ml-2').
                                        text('Fecha Fin:')).append('</label>').
                                        append($('<input>').
                                        attr('class', 'col-sm-4').
                                        attr('type', 'date').
                                        attr('name', 'fechaFinGen'+j).
                                        attr('id', 'fechaFinGen'+j)).append('</input>').
                                        append($('<button>').
                                        attr('class', 'btn btn-primary waves-effect waves-light ml-2').
                                        attr('type', 'button').
                                        attr('name', 'btnFechaAsignar'+j).
                                        attr('id', 'btnFechaAsignar'+j).
                                        attr('onclick', 'guardarFecha('+j+','+id+')').
                                        text('Enviar')).append('</button>').
                                        append($('<button>').
                                        attr('class', 'btn btn-secondary waves-effect waves-light ml-2').
                                        attr('name','btnFechaModificar'+j).
                                        attr('id', 'btnFechaModificar'+j).
                                        attr('type', 'button').
                                        attr('style', 'display: none;').
                                        attr('onclick', 'validarModificarFecha('+j+','+id+')').
                                        text('Modificar'))).append('</button></div>');
                                        
                    }
                }else{
                    Swal.fire({
                        title: 'Faltan asignar el plan de estudios.',
                        text: 'Por favor, asigna el plan de estudio correspondiente a la generación.',
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 4300
                    });
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#btnCerrarFechasGen").on('click', function(){
    $("#modalAsignarFechas").modal('hide');
})

function obtenerFechasPorCiclo(idGeneracion, ciclo, tipoCiclo, numeroTotal, fechaInicio, fechaFinal){
    //console.log(ciclo)
    //console.log(numeroTotal)
    //console.log(tipoCiclo)
    if(tipoCiclo == '1'){
        var meses = 4;
    }
    if(tipoCiclo == '2'){
        var meses = 6;
    }
    if(tipoCiclo == '3'){
        var meses = 3;
    }
$.ajax({
    url: '../assets/data/Controller/controlescolar/generacionesControl.php',
    type: 'POST',
    data: {action: 'obtenerFechasPorCiclo',
            'idGeneracion': idGeneracion,
            'numeroCiclo': ciclo},
    success : function(data){
        try{
            pr = JSON.parse(data)
            if(pr != ''){
                //console.log('hola')
                $("#fechaInicioGen"+ciclo).val(pr.fecha_inicio);
                $("#fechaFinGen"+ciclo).val(pr.fecha_fin);
                $("#btnFechaAsignar"+ciclo).hide();
                $("#btnFechaModificar"+ciclo).show();
            }else{
                if(ciclo==1){
                    $("#fechaInicioGen"+ciclo).val(fechaInicio);

                    var fechaObtenidaUno = new Date(fechaInicio);
                    fechaObtenidaUno.setMonth(fechaObtenidaUno.getMonth()+(ciclo*(meses)));
                    var fechaFinalUno = fechaObtenidaUno.getFullYear() + '-' + (fechaObtenidaUno.getMonth()+1) + '-' + (fechaObtenidaUno.getDate());
                    var cambioFormatoUno = new Date(fechaFinalUno);
                    let anioUno = new Intl.DateTimeFormat('en', { year: 'numeric'}).format(cambioFormatoUno);
                    let mesUno = new Intl.DateTimeFormat('en', { month: '2-digit'}).format(cambioFormatoUno);
                    let diaFUno = new Intl.DateTimeFormat('en', { day: '2-digit'}).format(cambioFormatoUno);
                    var formatoFinalUno = `${anioUno}-${mesUno}-${diaFUno}`;
                        
                    $("#fechaFinGen"+ciclo).val(formatoFinalUno);

                }else{
                    if(ciclo==numeroTotal){
                        var fechaObtenidaUltimo = new Date(fechaInicio);
                        fechaObtenidaUltimo.setMonth(fechaObtenidaUltimo.getMonth()+((ciclo-1)*meses));
                        var fechaFinalUltimo = fechaObtenidaUltimo.getFullYear() + '-' + (fechaObtenidaUltimo.getMonth()+1) + '-' + (fechaObtenidaUltimo.getDate()+1);
                        var cambioFormatoUltimo = new Date(fechaFinalUltimo);
                        let anioUltimo = new Intl.DateTimeFormat('en', { year: 'numeric'}).format(cambioFormatoUltimo);
                        let mesUltimo = new Intl.DateTimeFormat('en', { month: '2-digit'}).format(cambioFormatoUltimo);
                        let diaUltimo = new Intl.DateTimeFormat('en', { day: '2-digit'}).format(cambioFormatoUltimo);
                        var formatoFinalUltimo = `${anioUltimo}-${mesUltimo}-${diaUltimo}`;
                        
                        $("#fechaInicioGen"+ciclo).val(formatoFinalUltimo);

                        $("#fechaFinGen"+ciclo).val(fechaFinal);
                    }else{
                        
                        var fechaObtenida = new Date(fechaInicio);
                        fechaObtenida.setMonth(fechaObtenida.getMonth()+((ciclo-1)*meses));
                        var fechaFin = fechaObtenida.getFullYear() + '-' + (fechaObtenida.getMonth()+1) + '-' + (fechaObtenida.getDate()+1);
                        var cambioFormato = new Date(fechaFin);
                        let anio = new Intl.DateTimeFormat('en', { year: 'numeric'}).format(cambioFormato);
                        let mes = new Intl.DateTimeFormat('en', { month: '2-digit'}).format(cambioFormato);
                        let dia = new Intl.DateTimeFormat('en', { day: '2-digit'}).format(cambioFormato);
                        var formatoFinal = `${anio}-${mes}-${dia}`;

                        $("#fechaInicioGen"+ciclo).val(formatoFinal);

                        var fechaObtenidaFinal = new Date(fechaInicio);
                        fechaObtenidaFinal.setMonth(fechaObtenidaFinal.getMonth()+(ciclo*meses));
                        var fechaFinalFin = fechaObtenidaFinal.getFullYear() + '-' + (fechaObtenidaFinal.getMonth()+1) + '-' + (fechaObtenidaFinal.getDate());
                        var cambioFormatoFinal = new Date(fechaFinalFin);
                        let anioFin = new Intl.DateTimeFormat('en', { year: 'numeric'}).format(cambioFormatoFinal);
                        let mesFin = new Intl.DateTimeFormat('en', { month: '2-digit'}).format(cambioFormatoFinal);
                        let diaFin = new Intl.DateTimeFormat('en', { day: '2-digit'}).format(cambioFormatoFinal);
                        var formatoFinalFin = `${anioFin}-${mesFin}-${diaFin}`;
                        
                        $("#fechaFinGen"+ciclo).val(formatoFinalFin);
                    }
                }
                
                //$("#fechaInicioGen"+ciclo).val(pr.fecha_inicio);
                //$("#fechaFinGen"+ciclo).val(pr.fecha_fin);
            }
            //$("#fechaInicioGen"+ciclo).val();
            //$("#fechaFinGen"+ciclo).val();
        }catch(e){
            console.log(e)
            console.log(data)
        }
    }
});
}

function guardarFecha(ciclo, id){
var fechaI = $("#fechaInicioGen"+ciclo).val();
var fechaF = $("#fechaFinGen"+ciclo).val();
fData = new FormData();
fData.append('action', 'guardarFechaGeneracion');
fData.append('idGeneracion', id);
fData.append('numeroDeCiclo', ciclo);
fData.append('fechaInicio', fechaI);
fData.append('fechaFin', fechaF);
$.ajax({
    url: '../assets/data/Controller/controlescolar/generacionesControl.php',
    type: 'POST',
    data: fData,
    contentType: false,
    processData: false,
    success: function(data){
        if(data == 'Rango_Fecha'){
            Swal.fire({
                title: 'Elegir otra fecha.',
                text: 'La fecha de inicio no puede iniciar antes de terminar el periodo anterior.',
                type: 'info',
                customClass: 'myCustomClass-info',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 5600
            });
        }
        if(data == 'Rango_Fecha_Uno'){
            Swal.fire({
                title: 'Elegir otra fecha.',
                text: 'La fecha fin no puede terminar después del inicio del siguiente periodo.',
                type: 'info',
                customClass: 'myCustomClass-info',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 5600
            });
        }
        try{
            pr = JSON.parse(data)
            if(pr.estatus == 'ok'){
                swal({
                    title: 'Fecha asignada correctamente',
                    icon: 'success',
                    text: 'Espere un momento...',
                    button: false,
                    timer: 2500,
                }).then((result)=>{
                    $("#btnFechaAsignar"+ciclo).hide();
                    $("#btnFechaModificar"+ciclo).show();
                    vistaAsignarFechasGen(id);
                })
            }
        }catch(e){
            console.log(e)
            console.log(data)
        }
    }
});
/*console.log(id);
fechas2 = $(datos).closest('div').find('input');
fechas = $(datos).parent().find('input').val();
console.log(fechas);
console.log(fechas2)*/

} 

function validarModificarFecha(ciclo, idGeneracion){
    Swal.fire({
        text: '¿Estas seguro de modificar la fecha?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            modificarFecha(ciclo, idGeneracion);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function modificarFecha(ciclo, idGeneracion){
    var fechaI = $("#fechaInicioGen"+ciclo).val();
    var fechaF = $("#fechaFinGen"+ciclo).val();
    fData = new FormData();
    fData.append('action', 'modificarFechasGeneracion');
    fData.append('idGeneracion', idGeneracion);
    fData.append('numeroDeCiclo', ciclo);
    fData.append('fechaInicio', fechaI);
    fData.append('fechaFin', fechaF);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success: function(data){
            if(data == 'Rango_Fecha'){
                Swal.fire({
                    title: 'Elegir otra fecha.',
                    text: 'La fecha de inicio no puede iniciar antes de terminar el periodo anterior.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 5600
                });
            }
            if(data == 'Rango_Fecha_Uno'){
                Swal.fire({
                    title: 'Elegir otra fecha.',
                    text: 'La fecha fin no puede terminar después del inicio del siguiente periodo.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 5600
                });
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Fecha modificada correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#btnFechaAsignar"+ciclo).hide();
                        $("#btnFechaModificar"+ciclo).show();
                        vistaAsignarFechasGen(idGeneracion);
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}



function vistaBloqueoGen(idGeneracion){
$("#idGeneracionBloqueo").val(idGeneracion);
tBloqueoDocuments = $("#table-bloqueo-documentos").DataTable({
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
            title:'Documentos_'+new Date().toLocaleDateString().replace(/\//g, '-')
        }, {
            extend: "pdf",
            title:'Documentos_'+new Date().toLocaleDateString().replace(/\//g, '-')
        /*}, {
            extend: "print"*/
        }],
        "ajax": {
            url: '../assets/data/Controller/controlescolar/generacionesControl.php',
            type: 'POST',
            data: {action: 'obtenerDocumentosGeneracion',
                    idGen: idGeneracion},
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
        'order':false
    });
    setTimeout(() => {
        tBloqueoDocuments.columns.adjust();
    }, 800);
}

$("#btnAsignarDocumentos").on('click', function(){
    var idGeneracion = $("#idGeneracionBloqueo").val();
    $("#modalAsigDocumentosGen").modal('show');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: {
            action: 'obtenerListaDocumentosGeneracion',
            idGen: idGeneracion
        },
        success: function(data){
            try{
                pr = JSON.parse(data)

            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})

function vistaDatosBloqueo(idBloqueo, idGeneracion){
    $("#formAsigBloqueoDocumento")[0].reset();
    //$("#idGeneracionBloqueoDoc").val(idGeneracion);
    $("#idBloqueo").val(idBloqueo);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: {
            action: 'recuperarAsignarBloqueo',
            idBloq: idBloqueo
        },
        success: function(data){
            try{
                pr = JSON.parse(data);
                //console.log(pr)
                if(pr.bloqueo_fisico!=1){
                    $("#fechaBloqueoFisico").removeAttr('required');
                    $("#horaBloqueoFisico").removeAttr('required');
                    $("#divFechaBloqueoFisico").hide();
                    $("#divHoraBloqueoFisico").hide();
                }else{
                    $("#fechaBloqueoFisico").val(pr.fecha_bloqueo_fisico);
                    $("#horaBloqueoFisico").val(pr.hora_fisico);
                    $("#fechaBloqueoFisico").attr('required', '');
                    $("#horaBloqueoFisico").attr('required', '');
                    $("#divFechaBloqueoFisico").show();
                    $("#divHoraBloqueoFisico").show();
                }
                if(pr.bloqueo_digital!=1){
                    $("#fechaBloqueoDigital").removeAttr('required');
                    $("#horaBloqueoDigital").removeAttr('required');
                    $("#divFechaBloqueoDigital").hide();
                    $("#divHoraBloqueoDigital").hide();
                }else{
                    $("#fechaBloqueoDigital").val(pr.fecha_bloqueo_digital);
                    $("#horaBloqueoDigital").val(pr.hora_digital);
                    $("#fechaBloqueoDigital").attr('required', '');
                    $("#horaBloqueoDigital").attr('required', '');
                    $("#divFechaBloqueoDigital").show();
                    $("#divHoraBloqueoDigital").show();
                }
                $("#selectBloqueoFisico").val(pr.bloqueo_fisico);
                $("#selectBloqueoDigital").val(pr.bloqueo_digital);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#formAsigBloqueoDocumento").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'asignarBloqueoDocumento');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/generacionesControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success: function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus== 'ok'){
                    swal({
                        title: 'Guardado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formAsigBloqueoDocumento")[0].reset();
                        $("#modalDatosBloqueo").modal("hide");
                        tBloqueoDocuments.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})


$("#selectBloqueoDigital").on('change', function(){
    var seleccionBloqueoD = $("#selectBloqueoDigital").val();
    if(seleccionBloqueoD == 1){
        $("#fechaBloqueoDigital").attr('required', '');
        $("#horaBloqueoDigital").attr('required', '');
        $("#divHoraBloqueoDigital").show();
        $("#divFechaBloqueoDigital").show();
    }else{
        $("#fechaBloqueoDigital").removeAttr('required');
        $("#fechaBloqueoDigital").val('');
        $("#horaBloqueoDigital").removeAttr('required');
        $("#horaBloqueoDigital").val('');
        $("#divHoraBloqueoDigital").hide();
        $("#divFechaBloqueoDigital").hide();
    }
})


$("#selectBloqueoFisico").on('change', function(){
    var seleccionBloqueoF = $("#selectBloqueoFisico").val();
    if(seleccionBloqueoF == 1){
        $("#fechaBloqueoFisico").attr('required', '');
        $("#horaBloqueoFisico").attr('required', '');
        $("#divHoraBloqueoFisico").show();
        $("#divFechaBloqueoFisico").show();
    }else{
        $("#fechaBloqueoFisico").removeAttr('required');
        $("#fechaBloqueoFisico").val('');
        $("#horaBloqueoFisico").removeAttr('required');
        $("#horaBloqueoFisico").val('');
        $("#divHoraBloqueoFisico").hide();
        $("#divFechaBloqueoFisico").hide();
    }
})

$("#cerrarAsignarBloqueo").on('click', function(){
    $("#modalDatosBloqueo").modal('hide');
    $("#formAsigBloqueoDocumento")[0].reset();
})

$("#ocultarTablaDocumentosGeneracion").on('click', function(){
    $("#modalAsignarBloqueo").modal('hide');
})
