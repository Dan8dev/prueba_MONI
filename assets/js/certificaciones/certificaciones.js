

$(document).ready(function () {
    //console.log("Nuevo js Certificaciones");
    carreras();

});

$("#carrerasCert").on('change', function(e){
    $("#buttonNuevaFechaCer").removeClass("d-none");
    var idCarrera = $("#carrerasCert").val();
    tablaMateriasRe(idCarrera);
});

$("#buttonNuevaFechaCer").on("click",function(e){
    $("#modalNuevaFechaExpedicion").modal("show");
});

function VerificarCreacionZip(){
    var BloqueCal = [];
    TablaXMLDesc.cells().every((ix, g)=>{
        if(g == 0){
            nodelm = TablaXMLDesc.cell({row:ix, column:g}).node();
            id = $(nodelm).find('input').val();
            BloqueCal.push(id);
        }            
    });

    //console.log(BloqueCal);
    //Se quita el type json para no hacer doble conversion 
    $.ajax({
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        type: 'POST',
        data: {
            action: 'CrearZipDeXML',
            BloqueId: BloqueCal
        },
        success: function(data){
            try{
                //console.log(data);
                json = JSON.parse(data);
                //console.log(json);
                if(json.status == '0'){
                    swal({
                        title: 'ZIP generado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    });
                    today = new Date();
                    var dia = today.getDate().toString();
                    var mes = (today.getMonth()+1).toString();
                    var ani = today.getFullYear().toString();

                    if(dia.length == 1){
                        dia = "0"+dia;
                    }
                    if(mes.length == 1){
                        mes = "0"+mes;
                    }
                    location.assign("../controlescolar/archivos/certificaciones/ZipXml/"+ani+"-"+mes+"-"+dia+".zip");  
                }else{
                    swal({
                        title: 'Error al generar el ZIP',
                        icon: 'error',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2000,
                    });
                }
            }catch(e){
                console.log(e);
            }
           
        }
    });
}

function EnvioXML(e,este){
    e.preventDefault();
    var fechaCert = $("#fechasXmlUp option:selected").text();
    fData = new FormData($(este)[0]);
    fData.append('action','GenerarXml');
    fData.append('fecha',fechaCert);
    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        data: fData,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                json = JSON.parse(data);
                //console.log(json);
                if(json.estatus == 'ok'){
                    swal({
                        title: 'XML generado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        if($("#tableExpedicionesXmlDown").DataTable().ajax.url() !== null){
                            $("#tableExpedicionesXmlDown").DataTable().ajax.reload(null, false);
                        }
                        if($("#tableExpedicionesProspectos").DataTable().ajax.url() !== null){
                            $("#tableExpedicionesProspectos").DataTable().ajax.reload(null, false);
                        }
                        TablaXML.ajax.reload(null,false);
                    });
                }else{
                    if(json.estatus == 'faltantes'){
                        var AvisoFaltantes = "";
                        json.faltantes.forEach(element => AvisoFaltantes += element+"<br>");
                        Swal.fire({
                            title: 'Error al generar xml del alumno',
                            type: 'error',
                            html: '<b>Datos faltantes:</b> <br>'+AvisoFaltantes,
                            showConfirmButton: true
                        })
                    }
                }
            }catch(e){
                console.log(e);
            }
        }
    });
}


$("#formEditarFechaExpedicion").on('submit',function(e){
    e.preventDefault();
    var idCarrera = $("#carrerasCert").val();
    fData = new FormData(this);
    fData.append('action','EditarFecha');
    fData.append('idCarr',idCarrera);
    //console.log(fData);
    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        data: fData,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                json = JSON.parse(data);
                if(json.estatus == 'ok'){
                    swal({
                        title: 'Fecha editada Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        TablaFechasEx.ajax.reload(null,false);
                        $("#EditarFechaFirmaExpedicion").modal("hide");
                        $("#formEditarFechaExpedicion")[0].reset();
                    });
                }else{
                    swal({
                        title: 'Aun no ha selecciona un Firmante',
                        icon: 'error',
                        text: 'Intente nuevamente...',
                        button: false,
                        timer: 2500,
                    })
                }
            }catch(e){
                console.log(e);
            }
            
        }
    });
});

