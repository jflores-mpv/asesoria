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
//echo "accion";
//echo $txtAccion;

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
				$a=0;
				if(strlen($queryString) >0) 
				{

					$query6 = "SELECT
					compras.`id_compra` AS compras_id_compra,
					compras.`numero_factura_compra` AS compras_numero_factura_compra,
					compras.`fecha_compra` AS compras_fecha_compra,
					
					detalle_compras.`cantidad` AS detalle_compras_cantidad,
					detalle_compras.`id_producto` AS detalle_compras_id_producto,
					productos.`codigo` AS productos_codigo,
					productos.`producto` AS productos_nombre,
					
						productos.iva AS productos_iva,
					productos.`id_cuenta` AS productos_id_cuenta,
					productos.`stock` AS productos_stock,
					productos.`costo` AS productos_costo,
					
					productos.`codPrincipal` AS productos_codPrincipal,
					productos.`codAux` AS productos_codAux,
					
					
					detalle_compras.`valor_unitario` AS detalle_compras_v_unitario,
					detalle_compras.`valor_total` AS detalle_compras_v_total,
					
					detalle_compras.`des` AS detalle_compras_des,
					
					detalle_compras.`xml` AS detalle_compras_xml,
					
					proveedores.`id_proveedor` AS proveedores_id_proveedor,
					proveedores.`ruc` AS proveedores_ruc,
					proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
					proveedores.`direccion` AS proveedores_direccion,
					proveedores.`telefono` AS proveedores_telefono,
					
					compras.`autorizacion` AS compras_autorizacion,
					compras.`numSerie` AS compras_numSerie,
					compras.`caducidad` AS compras_caducidad,
					
					compras.`codSustento` AS compras_codSustento,
					compras.`txtEmision` AS compras_txtEmision,
					compras.`txtNum` AS compras_txtNum,
					
					compras.`TipoComprobante` AS compras_TipoComprobante,
					compras.`sub_total` AS 	compras_sub_total,
					compras.`subTotal0` AS compras_subTotal0,
					compras.`subTotal12` AS compras_subTotal12,
					compras.`id_iva` AS compras_iva,
					compras.`subTotal12`*compras.`id_iva`/100 AS compra_iva,
					compras.`xml` AS compra_xml,
					compras.`total` AS compra_total,
					
					compras.`descuento` AS compra_descuento,
					compras.`subTotalInvenarios` AS compra_subTotalInvenarios,
					compras.`sub_total`	 AS compra_subtotal,
					
					centro_costo.id_centro_costo as centro_costo_id,
					centro_costo.id_bodega as centro_costo_codigo,
					centro_costo.id_cuenta as centro_costo_id_cuenta,
					centro_costo.tipo as centro_costo_tipo,
					centro_costo.descripcion as centro_descripcion,
					
					tipos_compras.descripcion as tipo_descripcion,
					tipos_compras.id_tipo_cpra as id_tipo
				
					
					
					FROM `compras` compras 
					  INNER JOIN  `detalle_compras` detalle_compras
						ON compras.id_compra = detalle_compras.id_compra and  compras.`id_empresa`=detalle_compras.`id_empresa` 
					  INNER JOIN `productos` productos
						ON detalle_compras.`id_producto` = productos.`id_producto`  and productos.`id_empresa`=detalle_compras.`id_empresa`  
						INNER JOIN `proveedores` proveedores
						ON compras.`id_proveedor` = proveedores.`id_proveedor`  and proveedores.`id_empresa`=compras.`id_empresa` 
                      LEFT JOIN centro_costo centro_costo
						on detalle_compras.idBodega=centro_costo.id_centro_costo and detalle_compras.id_empresa=centro_costo.empresa
					LEFT JOIN impuestos ON impuestos.id_iva = detalle_compras.iva	
						 LEFT JOIN tipos_compras tipos_compras
						on tipos_compras.id_tipo_cpra=centro_costo.tipo 
						
					WHERE compras.`id_empresa`='".$sesion_id_empresa."' and 
					compras.`numero_factura_compra`= '".$queryString."'; "; 
          
                   
					$result6 = mysql_query($query6) or die(mysql_error());
					$numero_filas = mysql_num_rows($result6); // obtenemos el número de filas

					if($result6) 
					{
						if($numero_filas > 0)
						{	
							$cadena = "";
							//$response = [];
							$cadenaBancos = "";
							$response = [];
							$response["filas"] = [];
							while ($row = mysql_fetch_assoc($result6))
							{ 
								$productos_iva=intval($row['productos_iva']);
								$cantidad=$row['detalle_compras_cantidad'];
								$precio_venta1=$row['detalle_compras_v_unitario'];
							
								// if(is_numeric($productos_iva) ){
									$sqlIva1="Select * From impuestos where id_iva='".$productos_iva."' and id_empresa='".$sesion_id_empresa."'";
									$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
									$iva=0;
									$impuestos_id_plan_cuenta = 0;
									$row['impuestos_iva']=0;
									while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
									$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
										
									}
									$row['impuestos_iva']=$iva;
										$row['consulta']=$sqlIva1;
									$total_iva = ($cantidad*$precio_venta1 * $iva)/100;
									$total = $cantidad*$precio_venta1 + $total_iva;
								// }
								// else
								// {
								// 	$total = $cantidad*$precio_venta1;
								// 	$txtIdIva = 0;
								// 	$total_iva = 0;
								// 	$impuestos_id_plan_cuenta = 0;
								// 		$row['productos_iva']='0';
								// }
								
								
								$cadena=$cadena."*".$numero_filas."?".$row['compras_id_compra']."?";
								$cadena=$cadena.$row['compras_numero_factura_compra']."?";
								$cadena=$cadena.$row['compras_fecha_compra']."?";
								
								$cadena=$cadena.$row['proveedores_ruc']."?".$row['proveedores_nombre_comercial']."?";
								$cadena=$cadena.$row['proveedores_telefono']."?";
								$cadena=$cadena.$row['detalle_compras_id_producto']."?";
								$cadena=$cadena.$row['productos_codigo']."?".$row['productos_nombre']."?";
				
								$cadena=$cadena.$row['detalle_compras_cantidad']."?".$row['detalle_compras_v_unitario']."?";
								$cadena=$cadena.$row['detalle_compras_v_total']."?";
								$cadena=$cadena.$total_iva."?";
								$cadena=$cadena.$total."?";
								$cadena=$cadena.$iva."?";
								$cadena=$cadena.$row['compras_autorizacion']."?";
								$cadena=$cadena.$row['compras_numSerie']."?";
								$cadena=$cadena.$row['compras_caducidad']."?";
								$cadena=$cadena.$row['compras_TipoComprobante']."?";
								$cadena=$cadena.$row['compras_codSustento']."?";
								$cadena=$cadena.$row['compras_txtEmision']."?";
								$cadena=$cadena.$row['compras_txtNum']."?";
								$cadena=$cadena.$row['centro_costo_codigo']."?";
								$cadena=$cadena.$row['centro_descripcion']."?";
								$cadena=$cadena.$row['proveedores_id_proveedor']."?";
								$cadena=$cadena.$row['compras_id_compra']."?";
								$cadena=$cadena.$row['centro_costo_id_cuenta']."?";
								$cadena=$cadena.$row['centro_costo_id']."?";
								$cadena=$cadena.$row['productos_stock']."?";
								$cadena=$cadena.$row['compras_subTotal0']."?";
								$cadena=$cadena.$row['compras_subTotal12']."?";
								$cadena=$cadena.$row['compra_iva']."?";
								$cadena=$cadena.$row['compra_xml']."?";
								$cadena=$cadena.$row['compra_total']."?";
								$cadena=$cadena.$row['compra_descuento']."?";
								$cadena=$cadena.$row['compra_subTotalInvenarios']."?";
								
								$cadena=$cadena.$row['detalle_compras_des']."?";
								$cadena=$cadena.$row['compra_subtotal']."?";
							    $cadena=$cadena.$row['detalle_compras_xml']."?";
								$cadena=$cadena.$row['id_tipo']."?";
								$response["filas"][] = $row;
							}
							echo json_encode($response);
							//echo $cadenaBancos."î".$cadena;
								//echo $cadena;
						}else{
						    
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