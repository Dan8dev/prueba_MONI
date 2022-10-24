<?php 
date_default_timezone_set("America/Mexico_City");
	class Prospecto {
		var $tipos_prospectos = ['evento', 'carrera'];
		var $etapas = [
			// 'pendiente'=>0,
			// 'espera' => 1,
			// 'confirmado' => 2,
			// 'rechazo' => 3,
			// 'nointeresado' => 4,
		];
		var $etapas_num = [
			//0, 1, 2, 3, 4
		];
		function __construct(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			//$response = $con->query("SELECT * FROM a_prospectos WHERE estatus = 1")->fetchAll(PDO::FETCH_ASSOC);
			$response = $con->query("SELECT * FROM a_marketing_catalogo WHERE estatus = 1")->fetchAll(PDO::FETCH_ASSOC);
			foreach($response as $key => $value){
				$this->etapas[$value['clave']] = $value['idElemento'];
				$this->etapas_num[] = $value["idElemento"];
			}
		}

		public function prospectos_seguimiento($estatus = true, $tipo){ // true consulta asistentes que ya estan en seguimiento por mktng
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok" && in_array($tipo, $this->tipos_prospectos)){
				$con = $con["conexion"];

				$kw = ($estatus)? 'IN' : 'NOT IN';

				$not_null = ($tipo == 'evento')? 'ea.idCarrera IS NULL' : 'ea.idEvento IS NULL';

				$sql = "SELECT ea.* FROM a_prospectos ea WHERE ea.`idAsistente` {$kw} (SELECT mk_a.prospecto FROM a_marketing_atencion mk_a WHERE tipo_atencion = '{$tipo}') AND {$not_null};";
				
				$statement = $con->prepare($sql);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function listar_prospectos_tipo_atencion($tipo, $id = false, $estatus_seguim = 1, $ejecutiva = false, $prospecto = false){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok" && in_array($tipo, $this->tipos_prospectos)){
				$con = $con["conexion"];
				$join_tipo = '';
				$campo_tipo = '';
				if(!in_array($tipo, ['evento', 'carrera'])){
					$tipo = 'evento';
				}
				if($tipo == 'evento'){
					$join_tipo = 'JOIN ev_evento ev ON ev.idEvento = aten.evento_carrera';
					$campo_tipo = ',ev.titulo, ev.tipo, ev.idInstitucion';
				}else{
					$join_tipo = 'JOIN a_carreras crr ON crr.idCarrera = aten.evento_carrera AND crr.estatus = 1';
					$campo_tipo = ',crr.nombre as nombre_carrera, crr.tipo, crr.idInstitucion';
				}
				$query_evc = "";
				if($id !== false){
					$query_evc = " AND aten.evento_carrera = {$id}";
					$campo_tipo .= ", (SELECT gen.nombre FROM a_generaciones gen JOIN alumnos_generaciones agen ON agen.idgeneracion = gen.idGeneracion WHERE agen.idalumno = prs.idAsistente AND gen.idCarrera = {$id} LIMIT 1) as generacion_asignada";
				}
				$str_ejecutiva = '';
				if($ejecutiva !== false){
					$str_ejecutiva = " AND aten.idMk_persona = {$ejecutiva}";
				}
				$str_prospecto = '';
				if($prospecto !== false){
					$str_prospecto = " AND aten.prospecto = {$prospecto}";
				}

				$sql = "SELECT aten.*, prs.nombre, prs.aPaterno, prs.aMaterno, prs.correo, prs.telefono, afc.id_afiliado, mp.nombres as nombres_asesor, mp.apellidoPaterno as apaterno_asesor {$campo_tipo} FROM `a_marketing_atencion` aten 
				JOIN a_prospectos prs ON prs.idAsistente = aten.prospecto
				{$join_tipo}
				JOIN a_marketing_personal mp ON mp.idPersona = aten.idMk_persona
				LEFT JOIN afiliados_conacon afc ON afc.id_prospecto = prs.idAsistente
				WHERE aten.tipo_atencion = '{$tipo}' {$query_evc} AND aten.etapa = {$estatus_seguim} {$str_ejecutiva} {$str_prospecto};";
				$statement = $con->prepare($sql);
				// var_dump($sql);
				
				$statement->execute();
				if($statement->errorInfo()[0] == "00000"){
					$datos = $statement->fetchAll(PDO::FETCH_ASSOC);
					if($tipo == 'carrera'){
						require_once '../../Model/carreras/carrerasModel.php';
						$cr = new Carrera();
						$tipos_carrer = $cr->tipo_carreras;
						foreach ($datos as $key => $value) {
							if(in_array($value['tipo'], array_keys($tipos_carrer))){
								$datos[$key]['tipo_carrera_text'] = $tipos_carrer[$value['tipo']];
							}else{
								$datos[$key]['tipo_carrera_text'] = '';
							}
						}
					}
					$response = ["estatus"=>"ok", "data"=>$datos];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function confirmar_asistencia_prospecto($evento, $prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `a_marketing_atencion` SET etapa = 2 WHERE tipo_atencion = 'evento' AND prospecto = :prospecto;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':prospecto', $prospecto);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function rechazar_asistencia_prospecto($evento, $prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "UPDATE `a_marketing_atencion` SET etapa = 3 WHERE tipo_atencion = 'evento' AND prospecto = :prospecto;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':prospecto', $prospecto);
				$statement->execute();


				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function registrarAsistencia($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$quer_genero = ['',''];
				if(isset($data['genero'])){
					$quer_genero[0] = ', Genero';
					$quer_genero[1] = ', :genero';
				}
				// cuando el formulario incluye numero de casa
				$q_tel_c = ['',''];
				if(isset($data['telefono_casa'])){$q_tel_c = [', telefono_casa', ', :telefono_casa'];}
				
				// cuando el formulario incluye numero de recados
				$q_tel_rec = ['',''];
				if(isset($data['telefono_recados'])){$q_tel_rec = [', telefono_recados', ', :telefono_recados'];}
				
				// cuando incluye titulo y cedula
				$q_titulo = ['',''];
				if(isset($data['inp_titulo_estudio'])){$q_titulo = [', grado_academico, cedula', ', :inp_titulo_estudio, :inp_cedula'];}
				
				// cuando incluye nacionalidad
				$q_nacionalidad = ['',''];
				if(isset($data['select_nacionalidad'])){$q_nacionalidad = [', nacionalidad', ', :select_nacionalidad'];}
				
				// cuando incluye escuela_proc
				$q_escuela_proc = ['',''];
				if(isset($data['escuela_proc'])){$q_escuela_proc = [', escuela_procedencia', ', :escuela_proc'];}
				
				// cuando incluye fecha_egreso
				$q_fecha_egreso = ['',''];
				if(isset($data['fecha_egreso'])){$q_fecha_egreso = [', fecha_egreso', ', :fecha_egreso'];}

				$sql = "INSERT INTO `a_prospectos`(`idEvento`,`idCarrera`, `nombre`, `aPaterno`, `aMaterno`, `correo`, `telefono`, `codigo`,`idAsociacion`, `codigo_promocional`, `tipoPago` {$quer_genero[0]} {$q_tel_c[0]} {$q_tel_rec[0]} {$q_titulo[0]} {$q_nacionalidad[0]} {$q_escuela_proc[0]} {$q_fecha_egreso[0]}) 
						VALUES (:idEvento, :idCarrera, :nombre, :aPaterno, :aMaterno, :correo, :telefono,:codigo, :idAsociacion, :codigo_promocional, :tipo_moneda_prospecto {$quer_genero[1]} {$q_tel_c[1]} {$q_tel_rec[1]} {$q_titulo[1]} {$q_nacionalidad[1]} {$q_escuela_proc[1]} {$q_fecha_egreso[1]});";

				$statement = $con->prepare($sql);

				$statement->execute($data);


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function registrarPagoEvento($evento, $persona, $detalle, $plan_pago, $comprobante){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `a_pagos`(`id_prospecto`, `id_evento`, `id_concepto`, `detalle_pago`, `comprobante`) VALUES (:persona, :evento, :plan_pago, :detalles, :comprobante)";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(":persona", $persona);
				$statement->bindParam(":evento", $evento);
				$statement->bindParam(":plan_pago", $plan_pago);
				$statement->bindParam(":detalles", $detalle);
				$statement->bindParam(":comprobante", $comprobante);

				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>'ok', "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		
		public function asignarmembresiagratis($evento, $persona, $detalle, $plan_pago, $finmembresia){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO `a_pagos`(`id_prospecto`, `id_evento`, `id_concepto`, `detalle_pago`,`vencepago` ) VALUES (:persona, :evento, :plan_pago, :detalles, :vencepago)";
				
				$statement = $con->prepare($sql);
				
				$statement->bindParam(":persona", $persona);
				$statement->bindParam(":evento", $evento);
				$statement->bindParam(":plan_pago", $plan_pago);
				$statement->bindParam(":detalles", $detalle);
				$statement->bindParam(":vencepago", $finmembresia);

				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>'ok', "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		
		# funciones de acceso al panel para evento

		public function validar_acceso_asistente($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT ea.*, ev.titulo, (SELECT ma.etapa FROM a_marketing_atencion ma WHERE ma.prospecto = ea.idAsistente AND ma.tipo_atencion = 'evento') AS etapa_seguimiento
				FROM a_prospectos ea
				INNER JOIN ev_evento ev ON ev.idEvento = ea.idEvento
				WHERE ea.correo = :inpCorreo AND ea.codigo = :inpPassw;";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);


				if($statement->errorInfo()[0] == '00000'){
					$infoEv = $statement->fetchAll(PDO::FETCH_ASSOC);
					$response["estatus"] = "ok";
					if(!empty($infoEv)){
						$evtM = new Evento();
						$infoEv[0]['evento'] = $evtM->consultarEvento_Id($infoEv[0]['idEvento'])['data'];
					}
					
					$response["data"] = $infoEv;
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		# consultar pagos de los prospectos a eventos 
		public function consultar_pagos_prospectos($prospecto, $evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT pg.*, con.concepto as plan_pago FROM a_pagos pg
					INNER JOIN pagos_conceptos con ON pg.id_concepto = con.id_concepto
					WHERE id_prospecto = :prospecto;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto);
				//$statement->bindParam(":evento", $evento);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					$pagos = $statement->fetchAll(PDO::FETCH_ASSOC);
					if(!empty($pagos)){
						for ($i=0; $i < sizeof($pagos); $i++) { 
							$pagos[$i]['detalle_pago'] = json_decode($pagos[$i]['detalle_pago'], true);
						}
					}
					$response = ["estatus"=>"ok", "data"=>$pagos];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$prospecto];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}
		
			function conceptos_pago(){
				$conexion = new Conexion();
				$con = $conexion->conectar();
			
				return $con['conexion']->query("SELECT * FROM pagos_conceptos ORDER BY descripcion;", PDO::FETCH_ASSOC)->fetchAll();
			}

		public function consultar_todo_pagos_prospecto($prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT pag.id_concepto, pag.detalle_pago, concepto.descripcion, concepto.concepto, afil.email, SUBSTRING(pag.detalle_pago, LOCATE('value',pag.detalle_pago)+8, LOCATE('payee',pag.detalle_pago) - LOCATE('value',pag.detalle_pago) - 12) as monto_pago
					FROM `a_pagos` pag 
					INNER JOIN afiliados_conacon afil ON afil.id_prospecto = pag.id_prospecto 
					INNER JOIN pagos_conceptos concepto ON pag.id_concepto = concepto.id_concepto
					WHERE pag.id_prospecto = :prospecto;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto, PDO::PARAM_INT);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function obtenerdatosprospecto($idProspecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT *
						FROM a_prospectos
						WHERE idAsistente=:idProspecto;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":idProspecto", $idProspecto, PDO::PARAM_INT);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function consultar_info_prospecto_afiliado($prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT afil.*, prosp.nombre, prosp.aPaterno, prosp.aMaterno
					FROM `afiliados_conacon` afil 
					INNER JOIN a_prospectos prosp ON prosp.idAsistente = afil.id_prospecto 
					WHERE afil.id_prospecto = :prospecto;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto, PDO::PARAM_INT);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
					$datos = $statement->fetch(PDO::FETCH_ASSOC);
					if($datos){
						unset($datos['contrasenia']);
					}
					$response = ["estatus"=>"ok", "data"=>$datos];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}
		function consultar_info_prospecto_a($prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT prosp.*, prosp.idAsistente as id_prospecto, prosp.aPaterno as apaterno, prosp.aMaterno as amaterno, prosp.correo as email, prosp.telefono as celular
					FROM a_prospectos prosp
					WHERE prosp.idAsistente = :prospecto;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto, PDO::PARAM_INT);
				$statement->execute();


				if($statement->errorInfo()[0] == 00000){
					$datos = $statement->fetch(PDO::FETCH_ASSOC);
					if($datos){
						unset($datos['contrasenia']);
					}
					$response = ["estatus"=>"ok", "data"=>$datos];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function asistente_talleres_reservados($asistente){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT asist_t.*, tall.nombre FROM ev_asistente_talleres asist_t 
				JOIN ev_talleres tall ON asist_t.id_taller = tall.id_taller
				WHERE asist_t.id_asistente = :asistente ";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":asistente", $asistente);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function asistente_talleres_reservados_evento($asistente, $evento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT asist_t.*, tall.nombre, tall.salon, tall.fecha as fecha_tll FROM ev_asistente_talleres asist_t 
				JOIN ev_talleres tall ON asist_t.id_taller = tall.id_taller
				WHERE asist_t.id_asistente = :asistente AND tall.id_evento = :evento AND asist_t.estatus = 1;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":asistente", $asistente);
				$statement->bindParam(":evento", $evento);
				$statement->execute();


				if($statement->errorInfo()[0] == '00000'){
					
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>[$asistente, $evento]];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function apartar_talleres($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "INSERT INTO ev_asistente_talleres (id_asistente, id_taller, fecha_registro)
				VALUES (:prospecto, :taller, :fecha); ";
				
				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		# consultar comentarios / llamadas realizadas por call-center
		public function consultar_historial_seguimientos($prospecto_atencion, $tipo){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$tabla = ($tipo == 'llamadas') ? 'mk_atencion_llamadas' : 'mk_atencion_comentarios';
				$ordernador = ($tipo == 'llamadas') ? 'fecha_llamar' : 'fecha';
				if($tipo == 'llamadas'){
					$sql = "SELECT llam.*, comen.detalles FROM `mk_atencion_llamadas` llam
							LEFT JOIN mk_atencion_comentarios comen ON comen.idComentario = llam.idComentario
							WHERE llam.idAtencion = :prospecto_atencion ORDER BY {$ordernador} DESC;";
				}else{
					$sql = "SELECT * FROM {$tabla} WHERE idAtencion = :prospecto_atencion ORDER BY {$ordernador} DESC;";
				}
				
				$statement = $con->prepare($sql);

				$statement->bindParam(':prospecto_atencion',$prospecto_atencion);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC), 'tipo' => $tipo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}

		public function registrar_comentario_seguimiento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "INSERT INTO mk_atencion_comentarios (idAtencion, fecha, detalles, idEjecutiva) VALUES 
				(:id_atencion, :fecha, :inp_comentario, :ejecutiva);";
				
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}
		public function agendar_llamada_seguimiento($prospecto_llamar, $fecha_llamada){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "INSERT INTO mk_atencion_llamadas (idAtencion, fecha_llamar, estatus, idComentario) VALUES 
				(:prospecto_llamar, :fecha_llamada, 1, null);";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':prospecto_llamar',$prospecto_llamar);
				$statement->bindParam(':fecha_llamada',$fecha_llamada);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>[$prospecto_llamar, $fecha_llamada]];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}

		public function actualizar_llamada_seguimiento($idLlamada, $estatus, $comentario = null){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "UPDATE mk_atencion_llamadas SET estatus = :estatus, idComentario = :comentario WHERE idLlamada = :llamada;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':llamada',$idLlamada);
				$statement->bindParam(':estatus',$estatus);
				$statement->bindParam(':comentario',$comentario);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>[$idLlamada,$estatus,$comentario]];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}

		public function consultar_prospecto_by_campo($campo, $valor){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT * FROM a_prospectos WHERE {$campo} = :valor;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(':valor',$valor);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}
		function actalizar_informacion_contacto($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$str_tipo_moneda = "";
				if(isset($data['editar_tipo_moneda'])){
					$str_tipo_moneda = ",`tipoPago` = :editar_tipo_moneda ";
				}
				$q_select_edit_nacionalidad = '';
				$q_pais_nacimiento = ''; // <-- para afiliados
				if(isset($data['select_edit_nacionalidad'])){
					$q_select_edit_nacionalidad = ', nacionalidad = :select_edit_nacionalidad';
					$q_pais_nacimiento = ', pais_nacimiento = :select_edit_nacionalidad';
				}

				$q_edit_genero = '';
				if(isset($data['edit_genero'])){
					$q_edit_genero = ', Genero = :edit_genero';
				}

				$q_inp_edit_titulo_estudio = '';
				if(isset($data['inp_edit_titulo_estudio'])){
					$q_inp_edit_titulo_estudio = ', grado_academico = :inp_edit_titulo_estudio';
				}

				$q_edit_escuela_proc = '';
				if(isset($data['edit_escuela_proc'])){
					$q_edit_escuela_proc = ', escuela_procedencia = :edit_escuela_proc';
				}

				$q_edit_fecha_egreso = '';
				if(isset($data['edit_fecha_egreso'])){
					$q_edit_fecha_egreso = ', fecha_egreso = :edit_fecha_egreso';
				}

				$q_inp_edit_cedula = '';
				$q_cedulap = ''; // <--- para afiliados
				if(isset($data['inp_edit_cedula'])){
					$q_inp_edit_cedula = ', cedula = :inp_edit_cedula';
					$q_cedulap = ', cedulap = :inp_edit_cedula';
				}

				$q_edit_pr_telefono_c = '';
				if(isset($data['edit_pr_telefono_c'])){
					$q_edit_pr_telefono_c = ', telefono_casa = :edit_pr_telefono_c';
				}
				$q_edit_pr_telefono_rec = '';
				if(isset($data['edit_pr_telefono_rec'])){
					$q_edit_pr_telefono_rec = ', telefono_recados = :edit_pr_telefono_rec';
				}


				$sql = "UPDATE `a_prospectos` 
				SET `nombre` = :edit_pr_nombre, `aPaterno` = :edit_pr_apaterno, `aMaterno` = :edit_pr_amaterno, `telefono` = :edit_pr_telefono, `correo` = :edit_pr_correo, `idAsociacion` = :edit_pr_institucion {$str_tipo_moneda} {$q_select_edit_nacionalidad} {$q_edit_genero} {$q_inp_edit_titulo_estudio} {$q_edit_escuela_proc} {$q_edit_fecha_egreso} {$q_inp_edit_cedula} {$q_edit_pr_telefono_c} {$q_edit_pr_telefono_rec}
				WHERE idAsistente = :inp_prospect_edit;
				UPDATE `afiliados_conacon` SET email = :edit_pr_correo, `nombre` = :edit_pr_nombre, `apaterno` = :edit_pr_apaterno, `amaterno` = :edit_pr_amaterno, celular = :edit_pr_telefono {$q_inp_edit_titulo_estudio} {$q_pais_nacimiento} {$q_cedulap} WHERE id_prospecto = :inp_prospect_edit";

				$statement = $con->prepare($sql);

				$statement->execute($data);

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		function actalizar_curp_afiliado($curp, $clave){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$str_tipo_moneda = "";
				
				$sql = "UPDATE `afiliados_conacon` SET curp = :curp_nueva WHERE id_prospecto = :inp_prospect_edit";

				$statement = $con->prepare($sql);
				$statement->bindParam(':curp_nueva',$curp);
				$statement->bindParam(':inp_prospect_edit',$clave);
				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount(), "sql"=>$sql];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}


		function actualizar_dato_afiliado($campo, $valor, $id_prospecto){
			 $conexion = new Conexion();
			 $con = $conexion->conectar()['conexion'];
			 return $con->query("UPDATE afiliados_conacon SET `$campo` = '$valor' WHERE id_prospecto = '$id_prospecto'");
		}

		function reset_carrera_atencion($carrera, $asistente){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$fecha = date('Y-m-d H:i:s');
				$sql = "UPDATE `a_prospectos` SET `idCarrera` = :carrera , `idEvento` = null, fecha_registro = '$fecha' WHERE `idAsistente` = :asistente;";

				$statement = $con->prepare($sql);
				$statement->bindParam(':carrera',$carrera);
				$statement->bindParam(':asistente',$asistente);

				$statement->execute();

				if($statement->errorInfo()[0] == '00000'){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					// $response = ["estatus"=>"ok", "data"=>$correo];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function obtener_instituciones_afiliados($prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT af_in.*, inst.* FROM `instituciones_afiliados` af_in 
				JOIN a_instituciones inst ON inst.id_institucion = af_in.id_institucion
				WHERE af_in.id_prospecto = :prospecto;";
				
				$statement = $con->prepare($sql);

				$statement->bindParam(":prospecto", $prospecto);

				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "data"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}

		public function obtener_afiliados_instituciones($instituciones){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT af_in.*, CONCAT(prosp.aPaterno,' ',prosp.aMaterno,' ',prosp.nombre) AS afiliado_nom 
				FROM `instituciones_afiliados` af_in 
				JOIN a_prospectos prosp ON prosp.idAsistente = af_in.id_prospecto
				WHERE af_in.id_institucion IN ({$instituciones});";
				
				$statement = $con->prepare($sql);

				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "data"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;			
		}
		public function get_estatus_seguimiento($seguimiento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			return $con['conexion']->query("SELECT * FROM a_marketing_atencion WHERE idReg = {$seguimiento}")->fetch(PDO::FETCH_ASSOC);
		}

		public function actualizar_estatus_seguimiento($estatus, $seguimiento){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				if(in_array($estatus, array_keys($this->etapas)) || in_array($estatus, $this->etapas_num)){
					$con = $con["conexion"];
					
					$sql = "UPDATE a_marketing_atencion SET etapa = :estatus WHERE idReg = :idReg;";
					
					$statement = $con->prepare($sql);
					$fin_stat = (in_array($estatus, array_keys($this->etapas)) ? $this->etapas[$estatus] : $estatus);
					$statement->bindParam(':estatus',$fin_stat);
					$statement->bindParam(':idReg',$seguimiento);
					$statement->execute();
	
					if($statement->errorInfo()[0] == "00000"){
						$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					}else{
						$response = ["estatus"=>"error", "data"=>$statement->errorInfo()];
					}
				}else{
					$response = ["estatus"=>"error", "data"=>"Estatus no valido"];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function actualizar_destino($seguimiento, $id_cambio){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "UPDATE a_marketing_atencion SET evento_carrera = :cambio WHERE idReg = :idReg;";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':idReg',$seguimiento);
				$statement->bindParam(':cambio',$id_cambio);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>"Error al actualizar el estatus", "data"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}


		// CONSULTAR ALUMNOS CON CONCEPTOS DE PAGOS
		function consultar_alumnos_con_conceptos_pago($conceptos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$concept_str = "";
				if(gettype($conceptos) == 'array'){
					$concept_str = implode(', ', $conceptos);
				}elseif(gettype($conceptos) == 'integer' || gettype($conceptos) == 'string'){
					$concept_str = $conceptos;
				}

				$sql = "SELECT pags.*, prosp.* FROM a_pagos pags 
						JOIN a_prospectos prosp ON pags.id_prospecto = prosp.idAsistente
						WHERE pags.id_concepto IN ({$concept_str}) ORDER BY pags.id_prospecto; ";
				
				$statement = $con->prepare($sql);

				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "data"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		public function quitaracentosconvertirmayusculas($cadena){
			$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
			$permitidas= array ("a","e","i","o","u","A","E","I","O","U","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
			$texto = str_replace($no_permitidas, $permitidas ,$cadena);
			return strtoupper($texto);
		}

		function consultar_eventos_prospecto($prospecto){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$sql = "SELECT aten.*, ev.tipo as tipo_evento, ev.titulo, ev.webex_id, ev.fechaLimite, ev.fechaDisponible FROM a_marketing_atencion aten 
				JOIN ev_evento ev ON ev.idEvento = aten.evento_carrera
				WHERE aten.prospecto = :prospecto AND aten.tipo_atencion = 'evento';";
				
				$statement = $con->prepare($sql);
				$statement->bindParam(':prospecto',$prospecto);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "data"=>$statement->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
		}

		function consultarEmail($post){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				
				$seleMAil = "SELECT correo FROM a_prospectos WHERE 	idAsistente = '$post'";
				$st = $con->prepare($seleMAil);
				$st->execute();

				if($st->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$st->fetch(PDO::FETCH_ASSOC)['correo']];
				}else{
					$response = ["estatus"=>"error", "data"=>$st->errorInfo()];
				}
			}

			$conexion = null;
			$con = null;
		
			return $response;
			

		}

		function validar_email_afiliado($correo){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];
			$sql = "SELECT * FROM afiliados_conacon WHERE email = :correo";
			$statement = $con->prepare($sql);
			$statement->bindParam(':correo',$correo);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC);
		}

		function consultar_lista_prospectos_evento_clinica(){
			$sql = "SELECT aten.*, pr.correo, pr.idAsociacion, inst.nombre FROM `a_marketing_atencion` aten 
			JOIN a_prospectos pr ON pr.idAsistente = aten.prospecto
			JOIN a_instituciones inst ON inst.id_institucion = pr.idAsociacion
			WHERE aten.`tipo_atencion` = 'evento' AND aten.`evento_carrera` = 47 AND pr.idAsociacion != 0 AND pr.idAsociacion IS NOT NULL AND inst.fundacion = 1 AND inst.acuerdo = 0";
		}

		function busqueda_prospecto($palabra, $afiliados = false){
			$conexion = new Conexion();
			$con = $conexion->conectar()['conexion'];
			$str_afiliados = '';

			if($afiliados){
				$str_afiliados = "JOIN afiliados_conacon afc ON afc.id_prospecto = prs.idAsistente ";
			}

			$sql = "SELECT prs.* FROM a_prospectos prs {$str_afiliados} WHERE 
				REPLACE(UPPER(CONCAT(prs.nombre, prs.aPaterno, prs.aMaterno)),' ', '') REGEXP :expresion OR
				REPLACE(UPPER(CONCAT(prs.aPaterno, prs.aMaterno, prs.nombre)),' ', '') REGEXP :expresion OR
				REPLACE(UPPER(CONCAT(prs.aMaterno, prs.aPaterno, prs.nombre)),' ', '') REGEXP :expresion OR
				UPPER(prs.correo) REGEXP :expresion;
			";
			// $sql = "SELECT * FROM a_prospectos WHERE REPLACE(UPPER(CONCAT(aMaterno, aPaterno, nombre)),' ', '') REGEXP :expresion";
			$stmt = $con->prepare($sql);
			$val = '.*'.$palabra.'*';
			$stmt->bindParam(':expresion', $val);
			$stmt->execute();

			return $stmt->fetchAll();
		}

		function consultar_alumno_evento($alumno, $evento, $tipo = 'prospecto'){
			$conexion = new Conexion();
			$con = $conexion->conectar()["conexion"];
			if($tipo == 'prospecto'){
				$sql = "SELECT * FROM a_marketing_atencion WHERE `prospecto` = :alumno AND `evento_carrera` = :evento AND tipo_atencion = 'evento';";
			}else{
				$sql = "SELECT prs.*  FROM `a_marketing_atencion` prs 
					JOIN afiliados_conacon afc ON afc.id_prospecto = prs.prospecto
					WHERE afc.id_afiliado = :alumno AND prs.`evento_carrera` = :evento AND tipo_atencion = 'evento';";
			}
			$statement = $con->prepare($sql);
			$statement->bindParam(':alumno',$alumno);
			$statement->bindParam(':evento',$evento);
			
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
	}

	#SELECT UPPER(CONCAT(af.nombre," ",af.apaterno," ",af.amaterno)) as nombre, af.email, af.celular FROM afiliados_conacon af JOIN a_marketing_atencion aten ON aten.prospecto = af.id_prospecto AND aten.tipo_atencion = 'evento' JOIN ev_evento evs ON evs.idEvento = aten.evento_carrera WHERE evs.idInstitucion = 13 AND af.id_prospecto NOT IN ( SELECT ac.id_prospecto FROM afiliados_conacon ac JOIN alumnos_generaciones ag ON ag.idalumno = ac.id_prospecto AND ag.estatus NOT IN(2,5) JOIN a_generaciones gen ON gen.idGeneracion = ag.idgeneracion JOIN a_carreras carr ON carr.idInstitucion = 13 GROUP BY ac.id_afiliado ) GROUP BY af.email  
	/*SELECT ac.id_afiliado, UPPER(CONCAT(ac.nombre," ",ac.apaterno," ",ac.amaterno)), ac.email, ac.celular FROM afiliados_conacon ac
	JOIN alumnos_generaciones ag ON ag.idalumno = ac.id_prospecto AND ag.estatus NOT IN(2,5)
	JOIN a_generaciones gen ON gen.idGeneracion = ag.idgeneracion
	JOIN a_carreras carr ON carr.idInstitucion = 13
	GROUP BY ac.id_afiliado;*/
?>
