<?php
    session_start();

    //Include database connection details
    require_once('../conexion.php');
    $accion=$_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
   
    date_default_timezone_set('America/Guayaquil');
	
	//function calcularPromedio($id_producto1,$empresa,$opcion)
	function calcularPromedio($id_producto1,$empresa)
    {	
        
        
    $id_producto = $id_producto1;
	$sesion_id_empresa1=$empresa;
	   
	//echo "esto en la func calcularPromedio";
	//echo $id_producto;
	//echo $sesion_id_empresa1;
    $sql="delete from KARDEX_PROM where empresa='".$sesion_id_empresa1."' and id_producto='".$id_producto."'"   ;
	$resultado=mysql_query($sql);

    $sql1 = "select * from kardes WHERE kardes.`id_empresa` = '".$sesion_id_empresa1."' order by fecha";
	$resp1 = mysql_query($sql1);
    $numero_filas1 = mysql_num_rows($resp1);                  // obtenemos el número de filas
    $cont = 0;
    $saldoVT = 0;
    $saldoVU = 0;
    $saldoCant = 0;
    $salidaVT = 0;
    
    $fila=mysql_num_rows($sql);
    
    
if ($fila>0){    
		
	while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
    {
      $id_kardes=$row1['id_kardes']; 
      $fecha=$row1['fecha'];
      $detalle=$row1['detalle'];
      $id_factura=$row1['id_factura'];
      $sql2 = "";
      $sql3 = "";             
            
      if($detalle == "Compra")
	  {
        $sql2 = "SELECT
            compras.`id_compra` AS compras_id_compra, compras.`fecha_compra` AS compras_fecha_compra,
            compras.`total` AS compras_total,         compras.`sub_total` AS compras_sub_total,
            compras.`id_iva` AS compras_id_iva,   compras.`id_proveedor` AS compras_id_proveedor,
            compras.`numero_factura_compra` AS compras_numero_factura_compra,
            detalle_compras.`id_detalle_compra` AS detalle_compras_id_detalle_compra,
            detalle_compras.`cantidad` AS detalle_compras_cantidad,
            detalle_compras.`valor_unitario` AS detalle_compras_valor_unitario,
            detalle_compras.`valor_total` AS detalle_compras_valor_total,
            detalle_compras.`id_compra` AS detalle_compras_id_compra,  
	     	detalle_compras.`id_producto` AS detalle_compras_id_producto
          FROM `compras` compras INNER JOIN `detalle_compras` detalle_compras 
			ON compras.`id_compra` = detalle_compras.`id_compra` 
			WHERE compras.`id_empresa` = '".$sesion_id_empresa1."'  and
			detalle_compras.id_compra='".$id_factura."' and detalle_compras.id_producto='".$id_producto."'; ";
            
        $contador2 = 0;
        $resp2 = mysql_query($sql2);
        $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
        while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
        {
          $compras_id_compra = $row2['compras_id_compra'];
          $detalle_compras_id_detalle_compra = $row2['detalle_compras_id_detalle_compra'];
          $compras_numero_factura_compra = $row2['compras_numero_factura_compra'];
          $detalle_compras_valor_unitario = $row2['detalle_compras_valor_unitario'];
          $detalle_compras_cantidad = $row2['detalle_compras_cantidad'];
          $valTotalCompra = $detalle_compras_cantidad * $detalle_compras_valor_unitario;
          $saldoCant = $saldoCant + $detalle_compras_cantidad;
          $saldoVT = $saldoVT + $valTotalCompra;
          if($saldoVT == 0 && $saldoCant == 0)
		  { $saldoVU = 0; }
		  else
			  
		  { 
 			IF ($saldoCant<>0)
			{
				$saldoVU = floatval($saldoVT / $saldoCant);
			}
		 }
      					
		  $detalle_ventas_cantidad=0;
		  $detalle_ventas_v_unitario=0;
		  $salidaVT=0;
                
          $sql="INSERT INTO KARDEX_PROM(id_producto,fecha, detalle, cpra_cantidad, cpra_precio, cpra_total,
					ven_cantidad, ven_precio,ven_total, sal_cantidad, sal_precio, sal_total,empresa)
					VALUES ('".$id_producto."','".$fecha."','".$detalle."','".$detalle_compras_cantidad."',
					        '".$detalle_compras_valor_unitario."','".$valTotalCompra."',
							'".$detalle_ventas_cantidad."','".$detalle_ventas_v_unitario."','".$salidaVT."',
							'".$saldoCant."','".$saldoVU."','".$saldoVT."','".$sesion_id_empresa1."'   )";
//echo $sql;	
	$resultado=mysql_query($sql);		                 
        }
      }

      if($detalle == "Venta")
	  {
            $sql3 = "SELECT  ventas.`id_venta` AS ventas_id_venta, ventas.`fecha_venta` AS ventas_fecha_venta,
                 ventas.`estado` AS ventas_estado,     ventas.`total` AS ventas_total,
                 ventas.`sub_total` AS ventas_sub_total, ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
                 ventas.`descripcion` AS ventas_descripcion,   ventas.`id_iva` AS ventas_id_iva,
                 ventas.`id_cliente` AS ventas_id_cliente, detalle_ventas.`id_detalle_venta` AS detalle_ventas_id_detalle_venta,
                 detalle_ventas.`cantidad` AS detalle_ventas_cantidad, detalle_ventas.`estado` AS detalle_ventas_estado,
                 detalle_ventas.`v_unitario` AS detalle_ventas_v_unitario, detalle_ventas.`v_total` AS detalle_ventas_v_total,
                 detalle_ventas.`id_venta` AS detalle_ventas_id_venta, detalle_ventas.`id_kardex` AS detalle_ventas_id_producto
            FROM
                 `ventas` ventas INNER JOIN `detalle_ventas` detalle_ventas 
				 ON ventas.`id_venta` = detalle_ventas.`id_venta` 
				 WHERE ventas.`id_empresa` = '".$sesion_id_empresa1."' and 
				 detalle_ventas.id_venta='".$id_factura."' and detalle_ventas.id_kardex='".$id_producto."'; ";
		    $resp3 = mysql_query($sql3);
        
            while($row3=mysql_fetch_array($resp3))         //permite ir de fila en fila de la tabla
            {
                    $ventas_id_venta = $row3['ventas_id_venta'];
                    $detalle_ventas_id_detalle_venta = $row3['detalle_ventas_id_detalle_venta'];
                    $ventas_numero_factura_venta = $row3['ventas_numero_factura_venta'];
			  $detalle_ventas_cantidad = $row3['detalle_ventas_cantidad'];
              $detalle_ventas_v_unitario = $row3['detalle_ventas_v_unitario'];
              // calculo saldo
              $detalle_ventas_v_unitario = $saldoVU;  //2020
			 
              $salidaVT = $detalle_ventas_cantidad * $detalle_ventas_v_unitario;
              
			  $saldoCant = $saldoCant - $detalle_ventas_cantidad;
              $saldoVT = $saldoVT - $salidaVT;
              if($saldoVT == 0 && $saldoCant == 0){
                 $saldoVU = 0;
              }else
			  {
				IF ($saldoCant<>0)
				{
					$saldoVU = floatval($saldoVT / $saldoCant);
				}
			  }
              
			  $detalle_compras_cantidad=0;
			  $detalle_compras_valor_unitario=0;
			  $valTotalCompra=0;
					
			  $sql="INSERT INTO KARDEX_PROM(id_producto,fecha, detalle, cpra_cantidad, cpra_precio, cpra_total,
					ven_cantidad, ven_precio,ven_total, sal_cantidad, sal_precio, sal_total,empresa)
					VALUES ('".$id_producto."','".$fecha."','".$detalle."',
					'".$detalle_compras_cantidad."','".$detalle_compras_valor_unitario."','".$valTotalCompra."',
					'".$detalle_ventas_cantidad."','".$detalle_ventas_v_unitario."','".$salidaVT."',
					'".$saldoCant."','".$saldoVU."','".$saldoVT."','".$sesion_id_empresa1."'   )";
//echo $sql;
	   		  $resultado=mysql_query($sql);
            }
      }
            
	  if($detalle == "Devolucion Compra")
	  {
          $sql2 = "SELECT devolucion.`id_devolucion` AS devolucion_id_devolucion,	
		    devolucion.`fecha` AS devolucion_fecha,	devolucion.`cantidad` AS devolucion_cantidad,
			devolucion.`valor_unitario` AS devolucion_valor_unitario	
          FROM `devolucion` devolucion 
	      WHERE devolucion.`id_empresa` = '".$sesion_id_empresa1."'  and
			devolucion.id_devolucion='".$id_factura."' and
			devolucion.id_producto='".$id_producto."'; ";
		   //echo $sql2;
          $resp2 = mysql_query($sql2);
          $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
          while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
          {
            $devolucion_id_devolucion = $row2['devolucion_id_devolucion'];
			$ventas_cantidad = $row2['devolucion_cantidad'];
		    $ventas_v_unitario  = $row2['devolucion_valor_unitario'];
              
			//$ventas_v_unitario = $saldoVU;    2020
			$salidaVT  = $ventas_cantidad * $ventas_v_unitario;
			
			$saldoCant = $saldoCant - $ventas_cantidad;
            $saldoVT = $saldoVT - $salidaVT;
            if($saldoVT == 0 && $saldoCant == 0){
                $saldoVU = 0;
            }
			else
			{
				IF ($saldoCant<>0)
				{
                $saldoVU = floatval($saldoVT / $saldoCant);
				}
            }
                    
			$compras_cantidad=0;
			$compras_v_unitario=0;
		    $valTotalCompra=0;
					
        	$sql="INSERT INTO KARDEX_PROM(id_producto,fecha, detalle, cpra_cantidad, cpra_precio, cpra_total,
					ven_cantidad, ven_precio,ven_total, sal_cantidad, sal_precio, sal_total,empresa)
					VALUES ('".$id_producto."','".$fecha."','".$detalle."',
						'".$compras_cantidad."','".$compras_v_unitario."','".$valTotalCompra."',
						'".$ventas_cantidad."','".$ventas_v_unitario."','".$salidaVT."',
						'".$saldoCant."','".$saldoVU."','".$saldoVT."','".$sesion_id_empresa1."'   )";
					
			  $resultado=mysql_query($sql);		                                
//echo $sql;
            }
      }
	   
	  if($detalle == "Devolucion Venta")
	  {
		$sql2 = "SELECT devolucion.`id_devolucion` AS devolucion_id_devolucion,	devolucion.`fecha` AS devolucion_fecha,
					devolucion.`cantidad` AS devolucion_cantidad, devolucion.`valor_unitario` AS devolucion_valor_unitario	
                FROM `devolucion` devolucion 
				WHERE devolucion.`id_empresa` = '".$sesion_id_empresa1."'  and devolucion.id_devolucion='".$id_factura."' and
					  devolucion.id_producto='".$id_producto."'; ";
        $resp2 = mysql_query($sql2);
        $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
        while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
        {
          $devolucion_id_devolucion = $row2['devolucion_id_devolucion'];
		  $compras_cantidad = $row2['devolucion_cantidad'];
		  $compras_v_unitario = $row2['devolucion_valor_unitario'];
					
		  $valTotalCompra=$compras_cantidad*$compras_v_unitario;
		  $saldoCant = $saldoCant + $compras_v_unitario;
          $saldoVT = $saldoVT + $valTotalCompra;
          		
	      if($saldoVT == 0 && $saldoCant == 0){
                $saldoVU = 0;
				$saldoVT =0;
          }
		  else
		  {
			IF ($saldoCant<>0)
			{
				$saldoVU = floatval($saldoVT / $saldoCant);	
			}                        
          }
                    
			$ventas_cantidad=0;
			$ventas_v_unitario=0;
			$salidaVT=0;
					
			$sql="INSERT INTO KARDEX_PROM(id_producto,fecha, detalle, cpra_cantidad, cpra_precio, cpra_total,
							ven_cantidad, ven_precio,ven_total, sal_cantidad, sal_precio, sal_total,empresa)
				VALUES ('".$id_producto."','".$fecha."','".$detalle."',
						'".$compras_cantidad."','".$compras_v_unitario."','".$valTotalCompra."',
						'".$ventas_cantidad."','".$ventas_v_unitario."','".$salidaVT."',
						'".$saldoCant."','".$saldoVU."','".$saldoVT."','".$sesion_id_empresa1."'   )";
			//echo $sql;	
			$resultado=mysql_query($sql);
          }
      }      
	  
      if($detalle == "Ingreso" or $detalle == "Saldo Inicial" )
	  {
          $sql2 = "SELECT ingresos.`id_ingreso` AS ingresos_id_ingreso,
                ingresos.`fecha` AS ingresos_fecha,
                ingresos.`total` AS ingresos_total,
                ingresos.`sub_total` AS ingresos_sub_total,
                ingresos.`id_iva` AS ingresos_id_iva,
                ingresos.`numero` AS ingresos_numero,
                detalle_ingresos.`id_detalle_ingreso` AS detalle_ingresos_id_detalle_ingreso,
                detalle_ingresos.`cantidad` AS detalle_ingresos_cantidad,
                detalle_ingresos.`v_unitario` AS detalle_ingresos_valor_unitario,
                detalle_ingresos.`v_total` AS detalle_ingresos_valor_total,
                detalle_ingresos.`id_ingreso` AS detalle_ingresos_id_ingreso,
                detalle_ingresos.`id_producto` AS detalle_ingresos_id_producto
            FROM `ingresos` ingresos INNER JOIN `detalle_ingresos` detalle_ingresos 
			ON ingresos.`id_ingreso` = detalle_ingresos.`id_ingreso` 
			WHERE ingresos.`id_empresa` = '".$sesion_id_empresa1."'  and
			detalle_ingresos.id_ingreso='".$id_factura."' and detalle_ingresos.id_producto='".$id_producto."'; ";
//echo "<br>".$sql2;         		
			
          $resp2 = mysql_query($sql2);
          $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
          while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
          {
			 $ingresos_id_ingreso = $row2['ingresos_id_ingreso'];
             $detalle_ingresos_id_detalle_ingreso = $row2['detalle_ingresos_id_detalle_ingreso'];
             $ingresos_numero = $row2['ingresos_numero'];
			 $compras_cantidad = $row2['detalle_ingresos_cantidad'];
			 $compras_v_unitario = $row2['detalle_ingresos_valor_unitario'];
              	
             $valTotalCompra = $compras_cantidad * $compras_v_unitario;
			 
		//	  echo "<br>"."cantidad ingreso=".$compras_cantidad;
			  
		//	   echo "<br>"."total cantidad1 =".$saldoCant."<br>";
             $saldoCant = $saldoCant + $compras_cantidad;
						
		//	 echo "<br>"."total cantidad2 =".$saldoCant."<br>";
			 
             $saldoVT = $saldoVT + $valTotalCompra;
             if($saldoVT == 0 && $saldoCant == 0)
			 {
              $saldoVU = 0; 
			 }
			 else
			 {
				IF ($saldoCant <> 0)
				{
					$saldoVU = floatval($saldoVT / $saldoCant);
				}
             }
					
			 $ventas_cantidad=0;
			 $ventas_v_unitario=0;
			 $salidaVT=0;
                
             $sql="INSERT INTO KARDEX_PROM(id_producto,fecha, detalle, cpra_cantidad, cpra_precio, cpra_total,
					ven_cantidad, ven_precio,ven_total, sal_cantidad, sal_precio, sal_total,empresa)
					VALUES ('".$id_producto."','".$fecha."','".$detalle."','".$compras_cantidad."',
					        '".$compras_v_unitario."','".$valTotalCompra."',
							'".$ventas_cantidad."','".$ventas_v_unitario."','".$salidaVT."',
							'".$saldoCant."','".$saldoVU."','".$saldoVT."','".$sesion_id_empresa1."')";
		  //  echo $sql;			
	        $resultado=mysql_query($sql);		                 
          }
      }
		
	  if($detalle == "Egreso")
	  {
              //  echo "entro a egreso";
		  $sql3 = "SELECT
                 egresos.`id_egreso` AS egresos_id_egreso, egresos.`fecha` AS egresos_fecha,
                 egresos.`estado` AS egresos_estado,egresos.`total` AS egresos_total,
                 egresos.`sub_total` AS egresos_sub_total,
                 egresos.`numero` AS egresos_numero, egresos.`fecha_anulacion` AS egresos_fecha_anulacion,
                 egresos.`descripcion` AS egresos_descripcion, egresos.`id_iva` AS egresos_id_iva,
                 detalle_egresos.`id_detalle_egreso` AS detalle_egresos_id_detalle_egreso,
                 detalle_egresos.`cantidad` AS detalle_egresos_cantidad,
                 detalle_egresos.`estado` AS detalle_egresos_estado,
                 detalle_egresos.`v_unitario` AS detalle_egresos_v_unitario,
                 detalle_egresos.`v_total` AS detalle_egresos_v_total,
                 detalle_egresos.`id_egreso` AS detalle_egresos_id_egreso,
                 detalle_egresos.`id_producto` AS detalle_egresos_id_producto
            FROM
                 `egresos` egresos INNER JOIN `detalle_egresos` detalle_egresos 
				 ON egresos.`id_egreso` = detalle_egresos.`id_egreso` 
				 WHERE egresos.`id_empresa` = '".$sesion_id_empresa1."' and 
				 detalle_egresos.id_egreso='".$id_factura."' and detalle_egresos.id_producto='".$id_producto."'; ";
			//echo "<br>". $sql3;
        
     	  $resp3 = mysql_query($sql3);
          $numero_filas3 = mysql_num_rows($resp3); // obtenemos el número de filas
          while($row3=mysql_fetch_array($resp3))//permite ir de fila en fila de la tabla
          {
            $egresos_id_egreso = $row3['egresos_id_egreso'];
            $detalle_egresos_id_detalle_egreso = $row3['detalle_egresos_id_detalle_egreso'];
            $egresos_numero = $row3['egresos_numero'];
			$ventas_cantidad = $row3['detalle_egresos_cantidad'];
 		    $ventas_v_unitario = $row3['detalle_egresos_v_unitario'];
 //           $ventas_v_unitario = $saldoVU;              2020
            $salidaVT = $ventas_cantidad * $ventas_v_unitario;
            $saldoCant = $saldoCant - $ventas_cantidad;
            $saldoVT = $saldoVT - $salidaVT;
            if($saldoVT == 0 && $saldoCant == 0)
			{
               $saldoVU = 0;
            }
			else
			{
				IF ($saldoCant <> 0)
				{
                $saldoVU = floatval($saldoVT / $saldoCant);
					
				}
            }
					
			 $compras_cantidad=0;
			 $compras_v_unitario=0;
			 $valTotalCompra=0;
                
             $sql="INSERT INTO KARDEX_PROM(id_producto,fecha, detalle, cpra_cantidad, cpra_precio, cpra_total,
					ven_cantidad, ven_precio,ven_total, sal_cantidad, sal_precio, sal_total,empresa)
					VALUES ('".$id_producto."','".$fecha."','".$detalle."','".$compras_cantidad."',
					        '".$compras_v_unitario."','".$valTotalCompra."',
							'".$ventas_cantidad."','".$ventas_v_unitario."','".$salidaVT."',
							'".$saldoCant."','".$saldoVU."','".$saldoVT."','".$sesion_id_empresa1."')";
		  // echo $sql;			
	        $resultado=mysql_query($sql);	
			
          }
        }
		
	  }
		
	  $sql="select * from KARDEX_PROM where empresa ='".$sesion_id_empresa1."' and id_producto='".$id_producto."' order by id desc LIMIT 1  ";
	//echo $sql;
		$resultado=mysql_query($sql);
		$costo_promedio1=0;
		while($rows=mysql_fetch_array($resultado))
		{
			$costo_promedio1 = $rows['sal_precio'];
		}
		//echo "costo promedio";
		/* if (opcion1 == 2)
		{
			echo $costo_promedio1;			
		} */
		//echo "promedio111===".$costo_promedio1;
		return $costo_promedio1;
		
    }else{
        
            $sql="SELECT costo from productos where id_empresa='".$sesion_id_empresa1."' and id_producto='".$id_producto."'"   ;
	        $resultado=mysql_query($sql);
	        
	        while($rows=mysql_fetch_array($resultado))
		{
			$costo_promedio1 = $rows['costo'];
		}
		return $costo_promedio1;
    }
		
		
}


	if(isset($_POST['idProducto']))
	{
       $id_producto = $_POST['idProducto'];
	   $sesion_id_empresa1= $sesion_id_empresa;
	//   echo $id_producto;
	   //$promedio1=calcularPromedio();
	   $costo_promedio1=calcularPromedio($id_producto,$sesion_id_empresa1);
	   //ECHO "<br>";
	   //echo "promedio2==";
	   echo $costo_promedio1;		
	}

	
?>