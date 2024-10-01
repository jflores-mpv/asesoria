<?php

//ob_end_clean();
//Start session
error_reporting(0);
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

if(isset($_GET['mostrar_detalle']) ){
    if($_GET['mostrar_detalle']!='1'){
        $mostrar_detalle=0 ;
    }
}
// $mostrar_detalle=0;
$iva_sql = "SELECT `iva`, `id_iva`, `codigo` FROM `impuestos` WHERE `id_empresa` = $sesion_id_empresa";
$iva_result =mysql_query($iva_sql);
$numFila= mysql_num_rows($iva_result);
$ivas = [];
if ($numFila > 0) {
    while ($iva_row = mysql_fetch_array($iva_result) ) {
        $ivas[] = ['iva' => $iva_row['iva'], 'id_iva' => $iva_row['id_iva'], 'codigo' => $iva_row['codigo']];
    }
}

        $options = array (
            'shadeCol'=>array(0.9,0.9,0.9),
            'xOrientation'=>'center',
            'width'=>800,
            'cols'=>array(
              '#'=>array('justification'=>'right','width'=>30),
              'producto'=>array('justification'=>'right','width'=>220),
              'valor_unitario'=>array('justification'=>'right','width'=>85),
              'IVA'=>array('justification'=>'right','width'=>60),
              'sub_total'=>array('justification'=>'right','width'=>75),
              'valor_total'=>array('justification'=>'right','width'=>75)
            )
          );
 
            
    if($mostrar_detalle==1){
         $titles = array(
            '#' => '<b>#</b>',
            'producto' => '<b>Producto</b>',
            'valor_unitario' => '<b>Valor Unitario</b>',
            'IVA' => '<b>Cantidad</b>',
            'valor_total' => '<b>Valor Total</b>'
            
          );
        
        foreach ($ivas as $iva) {
    	   	$titles[$iva['id_iva']]=  "<b>IVA ".$iva['iva']."% - ".$iva['codigo']." ";
        }
    }else{
         $titles = array(
            '#' => '<b>#</b>',
            'fecha_compra' => '<b>Fecha de comppra</b>',
            'numero_compra' => '<b>Numero de compra</b>',
            'proveedor' => '<b>Proveedor</b>',
            'valor_unitario' => '<b>Valor Unitario</b>',
            'IVA' => '<b>IVA</b>',
            'valor_total' => '<b>Valor Total</b>'
            
          );
            foreach ($ivas as $iva) {
    	   	$titles[$iva['id_iva']]=  "<b>IVA ".$iva['iva']."% - ".$iva['codigo']." ";
    }
    }
     
    

    
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$pdf =& new Cezpdf('a4','Landscape');

$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);

