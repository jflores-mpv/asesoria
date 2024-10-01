<?php
//ob_end_clean();
//Start session
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('a4','Landscape');
$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$datacreador = array (
                    'Title'=>'Kardex',
                    'Subject'=>'Lista de Empelados',
                    'Author'=>'25 de junio',
                    'Producer'=>'Macarena LALAMA'
                    );
$pdf->addInfo($datacreador);
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
        $horasdesde = "00:00:00";
        $horashasta = "23:59:59";
    
$id_producto = $_GET['txtIdProducto'];
$fechaDesde = $_GET['txtFechaIngreso'];
$fechaHasta = $_GET['txtFechaSalida'];
 $bodega = $_GET['cmbBodegas'];
$codigo='';
 $sql0 = "SELECT
         productos.`id_producto` AS productos_id_producto,
         productos.`producto` AS productos_producto,
         productos.`codigo` AS productos_codigo,
         productos.`existencia_minima` AS productos_existencia_minima,
         productos.`existencia_maxima` AS productos_existencia_maxima,
         productos.`stock` AS productos_stock,
         productos.`costo` AS productos_costo,
         productos.`id_categoria` AS productos_id_categoria,
         productos.`id_proveedor` AS productos_id_proveedor,
         productos.`precio1` AS productos_precio1,
         productos.`precio2` AS productos_precio2,
         productos.`ganancia1` AS productos_ganancia1,
         productos.`ganancia2` AS productos_ganancia2,
         categorias.`id_categoria` AS categorias_id_categoria,
         categorias.`categoria` AS categorias_categoria
    FROM
         `categorias` categorias INNER JOIN `productos` productos  
			ON categorias.`id_categoria` = productos.`id_categoria`
             WHERE productos.`id_empresa` = '".$sesion_id_empresa."' AND 
              productos.id_producto='".$id_producto."'; ";

        $resp0 = mysql_query($sql0);

        while($row0=mysql_fetch_array($resp0))//permite ir de fila en fila de la tabla
        {
            $nombre=$row0['productos_producto'];
            $categoria=$row0['categorias_categoria'];
      
            $cod=$row0['productos_id_producto'];
            $codigo=$row0['productos_codigo'];
            $data[] = array('1'=>'NOMBRE:.......... '.$nombre,   '2'=>':....... '.$ex_min);
            $data[] = array('1'=>'CATEGORIA:....... '.$categoria,   '2'=>':....... '.$ex_max);
            $data[] = array('1'=>'CODIGO:....... '.$cod,   '2'=>':.................. '.$proveedor);

        }
        
		$titles = array('1'=>'DEL '.$fechaDesde, '2'=>'AL '.$fechaHasta);

        $options = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>600,
                    'cols'=>array(
                    '1'=>array('justification'=>'left','width'=>274),
                    '2'=>array('justification'=>'left','width'=>300)
                    )
        );        

        $options['fontSize']= 9;
		$horasdesde = "00:00:00";
        $horashasta = "23:59:59";
        $fechaDesde = $_GET['txtFechaIngreso'];
        $fechaHasta = $_GET['txtFechaSalida'];

    //    $sql1 = "select * from kardes WHERE fecha between '".$fechaDesde." ".$horasdesde."' AND '".$fechaHasta." ".$horashasta."' order by fecha asc; ";
       
         $sql1 = "select * from kardes WHERE kardes.`id_empresa` = '".$sesion_id_empresa."' and
		fecha between '".$fechaDesde." ".$horasdesde."' AND '".$fechaHasta." ".$horashasta."' order by fecha asc; ";

        $resp1 = mysql_query($sql1);
        $numero_filas1 = mysql_num_rows($resp1); // obtenemos el número de filas
        $cont = 0;
        $saldoVT = 0;
        $saldoVU = 0;
        $saldoCant = 0;
        $salidaVT = 0;
        
		while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
        {
            $id_kardes=$row1['id_kardes'];
            $fecha=$row1['fecha'];
            $detalle=$row1['detalle'];
            $id_factura=$row1['id_factura'];
            $sql2 = "";
            $sql3 = "";
            
            	if($detalle == "Saldo Inicial" or $detalle == "Ingreso" ){
                $sql6 = "SELECT
                ingresos.`id_ingreso` AS ingreso_id_ingreso,
                ingresos.`fecha` AS ingreso_fecha,
                ingresos.`total` AS ingreso_total,
                ingresos.`sub_total` AS ingreso_sub_total,
                ingresos.`id_iva` AS ingreso_id_iva,
                ingresos.`id_ingreso` AS ingreso_id_ingreso,
                detalle_ingresos.`id_ingreso` AS detalle_ingreso_id_ingreso,
                detalle_ingresos.`cantidad` AS detalle_ingreso_cantidad,
                detalle_ingresos.`v_unitario` AS detalle_ingreso_valor_unitario,
                detalle_ingresos.`v_total` AS detalle_ingreso_valor_total,
                detalle_ingresos.`id_ingreso` AS detalle_ingreso_id_ingreso,
                detalle_ingresos.`id_producto` AS detalle_ingreso_id_producto
            FROM `ingresos` ingresos INNER JOIN `detalle_ingresos` detalle_ingresos 
			ON ingresos.`id_ingreso` = detalle_ingresos.`id_ingreso` 
			INNER JOIN productos ON productos.id_producto= detalle_ingresos.id_producto
			INNER JOIN `bodegas` bodegas  ON detalle_ingresos.`bodega` = bodegas.`id` 
			WHERE ingresos.`id_empresa` = '".$sesion_id_empresa."'  and detalle_ingresos.`id_empresa` = '".$sesion_id_empresa."' and
			detalle_ingresos.id_ingreso='".$id_factura."' and 	productos.codigo='".$codigo."'  ";
			if($bodega!='0'){
			    $sql6 .= " and detalle_ingresos.bodega='".$bodega."'  ";
			}

                
                $contador2 = 0;
                $resp2 = mysql_query($sql6);
                $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
                while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
                {
                    $ingreso_id_ingreso = $row2['ingreso_id_ingreso'];
                    $detalle_ingreso_id_ingreso = $row2['detalle_ingreso_id_ingreso'];
                    $detalle_ingreso_valor_unitario = $row2['detalle_ingreso_valor_unitario'];
                    $detalle_ingreso_valor_unitario = number_format($detalle_ingreso_valor_unitario, 2, '.', '');
                    $detalle_ingreso_cantidad = $row2['detalle_ingreso_cantidad'];
                    $valTotalIngreso = $detalle_ingreso_cantidad * $detalle_ingreso_valor_unitario;
                    $valTotalIngreso = number_format($valTotalIngreso, 2, '.', '');
                    $cont++;
                    $contador2++;
                    // calculo saldo
                    $saldoCant = $saldoCant + $detalle_ingreso_cantidad;
                    $saldoVT = $saldoVT + $valTotalIngreso;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');
                    $data2[] = array('1'=>$cont, '2'=>$fecha, '3'=>$detalle." ".utf8_decode('Según')." Fac. Nro. ".$detalle_ingreso_id_ingreso, '4'=>$detalle_ingreso_cantidad, '5'=>$detalle_ingreso_valor_unitario, '6'=>$valTotalIngreso, '7'=>'', '8'=>'', '9'=>'', '10'=>$saldoCant, '11'=>$saldoVU, '12'=>$saldoVT);


                }

            }
            
            


			if($detalle == "Compra"){
                $sql2 = "SELECT
                compras.`id_compra` AS compras_id_compra,
                compras.`fecha_compra` AS compras_fecha_compra,
                compras.`total` AS compras_total,
                compras.`sub_total` AS compras_sub_total,
                compras.`id_iva` AS compras_id_iva,
                compras.`id_proveedor` AS compras_id_proveedor,
                compras.`numero_factura_compra` AS compras_numero_factura_compra,
                detalle_compras.`id_detalle_compra` AS detalle_compras_id_detalle_compra,
                detalle_compras.`cantidad` AS detalle_compras_cantidad,
                detalle_compras.`valor_unitario` AS detalle_compras_valor_unitario,
                detalle_compras.`valor_total` AS detalle_compras_valor_total,
                detalle_compras.`id_compra` AS detalle_compras_id_compra,
                detalle_compras.`id_producto` AS detalle_compras_id_producto
            FROM `compras` compras 
            
            INNER JOIN `detalle_compras` detalle_compras 
			ON compras.`id_compra` = detalle_compras.`id_compra` 
			
			INNER JOIN productos ON
	            productos.id_producto = detalle_compras.id_producto
	            
			WHERE compras.`id_empresa` = '".$sesion_id_empresa."'  and detalle_compras.`id_empresa` = '".$sesion_id_empresa."' and
			detalle_compras.id_compra='".$id_factura."' and productos.codigo='".$codigo."' ";
          if($bodega!='0'){
			    $sql2 .= " AND detalle_compras.`idBodegaInventario`='".$bodega."'   ";
			}
                
                $contador2 = 0;
                $resp2 = mysql_query($sql2);
                $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
                while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
                {
                    $compras_id_compra = $row2['compras_id_compra'];
                    $detalle_compras_id_detalle_compra = $row2['detalle_compras_id_detalle_compra'];
                    $compras_numero_factura_compra = $row2['compras_numero_factura_compra'];
                    $detalle_compras_valor_unitario = $row2['detalle_compras_valor_unitario'];
                    $detalle_compras_valor_unitario = number_format($detalle_compras_valor_unitario, 2, '.', '');
                    $detalle_compras_cantidad = $row2['detalle_compras_cantidad'];
                    $valTotalCompra = $detalle_compras_cantidad * $detalle_compras_valor_unitario;
                    $valTotalCompra = number_format($valTotalCompra, 2, '.', '');
                    $cont++;
                    $contador2++;
                    // calculo saldo
                    $saldoCant = $saldoCant + $detalle_compras_cantidad;
                    $saldoVT = $saldoVT + $valTotalCompra;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');
                    $data2[] = array('1'=>$cont, '2'=>$fecha, '3'=>$detalle." ".utf8_decode('Según')." Fac. Nro. ".$compras_numero_factura_compra, '4'=>$detalle_compras_cantidad, '5'=>$detalle_compras_valor_unitario, '6'=>$valTotalCompra, '7'=>'', '8'=>'', '9'=>'', '10'=>$saldoCant, '11'=>$saldoVU, '12'=>$saldoVT);


                }

            }
//------------------------- Inicio 
            if($detalle=="Devolucion Compra" || $detalle=="Devolucion Venta") {
                $sql2 = "SELECT kardes.id_kardes,
                                kardes.id_empresa,
                                kardes.fecha,
                                kardes.id_factura AS id_doc,
                                kardes.detalle AS trans,
                                devolucion.id_producto,
                                devolucion.cantidad AS cant,
                                devolucion.valor_unitario AS precio,
                                devolucion.cantidad*devolucion.valor_unitario AS total
                                FROM
                                devolucion
                                INNER JOIN kardes ON kardes.id_factura = devolucion.id_devolucion
                                INNER JOIN productos ON productos.id_producto = devolucion.id_producto
                                WHERE kardes.`id_empresa` = '".$sesion_id_empresa."'  and  kardes.id_factura='".$id_factura."'
                                            and  productos.codigo='".$codigo."'; ";
                                            
                                      
                $contador2 = 0;
                $resp2 = mysql_query($sql2);
                $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
                while ($row2=mysql_fetch_array($resp2)) {
                    $compras_id_compra = $row2['id_doc'];
                    $detalle_compras_id_detalle_compra = $row2['trans'];
                    $compras_numero_factura_compra = $row2['id_doc'];
                    $detalle_compras_valor_unitario = $row2['precio'];
                    $detalle_compras_valor_unitario = number_format($detalle_compras_valor_unitario, 2, '.', '');
                    $detalle_compras_cantidad = $row2['cant'];
                    $valTotalCompra = $detalle_compras_cantidad * $detalle_compras_valor_unitario;
                    $valTotalCompra = number_format($valTotalCompra, 2, '.', '');
                    $cont++;
                    $contador2++;
                    $contador3++;
                    if ($detalle=="Devolucion Compra"){ 
                        $saldoCant = $saldoCant - $detalle_compras_cantidad;
                        $saldoVT = $saldoVT - $valTotalCompra;
                        $contador2++;                        
                    }elseif ($detalle=="Devolucion Venta") {
                            $saldoCant = $saldoCant + $detalle_compras_cantidad;
                            $saldoVT = $saldoVT + $valTotalCompra;
                            $contador3++;                            
                    }    
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');

                    if ($detalle=="Devolucion Compra"){ 
                        $data2[] = array('1'=>$cont, '2'=>$fecha, '3'=>$detalle." ".utf8_decode('Según')." Doc. Nro. ".$compras_numero_factura_compra, '4'=>'', '5'=>'', '6'=>'', '7'=>$detalle_compras_cantidad, '8'=>$detalle_compras_valor_unitario, '9'=>$valTotalCompra, '10'=>$saldoCant, '11'=>$saldoVU, '12'=>$saldoVT);
                    }elseif ($detalle=="Devolucion Venta") {
                        $data2[] = array('1'=>$cont, '2'=>$fecha, '3'=>$detalle." ".utf8_decode('Según')." Doc.  Nro. ".$compras_numero_factura_compra, '4'=>$detalle_compras_cantidad, '5'=>$detalle_compras_valor_unitario, '6'=>$valTotalCompra, '7'=>'', '8'=>'', '9'=>'', '10'=>$saldoCant, '11'=>$saldoVU, '12'=>$saldoVT);
                }    
               }
            }
//------------------------- Fin
		    if($detalle == "Venta"){
               $sql3 = "SELECT
                 ventas.`id_venta` AS ventas_id_venta,
                 ventas.`fecha_venta` AS ventas_fecha_venta,
                 ventas.`estado` AS ventas_estado,
                 ventas.`total` AS ventas_total,
                 ventas.`sub_total` AS ventas_sub_total,
                 ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
                 ventas.`fecha_anulacion` AS ventas_fecha_anulacion,
                 ventas.`descripcion` AS ventas_descripcion,
                 ventas.`id_iva` AS ventas_id_iva,
                 ventas.`id_usuario` AS ventas_id_usuario,
                 ventas.`id_cliente` AS ventas_id_cliente,
                 detalle_ventas.`id_detalle_venta` AS detalle_ventas_id_detalle_venta,
                 detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
                 detalle_ventas.`estado` AS detalle_ventas_estado,
                 detalle_ventas.`v_unitario` AS detalle_ventas_v_unitario,
                 detalle_ventas.`v_total` AS detalle_ventas_v_total,
                 detalle_ventas.`id_venta` AS detalle_ventas_id_venta,
                 detalle_ventas.`id_kardex` AS detalle_ventas_id_kardex
            FROM
                 `ventas` ventas INNER JOIN `detalle_ventas` detalle_ventas 
                 ON ventas.`id_venta` = detalle_ventas.`id_venta` INNER JOIN productos ON
	                productos.id_producto = detalle_ventas.id_servicio 
	                WHERE 
                 ventas.`id_empresa` = '".$sesion_id_empresa."' and
                 detalle_ventas.id_venta='".$id_factura."' and 
                 productos.codigo='".$codigo."' 	 ";
               if($bodega!='0'){
			    $sql3 .= " and detalle_ventas.`idBodegaInventario`='".$bodega."'";
			}
                $contador3 = 0;
                $resp3 = mysql_query($sql3);
                $numero_filas3 = mysql_num_rows($resp3); // obtenemos el número de filas
                while($row3=mysql_fetch_array($resp3))//permite ir de fila en fila de la tabla
                {
                    $ventas_id_venta = $row3['ventas_id_venta'];
                    $detalle_ventas_id_detalle_venta = $row3['detalle_ventas_id_detalle_venta'];
                    $ventas_numero_factura_venta = $row3['ventas_numero_factura_venta'];
                    $detalle_ventas_v_unitario = $row3['detalle_ventas_v_unitario'];
                    $detalle_ventas_v_unitario = number_format($detalle_ventas_v_unitario, 2, '.', '');
                    $detalle_ventas_cantidad = $row3['detalle_ventas_cantidad'];
                    $cont++;
                    $contador3++;
                     // calculo saldo
                    $detalle_ventas_v_unitario = $saldoVU;
                    $salidaVT = $detalle_ventas_cantidad * $detalle_ventas_v_unitario;
                    $salidaVT = number_format($salidaVT, 2, '.', '');
                    $saldoCant = $saldoCant - $detalle_ventas_cantidad;
                    $saldoVT = $saldoVT - $salidaVT;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');

                    $data2[] = array('1'=>$cont, '2'=>$fecha, '3'=>$detalle." ".utf8_decode('Según')." Fac. Nro. ".$ventas_numero_factura_venta, '4'=>'', '5'=>'', '6'=>'', '7'=>$detalle_ventas_cantidad, '8'=>$detalle_ventas_v_unitario, '9'=>$salidaVT, '10'=>$saldoCant, '11'=>$saldoVU, '12'=>$saldoVT);
                }
            }


            	if($detalle == "Egreso"){
               $sql7 = "SELECT
                 egresos.`id_egreso` AS egresos_id_egresos,
                 egresos.`fecha` AS egresos_fecha,
                 egresos.`estado` AS egresos_estado,
                 egresos.`total` AS egresos_total,
                 egresos.`sub_total` AS egresos_sub_total,
                 egresos.`fecha_anulacion` AS egresos_fecha_anulacion,
                 egresos.`descripcion` AS egresos_descripcion,
                 egresos.`id_iva` AS egresos_id_iva,
                 detalle_egresos.`id_egreso` AS detalle_egresos_id_egresos,
                 detalle_egresos.`cantidad` AS detalle_egresos_cantidad,
                 detalle_egresos.`estado` AS detalle_egresos_estado,
                 detalle_egresos.`v_unitario` AS detalle_egresos_v_unitario,
                 detalle_egresos.`v_total` AS detalle_egresos_v_total
            FROM
                 `egresos` egresos INNER JOIN `detalle_egresos` detalle_egresos 
                 ON egresos.`id_egreso` = detalle_egresos.`id_egreso`  INNER JOIN productos ON productos.id_producto = detalle_egresos.`id_producto` WHERE 
                 egresos.`id_empresa` = '".$sesion_id_empresa."' and
                 detalle_egresos.id_egreso='".$id_factura."' and 
                 productos.codigo ='".$codigo."' ";
                if($bodega!='0'){
			    $sql7 .= "  and detalle_egresos.`bodega`='".$bodega."' ";
			}
                $contador3 = 0;
                $resp3 = mysql_query($sql7);
                $numero_filas3 = mysql_num_rows($resp3); // obtenemos el número de filas
                while($row3=mysql_fetch_array($resp3))//permite ir de fila en fila de la tabla
                {
                    $egresos_id_egresos = $row3['egresos_id_egresos'];
                    $detalle_egresos_id_egresos = $row3['detalle_egresos_id_egresos'];
                    $detalle_egresos_v_unitario = $row3['detalle_egresos_v_unitario'];
                    $detalle_egresos_v_unitario = number_format($detalle_egresos_v_unitario, 2, '.', '');
                    $detalle_egresos_cantidad = $row3['detalle_egresos_cantidad'];
                    $cont++;
                    $contador3++;
                     // calculo saldo
                    $detalle_egresos_v_unitario = $saldoVU;
                    $salidaVT = $detalle_egresos_cantidad * $detalle_egresos_v_unitario;
                    $salidaVT = number_format($salidaVT, 2, '.', '');
                    $saldoCant = $saldoCant - $detalle_egresos_cantidad;
                    $saldoVT = $saldoVT - $salidaVT;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');

                    $data2[] = array('1'=>$cont, '2'=>$fecha, '3'=>$detalle." ".utf8_decode('Según').". Nro. ".$egresos_id_egresos, '4'=>'', '5'=>'', '6'=>'', '7'=>$detalle_egresos_cantidad, '8'=>$detalle_egresos_v_unitario, '9'=>$salidaVT, '10'=>$saldoCant, '11'=>$saldoVU, '12'=>$saldoVT);
                }
            }

		

		}
		
	
        

		$titles2 = array('1'=>'<b>#</b>', '2'=>'<b>FECHA</b>', '3'=>'<b>DETALLE</b>', '4'=>'<b>CANT</b>', '5'=>'<b>V/UNIT</b>', '6'=>'<b>V/TOTAL</b>', '7'=>'<b>CANT</b>','8'=>'<b>V/UNIT</b>','9'=>'<b>V/TOTAL</b>','10'=>'<b>CANT</b>','11'=>'<b>V/UNIT</b>','12'=>'<b>V/TOTAL</b>');
        $options2 = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>783,
                    'cols'=>array(
                    '1'=>array('justification'=>'left','width'=>25),
                    '2'=>array('justification'=>'left','width'=>110),                                        
                    '3'=>array('justification'=>'left','width'=>0),
                    '4'=>array('justification'=>'left','width'=>45),                                        
                    '5'=>array('justification'=>'left','width'=>45),
                    '6'=>array('justification'=>'left','width'=>45),
                    '7'=>array('justification'=>'left','width'=>45),
                    '8'=>array('justification'=>'left','width'=>45),                                        
                    '9'=>array('justification'=>'left','width'=>45),
                    '10'=>array('justification'=>'left','width'=>45),
                    '11'=>array('justification'=>'left','width'=>45),                    
                    '12'=>array('justification'=>'left','width'=>45)                        
                     
                            )

                    );
					
        $options2['fontSize']= 8;
		
        $metodo = $_GET['cmbMetodo'];
        $pdf->ezImage('../images/encabezado_impresiones.jpg','','155','40','left','');
        $pdf->setLineStyle(1,'square');
        $pdf->setStrokeColor(0,0,0);
        $pdf->ezText("\n<b>KARDEX</b>", 16,array( 'justification' => 'center' ));
		$pdf->ezText("<b></b>", 10,array( 'justification' => 'center' ));
		$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        //$pdf->ezText("\n", 10);
