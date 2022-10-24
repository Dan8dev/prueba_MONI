$(document).ready(function() {
    init_d();
});

function init_d(){
    cargar_pagos_alumnos();
    // cargar_pagos_reportados();
    CarrerasGen();
    obtener_concentrado_alumnos();
    // cargar_pagos_rechazados();
}
function obtener_concentrado_alumnos(){
    /* {
        "idRelacion": "131",
        "idalumno": "10",
        "idgeneracion": "1",
        "fecha_inscripcion": "2021-09-01 00:00:00",
        "fecha_primer_colegiatura": null,
        "fecha_liberacion": "2022-04-30 20:00:16",
        "calificacion": "7",
        "fecha_titulacion": "2022-05-30 17:01:00",
        "estatus": "2",
        "diplomado": "1",
        "nombre": "PABLO JESÚS",
        "aPaterno": "ALVAREZ",
        "aMaterno": "ECHÁVARRI",
        "telefono": "(811) 8446-857",
        "celular": "8118446857",
        "email": "pabloalvareze@hotmail.com",
        "pais": "37",
        "pais_nacimiento": "0",
        "pais_estudio": "0",
        "contrasenia": "Sencilla1",
        "ciudad": "MONTERREY",
        "colonia": "CENTRO",
        "calle": "JULIÁN VILLARREAL",
        "cp": "64000",
        "matricula": "",
        "pais_nombre": "MÉXICO",
        "estado_nombre": null,
        "nombre_carrera": "Operador en Adicciones y Salud Mental (OTA)",
        "nombre_generacion": "Generación 1 Operador en Adicciones y Salud Mental (OTA)",
        "idCarrera": "1"
    } */
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/planpagos/alumnosPagosControl.php",
        data: {action:'obtener_concentrado_alumnos'},
        beforeSend: function(){
            $("#concentrado-alumnos").DataTable().clear();
            $("#concentrado-alumnos").DataTable().row.add(['<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>']);
            $("#concentrado-alumnos").DataTable().draw();
        },
        success: function (response) {
            var lista = JSON.parse(response);
            $("#concentrado-alumnos").DataTable().clear();
            for(var i in lista){
                var alumno = lista[i];
                var telefono = alumno.telefono !== null ? alumno.telefono.replace(/[^0-9]+/g,'') : '';
                celular = alumno.celular !== null ? alumno.celular.replace(/[^0-9]+/g,'') : '';
                string_tel = '';
                if(telefono != ''){
                    string_tel = telefono != celular && celular != '' ? `<a href="tel:${telefono}">${telefono}</a> / <a href="tel:${celular}">${celular}</a>` : `<a href="tel:${telefono}">${telefono}</a>`;
                }
                $("#concentrado-alumnos").DataTable().row.add([
                    `${alumno.aPaterno} ${alumno.aMaterno} ${alumno.nombre}`,
                    `<p class="mb-0"><b>Correo: </b> ${alumno.email}</p><p class="mb-0"><b>Teléfono: </b> ${string_tel}</p>`,
                    `<ul>
                        ${alumno.generaciones_arr.map(elm => 
                            `<li><div class="row mb-1"><div class="col">${elm[1]}</div><div class="col"><button class="btn btn-primary btn-sm float-right" onclick="ver_mensualidades(this, ${alumno.idalumno}, 0, ${elm[0]})"><i class="fas fa-eye" title="Ver plan pagos"></i></button></div></div></li>`).join('')}
                    </ul>`,
                    `<button class="btn btn-primary" onclick="consultar_historial_pago_id('${alumno.idalumno}')">Estado de cuenta</button>
                    `
                ]);
            }
            $("#concentrado-alumnos").DataTable().draw();
            $("#concentrado-alumnos").DataTable().columns.adjust();
            selects_datatable("concentrado-alumnos")
        }
    });
}
function Validar_Pagos(idAlumno){
    var genCert = $("#genCert").val();

    swal({
        icon:'info',
        title:'¿Está seguro de validar los pagos del alumno para el proceso de certificaciones?',
        text:'',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "",
                closeModal: true
            }
        }
    }).then(result=>{
        if(result){
            $.ajax({
                url: "../assets/data/Controller/planpagos/planpagosControl.php",
                type: "POST",
                data: {action:'Validar_pagos_alumno',
                    id: idAlumno,
                    idGen: genCert},
                success: function(data){
                    try{
                        var resp_estat = JSON.parse(data);
                        if(resp_estat.estatus == 'error'){
                            swal('Error al validar pagos','error');
                        }
                        if(resp_estat.estatus == 'ok'){
                            swal('Validado Correctamente','success').then((result)=>{
                                TablaAlumnos.ajax.reload(null,false);
                            });
                        }
                    }catch(e){
                        console.log(e);
                        console.log(data);
                    }
                }
            });  
        }
    });

}

$("#carrerasCert").on('change', function(){
    tableValidarAlumnos();
})

function tableValidarAlumnos(){
    var idCarrera =  $("#carrerasCert").val();
    cargarGeneraciones(idCarrera);
}

$("#genCert").on('change', function(){
    var idCarrera =  $("#carrerasCert").val();
    var idGeneracion = $(this).val();
    TablaAlumnos = $("#table-alumnos-certificaciones").DataTable({
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
        
        'language':{
            'sLengthMenu': 'Mostrar _MENU_ registros',
            'sInfo': 'Mostrando registro del _START_ al _END_ de un total de _TOTAL_ registros',
            'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros',
            'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
            'sSearch': 'Buscar:',
            'sLoadingRecords': 'Cargando',
            'oPaginate':{
                'Pregunta_e': 'pregunta',
                'Opciones_e': 'opciones'
            }
        },
        'bDestroy': true,
        'iDisplayLength': 15,
        'order':[
            [0,'asc']
        ],
            "ajax" :{
                url: "../assets/data/Controller/planpagos/planpagosControl.php",
                type: 'POST',
                dataType: "JSON",
                data:{
                    action: 'ValidarPagosAlumnosCertificaciones',
                    idCarr: idCarrera,
                    idGen: idGeneracion
                },
                //contentType: false,
                //processData: false,
                error: function(e){
                    console.log(e.responseText);
                }
            }
        });
});

function cargarGeneraciones(idCarrera){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'obtenerGeneracionesCarrera',
        idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#genCert").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#genCert").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        }
    });
}

