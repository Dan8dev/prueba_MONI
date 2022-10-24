list_clases = [];
let clave_cuso;

$(document).ready(function () {
	//console.log("Hello");
	Prueba();
	iniciarTablaProcesos();
	iniciarTablaProcesosRevision();
	iniciarTablaProcesosCorreccion();
	Verificacion();

});


function Verificacion(){
	var user = $("#numeroProspecto").val(); 
	$.ajax({
		url: "data/CData/servicioSocialControl.php",
		type: "POST",
		data: {action:'VerificarEstatusServicio',
			Usuario: user},
		
		success: function(data){
			try{
				documentos = JSON.parse(data);
				
				if(documentos.estatus == 'ok'){
					//console.log("Todo ok");
					console.log(documentos.data);
					switch(documentos.data.estatus){
						case '3':
							$("#enviar_formatos").addClass("active show");
							$("#F1").addClass("active");
							$("#C1").addClass("d-none");
							$("#D1").removeClass("active");
							$("#posts").removeClass("active show");
							$("#R1").addClass("d-none");
							$("#E1").addClass("d-none");
							break;
						case '4':
							$("#concluidos").addClass("active show");
							$("#F1").addClass("d-none");
							$("#C1").addClass("active");
							$("#D1").removeClass("active");
							$("#posts").removeClass("active show");
							$("#R1").addClass("d-none");
							$("#E1").addClass("d-none");
							break;
						default:
							$("#C1").addClass("d-none");
							$("#F1").addClass("d-none");;
							break
					}
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
			console.log("No todo ok");
		},
		/*complete: function(){
			//$("#loader").css("display", "none")
			console.log("Todo Completado");
		}*/
	});
}


function Prueba(){
	$.ajax({
		url: "data/CData/servicioSocialControl.php",
		type: "POST",
		data: {action:'ModeloControl'},
		
		success: function(data){
			try{
				documentos = JSON.parse(data);
				
				if(documentos.estatus == 'ok'){
					//console.log("Todo ok");
					console.log(documentos.data);
				}
			}catch(e){
				console.log(e);
				console.log(data);
			}
		},
		error: function(){
			console.log("No todo ok");
		},
		/*complete: function(){
			//$("#loader").css("display", "none")
			console.log("Todo Completado");
		}*/
	});
}


function HabilitarButton(idproceso, idarchivo, numenvio, user, iddocumento){
		$("#Button"+idproceso+"_"+idarchivo+"_"+numenvio+"_"+user+"_"+iddocumento).attr('disabled', false);
}

function HabilitarButton2(idproceso, idarchivo, numenvio, user){
	$("#ButtonC"+idproceso+"_"+idarchivo+"_"+numenvio+"_"+user).attr('disabled', false);
}

function iniciarTablaProcesos(){
	TablaProcesos = $("#tabla-descarga-formatos").DataTable({
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
			url: 'data/CData/servicioSocialControl.php',
			type: 'POST',
			dataType: "JSON",
			data:{
				action: 'ModeloControlTabla', 
			},
			//contentType: false,
			//processData: false,
			error: function(e){
				console.log(e.responseText);
			}
		}
	});
}


function iniciarTablaProcesosCorreccion(){
	var user = $("#numeroAfiliado").val(); 
	TablaCorreccion = $("#tabla-correccion-formatos").DataTable({
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
			url: 'data/CData/servicioSocialControl.php',
			type: 'POST',
			dataType: "JSON",
			data:{
				action: 'ConsultaFormatosCorreccion',
				usr: user 
			},
			//contentType: false,
			//processData: false,
			error: function(e){
				console.log(e.responseText);
			}
		}
	});
}


function iniciarTablaProcesosRevision(){
	var user = $("#numeroAfiliado").val(); 
	TablaRevision = $("#tabla-revision-formatos").DataTable({
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
			url: 'data/CData/servicioSocialControl.php',
			type: 'POST',
			dataType: "JSON",
			data:{
				action: 'ConsultaFormatosRevision',
				usr: user 
			},
			//contentType: false,
			//processData: false,
			error: function(e){
				console.log(e.responseText);
			}
		}
	});
}

