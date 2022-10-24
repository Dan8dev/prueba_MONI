$(document).ready(()=>{
    cargarGrado();
    
    cambioTab();
    var Nacionalidad = $("#IdentificadorNacionalidad").val();
    DocumentacionNacionalidad(Nacionalidad);
    if(Nacionalidad == 'Mexicano'){
        dataButtons();
    }
})

function cargarGrado(){
    $.ajax({
        url: 'data/CData/documentosControl.php',
        type: 'POST',
        data: {action: "cargarGrado"},
        dataType: 'JSON',
        success : function(data){
            try{
                $("#4").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data, function(key,registro){
                    $("#4").append('<option value='+registro.id_gradoE+'>'+registro.nombre+'</option>');
                });
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#4").on('change', function(){
    if($(this).val() === "0"){
        $("#gradoEstudios").prop("disabled", true);
    }else{
        $("#gradoEstudios").prop("disabled", false);
    }
})

$("#reiniciar").off('click').on('reset', function(){
    $("formDocumentos")[0].reset();
})

function VerificarBotonesEx(){
    indexNombres = {17:["#ButtonDocMigratorio","#DocMigratorio"] ,
                     2:["#ButtonActanaciemnto","#Actanaciemnto"],
                     4:["#ButtonCertEstMedicina","#CertEstMedicina"],
                     13:["#ButtonCopiaTitulo","#CopiaTitulo"],
                     11:["#ButtonComprobanteDomicilio","#ComprobanteDomicilio"],
                     14:["#ButtonDiplomaEspecialidad","#DiplomaEspecialidad"],
                     3:["#ButtoncurpEx","#curpEx"],
                     9:["#ButtonCartaMotivos","#CartaMotivos"],
                     18:["#ButtonDictamenTecnico","#DictamenTecnico"],
                     6:["#ButtonFotosInfantil","#FotosInfantil"],
                     19:["#ButtonFotosTitulo","#FotosTitulo"],
                     20:["#ButtonFotosCredencial","#FotosCredencial"]};
   // console.log(indexNombres);

    var idAlum = $("#IdentificadorAlumno").val();
    $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: {"action": "VerificarDocumentosAlumno",
                "idAlum": idAlum},
        dataType: "JSON",
        success: function (response) {
            $.each(response, function(key,registro){
                if(indexNombres[registro.id_documento] != undefined){     
                    //console.log(indexNombres[registro.id_documento][1]);
                    $(indexNombres[registro.id_documento][1]).prop("disabled",true);
                    $(indexNombres[registro.id_documento][1]).val("");

                    $(indexNombres[registro.id_documento][0]).prop("disabled",true);
                    $(indexNombres[registro.id_documento][0]).removeClass("btn-primary");
                    $(indexNombres[registro.id_documento][0]).addClass("btn-secondary waves-effect waves-light mr-2");
                    $(indexNombres[registro.id_documento][0]).prop("style","disabled");
                    $(indexNombres[registro.id_documento][0]).text("Enviado");

                    tSeguimiento.ajax.reload(null, false);
                }
                // console.log(indexNombres[registro.id_documento][0]+" --- "+indexNombres[registro.id_documento][1]);
            });
        }
    });
}

$("#formDocumentosExtranjeros").on("submit",function(e){
    e.preventDefault();
    var idAlum = $("#IdentificadorAlumno").val();
    fData =  new FormData(this);
    fData.append("action", "RegistroDocumentosExtranjeros");
    fData.append("idUsuario",idAlum);
    $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: fData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {
            if(response.estatus == "ok"){
                swal({
                    title: 'Documentos Cargados Correctamente',
                    icon: 'success',
                    text: 'Espere un momento...',
                    button: false,
                    timer: 2500,
                }).then((result)=>{
                    VerificarBotonesEx();
                });
            }else{
                swal({
                    title: 'Error al cargar documentos',
                    icon: 'error',
                    text: 'Espere un momento...',
                    button: false,
                    timer: 3000,
                });
            }
        }
    });
});

