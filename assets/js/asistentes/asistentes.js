let scanner = new Instascan.Scanner(
    {
        video: document.getElementById('preview')
    }
);
scanner.addListener('scan', function (content) {
    console.log(content);
    
    $('#nombreasistente').val('');
    $('#tituloevento').val('');
    $('#fechaevento').val('');
    $('#cantidadpagada').val('');
    $("#fotoasistente").attr("src","../assets/images/users/avatar-1.jpg");
    $("#talleresasistente").empty()

    $.post("../assets/data/Controller/asistentes/asistentesControl.php",{action: "obtenerdatosasistente",codigoqr: content}, function(data, status){
        data = JSON.parse(data);   
        if (Object.keys(data).length==1) {
            $('#nombreasistente').val(data[0].nombre);
            $('#tituloevento').val(data[0].nombreenvento);
            $('#fechaevento').val(data[0].fechaevento);
            data2 = JSON.parse(data[0].totalpagado);
            $('#cantidadpagada').val('$'+data2.purchase_units[0].amount.value);
            $("#fotoasistente").attr("src","../siscon/app/img/afiliados/"+data[0].foto+"");
            
            $.post("../assets/data/Controller/asistentes/asistentesControl.php",{action: "obtenertalleresasistente",codigoqr: content}, function(data3, status){
                data3 = JSON.parse(data3);
                console.log(data3)
                $("#talleresasistente").empty()
                $.each(data3,function(index,element) {
                    $("#talleresasistente").append("<option value="+ element.nombre +">" + element.nombre+"<option/>")
                });
            });
        } else {
            alert('No se ha encontrado datos de pago');
        }
 	});
});

Instascan.Camera.getCameras().then(cameras => {
    if (cameras.length > 0) {
        scanner.start(cameras[0]);
    } else {
        console.error("Não existe câmera no dispositivo!");
    }
});