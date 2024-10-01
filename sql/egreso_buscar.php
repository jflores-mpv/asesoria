<?php
	//require_once('../ver_sesion.php');
    date_default_timezone_set('America/Guayaquil');
	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');
    $txtAccion = $_POST['txtAccion'];
    
    $id_empresa_cookies = $_COOKIE["id_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    
//	$sesion_codigo_lug = $_SESSION['sesion_codigo_lug'];

//echo "estoy en sql";
if($txtAccion == 8)
{
		//BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
	try
	{
		// esta pagina retorna en la pagina: asientosContables.php 
    //echo "estoy en sql";
		if(isset($_POST['queryString'])) 
		{
			$queryString = $_POST['queryString'];
				//$aux = $_POST['aux'];
				
			//	echo $queryString;            
			//	detalle_egreso.`id_servicio` AS detalle_egreso_id_servicio,
			//  servicios.`nombre` AS servicios_nombre,servicios.`iva` AS servicios_iva,
					
			$a=0;
			if(strlen($queryString) >0) 
			{
				$query6 = "SELECT egreso.`id_egreso` AS egreso_id_egreso,	egreso.`numero` AS egreso_numero,
					egreso.`fecha` AS egreso_fecha, egreso.`observacion` AS egreso_observacion,
					productos.`producto` AS servicios_nombre,productos.`iva` AS servicios_iva,
					productos.`codigo` AS producto_codigo,productos.`id_cuenta` AS producto_cuenta,	productos.`costo` AS productos_costo,
					det_egreso.`id_producto` AS detalle_egreso_id_producto,det_egreso.`tipo_movim` AS detalle_egreso_tipo_mov,
					det_egreso.`cantidad` AS detalle_egreso_cantidad,det_egreso.`v_unitario` AS detalle_v_unitario,
					det_egreso.`v_total` AS detalle_egreso_v_total,det_egreso.`bodega` AS detalle_egreso_bodega,
					centro_costo.id_cuenta as centro_costo_cuenta,centro_costo.descripcion as centro_costo_descripcion 
					FROM `egresos` egreso INNER JOIN  `detalle_egresos` det_egreso
						   ON egreso.id_egreso = det_egreso.id_egreso and egreso.`id_empresa`=det_egreso.`id_empresa`
					INNER JOIN `productos` productos
						  ON det_egreso.`id_producto` = productos.`id_producto` and productos.`id_empresa`=det_egreso.`id_empresa`
						inner join `centro_costo` centro_costo
						on productos.`grupo`= centro_costo.id_centro_costo and  centro_costo.`empresa`=det_egreso.`id_empresa`
					WHERE egreso.`id_empresa`='".$sesion_id_empresa."' and egreso.`numero`= '".$queryString."'"; 
					
					if($sesion_id_empresa==41){
					   // det_egreso.cuentaOrigen = centro_costo.id_cuenta
					   // det_egreso.`bodega`= centro_costo.id_centro_costo 
					   // echo $query6;  
					}
				//	echo $query6;  
					$result6 = mysql_query($query6) or die(mysql_error());
					$numero_filas = mysql_num_rows($result6); // obtenemos el número de filas
				if($result6) 
				{
					if($numero_filas > 0)
					{	// cuando no hay datos envia 0  
							$cadena = "";
							$cadenaBancos = "";
						while ($row = mysql_fetch_assoc($result6))
						{ 
							$servicios_iva=$row['servicios_iva'];
							$cantidad=$row['detalle_egreso_cantidad'];
							$precio_venta1=$row['detalle_v_unitario'];
							
							if($servicios_iva == "Si")
							{
					// SACA LOS IMPUESTOS ACTUAL
									$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
									$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
									$iva=0;
									$impuestos_id_plan_cuenta = 0;
									while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
										$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
									}
									$total_iva = ($cantidad*$precio_venta1 * $iva)/100;
									$total = $cantidad*$precio_venta1 + $total_iva;
							}
							else
							{
									$total = $cantidad*$precio_venta1;
									$txtIdIva = 0;
									$total_iva = 0;
									$impuestos_id_plan_cuenta = 0;
							}
							$cadena=$cadena."*".$numero_filas."?".$row['egreso_id_egreso']."?";
								$cadena=$cadena.$row['egreso_numero']."?";
								$cadena=$cadena.$row['egreso_fecha']."?";
								$cadena=$cadena.$row['egreso_observacion']."?";
								$cadena=$cadena.$row['detalle_egreso_id_producto']."?";
								$cadena=$cadena.$row['producto_codigo']."?".$row['servicios_nombre']."?";
								$cadena=$cadena.$row['detalle_egreso_cantidad']."?".$row['detalle_v_unitario']."?";
								$cadena=$cadena.$row['detalle_egreso_v_total']."?";
								$cadena=$cadena.$row['detalle_egreso_bodega']."?";
								$cadena=$cadena.$row['detalle_egreso_tipo_mov']."?";
								$cadena=$cadena.$row['producto_cuenta']."?";
								$cadena=$cadena.$row['centro_costo_cuenta']."?";
								$cadena=$cadena.$row['centro_costo_descripcion']."?";
								$cadena=$cadena.$row['productos_costo']."?";
								$cadena=$cadena.$row['productos_costo']."?";
								
								
								
							//	$cadena=$cadena.$total_iva."?";
							//	$cadena=$cadena.$total."?";
							//	$cadena=$cadena.$iva."?";
																
	//							
								//$cadena=$cadena.$iva;
							//	$cadena=$cadena.$row['servicios_id_servicio']."?";
	//						//	$cadena=$cadena.$row['servicios_nombre'];
						}	
					
						echo $cadenaBancos."î".$cadena;
						//echo $cadena;
					}
					else
					{
						$cadena = "";
						$cadenaBancos = "";
						$cadena=$cadena."*"."1"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."2"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."3"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."4"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
		 
						echo $cadenaBancos."î".$cadena;
					}			
				}
				else 
				{
						 echo 'ERROR: Hay un problema con la consulta.';
				}	
			}
			else 
				{
					echo 'La longitud no es la permitida.';
				} // There is a queryString.
		} 
		else 
			{
				echo 'No hay ningún acceso directo a este script!';
			}
			
	}	
	catch(Exception $ex) 
	{ ?> 
		<div class="transparent_ajax_error">
			<p>Error en la consulta: <?php echo "".$ex ?></p>
		</div> 
	  <?php 
	}

}
	
