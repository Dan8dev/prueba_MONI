$(document).ready(function(){
    
});

function init_data_calificaciones(){
    carreras_profesor();
}
$("#calificaciones-tab").on('click',function(){
    carreras_profesor();
})

function carreras_profesor(){
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'listar_carreras_profesor'
        },
        success: function(data){
            try{
                var carreras = JSON.parse(data);
                var html = '<option disabled selected>Seleccione una carrera</option>';
                if(carreras.estatus == 'ok'){
                    for(var i in carreras.data){
                        html += `<option value="${carreras.data[i].idCarrera}">${carreras.data[i].nombre}</option>`;
                    }
                }
                $('#select-carreras').html(html);
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

$("#select-carreras").on('change',function(){
    var idCarrera = $(this).val();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: 'POST',
        data: {
            action: 'listar_generaciones_carrera',
            carrera: idCarrera
        },
        success: function(data){
            try{
                var generacion = JSON.parse(data);
                console.log(generacion);
                var html = '<option disabled selected>Seleccione una generación</option>';
                if(generacion.estatus == 'ok'){
                    for(var i in generacion.data.generaciones){
                        html += `<option value="${generacion.data.generaciones[i].idGeneracion}">${generacion.data.generaciones[i].nombre}</option>`;
                    }
                }
                $('#select-generacion').html(html);
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});
let info_ciclo = [];
$("#select-generacion").on('change',function(){
    info_ciclo = [];
    var idCarrera = $("#select-carreras").val()
    var idGeneracion = $(this).val();
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php?idCarrera='+idCarrera+'&idGeneracion='+idGeneracion,
        type: 'POST',
        data: {
            action: 'listar_alumnos_generacion'
        },
        success: function(data){
            try{
                var alumnos = JSON.parse(data);
                alumnos.plan_estudios['id_generacion'] = idGeneracion;
                info_ciclo = alumnos;
                alumns = alumnos.aaData;

                $("#select_ciclo").html(`<option disabled selected>Seleccione un ciclo</option>`);
                if(alumnos.plan_estudios && alumnos.plan_estudios.materias_ciclos){
                    for(m in alumnos.plan_estudios.materias_ciclos){
                        $("#select_ciclo").append(`<option value="${m}">Ciclo ${m}</option>`);
                    }
                }
            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
});

function ver_calificaciones(id, alumno){
    $("#lblNombreAlumnos").html(alumno);
    $("#modalVerCalificaciones").modal('show');
}
var ciclo = false;
$("#select_ciclo").on('change',function(){
    ciclo = $(this).val();
    if(info_ciclo.plan_estudios && info_ciclo.plan_estudios.materias_ciclos){
        $("#tabla_alumnos").DataTable();
        $("#tabla_alumnos").DataTable().destroy();
        $("#tabla_alumnos thead").html('')
        $("#tabla_alumnos tbody").html('')

        $("#tabla_alumnos thead").html(`
            <tr>
                <th>Alumno</th>
                ${info_ciclo.plan_estudios.materias_ciclos[ciclo].map(materia => `<th class="bg-muted align-middle">${materia.nombreMat}</th>`).join('')}
                <th></th>
            </tr>
        `);
        var promesa_calificaciones = new Promise(function(resolve, reject){

            arr_alumnos = info_ciclo.aaData.map(alumno => alumno[4]);
            arr_materias = info_ciclo.plan_estudios.materias_ciclos[ciclo].map(materia => materia.id_materia);
            $.ajax({
                url: '../assets/data/Controller/maestros/maestrosControl.php',
                type: 'POST',
                data: {
                    action: 'listar_calificaciones_alumnos',
                    id_alumnos: arr_alumnos,
                    id_materias: arr_materias,
                    id_generacion: info_ciclo.plan_estudios.id_generacion,
                    ciclo: ciclo
                },
                beforeSend: function(){
                    $("#tabla_alumnos tbody").html(`
                                <tr>
                                    <td colspan="${info_ciclo.plan_estudios.materias_ciclos[ciclo].length + 2}" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            `);
                },
                success: function(data){
                    try{
                        var calificaciones = JSON.parse(data);
                        $("#tabla_alumnos tbody").html('')
                        for(alumn in info_ciclo.aaData){
                            $("#tabla_alumnos tbody").append(`
                                <tr>
                                    <td>${info_ciclo.aaData[alumn][0]}</td>
                                    ${info_ciclo.plan_estudios.materias_ciclos[ciclo].map(function(materia){
                                        var calif = calificaciones.find( cal => cal.id_materia == materia.id_materia && cal.idProspecto == info_ciclo.aaData[alumn][4]);
                                        return `<td>
                                            <div class="row">
                                                <label class="col-1 col-form-label"><i style="cursor:pinter;" title="ver examenes y tareas" class="fas fa-info-circle" onclick="ver_desempenio(${info_ciclo.aaData[alumn][4]}, ${materia.id_materia}, ${info_ciclo.plan_estudios.id_plan_estudio})"></i></label>
                                                <div class="col-10">
                                                    <input class="form-control dinamic_input_calif" id="${info_ciclo.aaData[alumn][4]}_${materia.id_materia}" value="${calif ? calif.calificacion : ''}">
                                                </div>
                                            </div>
                                        </td>`}).join('')}
                                    <td><button class="btn btn-primary" onclick="guardar_calificaciones(this)">Guardar</button></td>
                                </tr>
                            `);
                        }
                        resolve();
                    }catch(e){
                        console.log(e);
                        console.log(data);
                    }
                }
            })
        });

        promesa_calificaciones.then(()=>{
            $("#tabla_alumnos").DataTable()
            $(".dinamic_input_calif").on('change', function(){
                var string = $(this).val().trim().replace(' ', '').toLocaleLowerCase();
                if(string == 's'){
                    $(this).val('Sin Calificación');
                }else if(string == 'n'){
                    $(this).val('N/C');
                }else{
                    var val = Math.abs(parseInt($(this).val() || 0) || 0);
                    $(this).val(val > 10 ? 10 : val);
                }
            })

            $(".dinamic_input_calif").on('click', function(){
                $(this).select();
            })
        });
        


    }
})

function ver_desempenio(alumno, materia, plan_estudios){
    $.ajax({
        url: '../assets/data/Controller/maestros/maestrosControl.php',
        type: "POST",
        data: {action:'consultar_desempenio', alumno: alumno, materia: materia, plan_estudios: plan_estudios},
        success: function(data){
            try{
                var resp = JSON.parse(data);
                if(resp.tareas.length > 0 || resp.examenes.length > 0){
                    var showresp = `
                        <tr>
                            <td colspan="2" class="bg-secondary text-white">Tareas</td>
                        </tr>
                        ${resp.tareas.map(tarea => `
                            <tr>
                                <td>${tarea.titulo}</td>
                                <td>${tarea.calificacion}</td>
                            </tr>
                        `).join('')}
                        <tr>
                            <td colspan="2" class="bg-secondary text-white">Exámenes</td>
                        </tr>
                        ${resp.examenes.map(examen => `
                            <tr>
                                <td>${examen.Nombre}</td>
                                <td>${parseFloat(examen.calificacion).toFixed(1)}%</td>
                            </tr>
                        `).join('')}
                        
                    `;
                    $("#tbl_calif_entregados").html(showresp);
                    $("#modalVerCalificaciones").modal('show')
                }else{
                    swal('No hay examenes o tareas entregadas');
                }

            }catch(e){
                console.log(e);
                console.log(data);
            }
        }
    });
}

function guardar_calificaciones(button){
    var calificaciones = {};
    message_confirm = '';
    $(button).parent().parent().find('input').each(function(){
        var key = $(this).attr('id');
        calificaciones[key] = $(this).val();
        materia = info_ciclo.plan_estudios.materias_ciclos[ciclo].find(elm => elm.id_materia == key.split('_')[1])
        message_confirm += `${materia.nombreMat.length > 40 ? materia.nombreMat.substr(0,40)+'...' : materia.nombreMat } : (${$(this).val()}) \n`;
    })
    swal({
        title: "¿Confirmar calificaciones?",
        text: message_confirm,
        icon: 'info',
        buttons: ["Cancelar", "Confirmar"]
    }).then(function(isConfirm){
        if(isConfirm){
            $.ajax({
                url: '../assets/data/Controller/maestros/maestrosControl.php',
                type: "POST",
                data: {action:'guardar_calificaciones', calificaciones: calificaciones, generacion: info_ciclo.plan_estudios.id_generacion, ciclo: ciclo},
                success: function(data){
                    try{
                        var resp = JSON.parse(data);
                        swal(resp.info)
                        console.log(resp);
                    }catch(e){
                        console.log(e);
                        console.log(data);
                    }
                }
            });
        }else{
            console.log('cancel');
        }
    });
}
