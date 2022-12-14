<?php 

	require_once 'conexion.php';



	class Alumno {

		public function consultarTodoAlumnos(){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

				$sql = "SELECT * FROM alumnos;";

				$statement = $con->prepare($sql);
				$statement->execute();

				if($statement->errorInfo()[0] == 0000){
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

		public function validarLogin($datos){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				include "keys.php";

				$sql = "SELECT ac.* FROM afiliados_conacon ac WHERE email = :usr_name AND contrasenia = AES_ENCRYPT(:usr_pass, '{$DECRYPT_PASS}');";
				$statement = $con->prepare($sql);
				$statement->execute($datos);

				if($statement->errorInfo()[0] == 00000){
					$alumno = ($statement->rowCount() > 0)? $statement->fetch(PDO::FETCH_ASSOC) : false;
					if($alumno){
						unset($alumno['contrasenia']);
						$alumno['instituciones'] = $con->query("SELECT inal.*, inst.nombre as institucion_n, inst.panel_url FROM instituciones_afiliados inal
						JOIN a_instituciones inst ON inst.id_institucion = inal.id_institucion WHERE inal.id_prospecto = ".$alumno['id_prospecto'])->fetchAll(PDO::FETCH_ASSOC);
					}
					$response = ["estatus"=>"ok", "data"=>$alumno];
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

		public function validarsiexiste($email){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			if($con["info"] == "ok"){
				$con = $con["conexion"];
				include "keys.php";
				$sql = "SELECT ac.id_afiliado, ac.nombre, ac.apaterno, ac.amaterno,AES_DECRYPT(ac.contrasenia, '{$DECRYPT_PASS}') as contrasena 
				FROM afiliados_conacon ac 
				JOIN a_prospectos as ap on ac.id_prospecto=ap.idAsistente
				WHERE email = :email
				LIMIT 1;";
				$statement = $con->prepare($sql);

				$statement->bindParam(":email", $email, PDO::PARAM_STR);
				
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

		public function cambiarpasw($contrasena,$idusuario){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			include "keys.php";

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE afiliados_conacon
						SET contrasenia=AES_ENCRYPT(:contrasenia, '{$DECRYPT_PASS}')
                                WHERE id_afiliado=:id_afiliado;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el m??todo PDOStatement::execute(). La sentencia SQL puede contener cero o m??s marcadores de par??metros con nombre (:name) o signos de interrogaci??n (?) por los cuales los valores reales ser??n sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los par??metros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un par??metro de sustituci??n con nombre o de signo de interrogaci??n correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":contrasenia", $contrasena, PDO::PARAM_STR);
				$stmt->bindParam(":id_afiliado", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$stmt->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;	

		}

		public function cambiarpaswusermoni($contrasena,$idusuario){

			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
			include "keys.php";

			if($con["info"] == "ok"){
				$con = $con["conexion"];
				$sql = "UPDATE a_accesos
						SET contrasenia=AES_ENCRYPT(:contrasenia, '{$DECRYPT_PASS}')
                                WHERE idAcceso=:idAcceso;";
				#prepare() Prepara una sentencia SQL para ser ejecutada por el m??todo PDOStatement::execute(). La sentencia SQL puede contener cero o m??s marcadores de par??metros con nombre (:name) o signos de interrogaci??n (?) por los cuales los valores reales ser??n sustituidos cuando la sentencia sea ejecutada. Ayuda a prevenir inyecciones SQL eliminando la necesidad de entrecomillar manualmente los par??metros.
                $stmt = $con->prepare($sql);

                #bindParam() Vincula una variable de PHP a un par??metro de sustituci??n con nombre o de signo de interrogaci??n correspondiente de la sentencia SQL que fue usada para preparar la sentencia.
                $stmt->bindParam(":contrasenia", $contrasena, PDO::PARAM_STR);
				$stmt->bindParam(":idAcceso", $idusuario, PDO::PARAM_INT);

                $stmt->execute();

				if($stmt->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$stmt->rowCount()];
				}else{
					$response = ["estatus"=>"error", "info"=>$stmt->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","info"=>"error de conexion"];
			}
			$conexion = null;
			$con = null;
			return $response;	

		}

	}

?>
