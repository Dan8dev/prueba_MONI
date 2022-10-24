$('#tabfechascorte').click(function (e) {
    tGeneraciones = $("#table-fechascorte").DataTable({
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
            url: '../assets/data/Controller/planpagos/fechascorteControl.php',
            type: 'POST',
            data: {action: 'obtenerGeneraciones'},
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
            }, complete: function(){
                selects_datatable('table-fechascorte')
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

$('#tabfechascorteporalumno').click(function (e) {
    tFechascorteporalumnos = $("#table-fechascorteporalumno").DataTable({
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
            url: '../assets/data/Controller/planpagos/fechascorteControl.php',
            type: 'POST',
            data: {action: 'obteneralumnos'},
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
            },complete: function (){
                selects_datatable('table-fechascorteporalumno');
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

function buscarGeneracionfechacorte(idgeneracion) {
    $('#diasdecortefechacortemod').numeric();
    var Data = {
        action: "buscarGeneracionfechacorte",
        idgeneracion: idgeneracion
     }
     $.ajax({
        url: '../assets/data/Controller/planpagos/fechascorteControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            try {
                
                $('#idgenfechacortemod').val(idgeneracion);
                if(Object.keys(data).length > 0){//si la generacion tiene fechas de corte personalizadas mostrar
                    $('#nombregenfechacorte').html(data[0].nombregeneracion);
                    $('#nombrecarrerafechacorte').html(data[0].nombrecarrera);
                    $('#costoInscripcionfechacortegenmod').val(data[0].precio);

                    //COSTO INSCRIPCION USD
                    if (data[0].precio_usd=='') {
                        data[0].precio_usd = 0;
                    }
                    $('#costoInscripcionfechacortegenmodusd').val(data[0].precio_usd);
                    //COSTO INSCRIPCION USD

                    var fechalimitepago1 = data[0].fechalimitepago.split(' ');
                    $('#fechalimitepagoinsfechacortemod').val(fechalimitepago1[0]);
                    $('#idconceptoinsfechacorte').val(data[0].id_concepto);

                    $('#idconceptoinsfechacorteusd').val(data[0].id_concepto);

                    if (Object.keys(data).length==4) {//mostrar si tiene conceptos de ins mens tit y resins personalizados
                        $('#divMensualidadfechacortemod').show();
                        $('#divReinscripcionfechacortemod').show();
                        $('#divcostotitulacionfechacortemod').show();

                        $('#nMensualidadesfechacortemod').val(data[1].numero_pagos);
                        $('#costoMensualidadfechacortemod').val(data[1].precio);
                        
                        //COSTO MENSUALIDAD USD
                        if (data[1].precio_usd=='') {
                            data[1].precio_usd = 0;
                        }
                        $('#costoMensualidadfechacortemodusd').val(data[1].precio_usd);
                        //COSTO MENSUALIDAD USD

                        $('#diasdecortefechacortemod').val(data[1].fechalimitepago.substr(-11,2));
                        $('#idconceptomensfechacorte').val(data[1].id_concepto);

                        $('#nReinscripcionfechacortemod').val(data[2].numero_pagos);
                        $('#costoReinscripcionfechacortemod').val(data[2].precio);

                        //COSTO REINSCRIPCION USD
                        if (data[2].precio_usd=='') {
                            data[2].precio_usd = 0;
                        }
                        $('#costoReinscripcionfechacortemodusd').val(data[2].precio_usd);
                        //COSTO REINSCRIPCION USD

                        $('#idconceptoreinsfechacorte').val(data[2].id_concepto);

                        $('#costotitulacionfechacortemod').val(data[3].precio);

                        //COSTO TITULACIÓN USD
                        if (data[3].precio_usd=='') {
                            data[3].precio_usd = 0;
                        }
                        $('#costotitulacionfechacortemodusd').val(data[3].precio_usd);
                        //COSTO TITULACIÓN USD

                        var fechalimitepagotitfechacorte1 = data[3].fechalimitepago.split(' ');
                        $('#fechalimitepagotitfechacortemod').val(fechalimitepagotitfechacorte1[0]);
                        $('#idconceptotitfechacorte').val(data[3].id_concepto);

                        $('#certificacionoenevtofechas').val('no');
                        //suma de conceptos para mostrar total en mxn
                        var costoins=data[0].precio;
                        var costomens = data[1].precio*data[1].numero_pagos;
                        var costoreins =data[2].precio*data[2].numero_pagos;
                        var costotit = data[3].precio;
                        //suma de conceptos para mostrar total en mxn

                        //suma de conceptos para mostrar total en usd
                        var costoins_usd=data[0].precio_usd;
                        var costomens_usd = data[1].precio_usd*data[1].numero_pagos;
                        var costoreins_usd =data[2].precio_usd*data[2].numero_pagos;
                        var costotit_usd = data[3].precio_usd;
                        //suma de conceptos para mostrar total en usd

                        var total = parseInt(costoins)+parseInt(costomens)+parseInt(costoreins)+parseInt(costotit);
                        var totalusd = parseInt(costoins_usd)+parseInt(costomens_usd)+parseInt(costoreins_usd)+parseInt(costotit_usd);

                        $('#totalfechacorte').val(total); //suma de todos los conceptos
                        $('#totalfechacorteusd').val(totalusd); //suma de todos los conceptos en usd
                    }
                    if (Object.keys(data).length==1) {// conceptos de certificaciones y eventos que ya estan personalizados
                        $('#divMensualidadfechacortemod').hide();
                        $('#divReinscripcionfechacortemod').hide();
                        $('#divcostotitulacionfechacortemod').hide();

                        $('#certificacionoenevtofechas').val('si');

                        $('#totalfechacorte').val(data[0].precio);
                        $('#totalfechacorteusd').val(data[0].precio_usd);

                        $("#nMensualidadesfechacortemod").removeAttr('required');
                        $("#costoMensualidadfechacortemod").removeAttr('required');
                        $("#diasdecortefechacortemod").removeAttr('required');
                        $("#nReinscripcionfechacortemod").removeAttr('required');
                        $("#costoReinscripcionfechacortemod").removeAttr('required');
                         //se eliminan atributos requeridos de campos que no se van a usar usd
                        $("#nMensualidadesfechacortemodusd").removeAttr('required');
                        $("#costoMensualidadfechacortemodusd").removeAttr('required');
                        $("#diasdecortefechacortemodusd").removeAttr('required');
                        $("#nReinscripcionfechacortemodusd").removeAttr('required');
                        $("#costoReinscripcionfechacortemodusd").removeAttr('required');
                        //se eliminan atributos requeridos de campos que no se van a usar usd
                    }

                }
                else{// si la generacion no tiene fechas personalizadas mostrar conceptos y fechas de corte del plan de pagos original de la carrera
                    var Data = {
                        action: "obtenerconceptosoriginales",
                        idgeneracion: idgeneracion
                     }
                    $.ajax({
                        type: "POST",
                        url: "../assets/data/Controller/planpagos/fechascorteControl.php",
                        data: Data,
                        dataType: "JSON",
                        success: function (response) {
                            $('#nombregenfechacorte').html(response[0].nombregeneracion);
                            $('#nombrecarrerafechacorte').html(response[0].nombrecarrera);
                            $('#costoInscripcionfechacortegenmod').val(response[0].precio);
                            var fechalimitepago = response[0].fechalimitepago.split(' ');
                            $('#fechalimitepagoinsfechacortemod').val(fechalimitepago[0]);

                            if (Object.keys(response).length==4) {//mostrar si tiene conceptos de ins mens tit y resins
                                $('#divMensualidadfechacortemod').show();
                                $('#divReinscripcionfechacortemod').show();
                                $('#divcostotitulacionfechacortemod').show();

                                $('#nMensualidadesfechacortemod').val(response[1].numero_pagos);
                                $('#costoMensualidadfechacortemod').val(response[1].precio);
                                $('#diasdecortefechacortemod').val(response[1].fechalimitepago.substr(-11,2));

                                $('#nReinscripcionfechacortemod').val(response[2].numero_pagos);
                                $('#costoReinscripcionfechacortemod').val(response[2].precio);

                                $('#costotitulacionfechacortemod').val(response[3].precio);
                                var fechalimitepagotitfechacorte = response[3].fechalimitepago.split(' ');
                                $('#fechalimitepagotitfechacortemod').val(fechalimitepagotitfechacorte[0]);
                                $('#certificacionoenevtofechas').val('no');
                            }
                            if (Object.keys(response).length==1) {
                                $('#divMensualidadfechacortemod').hide();
                                $('#divReinscripcionfechacortemod').hide();
                                $('#divcostotitulacionfechacortemod').hide();

                                $("#nMensualidadesfechacortemod").removeAttr('required');
                                $("#costoMensualidadfechacortemod").removeAttr('required');
                                $("#diasdecortefechacortemod").removeAttr('required');
                                $("#nReinscripcionfechacortemod").removeAttr('required');
                                $("#costoReinscripcionfechacortemod").removeAttr('required');

                                //se eliminan atributos requeridos de campos que no se van a usar usd
                                $("#nMensualidadesfechacortemodusd").removeAttr('required');
                                $("#costoMensualidadfechacortemodusd").removeAttr('required');
                                $("#diasdecortefechacortemodusd").removeAttr('required');
                                $("#nReinscripcionfechacortemodusd").removeAttr('required');
                                $("#costoReinscripcionfechacortemodusd").removeAttr('required');
                                //se eliminan atributos requeridos de campos que no se van a usar usd

                                $('#certificacionoenevtofechas').val('si');
                            }
                            $('#totalfechacorte').val(response[0].costototal); //suma de todos los conceptos

                            $('#nombregenfechacorteg').val(response[0].nombregeneracion);
                        }
                    });
                    
                }
            } catch (error) {
                console.log(error);
            }
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

function buscaralumnofechacorte(idAsistente, idGeneracion) {
    var Data = {
        action: "buscaralumnofechacorte",
        idGeneracion: idGeneracion,
        idAsistente: idAsistente
     }
     $.ajax({
        url: '../assets/data/Controller/planpagos/fechascorteControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            try {
                $('#idAsistentefechacortealumno').val(idAsistente);
                $('#idGeneracionfechacortealumno').val(idGeneracion);
                $('#fechaprimercolegiaturamod').val(data.fecha_primer_colegiatura);
                
            } catch (error) {
                console.log(error);
            }
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

$("#formactualizarfechasdecorteporalumno").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'formactualizarfechasdecorteporalumno');
    $.ajax({
        url: '../assets/data/Controller/planpagos/fechascorteControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        success: function(data){
            try{
                var pr = JSON.parse(data);
                if (pr.estatus == 'ok'){
                    swal({
                        title: 'Fecha actualizada',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        tFechascorteporalumnos.ajax.reload();
                    })
                }else{
                    swal({
                        icon:'info',
                        text:pr.info
                    });
                }
                $("#modalfechacorteporalumno").modal('hide');
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#totalfechacorte").prop('disabled',true);
            $("#btnCrearconceptosfechascorte").prop('disabled',false);
        }
    });
})

function obtenerTotalfechacorteMod(){
    if($("#costoMensualidadfechacortemod").val().trim() != '' && $("#nMensualidadesfechacortemod").val().trim() != '' && $("#costoInscripcionfechacortegenmod").val().trim() != '' && $("#nReinscripcionfechacortemod").val().trim() != '' && $("#costoReinscripcionfechacortemod").val().trim() != ''){
        var cM = parseInt($("#costoMensualidadfechacortemod").val());
        var nM = parseInt($("#nMensualidadesfechacortemod").val());
        var cI = parseInt($("#costoInscripcionfechacortegenmod").val());
        var nR = parseInt($("#nReinscripcionfechacortemod").val());
        var cR = parseInt($("#costoReinscripcionfechacortemod").val());
        var nuevotit = parseInt($("#costotitulacionfechacortemod").val());
        mulM = cM * nM;
        mulR = cR * nR;
        $("#totalfechacorte").val(cI + mulM + mulR + nuevotit);
    }
}

$("#costoMensualidadfechacortemod").keyup(function(){
    obtenerTotalfechacorteMod();
});

$("#nMensualidadesfechacortemod").keyup(function(){
    obtenerTotalfechacorteMod();
});

$("#costoInscripcionfechacortegenmod").keyup(function(){
    obtenerTotalfechacorteMod();
});

$("#nReinscripcionfechacortemod").keyup(function(){
    obtenerTotalfechacorteMod();
})

$("#costoReinscripcionfechacortemod").keyup(function(){
    obtenerTotalfechacorteMod();
})

$("#costotitulacionfechacortemod").keyup(function(){
    obtenerTotalfechacorteMod();
})

//calculo de conceptos USD
function obtenerTotalfechacorteModusd(){
    if($("#costoMensualidadfechacortemodusd").val().trim() != '' || $("#nMensualidadesfechacortemod").val().trim() != '' || $("#costoInscripcionfechacortegenmodusd").val().trim() != '' || $("#costoReinscripcionfechacortemodusd").val().trim() != '' || $("#costotitulacionfechacortemodusd").val().trim() != ''){
        var cI;
        var cM;
        var nM;
        var nR;
        var cR;
        var nuevotit;

        ($("#costoInscripcionfechacortegenmodusd").val()=='')? cI =0:cI = parseInt($("#costoInscripcionfechacortegenmodusd").val());
        ($("#costoMensualidadfechacortemodusd").val()=='')? cM =0:cM = parseInt($("#costoMensualidadfechacortemodusd").val());
        ($("#nMensualidadesfechacortemod").val()=='')?nM =0:nM = parseInt($("#nMensualidadesfechacortemod").val());
        ($("#nReinscripcionfechacortemod").val()=='')?nR =0:nR = parseInt($("#nReinscripcionfechacortemod").val());
        ($("#costoReinscripcionfechacortemodusd").val()=='')?cR =0:cR = parseInt($("#costoReinscripcionfechacortemodusd").val());
        ($("#costotitulacionfechacortemodusd").val()=='')?nuevotit =0:nuevotit = parseInt($("#costotitulacionfechacortemodusd").val());
        mulM = cM * nM;
        mulR = cR * nR;
        $("#totalfechacorteusd").val(cI + mulM + mulR + nuevotit);
    }
}

$("#costoMensualidadfechacortemodusd").keyup(function(){
    obtenerTotalfechacorteModusd();
});

$("#nMensualidadesfechacortemod").keyup(function(){
    obtenerTotalfechacorteMod();
    obtenerTotalfechacorteModusd();
    $('#nMensualidadesfechacortemodusd').val($("#nMensualidadesfechacortemod").val());
});

$("#costoInscripcionfechacortegenmodusd").keyup(function(){
    obtenerTotalfechacorteModusd();
});

$("#costoReinscripcionfechacortemodusd").keyup(function(){
    obtenerTotalfechacorteModusd();
})

$("#nReinscripcionfechacortemod").keyup(function(){
    obtenerTotalfechacorteModusd();
})

$("#costotitulacionfechacortemodusd").keyup(function(){
    obtenerTotalfechacorteModusd();
})
//calculo de conceptos USD

$("#formcrearnuevosconceptosgeneracion").on('submit', function(e){
    e.preventDefault();
    $("#nReinscripcionfechacortemod").prop('disabled',false);
    $("#totalfechacorte").prop('disabled',false);
    $("#btnCrearconceptosfechascorte").prop('disabled',true);
    fdata = new FormData(this)
    fdata.append('action', 'crearconceptosfechascortegen');
    fdata.append('actualizado_por', usrInfo.idAcceso);
    fdata.append('creado_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/planpagos/fechascorteControl.php',
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
                if (pr.estatus == 'ok'){
                    swal({
                        title: 'Conceptos de pago actualizados con éxito',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result)=>{
                        $("#formcrearnuevosconceptosgeneracion")[0].reset();
                        $("#modalfechacortegeneracion").modal("hide");
                        tGeneraciones.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#totalfechacorte").prop('disabled',true);
            $("#nReinscripcionfechacortemod").prop('disabled',true);
            $("#btnCrearconceptosfechascorte").prop('disabled',false);
        }
    });
})
