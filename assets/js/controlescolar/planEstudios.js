$(document).ready( function(){
    initPlanEstudios();
    //obtenerCarrerasPlanEstMod();
})

function initPlanEstudios(){

    $("#btn-crear-planestudios").on("click",function(e){
        obtenerCarrerasPlanE();
        $("#divRvoeCrear").hide();
        $("#formPlanEstudios")[0].reset();

        function obtenerCarrerasPlanE(){
            $.ajax({
                url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
                type: 'POST',
                data: {action: 'obtenerCarreras'},
                dataType : 'JSON',
                success : function(data){
                    $("#selectCarreraPlanE").html('<option selected="true" value="" disabled="disabled">Seleccione</option>');
                    $.each(data, function(key, registro){
                        if(registro.id_institucion != 20){
                            $("#selectCarreraPlanE").append('<option data-info='+registro.tipo+' value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                        }else{
                            if(registro.tipo != 1){
                                $("#selectCarreraPlanE").append('<option data-info='+registro.tipo+' value='+registro.idCarrera+'>'+registro.nombre+'</option>');
                            }
                        }
                    });
                },
                error: function(xhr){
                    if(xhr.responseText == 'no_session'){
                        swal({
                            title: 'Vuelve a iniciar sesión!',
                            text: 'La informacion no se actualizó',
                            icon: 'info'
                        });
                        setTimeout(() => {
                            window.location.replace('index.php');
                        }, 2000);
                    }
                }
            });
            
        }
    })

    $("#tabplanestudios").on('click', function(){
        tPlanEst = $("#table-planestudios").DataTable({
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
                className: "btn-primary",
                title:'Planes_Estudio_'+new Date().toLocaleDateString().replace(/\//g, '-')

            /*}, {
                extend: "pdf"
            }, {
                extend: "print"*/
            }],
            "ajax":{
                url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
                type: 'POST',
                data: {action: 'obtenerPlanesEstudio'},
                dataType: "JSON",
                error: function(e){	
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
    })

}


$("#formPlanEstudios").on('submit', function(e){
    $("#selectCicloPlanE").prop('disabled', false);
    $("#numeroCiclosPlanE").prop('disabled', false);
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'crearPlanEstudios');
    fData.append('creado_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                if(pr.data != 'Clave_existente'){
                    swal({
                        title: 'Creado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500
                    }).then((result)=>{
                        $("#formPlanEstudios")[0].reset();
                        tPlanEst.ajax.reload(); 
                        $("#modalPlanEstudios").modal("hide");

                    })
                }else{
                    swal({
                        title: "La clave ya se encuentra registrada!",
                        text: "Intenta crear otra clave",
                        icon: "info",
                    }); 
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
            
        },
        complete : function(){
            $("#selectCicloPlanE").prop('disabled', true);
            $("#numeroCiclosPlanE").prop('disabled', true);
        }
    })
})


function buscarPlanEstudio(id){
    Data = {
        action: 'obtenerPlanEstudio',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        beforeSend: function() {
            var id_carrera = $("#modSelectCarreraPlanE").val();
            obtenerListaPlanEstudio(id_carrera);
        },
        success : function(data){
            try{
                pr = JSON.parse(data)
                
                if(pr.plan_ref != null){
                    $("#PlanReferenciaNoneMod").removeClass("d-none");  
                    $("#PlanReferenciaMod").val(pr.plan_ref);
                    $("#selecTipoPlanMod").val('1');
                }else{
                    $("#selecTipoPlanMod").val('null');
                }

                obtenerCarrerasPlanEstMod(pr.id_carrera);
                validarCarreraCertificacionMod(pr.id_carrera);
                if(pr.tipo_rvoe!=0){
                    $("#divRvoe").show();
                }else{
                    $("#divRvoe").hide();
                }
                $("#tipoRvoe").val(pr.tipo_rvoe);
                $("#rvoePlanEstudios").val(pr.rvoe);
                $("#FecharvoePlanEstudiosEditar").val(pr.fecha_registro_rvoe);
                $("#modSelectCarreraPlanE").val(pr.id_carrera);
                $("#modNombrePlanE").val(pr.nombre);
                $("#modClavePlanE").val(pr.clave_plan);
                $("#modSelectCicloPlanE").val(pr.tipo_ciclo);
                $("#modNumeroCiclosPlanE").val(pr.numero_ciclos);
                $("#id_plan_estudio").val(pr.id_plan_estudio);
                $("#claveSepAntPlan").val(pr.rvoe);
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function validarCarreraCertificacionMod(id_carrera){
$.ajax({
    url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
    type: 'POST',
    data: {
        action: 'validarCarreraCertificacionMod',
        idCarr: id_carrera
    },
    success : function(data){
        try{
            pr = JSON.parse(data)
                if(pr.tipo == '1'|| pr.tipo == '3'){
                $("#modSelectCicloPlanE").prop('disabled', true);
                $("#modNumeroCiclosPlanE").prop('disabled', true);
            }else{
                $("#modSelectCicloPlanE").prop('disabled', false);
                $("#modNumeroCiclosPlanE").prop('disabled', false);
            }
        }catch(e){
            console.log(e)
            console.log(data)
        }
    }
});
}

$("#btnModPlanE").on('click', function(){
    $("#formPlanEstudios")[0].reset();
    $("#modalModPlanE").modal('hide');
})

$("#ocultarPlanEstudio").on('click', function(){
    $("#formPlanEstudios")[0].reset();
    $("#modalPlanEstudios").modal('hide');
});


$("#modalPlanEstudios").on("hidden.bs.modal", function () {
    $("#PlanReferenciaNone").addClass("d-none");
    $("#PlanReferencia").val();
    $("#selecTipoPlan").val("null");
});

function obtenerListaPlanEstudio(id_carrera){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: {action: 'obtenerListaPlanEstudio',
               idCarr: id_carrera},
        dataType : 'JSON',
        success : function(data){
            if(data.length == 0){
                $("#PlanReferencia").html('<option value = "" disabled="disabled" selected>Sin Planes Asignados</option>');
            }else{
                $("#PlanReferencia").html('<option value = "" disabled="disabled" selected>Seleccione</option>');
                $("#PlanReferenciaMod").html('<option value = "" disabled="disabled" selected>Seleccione</option>');
                $.each(data, function(key, registro){
                    $("#PlanReferencia").append('<option value='+registro.id_plan_estudio+'>'+registro.nombre+'</option>');
                    $("#PlanReferenciaMod").append('<option value='+registro.id_plan_estudio+'>'+registro.nombre+'</option>');
                });
            }
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: 'Vuelve a iniciar sesión!',
                    text: 'La informacion no se actualizó',
                    icon: 'info'
                });
                setTimeout(() => {
                    window.location.replace('index.php');
                }, 2000);
            }
        }
    });
    
}

