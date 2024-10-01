<?php
//ob_end_clean();
//Start session

session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$datacreador = array (
                    'Title'=>'Libro Mayor',
                    'Subject'=>'detalle del Libro Mayor',
                    'Author'=>'25 de junio',
                    'Producer'=>'Macarena  Lalama'
                    );
$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

$id_empresa_cookies = $_COOKIE["id_empresa"];

        $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
        $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
        $pdf->ezText("<b>LIBRO MAYOR</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));

$sql = "SELECT
     mayorizacion.`id_mayorizacion` AS mayorizacion_id_mayorizacion,
     mayorizacion.`id_plan_cuenta` AS mayorizacion_id_plan_cuenta,
     mayorizacion.`id_periodo_contable` AS mayorizacion_id_periodo_contable,
     periodo_contable.`estado` AS periodo_contable_estado,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     libro_diario.`fecha` AS libro_diario_fecha, libro_diario.`numero_asiento` AS numero_asiento
     
FROM
     `mayorizacion` mayorizacion INNER JOIN `periodo_contable` periodo_contable ON mayorizacion.`id_periodo_contable` = periodo_contable.`id_periodo_contable`
     INNER JOIN `plan_cuentas` plan_cuentas ON mayorizacion.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
     INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";

        if ($_GET['txtIdPlanCuenta'] >= 1){
		$sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."'
		AND plan_cuentas.`id_plan_cuenta` = '".$_GET['txtIdPlanCuenta']."' AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
                else {$sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' 
                AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND plan_cuentas.`id_plan_cuenta` like '".$_GET['txtIdPlanCuenta']."%' 
                AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
                
	if (isset($_GET['criterio_ordenar_por']))
		$sql .= " GROUP BY plan_cuentas.`codigo` order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']."";
	else
		$sql .= " GROUP BY plan_cuentas.`codigo` order by plan_cuentas.`codigo` asc ";
		
        if (isset($_GET['criterio_mostrar']))
		$sql .= " LIMIT ".((int)$_GET['criterio_mostrar']);

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
       if($sesion_id_empresa==41){
        //   echo $sql;
       }
        // echo $sql."***    ".$numero_filas;
        // exit;
        $data2 = array(
                 );
       while($row = mysql_fetch_array($result)){ //************************* FOR MAYOR ****************************************
            $numero ++;
            $mayorizacion_id_plan_cuenta = $row['mayorizacion_id_plan_cuenta'];
            $plan_cuentas_codigo = $row['plan_cuentas_codigo'];
            $plan_cuentas_nombre = $row['plan_cuentas_nombre'];


            $titles2 = array(
                'codigo' => 'Codigo: '.$plan_cuentas_codigo,
                'cuenta' => 'Cuenta: '.utf8_decode($plan_cuentas_nombre)

                );
            
            //primera
             

            $sql2 = "SELECT
     detalle_libro_diario.`id_libro_diario`,
     detalle_libro_diario.`debe`,
     detalle_libro_diario.`haber`,
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`numero_asiento` AS numero_asiento
     
FROM
     `libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario 
     ON libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
           AND libro_diario.id_periodo_contable= detalle_libro_diario.`id_periodo_contable`
            WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' and 
            detalle_libro_diario.`id_plan_cuenta`='".$mayorizacion_id_plan_cuenta."' AND 
            detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."' ";
            if (isset($_GET['criterio_ordenar_por']))
		$sql2 .= " order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']."";
	else
		$sql2 .= "  order by plan_cuentas.`codigo` asc ";
// echo $sql2;
        
            $result2=mysql_query($sql2);
            $numFilasDetallesLB= mysql_num_rows($result2);
            if($numFilasDetallesLB>0){
                 $debe_detalle_mayorizacion = array();
            $haber_detalle_mayorizacion = array();
            $id_libro_diario = array();
            $id_libro_diario2 = "";
            $fecha = "";
            $numero_comprobante = "";
            $b=0;
            $sumadebe = 0;
            $sumahaber = 0;
            $saldo=0;
            $numero_filas_detalle_mayorizacion = mysql_num_rows($result2); // obtenemos el número de filas
            while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
            {
                $sumadebe = $sumadebe + $row2['debe'];
                $sumahaber = $sumahaber + $row2['haber'];
                $debe_detalle_mayorizacion[$b] = $row2['debe'];
                $haber_detalle_mayorizacion[$b] = $row2['haber'];
                $id_libro_diario[$b] = $row2['id_libro_diario'];
                $b++;
            }
            $data1 = "";
            for($j=0; $j<$numero_filas_detalle_mayorizacion; $j++){

                $sql3 = "SELECT
                         libro_diario.`id_libro_diario`,
                         libro_diario.`fecha`,
                         libro_diario.`numero_comprobante`,
                         libro_diario.`descripcion`,
                        
                         libro_diario.`tipo_comprobante`, libro_diario.`numero_asiento`
                         
                         
                    FROM
                         `libro_diario` libro_diario
                    WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' AND libro_diario.`id_libro_diario`='".$id_libro_diario[$j]."';  ";
                $result3=mysql_query($sql3);
                while($row3=mysql_fetch_array($result3))//permite ir de fila en fila de la tabla
                {
                    $id_libro_diario2 = $row3['id_libro_diario'];
                    $fecha = $row3['fecha'];
                    $numero_asiento = $row3['numero_asiento'];
                    
                    $numero_comprobante = $row3['numero_comprobante'];
                    $tipo_comprobante = $row3['tipo_comprobante'];
                    $descripcion = $row3['descripcion'];
                     $descripcion = $row3['descripcion'];
                }
                if($tipo_comprobante == "Diario"){
                    $letra_tipo_compro = "D-";
                }
                if($tipo_comprobante == "Ingreso"){
                    $letra_tipo_compro = "I-";
                }
                if($tipo_comprobante == "Egreso"){
                    $letra_tipo_compro = "E-";
                }
                $fecha2 = explode(" ", $fecha);
                 $saldo = $saldo+  $debe_detalle_mayorizacion[$j] -$haber_detalle_mayorizacion[$j]; 
                $data1[] = array(
                'asientoNum'=>$numero_asiento,
                'asiento'=>$letra_tipo_compro.$numero_comprobante,
                'fecha'=>$fecha2[0],
                'descripcion'=>utf8_decode($descripcion),
                'debito'=>number_format($debe_detalle_mayorizacion[$j], 4, '.', ' '),
                'credito'=>number_format($haber_detalle_mayorizacion[$j], 4, '.', ' '),
                'saldo'=>number_format($saldo, 4, '.', ' '),
                );

                $titles1 = array(
                    'asientoNum' => '<b>Numero</b>',
                'asiento' => '<b>Cpte. Nro.</b>',
                'fecha' => '<b>Fecha</b>',                
                'descripcion' => '<b>'.utf8_decode('Descripción').'</b>',
                'debito' => '<b>Debito</b>',
                'credito' => '<b>Credito</b>',
                'saldo' => '<b>Saldo</b>'
                );

                //segunda
                $saldo_string = "";
                $saldo_deudor = 0;
                $saldo_acreedor =0;
                if($sumadebe > $sumahaber){
                    $saldo_deudor = $sumadebe - $sumahaber;
                    $saldo_string = "SD";
                }
                if($sumadebe < $sumahaber){
                    $saldo_acreedor = $sumahaber - $sumadebe;
                    $saldo_string = "SA";
                }
                if($saldo_deudor == "0" && $saldo_acreedor =="0"){
                    $saldo_string = "S";
                }
            }
            $data3 = array(
                );

            if($sumadebe == 0 & $sumahaber == 0){
                $numero --;
            }else{

                $titles3 = array(
                'codigo'=>'',
                'descripcion'=>'SUMAS: ',
                'debito'=>number_format($sumadebe, 4, '.', ' '),
                'credito'=>number_format($sumahaber, 4, '.', ' '),
                'saldo'=>number_format($saldo_deudor+$saldo_acreedor, 4, '.', ' '),

                );
                
            }

            $options1 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                            'asientoNum'=>array('justification'=>'left','width'=>35),
                            'asiento'=>array('justification'=>'left','width'=>35),
                            'fecha'=>array('justification'=>'left','width'=>70),
                            'descripcion'=>array('justification'=>'left','width'=>230),
                            'debito'=>array('justification'=>'right','width'=>60),
                            'credito'=>array('justification'=>'right','width'=>60),
                            'saldo'=>array('justification'=>'right','width'=>60)
                             )
                        );

            $options2 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                                    'codigo'=>array('justification'=>'left','width'=>140),
                                    'fecha'=>array('justification'=>'left','width'=>410),

                                     )
                        );
            $options3 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                            'codigo'=>array('justification'=>'left','width'=>140),
                            'descripcion'=>array('justification'=>'left','width'=>230),
                            'debito'=>array('justification'=>'right','width'=>60),
                            'credito'=>array('justification'=>'right','width'=>60),
                            'saldo'=>array('justification'=>'right','width'=>60)

                                     )
                        );

            $pdf->ezTable($data2, $titles2, '', $options2);
            $pdf->ezText($txttit, 0.5);
            $pdf->ezTable($data1, $titles1, '', $options1);
            $pdf->ezText($txttit, 2);
            $pdf->ezTable($data3, $titles3, '', $options3);
            
            //$pdf->ezTable($data3, $titles3, '', $options3);
            $pdf->ezText("\n\n\n", 10);
            }
           
        }
        
        
        //$pdf->selectFont('Arial','B',14); // establece la fuente, le tipo ( 'B' para negrita, 'I' para itálica, '' para normal,...)
        //$pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
        //$pdf->setLineStyle(1,'square');
        //$pdf->setStrokeColor(0,0,0);
        //$pdf->line('550', '750', '40', '750');
        
        $txttit.= "";
        $pdf->ezText($txttit, 12);
                $sqlEmpresa= " SELECT `razonSocial`, `nombreContador` FROM `empresa`  WHERE empresa.`id_empresa`=$sesion_id_empresa ";
      
    $resultEmpresa= mysql_query($sqlEmpresa);
    while($rEmpresa = mysql_fetch_array($resultEmpresa)){
        $razonSocialEmpresa = $rEmpresa['razonSocial'];
        $nombreContador=$rEmpresa['nombreContador'];
        
    }
    
    //  $razonSocialEmpresa='COMPAÑIA DE TRANSPORTE PUBLICO DE PASAJEROS INTRAPROVINCIAL CIUDAD DE ALAMOR TRANSALAMOR';
    // $nombreContador = "Genesis Castro";
    // $nombreEmpleado="ESCOBAR PAZMIÑO WILSON ROBERTO Calle: SIMON BOLIVAR Intersección: JUAN".' '."ESCOBAR PAZMIÑO WILSON ROBERTO Calle: SIMON BOLIVAR Intersección: JUAN";
    


    $opcionFirmas = array(
        'showHeadings'=>0,
        'shaded'=>0,
        'showLines'=>0,
       
        'cols'=>array(
                'razonSocial'=>array('width'=>300,'justification'=>'center'),
                'contador'=>array('width'=>300,'justification'=>'center'),
              //  'empleado'=>array('width'=>200,'justification'=>'center')
            )
        );
    $data =array( 
          array('razonSocial'=>'_____________________________','contador'=>'_____________________________','empleado'=>''),
        array('razonSocial'=>utf8_decode($nombreContador),'contador'=>utf8_decode($razonSocialEmpresa),'empleado'=>''),
         array('razonSocial'=>'DPTO. CONTABILIDAD','contador'=>'REPRESENTANTE LEGAL','empleado'=>'')
    
    );

     $pdf->ezTable($data,array('razonSocial'=>'','contador'=>'','empleado'=>''),'',$opcionFirmas);
    
        //$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
        $pdf->ezStartPageNumbers(550, 80, 10);
        //$nombrearchivo = "reporteLibroMayor.pdf";
        $pdf->ezStream();
        //$pdf->ezOutput($nombrearchivo);
        $pdf->Output('reporteLibroMayor.pdf', 'D');

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));


        mysql_close();
        mysql_free_result($result);

?>