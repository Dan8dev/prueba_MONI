<?php

class ContentEducate{

    function saveCommits($post){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "INSERT INTO `tb_commits_educate`(`id_prospect_commit`,`idClass`,`idGen`, `description`, `created`) 
            VALUES (:idUs,:idClass,:gen,:commitsForm,:dateC)";     
                $statement = $con->prepare($sql);
                $statement->execute($post);
                //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                if($statement->errorInfo()[0] == 00000){
                    $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
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
    function getCommits($idClass,$gen){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "SELECT *
                    FROM tb_commits_educate as tbce
                    JOIN a_prospectos as ap on ap.idAsistente = tbce.id_prospect_commit
                    WHERE idClass = '$idClass' and idGen = '$gen'
                    ORDER BY tbce.created DESC;";     
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
    function blogs($class){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "SELECT * FROM `tb_blogs` WHERE `id_materia` = :idmateria and statusBlog = 1  ORDER By orderB";     
                $statement = $con->prepare($sql);
                $statement->bindParam(':idmateria', $class);
                //$statement->bindParam(':idSession', $session);
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
    function saveBlogs($post){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $idClass = $post['idClass'];

            if(isset($post['idClass']) && $post['idClass'] != ''){

                $imgfo = $post['imgSend'];
                
                if($imgfo != '' && $imgfo != null && $imgfo != 'undefined'){
                    $updateA = "UPDATE tb_blogs SET  foto = '$imgfo'
                    WHERE id_blog = '$idClass'";
                    $statementA = $con->prepare($updateA);
                    $statementA->execute();                    
                }
                
                $data = [
                    'content' => $post['content'],
                    'dateC' => $post['dateC'],
                    'idClass' => $post['idClass'],
                    'title' => $post['title'],
                    'typeB' => $post['typeB'],
                    'files' => json_encode($post['files']),

                ];

                $update = 'UPDATE tb_blogs SET content_blog = :content, modified = :dateC, title = :title,tipo_de_blog = :typeB, archivo = :files
                            WHERE id_blog = :idClass';
                        $statement = $con->prepare($update);
                        $statement->execute($data);
                        //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                        if($statement->errorInfo()[0] == 00000){
                            $select = "SELECT `id_bloq`,orderB FROM tb_blogs WHERE id_blog = '$idClass'";
                            $statementS = $con->prepare($select);
                            $statementS->execute();
                            if($statementS->errorInfo()[0] == 00000){
                                $idB = $statementS->fetch(PDO::FETCH_ASSOC);
                            }
                            $response = ["estatus"=>"ok", "data"=>$statement->rowCount(), 'id'=>$post['idClass'],"idB"=>$idB];
                        }else{
                            $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$update];
                        }
            }else{

                $idMat = $post['idAssign'];
                $type = $post['typeB'];
                $bl = $post['bloqs'];

                $select = " SELECT max(orderB) as cons FROM tb_blogs WHERE id_materia = '$idMat' and  tipo_de_blog = '$type' and id_bloq = '$bl'";

                $statementS = $con->prepare($select);
                $statementS->execute();
                if($statementS->errorInfo()[0] == 00000){
                    $post['orderB'] = $statementS->fetch(PDO::FETCH_ASSOC)['cons'] + 1;
                }else{
                    $post['orderB'] = 1;
                }
                //unset($post['idClass']);    
                //var_dump($post);

                $post['files']  = json_encode($post['files']);
                $post['imgSend'] = $post['imgSend'] != 'undefined' ? $post['imgSend'] : NULL; 

                $sql = "INSERT INTO `tb_blogs`(`id_materia`,`title`, `content_blog`, `created`, `modified`,`foto`,`tipo_de_blog`,`id_bloq`,`orderB`,`archivo`) 
                        VALUES (:idAssign,:title,:content,:dateC,:dateC,:imgSend,:typeB,:bloqs,:orderB,:files)";
                        $statement = $con->prepare($sql);
                        $statement->execute($post);
                        
                        //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                        if($statement->errorInfo()[0] == 00000){
                            $idClass = $con->lastInsertId();
                            $select = "SELECT `id_bloq`,`orderB` FROM tb_blogs WHERE id_blog = '$idClass'";
                            $statementS = $con->prepare($select);
                            $statementS->execute();
                            if($statementS->errorInfo()[0] == 00000){
                                $idB = $statementS->fetch(PDO::FETCH_ASSOC);
                            }
                            $response = ["estatus"=>"ok", "data"=>$idClass, "id"=>$idClass,"idB"=>$idB];
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
    function deleteFilesBlogs($post){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $idClass = $post['idClass'];

            $sql = "UPDATE tb_blogs 
                    SET archivo = :files
                    WHERE id_blog = :idClass";     
                $statement = $con->prepare($sql);
                $statement->execute($post);
                //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                if($statement->errorInfo()[0] == 00000){

                    $select = "SELECT `id_bloq`,orderB FROM tb_blogs WHERE id_blog = '$idClass'";
                    $statementS = $con->prepare($select);
                    $statementS->execute();
                    if($statementS->errorInfo()[0] == 00000){
                        $idB = $statementS->fetch(PDO::FETCH_ASSOC);
                    }
                    $response = ["estatus"=>"ok", "data"=>$statement->rowCount(),"id"=>$idClass,'idB'=>$idB];
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
    function updateContent($post, $files){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $title = $post['title'];
            $des = $post['description'];
            $imgfo = $files['imgSend'];
            if(isset($post['idCarrera'])){
                $id = $post['idCarrera'];
                
                if(isset($imgfo) && $imgfo != null && $imgfo['size'] > 0 && $imgfo != 'undefined'){
                    $name = "carr".$id.$imgfo['name'];
                    $location = "../../../images/educate/".$name;
                    if (move_uploaded_file($imgfo['tmp_name'], $location) ) { 
                        $updateA = "UPDATE a_carreras SET imagen = '".$name."' WHERE idCarrera = '$id'";
                        $statementA = $con->prepare($updateA);
                        $statementA->execute();
                      }
                }
                $sql = "UPDATE a_carreras SET descriptionC = '$des' , title = '$title' WHERE idCarrera = '$id'";
                $valueId = 'Carrera';
                $idm = '';
                
            }else if(isset($post['idMateria'])){
                $id = $post['idMateria'];
                 if(isset($imgfo) && $imgfo != null && $imgfo['size'] > 0 && $imgfo != 'undefined'){
                    $name = "mat".$id.$imgfo['name'];
                    $location = "../../../images/educate/".$name;
                    if (move_uploaded_file($imgfo['tmp_name'], $location) ) { 
                        $updateA = "UPDATE materias SET  imagen = '$name'
                        WHERE id_materia = '$id'";
                        $statementA = $con->prepare($updateA);
                        $statementA->execute();                    
                    }
                }
                $sql = "UPDATE materias SET descriptionM = '$des', title = '$title' WHERE id_materia = '$id'";
                $valueId = 'Materia';
                $idm = '';
                
            }else if(isset($post['idExamen'])){
            
                $id = $post['idExamen'];
                if(isset($imgfo) && $imgfo != null && $imgfo['size'] > 0 && $imgfo != 'undefined'){
                    $name = "ex".$id.$imgfo['name'];
                    $location = "../../../images/educate/".$name;
                    if (move_uploaded_file($imgfo['tmp_name'], $location) ) { 
                        $updateA = "UPDATE cursos_examen SET foto = '$name'
                        WHERE idExamen = '$id'";
                        $statementA = $con->prepare($updateA);
                        $statementA->execute();                    
                    }
                }
                $sql = "UPDATE cursos_examen SET Nombre = '$title' WHERE idExamen = '$id'";

                $select = "SELECT idCurso,id_bloq as idBloq FROM cursos_examen WHERE idExamen = '$id'";

                $statementS = $con->prepare($select);
                $statementS->execute();
                if($statementS->errorInfo()[0] == 00000){
                    $idm = $statementS->fetch(PDO::FETCH_ASSOC);
                } 
                $valueId = 'Examen';
            }else{

                if(isset($post['idBloque'])){
                    $id = $post['idBloque'];
                    if(isset($imgfo) && $imgfo != null && $imgfo['size'] > 0 && $imgfo != 'undefined'){
                        $name = "bloque".$id.$imgfo['name'];
                        $location = "../../../images/educate/".$name;
                        if (move_uploaded_file($imgfo['tmp_name'], $location) ) { 
                            $updateA = "UPDATE tb_bloques SET img_bloq = '$name'
                            WHERE id = '$id'";
                            $statementA = $con->prepare($updateA);
                            $statementA->execute();                    
                        }
                    }
                    $sql = "UPDATE tb_bloques SET title_bloque = '$title', descripB = '$des' WHERE id = '$id'";
                    $select = "SELECT idMat FROM tb_bloques WHERE id = '$id'";

                    $statementS = $con->prepare($select);
                    $statementS->execute();
                    if($statementS->errorInfo()[0] == 00000){
                        $idm = $statementS->fetch(PDO::FETCH_ASSOC)['idMat'];
                    }    
                }else{
                    $idMat = $post['idMateriaB'];
                    $select = "SELECT max(blogs) as cons FROM tb_bloques WHERE idMat = '$idMat'";

                    $statementS = $con->prepare($select);
                    $statementS->execute();
                    if($statementS->errorInfo()[0] == 00000){
                        $consecutive = $statementS->fetch(PDO::FETCH_ASSOC)['cons'] + 1;
                    }else{
                        $consecutive  = 1;
                    }    
                    if(isset($imgfo) && $imgfo['size'] > 0){
                        $name = "matB".$id.$imgfo['name'];
                        $location = "../../../images/educate/".$name;
                        if (move_uploaded_file($imgfo['tmp_name'], $location) ) { 
                            $sql = "INSERT INTO tb_bloques(`blogs`, `idMat`, `title_bloque`, `img_bloq`, `descripB`) 
                            VALUE('$consecutive','$idMat','$title','$name','$des')";        
                        }
                    }
                    $idm = $idMat;
                }
                $valueId = 'Bloque';
            }

                $statement = $con->prepare($sql);
                $statement->execute();
                //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                if($statement->errorInfo()[0] == 00000){
                    $response = ["estatus"=>"ok", "data"=>$statement->rowCount(),"valueId"=>$valueId,'idMat'=>$idm];
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
    function getClass($idM){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "SELECT *
                    FROM tb_blogs as tbb
                    WHERE tbb.id_materia = '$idM' and statusBlog = 1 ORDER BY orderB;";     
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
    function getIDBl($idM){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "SELECT *
                    FROM tb_bloques as tbb
                    WHERE tbb.idMat = '$idM' and statusBloq = 1 ORDER bY blogs;";     
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
    function getExm($idM,$carrer){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
        

        if($carrer != ''){
        
            $where = "and ce.id_generacion = '$idM'";
        }else{
            $where = "and ce.idCurso = '$idM';";
        }
        
        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "SELECT *
                    FROM cursos_examen as ce
                    WHERE statusExm = 1 {$where}";     
                $statement = $con->prepare($sql);
                $statement->execute();
                //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                if($statement->errorInfo()[0] == 00000){
                    $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC),"sql"=>$where];

                    foreach($response['data'] as $key=>$value){
                        
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
    function setExm($post){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "INSERT INTO cursos_examen (`idCurso`,`Nombre`,`id_generacion`,`id_carrera`,`porcentaje_aprobar`,`tipo_examen`,`id_bloq`) 
            VALUE(:idMat,:nombreExamen,:idGen,:idCar,:inp_porcentaje_aprobar_i,'3',:idBl)";    
                $statement = $con->prepare($sql);
                $statement->execute($post);
                //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                if($statement->errorInfo()[0] == 00000){
                    $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
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
    function getGen($carrer){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "SELECT MAX(idGeneracion) as idG
                    FROM a_generaciones
                    WHERE idCarrera = '$carrer'";     
                $statement = $con->prepare($sql);
                $statement->execute();
                //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                if($statement->errorInfo()[0] == 00000){
                    $response = ["estatus"=>"ok", "data"=>$statement->fetch(PDO::FETCH_ASSOC)['idG']];
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
    public function consultar_materias_carrera($idCa,$idciclo){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];

            $sql = "SELECT pl_m.*, mat.*,ac.imagen as imgc, ac.nombre as acNombre, ac.title as acTitle 
            FROM planes_materias pl_m 
            JOIN materias mat ON mat.id_materia = pl_m.id_materia
            LEFT JOIN a_carreras as ac on ac.idCarrera = mat.id_carrera
            WHERE ac.idCarrera = '$idCa' and pl_m.ciclo_asignado = '$idciclo'";

            $statement = $con->prepare($sql);
            $statement->execute();

            if($statement->errorInfo()[0] == '00000'){
                $response = ["estatus"=>"ok", "data"=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ["estatus"=>"error", "info"=>$statement->errorInfo(), "sql"=>$sql];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }
    function setQuiz($id,$ques,$json){

        $post = [
            'idEx'=>$id,
            'quest'=>$ques,
            'options'=>$json
        ];
        
        //var_dump($post);

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "INSERT INTO cursos_examen_preguntas 
                    (idExamen, pregunta, opciones)VALUES(:idEx, :quest, :options)";     
                $statement = $con->prepare($sql);
                $statement->execute($post);
                //var_dump($statement->fetchAll(PDO::FETCH_ASSOC));
                if($statement->errorInfo()[0] == 00000){
                    $response = ["estatus"=>"ok", "data"=>$con->lastInsertId()];
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
    public function buscarIdPregunta($idExamen){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql = "SELECT idPregunta,pregunta,opciones
                FROM cursos_examen_preguntas 
                WHERE idExamen = '$idExamen'
                ORDER BY idPregunta ASC";

            $statement = $con->prepare($sql);
            $statement->execute();

            if($statement->errorInfo()[0] == "00000"){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
            }
        }
        $conexion = null;
        $con = null;
        return $response;
    }
    public function editarPreguntaExamen($idPregunta, $idExamen, $pregunta, $opciones){
        //var_dump($opciones);
        //die();
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];

        if($con['info'] == 'ok'){
            $con = $con['conexion'];
            $sql = "UPDATE cursos_examen_preguntas SET
                pregunta = :question, opciones = :options
                WHERE idPregunta = :idPregunta AND idExamen = :idExam";

            $statement = $con->prepare($sql);
            $statement->bindParam(':idPregunta',$idPregunta);
            $statement->bindParam(':question',$pregunta);
            $statement->bindParam(':options',$opciones);
            $statement->bindParam(':idExam',$idExamen);
            $statement->execute();

            if($statement->errorInfo()[0] == "00000"){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $statement = ['estatus'=>'error', 'info'=>$statement->errorInfo(), 'sql'=>$sql];
            }
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

            $sql = "SELECT ce.nombres,acc.correo as email,ce.estado,ce.id,acc.estatus_acceso
                    FROM controlescolar as ce
                    JOIN a_accesos as acc on acc.idPersona = ce.id
                    WHERE acc.idTipo_Persona = '$type' and acc.idPersona != '$id' and (acc.estatus_acceso != 1)";
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
                $email = $post['email'];
                $roles = $post['roles'];
                $idUs = $post['idUs'];
                $names = $post['names'];

                if($post['typeData'] == 'editU'){
                        $sql = "UPDATE `a_accesos` SET `correo` = '$email', `estatus_acceso` = '$roles'
                        WHERE idTipo_Persona = '$idP' and idPersona = '$idUs'";
                        $sql1 = "UPDATE `controlescolar` 
                        SET `nombres`= '$names' WHERE `id` = '$idUs'";
                        
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
                    $sql = "UPDATE `controlescolar` 
                        SET `estado`= '$type' WHERE `id` = '$idUs'" ;
                    $statement = $con->prepare($sql);
                    $statement->execute($post);
                    if($statement->errorInfo()[0] == 00000){
                        
                        $response = ["estatus"=>"ok", "data"=>$statement->rowCount()];
                    }else{
                        $response = ["estatus"=>"error", "data"=>$statement->errorInfo(), "sql"=>$sql];
                    }
                }

            }else{
                
                $postOf = [
                    'names'=>$post['names'],
                ];

                
                $sql = "INSERT INTO `controlescolar`(`nombres`,`estado`) 
                        VALUES (:names,1)";
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
    function getMaestros($gn){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];


        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $sql = "SELECT me.*
                    FROM maestros as me
                    LEFT JOIN maestros_carreras as mc on mc.idMaestro = me.id
                    LEFT JOIN a_carreras as ac on ac.idCarrera = mc.idCarrera
                    LEFT JOIN a_generaciones as ag on ag.idCarrera = ac.idCarrera
                    WHERE ag.idGeneracion = '$gn' and me.estado = 1";

            $statement = $con->prepare($sql);
            $statement->execute();
            if($statement->errorInfo()[0] == "00000"){
                $response = ['estatus'=>'ok', 'data'=>$statement->fetchAll(PDO::FETCH_ASSOC)];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
            }
            $conexion = null;
            $con = null;
            return $response;
        }
    
    }
    function updateOrder($post){

        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];
        

        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $fromId = $post['fromId'];
            $fromOrder = $post['fromIndex'];
            $toId = $post['toId'];
            $toOrder = $post['toIndex'];
            $options = $post['options'];
            $idOrderBIndex = $post['idOrderBIndex'];
            $idOrderB = $post['idOrderB'];
            $idOrderMIndex = $post['idOrderMIndex'];
            $idOrderM = $post['idOrderM'];

            if($options != 'bloque'){

                $selectType = "SELECT tipo_de_blog as typeb FROM tb_blogs WHERE id_blog = '$fromId'";
                $st = $con->prepare($selectType);
                $st->execute();
                $type = $st->fetch(PDO::FETCH_ASSOC)['typeb'];

                if($idOrderMIndex != $idOrderM){

                }else{
                    if($idOrderBIndex != $idOrderB){
                        $selectMax = "SELECT MAX(orderB) as maxx FROM tb_blogs WHERE id_bloq = '$toId' and tipo_de_blog = '$type'";
                        $stMax = $con->prepare($selectMax);
                        $stMax->execute();
                        $ordeB = $stMax->fetch(PDO::FETCH_ASSOC)['maxx'];
    
                        if($ordeB == NULL){
                            $ordeB = 1;
                        }else{
                            $ordeB = $ordeB + 1;
                        }
                        //echo 'different bloq'.$ordeB;
                        $sql = "UPDATE tb_blogs SET orderB = '$ordeB', id_bloq = '$toId' WHERE id_blog = '$fromId'";
                    }else{
                        $sql0 = "UPDATE tb_blogs SET orderB = '$toOrder' WHERE id_blog = '$fromId'";
                        $sql = "UPDATE tb_blogs SET orderB = '$fromOrder' WHERE id_blog = '$toId'";
                        //echo 'here same bloq';
                    }
                }    
            }else{
                if($idOrderMIndex != $idOrderM){
                    $selectMax = "SELECT MAX(blogs) as maxx FROM tb_bloques WHERE idMat = '$idOrderM'";
                    $stMax = $con->prepare($selectMax);
                    $stMax->execute();
                    $ordeB = $stMax->fetch(PDO::FETCH_ASSOC)['maxx'];

                    if($ordeB == NULL){
                        $ordeB = 1;
                    }else{
                        $ordeB = $ordeB + 1;
                    }
                    $sql = "UPDATE tb_bloques SET blogs = '$ordeB', idMat = '$idOrderM' WHERE id = '$fromId'";
                    $sql0 = "UPDATE tb_blogs SET id_materia = '$idOrderM' WHERE id_bloq = '$fromId'";
                }else{
                    $sql0 = "UPDATE tb_bloques SET blogs = '$toOrder' WHERE id = '$fromId'";
                    $sql = "UPDATE tb_bloques SET blogs = '$fromOrder' WHERE id = '$toId'";
                }
                //var_dump($post);
                //echo "update bloq";
               
            }

            
            if(isset($sql0) && $sql0 != NULL){
                $statement1 = $con->prepare($sql0);
                $statement1->execute();
            }
         
            $statement = $con->prepare($sql);
            $statement->execute();
            if($statement->errorInfo()[0] == "00000"){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
            }
            $conexion = null;
            $con = null;
            return $response;
        }
    }
    function deleteContent($post){
        $conexion = new Conexion();
        $con = $conexion->conectar();
        $response = [];


        if($con["info"] == "ok"){
            $con = $con["conexion"];

            $data = $post['id'];
            $idBloq = $post['idBloq'];
            //var_dump($post);

            switch($post['tablet']){
                case 0:
                    $sql = "UPDATE `tb_bloques` SET statusBloq = 2 WHERE id = '$data'";
                    $sql0 = "UPDATE `tb_blogs` SET  statusBlog = 2 WHERE id_bloq = '$data'";
                    $statement = $con->prepare($sql0);
                    $statement->execute();        
                break;
                case 1:

                    $select = "SELECT orderB FROM `tb_blogs` WHERE id_blog = '$data'";
                    $st = $con->prepare($select);
                    $st->execute();
                    $orB = $st->fetch(PDO::FETCH_ASSOC)['orderB'];

                    $sql = "UPDATE `tb_blogs` 
                    SET  statusBlog = 2, orderB = 0 
                    WHERE id_blog = '$data'";
                     $sql0 = "UPDATE `tb_blogs` 
                     SET orderB = orderB-1
                     WHERE id_bloq = '$idBloq' and orderB > '$orB'";
                     $statement = $con->prepare($sql0);
                     $statement->execute();
                    break;
                case 2:
                    $sql = "UPDATE `cursos_examen` SET statusExm = 2 WHERE idExamen  = '$data'";
                break;
            }
            

            $statement = $con->prepare($sql);
            $statement->execute();
            if($statement->errorInfo()[0] == "00000"){
                $response = ['estatus'=>'ok', 'data'=>$statement->rowCount()];
            }else{
                $response = ['estatus'=>'error', 'info'=>$statement->errorInfo(), $sql=>'sql'];
            }
            $conexion = null;
            $con = null;
            return $response;
        }
    }
    public function buscarCarrerasEduc(){
        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
        $response = [];
        

        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            
            $sql = "SELECT ac.nombre, ac.idCarrera,ac.imgFondo,ac.imagen,ac.fecha_actualizacion,ac.fecha_creado,ac.descriptionC,ac.title
            FROM a_carreras as ac
            WHERE ac.estatus = 1 AND idCarrera != 3 AND idCarrera != 4 AND idCarrera != 5 AND idCarrera != 10 AND idCarrera != 11
            ORDER BY ac.nombre;";

            $statement = $con->prepare($sql); 		  
            $statement->execute();
            
            $response = $statement->fetchAll(PDO::FETCH_ASSOC);			  
                
            $conexion = null;
            $con = null;
            return $response;
        }
    }
    public function listarMaterias($idCar){
        $conexion = new Conexion(); 
        $con = $conexion->conectar(); 
        $response = [];
        
        if($con["info"] == "ok"){ 
            $con = $con["conexion"];
            $sql = "SELECT mt.id_materia, mt.nombre,mt.descriptionM,mt.imagen,mt.title 
            FROM materias as mt
            JOIN a_carreras as ac on ac.idCarrera = mt.id_carrera
            WHERE ac.idCarrera = '$idCar'";
            $statement = $con->prepare($sql); 		  
            $statement->execute();

            $response = $statement->fetchAll(PDO::FETCH_ASSOC);

            $conexion = null;
            $con = null;
            return $response;
        }
    }//listarCiclos
}