function RegistarDocumentoEspecificoExt(idInput){
    //console.log(idInput);
    var archivo = $('#'+idInput)[0].files[0];
    var nombre = $('#'+idInput).prop("name");
    var idAlum = $("#IdentificadorAlumno").val();

    fData = new FormData();
    fData.append("action","RegistroDocumentosExtranjeros");
    fData.append(nombre,archivo);
    fData.append("idUsuario",idAlum)
    
    $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: fData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {
            if(response.estatus = "ok"){
                swal({
                    title: 'Documentos Cargados Correctamente',
                    icon: 'success',
                    text: 'Espere un momento...',
                    button: false,
                    timer: 2500,
                }).then((result)=>{
                    $('#'+idInput).prop("disabled",true);
                    $('#'+idInput).val("");
                    $('#Button'+idInput).prop("disabled",true);
                    $('#Button'+idInput).removeClass("btn-primary");
                    $('#Button'+idInput).addClass("btn-secondary waves-effect waves-light mr-2");
                    $('#Button'+idInput).prop("style","disabled");
                    $('#Button'+idInput).text("Enviado");

                    tSeguimiento.ajax.reload(null, false);
                });
            }else{
                swal({
                    title: 'Error al cargar documentos',
                    icon: 'error',
                    text: 'Espere un momento...',
                    button: false,
                    timer: 3000,
                })
            }
        }
    });
}

function DocumentacionNacionalidad(Nacionalidad){
    if(Nacionalidad == "Mexicano"){
        $("#DocumentosAlumnoNacional").removeClass("d-none");
        $("#DocumentacionAlumnoExtranjero").addClass("d-none")
    }else{
        VerificarBotonesEx();
        $("#DocumentosAlumnoNacional").addClass("d-none");
        $("#DocumentacionAlumnoExtranjero").removeClass("d-none");
    }

}


function HabilitarButtonEnvio(idButton){
    $('#'+idButton).prop("disabled",false);
}


function dataButtons(){
    var  arrayN = new Array();
    if($("#IdentificadorNacionalidad").val() == "Mexicano"){
        arrayN = [2 ,3 ,4 ,5 ,6 ,7 ,8];
    }else{
        arrayN = [2,3,4,5,6,9,11,12,13,14,15,16,17,18,19,20];
    }

    var usuario = $("#id").attr('class');
    tSeguimiento = $("#datatable-seguimiento").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "ajax": {
            url: 'data/CData/documentosControl.php',
            type: 'POST',
            data: {action: 'consultarDocumentos',
                    idusuario: usuario,
                    allows: arrayN},
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
                'sNext': '>>',
                'sPrevious': '<<'
            },
            buttons: {
                copyTitle: 'Tabla Copiada de manera exitósa',
                copySuccess: {
                    _: 'Se copio %d filas',
                    1: 'Se copio1 fila'
                }
            }
        },
        'bDestroy': true,
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ]
    });

}


function cambioTab(){
        $("a[data-toggle=\"tab\"]").on("shown.bs.tab",function(e){
            tSeguimiento.columns.adjust();
        });
}