$("#formNuevaFechaExpedicion").on('submit',function(e){
    e.preventDefault();
    var idCarrera = $("#carrerasCert").val();
    fData = new FormData(this);
    fData.append('action','NuevaFecha');
    fData.append('idCarr',idCarrera);
    //console.log(fData);
    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        data: fData,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                json = JSON.parse(data);
                if(json.estatus == 'ok'){
                    swal({
                        title: 'Fecha añadida Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        TablaFechasEx.ajax.reload(null,false);
                        $("#modalNuevaFechaExpedicion").modal("hide");
                        $("#formNuevaFechaExpedicion")[0].reset();
                    });
                }else{
                    swal({
                        title: 'Aun no ha selecciona un Firmante',
                        icon: 'error',
                        text: 'Intente nuevamente...',
                        button: false,
                        timer: 2500,
                    })
                }
            }catch(e){
                console.log(e);
            }
            
        }
    });
});



$('#carrerasExp').on("change", function(e){
    if(typeof(TablaProspectos) != 'undefined'){
        TablaProspectos.clear();
        TablaProspectos.draw();
    }
    var idcarrera = $('#carrerasExp').val();
    $.ajax({
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        type: 'POST',
        data: {
            action: 'buscarClasesCarreraSelect',
            idCarr: idcarrera,
            tabla: '0'
        },
        dataType: 'JSON',
        success: function(data){
            //console.log(data);
            $("#fechasExp").html('<option selected="true" value="" disabled="disabled">Seleccione fecha</option>');
           
            $.each(data, function(key,registro){
                $("#fechasExp").append('<option value='+registro.idexpedicion+'>'+registro.fecha_expedicion+'</option>');
            });
        }
    })
});

$('#carrerasXmlUp').on("change", function(e){
    if(typeof(TablaXML) != 'undefined'){
        TablaXML.clear();
        TablaXML.draw();
    }
    var idcarrera = $('#carrerasXmlUp').val();
    $.ajax({
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        type: 'POST',
        data: {
            action: 'buscarClasesCarreraSelect',
            idCarr: idcarrera,
            tabla: '0'
        },
        dataType: 'JSON',
        success: function(data){
            //console.log(data);
            $("#fechasXmlUp").html('<option selected="true" value="" disabled="disabled">Seleccione fecha</option>');
           
            $.each(data, function(key,registro){
                $("#fechasXmlUp").append('<option value='+registro.idexpedicion+'>'+registro.fecha_expedicion+'</option>');
            });
        }
    })
});

$('#carrerasXmlDown').on("change", function(e){
    if(typeof(TablaXMLDesc) != 'undefined'){
        TablaXMLDesc.clear();
        TablaXMLDesc.draw();
    }
    var idcarrera = $('#carrerasXmlDown').val();
    $.ajax({
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        type: 'POST',
        data: {
            action: 'buscarClasesCarreraSelect',
            idCarr: idcarrera,
            tabla: '0'
        },
        dataType: 'JSON',
        success: function(data){
            console.log(data);
            $("#fechasXmlDown").html('<option selected="true" value="" disabled="disabled">Seleccione fecha</option>');
           
            $.each(data, function(key,registro){
                $("#fechasXmlDown").append('<option value='+registro.idexpedicion+'>'+registro.fecha_expedicion+'</option>');
            });
        }
    });
});

//Asignar id Carrera para las vistas de las tablas
$("#fechasExp").on("change", function(){
    var idCarr = $("#carrerasExp").val();
    tablaProspectosCertificaciones(idCarr);
});

$("#fechasXmlUp").on('change',function(){
    var idCarr = $("#carrerasXmlUp").val();
    tableExpedicionesXmlUp(idCarr);
});

$("#fechasXmlDown").on("change", function(){
    var idCarr = $("#carrerasXmlDown").val();
    tableExpedicionesXmlDown(idCarr);
});


