$("#form-recovery").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'recuperar_pass');
    $.ajax({
        url: 'assets/data/Controller/accesoControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form-recovery button[type='submit']").attr("disabled", true);
        },
        success: function(data){
            try{
                var resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
                    swal({
                        icon:'success',
                        text:'Revisa tu bandeja de entrada, te enviaremos el procedimiento para recuperar tu contraseña'
                    }).then(()=>{
                        window.location.replace(`index.php`);
                    });
                }else{
                    swal({
                        icon:'info',
                        text:resp.info
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
            $("#form-recovery button[type='submit']").attr("disabled", false);
        }
    });
})

$("#formCompleteRecover").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'restablecer_pass');
    $.ajax({
        url: 'assets/data/Controller/accesoControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#formCompleteRecover button[type='submit']").attr("disabled", true);
        },
        success: function(data){
            try{
                var resp = JSON.parse(data);
                if(resp.estatus == 'ok'){
					swal({
						icon:'success',
						title:'contraseña actualizada'
					}).then(()=>{
						window.location.replace(`./`);
					});
				}else{
					swal({
						icon:'info',
						text:resp.info
					}).then(()=>{
						
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
            $("#formCompleteRecover button[type='submit']").attr("disabled", false);
        }
    });
})