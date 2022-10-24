function cargar_transportes(){
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:'consultar_solicitud_match', alumno:user_info.id_prospecto},
        success: function (response) {
            try{
                resp = JSON.parse(response)
                if(resp.enviadas.length > 0){
                    preg_rechazo = Boolean(resp.enviadas.find(elm => elm.numero_asiento == 'rechazo_transporte'))
                    preg_confirmo = Boolean(resp.enviadas.find(elm => elm.transporte == '0' || parseInt(elm.transporte) > 0))
                    if(!preg_rechazo && !preg_confirmo){
                        $("#layout-transporte").css('display','block')
                    }else{
                        $("#layout-transporte").css('display','none')
                    }

                    transporte_asignado = resp.enviadas.find(elm => parseInt(elm.transporte) != 0 && elm.transporte != null)
                    if(Boolean(transporte_asignado)){
                        info_qr = {};
                        info_qr.id_transporte = transporte_asignado.transporte
                        info_qr.numero_asiento = transporte_asignado.numero_asiento
                        info_qr.alumno = transporte_asignado.id_usuario
                        info_qr.alumno_matricula = transporte_asignado.solicitante_matricula

                        $("#lbl_transporte").html(transporte_asignado.transporte)
                        $("#lbl_asiento").html(transporte_asignado.numero_asiento)
                        $("#text").val(JSON.stringify(info_qr))
                        makeCode()

                        $("#content_qr_transporte").css('display','block')
                    }
                    console.log (Boolean(transporte_asignado))
                }else{
                    $("#layout-transporte").css('display','block')
                }
                console.log(resp)
            }catch(e){
                console.log(e)
                console.log(response)
            }
        }
    });
}

$("#form-solicitar-transporte").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'solicitud_transporte');
    
    $.ajax({
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            
        },
        success: function(data){
            try{
                resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
                    swal({
                        icon:'success',
                        text:'Su información ha sido recibida'
                    })
                }else{
                    swal({
                        icon:'info',
                        text:'Ha ocurrido un error. intente mas tarde'
                    })
                }
                cargar_transportes()
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
})

function cargar_alimentos(){
    $.ajax({
        type: "POST",
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        data: {action:'consultar_solicitud_match', alumno:user_info.id_prospecto},
        success: function (response) {
            try{
                resp = JSON.parse(response)
                if(resp.enviadas.length > 0){
                    preg_cena = Boolean(resp.enviadas.find(elm => elm.transporte == '0' || parseInt(elm.transporte) > 0))
                    
                    if(resp.enviadas[0].comida == 0){
                        $("#container_from_alimentos").css('display','block')
                    }else{
                        $("#radio_comida").attr('disabled','true')
                    } 
                    
                    if(resp.enviadas[0].cena == 0){
                        $("#container_from_alimentos").css('display','block')
                    }else{
                        $("#radio_cena").css('disabled','true')
                    }

                    if(resp.enviadas[0].comida != 0 && resp.enviadas[0].cena != 0){
                        $("#container_from_alimentos").css('display','none')
                        content_qr = {};
                        content_qr.solicitante = resp.enviadas[0].id_usuario
                        content_qr.comida = (parseInt(resp.enviadas[0].comida) <= 0)? 'no' : 'si';
                        content_qr.cena = (parseInt(resp.enviadas[0].cena) <= 0)? 'no' : 'si';
                        $("#text").val(JSON.stringify(content_qr))
                        makeCode()
                        $("#lbl_comida").html((parseInt(resp.enviadas[0].comida) <= 0)? '' : '1 Comida');
                        $("#lbl_cena").html((parseInt(resp.enviadas[0].cena) <= 0)? '' : '1 Cena');
                        $("#content_qr_comida").css('display','block')
                    }

                    // transporte_asignado = resp.enviadas.find(elm => parseInt(elm.transporte) != 0 && elm.transporte != null)
                    // if(Boolean(transporte_asignado)){
                    //     info_qr = {};
                    //     info_qr.id_transporte = transporte_asignado.transporte
                    //     info_qr.numero_asiento = transporte_asignado.numero_asiento
                    //     info_qr.alumno = transporte_asignado.id_usuario
                    //     info_qr.alumno_matricula = transporte_asignado.solicitante_matricula

                    //     $("#lbl_transporte").html(transporte_asignado.transporte)
                    //     $("#lbl_asiento").html(transporte_asignado.numero_asiento)
                    //     $("#text").val(JSON.stringify(info_qr))
                    //     makeCode()

                    //     $("#content_qr_transporte").css('display','block')
                    // }
                    // console.log (Boolean(transporte_asignado))
                }else{
                    $("#container_from_alimentos").css('display','block')
                }
                console.log(resp)
            }catch(e){
                console.log(e)
                console.log(response)
            }
        }
    });
}

$("#form_alimentos").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'solicitud_alimentos');
    
    $.ajax({
        url: "../../assets/data/Controller/hoteles/hotelControl.php",
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            
        },
        success: function(data){
            try{
                resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
                    swal({
                        icon:'success',
                        text:'Su información ha sido recibida'
                    })
                }else{
                    swal({
                        icon:'info',
                        text:'Ha ocurrido un error. intente mas tarde'
                    })
                }
                cargar_alimentos()
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
})