<?php
session_start();
if (isset($_POST["action"])) {
    date_default_timezone_set("America/Mexico_City");
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/conceptosModel.php';
    $conceptos = new Conceptos();

    if(!isset($_SESSION['usuario'])){
        $_POST['action'] = 'no_session';
    }
    $accion=@$_POST["action"];

    switch ($accion) {
        case 'obtenerInstituciones':
            unset($_POST['action']);
            $obInst = $conceptos->obtenerInstituciones()['data'];
            echo json_encode($obInst);
            break;

        case 'crearConcepto':
            unset($_POST['action']);
            // if(empty($_POST['precio'])){
            //     echo 'numero_invalido';
            // }else{

                $fCreado = date('Y-m-d H:i:s');
                $_POST['fCreado'] = $fCreado;
                if(floatval($_POST['precio']) == 0 && floatval($_POST['precio_usd']) == 0 ){
                    echo json_encode(['estatus'=>'error', 'info'=>'El costo de ambos conceptos no puede ser igual a 0']);
                    die();
                }
                $addC = $conceptos->crearConcepto($_POST);
                echo json_encode($addC);
            // }
            break;

        case 'obtenerConceptos':
            unset($_POST['action']);
            $csul = $conceptos->obtenerConceptos();
            $data = Array();
            while($dato=$csul->fetchObject()){
                $data[]=array(
                     $dato->concepto,
                     number_format(floatval($dato->precio)),
                     number_format(floatval($dato->precio_usd)),
                     $dato->categoria,
                     $dato->descripcion,
                     $dato->nombreInst,
                    '<a class="btn btn-primary" data-toggle="modal" data-target="#modal-editar-concepto" onclick="editarconcepto('.$dato->id_concepto.')">Modificar</a> '
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

        case 'obtenerConcepto':
            unset($_POST['action']);
            $rspta=$conceptos->obtenerConcepto($_POST);
            echo json_encode($rspta['data']);
            break;

        case 'modificarConcepto':
            unset($_POST['action']);
            // if(empty($_POST['editarPrecio'])){
            //     echo 'numero_invalido';
            // }else{
                if(floatval($_POST['editarPrecio']) == 0 && floatval($_POST['editarPrecio_usd']) == 0 ){
                    echo json_encode(['estatus'=>'error', 'info'=>'El costo de ambos conceptos no puede ser igual a 0']);
                    die();
                }
                $fModificado = date('Y-m-d H:i:s');
                $_POST['fModificado'] = $fModificado;
                $mod = $conceptos->modificarConcepto($_POST);
                echo json_encode($mod);
            // }
            break;

        case 'eliminarConcepto':
            unset($_POST['action']);
            $del = $conceptos->eliminarConcepto($_POST);
            echo json_encode($del);
            break;

        case 'no_session':
            echo 'no_session';
            break;

        default:
            # code...
            break;
    }
}else {
    header('Location: ../../../../index.php');
}
