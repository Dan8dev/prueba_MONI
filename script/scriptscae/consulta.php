<?php
	include("Conexion.php");
function consultar_pagos($idAlumno){
    $Conexion = new Conexion();
    $con = $Conexion->conectar();
	$DetallePago = $Nombre = $Carrera = $PlanPago = $Moneda = NULL;
    $DetallePago_arr = [];
	if($Conexion)
	{
		if(!empty($idAlumno))
		{
			$Resultado = "Exitoso";

            $SQLDatos =
            "SELECT
                CONCAT(A.ApellidoPaterno,' ',A.ApellidoMaterno,' ',A.Nombres) AS NombreCompleto, A.Correo,
                C.Carrera,
                CONCAT(G.Generacion,'&ordm; Generaci&oacute;n') AS Generacion,
                (SELECT C.Ciclo FROM (CiclosAlumnos CA JOIN Ciclos C ON CA.IDCiclo=C.IDCiclo) WHERE CA.IDAlumno=A.IDAlumno ORDER BY CA.IDCicloAlumno DESC LIMIT 1) AS Ciclo,
                (SELECT PP.PlanPago FROM (((PlanesPagosAlumnos PPA JOIN PlanesPagoGeneracionesFechas PPGF ON PPA.IDPlanPagoGeneracionFecha=PPGF.IDPlanPagoGeneracionFecha) JOIN PlanesPagoGeneraciones PPG ON PPGF.IDPlanPagoGeneracion=PPG.IDPlanPagoGeneracion) JOIN PlanesPago PP ON PPG.IDPlanPago=PP.IDPlanPago) WHERE PPA.IDAlumno=\"".$idAlumno."\" ORDER BY PPA.IDPlanPagoGeneracionFecha ASC LIMIT 1) AS PlanPago,
                (SELECT M.Moneda FROM ((((PlanesPagosAlumnos PPA JOIN PlanesPagoGeneracionesFechas PPGF ON PPA.IDPlanPagoGeneracionFecha=PPGF.IDPlanPagoGeneracionFecha) JOIN PlanesPagoGeneraciones PPG ON PPGF.IDPlanPagoGeneracion=PPG.IDPlanPagoGeneracion) JOIN PlanesPago PP ON PPG.IDPlanPago=PP.IDPlanPago) JOIN Monedas M ON PP.IDMoneda=M.IDMoneda) WHERE PPA.IDAlumno=\"".$idAlumno."\" ORDER BY PPA.IDPlanPagoGeneracionFecha ASC LIMIT 1) AS Moneda
                FROM
                    ((((CarreraInteres CI JOIN Alumnos A ON CI.IDAlumno=A.IDAlumno)
                    JOIN Carreras C ON CI.IDCarrera=C.IDCarrera)
                    JOIN GeneracionesAlumnos GA ON A.IDAlumno=GA.IDAlumno)
                    JOIN Generaciones G ON GA.IDGeneracion=G.IDGeneracion)
                WHERE
                    A.IDAlumno=\"".$idAlumno."\"
            ";
            $Datos = $con->query($SQLDatos)->fetch(PDO::FETCH_ASSOC);
            // var_dump($Datos);
                $Nombre = utf8_encode($Datos["NombreCompleto"]);
                $Correo = utf8_encode($Datos["Correo"]);
                $Carrera = utf8_encode($Datos["Carrera"]." - ".$Datos["Generacion"]." - ".$Datos["Ciclo"]);
                $PlanPago = utf8_encode($Datos["PlanPago"]);
                $Moneda = utf8_encode($Datos["Moneda"]);

            $Fecha = date("Y-m-d");
            $TipoPago = $Beca = $Promocion = $Bonificacion = "";
			$SubTotal = $TotalPagos = $Saldo = $Recargo = $TotalPagar = $Cont = $TieneBeca = $TienePromocion = $TieneBonificacion = 0;
            $SQL =
            "SELECT
                PPA.IDPlanPagoAlumno,
                P.Costo,
                PPA.FechaFinal,
                P.NumeroPago,
                IF(P.TipoPago=1,IF(P.NumeroPago=1,'Inscripción',CONCAT('Reinscripción', P.NumeroPago)),CONCAT('Mensualidad', P.NumeroPago)) AS TipoPago,
                IF(P.TipoPago=1,IF(P.NumeroPago=1,'Inscripción','Reinscripción'),'Mensualidad') AS Concepto,
                (SELECT IF(COUNT(B.Cantidad)>0,B.Cantidad,'0') FROM BecasAlumnos BA JOIN Becas B ON BA.IDBeca=B.IDBeca WHERE BA.IDPlanPagoAlumno=PPA.IDPlanPagoAlumno AND BA.Estatus='1') AS Beca,
                (SELECT IF(COUNT(Cantidad)>0,Cantidad,'0') FROM PromocionesAlumnos WHERE IDPlanPagoAlumno=PPA.IDPlanPagoAlumno AND Estatus='1') AS Promocion,
                (SELECT IF(COUNT(P.Promocion)>0,P.Promocion,'') FROM (PromocionesAlumnos PA JOIN Promociones P ON PA.IDPromocion=P.IDPromocion) WHERE PA.IDPlanPagoAlumno=PPA.IDPlanPagoAlumno AND PA.Estatus='1') AS NombrePromocion,
                (SELECT IF(COUNT(Bonificacion)>0,Bonificacion,'0') FROM Bonificaciones WHERE IDPlanPagoAlumno=PPA.IDPlanPagoAlumno AND Estatus='1') AS Bonificacion,
                (SELECT IF(COUNT(Cantidad)>0,SUM(Cantidad),'0') FROM Depositos WHERE IDPlanPagoAlumno=PPA.IDPlanPagoAlumno AND FechaPago<=PPA.FechaFinal AND Estatus='1') AS PagosAntesFechaFinal,
                IF(P.TipoPago='1','ATiempo',IF('".$Fecha."'<=PPA.FechaFinal,'ATiempo','FueraDeTiempo')) AS BanderaRecargo
                FROM 
                    ((PlanesPagosAlumnos PPA JOIN PlanesPagoGeneracionesFechas PPGF ON PPA.IDPlanPagoGeneracionFecha=PPGF.IDPlanPagoGeneracionFecha)
                    JOIN Pagos P ON PPGF.IDPago=P.IDPago)
                WHERE
                    PPA.IDAlumno=\"".$idAlumno."\"
                ORDER BY
                    PPA.FechaFinal ASC, P.TipoPago ASC
            ";
            // echo $SQL;
			$Consulta = $con->query($SQL);
            $consultados = $Consulta->fetchAll(PDO::FETCH_ASSOC);
            $complicado = false;
			foreach($consultados as $ResultadoConsulta)
			{
                $SubTotal = $ResultadoConsulta["Costo"];
                //-- BECAS --//
                if($ResultadoConsulta["Beca"]>0)
                {
                    $SubTotal = $SubTotal-($SubTotal*($ResultadoConsulta["Beca"]/100));
                    $Beca = $ResultadoConsulta["Beca"]."%   Beca";
                    $TieneBeca = 1;
                }
                //-- PROMOCIONES --//
                if($ResultadoConsulta["Promocion"]>0)
                {
                    $SubTotal = $SubTotal-($SubTotal*($ResultadoConsulta["Promocion"]/100));
                    $Promocion = $ResultadoConsulta["Promocion"]."% ".utf8_encode($ResultadoConsulta["NombrePromocion"]);
                    $TienePromocion = 1;
                }
                //-- BONIFICACIONES --//
                if($ResultadoConsulta["Bonificacion"]>0)
                {
                    $SubTotal = $SubTotal-$ResultadoConsulta["Bonificacion"];
                    $Bonificacion = $ResultadoConsulta["Bonificacion"];
                    $TieneBonificacion = 1;
                }
                //-- PAGOS --//
                $Pagos = "";
                $arr_p = [];
                $ContPagos = 0;
                $ConsultaPago = $con->query("SELECT D.Cantidad, FP.FormaPago, D.FechaPago, D.IdentificadorPago, D.FechaRegistro FROM Depositos D JOIN FormasPago FP ON D.IDFormaPago=FP.IDFormaPago WHERE D.IDPlanPagoAlumno=\"".$ResultadoConsulta["IDPlanPagoAlumno"]."\" AND D.Estatus='1' ORDER BY D.FechaPago ASC");
                $ConsultaPago = $ConsultaPago->fetchAll(PDO::FETCH_ASSOC);
                foreach($ConsultaPago as $ResultadoConsultaPago)
                {
                    $TotalPagos += $ResultadoConsultaPago["Cantidad"];
                    if($ContPagos>0){ $Pagos .= ","; }
                    $ContPagos++;
                    $arr_p[] = [
                        'Pago'=>$ResultadoConsultaPago["Cantidad"],
                        'FormaPago'=>$ResultadoConsultaPago["FormaPago"],
                        'FechaPago'=>$ResultadoConsultaPago["FechaPago"],
                        'FechaRegistro'=>$ResultadoConsultaPago["FechaRegistro"],
                        'IdentificadorPago'=>$ResultadoConsultaPago["IdentificadorPago"]
                    ];
                    $Pagos .=
                    "
                    {
                        \"Pago\": \"".$ResultadoConsultaPago["Cantidad"]."\",
                        \"FormaPago\": \"".utf8_encode($ResultadoConsultaPago["FormaPago"])."\",
                        \"FechaPago\": \"".($ResultadoConsultaPago["FechaPago"])."\"
                    }
                    ";
                }
                
                //-- SALDO --//
                $Saldo = $SubTotal-$ResultadoConsulta["PagosAntesFechaFinal"];
                
                //-- RECARGO --//
                if($ResultadoConsulta["BanderaRecargo"]=="FueraDeTiempo")
                {
                    if($ResultadoConsulta["PagosAntesFechaFinal"]<$SubTotal){ $Recargo = round($Saldo*0.15,1); }
                    else{ $Recargo = 0; }
                }else{ $Recargo = 0; }
                
                //-- TOTAL A PAGAR --//
                $TotalPagar = ($SubTotal+$Recargo)-$TotalPagos;
                
				if($Cont>0){ $DetallePago .= ","; }
				$Cont++;
                if(!empty($arr_p)){
                    if($Saldo != 0 || $Recargo != 0){
                        $complicado = true;
                    }
                    $DetallePago_arr[] = [
                        'IDPlanPagoAlumno'=>$ResultadoConsulta["IDPlanPagoAlumno"],
                        'TipoPago'=>$ResultadoConsulta["TipoPago"],
                        'Concepto'=>$ResultadoConsulta["Concepto"],
                        'NumeroPago'=>$ResultadoConsulta["NumeroPago"],
                        'Costo'=>$ResultadoConsulta["Costo"],
                        'Beca'=>$Beca,
                        'Promocion'=>$Promocion,
                        'Bonificacion'=>$Bonificacion,
                        'TieneBeca'=>$TieneBeca,
                        'TienePromocion'=>$TienePromocion,
                        'TieneBonificacion'=>$TieneBonificacion,
                        'SubTotal'=>$SubTotal,
                        'Pagos'=>$arr_p,
                        'Saldo'=>$Saldo,
                        'Recargo'=>$Recargo,
                        'TotalPagar'=>$TotalPagar,
                        'FechaLimitePago'=>$ResultadoConsulta["FechaFinal"],
                    ];
                    $DetallePago .= 
                    "
                    {
                        \"IDPlanPagoAlumno\": \"".$ResultadoConsulta["IDPlanPagoAlumno"]."\",
                        \"TipoPago\": \"".$ResultadoConsulta["TipoPago"]."\",
                        \"Costo\": \"".$ResultadoConsulta["Costo"]."\",
                        \"Beca\": \"".$Beca."\",
                        \"Promocion\": \"".$Promocion."\",
                        \"Bonificacion\": \"".$Bonificacion."\",
                        \"TieneBeca\": \"".$TieneBeca."\",
                        \"TienePromocion\": \"".$TienePromocion."\",
                        \"TieneBonificacion\": \"".$TieneBonificacion."\",
                        \"SubTotal\": \"".$SubTotal."\",
                        \"Pagos\": [".$Pagos."],
                        \"Saldo\": \"".$Saldo."\",
                        \"Recargo\": \"".$Recargo."\",
                        \"TotalPagar\": \"".$TotalPagar."\",
                        \"FechaLimitePago\": \"".($ResultadoConsulta["FechaFinal"])."\"
                    }
                    ";
                }
                
                //--LIMPIO VARIABLES--//
                $SubTotal = $TotalPagos = $TieneBeca = $TienePromocion = $TieneBonificacion = 0;
			}
		}else{ $Resultado = "ErrorDatos"; }
	}
    $resp = [
        'complicado'=>($complicado ? 1 : 0),
        'resultado'=>$Resultado,
        'detallePago'=>$DetallePago_arr,
        'NombreAlumno'=> $Nombre,
        'Correo'=> $Correo,
        'Carrera'=> $Carrera,
        'Moneda'=> $Moneda
    ];

    return $resp;
}

function formato_pago($id_pago, $monto, $fecha, $nombres, $apellidoP, $apellidoM, $correo, $plan_pago){
    $nombre_completo = $nombres." ".$apellidoP." ".$apellidoM;           

    $infopago = array(
        'id' => $id_pago,
        'intent' => 'CAPTURE',
        'status' => 'COMPLETED',
        'purchase_units' => array(
            array(
                'reference_id' => 'default',
                'amount' => array(
                    'currency_code' => 'MXN',
                    'value' => $monto
                ),
                'payee' => array(
                    'email_address' => 'pagos@universidaddelconde.edu.mx',
                    'merchant_id' => 'AZUHGK3DWV9NC'
                ),
                'description' => $plan_pago,
                'soft_descriptor' => 'PAYPAL *UNIVERSIDAD',
                'shipping' => array(
                    'name' => array(
                        'full_name' => $nombre_completo
                    ),
                    'address' => array(
                        'address_line_1' => '',
                        'address_line_2' => '',
                        'admin_area_2' => '',
                        'admin_area_1' => '',
                        'postal_code' => '',
                        'country_code' => 'MX'
                    )
                ),
                'payments' => array(
                    'captures' => array(
                        array(
                            'id' => $id_pago,
                            'status' => 'COMPLETED',
                            'amount' => array(
                                'currency_code' => 'MXN',
                                'value' => $monto
                            ),
                            'final_capture' => true,
                            'seller_protection' => array(
                                'status' => 'ELIGIBLE',
                                'dispute_categories' => array(
                                    'ITEM_NOT_RECEIVED',
                                    'UNAUTHORIZED_TRANSACTION'
                                )
                            ),
                            'create_time' => $fecha.'T16:36:52Z',
                            'update_time' => $fecha.'T16:36:52Z'
                        )
                    )
                )
            )
        ),
        'payer' => array(
            'name' => array(
                'given_name' => $nombres,
                'surname' => $apellidoP
            ),
            'email_address' => $correo,
            'payer_id' => '-',
            'address' => array(
                'country_code' => 'MX'
            )
        ),
        'create_time' => $fecha.'T16:36:52Z',
        'update_time' => $fecha.'T16:36:52Z'
    );

    return $infopago;
  }
?>
