$(document).ready(function () {
    cargarPaisesDirectorio();
});

listedGenerations = [];
function datosDirectorio(idAlumno, idCarrera, idGeneracion, pais, pais_nacimiento, pais_estudio, idRelacion) {
    //console.log(idAlumno);

    typeUs = usrInfo.estatus_acceso != undefined ? usrInfo.estatus_acceso : 1; 
    $("#formDatosDirectorio")[0].reset();
    cargarGeneracionesDirectorio(idCarrera);
    cargarEstadosDirectorio(pais);
    cargarEstadosPaisNacimiento(pais_nacimiento);
    cargarEstadosPaisRadica(pais_estudio);

    $('#groupVisible').addClass('hidden');

    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: 'obtenerDatosAlumnoDirectorio',
            idAlum: idAlumno,
            idGen: idGeneracion,
            idUs: typeUs
        },
        success: function (data) {
            try {
                pr = JSON.parse(data)
                //console.log(pr);
                $("#idAlumno_d").val(pr.idalumno);

                $("#telefonoDeCasaDirectorio").val(pr.telefono_casa);
                $("#telefonoRecadosDirectorio").val(pr.telefono_recados);

                $("#EscuelaEstudioDirectorio").val(pr.escuela_procedencia);
                $("#fechaEgresoEstudioDirectorio").val(pr.fecha_egreso);
                $("#CedulaProfesionalDirectorio").val(pr.cedula);

                $("#nombreDirectorio").val(pr.nombre);
                $("#apellidoPaternoDirectorio").val(pr.aPaterno);
                $("#apellidoMaternoDirectorio").val(pr.aMaterno);
                /** nuevos campos */
                $("#inp_matricula").val(pr.matricula);
                $("#inp_ciudad").val(pr.ciudad)
                $("#inp_colonia").val(pr.colonia)
                $("#inp_calle").val(pr.calle)
                $("#inp_cp").val(pr.cp)
                /** fin nuevos campos */

                $("#idGeneracionAntigua").val(pr.idgeneracion);

                
                //Estatus 6 es un id interno para indicar que el alumno fue validado por cobranza para el proceso de titulacion
                if(pr.alumgenEstatus == 6){
                    $("#estatusAlumnoGeneraciones").val(6);
                    pr.alumgenEstatus = 3; 
                    //$("#estatusAlumnoDirectorio").innerHTML("EGRESADOs");
                }else{
                    $("#estatusAlumnoGeneraciones").val("");
                }
                $("#estatusAlumnoDirectorio").val(pr.alumgenEstatus);
                

                
                $("#curpAlumnoDirectorio").val(pr.curp);
                if (pr.edad != 0) {
                    $("#edadAlumnoDirectorio").val(pr.edad);
                }
                $("#emailAlumnoDirectorio").val(pr.email);
                $("#telefonoAlumnoDirectorio").val(pr.celular);
                $("#gradoUltimoAlumnoDirectorio").val(pr.grado_academico);
                $("#sexoAlumnoDirectorio").val(pr.Genero);

                if (pr.pais != null) {
                    if (pr.estado == '') {
                        if (pr.pais != "37") {
                            $("#paisAlumnoDirectorio").val(pr.pais);
                        }
                    } else {
                        $("#paisAlumnoDirectorio").val(pr.pais);
                    }
                }
                $("#paisAlumnoDirectorio").change();
                if (pr.pais_nacimiento != 0) {
                    if (pr.estado_nacimiento == 0) {
                    //    if (pr.pais_nacimiento != "37") {
                            $("#paisNacimientoDirectorio").val(pr.pais_nacimiento);
                    //    }
                    } else {
                        $("#paisNacimientoDirectorio").val(pr.pais_nacimiento);
                    }
                }
                if (pr.pais_estudio != 0) {
                    if (pr.estado_estudio == 0) {
                        if (pr.pais_estudio != "37") {
                            $("#paisEstudioDirectorio").val(pr.pais_estudio);
                        }
                    } else {
                        $("#paisEstudioDirectorio").val(pr.pais_estudio);
                    }
                }
                var estadoDir = pr.estado;
                var estadoNac = pr.estado_nacimiento;
                var estadoRad = pr.estado_estudio;
                setTimeout(() => {
                    $("#generacionDirectorio").val(pr.idgeneracion);
                    //if (estadoDir == 0) {
                    //    $("#estadoAlumnoDirectorio").prop('disabled', true);
                    //} else {
                        $("#estadoAlumnoDirectorio").prop('disabled', false);
                    //}
                    if (estadoNac == 0) {
                        $("#entidadNacimientoDirectorio").prop('disabled', true);
                    } else {
                        $("#entidadNacimientoDirectorio").prop('disabled', false);
                    }
                    if (estadoRad == 0) {
                        $("#entidadEstudioDirectorio").prop('disabled', true);
                    } else {
                        $("#entidadEstudioDirectorio").prop('disabled', false);
                    }
                    $("#estadoAlumnoDirectorio").val(pr.estado);
                    $("#entidadNacimientoDirectorio").val(pr.estado_nacimiento);
                    $("#entidadEstudioDirectorio").val(pr.estado_estudio);
                    $("#idRelacion").val(idRelacion);
                    if(parseInt(usrInfo.estatus_acceso) == 4 || parseInt(usrInfo.idTipo_Persona) == 36){
                        selectGroup();
                    $('#generacionDirectorioGrupo').val(pr.grupo);
                    }
                    
                }, 1000);
                
                $("#notasDirectorio").val(pr.notas);
                if(pr.grupo != null && pr.grupo != ''){
                    $('#groupVisible').removeClass('hidden');
                }
            } catch (e) {
                console.log(e)
                console.log(data)
            }
        }
    })
}

