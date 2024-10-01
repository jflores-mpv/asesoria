<?php
error_reporting(0);
	//require_once('../ver_sesion.php');

        date_default_timezone_set('America/Guayaquil');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

    $txtAccion = $_POST['txtAccion'];
    $est = $_POST['est'];
    $emi = $_POST['emi'];
    $documento = $_POST['documento'];

    $id_empresa_cookies = $_COOKIE["id_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    
    
//	$sesion_codigo_lug = $_SESSION['sesion_codigo_lug'];

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
			//	detalle_ventas.`id_servicio` AS detalle_ventas_id_servicio,
			//  servicios.`nombre` AS servicios_nombre,servicios.`iva` AS servicios_iva,
					
				$a=0;
				if(strlen($queryString) >0) 
				{
					$query6 = "SELECT
					ventas.`id_venta` AS ventas_id_venta,
					ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
					ventas.`fecha_venta` AS ventas_fecha_venta,
					
					ventas.`sub0` AS sub0,
					ventas.`sub12` AS sub12,
					ventas.`sub_total` AS sub_total,
					ventas.`Vendedor_id` AS Vendedor_id,
					
					
					ventas.`descripcion` AS ventas_descripcion,
					ventas.`descuento` AS ventas_descuento,
					ventas.`propina` AS ventas_propina,
					ventas.`Autorizacion` AS ventas_Autorizacion,
					ventas.`xml` AS ventas_xml,
					ventas.`Retiva` AS ventas_Retiva,
					ventas.`MotivoNota` AS ventas_MotivoNota,
					ventas.vendedor_id_tabla as ventas_vendedor_id_tabla,
					ventas.`tipo_inco_term` AS ventas_tipo_inco_term,
					ventas.`lugar_inco_term` AS ventas_MotivoNotalugar_inco_term,
					ventas.`pais_origen` AS ventas_pais_origen,
					ventas.`puerto_embarque` AS ventas_puerto_embarque,
					ventas.`puerto_destino` AS ventas_puerto_destino,
					ventas.`pais_destino` AS ventas_pais_destino,
					
					ventas.`pais_adquisicion` AS ventas_pais_adquisicion,
					ventas.`numero_dae` AS ventas_numero_dae,
					ventas.`numero_transporte` AS ventas_numero_transporte,
					ventas.`flete_internacional` AS ventas_flete_internacional,
					
					ventas.`seguro_internaiconal` AS ventas_seguro_internaiconal,
					ventas.`gastos_aduaneros` AS ventas_gastos_aduaneros,
					ventas.`gastos_transporte` AS ventas_gastos_transporte,

					
					detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
					detalle_ventas.`id_kardex` AS detalle_ventas_id_servicio,

					productos.`id_producto` AS productos_id_producto,
					productos.`producto` AS servicios_nombre,
					detalle_ventas.`tarifa_iva` AS servicios_iva,
					productos.`codigo` AS productos_codigo,
					productos.`precio1` AS productos_precio1,
					productos.`tipos_compras` AS productos_tipos_compras,
					productos.`iva` AS productos_iva,

					centro_costo.`id_centro_costo` AS centro_id, 
					centro_costo.`id_cuenta` AS productos_id_cuenta, 
					centro_costo.`descripcion` AS centro_descripcion, 
					detalle_ventas.`idBodegaInventario` AS idBodegaInventario, 


					detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
					detalle_ventas.`v_unitario` AS detalle_v_unitario,
					detalle_ventas.`v_total` AS detalle_ventas_v_total,
					detalle_ventas.`descuento` AS detalle_ventas_descuento,
	                detalle_ventas.`id_lote` AS detalle_ventas_id_lote,
					
					clientes.`id_cliente` AS clientes_id_cliente,
					clientes.`cedula` AS clientes_cedula,
					clientes.`nombre` AS clientes_nombre,
					clientes.`apellido` AS clientes_apellido,
					clientes.`direccion` AS clientes_direccion,
					clientes.`telefono` AS clientes_telefono,
					clientes.`caracter_identificacion` AS clientes_caracter_identificacion,
					cantBodegas.cantidad as bodega_cantidad,
			
					
					ventas.`total` AS total
					
					
					FROM `ventas` ventas 
					    LEFT JOIN  `detalle_ventas` detalle_ventas
						   ON ventas.id_venta = detalle_ventas.id_venta and ventas.`id_empresa`=detalle_ventas.`id_empresa`
						LEFT JOIN `productos` productos
						  ON detalle_ventas.`id_kardex` = productos.`id_producto` and productos.`id_empresa`=detalle_ventas.`id_empresa`  
						LEFT JOIN `clientes` clientes 
						  ON ventas.`id_cliente` = clientes.`id_cliente` and clientes.`id_empresa`=ventas.`id_empresa`
						  LEFT JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
						  LEFT JOIN bodegas ON  detalle_ventas.idBodegaInventario = bodegas.id
						  LEFT JOIN cantBodegas ON  productos.codigo = cantBodegas.`idProducto` and bodegas.id= cantBodegas.idBodega
					WHERE ventas.`id_empresa`='".$sesion_id_empresa."' and ventas.`numero_factura_venta`= '".$queryString."' and  
					ventas.`codigo_lug` ='".$emi."' 
					and codigo_pun='".$est."' and ventas.tipo_documento='".$documento."' GROUP BY detalle_ventas.id_detalle_venta; "; 
					
					
					if($sesion_id_empresa==41){
					    echo $query6;  
					}
				
					$result6 = mysql_query($query6) or die( mysql_error() );
					$numero_filas = mysql_num_rows($result6); 
					
					if($result6) 
					{
						if($numero_filas > 0)
						{	
							$cadena = "";
							$cadenaBancos = "";
							$ivaFinal=0;
							$array_impuestos = array();
							while ($row = mysql_fetch_assoc($result6))
							{ 
								$servicios_iva=$row['servicios_iva'];
								$cantidad=$row['detalle_ventas_cantidad'];
								$precio_venta1=$row['detalle_v_unitario'];
								
								if($servicios_iva >0 ){

									$sqlIva1="Select * From impuestos where id_empresa='".$sesion_id_empresa."'  AND id_iva = '".$servicios_iva."' ";
									$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
									$iva=0;
									$impuestos_id_plan_cuenta = 0;
									while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
										$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
										
									}
									$total_iva = ($cantidad*($precio_venta1-$row['detalle_ventas_descuento'])* $iva)/100;
									$total = $cantidad*($precio_venta1-$row['detalle_ventas_descuento'])+ $total_iva;


									$ivaFinal = $ivaFinal +  ($cantidad*($precio_venta1-$row['detalle_ventas_descuento']) * $iva)/100;

									if (isset($array_impuestos[$txtIdIva])) {
										
									}else{
										$array_impuestos[$txtIdIva] = 0;
									}
									$array_impuestos[$txtIdIva] += $row['detalle_ventas_v_total'];

								}
								else
								{
									$total = $cantidad*$precio_venta1;
									$txtIdIva = 0;
									$total_iva = 0;
									$impuestos_id_plan_cuenta = 0;
									$array_impuestos[$txtIdIva] += $row['detalle_ventas_v_total'];
									
								}
								
								

								$cadena=$cadena."*".$numero_filas."?".$row['ventas_id_venta']."?";
								$cadena=$cadena.$row['ventas_numero_factura_venta']."?";
								$cadena=$cadena.$row['ventas_fecha_venta']."?";
								
								$cadena=$cadena.$row['clientes_cedula']."?".$row['clientes_nombre']."?";
								$cadena=$cadena.$row['clientes_apellido']."?".$row['clientes_telefono']."?";
						
								$cadena=$cadena.$row['detalle_ventas_id_servicio']."?".$row['servicios_nombre']."?";
								$cadena=$cadena.$row['detalle_ventas_cantidad']."?".$row['detalle_v_unitario']."?";
								$cadena=$cadena.$row['detalle_ventas_v_total']."?";//12
								$cadena=$cadena.$total_iva."?";//13
								$cadena=$cadena.$total."?";//14
								$cadena=$cadena.$iva."?";//15
																
	//							
								//$cadena=$cadena.$iva;
								$cadena=$cadena.$row['productos_id_producto']."?";//16
								$cadena=$cadena.$row['sub12']."?";//17
								$cadena=$cadena.$row['sub0']."?";//18
								$cadena=$cadena.$row['sub_total']."?";//19
								
								$cadena=$cadena.$row['total']."?";//20
								


								$cadena=$cadena.$row['productos_codigo']."?";//21
								$cadena=$cadena.$row['productos_precio1']."?";//22
								$cadena=$cadena.$row['productos_tipos_compras']."?";//23
								$cadena=$cadena.$row['productos_iva']."?";//24
								
								$cadena=$cadena.$row['centro_id']."?";//25
								$cadena=$cadena.$row['productos_id_cuenta']."?";//26
								$cadena=$cadena.$row['centro_descripcion']."?";//27
								$cadena=$cadena.$row['idBodegaInventario']."?";//28
								$cadena=$cadena.$txtIdIva."?";//29
								$cadena=$cadena.$row['clientes_direccion']."?";//30
								
								$descuento_detalle =(trim($row['detalle_ventas_descuento'])=='')?0.00:$row['detalle_ventas_descuento'];
								
								$cadena=$cadena.$descuento_detalle."?";//31
								$cadena=$cadena.$ivaFinal."?";//32
								$cadena=$cadena.$row['ventas_descuento']."?";//33
								$cadena=$cadena.$row['ventas_propina']."?";//34
								$cadena=$cadena.$row['clientes_id_cliente']."?";//35
								$cadena=$cadena.$row['ventas_descripcion']."?";//36

								$cadena=$cadena.$row['Vendedor_id']."?";//37
							$suma = $row['bodega_cantidad']+$row['detalle_ventas_cantidad'];
								$cadena=$cadena.$suma."?";//38
								$cadena=$cadena.$row['ventas_Autorizacion']."?";//39
								$cadena=$cadena.$row['ventas_xml']."?";//40
								$cadena=$cadena.$row['ventas_Retiva']."?";//41
								$cadena=$cadena.$row['ventas_MotivoNota']."?";//42
						
							$cadena=$cadena.$row['ventas_tipo_inco_term']."?";//43
								$cadena=$cadena.$row['ventas_MotivoNotalugar_inco_term']."?";//44
								$cadena=$cadena.$row['ventas_pais_origen']."?";//45
								
								$cadena=$cadena.$row['ventas_puerto_embarque']."?";//46
								$cadena=$cadena.$row['ventas_puerto_destino']."?";//47
								$cadena=$cadena.$row['ventas_pais_destino']."?";//48
								
					$cadena=$cadena.$row['ventas_pais_adquisicion']."?";//49
								
								$cadena=$cadena.$row['ventas_numero_dae']."?";//50
								$cadena=$cadena.$row['ventas_numero_transporte']."?";//51
								$cadena=$cadena.$row['ventas_flete_internacional']."?";//52
                $cadena=$cadena.$row['ventas_seguro_internaiconal']."?";//53
                
								$cadena=$cadena.$row['ventas_gastos_aduaneros']."?";//54
								$cadena=$cadena.$row['ventas_gastos_transporte']."?";//55
									$cadena=$cadena.$row['clientes_caracter_identificacion']."?";//56

                            $sqlNota="SELECT `id_venta`,`numero_factura_venta`, `codigo_pun`, `codigo_lug`, ventas.Autorizacion, ventas.ClaveAcceso FROM `ventas` WHERE ventas.id_venta='".$row['ventas_Retiva']."' ";
                            $resultNota= mysql_query($sqlNota);
                            $numero_factura_venta='';
                            while($rowN = mysql_fetch_array($resultNota) ){
                                $numero_factura_venta = $rowN['numero_factura_venta'];
                            }
	//							$cadena=$cadena.$row['servicios_nombre'];
	
								$cadena=$cadena.$numero_factura_venta."?";//57
								$cadena=$cadena.$row['ventas_vendedor_id_tabla']."?";//58
                                $cadena=$cadena.$row['detalle_ventas_id_lote']."?";//59
								 $cantidadSubtotales =count($array_impuestos);
                
								$cadena=$cadena.$cantidadSubtotales."?";//60
                        
								foreach ($array_impuestos as $txtIdIva => $valor) {
									// echo "Clave: $txtIdIva, Valor: $valor<br>";
									$cadena=$cadena.$txtIdIva."?";//61
									$cadena=$cadena.$valor."?";//62
								}
								
								
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
	
	if($txtAccion == 4)
	{
		//BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
		try
		{
		// esta pagina retorna en la pagina: asientosContables.php 
   
			if(isset($_POST['queryString'])) 
			{
				$queryString = $_POST['queryString'];
				//echo "venta NUMERO".$queryString;
				//$aux = $_POST['aux'];
			//	echo $queryString;            
			//	detalle_ventas.`id_servicio` AS detalle_ventas_id_servicio,
			//  servicios.`nombre` AS servicios_nombre,servicios.`iva` AS servicios_iva,
				$a=0;
				if(strlen($queryString) >0) 
				{
					$query6 = "SELECT ventas.`id_venta` AS ventas_id_venta,
					ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
					ventas.`fecha_venta` AS ventas_fecha_venta,
					ventas.`sub0` AS sub0,ventas.`sub12` AS sub12,
					ventas.`sub_total` AS sub_total,
					ventas.`descripcion` AS ventas_descripcion,
					ventas.`Retiva` AS ventas_Retiva,
					
						detalle_ventas.`descuento` AS detalle_ventas_descuento,
					
					detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
					
					detalle_ventas.`id_kardex` AS detalle_ventas_id_servicio,
					
					productos.`producto` AS servicios_nombre,
					
					productos.`grupo` AS servicios_grupo,
					
					productos.`iva` AS servicios_iva,
					
					centro_costo.`id_centro_costo` AS centro_costo_id,
					
					centro_costo.`tipo` AS centro_costo_tipo,
					
					centro_costo.`descripcion` AS centro_costo_descripcion,
					
					centro_costo.`id_cuenta` AS centro_costo_id_cuenta,
					
					detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
					
					detalle_ventas.`v_unitario` AS detalle_v_unitario,
					
					detalle_ventas.`v_total` AS detalle_ventas_v_total,
					
					
					clientes.`id_cliente` AS clientes_id_cliente,clientes.`cedula` AS clientes_cedula,
					clientes.`nombre` AS clientes_nombre,clientes.`apellido` AS clientes_apellido,
					clientes.`direccion` AS clientes_direccion,clientes.`telefono` AS clientes_telefono,
					
					ventas.`total` AS total
					
					FROM `ventas` ventas 
					    INNER JOIN  `detalle_ventas` detalle_ventas
						   ON ventas.id_venta = detalle_ventas.id_venta and ventas.`id_empresa`=detalle_ventas.`id_empresa`
						INNER JOIN `productos` productos
						  ON detalle_ventas.`id_kardex` = productos.`id_producto` and productos.`id_empresa`=detalle_ventas.`id_empresa` 
						INNER JOIN  `centro_costo` centro_costo
						   ON centro_costo.id_centro_costo = productos.grupo 
						   
						 
						INNER JOIN `clientes` clientes 
						  ON ventas.`id_cliente` = clientes.`id_cliente` and clientes.`id_empresa`=ventas.`id_empresa`
					WHERE ventas.`id_empresa`='".$sesion_id_empresa."' and ventas.`id_venta`= '".$queryString."' and 
					ventas.`codigo_lug` ='".$emi."' and codigo_pun='".$est."'; "; 					
					
			
					
					
				//	WHERE ventas.`id_empresa`='".$sesion_id_empresa."' and ventas.`codigo_lug`='".$sesion_codigo_lug."' and
					echo $query6;  
					$result6 = mysql_query($query6) or die(mysql_error());
					$numero_filas = mysql_num_rows($result6); // obtenemos el número de filas
				//	echo "iii";
				//	echo $query6;
				//	echo "numero de filas";
				//	echo $numero_filas;       
//echo "<br>";					
					if($result6) 
					{
						if($numero_filas > 0)
						{	// cuando no hay datos envia 0  
							$cadena = "";
							$cadenaBancos = "";
							$total_iva=0;
							$ivaFinal=0;
							while ($row = mysql_fetch_assoc($result6))
							{ 
								$servicios_iva=$row['servicios_iva'];
								$cantidad=$row['detalle_ventas_cantidad'];
								$precio_venta1=$row['detalle_v_unitario'];
							
							
								
									
								if($servicios_iva >0 ){

									$sqlIva1="Select * From impuestos where id_empresa='".$sesion_id_empresa."'  AND id_iva = '".$servicios_iva."' ";
									$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
									$iva=0;
									$impuestos_id_plan_cuenta = 0;
									while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
										$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
										
									}
									$total_iva = ($cantidad*($precio_venta1-$row['detalle_ventas_descuento'])* $iva)/100;
									$total = $cantidad*($precio_venta1-$row['detalle_ventas_descuento'])+ $total_iva;


									$ivaFinal = $ivaFinal +  ($cantidad*($precio_venta1-$row['detalle_ventas_descuento']) * $iva)/100;

									if (isset($array_impuestos[$txtIdIva])) {
										
									}else{
										$array_impuestos[$txtIdIva] = 0;
									}
									$array_impuestos[$txtIdIva] += $row['detalle_ventas_v_total'];

								}
								else
								{
									$total = $cantidad*$precio_venta1;
									$txtIdIva = 0;
									$total_iva = 0;
									$impuestos_id_plan_cuenta = 0;
									$array_impuestos[$txtIdIva] += $row['detalle_ventas_v_total'];
									
								}
								
								
								$ivacalculado = $ivaFinal;
								
								$cadena=$cadena."*".$numero_filas."?".$row['ventas_id_venta']."?";
								$cadena=$cadena.$row['ventas_numero_factura_venta']."?";
								$cadena=$cadena.$row['ventas_fecha_venta']."?";
								
								$cadena=$cadena.$row['clientes_cedula']."?".$row['clientes_nombre']."?";
								$cadena=$cadena.$row['clientes_apellido']."?".$row['clientes_telefono']."?";
							//	$cadena=$cadena.$row['clientes_direccion']."?";
								$cadena=$cadena.$row['detalle_ventas_id_servicio']."?".$row['servicios_nombre']."?";
								$cadena=$cadena.$row['detalle_ventas_cantidad']."?".$row['detalle_v_unitario']."?";
								$cadena=$cadena.$row['detalle_ventas_v_total']."?";
								$cadena=$cadena.$total_iva."?";
								$cadena=$cadena.$total."?";
								$cadena=$cadena.$iva."?";
	//							
								//$cadena=$cadena.$iva;
								$cadena=$cadena.$row['servicios_id_servicio']."?";
								$cadena=$cadena.$row['sub12']."?";
								$cadena=$cadena.$row['sub0']."?";
								$cadena=$cadena.$row['sub_total']."?";
								$cadena=$cadena.$row['clientes_id_cliente']."?";
								
								$cadena=$cadena.$row['sub_total']."?";
								
								
							    $cadena=$cadena.$ivacalculado."?";
								
								$cadena=$cadena.$row['centro_costo_id']."?";
								$cadena=$cadena.$row['centro_costo_tipo']."?";
								$cadena=$cadena.$row['centro_costo_descripcion']."?";
								$cadena=$cadena.$row['centro_costo_id_cuenta']."?";
								
								$cadena=$cadena.$row['servicios_iva']."?";
								$cadena=$cadena.$row['detalle_ventas_descuento']."?";
								$cadena=$cadena.$row['ventas_Retiva']."?";
								
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
	if($txtAccion == 12)
{
	//BUQUEDA PEDIDOS
	try
	{
	// esta pagina retorna en la pagina: asientosContables.php 
//echo "estoy en sql";
		if(isset($_POST['queryString'])) 
		{
			$queryString = $_POST['queryString'];
	
	
			$a=0;
			if(strlen($queryString) >0) 
				{
					$query6 = "SELECT
					pedidos.`pedido` AS pedidos_pedido,
					pedidos.`numero_pedido` AS pedidos_numero_pedido,
					pedidos.`fecha_pedido` AS pedidos_fecha_pedido,
					pedidos.`descripcion` AS pedidos_descripcion,
					pedidos.`Mesa_id` AS pedidos_Mesa_id,
					pedidos.`vendedor_id` AS pedidos_vendedor_id,

					pedidos.`sub0` AS sub0,
					pedidos.`sub12` AS sub12,
					pedidos.`sub_total` AS sub_total,
					pedidos.`total` AS total,
					pedidos.`descuento` AS descuento,
					pedidos.`propina` AS propina,
					
					detalle_pedido.`cantidad` AS detalle_pedido_cantidad,
					detalle_pedido.`id_servicio` AS detalle_pedido_id_servicio,
					
					productos.`id_producto` AS productos_id_producto,
					productos.`producto` AS servicios_nombre,
					productos.`iva` AS servicios_iva,
					productos.`codigo` AS productos_codigo,
					productos.`precio1` AS productos_precio1,
					productos.`tipos_compras` AS productos_tipos_compras,
					productos.`iva` AS productos_iva,
					
			
					detalle_pedido.`id_detalle_pedido` AS detalle_pedido_id,
					detalle_pedido.`cantidad` AS detalle_pedido_cantidad,
					detalle_pedido.`v_unitario` AS detalle_v_unitario,
					detalle_pedido.`v_total` AS detalle_pedido_v_total,
					detalle_pedido.`descuento` AS detalle_pedido_descuento,
					
					centro_costo.`id_centro_costo` AS centro_id, 
					centro_costo.`id_cuenta` AS productos_id_cuenta, 
					centro_costo.`descripcion` AS centro_descripcion, 
					detalle_pedido.`idBodega` AS idBodegaInventario, 
					
					clientes.`id_cliente` AS clientes_id_cliente,
					clientes.`cedula` AS clientes_cedula,
					clientes.`nombre` AS clientes_nombre,
					clientes.`apellido` AS clientes_apellido,
					clientes.`direccion` AS clientes_direccion,
					clientes.`telefono` AS clientes_telefono

					FROM
						`pedidos` pedidos INNER JOIN  `detalle_pedido` detalle_pedido ON pedidos.pedido = detalle_pedido.pedido
						INNER JOIN `productos` productos
						ON detalle_pedido.`pedido`=pedidos.`pedido` and detalle_pedido.`id_servicio` = productos.`id_producto`    
						INNER JOIN `clientes` clientes
						ON pedidos.`id_cliente` = clientes.`id_cliente`
						INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
						LEFT JOIN detalle_pedidos 
						ON detalle_pedidos.id_detalle= detalle_pedido.id_detalle_pedido 
						WHERE pedidos.`id_empresa`='".$sesion_id_empresa."' and
						pedidos.`codigo_lug`='".$emi."' and
						pedidos.`numero_pedido` = '".$queryString."'
						GROUP BY productos.id_producto; "; 
						
				// 	echo $query6;
					
					$result6 = mysql_query($query6) or die(mysql_error());
					$numero_filas = mysql_num_rows($result6); // obtenemos el número de filas
				
					if($result6) 
					{
						if($numero_filas > 0)
						{	// cuando no hay datos envia 0  
							$cadena = "";
							$cadenaBancos = "";
							$ivaFinal=0;
							while ($row = mysql_fetch_assoc($result6))
							{ 
								$servicios_iva=$row['productos_iva'];
								$cantidad=$row['detalle_pedido_cantidad'];
								$precio_pedido1=$row['detalle_v_unitario'];
							
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
									$total_iva = ($cantidad*$precio_pedido1 * $iva)/100;
									$total = $cantidad*$precio_pedido1 + $total_iva;
										$ivaFinal = $ivaFinal +  ($cantidad*($precio_pedido1) * $iva)/100;
								}
								else
								{
									$total = $cantidad*$precio_pedido1;
									$txtIdIva = 0;
									$total_iva = 0;
									$impuestos_id_plan_cuenta = 0;
								}
								
								$precioUnitario=$row['detalle_v_unitario']+$row['detalle_pedido_descuento'];
								
								$cadena=$cadena."*".$numero_filas."?".$row['pedidos_pedido']."?";
								$cadena=$cadena.$row['pedidos_numero_pedido']."?";
								$cadena=$cadena.$row['pedidos_fecha_pedido']."?";
								
								$cadena=$cadena.$row['clientes_cedula']."?".$row['clientes_nombre']."?";
								$cadena=$cadena.$row['clientes_apellido']."?".$row['clientes_telefono']."?";
								$cadena=$cadena.$row['detalle_pedido_id_servicio']."?".$row['servicios_nombre']."?";
								$cadena=$cadena.$row['detalle_pedido_cantidad']."?".$precioUnitario."?";
								$cadena=$cadena.$row['detalle_pedido_v_total']."?";
								$cadena=$cadena.$total_iva."?";
								$cadena=$cadena.$total."?";
								$cadena=$cadena.$iva."?";

								$cadena=$cadena.$row['productos_id_producto']."?";//16
							$cadena=$cadena.$row['sub12']."?";//17
							$cadena=$cadena.$row['sub0']."?";//18
							$cadena=$cadena.$row['sub_total']."?";//19
							
							$cadena=$cadena.$row['total']."?";//20
							


							$cadena=$cadena.$row['productos_codigo']."?";//21
							$cadena=$cadena.$row['productos_precio1']."?";//22
							$cadena=$cadena.$row['productos_tipos_compras']."?";//23
							$cadena=$cadena.$row['productos_iva']."?";//24
							
							$cadena=$cadena.$row['centro_id']."?";//25
							$cadena=$cadena.$row['productos_id_cuenta']."?";//26
							$cadena=$cadena.$row['centro_descripcion']."?";//27
							$cadena=$cadena.$row['idBodegaInventario']."?";//28
							$cadena=$cadena.$txtIdIva."?";//29
							$cadena=$cadena.$row['clientes_direccion']."?";//30
							$cadena=$cadena.$row['detalle_pedido_descuento']."?";//31
							$cadena=$cadena.$ivaFinal."?";//32
							$cadena=$cadena.$row['descuento']."?";//33
							$cadena=$cadena.$row['propina']."?";//34
							$cadena=$cadena.$row['clientes_id_cliente']."?";//35
							$cadena=$cadena.$row['pedidos_descripcion']."?";//36

							$cadena=$cadena.$row['pedidos_vendedor_id']."?";//37
						
							
								
								
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


if($txtAccion == 14)
	{

		$cmbEst = $_POST['cmbEst'];
		$cmbEmi = $_POST['cmbEmi'];
		$cmbTipoDocumentoFVC = $_POST['cmbTipoDocumentoFVC'];
		$nFactura= $_POST['txtNumeroFacturaFVC'];

		 $sql = "SELECT numero_factura_venta, Autorizacion from ventas where numero_factura_venta='".$nFactura."' and id_empresa='".$sesion_id_empresa."'
		and codigo_pun ='".$cmbEst."' and codigo_lug='".$cmbEmi."' and tipo_documento='".$cmbTipoDocumentoFVC."';";
		$resp = mysql_query($sql);
		$nFilas =  mysql_num_rows($resp);
		if($nFilas>0){
			while($row=mysql_fetch_array($resp))
			{
				$autorizacion = trim($row['Autorizacion']);
			}
			//1 no tiene autorizacion
			//2 si tiene autorizacion
			$autorizado = (is_null($autorizacion)||$autorizacion=='')?'1':'2';
			if($autorizado==1){
				$sql="SELECT numero_asiento FROM `cuentas_por_cobrar` where id_empresa='".$sesion_id_empresa."' 
				and numero_factura='".$nFactura."';";
	
	
				$numero_asiento='';
				$result=mysql_query($sql);
				while($row=mysql_fetch_array($result))
				{
					$numero_asiento=$row['numero_asiento'];
				}
	
				if(trim($numero_asiento) !=''){
					echo '4';
					exit;
				}
			}
			echo $autorizado;
		}else{
			echo '3';
		}

	}
	
if($txtAccion == 16){
    $response = array();
   
    $id_venta = $_POST['id_venta'];
    
    $sql="SELECT `id_info_adicional`, `campo`, `descripcion`, `id_venta`, `id_empresa`, `xml` FROM `info_adicional` WHERE id_venta=$id_venta";
    $result = mysql_query($sql);
    $numFilas = mysql_num_rows($result);
  $fila=1;
    if($numFilas>0){
       
        while($row = mysql_fetch_array($result) ){
            	if($row['campo']!='DIRECCION' && $row['campo']!='TELEFONO' && $row['campo']!='EMAIL'){
            	     $response['id'][$fila]         =   $row['id_info_adicional'];
             $response['campo'][$fila]         =   $row['campo'];
              $response['descripcion'][$fila]  =   $row['descripcion'];
              $fila++;
            	}
        }
    }
     $response['numFilas']=$fila;
    echo json_encode($response);
}

?>