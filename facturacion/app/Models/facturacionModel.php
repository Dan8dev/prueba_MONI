<?php

use FontLib\Table\Type\post;

date_default_timezone_set('America/Mexico_City');
	require '../../../assets/data/Model/conexion/conexion.php';

    class DataFactura{

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
                        SET `nombre_rz` = :nrazon ,`rfc` = :rfc, `calle` = :street,`numero_ext` = :numberD,`numero_int` = :numberI,`poblacion`= :pob,`colonia` = :bd,`ciudad` = :city,`estado` = :stateD,
                        `cp` = :cp,`email` = :email, `uso_cfdi` = :cfdi, `nationality` = :nationality, `activity` = :activity, `regimen` = :regimen,
                        `link_conts` = :file_pdf,`id_fiscal` = :idfiscal ,`change_request` = :changeDa, `status_data` = :statusDa, `reason_of_rejection` = :reason
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
                        (`nombre_rz`,`rfc`, `calle`,`numero_ext`,`numero_int`,`poblacion`,`colonia`,`ciudad`,`estado`,
                        `cp`,`email`,`uso_cfdi`,`nationality`,`activity`,`regimen`,
                        `link_conts`,`id_fiscal`,`id_prospecto`,`change_request`,`status_data`,`reason_of_rejection`) 
                        VALUES (:nrazon,:rfc,:street,:numberD,:numberI,:pob,:bd,:city,:stateD,:cp,:email,:cfdi,:nationality,:activity,:regimen,:file_pdf,:idfiscal,:id_us,:changeDa,:statusDa,:reason);";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);

                        if($statement->errorInfo()[0] == 00000){
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$post];
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


                if($post['option'] == 'porfacturar'){
                    $sql = "SELECT *,afc.idAsistente,afc.nombre as nombreA ,ai.nombre as n_institucion,ap.referencia as apref,ap.moneda
                    FROM a_pagos as ap
                    LEFT JOIN a_prospectos as afc on afc.idAsistente  = ap.id_prospecto
                    JOIN d_facturacion as df on df.id_prospecto = ap.id_prospecto
                    LEFT JOIN pagos_conceptos as pc on pc.id_concepto = ap.id_concepto
                    LEFT JOIN dc_facturas as dcf on dcf.id_pagos = ap.id_pago
                    LEFT JOIN a_generaciones as ag on ag.idGeneracion=pc.id_generacion
                    LEFT JOIN a_carreras as ac on ac.idCarrera=ag.idCarrera
                    LEFT JOIN a_instituciones as ai on ai.id_institucion=ac.idInstitucion
                    WHERE (NOT EXISTS(SELECT * 
                                     FROM dc_facturas as dcf 
                                     WHERE dcf.id_pagos = ap.id_pago ) or status_dc = 'eliminado') and df.status_data = 'aprobado' and ap.estatus = 'verificado';";
                }else{
                    $sql = "SELECT *,afc.idAsistente,afc.nombre as nombreA,ai.nombre as n_institucion,ap.referencia as apref,ap.moneda
                    FROM a_pagos as ap
                    LEFT JOIN a_prospectos as afc on afc.idAsistente  = ap.id_prospecto
                    JOIN d_facturacion as df on df.id_prospecto = ap.id_prospecto
                    LEFT JOIN pagos_conceptos as pc on pc.id_concepto = ap.id_concepto
                    LEFT JOIN dc_facturas as dcf on dcf.id_pagos = ap.id_pago
                    LEFT JOIN a_generaciones as ag on ag.idGeneracion=pc.id_generacion
                    LEFT JOIN a_carreras as ac on ac.idCarrera=ag.idCarrera
                    LEFT JOIN a_instituciones as ai on ai.id_institucion=ac.idInstitucion
                    WHERE (EXISTS(SELECT * 
                                     FROM dc_facturas as dcf 
                                     WHERE dcf.id_pagos = ap.id_pago) and status_dc = 'enviado') and df.status_data = 'aprobado' and ap.estatus = 'verificado';";
                }
		
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


				$sql = "SELECT *,ap.referencia as apref
                        FROM a_pagos as ap
                        LEFT JOIN a_prospectos as afc on afc.idAsistente  = ap.id_prospecto
                        JOIN d_facturacion as df on df.id_prospecto = ap.id_prospecto
                        LEFT JOIN pagos_conceptos as pc on pc.id_concepto = ap.id_concepto
                        LEFT JOIN dc_facturas as dcf on dcf.id_pagos = ap.id_pago
                        WHERE EXISTS(SELECT * 
                                        FROM dc_facturas as dcf 
                                        WHERE dcf.id_pagos = ap.id_pago) and ap.id_prospecto = '$post' and status_dc = 'enviado';";

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
        public function subirFacturas($id,$pay,$nName,$nName1){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                $post = [
                    'id_us'=> $id,
                    'idPayment'=> $pay,
                    'file_pdf' => $nName,
                    'file_xml' => $nName1
                ];

				$sql = "INSERT INTO `dc_facturas`
						(`id_prospecto`,`id_pagos`,`link_pdf`,`link_xml`) 
						VALUES (:id_us,:idPayment,:file_pdf,:file_xml);";
				$statement = $con->prepare($sql);
				$statement->execute($post);


				if($statement->errorInfo()[0] == 00000){


                    $sql1 = "UPDATE `dc_facturas`
                            SET status_dc = 'changedforerror'
						    WHERE id_pagos = '$pay' and status_dc = 'eliminado'";
				    $statement1 = $con->prepare($sql1);
				    $statement1->execute();
                    $row = $statement1->rowCount();
                    if($row > 0){
                        $response = ["estatus"=>"ok", "data"=>$statement1->rowCount()];
                    }else{
                        $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
                    }
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
        public function cRequest($post){


            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];


				$sql = "UPDATE `d_facturacion`
                        SET `change_request` = :change
                        WHERE id_prospecto = :id_us";
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
        public function bFacturar($post){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                if($post['idU'] != 'undefined'){
                    $id = $post['idU'];
                    $where = "id_prospecto = '$id'";
                }else{
                    if($post['option'] == 'poraprobar'){
                        $where = "(status_data = '' or status_data = 'rechazado') and change_request = ''";
                    }else{
                        $where = "change_request != ''";
                    }
                }

                $sql = "SELECT df.*,afc.nombre,afc.aPaterno,afc.aMaterno 
                        FROM d_facturacion as df 
                        JOIN a_prospectos as afc on afc.idAsistente  = df.id_prospecto
                        WHERE $where";     
                    $statement = $con->prepare($sql);
                    $statement->execute();
                    //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
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
        public function saveStatus($post){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                $sql = "UPDATE d_facturacion as df
                        SET status_data = :statusF, reason_of_rejection = :reason, change_request = :changeR
                        WHERE id = :idTable";     
                    $statement = $con->prepare($sql);
                    $statement->execute($post);
                    //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
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
        public function deleteBill($post){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                $sql = "UPDATE dc_facturas as df
                        SET status_dc = 'eliminado'
                        WHERE id = :deletefacts";     
                    $statement = $con->prepare($sql);
                    $statement->execute($post);
                    //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
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
