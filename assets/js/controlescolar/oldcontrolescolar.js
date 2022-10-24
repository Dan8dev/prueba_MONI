var carreras_actuales = [];
var expanded = false;
var cont_carreras = 0;
var tabla_directorio = null;
var listedUsers = [];
var listedGenerations = [];
var listedTeacher = [];
var Band = $("#idUsuarioBand").val();
$(document).ready(()=>{
    $(this).click(function(){
        $('html').css('overflow','auto');
    });

    if(Band != 2){
        TablaCredenciales();
        tablaRevisiones();
        tablaCorrecciones();
        TablaSolicitaDocumentos();
        listarCarrerasExpedientes();

        // ver_todo_asistencias();
        tablasesionesenvivo();
        buscarClasesCarrera();
        
        tablaMaestros();
        //Tabla de Preguntas
        Tabla_PreguntasExPas();
        Tabla_PreguntasExPas2();
        Validar_check();

        //Tabla
        TablaProcesos();

    }

    cargarCarrerasTabla(Band);  
    carrerasCalificacion(Band);
    
    
    if(window.location.pathname.includes('gestorUsuarios')){
        tablaUsuarios();
        $('#clickModal').on('click', function(){
            $('#CustomLabel').text('Agregar Usuario');
            $('#addU')[0].reset();
        });
    }
    
    if(Band=='1' && Band=='4'){
        Columnas = [0,1,2,3,4,5,6];
    }else{
        Columnas=[0,1,2,3,4,5];
        //console.log(Columnas);
    }
    tabla_directorio = $("#table_directorio").DataTable({
        Processing: true,
        ServerSide: true,
        "lengthMenu": [ 10, 25, 50, 75, 100 ],
        "dom" :'Bfrtip',
            buttons:[{
                extend: "excel",
                className: "btn-primary"
            }, {
            extend: "pdf",
            title:'Directorio',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            exportOptions: {
                columns: Columnas}
            }, {
                extend: "print",
                title:'Directorio',
                exportOptions: {
                    columns: Columnas,
                },
                customize: function ( win ) {
                $(win.document.body).find( 'table' )
                .addClass( 'compact' )
                .css( 'font-size', '15px' )
                .addClass('display')
                // .css( 'background-color', 'blue');
                }
            }],
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
        'order':[
            [0,'asc']
        ],
        "initComplete": function () {
            tablaDirectorio(Band);
        }
    });
        //Ocultar columnas de un datatable
        
    $('#cursoExamen').on('change',function(){
        var value =  $('option:selected', this).text();
        $('#nameMat').val(value);
    });

    $('#cursoExamenBanco').on('change',function(){
        var value =  $('option:selected', this).text();
        $('#nameMatBanco').val(value);
    });

    
    $('#check_extraordinario, #check_extraordinarioBanco').on('click', function(){
        var check = $(this);
        var id = $(this).attr('id');
        if(id == 'check_extraordinarioBanco' ){
            inputs = 'costsb input';
            divcostos = 'costsb';
            $ordinary = 'check_ordinarioBanco';
        }else{
            inputs = 'costs input';
            divcostos = 'costs';
            $ordinary = 'check_ordinario';
        }

        if(check.is(':checked')){
            swal({
            icon:'info',
            title:'Seleccionaste examen extraordinario, ¿Es correcto?',
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
                text: "OK",
                value: true,
                visible: true,
                className: "",
                closeModal: true
            }
            },
            }).then((result)=>{
        
                if(!result){
                check.prop('checked',false);
                    $('#'+divcostos).addClass('d-none'); 
                    $('#'+divcostos+' input').removeAttr('required');
                    $('#'+$ordinary).attr('disabled',false);    
                }else{

                    $('#'+divcostos).removeClass('d-none');
                    $('#'+divcostos+' input').attr('required',true);  
                    $('#'+$ordinary).attr('disabled',true);
                }
            })
            
        }else{
            $('#'+divcostos).addClass('d-none');
            
            $('#'+$ordinary).attr('disabled',false);
        }
    });

    $('#check_ordinario, #check_ordinarioBanco').on('click',function(){
        var id = $(this).attr('id');
        if(id == 'check_ordinarioBanco' ){
            inputs = 'check_extraordinarioBanco';
        }else{
            inputs = 'check_extraordinario';
        }
        if($(this).is(':checked')){   
            $('#'+inputs).attr('disabled',true);
        }else{
            $('#'+inputs).attr('disabled',false);
        }
    });
        OtorgarVistas(); 
    });

    $('#sessions-tab').on('click',function(){
        tableSessions();
    });

function OtorgarVistas(){
    var Band = $("#idUsuarioBand").val();
    //console.log(Band);
    if (Band == 2){
        var table = $('#table_directorio').DataTable();
        //table.column(3).visible(false);
        //table.column(7).visible(false);
        //table.column(8).visible(false);
        //console.log("Otorgando campos");
        $("#btn-crear-examen").addClass("d-none");
        $("#Boletas-tab").addClass("d-none");
        $("#Kardex-tab").addClass("d-none");
        $("#profile-tab").addClass("d-none");
        $("#Certificaciones-tab").addClass("d-none");

        $("#home-tab").removeClass("active");
        $("#Reporte-tab").addClass("active");
        $("#home-tab").addClass("d-none");

        $("#home").removeClass("show active");
        $("#Reporte").addClass("show active");

        /*$("#tabla_directorio").dataTable( {
            "columnDefs": [
              { "width": "40%", "targets": 0 }
            ]
          } );*/
    } 
}


function Validar_check(){
    $("#check_retomar_preguntas").on('click', function(){
        var check = $(this).is(':checked'); 
        if(check){ 
            $("#num_preguntas_retomar").attr('disabled', false);
            $("#num_preguntas_retomar").parent().removeClass('d-none');
            $('.num_preguntas_retomar').attr('required', 'required');
    
            $("#id_examen_pasado").parent().removeClass('d-none');
            $("#id_examen_pasado").attr('disabled', false)
    
            $("#datatable-tablaPreguntas2").parent().removeClass('d-none');
            $("#datatable-tablaPreguntas2").attr('disabled', false);
            
        }else{
            $("#num_preguntas_retomar").attr('disabled', true);
            $("#num_preguntas_retomar").parent().addClass('d-none');
            $('.num_preguntas_retomar').removeAttr('required');
            
            $("#id_examen_pasado").parent().addClass('d-none');
            $("#id_examen_pasado").attr('disabled', true)
    
            $("#datatable-tablaPreguntas2").parent().addClass('d-none');
            $("#datatable-tablaPreguntas2").attr('disabled', true)
        }
    });
}
function tableSessions(){

    tableSession = $("#table-sessions").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {action: 'selectSessions'},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            dataSrc: function(json){

                console.log();

                if(listedTeacher.length > 0){
                    listedTeacher = [];
                }
                $.each(json.ateacher,(i,el)=>{
                    listedTeacher.push(el); 
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
            [1,'desc']
        ],
    });
}
function editSessions($names,$id,$date){
    $('#idSession').val($id);
    $('#nameS').val($names);
    $('#dateS').val($date);
    $('#searchInput').val('');

    $('#searchInput').on('keyup', function(){

        $ls = $(this).val(); 
        
        let expr = new RegExp(`${$ls}.*`,"i");

        listedSearch = listedTeacher.find(list => expr.test(list.nTeacher));
        $option = '<option value="0"></option>';
        $(listedSearch).each(function(i,list){
            console.log(list);
            $option += '<option value="'+list.idTeacher+'">'+list.nTeacher+'</option>'; 
        });
        if($('#selectTeacher option').length > 0){
            $('#selectTeacher').removeClass('hidden');
        }
        
        $('#selectTeacher').html($option);
        $('#selectTeacher').attr('size',$('#selectTeacher option').length);
    });
    
    $('#selectTeacher').on('change', function(){

        $ls = $(this).val(); 
        $txt = $('option:selected', this).text();;
        
        if($('#selectTeacher option').length > 0){
            $('#selectTeacher').addClass('hidden');
        }
        $('#searchInput').val($txt);
        //$('#searchInput').attr('value',$txt);
        //$('#searchInput').attr('placeholder',$txt);
    });

    $('#form-sessions').on('submit',function(e){

        e.preventDefault();
        $form = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            data: $form,
            success: function(data){

                json = JSON.parse(data);
                if(json.estatus == 'ok'){
                    swal('Datos actualizados corrrectamente.');
                    tableSessions();
                    $('#modalEditSession').modal('hide');

                }else{
                    swal('Hubo un error intenta de nuevo.');
                }
            }
        });
    });

}
function tablaMateriasReportes(idCiclo,idAlumno,idGen,idCarr){

    sesionesenvivo = $("#TableCalReporteSemestre").DataTable({
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
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                dataType: "JSON",
                data:{
                    action: 'ConsultarCalPorciclo', 
                    idCic: idCiclo,
                    idAlu: idAlumno,
                    idGen: idGen,
                    idCarr:idCarr
                },
                //contentType: false,
                //processData: false,
                error: function(e){
                    console.log(e.responseText);
                }
            }
        });
}
function tablaDirectorio(Band){
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data:{
            action:'volcar_alumnos',
            vista: Band
        },
        success: function (data){

            

            try{
                var alumnos = JSON.parse(data);
                tabla_directorio.clear();
                for(a in alumnos){
                    alumn = alumnos[a];
                    telefono = alumn.telefono !== null ? alumn.telefono.replace(/[^0-9]+/g,'') : '';
                    celular = alumn.celular !== null ? alumn.celular.replace(/[^0-9]+/g,'') : '';
                    string_tel = '';
                    if(telefono != ''){
                        string_tel = telefono != celular && celular != '' ? `<a href="tel:${telefono}">${telefono}</a> / <a href="tel:${celular}">${celular}</a>` : `<a href="tel:${telefono}">${telefono}</a>`;
                    }

                    $buttonArsM = '';
                    $buttonListB = '';
                    if(parseInt(usrInfo.idTipo_Persona) == 36){
                        if(alumn.bitacora == null ||alumn.bitacora == ''){
                            $buttonArsM =  `<button class="btn btn-primary btn-sm mt-2" onclick="addBitacora(${alumn.idalumno},${alumn.idgeneracion},${alumn.idCarrera})"><i class="fas fa-list-alt"></i></button>`;
                        }
                        $buttonListB = `<button class="btn btn-primary btn-sm mt-2" data-target="#modalListProce" data-toggle="modal" onclick="ListBitacora(${alumn.idalumno})"><i class="fas fa-list-ul"></i></button>`;
                    }

                    // if(parseInt(usrInfo.estatus_acceso) == 4){
                    //     $input = `<input type="checkbox" onclick="groups(${alumn.idalumno})" />`;
                    // }else{
                    //     $input = "";
                    // }
                    var estatus = "";
                    switch(alumn.estatusGen){
                        case '1':
                            estatus = "Activo";
                            break;
                        case '2':
                            estatus = "Baja";
                            break;
                        case '3':
                            estatus = "Egresado";
                            break;
                        case '4':
                            estatus = "Titulado";
                            break;
                        case '5':
                            estatus = "Expulsado";
                            break;
                        case '6':
                            estatus = "Egresado";
                            break;
                            case '7':
                            estatus = "Bloqueado";
                            break;
                        default:
                            estatus = "Por Asignar";
                            break;
                    }

                    var notas = "";
                    if(alumn.notas != "" && alumn.notas != null){
                        //console.log(alumn.notas);
                        notas = `<i class='far fa-comment-dots' onClick ='VerComentariosDirectorio(${alumn.id_afiliado})'></i>`;
                    }
                    //notas = `<i class='far fa-comment-dots' onClick ='VerComentariosDirectorio(${alumn.id_afiliado})'></i>`;
                    
                    if(Band !=2){
                        tabla_directorio.row.add([
                            `${alumn.aPaterno} ${alumn.aMaterno} ${alumn.nombre} ${ "<br><b>"+estatus+"</b>"}`,
                            `${string_tel}`,
                            ` <a href="mailto:${alumn.email}"> <i class="fa fa-envelope"></i></a> ${alumn.email}`,
                            `${alumn.pais_nombre != '' && alumn.pais_nombre != null ? alumn.pais_nombre+' -' : ''} ${alumn.estado_nombre != '' && alumn.estado_nombre != null ? alumn.estado_nombre : ''},
                                ${alumn.ciudad.toUpperCase()}, ${alumn.colonia.toUpperCase()}, ${alumn.calle.toUpperCase()}`,
                            `${alumn.nombre_carrera}`,
                            `${alumn.nombre_generacion.substr(0, alumn.nombre_generacion.indexOf(' ',11))}`,
                            `${alumn.matricula}`,
                            ` <i class="fa fa-eye" style="cursor:pointer" onclick="$('#spn_${alumn.idalumno+'-'+a}').fadeToggle().delay(3000).fadeToggle()"></i>&nbsp;${notas}<br><span id="spn_${alumn.idalumno+'-'+a}" style="display:none;">${alumn.contrasenia}</span>`,
                            `<button class="btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#modalModificarDatosDirectorio" onclick="datosDirectorio(${alumn.idalumno},${alumn.idCarrera}, ${alumn.idgeneracion}, '${alumn.pais}','${alumn.pais_nacimiento}','${alumn.pais_estudio}','${alumn.idRelacion}')"><i class="fas fa-edit"></i></button> ${$buttonArsM}${$buttonListB}`
                        ])
                    }else{
                        tabla_directorio.row.add([
                            `${alumn.aPaterno} ${alumn.aMaterno} ${alumn.nombre}`,
                            `${string_tel}`,
                            ` <a href="mailto:${alumn.email}"> <i class="fa fa-envelope"></i></a> ${alumn.email}`,
                            `${alumn.nombre_carrera}`,
                            `${alumn.nombre_generacion.substr(0, alumn.nombre_generacion.indexOf(' ',11))}`,
                            `${alumn.matricula}`
                        ])
                    }
                }
                tabla_directorio.draw();
                selects_datatable('table_directorio')
            }catch(e){
                console.log(e);
            }
        }
    })
}//FIN directorio

function VerComentariosDirectorio(idAfiliado){
    $("#ComentariosAfiliado").modal("show");
    tablaComentarios(idAfiliado);

}

function tablaMaestros(){
    tAlumnos = $("#datatable-tablaMaestros").DataTable({
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
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarMaestros'},
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
        [0,'asc']
    ],
    });
}//FIN tablaMaestros

function tablaComentarios(idAfiliado){
    tComentario = $("#datatableComentariosAfiliados").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        searching: false,
        "dom" :'Bfrtip',
        buttons:[{
            extend: "excel",
            className: "d-none"
        }, {
            extend: "pdf",
            className: "d-none"
        }, {
            extend: "print",
            className: "d-none"
        }],
        "ajax": {
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {action: 'VerComentariosDirectorio',
                    'idAf': idAfiliado},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
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
}//Fin

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
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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

function editUs($us,$vals){

    //console.log(listedUsers);
    switch($vals){
        
        case 3:
            user = listedUsers.find(elem=> elem.idPersona == $us);   

            $('#CustomLabel').text('Editar Usuarios');
            $('#names').val(user.nombres);
            //$('#apa').val(aPaterno);
            //$('#ama').val(aMaterno);
            $('#email').val(user.email);
            $('#roles').val(user.estado);
            $('#modalAgregausers').modal();
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
$('#addU').on('submit',function(e){
    e.preventDefault();
    
    $form = new FormData(this);
    $form.append('action','createUs');

    $.ajax({
        type: 'POST',
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
//edit status de user
function updateStatusUsers($us,$vals){


    $form = new FormData();

    $form.append('action','createUs');
    $form.append('idUs', $us);
    $form.append('typeData',$vals);


    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: $form,
        contentType:false,
        processData:false,
        success: function(data){
            console.log(data);
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
$("#Comentario-document-alu").on("submit", function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'InsertarComentarioServicio');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
                        text: 'Se Notificará al Alumno',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#ComentarioArchivo").val("");
                        TablaRevision.ajax.reload(null,false);
                        TablaComentariosArchivo.ajax.reload(null,false);
                        tablaRevisionesServicio.ajax.reload(null,false);
                        tablaCorreccionesServicio.ajax.reload(null,false);
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
        className: "btn-primary d-none"
    }, {
        extend: "pdf",
        className: "d-none"
    }, {
        extend: "print",
        className: "d-none"
    }],
    "ajax": {
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
    TablaComentariosArchivo.page('last').draw(true);
}

function MostrarDocumentosAlumno(idDocumento,user){
    //iniciarTablaProcesosRevision(user);
    $("#ModalObservacionesDoc").modal("show");
    //$("#FormatosRevisionAlumno").modal("show");
    $("#idArchivo").val(idDocumento);
    tablacomentariosServicio(idDocumento);
    $(".cerrar").on("click",function(){
        $(".modal").css('overflow','auto');
    })
}


function agregarObservaciones(idDocumento,user,Nombre){
    iniciarTablaProcesosRevision(user);
    //$("#ModalObservacionesDoc").modal("show");
    $("#NombreDocumentos").text(Nombre);
    $("#FormatosRevisionAlumno").modal("show");
    $('html').css('overflow','hidden');
    $("#idArchivo").val(idDocumento);
    tablacomentariosServicio(idDocumento);
    $(".fromt").on("click",function(){
        $('html').css('overflow','auto');
        
    });
}

function SolicitarOriginales(iddocumento,idAlumno){
    Swal.fire({
        text: '¿Está  seguro de cambiar el estatus del documento?',
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
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {action: 'CambiarestatusServicio',
                    idAlu: idAlumno,
                        },
               
                success: function(data){
                    if(data == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se cargó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                    try{
                        //console.log("Cambiando estatus del docuemnto");
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Actualizado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                if($("#Solicitar-select-servicio").DataTable().ajax.url() !== null){
                                    $("#Solicitar-select-servicio").DataTable().ajax.reload(null, false);
                                }
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }   
                }
            });
        }
    });

}

function VerificarEntrega(iddocumento,idAlumno){
    Swal.fire({
        text: '¿Está  seguro de concluir el servicio social del alumno?',
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
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {action: 'CambiarestatusServicioConcluido',
                    idAlu: idAlumno,
                        },
               
                success: function(data){
                    if(data == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se cargó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                    try{
                        //console.log("Cambiando estatus del docuemnto");
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Actualizado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                //console.log("Cambiado!");
                                //
                                if($("#Solicitar-select-servicio").DataTable().ajax.url() !== null){
                                    $("#Solicitar-select-servicio").DataTable().ajax.reload(null, false);
                                }
                                //TablaSolicitaDocumentos();
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }   
                }
            });
        }
    });

}
function formatoListo(idDocumento){
    Swal.fire({
        text: '¿Está  seguro de cambiar el estatus del documento?',
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
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {action: 'documentoCambioListo',
                    idArchivo: idDocumento,
                        },
               
                success: function(data){
                    if(data == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se cargó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                    try{
                        //console.log("Cambiando estatus del docuemnto");
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Actualizado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                //Ponerasd
                                tablaCorreccionesServicio.ajax.reload(null,false);
                                TablaRevision.ajax.reload(null,false);
                                //LlenarTablaServicio();
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }   
                }
            });
        }
    });
}
function iniciarTablaProcesosRevision(user){
	//var user = $("#numeroAfiliado").val(); 
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
			url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
function tablasesionesenvivo(){
    sesionesenvivo = $("#datatable-tablasesionesenvivo").DataTable({
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
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarsesionesenvivo'},
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
    'iDisplayLength': 15,
    'order':[
        [0,'asc']
    ],
    });
}//FIN tablaMaestros
function Tabla_PreguntasExPas(){
    sesionesenvivo = $("#datatable-tablaPreguntas2").DataTable({
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
    });
}//FIN TabLApREGUNTAS
function Tabla_Banco(){
    sesionesenvivo = $("#Tabla-Banco-preguntas").DataTable({
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
            'Examen': 'Examen',
            'Carrera': 'Carrera'
        }
    },
    'bDestroy': true,
    'iDisplayLength': 15,
    'order':[
        [0,'asc']
    ],
    });
}//FIN TabLApREGUNTAS
function Tabla_PreguntasExPas2(){
    sesionesenvivo = $("#datatable-tablaPreguntas3").DataTable({
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
    });
}//FIN TabLApREGUNTAS

/*function tablaAsistenciasClases(){
    tAlumnos = $("#datatable-tablaAsisteciasClases").DataTable({
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
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarAsistenciaClases'},
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
        [0,'asc']
    ],
    });
}//FIN tablaAsistenciaClases*/

function AddGroups($id){

    i = 0;
    $('.checkGroup').each(function(elem){

        if($(this).is(":checked")){
            i++;
        }
    });

    if(i > 0){
        $('#formAddGroup').removeClass('hidden');
    }else{
        $('#formAddGroup').addClass('hidden');
    }


    
    inputs = `<input type="hidden" name="idAlums[]" id="idAlumn${$id}" value="${$id}">`;
    if(!document.body.contains(document.getElementById("idAlumn"+$id))){
        $('#formAddGroup').append(inputs);
    }else{
        document.getElementById("formAddGroup").removeChild(document.getElementById("idAlumn"+$id));
    }
    $('#formAddGroup').on('submit',function(e){

        e.preventDefault();
        $form = $(this).serialize();
        $.ajax({
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: $form,
            success:function(data){

                json = JSON.parse(data);
                if(json.estatus == 'ok'){
                    swal(`Alumnos agregados a grupo ${json.group}`);
                    tableAsignarGrupo(json.idG);
                    $('#selectBuscarAGrupo').val("0");
                }else{
                    swal(`Error intenta de nuevo`);
                    location.reload();
                }
            },
        });
    });

}
function selectGroup(){

    idGeneracion = $("#S_generacionesExpedientes,#selectBuscarGeneracionesGrupo").val();

    if(idGeneracion == undefined){
        idGeneracion = $("#selectBuscarGeneraciones").val();
        
        if(idGeneracion == undefined ){
            idGeneracion = $("#generacionDirectorio").val();
            if(idGeneracion == undefined && idGeneracion == null){
                idGeneracion = $("#SGeneraciones").val().split("-")[0];
            }
        }
    }

    gen = listedGenerations.find(el=> el.idGeneracion == idGeneracion);
    
    countG = gen.grupos;
    groups = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","U","V","W","X","Y","Z"];
    
    opciones = '<select name="s_group_op" id="s_group_op" class="form-control" onchange="tablaAlumnos()" required>';
    opciones += '<option value="0" class="form-control">Seleccione un grupo...</option>';
    options = '<option value="0" class="form-control">Seleccione un grupo...</option>';

    for(i =0; i < countG; i++){
        opciones += '<option value="'+groups[i]+'" class="form-control">'+groups[i]+'</option>';
        options += '<option value="'+groups[i]+'" class="form-control">'+groups[i]+'</option>';
    }
    opciones += '</select>';
    $('#groupsG').html(opciones);
    $('#selectBuscarGrupo').html(options);
    $('#generacionGrupo').html(opciones);
    $('#selectBuscarAGrupo').html(options);
    $('#selectBuscarAGrupo').html(options);
    $('#generacionDirectorioGrupo').html(options);

}

