$(document).ready(()=>{
    //cargarGrado();
    $("#TipoAlumno").val('Universidad');
    if(carreras == "1"){
        $("#TipoAlumno").val('Medicina');
    }
    CrearFormularioDinamico(carreras);
    dataButtons();
    tableFisicos();
    //cambioTab();
});

$("#nav1").on("click", function(e){
    $("#documentos_digitales").removeClass("active show");
});

$("#nav2").on("click", function(e){
    $("#documentos_fisicos").removeClass("active show");
});
function CrearFormularioDinamico(carreras){
    $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: {"action": "consultarDocumentosListaCompleta"},
        dataType: "JSON",
        success: function (response) {
            $.each(response, function(key,registro){
                var letrero = (carreras == "1" && registro.letreromedicina != null) ? registro.letreromedicina : registro.letrero;
                var indic =  (letrero == "" || letrero == null) ? "" : "<strong> - Documento Claro y Legible. </strong>";
               
                $("#Formulario_dinamico").append(`
                <div class="form-group row justify-content-center `+registro.clases+` tx-bg">
                    <div class="col-md-12 clave  tx-bg my-3">
                        `+letrero.replace(/\n/g,"<br>")+indic+`
                    </div>
                    <div class="col-md-2 text-center tx-bg my-">
                        <label  for="`+registro.nomenclatura_documento+`"><b>`+registro.nombre_documento+`</b><br>(pdf, jpeg, jpg, png)</label>
                    </div>
                    <div class="col-md-8 tx-bg">
                        <input class="form-control" type="file" name="`+registro.nomenclatura_documento+`" id="`+registro.nomenclatura_documento+`" accept=".pdf, .jpeg, .jpg, .png" oninput = "HabilitarButtonEnvio('Button`+registro.nomenclatura_documento+`')">
                    </div>
                    <div class="col-md-2 tx-bg ">
                        <button type="button" id = "Button`+registro.nomenclatura_documento+`" onClick ="RegistarDocumentoEspecificoExt('`+registro.nomenclatura_documento+`')" class="btn btn-primary" disabled>Enviar</button>
                    </div>
                </div>
                `);
            });
            $("#Formulario_dinamico").append(`<button type="submit" class ="btn btn-primary">Enviar Documentos Cargados</button>`);
        },
        complete: function(response){
            var Nacionalidad = $("#IdentificadorNacionalidad").val();
            DocumentacionNacionalidad(Nacionalidad);
        }
    });
}

function VerificarBotonesEx(){
    indexNombres = new Array();
    $.ajax({
        type: "POST",
        url: "data/CData/documentosControl.php",
        data: {"action": "consultarDocumentosListaCompleta"},
        dataType: "JSON",
        success: function (response) {
            
            $.each(response, function(key,registro){
                if(registro.id_documento >=1 ){
                    indexNombres[registro.id_documento] = (["#Button"+registro.nomenclatura_documento,"#"+registro.nomenclatura_documento]);
                }
            });
        },
        complete: function (response){
            console.log(indexNombres);
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
                    });
                }
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
    VerificarBotonesEx();
    $(".AMBOS").removeClass("d-none");
    $(".INDIST").removeClass("d-none");

    if(Nacionalidad == "Mexicano"){
       console.log("Mostrar documentos Mexicanos");
       $(".EXTRANJERO").addClass("d-none");
    }else{
        console.log("Mostrar documentos Extranjeros");
        $(".MEXICANO").addClass("d-none");
    }

    var TipoAlumno = $("#TipoAlumno").val();
    console.log(TipoAlumno);
    if(TipoAlumno == "Universidad"){
        console.log("Mostrar documentos TSU");
        $(".MEDICINA").addClass("d-none");
     }else{
         console.log("Mostrar documentos MEDICINA");
         $(".TSU").addClass("d-none");
     }

     if(Nacionalidad == "Mexicano" && TipoAlumno == "Universidad"){
        $(".EXCLUD").removeClass("d-none");
     }
 
}


function HabilitarButtonEnvio(idButton){
    $('#'+idButton).prop("disabled",false);
}




function dataButtons(){
    var usuario = $("#id").attr('class');
    tSeguimiento = $("#datatable-seguimiento").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        /*"dom" :'Bfrtip',
        buttons:[{
            extend:"copy",
            className: "btn-success"
        },{
            extend: "csv"
        }, {
            extend: "excel",
            className: "btn-primary"
        }, {
            extend: "pdf"
        }, {
            extend: "print"
        }],*/
        "ajax": {
            url: 'data/CData/documentosControl.php',
            type: 'POST',
            data: {action: 'consultarDocumentos',
                    idusuario: usuario},
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

function tableFisicos(){
    var usuario = $("#id").attr('class');
    tSeguimientofisico = $("#datatable-seguimiento-fisicos").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        /*"dom" :'Bfrtip',
        buttons:[{
            extend:"copy",
            className: "btn-success"
        },{
            extend: "csv"
        }, {
            extend: "excel",
            className: "btn-primary"
        }, {
            extend: "pdf"
        }, {
            extend: "print"
        }],*/
        "ajax": {
            url: 'data/CData/documentosControl.php',
            type: 'POST',
            data: {action: 'consultarDocumentosFisicos',
                    idUsuario: usuario},
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

// function cambioTab(){
//     $("a[data-toggle=\"tab\"]").on("shown.bs.tab",function(e){
//         tSeguimiento.columns.adjust();
//     });
// }

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
            //$("#verDoc").show();
            //document.getElementById("verDoc").setAttribute("src", "");
            $("#verImg").attr('src','');
            $("#verImg").hide();
            
            pr = JSON.parse(data);
            var $id = pr.data[0].id_prospectos;
            var $name = pr.data[0].nombre_archivo;
            var $ext = $name.split('.');
            var $idDoc = pr.data[0].id_documento;
            var $com = pr.data[0].comentario;
            let $extImg = ['jpg', 'jpeg', 'png'];
            let $extDoc = ['pdf'];
            
            setTimeout(function(){
                if($extImg.includes($ext[1])){
                    $("#pdf_renderer").hide();
                    $("#verImg").attr('src','../app/lista_documentos/'+$id+'/'+$name);
                    $("#comentario").val($com);
                    $("#idDocument").val($idDoc);
                    $("#verImg").show();
                }
                if($extDoc.includes($ext[1])){
                    $("#verImg").hide();
                    var $dir = '../app/lista_documentos/'+$id+'/'+$name;
                    canvas($dir);
                    $("#pdf_renderer").show();
                    $("#comentario").val($com);
                    $("#idDocument").val($idDoc);
                }
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
                        var copy = '';
                        var locat = window.location.toString();
                        if(locat.includes('localhost') || locat.includes('sandbox.conacon.org')){
                            copy = '../../siscon/app/data/CData/documentosControl.php';
                        }else if(locat.includes('moni.com.mx')){
                            copy = 'https://conacon.org/moni/siscon/app/data/CData/documentosControl.php';
                        }
                        if(copy != ''){
                            console.log(copy);
                            $.ajax({
                                url: copy,
                                type: 'POST',
                                data: fData,
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




function canvas($name){
    console.log($name)
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

