let items = new Array();

$(document).ready(()=>{
    cargarDatosFactura();
    cargarDatosAfFac();
    cargarFacturas();
    obtenerPais();

});

$('#form-billing').on("submit",function(e){
    e.preventDefault();

    fdata = new FormData(this)
    fdata.append('action',"subirDatos");
    var ruta = "data/CData/facturacionControl.php"
    
    $.ajax({
        url: ruta,
        type: "POST",
        data: fdata,
        contentType:false,
		processData:false,
        success: function(data){

            console.log(data)
            resp = JSON.parse(data)
            if(resp.estatus == 'ok'){
            swal({icon:'success', title:'Datos Guarados exitosamente', text:'Corrobora tu información, ya que si es correcta no podremos realizar tu factura.'});
            document.getElementById('form-billing').reset();
            cargarDatosAfFac()
            }else{
                swal({icon:'info', title:'Error de conexión actualiza tu información', text:''});
            }
        }    
    });
    
});


$('#formularioFactura').on("submit",function(e){
    e.preventDefault();

    fdata = new FormData(this)
    pdf = $('#pdf').prop('files')[0];
    xml = $('#xml').prop('files')[0];
    fdata.append('action',"subirFactura");
    fdata.append('pdf',pdf);
    fdata.append('xml',xml);

    var ruta = "../siscon/app/data/CData/facturacionControl.php"
    
    $.ajax({
        url: ruta,
        type: "POST",
        data: fdata,
        contentType:false,
		processData:false,
        success: function(data){

            console.log(data)
            resp = JSON.parse(data)
            if(resp.estatus == 'ok'){
            swal({icon:'success', title:'Factura guardada correctamente', text:''});
            location.reload();
            }else{
                swal({icon:'info', title:'Error de conexión actualiza tu información', text:''});
            }
        }    
    });

})
function target_id(id){
    $('#idPayment').val(id);
}

function cargarDatosAfFac(){

    var ruta = "data/CData/facturacionControl.php"
    fdata = new FormData()
    fdata.append('action',"cargarDatosAfFac");

    $.ajax({
        url: ruta,
        type: "POST",
        data: fdata,
        contentType:false,
		processData:false,
        success: function(data){

            resp = JSON.parse(data)
            
            if(resp.estatus == 'ok' && resp.data != ''){
                $('#name').val(resp.data[0].nombre_rz);
                $('#rfc').val(resp.data[0].rfc)
                $('#street').val(resp.data[0].calle)
                $('#number').val(resp.data[0].numero)
                $('#bd').val(resp.data[0].colonia)
                $('#cp').val(resp.data[0].cp)
                $('#city').val(resp.data[0].ciudad)
                $('#state').val(resp.data[0].estado)
                $('#email').val(resp.data[0].email)
                $('#cfdi').val(resp.data[0].uso_cfdi)
                
            }else{
                
            }
        }    
    });
}


function cargarDatosFactura(){

    var url = '';
    table =  $("#datatable-subirfactura").DataTable({
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
        url: '../siscon/app/data/CData/facturacionControl.php',
        type: 'POST',
        data: {action: 'datosPagos'},
        dataType: "JSON",
        error: function(e){
            console.log(e.responseText);	
            /*if(e.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }*/
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
    'iDisplayLength': 11,
    'order':[
        [0,'asc']
    ]
    
    });
}

function cargarFacturas(){

    var url = '';
    table =  $("#tabla_facturas_desc").DataTable({
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
        url: 'data/CData/facturacionControl.php',
        type: 'POST',
        data: {action: 'datosFactura'},
        dataType: "JSON",
        error: function(e){
            console.log(e.responseText);	
            /*if(e.responseText == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }*/
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
    'iDisplayLength': 11,
    'order':[
        [0,'asc']
    ]
    
    });
}

function obtenerPais(){
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
      };

    $.ajax({
        url: 'data/CData/afiliadosControl.php?op=obtenerpais',
        dataType: 'json',
        success: function(data) {
          
            var size = Object.size(data);
          var items = "";
          for(var i = 0; i < size; i++)
          {
            items = items + '<option value="' + data[i].pais + '">' + data[i].pais + '</option>';
          } 
          $('#city').append(items);
        },
        complete: function () {
        
            $.post("data/CData/afiliadosControl.php",{ action: "obtenerestado", idpais: "MÉXICO" }, function(data) {
                var data = JSON.parse(data);
                var size = Object.size(data);
                console.log(data);
                    var items = "";
                    for(var i = 0; i < size; i++){
                    items = items + '<option value="' + data[i].Estado + '">' + data[i].Estado + '</option>';
                    }  
                    $('#state').html("");
                    $('#state').append(items);
                    
            });    

          }
      });
  
     $('#city').change(function() {
      $.ajax({
        url: 'data/CData/afiliadosControl.php?op=obtenerestado&idpais=MÉXICO',
        dataType: 'json',
        success: function(data) {
          var size = Object.size(data);
          var items = "";
          for(var i = 0; i < size; i++)
          {
            items = items + '<option value="' + data[i].Estado + '">' + data[i].Estado + '</option>';
          }  
          $('#state').html("");
          $('#state').append(items);
        }
      });      
     });
}

