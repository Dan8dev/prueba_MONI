// let estatus_seguimiento = [];

$(document).ready(function(){
    init_data_e();
    cargar_estatus_seguimiento();
    $('#input_search').select2({
        ajax: {
          url: '../assets/data/Controller/prospectos/search.php',
          data: function (params) {
            var query = {
              search: params.term,
              type: 'public'
            }
            return query;
          },
          processResults: function (data) {
            data = JSON.parse(data);
            return {
              results: data.items
            };
          }
        }
      });
});

$("#input_search").on('change', function(){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/marketing/marketingControl.php",
        data: {action:'consultar_seguimientos', prospecto:$(this).val()},
        beforeSend: function(){
            $('#span_nombre').html('');
            $('#span_correo').html('');
            $('#span_telefono').html('');
            $('#subscriptions').html('<tr><td class="text-center" colspan="2"><i class="fa fa-solid fa-spinner fa-spin"></i></td></tr>');
        },
        success: function (data){
            try{
                var segims = JSON.parse(data);
                if(segims.info){
                    $('#span_nombre').html(`${segims.info.aPaterno} ${segims.info.aMaterno} ${segims.info.nombre}`);
                    $('#span_correo').html(segims.info.correo);
                    $('#span_telefono').html(segims.info.telefono || ' - ');
                }
                if(segims.results.length > 0){
                    $('#subscriptions').html(segims.results.map(elm => {return `<tr onclick="ir_a_tabla('${elm.tipo_atencion}', ${elm.evento_carrera} , ${elm.etapa}, '${elm.correo.trim()}')">
                        <td>${elm.tipo_atencion == 'carrera' ? elm.nombre_carrera : elm.titulo}</td>
					 	<td><b><span class="text-dark fw-bold">${elm.nombres_asesor}</span></b></td>
                        <td>${estatus_seguimiento.find(est => est.idElemento == elm.etapa).nombre}</td>
                    </tr>`}).join(''));
                }else{
                    $('#subscriptions').html('<tr><td class="text-center" colspan="2">Este prospecto aún no se ha registrado a un evento o carrera.</td></tr>');
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});

function ir_a_tabla(tipo, e_c, etapa, nombre){
    switch (etapa) {
        case 1:
            $("#multiple_evento").val(`${tipo}-${e_c}`);
            $("#multiple_evento").trigger('change');
            $("#home-tab").click();
            setTimeout(()=>{
                $("#tabla_general_prospectos_filter input").val(nombre);
                $("#tabla_general_prospectos_filter input").keyup();
            }, 150);
            break;
        case 3:
            if(tipo == 'carrera'){
                $("#multiple_inscritos").val(`${e_c}`);
                $("#multiple_inscritos").trigger('change');
                $("#profile-tab").click();
                setTimeout(()=>{
                    $("#listado_prospectos_filter input").val(nombre);
                    $("#listado_prospectos_filter input").keyup();
                }, 150);
            }
            break;
        default:
            $("#multiple_otrosestatus").val(`${tipo}-${e_c}`);
            $("#multiple_otrosestatus").trigger('change');
            $("#multiple_estatus").val(`${etapa}`);
            $("#multiple_estatus").trigger('change');
            $("#otrosestatus-tab").click();
            setTimeout(()=>{
                $("#listado_otros_prospectos_filter input").val(nombre);
                $("#listado_otros_prospectos_filter input").keyup();
            }, 150);
            break;
    }
}

function cargar_estatus_seguimiento(){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/marketing/marketingControl.php",
        data: {action:'estatus_seguimiento'},
        success: function (response) {
            try{
                estatus_seguimiento = JSON.parse(response);
                // estatus_seguimiento
                $("#select_estat_seguimiento").html(estatus_seguimiento.map(elm=>{return `<option value="${elm.idElemento}" title="${elm.descipcion}">${elm.nombre}</option>`}).join(''))
                filt_estatus_seguimiento = estatus_seguimiento.filter(elm => elm.idElemento != 1 && elm.idElemento != 3);
                $("#multiple_estatus").html(filt_estatus_seguimiento.map(elm=>{return `<option value="${elm.idElemento}" title="${elm.descipcion}">${elm.nombre}</option>`}).join(''))
            }catch(e){
                console.log(e);
                console.log(response);
            }
        }
    });
}

function init_data_e(){
    select_general();
}

function select_general(){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/marketing/marketingControl.php",
        data: {action:'select_general'},
        success: function (response) {
            try{
                var options = JSON.parse(response);
                var html_opt = '';
                var html_carr = '';
                for(i in options.carreras){
                    var crr = options.carreras[i];
                    html_opt += `<option value="carrera-${crr.idCarrera}">
                        ${crr.tipo_carrera_text != '' ? '<b>'+crr.tipo_carrera_text.toUpperCase()+'</b> - ' : ''} ${crr.nombre.toUpperCase()}
                    </option>`
                    html_carr += `<option value="${crr.idCarrera}">${crr.tipo_carrera_text != '' ? '<b>'+crr.tipo_carrera_text.toUpperCase()+'</b> - ' : ''} ${crr.nombre.toUpperCase()}</option>`;
                }

                for(i in options.eventos){
                    var evt = options.eventos[i];
                    html_opt += `<option value="evento-${evt.idEvento}">
                        ${evt.tipo != '' ? '<b>'+evt.tipo.toUpperCase()+'</b> - ' : ''} ${evt.titulo.toUpperCase()}
                    </option>`
                }

                $("#multiple_evento").html(html_opt);
                $("#multiple_otrosestatus").html(html_opt);
                $("#multiple_inscritos").html(html_carr);
                $('.js-example-basic-multiple').select2();
                
            }catch(e){
                console.log(e);
                console.log(response);
            }
        }
    });
}
// TABLA PRINCIPAL DE PROSPECTOS
$("#multiple_evento").on('change', function(){
    if($(this).val().length > 0){
        $.ajax({
            type: "POST",
            url: "../assets/data/Controller/marketing/marketingControl.php",
            data: {action:'listar_prospectos', seleccion:$(this).val()},
            beforeSend: function(){
                $("#multiple_evento").attr('disabled', true);
            },
            success: function (response) {
                try{
                    var prospectos = JSON.parse(response);
                    $("#tabla_general_prospectos").DataTable().clear();
                    for(var p in prospectos){
                        var pros = prospectos[p];
                        $("#tabla_general_prospectos").DataTable().row.add([
                            `<div style="position: relative;">
                                <span title="${pros.prospecto}">${pros.aPaterno} ${pros.aMaterno} ${pros.nombre}</span>
                                ${pros.id_afiliado !== null ? '<i class="fa fa-user-circle" style="position: absolute;right: 0px;top: 2px"></i>' : ''}
                                ${(pros.hasOwnProperty('generacion_asignada') && pros.generacion_asignada !== null) ? '<i class="fa fa fa-graduation-cap" style="position: absolute;right: -2px;top: 18px;" title="'+pros.generacion_asignada.toUpperCase()+'"></i>' : ''}
                            </div>`,
                            `<a href="javascript:void(0)" class="clpb" aria-label="${pros.telefono}">${pros.telefono}</a>`,
                            `<a href="javascript:void(0)" class="clpb" aria-label="${pros.correo}">${pros.correo}</a>`,
                            (pros.seguimiento != '' ? pros.seguimiento.substr(0,10) : '-'),
                            (pros.tipo_atencion == 'evento' ? pros.tipo.toUpperCase():pros.tipo_carrera_text.toUpperCase()),
                            (pros.tipo_atencion == 'evento' ? pros.titulo.toUpperCase() : pros.nombre_carrera.toUpperCase()),
                            pros.nombres_asesor+' '+pros.apaterno_asesor,
                            `<button class="btn btn-secondary" onclick="seguimiento_e(${pros.idReg}, ['${pros.nombre.trim()} ${pros.aPaterno.trim()}','${(pros.tipo_atencion == 'evento' ? pros.titulo.toUpperCase() : pros.nombre_carrera.toUpperCase())}'], ${pros.prospecto})"><i class="fa fa-cogs" aria-hidden="true"></i></button>
                            <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_${pros.tipo_atencion}(${pros.prospecto}, ${pros.evento_carrera}, '${pros.nombre.trim()} ${pros.aPaterno.trim()}', ${pros.idInstitucion})"><i class="fas fa-money-bill-wave"></i></button>
                            `
                        ]);
                    }
                    $("#tabla_general_prospectos").DataTable().draw();
                    $("#tabla_general_prospectos").DataTable().columns.adjust();
                }catch(e){
                    console.log(e);
                    console.log(response);
                }                
            },
            complete: function(){
                $("#multiple_evento").attr('disabled', false);
            }
        });
    }else{
        $("#tabla_general_prospectos").DataTable().clear();
        $("#tabla_general_prospectos").DataTable().draw();
    }
});
// TABLA DE PROSPECTOS CONFIRMADOS
$("#multiple_inscritos").on('change', function(){
    if($(this).val().length > 0){
        $.ajax({
            type: "POST",
            url: "../assets/data/Controller/marketing/marketingControl.php",
            data: {action:'listar_confirmados', seleccion:$(this).val()},
            beforeSend: function(){
                $("#multiple_inscritos").attr('disabled', true);
            },
            success: function (response) {
                try{
                    var inscritos = JSON.parse(response);
                    console.log(inscritos);
                    $("#listado_prospectos").DataTable().clear();
                    for(var p in inscritos){
                        var pros = inscritos[p];
                        var sub_table = `<table class="table table-sm">`;
                        new_seg = pros.seguimientos.reduce((acc, elm)=>{
                            if(acc.find(fnd => fnd.idReg == elm.idReg) == undefined){
                                acc.push(elm);
                            }
                            return acc;
                        }, []);
                        pros.seguimientos = new_seg;
                        for(var st in pros.seguimientos){
                            sub_elm = pros.seguimientos[st];
                            var title_show = sub_elm.tipo_atencion == 'evento' ? sub_elm.titulo.toUpperCase() : sub_elm.nombre_carrera.toUpperCase();
                            title_show = mayusc_minusc(title_show);
                            sub_table += `<tr>
                                <td class="py-1 px-2" style="white-space: normal;">${title_show}<i class="fa fa-info-circle float-right" title="Fecha de registro: ${sub_elm.seguimiento != '' ? sub_elm.seguimiento.substr(0,10) : ''}" aria-hidden="true"></i></td>
                                <td class="py-1 px-2">${estatus_seguimiento.find(elm => parseInt(elm.idElemento) == sub_elm.etapa).nombre}</td>
                                <td class="py-1 px-2">
                                    <button class="btn btn-secondary" onclick="seguimiento_e(${sub_elm.idReg}, ['${sub_elm.nombre.trim()} ${sub_elm.aPaterno.trim()}', '${title_show.toUpperCase()}'], ${sub_elm.prospecto})"><i class="fa fa-cogs" aria-hidden="true"></i></button>
                                    <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_${sub_elm.tipo_atencion}(${sub_elm.prospecto}, ${sub_elm.evento_carrera}, '${sub_elm.nombre.trim()} ${sub_elm.aPaterno.trim()} - ${title_show.toUpperCase()}', ${sub_elm.idInstitucion})"><i class="fas fa-money-bill-wave"></i></button>
                                </td>
                            </tr>`;
                        }
                        sub_table += `</table>`;
                        collapse = `<div id="accordion-test" class="card-box">
                                        <div class="card">
                                            <div class="card-header" id="heading1_${p}">
                                                <h5 class="m-0">
                                                <a href="" class="text-dark collapsed" data-toggle="collapse" data-target="#collapse1_${p}" aria-expanded="false" aria-controls="collapse1_${p}">Ver áreas de interés</a>
                                                </h5>
                                            </div>

                                            <div id="collapse1_${p}" class="collapse" aria-labelledby="heading1_${p}" data-parent="#accordion-test">
                                                <div class="card-body" style="max-height: 150px;overflow: auto;">
                                                    ${sub_table}
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                        $("#listado_prospectos").DataTable().row.add([
                            `<div style="position: relative;">
                                <span title="${pros.prospecto}" class="mr-3">${pros.aPaterno} ${pros.aMaterno} ${pros.nombre}</span>
                                ${pros.id_afiliado !== null ? '<i class="fa fa-user-circle" style="position: absolute;right: 0px;top: 2px"></i>' : ''}
                                ${(pros.hasOwnProperty('generacion_asignada') && pros.generacion_asignada !== null) ? '<i class="fa fa fa-graduation-cap" style="position: absolute;right: -2px;top: 18px;" title="'+pros.generacion_asignada.toUpperCase()+'"></i>' : ''}
                            </div>`,
                            `<small><a href="javascript:void(0)" class="clpb" aria-label="${pros.telefono}">${pros.telefono}</a><br> <a href="javascript:void(0)" class="clpb" aria-label="${pros.correo}">${pros.correo}</a></small>`,
                            collapse
                        ]);
                    }
                    $("#listado_prospectos").DataTable().draw();
                    $("#listado_prospectos").DataTable().columns.adjust();
                }catch(e){
                    console.log(e);
                    console.log(response);
                }                
            },
            complete: function(){
                $("#multiple_inscritos").attr('disabled', false);
            }
        });
    }else{
        $("#listado_prospectos").DataTable().clear();
        $("#listado_prospectos").DataTable().draw();
    }
});

$("#multiple_otrosestatus").on('change', cunsultar_multiples_estatus);
$("#multiple_estatus").on('change', cunsultar_multiples_estatus);
// TABLA DE OTROS PROSPECTOS
function cunsultar_multiples_estatus(){
    if($("#multiple_otrosestatus").val().length > 0 && $("#multiple_estatus").val().length > 0){
        $.ajax({
            type: "POST",
            url: "../assets/data/Controller/marketing/marketingControl.php",
            data: {action:'listar_otros', seleccion:$("#multiple_otrosestatus").val(), estatus:$("#multiple_estatus").val()},
            beforeSend: function(){
                $("#multiple_otrosestatus").attr('disabled', true);
                $("#multiple_estatus").attr('disabled', true);
            },
            success: function (response) {
                try{
                    var otros = JSON.parse(response);
                    $("#listado_otros_prospectos").DataTable().clear();
                    for(var p in otros){
                        var pros = otros[p];
                        var sub_table = `<table class="table table-sm"  id="table_${pros.prospecto}">`;
                        new_seg = pros.seguimientos.reduce((acc, elm)=>{
                            if(acc.find(fnd => fnd.idReg == elm.idReg) == undefined){
                                acc.push(elm);
                            }
                            return acc;
                        }, []);
                        pros.seguimientos = new_seg;
                        for(var st in pros.seguimientos){
                            sub_elm = pros.seguimientos[st];
                            var title_show = sub_elm.tipo_atencion == 'evento' ? sub_elm.titulo.toUpperCase() : sub_elm.nombre_carrera.toUpperCase();
                            title_show = mayusc_minusc(title_show);
                            sub_table += `<tr>
                                <td class="py-1 px-2" style="white-space: normal;">${title_show}</td>
                                <td class="py-1 px-2">${estatus_seguimiento.find(elm => elm.idElemento == sub_elm.etapa).nombre} <i class="fa fa-info-circle float-right" title="Fecha de registro: ${sub_elm.seguimiento != '' ? sub_elm.seguimiento.substr(0,10) : ''}" aria-hidden="true"></i></td>
                                <td class="py-1 px-2">
                                    <button class="btn btn-secondary" onclick="seguimiento_e(${sub_elm.idReg}, ['${sub_elm.nombre.trim()} ${sub_elm.aPaterno.trim()}', '${title_show.toUpperCase()}'], ${sub_elm.prospecto})"><i class="fa fa-cogs" aria-hidden="true"></i></button>
                                    <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_${sub_elm.tipo_atencion}(${sub_elm.prospecto}, ${sub_elm.evento_carrera}, '${sub_elm.nombre.trim()} ${sub_elm.aPaterno.trim()} - ${title_show.toUpperCase()}', ${sub_elm.idInstitucion})"><i class="fas fa-money-bill-wave"></i></button>
                                </td>
                            </tr>`;
                        }
                        sub_table += `</table>`;
                        collapse = `<div id="accordion-test" class="card-box">
                                        <div class="card">
                                            <div class="card-header" id="heading1_${p}">
                                                <h5 class="m-0">
                                                <a href="" onclick="ver_mas_seguimientos(${pros.prospecto}, this)"  class="text-dark collapsed" data-toggle="collapse" data-target="#collapse1_${p}" aria-expanded="false" aria-controls="collapse1_${p}">Ver áreas de interés</a>
                                                </h5>
                                            </div>

                                            <div id="collapse1_${p}" class="collapse" aria-labelledby="heading1_${p}" data-parent="#accordion-test">
                                                <div class="card-body" style="max-height: 150px;overflow: auto;">
                                                    ${sub_table}
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                        $("#listado_otros_prospectos").DataTable().row.add([
                            `<div style="position: relative;">
                                <span title="${pros.prospecto}" class="mr-3">${pros.aPaterno} ${pros.aMaterno} ${pros.nombre}</span>
                                ${pros.id_afiliado !== null ? '<i class="fa fa-user-circle" style="position: absolute;right: 0px;top: 2px"></i>' : ''}
                                ${(pros.hasOwnProperty('generacion_asignada') && pros.generacion_asignada !== null) ? '<i class="fa fa fa-graduation-cap" style="position: absolute;right: -2px;top: 18px;" title="'+pros.generacion_asignada.toUpperCase()+'"></i>' : ''}
                            </div>`,
                            `<small><a href="javascript:void(0)" class="clpb" aria-label="${pros.telefono}">${pros.telefono}</a><br> <a href="javascript:void(0)" class="clpb" aria-label="${pros.correo}">${pros.correo}</a></small>`,
                            collapse
                        ]);
                    }
                    $("#listado_otros_prospectos").DataTable().draw();
                    $("#listado_otros_prospectos").DataTable().columns.adjust();
                }catch(e){
                    console.log(e);
                    console.log(response);
                }                
            },
            complete: function(){
                $("#multiple_otrosestatus").attr('disabled', false);
                $("#multiple_estatus").attr('disabled', false);
            }
        });
    }else{
        $("#listado_otros_prospectos").DataTable().clear();
        $("#listado_otros_prospectos").DataTable().draw();
    }
}

function ver_mas_seguimientos(prosp, node){
    if($(node).attr('aria-expanded') == 'false'){
        $(`#table_${prosp}`).html(`<tr><td></td><td></td><td></td></tr>`)
        $.ajax({
            type: "POST",
            url: "../assets/data/Controller/marketing/marketingControl.php",
            data: {action:'consultar_seguimientos', prospecto : prosp},
            beforeSend: function(){
                $(`#table_${prosp}`).html(`<tr><td colspan="3" class="text-center"><i class="fa fa-solid fa-spinner fa-spin"></i></td></tr>`)
            },
            success: function(data){
                try{
                    pros = JSON.parse(data);
                    sub_table = '';
                    for(var st in pros.results){
                        sub_elm = pros.results[st];
                        var title_show = sub_elm.tipo_atencion == 'evento' ? sub_elm.titulo.toUpperCase() : sub_elm.nombre_carrera.toUpperCase();
                        title_show = mayusc_minusc(title_show);
                        sub_table += `<tr>
                            <td class="py-1 px-2" style="white-space: normal;">${title_show}</td>
                            <td class="py-1 px-2">${estatus_seguimiento.find(elm => elm.idElemento == sub_elm.etapa).nombre} <i class="fa fa-info-circle float-right" title="Fecha de registro: ${sub_elm.seguimiento != '' ? sub_elm.seguimiento.substr(0,10) : ''}" aria-hidden="true"></i></td>
                            <td class="py-1 px-2">
                                <button class="btn btn-secondary" onclick="seguimiento_e(${sub_elm.idReg}, ['${sub_elm.nombre.trim()} ${sub_elm.aPaterno.trim()}', '${title_show.toUpperCase()}'], ${sub_elm.prospecto})"><i class="fa fa-cogs" aria-hidden="true"></i></button>
                                <button type="button" class="btn waves-effect btn-secondary but-circle" onclick="pago_${sub_elm.tipo_atencion}(${sub_elm.prospecto}, ${sub_elm.evento_carrera}, '${sub_elm.nombre.trim()} ${sub_elm.aPaterno.trim()} - ${title_show.toUpperCase()}', ${sub_elm.idInstitucion})"><i class="fas fa-money-bill-wave"></i></button>
                            </td>
                        </tr>`;
                    }
                    $(`#table_${prosp}`).html(sub_table)
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            }
        });
    }
}

function reload_tablas(){
    $("#multiple_evento").change();
    $("#multiple_inscritos").change();
    $("#multiple_otrosestatus").change();
}

$("#tipo_nuevo_interes").on('change',()=>{
    $("#id_nuevo_interes").html('');
    if($("#tipo_nuevo_interes").val() == 'evento'){
        $("#id_nuevo_interes").html(list_eventos.map(elm => {
            return `<option value="${elm.idEvento}">${elm.titulo.toUpperCase()}</option>`
        }).join(''));
        $("#id_nuevo_interes").parent().removeClass('d-none');
    }else{
        $("#id_nuevo_interes").html(list_carreras.map(elm => {
            return `<option value="${elm.idCarrera}">${elm.nombre.toUpperCase()}</option>`
        }).join(''));
        $("#id_nuevo_interes").parent().removeClass('d-none');
    }
    $("#btn_add_atencion").attr('disabled', false);
})

$("#btn_add_atencion").on('click', function(){
    var tipo = $("#tipo_nuevo_interes").val();
    var id = $("#id_nuevo_interes").val();
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/marketing/marketingControl.php",
        data: {
            n_prosp_tipo: tipo,
            prospecto: $("#inp_prospect_edit_ej").val(),
            interes: id,
            action:'asignar_prospecto'
        },
        success: function (response) {
            try{
                var nuevo_seg = JSON.parse(response);
                if(nuevo_seg.estatus == 'ok'){
                    swal("Exito", "Prospecto registrado", "success");
                    $("#modal_seguimiento").modal('hide');
                    $("#multiple_evento").change();
                }else{
                    swal('',nuevo_seg.info,'info');
                }
                reload_tablas();
            }catch(e){
                console.log(e);
                console.log(response);
            }
        }
    });
});

