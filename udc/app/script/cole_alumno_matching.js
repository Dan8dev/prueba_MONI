// document.addEventListener('touchstart', handler, {passive: true});
$(document).ready(function(){
    ConsultarCortesiasDisponibles();
});
function ConsultarCortesiasDisponibles(){
    var id_afiliado = $("#Usuario").val();
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action: "ConsultarCortesiasDisponibles", id_afi: id_afiliado},
        complete: function(e){
            solicitudes_match();
            cargar_alimentos($("#idcortesiaAlim").val());
            cargar_transportes($("#idcortesiaTransp").val());
        },
        success: function (data) {
            var idHospedaje = "";
            $.each(data, function(key,registro){
                var title = ``;
                var form = ``;
                var idForm = `form_invalid`;
                var caso = "";
                switch(registro.typecort){
                    case '0':
                        title = "<img src='img/visitas/hotel.png' > Hospedaje";
                        form = `
                            <div class="p-3 d-none" id="solicitar_reservacion">
                                <b>En caso de no requerirlo no hace falta enviar ni guardar la solicitud.</b>
                                <div class= "form-group">
                                    <div id="form-solicitar-reservacion">
                                        <p class="">Reserva habitación</p>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="rdiobox"><input name="radio_reservar" type="radio" value="si" onclick="select_reserv(this)" checked disabled><span>Si</span></label>
                                            </div>

                                            <div class="col-md-4 d-none">
                                                <label class="rdiobox"><input name="radio_reservar" type="radio" value="no" onclick="select_reserv(this)"><span>No</span></label>
                                            </div>

                                            <div class="col-md-4" id="content_matricula_input">
                                                <b>En caso de no contar con matrícula para match comuniquese con Cobranza</b><br>
                                                <label class="form-control-label">Reservar con: </label><input class="form-control" type="text" id="matricula_companiero" name="matricula_companiero" placeholder="Ingresar número matrícula">
                                            </div>
                                        </div>
                                        <input class = "d-none" type="text" id= "idcortesiares" value = '${registro.idcortesia}' name="idcortesia">
                                        <div class="form-layout-footer">
                                            <button type = "button" class="btn btn-info" onclick = "solicitar_match()">Enviar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div  class="form-layout form-layout-1 mg-t-25" id="list_solicitudes">
                                
                            </div>
                            <div id="content_hotel_info" class="d-none"> 
                                <b id = "lbl_habitacion"><h3>Reservacion Verificada.</h3>
                                </b>
                            </div>`;
                        break;
                    case '1':
                        title = " <img src='img/visitas/transporte.png' > Transporte";
                        form = `
                            <div class="p-3" id="layout-transporte" >
                                <form id="form-solicitar-transporte">
                                    <b>En caso de no requerirlo no hace falta enviar ni guardar la solicitud.</b>
                                    <p class="mg-b-0">Reserva transporte</p>
                                    <div class="row mg-b-25">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label class="rdiobox">
                                                <input name="radio_reserv_transporte" value="si" type="radio" checked disabled>
                                                <span>Si</span>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 d-none">
                                            <div class="form-group">
                                                <label class="rdiobox">
                                                    <input name="radio_reserv_transporte" value="no" type="radio" >
                                                    <span>No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type = "text" class = 'd-none' value = '${registro.idcortesia}' name = 'idcortesia' id = "idcortesiaTransp"></input>
                                    <div class="form-layout-footer">
                                        <button class="btn btn-info" type="submit">Guardar</button>
                                    </div>
                                </form>
                            </div>
                            <b><span id="lbl_transporte"></span></b>
                            <b><span id="lbl_asiento"></span></b>`;
                            idForm = "form-solicitar-transporte";
                            caso = 'solicitud_transporte';
                        break;
                    case '2':
                        title = " <img src='img/visitas/comida.png' > Alimentos";
                        form = `
                            <b id="lbl_comida"></b> <br>
                            <b id="lbl_cena"></b>
                            <div class="p-3" id="container_from_alimentos">
                                <p class="mg-b-0">Selecciona</p>
                                <form id="form_alimentos">
                                    <div class="row mg-b-25">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label class="ckbox">
                                                <input name="radio_comida" id="radio_comida" type="checkbox">
                                                <span>Comida</span>
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="ckbox">
                                                    <input name="radio_cena" id="radio_cena" type="checkbox">
                                                    <span>Cena</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type = "text" class = 'd-none' value = '${registro.idcortesia}' name = 'idcortesia' id = "idcortesiaAlim"></input>
                                    <div class="form-layout-footer">
                                        <button type = "submit" class="btn btn-info">Guardar</button>
                                    </div>
                                </form>
                            </div>`;
                            idForm = "form_alimentos";
                            caso = 'solicitud_alimentos';
                        break;
                }
                $("#cortesiasAlumnmo").append(`
                    <div class="visitastile card p-4 row justify-content-center  mt-3">
                        <div class="card-title col-md-12 clave alert alert-info text-center uppercase">
                            <h3>${title}</h3>
                        </div>
                        <div class="col-md-12 clave alert text-center text-primary uppercase">
                        <h5>${registro.nombre}</h5>
                        </div>
                        <div class="card-body col-md-12">
                            <label for="${registro.informacion}"><b>${registro.informacion}</b></label>
                        </div>
                        <div class="col-md-12">
                            ${form}
                        </div>
                    </div>
                `);
                funcionalform(idForm, caso, registro.idcortesia);
            });
            if(idHospedaje != 0){
                $("#idcortesiares").val(idHospedaje);
            }
            if(data.length==0){
                $("#cortesiasAlumnmo").append(`<h3 class = "text-center mt-3">Aun no cuentas con cortesias asignadas</h3>`);
            }
        }
    });
}

