$(document).ready(()=>{
    cargarGrado();
    dataButtons();
    cambioTab();
})

function cargarGrado(){
    $.ajax({
        url: 'data/CData/documentosControl.php',
        type: 'POST',
        data: {action: "cargarGrado"},
        dataType: 'JSON',
        success : function(data){
            try{
                $("#4").html('<option selected="true" disabled="disabled">Seleccione</option>');
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

$("#formDocumentos").off('submit').on('submit', function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'registrarDocumentos');
        $.ajax({
            url: 'data/CData/documentosControl.php',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
            success : function(data){
                if(data == 'DocInc'){
                    swal({
                        title: 'Formato de documentos incorrecto',
                        icon: 'info',
                        text: 'Adjunta un formato correcto: pdf o verifica que tu archivo no sobrepase los 2MB.',
                        button: false,
                        timer: 3000,
                    });
                }
                if(data == 'DocImg'){
                    swal({
                        title: 'Formato de imagenes incorrecto',
                        icon: 'info',
                        text: 'Adjunta un formato correcto: png, jpg, jpeg o verifica que tu archivo no sobrepase los 2MB.',
                        button: false,
                        timer: 3000,
                    });
                }
                if(data == 'DocIncDocImg'){
                    swal({
                        title: 'Documentos incorrectos',
                        icon: 'info',
                        text: 'Adjunta un formato correcto o verifica que tus archivos no sobrepasen los 2MB.',
                        button: false,
                        timer: 3000,
                    });
                }
                try{
                    pr = JSON.parse(data)
                    console.log(pr)
                    if(pr.estatus == 'ok'){
                        swal({
                            title: 'Enviado Correctamente',
                            icon: 'info',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 3000,
                        }).then((result)=>{
                            $("#formDocumentos")[0].reset();
                            $("#7").prop('disabled', true);
                            $("#8").prop('disabled', true);
                            $("#2").prop('disabled', true);
                            $("#3").prop('disabled', true);
                            $("#4").prop('disabled', true);
                            $("#gradoEstudios").prop('disabled', true);
                            $("#5").prop('disabled', true);
                            $("#6").prop('disabled', true);
                            tSeguimiento.ajax.reload();
                            $("a").trigger("click");
                            //changeContenido();
                        })
                    }
                }catch(e){
                    //console.log(e)
                    //console.log(data)
                }
            },
            error : function(){

            },
            complete : function(){
                $(".outerDiv_S").css("display", "none")
            }
        });
})


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
            //$("#verDoc").show();
            //document.getElementById("verDoc").setAttribute("src", "");
            $("#verImg").attr('src','');
            $("#verImg").hide();
            
            pr = JSON.parse(data);
            var $id = pr.data[0].id_prospectos;
            var $name = pr.data[0].nombre_archivo;
            var $idDoc = pr.data[0].id_documento;
            var $com = pr.data[0].comentario;
            
            
            setTimeout(function(){ 
            if($idDoc == 7){
                $("#verImg").hide();
                var $dir = '../app/lista_documentos/'+$id+'/'+$name;
                canvas($dir);
                $("#pdf_renderer").show();
                //$("#verDoc").attr('src','../app/lista_documentos/Identificaciones/'+$name);
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);
                //$("#name").val($name);
                //$("#verDoc").show();
            }
            if($idDoc == 8){
                $("#verImg").hide();
                var $dir = '../app/lista_documentos/'+$id+'/'+$name;
                canvas($dir);
                $("#pdf_renderer").show();
                //$("#verDoc").attr('src','../app/lista_documentos/Identificaciones/'+$name);
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);
                //$("#name").val($name);
                //$("#verDoc").show();
            }
            if($idDoc == 2){
                $("#verImg").hide();
                var $dir = '../app/lista_documentos/'+$id+'/'+$name;
                canvas($dir);
                $("#pdf_renderer").show();
                //$("#verDoc").attr('src','../app/lista_documentos/Actas_nacimiento/'+$name);
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);
                //$("#name").val($name);
                //$("#verDoc").show();
            }
            if($idDoc == 3){
                $("#verImg").hide();
                var $dir = '../app/lista_documentos/'+$id+'/'+$name;
                canvas($dir);
                $("#pdf_renderer").show();
                //$("#verDoc").attr('src','../app/lista_documentos/Curp/'+$name);
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);
                //$("#name").val($name);
                //$("#verDoc").show();
            }
            if($idDoc == 4){
                $("#verImg").hide();
                var $dir = '../app/lista_documentos/'+$id+'/'+$name;
                canvas($dir);
                $("#pdf_renderer").show();
                //$("#verDoc").attr('src','../app/lista_documentos/Comprobante_estudios/'+$name);
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);
                //$("#name").val($name);
                //$("#verDoc").show();
            }
            if($idDoc == 5){
                $("#pdf_renderer").hide();
                //$("#verDoc").hide();
                $("#verImg").attr('src','../app/lista_documentos/'+$id+'/'+$name);
                //$("#nom").val($name);
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);
                //$("#name").val($name);
                $("#verImg").show();
                //document.getElementById("verDoc").removeAttribute("src", "");
            }
            if($idDoc == 6){
                $("#pdf_renderer").hide();
                //$("#verDoc").hide();
                $("#verImg").attr('src','../app/lista_documentos/'+$id+'/'+$name);
                //$("#nom").val($name);
                //$("#pdf_renderer").hide();
                $("#comentario").val($com);
                $("#idDocument").val($idDoc);
                //$("#name").val($name);
                $("#verImg").show();
                //document.getElementById("verDoc").removeAttribute("src", "");
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
                    text: 'Adjunta un formato correcto o verifica que tu archivo no sobrepase los 2MB.',
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
                    }
                    if(pr.data[i].id_documento == 8){
                        $("#8").prop('disabled', true);
                    }
                    if(pr.data[i].id_documento == 2){       
                        $("#2").prop('disabled', true);
                    }
                    if(pr.data[i].id_documento == 3){
                        $("#3").prop('disabled', true);
                    }
                    if(pr.data[i].id_documento == 4){    
                        $("#4").prop('disabled', true);
                    }
                    if(pr.data[i].id_documento == 5){
                        $("#5").prop('disabled', true);
                    }
                    if(pr.data[i].id_documento == 6){
                        $("#6").prop('disabled', true);
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

