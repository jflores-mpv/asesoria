<?php

	//require_once('../ver_sesion.php');

    date_default_timezone_set('America/Guayaquil');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

    $accion = $_POST['txtAccion'];
	$produccion= $_POST['txtProduccion'];
    $id_empresa_cookies = $_COOKIE["id_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
//	$sesion_codigo_lug = $_SESSION['sesion_codigo_lug'];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
  // echo $sesion_tipo_empresa;
if($accion == "1")
	{
	// GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
	try 
	{
		if(isset ($_POST['txtNumeroProduccion']))
		{
			$numero = $_POST['txtNumeroProduccion'];
			$sql = "SELECT numero from produccion_cabecera where numero='".$numero."' and empresa='".$sesion_id_empresa."';";
			$resp = mysql_query($sql);
			$entro=0;
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$var=$row["numero"];
			}
			if($var==$numero)
			{				   
				?> <div class="alert alert-danger"><p>Produccion ya existe 
				<?php echo "".$ex ?></p></div> 
				<?php
			}
			else
			{
				$entro=1;
			//	echo $entro;
			}
		}
	}
	catch(Exception $ex) 
	{
		?> <div class="transparent_ajax_error"><p>Error al verificar el costo
			<?php echo "".$ex ?></p></div> 
		<?php 
	}
	
	if ($entro==1)
	{
		try
		{
			$numeroProduccion=$_POST['txtNumeroProduccion'];
			$fecha= $_POST['textFechaFVC'];
			$descripcion=$_POST['txtDescripcion'];
			$mesa=$_POST['txtMesa'];
			
			$estado = "Proceso";		
			$total=$_POST['txtSubtotalFVC'];
			$cmbBodegas = trim($_POST['cmbBodegas'])==''?0:$_POST['cmbBodegas'];
			$txtContadorFilas=$_POST['txtContadorFilasFVC'];
		
			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
		
			if($numeroProduccion !=""  )
			{       
				$sql="insert into produccion_cabecera (numero, fecha,           estado,    total,    descripcion,   empresa,mesa,id_bodega) 
				      values ('".$numeroProduccion."','".$fecha."','".$estado."','".$total."','".$txtDescripcion."','".$sesion_id_empresa."','".$mesa."','".$cmbBodegas."');";
				      
				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en produccion: '.mysql_error().' </p></div>  ');
				$id_produccion=mysql_insert_id();
				

    //             $sqlUpdate="update mesas set Estado='1' where id_empresa='".$sesion_id_empresa."' and num='".$mesa."' ";
				// $resultupdate=mysql_query($sqlUpdate) or die('<div class="transparent_ajax_error"><p>Error al actualizar Mesa: '.mysql_error().' </p></div>  ');

				for($i=1; $i<=$txtContadorFilas; $i++)
				{
					if($_POST['txtIdServicio'.$i] >= 1)
					{ //verifica si en el campo esta agregada una cuenta
						//permite sacar el id maximo de detalle_libro_diario

						$cantidad = $_POST['txtCantidadS'.$i];
						$idServicio = $_POST['txtIdServicio'.$i];
						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						$valorTotal = $_POST['txtValorTotalS'.$i];
						$idCosto = $_POST['txtIdCostoS'.$i];
						$txtDescripcionDetalle = $_POST['txtDescripcionDetalle'.$i];
						$txtLote  = trim($_POST['txtLoteS'.$i])==''?0:$_POST['txtLoteS'.$i];
						$txtBodegas  = trim($_POST['txtBodegasS'.$i])==''?0:$_POST['txtBodegasS'.$i];
						//GUARDA EN EL DETALLE VENTAS
						$sql2 = "insert into produccion_detalle (id_produccion, id_producto, cantidad, costo, total, id_costo,id_lote,id_bodega) values
						('".$id_produccion."', '".$idServicio."','".$cantidad."','".$valorUnitario."','".$valorTotal."','".$idCosto."','".$txtLote."','".$txtBodegas."');";
						$id_detalle_produccion=mysql_insert_id();
						
						$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles produccion: '.mysql_error().' </p></div>  ');
						
						$sqlObser = "insert into detalle_pedidos (id_detalle, descri) values('".$id_detalle_produccion."','".$txtDescripcionDetalle."');";
						
						$respobser = mysql_query($sqlObser) or die('<div class="transparent_ajax_error"><p>Error al guardar en observaciones: '.mysql_error().' </p></div>  ');
							
						//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************					
					}
				}// fin del bucle que pasa por todas las filas
				
				if($result && $resp2 && $respobser)
				{
					?> <div class='alert alert-success'><p>Registro guardado correctamente.</p></div> <?php
				}
				else
				{
					?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Revise que haya ingresado todos los datos correctamente. <?php echo " ".mysql_error(); ?>;</p></div> <?php
				}
				
				// if ($sesion_tipo_empresa=="6")
				// { //aqui1
					//permite sacar el numero_asiento de libro_diario
			        $tot_costo=0;
					try
					{
					  $sqlMNA="SELECT
						max(numero_asiento) AS max_numero_asiento,
						periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
						periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
						periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
						periodo_contable.`estado` AS periodo_contable_estado,
						periodo_contable.`ingresos` AS periodo_contable_ingresos,
						periodo_contable.`gastos` AS periodo_contable_gastos,
						periodo_contable.`id_empresa` AS periodo_contable_id_empresa
					  FROM
						 `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
						 WHERE periodo_contable.`id_empresa` ='".$sesion_id_empresa."' GROUP BY periodo_contable.`id_periodo_contable` ;";
						$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error5: '.mysql_error().' </p></div>  ');
						$numero_asiento=0;
						while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
						{
							$numero_asiento=$rowMNA['max_numero_asiento'];
						}
						$numero_asiento++;
					}
					 catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
					
				//Fin permite sacar el numero_asiento de libro_diario
				
				/* GUARDAR ASIENTO CONTABLE */
				
					//permite sacar el id maximo de libro_diario
					try
					{
						$sqlm="Select max(id_libro_diario) From libro_diario";
						$resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error6: '.mysql_error().' </p></div>  ');
						$id_libro_diario=0;
						while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
						{
							$id_libro_diario=$rowm['max(id_libro_diario)'];
						}
						$id_libro_diario++;

					}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
				
					//Fin permite sacar el id maximo de libro_diario
		
					$tipo_comprobante = "Diario"; 
				// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
					try
					{
						$tipoComprobante = $tipo_comprobante;
						$consulta7="SELECT
							max(numero_comprobante) AS max_numero_comprobante
						FROM
							`comprobantes` comprobantes
							WHERE comprobantes.`id_empresa` = '".$sesion_id_empresa."' AND  comprobantes.`tipo_comprobante` = '".$tipoComprobante."' ;";
								$result7=mysql_query($consulta7) or die('<div class="transparent_ajax_error"><p>Error1: '.mysql_error().' </p></div>  ');
								$numero_comprobante = 0;
							while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
							{
								$numero_comprobante=$row7['max_numero_comprobante'];
							}
							$numero_comprobante ++;
					}
					catch (Exception $e) 
					{
						// Error en algun momento.
					   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
					}
				// FIN DE SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
				
				//SACA EL ID MAX DE COMPROBANTES
					try
					{
						$sqlCM="Select max(id_comprobante) From comprobantes; ";
						$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error2: '.mysql_error().' </p></div>  ');
						$id_comprobante=0;
						while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
						{
							$id_comprobante=$rowCM['max(id_comprobante)'];
						}
						$id_comprobante++;

					}	catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
							  
				//FIN SACA EL ID MAX DE COMPROBANTES
		
					$fecha= date("Y-m-d h:i:s");
				// 	$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
					$descripcion="Orden de Produccion #".$numeroProduccion." realizado por ".$sesion_empresa_nombre;
					
					$total_debe = $total;
					$total_haber = $total;

					$tipo_mov="P";
	
				//GUARDA EN  COMPROBANTES
					$sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error A: comprobantes, '.mysql_error().' </p></div>  ');
					
				//GUARDA EN EL LIBRO DIARIO
					$sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha, total_debe,
					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."',
					'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					'".$id_comprobante."','".$tipo_mov."','".$numeroProduccion."' )";
				
					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error B: LIBRO DIARIO, '.mysql_error().' </p></div>  ');

					$idPlanCuentas[1] = '';
					$idPlanCuentas[2] = '';
					$debeVector[1] = 0;
					$debeVector[2] = 0;
					$haberVector[1] = 0;
					$haberVector[2] = 0;
					$lin_diario=0;
					
					try 
					{
						$sql="SELECT tipo_cpra,cuenta_contable FROM enlaces_compras 
						WHERE (`tipo_cpra` = 12 or `tipo_cpra` = 9) and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
						$result=mysql_query($sql);
						$idcodigo_inventario=0;
						$idcodigo_proceso=0;
						while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
						{
							if ($row['tipo_cpra']==12)
							{
								$idcodigo_inventario=$row['cuenta_contable'];	
							}
							else
							{
								$idcodigo_proceso=$row['cuenta_contable'];	
							}
						}
						//$idcodigo_inv;
					
					}catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
					
					if ($total!=0)
					{
						$lin_diario=$lin_diario+1;
						$idPlanCuentas[$lin_diario]=$idcodigo_proceso;
						$debeVector[$lin_diario]=$total;
						$haberVector[$lin_diario]=0;					
					
						$lin_diario=$lin_diario+1;
						$idPlanCuentas[$lin_diario]= $idcodigo_inventario;
						$debeVector[$lin_diario]=0;
						$haberVector[$lin_diario]=$total;
					}
												 
					for($i=1; $i<=$lin_diario; $i++)
					{
						if ($idPlanCuentas[$i] !="")
						{
						//permite sacar el id maximo de detalle_libro_diario
							try 
							{
								$sqli="Select max(id_detalle_libro_diario) From detalle_libro_diario";
								$result=mysql_query($sqli);
								$id_detalle_libro_diario=0;
								while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
								{
									$id_detalle_libro_diario=$row['max(id_detalle_libro_diario)'];
								}
								$id_detalle_libro_diario++;
							}
							catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

						//GUARDA EN EL DETALLE LIBRO DIARIO
							$sqlDLD = "insert into detalle_libro_diario (id_detalle_libro_diario, id_libro_diario, 
							id_plan_cuenta,debe, haber, id_periodo_contable) values 
							('".$id_detalle_libro_diario."','".$id_libro_diario."',
							'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
							'".$sesion_id_periodo_contable."');";
							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error7: '.mysql_error().' </p></div>  ');
									
							// CONSULTAS PARA GENERAR LA MAYORIZACION
							$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
							$result5=mysql_query($sql5);
							while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
							{
								$id_mayorizacion=$row5['id_mayorizacion'];
							}
							$numero = mysql_num_rows($result5); // obtenemos el número de filas
							if($numero > 0)
							{
								 // si hay filas
							}
							else 
							{
								// no hay filas
								//INSERCION DE LA TABLA MAYORIZACION
								try 
								{
									//permite sacar el id maximo de la tabla mayorizacion
									$sqli6="Select max(id_mayorizacion) From mayorizacion";
									$resulti6=mysql_query($sqli6);
									$id_mayorizacion=0;
									while($row6=mysql_fetch_array($resulti6))//permite ir de fila en fila de la tabla
									{
										$id_mayorizacion=$row6['max(id_mayorizacion)'];
									}
									$id_mayorizacion++;

									$sql6 = "insert into mayorizacion (id_mayorizacion, id_plan_cuenta, id_periodo_contable) values ('".$id_mayorizacion."','".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
									$result6=mysql_query($sql6);
								}
								catch(Exception $ex) 
								{ ?> <div class="transparent_ajax_error">
									<p>Error en la insercion de la tabla mayorizacion: 
									<?php echo "".$ex ?></p></div> <?php }
								// FIN DE MAYORIZACION
							}
						}
					}

			
				// } // aqui1
				
				//echo "Fin de ingreso de asiento";
					$fechahoy= date('Y-m-d');
			// Actualizar el inventario
				for($i=1; $i<=$txtContadorFilas; $i++)
				{
					//echo "va actualizar inventario";
					if($_POST['txtIdServicio'.$i] >= 1)
					{ 
						$idProductoProd=$_POST['txtIdServicio'.$i];
						$idCosto=$_POST['txtIdCostoS'.$i];
						$cant_prod = $_POST['txtCantidadS'.$i];
						$codProductoProd = $_POST['txtCodigoServicio'.$i];
						$id_lote_Prod = $_POST['txtLoteS'.$i];
						$id_bodega_Prod = $_POST['txtBodegasS'.$i];
						
							if($id_lote_Prod !=0 && $id_lote_Prod !=''){
								$sqlLote="SELECT `id_lote`, `numero_lote`, `fecha_elaboracion`, `fecha_caducidad`, `calidad_lote`, `estado_lote`, `fecha_registro`, `detalle`, `id_empresa` FROM `lotes` WHERE id_lote=$id_lote_Prod";
								$resultLote = mysql_query($sqlLote);
								$estado_lote_anterior='';
								while($rl = mysql_fetch_array($resultLote) ){
								    $estado_lote_anterior= $rl['estado_lote'];
								}

								$sqlUpdateLote= "UPDATE `lotes` SET `tipo_origen_lote`='1',estado_lote=3,`id_origen_lote`='".$id_produccion."' WHERE id_lote=$id_lote_Prod";
								$resultUpdateLote = mysql_query($sqlUpdateLote);
								
								$sqlHistorialLote ="INSERT INTO `historial_estados_lote`( `id_estado`, `id_lote`, `fecha_estado`, `id_estado_anterior`) VALUES ('3','".$id_lote_Prod."','".$fechahoy."','".$estado_lote_anterior."')";
								$resultHistorialLote = mysql_query( $sqlHistorialLote );
                               
							}
						 $sql="SELECT b.id_producto,b.cantidad,b.tipo_costo, productos.codigo ,productos.producto, a.id_lote
						FROM produccion_detalle a 
						INNER JOIN costos_detalle b ON a.id_costo = b.id_costo
						INNER JOIN productos ON productos.id_producto = b.id_producto
						WHERE id_produccion =" .$id_produccion. " and idProdProduccion=".$idProductoProd." and b.id_costo=".$idCosto ;
				
						$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error8: '.mysql_error().' </p></div>  ');;
						$idProducto_costo=0;
						$cantidad=0;
						$cmbBodegas = $_POST['cmbBodegas'];
						while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
						{
							$idProducto_costo=$row['id_producto'];
							$cantidad=$row['cantidad'];
							$tipo_costo=$row['tipo_costo'];
							$codigoProducto = $row['codigo'];
							$id_lote = $row['id_lote'];
							$txtBodegasS = $row['txtBodegasS'];
							
						
							if ($tipo_costo=="D")
							{
								
								  $sql1="SELECT cantBodegas.`id`, cantBodegas.`idBodega`, cantBodegas.`idProducto`, cantBodegas.`cantidad`, cantBodegas.`proceso`,cantBodegas.id_lote 
								FROM `cantBodegas` 
								INNER JOIN bodegas on bodegas.id = cantBodegas.idBodega 
								WHERE idProducto = '".$codigoProducto."' AND bodegas.id_empresa='".$sesion_id_empresa."' Order by cantBodegas.cantidad desc;";
								
								$resultado1=mysql_query($sql1) or die('<div class="transparent_ajax_error"><p>Error9: '.mysql_error().' </p></div>  ');;
								$stock1=0;
								$proceso1=0;
								while($row1=mysql_fetch_array($resultado1))
								{
									$stock1=$row1['cantidad'];
									$proceso1=$row1['proceso'];
									$bodega=$row1['idBodega'];
								}


								$cant_total=$cant_prod * $cantidad;
                                if($bodega!=''){
                                    	  $sql2="UPDATE `cantBodegas` SET `cantidad`=cantidad-$cant_total,`proceso`=proceso+$cant_total WHERE  idProducto='".$codigoProducto."' and  idBodega=$bodega " ;
                                }else{
                                    
                                    	  $sql2="INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`, `proceso`) VALUES ('".$cmbBodegas."','".$codigoProducto."','0','".$cant_total."') " ;
                                }
							
								
								$result2=mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error10: '.mysql_error().' </p></div>  ');					
							}
						}

						$sql1="SELECT cantBodegas.`id`, cantBodegas.`idBodega`, cantBodegas.`idProducto`, cantBodegas.`cantidad`, cantBodegas.`proceso` 
						FROM `cantBodegas`
						INNER JOIN bodegas on bodegas.id = cantBodegas.idBodega 
                        WHERE cantBodegas.idProducto = '".$codProductoProd."' AND cantBodegas.id_lote=$id_lote_Prod  AND bodegas.id_empresa='".$sesion_id_empresa."'  AND bodegas.id='".$id_bodega_Prod."'  ";
                        
						$resultado1=mysql_query($sql1) or die('<div class="transparent_ajax_error"><p>Error9: '.mysql_error().' </p></div>  ');;
						$stock1=0;
						$proceso1=0;
						
						while($row1=mysql_fetch_array($resultado1))
						{
							$stock1=$row1['cantidad'];
							$proceso1=$row1['proceso'];
							$bodegaProd=$row1['idBodega'];
						}
						
						$fila=mysql_num_rows($resultado1);
		                if ($fila>0){
		  
		  
						     $sql3="UPDATE `cantBodegas` SET `proceso`=proceso+$cant_prod WHERE  idProducto='".$codProductoProd."' and  idBodega=$id_bodega_Prod  and id_lote=$id_lote_Prod " ;

						    $result3=mysql_query($sql3) or die('<div class="transparent_ajax_error"><p>Error C: '.mysql_error().' </p></div>  ');
						    
						}else{

						    $sqlbodegas="INSERT INTO `cantBodegas`(`idBodega`, `idProducto`, `cantidad`,proceso,id_lote) 
                            VALUES ('".$id_bodega_Prod."','".$codProductoProd."',0,'".$cant_prod."','".$id_lote_Prod."')";
                            $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
						}
						
						


					}
				}	
			}
			else
			{
				?> <div class='transparent_ajax_error'><p>Error: No ha ingresado suficiente datos de factura <?php echo " ".mysql_error(); ?>;</p></div> <?php
			}
		}
		catch (Exception $e) 
		{
			// Error en algun momento.
			   ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
		}		
	}	
}
if($accion == "4")
{
    // Is there a posted query string?
	if(isset($_POST['queryString'])) 
	{
		$queryString = $_POST['queryString'];
		$cont = $_POST['cont'];

        // Is the string length greater than 0?
        if(strlen($queryString) >0) 
		{        
		$query = "SELECT
            productos.`id_producto` AS productos_id_producto,
            productos.`codigo` AS productos_codigo,
			productos.`producto` AS productos_nombre,
            productos.`costo` AS productos_costo,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`id_cuenta` AS productos_id_cuenta,
			categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
			productos.`id_empresa` AS productos_id_empresa,
			costos_cabecera.`id_costo` AS costos_cabecera_id_costo,
			costos_cabecera.`total_cd` AS costos_cabecera_total_cd,
			costos_cabecera.`total_ci` AS costos_cabecera_total_ci
			
	    FROM
            `categorias`categorias INNER JOIN `productos` productos 
			ON categorias.`id_categoria` = productos.`id_categoria`
			INNER JOIN `costos_cabecera` costos_cabecera 
			ON productos.`id_producto` = costos_cabecera.`producto` and 
			productos.`id_empresa` = costos_cabecera.`empresa`
            WHERE productos.`id_empresa`='".$sesion_id_empresa."' and 
			produccion ='".$produccion."' AND CONCAT(productos.`id_producto`, productos.`producto`) LIKE '%$queryString%'  LIMIT 20; ";
            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) 
			{
                if($numero_filas ==0)
				{
                    echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
                }
				else
				{
                    echo "<table class='table table-bordered table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>IdProducto</th><th>Código</th><th>Nombre</th><th >Total costos directos</th>  <th>Total costos directos</th><th>Id Costo</th><th ><a href='javascript: fn_cerrar_div();'><img align='right' src='images/cerrar2.png' width='16' height='16' alt='cerrar' title='Cerrar' /></a></th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysql_fetch_array($result))
					{
						echo '<tr onClick="fill_produccion(\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["productos_id_producto"]."*".$row["productos_codigo"]."*".$row["productos_nombre"]."*".$row["costos_cabecera_total_cd"]."*".$row["costos_cabecera_total_ci"]."*".$row["productos_id_cuenta"]."*".$row["costos_cabecera_id_costo"].'\');"    style="cursor: pointer" title="Clic para seleccionar">';
                        echo "<td>".$row["productos_id_producto"]."</td>";
						echo "<td>".$row["productos_codigo"]."</td>";
					    echo "<td>".$row["productos_nombre"]."</td>";
						echo "<td>".$row["costos_cabecera_total_cd"]."</td>";
						echo "<td>".$row["costos_cabecera_total_ci"]."</td>";
		//				echo "<td>".$row["productos_id_cuenta"]."</td>";
						echo "<td>".$row["costos_cabecera_id_costo"]."</td>";
					
						echo "</tr>";
                    }
                    echo "</tbody>";
                    echo"</table>";
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
			}
    } 
	else 
		{
            echo 'No hay ningún acceso directo a este script!';
		}
}


if($accion == "8")

{
		//BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
	try
	{
		// esta pagina retorna en la pagina: asientosContables.php 
    //echo "estoy en sql opcion 8";
		if(isset($_POST['queryString'])) 
		{ 
			$queryString = $_POST['queryString'];		
			$a=0;
			if(strlen($queryString) >0) 
			{
				$query6 = "SELECT
					a.`id_produccion` AS ventas_id_venta,a.`numero` AS ventas_numero_factura_venta,
					a.`fecha`  AS ventas_fecha_venta, a.`descripcion` AS produccion_cabecera_descripcion,
					b.`id_producto` AS produccion_detalle_id_producto,
					a.`id_bodega` AS produccion_bodega,
					b.`id_lote` AS produccion_detalle_id_lote,
					d.`numero_lote` AS produccion_detalle_numero_lote,
					c.`codigo` AS producto_codigo,c.`producto` AS servicios_nombre,
					b.`cantidad` AS detalle_ventas_cantidad, 
					b.`costo` AS detalle_v_unitario, b.`total` AS detalle_ventas_v_total,
					b.`id_costo` AS detalle_id_costo	
					FROM `produccion_cabecera` a 
					    INNER JOIN  `produccion_detalle` b
						ON a.id_produccion = b.id_produccion 
						INNER JOIN `productos` c
						ON b.`id_producto` = c.`id_producto` and c.`id_empresa`=a.`empresa`
						LEFT JOIN  `lotes` d
						ON d.id_lote = b.id_lote 
						WHERE a.`empresa`='".$sesion_id_empresa."' and a.`numero`= '".$queryString."'; "; 
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
								$cantidad=$row['detalle_ventas_cantidad'];
								$precio_venta1=$row['detalle_v_unitario'];
								$total = $cantidad*$precio_venta1;
								
								$cadena=$cadena."*".$numero_filas."?".$row['ventas_id_venta']."?";
								$cadena=$cadena.$row['ventas_numero_factura_venta']."?";
								$cadena=$cadena.$row['ventas_fecha_venta']."?";
								$cadena=$cadena.$row['produccion_cabecera_descripcion']."?";
								
								$cadena=$cadena.$row['produccion_detalle_id_producto']."?";
								$cadena=$cadena.$row['producto_codigo']."?".$row['servicios_nombre']."?";
								$cadena=$cadena.$row['detalle_ventas_cantidad']."?".$row['detalle_v_unitario']."?";
								$cadena=$cadena.$row['detalle_ventas_v_total']."?";
								$cadena=$cadena.$row['detalle_id_costo']."?";
								$cadena=$cadena.$row['produccion_detalle_id_lote']."?";
								$cadena=$cadena.$row['produccion_bodega']."?";
								$cadena=$cadena.$row['produccion_detalle_numero_lote']."?";
							//	$cadena=$cadena.$row['servicios_id_servicio']."?";
							}
							echo $cadenaBancos."î".$cadena;
							//	echo $cadena;
						}
						else
						{
							$cadena = "";
							$cadena=$cadena."*"."1"."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
							$cadena=$cadena."*"."2"."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
							$cadena=$cadena."*"."3"."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
							$cadena=$cadena."*"."4"."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
		 
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

		
	if($accion == "5")
{
	// GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
	try 
	{
		if(isset ($_POST['txtNumeroProduccion']))
		{
			$numeroProduccion = $_POST['txtNumeroProduccion'];
			$sql = "SELECT numero, estado,id_produccion from produccion_cabecera where numero='".$numeroProduccion."' and empresa='".$sesion_id_empresa."';";
			$resp = mysql_query($sql);
			$entro=0;
			$estado="";
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$var=$row["numero"];
				$estado=$row["estado"];
				$id_produccion=$row["id_produccion"];
			}
		//	echo "estado=".$estado;
			if($var==$numeroProduccion)
			{	
				if ($estado=='Proceso')
				{
					$entro=1;
				}
				else
				{
					if ($estado=='Terminado')
					{
					?> <div class="alert alert-danger"><p>No. de Produccion ya se paso a  inventario  
					<?php echo "".$ex ?></p></div> 
					<?php
					}					
				}
			//	echo $entro;

			}
			else
			{
				
				?> <div class="alert alert-danger"><p>Produccion no se ha grabado previamente 
				<?php echo "".$ex ?></p></div> 
				<?php
				
			}
		}
	}
	catch(Exception $ex) 
	{
		?> <div class="transparent_ajax_error"><p>Error al verificar la produccion
			<?php echo "".$ex ?></p></div> 
		<?php 
	}
	
	if ($entro==1)
	{
		try
		{
			$numeroProduccion=$_POST['txtNumeroProduccion'];
			$fecha= $_POST['textFechaFVC'];
			$descripcion=$_POST['txtDescripcion'];
			
			$total=$_POST['txtSubtotalFVC'];
			$cmbBodegas=trim($_POST['cmbBodegas'])==''?0:$_POST['cmbBodegas'];
		
			$txtContadorFilas=$_POST['txtContadorFilasFVC'];
			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
		
			if($numeroProduccion !=""  )
			{ 
				// if ($sesion_tipo_empresa=="6")
				// { //aqui2
					//permite sacar el numero_asiento de libro_diario
			        $tot_costo=0;
					try
					{
					  $sqlMNA="SELECT
						max(numero_asiento) AS max_numero_asiento,
						periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
						periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
						periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
						periodo_contable.`estado` AS periodo_contable_estado,
						periodo_contable.`ingresos` AS periodo_contable_ingresos,
						periodo_contable.`gastos` AS periodo_contable_gastos,
						periodo_contable.`id_empresa` AS periodo_contable_id_empresa
					  FROM
						 `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
						 WHERE periodo_contable.`id_empresa` ='".$sesion_id_empresa."' GROUP BY periodo_contable.`id_periodo_contable` ;";
						$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error D: '.mysql_error().' </p></div>  ');
						$numero_asiento=0;
						while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
						{
							$numero_asiento=$rowMNA['max_numero_asiento'];
						}
						$numero_asiento++;
					}
					 catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
					
				//Fin permite sacar el numero_asiento de libro_diario
				
				/* GUARDAR ASIENTO CONTABLE */
				
					//permite sacar el id maximo de libro_diario
					try
					{
						$sqlm="Select max(id_libro_diario) From libro_diario";
						$resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error E: '.mysql_error().' </p></div>  ');
						$id_libro_diario=0;
						while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
						{
							$id_libro_diario=$rowm['max(id_libro_diario)'];
						}
						$id_libro_diario++;

					}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
				
					//Fin permite sacar el id maximo de libro_diario
		
					$tipo_comprobante = "Diario"; 
				// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
					try
					{
						$tipoComprobante = $tipo_comprobante;
						$consulta7="SELECT
							max(numero_comprobante) AS max_numero_comprobante
						FROM
							`comprobantes` comprobantes
							WHERE comprobantes.`id_empresa` = '".$sesion_id_empresa."' AND  comprobantes.`tipo_comprobante` = '".$tipoComprobante."' ;";
								$result7=mysql_query($consulta7) or die('<div class="transparent_ajax_error"><p>Error F: '.mysql_error().' </p></div>  ');
								$numero_comprobante = 0;
							while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
							{
								$numero_comprobante=$row7['max_numero_comprobante'];
							}
							$numero_comprobante ++;
					}
					catch (Exception $e) 
					{
						// Error en algun momento.
					   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
					}
				// FIN DE SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
				
				//SACA EL ID MAX DE COMPROBANTES
					try
					{
						$sqlCM="Select max(id_comprobante) From comprobantes; ";
						$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error G: '.mysql_error().' </p></div>  ');
						$id_comprobante=0;
						while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
						{
							$id_comprobante=$rowCM['max(id_comprobante)'];
						}
						$id_comprobante++;

					}	catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
							  
				//FIN SACA EL ID MAX DE COMPROBANTES
		
					$fecha= date("Y-m-d h:i:s");
					$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
					$descripcion="Orden de Produccion #".$numeroProduccion." realizado por ".$sesion_empresa_nombre;
					
					$total_debe = $total;
					$total_haber = $total;

					$tipo_mov="T";
	
				//GUARDA EN  COMPROBANTES
					$sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error H: comprobantes, '.mysql_error().' </p></div>  ');
					
				//GUARDA EN EL LIBRO DIARIO
					$sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha, total_debe,
					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."',
					'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					'".$id_comprobante."','".$tipo_mov."','".$numeroProduccion."' )";
				
					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error I: LIBRO DIARIO, '.mysql_error().' </p></div>  ');

					$idPlanCuentas[1] = '';
					$idPlanCuentas[2] = '';
					$debeVector[1] = 0;
					$debeVector[2] = 0;
					$haberVector[1] = 0;
					$haberVector[2] = 0;
					$lin_diario=0;
					
					try 
					{
						$sql="SELECT tipo_cpra,cuenta_contable FROM enlaces_compras 
						WHERE (`tipo_cpra` = 5 or `tipo_cpra` = 9) and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
						$result=mysql_query($sql);
						$idcodigo_inventario=0;
						$idcodigo_proceso=0;
						while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
						{
							if ($row['tipo_cpra']==5)
							{
								$idcodigo_inventario=$row['cuenta_contable'];	
							}
							else
							{
								$idcodigo_proceso=$row['cuenta_contable'];	
							}
						}
						//$idcodigo_inv;
					
					}catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
					
					if ($total!=0)
					{
						$lin_diario=$lin_diario+1;
						$idPlanCuentas[$lin_diario]= $idcodigo_inventario;
						$debeVector[$lin_diario]=$total;
						$haberVector[$lin_diario]=0;					
					
						$lin_diario=$lin_diario+1;
						$idPlanCuentas[$lin_diario]=$idcodigo_proceso;
						$debeVector[$lin_diario]=0;
						$haberVector[$lin_diario]=$total;
					}
												 
					for($i=1; $i<=$lin_diario; $i++)
					{
						if ($idPlanCuentas[$i] !="")
						{
						//permite sacar el id maximo de detalle_libro_diario
							try 
							{
								$sqli="Select max(id_detalle_libro_diario) From detalle_libro_diario";
								$result=mysql_query($sqli);
								$id_detalle_libro_diario=0;
								while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
								{
									$id_detalle_libro_diario=$row['max(id_detalle_libro_diario)'];
								}
								$id_detalle_libro_diario++;
							}
							catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

						//GUARDA EN EL DETALLE LIBRO DIARIO
							$sqlDLD = "insert into detalle_libro_diario (id_detalle_libro_diario, id_libro_diario, 
							id_plan_cuenta,debe, haber, id_periodo_contable) values 
							('".$id_detalle_libro_diario."','".$id_libro_diario."',
							'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
							'".$sesion_id_periodo_contable."');";
							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error J: '.mysql_error().' </p></div>  ');
									
							// CONSULTAS PARA GENERAR LA MAYORIZACION
							$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
							$result5=mysql_query($sql5);
							while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
							{
								$id_mayorizacion=$row5['id_mayorizacion'];
							}
							$numero = mysql_num_rows($result5); // obtenemos el número de filas
							if($numero > 0)
							{
								 // si hay filas
							}
							else 
							{
								// no hay filas
								//INSERCION DE LA TABLA MAYORIZACION
								try 
								{
									//permite sacar el id maximo de la tabla mayorizacion
									$sqli6="Select max(id_mayorizacion) From mayorizacion";
									$resulti6=mysql_query($sqli6);
									$id_mayorizacion=0;
									while($row6=mysql_fetch_array($resulti6))//permite ir de fila en fila de la tabla
									{
										$id_mayorizacion=$row6['max(id_mayorizacion)'];
									}
									$id_mayorizacion++;

									$sql6 = "insert into mayorizacion (id_mayorizacion, id_plan_cuenta, id_periodo_contable) values ('".$id_mayorizacion."','".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
									$result6=mysql_query($sql6);
								}
								catch(Exception $ex) 
								{ ?> <div class="transparent_ajax_error">
									<p>Error en la insercion de la tabla mayorizacion: 
									<?php echo "".$ex ?></p></div> <?php }
								// FIN DE MAYORIZACION
							}
						}
					}
				// }  //aqui2
				
				//echo "Fin de ingreso de asiento";
			
			// Actualizar el inventario
				for($i=1; $i<=$txtContadorFilas; $i++)
				{
					//echo "idServicio".$_POST['txtIdServicio'.$i];
					if($_POST['txtIdServicio'.$i] >= 1)
					{ 									
					    $idProductoProd=$_POST['txtIdServicio'.$i];
						$codProductoProd = $_POST['txtCodigoServicio'.$i];
						$idCosto=$_POST['txtIdCostoS'.$i];
						$cant_prod = $_POST['txtCantidadS'.$i];
						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						$txtLoteS = $_POST['txtLoteS'.$i];
										
				$sql="SELECT b.id_producto,b.cantidad,b.tipo_costo, productos.codigo ,productos.producto 
				FROM produccion_detalle a 
				INNER JOIN costos_detalle b ON a.id_costo = b.id_costo
				INNER JOIN productos ON productos.id_producto = b.id_producto
				WHERE id_produccion=".$id_produccion." and idProdProduccion=".$idProductoProd." and b.id_costo=".$idCosto ;
								
						$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error K: '.mysql_error().' </p></div>  ');;
						$idProducto_costo=0;
						$cantidad=0;
						while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
						{
							$idProducto_costo=$row['id_producto'];
							$cantidad=$row['cantidad'];
							$tipo_costo=$row['tipo_costo'];
							$codigoProducto = $row['codigo'];
						
							if ($tipo_costo=="D")
							{
								 $sql1="SELECT cantBodegas.`id`, cantBodegas.`idBodega`, cantBodegas.`idProducto`, cantBodegas.`cantidad`, cantBodegas.`proceso` 
								 FROM `cantBodegas`
								 INNER JOIN bodegas on bodegas.id = cantBodegas.idBodega 
								 	WHERE cantBodegas.idProducto = '".$codigoProducto."' AND bodegas.id_empresa='".$sesion_id_empresa."'
								 ORDER BY cantBodegas.cantidad DESC LIMIT 1 " ;
								$resultado1=mysql_query($sql1) or die('<div class="transparent_ajax_error"><p>Error9: '.mysql_error().' </p></div>  ');;
								$stock1=0;
								$proceso1=0;
								while($row1=mysql_fetch_array($resultado1))
								{
									$stock1=$row1['cantidad'];
									$proceso1=$row1['proceso'];
									$bodega=$row1['idBodega'];
								}

								$cant_total=$cant_prod * $cantidad;


								  $sql2="UPDATE `cantBodegas` SET `proceso`=proceso-$cant_total WHERE  idProducto='".$codigoProducto."' and  idBodega=$bodega  " ;

								$result2=mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error L: '.mysql_error().' </p></div>  ');;		
							}
							
						}
						
						 $sql1="SELECT `id`, `idBodega`, `idProducto`, `cantidad`, `proceso`, id_lote FROM `cantBodegas` WHERE idProducto = '".$codProductoProd."' and id_lote='".$txtLoteS."' and idBodega='".$cmbBodegas."' ORDER BY cantidad DESC LIMIT 1 " ;
						$resultado1=mysql_query($sql1) or die('<div class="transparent_ajax_error"><p>Error9: '.mysql_error().' </p></div>  ');;
						$stock1=0;
						$proceso1=0;
						$bodegaProd='';
						while($row1=mysql_fetch_array($resultado1))
						{
							$stock1=$row1['cantidad'];
							$proceso1=$row1['proceso'];
							$bodegaProd=$row1['idBodega'];
						}
						
							$sql2="update productos set stock=stock+'".$cant_prod."', costo='".$valorUnitario."' where id_producto=".$idProductoProd. " and id_empresa=".$sesion_id_empresa ;

						$result2=mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error M: '.mysql_error().' </p></div>  ');
						
						// ,`proceso`=proceso-	$cant_prod 
						if(trim($bodegaProd)!=''){
                                    	$sql3="UPDATE `cantBodegas` SET proceso=proceso-$cant_prod , `cantidad`=cantidad+$cant_prod WHERE  idProducto='".$codProductoProd."' and  idBodega=$cmbBodegas  and  id_lote=$txtLoteS " ;
                        }else{
                             
                                    	 $sql3="INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`, `proceso`,id_lote) VALUES ('".$cmbBodegas."','".$codProductoProd."','".$cant_prod."','0','".$txtLoteS."') " ;
                        }
                                
					 

						$result3=mysql_query($sql3) or die('<div class="transparent_ajax_error"><p>Error N: '.mysql_error().' </p></div>  ');

					}	
				}
				
				$sqlki="Select max(id_kardes) From kardes";
						$resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
						$id_kardes='0';
						while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
						{
							$id_kardes=$rowki['max(id_kardes)'];
						}
						$id_kardes++;
						$sqlk="insert into kardes (id_kardes, fecha, detalle,  id_factura,id_empresa) values
						('".$id_kardes."','".$fecha."','Terminados','".$numeroProduccion."', '".$sesion_id_empresa."')";
				//		echo $sqlk;
						$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());						
				
				$sql="update produccion_cabecera set estado='Terminado' where numero=".$numeroProduccion." and empresa='".$sesion_id_empresa."'" ;
				//echo $sql;
				$resultado=mysql_query($sql) or die("\nError al actualizar estado de No. produccion: ".mysql_error());
				if($resultado)
				{
					?> <div class='alert alert-success'><p>Fin de proceso de pasar a inventario.</p></div> <?php
				}	
			}
			else
			{
				?> <div class='transparent_ajax_error'><p>Error: No ha ingresado suficiente datos de factura <?php echo " ".mysql_error(); ?>;</p></div> <?php
			}
		}
		catch (Exception $e) 
		{
			// Error en algun momento.
			   ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
		}		
	}	
}
if($accion == "9")
{

	$valor = $_POST['valor'];
	$tipo = $_POST['selecion'];
	$nombreTipo='';
	$vM=0;
	$vI=0;
	//1 inventario
	//2 mercaderia
	if($tipo=='1'){
		$nombreTipo='area_inventario_proceso';
		$vI= $_POST['valor'];
	}else{
		$nombreTipo='area_mercaderia_terminada';
		$vM= $_POST['valor'];
	}
	

	$sqlExiste="SELECT `id_produccion`, `area_mercaderia_terminada`, `area_inventario_proceso` FROM `parametros_produccion`  WHERE id_empresa=$sesion_id_empresa  ";
	$resultExiste = mysql_query($sqlExiste);
	$numFilasExiste = mysql_num_rows($resultExiste);
	$respuesta = '';

	if($numFilasExiste>0){
		$respuesta = 'Se actualizo correctamente';
		$sql="UPDATE `parametros_produccion` SET  ".$nombreTipo." = ".$valor." WHERE  id_empresa=$sesion_id_empresa";
	}else{
		$respuesta = 'Se guardo correctamente';
		$sql="INSERT INTO `parametros_produccion`( `id_empresa`, `area_mercaderia_terminada`, `area_inventario_proceso`) VALUES (".$sesion_id_empresa.",$vM,$vI)";
	}
// echo $sql;
	$result = mysql_query($sql)or die (mysql_error());
	
	$response = ($result)?$respuesta:2;
	echo $response;
}

if($accion== 10){
	$opciones='';
    $consulta = "SELECT `id_lote`, `numero_lote`, `fecha_elaboracion`, `fecha_caducidad`, `calidad_lote`, `estado_lote`, `fecha_registro`, `detalle`, `id_empresa` FROM `lotes` WHERE id_empresa=$sesion_id_empresa AND tipo_origen_lote=1";
                                       
    $resultado = mysql_query( $consulta);

    while ($misdatos = mysql_fetch_array($resultado)) {
                                        
        $opciones.= "<option data-subtext='".$misdatos["id_lote"]."' value='".$misdatos["id_lote"]."'>".$misdatos["numero_lote"]."</option>";
										}
                             
        echo $opciones;                        
}



?>





