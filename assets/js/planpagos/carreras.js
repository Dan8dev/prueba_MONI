$(document).ready(function () {
    //initCarreras();
    carrerasPlan();

    getPaisesModcarreras();
    getInstitucionescarrera();
    getPlantillascarreraMod();
    vDevImagencarrera();
    vDevFondocarrera();
});


    $("#boton-crear-carrera").on("click",function (e) {
        
        obtenerpaisescarreras();
        obtenerinstituciones();
        vImagencarreras();
        vImagenfondo();
        getPlantillascarrera();
        
        $("#crearcarrera")[0].reset();
        $(".clave").hide();
        $("#vImagencarrera").attr('src','');
        $("#vImagencarrera").hide();
        $("#vFondocarrera").attr('src','');
        $("#vFondocarrera").hide();
        $('#crear-carrera').modal("show");

        function obtenerpaisescarreras() {
            $("#select-pais").empty();
            var Data = {
               action: "obtenerpaises"
            }
            $.ajax({
                url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
                type: 'POST',
                data: Data,
                dataType: 'JSON',
                success : function(data){
                    $("#select-pais").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key,registro){
                        $("#select-pais").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
                        //$("#devpaiscarrera").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
                    });
                },
                complete : function(){

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

        function obtenerinstituciones() {
            $("#select-institucion").empty();
            var Data = {
               action: "obtenerinstituciones"
            }
            $.ajax({
                url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
                type: 'POST',
                data: Data,
                dataType: 'JSON',
                success : function(data){
                    $("#select-institucion").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key,registro){
                        $("#select-institucion").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
                    });
                },
                complete : function(){
                    
                }
            });
        }

    });

    function carrerasPlan(){

        tCarrera = $("#table-carreras").DataTable({
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
                title:'Carreras_'+new Date().toLocaleDateString().replace(/\//g, '-')
            /*}, {
                extend: "pdf"
            }, {
                extend: "print"*/
            }],
            "ajax": {
                url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
                type: 'POST',
                data: {action: 'obtenerCarreas'},
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
        
    }

    $('#select-pais').change(function() {
        $("#select-estado").empty();
        var Data = {
            action: "obtenerestados",
            idpais: $('#select-pais').val()
         }
        $.ajax({
          url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
          type: 'POST',
          data: Data,
          dataType: 'JSON',
          success: function(data) {
            $("#select-estado").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data, function(key,registro){
                    $("#select-estado").prop('disabled', false);
                    $("#select-estado").append('<option value='+registro.IDEstado+'>'+registro.Estado+'</option>');
                });
                if(data == ''){
                    swal({
                        title: 'País sin estados',
                        icon: 'info',
                        text: 'Selecciona otro país, si es el caso.',
                        button: false,
                        timer: 3000,
                    });
                    $("#select-estado").prop('disabled', true);
                }
          }
        });      
       });

       function vImagencarreras(){
        const $seleccionArchivos = document.querySelector("#imagencarrera"),
            $imagenPrevisualizacion = document.querySelector("#vImagencarrera");
            //Escuchar cuando cambie
            $seleccionArchivos.addEventListener("change", () =>{
                const archivos = $seleccionArchivos.files;
                //si no hay archivos salimos de la función y quitamos la imagen
                if(!archivos || !archivos.length){
                    $imagenPrevisualizacion.src = "";
                    $("#vImagencarrera").hide();  
                    return;
                }
                const primerArchivo = archivos[0];
                const objetoURL = URL.createObjectURL(primerArchivo);
                
                $imagenPrevisualizacion.src = objetoURL;
                $("#vImagencarrera").show();     
            });
    }

    function vImagenfondo(){
        const $seleccionArchivos = document.querySelector("#imgFondocarrera"),
            $imagenPrevisualizacion = document.querySelector("#vFondocarrera");
            //Escuchar cuando cambie
            $seleccionArchivos.addEventListener("change", () =>{
                const archivos = $seleccionArchivos.files;
                //si no hay archivos salimos de la función y quitamos la imagen
                if(!archivos || !archivos.length){
                    $imagenPrevisualizacion.src = "";
                    $("#vFondocarrera").hide();  
                    return;
                }
                const primerArchivo = archivos[0];
                const objetoURL = URL.createObjectURL(primerArchivo);
                
                $imagenPrevisualizacion.src = objetoURL;
                $("#vFondocarrera").show();     
            });
    }

    function getPlantillascarrera(){
        Data = {
            action: "buscarPlantillas"
        }
        $.ajax({
            url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
            type: "POST",
            data: Data,
            dataType: "json",
            success: function(data){
                $("#select-plantilla").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                $.each(data, function(key, registro){
                    if(registro.plantilla_bienvenida !== ''){
                    $("#select-plantilla").append('<option value='+registro.plantilla_bienvenida+'>'+registro.plantilla_bienvenida+'</option>');
                    }
                });
            }
        });
    }

    $("#ocultar").on('click', function(){
        $("#crear-carrera").modal("hide");
        $("#crearcarrera")[0].reset();
        $(".clave").hide();
        $("#vImagencarrera").attr('src','');
        $("#vImagencarrera").hide();
        $("#vFondocarrera").attr('src','');
        $("#vFondocarrera").hide();
    })

    $("#ocultar2").on('click', function(){
        $("#modalModifycarrera").modal("hide");
        $("#devimagencarrera").attr('src','');
        $("#devimagencarrera").hide();
        $("#devfondocarrera").attr('src','');
        $("#devfondocarrera").hide();
        $("#formModCarrera")[0].reset();
    })

    $("#formModCarrera").on("submit", function(e){
        e.preventDefault();
        fData = new FormData(this);
        fData.append('action', 'modificarCarrera');
        //fData.append('creador_por', usrInfo.idAcceso);
        $.ajax({
            url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
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
                if (data == 'no_format'){
                    swal({
                        title: 'Formato no admitido',
                        icon: 'info',
                        text: 'Verifica que tu imagen sea: JPG, JPEG o PNG.',
                        button: false,
                        timer: 3500,
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
                            $("#formModCarrera")[0].reset();
                            $("#devimagencarrera").attr('src','');
                            $("#devimagencarrera").hide();
                            $("#devfondocarrera").attr('src','');
                            $("#devfondocarrera").hide();
                            tCarrera.ajax.reload(); 
                            $("#modalModifycarrera").modal("hide");
                        })
                    }
                    if(data == 1){
                        Swal.fire({
                            title: 'No se puede repetir el nombre clave',
                            confirmButtonColor: '#AA262C',
                        }).then((result)=>{
                        $(".devMessC").show();
                        })
                    }
                }catch(e){
                    console.log(e)
                    console.log(data)
                }
            }
        });
    })