function buscarDocumento(documento, idusuario){
Data = {
    action: 'BuscarDocumento',
    idDoc: documento,
    idUsu: idusuario
}
$.ajax({
    url: 'data/CData/documentosControl.php',
    type: 'POST',
    data: Data,
    success: function(data){
        
        try{
            $("#formularioModificar")[0].reset();
            $("#pdf_renderer").hide();
            $("#verImg").attr('src','');
            $("#verImg").hide();
            
            pr = JSON.parse(data);
            var $id = pr.data[0].id_prospectos;
            var $name = pr.data[0].nombre_archivo;
            var $idDoc = pr.data[0].id_documento;
            var $com = pr.data[0].comentario
            setTimeout(function(){
                var direccion = '../app/lista_documentos/'+$id+'/'+$name;;
                var nombre = direccion.split('.');
                var extension = nombre.pop();
                //console.log(extension);
                if(extension == "pdf"){
                    $("#verImg").hide();
                    var $dir = '../app/lista_documentos/'+$id+'/'+$name;
                    canvas($dir);
                    $("#pdf_renderer").show();
                    $("#verDoc").show();
                }else{
                    $("#pdf_renderer").hide();
                    $("#verImg").attr('src','../app/lista_documentos/'+$id+'/'+$name);
                    $("#verImg").show();
                    $("#verDoc").hide();

                }
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);

            // if($idDoc == 7){
            //     $("#verImg").hide();
            //     var $dir = '../app/lista_documentos/'+$id+'/'+$name;
            //     canvas($dir);
            //     $("#pdf_renderer").show();
            //     $("#comentario").val($com);
            //     $("#idDocument").val($idDoc);
            // }
            // if($idDoc == 8){
            //     $("#verImg").hide();
            //     var $dir = '../app/lista_documentos/'+$id+'/'+$name;
            //     canvas($dir);
            //     $("#pdf_renderer").show();
            //     $("#comentario").val($com);
            //     $("#idDocument").val($idDoc);
            // }
            // if($idDoc == 2){
            //     $("#verImg").hide();
            //     var $dir = '../app/lista_documentos/'+$id+'/'+$name;
            //     canvas($dir);
            //     $("#pdf_renderer").show();
            //     $("#comentario").val($com);
            //     $("#idDocument").val($idDoc);
            //     //$("#name").val($name);
            //     //$("#verDoc").show();
            // }
            // if($idDoc == 3){
            //     $("#verImg").hide();
            //     var $dir = '../app/lista_documentos/'+$id+'/'+$name;
            //     canvas($dir);
            //     $("#pdf_renderer").show();
            //     //$("#verDoc").attr('src','../app/lista_documentos/Curp/'+$name);
            //     $("#comentario").val($com);
            //     $("#idDocument").val($idDoc);
            //     //$("#name").val($name);
            //     //$("#verDoc").show();
            // }
            // if($idDoc == 4){
            //     $("#verImg").hide();
            //     var $dir = '../app/lista_documentos/'+$id+'/'+$name;
            //     canvas($dir);
            //     $("#pdf_renderer").show();
            //     //$("#verDoc").attr('src','../app/lista_documentos/Comprobante_estudios/'+$name);
            //     $("#comentario").val($com);
            //     $("#idDocument").val($idDoc);
            //     //$("#name").val($name);
            //     //$("#verDoc").show();
            // }
            // if($idDoc == 5){
            //     $("#pdf_renderer").hide();
            //     //$("#verDoc").hide();
            //     $("#verImg").attr('src','../app/lista_documentos/'+$id+'/'+$name);
            //     //$("#nom").val($name);
            //     $("#comentario").val($com);
            //     $("#idDocument").val($idDoc);
            //     //$("#name").val($name);
            //     $("#verImg").show();
            //     //document.getElementById("verDoc").removeAttribute("src", "");
            // }
            // if($idDoc == 6){
            //     $("#pdf_renderer").hide();
            //     //$("#verDoc").hide();
            //     $("#verImg").attr('src','../app/lista_documentos/'+$id+'/'+$name);
            //     //$("#nom").val($name);
            //     //$("#pdf_renderer").hide();
            //     $("#comentario").val($com);
            //     $("#idDocument").val($idDoc);
            //     //$("#name").val($name);
            //     $("#verImg").show();
            //     //document.getElementById("verDoc").removeAttribute("src", "");
            // }
            },900);
        }catch(e){
            console.log(e)
            console.log(data)
        }
    },
    error: function(){

    },
    complete: function(){
        $(".outerDiv_S").css("display", "none")
    }
});
}



$("#ocultar").on("click", function(){
    $("#formularioModificar")[0].reset();
    $("#verImg").hide();
    $("#verImg").attr('src','');
    $("#pdf_renderer").hide();
    //var $dir = '';
    //canvas($dir);

    //$("#verDoc").hide();
    //document.getElementById("verDoc").removeAttribute("src", "");
    //document.getElementById("verDoc").setAttribute("src", "");
})

