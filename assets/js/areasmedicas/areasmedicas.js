var listedUsers = [];
var listedProcedimientos = [];
var listedStud = [];

$(document).ready(()=>{
    $('#openFile').on('click',function(){
        $id = $(this).attr('id');
        $('.'+$id).click();
    });
    $('.openFile').on('change',function(){
        lengthFile = $(this).prop('files')[0];
        previewFiles(lengthFile,this);

    });
    $('#addTu').on('submit',function(e){

        updateStatusTU(0,0,this,e);
    
    });
    $('#addProc').on('submit',function(e){

        if($('#addProc input[name=idM]').length > 0){
            $add = 1;
        }else{
            $add = 0;
        }
        updateStatusPro(0,0,this,e,$add);

    });
    $('#modalAgregaProce .close').click(function(){
        $('#addProc')[0].reset();
        $('#addProc input[name=idM]').remove();
    });
    $('#clickModal').on('click',function(){
        $('#CustomLabelEditProce').text('Agregar Procedimiento');
        $('#addProc input[name=idM]').remove();
    });
    
    $(document).on('click','button.btnUploadFiles',function(){
        $id = $(this).attr('id');
        $type = $(this).attr('data-files');
        
        //$('#divDesc').addClass('hidden');
        if($type == 'PRCD'){
            $('#modalUploadFiles #CustomLabel').text('Subir Listas');
            $('#divDesc').removeClass('hidden');
            proce = listedProcedimientos.find(elem=> elem.idpm == $id);  
            
            $files = new Array(); 
            $descp = new Array();
            if(proce.archivo != null && proce.archivo != ''){

                for(f in proce.archivo){
                    $files.push(`"${proce.archivo[f]}"`);
                }
                for (d in proce.descripcion){
                    $descp.push(`"${proce.descripcion[d]}"`);
                }
                $('#modalUploadFiles #oldfiles').val('['+$files+']');
                $('#modalUploadFiles #oldfiles').attr('name','oldfiles');
                $('#modalUploadFiles #olddes').val('['+$descp+']');
                $('#modalUploadFiles #olddes').attr('name','olddes');
            }

        }else{
            $('#modalUploadFiles #CustomLabel').text('Subir CV');
        }

        $('#formUpCv').append(`<input type="hidden" name="idM" value="${$id}"/>`);
        $('#typeFiles').val($type);
    });
    $('.closeUpcv').click(function(){
        $('#typeFiles').val('');
        $('#formUpCv input[name=idM]').remove();
        $('html').css('overflow','hidden');
        $('#divDesc').addClass('hidden');
        $('.previewModal span').text('');
        $('.previewModal').addClass('hidden');
    });
    $('#saveFils').click(function(){

        $files = $('#fileSaves').prop('files')[0];
        if($files != undefined){

            $('#formUpCv').submit();
        }else{

            $('.toast-success').html('debes subir seleccionar un archivo de tu galería.');
            $('.toast-success').addClass('show');
            $('.toast-success').css({'z-index':'1055'})
            setTimeout(()=>{
                $('.toast-success').removeClass('show');
            },3000);

        }
    });
    $('#idCarrer').on('change',function(e){

       $vls = $(this).val();
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            data: {action: 'getGen',idC: $vls},
            success: function(data){

                json = JSON.parse(data);
                if(json.estatus == 'ok'){

                    $('#idGen').removeClass('hidden');
                    options = '<option value="" selected disabled>Selecciona una generación</option>';
                    $.each(json.data,(i,elem)=>{
                        
                        options += `<option value="${elem.idGeneracion}">${elem.nombre}</option>`;
                    });
                    
                }
                
                $('#selIdGen').html(options);
            }
        });
    });
    TableDirectory();
    TableProcedimientos();

    $('#alumnos-tab').on('click',function(){
        TableStudents();
    });
    $('#cirugia-tab').on('click',function(){
        listAlumnPro();
    });
});

$('#modalAgregaProce').on('hidden.bs.modal', function() {
    $("#addProc")[0].reset();
});

