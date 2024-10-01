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
                    'Title'=>'Comprobante',
                    'Subject'=>'detalle de Comprobante',
                    'Author'=>'25 de junio',
                    'Producer'=>'Andres Anrrango'
                    );
$pdf->addInfo($datacreador);

$sql = "SELECT
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,     
     libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
     libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`total_debe` AS libro_diario_total_debe,
     libro_diario.`total_haber` AS libro_diario_total_haber,
     libro_diario.`descripcion` AS libro_diario_descripcion,
     libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
     periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
     periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
     periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
     periodo_contable.`estado` AS periodo_contable_estado,
     periodo_contable.`ingresos` AS periodo_contable_ingresos,
     periodo_contable.`gastos` AS periodo_contable_gastos

FROM
     `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";

if ($_GET['txtIdComprobante'] >= 1){
            $sql .= " where libro_diario.`id_libro_diario` = '".($_GET['txtIdComprobante'])."' AND periodo_contable.`id_periodo_contable` = '".($_GET['txtIdPeriodoContable'])."' "; }
            else { $sql .= " where libro_diario.`id_libro_diario` like '%".($_GET['txtIdComprobante'])."%' AND periodo_contable.`id_periodo_contable` = '".($_GET['txtIdPeriodoContable'])."' "; }
    if (isset($_GET['txtFecha'])){
            $sql .= " AND libro_diario.`fecha` like '%".($_GET['txtFecha'])."%' "; }
    if (isset($_GET['criterio_ordenar_por'])){
            $sql .= " order by ". ($_GET['criterio_ordenar_por'])." ". ($_GET['criterio_orden']).""; }           
    else{
            $sql .= " order by libro_diario.`numero_asiento` asc"; }
    if (isset($_GET['criterio_mostrar']))
	$sql .=" LIMIT ". $_GET['criterio_mostrar']."";

        $result = mysql_query($sql) or die(mysql_error());
        $aux1=0;
        //$ixx = 0;
        while($row = mysql_fetch_array($result)) {
            $id_libro_diario = $row["libro_diario_id_libro_diario"];
             $libro_diario_fecha = $row["libro_diario_fecha"];
             $libro_diario_numero_asiento = $row["libro_diario_numero_asiento"];
             $libro_diario_descripcion = $row["libro_diario_descripcion"];
             $libro_diario_numero_comprobante = $row["libro_diario_numero_comprobante"];
//             $detalle_libro_diario_debe = $row["detalle_libro_diario_debe"];
//             $detalle_libro_diario_haber = $row["detalle_libro_diario_haber"];

                //$ixx = $ixx+1;
                //$data[] = array_merge($row, array('num'=>$ixx));

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
     plan_cuentas.`total` AS plan_cuentas_total
FROM
     `plan_cuentas` plan_cuentas INNER JOIN `detalle_libro_diario` detalle_libro_diario ON plan_cuentas.`id_plan_cuenta` = detalle_libro_diario.`id_plan_cuenta`
     WHERE detalle_libro_diario.`id_libro_diario`='".$id_libro_diario."' AND detalle_libro_diario.`id_periodo_contable` = '".$_GET['txtIdPeriodoContable']."';";
        $result2=mysql_query($sql2);
        
        $detalle_libro_diario_id_detalle_libro_diario = array();
        $plan_cuentas_codigo = array();
        $plan_cuentas_nombre = array();
        $detalle_libro_diario_debe = array();
        $detalle_libro_diario_haber = array();
        $b=0;        
        $num_filas_detalle_libro_diario = mysql_num_rows($result2); // obtenemos el número de filas
        
        $comprobante = "DIARIO";
        while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
        {
            $detalle_libro_diario_id_detalle_libro_diario[$b] = $row2['detalle_libro_diario_id_detalle_libro_diario'];
            $plan_cuentas_codigo[$b] = $row2['plan_cuentas_codigo'];
            $plan_cuentas_nombre[$b] = $row2['plan_cuentas_nombre'];
            $detalle_libro_diario_debe[$b] = $row2['detalle_libro_diario_debe'];
            $detalle_libro_diario_haber[$b] = $row2['detalle_libro_diario_haber'];
            $sumadebe = $sumadebe + $row2['detalle_libro_diario_debe'];
            $sumahaber = $sumahaber + $row2['detalle_libro_diario_haber'];
            $b++;
            if(stristr($row2['plan_cuentas_nombre'], 'Banco') || stristr($row2['plan_cuentas_nombre'], 'Bancos')){
                if($row2['detalle_libro_diario_debe'] > 0){
                    $comprobante = "INGRESO";
                }
                if($row2['detalle_libro_diario_haber'] > 0){
                    $comprobante = "EGRESO";
                }
            }
        }

        $aux1=0;
         for($j=0; $j<$num_filas_detalle_libro_diario; $j++){
        $data[] = array(

            'libro_diario_numero_asiento'=>$libro_diario_numero_asiento,            
            'plan_cuentas_codigo'=>$plan_cuentas_codigo[$j],
            'plan_cuentas_nombre'=>utf8_decode($plan_cuentas_nombre[$j]) ,
            'detalle_libro_diario_debe'=>$detalle_libro_diario_debe[$j],
            'detalle_libro_diario_haber'=>$detalle_libro_diario_haber[$j]
            );
         }
        $titles = array(
            'libro_diario_numero_asiento' => '<b>Asiento</b>',            
            'plan_cuentas_codigo' => '<b>Codigo</b>',
            'plan_cuentas_nombre' => '<b>Cuentas</b>',
            'detalle_libro_diario_debe' => '<b>Debe</b>',
            'detalle_libro_diario_haber' => '<b>Haber</b>',
        );
        $options = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>500,
                        'cols'=>array(
                              'detalle_libro_diario_debe'=>array('justification'=>'left','width'=>70),
                               'detalle_libro_diario_haber'=>array('justification'=>'left','width'=>70)
                             )
                        );        
    }
            /*************   2   **********/
//        $totales=array(
//
//              );
//
//        $totalestitulos=array(
//                        'descripcion'=>''.$libro_diario_descripcion,
//
//            );
//
//        $optotal = array(
//                'shadeCol'=>array(0.9,0.9,0.9),
//                'xOrientation'=>'center',
//                'width'=>550,
//                'cols'=>array(
//                              'descripcion'=>array('justification'=>'center','width'=>550),
//
//                             )
//            );

            /***********   3    ************/

         $totales1=array(

              );

        $totalestitulos1=array(
                        'total'=>'TOTALES',
                      'debe'=>$sumadebe = number_format($sumadebe, 2, '.', ' '),
                      'haber'=>$sumahaber = number_format($sumahaber, 2, '.', ' ')
            );

        $optotal1 = array(
                'shadeCol'=>array(0.9,0.9,0.9),
                'xOrientation'=>'center',
                'width'=>500,
                'cols'=>array(
                              'total'=>array('justification'=>'center','width'=>360),
                              'debe'=>array('justification'=>'left','width'=>70),
                               'haber'=>array('justification'=>'left','width'=>70)
                             )
            );
        
        //$pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
        //$pdf->setLineStyle(1,'square');
        //$pdf->setStrokeColor(0,0,0);
        //$pdf->line('550', '750', '40', '750');
       $pdf->ezText("<b>COMPROBANTE DE ".$comprobante." Nro. ".$libro_diario_numero_comprobante."</b>", 14,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($_GET['nombre_sistema'])."</b>", 18,array( 'justification' => 'center' ));
        
        $pdf->ezText("\n<b>Lugar y Fecha: Ibarra, ".$libro_diario_fecha."</b>", 11,array( 'justification' => 'left' ));
        $pdf->ezText("<b>Detalle: ".utf8_decode($libro_diario_descripcion)."</b>", 11,array( 'justification' => 'left' ));
        //$pdf->ezText("\n<b></b>", 18,array( 'justification' => 'center' )); 
        
        $txttit.= "\n";
        $pdf->ezText($txttit, 12);
        $pdf->ezTable($data, $titles, '', $options);
        $pdf->ezText("\n", 1);
        //$pdf->ezTable($totales, $totalestitulos, '', $optotal);
        //$pdf->ezText("\n", 1);
        $pdf->ezTable($totales1, $totalestitulos1, '', $optotal1);
        
        //$pdf->ezTable($totales1);
        $pdf->ezText("\n\n\n", 10);
        $pdf->ezText("<b>Elaborado                     Contabilizado                      Digitado</b> ", 10,array( 'justification' => 'center' ));
        $pdf->ezText("\n\n\n", 10);
        $pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
        $pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
        $pdf->ezStartPageNumbers(550, 80, 10);
        $nombrearchivo = "reporteComprobante.pdf";
        $pdf->ezStream();
        $pdf->ezOutput($nombrearchivo);

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));


        mysql_close();
        mysql_free_result($result);

?>