$("#select_estat_seguimiento").on('change', function(){
    var prev = $("#current_prosp_stat").val();
    var nuevo = $("#select_estat_seguimiento").val();
    if(prev == nuevo){
        $("#btn_change_estatus_prosp").attr('disabled', true);
    }else{
        $("#btn_change_estatus_prosp").attr('disabled', false);
    }
});

$("#btn_change_estatus_prosp").on('click', function(){
    var prev = $("#current_prosp_stat").val();
    swal({
        text: `¿Cambiar el estatus de '${$("#select_estat_seguimiento option[value='"+prev+"']").html()}' a '${$("#select_estat_seguimiento option:selected").html()}'`,
        icon: 'info',
        buttons: ['Cancelar', 'Aceptar'],
    }).then((val)=>{
        if(val){
            $.ajax({
                type: "POST",
                url: "../assets/data/Controller/prospectos/prospectoControl.php",
                data: {
                    action:'cambio_estatus_prospecto',
                    seguim:$("#prospecto_llamar").val(),
                    stat:$("#select_estat_seguimiento").val()
                },
                success: function (response) {
                    try {
                        var cambio = JSON.parse(response);
                        if(cambio.estatus == 'ok'){
                            swal('', 'Estatus cambiado', 'success');
                            $("#current_prosp_stat").val($("#select_estat_seguimiento").val());
                            $("#btn_change_estatus_prosp").attr('disabled', true);
                            $("#multiple_evento").change();
                        }else{
                            swal('',cambio.info,'info');
                        }
                        reload_tablas();
                    } catch (error) {
                        console.log(error);
                        console.log(response);
                    }
                }
            });
        }else{
            $("#select_estat_seguimiento").val(prev);
        }
    })
});