//f
// function validar_adeudosAlumno(id,idAlumno){
//     $.ajax({
//         url: '..udc/app/data/CData/materiasControl.php',
//         type: 'POST',
//         data: {action: 'validar_adeudos',
//             id: id,
//             idAlumno: idAlumno},
//         success: function(data){
            
//             if(data.trim() =='si'){
//                 console.log("si");
//             }else{
//                 var mensaje = '';
//                 switch(data.trim()){
//                     case 'no inscripcion':
//                         mensaje = 'Su acceso se ha bloqueado por no contar con registro de pago de inscripción';
//                     break;
//                     case 'no mensualidad':
//                         mensaje = 'Su acceso se ha bloqueado por no contar con registro de pago de mensualidad';
//                     break;
//                     case 'no documentos':
//                         mensaje = 'Su acceso se ha bloqueado falta de entrega de documentos digitales';
//                     break;
//                     case 'no documentos fisicos':
//                         mensaje = 'Su acceso se ha bloqueado falta de entrega de documentos físicos';
//                     break;
//                 }

//                 if(mensaje != ''){
//                    console.log("No");
//                 }
//             }
//         }
//     });
// }

//:::
function Modificar(idexpedicion){
    $("#idFechaExpediente").val(idexpedicion);

    var idcarrera = $('#carrerasCert').val();
    $("#EditarFechaExpediente").val();
    $("#EditarFechaFirmaExpedicion").modal("show");
    $.ajax({
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        type: 'POST',
        data: {
            action: 'buscarClasesCarreraSelect',
            filtroFechaId: idexpedicion,
            idCarr: idcarrera,
        },
        success: function(data){
            json = JSON.parse(data);
            TablaFechasEx.ajax.reload(null,false);
            $("#EditarFechaExpediente").val(json[0].fecha_expedicion);
            $("#TipoFirmanteEdit").val(json[0].tipo);
            $("#FirmanteSeleccionadoEdit").prop('checked',true);;
        }
    });
}

function DarDeBaja(idexpedicion){
    Swal.fire({
        text: '¿Está  seguro de eliminar la fecha de expedición?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            $.ajax({
                url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
                type: 'POST',
                data: {
                    action: 'EliminarFechaExpedicion',
                    filtroFechaId: idexpedicion
                },
                success: function(data){
                    swal({
                        title: 'Fecha eliminada correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    });
                    json = JSON.parse(data);
                    TablaFechasEx.ajax.reload(null,false);
                }
            });
        }
    });
}

function Quitar(idAlumno,gen){
    // console.log(idAlumno);
    // console.log(gen);
    Swal.fire({
        text: '¿Está  seguro de quitar al alumno de los Prospectos?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            $.ajax({
                url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
                type: 'POST',
                data: {
                    action: 'CambiarEstatusAlumno',
                    idAl: idAlumno,
                    idGen: gen
                },
                dataType: 'JSON',
                success: function(data){
                   console.log(data);
                    if(data.data == 1){
                        swal({
                            title: 'Alumno eliminado correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            TablaProspectos.ajax.reload(null,false);
                        });
                    }
                }
            });
        }
    });
}

function Eliminar_XML(idAlumno,gen){
    Swal.fire({
        text: '¿Está  seguro de eliminar el XML del alumno?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            $.ajax({
                url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
                type: 'POST',
                data: {
                    action: 'EliminarXmlAlumno',
                    idAl: idAlumno,
                    idGen: gen
                },
                dataType: 'JSON',
                success: function(data){
                   console.log(data);
                    if(data.estatus == "ok"){
                        swal({
                            title: 'XML eliminado correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            TablaProspectos.ajax.reload(null,false);
                        });
                    }
                }
            });
        }
    });
}

function carreras(){
    $.ajax({
        url: '../assets/data/Controller/certificaciones/certificacionesControl.php',
        type: 'POST',
        data: {
            action: 'buscarClasesCarrera',
        },
        dataType: 'JSON',
        success: function(data){
            $("#carrerasCert").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasExp").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasXmlUp").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasXmlDown").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
           
            $.each(data, function(key,registro){
                $("#carrerasCert").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasExp").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasXmlUp").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasXmlDown").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                });
        }
    })
}

