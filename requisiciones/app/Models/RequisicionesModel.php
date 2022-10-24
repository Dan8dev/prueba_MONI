<?php
use FontLib\Table\Type\post;

date_default_timezone_set('America/Mexico_City');
    require '../../../assets/data/Model/conexion/conexion.php';

    class DataRequest{

        public function registrarProveedores($post){
			$conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                    $rfc = $post['rfc']; 
                    $idarea = $post['idArea'];
                    $typeData = isset($post["typeData"]) ? $post["typeData"] : NULL;
                    $idprov = isset($post["idProv"]) ? $post["idProv"] : false;

                    unset($post['idArea']);
                    unset($post['typeData']);
                    unset($post["idProv"]);

                    if($typeData != 'editP'){
                        $sql = "SELECT id_prov FROM tb_proveedor WHERE n_rfc = '$rfc'";
                        $statement = $con->prepare($sql);
                        $statement->execute();
                        $r = $statement->rowCount();
                        $idprov = $r > 0 ? $statement->fetch(PDO::FETCH_ASSOC)['id_prov'] : false;
                    }


                    if($idprov){
                        
                        $sql = "UPDATE `tb_proveedor`
                        SET  `tipo_act` = :activity, `tipo_reg` = :regimen,`nrazon` = :nrazon ,`n_rfc` = :rfc, `calle` = :street,`n_ext` = :numberE,
                        `n_int` = :numberI,`colonia` = :neighborhood,`ciudad` = :city,`estado` = :stateD,`cp` = :cp,`email` = :email,`telefono` = :tel, `nombre_banco` = :bank, 
                        `num_cuenta` = :acountB, `num_clabe` = :clabeB
                        WHERE id_prov = '$idprov'";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);

                        if($statement->errorInfo()[0] == 00000){

                            $recycler = new DataRequest();
                            $response = $recycler->registryProvArea($idprov,$idarea);
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                        }
                    }else{

                        $sql = "INSERT INTO `tb_proveedor`
                        (`tipo_act`,`tipo_reg`,`nrazon`,`n_rfc`, `calle`,`n_ext`,
                        `n_int`,`colonia`,`ciudad`,`estado`,`cp`,`email`,`telefono`,`nombre_banco`, 
                        `num_cuenta`,`num_clabe`)
                        VALUES (:activity,:regimen,:nrazon,:rfc,:street,:numberE,:numberI,:neighborhood,:city,:stateD,:cp,:email,:tel,:bank,:acountB,:clabeB);";
                        $statement = $con->prepare($sql);
                        // var_dump($post);
                        // die();
                        $statement->execute($post);

                        if($statement->errorInfo()[0] == 00000){

                            $recycler = new DataRequest();
                            $idprov = $con->lastInsertId();
                            $response = $recycler->registryProvArea($idprov,$idarea);
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$post];
                        }
                    }
				
			}else{
				$response = ["estatus"=>"error","data"=>"error de conexion",'conne'=>$con];
			}

			$conexion = null;
			$con = null;
			return $response;			
		}
        public function registryProvArea($idprov,$idarea){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                $data = [
                    'idprov'=>$idprov,
                    'idarea'=>$idarea,
                ];

                $select = "SELECT * FROM `tb_prov_areas_join` WHERE `id_prov_join` = '$idprov' AND `id_area_join` = '$idarea'";
                $statement = $con->prepare($select);
                $statement->execute();
                $r = $statement->rowCount();

                if($r > 0){
                    $response = ["estatus"=>"ok", "data"=>$idprov];   
                }else{
                    $sql = "INSERT INTO `tb_prov_areas_join`(`id_prov_join`, `id_area_join`) 
                    VALUES (:idprov,:idarea)";
                    $statement = $con->prepare($sql);
                    $statement->execute($data);

                if($statement->errorInfo()[0] == 00000){
                    
                    $response = ["estatus"=>"ok", "data"=>$idprov];
                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$data];
                }
                }
				
			}else{
				$response = ["estatus"=>"error","data"=>"error de conexion",'conne'=>$con];
			}

			$conexion = null;
			$con = null;
			return $response;			
        }
        public function obtenerProveedores($idarea){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

            $where = '';
          
            if($idarea != '5'){
                $where = "WHERE id_area_join = '$idarea'";
            }
			if($con["info"] == "ok"){
				$con = $con["conexion"]; 

               
                
            $sql = "SELECT tbr.*, tbj.id_area_join as tbArea
                    FROM tb_proveedor as tbr 
                    JOIN tb_prov_areas_join as tbj on tbj.id_prov_join = tbr.id_prov 
                    {$where}
                    ORDER BY id_prov;";
                    $statement = $con->prepare($sql);
                    $statement->execute();


                    $response = $statement;
    
                    $conexion = null;
                        $con = null;
                        return $response;	
            }	
        }
        public function subirReq($post){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];
                
                $folio = $post['folio'];
                $sql1 = "SELECT folio FROM tb_requisiciones WHERE folio = '$folio';";
                $statement = $con->prepare($sql1);
                $statement->execute();
                $r = $statement->rowCount();

                if($r > 0){

                    $sql1 = "SELECT MAX(folio) as maxf FROM tb_requisiciones;";
                    $statement = $con->prepare($sql1);
                    $statement->execute();
                    $post['folio'] = $statement->fetch(PDO::FETCH_ASSOC)['maxf'];
                    $date = date('Ym');
                    $consecutive = explode('-',$post['folio']);
                    $fols= $date.'-'.($consecutive[1]+1);
                    $post['folio'] = $fols;
                }

                $sql = "INSERT INTO `tb_requisiciones`(`id_prov_req`, `id_us_req`, `tipo_req`,`folio`,`cantidad`, `unidad`, 
                        `concepto`, `modelo`, `marca`, `link_ref_comp`, `precio`, `subtotal`) 
                        VALUES (:prov,:idUs,:op,:folio,:cant,:uni,:concp,:model,:mark,:linkBuy,:price,:subto);";
                $statement = $con->prepare($sql);
                $statement->execute($post);

                if($statement->errorInfo()[0] == 00000){
                    $date = date('Ym');
                    $consecutive = explode('-',$post['folio']);
                    $fols= $date.'-'.($consecutive[1]+1);

                    file_put_contents("../utils/utils.txt", strval($fols));
                    $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$post];
                }

			}else{
				$response = ["estatus"=>"error","data"=>"error de conexion",'conne'=>$con];
			}

			$conexion = null;
			$con = null;
			return $response;			 
        }
        public function obtenerRequest($post){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                $id = $post['idUs'];
                $option = $post['option'];
                $option = str_replace('s',"",$option);

                if(isset($_POST['viewAdmin'])){
                
                    $whereU = "";
                    $join = '';
                    $sel = '';
                    $joinRe = '';
                    $where = "WHERE status_req = '$option'";

                    if($option == 'rechazada'){
                        $join = "JOIN tb_declined as tbd on tbd.id_req_reason = tbr.id_req"; 
                        $sel = 'tbd.d_reason,';
                    }

                    if($post['tAccess'] == 4){
                        $where = "WHERE status_req = '$option' and tipo_req = 'transferencia'";
                    }

                    if($option == 'pagada'){
                        $where = "WHERE NOT EXISTS (SELECT * FROM tb_comp_pago as tbc WHERE tbc.id_req_com = tbr.id_req) and status_req = '$option';";
                        $join = "LEFT JOIN tb_comp_pago as tbc on tbc.id_req_com = tbr.id_req";
                    }
                    if($option == 'pendiente'){
                        $joinRe = 'LEFT JOIN tb_req_autorizadas as tbra on tbra.folio = tbr.folio';
                        $sel = 'tbra.folio as tbraFolio,';
                    }
                    if($option == 'facturada'){
                        $join = "JOIN tb_facturas as tbf on tbf.id_us_fc = app.idPersona"; 
                    }

                    $select = "tbr.folio,app.idPersona, app.nombres, app.apellidoPaterno,app.apellidoMaterno,
                    tbr.fecha_req,tbr.fecha_pag,tbr.fecha_apro,tbr.fecha_decl,tba.nombre_area,$sel
                    (SELECT SUM(tbr1.subtotal) From tb_requisiciones as tbr1 WHERE tbr.folio = tbr1.folio ) as total";
                }else{
                    $sel = '';
                    $join = '';
                    $joinRe = '';
                    $whereU = "WHERE app.idPersona = '$id'";
                    $where = "and status_req = '$option'";

                    if($option == 'rechazada'){
                       
                        $join = "JOIN tb_declined as tbd on tbd.id_req_reason = tbr.id_req";
                        $sel = 'tbd.d_reason,';
                    }
                    $select = "tbr.folio,tbr.fecha_req,tbr.fecha_pag,tbr.fecha_apro,tbr.fecha_decl,$sel
                    (SELECT SUM(tbr1.subtotal) FROM tb_requisiciones as tbr1 WHERE tbr.folio = tbr1.folio ) as total";

                    if($option == 'facturada'){
                        $join = "JOIN tb_facturas as tbf on tbf.id_us_fc = app.idPersona"; 
                    }
                }

            $sql = "SELECT  $select
                    FROM tb_requisiciones as tbr
                    JOIN tb_proveedor as tbp on tbp.id_prov = tbr.id_prov_req
                    JOIN a_plan_pagos as app on app.idPersona = tbr.id_us_req
                    JOIN tb_areas as tba on tba.id_area = app.col_area
                    $join
                    $joinRe
                    $whereU$where;";
                    $statement = $con->prepare($sql);
                    $statement->execute();
                    $response = $statement;
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function obtenerBreakdown($post){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

            $id = $post['idUs'];
            $folio = $post['folio'];
            $us = $_POST['us'];
            unset($_POST['us']);
            $option = str_replace('s','',$post['option']);
			if($con["info"] == "ok"){
				$con = $con["conexion"];

                $join = '';

                if($option == 'rechazada'){
                    $join = "JOIN tb_declined as tbd on tbd.id_req_reason = tbr.id_req";
                }

                if($option == 'comprobanteincorrecto' || $option == 'facturaincorrecto'){
                    $join = " JOIN tb_declined_comp_fac as tbfc on tbfc.id_req_d = tbr.id_req";
                }
                //var_dump($option);

                if($us == 'admin'){
                    $where = "WHERE tbr.folio = '$folio' and status_req = '$option';";
                    if($option == 'pagada'){
                        $where = "WHERE NOT EXISTS (SELECT * FROM tb_comp_pago as tbc WHERE tbc.id_req_com = tbr.id_req) and status_req = '$option';";
                        $join = "LEFT JOIN tb_comp_pago as tbc on tbc.id_req_com = tbr.id_req";
                    }
                }else{
                    $where = "WHERE app.idPersona = '$id' and tbr.folio = '$folio' and status_req = '$option';";
                    if($option == 'pagada'){
                        $where = "WHERE EXISTS (SELECT * FROM tb_comp_pago as tbc WHERE tbc.id_req_com = tbr.id_req) and status_req = '$option';";
                        $join = "LEFT JOIN tb_comp_pago as tbc on tbc.id_req_com = tbr.id_req";
                    }
                }

            $sql = "SELECT  *
                    FROM tb_requisiciones as tbr
                    JOIN tb_proveedor as tbp on tbp.id_prov = tbr.id_prov_req
                    JOIN a_plan_pagos as app on app.idPersona = tbr.id_us_req
                    $join
                    $where";
                    $statement = $con->prepare($sql);
                    $statement->execute();
                    $response = $statement;
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function updateReq($post){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

            $status = $post['status'];
            $idReq = $post['idReq'];
            $id = $post['idAd'];

            if(isset($post['reason'])){
                $reason = $post['reason'];
            }else{
                $reason = '';
            }
            
			if($con["info"] == "ok"){
				$con = $con["conexion"];

                $date = date('Y-m-d H:i:s');

                
                $numMov = isset($post['numMov']) ? $post['numMov'] : NULL;
                $numCosto = isset($post['numCosto']) ? $post['numCosto'] : NULL;
                $seNum = '';
                $set = '';
                $setCost = '';

                if($numMov != '' && $numMov != NULL){
                    $seNum = ", num_mov_pago = '$numMov'";
                }
                if($numCosto != '' && $numCosto != NULL){
                    $setCost = ", centro_costo = '$numCosto'";
                }
                // var_dump($post);
                // die();
                if($status == 'pagada'){
                    $set = ",id_admin_pay = '$id', fecha_pag = '$date'";

                }else if($status == 'aprobada' || $status == 'rechazada'){

                    if($status == 'aprobada'){
                        $dtime = ",fecha_apro = '$date'";
                    }else{
                    $dtime = ",fecha_decl = '$date'";
                    }   
                    $set = ",id_admin_approved = '$id'$dtime";

                }

            $sql = "UPDATE tb_requisiciones
                    SET status_req = '$status'$set$seNum$setCost
                    WHERE id_req = '$idReq';";
                    $statement = $con->prepare($sql);
                    $statement->execute();

                if($statement->errorInfo()[0] == 00000){

                    $recycler = new DataRequest();

                    $data = [
                        'idReq'=>$idReq,
                        'reason'=>$reason,
                    ];

                    if($reason != ''){
                        $response = $recycler->insertReason($data);
                    }else{
                        $response =  ["estatus"=>"ok", "data"=>$statement->rowCount()];
                    }
                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$post];
                }
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function saveFiles($post){
            
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

            $type = $post['fileSave'];
            $folio = $post['idReq'];
            $id = $post['idUs'];
            $sta = '';
            $numMov = isset($post['numMov']) ? $post['numMov'] : NULL;  
            unset($post['numMov']);
            unset($post['fileSave']);
            
			if($con["info"] == "ok"){
				$con = $con["conexion"];

                if($type == 'saveComp'){

                    if(isset($post['idComp'])){

                        $idCom = $post['idComp'];
                        $file = $post['files'];
                        $sql = "UPDATE `tb_comp_pago` 
                                SET `link_comp`='$file'
                                WHERE id_comp_pago = '$idCom'";
                        $statement = $con->prepare($sql);
                        $statement->execute();

                    }else{

                        $sql = "INSERT INTO `tb_comp_pago`(`id_req_com`, `id_com_us`, `date_pago`, `date_reg`, `link_comp`) 
                        VALUES (:idReq,:idUs,:datePago,:dateReg,:files)";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);
                    }
                    $sta = 'pagada';
                }else if($type == 'saveFac'){

                    $sta = 'facturada';

                    if(isset($post['idComp'])){

                        $idCom = $post['idComp'];
                        $pdf = $post['file_pdf'];
                        $xml = $post['file_xml'];
                        $sql = "UPDATE `tb_comp_pago` 
                                SET `link_pdf` = '$pdf', `link_xml` ='$xml'
                                WHERE id_fc = '$idCom'";
                        $statement = $con->prepare($sql);
                        $statement->execute();

                    }else{
                        $sql = "INSERT INTO `tb_facturas`(`id_us_fc`, `id_req_fact`, `link_pdf`, `link_xml`, `status_fac`) 
                        VALUES (:idUs,:idReq,:file_pdf,:file_xml,'enviado')";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);
                    }
                
                }else{
                    
                    $sql = "INSERT INTO `tb_req_autorizadas`(`folio`,`date_auth`, `link_file`, `id_us_upload`) 
                    VALUES (:idReq,:datePago,:files,:idUs)";
                    $statement = $con->prepare($sql);
                    $statement->execute($post);

                }
        
                if($statement->errorInfo()[0] == "00000"){
                    
                    $data = [
                        'idReq'=>$folio,
                        'status'=>$sta,
                        'idAd'=>$id,
                        'numMov'=>$numMov,
                    ];

                    if($type == 'saveSigns'){
                        $response = ['estatus'=> 'ok', 'data'=> $id];
                    }else{
                        $recicler = new DataRequest;
                        $response = $recicler->updateReq($data);
                    }

                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$post];
                }
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function updateSerie($id,$serie){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
            
			if($con["info"] == "ok"){
				$con = $con["conexion"];

            $sql = "UPDATE tb_requisiciones
                    SET num_serie = '$serie' 
                    WHERE id_req = '$id';";
                    $statement = $con->prepare($sql);
                    $statement->execute();

                if($statement->errorInfo()[0] == 00000){
                    
                    $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$serie];
                }
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function insertReason($data){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];
            
			if($con["info"] == "ok"){
				$con = $con["conexion"];

            $sql = "INSERT INTO `tb_declined`(`id_req_reason`, `d_reason`) 
                    VALUES (:idReq,:reason);";
                    $statement = $con->prepare($sql);
                    $statement->execute($data);

                if($statement->errorInfo()[0] == 00000){
                    
                    $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                }else{
                    $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$data];
                }
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function obtenerDocs($id){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

            $sql = "SELECT tbc.link_comp,tbf.link_pdf,id_com_us,id_comp_pago,id_fc,tbr.id_req,id_us_fc
                    FROM tb_comp_pago as tbc
                    JOIN tb_requisiciones as tbr on tbr.id_req = tbc.id_req_com
                    LEFT JOIN tb_facturas as tbf on tbf.id_req_fact = tbr.id_req
                    WHERE tbr.id_req = '$id'";
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
        public function changeDocs($post){

            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];



                $cond = $post['cond'];
                $idR = $post['idReq'];
                $id = $post['idUs'];
                $type = $post['tipo'];
                if($cond == 'uncorrect'){

                    unset($post['cond']);
                    unset($post['idUs']);
                    
                    $sql = "INSERT INTO `tb_declined_comp_fac`(id_req_d, `id_compcf`, `id_factcf`, `tipo`, `motivo`, `status_recived`) 
                    VALUES (:idReq,:comprobante,:factura,:tipo,:errorDoc,'pendiente')";
                    $statement = $con->prepare($sql);
                    $statement->execute($post);
                    if($statement->errorInfo()[0] == 00000){


                        if($type == 'comprobante'){
                            $ty = 'comprobanteincorrecto';
                        }else {
                            $ty = 'facturaincorrecto';
                        }
                        $data = [
                            'idReq'=>$idR,
                            'status'=>$ty,
                            'idAd'=>$id,
                        ];
                        $recicler = new DataRequest;
                        $response = $recicler->updateReq($data);
                    }else{
                        $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                    }
                }else{
                    $data = [
                        'idReq'=>$idR,
                        'status'=>'archivada',
                        'idAd'=>$id,
                    ];
                    $recicler = new DataRequest;
                    $response = $recicler->updateReq($data);
                }    
            }else{
				$response = ["estatus"=>"error","data"=>"error de conexion"];
			}

            $conexion = null;
			$con = null;
			return $response;	
        }
        public function Depto($post){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                if(isset($post['typeAc'])){
                    $sql = "UPDATE `tb_areas` 
                            SET status_area = :typeAc WHERE id_area = :idDpto";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);
                        if($statement->errorInfo()[0] == 00000){
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                        }
                    
                }else{
                        $sql = "INSERT INTO `tb_areas`(`nombre_area`, `status_area`) 
                                VALUES (:dpto,:statusD)";
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
        public function obtenerDepto(){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

            $sql = "SELECT * FROM `tb_areas` WHERE status_area = 'active'";
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
        public function getUsers($id,$type){
            $conexion = new Conexion();
                $con = $conexion->conectar();
                $response = [];
    
                if($con["info"] == "ok"){
                    $con = $con["conexion"];
                    $filter = '';
        
                // if($type != 31){
                //         $filter = 'and (acc.estatus_acceso != 1)';
                //     }
    
                $sql = "SELECT ce.nombres,ce.apellidoPaterno,ce.apellidoMaterno,acc.correo as email,
                        ce.estatus,ce.idPersona,acc.estatus_acceso,tba.nombre_area,ce.col_area
                        FROM a_plan_pagos as ce
                        JOIN a_accesos as acc on acc.idPersona = ce.idPersona
                        JOIN tb_areas as tba on tba.id_area = ce.col_area
                        WHERE acc.idTipo_Persona = '$type' and acc.idPersona != '$id'";
                        $statement = $con->prepare($sql);
                        $statement->execute();
    
                    $response = $statement;
    
                    $conexion = null;
                        $con = null;
                        return $response;	
    
                }

        }
        public function setUsers($post){
            $conexion = new Conexion();
            $con = $conexion->conectar();
            $response = [];
    
            if($con["info"] == "ok"){
                $con = $con["conexion"];
    
                if(isset($post['typeData'])){
    
                    $idP = $post['idTP']; 
                    unset($post['idTP']);
                    unset($post['idUsM']);
                    $idUs = $post['idUs'];
                    
    
                    if($post['typeData'] == 'editU'){
                        $email = $post['email'];
                        $roles = $post['roles'];
                        $names = $post['names'];
                        $apa = $post['apa'];
                        $ama = $post['ama'];
                        $tel = '1234567890';
                        $area = $post['dptouser'];

                        $sql = "UPDATE `a_accesos` SET `correo` = '$email', `estatus_acceso` = '$roles'
                        WHERE idTipo_Persona = '$idP' and idPersona = '$idUs'";
                        $sql1 = "UPDATE `a_plan_pagos` 
                        SET `nombres`= '$names',`apellidoPaterno`= '$apa',`apellidoMaterno`= '$ama',
                        `telefono`= '$tel', `col_area`= '$area'
                        WHERE `idPersona` = '$idUs'";

                        $statement1 = $con->prepare($sql1);
                        $statement1->execute();
                        $statement = $con->prepare($sql);
                        $statement->execute();
                        if($statement->errorInfo()[0] == 00000){
                            
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                        }
                    }else{
                    
                        
                        $type = $post['typeData'];
                        
                        $sql = "UPDATE `a_plan_pagos` 
                                SET `estatus`= '$type'
                                WHERE `idPersona` = '$idUs'" ;
                        //echo $sql;
                        $statement = $con->prepare($sql);
                        $statement->execute();
                        if($statement->errorInfo()[0] == 00000){
                            
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                        }
                    }
        
                    
    
                }else{
                    
                    $postOf = [
                        'names'=> $post['names'],
                        'apa' => $post['apa'],
                        'ama' => $post['ama'],
                        'tel' => '1234567890',
                        'dateC' => date('Y-m-d H:i:s'),
                        'dptouser' => $post['dptouser']
                    ];
    
                    $sql = "INSERT INTO `a_plan_pagos`(`nombres`, `apellidoPaterno`, `apellidoMaterno`, `telefono`, `estatus`, `fechacreado`, `col_area`) 
                            VALUES (:names,:apa,:ama,:tel,1,:dateC,:dptouser)";
                    $statement = $con->prepare($sql);
                    $statement->execute($postOf);
                    if($statement->errorInfo()[0] == 00000){
                        $postMail = [
                            'idTP'=>$post['idTP'],
                            'idUs'=> $con->lastInsertId(),
                            'email'=> $post['email'],
                            'roles' =>$post['roles']
                        ];
                        $sql = "INSERT INTO `a_accesos`(`idTipo_Persona`, `idPersona`, `correo`, `contrasenia`, `estatus_acceso`) 
                                VALUES (:idTP,:idUs,:email, aes_encrypt('12345','SistemasPUE21') ,:roles)";
                    $statement = $con->prepare($sql);
                    $statement->execute($postMail);
                    if($statement->errorInfo()[0] == 00000){
                        $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                    }else{
                        $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                    }   
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
        public function updateReqProv($post){
            $conexion = new Conexion();
			$con = $conexion->conectar();
			$response = [];

			if($con["info"] == "ok"){
				$con = $con["conexion"];

                    $sql = "UPDATE `tb_requisiciones` 
                    SET `id_prov_req`=:idProv 
                    WHERE `id_req`= :idReq";

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
