<?php
session_start();
if (isset($_POST["action"])) {
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/horastrabajadas/horasModel.php';
    $horasTrabajadas = new Horas();

    $accion = @$_POST["action"];

    switch ($accion) {
        case 'cargar_clases_docente':
            echo json_encode($horasTrabajadas->cargar_clases_y_docentes_generacion($_POST['generacion'], 'clases_maestro', $_POST['maestro']));
            break;
        case 'cargar_clases_y_docentes_generacion':
            echo json_encode($horasTrabajadas->cargar_clases_y_docentes_generacion($_POST['generacion'], $_POST['listar']));
            break;
        case 'cargar_carreras_docentes':
            echo json_encode($horasTrabajadas->carreras_con_docentes());
            break;
        case 'consultarHorasTotales':
            unset($_POST["action"]);
            $horasTotales = $horasTrabajadas->ListHorasTrabajadas($_POST);
            echo json_encode($horasTotales);
            break;
        case 'updateHoras':
            unset($_POST["action"]);
            $ya_registrado = $horasTrabajadas->horas_ya_registradas($_POST['select_maestro_gen'], $_POST['select_clase_id']);
            if($ya_registrado !== false){
                echo json_encode(['estatus'=>'error', 'info' => 'Ya existe un registro para el docente con esta clase']);
                die();
            }
            $updateHoras = $horasTrabajadas->updateHoras($_POST);
            echo json_encode($updateHoras);
            break;
        case 'CalcularHoras':
            unset($_POST["action"]);
            $CalcHoras = $horasTrabajadas->CalcularHoras($_POST);
            echo json_encode($CalcHoras);
            break;
        case 'ConsultaHorasDocTable':
            unset($_POST["action"]);
            $ConsHorasDoc = $horasTrabajadas->ConsultaHorasDocTable();
            $data = Array();
            while($dato = $ConsHorasDoc->fetchObject()){
                   $Horai=substr($dato->horaentrada,0,-3);
                   $Horaf=substr($dato->horasalida,0,-3);
                   $Horat=substr($dato->tiempotrabajado,0,-3);

                   $pago_td = '';
                   $pago_butn = '';

                   //-----------------------------------------------------------------
                   $split = explode(":",$Horat,2);
                   if(count($split) > 1){
                        $multiplo = intval( $split[0]) + (intval($split[1])/60);
                    }else{
                        if(count($split)==1){
                        $multiplo = intval($split[0]);
                        }
                        else{
                            $multiplo = 0;
                        }
                    }
					//var_dump($multiplo);
                    $pago_total = round(($dato->pago_hora*$multiplo),2); 
                //-----------------------------------------------------------------


                    if($dato->estatus == 0){
                        $pago_td  = "<p class='mb-1'><b class='text-warning'>PENDIENTE DE PAGO</b></p>";
                        //$pago_butn .= "<button class = 'btn btn-primary' onClick = `AsignarID({$dato->idhoras},'{$pago_total}',{$dato->pago_hora},'{$Horat}:00')` data-toggle='modal' data-target='#ModalRegistrarPago'> Registrar Pago </button>";
                        $pago_butn .= "<button class = 'btn btn-primary' onClick = 'AsignarID({$dato->idhoras},{$pago_total},{$dato->pago_hora},`$Horat`)' data-toggle='modal' data-target='#ModalRegistrarPago'> Registrar Pago </button>";
                    }else{
                        //$pago_td  = "<p class='mb-1'><b class='text-success'>PAGADO</b></p>";
                        $pago_td = "<p class='mb-1'><b class='text-success'>$ ".number_format(($dato->monto !== null ? $dato->monto : 00), 2)." </b></p>";
                        $pago_butn .= "<p><span class='float-right'><i><small>Fecha de pago: </small>".$dato->fecha."</i></span></p>";
                        
                        $pago_butn .= "<span class='float-left'><a target='_blank' href=".($dato->archivo !== null ? "../maestros/comprobantes/".$dato->idmaestro."/".urldecode(urlencode($dato->archivo)) : "#" )."><i class='fa fa-credit-card' aria-hidden='true'></i></a></span>";
                    }

                    //Reestructuracion de vista:
                    //var_dump();
                   $data[]=array(
                        0=> "<i>- <b>{$dato->nombre_generacion} </b></i><br> ".
                        "<i>-<b>Materia: </b> {$dato->nombreMat} </i><br> ".
                        "<i>-<b>Clase: </b> {$dato->titulo_clase} </i> ",
                        1=> "<b>Nombre: </b> {$dato->nombreMaestro} <br> <b>Email: </b> {$dato->email} </b> <br> <b>Cuenta Clabe: </b> <b class='text-dark'> {$dato->cuenta_clave} </b>",
                        //2=> "{$dato->cuenta_clave}",
                        2=> substr($dato->fecha_hora_clase, 0, 10),
                        3=> "<b>$Horai Hrs.<br>",
                        4=> "<b>$Horaf Hrs.</b>",
                        5=> "<b>$Horat Hrs.</b>",
                        6=> "<p> <b>Costo Hr:  </b> <b class='text-dark'>$ {$dato->pago_hora} </b> <br><b>Total a pagar:  </b> <b class='text-dark'>$ {$pago_total} </b></p>",
                        7=> $pago_td,
                        8=> $pago_butn,
                    );
            }
            $result = array(
                'sEcho'=>1,
                'iTotalRecords'=>count( $data ),
                'iTotalDisplayRecords'=>count( $data ),
                'aaData'=>$data
            );
            echo json_encode($result);
            break;

            case 'updatepago':
                unset($_POST["action"]);
                // var_dump($_FILES['comprobantePago']);
                if(!isset($_POST['cantidadPago']) || $_POST['cantidadPago'] == '' || !isset($_FILES['comprobantePago']) || $_FILES['comprobantePago']['name'] == '' || !isset($_POST['idhoras']) || $_POST['idhoras'] == ''){
                    echo json_encode(['estatus'=>'error', 'info'=>'LLene todos los campos del formuario']);
                    die();
                }
                $maxSize = 5242880;
                $infoDoc = $horasTrabajadas->ListHorasTrabajadas(["idhoras"=>$_POST["idhoras"]]);
                //se busca el idMaestro para guardar sus comprobantes en una carpeta especifica
                $idMaestro = $infoDoc['data'][0]['idmaestro'];
                foreach($_FILES as $index => $Archivos){
                    if($Archivos['tmp_name'] != null && $Archivos['tmp_name'] != ""){
                        if($Archivos['size'] > $maxSize){
                            $response = ["estatus"=>"error","info"=>"tamanioDoc"];
                        }else{
                            $Var = explode('/',$Archivos['type']);
                            $Direccion = "../../../../maestros/comprobantes/$idMaestro/";
                            if(!file_exists($Direccion)){
                                mkdir($Direccion, 0707);
                            }
                            //Otorgar el nombre dependienod de los archivos
                            $today = date("Y-m-d_H-i-s"); 
                            $nName = "{$idMaestro}_Comprobante_{$today}.{$Var[1]}";
                            $statFile = move_uploaded_file($Archivos['tmp_name'],"{$Direccion}{$nName}");
                            $_POST['nName'] = $nName;
                        }
                    }
                }
                //Movimiento de los arvhivos al lugar correspondiente
                $upPago = $horasTrabajadas->updatepago($_POST);
                echo json_encode($upPago);
                break;
        default:
            # code...
            break;
    }
}else {
    header('Location: ../../../../index.php');
}
