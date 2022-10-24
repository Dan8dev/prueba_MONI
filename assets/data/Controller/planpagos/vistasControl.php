<?php
session_start();
if (isset($_POST["action"])) {
    require_once '../../Model/conexion/conexion.php';
    require_once '../../Model/planpagos/vistasModel.php';
    // require_once '../../../../siscon/app/data/Model/afiliadosModel.php';

    $vistaM = new Vistas();
    // $afiliM = new Afiliados();

    $accion=@$_POST["action"];

    switch ($accion) {
        case 'listar_afiliados':
            $resp = [];
            $resp = $vistaM->consultar_afiliados();
            echo json_encode($resp);
            break;
        case 'vistas_afiliado':
            $resp = [];
            $resp = $vistaM->vistas_afiliado($_POST['prospecto']);
            echo json_encode($resp);
            break;
        case 'listar_vistas':
            echo json_encode($vistaM->vistas_registradas());
            break;
        case 'vistas_existentes':
            $resp = [];
            if(isset($_POST['modulo'])){
                if($_POST['modulo'] == 'siscon'){
                    $vistas = scandir('../../../../siscon/app/');
                    for ($i=0; $i < sizeof($vistas); $i++) { 
                        $file = explode(".", $vistas[$i]);
                        if(sizeof($file) > 0){

                            if($file[sizeof($file)-1] == 'php' && !preg_match('/[a-zA-Z]*(_r)$/', $file[0])){
                                array_push($resp, substr($vistas[$i],0,strlen($vistas[$i])-4));
                            }
                        }
                    }

                    $vistas_registro = $vistaM->vistas_registradas()['data'];
                    foreach ($vistas_registro as $vista => $value) {
                        if(array_search($value['directorio'], $resp) !== false){
                            unset($resp[array_search($value['directorio'],$resp)]);
                            $resp = array_values($resp);
                        }
                        // echo "\n";
                        // print_r($resp);
                        // echo $value['directorio'];
                        // echo "\n";
                        // echo array_search($value['directorio'],$resp);
                        // echo "\n";
                    }
                }
            }else{
                $resp = ['estatus'=>'error','info'=>'Módulo no definido.'];
            }
            echo json_encode(array_values($resp));
            break;
        case 'registrar_vista':
            if(isset($_POST['directorio']) && isset($_POST['nombre_vista'])){
                unset($_POST['action']);

                $resp = $vistaM->registrar_vista($_POST);
            }else{
                $resp = ['estatus'=>'error','info'=>'Complete los campos.'];
            }
            echo json_encode($resp);
            break;
        case 'editar_registro':
            if(isset($_POST['directorio']) && isset($_POST['nombre_vista']) && isset($_POST['editar_vista_i'])){
                unset($_POST['action']);
                $_POST['check_active_vist'] = (isset($_POST['check_active_vist'])) ? 1 : 0;
                $resp = $vistaM->actualizar_vista($_POST);
            }else{
                $resp = ['estatus'=>'error','info'=>'Complete los campos.'];
            }
            echo json_encode($resp);
            break;
        case 'upd_vista_alumno':
            if(isset($_POST['prospecto_vista_set']) && intval($_POST['prospecto_vista_set']) > 0 ){
                $actual_vistas = $vistaM->vistas_afiliado($_POST['prospecto_vista_set'])['data'];

                $nuevas_a_reg = []; // arreglo para guardar los ids de las vistas que aun no tenga asignadas
                $vistas_habilitar = []; // arreglo para guardar las vistas que ya existen, que están deshabilitadas pero que hay que volver a habilitar
                foreach ($_POST as $key => $value) {
                    // verificar si la vista del post esta en el arreglo de vistas actuales
                    if(preg_match('/^(check_vist_)[0-9]+/', $key)){
                        $idv = explode('_', $key);
                        $ix = array_search($idv[sizeof($idv)-1], array_column($actual_vistas, 'vista'));

                        if($ix === false){
                            array_push($nuevas_a_reg, $idv[sizeof($idv)-1]);
                        }else{
                            if(intval($actual_vistas[$ix]['estatus']) == 0){
                                array_push($vistas_habilitar, $idv[sizeof($idv)-1]);
                            }
                        }
                    }
                }
                $vistas_deshabilitar = []; // arreglo para guardar los ids que ya tiene asignados pero se van a deshabilitar porque no tienen el check habilitado
                for ($i=0; $i < sizeof($actual_vistas); $i++) { 
                    // print_r(['check_vist_'.$actual_vistas[$i]['vista'], array_keys($_POST)]);
                    // echo "\n";
                    // var_dump(array_search('check_vist_'.$actual_vistas[$i]['vista'], array_keys($_POST)));
                    // echo "\n";
                    $ix = array_search('check_vist_'.$actual_vistas[$i]['vista'], array_keys($_POST));
                    if($ix === false){
                        array_push($vistas_deshabilitar, $actual_vistas[$i]['vista']);
                    }
                    // echo "\n((";
                    // print_r($vistas_deshabilitar);
                    // echo "\n))";
                }
                // CREAR REGISTRO DE VISTAS NO EXISTENTES A UN AFILIADO
                $nuevos_reg = 0;
                $statf = null;
                for ($i=0; $i < sizeof($nuevas_a_reg); $i++) { 
                    $insert = $vistaM->registrar_vista_afiliado($_POST['prospecto_vista_set'],$nuevas_a_reg[$i]);
                    if($insert['estatus'] == 'error'){
                        $statf = ['estatus'=>'error','info'=>'Error al insertar'.json_encode($insert)];
                    }else{
                        $nuevos_reg++;
                    }
                }

                // HABILITAR LISTA DE VISTAS A PROSPECTO
                $habilit = 0;
                $des_habilit = 0;
                if(sizeof($vistas_habilitar) > 0){
                    $habilit = $vistaM->habilitar_vistas_afiliados($_POST['prospecto_vista_set'], $vistas_habilitar)['data'];
                }
                if(sizeof($vistas_deshabilitar) > 0){
                    $des_habilit = $vistaM->des_habilitar_vistas_afiliados($_POST['prospecto_vista_set'], $vistas_deshabilitar)['data'];
                }

                if($statf == null){
                    $resp = ['nuevos'=>$nuevos_reg,'habilitados'=>$habilit,'deshabilitados'=>$des_habilit];
                }else{
                    $resp = $statf;
                }
            }else{
                $resp = ['estatus'=>'error','info'=>'Alumno no identificado'];
            }
            echo json_encode($resp);
            break;
        default:
            # code...
            break;
    }
    
    # code...
} else {
    header('Location: ../../../../index.php');
}