function funcionalform(id_Form, caso, idcortesia){
    $("#"+id_Form).on('submit', function(e){
        e.preventDefault();
        fdata = new FormData(this)
        fdata.append('action', caso);
        
        $.ajax({
            url: "../../assets/data/Controller/hoteles/hotelControl.php",
            type: "POST",
            data: fdata,
            contentType:false,
            processData:false,
            success: function(data){
                try{
                    resp = JSON.parse(data);
                    if(resp.estatus == 'ok'){
                        swal({
                            icon:'success',
                            text:'Solicitud enviada, espere respuesta'
                        }).then(result=>{
                            switch(caso){
                                case 'solicitud_alimentos':
                                    cargar_alimentos(idcortesia);
                                    break;
                                case 'solicitud_transporte':
                                    cargar_transportes(idcortesia);
                                    break;
                            }
                        });
                    }else{
                        swal({
                            icon:'info',
                            text:'Ha ocurrido un error. intente mas tarde'
                        })
                    }
                    //cargar_alimentos()
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
            },
            complete: function(){
                
            }
        });
    });
}


function solicitudes_match(){
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        //:::
        data: {action:'consultar_solicitud_match', alumno:user_info.id_prospecto, idcortesia: $("#idcortesiares").val()},
        dataType: "JSON",
        success: function (resp) {
            try{
                console.log(resp);
                //verificar si ha hecho solicitudes
                solicitar = false;

                if(resp.enviadas.length > 0){
                    canceladas = resp.enviadas.reduce( (acc, itm) =>{return (itm.id_companiero === null || itm.id_companiero == 0)? true : acc }, false)
                    reservacion_cancelada = resp.enviadas.reduce( (acc, itm) =>{return (itm.estatus == '2')? true : acc }, false)
                    // solicitud_cero = resp.enviadas.reduce( (acc, itm) =>{return (itm.id == '2')? true : acc }, false)
                    if(canceladas && !reservacion_cancelada){
                        solicitar = true;
                        $("#solicitar_reservacion").removeClass("d-none");
                    }else{
                        solicitar = false;
                        $("#solicitar_reservacion").removeClass("d-none");
                        //$("#form-solicitar-reservacion").parent().remove();
                        $("#solicitar_reservacion").html('<h5>Espere a que su solicitud sea confirmada</h5>');
                    }
                }else{
                    $("#solicitar_reservacion").removeClass("d-none");
                    $("#form-solicitar-reservacion").removeClass("d-none");
                }
                
                html_sol = "";
                if(resp.recibidas.length > 0){
                    $("#form-solicitar-reservacion").addClass("d-none");
                    html_sol = ``;
                    for (i = 0; i < resp.recibidas.length; i++) {
                        solicitud = resp.recibidas[i];
                        if(solicitud.match_comp == '0' && solicitud.estatus == '0'){
                            $("#list_solicitudes").parent().css("display","block");
                            html_sol+=`
                            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-t-20 mg-b-10">Solicitud para compartir habitación</h6>
                                <div class="row mg-b-25 mt-3">
                                    <div class="col-lg-4">
                                        <div class="form-group mg-b-10-force">
                                            <label class="form-control-label">Compartir con: </label>
                                            <input class="form-control" type="text" title="${solicitud.solicitante}" value="${solicitud.solicitante}" readonly="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-layout-footer">
                                    <button class="btn btn-info" onclick="aprobar(${solicitud.id})">Aceptar</button>
                                    <button class="btn btn-secondary" onclick="rechazar(${solicitud.id})">Rechazar</button>
                                </div>`;
                        }
                    }
                    $("#list_solicitudes").append(html_sol);
                }
                
                if(html_sol == ""){
                    $("#list_solicitudes").addClass("d-none");
                }else{
                    $("#list_solicitudes").removeClass("d-none");
                }

                if(resp.enviadas.length > 0 && resp.enviadas[0].id_hotel > 0){
                    $("#solicitar_reservacion").addClass("d-none");
                    $("#content_hotel_info").removeClass('d-none');
                    $("#lbl_habitacion").append("Hotel: " + resp.enviadas[0].NombreHotel);
                    $("#lbl_habitacion").append("<br>Dirección: " + resp.enviadas[0].direccion);
                    $("#lbl_habitacion").append("<br>Habitacion: " + resp.enviadas[0].habitacion);
                }
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
        data: {action:'aprobar_solicitud_match', solicitud:id_solicitud, idcortesia: $("#idcortesiares").val()},
        success: function (response) {
            try {
                resp = JSON.parse(response)
                if(resp.estatus == 'ok'){
                    swal({icon:'success',text:'¡Confirmación realizada!'}).then(result =>{
                        $("#list_solicitudes").html('<h5>Espere a que su solicitud sea confirmada</h5>');
                    });
                }else{
                    swal({icon:'info',text:resp.info});
                }
                //init_datos();
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
                //init_datos();
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
                fdata = {action:'solicitar_match', solicita:$("input[type='radio']:checked").val(), matricula:$("#matricula_companiero").val(), idcortesia: $("#idcortesiares").val()};
                // if()
                $.ajax({
                    url: '../../assets/data/Controller/hoteles/hotelControl.php',
                    type: "POST",
                    data: fdata,
                    success: function(data){
                        try{
                            resp = JSON.parse(data);
                            if(resp.estatus == 'ok'){
                                swal({
                                    icon:'success',
                                    text:'Tu solicitud ha sido enviada exitosamente, espera confirmación.'
                                }).then(result => {
                                    $("#form-solicitar-reservacion").parent().remove();
                                    $("#solicitar_reservacion").html('<h5>Espere a que su solicitud sea confirmada</h5>');
                                    $("#matricula_companiero").prop("disabled",true);
                                });
                                validarEstatusRes(resp.data,"id_companiero");
                            }else{
                                swal({icon:'info', text:resp.info})
                            }
                            //init_datos();
                            console.log(resp)
                        }catch(e){
                            console.log(e);
                            console.log(data);
                        }
                    },
                    error: function(){
                    }
                });
            }
        })
    }
}



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


function cargar_transportes(idcortesia){
    //console.log(idcortesia); 
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:'consultar_solicitud_match', alumno:user_info.id_prospecto, idcortesia: idcortesia},
        dataType: "JSON",
        success: function (resp) {
            try{
                // console.log(response.enviadas);
                if(resp.enviadas != undefined && resp.enviadas.length > 0){
                    preg_rechazo = Boolean(resp.enviadas.find(elm => elm.numero_asiento == 'rechazo_transporte'))
                    preg_confirmo = Boolean(resp.enviadas.find(elm => elm.transporte == '0' || parseInt(elm.transporte) > 0))
                    if(!preg_rechazo && !preg_confirmo){
                        $("#layout-transporte").removeClass('d-none');
                    }else{
                        $("#layout-transporte").addClass('d-none');
                    }

                    transporte_asignado = resp.enviadas.find(elm => parseInt(elm.transporte) >= 0 && elm.transporte != null);
                    //console.log(transporte_asignado);
                    if(Boolean(transporte_asignado)){
                        if(transporte_asignado.transporte == 0){
                            $("#layout-transporte").html('<b>Solicitud de Transporte enviada, Espere confirmación.</b> <br>');
                            $("#layout-transporte").removeClass('d-none');
                        }else{
                            $("#lbl_transporte").html("<h3>Reservacion Verificada.</h3>");
                            $("#lbl_asiento").append("Transporte: " + transporte_asignado.NombreTransp + "<br> Asiento: " + transporte_asignado.numero_asiento);
                        }
                        // info_qr = {};
                        // info_qr.id_transporte = transporte_asignado.transporte
                        // info_qr.numero_asiento = transporte_asignado.numero_asiento
                        // info_qr.alumno = transporte_asignado.id_usuario
                        // info_qr.alumno_matricula = transporte_asignado.solicitante_matricula
                        // console.log(info_qr);
                        // $("#text_tr").val(JSON.stringify(info_qr))
                        // makeCode()

                        //$("#content_qr_transporte").css('display','block')
                    }else{
                        
                        $("#layout-transporte").removeClass('d-none');
                    }
                    //console.log (Boolean(transporte_asignado))
                }else{
                    //:::
                    $("#layout-transporte").css('display','block')
                }
                //console.log(resp)
            }catch(e){
                console.log(e)
                console.log(response)
            }
        }
    });
}




