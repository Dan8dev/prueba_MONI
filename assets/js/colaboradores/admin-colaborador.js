$(document).ready(function(){
    init_data();
    for(var t in tipos_usuarios){
        $("#inp_Tipo").append(`<option value="${t}">${tipos_usuarios[t]}</option>`);
        $("#inp_tipo_comision").append(`<option value="${t}">${tipos_usuarios[t]}</option>`);
    }
});

const tipos_usuarios = {
    1: "Embajador",
    2: "Vocero"
}

function init_data(){
    cargar_parametros_comisiones();
    cargar_usuarios();
    cargar_instituciones();
}

function cargar_instituciones(){
	$.ajax({
		url: "../assets/data/Controller/instituciones/institucionesControl.php",
		type: "POST",
		data: {action:'lista_todo_instituciones'},
		success: function(data){
			try{
				instit = JSON.parse(data);
				html_opc = "";
				if(instit.estatus == 'ok'){
					instit.data.sort((a , b)=>{
						return parseInt(a.acuerdo) + parseInt(b.acuerdo);
					});
					for (i = 0; i < instit.data.length; i++) {
						if(instit.data[i].fundacion == '1'){
							if(instit.data[i].acuerdo == '1'){
								html_opc+=`<option value="${instit.data[i].id_institucion}">${instit.data[i].nombre}</option>`
							}
						}
					}
				}
				$("#inp_Institucion").html(html_opc);
			}catch(e){
				console.log(e);
				console.log(data);
			}
		}
	});
}

function cargar_parametros_comisiones(){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/colaboradores/comisionesControl.php",
        data: {action:'consultar_parametros'},
        success: function (response) {
            try{
                var parametros = JSON.parse(response);
                $("#datatable-buttons").DataTable().clear();
                for(var p in parametros.data){
                    var parametro = parametros.data[p];
                    $("#datatable-buttons").DataTable().row.add([
                        tipos_usuarios[parametro.tipoColaborador],
                        (parametro.nombre_carrera.length > 44 ? parametro.nombre_carrera.substring(0, 44) + "..." : parametro.nombre_carrera),
                        parametro.minimo,
                        parametro.maximo,
                        parametro.porcentaje+"%",
                        `<button class="btn btn-primary btn-sm" onclick="editar_parametro(${parametro.idComision})"><i class="fa fa-cogs"></i></button>`
                    ]);
                }
                $("#datatable-buttons").DataTable().draw();
            }catch(e){
                console.log(e);
                console.log(response);
            }
        }
    });
}

function cargar_usuarios(){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/colaboradores/colaboradorControl.php",
        data: {action:'consultar_usuarios'},
        success: function (response) {
            try{
                var usuarios = JSON.parse(response);
                $("#datatable_colaboradores").DataTable().clear();
                for(var u in usuarios.data){
                    var usuario = usuarios.data[u];
                    $("#datatable_colaboradores").DataTable().row.add([
                        tipos_usuarios[usuario.tipo],
                        `${usuario.apellidoMaterno} ${usuario.apellidoPaterno} ${usuario.nombres}`,
                        usuario.codigo,
                        usuario.correo,
                        usuario.celular,
                        usuario.institucion_nombre,
                        `<button class="btn btn-primary btn-sm" onclick="editar_usuario(${usuario.idColaborador})"><i class="fa fa-edit"></i></button>`
                    ]);
                }
                $("#datatable_colaboradores").DataTable().draw();
            }catch(e){
                console.log(e);
                console.log(response);
            }
        }
    });
}

$("#form_new_vocero").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'registrar_vocero');
    $.ajax({
        url: '../assets/data/Controller/colaboradores/colaboradorControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form_new_vocero button:submit").attr("disabled", true);
        },
        success: function(data){
            try{
                var resp = JSON.parse(data);
                if(resp.estatus == "ok"){
                    var mensaje = $("#user_val").val() == 0 ? "Usuario registrado correctamente" : "Usuario actualizado correctamente";
                    swal('', mensaje, 'success');
                }else{
                    swal('', resp.info, 'info');
                    console.log(resp);
                }
                $("#myModal").modal('hide');
                $("#form_new_vocero")[0].reset();
                cargar_usuarios();
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#form_new_vocero button:submit").attr("disabled", false);
        }
    });
})

$(".for_code").on('change', function(){
    var val1 = $("#inp_nombre").val().trim();
    var val2 = $("#inp_aPaterno").val().trim();
    var val3 = $("#inp_aMaterno").val().trim();
    if(val1 != "" && val2 != "" && val3 != "" && ($("#user_val").val() == '' || $("#user_val").val() == 0)){
        $.ajax({
            type: "POST",
            url: "../assets/data/Controller/colaboradores/colaboradorControl.php",
            data: {
                action:'validar_codigo',
                inp_nombre: val1,
                inp_aPaterno: val2,
                inp_aMaterno: val3
            },
            success: function (response) {
                if(response != ''){
                    $("#inp_Codigo").val(response);
                }
            }
        });
    }
});

function agregarUsuario(){
    $("#user_val").val(0);
    $("#myModal").modal('show');
}

function editar_usuario(id){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/colaboradores/colaboradorControl.php",
        data: {action:'consultar_usuario',usr:id},
        success: function (response) {
            try{
                var usuario = JSON.parse(response);
                if(usuario.estatus == "ok"){
                    var usr = usuario.data;
                    $("#user_val").val(usr.idColaborador);
                    $("#inp_nombre").val(usr.nombres);
                    $("#inp_aPaterno").val(usr.apellidoPaterno);
                    $("#inp_aMaterno").val(usr.apellidoMaterno);
                    $("#inp_Correo").val(usr.correo);
                    $("#inp_telefono").val(usr.celular);
                    $("#inp_Institucion").val(usr.idInstitucion);
                    $("#inp_Tipo").val(usr.tipo);
                    $("#inp_Codigo").val(usr.codigo);

                    $("#myModal").modal('show');
                }
            }catch(e){
                console.log(e);
                console.log(response);
            }
        }
    });
}

function editar_parametro(parametro){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/colaboradores/comisionesControl.php",
        data: {action:'consultar_parametro', parametro:parametro},
        success: function (response) {
            try{
                var parametro = JSON.parse(response);
                if(parametro.estatus == "ok"){
                    var param = parametro.data;
                    $("#comision_val").val(param.idComision);
                    $("#inp_minimo").val(param.minimo);
                    $("#inp_maximo").val(param.maximo);
                    $("#inp_porcentaje").val(param.porcentaje);

                    $("#ComisionModal").modal('show');
                }
            }catch(e){
                console.log(e);
                console.log(response);
            }
        }
    });
}

$("#form_comision").on('submit', function(e){
    e.preventDefault();
    fdata = new FormData(this)
    fdata.append('action', 'actualizar_parametros');
    $.ajax({
        url: '../assets/data/Controller/colaboradores/comisionesControl.php',
        type: "POST",
        data: fdata,
        contentType:false,
        processData:false,
        beforeSend : function(){
            $("#form_new_vocero button:submit").attr("disabled", true);
        },
        success: function(data){
            try{
                var resp = JSON.parse(data);
                if(resp.estatus == "ok"){
                    swal('', "Parametro actualizado correctamente", 'success');
                }else{
                    swal('', resp.info, 'info');
                }
                $("#ComisionModal").modal('hide');
                cargar_parametros_comisiones()
            }catch(e){
                console.log(e);
                console.log(data);
            }
        },
        complete: function(){
            $("#form_new_vocero button:submit").attr("disabled", false);
        }
    });
})