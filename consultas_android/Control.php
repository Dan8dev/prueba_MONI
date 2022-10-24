<?php

//Consulta de apli Android
if (isset($_POST["action"])) {
    
	require_once '../assets/data/Model/conexion/conexion.php';
	require_once 'Model.php';


    $ce = new ControlEscolar();
    //$matsM = new Materias();
    $RutaLocal = "https://moni.com.mx/assets/images/ponentes/";
    //$Ruta = "https://sandbox.conacon.org/assets/images/ponentes/";
    //$RutaLocal = "http://localhost/re-moni/assets/images/ponentes/";
    switch($_POST['action']){     

        case 'talleres_eventos':
            unset($_POST['action']);
            $Talleres = $ce->talleres_eventos($_POST);
            echo json_encode($Talleres);
        break;

        case 'talleres_eventos_inscritos':
            unset($_POST['action']);
            $Talleres = $ce->talleres_eventos_inscritos($_POST);
            echo json_encode($Talleres);
        break;

        case 'ponencias_eventos':
            unset($_POST['action']);
            $Ponencias = $ce->ponencias_eventos($_POST);
            echo json_encode($Ponencias);
        break;

        case 'eventos_taller_limite':
            unset($_POST['action']);
            $Limite = $ce->eventos_taller_limite($_POST);
            echo json_encode($Limite);
        break;

        case 'Consulta':
            unset($_POST['action']);
            
            $response = [];
            $array = [];
            //$limite_taller = [];
            if(($ce->eventos_taller_limite($_POST)["limite_taller"])!=[]){
                //var_dump($ce->eventos_taller_limite($_POST)["limite_taller"][0]);
                $limite_taller = $ce->eventos_taller_limite($_POST)["limite_taller"];
                //var_dump($limite_taller["limite_taller"]);
            }else{
               $limite_taller = ['limite_taller'=>'0'];
            }

            $lista_talleres = $ce->talleres_eventos($_POST)["lista_talleres"];
            $talleres_inscritos = $ce->talleres_eventos_inscritos($_POST)["talleres_inscritos"];

            //Añadir Dirección
            $ponencias = $ce->ponencias_eventos($_POST)["ponencias"];
            //var_dump($ponencias[0]['foto']);
            $count = 0;
            foreach($talleres_inscritos as $Listas){
                if($talleres_inscritos[$count]['foto'] == null || $talleres_inscritos[$count]['foto']==''){
                    //$pon['foto'] =$RutaLocal.'default.jpg';
                    $talleres_inscritos[$count]['foto'] = $RutaLocal.'default.jpg';
                }else{
                    //$pon['foto'] = $RutaLocal.$pon['nombre_ponente'];

                    $Nombre = $talleres_inscritos[$count]['foto'];
                    //var_dump($Nombre);
                    if(file_exists('../assets/images/ponentes/'.$Nombre)){
                        $talleres_inscritos[$count]['foto'] = $RutaLocal.$Listas['foto'];
                    }else{
                        $talleres_inscritos[$count]['foto'] = $RutaLocal.'default.jpg';
                    }
                    //$talleres_inscritos[$count]['foto'] = $RutaLocal.$Listas['foto'];
                }
                $count++;
            }

            $count = 0;
            foreach($lista_talleres as $Lista){
                if($lista_talleres[$count]['foto'] == null || $lista_talleres[$count]['foto']==''){
                    //$pon['foto'] =$RutaLocal.'default.jpg';
                    $lista_talleres[$count]['foto'] = $RutaLocal.'default.jpg';
                }else{
                    //$pon['foto'] = $RutaLocal.$pon['nombre_ponente'];
                    $Nombre = $Lista['foto'];
                    //var_dump($Nombre);
                    if(file_exists('../assets/images/ponentes/'.$Nombre)){
                        
                        $lista_talleres[$count]['foto'] = $RutaLocal.$Lista['foto'];
                    }else{
                        $lista_talleres[$count]['foto'] = $RutaLocal.'default.jpg';
                    }
                }
                $count++;
            }


            $count = 0;
            foreach($ponencias as $pon){
                if($ponencias[$count]['foto'] == null || $ponencias[$count]['foto']==''){
                    //$pon['foto'] =$RutaLocal.'default.jpg';
                    $ponencias[$count]['foto'] = $RutaLocal.'default.jpg';
                }else{
                    //$pon['foto'] = $RutaLocal.$pon['nombre_ponente'];
                    $Nombre = $ponencias[$count]['foto'];
                    //var_dump($Nombre);
                    if(file_exists('../assets/images/ponentes/'.$Nombre)){
                        $ponencias[$count]['foto'] = $RutaLocal.$pon['foto'];
                    }else{
                        $ponencias[$count]['foto'] = $RutaLocal.'default.jpg';
                    }
                }
                $count++;
            }
            $count = 0;
            
            /*if($ponencias[0]['foto'] != null || $ponencias[0]['foto']== '' ){
                $ponencias[0]['foto']=$Ruta.$ponencias[0]['foto'];
            }else{
                $ponencias[0]['foto']=$Ruta.'default.jpg';
            }*/
            //var_dump($ponencias);
            //die();
            $array = ['data'=> ['talleres_inscritos'=>$talleres_inscritos,'ponencias'=>$ponencias,'limite_taller'=>$limite_taller["limite_taller"],'lista_talleres'=>$lista_talleres]];
            $response = $array;
            echo json_encode($response);
        break;
        
    case 'obtenerCredencial':
        unset($_POST['action']);
        $generarPdf = $ce->ObtenerPdfCredenccial($_POST);
        echo json_encode($generarPdf);
        break;
        
        default:
            echo "noaction";
        break;
    }

}else{
	echo "noaction";
}


?>
