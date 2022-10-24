<?php 
date_default_timezone_set("America/Mexico_City");

	class AdminPM{

        public function consultarAdminPM_ById($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM `ac_adminpm` WHERE id = :id;"; 

				$statement = $con->prepare($sql); 
				$statement->bindParam(":id", $id);
				$statement->execute();			  
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}

			$conexion = null;
			$con = null; 
			return $response;
		}

		public function consultarMedicos(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT DISTINCT pm_medicos.*, a_accesos.correo FROM pm_medicos, a_accesos WHERE pm_medicos.id = a_accesos.idPersona AND a_accesos.idTipo_Persona = 20";

				$statement = $con->prepare($sql);
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarMedicos

		public function desactivarMedico($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE pm_medicos SET estado = :vEstado WHERE id = :idDesactivar";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function agregarMedico($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			include('../../Model/acceso/keys.php');

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO pm_medicos (apellidop, apellidom, nombre, tipo) VALUES ( '".$data['apellidop']."', '".$data['apellidom']."', '".$data['nombres']."', '".$data['rol']."' )";
				$statement = $con->prepare($sql);
				$statement->execute();

				$sql = "INSERT INTO a_accesos (idTipo_Persona, idPersona, correo, contrasenia) VALUES ( 20, ".$con->lastInsertId().",  '".$data['email']."', AES_ENCRYPT('abc', '{$DECRYPT_PASS}') )";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//fin agregarMedico

		public function editarMedico($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			include('../../Model/acceso/keys.php');

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				if( isset( $data['reset_e'] ) ) $reset = ", contrasenia = AES_ENCRYPT('abc', '{$DECRYPT_PASS}') ";
				else $reset = '';

				$sql = "UPDATE pm_medicos SET apellidop = '".$data['apellidop_e']."', apellidom = '".$data['apellidom_e']."', nombre = '".$data['nombres_e']."', tipo = '".$data['rol_e']."' 
				WHERE id = ".$data['idMedico']."; 
				UPDATE a_accesos SET correo = '".$data['email_e']."' ".$reset." WHERE idPersona = ".$data['idMedico']." AND idTipo_Persona = 20";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//editarMedico

		public function buscarMedico($buscar){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT pm_medicos.*, a_accesos.correo FROM pm_medicos, a_accesos WHERE pm_medicos.id = $buscar AND a_accesos.idTipo_Persona = 20 AND a_accesos.idPersona = $buscar";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}//Fin buscarMedico

		public function consultarHospitales(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT * FROM pm_sitios";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarHospitales

		public function agregarHospital($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO pm_sitios (nombre, direccion) VALUES ( '".$data['nombre']."', '".$data['direccion']."' )";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//Fin agregarHospital

		public function buscarHospital($buscar){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM pm_sitios WHERE idsitio = $buscar";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}//Fin buscarHospital

		public function editarHospital($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE pm_sitios SET nombre = '".$data['nombre_e']."', direccion = '".$data['direccion_e']."' WHERE idsitio = ".$data['idHospital'];
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//editarHospital

		public function desactivarHospital($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE pm_sitios SET estado = :vEstado WHERE idsitio = :idDesactivar";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//Fin DesactivarHospital

		public function consultarProcedimientos(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT pm_procedimientos.*, a_carreras.nombre AS carrera_nombre FROM pm_procedimientos, a_carreras WHERE a_carreras.idCarrera = pm_procedimientos.idCarrera";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarProcedimientos

		public function buscarCarreras(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "SELECT nombre, idCarrera FROM a_carreras WHERE nombre LIKE 'Maestría en Medicina Estética y Longevidad' ORDER BY nombre";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//buscarCarreras

		public function agregarProcedimiento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "INSERT INTO pm_procedimientos (nombre, idCarrera, costo, descripcion) VALUES ( '".$data['nombrep']."', ".$data['listacarreras'].", '".$data['costop']."', '".$data['descripcionp']."' )";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//Fin agregarProcedimiento

		public function desactivarProcedimiento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE pm_procedimientos SET estado = :vEstado WHERE idpm = :idDesactivar";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//Fin DesactivarHospital

		public function buscarProcedimiento($buscar){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT * FROM pm_procedimientos WHERE idpm = $buscar";
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}//Fin buscarProcedimiento

		public function editarProcedimiento($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE pm_procedimientos SET nombre = '".$data['nombre_ep']."', idCarrera = ".$data['listacarreras_e'].", costo = ".$data['costo_ep'].", descripcion = '".$data['descripcion_ep']."' WHERE idpm = ".$data['idProcedimiento'];
				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}//editarProcedimiento

		public function consultarExpedientes(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Cancelar procedimientos que tengan más de 168 horas en estado de revisión (ESTADO = 3)
				$sql = "UPDATE pm_expedientes SET estado = 8, comentarios = CONCAT( comentarios, '[', now(), '] MOTIVO DE CANCELACIÓN: Se excedió el tiempo de espera para revisión/corrección del expediente.' ) 
				WHERE estado = 3 AND TIMESTAMPDIFF( HOUR, factualizacion, now() ) > 168";
				$statement = $con->prepare($sql);
				$statement->execute();

				/*$sql = "SELECT pm_expedientes.*, nombres, apellidoPaterno, apellidoMaterno, pm_procedimientos.nombre AS nompre_proc, TIMESTAMPDIFF( HOUR, factualizacion, now() ) AS atendido 
				FROM pm_expedientes, a_alumnos, pm_procedimientos 
				WHERE a_alumnos.id_alumno = pm_expedientes.idalumno 
				AND pm_expedientes.idpm = pm_procedimientos.idpm";*/

				$sql = "SELECT pm_expedientes.*, afiliados_conacon.nombre AS nombres, afiliados_conacon.apaterno AS apellidoPaterno, afiliados_conacon.amaterno AS apellidoMaterno, pm_procedimientos.nombre AS nompre_proc, TIMESTAMPDIFF( HOUR, factualizacion, now() ) AS atendido 
				FROM pm_expedientes, afiliados_conacon, pm_procedimientos 
				WHERE afiliados_conacon.id_afiliado = pm_expedientes.idalumno 
				AND pm_expedientes.idpm = pm_procedimientos.idpm";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//consultarExpedientes

		public function dirAlumnos(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				/*$sql = "SELECT aPros.correo, aPros.telefono, gen.idGeneracion, gen.nombre AS ngeneracion, alumGen.idalumno AS id_prospecto, 
				aPros.nombre AS nombres, aPros.aPaterno AS apellidoPaterno, aPros.aMaterno AS apellidoMaterno, afiliados_conacon.id_afiliado, gen.idCarrera, a_carreras.nombre AS nombreCarrera, a_carreras.area
				
                FROM a_carreras, afiliados_conacon, a_generaciones gen INNER JOIN alumnos_generaciones alumGen ON alumGen.idgeneracion = gen.idGeneracion 
				INNER JOIN a_prospectos aPros ON aPros.idAsistente = alumGen.idalumno 
				
                WHERE afiliados_conacon.id_prospecto = alumGen.idalumno 
				AND a_carreras.idCarrera = gen.idCarrera 
				AND a_carreras.area='Ciencias Naturales y de la Salud' GROUP BY id_prospecto";*/

				/*$sql = "SELECT alumnos_generaciones.idgeneracion, a_generaciones.nombre AS ngeneracion, a_generaciones.idCarrera AS idCarrera, 
				a_carreras.nombre AS nombreCarrera, afiliados_conacon.apaterno AS apellidoPaterno, afiliados_conacon.amaterno AS apellidoMaterno, 
				afiliados_conacon.nombre AS nombres, afiliados_conacon.email AS correo, afiliados_conacon.celular AS telefono 
				FROM afiliados_conacon, alumnos_generaciones , a_generaciones, a_carreras
				WHERE id_afiliado = alumnos_generaciones.idalumno 
				AND a_generaciones.idGeneracion = alumnos_generaciones.idgeneracion 
				AND a_carreras.idCarrera = a_generaciones.idCarrera;";*/

				/*$sql = "SELECT pr.nombre AS nombres, pr.aPaterno, pr.aMaterno, pr.correo, car.nombre AS Carrera, gen.nombre AS generacion_nombre FROM a_prospectos pr
				JOIN alumnos_generaciones ag ON ag.idalumno = pr.idAsistente
				JOIN a_generaciones gen on gen.idGeneracion = ag.idgeneracion
				JOIN a_carreras car ON car.idCarrera = gen.idCarrera 
				WHERE car.nombre LIKE 'Maestría en Medicina Estética y Longevidad';";*/

				$sql = "SELECT pr.nombre AS nombres, pr.aPaterno AS apellidoPaterno, pr.aMaterno AS apellidoMaterno, pr.correo, pr.telefono, car.nombre AS nombreCarrera, gen.nombre AS ngeneracion FROM a_prospectos pr
				JOIN alumnos_generaciones ag ON ag.idalumno = pr.idAsistente
				JOIN a_generaciones gen on gen.idGeneracion = ag.idgeneracion
				JOIN a_carreras car ON car.idCarrera = gen.idCarrera
				WHERE car.idCarrera = 85";

				$statement = $con->prepare($sql);
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}//dirAlumnos
	
    }
?>