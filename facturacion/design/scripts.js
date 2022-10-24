let items = new Array();
const formatter = new Intl.NumberFormat('en-MX', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2
});

let Validate_rfc = '';

$(document).ready(()=>{
    link = location.pathname.includes('facturaciones.php');
    if(link){
        cargarDatosAfFac();
        cargarFacturas();
        saveDataAfilliationer()
    }else{
        saveBilling();
        showXBilling('poraprobar');
    }
    $('#changeAct').on('change',function(){
        var action = $('#changeAct').val()

        if(action == 'persona fisica'){
            $('#rfc').attr('maxlength','13');
            $('#rfc').attr('minlength','13');
        }else{
            $('#rfc').attr('maxlength','12');
            $('#rfc').attr('minlength','12');
        }
        showRegim(action,'');
    });

    $('#reg').on('change',function(){
        var action = $(this).val();
        changeCFDI(action);
    });    


    $('#onchangeNat').on('change',function(){
        var vls = $(this).val()
        obtenerEstados(vls,'','');
        $("#city").html("");
        $('#dataShow').removeClass('hidden');
        if(vls == 'Mexico'){
            $('#idf').addClass('hidden');
            $('#rfc').val("");
            $('#rfc').attr("readonly", false);
            $('#select_Pf_Pm').removeClass('hidden');
            $('#select_Pf_Pm select').attr('required','required');
            $('#changeAct').attr('required','required');
            $('#reg').attr('required','required');
            $('#ConsFis').removeClass('hidden');
            $('#pdf').attr('required','required');
            $('#idf input').removeAttr('required');
            $('#idf input').val('');
            $('#info_datos').removeClass('hidden');
            $('#info_nombre').removeClass('hidden');
            $('#div_cfdi').removeClass('hidden');
        }else{
            $('#ConsFis').addClass('hidden');
            $('#pdf').removeAttr('required');
            $('#changeAct').val('');
            $('#reg').val('');
            $('#changeAct').removeAttr('required');
            $('#reg').removeAttr('required');
            $('#idf').removeClass('hidden');
            $('#idf input').attr('required','required');
            $('#idf input').val('');
            $('#rfc').val("XEXX010101000");
            $('#rfc').attr("readonly", true);
            $('#select_Pf_Pm').addClass('hidden');
            $('#info_datos').addClass('hidden');
            $('#info_nombre').addClass('hidden');
            $('#div_cfdi').addClass('hidden');
        }
    });
    $('#changed').on('click',function(){
        $(this).attr('disabled','disabled');
        changeResquest();
    });
    $('li.btn-gin').on('click',function(){
        var local =$(this); 
        var option = '';
        $('.btn-gin').removeClass('active');
        local.addClass('active');
        option = local.text().replace(/ /g, "").toLowerCase();

        //console.log(option);
        if(option == 'solicitudesdecambios' || option == 'poraprobar'){
            showXBilling(option);
        }else{
            cargarDatosFactura(option);
        }
    });
    $('.approved').on('click','button.rounded-circle',function(){
        var id = $(this).attr('id'),
            card = $('.approved .body-'+id);
        if(card.hasClass('hidden')){
            $('.approved .card-body').addClass('hidden');
            card.removeClass('hidden');
        }else{
            $('.approved .card-body').addClass('hidden');
        }
    });
    $('.modal').on('click','.btn-closed',function(){
        $('.declined').removeClass('active');
        $('#sendBilling').removeClass('active');
        $('#conFac').html('');
        $('#idConst').val('');
        $('#DataFact').removeClass('active');
        $('.modal.viewer').removeClass('active');
        $('#pdf').val('');
        $('#previewsFact').removeClass('active');
        $('body').removeClass('no-scroll');
        var myCanvas=document.getElementById("previewPDF");
        var myCanvas1=document.getElementById("previewBills");
        //var context=myCanvas.getContext("2d");
        myCanvas.width= 300;
        myCanvas.height = 0;
        myCanvas1.width=300;
        myCanvas1.height = 0;


        if($('.checked_box')){
            $('.checked_box').each(function(){
                $(this).prop('checked',false);
            });
        }

    });
    $('.approved').on('click','.btn-declined',function(){
        var id = $(this).attr('id');
        $('.declined').addClass('active');
        $('body').addClass('no-scroll');
        $('#idConst').val(id);
    });
    $('.approved').on('click','.btn-success',function(){
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Aprobar solicitud',
            html:
            '¿Tu validación es correcta?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Aprobar',
            cancelButtonText: 'Revisar de nuevo',
        }).then((result) => {
            if (result.value == true) {
                
                saveApproved('aprobado',id,'','');
            }
        });
    });
    $('#btn-submit').on('click',function(e){
        e.preventDefault();

        var $status  = ''; 
        $spaceW = $('#reasonTyping').val().replace(/\s/g, '');

        if($spaceW != ''){
            $status = $('#reasonTyping').val();
        }else{
            $status = $('#reason').val();
        }
       
        $id = $('#idConst').val();

        //console.log($status+'-'+$spaceW+'-'+$('#reason').val());

        if($status != null || $status != ''){
            saveApproved('rechazado',$id,$status,'');
            document.getElementById('declined').reset();
        }else{
            Swal.fire({
                title: '',
                html:
                'Debes seleccionar un motivo de rechazo.',
                type: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#2826aa',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Revisar de nuevo',
            }) 
        }
    });
    $('#reason').on('click',function(){
        $('#reasonTyping').val('');
        $('#btn-submit').removeAttr('disabled');
    })
    $('#reasonTyping').on('click',function(){
        $('#reason').val('');
        $('#btn-submit').removeAttr('disabled');
    });
    $('.changeRes').on('click','button',function(){
        var id = $(this);

        if(id.text() == 'Aceptar'){
            $status = 'aprobado'; 
        }else{
            $status = 'rechazado';
        }

        saveApproved('',id.attr('id'),'',$status);
    });
    $('#globals').on('click',function(){
        $('#sendBilling').addClass('active');
        $('body').addClass('no-scroll');
    });
    
    $('#viewer').on('click',function(){

        var cont = $(this).attr('id');

        // $('.modal.'+cont).addClass('active');
        $('#modelId').modal('show');
        $('body').removeClass('no-scroll');

        pdfjsLib.getDocument('../../facturacion/design/img/csf_ejemplo.pdf').then(doc =>{
            //console.log("this file has"+doc._pdfInfo.numPages+"pages");

            // doc.getPage(1).then(page=>{
            // var myCanvas=document.getElementById("my_canvasexample");
            // var context=myCanvas.getContext("2d");    
            // var viewport=page.getViewport(1);
            // myCanvas.width=viewport.width;
            // myCanvas.height=viewport.height;
        
            //     page.render({
            //     canvasContext:context,
            //     viewport:viewport
            //     });
            // });
        });
    });
    $('#pdf').on('change',function(){

        lengthPdf = $(this).prop('files')[0];
        var url = URL.createObjectURL(lengthPdf);

        // pdfjsLib.getDocument(url).then(doc =>{
        //     //console.log("this file has"+doc._pdfInfo.numPages+"pages");

        //     doc.getPage(1).then(page=>{
        //     var myCanvas=document.getElementById("previewPDF");
        //     var context=myCanvas.getContext("2d");    
        //     var viewport=page.getViewport(1);
        //     myCanvas.width=viewport.width;
        //     myCanvas.height=viewport.height;
        
        //         page.render({
        //         canvasContext:context,
        //         viewport:viewport
        //         });
        //     });
        // });
    });
    $('#deleteFcts').on('submit',function(e){
        e.preventDefault();

        $('#previewsFact').removeClass('active');
        $('body').removeClass('no-scroll');
        fdata = new FormData(this);
        fdata.append('action','deleteBill');
        $.ajax({
            type: "POST",
            url: "../facturacion/app/CData/facturacionControl.php",
            data:fdata,
            contentType:false,
            processData:false,
            success: function(data){
    
                console.log(data);
    
                resp = JSON.parse(data);
    
                if(resp.estatus == 'ok'){
                    Swal.fire({
                        title: '',
                        html:
                        'Factura eliminada ahora la puedes enviar de nuevo desde Por facturar.',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        if (result.value == true) {
                            cargarDatosFactura('facturado');
                        }
                    });
                   

                }else{  
                    Swal.fire({
                        title: '',
                        html:
                        'Algo salio mal intenta más tarde.',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }) 
                }
            }
            });
    });
    $("#cp,numberD,numberI").on('input', function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
});

