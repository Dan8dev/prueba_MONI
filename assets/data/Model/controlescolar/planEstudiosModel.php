<?php 
date_default_timezone_set("America/Mexico_City");

class PlanEstudios{


    public function obtenerClavePlan($id,$val,$idExistente){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $filtro= "";
            if($val == 2){
                $filtro = "AND id_plan_estudio != $idExistente";
            }

            $sql = "SELECT * 
            FROM planes_estudios 
            WHERE clave_plan = :id {$filtro}";

                $statement = $con->prepare($sql);
                $statement->bindParam(':id', $id);
                $statement->execute();

                if($statement->errorInfo()[0] == '00000'){
                    if($statement->rowCount()>0){
                        $ret = "Clave_existente";
                    }else{
                        $ret = "Registrar";
                    }
                    $response = ["estatus"=>"ok", "data"=>$ret];
                }else{
                    $response =["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
                }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function obtenerCarreras($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $complete = " WHERE carr.estatus = 1 AND carr.idCarrera != 3 AND carr.idCarrera != 4 AND carr.idCarrera != 5 AND carr.idCarrera != 10 AND carr.idCarrera != 11";
            if($id == 4){
                $complete = " WHERE carr.estatus = 1 AND (carr.idCarrera = 14 or carr.idCarrera = 19)";
            }

            $sql = "SELECT carr.idCarrera, carr.nombre, carr.tipo, inst.id_institucion
                    FROM a_carreras carr
                    INNER JOIN a_instituciones inst ON inst.id_institucion = carr.idInstitucion
                   {$complete}
                    ORDER BY carr.nombre ASC";
            /*$sql = "SELECT idCarrera, nombre
                FROM a_carreras
                WHERE estatus = 1 AND tipo != 1
                ORDER BY nombre ASC";*/

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

    public function crearPlanEstudios($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $campo = "";
            $insert = "";

            if(isset($data['PlanReferencia'])){
                $campo = ",plan_ref";
                $insert = ",:PlanReferencia";
            }

            $sql = "INSERT INTO planes_estudios(id_carrera, nombre, clave_plan, tipo_ciclo, numero_ciclos, tipo_rvoe, creado_por, fecha_creado, activo {$campo})
            VALUES(:selectCarreraPlanE, :nombrePlanE, :clavePlanE, :selectCicloPlanE, :numeroCiclosPlanE, :tipoRvoeCrear, :creado_por, :fCreado, 1 {$insert})";

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

    public function crearPlanEstudiosRvoe($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $campo = "";
            $insert = "";
            if(isset($data['PlanReferencia'])){
            $campo = ",plan_ref";
            $insert = ",:PlanReferencia";
            }
            $sql = "INSERT INTO planes_estudios
                (id_carrera, nombre, clave_plan, tipo_ciclo, numero_ciclos, tipo_rvoe, rvoe, fecha_registro_rvoe, creado_por, fecha_creado, activo {$campo})
                VALUES(:selectCarreraPlanE, :nombrePlanE, :clavePlanE, :selectCicloPlanE, :numeroCiclosPlanE, :tipoRvoeCrear, :rvoePlanEstudiosCrear,:FecharvoePlanEstudiosCrear ,:creado_por, :fCreado, 1 {$insert})";

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

	public function obtenerPlanesEstudio($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $complete = '';
            if($id == 4){
                $complete = "and (carrer.idCarrera = 14 or carrer.idCarrera = 19)";
            }
            $sql = "SELECT plE.*, carrer.nombre as nombreCarr
            FROM planes_estudios plE
            INNER JOIN a_carreras carrer ON carrer.idCarrera = plE.id_carrera
            WHERE activo = 1 {$complete}";

            $statement = $con->prepare($sql);
            $statement->execute();

        }
        $conexion = null;
        $con = null;
        return $statement;
    }

    public function obtenerListaPlanEstudio($id,$data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $complete = '';
            if($id == 4){
                $complete = "and (carrer.idCarrera = 14 or carrer.idCarrera = 19)";
            }

            $sql = "SELECT plE.*, carrer.nombre as nombreCarr
                    FROM planes_estudios plE
                    INNER JOIN a_carreras carrer ON carrer.idCarrera = plE.id_carrera
                    WHERE plE.plan_ref IS NULL AND plE.activo = 1 AND plE.id_carrera = :idCarr {$complete}";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function obtenerPlanEstudio($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT *
                    FROM planes_estudios
                    WHERE id_plan_estudio = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function modificarPlanEstudiosClave($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE planes_estudios SET id_carrera = :modSelectCarreraPlanE, nombre = :modNombrePlanE, clave_plan = :modClavePlanE,
                 tipo_ciclo = :modSelectCicloPlanE, numero_ciclos = :modNumeroCiclosPlanE, tipo_rvoe = :tipoRvoe, rvoe = :rvoePlanEstudios, fecha_registro_rvoe =  :FecharvoePlanEstudiosEditar, modificado_por = :modificado_por, fecha_actualizado = :fModificado
                WHERE id_plan_estudio = :id_plan_estudio";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function insertarNumReinsconceptoscarreras($idCarrera, $numeroderesincripciones){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
        $numeroderesincripciones = $numeroderesincripciones-1;

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE pagos_conceptos 
                    SET numero_pagos = :numero_pagos
                WHERE id_generacion in (SELECT idGeneracion FROM a_generaciones WHERE idCarrera = :idCarrera) AND categoria = 'Reinscripción'";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idCarrera', $idCarrera);
            $statement->bindParam(':numero_pagos', $numeroderesincripciones);

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

    public function insertarNumReinsconceptos($idGeneracion, $numeroderesincripciones){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
        $numeroderesincripciones = $numeroderesincripciones-1;

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE pagos_conceptos 
                    SET numero_pagos=:numero_pagos
                WHERE id_generacion=:idGeneracion AND categoria = 'Reinscripción'";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idGeneracion', $idGeneracion);
            $statement->bindParam(':numero_pagos', $numeroderesincripciones);

            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$idGeneracion];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function modificarPlanEstudiosSinClave($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE planes_estudios SET id_carrera = :modSelectCarreraPlanE, nombre = :modNombrePlanE, clave_plan = :modClavePlanE,
                tipo_ciclo = :modSelectCicloPlanE, numero_ciclos = :modNumeroCiclosPlanE, tipo_rvoe = :tipoRvoe, modificado_por = :modificado_por, fecha_actualizado = :fModificado
                WHERE id_plan_estudio = :id_plan_estudio";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }
    
    public function modificarPlanEstudiosNull($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE planes_estudios SET id_carrera = :modSelectCarreraPlanE, nombre = :modNombrePlanE, clave_plan = :modClavePlanE,
                tipo_ciclo = :modSelectCicloPlanE, numero_ciclos = :modNumeroCiclosPlanE, tipo_rvoe = :tipoRvoe, rvoe = '', modificado_por = :modificado_por, fecha_actualizado = :fModificado
                WHERE id_plan_estudio = :id_plan_estudio";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function eliminarPlanEstudios($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "UPDATE planes_estudios SET activo = 2
                WHERE id_plan_estudio = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function buscarPlanEstudios($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT plEst.id_plan_estudio, plEst.nombre, plEst.tipo_ciclo, plEst.numero_ciclos, plEst.id_carrera, carr.tipo, plEst.plan_ref
            FROM planes_estudios plEst
            INNER JOIN a_carreras carr ON plEst.id_carrera = carr.idCarrera
            WHERE plEst.id_plan_estudio = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
            }

        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function obtenerMateriasSinAsignar($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];



        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $planref = "";
            if(isset($data['planref']) && $data['planref'] != null && $data['planref'] != ""){
                $planref = "AND mat.id_materia NOT IN(SELECT plMat.id_materia
                            FROM planes_materias plMat
                            WHERE plMat.id_materia = mat.id_materia AND plMat.id_plan = :planref)";
            }else{
                $data['planref'] =  $data['idPlan'];
            }

            $sql = "SELECT mat.id_materia, mat.nombre, mat.oficial
                FROM materias mat
                WHERE mat.id_materia NOT IN(SELECT plMat.id_materia
                FROM planes_materias plMat
                WHERE plMat.id_materia = mat.id_materia AND plMat.id_plan = :idPlan OR plMat.id_plan = :planref) {$planref}
                AND mat.estatus = 1 AND mat.id_carrera = :idCarr";

                $statement = $con->prepare($sql);
                $statement->execute($data);

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
    public function obtenerMateriasSinAsignarConacon($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT mat.id_materia, mat.nombre
                FROM materias mat
                WHERE NOT EXISTS (SELECT NULL
                FROM planes_materias plMat
                WHERE plMat.id_materia = mat.id_materia AND plMat.id_plan = :idPlan) AND mat.estatus = 1 AND mat.id_carrera = :idCarr AND oficial = 2";

                $statement = $con->prepare($sql);
                $statement->execute($data);

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
    public function obtenerMateriasAsignadasPlan($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT plE.*, mat.nombre as nombreMat, mat.clave_asignatura as claveMat, mat.tipo as tipoMat, mat.numero_creditos as creditosMat
                FROM planes_materias plE
                INNER JOIN materias mat ON mat.id_materia = plE.id_materia
                WHERE plE.id_plan = :planEst AND plE.ciclo_asignado = :numCiclo;";

                $statement = $con->prepare($sql);
                $statement->execute($data);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, 'data'=>$data];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function borrarMateria($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "DELETE FROM planes_materias
                WHERE id_asignacion = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function guardarMateriasAsignadas($idPlan, $cicloAsignar, $idMateria){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "INSERT INTO planes_materias
                (id_plan, ciclo_asignado, id_materia)VALUES(:idPlan, :ciclo, :idMat)";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idPlan', $idPlan);
            $statement->bindParam(':ciclo', $cicloAsignar);
            $statement->bindParam(':idMat', $idMateria);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$con->lastInsertId()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
    $conexion = null;
    $con = null;
    return $response;
    }

    public function idPlanAsignado($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT id_plan 
            FROM planes_materias
            WHERE id_asignacion = :idAsig";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idAsig', $data);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function obtenerGeneracion($idPlanestudio){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT * 
            FROM a_generaciones
            WHERE id_plan_estudio = :id_plan_estudio";

            $statement = $con->prepare($sql);
            $statement->bindParam(':id_plan_estudio', $idPlanestudio);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function validarCrearPDFPlanEstudios($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT numero_ciclos
                FROM planes_estudios
                WHERE id_plan_estudio = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
            }

        }
    $conexion = null;
    $con = null;
    return $response;
    }

    public function contarAsignacionesPlanE($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT DISTINCT ciclo_asignado
                FROM planes_materias
                WHERE id_plan = :id";

            $statement = $con->prepare($sql);
            $statement->execute($id);

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$id];
            }
        }
    $conexion = null;
    $con = null;
    return $response;
    }

    public function datosPDFPlanEstudios($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT plEst.nombre, plEst.rvoe, plEst.tipo_ciclo, plEst.numero_ciclos, plEst.fecha_creado,
                carr.nombre as nombreCarr, carr.modalidadCarrera, inst.id_institucion, inst.nombre as nombreInst
                FROM planes_estudios plEst
                INNER JOIN a_carreras carr ON plEst.id_carrera = carr.idCarrera
                INNER JOIN a_instituciones inst ON inst.id_institucion = carr.idInstitucion
                WHERE plEst.id_plan_estudio = :id";

            $statement = $con->prepare($sql);
            $statement->bindParam(':id',$id);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
    $conexion = null;
    $con = null;
    return $response;
    }

    public function obtenerCarrerasMod($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT idCarrera, nombre
            FROM a_carreras
            WHERE estatus = 1 AND idCarrera = :idCarr";
			//file_put_contents('testmike.txt',json_encode($data));
            $statement = $con->prepare($sql);
            $statement->execute($data);

            if($statement->errorInfo()[0] == 00000){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
    $conexion = null;
    $con = null;
    return $response;
    }

    public function materiasPDFPlanEstudios($id_plan, $num_ciclo){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT mat.nombre, mat.clave_asignatura, mat.tipo, mat.numero_creditos, carr.idInstitucion
                    FROM planes_materias plMat
                    INNER JOIN materias mat ON mat.id_materia = plMat.id_materia
                    LEFT JOIN planes_estudios AS pl ON pl.id_plan_estudio =  plMat.id_plan
                    LEFT JOIN a_carreras AS carr ON carr.idCarrera = pl.id_carrera
                    WHERE plMat.id_plan = :idPlan AND plMat.ciclo_asignado = :numCiclo";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idPlan',$id_plan);
            $statement->bindParam(':numCiclo',$num_ciclo);
            $statement->execute();

            if($statement->errorInfo()[0] == 00000){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    public function validarCarreraCertificacionMod($datos){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql = "SELECT tipo
                FROM a_carreras
                WHERE idCarrera= :idCarr";

            $statement = $con->prepare($sql);
            $statement->execute($datos);

            if($statement->errorInfo()[0] == 00000){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql, 'data'=>$datos];
            }
        }
    $conexion = null;
    $con = null;
    return $response;
    }

}
