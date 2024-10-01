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
	
function DiarioDetalle($id_libro_diario,$empresa)
{
	$tipo_comprobante = "Diario"; 				
	//echo $txtContadorFilas;
	$txtContadorFilas=$_POST['txtContadorFilas'];
	//echo "cont".$txtContadorFilas;
			
	for($i=1; $i<=$txtContadorFilas; $i++)
	{
		$idPlanCuentas[$i] = '';
		$debeVector[$i] = 0;
		$haberVector[$i] = 0;	
	}					
	$tot_ventas=0;
	$tot_servicios=0;
	$tot_costo=0;
	$lin_diario=0;
	$valor[$lin_diario]=0;
		    
	$encontradoDestino="NO";
    $encontradoOrigen ="NO";
	for($i=1; $i<=$txtContadorFilas; $i++)
	{
		if($_POST['txtIdServicio'.$i] >= 1)
		{										
		//    echo $_POST['txtIdServicio'.$i];
			$idProducto=$_POST['txtIdServicio'.$i];
			$id_tipoP = $_POST['txtTipo'.$i];							
			$cuentaOrigen= $_POST['cuenta'.$i];//ORIGEN
			$cuentaDestino = $_POST['txtCuentaS'.$i];//DESTINO
			$total= $_POST['txtValorTotalS'.$i];
		//	echo "o=".$cuentaOrigen;
		//	echo "d=".$cuentaDestino;
			
			///destino
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


			//echo "fila-conta".$txtContadorFilas;
			if ($lin_diario>0) 
			{
				for ($k=1;$k<=$lin_diario;$k++)
				{
					if ($idPlanCuentas[$k] == $cuentaDestino) 
            		{
           				$valor[$k]  = $valor[$k] + $total;
           				$debeVector[$k] =$valor[$k] ;
           				$haberVector[$k] =0 ;
           				$encontradoDestino="SI";
                	}
            	}
            }
 
			if($encontradoDestino=="NO")
			{
        	       //destino
			//	echo "destno";
            	$lin_diario=$lin_diario+1;
      			$idPlanCuentas[$lin_diario] = $cuentaDestino;
      			$valor[$lin_diario]         = $total;
      			$debeVector[$lin_diario]    = $valor[$lin_diario] ;
      			$haberVector[$lin_diario] = 0 ;
        	}
 
			if($encontradoOrigen == "NO")
			{
				//echo "destino";
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
		//ECHO "<BR/>";
		//echo $idPlanCuentas[$i];
		//echo $debeVector[$i];
		//echo $haberVector[$i];
		
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
			catch(Exception $ex) 
			{ ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

						//GUARDA EN EL DETALLE LIBRO DIARIO
			$sqlDLD = "insert into detalle_libro_diario (id_detalle_libro_diario, id_libro_diario, 
							id_plan_cuenta,debe, haber, id_periodo_contable) values 
							('".$id_detalle_libro_diario."','".$id_libro_diario."',
							'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
							'".$empresa."');";
						//		echo "<br>".$sqlDLD;
			$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				
//echo "resp2==".$resp2;				
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
	
	return $resp2;
}


	
if($accion == "1")
{
	// GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
	try 
	{
		if(isset ($_POST['txtNumeroEgreso']))
		{
			$numero = $_POST['txtNumeroEgreso'];
			$sql = "SELECT numero from ingresos where numero='".$numero."' and id_empresa='".$sesion_id_empresa."';";
		//	echo $sql;
			$resp = mysql_query($sql);
			$entro=0;
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$var=$row["numero"];
			}
			if($var==$numero)
			{				   
				?> <div class="alert alert-danger"><p>Ingreso ya existe 
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
		try
		{
			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
			$numero=$_POST['txtNumeroEgreso'];
			
		    $fecha= $_POST['txtFechaFVC'];
				
			$observacion=$_POST['txtObservacion'];
			$estado = "Activo";
						
			$total=$_POST['txtSubtotal_EI'];
			
			
			$checkSaldoInicial=$_POST['checkSaldoInicial'];
			
			$txtDescripcion=$_POST['txtDescripcionFVC'];
			
			$txtContadorFilas=$_POST['txtContadorFilas'];
			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
			$cmbCosto=$_POST['cmbCosto'];
       		
		    $saldoInicial="trans";

			
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

				

				$sql="insert into ingresos (id_ingreso, fecha, estado, total, sub_total, numero, 
				fecha_anulacion, descripcion, id_iva, id_empresa,id_cuenta, tipo_documento, observacion) 
				values ('".$id_ingreso."','".$fecha."','".$estado."','".$total."','".$sub_total."','".$numero."',
				NULL,'".$txtDescripcion."', '".$txtIdIva."', '".$sesion_id_empresa."',
				NULL,'".$cmbTipoDocumentoFVC."', '".$observacion."');";

				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
                $idBodega=$_POST['cmbBodegas'];
                
                
				for($i=1; $i<=$txtContadorFilas; $i++)
				
				{
				    
				    
					if($_POST['txtIdServicio'.$i] >= 1)
					{ //verifica si en el campo esta agregada una cuenta
						//permite sacar el id maximo de detalle_libro_diario

						$cantidad = $_POST['txtCantidadS'.$i];
						$idServicio = $_POST['txtIdServicio'.$i];
						$idKardex = $_POST['txtIdServicio'.$i];
						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						$valorTotal = $_POST['txtValorTotalS'.$i];
						$codProducto2 = $_POST['txtCodigoServicio'.$i];

						$id_tipoP1 ="";
						
						$id_tipoP = $_POST['txtTipo'.$i];
						$id_tipoP1 = $_POST['txtTipo'.$i];
					
			        	if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
						{
								$id_tipoP1 = "P";
						}
					




						$sql2 = "insert into detalle_ingresos (bodega, cantidad, estado, v_unitario, v_total,id_ingreso, id_producto,tipo_movim, id_empresa) values
						
						('".$idBodega."','".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_ingreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";
						$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
							
						//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
												

						if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
						{
						    
						//	echo "act";
							$sql3="update productos set stock=stock+'".$cantidad."' where id_empresa='".$sesion_id_empresa."' and id_producto='".$idServicio."';";
						//	echo $sql3;
							$resp3 = mysql_query($sql3) or die('<div class="transparent_ajax_error"><p>Error al actualizar existencia '.mysql_error().' </p></div>  ');
							
							
				         $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$idBodega."' and idProducto='".$codProducto2."' ";
                        $resultado = mysql_query($stockBodegas);
                        while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
            				{
            					$id=$rowBodegas1['id'];
            					$cantidad3=$rowBodegas1['cantidad'];
            				}
            				
    		            $fila=mysql_num_rows($resultado);
    		            if ($fila>0){
    		                
    		                $cantidadBodega = $cantidad3+$cantidad;
    		                
    		                $sqlbodegas="UPDATE `cantBodegas` set `cantidad`='".$cantidadBodega."' WHERE idProducto='".$codProducto2."' and id='".$id."'";
    	                    $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    	                    
    		            }else{
    		                
    		                $cantidadBodega = $cantidad3+$cantidad;
    		                
    		                 $sqlbodegas="INSERT INTO `cantBodegas`(`idBodega`, `idProducto`, `cantidad`) 
                            VALUES ('".$idBodega."','".$codProducto2."','".$cantidadBodega."')";
                            
                            $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    		            }
							
							
						}
						
					}
				}
				
				
				
				
				
				if ($sesion_tipo_empresa=="6" and $saldoInicial=="No" )
				{
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
					//$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
//					$descripcion=$Observacion." ".$cmbTipoDocumentoFVC." ".$numero_factura;
					$descripcion=$observacion." ".$cmbTipoDocumentoFVC."#".$numero." realizado por ".$sesion_empresa_nombre;

					$debe = $total;
					$haber =$total;
					//$haber1 = $sub_total;
					//$haber2 = $_POST['txtTotalIvaFVC'];
					$total_debe = $debe;
					$total_haber =$haber;
					//$total_haber = $haber1 + $haber2;
					
					$tipo_mov="I";
	
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



					//echo "fila-conta".$txtContadorFilas;
					for($i=1; $i<=$txtContadorFilas; $i++)
					{
						if($_POST['txtIdServicio'.$i] >= 1)
						{										 //verifica si en el campo esta agregada una cuenta
							$idProducto=$_POST['txtIdServicio'.$i];
							$id_tipoP = $_POST['txtTipo'.$i];
							$sqlS = "SELECT
									productos.`id_producto` AS productos_id_servicio,
									productos.`producto` AS productos_nombre,
									productos.`iva` AS productos_iva,
									productos.`id_empresa` AS productos_id_empresa,
									productos.`costo` AS productos_costo,                  
									productos.`id_cuenta` AS productos_id_cuenta	
								FROM
								`productos` productos  Where productos.`id_empresa`='".$sesion_id_empresa."' and
									productos.`id_producto` ='".$idProducto."'";
								$resultS=mysql_query($sqlS) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;	
                            $productos_costo=0;								
							while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
							{
								$productos_id_cuenta=$rowS['productos_id_cuenta'];
							}
					//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
							
							if ($id_tipoP == "Inventario"  or $id_tipoP == "P" )
							{
								$tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
								$costo_promedio=0;  //pendiente
								$tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
							//	echo "<br>".$tot_ventas;
							}
							else
							{
								$tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
							}
						}
					}
				/* 	$idcodigo_ingr=$cmbCosto;
					
						try {
                    $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas WHERE 
                    `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable and 
                    enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
                    $resultInventario=mysql_query($sqlInventario);
                    $idcodigo_inv=0;
                    while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
                    {
                        $idcodigo_inv=$row['codigo_plan_cuentas'];
                    }
                    $idcodigo_inv;

            }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
					
			 */		
				/* 	$sql="select 
							plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,
							plan_cuentas.`codigo` AS plan_cuentas_codigo
      					from
							`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
							(  plan_cuentas.`codigo` ='".$idcodigo_inv."')"  ;

					$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
				 */	
				/* 	$plan_id_cuenta_inventario=0;
					//$plan_id_cuenta_egr=$idcodigo_egr;
					$plan_id_cuenta_egr=$idcodigo_ingr;
					while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
					{
						if ($rowS['plan_cuentas_codigo']==$idcodigo_inv)
						{
							$plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
						}
					}
					
					$sql="SELECT cuenta_contable from enlaces_compras where tipo = 'inventario-mercaderi' and 
					id_empresa = '".$sesion_id_empresa."'";
					
					$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
					$idcodigo_inv=0;
					while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
					{
						$plan_id_cuenta_inventario=$rowS['cuenta_contable'];
					}
											
					if ($tot_ventas!=0)
					{
					//	echo "entro";
						$lin_diario=$lin_diario+1;
						$idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
					
						$debeVector[$lin_diario]=$tot_ventas;
						$haberVector[$lin_diario]=0;						
					}
					
					$lin_diario=$lin_diario+1;
					$idPlanCuentas[$lin_diario]= $plan_id_cuenta_egr;
					
					$debeVector[$lin_diario]=0;
					$haberVector[$lin_diario]=$tot_ventas;
 */
		//		ECHO "ANTES/";
		$resp2=0;
				$resp2=DiarioDetalle($id_libro_diario,$sesion_id_periodo_contable);	
				//	echo "	va a crear CostoS=".$tot_costo;
				
				}

			
			    
			    
			    
			    
			    
			    
			    
			    
				$detalle="Transferencia"; 

				$sqlki="Select max(id_kardes) From kardes";
				$resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
				$id_kardes='0';
				while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
				{
					$id_kardes=$rowki['max(id_kardes)'];
				}
				$id_kardes++;
				$sqlk="insert into kardes (id_kardes, fecha, detalle,  id_factura,id_empresa) values
				('".$id_kardes."','".$fecha."','".$detalle."','".$id_ingreso."', '".$sesion_id_empresa."')";
				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				if($result && $resp2)
				{
					?> <div class='alert alert-success'><p>Registro guardado correctamente.</p></div> <?php
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
	}
}


	


