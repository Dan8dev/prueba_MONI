$(document).ready(function() {
    init_data();
    construir_select_periodo();
});

function init_data(){
    cargar_ejecutivas();
}
function construir_select_periodo(){
    var fecha_select = fecha_actual;
    $("#select_periodo_comision").html('<option value="0" selected>Seleccione un periodo</option>');
    for(i = 12; i > 0; i--){
        var mes = fecha_select.getMonth() + 1;
        $("#select_periodo_comision").append(`<option value="${fecha_select.getFullYear()}-${((mes < 10)? '0'+mes : mes)}">${meses[mes - 1]} ${fecha_select.getFullYear()}</option>`);
        fecha_select.setMonth(fecha_select.getMonth() - 1);
    }
}
function cargar_ejecutivas(){
    $.ajax({
        url: '../assets/data/Controller/marketing/marketingControl.php',
        type: "POST",
        data: {action:'ejecutivas_comision'},
        success: function(data){
            try{
                var ejecutivas = JSON.parse(data);
                var periodo = null;
                $("#table_ejecutivas").DataTable().clear();
                for(ej in ejecutivas){
                    var ejecutiva = ejecutivas[ej];
                    periodo = ejecutiva.comision_periodo.periodo;

                    $("#table_ejecutivas").DataTable().row.add([
                        `${ejecutiva.nombres} ${ejecutiva.apellidoPaterno} ${ejecutiva.apellidoMaterno }`,
                        (ejecutiva.comision_periodo.detalles ? moneyFormat.format(ejecutiva.comision_periodo.detalles.montoCalculado) : 'Aún no disponible'),
                        `<button class="btn btn-primary" onclick="consultar_estatus(${ejecutiva.idPersona}, '${ejecutiva.nombres}')">Ver</button>`
                    ]).draw();
                }
                if(periodo !== null){
                    $("#periodo_consultado").html(meses[parseInt(periodo[1])-1]+' '+periodo[0]);
                }
                $("#table_ejecutivas").DataTable().draw();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

function consultar_estatus(ejecutiva, nombre_ejecutiva){
    $("#select_periodo_comision").val(0);
    $("#btn_corte").addClass("d-none");
    $("#lbl_persona_seguimiento").html(nombre_ejecutiva);
    $("#select_periodo_comision").val(fecha_otr.toISOString().substring(0,7));
    var periodo = $("#select_periodo_comision").val();
    $("#ejecutiva_id").val(ejecutiva);
    consultar_periodo(ejecutiva, periodo);
}

function consultar_periodo(ejecutiva, periodo){
    if($("#select_periodo_comision").val() != 0){
        $.ajax({
            url: '../assets/data/Controller/marketing/marketingControl.php',
            type: "POST",
            data: {action:'comision_ejecutiva', ejecutiva:ejecutiva, periodo:periodo},
            beforeSend: function(){
                $("#body_table_comisiones").html(`<tr>
                    <td colspan="3">
                        <center><i class="fa fa-spinner fa-spin fa-3x"></i></center>
                    </td>
                </tr>`);
            },
            success: function(data){
                try{
                    var comisiones = JSON.parse(data);
                    var monto_comision = 0;
                    $("#body_table_comisiones").html('')
                    if(comisiones.estatus == 'Pendiente'){
                        for(i in comisiones.corte.detalles){
                            monto_comision += parseFloat(comisiones.corte.detalles[i].comision);
                            $("#body_table_comisiones").append(`
                            <tr>
                                <td>${comisiones.corte.detalles[i].nombre_prospecto}</td>
                                <td><b>${comisiones.corte.detalles[i].tipo_atencion.toUpperCase()}</b> ${comisiones.corte.detalles[i].nombre_interes.toUpperCase()}</td>
                                <td>${moneyFormat.format(comisiones.corte.detalles[i].comision)}</td>
                            </tr>
                            `);
                        }
                        if(comisiones.corte.detalles.length == 0){
                            $("#btn_corte").addClass("d-none");
                            $("#body_table_comisiones").append(`
                            <tr>
                            <td colspan="3">No registros para este periodo.</td>
                            </tr>
                            `);
                        }else{
                            if(comisiones.estatus == 'Pendiente'){
                                $("#btn_corte").removeClass("d-none");
                            }
                        }
                    }else{
                        $("#btn_corte").addClass("d-none");
                        monto_comision = comisiones.corte.detalles.montoCalculado;
                        for(i in comisiones.corte.detalles.jsonEC.operaciones){
                            concepto = comisiones.corte.detalles.jsonEC.operaciones[i];
                            $("#body_table_comisiones").append(`
                                <tr>
                                    <td>${concepto.prospecto}</td>
                                    <td><b>${concepto.tipo_atencion.toUpperCase()}</b> ${concepto.interes.toUpperCase()}</td>
                                    <td>${moneyFormat.format(concepto.monto_comision)}</td>
                                </tr>
                            `);
                        }
                    }
    
                    $("#tbody_comision").html(`
                        <tr>
                            <td>${meses[parseInt(comisiones.corte.periodo[1])-1]+' '+comisiones.corte.periodo[0]}</td>
                            <td>${comisiones.estatus}</td>
                            <td>${moneyFormat.format(monto_comision)}</td>
                        </tr>
                    `)
                }catch(e){
                    console.log(e);
                    console.log(data);
                    $("#body_table_comisiones").html(``);
                }
            }
        });
    }
    $("#modal_comision").modal('show');
}

$("#select_periodo_comision").change(function(){
    var periodo = $(this).val();
    var ejecutiva = $("#ejecutiva_id").val();
    consultar_periodo(ejecutiva, periodo);
});

$("#btn_corte").click(function(){
    swal({
        icon:'info',
        text:'Desea generar el corte de la comisión?',
        buttons: ['Cancelar', 'Aceptar']
    }).then( ()=>{
        $.ajax({
            url: '../assets/data/Controller/marketing/marketingControl.php',
            type: "POST",
            data: {action:'generar_corte', ejecutiva:$("#ejecutiva_id").val(), periodo:$("#select_periodo_comision").val()},
            beforeSend: function(){
                
            },
            success: function(data){
                try{
                    var generar_corte = JSON.parse(data);
                    if(generar_corte.estatus > 0){
                        swal({
                            icon:'success',
                            text:'Corte generado correctamente.'
                        }).then( ()=>{
                            $("#modal_comision").modal('hide');
                        });
                    }else{
                        swal({
                            icon:'info',
                            text:'Error al generar el corte.'
                        });
                    }
                    init_data();
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            }
        });
    })
});