function tablaAlumnos(){

    idCarrera = document.getElementById("S_carrerasExpedientes").value;
    idGeneracion = document.getElementById("S_generacionesExpedientes").value;
    groupG = '';
    if(Band == 4){
    groupG = ''; //document.getElementById("s_group_op").value;
    }
    //console.log( "Id carrera -> " + idCarrera );

    var select = document.getElementById("S_generacionesExpedientes") //El <select>
       
        text = select.options[select.selectedIndex].innerText;
        //console.log(text);
         //El texto de la opción seleccionada

    tAlumnos = $("#datatable-tablaAlumnos").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "dom" :'Bfrtip',
    buttons:[{
        extend: "excel",
        className: "btn-primary"
    }, {
        extend: "pdf",
        title:'Expediente',
        
        orientation: 'landscape',
        pageSize: 'LEGAL',
        exportOptions: {
            columns: [ 0,1,2,3,4,6],
        }
       
    // }, {
    //     extend: "print",
    //         title:'Expediente',
    //         exportOptions: {
    //             columns: [ 0,2,3,4],
    //         },
    //         customize: function ( win ) {
    //             $(win.document.body).find( 'thead' ).prepend(text);
    //         }

    // }
    }],
    "ajax": {
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php?idCarrera='+idCarrera+'&idGeneracion='+idGeneracion+'&groupG='+groupG,
        type: 'POST',
        data: {action: 'consultarAlumnos'},
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
        [0,'asc']
    ],
    'fnRowCallback': function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
		if(aData.length == 8){
			if(aData[7] != "0"){
				$('td:eq(0)',nRow).css('background-color', '#4478AA');
				$('td:eq(0)',nRow).css('color', 'white');
				$('td:eq(1)',nRow).css('background-color', '#4478AA');
				$('td:eq(1)',nRow).css('color', 'white');
				$('td:eq(2)',nRow).css('background-color', '#4478AA');
				$('td:eq(2)',nRow).css('color', 'white');
				$('td:eq(3)',nRow).css('background-color', '#4478AA');
				$('td:eq(3)',nRow).css('color', 'white');
				$('td:eq(4)',nRow).css('background-color', '#4478AA');
				$('td:eq(4)',nRow).css('color', 'white');
			}
			aData.pop();
		}
      }
    });
}//FIN tablaAlumnos

function tablaExpediente( id, nombre ){
    $("#modalverExpediente").modal("show");
    document.getElementById("idExp").value = id;
    document.getElementById('divA').textContent = nombre;

    tAlumnos = $("#datatable-tablaExpediente").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "ajax": {
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarExpediente', idBuscar: id},
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
        [0,'asc']
    ],
    });
}//FIN tablaAlumnos

$("#formverExpediente").on('submit',function(e){

    id = document.getElementById("idExp").value;
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'validarExpediente');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
                        title: 'Sus cambios han sido guardados.',
                        icon: 'success',
                        text: '\r',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formverExpediente")[0].reset();
                        tablaExpediente( id, document.getElementById('divA').textContent );
                        document.getElementById('btnEdit').disabled = true;
                        tablaAlumnos()
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
})//Fin #formverExpediente

function mostrarMaestro( id ){

    document.getElementById('idMaestro').value = id;
    document.getElementById('btnEditMaestroE').disabled = true;
    $("#formMostrarMaestro")[0].reset();

    Data = {
        action: 'buscarMaestro',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                $("#modalMostrarMaestro").modal("show");
                pr = JSON.parse(data);
                $("#nombres_em").val(pr.data[0].nombres);
                $("#aPaterno_em").val(pr.data[0].aPaterno);
                $("#aMaterno_em").val(pr.data[0].aMaterno);
                $("#email_em").val(pr.data[0].email);
                $("#telefono_em").val(pr.data[0].telefono);
                $("#idMaestro").val(id);

                sH = document.getElementById( 'sexoH_em' );
                sM = document.getElementById( 'sexoM_em' );
                
                if( pr.data[0].sexo == "M" ){
                    sM.checked = true;                
                    sH.checked = false;
                }else{
                    sM.checked = false;                
                    sH.checked = true;
                }
                
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

}//fin mostrarMaestro

function AsignacionMaterias( id, nombre ){

    document.getElementById('divA2').textContent = "Maestro(a): "+nombre;
    document.getElementById('op_materias').innerHTML = '';

    document.getElementById('btn_mostrarAsignacionMaterias').disabled = true;
    $("#form_mostrarAsignacionMaterias")[0].reset();

    Data = {
        action: 'buscarCarrerasMaestro',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                $("#modal_mostrarAsignacionMaterias").modal("show");
                pr = JSON.parse(data);
                console.log( pr.length );

                if( pr.length > 0 ){
                    opciones = '<select name="s_carrerasop" id="s_carrerasop" class="form-control" onchange="verMaterias()">';
                    opciones += '<option value="0" class="form-control">Seleccione carrera*...</option>';
                
                    for( i = 0; i < pr.length; i++ )
                        opciones += '<option value="'+pr[i]['idCarrera']+'" class="form-control">'+pr[i]['nombre']+'</option>';

                    opciones += '</select>';
                    document.getElementById('s_carreras').innerHTML = opciones;
                }else{
                    document.getElementById('s_carreras').innerHTML = "<span style='color:red;'>El usuario no cuenta con carreras asignadas. Vaya a Editar para seleccionarlas</span>";
                    //No hay carreras asignadas. Vaya a Editar Maestro.
                }
                
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

}//fin mostrarAsignacionMaterias

function verMaterias(){
    idCarrera = document.getElementById("s_carrerasop");
    document.getElementById('op_materias').innerHTML = '';

    Data = {
        action: 'buscarMaterias',
        idBuscar: idCarrera.value
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);
                checks = '';
                
                for( i = 0; i < pr.length; i++ )
                    checks += '<label style="width:100%"><a class="list-group-item list-group-item-action"><input name="checkbox'+i+'" type="checkbox" id="checkbox'+i+'" value="'+pr[i]['idMateria']+'"> '+pr[i]['nombre']+'</input></a></label>';
                
                document.getElementById('op_materias').innerHTML = checks;
                
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


}//verMaterias

function carrerasActuales( id ){

    Data = {
        action: 'carrerasActuales',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{

                pr = JSON.parse(data);
                carreras_actuales = [];                
                carreras_actuales = pr.carreras;                    
                
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

}//fin carrerasActuales

//editarMaestro
$("#formMostrarMaestro").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'editarMaestro');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
                        title: 'Editado Correctamente',
                        icon: 'success',
                        text: '\r',
                        button: false,
                        timer: 1500,
                    }).then((result)=>{
                        $("#formMostrarMaestro")[0].reset();
                        tablaMaestros();
                        $("#modalMostrarMaestro").modal("hide");
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
})//Fin editarMaestro

function EliminarFormato(idarchivo,archivo){
    //$("#EditarProceso").modal("show");
    Swal.fire({
        text: '¿Está  seguro de eliminar el Formato Seleccionado?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            console.log("Eliminando");
             //Ajax para eliminar un proceso acorde al id 
            $.ajax({
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {action: 'EliminarFormatoRec',
                        idarch: idarchivo,
                        nombre: archivo
                        },
               
                success: function(data){
                    if(data == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se cargó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                    try{
        
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Eliminado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                //Ponerasd
                                tabladocservicio.ajax.reload(null,false);
                                //LlenarTablaServicio();
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }   
                }
            });
        }
    });
}

$("#formularioEditarProceso").on("submit",function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'EditarProcesoBase');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                var pr = JSON.parse(data);
                if(pr.data > 0){
                    swal({
                        title: "Editado Correctamente!",
                        text: "El proceso se mostrara en breve",
                        icon: "success",
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result)=>{
                        //$("#formulario-procesos-nuevos")[0].reset();
                        tablaprocesosnuevos.ajax.reload();
                        LlenarTablaServicio();
                        $("#EditarProceso").modal("hide");
                    });
                }else{
                    swal({
                        title: "Sin cambios detectados!",
                        text: "El proceso se mostrara en breve",
                        icon: "error",
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
               
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});

function EditarProceso(idproceso,nombre,orden){
    $("#EditarProceso").modal("show");
    
    $("#idProcesoEditar").val(idproceso);
    $("#EditarNombreProceso").val(nombre);
    $("#EditarOrdenProceso").val(orden);
}

function EliminarProceso(idproceso){
    //$("#EditarProceso").modal("show");
    Swal.fire({
        text: '¿Está  seguro de eliminar el proceso Seleccionado?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            console.log("Eliminando");
             //Ajax para eliminar un proceso acorde al id 
            $.ajax({
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {action: 'EliminarProcesoRec',
                        idproc: idproceso
                        },
               
                success: function(data){
                    if(data == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se cargó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                    try{
        
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Eliminado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                tablaprocesosnuevos.ajax.reload(null,false);
                                LlenarTablaServicio();
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }   
                }
            });
        }
    });
}

function mostrarCarreras( selectn ){

    if( selectn == 'checkboxes2') carreras_actuales = [];
    cont_carreras = 0;

    Data = {
        action: 'buscarCarreras',
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{

                pr = JSON.parse(data);
                document.getElementById(selectn).innerHTML = '';
                check = '';
                cont_carreras = 0;
                
                for( i = 0; i < pr['aaData'].length; i++ ){
                    if( carreras_actuales.includes( pr['aaData'][i][1] ) ) check = 'checked'; else check = '';
                    document.getElementById( selectn ).innerHTML += '<label>&nbsp;<input type="checkbox" '+check+' id="'+selectn+'cc'+i+'" name="'+selectn+'cc'+i+'" value="'+pr['aaData'][i][1]+'" onClick="contarCarreras( \''+selectn+'cc'+i+'\' );"/> '+pr['aaData'][i][0]+'</label>';
                }//Fin for

                document.getElementById( selectn+'cont_carreras').value = pr['aaData'].length;

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
}//fin mostrarCarreras
function validarDesactivarMaestro(idMaestro, estado){
    Swal.fire({
        text: '¿Confirma que desea activar/desactivar al Maestro?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            desactivarMaestro(idMaestro, estado);
        }
    })
}//fin validarDesactivarMaestro

function desactivarMaestro(idMaestro, estado){
    Data = {
        action: "desactivarMaestro",
        idDesactivar: idMaestro,
        vEstado: estado
    }

    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success : function (data) {
            if (data == 'no_session') {
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La información no se actualizó",
                    icon: "info",
                });
                setTimeout(function () {
                    window.location.replace("index.php");
                }, 2000);
            }
            try {
                if (data != 'no_session') {
                    swal({
                        title: 'Cambios guardados',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1500,
                    })
                        .then((result) => {
                            tablaMaestros();
                        });
                }
            } catch (e) {
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
            
        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
}//finDesactivarMedico

$("#formAgregarMaestro").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'agregarMaestro');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
                        title: 'Agregado Correctamente',
                        icon: 'success',
                        text: '\r',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formAgregarMaestro")[0].reset();
                        tablaMaestros();
                        $("#modalAgregarMaestro").modal("hide");
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
})//Fin AgregarMaestro
/*
function tablaExamenes(){
    tAlumnos = $("#datatable-tablaExamenes").DataTable({
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
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarExamenes'},
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
        [0,'asc']
    ],
    });
}//FIN tablaExamenes*/

function contarCarreras( c ){
    if( document.getElementById( c ).checked ) cont_carreras++;
    else cont_carreras--;
}

function requerido( no ){
    if( document.getElementById("select"+no).value == 2 )
        document.getElementById("comentario"+no).required = true;
    else   
        document.getElementById("comentario"+no).required = false;
}//función requerido

function showCheckboxes( echeck ) {
  var checkboxes = document.getElementById( echeck );
  if (!expanded){
    checkboxes.style.display = "block";
    expanded = true;
  }else{
    checkboxes.style.display = "none";
    expanded = false;
  }
}//fin showCheckboxes

function listarCarreras(){

    if( document.getElementById('lista_carreras').innerHTML != '' ){
        document.getElementById('lista_carreras').innerHTML = '';
        return;
    }

    Data = {
        action: 'listarCarreras',
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);
                //console.log( pr );
                opciones = '';
                var opt = '<option disabled selected>Seleccione una carrera</option>';
                for( i = 0; i < pr.length; i++ ){
                    opciones += '<label style="width:100%"><a class="list-group-item list-group-item-action"><input name="checkbox_c'+i+'" type="checkbox" id="checkbox_c'+i+'" value="'+pr[i]['idCarrera']+'"> '+pr[i]['nombre']+'</input></a></label>';
                    opt += '<option value="'+pr[i]['idCarrera']+'">'+pr[i]['nombre']+'</option>';
                }

                document.getElementById('lista_carreras').innerHTML = opciones;
                $("#select_carreras_edit").html( opt );
                document.getElementById('total_carreras').value = pr.length;
                
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

}//fin listarCarreras

function resetCarrerasE(){
    document.getElementById('lista_carrerasE').innerHTML = '';
    document.getElementById('total_carrerasE').value = 0;
}

function listarCarrerasE(){

    if( document.getElementById('lista_carrerasE').innerHTML != '' ){
        document.getElementById('lista_carrerasE').innerHTML = '';
        document.getElementById('total_carrerasE').value = 0;
        return;
    }
    document.getElementById('total_carrerasE').value = 0;

    Data = {
        action: 'listarCarrerasE',
        idBuscar: document.getElementById('idMaestro').value
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);
                carreras = pr.data;
                carreras_m = pr.cseleccion;
                opciones = '';
                
                for( i = 0; i < carreras.length; i++ ){

                    const found = carreras_m.find(element => element.idCarrera == carreras[i]['idCarrera'] );
                    if( found ) $ch = "checked";
                    else $ch = "";
                    opciones += '<label style="width:100%"><a class="list-group-item list-group-item-action"><input name="checkbox_ce'+i+'" type="checkbox" '+$ch+' id="checkbox_ce'+i+'" value="'+carreras[i]['idCarrera']+'"> '+carreras[i]['nombre']+'</input></a></label>';
                }

                document.getElementById('lista_carrerasE').innerHTML = opciones;
                document.getElementById('total_carrerasE').value = carreras.length;
                
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

}//fin listarCarreras

function AsignarClase( id, nombre ){

    $("#formAsignarClase")[0].reset();
    document.getElementById('carrerasGeneraciones').innerHTML = "";
    document.getElementById('ciclos').innerHTML = "";
    document.getElementById('materias_asignacion').innerHTML = "";
    document.getElementById('btnAsignarClase').disabled=true;

    Data = {
        action: 'listarCarrerasAsignacion',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                $("#modalAsignarClase").modal("show");
                /*$("#aumentoControlado").val(0);
                $("#aumentoControladoApoyo").val(0);*/
                document.getElementById( 'nclase' ).textContent = "Asignar clase a "+nombre;
                document.getElementById('idMaestroAsignacion').value = id;
                pr = JSON.parse(data);

                if( pr.length > 0 ){
                    opciones = '<label>Seleccione carrera.</label><select name="ScarrerasAsignacion" id="ScarrerasAsignacion" class="form-control" onchange="seleccionarGeneracion();">';
                    opciones += '<option value="0" class="form-control">Seleccione carrera...</option>';
                    
                    for( i = 0; i < pr.length; i++ )
                        opciones += '<option value="'+pr[i]['idCarrera']+'" class="form-control">'+pr[i]['nombre']+'</option>';
                    
                    opciones += '</select>';
                    document.getElementById('carrerasAsignacion').innerHTML = opciones;
                    document.getElementById('select_carreras_edit').innerHTML = opciones;
                }else{
                    document.getElementById('carrerasAsignacion').innerHTML = "<span style='color:red;'>El usuario no cuenta con carreras asignadas. Vaya a Editar para seleccionarlas";
                    //No hay carreras asignadas. Vaya a Editar Maestro.</span>
                }
                
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

}//fin AsignarClase

function seleccionarGeneracion(){

    idCarrera = document.getElementById( "ScarrerasAsignacion" ).value;
    document.getElementById('ciclos').innerHTML = "";
    document.getElementById('materias_asignacion').innerHTML = "";
    document.getElementById('btnAsignarClase').disabled=true;

    Data = {
        action: 'listarGeneraciones',
        idBuscar: idCarrera
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                tableSessions();
                pr = JSON.parse(data);
                opciones = '<label>Seleccionar generación.</label><select name="SGeneraciones" id="SGeneraciones" class="form-control" onchange="seleccionarCiclo();">';
                opciones += '<option value="0" class="form-control">Seleccione generación...</option>';
                if(listedGenerations.length > 0){
                    listedGenerations = [];
                }

                listedGenerations = pr;

                for( i = 0; i < pr.length; i++ )
                    opciones += '<option value="'+pr[i]['idGeneracion']+'-'+pr[i]['id_plan_estudio']+'" class="form-control" >'+pr[i]['nombre']+'</option>';

                opciones += '</select>';
                document.getElementById('carrerasGeneraciones').innerHTML = opciones;
                
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

}//fin SeleccionarGeneracion

function seleccionarCiclo(){

    var datos = document.getElementById( "SGeneraciones" ).value.split("-");
    document.getElementById('materias_asignacion').innerHTML = "";
    document.getElementById('btnAsignarClase').disabled=true;


    //selectGroup();

    Data = {
        action: 'listarCiclos',
        idGeneracion: datos[0],
        idPlan: datos[1]
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);

                if( pr.length > 0 ){
                    opciones = '<label>Seleccione el ciclo</label><select name="Sciclos" id="Sciclos" class="form-control" onchange="seleccionarMateria();">';
                    opciones += '<option value="0" class="form-control">Seleccione ciclo...</option>';
                    
                    for( i = 0; i < pr.length; i++ ){
                        if( pr[i]['tipo_ciclo'] == 1 ) $ciclon = "Cuatrimestre";
                        else if( pr[i]['tipo_ciclo'] == 2 ) $ciclon = "Semestre";
                        else $ciclon = "Trimestre";
                        opciones += '<option value="'+pr[i]['ciclo_asignado']+'" class="form-control">'+$ciclon+' '+pr[i]['ciclo_asignado']+'</option>';
                    }

                    opciones += '</select>';
                    document.getElementById('ciclos').innerHTML = opciones;
                }//fin if
                else {
                    document.getElementById('ciclos').innerHTML = "<span style='color:red;'>No hay ciclos disponibles. Vaya a Gestor Carreras > Planes de Estudios.</span>";
                }
                
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
}//SeleccionarCiclo

function seleccionarMateria(){

    var datos = document.getElementById( "SGeneraciones" ).value.split("-");
    document.getElementById('btnAsignarClase').disabled=false;

    Data = {
        action: 'listarMaterias',
        idCiclo: document.getElementById( "Sciclos" ).value,
        idPlan: datos[1]
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);

                opciones = '<div class="border p-2" id="border-d" style="display: none;"><label>Seleccione la materia.</label><select name="Smaterias" id="Smaterias" class="form-control mb-2" required>';

                for( i = 0; i < pr.length; i++ ){
                    opciones += '<option value="'+pr[i]['id_materia']+'" class="form-control">'+pr[i]['nombre']+'</option>';
                }

                opciones += '</select>';
                opciones += '<br><label>Especifique el nombre para la clase.</label><input id="nombre_clase" name="nombre_clase" class="form-control mb-2" type="text" placeholder="Nombre de la clase..." required></input><br>';
                opciones += 'Día y hora de la clase: <br><input id="fecha_clase" name="fecha_clase" class="form-control mb-2" type="datetime-local" required></input><br>';
                /*opciones += 'Video de la clase: <br><input id="video" name="video" class="form-control" type="url" pattern="https://.*" placeholder="https://www.ejemplo.com/video.mp4"></input><br>';

                opciones += 'Foto para la clase: <br><input name="foto_clase" type="file" id="foto_clase" class="btn btn-info active" accept=".jpg, .png, .jpeg" required>';*/
                //opciones += 'Foto para la clase: <br><input name="foto_clase" type="file" id="foto_clase" class="btn btn-info active" accept=".jpg, .png, .jpeg" onchange="return fileValidation(\'foto_clase\')" required>';

                /*opciones += '<button type="button" id="btnAgregarRecurso" class="btn btn-dark waves-effect waves-light" style="width:100%;" onclick="agregarInputRecurso();"><i class="far fa-plus-square"></i> Agregar recurso</button>';

                opciones += '<div class="form-group" id="divInputRecursos"><br></div>';
                
                opciones += '<button type="button" id="btnAgregarApoyo" class="btn btn-dark waves-effect waves-light" style="width:100%;" onclick="agregarInputApoyo();"><i class="far fa-plus-square"></i> Agregar Apoyo</button>';
                
                opciones += '<div class="form-group" id="divInputApoyo"><br></div></div>';*/

              
                document.getElementById('materias_asignacion').innerHTML = opciones;
                document.getElementById('total_materias_asignacion').value = pr.length;
                $("#border-d").fadeIn();
                
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
    
}//seleccionarMateria

function agregarRecurso(){
    total_recursos = document.getElementById( "total_recursos" ).value;
    document.getElementById('dr'+(++total_recursos)).style.display="block";
    document.getElementById( "total_recursos" ).value = total_recursos;
}//agregarRecurso

/*$("#btnAgregarRecurso").on('click', function(){
    console.log('hola');
    id++;
})*/

//var id = 0;
function agregarInputRecurso(){
    var id = $("#aumentoControlado").val();
    var numeroRecurso = id; 
    //var botonElement = document.getElementById('btnAgregarRecurso');
    $("#divInputRecursos").append($('<label>').text("Nombre del archivo "+(++numeroRecurso))).append('</label>').
                            append($('<input>').
                            attr('type', 'text').
                            attr('class', 'form-control').
                            attr('id', 'nombreArchivo'+id).
                            attr('name', 'nombreArchivo'+id).
                            attr('placeholder','Ingresa el nombre del archivo').
                            attr('required', '')).append('</input><br>').
                            append($('<label>').text("Cargar archivo "+numeroRecurso)).append('</label>').
                            append($('<input>').
                            attr('type', 'file').
                            attr('class', 'form-control').
                            attr('id', 'archivo'+id).
                            attr('name', 'archivo'+id).
                            attr('required', '').
                            attr('accept', '.jpg, .png, .jpeg, .pdf, .docx, .pptx, .xlsx')).append('</input><br>');
    id++;
    $("#aumentoControlado").val(id); 
}
function agregarInputApoyo(){
    var idApoyo = $("#aumentoControladoApoyo").val();
    var numeroApoyo = idApoyo;
    //var botonElement = document.getElementById('btnAgregarRecurso');
    $("#divInputApoyo").append($('<label>').text("Nombre del archivo "+(++numeroApoyo))).append('</label>').
                            append($('<input>').
                            attr('type', 'text').
                            attr('class', 'form-control').
                            attr('id', 'nombreArchivoApoyo'+idApoyo).
                            attr('name', 'nombreArchivoApoyo'+idApoyo).
                            attr('placeholder','Ingresa el nombre del archivo').
                            attr('required', '')).append('</input><br>').
                            append($('<label>').text("Cargar archivo "+numeroApoyo)).append('</label>').
                            append($('<input>').
                            attr('type', 'file').
                            attr('class', 'form-control').
                            attr('id', 'archivoApoyo'+idApoyo).
                            attr('name', 'archivoApoyo'+idApoyo).
                            attr('required', '').
                            attr('accept', '.jpg, .png, .jpeg, .pdf, .docx, .pptx, .xlsx')).append('</input><br>');
    idApoyo++;
    $("#aumentoControladoApoyo").val(idApoyo);
}
/*
$("#btnAsignarClaseProfesor").on('click', function(){
    $("#aumentoControlado").val(0);  
})*/

function quitarRecurso( i ){
    document.getElementById( 'dr'+i ).style.display="none";
    document.getElementById( 'nr' + i ).value="";
    document.getElementById( 'urlr' + i ).value="";
}//quitarRecurso

function agregarApoyo(){
    total_apoyos = document.getElementById( "total_apoyos" ).value;
    document.getElementById('dy'+(++total_apoyos)).style.display="block";
    document.getElementById( "total_apoyos" ).value = total_apoyos;
}//agregarAPoyo

function quitarApoyo( i ){
    document.getElementById( 'dy'+i ).style.display="none";
    document.getElementById( 'ny' + i ).value="";
    document.getElementById( 'urly' + i ).value="";
}//quitarAPoyo

function fileValidation( $nombre ){
    var fileInput = document.getElementById( $nombre );
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.png)$/i;
    var fileInput = document.getElementById($nombre);
    var files = fileInput.files;
    
    if(!allowedExtensions.exec(filePath) || files[0].size > 2097152 ){
        alert('El archivo debe pesar máximo 2Mb y ser JPG o PNG.');
        fileInput.value = '';
        filePath.value='';
        return false;
    }
}//FileValidation

$("#formAsignarClase").on('submit',function(e){

    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'AsignarClase');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
            if(data == 'documento_extension_mal'){
                Swal.fire({
                    title: 'Un archivo de la sección "recursos" es incorrecto el formato.',
                    text: 'Por favor, cambia el documento que no tenga extensión: JPG, JPEG, PNG, XLSX, PPTX, DOCX o PDF.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 7500
                })
            }
            if(data == 'extensiones_mal'){
                Swal.fire({
                    title: 'Archivos con formato incorrecto en recursos y apoyos.',
                    text: 'Por favor, cambia los documento que no tenga extensión: JPG, JPEG, PNG, XLSX, PPTX, DOCX o PDF.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 7500
                })
            }
            if(data == 'apoyo_extension_mal'){
                Swal.fire({
                    title: 'Un archivo de la sección "apoyos" es incorrecto el formato.',
                    text: 'Por favor, cambia el documento que no tenga extensión: JPG, JPEG, PNG, XLSX, PPTX, DOCX o PDF.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 7500
                })
            }
            if(data == 'image_extension_mal'){
                Swal.fire({
                    title: 'Imagen incorrecta.',
                    text: 'La imagen de la clase debe ser: JPG, JPEG o PNG.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 4000
                })
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Sus cambios han sido guardados.',
                        icon: 'success',
                        text: '\r',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formAsignarClase")[0].reset();
                        $("#modalAsignarClase").modal("hide");
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
})//Fin #formAsignarClase

function tablaClases( id, nombre ){
	$('#clases-tab').click()
    $("#inputs_materiales").html("");
    $("#inputs_recursos").html("");
    $("#btn_agregarMaterial").addClass("d-none");
    $("#btn_agregarRecurso").addClass("d-none");
	
    $("#modalVerClases").modal("show");
    document.getElementById('tclases').textContent = "Clases de "+nombre;

    tClases = $("#datatable-tablaClases").DataTable({
    responsive: true,
    Processing: true,
    ServerSide: true,
    "ajax": {
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarClasesMaestros', idBuscar: id},
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
        [0,'desc']
    ],
    });

    tClases.columns.adjust()
}//FIN tablaClases

function validarDesactivarClase(idMaestro, idClase, estado){
    Swal.fire({
        text: '¿Confirma que desea activar/desactivar la clase?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            desactivarClase(idMaestro, idClase, estado);
        }
    })
}//fin validarDesactivarClase

