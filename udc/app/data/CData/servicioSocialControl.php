<?php
date_default_timezone_set("America/Mexico_City");
require "../Model/ServicioSocialModel.php";
require "../Model/AlumnoModel.php";
require "../Model/PagosModel.php";
$Almn = new Alumno();

$Servicio = new Servicio();
$Pgs = new Pagos();

//var_dump($_SESSION);

switch ($_POST["action"]) {
	case 'ModeloControl':
		unset($_POST['action']);
		$Model = $Servicio->ConsultaFormatos(1);
		echo json_encode($Model);
		break;

	case 'ModeloControlTabla':
		unset($_POST['action']);
		$Model2 = $Servicio->ConsultaFormatos(2);
		//var_dump($Model2);
		$data = Array();
		$count = 0;
            while($dato=$Model2->fetchObject()){
				$count++;
                $data[]=array(
					
                0=> $count,
                1=> $dato->procNombre,
                2=> $dato->nombre,
				3=> '<div class ="text-center"><a class = "btn btn-primary" type="button" target="_blank" href= "../../assets/files/servicio/documentos/'.$dato->archivo.'"> Descargar</a></div>');
            }	
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
		echo json_encode($result);
		//echo json_encode($Model2);
		break;

	case 'ConsultaFormatosCorreccion':
		unset($_POST['action']);
			$Model2 = $Servicio->ConsultaFormatosRevision(2);
			$idUsuario = $_POST['usr'];
			unset($_POST['usr']);
			//var_dump($Model2);
			$data = Array();
			$count = 0;
			$datosConsulta = Array();
			while($dato=$Model2->fetchObject()){
				//var_dump($dato->vecesenvio,$dato->numenvio);
				for($i=0;$i<$dato->vecesenvio;$i++){
					$datosConsulta = ['idProceso' => $dato->idproceso, 'idFormato' => $dato->idarchivo, 'numEnvio' => strval($i+1), 'idAlumno'=> $idUsuario];
					//var_dump($datosConsulta);
					$Consulta = $Servicio->validarEntregaDoc($datosConsulta);
					//var_dump($Consulta['data'][0]['iddocumento']);
					$ControlDoc = '';
					if($Consulta['data'] != []){
						$idDocumento = $Consulta['data'][0]['iddocumento'];
						$nombreDoc = $Consulta['data'][0]['nombre'];
						$button = '<button class="btn btn-primary" onClick="VerComentarios(\''.$dato->idproceso.'\',\''.$dato->idarchivo.'\',\''.strval($i+1).'\',\''.$idUsuario.'\',\''.$idDocumento.'\')">Observaciones</button>';
						$buttoncambios = '<button class="btn btn-primary" id="Button'.$dato->idproceso.'_'.$dato->idarchivo.'_'.strval($i+1).'_'.$idUsuario.'_'.$idDocumento.'" onClick="CambiarArchivo(\''.$dato->idproceso.'\',\''.$dato->idarchivo.'\',\''.strval($i+1).'\',\''.$idUsuario.'\',\''.$idDocumento.'\')" disabled>Cambiar</button>';
						$input = '<input class = "form-control My-enter-file" type="file" onchange="HabilitarButton(\''.$dato->idproceso.'\',\''.$dato->idarchivo.'\',\''.strval($i+1).'\',\''.$idUsuario.'\',\''.$idDocumento.'\')" id="Cambio'.$dato->idproceso.'_'.$dato->idarchivo.'_'.strval($i+1).'_'.$idUsuario.'_'.$idDocumento.'" name="Cambio">';
						//var_dump($Consulta['data'][0]['estatus']);
						$Mensaje = null;
						switch($Consulta['data'][0]['estatus']){
							case '1':
								$Mensaje = "Enviado";
								break;
							case '2':
								$Mensaje = "Revision";
								$count++;
								$data[]=array(
									0=> $count,
									1=> '<b>'.$dato->procNombre.'</b>',
									2=> '<div class ="row"><div class = "col-md-6">'.$dato->nombre.'</div><div class = "col-md-6"><a href="../../assets/files/servicio/alumnos/'.$nombreDoc.'"><h6 class="text-primary"> Visualizar</h6><div></div>',
									3=> '<div class = "container"><div class = "row"><div class = "col-md-4">'.$button.'</div><div class = "col-md-4">'.$input.'</div><div class="col-md-4">'.$buttoncambios.'<div/></div></div>'//4=> $ControlDoc == '' ? '<div class = "row text-center"><div class = "col-md-6 col-sm-6"><input class = "form-control especial" type="file" id="avatar_'.$dato->idproceso.'_'.$dato->idarchivo.'_'.strval($i+1).'_'.$idUsuario.'" name="avatar"></div><div class = "col-md-6 col-sm-6">'.$button.'</div></div>' : $ControlDoc
								);
								break;
							case '3':
								$Mensaje = "Listo";
								break;
						}
						$ControlDoc = '<div class = "row text-center form-group"><div class = "col-md-6">'.$Mensaje.'</div></div>';
						
					}
						//var_dump($button);
						//var_dump($Consulta);

					}
					
					//var_dump($datosConsulta);
					$datosConsulta = [];	
				}	
				$result = array(
					'sEcho'=>1,
					'iTotalRecords'=>count( $data ),
					'iTotalDisplayRecords'=>count( $data ),
					'aaData'=>$data
				);
			echo json_encode($result);
		break;

	case 'consultarComentariosArchivo':
		unset($_POST['action']);
		$ComentariosArchivo = $Servicio->verComentariosArchivo($_POST);
		$data = Array();
		while($dato=$ComentariosArchivo->fetchObject()){
			
			$data[]=array(
				0=> $dato->autor == 1 ? '<strong>Control Escolar: </strong>'.$dato->comentario: '<strong>Alumno: </strong>'.$dato->comentario,
				1=> $dato->fecha
			);
		}
		//<button type="button" class="btn btn-primary" onclick="editarExamen('.$dato->idExamen.')">Editar</button> 
		$result = array(
			'sEcho'=>1,
			'iTotalRecords'=>count( $data ),
			'iTotalDisplayRecords'=>count( $data ),
			'aaData'=>$data
		);
		echo json_encode($result);

		break;

	case 'ConsultaFormatosRevision':
		unset($_POST['action']);
			$Model2 = $Servicio->ConsultaFormatosRevision(2);
			$idUsuario = $_POST['usr'];
			unset($_POST['usr']);
			//var_dump($Model2);
			$data = Array();
			$count = 0;
			$datosConsulta = Array();
				while($dato=$Model2->fetchObject()){
					//var_dump($dato->vecesenvio,$dato->numenvio);
					for($i=0;$i<$dato->vecesenvio;$i++){
						$datosConsulta = ['idProceso' => $dato->idproceso, 'idFormato' => $dato->idarchivo, 'numEnvio' => strval($i+1), 'idAlumno'=> $idUsuario];
						//var_dump($datosConsulta);
						$Consulta = $Servicio->validarEntregaDoc($datosConsulta);
						$ControlDoc = '';
						$button = '<button class="btn btn-primary" id="ButtonC'.$dato->idproceso.'_'.$dato->idarchivo.'_'.strval($i+1).'_'.$idUsuario.'" onClick="InsertarDocumentoAlumno(\''.$dato->idproceso.'\',\''.$dato->idarchivo.'\',\''.strval($i+1).'\',\''.$idUsuario.'\')" disabled>Enviar Documento</button>';
						if($Consulta['data'] != []){
							//var_dump($Consulta['data'][0]['estatus']);
							$Mensaje = null;
							switch($Consulta['data'][0]['estatus']){
								case '1':
									$Mensaje = "Enviado";
									break;
								case '2':
									$Mensaje = "Revision";
									break;
								case '3':
									$Mensaje = "Aprobado";
									break;
							}
							$ControlDoc = '<div class = "row text-center form-group"><div class = "col-md-6">'.$Mensaje.'</div></div>';
							
						}
						//var_dump($button);
						//var_dump($Consulta);
						$accept = 'accept=".pdf,.doc,.docx,.xlsx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel"';
						$count++;
						$data[]=array(
							0=> $count,
							1=> '<b>'.$dato->procNombre.'</b>',
							2=> $dato->nombre,
							3=> ($i+1).' de '.$dato->vecesenvio,
							4=> $ControlDoc == '' ? '<div class = "row text-center"><div class = "col-md-6 col-sm-6"><input '.$accept.' class = "form-control especial" onchange ="HabilitarButton2('.$dato->idproceso.','.$dato->idarchivo.','.strval($i+1).','.$idUsuario.')" type="file" id="avatar_'.$dato->idproceso.'_'.$dato->idarchivo.'_'.strval($i+1).'_'.$idUsuario.'" name="avatar"></div><div class = "col-md-6 col-sm-6">'.$button.'</div></div>' : $ControlDoc

						);
					}
					
					//var_dump($datosConsulta);
					$datosConsulta = [];	
				}	
				$result = array(
					'sEcho'=>1,
					'iTotalRecords'=>count( $data ),
					'iTotalDisplayRecords'=>count( $data ),
					'aaData'=>$data
				);
			echo json_encode($result);
		break;
		case 'InsertarDocumentoAlumno':
			unset($_POST['action']);
			$Nombre = $_FILES['archivo']['name'];
			//var_dump($_FILES['archivo']['name']);
			//Crear el directorio a donde se subiran los archivos
			$tmp_name = $_FILES['archivo']['tmp_name'];
			$uploads_dir = "../../../../assets/files/servicio/alumnos";
			move_uploaded_file($tmp_name, "$uploads_dir/$Nombre");
			$_POST['nombreArchivo'] =  $_FILES['archivo']['name'];
			unset($_POST['archivo']);
			//var_dump($_POST);
			$NuevoFormato = $Servicio->InsertarFormatoRevision($_POST);
			echo json_encode($NuevoFormato);
			break;

		case 'CambiarDocumentoAlumno':
			unset($_POST['action']);
			$Nombre = $_FILES['archivo']['name'];
			//var_dump($Nombre);
			//var_dump($_FILES['archivo']['name']);
			//Crear el directorio a donde se subiran los archivos
			$tmp_name = $_FILES['archivo']['tmp_name'];
			$uploads_dir = "../../../../assets/files/servicio/alumnos";
			move_uploaded_file($tmp_name, "$uploads_dir/$Nombre");
			$_POST['nombreArchivo'] =  $_FILES['archivo']['name'];
			unset($_POST['archivo']);
			unset($_POST['idAlum']);
			unset($_POST['idPorc']);
			unset($_POST['idArch']);
			//var_dump($_POST);
			//die();
			$FormatoActual = $Servicio->ActualizarFormatoRevision($_POST);
			echo json_encode($FormatoActual);
			break;
		
		case 'VerificarEstatusServicio':
			unset($_POST['action']);
			$EstatusServicio = $Servicio->VerificarEstatusServicio($_POST['Usuario']);
			echo json_encode($EstatusServicio);
			break;
		
		case 'InsertarComentarioServicio':
			unset($_POST['action']);
			$comentarioInsertado = $Servicio->InsertarComentarioServicio($_POST);
			//$cambioestatus = $ce->CambiarEstatusDocumentoServicio(['idArchivo'=>$_POST['idArchivo'],'estatus'=>2]);
			echo json_encode($comentarioInsertado);
			break;

	default:
		echo "errorMethod";
		break;
}
//print_r($alumno->consultarTodoAlumnos());


?>