function CambiarArchivo(idproceso, idarchivo, numenvio, user, iddocumento){
	
	var NuevoArchivo = $("#Cambio"+idproceso+"_"+idarchivo+"_"+numenvio+"_"+user+"_"+iddocumento).prop("files")[0];
	console.log(NuevoArchivo);
	fdata = new FormData();
		fdata.append('action',"CambiarDocumentoAlumno");
		fdata.append('idDoc',iddocumento);
		fdata.append('idPorc',idproceso);
		fdata.append('idArch',idarchivo);
		fdata.append('numEn',numenvio);
		fdata.append('idAlum',user);
		fdata.append('archivo',NuevoArchivo);
		//Ajax para cambiar el archivo en js

		$.ajax({
			url: "data/CData/servicioSocialControl.php",
			type: "POST",
			data: fdata,
			contentType:false,
            processData:false,

			success: function(data){
				try{
					documentos = JSON.parse(data);
					
					if(documentos.estatus == 'ok'){
						
						swal({
							title: 'Documento cambiado correctamente',
							icon: 'success',
							text: 'Espere su revisión',
							button: false,
							timer: 2500,
						}).then((result)=>{
							TablaRevision.ajax.reload(null,false);
							TablaCorreccion.ajax.reload(null,false);
							$("#Cambio"+idproceso+"_"+idarchivo+"_"+numenvio+"_"+user+"_"+iddocumento).val("");
							$("#Button"+idproceso+"_"+idarchivo+"_"+numenvio+"_"+user+"_"+iddocumento).attr("disabled", false);
						});
					}
				}catch(e){
					console.log(e);
					console.log(data);
				}
			},
			error: function(){
				console.log("No todo ok");
			},
			complete: function(){
				TablaRevision.ajax.reload(null,false);
			}
		});

}




function InsertarDocumentoAlumno(idproceso, idarchivo, numenvio, user){
	var archivoSubido = $("#avatar_"+idproceso+"_"+idarchivo+"_"+numenvio+"_"+user).prop("files")[0]; 
	console.log(archivoSubido);
	/*swal({
        text: '¿Está  seguro de cambiar el estatus del documento?',
        icon:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{*/
		fdata = new FormData();
		fdata.append('action',"InsertarDocumentoAlumno");
		fdata.append('idPorc',idproceso);
		fdata.append('idArch',idarchivo);
		fdata.append('numEn',numenvio);
		fdata.append('idAlum',user);
		fdata.append('archivo',archivoSubido);

		$.ajax({
			url: "data/CData/servicioSocialControl.php",
			type: "POST",
			data: fdata,
			contentType:false,
            processData:false,

			success: function(data){
				try{
					documentos = JSON.parse(data);
					
					if(documentos.estatus == 'ok'){
						
						swal({
							title: 'Documento agregado correctamente',
							icon: 'success',
							text: 'Espere su revisión',
							button: false,
							timer: 2500,
						}).then((result)=>{
							TablaRevision.ajax.reload(null,false);
							$("#avatar_"+idproceso+"_"+idarchivo+"_"+numenvio+"_"+user).val("");
						});
					}
				}catch(e){
					console.log(e);
					console.log(data);
				}
			},
			error: function(){
				console.log("No todo ok");
			},
			complete: function(){
				TablaRevision.ajax.reload(null,false);
			}
		});
	//});
}

function VerComentarios(idproceso, idarchivo, numenvio, user, iddocumento){
	$("#ComentariosServicio").modal("show");
	$("#idArchivo").val(iddocumento);
	tablacomentariosServicio(iddocumento);

	/*swal({
        text: '¿Está  seguro de cambiar el estatus del documento?',
        icon:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{*/
		fdata = new FormData();
		fdata.append('action',"InsertarDocumentoAlumno");
		fdata.append('idPorc',idproceso);
		fdata.append('idArch',idarchivo);
		fdata.append('numEn',numenvio);
		fdata.append('idAlum',user);
		//fdata.append('archivo',archivoSubido);

		/*$.ajax({
			url: "data/CData/servicioSocialControl.php",
			type: "POST",
			data: fdata,
			contentType:false,
            processData:false,

			success: function(data){
				try{
					documentos = JSON.parse(data);
					
					if(documentos.estatus == 'ok'){
						
						swal({
							title: 'Documento agregado correctamente',
							icon: 'success',
							text: 'Espere su revisión',
							button: false,
							timer: 2500,
						}).then((result)=>{
							TablaRevision.ajax.reload(null,false);
						});
					}
				}catch(e){
					console.log(e);
					console.log(data);
				}
			},
			error: function(){
				console.log("No todo ok");
			},
			complete: function(){
				TablaRevision.ajax.reload(null,false);
			}
		});*/
	//});
}

$("#Comentario-document-alu").on("submit", function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'InsertarComentarioServicio');
    $.ajax({
        url: 'data/CData/servicioSocialControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se agregó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Comentario agregado correctamente',
                        icon: 'success',
                        text: 'Espere su revisión',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#ComentarioArchivo").val("");
                        TablaComentariosArchivo.ajax.reload(null,false);
                        
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data)
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
});

function tablacomentariosServicio(idArchivo){
    TablaComentariosArchivo = $("#tablaComentariosArchivo").DataTable({
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
    "ajax": {
        url: 'data/CData/servicioSocialControl.php',
        type: 'POST',
        data: {action: 'consultarComentariosArchivo',
                idArch: idArchivo},
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
        }
    },
    'bDestroy': true,
    'iDisplayLength': 10,
    'order':[
        [1,'asc']
    ],
    });
}