$("#crearcarrera").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'crearcarrera');
    fdata.append('creador_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#loader").css("display", "block")
        },
        success: function(data){
            if (data == 'no_format'){
                swal({
                    title: 'Formato no admitido',
                    icon: 'info',
                    text: 'Verifica que tu imagen sea: JPG, JPEG o PNG.',
                    button: false,
                    timer: 3500,
                });
            }
            try{
                pr = JSON.parse(data)
                if (pr.estatus == 'ok') {
                    swal({
                        title: 'Carrera creada con éxito',
                        icon: 'success',
                        text: '',
                        confirmButtonText: "Aceptar",
                        timer: 2500,
                    }).then((result)=>{
                        $("#crearcarrera")[0].reset();
                        $("#vImagen").attr('src','');
                        $("#vImagen").hide();
                        $("#vFondo").attr('src','');
                        $("#vFondo").hide();
                        $("#crear-carrera").modal("hide");
                        tCarrera.ajax.reload();
                    })
                } else {
                    /*
                    swal({
                        title: "Error",
                        text: "No se pudo crear la carrera",
                        type: "error",
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#2ecc71",
                        closeOnConfirm: false
                    });*/
                    
                }
                if(data == 1){
                    Swal.fire({
                        title: 'No se puede repetir el nombre clave',
                        confirmButtonColor: '#AA262C',
                    }).then((result)=>{
                        $(".clave").show();
                    })       
                }  
                if (data.estatus == 'error'){
                    swal({
                        title: "Error",
                        text: "No se pudo crear la carrera",
                        type: "error",
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#2ecc71",
                        closeOnConfirm: false
                    });
                }
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
})

function getPaisesModcarreras(){
    var Data = {
       action: "obtenerpaises"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#devpaiscarrera").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#devpaiscarrera").append('<option value='+registro.IDPais+'>'+registro.Pais+'</option>');
            });
        },
        complete : function(){

        }
    });
}

$("#devpaiscarrera").on('change', function(){
    $("#devestadocarrera").empty();
    idPais = $("#devpaiscarrera").val();
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
        type: 'POST',
        data: {
                action: "obtenerestados", 
                idpais: idPais
            },
        dataType: 'JSON',
        success : function(data){
            $("#devestadocarrera").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#devestadocarrera").prop('disabled', false);
                $("#devestadocarrera").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
            if(data == ''){
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#devestadocarrera").prop('disabled', true);
            }
        },
        complete : function (){

        }
    });
})


function getInstitucionescarrera(){
    Data = {
        action: "obtenerinstituciones"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success: function(data){
            $("#devinstitucion").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                //$("#idInstitucion").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
                $("#devinstitucion").append('<option value='+registro.id_institucion+'>'+registro.nombre+'</option>');
            });
        },
        complete : function(){

        }
    });
}

