<?php 
date_default_timezone_set("America/Mexico_City");
	class Comision {
        public function cargar_parametros_comisiones($estatus = 1){
            $conexion = new Conexion(); 
            $con = $conexion->conectar(); 
            $response = [];

            if($con["info"] == "ok"){ 
                $con = $con["conexion"];
                $sql = "SELECT com.*, cars.nombre as nombre_carrera FROM `cc_comision` com
                    JOIN a_carreras cars ON cars.idCarrera = com.idCarrera
                    WHERE com.estatus = :estatus;"; 
                
                $statement = $con->prepare($sql); 
                $statement->bindParam(':estatus', $estatus); 
                $statement->execute();
                    
                if($statement->errorInfo()[0] == "00000"){
                    $response = ["estatus"=>"ok", "data"=> $statement->fetchAll(PDO::FETCH_ASSOC)];
                }else{
                    $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
                }
            }

            $conexion = null;
            $con = null;
            return $response;
        }

        public function cargar_usuarios($estatus = 1){
            $conexion = new Conexion(); 
            $con = $conexion->conectar(); 
            $response = [];

            if($con["info"] == "ok"){ 
                $con = $con["conexion"];
                $sql = "SELECT com.*, cars.nombre as nombre_carrera FROM `cc_comision` com
                    JOIN a_carreras cars ON cars.idCarrera = com.idCarrera
                    WHERE com.estatus = :estatus;"; 
                
                $statement = $con->prepare($sql); 
                $statement->bindParam(':estatus', $estatus); 
                $statement->execute();
                    
                if($statement->errorInfo()[0] == "00000"){
                    $response = ["estatus"=>"ok", "data"=> $statement->fetchAll(PDO::FETCH_ASSOC)];
                }else{
                    $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
                }
            }

            $conexion = null;
            $con = null;
            return $response;
        }

        public function cargar_parametro($id){
            $conexion = new Conexion(); 
            $con = $conexion->conectar()['conexion'];
            $response = [];
            $sql = "SELECT * FROM `cc_comision` WHERE idComision = :id;";
            $statement = $con->prepare($sql);
            $statement->bindParam(':id', $id);
            $statement->execute();
            if($statement->errorInfo()[0] == "00000"){
                $response = ["estatus"=>"ok", "data"=> $statement->fetch(PDO::FETCH_ASSOC)];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
            }
            $conexion = null;
            $con = null;
            return $response;
        }

        public function validar_rango($carrera, $tipo, $min, $max, $id){
            $conexion = new Conexion();
            $con = $conexion->conectar()['conexion'];
            $response = [];
            $sql = "SELECT * FROM `cc_comision` WHERE idCarrera = :carrera AND tipoColaborador = :tipo AND minimo >= :minim and maximo <= :maxim AND idComision != :idComision;";
            $statement = $con->prepare($sql);
            $statement->bindParam(':carrera', $carrera);
            $statement->bindParam(':tipo', $tipo);
            $statement->bindParam(':minim', $min);
            $statement->bindParam(':maxim', $max);
            $statement->bindParam(':idComision', $id);
            $statement->execute();
            if($statement->errorInfo()[0] == "00000"){
                $response = ["estatus"=>"ok", "data"=> $statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$data];
            }
            $conexion = null;
            $con = null;
            return $response;
        }

        public function actualizar_parametros($id, $minimo, $maximo, $comision, $tipo){
            $conexion = new Conexion();
            $con = $conexion->conectar()['conexion'];
            $response = [];
            $sql = "UPDATE `cc_comision` SET minimo = :minimo, maximo = :maximo, porcentaje = :comision, tipoColaborador = :tipo WHERE idComision = :id;";
            $statement = $con->prepare($sql);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':minimo', $minimo);
            $statement->bindParam(':maximo', $maximo);
            $statement->bindParam(':comision', $comision);
            $statement->bindParam(':tipo', $tipo);
            $statement->execute();
            if($statement->errorInfo()[0] == "00000"){
                $response = ["estatus"=>"ok", "data"=> $statement->rowCount()];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
            }
            $conexion = null;
            $con = null;
            return $response;
        }
    }
?>