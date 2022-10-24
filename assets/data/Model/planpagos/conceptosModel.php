<?php 
date_default_timezone_set("America/Mexico_City");

class Conceptos{

    public function obtenerInstituciones(){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];

      if($con['info'] == 'ok'){
        $con = $con['conexion'];
        $sql = "SELECT id_institucion, nombre
          FROM a_instituciones
          WHERE fundacion = 0 AND estatus = 1 AND id_institucion != 2";

        $statement = $con->prepare($sql);
        $statement->execute();

        if($statement->errorInfo()[0] == 00000){
          $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
        }else{
          $response = ['estatus'=>'ok', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
        }
      }
      $conexion = null;
      $con = null;
      return $response;
    }

    public function crearConcepto($concepto){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con["info"] == "ok"){
      $con = $con["conexion"];
  
      $sql = "INSERT INTO pagos_conceptos 
      (concepto, categoria, precio, precio_usd, parcialidades, descripcion, creado_por, fechacreado, eliminado, actualizado_por, fecha_actualizado, generales, institucion, numero_pagos)
      VALUES(:nombreconcepto, 'Generales', :precio, :precio_usd, :selectParcialidades, :descripcion, :creador_por, :fCreado, 1, NULL, NULL, 1, :selectInstitucionConcepto, 1)";
            
      $statement = $con->prepare($sql);
      $statement->execute($concepto);

        if($statement->errorInfo()[0] == '00000'){
          $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
        }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$concepto];
        }
        
      }
    $conexion = null;
    $con = null;  
    
    return $response;
    }

    public function obtenerConceptos(){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
            $con = $con['conexion'];
    
            $sql = "SELECT paCon.*, inst.nombre as nombreInst
                    FROM pagos_conceptos paCon
                    INNER JOIN a_instituciones inst ON inst.id_institucion = paCon.institucion
                    WHERE paCon.eliminado = 1 AND paCon.categoria != 'inscripcion' AND paCon.categoria != 'mensualidad' AND paCon.categoria != 'reinscripcion' AND paCon.generales = 1";
  
          $statement = $con->prepare($sql);
          $statement->execute();
        }
        $conexion = null;
        $con = null;

        return $statement;
    }

    public function obtenerConcepto($idconcepto){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
      
        if($con['info'] == 'ok'){
        $con = $con['conexion'];
        $sql = "SELECT paCon.*, vis.idVista as vista
                FROM pagos_conceptos paCon
                LEFT JOIN vistas vis ON vis.id_concepto = paCon.id_concepto
                WHERE paCon.id_concepto=:idconcepto";
      
        $statement = $con->prepare($sql);
        $statement->execute($idconcepto);
      
          if($statement->errorInfo()[0] == 00000){
            $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$idconcepto];
          }
        }
      $conexion = null;
      $con = null;  
      return $response;
    }

      public function modificarConcepto($mod){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response =  [];
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
          $sql = "UPDATE pagos_conceptos SET institucion = :editarSelectInstitucionConceptos , concepto = :editarNombreConcepto, precio = :editarPrecio, precio_usd = :editarPrecio_usd, parcialidades = :editarParcialidades,
              descripcion = :editarDescripcion, actualizado_por = :modificado_por, fecha_actualizado = :fModificado 
              WHERE id_concepto = :idC";
          
          $statement = $con->prepare($sql);
          $statement->execute($mod);

          if($statement->errorInfo()[0] == "00000"){
            $response = ["estatus"=> "ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->error_info(), "sql"=>$sql, "data"=>$mod];
          }
        }
        $conexion = null;
        $con = null;

        return $response;
      }

      public function eliminarConcepto($id){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
    
        if($con['info'] == 'ok'){
          $con = $con['conexion'];
    
          $sql = "UPDATE pagos_conceptos SET eliminado = 2 WHERE id_concepto = :idEliminar";
    
          $statement = $con->prepare($sql);
          $statement->execute($id);
    
          if($statement->errorInfo()[0] == "00000"){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
          }
            
        }
        $conexion = null;
        $con = null;
    
        return $response;
      }

}
