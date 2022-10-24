/**
 * Template Name: Hexzy - Bootstrap 4 Admin Dashboard
 * Datatable
 */
 let lemnt = null;

!function($) {
    "use strict";

    var DataTable = function() {
        this.$dataTableButtons = $(".TBNR table")
    };
    DataTable.prototype.createDataTableButtons = function() {
        0 !== this.$dataTableButtons.length && this.$dataTableButtons.DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: "excel",
                className: "btn-primary"
            }, {
                extend: "print"
            }],
            responsive: !0,
            //"columnDefs": [
            //  { className: "truncate", "targets": "_all" }
            //],
            'createdRow': function (row, data, rowIndex) {
                // Per-cell function to do whatever needed with cells
                $.each($('td', row), function (colIndex) {
                    if($(this).children().length > 0 && $(this).children().find("a").length > 0 && $(this).children().find("a").hasClass("clpb")){
                        $(this).attr('title', 'Clic para copiar');
                    }else{
                        if($(this).children().length == 0){
                            $(this).attr('title', $(this).text());
                        }
                    }
                });
              },
			"language":{
                "sProcessing":     "Procesando...",
                "lengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "\n(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
			},
            "order": [],
            "pageLength": 50,
            initComplete: function (e) {
                // var api = this.api();
                // lemnt = api;
                // var ctxTableId = api.table().context[0].nTable.id;
                // reload_sel(ctxTableId);
            }
        });
    },
    DataTable.prototype.init = function() {
        //creating demo tabels
        // $('#datatable').dataTable();
        // $('#datatable-keytable').DataTable({keys: true});
        // $('#datatable-responsive').DataTable();
        // $('#datatable-scroller').DataTable({
        //     ajax: "assets/plugins/datatables/json/scroller-demo.json",
        //     // deferRender: true,
        //     scrollY: 380,
        //     scrollCollapse: true,
        //     scroller: true
        // });
        var table = $('#datatable-fixed-header').DataTable();

        //creating table with button
        this.createDataTableButtons();
    },
    //init
    $.DataTable = new DataTable, $.DataTable.Constructor = DataTable
}(window.jQuery),

//initializing
function ($) {
    "use strict";
    $.DataTable.init();
}(window.jQuery);

$.fn.dataTable.Api.register('reload_selects()', function (e) {
    reload_sel(this.table().context[0].nTable.id);
});

function reload_sel(table_id){
    // var api = elm.api();
    // var ctxTableId = api.context[0].nTable.attributes[1].nodeValue;
    var ctxTableId = table_id;
    api = $(`#${ctxTableId}`).DataTable();
    $(`#${ctxTableId}_selects_space`).remove();
    $(`#${ctxTableId}`).parent().prepend(`<div class="row" id="${ctxTableId}_selects_space"> </div>`);

    api.columns().eq(0).each(function(colix){
        var node_head = $(api.column(colix).header());
         if(node_head.attr('for-filter') !== undefined){
            var options_filt = [];
            for(var irow = 0; irow < api.rows()[0].length; irow++){
                if(!options_filt.includes(api.cell(irow, colix).data())){
                    // if(ctxTableId == 'concentrado-alumnos' && colix == 2){
                    //     lemnt = api.cell(irow, colix)
                    // }
                    if($(api.cell(irow, colix).node()).find('li').length > 0){
                        $(api.cell(irow, colix).node()).find('li').each(function(){
                            if(!options_filt.includes($(this).text().trim())){
                                options_filt.push($(this).text().trim());
                            }
                        })
                    }else{
                        options_filt.push(api.cell(irow, colix).node().innerText.trim());
                    }
                }
            }
            options_filt.sort(function(a, b){
                if(a < b) { return -1; }
                if(a > b) { return 1; }
                return 0;
            })
            var options_html = `<option value="">Seleccione para filtrar ${node_head.html()}</option>`;
            options_html+=options_filt.map((elm, ix) => `<option value="${ix}">${elm}</option>`).join('')

            $(`#${ctxTableId}_selects_space`).append(
                `<div class="col-sm m-2 p-2 border rounded">
                    <label>Seleccione para filtrar ${node_head.html()}</label>
                    <select class="form-control autoselect_filter border" column_index="${colix}" id="select_${ctxTableId}_filter_col_${colix}">${options_html}</select>
                </div>`);
         }
    });

    $(".autoselect_filter").on('change', function(){
        var search = $(this).find("option:selected").text();
        var vaal = $(this).find("option:selected").val();
        if(vaal == ''){
            search = '';
        }
        api.column(parseInt($(this).attr('column_index')))
            .search( search != '' ? search : '').draw();
    });
}