function TableDirectory(){

    tableDirectory = $("#table_tutores").DataTable({
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
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            type: 'POST',
            data: {action: 'selectTutor'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            dataSrc: function(json){

                if(listedUsers.length > 0){
                    listedUsers = [];
                }

                $.each(json.aaData, (i,elem)=>{
    
                    listedUsers.push(elem);
                
                });
                return json.aData;

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
        'iDisplayLength': 100,
        'order':[
            [0,'asc']
        ],
    });
    
}
function TableProcedimientos(){

    $("#tableProcedimientos").DataTable({
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
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            type: 'POST',
            data: {action: 'getProced'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            dataSrc: function(json){

                //console.log(json.aaData);

                if(listedProcedimientos.length > 0){
                    listedProcedimientos = [];
                }
    
                $.each(json.aaData, (i,elem)=>{
    
                    listedProcedimientos.push(elem);
                
                });
                return json.aData;

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
        'iDisplayLength': 100,
        'order':[
            [0,'asc']
        ],
    });

}
function TableStudents(){

    $("#tableAlumnos").DataTable({
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
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            type: 'POST',
            data: {action: 'getStu'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            dataSrc: function(json){

                console.log(json.aaData);

                // if(listedProcedimientos.length > 0){
                //     listedProcedimientos = [];
                // }
    
                // $.each(json.aaData, (i,elem)=>{
    
                //     listedProcedimientos.push(elem);
                
                // });
                return json.aData;

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
        'iDisplayLength': 100,
        'order':[
            [0,'asc']
        ],
    });

}
function previewFiles(lengthFile,_this){

    inputFile = $(_this).siblings()[0];

    $(inputFile).removeClass('hidden')
    
    lengthFile != undefined ? $('span', inputFile).text(lengthFile.name) : $(inputFile).addClass('hidden');

    $('#formUpCv').on('submit',function(e){

        e.preventDefault();
        $form = new FormData(this);
        $form.append('action','saveFiles');

        $.ajax({
            type: 'POST',
            url : '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            data: $form,
            contentType:false,
            processData:false,
            success: function(data){

                json = JSON.parse(data);
                
                if(json.estatus == 'ok'){
                    $('.closeUpcv').click();
                    $('#formUpCv')[0].reset();
                    $('.previewModal span').text('');
                    $('.previewModal').addClass('hidden');
                    $('html').css('overflow','auto');
                    $('#divDesc').addClass('hidden');
                    TableDirectory();
                    $('.toast-success').html('Archivo(s) actualizados.');
                    $('.toast-success').addClass('show');
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                },3000);
                }else{
                    swal('intenta de nuevo más tarde.');
                }
            }
        });
    });
}
function editResult($us,$vals,$type){

    switch($vals){
        
        case 3:
            user = listedUsers.find(elem=> elem.id == $us);   

            $('html').css('overflow','hidden');
            
            $('#CustomLabelEdit').text('Editar Usuario');
            $('#names').val(user.nombres);
            $('#apa').val(user.aPaterno);
            $('#ama').val(user.aMaterno);
            $('#email').val(user.email);
            $('#roles').val(user.rolem);
            $('#tel').val(user.telefono);
            $('#cel').val(user.celular);
            $('#telt').val(user.telefono_trabajo);
            $('#telr').val(user.telefono_recados);
            $('#descp').val(user.descripcion);
            $('#gen').val(user.sexo);
            $('#modalAgregatutores').modal();
            $('#addTu').append('<input type="hidden" name="idM" value="'+$us+'">');

        break;
        case 0:

            $text = $type == 1 ? '¿Deseas desactivar al tutor?' : '¿Deseas desactivar el procedimiento?';

            swal({
                icon:'info',
                title: $text,
                text:'',
                buttons: {
                cancel: {
                    text: "Cancelar",
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

                    $type == 1 ? updateStatusTU($us,$vals) : updateStatusPro($us,$vals);

                                    
                }
            });
           
        break;
        case 1:
            $type == 1 ? updateStatusTU($us,$vals) : updateStatusPro($us,$vals);
        break;
        case 2:
            
            proce = listedProcedimientos.find(elem=> elem.idpm == $us);   

            $('html').css('overflow','hidden');
            $('#namesP').val(proce.nombre);
            $('#costo').val(proce.costo);
            $('#CustomLabelEditProce').text('Editar Procedimiento');
            $('#modalAgregaProce').modal();
            $('#addProc').append('<input type="hidden" name="idM" value="'+$us+'">');
        break;
        case 4:
            console.log(listedStud);
            proce = listedStud.find(elem=> elem.idAsistente == $us);   

            console.log(proce);
            
        break;
        default:
            swal('parece ser que hay un error intenta de nuevo.'); 
        break;
    }
}

$("#FormCirugias").on("submit",function(e){
    e.preventDefault();
    console.log(envio);
    $form = new FormData(this);
    $form.append('action','updateCirugia');
    $form.append('case','create');

    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/areasmedicas/areasMedicasControl.php",
        data: $form,
        contentType:false,
        processData:false,
        dataType: "JSON",
        success: function (response) {
           console.log(response);
            if(response.estatus == "ok"){
                swal({
                    title: 'Cirugia añadida correctamente',
                    icon: 'success',
                    text: 'Espere un momento',
                    button: false,
                    timer: 2500,
                }).then((result)=>{
                    console.log("reload");
                });   
            }
        }
    });
});

function updateStatusTU($us,$vals,_this,event){


    text = '';
    if(_this != undefined){
        event.preventDefault();
        $form = new FormData(_this);
        text = "Actualización de usuario(s) correcta.";
    }else{
        $form = new FormData();
        $form.append('idM', $us);
        $form.append('typeU',$vals);
        text = "Actualización de estatus correcta.";
    }
    $form.append('action','createUs');
    

    $('#modalAgregatutores .close').click();  
    $('#addTu')[0].reset();

    $.ajax({
        url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
        type: 'POST',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            //console.log(data);
            json = JSON.parse(data);
            
            if(json.estatus == 'ok' && json.data != ''){
                TableDirectory();   
                $('.toast-success').addClass('show');
                $('.toast-success').html(text);
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                    
                },3000);
               
            }else{
            swal('algo salio mal.');
            }
        }
    });




}
function updateStatusPro($us,$vals,_this,event,$add){

    text = '';
    if(_this != undefined){
        event.preventDefault();
        $form = new FormData(_this);
        
        text = $add > 0 ? "Actualización de procedimiento correcta." : "Procedimiento agregado exitosamente.";
    }else{
        $form = new FormData();
        $form.append('idM', $us);
        $form.append('typeU',$vals);
        text = "Actualización de estatus correcta.";
    }

    $form.append('action','createPro');
    $('#modalAgregaProce .close').click();

    $.ajax({
        url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
        type: 'POST',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            console.log(data);
            json = JSON.parse(data);
            
            if(json.estatus == 'ok' && json.data != ''){
                
                TableProcedimientos();
                $('.toast-success').addClass('show');
                $('.toast-success').html(text);
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                },3000);
            }else{
            swal('algo salio mal.');
            }
        }
    });

}
function protoTesis($id){

    i = 0;
    $('.checkProto').each(function(elem){

        if($(this).is(":checked")){
            i++;
        }
    });

    if(i > 0){
        $('.btnsProto').removeClass('hidden')
    }else{
        $('#idCarrer').val('');
        $('#selIdGen').html('');
        $('#idGen').addClass('hidden');
        $('.btnsProto').addClass('hidden');
    }
    
    inputs = `<input type="hidden" name="idPro[]" id="idPro${$id}" value="${$id}">`;

    if(!document.body.contains(document.getElementById("idPro"+$id))){
        $('#protoTes').append(inputs);
    }else{
        document.getElementById("protoTes").removeChild(document.getElementById("idPro"+$id));
    }
}
function AssignCarrer($id){

    i = 0;
    $('.checkProto').each(function(elem){

        if($(this).is(":checked")){
            i++;
        }
    });

    if(i > 0){
        $('.btnCarrer').removeClass('hidden');
    }else{
        $('#idCarrer').val('');
        $('#selIdGen').html('');
        $('#idGen').addClass('hidden');
        $('.btnCarrer').addClass('hidden');
    }
    
    inputs = `<input type="hidden" name="idM[]" id="idM${$id}" value="${$id}">`;

    if(!document.body.contains(document.getElementById("idM"+$id))){
        $('#addCar').append(inputs);
    }else{
        document.getElementById("addCar").removeChild(document.getElementById("idM"+$id));
    }
}
function UpdateProtoTesis($typeO){

    $('#protoTes').on('submit', function(e){

        e.preventDefault();

        $('#typeOperation').val($typeO);

        if($typeO == 1){
            text = 'Carrera(s) Asignada(s) Correctamente.';
        }else{
            text = 'Carrera(s) Desasignada(s).';
        }
        $form = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            data: $form,
            success: function(data){

                $('.btnsProto').addClass('hidden');
                $("#protoTes")[0].reset();
                $("#protoTes input[name='idPro[]']").remove();
                $('#idGen').addClass('hidden');
                json = JSON.parse(data);
                if(json.estatus == 'ok'){
                    TableProcedimientos();
                $('.toast-success').addClass('show');
                $('.toast-success').html(text);
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                },3000);
                }else{
                    swal('algo salio mal.');
                    TableProcedimientos();
                }
            }
        });
    });
}

