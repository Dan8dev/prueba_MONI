
function buscarDatosCredencial(usuario){
    Data = {
        action: 'obtenerCredencial',
        idusuario: usuario
    }
    $.ajax({
        url: 'data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){ 
            try{
                var dir = usuario +'-credencial-conacon.pdf';
                window.open('credenciales/'+dir,'_blank');
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        error : function(){

        },
        complete : function(){
            $(".outerDiv_S").css("display", "none")
        }
    });
}

function buscarDatosTarjeta(usuario){
    Data = {
        action: 'obtenerTarjeta',
        idusuario: usuario
    }
    $.ajax({
        url: 'data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                var dir = usuario+'-tarjeta-conacon.pdf';
                window.open('tarjetas/'+dir,'_blank');
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


function buscarAfiliacion(usuario){
    Data = {
        action: 'obtenerAfiliacion',
        idusuario: usuario
    }
    $.ajax({
        url: 'data/CData/identidadControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                var dir = usuario+'-tarjeta-afiliacion.pdf';
                window.open('tarjeta-afiliacion/'+dir,'_blank');
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