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
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$pdf =& new Cezpdf('a4','landscape');

// $pdf->selectFont('fonts/courier.afm');
$pdf->selectFont('fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);

$datacreador = array (
                    'Title'=>'Facturas',
                    'Subject'=>'Saldo Inicial',
                    'Author'=>'25 de junio',
                    'Producer'=>'Macarena Lalama'
                    );
                    
$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha actual:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));

//$sesion_id_empresa = $_GET['Empresa_id'];//$_SESSION["sesion_id_empresa"];
	//;$_SESSION["sesion_empresa_nombre"];
	$sesion_id_periodo_contable =$_GET['Periodo'];// $_SESSION["sesion_id_periodo_contable"];


	$id_compras = $_GET["id_compras"];
        $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
        $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
        $txtProveedor =  $_GET['txtProveedor'];

        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
        $pdf->ezText("<b>REPORTE DE VENTAS</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));

        $titles = array(
		    '#' => '<b>#</b>',
		    // 'tipo' => '<b>tipo</b>',
		    'fecha' => '<b>Fecha</b>',
		    'numero_factura_venta' => '<b>No. de Factura</b>',
		    'nombre' => '<b>Cliente</b>',
            'concepto' => '<b>Concepto</b>',
            'valor' => '<b>Valor</b>',
            'cantidad' => '<b>Cantidad</b>',
		     'total' => '<b>Total Neto</b>',
		    // 'subTotal0' => '<b>Base 0</b>',
		    // 'subTotal12' => '<b>Base 12</b>',
		    'sub_total' => '<b>Sub_total</b>',
            '%'=>'<b>%</b>',
		    'retFuente' => '<b>Ret. Fuente</b>',
            // 'retIva' => '<b>Ret. IVA</b>'
            
        );
            
             //tercera        
        $options = array
		(
                'shadeCol'=>array(0.9,0.9,0.9),
                'xOrientation'=>'center',
                'width'=>800,
				
				'cols'=>array(
				'#'=>array('justification'=>'left','width'=>20),
			
        		'numero_factura_venta'=>array('justification'=>'left','width'=>85),
                'nombre'=>array('justification'=>'left','width'=>160),
    			'fecha'=>array('justification'=>'center','width'=>85),
                'sub_total'=>array('justification'=>'right','width'=>75),
                'total'=>array('justification'=>'right','width'=>75),
                'subTotal0'=>array('justification'=>'right','width'=>75),
                'subTotal12'=>array('justification'=>'right','width'=>75)
                )
         
		);
    
	
		$options['fontSize']= 7;

        $sql="SELECT	clientes.id_cliente as clientes_idCliente,
        clientes.nombre as clientes_nombre,
      ventas.id_venta as ventas_idVenta,
      ventas.total as ventas_total,
      ventas.sub_total as ventas_subtotal,
      ventas.numero_factura_venta as ventas_numeroFacturaVenta,
      ventas.fecha_venta as ventas_fechaVenta,
      detalle_ventas.id_venta as detalleVentas_idVenta,
      detalle_ventas.cantidad as detalleVentas_cantidad,
      detalle_ventas.v_unitario as detalleVentas_valor,
      detalle_ventas.v_total as detalleVentas_vtotal,
      detalle_ventas.idBodega as detalleVentas_idBodega,
      productos.producto as productos_producto,
      productos.id_producto  as producto_idProducto,
      productos.costo as producto_costo,
  impuestos.iva as impuestos_iva
       FROM `ventas` INNER JOIN detalle_ventas ON detalle_ventas.id_venta = ventas.id_venta 
       INNER JOIN productos ON productos.id_producto = detalle_ventas.idBodega 
       INNER JOIN clientes ON clientes.id_cliente = ventas.id_cliente 
       LEFT JOIN 
	impuestos ON	ventas.id_iva = impuestos.id_iva 
        where ventas.`id_empresa`='".$sesion_id_empresa."' and
         DATE_FORMAT(ventas.fecha_venta, '%Y-%m-%d')>=DATE_FORMAT('$fecha_desde', '%Y-%m-%d') and   DATE_FORMAT(ventas.fecha_venta, '%Y-%m-%d')<=DATE_FORMAT('$fecha_hasta', '%Y-%m-%d')
     ORDER BY  productos.id_producto, ventas.id_venta DESC";
        //  echo $sql;
        
        // exit;
	
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;

       
        // echo  $sql."***    ".$numero_filas; exit;
        $data2 = array(
        );
        $ntipo=0;
        $cambiante='';

        $sumaRetencion=0;//fuera del while
        $sumaTotalRetencion=0;
        $contax=0;
        $granSubtotal=0;
        $granRetencion=0;
        // $uTipo='';
        // $uPorcent='';
        $numeroFilas = mysql_num_rows($result);
        $contadorCiclo=0;
        $granTotal = 0;
        $granSubtotal =0;
		 while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
        {
          
           
			$numero ++;
			$compras_id_compra = $row["ventas_idVenta"];
            $subtotal_venta = $subtotal_venta + $row["ventas_subtotal"];
            $total_venta = $total_venta + $row["ventas_total"];
         
                    
        //      $sqlu="Select valor From cobrosPagos where id_factura='".$compras_id_compra."' and tipo='retencion-fuente';";
        //    ///  echo $sqlu;
        //         $resultu=mysql_query($sqlu);
        //         while($rowu=mysql_fetch_array($resultu))
        //          {               
        //           $valorRF = $rowu["valor"];
        //          }
              
        //     $sqli="Select valor From cobrosPagos where id_factura='".$compras_id_compra."' and tipo='retencion-iva';";
        //     // echo $sqlu;
        //         $resulti=mysql_query($sqli);
        //         while($rowi=mysql_fetch_array($resulti))
        //          {               
        //           $valorRI = $rowi["valor"];
        //          }
               
                 if($contadorCiclo===0){
                    $texto="<b>".$row['productos_producto']."</b> ";
                    $pdf->ezText($texto, 10,array( 'justification' => 'left' ));
                    $pdf->ezText("\n", 2);
                 }
                 $contadorCiclo++;
                     if($row['productos_producto']!=$cambiante ){
                        
                        if($contax>0){
                        //  INICIO total retencio anterior 
                
                        $data[] = array(
                            'concepto'=>'Total',
                            'sub_total'=>number_format($sumaSubTotal, 3, '.', ' '),
                            'total'=>number_format($sumaTotal, 3, '.', ' ')
                            );
                            $granTotal+=$sumaTotal;
                            $granSubtotal += $sumaSubTotal;
                         $sumaSubTotal=0;
                         $sumaTotal=0;
                 
 // FIN total retencion anterior 
 $pdf->ezTable($data, $titles, '', $options);
 $pdf->ezText("\n", 2);
 $texto="<b>".$row['productos_producto']."</b>";
 $pdf->ezText($texto, 10,array( 'justification' => 'left' ));
 $pdf->ezText("\n", 2);
 unset($data);
               
            
                   $data=array();
                        }
                            
                       $contax=1;
                        
                        $cambiante= $row['productos_producto'];
                     
                        $numero=1;
                     
                           
                     }
                       
                  
                 
                        $data[] = array(
                            '#'=>$numero,
                            // 'tipo'=>$row['tipo'],
                            'numero_factura_venta'=>$row['ventas_numeroFacturaVenta'],
                            'nombre'=>$row["clientes_nombre"] ,
                            'concepto' => $row["productos_producto"] ,
                            'valor'=>$row["detalleVentas_valor"] ,
                            'cantidad'=>$row["detalleVentas_cantidad"] ,
                            'fecha'=>($row["ventas_fechaVenta"]) ,
                            'sub_total'=>number_format($row["ventas_subtotal"], 3, '.', ' '),
                             'total'=>number_format($row["ventas_total"], 2, '.', ' '),
                            '%'=>number_format($row["impuestos_iva"], 3, '.', ' '),
                            'retFuente'=>number_format((floatval($row["detalleVentas_valor"]))*(floatval($row["impuestos_iva"])/100), 3, '.', ' ')  ,
                            // 'retIva'=>number_format($valorRI, 2, '.', ' ')
                            );
                        
                            $sumaTotal= $sumaTotal+floatval($row['ventas_total']);//antes del if
                      
                            $sumaSubTotal= $sumaSubTotal+floatval($row['ventas_subtotal']);//antes del if
                            
                        
                  
                   
          

                     if( $contadorCiclo== $numeroFilas){
                        
                        $data[] = array(
                            'concepto'=>'Total',
                            'sub_total'=>number_format($sumaSubTotal, 3, '.', ' '),
                            'total'=> number_format($sumaTotal, 3, '.', ' ')
                            );
                    
                            $granTotal+=$sumaTotal;
                            $granSubtotal += $sumaSubTotal;
                        
                           
                     }
                   
                  
        }

        $pdf->ezTable($data, $titles, '', $options);
        $pdf->ezText("\n", 2);
        $texto="<b>Gran Total =</b> <b>".number_format($granTotal, 3, '.', ' ')."</b> <b>Gran Subtotal =</b>   <b>".number_format($granSubtotal, 3, '.', ' ')."</b>";
        $pdf->ezText($texto, 10,array( 'justification' => 'right' ));
        $pdf->ezText("\n", 2);
    
      
        $txttit.= "";
        $pdf->ezText($txttit, 12);
        //$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
        $pdf->ezStartPageNumbers(550, 80, 10);
        //$nombrearchivo = "reporteLibroMayor.pdf";
        $pdf->ezStream();
        //$pdf->ezOutput($nombrearchivo);
        $pdf->Output('reporteSaldoInicial.pdf', 'D');

        mysql_close();
        mysql_free_result($result);

?>