function EditarCirugia(idExpediente){
    $form = new FormData();
    $form.append('action','updateCirugia');
    $form.append('case','list');
    $form.append('idexp',idExpediente);

    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/areasmedicas/areasMedicasControl.php",
        data: $form,
        contentType:false,
        processData:false,
        dataType: "JSON",
        success: function (response) {
           console.log(response);
            if(response.estatus == "ok"){
                $.each(response.data, function(key,registro){
                    $("#idAlumno").val(registro.idalumno);
                    $("#nombreAlumno").val("Desc");
                    $("#procedimientoAsignado").val(registro.idpm);
                    $("#pacienteAsignado").val(registro.paciente);
                    $("#sitioAsignado").val(registro.idsitio);
                    $("#fechaHoraAsignada").val(registro.frealizacion);
                    $("#tutorAsigando").val(registro.tutor);
                });
                $("#ModalControlCirugia").modal("show");
            }else{
                swal('algo salio mal.');
            }
        }
    });

}

function UpdateTuCar($typeO){

    $('#addCar').on('submit', function(e){

        e.preventDefault();

        $('#typeOperationT').val($typeO);

        if($typeO == 1){
            text = 'Carrera(s) Asignada(s) Correctamente.';
        }else{
            text = 'Carrera(s) Desasignada(s).';
        }
        $form = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            data: $form,
            success: function(data){

                $('.btnCarrer').addClass('hidden');
                $("#addCar")[0].reset();
                $("#addCar input[name='idM[]']").remove();
                json = JSON.parse(data);
                if(json.estatus == 'ok'){
                    TableDirectory();
                $('.toast-success').addClass('show');
                $('.toast-success').html(text);
                setTimeout(()=>{
                    $('.toast-success').removeClass('show');
                },3000);
                }else{
                    swal('algo salio mal.');
                    TableDirectory();
                }
            }
        });
    });
}
function addBitacora($idA,$idGen,$idCa){
    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
        data: {action:'addBitacoras',idA:$idA,idGen:$idGen,idCa:$idCa},
        success: function(data){

            json = JSON.parse(data);
            if(json.estatus == 'ok'){
                tablaDirectorio(usrInfo.estatus_acceso);
            $('.toast-success').addClass('show');
            $('.toast-success').html('Agregado a bitácoras');
            setTimeout(()=>{
                $('.toast-success').removeClass('show');
            },3000);
            }else{
                swal('algo salio mal.');
                tablaDirectorio(usrInfo.estatus_acceso);
            }
        }
    });
}
function  ListBitacora($idA){

    $("#table_procedimientos_r").DataTable({
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
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            type: 'POST',
            data: {action: 'getProcedR',idA:$idA},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            dataSrc: function(json){

                //console.log(json.aaData);

                if(listedProcedimientos.length > 0){
                    listedProcedimientos = [];
                }
    
                $.each(json.aaData, (i,elem)=>{
    
                    listedProcedimientos.push(elem);
                
                });
                return json.aData;

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
        'iDisplayLength': 100,
        'order':[
            [0,'asc']
        ],
    });

}
function  listAlumnPro(){

    $("#table_cirugias").DataTable({
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
            url: '../assets/data/Controller/areasmedicas/areasMedicasControl.php',
            type: 'POST',
            data: {action: 'getAlumnPro'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            dataSrc: function(json){

                //console.log(json.aData);

                if(listedProcedimientos.length > 0){
                    listedProcedimientos = [];
                }
    
                $.each(json.aaData, (i,elem)=>{
    
                    listedProcedimientos.push(elem);
                
                });
                return json.aData;

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
        'iDisplayLength': 100,
        'order':[
            [0,'asc']
        ],
    });

}
