// document.addEventListener('touchstart', handler, {passive: true});
$(document).ready(function(){
    init_datos();
})

function init_datos(){
    solicitudes_match();
}

function solicitudes_match(){
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:'consultar_solicitud_match', alumno:user_info.id_prospecto},
        success: function (response) {
            try{
                resp = JSON.parse(response)
                //verificar si ha hecho solicitudes
                solicitar = false;

                if(resp.enviadas.length > 0){
                    canceladas = resp.enviadas.reduce( (acc, itm) =>{return (itm.id_companiero === null || itm.id_companiero == 0)? true : acc }, false)
                    reservacion_cancelada = resp.enviadas.reduce( (acc, itm) =>{return (itm.estatus == '2')? true : acc }, false)
                    // solicitud_cero = resp.enviadas.reduce( (acc, itm) =>{return (itm.id == '2')? true : acc }, false)
                    if(canceladas && !reservacion_cancelada){
                        solicitar = true;
                        if($("#form-solicitar-reservacion").length == 0){
                            $("#solicitar_reservacion").html(solicitar_html_f);
                        }
                    }else{
                        solicitar = false;
                        $("#form-solicitar-reservacion").parent().remove();
                    }
                }else{
                    $("#solicitar_reservacion").html(solicitar_html_f);
                }
                
                html_sol = "";
                if(resp.recibidas.length > 0){
                    $("#form-solicitar-reservacion").parent().remove();
                    for (i = 0; i < resp.recibidas.length; i++) {
                        solicitud = resp.recibidas[i];
                        if(solicitud.match_comp == '0' && solicitud.estatus == '0'){
                            $("#list_solicitudes").parent().css("display","block")
                            html_sol+=`<div class="row mg-b-25 mt-3">
                                        <div class="col-lg-4">
                                        <div class="form-group mg-b-10-force">
                                            <label class="form-control-label">Compartir con: </label>
                                            <input class="form-control" type="text" title="${solicitud.solicitante}" value="${solicitud.solicitante_matricula}" readonly="true">
                                        </div>
                                        </div>
                        
                                    </div><!-- row -->
                                    <div class="form-layout-footer">
                                        <button class="btn btn-info" onclick="aprobar(${solicitud.id})">Aceptar</button>
                                        <button class="btn btn-secondary" onclick="rechazar(${solicitud.id})">Rechazar</button>
                                    </div>`
                        }
                    }
                }else{
                    $("#list_solicitudes").parent().css("display","none");
                }
                
                $("#list_solicitudes").html(html_sol)
                /*else{
                    $("#list_solicitudes").parent().css("display","none")
                    if($("#form-solicitar-reservacion").length == 0){
                        $("#solicitar_reservacion").html(solicitar_html_f);
                    }
                }
                */

                if(html_sol == ""){
                    $("#list_solicitudes").parent().css("display","none")
                }

                if(resp.enviadas[0].id_hotel > 0){
                    $("#content_hotel_info").css('display','block');
                    $("#lbl_habitacion").html(resp.enviadas[0].habitacion)
                }

                console.log(resp)
            }catch(e){
                console.log(e)
                console.log(response)
            }
        }
    });
}

function aprobar(id_solicitud){
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:'aprobar_solicitud_match', solicitud:id_solicitud},
        success: function (response) {
            try {
                resp = JSON.parse(response)
                if(resp.estatus == 'ok'){
                    swal({icon:'success',text:'Confirmación realizada!'});
                }else{
                    swal({icon:'info',text:resp.info});
                }
                init_datos();
                // console.log(resp)
            } catch (error) {
                console.log(error);
                console.log(response);
            }
        }
    });
}

function rechazar(id_solicitud){
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:'rechazar_solicitud_match', solicitud:id_solicitud},
        success: function (response) {
            try {
                resp = JSON.parse(response)
                if(resp.estatus == 'ok'){
                    swal({icon:'success',text:'Solicitud rechazada.'});
                }else{
                    swal({icon:'info',text:resp.info});
                }
                init_datos();
                console.log(resp)
            } catch (error) {
                console.log(error);
                console.log(response);
            }
        }
    });
}

function solicitar_match(){
    message = '';
    if($("input[type='radio']:checked").val() == 'no'){
        message = 'Desea omitir la reservación de hotel?';
    }else if($("input[type='radio']:checked").val() == 'si'){
        if($("#matricula_companiero").val().trim() == ''){
            swal({
                icon:'info',
                text:'Debe ingresar una matricula para seleccionar un compañero de habitación'
            })
        }else{
            message = `Desea reservar habitación con el alumno con matricula ${$("#matricula_companiero").val().trim()}?`;
        }
    }

    if(message != ''){
        swal({
            icon:'info',
            text:message,
            buttons: ["Cancelar", "Confirmar"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                fdata = {action:'solicitar_match', solicita:$("input[type='radio']:checked").val(), matricula:$("#matricula_companiero").val()};
                // if()
                $.ajax({
                    url: '../../assets/data/Controller/hoteles/hotelControl.php',
                    type: "POST",
                    data: fdata,
                    beforeSend : function(){
                        $("#loader").css("display", "block")
                    },
                    success: function(data){
                        try{
                            resp = JSON.parse(data);
                            if(resp.estatus == 'ok'){
                                swal({
                                    icon:'success',
                                    text:'Tu solicitud ha sido enviada exitosamente, espera confirmación.'
                                });
                                validarEstatusRes(resp.data,"id_companiero");
                            }else{
                                swal({icon:'info', text:resp.info})
                            }
                            init_datos();
                            console.log(resp)
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
          })
    }
}

let solicitar_html_f = `<div class="form-layout form-layout-1">
    <div id="form-solicitar-reservacion">
        <p class="mg-b-0">Reserva habitación</p>
        <div class="row mg-b-25">
        <div class="col-lg-4">
            <div class="form-group">
            <label class="rdiobox"><input name="radio_reservar" type="radio" value="si" onclick="select_reserv(this)" checked><span>Si</span></label>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
            <label class="rdiobox"><input name="radio_reservar" type="radio" value="no" onclick="select_reserv(this)"><span>No</span></label>
            </div>
        </div>
        <div class="col-lg-4" id="content_matricula_input">
            <div class="form-group mg-b-10-force">
            <label class="form-control-label">Reservar con: </label><input class="form-control" type="text" id="matricula_companiero" name="matricula_companiero" placeholder="Ingresar número matricula">
            </div>
        </div>
        </div>
        <div class="form-layout-footer">
        <button class="btn btn-info" onclick="solicitar_match()">Enviar</button>    <button class="btn btn-secondary">Cancelar</button>
        </div>
    </div>
</div>`;

function select_reserv(element){
    if($(element).val() == 'si'){
        $("#content_matricula_input").fadeIn();
    }else{
        $("#content_matricula_input").fadeOut();
    }
}

function validarEstatusRes(idRes,selects){
    console.log("verificacion")
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action: "validarRes", idreservacion: idRes, selects: selects},
        dataType: "JSON",
        success: function (response) {
            console.log(response);
        }
    });
}