function obtenerCarrerasPlanEstMod(id_carrera){
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: {action: 'obtenerCarrerasMod',
               idCarr: id_carrera},
        dataType : 'JSON',
        success : function(data){
            $("#modSelectCarreraPlanE").html('<option value="" disabled="disabled">Seleccione</option>');
            $.each(data, function(key, registro){
                $("#modSelectCarreraPlanE").append('<option value='+registro.idCarrera+'>'+registro.nombre+'</option>');
            });
        },
        error: function(xhr){
            if(xhr.responseText == 'no_session'){
                swal({
                    title: 'Vuelve a iniciar sesión!',
                    text: 'La informacion no se actualizó',
                    icon: 'info'
                });
                setTimeout(() => {
                    window.location.replace('index.php');
                }, 2000);
            }
        }
    });
    
}

$("#formModPlanEstudio").on('submit', function(e){
    $("#modSelectCicloPlanE").prop('disabled', false);
    $("#modNumeroCiclosPlanE").prop('disabled', false);
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'modificarPlanEstudios');
    fData.append('modificado_por', usrInfo.idAcceso);
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: "Vuelve a iniciar sesión!",
                    text: "La informacion no se actualizó",
                    icon: "info",
                });
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Modificado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500
                    }).then((result)=>{
                        $("#formModPlanEstudio")[0].reset();
                        tPlanEst.ajax.reload(); 
                        $("#modalModPlanE").modal("hide");
                    })
                }else{
                    if(pr.estatus == 'clave_existente'){
                        swal({
                            title: 'Clave Repetida',
                            icon: 'error',
                            text: 'Intente ingresar otra clave...',
                            button: false,
                            timer: 2000
                        })
                    }
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        },
        complete : function (){
            $("#modSelectCicloPlanE").prop('disabled', true);
            $("#modNumeroCiclosPlanE").prop('disabled', true);
        }
    });
})

