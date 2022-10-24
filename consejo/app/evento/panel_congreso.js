$(document).ready(function(){
	cargar_talleres_disponibles();
	setTimeout(crear_qr(),500);
})

function cargar_talleres_disponibles(){
  fData = {
    action: 'talleres_eventos',
    evento: usrInfo.id_evento
  }
  $.ajax({
    url: "data/CData/afiliadosControl.php",
    type: "POST",
    data: fData,
    beforeSend : function(){
      $(".outerDiv_S").css("display", "block")
    },
    success: function(data){
      try{
        talleres = JSON.parse(data);
        html_form = ""
        
        if(talleres.estatus == 'ok'){
        	talleres = talleres.data;
        	for (i = 0; i < talleres.length; i++) {
        		html_form += `<div class='col-sm-12 col-md-6'>
	        					<div class="form-check">
                          <input id="chk_${talleres[i].id_taller}" name="chk_${talleres[i].id_taller}" type="checkbox" class="form-check-input">
                          <label for="chk_${talleres[i].id_taller}" class="form-check-label">
                              ${talleres[i].nombre}
                          </label>
                      </div>
                    </div>`;
        	}
        }
        
        
		$("#content-form-chk").html(html_form);
      }catch(e){
        console.log(e);
        console.log(data);
      }
    },
    error: function(){
    },
    complete: function(){
      $(".outerDiv_S").css("display", "none")
    }
  
  })
}

$("#btn-confirm-talleres").on('click', function(){
	if($("input:checked" ).length > 0){
		$("#lista_talleres_selected").html('');
		
		$("input:checked").each(function(e){
			$("#lista_talleres_selected").append(`<li>${$(this).next().text()}</li>`);
		})

		$("#modal-confirm").modal('show');
	}else{
		swal({
  		title:'Campos vacíos',
			text: 'por favor seleccione algún taller de la lista',
			icon: 'info'
  	})
	}
});

$("#form_apartar_talleres").on("submit", function(e){
	e.preventDefault();

  if($("input:checked" ).length > 0){
	  fData = new FormData(this);
	  fData.append('persona',usrInfo.id_asistente);
	  fData.append('action','apartar_talleres');
	  
	  $.ajax({
	    url: "data/CData/afiliadosControl.php",
	    type: "POST",
	    data: fData,
	    contentType: false,
	    processData:false,
	    beforeSend : function(){
	      $(".outerDiv_S").css("display", "block")
	    },
	    success: function(data){
	      try{
	        json = JSON.parse(data);
	        if(json.estatus == 'ok'){
	        	swal({
	        		title:'Listo!',
	        		text: 'hemos reservado tus lugares con éxito.',
	        		icon: 'success'
	        	}).then((value)=>{
                window.location.reload(true);
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
	      $(".outerDiv_S").css("display", "none")
	    }
	  
	  })
  }else{
  	swal({
  		title:'Campos vacíos',
			text: 'por favor seleccione algún taller de la lista',
			icon: 'info'
  	})
  }
});

function crear_qr(){
	if($("#qrcode").length > 0){
		var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
		var height = (window.innerheight > 0) ? window.innerheight : screen.height;
		vm = 0;
		console.log('width:'+width)
		console.log('height:'+height)
		if(width > height){
			vm = height;
		}else{
			vm = width;
		}
		if(vm > 800){
			vm = vm * .6;
		}
	  var qrcode = new QRCode(document.getElementById("qrcode"), {
	      width : vm * .7,
	      height : vm * .7
	    });

	    function makeCode () {    
	      var elText = document.getElementById("text");
	      
	      if (!elText.value) {
	        alert("Input a text");
	        elText.focus();
	        return;
	      }
	      
	      qrcode.makeCode(elText.value);
	    }

	    makeCode();

	    $("#text").
	    on("blur", function () {
	      makeCode();
	    }).
	    on("keydown", function (e) {
	      if (e.keyCode == 13) {
	        makeCode();
	      }
	    });
	}
}
