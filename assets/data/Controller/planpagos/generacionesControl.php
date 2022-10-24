<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/generacionesModel.php';
    $Gnera = new Generaciones();

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }

    $accion=@$_POST["action"];

    switch ($accion) {

        case 'obtenerCarreras':
            unset($_POST['action']);
            $obCarrer = $Gnera->obtenerListaCarreras()['data'];
            echo json_encode($obCarrer);
            break;
        
        case 'crearGeneracion':
            unset($_POST['action']);

            foreach($_POST as $campo => $valor){
                if($campo == 'selectCarrer'){
                    $var1 = $valor;
                }
            }
            unset($_POST['selectCarrer']);
            $fCreado = date('Y-m-d H:i:s');
            $_POST['fCreado'] = $fCreado;

            $addG = $Gnera->crearGeneracion($_POST);

            $idG = $addG;
            
            for($i=0; $i < count($var1) ;$i++){
                $addC = $Gnera->addGeneracion($var1[$i], $idG['data']);
            }
            echo json_encode($addC);

            break;

        case 'obtenerGeneraciones':
            unset($_POST['action']);
            $csulG = $Gnera->obtenerGeneraciones();
            $data = Array();
            while($dato=$csulG->fetchObject()){
                $data[]=array(
                    //0=> $dato->IDPlanPago,
                    0=> $dato->nombre,
                    1=> $dato->fecharegistro,
                    2=> $dato->fechafinal,
                    3=> $dato->fechaCreado,
                    4=>'<button class="btn btn-primary" data-toggle="modal" data-target="#modalModGene" onclick="buscarGeneracion('.$dato->idGeneracion.')">Modificar</button>',
                    5=>'<button class="btn btn-danger" onclick="validarEliminarGeneracion('.$dato->idGeneracion.')">Eliminar</button>'
                );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count($data),
                'iTotalDisplayRecords'=>count($data),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

        case 'eliminarGeneracion':
            unset($_POST['action']);
            $del = $Gnera->eliminarGeneracion($_POST);
            echo json_encode($del);
            break;

        case 'obtenerCarrerasMod':
            unset($_POST['action']);
            $obCarrer = $Gnera->obtenerListaCarrerasMod($_POST)['data'];
            echo json_encode($obCarrer);
            break;

        case 'buscarGeneracion':
            unset($_POST['action']);
            //$busC = $Gnera->buscarCarrerasG($_POST['idEditar']);
            $bus = $Gnera->buscarGeneracion($_POST['idEditar']);
            //var_dump(count($bus));
            $bus['data'][0]['fecharegistro'] = date("Y-m-d", strtotime($bus['data'][0]['fecharegistro']));
            $bus['data'][0]['fechafinal'] = date("Y-m-d", strtotime($bus['data'][0]['fechafinal']));
            echo json_encode($bus);
            break;

        case 'buscarCarrerasMod':
            unset($_POST['action']);
            $obCarrer = $Gnera->buscarCarrerasMod($_POST)['data'];
            echo json_encode($obCarrer);
            break;

        case 'modificarGeneracion':
            unset($_POST['action']);
            foreach($_POST as $campo => $valor){
                if($campo == 'modselectCarrer'){
                    $var1 = $valor;
                }
            }
            unset($_POST['modselectCarrer']);

            //unset($_POST['selectAnterior']);
            //var_dump(substr_count($_POST['selectAnterior'], ','));
            $comas = substr_count($_POST['selectAnterior'], ',');
            
            if($comas != 0){
                for($j=0; $j < substr_count($_POST['selectAnterior'], ','); $j++){
                    $ant = explode(',', $_POST['selectAnterior']);
                }
            }else{
                $ant[0] = $_POST['selectAnterior'];
            }
            unset($_POST['selectAnterior']);
            //var_dump($var1);
            //var_dump($ant);
            if(array_diff($var1,$ant)){
                //$actCarr
                //echo 'si';
                $cont1 = count($var1);
                $contA = count($ant);
                for($l=0; $l < $contA; $l++){
                    $notAddG = $Gnera->notAddGeneracion($ant[$l], $_POST['idG']);
                }
                for($n=0; $n < $cont1; $n++){
                    $addG = $Gnera->addGeneracion($var1[$n], $_POST['idG']);
                }
            }else{
                //echo 'no';
                $cont1 = count($var1);
                $contA = count($ant);
                //var_dump($cont1);
                //var_dump($contA);
                if($contA != $cont1){
                    //echo 'action';
                    for($z=0; $z < $contA; $z++){
                        $notAddG = $Gnera->notAddGeneracion($ant[$z], $_POST['idG']);
                    }
                    for($y=0; $y < $cont1; $y++){
                        $addGe = $Gnera->addGeneracion($var1[$y], $_POST['idG']);
                    }
                }else{
                    //echo 'no_action';
                }
            }
            $fActualizacion = date('Y-m-d H:i:s');
            $_POST['fActualizacion'] = $fActualizacion;
            $modG = $Gnera->modificarGeneracion($_POST);
            echo json_encode($modG);
            break;

        case 'no_session':
            echo 'no_session';
            break;

        default:
            # code...
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}
