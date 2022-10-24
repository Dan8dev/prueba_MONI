<?php 
date_default_timezone_set("America/Mexico_City");

class fechasCorte{
    public function obtenerGeneraciones(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT gen.*,car.nombre as nombrecarrera
				FROM a_generaciones as gen
                JOIN a_carreras as car ON car.idCarrera = gen.idCarrera
                WHERE gen.estatus = 1";

			$statement = $con->prepare($sql);
			$statement->execute();
			}
		$conexion = null;
		$con = null;
		return $statement;
	}

  public function obteneralumnos(){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT ap.idAsistente,concat(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno) as alumno, gen.secuencia_generacion as generacion, car.nombre as carrera, gen.idGeneracion
      FROM a_prospectos as ap 
      JOIN alumnos_generaciones as ag on ag.idalumno=ap.idAsistente 
      JOIN a_generaciones as gen on gen.idGeneracion=ag.idgeneracion 
      JOIN a_carreras as car on car.idCarrera=gen.idCarrera
      JOIN planes_pagos ppg ON ppg.idCarrera = gen.idCarrera";

			$statement = $con->prepare($sql);
			$statement->execute();
			}
		$conexion = null;
		$con = null;
		return $statement;
	}

    public function buscarGeneracionfechacorte($id_generacion){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT pc.*,ag.nombre as nombregeneracion, ac.nombre as nombrecarrera
                FROM pagos_conceptos as pc
                JOIN a_generaciones AS ag on ag.idGeneracion=pc.id_generacion
                JOIN a_carreras as ac on ac.idCarrera=ag.idCarrera
                WHERE ag.idGeneracion=:id_generacion AND pc.idExamen IS NULL OR pc.idExamen = 'null' OR pc.idExamen = 'NULL'";
    
      $statement = $con->prepare($sql);
    $statement->bindParam(':id_generacion',$id_generacion);
      $statement->execute();
    
      if($statement->errorInfo()[0] == 00000){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
        }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
        }
        $conexion = null;
        $con = null;
    
        return $response;
      }
    }

    public function buscaralumnofechacorte($idGeneracion, $idAsistente){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT fecha_primer_colegiatura
                FROM alumnos_generaciones
                WHERE idgeneracion=:idGeneracion AND idalumno=:idAsistente";
    
      $statement = $con->prepare($sql);
      $statement->bindParam(':idGeneracion',$idGeneracion);
      $statement->bindParam(':idAsistente',$idAsistente);

      $statement->execute();
    
      if($statement->errorInfo()[0] == 00000){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
        }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
        }
        $conexion = null;
        $con = null;
    
        return $response;
      }
    }

    public function obtenerconceptosoriginales($id_generacion){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
      
        if($con['info'] == 'ok'){
        $con = $con['conexion'];
        $sql = "SELECT pc.*,ag.nombre as nombregeneracion,ac.nombre as nombrecarrera, pp.total as costototal
                FROM pagos_conceptos as pc
                JOIN planes_pagos as pp on pp.idPlanPago=pc.idPlan_pago
                JOIN a_carreras as ac on ac.idCarrera=pp.idCarrera
                JOIN a_generaciones as ag on ag.idCarrera=ac.idCarrera
                WHERE ag.idGeneracion=:id_generacion";
      
        $statement = $con->prepare($sql);
        $statement->bindParam(':id_generacion',$id_generacion);
        $statement->execute();
      
        if($statement->errorInfo()[0] == 00000){
          $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
          }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
          }
          $conexion = null;
          $con = null;
      
          return $response;
        }
      }

      public function consultarconceptoins($idgeneracion){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
      
        if($con['info'] == 'ok'){
        $con = $con['conexion'];
        $sql = "SELECT * 
                FROM pagos_conceptos
                WHERE id_generacion=:id_generacion";
      
        $statement = $con->prepare($sql);
        $statement->bindParam(':id_generacion',$idgeneracion);

        $statement->execute();
      
        if($statement->errorInfo()[0] == 00000){
          $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
          }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
          }
          $conexion = null;
          $con = null;
      
          return $response;
        }
      }

      public function crearconceptofechacortegenins($nombregeneracion,$costoinscripcion,$costoinscripcionusd,$categoria,$pago_aplicar,$idgeneracion,$parcialidades,$fechacorteinscripcion,$eliminado,$creadopor,$numerodepagos){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
      
        if($con["info"] == "ok"){
          $con = $con["conexion"];
          $sql = "INSERT INTO pagos_conceptos (concepto, descripcion, precio,precio_usd,categoria,pago_aplicar,id_generacion,parcialidades,fechalimitepago,eliminado,creado_por,numero_pagos)
            VALUES (:concepto, :descripcion, :precio, :precio_usd, :categoria, :pago_aplicar, :id_generacion, :parcialidades, :fechalimitepago, :eliminado, :creado_por, :numero_pagos); ";
              
          $statement = $con->prepare($sql);
          $statement->bindParam(':concepto',$nombregeneracion);
          $statement->bindParam(':descripcion',$nombregeneracion);
          $statement->bindParam(':precio',$costoinscripcion);
          $statement->bindParam(':precio_usd',$costoinscripcionusd);
          $statement->bindParam(':categoria',$categoria);
          $statement->bindParam(':categoria',$categoria);
          $statement->bindParam(':pago_aplicar',$pago_aplicar);
          $statement->bindParam(':id_generacion',$idgeneracion);
          $statement->bindParam(':parcialidades',$parcialidades);
          $statement->bindParam(':fechalimitepago',$fechacorteinscripcion);
          $statement->bindParam(':eliminado',$eliminado);
          $statement->bindParam(':creado_por',$creadopor);
          $statement->bindParam(':numero_pagos',$numerodepagos);


          $statement->execute();
      
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

      public function actualizarconceptofechacortegenins($idconceptoinscripcion, $nuevoprecioins, $nuevoprecioinsusd, $nuevafechalimitepagoins, $actualizadopor, $fechaactualizado){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
          $sql = "UPDATE pagos_conceptos SET precio = :costoIns,precio_usd = :costoInsusd, fechalimitepago = :fechalimitepago, actualizado_por = :actualizado_por, fecha_actualizado=:fecha_actualizado
          WHERE id_concepto = :id_concepto";
    
          $statement = $con->prepare($sql);
          $statement->bindParam(':costoIns',$nuevoprecioins);
          $statement->bindParam(':costoInsusd',$nuevoprecioinsusd);
          $statement->bindParam(':fechalimitepago',$nuevafechalimitepagoins);
          $statement->bindParam(':actualizado_por',$actualizadopor);
          $statement->bindParam(':fecha_actualizado',$fechaactualizado);
          $statement->bindParam(':id_concepto',$idconceptoinscripcion);
    
          $statement->execute();
    
          if($statement->errorInfo()[0] == '00000'){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
          }
        }
        $conexion = null;
        $con = null;
        return $response;
      }

      public function actualizarconceptofechacortealumno($idGeneracion, $idAsistente, $fechaprimercolegiaturamod){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
          $sql = "UPDATE alumnos_generaciones SET fecha_primer_colegiatura = :fecha_primer_colegiatura
          WHERE idalumno = :idAsistente AND idgeneracion = :idGeneracion";
    
          $statement = $con->prepare($sql);
          $statement->bindParam(':fecha_primer_colegiatura',$fechaprimercolegiaturamod);
          $statement->bindParam(':idAsistente',$idAsistente);
          $statement->bindParam(':idGeneracion',$idGeneracion);

    
          $statement->execute();
    
          if($statement->errorInfo()[0] == '00000'){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
          }
        }
        $conexion = null;
        $con = null;
        return $response;
      }

      public function actualizarconceptofechacortegenmens($idconceptoinscripcion, $nuevoprecioins, $nuevoprecioinsusd, $nuevafechalimitepagoins, $actualizadopor, $fechaactualizado,$numerodepagos){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
          $sql = "UPDATE pagos_conceptos SET precio = :costoIns,precio_usd = :costoInsusd, fechalimitepago = :fechalimitepago, actualizado_por = :actualizado_por, fecha_actualizado=:fecha_actualizado,numero_pagos=:numero_pagos
          WHERE id_concepto = :id_concepto";
    
          $statement = $con->prepare($sql);
          $statement->bindParam(':costoIns',$nuevoprecioins);
          $statement->bindParam(':costoInsusd',$nuevoprecioinsusd);
          $statement->bindParam(':fechalimitepago',$nuevafechalimitepagoins);
          $statement->bindParam(':actualizado_por',$actualizadopor);
          $statement->bindParam(':fecha_actualizado',$fechaactualizado);
          $statement->bindParam(':id_concepto',$idconceptoinscripcion);
          $statement->bindParam(':numero_pagos',$numerodepagos);
    
          $statement->execute();
    
          if($statement->errorInfo()[0] == '00000'){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
          }
        }
        $conexion = null;
        $con = null;
        return $response;
      }

      public function actualizarconceptofechacortegenreins($idconceptoinscripcion, $nuevoprecioins, $nuevoprecioinsusd, $actualizadopor, $fechaactualizado,$numeroreins){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
          $sql = "UPDATE pagos_conceptos SET precio = :costoIns,precio_usd = :costoInsusd, actualizado_por = :actualizado_por, fecha_actualizado=:fecha_actualizado,numero_pagos=:numero_pagos
          WHERE id_concepto = :id_concepto";
    
          $statement = $con->prepare($sql);
          $statement->bindParam(':costoIns',$nuevoprecioins);
          $statement->bindParam(':costoInsusd',$nuevoprecioinsusd);
          $statement->bindParam(':actualizado_por',$actualizadopor);
          $statement->bindParam(':fecha_actualizado',$fechaactualizado);
          $statement->bindParam(':id_concepto',$idconceptoinscripcion);
          $statement->bindParam(':numero_pagos',$numeroreins);
    
          $statement->execute();
    
          if($statement->errorInfo()[0] == '00000'){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
          }
        }
        $conexion = null;
        $con = null;
        return $response;
      }

      public function actualizarconceptofechacortegentit($idconceptoinscripcion, $nuevoprecioins, $nuevoprecioinsusd, $nuevafechalimitepagoins, $actualizadopor, $fechaactualizado){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
          $sql = "UPDATE pagos_conceptos SET precio = :costoIns,precio_usd = :costoInsusd, fechalimitepago = :fechalimitepago, actualizado_por = :actualizado_por, fecha_actualizado=:fecha_actualizado
          WHERE id_concepto = :id_concepto";
    
          $statement = $con->prepare($sql);
          $statement->bindParam(':costoIns',$nuevoprecioins);
          $statement->bindParam(':costoInsusd',$nuevoprecioinsusd);
          $statement->bindParam(':fechalimitepago',$nuevafechalimitepagoins);
          $statement->bindParam(':actualizado_por',$actualizadopor);
          $statement->bindParam(':fecha_actualizado',$fechaactualizado);
          $statement->bindParam(':id_concepto',$idconceptoinscripcion);
    
          $statement->execute();
    
          if($statement->errorInfo()[0] == '00000'){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
          }
        }
        $conexion = null;
        $con = null;
        return $response;
      }

      public function actualizarfechalimitedepagoprorroga($idconceptoinscripcion, $nuevafechalimitepagoins){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
          $sql = "UPDATE prorrogas SET fechalimitepago = :fechalimitepago
          WHERE id_concepto = :id_concepto";
    
          $statement = $con->prepare($sql);
          $statement->bindParam(':fechalimitepago',$nuevafechalimitepagoins);
          $statement->bindParam(':id_concepto',$idconceptoinscripcion);
    
          $statement->execute();
    
          if($statement->errorInfo()[0] == '00000'){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
          }
        }
        $conexion = null;
        $con = null;
        return $response;
      }

      function verificar_no_pago($generacion, $alumno){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];
        $sql = "SELECT pg.* FROM a_pagos pg 
          JOIN pagos_conceptos pc ON pc.id_concepto = pg.id_concepto
          WHERE pg.id_prospecto = :alumno AND pc.id_generacion = :generacion AND pg.estatus = 'verificado' AND pc.categoria = 'Mensualidad';";
        $statement = $con->prepare($sql);
        $statement->bindParam(':alumno',$alumno);
        $statement->bindParam(':generacion',$generacion);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
      }
}