function desactivarClase(idMaestro, idClase, estado){

    nombre = document.getElementById('tclases').textContent;
    nombre = nombre.substring(9, nombre.length);

    Data = {
        action: "desactivarClase",
        idDesactivar: idClase,
        vEstado: estado
    }

    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success : function (data) {
            if (data == 'no_session') {
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La información no se actualizó",
                    icon: "info",
                });
                setTimeout(function () {
                    window.location.replace("index.php");
                }, 2000);
            }
            try {
                if (data != 'no_session') {
                    swal({
                        title: 'Cambios guardados',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1500,
                    })
                        .then((result) => {
                            tablaClases( idMaestro, nombre );
                        });
                }
            } catch (e) {
                console.log(e);
                console.log(data);
            }
        },
        error: function(){
            
        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
}//finDesactivarClase

function AsignarClaseE( id, idClase){

    $("#formAsignarClase")[0].reset();
    nombre = "????";
    document.getElementById('carrerasGeneraciones').innerHTML = "";
    document.getElementById('ciclos').innerHTML = "";
    document.getElementById('materias_asignacion').innerHTML = "";
    document.getElementById('btnAsignarClase').disabled=true;

    Data = {
        action: 'listarCarrerasAsignacion',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                $("#modalAsignarClaseE").modal("show");
                $("#modalVerClases").modal("hide");
                document.getElementById( 'nclaseE' ).textContent = "Editar clase a "+nombre;
                document.getElementById('idMaestroAsignacionE').value = id;
                pr = JSON.parse(data);

                if( pr.length > 0 ){
                    opciones = '<select name="ScarrerasAsignacionE" id="ScarrerasAsignacionE" class="form-control" onchange="seleccionarGeneracion();">';
                    opciones += '<option value="0" class="form-control">Seleccione carrera...</option>';
                    
                    for( i = 0; i < pr.length; i++ )
                        opciones += '<option value="'+pr[i]['idCarrera']+'" class="form-control">'+pr[i]['nombre']+'</option>';

                    opciones += '</select>';
                    document.getElementById('carrerasAsignacionE').innerHTML = opciones;
                }else{
                    document.getElementById('carrerasAsignacionE').innerHTML = "<span style='color:red;'>El usuario no cuenta con carreras asignadas. Vaya a Editar para seleccionarlas</span>";
                    //No hay carreras asignadas. Vaya a Editar Maestro.
                }
                
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

}//fin AsignarClaseE

function listarCarrerasExpedientes(){

    /*idCarrera = document.getElementById( "ScarrerasAsignacion" ).value;
    document.getElementById('ciclos').innerHTML = "";
    document.getElementById('materias_asignacion').innerHTML = "";
    document.getElementById('btnAsignarClase').disabled=true;*/

    Data = {
        action: 'listarCarrerasExpedientes',
        //idBuscar: idCarrera
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);
                //console.log( pr );
                opciones = '<select name="S_carrerasExpedientes" id="S_carrerasExpedientes" class="form-control" onchange="listarGeneracionesExpedientes();">';
                opciones += '<option value="0" class="form-control">Seleccione carrera...</option>';
                
                for( i = 0; i < pr.length; i++ )
                    opciones += '<option value="'+pr[i]['idCarrera']+'" class="form-control" >'+pr[i]['nombre']+'</option>';

                opciones += '</select>';
                
                if(document.getElementById('S_carrerasExpedientesC')){
                    document.getElementById('S_carrerasExpedientesC').innerHTML = opciones;
                }
                //document.getElementById('S_carrerasExpedientesC').innerHTML = opciones;
                
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

}//fin listarCarrerasExpedientes

function listarGeneracionesExpedientes(){

    idCarrera = document.getElementById( "S_carrerasExpedientes" ).value;
    /*document.getElementById('ciclos').innerHTML = "";
    document.getElementById('materias_asignacion').innerHTML = "";
    document.getElementById('btnAsignarClase').disabled=true;*/
    //console.log(idCarrera);

    Data = {
        action: 'listarGeneracionesExpedientes',
        //idBuscar: idCarrera
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php?idCarrera='+idCarrera,
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se cargó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data);

                // if(Band == 4){
                //     $groups = 'selectGroup()';
                // }else{
                //     $groups = 'tablaAlumnos()';
                // }
                $groups = 'tablaAlumnos()';
                //console.log( pr );
                opciones = '<select name="S_generacionesExpedientes" id="S_generacionesExpedientes" class="form-control" onchange="'+$groups+'">';
                opciones += '<option value="0" class="form-control">Seleccione generación...</option>';
                
                if(listedGenerations.length > 0){
                    listedGenerations = [];
                }
                listedGenerations = pr;

                for( i = 0; i < pr.length; i++ )
                    
                    opciones += '<option value="'+pr[i]['idGeneracion']+'" class="form-control" >'+pr[i]['nombre']+'</option>';

                opciones += '</select>';
                document.getElementById('S_generacionesExpedientesC').innerHTML = opciones;
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

}//fin listarCarrerasExpedientes

function verAsistencias(id){
    Data = {
        action: 'validarAsistencias',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data == 'sin_asistencias'){
                Swal.fire({
                    title: 'Sin asistencias',
                    text: 'No se encuentran asistencias en los registros de esta clase.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3500
                })
            }
            if(data == 'con_asistencias'){
                window.open('asistenciaClases.php?id_clase='+id, '_blank');
            }
        }
    });
}

function buscarClasesCarrera(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'buscarClasesCarrera'},
        dataType: 'JSON',
        success: function(data){
            $("#selectBuscarAsistencias").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#selectBuscarAsignarGrupo").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#selectBuscarAsistencias").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#selectBuscarAsignarGrupo").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    });  
}

$("#selectBuscarAsistencias").on('change', function(){
    
    var idCarrera = $(this).val();
    //console.log(idCarrera);
    
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'listarGeneraciones', idBuscar: idCarrera},
        success: function(data){
            try{
                var gen = JSON.parse(data);
                //console.log(gen);
                var gen_html = '<option selected="true" disabled="disabled">Seleccione una generación</option>';
                if(listedGenerations.length > 0){
                    listedGenerations = [];
                }
                listedGenerations = gen;

                for(var i in gen){
                    gen_html += `<option value="${gen[i].idGeneracion}">${gen[i].nombre}</option>`;
                }
                $("#selectBuscarGeneraciones").html(gen_html);
                $("#selectBuscarGeneracionesGrupo").html(gen_html);
            }catch(e){
                console.log(e, data);
            }
        }
    })

    /* tAlumnos = $("#datatable-tablaAsisteciasClases").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {action: 'consultarAsistenciaClases', idCarr: idCarrera},
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
            [0,'asc']
        ],
    }); */
})

$("#selectBuscarGeneraciones").on('change', function(){
    $('#selectIESM').addClass('hidden');
    var idGeneracion = $("#selectBuscarGeneraciones").val();
    var idCarrera = $("#selectBuscarAsistencias").val();
    var groupG = '';

    tableAsistenciasAlumno(idCarrera,idGeneracion,groupG);

    // if(Band == 4){
    //     $('#selectIESM').removeClass('hidden');
    //   selectGroup();
    // }else{
    //     tableAsistenciasAlumno(idCarrera,idGeneracion,groupG);
    // }
    
});

$("#selectBuscarGeneracionesGrupo").on('change', function(){
    $('#selectIESM').addClass('hidden');
    var idGeneracion = $("#selectBuscarGeneracionesGrupo").val();

    $('#idGen').val(idGeneracion);

        selectGroup();
        $('#selectIESMAG').removeClass('hidden');

        tableAsignarGrupo(idGeneracion);

    $('#selectBuscarAGrupo').on('change',function(){
        $group = $(this).val();
        $('#NomGroup').val($group);
    });
});

$("#selectBuscarGrupo").on('change', function(){
    var idGeneracion = $("#selectBuscarGeneraciones").val();
    var idCarrera = $("#selectBuscarAsistencias").val();
    var groupG = $(this).val();

    tableAsistenciasAlumno(idCarrera,idGeneracion,groupG);
    
})

function tableAsignarGrupo(idGeneracion){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultar_alumnos_generacion', generacion:idGeneracion},
        success: function(data){
            try{
                var alumn = JSON.parse(data);
                $("#datatable-asignar-grupos").DataTable().clear();
                for(a in alumn){
                    if(alumn[a].grupo != null && alumn[a].grupo != ''){
                        $input = '';
                        $group = `Grupo${alumn[a].grupo}`    
                    }else{
                        $input =  `<input type="checkbox" class="checkGroup" onclick="AddGroups(${alumn[a].id_prospecto})" />`;
                        $group = '';
                    }
                    $("#datatable-asignar-grupos").DataTable().row.add([
                        
                       $input,
                        `${alumn[a].nombre} ${alumn[a].apaterno} ${alumn[a].amaterno}`,
                       $group,
                    ]);
                }
                $("#datatable-asignar-grupos").DataTable().draw();
               
            }catch(e){
                console.log(e, data);
            }
        }
    })
}

function tableAsistenciasAlumno(idCarrera,idGeneracion,groupG){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultar_clases_materias', carrera: idCarrera, generacion: idGeneracion,groupG:groupG},
        success: function(data){
            try{
                var clases = JSON.parse(data);
                $("#datatable-tablaAsisteciasClases").DataTable().clear();
                for(c in clases){
                    var mat_n = clases[c].nombre.length > 30 ? `<span title="${clases[c].nombre}">${clases[c].nombre.substring(0,30)+'...'}</span>` : clases[c].nombre;
                    $("#datatable-tablaAsisteciasClases").DataTable().row.add([
                        mat_n,
                        clases[c].titulo,
                        clases[c].fecha_hora_clase,
                        `<button class="btn btn-primary" data-toggle="modal" data-target="#" onclick="verAsistencias('${clases[c].idClase}')">Ver Lista</button> <a class="btn btn-primary" href="asistenciasMateria.php?materia=${clases[c].id_materia}&generacion=${clases[c].idGeneracion}&group=${groupG}&vista=${Band}" target="_blank">Asistencias por materia</a>`
                    ]);
                }
                $("#datatable-tablaAsisteciasClases").DataTable().draw();
            }catch(e){
                console.log(e, data);
            }
        }
    })
}

function check(e){
    tecla = (document.all) ? e.keycode : e.which;

    if(tecla == 8){
        return true;
    }

    patron = /[0-9]/;
    tecla__final = String.fromCharCode(tecla);
    return patron.test(tecla__final);
}

function buscarExpedienteAdmin(id){
    $("#formDocumentacionAdmin")[0].reset();
    $("#documento2").prop('disabled', false);
    $("#documento3").prop('disabled', false);
    $("#documento4").prop('disabled', false);
    $("#documento5").prop('disabled', false);
    $("#documento6").prop('disabled', false);
    $("#documento7").prop('disabled', false);
    $("#documento8").prop('disabled', false);
    $("#btnDoc2").prop('disabled', false);
    $("#btnDoc3").prop('disabled', false);
    $("#btnDoc4").prop('disabled', false);
    $("#btnDoc5").prop('disabled', false);
    $("#btnDoc6").prop('disabled', false);
    $("#btnDoc7").prop('disabled', false);
    $("#btnDoc8").prop('disabled', false);
    $("#btnDoc2").show();
    $("#btnDoc3").show();
    $("#btnDoc4").show();
    $("#btnDoc5").show();
    $("#btnDoc6").show();
    $("#btnDoc7").show();
    $("#btnDoc8").show();
    $("#btnEnviado2").hide();
    $("#btnEnviado3").hide();
    $("#btnEnviado4").hide();
    $("#btnEnviado5").hide();
    $("#btnEnviado6").hide();
    $("#btnEnviado7").hide();
    $("#btnEnviado8").hide();
    $("#idUsuario").val(id);
    cargarGrado();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'obtenerExpedienteAlumno',
               'idAlumno': id},
        success: function(data){
            try{
                $("#modalSubirDocumentacionAdmin").modal("show");
                pr = JSON.parse(data)
                for(var i in pr.data){
                    if(pr.data[i].id_documento == 7){
                        $("#documento7").prop('disabled', true);
                        $("#btnDoc7").prop('disabled', true);
                        $("#btnDoc7").hide();
                        $("#btnEnviado7").show();
                    }
                    if(pr.data[i].id_documento == 8){
                        $("#documento8").prop('disabled', true);
                        $("#btnDoc8").prop('disabled', true);
                        $("#btnDoc8").hide();
                        $("#btnEnviado8").show();
                    }
                    if(pr.data[i].id_documento == 2){       
                        $("#documento2").prop('disabled', true);
                        $("#btnDoc2").prop('disabled', true);
                        $("#btnDoc2").hide();
                        $("#btnEnviado2").show();
                    }
                    if(pr.data[i].id_documento == 3){
                        $("#documento3").prop('disabled', true);
                        $("#btnDoc3").prop('disabled', true);
                        $("#btnDoc3").hide();
                        $("#btnEnviado3").show();
                    }
                    if(pr.data[i].id_documento == 4){    
                        $("#documento4").prop('disabled', true);
                        $("#gradoEstudios").prop('disabled', true);
                        $("#btnDoc4").prop('disabled', true);
                        $("#btnDoc4").hide();
                        $("#btnEnviado4").show();
                    }
                    if(pr.data[i].id_documento == 5){
                        $("#documento5").prop('disabled', true);
                        $("#btnDoc5").prop('disabled', true);
                        $("#btnDoc5").hide();
                        $("#btnEnviado5").show();
                    }
                    if(pr.data[i].id_documento == 6){
                        $("#documento6").prop('disabled', true);
                        $("#btnDoc6").prop('disabled', true);
                        $("#btnDoc6").hide();
                        $("#btnEnviado6").show();
                    }
                }

            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function guardarDocumento(archivo, documento){
    $("#btnDoc"+documento).prop('disabled', true);
    selectGrado = $(archivo).parent().find('select option:selected').val();
    csvFile = $(archivo).parent().find('input')[0].files[0];
    var id = $("#idUsuario").val();

    fData = new FormData();
    fData.append('action', 'registrarDocumentoAdmin');
    fData.append('file', csvFile);
    fData.append('documento', documento);
    fData.append('idUsuario', id);
    fData.append('gradoEstudio', selectGrado);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
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
                        timer: 3000,
                    }).then((result)=>{
                        if(pr.data.documento=="4"){
                            $("#documento"+pr.data.documento).prop('disabled', true);
                            $("#gradoEstudios").prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).hide();
                            $("#btnEnviado"+pr.data.documento).show();
                            $("#spinnerDoc"+pr.data.documento).hide();
                        }else{
                            $("#documento"+pr.data.documento).prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).prop('disabled', true);
                            $("#btnDoc"+pr.data.documento).hide();
                            $("#btnEnviado"+pr.data.documento).show();
                            $("#spinnerDoc"+pr.data.documento).hide();
                        }
                        $("#formDocumentacionAdmin")[0].reset();
                        tAlumnos.ajax.reload();
                        //$("a").trigger("click");
                    })
                }
            }catch(e){
                //console.log(e)
                //console.log(data)
            }
        }
    });
}

function cargarGrado(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: "cargarGrado"},
        dataType: 'JSON',
        success : function(data){
            try{
                $("#documento4").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data, function(key,registro){
                    $("#documento4").append('<option value='+registro.id_gradoE+'>'+registro.nombre+'</option>');
                });
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#documento4").on('change', function(){
    if($(this).val() === "0"){
        $("#gradoEstudios").prop("disabled", true);
    }else{
        $("#gradoEstudios").prop("disabled", false);
    }
})

$("#ocultarSubirDocumentacion").on('click',function(){
    $("#modalSubirDocumentacionAdmin").modal('hide');
});

function CambiarTipo(idAlumno){
    //Boton de cancelar
    var idGenAnt = $("#S_generacionesExpedientes").val();
    //var idAlumno = $("#idAlumnoCambio").val();
    var idCarr = $("#S_carrerasExpedientes").val();
    Swal.fire({
        text: '¿Está  seguro cambiar el estatus a diplomado?',
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
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {action: 'InsertaTipoAlumno',
                        id_Gen: idGenAnt,
                        id_Alu: idAlumno
                        /*id_Carr: idCarr*/},
               
                success: function(data){
                    if(data == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se cargó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }
                    try{
        
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Cambiado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                tAlumnos.ajax.reload(null,false);
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }   
                }
            });
            //Ajax para update en alumnos_generaciones
        }
    });
}
function cargarCarreras(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'buscarClasesCarrera'},
        dataType: 'JSON',
        success: function(data){
            $("#examenCarrera").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#examenCarrera").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    });
}
function cargarCarrerasBanco(idCarrera){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'obtenerGeneracionesCarrera',
        idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#examenGeneracionBanco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#examenGeneracionBanco").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        }
    });
}
$("#btn-crear-examen-banco").on('click',function(){
    $("#check_extraordinarioBanco").prop('checked', false);
    $("#modalCrearExamenBanco").modal('show');
    $("#examenCarreraBanco").empty();
    $("#CarreraBanco").attr("value",$("#selectBuscarExamenesCarrera_Banco").val());
    $("#costsb").addClass('d-none');
    $("#costsb input").removeAttr('required');
    
});

