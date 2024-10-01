
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
	$sesion_id_usuario = $_SESSION["sesion_id_usuario"];
 
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

	function guardaKardex($fecha,$id_operacion,$sesion_id_empresa, $detalle){

				$sqlki="Select max(id_kardes) From kardes";
				$resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
				$id_kardes='0';
				while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
				{
					$id_kardes=$rowki['max(id_kardes)'];
				}
				$id_kardes++;
				 $sqlk="insert into kardes (id_kardes, fecha, detalle,  id_factura,id_empresa) values
				('".$id_kardes."','".$fecha."','$detalle','".$id_operacion."', '".$sesion_id_empresa."')";
				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
	}

function impuestos($sesion_id_empresa){
	$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
	$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
	$respuesta= array();
	while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
	{
		$respuesta['iva']=$rowIva1['iva'];
		$respuesta['id_iva']=$rowIva1['id_iva'];
		$respuesta['id_plan_cuenta']=$rowIva1['id_plan_cuenta'];
	}
	return $respuesta;
}


	if($accion == "1")
{
	
	$fecha= $_POST['txtFechaFVC'];
	$estado= '0';
	$total= $_POST['txtTotalFVC'];
	$sub_total= $_POST['txtSubtotal_EI'];
	$numero= '0';
	$descripcion= '';
	$id_iva= '0';
	$id_cuenta= '0';
	$tipo_documento= '0';
	$observacion= '';
	$num_trans = $_POST['txtNumeroEgreso'];
	$id_ingreso= 0;
	$id_egreso=0;
	$id_usuario = '';
    $cmbTipoDocumentoFVC='';
    $observacion='';

    $estado = "Activo";

    $total=$_POST['txtSubtotal_EI'];
    $txtDescripcion=$_POST['txtDescripcionFVC'];
    $txtContadorFilas=$_POST['txtContadorFilas'];
    $txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
    $cmbCosto=$_POST['cmbCosto'];
    

    if($numero !="" )
    {      
		$infoImpuestos = impuestos($sesion_id_empresa);   
		$iva=$infoImpuestos['iva'];
		$txtIdIva=$infoImpuestos['id_iva'];
		$impuestos_id_plan_cuenta=$infoImpuestos['id_plan_cuenta'];
    }

    if($numero !=""  )
    {       
        $sqlm1="Select max(id_egreso) as idEgreso, max(numero) as numeroEgreso From egresos where id_empresa ='".$sesion_id_empresa."'  ;";
        $resultm1=mysql_query($sqlm1) ;
        $id_egreso=0;
		$numero_egreso=0;
        while($rowm1=mysql_fetch_array($resultm1))
        {
			$numero_egreso=$rowm1['numeroEgreso'];
        }
		$numero_egreso++;
		
 		  $sql="insert into egresos (   fecha,           estado,    total,     sub_total,           numero, 	fecha_anulacion, descripcion, id_iva, id_empresa,id_cuenta, tipo_documento, observacion) 
           values ('".$fecha."','".$estado."','".$total."','".$sub_total."','".$numero_egreso."',NULL,'".$txtDescripcion."', '".$txtIdIva."', '".$sesion_id_empresa."',NULL,'".$cmbTipoDocumentoFVC."', '".$observacion."');";
        $result=mysql_query($sql) ;
        $id_egreso=mysql_insert_id();

        $txtBodega=$_POST['cmbBodegas'];
        for($i=1; $i<=$txtContadorFilas; $i++)
        {
            if($_POST['txtIdServicio'.$i] >= 1)
            { 
                $cantidad = $_POST['txtCantidadS'.$i];
                $idServicio = $_POST['txtIdServicio'.$i];
                $idKardex = $_POST['txtIdServicio'.$i];
                $valorUnitario = $_POST['txtValorUnitarioS'.$i];
                $valorTotal = $_POST['txtValorTotalS'.$i];
				$codigoServicio = $_POST['txtCodigoServicio'.$i];
				$bodegaOrigen = $_POST['bodegaOrigen'.$i];
				$bodegaDestino = $_POST['cmbBodegas'];
                $idBodega = $_POST['idBodega'.$i];
                $cuentaOrigen=$_POST['txtCuentaS'.$i];
                $id_tipoP1 ="";
                $id_tipoP = $_POST['txtTipo'.$i];
                $id_tipoP1 = $_POST['txtTipo'.$i];
                
                if ($id_tipoP == "1")
                    {
                        $id_tipoP1 = "P";
                    }
                
               $sql2 = "insert into detalle_egresos ( cantidad, bodega, cuentaOrigen,estado, v_unitario, v_total, 					
                id_egreso, id_producto,tipo_movim, id_empresa) values
                ('".$cantidad."','".$bodegaOrigen."','".$cuentaOrigen."','".$estado."','".$valorUnitario."','".$valorTotal."',
                '".$id_egreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";
            //	echo "grabar detalle";
            // 	echo $sql2;
                $resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
                    
                //******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************

                if ($id_tipoP == "P"  or $id_tipoP == "Inventario" or $id_tipoP == "I" or $id_tipoP == "1" )
                {
                //	echo "act";
                // echo    $sql3="update productos set stock=stock-'".$cantidad."' where 
                //     id_empresa='".$sesion_id_empresa."' and id_producto='".$idServicio."' ;";
                
                    // $resp3 = mysql_query($sql3) or die('<div class="alert alert-warning">No existen stock para egresar</div>  ');

					//egreso
				
				     $sqlB="UPDATE `cantBodegas` SET  cantidad=cantidad-'".$cantidad."' WHERE `idProducto`='".$codigoServicio."' and `idBodega`='".$bodegaOrigen."' ;";
                
                    $respB = mysql_query($sqlB) ;

					//ingreso
					
					$sqlBod="SELECT `id` FROM `cantBodegas` WHERE `idProducto`='".$codigoServicio."' and  `idBodega`='".$bodegaDestino."'";
					$resultsqlBod= mysql_query($sqlBod);
					$existeEnBodega=  mysql_num_rows($resultsqlBod);

					if($existeEnBodega==0){
						// echo '<br>';
				   $sqlI="INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`) VALUES ('".$bodegaDestino."' ,'".$codigoServicio."','".$cantidad."');";
						
					}else{
						// echo '<br>';
			  $sqlI="UPDATE `cantBodegas` SET  cantidad=cantidad+'".$cantidad."' WHERE `idProducto`='".$codigoServicio."' and `idBodega`='".$bodegaDestino."' ;";             
					
					}

					$respI = mysql_query($sqlI) ;
					
                }
                
            }
        }// fin del bucle que pasa por todas las filas




    
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
	$todoBienEgresos = false;
        if($result && $resp2)
        {
			$todoBienEgresos = true;
        }
        else
        {
			$todoBienEgresos = false;
        }
    }
    else
    {
        ?> <div class='transparent_ajax_error'><p>Error: No ha ingresado suficiente datos de factura <?php echo " ".mysql_error(); ?>;</p></div> <?php
    }
	


//FIN EGRESOS

//INICIO INGRESOS

			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
			$numero=$_POST['txtNumeroEgreso'];
			//	$fecha_venta= date("Y-m-d h:i:s");
		    $fecha= $_POST['txtFechaFVC'];
				
			$observacion=$_POST['txtObservacion'];
			$estado = "Activo";
						
			$total=$_POST['txtSubtotal_EI'];
			$txtDescripcion=$_POST['txtDescripcionFVC'];
			
			$txtContadorFilas=$_POST['txtContadorFilas'];
			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
			$cmbCosto=$_POST['cmbCosto'];
       		
			//echo "contador=".$txtContadorFilas;
			
			if( ($_POST['checkSaldoInicial'] == true) or ($_POST['checkSaldoInicial'] == "ON") )
			{
				$saldoInicial="Si";	}
			else
			{
				$saldoInicial = "No";}
			
		

			if($numero !=""  )
			{       
				//echo "entro a cliente";
				$sqlm1="Select max(id_ingreso) as id , max(numero) as numero From ingresos where 	id_empresa ='".$sesion_id_empresa."'  ;";
				$resultm1=mysql_query($sqlm1)  or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_ingreso=0;
				$numero_ingreso=0;
				while($rowm1=mysql_fetch_array($resultm1))//permite ir de fila en fila de la tabla
				{
				// 	$id_ingreso=$rowm1['id'];
					$numero_ingreso=$rowm1['numero'];
				}
				$numero_ingreso++;
				// $id_ingreso++;
			
				 $sql="insert into ingresos (fecha, estado, total, sub_total, numero, 
				fecha_anulacion, descripcion, id_iva, id_empresa,id_cuenta, tipo_documento, observacion) 
				values ('".$fecha."','".$estado."','".$total."','".$sub_total."','".$numero_ingreso."',
				NULL,'".$txtDescripcion."', '".$txtIdIva."', '".$sesion_id_empresa."',
				NULL,'".$cmbTipoDocumentoFVC."', '".$observacion."');";
		
		$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
		$id_ingreso = mysql_insert_id();
$txtBodega=$_POST['cmbBodegas'];
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
						
						$idBodega = $_POST['idBodega'.$i]; 
						if($idBodega == NULL){
						    $idBodega = '0';
						}else{
						    $idBodega = $_POST['idBodega'.$i];
						}
						
			
						$id_tipoP1 ="";
						
						$id_tipoP = $_POST['txtTipo'.$i];
						$id_tipoP1 = $_POST['txtTipo'.$i];
					
			        	if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
						{
								$id_tipoP1 = "P";
						}
					
		
				
						$sql2 = "insert into detalle_ingresos (id_detalle_ingreso,bodega, cantidad, estado, v_unitario, v_total, 					
						id_ingreso, id_producto,tipo_movim, id_empresa) values
						('".$id_detalle_ingreso."','".$txtBodega."','".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."',
						'".$id_ingreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";
				

				//	echo "grabar detalle";
						//echo $sql2;
						$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas 22: '.mysql_error().' </p></div>  ');
							
						//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
				
						
					}
				}
		

			
			  // GUARDAR EN KARDEX
			  	if ($saldoInicial=="Si")
				{ $detalle="Saldo Inicial";	}
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
				// echo '<br>';
				 $sqlk="insert into kardes (id_kardes, fecha, detalle,  id_factura,id_empresa) values
				('".$id_kardes."','".$fecha."','".$detalle."','".$id_ingreso."', '".$sesion_id_empresa."')";
				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				$todoBienIngresos  =false;
				if($result && $resp2)
				{
					$todoBienIngresos  =true;
				}
				else
				{
					$todoBienIngresos  =false;
				}
			}
			else
			{
				?> <div class='transparent_ajax_error'><p>Error: No ha ingresado suficiente datos de factura <?php echo " ".mysql_error(); ?>;</p></div> <?php
			}
	

