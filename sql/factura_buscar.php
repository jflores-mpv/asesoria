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
	$sesion_codigo_lug = $_SESSION['sesion_codigo_lug'];
	/* $sesion_codigo_lug=$_POST['textPunto'];
        date_default_timezone_set('America/Guayaquil');
		echo "sql accion";
		echo $sesion_codigo_lug; */
		//$sesion_codigo_lug='002';
//echo $txtAccion;

//echo $sesion_codigo_lug;
	if($txtAccion == 8)
	{
		//BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
		try
		{
		// esta pagina retorna en la pagina: asientosContables.php 
    
			if(isset($_POST['queryString'])) 
			{
				$queryString = $_POST['queryString'];
				//$aux = $_POST['aux'];
				
				echo $queryString;            
				$a=0;
				if(strlen($queryString) >0) 
				{
					$query6 = "SELECT
					ventas.`id_venta` AS ventas_id_venta,
					ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
					ventas.`fecha_venta` AS ventas_fecha_venta,
					ventas.`descripcion` AS ventas_descripcion,
					detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
					detalle_ventas.`id_servicio` AS detalle_ventas_id_servicio,
					servicios.`nombre` AS servicios_nombre,
					servicios.`iva` AS servicios_iva,
					detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
					detalle_ventas.`v_unitario` AS detalle_v_unitario,
					detalle_ventas.`v_total` AS detalle_ventas_v_total,
					clientes.`id_cliente` AS clientes_id_cliente,
					clientes.`cedula` AS clientes_cedula,
					clientes.`nombre` AS clientes_nombre,
					clientes.`apellido` AS clientes_apellido,
					clientes.`direccion` AS clientes_direccion,
					
					clientes.`telefono` AS clientes_telefono
					FROM
						`ventas` ventas INNER JOIN  `detalle_ventas` detalle_ventas
						ON ventas.id_venta = detalle_ventas.id_venta
						INNER JOIN `servicios` servicios
						ON detalle_ventas.`id_servicio` = servicios.`id_servicio`  
						INNER JOIN `clientes` clientes
						ON ventas.`id_cliente` = clientes.`id_cliente`
						WHERE ventas.`id_empresa`='".$sesion_id_empresa."' and ventas.`codigo_lug`='".$sesion_codigo_lug."' and
						ventas.`numero_factura_venta` = '".$queryString."'; "; 
					
					//	WHERE ventas.`id_empresa`='".$sesion_id_empresa."' and ventas.`codigo_lug`='002' and
					
					//	WHERE ventas.`id_empresa`='".$sesion_id_empresa."' and ventas.`codigo_lug`='".$sesion_codigo_lug."' and
					
					echo $query6;  
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
								$cantidad=$row['detalle_ventas_cantidad'];
								$precio_venta1=$row['detalle_v_unitario'];
							
								if($servicios_iva == "Si"){
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
								
								$cadena=$cadena."*".$numero_filas."?".$row['ventas_id_venta']."?";
								
								$cadena=$cadena.$row['ventas_numero_factura_venta']."?";
								
								$cadena=$cadena.$row['ventas_fecha_venta']."?";
								
								$cadena=$cadena.$row['clientes_cedula']."?".$row['clientes_nombre']."?";
								
								$cadena=$cadena.$row['clientes_apellido']."?".$row['clientes_telefono']."?";

								$cadena=$cadena.$row['detalle_ventas_id_servicio']."?".$row['servicios_nombre']."?";
								
								$cadena=$cadena.$row['detalle_ventas_cantidad']."?".$row['detalle_v_unitario']."?";
								
								$cadena=$cadena.$row['detalle_ventas_v_total']."?";
								
								$cadena=$cadena.$total_iva."?";
								
								$cadena=$cadena.$total."?";
								
								$cadena=$cadena.$iva."?";
																
	//							
								//$cadena=$cadena.$iva;
								$cadena=$cadena.$row['servicios_id_servicio']."?";
	//							$cadena=$cadena.$row['servicios_nombre'];
								
								
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
						//echo "cadena";
						//   echo $cadenaBancos."î".$cadena;
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
		{ ?> <div class="transparent_ajax_error">
		<p>Error en la consulta: <?php echo "".$ex ?></p>
		</div> 
		<?php 
		}
	}

?>