$( document ).ready(function() {
    var idUser = $("#idAfiliado").val();
    cargar_cursos_pagos(idUser);
    //console.log( "ready!",idUser);
    $.ajax({
        url: '../../udc/app/data/CData/identidadControl.php',
        type: 'POST',
        data: {
            action: 'obtenerSolicitudesCredencial',
            idusuario: idUser
        },
        success : function(data){

            console.log(data);
            try{
                pr = JSON.parse(data);
                //console.log(pr.data.estatus);
                var texto = "Envia tu fotografia, espera la autorización y solicita tu credencial ";
                if(pr.data==false){
                    $('#ContenedorCredenciales').addClass('d-none');
                    $('#ContenedorSolicitudCredenciales').removeClass('d-none');
                    $('BotonSolicitudCredencial').prop('disabled',false);
                }else{
                    $('#ContenedorSolicitudCredenciales').addClass('d-none');
                    switch(pr.data.estatus){
                        case '1':
                            texto = '<b>Credencial Solicitada, en espera de respuesta.</b>';
                            break;
                        case '2':
                            texto = '<span class="text-success">Descarga Autorizada.</span>';
                            $('#ContenedorCredenciales').removeClass('d-none');
                            break;
                        case '3':
                            texto = '<b>Descarga Finalizada.</b>';
                            $('#ContenedorSolicitudCredenciales').removeClass('d-none');
                            break;
                        case '4':
                            texto = '<span class="text-primary">Solicitud Rechazada.</span>';
                            $('#ContenedorSolicitudCredenciales').removeClass('d-none');
                            break;
                    }
                }
                
                $('#AvisoEstatus').append(texto);
                //$('#estatusSolicitud').text(texto);
                //console.log(texto);
            }catch(e){
                console.log(e)
                console.log(data)
            }

        }
    });

});

$('#BotonSolicitudCredencial').on('click',function(e){
    $('#CursosCredencial').modal('show');

});

function SolcitarCredencial(usuario){
    Data = {
        action: 'obtenerCredencial',
        idusuario: usuario
    }
    $.ajax({
        url: '../../udc/app/data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.validacion==1){
                    var dir = usuario +'-credencial-conacon.pdf';
                    window.open('credenciales/'+dir,'_blank');
                }
                if(pr.validacion==0){
                    swal({
                        title: 'Fotografía aun sin validar.',
                        icon: 'info',
                        text: 'Espere a que el departamento de control escolar de una respuesta.',
                        button: false,
                        timer: 4000,
                    });
                }
                if(pr.validacion==2){
                    swal({
                        title: 'Fotografía rechazada.',
                        icon: 'info',
                        text: 'Por favor, sustituye la fotografía para el proceso de validación en la opción "Documentación".',
                        button: false,
                        timer: 4500,
                    });
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }

        }
    });
}

