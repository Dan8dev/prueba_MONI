<?php 
	session_start();
	require 'data/Model/conexion.php';
	require 'data/Model/examenModel.php';
	require 'data/Model/AfiliadosModel.php';
	if(isset($_POST['examen']) && isset($_SESSION['alumno'])){
		$examenM = new Examen();
		echo "<script>const exm = ".$_POST['examen']."; </script>";
		echo "<script>const alumn = ".$_SESSION['alumno']['id_afiliado']."; </script>";
		$exm = $examenM->cargar_examen_id($_POST['examen'])['data'];
		$short_resp = ($exm['id_carrera'] == 14 || $exm['id_carrera'] == 19);
		$exm_preg = intval($exm['Examen_ref']) > 0 ? $exm['Examen_ref'] : $_POST['examen'];
		$preguntas = $examenM->cargar_preguntas_examen($exm_preg)['data'];

		// reodenar las preguntas
		$preguntas_ordenadas = [];
		$keys = array_keys($preguntas);
		shuffle($keys);
		foreach($keys as $key){
			$preguntas_ordenadas[] = $preguntas[$key];
		}
		$num_preguntas = intval($exm['preguntas_aplicar']);
		//truncar las preguntas al numero establecido en la config del examen
		if($num_preguntas > 1){
			$preguntas_ordenadas = array_slice($preguntas_ordenadas, 0, $num_preguntas, false);
			
		}
		$preguntas = $preguntas_ordenadas;

		$afiliados = new Afiliados();
		$idusuario=$_SESSION['alumno']['id_afiliado'];
  		$usuario=$afiliados->obtenerusuario($idusuario);
  		

  		if(isset($_POST['loc'])){
			$loc = $_POST['loc'];
	  	}else{
			$loc = '';
	  	}

	}else{
		header('Location: panel.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" lang="es">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Aplicación de examen</title>
	<link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
    <link href="../lib/highlightjs/github.css" rel="stylesheet">
    <link href="../lib/jquery.steps/jquery.steps.css" rel="stylesheet">
    
    <link rel="icon" type="imge/png" href="img/favicon.png">

    <!--Datatables-->
    <link href="../../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Bracket CSS -->
    <link rel="stylesheet" href="../css/bracket.css">
    <link rel="stylesheet" href="../css/alertas.css">
	<style>
		#loader{
			position: absolute;
			top: 0;
			background-color: #000000b0;
			width: 98%;
			height: 97vh;
			margin: 1%;
			text-align: center;
			padding-top: 25%;
			color:white;
			/* display:none; */
		}
		@media (max-width: 690px) {
			#loader {
				padding-top: 90%;
			}
		}
	</style>
    
</head>

<body>
<div class="br-pagebody">
 <div class="br-section-wrapper">	
	<div class="bd-l bd-3 bd-primary bg-gray-200 pd-x-20 pd-y-25">
		<h6 class="tx-primary"><b>UNIVERSIDAD DEL CONDE</b></h6>
		<h6>Test: <?php echo $exm['Nombre']; ?></h6>
		<h6>Nombre alumno: <?php echo $usuario['data']['nombre']." ".$usuario['data']['apaterno']." ".$usuario['data']['amaterno']; ?></h6>
		<h6>Instrucciones: Lee atentamente cada pregunta y selecciona la opción correcta</h6>
	</div><!-- card-header -->
				
		<form id="form-examen">
			<div class="row p-2" id="content-form">
				<?php
					$i = 0;
					foreach ($preguntas as $key => $preg) {
						echo "<div class='col-sm-12 col-md-6 mt-4'>";
						echo "<span><h4><b>".($i+1).".- ".$preg['pregunta']."</b></h4></span>";
						$opc = json_decode($preg['opciones']);		
						echo "<br>";
						$j = 0;
						foreach ($opc as $key => $opc) {
							echo "<input type='radio' id='preg_exm-".$preg["idPregunta"].$j."' onchange='salvado_automatico()' name='preg_exm-".$preg["idPregunta"]."' value='".$key."' > 
							<label for='preg_exm-".$preg["idPregunta"].$j."'>".$key."</label><br>";
							$j++;
						}
						echo "</div>";
						$i++;
					}
				?>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<button type="submit" class="btn btn-primary btn-block">Terminar</button>
				</div>
			</div>
		</form>
 </div>	
</div>

<div id="loader">
    <h3>Cargando...  <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></h3>
