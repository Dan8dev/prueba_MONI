<?php
date_default_timezone_set("America/Mexico_City");

class Notificacion{

    function registrarNotificacion($prospecto, $titulo, $mensaje, $enviar_correo = 0){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        if($enviar_correo == 1){
            require_once '../../functions/correos_prospectos.php';
             $info_prosp = $con->query('SELECT * FROM a_prospectos WHERE idAsistente = '.$prospecto)->fetch(PDO::FETCH_ASSOC);
            $claves = ['%%TITULO', '%%NOMBRE', '%%TODOCONTENIDO'];
            $valores = [$titulo, $info_prosp['nombre'].' '.$info_prosp['aPaterno'], $mensaje];
            enviar_correo_registro($titulo, [[$info_prosp['correo'], $info_prosp['nombre']]], 'plantilla_interno.html', $claves, $valores);
        }


        $sql = "INSERT INTO `notificaciones`(`id_prospecto`, `titulo`, `mensaje`) VALUES 
            (:id_prospecto, :titulo, :mensaje)";

        $statement = $con->prepare($sql);
        $statement->bindParam(':id_prospecto', $prospecto);
        $statement->bindParam(':titulo', $titulo);
        $statement->bindParam(':mensaje', $mensaje);

        $statement->execute();

        return $con->lastInsertId();
    }

    function listarNotificacionesProspecto($prospecto, $estatus = null){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        $sql = "SELECT * FROM `notificaciones` WHERE `id_prospecto` = :id_prospecto";
        if($estatus !== null){
            $sql.= " AND estatus = ".$estatus;
        }

        $statement = $con->prepare($sql);
        $statement->bindParam(':id_prospecto', $prospecto);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function cambiarEstatusNotificacion($notificacion, $estatus){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $con = $con['conexion'];

        $sql = "UPDATE `notificaciones` SET estatus = :estatus WHERE `id_notificacion` = :notificacion";
        $statement = $con->prepare($sql);
        $statement->bindParam(':notificacion', $notificacion);
        $statement->bindParam(':estatus', $estatus);

        $statement->execute();
        return $statement->rowCount();
    }
}
function crearEvento($info){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $con = $con['conexion'];

    $sql = "INSERT INTO `ev_evento`(`titulo`, `fechaDisponible`, `fechaLimite`,`direccion`, `modalidadEvento`, `idInstitucion`,`descripcion`) 
    VALUES (:titulo, :fechaDisponible, :fechaLimite, :direccion, :descripcion)"

}