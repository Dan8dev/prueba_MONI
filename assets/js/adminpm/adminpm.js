$(document).ready(()=>{
    tablaMedicos();
})

function tablaMedicos(){
    tMedicos = $("#datatable-tablaMedicos").DataTable({
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
    }, {
        extend: "pdf"
    }, {
        extend: "print"
    }],
    "ajax": {
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
        type: 'POST',
        data: {action: 'consultarMedicos'},
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
}//FIN tablaMedicos

$("#formAgregarMedico").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'agregarMedico');
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                        text: 'Password por default: abc',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formAgregarMedico")[0].reset();
                        tablaMedicos();
                        $("#modalAgregarMedico").modal("hide");
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
})//Fin AgregarMedico

//editarMedico
$("#formMostrarMedico").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'editarMedico');
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                        $("#formMostrarMedico")[0].reset();
                        tablaMedicos();
                        $("#modalMostrarMedico").modal("hide");
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
})//Fin editarMedico

function mostrarMedico( id ){

    document.getElementById('btnEdit').disabled = true;
    $("#formMostrarMedico")[0].reset();

    Data = {
        action: 'buscarMedico',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                $("#modalMostrarMedico").modal("show");
                pr = JSON.parse(data);
                $("#apellidop_e").val(pr.data[0].apellidop);
                $("#apellidom_e").val(pr.data[0].apellidom);
                $("#nombres_e").val(pr.data[0].nombre);
                $("#email_e").val(pr.data[0].correo);
                $("#idMedico").val(id);
                tipo1 = document.getElementById( 'rol_e1' );
                tipo2 = document.getElementById( 'rol_e2' );
                
                if( pr.data[0].tipo == "M" ){
                    tipo1.checked = true;                
                    tipo2.checked = false;
                }else{
                    tipo1.checked = false;                
                    tipo2.checked = true;
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


}//fin mostrarMedico

function validarDesactivarMedico(idMedico, estado){
    Swal.fire({
        text: '¿Confirma que desea activar/desactivar al médico?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            desactivarMedico(idMedico, estado);
        }/*else{
            swal("Guardado Correctamente");
        }*/
    })
}//fin validarDesactivarMedico

function desactivarMedico(idMedico, estado){
    Data = {
        action: "desactivarMedico",
        idDesactivar: idMedico,
        vEstado: estado
    }

    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                            tablaMedicos();
                            //console.log( "Ok" );
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

function tablaHospitales(){
    tMedicos = $("#datatable-tablaHospitales").DataTable({
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
    }, {
        extend: "pdf"
    }, {
        extend: "print"
    }],
    "ajax": {
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
        type: 'POST',
        data: {action: 'consultarHospitales'},
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
}//FIN tablaHospitales

$("#formAgregarHospital").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'agregarHospital');
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                        $("#formAgregarHospital")[0].reset();
                        tablaHospitales();
                        $("#modalAgregarHospital").modal("hide");
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
})//Fin AgregarHospital

function mostrarHospital( id ){

    document.getElementById('btnEdit2').disabled = true;
    $("#formMostrarHospital")[0].reset();

    Data = {
        action: 'buscarHospital',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                $("#modalMostrarHospital").modal("show");
                pr = JSON.parse(data);
                $("#nombre_e").val(pr.data[0].nombre);
                $("#direccion_e").val(pr.data[0].direccion);
                $("#idHospital").val(id);
                
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


}//fin mostrarHospital

//editarHospital
$("#formMostrarHospital").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'editarHospital');
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                        $("#formMostrarHospital")[0].reset();
                        tablaHospitales();
                        $("#modalMostrarHospital").modal("hide");
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
})//Fin editarHospital

function validarDesactivarHospital(idHospital, estado){
    Swal.fire({
        text: '¿Confirma que desea activar/desactivar al Hospital/Clínica?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            desactivarHospital(idHospital, estado);
        }/*else{
            swal("Guardado Correctamente");
        }*/
    })
}//fin validarDesactivarHospital

function desactivarHospital(idHospital, estado){

    Data = {
        action: "desactivarHospital",
        idDesactivar: idHospital,
        vEstado: estado
    }

    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                            tablaHospitales();
                            //console.log( "Ok" );
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
}//finDesactivarHospital

function tablaProcedimientos(){
    tMedicos = $("#datatable-tablaProcedimientos").DataTable({
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
    }, {
        extend: "pdf"
    }, {
        extend: "print"
    }],
    "ajax": {
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
        type: 'POST',
        data: {action: 'consultarProcedimientos'},
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
}//FIN tablaProcedimientos

function mostrarCarreras( selectn ){

    $("#formAgregarProcedimiento")[0].reset();
    $("#formMostrarProcedimiento")[0].reset();    
    sn = document.getElementById( selectn );
    idCarrera_actual = document.getElementById( "idCarrera_e" );

    Data = {
        action: 'buscarCarreras',
    }
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                //s = document.querySelector(sn);                
                $('#listacarreras') .empty();
                $('#listacarreras_e') .empty();
                
                for( i = 0; i < pr['aaData'].length; i++ ){
                    option = document.createElement('option');
                    option.text = pr['aaData'][i][0];
                    option.value = pr['aaData'][i][1];
                    if( pr['aaData'][i][1] == idCarrera_actual.value )
                        option.selected = 'selected';
                    sn.appendChild(option);
                }//Fin for

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

$("#formAgregarProcedimiento").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'agregarProcedimiento');
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                        $("#formAgregarProcedimiento")[0].reset();
                        tablaProcedimientos();
                        $("#modalAgregarProcedimiento").modal("hide");
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
})//Fin AgregarProcedimiento

