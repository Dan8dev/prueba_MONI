$(document).ready(function(){
    init_data_carreras();
    $(".moneyFt").maskMoney();
})

function init_data_carreras(){
    cargar_carreras();
}

function cargar_carreras(){
    $.ajax({
        url: '../assets/data/Controller/marketing/marketingControl.php',
        type: "POST",
        data: {action:'consultar_comision_carreras'},
        success: function(data){
            try{
                var carreras = JSON.parse(data);
                var no_acept = [10, 11, 5];
                $("#tabla_carreras").DataTable().clear();
                for(i in carreras){
                    var carrera = carreras[i];
                    if(!no_acept.includes(parseInt(carrera.idCarrera))){
                        $("#tabla_carreras").DataTable().row.add([
                            carrera.nombre,
                            (carrera.comision != null ? moneyFormat.format(carrera.comision) : '-'),
                            `<button class="btn btn-primary" onclick="editar(${carrera.idCarrera}, ${carrera.comision})">Editar</button>`
                        ]).draw();
                    }
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

function editar(carrera, monto){
    $("#id_carrera").val(carrera)
    $("#inp_monto").val(moneyFormat.format(monto != null ? monto : 0));
    $("#modal_configurar").modal('show');
}

$("#form_actualizar").on('submit', function(e){
    e.preventDefault();
    swal({
        title: "¿Desea actualizar el monto de la comisión?",
        icon: "info",
        buttons: ["Cancelar", "Aceptar"],
        dangerMode: true,
    }).then( (value) => {
        if(value){
            fdata = new FormData(this)
            fdata.append('action', 'actualizar_monto_comision');
            $.ajax({
                url: '../assets/data/Controller/marketing/marketingControl.php',
                type: "POST",
                data: fdata,
                contentType:false,
                processData:false,
                success: function(data){
                    try{
                        var resp = JSON.parse(data);
                        if(resp.estatus == 'ok'){
                            swal({
                                icon: 'success',
                                text: 'Monto actualizado correctamente.'
                            }).then( ()=>{
                                $("#modal_configurar").modal('hide');
                                cargar_carreras();
                            });
                        }else{
                            swal({
                                icon: 'info',
                                text: resp.info
                            });
                        }
                    }catch(e){
                        console.log(e);
                        console.log(data);
                    }
                }
            });
        }
    })
})