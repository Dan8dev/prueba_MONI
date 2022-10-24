<?php
date_default_timezone_set("America/Mexico_City");

class Verificacion{

    function registrarJerarquia($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if ($con['info'] == 'ok') {
            $con = $con['conexion'];
            $sql = "INSERT INTO `jerarquias` (`nivel`, `nombre`) VALUES (:nivel, :nombre)";

            $statement = $con->prepare($sql);
            $statement->execute($data);

            if ($statement->errorInfo()[0] == '00000') {
                $response = ['estatus' => 'ok', 'data' => $con->lastInsertId()];
            } else {
                $response = ['estatus' => 'ok', 'info' => 'Error al registrar', 'details' => $statement->errorInfo()];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }

    function consultarJerarquias(){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        $sql = "SELECT * FROM `jerarquias` ";

        $statement = $con->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function consultarJerarquiaId($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        $sql = "SELECT * FROM `jerarquias` WHERE id_jerarquia = :jerarquia";

        $statement = $con->prepare($sql);
        $statement->bindParam(':jerarquia', $id);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function consultarJerarquiaProspecto($prospecto){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        $sql = "SELECT jq.*, prs.nombre, prs.aPaterno, prs.aMaterno, prs.correo, afc.estado FROM `jerarquias` jq
            JOIN afiliados_conacon afc ON afc.jerarquia = jq.id_jerarquia
            JOIN a_prospectos prs ON prs.idAsistente = afc.id_prospecto
            WHERE afc.id_prospecto = :prospecto";

        $statement = $con->prepare($sql);
        $statement->bindParam(':prospecto', $prospecto);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function consultarJerarquiaEstado($estado, $jerarquia = null){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        $sql = "SELECT jq.*, prs.nombre as nombre_prospecto, prs.aPaterno, prs.aMaterno, prs.correo, afc.estado FROM `jerarquias` jq
        JOIN afiliados_conacon afc ON afc.jerarquia = jq.id_jerarquia
        JOIN a_prospectos prs ON prs.idAsistente = afc.id_prospecto
        WHERE afc.estado = :estado ";
        if($jerarquia !== null){
            $sql.= " AND afc.jerarquia = ".$jerarquia;
        }

        $statement = $con->prepare($sql);
        $statement->bindParam(':estado', $estado);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function asignarJerarquiaProspecto($prospecto, $jerarquia){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        $sql = "UPDATE afiliados_conacon SET jerarquia = :jerarquia WHERE id_prospecto = :prospecto";

        $statement = $con->prepare($sql);
        $statement->bindParam(':prospecto', $prospecto);
        $statement->bindParam(':jerarquia', $jerarquia);
        $statement->execute();

        return $statement->rowCount();
    }
}