function cargar_pagos_alumnos() {
    return;
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: "POST",
        data: {action:'cargar_pagos_alumnos'},
        beforeSend : function(){
            $("#loader").css("display", "block")
        },
        success: function(data){
            try{
                resp = JSON.parse(data);
                var monto_pagos = 0;
                $("#table-alumnos-pagos").DataTable().clear();
                if(resp.estatus == 'ok'){
                    for(var i = 0; i < resp.data.length; i++){
                        monto_pagos += parseFloat(resp.data[i].montopagado) + parseFloat(resp.data[i].cargo_retardo);
                        info_p = JSON.parse(resp.data[i].detalle_pago);
                        var total_pagado = parseFloat(resp.data[i].montopagado);
                        if(resp.data[i].cargo_retardo !== null){
                            total_pagado+=parseFloat(resp.data[i].cargo_retardo);
                        }

                        var detalle_p = '';
                        if(resp.data[i].referencia != null && resp.data[i].referencia != ''){
                            detalle_p += '<b>Referencia:</b> ' + resp.data[i].referencia + '<br>';
                        }
                        if(resp.data[i].codigo_de_autorizacion != null && resp.data[i].codigo_de_autorizacion != ''){
                            detalle_p += '<b>Autorización:</b> ' + resp.data[i].codigo_de_autorizacion + '<br>';
                        }
                        string_concepto = resp.data[i].concepto;
                        if(resp.data[i].categoria == 'Mensualidad' && resp.data[i].numero_de_pago > 0){
                            string_concepto = resp.data[i].concepto.slice(0,11)+` [N° ${resp.data[i].numero_de_pago}]`+resp.data[i].concepto.slice(11)
                        }
                        string_comentario = '<span style="font-family: monospace;font-style: oblique;white-space: normal;color:#0000a1;">';
                        if(resp.data[i].comentario_callcenter && resp.data[i].comentario_callcenter != ''){
                            string_comentario += `[<b style="color:#e91e63;">Marketing -</b> ${resp.data[i].comentario_callcenter}]<br>`;
                        }
                        string_comentario += '</span>';
                        $("#table-alumnos-pagos").DataTable().row.add([
                            resp.data[i].nombre_alumno,
                            `<span title="${resp.data[i].id_pago}">${string_concepto}</span><span class="float-right"><a href="javascript:void(0)" onclick="ver_mensualidades(this, ${resp.data[i].id_prospecto}, 0, ${resp.data[i].id_generacion})"><i class="fa fa-folder-open" aria-hidden="true"></a></i></span>`,
                            info_p.id+' '+((resp.data[i].comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${resp.data[i].comprobante}" target="_blank"><i class="fas fa-file"></i></a>` : ''),
                            (info_p.id=='Callcenter')?resp.data[i].nombre_callcenter:'',
                            detalle_p,
                            resp.data[i].fecha_limite_pago,
                            resp.data[i].moneda,
                            moneyFormat.format(parseFloat(resp.data[i].costototal) + parseFloat(resp.data[i].saldo) + parseFloat(resp.data[i].cargo_retardo)),
                            resp.data[i].fechapago.substr(0, 10),
                            moneyFormat.format( total_pagado ),
                            moneyFormat.format( parseFloat(resp.data[i].saldo) + parseFloat(resp.data[i].restante) ),
                            resp.data[i].metodo_de_pago,
                            resp.data[i].banco_de_deposito,
                            string_comentario,
                            //`<button class="btn btn-primary" data-toggle="modal" data-target=".modal-modificar-fechapago" onclick="modificarfecha_pago('${resp.data[i].id_pago}','${resp.data[i].como_realizo_pago}','${resp.data[i].metodo_de_pago}','${moneyFormat.format( total_pagado )}','${resp.data[i].fechapago.substr(0, 10)}','${resp.data[i].banco_de_deposito}','${string_concepto}')">Modificar</button>` + 
                            ` <button class="btn btn-primary" data-toggle="modal" data-target=".modal-consultar-historial" onclick="consultar_historial_pago_id('${resp.data[i].id_prospecto}')">Estado de cuenta</button>`
                        ])
                    }
                }
                $("#foot_total_pagos").html(`Total de pagos: <span class="float-right alert-link">${moneyFormat.format(monto_pagos)}<span>`);
                $("#table-alumnos-pagos").DataTable().draw();
                setTimeout(function(){
                    $("#table-alumnos-pagos").DataTable().columns.adjust();
                }, 1000);
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
let loading_reportados = false;
function cargar_pagos_reportados() {
    if(loading_reportados){
        return;
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: "POST",
        data: {action:'cargar_pagos_reportados'},
        beforeSend : function(){
            loading_reportados = true;
            $("#loader").css("display", "block")
            $("#table-pagos-notificados").DataTable().clear();
            $("#table-pagos-notificados").DataTable().draw();
            $("#table-pagos-notificados").DataTable().row.add(['<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>']);
            $("#table-pagos-notificados").DataTable().draw();
        },
        success: function(data){
            try{
                resp_rep = JSON.parse(data);
                var monto_pagos = 0;
                $("#table-pagos-notificados").DataTable().clear();
                if(resp_rep.estatus == 'ok'){
                    for(var i = 0; i < resp_rep.data.length; i++){
                        monto_pagos += parseFloat(resp_rep.data[i].montopagado);
                        select_status = '';
                        var info_p = JSON.parse(resp_rep.data[i].detalle_pago);
                        if(resp_rep.data[i].estatus == 'pendiente'){
                            select_status = `<div>
                                <select class="form-control" id="select-pago-${resp_rep.data[i].id_pago}" onchange="cambiar_estatus_pago(${resp_rep.data[i].id_pago}, this, '${resp_rep.data[i].nombre_alumno}', '${resp_rep.data[i].concepto}',${resp_rep.data[i].id_prospecto},${resp_rep.data[i].id_generacion})">
                                    <option value="pendiente" selected>Pendiente</option>
                                    <option value="verificado">Verificado</option>
                                    <option value="rechazado" >Rechazado</option>
                                </select>
                            </div>`
                        }else{
                            select_status = resp_rep.data[i].estatus;
                        }
                        var total_pagado = parseFloat(resp_rep.data[i].montopagado);
                        if(resp_rep.data[i].cargo_retardo !== null){
                            total_pagado += parseFloat(resp_rep.data[i].cargo_retardo);
                        }
                        string_concepto = resp_rep.data[i].concepto;
                        if(resp_rep.data[i].categoria == 'Mensualidad' && resp_rep.data[i].numero_de_pago > 0){
                            string_concepto = resp_rep.data[i].concepto.slice(0,11)+` [N° ${resp_rep.data[i].numero_de_pago}]`+resp_rep.data[i].concepto.slice(11)
                        }
                        string_comentario  = '';
                        if(resp_rep.data[i].comentario_callcenter != '' && info_p.id == 'Callcenter'){
                            string_comentario = `<span style="font-family: monospace;font-style: oblique;white-space: normal;color:#0000a1;">[<b style="color:#e91e63;">Marketing - </b>${resp_rep.data[i].comentario_callcenter}]</span>`;
                        }
                        if(string_concepto.includes('Generación')){
                            string_concepto = string_concepto.split('Generación')[0];
                        }
                        $("#table-pagos-notificados").DataTable().row.add([
                            resp_rep.data[i].nombre_alumno,
                            (string_concepto.split('-').length > 0 && resp_rep.data[i].nombre_generacion !== null ? string_concepto.split('-')[0].trim() : string_concepto),
                            resp_rep.data[i].nombre_carrera || '',
                            resp_rep.data[i].nombre_generacion !== null ?  'Generación '+resp_rep.data[i].nombre_generacion : '',
                            info_p.id,
                            (info_p.id=='Callcenter')?resp_rep.data[i].nombre_callcenter:'',
                            resp_rep.data[i].fecha_limite_pago,
                            resp_rep.data[i].moneda,
                            moneyFormat.format(parseFloat(resp_rep.data[i].costototal) + parseFloat(resp_rep.data[i].saldo) + parseFloat(resp_rep.data[i].cargo_retardo)),
                            resp_rep.data[i].fechapago.substr(0,10),
                            moneyFormat.format(total_pagado),
                            moneyFormat.format( parseFloat(resp_rep.data[i].saldo) + parseFloat(resp_rep.data[i].restante) ),
                           `<a href="${resp_rep.data[i].comprobante}" target="_blank">Ver comprobante <i class="fas fa-file"></i></a>`,
                            select_status,
                            resp_rep.data[i].metodo_de_pago,
                            resp_rep.data[i].banco_de_deposito,
                            string_comentario,
                            `<button class="btn btn-primary" data-toggle="modal" data-target=".modal-modificar-fechapago" onclick="modificarfecha_pago('${resp_rep.data[i].id_pago}','${resp_rep.data[i].como_realizo_pago}','${resp_rep.data[i].metodo_de_pago}','${moneyFormat.format( total_pagado )}','${resp_rep.data[i].fechapago.substr(0, 10)}','${resp_rep.data[i].banco_de_deposito}','${resp_rep.data[i].concepto}')">Modificar</button>` +
                            ` <button class="btn btn-primary" data-toggle="modal" data-target=".modal-consultar-historial" onclick="consultar_historial_pago_id('${resp_rep.data[i].id_prospecto}')">Estado de cuenta</button>`
                        ])
                    }
                }

                $("#foot_total_reportados").html(`Total de pagos: <span class="float-right alert-link">${moneyFormat.format(monto_pagos)}<span>`);
                $("#table-pagos-notificados").DataTable().draw();
                $("#table-pagos-notificados").DataTable().columns.adjust();
                selects_datatable("table-pagos-notificados")
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $("#loader").css("display", "none")
            loading_reportados = false;
        }
    });
}
let loading_rechazados = false;
function cargar_pagos_rechazados() {
    if(loading_rechazados == true){
        return;
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: "POST",
        data: {action:'cargar_pagos_rechazados'},
        beforeSend : function(){
            loading_rechazados = true;
            $("#loader").css("display", "block")
            $("#table-pagos-rechazados").DataTable().clear();
            $("#table-pagos-rechazados").DataTable().row.add(['<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>','<i class="fas fa-spinner fa-spin"></i>']);
            $("#table-pagos-rechazados").DataTable().draw();
            
        },
        success: function(data){
            try{
                resp = JSON.parse(data);
                var monto_pagos = 0;
                $("#table-pagos-rechazados").DataTable().clear();
                if(resp.estatus == 'ok'){
                    for(var i = 0; i < resp.data.length; i++){
                        var info_p = JSON.parse(resp.data[i].detalle_pago);

                        var total_pagado = parseFloat(resp.data[i].montopagado);
                        if(resp.data[i].cargo_retardo !== null){
                            total_pagado += parseFloat(resp.data[i].cargo_retardo);
                        }
                        string_concepto = resp.data[i].concepto;
                        if(resp.data[i].categoria == 'Mensualidad' && resp.data[i].numero_de_pago > 0){
                            string_concepto = resp.data[i].concepto.slice(0,11)+` [N° ${resp.data[i].numero_de_pago}]`+resp.data[i].concepto.slice(11)
                        }
                        string_comentario = '<span style="font-family: monospace;font-style: oblique;white-space: normal;color:#0000a1;">';
                        if(resp.data[i].comentario_callcenter && resp.data[i].comentario_callcenter != ''){
                            string_comentario += `[<b style="color:#e91e63;">Marketing -</b> ${resp.data[i].comentario_callcenter}]<br>`;
                        }
                        
                        if(resp.data[i].comentario && resp.data[i].comentario != ''){
                            string_comentario += `[<b style="color:#e91e63;">Cobranza -</b> ${resp.data[i].comentario}]`;
                        }
                        string_comentario += '</span>';
                        if(string_concepto.includes('Generación')){
                            string_concepto = string_concepto.split('Generación')[0];
                        }
                        if(string_concepto.includes('(')){
                            string_concepto = string_concepto.replace('(', '<br>(');
                        }
                        $("#table-pagos-rechazados").DataTable().row.add([
                            resp.data[i].nombre_alumno,
                            (string_concepto.split('-').length > 0 && resp.data[i].nombre_generacion !== null ? string_concepto.split('-')[0].trim() : string_concepto),
                            resp.data[i].nombre_carrera || '',
                            resp.data[i].nombre_generacion !== null ?  'Generación '+resp.data[i].nombre_generacion : '',
                            info_p.id,
                            (info_p.id=='Callcenter')?resp.data[i].nombre_callcenter:'',
                            resp.data[i].fechapago.substr(0,10),
                            resp.data[i].moneda,
                            moneyFormat.format(total_pagado),
                            (resp.data[i].comprobante != '') ? `<a href="../assets/files/comprobantes_pago/${resp.data[i].comprobante}" target="_blank">Ver comprobante <i class="fas fa-file"></i></a>` : '',
                            string_comentario
                        ])
                    }
                }

                $("#table-pagos-rechazados").DataTable().draw();
                $("#table-pagos-rechazados").DataTable().columns.adjust();
                selects_datatable("table-pagos-rechazados");
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            loading_rechazados = false;
            $("#loader").css("display", "none")
        }
    });
}
function cancelar_rechazo(nodo){
    $(`#${nodo}`).val('pendiente');
    $(".inp_dinamico").remove();
}

function rechazar_pago(pago, alumno, concepto){
    var motivo = $(`#motivo_rechazo_${pago}`).val().trim();
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: "POST",
        data: {action:'cambiar_estatus_pago', id_pago:pago, estatus:'rechazado', motivo:motivo, sess: usrInfo.idAcceso},
        success: function(data){
            try{
                var resp_estat = JSON.parse(data);
                if(resp_estat.estatus == 'error'){
                    swal(resp_estat.mensaje,'info');
                }
                init_d();
                cargar_pagos_reportados();
                cargar_pagos_rechazados();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });  
}

function cambiar_estatus_pago(pago, nodo, alumno, concepto, idProspecto, idGeneracion) {
    if(nodo.value != 'pendiente'){
        if(nodo.value == 'rechazado'){
            $(nodo).parent().append(`
            <input type="text" class="form-control mt-2 inp_dinamico" id="motivo_rechazo_${pago}" placeholder="Motivo del rechazo" required>
                <button class="btn btn-primary mt-2 inp_dinamico" onclick="rechazar_pago(${pago}, '${alumno}', '${concepto}')">Confirmar</button>
                <button class="btn btn-secondary mt-2 inp_dinamico" onclick="cancelar_rechazo('${nodo.id}')">Cancelar</button>
            `);
        }else{
            $(".inp_dinamico").remove();
            swal({
                title: `Cambiar estatus a ${nodo.value}?`,
                text: "Pago de " + alumno + " por \"" + concepto + "\".",
                icon: "info",
                buttons: ["Cancelar", "Aceptar"],
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
                        type: "POST",
                        data: {action:'cambiar_estatus_pago', id_pago:pago, estatus:nodo.value, sess: usrInfo.idAcceso},
                        beforeSend: function(){
                            $(nodo).parent().html('<h4 class="text-center">Espera un momento... <i class="fas fa-spinner fa-spin"></i></h4>')
                        },
                        success: function(data){
                            try{
                                var resp_estat = JSON.parse(data);
                                if(resp_estat.estatus == 'error'){
                                    swal(resp_estat.mensaje,'info');
                                }
                                asignar_generacion_alumno_nuevo(idProspecto, idGeneracion);
                                // init_d();
                            }catch(e){
                                console.log(e);
                                console.log(data);
                            }
                            init_d();
                            cargar_pagos_reportados();
                        }
                    });
                }else{
                    nodo.value = 'pendiente';
                }
			
            });
        }
    }else{
        $(".inp_dinamico").remove();
    }
}

function asignar_generacion_alumno_nuevo(idProspecto, idGeneracion) {
    $.ajax({
        url: '../assets/data/Controller/planpagos/pagosControl.php',
        type: "POST",
        data: {action:'asignar_generacion', alumno_generacion:idProspecto, select_alumno_gen:idGeneracion},
        success: function(data){
            try{
                var resp_estat = JSON.parse(data);
                if(resp_estat.estatus == 'ok' && resp_estat.hasOwnProperty('persona') && resp_estat.hasOwnProperty('carrera')){
                    $.ajax({
                        url: '../assets/data/functions/generar_pdf_plan.php',
                        type: 'POST',
                        data: {prospecto:resp_estat.persona,carrera:resp_estat.carrera},
                        success: function(d){
                            // console.log(d)
                        }
                    });
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

$(".nav-tabs").find('.nav-link').on('click', function(){
	var elm = $(this)
	setTimeout(function(){
		$(elm.attr('data-target')).find('table').DataTable().columns.adjust()
	}, 200)
})

function CarrerasGen(){
    var Data = {
       action: "obtenerCarrerasGen"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#list-carrera-gen").html('<option value="" disabled="disabled">Seleccione la Carrera</option>');
            $.each(data, function(key,registro){
                $("#list-carrera-gen").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
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

$("#list-carrera-gen").on('change', function(){
    $("#list-generacion-gen").empty();
    idCarrera = $("#list-carrera-gen").val();
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: 'POST',
        data: {
                action: "buscarGeneraciones", 
                idCarrera: idCarrera
            },
        dataType: 'JSON',
        success : function(data){
            $("#list-generacion-gen").html('<option selected="true" disabled="disabled">Seleccione la Generación</option>');
            $.each(data, function(key,registro){
                $("#list-generacion-gen").append('<option value ='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        complete : function (){
            $("#mostrarselectgeneraciones").show();
        }
    });
})

$("#list-generacion-gen").on('change', function(){
    idGeneracion = $("#list-generacion-gen").val();
    obteneralumnosgeneracionreporte(idGeneracion);
})


function obteneralumnosgeneracionreporte(idGeneracion){

    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: 'POST',
        data: {
                action: "totalalumnosgeneracion", 
                idGeneracion: idGeneracion
            },

        success : function(data){
            $('#totalalumnosgeneracion').html(data);
            // console.log(data);
        },
        complete : function (){
            $("#mostrarselectgeneraciones").show();
        }
    });

    tAlumnoGeneracion = $("#table-alumnos-generacion").DataTable({
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
            url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
            type: 'POST',
            data: {action: 'obteneralumnosgeneracionreporte',
                    idGeneracion: idGeneracion},
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

function listar_prorrogas(){

    tListarprorrogas = $("#table-alumnos-prorrogas").DataTable({
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
            url: '../assets/data/Controller/planpagos/prorrogasControl.php',
            type: 'POST',
            data: {action: 'listar_prorrogas'},
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


$( "#tab_tabla_prorrogas" ).click(function() {
    listar_prorrogas()
  });

  function obtener_informacion_prorroga(id_prorroga){
    $('#letrero_aceptada_rechazada').hide();
    $('#mostrar_opciones_prorroga').hide();
    $.ajax({
        url: '../assets/data/Controller/planpagos/prorrogasControl.php',
        type: 'POST',
        data: {
                action: "obtener_informacion_prorroga", 
                id_prorroga: id_prorroga
            },
        dataType: 'JSON',
        success : function(data){
            if (data.estatus_prorroga!='pendiente') {
                $('#letrero_aceptada_rechazada').show();
                if (data.estatus_prorroga=='rechazado') {
                    $('#letrero_aceptada_rechazada').html('<span class="badge badge-danger"> La prorroga no fue aprobada</span>');
                }
                if (data.estatus_prorroga=='aprobado') {
                    $('#letrero_aceptada_rechazada').html('<span class="badge badge-success"> La prorroga se aprobó</span>');
                }
            } else {
                $('#mostrar_opciones_prorroga').show();
            }
            $('#nombre_solicitante_alumno').html(data.nombre_alumno);
            $('#descripcion_prorroga_solicitante').html(data.descripcion);
            $('#nueva_fecha_pago_alumno').html(data.nuevafechalimitedepago);
            $('#idprorrogasolicitante').val(data.idProrroga);
            $('#idAsistente_solocitudprorroga').val(data.idAsistente);
            myArray = data.nombre_concepto.split("-");
            $('#concepto_prorroga_solicitante').html(myArray[0]+' '+data.numero_de_pago);
            $('#fecha_limite_pago_prorroga').html(data.fechalimitepago);
        },
        complete : function (){
            $("#modal-prorroga").modal('show');
        }
    });
}

$( "#rechazar_prorroga" ).click(function() {
    $( "#rechazar_prorroga" ).attr("disabled", true);
    $.ajax({
        url: '../assets/data/Controller/planpagos/prorrogasControl.php',
        type: 'POST',
        data: {
                action: "rechazar_prorroga", 
                id_prorroga: $('#idprorrogasolicitante').val(),
                idAsistente: $('#idAsistente_solocitudprorroga').val(),
                nuevafechalimitedepago: $('#nueva_fecha_pago_alumno').text()
            },
        dataType: 'JSON',
        success : function(data){
            if (data==1) {
                swal({
                    title: "Prorroga rechazada",
                    icon: "success"
                });
                $("#modal_ver_prorroga").modal('hide');
            }
        },
        complete : function (){
            tListarprorrogas.ajax.reload();
            $( "#rechazar_prorroga" ).attr("disabled", false);
        }
    });
  });

  $( "#aceptar_prorroga" ).click(function() {
    $( "#aceptar_prorroga" ).attr("disabled", true);
    $.ajax({
        url: '../assets/data/Controller/planpagos/prorrogasControl.php',
        type: 'POST',
        data: {
                action: "aprobar_prorroga", 
                id_prorroga: $('#idprorrogasolicitante').val(),
                idAsistente: $('#idAsistente_solocitudprorroga').val(),
                nuevafechalimitedepago: $('#nueva_fecha_pago_alumno').text()
            },
        dataType: 'JSON',
        success : function(data){
            if (data==1) {
                swal({
                    title: "Prorroga aprobada",
                    icon: "success"
                });
                $("#modal_ver_prorroga").modal('hide');
            }
        },
        complete : function (){
            tListarprorrogas.ajax.reload();
            $( "#aceptar_prorroga" ).attr("disabled", false);
        }
    });
  });

  $( "#subirpagos-tab" ).click(function() {
    var Data = {
        action: "obtenerCarrerasGen"
     }
     $.ajax({
         url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
         type: 'POST',
         data: Data,
         dataType: 'JSON',
         success : function(data){
             $("#seleccionacarrerasubirpago").html('<option value="" disabled="disabled">Seleccione la Carrera</option>');
             $.each(data, function(key,registro){
                 $("#seleccionacarrerasubirpago").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
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
  });

  $("#seleccionacarrerasubirpago").on('change', function(){
    $("#listar-generacion-subirpago").empty();
    idCarrera = $("#seleccionacarrerasubirpago").val();
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: 'POST',
        data: {
                action: "buscarGeneraciones", 
                idCarrera: idCarrera
            },
        dataType: 'JSON',
        success : function(data){
            $("#listar-generacion-subirpago").html('<option selected="true" disabled="disabled">Seleccione la Generación</option>');
            $.each(data, function(key,registro){
                $("#listar-generacion-subirpago").append('<option value ='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        complete : function (){
            $("#listar-generacion-subirpago").show();
        }
    });
})

$("#listar-generacion-subirpago").on('change', function(){
    idGeneracion = $("#listar-generacion-subirpago").val();
    obteneralumnosgeneracionnotificarpago(idGeneracion);
})


function obteneralumnosgeneracionnotificarpago(idGeneracion){

    tAlumnoGeneracion = $("#table-pagos-subirpagos").DataTable({
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
            url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
            type: 'POST',
            data: {action: 'obteneralumnosgeneracionnotificarpago',
                    idGeneracion: idGeneracion},
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
$("#inp_busca_alumno").on('keypress', function(e){
    patron = /[A-Za-z]/;
    special = [13, 32];
    if(!patron.test(String.fromCharCode(e.which)) && !special.includes(e.which)){
        e.preventDefault(); 
    }
    if(e.which == 13 && $("#inp_busca_alumno").val().trim().length > 2){
        buscar_alumno($("#inp_busca_alumno").val().trim())
    }
})

$("#button-addon1").on('click', function(){
    if($("#inp_busca_alumno").val().trim().length > 2){
        buscar_alumno($("#inp_busca_alumno").val().trim())
    }
})

function buscar_alumno(nombre){
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: "POST",
        data: {action: 'buscar_alumno_generacion', nombre: nombre},
        dataType: "JSON",
        success: function(rdata){
            try{
                var respuestas = rdata.aaData;
                $("#table-pagos-subirpagos").DataTable().clear();
                for(i in respuestas){
                    $("#table-pagos-subirpagos").DataTable().row.add(respuestas[i]);
                }
                $("#table-pagos-subirpagos").DataTable().draw();
                $("#table-pagos-subirpagos").DataTable().columns.adjust()
            }catch(e){
                console.log(e);
                console.log(rdata);
            }
        }
    });
}

function modificarfecha_pago(id_pago,como_realizo_pago,metododepago,totalpagado,fechapago,bancodedeposito,concepto){
    $('#id_pago_modificar').val(id_pago)
    $('#totalpagado').html(totalpagado)
    $('#nuevafechadepago').val(fechapago)
    $('#modificarbancopago').val(bancodedeposito)
    $('#enviarconcepto').val(concepto)

    switch (como_realizo_pago) {
		case '1':
            $('#metododepago1modificar').val(como_realizo_pago);
            $('#modificarmedotodepago').val(metododepago);
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '2':
            $('#metododepago1modificar').val(como_realizo_pago);
            $('#modificarmedotodepago').val(metododepago);
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '3':
            $('#metododepago1modificar').val(como_realizo_pago);
            $('#modificarmedotodepago').val(metododepago);
			$('#pagoenefectivo').show();
			$('#tarjetadecredito').show();
			$('#tarjetadedebito').show();
			break;
		case '4':
            $('#metododepago1modificar').val(como_realizo_pago);
            $('#modificarmedotodepago').val(metododepago);
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '5':
            $('#metododepago1modificar').val(como_realizo_pago);
            $('#modificarmedotodepago').val(metododepago);
			$('#pagoenefectivo').show();
			$('#tarjetadecredito').show();
			$('#tarjetadedebito').show();
			break;
		case '6':
			$('#transferenciaelectronica').show();
			$('#metododepago1modificar').val(como_realizo_pago);
            $('#modificarmedotodepago').val(metododepago);
			break;
		case '7':
			$('#paypal').show();
			$('#metododepago1modificar').val(como_realizo_pago);
            $('#modificarmedotodepago').val(metododepago);
			break;
		default:
			break;
	}
}

$( "#metododepago1modificar" ).change(function() {

	$('#pagoenefectivo').hide();
	$('#chechenominativo').hide();
	$('#tarjetadecredito').hide();
	$('#tarjetadedebito').hide();
	$('#transferenciaelectronica').hide();
	$('#paypal').hide();

	$('#noselect').prop( "selected", true )


	var metododepago = $( "#metododepago1modificar" ).val();
	switch (metododepago) {
		case '1':
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '2':
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '3':
			$('#pagoenefectivo').show();
			$('#tarjetadecredito').show();
			$('#tarjetadedebito').show();
			break;
		case '4':
			$('#pagoenefectivo').show();
			$('#chechenominativo').show();
			break;
		case '5':
			$('#pagoenefectivo').show();
			$('#tarjetadecredito').show();
			$('#tarjetadedebito').show();
			break;
		case '6':
			$('#transferenciaelectronica').show();
			$('#modificarmedotodepago').val('Transferencia eletrónica');
			break;
		case '7':
			$('#paypal').show();
			$('#modificarmedotodepago').val('Paypal');
			break;
		default:
			break;
	}

  });

$("#form-modificar-fecha-pago").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'modificar_pago');
    const str = $('#totalpagado').text();
    const montopagado = str.slice(1, -1)
    fdata.append('montopagado', montopagado);
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                pr = JSON.parse(data)
                if (pr.estatus == 'ok'){
                    swal({
                        title: 'Pago actualizado',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        location.reload();
                    })
                }
            }catch(e){

                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            location.reload();
            $("#totalfechacorte").prop('disabled',true);
            $("#btnCrearconceptosfechascorte").prop('disabled',false);
        }
    });
})

function consultar_historial_pago_id(idAsistente){
	/* $.ajax({
		url: '../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'consultar_historial_pago_id', idAsistente:idAsistente},
		success: function(data){
			try{
				var aplicados = JSON.parse(data);
				$("#span_saldo").html(``);
				$("#table_pagos_apli").DataTable().clear();
				for (i = 0; i < aplicados.length; i++) {
					if(aplicados[i].estatus == 'verificado'){
						if(aplicados[i].hasOwnProperty('saldo_favor')){
							if(aplicados[i].saldo_favor > 0){
								$("#span_saldo").html(`<div class="alert alert-info" role="alert">
								Usted cuenta con un saldo a favor por <strong class="d-block d-sm-inline-block-force">${moneyFormat.format(aplicados[i].saldo_favor)}</strong> pesos.
							  </div>`);
							}else{
								$("#span_saldo").html(``);
							}
						}else{
							var col_monto_pagar = 0;
							if(aplicados[i].idPromocion != null){
								// monto por pagar menos promocion
								col_monto_pagar = parseFloat(aplicados[i].precio_orig) - (parseFloat(aplicados[i].precio_orig) * (parseFloat(aplicados[i].promocion_info.porcentaje) / 100));
								// // monto por pagar menos aplicados
							}else{
								col_monto_pagar = parseFloat(aplicados[i].precio_orig)
								if(parseInt(aplicados[i].concepto_parcialidades) == 1){
									col_monto_pagar = Math.abs(parseFloat(aplicados[i].costototal) - (parseFloat(aplicados[i].costototal) + parseFloat(aplicados[i].restante) + parseFloat(aplicados[i].montopagado)));
								}
							}
							if(aplicados[i].cargo_retardo > 0){
								col_monto_pagar = col_monto_pagar + parseFloat(aplicados[i].cargo_retardo);
							}
                            string_concepto = aplicados[i].concepto_nombre;
                            if(aplicados[i].concepto_categoria == 'Mensualidad' && parseInt(aplicados[i].numero_de_pago) > 0){
                                string_concepto = aplicados[i].concepto_nombre.slice(0,11)+` [N° ${aplicados[i].numero_de_pago}]`+aplicados[i].concepto_nombre.slice(11)
                            }
							$("#table_pagos_apli").DataTable().row.add([
								`<span title="${aplicados[i].fechapago} [${aplicados[i].id_pago}]">${aplicados[i].fechapago.substr(0,10)}</span>`,
								aplicados[i].concepto_nombre + (parseInt(aplicados[i].concepto_numero_pagos) > 1 ? ` <span>[<b>Pago N° ${parseInt(aplicados[i].numero_de_pago)}  de ${parseInt(aplicados[i].concepto_numero_pagos)}</b> ${aplicados[i].restante > 0 ? ' <small class="text-blue">pago parcial</small>':''}]`:''),
								aplicados[i].detalle_pago.id,
								moneyFormat.format(aplicados[i].precio_orig)+ aplicados[i].tipomoneda,
								(aplicados[i].idPromocion != null) ? `<span class="text-success">- ${parseFloat(aplicados[i].promocion_info.porcentaje).toFixed(2)} %</span>` : "-",
								(aplicados[i].cargo_retardo == null || aplicados[i].cargo_retardo == 0)? '$ 0,00': `<span class="text-danger">${moneyFormat.format(aplicados[i].cargo_retardo)}</span>`,
								moneyFormat.format(col_monto_pagar) + aplicados[i].tipomoneda,
								`<span class="d-block text-info">${moneyFormat.format(aplicados[i].montopagado)} ${aplicados[i].tipomoneda}</span>`,
								(aplicados[i].restante !== null) ? moneyFormat.format(aplicados[i].restante) +aplicados[i].tipomoneda: '$ 0,00',
								aplicados[i].saldo
								
							]);
						}
					}else if(aplicados[i].estatus == 'rechazado'){
						$("#table_pagos_apli").DataTable().row.add([
							`<span title="${aplicados[i].fechapago}" class="text-danger">${aplicados[i].fechapago.substr(0,10)}</span>`,
							'<span class="text-danger"><b>PAGO RECHAZADO</b> '+(aplicados[i].hasOwnProperty('numero_pago_actual') ? `<span title="Pago correspondiente a la mensualidad numero ${parseInt(aplicados[i].numero_pago_actual)+1}">[${parseInt(aplicados[i].numero_pago_actual)+1}] `:'')+aplicados[i].concepto_nombre+` <br><b>Comentario: <i>${(aplicados[i].comentario != null) ? aplicados[i].comentario : ''}</i></b></span>`,
							`<span class="text-danger">${aplicados[i].detalle_pago.id}</div>`,
							`<span class="text-danger">${moneyFormat.format(aplicados[i].precio_orig)} ${aplicados[i].tipomoneda}</div>`,
							"-",
							"-",
							`<span class="text-danger">-</div>`,
							`-`,
							'-',
							'-'
						]);
					}
				}
				$("#table_pagos_apli").DataTable().draw();
                $(".modal-consultar-historial").modal('show')
			}catch(e){
				console.log(e);
				console.log(data)
			}

		}
	}); */
	$.ajax({
		url: '../assets/data/Controller/planpagos/pagosControl.php',
		type: "POST",
		data: {action:'consultar_historial_pago', idAsistente:idAsistente},
		success: function(data){
			try{
				var aplicados = JSON.parse(data);
				$("#span_saldo").html(``);
				$("#table_pagos_apli").DataTable().clear();
				for (i = 0; i < aplicados.length; i++) {
					if(aplicados[i].estatus == 'verificado'){
						if(aplicados[i].hasOwnProperty('saldo_favor')){
							if(aplicados[i].saldo_favor > 0){
								$("#span_saldo").html(`<div class="alert alert-info" role="alert">
								Usted cuenta con un saldo a favor por <strong class="d-block d-sm-inline-block-force">${moneyFormat.format(aplicados[i].saldo_favor)}</strong> pesos.
							  </div>`);
							}else{
								$("#span_saldo").html(``);
							}
						}else{
							var col_monto_pagar = 0;
							if(aplicados[i].idPromocion != null){
								// monto por pagar menos promocion
								col_monto_pagar = parseFloat(aplicados[i].precio_orig) - (parseFloat(aplicados[i].precio_orig) * (parseFloat(aplicados[i].promocion_info.porcentaje) / 100));
								// // monto por pagar menos aplicados
							}else{
								col_monto_pagar = parseFloat(aplicados[i].precio_orig)
								if(parseInt(aplicados[i].concepto_parcialidades) == 1){
									col_monto_pagar = Math.abs(parseFloat(aplicados[i].costototal) - (parseFloat(aplicados[i].costototal) + parseFloat(aplicados[i].restante) + parseFloat(aplicados[i].montopagado)));
								}
							}
							if(aplicados[i].cargo_retardo > 0){
								// col_monto_pagar = col_monto_pagar + parseFloat(aplicados[i].cargo_retardo);
							}
							var total_a_pagar = parseFloat(aplicados[i].montopagado) + ( (aplicados[i].restante > 0.5) ? parseFloat(aplicados[i].restante) : 0 ) + parseFloat(aplicados[i].saldo);
							aplicados[i].restante = parseFloat(aplicados[i].restante);
							aplicados[i].saldo = parseFloat(aplicados[i].saldo);
							var real_restante = (aplicados[i].restante > 0.5) ? aplicados[i].restante : 0;
                            var button_comprobante = '';
                            // if(aplicados[i].hasOwnProperty('enlace_comp')){
                            //     if(aplicados[i].enlace_comp == 'no-found'){
                            //         button_comprobante = `<i class="fa fa-exclamation-circle" aria-hidden="true" title="archivo no encontrado"></i>`;
                            //     }else{
                            //         button_comprobante = `<a href="${aplicados[i].enlace_comp}" target="_blank"><i class="fas fa-file" aria-hidden="true"></i></a>`;
                            //     }
                            // }
							$("#table_pagos_apli").DataTable().row.add([
								aplicados[i].numOrder,
								`<span title="${aplicados[i].fechapago}">${aplicados[i].fechapago.substr(0,10)}</span>`,
								aplicados[i].concepto_nombre + (parseInt(aplicados[i].concepto_numero_pagos) > 1 ? ` <span>[<b>${aplicados[i].concepto_categoria == 'Mensualidad' ? 'Mensualidad' : 'Pago'} ${parseInt(aplicados[i].numero_de_pago)}</b> ${aplicados[i].restante > 0 ? ' <small>pago parcial</small>':''}]`:'')+(aplicados[i].hasOwnProperty('fecha_limite_pago') && aplicados[i].fecha_limite_pago !== null ? `<br> Fecha limite de pago: ${aplicados[i].fecha_limite_pago}`:''),
								button_comprobante+' '+aplicados[i].detalle_pago.id,
								moneyFormat.format(aplicados[i].precio_orig)+ aplicados[i].tipomoneda,
								(aplicados[i].idPromocion != null) ? `<span class="text-success">- ${parseFloat(aplicados[i].promocion_info.porcentaje).toFixed(2)} %</span>` : "-",
								moneyFormat.format(col_monto_pagar) + aplicados[i].tipomoneda,
								moneyFormat.format(parseFloat(aplicados[i].saldo) + parseFloat(aplicados[i].cargo_retardo)),
								moneyFormat.format(total_a_pagar + parseFloat(aplicados[i].cargo_retardo)) + aplicados[i].tipomoneda,
								`<span class="d-block font-weight-bold">${moneyFormat.format(parseFloat(aplicados[i].montopagado) + parseFloat(aplicados[i].cargo_retardo))} ${aplicados[i].tipomoneda}</span>`,
								`
								<b>Concepto: </b> ${moneyFormat.format(real_restante)+' '+aplicados[i].tipomoneda} <br> 
								<b>Recargo: </b> ${moneyFormat.format(aplicados[i].saldo)+' '+aplicados[i].tipomoneda}
								${(parseFloat(aplicados[i].restante) < -0.5)? "<br><b>Saldo a favor: </b><span class='text-success'>"+moneyFormat.format(Math.abs(aplicados[i].restante))+"</span>":""}
								`,
								`<!--<small>
                                    <p class="mb-1"><b>Fecha registro: </b> ${aplicados[i].fecha_registro}</p>
                                    <p class="mb-1"><b>Fecha verificación: </b> ${aplicados[i].fecha_verificacion}</p>
                                </small>--> <button class="btn btn-primary" onclick="mas_informacion(${aplicados[i].id_pago})">Ver más información</button>`
							]);
						}
					}else if(aplicados[i].estatus == 'rechazado'){
						// if(aplicados[i].detalle_pago.id == 'Alumno'){
							$("#table_pagos_apli").DataTable().row.add([
								``,
								`<span title="${aplicados[i].fechapago}" class="text-danger">${aplicados[i].fechapago.substr(0,10)}</span>`,
								'<span class="text-danger"><b>PAGO RECHAZADO</b> '+(aplicados[i].hasOwnProperty('numero_pago_actual') ? `<span title="Pago correspondiente a la mensualidad numero ${parseInt(aplicados[i].numero_pago_actual)+1}">[${parseInt(aplicados[i].numero_pago_actual)+1}] `:'')+aplicados[i].concepto_nombre+` <br>${(aplicados[i].comentario != null && aplicados[i].detalle_pago.id == 'Alumno') ? '<b>Comentario: <i>'+aplicados[i].comentario+'</i></b>' : ''}</span>`,
								`<span class="text-danger">${aplicados[i].detalle_pago.id}</div>`,
								`<span class="text-danger">${moneyFormat.format(aplicados[i].precio_orig)} ${aplicados[i].tipomoneda}</div>`,
								"-",
								"-",
								`<span class="text-danger">-</div>`,
								`-`,
								'-',
								'-',
								''
							]);
						// }
					}
				}
				$("#table_pagos_apli").DataTable().draw();
                $(".modal-consultar-historial").modal('show')
			}catch(e){
				console.log(e);
				console.log(data)
			}

		}
	});

    tGeneraciones = $("#table_pagos_total_carreras").DataTable({
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
            url: '../assets/data/Controller/planpagos/pagosControl.php',
            type: 'POST',
            data: {action: 'obtener_totales_carrera_id', idAsistente: idAsistente},
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

function createElementFromHTML(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();
  
    // Change this to div.childNodes to support multiple top-level nodes.
    return div.firstChild;
}

function mas_informacion(pago){
    $.ajax({
		url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
		type: "POST",
		data: {action:'detalles_pago', id_pago:pago},
		success: function(data){
			try{
				var aplicado = JSON.parse(data);
                if(aplicado.estatus != 'ok'){
                    swal({text:'ha ocurrido un error'});
                    return;
                }
                aplicado = aplicado.data;
                var show_call = '';
                var pago_data = JSON.parse(aplicado.detalle_pago);
                
                if(aplicado.hasOwnProperty('nombre_callcenter') && aplicado.nombre_callcenter != '' && pago_data.id == 'Callcenter'){
                    show_call = `<p><b>Reportó: <br></b>${aplicado.nombre_callcenter}</p>`;
                }
                var button_comprobante = '';
                if(aplicado.hasOwnProperty('enlace_comp')){
                    if(aplicado.enlace_comp == 'no-found'){
                        button_comprobante = `<i class="fa fa-exclamation-circle" aria-hidden="true" title="archivo no encontrado"></i> No se encontró el comprobante o está dañado`;
                    }else{
                        button_comprobante = `<a href="${aplicado.enlace_comp}" target="_blank"><i class="fas fa-file" aria-hidden="true"></i> Ver comprobante</a>`;
                    }
                }

                string_comentario = '<span style="font-family: monospace;font-style: oblique;white-space: normal;color:#0000a1;">';
                if(aplicado.comentario_callcenter && aplicado.comentario_callcenter != ''){
                    string_comentario += `[<b style="color:#e91e63;">Marketing -</b> ${aplicado.comentario_callcenter}]<br>`;
                }
                
                if(aplicado.comentario && aplicado.comentario != ''){
                    string_comentario += `[<b style="color:#e91e63;">Cobranza -</b> ${aplicado.comentario}]`;
                }
                string_comentario += '</span>';
                if(aplicado.comentario_callcenter == '' && aplicado.comentario == ''){
                    string_comentario = '<i>Sin comentarios realizados</i>';
                }
                var nombre_cobranza = '';
                if( aplicado.nombre_cobranza != null ){
                    nombre_cobranza = `<p><b>Verificado por: <br></b>${aplicado.nombre_cobranza}</p>`;
                }

                var swaltext = `<div class="border rounded p-2">
                                    <div class="row text-left">
                                        <div class="col-12 text-center">
                                            <h4>Concepto: ${aplicado.concepto}</h4>
                                        </div>
                                        <div class="col">
                                            <p><b>Fecha registro: <br></b>${aplicado.fecha_registro}</p>
                                            <p><b>Fecha verificación: <br></b>${aplicado.fecha_verificacion}</p>
                                            <p>${show_call}</p>
                                            ${nombre_cobranza}
                                        </div>
                                        <div class="col">
                                            <p><b>Metodo de pago: <br></b>${aplicado.metodo_de_pago}</p>
                                            <p><b>Banco: <br></b>${(aplicado.banco_de_deposito !== null) ? aplicado.banco_de_deposito : ''}</p>
                                            <p><b>Referencia: <br></b>${aplicado.referencia !== null ? aplicado.referencia : ''}</p>
                                            <p><b>Cod. autorización: <br></b>${aplicado.codigo_de_autorizacion !== null ? aplicado.codigo_de_autorizacion : ''}</p>
                                            ${button_comprobante}
                                        </div>
                                        <div class="col-12">
                                            <label>Comentarios:</label>
                                            <p>${string_comentario}</p>
                                        </div>
                                    </div>
                                </div>`;
                var nodo = createElementFromHTML(swaltext);
                swal({
                    content:nodo
                });
            }catch(e){
                console.log(e);
            }
        }
    });
}