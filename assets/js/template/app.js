/*
 Template Name: Hexzy - Responsive Bootstrap 4 Admin Dashboard
 Author: ThemeDesign
 File: Main js
 */
$(".onlyNum").on('keypress',function(evt){
    if (evt.which < 46 || evt.which > 57){
        evt.preventDefault();
    }
})
const currencyF = { style: 'currency', currency: 'USD' };
const moneyFormat = new Intl.NumberFormat('en-US', currencyF);

const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

!function($) {
    "use strict";

    var MainApp = function() {
        this.$btnFullScreen = $("#btn-fullscreen")
    };

    //full screen
    MainApp.prototype.initFullScreen = function () {
        var $this = this;
        $this.$btnFullScreen.on("click", function (e) {
            e.preventDefault();

            if (!document.fullscreenElement && /* alternative standard method */ !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        });
    },

    MainApp.prototype.initNavbar = function () {

        $('.navbar-toggle').on('click', function (event) {
            $(this).toggleClass('open');
            $('#navigation').slideToggle(400);
        });

        $('.navigation-menu>li').slice(-1).addClass('last-elements');

        $('.navigation-menu li.has-submenu a[href="#"]').on('click', function (e) {
            if ($(window).width() < 992) {
                e.preventDefault();
                $(this).parent('li').toggleClass('open').find('.submenu:first').toggleClass('open');
            }
        });
    },

    // === fo,llowing js will activate the menu in left side bar based on url ====
    MainApp.prototype.initMenuItem = function () {
        $(".navigation-menu a").each(function () {
            var pageUrl = window.location.href.split(/[?#]/)[0];
            if (this.href == pageUrl) { 
                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().parent().addClass("active"); // add active class to an anchor
                $(this).parent().parent().parent().parent().parent().addClass("active"); // add active class to an anchor
            }
        });
    },
    MainApp.prototype.initComponents = function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    },
    MainApp.prototype.initslimscrollleft = function () {

         //SLIM SCROLL
      $('.slimscroller').slimscroll({
        height: 'auto',
        size: "5px",
        color: '#dee4e8'
      });

        $('.slimscrollleft').slimScroll({
            height: 'auto',
            position: 'right',
            size: "7px",
            color: '#dee4e8',
            wheelStep: 5
        });
    },

    MainApp.prototype.init = function () {
        this.initFullScreen();
        this.initNavbar();
        this.initMenuItem();
        this.initComponents();
        this.initslimscrollleft();
    },

    //init
    $.MainApp = new MainApp, $.MainApp.Constructor = MainApp
}(window.jQuery),

//initializing
function ($) {
    "use strict";
    $.MainApp.init();
}(window.jQuery);


function selects_datatable(table_id, reloadSelects = true){
    // var api = elm.api();
    // var ctxTableId = api.context[0].nTable.attributes[1].nodeValue;
    var ctxTableId = table_id;
    if(!reloadSelects && $(`#${ctxTableId}_selects_space`).length > 0){
        return;
    }
    api = $(`#${ctxTableId}`).DataTable();
    $(`#${ctxTableId}_selects_space`).remove();
    $(`#${ctxTableId}`).parent().prepend(`<div class="row" id="${ctxTableId}_selects_space"> </div>`);

    api.columns().eq(0).each(function(colix){
        var node_head = $(api.column(colix).header());
         if(node_head.attr('for-filter') !== undefined){
            var options_filt = [];
            for(var irow = 0; irow < api.rows()[0].length; irow++){
                if(!options_filt.includes(api.cell(irow, colix).node().innerText.trim())){
                    // if(ctxTableId == 'concentrado-alumnos' && colix == 2){
                    //     lemnt = api.cell(irow, colix)
                    // }
                    if($(api.cell(irow, colix).node()).find('li').length > 0){
                        $(api.cell(irow, colix).node()).find('li').each(function(){
                            if(!options_filt.includes($(this).text().trim()) && $(this).text().trim() != ''){
                                options_filt.push($(this).text().trim());
                            }
                        })
                    }else{
                        if(api.cell(irow, colix).node().innerText.trim() != ''){
                            options_filt.push(api.cell(irow, colix).node().innerText.trim());
                        }
                    }
                }
            }
            options_filt.sort(function(a, b){
                if(a < b) { return -1; }
                if(a > b) { return 1; }
                return 0;
            })
            // limpiar filtros
            api.column(colix).search( '', false).draw();
            var options_html = `<option value="">Seleccione para filtrar ${node_head.html()}</option>`;
            options_html+=options_filt.map((elm, ix) => `<option value="${ix}">${elm}</option>`).join('')
            var strict_search = (node_head.attr('strict') !== undefined);
            $(`#${ctxTableId}_selects_space`).append(
                `<div class="col-sm m-2 p-2 border rounded">
                    <label>Seleccione para filtrar ${node_head.html()}</label>
                    <select class="form-control autoselect_filter border" ${strict_search ? 'strict' : ''} column_index="${colix}" id="select_${ctxTableId}_filter_col_${colix}" parent="${ctxTableId}">${options_html}</select>
                </div>`);
         }
    });
    $(".autoselect_filter").attr("onchange", "").unbind("change");
    $(".autoselect_filter").on('change', function(){
        var search = $(this).find("option:selected").text();
        var vaal = $(this).find("option:selected").val();
        if(vaal != ''){
            $(this).css('box-shadow', '#8c0000 0px 0px 3px')
        }else{
            $(this).css('box-shadow', 'unset')
        }
        var strict = $(this).attr('strict') !== undefined;
        if(vaal == ''){
            search = '';
        }
        var format = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
        contiene_ex = format.test(search);
        if(!contiene_ex && search != ''){
            if(strict){
                search = "" + search + "$"
            }else{
                search = "" + search + ""
            }
        }
        var colum_ix = $(this).attr('column_index');
        $(`#${$(this).attr('parent')}`).DataTable().column(parseInt(colum_ix))
            // .search( search != '' ? "^" + search + "$" : '', true, true).draw();
            .search( search != '' ? search : '', !contiene_ex, false).draw();
    });
}

function createElementFromString(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();
  
    // Change this to div.childNodes to support multiple top-level nodes.
    return div.firstChild;
}