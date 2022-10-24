$(document).ready(function () {
    CargarFormulariosCreados();
});

function formularioHistoriaClinica(idformulario){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/formularios/formulariosControl.php",
        data: {"action": "informacionHistoriaClinica",
                "idformulario":idformulario},
        dataType: "JSON",
        success: function (response) {
            $.each(response, function(i, item) {
                //console.log(i,item);
                if(typeof(item) == "object"){
                    $.each(item,function(index,datos){
                        //console.log(datos);
                        $("#form_dim_container").append(`<h5>${datos.nombre}</h5> <div id = "Seccion_${datos.idseccion}"class="col-md-12 clave alert alert-info"></div>`);
                        if(typeof(datos) == "object"){
                            $.each(datos,function(indice,elem){
                                 if(typeof(elem) == "object"){
                                    $.each(elem,function(subin,itemfinal){
                                        var $InfodePruebaGeneral = "";
                                        switch(itemfinal.tipo){
                                            case '0':
                                                $InfodePruebaGeneral = " ________  ";
                                                break;
                                            case '1':
                                                $InfodePruebaGeneral = " ( ) ";
                                                break;
                                            case '2':
                                                $InfodePruebaGeneral = " (x) /";
                                                break;
                                        }

                                        $(`#Seccion_${datos.idseccion}`).append(`<button type = "button" class = "btn btn-flat"><b>${itemfinal.nombre}</b>:${$InfodePruebaGeneral}</button>`);
                                    });
                                    
                                    $(`#Seccion_${datos.idseccion}`).append(`<div id = "newButton_${datos.idseccion}"><button onClick = "transformarElemento(${datos.idseccion})" type = "button" class = "btn btn-succes"><b> + Item </b></button></div>`);
                                   
                                 }
                            });
                        }
                    });
                    $("#form_dim_container").append(`<button id = "newSeccion_{${idformulario}}" type = "button" class = "btn btn-primary onClick = "newSeccionType(this,${idformulario})"><b> + Sección</b></button>`);
                }else{
                    $("#TituloFormulario").append(item);
                }
            });
        },
        complete: function(e){
            $("#modalFormularios").modal("show");
        }
    });
}

function transformarElemento(idSeccion){
    $(`#newButton_${idSeccion}`).html(`<select onChange ="newAddItemType(this,${idSeccion})" id = "newItem_${idSeccion}" class = "form-control">
                                            <option value="" selected disabled>Seccione el tipo de item</option>
                                            <option value="1">Texto</option>
                                            <option value="2">Check</option>
                                            <option value="3">Multiple Check</option>
                                        <select>`);
}

$('#modalFormularios').on('hide.bs.modal', function () { 
    $("#form_dim_container").html("");
});


function newAddItemType(este,id){
    $(`#newButton_${id}`).html("");
    var letrero = "";
    switch($(este).val()){
        case '1':
            letrero = "Introduza el nombre del text";
            break;
        case '2':
            letrero = "Introduzca el nombre del check";
            break;
        case '3':
            letrero = "Introduzca las opciones separadad por un '/'";
            break;
    }
    $(`#newButton_${id}`).html(`<input class = "form-control" placeholder="${letrero}"></input>`);
}

function newSeccionType(este,id){
    $(`#newButton_${id}`).html("");
    var letrero = "";
    switch($(este).val()){
        case '1':
            letrero = "Introduza el nombre del text";
            break;
        case '2':
            letrero = "Introduzca el nombre del check";
            break;
        case '3':
            letrero = "Introduzca las opciones separadad por un '/'";
            break;
    }
    $(`#newButton_${id}`).html(`<input class = "form-control" placeholder="${letrero}"></input>`);
}


function CargarFormulariosCreados(){
    tableSession = $("#tableFormularios").DataTable({
        responsive: true,
        Processing: true,
        ServerSide: true,
        "dom" :'Bfrtip',
        buttons:[{
            extend: "excel",
            className: "btn-primary"
        }, {
            extend: "pdf",
        }, {
            extend: "print",
            className: "d-none",
        }],
        "ajax": {
            url: '../assets/data/Controller/formularios/formulariosControl.php',
            type: 'POST',
            data: {action: 'loadFormsTable'},
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
        'iDisplayLength': 50,
        'order':[
            [1,'desc']
        ],
    });
}

function abrirInformanciondeFormulario(idformulario){
    
}