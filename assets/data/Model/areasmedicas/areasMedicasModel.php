<?php
date_default_timezone_set("America/Mexico_City");

class areasMedicas{


    public function getGen($post,$type,$idp){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];

            $join = '';
            $whr = "";

            if($type == 1){
                $join = "JOIN pm_procedimiento_carrera as pmc on pmc.id_generacion = ag.idGeneracion";
                $whr = "and pmc.id_proc = '$idp'";
            }
            
            $id = isset($post['idC']) ? $post['idC'] : $post ;
            
            $sql = "SELECT DISTINCT ag.idGeneracion,ag.nombre
                    FROM a_generaciones as ag
                    JOIN a_carreras as ac on ac.idCarrera = ag.idCarrera
                    {$join}
                    WHERE ac.idCarrera = '$id' {$whr};";
    
            $statement = $con->prepare($sql); 		  
            $statement->execute();

            $response = ['estatus'=>'ok','data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            $conexion = null;
            $con = null;
            return $response;
        }

    }
    public function getCarreraPro($id,$offs){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];

            if($offs == 1){
                $sel = "DISTINCT ac.idCarrera,ac.nombre as ncarrera";
                $whr = "pp.idpm = '$id'";
            }else{
                $sel = "DISTINCT pp.idpm,pp.nombre as nprocd";
                $whr = "ac.idCarrera = '$id'";
            }
            
            $sql = "SELECT $sel
                    FROM a_carreras as ac
                    LEFT JOIN pm_procedimiento_carrera as pmc on pmc.id_carrera = ac.idCarrera
                    LEFT JOIN pm_procedimientos as pp on pp.idpm = pmc.id_proc
                    WHERE  $whr ORDER BY ac.idCarrera;";
    
            $statement = $con->prepare($sql); 		  
            $statement->execute();

            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
            $conexion = null;
            $con = null;
            return $response;
        }

    }
    public function getProceTuto($id,$offs){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];

            if($offs == 1){
                $sel = "DISTINCT ac.idCarrera,ac.nombre as ncarrera";
                $whr = "pp.idpm = '$id'";
            }else{
                $sel = "DISTINCT pp.idpm,pp.nombre as nprocd";
                $whr = "ac.idCarrera = '$id'";
            }
            
            $sql = "SELECT $sel
                    FROM a_carreras as ac
                    LEFT JOIN pm_procedimiento_carrera as pmc on pmc.id_carrera = ac.idCarrera
                    LEFT JOIN pm_procedimientos as pp on pp.idpm = pmc.id_proc
                    WHERE  $whr ORDER BY ac.idCarrera;";
    
            $statement = $con->prepare($sql); 		  
            $statement->execute();

            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
            $conexion = null;
            $con = null;
            return $response;
        }

    }
    public function getCarreraMa($id){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            
            $sql = "SELECT ac.nombre as ncarrera,ac.idCarrera
                    FROM a_carreras as ac
                    LEFT JOIN maestros_carreras as mc on mc.idCarrera = ac.idCarrera
                    WHERE mc.idMaestro = '$id' ORDER BY ac.idCarrera;";
    
            $statement = $con->prepare($sql); 		  
            $statement->execute();

            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
            $conexion = null;
            $con = null;
            return $response;
        }

    }
    public function consultarProced(){
        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            
            $sql = "SELECT pm.*
                    FROM pm_procedimientos as pm";
    
            $statement = $con->prepare($sql); 		  
            $statement->execute();

            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
            $conexion = null;
            $con = null;
            return $response;
        }
    }
    public function consultarTutores(){
        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            
            $sql = "SELECT * 
                    FROM maestros WHERE rolem != 1";
    
            $statement = $con->prepare($sql); 		  
            $statement->execute();			
            
            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                
            $conexion = null;
            $con = null;
            return $response;
        }
    }
    public function consultarStu(){
        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            
            $sql = "SELECT ap.*,ag.nombre as nGen,ac.nombre as nCar,ac.idCarrera
                    FROM a_prospectos as ap
                    JOIN alumnos_generaciones as alg on alg.idalumno = ap.idAsistente
                    JOIN a_generaciones as ag on ag.idGeneracion = alg.idgeneracion
                    JOIN a_carreras as ac on ac.idCarrera = ag.idCarrera
                    WHERE ag.idCarrera = 14 or ag.idCarrera = 19;";
                    
            $statement = $con->prepare($sql);
            $statement->execute();			
            
            $response = $statement->fetchAll(PDO::FETCH_ASSOC);
                
            $conexion = null;
            $con = null;
            return $response;
        }
    }
    public function saveValues($post){
        //var_dump($post);

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 

        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            $date = date('Y-m-d H:i:s');
            
            if(isset($post['pdf'])){
                $post['dateR'] = $date;
                $set = "SET `link_cv`= :pdf, `date_upload_cv`= :dateR, `descripcion` = :descp";
            }else if(isset($post['typeU'])){    
                $set = "SET `estado`= :typeU";
            }else{
                $set = "SET `nombres`= :names,`aPaterno`= :apa,`aMaterno`=:ama,`sexo`=:gen,`email`=:email,`telefono`=:tel,
                `celular`=:cel,`telefono_trabajo`=:telt,`telefono_recados`=:telr,`descripcion`= :descp,
                `rolem`=:roles";
            }

            $sql = "UPDATE `maestros` {$set} WHERE id = :idM";

            // var_dump($sql,$post);
            // die();
    
            $statement = $con->prepare($sql); 		  
            $statement->execute($post);			  

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>'ok', "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>'error', "data"=>$statement->errorInfo()];
            }
            
        }else{
            $response = ["estatus"=>'error', "data"=>'error de conexión'];
        }

        $conexion = null;
            $con = null;
            return $response;

    }
    public function saveProced($post){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 

        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            $date = date('Y-m-d H:i:s');
            
            if(isset($post['pdf'])){
                $post['dateR'] = $date;
                $post['pdf'] = json_encode($post['pdf']);
                $post['descp'] = json_encode($post['descp']);
                
                $set = "SET `archivo`= :pdf, `dateUploadFile` = :dateR, `idEmployed` = :idP,`descripcion`=:descp";
            }else if(isset($post['typeU'])){    
                $set = "SET `estado`= :typeU";
            }else{
                $set = "SET `nombre`= :name,`costo`=:costo";
            }

            if(isset($post['idM'])){
                $sql = "UPDATE `pm_procedimientos` {$set} WHERE idpm = :idM";
            }else{
                $post['names'] = $post['name'];
                unset($post['name']);
                $sql = "INSERT INTO `pm_procedimientos`(`nombre`, `costo`) 
                VALUES (:names,:costo)";
            }
            
            $statement = $con->prepare($sql); 		  
            $statement->execute($post);			  

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>'ok', "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>'error', "data"=>$statement->errorInfo()];
            }
            
        }else{
            $response = ["estatus"=>'error', "data"=>'error de conexión'];
        }

        $conexion = null;
            $con = null;
            return $response;

    }
    public function assignPro($post){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 

        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            $date = date('Y-m-d H:i:s');
            $typeO = $post['typeOperation'];
            unset($post['typeOperation']);
            unset($post['idPro']);

            if($typeO == 1){

                $select = "SELECT * FROM `pm_procedimiento_carrera` WHERE `id_proc` = :idPros and `id_carrera` = :idCar and `id_generacion` = :idGen";

                $statement = $con->prepare($select); 		  
                $statement->execute($post);
                $row = $statement->rowCount();
                if($row > 0){
                    $sql = $select;
                }else{
                    $sql = "INSERT INTO `pm_procedimiento_carrera`(`id_proc`, `id_carrera`, `id_generacion`) 
                    VALUES (:idPros,:idCar,:idGen)";
                }
            }else{
                $sql = "DELETE FROM `pm_procedimiento_carrera` WHERE `id_proc` = :idPros and `id_carrera` = :idCar and `id_generacion` = :idGen";
            }

            $statement = $con->prepare($sql); 		  
            $statement->execute($post);			  

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>'ok', "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>'error', "data"=>$statement->errorInfo()];
            }
            
        }else{
            $response = ["estatus"=>'error', "data"=>'error de conexión'];
        }

        $conexion = null;
            $con = null;
            return $response;

    }
    public function assignTut($post){       
        
        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];

            $typeO = $post['typeOperation'];
            unset($post['typeOperation']);
            unset($post['idM']);
            unset($post['carrer']);

            if($typeO == 1){

                $select = "SELECT * FROM `maestros_carreras` WHERE `idCarrera` = :carrers and `idMaestro` = :idMs";

                $statement = $con->prepare($select); 		  
                $statement->execute($post);
                $row = $statement->rowCount();
                if($row > 0){
                    $sql = $select;
                }else{
                    $sql = "INSERT INTO `maestros_carreras`(`idCarrera`, `idMaestro`) 
                    VALUES (:carrers,:idMs)";
                }
            }else{
                $sql = "DELETE FROM `maestros_carreras` WHERE `idCarrera` = :carrers and `idMaestro` = :idMs";
            }
    
            $statement = $con->prepare($sql); 		  
            $statement->execute($post);

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>'ok', "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>'error', "data"=>$statement->errorInfo()];
            }

            $conexion = null;
            $con = null;
            return $response;
        }
    }
    public function addBitacoras($post){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];

            $idCa = $post['idCa'];
            unset($post['idCa']);
            $select = "SELECT idMaestro FROM `maestros_carreras` WHERE `idCarrera` = '$idCa'";

            $statementS = $con->prepare($select); 		  
            $statementS->execute();
            $row = $statementS->rowCount();
            $datas = $statementS->fetchAll(PDO::FETCH_ASSOC);

            if($row > 0){
                foreach($datas as $data){
                    $post['idT'] = $data['idMaestro'];
                    
                    $sql = "INSERT INTO `pm_bitacora_tutor_alumno`(`id_tutor`, `id_alumno`, `id_generacion`) 
                    VALUES (:idT,:idA,:idGen)";
                    //var_dump($post);
                     $statement = $con->prepare($sql); 		  
                     $statement->execute($post);
                }
            }
            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>'ok', "data"=>$statement->rowCount()];
            }else{
                $response = ["estatus"=>'error', "data"=>$statement->errorInfo()];
            }

            $conexion = null;
            $con = null;
            return $response;
        }
    }
    public function consultarStuProce($idA){

        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
    
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];

            $whr = '';
            // if($idA > 0){
            //    $whr = " WHERE pe.idalumno = '$idA'" ;
            // }else{
            //     $whr = " WHERE pe.idtutor is NULL or pe.idtutor = 0" ;
            // }
            $select = "SELECT CONCAT(me.nombres,' ',me.aPaterno,' ',me.aMaterno) as ntutor, pe.paciente, pe.idtutor,
            pe.comentarios,pmp.nombre,pms.nombre as nsitio,pe.frealizacion,CONCAT(ap.nombre,' ',ap.aPaterno,' ',ap.aMaterno) as nombreA, pe.idalumno, pe.idexp
            FROM pm_expedientes as pe
            LEFT JOIN maestros as me on pe.idtutor = me.id
            jOIN pm_procedimientos as pmp on pmp.idpm = pe.idpm
            JOIN pm_sitios as pms on pms.idsitio = pe.idsitio
            JOIN a_prospectos as ap on ap.idAsistente = pe.idalumno
            {$whr}
            ";

            $statementS = $con->prepare($select); 		  
            $statementS->execute();
            $statement = $con->prepare($select); 		  
            $statement->execute();
            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>'ok', "data"=>$statementS->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ["estatus"=>'error', "data"=>$statement->errorInfo()];
            }

            $conexion = null;
            $con = null;
            return $response;
        }
    }

    public function updateCirugia($data){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        if($con["info"] == "ok"){
            $con = $con["conexion"];
            $case = $data["case"];
            unset($data["case"]);
            switch($case){
                case 'create':
                    $sql = "INSERT INTO pm_expedientes(idpm, idalumno, paciente, idtutor, idmedico, fcreacion, factualizacion, frealizacion, estado, idsitio, comentarios) 
                            VALUES ( '1', '11', 'Juana', '50', '50', NOW(),NOW(), '2022-08-02', '1', '1', NULL)";
                    break;
                case 'edit':
                    $sql = "UPDATE pm_expedientes 
                            SET paciente = 'Monicas', idtutor = '50', idmedico = '50',  NOW(), frealizacion = '2022-08-10', estado = '2', idsitio = '2', comentarios = 'En recuperacións' 
                            WHERE idexp = 2;";
                    break;
                case 'list':
                    $id = "";
                    if(isset($data["idexp"])){
                        $id = " WHERE idexp = :idexp"; 
                    }
                    $sql = "SELECT * FROM pm_expedientes{$id};";
                    break;
            }
            $statement = $con->prepare($sql); 		  
            $statement->execute($data);
          
            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>'ok', "data"=>$case == "create" || $case == "edit" ? $statement->rowCount() : $statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ["estatus"=>'error', "data"=>$statement->errorInfo()];
            }

            $conexion = null;
            $con = null;
            return $response;
        }
    }
}




?>