$("#formularioModificar").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'modificarDocumento');
    $.ajax({
        url: 'data/CData/documentosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Modificación Correcta',
                        icon: 'info',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        var copy_send = '';
                        var locat = window.location.toString();
                        if(locat.includes('localhost') || locat.includes('sandbox.conacon.org')){
                            copy_send = '../../udc/app/data/CData/documentosControl.php';
                        }else if(locat.includes('conacon.org')){
                            copy_send = 'https://moni.com.mx/udc/app/data/CData/documentosControl.php';
                        }
                        if(copy_send != ''){
                            $.ajax({
                                url: copy_send,
                                type: 'POST',
                                data: fData,
								crossDomain: true,
                                contentType: false,
                                processData: false,
                                success: function (res) {
                                    console.log(res);
                                }
                            })
                        }
                        $("#formularioModificar")[0].reset;
                        $("#verImg").attr('src',''); 
                        $("#verImg").hide();
                        //document.getElementById("verDoc").removeAttribute("src", "");
                        //$("#verDoc").hide();
                        tSeguimiento.ajax.reload(); 
                        $("#modalModify").modal('hide');
                    })
                }else{
                    swal({
                        title: 'Formato Incorrecto',
                        icon: 'warning',
                        text: 'Adjunta un formato correcto',
                        button: false,
                        timer: 2000,
                    })
                }
            }catch(e){
                swal({
                    title: 'Formato Incorrecto',
                    icon: 'info',
                    text: 'Adjunta un formato correcto o verifica que tu archivo no sobrepase los 5MB.',
                    button: false,
                    timer: 3000,
                })
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }        
    });
})



function habilitarInputs(idusuario){
    Data = {
        action: 'habilitarInputs',
        idUsu: idusuario
    }
    $.ajax({
        url: 'data/CData/documentosControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                pr = JSON.parse(data)
                //var arrayInp = new Array();
                for(var i in pr.data){
                    if(pr.data[i].id_documento == 7){
                        $("#7").prop('disabled', true);
                        $("#btnDoc7").prop('disabled', true);
                        $("#btnDoc7").hide();
                        $("#btnEnviado7").show();
                    }
                    if(pr.data[i].id_documento == 8){
                        $("#8").prop('disabled', true);
                        $("#btnDoc8").prop('disabled', true);
                        $("#btnDoc8").hide();
                        $("#btnEnviado8").show();
                    }
                    if(pr.data[i].id_documento == 2){       
                        $("#2").prop('disabled', true);
                        $("#btnDoc2").prop('disabled', true);
                        $("#btnDoc2").hide();
                        $("#btnEnviado2").show();
                    }
                    if(pr.data[i].id_documento == 3){
                        $("#3").prop('disabled', true);
                        $("#btnDoc3").prop('disabled', true);
                        $("#btnDoc3").hide();
                        $("#btnEnviado3").show();
                    }
                    if(pr.data[i].id_documento == 4){    
                        $("#4").prop('disabled', true);
                        $("#btnDoc4").prop('disabled', true);
                        $("#btnDoc4").hide();
                        $("#btnEnviado4").show();
                    }
                    if(pr.data[i].id_documento == 5){
                        $("#5").prop('disabled', true);
                        $("#btnDoc5").prop('disabled', true);
                        $("#btnDoc5").hide();
                        $("#btnEnviado5").show();
                    }
                    if(pr.data[i].id_documento == 6){
                        $("#6").prop('disabled', true);
                        $("#btnDoc6").prop('disabled', true);
                        $("#btnDoc6").hide();
                        $("#btnEnviado6").show();
                    }
                }
            }catch(e){

            }
        },
        error : function(){

        },
        complete: function(){

        }
    });
}

function canvas($name){
    //console.log($name)
    var sourcetype = $name.split('.');
    sourcetype = sourcetype[sourcetype.length - 1];
    if(sourcetype == 'pdf'){
        var myState = {
            pdf: null,
            currentPage: 1,
            zoom: 1
        }
        //pdfjsLib.getDocument('../app/lista_documentos/Identificaciones/43_identificacion.pdf').then((pdf) =>{
        
        pdfjsLib.getDocument($name).then((pdf) =>{
            myState.pdf = pdf;
            render(myState);
        });
    }else if(['gif', 'jpg', 'jpeg', 'png'].includes(sourcetype.toLowerCase())){
        $("#canvas_container").prepend(`<img id="image_dynamic" src="${$name}" width="80%" >`);
    }else{
        $("#canvas_container").prepend(`<a class="text-primary" id="image_dynamic" href="${$name}" target="_blank"><h5>Clic aquí para ver documento</h5> </a> `);
    }
}

function render(myState){
    myState.pdf.getPage(myState.currentPage).then((page)=>{
        var canvas = document.getElementById("pdf_renderer");
        var ctx = canvas.getContext('2d');

        var viewport = page.getViewport(myState.zoom);
        var viewport=page.getViewport(.6);
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        //myCanvas.style.width = "100%";
        //myCanvas.style.height = "100%";
        
        page.render({
            canvasContext: ctx,
            viewport: viewport
        });
    });
}