//FIN INGRESOS
		


	  $sqlTrasferencia="INSERT INTO `transferencias`( `id_empresa`, `num_trans`, `id_ingreso`, `id_egreso`, `fecha_trans`, `id_usuario`) VALUES 
	  ('".$sesion_id_empresa."','".$num_trans."','".$id_ingreso."','".$id_egreso."','".$fecha."','".$sesion_id_usuario."')";
	$resultDetalleTransferencia=mysql_query($sqlTrasferencia);
$id_transferencia = mysql_insert_id();
if($resultDetalleTransferencia &&$todoBienIngresos && $todoBienEgresos){
		?> <div class='alert alert-success'><p>Registro guardado correctamente.</p></div> <?php
	}else {
		?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Revise que haya ingresado todos los datos correctamente. <?php echo " ".mysql_error(); ?>;</p></div> <?php
	}

                 $detalle="Transferencia"; 
			
//echo $id_venta;
				$sqlki="Select max(id_kardes) From kardes";
				$resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
				$id_kardes='0';
				while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
				{
					$id_kardes=$rowki['max(id_kardes)'];
				}
				$id_kardes++;
				// echo '<br>';
				 $sqlk="insert into kardes (id_kardes, fecha, detalle,  id_factura,id_empresa) values
				('".$id_kardes."','".$fecha."','".$detalle."','".$id_transferencia."', '".$sesion_id_empresa."')";
				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				$todoBienIngresos  =false;
				if($result && $resp2)
				{
					$todoBienIngresos  =true;
				}
				else
				{
					$todoBienIngresos  =false;
				}
}

	
if($accion == "2")
{
	$fecha= $_POST['txtFechaFVC'];
	$estado= '0';
	$total= $_POST['txtTotalFVC'];
	$sub_total= $_POST['txtSubtotal_EI'];
	$numero= '0';
	$descripcion= '';
	$id_iva= '0';
	$id_cuenta= '0';
	$tipo_documento= '0';
	$observacion= '';
	$num_trans = $_POST['txtNumeroEgreso'];
	$id_ingreso= 0;
	$id_egreso=0;
	$id_usuario = '';
    $cmbTipoDocumentoFVC='';
    $observacion='';
    $estado = "Activo";
    $total=$_POST['txtSubtotal_EI'];
    $txtDescripcion=$_POST['txtDescripcionFVC'];
    $txtContadorFilas=$_POST['txtContadorFilas'];
    $txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
    $cmbCosto=$_POST['cmbCosto'];
	$txtObservacion = ucwords($_POST['txtObservacion']);

    if($num_trans !="" )
    {      
		$infoImpuestos = impuestos($sesion_id_empresa);   
		$iva=$infoImpuestos['iva'];
		$txtIdIva=$infoImpuestos['id_iva'];
		$impuestos_id_plan_cuenta=$infoImpuestos['id_plan_cuenta'];
    }

    if($num_trans !=""  )
    {       
	 $sqlTransferencia = "SELECT 
	transferencias.`id_transferencia`, 
	transferencias.`id_empresa`, 
	transferencias.`num_trans`, 
	transferencias.`id_ingreso`, 
	transferencias.`id_egreso`, 
	transferencias.`fecha_trans`, 
	transferencias.`id_usuario`, 
	ingresos.numero as numero_ingreso, 
	egresos.numero as numero_egreso 
	FROM `transferencias` 
	INNER JOIN ingresos ON ingresos.id_ingreso = transferencias.id_ingreso 
	INNER JOIN egresos ON egresos.id_egreso = transferencias.id_egreso 
	WHERE  transferencias.num_trans='".$num_trans."' AND transferencias.id_empresa='".$sesion_id_empresa."'";
	$resultTransferencia = mysql_query($sqlTransferencia);
	$numeroEgreso= '';
	$numeroIngreso= '';
	while($rowTrans = mysql_fetch_array($resultTransferencia) ){
		$id_ingreso = $rowTrans['id_ingreso'];
		$id_egreso = $rowTrans['id_egreso'];
		$numeroEgreso = $rowTrans['numero_egreso'];
		$numero_ingreso = $rowTrans['numero_ingreso'];
		$id_transferencia = $rowTrans['id_transferencia'];
	}

	$sql2 = "update egresos set observacion='".$txtObservacion."', fecha='".$fecha."', total=".$total." , sub_total=".$total." ,id_iva='".$txtIdIva."' where id_empresa='".$sesion_id_empresa."' and egresos.id_egreso='".$id_egreso."'";
    $result2=mysql_query($sql2) or die(mysql_error());

	$sql2="select detalle_egresos.*, productos.codigo from detalle_egresos INNER JOIN productos on detalle_egresos.id_producto = productos.id_producto where detalle_egresos.id_empresa='".$sesion_id_empresa."' and detalle_egresos.id_egreso='".$id_egreso."'";   
	$resultado=mysql_query($sql2) or die(mysql_error());
	while($row=mysql_fetch_array($resultado) )
		{
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
        $txtBodega=$_POST['cmbBodegas'];
        for($i=1; $i<=$txtContadorFilas; $i++)
        {
            if($_POST['txtIdServicio'.$i] >= 1)
            {
                $cantidad = $_POST['txtCantidadS'.$i];
                $idServicio = $_POST['txtIdServicio'.$i];
                $idKardex = $_POST['txtIdServicio'.$i];
                $valorUnitario = $_POST['txtValorUnitarioS'.$i];
                $valorTotal = $_POST['txtValorTotalS'.$i];
				$codigoServicio = $_POST['txtCodigoServicio'.$i];
				$bodegaOrigen = $_POST['idBodega'.$i];
				$bodegaDestino = $_POST['cmbBodegas'];

                $idBodega = $_POST['idBodega'.$i];
                $cuentaOrigen=$_POST['txtCuentaS'.$i];
                $id_tipoP1 ="";
                
                $id_tipoP = $_POST['txtTipo'.$i];
                $id_tipoP1 = $_POST['txtTipo'.$i];
                
                if ($id_tipoP == "1")
                    {
                        $id_tipoP1 = "P";
                    }

               $sql2 = "insert into detalle_egresos ( cantidad, bodega, cuentaOrigen,estado, v_unitario, v_total, 					
                id_egreso, id_producto,tipo_movim, id_empresa) values
                ('".$cantidad."','".$bodegaOrigen."','".$cuentaOrigen."','".$estado."','".$valorUnitario."','".$valorTotal."',
                '".$id_egreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";
                $resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles egresos: '.mysql_error().' </p></div>  ');

                if ($id_tipoP == "P"  or $id_tipoP == "Inventario" or $id_tipoP == "I" or $id_tipoP == "1" )
                {
    
				$sqlB="UPDATE `cantBodegas` SET  cantidad=cantidad-'".$cantidad."' WHERE `idProducto`='".$codigoServicio."' and `idBodega`='".$bodegaOrigen."' ;";
                    $respB = mysql_query($sqlB) ;

					//ingreso
					
					$sqlBod="SELECT `id` FROM `cantBodegas` WHERE `idProducto`='".$codigoServicio."' and  `idBodega`='".$bodegaDestino."'";
					$resultsqlBod= mysql_query($sqlBod);
					$existeEnBodega=  mysql_num_rows($resultsqlBod);

					if($existeEnBodega==0){
						// echo '<br>';
				   $sqlI="INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`) VALUES ('".$bodegaDestino."' ,'".$codigoServicio."','".$cantidad."');";
						
					}else{
						// echo '<br>';
			  $sqlI="UPDATE `cantBodegas` SET  cantidad=cantidad+'".$cantidad."' WHERE `idProducto`='".$codigoServicio."' and `idBodega`='".$bodegaDestino."' ;";             
					
					}

					$respI = mysql_query($sqlI) ;
					
                }
                
            }
        }// fin del bucle que pasa por todas las filas



		$sqlk="UPDATE kardes SET fecha='".$fecha."' WHERE id_factura='".$id_egreso."' AND detalle='Egreso' AND id_empresa=$sesion_id_empresa;";
        $result=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
    //	echo resp2;
	$todoBienEgresos = false;
        if($result && $resp2)
        {
			$todoBienEgresos = true;
        }
        else
        {
			$todoBienEgresos = false;
        }
		
//FIN EGRESOS

//INICIO INGRESOS

$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
$numero=$_POST['txtNumeroEgreso'];
//	$fecha_venta= date("Y-m-d h:i:s");
$fecha= $_POST['txtFechaFVC'];
	
$observacion=$_POST['txtObservacion'];
$estado = "Activo";
			
$total=$_POST['txtSubtotal_EI'];
$txtDescripcion=$_POST['txtDescripcionFVC'];

$txtContadorFilas=$_POST['txtContadorFilas'];
$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
$cmbCosto=$_POST['cmbCosto'];
   
//echo "contador=".$txtContadorFilas;

if( ($_POST['checkSaldoInicial'] == true) or ($_POST['checkSaldoInicial'] == "ON") )
{
	$saldoInicial="Si";	}
else
{
	$saldoInicial = "No";}



if( $numero_ingreso !=""  )
{       
	$sql2 = "update ingresos set observacion='".$observacion."', fecha='".$fecha."', total=".$total.", sub_total=".$total." ,id_iva='".$txtIdIva."' where id_empresa='".$sesion_id_empresa."' and numero='".$numero_ingreso."'";
	$result=mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al actualizar en ingresos: '.mysql_error().' </p></div>  ');

	$sql2="select detalle_ingresos.*, productos.codigo from detalle_ingresos INNER JOIN productos on detalle_ingresos.id_producto = productos.id_producto where detalle_ingresos.id_empresa='".$sesion_id_empresa."' and id_ingreso='".$id_ingreso."'";

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

	$txtBodega=$_POST['cmbBodegas'];
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
			
			$idBodega = $_POST['idBodega'.$i]; 
			if($idBodega == NULL){
				$idBodega = '0';
			}else{
				$idBodega = $_POST['idBodega'.$i];
			}
			

			$id_tipoP1 ="";
			
			$id_tipoP = $_POST['txtTipo'.$i];
			$id_tipoP1 = $_POST['txtTipo'.$i];
		
			if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" )
			{
					$id_tipoP1 = "P";
			}

			$sql2 = "insert into detalle_ingresos (bodega, cantidad, estado, v_unitario, v_total, 					
			id_ingreso, id_producto,tipo_movim, id_empresa) values
			('".$txtBodega."','".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."',
			'".$id_ingreso."', '".$idServicio."', '".$id_tipoP1."','".$sesion_id_empresa."' );";

			$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas 22: '.mysql_error().' </p></div>  ');
				
			//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
	
			
		}
	}



  // GUARDAR EN KARDEX
	  if ($saldoInicial=="Si")
	{ $detalle="Saldo Inicial";	}
	else
	{ $detalle="Ingreso"; }


	$sqlk="UPDATE kardes SET fecha='".$fecha."' WHERE id_factura='".$id_ingreso."' AND detalle='Ingreso' AND id_empresa=$sesion_id_empresa;";
	$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
	
	$todoBienIngresos  =false;
	if($result && $resp2)
	{
		$todoBienIngresos  =true;
	}
	else
	{
		$todoBienIngresos  =false;
	}
}
else
{
	?> <div class='transparent_ajax_error'><p>Error: No hay datos de ingresos<?php echo " ".mysql_error(); ?>;</p></div> <?php
}