$("#cerrarCrearExamenBanco").on('click', function(){
    $("#modalCrearExamenBanco").modal('hide');
    $("#formularioCrearExamenBanco")[0].reset();
    $("#costsb").addClass('d-none');
    $("#costsb input").removeAttr('required');
});
$("#btn-crear-examen").on('click',function(){
    $("#modalCrearExamen").modal('show');
    $("#formularioCrearExamen")[0].reset();
    $("#costs").addClass('d-none');
    $("#costs input").removeAttr('required');

    $("#check_retomar_preguntas").prop('disabled', true);
    $("#examenCarrera").empty();
    cargarCarreras();
    $("#divGeneracion").hide();
    $("#divMaestros").hide();
    $("#divMaterias").hide();
    $("#examenGeneracion").empty();
    $("#selectMaestros").empty();
    $("#cursoExamen").empty();
    $("#check_multiple_aplicacion_i").prop('checked', false);
    $("#inp_porcentaje_aprobar_i").parent().addClass('d-none');
    $("#inp_porcentaje_aprobar_i").attr('disabled', true)

    $("#check_retomar_preguntas").prop('checked', false);
    $("#id_examen_pasado").parent().addClass('d-none');
    $("#id_examen_pasado").attr('disabled', true)
    $("#id_examen_pasado").prop('selected', false);

    $("#check_multiple_aplicacion_iBanco").prop('checked', false);
    $("#inp_porcentaje_aprobar_iBanco").parent().addClass('d-none');
    $("#inp_porcentaje_aprobar_iBanco").attr('disabled', true)

    //Vistas para editar
    $("#check_retomar_preguntas_e").prop('checked', false);
    $("#id_examen_pasado_e").parent().addClass('d-none');
    $("#id_examen_pasado_e").attr('disabled', true)

    $("#id_examen_pasado_e").prop('selected', false);
    $("#inp_porcentaje_aprobar_i").attr('disabled', true);
    $('#costs').addClass('d-none');
    $('#costs input').attr('required',false);
})

$("#cerrarCrearExamen").on('click', function(){
    $("#modalCrearExamen").modal('hide');
    $("#formularioCrearExamen")[0].reset();
    $("#costs").addClass('d-none');
    $("#costs input").removeAttr('required');
})

$("#examenCarrera").on('change', function(){
    var idCarrera = $("#examenCarrera").val();
    //Id para verificar el numero de preguntas.
    //var idPregunta = $("#id_examen_pasado").val();
    $("#check_retomar_preguntas").prop('disabled', false);
    $("#divGeneracion").show();
    $("#divMaestros").hide();
    $("#divMaterias").hide();
    $("#examenGeneracion").empty();
    $("#selectMaestros").empty();
    $("#cursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#examenGeneracion").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#examenGeneracion").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#examenGeneracion").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });

    // $.ajax({
    //     url: '../assets/data/Controller/maestros/maestrosControl.php',
    //     type: 'POST',
    //     data: {
    //         action: 'insertarPreguntaPasadaExamen',
    //         idCarr: idCarrera
    //     },

    //     dataType: 'JSON',
    //     success: function(data){
    //         console.log(data);            
    //         $("#id_examen_pasado").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    //         $.each(data.data, function(key,data){
    //             $("#id_examen_pasado").append('<option value='+data.idExamen+'>'+data.Nombre+'</option>');
    //         });
    //     },
    //     error : function(xhr){
    //     console.log(xhr);
    //         if(xhr.responseText == 'sin_generaciones'){
    //             $("#id_examen_pasado").html('<option selected="true" value="" disabled="disabled">Sin Examenes asignados</option>');
    //         }
    //     }
    // });



});

$("#id_examen_pasado").on('change', function(){
    
    //Id para verificar el numero de preguntas.
    var idExamen = $("#id_examen_pasado").val();
    console.log(idExamen);
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'Rango_de_Preguntas',
            idEx: idExamen
        },

        dataType: 'JSON',
        success: function(data){
            console.log(data);            
            $("#rango_examen_pasado").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#datatable-tablaPreguntas2").DataTable().clear();
            $.each(data.data, function(key,data){
                $("#rango_examen_pasado").append('<option value='+data.idPregunta+'>'+data.pregunta+'  </option>');

                var opciones = JSON.parse(data.opciones);
                var texto = "";
                //console.log(opciones);
                
                $.each(opciones,function(key,data){
                    texto += " / "+key;
                });

                $("#datatable-tablaPreguntas2").DataTable().row.add([
                    data.pregunta,
                    texto
                    
                ])
            });
            $("#datatable-tablaPreguntas2").DataTable().draw();
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#rango_examen_pasado").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });

});

$("#id_examen_pasado_e").on('change', function(){
    
   
    //Id para verificar el numero de preguntas.
    var idExamen = $("#id_examen_pasado_e").val();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'Rango_de_Preguntas',
            idEx: idExamen
        },

        dataType: 'JSON',
        success: function(data){
            console.log(data);            
            $("#rango_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#datatable-tablaPreguntas3").DataTable().clear();
            $.each(data.data, function(key,data){
                $("#rango_examen_pasado_e").append('<option value='+data.idPregunta+'>'+data.pregunta+'  </option>');

                var opciones = JSON.parse(data.opciones);
                var texto = "";
                //console.log(opciones);
                
                $.each(opciones,function(key,data){
                    texto += " / "+key;
                });

                $("#datatable-tablaPreguntas3").DataTable().row.add([
                    data.pregunta,
                    texto
                    
                ])
            });
            $("#datatable-tablaPreguntas3").DataTable().draw();
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#rango_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });

})

$("#examenGeneracion").on('change', function(){
    var idCarrera = $("#examenCarrera").val();
    var examenGeneracion = $("#examenGeneracion").val();
    $("#divMaestros").show();
    $("#divMaterias").hide();
    $("#selectMaestros").empty();
    $("#cursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action: 'obtenerMaestros',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#selectMaestros").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#selectMaestros").append('<option value='+registro.id+'>'+registro.nombres+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_maestros'){
                $("#selectMaestros").html('<option selected="true" value="" disabled="disabled">Sin maestros asignadas</option>');
            }
        }
    });

    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarExamenesPorID',
                idGen: examenGeneracion},
        dataType: "JSON",
        success: function(data){
            if(data!=[]){
                    $("#id_examen_pasado").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data.data, function(key, registro){
                    $("#id_examen_pasado").append('<option value='+registro.idExamen+'>'+registro.Nombre+'</option>');
                });
            }else{
                $("#id_examen_pasado").html('<option selected="true" value="" disabled="disabled">Sin examenes asignados</option>');
            }
            
        },
        error : function(xhr){
            console.log(xhr);
            if(xhr.responseText == 'Sin_examenes'){
                $("#id_examen_pasado").html('<option selected="true" value="" disabled="disabled">Sin Examenes Asignados</option>');
            }
        }
    });


})

$("#selectMaestros").on('change', function(){
    var idGeneracion = $("#examenGeneracion").val();
    $("#divMaterias").show();
    $("#cursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerMaterias',
            idGen: idGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#cursoExamen").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#cursoExamen").append('<option value='+registro.id_materia+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_materias'){
                $("#cursoExamen").html('<option selected="true" value="" disabled="disabled">Sin materias asignadas</option>');
            }
        }
    });
})

$("#formularioCrearExamen").on('submit', function(e){
e.preventDefault();
$('#crewEx').attr('disabled',true)
fData = new FormData(this);
fData.append('action', 'crearExamen');

$.ajax({
    url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
    type: 'POST',
    data: fData,
    contentType: false,
    processData: false,
    success: function(data){
        try{
            pr = JSON.parse(data);
            if(pr.estatus == "ok"){
                swal({
                    title: 'Creado Correctamente',
                    icon: 'success',
                    text: 'Espere un momento...',
                    button: false,
                    timer: 2500,
                }).then((result)=>{
                    $("#formularioCrearExamen")[0].reset();
                    $("#datatable-tablaPreguntas2").parent().addClass('d-none');
                    $("#datatable-tablaPreguntas2").attr('disabled', true)
                    $('#crewEx').attr('disabled',false);
                    $("#modalCrearExamen").modal("hide");
                    $("#id_examen_pasado").empty();
                    //:::
                    if($("#datatable-tablaExamenes").DataTable().ajax.url() !== null){
                        $("#datatable-tablaExamenes").DataTable().ajax.reload(null, false);
                    }
                });
            }
        }catch(e){
            console.log(e)
            console.log(data)
        }
    }
});
});

//Btn para cambiar informacion de titulados
$("#Titulados").on('click', function(e){
    //$(".my_input").prop('checked',false);
    var Generacion = $("#generacionesCalificacionesTitulados").val()
    e.preventDefault();
    fData = new FormData();
    fData.append('action', 'ActualizarTitulados');
    fData.append('id_Alumnos', id_titulados_check);
    fData.append('id_Gen', Generacion);
    
    Swal.fire({
        text: '¿Está  seguro de titular a los alumnos seleccionados?',
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
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: fData,
                //id_preguntas: id_preguntas_check,
                contentType: false,
                processData: false,
                success: function(data){
                    try{
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Guardado Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                $("#Titulados").prop("disabled",true);
                                id_titulados_check = [];
                                //var idCarrera = $("#selectBuscarExamenesCarrera_Banco").val();
                                //LlenarTablaBanco(idCarrera);
                                tablaAlumnosTitulados.ajax.reload(null, false);
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }
                }
            });
        }
    })

    
    //$("#selectBuscarExamenesCarrera_Banco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
});

$("#formularioCrearExamen_Banco").on('submit', function(e){
    $(".my_input").prop('checked',false);
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'CrearExamenBanco');
    fData.append('id_preguntas', id_preguntas_check);
    
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        //id_preguntas: id_preguntas_check,
        contentType: false,
        processData: false,
        success: function(data){
            try{
                pr = JSON.parse(data);
                if(pr.estatus == "ok"){
                    swal({
                        title: 'Creado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formularioCrearExamen_Banco")[0].reset();
                        $("#modalCrearExamenBanco").modal("hide");
                        $(".form-check-input input").prop("checked",false);
                        $("#btn-crear-examen-banco").prop("disabled",true);
                        id_preguntas_check = [];
                        var idCarrera = $("#selectBuscarExamenesCarrera_Banco").val();
                        LlenarTablaBanco(idCarrera);
                        //tExamenes.ajax.reload(null, false);
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
    //$("#selectBuscarExamenesCarrera_Banco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
});

$("#CarreraBanco").on('change', function(){
    var idCarrera = $("#CarreraBanco").val();
    //Id para verificar el numero de preguntas.
    //var idPregunta = $("#id_examen_pasado").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#examenGeneracionBanco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#examenGeneracionBanco").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#examenGeneracionBanco").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});


$("#examenGeneracionBanco").on('change', function(){
    var idCarrera = $("#CarreraBanco").val();
    //var examenGeneracion = $("#examenGeneracionBanco").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action: 'obtenerMaestros',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#selectMaestrosBanco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#selectMaestrosBanco").append('<option value='+registro.id+'>'+registro.nombres+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_maestros'){
                $("#selectMaestrosBanco").html('<option selected="true" value="" disabled="disabled">Sin maestros asignadas</option>');
            }
        }
    });
});

function editarExamen(id){
    //console.log(id)
    carrerasEditarExamen();
    $("#modalEditarExamen").modal('show');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerDatosExamen',
            idExamen: id
        },
        success: function(data){
            try{
                pr = JSON.parse(data);
                generacionesEditarExamen(pr.id_carrera,pr.id_generacion);
                maestrosEditarExamen(pr.id_carrera);
                materiasEditarExamen(pr.id_generacion);
                $("#editarNombreExamen").val(pr.Nombre);
                setTimeout(() => {
                    $("#editarExamenCarrera").val(pr.id_carrera);
                    $("#editarExamenGeneracion").val(pr.id_generacion);
                    $("#editarSelectMaestros").val(pr.idMaestro);
                    $("#editarCursoExamen").val(pr.idCurso);
                    if(pr.Examen_ref > 0){
                        
                        $("#check_retomar_preguntas_e").prop('checked', true);
                        $("#id_examen_pasado_e").val(pr.Examen_ref);
                        
                        $("#num_preguntas_retomar_e").val(pr.preguntas_aplicar);
                        $("#id_examen_pasado_e").parent().removeClass('d-none');
                        $("#num_preguntas_retomar_e").attr('disabled', false)
                    }else{
                    $("#check_retomar_preguntas_e").prop('checked', false);
                        $("#id_examen_pasado_e").val('');
                         $("#id_examen_pasado_e").parent().addClass('d-none');
                         $("#num_preguntas_retomar_e").attr('disabled', true);
                        $("#num_preguntas_retomar_e").val('');
                    }
                    if(pr.tipo_examen > 1){
                    	$("#formularioEditarExamen input,#formularioEditarExamen select").attr('readonly',true);
                    	$("#formularioEditarExamen button[type='submit'],#formularioEditarExamen select").attr('disabled',true);
                        $('#formularioEditarExamen #costs').removeClass('d-none');
                        $('#formularioEditarExamen #costs input').attr('required',true);
                        $('#formularioEditarExamen #costs input[name=costoPesos]').val(pr.precio);
                        $('#formularioEditarExamen #costs input[name=costoUsd]').val(pr.precio_usd);
                            swal('examen extraordinario solo para consulta por el momento.');
                    }else{
                    	$("#formularioEditarExamen input,#formularioEditarExamen select").attr('readonly',false);
                    	$("#formularioEditarExamen button[type='submit'],#formularioEditarExamen select").attr('disabled',false);
                        $('#formularioEditarExamen #costs').addClass('d-none');
                        $('#formularioEditarExamen #costs input').attr('required',false);
                    }
                }, 1400);
         
                $("#idExamen").val(pr.idExamen);
                $("#editarFechaInicioExamen").val(pr.fechaInicio);
                $("#editarHoraInicioExamen").val(pr.horaInicio);
                $("#editarFechaFinExamen").val(pr.fechaFin);
                $("#editarHoraFinExamen").val(pr.horaFin);

                if(pr.multiple_intento == 1){
                    $("#check_multiple_aplicacion").prop('checked', true);
                    $("#inp_porcentaje_aprobar").val(pr.porcentaje_aprobar);
                    $("#inp_porcentaje_aprobar").parent().removeClass('d-none');
                    $("#inp_porcentaje_aprobar").attr('disabled', false)
                }else{
                    $("#inp_porcentaje_aprobar").val('');
                    $("#check_multiple_aplicacion").prop('checked', false);
                    $("#inp_porcentaje_aprobar").parent().addClass('d-none');
                    $("#inp_porcentaje_aprobar").attr('disabled', true)
                }

                
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });

}

function carrerasEditarExamen(){
    $("#editarExamenCarrera").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'buscarClasesCarrera'},
        dataType: 'JSON',
        success: function(data){
            $("#editarExamenCarrera").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#editarExamenCarrera").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    });
}

function generacionesEditarExamen(idCarrera,idGeneracion){

$.ajax({
    url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
    type: 'POST',
    data: {
        action: 'obtenerGeneracionesCarrera',
        idCarr: idCarrera
    },
    dataType: 'JSON',
    success: function(data){
        $("#editarExamenGeneracion").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $.each(data, function(key,registro){
            $("#editarExamenGeneracion").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
        });
    }
});

$.ajax({
    url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
    type: 'POST',
    data: {action: 'consultarExamenesPorID',
            idGen: idGeneracion},
    dataType: "JSON",
    success: function(data){
        if(data!=[]){
            $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
        $.each(data.data, function(key, registro){
            $("#id_examen_pasado_e").append('<option value='+registro.idExamen+'>'+registro.Nombre+'</option>');
        });
        }else{
            $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin examenes asignados</option>');
        }
        
    },
    error : function(xhr){
        console.log(xhr);
        if(xhr.responseText == 'Sin_examenes'){
            $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin Examenes Asignados</option>');
        }
    }
});

// $.ajax({
//     url: '../assets/data/Controller/maestros/maestrosControl.php',
//     type: 'POST',
//     data: {
//         action: 'insertarPreguntaPasadaExamen',
//         idCarr: idCarrera
//     },

//     dataType: 'JSON',
//     success: function(data){
    
        
//         $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
//         $.each(data.data, function(key,data){

//             $("#id_examen_pasado_e").append('<option value='+data.idExamen+'>'+data.Nombre+'</option>');
//         });
//     },
//     error : function(xhr){
//         console.log(xhr);
//         if(xhr.responseText == 'sin_generaciones'){
//             $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
//         }
//     }
// });
}

function EditarExamen_ref(idCarrera){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerCarrera_ref',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
        	//console.log('hola2');
            $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
            	
                $("#id_examen_pasado_e").append('<option value='+registro.IDExamen_edit+'>'+registro.Nombre_edit+'</option>');
            });
        }
    });
    }

function maestrosEditarExamen(idCarrera){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action: 'obtenerMaestros',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#editarSelectMaestros").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#editarSelectMaestros").append('<option value='+registro.id+'>'+registro.nombres+'</option>');
            });
        }
    });
}

function obtenermaestrosBanco(idCarrera){
    $("#divMaestrosBanco").show();
    divMaestros
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action: 'obtenerMaestros',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#selectMaestrosBanco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#selectMaestrosBanco").append('<option value='+registro.id+'>'+registro.nombres+'</option>');
            });
        }
    });
}

function materiasEditarExamen(idGeneracion){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerMaterias',
            idGen: idGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#editarCursoExamen").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#editarCursoExamen").append('<option value='+registro.id_materia+'>'+registro.nombre+'</option>');
            });
        }
    });
}

$("#formularioEditarExamen").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this)
    fData.append('action', 'editarExamen');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success: function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus == "ok"){
                    swal({
                        title: 'Editado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formularioEditarExamen")[0].reset();
                        $("#modalEditarExamen").modal("hide");
                        tExamenes.ajax.reload(null,false);
                    })
                }else{
                    if(pr.hasOwnProperty('mensaje')){
                        swal(pr.mensaje)
                    }
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})


$("#editarExamenCarrera").on('change', function(){
    var idCarrera = $("#editarExamenCarrera").val();
    var idGeneracion = $("#editarExamenGeneracion").val();
    $("#divEditarGeneracion").show();
    $("#divEditarMaestros").hide();
    $("#divEditarMaterias").hide();
    $("#editarExamenGeneracion").empty();
    $("#editarSelectMaestros").empty();
    $("#editarCursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#editarExamenGeneracion").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#editarExamenGeneracion").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#editarExamenGeneracion").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarExamenesPorID',
                idGen: idGeneracion},
        dataType: "JSON",
        success: function(data){
            if(data!=[]){
                    $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data.data, function(key, registro){
                    $("#id_examen_pasado_e").append('<option value='+registro.idExamen+'>'+registro.Nombre+'</option>');
                });
            }else{
                $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin examenes asignados</option>');
            }
            
        },
        error : function(xhr){
            console.log(xhr);
            if(xhr.responseText == 'Sin_examenes'){
                $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin Examenes Asignados</option>');
            }
        }
    });

    // $.ajax({
    //     url: '../assets/data/Controller/maestros/maestrosControl.php',
    //     type: 'POST',
    //     data: {
    //         action: 'insertarPreguntaPasadaExamen',
    //         idCarr: idCarrera
    //     },

    //     dataType: 'JSON',
    //     success: function(data){
    //         console.log(data);            
    //         $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    //         $.each(data.data, function(key,data){
    //             $("#id_examen_pasado_e").append('<option value='+data.idExamen+'>'+data.Nombre+'</option>');
    //         });
    //     },
    //     error : function(xhr){
    //         if(xhr.responseText == 'sin_generaciones'){
    //             $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
    //         }
    //     }
    // });
})

$("#editarExamenGeneracion").on('change', function(){
    var idCarrera = $("#editarExamenCarrera").val();
    var idGeneracion = $("#editarExamenGeneracion").val();
    $("#divEditarMaestros").show();
    $("#divEditarMaterias").hide();
    $("#editarSelectMaestros").empty();
    $("#editarCursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action: 'obtenerMaestros',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#editarSelectMaestros").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#editarSelectMaestros").append('<option value='+registro.id+'>'+registro.nombres+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_maestros'){
                $("#editarSelectMaestros").html('<option selected="true" value="" disabled="disabled">Sin maestros asignadas</option>');
            }
        }
    });
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'consultarExamenesPorID',
                idGen: idGeneracion},
        dataType: "JSON",
        success: function(data){
            if(data!=[]){
                    $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data.data, function(key, registro){
                    $("#id_examen_pasado_e").append('<option value='+registro.idExamen+'>'+registro.Nombre+'</option>');
                });
            }else{
                $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin examenes asignados</option>');
            }
            
        },
        error : function(xhr){
            console.log(xhr);
            if(xhr.responseText == 'Sin_examenes'){
                $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin Examenes Asignados</option>');
            }
        }
    });
    // $.ajax({
    //     url: '../assets/data/Controller/maestros/maestrosControl.php',
    //     type: 'POST',
    //     data: {
    //         action: 'insertarPreguntaPasadaExamen',
    //         idCarr: idCarrera
    //     },

    //     dataType: 'JSON',
    //     success: function(data){
    //         console.log(data);            
    //         $("#id_examen_pasado_e").html('<option selected="true" value="'+data.idExamen+'>'+data.Nombre+'" disabled="disabled">Seleccione</option>');
    //         $.each(data.data, function(key,data){
    //             $("#id_examen_pasado_e").append('<option value='+data.idExamen+'>'+data.Nombre+'</option>');
    //         });
    //     },
    //     error : function(xhr){
    //         if(xhr.responseText == 'sin_generaciones'){
    //             $("#id_examen_pasado_e").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
    //         }
    //     }
    // });
});

$("#editarSelectMaestros").on('change', function(){
    var idGeneracion = $("#editarExamenGeneracion").val();
    $("#divEditarMaterias").show();
    $("#editarCursoExamen").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerMaterias',
            idGen: idGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#editarCursoExamen").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#editarCursoExamen").append('<option value='+registro.id_materia+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_materias'){
                $("#editarCursoExamen").html('<option selected="true" value="" disabled="disabled">Sin materias asignadas</option>');
            }
        }
    });
})

$("#selectBuscarExamenesCarrera").on('change', function(){
    $("#divGeneracionesTable").show();
    $("#selectBuscarExamenesGeneracion").empty();
    var idCarrera = $("#selectBuscarExamenesCarrera").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            console.log(data)
            $("#selectBuscarExamenesGeneracion").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#selectBuscarExamenesGeneracion").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#selectBuscarExamenesGeneracion").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    })
})

$("#selectMaestrosBanco").on('change', function(){
    $("#divMateriasBanco").show();
    $("#cursoExamenBanco").empty();
    var idGeneracion = $("#examenGeneracionBanco").val();
        $.ajax({
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'obtenerMaterias',
                idGen: idGeneracion
            },
            dataType: 'JSON',
            success: function(data){
                $("#cursoExamenBanco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data, function(key, registro){
                    $("#cursoExamenBanco").append('<option value='+registro.id_materia+'>'+registro.nombre+'</option>');
                });
            },
            error : function(xhr){
                if(xhr.responseText == 'sin_materias'){
                    $("#cursoExamenBanco").html('<option selected="true" value="" disabled="disabled">Sin materias asignadas</option>');
                }
            }
        });
});

