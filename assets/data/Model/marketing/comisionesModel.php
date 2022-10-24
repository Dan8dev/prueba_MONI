<?php 
date_default_timezone_set("America/Mexico_City");
	class MarketingComisiones{
        var $montos_comision = [
			'19' => 250,
			'20' => 250,
			'13' => 100
		];

        function __consultar_prospectos_confirmados_ejecutiva($mes, $anio, $ejecutiva){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$con = $con["conexion"];

			$sql = "SELECT aten.*, carr.nombre as nombre_carrera, carr.idInstitucion FROM a_marketing_atencion aten
					JOIN a_carreras carr ON carr.idCarrera = aten.evento_carrera
					JOIN a_pagos pags ON pags.id_prospecto = aten.prospecto
					WHERE aten.etapa = 2 AND (MONTH(pags.fechapago) = :mes AND YEAR(pags.fechapago) = :anio) AND aten.idMk_persona = :ejecutiva ORDER BY aten.tipo_atencion;";
			$statement = $con->prepare($sql);
			$statement->bindParam(':ejecutiva', $ejecutiva);
			$statement->bindParam(':mes', $mes);
			$statement->bindParam(':anio', $anio);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

        function consultar_prospectos_confirmados_ejecutiva($ejecutiva){
            $conexion = new Conexion();
            $con = $conexion->conectar();
            $con = $con["conexion"];
            
            $sql = "SELECT aten.*, CONCAT(prosp.nombre,' ',prosp.aPaterno,' ',prosp.aMaterno) as nombre_prospecto FROM a_marketing_atencion aten
                JOIN a_prospectos prosp ON prosp.idAsistente = aten.prospecto
                WHERE aten.etapa = 2 AND aten.idMk_persona = :ejecutiva AND aten.corte IS NULL ORDER BY aten.tipo_atencion, aten.prospecto;";
            $statement = $con->prepare($sql);
            
            $statement->bindParam(':ejecutiva', $ejecutiva);
            
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function consultar_periodo($mes, $anio, $ejecutiva = null){
            $conexion = new Conexion();
            $con = $conexion->conectar();
            $con = $con["conexion"];
            $query_ejecutiva = "";
            if($ejecutiva != null){
                $query_ejecutiva = " AND idMarketing = :ejecutiva";
            }
            $sql = "SELECT * FROM cc_cortes_comisiones WHERE MONTH(fechaCorte) = :mes AND YEAR(fechaCorte) = :anio {$query_ejecutiva};";
            $statement = $con->prepare($sql);
            $statement->bindParam(':mes', $mes);
            $statement->bindParam(':anio', $anio);
            if($ejecutiva != null){
                $statement->bindParam(':ejecutiva', $ejecutiva);
            }
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        function consultar_pagos_alumno_periodo_by_carrera($mes, $anio, $prospecto, $concepto = null, $carrera){
            $conexion = new Conexion();
            $con = $conexion->conectar()["conexion"];

            $sql = "SELECT car.idCarrera, gen.idGeneracion, con.concepto, con.descripcion, con.categoria, con.precio, con.parcialidades, pags.fechapago, pags.id_prospecto, pags.restante, pags.id_pago FROM `a_carreras` car
                    JOIN a_generaciones gen ON gen.idCarrera = car.idCarrera
                    JOIN pagos_conceptos con ON con.id_generacion = gen.idGeneracion
                    JOIN a_pagos pags ON pags.id_concepto = con.id_concepto
                    WHERE car.idCarrera = :carrera AND pags.id_prospecto = :prospecto AND ((MONTH(pags.fechapago) = :mes AND YEAR(pags.fechapago) = :anio) OR (MONTH(pags.fechapago) < :mes AND YEAR(pags.fechapago) <= :anio)) AND pags.estatus = 'verificado' ORDER BY pags.id_concepto;";
            $statement = $con->prepare($sql);
            $statement->bindParam(':prospecto', $prospecto);
            $statement->bindParam(':mes', $mes);
            $statement->bindParam(':anio', $anio);
            $statement->bindParam(':carrera', $carrera);

            $statement->execute();
            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
            if($concepto != null){
                foreach ($response as $key => $value) {
                    if($value["categoria"] != $concepto){
                        unset($response[$key]);
                    }
                }
                $response = array_values($response);
            }
            return $response;
        }

        function consultar_pagos_alumno_periodo_by_evento($mes, $anio, $prospecto, $concepto = null, $evento){
            $conexion = new Conexion();
            $con = $conexion->conectar()["conexion"];

            $sql = "SELECT ev.idEvento, ev.titulo, con.id_concepto, con.concepto, con.categoria, pags.* FROM `ev_evento` ev
                    JOIN planes_pagos pp ON pp.idEvento = ev.idEvento
                    JOIN pagos_conceptos con ON con.idPlan_pago = pp.idPlanPago
                    JOIN a_pagos pags ON pags.id_concepto = con.id_concepto
                    WHERE ev.idEvento = :evento AND pags.id_prospecto = :prospecto AND ((MONTH(pags.fechapago) = :mes AND YEAR(pags.fechapago) = :anio) OR (MONTH(pags.fechapago) < :mes AND YEAR(pags.fechapago) <= :anio)) AND pags.estatus = 'verificado' ORDER BY pags.id_concepto;";
            $statement = $con->prepare($sql);
            $statement->bindParam(':prospecto', $prospecto);
            $statement->bindParam(':mes', $mes);
            $statement->bindParam(':anio', $anio);
            $statement->bindParam(':evento', $evento);

            $statement->execute();
            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
            if($concepto != null){
                foreach ($response as $key => $value) {
                    if($value["categoria"] != $concepto){
                        unset($response[$key]);
                    }
                }
                $response = array_values($response);
            }
            return $response;
        }

        function consultar_nombre_evento_carrera($tipo, $id){
            $conexion = new Conexion();
            $con = $conexion->conectar()["conexion"];
            if($tipo == "evento"){
                return $con->query("SELECT ev.* FROM `ev_evento` ev WHERE ev.idEvento = {$id};")->fetch(PDO::FETCH_ASSOC);
            }else{
                return $con->query("SELECT car.* FROM `a_carreras` car WHERE car.idCarrera = {$id};")->fetch(PDO::FETCH_ASSOC);
            }
        }

        function calcular_comisiones($atenciones, $fecha){
            foreach($atenciones as $key => $atencion){
                // consultar los pagos que el prospecto ha realizado referentes al interes en cuestion
                $pagos = [];
                $institucion = '';
                $const_comision = 0;
                if($atencion['tipo_atencion'] == 'evento'){
                    // $info_i = $this->consultar_nombre_evento_carrera('evento', $atencion['evento_carrera']);
                    // $atenciones[$key]['nombre_interes'] = $info_i['titulo'];
                    // $institucion = $info_i['idInstitucion'];
                    // $pagos = $this->consultar_pagos_alumno_periodo_by_evento($fecha[1], $fecha[0], $atencion['prospecto'], 'Inscripción', $atencion['evento_carrera']);
                }else if($atencion['tipo_atencion'] == 'carrera'){
                    $info_i = $this->consultar_nombre_evento_carrera('carrera', $atencion['evento_carrera']);
                    $const_comision = $info_i['comision'] !== null ? $info_i['comision'] : 0;
                    $atenciones[$key]['nombre_interes'] = $info_i['nombre'];
                    $institucion = $info_i['idInstitucion'];
                    $pagos = $this->consultar_pagos_alumno_periodo_by_carrera($fecha[1], $fecha[0], $atencion['prospecto'], 'Inscripción', $atencion['evento_carrera']);
                }
        
                $atenciones[$key]['institucion'] = $institucion;
                foreach($pagos as $key_p => $pago){ // filtrar pagos que aun tienen saldo pendiente
                    if(intval($pago['restante']) > 2){
                        unset($pagos[$key_p]);
                    }
                }
                // si hay pagos que no tengas saldo pendiente, se sumara el monto de la comision
                $monto_comision = $const_comision;
        
                $atenciones[$key]['comision'] = $monto_comision;
                $pagos = array_values($pagos);
                $atenciones[$key]['pagos'] = $pagos;
                if(empty($pagos)){
                    unset($atenciones[$key]);
                }
                // $pagos_periodo = $this->consultar_pagos_alumno_periodo($fecha[1], $fecha[0], $atencion['prospecto'], 'Inscripción');
                // echo "Ejecutiva: ".$atencion['idMk_persona'].", Atención: ".$atencion['tipo_atencion']." [{$atencion["evento_carrera"]}]".", prospecto: ".$atencion['prospecto'].", etapa: ".$atencion['etapa']." Pagos: ".sizeof($pagos_periodo)." \n";
            }
        
            return $atenciones;
        }

        function registrar_corte_ejecutiva($data){
            $conexion = new Conexion();
            $con = $conexion->conectar()["conexion"];

            $sql = "INSERT INTO cc_cortes_comisiones (idMarketing, montoCalculado, fechaCorte, jsonEC, pagado)
                    VALUES (:ejecutiva, :monto, :fecha_corte, :json_ec, 0);";
            $statement = $con->prepare($sql);
            $statement->execute($data);
            return $con->lastInsertId();
        }

        function marcar_atenciones_en_corte($ids, $corte){
            $conexion = new Conexion();
            $con = $conexion->conectar()["conexion"];

            $sql = "UPDATE a_marketing_atencion SET corte = :corte WHERE idReg in ({$ids});";
            $statement = $con->prepare($sql);
            $statement->bindParam(':corte', $corte);
            $statement->execute();
        }

        function consultar_carreras(){
            $conexion = new Conexion();
            $con = $conexion->conectar()["conexion"];

            $sql = "SELECT car.* FROM a_carreras car JOIN a_instituciones inst ON inst.id_institucion = car.idInstitucion;";
            $statement = $con->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function actualizar_monto_comision($carrera, $monto){
            $conexion = new Conexion();
            $con = $conexion->conectar()["conexion"];

            $sql = "UPDATE a_carreras SET comision = :monto WHERE idCarrera = :carrera;";
            $statement = $con->prepare($sql);
            $statement->bindParam(':monto', $monto);
            $statement->bindParam(':carrera', $carrera);
            $statement->execute();
            return $statement->rowCount();
        }
        
    }
?>