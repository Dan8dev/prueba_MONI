$(document).ready(function () {
    init();
});

let list_v = [];
function init(){
    listar_afiliados();
    listar_vistas_noasign();
    listar_vistas();
}

function listar_afiliados(){
    $.ajax({
        url: '../assets/data/Controller/planpagos/vistasControl.php',
        type: 'POST',
        data: {action:'listar_afiliados'},
        success : function(data){
            try{
            resp = JSON.parse(data);

            if(resp.estatus == 'ok'){
                resp = resp.data;
                $("#table-afiliados").DataTable().clear();

                for (i = 0; i < resp.length; i++) {
                    $("#table-afiliados").DataTable().row.add([
                        `${resp[i].apaterno} ${resp[i].amaterno} ${resp[i].nombre}`,
                        `<button class="btn btn-primary" onclick="listar_vistas_prospecto(${resp[i].id_prospecto}, '${resp[i].amaterno} ${resp[i].nombre}')"><i class="fas fa-tasks"></i></button>`
                    ]);
                    
                }
                $("#table-afiliados").DataTable().draw();
                $("#table-afiliados").DataTable().columns.adjust();
            }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        complete : function(){

        }
    });
}

function listar_vistas_prospecto(prosp, nombre){
    $.ajax({
        url: '../assets/data/Controller/planpagos/vistasControl.php',
        type: 'POST',
        data: {action:'vistas_afiliado', prospecto:prosp},
        success : function(data){
            try{
                resp_p = JSON.parse(data);
                if(resp_p.estatus == 'ok'){
                    resp_p = resp_p.data;
                    $("input[type='checkbox']").prop('checked',false)

                    console.log(resp_p)
                    for (i = 0; i < resp_p.length; i++) {
                        console.log(">"+resp_p[i].vista+'_'+parseInt(resp_p[i].estatus))
                        if(parseInt(resp_p[i].estatus) == 1){
                            $("#check_vist_"+resp_p[i].vista).prop('checked',true)
                        }
                    }
                }
                $("#lbl_afiliado_accesos").html(nombre)

                $("#prospecto_vista_set").val(prosp);
                $("#button_asignar_vistas").attr('disabled',true);
                $("#modal_vistas_asignadas").modal('show')
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        complete : function(){

        }
    });
}

function listar_vistas_noasign(){
    $.ajax({
        url: '../assets/data/Controller/planpagos/vistasControl.php',
        type: 'POST',
        data: {action:'vistas_existentes',modulo:'siscon'},
        success : function(data){
            try{
                resp_v = JSON.parse(data);

                opc_v = "<option disabled selected> Selecciona una vista </option>";
                for (i = 0; i < resp_v.length; i++) {
                    opc_v+=`<option value='${resp_v[i]}'> ${resp_v[i]} </option>`
                }

                $(".select_direct").html(opc_v);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        complete : function(){

        }
    });
}

function listar_vistas(){
    $.ajax({
        url: '../assets/data/Controller/planpagos/vistasControl.php',
        type: 'POST',
        data: {action:'listar_vistas'},
        success : function(data){
            try{
                resp = JSON.parse(data);
                resp = resp.data

                $("#table-vistas-afiliados").find('tbody').children().remove()
                $("#table-vistas-exist").DataTable().clear();
                list_v = [];
                for (i = 0; i < resp.length; i++) {
                    list_v.push(resp[i])
                    $("#table-vistas-exist").DataTable().row.add([
                        `${resp[i].nombre}`,
                        `${resp[i].descripcion}`,
                        `<button class="btn btn-primary" onclick="editar_registro_vista(${resp[i].idVista})"><i class="fas fa-cogs"></i></button>`
                    ]);
                    $("#table-vistas-afiliados").find('tbody').append(`
                        <tr>
                            <td>${resp[i].nombre}</td>
                            <td><input type="checkbox" id="check_vist_${resp[i].idVista}" name="check_vist_${resp[i].idVista}" value="vist_${resp[i].idVista}"> <label for="check_vist_${resp[i].idVista}"> Habilitado ${resp[i].idVista}</label></td>
                        </tr>
                    `)
                }
                
                $("#table-vistas-exist").DataTable().draw();
                $("#table-vistas-exist").DataTable().columns.adjust();


                $("#form_acceso_vistas").find("input[type='checkbox']").on('change', function(){
                  if($("#button_asignar_vistas").attr('disabled')){
                    $("#button_asignar_vistas").attr('disabled', false);
                  }
                })
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        complete : function(){

        }
    });
}

function revisar_pagos(){
    $.ajax({
        url: '../assets/data/Controller/prospectos/prospectoControl.php',
        type: 'POST',
        data: {action:'consultar_pagos_prospecto', prospecto:prosp},
        success : function(data){
            resp_p = JSON.parse(data);
            console.log(resp_p)
            /*if(resp.estatus == 'ok'){
                resp = resp.data;
                $("#table-afiliados").DataTable().clear();
                for (i = 0; i < resp.length; i++) {
                    $("#table-afiliados").DataTable().row.add([
                        `${resp[i].apaterno} ${resp[i].amaterno} ${resp[i].nombre}`,
                        `<button class="btn btn-primary" onclick="listar_vistas(${resp[i].id_prospecto})"><i class="fas fa-tasks"></i></button>`
                        ]);
                }
                $("#table-afiliados").DataTable().draw();
                $("#table-afiliados").DataTable().columns.adjust();
            }*/
        },
        complete : function(){

        }
    });
}

$("#form_registrar_vista").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'registrar_vista');
    $.ajax({
        url: '../assets/data/Controller/planpagos/vistasControl.php',
        type: "POST",
        data: fData,
        contentType: false,
        processData:false,
        beforeSend : function(){
            $(".outerDiv_S").css("display", "block")
        },
        success: function(data){
            try{
                console.log(data)
                resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
                    swal({
                        icon:'success',
                        title:'Registrado con exito'
                    })
                }else{
                    swal({
                        icon:'info',
                        title: resp.info
                    })
                }
                init();
                $("#form_registrar_vista")[0].reset()
                $("#modal_registrar_vista").modal('hide')
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
});

function editar_registro_vista(vista){
    v = list_v.find( elm => elm.idVista == vista)
    $("option[tmp='true']").remove()

    if(v){
        $("#editar_vista_i").val(v.idVista)
        $("#select_edit_v").append(`<option selected tmp="true" value="${v.directorio}">${v.directorio}</option>`)
        $("#nombre_vista_edit").val(v.nombre)
        $("#descripcion_vista_edit").val(v.descripcion)
        $("#check_active_vist").prop('checked', Boolean(v.estatus))
    }else{
        $("#form_registrar_vista")[0].reset()
    }

    $("#modal_editar_registro_v").modal('show');
}

$("#form_actualizar_vista").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action','editar_registro')
    $.ajax({
        url: '../assets/data/Controller/planpagos/vistasControl.php',
        type: "POST",
        data: fData,
        contentType: false,
        processData:false,
        beforeSend : function(){
            $(".outerDiv_S").css("display", "block")
        },
        success: function(data){
            try{
                resp = JSON.parse(data)
                console.log(data)
                if(resp.estatus == 'ok'){
                    swal({
                        icon:'success',
                        title:'Actualizado con exito'
                    })
                }else{
                    swal({
                        icon:'info',
                        title: resp.info
                    })
                }
                init();
                $("#modal_editar_registro_v").modal('hide');
                $("#form_registrar_vista")[0].reset()
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
});

$("#form_acceso_vistas").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action','upd_vista_alumno')
    $.ajax({
        url: '../assets/data/Controller/planpagos/vistasControl.php',
        type: "POST",
        data: fData,
        contentType: false,
        processData:false,
        beforeSend : function(){
            $(".outerDiv_S").css("display", "block")
        },
        success: function(data){
            try{
                resp = JSON.parse(data)
                console.log(resp)
                if(resp.hasOwnProperty('estatus') && resp.estatus == 'error'){
                    swal({
                        icon:'info',
                        title:resp.info
                    })
                }else{
                    cambios = 0;
                    
                    cambios = (resp.deshabilitados > cambios)? resp.deshabilitados : cambios;
                    cambios = (resp.habilitados > cambios)? resp.habilitados : cambios;
                    cambios = (resp.nuevos > cambios)? resp.nuevos : cambios;
                    swal({
                        icon:'success',
                        title: cambios+" cambios aplicados"
                    })
                }
                init();
                $("#modal_vistas_asignadas").modal('hide');
                $("#form_acceso_vistas")[0].reset()
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
});