$datacreador = array (
  'Title'=>'Facturas',
  'Subject'=>'Saldo Inicial',
  'Author'=>'25 de junio',
  'Producer'=>'Macarena Lalama'
);
  $pdf->addInfo($datacreador);
  $pdf->ezText("<b>Fecha actual:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));
	$sesion_id_periodo_contable =$_GET['Periodo'];

  $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
  $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
  $txtProveedor =  $_GET['txtProveedor'];

  $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
  $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
  $pdf->ezText("<b>REPORTE DE COMPRAS</b>", 18,array( 'justification' => 'center' ));
  $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
  $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
function ceros($valor){
    $s='';
	for($i=1;$i<=9-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}

  $criterio_orden =$_GET['criterio_orden'];
  $criterio_mostrar =$_GET['criterio_mostrar'];
   $sql = "SELECT *, 
   detalle_compras.iva as ivaproducto,
   productos.iva as iva_producto
        FROM `detalle_compras` 
        LEFT JOIN `compras` ON `compras`.`id_compra` = `detalle_compras`.`id_compra` 
        LEFT JOIN `proveedores` ON `proveedores`.`id_proveedor` = `compras`.`id_proveedor` 
        LEFT JOIN `productos` ON `productos`.`id_producto` = `detalle_compras`.`id_producto` 
        LEFT JOIN `impuestos` ON `impuestos`.`id_iva` = `detalle_compras`.`iva` 
        
        WHERE `detalle_compras`.`id_empresa` = $sesion_id_empresa 
        AND compras.fecha_compra >= '".$fecha_desde."' AND 
        compras.fecha_compra <= '".$fecha_hasta."'  and compras.anulado='0'";
    

  if ($_GET['txtProveedor']!='0' && $_GET['txtProveedor']!=''){
    $sql .= " and  compras.`id_proveedor`='".$txtProveedor."' ";   
  }  

  $sql .= " ORDER BY  compras.fecha_compra $criterio_orden  "; 
if($sesion_id_empresa==41){
    // echo $sql;
}
 $prev_id_compra = null;
  $contador = 0;
            $subtotal = 0;
            $total_iva = 0;
            $totales_por_iva = array_fill_keys(array_column($ivas, 'id_iva'), 0);

        $total_general = 0;
        $total_general_iva = 0;
        $total_por_iva_global = array_fill_keys(array_column($ivas, 'id_iva'), 0);

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
        $contador2=0;
        $cantidad_compras=0;
		 while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
     {
         
       if( $prev_id_compra == null){
         
           
           if($mostrar_detalle==1){
               
           if($contador==0){
               $cantidadDataEncabezado=0;
           }
           
            $data[$cantidadDataEncabezado] = array(
              '#'=>$contador."-".$idCompra,
              'producto'=>utf8_decode($fechaCompra.'    *|   '.$NumeroCompra.'   | '.$proveedor),
              'valor_unitario'=>  number_format($subtotal, 2) ,
              'IVA'=>number_format($total_iva, 2) ,
              'valor_total'=>number_format($subtotal + $total_iva, 2)
              
            );
         
              }
               $cantidadDataEncabezado = count($data)-1;
       }
         
              
          if ($prev_id_compra !== null && $prev_id_compra !== $row['id_compra']) {
              $cantidad_compras++;
              $contador2++;
                if($mostrar_detalle==1){
                 $data[] = array(
              '#'=>'',
              'producto'=>'',
              'valor_unitario'=>  '',
              'IVA'=>'',
              'valor_total'=>''
              
            ); 
            
             }
             
              if($mostrar_detalle==1){
                   $data[$cantidadDataEncabezado] = array(
              '#'=>$contador."-".$idCompra,
              'producto'=>utf8_decode($fechaCompra.'    |   '.$NumeroCompra.'   | '.$proveedor),
              'valor_unitario'=>  number_format($subtotal, 2) ,
              'IVA'=>number_format($total_iva, 2) ,
              'valor_total'=>number_format($subtotal + $total_iva, 2)
              
            );
            $cantidadData = $cantidadDataEncabezado;
	        
    	   	
            foreach ($ivas as $iva) {
                // $titulo_actua=  "<b>IVA ".$iva['iva']."% - ".$iva['codigo']." ";
                $data[$cantidadData][$iva['id_iva']]= number_format($totales_por_iva[$iva['id_iva']], 2) ;
            }
                $data[] = array(
              '#'=>'',
              'producto'=>'',
              'valor_unitario'=>  '',
              'IVA'=>'',
              'valor_total'=>''
              
            ); 
             $cantidadDataEncabezado = count($data)-1;
              }else{
                   $data[] = array(
                '#'=>$cantidad_compras."-".$idCompra,
                'fecha_compra' =>$fechaCompra,
                'numero_compra' => $NumeroCompra,
                'proveedor' => $proveedor,
                'valor_unitario'=>  number_format($subtotal, 2) ,
                'IVA'=>number_format($total_iva, 2) ,
                'valor_total'=>number_format($subtotal + $total_iva, 2)
              
            );
                 $cantidadData = count($data)-1;
	        
    	   	
            foreach ($ivas as $iva) {
                // $titulo_actua=  "<b>IVA ".$iva['iva']."% - ".$iva['codigo']." ";
                $data[$cantidadData][$iva['id_iva']]= number_format($totales_por_iva[$iva['id_iva']], 2) ;
            } 
              }
              
          
              
            $total_general += $subtotal;
            $total_general_iva += $total_iva;
            foreach ($ivas as $iva) {
                $total_por_iva_global[$iva['id_iva']] += $totales_por_iva[$iva['id_iva']];
            }

            // Resetear totales
            $subtotal = 0;
            $total_iva = 0;
            $totales_por_iva = array_fill_keys(array_column($ivas, 'id_iva'), 0);
          
              $contador=1;   
    
        }

            if($mostrar_detalle==1){
                $iva_prod=empty($row['ivaproducto']) ? $row['iva_producto'] : $row['ivaproducto'];
                $data[] = array(
              '#'=>$row['id_producto'],
              'producto'=> $row['producto'],
              'valor_unitario'=>  number_format($row['valor_unitario'], 2) ,
              'IVA'=>$iva_prod ,
              'valor_total'=>number_format($row['valor_total'], 2)
              
            );
              $cantidadData = count($data)-1;
              
         foreach ($ivas as $iva) {
            
                $ivaK= empty($row['ivaproducto']) ? $row['iva_producto'] : $row['ivaproducto'];
                
                if ($ivaK== $iva['id_iva']) {
                    $data[$cantidadData][$iva['id_iva']]= number_format($row['valor_total'], 2) ;
                    $totales_por_iva[$iva['id_iva']] += $row['valor_total'];
                } else {
                    $data[$cantidadData][$iva['id_iva']]= number_format(0, 2) ;
                }
            }
            
            }else{
               foreach ($ivas as $iva) {
                    $ivaK= empty($row['ivaproducto']) ? $row['iva_producto'] : $row['ivaproducto'];
                    
                    if ($ivaK == $iva['id_iva']) {
                        $totales_por_iva[$iva['id_iva']] += $row['valor_total'];
                    } 
                }  
            }
        
        $subtotal += $row['valor_total'];
        $total_iva = $row['total_iva'];  // Acumular total IVA por id_compra
        $prev_id_compra = $row['id_compra'];
        $proveedor = $row['nombre_comercial'];
        $idCompra = $row['numero_factura_compra'];
        $fechaCompra = date("Y-m-d", strtotime($row['fecha_compra']));
        $NumeroCompra= $row['numSerie']."-".$row['txtEmision']."-".ceros($row['txtNum']);
        $contador++;
           
             
          }
         // fin while 
          
          if ($prev_id_compra !== null) {
             
            if($mostrar_detalle==1){
                $data[$cantidadDataEncabezado] = array(
              '#'=>$contador."-".$idCompra,
              'producto'=>$fechaCompra.'    |   '.$NumeroCompra.'   |   '.$proveedor,
              'valor_unitario'=>  number_format($subtotal, 2) ,
              'IVA'=>number_format($total_iva, 2) ,
              'valor_total'=>number_format($subtotal + $total_iva, 2)
              
            );
             $cantidadData = $cantidadDataEncabezado;
            }else{
                   $data[] = array(
              '#'=>$cantidad_compras."-".$idCompra,
               'fecha_compra' =>$fechaCompra,
                'numero_compra' => $NumeroCompra,
                'proveedor' => $proveedor,
              'valor_unitario'=>  number_format($subtotal, 2) ,
              'IVA'=>number_format($total_iva, 2) ,
              'valor_total'=>number_format($subtotal + $total_iva, 2)
              
            );
             $cantidadData = count($data)-1;
            }
            
        
        foreach ($ivas as $iva) {
             $data[$cantidadData][$iva['id_iva']]= number_format($totales_por_iva[$iva['id_iva']], 2) ;
        }   
 
        $total_general += $subtotal;
        $total_general_iva += $total_iva;
        foreach ($ivas as $iva) {
            $total_por_iva_global[$iva['id_iva']] += $totales_por_iva[$iva['id_iva']];
        }
                    
    }   
        
        
         
         if($mostrar_detalle==1){
              $data[] = array(
            '#'=>$cantidad_compras,
            'producto'=> 'TOTAL' ,
            'valor_unitario'=>number_format($total_general, 2) ,
            'IVA'=>  number_format($total_general_iva, 2) ,
            'valor_total'=>number_format($total_general + $total_general_iva, 2)
            
          );
         }else{
             $data[] = array(
            '#'=>$cantidad_compras,
            'proveedor'=> 'TOTAL' ,
            'valor_unitario'=>number_format($total_general, 2) ,
            'IVA'=>  number_format($total_general_iva, 2) ,
            'valor_total'=>number_format($total_general + $total_general_iva, 2)
            
          );
         }   
         
        $filaActual = count($data)-1;
            foreach ($ivas as $iva){
            $data[$filaActual][$iva['id_iva']]=   number_format($total_por_iva_global[$iva['id_iva']], 2) ;
    	  
        }


            $options['fontSize']= 7;
            $txttit.= "";
            $pdf->ezText($txttit, 12);
            $pdf->ezTable($data, $titles, '', $options);
            $pdf->ezStartPageNumbers(550, 80, 10);
            $pdf->ezStream();
            $pdf->Output('reporteSaldoInicial.pdf', 'D');
            
          mysql_close();
          mysql_free_result($result);

          ?>
