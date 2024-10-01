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
                    'Title'=>'Libro Diario',
                    'Subject'=>'detalle del Libro Diario',
                    'Author'=>'25 de junio',
                    'Producer'=>'Andres Anrrango'
                    );
$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));

        $id_empresa_cookies = $_COOKIE["id_empresa"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
        
        
        $cmbCosto = $_GET['cmbCentro'];
        $fecha_desde_principal = explode(" ", ($_GET['fecha_desde']));
        $fecha_hasta_principal = explode(" ", ($_GET['fecha_hasta']));
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
        $pdf->ezText("<b>LIBRO DIARIO</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        $pdf->ezText("<b>Desde ".strtoupper($fecha_desde_principal[0])."    Hasta ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
        $txttit.= "";

$sql = "SELECT
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,     
     libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
     libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`total_debe` AS libro_diario_total_debe,
     libro_diario.`total_haber` AS libro_diario_total_haber,
     libro_diario.`descripcion` AS libro_diario_descripcion,
     libro_diario.`tipo_comprobante` AS libro_diario_tipo_comprobante,
     libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
     libro_diario.`centroCosto` AS centroCosto,
     periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
     periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
     periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
     periodo_contable.`estado` AS periodo_contable_estado,
     periodo_contable.`ingresos` AS periodo_contable_ingresos,
     periodo_contable.`id_empresa` AS periodo_contable_id_empresa,
     periodo_contable.`gastos` AS periodo_contable_gastos

FROM
     `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";

if (isset($_GET['criterio_usu_per']))
    $sql .= " where periodo_contable.`id_empresa`='".$sesion_id_empresa."' AND libro_diario.`numero_asiento` like '%".$_GET['criterio_usu_per']."%' AND periodo_contable.`id_periodo_contable` = '".$_GET['txtIdPeriodoContable']."' AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' ";


if (isset($_GET['criterio_ordenar_por']))
    $sql .= " order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']." ";
   
else
    $sql .= " order by libro_diario.`numero_asiento` asc";
if (isset($_GET['criterio_mostrar']))
	$sql .=" LIMIT ". $_GET['criterio_mostrar']."";
//echo $sql;
        $result = mysql_query($sql) or die(mysql_error());
        $aux1=0;
        //$ixx = 0;
        while($row = mysql_fetch_assoc($result)) { // ********************** FOR MAYOR **************************
            $id_libro_diario = $row["libro_diario_id_libro_diario"];
             $libro_diario_fecha = $row["libro_diario_fecha"];
             $libro_diario_numero_asiento = $row["libro_diario_numero_asiento"];
             $tipo_comprobante = $row["libro_diario_tipo_comprobante"];
             $descripcion = $row["libro_diario_descripcion"];
             $libro_diario_numero_comprobante = $row["libro_diario_numero_comprobante"];
//             $detalle_libro_diario_haber = $row["detalle_libro_diario_haber"];

                //$ixx = $ixx+1;
                //$data[] = array_merge($row, array('num'=>$ixx));

             $fecha = explode(" ", $libro_diario_fecha);
             $titulos1 = array(
                'tipo_comprobante' => $tipo_comprobante.' Nro.'.$libro_diario_numero_comprobante,
                'numero_asiento'=>'Asiento Nro.'.$libro_diario_numero_asiento,
                 'fecha'=>$fecha[0]

            );
             
             $data1 = array(
                 );

                $sql2 = "SELECT
     detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
     detalle_libro_diario.`id_libro_diario` AS detalle_libro_diario_id_libro_diario,
     detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
     detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
     detalle_libro_diario.`haber` AS detalle_libro_diario_haber,     
     plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`clasificacion` AS plan_cuentas_clasificacion,
     plan_cuentas.`tipo` AS plan_cuentas_tipo,
     plan_cuentas.`categoria` AS plan_cuentas_categoria,
     plan_cuentas.`nivel` AS plan_cuentas_nivel,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     plan_cuentas.`total` AS plan_cuentas_total
FROM
     `plan_cuentas` plan_cuentas INNER JOIN `detalle_libro_diario` detalle_libro_diario ON plan_cuentas.`id_plan_cuenta` = detalle_libro_diario.`id_plan_cuenta` 
     WHERE  plan_cuentas.`id_empresa`='".$sesion_id_empresa."' AND detalle_libro_diario.`id_libro_diario`='".$id_libro_diario."' AND detalle_libro_diario.`id_periodo_contable` = '".$_GET['txtIdPeriodoContable']."';";
     
   

        $result2=mysql_query($sql2);
        
        $detalle_libro_diario_id_detalle_libro_diario = array();
        $plan_cuentas_codigo = array();
        $plan_cuentas_nombre = array();
        $detalle_libro_diario_debe = array();
        $detalle_libro_diario_haber = array();
        $b=0;
         //   echo "   *************       ".$sql2;
        $numero_filas_detalle_libro_diario = mysql_num_rows($result2); // obtenemos el número de filas
        if($numero_filas_detalle_libro_diario>0){
            
        $titulos2 = array(
            'codigo' =>'Codigo',
            'cuenta'=>'Cuenta',
            'debito'=>'Debito',
            'credito'=>'Credito'

        );
        $sumadebe = 0;
        $sumahaber = 0;
        $data2 = "";
        while($row2=mysql_fetch_assoc($result2))//********************* FOR SECUNDARIO ********************************
        {
//            $detalle_libro_diario_id_detalle_libro_diario[$b] = $row2['detalle_libro_diario_id_detalle_libro_diario'];
//            $plan_cuentas_codigo[$b] = $row2['plan_cuentas_codigo'];
//            $plan_cuentas_nombre[$b] = $row2['plan_cuentas_nombre'];
//            $detalle_libro_diario_debe[$b] = $row2['detalle_libro_diario_debe'];
//            $detalle_libro_diario_haber[$b] = $row2['detalle_libro_diario_haber'];
            $sumadebe = $sumadebe + $row2['detalle_libro_diario_debe'];
            $sumahaber = $sumahaber + $row2['detalle_libro_diario_haber'];
//            $b++;

            $data2[] = array(
            'codigo'=>$row2['plan_cuentas_codigo'],
            'cuenta'=>utf8_decode($row2['plan_cuentas_nombre']),
            'debito'=>number_format($row2['detalle_libro_diario_debe'], 2, '.', ' '),
            'credito'=>number_format($row2['detalle_libro_diario_haber'], 2, '.', ' ')
            );
        } 
        

        $data3=array(

              );


        $titulos3=array(
                        'total'=>'Detalle: '.$descripcion,
                      'debito'=>$sumadebe = number_format($sumadebe, 2, '.', ' '),
                      'credito'=>$sumahaber = number_format($sumahaber, 2, '.', ' ')
                     );

        $opciones1 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                            'tipo_comprobante'=>array('justification'=>'center','width'=>100),
                            'numero_asiento'=>array('justification'=>'center','width'=>250),
                            'fecha'=>array('justification'=>'center','width'=>200)
                             )
                        );
        $opciones2 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                                'codigo'=>array('justification'=>'left','width'=>100),
                                'cuenta'=>array('justification'=>'','width'=>250),
                                'debito'=>array('justification'=>'right','width'=>100),
                                'credito'=>array('justification'=>'right','width'=>100)
                             )
                        );
        $opciones3 = array(
                'shadeCol'=>array(0.9,0.9,0.9),
                'xOrientation'=>'center',
                'width'=>550,
                'cols'=>array(
                              'total'=>array('justification'=>'left','width'=>350),
                              'debito'=>array('justification'=>'right','width'=>100),
                                'credito'=>array('justification'=>'right','width'=>100)
                             )
            );
        $pdf->ezTable($data1, $titulos1, '', $opciones1);
        $pdf->ezText($txttit, 1);
        $pdf->ezTable($data2, $titulos2, '', $opciones2);
        $pdf->ezText("\n", 1);
        $pdf->ezTable($data3, $titulos3, '', $opciones3);
        $pdf->ezText("\n\n\n", 10);
            
        }
        
        }       


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
                // 'empleado'=>array('width'=>200,'justification'=>'center')
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
        $nombrearchivo = "reporteLibroDiario.pdf";
        $pdf->ezStream();
        $pdf->ezOutput($nombrearchivo);

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));
      

        mysql_close();
        mysql_free_result($result);
    
?>

