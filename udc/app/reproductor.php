<?php 
	if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
	{
		//Tell the browser to redirect to the HTTPS URL.
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
		//Prevent the rest of the script from executing.
		exit;
	}

  session_start();
  if (!isset($_SESSION["alumno"])) {
    header('Location: index.php');
    die();
  }
  $usr = $_SESSION['alumno'];
 $usr = $_SESSION['alumno'];
	require "data/Model/AfiliadosModel.php";
  $idusuario=$_SESSION['alumno']['id_afiliado'];
  $afiliados = new Afiliados();
  $usuario=$afiliados->obtenerusuario($idusuario);
  $idPros = $usuario["data"]["id_prospecto"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:url" content="https://www.facebook.com/udconde/">
    <meta property="og:title" content="Universidad del Conde">
    <meta property="og:description" content="En Universidad del Conde, día con día nos damos a la tarea de sembrar en nuestros alumnos un interés genuino por influir de forma positiva en el mundo, por ser el agente de cambio que nuestro México necesita y trabajando todos los días sin quitar la vista de nuestro objetivo.">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <!-- Open Graph data -->
    <meta property="og:title" content="Universidad del Conde" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://moni.com.mx/UDC" />
    <meta property="og:image" content="https://moni.com.mx/consejo/app/img/logoMetas.png" />
    <meta property="og:description" content="" />
    <!-- Meta -->
    <meta name="description" content="Universidad del Conde">
    <meta name="author" content="Universidad del Conde Desarrollo Tecnológico">

    <title>Universidad del Conde</title>

    <!-- vendor css -->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
    <link href="../lib/highlightjs/github.css" rel="stylesheet">
    <link href="../lib/jquery.steps/jquery.steps.css" rel="stylesheet">
	  
	  <link rel="icon" type="imge/png" href="img/favicon.png">

    <link href="//vjs.zencdn.net/7.10.2/video-js.min.css" rel="stylesheet">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
	<?php require 'plantilla/header.php'; ?>
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
      <!-- <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
          <a class="breadcrumb-item" href="panel.php">Panel</a>
          <a class="breadcrumb-item" href="memoriaDigital.php">Videoteca</a>
          <span class="breadcrumb-item active">Reproductor</span>
        </nav>
      </div> -->
      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h6 class="tx-gray-800 mg-b-5" id="event_name"></h6>
        <!-- <p class="mg-b-0">Descripción</p> -->
      </div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">         
          <div class="row">
            <div class="col-xl-12 col-sm-12">
                <div class="embed-responsive embed-responsive-16by9" >
                  <video
                      id="my-player"
                      class="video-js embed-responsive-item vjs-big-play-centered"
                      controls
                      preload="auto"
                      poster="udc.jpg"
                      data-setup='{}'>
                      <source id = "content-video-dinamic" ></source>
                      <!-- <source src="https://vimeo.com/live-chat/754900401/" type="video/webm"></source> -->
                      <!-- <source src="//vjs.zencdn.net/v/oceans.ogv" type="video/ogg"></source> -->
                    <p class="vjs-no-js">
                      To view this video please enable JavaScript, and consider upgrading to a
                      web browser that
                      <a href="https://videojs.com/html5-video-support/" target="_blank">
                        supports HTML5 video
                      </a>
                    </p>
                  </video>
                </div>
            </div>
          </div>   
          
        </div><!-- br-section-wrapper -->
      </div><!-- br-pagebody -->
      <?php require 'plantilla/footer.php'; ?>
    </div><!-- br-mainpanel -->
    <!-- ########## END: MAIN PANEL ########## -->

    <script src="../lib/jquery/jquery.js"></script>
    <script src="../lib/popper.js/popper.js"></script>
    <script src="../lib/bootstrap/bootstrap.js"></script>
    <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
    <script src="../lib/moment/moment.js"></script>
    <script src="../lib/jquery-ui/jquery-ui.js"></script>
    <script src="../lib/jquery-switchbutton/jquery.switchButton.js"></script>
    <script src="../lib/peity/jquery.peity.js"></script>
    <script src="../lib/highlightjs/highlight.pack.js"></script>
    <script src="../lib/jquery.steps/jquery.steps.js"></script>
    <script src="../lib/parsleyjs/parsley.js"></script>
   
    <script src="//vjs.zencdn.net/7.10.2/video.min.js"></script>

    <script src="../js/bracket.js"></script>
    <script>
		<?php
			if(isset($_GET['url'])):
        if(isset($_GET['idclass'])){
          $numeroClass = $_GET['idclass'];
        }else{
          $numeroClass = 0;
        }
		?>
			$(document).ready(function(){
        $link = "https://vod-progressive.akamaized.net/exp=1663706672~acl=%2Fvimeo-transcode-storage-prod-us-central1-h264-720p%2F01%2F961%2F26%2F654805633%2F3006597140.mp4~hmac=fa435d1d23a6a1558138bd7bd28b3e9c63ef6db17f491a9d834bda3d99a7d4da/vimeo-transcode-storage-prod-us-central1-h264-720p/01/961/26/654805633/3006597140.mp4?filename=file.mp4";
				$("#content-video-dinamic").attr('src', '<?php $url = $_GET['url']; $url = urldecode($url); echo "{$url}"; ?>');
        $("#content-video-dinamic").attr("type","video/mp4")
        //$("#content-video-dinamic").attr('src', <?php// echo $link;?>);
			});
		<?php
			else:
		?>
			// $(document).ready(function(){
        
			// 	$.ajax({
			// 	  url: "../../assets/data/Controller/eventos/eventosControl.php",
			// 	  type: "POST",
			// 	  data: {action:'consultarEvento_Clave', clave:'<?php echo $_GET['evento']; ?>'},
			// 	  //contentType: false,
			// 	  //processData:false,
			// 	  beforeSend : function(){
			// 	  },
			// 	  success: function(data){
			// 		try{
			// 		  // console.log(data)
			// 		  evento = JSON.parse(data);
			// 		  if(evento.data.length > 0){
			// 			  //var link = "https://vod-progressive.akamaized.net/exp=1663706672~acl=%2Fvimeo-transcode-storage-prod-us-central1-h264-720p%2F01%2F961%2F26%2F654805633%2F3006597140.mp4~hmac=fa435d1d23a6a1558138bd7bd28b3e9c63ef6db17f491a9d834bda3d99a7d4da/vimeo-transcode-storage-prod-us-central1-h264-720p/01/961/26/654805633/3006597140.mp4?filename=file.mp4";
			// 			  parte = <?php echo $_GET['pt']; ?>;
			// 			  urls = JSON.parse(evento.data[0].video_url);
			// 			  var link  = "https://vod-progressive.akamaized.net/exp=1663708253~acl=%2Fvimeo-transcode-storage-prod-us-central1-h264-720p%2F01%2F961%2F26%2F654805633%2F3006597140.mp4~hmac=ead9acbca1a61e72ddbebd062b60f7b241cea14164cd83d8cf3befbff403845e/vimeo-transcode-storage-prod-us-central1-h264-720p/01/961/26/654805633/3006597140.mp4?filename=file.mp4";
			// 			$("#content-video-dinamic").attr('src', link);
			// 			$("#event_name").html(evento.data[0].titulo)
			// 		  }else{
			// 			alert('el evento al que intenta acceder no existe, o ya no está disponible.');
			// 		  }
			// 		}catch(e){
			// 		  console.log(e);
			// 		  console.log(data);
			// 		}
			// 	  },
			// 	  error: function(){
			// 	  },
			// 	  complete: function(){
			// 	  }
			// 	});
			//   });
		<?php
			endif;
		?>



      $(document).ready(function(){
        'use strict';
        //var player = videojs('my-player');
        var options = {};
        var player = videojs('my-player', options ,function onPlayerReady() {
          videojs.log('La Clase se cargo correctamente');

          // In this context, `this` is the player that was created by Video.js.
          this.on("play",function(e){
            videojs.log('Reproduciendo'); 
            videojs.log(this.cache_.currentTime);
          });

          this.on("pause",function(e){
            console.log(this.played().start(0));
            console.log(this.played().end(0));
            // var modal = player.createModal('Sigue disfrutando tu clase');
            //   modal.on('modalclose', function() {
            //     player.play();
              // });
          });

          // How about an event listener?
          this.on('ended', function() {
            videojs.log('Video Terminado Tomar Asistencia');
           
            $.ajax({
              type: "POST",
              dataType: "JSON",
              url: "data/CData/afiliadosControl.php",
              data: { action: "guardar_asistencia", idAlumno: <?php echo $idPros;?>, numClass: <?php echo $numeroClass;?>},
              success: function (response) {
                console.log(response)
              }
            });
          });
        });
        
        //$(frames[i]).contents().find('video')[0].play();
        // video.on("pause();",function(e){
        //   console.log("pausa");
        // });

        $('#wizard2').steps({
          headerTag: 'h3',
          bodyTag: 'section',
          autoFocus: true,
          titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
          onStepChanging: function (event, currentIndex, newIndex) {
            if(currentIndex < newIndex) {
              // Step 1 form validation
              if(currentIndex === 0) {
                var fname = $('#firstname').parsley();
                var lname = $('#lastname').parsley();

                if(fname.isValid() && lname.isValid()) {
                  return true;
                } else {
                  fname.validate();
                  lname.validate();
                }
              }

              // Step 2 form validation
              if(currentIndex === 1) {
                var email = $('#email').parsley();
                if(email.isValid()) {
                  return true;
                } else { email.validate(); }
              }
            // Always allow step back to the previous step even if the current step is not valid.
            } else { return true; }
          }
        });

        $('.fc-datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
        }); 
      });
      function mayusculas(e) {
          e.value = e.value.toUpperCase();
        } 
    </script> 

  </body>
</html>
