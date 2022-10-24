glob_elm = null;
var tipo_ciclo = {
    1: 'Cuatrimestre',
    2: 'Semestre',
    3: 'Trimestre'
}
let search = false;
function ver_mensualidades(node, prospecto, carrera = 0, generacion = 0, url = false, to_show = false){
    data_send = {
        action: 'obtener_plan_pago_callcenter',
        prospecto: prospecto,
        instit: 20,
        info_alumno: 1
    }
    if(carrera != 0){
        data_send.inscrito_a = carrera
    }else if(generacion != 0){
        data_send.buscar_generacion = generacion
    }else{
        swal('Error', 'No se pudo obtener la información del plan de pago', 'error');
        return;
    }
    if(!search){
        $.ajax({
            url: !url? '../assets/data/Controller/planpagos/pagosControl.php' : url,
            type: 'POST',
            data: data_send,
            beforeSend: function(){
                search = true;
                $(node).css('cursor', 'wait');
                $('body').css('cursor', 'wait')
            },
            success: function(data){
                try{
                    plan = JSON.parse(data);
                    if(plan.estatus == 'ok' && plan.data != false){
                        var generacion = plan.data.generaciones.find(elm => elm.asignacion.length > 0);
                        if(generacion != undefined){
                            var infor_mostrar = {};
                            infor_mostrar.alumno = plan.data.info_alumno;
                            infor_mostrar.generacion = generacion;
                            infor_mostrar.generacion.asignacion = generacion.asignacion[0];
                            infor_mostrar.conceptos = plan.data.pagos_aplicar;
                            infor_mostrar.promociones = plan.data.promociones;
                            show_info(infor_mostrar, to_show, (!url? '../assets/data/Controller/planpagos/pagosControl.php' : url));
                        }else{
                            swal("", "El alumno aún no se encuentra inscrito a esta oferta académica", "info");
                        }
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            complete: function(){
                $(node).css('cursor', 'default');
                $('body').css('cursor', 'default');
                search = false;
            }
        });
    }
}
recibed = null;



async function show_info(info, to_show, url){
    recibed = info;
    var string_mensualidad = '';
    var str_primer_mensualidad = '';
    mensl = info.conceptos.find(elm => elm.categoria == 'Mensualidad');
    var fech_prim_mes = '';
    if(mensl !== undefined){
        fech_prim_mes = mensl.primer_mensualidad;
        string_mensualidad = `<p><b>Fecha primera mensualidad: </b> ${fech_prim_mes.substr(0, 10)}</p>`
        str_primer_mensualidad = fech_prim_mes.substr(0, 10);
    }
    
    tabla_conceptos = '';
    for(c in info.conceptos){
        var estatus_concepto = '<span class="badge badge-info">Pagado</span>';
        var info_promo_concepto = '';
        if(info.conceptos[c].aplicados.length == 0){
            estatus_concepto = '<span class="badge badge-secondary">Sin pagos</span>';
        }else{
            estatus_concepto = `<span class="badge badge-success">${Math.max(... info.conceptos[c].aplicados.map(elm => elm.numero_de_pago))} pagos aplicados</span>`
        }
        var pago_concepto = info.conceptos[c].aplicados.reduce((acc, cur)=>{
            acc = acc === false ? cur : (parseFloat(cur.saldo) + parseFloat(cur.restante) < parseFloat(acc.saldo) + parseFloat(acc.restante)) ? cur : acc;
            return acc;
        }, false);
        if(pago_concepto.moneda === null){
            pago_concepto.moneda = 'MXN';
        }
        var pago_fin_concepto = moneyFormat.format(info.conceptos[c].precio)+` ${info.alumno.tipoPago == '2' ? 'USD' : 'MXN'}`;
        if(pago_concepto.porcentaje && pago_concepto.porcentaje != null && info.conceptos[c].categoria != 'Mensualidad'){
            pago_fin_concepto = `${moneyFormat.format(pago_concepto.costototal)} ${pago_concepto.moneda.toLocaleUpperCase()}`;
            info_promo_concepto = `<span class="text-success">${parseFloat(pago_concepto.porcentaje).toFixed(1)}%-</span>`;
        }else if(info.conceptos[c].categoria != 'Mensualidad'){
            var promo_concepto_aplicar = info.promociones.find(elm => elm.id_concepto == info.conceptos[c].id_concepto);
            if(promo_concepto_aplicar !== undefined){
                info_promo_concepto = `<span class="text-success">${parseFloat(promo_concepto_aplicar.porcentaje).toFixed(1)}%-</span>`;
            }
        }
        var nombre_concepto = `${info.conceptos[c].categoria.toLocaleUpperCase()}`;
        if(info.conceptos[c].categoria == 'Mensualidad'){
            nombre_concepto = `${info.conceptos[c].categoria.toLocaleUpperCase()} <i class="fa fa-plus-circle" onclick="show_mensualidades()"></i>`;
        }else if(info.conceptos[c].categoria == 'Reinscripción'){
            nombre_concepto = `${info.conceptos[c].categoria.toLocaleUpperCase()} <i class="fa fa-plus-circle" onclick="show_reinscripciones()"></i>`;
        }else if(info.conceptos[c].categoria == 'Generales' || info.conceptos[c].categoria == 'General'){
            nombre_concepto = `${info.conceptos[c].categoria.toLocaleUpperCase()} <br><small>${info.conceptos[c].concepto.toLocaleUpperCase()}</small>`;
        }else if(info.conceptos[c].categoria == 'Inscripción' && info.conceptos[c].aplicados.length > 0 && info.conceptos[c].parcialidades == '1'){
            nombre_concepto = `${info.conceptos[c].categoria.toLocaleUpperCase()} <i class="fa fa-plus-circle" onclick="show_parcialidades()"></i>`;
        }
        tabla_conceptos += `<tr>
                                <td>${nombre_concepto}</td>
                                <td>${pago_fin_concepto}</td>
                                <td>${info_promo_concepto}</td>
                                <td>${info.conceptos[c].fechalimitepago != null ? info.conceptos[c].fechalimitepago.substr(0, 10):'-'}</td>
                                <td>${estatus_concepto}</td>
                            </tr>`;
        if(info.conceptos[c].categoria == 'Inscripción' && info.conceptos[c].aplicados.length > 0 && info.conceptos[c].parcialidades == '1'){
            sub_td_insc = '';
            for(i = 0; i < info.conceptos[c].aplicados.length; i++){
                if(info.conceptos[c].aplicados[i].moneda === null){
                    info.conceptos[c].aplicados[i].moneda = 'mxn';
                }
                sub_td_insc += `<tr>
                    <td>Inscripción</td>
                    <td>${moneyFormat.format(info.conceptos[c].aplicados[i].montopagado)+' '+(info.conceptos[c].aplicados[i].moneda.toLocaleUpperCase() == 'USD' ? 'USD' : 'MXN')}</td>
                    <td>${info.conceptos[c].aplicados[i].fechapago.substr(0,10)}</td>
                    </tr>`;
            }
            sub_td_insc += `<tr class="bg-light">
                <td>Restante por pagar:</td>
                <td>${moneyFormat.format(Math.min(... info.conceptos[c].aplicados.filter(elm => parseFloat(elm.restante) >= 0).map(elm => elm.restante)))}</td>
                <td></td>
            </tr>`;
            tabla_conceptos += `<tr id="desglose_parcialidades" style="display:none;">
                <td colspan="5">
                    <table class="table">
                        <thead>
                        <th>Concepto</th>
                        <th>Monto pagado</th>
                        <th>Fecha de pago</th>
                        </thead>
                        <tbody>${sub_td_insc}</tbody>
                    </table>
                </td>
            </tr>`;
        }

        if(info.conceptos[c].categoria == 'Mensualidad'){
            var sub_td = '';
            inicio_mens = mensl.primer_mensualidad.substr(0,10);
            inicio_mens = new Date(inicio_mens+"T00:00:00");
            aux_mens = inicio_mens;
            for(i = 0; i < info.conceptos[c].numero_pagos; i++){
                var info_promo = '';
                var mens_pagada = null;
                var pago_mensualidad = info.conceptos[c].aplicados.reduce((acc, cur)=>{
                    if(cur.numero_de_pago == (i+1)){
                        acc = acc === false ? cur : (parseFloat(cur.saldo) + parseFloat(cur.restante) < parseFloat(acc.saldo) + parseFloat(acc.restante)) ? cur : acc;
                    }
                    return acc;
                }, false);
                if(pago_mensualidad){
                    mens_pagada = pago_mensualidad.fecha_limite_pago.substr(0, 10);
                    if(pago_mensualidad.numero_de_pago > 1){
                        var aux_mensualidad = info.conceptos[c].aplicados.reduce((acc, cur)=>{
                            if(cur.numero_de_pago == (i)){
                                acc = acc === false ? cur : (parseFloat(cur.saldo) + parseFloat(cur.restante) < parseFloat(acc.saldo) + parseFloat(acc.restante)) ? cur : acc;
                            }
                            return acc;
                        }, false);
                        mens_pagada = aux_mensualidad.fecha_limite_pago.substr(0, 10);
                    }else{
                        mens_pagada = str_primer_mensualidad;
                    }
                }
                if(pago_mensualidad.moneda === null){
                    pago_mensualidad.moneda = 'MXN';
                }
                var estatus_pago = `<span class="badge badge-secondary">Sin pagos</span>`;
                if(pago_mensualidad === undefined){
                    estatus_pago = `<span class="badge badge-secondary">Sin pagos</span>`;
                }else if(parseFloat(pago_mensualidad.saldo) + parseFloat(pago_mensualidad.restante) < 1){
                    estatus_pago = `<span class="badge badge-success">Pagado</span>`;
                }else if(parseFloat(pago_mensualidad.saldo) + parseFloat(pago_mensualidad.restante) >= 1){
                    estatus_pago = `<span class="badge badge-warning">Saldo pendiente</span>`;
                }
                var pago_fin = `${moneyFormat.format(info.conceptos[c].precio)} ${info.alumno.tipoPago == '2' ? 'USD' : 'MXN'}`;
                
                if(pago_mensualidad.porcentaje && pago_mensualidad.porcentaje != null){
                    pago_fin = `${moneyFormat.format(pago_mensualidad.costototal)} ${pago_mensualidad.moneda.toLocaleUpperCase()}`;
                    info_promo = `<span class="text-success">${parseFloat(pago_mensualidad.porcentaje).toFixed(1) > 1 ? parseFloat(pago_mensualidad.porcentaje).toFixed(1)+'%-' : ''}</span>`;
                }else{
                    var promo_con = info.promociones.find(elm => elm.id_concepto == info.conceptos[c].id_concepto && elm.id_prospecto == info.alumno.idAsistente && elm.Nopago != '' && elm.Nopago.includes((i+1).toString()));
                    promo_con = promo_con === undefined ? info.promociones.find(elm => elm.id_concepto == info.conceptos[c].id_concepto && elm.id_prospecto == info.alumno.idAsistente && (elm.Nopago == '' || elm.Nopago == null || elm.Nopago == 0)) : promo_con;
                    promo_con = promo_con === undefined ? info.promociones.find(elm => elm.id_concepto == info.conceptos[c].id_concepto && (elm.Nopago == '' || elm.Nopago == null || elm.Nopago == 0)) : promo_con;
                    
                    if(promo_con !== undefined && pago_mensualidad === false){
                        pago_fin = `${moneyFormat.format(parseFloat(info.conceptos[c].precio) - parseFloat(info.conceptos[c].precio) * (parseFloat(promo_con.porcentaje) / 100))} ${info.alumno.tipoPago == '2' ? 'USD' : 'MXN'}`;
                        var vigencia = '';
                        if(promo_con.fechainicio != null && promo_con.fechafin != null){
                            vigencia = `<i style="font-size:small;" class="fa fa-info" title="Vigencia: ${promo_con.fechainicio.substr(0,10)} - ${promo_con.fechafin.substr(0,10)}"></i>`;
                        }else if(promo_con.Nopago.length > 0){
                            vigencia = `<i style="font-size:small;" class="fa fa-info" title="Aplica para mensualidades: ${promo_con.Nopago.join(', ')}"></i>`;
                        }
                        info_promo = `<span class="text-success">${parseFloat(promo_con.porcentaje) > 1 ? parseFloat(promo_con.porcentaje).toFixed(1)+'%- '+vigencia : ''}</span> `;
                    }
                }
                sub_td += `<tr>
                <td>Mensualidad ${i+1}</td>
                <td>${pago_fin}</td>
                <td>${info_promo}</td>
                <td>${ mens_pagada ? mens_pagada : aux_mens.toISOString().substr(0,10)}</td>
                <td>${estatus_pago}</td>
                </tr>`;
                aux_mens.setMonth(aux_mens.getMonth()+1);
            }
            tabla_conceptos += `<tr id="desglose_mensualidad" style="display:none;"><td colspan="5"><table class="table"><tbody>${sub_td}</tbody></table></td></tr>`;
        }

        if(info.conceptos[c].categoria == 'Reinscripción'){
            var sub_td_reins = '';
            // consultar mensualidades pagado
            let apl = await new Promise( (res, rej) =>{
                $.ajax({
                    // async:false,
                    type: "POST",
                    url: url,
                    data: {action:'consultar_pagos_anteriores', concepto: info.conceptos[c].id_concepto, prospecto: info.alumno.idAsistente},
                    success: function (response) {
                        res(JSON.parse(response));
                    }
                });
            } );
            for(i = 0; i < apl.data.length; i++){
                var pago_re = apl.data[i];
                var info_promo_re = '';

                estatus_pago = `<span class="badge badge-success">Pagado</span>`;
                
                var pago_fin = `${moneyFormat.format(pago_re.costototal)} ${pago_re.moneda.toLocaleUpperCase()}`;
                
                if(pago_re.porcentaje && pago_re.porcentaje != null){
                    pago_fin = `${moneyFormat.format(pago_re.costototal)} ${pago_re.moneda.toLocaleUpperCase()}`;
                    info_promo_re = `<span class="text-success">${parseFloat(pago_re.porcentaje).toFixed(1) > 1 ? parseFloat(pago_re.porcentaje).toFixed(1)+'%-' : ''}</span>`;
                }
                sub_td_reins += `<tr>
                <td>Reinscripción ${i+1}</td>
                <td>${pago_fin}</td>
                <td>${info_promo_re}</td>
                <td>${pago_re.fecha_limite_pago}</td>
                <td>${estatus_pago}</td>
                </tr>`;
            }
            tabla_conceptos += `<tr id="desglose_reinscripcion" style="display:none;"><td colspan="5"><table class="table"><tbody>${sub_td_reins}</tbody></table></td></tr>`;
        }
    }
    modal = `<div class="modal fade" id="modal_ver_plan" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header pb-0">
                    <h4 class="modal-title">PLAN DE PAGOS</h4>
                    </div>
                    <div class="modal-body pt-0">
                    <div class="row mt-4">
                        <div class="col-md-5 col-sm-6">
                        <img src="${info.alumno.foto}" class="w-50" alt="">
                        <p><b>Inicio de la generación: </b> ${info.generacion.fecha_inicio.substr(0, 10)} <!--23/05/2022--></p>
                        ${string_mensualidad}
                        </div>
                        <div class="col-md-2 col-sm-6 d-sm-none d-md-block">
                        <!-- libre -->
                        </div>
                        <div class="col-md-5 col-sm-6">
                        <p><b>Nombre: </b> ${info.alumno.nombre} ${info.alumno.aPaterno} ${info.alumno.aMaterno}</p>
                        <p><b>Carrera: </b> ${info.generacion.nombre}</p>
                        <p class="text-center bg-light"><i>${info.generacion.asignacion.ciclo_actual}° ${tipo_ciclo.hasOwnProperty(info.generacion.asignacion.tipoCiclo)? tipo_ciclo[info.generacion.asignacion.tipoCiclo] : ''}</i></p>
                        </div>
                        <div class="col-12 table-responsive">
                        <table class="table">
                            <thead>
                            <tr><th>CONCEPTO</th>
                            <th>MONTO A PAGAR</th>
                            <th>BECA / PROMOCIÓN</th>
                            <th>FECHA PAGO</th><th></th>
                            </tr></thead>
                            <tbody>
                            ${tabla_conceptos}
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary mb-2">Cerrar</button>
                    </div>
                </div>
                </div>
            </div>`;
    if(to_show === false){
        $("#modal_ver_plan").remove();
        $("body").append(modal);
        setTimeout(() => {
            $("#modal_ver_plan").modal('show');
        }, 200);
    }else{
        $(`#${to_show}`).html(`<div class="row mt-4">
                <div class="col-md-5 col-sm-6">
                <img src="${info.alumno.foto}" class="w-50" alt="">
                <p><b>Inicio de la generación: </b> ${info.generacion.fecha_inicio.substr(0, 10)} <!--23/05/2022--></p>
                ${string_mensualidad}
                </div>
                <div class="col-md-2 col-sm-6 d-sm-none d-md-block">
                <!-- libre -->
                </div>
                <div class="col-md-5 col-sm-6">
                <p><b>Nombre: </b> ${info.alumno.nombre} ${info.alumno.aPaterno} ${info.alumno.aMaterno}</p>
                <p><b>Carrera: </b> ${info.generacion.nombre}</p>
                <p class="text-center bg-light"><i>${info.generacion.asignacion.ciclo_actual}° ${tipo_ciclo.hasOwnProperty(info.generacion.asignacion.tipoCiclo)? tipo_ciclo[info.generacion.asignacion.tipoCiclo] : ''}</i></p>
                </div>
                <div class="col-12 table-responsive">
                <table class="table">
                    <thead>
                    <tr><th>CONCEPTO</th>
                    <th>MONTO A PAGAR</th>
                    <th>BECA / PROMOCIÓN</th>
                    <th>FECHA PAGO</th><th></th>
                    </tr></thead>
                    <tbody>
                    ${tabla_conceptos}
                    </tbody>
                </table>
                </div>
            </div>`);
    }
}
function show_mensualidades(){
    $("#desglose_mensualidad").fadeToggle();
}
function show_reinscripciones(){
    $("#desglose_reinscripcion").fadeToggle();
}
function show_parcialidades(){
    $("#desglose_parcialidades").fadeToggle();
}
function buscar_imagen(url){
    var request = new XMLHttpRequest();
    request.open("GET", url, true);
    request.send();
    request.onload = function() {
    if (request.status == 200){
        return url;
    } else {
        return false;
    }
    }
}