function guardarDocumento(archivo, documento, idUsuario){
    $("#btnDoc"+documento).prop('disabled', true);
    selectGrado = $(archivo).parent().find('select option:selected').val();
    csvFile = $(archivo).parent().find('input')[0].files[0];
    fData = new FormData();
    fData.append('action', 'registrarDocumentos');
    fData.append('file', csvFile);
    fData.append('documento', documento);
    fData.append('idUsuario', idUsuario);
    fData.append('gradoEstudio', selectGrado);
    $.ajax({
        url: 'data/CData/documentosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        beforeSend: function(){
            //$("#Enviar").prop('disabled', true);
            $("#spinnerDoc"+documento).show();
        },
        success : function(data){
            if(data == 'DocInc'){
                swal({
                    title: 'Formato del documento incorrecto',
                    icon: 'info',
                    text: 'Adjunta un formato correcto: pdf o verifica que tu archivo no sobrepase los 5MB.',
                    button: false,
                    timer: 3000,
                });
                $("#spinnerDoc"+documento).hide();
                $("#btnDoc"+documento).prop('disabled', false);
            }
            if(data == 'ImgInc'){
                swal({
                    title: 'Formato de la imagen incorrecto',
                    icon: 'info',
                    text: 'Adjunta un formato correcto: png, jpg, jpeg o verifica que tu archivo no sobrepase los 5MB.',
                    button: false,
                    timer: 3000,
                });
                $("#spinnerDoc"+documento).hide();
                $("#btnDoc"+documento).prop('disabled', false);
            }
            if(data == ''){
                swal({
                    title: 'Sin documento',
                    icon: 'info',
                    text: 'Adjunta el archivo correspondiente.',
                    button: false,
                    timer: 2200,
                });
                $("#spinnerDoc"+documento).hide();
                $("#btnDoc"+documento).prop('disabled', false);
            }
            try{
                pr = JSON.parse(data)
                //console.log(pr.data.documento)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Enviado Correctamente',
                        icon: 'info',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1500,
                    }).then((result)=>{
                        var copy_send_r = '';
                        var locat = window.location.toString();
                        if(locat.includes('localhost') || locat.includes('sandbox.conacon.org')){
                            copy_send_r = '../../udc/app/data/CData/documentosControl.php';
                        }else if(locat.includes('conacon.org')){
                            copy_send_r = 'https://moni.com.mx/udc/app/data/CData/documentosControl.php';
                        }
                        if(copy_send_r != ''){
                            fData.append('copy','1');
                            $.ajax({
                                url: copy_send_r,
                                type: 'POST',
                                data: fData,
                                contentType: false,
                                processData: false,
                                success: function (res) {
                                    console.log(res);
                                },
                                error: function(jqXHR, status){
                                    console.log(jqXHR, status);
                                },
                                
                            })
                        }
                        if(pr.data.documento=="4"){
                            $("#"+pr.data.documento).prop('disabled', true);
                            $("#gradoEstudios").prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).hide();
                            $("#btnEnviado"+pr.data.documento).show();
                            $("#spinnerDoc"+pr.data.documento).hide();
                        }else{
                            $("#"+pr.data.documento).prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).hide();
                            $("#btnEnviado"+pr.data.documento).show();
                            $("#spinnerDoc"+pr.data.documento).hide();
                        }
                        $("#formDocumentos")[0].reset();
                        tSeguimiento.ajax.reload();
                        $("a[data-target='#seguimiento']").trigger("click");
                        //$("#7").prop('disabled', true);
                        //changeContenido();
                    })
                }
            }catch(e){
                //console.log(e)
                //console.log(data)
            }
        }
    });
}

$("#7").on('change', function(){
    $("#spinnerDoc7").show();
    t=0;
    //console.log('hola');
    const inputs = document.querySelector(".inputfile");
    const archivos = inputs.files;
    const valueInp = archivos;
        if( !archivos || !archivos.length){
            $("#spinnerDoc7").hide();
            return;
        }
        //console.log('hola2');
        if(valueInp){
            //console.log('hola3');
            t=1;
            if(t==1){
                //console.log('hola4');
                $("#spinnerDoc7").hide();
            }
        }
})