function cargarGeneracionesDirectorio(id) {
    $("#generacionDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarGeneracionesDirectorio",
            idCarrera: id
        },
        dataType: 'JSON',
        success: function (data) {
            $("#generacionDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            if(listedGenerations.length > 0){
                listedGenerations = [];
            }
            listedGenerations = data;
            
            $.each(data, function (key, registro) {
                 
                $("#generacionDirectorio").append('<option value=' + registro.idGeneracion + '>' + registro.nombre + '</option>');
            });
        }
    });
}

function cargarEstadosDirectorio(pais) {
    $("#estadoAlumnoDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: pais
        },
        dataType: 'JSON',
        success: function (data) {
            $("#estadoAlumnoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function (key, registro) {
                $("#estadoAlumnoDirectorio").append('<option value=' + registro.IDEstado + '>' + registro.Estado + '</option>');
            });
        }
    })
}
function cargarEstadosPaisNacimiento(pais) {
    $("#entidadNacimientoDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: pais
        },
        dataType: 'JSON',
        success: function (data) {
            $("#entidadNacimientoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function (key, registro) {
                $("#entidadNacimientoDirectorio").append('<option value=' + registro.IDEstado + '>' + registro.Estado + '</option>');
            });
        }
    })
}

function cargarEstadosPaisRadica(pais) {
    $("#entidadEstudioDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: pais
        },
        dataType: 'JSON',
        success: function (data) {
            $("#entidadEstudioDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function (key, registro) {
                $("#entidadEstudioDirectorio").append('<option value=' + registro.IDEstado + '>' + registro.Estado + '</option>');
            });
        }
    })
}

$("#paisEstudioDirectorio").on('change', function () {
    $("#entidadEstudioDirectorio").empty();
    idPais = $("#paisEstudioDirectorio").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: idPais
        },
        dataType: 'JSON',
        success: function (data) {
            $("#entidadEstudioDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione el Estado</option>');
            $.each(data, function (key, registro) {
                $("#entidadEstudioDirectorio").prop('disabled', false);
                $("#entidadEstudioDirectorio").append('<option value =' + registro.IDEstado + '>' + registro.Estado + '</option>');
            });
            if (data == '') {
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#entidadEstudioDirectorio").prop('disabled', true);
            }
        }
    });
})

function cargarEstadosDirectorio(pais) {
    $("#estadoAlumnoDirectorio").empty();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: pais
        },
        dataType: 'JSON',
        success: function (data) {
            $("#estadoAlumnoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function (key, registro) {
                $("#estadoAlumnoDirectorio").append('<option value=' + registro.IDEstado + '>' + registro.Estado + '</option>');
            });
        }
    })
}

function cargarPaisesDirectorio() {
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarPaisesDirectorio"
        },
        dataType: 'JSON',
        success: function (data) {
            $("#paisAlumnoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#paisNacimientoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $("#paisEstudioDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
            $.each(data, function (key, registro) {
                $("#paisAlumnoDirectorio").append('<option value=' + registro.IDPais + '>' + registro.Pais + '</option>');
                $("#paisNacimientoDirectorio").append('<option value=' + registro.IDPais + '>' + registro.Pais + '</option>');
                $("#paisEstudioDirectorio").append('<option value=' + registro.IDPais + '>' + registro.Pais + '</option>');
            });
        }
    });
}