function changeCFDI(action,value){

    console.log(action);
    $.ajax({
        type: "POST",
        url: "../../facturacion/app/CData/processUsos.php",
        data: { usos : action } 
        }).done(function(data){
            // console.log(data)
        $("#cfdi").html(data);

         var child = $("#cfdi").children().eq(0);
         if(value != '' && value != null && value != undefined){
            //console.log(chan);
             $('#cfdi').val(value);             
         }else{
            child.attr('selected','selected');
         }
    });
}
function changeResquest(){
    $.ajax({

        url:'../../facturacion/app/CData/facturacionControl.php',
        type:'POST',
        data:{action: "changeResq"},
        success: function(data){
            
            console.log(data);
            resp = JSON.parse(data);
            
            if(resp.estatus == "ok"){
                Swal.fire({
                    title: '',
                    html:
                    'Envío correctamente, en breve un ejecutivo aprobará tu solicitud.',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#0866C6',
                    //cancelButtonColor: '#0074B5',
                    confirmButtonText: 'Aceptar',
                    // cancelButtonText: 'Continuar viendo perfiles'
                })
            }else{
                Swal.fire({
                    title: '',
                    html:
                    'Error de conexión intenta más tarde.',
                    type: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#0866C6',
                    //cancelButtonColor: '#0074B5',
                    confirmButtonText: 'Aceptar',
                    // cancelButtonText: 'Continuar viendo perfiles'
                }) 
            }
            
        }
    })
}
function saveDataAfilliationer(){
    
    $('#form-billing').on("submit",function(e){
        e.preventDefault();
        
        pdf = $('#pdf').prop('files')[0];
        fdata = new FormData(this)
        fdata.append('pdf',pdf);
        fdata.append('action',"subirDatos");
        var ruta = "../../facturacion/app/CData/facturacionControl.php"
        
        $.ajax({
            url: ruta,
            type: "POST",
            data: fdata,
            contentType:false,
            processData:false,
            success: function(data){
    
               //console.log(data)
                resp = JSON.parse(data)
                if(resp.estatus == 'ok'){
                Swal.fire({
                    title: '',
                    html:
                    'Envío correctamente, en breve un ejecutivo aprobará tu solicitud.',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#0866C6',
                    //cancelButtonColor: '#0074B5',
                    confirmButtonText: 'Aceptar',
                    // cancelButtonText: 'Continuar viendo perfiles'
                })
                document.getElementById('form-billing').reset();
                cargarDatosAfFac()
                }else{
                    Swal.fire({
                        title: '',
                        html:
                        'Error con de conexión, intenta de nuevo en otro momento.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#0866C6',
                        //cancelButtonColor: '#0074B5',
                        confirmButtonText: 'Aceptar',
                        // cancelButtonText: 'Continuar viendo perfiles'
                    })
                }
            }    
        });
        
    });
    
    
}
function saveBilling(){

    $('#formularioFactura').on("submit",function(e){
        e.preventDefault();
    
        $('#sendBilling').removeClass('active');

        fdata = new FormData(this)
        pdf = $('#pdf').prop('files')[0];
        xml = $('#xml').prop('files')[0];
        fdata.append('action',"subirFactura");
        fdata.append('pdf',pdf);
        fdata.append('xml',xml);
    
        var ruta = "../facturacion/app/CData/facturacionControl.php"
        
        $.ajax({
            url: ruta,
            type: "POST",
            data: fdata,
            contentType:false,
            processData:false,
            success: function(data){
    
                //console.log(data)
                resp = JSON.parse(data)
                if(resp.estatus == 'ok'){

                    Swal.fire({
                        title: '',
                        html:
                        'Factura enviada exitosamente',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        cargarDatosFactura('porfacturar');
                        $('body').removeClass('no-scroll');
                        $('#conFac').html('');
                        $('#formularioFactura')[0].reset();
                        var myCanvas=document.getElementById("previewPDF");
                        myCanvas.height = 0;
                        myCanvas.width = 300;    
                    });
                
                }else{
                    Swal.fire({
                        title: '',
                        html:
                        'Error de conexión actualiza tu información',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aprobar',
                        cancelButtonText: 'Revisar de nuevo',
                    })
                    $('body').removeClass('no-scroll');
                }
            }    
        });
    })
}
function target_id(id,idu){
    $('#sendBilling').addClass('active');
    
    cont = $('#conFac');

    inputs = '<input type="hidden" name="idPayment[]" id="idPayment-'+id+'" value="'+id+'">'+
                '<input type="hidden" name="idProspecto[]" id="idProspecto-'+idu+'" value="'+idu+'">';
    cont.html(inputs);
}
function facGlobals(id,idu, vrfc){

    var gl = $('#globals');
    i = 0;
    
    cont = $('#conFac');
    $('.checked_box').each(function(elem){

        if($(this).is(":checked")){
            i++;
            var vls = $(this).attr('id');
            $('#btn-'+vls).css('display','none');
        }else{
            var vls = $(this).attr('id');
            $('#btn-'+vls).css('display','block');
        }
    })

    inputs = '<input type="hidden" name="idPayment[]" id="idPayment-'+id+'" value="'+id+'">'+
                '<input type="hidden" name="idProspecto[]" id="idProspecto-'+idu+'" value="'+idu+'">';
    if(!document.body.contains(document.getElementById("idPayment-"+id))){
    cont.append(inputs);
    }else{
        document.getElementById("conFac").removeChild(document.getElementById("idPayment-"+id));
        document.getElementById("conFac").removeChild(document.getElementById("idProspecto-"+idu));
    }
    if(i == 0){
        Validate_rfc = '';
    }

    $('.checked_box').each(function(elem){

        if($(this).is(":checked")){
            i++;
            var vls = $(this).attr('id');
            $('#btn-'+vls).css('display','none');
        }else{
            var vls = $(this).attr('id');
            $('#btn-'+vls).css('display','block');
        }
    })
    if(i > 1){
        Validate_rfc = vrfc;
        gl.css('opacity','1');
        gl.removeAttr('disabled');
    }else{
        gl.css('opacity','.5');
        gl.attr('disabled','disabled');
    }  
}
function showData(idu){
    
    $option = 'poraprobar';
    showXBilling($option,idu);
}
function deleteFac(idF,idP,link){
    $('#previewsFact').addClass('active');

    $('#deletefacts').val(idF);
    url = '../facturacion/app/lista_facturacion/'+idP+'/'+link
    pdfjsLib.getDocument(url).then(doc =>{
        //console.log("this file has"+doc._pdfInfo.numPages+"pages");

        doc.getPage(1).then(page=>{
        var myCanvas=document.getElementById("previewBills");
        var context=myCanvas.getContext("2d");    
        var viewport=page.getViewport(1);
        myCanvas.width=viewport.width;
        myCanvas.height=viewport.height;
    
            page.render({
            canvasContext:context,
            viewport:viewport
            });
        });
    });
}
function cargarDatosAfFac(){

    var ruta = "../../facturacion/app/CData/facturacionControl.php"
    fdata = new FormData()
    fdata.append('action',"cargarDatosAfFac");

    $.ajax({
        url: ruta,
        type: "POST",
        data: fdata,
        contentType:false,
		processData:false,
        success: function(data){

            //console.log(data);

            resp = JSON.parse(data)

                console.log(resp);   
            
            if(resp.estatus == 'ok'){

                //console.log(resp.data.length);   
                $('#onchangeNat').val(resp.data[0].nationality);
                $('#dataShow').removeClass('hidden');  

                if(resp.data[0].change_request == 'pendiente'){
                    $('#inputEnable input').prop('checked',true);
                }
                
                if(resp.data[0].nationality == 'Mexico'){
                    $('#idf').addClass('hidden');
                    $('#rfc').val("");
                    $('#rfc').attr("readonly", false);
                    $('#select_Pf_Pm').removeClass('hidden');
                    $('#select_Pf_Pm select').attr('required','required');
                    $('#ConsFis').removeClass('hidden');
                    $('#pdf').attr('required','required');
                    $('#div_cfdi').removeClass('hidden');
                }else{
                    $('#ConsFis').addClass('hidden');
                    $('#pdf').removeAttr('required');
                    $('#idf').removeClass('hidden');
                    $('#rfc').val("XEXX010101000");
                    $('#rfc').attr("readonly", true);
                    $('#select_Pf_Pm').addClass('hidden');
                    $('#div_cfdi').addClass('hidden');
                }

               
                if(resp.data[0].activity != ''){
                    $('#changeAct').val(resp.data[0].activity);
                    if(resp.data[0].activity == 'persona fisica'){
                        $('#rfc').attr('maxlength','13');
                        $('#rfc').attr('minlength','13');
                    }else{
                        $('#rfc').attr('maxlength','12');
                        $('#rfc').attr('minlength','12');
                    }
                    showRegim(resp.data[0].activity,resp.data[0].regimen,resp.data[0].change_request,resp.data[0].status_data);
                    changeCFDI(resp.data[0].regimen,resp.data[0].uso_cfdi);
                }
                obtenerEstados(resp.data[0].nationality,resp.data[0].estado,resp.data[0].ciudad,resp.data[0].change_request,resp.data[0].status_data);
                               
                $('#name').val(resp.data[0].nombre_rz);
                $('#rfc').val(resp.data[0].rfc)
                $('#street').val(resp.data[0].calle)
                $('#numberD').val(resp.data[0].numero_ext)
                $('#numberI').val(resp.data[0].numero_int)
                $('#bd').val(resp.data[0].colonia)
                $('#cp').val(resp.data[0].cp)
                $('#city').val(resp.data[0].ciudad)
                $('#email').val(resp.data[0].email)
                $('#idfiscal').val(resp.data[0].id_fiscal)
                $('#pob').val(resp.data[0].poblacion)

                if(resp.data[0].status_data == 'rechazado'){
                    Swal.fire({
                        title: 'Datos Incorrectos',
                        html:
                        'Tus datos han sido declinados, corríge y vuelve a enviarlos. <b>Motivo</b>: '+resp.data[0].reason_of_rejection,
                        type: 'info',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    })

                }else if(resp.data[0].change_request == '' || resp.data[0].change_request == 'pendiente'){
                    
                    $('#inputEnable').removeClass('hidden');
                    $.each($('form').serializeArray(), function(index, value){
                        $('[name="' + value.name + '"]').attr('readonly', 'readonly');
                        $('[name="' + value.name + '"]').attr('disabled', 'disabled');
                    })
    
                    $('#btn_save').addClass('d-none')
                    $('#pdf').attr('disabled','disabled');
                }

            }else{
                
            }
        }    
    });

}
function cargarDatosFactura($option){

    $('.approved').html('');
    $('.approved').addClass('hidden');
    $('.changeRes').removeClass('hidden');
    $('.changeRes').html('');

    $('.out-billing').removeClass('hidden');
    
    if($option == 'facturado'){
        $('#globals').addClass('hidden');
    }else{
        $('#globals').removeClass('hidden');
    }
    var url = '';
    var names = new Array();
    var table =  $("#datatable-subirfactura").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[
            /*{extend:"copy",
            className: "btn-success"
        },*/
        {
            extend: "excel",
            className: "btn-download",
            exportOptions: {
                columns: [ 1,2,3,4,5,6,7,8,9,10,11,12],
                modifier: {
                    page: 'current'
                }
            },
            footer: true
        }, {
            extend: "pdf",
            className: "btn-download",
            orientation: 'landscape',
            exportOptions: {
                columns: [ 1,2,3,4,5,6,7,8,9,10,11,12],
                modifier: {
                    page: 'current'
                }
            },
            footer: true,
        }, {
            extend: "print",
            className: "btn-download",
            exportOptions: {
                columns: [ 1,2,3,4,5,6,7,8,9,10,11,12],
                modifier: {
                    page: 'current'
                }
            },
            footer: true
        }],

        "ajax": {
            url: '../facturacion/app/CData/facturacionControl.php',
            type: 'POST',
            data: {action: 'datosPagos',option: $option},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);	
                /*if(e.responseText == 'no_session'){
                    swal({
                        title: "Vuelve a iniciar sesión!",
                        text: "La informacion no se actualizó",
                        icon: "info",
                    });
                    setTimeout(function(){
                        window.location.replace("index.php");
                    }, 2000);
                }*/
            },
            dataSrc: function(json){
                //console.log(json);
                names = json.dataNames;
                $('.totals').html('<b>Total de pagos en pesos</b>: $'+json.data2);
                $('.totalsD').html('<b>Total de pagos en dolares</b>: $'+json.data3);
                $('body').removeClass('no-scroll');
                $('#conFac').html('');
                return json.aaData
            },
        },

        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
 
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;              
            };
  
            // Total over all pages
 
            if (api.column(11).data().length){
            var total = api
            .column(  )
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            } ) }
            else{ total = 0};
                 
            // Total over this page
             
            if (api.column(11).data().length){
            var pageTotal = api
                .column( 11, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                } ) }
                else{ pageTotal = 0};
  
            // Update footer
            $( api.column(11).footer() ).html(
                'Total: $'+pageTotal+' MXN'
                
            );
            
            if (api.column(12).data().length){
            var totalD = api
            .column(  )
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            } ) }
            else{ totalD = 0};
            
             if (api.column(12).data().length){
            var pageTotalD = api
                .column( 12, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                } ) }
                else{ pageTotalD = 0};
            
             // Update footer
            $( api.column(12).footer() ).html(
                'Total: $'+pageTotalD+' USD'
                
            );
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
        'iDisplayLength': 11,
        'order':[
            [0,'asc']
        ],
        
    });
}
function cargarFacturas(){

    var url = '';
    table =  $("#tabla_facturas_desc").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "dom" :'Bfrtip',
    buttons:[{
        /*extend:"copy",
        className: "btn-success"
    },{
        extend: "csv"
    }, {*/
        extend: "excel",
        className: "btn-primary"
    /*}, {
        extend: "pdf"
    }, {
        extend: "print"*/
    }],
    "ajax": {
        url: '../../facturacion/app/CData/facturacionControl.php',
        type: 'POST',
        data: {action: 'datosFactura'},
        dataType: "JSON",
        error: function(e){
            console.log(e.responseText);	
            /*if(e.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }*/
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
    'iDisplayLength': 11,
    'order':[
        [0,'asc']
    ]
    });
}
function obtenerEstados(vls,state,city,chan,st){

    // Obtener estados
    $.ajax({
        type: "POST",
        url: "../../facturacion/app/CData/processStates.php",
        data: { estados : vls } 
        }).done(function(data){
            // console.log(data)
        $("#state").html(data);
         var child = $("#state").children().eq(0);
         $("#state").val(state);
         if(state != ''){
            if(st != 'rechazado'){
                if(chan != 'aprobado' && chan != undefined){
                    $("#state").attr('disabled','disabled');
                 }
            }
            
         }else{
            child.attr('selected','selected');
         }
         obtenerCiudad(state,city,chan,st);
        });
        // Obtener municipios
        $("#state").change(function(){
            obtenerCiudad($(this).val(),'')
        });
}
function obtenerCiudad(state,city,chan,st){
    
    $.ajax({
    type: "POST",
    url: "../../facturacion/app/CData/processStates.php",
    data: { municipios : state } 
    }).done(function(data){
        //console.log(data);
        $("#city").html(data);
        $("#city").val(city);
        var child = $("#city").children().eq(0);
      
        if(city != ''){
            if(st != 'rechazado'){
                if(chan != 'aprobado' && chan != undefined){
                    $('#city').attr('disabled', 'disabled');
                }
            }
        }else{
           
            child.attr('selected','selected');
        }
    });
}
function showRegim(action,regmn,chan,st){
   
    $.ajax({
        type: "POST",
        url: "../../facturacion/app/CData/processRegim.php",
        data: { regimen : action } 
        }).done(function(data){
            // console.log(data)
        $("#reg").html(data);
        //$("#usos").html(data);
         var child = $("#reg").children().eq(0);
         if(regmn != ''){
            //console.log(chan);
             $('#reg').val(regmn);
             if(st != 'rechazado'){
                if(chan != 'aprobado' && chan != undefined){
                    $('#reg').attr('disabled','disabled');
                 }
             }
             
         }else{
            child.attr('selected','selected');
         }
        });
}
function showXBilling($option,idu){
    if(idu == undefined){
        $('.out-billing').addClass('hidden');
    $('.out-billing table tbody').html('');
    }
    
    $('.approved').html('');
    $('.approved').addClass('hidden');
    $('.changeRes').removeClass('hidden');
    $('.changeRes').html('');
    fdata = new FormData()
    fdata.append('action',"dataBillUS");
    fdata.append('option',$option);
    fdata.append('idU',idu);

    $.ajax({
        type: "POST",
        url: "../facturacion/app/CData/facturacionControl.php",
        data:fdata,
        contentType:false,
		processData:false,
        success: function(data){
        
            console.log(data)
            resp = JSON.parse(data);
            //console.log(resp);

            var html  = "",
            $act = '',
            $reg = '';


            if(idu != undefined){
                
                $name = resp[0].nombre+' '+resp[0].aPaterno+' '+resp[0].aMaterno;
                $link = resp[0].link_conts;
                html = '<div class="row"><div class="col-sm-4">'+
                '<p><b>Nombre</b>:'+$name+'</p>'+
                '<p><b>Razón social</b>: '+resp[0].nombre_rz+'</p>'+
                '<p><b>Direccion</b>: '+resp[0].calle+' '+resp[0].numero_ext+' '+resp[0].numero_int+' '+resp[0].poblacion+' '+resp[0].colonia+' '+resp[0].ciudad+' '+resp[0].estado+' '+resp[0].cp+'</p>'+
                '<p><b>Email</b>: '+resp[0].email+'</p>'+
                '<p><b>RFC</b>: '+resp[0].rfc+'</p>';

                if(resp[0].nationality != 'Mexico'){
                    $nat = 'USA';
                }else{
                    $nat = 'México';
                }

                html += '<p><b>Nacionalidad</b>: '+$nat+'</p>';

                if(resp[0].activity != ''){
                    $act = resp[0].activity;
                    $reg = resp[0].regimen;
                }else{
                    $act = 'No aplica';
                    $reg = 'No aplica';
                }
                if(resp[0].id_fiscal != ''){
                $fscl = resp[0].id_fiscal;
                }else{
                    $fscl = 'No aplica';
                }
                html += '<p><b>ID fiscal</b>: '+$fscl+'</p>'+
                        '<p><b>Tipo de persona</b>: '+$act+'</p>'+
                        '<p><b>Regimen fiscal</b>: '+$reg+'</p>'+
                        '<p><b>Uso de CFDI</b>: '+resp[0].uso_cfdi+'</p>'+
                        '</div><div class="col-sm-8"><a href="'+$link+'" download>'+
                        '<iframe name="iframe" src="'+$link+'" style="width: 100%;height: 100%;"></iframe>'+
                        '</a></div>'+
                        '</div><button class="btn-closed btn-danger btn-plus d-block ms-auto">Cerrar</button>';


                // pdfjsLib.getDocument($link).then(doc =>{
                //     //console.log("this file has"+doc._pdfInfo.numPages+"pages");
    
                //     doc.getPage(1).then(page=>{
                //     var myCanvas=document.getElementById("my_canvas"+0);
                //     var context=myCanvas.getContext("2d");    
                //     var viewport=page.getViewport(1);
                //     myCanvas.width=viewport.width;
                //     myCanvas.height=viewport.height;
                
                //         page.render({
                //         canvasContext:context,
                //         viewport:viewport
                //         });
                //     });
                // });

                $('#showDatasBill').html(html);
                $('#DataFact').addClass('active');
                $('body').addClass('no-scroll');
            }else{
                if(resp != ''){
                    switch($option){
                        case 'poraprobar':
                            $('.approved').removeClass('hidden');
                            $.each(resp, function(i , elem){
                                $name = elem.nombre+' '+elem.aPaterno+' '+elem.aMaterno;
                                $link = elem.link_conts;
                                html += '<div class="card">'+
                                '<div class="card-header">'+
                                '<p class="d-inline-block m-0"><b>Nombre</b>: '+$name+'</p>';
                                if(elem.status_data != 'rechazado'){
                                    html += '<button class="d-inline-block float-end btn-plus rounded-circle" id="'+i+'" type="button">+</button>';
                                }else{
                                    html += '<p>En espera de correción y se envien datos correctos.</p>';
                                }
                            
                                html += '</div>'+
                                '<div class="card-body p-2 hidden body-'+i+'"><div class="row"><div class="col-sm-4">'+
                                '<p><b>Razón social</b>: '+elem.nombre_rz+'</p>'+
                                '<p><b>Direccion</b>: '+elem.calle+' '+elem.numero_ext+' '+elem.numero_int+' '+elem.colonia+' '+elem.poblacion+' '+elem.ciudad+' '+elem.estado+' '+elem.cp+'</p>'+
                                '<p><b>Email</b>: '+elem.email+'</p>'+
                                '<p><b>RFC</b>: '+elem.rfc+'</p>';
                                
                                if(elem.nationality != 'Mexico'){
                                    $nat = 'USA';
                                }else{
                                    $nat = 'México';
                                }

                                html += '<p><b>Nacionalidad</b>: '+$nat+'</p>';
                
                                if(elem.activity != ''){
                                    $act = elem.activity;
                                    $reg = elem.regimen;
                                }else{
                                    $act = 'No aplica';
                                    $reg = 'No aplica';
                                }
                                if(elem.id_fiscal != ''){
                                    $fscl = elem.id_fiscal;
                                    }else{
                                        $fscl = 'No aplica';
                                    }

                                html += '<p><b>ID fiscal</b>: '+$fscl+'</p>'+
                                '<p><b>Tipo de persona</b>: '+$act+'</p>'+
                                '<p><b>Regimen fiscal</b>: '+$reg+'</p>'+
                                '<p><b>Uso de CFDI</b>: '+elem.uso_cfdi+'</p>'+
                                '<button type="button" class="btn-success border-0 me-2 p-2" id="'+elem.id+'">Aceptar</button>'+
                                '<button type="button" class="btn-danger border-0 p-2 btn-declined" id="'+elem.id+'">Declinar</button>'+
                                '</div><div class="col-sm-8"><a href="'+$link+'" download>'+
                                '<iframe name="iframe" src="'+$link+'" style="width: 100%;height: 100%;"></iframe>'+
                                '</a></div>'+
                                '</div></div>'+
                                ''+
                                '</div>';
                
                                // pdfjsLib.getDocument($link).then(doc =>{
                                //     //console.log("this file has"+doc._pdfInfo.numPages+"pages");
                    
                                //     doc.getPage(1).then(page=>{
                                //     var myCanvas=document.getElementById("my_canvas"+i);
                                //     var context=myCanvas.getContext("2d");    
                                //     var viewport=page.getViewport(1);
                                //     myCanvas.width=viewport.width;
                                //     myCanvas.height=viewport.height;
                                
                                //         page.render({
                                //         canvasContext:context,
                                //         viewport:viewport
                                //         });
                                //     });
                                // });
                            });
                            $('.approved').html(html);
                        break;
                        case 'solicitudesdecambios':
                            $('.changeRes').removeClass('hidden');
                            $.each(resp, function(i , elem){
                                $name = elem.nombre+' '+elem.aPaterno+' '+elem.aMaterno;
                                $link = elem.link_conts;
                                html += '<div class="card">'+
                                '<div class="card-header">'+
                                '<p class="d-inline-block m-0"><b>Nombre</b>: '+$name+'</p>'+
                                '</div>'+
                                '<div class="card-body p-2">';

                                if(elem.change_request != 'pendiente'){
                                    html +='<p>Se aplicarán correciones a datos fiscales, en espera de que se envien los datos.</p>';
                                }else{
                                    html +='<button class="d-inline-block float-end btn-success border-none" id="'+elem.id+'" type="button">Aceptar</button>';
                                }
                                html +='</div>'+
                                ''+
                                '</div>';
                            });
                            $('.changeRes').html(html);
                        break;
                        case 'default':
                        break;
                    }
                }else{
                    html = "";
                    if($option == 'poraprobar'){
                        $('.approved').removeClass('hidden');
                        $('.approved').html(html);
                    }else{
                        $('.changeRes').removeClass('hidden');
                        $('.changeRes').html(html); 
                    }
                }
            }

            

            
        },
    });

}
function saveApproved(status,id,reason,changed){    


    fdata = new FormData()
    fdata.append('action',"saveStatus");
    fdata.append('statusF',status);
    fdata.append('idTable',id);
    fdata.append('reason',reason);
    fdata.append('changeR',changed);
    $.ajax({
        type: "POST",
        url: "../facturacion/app/CData/facturacionControl.php",
        data:fdata,
        contentType:false,
		processData:false,
        success: function(data){

               console.log(data);

            resp = JSON.parse(data);

            if(resp.estatus == 'ok'){

                
                Swal.fire({
                    title: '',
                    html:
                    'Se ha notificado del estatus',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#2826aa',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Revisar de nuevo',
                }).then((result) => {
                    if (result.value == true) {
                        
                        location.reload();
                    }
                });
                


            }else{  
                Swal.fire({
                    title: '',
                    html:
                    'Algo salio mal intenta más tarde.',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#2826aa',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Revisar de nuevo',
                }) 
            }
        }
        });
}
