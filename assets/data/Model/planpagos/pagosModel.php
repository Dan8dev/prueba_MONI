<?php 
date_default_timezone_set("America/Mexico_City");

class pagosModel{
  var $porcentaje_recargo = 15;
  var $estatus_pagos = ['verificado', 'pendiente', 'rechazado'];

    public function registrar_pago($id_prospecto,$id_concepto,$json_detalle,$fechapago,$nuevo_restante,$montoapagar,$costo_total_concepto, $comprobante = '', $estatus, $promocion, $retardo,$metodo_de_pago, $banco_de_deposito){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con["info"] == "ok"){
        $con = $con["conexion"];
        $sql = "INSERT INTO a_pagos (id_prospecto, id_concepto, detalle_pago, fechapago, montopagado,restante,costototal, comprobante, estatus, idPromocion,  cargo_retardo, metodo_de_pago, banco_de_deposito)
            VALUES (:id_prospecto, :id_concepto, :detalle_pago,:fecha_pago,:montopagado,:restante,:costototal, :comprobante, :estatus, :promocion, :cargo_retardo, :metodo_de_pago, :banco_de_deposito);";
                    
        $statement = $con->prepare($sql);
        $statement->bindParam(':id_prospecto', $id_prospecto);
        $statement->bindParam(':id_concepto', $id_concepto);
        $statement->bindParam(':detalle_pago', $json_detalle);
        $statement->bindParam(':fecha_pago', $fechapago);
        $statement->bindParam(':montopagado', $montoapagar);
        $statement->bindParam(':restante', $nuevo_restante);
        $statement->bindParam(':costototal', $costo_total_concepto);
        $statement->bindParam(':comprobante', $comprobante);
        $statement->bindParam(':estatus', $estatus);
        $statement->bindParam(':promocion', $promocion);
        $statement->bindParam(':cargo_retardo', $retardo);
        $statement->bindParam(':metodo_de_pago', $metodo_de_pago);
        $statement->bindParam(':banco_de_deposito', $banco_de_deposito);
        
        $statement->execute();
    
        if($statement->errorInfo()[0] == '00000'){
          $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
        }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
        }
      }
      $conexion = null;
      $con = null;
            
      return $response;
    }

    public function registrar_pago_card($id_prospecto,$id_concepto,$json_detalle,$fechapago,$nuevo_restante,$montoapagar,$costo_total_concepto, $comprobante = '', $estatus, $promocion, $retardo, $codigoauth, $ord_id, $metodo_de_pago){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con["info"] == "ok"){
        $con = $con["conexion"];
        $sql = "INSERT INTO a_pagos (id_prospecto, id_concepto, detalle_pago, fechapago, montopagado,restante,costototal, comprobante, estatus, idPromocion,  cargo_retardo,codigo_de_autorizacion,order_id,metodo_de_pago)
            VALUES (:id_prospecto, :id_concepto, :detalle_pago,:fecha_pago,:montopagado,:restante,:costototal, :comprobante, :estatus, :promocion, :cargo_retardo, :codigo_de_autorizacion, :order_id, :metodo_de_pago);";
                    
        $statement = $con->prepare($sql);
        $statement->bindParam(':id_prospecto', $id_prospecto);
        $statement->bindParam(':id_concepto', $id_concepto);
        $statement->bindParam(':detalle_pago', $json_detalle);
        $statement->bindParam(':fecha_pago', $fechapago);
        $statement->bindParam(':montopagado', $montoapagar);
        $statement->bindParam(':restante', $nuevo_restante);
        $statement->bindParam(':costototal', $costo_total_concepto);
        $statement->bindParam(':comprobante', $comprobante);
        $statement->bindParam(':estatus', $estatus);
        $statement->bindParam(':promocion', $promocion);
        $statement->bindParam(':cargo_retardo', $retardo);
        $statement->bindParam(':codigo_de_autorizacion', $codigoauth);
        $statement->bindParam(':order_id', $ord_id);
        $statement->bindParam(':metodo_de_pago', $metodo_de_pago);
        
        $statement->execute();
    
        if($statement->errorInfo()[0] == '00000'){
          $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
        }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
        }
      }
      $conexion = null;
      $con = null;
            
      return $response;
    }

    public function set_referencia($id_pago, $referencia){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con["info"] == "ok"){
        $con = $con["conexion"];
        $sql = "UPDATE a_pagos SET codigo_de_autorizacion = :referencia WHERE id_pago = :id_pago;";
                    
        $statement = $con->prepare($sql);
        $statement->bindParam(':id_pago', $id_pago);
        $statement->bindParam(':referencia', $referencia);
        
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

    public function actualizar_retardo($id_pago){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT *
              FROM a_pagos
              WHERE id_pago=:id_concepto";
    
      $statement = $con->prepare($sql);
      $statement->bindParam(':id_concepto', $id_pago);
      $statement->execute();
    
      if($statement->errorInfo()[0] == "00000"){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
        }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id_pago];
        }
        $conexion = null;
        $con = null;
    
        return $response;
      }
    }

    public function actualizar_saldo($id_pago){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
  
      if($con['info'] == 'ok'){
        $con = $con['conexion'];
        $sql = "UPDATE a_pagos SET cargo_retardo = 0 WHERE id_pago = :id_pago;"; 
              
          $statement = $con->prepare($sql);
          $statement->bindParam(':id_pago', $id_pago);
          $statement->execute();
      
          if($statement->errorInfo()[0] == '00000'){
            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
          }else{
            $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
          }
        $conexion = null;
        $con = null;
    
        return $response;
      }
    }

    public function registrar_pago_conekta($id_prospecto,$id_concepto,$json_detalle,$fechapago,$nuevo_restante,$montoapagar,$costo_total_concepto, $comprobante = '', $estatus, $promocion, $retardo, $referencia, $order_id){
      $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con["info"] == "ok"){
        $con = $con["conexion"];
        $sql = "INSERT INTO a_pagos (id_prospecto, id_concepto, detalle_pago, fechapago, montopagado,restante,costototal, comprobante, estatus, idPromocion,  cargo_retardo, referencia, order_id)
            VALUES (:id_prospecto, :id_concepto, :detalle_pago,:fecha_pago,:montopagado,:restante,:costototal, :comprobante, :estatus, :promocion, :cargo_retardo, :referencia, :order_id);";
                    
        $statement = $con->prepare($sql);
        $statement->bindParam(':id_prospecto', $id_prospecto);
        $statement->bindParam(':id_concepto', $id_concepto);
        $statement->bindParam(':detalle_pago', $json_detalle);
        $statement->bindParam(':fecha_pago', $fechapago);
        $statement->bindParam(':montopagado', $montoapagar);
        $statement->bindParam(':restante', $nuevo_restante);
        $statement->bindParam(':costototal', $costo_total_concepto);
        $statement->bindParam(':comprobante', $comprobante);
        $statement->bindParam(':estatus', $estatus);
        $statement->bindParam(':promocion', $promocion);
        $statement->bindParam(':cargo_retardo', $retardo);
        $statement->bindParam(':referencia', $referencia);
        $statement->bindParam(':order_id', $order_id);
        
        $statement->execute();
    
        if($statement->errorInfo()[0] == '00000'){
          $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
        }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
        }
      }
      $conexion = null;
      $con = null;
            
      return $response;
    }

    public function formato_pago($id_pago, $monto, $fecha, $nombres, $apellidoP, $apellidoM, $correo, $plan_pago){
      $nombre_completo = $nombres." ".$apellidoP." ".$apellidoM;           
  
      $infopago = array(
          'id' => $id_pago,
          'intent' => 'CAPTURE',
          'status' => 'COMPLETED',
          'purchase_units' => array(
              array(
                  'reference_id' => 'default',
                  'amount' => array(
                      'currency_code' => 'MXN',
                      'value' => $monto
                  ),
                  'payee' => array(
                      'email_address' => 'pagos@universidaddelconde.edu.mx',
                      'merchant_id' => 'AZUHGK3DWV9NC'
                  ),
                  'description' => $plan_pago,
                  'soft_descriptor' => 'PAYPAL *UNIVERSIDAD',
                  'shipping' => array(
                      'name' => array(
                          'full_name' => $nombre_completo
                      ),
                      'address' => array(
                          'address_line_1' => '',
                          'address_line_2' => '',
                          'admin_area_2' => '',
                          'admin_area_1' => '',
                          'postal_code' => '',
                          'country_code' => 'MX'
                      )
                  ),
                  'payments' => array(
                      'captures' => array(
                          array(
                              'id' => $id_pago,
                              'status' => 'COMPLETED',
                              'amount' => array(
                                  'currency_code' => 'MXN',
                                  'value' => $monto
                              ),
                              'final_capture' => true,
                              'seller_protection' => array(
                                  'status' => 'ELIGIBLE',
                                  'dispute_categories' => array(
                                      'ITEM_NOT_RECEIVED',
                                      'UNAUTHORIZED_TRANSACTION'
                                  )
                              ),
                              'create_time' => $fecha.'T16:36:52Z',
                              'update_time' => $fecha.'T16:36:52Z'
                          )
                      )
                  )
              )
          ),
          'payer' => array(
              'name' => array(
                  'given_name' => $nombres,
                  'surname' => $apellidoP
              ),
              'email_address' => $correo,
              'payer_id' => '-',
              'address' => array(
                  'country_code' => 'MX'
              )
          ),
          'create_time' => $fecha.'T16:36:52Z',
          'update_time' => $fecha.'T16:36:52Z'
      );
  
      return $infopago;
    }

  public function leer_pagos_anteriores($id_concepto,$id_prospecto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT MAX(fechapago) as fechaultimopago,MIN(restante)as restante
            FROM a_pagos
            WHERE id_prospecto=:id_prospecto AND id_concepto=:id_concepto AND estatus = 'verificado';";
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_prospecto', $id_prospecto);
    $statement->bindParam(':id_concepto', $id_concepto);
    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>[$id_concepto, $id_prospecto]];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_concepto($id_concepto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT pc.*, ag.idCarrera FROM pagos_conceptos pc
            LEFT JOIN a_generaciones ag ON ag.idGeneracion = pc.id_generacion
            WHERE pc.id_concepto=:id_concepto;"; 
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_concepto', $id_concepto);

    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id_concepto];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtenerfechalimitedepago($idGeneracion, $numerodepago){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT * 
            FROM fechas_ciclos 
            WHERE id_generacion=:id_generacion AND ciclo=:ciclo;"; 
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_generacion', $idGeneracion);
    $statement->bindParam(':ciclo', $numerodepago);

    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id_concepto];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_api_key($id_concepto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT pc.concepto, ac.idInstitucion, ai.nombre
            FROM pagos_conceptos as pc
            JOIN a_generaciones as ag on ag.idGeneracion=pc.id_generacion
            JOIN a_carreras as ac on ac.idCarrera=ag.idCarrera
            JOIN a_instituciones as ai on ai.id_institucion=ac.idInstitucion
            WHERE pc.id_concepto=:id_concepto"; 
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_concepto', $id_concepto);

    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $data1 = $statement->fetch(PDO::FETCH_ASSOC);
      if(!$data1){
        $data1 = $con->query("SELECT pc.concepto, ai.id_institucion as idInstitucion, ai.nombre FROM pagos_conceptos as pc JOIN a_instituciones as ai on ai.id_institucion=pc.institucion WHERE pc.id_concepto = ".$id_concepto)->fetch(PDO::FETCH_ASSOC);
      }
      $response = ["estatus"=>"ok", "data"=>$data1];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id_concepto];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_api_key_evento($id_concepto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT pc.concepto, ev.idInstitucion, inst.nombre
            FROM pagos_conceptos as pc
            JOIN planes_pagos as pp on pc.idPlan_pago = pp.idPlanPago
            JOIN ev_evento as ev on ev.idEvento = pp.idEvento
            JOIN a_instituciones as inst ON inst.id_institucion = ev.idInstitucion
            WHERE pc.id_concepto=:id_concepto"; 
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_concepto', $id_concepto);

    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id_concepto];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_modo_udc(){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT *
            FROM api_keys
            WHERE id=1";
  
    $statement = $con->prepare($sql);

    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$statement->errorInfo()];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_modo_conacon(){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT *
            FROM api_keys
            WHERE id=2";
  
    $statement = $con->prepare($sql);

    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$statement->errorInfo()];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function totalalumnosgeneracion($idGeneracion){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT * 
            FROM alumnos_generaciones 
            WHERE idgeneracion=:idgeneracion;"; 
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':idgeneracion', $idGeneracion);
    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$idGeneracion];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_tipo_carrera($id_carrera){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT tipo 
            FROM a_carreras
            WHERE idCarrera=:id_carrera";
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_carrera', $id_carrera);
    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id_carrera];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_pagos_anteriores($id_concepto,$id_prospecto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT MAX(fechapago) as fechaultimopago,MIN(restante)as restante
            FROM a_pagos
            WHERE id_prospecto=:id_prospecto AND id_concepto=:id_concepto;";
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_prospecto', $id_prospecto);
    $statement->bindParam(':id_concepto', $id_concepto);
    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>[$id_concepto, $id_prospecto]];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function listar_pagos_anteriores($id_concepto, $id_prospecto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT * FROM a_pagos
            WHERE id_prospecto=:id_prospecto AND id_concepto=:id_concepto AND estatus = 'verificado' ORDER by numero_de_pago, fecha_verificacion, id_pago ASC;"; // el order id es requerido para obtener cual es el ultimo pago aplicado. Existe otra funcion similar en planpagosModel.sql
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_prospecto', $id_prospecto);
    $statement->bindParam(':id_concepto', $id_concepto);
    $statement->execute();
  
    if($statement->errorInfo()[0] == '00000'){
      $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>[$id_concepto, $id_prospecto]];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function cargar_pagos_alumnos($estatus = 'verificado'){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT pags.*, con.concepto,con.numero_pagos, CONCAT(prosp.aPaterno,' ',prosp.aMaterno,' ',prosp.nombre) as nombre_alumno,con.id_generacion, con.categoria, crs.nombre as nombre_carrera, gens.secuencia_generacion as nombre_generacion FROM a_pagos pags 
            JOIN pagos_conceptos con ON pags.id_concepto = con.id_concepto
            JOIN a_prospectos prosp ON prosp.idAsistente = pags.id_prospecto
            LEFT JOIN a_generaciones gens ON gens.idGeneracion = con.id_generacion
            LEFT JOIN a_carreras crs ON crs.idCarrera = gens.idCarrera
            WHERE pags.estatus = :estatus ORDER BY pags.fecha_verificacion DESC ";
            if($estatus == 'pendiente'){
              $sql.=" ,`fechapago` ASC ";
            }
      if(in_array($estatus, $this->estatus_pagos)){
        $statement = $con->prepare($sql);
        $statement->bindParam(':estatus', $estatus);
        $statement->execute();
    
        if($statement->errorInfo()[0] == '00000'){
          $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
        }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
        }
      }else{
        $response = ["estatus"=>"error", "info"=>"Estatus de pago no valido"];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function cambiar_estatus_pago($id_pago, $estatus, $motivo = null, $session = 0){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $str_motivo = $motivo != null ? ', comentario = :comentario ' : '';
      $verif_fecha = '';
      if($estatus == 'verificado'){
        $verif_fecha = ', fecha_verificacion = NOW() ';
      }
      if($session > 0){
        $verif_fecha .= ", quien_verifico = ".$session." ";
      }
      $sql = "UPDATE a_pagos SET estatus = :estatus {$str_motivo} {$verif_fecha} WHERE id_pago = :id_pago;"; 
            
      if(in_array($estatus, $this->estatus_pagos)){
        $statement = $con->prepare($sql);
        $statement->bindParam(':estatus', $estatus);
        $statement->bindParam(':id_pago', $id_pago);
        if($motivo != null){
          $statement->bindParam(':comentario', $motivo);
        }
        $statement->execute();
    
        if($statement->errorInfo()[0] == '00000'){
          $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
        }else{
          $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
        }
        if($estatus == 'verificado'){
          $info_p = $this->obtener_informacion_pago_id($id_pago)['data'];
          $info_concep = $this->obtener_concepto($info_p['id_concepto']);
          if($info_concep['data']['categoria'] == 'Mensualidad'){
            require_once 'promocionesModel.php';
            $promoM = new Promociones();
            $promos_alumn = $promoM->obtenerPromocion_concepto_alumno($info_p['id_prospecto'], $info_p['id_concepto'])['data'];
            $registrar_siguiente = false;
            $id_promo = 0;
            foreach($promos_alumn as $prom){
              if(gettype($prom['Nopago']) == 'array'){
                if(in_array(intval($info_p['numero_de_pago'])+1 , $prom['Nopago']) && floatval($prom['porcentaje']) >= 100){
                  $registrar_siguiente = true;
                  $id_promo = $prom['idPromocion'];
                }
              }
            }
            /**
             * se registran en automatico los siguientes pagos que tengar promocion del 100%
             */
            if($registrar_siguiente){
              $num_p = intval($info_p['numero_de_pago'])+1;
              $pag_js = json_encode($this->formato_pago('BECA', '0', date("Y-m-d"), '', '', '', '', $info_concep['data']['categoria']));
    
              $insert = [
                'id_prospecto' => $info_p['id_prospecto'],  'id_concepto' => $info_p['id_concepto'],
                'detalle' => $pag_js,                       'montopagado' => 0,
                'cargo_retardo' => 0,                       'restante' => 0,
                'saldo' => 0,                               'costototal' => 0,
                'numero_de_pago' => $num_p,                 'fecha_limite_pago' => date('Y-m-d', strtotime('+1 month', strtotime($info_p['fecha_limite_pago']))),
                'fechapago' => date("Y-m-d"),               'comprobante' => '',
                'idPromocion' => $id_promo,          'estatus' => 'verificado',
                'como_realizo_pago' => '',                  'metodo_de_pago' => '',
                'banco_de_deposito' => '',                  'quien_registro' => $info_p['quien_registro'],
                'codigo_de_autorizacion' => '',             'referencia' => '',
                'order_id' => '', 'moneda' => $info_p['moneda']
              ];
              $this->registrar_pago_mult($insert);
            }
            /**
             * se actualizar el numero de pago de los siguientes pagos pendientes
             */
          }//else if($info_concep['data']['categoria'] == 'Inscripción'){ // <-------
            // verificar los pagos que pertenescan al mismo y que esten pendientes de verificar
            $consultar_pendientes = $this->obtener_pago_pendiente($info_p['id_prospecto'], $info_p['id_concepto']);
            if(sizeof($consultar_pendientes) > 0){
              // buscar el menor restante de los pagos verificados
              $minimo_restante = $this->encontrar_menor_restante($info_p['id_prospecto'], $info_p['id_concepto']);
              if($minimo_restante){
                // actualizar el restante de los pagos pendientes
                $actualizacion['estatus'] = '';

                if($info_concep['data']['categoria'] == 'Inscripción'){
                  $actualizacion = $this->actualizar_restante_pagos_pendientes($info_p['id_prospecto'], $info_p['id_concepto'], floatval($info_p['montopagado']), $info_p['restante'], $info_p['numero_de_pago']);
                }else if($info_concep['data']['categoria'] == 'Mensualidad'){
                  $this->actualizar_pagos_pendientes($info_p['id_pago']);
                }
              }
            }
          //} // <-------
        }
      }else{
        $response = ["estatus"=>"error", "info"=>"Estatus de pago no valido"];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  function encontrar_menor_restante($prospecto, $concepto){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
    $con = $con['conexion'];
		$query = $con->query("SELECT MIN(restante) as restante FROM a_pagos WHERE id_prospecto = $prospecto AND id_concepto = $concepto AND estatus = 'verificado' ;");
    if($query){
      $response = $query->fetch(PDO::FETCH_ASSOC);
    }
		return $response;
  }

  public function actualizar_restante_pagos_pendientes($prospecto, $concepto, $pagado, $nuevo_restante_aprobados, $numero_de_pago){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "UPDATE a_pagos SET restante = (restante - :pagado), numero_de_pago = :numero_de_pago  WHERE id_concepto = :concepto AND id_prospecto = :prospecto AND estatus = 'pendiente';
              "; 
            // UPDATE a_pagos SET restante = :nuevo_restante WHERE id_concepto = :concepto AND id_prospecto = :prospecto AND estatus = 'verificado';
      $statement = $con->prepare($sql);
      $statement->bindParam(':pagado', $pagado);
      $statement->bindParam(':concepto', $concepto);
      $statement->bindParam(':prospecto', $prospecto);
      $numero_de_pago ++;
      $statement->bindParam(':numero_de_pago', $numero_de_pago);
      // $statement->bindParam(':nuevo_restante', $nuevo_restante_aprobados);

      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function actualizar_pagos_pendientes_mensualidades($prospecto, $concepto, $numero_de_pago, $fecha_lim, $id_pago){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "UPDATE a_pagos SET numero_de_pago = :numero_de_pago, fecha_limite_pago = :fecha_lim WHERE id_pago = :pago;"; 

      $statement = $con->prepare($sql);
      $numero_de_pago ++;
      $fecha_lim = date("Y-m-d",strtotime('+1 month', strtotime($fecha_lim)));
      $statement->bindParam(':numero_de_pago', $numero_de_pago);
      $statement->bindParam(':fecha_lim', $fecha_lim);
      $statement->bindParam(':pago', $id_pago);
      // $statement->bindParam(':nuevo_restante', $nuevo_restante_aprobados);

      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function actualizar_pagos_parciales_pendientes_mensualidades($id_pago, $pago_saldo, $pago_restante, $cargo_retardo, $montopagado){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "UPDATE a_pagos SET saldo = :saldo, restante = :restante, cargo_retardo = :cargo_retardo, montopagado = :montopagado WHERE id_pago = :id_pago;
              ";
            // UPDATE a_pagos SET restante = :nuevo_restante WHERE id_concepto = :concepto AND id_prospecto = :prospecto AND estatus = 'verificado';
      $statement = $con->prepare($sql);
      $statement->bindParam(':saldo', $pago_saldo);
      $statement->bindParam(':restante', $pago_restante);
      $statement->bindParam(':id_pago', $id_pago);
      $statement->bindParam(':cargo_retardo', $cargo_retardo);
      $statement->bindParam(':montopagado', $montopagado);
      
      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_pagos_plan_alumno($prospecto, $plan_pagos){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT pags.*, con.categoria FROM a_pagos pags
        JOIN pagos_conceptos con ON pags.id_concepto = con.id_concepto
        WHERE con.idPlan_pago = :plan_pagos AND pags.id_prospecto = :prospecto;"; 
            
      $statement = $con->prepare($sql);
      $statement->bindParam(':plan_pagos', $plan_pagos);
      $statement->bindParam(':prospecto', $prospecto);
      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_pagos_generacion_alumno($prospecto, $generacion){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT pags.*, con.categoria FROM a_pagos pags
        JOIN pagos_conceptos con ON pags.id_concepto = con.id_concepto
        WHERE con.id_generacion = :generacion AND pags.id_prospecto = :prospecto;"; 
            
      $statement = $con->prepare($sql);
      $statement->bindParam(':generacion', $generacion);
      $statement->bindParam(':prospecto', $prospecto);
      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_conceptos_generacion($generacion, $idAsistente){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT con.*, curEx.idExamen, curEx.idCurso, cal.calificacion, mat.calificacion_min, con.concepto
            FROM pagos_conceptos con
            LEFT JOIN cursos_examen as curEx ON curEx.idExamen = con.idExamen
            LEFT JOIN calificaciones AS cal ON cal.idGeneracion = con.id_generacion and cal.idProspecto = :idAsistente AND cal.id_materia = curEx.idCurso
            LEFT JOIN materias AS mat ON mat.id_materia = cal.id_materia
            WHERE con.id_generacion = :generacion";
            
      $statement = $con->prepare($sql);
      $statement->bindParam(':generacion', $generacion);
      $statement->bindParam(':idAsistente', $idAsistente);
      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtener_informacion_pago_id($pago){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT *, con.categoria FROM a_pagos pag
        JOIN pagos_conceptos con ON pag.id_concepto = con.id_concepto
        WHERE pag.id_pago = :pago;"; 
            
      $statement = $con->prepare($sql);
      $statement->bindParam(':pago', $pago);
      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obtenerCarrerasGen(){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT * 
              FROM a_carreras
              WHERE estatus= 1;"; 
            
      $statement = $con->prepare($sql);
      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function buscarGeneraciones($idCarrera){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con['info'] == 'ok'){
      $con = $con['conexion'];
      $sql = "SELECT * 
              FROM a_generaciones
              WHERE idCarrera=:idCarrera AND estatus= 1;"; 
            
      $statement = $con->prepare($sql);
      $statement->bindParam(':idCarrera', $idCarrera);

      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function obteneralumnosgeneracionreporte($idGeneracion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT pags.*, con.concepto, CONCAT(prosp.aPaterno,' ',prosp.aMaterno,' ',prosp.nombre) as nombre_alumno 
      FROM a_pagos pags 
      JOIN pagos_conceptos con ON pags.id_concepto = con.id_concepto
      JOIN a_generaciones agen ON con.id_generacion = agen.idGeneracion
      JOIN a_prospectos prosp ON prosp.idAsistente = pags.id_prospecto
      WHERE agen.idGeneracion = :idGeneracion;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idGeneracion', $idGeneracion);

			$statement->execute();

			$conexion = null;
			$con = null;

			return $statement;
			
		}
	}

  public function obtener_alumnos_totales_carrera($idCarrera){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT ap.idAsistente,concat(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno) as nombre_completo,ag.nombre as nombre_generacion,pc.numero_pagos,sum(pags.montopagado) as total_pagado,pags.cargo_retardo, pags.restante , pags.saldo, SUM(pc.numero_pagos*pags.costototal) as costo_total 
              from a_pagos as pags 
              JOIN a_prospectos as ap on ap.idAsistente=pags.id_prospecto 
              join pagos_conceptos as pc on pc.id_concepto=pags.id_concepto 
              join a_generaciones as ag on ag.idGeneracion=pc.id_generacion 
              join a_carreras as ac on ac.idCarrera=ag.idCarrera 
              where ac.idCarrera=:idCarrera and pags.estatus='verificado' 
              GROUP by ap.idAsistente";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idCarrera', $idCarrera);

			$statement->execute();

			$conexion = null;
			$con = null;

			return $statement;
			
		}
	}

  public function obteneralumnosgeneracionnotificarpago($idGeneracion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT ap.idAsistente,concat(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno) as nombre_completo, ap.correo, ap.telefono, ac.idCarrera, ac.idInstitucion, ag.nombre as nombre_generacion, ac.nombre as nombre_carrera
      FROM a_generaciones as ag 
      JOIN a_carreras as ac on ac.idCarrera=ag.idCarrera
      JOIN alumnos_generaciones as alug on alug.idgeneracion=ag.idGeneracion 
      JOIN a_prospectos as ap on ap.idAsistente=alug.idalumno WHERE ag.idGeneracion = :idGeneracion";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idGeneracion', $idGeneracion);

			$statement->execute();

			$conexion = null;
			$con = null;

			return $statement;
			
		}
	}

  public function buscar_alumno_generacion($nombre_alumn){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT ap.idAsistente,concat(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno) as nombre_completo, ap.correo, ap.telefono, ac.idCarrera, ac.idInstitucion, ag.nombre as nombre_generacion, ac.nombre as nombre_carrera
      FROM a_generaciones as ag 
      JOIN a_carreras as ac on ac.idCarrera=ag.idCarrera
      JOIN alumnos_generaciones as alug on alug.idgeneracion=ag.idGeneracion 
      JOIN a_prospectos as ap on ap.idAsistente=alug.idalumno WHERE concat(ap.nombre,ap.aPaterno,ap.aMaterno,ap.correo) LIKE '%".$nombre_alumn."%' OR concat(ap.aPaterno,ap.aMaterno,ap.nombre,ap.correo) LIKE '%".$nombre_alumn."%' OR concat(ap.aMaterno,ap.aPaterno,ap.nombre,ap.correo) LIKE '%".$nombre_alumn."%';";

			$statement = $con->prepare($sql);

			$statement->execute();

			$conexion = null;
			$con = null;

			return $statement;
			
		}
	}

  public function obtener_historial_pago($alumno){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $pagos = $con['conexion']->query("SELECT pags.*, con.concepto as concepto_nombre, con.precio as precio_orig,con.precio_usd , con.numero_pagos as concepto_numero_pagos, con.parcialidades as concepto_parcialidades, con.categoria as concepto_categoria, con.id_generacion, con.idPlan_pago, con.generales 
      FROM a_pagos pags 
      JOIN pagos_conceptos con ON pags.id_concepto = con.id_concepto
      WHERE pags.id_prospecto = $alumno")->fetchAll(PDO::FETCH_ASSOC);

    foreach($pagos as $key => $pago){
      $obtenerdatosprospecto = $this->obtenerdatosprospecto($alumno)['data'];
      if ($obtenerdatosprospecto['tipoPago']==2) {//si el prospecto tiene asignado pago en dolares
        $pagos[$key]['precio_orig'] = $pago['precio_usd'];
        $pagos[$key]['tipomoneda'] = ' USD';
      }
      if ($obtenerdatosprospecto['tipoPago']==1) {
        $pagos[$key]['tipomoneda'] = ' MXN';
      }
      if($pago['concepto_categoria'] == 'Mensualidad'){
        $pagos[$key]['numero_pago_actual'] = $con['conexion']->query("SELECT * FROM a_pagos WHERE id_prospecto = $alumno AND id_concepto = ".$pago['id_concepto']." AND id_pago < ".$pago['id_pago']." AND estatus = 'verificado'")->rowCount();
        $max_numero_actual = $con['conexion']->query("SELECT MAX(numero_de_pago) as max_numero_actual FROM a_pagos WHERE id_prospecto = $alumno AND id_concepto = ".$pago['id_concepto']." AND estatus = 'verificado' ")->fetch();
        $pagos[$key]['max_aplicado'] = ($max_numero_actual) ? $max_numero_actual['max_numero_actual'] : 0;
      }
      if(($pago['idPlan_pago'] == null || $pago['idPlan_pago'] == 0) && ($pago['id_generacion'] != null && $pago['id_generacion'] != 0)){
        $pagos[$key]['carrera_id'] = $con['conexion']->query("SELECT idCarrera FROM a_generaciones WHERE idGeneracion = ".$pago['id_generacion'])->fetch(PDO::FETCH_ASSOC)['idCarrera'];
      }else if(($pago['id_generacion'] == null || $pago['id_generacion'] == 0) && ($pago['idPlan_pago'] != null && $pago['idPlan_pago'] != 0)){
        $pagos[$key]['carrera_id'] = $con['conexion']->query("SELECT idCarrera FROM planes_pagos WHERE idPlanPago = ".$pago['idPlan_pago'])->fetch(PDO::FETCH_ASSOC)['idCarrera'];
      }
      if($pago['idPromocion'] != null){
        $pagos[$key]['promocion_info'] = $con['conexion']->query("SELECT * FROM promociones WHERE idPromocion = ".$pago['idPromocion'])->fetch(PDO::FETCH_ASSOC);
      }
    }
    return $pagos;
  }

  function consultar_info_prospecto_a($prospecto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con["info"] == "ok"){
      $con = $con["conexion"];

      $sql = "SELECT prosp.*, prosp.idAsistente as id_prospecto, prosp.aPaterno as apaterno, prosp.aMaterno as amaterno, prosp.correo as email, prosp.telefono as celular
        FROM a_prospectos prosp
        WHERE prosp.idAsistente = :prospecto;";
      
      $statement = $con->prepare($sql);

      $statement->bindParam(":prospecto", $prospecto, PDO::PARAM_INT);
      $statement->execute();


      if($statement->errorInfo()[0] == 00000){
        $datos = $statement->fetch(PDO::FETCH_ASSOC);
        if($datos){
          unset($datos['contrasenia']);
        }
        $response = ["estatus"=>"ok", "data"=>$datos];
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

  public function obtener_saldo_a_favor_alumno($alumno){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $pagos = $con['conexion']->query("SELECT SUM(restante) as saldo_favor FROM `a_pagos` WHERE restante < 0 AND id_prospecto = {$alumno} AND estatus = 'verificado';")->fetch(PDO::FETCH_ASSOC);
    return $pagos;
  }
  
  public function obtener_datos_prospecto($idAsistente){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT concat(aPaterno,' ',aMaterno,' ',nombre) as nombre_completo, idAsistente, correo, telefono,nombre, aPaterno, aMaterno, referencia
      FROM a_prospectos
      WHERE idAsistente = :idAsistente;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idAsistente', $idAsistente);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}

  }

  public function solicitar_prorroga($id_prospecto,$id_concepto,$descripcion,$nueva_fecha_prorroga, $fecha_solicitud, $numero_de_pago, $fecha_limite_pago){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    $estatus='pendiente';
    if($con["info"] == "ok"){
      $con = $con["conexion"];
      $sql = "INSERT INTO prorrogas (idAsistente, id_concepto, estatus, nuevafechalimitedepago, descripcion, fechacreado,  numero_de_pago, fechalimitepago)
          VALUES (:idAsistente, :id_concepto, :estatus,:nuevafechalimitedepago,:descripcion,:fechacreado, :numero_de_pago, :fechalimitepago);";
                  
      $statement = $con->prepare($sql);
      $statement->bindParam(':idAsistente', $id_prospecto);
      $statement->bindParam(':id_concepto', $id_concepto);
      $statement->bindParam(':estatus', $estatus);
      $statement->bindParam(':nuevafechalimitedepago', $nueva_fecha_prorroga);
      $statement->bindParam(':descripcion', $descripcion);
      $statement->bindParam(':fechacreado', $fecha_solicitud);
      $statement->bindParam(':numero_de_pago', $numero_de_pago);
      $statement->bindParam(':fechalimitepago', $fecha_limite_pago);
      
      $statement->execute();
  
      if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
    }
    $conexion = null;
    $con = null;
          
    return $response;
  }

  public function obtener_informacion_registro_pago($registro){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    if($con["info"] == "ok"){
      $con = $con["conexion"];
      $sql = "SELECT pags.*, con.* FROM a_pagos pags 
            JOIN pagos_conceptos con ON pags.id_concepto = con.id_concepto
            JOIN a_prospectos prosp ON prosp.idAsistente = pags.id_prospecto
            WHERE pags.id_pago = :id_pago;";
      $statement = $con->prepare($sql);
      $statement->bindParam(':id_pago', $registro);
      $statement->execute();
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

  function aplicar_promesa($id_pago){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    if($con["info"] == "ok"){
      $con = $con["conexion"];
      $sql = "UPDATE a_pagos SET promesa_de_pago = 1 WHERE id_pago = :id_pago;";
      $statement = $con->prepare($sql);
      $statement->bindParam(':id_pago', $id_pago);
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

  function modificar_pago($id_pago,$nuevafechadepago,$como_realizo_pago,$metododepago,$banco_de_deposito,$modificadopor, $nueva_fecha){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    if($con["info"] == "ok"){
      $con = $con["conexion"];

        $sql = "UPDATE a_pagos
        SET fechapago =:fechamodificado,modificado_por =:modificado_por,como_realizo_pago=:como_realizo_pago,metodo_de_pago=:metodo_de_pago,banco_de_deposito=:banco_de_deposito,saldo=0, fecha_limite_pago = :nueva_fecha
        WHERE id_pago = :id_pago;";
      
      
      $statement = $con->prepare($sql);
      $statement->bindParam(':id_pago', $id_pago);
      $statement->bindParam(':fechamodificado', $nuevafechadepago);
      $statement->bindParam(':como_realizo_pago', $como_realizo_pago);
      $statement->bindParam(':metodo_de_pago', $metododepago);
      $statement->bindParam(':modificado_por', $modificadopor);
      $statement->bindParam(':banco_de_deposito', $banco_de_deposito);
      $statement->bindParam(':nueva_fecha', $nueva_fecha);
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

  function modificar_pago_normal($id_pago,$nuevafechadepago,$como_realizo_pago,$metodo_de_pago,$banco_de_deposito,$modificadopor){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    if($con["info"] == "ok"){
      $con = $con["conexion"];

        $sql = "UPDATE a_pagos
        SET fechapago =:fechamodificado,modificado_por =:modificado_por,como_realizo_pago=:como_realizo_pago,metodo_de_pago=:metodo_de_pago,banco_de_deposito=:banco_de_deposito
        WHERE id_pago = :id_pago;";
      
      
      $statement = $con->prepare($sql);
      $statement->bindParam(':id_pago', $id_pago);
      $statement->bindParam(':fechamodificado', $nuevafechadepago);
      $statement->bindParam(':como_realizo_pago', $como_realizo_pago);
      $statement->bindParam(':metodo_de_pago', $metodo_de_pago);
      $statement->bindParam(':modificado_por', $modificadopor);
      $statement->bindParam(':banco_de_deposito', $banco_de_deposito);
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

  function agregarsaldopendiente($id_pago,$nuevafechadepago,$saldo,$modificadopor){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    if($con["info"] == "ok"){
      $con = $con["conexion"];

        $sql = "UPDATE a_pagos
        SET saldo=:saldo,fechapago=:fechapago,modificado_por = :modificado_por
        WHERE id_pago = :id_pago;";
      
      
      $statement = $con->prepare($sql);
      $statement->bindParam(':id_pago', $id_pago);
      $statement->bindParam(':fechapago', $nuevafechadepago);
      $statement->bindParam(':modificado_por', $modificadopor);
      $statement->bindParam(':saldo', $saldo);
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

  public function verificar_prorroga($id_concepto, $id_prospecto, $numero_de_pago){
    $estatus='aprobado';
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    if($con["info"] == "ok"){
      $con = $con["conexion"];
      $sql = "SELECT *
              FROM prorrogas
              WHERE idAsistente=:idAsistente AND id_concepto=:id_concepto AND numero_de_pago=:numero_de_pago AND estatus=:estatus;";
      $statement = $con->prepare($sql);

      $statement->bindParam(':idAsistente', $id_prospecto);
      $statement->bindParam(':id_concepto', $id_concepto);
      $statement->bindParam(':numero_de_pago', $numero_de_pago);
      $statement->bindParam(':estatus', $estatus);
      
      $statement->execute();

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

  public function validar_si_existe_prorroga($id_prospecto, $id_concepto, $numero_de_pago){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    if($con["info"] == "ok"){
      $con = $con["conexion"];
      $sql = "SELECT *
              FROM prorrogas
              WHERE idAsistente=:idAsistente AND id_concepto=:id_concepto AND numero_de_pago=:numero_de_pago";
      $statement = $con->prepare($sql);

      $statement->bindParam(':idAsistente', $id_prospecto);
      $statement->bindParam(':id_concepto', $id_concepto);
      $statement->bindParam(':numero_de_pago', $numero_de_pago);
      
      $statement->execute();

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

  public function insertar_codigo_auth($order_id, $codigo_auth){
    $conexion = new Conexion();
      $con = $conexion->conectar();
      $response = [];
    
      if($con["info"] == "ok"){
        $con = $con["conexion"];
        $sql = "UPDATE a_pagos 
                SET codigo_de_autorizacion = :codigo_de_autorizacion 
                WHERE order_id = :order_id;";
                    
        $statement = $con->prepare($sql);
        $statement->bindParam(':codigo_de_autorizacion', $codigo_auth);
        $statement->bindParam(':order_id', $order_id);
        
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

  public function obtener_datos_prospecto_by_order_id($order_id){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT concat(ap.aPaterno,' ',ap.aMaterno,' ',ap.nombre) as nombre_completo, ap.idAsistente, ap.correo, ap.telefono,ap.nombre, ap.aPaterno, ap.aMaterno
              FROM a_prospectos as ap
              JOIN a_pagos as apa on apa.id_prospecto=ap.idAsistente
              WHERE apa.order_id = :order_id;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':order_id', $order_id);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }

  public function actualizar_saldo_pendiente($registro_pago, $saldo){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];
			$sql = "UPDATE a_pagos SET saldo = :saldo WHERE id_pago = :regostro;";
			$statement = $con->prepare($sql);
      $statement->bindParam(':saldo', $saldo);
      $statement->bindParam(':regostro', $registro_pago);
			$statement->execute();
			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      $conexion = null;
      $con = null;
      return $response;
		}
  }

  public function obtenerTotalesCarreras($idAsistente){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT totales.nombrecarrera, SUM(totales.costototal) as costototal, SUM(totales.resta) as restante 
                FROM (SELECT ag.idalumno, ag.idgeneracion, ac.nombre as nombrecarrera,pc.id_concepto,pc.precio,pc.numero_pagos,(pc.precio*pc.numero_pagos) as costototal,COALESCE((SELECT MIN(restante) 
                      FROM a_pagos 
                      WHERE id_prospecto=:idAsistente AND id_concepto=pc.id_concepto AND estatus = 'verificado'),0) as resta 
                      FROM alumnos_generaciones as ag JOIN a_generaciones as agen on agen.idGeneracion=ag.idgeneracion 
                      JOIN a_carreras as ac on ac.idCarrera=agen.idCarrera 
                      JOIN pagos_conceptos as pc on pc.id_generacion=agen.idGeneracion 
                      WHERE ag.idalumno=:idAsistente) AS totales 
              GROUP BY totales.idgeneracion;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idAsistente', $idAsistente);
			$statement->execute();
			}
		$conexion = null;
		$con = null;
		return $statement;
	}

  public function obtenerdatosprospecto($idProspecto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];

    if($con["info"] == "ok"){
      $con = $con["conexion"];

      $sql = "SELECT *
          FROM a_prospectos
          WHERE idAsistente=:idProspecto;";
      
      $statement = $con->prepare($sql);

      $statement->bindParam(":idProspecto", $idProspecto, PDO::PARAM_INT);
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

  function obtener_pago_aplicar($prospecto, $concepto, $fecha_pago){
    require_once 'promocionesModel.php';
    if (!class_exists('Generaciones')) {
      require_once 'generacionesModel.php';
    }
    $promoM = new Promociones();
    $generacionesM = new Generaciones();
    $pago_req = [
      'monto_por_pagar'    => 0,
      'fecha_limite_pago'  => "",
      'monto_retardo'      => 0,
      'numero_de_pago'     => 0,
      'monto_promocion'    => 0,
      'porcentaje_promocion' => 0,
      'monto_total_concepto' => 0,
      'id_promocion'       => null
    ];
    $promo_info = null;

    if(strlen($fecha_pago) > 10){
      $fecha_pago = substr($fecha_pago, 0, 10);
    }

    $concepto_pago = $this->obtener_concepto($concepto)['data'];
    $obtenerdatosprospecto = $this->obtenerdatosprospecto($prospecto)['data'];
    $pago_req['monto_por_pagar'] = $concepto_pago["precio"]; // <----------------
    if ($obtenerdatosprospecto['tipoPago']==2) {
      $pago_req['monto_por_pagar'] = $concepto_pago['precio_usd'];
    }

    $compare = strtotime(substr($fecha_pago,0,10));
    #
    $info_pago_anterior = $this->listar_pagos_anteriores($concepto,$prospecto);
    if(!empty($info_pago_anterior['data'])){
      $info_pago_anterior['data'] = $info_pago_anterior['data'][sizeof($info_pago_anterior['data']) - 1];
      if(floatval($info_pago_anterior['data']['saldo']) > 0 || floatval($info_pago_anterior['data']['restante']) > 0){
          $pago_req['numero_de_pago'] = $info_pago_anterior['data']['numero_de_pago'];
          if(floatval($info_pago_anterior['data']['restante']) > 0){
              $pago_req['band_saldo_pendiente'] = true;
          }
          if($concepto_pago['parcialidades'] == 1){
            $pago_req['numero_de_pago'] = $info_pago_anterior['data']['numero_de_pago'] + 1;
          }
      }else{
          if($concepto_pago['numero_pagos'] > 1){
              $pago_req['numero_de_pago'] = $info_pago_anterior['data']['numero_de_pago'] + 1;
          }
      }
    }else{
        $pago_req['numero_de_pago'] = 1;
    }
    #
    /** Consultar y filtrar informacion de promociones */
      // consultar promociones del alumno
    $promo_info = $promoM->validar_promo_exist($concepto, null, $prospecto);
    $info_fin = false;
    if(sizeof($promo_info['data']) > 0){
      foreach ($promo_info['data'] as $promo_key => $promo_value){
        # 1 validar el numero de pago para la promocion
        if(gettype($promo_value['Nopago']) == 'array' && in_array($pago_req['numero_de_pago'], $promo_value['Nopago'])){
          $info_fin = $promo_value;
        }
      }
      if($info_fin === false){
        foreach ($promo_info['data'] as $promo_key => $promo_value){
          # 2 validar la fecha de la promocion
          if($promo_value['fechainicio'] !== null && $promo_value['fechafin'] !== null ){
            if(strtotime($promo_value['fechainicio']) <= $compare && strtotime($promo_value['fechafin']) >= $compare){
              $info_fin = $promo_value;
            }else{
              unset($promo_info['data'][$promo_key]);
            }
          }
        }
      }
      $promo_info['data'] = array_values($promo_info['data']);
    }
    if($info_fin === false && $concepto_pago['id_generacion'] != 0){
      $promo_info = $promoM->validar_promo_exist($concepto, $concepto_pago['id_generacion'], null);
      $info_fin = false;
      if(sizeof($promo_info['data']) > 0){
        foreach ($promo_info['data'] as $promo_key => $promo_value){
          # 1 validar el numero de pago para la promocion
          if(gettype($promo_value['Nopago']) == 'array' && in_array($pago_req['numero_de_pago'], $promo_value['Nopago'])){
            $info_fin = $promo_value;
          }
        }
        if($info_fin === false){
          foreach ($promo_info['data'] as $promo_key => $promo_value){
            # 2 validar la fecha de la promocion
            if($promo_value['fechainicio'] !== null && $promo_value['fechafin'] !== null ){
              if(strtotime($promo_value['fechainicio']) <= $compare && strtotime($promo_value['fechafin']) >= $compare){
                $info_fin = $promo_value;
              }else{
                unset($promo_info['data'][$promo_key]);
              }
            }
          }
        }
        $promo_info['data'] = array_values($promo_info['data']);
      }
    }
    
    if($info_fin !== false){
      $promo_info['data'] = [$info_fin];
    }else{
      $promo_info['data'] = [];
    }
    $calcular_retardo = false;
    if($concepto_pago['categoria'] == 'Mensualidad'){
      $calcular_retardo = true;
    }
    // if(empty($promo_info['data']) && $concepto_pago['id_generacion'] != 0){
    //   $promo_info = $promoM->validar_promo_exist($concepto, $concepto_pago['id_generacion'], null);
    //   if(sizeof($promo_info['data']) > 0){
    //     foreach ($promo_info['data'] as $promo_key => $promo_value) {
    //       if(strtotime($promo_value['fechainicio']) <= $compare && strtotime($promo_value['fechafin']) >= $compare){
    //       }else{
    //         unset($promo_info['data'][$promo_key]);
    //       }
    //     }
    //     $promo_info['data'] = array_values($promo_info['data']);
    //   }
    // }
    /** Consultar y filtrar informacion de promociones */
    if(!empty($promo_info['data'])){
      $promo_info['data'] = $promo_info['data'][0];
      // if(strtotime($fecha_pago) >= strtotime($promo_info['data']['fechainicio']) && strtotime($fecha_pago) <= strtotime($promo_info['data']['fechafin'])){
        $porcen = floatval($promo_info['data']['porcentaje']);
        $pago_req['id_promocion'] = $promo_info['data']['idPromocion'];
        $pago_req['monto_promocion'] = $pago_req['monto_por_pagar'] * ($porcen / 100);
        $pago_req['monto_total_concepto'] = $pago_req['monto_por_pagar'] - ($pago_req['monto_por_pagar'] * ($porcen / 100));
        $pago_req['monto_por_pagar'] = $pago_req['monto_por_pagar'] - ($pago_req['monto_por_pagar'] * ($porcen / 100));
        $pago_req['porcentaje_promocion'] = $porcen;
      // }
    }else{
      $pago_req['monto_total_concepto'] = $pago_req['monto_por_pagar'];
    }
    
    if(!empty($info_pago_anterior['data'])){
      // $info_pago_anterior['data'] = $info_pago_anterior['data'][sizeof($info_pago_anterior['data'])-1];
      if(floatval($info_pago_anterior['data']['saldo']) > 0 || floatval($info_pago_anterior['data']['restante']) > 0){
        // $pago_req['numero_de_pago'] = $info_pago_anterior['data']['numero_de_pago'];
        $pago_req['monto_retardo'] = $info_pago_anterior['data']['saldo'];
        $pago_req['monto_por_pagar'] = $info_pago_anterior['data']['restante'];
        $pago_req['fecha_limite_pago'] = $info_pago_anterior['data']['fecha_limite_pago'];
        if(floatval($info_pago_anterior['data']['restante']) > 0){
          $pago_req['band_saldo_pendiente'] = true;
        }
        // $pago_req['monto_promocion'] = 0;
      }else{
        $ultimo_pago = false;
        if($concepto_pago['numero_pagos'] > 1){
          // $pago_req['numero_de_pago'] = $info_pago_anterior['data']['numero_de_pago'] + 1;
          if($info_pago_anterior['data']['numero_de_pago'] >= $concepto_pago['numero_pagos'] && $info_pago_anterior['data']['restante'] == 0 && $info_pago_anterior['data']['saldo'] == 0){
            $ultimo_pago = true;
            $pago_req['monto_por_pagar'] = 0;
          }
        }else{
          if($info_pago_anterior['data']['restante'] == 0 && $info_pago_anterior['data']['saldo'] == 0){
            $ultimo_pago = true;
            $pago_req['monto_por_pagar'] = 0;
          }
        }
        // validar prorroga de conceptos de titulacion y mensualidad
        $pago_req['fecha_limite_pago'] = $info_pago_anterior['data']['fecha_limite_pago'];
        if($concepto_pago['categoria'] == 'Mensualidad' || $concepto_pago['categoria'] == 'Titulación'){
          $consultar_p = $this->validar_si_existe_prorroga($prospecto, $concepto, $pago_req['numero_de_pago']);
          if($consultar_p['estatus'] == 'ok' && $consultar_p['data']){
              // si el estatus de la prorroga es aprobado sobreescribe la fecha
              if($consultar_p['data']['estatus'] == 'aprobado'){
                $pago_req['fecha_limite_pago'] = $consultar_p['data']['nuevafechalimitedepago'];
              }
          }
        }
        if(strtotime($pago_req['fecha_limite_pago']) < strtotime($fecha_pago) && $calcular_retardo){
          if($concepto_pago['idCarrera'] != 13){
            $pago_req['monto_retardo'] = ($pago_req['monto_por_pagar'] * ($this->porcentaje_recargo / 100));
          }
        }
        if($info_pago_anterior['data']['restante'] < 0){
          $pago_req['monto_por_pagar'] = $pago_req['monto_por_pagar'] + $info_pago_anterior['data']['restante'];
        }
      }
    }else{ // no hay saldo pendiente ni recargo pendiente
      $generacion_info = [];
      // $pago_req['numero_de_pago'] = 1;
      // setear la fecha limite de pago para el primer pago de mensaulidad
      if($concepto_pago['categoria'] == "Mensualidad"){
        $generacion_info = $generacionesM->buscarGeneracion($concepto_pago['id_generacion'])['data'];
        // obtener la fecha (dia) del plan de pago
        $fecha_lim = $concepto_pago['fechalimitepago'];
        // componer la fecha de acuerdo al inicio de la generacion
        $fecha_lim = substr($generacion_info['fecha_inicio'], 0, 8).explode('-', $fecha_lim)[2];
        // si la fecha limite de pago es menor a la fecha de inicio de la generacion, se aumenta un mes
        if(strtotime($fecha_lim) < strtotime($generacion_info['fecha_inicio'])){
            $fecha_lim = date('Y-m-d', strtotime('+1 month', strtotime($fecha_lim)));
        }
        $asign_gen = $generacionesM->buscarAsignacion($prospecto, $generacion_info['idGeneracion']);
        if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
          if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
            $fecha_lim = $asign_gen['data'][0]['fecha_primer_colegiatura'];
          }
        }
        // si la fecha limite de pago es a menos de 10 dias de inicio de la generacion se agregaran otros 10 dias
        $now = strtotime($generacion_info['fecha_inicio']); // or your date as well
        $your_date = strtotime($fecha_lim);
        $datediff = $your_date - $now;
        $diays_dif = round($datediff / (60 * 60 * 24));
        //if($diays_dif < 10){
        //  $fecha_lim = date('Y-m-d', strtotime('+10 day', strtotime($fecha_lim)));
        //}
        $pago_req['fecha_limite_pago'] = $fecha_lim;
        if($concepto_pago['categoria'] == 'Mensualidad' || $concepto_pago['categoria'] == 'Titulación'){
          $consultar_p = $this->validar_si_existe_prorroga($prospecto, $concepto, $pago_req['numero_de_pago']);
          if($consultar_p['estatus'] == 'ok' && $consultar_p['data']){
              // si el estatus de la prorroga es aprobado sobreescribe la fecha
              if($consultar_p['data']['estatus'] == 'aprobado'){
                $pago_req['fecha_limite_pago'] = $consultar_p['data']['nuevafechaaceptada'];
              }
          }
        }
        if(strtotime($pago_req['fecha_limite_pago']) < strtotime($fecha_pago) && $calcular_retardo){// Verificar que no sea tsu
          if($concepto_pago['idCarrera'] != 13){
            $pago_req['monto_retardo'] = ($pago_req['monto_por_pagar'] * ($this->porcentaje_recargo / 100));
          }
        }
      }else{
        $pago_req['fecha_limite_pago'] = $concepto_pago['fechalimitepago'];
      }
    }
    $pago_req['monto_por_pagar'] = round($pago_req['monto_por_pagar'], 2);
    if(isset($pago_req['monto_promocion'])){$pago_req['monto_promocion'] = round($pago_req['monto_promocion'], 2);};
    if(isset($pago_req['monto_retardo'])){$pago_req['monto_retardo'] = round($pago_req['monto_retardo'], 2);};
    if(isset($pago_req['monto_total_concepto'])){$pago_req['monto_total_concepto'] = round($pago_req['monto_total_concepto'], 2);};
    return $pago_req;
  }

  function registrar_pago_mult($data){
    // print_r($data);
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
    if(!isset($data['order_id'])){
      $data['order_id'] = null;
    }
    if(!isset($data['referencia'])){
      $data['referencia'] = null;
    }
	if(!isset($data['como_realizo_pago'])){
      $data['como_realizo_pago'] = null;
    }
		if($con['info'] == 'ok'){
			$con = $con['conexion'];
      
      $data['montopagado'] = round(floatval($data['montopagado']), 2);
      $data['cargo_retardo'] = round(floatval($data['cargo_retardo']), 2);
      $data['restante'] = round(floatval($data['restante']), 2);
      $data['saldo'] = round(floatval($data['saldo']), 2);
      $data['costototal'] = round(floatval($data['costototal']), 2);
      $sting_verif = '';
      $param_verif = '';
      if($data['estatus'] == 'verificado'){
        $sting_verif = ', fecha_verificacion';
        $param_verif = ', NOW()';
        if(strpos($data['detalle'], 'Cobranza') !== false){
          $sting_verif .= ', quien_verifico';
          $param_verif .= ', :quien_registro';
        }
      }
      $string_comentario_cc = '';
      $param_comentario_cc = '';
      if(isset($data['comentario_callcenter'])){
        $string_comentario_cc = ', comentario_callcenter';
        $param_comentario_cc = ', :comentario_callcenter';
      }
      // consultar prorroga para el numero de pago
      $info_pror = $this->verificar_prorroga($data['id_concepto'], $data['id_prospecto'], $data['numero_de_pago']);
      if($info_pror['estatus'] == 'ok' && $info_pror['data']){
        $total = floatval($data['restante']) + floatval($data['saldo']);
        $fecha_prorroga = $info_pror['data']['fechalimitepago'];
        if($total > 1){
          $data['fecha_limite_pago'] = $fecha_prorroga;
        }else{
          $data['fecha_limite_pago'] = date('Y-m-d', strtotime('+1 month', strtotime($fecha_prorroga)));
        }
      }
			$sql = "INSERT INTO a_pagos (id_prospecto, id_concepto, detalle_pago, montopagado, cargo_retardo, restante, saldo, costototal, numero_de_pago, fecha_limite_pago, fechapago, comprobante, idPromocion, estatus, como_realizo_pago, metodo_de_pago, banco_de_deposito, quien_registro, codigo_de_autorizacion, referencia, order_id, moneda {$sting_verif} {$string_comentario_cc}) VALUES 
                (:id_prospecto, :id_concepto, :detalle, :montopagado, :cargo_retardo, :restante, :saldo, :costototal, :numero_de_pago, :fecha_limite_pago, :fechapago, :comprobante, :idPromocion, :estatus,:como_realizo_pago, :metodo_de_pago, :banco_de_deposito, :quien_registro, :codigo_de_autorizacion, :referencia, :order_id, :moneda {$param_verif} {$param_comentario_cc});";
      
      $statement = $con->prepare($sql);

			$statement->execute($data);
      $response = $con->lastInsertId();
      /**
       * Verificar si el siguiente pago esta becado al 100 y si este pago se esta registrando directamente como aprobado
       * Si es asi se registraran los siguientes pagos consecutivos
       * ESTO MISMO SE TENDRA QUE HACER CUANDO SE VERIFIQUE UN PAGO PENDIENTE Y CUANDO SE LA MISMA PROMOCION Y AGREGUE EL SIGUIENTE UN NUMERO DE MENSUALIDAD MAS A LA PROMOCION
       */
      $info_concep = $this->obtener_concepto($data['id_concepto']);
      if($info_concep['data']['categoria'] == 'Mensualidad' && $data['estatus'] == 'verificado'){
        require_once 'promocionesModel.php';
        $promoM = new Promociones();
        $promos_alumn = $promoM->obtenerPromocion_concepto_alumno($data['id_prospecto'], $data['id_concepto'])['data'];
        $registrar_siguiente = false;
        $id_promo = 0;
        foreach($promos_alumn as $prom){
          if(gettype($prom['Nopago']) == 'array'){
            if(in_array(intval($data['numero_de_pago'])+1 , $prom['Nopago']) && floatval($prom['porcentaje']) >= 100){
              $registrar_siguiente = true;
              $id_promo = $prom['idPromocion'];
            }
          }
        }
        if($registrar_siguiente){
          $num_p = intval($data['numero_de_pago'])+1;
          $pag_js = json_encode($this->formato_pago('BECA', '0', date("Y-m-d"), '', '', '', '', $info_concep['data']['categoria']));

          $insert = [
            'id_prospecto' => $data['id_prospecto'],  'id_concepto' => $data['id_concepto'],
            'detalle' => $pag_js,                       'montopagado' => 0,
            'cargo_retardo' => 0,                       'restante' => 0,
            'saldo' => 0,                               'costototal' => 0,
            'numero_de_pago' => $num_p,                 'fecha_limite_pago' => date('Y-m-d', strtotime('+1 month', strtotime($data['fecha_limite_pago']))),
            'fechapago' => date("Y-m-d"),               'comprobante' => '',
            'idPromocion' => $id_promo,          'estatus' => 'verificado',
            'como_realizo_pago' => '',                  'metodo_de_pago' => '',
            'banco_de_deposito' => '',                  'quien_registro' => $data['quien_registro'],
            'codigo_de_autorizacion' => '',             'referencia' => '',
            'order_id' => '', 'moneda' => $data['moneda']
          ];
          $this->registrar_pago_mult($insert);
        }
      }
      if($info_concep['data']['categoria'] == 'Inscripción' && $data['estatus'] == 'verificado'){
        // verificar los pagos que pertenescan al mismo y que esten pendientes de verificar
        $consultar_pendientes = $this->obtener_pago_pendiente($data['id_prospecto'], $data['id_concepto']);
        if(sizeof($consultar_pendientes) > 0){
          // buscar el menor restante de los pagos verificados
          $minimo_restante = $this->encontrar_menor_restante($data['id_prospecto'], $data['id_concepto']);
          if($minimo_restante){
            // actualizar el restante de los pagos pendientes
            $nuevo_restante = floatval($minimo_restante['restante']) - floatval($data['montopagado']);
            $actualizacion = $this->actualizar_restante_pagos_pendientes($data['id_prospecto'], $data['id_concepto'], floatval($data['montopagado']) , $data['restante'], $data['numero_de_pago']);
          }
        }
      }else if($info_concep['data']['categoria'] == 'Mensualidad' && $data['estatus'] == 'verificado'){
        $this->actualizar_pagos_pendientes($response);
      }
		}
		$conexion = null;
		$con = null;
		return $response;
  }

  function consultar_fecha_corte_mensualidad($alumno, $concepto_mensualidad){
    $resp = false;
    $info_con = $this->obtener_concepto($concepto_mensualidad);
    $info_con = $info_con['data'];
    if($info_con && $info_con['id_generacion'] != 0){
      if (!class_exists('Generaciones')) {
          require_once 'generacionesModel.php';
      }
      $generacionesM = new Generaciones();
      $asign_gen = $generacionesM->buscarAsignacion($alumno, $info_con['id_generacion']);
      if($asign_gen['estatus'] == 'ok' && sizeof($asign_gen['data']) > 0){
        if($asign_gen['data'][0]['fecha_primer_colegiatura'] !== null){
          $resp = explode('-', $asign_gen['data'][0]['fecha_primer_colegiatura'])[2];
        }
      }
    }
    if($resp == false){
      $resp = explode('-', $info_con['fechalimitepago'])[2];
    }
    if($resp !== false){
      $resp = explode(" ",$resp)[0];
    }
    return $resp;
  }
public function validarfechalimitedepago($id_pago){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT *
              FROM a_pagos
              WHERE id_pago = :id_pago;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':id_pago', $id_pago);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }

  function obtener_pago_pendiente($alumno, $concepto){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
    $con = $con['conexion'];
		$query = $con->query("SELECT * FROM a_pagos WHERE id_prospecto = $alumno AND id_concepto = $concepto AND estatus = 'pendiente' ;");
    if($query){
      $response = $query->fetchAll(PDO::FETCH_ASSOC);
    }
		return $response;
  }
public function validar_pagos_alumno($id_prospecto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT * 
            FROM a_pagos 
            WHERE id_prospecto =:idProspecto AND estatus='verificado'";
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':idProspecto', $id_prospecto);
    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  function obtener_fecha_limite_pago_anterior($alumno, $concepto, $prev, $idp = false){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
    $con = $con['conexion'];
    
    $query = $con->query("SELECT MAX(fecha_limite_pago) as fecha_limite_pago FROM a_pagos WHERE id_prospecto = $alumno AND id_concepto = $concepto AND estatus = 'verificado' AND numero_de_pago = $prev ;");
    if($query){
      $response = $query->fetch(PDO::FETCH_ASSOC);
    }
    return $response;
  }

  public function validar_promocion_alumno($id_prospecto){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT * 
            FROM `promociones`
            WHERE id_prospecto =:idProspecto";
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':idProspecto', $id_prospecto);
    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function validar_promocion_generacion($idGeneracion){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT * 
            FROM `promociones`
            WHERE id_generacion =:id_generacion";
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':id_generacion', $idGeneracion);
    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function quien_registro($idAcceso){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT idTipo_Persona,idPersona
              FROM a_accesos
              WHERE idAcceso = :idAcceso;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idAcceso', $idAcceso);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }

  public function nombre_marketing($idPersona){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT concat(nombres,' ',apellidoPaterno) as nombres
              FROM a_marketing_personal
              WHERE idPersona = :idPersona;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idPersona', $idPersona);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }

  public function costo_total_alumno_promo_generacion($idGeneracion){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT promo.id_concepto, promo.nombrePromocion, pc.precio, promo.id_generacion, promo.porcentaje,pc.numero_pagos,promo.fechainicio,promo.fechafin,(pc.precio*pc.numero_pagos)*promo.porcentaje/100 as costoconpromocion
              FROM `promociones` as promo
              JOIN pagos_conceptos AS pc on pc.id_concepto=promo.id_concepto
              WHERE promo.id_generacion = :idGeneracion AND now() BETWEEN promo.fechainicio AND concat(promo.fechafin,' 23:59:59');";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idGeneracion', $idGeneracion);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }

  public function costo_total_alumno_promo($idAsistente){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT promo.id_prospecto,promo.id_concepto,promo.nombrePromocion,promo.porcentaje,pc.concepto,pc.precio,pc.numero_pagos,promo.fechainicio,promo.fechafin, (pc.precio*pc.numero_pagos)*promo.porcentaje/100 as costoconpromocion
      FROM `promociones` as promo 
      JOIN pagos_conceptos as pc on pc.id_concepto=promo.id_concepto
      WHERE `id_prospecto` = :idAsistente AND now() BETWEEN promo.fechainicio AND concat(promo.fechafin,' 23:59:59');";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idAsistente', $idAsistente);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }

  public function obtener_totales_sinpromocion($idCarrera){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT algen.idGeneracion,algen.idalumno,algen.idRelacion, concat(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno) as nombre_completo,ap.correo, agen.nombre as nombre_generacion, pc.concepto,pc.precio,pc.numero_pagos, sum(pc.precio*pc.numero_pagos) as costo_total,ap.tipoPago
              from alumnos_generaciones as algen
              join a_generaciones as agen on agen.idGeneracion=algen.idgeneracion
              JOIN a_carreras as ac on ac.idCarrera=agen.idCarrera
              JOIN a_prospectos as ap on ap.idAsistente=algen.idalumno
              JOIN pagos_conceptos as pc on pc.id_generacion=algen.idgeneracion
              where ac.idCarrera=:idCarrera AND pc.idExamen is null
              group by algen.idRelacion;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idCarrera', $idCarrera);
      
			$statement->execute();

		
      
      $conexion = null;
      $con = null;
  
      return $statement;
			
		}
  }

  public function montopagadoalumno($idProspecto,$idGeneracion){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT apag.id_prospecto,apag.id_concepto,sum(apag.montopagado) as montopagadoalumno
              FROM a_pagos as apag
              JOIN pagos_conceptos as pc on pc.id_concepto=apag.id_concepto
              WHERE apag.id_prospecto=:idProspecto and apag.estatus='verificado' and pc.id_generacion=:idGeneracion
              GROUP by id_prospecto;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':idProspecto', $idProspecto);
      $statement->bindParam(':idGeneracion', $idGeneracion);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }
public function tiene_pagos_verificados($id_prospecto,$idGeneracion){
    $conexion = new Conexion();
    $con = $conexion->conectar();
    $response = [];
  
    if($con['info'] == 'ok'){
    $con = $con['conexion'];
    $sql = "SELECT * 
            FROM a_pagos as apag
            JOIN pagos_conceptos as pc on pc.id_concepto=apag.id_concepto
            WHERE apag.id_prospecto =:idProspecto AND apag.estatus='verificado' and pc.id_generacion=:idGeneracion;";
  
    $statement = $con->prepare($sql);
    $statement->bindParam(':idProspecto', $id_prospecto);
    $statement->bindParam(':idGeneracion', $idGeneracion);

    $statement->execute();
  
    if($statement->errorInfo()[0] == "00000"){
      $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql, "data"=>$id];
      }
      $conexion = null;
      $con = null;
  
      return $response;
    }
  }

  public function validar_si_generacion_ya_esta_asignada($idProspecto,$idGeneracion){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT ag.*, g.secuencia_generacion FROM alumnos_generaciones ag 
              JOIN a_generaciones g ON g.idGeneracion = ag.idgeneracion
              WHERE ag.idalumno = :alumno AND ag.idgeneracion = :generacion;";

			$statement = $con->prepare($sql);
			$statement->bindParam(':alumno', $idProspecto);
			$statement->bindParam(':generacion', $idGeneracion);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
  }

  public function asignar_generacion_alumno($alumno, $generacion){
		$conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
		$fecha_hoy = date('Y-m-d H:i:s');
		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "INSERT INTO `alumnos_generaciones`(`idalumno`, `idgeneracion`, `fecha_inscripcion`) 
			VALUES (:alumno,:generacion,:fecha)";

			$statement = $con->prepare($sql);
			$statement->bindParam(':alumno', $alumno);
			$statement->bindParam(':generacion', $generacion);
			$statement->bindParam(':fecha', $fecha_hoy);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
	}

  public function validar_carrera($idGeneracion){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT *  
              FROM `a_generaciones` 
              WHERE `idGeneracion` = :generacion";

			$statement = $con->prepare($sql);
			$statement->bindParam(':generacion', $idGeneracion);
			$statement->execute();

			if($statement->errorInfo()[0] == 00000){
				$response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
			}else{
				$response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
			}
		}
		$conexion = null;
		$con = null;
		return $response;
  }

  function actualizar_pagos_pendientes($id_pago){
    $info_pago = $this->obtener_informacion_pago_id($id_pago)['data'];
    // var_dump($info_pago);
    $montopagado = $info_pago['montopagado'];     // <- pagado
    $cargo_retardo = $info_pago['cargo_retardo']; // <- pagado

    $restante = $info_pago['restante'];          // <- debe
    $saldo = $info_pago['saldo'];                // <- debe

    $pendientes = $this->obtener_pago_pendiente($info_pago['id_prospecto'], $info_pago['id_concepto']);
    $anteriores = $this->listar_pagos_anteriores($info_pago['id_concepto'], $info_pago['id_prospecto'])['data'];
    $anterior_p = false;
    foreach($anteriores as $anterior){
      if($anterior['id_pago'] != $id_pago && $anterior['numero_de_pago'] == $info_pago['numero_de_pago'] && $anterior_p === false){
        $anterior_p = $anterior;
      }
    }
    foreach($pendientes as $pendiente){
      $numero_de_pago = $info_pago['numero_de_pago'];
      $fecha_limite_pago = $info_pago['fecha_limite_pago'];
      $actualizar_pendiente = ['id_pago' => $pendiente['id_pago'], 'montopagado' => 0, 'cargo_retardo' => 0, 'restante' => 0, 'saldo' => 0];
      if(($restante + $saldo) > 0.5){
        $disponible = $pendiente['montopagado'] + $pendiente['cargo_retardo'];
        if($pendiente['restante'] < 0){
          $disponible += abs($pendiente['restante']);
        }
        if($disponible >= $restante && $restante > 0){
          $actualizar_pendiente['montopagado'] = $restante;
          $disponible = $disponible - $restante;
        }else if($restante > 0){
          $actualizar_pendiente['montopagado'] = $disponible;
          $actualizar_pendiente['restante'] = $restante - $disponible;
          $disponible = 0;
        }

        if($disponible >= $saldo && $saldo > 0){
          $actualizar_pendiente['cargo_retardo'] = $saldo;
          $disponible = $disponible - $saldo;
        }else if($saldo > 0){
          $actualizar_pendiente['cargo_retardo'] = $disponible;
          $actualizar_pendiente['saldo'] = $saldo - $disponible;
          $disponible = 0;
        }
        if($disponible > 0){
          $actualizar_pendiente['restante'] = 0 - $disponible;
        }
        if(($actualizar_pendiente['restante'] + $actualizar_pendiente['saldo'] <= 0.5) && ($anterior_p === false || $anterior_p['fecha_limite_pago'] == $pendiente['fecha_limite_pago'])){
          $fecha_limite_pago = date('Y-m-d', strtotime($fecha_limite_pago . ' + 1 month'));
        }
      }else{
        $disponible = $info_pago['restante'] < 0 ? abs($info_pago) : 0;
        $disponible += $pendiente['restante'] < 0 ? abs($pendiente['restante']) : 0;
        $disponible += $pendiente['montopagado'] + $pendiente['cargo_retardo'];
        /* RESPALDO
        if($pendiente['montopagado'] + $pendiente['cargo_retardo'] + $disponible >= $pendiente['costototal']){
          if($disponible >= $pendiente['restante'] && $pendiente['restante'] > 0){
            $actualizar_pendiente['montopagado'] = $pendiente['montopagado'] + $pendiente['restante'];
            $disponible = $disponible - $pendiente['restante'];
            $actualizar_pendiente['restante'] = 0;
          }else if($pendiente['restante'] > 0){
            $actualizar_pendiente['montopagado'] = $pendiente['montopagado'] + $disponible;
            $actualizar_pendiente['restante'] = $pendiente['restante'] - $disponible;
            $disponible = 0;
          }

          if($disponible >= $pendiente['saldo'] && $pendiente['saldo'] > 0){
            $actualizar_pendiente['cargo_retardo'] = $pendiente['cargo_retardo'] + $pendiente['saldo'];
            $disponible = $disponible - $pendiente['saldo'];
            $actualizar_pendiente['saldo'] = 0;
          }else if($pendiente['saldo'] > 0){
            $actualizar_pendiente['cargo_retardo'] = $pendiente['cargo_retardo'] + $disponible;
            $actualizar_pendiente['saldo'] = $pendiente['saldo'] - $disponible;
            $disponible = 0;
          }
          $numero_de_pago++;
          $fecha_limite_pago = date('Y-m-d', strtotime($fecha_limite_pago . ' + 1 month'));
          if($disponible > 0){
            $actualizar_pendiente['restante'] = 0 - $disponible;
          }
        }*/
        $siguiente_pago = $this->obtener_pago_aplicar($info_pago['id_prospecto'], $info_pago['id_concepto'], $pendiente['fechapago']);
        if($disponible >= $siguiente_pago['monto_por_pagar'] && $siguiente_pago['monto_por_pagar'] > 0){
          $actualizar_pendiente['montopagado'] = $siguiente_pago['monto_por_pagar'];
          $disponible = $disponible - $siguiente_pago['monto_por_pagar'];
          $actualizar_pendiente['restante'] = 0;
        }else if($siguiente_pago['monto_por_pagar'] > 0){
          $actualizar_pendiente['montopagado'] = $disponible;
          $actualizar_pendiente['restante'] = $siguiente_pago['monto_por_pagar'] - $disponible;
          $disponible = 0;
        }
        if($disponible >= $siguiente_pago['monto_retardo'] && $siguiente_pago['monto_retardo'] > 0){
          $actualizar_pendiente['cargo_retardo'] = $siguiente_pago['monto_retardo'];
          $disponible = $disponible - $siguiente_pago['monto_retardo'];
          $actualizar_pendiente['saldo'] = 0;
        }else if($siguiente_pago['monto_retardo'] > 0){
          $actualizar_pendiente['cargo_retardo'] = $disponible;
          $actualizar_pendiente['saldo'] = $siguiente_pago['monto_retardo'] - $disponible;
          $disponible = 0;
        }
        if($disponible > 0){
          $actualizar_pendiente['restante'] = 0 - $disponible;
        }
        $numero_de_pago = $siguiente_pago['numero_de_pago'];
        $fecha_limite_pago = $siguiente_pago['fecha_limite_pago'];
        if($numero_de_pago > $info_pago['numero_de_pago'] && $actualizar_pendiente['restante'] + $actualizar_pendiente['saldo'] <= 0.5){
          $fecha_limite_pago = date('Y-m-d', strtotime($fecha_limite_pago . ' + 1 month'));
        }
      }
      // actualizar numero de pago y fecha limite de pago
      //var_dump($actualizar_pendiente);
      $this->actualizar_pendiente($actualizar_pendiente, $fecha_limite_pago, $numero_de_pago);
    }
    # obtener informacion del pago que se esta procesando
    
    /**
     *  montopagado     <- pagado
     *  cargo_retardo   <- pagado
     *  -------------------------
     *  restante        <- debe
     *  saldo           <- debe
     * */

    # foreach pagos_pendientes as pendiente
    #   
    #   si el pago tiene un saldo o restante por cubrir, el resto de los pendientes se toman para cubrir los saldos pendientes
    #     
    #     // tomar el saldo disponible de cada pago pendiente
    #     disponible = pendiente->montopagado + pendiente->cargo_retardo + (pendiente->restante < 0 ? pendiente->restante : 0)
    #     // pagar el restante del saldo disponible
    #     si disponible >= pago->restante && pago->restante > 0
    #       pendiente->montopagado = pago->restante
    #       disponible = disponible - pago->restante
    #     si no
    #       pendiente->montopagado = disponible
    #       pendiente->restante = pago->restante - disponible
    #       disponible = 0
    #     fin
    #
    #     // pagar el restante del saldo disponible
    #     si disponible >= pago->saldo
    #       pendiente->cargo_retardo = pago->saldo
    #       disponible = disponible - pago->saldo
    #     si no
    #       pendiente->cargo_retardo = disponible
    #       pendiente->saldo = pago->saldo - disponible
    #       disponible = 0
    #     fin
    #     
    #     pendiente->restante = disponible < 0 ? 0 - disponible
    #     pendiente->update()
    #   si no, si el pago no tiene restante o saldo por cubrir 
    #     // si el pago tiene saldo a favor se tomara para cubrir restantes o saldos faltantes de cada pago pendiente
    #     disponible = pago->restante < 0 ? abs(pago->restante) : 0
    #     disponible += pendiente->restante < 0 ? abs(pendiente->restante) : 0
    #   
    #     si disponible >= pendiente->restante && pendiente->restante > 0
    #       pendiente->montopagado = pendiente->monto_pagado + pendiente->restante
    #       disponible = disponible - pendiente->restante
    #       pendiente->restante = 0
    #     sino
    #       pendiente->montopagado = pendiente->monto_pagado + disponible
    #       pendiente->restante = pendiente->restante - disponible
    #       disponible = 0
    #     fin
    #   
    #     si disponible >= pendiente->saldo
    #       pendiente->recargo_pagado = pendiente->recargo_pagado + pendiente->saldo
    #       disponible = disponible - pendiente->saldo
    #       pendiente->saldo = 0
    #     sino
    #       pendiente->recargo_pagado = pendiente->recargo_pagado + disponible
    #       disponible = 0
    #       pendiente->saldo = pendiente->saldo - disponible
    #     fin
    #   
    #     pendiente->restante = disponible < 0 ? 0 - disponible
    #     pago->numero_de_pago ++;
    #     pendiente->update()
    #   fin
    #   cada pendiente tomara el numero de pago y la fecha limite del pago que se esta procesando
  }

  function actualizar_pendiente($datos, $fecha_lim, $num_p){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];
    $con = $con['conexion'];
    $sql = "UPDATE a_pagos SET ";
    $sets = '';
    $where = '';
    foreach($datos as $key => $val){
      if($key != 'id_pago'){
        if($sets == ''){
          $sets .= $key . " = '" . $val . "'";
        }else{
          $sets .= ", ".$key . " = '" . $val . "' ";
        }
      }else{
        $where .= " WHERE " . $key . " = " . $val . "";
      }
    }
    $sets .= ", fecha_limite_pago = :f_lim, numero_de_pago = :num_p ";
    $sql .= $sets . $where;
    $statement = $con->prepare($sql);
    $statement->bindParam(':f_lim', $fecha_lim);
    $statement->bindParam(':num_p', $num_p);

    $statement->execute();
    if($statement->errorInfo()[0] == '00000'){
      $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
    }else{
      $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
    }
    
    $conexion = null;
    $con = null;

    return $response;
  }
  function contrasenia_correo($correo){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con["info"] == "ok"){
			$con = $con['conexion'];
			$sql = "SELECT AES_DECRYPT(contrasenia, 'SistemasPUE21') as contrasenia
              FROM `afiliados_conacon` 
              WHERE `email` = :correo";

			$statement = $con->prepare($sql);
			$statement->bindParam(':correo', $correo);
			$statement->execute();
    }
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  function nombre_cobranza_idAcceso($idAcceso){
    $conexion = new Conexion();
		$con = $conexion->conectar();
		$response = [];

		if($con['info'] == 'ok'){
			$con = $con['conexion'];

			$sql = "SELECT acs.*, pln.nombres, pln.apellidoPaterno, pln.apellidoMaterno FROM `a_accesos` acs 
        JOIN a_plan_pagos pln ON pln.idPersona = acs.idPersona
        WHERE acs.idAcceso = :persona;";

			$statement = $con->prepare($sql);
      $statement->bindParam(':persona', $idAcceso);
      
			$statement->execute();

			if($statement->errorInfo()[0] == '00000'){
        $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)];
      }else{
        $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
      }
      
      $conexion = null;
      $con = null;
  
      return $response;
			
		}
  }
}
?>

  
