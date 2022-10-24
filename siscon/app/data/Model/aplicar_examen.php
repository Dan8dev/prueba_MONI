<?php 
	session_start();
	require 'data/Model/conexion.php';
	require 'data/Model/examenModel.php';
	require 'data/Model/AfiliadosModel.php';
	if(isset($_POST['examen']) && isset($_SESSION['alumno'])){
		$examenM = new Examen();

		$exm = $examenM->cargar_examen_id($_POST['examen'])['data'];
		$preguntas = $examenM->cargar_preguntas_examen($_POST['examen'])['data'];

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
			$preguntas = $preguntas_ordenadas;
		}

		$afiliados = new Afiliados();
		$idusuario=$_SESSION['alumno']['id_afiliado'];
  		$usuario=$afiliados->obtenerusuario($idusuario);

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
    
</head>

<body>
<div class="br-pagebody">
 <div class="br-section-wrapper">	
	<div class="bd-l bd-3 bd-primary bg-gray-200 pd-x-20 pd-y-25">
		<h6 class="tx-primary">Colegio: CONACON</h6>
		<h6>Test: <?php echo $exm['Nombre']; ?></h6>
		<h6>Nombre alumno: <?php echo $usuario['data']['nombre']." ".$usuario['data']['apaterno']." ".$usuario['data']['amaterno']; ?></h6>
		<h6>Instrucciones: Lee atentamente cada pregunta y selecciona la opción correcta</h6>
	</div><!-- card-header -->
				
		<form id="form-examen">
			<div class="row p-2">
				<?php
					$i = 0;
					foreach ($preguntas as $key => $preg) {
						echo "<div class='col-sm-12 col-md-6 mt-4'>";
						echo "<span><h4><b>".($i+1).".- ".$preg['pregunta']."</b></h4></span>";
						$opc = json_decode($preg['opciones']);		
						echo "<br>";
						$j = 0;
						foreach ($opc as $key => $opc) {
							echo "<input type='radio' id='preg_exm-".$preg["idPregunta"].$j."' name='preg_exm-".$preg["idPregunta"]."' value='".$key."' > 
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

	<script src="../lib/jquery/jquery.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript" href="../js/sweetalert.min.js"></script>
	<script type="text/javascript">
		finish = false;
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

	    $("#form-examen").on('submit',function(e){
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
							text : 'falta responer la pregunta: \n '+$(this).parent().find("span").html()
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
							exm = JSON.parse(data)
							console.log(exm)
							if(exm.estatus == 'ok'){
								swal({
									icon:'success',
									title:'Examen terminado satisfactoriamente',
									text:'puede consultar su calificación en su panel de clase'
								}).then((result)=>{
									window.location.replace('cursos.php');
								})
							}else{
								swal({
									icon:'info',
									text: exm.info == 'examen_vencido' ? 'Excediste la hora limite del examen':'ha ocurrido algo al guardar las respuestas del examen, contacte a soporte técnico'
								}).then((result)=>{
									window.location.replace('cursos.php');
								})
							}
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
			}
	    })
	</script>
</body>
</html>