//FIN INGRESOS

    }
    else
    {
        ?> <div class='transparent_ajax_error'><p>Error: No ha ingresado suficiente datos de factura <?php echo " ".mysql_error(); ?>;</p></div> <?php
    }




$sqlTrasferencia="UPDATE `transferencias` SET `fecha_trans`='".$fecha."',`id_usuario`='".$sesion_id_usuario."' WHERE id_transferencia=$id_transferencia AND id_empresa =$sesion_id_empresa";
$resultDetalleTransferencia=mysql_query($sqlTrasferencia);

if($resultDetalleTransferencia ){
		?> <div class='alert alert-success'><p>Transferencia actualizado correctamente.</p></div> <?php
	}else {
		?> <div class='transparent_ajax_error'><p>No se guardo correctamente la transferencia <?php echo " ".mysql_error(); ?>;</p></div> <?php
	}


	$sqlk="UPDATE kardes SET fecha='".$fecha."' WHERE id_factura='".$id_transferencia."' AND detalle='Transferencia' AND id_empresa=$sesion_id_empresa;";
	$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
	
}



	
if($accion == "3")
{
	$num_trans = $_POST['id'];
	$response[] = array();
	$response['eliminar_ingreso'] = 0;
	$response['eliminar_detalle_ingreso'] =0;
	$response['eliminar_kardex_ingreso'] =0;
	$response['eliminar_egreso'] = 0;
	$response['eliminar_detalle_egreso'] =0;
	$response['eliminar_kardex_egreso'] =0;
	$response['eliminar_transferencia'] = 0;
	$response['num_transferencia'] ='';
	$response['num_ingreso'] ='';
	$response['consultasSql'] ='';


    if($num_trans !=""  )
    {       
	//INICIO transferencia
	$sqlTransferencia = "SELECT 
	transferencias.`id_transferencia`, 
	transferencias.`id_empresa`, 
	transferencias.`num_trans`, 
	transferencias.`id_ingreso`, 
	transferencias.`id_egreso`, 
	transferencias.`fecha_trans`, 
	transferencias.`id_usuario`, 
	ingresos.numero as numero_ingreso, 
	egresos.numero as numero_egreso 
	FROM `transferencias` 
	INNER JOIN ingresos ON ingresos.id_ingreso = transferencias.id_ingreso 
	INNER JOIN egresos ON egresos.id_egreso = transferencias.id_egreso 
	WHERE  transferencias.num_trans='".$num_trans."' AND transferencias.id_empresa='".$sesion_id_empresa."'";
	$response['consultasSql'][] =$sqlTransferencia;
	$resultTransferencia = mysql_query($sqlTransferencia);
	$numeroEgreso= '';
	$numeroIngreso= '';
	while($rowTrans = mysql_fetch_array($resultTransferencia) ){
		$id_ingreso = $rowTrans['id_ingreso'];
		$id_egreso = $rowTrans['id_egreso'];
		$numeroEgreso = $rowTrans['numero_egreso'];
		$numeroIngreso = $rowTrans['numero_ingreso'];
		$id_transferencia = $rowTrans['id_transferencia'];
	}

	$sql2="select detalle_egresos.*, productos.codigo from detalle_egresos INNER JOIN productos on detalle_egresos.id_producto = productos.id_producto where detalle_egresos.id_empresa='".$sesion_id_empresa."' and detalle_egresos.id_egreso='".$id_egreso."'";  
	$response['consultasSql'][] =$sql2; 
	$resultado=mysql_query($sql2);
	while($row=mysql_fetch_array($resultado) )
		{
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
	$sqlEliminarDetalleEgresos = "DELETE FROM detalle_egresos where id_egreso='".$id_egreso."';";
    $resultEliminarDetalleEgresos=mysql_query($sqlEliminarDetalleEgresos);
	$response['consultasSql'][] =$sqlEliminarDetalleEgresos; 
	if($resultEliminarDetalleEgresos){
		$response['eliminar_detalle_egreso'] =1;
		$sqlEgresos = "DELETE FROM  egresos  where id_empresa='".$sesion_id_empresa."' and egresos.id_egreso='".$id_egreso."'";
		$response['consultasSql'][] =$sqlEgresos; 
		$resultEgresos=mysql_query($sqlEgresos) ;
		if($resultEliminarDetalleEgresos){
			$response['eliminar_egreso'] = 1;
			$sqlk="DELETE FROM  kardes WHERE id_factura='".$id_egreso."' AND detalle='Egreso' AND id_empresa=$sesion_id_empresa;";
			$response['consultasSql'][] =$sqlk; 
			$result=mysql_query($sqlk) ;
			if($resultEliminarDetalleEgresos){
				$response['eliminar_kardex_egreso'] =1;
			}
		}
	}

    }
    else
    {
		$response['num_transferencia'] ='No existe numero transferencia';
    }
	
//FIN EGRESOS

//INICIO INGRESOS
	if($id_ingreso !=""  )
		{       
		$sql2="select detalle_ingresos.*, productos.codigo from detalle_ingresos INNER JOIN productos on detalle_ingresos.id_producto = productos.id_producto where detalle_ingresos.id_empresa='".$sesion_id_empresa."' and id_ingreso='".$id_ingreso."'";
		$response['consultasSql'][] =$sql2; 
		$resultado=mysql_query($sql2);
		
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
			while($rowBod=mysql_fetch_array($resultadoBod))
				{ $stock =$rowBod['cantidad']; }
                
			$stock_act=$stock-$cantidad;
			$sql221="update cantBodegas set cantidad = '".$stock_act."' where idProducto='".$cod_producto."' and idBodega='".$bodegaDetalle."' ;";
			$result221=mysql_query($sql221) ;
		}

		$sqlDetalleIngresos = "DELETE FROM detalle_ingresos where id_ingreso='".$id_ingreso."';";
        $resultDetalleIngresos=mysql_query($sqlDetalleIngresos);
		$response['consultasSql'][] =$sqlDetalleIngresos; 
		if($resultDetalleIngresos){
			$response['eliminar_detalle_ingreso'] =1;
			$sqlIngresos = "DELETE FROM  ingresos  where id_empresa='".$sesion_id_empresa."'  and id_ingreso='".$id_ingreso."' ";
			$response['consultasSql'][] =$sqlIngresos;
			$resultIngresos=mysql_query($sqlIngresos);
			if($resultIngresos){
				$response['eliminar_ingreso'] = 1;
				$sqlKardexIngresos="DELETE FROM  kardes  WHERE id_factura='".$id_ingreso."' AND detalle='Ingreso' AND id_empresa=$sesion_id_empresa;";
				$response['consultasSql'][] =$sqlKardexIngresos;
				$resultKardexIngresos=mysql_query($sqlKardexIngresos);
				if($resultKardexIngresos){
					$response['eliminar_kardex_ingreso'] =1;
				}
			}
		}

		

		

	}else{
		$response['num_ingreso'] ='No existe numero ingreso';
	}

	$sqlTrasferencia="DELETE FROM  `transferencias`  WHERE id_transferencia=$id_transferencia AND id_empresa =$sesion_id_empresa";
	$response['consultasSql'][] =$sqlTrasferencia;
	$resultDetalleTransferencia=mysql_query($sqlTrasferencia);
	if($resultDetalleTransferencia ){
		$response['eliminar_transferencias'] =1;

		$sqlKardexTransferencia ="DELETE FROM  kardes  WHERE id_factura='".$id_transferencia."' AND detalle='Transferencia' AND id_empresa=$sesion_id_empresa;";
		$response['consultasSql'][] =$sqlKardexTransferencia;
		$resultKardexTransferencia=mysql_query($sqlKardexTransferencia);
		if($resultk ){
			$response['eliminar_kardex_ingreso'] =1;
		}
	}

	

	// echo json_encode($response);
	if($response['eliminar_kardex_ingreso'] == 1 && $response['eliminar_kardex_ingreso']== 1 && $response['eliminar_kardex_egreso']==1 ){
		?> <div class='alert alert-success'><p>Transferencia eliminado correctamente.</p></div> <?php
	}else {
		?> <div class='transparent_ajax_error'><p>No se  elimino correctamente la transferencia <?php echo " ".mysql_error(); ?>;</p></div> <?php
	}

}
?>


	    
