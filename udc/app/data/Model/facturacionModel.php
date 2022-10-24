<?php

use FontLib\Table\Type\post;

date_default_timezone_set('America/Mexico_City');
	require_once 'conexion.php';

    class DataFactur{

        public function registrardatosfactuacion($post){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                    $id = $post['id_us']; 
                    $sql = "SELECT id FROM d_facturacion WHERE id_prospecto = '$id'";
                    $statement = $con->prepare($sql);
                    $statement->execute();
                    $r = $statement->rowCount();
                    if($r > 0){
                        unset($post['id_us']);
                        $sql = "UPDATE `d_facturacion`
                        SET `nombre_rz` = :nrazon ,`rfc` = :rfc, `calle` = :street,`numero` = :numberD,`colonia` = :bd,`ciudad` = :city,`estado` = :stateD,
                        `cp` = :cp,`email` = :email, `uso_cfdi` = :cfdi
                        WHERE id_prospecto = '$id'";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);

                        if($statement->errorInfo()[0] == 00000){
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                        }
                    }else{
                        $sql = "INSERT INTO `d_facturacion`
                        (`nombre_rz` ,`rfc`, `calle`,`numero`,`colonia` ,`ciudad` ,`estado`,
                        `cp` ,`email`, `uso_cfdi`, `id_prospecto`) 
                        VALUES (:nrazon, :rfc, :street, :numberD, :bd, :city, :stateD, :cp, :email, :cfdi , :id_us);";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);


                        if($statement->errorInfo()[0] == 00000){
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
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
        public function datosFacturas($post){
			$conexion = new Conexion();
			$con = $conexion->conectar();
		
			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT * 
                        FROM a_pagos as ap
                        LEFT JOIN afiliados_conacon as afc on afc.id_prospecto = ap.id_prospecto
                        JOIN d_facturacion as df on df.id_prospecto = ap.id_prospecto
                        LEFT JOIN pagos_conceptos as pc on pc.id_concepto = ap.id_concepto
                        LEFT JOIN dc_facturas as dcf on dcf.id_pagos = ap.id_pago
                        WHERE NOT EXISTS(SELECT * 
                                        FROM dc_facturas as dcf 
                                        WHERE dcf.id_pagos = ap.id_pago );";

                $statement = $con->prepare($sql);
                $statement->execute();

            
                $conexion = null;
                $con = null;
                $response = $statement;
                
                return $response;
                    
			}

						
		}
        public function descargarFacturas($post){
			$conexion = new Conexion();
			$con = $conexion->conectar();
		
			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "SELECT * 
                        FROM a_pagos as ap
                        LEFT JOIN afiliados_conacon as afc on afc.id_prospecto = ap.id_prospecto
                        JOIN d_facturacion as df on df.id_prospecto = ap.id_prospecto
                        LEFT JOIN pagos_conceptos as pc on pc.id_concepto = ap.id_concepto
                        LEFT JOIN dc_facturas as dcf on dcf.id_pagos = ap.id_pago
                        WHERE EXISTS(SELECT * 
                                        FROM dc_facturas as dcf 
                                        WHERE dcf.id_pagos = ap.id_pago ) and ap.id_prospecto = '$post';";

                $statement = $con->prepare($sql);
                $statement->execute();

            
                $conexion = null;
                $con = null;
                $response = $statement;
                
                return $response;
                    
			}

						
		}
        public function cargarDatosAfFac($post){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

            $sql = "SELECT * FROM d_facturacion WHERE id_prospecto = '$post'";
                    $statement = $con->prepare($sql);
                    $statement->execute();
                    if($statement->errorInfo()[0] == 00000){
                        $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
                    }else{
                        $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                    }
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function subirFacturas($post){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "INSERT INTO `dc_facturas`
						(`id_prospecto`,`id_pagos`,`link_pdf`, `link_xml`) 
						VALUES (:id_us,:idPayment,:file_pdf, :file_xml);";
				$statement = $con->prepare($sql);
				$statement->execute($post);


				if($statement->errorInfo()[0] == 00000){
					$response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
				}else{
					$response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
				}
			}else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

			$conexion = null;
			$con = null;
			return $response;			
		}

    
    }