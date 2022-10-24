$(document).ready(function(){
    $("#tmp_subm")[0].reset();
    $("#idusuarioidevento").focus()
})
$("#asistencia-precongreso").on('submit', function(e){
    e.preventDefault();
    // console.log(e.which)
    fdata = new FormData(this)
    fdata.append('action', 'check-congreso');
    fdata.append('eventoid', eventoid);
    $.ajax({
        url: '../assets/data/Controller/eventos/talleresControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#loader").css("display", "block")
        },
        success: function(data){
            try{
                resp = JSON.parse(data)
                // console.log(resp)
                if(resp.hasOwnProperty('info')){
                    swal({
                        icon:'info',
                        text:resp.info
                    })
                }else{
                    $("#talleres_selec_c").css('display', 'flex')
                    $("#nombreasistente").val(resp.persona.nombre+' '+resp.persona.aPaterno+' '+resp.persona.aMaterno);
                    /*
					foto  = ''
                    if(resp.persona.foto == ''){
                        foto = 'defaultfoto.jpg'
                    }else{
                        foto = resp.persona.foto
                    }
                    var http = new XMLHttpRequest();
                    http.open('GET', '../'+resp.persona.instituciones[0].panel_url+'/app/img/afiliados/'+foto, false);
                    http.send();
                    
                    var foto = '';
                    if(http.status != 404){
                        foto  = '../'+resp.persona.instituciones[0].panel_url+'/app/img/afiliados/'+resp.persona.foto;
                    }else{
                        foto = `../${resp.persona.instituciones[0].panel_url}/app/img/afiliados/defaultfoto.jpg`;
                    }
                    $("#fotoasistente").attr('src', foto)*/

                    if(resp.hasOwnProperty('evento')){
                        ic = ''
                        if(!resp.evento.acceso){
                            ic='warning'
                        }else{
                            ic='success'
                        }
                        swal({
                            icon:ic,
                            title:resp.evento.mensaje
                        }).then((val)=>{
                            $("#cantidadpagada").val(resp.evento.pagado)
							$("#idusuarioidevento").focus()
                        })
                        // $("#tituloevento").val(resp.evento.titulo)
                        console.log(resp.evento.pagado)
                    }else{
                        $("#cantidadpagada").val(resp.pagos_general)
                        $("#tituloevento").parent().parent().css('display', 'none');
                    }

                    if(resp.hasOwnProperty('taller')){
                        if(!resp.taller.acceso){
                            swal({
                                icon:'warning',
                                title:resp.taller.mensaje
                            })
                        }else{
                            swal({
                                icon:'success',
                                title:resp.taller.mensaje
                            })
                        }
                        $("#tituloevento").val(resp.taller.nombre)
                        $("#cantidadpagada").val(resp.taller.pagado)
                    }else{
                        $("#cantidadpagada").val(resp.pagos_general)
                        $("#tituloevento").parent().parent().css('display', 'none');
                    }

                    if(resp.persona.instituciones.length > 0){
                        $("#badge-class").css('background-color', resp.persona.instituciones[0].color_n1)
                    }
                    $("#talleres_selec").html(resp.talleres.reduce( (acc, it) => {
                        var salon = ''
                        var fecha = ''
                        if(it.fecha_tll && it.fecha_tll != ''){
                            fecha = it.fecha_tll.split(' ');
                            var fch = fecha[0].split('-');
                            fecha = ` ${meses[fch[1]-1]} ${fch[2]} [${fecha[1].substring(0, 5)}]`
                        }
                        if(it.salon != ''){
                            salon = `<span class="float-right"><i class="fa fa-street-view"></i> <b>Salón: </b>${it.salon}. <i>${fecha}</i></span>`
                        }
                        return acc+=`<div class="card bg-light px-3"><p>${it.nombre} ${salon}</p></div>`;
                    } , ''))
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
        },
        complete: function(){
            $("#loader").css("display", "none")
            $("#idusuarioidevento").val('')
            $("#idusuarioidevento").focus()

        }
    });
})

/*$("#idusuarioidevento").on('keyup', function(kw){
    if(kw.which == 13){

    }
})*/
// $("#idusuarioidevento").on('change', function(kw){
//     console.log(kw)
// })
