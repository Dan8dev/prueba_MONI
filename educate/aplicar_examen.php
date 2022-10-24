<?php 
	session_start();
	require '../assets/data/Model/conexion/conexion.php';
	require '../assets/data/Model/controlescolar/examenModel.php';
	if(isset($_POST['examen']) && isset($_POST['idafi'])){
		$examenM = new Examen();
		$exm = $examenM->cargar_examen_id($_POST['examen'])['data'];
		$ex_id = $_POST['examen'];
		
		//var_dump($exm);
		if($exm['Examen_ref'] != null){
			$ex_id = $exm['Examen_ref'];
		}
		$preguntas = $examenM->cargar_preguntas_examen($ex_id)['data'];



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

		$idusuario=$_POST['idafi'];
  		$usuario=$examenM->obtenerusuario($idusuario);

	}else{
		header('Location: panel.php');
	}
include 'partials/header.php';
?>

<div class="br-pagebody">
 <div class="br-section-wrapper">	
	<div class="bd-l bd-3 bd-primary bg-gray-200 pd-x-20 pd-y-25">
		<!-- <h6 class="tx-primary">Colegio: CONACON</h6> -->
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
						echo ($i+1).".- ".$preg['pregunta'];
						$opc = json_decode($preg['opciones']);		
						echo "<br>";
						$j = 0;
						foreach ($opc as $key => $opc) {
							echo "<input type='radio' id='preg_exm-".$preg["idPregunta"].$j."' name='preg_exm-".$preg["idPregunta"]."' value='".$key."' required> 
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

<?php 
include 'partials/footer.php';
?>
<script type="text/javascript">	

	var test = <?php echo $_POST['examen'].".".$_POST['idpro'];?>;
	terminar_examen(test,<?=$exm['tipo_examen']?>);
</script>
</body>
</html>