function getPlantillascarreraMod(){
    Data = {
        action: "buscarPlantillas"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
        type: "POST",
        data: Data,
        dataType: "json",
        success: function(data){
            $("#devplantillacarrera").html('<option selected="true" disabled="disabled">Seleccione</option>');
            //$("#newPlantilla").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                if(registro.plantilla_bienvenida !== ''){
                $("#devplantillacarrera").append('<option value='+registro.plantilla_bienvenida+'>'+registro.plantilla_bienvenida+'</option>');
                }
            });
        }
    });
}

function getEstadosModcarreras(){
    //$("#devEstadocarrera").empty();
    idpais = $("#devpaiscarrera").val();
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
        type: 'POST',
        data: {
                action: "obtenerestados", 
                idpais: idpais
            },
        dataType: 'JSON',
        success : function(data){
            $("#devestadocarrera").html('<option selected="true" disabled="disabled">Seleccione</option>');
            $.each(data, function(key,registro){
                $("#devestadocarrera").append('<option value ='+registro.IDEstado+'>'+registro.Estado+'</option>');
            });
        },
        complete : function (){

        }
    });
}

function buscarCarrera(id){
    $("#formModCarrera")[0].reset();
    Data = {
        action: 'buscarCarrera',
        idEditar: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
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
                $(".devMessC").hide();
                $("#devimagencarrera").show();
                $("#devfondocarrera").show();
                $("#modalModifycarrera").modal('show');
                pr = JSON.parse(data);
                $("#devinstitucion").val(pr.data[0].idInstitucion);
                $("#devnombrecarrera").val(pr.data[0].nombre);
                $("#devclavecarrera").val(pr.data[0].nombre_clave);
                $("#devtipocarrera").val(pr.data[0].tipo);
                $("#devmodalidadcarrera").val(pr.data[0].modalidadCarrera);
                $("#devduracionmeses").val(pr.data[0].duracionTotal);
                $("#devtipociclo").val(pr.data[0].tipoCiclo);
                $("#devcodigopromocional").val(pr.data[0].codigoPromocional);
                $("#devdireccioncarrera").val(pr.data[0].direccion);
                $("#devpaiscarrera").val(pr.data[0].pais);
                $("#devplantillacarrera").val(pr.data[0].plantilla_bienvenida);

                $("#devimagencarrera").attr('src', '../assets/images/generales/flyers/'+ pr.data[0].imagen);
                $("#devfondocarrera").attr('src', '../assets/images/generales/fondos/'+ pr.data[0].imgFondo);
                getEstadosModcarreras();
                setTimeout(function(){ $("#devestadocarrera").val(pr.data[0].estado)}, 1000);
                $("#devcrearfechainicio").val(pr.data[0].fechainicio);
                $("#devcrearfechafin").val(pr.data[0].fechafin);
                $("#id_carrera").val(pr.data[0].idCarrera);
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
}

function vDevImagencarrera(){
    const $seleccionArchivos = document.querySelector("#newimagencarrera"),
        $imagenPrevisualizacion = document.querySelector("#devimagencarrera");
        //Escuchar cuando cambie
        $seleccionArchivos.addEventListener("change", () =>{
            const archivos = $seleccionArchivos.files;
            //si no hay archivos salimos de la función y quitamos la imagen
            if(!archivos || !archivos.length){
                $imagenPrevisualizacion.src = "";
                //$("#devFondocarrera").hide();
                return;
            }
            const primerArchivo = archivos[0];
            const objetoURL = URL.createObjectURL(primerArchivo);
            
            $imagenPrevisualizacion.src = objetoURL;
            //$("#devFondocarrera").show();
        });
}

function vDevFondocarrera(){
    const $seleccionArchivos = document.querySelector("#newfondocarrera"),
        $imagenPrevisualizacion = document.querySelector("#devfondocarrera");
        //Escuchar cuando cambie
        $seleccionArchivos.addEventListener("change", () =>{
            const archivos = $seleccionArchivos.files;
            //si no hay archivos salimos de la función y quitamos la imagen
            if(!archivos || !archivos.length){
                $imagenPrevisualizacion.src = "";
                //$("#devFondocarrera").hide();
                return;
            }
            const primerArchivo = archivos[0];
            const objetoURL = URL.createObjectURL(primerArchivo);
            
            $imagenPrevisualizacion.src = objetoURL;
            //$("#devFondocarrera").show();
        });
}

function validarEliminar(id){
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
            eliminarCarrera(id);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function eliminarCarrera(id){
    Data = {
        action: "eliminarCarrera",
        idEliminar: id
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/crearCarrerasControl.php',
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
                        tCarrera.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}
