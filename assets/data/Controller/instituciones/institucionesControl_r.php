 <?php 
if (isset($_POST["action"])) {
	date_default_timezone_set("America/Mexico_City");
	require_once '../../Model/conexion/conexion.php';
	require_once '../../Model/institucion/institucionModel.php';
	
	$instM = new Institucion();

	switch ($_POST["action"]) {
		case 'lista_instituciones':
			echo json_encode($instM->consultarTodoInstituciones());
			break;
		case 'registrar_clinica':
			unset($_POST["action"]);
			$required = ["name_cl", "paterno_cl", "materno_cl", "name_clinica_cl", "email_cl", "telefono_cl", "direccion_cl", "flexRadioDefault", "nombre_clave_destino", "capacidad_cl"];
			$completo = true;
			$faltante = '';
			foreach($required as $req){
				if(!isset($_POST[$req]) || trim($_POST[$req]) == ""){
					$completo = false;
					$faltante = $req;
				}
			}
			if(!$completo){
				echo json_encode(["estatus"=>"error", "info"=>"Faltan datos", 'detalles'=>$faltante]);
			}else{
				$existente = $instM->consultar_institucion_existente($_POST["email_cl"]);
				if(!$existente){
					require_once '../../functions/correos_prospectos.php';
						// contenido constante del mail
					$destinatarios = [[$_POST['email_cl'],$_POST['name_clinica_cl']]];
					$plantilla = 'nueva_plantilla_registro_clinica.html';

					$asunto = 'Confirmación de registro.';

					$claves = ['%%prospecto'];
					$valores = [ $_POST["name_clinica_cl"]];
							

					$send = enviar_correo_registro($asunto, $destinatarios, $plantilla, $claves, $valores);

					$insertar = $instM->insertar_institucion_fundacion($_POST);
					if($insertar > 0){
						echo json_encode(["estatus"=>"ok","data" => $insertar, "info"=>"Registro exitoso","correo"=>$send]);
					}else{
						echo json_encode(["estatus"=>"error", "info"=>"Error al registrar"]);
					}
				}else{
					echo json_encode(["estatus"=>"error", "info"=>"Ya se tiene una institución registrada con este correo"]);
				}
			}
			break;
		default:
			echo json_encode(["estatus"=>"error","info"=>"noaction"]);
			break;
	}
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
