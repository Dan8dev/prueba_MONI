<?php 
date_default_timezone_set("America/Mexico_City");

class Materias{

    public function obtenerCarreras($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $whr = "   WHERE estatus = 1 AND tipo = 1 AND idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11";
            if($id == 4){
                $whr = "WHERE estatus = 1 AND tipo != 1 and (idCarrera = 14 or idCarrera = 19)";
            }

            $sql = "SELECT idCarrera, nombre
                FROM a_carreras
                {$whr}
                ORDER BY nombre ASC";

                $statement = $con->prepare($sql);
                $statement->execute();

                if($statement->errorInfo()[0] == '00000'){
                    $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
                }else{
                    $response =["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
                }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function obtenerCarrerasOficial($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $whr = " WHERE estatus = 1 AND tipo != 1 AND idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11";
            if($id == 4){
                $whr = "WHERE estatus = 1 AND tipo != 1 and (idCarrera = 14 or idCarrera = 19)";
            }

            $sql = "SELECT idCarrera, nombre
                FROM a_carreras
               {$whr}
                ORDER BY nombre ASC";

                $statement = $con->prepare($sql);
                $statement->execute();

                if($statement->errorInfo()[0] == '00000'){
                    $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
                }else{
                    $response =["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
                }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

	public function crearMateriaOficial($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
  
		if($con["info"] == "ok"){
		$con = $con["conexion"];

		$sql = "INSERT INTO materias 
		(oficial, nombre, clave_asignatura, tipo, numero_creditos, fecha_creado, creado_por, id_carrera, estatus)
        VALUES(:selectOficial, :nombreMateria, :claveMateria, :selectTipoMateria, :numeroCreditos, :fcreado, :creado_por, :selectCarreraAsig, 1)";

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

    public function crearMateriaOficialPDF($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql = "INSERT INTO materias
            (oficial, nombre, clave_asignatura, tipo, numero_creditos, fecha_creado, creado_por, id_carrera, estatus, contenido_pdf)
            VALUES(:selectOficial, :nombreMateria, :claveMateria, :selectTipoMateria, :numeroCreditos, :fcreado, :creado_por, :selectCarreraAsig, 1, :nName)";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$data];
            }
        }
    $conexion = null;
    $con = null;
    return $response; 
    }

    public function crearMateriaNoOficial($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
  
		if($con["info"] == "ok"){
		$con = $con["conexion"];

		$sql = "INSERT INTO materias 
		(oficial, nombre, clave_asignatura, numero_creditos, fecha_creado, creado_por, id_carrera, estatus)
        VALUES(:selectOficial, :nombreMateria, :claveMateria, :numeroCreditosNoOficial, :fcreado, :creado_por, :selectCarreraAsig, 1)";

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

    public function crearMateriaNoOficialPDF($data){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
  
		if($con["info"] == "ok"){
		$con = $con["conexion"];

		$sql = "INSERT INTO materias 
		(oficial, nombre, clave_asignatura, numero_creditos, fecha_creado, creado_por, id_carrera, estatus, contenido_pdf)
        VALUES(:selectOficial, :nombreMateria, :claveMateria, :numeroCreditosNoOficial, :fcreado, :creado_por, :selectCarreraAsig, 1, :nName)";

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
    public function obtenerMaterias(){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT mt.*, carrer.nombre as nombreCarr, carrer.idCarrera
                FROM materias mt
                INNER JOIN a_carreras carrer ON carrer.idCarrera = mt.id_carrera
                WHERE mt.estatus = 1;";

            $statement = $con->prepare($sql);
            $statement->execute();
        }
        $conexion = null;
        $con = null;   
        return $statement;
    }

    public function buscarMateria($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT * FROM materias
                WHERE id_materia = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
            }
        }

        $conexion = null;
        $con = null;
        return $response;
    }

    public function modificarMateriaOficialPDF($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE materias SET nombre = :modNombreMateria, clave_asignatura = :modClaveMateria, tipo = :modSelectTipoMateria,
                 numero_creditos = :modNumeroCreditos, contenido_pdf = :nName, fecha_modificado = :fmodificado , modificado_por = :modificado_por, id_carrera = :modSelectCarreraAsig
                WHERE id_materia = :id_materia";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function modificarMateriaOficial($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE materias SET nombre = :modNombreMateria, clave_asignatura = :modClaveMateria, tipo = :modSelectTipoMateria,
                 numero_creditos = :modNumeroCreditos, fecha_modificado = :fmodificado , modificado_por = :modificado_por, id_carrera = :modSelectCarreraAsig
                 WHERE id_materia = :id_materia";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function modificarMateriaNoOficial($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE materias SET nombre = :modNombreMateria, clave_asignatura = :modClaveMateria, numero_creditos = :modNumeroCreditosNoOficial, fecha_modificado = :fmodificado , modificado_por = :modificado_por, id_carrera = :modSelectCarreraAsig
                 WHERE id_materia = :id_materia";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function modificarMateriaNoOficialPDF($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE materias SET nombre = :modNombreMateria, clave_asignatura = :modClaveMateria, numero_creditos = :modNumeroCreditosNoOficial, contenido_pdf = :nName, fecha_modificado = :fmodificado , modificado_por = :modificado_por, id_carrera = :modSelectCarreraAsig
                WHERE id_materia = :id_materia";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function modificarMateriaOficialRVOENull($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE materias SET nombre = :modNombreMateria, clave_asignatura = :modClaveMateria, tipo = :modSelectTipoMateria,
                 numero_creditos = :modNumeroCreditos, clave_sep = '', fecha_modificado = :fmodificado , modificado_por = :modificado_por, id_carrera = :modSelectCarreraAsig
                WHERE id_materia = :id_materia";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function eliminarMateria($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE materias SET estatus = 2
                 WHERE id_materia = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function buscarClaveMateria($clave){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT * FROM materias
                WHERE clave_asignatura = :claveMateria";

            $statement = $con->prepare($sql);
            $statement->bindParam(':claveMateria',$clave);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$clave];
            }
        }
    $conexion = null;
    $con = null;
    return $response;
    }

    public function buscarClaveMateriaMod($clave, $idMateria){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT * FROM materias
                WHERE clave_asignatura = :claveMatMod AND id_materia != :idMat";

            $statement = $con->prepare($sql);
            $statement->bindParam(':claveMatMod',$clave);
            $statement->bindParam(':idMat',$idMateria);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
    $conexion = null;
    $con = null;
    return $response;
    }

    /**
     * Consulta de chuy
     */
    public function consultar_materias_plan_ciclo($plan, $ciclo){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT pl_m.*, mat.*,ac.imagen as imgc, ac.nombre as acNombre, ac.title as acTitle FROM planes_materias pl_m 
            JOIN materias mat ON mat.id_materia = pl_m.id_materia
            LEFT JOIN a_carreras as ac on ac.idCarrera = mat.id_carrera
            WHERE pl_m.id_plan = :plan AND pl_m.ciclo_asignado = :ciclo;";

            $statement = $con->prepare($sql);
            $statement->execute(['plan'=>$plan, 'ciclo'=>$ciclo]);

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
    public function obtenerListadoDeCarreras($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $whr = "WHERE idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11";
            if($id == 4){
                $whr = "WHERE idCarrera = 14 or idCarrera = 19";
            }

            $sql = "SELECT idCarrera, nombre
                FROM a_carreras
                {$whr}
                ORDER BY nombre ASC";

            $statement = $con->prepare($sql);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
        $con = null;
        $conexion = null;
        return $response;
    }
    public function obtenerMateriasPorCarrera($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql = "SELECT mt.*, carrer.nombre as nombreCarr, carrer.idCarrera
                FROM materias mt
                INNER JOIN a_carreras carrer ON carrer.idCarrera = mt.id_carrera
                WHERE mt.id_carrera = :idCarrera AND mt.estatus = 1";

            $statement = $con->prepare($sql);
            $statement->execute($id);
        }
        $conexion = null;
        $con = null;
        return $statement;
    }

    // mas funciones chuy
    public function consultar_sesiones($generacion){//obtenemos las clases programadas solo de hoy
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql = "SELECT ses.*, cls.fecha_hora_clase as fecha_clase,cls.idClase, ac.idInstitucion
            FROM `accesos_sesion_webex` ses
            JOIN clases cls ON cls.idClase = ses.id_clase
            JOIN a_generaciones gens ON gens.idGeneracion = cls.idGeneracion
            JOIN a_carreras as ac on ac.idCarrera = gens.idCarrera
            WHERE gens.idGeneracion = :generacion AND cls.fecha_hora_clase BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL 1 DAY)";

            $statement = $con->prepare($sql);
            $statement->bindParam(':generacion', $generacion);
            $statement->execute();
        }
        $conexion = null;
        $con = null;
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultar_sesiones_sin_filtro($generacion){//obtenemos las clases programadas solo de hoy
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql = "SELECT ses.*, cls.fecha_hora_clase as fecha_clase, cls.idClase, cls.titulo , ac.idInstitucion, mats.id_materia,
            cls.video
            FROM clases cls
            LEFT JOIN `accesos_sesion_webex` ses ON cls.idClase = ses.id_clase
            LEFT JOIN a_generaciones gens ON gens.idGeneracion = cls.idGeneracion
            LEFT JOIN materias mats ON mats.id_materia = cls.idMateria
            LEFT JOIN a_carreras as ac on ac.idCarrera = gens.idCarrera
            WHERE gens.idGeneracion = :generacion"; // filtro fecha   AND cls.fecha_hora_clase > DATE_SUB(CURDATE(),INTERVAL 1 DAY)
            $statement = $con->prepare($sql);
            $statement->bindParam(':generacion', $generacion);
            $statement->execute();
        }
        $conexion = null;
        $con = null;
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function consultar_clases_materia($materia, $generacion,$groupG,$vista){
        $conexion = new Conexion();

        // $whr = '';
        // if($vista == 4){
        //     $whr = "and cls.grupo = '$groupG'";
        // }
        $con = $conexion->conectar()['conexion'];
        $sql = "SELECT mats.id_materia, mats.nombre, carr.nombre as carrera_nombre, mats.oficial, cls.idClase, cls.titulo, cls.fecha_hora_clase, cls.idMaestro, mast.nombres, mast.aPaterno, mast.aMaterno, cls.idGeneracion, carr.idInstitucion FROM materias mats
            JOIN clases cls ON cls.idMateria = mats.id_materia
            JOIN maestros mast ON mast.id = cls.idMaestro
            JOIN a_carreras carr ON carr.idCarrera = mats.id_carrera
            WHERE mats.id_materia = :materia AND cls.idGeneracion = :generacion 
            ORDER BY fecha_hora_clase ASC";
        $statement = $con->prepare($sql);
        $statement->bindParam(':materia', $materia);
        $statement->bindParam(':generacion', $generacion);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    // fin funciones chuy
    public function obtener_calificaciones_periodo($idGeneracion,$idProspecto,$numero_ciclo){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT calif.*, agen.tipoCiclo, mat.nombre
            FROM calificaciones as calif
            JOIN a_generaciones as agen on agen.idGeneracion=calif.idGeneracion
            JOIN materias as mat on mat.id_materia=calif.id_materia
            WHERE calif.idGeneracion=:idGeneracion AND calif.idProspecto=:idProspecto AND calif.numero_ciclo=:numero_ciclo";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idGeneracion', $idGeneracion);
            $statement->bindParam(':idProspecto', $idProspecto);
            $statement->bindParam(':numero_ciclo', $numero_ciclo);

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

    public function obtener_numero_de_ciclos($idGeneracion,$idProspecto){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT calif.numero_ciclo, agen.tipoCiclo, COUNT(calif.numero_ciclo) as numero_materias 
                    FROM calificaciones as calif 
                    JOIN a_generaciones as agen on agen.idGeneracion=calif.idGeneracion 
                    JOIN materias as mat on mat.id_materia=calif.id_materia 
                    WHERE calif.idGeneracion=:idGeneracion AND calif.idProspecto=:idProspecto 
                    GROUP by calif.numero_ciclo;";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idGeneracion', $idGeneracion);
            $statement->bindParam(':idProspecto', $idProspecto);

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
    function pago_cursos($prospecto){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];


            $sql = "SELECT DISTINCT pag.id_prospecto, pag.*, concep.concepto 
            FROM a_pagos pag 
            INNER JOIN pagos_conceptos concep ON concep.id_concepto = pag.id_concepto 
            WHERE pag.id_concepto IN (2, 5, 7, 9,16) AND pag.id_prospecto = :prospecto
            GROUP BY pag.id_prospecto;";

            $statement = $con->prepare($sql);
            $statement->bindParam(':prospecto',$prospecto);
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
    function validarAlumnoMateria($datos){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];


            $sql = "SELECT * FROM clases_incripcion WHERE idMateria = :materia AND idAlumno = :alumno AND estatus = 1;";

            $statement = $con->prepare($sql);
            $statement->execute($datos);


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
    function cursoClases($curso, $generacion){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];


            $sql = "SELECT *
                    FROM clases as cl 
                    JOIN maestros as mt on mt.id = cl.idMaestro 
                    JOIN materias as mr on mr.id_materia = cl.idMateria
                    WHERE idMateria = :materia AND idGeneracion = :generacion AND cl.estado = 1";

            $statement = $con->prepare($sql);
            $statement->bindParam(':materia', $curso);
            $statement->bindParam(':generacion', $generacion);
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
    function tareasClase($clase){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];


            $sql = "SELECT * FROM clases_tareas WHERE idClase = :clase";

            $statement = $con->prepare($sql);
            $statement->bindParam(':clase', $clase);
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
    function entregar_tareas($datos){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];
            $fecha_hora = date('Y-m-d H:i:s');
            $statement = '';
            $type = $datos['type'];
            if($type == 'actividad'){
            $datos = [
                    'idtarea' => $datos['tarea'],
                    'alumno' => $datos['alumno'],
                    'comentario'=> $datos['comentario'],
                    'archivo'=>$datos['archivo'],
                    'dates'=>$fecha_hora
                ];
                $sql = 'INSERT INTO tb_actividad_educate (`idActividad`, `idAlumno`,`comentario`,`archivo`, `fecha_entrega`)
                VALUE(:idtarea, :alumno,:comentario, :archivo ,:dates)';

            }else{
                unset($datos['type']);
                $sql = "INSERT INTO `clases_tareas_entregas`(`idTarea`, `idAlumno`, `archivo`, `comentario`, `calificacion`, `fecha_entrega`) 
            VALUES (:tarea, :alumno, :archivo, :comentario, 0, '{$fecha_hora}') ";
                
            }

            

            $statement = $con->prepare($sql);
            $statement->execute($datos);

            if($type = 'activity'){
                $value = $con->lastInsertId();
            }else{
                $value = $statement->rowCount();
            }

            if($statement->errorInfo()[0] == 00000){
                $response = ["estatus"=>"ok", "data"=>$value];
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
    function obtener_info_tarea_entrega($tarea, $alumno){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];


            $sql = "SELECT * FROM clases_tareas_entregas WHERE idTarea = :tarea AND idAlumno = :alumno";

            $statement = $con->prepare($sql);
            $statement->bindParam(':tarea', $tarea);
            $statement->bindParam(':alumno', $alumno);
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
    public function getUsers($id,$type){
        $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
                $filter = '';
    
            
                if($type != 31 and $type != 36){
                    $filter = 'and (acc.estatus_acceso != 1)';
                }

            $sql = "SELECT ce.nombres,acc.correo as email,ce.estado,ce.id,acc.estatus_acceso
                    FROM controlescolar as ce
                    JOIN a_accesos as acc on acc.idPersona = ce.id
                    WHERE acc.idTipo_Persona = '$type' and acc.idPersona != '$id' {$filter}";
                    $statement = $con->prepare($sql);
                    $statement->execute();

                $response = $statement;

                $conexion = null;
                    $con = null;
                    return $response;	

            }

            
    }
    public function setUsers($post){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            if(isset($post['typeData'])){

                $idP = $post['idTP']; 
                unset($post['idTP']);
                unset($post['idUsM']);
                
                $idUs = $post['idUs'];
                
                if($post['typeData'] == 'editU'){
                        
                    $email = $post['email'];
                    $roles = $post['roles'];
                    $names = $post['names'];
    
                        $sql = "UPDATE `a_accesos` SET `correo` = '$email', `estatus_acceso` = '$roles'
                        WHERE idTipo_Persona = '$idP' and idPersona = '$idUs'";
                        $sql1 = "UPDATE `controlescolar` 
                        SET `nombres`= '$names' WHERE `id` = '$idUs'";
                     
                        $statement1 = $con->prepare($sql1);
                        $statement1->execute();
                        $statement = $con->prepare($sql);
                        $statement->execute();
                        if($statement->errorInfo()[0] == 00000){
                            
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                        }
                }else{
                
                    
                    $type = $post['typeData'];
                    $sql = "UPDATE `controlescolar` 
                        SET `estado`= '$type' WHERE `id` = '$idUs'" ;
                    $statement = $con->prepare($sql);
                    $statement->execute($post);
                    if($statement->errorInfo()[0] == 00000){
                        
                        $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                    }else{
                        $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                    }
                }
               
    
                

            }else{
                
                $postOf = [
                    'names'=>$post['names'],
                ];

                
                $sql = "INSERT INTO `controlescolar`(`nombres`,`estado`) 
                        VALUES (:names,1)";
                $statement = $con->prepare($sql);
                $statement->execute($postOf);
                if($statement->errorInfo()[0] == 00000){
                    $postMail = [
                        'idTP'=>$post['idTP'],
                        'idUs'=> $con->lastInsertId(),
                        'email'=> $post['email'],
                        'roles' =>$post['roles']
                    ];
                    $sql = "INSERT INTO `a_accesos`(`idTipo_Persona`, `idPersona`, `correo`, `contrasenia`, `estatus_acceso`) 
                            VALUES (:idTP,:idUs,:email, aes_encrypt('12345','SistemasPUE21') ,:roles)";
                $statement = $con->prepare($sql);
                $statement->execute($postMail);
                if($statement->errorInfo()[0] == 00000){
                    $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                }   
                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                }   
            }
        }else{
            $response = ["estatus"=>"error","data"=>"error de conexion"];
        }

        $conexion = null;
        $con = null;
        return $response;	
    }
}