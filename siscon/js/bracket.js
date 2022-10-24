/*!
 * Bracket Plus v1.0.0 (https://themetrace.com/bracketplus)
 * Copyright 2017-2018 ThemePixels
 * Licensed under ThemeForest License
 */

 'use strict';

 $(document).ready(function(){

  // This will auto show sub menu using the slideDown()
  // when top level menu have a class of .show-sub
  $('.show-sub + .br-menu-sub').slideDown();



  // This will collapsed sidebar menu on left into a mini icon menu
  $('#btnLeftMenu').on('click', function(){
    var menuText = $('.menu-item-label,.menu-item-arrow');

    if($('body').hasClass('collapsed-menu')) {
      $('body').removeClass('collapsed-menu');

      // show current sub menu when reverting back from collapsed menu
      $('.show-sub + .br-menu-sub').slideDown();

      $('.br-sideleft').one('transitionend', function(e) {
        menuText.removeClass('op-lg-0-force');
        menuText.removeClass('d-lg-none');
      });

    } else {
      $('body').addClass('collapsed-menu');

      // hide toggled sub menu
      $('.show-sub + .br-menu-sub').slideUp();

      menuText.addClass('op-lg-0-force');
      $('.br-sideleft').one('transitionend', function(e) {
        menuText.addClass('d-lg-none');
      });
    }
    return false;
  });



  // This will expand the icon menu when mouse cursor points anywhere
  // inside the sidebar menu on left. This will only trigget to left sidebar
  // when it's in collapsed mode (the icon only menu)
  $(document).on('mouseover', function(e){
    e.stopPropagation();

    if($('body').hasClass('collapsed-menu') && $('#btnLeftMenu').is(':visible')) {
      var targ = $(e.target).closest('.br-sideleft').length;
      if(targ) {
        $('body').addClass('expand-menu');

        // show current shown sub menu that was hidden from collapsed
        $('.show-sub + .br-menu-sub').slideDown();

        var menuText = $('.menu-item-label,.menu-item-arrow');
        menuText.removeClass('d-lg-none');
        menuText.removeClass('op-lg-0-force');

      } else {
        $('body').removeClass('expand-menu');

        // hide current shown menu
        $('.show-sub + .br-menu-sub').slideUp();

        var menuText = $('.menu-item-label,.menu-item-arrow');
        menuText.addClass('op-lg-0-force');
        menuText.addClass('d-lg-none');
      }
    }
  });



  // This will show sub navigation menu on left sidebar
  // only when that top level menu have a sub menu on it.
  $('.br-menu-link').on('click', function(){
    var nextElem = $(this).next();
    var thisLink = $(this);

    if(nextElem.hasClass('br-menu-sub')) {

      if(nextElem.is(':visible')) {
        thisLink.removeClass('show-sub');
        nextElem.slideUp();
      } else {
        $('.br-menu-link').each(function(){
          $(this).removeClass('show-sub');
        });

        $('.br-menu-sub').each(function(){
          $(this).slideUp();
        });

        thisLink.addClass('show-sub');
        nextElem.slideDown();
      }
      return false;
    }
  });



  // This will trigger only when viewed in small devices
  // #btnLeftMenuMobile element is hidden in desktop but
  // visible in mobile. When clicked the left sidebar menu
  // will appear pushing the main content.
  $('#btnLeftMenuMobile').on('click', function(){
    $('body').addClass('show-left');
    return false;
  });



  // This is the right menu icon when it's clicked, the
  // right sidebar will appear that contains the four tab menu
  $('#btnRightMenu').on('click', function(){
    $('body').addClass('show-right');
    return false;
  });



  // This will hide sidebar when it's clicked outside of it
  $(document).on('click', function(e){
    e.stopPropagation();

    // closing left sidebar
    if($('body').hasClass('show-left')) {
      var targ = $(e.target).closest('.br-sideleft').length;
      if(!targ) {
        $('body').removeClass('show-left');
      }
    }

    // closing right sidebar
    if($('body').hasClass('show-right')) {
      var targ = $(e.target).closest('.br-sideright').length;
      if(!targ) {
        $('body').removeClass('show-right');
      }
    }
  });



  // displaying time and date in right sidebar
  var interval = setInterval(function() {
    var momentNow = moment();
    $('#brDate').html(momentNow.format('MMMM DD, YYYY') + ' '
      + momentNow.format('dddd')
      .substring(0,3).toUpperCase());
      $('#brTime').html(momentNow.format('hh:mm:ss A'));
  }, 100);

  // Datepicker
  if($().datepicker) {
    $('.form-control-datepicker').datepicker()
      .on("change", function (e) {
        console.log("Date changed: ", e.target.value);
    });
  }



  // custom scrollbar style
  $('.overflow-y-auto').perfectScrollbar();

  // jquery ui datepicker
  var f_eventos = [];
  f_eventos = [
    '2022-05-08'];

  var f_eventosNombres = [];
  f_eventosNombres = [
      'Certificación OTA'];

    
  var f_clases = [];
  f_clases = [
    '2022-05-12',
    '2022-05-19',
    '2022-05-26',
    '2022-06-02',
    '2022-06-09',
    '2022-06-16',
    '2022-06-23',
    '2022-06-30',
    '2022-07-07',
    '2022-07-14',
    '2022-07-21',
    '2022-07-28',
    '2022-08-04',
    '2022-08-11',
    '2022-08-18',
    '2022-08-25',
    '2022-09-01',
    '2022-09-08',
    '2022-09-15',
    '2022-09-22',
    '2022-09-29',
    '2022-10-06',
    '2022-10-13',
    '2022-10-20'

  ];

  var f_clasesNombres = [];
  f_clasesNombres = [
    'LA TRIADA DE LA ADICCION LA RECUPERACION Y LA ABSTINENCIA',
    '¿QUÉ ES Y CÓMO MANEJAR EL SÍNDROME DE ABSTINENCIA POST-AGUDA? LA RECUPERACIÓN.',
    'ENTENDIENDO LA RECAÍDA',
    'FASES Y SENALES DE PELIGRO DE LA RECAÍDA. PLAN DE PREVENCIÓN',
    'INTRODUCCIÓN Y MANEJO DE LA CRISIS',
    'CÓMO DETENER MIS IMPULSOS DE ADICCIÓN',
    'MENTE SABIA: CÓMO TOMAR DECISIONES EFECTIVAS',
    'CÓMO REGULARME A TRAVÉS DE LA ATENCIÓN PLENA',
    'BÚSQUEDA ESPIRITUAL',
    'EL AUTO CUIDADO Y LA ESPIRITUALIDAD',
    'REDES DE APOYO ESPIRITUALES',
    'EL SENTIDO DE LA VIDA',
    'IDENTIFICANDO LAS REDES DE APOYO MOTIVACIÓN Y DESARROLLO DE VÍNCULOS',
    'MI ROL EN LAS REDES DE APOYO Y MI RESPONSABILIDAD CON PERSONAS SIGNIFICATIVAS',
    'EL PROCESO DE REINSERCIÓN Y LA PARTICIPACIÓN FAMILIAR (IDENTIFICACIÓN DE MECANISMOS DE ABANDONO DEL PROCESO TERAPÉUTICO)',
    'MI PAPEL EN LA SOCIEDAD: HACIENDO FRENTE A MI COMPROMISO',
    'MI PROYECTO DE VIDA A CORTO, MEDIANO Y LARGO PLAZO',
    'EL AUTOCONOCIMIENTO PARA EL DESARROLLO DE HABILIDADES COGNITIVAS',
    'LA AUTOGESTIÓN Y EL DESARROLLO DE HABILIDADES EMOCIONALES',
    'EL AUTOESTIMA PARA EL DESARROLLO DE HABILIDADES SOCIALES EN EL PROCESO DE REINSERCIÓN',
    'CONCEPTOS BÁSICOS DE SEXUALIDAD: DIVERSIDAD, SEXUALIDAD Y GÉNERO,',
    'FACTORES DE RIESGO Y DE PROTECCIÓN VINCULADOS A CONDUCTAS SEXUALES,',
    'RELACIONES EMOCIONALES, SEXUALES Y AMOROSAS',
    'CONSENTIMIENTO Y ACTIVIDADES SEXUALES ORIENTADAS AL AUTOCUIDADO',

  ];
  
  //Verificar fechas y nombres de los eventos para pintarlos en el
  var letrero = null;
  var letrero2 = null;
  $('.datepicker').datepicker({
    beforeShowDay: function(d) {

      letrero = f_clases.indexOf(d.toISOString().substr(0, 10));
      letrero2 = f_eventos.indexOf(d.toISOString().substr(0, 10));

      if(letrero2>-1){
        return [true, f_eventos.includes(d.toISOString().substr(0, 10)) ? "my-class" : "",f_eventos.includes(d.toISOString().substr(0, 10)) ? ''+f_eventosNombres[letrero2] : ''];;
      }else{
        return [true, f_clases.includes(d.toISOString().substr(0, 10)) ? "my-class-event" : "",f_clases.includes(d.toISOString().substr(0, 10)) ? ''+f_clasesNombres[letrero] : ''];
      }
      //console.log(d.toISOString().substr(0, 10));
      //console.log(f_clases[letrero]);
     
      
      
    }
  });


  // switch button
  $('.switch-button').switchButton();

  // peity charts
  $('.peity-bar').peity('bar');

  // highlight syntax highlighter
  $('pre code').each(function(i, block) {
    hljs.highlightBlock(block);
  });

  // Initialize tooltip
  $('[data-toggle="tooltip"]').tooltip();

  // Initialize popover
  $('[data-popover-color="default"]').popover();



  // By default, Bootstrap doesn't auto close popover after appearing in the page
  // resulting other popover overlap each other. Doing this will auto dismiss a popover
  // when clicking anywhere outside of it
  $(document).on('click', function (e) {
    $('[data-toggle="popover"],[data-original-title]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
        }

    });
  });



  // Select2 Initialize
  // Select2 without the search
  if($().select2) {
    $('.select2').select2({
      minimumResultsForSearch: Infinity
    });

    // Select2 by showing the search
    $('.select2-show-search').select2({
      minimumResultsForSearch: ''
    });

    // Select2 with tagging support
    $('.select2-tag').select2({
      tags: true,
      tokenSeparators: [',', ' ']
    });
  }

});