//        $pdf->ezText("\n<b>AÑO: </b>".$_GET['cmbAno']."<b>    MES: </b>".$_GET['cmbMes'], 10,array( 'justification' => 'left' ));



        $titles0 = array('4'=>'Detalle de Moviemientos del Kardex','5'=>'Ingresos', '6'=>'Egresos', '7'=>'Saldo');

        $options0 = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'showHeadings'=>1,
                    'xOrientation'=>'center',
                    'width'=>783,
                    'cols'=>array(
                    '4'=>array('justification'=>'center','width'=>378),                                                                
                    '5'=>array('justification'=>'center','width'=>135),                                        
                    '6'=>array('justification'=>'center','width'=>135),                                        
                    '7'=>array('justification'=>'center','width'=>135)
                    )
        );        







        $pdf->ezText("", 10);
        $pdf->ezTable($data, $titles, '', $options);
        $pdf->ezText("\n\n",5);

  /*  $pdf->addText(410,430,12,'INGRESO');
    $pdf->addText(545,430,12,'EGRESO');
    $pdf->addText(680,430,12,'SALDO');*/

    $data0[] = array();
 

        $pdf->ezTable($data0, $titles0, '', $options0);
        $pdf->ezText("\n",1);    

		$pdf->ezTable($data2, $titles2, '', $options2);
        $pdf->ezText("\n\n\n", 10);
		$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
//		 $pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
         $pdf->ezStartPageNumbers(800, 40, 10);
 
  /*
       
       */  $nombrearchivo = "reporteEmpleados.pdf";
        $pdf->ezStream();
        $pdf->ezOutput($nombrearchivo);


		/* $data[] = array('1'=>'NOMBRE:.......... ');
		$pdf->ezTable($data, $titles, '', $options);
		$pdf->ezText("\n", 10);
        $pdf->ezTable($data2, $titles2, '', $options2);
         */
/* 		 $nombrearchivo = "reporteEmpleados.pdf";
        $pdf->ezStream();
        $pdf->ezOutput($nombrearchivo);
 */


?>