<?php 
date_default_timezone_set("America/Mexico_City");
	class Hotel{
		// funciones login
        public function consultarHoteles_ById($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM `ac_hoteles` WHERE id = :id;"; 

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

		public function cargarCortesiasHospedaje(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT cort.* FROM cortesias cort 
						WHERE cort.typecort = 0 
						AND cort.inicio <= CURDATE() AND cort.fin >= CURDATE()";

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
		
		public function cargarHoteles(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT id,nombre FROM lista_hoteles";

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

		public function consultarAlimentos(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//$sql = "SELECT id_usuario, comida, cena FROM reservaciones";
				/*bien
				$sql = "SELECT res.id_usuario, res.comida, res.cena,af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				WHERE res.estatus = 0";*/
				//INNER JOIN id_afiliado af ON res.id_usuario";
				$sql = "SELECT res.id idreservacion,res.id_usuario, res.comida, res.cena, res.idcortesia, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno 
						FROM reservaciones res
						INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
						JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
						WHERE res.comida >= 0 OR res.cena >= 0;";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}

		public function ConsultarCortesiasDisponibles($data){
			//var_dump($data);
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				switch($data["indice"]){
					case 'id_prospecto':
						$sql = "SELECT cort.* FROM `asigna_cortesias` ascort
								JOIN  cortesias AS cort ON cort.idcortesia = ascort.idcortesia
 								WHERE ascort.tipo = 2 AND ascort.idasignado = :idBuscar AND cort.inicio <= CURDATE() AND cort.fin >= CURDATE() GROUP BY cort.typecort;";
						break;
					case 'idGeneracion':
						$sql = "SELECT cort.* FROM `asigna_cortesias` ascort
								JOIN  cortesias AS cort ON cort.idcortesia = ascort.idcortesia
								WHERE ascort.tipo = 1 AND ascort.idasignado = :idBuscar AND cort.inicio <= CURDATE() AND cort.fin >= CURDATE() GROUP BY cort.typecort;";
						break;
					case 'idCarrera':
						$sql = "SELECT cort.* FROM `asigna_cortesias` ascort
								JOIN  cortesias AS cort ON cort.idcortesia = ascort.idcortesia
								WHERE ascort.tipo = 0 AND ascort.idasignado = :idBuscar AND cort.inicio <= CURDATE() AND cort.fin >= CURDATE() GROUP BY cort.typecort;";
						break;
				}

				$statement = $con->prepare($sql);
				$statement->bindParam(':idBuscar',$data["id"]);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function ConsultarCarrGenAlumno($idAlumno){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "SELECT afc.id_prospecto, ag.idGeneracion, ag.idCarrera FROM afiliados_conacon afc
							INNER JOIN a_prospectos ap ON ap.idAsistente =afc.id_prospecto
							INNER JOIN alumnos_generaciones algen ON algen.idalumno = ap.idAsistente
							INNER JOIN a_generaciones ag on ag.idGeneracion=algen.idgeneracion
						WHERE afc.id_afiliado = :idBuscar";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idBuscar',$idAlumno);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql, "data"=>$idAlumno];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function AsignarCortesias($data){
			//var_dump($data);
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				$sql = "INSERT INTO asigna_cortesias (idcortesia, tipo, idasignado) VALUES (:idcortesia, :tipoA, :idasignado)";

				$statement = $con->prepare($sql); 		  
				$statement->execute($data);			  
					
				if($statement->errorInfo()[0] == "00000"){
					switch($data["tipoA"]){
						case '0':
							$msj1 = "Cortesia Asignada a la Carrera";
							break;
						case '1':
							$msj1 = "Cortesia Asignada a la Generacion";
							break;
						case '2':
							$msj1 = "Cortesia Asignada a los Alumnos";
							break;
					}
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount(), "msj"=> $msj1];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function cargarAlumnosCortesias($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$whrGen = "";
				if(isset($data["idgeneracion"])){
					$whrGen = "AND ag.idgeneracion = :idgeneracion";
				}
				//var_dump($data);
				$sql = "SELECT ag.idalumno, UPPER(CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno)) nombre FROM alumnos_generaciones ag
							INNER JOIN a_prospectos ap ON ag.idalumno = ap.idAsistente
							INNER JOIN a_generaciones gen ON gen.idGeneracion = ag.idgeneracion
							INNER JOIN a_carreras ac ON ac.idCarrera = gen.idCarrera
						WHERE ac.idCarrera = :idcarrera {$whrGen} GROUP BY ag.idalumno";

				$statement = $con->prepare($sql); 		  
				$statement->execute($data);			  
			}
			$conexion = null;
			$con = null;
			return $statement;
		}

		public function cargarCotesias($data){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];
			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				//var_dump($data);
				switch($data["case"]){
					case "queryAll":
						$sql = "SELECT cort.* FROM cortesias cort
						WHERE cort.inicio <= CURDATE() AND cort.fin >= CURDATE();";
						break;
					case "queryAllPast":
						$sql = "SELECT cort.* FROM cortesias cort
						WHERE cort.inicio > CURDATE() OR cort.fin < CURDATE();";
						break;
					case "queryUnic":
						$sql = "SELECT * FROM cortesias WHERE idcortesia = :idcortesia;";
						break;
					case "update":
						$sql = "UPDATE cortesias SET nombre = :nombre_i ,informacion = :informacion_i, typecort=:typecort_i, inicio = :inicio_i, fin = :fin_i WHERE idcortesia = :idcortesia;";
						break;
					case "insert":
						$sql = "INSERT INTO cortesias (nombre,informacion, typecort, inicio, fin) VALUES (:nombre_i,:informacion_i,:typecort_i,:inicio_i,:fin_i);";
						break;
					case "asignar":
						$sql = "UPDATE cortesias SET tipo = :, idasignado = : WHERE idcortesia = :idcortesia;";
						break;

				}
				$band = $data["case"];
				unset($data["case"]);

				$statement = $con->prepare($sql); 		  
				$statement->execute($data);			  
					
				if($statement->errorInfo()[0] == "00000"){

					switch ($band) {
						case "queryAllPast":
						case "queryAll":
							return $statement;
							break;
						case "queryUnic":
							$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
							break;
						case "update":
						case "insert":
							$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
							break;
					}
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function buscarAlimentos($buscar){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				//$sql = "SELECT * FROM reservaciones WHERE id_usuario = :idBuscar";
				$sql = "SELECT res.*, af.nombre as nombre, af.aPaterno as apaterno, res.idcortesia FROM reservaciones res 
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
				WHERE res.id_usuario = :idBuscar AND res.idcortesia = :idcortesia";

				$statement = $con->prepare($sql);
				$statement->execute($buscar);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql, "data"=>$buscar];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function modificarAlimentos($modify){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				$sql = "UPDATE reservaciones SET comida = :devComida ,cena = :devCena WHERE id_usuario = :idModificarAlimentos";

				$statement = $con->prepare($sql);
				$statement->execute($modify);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$modify];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function eliminarAlimentos($del){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE reservaciones SET estatus = 1 WHERE id_usuario = :idEliminar";

				$statement = $con->prepare($sql);
				//$statement->bindParam(':idEliminar', $del);
				$statement->execute($del);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$del];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function consultarHotel(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//$sql = "SELECT id_usuario, id_companiero, id_hotel, habitacion FROM reservaciones";
				/*Bien
				$sql = "SELECT res.id_usuario, res.id_companiero, res.id_hotel, res.habitacion, af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno,
				afComp.nombre as nombreComp, afComp.apaterno as apaternoComp, afComp.amaterno as amaternoComp,
				listH.nombre as nombreH  
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				INNER JOIN afiliados_conacon afComp ON res.id_companiero = afComp.id_afiliado
				INNER JOIN lista_hoteles listH ON res.id_hotel = listH.id
				WHERE res.match_comp = 1 AND res.estatus = 0";*/
				$sql = "SELECT DISTINCT res.habitacion, res.id_usuario, res.id_companiero, res.id_hotel, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno,
				apComp.nombre as nombreComp, apComp.aPaterno as apaternoComp, apComp.aMaterno as amaternoComp,
				listH.nombre as nombreH, res.idcortesia
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
				JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
				INNER JOIN afiliados_conacon afComp ON res.id_companiero = afComp.id_prospecto
				JOIN a_prospectos apComp ON afComp.id_prospecto = apComp.idAsistente
				INNER JOIN lista_hoteles listH ON res.id_hotel = listH.id
				WHERE res.match_comp = 1 AND res.estatus = 0
				GROUP BY res.habitacion";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}

		public function consultarEsperaHotel(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				/*Bien
				$sql = "SELECT res.id_usuario, res.id_companiero, res.id_hotel, res.habitacion, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno,
				apComp.nombre as nombreComp, apComp.aPaterno as apaternoComp, apComp.aMaterno as amaternoComp
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
				INNER JOIN afiliados_conacon afComp ON res.id_companiero = afComp.id_afiliado
				JOIN a_prospectos apComp ON afComp.id_prospecto = apComp.idAsistente
				WHERE res.match_comp = 1 AND res.id_hotel = 0 AND res.estatus = 0 LIMIT 1";*/

				$sql = "SELECT DISTINCT res.clave_companiero, res.id_usuario, res.id_companiero, res.id_hotel, res.habitacion, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno,
						apComp.nombre as nombreComp, apComp.aPaterno as apaternoComp, apComp.aMaterno as amaternoComp, res.idcortesia
						FROM reservaciones res
						INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
						JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
						INNER JOIN afiliados_conacon afComp ON res.id_companiero = afComp.id_prospecto
						JOIN a_prospectos apComp ON afComp.id_prospecto = apComp.idAsistente
						WHERE res.match_comp = 1 AND (res.id_hotel IS NULL OR res.id_hotel = 0 AND res.estatus = 0) GROUP BY res.clave_companiero";


				/*$sql = "SELECT res.id_usuario, res.comida, res.cena,af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				WHERE res.estatus = 0";*/
				//INNER JOIN id_afiliado af ON res.id_usuario";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}

		public function obtenerUsuarios($data){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];
			unset($data["idComp"]);
			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				/*Bien
				$sql = "SELECT res.*, af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno,
				afComp.nombre as nombreComp, afComp.apaterno as apaternoComp, afComp.amaterno as amaternoComp
				FROM reservaciones res 
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				INNER JOIN afiliados_conacon afComp ON res.id_companiero = afComp.id_afiliado
				WHERE id_usuario = :idUsu";*/
				$sql ="SELECT res.*, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno,
				apComp.nombre as nombreComp, apComp.aPaterno as apaternoComp, apComp.aMaterno as amaternoComp, res.idcortesia
				FROM reservaciones res 
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
				JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
				LEFT JOIN afiliados_conacon afComp ON res.id_companiero = afComp.id_prospecto
				LEFT JOIN a_prospectos apComp ON afComp.id_prospecto = apComp.idAsistente
				WHERE res.id_usuario = :idUsu AND res.idcortesia = :idcortesia;";
				
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function asignarHotel($asignar){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				$sql = "UPDATE reservaciones SET id_hotel = :hotelesAsig ,habitacion = :habitacion 
						WHERE (id_usuario = :idAsignarUsu OR id_usuario = :idAsignarComp) AND idcortesia = :idcortesia;";

				$statement = $con->prepare($sql);
				$statement->execute($asignar);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$asignar];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function correoPorClave($asignar){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				$sql = "SELECT  UPPER(CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno)) AS nombre, ap.correo  FROM reservaciones res
						JOIN a_prospectos ap ON ap.idAsistente = res.id_usuario
						WHERE res.clave_companiero = :idAsignarUsu";

				$statement = $con->prepare($sql);
				$statement->execute($asignar);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->FetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$asignar];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}		

		public function modAsignarHotel($mod){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				$sql = "UPDATE reservaciones SET id_hotel = :devHoteles ,habitacion = :devHabitacion 
						WHERE (id_usuario = :idModAsignarUsu OR id_usuario = :idModAsignarComp) AND idcortesia = :idcortesia";

				$statement = $con->prepare($sql);
				$statement->execute($mod);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$mod];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function consultarEsperaTransporte(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				/*Bien
				$sql = "SELECT res.id_usuario, res.id_companiero, res.transporte, af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				WHERE res.transporte = 0 AND res.estatus = 0";
				*/

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo..... traer el id de la reservacion.
				$sql = "SELECT res.id_usuario, res.id_companiero, res.transporte, ap.nombre as nombre, res.idcortesia, ap.aPaterno as apaterno, ap.aMaterno as amaterno
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
				JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
				WHERE res.transporte = 0 AND res.estatus = 0";
				/*
				$sql = "SELECT res.id_usuario, res.id_companiero, res.transporte, af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado AND res.id_companiero != af.id_afiliado
				WHERE res.transporte = 0 AND res.estatus = 0";*/

				/*$sql = "SELECT res.id_usuario, res.comida, res.cena,af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				WHERE res.estatus = 0";*/
				//INNER JOIN id_afiliado af ON res.id_usuario";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}

		public function validarEstatusRes($data){
			//var_dump($asignar);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				$sql = "SELECT :selects FROM reservaciones WHERE id = :idreservacion;";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$asignar];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function CorreoPorRes($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				$sql = "SELECT UPPER(CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno)) AS nombre, ap.correo FROM reservaciones res 
				JOIN a_prospectos ap ON ap.idAsistente = res.id_usuario
				WHERE res.id = :idreservacion;";

				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$asignar];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function asignarTransporte($asignar){
			//var_dump($asignar);
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				$sql = "UPDATE reservaciones SET transporte = :transporteAsign ,numero_asiento = :asiento WHERE id_usuario = :idAsignarUsuT AND idcortesia = :idcortesia;";

				$statement = $con->prepare($sql);
				$statement->execute($asignar);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$asignar];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		public function correoPorClaveTransporte($asignar){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				$sql = "SELECT  UPPER(CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno)) AS nombre, ap.correo  FROM reservaciones res
						JOIN a_prospectos ap ON ap.idAsistente = res.id_usuario
						WHERE res.id_usuario = :idAsignarUsu";

				$statement = $con->prepare($sql);
				$statement->execute($asignar);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->FetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$asignar];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}			

		public function consultarTransportes(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				/*Bien
				$sql = "SELECT res.id_usuario, res.transporte, res.numero_asiento, af.nombre as nombre, af.apaterno as apaterno, af.amaterno as amaterno
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_afiliado
				WHERE res.transporte != 0 AND res.estatus = 0";
				*/
				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo..... traer el id de la cortesia
				$sql = "SELECT res.id_usuario, res.transporte, res.numero_asiento, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno, res.idcortesia, lt.nombre NombreTransp
						FROM reservaciones res
						INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
						JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
						LEFT JOIN listado_transporte lt ON lt.idtransporte = res.transporte
						WHERE res.transporte != 0 AND res.estatus = 0";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}
		
		public function obtenerTransporte($usu,$cort){
			$conexion = new Conexion();
			$con = $conexion->Conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo..... traer el id de la cortesia
				$sql ="SELECT res.*, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno,
				apComp.nombre as nombreComp, apComp.aPaterno as apaternoComp, apComp.aMaterno as amaternoComp
				FROM reservaciones res 
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
				JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
				LEFT JOIN afiliados_conacon afComp ON res.id_companiero = afComp.id_prospecto
				LEFT JOIN a_prospectos apComp ON afComp.id_prospecto = apComp.idAsistente
				WHERE res.id_usuario = :idUsu AND res.idcortesia = :idcort;";

				$statement = $con->prepare($sql);
				$statement->bindParam(':idUsu',$usu);
				$statement->bindParam(':idcort',$cort);
				$statement->execute();

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->erroInfo(), "sql"=>$sql];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function modificarTransporte($modify){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....id cortesia 
				$sql = "UPDATE reservaciones SET transporte = :transporteMod ,numero_asiento = :asientoMod WHERE id_usuario = :idModTranspor AND idcortesia = :idcortesia";

				$statement = $con->prepare($sql);
				$statement->execute($modify);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$modify];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function AddHotel($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if ($con['info'] == 'ok') {
				$con = $con['conexion'];

				switch($data["tipoCase"]){
					case 'update':
						$sql = "UPDATE lista_hoteles  SET nombre = :newHotel, direccion = :direccion WHERE id = :idHotel";
						break;
					case 'add':
						$sql = "INSERT INTO  lista_hoteles  (`nombre`, `direccion`, `status`) values ( :newHotel, :direccion, 0);";
						unset($data["idHotel"]);
						break;
				}
				unset($data["tipoCase"]);
				//var_dump($data);
				$statement = $con->prepare($sql);
				$statement->execute($data);

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

		public function consultarHoteles($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				$whr = "";
				if($id>0){
					$whr = "WHERE id = {$id}";
				}

				$sql = "SELECT * FROM lista_hoteles {$whr}";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				if($id>0){
					return $statement->fetchAll(PDO::FETCH_ASSOC);
				}else{
					return $statement;
				}
			}
		}

		public function AddTransporte($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				switch($data["case"]){
					case 'add':
						$sql = "INSERT INTO listado_transporte (nombre) VALUES(:nombreTransporte)"; 
						break;
					case 'update':
						$sql = "UPDATE listado_transporte SET nombre = :nombreTransporte WHERE idtransporte = :idTransporte;";
						break;
				}
				unset($data["case"]);

				$statement = $con->prepare($sql);
				//$statement->bindParam(':idEliminar', $del);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		//Posible unificacion con la funcion de abajo
		public function canjearAlimentos($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo..... ide de la cortesia
				$sql = "UPDATE reservaciones SET comida = 2 WHERE id = :id;"; 
					
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function canjearCena($data){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];
				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				$sql = "UPDATE reservaciones SET cena = 2 WHERE id = :id;"; 
					
				$statement = $con->prepare($sql);
				$statement->execute($data);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function consultarFinal(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo..... validar las cortesia para esa mensualidad
				$sql = "SELECT res.*, ap.nombre as nombre, ap.aPaterno as apaterno, ap.aMaterno as amaterno,
				listH.nombre as nombreH  
				FROM reservaciones res
				INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
				JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
				INNER JOIN lista_hoteles listH ON res.id_hotel = listH.id
				WHERE res.match_comp = 1 AND res.estatus = 0 AND res.id_hotel != 0 AND res.transporte != 0";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}
		
		public function consultarGeneral(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				$sql = "SELECT res.id_usuario, UPPER(CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno)) as nombre, hotel.id_hotel, hotel.nombreHotel, hotel.habitacion, transp.transporte, transp.nombreTransporte,transp.numero_asiento, alim.comida, alim.cena
						FROM reservaciones res
						INNER JOIN cortesias cort ON cort.idcortesia = res.idcortesia AND cort.inicio <= CURDATE() AND cort.fin >= CURDATE()
						INNER JOIN afiliados_conacon af ON res.id_usuario = af.id_prospecto
						INNER JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
						LEFT JOIN (SELECT resa.*,lh.nombre as nombreHotel FROM reservaciones resa
								INNER JOIN cortesias corta ON corta.idcortesia = resa.idcortesia AND corta.inicio <= CURDATE() AND corta.fin >= CURDATE()
                                LEFT JOIN lista_hoteles lh ON lh.id = resa.id_hotel
								WHERE resa.id_hotel > 0) hotel ON hotel.id_usuario = res.id_usuario
						LEFT JOIN (SELECT resb.*, lt.nombre as nombreTransporte FROM reservaciones resb
								INNER JOIN cortesias cortb ON cortb.idcortesia = resb.idcortesia AND cortb.inicio <= CURDATE() AND cortb.fin >= CURDATE()
                                LEFT JOIN listado_transporte lt ON lt.idtransporte = resb.transporte 
								WHERE resb.transporte >= 0) transp ON transp.id_usuario = res.id_usuario
						LEFT JOIN (SELECT resc.* FROM reservaciones resc
								INNER JOIN cortesias cortc ON cortc.idcortesia = resc.idcortesia AND cortc.inicio <= CURDATE() AND cortc.fin >= CURDATE()
								WHERE resc.cena != 0 OR resc.comida != 0) alim ON alim.id_usuario = res.id_usuario
						WHERE res.estatus = 0 GROUP BY res.id_usuario;";
				
				/*WHERE res.match_comp = 0 AND res.estatus = 0 AND res.id_hotel = 0 AND res.transporte = 0";*/
				$statement = $con->prepare($sql);
				$statement->execute(); 
					
				$conexion = null;
				$con = null;
				return $statement;
			}
		}

		public function eliminarGeneral($del){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....validar el id de la cortesia
				$sql = "UPDATE reservaciones SET estatus = 1 WHERE id_usuario = :idEliminar";

				$statement = $con->prepare($sql);
				//$statement->bindParam(':idEliminar', $del);
				$statement->execute($del);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$del];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function eliminarUsuarios($del){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];


				$sql = "DELETE FROM reservaciones 
						WHERE (id_usuario = :idEliminar AND id_companiero = :idComp AND idcortesia = :idcortesia) 
						OR (id_usuario = :idComp AND id_companiero = :idEliminar AND idcortesia = :idcortesia)";

				$statement = $con->prepare($sql);
				//$statement->bindParam(':idEliminar', $del);
				$statement->execute($del);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$del];
				}
				$conexion = null;
				$con = null;

				return $response;
			}
		}

		public function eliminarCompanieros($del){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "DELETE FROM reservaciones
						WHERE  (id_usuario = :idEliminar AND id_companiero = :idComp AND idcortesia = :idcortesia) 
						OR (id_usuario = :idComp AND id_companiero = :idEliminar AND idcortesia = :idcortesia)";

				$statement = $con->prepare($sql);
				//$statement->bindParam(':idEliminar', $del);
				$statement->execute($del);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$del];
				}
				
			}
			$conexion = null;
			$con = null;

			return $response;
		}

		public function CargarTipoTransporte($band){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT * FROM listado_transporte";

				$statement = $con->prepare($sql); 		  
				$statement->execute();			  
				if($band == 1){
					if($statement->errorInfo()[0] == "00000"){
						$response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}
				}	
			}
			$conexion = null;
			$con = null;
			return  $band == 1 ? $response : $statement;
		}

		public function buscarUsuarios(){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$sql = "SELECT res.id_usuario, ap.Genero FROM reservaciones res
				INNER JOIN  afiliados_conacon af ON res.id_usuario = af.id_prospecto
				JOIN a_prospectos ap ON af.id_prospecto = ap.idAsistente
				WHERE res.estatus = 0 AND match_comp = 0";

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

		public function registrarCompanierosA($id_usu, $id_comp){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con['info'] == 'ok'){
				$con = $con['conexion'];

				$sql = "UPDATE reservaciones res, reservaciones resTwo
				SET res.id_companiero = :idComp, res.match_comp = 1, res.clave_companiero = :idUsuario,
				resTwo.id_companiero = :idUsuario, resTwo.match_comp = 1, resTwo.clave_companiero = :idUsuario
				WHERE res.id_usuario = :idUsuario AND resTwo.id_usuario = :idComp";
				
				$statement = $con->prepare($sql);
				/*$statement->bindParam(':idUsuarioA', $id_usu, PDO::PARAM_INT);
				$statement->bindParam(':idUsuarioB', $id_usu, PDO::PARAM_INT);
				$statement->bindParam(':idUsuarioC', $id_usu, PDO::PARAM_INT);
				$statement->bindParam(':idUsuarioD', $id_usu, PDO::PARAM_INT);
				$statement->bindParam(':idCompA', $id_comp, PDO::PARAM_INT);
				$statement->bindParam(':idCompB', $id_comp, PDO::PARAM_INT);*/
				$statement->bindParam(':idUsuario', $id_usu);
				$statement->bindParam(':idComp', $id_comp);
				$statement->execute();


				/*bien
				UPDATE reservaciones res,
				reservaciones resTwo
				SET res.id_companiero = 15, res.match_comp = 1, res.clave_companiero = 5,
				resTwo.id_companiero = 5, resTwo.match_comp = 1, resTwo.clave_companiero = 5
				WHERE res.id_usuario = 5 AND resTwo.id_usuario = 15*/

				//$statement->bindParam(':idEliminar', $del);
				//$statement->execute($aletorio);

				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount(), $sql];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}
			$conexion = null;
			$con = null;

			return $response;
		}
		// funciones para vista de usuario
		function consultar_solicitud_match($alumno, $cortesia){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....validar id de la cortesia
				$sql = "SELECT res.*, afil.nombre as solicitante, afil.matricula as solicitante_matricula, afil_c.nombre as companiero, afil_c.matricula as companiero_matricula, lh.direccion, lh.nombre as NombreHotel, lt.nombre as NombreTransp
						FROM `reservaciones` res
						JOIN afiliados_conacon afil ON res.id_usuario = afil.id_prospecto
						LEFT JOIN afiliados_conacon afil_c ON res.id_companiero = afil_c.id_prospecto
						LEFT JOIN lista_hoteles lh ON lh.id = res.id_hotel
						LEFT JOIN listado_transporte lt ON lt.idtransporte = res.transporte
						WHERE res.id_companiero = :alumno AND res.idcortesia = :cortesia;";

				$statement = $con->prepare($sql); 		  
				$statement->bindParam(':alumno', $alumno);
				$statement->bindParam(':cortesia', $cortesia);
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

		function consultar_solicitud_match_realizadas($alumno, $corte){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				$sql = "SELECT res.*, afil.nombre as solicitante, afil.matricula as solicitante_matricula, afil_c.nombre as companiero, afil_c.matricula as companiero_matricula, lh.nombre as NombreHotel, lh.direccion,lt.nombre as NombreTransp
						FROM `reservaciones` res
						JOIN afiliados_conacon afil ON res.id_usuario = afil.id_prospecto
						LEFT JOIN afiliados_conacon afil_c ON res.id_companiero = afil_c.id_prospecto
						LEFT JOIN lista_hoteles lh ON lh.id = res.id_hotel
						LEFT JOIN listado_transporte lt ON lt.idtransporte = res.transporte
						WHERE res.id_usuario = :alumno AND res.idcortesia = :cortesia";

				$statement = $con->prepare($sql); 		  
				$statement->bindParam(':alumno', $alumno);
				$statement->bindParam(':cortesia', $corte);
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

		function consultar_solicitud_match_id($id){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				$sql = "SELECT res.*, afil.nombre as solicitante, afil.matricula as solicitante_matricula, afil_c.nombre as companiero, afil_c.matricula as companiero_matricula
						FROM `reservaciones` res
						JOIN afiliados_conacon afil ON res.id_usuario = afil.id_prospecto
						left JOIN afiliados_conacon afil_c ON res.id_companiero = afil_c.id_prospecto
						WHERE res.id = :id;";

				$statement = $con->prepare($sql); 		  
				$statement->bindParam(':id', $id);
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

		function registrar_solicitud_match($solicita, $compa, $valor, $solicita2, $campo ,$cortesia){
			//var_dump($solicita2);
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){
				$inserta = "";
				$camp = "";
				if($valor == "1"){
					$inserta = ",clave_companiero";
					$camp = ",:clave_comp";
				}
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo..... volver funcion principal, cambiar el tipo dependiendo de la llave foranea
				$sql = "INSERT INTO `reservaciones`(`id_usuario`, `id_companiero`, `match_comp`, `id_hotel`, $campo  {$inserta}) 
				VALUES (:id_usuario, :id_companiero, :match, 0, :cortesia {$camp});";

				$statement = $con->prepare($sql); 		  
				$statement->bindParam(':id_usuario', $solicita);
				$statement->bindParam(':id_companiero', $compa);
				$statement->bindParam(':match', $valor);
				$statement->bindParam(':cortesia', $cortesia);
				
				if($valor == "1"){
					$statement->bindParam(':clave_comp', $solicita2);
				}
				$statement->execute();
					
				if($statement->errorInfo()[0] == "00000"){
					$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
				}else{
					$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function repetir_solicitud_match($solicita, $compa, $idcort){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....cambiar por un select
				$sql = "UPDATE `reservaciones` SET `id_companiero` = :id_companiero WHERE `id_usuario` = :id_usuario AND idcortesia = :id_cortesia;";

				$statement = $con->prepare($sql); 		  
				$statement->bindParam(':id_usuario', $solicita);
				$statement->bindParam(':id_companiero', $compa);
				$statement->bindParam(':id_cortesia', $idcort);
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

		function aprobar_solicitud($solicitante, $companiero, $idcortesia){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$compa_solicitudes_enviadas = $this->consultar_solicitud_match_realizadas($companiero,$idcortesia);
				$upd_soli = 0;
				if(sizeof($compa_solicitudes_enviadas['data']) > 0){
					$upd_soli = $con->query("UPDATE `reservaciones` SET id_companiero = {$solicitante}, match_comp = 1, clave_companiero = {$solicitante} WHERE id_usuario = {$companiero}")->rowCount();
					$con->query("UPDATE `reservaciones` SET id_companiero = null, match_comp = 0, clave_companiero = '' WHERE id_usuario != {$solicitante} AND id_companiero = {$companiero}");
				}else{
					$upd_soli = $con->query("INSERT INTO `reservaciones`(`id_usuario`, `id_companiero`, `match_comp`, `id_hotel`, `clave_companiero`,`idcortesia`) VALUES ({$companiero}, {$solicitante}, 1, 0, {$solicitante},{$idcortesia});")->rowCount();
					$upd_soli = $con->lastInsertId();
				}
				if($upd_soli > 0){
					$sql = "UPDATE `reservaciones` SET match_comp = 1, clave_companiero = {$solicitante} WHERE id_usuario = {$solicitante};";
	
					$statement = $con->prepare($sql); 		  
					$statement->execute();
						
					if($statement->errorInfo()[0] == "00000"){
						$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}
				}else{
					$response = ["estatus"=>"error", "info"=>"error_editar_registro_companiero"];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		function rechazar_solicitud($solicitante, $companiero){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....verificar el id de la cortesia
				$sql = "UPDATE `reservaciones` SET id_companiero = null, match_comp = 0, clave_companiero = '' WHERE id_usuario = {$solicitante};";

				$statement = $con->prepare($sql); 		  
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
		function rechazar_reservacion($solicitante){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....id de la cortesia
				$solicitud_upd = $con->query("UPDATE `reservaciones` SET 
					id_companiero = null, match_comp = 0, clave_companiero = 0,
					id_hotel = '', habitacion = '',  comida = 0,
					cena = 0, transporte = 0, numero_asiento = '', estatus = 2 WHERE id_usuario = {$solicitante};")->rowCount();
				if($solicitud_upd == 0){
					$sql = "INSERT INTO `reservaciones`(`id_usuario`, `id_companiero`, `match_comp`, `id_hotel`, `estatus`) 
					VALUES (:id_usuario, null, 0, 0, 2);";
					
					$statement = $con->prepare($sql); 		  
					$statement->bindParam(':id_usuario', $solicitante);
					$statement->execute();
						
					if($statement->errorInfo()[0] == "00000"){
						$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
					}else{
						$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
					}
				}else{
					$response = ["estatus"=>"ok", "data"=>$solicitud_upd];
				}
			}
			$conexion = null;
			$con = null;
			return $response;
		}

		/// funciones para establecer la solicitud de transporte

		function solicitud_transporte($solicitante, $bool, $cortesia){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				$xr_answer = "";
				$xt_query = "";
				$hab = 0;
				if(!$bool){# si se esta rechazando la solicitud de transporte
					$hab = null; 
					//$xt_query = ", numero_asiento = 'rechazo_transporte'";
					$xt_query = ",numero_asiento'";
					$xr_answer = ',rechazo_transporte';
				}

				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				//$sql = "UPDATE `reservaciones` SET transporte = :transporte_stat {$xt_query}, idcortesia = :cortesia WHERE id_usuario = :solicitante;";
				$sql = "INSERT INTO reservaciones (id_usuario, transporte {$xt_query}, idcortesia) VALUES (:solicitante,:transporte_stat {$xr_answer}, :cortesia)";
				
				$statement = $con->prepare($sql); 		  
				$statement->bindParam(':solicitante', $solicitante);
				$statement->bindParam(':transporte_stat', $hab);
				$statement->bindParam(':cortesia', $cortesia);
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

		function solicitud_alimentos($solicitante, $comida, $cena, $cortesia){
			$conexion = new Conexion(); 
			$con = $conexion->conectar(); 
			$response = [];

			if($con["info"] == "ok"){ 
				$con = $con["conexion"];
				
				//Modificar consulta para que solo reciba el id de la cortesia y pueda consultarlo.....
				//$sql = "UPDATE `reservaciones` SET comida = :comida, cena = :cena, idcortesia = :cortesia WHERE id_usuario = :solicitante;";
				$sql = "INSERT INTO reservaciones (id_usuario, comida,cena,idcortesia) VALUES(:solicitante, :comida, :cena, :cortesia);";

				
				$statement = $con->prepare($sql); 		  
				$statement->bindParam(':solicitante', $solicitante);
				$statement->bindParam(':comida', $comida);
				$statement->bindParam(':cena', $cena);
				$statement->bindParam(':cortesia', $cortesia);
				
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

		function enviar_notificacion($destinatario, $mensaje, $asunto){
			require_once '../../functions/correos_prospectos.php';
			$claves = [];
			$valores = [];
			array_push($claves,'%%prospecto');
			array_push($valores,$destinatario['nombre']);
			array_push($claves,'%%MENSAJECONFIRMACION');
			array_push($valores,$mensaje);
			//var_dump($asunto);
			//var_dump($destinatario);
			enviar_correo_registro($asunto, [[$destinatario['correo'],$destinatario['nombre']]], 'carreras/nueva_plantilla_udc_visitas_medicina.html', $claves, $valores);
		}
    }
?>