function validarEliminarPlanEstudio(id){
  Swal.fire({
      text: '¿Estas seguro de eliminarlo?',
      type: 'info',
      customClass: 'myCustomClass-info',
      showCancelButton: true,
      confirmButtonColor: '#AA262C',
      confirmButtonText: 'Aceptar',
      cancelButtonColor: '#767575',
      cancelButtonText: 'Cancelar'
  }).then(result=>{
      if(result.value){
          eliminarPlanEstudios(id);
      }else{
          swal("Cancelado Correctamente");
      }
  })
}

function eliminarPlanEstudios(id){
    Data = {
        action: 'eliminarPlanEstudios',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: 'Vuelve a iniciar sesión!',
                    text: 'La información no se actualizó',
                    icon: 'info'
                });
                setTimeout(() => {
                        window.location.replace('index.php');
                }, 2000);
            }
            try{
                //data != 'no_session'
                pr = JSON.parse(data)
                if(pr.estatus == 'ok' && data != 'no_session'){
                    swal({
                        title: 'Eliminado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500
                    }).then(result=>{
                        tPlanEst.ajax.reload();
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}

$("#btnCerrarPlanE").on('click', function(){
    $("#modalAsigMaterias").modal('hide');
})

function asignarPlanEstudio(id){
    Data = {
        action: 'buscarPlanEstudios',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        success: function(data){
            if(data == 'no_session'){
                swal({
                    title: 'Vuelve a iniciar sesión!',
                    text: 'La información no se actualizó',
                    icon: 'info'
                });
                setTimeout(() => {
                        window.location.replace('index.php');
                }, 2000);
            }
            try{
                $('#namePlanE').empty();
                $("#avisoPlanE").empty();
                $('#divAsignar').empty();
                //$("#divAsignar").empty();
                pr = JSON.parse(data)
                $("#idPlanEstudioAsigMat").val(pr.id_plan_estudio)
                var numeroTotal = pr.numero_ciclos;
                var Ciclo;
                var idCarr = pr.id_carrera;
                var tipoCarrera = pr.tipo;
                var plan_ref = pr.plan_ref;

                if(pr.tipo_ciclo == 1){
                    Ciclo = 'Cuatrimestre';
                }
                if(pr.tipo_ciclo == 2){
                    Ciclo = 'Semestre';
                }
                if(pr.tipo_ciclo == 3){
                    Ciclo = 'Trimestre';
                }

                $("#namePlanE").append($('<h3><strong><label>').
                                text(pr.nombre)).append('</label></strong></h3>');

                $("#avisoPlanE").append($('<h4 style="color: #4478AA"><label>').
                                text("Puedes seleccionar múltiples materias de la lista mostrada, cada una de estas materias seleccionadas se guardarán al dar clic en \"Guardar Materias\". Recuerda que la asignación de materias es un ciclo a la vez.")).append('</label></h4>');
                
                for(var j=1 ; j <= numeroTotal ; j++){
                    obtenerMateriasAsignadasPlan(id, j);
                    if(tipoCarrera != 1){
                        obtenerMateriasSinAsignar(j, idCarr,plan_ref, id);
                    }else{
                        obtenerMateriasSinAsignarConacon(j, idCarr, id);
                    }

                    $('#divAsignar').append($('<h5><strong><label>').
                                        text(Ciclo+': '+ j)).append('</label></strong><br></h5>');

                    $("#divAsignar").append($('<div>').
                                        attr('class', 'table-responsive text-center col-lg-12 col-sm-12 col-md-12').
                                        append($('<table>').
                                        attr('class', 'table text-center dt-responsive').
                                        attr('id', 'tableAsignacionMaterias'+j).
                                        attr('style', 'border-collapse: collapse; width: 100%;').
                                        append($('<thead>').
                                        append($('<tr>').
                                        append($('<th></th>')).
                                        append($('<th>Materia</th>')).
                                        append($('<th>Clave</th>')).
                                        append($('<th>Tipo de Asignatura</th>')).
                                        append($('<th>Número de Créditos</th>')).
                                        append($('<th>Eliminar</th>')).
                                        append('</tr>')).
                                        append('</thead>')).
                                        append($('<tbody>')).
                                        append('</tbody>').
                                        append('</table>'))).
                                        append('</div>');

               

                    $('#divAsignar').append($('<select>').
                                        attr('class', "fa form-control border border-secondary").
                                        attr('data-none-selected-text', "Selecciona las materias a asignar").
                                        attr('name', 'asigMaterias'+j+'[]').
                                        attr('id', 'asigMaterias'+j).
                                        attr('multiple','')).append('</select><br><br>');

                    $('#divAsignar').append($('<button>').
                                        attr("class", "btn btn-primary btn waves-effect waves-light mr-3 mb-3").
                                        attr("type", "submit").
                                        attr("id", "btnGuardarMateriasAsig"+j).
                                        text('Guardar Materias')).append('</button>');

                    /*$('#divAsignar').append($('<button>').
                                        attr("class", "btn btn-primary btn waves-effect waves-light mr-3 mb-3").
                                        attr("type", "button").
                                        attr("id", "btnAsigMateria"+j).
                                        attr('onclick', 'vistaAsignar(id,'+idCarr+','+numeroTotal+','+tipoCarrera+','+id+')').
                                        text('Asignar Materia - '+Ciclo+' '+j)).append('</button>');*/

                    /*$('#divAsignar').append($('<button>').
                                        attr("class", "btn btn-secondary btn waves-effect waves-light mr-3 mb-3").
                                        attr("type", "button").
                                        attr("id", "btnNoAsig"+j).
                                        attr('onclick', 'vistaNoAsignar(id,'+numeroTotal+')').
                                        text('Cancelar')).append('</button><br>');*/

                }

                /*
                for(x in numeroTotal){
                    $('#divAsignar').append($('<label>').
                                        text('Ciclo número: '+pr.numero_ciclos))
                }*/

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
/*
function vistaAsignar(id, id_carrera, numeroT, tipoCarrera, idPlan){
    this.idPlan;
    this.id;
    this.id_carrera;
    this.numeroT;
    let textArrayBtn = id.split('btnAsigMateria');
    let union = textArrayBtn.join();
    var idfinal = union.replace(/^,|,$/g,'');
    $("#asigMaterias"+idfinal).show();
    $("#btnAsigMateria"+idfinal).hide();

    for(var k=1; k <= numeroT; k++){
        if(idfinal==k){
            $("#btnAsigMateria"+k).prop('disabled',false);
            $("#btnNoAsig"+k).prop('disabled', false);
        }else{
            $("#btnAsigMateria"+k).prop('disabled',true);
            $("#btnNoAsig"+k).prop('disabled', true);
        }
    }
    if(tipoCarrera!=1){
        $("#btnGuardarMateriasAsig"+idfinal).show();
        obtenerMateriasSinAsignar(idfinal, id_carrera, idPlan);
    }else{
        $("#btnGuardarMateriasAsig"+idfinal).show();
        obtenerMateriasSinAsignarConacon(idfinal, id_carrera, idPlan);
    }

}*/

/*function vistaNoAsignar(id, numeroT){
    this.id;
    this.numeroT;
    let textArrayBtn = id.split('btnNoAsig');
    let union = textArrayBtn.join();
    var idfinal = union.replace(/^,|,$/g,'');
    //$("#asigMaterias"+idfinal).hide();
    $("#asigMaterias"+idfinal).selectpicker('hide');
    $("#asigMaterias"+idfinal).val('');

    $("#btnAsigMateria"+idfinal).show();
    $("#btnGuardarMateriasAsig"+idfinal).hide();

    for(var k = 1; k <= numeroT; k++){
        $("#btnAsigMateria"+k).prop('disabled',false);
        $("#btnNoAsig"+k).prop('disabled', false);    
        //$("#btnAsigMateria"+k).show();
        //$("#btnGuardarMateriasAsig"+k).hide();
        //$("#asigMaterias"+k).hide();
    }
}*/

//selectpicker
function obtenerMateriasSinAsignar(id, id_carrera, plan_ref,idPlanE){
    $("#asigMaterias"+id).selectpicker('show');
    $("#btnGuardarMateriasAsig"+id).prop('disabled', false);
    //$("#asigMaterias"+id).show();
    //$("#asigMaterias"+id).empty();
    //$("#asigMaterias"+id).destroy();
    Data = {
        action: 'obtenerMateriasSinAsignar',
        idCarr: id_carrera,
        idPlan: idPlanE,
        planref: plan_ref
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){

                    $.each(data, function(key, registro){
                        var icono = registro.oficial == '1' ? '&#Xf00c; ':'&#Xf00d; ';
                        $("#asigMaterias"+id).append('<option class = "fa" value='+registro.id_materia+'>'+icono+registro.nombre+'</option>');
                        $("#asigMaterias"+id).selectpicker('refresh');
                    });
        },
        error : function(xhr){
            if(xhr.responseText == 'no_materias'){
                if(id==1){
                    Swal.fire({
                        title: 'No hay materias registradas',
                        text: 'Se necesitan dar de alta en el módulo de materias.',
                        type: 'info',
                        customClass: 'myCustomClass-info',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
                
                $("#asigMaterias"+id).selectpicker('hide');
                $("#btnGuardarMateriasAsig"+id).prop('disabled', true);
            }
        }
    });
}

$("#selecTipoPlan").on("change",function(e){
    if($(this).val()==1){
        $("#PlanReferencia").prop("required",true);
        $("#PlanReferenciaNone").removeClass("d-none");
        id_carrera = $("#selectCarreraPlanE").val(); 
        obtenerListaPlanEstudio(id_carrera);
    }else{
        $("#PlanReferencia").prop("required",false);
        $("#PlanReferenciaNone").addClass("d-none");
    }
});



function obtenerMateriasSinAsignarConacon(id, id_carrera, idPlanE){
    $("#asigMaterias"+id).selectpicker('show');
    $("#btnGuardarMateriasAsig"+id).prop('disabled', false);
    Data = {
        action: 'obtenerMateriasSinAsignarConacon',
        idCarr: id_carrera,
        idPlan: idPlanE
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        dataType: 'JSON',
        success : function(data){

                    $.each(data, function(key, registro){
                        $("#asigMaterias"+id).append('<option value='+registro.id_materia+'>'+registro.nombre+'</option>');
                        $("#asigMaterias"+id).selectpicker('refresh');
                    });
        },
        error : function(xhr){
            if(xhr.responseText == 'no_materias'){
                Swal.fire({
                    title: 'No hay materias registradas',
                    text: 'Se necesitan dar de alta en el módulo de materias.',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3000
                });
                $("#asigMaterias"+id).selectpicker('hide');
                $("#btnGuardarMateriasAsig"+id).prop('disabled', true);
            }
        }
    });
}

function obtenerMateriasAsignadasPlan(id_plan_estudio, numero_ciclo){
    this.id_plan_estudio;
    this.numero_ciclo;
    Data = {
        action: 'obtenerMateriasAsignadasPlan',
        planEst: id_plan_estudio,
        numCiclo: numero_ciclo
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            try{
                pr = JSON.parse(data)
                var f = 1;
                var tipoC;
                
                var numObj= Object.keys(pr).length
                //console.log(numObj)
            
                for(var y = 0 ;y < numObj ; y++){
                    if(numObj != 0){
                        if(numero_ciclo == pr[y].ciclo_asignado){

                            switch(pr[y].tipoMat){
                                case '1':
                                    tipoC = 'Adicional';
                                    break;
                                case '2':
                                    tipoC = 'Área';
                                    break;
                                case '3':
                                    tipoC = 'Complementaria';
                                    break;
                                case '4':
                                    tipoC = 'Obligatoria';
                                    break;
                                case '5':
                                    tipoC = 'Optativa';
                                    break;
                                default:
                                    tipoC = '';
                                    break;
                            }

                            $("#tableAsignacionMaterias"+numero_ciclo+" tbody").append('<tr><td>'+f+'</td><td>'+
                                                                                pr[y].nombreMat+'</td><td>'+
                                                                                pr[y].claveMat+'</td><td>'+
                                                                                tipoC+'</td><td>'+
                                                                                pr[y].creditosMat+'</td><td><button type="button" class="btn-primary" onclick="validarBorrarMateria('+pr[y].id_asignacion+','+"tableAsignacionMaterias"+numero_ciclo+','+id_plan_estudio+')">Borrar <i class="fas fas fa-trash-alt"></button></td></tr>');
                            f++;
                        }
                    }
                }

            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
}

function validarBorrarMateria(id, nomTabla, id_plan_estudio){
    Swal.fire({
        type: 'info',
        text: '¿Estas seguro de eliminarlo?',
        customClass: 'myCustomClass-info',
        showCancelButton: true,
        confirmButtonColor: '#AA262C',
        confirmButtonText: 'Aceptar',
        cancelButtonColor: '#767575',
        cancelButtonText: 'Cancelar'
    }).then(result=>{
        if(result.value){
            borrarMateria(id, nomTabla, id_plan_estudio);
        }else{
            swal("Cancelado Correctamente");
        }
    })
}

function borrarMateria(id, nomTabla, id_plan_estudio){
    this.nomTabla;
    Data = {
        action: 'borrarMateria',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data == 'no_session'){
                swal({
                    title: 'Vuelve a iniciar sesión!',
                    text: 'La información no se actualizó',
                    icon: 'info'
                });
                setTimeout(() => {
                        window.location.replace('index.php');
                }, 2000);
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok' && data != 'no_session'){
                    swal({
                        title: 'Eliminado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500
                    }).then(result=>{
                        asignarPlanEstudio(id_plan_estudio);
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    })
}

$("#formAsigPlanEstudio").on('submit', function(e){
    e.preventDefault();
    fData = new FormData(this);
    fData.append('action', 'guardarMateriasAsignadas');
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: fData,
        contentType: false,
        processData: false,
        success : function(data){
            if(data == 'asig_vacio'){
                Swal.fire({
                    title: 'No seleccionaste ninguna materia',
                    text: 'Por favor, elige una materia',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3000
                })
            }
            if(data == 'vaciar_un_campo'){
                Swal.fire({
                    title: 'La asignación es ciclo por ciclo',
                    text: 'Por favor, solo selecciona las materias de un solo ciclo',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 4200
                })
            }
            try{
                pr = JSON.parse(data)
                if(pr.estatus == 'ok'){
                    swal({
                        title: 'Asignado Correctamente',
                        icon: 'success',
                        text: 'Espere un momento...',
                        button: false,
                        timer: 2500
                    }).then(result=>{
                        asignarPlanEstudio(pr.data.id_plan);
                    })
                }
            }catch(e){
                console.log(e)
                console.log(data)
            }
        }
    });
})

function verPlanEstudio(id){
    Data = {
        action: 'validarCrearPDFPlanEstudios',
        id: id
    }
    $.ajax({
        url: '../assets/data/Controller/controlescolar/planEstudiosControl.php',
        type: 'POST',
        data: Data,
        success : function(data){
            if(data == 'falta_asignar'){
                Swal.fire({
                    title: 'Faltan asignar materias en algunos ciclos',
                    text: 'Por favor, revisa que cada ciclo cumpla su asignación de materias',
                    type: 'info',
                    customClass: 'myCustomClass-info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3500
                })
            }
            if(data == 'asignado'){

                window.open('plan_estudio.php?id_plan='+id, '_blank');

            }
        }
    });
}

$("#selectCarreraPlanE").on('change', function (){
    $("#selecTipoPlan").removeAttr("disabled");
    $("#selectCarreraPlanE option:checked").each(function(){
        valTipoCarrera = $(this).attr("data-info");
    });

    //var valTipoCarrera = $("#selectCarreraPlanE").attr("data-info");
    //obtenerPlanCertificacion(valCarreraPE);

    if(valTipoCarrera == 3){
        $("#selectCicloPlanE").val(1);
        $("#selectCicloPlanE").prop('disabled', true);
        $("#numeroCiclosPlanE").val(1);
        $("#numeroCiclosPlanE").prop('disabled', true);
    }else{
    if(valTipoCarrera == 1){
        $("#selectCicloPlanE").val(3);
        $("#selectCicloPlanE").prop('disabled', true);
        $("#numeroCiclosPlanE").val(1);
        $("#numeroCiclosPlanE").prop('disabled', true);
    }else{
        $("#selectCicloPlanE").prop('disabled', false);
        $("#numeroCiclosPlanE").prop('disabled', false);
        $("#selectCicloPlanE").val('');
        $("#numeroCiclosPlanE").val('');
    }
    }
});


$("#tipoRvoe").on('change', function(){
    var tRvoe = $("#tipoRvoe").val();
    if(tRvoe!=0 && tRvoe != undefined){
        $("#divRvoe").show();
        $("#rvoePlanEstudios").attr('required','');
        $("#FecharvoePlanEstudiosEditar").attr('required','');
    }else{
        $("#divRvoe").hide();
        $("#rvoePlanEstudios").removeAttr('required');
        $("#FecharvoePlanEstudiosEditar").removeAttr('required');

        $("#rvoePlanEstudios").val('');
        $("#FecharvoePlanEstudiosEditar").val("");
    }
})


$("#tipoRvoeCrear").on('change', function(){
    var tRvoeCrear = $("#tipoRvoeCrear").val();
    if(tRvoeCrear!=0){
        $("#divRvoeCrear").show();
        $("#rvoePlanEstudiosCrear").attr('required', '');
        $("#FecharvoePlanEstudiosCrear").attr('required', '');
    }else{
        $("#divRvoeCrear").hide();
        $("#rvoePlanEstudiosCrear").removeAttr('required');
        $("#FecharvoePlanEstudiosCrear").removeAttr('required');
        
        $("#rvoePlanEstudiosCrear").val('');
        $("#FecharvoePlanEstudiosCrear").val('');
    }
})