function cargar_cursos_pagos(idUser){ // consultar carreras con generaciones asignadas al alumno
	$.ajax({
		url: "../../udc/app/data/CData/materiasControl.php",
		type: "POST",
		data: {action:'pago_cursos'},
		beforeSend : function(){
			$("#loader").css("display", "block")
		},
		success: function(data){
			try{
				cursos_disp = JSON.parse(data);
				if(cursos_disp.estatus == 'ok'){
					html_c = "";
					
					for (i = 0; i < cursos_disp.data.length; i++) {
						var foto_g = '';

						if(cursos_disp.data[i].idInstitucion == 19){
						if(cursos_disp.data[i].imagen_generacion != ''){
							foto_g = `../../assets/images/generales/flyers/${cursos_disp.data[i].imagen_generacion}`
							var xhr = new XMLHttpRequest();
							xhr.open('HEAD', foto_g, false);
							xhr.send();
							if (xhr.status == "404") {
								foto_g = '../../assets/images/generales/flyers/default_curso_udc.png'
							}
						}else{
							foto_g = `../../assets/images/generales/flyers/default_curso_udc.png`
						}
						//mike
						var nombre_generacion = cursos_disp.data[i].nombre_generacion;
						var nombre_gene = nombre_generacion.split(' ');
						var nombre_gen = nombre_gene[0]+' '+nombre_gene[1];
						cardsVisibles(cursos_disp.data[i].idalumno, cursos_disp.data[i].idgeneracion, cursos_disp.data[i].nombre, nombre_gen, foto_g,idUser);
						}	
					}
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
		}
	});
}


function cardsVisibles(idAlumno, id, nombreCarrera, nombreGeneracion, foto,idUser){
    $.ajax({
        url: '../../udc/app/data/CData/materiasControl.php',
        type: 'POST',
        data: {action: 'tipoCarrera',
            id: id,
            idAlumno: idAlumno},
        success: function(data){
            /*if(data =='si'){
            html_c+=`<div class="col-sm-6 col-md-6 col-xl-4">
                        <div class="card mg-b-40">
                            <div class="card-body bg-primary">
                            <p class="card-text text-truncate text-white mb-0">${nombreCarrera}</p>
                                <small class="card-text text-truncate text-white text-sm">${nombreGeneracion}</small>
                            </div>
                            <a href="clases.php?curso=${id}"><img class="card-img-bottom img-fluid" src="${foto}" alt="Image"></a>
                        </div>
                    </div>`;
                    $("#cursos-container").html(html_c);
            }*/
            try{
                pr = JSON.parse(data);
                if(pr.tipo!='1'){
                    cardsVisiblesUDC(idAlumno, id, nombreCarrera, nombreGeneracion, foto,idUser);
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
    }


    function cardsVisiblesUDC(idAlumno, id, nombreCarrera, nombreGeneracion, foto,idUser){
        $.ajax({
            url: '../../udc/app/data/CData/materiasControl.php',
            type: 'POST',
            data: {action: 'validar_adeudos',
                id: id,
                idAlumno: idAlumno},
            success: function(data){
                
                if(data.trim() =='si'){
                html_c=`<div class="col-sm-6 col-md-6 col-xl-4">
                            <div class="card mg-b-40">
                                <div class="card-body bg-primary">
                                <p class="card-text text-truncate text-white mb-0">${nombreCarrera}</p>
                                    <small class="card-text text-truncate text-white text-sm">${nombreGeneracion}</small>
                                </div>
                                <button class = "btn btn-info form-control form-group" onClick="insertarSolicitudCred(${idUser},${id})">Elegir curso</button>
                            </div>
                        </div>`;
                        $("#cursos-container").append(html_c);
                }else{
                    var mensaje = '';
                    switch(data.trim()){
                        case 'no inscripcion':
                            mensaje = `<small>${nombreCarrera}</small><br>Su acceo se ha bloqueado por no contar con registro de pago de inscripción`;
                        break;
                        case 'no mensualidad':
                            mensaje = `<small>${nombreCarrera}</small><br>Su acceo se ha bloqueado por no contar con registro de pago de mensualidad`;
                        break;
                        case 'no reinscripcion':
                            mensaje = `<small>${nombreCarrera}</small><br>Su acceo se ha bloqueado por no contar con registro de pago de reinscripción`;
        
                        break;
                        case 'no documentos':
                            mensaje = `<small>${nombreCarrera}</small><br>Su acceo se ha bloqueado falta de entrega de documentos digitales`;
                        break;
                        case 'no documentos fisicos':
                            mensaje = `<small>${nombreCarrera}</small><br>Su acceo se ha bloqueado falta de entrega de documentos fisicos`;
                        break;
                    }
                    if(mensaje != ''){
                        $("#cursos-container").append(`<div class="alert alert-warning" style="max-height: 100px;" role="alert">
                            <strong class="d-block d-sm-inline-block-force"></strong> ${mensaje}.
                        </div>`);
                    }
                }
            }
        });
    }

function insertarSolicitudCred(idAlumno,idCurso){
    Data = {
        action: 'InsertarSolicitudCred',
        idusuario: idAlumno,
        idCur: idCurso
    }
    $.ajax({
        url: '../../udc/app/data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                pr = JSON.parse(data)
                
                if(pr.data==1){
                    swal({
                        title: 'Solicitud Añadida Correctamente',
                        icon: 'info',
                        text: 'Espere aprobacion de control escolar.',
                        button: false,
                        timer: 4500,
                    });
                    window.location.reload();
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }

        }
    });
}

function buscarDatosCredencial(usuario){
    Data = {
        action: 'obtenerCredencial',
        idusuario: usuario
    }
    $.ajax({
        url: '../../udc/app/data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                pr = JSON.parse(data)

                if(pr.validacion == 'Imagen no encontrada'){
                    swal({
                        title: 'Fotografia no encontrada',
                        icon: 'error',
                        text: 'Verifique que subio su fotografia infantil en documentación y se encuentre aprobada, si todo esta correcto contacte a Control Escolar.',
                        button: false,
                        timer: 5000,
                    });
                }

                if(pr.validacion==1){
                    var dir = usuario +'-credencial-conacon.pdf';
                    window.open('credenciales/'+dir,'_blank');
                }
                if(pr.validacion==0){
                    swal({
                        title: 'Fotografía aun sin validar.',
                        icon: 'info',
                        text: 'Espere a que el departamento de control escolar de una respuesta.',
                        button: false,
                        timer: 4000,
                    });
                }
                if(pr.validacion==2){
                    swal({
                        title: 'Fotografía rechazada.',
                        icon: 'info',
                        text: 'Por favor, sustituye la fotografía para el proceso de validación en la opción "Documentación".',
                        button: false,
                        timer: 4500,
                    });
                }

                if(pr.validacion == 'Falta de fotografia'){
                    swal({
                        title: 'Falta de fotografia.',
                        icon: 'error',
                        text: 'Por favor, sube la fotografía infantil para el proceso de validación en la opción "Documentación".',
                        button: false,
                        timer: 4500,
                    });
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }

        }
    });
}

function buscarDatosTarjeta(usuario){
    Data = {
        action: 'obtenerTarjeta',
        idusuario: usuario
    }
    $.ajax({
        url: '../../udc/app/data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                var dir = usuario+'-tarjeta-conacon.pdf';
                window.open('tarjetas/'+dir,'_blank');
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display","none")
        }
    });
}


function buscarAfiliacion(usuario){
    Data = {
        action: 'obtenerAfiliacion',
        idusuario: usuario
    }
    $.ajax({
        url: '../../udc/app/data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                var dir = usuario+'-tarjeta-afiliacion.pdf';
                window.open('tarjeta-afiliacion/'+dir,'_blank');
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display","none")
        }
    });
}
