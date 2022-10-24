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
