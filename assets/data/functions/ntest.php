<?php
    
    require_once __DIR__.'/../Model/conexion/conexion.php';
    
    require_once __DIR__.'/../Model/carreras/carrerasModel.php';
    require_once __DIR__.'/../Model/eventos/eventosModel.php';
    require_once __DIR__.'/../Model/planpagos/planpagosModel.php';
    require_once __DIR__.'/../Model/planpagos/promocionesModel.php';
    require_once __DIR__.'/../Model/planpagos/pagosModel.php';
    require_once __DIR__.'/../Model/prospectos/prospectosModel.php';
    require_once __DIR__.'/../Model/planpagos/generacionesModel.php';
    require_once __DIR__.'/../Model/planpagos/vistasModel.php';
    require_once __DIR__.'/../Model/alumnos/alumnosInstitucionesModel.php';
    require_once __DIR__.'/../Model/controlescolar/examenModel.php';
    require_once __DIR__.'/../Model/controlescolar/controlEscolarModel.php';
    
    require_once __DIR__.'/../Controller/planpagos/aux_pagosControl.php';

    // echo "<pre>";
    // print_r($resp);
    // echo "</pre>";
    $plan = $resp;
    if($plan['estatus'] == 'ok' && $plan['data'] != false){
        $generacion = array_reduce($plan['data']['generaciones'], function($ac, $item){
            if(sizeof($item['asignacion']) > 0){
                $ac = $item;
            }
            return $ac;
        }, false);

        if($generacion){
            $infor_mostrar = [];
            $infor_mostrar['alumno'] = $plan['data']['info_alumno'];
            $infor_mostrar['generacion'] = $generacion;
            $infor_mostrar['generacion']['asignacion'] = $generacion['asignacion'][0];
            $carrera_nombre = mb_strtoupper($infor_mostrar['generacion']['nombre'], 'UTF-8');
            $infor_mostrar['alumno']['generacion'] = $generacion;
            $infor_mostrar['alumno']['generacion']['asignacion'] = $generacion['asignacion'][0];
            $infor_mostrar['alumno']['generacion']['carrera_nom'] = $carrera_nombre;
            $infor_mostrar['alumno']['institucion'] = $plan['data']['institucion'];
            $infor_mostrar['conceptos'] = $plan['data']['pagos_aplicar'];
            $infor_mostrar['promociones'] = $plan['data']['promociones'];
            $tabla_conceptos = '';
            $desglose = show_info($infor_mostrar);
        }else{
            die("El alumno aún no se encuentra inscrito a esta oferta académica");
        }
    }

    // print_r($desglose);

    
    function show_info($info){
        $recibed = $info;
        $string_mensualidad = '';
        // $mensl = $info.conceptos.find(elm => elm['categoria'] == 'Mensualidad');
        $mensl = array_search('Mensualidad', array_column($info['conceptos'], 'categoria'));
        $mensl = array_reduce($info['conceptos'], function($acc, $it){
            if($it['categoria'] == 'Mensualidad'){
                $acc = $it;
            }
            return $acc;
        }, false);
        if($mensl){
            $fech_prim_mes = '';
            $fech_prim_mes = $mensl['primer_mensualidad'];
            $string_mensualidad = substr($fech_prim_mes, 0, 10);
            $string_mensualidad = $string_mensualidad;
            $info['alumno']['primer_mensualidad'] = $string_mensualidad;
        }
        
        $tabla_conceptos = '';
        $desglose_concepto = [[$info['alumno']]];
        for($c = 0; $c < sizeof($info['conceptos']); $c++){

            $estatus_concepto = 'Pagado';
            $info_promo_concepto = '-';
            
            if(empty($info['conceptos'][$c]['aplicados'])){
                $estatus_concepto = 'Sin pagos';
            }else{
                //Math.max(... $info.conceptos[$c]['aplicados'].map(elm => elm.numero_de_pago))
                $max_pag = max(array_reduce($info['conceptos'][$c]['aplicados'], function($ac, $it){
                    $ac[] = $it['numero_de_pago'];
                    return $ac;
                }, []));
                $estatus_concepto = $max_pag.' pagos aplicados';
            }

            $pago_concepto = array_reduce($info['conceptos'][$c]['aplicados'], function($ac, $it){
                $ac = $ac === false ? $it : (floatval($it['saldo']) + floatval($it['restante']) < floatval($ac['saldo']) + floatval($ac['restante'])) ? $it : $ac;
                return $ac;
            }, false);
            
            $info_promo_concepto = ' - ';
            $pago_fin_concepto = ' $ 0.00';
            $str_mon = $info['alumno']['tipoPago'] == '2' ? 'USD' : 'MXN';
            $pago_fin_concepto = "$ ".number_format($info['conceptos'][$c]['precio'], 2).' '.$str_mon;
            if($pago_concepto){
                if($pago_concepto['moneda'] === null){
                    $pago_concepto['moneda'] = 'MXN';
                }
    
                if($pago_concepto['porcentaje'] && $pago_concepto['porcentaje'] != null && $info['conceptos'][$c]['categoria'] != 'Mensualidad'){
                    $str_mon_mens = "$ ".number_format($pago_concepto['costototal'], 2);
                    $pago_fin_concepto = $str_mon_mens.strtoupper($pago_concepto['moneda']);
                    $info_promo_concepto = "".number_format(floatval($pago_concepto['porcentaje']), 1)."%-";
                }else if($info['conceptos'][$c]['categoria'] != 'Mensualidad'){
                    $promo_concepto_aplicar = array_search($info['conceptos'][$c]['id_concepto'], array_column($info['promociones'], 'id_concepto'));
                    if($promo_concepto_aplicar){
                        $info_promo_concepto = ''.number_format($promo_concepto_aplicar['porcentaje'], 1).'%-';
                    }
                }
            }
            
            $nombre_concepto = strtoupper($info['conceptos'][$c]['categoria']);
            if($info['conceptos'][$c]['categoria'] == 'Mensualidad'){
                $nombre_concepto = strtoupper($info['conceptos'][$c]['categoria']);
                $nombre_concepto = strtoupper('Mensualidades');
            }else if($info['conceptos'][$c]['categoria'] == 'Reinscripción'){
                $nombre_concepto = strtoupper($info['conceptos'][$c]['categoria']);

            }else if($info['conceptos'][$c]['categoria'] == 'Generales' || $info['conceptos'][$c]['categoria'] == 'General'){
                $nombre_concepto = strtoupper($info['conceptos'][$c]['categoria']).' '.strtoupper($info['conceptos'][$c]['concepto']).'';
                
            }else if($info['conceptos'][$c]['categoria'] == 'Inscripción' && !empty($info['conceptos'][$c]['aplicados']) && $info['conceptos'][$c]['parcialidades'] == '1'){
                $nombre_concepto = strtoupper($info['conceptos'][$c]['categoria']);

            }
            $str_fecha_conce_mens = $info['conceptos'][$c]['fechalimitepago'] != null && $info['conceptos'][$c]['categoria'] != 'Mensualidad' ? substr($info['conceptos'][$c]['fechalimitepago'], 0, 10):'-';
            $concepto_arr = [
                "concepto"=>mb_strtoupper($nombre_concepto, 'UTF-8'),
                "monto_pagar"=>$pago_fin_concepto,
                "promocion"=>$info_promo_concepto,
                "str_fecha_conce_mens"=>$str_fecha_conce_mens,
                "estatus"=>$estatus_concepto
            ];
            $tabla_conceptos .= "<tr>
                                    <td>".mb_strtoupper($nombre_concepto, 'UTF-8')."</td>
                                    <td>{$pago_fin_concepto}</td>
                                    <td>{$info_promo_concepto}</td>
                                    <td>{$str_fecha_conce_mens}</td>
                                    <td>{$estatus_concepto}</td>
                                </tr>";

            if($info['conceptos'][$c]['categoria'] == 'Inscripción' && !empty($info['conceptos'][$c]['aplicados']) && $info['conceptos'][$c]['parcialidades'] == '1'){
                $sub_td_insc = '';
                for($i = 0; $i < sizeof($info['conceptos'][$c]['aplicados']); $i++){
                    if($info['conceptos'][$c]['aplicados'][$i]['moneda'] === null){
                        $info['conceptos'][$c]['aplicados'][$i]['moneda'] = 'mxn';
                    }
                    $sub_td_insc .= '<tr>
                        <td>Inscripción</td>
                        <td> $ '.number_format($info['conceptos'][$c]['aplicados'][$i]['montopagado'], 2).' '.(strtoupper($info['conceptos'][$c]['aplicados'][$i]['moneda']) == 'USD' ? 'USD' : 'MXN').'</td>
                        <td>'.substr($info['conceptos'][$c]['aplicados'][$i]['fechapago'],0,10).'}</td>
                        </tr>';
                }
                $min_show = number_format(min(array_reduce($info['conceptos'][$c]['aplicados'], function($ac, $it){
                    if(floatval($it['restante']) >= 0){
                        $ac[] = $it['restante'];
                    }
                    return $ac;
                }, [])), 2);
                
                $sub_td_insc .= '<tr class="bg-light">
                    <td>Restante por pagar:</td>
                    <td> $ '.$min_show.'</td>
                    <td></td>
                </tr>';
                $tabla_conceptos .= '<tr id="desglose_parcialidades" style="display:none;">
                    <td colspan="5">
                        <table class="sub-table w-100">
                            <thead>
                            <th>Concepto</th>
                            <th>Monto pagado</th>
                            <th>Fecha de pago</th>
                            </thead>
                            <tbody>'.$sub_td_insc.'</tbody>
                        </table>
                    </td>
                </tr>';
            }

            if($info['conceptos'][$c]['categoria'] == 'Mensualidad'){
                $sub_td = '';
                $inicio_mens = substr($mensl['primer_mensualidad'],0,10);
                $inicio_mens = strtotime($inicio_mens);
                $aux_mens = $inicio_mens;
                $sub_des = [];
                for($i = 0; $i < $info['conceptos'][$c]['numero_pagos']; $i++){
                    $info_promo = '';
                    $pago_mensualidad = array_reduce($info['conceptos'][$c]['aplicados'], function($acc, $cur) use ($i){
                        if($cur['numero_de_pago'] == ($i+1)){
                            $acc = $acc === false ? $cur : (floatval($cur['saldo']) + floatval($cur['restante']) < floatval($acc['saldo']) + floatval($acc['restante'])) ? $cur : $acc;
                        }
                        return $acc;
                    }, false);
                    
                    if($pago_mensualidad && $pago_mensualidad['moneda'] === null){
                        $pago_mensualidad['moneda'] = 'MXN';
                    }
                    $estatus_pago = 'Sin pagos';
                    if(!$pago_mensualidad){
                        $estatus_pago = 'Sin pagos';
                    }else if(floatval($pago_mensualidad['saldo']) + floatval($pago_mensualidad['restante']) < 1){
                        $estatus_pago = 'Pagado';
                    }else if(floatval($pago_mensualidad['saldo']) + floatval($pago_mensualidad['restante']) >= 1){
                        $estatus_pago = 'Saldo pendiente';
                    }
                    $pago_fin = '$ '.number_format($info['conceptos'][$c]['precio'], 2).' '.($info['alumno']['tipoPago'] == '2' ? 'USD' : 'MXN');
                    if($pago_mensualidad && $pago_mensualidad['porcentaje'] && $pago_mensualidad['porcentaje'] != null){
                        $pago_fin = '$ '.number_format($pago_mensualidad['costototal'], 2).' '.strtoupper($pago_mensualidad['moneda']);
                        $info_promo = (floatval($pago_mensualidad['porcentaje']) > 1 ? round(floatval($pago_mensualidad['porcentaje']), 1).'%-' : '');

                    }else{
                        // $promo_con = $info['promociones'].find(elm => elm.id_concepto == $info['conceptos'][$c].id_concepto && elm.id_prospecto == $info['alumno'].idAsistente && elm.Nopago != '' && elm.Nopago.includes((i+1).toString()));
                        $use = [$info['conceptos'][$c]['id_concepto'], $info['alumno']['idAsistente'], $i+1];

                        $promo_con = array_reduce($info['promociones'], function($acc, $it) use ( $use ){
                            if($it['id_concepto'] == $use[0] && $it['id_prospecto'] == $use[1] && $it['Nopago'] != '' && (gettype($it['Nopago']) == 'array' && in_array(strval($use[2]), $it['Nopago'])) && $acc === false){
                                $acc = $it;
                            }
                            return $acc;
                        }, false);
                        $use_dos = [$info['conceptos'][$c]['id_concepto'], $info['alumno']['idAsistente']];
                        $promo_con = ($promo_con === false) ? array_reduce($info['promociones'], function($acc, $it) use ($use_dos){
                            if($it['id_concepto'] == $use_dos[0] && $it['id_prospecto'] == $use_dos[1] && ($it['Nopago'] == '' || $it['Nopago'] == NULL || $it['Nopago'] == 0)){
                                $acc = $it;
                            }
                            return $acc;
                        }, false) : $promo_con;

                        $use_tres = [$info['conceptos'][$c]['id_concepto']];
                        $promo_con = ($promo_con === false) ? array_reduce($info['promociones'], function($acc, $it) use ($use_tres){
                            if($it['id_concepto'] == $use_tres[0] && ($it['Nopago'] == '' || $it['Nopago'] == NULL || $it['Nopago'] == 0)){
                                $acc = $it;
                            }
                            return $acc;
                        }, false) : $promo_con;
                        
                        if($promo_con && !$pago_mensualidad){
                            $pago_fin = '$ '.number_format(floatval($info['conceptos'][$c]['precio']) - floatval($info['conceptos'][$c]['precio']) * (floatval($promo_con['porcentaje']) / 100), 2).' '.($info['alumno']['tipoPago'] == '2' ? 'USD' : 'MXN');
                            $vigencia = '';
                            if($promo_con['fechainicio'] != null && $promo_con['fechafin'] != null){
                                $vigencia = '';
                            }else if(!empty($promo_con['Nopago'])){
                                $vigencia = '';
                            }
                            $info_promo = floatval($promo_con['porcentaje']) > 1 ? round(floatval($promo_con['porcentaje']), 2).'%- '.$vigencia : '';
                        }
                    }
                    $sub_des[] = [
                        ('    -Mensualidad '.($i+1)),
                        $pago_fin,
                        $info_promo,
                        date('Y-m-d', $aux_mens),
                        $estatus_pago
                    ];
                    $sub_td .= '<tr>
                    <td> Mensualidad '.($i+1).'</td>
                    <td>'.$pago_fin.'</td>
                    <td>'.$info_promo.'</td>
                    <td>'.date('Y-m-d', $aux_mens).'</td>
                    <td>'.$estatus_pago.'</td>
                    </tr>';
                    $aux_mens = strtotime('+1 month', $aux_mens);
                }
                $tabla_conceptos .= '<tr id="desglose_mensualidad" style=""><td colspan="5"><table class="sub-table"><tbody>'.$sub_td.'</tbody></table></td></tr>';
                $concepto_arr['sub_desglose'] = $sub_des;
            }
            
            /* if($info['conceptos'][$c]['categoria'] == 'Reinscripción'){
                $sub_td_reins = '';
                $inicio_mens = substr($mensl['primer_mensualidad'], 0, 10);
                $inicio_mens = strtotime($inicio_mens);
                $aux_mens = $inicio_mens;
                $sub_des_ins = [];
                for($i = 0; $i < $info['conceptos'][$c]['numero_pagos']; $i++){
                    $info_promo = '';
                    $pago_mensualidad = array_reduce($info['conceptos'][$c]['aplicados'], function($acc, $cur) use ($i){
                        if($cur['numero_de_pago'] == ($i+1)){
                            $acc = $acc === false ? $cur : (floatval($cur['saldo']) + floatval($cur['restante']) < floatval($acc['saldo']) + floatval($acc['restante'])) ? $cur : $acc;
                        }
                        return $acc;
                    }, false);
                    if($pago_mensualidad && $pago_mensualidad['moneda'] === null){
                        $pago_mensualidad['moneda'] = 'MXN';
                    }
                    $estatus_pago = 'Sin pagos';
                    if(!$pago_mensualidad){
                        $estatus_pago = 'Sin pagos';
                    }else if(floatval($pago_mensualidad['saldo']) + floatval($pago_mensualidad['restante']) < 1){
                        $estatus_pago = 'Pagado';
                    }else if(floatval($pago_mensualidad['saldo']) + floatval($pago_mensualidad['restante']) >= 1){
                        $estatus_pago = 'Saldo pendiente';
                    }
                    $pago_fin = number_format($info['conceptos'][$c]['precio'], 2).($info['alumno']['tipoPago'] == '2' ? 'USD' : 'MXN');
                    
                    if($pago_mensualidad && $pago_mensualidad['porcentaje'] && $pago_mensualidad['porcentaje'] != null){
                        $pago_fin = number_format($pago_mensualidad['costototal'], 2).' '.strtoupper($pago_mensualidad['moneda']);
                        $info_promo = floatval($pago_mensualidad['porcentaje']) > 1 ? round(floatval($pago_mensualidad['porcentaje']), 1).'%-' : '';
                    }else{
                        // $promo_con = $info['promociones'].find(elm => elm.id_concepto == $info['conceptos'][$c].id_concepto && elm.id_prospecto == $info['alumno'].idAsistente && elm.Nopago != '' && elm.Nopago.includes((i+1).toString()));
                        $use = [$info['conceptos'][$c]['id_concepto'], $info['alumno']['idAsistente'], $i];
                        $promo_con = array_reduce($info['promociones'], function($acc, $it) use ( $use ){
                            if($it['id_concepto'] == $use[0] && $it['id_prospecto'] == $use[1] && $it['Nopago'] != '' && strpos($it['Nopago'], strval($use[2])) && $acc !== false){
                                $acc = $it;
                            }
                            return $acc;
                        }, false);

                        $use_dos = [$info['conceptos'][$c]['id_concepto'], $info['alumno']['idAsistente']];
                        $promo_con = ($promo_con) ? array_reduce($info['promociones'], function($acc, $it) use ($use_dos){
                            if($it['id_concepto'] == $use_dos[0] && $it['id_prospecto'] == $use_dos[1] && ($it['Nopago'] == '' || $it['Nopago'] == NULL || $it['Nopago'] == 0)){
                                $acc = $it;
                            }
                            return $acc;
                        }, false) : $promo_con;

                        $use_tres = [$info['conceptos'][$c]['id_concepto']];
                        $promo_con = ($promo_con) ? array_reduce($info['promociones'], function($acc, $it) use ($use_tres){
                            if($it['id_concepto'] == $use_tres[0] && ($it['Nopago'] == '' || $it['Nopago'] == NULL || $it['Nopago'] == 0)){
                                $acc = $it;
                            }
                            return $acc;
                        }, false) : $promo_con;

                        if($promo_con && !$pago_mensualidad){
                            $pago_fin = number_format(floatval($info['conceptos'][$c]['precio']) - floatval($info['conceptos'][$c]['precio']) * (floatval($promo_con['porcentaje']) / 100), 2).' '.($info['alumno']['tipoPago'] == '2' ? 'USD' : 'MXN');
                            $vigencia = '';
                            if($promo_con['fechainicio'] != null && $promo_con['fechafin'] != null){
                                $vigencia = '<i style="font-size:small;" class="fa fa-info" ></i>';
                            }else if(!empry($promo_con['Nopago'])){
                                $vigencia = '<i style="font-size:small;" class="fa fa-info"></i>';
                            }
                            $info_promo = floatval($promo_con['porcentaje']) > 1 ? round(floatval($promo_con['porcentaje']), 2).'%- '.$vigencia : '';
                        }
                    }

                    $sub_des_ins[] = [
                        strtoupper('Reinscripción '.($i+1)),
                        $pago_fin,
                        $info_promo,
                        date('Y-m-d', $aux_mens),
                        $estatus_pago
                    ];

                    $sub_td_reins .= '<tr>
                    <td>Reinscripción '.($i+1).'</td>
                    <td>'.$pago_fin.'</td>
                    <td>'.$info_promo.'</td>
                    <td>'.date('Y-m-d', $aux_mens).'</td>
                    <td>'.$estatus_pago.'</td>
                    </tr>';
                    $aux_mens = strtotime('+1 month', $aux_mens);
                }
                $tabla_conceptos .= '<tr id="desglose_reinscripcion" style=""><td colspan="5"><table class="sub-table"><tbody>'.$sub_td_reins.'</tbody></table></td></tr>';
                $concepto_arr['sub_desglose'] = $sub_des_ins;
            } */
            if($info['conceptos'][$c]['categoria'] != 'Generales' && $info['conceptos'][$c]['categoria'] != 'General'){
                $desglose_concepto[] = $concepto_arr;
            }
        }
        
        return $desglose_concepto;
    }
?>