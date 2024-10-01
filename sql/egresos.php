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
    
    
	
if($accion == "1")
{
	// GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
	try 
	{
	//	echo "estoy en sql, voy a grabar";
		if(isset ($_POST['txtNumeroEgreso']))
		{
			$numero = $_POST['txtNumeroEgreso'];
			$sql = "SELECT numero from egresos where numero='".$numero."' and id_empresa='".$sesion_id_empresa."';";
		//	echo $sql;
			$resp = mysql_query($sql);
			$entro=0;
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$var=$row["numero"];
			}
			if($var==$numero)
			{				   
				?> <div class="alert alert-danger"><p>Egreso ya existe 
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
			?> <div class="transparent_ajax_error"><p>Error al verificar el egreso 
				<?php echo "".$ex ?></p></div> 
				<?php 
			}
			
			if ($entro==1)
			{
		//INICIO EGRESOS
				try
				{
					$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
					$numero=$_POST['txtNumeroEgreso'];
					$fecha= $_POST['txtFechaFVC'];
					
					$observacion=$_POST['txtObservacion'];
					$estado = "Activo";

					$total=$_POST['txtSubtotal_EI'];
					$txtDescripcion=$_POST['txtDescripcionFVC'];
					$txtContadorFilas=$_POST['txtContadorFilas'];
					$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
					$cmbCosto=$_POST['cmbCosto'];
					
					
					if($numero !="" )
					{            
						$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
						$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
						$iva=0;
				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				{
					$iva=$rowIva1['iva'];
					$txtIdIva=$rowIva1['id_iva'];
					$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				}
			}

			if($numero !=""  )
			{       
				//echo "entro a cliente";
				$sqlm1="Select max(id_egreso) From egresos;";
				$resultm1=mysql_query($sqlm1)  or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_egreso=0;
				while($rowm1=mysql_fetch_array($resultm1))//permite ir de fila en fila de la tabla
				{
					$id_egreso=$rowm1['max(id_egreso)'];
				}
				$id_egreso++;

				$sql="insert into egresos (id_egreso,      fecha,           estado,    total,     sub_total,           numero, 	fecha_anulacion, descripcion, id_iva, id_empresa,id_cuenta, tipo_documento, observacion) 
				values ('".$id_egreso."','".$fecha."','".$estado."','".$total."','".$sub_total."','".$numero."',NULL,'".$txtDescripcion."', '".$txtIdIva."', '".$sesion_id_empresa."',NULL,'".$cmbTipoDocumentoFVC."', '".$observacion."');";
				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');


				$idBodega=$_POST['cmbBodegas'];
				for($i=1; $i<=$txtContadorFilas; $i++)
				{
					if($_POST['txtIdServicio'.$i] >= 1)
					{ //verifica si en el campo esta agregada una cuenta
						//permite sacar el id maximo de detalle_libro_diario
						try 
						{
							$sqli="Select max(id_detalle_egreso) From detalle_egresos";
							$result=mysql_query($sqli) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
							$id_detalle_egreso=0;
							while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
							{
								$id_detalle_egreso=$row['max(id_detalle_egreso)'];
							}
							$id_detalle_egreso++;

						}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

						$cantidad = $_POST['txtCantidadS'.$i];
						$idServicio = $_POST['txtIdServicio'.$i];
						$idKardex = $_POST['txtIdServicio'.$i];
						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						$valorTotal = $_POST['txtValorTotalS'.$i];

						$txtPrecioCosto = $_POST['txtPrecioS'.$i];
						$cuentaOrigen=$_POST['txtCuentaS'.$i];
						$id_tipoP1 ="";
						$codProducto2 = $_POST['txtCodigoServicio'.$i];
						$id_tipoP = $_POST['txtTipo'.$i];
						$id_tipoP1 = $_POST['txtTipo'.$i];
						
						if ($id_tipoP == "1")
						{
							$id_tipoP1 = "P";
						}
						
							//GUARDA EN EL DETALLE VENTAS
						$sql2 = "insert into detalle_egresos (id_detalle_egreso, cantidad, bodega, cuentaOrigen,estado, v_unitario, v_total, 					
						id_egreso, id_producto,tipo_movim, id_empresa) values
						('".$id_detalle_egreso."','".$cantidad."','".$idBodega."','".$cuentaOrigen."','".$estado."','".$valorUnitario."','".$valorTotal."',
						'".$id_egreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";
					//	echo "grabar detalle";
					//	echo $sql2;
						$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
						
						//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
						
						//echo "TIPO";
						//echo $id_tipoP;
						
						
						
						if ($id_tipoP == "P"  or $id_tipoP == "Inventario" or $id_tipoP == "I" or $id_tipoP == "1" )
						{
						//	echo "act";
							$sql3="update productos set costo='".$txtPrecioCosto."', stock=stock-'".$cantidad."' where 
							id_empresa='".$sesion_id_empresa."' and id_producto='".$idServicio."' ;";
							
							$resp3 = mysql_query($sql3) or die('<div class="alert alert-warning">No existen stock para egresar</div>  ');
							
							
            	    $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$idBodega."' and idProducto='".$codProducto2."' ";
                    $resultado = mysql_query($stockBodegas);
                    while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
            			{
            				$id=$rowBodegas1['id'];
            				$cantidad3=$rowBodegas1['cantidad'];
            			}
    		            $fila=mysql_num_rows($resultado);
    		            if ($fila>0){
    		                
    		                $cantidadBodega = $cantidad3-$cantidad;
    		                
    		                $sqlbodegas="UPDATE `cantBodegas` set `cantidad`='".$cantidadBodega."' WHERE idProducto='".$codProducto2."' and id='".$id."'";
    	                    $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    	                    
    		            }else{
    		                
    		                $cantidadBodega = $cantidad3-$cantidad;
    		                
    		                 $sqlbodegas="INSERT INTO `cantBodegas`(`idBodega`, `idProducto`, `cantidad`) 
                            VALUES ('".$idBodega."','".$codProducto2."','".$cantidadBodega."')";
                            
                            $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    		            }
							
							
						}
						
					}
				}// fin del bucle que pasa por todas las filas





				$saldoInicial='';
				if( isset($_POST['checkSaldoInicial']) )
				{
					$saldoInicial="Si";	}
					else
					{
						$saldoInicial = "No";}

// Crear el asiento		
						if ( $saldoInicial=="No")
						{ //$sesion_tipo_empresa=="6" and
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
								$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
						$resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
						$result7=mysql_query($consulta7) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
							$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
					$descripcion=$observacion." ".$cmbTipoDocumentoFVC." #".$numero." realizado por ".$sesion_empresa_nombre;
					$debe = $total;
					$haber1 = $sub_total;
					$haber2 = $_POST['txtTotalIvaFVC'];
					$total_debe = $debe;
					$total_haber = $haber1 + $haber2;
					
					$tipo_mov="E";
					
				//GUARDA EN  COMPROBANTES
					$sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					
				//GUARDA EN EL LIBRO DIARIO
					$sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha, total_debe,
					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."',
					'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					'".$id_comprobante."','".$tipo_mov."','".$numero."' )";
				//	echo $sqlLD;
					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');

					for($i=1; $i<=$txtContadorFilas; $i++)
					{
						$idPlanCuentas[$i] = '';
						$debeVector[$i] = 0;
						$haberVector[$i] = 0;	
						$tipo[$i]='';
					}
					
					$lin_diario=0;
					
					//echo "fila-conta".$txtContadorFilas;
					$tot_ventas=0;
					$tot_servicios=0;
					$tot_costo=0;
					$lin_diario=0;
					//$lin_diario=0;
					$valor[$lin_diario]=0;
					
					for($i=1; $i<=$txtContadorFilas; $i++)
					{
						if($_POST['txtIdServicio'.$i] >= 1)
						{										
							$idProducto=$_POST['txtIdServicio'.$i];
							$id_tipoP = $_POST['txtTipo'.$i];
							
							$cuentaOrigen = $_POST['txtCuentaS'.$i];//origen
							// $cuentaDestino= $_POST['cuenta'.$i];//destino
							$cuentaDestino =( $_POST['cuenta'.$i]=='')?$_POST['cmbCosto']:$_POST['cuenta'.$i];//DESTINO
							$total= $_POST['txtValorTotalS'.$i];
							
							///destino
							

							$encontradoOrigen ="NO";
							$encontradoDestino="NO";
							if ($lin_diario>0) {
								for ($k=1;$k<=$lin_diario;$k++){
									if ($idPlanCuentas[$k] == $cuentaDestino && $tipo[$k]=='Destino') 
									{
										$tipo[$k]='Destino';
										$valor[$k]  = $valor[$k] + $total;
										$debeVector[$k] =$valor[$k] ;
										$haberVector[$k] =0 ;
										$encontradoDestino="SI";
										
									}
									
								}
							}
							
							if ($lin_diario>0) {
								for ($k=1;$k<=$lin_diario;$k++){
									if ($idPlanCuentas[$k] == $cuentaOrigen && $tipo[$k]=='Origen') 
									{
										$tipo[$k]='Origen';
										$valor[$k]  = $valor[$k] + $total;
										$debeVector[$k] =0;
										$haberVector[$k] = $valor[$k] ;
										$encontradoOrigen="SI";
										
									}
								}
							}
							
							if($encontradoDestino=="NO"){
        				        //destino
								$lin_diario=$lin_diario+1;
								$tipo[$lin_diario]='Destino';
								$idPlanCuentas[$lin_diario] = $cuentaDestino;
								$valor[$lin_diario]         = $total;
								$debeVector[$lin_diario]    = $valor[$lin_diario] ;
								$haberVector[$lin_diario] = 0 ;
							}
							
							
							
							
							if($encontradoOrigen == "NO"){
                				     //origen
								$lin_diario=$lin_diario+1;
								$tipo[$lin_diario]='Origen';
								$idPlanCuentas[$lin_diario] = $cuentaOrigen;
								$valor[$lin_diario]         = $total;
								$debeVector[$lin_diario]    = 0 ;
								$haberVector[$lin_diario] = $valor[$lin_diario] ;   
							}
							
							
						}
					}
					
					for($i=1; $i<=$lin_diario; $i++)
					{
						if ($idPlanCuentas[$i] !="")
						{
							
						//GUARDA EN EL DETALLE LIBRO DIARIO
							$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
							('".$id_libro_diario."',	'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							$id_detalle_libro_diario=mysql_insert_id();	
							
                    //      echo $sqlDLD;
							
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

				//	echo "	va a crear CostoS=".$tot_costo;
							
						}

						
			  // GUARDAR EN KARDEX
//echo $id_venta;
						$sqlki="Select max(id_kardes) From kardes";
						$resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
						$id_kardes='0';
				while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
				{
					$id_kardes=$rowki['max(id_kardes)'];
				}
				$id_kardes++;
				$sqlk="insert into kardes (id_kardes, fecha, detalle,  id_factura,id_empresa) values
				('".$id_kardes."','".$fecha."','Egreso','".$id_egreso."', '".$sesion_id_empresa."')";
				$result=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
			//	echo resp2;
				if($result && $resp2)
				{
					?> Registro guardado correctamente. <?php
				}
				else
				{
					?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Revise que haya ingresado todos los datos correctamente. <?php echo " ".mysql_error(); ?>;</p></div> <?php
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
		//FIN EGRESOS 		
	}
}




if($accion == "2")
{
  
try
	{
        $NumeroEgreso = $_POST['txtNumeroEgreso'];
		$txtFecha = $_POST['txtFechaFVC'];
        $contador_filas = $_POST['txtContadorFilas'];
	//	echo "contadofila11111=".$contador_filas;
        $txtObservacion = ucwords($_POST['txtObservacion']);
		$txtSubtotal_EI = $_POST['txtSubtotal_EI'];
		
        $txtPeriodoContable2 = $sesion_id_periodo_contable;

	    $txtContadorAsientosAgregados2 = $_POST['txtContadorAsientosAgregadosFVC'];
		$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
		$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
		$iva=0;
		while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
		{
			$iva=$rowIva1['iva'];
			$txtIdIva=$rowIva1['id_iva'];
			$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
		}
		$sql2 = "update egresos set observacion='".$txtObservacion."', fecha='".$txtFecha."', total=".$txtSubtotal_EI." , sub_total=".$txtSubtotal_EI." ,id_iva='".$txtIdIva."' where id_empresa='".$sesion_id_empresa."' and numero='".$NumeroEgreso."'";
        $result2=mysql_query($sql2) or die(mysql_error());
		
		$sql2="select id_egreso from egresos where id_empresa='".$sesion_id_empresa."' and numero='".$NumeroEgreso."'";
		$resultado=mysql_query($sql2) or die(mysql_error());
			
		while($row=mysql_fetch_array($resultado) )
		{
			$id_egreso=$row['id_egreso'];
		}

		$sql2="select detalle_egresos.*, productos.codigo from detalle_egresos INNER JOIN productos on detalle_egresos.id_producto = productos.id_producto where detalle_egresos.id_empresa='".$sesion_id_empresa."' and detalle_egresos.id_egreso='".$id_egreso."'";   
		$resultado=mysql_query($sql2) or die(mysql_error());
		while($row=mysql_fetch_array($resultado) )
		{
			//echo "act invent";
			
			
			$id_tipoP=$row['tipo_movim'];
			if ($id_tipoP == "P") 
			{
				$id_producto=$row['id_producto'];
				$cantidad=$row['cantidad'];
				$cod_producto=$row['codigo'];
				$bodegaDetalle= $row['bodega'];
				$sql2="SELECT `id`, `idBodega`, `idProducto`, `cantidad` FROM `cantBodegas` WHERE idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$resultadoBod=mysql_query($sql2);
			$stock = 0;
			$stock_act=0;
			while($rowBod=mysql_fetch_array($resultadoBod))
				{
					$stock =$rowBod['cantidad'];
				}
                
			$stock_act=$stock+$cantidad;
			$sql221="update cantBodegas set cantidad = '".$stock_act."' where idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$result221=mysql_query($sql221) or die("\nError al actualizar el stock de cantBodegas: ".mysql_error());
			
			}
		}

		$sqlelimina2 = "Delete From detalle_egresos where id_egreso='".$id_egreso."';";
        $resultelimina2=mysql_query($sqlelimina2);
    	
		$idBodega=$_POST['cmbBodegas'];
		$txtContadorFilas=$_POST['txtContadorFilas'];
		for($i=1; $i<=$txtContadorFilas; $i++)
				{
					
			if($_POST['txtIdServicio'.$i] >= 1){ //verifica si en el campo esta agregada una cuenta
						//permite sacar el id maximo de detalle_libro_diario
						

						$cantidad = $_POST['txtCantidadS'.$i];
						$idServicio = $_POST['txtIdServicio'.$i];
						$idKardex = $_POST['txtIdServicio'.$i];
						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						$valorTotal = $_POST['txtValorTotalS'.$i];

						
						$cuentaOrigen=$_POST['txtCuentaS'.$i];
						$id_tipoP1 ="";
						$codProducto2 = $_POST['txtCodigoServicio'.$i];
						$id_tipoP = $_POST['txtTipo'.$i];
						$id_tipoP1 = $_POST['txtTipo'.$i];
						$estado = 'Activo';
						if ($id_tipoP == "1")
						{
							$id_tipoP1 = "P";
						}
						
							//GUARDA EN EL DETALLE VENTAS
						$sql2 = "insert into detalle_egresos ( cantidad, bodega, cuentaOrigen,estado, v_unitario, v_total, 					
						id_egreso, id_producto,tipo_movim, id_empresa) values
						('".$cantidad."','".$idBodega."','".$cuentaOrigen."','".$estado."','".$valorUnitario."','".$valorTotal."',
						'".$id_egreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";
					
						$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
						
						//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
						
						//echo "TIPO";
						//echo $id_tipoP;
						
						
						
						if ($id_tipoP == "P"  or $id_tipoP == "Inventario" or $id_tipoP == "I" or $id_tipoP == "1" )
						{
						
							
            	    $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$idBodega."' and idProducto='".$codProducto2."' ";
                    $resultado = mysql_query($stockBodegas);
                    while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
            			{
            				$id=$rowBodegas1['id'];
            				$cantidad3=$rowBodegas1['cantidad'];
            			}
    		            $fila=mysql_num_rows($resultado);
    		            if ($fila>0){
    		                
    		                $cantidadBodega = $cantidad3-$cantidad;
    		                
    		                $sqlbodegas="UPDATE `cantBodegas` set `cantidad`='".$cantidadBodega."' WHERE idProducto='".$codProducto2."' and id='".$id."'";
    	                    $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    	                    
    		            }else{
    		                
    		                $cantidadBodega = $cantidad3-$cantidad;
    		                
    		                 $sqlbodegas="INSERT INTO `cantBodegas`(`idBodega`, `idProducto`, `cantidad`) 
                            VALUES ('".$idBodega."','".$codProducto2."','".$cantidadBodega."')";
                            
                            $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    		            }
							
							
						}
						
					}
				}// fin del bucle que pasa por todas las filas	
		
		$sqlBuscarKardes="SELECT `id_kardes`, `fecha`, `detalle`, `cantidad`, `bodegaInventario`, `total`, `id_factura`, `id_empresa` FROM `kardes` WHERE id_factura='".$id_egreso."' AND detalle='Egreso' AND id_empresa =$sesion_id_empresa ;";
		$resultBuscarKardes= mysql_query($sqlBuscarKardes);
		$numFilasKardes = mysql_num_rows($resultBuscarKardes);
		if($numFilasKardes==0){
		    	$sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
				('".$txtFecha."','Egreso','".$id_egreso."', '".$sesion_id_empresa."')";
		}else{
		    $sqlk="UPDATE kardes SET fecha='".$txtFecha."' WHERE id_factura='".$id_egreso."' AND detalle='Egreso';";
		}
		
		$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());



			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC'];
			$observacion=$_POST['txtObservacion'];

			$descripcion=$observacion." ".$cmbTipoDocumentoFVC."#".$NumeroEgreso." realizado por ".$sesion_empresa_nombre;

		    $tot_costo=0;
			try
			{
			 $sqldes = "update libro_diario set descripcion='".$descripcion."', fecha='".$txtFecha."', total_debe='".$txtSubtotal_EI."', 
			 total_haber='".$txtSubtotal_EI."' where id_periodo_contable='".$sesion_id_periodo_contable."' and tipo_mov='E' and numero_cpra_vta='".$NumeroEgreso."';";
             $resultdes=mysql_query($sqldes) or die("\nError al actualizar libro diario ".mysql_error());
			}
			catch
			(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error al actualizar libro_diario: <?php echo "".$ex ?></p></div> <?php }


			$sql2="select id_libro_diario,id_comprobante from libro_diario where id_periodo_contable='".$sesion_id_periodo_contable."' and numero_cpra_vta='".$NumeroEgreso."' and tipo_mov='E' ";
		//	echo $sql2;     
			$resultado=mysql_query($sql2) or die(mysql_error());
			$id_libro_diario=0;
			while($row=mysql_fetch_array($resultado) )
			{
				$id_libro_diario=$row['id_libro_diario'];
				$id_comprobante = $row['id_comprobante'];
			}
					
					
			//Fin actualizar la cabecera del libro_diario
				
				/* GUARDAR ASIENTO CONTABLE */
			$sqlelimina = "Delete From detalle_libro_diario where id_libro_diario='".$id_libro_diario."';";
            $resultelimina=mysql_query($sqlelimina);
		
			$tipo_comprobante = "Diario"; 				
				$idPlanCuentas[1] = '';
				$idPlanCuentas[2] = '';
				$idPlanCuentas[3] = '';
				$debeVector[1] = 0;
				$debeVector[2] = 0;
				$debeVector[3] = 0;
				$haberVector[1] = 0;
				$haberVector[2] = 0;
				$haberVector[3] = 0;		
				$lin_diario=0;
					
					//echo "fila-conta".$txtContadorFilas;
				$tot_ventas=0;
				$tot_servicios=0;
				$tot_costo=0;
				$lin_diario=0;
					//$lin_diario=0;
		    $valor[$lin_diario]=0;
		            
			for($i=1; $i<=$txtContadorFilas; $i++)
			{
				if($_POST['txtIdServicio'.$i] >= 1)
				{										
					$idProducto=$_POST['txtIdServicio'.$i];
					$id_tipoP = $_POST['txtTipo'.$i];
							
					$cuentaOrigen = $_POST['txtCuentaS'.$i];//origen
					$cuentaDestino= $_POST['cuenta'.$i];//destino
					$total= $_POST['txtValorTotalS'.$i];
							
							///destino
        			$encontradoOrigen ="NO";
        			$encontradoDestino="NO";
            		if ($lin_diario>0) {
            				for ($k=1;$k<=$lin_diario;$k++){
								if ($idPlanCuentas[$k] == $cuentaDestino) 
            					{
            							$valor[$k]  = $valor[$k] + $total;
            							$debeVector[$k] =$valor[$k] ;
            							$haberVector[$k] =0 ;
            							$encontradoDestino="SI";
                				}
            				}
            			}
            				
            			if ($lin_diario>0) 
						{
            				for ($k=1;$k<=$lin_diario;$k++)
							{
                           		if ($idPlanCuentas[$k] == $cuentaOrigen) 
            					{
            						$valor[$k]  = $valor[$k] + $total;
            						$debeVector[$k] =0;
            						$haberVector[$k] = $valor[$k] ;
            						$encontradoOrigen="SI";
            
                				}
            				}
            			}
                                
        				if($encontradoDestino=="NO"){
        				        //destino
            				$lin_diario=$lin_diario+1;
            				$idPlanCuentas[$lin_diario] = $cuentaDestino;
            				$valor[$lin_diario]         = $total;
            				$debeVector[$lin_diario]    = $valor[$lin_diario] ;
            				$haberVector[$lin_diario] = 0 ;
        				}
            				    
                        if($encontradoOrigen == "NO")
							{
                				     //origen
                					$lin_diario=$lin_diario+1;
                					$idPlanCuentas[$lin_diario] = $cuentaOrigen;
                					$valor[$lin_diario]         = $total;
                					$debeVector[$lin_diario]    = 0 ;
                					$haberVector[$lin_diario] = $valor[$lin_diario] ;   
            				}                               				
						}
					}
		
					for($i=1; $i<=$lin_diario; $i++)
					{
						if ($idPlanCuentas[$i] !="")
						{
							$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
							('".$id_libro_diario."',	'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							$id_detalle_libro_diario=mysql_insert_id();	                     									
						}
					}

			
					if($resultdes)
					{	
						if($resultelimina && $resp2)
						{	
							?> <div class='transparent_ajax_correcto'><p>Registros modificados correctamente.</p></div> <?php

						}
						else { ?> <div class='transparent_ajax_error'><p>Error al actualiza la tabla detalle_libro_diario. </p></div> <?php }

					}else{ ?> <div class='transparent_ajax_error'><p>Error al actualiza la tabla libro_diario. </p></div> <?php }
			
				
				// }//aqui
				
			
				
    }
	catch (Exception $e) 
	{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}
if($accion == "3")
{
  
try
	{
        $NumeroEgreso = $_POST['id'];
		$txtFecha = $_POST['txtFechaFVC'];

		
        $txtPeriodoContable2 = $sesion_id_periodo_contable;

		$sql2="select id_egreso from egresos where id_empresa='".$sesion_id_empresa."' and numero='".$NumeroEgreso."'";
		$resultado=mysql_query($sql2) or die(mysql_error());
			
		while($row=mysql_fetch_array($resultado) )
		{
			$id_egreso=$row['id_egreso'];
		}

		$sql2="select detalle_egresos.*, productos.codigo from detalle_egresos INNER JOIN productos on detalle_egresos.id_producto = productos.id_producto where detalle_egresos.id_empresa='".$sesion_id_empresa."' and detalle_egresos.id_egreso='".$id_egreso."'";   
		$resultado=mysql_query($sql2) or die(mysql_error());
		while($row=mysql_fetch_array($resultado) )
		{
			//echo "act invent";
			
			
			$id_tipoP=$row['tipo_movim'];
			if ($id_tipoP == "P") 
			{
				$id_producto=$row['id_producto'];
				$cantidad=$row['cantidad'];
				$cod_producto=$row['codigo'];
				$bodegaDetalle= $row['bodega'];
				$sql2="SELECT `id`, `idBodega`, `idProducto`, `cantidad` FROM `cantBodegas` WHERE idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$resultadoBod=mysql_query($sql2);
			$stock = 0;
			$stock_act=0;
			while($rowBod=mysql_fetch_array($resultadoBod))
				{
					$stock =$rowBod['cantidad'];
				}
                
			$stock_act=$stock+$cantidad;
			$sql221="update cantBodegas set cantidad = '".$stock_act."' where idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$result221=mysql_query($sql221) or die("\nError al actualizar el stock de cantBodegas: ".mysql_error());
			
			}
		}

		$sqlelimina2 = "Delete From detalle_egresos where id_egreso='".$id_egreso."';";
        $resultelimina2=mysql_query($sqlelimina2);
    	
		$sql2 = "DELETE FROM  egresos  where id_egreso='".$id_egreso."'" ;
        $result2=mysql_query($sql2) or die(mysql_error());

		$idBodega=$_POST['cmbBodegas'];
		$txtContadorFilas=$_POST['txtContadorFilas'];
	
		
		$sqlk="DELETE FROM  kardes  WHERE id_factura='".$id_egreso."' AND detalle='Egreso';";
		$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());



			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC'];
			$observacion=$_POST['txtObservacion'];

			$descripcion=$observacion." ".$cmbTipoDocumentoFVC."#".$NumeroEgreso." realizado por ".$sesion_empresa_nombre;

		    $tot_costo=0;
	

			echo $sql2="select id_libro_diario,id_comprobante from libro_diario where id_periodo_contable='".$sesion_id_periodo_contable."' and numero_cpra_vta='".$NumeroEgreso."' and tipo_mov='E' ";
		//	echo $sql2;     
			$resultado=mysql_query($sql2) or die(mysql_error());
			$id_libro_diario='';
			while($row=mysql_fetch_array($resultado) )
			{
				$id_libro_diario=$row['id_libro_diario'];
				$id_comprobante = $row['id_comprobante'];
			}
			if(trim($id_libro_diario)!=''){
				$sqlelimina = "Delete From detalle_libro_diario where id_libro_diario='".$id_libro_diario."';";
				$resultelimina=mysql_query($sqlelimina);
				
				$sqldes = "DELETE FROM libro_diario  where id_libro_diario=$id_libro_diario AND id_periodo_contable=$sesion_id_periodo_contable";
				$resultdes=mysql_query($sqldes) or die("\nError al actualizar libro diario ".mysql_error());
	
				$sqldes = "DELETE FROM comprobantes  where id_comprobante=$id_comprobante AND id_empresa=$sesion_id_empresa";
				$resultdes=mysql_query($sqldes) or die("\nError al actualizar libro diario ".mysql_error());
			}
			
			if($resultk )
			{
				?> <div class='alert alert-success'><p>Registro eliminado correctamente.</p></div> <?php
			}
			else
			{
				?> <div class='transparent_ajax_error'><p>Error al eliminar los datos: Revise que haya ingresado todos los datos correctamente. <?php echo " ".mysql_error(); ?>;</p></div> <?php
			}
				
			
				
    }
	catch (Exception $e) 
	{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}


//echo $accion;	
if($accion == "4")
{
    // Is there a posted query string?
	if(isset($_POST['queryString'])) 
	{
		$queryString = $_POST['queryString'];
		
		$checkSaldoInicial = $_POST['checkSaldoInicial'];
		
        //echo "==".$checkSaldoInicial;
		
		$cont = $_POST['cont'];

        // Is the string length greater than 0?
        if(strlen($queryString) >0) 
		{        
		    
	if($checkSaldoInicial=='trans'){
	    		$query = "SELECT
    productos.`id_producto` AS productos_id_producto,
    productos.`producto` AS productos_nombre,
    productos.`precio1` AS productos_precio1,
    productos.`precio2` AS productos_precio2,
     productos.`costo` AS productos_costo,
    productos.`id_empresa` AS productos_id_empresa,
    productos.`iva` AS productos_iva,
    productos.`codigo` AS productos_codigo,
    productos.`tipos_compras` AS productos_tipos_compras,
    productos.`stock` AS productos_stock,
    productos.`id_cuenta` AS productos_cuenta,
    productos.`grupo` AS productos_grupo,
    productos.`codigo` AS productos_codigo,
    centro_costo.`id_centro_costo` AS centro_id,
    centro_costo.`descripcion` AS centro_descripcion,
    categorias.`id_categoria` AS categorias_id_categoria,
    categorias.`categoria` AS categorias_categoria,
    categorias.`id_empresa` AS categorias_id_empresa,
    productos.`id_empresa` AS productos_id_empresa,
    cantBodegas.`cantidad` AS bodega_cantidad,
    cantBodegas.`idBodega` AS bodega_idBodega,
    bodegas.`id` AS bodegas_id,
    bodegas.`detalle` AS bodega_detalle
FROM
    `productos`
INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
INNER JOIN cantBodegas ON productos.codigo = cantBodegas.`idProducto`
INNER JOIN bodegas ON bodegas.id = cantBodegas.`idBodega`
INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
WHERE
    (
        productos.codigo LIKE '%$queryString%' || productos.producto LIKE '%$queryString%'
    ) AND productos.id_empresa = '$sesion_id_empresa'AND productos.`tipos_compras` = '1' 
    
and bodegas.id_empresa= '$sesion_id_empresa'
                
                GROUP BY bodegas.id ";
                // , productos.codigo
	}else{	    
		    
		$query = "SELECT
            productos.`id_producto` AS productos_id_producto,
			productos.`codigo` AS productos_codigo,
            productos.`producto` AS productos_nombre,
            productos.`costo` AS productos_costo,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
			productos.`codigo` AS productos_codigo,
			productos.`tipos_compras` AS productos_tipos_compras,
			productos.`id_cuenta` AS productos_cuenta,
			categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
			productos.`id_empresa` AS productos_id_empresa
        FROM
            `categorias`categorias INNER JOIN `productos` productos 
			ON categorias.`id_categoria` = productos.`id_categoria`
            WHERE productos.`id_empresa`='".$sesion_id_empresa."' and 
			CONCAT(productos.`codigo`, productos.`producto`) LIKE '%$queryString%'  
		
			LIMIT 20; ";
				// GROUP BY productos.`codigo`
	}
// 	echo $query;

            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) 
			{
                if($numero_filas ==0){
                         echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
                    }else{
                       echo "<table class='table table-bordered table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Código</th><th>Nombre</th><th >Categoria</th>  <th>Unidad</th>  <th>Tipo Servicio</th>  <th ><a href='javascript: fn_cerrar_div();'><img align='right' src='images/cerrar2.png' width='16' height='16' alt='cerrar' title='Cerrar' /></a></th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysql_fetch_array($result))
					{
                        $id_iva = 0;
                        $iva = 0;
                            
                            // CONSULTA PARA EL IVA
						if ($row["productos_iva"]=='Si')
						{
							$sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$sesion_id_empresa."';";
                            $result2 = mysql_query($sqliva);
							while ($row2 = mysql_fetch_array($result2)) 
							{
									$id_iva = $row2["id_iva"];
									$iva = $row2["iva"];
							}					
						}
						
  	if($checkSaldoInicial=='trans'){ 		
    echo '<tr onClick="fill10_egreso(\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["productos_id_producto"]."*".$row["productos_nombre"]."*".$row["productos_costo"]." "."*".$id_iva."*".$iva."*".$row["productos_tipos_compras"]."*".$row["productos_cuenta"]."*".$row["productos_codigo"]."*".$row["centro_descripcion"]."*".$row["bodega_idBodega"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
  	}else{
  	    $row["centro_descripcion"] ='0';
  	    $row["bodega_idBodega"] = '0';
    echo '<tr onClick="fill10_egreso(\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["productos_id_producto"]."*".$row["productos_nombre"]." "."*".$row["productos_costo"]."*".$id_iva."*".$iva."*".$row["productos_tipos_compras"]."*".$row["productos_cuenta"]."*".$row["productos_codigo"]."*".$row["centro_descripcion"]."*".$row["bodega_idBodega"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
  	}
            
           	if($checkSaldoInicial=='trans'){ 
            $sqlCantidadTotalBodegas = "SELECT SUM(cantidad) as total from cantBodegas,bodegas  where idProducto = '".$row['productos_codigo']."' 
                and bodegas.id=cantBodegas.idBodega and bodegas.id_empresa='".$sesion_id_empresa."'  ";

                $resultCantidadTotalBodegas=mysql_query($sqlCantidadTotalBodegas);
                $sumaStockBodegas=0;
                while ($rowCantidadTotalBodega = mysql_fetch_array($resultCantidadTotalBodegas)) {
                    $sumaStockBodegas=   $rowCantidadTotalBodega['total'];
                }
            $sumaStockBodegas= ($sumaStockBodegas=='')?0:$sumaStockBodegas;             
                            echo "<td>".$row["productos_codigo"]."</td>";
                            echo "<td>".$row["productos_nombre"]."</td>";
							echo "<td>".$sumaStockBodegas."</td>";
							echo "<td>".$row["productos_tipos_compras"]."</td>";
							echo "<td>".$row["bodega_detalle"]."</td>";
							echo "<td>".$row["bodega_cantidad"]."</td>";     
           	}else{
           	                echo "<td>".$row["productos_codigo"]."</td>";
                            echo "<td>".$row["productos_nombre"]."</td>";
							echo "<td>".$row["productos_tipos_compras"]."</td>";
           	}
                         
                     		
							
							
							
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

if($accion == "5")
{
	$tipo_docum=$_POST['tipo_docum'];

	if ($tipo_docum=='Ingreso')
	{
		 $sqlp="Select max(numero), max(id_ingreso) From ingresos where id_empresa='".$sesion_id_empresa."';";
	    $result=mysql_query($sqlp);
        $numero=0;
        $id_ingreso=0;
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            $numero=$row['max(numero)'];
            $id_ingreso=$row['max(id_ingreso)'];
        }
        $numero++;
        $id_ingreso++;
		echo $numero;
	}
	else if  ($tipo_docum=='Egreso')
	{
		$sqlp="Select max(numero), max(id_egreso) From egresos where id_empresa='".$sesion_id_empresa."';";
	    $result=mysql_query($sqlp);
        $numero=0;
        $id_egreso=0;
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            $numero=$row['max(numero)'];
			$id_egreso=$row['max(id_egreso)'];
        }
        $numero++;
        $id_egreso++;
		echo $numero;
	}	
	else if  ($tipo_docum=='trans')
	{
	  $sqlp="Select max(num_trans), max(id_transferencia) From transferencias where id_empresa='".$sesion_id_empresa."';";
	    $result=mysql_query($sqlp);
        $numero=0;
        $id_egreso=0;
        while($row= mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            $numero=$row['max(num_trans)'];
			$id_transferencia=$row['max(id_transferencia)'];
        }
        $numero++;
        $id_transferencia++;
		echo $numero;
	}
}
?>
