
$(".passcontrol").on('keyup', (e)=>{
    var val1 = $("#inpPassw").val().trim();
    var val2 = $("#inpPassw_verify").val().trim();
    if(val1 == val2 && val1 != ''){
        $("#verifypass").html(`<div class="alert alert-success"> Las contraseñas coinciden.</div>`);
    }else if(val1 == '' || val2 == ''){
        $("#verifypass").html('');
    }else{
        $("#verifypass").html(`<div class="alert alert-danger"> Las contraseñas no coinciden.</div>`);
    }
});

$("#recover_pass").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this);
    fdata.append('action', 'restablecer_pass');
    $.ajax({
        url: '../../udc/app/data/CData/alumnosControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#loader").css("display", "block")
        },
        success: function(data){
            try{
                var cambio = JSON.parse(data);
                if(cambio.estatus == 'ok'){
                    swal({
                        icon:'success',
                        text:'Su contraseña se ha reestablecido correctamente.'
                    }).then(()=>{
                        window.location.href = (cambio.panel == 'conacon' ? 'https://conacon.org/moni/siscon': 'https://moni.com.mx/iesm');
                    });
                }else{
                    swal({
                        icon:'info',
                        text:cambio.info
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