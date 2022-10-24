function dataMaicol(op, callb = null){
    error = null;
    error = (!op.hasOwnProperty('class_i') && !op.hasOwnProperty('id_tab')) ? 'selector' : error;
    
    debug = (op.hasOwnProperty('debug'))? op.debug : false;
    data_o = (op.hasOwnProperty('order_data'))? [op.order_data] : null;
    
    if(op.hasOwnProperty('id_tab')){
        selector_tab = "#"+op.id_tab;
    }
    if(op.hasOwnProperty('class_i')){
        selector_tab = "."+op.class_i;
    }

    response = [];
    if(op.hasOwnProperty('data')){
        // ajx = $.ajax();
        error = (!op.hasOwnProperty('url')) ? 'url' : error;
        ajx = {
            url: op.url,
            type: 'POST',
            data: op.data,
            dataType: "JSON",
            error: function(xhr, status, error){
                console.log(xhr);
                console.log(status);
                console.log(error);
            },
            dataSrc: function(json){
                if(debug){
                    console.log(json);
                }
                if(json.hasOwnProperty('estatus')){
                    if(json.estatus == 'ok'){
                        response = json.data;
                        return json.data;
                    }
                }else{
                    response = json;
                    return json;
                }
            },
            complete: function(){
                if(response.length > 0){
                    callb();
                }
            }
        }
        // ajx = ajx.responseJSON.data;
        // console.log(">>>> ");
        // console.log(ajx);
    }else{
        ajx = null;
    }
    if(error !== null){
        alert('Falta definir: '+error);
    }else{
        if(op.hasOwnProperty('id_tab')){
            $(selector_tab).DataTable({
                responsive: true,
                Processing: true,
                ServerSide: true,
                "ajax":ajx,
                'language':{
                    'sLengthMenu':  'Mostrar _MENU_ registros',
                    'sInfo':        'Mostrando registro del _START_ al _END_ de un total de _TOTAL_ registros',
                    'sInfoEmpty':   'Mostrando registros del 0 al 0 de un total de 0 registros',
                    'sEmptyTable':  "No hay datos disponibles",
                    'sInfoFiltered':'(filtrado de un total de _MAX_ registros)',
                    'sSearch':      'Buscar:',
                    'sLoadingRecords': 'Cargando',
                    'oPaginate':{
                        'sFirst':   'Primero',
                        'sLast':    'Último',
                        'sNext':    'Siguiente',
                        'sPrevious':'Anterior'
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
                'iDisplayLength': 10
            });
        }else if(op.hasOwnProperty('class_i')){
            $(selector_tab).each(function(){
                console.log($(this))
                $(this).DataTable({
                    responsive: true,
                    Processing: true,
                    ServerSide: true,
                    ajax:ajx,
                    'language':{
                        'sLengthMenu': 'Mostrar MENU registros',
                        'sInfo': 'Mostrando registro del START al END de un total de TOTAL registros',
                        'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros',
                        'sInfoFiltered': '(filtrado de un total de MAX registros)',
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
                    'iDisplayLength': 10
                });
            })
        }
    }

    
}