//formulario en proceso asigandos
$("#formulario-servicio-alumnos").on("submit", function(e){
    var idarticulo = $("#articulo-servicio").val();
    var carrera = $("#carrera-servicio").val();
    e.preventDefault();
    fData = new FormData();
    fData.append('action', 'ActualizarAsignadosArticulo');
    fData.append('id_asignados', id_asignados_check);
    fData.append('id_art', idarticulo);
    fData.append('idCarr', carrera);

    Swal.fire({
        text: '¿Está  seguro de asignar a los alumnos a este artículo?',
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
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: fData,
                //id_preguntas: id_preguntas_check,
                contentType: false,
                processData: false,
                success: function(data){
                    try{
                        pr = JSON.parse(data);
                        if(pr.estatus == "ok"){
                            swal({
                                title: 'Alumnos Asignados Correctamente',
                                icon: 'success',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                $("#button-servicio").prop("disabled",true);
                                id_asignados_check = [];
                                tablaAlumnosAsignados.ajax.reload(null, false);
                            })
                        }
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }
                }
            });
        }
    });
});

//Obtener arreglo para preguntas banco
var id_asignados_check = [];
function obtenerAsignados(idAlumno){
    //Verificar si idPregunta existe en el array de preguntas
    var Comprobacion = id_asignados_check.indexOf(idAlumno);
 
    if(Comprobacion==-1){
        id_asignados_check.push(idAlumno);
    }else{
        id_asignados_check.splice(id_asignados_check.indexOf(idAlumno),1);
    }
    if(id_asignados_check.length<1){
        $("#button-servicio").prop('disabled',true);
    }else{
        $("#button-servicio").prop('disabled',false);
    }
    console.log(id_asignados_check);
}

//Obtener arreglo para preguntas banco
var id_titulados_check = [];
function obtenerTitulados(idAlumno){
    //Verificar si idPregunta existe en el array de preguntas
    var Comprobacion = id_titulados_check.indexOf(idAlumno);
 
    if(Comprobacion==-1){
        id_titulados_check.push(idAlumno);
    }else{
        id_titulados_check.splice(id_titulados_check.indexOf(idAlumno),1);
    }
    if(id_titulados_check.length<1){
        $("#Titulados").prop('disabled',true);
    }else{
        $("#Titulados").prop('disabled',false);
    }
    console.log(id_titulados_check);
}

//Obtener arreglo para preguntas banco
var id_preguntas_check = [];
function obtenerArreglo(idPregunta){
    //Verificar si idPregunta existe en el array de preguntas
    var Comprobacion = id_preguntas_check.indexOf(idPregunta);
 
    if(Comprobacion==-1){
        id_preguntas_check.push(idPregunta);
    }else{
        id_preguntas_check.splice(id_preguntas_check.indexOf(idPregunta),1);
    }
    if(id_preguntas_check.length<1){
        $("#btn-crear-examen-banco").prop('disabled',true);
    }else{
        $("#btn-crear-examen-banco").prop('disabled',false);
    }
}

$("#selectBuscarExamenesCarrera_Banco").on('change', function(){
    $("#Tabla-Banco-preguntas").show();
    $("#selectBuscarExamenesGeneracion").empty();
    
    var idCarrera = $("#selectBuscarExamenesCarrera_Banco").val();
    cargarCarrerasBanco(idCarrera);
    obtenermaestrosBanco(idCarrera);
    LlenarTablaBanco(idCarrera);
});

function LlenarTablaBanco(idCarrera){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerPreguntasExamenesRef',
            idCarr: idCarrera
            //id_Preguntas: id_preguntas_check
        },
        dataType: 'JSON',
        success: function(data){
            console.log(data);     
            console.log(data[2]);        
            $("#Tabla-Banco-preguntas").DataTable().clear();
            for(i=0;i<data.length;i++ ){
                $("#Tabla-Banco-preguntas").DataTable().row.add([
                    data[i].pregunta,
                    //`<div>${data[i].Nombre}<br>${data[i].nombre_gen}</div>`
                    `<div class = "row  m-0 justify-content-center Mycheckbox"><input class="form-check-input" type="checkbox" name ="" value="${data[i].id_pregunta}" id="" onclick="obtenerArreglo(${data[i].id_pregunta})"/> </div>`
                    
                ]);
            }
            $("#Tabla-Banco-preguntas").DataTable().draw();
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                //$("#selectBuscarExamenesGeneracion").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
                var_dump($Carrera );
            }
        }
    });
}


function cargarCarrerasTabla(Band){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'buscarClasesCarrera',
            vista: Band
        },
        dataType: 'JSON',
        success: function(data){
            $("#selectBuscarExamenesCarrera").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#selectBuscarExamenesCarrera_Banco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#CarreraBanco").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#selectBuscarExamenesCarrera").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#selectBuscarExamenesCarrera_Banco").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#CarreraBanco").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    })
}

$("#selectBuscarExamenesGeneracion").on('change', function(){
    var idGeneracion = $("#selectBuscarExamenesGeneracion").val();
    tExamenes = $("#datatable-tablaExamenes").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {action: 'consultarExamenes',
                    idGen: idGeneracion,
                    vista: Band},
            dataType: "JSON",
            error: function(e){
                console.log(e.responseText);
            },
            dataSrc: function(json){

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
})

// se agrega modal para ver respuestaas de examenes
let respuestas_examen = [];
function revisar_entregas(examen){
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'consultar_entregas',
            id: examen
        },
        success: function(data){
            try{
                var entregas = JSON.parse(data);
                respuestas_examen = entregas;
                //console.log(respuestas_examen);
                $("#tbl_examenes_entregados").DataTable().clear();
                for(i in entregas){
                    $("#tbl_examenes_entregados").DataTable().row.add([
                        entregas[i].alumno_nombre,
                        entregas[i].fechaPresentacion,
                        parseFloat(entregas[i].calificacion).toFixed(2)+' % ',
                        `<button class="btn btn-primary btn-sm" onclick="ver_examen('${entregas[i].idResultado}')"><i class="fa fa-eye" aria-hidden="true"></i></button>`
                    ]);
                }
                $("#tbl_examenes_entregados").DataTable().draw();
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
    $("#modalEntregasExamen").modal('show');
}

function ver_examen(resultado){
    respuesta = respuestas_examen.find( elm => elm.idResultado == resultado);
    //console.log(respuesta)
    if(respuesta){
        var tabla   = document.createElement("table");
        var tblHead = document.createElement("thead");

        var encabezado = document.createElement("tr");

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Número');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Pregunta');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Respuesta');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);

        var celdaTitulo = document.createElement("th");
        var textoCeldaTitulo = document.createTextNode('Resultado');
        celdaTitulo.appendChild(textoCeldaTitulo);
        encabezado.appendChild(celdaTitulo);


        tblHead.appendChild(encabezado);

        var tblBody = document.createElement("tbody");
        var j = 1;
        for (var i = 0; i < respuesta.respuestas.length; i++) {
            //console.log(respuesta.respuestas[i][0]);
            var hilera = document.createElement("tr");

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode(j);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode(respuesta.respuestas[i][3].pregunta);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode(respuesta.respuestas[i][1]);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            var celda = document.createElement("td");
            var textoCelda = document.createTextNode((respuesta.respuestas[i][2] == 1) ? 'Correcta' : 'Incorrecta');
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);

            tblBody.appendChild(hilera);
            j++;
        }

        tabla.appendChild(tblHead);
        tabla.appendChild(tblBody);
        tabla.setAttribute("class", "table");

        swal({
            title: 'Respuestas del alumno',
            content: tabla,
            className: 'swal-wide',
        })
    }
}
//

function validarVistaDocumentos(id, idGeneracion){
    //console.log(id);
    //console.log(idGeneracion);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'validarVistaDocumentos',
            idGen: idGeneracion
        },
        success: function(data){
            try{
                pr = JSON.parse(data)
                switch(pr.idInstitucion){
                    case '20':
                        if(pr.tipo == 2 || pr.tipo == 4){
                            buscarExpedienteAdminUDC(id);
                        }
                        break;
                    case '19':

                        break;
                    case '13':
                        buscarExpedienteAdmin(id);
                        break;
                }
                //console.log()
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
    //buscarExpedienteAdmin(id);
}

function buscarExpedienteAdminUDC(idAfiliado){
    $("#formDocumentacionAdminUDC")[0].reset();
    $("#documentoUDC2").prop('disabled', false);
    $("#documentoUDC3").prop('disabled', false);
    $("#documentoUDC4").prop('disabled', false);
    $("#documentoUDC5").prop('disabled', false);
    $("#documentoUDC6").prop('disabled', false);
    $("#documentoUDC7").prop('disabled', false);
    $("#documentoUDC8").prop('disabled', false);
    $("#documentoUDC9").prop('disabled', false);
    $("#documentoUDC10").prop('disabled', false);
    $("#documentoUDC11").prop('disabled', false);
    $("#btnDocUDC2").prop('disabled', false);
    $("#btnDocUDC3").prop('disabled', false);
    $("#btnDocUDC4").prop('disabled', false);
    $("#btnDocUDC5").prop('disabled', false);
    $("#btnDocUDC6").prop('disabled', false);
    $("#btnDocUDC7").prop('disabled', false);
    $("#btnDocUDC8").prop('disabled', false);
    $("#btnDocUDC9").prop('disabled', false);
    $("#btnDocUDC10").prop('disabled', false);
    $("#btnDocUDC11").prop('disabled', false);
    $("#btnDocUDC2").show();
    $("#btnDocUDC3").show();
    $("#btnDocUDC4").show();
    $("#btnDocUDC5").show();
    $("#btnDocUDC6").show();
    $("#btnDocUDC7").show();
    $("#btnDocUDC8").show();
    $("#btnDocUDC9").show();
    $("#btnDocUDC10").show();
    $("#btnDocUDC11").show();
    $("#btnEnviadoUDC2").hide();
    $("#btnEnviadoUDC3").hide();
    $("#btnEnviadoUDC4").hide();
    $("#btnEnviadoUDC5").hide();
    $("#btnEnviadoUDC6").hide();
    $("#btnEnviadoUDC7").hide();
    $("#btnEnviadoUDC8").hide();
    $("#btnEnviadoUDC9").hide();
    $("#btnEnviadoUDC10").hide();
    $("#btnEnviadoUDC11").hide();
    $("#idUsuarioUDC").val(idAfiliado);
    //cargarGrado();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'obtenerExpedienteAlumno',
               'idAlumno': idAfiliado},
        success: function(data){
            try{
                $("#modalSubirDocumentacionAdminUDC").modal("show");
                pr = JSON.parse(data)
                for(var i in pr.data){
                    if(pr.data[i].id_documento == 7){
                        $("#documentoUDC7").prop('disabled', true);
                        $("#btnDocUDC7").prop('disabled', true);
                        $("#btnDocUDC7").hide();
                        $("#btnEnviadoUDC7").show();
                    }
                    if(pr.data[i].id_documento == 8){
                        $("#documentoUDC8").prop('disabled', true);
                        $("#btnDocUDC8").prop('disabled', true);
                        $("#btnDocUDC8").hide();
                        $("#btnEnviadoUDC8").show();
                    }
                    if(pr.data[i].id_documento == 2){       
                        $("#documentoUDC2").prop('disabled', true);
                        $("#btnDocUDC2").prop('disabled', true);
                        $("#btnDocUDC2").hide();
                        $("#btnEnviadoUDC2").show();
                    }
                    if(pr.data[i].id_documento == 3){
                        $("#documentoUDC3").prop('disabled', true);
                        $("#btnDocUDC3").prop('disabled', true);
                        $("#btnDocUDC3").hide();
                        $("#btnEnviadoUDC3").show();
                    }
                    if(pr.data[i].id_documento == 4){    
                        $("#documentoUDC4").prop('disabled', true);
                        $("#gradoEstudiosUDC").prop('disabled', true);
                        $("#btnDocUDC4").prop('disabled', true);
                        $("#btnDocUDC4").hide();
                        $("#btnEnviadoUDC4").show();
                    }
                    if(pr.data[i].id_documento == 5){
                        $("#documentoUDC5").prop('disabled', true);
                        $("#btnDocUDC5").prop('disabled', true);
                        $("#btnDocUDC5").hide();
                        $("#btnEnviadoUDC5").show();
                    }
                    if(pr.data[i].id_documento == 6){
                        $("#documentoUDC6").prop('disabled', true);
                        $("#btnDocUDC6").prop('disabled', true);
                        $("#btnDocUDC6").hide();
                        $("#btnEnviadoUDC6").show();
                    }
                    if(pr.data[i].id_documento == 11){
                        $("#documentoUDC11").prop('disabled', true);
                        $("#btnDocUDC11").prop('disabled', true);
                        $("#btnDocUDC11").hide();
                        $("#btnEnviadoUDC11").show();
                    }
                    if(pr.data[i].id_documento == 9){
                        $("#documentoUDC9").prop('disabled', true);
                        $("#btnDocUDC9").prop('disabled', true);
                        $("#btnDocUDC9").hide();
                        $("#btnEnviadoUDC9").show();
                    }
                    if(pr.data[i].id_documento == 10){
                        $("#documentoUDC10").prop('disabled', true);
                        $("#btnDocUDC10").prop('disabled', true);
                        $("#btnDocUDC10").hide();
                        $("#btnEnviadoUDC10").show();
                    }
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#ocultarSubirDocumentacionUDC").on('click', function(){
    $("#modalSubirDocumentacionAdminUDC").modal('hide');
})

function guardarDocumentoUDC(archivo, documento){
    $("#btnDocUDC"+documento).prop('disabled', true);
    selectGrado = $(archivo).parent().find('select option:selected').val();
    csvFile = $(archivo).parent().find('input')[0].files[0];
    var id = $("#idUsuarioUDC").val();

    fData = new FormData();
    fData.append('action', 'registrarDocumentos');
    fData.append('file', csvFile);
    fData.append('documento', documento);
    fData.append('idUsuario', id);
    fData.append('gradoEstudio', selectGrado);
    $.ajax({
        url: '../udc/app/data/CData/documentosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        beforeSend: function(){
            //$("#Enviar").prop('disabled', true);
            $("#spinnerDocUDC"+documento).show();
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
                $("#spinnerDocUDC"+documento).hide();
                $("#btnDocUDC"+documento).prop('disabled', false);
            }
            if(data == 'ImgInc'){
                swal({
                    title: 'Formato de la imagen incorrecto',
                    icon: 'info',
                    text: 'Adjunta un formato correcto: png, jpg, jpeg o verifica que tu archivo no sobrepase los 5MB.',
                    button: false,
                    timer: 3000,
                });
                $("#spinnerDocUDC"+documento).hide();
                $("#btnDocUDC"+documento).prop('disabled', false);
            }
            if(data == ''){
                swal({
                    title: 'Sin documento',
                    icon: 'info',
                    text: 'Adjunta el archivo correspondiente.',
                    button: false,
                    timer: 2200,
                });
                $("#spinnerDocUDC"+documento).hide();
                $("#btnDocUDC"+documento).prop('disabled', false);
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
                        timer: 3000,
                    }).then((result)=>{
                        if(pr.data.documento=="4"){
                            $("#documentoUDC"+pr.data.documento).prop('disabled', true);
                            $("#gradoEstudiosUDC").prop('disabled', true);
                            $("#btnDocUDC"+pr.data.documento).prop('disabled', true);
                            $("#btnDocUDC"+pr.data.documento).hide();
                            $("#btnEnviadoUDC"+pr.data.documento).show();
                            $("#spinnerDocUDC"+pr.data.documento).hide();
                        }else{
                            $("#documentoUDC"+pr.data.documento).prop('disabled', true);
                            $("#btnDocUDC"+pr.data.documento).prop('disabled', true);
                            $("#btnDocUDC"+pr.data.documento).hide();
                            $("#btnEnviadoUDC"+pr.data.documento).show();
                            $("#spinnerDocUDC"+pr.data.documento).hide();
                        }
                        $("#formDocumentacionAdminUDC")[0].reset();
                        tAlumnos.ajax.reload();
                        //$("a").trigger("click");
                    })
                }
            }catch(e){
                //console.log(e)
                //console.log(data)
            }
        }
    });
}

$("#documentoUDC4").on('change', function(){
    if($(this).val() === "0"){
        $("#gradoEstudiosUDC").prop("disabled", true);
    }else{
        $("#gradoEstudiosUDC").prop("disabled", false);
    }
})

// chuy

function editar_clase(id_clase){
    listarCarreras()
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: {action:'consultar_by_id', id:id_clase},
        success: function(data){
            try{
                var resp = JSON.parse(data);
                
                $("#inp_edit_clase").val(resp.idClase)
                $("#inp_edit_link").val(resp.video);
                $("#inp_edit_nombre").val(resp.titulo);
                if(resp.fecha_hora_clase != null){
                    // var fech_clase = new Date(resp.fecha_hora_clase);
                    var fech_clase = resp.fecha_hora_clase.replace(' ', 'T');
                    $("#inp_edit_fecha").val(fech_clase);
                }
                $("#select_carreras_edit").val(resp.idCarrera);
                $("#select_carreras_edit").trigger('change');
                setTimeout(function(){
                    // $("#select_generacion_edit").html(`<option value="${resp.idGeneracion}">${resp.nombre_generacion}</option>`);
                    $("#select_generacion_edit").val($("#select_generacion_edit").find('option[value^="'+resp.idGeneracion+'-"]').val());
                }, 500);
                $("#select_materias_edit").html(`<option value="${resp.id_materia}">${resp.nombre_materia}</option>`);

                $("#list_materiales").html('');
                for(i in resp.apoyo){
                    $("#list_materiales").append(`<li> <a target="_blank" href="../assets/files/clases/apoyos/${resp.apoyo[i][0]}">${resp.apoyo[i][1]}</a></li>`)
                }

                $("#list_recursos").html('')
                for(i in resp.recursos){
                    $("#list_recursos").append(`<li> <a target="_blank" href="../assets/files/clases/recursos/${resp.recursos[i][0]}">${resp.recursos[i][1]}</a></li>`)
                }
                
                $("#editarclase-tab").trigger('click');
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

function EditarFormato(idarchivo, nombre, archivo,envio){
    $("#EditarFormato").modal("show");
    $("#idFormatoEditar").val(idarchivo);
    $("#nombreformatoEditar").val(nombre);
    $("#vecesenvioEditar").val(envio);
}

$("#formularioEditarFormato").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'editarformatoproc');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                var pr = JSON.parse(data);
                if(pr.data > 0){
                    swal({
                        title: "Formato editado Correctamente!",
                        text: "El formato se mostrara en breve",
                        icon: "success",
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result)=>{
                        cargarTablaFormatos();
                        $("#EditarFormato").modal("hide");
                        $("#archivoformatoEditar").val("");
                    });
                }else{
                    swal({
                        title: "No se detectaron cambios!",
                        text: "Intente cambiar la información",
                        icon: "error",
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                //$("#formulario-formatos-nuevos")[0].reset();
                
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});

$("#formulario-formatos-nuevos").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'agregarformatoproc');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                var pr = JSON.parse(data);
                if(pr.data > 0){
                    swal({
                        title: "Formato añadido!",
                        text: "El formato se mostrara en breve",
                        icon: "success",
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result)=>{
                        cargarTablaFormatos();
                        $("#nombreformato").val("");
                        $("#archivoformato").val("");
                        $("#vecesenvio").val(0);
                    });
                }
                //$("#formulario-formatos-nuevos")[0].reset();
                
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});


$("#formulario-procesos-nuevos").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'agregarproceso');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                var pr = JSON.parse(data);
                if(pr.data > 0){
                    swal({
                        title: "Proceso añadido!",
                        text: "El proceso se mostrara en breve",
                        icon: "success",
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
               
                $("#formulario-procesos-nuevos")[0].reset();
                tablaprocesosnuevos.ajax.reload();
                LlenarTablaServicio();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});

$("#form_actualizar_clase").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'actualiza_clase');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form_actualizar_clase button[type='submit']").prop('disabled', true);
        },
        success: function(data){
            try{
                var actualizacion = JSON.parse(data);
                if(actualizacion == 1){
                    swal('Actualizado');
                }
                $("#modalVerClases").modal('hide');
                $("#btn_agregarMaterial").addClass("d-none");
                $("#btn_agregarRecurso").addClass("d-none");
                $("#form_actualizar_clase")[0].reset();
                $("#inputs_materiales").html('');
                $("#inputs_recursos").html('');
                tAlumnos.ajax.reload();
                $('#clases-tab').click()
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#form_actualizar_clase button[type='submit']").prop('disabled', false);
        }
    });
});

$("#empty-materiales").on('click', function(){
    $("#inputs_materiales").html('');
    $("#list_materiales").html('');
    $("#btn_agregarMaterial").removeClass("d-none");
    if($("#inputs_materiales").children().length == 0){
        $("#inputs_materiales").html(`
            <div class="row border-bottom dinamic_input_materiales pb-2">
                <div class="col">
                    <input type="file" name="input_materiales_1" required>
                </div>
                <div class="col">
                    <input type="text" name="input_nombre_materiales_1" required class="form-control" placeholder="Titulo del recurso">
                </div>
                <hr>
            </div>
        `);
    }
})
$("#empty-recursos").on('click', function(){
    $("#inputs_recursos").html('');
    $("#list_recursos").html('');
    $("#btn_agregarRecurso").removeClass("d-none");
    if($("#inputs_recursos").children().length == 0){
        $("#inputs_recursos").html(`
            <div class="row border-bottom dinamic_input_recursos pb-2">
                <div class="col">
                    <input type="file" name="input_recursos_1" required>
                </div>
                <div class="col">
                    <input type="text" name="input_nombre_recursos_1" required class="form-control" placeholder="Titulo del recurso">
                </div>
            </div>
        `);
    }
})