function cargar_alimentos(idcortesia){
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:'consultar_solicitud_match', alumno:user_info.id_prospecto, idcortesia: idcortesia},
        dataType: "JSON",
        success: function (resp) {
            try{
                console.log(resp.enviadas);
                if(resp.enviadas != undefined && resp.enviadas.length > 0){
                    //preg_cena = Boolean(resp.enviadas.find(elm => elm.transporte == '0' || parseInt(elm.transporte) > 0))
                    if(resp.enviadas[0].comida == null){
                        $("#container_from_alimentos").css('display','block')
                    }else{
                        $("#radio_comida").attr('disabled','true')
                    } 
                    
                    if(resp.enviadas[0].cena == null){
                        $("#container_from_alimentos").css('display','block')
                    }else{
                        $("#radio_cena").css('disabled','true')
                    }

                    if(resp.enviadas[0].comida != null && resp.enviadas[0].cena != null){
                        $("#container_from_alimentos").css('display','none')
                        // content_qr = {};
                        // content_qr.solicitante = resp.enviadas[0].id_usuario
                        // content_qr.comida = (parseInt(resp.enviadas[0].comida) <= 0)? 'no' : 'si';
                        // content_qr.cena = (parseInt(resp.enviadas[0].cena) <= 0)? 'no' : 'si';
                        // $("#text").val(JSON.stringify(content_qr))
                        //makeCode()

                        if(resp.enviadas[0].comida != 2 && resp.enviadas[0].cena != 2){
                            $("#lbl_comida").append("<br><b>Espere la aprobación de su solicitud</b><br>");
                        }else{
                            $("#lbl_comida").append("<h3>Reservación Verificada.</h3>");
                        }

                        $("#lbl_comida").append((parseInt(resp.enviadas[0].comida) >= 1) ? '1 Comida' : 'Comida no solicitada');
                        $("#lbl_cena").append((parseInt(resp.enviadas[0].cena) >= 1) ? '1 Cena' : 'Cena no solicitada');
                        //$("#content_qr_comida").css('display','block')
                    }

                    // transporte_asignado = resp.enviadas.find(elm => parseInt(elm.transporte) != 0 && elm.transporte != null)
                    // if(Boolean(transporte_asignado)){
                    //     info_qr = {};
                    //     info_qr.id_transporte = transporte_asignado.transporte
                    //     info_qr.numero_asiento = transporte_asignado.numero_asiento
                    //     info_qr.alumno = transporte_asignado.id_usuario
                    //     info_qr.alumno_matricula = transporte_asignado.solicitante_matricula

                        //$("#lbl_transporte").html(transporte_asignado.transporte)
                        //$("#lbl_asiento").html(transporte_asignado.numero_asiento)
                    //     $("#text").val(JSON.stringify(info_qr))
                    //     makeCode()

                    //     $("#content_qr_transporte").css('display','block')
                    // }
                    // console.log (Boolean(transporte_asignado))
                }else{
                    $("#container_from_alimentos").css('display','block')
                }
                //console.log(resp)
            }catch(e){
                console.log(e)
                console.log(response)
            }
        }
    });
}