$("#paisNacimientoDirectorio").on('change', function () {
    $("#entidadNacimientoDirectorio").empty();
    idPais = $("#paisNacimientoDirectorio").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: idPais
        },
        dataType: 'JSON',
        success: function (data) {
            $("#entidadNacimientoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione el Estado</option>');
            $.each(data, function (key, registro) {
                $("#entidadNacimientoDirectorio").prop('disabled', false);
                $("#entidadNacimientoDirectorio").append('<option value =' + registro.IDEstado + '>' + registro.Estado + '</option>');
            });
            if (data == '') {
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#entidadNacimientoDirectorio").prop('disabled', true);
            }
        }
    });
})

$("#formDatosDirectorio").on('submit', function (e) {
    e.preventDefault();
    fData = new FormData(this);
    var EstatusAlumno = $("#estatusAlumnoGeneraciones").val();
    if(EstatusAlumno==6){
        fData.set('estatusAlumnoDirectorio', 6)
    }

    fData.append('action', 'actualizarDirectorioAlumno');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data == 'ya_existe_generacion') {
                swal({
                    title: 'No se puede modificar la generación.',
                    icon: 'info',
                    text: 'Ya existe su registro en esa generación.',
                    button: false,
                    timer: 4200,
                });
            }
            try {
                pr = JSON.parse(data)
                if (pr.estatus == 'ok') {
                    //console.log(pr.estatus);
                    swal({
                        title: 'Actualizado correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500,
                    }).then((result) => {
                        if($("#table-alumnos-titulados").DataTable().ajax.url() !== null){
                            $("#table-alumnos-titulados").DataTable().ajax.reload(null, false);
                        }
                        $("#formDatosDirectorio")[0].reset();
                        $("#modalModificarDatosDirectorio").modal("hide");
                        tablaDirectorio(Band, false)
                    })
                }
            } catch (e) {
                console.log(e)
                console.log(data)
            }
        }
    })
})

$("#curpAlumnoDirectorio").keyup(function () {
    var curp = $("#curpAlumnoDirectorio").val();
    var curpF = curp;
    if (curp.length == 18) {
        curpFinal = curpF.slice(4, -8);
        var anio = curpFinal.substr(0, 2);
        var mes = curpFinal.substr(2, 2);
        var dia = curpFinal.substr(4, 2);
        var anyo = parseInt(anio) + 1900;
        if (anyo < 1950) anyo += 100;
        var mounth = parseInt(mes) - 1;
        var day = parseInt(dia);
        fechaFinal = new Date(anyo, mounth, day);
        let hoy = new Date();
        var edad = hoy.getFullYear() - fechaFinal.getFullYear();
        var m = hoy.getMonth() + 1 - fechaFinal.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fechaFinal.getDate())) {
            edad--;
        }
        $("#edadAlumnoDirectorio").val(edad);
    }
})

function checkTel(e) {
    tecla = (document.all) ? e.keycode : e.which;
    if (tecla == 8) { return true; }
    patron = /[0-9]/;
    tecla__final = String.fromCharCode(tecla);
    return patron.test(tecla__final);
}

$("#paisAlumnoDirectorio").on('change', function () {
    $("#estadoAlumnoDirectorio").empty();
    idPais = $("#paisAlumnoDirectorio").val();
    $.ajax({
        url: '../assets/data/Controller/controlescolar/crearCarrerasControl.php',
        type: 'POST',
        data: {
            action: "cargarEstadosDirectorio",
            idPais: idPais
        },
        dataType: 'JSON',
        success: function (data) {
            $("#estadoAlumnoDirectorio").html('<option selected="true" value="" disabled="disabled">Seleccione el Estado</option>');
            $.each(data, function (key, registro) {
                $("#estadoAlumnoDirectorio").prop('disabled', false);
                $("#estadoAlumnoDirectorio").append('<option value =' + registro.IDEstado + '>' + registro.Estado + '</option>');
            });
            if (data == '') {
                swal({
                    title: 'País sin estados',
                    icon: 'info',
                    text: 'Selecciona otro país, si es el caso.',
                    button: false,
                    timer: 3000,
                });
                $("#estadoAlumnoDirectorio").prop('disabled', true);
            }
        }
    });
})

$(".upper").on('change', function(){
    $(this).val($(this).val().toUpperCase());
})
