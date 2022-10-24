$(document).ready(function () {
    initGeneraciones();
});

function initGeneraciones() {
    
    $("#btn-crear-generacion").on("click",function (e) {
        getCarreras();
        function getCarreras(){
            var Data = {
            action: "obtenerCarreras"
            }
            $.ajax({
                url: '../assets/data/Controller/planpagos/generacionesControl.php',
                type: 'POST',
                data: Data,
                dataType: 'JSON',
                success : function(data){
                    $("#selectCarrer").html('<option value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key,registro){
                        $("#selectCarrer").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                        $("#selectCarrer").selectpicker('refresh');
                    });
                },
                error: function(xhr){
                    if(xhr.responseText == 'no_session'){
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
            });
        }

    });

    $('#tabgeneraciones').click(function (e) {
        tGeneraciones = $("#table-generaciones").DataTable({
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
                url: '../assets/data/Controller/planpagos/generacionesControl.php',
                type: 'POST',
                data: {action: 'obtenerGeneraciones'},
                dataType: "JSON",
                error: function(e){
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
            'order':[
                [0,'asc']
            ]
        });
        
    });

    $("#formModGeneracion").on("submit", function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'modificarGeneracion');
        fData.append('actualizado_por', usrInfo.idAcceso);
        $.ajax({
            url: '../assets/data/Controller/planpagos/generacionesControl.php',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
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
                    pr = JSON.parse(data)
                    if(pr.estatus == 'ok'){
                        swal({
                            title: 'Modificado Correctamente',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formModCarrera")[0].reset();
                            tGeneraciones.ajax.reload(); 
                            $("#modalModGen").modal("hide");
                        })
                    }
                    /*
                    if(data == 1){
                        Swal.fire({
                            title: 'No se puede repetir el nombre clave',
                            confirmButtonColor: '#AA262C',
                        }).then((result)=>{
                        $(".devMessC").show();
                        })
                    }*/
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            }
        });
    })
}

//$("#selectCarrer").selectpicker();
$("#formGeneracion").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'crearGeneracion');
    fdata.append('creador_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/planpagos/generacionesControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
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
                if (pr.estatus == 'ok') {
                    swal({
                        title: 'Generación creada con éxito',
                        icon: 'success',
                        text: '',
                        confirmButtonText: "Aceptar",
                        timer: 2500,
                    }).then((result)=>{
                        $("#formGeneracion")[0].reset();
                        $("#modalGeneracion").modal("hide");
                        tGeneraciones.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
})


function validarEliminarGeneracion(id){
    Swal.fire({
        text: '¿Estas seguro de eliminarlo?',
        type:'info',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            eliminarGeneracion(id);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function eliminarGeneracion(id){
    Data = {
        action: "eliminarGeneracion",
        idEliminar: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/generacionesControl.php',
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
                        tGeneraciones.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });

}

function buscarGeneracion(id){
    getCarrerasMod(id);
    $("#formModGeneracion")[0].reset();
    Data = {
        action: 'buscarGeneracion',
        idEditar: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/generacionesControl.php',
        type: 'POST',
        data: Data,
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
                setTimeout(() => {    
                    buscarCarrerasMod(id);
                }, 300);
                $("#modalModGen").modal('show');
                pr = JSON.parse(data);
                //$("#modselectPago").val(pr.data[0].IDPlanPago);
                $("#modnombreG").val(pr.data[0].nombre);
                $("#modfechainicio").val(pr.data[0].fecharegistro);
                $("#modfechafin").val(pr.data[0].fechafinal);
                $("#idG").val(pr.data[0].idGeneracion);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}


$("#ocultar3").on('click', function(){
    $("#formGeneracion")[0].reset();
    $("#modalGeneracion").modal("hide");
})

$("#ocultar4").on('click', function(){
    $("#formModGeneracion")[0].reset();
    $("#modalModGen").modal("hide");
})

//modselectCarrer
function getCarrerasMod(id){
    var Data = {
    action: "obtenerCarrerasMod",
    id: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/generacionesControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#modselectCarrer").html('<option value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#modselectCarrer").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#modselectCarrer").selectpicker('refresh');
            });
        }
    });
}

function buscarCarrerasMod(id){
    Data = {
        action: "buscarCarrerasMod",
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/generacionesControl.php',
        type: 'POST',
        data: Data,
        success :function(data){
            try{
                pr = JSON.parse(data);
                var select=[];
                for(x in pr){
                    select[x]=pr[x].idCarrera;
                }
                $("#modselectCarrer").selectpicker('val',select)
                $('.modselectCarrer').selectpicker('refresh');
                $("#selectAnterior").val(select);

            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function recibirCarreras(id){
    Data = {
        action: "buscarCarrerasMod",
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/generacionesControl.php',
        type: 'POST',
        data: Data,
        success :function(data){
            try{
                pr = JSON.parse(data);
                var select=[];
                for(x in pr){
                    select[x]=pr[x].idCarrera;
                }
                $("#modselectCarrer").selectpicker('val',select)
                $('.modselectCarrer').selectpicker('refresh');
                $("#selectAnterior").val(select);

            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

/*
$("#modselectCarrer").selectpicker('val',[14])*/