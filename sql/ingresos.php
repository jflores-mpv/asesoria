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
			//	$fecha_venta= date("Y-m-d h:i:s");
		    $fecha= $_POST['txtFechaFVC'];
				
			$observacion=$_POST['txtObservacion'];
			$estado = "Activo";
						
			$total=$_POST['txtSubtotal_EI'];
			
			
			$checkSaldoInicial=$_POST['checkSaldoInicial'];
			
			$txtDescripcion=$_POST['txtDescripcionFVC'];
			
			$txtContadorFilas=$_POST['txtContadorFilas'];
			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
			$cmbCosto=$_POST['cmbCosto'];
       		
// 			echo "contador=".$txtContadorFilas;
		
			if( $checkSaldoInicial=='saldo' )
			{
				$saldoInicial="Si";	
			    
			}else if( $checkSaldoInicial=='trans' ){
				    $saldoInicial="trans";
				}
			else
			{
				$saldoInicial = "No";}
				
				
				
			
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
				$sqlm1="Select max(id_ingreso) From ingresos;";
				$resultm1=mysql_query($sqlm1)  or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_ingreso=0;
				while($rowm1=mysql_fetch_array($resultm1))//permite ir de fila en fila de la tabla
				{
					$id_ingreso=$rowm1['max(id_ingreso)'];
				}
				$id_ingreso++;

				$sql="insert into ingresos (id_ingreso, fecha, estado, total, sub_total, numero, 
				fecha_anulacion, descripcion, id_iva, id_empresa,id_cuenta, tipo_documento, observacion) 
				values ('".$id_ingreso."','".$fecha."','".$estado."','".$total."','".$sub_total."','".$numero."',
				NULL,'".$txtDescripcion."', '".$txtIdIva."', '".$sesion_id_empresa."',
				NULL,'".$cmbTipoDocumentoFVC."', '".$observacion."');";
		//echo $sql;
				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
                $idBodega=$_POST['cmbBodegas'];
				for($i=1; $i<=$txtContadorFilas; $i++)
				{
					if($_POST['txtIdServicio'.$i] >= 1)
					{ //verifica si en el campo esta agregada una cuenta
						//permite sacar el id maximo de detalle_libro_diario
						try 
						{
							$sqli="Select max(id_detalle_ingreso) From detalle_ingresos";
							$result=mysql_query($sqli) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
							$id_detalle_ingreso=0;
							while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
							{
							$id_detalle_ingreso=$row['max(id_detalle_ingreso)'];
							}
							$id_detalle_ingreso++;

						}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

						$cantidad = $_POST['txtCantidadS'.$i];
						$idServicio = $_POST['txtIdServicio'.$i];
						$idKardex = $_POST['txtIdServicio'.$i];
						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						$valorTotal = $_POST['txtValorTotalS'.$i];
						$codProducto2 = $_POST['txtCodigoServicio'.$i];
						$bodegaOrigen = $_POST['bodegaOrigen'.$i];
						$txtPrecioCosto = $_POST['txtPrecioS'.$i];
						
				// 		echo $idBodega."</br>";
						
						
				// 		if($idBodega == NULL){
				// 		    $idBodega = '0';
				// 		}else{
				// 		    $idBodega = $_POST['cmbBodegas'.$i];
				// 		}
						
			
						$id_tipoP1 ="";
						
						$id_tipoP = $_POST['txtTipo'.$i];
						$id_tipoP1 = $_POST['txtTipo'.$i];
					
			        	if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
						{
								$id_tipoP1 = "P";
						}
					
						$sql2 = "insert into detalle_ingresos (id_detalle_ingreso,bodega, cantidad, estado, v_unitario, v_total, 					
						id_ingreso, id_producto,tipo_movim, id_empresa) values
						
						('".$id_detalle_ingreso."','".$idBodega."','".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."',
						'".$id_ingreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";
				
						$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
							
						//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
												

						if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
						{
						    
						//	echo "act";
							$sql3="update productos set costo='".$txtPrecioCosto."', stock=stock+'".$cantidad."' where id_empresa='".$sesion_id_empresa."' and codigo='".$codProducto2."';";
						//	echo $sql3;
							$resp3 = mysql_query($sql3) or die('<div class="transparent_ajax_error"><p>Error al actualizar existencia '.mysql_error().' </p></div>  ');
							
	if($saldoInicial=="trans"){
	   
    	    $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$bodegaOrigen."' and idProducto='".$codProducto2."' ";
            $resultado = mysql_query($stockBodegas);
            while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
			{
				$idCantBodegas=$rowBodegas1['id'];
				$cantidad3=$rowBodegas1['cantidad'];
			}
			
			$cantidadtransferencia=$cantidad3-$cantidad;
			$sqlbodegasTrans="UPDATE `cantBodegas` set `cantidad`='".$cantidadtransferencia."' WHERE idProducto='".$codProducto2."' and id='".$idCantBodegas."'";
    	    $resultBodegasTrans=mysql_query($sqlbodegasTrans) or die("\nError actualizar en bodega: ".mysql_error());
	   
	   if($resultBodegasTrans){
	       
	   
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
	    
        
	}else{
	   
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
				}
				if ( $saldoInicial=="No" )
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

			
			  // GUARDAR EN KARDEX
			  	if ($saldoInicial=="Si")
				{ $detalle="Saldo Inicial";	}
				else if ($saldoInicial=="trans")
				{ $detalle="Transferencia"; }
				else 
				{ $detalle="Ingreso"; }
			
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

if($accion == 2)
{
try
	{	
	    $checkSaldoInicial=$_POST['checkSaldoInicial'];
	    	if( $checkSaldoInicial=='saldo' )
			{
				$saldoInicial="Si";	
			    
			}else if( $checkSaldoInicial=='trans' ){
				    $saldoInicial="trans";
				}
			else
			{
				$saldoInicial = "No";}
				
		$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
		$observacion=$_POST['txtObservacion'];
		$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
		$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
		$iva=0;
		while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
		{
			$iva=$rowIva1['iva'];
			$txtIdIva=$rowIva1['id_iva'];
			$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
		}
		
		$idBodega = $_POST['cmbBodegas'];
        $NumeroEgreso = $_POST['txtNumeroEgreso'];
		$txtFecha = $_POST['txtFechaFVC'];
        $contador_filas = $_POST['txtContadorFilas'];
	//	echo "contadofila11111=".$contador_filas;
        $txtObservacion = ucwords($_POST['txtObservacion']);
		$txtSubtotal_EI = $_POST['txtSubtotal_EI'];
		
        $txtPeriodoContable2 = $sesion_id_periodo_contable;
		//txtContadorAsientosAgregadosFVC
	    $txtContadorAsientosAgregados2 = $_POST['txtContadorAsientosAgregadosFVC'];

		$sql2 = "update ingresos set observacion='".$txtObservacion."', fecha='".$txtFecha."', total=".$txtSubtotal_EI." ,id_iva='".$txtIdIva."' where id_empresa='".$sesion_id_empresa."' and numero='".$NumeroEgreso."'";
       // echo $sql2;
        $result2=mysql_query($sql2) or die(mysql_error());
		
		//elimina los detalles del egreso para volver a guardarlos
		$sql2="select id_ingreso from ingresos where id_empresa='".$sesion_id_empresa."' and numero='".$NumeroEgreso."'";
	//	echo $sql2;     
		$resultadoI=mysql_query($sql2) or die(mysql_error());
			
		while($rowI=mysql_fetch_array($resultadoI) )
		{
			$id_ingreso=$rowI['id_ingreso'];
		}
		
        //elimina los detalles del egreso para volver a guardarlos
		$sql2="select detalle_ingresos.*, productos.codigo from detalle_ingresos INNER JOIN productos on detalle_ingresos.id_producto = productos.id_producto where detalle_ingresos.id_empresa='".$sesion_id_empresa."' and id_ingreso='".$id_ingreso."'";
		// echo $sql2;     
		$resultado=mysql_query($sql2) or die(mysql_error());
		
		while($row=mysql_fetch_array($resultado) )
		{
			$id_tipoP=$row['tipo_movim'];
			$id_producto=$row['id_producto'];
			$cod_producto=$row['codigo'];
			$cantidad=$row['cantidad'];
			$bodegaDetalle= $row['bodega'];
				
			$sql2="SELECT `id`, `idBodega`, `idProducto`, `cantidad` FROM `cantBodegas` WHERE idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$resultadoBod=mysql_query($sql2);
			$stock = 0;
			$stock_act=0;
			while($rowBod=mysql_fetch_array($resultadoBod))//permite ir de fila en fila de la tabla
				{
					$stock =$rowBod['cantidad'];
				}
                
			$stock_act=$stock-$cantidad;
			$sql221="update cantBodegas set cantidad = '".$stock_act."' where idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$result221=mysql_query($sql221) or die("\nError al actualizar el stock de cantBodegas: ".mysql_error());
		}
		$sqlelimina2 = "Delete From detalle_ingresos where id_ingreso='".$id_ingreso."';";
        $resultelimina2=mysql_query($sqlelimina2);

		for($i=1; $i<=$contador_filas; $i++)
		{
            if($_POST['txtCodigoServicio'.$i] != "")   //verifica si en el campo esta agregada una cuenta
            {         
				$id_producto=$_POST['txtIdServicio'.$i];
				$cantidad=$_POST['txtCantidadS'.$i];
				$valor_unitario=$_POST['txtValorUnitarioS'.$i];
				$valor_total=$_POST['txtValorTotalS'.$i];
				$codigo_servicio = $_POST['txtCodigoServicio'.$i];
				$idServicio = $_POST['txtIdServicio'.$i];
				$idKardex = $_POST['txtIdServicio'.$i];
				$valorUnitario = $_POST['txtValorUnitarioS'.$i];
				$valorTotal = $_POST['txtValorTotalS'.$i];
				$cuentaDestino=$_POST['txtCuentaS'.$i];

				$id_tipoP1 ="";
						
				$id_tipoP = $_POST['txtTipo'.$i];
				$id_tipoP1 = $_POST['txtTipo'.$i];
					
			    if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
				{
					$id_tipoP1 = "P";
				}
				
				$sql2="insert into detalle_ingresos ( cantidad,bodega,estado,
					v_unitario, v_total, id_ingreso, id_producto,tipo_movim,id_empresa) 
					values ('".$cantidad."','".$idBodega."','Activo','".$valorUnitario."','".
					$valorTotal."','".$id_ingreso."','".$id_producto."','".$id_tipoP1."','".$sesion_id_empresa."');";
				$result2=mysql_query($sql2) or die("\nError al guardar detalles compra Fila 2: ".mysql_error());

				if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
				{
					$stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$idBodega."' and idProducto='".$codigo_servicio."' ";
					$resultado = mysql_query($stockBodegas);
					while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
					{
						$id=$rowBodegas1['id'];
						$cantidad3=$rowBodegas1['cantidad'];
					}
					$fila=mysql_num_rows($resultado);
					if ($fila>0){
						
						$cantidadBodega = $cantidad3+$cantidad;
						
						$sqlbodegas="UPDATE `cantBodegas` set `cantidad`='".$cantidadBodega."' WHERE idProducto='".$codigo_servicio."' and id='".$id."'";
						$resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
						
					}else{
						
						$cantidadBodega = $cantidad3+$cantidad;
						
						$sqlbodegas="INSERT INTO `cantBodegas`(`idBodega`, `idProducto`, `cantidad`) 
						VALUES ('".$idBodega."','".$codigo_servicio."','".$cantidadBodega."')";
						
						$resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
					}

				}
				

				
			}
		}	
		
	$sqlBuscarKardes="SELECT `id_kardes`, `fecha`, `detalle`, `cantidad`, `bodegaInventario`, `total`, `id_factura`, `id_empresa` FROM `kardes` WHERE id_factura='".$id_ingreso."' AND detalle='Ingreso' AND id_empresa =$sesion_id_empresa ;";
		$resultBuscarKardes= mysql_query($sqlBuscarKardes);
		$numFilasKardes = mysql_num_rows($resultBuscarKardes);
		if($numFilasKardes==0){
		    	$sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
				('".$txtFecha."','Ingreso','".$id_ingreso."', '".$sesion_id_empresa."')";
		}else{
		    $sqlk="UPDATE kardes SET fecha='".$txtFecha."' WHERE id_factura='".$id_ingreso."' AND detalle='Ingreso';";
		}
		
// 		 $sqlk="UPDATE kardes SET fecha='".$txtFecha."' WHERE id_factura='".$id_ingreso."' AND detalle='Ingreso';";
		$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());



		if ( $saldoInicial=="No"  )
		{
			//permite sacar el numero_asiento de libro_diario
		    $tot_costo=0;
			$descripcion=$observacion." ".$cmbTipoDocumentoFVC."#".$NumeroEgreso." realizado por ".$sesion_empresa_nombre;
			try
			{
			 $sqldes = "update libro_diario set descripcion='".$descripcion."', fecha='".$txtFecha."', total_debe='".$txtSubtotal_EI."', 
			 total_haber='".$txtSubtotal_EI."' where id_periodo_contable='".$sesion_id_periodo_contable."' and tipo_mov='I' and numero_cpra_vta='".$NumeroEgreso."';";
		//	 echo $sqldes;
             $resultdes=mysql_query($sqldes) or die("\nError al actualizar libro diario ".mysql_error());
			 //echo "sqldes".$sqldes;
			}
			catch
			(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error al actualizar libro_diario: <?php echo "".$ex ?></p></div> <?php }


			$sql2="select id_libro_diario, id_comprobante from libro_diario where id_periodo_contable='".$sesion_id_periodo_contable."' and numero_cpra_vta='".$NumeroEgreso."' and tipo_mov='I'";
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

			$resp2=0;

			$resp2=DiarioDetalle($id_libro_diario,$sesion_id_periodo_contable);	
            
            if($resultelimina && $resp2)
				{	
					
							?> <div class='alert alert-success'><p>Registros modificados correctamente.</p></div> <?php

				}
				else 
				{ ?> <div class='alert alert-error'><p>Error al actualiza la tabla detalle_libro_diario. </p></div> <?php }
		
		}else{
		  if( $sqlk)
			{	
				
	?> <div class='alert alert-success'><p>Registros modificados correctamente.</p></div> <?php
			}  
		}
			
	}
	catch (Exception $e) 
	{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}
if($accion == 3)
{
try
	{
	    
        $NumeroEgreso = $_POST['id'];
        $txtPeriodoContable2 = $sesion_id_periodo_contable;
		$sql2="select id_ingreso from ingresos where id_empresa='".$sesion_id_empresa."' and numero='".$NumeroEgreso."'";   
		$resultado=mysql_query($sql2) or die(mysql_error());
			
		while($row=mysql_fetch_array($resultado) )
		{
			$id_ingreso=$row['id_ingreso'];
		}
		$sql2="select detalle_ingresos.*, productos.codigo from detalle_ingresos INNER JOIN productos on detalle_ingresos.id_producto = productos.id_producto where detalle_ingresos.id_empresa='".$sesion_id_empresa."' and id_ingreso='".$id_ingreso."'";
		// echo $sql2;     
		$resultado=mysql_query($sql2) or die(mysql_error());
		
		while($row=mysql_fetch_array($resultado) )
		{
			$id_tipoP=$row['tipo_movim'];
			$id_producto=$row['id_producto'];
			$cod_producto=$row['codigo'];
			$cantidad=$row['cantidad'];
			$bodegaDetalle= $row['bodega'];
				
			$sql2="SELECT `id`, `idBodega`, `idProducto`, `cantidad` FROM `cantBodegas` WHERE idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$resultadoBod=mysql_query($sql2);
			$stock = 0;
			$stock_act=0;
			while($rowBod=mysql_fetch_array($resultadoBod))//permite ir de fila en fila de la tabla
				{
					$stock =$rowBod['cantidad'];
				}
                
			$stock_act=$stock-$cantidad;
			$sql221="update cantBodegas set cantidad = '".$stock_act."' where idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$result221=mysql_query($sql221) or die("\nError al actualizar el stock de cantBodegas: ".mysql_error());
		}
		
		$sqlelimina2 = "Delete From detalle_ingresos where id_ingreso='".$id_ingreso."';";
        $resultelimina2=mysql_query($sqlelimina2);

		$sqlelimina2 = "Delete From ingresos where id_ingreso='".$id_ingreso."';";
        $resultelimina2=mysql_query($sqlelimina2);
    	
		
		
		 $sqlk="DELETE FROM kardes WHERE id_factura='".$id_ingreso."' AND detalle='Ingreso' AND id_empresa=$sesion_id_empresa";
		$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());

	
			 $sql2="select id_libro_diario, id_comprobante from libro_diario where id_periodo_contable='".$sesion_id_periodo_contable."' and numero_cpra_vta='".$NumeroEgreso."' and tipo_mov='I' ";
		//	echo $sql2;     
			$resultado=mysql_query($sql2) or die(mysql_error());
			$id_libro_diario=0;
			while($row=mysql_fetch_array($resultado) )
			{
				$id_libro_diario = $row['id_libro_diario'];
				$id_comprobante = $row['id_comprobante'];
			}
			if ( trim($id_libro_diario)!='' )
		{			
			 $sqldes1 = "DELETE FROM libro_diario  where id_libro_diario=$id_libro_diario AND id_periodo_contable=$sesion_id_periodo_contable";
            $resultdes=mysql_query($sqldes1) or die("\nError al actualizar libro diario ".mysql_error());

			$sqldes = "DELETE FROM comprobantes  where id_comprobante=$id_comprobante AND id_empresa=$sesion_id_empresa";
            $resultdes=mysql_query($sqldes) or die("\nError al actualizar libro diario ".mysql_error());
				
			$sqlelimina = "Delete From detalle_libro_diario where id_libro_diario='".$id_libro_diario."';";
            $resultelimina=mysql_query($sqlelimina);
	        
	        if($resultdes)
				{
					?> <div class='alert alert-success'><p>Registro eliminado correctamente.</p></div> <?php
				}
				else
				{
					?> <div class='transparent_ajax_error'><p>Error al eliminar los datos: Revise que haya ingresado todos los datos correctamente. <?php echo " ".mysql_error(); ?>;</p></div> <?php
				}
		
		}else{
		    if($sqlk)
				{
					?> <div class='alert alert-success'><p>Registro eliminado correctamente.</p></div> <?php
				}
				else
				{
					?> <div class='transparent_ajax_error'><p>Error al eliminar los datos: Revise que haya ingresado todos los datos correctamente. <?php echo " ".mysql_error(); ?>;</p></div> <?php
				}
		}
	}
	catch (Exception $e) 
	{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}
	
if($accion == "44")
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
            productos.`producto` AS productos_nombre,
            productos.`costo` AS productos_costo,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
			productos.`codigo` AS productos_codigo,
			productos.`tipos_compras` AS productos_tipos_compras,
			categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
			productos.`id_empresa` AS productos_id_empresa
        FROM
            `categorias`categorias INNER JOIN `productos` productos 
			ON categorias.`id_categoria` = productos.`id_categoria`
            WHERE productos.`id_empresa`='".$sesion_id_empresa."' and 
			CONCAT(productos.`id_producto`, productos.`producto`) LIKE '%$queryString%'  LIMIT 20; ";

            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) 
			{
                if($numero_filas >0)
				{
                    echo "<table id='tblServicios".$cont."' class='lista' border='0' >";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th style='padding-left: 5px; padding-right: 5px;'>Código</th>  <th style='padding-left: 5px; padding-right: 5px;'>Nombre</th>  <th style='padding-left: 5px; padding-right: 5px;'>Categoria</th>  <th style='padding-left: 5px; padding-right: 5px;'>Unidad</th>  <th style='padding-left: 5px; padding-right: 5px;'>Tipo Servicio</th>  <th style='padding-left: 5px; padding-right: 5px;'><a href='javascript: fn_cerrar_div();'><img align='right' src='images/cerrar2.png' width='16' height='16' alt='cerrar' title='Cerrar' /></a></th>";
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
//  echo '<tr onClick="fill10(\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["servicios_codigo"]."*".$row["servicios_nombre"]." "."*".$row["servicios_precio_venta1"]."*".$id_iva."*".$iva.'\');" style="cursor: pointer" title="Clic para seleccionar">';
						//$tipoProducto='P';
  echo '<tr onClick="fill10_egreso(\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["productos_id_producto"]."*".$row["productos_nombre"]." "."*".$row["productos_costo"]."*".$id_iva."*".$iva."*".$row["productos_tipos_compras"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
                                
				//   "*".$row["servicios_codigo"]."*".$row["servicios_nombre"]." ".$row["categorias_categoria"]." ".$row["unidades_nombre"]." ".$row["tipo_servicios_nombre"]."*".$row["servicios_precio_venta1"]."*".$row["servicios_precio_venta2"]."*".$row["servicios_precio_venta3"]."*".$row["servicios_precio_venta4"]."*".$row["servicios_precio_venta5"]."*".$row["servicios_precio_venta6"]."*".$row["servicios_id_iva"].
                            echo "<td>".$row["productos_id_producto"]."</td>";
                            echo "<td>".$row["productos_nombre"]."</td>";
							echo "<td>".$row["categorias_categoria"]."</td>";
                      		echo "<td>".$iva."</td>";
	                        echo "<td></td>";
							echo "<td>".$row["productos_tipos_compras"]."</td>";
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

