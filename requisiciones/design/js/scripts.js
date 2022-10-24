let  i = 0;
var listedUsers = [];
var listedProveedores = [];
var link = false;
var band = $('#band').val();
const formatter = new Intl.NumberFormat('en-MX', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2
});

$(document).ready(function() {
    link = location.pathname.includes('contabilidad.php');
    linkE = location.pathname.includes('ejecutivo.php');
    saveFile();
    if(link){
        if(band != 4){
            showRequest('pendientes',2);
        }else{
            showRequest('aprobadas',2);
        }
        
        createElement();
    }else if(linkE){
        addConcepts();
        subirReq();
        saveSeries();
    }

    if(window.location.pathname.includes('gestorUsuarios')){
        tablaUsuarios();
        $('#clickModal').on('click', function(){
            selectDepto();
            $('#CustomLabel').text('Agregar Usuario');
            $('#addU')[0].reset();
        });
    }

    $("#modalAgregausers .close").on("click", function(){
        $("#modalAgregausers").modal("hide");
    });

    if(window.location.pathname.includes('proveedor')){
        subirProv();
        tablaProveedores();
        $('#clickModal').on('click', function(){
            $('#CustomLabel').text('Agregar Proveedor');
            $('#editproveedor')[0].reset();
            $('html').addClass('no-scroll');
            obtenerEstados('Mexico','stateE','cityE','','');
        });
    }

    var table =  $("#datatable").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        info: false,
        paging:false,
        searching: false,
        ordering: false,
        "dom" :'Bfrtip',
        buttons:[
            /*{extend:"copy",
            className: "btn-success"
        },*/    
        // {
        //     extend: "excel",
        //     className: "btn-download"
        // }, {
        //     extend: "pdf",
        //     className: "btn-download"
        // }, 
        //{
        //     extend: "print",
        //     className: "btn-download"
        // }
        ],
    });
    $('li.btn-gin').on('click',function(){
        var local =$(this); 
        var option = '';
        $('.btn-gin').removeClass('active');
        local.addClass('active');
        option = local.text().normalize("NFD").replace(/[\u0300-\u036f-\s]/g, "").toLowerCase();

        //console.log(option);

        $('.req').addClass('hidden');
        $('.sreq').addClass('hidden');
        $('.editProv').addClass('hidden');
        $('.admonU').addClass('hidden');
        $('.admonD').addClass('hidden');

        if(option == 'solicitarrequisicion'){
            $('.req').removeClass('hidden');
        }else if(option == 'registrar/editarproveedor'){
            $('.editProv').removeClass('hidden');
            obtenerEstados('Mexico','stateE','cityE','','');
        }else{
            $('.sreq').removeClass('hidden');
            $('#titleReq').text(local.text());
            if(option == 'compararfactura/pago'){
                option = 'facturadas';
            }
            if(link){
                // if(option == 'admonusuarios'){
                //     $('.sreq').addClass('hidden');
                //     $('.admonU').removeClass('hidden');
                //     $('.admonD').addClass('hidden');
                // }else if(option == 'admondepartamentos'){
                //     $('.sreq').addClass('hidden');
                //     $('.admonU').addClass('hidden');
                //     $('.admonD').removeClass('hidden');
                // }else{
                showRequest(option,2)
                //}
            }else{
                if(option == 'afacturar'){
                    option = 'facturadas';
                }
                showRequest(option);
            }
        }
    });
    $('#admU,#admD').on('click','li',function(){
        var local =$(this); 
        var option = '';
        var parent = $(this).parent().attr('id');
        $('#'+parent+' li').removeClass('active');
        local.addClass('active');
        option = local.text().normalize("NFD").replace(/[\u0300-\u036f-\s]/g, "").toLowerCase();

        //console.log(option,parent);

        if(parent == 'admU'){
            $('#addU').addClass('hidden');
            $('#editU').addClass('hidden');
            if(option == 'crear'){
                $('#addU').removeClass('hidden');
            }else{
                $('#editU').removeClass('hidden');
                selectUser();
            }
            
        }else{      
            $('#addD').addClass('hidden');
            $('#editD').addClass('hidden');
            if(option == 'crear'){
                $('#addD').removeClass('hidden');
            }else{
                $('#editD').removeClass('hidden');
                selectDepto();
            }
        }
        
        
    });
    $('#moreConcept').on('click',function(){
       addConcepts();
    });
    $('#datatable').on('click','.btn-danger',function(){
        var id = $(this).attr('id');
        deleteConcepts(id);
     });
    $('#datatable').on('change','.testing',function(){
        id = $(this).attr('id').split('-')[1];
        elem = $(this).attr('id').split('-')[0];
            price = $('#pr-'+id).val();
            cant = $('#cant-'+id).val();
            totals(cant,price,id);    
    });
    $('#datatable').on('click','.listProveedores',function(){

        var id = $(this).attr('id');
        if($(this).val() == 'newA'){
            $('#proveedor').addClass('active');
            obtenerEstados('Mexico','state','city','','');
            $('#idSelect').val(id);
        }
    });
    $('#changeAct,#EdchangeAct').on('change',function(){
        var action = $(this).val();
        var id = $(this).attr('id');

        if(id == 'changeAct'){
            idS = 'reg';
        }else{
            idS = 'Edreg';   
        }

        if(action == 'persona fisica'){
            $('#rfc').attr('maxlength','13');
            $('#rfc').attr('minlength','13');
        }else{
            $('#rfc').attr('maxlength','12');
            $('#rfc').attr('minlength','12');
        }
        showRegim(action,'',idS);
    });
    $('.close').on('click',function(){
        var vls = $(this).attr('id');
        if(vls == 'close_save' && $('#comprRq').hasClass('active')){
            $('input[name="idReq"]').remove();
            $('input[name="numMov"]').remove();
            $('input[name="idComp"]').remove();
        }else if(vls == 'close_comp' && $('#comPayBill').hasClass('active')){
            $('input[name="idReq"]').remove();
        }
        if(vls == 'provNew'){
            $('.listProveedores').val('');
        }
        $('#proveedor').removeClass('active');
        $('#breakdown').removeClass('active');
        $('.sendReq').attr('disabled',true);
        $('.sendReq').css('opacity','.5');
        $('#declined').removeClass('active');
        $('#comprRq').removeClass('active');
        $('#comPayBill').removeClass('active'); 
        $('html').removeClass('no-scroll');
    });
    $('#closeSign').click( function(){ 
        
        $('#breakdown').addClass('active') 
        $('#formSign input[name=idReq]').remove();
        $('#formSign')[0].reset();

    });    
    $('#fileSignApproved').click(function(){
        
        folio = $('#folio').text();
        $('#formSign').append('<input type="hidden" name="idReq" value="'+folio+'"/>');

    });
    $('.sendReq').on('click',function(){
        var $st = $(this).attr('id');


        count = $('.provDrop').length;
        i = 0;
        $('.provDrop').each((i,el)=>{

            console.log(el);
            
            if($(this).val() != '' && $(this).val() != undefined){
                i++;
            }
        });

        if($st == 'approved' && i == count){
            
            if($(this).text() == 'Aprobar'){
                $st = 'aprobada';
            }else{
                $st = 'pagada';
            }
            UpdateRq($st);
            $('#formAdmin').submit();
        }else{
            if($st == 'declined'){
                $('#modalDeclined').removeClass('hidden');
            }else{
                swal('debes seleccionar un proveedor antes de aprobar la solicitud.');
            }
            
        }
        
    });
    $('#sendDeclined').on('click',function(){

        $st = 'rechazada';
        UpdateRq($st);
        $('#formAdmin').submit();
    });
    $('#pdf').on('change',function(){
        lengthFile = $(this).prop('files')[0];
        previewComp(lengthFile);
    });
    $('#datatableBKD').on('change','.input_series',function(){
        cont = $('#formSeries');
        inputs = '<input type="hidden" name="serie[]" value="'+$(this).val()+'">'+
        '<input type="hidden" name="idReq[]" value="'+$(this).attr('id')+'">';
        cont.append(inputs);  
    });
    $('#saveSerie').on('click',function(){
       
        $('#saveSerie').addClass('hidden');
        $('#formSeries').submit();
    });
    $('#datatableBKD').on('click','.input_series',function(){
        $('#saveSerie').removeClass('hidden');
    });
    $('.valuesD').on('click',function(){
        var id = $(this).attr('id');
        changeDocs(id);
        $('#formValidate').submit();
    });
    $('#newSendCom').on('click',function(){
        $('.close').click();
        $('#comprRq').addClass('active');
    });
    $('#users').on('change',function(){
        var ele = $(this);
        console.log(ele);
        $('#users option:selected').each(function(){
            console.log($(this).attr('id'))
        }); 
    });
    $('#valuesDeclined').on('click','input[name=compr]',function(){
    

        $('#compNOK').addClass('hidden');
        $('#compOK').addClass('hidden');

        if($(this).val() != 'correct'){
            $('#compNOK').removeClass('hidden');
            changeDocs('uncorrect');
        }else{
            $('#compOK').removeClass('hidden');
            
        }
    });
    $('#addU').on('submit',function(e){
        e.preventDefault();
        
        $form = new FormData(this);
        $form.append('action','createUs');
    
        $.ajax({
            type: 'POST',
            url: 'app/CData/RequisicionesControl.php',
            data: $form,
            contentType:false,
            processData:false,
            success: function(data){
    
                //console.log(data);
    
                json = JSON.parse(data);
    
                console.log(json);
                if(json.estatus == 'ok'){
                    
                    tablaUsuarios();
                    $('#modalAgregausers .close').click();  
                    Swal.fire({
                        title: '',
                        html:
                        'Información guardada correctamente.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    });
    
                }else{
    
                    Swal.fire({
                        title: '',
                        html:
                        'conexión inestable intenta de nuevo.',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result)=>{
    
                        location.reload(false);
                    });
                }
                
            },
        });
    });
    $('#datatableBKD').on('change','.provDrop',function(){

        $id = $(this).val();
        $text = $(this).text();
        $idR = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: 'app/CData/RequisicionesControl.php',
            data: {action: 'updateProv',idProv: $id,idReq:$idR},
            success: (data)=>{

                console.log(data);

                json = JSON.parse(data);
                if(json.estatus != 'ok'){
                    $(this).val('');
                    swal('tu proveedor no fue actualizado intenta de nuevo.');
                }
            }
        });
    });
});
function createElement(){

    $('#addU,#editU').on('submit',function(e){
        e.preventDefault();

        var id = $('#idSelect').val();

        var $form = new FormData(this);
        $form.append('action','createUs');

        $.ajax({
            url: 'app/CData/RequisicionesControl.php',
            type: 'POST',
            data: $form,
            contentType:false,
		    processData:false,
            success: function(data){
                console.log(data);
                json = JSON.parse(data);

                //console.log(json)
                if(json.estatus == 'ok'){
                    

                    Swal.fire({
                        title: '',
                        html:
                        'Información guardada correctamente.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        if (result.value == true) {
                            location.reload()
                        }
                    });
                }else{
                    Swal.fire({
                        title: '',
                        html:
                        'Falla en la conexión intenta de nuevo más tarde, o recarga la página.',
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
    $('#addD,#editD').on('submit',function(e){
        e.preventDefault();

        var $form = new FormData(this);
        $form.append('action','createDpto');

        $.ajax({
            url: 'app/CData/RequisicionesControl.php',
            type: 'POST',
            data: $form,
            contentType:false,
		    processData:false,
            success: function(data){
                console.log(data);
                json = JSON.parse(data);

                //console.log(json)
                if(json.estatus == 'ok'){
                              
                    Swal.fire({
                        title: '',
                        html:
                        'Información guardada correctamente.',
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
                        'Falla en la conexión intenta de nuevo más tarde, o recarga la página.',
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
}
function addConcepts(){

    html = ' <tr class="concepts" id="tr-'+i+'">'+
        '<td>'+
    '<div class="form-group">'+
    '<select name="prov[]" id="prov-'+i+'" class="form-control listProveedores" required>'+
    '</select>'+
    '</div>'+
    '</td>'+
    '<td>'+
        '<div class="form-group">'+
            '<input type="number" name="cant[]" class="form-control testing" placeholder="Cantidad" id="cant-'+i+'" required>'+
        '</div>'+
    '</td>'+
    '<td>'+
        '<div class="form-group">'+
            '<select name="uni[]" class="form-control" requiered>'+
            '<option value="">Seleciona una opción</option>'+
            '<option value="pieza">Pieza</option>'+
            '<option value="caja">Caja</option>'+
            '<option value="servicio">Servicio</option>'+
            '<option value="costal">Costal</option>'+
            '<option value="cubeta">Cubeta</option>'+
            '<option value="litro">Litro</option>'+
            '<option value="lote">Lote</option>'+
            '</select>'+
        '</div>'+
    '</td>'+
    '<td>'+
        '<div class="form-group">'+
            '<input type="text" name="concp[]" class="form-control" placeholder="Concepto" required>'+
        '</div>'+
    '</td>'+
    '<td>'+
        '<div class="form-group">'+
            '<input type="text" name="model[]" class="form-control" placeholder="Modelo">'+
        '</div>'+
    '</td>'+
    '<td>'+
        '<div class="form-group">'+
            '<input type="text" name="mark[]" class="form-control" placeholder="Marca">'+
        '</div>'+
    '</td>'+
    '<td>'+
        '<div class="form-group">'+
            '<input type="url" name="linkBuy[]" class="form-control" placeholder="Link de referencia / compra">'+
        '</div>'+
    '</td>'+
   '<td>'+
        '<div class="form-group">'+
            '<input type="number" name="price[]" class="form-control testing" placeholder="Precio" id="pr-'+i+'" required>'+
        '</div>'+
    '</td>'+
   '<td>'+
        '<div class="form-group">'+
            '<input type="text" name="subto[]" class="form-control subt" value="0" readonly id="total-'+i+'">'+
        '</div>'+
    '</td>'+
    '<td>'+
        '<div class="form-group">'+
            '<button type="button" class="btn btn-danger" id="'+i+'">Eliminar</button>'+
        '</div>'+
    '</td>'+
'</tr>';
    $('#datatable tbody').append(html);
    getSelectProv('prov-'+i);
    i++;
}
function deleteConcepts(id){


    cont = 0;
    $('.concepts').each(function(){
        cont ++;
    });

    if(cont > 1){

        $('#tr-'+id).remove();
        //console.log(id);
        var subtotal = 0;
        $('.subt').each(function(){

            subtotal = subtotal + parseInt($(this).val());
        });

        iva = subtotal * .16;
        $('#Iva').text(formatter.format(iva));
        $('#globalsSubT').text(formatter.format(subtotal));
        $('#globaslT').text(formatter.format(subtotal + iva));
    }else{
        Swal.fire({
            title: 'Conceptos minimos',
            html:
            'No puedes eliminar el concepto tu requisición debe contener al menos un elemento.',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#2826aa',
            confirmButtonText: 'De acuerdo',
        })
    }   
}
function totals(cant,price,id){
    
    total = cant*price;
    var subtotal = 0;
    $('#total-'+id).val(total);
    $('.subt').each(function(){
        subtotal = subtotal + parseInt($(this).val());
    });

    iva = subtotal * .16;
    $('#Iva').text(formatter.format(iva));
    $('#globalsSubT').text(formatter.format(subtotal));
    $('#globaslT').text(formatter.format(subtotal + iva));
}
function showRegim(action,regmn,chan){
   
    $.ajax({
        type: "POST",
        url: "../facturacion/app/CData/processRegim.php",
        data: { regimen : action } 
        }).done(function(data){
            // console.log(data)
        $("#"+chan).html(data);
         var child = $("#"+chan).children().eq(0);
         
         if(regmn != ''){
             $('#'+chan).val(regmn);
         }else{
            child.attr('selected','selected');
         }
        });
}
function obtenerEstados(vls,$status,$stac,$state,$ct){

    //console.log(vls,$status,$stac,$state,$ct);
    // Obtener estados
    $.ajax({
        type: "POST",
        url: "globals/processStates.php",
        data: { estados : vls } 
        }).done(function(data){
        //console.log(data)
        $("#"+$status).html(data);
          var child = $("#"+$status).children().eq(0);

          if($state != ''){
            $("#"+$status).val($state);
            obtenerCiudad($state,$stac,$ct);
          }else{
            child.attr('selected','selected');
          }
        });
        // Obtener municipios
        $("#"+$status).change(function(){
            obtenerCiudad($(this).val(),$stac,$ct)
        });
}
function obtenerCiudad(state,$stac,$ct){
    
    $.ajax({
    type: "POST",
    url: "globals/processStates.php",
    data: { municipios : state } 
    }).done(function(data){
        //console.log(data);
        $("#"+$stac).html(data);
        var child = $("#"+$stac).children().eq(0);

        if($ct != ''){
            $("#"+$stac).val($ct);
        }else{
            child.attr('selected','selected');
        }
    });
}
function subirProv(){
    $('#editproveedor').on('submit',function(e){
        e.preventDefault();

        var $form = new FormData(this);
        $form.append('action','subirDatos');


        if(ValidarRfc()){
            $.ajax({
                url: 'app/CData/RequisicionesControl.php',
                type: 'POST',
                data: $form,
                contentType:false,
                processData:false,
                success: function(data){
                    console.log(data);
                    json = JSON.parse(data);
                        if(json.estatus == 'ok'){
                            tablaProveedores();
                            $('#clickModal').click()
                            Swal.fire({
                                title: '',
                                html:
                                'Lista de proveedores actualizada.',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#2826aa',
                                cancelButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar',
                                cancelButtonText: 'Revisar de nuevo',
                            })
                        }else{
                            Swal.fire({
                                title: '',
                                html:
                                'Falla en la conexión intenta de nuevo más tarde, o recarga la página.',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#2826aa',
                                cancelButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar',
                                cancelButtonText: 'Revisar de nuevo',
                            })
                        }
                        //console.log(json)
                    }
                });
        }else{
            Swal.fire({
                title: '',
                html:
                'El tipo de persona no coincide con el RFC.',
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#2826aa',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Revisar de nuevo',
            });
        }
    });
}
function ValidarRfc(){
    var tipo = $("#EdchangeAct").val();
    var rfc = $("#rfc").val();
    
    var result = null;
    switch(tipo){
        case 'persona fisica':
            result = rfc.length == 13 ? true : false;
            break;
        case 'persona moral':
            result = rfc.length == 12 ? true : false;
            break;
    }
    return result;
}
function getProveedores(idArea,id,idprov){

    if(idprov != undefined){
        prov = listedProveedores.find(el=> el.id_prov == idprov);
        //console.log(prov);
        $('#clickModal').click();
        $('#CustomLabel').text('Editar Proveedor');
        $('#EdchangeAct').val(prov.tipo_act);
        showRegim(prov.tipo_act,prov.tipo_reg,'Edreg')
        $('#nrazon').val(prov.nrazon);
        $('#rfc').val(prov.n_rfc);
        $('#street').val(prov.calle);
        $('#numberE').val(prov.n_ext);
        $('#numberI').val(prov.n_int);
        $('#email').val(prov.email);
        $('#tel').val(prov.telefono);
        $('#nbd').val(prov.colonia);
        obtenerEstados('Mexico','stateE','cityE',prov.estado,prov.ciudad);
        $('#cp').val(prov.cp);
        $('#bank').val(prov.nombre_banco);
        $('#acountB').val(prov.num_cuenta);
        $('#clabeB').val(prov.num_clabe);
        $('#editproveedor').append('<input type="hidden" name="typeData" value="editP"><input type="hidden" name="idProv" value="'+idprov+'">');
    }else{


        html = '<option value="" selected disabled>Selecciona un proveedor</option>'+
        '<option value="0" >Sin proveedor</option>';

        prov = listedProveedores;
        $(prov).each(function(i,elem){
            html += '<option value="'+elem.id_prov+'">'+elem.id_prov+'-'+elem.nrazon+'</option>';
        });
        console.log(prov);
        $('#'+id).html(html);
        $('.provDrop').html(html);        
    }

}
function subirReq(){
    
    $('#requestSend').on('submit',function(e){
        e.preventDefault();

        var type = '';

        $('input[type=radio]').each(function(){
            if($(this).is(':checked')){
                type = $(this).val();
            }
        });

        //console.log(type);
        var $form = new FormData(this);
        $form.append('action','subirReq');
        $form.append('op',type);
        $.ajax({
            url: 'app/CData/RequisicionesControl.php',
            type: 'POST',
            data: $form,
            contentType:false,
		    processData:false,
            success: function(data){
                console.log(data);
                json = JSON.parse(data);

                //console.log(json)
                if(json.estatus == 'ok'){
                    Swal.fire({
                        title: '',
                        html:
                        'Solicitud enviada correctamente.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        location.reload();
                    });
                }else{
                    Swal.fire({
                        title: '',
                        html:
                        'Falla en la conexión intenta de nuevo más tarde, o recarga la página.',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        
                        location.reload();
                        
                    });
                }
            }
        });
    });
}
function showRequest($option,vls){

    if(vls != undefined){
        $data = {action: 'dataReqsA',option: $option,viewAdmin:'master'};
    }else{
        $data = {action: 'dataReqs',option: $option};
    }
    var table =  $("#datatableRes").DataTable({
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
            className: "btn-download"
        }, {
            extend: "pdf",
            className: "btn-download"
        }, {
            extend: "print",
            className: "btn-download"
        }],

        "ajax": {
            url: 'app/CData/RequisicionesControl.php',
            type: 'POST',
            data: $data,
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
                //$('.totals').html('<b>Total de la requisición </b>: $'+json.data2);
                return json.aaData
            },
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
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ],
        
    });
}
function showBreakDown($id,$folio,$option,$date,$name,req){

    $('#breakdown').addClass('active');
    $('#folio').text($folio);
    $('#dateR').html('<b>Fecha de solicitud</b>: '+$date);
    $('#prename').html('<b>Nombre del solicitante</b>: '+$name);
    $('#fileSignApproved').addClass('active');
    $('#fileSignApproved').addClass('hidden');
    $('#optionsB').val($option);

    if(link){
        if($option != 'pendiente'){
            $('.sendReq').addClass('hidden');
            $('#approved').text('Aprobar');
            $('#fileSignApproved').addClass('hidden');
            if($option == 'aprobada'){
                //$('#comprRq').removeClass('hidden');
                $('#approved').removeClass('hidden');
                $('#approved').text('Pagado');
            }else if($option == 'facturada'){
                $('.comPayBill').removeClass('hidden');
            }
        }else{
            $('.sendReq').removeClass('hidden');
            if(req < 1){
                $('#fileSignApproved').removeClass('hidden');
            }
        }
        $users = 'admin';
    }else{
        $users = 'jefe';
    }

    $data = {action: 'showBKD',idUs:$id,folio:$folio,option:$option,us:$users}

    var table =  $("#datatableBKD").DataTable({
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
            className: "btn-download"
        }, {
            extend: "pdf",
            className: "btn-download"
        }, {
            extend: "print",
            className: "btn-download"
        }],

        "ajax": {
            url: 'app/CData/RequisicionesControl.php',
            type: 'POST',
            data: $data,
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
               //console.log(json.aaData);
                $('.totals').html('<b>Total de la requisición </b>: $'+json.data2);
                getSelectProv(0);
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
    
            if (api.column(8).data().length){
            var total = api
            .column(  )
            .data()
            .reduce( function (a, b) {
            return intVal(a) + intVal(b);
            } ) }
            else{ total = 0};
                    
            // Total over this page
                
            if (api.column(8).data().length){
            var pageTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                } ) }
                else{ pageTotal = 0};
    
            // Update footer
            $( api.column(8).footer() ).html(
                formatter.format(pageTotal)
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
        'iDisplayLength': 10,
        'order':[
            [0,'asc']
        ],
        
    });
}
function payGlobals(id,_this){

    //console.log(id,_this);

    var gl = $('.sendReq');
    i = 0;
    
    cont = $('#formAdmin');

    numMov = $('#numMovText-'+id);
    cCosto = $('#cCosto-'+id);
    inputs = '<input type="hidden" name="idReq[]" id="idReq-'+id+'" value="'+id+'">';
    if(cCosto.val() != '' && numMov.val() != ''){
        if(!document.body.contains(document.getElementById("idReq-"+id))){
            cont.append(inputs);
                if(numMov.val() != '' && numMov.val() != undefined){
                    
                    inputM = '<input type="hidden" name="numMov[]" id="numMov-'+id+'" value="'+numMov.val()+'">';   
                    cont.append(inputM);
                }
                if(cCosto.val() != undefined){
                    inputM = '<input type="hidden" name="numCosto[]" id="Cost-'+id+'" value="'+cCosto.val()+'">';   
                    cont.append(inputM);
                }
        }else{
            document.getElementById("formAdmin").removeChild(document.getElementById("idReq-"+id));
            document.getElementById("formAdmin").removeChild(document.getElementById("numMov-"+id));
            document.getElementById("formAdmin").removeChild(document.getElementById("Cost-"+id));
            
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
        if(i > 0){
            gl.css('opacity','1');
            gl.removeAttr('disabled');
        }else{
            gl.css('opacity','.5');
            gl.attr('disabled','disabled');
        }
    }else{
        $(_this).prop('checked',false);

        Swal.fire({
            title: '',
            html:
            'Debes ingresar un centro de costo / numero de movimiento para este pago.',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#2826aa',
            cancelButtonColor: '#dc3545',
            confirmButtonText: '¡Lo agrego!',
        }).then((result)=>{
            cCosto.focus();
        });
    }
}
function saveFiles(id,linkC,idU,idCOm){
    cont = $('#formComp');
    cont1 = $('#compNOK #formValidate');

    numMov = $('#numMovText-'+id);

    if(numMov.val() != ''){
            
        inputs = '<input type="hidden" name="idReq" id="idReq-'+id+'" value="'+id+'">';
        inputM = '<input type="hidden" name="numMov" id="numMov-'+id+'" value="'+numMov.val()+'">';   
        cont.append(inputM);
        cont.append(inputs);
        cont1.append(inputs);

        numMov = $('#numMovText-'+id);
        if(linkC != undefined){
            url = 'app/lista_comprobantes/'+idU+'/'+linkC;
            previewComp(url,1);
        }

        inputs = '<h5>¿El comprobante es ?</h5><input type="radio" name="compr" value="correct" required><label>Correcto</label><br>'+
        '<input type="radio" name="compr" value="'+idCOm+'" id="comprobante"><label>Incorrecto</label>';
        $('#valuesDeclined').html(inputs);
        
        $('#comprRq').addClass('active');
    }else{
        Swal.fire({
            title: '',
            html:
            'Debes ingresar el numero de movimiento de este pago.',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#2826aa',
            cancelButtonColor: '#dc3545',
            confirmButtonText: '¡Lo agrego!',
        }).then((result)=>{
            numMov.focus();
        });
    }
    
}
function UpdateRq($status){

    $reason = $('#decline_reason').val();
;
    $('#formAdmin').on('submit',function(e){
        e.preventDefault();
        var $form = new FormData(this);
        $form.append('action','updateReq');
        $form.append('status',$status);
        $form.append('reason',$reason);

        $.ajax({
            url: 'app/CData/RequisicionesControl.php',
            type: 'POST',
            data: $form,
            contentType:false,
            processData:false,
            success: function(data){
                //console.log(data);
                json = JSON.parse(data);

                //console.log(json)
                if(json.estatus == 'ok'){
                    if($status == 'aprobada'){
                        $text = "Aprobaste la solicitud.";
                        $type = 'success';
                    }else if($status == 'rechazada'){
                        $text = "Rechazaste la solicitud.";
                        $type = 'info';
                    }else{
                        $text = "Pagaste la solicitud.";
                        $type = 'info';
                    }
                    
                    Swal.fire({
                        title: '',
                        html: $text,
                        type: $type,
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        if (result.value == true) {
                            location.reload();
                        }else{
                            location.reload();
                        }
                    });
                }else{
                    Swal.fire({
                        title: '',
                        html:
                        'Falla en la conexión intenta de nuevo más tarde, o recarga la página.',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#2826aa',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        if (result.value == true) {
                            location.reload();
                        }else{
                            location.reload();
                        }
                    });
                }
            }
        });
        });

}
function previewComp(lengthFile,id){
    
    if(id == undefined){
        id = "";
        var url = URL.createObjectURL(lengthFile);
        var extension = lengthFile.name.replace(/^.*\./, '');
        if(extension != lengthFile.name){
            extension = extension.toLowerCase();
        } 
    }else{
        url = lengthFile;
        extension = url.split('.')[1];
    }

    if(extension == 'pdf'){
        pdfjsLib.getDocument(url).then(doc =>{
            //console.log("this file has"+doc._pdfInfo.numPages+"pages");
    
            doc.getPage(1).then(page=>{
            var myCanvas=document.getElementById("previewPDF"+id);
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
        $('#previewImg'+id).addClass('hidden');
        $('#previewPDF'+id).removeClass('hidden');
    }else{
        $('#previewImg'+id).attr('src',url);
        $('#previewImg'+id).removeClass('hidden');
        $('#previewPDF'+id).addClass('hidden');
    }
}
function saveFile(){

    $('#formComp, #formSign').on('submit',function(e){

        e.preventDefault();

        modalSAve = $(this).attr('id');
        $folio = $('#folio').text();
        $dateR = $('#dateR').text().split(':')[1]+':'+$('#dateR').text().split(':')[2]+':'+$('#dateR').text().split(':')[3];
        $name = $('#prename').text().split(':')[1];
        $option = $('#optionsB').val();

        $form = new FormData(this);
        $form.append('action','saveFile');
        // pdf = $('#pdf').prop('files')[0];
        // pdf = $('#xml').prop('files')[0];
        // $form.append('pdf',pdf);

        $.ajax({
            url: 'app/CData/RequisicionesControl.php',
            type: "POST",
            data: $form,
            contentType:false,
            processData:false,
            success: function(data){
    
                //console.log(data)
                resp = JSON.parse(data)
                if(resp.estatus == 'ok'){
                    $('.close').click();

                    if(link){
                        showRequest($option,2);
                    }else{
                        showRequest($option);
                    }
                    showBreakDown(resp.idUS,$folio,$option,$dateR,$name,1);

                    Swal.fire({
                        title: '',
                        html:
                        'Comprobante guardado exitosamente.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
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
                }
            }    
        });
    });
}
function saveSeries(){
   
    $('#formSeries').on('submit',function(e){
        
        e.preventDefault();

        $folio = $('#folio').text();
        $dateR = $('#dateR').text().split(':')[1]+':'+$('#dateR').text().split(':')[2]+':'+$('#dateR').text().split(':')[3];
        $name = $('#prename').text().split(':')[1];
        $option = $('#optionsB').val();

       
        $form = new FormData(this);
        $form.append('action','saveSerie');

        $.ajax({
            url: 'app/CData/RequisicionesControl.php',
            type: "POST",
            data: $form,
            contentType:false,
            processData:false,
            success: function(data){
    
                //console.log(data)
                resp = JSON.parse(data)
                if(resp.estatus == 'ok'){

                    showRequest($option);
                    showBreakDown(resp.idUS,$folio,$option,$dateR,$name,1);

                    Swal.fire({
                        title: '',
                        html:
                        'N° de serie(s) guardado(s) exitosamente.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
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
                }
            }    
        });
    });
}
function previewDocs(id,option){
    cont = $('#formValidate');
    inputs = '<input type="hidden" name="idReq" id="idReq-'+id+'" value="'+id+'">';
    cont.append(inputs);

    $form = new FormData();
    $form.append('action','linkDoc');
    $form.append('idReq',id);

    $.ajax({
        url: 'app/CData/RequisicionesControl.php',
        type: "POST",
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            resp = JSON.parse(data)
            console.log(resp)
            if(resp.estatus == 'ok'){

               

                inputs = '<h5>Selecciona el archivo que tiene el error</h5><input id="comprobante" type="radio" name="fac" value="'+resp.data[0].id_comp_pago+'" required checked><label>Comprobante de pago</label><br>'+
                '<input type="radio" name="fac" value="'+resp.data[0].id_fc+'" id="factura"><label>Factura</label>';
                $('#valuesDeclined').html(inputs);
                $('#comPayBill').addClass('active');
               
                if(option == 'facturada'){
                    $('#formValidate').removeClass('hidden');
                    $('#newSendCom').addClass('hidden');
                    link = 'app/lista_comprobantes/'+resp.data[0].id_com_us+'/'+resp.data[0].link_comp;
                    previewComp(link,1);
                    link = 'app/lista_facturas/'+resp.data[0].id_us_fc+'/'+resp.data[0].link_pdf;
                    previewComp(link,2);
                }else{
                    link = 'app/lista_comprobantes/'+resp.data[0].id_com_us+'/'+resp.data[0].link_comp;
                    previewComp(link,1);
                    $('#formValidate').addClass('hidden');
                    $('#newSendCom').removeClass('hidden');
                    cont = $('#formComp');

                    if(option == 'comprobanteincorrecto'){

                            inputs = '<input type="hidden" name="idComp" value="'+resp.data[0].id_comp_pago+'">';
                        $('#datePago').addClass('hidden');
                        $('#datePago').removeAttr('name');
                        $('#datePago').removeAttr('required');
                    }
                    inputs += '<input type="hidden" name="idReq" value="'+resp.data[0].id_req+'">';
                    cont.append(inputs);
                    $('#comprRq').addClass('active');
                }
            
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
            }
        }    
    });
}
function changeDocs(id){

    //console.log(id);
    $('#formValidate').on('submit',function(e){
        e.preventDefault();

        type = '';
        text = '';
        $('#valuesDeclined input[type=radio]').each(function(){
            if($(this).is(':checked')){
                text = $(this).attr('id');
                type = $(this).val();
            }
        });
        //console.log(text+type);
        $form = new FormData(this);
        $form.append('action','changeDocs');
        $form.append('cond',id);
        $form.append(text,type);
        $('.close').click();
        $.ajax({
            url: 'app/CData/RequisicionesControl.php',
            type: "POST",
            data: $form,
            contentType:false,
            processData:false,
            success: function(data){
    
                console.log(data)
                resp = JSON.parse(data)
                if(resp.estatus == 'ok'){

                    Swal.fire({
                        title: '',
                        html:
                        'estatus actualizado exitosamente.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Revisar de nuevo',
                    }).then((result) => {
                        if (result.value == true) {
                            showRequest('facturadas');
                        }
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
                }
            }    
        });

    });
}
function selectDepto(dpto){

    $form = new FormData()
    $form.append('action','selectDpto');
    $.ajax({
        url: 'app/CData/RequisicionesControl.php',
        type: 'POST',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            //console.log(data);

            json = JSON.parse(data);
            //console.log(json);
            html = '<option value="" selected disabled>Selecciona un departamento</option>';

            if(json.estatus == 'ok'){

                $(json.data).each(function(i){
                    html += '<option value="'+json.data[i].id_area+'">'+json.data[i].nombre_area+'</option>';
                });
                $('#dpto').html(html);
                $('#dptouser').html(html);

                if(dpto != undefined){
                    $('#dptouser').val(dpto);
                }
               
            }else{
                Swal.fire({
                    title: '',
                    html:
                    'Falla en la conexión intenta de nuevo más tarde, o recarga la página.',
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
function selectUser(){

    $form = new FormData()
    $form.append('action','selectuser');
    $.ajax({
        url: 'app/CData/RequisicionesControl.php',
        type: 'POST',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            //console.log(data);

            json = JSON.parse(data);
            //console.log(json);
            html = '<option value="" selected disabled>Selecciona un usuario</option>';

            if(json.estatus == 'ok'){

                $(json.data).each(function(i){

                    $name = json.data[i].nombres+' '+json.data[i].apellidoPaterno+' '+json.data[i].apellidoMaterno;
                    html += '<option value="'+json.data[i].idPersona+'" id="'+json.data[i].estatus+'">'+$name+'</option>';
                    
                });
                $('#users').html(html);

            }else{
                Swal.fire({
                    title: '',
                    html:
                    'Falla en la conexión intenta de nuevo más tarde, o recarga la página.',
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
function tablaUsuarios(){
    tAlumnos = $("#datatable-tablaUsers").DataTable({
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
        url: 'app/CData/RequisicionesControl.php',
        type: 'POST',
        data: {action: 'selectuser'},
        dataType: "JSON",
        error: function(e){
            console.log(e.responseText);
        },
        dataSrc: function(json){
            
            if(listedUsers.length > 0){
                listedUsers = [];
            }

            $.each(json.adata, (i,elem)=>{

                listedUsers.push(elem);
            
            });
            return json.aaData;
        },
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
        [0,'asc']
    ],
    });



}//Fin table users
function tablaProveedores(){

    tAlumnos = $("#datatable-tablaProveedores").DataTable({
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
        url: 'app/CData/RequisicionesControl.php',
        type: 'POST',
        data: {action: 'obtenerProv'},
        dataType: "JSON",
        error: function(e){
            console.log(e.responseText);
        },
        dataSrc: function(json){

            //console.log(json.adata);    
            if(listedProveedores.length > 0){
                listedProveedores = [];
            }

            $.each(json.adata, (i,elem)=>{

                listedProveedores.push(elem);
            
            });
            return json.aaData;
        },
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
        [0,'asc']
    ],
    });



} 
function getSelectProv(id){

    $.ajax({
        url: 'app/CData/RequisicionesControl.php',
        type: 'POST',
        data: {action: 'obtenerProv'},
        dataType: "JSON",
        success: function(json){

            if(listedProveedores.length > 0){
                listedProveedores = [];
            }

            $.each(json.adata,(i,elem)=>{
                listedProveedores.push(elem);
            });
            var idArea = $('#Area').val();

            if(idArea == undefined){
                idArea = 0;
                id = 0;
            }
            getProveedores(idArea,id);
        }
    });
}
function editUs($us,$vals){

    console.log($vals);
    switch($vals){
        
        case 3:
            user = listedUsers.find(elem=> elem.idPersona == $us);

            $('#modalAgregausers').modal("show");
            $('#CustomLabel').text('Editar Usuario');
            selectDepto(user.area);
            $('#names').val(user.nombres);
            $('#apa').val(user.aPaterno);
            $('#ama').val(user.aMaterno);
            $('#email').val(user.email);
            $('#roles').val(user.estado);
            $('#addU').append('<input type="hidden" name="typeData" value="editU"><input type="hidden" name="idUs" value="'+$us+'">');

        break;
        case 0:
            swal({
                icon:'info',
                title:'¿Deseas desactivar al usuario?',
                text:'',
                buttons: {
                cancel: {
                    text: "Cancel",
                    value: false,
                    visible: true,
                    className: "",
                    closeModal: true,
                  },
                   confirm: {
                    text: "Sí",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                  }
                  },
            }).then((result)=>{
                if(result){
                    updateStatusUsers($us,$vals);
                }
            });
           
        break;
        case 1:
            updateStatusUsers($us,$vals);
        break;
        default:
            swal('parecer ser que hay un error intenta de nuevo.'); 
        break;
    }
}
//edit status de user
function updateStatusUsers($us,$vals){

    $form = new FormData();
    $form.append('action','createUs');
    $form.append('idUs', $us);
    $form.append('typeData',$vals);

    $.ajax({
        url: 'app/CData/RequisicionesControl.php',
        type: 'POST',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            //console.log(data);
            json = JSON.parse(data);
            
            //console.log(json)
            if(json.estatus == 'ok' && json.data != ''){
                $('.toast-success').addClass('show');
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                },3000);
                tablaUsuarios();   
            }else{
            swal('algo salio mal.');
            }
        }
    });


}