function validarDesactivarProcedimiento(idProcedimiento, estado){
    Swal.fire({
        text: '¿Confirma que desea activar/desactivar el procedimiento?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            desactivarProcedimiento(idProcedimiento, estado);
        }/*else{
            swal("Guardado Correctamente");
        }*/
    })
}//fin validarDesactivarHospital

function desactivarProcedimiento(idProcedimiento, estado){

    Data = {
        action: "desactivarProcedimiento",
        idDesactivar: idProcedimiento,
        vEstado: estado
    }

    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                            tablaProcedimientos();
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
}//finDesactivarProcedimiento

function mostrarProcedimiento( id ){

    document.getElementById('btnEdit3').disabled = true;
    $("#formMostrarProcedimiento")[0].reset();

    Data = {
        action: 'buscarProcedimiento',
        idBuscar: id
    }
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                $("#modalMostrarProcedimiento").modal("show");
                pr = JSON.parse(data);
                $("#nombre_ep").val(pr.data[0].nombre);
                $("#costo_ep").val(pr.data[0].costo);
                $("#descripcion_ep").val(pr.data[0].descripcion);
                $("#idProcedimiento").val(id);
                $("#idCarrera_e").val(pr.data[0].idCarrera);
                
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


}//fin mostrarProcedimiento

//editarProcedimiento
$("#formMostrarProcedimiento").on('submit',function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'editarProcedimiento');
    $.ajax({
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
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
                        $("#formMostrarProcedimiento")[0].reset();
                        tablaProcedimientos();
                        $("#modalMostrarProcedimiento").modal("hide");
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
})//Fin editarProcedimiento

function tablaExpedientes(){
    tMedicos = $("#datatable-tablaExpedientes").DataTable({
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
    }, {
        extend: "pdf"
    }, {
        extend: "print"
    }],
    "ajax": {
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
        type: 'POST',
        data: {action: 'consultarExpedientes'},
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
}//FIN tablaMedicos

var m = true;
var ccheck = 5;
function marcarReportes(){
    if( m ){ m = false; ccheck = 0; }else { m = true; ccheck = 5; }
    for( i = 1; i <= 6; i++ )
        document.getElementById( "rp"+i ).checked = m;
    
    if( ccheck <= 0 )
        document.getElementById( "btnreporte" ).disabled = true;
    else 
        document.getElementById( "btnreporte" ).disabled = false;
}

function sumarCheck( id ){
    if( document.getElementById( "rp"+id ).checked == true ) ccheck++;
    else ccheck--; 

    if( ccheck <= 0 )
        document.getElementById( "btnreporte" ).disabled = true;
    else 
        document.getElementById( "btnreporte" ).disabled = false;
}

function tabladirAlumnos(){
    tdiralumnos = $("#datatable-tabladiralumnos").DataTable({
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
    }, {
        extend: "pdf"
    }, {
        extend: "print"
    }],
    "ajax": {
        url: '../assets/data/Controller/adminpm/adminpmControl.php',
        type: 'POST',
        data: {action: 'dirAlumnos'},
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
}//FIN tabladirAlumnos

$("#diralumnos-tab").on('click', function(){
    $("#diralumnos").fadeIn('fast');
    $("#hospitales").fadeOut('fast');
    $("#medicos").fadeOut('fast');
    $("#procedimientos").fadeOut('fast');
    $("#expedientes").fadeOut('fast');
    $("#estadisticas").fadeOut('fast');
    tabladirAlumnos();
})

$("#hospitales-tab").on('click', function(){
    $("#hospitales").fadeIn('fast');
    $("#medicos").fadeOut('fast');
    $("#procedimientos").fadeOut('fast');
    $("#expedientes").fadeOut('fast');
    $("#estadisticas").fadeOut('fast');
    $("#diralumnos").fadeOut('fast');
    tablaHospitales();
})

$("#medicos-tab").on('click', function(){
    $("#medicos").fadeIn('fast');
    $("#hospitales").fadeOut('fast');
    $("#procedimientos").fadeOut('fast');
    $("#expedientes").fadeOut('fast');
    $("#estadisticas").fadeOut('fast');
    $("#diralumnos").fadeOut('fast');
    tablaMedicos();
})

$("#procedimientos-tab").on('click', function(){
    $("#medicos").fadeOut('fast');
    $("#hospitales").fadeOut('fast');
    $("#procedimientos").fadeIn('fast');
    $("#expedientes").fadeOut('fast');
    $("#estadisticas").fadeOut('fast');
    $("#diralumnos").fadeOut('fast');
    tablaProcedimientos();
})

$("#expedientes-tab").on('click', function(){
    $("#expedientes").fadeIn('fast');
    $("#hospitales").fadeOut('fast');
    $("#medicos").fadeOut('fast');
    $("#procedimientos").fadeOut('fast');
    $("#estadisticas").fadeOut('fast');
    $("#diralumnos").fadeOut('fast');
    tablaExpedientes();
})

$("#estadisticas-tab").on('click', function(){
    $("#hospitales").fadeOut('fast');
    $("#medicos").fadeOut('fast');
    $("#procedimientos").fadeOut('fast');
    $("#expedientes").fadeOut('fast');
    $("#estadisticas").fadeIn('fast');
    $("#diralumnos").fadeOut('fast');
})