function mayusc_minusc(string){
    const str = string;
    const arr = str.split(" ");
    for (var i = 0; i < arr.length; i++) {
        arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1).toLowerCase();
    }
    const str2 = arr.join(" ");
    return str2;
}
let loading = false;
function cargar_solicitudes_clinicas(){
    if(loading){
        return;
    }
    $.ajax({
        url: '../assets/data/Controller/marketing/marketingControl.php',
        type: "POST",
        data: {action:'consultar_clinicas_solicitudes'},
        beforeSend: function(){
            loading = true;
        },
        success: function(data){
            try{
                var resp = JSON.parse(data);
                $("#listado_solicitudes_clinicas").DataTable().clear();
                for(i in resp){
                    var solic = resp[i];
                    var atiende = false;
                    if(typeof list_ejecutivas !== 'undefined'){
                        atiende = list_ejecutivas.find(elm => elm.idPersona == solic.idMk_persona);
                    }
                    
                    if(!atiende){
                        if(solic.idMk_persona == usrInfo.idPersona && usrInfo.idTipo_Persona == 3){
                            atiende = usrInfo.persona;
                        }
                    }
                    // var titlle_show = (solic.tipo_atencion == 'evento') ? list_eventos.find(elm => elm.idEvento == solic.evento_carrera).titulo : list_carreras.find(elm => elm.idCarrera == solic.evento_carrera).nombre;
                    /*
                    <div class="badge badge-pill badge-secondary ml-3 d-none" style="cursor:pointer">
                                <i class="fa fa-cogs" aria-hidden="true" onclick="seguimiento_e(${solic.idReg}, ['${solic.responsable_nombre.trim()} ${solic.aPaterno.trim()}', '${titlle_show.toUpperCase()}'], ${solic.idAsistente})"></i>
                            </div>
                     */
                    $("#listado_solicitudes_clinicas").DataTable().row.add([
                        `${solic.nombre} 
                            `, // nombre de la clinica
                        (solic.preautorizacion === 'invalid' ? '<span class="text-danger fw-bold" style="cursor:pointer;">NO ACREDITADA</span>' : (solic.preautorizacion === null ? '<span class="text-warning fw-bold">PENDIENTE</span>' : '<span class="text-success fw-bold">ACREDITADA</span>' )), // nombre de la clinica
                        solic.fecha_registro.substring(0, 10),
                        atiende ? atiende.nombres : '-',
                        `<ul class="mb-0">
                            <li><b>Responsable:</b> ${solic.responsable_nombre} ${solic.aPaterno}
                                <ul>
                                    <li><b>Correo:</b> ${solic.correo}</li>
                                    <li><b>Teléfono:</b> ${solic.telefono}</li>
                                </ul>
                            </li>
                            </ul>
                        <button 
                            class="btn btn-primary btn-sm btn-block mt-4" 
                            onclick="verificar_institucion(${solic.id_institucion}, '${solic.nombre.trim().replace(/[\W_]+/g,"")}', '${solic.responsable_nombre.trim()} ${solic.aPaterno.trim()}', '${solic.correo}', '${solic.telefono}', ${solic.preautorizacion !== null ? true : false}, '${solic.comentario}', ${solic.preautorizacion === 'invalid'}, '${solic.preautorizacion}')"
                        >
                            <i class="fa fa-check-square"></i>
                        </button>`
                    ]);
                }

                /*
                <ul class="mb-0 autotd"><li><b>Responsable:</b> ${solic.responsable_nombre} ${solic.aPaterno}<ul><li><b>Correo:</b> ${solic.correo}</li><li><b>Teléfono:</b> ${solic.telefono}</li></ul></li></ul><button class="btn btn-primary btn-sm btn-block" style="display: block;position: absolute;bottom: 0;width: 93%;"><i class="fa fa-cogs"></i></button> 
                $(".autotd").each(function(){$(this).parent().css('position', 'relative');})
                */
                $("#listado_solicitudes_clinicas").DataTable().draw();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            loading = false;
        }
    });
}

function verificar_institucion(institucion, nombre_cl, nombre_res, correo, telefono, asigned, comentario, rechazada, folio){
    $("#conainer_folio").addClass('d-none');
    if(rechazada){
        $("#space-for-alert").html(`<div class="alert alert-danger p-1">Esta clínica no acreditó titularidad.</div>`)
    }else{
        $("#space-for-alert").html(``)
        if(asigned){
            $("#inp_ver_folio").val(folio)
            $("#conainer_folio").removeClass('d-none');
        }
    }
    $("#clinica_verify").val('');
    $("#inp_ver_nombre_clinica").val(nombre_cl);
    $("#inp_ver_nombre_responsable").val(nombre_res);
    $("#inp_ver_correo").val(correo);
    $("#inp_ver_telefono").val(telefono);
    $("#comentario_verificacion").val(comentario);
    if(asigned){
        $("#buttons_clinic").addClass('d-none');
        $("#comentario_verificacion").attr('readonly', true);
    }else{
        $("#clinica_verify").val(institucion);
        $("#buttons_clinic").removeClass('d-none');
        $("#comentario_verificacion").attr('readonly', false);
    }
    $("#modalVerificarClinica").modal('show');
}

function set_verified(){
    swal({
        icon:'info',
        title:'Verificar clínica?',
        text:'Está seguro de marcar la clínica como verificada',
        buttons:['Cancelar','Confirmar']
    }).then((val)=>{
        if(val){
            $.ajax({
                type: "POST",
                url: "../assets/data/Controller/marketing/marketingControl.php",
                data: {action:'verify_clinic', clinic:$("#clinica_verify").val(), comentario: $("#comentario_verificacion").val()},
                success: function (response) {
                    try {
                        var resp = JSON.parse(response);
                        if(resp.estatus == 'ok'){
                            swal({
                                icon:'success',
                                title:'Información actualizada'
                            })
                        }else{
                            swal({
                                icon:'info',
                                text:resp.info
                            })
                        }
                        cargar_solicitudes_clinicas();
                        $("#modalVerificarClinica").modal('hide');
                    } catch (error) {
                        
                    }
                }
            });
        }
    })
}
function set_non_verified(){
    swal({
        icon:'warning',
        title:'No acreditar?',
        text:'Está seguro de marcar la clínica no acreditar',
        buttons:['Cancelar','Confirmar']
    }).then((val)=>{
        if(val){
            $.ajax({
                type: "POST",
                url: "../assets/data/Controller/marketing/marketingControl.php",
                data: {action:'non_verify_clinic', clinic:$("#clinica_verify").val(), comentario: $("#comentario_verificacion").val()},
                success: function (response) {
                    try {
                        var resp = JSON.parse(response);
                        if(resp.estatus == 'ok'){
                            swal({
                                icon:'success',
                                title:'Información actualizada'
                            })
                        }else{
                            swal({
                                icon:'info',
                                text:resp.info
                            })
                        }
                        cargar_solicitudes_clinicas();
                        $("#modalVerificarClinica").modal('hide');
                    } catch (error) {
                        
                    }
                }
            });
        }
    })
}