function agregar_elemento(tipo){
    var agregar_otro = true;
    $(`.dinamic_input_${tipo}`).each(function(index){
        agregar_otro = (!$(this).find(':input[type="file"]').val() || $(this).find(':input[type="file"]').val() == '')? false : agregar_otro; 
        agregar_otro = (!$(this).find(':input[type="text"]').val() || $(this).find(':input[type="text"]').val() == '')? false : agregar_otro; 
    });
    if(agregar_otro){
        $(`#inputs_${tipo}`).append(`
            <div class="row border-bottom dinamic_input_materiales my-2 pb-2">
                <div class="col">
                    <input type="file" name="input_${tipo}_${$(`#inputs_${tipo}`).children().length + 1}" required>
                </div>
                <div class="col">
                    <input type="text" name="input_nombre_${tipo}_${$(`#inputs_${tipo}`).children().length + 1}" class="form-control" placeholder="Titulo del recurso" required>
                </div>
                <i class="fa fa-times float-right" onclick="$(this).parent().remove()" style="cursor:pointer;"></i>
            </div>
        `)
    }
}

function buscarExpedienteAdminUDC(idAfiliado){
    $("#formDocumentacionAdminUDC")[0].reset();
    $("#documentoUDC1").prop('disabled', false);
    $("#documentoUDC2").prop('disabled', false);
    $("#documentoUDC3").prop('disabled', false);
    $("#documentoUDC4").prop('disabled', false);
    $("#documentoUDC5").prop('disabled', false);
    $("#documentoUDC6").prop('disabled', false);
    $("#documentoUDC7").prop('disabled', false);
    $("#documentoUDC8").prop('disabled', false);
    $("#documentoUDC9").prop('disabled', false);
    $("#documentoUDC10").prop('disabled', false);
    $("#documentoUDC11").prop('disabled', false);
    $("#btnDocUDC1").prop('disabled', false);
    $("#btnDocUDC2").prop('disabled', false);
    $("#btnDocUDC3").prop('disabled', false);
    $("#btnDocUDC4").prop('disabled', false);
    $("#btnDocUDC5").prop('disabled', false);
    $("#btnDocUDC6").prop('disabled', false);
    $("#btnDocUDC7").prop('disabled', false);
    $("#btnDocUDC8").prop('disabled', false);
    $("#btnDocUDC9").prop('disabled', false);
    $("#btnDocUDC10").prop('disabled', false);
    $("#btnDocUDC11").prop('disabled', false);
    $("#btnDocUDC1").show();
    $("#btnDocUDC2").show();
    $("#btnDocUDC3").show();
    $("#btnDocUDC4").show();
    $("#btnDocUDC5").show();
    $("#btnDocUDC6").show();
    $("#btnDocUDC7").show();
    $("#btnDocUDC8").show();
    $("#btnDocUDC9").show();
    $("#btnDocUDC10").show();
    $("#btnDocUDC11").show();
    $("#btnEnviadoUDC1").hide();
    $("#btnEnviadoUDC2").hide();
    $("#btnEnviadoUDC3").hide();
    $("#btnEnviadoUDC4").hide();
    $("#btnEnviadoUDC5").hide();
    $("#btnEnviadoUDC6").hide();
    $("#btnEnviadoUDC7").hide();
    $("#btnEnviadoUDC8").hide();
    $("#btnEnviadoUDC9").hide();
    $("#btnEnviadoUDC10").hide();
    $("#btnEnviadoUDC11").hide();
    $("#idUsuarioUDC").val(idAfiliado);
    //cargarGrado();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'obtenerExpedienteAlumno',
               'idAlumno': idAfiliado},
        success: function(data){
            try{
                $("#modalSubirDocumentacionAdminUDC").modal("show");
                pr = JSON.parse(data)
                for(var i in pr.data){
                    if(pr.data[i].id_documento == 7){
                        $("#documentoUDC7").prop('disabled', true);
                        $("#btnDocUDC7").prop('disabled', true);
                        $("#btnDocUDC7").hide();
                        $("#btnEnviadoUDC7").show();
                    }
                    if(pr.data[i].id_documento == 8){
                        $("#documentoUDC8").prop('disabled', true);
                        $("#btnDocUDC8").prop('disabled', true);
                        $("#btnDocUDC8").hide();
                        $("#btnEnviadoUDC8").show();
                    }
                    if(pr.data[i].id_documento == 1){       
                        $("#documentoUDC1").prop('disabled', true);
                        $("#btnDocUDC1").prop('disabled', true);
                        $("#btnDocUDC1").hide();
                        $("#btnEnviadoUDC1").show();
                    }
                    if(pr.data[i].id_documento == 2){       
                        $("#documentoUDC2").prop('disabled', true);
                        $("#btnDocUDC2").prop('disabled', true);
                        $("#btnDocUDC2").hide();
                        $("#btnEnviadoUDC2").show();
                    }
                    if(pr.data[i].id_documento == 3){
                        $("#documentoUDC3").prop('disabled', true);
                        $("#btnDocUDC3").prop('disabled', true);
                        $("#btnDocUDC3").hide();
                        $("#btnEnviadoUDC3").show();
                    }
                    if(pr.data[i].id_documento == 4){    
                        $("#documentoUDC4").prop('disabled', true);
                        $("#gradoEstudiosUDC").prop('disabled', true);
                        $("#btnDocUDC4").prop('disabled', true);
                        $("#btnDocUDC4").hide();
                        $("#btnEnviadoUDC4").show();
                    }
                    if(pr.data[i].id_documento == 5){
                        $("#documentoUDC5").prop('disabled', true);
                        $("#btnDocUDC5").prop('disabled', true);
                        $("#btnDocUDC5").hide();
                        $("#btnEnviadoUDC5").show();
                    }
                    if(pr.data[i].id_documento == 6){
                        $("#documentoUDC6").prop('disabled', true);
                        $("#btnDocUDC6").prop('disabled', true);
                        $("#btnDocUDC6").hide();
                        $("#btnEnviadoUDC6").show();
                    }
                    if(pr.data[i].id_documento == 11){
                        $("#documentoUDC11").prop('disabled', true);
                        $("#btnDocUDC11").prop('disabled', true);
                        $("#btnDocUDC11").hide();
                        $("#btnEnviadoUDC11").show();
                    }
                    if(pr.data[i].id_documento == 9){
                        $("#documentoUDC9").prop('disabled', true);
                        $("#btnDocUDC9").prop('disabled', true);
                        $("#btnDocUDC9").hide();
                        $("#btnEnviadoUDC9").show();
                    }
                    if(pr.data[i].id_documento == 10){
                        $("#documentoUDC10").prop('disabled', true);
                        $("#btnDocUDC10").prop('disabled', true);
                        $("#btnDocUDC10").hide();
                        $("#btnEnviadoUDC10").show();
                    }
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#ocultarSubirDocumentacionUDC").on('click', function(){
    $("#modalSubirDocumentacionAdminUDC").modal('hide');
});

$("#formatosexistentes").on('change', function(){
    //var id_proceso = $("#formatosexistentes").val();
    cargarTablaFormatos();
});

function cargarTablaFormatos(id_proceso){
    var id_proceso = $("#formatosexistentes").val();
    tabladocservicio= $("#tabla-documentos-proceso").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultar_documentos_proceso', 
                idproc: id_proceso
            },
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
        'iDisplayLength':15,
        'order':[
            [0,'asc']
        ],
    });
}
$("#select_carreras_edit").on('change', function(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'listarGeneraciones',idBuscar: $("#select_carreras_edit").val()},
        success: function(data){
            if(data == 'no_session'){
                swal("Vuelve a iniciar sesión!").then( () => {window.location.replace("index.php");});
            }
            try{
                var carr = JSON.parse(data);
                opciones = '<option disabled selected>Seleccione una Generación</option>';
                for( i = 0; i < carr.length; i++ ){
                    opciones += '<option value="'+carr[i]['idGeneracion']+'-'+carr[i]['id_plan_estudio']+'" class="form-control" >'+carr[i]['nombre']+'</option>';
                }
                $("#select_generacion_edit").html(opciones);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})

$("#select_generacion_edit").on('change', function(){
    var datos = $("#select_generacion_edit").val().split("-");
    Data = {
        action: 'listarCiclos',
        idGeneracion: datos[0],
        idPlan: datos[1]
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal("Vuelve a iniciar sesión!").then( () => {window.location.replace("index.php");});
            }
            try{
                var matr = JSON.parse(data);
                opt = '<option disabled selected>Seleccione un Ciclo</option>';
                for(i in matr){
                    if( pr[i]['tipo_ciclo'] == 1 ) $ciclon = "Cuatrimestre";
                    else if( pr[i]['tipo_ciclo'] == 2 ) $ciclon = "Semestre";
                    else $ciclon = "Trimestre";
                    opt += '<option value="'+matr[i]['ciclo_asignado']+'">'+$ciclon+' '+matr[i]['ciclo_asignado']+'</option>';
                }
                
                $("#select_ciclo_edit").html(opt);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})

$("#select_ciclo_edit").on('change', function(){
    var datos = $("#select_generacion_edit").val().split("-");
    Data = {
        action: 'listarMaterias',
        idCiclo: $("#select_ciclo_edit").val(),
        idPlan: datos[1]
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            try{
                var materias = JSON.parse(data);
                opciones = '<option disabled selected> Seleccione una materia </option>';
                for( i = 0; i < materias.length; i++ ){
                    opciones += '<option value="'+materias[i]['id_materia']+'" class="form-control">'+materias[i]['nombre']+'</option>';
                }
                $("#select_materias_edit").html(opciones);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
});
//fin chuy

function validar_eliminar(idClase){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: "validarEliminarClase",
            idClass: idClase
        },
        success : function(data){
            if(data == "con_link"){
                Swal.fire({
                    title: 'Ya existe un link vinculado.',
                    text: 'Por favor, contacte el área de sistemas.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3800
                });
            }
            try{
                pr = JSON.parse(data)
                if(pr.video == ''){
                    Swal.fire({
                        text: '¿Estas seguro de eliminar la clase?',
                        type:'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: true,
                        confirmButtonColor: '#AA262C',
                        confirmButtonText: 'Aceptar',
                        cancelButtonColor: '#767575',
                        cancelButtonText: 'Cancelar'
                    }).then(result=>{
                        if(result.value){
                            eliminarClase(idClase);
                        }else{
                            swal("Cancelado Correctamente");
                        }
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}

function eliminarClase(idClase){
    Data = {
        action: "eliminarClase",
        idEliminar: idClase
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                if(data != 'no_session'){
                    swal({
                        title: 'Eliminado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    })
                    .then((result)=>{
                        tClases.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

$("#cerrarEditarExamen").on('click',function(){
$("#formularioEditarExamen")[0].reset();
$("#modalEditarExamen").modal('hide');
})

function asignarProrrogaAlumno(idAfiliado, idGeneracion){
    //console.log(idAfiliado)
    //console.log(idGeneracion)
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action: "asignarProrrogaAlumnoDocumento",
            idGen: idGeneracion,
            idAfi: idAfiliado
        },
        success: function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr)
                html_prorroga_doc = "";

                for(k = 0; k < pr.length ; k++){
                    //console.log(idAfiliado);console.log(idGeneracion);console.log(pr[k].id_documento);console.log(pr[k].nombre_documento);
                    obtenerIdDocumentoAlumnoProrroga(idAfiliado, idGeneracion, pr[k].id_documento, pr[k].nombre_documento);
  
                }


                $("#divAsigProrrogaDocumentos").html(html_prorroga_doc);

                $("#modalAsignarProrrogaDocumento").modal('show');
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#ocultarAsignarProrroga").on('click', function(){
    $("#modalAsignarProrrogaDocumento").modal('hide');

})

function obtenerIdDocumentoAlumnoProrroga(idAlumno, idGeneracion, idDocumento, nombre){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action : 'obtenerIdDocumentoAlumnoProrroga',
            idAlum: idAlumno,
            idDoc: idDocumento
        },
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr!=false){
                    if(pr.fecha_prorroga_digital==null && pr.fecha_prorroga_fisica==null){
                        html_prorroga_doc+=`<div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-5 col-md-5 mb-3">
                                                        <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 mb-3">
                                                        <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumento(${idDocumento},${idAlumno},${idGeneracion})">Digital</button>
                                                        <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumentoFisico(${idDocumento},${idAlumno},${idGeneracion})">Fisico</button>
                                                    </div>
                                                </div>
                                            </div><hr>`;
                    }else{
                        if(pr.fecha_prorroga_digital==null && pr.fecha_prorroga_fisica!=null){
                            html_prorroga_doc+=`<div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-5 col-md-5 mb-3">
                                                            <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6 mb-3">
                                                            <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumento(${idDocumento},${idAlumno},${idGeneracion})">Digital</button>
                                                            <button class="btn btn-primary waves-effect waves-light mr-2" onclick=modificarProrrogaDocumentoFisico("${pr.id_prorroga}")>Modificar Fisico</button>
                                                            <button class="btn btn-secondary waves-effect waves-light mr-2" onclick="desactivarProrrogaDocumentoFisico(${pr.id_prorroga},${idAlumno},${idGeneracion})">Desactivar Fisico</button>
                                                        </div>
                                                    </div>
                                                </div><hr>`;
                        }else{
                            if(pr.fecha_prorroga_digital!=null && pr.fecha_prorroga_fisica==null){
                                html_prorroga_doc+=`<div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-5 col-md-5 mb-3">
                                                                <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 mb-3">   
                                                                <button class="btn btn-primary waves-effect waves-light mr-2" onclick=modificarProrrogaDocumento("${pr.id_prorroga}")>Modificar Digital</button>
                                                                <button class="btn btn-secondary waves-effect waves-light mr-2" onclick="desactivarProrrogaDocumento(${pr.id_prorroga},${idAlumno},${idGeneracion})">Desactivar Digital</button>
                                                                <button class="btn btn-primary waves-effect waves-light mr-2 mt-2" onclick="asignarProrrogaDocumentoFisico(${idDocumento},${idAlumno},${idGeneracion})">Fisico</button>
                                                            </div>
                                                        </div>
                                                    </div><hr>`;
                            }else{
                                if(pr.fecha_prorroga_digital!=null && pr.fecha_prorroga_fisica!=null){
                                    html_prorroga_doc+=`<div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-5 col-md-5 mb-3">
                                                                    <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 mb-3">
                                                                    <button class="btn btn-primary waves-effect waves-light mr-2" onclick=modificarProrrogaDocumento("${pr.id_prorroga}")>Modificar Digital</button>
                                                                    <button class="btn btn-secondary waves-effect waves-light mr-2" onclick="desactivarProrrogaDocumento(${pr.id_prorroga},${idAlumno},${idGeneracion})">Desactivar Digital</button>
                                                                    <button class="btn btn-primary waves-effect waves-light mr-2 mt-2" onclick=modificarProrrogaDocumentoFisico("${pr.id_prorroga}")>Modificar Fisico</button>
                                                                    <button class="btn btn-secondary waves-effect waves-light mr-2 mt-2" onclick="desactivarProrrogaDocumentoFisico(${pr.id_prorroga},${idAlumno},${idGeneracion})">Desactivar Fisico</button>
                                                                </div>
                                                            </div>
                                                        </div><hr>`;
                                }
                            }    
                        }
                    }


                   /* if(pr.fecha_prorroga_digital!=null){
                        
                    }else{
                        html_prorroga_doc+=`<div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                            </div>
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumento(${idDocumento},${idAlumno},${idGeneracion})">Activar</button>
                                            </div>
                                        </div>
                                    </div>`;
                    }
                }else{
                    html_prorroga_doc+=`<div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                            </div>
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumento(${idDocumento},${idAlumno},${idGeneracion})">Activar</button>
                                            </div>
                                        </div>
                                    </div>`;*/
                }else{
                    html_prorroga_doc+=`<div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-5 col-md-5 mb-3">
                                                    <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mb-3">
                                                    <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumento(${idDocumento},${idAlumno},${idGeneracion})">Digital</button>
                                                    <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumentoFisico(${idDocumento},${idAlumno},${idGeneracion})">Fisico</button>
                                                </div>
                                            </div>
                                        </div><hr>`;
                }
                
                
                $("#divAsigProrrogaDocumentos").html(html_prorroga_doc);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}

function modificarProrrogaDocumento(idProrroga){
    $("#formModFechaProrrogaDigital")[0].reset();
    $("#idProrroga").val(idProrroga);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerDatosProrrogaDigital',
            idProrr: idProrroga,
        },
        success: function(data){
            try{
                pr = JSON.parse(data)
                $("#modificarFechaDigital").val(pr.fecha_prorroga_digital);
                $("#modificarHoraDigital").val(pr.hora_prorroga_digital);
                $("#modalModFechasProrrogaDocumentoDigital").modal('show');
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}

function modificarProrrogaDocumentoFisico(idProrroga){
    $("#formModFechaProrrogaFisico")[0].reset();
    $("#idProrrogaFisico").val(idProrroga);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerDatosProrrogaFisico',
            idProrr: idProrroga,
        },
        success: function(data){
            try{
                pr = JSON.parse(data)
                $("#modificarFechaFisico").val(pr.fecha_prorroga_fisica);
                $("#modificarHoraFisico").val(pr.hora_prorroga_fisica);
                $("#modalModFechasProrrogaDocumentoFisico").modal('show');
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}


$("#formModFechaProrrogaDigital").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'modificarProrrogaDigital');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus== 'ok'){
                    swal({
                        title: 'Modificado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formModFechaProrrogaDigital")[0].reset();
                        $("#modalModFechasProrrogaDocumentoDigital").modal("hide");
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})

$("#formModFechaProrrogaFisico").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'modificarProrrogaFisico');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            try{
                pr = JSON.parse(data)
                if(pr.estatus== 'ok'){
                    swal({
                        title: 'Modificado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formModFechaProrrogaFisico")[0].reset();
                        $("#modalModFechasProrrogaDocumentoFisico").modal("hide");
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})

function asignarProrrogaDocumento(idDocumento, idAlumno, idGeneracion){
    $("#formFechaProrrogaDigital")[0].reset();
    $("#idDocumento").val(idDocumento);
    $("#idAlumno").val(idAlumno);
    $("#idGeneracion").val(idGeneracion);
    $("#modalFechasProrrogaDocumentoDigital").modal('show');
}

function asignarProrrogaDocumentoFisico(idDocumento, idAlumno, idGeneracion){
    $("#formFechaProrrogaFisico")[0].reset();
    $("#idDocumentoFisico").val(idDocumento);
    $("#idAlumnoFisico").val(idAlumno);
    $("#idGeneracionFisico").val(idGeneracion);
    $("#modalFechasProrrogaDocumentoFisico").modal('show');
}

$("#formFechaProrrogaDigital").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'asignarFechaProrrogaDigital');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        processData: false,
        contentType: false,
        success: function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr)
                if(pr!=[]){
                    swal({
                        title: 'Asignada Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formFechaProrrogaDigital")[0].reset();
                        $("#modalFechasProrrogaDocumentoDigital").modal("hide");
                        asignarProrrogaAlumno(pr.idAlumno, pr.id_generacion);
                        tAlumnos.ajax.reload();
                    })
                }
                /*
                if(pr.estatus== 'ok'){
                    swal({
                        title: 'Asignada Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formFechaProrrogaDigital")[0].reset();
                        $("#modalFechasProrrogaDocumentoDigital").modal("hide");
                    })
                }*/
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})

function ObtenerControlEscolar(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'ObtenerControlEscolar',
        },
        dataType: 'JSON',
        success: function(data){
            console.log(data);
            $("#nombreAdmin").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#nombreAdmin").append('<option value='+registro.idAcceso+'>'+registro.nombres+'</option>');
                });
        }
    })
}

$("#formFechaProrrogaFisico").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'asignarFechaProrrogaFisico');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        processData: false,
        contentType: false,
        success: function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr)
                if(pr!=[]){
                    swal({
                        title: 'Asignada Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formFechaProrrogaFisico")[0].reset();
                        $("#modalFechasProrrogaDocumentoFisico").modal("hide");
                        asignarProrrogaAlumno(pr.idAlumno, pr.id_generacion);
                        tAlumnos.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
})

$("#ocultarAsignarProrrogaModificar").on('click', function(){
    $("#formModFechaProrrogaDigital")[0].reset();
    $("#modalModFechasProrrogaDocumentoDigital").modal('hide');
})

$("#ocultarAsignarProrrogaModificarFisico").on('click', function(){
    $("#formModFechaProrrogaFisico")[0].reset();
    $("#modalModFechasProrrogaDocumentoFisico").modal('hide');
})

$("#ocultarAsignarProrrogaAsignar").on('click', function(){
    $("#formFechaProrrogaDigital")[0].reset();
    $("#modalFechasProrrogaDocumentoDigital").modal('hide');
})

$("#ocultarAsignarProrrogaFisico").on('click', function(){
    $("#formFechaProrrogaFisico")[0].reset();
    $("#modalFechasProrrogaDocumentoFisico").modal('hide');
})


function desactivarProrrogaDocumento(idProrroga, idAlumno, idGeneracion){
    Swal.fire({
        text: '¿Estas seguro de desactivar la prorroga?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            quitarProrrogaDocumento(idProrroga, idAlumno, idGeneracion);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function desactivarProrrogaDocumentoFisico(idProrroga, idAlumno, idGeneracion){
    Swal.fire({
        text: '¿Estas seguro de desactivar la prorroga?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            quitarProrrogaDocumentoFisico(idProrroga, idAlumno, idGeneracion);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}


function quitarProrrogaDocumento(idProrroga, idAlumno, idGeneracion){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'quitarProrrogaDocumento',
            id: idProrroga,
            idAlum: idAlumno,
            idGen: idGeneracion
        },
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                if(pr!=[]){
                    swal({
                        title: 'Desactivado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        asignarProrrogaAlumno(pr.idAlumno, pr.id_generacion);
                        tAlumnos.ajax.reload();
                    });
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function quitarProrrogaDocumentoFisico(idProrroga, idAlumno, idGeneracion){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'quitarProrrogaDocumentoFisico',
            id: idProrroga,
            idAlum: idAlumno,
            idGen: idGeneracion
        },
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                if(pr!=[]){
                    swal({
                        title: 'Desactivado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        asignarProrrogaAlumno(pr.idAlumno, pr.id_generacion);
                        tAlumnos.ajax.reload();
                    });
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function registrarDocumentacion(idAlumno, idGeneracion){
    $("#divDocumentosFisico").empty();
    $("#formRegistrarDocumentosFisicos")[0].reset();
    html_check_doc = "";
    ObtenerControlEscolar();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action : 'obtenerListaDocumentosFisicos',
            idGen: idGeneracion
        },
        success : function(data){
            try{
                pr = JSON.parse(data)
               
                for(let z = 0; z < pr.length ; z++){
                        listadoDeDocumentos(pr[z].id_documento, pr[z].nombre_documento, idAlumno);
                    //listadoDeDocumentos(pr[z].id_documento, pr[z].nombre_documento, idAlumno);
                }

                $("#idAlumnoDocumentacionFisica").val(idAlumno);
                $("#modalRegistrarDocumentosFisicos").modal('show');
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}


function listadoDeDocumentos(idDocumento, nombreDocumento, idAlumno){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'recuperarChecksDocumentos',
            idDoc: idDocumento,
            idAlum: idAlumno
        },
        success: function(data){
            try{
                pr = JSON.parse(data);
                //console.log(pr)
                var check;
                if(pr!=false){
                    console.log(pr.fecha_registro);
                    var fecha = pr.fecha_registro.substr(0,10);

                    $("#fechaDocumentoFisico").val(fecha);
                    //$("#nombreAdmin").val(pr.nombres);
                    $(`#nombreAdmin option:contains(${pr.nombres})`).attr('selected', true);
                    check="checked";
                }else{
                    check ='';
                }
                html_check_doc+=`<div class="form-group">
                                    <div class="row">
                                        <div class = "col-md-4">
                                            <a class="list-group-item list-group-item-action">
                                                <input type="checkbox" name="checkName${idDocumento}" id="checkName${idDocumento}" ${check}> ${nombreDocumento}
                                           </a>
                                        </div>
                                        
                                        ${pr.nombres != "" && pr.nombres != null && pr.nombres != undefined  ?
                                            `<div class = "col-md-4">
                                                <a class="list-group-item list-group-item-action">
                                                    <b>${pr.nombres}</b>
                                                </a>
                                            </div>` : ""}

                                        ${pr.fecha_registro != "" && pr.fecha_registro != null && pr.fecha_registro != undefined  ?
                                            `<div class = "col-md-4">
                                                <a class="list-group-item list-group-item-action">
                                                    <b>${pr.fecha_registro}</b>
                                                </a>
                                            </div>` : ""}
                                    </div>
                                </div>`;


                /*if(pr!=false){                
                html_check_doc+=`<div class="form-group">
                                    <div class="row">
                                    <label style="width:100%">
                                        <a class="list-group-item list-group-item-action">
                                            <input type="checkbox" name="checkName${idDocumento}" id="checkName${idDocumento}" checked> ${nombreDocumento}
                                        </a></label>
                                    </div>
                                </div>`;
                }else{
                    html_check_doc+=`<div class="form-group">
                                    <div class="row">
                                    <label style="width:100%">
                                        <a class="list-group-item list-group-item-action">
                                            <input type="checkbox" name="checkName${idDocumento}" id="checkName${idDocumento}"> ${nombreDocumento}
                                        </a></label>
                                    </div>
                                </div>`;
                }*/
                $("#divDocumentosFisico").html(html_check_doc);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

$("#formRegistrarDocumentosFisicos").on('submit', function(e){
    e.preventDefault();

    var fecha = $("#fechaDocumentoFisico").val();
    var nombre = $("#nombreAdmin").val();
    fData = new FormData(this);
    fData.append('action', 'registrarDocumentosFisicos');
    fData.append('idUsuarioRegistro', nombre);
    fData.append('fechaAct', fecha);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        processData: false,
        contentType: false,
        success: function(data){
            if(data == 'selecciona_documento'){
                swal({
                    title: 'Seleccione un documento',
                    icon: 'info',
                    text: 'No se detecto la selección de algun documento listado.',
                    button: false,
                    timer: 4700,
                });
            }
            if(data == 'documentos_existentes'){
                swal({
                    title: 'Estos documentos ya estan guardados.',
                    icon: 'info',
                    text: 'No hay cambios por guardar.',
                    button: false,
                    timer: 4200,
                });
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Registrado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formRegistrarDocumentosFisicos")[0].reset();
                        $("#modalRegistrarDocumentosFisicos").modal("hide");
                    })
                }
                //console.log(pr)
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})

$("#ocultarDocumentacionFisica").on('click', function(){
    $("#modalRegistrarDocumentosFisicos").modal('hide');
    $("#formRegistrarDocumentosFisicos")[0].reset();
})

function obtenerProrrogasFisica(idAfiliado, idGeneracion){
console.log(idAfiliado);
console.log(idGeneracion);
$.ajax({
    url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
    type: 'POST',
    data:{
        action: 'obtenerProrrogasFisica',
        idAfi: idAfiliado,
        idGen: idGeneracion
    },
    success : function(data){
        try{
            pr = JSON.parse(data);
            console.log(pr);

            html_prorroga_fisica = "";

                for(k = 0; k < pr.length ; k++){
                    obtenerIdProrrogaDocumentoFisico(idAfiliado, idGeneracion, pr[k].id_documento, pr[k].nombre_documento);
  
                }

                $("#divAsigProrrogaDocumentos").html(html_prorroga_fisica);

                $("#modalAsignarProrrogaDocumento").modal('show');

        }catch(e){
            console.log(e);
            console.log(data);
        }
    }
});
}

/*
function obtenerIdProrrogaDocumentoFisico(idAlumno, idGeneracion, idDocumento, nombre){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data:{
            action : 'obtenerIdProrrogaDocumentoFisico',
            idAlum: idAlumno,
            idDoc: idDocumento
        },
        success : function(data){
            try{
                pr = JSON.parse(data);
                if(pr!=false){
                    if(pr.fecha_prorroga_digital!=null){
                        html_prorroga_fisica+=`<div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 mb-3">
                                                    <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mb-3">
                                                    <button class="btn btn-primary waves-effect waves-light mr-2" onclick=modificarProrrogaDocumento("${pr.id_prorroga}")>Modificar</button>
                                                    <button class="btn btn-secondary waves-effect waves-light mr-2" onclick="desactivarProrrogaDocumento(${pr.id_prorroga},${idAlumno},${idGeneracion})">Desactivar</button>
                                                </div>
                                            </div>
                                        </div>`;
                    }else{
                        html_prorroga_fisica+=`<div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                            </div>
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumento(${idDocumento},${idAlumno},${idGeneracion})">Activar</button>
                                            </div>
                                        </div>
                                    </div>`;
                    }
                }else{
                    html_prorroga_fisica+=`<div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <input class="form-control col-sm-12" type="text" disabled value="${nombre}"></input>
                                            </div>
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <button class="btn btn-primary waves-effect waves-light mr-2" onclick="asignarProrrogaDocumento(${idDocumento},${idAlumno},${idGeneracion})">Activar</button>
                                            </div>
                                        </div>
                                    </div>`;
                }
            
                $("#divAsigProrrogaDocumentosFisicos").html(html_prorroga_fisica);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}*/

function buscarCarrerasMaterias(){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {action: 'buscarClasesCarrera'},
        dataType: 'JSON',
        success: function(data){
            $("#selectCarrerasAsistenciasMateria").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#selectCarrerasAsistenciasMateria").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    });  
}


$("#selectCarrerasAsistenciasMateria").on('change', function(){
$("#divGeneracionAsistencias").show();
var seleccionCarreraMateria = $("#selectCarrerasAsistenciasMateria").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesMaterias',
            idCarrera: seleccionCarreraMateria
            },
        dataType: 'JSON',
        success: function(data){
            $("#selectGeneracionAsistenciasMaterias").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#selectGeneracionAsistenciasMaterias").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            })
        }
    })
})