if($txtAccion == 9)
{
		//BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
	try
	{
		// esta pagina retorna en la pagina: asientosContables.php 
    //echo "estoy en sql";
		if(isset($_POST['queryString'])) 
		{
			$queryString = $_POST['queryString'];
				//$aux = $_POST['aux'];
				
			//	echo $queryString;            
			//	detalle_egreso.`id_servicio` AS detalle_egreso_id_servicio,
			//  servicios.`nombre` AS servicios_nombre,servicios.`iva` AS servicios_iva,
					
			$a=0;
			if(strlen($queryString) >0) 
			{
				$query6 = "SELECT
				ingresos.id_ingreso AS egreso_id_egreso,
				ingresos.numero AS egreso_numero,
				ingresos.fecha AS egreso_fecha,
				ingresos.observacion AS egreso_observacion,
				productos.`producto` AS servicios_nombre,
				productos.`iva` AS servicios_iva,
				productos.`codigo` AS producto_codigo,
				productos.`id_cuenta` AS producto_cuenta,
				productos.`costo` AS productos_costo,
				detalle_ingresos.id_producto AS detalle_egreso_id_producto,
				detalle_ingresos.`tipo_movim` AS detalle_egreso_tipo_mov,
				detalle_ingresos.`cantidad` AS detalle_egreso_cantidad,
				detalle_ingresos.`v_unitario` AS detalle_v_unitario,
				detalle_ingresos.`v_total` AS detalle_egreso_v_total,
				detalle_ingresos.`bodega` AS detalle_egreso_bodega,
				centro_costo.id_cuenta AS centro_costo_cuenta,
				centro_costo.descripcion AS centro_costo_descripcion
			FROM
				`ingresos` ingresos
			INNER JOIN `detalle_ingresos` detalle_ingresos ON
				ingresos.id_ingreso = detalle_ingresos.id_ingreso AND ingresos.`id_empresa` = detalle_ingresos.`id_empresa`
			INNER JOIN `productos` productos ON
				detalle_ingresos.`id_producto` = productos.`id_producto` AND productos.`id_empresa` = detalle_ingresos.`id_empresa`
	
			LEFT JOIN `centro_costo` centro_costo ON
				productos.`grupo` = centro_costo.id_centro_costo AND centro_costo.`empresa` = detalle_ingresos.`id_empresa`
		
					WHERE ingresos.`id_empresa`='".$sesion_id_empresa."' and ingresos.`numero`= '".$queryString."'  "; 
					
					if($sesion_id_empresa==41){
					    echo $query6;  
					}
					// echo $query6;  
					$result6 = mysql_query($query6) or die(mysql_error());
					$numero_filas = mysql_num_rows($result6); // obtenemos el número de filas
				if($result6) 
				{
					if($numero_filas > 0)
					{	// cuando no hay datos envia 0  
							$cadena = "";
							$cadenaBancos = "";
						while ($row = mysql_fetch_assoc($result6))
						{ 
							$servicios_iva=$row['servicios_iva'];
							$cantidad=$row['detalle_egreso_cantidad'];
							$precio_venta1=$row['detalle_v_unitario'];
							
							if($servicios_iva == "Si")
							{
					// SACA LOS IMPUESTOS ACTUAL
									$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
									$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
									$iva=0;
									$impuestos_id_plan_cuenta = 0;
									while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
										$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
									}
									$total_iva = ($cantidad*$precio_venta1 * $iva)/100;
									$total = $cantidad*$precio_venta1 + $total_iva;
							}
							else
							{
								$iva=0;
									$total = $cantidad*$precio_venta1;
									$txtIdIva = 0;
									$total_iva = 0;
									$impuestos_id_plan_cuenta = 0;
							}
							$cadena=$cadena."*".$numero_filas."?".$row['egreso_id_egreso']."?";
								$cadena=$cadena.$row['egreso_numero']."?";
								$cadena=$cadena.$row['egreso_fecha']."?";
								$cadena=$cadena.$row['egreso_observacion']."?";
								$cadena=$cadena.$row['detalle_egreso_id_producto']."?";
								$cadena=$cadena.$row['producto_codigo']."?".$row['servicios_nombre']."?";
								$cadena=$cadena.$row['detalle_egreso_cantidad']."?".$row['detalle_v_unitario']."?";
								$cadena=$cadena.$row['detalle_egreso_v_total']."?";
								$cadena=$cadena.$row['detalle_egreso_bodega']."?";
								$cadena=$cadena.$row['detalle_egreso_tipo_mov']."?";
								$cadena=$cadena.$row['producto_cuenta']."?";
								$cadena=$cadena.$row['centro_costo_cuenta']."?";
								$cadena=$cadena.$row['centro_costo_descripcion']."?";
								$cadena=$cadena.$row['productos_costo']."?";
								$cadena=$cadena.$txtIdIva."?";
								$cadena=$cadena.$total_iva."?";
								$cadena=$cadena.$iva."?";
								$cadena=$cadena.$row['productos_costo']."?";
								$cadena=$cadena.$row['productos_costo']."?";
								
								
								
							//	$cadena=$cadena.$total_iva."?";
							//	$cadena=$cadena.$total."?";
							//	$cadena=$cadena.$iva."?";
																
	//							
								//$cadena=$cadena.$iva;
							//	$cadena=$cadena.$row['servicios_id_servicio']."?";
	//						//	$cadena=$cadena.$row['servicios_nombre'];
						}	
					
						echo $cadenaBancos."î".$cadena;
						//echo $cadena;
					}
					else
					{
						$cadena = "";
						$cadenaBancos = "";
						$cadena=$cadena."*"."1"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."2"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."3"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."4"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
		 
						echo $cadenaBancos."î".$cadena;
					}			
				}
				else 
				{
						 echo 'ERROR: Hay un problema con la consulta.';
				}	
			}
			else 
				{
					echo 'La longitud no es la permitida.';
				} // There is a queryString.
		} 
		else 
			{
				echo 'No hay ningún acceso directo a este script!';
			}
			
	}	
	catch(Exception $ex) 
	{ ?> 
		<div class="transparent_ajax_error">
			<p>Error en la consulta: <?php echo "".$ex ?></p>
		</div> 
	  <?php 
	}

}	



