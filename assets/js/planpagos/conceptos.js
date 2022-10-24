$(document).ready(function () {
    initconceptos();
    getInstitucionesConceptoMod();
});

    function initconceptos() {
        $('#boton-crear-concepto').click(function () {
            $("#formCrearConcepto")[0].reset();
            
            getInstitucionesConcepto();
            function getInstitucionesConcepto(){
                $("#selectInstitucionConcepto").empty();
                var Data = {
                    action: "obtenerInstituciones"
                }
                $.ajax({
                    url: '../assets/data/Controller/planpagos/conceptosControl.php',
                    type: 'POST',
                    data: Data,
                    dataType: 'JSON',
                    success : function(data){
                        $("#selectInstitucionConcepto").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                        $.each(data, function(key,registro){
                            $("#selectInstitucionConcepto").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
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

        $('#tabconceptos').click(function (e) {

            tablaconceptos = $("#table-conceptos").DataTable({
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
                    url: '../assets/data/Controller/planpagos/conceptosControl.php',
                    type: 'POST',
                    data: {action: 'obtenerConceptos'},
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
                'order':[
                    [0,'asc']
                ]
            });
            
        });

    }

    $("#formCrearConcepto").on('submit', function(e){
        e.preventDefault();
        fdata = new FormData(this)
        fdata.append('action', 'crearConcepto');
        fdata.append('creador_por', usrInfo.idAcceso);
        $.ajax({
            url: '../assets/data/Controller/planpagos/conceptosControl.php',
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
                if(data == 'numero_invalido'){
                    swal({
                        title: 'No se admite valor 0',
                        icon: 'info',
                        text: 'Cambia el precio por uno mayor o igual a 0.01',
                        button: false,
                        timer: 3400,
                    });
                }
                try{
                    var pr = JSON.parse(data)
                    if (pr.estatus == 'ok') {
                        swal({
                            title: 'Concepto creado con éxito',
                            icon: 'success',
                            text: 'Espere un momento...',
                            button: false,
                            timer: 2500,
                        }).then((result)=>{
                            $("#formCrearConcepto")[0].reset();
                            $("#modal-crear-concepto").modal("hide");
                            tablaconceptos.ajax.reload();
                        })
                    }else{
                        swal({
                            icon:'info',
                            text:pr.info
                        });
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            }
        });
    })

    function editarconcepto(idconcepto){
        $.ajax({
            url: '../assets/data/Controller/planpagos/conceptosControl.php',
            type: "POST",
            data: {action: 'obtenerConcepto', idconcepto: idconcepto},
            beforeSend : function(){
                $("#loader").css("display", "block")
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
                    data = JSON.parse(data);
                    $('#editarSelectInstitucionConceptos').val(data.institucion);
                    $('#editarNombreConcepto').val(data.concepto);
                    $('#editarPrecio').val(data.precio);
                    $('#editarPrecio_usd').val(data.precio_usd);
                    $('#editarParcialidades').val(data.parcialidades);
                    $("#editarDescripcion").val(data.descripcion);
                    $("#idC").val(data.id_concepto);
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            },
            error: function(){
            },
            complete: function(){
                $("#loader").css("display", "none")
            }
        });
    }

    function validareliminarconcepto(id){
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
                eliminarConcepto(id);
            }else{
                swal("Cancelado Correctamente");
            }
        })
    }

    function eliminarConcepto(id){
        Data = {
            action: "eliminarConcepto",
            idEliminar: id
        }
        $.ajax({
            url: '../assets/data/Controller/planpagos/conceptosControl.php',
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
                            tablaconceptos.ajax.reload();
                        })
                    }
                }catch(e){
                    console.log(e);
                    console.log(data);
                }
            }
        });
    
    }

    $("#ocultarConp").on('click', function(){
        $("#formCrearConcepto")[0].reset();
        $("#modal-crear-concepto").modal('hide');
    })

    $("#ocultarConpEdit").on('click',function(){
        $("#form-editarconcepto")[0].reset();
        $("#modal-editar-concepto").modal('hide');
    })

    function check(e){
        tecla = (document.all) ? e.keycode : e.which;

        if(tecla == 8){
            return true;
        }
        patron = /[0-9*]/;
        tecla__final = String.fromCharCode(tecla);
        return patron.test(tecla__final);
    }

    $("#form-editarconcepto").on("submit", function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'modificarConcepto');
        fData.append('modificado_por', usrInfo.idAcceso);
        $.ajax({
            url: '../assets/data/Controller/planpagos/conceptosControl.php',
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
                if(data == 'numero_invalido'){
                    swal({
                        title: 'No se admite valor 0',
                        icon: 'info',
                        text: 'Cambia el precio por uno mayor o igual a 0.01',
                        button: false,
                        timer: 3400,
                    });
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
                            $("#form-editarconcepto")[0].reset();
                            tablaconceptos.ajax.reload(); 
                            $("#modal-editar-concepto").modal("hide");
                        })
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            }
        });
    })


    function getInstitucionesConceptoMod(){
        $("#editarSelectInstitucionConceptos").empty();
        var Data = {
            action: "obtenerInstituciones"
        }
        $.ajax({
            url: '../assets/data/Controller/planpagos/conceptosControl.php',
            type: 'POST',
            data: Data,
            dataType: 'JSON',
            success : function(data){
                $("#editarSelectInstitucionConceptos").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data, function(key,registro){
                    $("#editarSelectInstitucionConceptos").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
                });
            }
        });
    }