$("#selectGeneracionAsistenciasMaterias").on('change', function(){
    $("#divCicloAsistencias").show();
var seleccionGeneracionMateria = $("#selectGeneracionAsistenciasMaterias").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerCiclosGeneracion',
            idGen: seleccionGeneracionMateria
        },
        dataType: 'JSON',
        success: function(data){
            $("#selectCicloAsistenciasMaterias").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                if(registro.tipoCiclo == 1){
                    $("#selectCicloAsistenciasMaterias").append('<option value='+registro.id_asignacion+'>Cuatrimestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 2){
                    $("#selectCicloAsistenciasMaterias").append('<option value='+registro.id_asignacion+'>Semestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 3){
                    $("#selectCicloAsistenciasMaterias").append('<option value='+registro.id_asignacion+'>Trimestre '+registro.ciclo_asignado+'</option>');
                }
            })
        }
    })
})

$("#selectCicloAsistenciasMaterias").on('change', function(){
    var seleccionGeneracion = $("#selectGeneracionAsistenciasMaterias").val();
    var cicloAsistencias = $("#selectCicloAsistenciasMaterias").val();

    tAsistenciasMaterias = $("#datatable-tablaAsisteciasMateria").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarAsistenciaMaterias', 
                idCiclo: cicloAsistencias,
                idGen: seleccionGeneracion
            },
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
            [0,'asc']
        ],
    });
})

//Llenar todos los select del tab calificaciones 
function carrerasCalificacion(Band){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'buscarClasesCarrera',
            vista: Band
        },
        dataType: 'JSON',
        success: function(data){
            $("#carrera-servicio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasCalificaciones").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasCalificaciones_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasCalificacionesReporte").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasCalificacionesBoletas").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasCalificacionesKardex").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#carrerasCalificacionesTitulados").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            
            $.each(data, function(key,registro){
                $("#carrera-servicio").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasCalificaciones").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasCalificaciones_noAcre").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasCalificacionesReporte").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasCalificacionesBoletas").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasCalificacionesKardex").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasCalificacionesTitulados").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        }
    })
}

//LLENAR TTABLA DE Servicio
$("#carrera-servicio").on('change',function(){
    var idCarrera = $("#carrera-servicio").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generaciones-servicio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generaciones-servicio").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generaciones-servicio").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});

//LLENAR TTABLA DE TITULADOS
$("#carrerasCalificacionesTitulados").on('change',function(){
    var idCarrera = $("#carrerasCalificacionesTitulados").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionesCalificacionesTitulados").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generacionesCalificacionesTitulados").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generacionesCalificacionesTitulados").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});

//Reporte por semestre
$("#carrerasCalificacionesBoletas").on('change',function(){
    var idCarrera = $("#carrerasCalificacionesBoletas").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionesCalificacionesBoletas").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generacionesCalificacionesBoletas").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generacionesCalificacionesBoletas").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});

//Karderx Generaciones
$("#carrerasCalificacionesKardex").on('change',function(){
    var idCarrera = $("#carrerasCalificacionesKardex").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionesCalificacionesKardex").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generacionesCalificacionesKardex").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generacionesCalificacionesKardex").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});


//Reporte por semestre
$("#carrerasCalificacionesReporte").on('change',function(){
    $("#cicloCalificacionesReporte").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    var idCarrera = $("#carrerasCalificacionesReporte").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionesCalificacionesReporte").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generacionesCalificacionesReporte").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generacionesCalificacionesReporte").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});


//No acreditados
$("#carrerasCalificaciones_noAcre").on('change',function(){
    $("#cicloCalificaciones_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    $("#MateriaCalificacion_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    var idCarrera = $("#carrerasCalificaciones_noAcre").val();
    var buttonCambiarMin = document.getElementById('CambiarCalifiacionMinima');
    buttonCambiarMin.disabled = true;

    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionesCalificaciones_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generacionesCalificaciones_noAcre").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generacionesCalificaciones_noAcre").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});


//Calificaciones Totales
$("#carrerasCalificaciones").on('change',function(){
    $("#cicloCalificaciones").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    var idCarrera = $("#carrerasCalificaciones").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerGeneracionesCarrera',
            idCarr: idCarrera
        },
        dataType: 'JSON',
        success: function(data){
            $("#generacionesCalificaciones").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#generacionesCalificaciones").append('<option value='+registro.idGeneracion+'>'+registro.nombre+'</option>');
            });
        },
        error : function(xhr){
            if(xhr.responseText == 'sin_generaciones'){
                $("#generacionesCalificaciones").html('<option selected="true" value="" disabled="disabled">Sin generaciones asignadas</option>');
            }
        }
    });
});


$("#generacionesCalificaciones").on('change', function(){
    var idGeneracion = $("#generacionesCalificaciones").val();
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data: {
            action: 'obtenerCiclosGeneracion',
            idGen: idGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#cicloCalificaciones").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                if(registro.tipoCiclo == 1){
                    $("#cicloCalificaciones").append('<option value='+registro.id_asignacion+'>Cuatrimestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 2){
                    $("#cicloCalificaciones").append('<option value='+registro.id_asignacion+'>Semestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 3){
                    $("#cicloCalificaciones").append('<option value='+registro.id_asignacion+'>Trimestre '+registro.ciclo_asignado+'</option>');
                }
            })
        }
    });   
});

$("#generaciones-servicio").on('change', function(){
    
    $("#articulo-servicio").empty().append('<option selected disabled>Seleccione una opcion...</option>');
    $("#articulo-servicio").append('<option value="1">Artículo 52 LRART. 5 Const.</option>');
    $("#articulo-servicio").append('<option value="2">Artículo 55 LRART. 5 Const.</option>');
    $("#articulo-servicio").append('<option value="3">Artículo 91 LRART. 5 Const.</option>');
    $("#articulo-servicio").append('<option value="4">Artículo 10 Reglamento para la prestación del servicio social de los estudiantes de las instituciones de educacación superior en la república mexicana.</option>');
    $("#articulo-servicio").append('<option value="5">No aplica</option>');
});

//No acreditados
$("#generacionesCalificaciones_noAcre").on('change', function(){
    $("#cicloCalificaciones_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    $("#MateriaCalificacion_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    var idGeneracion = $("#generacionesCalificaciones_noAcre").val();
    var buttonCambiarMin = document.getElementById('CambiarCalifiacionMinima');
    buttonCambiarMin.disabled = true;
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data: {
            action: 'obtenerCiclosGeneracion',
            idGen: idGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#cicloCalificaciones_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                if(registro.tipoCiclo == 1){
                    $("#cicloCalificaciones_noAcre").append('<option value='+registro.id_asignacion+'>Cuatrimestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 2){
                    $("#cicloCalificaciones_noAcre").append('<option value='+registro.id_asignacion+'>Semestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 3){
                    $("#cicloCalificaciones_noAcre").append('<option value='+registro.id_asignacion+'>Trimestre '+registro.ciclo_asignado+'</option>');
                }
            })
        }
    });   
});

//Boletas
$("#generacionesCalificacionesBoletas").on('change', function(){
    var idGeneracion = $("#generacionesCalificacionesBoletas").val();
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data: {
            action: 'obtenerCiclosGeneracion',
            idGen: idGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#cicloCalificacionesBoletas").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                if(registro.tipoCiclo == 1){
                    $("#cicloCalificacionesBoletas").append('<option value='+registro.id_asignacion+'>Cuatrimestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 2){
                    $("#cicloCalificacionesBoletas").append('<option value='+registro.id_asignacion+'>Semestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 3){
                    $("#cicloCalificacionesBoletas").append('<option value='+registro.id_asignacion+'>Trimestre '+registro.ciclo_asignado+'</option>');
                }
            })
        }
    });   
});

//Generacion Reporte
$("#generacionesCalificacionesReporte").on('change', function(){
    var idGeneracion = $("#generacionesCalificacionesReporte").val();
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data: {
            action: 'obtenerCiclosGeneracion',
            idGen: idGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#cicloCalificacionesReporte").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                if(registro.tipoCiclo == 1){
                    $("#cicloCalificacionesReporte").append('<option value='+registro.id_asignacion+'>Cuatrimestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 2){
                    $("#cicloCalificacionesReporte").append('<option value='+registro.id_asignacion+'>Semestre '+registro.ciclo_asignado+'</option>');
                }
                if(registro.tipoCiclo == 3){
                    $("#cicloCalificacionesReporte").append('<option value='+registro.id_asignacion+'>Trimestre '+registro.ciclo_asignado+'</option>');
                }
            })
        }
    });   
});


//No acreditados
$("#cicloCalificaciones_noAcre").on('change', function(){
    $("#MateriaCalificacion_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
    var seleccionGeneracion = $("#generacionesCalificaciones_noAcre").val();
    var cicloAsistencias = $("#cicloCalificaciones_noAcre").val();
    var buttonCambiarMin = document.getElementById('CambiarCalifiacionMinima');
    buttonCambiarMin.disabled = true;
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data: {
            action: 'listaMateriasSelect', 
            idCiclo: cicloAsistencias,
            idGen: seleccionGeneracion
        },
        dataType: 'JSON',
        success: function(data){
            $("#MateriaCalificacion_noAcre").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                    $("#MateriaCalificacion_noAcre").append('<option value='+registro.id_materia+'>'+registro.nombre+'</option>');
            })
        }
    });   
});

$("#CambiarCalifiacionMinima").on('click', function(){
    var idMateria = $("#MateriaCalificacion_noAcre").val();
    var CalificacionNueva = $("#Calificacion_minima").val();
    if(CalificacionNueva>10){
        CalificacionNueva=10;
    }else if(CalificacionNueva<0){
        CalificacionNueva=0;
    }
    //console.log(CalificacionNueva);

    //let nodo_act = false;
    Swal.fire({
        text: '¿Estás seguro de cambiar la calificación minmima? Al aceptar reprobará a los alumnos con calificacion menor',
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
                url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
                type:'POST',
                data: {
                    action: 'CambiarCalificacionMinima', 
                    idMat: idMateria,
                    CalNue: CalificacionNueva
                },
                dataType: 'JSON',
                success: function(data){
                    try{
                        pr = JSON.parse(data)
                        if(pr == 1){
                            swal({
                                title: 'Calificación mínima actualizada.',
                                icon: 'success',
                                text: '\r',
                                button: false,
                                timer: 2500,
                            }).then((result)=>{
                                var idGeneracion = $("#generacionesCalificaciones_noAcre").val();
                                LlenadoTablaNoAcreditado(idMateria,idGeneracion);  
                            })
                        }
                    }catch(e){
                        console.log(e);
                        console.log(data)
                    }
                }
            });   
        }
    })
    
});

function TablaSolicitaDocumentos(){
    tablaCorreccionesServicio= $("#Solicitar-select-servicio").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'DocumentosAlumnosServicioSolic', 
            },
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
        'iDisplayLength':15,
        'order':[
            [0,'asc']
        ],
    });
}


function tablaCorrecciones(){
    tablaCorreccionesServicio= $("#correcciones-select-servicio").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'DocumentosAlumnosServicioCorr', 
            },
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
        'iDisplayLength':15,
        'order':[
            [0,'asc']
        ],
    });
}

function tablaRevisiones(){
    tablaRevisionesServicio = $("#revisiones-select-servicio").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarDocumentosAlumnosServicio', 
            },
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
        'iDisplayLength':15,
        'order':[
            [0,'asc']
        ],
    });
}

function tablaCredenciales(idAlumno){
    
    $("#HistorialCredenciales").modal("show");
    tablaSolicitudCred= $("#tablaHistorialCredenciales").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarHistorialSolicitudes',
                idAlu: idAlumno
            },
            dataType: "JSON",
            error: function(e){
                //console.log(e.responseText);
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
        'iDisplayLength':15,
        'order':[
            [1,'desc']
        ],
    });
}


function TablaCredenciales(){
    tablacredencialesnuevos= $("#datatable-tablaCredencialesAlumno").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'obtenerSolicitudesCredenciales', 
            },
            dataType: "JSON",
            error: function(e){
                //console.log(e.responseText);
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
        'iDisplayLength':15,
        'order':[
            [2,'desc']
        ],
    });
}

function TablaProcesos(){
    tablaprocesosnuevos= $("#tabla-procesos-nuevo").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarProcesos', 
            },
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
        'iDisplayLength':15,
        'order':[
            [0,'asc']
        ],
    });
    LlenarTablaServicio();
}

function LlenarTablaServicio(){
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data: {
            action: 'consultarProcesoSelect', 
        },
        dataType: 'JSON',
        success: function(data){
            $("#formatosexistentes").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                    $("#formatosexistentes").append('<option value='+registro.idproceso+'>'+registro.nombre+'</option>');
                    //console.log(registro.calificacion_min);
            });
        }
    });  
}



function actualizarestatusSolicituscred(idAlumno,estatus){
    Swal.fire({
        text: '¿Está  seguro de cambiar el estatus de la solicitud de credencial?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            console.log("Eliminando");
             //Ajax para eliminar un proceso acorde al id 
            $.ajax({
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {action: 'actualizarestatusSolicituscred',
                    idSol: idAlumno,
                    estat: estatus
                        },
               
                success: function(data){
                    if(data == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se cargó",
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
                                title: 'Actualizado Correctamente',
                                icon: 'info',
                                text: 'Espere un momento...',
                                button: false,
                                timer: 2200
                            }).then((result)=>{
                                
                                tablaSolicitudCred.ajax.reload(null,false)
                                tablacredencialesnuevos.ajax.reload(null,false);
                            })
                        }

                        
                    }catch(e){
                        console.log(e)
                        console.log(data)
                    }   
                }
            });
        }
    });
}


$("#articulo-servicio").on('change', function(){
    $("#button-servicio").prop("disabled",true);
    id_asignados_check = [];
    var idGeneracion = $("#generaciones-servicio").val();
    var idCarrera = $("#carrera-servicio").val();
    tablaAlumnosAsignados= $("#alumnos-select-servicio").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarAlumnosGenAsignar', 
                idGen: idGeneracion,
                idCarr: idCarrera
            },
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
        'iDisplayLength':15,
        'order':[
            [0,'asc']
        ],
    });
});

//lISTAR ALUMNOS EN TITULADOS
$("#generacionesCalificacionesTitulados").on('change', function(){
    var seleccionGeneracion = $("#generacionesCalificacionesTitulados").val();
    var carrerasAlumno = $("#carrerasCalificacionesTitulados").val();

    tablaAlumnosTitulados= $("#table-alumnos-titulados").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarAlumnosGenTitulados', 
                idGen: seleccionGeneracion,
                idCarr: carrerasAlumno
            },
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
        'iDisplayLength': 15,
        'order':[
            [0,'asc']
        ],
    });
});

//lISTAR ALUMNOS EN KARDEX
$("#generacionesCalificacionesKardex").on('change', function(){
    var seleccionGeneracion = $("#generacionesCalificacionesKardex").val();
    var carrerasAlumno = $("#carrerasCalificacionesKardex").val();

    tCalificaciones = $("#table-alumnos-calificacionesKardex").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarAlumnosGen', 
                idGen: seleccionGeneracion,
                idCarr: carrerasAlumno
            },
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
            [0,'asc']
        ],
    });
})

//lISTAR ALUMNOS EN BOLETAS
$("#cicloCalificacionesBoletas").on('change', function(){
    var seleccionGeneracion = $("#generacionesCalificacionesBoletas").val();
    var cicloAsistencias = $("#cicloCalificacionesBoletas").val();
    var Carrera = $("#carrerasCalificacionesBoletas").val();
    //var Generacion = $("#generacionesCalificacionesBoletas").text();
    //var Ciclo = $("#cicloCalificacionesBoletas").text();

    tCalificaciones = $("#table-alumnos-calificacionesBoletas").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarCalificacionesBoletas', 
                idCiclo: cicloAsistencias,
                idGen: seleccionGeneracion,
                idCarr: Carrera/*,
                Gen: Generacion,
                Cic: Ciclo*/
            },
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
            [0,'asc']
        ],
    });
})

//lISTAR ALUMNOS EN REPORTE
$("#cicloCalificacionesReporte").on('change', function(){
    var seleccionGeneracion = $("#generacionesCalificacionesReporte").val();
    var cicloAsistencias = $("#cicloCalificacionesReporte").val();
    var carrerasCalif = $("#carrerasCalificacionesReporte").val();

    tCalificaciones = $("#table-alumnos-calificacionesReporte").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'consultarCalificacionesGenCiclo', 
                idCiclo: cicloAsistencias,
                idGen: seleccionGeneracion,
                idCarr: carrerasCalif
            },
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
            [0,'asc']
        ],
    });
});

//Listar alumnos por calificacion