function tableExpedicionesXmlDown(idCarrera){
    suf_carr = $("#carrerasXmlDown option:selected").text();
    suf_fecha = new Date().toLocaleDateString().replace(/\//g, '-');
    var datosnum = 0;
    var fecha =  $("#fechasXmlDown option:selected").text();
    TablaXMLDesc = $("#tableExpedicionesXmlDown").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            extend: "excel",
            title: `DescargarXML_${suf_carr}_${suf_fecha}`,
            className: "btn-primary"
        }, {
            extend: "pdf",
            title: `DescargarXML_${suf_carr}_${suf_fecha}`
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
                url: "../assets/data/Controller/certificaciones/certificacionesControl.php",
                type: 'POST',
                dataType: "JSON",
                data:{
                    action: 'buscarAlumnosCarreraXMLDown',
                    idCarr: idCarrera,
                    fechaSelect: fecha
                },
                complete: function(response){
                    if($("#tableExpedicionesXmlDown").DataTable().rows().count() > 0){
                        $("#buttonNuevoZip").removeClass("d-none");
                    }else{
                        $("#buttonNuevoZip").addClass("d-none");
                    }
                },
                //contentType: false,
                //processData: false,
                error: function(e){
                    console.log(e.responseText);
                }
            }
    });
}

function tableExpedicionesXmlUp(idCarrera){
    suf_carr = $("#carrerasXmlUp option:selected").text();
    suf_fecha = new Date().toLocaleDateString().replace(/\//g, '-');

    TablaXML = $("#tableExpedicionesXmlUp").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            extend: "excel",
            title: `GenerarXML_${suf_carr}_${suf_fecha}`,
            className: "btn-primary"
        }, {
            extend: "pdf",
            title: `GenerarXML_${suf_carr}_${suf_fecha}`
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
            url: "../assets/data/Controller/certificaciones/certificacionesControl.php",
            type: 'POST',
            dataType: "JSON",
            data:{
                action: 'buscarAlumnosCarreraXML',
                idCarr: idCarrera,
            },
            //contentType: false,
            //processData: false,
            error: function(e){
                console.log(e.responseText);
            }
        }
    });
}

function tablaProspectosCertificaciones(idCarrera){
    suf_carr = $("#carrerasExp option:selected").text();
    suf_fecha = new Date().toLocaleDateString().replace(/\//g, '-');

    TablaProspectos = $("#tableExpedicionesProspectos").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "dom" :'Bfrtip',
    buttons:[{
        extend: "excel",
        title: `ProspectosCertificaciones_${suf_carr}_${suf_fecha}`,
        className: "btn-primary"
    }, {
        extend: "pdf",
        title: `ProspectosCertificaciones_${suf_carr}_${suf_fecha}`
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
            url: "../assets/data/Controller/certificaciones/certificacionesControl.php",
            type: 'POST',
            dataType: "JSON",
            data:{
                action: 'buscarAlumnosCarrera',
                idCarr: idCarrera,
            },
            //contentType: false,
            //processData: false,
            error: function(e){
                console.log(e.responseText);
            }
        }
    });
}

function tablaMateriasRe(idCarrera){
    suf_carr = $("#carrerasCert option:selected").text();
    suf_gen = '';
    suf_fecha = new Date().toLocaleDateString().replace(/\//g, '-');

    TablaFechasEx = $("#tableExpedicionesRegistradas").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "dom" :'Bfrtip',
    buttons:[{
        extend: "excel",
        title: `Expediciones_${suf_carr}_${suf_fecha}_${suf_gen}`,
        className: "btn-primary"
    }, {
        extend: "pdf",
        title: `Expediciones_${suf_carr}_${suf_fecha}_${suf_gen}`
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
            url: "../assets/data/Controller/certificaciones/certificacionesControl.php",
            type: 'POST',
            dataType: "JSON",
            data:{
                action: 'ConsultarCarreras',
                idCarr: idCarrera,
                tabla: '1'
            },
            //contentType: false,
            //processData: false,
            error: function(e){
                console.log(e.responseText);
            }
        }
    });
}