</div>

	<script src="../lib/jquery/jquery.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript" href="../js/sweetalert.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			recuperar_respuestas();
		});
		<?php if($short_resp): ?>
			var medicina = true;
		<?php else: ?>
			var medicina = false;
		<?php endif; ?>
		finish = false;
		function recuperar_respuestas(){
			// $("#loader").css('display','none');
			// return;
			if(!localStorage.getItem('examen_guardado_'+exm+'_'+alumn)){
				$("#loader").css('display','none');
			}else{
				var guardado = localStorage.getItem('examen_guardado_'+exm+'_'+alumn);
				guardado = JSON.parse(guardado);
				var examen = '';
				for (var [key, value] of Object.entries(guardado.preguntas)) {
					var opciones = value.opciones.map(elm=>{
						return `<br>
								<input type="radio" id="preg_exm-${elm.valor}" onchange='salvado_automatico()' name='preg_exm-${value.pregunta}' value="${elm.text_opcion}" ${elm.selected ? 'checked':''}> 
								<label for="preg_exm-${elm.valor}">${elm.text_opcion}</label>`;
					})
					examen+=`
						<div class="col-sm-12 col-md-6 mt-4">
							<span><h4><b>${value.text_pregunta}</b></h4></span>
							${opciones.join('')}
						</div>
					`;
				}
				$("#content-form").html(examen);
				$("#loader").css('display','none');
			}
		}
		function salvado_automatico(){
			// if(!localStorage.getItem('examen_guardado_'+exm+'_'+alumn)){
				var preguntas = {};
				$("input[type='radio']").each(function(){
					var pregunta = $(this).attr('name').split('-')[1];
					var text_pregunta = $(this).parent().find('span').text()
					var orden = parseInt(text_pregunta.split('.-')[0]);
					if(!preguntas.hasOwnProperty(orden+"-"+pregunta.toString())){
						var opcion = $(this).attr('id').split('-')[1];

						preguntas[orden+"-"+pregunta.toString()] = {
							'text_pregunta':text_pregunta,
							'orden':orden,
							'pregunta':pregunta,
							'opciones':[
								{
									'text_opcion':$(`label[for='${$(this).attr('id')}']`).text(),
									'selected':$(this).is(":checked"),
									'valor':opcion
								}
							]
						}
					}else{
						var opcion = $(this).attr('id').split('-')[1];
						var text_pregunta = $(this).parent().find('span').text()
						preguntas[orden+"-"+pregunta.toString()].opciones.push({
								'text_opcion':$(`label[for='${$(this).attr('id')}']`).text(),
								'selected':$(this).is(":checked"),
								'valor':opcion
							});
					}
				})
				var save_object = {
					'examen':exm,
					'alumno':alumn,
					'preguntas':preguntas
				}
				localStorage.setItem('examen_guardado_'+exm+'_'+alumn, JSON.stringify(save_object))
			// }
		}
		/*$(document).ready(function(){
			salvado_automatico();
		})

		function salvado_automatico(){
			fData = new FormData($("#form-examen")[0])
			fData.append('action','salvado_automatico')
			fData.append('code',<?php echo $_POST['examen'].".".$_SESSION['alumno']['id_afiliado'];?>)
			$.ajax({
				url: "data/CData/materiasControl.php",
				type: "POST",
				data: fData,
				processData:false,
				contentType:false,
				beforeSend : function(){
					$("#loader").css("display", "block")
				},
				success: function(data){
					try{
						exm = JSON.parse(data)
						console.log(exm)
					}catch(e){
						console.log(e);
						console.log(data);
					}
				},
				error: function(){
				},
				complete: function(){
					$("#loader").css("display", "none")
				}
			})

			setTimeout(function(){
				salvado_automatico();
			}, 9000)

		}*/
		window.onbeforeunload = confirmExit;
	    function confirmExit() {
	    	if(!finish){
	        	return "Desea cerrar sin terminar su examen?";
	    	}
	    }

		$(document).ready(()=>{
			console.log('<?=$loc?>');
		});
	    $("#form-examen").on('submit',function(e){
	     var loc = '<?=$loc?>';
	    	finish = true;
	    	e.preventDefault();
			var inp_name = '';
			var todo_respondido = true;
			$(":radio").each(function(ix){
				if(inp_name != $(this).attr('name')){
					inp_name = $(this).attr('name');
					if(!$(`input[name='${inp_name}']`).is(":checked") && todo_respondido == true){
						todo_respondido = false;
						swal({
							text : 'falta responer la pregunta: \n '+$(this).parent().find("span").text()
						}).then(()=>{
							$([document.documentElement, document.body]).animate({
								scrollTop: $(`input[name='${inp_name}']`).offset().top - 100
							}, 100);
						})
					}
				}
			})
			if(todo_respondido){
				fData = new FormData(this)
				fData.append('action','terminar_examen')
				fData.append('code','<?php echo $_POST['examen'].".".$_SESSION['alumno']['id_prospecto'];?>')
				$.ajax({
					url: "data/CData/materiasControl.php",
					type: "POST",
					data: fData,
					processData:false,
					contentType:false,
					beforeSend : function(){
						$("#loader").css("display", "block")
					},
					success: function(data){
						try{
							exm_complete = JSON.parse(data)
							if(exm_complete.estatus == 'ok'){
								localStorage.removeItem('examen_guardado_'+exm+'_'+alumn)
								swal({
									icon:'success',
									title:'Examen terminado satisfactoriamente',
									text:(medicina ? '' : 'puede consultar su calificación en su panel de clase')
								}).then((result)=>{
									if(loc == 'extra'){
										window.location.replace('examenes-extra.php');
									}else{
										window.location.replace('cursos.php');	
									}
								})
							}else{
								swal({
									icon:'info',
									text: exm_complete.info == 'examen_vencido' ? 'Excediste la hora limite del examen':'ha ocurrido algo al guardar las respuestas del examen, contacte a soporte técnico'
								}).then((result)=>{
									if(loc == 'extra'){
										window.location.replace('examenes-extra.php');
									}else{
										window.location.replace('cursos.php');	
									}
								})
							}
							console.log(exm_complete)
						}catch(e){
							console.log(e);
							console.log(data);
						}
					},
					error: function(){
					},
					complete: function(){
						$("#loader").css("display", "none")
					}
				})
			}
	    })
	</script>
</body>
</html>