//lISTAR MATERIAS EN ASIGNAR CALIFICACIONES
$("#cicloCalificaciones").on('change', function(){
    var seleccionGeneracion = $("#generacionesCalificaciones").val();
    var cicloAsistencias = $("#cicloCalificaciones").val();

    tCalificaciones = $("#datatable-tablaCalificaciones").DataTable({
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
            url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
            type: 'POST',
            data: {
                action: 'listaMaterias', 
                idCiclo: cicloAsistencias,
                idGen: seleccionGeneracion
            },
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
            [0,'asc']
        ],
    });
})


//Modal No acreditados
$("#MateriaCalificacion_noAcre").on('change', function(){
    var idMateria = $("#MateriaCalificacion_noAcre").val();
    var idGeneracion = $("#generacionesCalificaciones_noAcre").val();
    var buttonCambiarMin = document.getElementById('CambiarCalifiacionMinima');
    buttonCambiarMin.disabled = false;
    
    LlenadoTablaNoAcreditado(idMateria,idGeneracion);  
});

function LlenadoTablaNoAcreditado(idMateria,idGeneracion){
    $.ajax({
        url:'../assets/data/Controller/controlescolar/controlescolarControl.php',
        type:'POST',
        data: {
            action: 'ObtenerCalificacionMinima', 
            idMat: idMateria
        },
        dataType: 'JSON',
        success: function(data){
            $("#Calificacion_minima").text("Selecciona una materia");
            $.each(data, function(key,registro){
                    $("#Calificacion_minima").val(registro.calificacion_min);
                    //console.log(registro.calificacion_min);
            })
        }
    });   

    tCaficaciones = $("#table-alumnos-calificacionesNoAcre").DataTable({
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
                className: "btn-primary",
                exportOptions: {
                  customizeData: function (d) {
                    for(i = 0; i < d.body.length; i++){
                        pos = $("#table-alumnos-calificacionesNoAcre").DataTable().cell(i, 1).data().indexOf('value')
                        text_tag = $("#table-alumnos-calificacionesNoAcre").DataTable().cell(i,1).data().substr(pos + 7)
                        d.body[i][1] = text_tag.substr(0, text_tag.indexOf('"'));
                        d.body[i][2] = '';
                    }
                  }
                }
            }, {
                extend: "pdf",
                exportOptions: {
                  customizeData: function (d, dot) {
                      console.log(dot);
                    for(i = 0; i < d.body.length; i++){
                        pos = $("#table-alumnos-calificacionesNoAcre").DataTable().cell(i, 1).data().indexOf('value')
                        text_tag = $("#table-alumnos-calificacionesNoAcre").DataTable().cell(i,1).data().substr(pos + 7)
                        d.body[i][1] = text_tag.substr(0, text_tag.indexOf('"'));
                        d.body[i][2] = '';
                    }
                  }
                }
            /*}, {
                extend: "print"*/
            }],
            "ajax": {
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {
                    action: 'consultarCalificaciones_noAcre',
                    idMat: idMateria,
                    idGen: idGeneracion
                },
                dataType: "JSON",
                error: function(e){
                    console.log(e.responseText);
                    if(e.responseText == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se actualizó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }	
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
            'iDisplayLength': 10,
            'order':false
        });
        setTimeout(() => {
            tCaficaciones.columns.adjust();
        }, 800);
}

//Boton reporte por semestre
function consultarCalificacionesCicloGen(idAlumno, nombre){
    $("#modalReporteSemestre").modal('show');
    var idCiclo = $("#cicloCalificacionesReporte").val();
    var idGen = $("#generacionesCalificacionesReporte").val();
    var idCarr = $("#carrerasCalificacionesReporte").val();
    
    $("#info").text(nombre);
    tablaMateriasReportes(idCiclo,idAlumno,idGen,idCarr);

    
}

/*function boletasCalificacionesCicloGen(idAlumno,nombre){
    window.location.assign('../controlescolar/GenerarBoleta.php');
}*/


//modalTablaCalificaciones

function consultarCalificaciones(idMateria, idGeneracion){
    $("#modalTablaCalificaciones").modal('show');
    $("#calificacionMat").val(idMateria);
    $("#calificacionGen").val(idGeneracion);
    tCaficaciones = $("#table-alumnos-calificaciones").DataTable({
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
                className: "btn-primary",
                exportOptions: {
                  customizeData: function (d) {
                    for(i = 0; i < d.body.length; i++){
                        pos = $("#table-alumnos-calificaciones").DataTable().cell(i, 1).data().indexOf('value')
                        text_tag = $("#table-alumnos-calificaciones").DataTable().cell(i,1).data().substr(pos + 7)
                        d.body[i][1] = text_tag.substr(0, text_tag.indexOf('"'));
                        d.body[i][2] = '';
                    }
                  }
                }
            }, {
                extend: "pdf",
                exportOptions: {
                  customizeData: function (d, dot) {
                      console.log(dot);
                    for(i = 0; i < d.body.length; i++){
                        pos = $("#table-alumnos-calificaciones").DataTable().cell(i, 1).data().indexOf('value')
                        text_tag = $("#table-alumnos-calificaciones").DataTable().cell(i,1).data().substr(pos + 7)
                        d.body[i][1] = text_tag.substr(0, text_tag.indexOf('"'));
                        d.body[i][2] = '';
                    }
                  }
                }
            /*}, {
                extend: "print"*/
            }],
            "ajax": {
                url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
                type: 'POST',
                data: {
                    action: 'consultarCalificaciones',
                    idMat: idMateria,
                    idGen: idGeneracion
                },
                dataType: "JSON",
                error: function(e){
                    console.log(e.responseText);
                    if(e.responseText == 'no_session'){
                        swal({
                            title: "Vuelve a iniciar sesión!",
                            text: "La informacion no se actualizó",
                            icon: "info",
                        });
                        setTimeout(function(){
                            window.location.replace("index.php");
                        }, 2000);
                    }	
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
            'iDisplayLength': 10,
            'order':false
        });
        setTimeout(() => {
            tCaficaciones.columns.adjust();
        }, 800);
}

$("#Guardar_Calificaciones").on("click", function(e){
    e.preventDefault();
    swal({
        title: 'Cargando Calificaciones',
        icon: 'success',
        text: 'Espere un momento...',
        button: false,
        timer: 2000
    }).then((result)=>{
        insertarCalificacionesGrupales();
    });

});

function insertarCalificacionesGrupales(){
    var NuevasCal = [];
    var BloqueCal = [];
    //console.log("Funcionando");
    var idCarrera = $("#carrerasCalificaciones").val()
    var idGeneracion = $("#generacionesCalificaciones").val()
    var idCicloPre = $("#cicloCalificaciones").val()
    var idMateria = $("#calificacionMat").val()
    //var idCarrera = $("#carrerasCalificaciones").val()
    var Calenbase = null;
    
    tCaficaciones.cells().every((ix, g)=>{
        if(g == 1){
            nodelm = tCaficaciones.cell({row:ix, column:g}).node();
            CalNueva = $(nodelm).find('input').val();
            idCompuesto =$(nodelm).find('input').prop("id");
            idCalif =$(nodelm).find('input').prop("name");

            let indice = idCompuesto.indexOf("n");
            let idAlu = idCompuesto.substring(indice+1,idCompuesto.length);

            BloqueCal.push(idAlu,CalNueva,idCalif);
            NuevasCal.push(BloqueCal);
            BloqueCal = [];
           
        }            
    })
    var Calenbase = VerificarCalificacionBase(idCarrera,idGeneracion,idCicloPre,idMateria,NuevasCal);
    //console.log(Calenbase);
    return (true);
}

function VerificarCalificacionBase(CalificacionNueva,idGeneracion,idCiclo,idMateria,entradas){
    //$("#modalModificarCalificacion").modal('show');
  $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'VerificarExistenciaCalificacion',
            idGen: idGeneracion,
            idCic: idCiclo,
            idMat: idMateria,
            arrEntr: entradas
        },
        success: function(data){
            try{
                //console.log("consultando");
                pr = JSON.parse(data)
                if($("#table-alumnos-calificaciones").DataTable().ajax.url() !== null){
                    $("#table-alumnos-calificaciones").DataTable().ajax.reload(null, false);
                }
                //console.log(pr);
            }catch(e){
                console.log(e)
                console.log(data)
            }
            
        }
    })
}


/*function cambiarCalificacion(idCalificacion){
    $("#modalModificarCalificacion").modal('show');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: {
            action: 'obtenerCalificacion',
            idCalif: idCalificacion
        },
        success: function(data){
            try{
                pr = JSON.parse(data)
                //console.log(pr)
                $("#calificacionAlumno").val(pr.calificacion);
                
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}*/

function cambiarCalificacion(idCalificacion, idAlumno,calificacion){
    //calificacionAlumno = $(calificacion).parent().find('input')[0].val();
    //csvFile = $(archivo).parent().find('input')[0].files[0];
    //var calificacionAlumno = $("#calificacion"+idAlumno).val();
    var calificacionAlumno = calificacion;
    if(calificacionAlumno == ''){
        calificacionAlumno = 'Sin Calificación';
    }
    fData = new FormData();
    fData.append('action', 'cambiarCalificacion');
    fData.append('califAlum', calificacionAlumno);
    fData.append('idCalif', idCalificacion);

    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == ''){
                swal({
                    title: 'Sin documento',
                    icon: 'info',
                    text: 'Adjunta el archivo correspondiente.',
                    button: false,
                    timer: 2200,
                });
                $("#spinnerDocUDC"+documento).hide();
                $("#btnDocUDC"+documento).prop('disabled', false);
            }
            try{
                pr = JSON.parse(data)
                //console.log(pr.data.documento)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Actualizado Correctamente',
                        icon: 'info',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2200
                    }).then((result)=>{
                        //$("#formDocumentacionAdminUDC")[0].reset();
                        if($("#table-alumnos-calificacionesNoAcre").DataTable().ajax.url() !== null){
                            $("#table-alumnos-calificacionesNoAcre").DataTable().ajax.reload();
                        }
                        if($("#table-alumnos-calificaciones").DataTable().ajax.url() !== null){
                            $("#table-alumnos-calificaciones").DataTable().ajax.reload(null, false);
                        }
                    })
                }
            }catch(e){
                //console.log(e)
                //console.log(data)
            }
        }
    });
}

$("#ocultarCalificaciones").on('click', function(){
    $("#modalTablaCalificaciones").modal('hide');
})


let nodo_act = false;
function validarModificarCalificacion(idCalificacion, idAlumno, nodo){
    Swal.fire({
        text: '¿Estas seguro de cambiar la calificación?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            var Calificacion = $("#calificacion"+idAlumno).val();
            cambiarCalificacion(idCalificacion, idAlumno,Calificacion);
            nodo_act = $(nodo).parent().parent();
            $($(nodo).parent().parent().children()[1]).find('input').attr('value', $("#calificacion"+idAlumno).val());
        }
    })
}


function checkCalificaciones(idCalificacion){
    var input = $("#calificacion"+idCalificacion);
    //console.log(input);
    var string = $(input).val().trim().replace(' ', '').toLocaleLowerCase();
    //console.log(string);
    if(string == 's'){
        $(input).val('Sin Calificación');
    }else if(string == 'n'){
        $(input).val('N/C');
    }else{
        if(string!=''){
            var val = Math.abs(parseInt($(input).val() || 0) || 0);
            $(input).val(val > 10 ? 10 : val);
        }
    }
}

function validarInsertarCalificacionNoAcre(idalumno){
    Swal.fire({
        text: '¿Estas seguro de cambiar la calificación?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            insertarCalificacionNoAcre(idalumno);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function validarInsertarCalificacion(idalumno){
    Swal.fire({
        text: '¿Estas seguro de cambiar la calificación?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            var calificacionAlumno = $("#calificacion"+idalumno).val();
            insertarCalificacion(idalumno,calificacionAlumno);
        }else{
            swal("Cancelado Correctamente");

        }
    })
}

function insertarCalificacion(idalumno, calificacion){
    var calificacionAlumno = $("#calificacion"+idalumno).val();
    var calificacionAlumno = calificacion;
    var calificacionGeneracion = $("#calificacionGen").val();
    var calificacionMateria = $("#calificacionMat").val();
   

    if(calificacionGeneracion == ''){
        calificacionGeneracion = $("#generacionesCalificaciones_noAcre").val();
       
    }
    if(calificacionMateria == ''){
        calificacionMateria =  $("#MateriaCalificacion_noAcre").val();
    }
    
    fData = new FormData();
    fData.append('action', 'insertarCalificacion');
    fData.append('califAlum', calificacionAlumno);
    fData.append('idAlum', idalumno);
    fData.append('idGen', calificacionGeneracion);
    fData.append('idMat', calificacionMateria);
    fData.append('idUsuarioRegistro', usrInfo.idTipo_Persona);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == ''){
                swal({
                    title: 'Sin documento',
                    icon: 'info',
                    text: 'Adjunta el archivo correspondiente.',
                    button: false,
                    timer: 2200,
                });
                $("#spinnerDocUDC"+documento).hide();
                $("#btnDocUDC"+documento).prop('disabled', false);
            }
            try{
                pr = JSON.parse(data)
                //console.log(pr.data.documento)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Actualizado Correctamente',
                        icon: 'info',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1000,
                    }).then((result)=>{
                        //$("#formDocumentacionAdminUDC")[0].reset();
                    $("#table-alumnos-calificaciones").DataTable().ajax.reload(null,false);
                        //$("#table-alumnos-calificacionesNoAcre").DataTable().ajax.reload(null,false);
                        
                        //table-alumnos-calificacionesNoAcre
                    })
                }
            }catch(e){
                //console.log(e)
                //console.log(data)
            }
        }
    });
}


function insertarCalificacionNoAcre(idalumno){
    var calificacionAlumno = $("#calificacion"+idalumno).val();
    var calificacionGeneracion = $("#generacionesCalificaciones_noAcre").val();
    var calificacionMateria = $("#MateriaCalificacion_noAcre").val();

    /*if(calificacionGeneracion == ''){
        calificacionGeneracion = $("#generacionesCalificaciones_noAcre").val();
       
    }
    if(calificacionMateria == ''){
        calificacionMateria =  $("#MateriaCalificacion_noAcre").val();
    }*/
    
    fData = new FormData();
    fData.append('action', 'insertarCalificacion');
    fData.append('califAlum', calificacionAlumno);
    fData.append('idAlum', idalumno);
    fData.append('idGen', calificacionGeneracion);
    fData.append('idMat', calificacionMateria);
    fData.append('idUsuarioRegistro', usrInfo.idTipo_Persona);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/controlescolarControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == ''){
                swal({
                    title: 'Sin documento',
                    icon: 'info',
                    text: 'Adjunta el archivo correspondiente.',
                    button: false,
                    timer: 2200,
                });
                $("#spinnerDocUDC"+documento).hide();
                $("#btnDocUDC"+documento).prop('disabled', false);
            }
            try{
                pr = JSON.parse(data)
                //console.log(pr.data.documento)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Actualizado Correctamente',
                        icon: 'info',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 1000,
                    }).then((result)=>{
                        //$("#formDocumentacionAdminUDC")[0].reset();
						$("#table-alumnos-calificacionesNoAcre").DataTable().ajax.reload(null,false);
                        //$("#table-alumnos-calificacionesNoAcre").DataTable().ajax.reload(null,false);
                        
                        //table-alumnos-calificacionesNoAcre
                    })
                }
            }catch(e){
                //console.log(e)
                //console.log(data)
            }
        }
    });
}

/*
var string = $(this).val().trim().replace(' ', '').toLocaleLowerCase();
if(string == 's'){
    $(this).val('Sin Calificación');
}else if(string == 'n'){
    $(this).val('N/C');
}else{
    var val = Math.abs(parseInt($(this).val() || 0) || 0);
    $(this).val(val > 10 ? 10 : val);
}*/
$("#check_extraordinario").prop('checked', false);
$("#check_extraordinario").on('change', function(){
    var checkin = $(this).is(':checked');
    if(checkin){
        $("*costoPesos").parent().removeClass('d-none');
        $("*costoPesos").attr('disabled', false);
        $("*costoUsd").parent().removeClass('d-none');
        $("*costoUsd").attr('disabled', false)
    }else{
        $("*costoPesos").parent().addClass('d-none');
        $("*costoPesos").attr('disabled', true)
        $("*costoUsd").parent().addClass('d-none');
        $("*costoUsd").attr('disabled', true)
    }
});

$("#check_extraordinarioBanco").prop('checked', false);
$("#check_extraordinarioBanco").on('change', function(){
    var checkinB = $(this).is(':checked');
    if(checkinB){
        $("*costoPesosBanco").parent().removeClass('d-none');
        $("*costoPesosBanco").attr('disabled', false);
        $("*costoUsdBanco").parent().removeClass('d-none');
        $("*costoUsdBanco").attr('disabled', false)
    }else{
        $("*costoPesosBanco").parent().addClass('d-none');
        $("*costoPesosBanco").attr('disabled', true)
        $("*costoUsdBanco").parent().addClass('d-none');
        $("*costoUsdBanco").attr('disabled', true)
    }
});





$("#check_multiple_aplicacion").prop('checked', false);
$("#check_multiple_aplicacion").on('change', function(){
    var check = $(this).is(':checked');
    if(check){
        $("#inp_porcentaje_aprobar").parent().removeClass('d-none');
        $("#inp_porcentaje_aprobar").attr('disabled', false)
    }else{
        $("#inp_porcentaje_aprobar").parent().addClass('d-none');
        $("#inp_porcentaje_aprobar").attr('disabled', true)
    }
});

$("#inp_porcentaje_aprobar").on('change',function(){
    var porcentaje = $(this).val();
    if(porcentaje > 100){
        porcentaje = 100;
    }
    if(porcentaje < 0){
        porcentaje = 0;
    }
    $(this).val(parseInt(porcentaje));
})

// script crear nuevo examen
$("#check_multiple_aplicacion_i").prop('checked', false);

$("#check_multiple_aplicacion_i").on('change', function(){
    var check = $(this).is(':checked');
    if(check){
        $("#inp_porcentaje_aprobar_i").parent().removeClass('d-none');
        $("#inp_porcentaje_aprobar_i").attr('disabled', false)
    }else{
        $("#inp_porcentaje_aprobar_i").parent().addClass('d-none');
        $("#inp_porcentaje_aprobar_i").attr('disabled', true)
    }
});

$("#check_multiple_aplicacion_iBanco").prop('checked', false);

$("#check_multiple_aplicacion_iBanco").on('change', function(){
    var check = $(this).is(':checked');
    if(check){
        $("#inp_porcentaje_aprobar_iBanco").parent().removeClass('d-none');
        $("#inp_porcentaje_aprobar_iBanco").attr('disabled', false)
    }else{
        $("#inp_porcentaje_aprobar_iBanco").parent().addClass('d-none');
        $("#inp_porcentaje_aprobar_iBanco").attr('disabled', true)
    }
});

$("#inp_porcentaje_aprobar_iBanco").on('change',function(){
    var porcentaje2 = $(this).val();
    if(porcentaje2 > 100){
        porcentaje2 = 100;
    }
    if(porcentaje2 < 0){
        porcentaje2 = 0;
    }
    $(this).val(parseInt(porcentaje2));
});


$("#inp_porcentaje_aprobar_i").on('change',function(){
    var porcentaje = $(this).val();
    if(porcentaje > 100){
        porcentaje = 100;
    }
    if(porcentaje < 0){
        porcentaje = 0;
    }
    $(this).val(parseInt(porcentaje));
})

// script crear nuevo examen de examen anterior
$("#check_retomar_preguntas").prop('checked', false);

$("#check_retomar_preguntas").on('change', function(){
    var check = $(this).is(':checked');
    if(check){     
        $("#num_preguntas_retomar").attr('disabled', false);
        $("#num_preguntas_retomar").parent().removeClass('d-none');
        $("#id_examen_pasado").parent().removeClass('d-none');
        $("#id_examen_pasado").attr('disabled', false)

        $("#datatable-tablaPreguntas2").parent().removeClass('d-none');
        $("#datatable-tablaPreguntas2").attr('disabled', false)
    }else{
        $("#num_preguntas_retomar").attr('disabled', true);
        
        $("#id_examen_pasado").parent().addClass('d-none');
        $("#id_examen_pasado").attr('disabled', true)

        $("#datatable-tablaPreguntas2").parent().addClass('d-none');
        $("#datatable-tablaPreguntas2").attr('disabled', true)
    }
})

// script crear nuevo examen de examen anterior
/*$("#id_examen_pasado").prop('selected', false);

$("#id_examen_pasado").on('change', function(){
    var check = $(this).val();
    console.log(check);
    if(check){

        $("#datatable-tablaPreguntas2").parent().removeClass('d-none');
        $("#datatable-tablaPreguntas2").attr('disabled', false)

    }else{

        $("#datatable-tablaPreguntas2").parent().addClass('d-none');
        $("#datatable-tablaPreguntas2").attr('disabled', true)
    }
})*/

//Vista de tabla despues del select
/*$("#check_retomar_preguntas").prop('checked', false);
$("#check_retomar_preguntas").on('change', function(){
    var check = $(this).val();
    console.log(check);
    if(check){

        

    }else{


    }
})*/
//-------
// script editar examen de examen anterior
$("#check_retomar_preguntas_e").prop('checked', false);

$("#check_retomar_preguntas_e").on('change', function(){
    var check = $(this).is(':checked');
    if(check){
        $("#id_examen_pasado_e").parent().removeClass('d-none');
        $("#id_examen_pasado_e").attr('disabled', false);
        $("#id_examen_pasado_e").prop('required',true);

        $("#num_preguntas_retomar_e").attr('disabled', false);
        $("#datatable-tablaPreguntas3").parent().removeClass('d-none');
        $("#datatable-tablaPreguntas3").attr('disabled', false)
    }else{
        $("#id_examen_pasado_e").parent().addClass('d-none');
        $("#id_examen_pasado_e").attr('disabled', true);
        $("#id_examen_pasado_e").prop('required',false);

        $("#num_preguntas_retomar_e").attr('disabled', true);
        $("#datatable-tablaPreguntas3").parent().addClass('d-none');
        $("#datatable-tablaPreguntas3").attr('disabled', true)
    }
});

// script editar nuevo examen de examen anterior
/*$("#id_examen_pasado_e").prop('selected', false);

$("#id_examen_pasado_e").on('change', function(){
    var check = $(this).val();
    console.log(check);
    if(check){

        

    }else{

        
    }
})*/
/*$("#id_examen_pasado").on('change',function(){
    var id_examenPas = $(this).val();
    if(id_examenPas > 100){
        id_examenPas = 100;
    }
    if(id_examenPas < 0){
        id_examenPas = 0;
    }
    $(this).val(parseInt(id_examenPas));

    
})*/


