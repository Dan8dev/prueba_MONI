listarcarrerasreporte()
function listarcarrerasreporte(){
    var Data = {
       action: "obtenerCarrerasGen"
    }
    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){
            $("#list-carrera-totales").html('<option disabled="disabled" value="" selected>Seleccione la Carrera</option>');
            $("#carrerasCert").html('<option  disabled="disabled" selected>Seleccione la Carrera</option>');
            $.each(data, function(key,registro){
                $("#list-carrera-totales").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                $("#carrerasCert").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
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

$("#list-carrera-totales").on('change', function(){
    idCarrera = $("#list-carrera-totales").val();
    obteneralumnostotalesreporte(idCarrera);
})


function obteneralumnostotalesreporte(idCarrera){

    $.ajax({
        url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
        type: 'POST',
        data: {
                action: "totalalumnosgeneracion", 
                idCarrera: idCarrera
            },

        success : function(data){
            $('#totalalumnosgeneracion').html(data);
            console.log(data);
        },
        complete : function (){
            $("#mostrarselectgeneraciones").show();
        }
    });

    tAlumnoCarrera = $("#table-alumnos-carreras").DataTable({
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
            url: '../assets/data/Controller/planpagos/alumnosPagosControl.php',
            type: 'POST',
            data: {action: 'obtener_alumnos_totales_carrera',
                     idCarrera: idCarrera},
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