if($txtAccion == 10)
{
		//BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
	try
	{
		// esta pagina retorna en la pagina: asientosContables.php 
    //echo "estoy en sql";
		if(isset($_POST['queryString'])) 
		{
			$queryString = $_POST['queryString'];
				//$aux = $_POST['aux'];
				
			//	echo $queryString;            
			//	detalle_egreso.`id_servicio` AS detalle_egreso_id_servicio,
			//  servicios.`nombre` AS servicios_nombre,servicios.`iva` AS servicios_iva,
					
			$a=0;
			if(strlen($queryString) >0) 
			{
				$query6 = "SELECT
    egreso.`id_egreso` AS egreso_id_egreso,
    egreso.`numero` AS egreso_numero,
    egreso.`fecha` AS egreso_fecha,
    egreso.`observacion` AS egreso_observacion,
    productos.`producto` AS servicios_nombre,
    productos.`iva` AS servicios_iva,
    productos.`codigo` AS producto_codigo,
    productos.`id_cuenta` AS producto_cuenta,
    productos.`costo` AS productos_costo,
    det_egreso.`id_producto` AS detalle_egreso_id_producto,
    det_egreso.`tipo_movim` AS detalle_egreso_tipo_mov,
    det_egreso.`cantidad` AS detalle_egreso_cantidad,
    det_egreso.`v_unitario` AS detalle_v_unitario,
    det_egreso.`v_total` AS detalle_egreso_v_total,
    det_egreso.`bodega` AS detalle_egreso_bodega,
    centro_costo.id_cuenta AS centro_costo_cuenta,
    centro_costo.descripcion AS centro_costo_descripcion,
    transferencias.id_ingreso as transferencia_ingreso,
    transferencias.id_egreso as transferencia_egreso
FROM
	transferencias 
    
  INNER JOIN `egresos` egreso ON egreso.id_egreso= transferencias.id_egreso
INNER JOIN `detalle_egresos` det_egreso ON
    egreso.id_egreso = det_egreso.id_egreso AND egreso.`id_empresa` = det_egreso.`id_empresa`
INNER JOIN `productos` productos ON
    det_egreso.`id_producto` = productos.`id_producto` AND productos.`id_empresa` = det_egreso.`id_empresa`
INNER JOIN `centro_costo` centro_costo ON
    productos.`grupo` = centro_costo.id_centro_costo AND centro_costo.`empresa` = det_egreso.`id_empresa`
	WHERE egreso.`id_empresa`='".$sesion_id_empresa."'   AND transferencias.`num_trans`= '".$queryString."'"; 
					
					if($sesion_id_empresa==41){
					   // det_egreso.cuentaOrigen = centro_costo.id_cuenta
					   // det_egreso.`bodega`= centro_costo.id_centro_costo 
					    echo $query6;  
					}
				//	echo $query6;  
					$result6 = mysql_query($query6) or die(mysql_error());
					$numero_filas = mysql_num_rows($result6); // obtenemos el número de filas
				if($result6) 
				{
					if($numero_filas > 0)
					{	// cuando no hay datos envia 0  
							$cadena = "";
							$cadenaBancos = "";
							$contador=0;
						while ($row = mysql_fetch_assoc($result6))
						{ 
						    if($contador ==0){
						          $sqlBodegaOrigen = "SELECT id_egreso, bodega FROM `detalle_egresos` WHERE `id_egreso` = ".$row['transferencia_egreso']." LIMIT 1 ";
						      //  $sqlBodegaOrigen = "SELECT id_ingreso, bodega FROM `detalle_ingresos` WHERE `id_ingreso` = ".$row['transferencia_ingreso']." LIMIT 1 ";
						        $resultOrigen = mysql_query($sqlBodegaOrigen);
						        $bodegaOrigen = '';
						       while($rowBo = mysql_fetch_array($resultOrigen)){
						           $bodegaOrigen = $rowBo['bodega'];
						       }
						       $sqlBodegaDestino = "SELECT id_ingreso, bodega FROM `detalle_ingresos` WHERE `id_ingreso` = ".$row['transferencia_ingreso']." LIMIT 1 ";
						        $resultDestino = mysql_query($sqlBodegaDestino);
						        $bodegaDestino = '';
						       while($rowDestino = mysql_fetch_array($resultDestino)){
						           $bodegaDestino = $rowDestino['bodega'];
						       }
						       $contador++;
						    }
						    
							$servicios_iva=$row['servicios_iva'];
							$cantidad=$row['detalle_egreso_cantidad'];
							$precio_venta1=$row['detalle_v_unitario'];
							
							if($servicios_iva == "Si")
							{
					// SACA LOS IMPUESTOS ACTUAL
									$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
									$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
									$iva=0;
									$impuestos_id_plan_cuenta = 0;
									while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
										$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
									}
									$total_iva = ($cantidad*$precio_venta1 * $iva)/100;
									$total = $cantidad*$precio_venta1 + $total_iva;
							}
							else
							{
									$total = $cantidad*$precio_venta1;
									$txtIdIva = 0;
									$total_iva = 0;
									$impuestos_id_plan_cuenta = 0;
							}
							$cadena=$cadena."*".$numero_filas."?".$row['egreso_id_egreso']."?";
								$cadena=$cadena.$row['egreso_numero']."?";
								$cadena=$cadena.$row['egreso_fecha']."?";
								$cadena=$cadena.$row['egreso_observacion']."?";
								$cadena=$cadena.$row['detalle_egreso_id_producto']."?";
								$cadena=$cadena.$row['producto_codigo']."?".$row['servicios_nombre']."?";
								$cadena=$cadena.$row['detalle_egreso_cantidad']."?".$row['detalle_v_unitario']."?";
								$cadena=$cadena.$row['detalle_egreso_v_total']."?";
								$cadena=$cadena.$bodegaOrigen."?";
								$cadena=$cadena.$row['detalle_egreso_tipo_mov']."?";
								$cadena=$cadena.$row['producto_cuenta']."?";
								$cadena=$cadena.$row['centro_costo_cuenta']."?";
								$cadena=$cadena.$row['centro_costo_descripcion']."?";
								$cadena=$cadena.$row['productos_costo']."?";
									$cadena=$cadena.$bodegaDestino."?";
								
								
							//	$cadena=$cadena.$total_iva."?";
							//	$cadena=$cadena.$total."?";
							//	$cadena=$cadena.$iva."?";
																
	//							
								//$cadena=$cadena.$iva;
							//	$cadena=$cadena.$row['servicios_id_servicio']."?";
	//						//	$cadena=$cadena.$row['servicios_nombre'];
						}	
					
						echo $cadenaBancos."î".$cadena;
						//echo $cadena;
					}
					else
					{
						$cadena = "";
						$cadenaBancos = "";
						$cadena=$cadena."*"."1"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."2"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."3"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
						$cadena=$cadena."*"."4"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
		 
						echo $cadenaBancos."î".$cadena;
					}			
				}
				else 
				{
						 echo 'ERROR: Hay un problema con la consulta.';
				}	
			}
			else 
				{
					echo 'La longitud no es la permitida.';
				} // There is a queryString.
		} 
		else 
			{
				echo 'No hay ningún acceso directo a este script!';
			}
			
	}	
	catch(Exception $ex) 
	{ ?> 
		<div class="transparent_ajax_error">
			<p>Error en la consulta: <?php echo "".$ex ?></p>
		</div> 
	  <?php 
	}

}
?>

							
					
			