$("#horas-tab").on('click', cargar_carreras_docentes);
$(".time_inp").on('change', calc_dif_time);

function calc_dif_time(){
    if($("#inp_hora_ent").val() != '' && $("#inp_hora_sal").val() != ''){
        var f_in = new Date("2022-01-01T"+$("#inp_hora_ent").val());
        var f_out = new Date("2022-01-01T"+$("#inp_hora_sal").val());
        if(f_out < f_in){
            $("#inp_hora_sal").val($("#inp_hora_ent").val());
        }else{
            var diffMs = (f_out - f_in); // milliseconds between now & Christmas
            var diffDays = Math.floor(diffMs / 86400000); // days
            var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
            var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000);
            $("#inp_hors_total").val(`${(diffHrs >= 10 ? diffHrs : '0'+diffHrs)}:${(diffMins >= 10 ? diffMins : '0'+diffMins)} Hrs.`);
        }
    }
}

$("#select_clase_id").on('change', function(){
    $("#view_date_class").val($("#select_clase_id option:selected").text().trim().substring(1,11));
    $("#inp_hora_ent").val($("#select_clase_id option:selected").text().trim().substr(12,5));
    $("#inp_hora_ent").attr('min', $("#select_clase_id option:selected").text().trim().substr(12,5));
});

$("#select_maestro_gen").on('change', function(){
    $.ajax({
        url: '../assets/data/Controller/horastrabajadas/horasControl.php',
        type: "POST",
        data: {action:'cargar_clases_docente', maestro:$(this).val(), generacion:$("#select_generaciones_carreras").val() },
        success: function(data){
            try{
                var resp = JSON.parse(data);
                $("#select_clase_id").html("<option value='' selected='' disabled=''>Seleccione clase</option>");
                for(var c in resp){
                    $("#select_clase_id").append(
                        `<option value="${resp[c].idClase}">
                        [${resp[c].fecha_hora_clase.substring(0,16)}] ${(resp[c].nombre.length > 40 ? resp[c].nombre.substring(0,40)+'...':resp[c].nombre)} ${resp[c].titulo_sesion}</option>`);
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});

$("#select_generaciones_carreras").on('change', function(){
    $.ajax({
        url: '../assets/data/Controller/horastrabajadas/horasControl.php',
        type: "POST",
        data: {action:'cargar_clases_y_docentes_generacion', generacion:$(this).val(), listar:'maestros'},
        success: function(data){
            try{
                var resp = JSON.parse(data);
                $("#select_maestro_gen").html("<option value='' selected='' disabled=''>Seleccione docente</option>");
                for(var c in resp){
                    $("#select_maestro_gen").append(`<option value="${resp[c].id_maestro}">${resp[c].aPaterno} ${resp[c].aMaterno} ${resp[c].nombres}</option>`)
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});

$("#tabhoras_trabajadas").on('click',function () {
    IniciarTablaHorasDocentes();
});

function cargar_carreras_docentes(){
    $.ajax({
        url: '../assets/data/Controller/horastrabajadas/horasControl.php',
        type: "POST",
        data: {action:'cargar_carreras_docentes'},
        success: function(data){
            try{
                resp = JSON.parse(data);
                $("#select_carreras_docentes").html("<option value='' selected='' disabled=''>Seleccione carrera</option>");
                for(var c in resp){
                    $("#select_carreras_docentes").append(`<option value="${resp[c].idCarrera}">${resp[c].nombre}</option>`)
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

$("#select_carreras_docentes").on('change', function(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: {action:'obtenerGeneracionesCarrera', idCarr:$(this).val()},
        success: function(data){
            try{
                var resp = JSON.parse(data);
                $("#select_generaciones_carreras").html("<option value='' selected='' disabled=''>Seleccione generación</option>");
                for(var c in resp){
                    $("#select_generaciones_carreras").append(`<option value="${resp[c].idGeneracion}">${resp[c].nombre}</option>`)
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});
let tab_doc_loading = false;

function IniciarTablaHorasDocentes(){
    if(tab_doc_loading){
        return;
    }
    tCalificaciones = $("#table-horas_trabajadas").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            extend: "excel",
            title:'Alumnos',
            className: "btn-primary"
        }, {
            extend: "pdf",
            title:'Alumnos'
        }, {
            extend: "print"
        }],
        "ajax": {
            url: '../assets/data/Controller/horastrabajadas/horasControl.php',
            type: 'POST',
            data: {
                action: 'ConsultaHorasDocTable'
            },
            beforeSend:() => {
                tab_doc_loading = true;
            },
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            complete: () => {
                tab_doc_loading = false;
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

$("#formRegistroHoras").on("submit",function(e){
    e.preventDefault();
    $form = new FormData(this);
    $form.append("action","updateHoras");
    $form.append("case","insert");

    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/horastrabajadas/horasControl.php',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){

            json = JSON.parse(data);
            if(json.estatus == 'ok'){
                Swal.fire('Datos actualizados corrrectamente.');
            }else{
                if(json.hasOwnProperty('info')){
                    Swal.fire({
                        type:'info',
                        text:json.info
                    });
                }else{
                    Swal.fire('Hubo un error intenta de nuevo.');
                }
            }
            $("#formRegistroHoras")[0].reset();
        }
    });

});

//function AsignarID(idhorasAsignar){

//Se modifica para imprimir valores
function AsignarID(idhorasAsignar, pago_total, pago_hora,Horat){
    $("#idhoras").val(idhorasAsignar);
    $("#montoSugerido").val(pago_total);  //Se agrega para imprimir el pago total
    $("#costoHr").val(pago_hora);
    $("#hrsTutoria").val(Horat);
}

$('#ModalRegistrarPago').on('hidden.bs.modal', function () {
    $('#formComprobantedePago')[0].reset();
});

$("#formComprobantedePago").on("submit",function(e){
    e.preventDefault();
    $form = new FormData(this);
    $form.append("action","updatepago");
    $form.append("registra",usrInfo.idAcceso);
   
    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/horastrabajadas/horasControl.php',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            json = JSON.parse(data);
            if(json.estatus == 'ok'){
                Swal.fire('Pago actualizado corrrectamente.');
                $("#formComprobantedePago")[0].reset();
                $("#ModalRegistrarPago").modal('hide');
                IniciarTablaHorasDocentes();
            }else{
                if(json.hasOwnProperty('info')){
                    Swal.fire({
                        type:'info',
                        text:json.info
                    });
                }else{
                    Swal.fire('Hubo un error intenta de nuevo.');
                }
            }
        }
    });
});
