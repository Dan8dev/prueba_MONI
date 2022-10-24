$(document).ready(()=>{
    consultar_validaciones()
});

function consultar_validaciones(){
    $.ajax({
        type: "POST",
        url: "../assets/data/Controller/clinicas/clinicasControl.php",
        data: {action:'consultar_validaciones'},
        dataType: "JSON",
        success: function (response) {
            $("#table_pendientes").DataTable().clear();
            for(var p in response){
                var pros = response[p];

                var string_docs = '';
                if(pros.docs.length == 0){
                    string_docs = '<i>Sin documentos entregados.</i>';
                }else{
                    pros.docs.forEach(element => {
                        if(element.verificacion !== null){
                            string_docs += `<li>${element.nombre_documento} <a target="_blank" href="../siscon/app/lista_documentos/${element.id_prospectos}/${element.nombre_archivo}"><i class="fas fa-eye"></i></a></li>`;
                        }
                    });
                    if(string_docs != ''){
                        string_docs = `<ul>${string_docs}</ul>`;
                    }else{
                        string_docs = '<i>Sin documentos entregados.</i>';
                    }
                }

                $("#table_pendientes").DataTable().row.add([
                    `${pros.aPaterno} ${pros.aMaterno} ${pros.nombre}`,
                    string_docs,
                    `<button class="btn btn-primary" onclick="revision_documentos(${pros.id_afiliado})"> Revisión </button>`
                ]);
            }
            $("#table_pendientes").DataTable().draw();
        }
    });
}

/*
    <div>
        <select class="form-control" onchange="actualiza_verificacion(${pros.id_prospecto}, this)">
            <option value="pendiente" selected>Pendiente</option>
            <option value="rechazado">Rechazado</option>
            <option value="verificado">Verificado</option>
        </select>
    </div>
*/

function revision_documentos(afiliado){
    $.ajax({
        type: "POST",
        url: "../siscon/app/data/CData/documentosControl.php",
        data: {action:'VerificarDocumentosAlumno', idAlum : afiliado},
        dataType: "JSON",
        success: function (response) {
            $("#form-revision-docs").html('');
            var pendientes = 0;
            for(var dc in response){
                var docum = response[dc];
                if(docum.verificacion == 1){
                    var show_string = '';
                    if(docum.validacion_mk == 0){ 
                        show_string = `<select class="form-control" name="valid_document_${docum.id}">
                            <option value="pendiente" selected>Pendiente</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="verificado">Verificado</option>
                        </select>`;
                        pendientes++;
                    }else{
                        show_string = docum.validacion_mk == 1 ? 'verificado' : 'rechazado';
                    }

                    $("#form-revision-docs").append(`
                        <div class="row mx-3 mb-2 pb-1 border-bottom">
                            <div class="col">
                                ${docum.nombre_documento}
                            </div>
                            <div class="col">
                                ${show_string}
                            </div>
                        </div>
                    `);
                }
            }
            if(pendientes > 0){
                $("#form-revision-docs").prepend(`<`);
                $("#form-revision-docs").append(`
                    <div class="row mx-3 text-right">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </div>
                `);
            }

            $("#modalRevision_docs").modal('show');
        }
    });
}

function actualiza_verificacion(prosp, node){
    if($(node).val() != 'pendiente'){
        swal({
            icon:'info',
            title:'¿Desea cambiar estatus?',
            text:'Cambiará el estatus de la solicitud a '+$(node).val(),
            buttons:['Cancelar', 'Aceptar']
        }).then(val => {
            if(val){

            }else{
                $(node).val('pendiente');
            }
        })
    }else{
    }
}