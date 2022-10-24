<?php 
 class Conexion{

   var $serv = "localhost";
   var $usuario = "root";
   var $pass = "";
   var $dbname = "moniDB";
   //var $dbname = "moni_local";
   // var $dbname = "moni_dev_market";

   public function conectar(){
      try {
         $mbd = new PDO('mysql:host=localhost;dbname=scae', 'root', '');
         $mbd -> exec('SET CHARACTER SET utf8');
         //$mbd = new PDO('mysql:host=localhost;dbname=SCAE', 'userpuebla', 'P7s0@uh8');
         return $mbd;
      } catch (PDOException $e) {
		  var_dump($e->getMessage());
         return false;
      }
   }

   public function conectar_moni(){
      try {
         $mbd = new PDO('mysql:host=localhost;dbname=moni_prod', 'root', '');
         $mbd -> exec('SET CHARACTER SET utf8');
         //$mbd = new PDO('mysql:host=localhost;dbname=SCAE', 'userpuebla', 'P7s0@uh8');
         return $mbd;
      } catch (PDOException $e) {
		  var_dump($e->getMessage());
         return false;
      }
   }

}

class Validador{
   public static function validar_datos_insertar($post, $requeridos){
      // validar que todos los indices en el post esten dentro del arreglo de los datos requeridos
      // es decir, que no haya indices en post que no sean requeridos
      #$band1 = array_reduce(array_keys($post), function($acc, $item) use ($requeridos){
      #                                 return $acc = (!in_array($item, $requeridos))? false : $acc;
      #                              }, true);
      // validar que todos los datos requeridos esten en los indices del post
      // es decir que no falten datos por insertar
      $band2 = array_reduce($requeridos, function($acc, $item) use ($post){
                                       return $acc = (!in_array($item, array_keys($post)))? false : $acc;
                                    }, true);
      #$estatus = ($band1 && $band2)?'ok':'error';
      $estatus = ($band2)?'ok':'error';
      $info = '';
      #$info .= (!$band1)? 'sobran_indices-':'';
      $info .= (!$band2)? 'faltan_datos-':'';

      return ['estatus'=>$estatus, 'info'=>$info];
   }

     /*public static function solicitarController($infoPost, $controllerURL){
      $postdata = http_build_query($infoPost);
      $opts = array('http' =>
         array(
           'method'  => 'POST',
           'header'  => 'Content-Type: application/x-www-form-urlencoded',
           'content' => $postdata
         )
      );

      $context  = stream_context_create($opts);
	   $url = '../../Controller/'.$controllerURL;
      $result = file_get_contents($url, false, $context);
      return json_decode($result, true);
      // return $url;

   }*/
}
?>