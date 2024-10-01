<?php
error_reporting(0);
    session_start();

    //Include database connection details
    
    require_once('../conexion.php');
	require_once('promedio.php');
	
    require_once('facturaXml.php');
	
    $accion=$_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_usuario = $_SESSION['sesion_id_usuario'];
    date_default_timezone_set('America/Guayaquil');
	$sesion_empresa_ruc=$_SESSION['sesion_empresa_ruc'];
	$sesion_punto = $_SESSION['userpunto'];
	$sesion_id_est = $_SESSION['userest'];
	  
	    

	    
  
    
    if($accion == "1")
  {
	$response= [];
	
    $modoFacturacion=$_POST['modo']; 
	
	// GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
	try 
	{
	    
			$sqlEmpresa="SELECT limiteFacturas from  `empresa`  WHERE id_empresa='$sesion_id_empresa' ";
			$resultEmpresa=mysql_query($sqlEmpresa) or die();
// 			$response['consulta']['sql'][]= $sqlEmpresa;
			$response['consulta']['ejecucion'][]= ($resultEmpresa)?'Si':'No';
			
			while($rowLimite=mysql_fetch_array($resultEmpresa))//permite ir de fila en fila de la tabla
			{
				$limite=$rowLimite['limiteFacturas'];
			}
			
				
			if($limite>=0){    
			    $limite3 = '1';	
			}else {	  
			    $limite3 = '2';	
			}
	    
	    
	   	
		if(isset ($_POST['txtNumeroFacturaFVC']))
		{
		    $cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
		    $cmbEst=$_POST['cmbEst'];
			$cmbEmi=$_POST['cmbEmi'];
			$cedula = $_POST['txtNumeroFacturaFVC'];
			
			$sql = "SELECT numero_factura_venta from ventas where numero_factura_venta='".$cedula."' and id_empresa='".$sesion_id_empresa."'
			and codigo_pun ='".$cmbEst."' and codigo_lug='".$cmbEmi."' and tipo_documento='".$cmbTipoDocumentoFVC."';";
			$resp = mysql_query($sql);
// 			$response['consulta']['sql'][]= $sql;
			$response['consulta']['ejecucion'][]= ($resp)?'Si':'No';
			$entro=0;
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$var=$row["numero_factura_venta"];
			}
			if($var==$cedula)
			{				  
				$response['venta_registrada']= 'SI'; 
			}
			else
			{ 
				$response['venta_registrada']= 'NO'; 
			    $entro=1; 
			}
		}
	}
	catch(Exception $ex) 
	{
		?> <div class="alert alert-warning"><p>Error al verificar la factura 
			<?php echo "".$ex ?></p></div> 
		<?php 
	}
	
	
	if ($entro == 1) {
   
        if ($limite3 == 1) {
            try
    		{
    		    
    		    
    			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
    			
    			$numero_factura=$_POST['txtNumeroFacturaFVC'];
    		    $fecha_venta= $_POST['textFechaFVC'];	
    		  //   $fecha_venta= date('Y-m-d h:i:s');
    		     
    			$id_cliente=$_POST['textIdClienteFVC'];
    			$estado = "Activo";
    			
    // 			$cmbIdVendedor=$_POST['cmbIdVendedorFVC'];
    	        $cmbIdVendedor=$_POST['chofer_id'];
                
    			
    			$cmbEst=$_POST['cmbEst'];
    			$cmbEmi=$_POST['cmbEmi'];
    			
    
    			$txtNombreFVC=$_POST['txtNombreFVC'];
    			$idFormaPago=0;
    			$txtCuotasTP=0;
    			$total=$_POST['txtTotalFVC'];
    			$sub_total=$_POST['txtSubtotalFVC'];
    			$sub_total0		= isset($_POST['txtSubtotal0FVC'])? $_POST['txtSubtotal0FVC']: 0;
    			$sub_total12	= isset($_POST['txtSubtotal12FVC'])? $_POST['txtSubtotal12FVC'] :0;
    			$descuento=$_POST['txtDescuentoFVCNum'];
    			$propina=$_POST['txtPropinaFVC'];
    		
    			$txtIva=$_POST['txtIva1'];
    			$facAn=trim($_POST['facAn']);
    			$facAn= ($facAn!='')?$facAn:0;
    			$motivo =$_POST['MotivoNota'] ;
    			$txtTotalIvaFVC=$_POST['txtTotalIvaFVC'];
    			$txtDescripcion=$_POST['txtDescripcionFVC'];
    			$txtContadorFilas=$_POST['txtContadorFilasFVC'];
    
    			$txtCambioFP=$_POST['txtCambioFP'];
    			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
    			$totalCobrar = $_POST['txtSubtotalVta']; 

			
					$totalAnticipo =0;
				
    			if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
    			{            
    				$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."' and iva >0 ;";
    				$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
    				<p>Error: '.mysql_error().' </p></div>  ');
				// 	$response['consulta']['sql'][]= $sqlIva1;
					$response['consulta']['ejecucion'][]=  ($resultIva1)?'Si':'No';
    				$iva=0;
    				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
    				{
    					$iva=$rowIva1['iva'];
    					$txtIdIva=$rowIva1['id_iva'];
    					$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
    				}
    				
    				// 	echo "txtIdIva ==>".$txtIdIva;
    			}
    			
    			
    			
    			if ($cmbTipoDocumentoFVC==1 || $cmbTipoDocumentoFVC==41 || $cmbTipoDocumentoFVC==100)
    			{
    				if($id_cliente!="" && $numero_factura !=""  )
    			    {       
    			     //   if($sesion_id_empresa==116){
    			            
    			        $incoterm =isset($_POST['incoterm'])?$_POST['incoterm']:'0';
    			        $lugarIncoTerm =isset($_POST['lugarIncoTerm'])?$_POST['lugarIncoTerm']:'';
    			        
    			        $paisOrigen =isset($_POST['paisOrigen'])?$_POST['paisOrigen']:'';
    			        
    			        $puertoEmbarque =isset($_POST['puertoEmbarque'])?$_POST['puertoEmbarque']:'';
    			       
    			        $puertoDestino =isset($_POST['puertoDestino'])?$_POST['puertoDestino']:'';
    			        
    			         $paisDestino=isset($_POST['paisDestino'])?$_POST['paisDestino']:'';
    			         
    			        $paisAdquisicion =isset($_POST['paisAdquisicion'])?$_POST['paisAdquisicion']:'';
    			        
    			        $numeroDae =isset($_POST['numeroDae'])?$_POST['numeroDae']:'';
    			        
    			        $numeroTransporte =isset($_POST['numeroTransporte'])?$_POST['numeroTransporte']:'';
    			        
    			        $fleteInternacional =isset($_POST['fleteInternacional'])?$_POST['fleteInternacional']:'';
    			        
    			        $seguroInternacional =isset($_POST['seguroInternacional'])?$_POST['seguroInternacional']:'';
    			        
    			    $gastosAduaneros=0;
    			        if(isset($_POST['gastosAduaneros'])){
    			            if(trim($_POST['gastosAduaneros'])!=''){
    			                $gastosAduaneros =$_POST['gastosAduaneros'];
    			            }
    			        }
    			       $gastosTransporte=0;
    			       if(isset($_POST['gastosTransporte'])){
    			            if(trim($_POST['gastosTransporte'])!=''){
    			                $gastosTransporte =$_POST['gastosTransporte'];
    			            }
    			        }
    			        
    			        $vendedor_id = trim($_POST['vendedor_id']);
    			        $vendedor_id = ($vendedor_id=='')?0:$vendedor_id;
    			        
    			$sql="insert into ventas (fecha_venta,       estado,        total, sub_total,sub0,sub12,descuento,propina,numero_factura_venta, fecha_anulacion, descripcion, id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario,`tipo_inco_term`, `lugar_inco_term`, `pais_origen`, `puerto_embarque`, `puerto_destino`, `pais_destino`, `pais_adquisicion`, `numero_dae`, `numero_transporte`, `flete_internacional`, `seguro_internaiconal`, `gastos_aduaneros`, `gastos_transporte`,vendedor_id_tabla ,total_iva) 
    					values (                '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."', '".$incoterm."', '".$lugarIncoTerm."', '".$paisOrigen."', '".$puertoEmbarque."', '".$puertoDestino."', '".$paisDestino."', '".$paisAdquisicion."', '".$numeroDae."', '".$numeroTransporte."', '".$fleteInternacional."', '".$seguroInternacional."', '".$gastosAduaneros."', '".$gastosTransporte."','".$vendedor_id."','".$txtTotalIvaFVC."');";
    		
    			 if($_POST['idtipocliente'] =='08'){
    			     $iva_exportador=0;
    			     $sql_iva_exportador= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='". $sesion_id_empresa . "' AND iva=0 ";
					$result_iva_exportador = mysql_query( $sql_iva_exportador );
					while($row_iva_exportador = mysql_fetch_array($result_iva_exportador) ){
						$iva_exportador= $row_iva_exportador['id_iva'];
					}
    			 }
    					$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
				// 		$response['consulta']['sql'][]= $sql;
						$response['consulta']['ejecucion'][]=  ($result)?'Si':'No';
    					$id_venta=mysql_insert_id();
						$response['venta'][]= $id_venta;
    				
    					for($i=1; $i<=$txtContadorFilas; $i++)	{
    					    
    					if($_POST['txtIdServicio'.$i] >= 1){ //verifica si en el campo esta agregada una cuenta
    			
    							$cantidad = $_POST['txtCantidadS'.$i];
    							$idServicio = $_POST['txtIdServicio'.$i];
    							$idKardex = $_POST['txtIdServicio'.$i];
    							$valorUnitario = $_POST['txtValorUnitarioShidden'.$i];
    							
    							$valorTotal = $_POST['txtValorTotalS'.$i];
    						    $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
    						    $txtTipo11 = $_POST['txtTipo'.$i];
    						    
    							$id_tipoP = $_POST['txtTipoS'.$i];
    							$cuenta = $_POST['cuenta'.$i];
    							$idBod = $_POST['idbod'.$i];
    							$idvalorPago=$_POST['txtValorS'.$i];
                                $txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];
    							$txtdetalle2=$_POST['txtdetalle2'.$i];
    							$bodegaCantidad=$_POST['bodegaCantidad'.$i];
    							if($bodegaCantidad!=''){
    							    $bodegaCantidad=$_POST['bodegaCantidad'.$i];
    							}else{
    							    $bodegaCantidad='0';
    							}
    							$txtCodigoServicio=$_POST['txtCodigoServicio'.$i];
    							
    							$txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
    							if ($id_tipoP == "2" && $txtDescripcionS!='') {
                                    $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
                                    $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
                                    // $response['consulta']['sql'][]= $sql3;
									$response['consulta']['ejecucion'][]= ($resp3)?'Si':'No';
                                }
    							

    							    $sqlCentem = "SELECT establecimientos.centro_costo FROM `establecimientos` WHERE  id= '" . $cmbEst . "'";
    							    $resultCentm = mysql_query($sqlCentem);
								// 	$response['consulta']['sql'][]= $sqlCentem;
									$response['consulta']['ejecucion'][]=  ($resultCentm)?'Si':'No';
    							    $numFilasCetm = mysql_num_rows($resultCentm);
    							    if($numFilasCetm>0){
    							        while($rowCetm = mysql_fetch_array($resultCentm) ){
    							            $id_proyecto= $rowCetm['centro_costo'];
    							        }
    							        
    							    }else{
    							        $id_proyecto=0;
    							    }
								$id_iva = $_POST['IVA120'.$i];
								$sql_iva= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='" . $sesion_id_empresa . "' AND id_iva='" . $id_iva . "' ";
								$result_iva = mysql_query( $sql_iva );
								while($row_iva = mysql_fetch_array($result_iva) ){
									$tarifa_iva= $row_iva['iva'];
								}
								

								$total_iva= floatval($valorTotal) * (floatval( $tarifa_iva )/100);   
								
                                 if($_POST['idtipocliente'] =='08'){
    			                    $id_iva=(trim($iva_exportador)=='')?0:$iva_exportador;
                                     $total_iva=0;
                                 }
                                  
    							$sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa,id_proyecto, `tarifa_iva`, `total_iva`) 
    							values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."', '".$id_proyecto."','".$id_iva."', '".$total_iva."' );";  
    							
    							    
    			
    							$resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
    							<p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
								$response['consulta']['sql'][]= $sql2;
								$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
    							$id_detalle_venta=mysql_insert_id();
								$response['detalle_venta'][]= $id_detalle_venta;
								
    				    if ($id_tipoP == "1") {
    				        
        				    if($bodegaCantidad!=''){
        				    $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$bodegaCantidad."' and idProducto='".$txtCodigoServicio."' ";
        				 
        				    
                            $resultado = mysql_query($stockBodegas);
							$response['consulta']['sql'][]= $stockBodegas;
							$response['consulta']['ejecucion'][]= ($resultado)?'Si':'No';
                            while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
                            	{
                            		$id=$rowBodegas1['id'];
                            		$cantidadBodega=$rowBodegas1['cantidad'];
                            	}
                		  $cantidadBodega = $cantidadBodega-$cantidad;
	                    $response['cantidadBodega'][]= $cantidadBodega;
	                    $response['cantidad'][]= $cantidad;
                		             $sqlbodegas="UPDATE `cantBodegas` SET `cantidad`='".$cantidadBodega."' WHERE id='".$id."'";
                	                    $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
										$response['consulta']['sql'][]= $sqlbodegas;
										$response['consulta']['ejecucion'][]= ($resultBodegas)?'Si':'No';
        
        				            }
    				        }
                        }
                        
                        
                        
                        
                        
    				}
    		
					
    			        if($limite==0){
    					    $limite=0;
    					    
    					}else if($limite==1){
    					    $limite=$limite-2;
    					    
    					}else{
    					    
    					    $limite=$limite-1;
    					}
    					
    				// 	$cmbEmi;
    				if($_POST['cmbTipoDocumentoFVC']=='1' or $_POST['cmbTipoDocumentoFVC']=='41'){
    				    	$sqlNumFac ="update emision set numFac='".$numero_factura."' where id ='".$cmbEmi."' ";
    					$result=mysql_query($sqlNumFac) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().'</p></div>  ');
    					
    
    					$response['consulta']['sql'][]= $sqlNumFac;
						
						$response['consulta']['ejecucion'][]=  ($result)?'Si':'No';
    				}
    				

    					$sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
    					$resultEmpresa2=mysql_query($sqlEmpresa2) or die();
						$response['consulta']['sql'][]= $sqlEmpresa2;
						$response['consulta']['ejecucion'][]=  ($resultEmpresa2)?'Si':'No';
    					
    				
    	
                    // Crear el asiento		
    
    				// if ($sesion_tipo_empresa=="6")
    				// {
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
							$response['num_asiento'][]= $numero_asiento;
    					}
    					 catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
    		
    		
    		            $tipo_comprobante = "Diario"; 
    				
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
								$response['num_comprobante'][]= $numero_comprobante;
    					}
    					catch (Exception $e) 
    					{
    						// Error en algun momento.
    					   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
    					}
    					
    					
    							  
    
    					$fecha= date("Y-m-d h:i:s");
    					$descripcion = "Factura de venta #".$numero_factura." realizada a ".$txtNombreFVC;
    					 if(  $cmbTipoDocumentoFVC==100 ){
    					     	$descripcion = "Nota de venta #".$numero_factura." realizada a ".$txtNombreFVC;
    					 }
    					$debe = $total;
    					$debe2 = $descuento;
    					$total_debe = $debe + $debe2;
    					
    					$haber1 = $sub_total;
    					$haber2 = $_POST['txtTotalIvaFVC'];
    					
    					$total_haber = $haber1 + $haber2 + $propina;
    					
    					$tipo_mov="F";
    	
    				//GUARDA EN  COMPROBANTES
    					$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
    					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
						$response['consulta']['sql'][]= $sqlC;
						$response['consulta']['ejecucion'][]=  ($respC)?'Si':'No';
    					$id_comprobante=mysql_insert_id();
    					$response['comprobante'][]= $id_comprobante;
    					
    				$sqlCentroCosto = "SELECT `id`, `id_empresa`, `codigo`, `direccion`, `centro_costo` FROM `establecimientos` WHERE id=$cmbEst ;";
    				$resultCentroCosto = mysql_query($sqlCentroCosto);
					$response['consulta']['sql'][]= $sqlCentroCosto;
					$response['consulta']['ejecucion'][]=   ($resultCentroCosto)?'Si':'No';
    				$centroCosto=0;
    				while($rowccc = mysql_fetch_array($resultCentroCosto) ){
    				    $centroCosto= $rowccc['centro_costo'];
    				}

    				//GUARDA EN EL LIBRO DIARIO
    					$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
    					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta,centroCosto) 
    					values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
    					'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' ,'".$centroCosto."')";
    					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    					$id_libro_diario=mysql_insert_id();
						$response['consulta']['sql'][]= $sqlLD;
						$response['consulta']['ejecucion'][]=  ($resp)?'Si':'No';
						$response['libro_diario'][]= $id_libro_diario;

    					$idPlanCuentas[1] = '';
    					$idPlanCuentas[2] = '';
    					$idPlanCuentas[3] = '';
    					$debeVector[1] =    0;
    					$debeVector[2] =    0;
    					$debeVector[3] =    0;
    					$haberVector[1] =   0;
    					$haberVector[2] =   0;
    					$haberVector[3] =   0;		

    					$lin_diario=0;
                        $valor[$lin_diario]=0;
    					$ident=0;
    					$existeCuota='';
    					 $listaServicios=array();
    					for($i=1; $i<=$txtContadorFilas; $i++)				
    					{
    						if($_POST['txtCodigoS'.$i] >=1)
    						{	

    							$lin_diario=$lin_diario+1;
    							$idPlanCuentas[$lin_diario]=$_POST['txtCodigoS'.$i];
    							$debeVector[$lin_diario]=$_POST['txtValorS'.$i];
    							$haberVector[$lin_diario]=0; 
    							$formaPagoId[$lin_diario]=$_POST['formaPagoId'.$i];
    							
                             $txtDiasCuotas = trim($_POST['txtDiasCuotas'.$i])==''?30:trim($_POST['txtDiasCuotas'.$i]);

								if($_POST['txtTipo1'.$i]==4 ){
									$existeCuota = $i;
								
									$sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje,intervalo_cuotas) 
									VALUES     ('".$formaPagoId[$i]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$i]."','".$_POST['txtTipo1'.$i]."', NULL, '".$txtDiasCuotas."');";
								   }else if($_POST['txtTipo1'.$i]==6 ){
			
									$sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje,numero_retencion,autorizacion) 
									VALUES     ('".$formaPagoId[$i]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$i]."','".$_POST['txtTipo1'.$i]."', NULL,'".$_POST['txtNumeroRetencionS'.$i]."','".$_POST['txtAutorizacion'.$i]."');";
								   }else{
									$sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) 
									VALUES     ('".$formaPagoId[$i]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$i]."','".$_POST['txtTipo1'.$i]."', NULL );";
								   }
    				            
    				            $respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
								$response['consulta']['sql'][]= $sqlforma;
								$response['consulta']['ejecucion'][]=  ($respForma)?'Si':'No';

    				            if($respForma){
    				                if($_POST['txtTipo1'.$i]==1 ){
    				                 $identificador="01";
    				                }
    				                else if($_POST['txtTipo1'.$i]==2){
    				                  $ident=1;
    				                    $identificador="02";
    				                }else if($_POST['txtTipo1'.$i]==16 && $_POST['txtTipo1'.$i]==17 ){
    				                //   $ident=1;
    				                    $identificador="19";
    				                }
    				                else{
    				                    $identificador="03";
    				                }
    				                   if($ident==1){
    				                       $identificador="02";
    				                   } 
    				                    $sql3="update ventas set id_forma_pago='".$identificador."' where id_venta='".$id_venta."' ";
    									$resp3 = mysql_query($sql3) or die(mysql_error());
										$response['consulta']['sql'][]= $sql3;
										$response['consulta']['ejecucion'][]=  ($resp3)?'Si':'No';
    				            }
    						}
    						
    								if($_POST['txtTipo1'.$i]=='18'){
    							
    							$sqlCtaPagar = "SELECT
    							cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
    							cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
    							cuentas_por_pagar.`id_cliente` AS cuentas_por_pagar_id_proveedor,
    							cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
    							cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
    					   FROM
    							`cuentas_por_pagar` cuentas_por_pagar 
    							 WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
    							 cuentas_por_pagar.`id_cliente`='".$id_cliente."' and saldo>0 
    							 order by cuentas_por_pagar.`fecha_vencimiento`"; 
    							$resultCtaPagar= mysql_query($sqlCtaPagar);
    							$response['consulta']['sql'][]= $sqlCtaPagar;
								$response['consulta']['ejecucion'][]= ($resultCtaPagar)?'Si':'No';

    							 $cantidadPagar = round(floatval($_POST['txtValorS'.$i]),2);
    							// echo '|';
    							while ($row = mysql_fetch_array($resultCtaPagar))
    							{ 
    								$id_cuenta_pagar= $row['ctas_x_pagar_id_cuenta_por_pagar'];
    								// echo '|saldo_pagar=>';	
    								 $saldo_pagar= round(floatval($row['cuentas_por_pagar_saldo']), 2);
    								// echo '|';	
    	
    								if($cantidadPagar>= $saldo_pagar){
    									$cantidadPagar  = $cantidadPagar - $saldo_pagar;
    									$saldo_actual = 0;
    									$text='Canceladas';
    								}else{
    									$saldo_actual = $saldo_pagar-$cantidadPagar;
    									$cantidadPagar=0;
    									$text='Pendientes';
    								}
    
    								// echo '|';
    								 $sqlUpdateCtaPagar="UPDATE `cuentas_por_pagar` SET `saldo`='$saldo_actual', estado='$text' ,fecha_pago='$fecha_venta'
    						WHERE id_cuenta_por_pagar=$id_cuenta_pagar AND id_empresa='".$sesion_id_empresa."' ";
    						
    								// echo '|';
    
    								$resultUpdateCtaPagar= mysql_query($sqlUpdateCtaPagar);
									$response['consulta']['sql'][]= $sqlUpdateCtaPagar;
									$response['consulta']['ejecucion'][]= ($resultUpdateCtaPagar)?'Si':'No';
    								if($cantidadPagar==0){
    									break;
    								}
    							}
    						}
    						if ($_POST['txtTipo1'.$i]=='4')
    						{
    							$total=$_POST['txtValorS'.$i];
    							$txtCuotasTP=$_POST['txtCuotaS'.$i];
    							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];		
    						}
    						
    						if ($_POST['txtTipo1'.$i]=='13')
    						{
    							$estadoCC="Pendientes";
    							
    							$total_x_pagar=$_POST['txtValorS'.$i];
    							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
    							$cuentaxpagar="SI";
    						
    							$cmbTipoDocumentoFVC="Factura No.";
    							 if(  $_POST['cmbTipoDocumentoFVC'] == 100 ){
    							     	$cmbTipoDocumentoFVC="Nota de venta No.";
    					 }
    							$sql3 = "insert into cuentas_por_pagar (tipo_documento, numero_compra, referencia,  
    							valor, saldo,numero_asiento,fecha_vencimiento,  id_proveedor,id_cliente ,id_plan_cuenta, id_empresa,
    							id_compra, estado) " . "values 
    							('".$cmbTipoDocumentoFVC."','".$numero_factura."','".
    						$txtNombreFVC."', '".$total_x_pagar."','".$total_x_pagar."','','".$fecha_venta."',null,'".$id_cliente."','".
    							$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."','".$id_venta."', '".$estadoCC."');";
    
                				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_pagar: '.mysql_error().' </p></div>  '.$sql3);
                				$id_cuenta_por_pagar=mysql_insert_id();
								// $response['consulta']['sql'][]= $sql3;
								$response['consulta']['ejecucion'][]=  ($resp3)?'Si':'No';
								$response['cuenta_pagar'][]= $id_cuenta_por_pagar;
    						}
    							$response['banco']['tipo'][]= $_POST['txtTipo1'.$i];
    						if ($_POST['txtTipo1'.$i]=='17')
    						{
    						    // echo "TRANSFERENCIA</br>";

                                        $cmbTipoDocumento='Transferencia';
                                        $txtNumeroDocumento=$_POST['txtNumDocumento'.$i];
                                        $txtDetalleDocumento="Transferencia de ".$txtNombreFVC ;
                                        $txtFechaEmision=$fecha_venta;
                                        $txtFechaVencimiento=$fecha_venta;
                                        $saldo_conciliado = 0;
                                        $valorConciliacion = $_POST['txtValorS'.$i];
                                        $estado = "No Conciliado";
                                        
                                        $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$_POST['txtCodigoS'.$i]."' 
                                        AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
                                        $resultb2=mysql_query($sqlb2);
								// 		$response['consulta']['sql'][]= $sqlb2;
										$response['consulta']['ejecucion'][]= ($resultb2)?'Si':'No';
                                        while($rowb2=mysql_fetch_array($resultb2))//permite ir de fila en fila de la tabla
                                        {
                                            $id_bancos2=$rowb2['id_bancos'];
                                        }    
                                        
                                        $numero_fil = mysql_num_rows($resultb2);
                                        
                                        if($numero_fil > 0){
                                            
                                            $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                            values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";   
                                        }else {
                                            
                                            $sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values 
                                            ('".$_POST['txtCodigoS'.$i]."','".$saldo_conciliado."', '".$sesion_id_periodo_contable."');";
                                            $resultB=mysql_query($sqlB);
											$response['consulta']['sql'][]= $sqlB;
											$response['consulta']['ejecucion'][]=  ($resultB)?'Si':'No';
                							$id_bancos=mysql_insert_id();
                                            $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                            values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos."', '".$estado."', '".$id_libro_diario."');";
                                        }
										$resultDB=mysql_query($sqlDB);
                						$id_detalle_banco=mysql_insert_id();
										$response['consulta']['sql'][]= $sqlDB;
										$response['consulta']['ejecucion'][]=  ($resultDB)?'Si':'No';
    						}
    					}

    					$tot_ventas=0;
    					$tot_servicios=0;
    					$tot_costo=0;
    					
    					for($i=1; $i<=$txtContadorFilas; $i++){
    						if($_POST['txtIdServicio'.$i] >= 1)
    						{
    							$idProducto=$_POST['txtIdServicio'.$i];
    							$id_tipoP = $_POST['txtTipoS'.$i];
    							$cuenta = $_POST['cuenta'.$i];
    			
    							if ($id_tipoP =="1"){
    								$tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
    								$costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
    								$tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
    							}
    							
    						    if ($id_tipoP == "2" ){
    						        $sqlPlanCuenta= "SELECT  
    								productos.`id_producto` AS productos_id_producto, 
    							 productos.`producto` AS productos_nombre,
    							 productos.`grupo` AS productos_grupo, 
    							 centro_costo.`id_centro_costo` AS centro_id, 
    							 centro_costo.`descripcion` AS centro_descripcion, 
    							 centro_costo.`id_cuenta` AS productos_id_cuenta,
    							 plan_cuentas.codigo,
          						 plan_cuentas.nombre
    							 FROM  `productos` 
    							 INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
    							 INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = centro_costo.id_cuenta
    							 WHERE productos.id_producto=$idProducto ";
    							 $resultPlanCuentas= mysql_query($sqlPlanCuenta);
								 $response['consulta']['sql'][]= $sqlPlanCuenta;
								$response['consulta']['ejecucion'][]=   ($resultPlanCuentas)?'Si':'No';
    							 $planCuentaServicio = 0;
    							 while($rowPC = mysql_fetch_array($resultPlanCuentas)){
    								$planCuentaServicio = $rowPC['productos_id_cuenta'];
    							 }
    							 if(array_key_exists($planCuentaServicio,$listaServicios)){
                                                $listaServicios[$planCuentaServicio]=$listaServicios[$planCuentaServicio]+ $_POST['txtValorTotalS'.$i]; 
                                              }else{
                                                   $listaServicios[$planCuentaServicio]=$_POST['txtValorTotalS'.$i]; 
                                              }
                                              
    								$tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
    							}
    							
    						}
    					}
    					
    					
    			    try 
    				{
                        $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
    					WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
    					formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultMercaderia=mysql_query($sqlMercaderia);
						$response['consulta']['sql'][]= $sqlMercaderia;
						$response['consulta']['ejecucion'][]=  ($resultMercaderia)?'Si':'No';
                        $idcodigo_v=0;
                        while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
                        {
                            $idcodigo_v=$row['codigo_plan_cuentas'];
                        }
                        $idcodigo_v;
    				}
    				catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
    				
    				try 
    				{
    					$sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
    					WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
    					formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultServicio=mysql_query($sqlServicio);
						$response['consulta']['sql'][]= $sqlServicio;
						$response['consulta']['ejecucion'][]= ($resultServicio)?'Si':'No';
                        $idcodigo_s=0;
                        while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
                        {
                            $idcodigo_s=$row['codigo_plan_cuentas'];
                        }
                        $idcodigo_s;
    
    				}catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				
    
    						
        				$sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
              				from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
        						( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
        
        				$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$response['consulta']['sql'][]= $sql;
						$response['consulta']['ejecucion'][]= ($resultS)?'Si':'No';			
        				$plan_id_cuenta_vta=0;
        				$plan_id_cuenta_servicio=0;
        				while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
        				{
        					if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
        						{
        							$plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
        						}
        					if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
        						{
        							$plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
        						}
    
        						
        				}
                        
                        
						$response['tot_ventas']= $tot_ventas;
        				if ($tot_ventas!=0)
        				{
        					$lin_diario=$lin_diario+1;
        					$idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
        					$debeVector[$lin_diario]=0;
        					$haberVector[$lin_diario]=$tot_ventas;
        					
    
        				}
        				
						$response['tot_servicios']= $tot_servicios;
        				if ($tot_servicios!=0)
        				{
                            foreach ($listaServicios as $key => $value) {
                                $lin_diario=$lin_diario+1;
                                $idPlanCuentas[$lin_diario]= $key;
                                $debeVector[$lin_diario]=0;
                                $haberVector[$lin_diario]=$value;
                            }
        				}
                        
                        
                        try {
                                $sqlDescuento="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago WHERE `id_tipo_movimiento` =14 
                                and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                                $resultDescuento=mysql_query($sqlDescuento);
								$response['consulta']['sql'][]= $sqlDescuento;
								$response['consulta']['ejecucion'][]= ($resultDescuento)?'Si':'No';		
                                $idcodigo_d=0;
                                while($row=mysql_fetch_array($resultDescuento))//permite ir de fila en fila de la tabla
                                {
                                    $idcodigo_d=$row['codigo_plan_cuentas'];
                                }
                                $idcodigo_d;
            
                        }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
    					
    					
    					
    					if ($descuento!=0)
    					{
    						$lin_diario=$lin_diario+1;
    						$idPlanCuentas[$lin_diario]= $idcodigo_d;
    						$debeVector[$lin_diario]=$descuento;
    						$haberVector[$lin_diario]=0;
    					}
    					
    					
            			try {
                                $sqlPropina="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago 
                                WHERE `id_tipo_movimiento` =15 and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                                $resultPropina=mysql_query($sqlPropina);
								$response['consulta']['sql'][]= $sqlPropina;
								$response['consulta']['ejecucion'][]= ($resultPropina)?'Si':'No';	
                                $idcodigo_p=0;
                                while($row=mysql_fetch_array($resultPropina))//permite ir de fila en fila de la tabla
                                {
                                    $idcodigo_p=$row['codigo_plan_cuentas'];
                                }
                                $idcodigo_p;
            
                        }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
    					
    					
    					if ($propina!=0)
    					{
    						$lin_diario=$lin_diario+1;
    						$idPlanCuentas[$lin_diario]= $idcodigo_p;
    						$debeVector[$lin_diario]=0;
    						$haberVector[$lin_diario]=$propina;
    					}
    					
    					if ($txtTotalIvaFVC!=0)
    					{
    
        					$lin_diario=$lin_diario+1;
        					$idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
        					$debeVector[$lin_diario]=0;
        					$haberVector[$lin_diario]=$txtTotalIvaFVC;
    
    					}
    					
    					
                        
                        
    					for($i=1; $i<=$lin_diario; $i++)
    					{
    					    
    
    						if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
    						{
    						    
    							$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
    							('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
    							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    							$id_detalle_libro_diario=mysql_insert_id();
								$response['consulta']['sql'][]= $sqlDLD;
								$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
								$response['detalle_libro_diario'][]= $id_detalle_libro_diario;
    							
    							$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
    							$result5=mysql_query($sql5);
								$response['consulta']['sql'][]= $sql5;
								$response['consulta']['ejecucion'][]= ($result5)?'Si':'No';
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
    							    
    								try 
    								{
    
    									$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
    									$result6=mysql_query($sql6);
    									$id_mayorizacion=mysql_insert_id();
										$response['consulta']['sql'][]= $sql6;
										$response['consulta']['ejecucion'][]= ($result6)?'Si':'No';
										$response['mayorizacion'][]= $id_mayorizacion;
    								}
    								catch(Exception $ex) 
    								{ ?> <div class="transparent_ajax_error">
    									<p>Error en la insercion de la tabla mayorizacion: 
    									<?php echo "".$ex ?></p></div> <?php }
    								// FIN DE MAYORIZACION
    							}
    						}
    					}
    					
    					$response['tot_costo']= $tot_costo;
    				    if ($tot_costo>0)
    					{
							$numero_asiento++;
    						$tipo_comprobante = "Diario"; 
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
    
    					
    				//FIN SACA EL ID MAX DE COMPROBANTES
    		
    						$fecha= date("Y-m-d h:i:s");
    						
    						$descripcion="Asiento de costo de venta de la Factura No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
    if( $_POST['cmbTipoDocumentoFVC'] == 100 ){
    	$descripcion="Asiento de costo de venta de la Nota de venta No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
    					 }
    					    $total_debe  = $tot_costo;
    					    $total_haber = $tot_costo;
    					    $tipo_mov="F";
    	
    				//GUARDA EN  COMPROBANTES
    						$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
    						$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
							$id_comprobante=mysql_insert_id();
							$response['consulta']['sql'][]= $sqlC;
							$response['consulta']['ejecucion'][]=  ($respC)?'Si':'No';
    				//GUARDA EN EL LIBRO DIARIO
    						$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
    						total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta,centroCosto) 
    						values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
    						'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
    						'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' ,'".$centroCosto."')";
    
    						$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    						$id_libro_diario=mysql_insert_id();
							$response['consulta']['sql'][]= $sqlLD;
							$response['consulta']['ejecucion'][]= ($resp)?'Si':'No';
							$response['libro_diario'][]= $id_libro_diario;
    			try {
                        $sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
                        WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
                        formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultCosto=mysql_query($sqlCosto);
						$response['consulta']['sql'][]= $sqlCosto;
						$response['consulta']['ejecucion'][]= ($resultCosto)?'Si':'No';
                        $idcodigo_v=0;
                        while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
                        {
                            $idcod_costo=$row['codigo_plan_cuentas'];
                        }
                        $idcod_costo;
    
                }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
    
    	
    		try {
                        $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas 
                        WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable
                        and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
                        $resultInventario=mysql_query($sqlInventario);
						$response['consulta']['sql'][]= $sqlInventario;
						$response['consulta']['ejecucion'][]= ($resultInventario)?'Si':'No';
                        $idcodigo_v=0;
                        while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
                        {
                            $idcod_inventario=$row['codigo_plan_cuentas'];
                        }
                        $idcod_inventario;
    
                }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
				
    				$sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
    							( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
    						$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							$response['consulta']['sql'][]= $sql;
							$response['consulta']['ejecucion'][]= ($resultS)?'Si':'No';		
    						$plan_id_cuenta_costo=0;
    						$plan_id_cuenta_inventario=0;
    						while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
    						{
    							if ($rowS['plan_cuentas_codigo']==$idcod_costo)
    							{
    								$plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
    					
    							}
    							if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
    							{
    								$plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
    							}
    						}
    					
    
    					
    						$lin_diario=0;
    						if ($tot_costo>0)
    						{
    							$lin_diario=$lin_diario+1;
    							$idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
    							$debeVector[$lin_diario]=$tot_costo;
    							$haberVector[$lin_diario]=0;	
    							$lin_diario=$lin_diario+1;
    							$idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
    							$debeVector[$lin_diario]=0;
    							$haberVector[$lin_diario]=$tot_costo;									
    						}

    						for($i=1; $i<=$lin_diario; $i++)
    						{
    							if ($idPlanCuentas[$i] !="")
    							{
    
    						//GUARDA EN EL DETALLE LIBRO DIARIO
    								$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 
    								id_plan_cuenta,debe, haber, id_periodo_contable) values 
    								('".$id_libro_diario."',
    								'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
    								'".$sesion_id_periodo_contable."');";
    								$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    								$id_detalle_libro_diario=mysql_insert_id();
									$response['consulta']['sql'][]= $sqlDLD;
									$response['consulta']['ejecucion'][]= ($resp2)?'Si':'No';		
    								
    							// CONSULTAS PARA GENERAR LA MAYORIZACION
    								$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
    								$result5=mysql_query($sql5);
									$response['consulta']['sql'][]= $sql5;
									$response['consulta']['ejecucion'][]=  ($result5)?'Si':'No';
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
    										$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
    										$result6=mysql_query($sql6);
    										$id_mayorizacion=mysql_insert_id();
											$response['consulta']['sql'][]= $sql6;
											$response['consulta']['ejecucion'][]= ($result6)?'Si':'No';
											$response['mayorizacion'][]= $id_mayorizacion;
    									}
    									catch(Exception $ex) 
    									{ ?> <div class="transparent_ajax_error">
    										<p>Error en la insercion de la tabla mayorizacion: 
    										<?php echo "".$ex ?></p></div> <?php }
    								// FIN DE MAYORIZACION
    								}  
    							}
    						}						
    					}
    				// } //aqui finaliza
					
    			
    			  // GUARDAR EN KARDEX
                $doc_kardes ='Venta';
			   if( $_POST['cmbTipoDocumentoFVC'] ==100){
			       $doc_kardes ='Nota de venta';
			   }
    				$sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
    				('".$fecha_venta."','".$doc_kardes."','".$id_venta."', '".$sesion_id_empresa."')";
    				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
    				$id_kardes=mysql_insert_id();
					$response['consulta']['sql'][]= $sqlk;
					$response['consulta']['ejecucion'][]= ($resultk)?'Si':'No';
					$response['kardes'][]= $id_kardes;
					$response['guardo']= 0;
    				if($result && $resp2)
    				{
						$response['guardo']=1;
    					// echo "kardex";
    					if($_POST['cmbTipoDocumentoFVC']==41){
							$txtContadorFilasReembolso = $_POST['txtContadorFilasReembolso'];
							for($re=1;$re<=$txtContadorFilasReembolso;$re++){
								$txtCedulaReembolso = $_POST['txtCedulaReembolso'.$re];
								$txtCodigoPais = $_POST['txtCodigoPais'.$re];
								$txtTipoProveedor = $_POST['txtTipoProveedor'.$re];
								$txtTipoDocumento = $_POST['txtTipoDocumento'.$re];
								$txtEstablecimientoReembolso = $_POST['txtEstablecimientoReembolso'.$re];
								$txtEmisionReembolso = $_POST['txtEmisionReembolso'.$re];
								$txtSecuencialReembolso = $_POST['txtSecuencialReembolso'.$re];
								$txtFechaReembolso = $_POST['txtFechaReembolso'.$re];
								$txtNumeroAutorizacion = $_POST['txtNumeroAutorizacion'.$re];
								$cantidadCaracteres=  strlen($txtCedulaReembolso);
								$tipo_identificacion_proveedor_reembolso='05';
								if($cantidadCaracteres==13){
									$tipo_identificacion_proveedor_reembolso='06';
								}
                                if(trim($txtCedulaReembolso)!=''){
                                    	 $sqlReembolso="INSERT INTO `reembolsos_gastos`( `tipo_identificacion_proveedor_reembolso`, `identificacion_proveedor_reembolso`, `cod_pais_proveedor_reembolso`, `tipo_proveedor_reembolso`, `cod_doc_reembolso`, `estab_doc_reembolso`, `pto_emi_doc_reembolso`, `fecha_emision_doc_reembolso`, `id_venta`,secuencial_doc_reembolso,numero_autorizacion_doc_reembolso) VALUES ('".$tipo_identificacion_proveedor_reembolso."','".$txtCedulaReembolso."','".$txtCodigoPais."','".$txtTipoProveedor."','".$txtTipoDocumento."','".$txtEstablecimientoReembolso."','".$txtEmisionReembolso."','".$txtFechaReembolso."','".$id_venta."','".$txtSecuencialReembolso."','".$txtNumeroAutorizacion."')";
								$resultReembolso = mysql_query($sqlReembolso);
								$id_reembolso = mysql_insert_id();
								$response['consulta']['sql'][]= $sqlReembolso;
								$response['consulta']['ejecucion'][]= ($resultReembolso)?'Si':'No';
								$response['reembolsos_gastos'][]= $id_reembolso;
								$txtContadorFilasCompensacion = $_POST['txtContadorFilasCompensacion'.$re];

								for($t=1;$t<=$txtContadorFilasCompensacion;$t++){
									// $txtCodigoCompensacion=$_POST['txtCodigoCompensacion'.$re.'_'.$t];
									$txtCodigoImpuestoCompensacion=$_POST['txtCodigoImpuestoCompensacion'.$re.'_'.$t];
									$txtPorcentajeCompensacion=$_POST['txtPorcentajeCompensacion'.$re.'_'.$t];
									$txtTarifaCompensacion=$_POST['txtTarifaCompensacion'.$re.'_'.$t];
									$txtBaseImponible=$_POST['txtBaseImponible'.$re.'_'.$t];
									$txtImpuestoCompensacion=$_POST['txtImpuestoCompensacion'.$re.'_'.$t];

									// echo $sqlCompensacion="INSERT INTO `compensaciones_reembolso`( `codigo`, `tarifa`, `valor`, `id_reembolso`) VALUES ('".$txtCodigoCompensacion."','".$txtTarifaCompensacion."','".$txtBaseImponible."','".$id_reembolso."')";
									// $resultCompensacion = mysql_query($sqlCompensacion);

									 $sqlImpuestos="INSERT INTO `impuestos_reembolso`( `codigo_impuesto`, `codigo_porcentaje`, `tarifa`, `base_imponible`, `impuesto`, `id_reembolsos`) VALUES ('".$txtCodigoImpuestoCompensacion."','".$txtPorcentajeCompensacion."','".$txtTarifaCompensacion."','".$txtBaseImponible."','".$txtImpuestoCompensacion."','".$id_reembolso."')";
									$resultImpuestos = mysql_query($sqlImpuestos);
									$response['consulta']['sql'][]= $sqlImpuestos;
									$response['consulta']['ejecucion'][]= ($resultImpuestos)?'Si':'No';

								}
                                }
							

							}
							
						}
    				}

    				/*  GUARDAR EN CUENTAS POR COBRAR */
    				if ($total>0 and $txtCuotasTP>0)
    				{
    					$txtContadorFilas=8;
    					
    					for($i=1; $i<=$txtContadorFilas; $i++)				
    					{
    						if ($_POST['txtTipo1'.$i]=='4')
    						{
    						    $txtFechaTP=$_POST['txtFechaS'.$i];
    							$total=$_POST['txtValorS'.$i];
    							$txtCuotasTP=$_POST['txtCuotaS'.$i];
    							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
    						//	echo "fecha ===".$txtFechaTP;
    						}
    					}
    				}
    
    				if($total > 0 and $txtCuotasTP>0)
    				{            
    					$cuotas = round(($total / $txtCuotasTP),2); 
    					//	$cuotas= round($cuotas_x * 100) / 100;
    						$aux=round(($cuotas * $txtCuotasTP),2); 
    						$dif=round(($total-$aux),2);
    						$cuota_final=$cuotas;
    						//echo $dif;
    					if ($dif != 0)
    					{
    							$cuota_final=$cuota_final + $dif;
    					}
    		
    					$estadoCC = "Pendientes";                
    					for($i=1; $i<=$txtCuotasTP; $i++)
    					{
    						if ($i == $txtCuotasTP)
    					    {
    							$cuotax=$cuota_final;
    						}
    						else
    						{
    							$cuotax=$cuotas;
    						} 
    							
    						if(  (trim($_POST['txtDiasCuotas'.$existeCuota])!='') ){
								$diasASumar=  $txtDiasCuotas * $i;
								$mod_date = strtotime($txtFechaTP."+ ".$diasASumar." days");
							}else{
								$mod_date = strtotime($txtFechaTP."+ ".$i." months");
							}

    						
    						$fecha_nueva = date("Y-m-d",$mod_date);

    							//$sql3 = "insert into cuentas_por_cobrar (id_cuenta_por_cobrar, numero_factura, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
    							//	. "values ('".$id_cuenta_por_cobrar."','".$numero_factura."','".$txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura."','".$cuotax."','".$cuotax."','', '".$fecha_nueva."', '', '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";
    						$sql3 = "insert into cuentas_por_cobrar ( tipo_documento,         numero_factura,         referencia,        valor,          saldo,        numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor,id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
    								. "values                      ('".$cmbTipoDocumentoFVC."','".$numero_factura."','".$txtNombreFVC."' ,'".$cuotax."','".$cuotax."',          '',             '".$fecha_nueva."', null, null, '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";						
    						$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
    						$id_cuenta_por_cobrar=mysql_insert_id();
							$response['consulta']['sql'][]= $sql3;
							$response['consulta']['ejecucion'][]= ($resp3)?'Si':'No';
							$response['cuentas_por_cobrar'][]= $id_cuenta_por_cobrar;
    					}                
    				
    				
    				}    
    				
    				

    				$response['modoFacturacion']= $modoFacturacion;
    				if($modoFacturacion=='200'){
    				    
    				    $emision_tipoEmision='F';
    				    
    				}else{
    				    
    				    $emision_tipoEmision = $_SESSION['emision_tipoEmision'];
    				}
					$response['emision_tipoEmision']= $emision_tipoEmision;
    				
            				if ($emision_tipoEmision === 'E'){
            				    if( $_POST['cmbTipoDocumentoFVC'] !=100){
            				        genXml($id_venta);
            				    }
            			        
            			        
            			        	            $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
            									$result=mysql_query($sqli);
            									$response['consulta']['sql'][]= $sqli;
												$response['consulta']['ejecucion'][]= ($result)?'Si':'No';
            									while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            									{
            										$claveAcceso=$row['ClaveAcceso'];
            									}
            									if ($claveAcceso != ''){
													$response['claveAcceso']= "SI";
            									    // echo "SI";
            									    }else{
														$response['claveAcceso']= "NO";
            									        // echo "NO";
            									    }
            			        
            			        }
    				
								
    				
    			    }
    			    else
    			    {
    				if($txtCambioFP>0.0 )
    				{
    				?> <div class='transparent_ajax_error'><p>Existe un saldo pendiente de cancelar <?php echo " ".mysql_error(); ?>;</p></div> <?php
    					
    				}
    				else
    				{
    				?> <div class='transparent_ajax_error'><p>Error: Valor a cobrar incorrecto <?php echo " ".mysql_error(); ?>;</p></div> <?php
    					
    				}
    			}
		
				echo json_encode($response) ;
				exit;
    	}
    			if ($cmbTipoDocumentoFVC==4)
    			{
    
    
    			$txtIva=$_POST['txtTotalIvaFVC'];
    						
    			if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
    			{            
    				$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
    				$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
    				<p>Error: '.mysql_error().' </p></div>  ');;
    				$iva=0;
					//$response['consulta']['sql'][]= $sqlIva1;
					//$response['consulta']['ejecucion'][]=  ($resultIva1)?'Si':'No';
    				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
    				{
    					$iva=$rowIva1['iva'];
    					$txtIdIva=$rowIva1['id_iva'];
    					$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
    				}
    				
    			}
    					
    			    $sql="insert into ventas (fecha_venta,      estado,       total,       sub_total,         sub0,               sub12,           descuento,        propina,      numero_factura_venta, fecha_anulacion, descripcion, id_iva, id_vendedor, id_cliente, id_empresa,	tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, RetIva,MotivoNota,total_iva) 
    				values  (             '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."', '".$propina."',   '".$numero_factura."',    '0000-00-00 00:00:00'  ,'".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$facAn."','".$motivo."','".$txtTotalIvaFVC."');";
    				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en Nota de Credito: '.mysql_error().' </p></div>  ');
    				$id_venta=mysql_insert_id();

    				for($i=1; $i<=$txtContadorFilas; $i++)
    				{
    					if($_POST['txtIdServicio'.$i] >= 1)
    					{ 
    					    
    						$cantidad = $_POST['txtCantidadS'.$i];
    						$idServicio = $_POST['txtIdServicio'.$i];
    						$idKardex = $_POST['txtIdServicio'.$i];
    						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
    						$valorTotal = $_POST['txtValorTotalS'.$i];
    						$txtdetalle2=$_POST['txtdetalle2'.$i];
    						$id_tipoP = $_POST['txtTipoS'.$i];
    						//echo "tipo".$id_tipoP;
    						$idBod = $_POST['idbod'.$i];
    						$idvalorPago=$_POST['txtValorS'.$i];
    						$bodegaCantidad=$_POST['bodegaCantidad'.$i];
    							if($bodegaCantidad!=''){
    							    $bodegaCantidad=$_POST['bodegaCantidad'.$i];
    							}else{
    							    $bodegaCantidad='0';
    							}
    							
    						$txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];
                            $id_iva = $_POST['IVA120'.$i];
								$sql_iva= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='" . $sesion_id_empresa . "' AND id_iva='" . $id_iva . "' ";
								$result_iva = mysql_query( $sql_iva );
								while($row_iva = mysql_fetch_array($result_iva) ){
									$tarifa_iva= $row_iva['iva'];
								}
								

								$total_iva= floatval($valorTotal) * (floatval( $tarifa_iva )/100);   

    						
    							
						$sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa, `tarifa_iva`, `total_iva`) 
						values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' ,'".$id_iva."', '".$total_iva."');";
						$resp2 = mysql_query($sql2) or die('<div class="alert alert-danger"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
						$id_detalle_venta=mysql_insert_id();
						//$response['consulta']['sql'][]= $sql2;
						//$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
						$response['detalle_ventas']= $id_detalle_venta;
    					}
    				}	
    			
    				$sqlNumnota ="update emision set numnota='".$numero_factura."' where id ='".$cmbEmi."' ";
    					$resultNOta=mysql_query($sqlNumnota) or die('<div class="transparent_ajax_error"><p>Error 1447'.mysql_error().'</p></div>  ');
    			$asiento=1;
    		
    if($asiento===1){
        	$tot_costo=0;
    				try	
    				{
    				  $sqlMNA="SELECT max(numero_asiento) AS max_numero_asiento
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
    				// Creacion del diario de la nota de credito
    		
    				$tipo_comprobante = "Diario"; 
    						try
    				{
    					$tipoComprobante = $tipo_comprobante;
    					$consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
    						     FROM `comprobantes` comprobantes
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
    	$fecha= date("Y-m-d h:i:s");
    				$descripcion = $txtNombreFVC." Nota de credito # ".$numero_factura;
    				$debe = $total;
    				$haber1 = $sub_total;
    				$haber2 = $_POST['txtTotalIvaFVC'];
    				$total_debe = $debe;
    				$total_haber = $haber1 + $haber2;
    				
    				$tipo_mov="D";
    	
    				//GUARDA EN  COMPROBANTES
    				$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values 
    				('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
    				$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
    				$id_comprobante=mysql_insert_id();
					//$response['consulta']['sql'][]= $sqlC;
					//$response['consulta']['ejecucion'][]=  ($respC)?'Si':'No';
					$response['comprobantes']= $id_comprobante;
    				
    				//GUARDA EN EL LIBRO DIARIO
    				$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
    					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
    					values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."',
    					'".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
    					'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
    	            $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    				$id_libro_diario=mysql_insert_id();
					//$response['consulta']['sql'][]= $sqlLD;
					//$response['consulta']['ejecucion'][]=  ($resp)?'Si':'No';
					$response['libro_diario']= $id_libro_diario;
    				
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
    										
    				$tot_ventas=0;
    				$tot_servicios=0;
    				$tot_costo=0;
    				$txtContadorFilas=8;
    					$listaServicios=array();
    	for($i=1; $i<=$txtContadorFilas; $i++)
    				{
    					if($_POST['txtIdServicio'.$i] >= 1)		
    					{										 //verifica si en el campo esta agregada una cuenta
    						$idProducto=$_POST['txtIdServicio'.$i];
    						$id_tipoP = $_POST['txtTipoS'.$i];
    						$sqlS = "SELECT productos.`id_producto` AS productos_id_servicio,
    								productos.`producto` AS productos_nombre, productos.`iva` AS productos_iva,
    								productos.`id_empresa` AS productos_id_empresa,	productos.`costo` AS productos_costo,                  
    								productos.`id_cuenta` AS productos_id_cuenta	
    							FROM
    								`productos` productos  Where productos.`id_empresa`='".$sesion_id_empresa."' and
    									productos.`id_producto` ='".$idProducto."'";
    						$resultS=mysql_query($sqlS) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							//$response['consulta']['sql'][]= $sqlS;
							//$response['consulta']['ejecucion'][]=  ($resultS)?'Si':'No';
                            $productos_costo=0;								
    						while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
    						{
    							$productos_id_cuenta=$rowS['productos_id_cuenta'];
    							//	$productos_costo=$rowS['productos_costo'];
    						}
    					//******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
    					
    						
    						if ($id_tipoP == "Inventario" or $id_tipoP=="P" or $id_tipoP=="I" or $id_tipoP=="1" )
    						{
    						     
    								$tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
    	//						//	$tot_costo=$tot_costo+($productos_costo * $_POST['txtCantidadS'.$i]);
    							//	$costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa,1);
    								$costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
    								$tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
    						}
    						else
    						{
    						 
    						    
    						    if(array_key_exists($productos_id_cuenta,$listaServicios)){
    						      $listaServicios[$productos_id_cuenta]=$listaServicios[$productos_id_cuenta]+ $_POST['txtValorTotalS'.$i]; 
    						    }else{
    						         $listaServicios[$productos_id_cuenta]=$_POST['txtValorTotalS'.$i]; 
    						    }
    						  
    							$tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
    						}
    					}
    				}
    			
    				try 
    				{
                        $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
    					WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
    					formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultMercaderia=mysql_query($sqlMercaderia);
						//$response['consulta']['sql'][]= $sqlMercaderia;
						//$response['consulta']['ejecucion'][]=  ($resultMercaderia)?'Si':'No';
                        $idcodigo_v=0;
                        while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
                        {
                            $idcodigo_v=$row['codigo_plan_cuentas'];
                        }
                        $idcodigo_v;
    				}
    				catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
    				try 
    				{
    					$sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
    					WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
    					formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultServicio=mysql_query($sqlServicio);
						//$response['consulta']['sql'][]= $sqlServicio;
						//$response['consulta']['ejecucion'][]=  ($resultServicio)?'Si':'No';
                        $idcodigo_s=0;
                        while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
                        {
                            $idcodigo_s=$row['codigo_plan_cuentas'];
                        }
                        $idcodigo_s;
    
    				}catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				
    
    						
    				$sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
          				from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
    						( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
    
    				$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					//$response['consulta']['sql'][]= $sql;
					//$response['consulta']['ejecucion'][]=  ($resultS)?'Si':'No';			
    				$plan_id_cuenta_vta=0;
    				$plan_id_cuenta_servicio=0;
    				while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
    				{
    					if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
    						{
    							$plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
    						}
    					if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
    						{
    							$plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
    						}
    				}
    
    				$lin_diario=0;
    				// echo             "<==> total <==>".$total."<==>";
					$response['tot_ventas'][]= $tot_ventas;
    				if ($tot_ventas!=0)
    				{
    					$lin_diario=$lin_diario+1;
    					$idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
    					$debeVector[$lin_diario]=$tot_ventas;
    					$haberVector[$lin_diario]=0;
    				}
					$response['tot_servicios'][]= $tot_servicios;
    				if ($tot_servicios!=0)
    				{
    				    foreach ($listaServicios as $key => $value) {
                        $lin_diario=$lin_diario+1;
    					$idPlanCuentas[$lin_diario]= $key;
    					$debeVector[$lin_diario]=$value;
    					$haberVector[$lin_diario]=0;
                        }
    				}
    				//echo $impuestos_id_plan_cuenta;
    				$lin_diario=$lin_diario+1;
    				$idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
    				$debeVector[$lin_diario]=$txtTotalIvaFVC;
    				$haberVector[$lin_diario]=0;
    				
    				$sql = "SELECT id_plan_cuenta FROM formas_pago  
    					WHERE id_empresa = '" .$sesion_id_empresa. "' and id_tipo_movimiento=13 LIMIT 1"; 
    				$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					//$response['consulta']['sql'][]= $sql;
					//$response['consulta']['ejecucion'][]=  ($resultado)?'Si':'No';	
    						
    				while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
    				{
    					$lin_diario=$lin_diario+1;
    					$idPlanCuentas[$lin_diario]=$row['id_plan_cuenta'];
    					$debeVector[$lin_diario]=0;
    					$haberVector[$lin_diario]=$total;	
    				}
    					for($i=1; $i<=$lin_diario; $i++)
    				{
    					if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
    					{
    
    						//GUARDA EN EL DETALLE LIBRO DIARIO
    					$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
    							('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
    							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    							$id_detalle_libro_diario=mysql_insert_id();
								//$response['consulta']['sql'][]= $sqlDLD;
								//$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
								$response['detalle_libro_diario'][]= $id_detalle_libro_diario;		
    							
    							// CONSULTAS PARA GENERAR LA MAYORIZACION
    						$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
    							$result5=mysql_query($sql5);
								//$response['consulta']['sql'][]= $sql5;
								//$response['consulta']['ejecucion'][]=  ($result5)?'Si':'No';
			
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
    								$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
    								$result6=mysql_query($sql6);
    								$id_mayorizacion=mysql_insert_id();
									//$response['consulta']['sql'][]= $sql6;
									//$response['consulta']['ejecucion'][]=  ($result6)?'Si':'No';
									$response['mayorizacion'][]= $id_mayorizacion;
    							}
    							catch(Exception $ex) 
    								{ ?> <div class="transparent_ajax_error">
    									<p>Error en la insercion de la tabla mayorizacion: 
    									<?php echo "".$ex ?></p></div> <?php }
    								// FIN DE MAYORIZACION
    						}
    					}
    				}

					$response['tot_costo']= $tot_costo;
    				if ($tot_costo>0)
    				{
    					
    			$numero_asiento++;
    					$tipo_comprobante = "Diario"; 
    				// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
    					try
    					{
    						$tipoComprobante = $tipo_comprobante;
    							$consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
    							FROM `comprobantes` comprobantes
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

    		
    					$fecha= date("Y-m-d h:i:s");
    					
    					$descripcion="Asiento de costo de nota de credito No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
    		
    										
    					$total_debe  = $tot_costo;
    					$total_haber = $tot_costo;
    					
    	
    					$tipo_mov="4";
    	
    				//GUARDA EN  COMPROBANTES
    					$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
    					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
    					$id_comprobante=mysql_insert_id();
						//$response['consulta']['sql'][]= $sqlC;
						//$response['consulta']['ejecucion'][]=  ($respC)?'Si':'No';
						$response['comprobantes'][]= $id_comprobante;
    				//GUARDA EN EL LIBRO DIARIO
    					$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
    						total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
    						values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
    						'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
    						'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
    
    					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    					$id_libro_diario=mysql_insert_id();
						//$response['consulta']['sql'][]= $sqlLD;
						//$response['consulta']['ejecucion'][]=  ($resp)?'Si':'No';
						$response['libro_diario'][]= $id_libro_diario;
    					try 
    					{
    						$sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
    						WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
    						$resultCosto=mysql_query($sqlCosto);
							//$response['consulta']['sql'][]= $sqlCosto;
							//$response['consulta']['ejecucion'][]=  ($resultCosto)?'Si':'No';
						
    						$idcodigo_v=0;
    						while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
    						{
    							$idcod_costo=$row['codigo_plan_cuentas'];
    						}
    						$idcod_costo;
    
    					}catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
    		//	echo 	$idcod_costo;	
    					
    	//		$idcod_costo     ="51001001";
    	
    					try 
    					{
    						$sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas
    						WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
    						$resultInventario=mysql_query($sqlInventario);
							//$response['consulta']['sql'][]= $sqlInventario;
							//$response['consulta']['ejecucion'][]=  ($resultInventario)?'Si':'No';
    						$idcodigo_v=0;
    						while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
    						{
    							$idcod_inventario=$row['codigo_plan_cuentas'];
    						}
    						$idcod_inventario;
    					}
    					catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
    		
    		//	echo "</br>".	$idcod_inventario;	
    	       
    //			$idcod_inventario="115001001"
    						
    					$sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
    							( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
    					//echo $sql;
    						$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							//$response['consulta']['sql'][]= $sql;
							//$response['consulta']['ejecucion'][]=  ($resultS)?'Si':'No';					
    						$plan_id_cuenta_costo=0;
    						$plan_id_cuenta_inventario=0;
    					while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
    					{
    						if ($rowS['plan_cuentas_codigo']==$idcod_costo)
    						{
    							$plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
    						}
    						if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
    						{
    							$plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
    						}
    					}
    					$idPlanCuentas=array();
    					$debeVector=array();
    					$haberVector= array();
    					$lin_diario=0;
    					if ($tot_costo>0)
    					{
    						$lin_diario=$lin_diario+1;
    						$idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
    						$debeVector[$lin_diario]=$tot_costo;
    						$haberVector[$lin_diario]=0;
    							
    						$lin_diario=$lin_diario+1;
    						$idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
    						$debeVector[$lin_diario]=0;
    						$haberVector[$lin_diario]=$tot_costo;			
    //echo $idPlanCuentas[$lin_diario];
    //echo $idPlanCuentas[$lin_diario];										
    					}
    		

    					for($i=1; $i<=$lin_diario; $i++)
    					{
    							//echo "entro a costo";
    						if ($idPlanCuentas[$i] !="")
    						{
    						
    						//GUARDA EN EL DETALLE LIBRO DIARIO
    							$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario,id_plan_cuenta,debe, haber,
    									id_periodo_contable) values 
    								('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
    								'".$sesion_id_periodo_contable."');";
    					//	echo "<br>".$sqlDLD;
    							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    							$id_detalle_libro_diario=mysql_insert_id();
								//$response['consulta']['sql'][]= $sqlDLD;
								//$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
								$response['detalle_libro_diario'][]= $id_detalle_libro_diario;
    								
    							// CONSULTAS PARA GENERAR LA MAYORIZACION
    							$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
    							$result5=mysql_query($sql5);
								//$response['consulta']['sql'][]= $sql5;
								//$response['consulta']['ejecucion'][]=  ($result5)?'Si':'No';
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
    
    									$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
    									$result6=mysql_query($sql6);
    									$id_mayorizacion=mysql_insert_id();
										//$response['consulta']['sql'][]= $sql6;
										//$response['consulta']['ejecucion'][]=  ($result6)?'Si':'No';
										$response['mayorizacion'][]= $id_mayorizacion;
    								}
    									catch(Exception $ex) 
    									{ ?> <div class="transparent_ajax_error">
    										<p>Error en la insercion de la tabla mayorizacion: 
    										<?php echo "".$ex ?></p></div> <?php }
    								// FIN DE MAYORIZACION
    							}  
    						}
    					}						
    				}
    			
    }// fin asiento
    			
    			
    			
    			
    				
    				if ($total>0)
    				{
    					$tipo_documentox="NotaCredito";
    					$referencia=$tipo_documentox."No.".$numero_factura;
    					$estadoCC="Pendientes";
    					
                        $mod_date = strtotime($fecha_venta."+ 30 days");
                        $fecha_nueva = date("Y-m-d",$mod_date);
                        
                       
                            	$sql3 = "insert into cuentas_por_pagar (tipo_documento,numero_compra, referencia, valor, saldo,
    						numero_asiento, fecha_vencimiento, id_proveedor, id_cliente,id_plan_cuenta, id_empresa, 
    						id_compra, estado) 
    				      values ('".$tipo_documentox."','".$numero_factura."','".$referencia."','".$total."',
    					  '".$total."','".$id_libro_diario."','".$fecha_nueva."','0','".$id_cliente."','0','".$sesion_id_empresa."',
    					'".$id_venta."', '".$estadoCC."');";
                        
    				
    //echo $sql3;
    				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
    				$id_cuenta_por_pagar=mysql_insert_id();
    				//$response['consulta']['sql'][]= $sql3;
					//$response['consulta']['ejecucion'][]=  ($resp3)?'Si':'No';
				// 	$response['cuentas_por_pagar'][]= $id_cuenta_por_pagar;	
    				}
    					
    			 
    				$sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
    				('".$fecha_venta."','Nota Credito en Venta','".$id_venta."', '".$sesion_id_empresa."')";
    				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
    				$id_kardes=mysql_insert_id();
					//$response['consulta']['sql'][]= $sqlk;
					//$response['consulta']['ejecucion'][]=  ($resultk)?'Si':'No';
				// 	$response['cuentas_por_pagar'][]= $id_kardes;	
    				if($result && $resp2)
    				{
						$response['guardo']='SI';	
    					// echo "1";
    				}
    				else{
						$response['guardo']='NO';
    					// echo "3";
    					}
    					
						$response['emision_tipoEmision']=$emision_tipoEmision;
    					if ($emision_tipoEmision === 'E'){
            			        genXmlnc($id_venta);
            			        
            			        	            $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
            									$result=mysql_query($sqli);
												//$response['consulta']['sql'][]= $sqli;
												//$response['consulta']['ejecucion'][]=  ($result)?'Si':'No';
            									
            									while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            									{
            										$claveAcceso=$row['ClaveAcceso'];
            									}
												
            									if ($claveAcceso != ''){
													$response['claveAcceso']=  "SI";
        									    }else{
													$response['claveAcceso']= "NO";
        									        
        									    }     
            			}else {
            			   $response['claveAcceso']=  "F"; 
            			}
    					echo json_encode($response);
    			}
    		    
    		    if ($cmbTipoDocumentoFVC==6){
    		        
    		      //  echo "guia==>1";
    		            
    		            $txtIdIva2 = $_POST['txtTotalIvaFVC']; 
    		            $FechaFin = $_POST['FechaFin']; 
    		            $FechaInicio = $_POST['FechaInicio']; 
    		            $motivoT = $_POST['MotivoTraslado']; 
    		            $Vendedor_id = $_POST['chofer_id']; 
    		            $DirDestino = $_POST['DirDestino']; 
    		             $DirOrigen = $_POST['DirOrigen']; 
    		            $txtIva=$_POST['txtTotalIvaFVC'];
    						
    			if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
    			{            
    				$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
    				$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
    				<p>Error: '.mysql_error().' </p></div>  ');;
    				$iva=0;
    				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
    				{
    					$iva=$rowIva1['iva'];
    					$txtIdIva=$rowIva1['id_iva'];
    					$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
    				}
    				
    			}
    
    		        	
    					$sql="insert into ventas (fecha_venta,       estado,        total, sub_total,sub0,sub12,descuento,propina,numero_factura_venta, fecha_anulacion, descripcion, id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, FechaInicio,FechaFin,DirDestino,DirOrigen,Vendedor_id,MotivoTraslado, RetIva) 
    					values (                '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$FechaInicio."','".$FechaFin."','".$DirDestino."','".$DirOrigen."','".$Vendedor_id."','".$motivoT."','".$facAn."');";
    
    					$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
    					$id_venta=mysql_insert_id();
    
    					if($limite==0){
    					    $limite=0;
    					}else if($limite==1){
    					    $limite=$limite-2;
    					}else{
    					    $limite=$limite-1;
    					}
    					
    					$sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
    					$resultEmpresa2=mysql_query($sqlEmpresa2) or die();
    				
    			if($sql){
    						    for($i=1; $i<=$txtContadorFilas; $i++)
    				{
    
    					if($_POST['txtIdServicio'.$i] >= 1)
    					{ //verifica si en el campo esta agregada una cuenta
    			
    						$cantidad = $_POST['txtCantidadS'.$i];
    						$idServicio = $_POST['txtIdServicio'.$i];
    						$idKardex = $_POST['txtIdServicio'.$i];
    						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
    						$valorTotal = $_POST['txtValorTotalS'.$i];
    						
    						$id_tipoP = $_POST['txtTipoS'.$i];
    			            $idBod = $_POST['idbod'.$i];
    						$idvalorPago=$_POST['txtValorS'.$i];
    						$txtdetalle2=$_POST['txtdetalle2'.$i];
    									//GUARDA EN EL DETALLE VENTAS
    						$sql2 = "insert into detalle_ventas ( cantidad, estado, v_unitario, v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa) 
    						values ('".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."'	, '".$txtdetalle2."' ,'".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";
    
    						$resp2 = mysql_query($sql2) or die('<div class="alert alert-danger"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
    
    						$id_detalle_venta=mysql_insert_id();
    						
    						if($sql2){
    						  //  echo "guia";
    
            			        generarXMLGUIA($id_venta);
            			     //  print_r($result);
            			        
                			        	            $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
                			        	            // echo $sqli;
                									$result=mysql_query($sqli);
                									
                									while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                									{
                										$claveAcceso=$row['ClaveAcceso'];
                									}
                									if ($claveAcceso != ''){
                									    echo "SI";
                									    }else{
                									        echo "NO";
                									    }
            									
            									
            			        
            // 			}
    						    
    						}else{
    						    echo "guiano";
    						}
    
    
                
                
    					}
    					
    
    				}
    						    
    						}else{
    						    echo "2";
    						}
    		        
    
    			   
    		    	    
    		    	}
    		    if ($cmbTipoDocumentoFVC==5){
    			     $txtIva=$_POST['txtTotalIvaFVC'];
    			    
    			    		if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
    			{            
    				$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
    				$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    				// echo $sqlIva1;
    				$iva=0;
    				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
    				{
    					$iva=$rowIva1['iva'];
    					$txtIdIva=$rowIva1['id_iva'];
    					$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
    				}
    				
    			}
    				if($id_cliente!="" && $numero_factura !="" && $_POST['txtSubtotalFVC']>0  )
    			    {       
    					 $sql="insert into ventas (fecha_venta, estado, total, sub_total,sub0,sub12,                                     descuento,      propina,        numero_factura_venta, fecha_anulacion, descripcion,              id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario,total_iva) 
    					values ('".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,            '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$txtTotalIvaFVC."');";
    // echo $sql;
    					$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar proforma: '.mysql_error().' </p></div>  ');
    					$id_venta=mysql_insert_id();
    					
    								if($limite==0){
    					    $limite=0;
    					}else if($limite==1){
    					    $limite=$limite-2;
    					}else{
    					    $limite=$limite-1;
    					}
    					
    					$sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
    					$resultEmpresa2=mysql_query($sqlEmpresa2) or die();
    				
    			     
    					for($i=1; $i<=$txtContadorFilas; $i++)	
    					{
    					if($_POST['txtIdServicio'.$i] >= 1)
    					{ //verifica si en el campo esta agregada una cuenta
    			
    							$cantidad = $_POST['txtCantidadS'.$i];
    							$idServicio = $_POST['txtIdServicio'.$i];
    							$idKardex = $_POST['txtIdServicio'.$i];
    							$valorUnitario = $_POST['txtValorUnitarioS'.$i];
    							$valorTotal = $_POST['txtValorTotalS'.$i];
    						    $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
    						    $txtTipo11 = $_POST['txtTipo'.$i];
    						    
    							$id_tipoP = $_POST['txtTipoS'.$i];
    							$cuenta = $_POST['cuenta'.$i];
    							$idBod = $_POST['idbod'.$i];
    							$idvalorPago=$_POST['txtValorS'.$i];
    						
    						
    							$txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
    							if ($id_tipoP == "2" && $txtDescripcionS!='') {
                                    $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
                                    $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
                                    // echo "UPDATE PRODUCTO ==>".$sql3;
                                }
    							
    							if ($sql){
    							     $id_iva = $_POST['IVA120'.$i];
                                $sql_iva= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='" . $sesion_id_empresa . "' AND id_iva='" . $id_iva . "' ";
                                $result_iva = mysql_query( $sql_iva );
                                while($row_iva = mysql_fetch_array($result_iva) ){
                                    $tarifa_iva= $row_iva['iva'];
                                }
                                

                                $total_iva= floatval($valorTotal) * (floatval( $tarifa_iva )/100);
    							    	$sql2 = "insert into detalle_ventas ( idBodega,cantidad, estado, v_unitario, v_total, 	id_venta, id_servicio,id_kardex,tipo_venta, id_empresa, `tarifa_iva`, `total_iva`) 
    							values ('".$idBod."','".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."'	, '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."','".$id_iva."', '".$total_iva."' );";
    							$resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
    							<p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
    							$id_detalle_venta=mysql_insert_id();
    							if($sql2){
    							    echo "1";
    							}else{
    							    echo "3";
    							}
    							}else{
    							    echo "2";
    							}
    							
    							//GUARDA EN EL DETALLE VENTAS
    						
    						}
    					}
    								
    			    }
    			    else
    			    {
    				echo 'Datos vacios';
    			}
    		}
    		
    		}
    		catch (Exception $e) {
    			// Error en algun momento.
    			   ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
    		}
        } else {
           echo "Limite de factura alcanzado, revise por favor con su proveedor";
        }
    } else {
        echo "La factura está repetida, revisa la numeración por favor";
    }


	    

	
	
	
}






	if($accion == "2")
	{
// GUARDAR MODIFICACION FACTURA VENTA PAGINA: modificarFacturaVenta.php
		try
		 {
			$id_venta=$_POST['txtIdVenta'];
			$id_cliente=$_POST['txtIdCliente'];
			$fecha_venta=$_POST['textFecha'];
			$numero_factura=$_POST['txtFactura'];
			$total=$_POST['txtTotal'];
			$sub_total=$_POST['txtSubtotal'];
			$iva=$_POST['txtIdIva'];
			$id_usuario=$_POST['cmbIdUsuario'];

			if($fecha_venta!="" && $id_cliente!="" && $iva!="")
			{

				$sql="update ventas set fecha_venta='".$fecha_venta."', total='".$total."', sub_total='".$sub_total."', id_iva='".$iva."', id_cliente='".$id_cliente."', numero_factura_venta='".$numero_factura."', id_usuario='".$id_usuario."' WHERE id_venta='".$id_venta."' ;";
				$result=mysql_query($sql);
				if ($result)
				{ ?> <div class='transparent_ajax_correcto'><p>Registro Modificado correctamente.</p></div> <?php }
				else
				{ ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Revise que haya ingresado todos los datos correctamente. o Consulte con el Administrador <?php echo " ".mysql_error(); ?>;</p></div> <?php }

				$sqldelet = "delete from detalle_ventas where id_venta='".$id_venta."';";
				$resultdelet=mysql_query($sqldelet);
				//Inserccion a tabla detalle_ventas
				$cant=2;
				$valun=2;
				$valtotal=2;
				$idp=2;
				$st=2;//stock       

				for($j=1;$j<=13;$j++){
					//FILAS
					$id_producto2=$_POST['idproducto'.$idp];
					if($id_producto2!=0)
					{
					$sqlm="Select max(id_detalle_venta) From detalle_ventas;";
					$resultm=mysql_query($sqlm) or die("\nError al sacar el id maximo de detalles_compras Fila 2: ".mysql_error());
					$id_detalle_venta='0';
					while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
					{
						$id_detalle_venta=$rowm['max(id_detalle_venta)'];
					}
					$id_detalle_venta++;

					$cantidad2=$_POST['cant'.$cant];
					$valor_unitario2=$_POST['valun'.$valun];
					$valor_total2=$_POST['valtotal'.$valtotal];
					$sql2="insert into detalle_ventas ( cantidad, estado, v_unitario, v_total, id_venta, id_producto) values ('".$cantidad2."','Activo','".$valor_unitario2."','".$valor_total2."','".$id_venta."','".$id_producto2."');";
					$result2=mysql_query($sql2) or die("\nError al guardar detalles compra Fila 2: ".mysql_error());
					$id_detalle_venta=mysql_insert_id();
					
					$stock2=$_POST['stock'.$st] - $cantidad2; // RESTA EL STOCK
					$sql22="update productos set stock = '".$stock2."' where id_producto='".$id_producto2."';";
					$result22=mysql_query($sql22) or die("\nError al actualizar el Stock Fila 2: ".mysql_error());
					}
					
					$st++;
					$idp++;
					$cant++;
					$valun++;
					$valtotal++;
				}

			// GUARDAR EN KARDEX

			$sqlk="UPDATE kardes SET fecha='".$fecha_venta."' WHERE id_factura='".$id_venta."' AND detalle='Venta';";
			$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());

		}//fin if de pregunta, si hay datos guardar factura
		else{
			?> <div class="transparent_ajax_error"><p>Error: No ha ingresado suficiente datos de factura <?php echo " ".mysql_error(); ?></p></div> <?php
		}

		}catch (Exception $e) {
		// Error en algun momento.
		   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
		}

	}


	if($accion == "3"){
		// ANULAR FACTURA venta PAGINA: nuevaFacturaventa.php
		 try
		 {
			if(isset ($_POST['idProveedor'])){
			  $idProveedor = $_POST['idProveedor'];

			  //cambia el estado a libre y limpia el usuario y cedula
			  $sql3 = "delete from proveedores WHERE id_proveedor='".$idProveedor."'; ";
			  $result3=mysql_query($sql3);

			   if($result3){
				   echo "Registro eliminado correctamente.";
				  }
			 else{
				 echo "Error al eliminar los datos: ".mysql_error();
				 }
			 }else{
			  echo "Fallo en el envio del Formulario: No hay datos, ".mysql_error();
		  }

		 }catch (Exception $e) {
		// Error en algun momento.
		   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}

	}


	if($accion == "4"){
		// VALIDA PARA QUE EL RUC DEL PROVEEDOR NO SE REPITA PAGINA: proveedores.php
		 try
		 {
			if(isset ($_POST['ruc'])){
			  $nombre1 = $_POST['ruc'];
			  $sql1 = "SELECT ruc from proveedores where ruc='".$nombre1."'; ";
			  $resp1 = mysql_query($sql1);
			  $entro=0;
			  while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
						{
							$var1=$row1["ruc"];
						}
			   $nombre2 = strtolower($nombre1);
			   $var2 = strtolower($var1);
			  if($var2==$nombre2){
				   if($var2==""&&$nombre2==""){
						 $entro=0;
					  }else{
						  $entro=1;
					  }
			  }
			 echo $entro;
			 }

		 }catch (Exception $e) {
		// Error en algun momento.
		   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}

	}


	if($accion == "5"){
	// GUARDAR FACTURA VENTA AUTOMATICAMENTE CONDOMINIOS PAGINA: facturaAutomaticamenteCondominios.php

		//COJE LOS IDES DE LOS CHECK SERVICIOS SELECCIONADOS    
		$txtLimiteServicios=$_POST['txtLimiteServicios'];  
		$p = 1;
		for($i=1; $i<=$txtLimiteServicios; $i++){
			if(isset($_POST['checkServicios'.$i])){            
				$vec_id_servicios[$p] = $_POST['checkServicios'.$i];  
				$p ++;            
			}
		}      
		$tamano_vector_servicios = count($vec_id_servicios);
		
		//COJE LOS IDES DE LOS CHECK CLIENTES SELECCIONADOS    
		$txtLimiteClientes=$_POST['txtLimiteClientes'];   
		$q = 1;
		for($b=1; $b<=$txtLimiteClientes; $b++){
			if(isset($_POST['checkClientes'.$b])){
				$vec_id_clientes[$q] = $_POST['checkClientes'.$b];
				$q++;
			}
		}
		//echo $vec_id_clientes;
		$tamano_vector_clientes = count($vec_id_clientes);
		//echo $tamano_vector_clientes;
		
		// ID FORMA DE PAGO
		$cmbFormaPagoFP=$_POST['cmbFormaPagoFP']; 
		$idFormaPago = explode("*", $cmbFormaPagoFP); //split  explode
		//echo "ID FORMA DE PAGO: ".$idFormaPago[0];    
			
		$cmbTipoDocumentoFVC="Registro Nro."; 
		$estado = "Activo";
		$cantidad = 1;
		$cmbIdVendedor=$_POST['cmbIdVendedorFVC'];
		
		
		for($c=1; $c<=$tamano_vector_servicios;$c++){ //*********************    FOR PRIMARIO SERVICIOS   *******************************
			for($d=1; $d<=$tamano_vector_clientes;$d++){//********************  FORM SECUNDARIO CLIENTES  ****************************
				
				$fecha_venta= date("Y-m-d h:i:s");   
												   
				// NUMERO DE FACTURA
				$sqlp="Select max(numero_factura_venta), max(id_venta) From ventas where id_empresa='".$sesion_id_empresa."';";
				$resultV=mysql_query($sqlp);
				$numero_factura_venta='0';
				$id_venta="0";
				while($row=mysql_fetch_array($resultV))//permite ir de fila en fila de la tabla
				{
					$numero_factura_venta=$row['max(numero_factura_venta)'];
					$id_venta=$row['max(id_venta)'];
				}
				$numero_factura_venta++;
				$id_venta++;
				$txtDescripcion = "Factura Nro: ".$numero_factura_venta." generado automaticamente por el usuario: ".$sesion_id_empleado;
				
				// SACA SERVICIOS
				$sqlServicios = "SELECT * FROM servicios where id_empresa='".$sesion_id_empresa."' and id_servicio='".$vec_id_servicios[$c]."';";
				$resultServicios=mysql_query($sqlServicios) or die('<div class="transparent_ajax_error"><p>Error en servicios: '.mysql_error().' </p></div>  ');;
				while($rowServicios=mysql_fetch_array($resultServicios))//permite ir de fila en fila de la tabla
				{
					$precio_venta1=$rowServicios['precio_venta1'];
					$servicios_iva=$rowServicios['iva'];
					$servicios_id_plan_cuenta=$rowServicios['id_plan_cuenta'];
				}
				
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
						$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
					}
					$total_iva = ($precio_venta1 * $iva)/100;
					$total = $precio_venta1 + $total_iva;
				}else{
					$total = $precio_venta1;
					$txtIdIva = 0;
					$total_iva = 0;
					$impuestos_id_plan_cuenta = 0;
				}
				
				$sub_total = ($precio_venta1 * $cantidad) * 1;
				
				$sql="insert into ventas ( fecha_venta, estado, total, sub_total, numero_factura_venta, fecha_anulacion, descripcion, id_iva, id_vendedor, id_cliente, id_empresa, tipo_documento, id_forma_pago) values ('".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$numero_factura_venta."', '','".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$vec_id_clientes[$d]."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$idFormaPago[0]."');";
				//echo $sql;
				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
				$id_venta=mysql_insert_id();
				//*******************************************   GUARDA EN DETALLE VENTAS   **************************************************
								
				//permite sacar el id maximo de detalle_ventas
				try {
					$sqli="Select max(id_detalle_venta) From detalle_ventas";
					$resultM=mysql_query($sqli) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
					$id_detalle_venta=0;
					while($row=mysql_fetch_array($resultM))//permite ir de fila en fila de la tabla
					{
						  $id_detalle_venta=$row['max(id_detalle_venta)'];
					}
					$id_detalle_venta++;

				}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

				//$cantidad = $cantidad;
				$idServicio = $vec_id_servicios[$c];
				$valorUnitario = $precio_venta1;
				$valorTotal = $precio_venta1 * $cantidad;

				//GUARDA EN EL DETALLE VENTAS
				$sql2 = "insert into detalle_ventas (cantidad, estado, v_unitario, v_total, id_venta, id_servicio) values ('".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."');";
				$resp2 = mysql_query($sql2) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas: '.$sql2." -- ".mysql_error().' </p></div>  ');
				$id_detalle_venta=mysql_insert_id();		
						
						
				//************************************ GUARDAR ASIENTO CONTABLE ***************************************/
				//****************************************** LIBRO DIARIO ***************************************/
				
				//SACA CLIENTES
				$sqlCliente = "SELECT
				clientes.`id_cliente` AS clientes_id_cliente,
				clientes.`nombre` AS clientes_nombre,
				clientes.`apellido` AS clientes_apellido,
				clientes.`cedula` AS clientes_cedula,
				clientes.`id_empresa` AS clientes_id_empresa,
				clientes.`descuento` AS clientes_descuento
		   FROM
				`clientes` clientes
				WHERE clientes.`id_cliente`='".$vec_id_clientes[$d]."';";
				$resultC=mysql_query($sqlCliente);
				while($rowC=mysql_fetch_array($resultC))//permite ir de fila en fila de la tabla
				{
					$clientes_nombre=$rowC['clientes_nombre'];
					$clientes_apellido=$rowC['clientes_apellido'];
				}
				
				//permite sacar el numero_asiento de libro_diario
				try{
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

				}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

				//permite sacar el id maximo de libro_diario
				try{
					$sqlm="Select max(id_libro_diario) From libro_diario";
					$resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					$id_libro_diario=0;
					while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
					{
						$id_libro_diario=$rowm['max(id_libro_diario)'];
					}
					$id_libro_diario++;

				}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
						
						
					// CONSULTA A LA TABLA FORMAS DE PAGO
					$sqlFP = "SELECT
					formas_pago.`id_forma_pago` AS formas_pago_id_forma_pago,
					formas_pago.`nombre` AS formas_pago_nombre,
					formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
					formas_pago.`id_empresa` AS formas_pago_id_empresa,
					formas_pago.`id_tipo_movimiento` AS formas_pago_id_tipo_movimiento,
					formas_pago.`diario` AS formas_pago_diario,
					formas_pago.`ingreso` AS formas_pago_ingreso,
					formas_pago.`egreso` AS formas_pago_egreso
			   FROM
					`formas_pago` formas_pago 
					Where formas_pago.`id_forma_pago`='".$idFormaPago[0]."';";
					$resultFP=mysql_query($sqlFP) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

					while($rowFP=mysql_fetch_array($resultFP))//permite ir de fila en fila de la tabla
					{
						$formas_pago_diario=$rowFP['formas_pago_diario'];
						$formas_pago_ingreso=$rowFP['formas_pago_ingreso'];
						$formas_pago_egreso=$rowFP['formas_pago_egreso'];
						$formas_pago_id_plan_cuenta=$rowFP['formas_pago_id_plan_cuenta'];
					}

					// BUSCA QUE TIPO DE COMPROBANTE ES
					switch ("Si"){
						case $formas_pago_diario:  {
							$tipo_comprobante = "Diario"; 
							break;
						}

						case $formas_pago_ingreso: {
							$tipo_comprobante = "Ingreso"; 
							break;
						}               
						case $formas_pago_egreso: {
							$tipo_comprobante = "Egreso"; 
							break;
						}                                
					}

					// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
					try{
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

					}catch (Exception $e) {
						// Error en algun momento.
					   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
					}

					//SACA EL ID MAX DE COMPROBANTES
					try{
						$sqlCM="Select max(id_comprobante) From comprobantes;";
						$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_comprobante=0;
						while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
						{
							$id_comprobante=$rowCM['max(id_comprobante)'];
						}
						$id_comprobante++;

					}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

					$fecha= date("Y-m-d h:i:s");
					$descripcion = $clientes_nombre." ".$clientes_apellido." ".$cmbTipoDocumentoFVC." ".$numero_factura_venta.". Generado Automaticamente";
					
					$debe = $total;
					$haber1 = $sub_total;
					$haber2 = $total_iva;
					$total_debe = $debe;
					$total_haber = $haber1 + $haber2;

					//GUARDA EN  COMPROBANTES
					$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					$id_comprobante=mysql_insert_id();
					//GUARDA EN EL LIBRO DIARIO
					$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante) values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."','".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."', '".$id_comprobante."')";
					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					$id_libro_diario=mysql_insert_id();
									
					//************************************    GUARDA EN EL DETALLE LIBRO DIARIO      **********************
											
					$idPlanCuentas[1] = $formas_pago_id_plan_cuenta;
					$idPlanCuentas[2] = $servicios_id_plan_cuenta;                    
					$idPlanCuentas[3] = $impuestos_id_plan_cuenta;

					$debeVector[1] = $total;
					$debeVector[2] = 0;
					$debeVector[3] = 0;
					$haberVector[1] = 0;
					$haberVector[2] = $sub_total;
					$haberVector[3] = $total_iva;

					if($servicios_iva == "Si"){
						$limite = 3;
					}else{
						$limite = 2;
					}

					for($i=1; $i<=$limite; $i++){

						//permite sacar el id maximo de detalle_libro_diario
						try {
							$sqli="Select max(id_detalle_libro_diario) From detalle_libro_diario";
							$resultmd=mysql_query($sqli);
							$id_detalle_libro_diario=0;
							while($row=mysql_fetch_array($resultmd))//permite ir de fila en fila de la tabla
							{
								  $id_detalle_libro_diario=$row['max(id_detalle_libro_diario)'];
							}
							$id_detalle_libro_diario++;

						}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }


						//GUARDA EN EL DETALLE LIBRO DIARIO
						$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta, debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."','".$sesion_id_periodo_contable."');";
						$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_detalle_libro_diario=mysql_insert_id();
						// CONSULTAS PARA GENERAR LA MAYORIZACION
						$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
						$result5=mysql_query($sql5);
						while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
							{
								$id_mayorizacion=$row5['id_mayorizacion'];
							}
						$numero = mysql_num_rows($result5); // obtenemos el número de filas
						if($numero > 0){
							   // si hay filas

						}else {
							// no hay filas
							//INSERCION DE LA TABLA MAYORIZACION
							try {
								//permite sacar el id maximo de la tabla mayorizacion
								$sqli6="Select max(id_mayorizacion) From mayorizacion";
								$resulti6=mysql_query($sqli6);
								$id_mayorizacion=0;
								while($row6=mysql_fetch_array($resulti6))//permite ir de fila en fila de la tabla
								{
									$id_mayorizacion=$row6['max(id_mayorizacion)'];
								}
								$id_mayorizacion++;

								$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
								$result6=mysql_query($sql6);
								$id_mayorizacion=mysql_insert_id();
							}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }

						}
					}    
				
				//**************************************************************************************************************
				
								/*  GUARDAR EN CUENTAS POR COBRAR */ 
										
				$tipoMovimiento = $idFormaPago[1];
				$referencia = $clientes_nombre." ".$clientes_apellido." ".$cmbTipoDocumentoFVC." ".$numero_factura_venta;
				
				if($tipoMovimiento == "4"){
					
					$cuotas = $total;                
					$estadoCC = "Pendientes";                 
					for($i=0; $i<1; $i++){
						
						$date = date("d-m-Y");
						//Incrementando meses
						$mod_date = strtotime($date."+ ".$i." months");
						$fecha_nueva = date("Y-m-d",$mod_date);
						
						$sqlm2="Select max(id_cuenta_por_cobrar) From cuentas_por_cobrar;";
						$resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_cuenta_por_cobrar=0;
						while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
						{
							$id_cuenta_por_cobrar=$rowm2['max(id_cuenta_por_cobrar)'];
						}
						$id_cuenta_por_cobrar++;
						
						$sql3 = "insert into cuentas_por_cobrar ( numero_factura, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago,id_proveedor ,id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
							. "values ('".$numero_factura_venta."','".$referencia."','".$cuotas."','".$cuotas."','', '".$fecha_nueva."', '', null,'".$vec_id_clientes[$d]."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."','".$estadoCC."');";
						$resp3 = mysql_query($sql3) or die('<div class="transparent_ajax_error"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
						$id_cuenta_por_cobrar=mysql_insert_id();
					echo $sql3;
					}                

				}
			}
		}	   
	}



	if($accion == "7")
	{
    // ELIMINA CARGOS PAGINA: cargos.php
		try
		{
			//echo "codigo";
			//echo $_POST[id_venta];
			if(isset ($_POST['id_venta']))
			{
				$id_venta = $_POST['id_venta'];
				$sql4 = "delete from detalle_ventas where id_venta='".$id_venta."';  ";
				//echo $sql4;
				$resp4 = mysql_query($sql4);
				if(!mysql_query($sql4)){
					echo "Ocurrio un error\n$sql4";
				}
				else
				{
					echo "El detalle registro ha sido Eliminado."; 
				}
			}
			else
				{
					?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
				}
		}
		catch (Exception $e)
		{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
   
		}
		
		try
		{
			//echo "codigo";
			//echo $_POST[id_venta];
			if(isset ($_POST['id_venta'])){
				$id_venta = $_POST['id_venta'];
				$sql4 = "delete from ventas where id_venta='".$id_venta."';  ";
				//echo $sql4;
				$resp4 = mysql_query($sql4);
				if(!mysql_query($sql4)){
					echo "Ocurrio un error\n$sql4";
				}
				else
				{
					echo "El registro ha sido Eliminado."; }
				}else
					{
					?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
					}
		}
		catch (Exception $e)
		{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
   
		}		
    }

function validarEliminacionVenta($sesion_id_empresa, $numero_cpra_vta){


	$sql="SELECT numero_asiento FROM `cuentas_por_cobrar` where id_empresa='".$sesion_id_empresa."' 
	and numero_factura='".$numero_cpra_vta."';";
	
	
	$numero_asiento=0;
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
		$numero_asiento=$row['numero_asiento'];
	}
	
	return $numero_asiento;
}

if ($accion=="8")
{

	$numero_cpra_vta_borrar=$_POST['numero_venta'];
	$documento=$_POST['documento'];
	$est=$_POST['est'];
	$emi=$_POST['emi'];
	// $numero_cpra_vta_borrar=1;
	// $documento=1;
	// $est=421;
	// $emi=589;
	
	$sqlBorrar="SELECT id_venta, numero_factura_venta,Autorizacion FROM `ventas` where id_empresa='".$sesion_id_empresa."' 
	and numero_factura_venta='".$numero_cpra_vta_borrar."' and tipo_documento='".$documento."' 
	and codigo_pun='".$est."' and codigo_lug='".$emi."';";
			// echo $sqlBorrar;
			
			
			
	$result=mysql_query($sqlBorrar);
	while($row=mysql_fetch_array($result))
	{
		$id_venta=$row['id_venta'];
		$numero_cpra_vta=$row['numero_factura_venta'];
		$autorizacion=$row['Autorizacion'];
	}
	
	
// 	if($autorizacion!='' and $ambie)
// 	{	
// 		echo "No se puede eliminar factura, ya tiene autorizacion .$autorizacion ";
// 		exit;
		
// 	}
	
	if(validarEliminacionVenta($sesion_id_empresa,$numero_cpra_vta)!=''){	
		echo "No se puede eliminar factura, ya tiene registro de cobros ";
		exit;
	}
	else 
	{
		
		$respuestas = array();

		$tipo_mov='F';
            //detalle libro diario
		$sql="SELECT id_libro_diario,numero_comprobante FROM `libro_diario` where id_periodo_contable='".$sesion_id_periodo_contable."' 
		and numero_cpra_vta='".$numero_cpra_vta."'  and tipo_mov='".$tipo_mov."';";
		$resultado11=mysql_query($sql);
		$idDiario=0;
			while($row=mysql_fetch_array($resultado11))  //permite ir de fila en fila de la tabla
			{
				$idDiario=$row['id_libro_diario'];
				$numero_comprobante =$row['numero_comprobante'];

				$sql12="delete FROM `detalle_libro_diario` where id_periodo_contable='". $sesion_id_periodo_contable ."' 
				and id_libro_diario='".$idDiario."';";
				$resultado2=mysql_query($sql12);
				$respuestas['detalle_libroDiario']= ($resultado2)?"Registro detalle libro diario eliminado correctamente. ":"Error al eliminar los datos del detalle de libro diario.";
				$respuestas['mensajeUsuario'] .=($resultado2)?'':$respuestas['detalle_libroDiario'];

				$sql="delete FROM `comprobantes` where id_empresa='".$sesion_id_empresa."' 
				and numero_comprobante='".$numero_comprobante."';";
				$resultado1=mysql_query($sql);
				$respuestas['comprobantes']= ($resultado1)?'Registro de comprobante eliminado correctamente. ': "Error al eliminar los datos de comprobantes. ";
				$respuestas['mensajeUsuario'] .=($resultado1)?'':$respuestas['comprobantes'];

			}	
			

			
              // libro diario
			
			$sql="delete FROM `libro_diario` where id_periodo_contable='".$sesion_id_periodo_contable."' and numero_cpra_vta='".$numero_cpra_vta."' and tipo_comprobante='Diario' and  tipo_mov='F' ; ";
			$resultado3=mysql_query($sql);
			$respuestas['libroDiario']= ($resultado3)?"Registro libro diario eliminado correctamente.":"Error al eliminar los datos del libro diario. ";
			$respuestas['mensajeUsuario'] .=($resultado3)?'':$respuestas['libroDiario'];


        			
        $sql="SELECT detalle_ventas.*,productos.codigo FROM `detalle_ventas` 
        INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio 
        where id_venta='".$id_venta."';";
        $result2=mysql_query($sql);
        $id_producto=0;
        while($row=mysql_fetch_array($result2))  //permite ir de fila en fila de la tabla
        {
        $id_producto=$row['id_kardex'];
        $cantidad=$row['cantidad'];
        $sql2="update productos set stock=stock+'".$cantidad."' where id_producto='".$id_producto."';";
        $resultado4=mysql_query($sql2);
        $respuestas['detalle_ventas'][]= ($resultado4)?"Registro de productos actualizado correctamente. ":"Error al actualizar el stock de productos. ";
        $respuestas['mensajeUsuario'] .=($resultado4)?'':$respuestas['detalle_ventas'];		
        
        
         $sqlbodegas="UPDATE `cantBodegas` set `cantidad`=cantidad+'".$cantidad."' WHERE idProducto='".$row['codigo']."' AND  idBodega='".$row['idBodegaInventario']."'  ";
        $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
        $response['logs'] =($resultBodegas)?"La cantidad en la bodega se han actualizado correctamente":'Error al actualizar la cantidad en la bodega.';
        
        
        }
		

		$descr='Venta';
		$sql="delete FROM `kardes` where id_empresa='".$sesion_id_empresa."' and detalle='".$descr."' and id_factura='".$id_venta."';";
		$resultado5=mysql_query($sql);
		$respuestas['kardes']= ($resultado5)?"Registro de kardex  eliminado correctamente. ":"Error al eliminar el kardes. ";
		$respuestas['mensajeUsuario'] .=($resultado5)?'':$respuestas['kardes'];

		if($respuestas['mensajeUsuario']=='' )
		{
			$sql="delete FROM `detalle_ventas` where id_empresa='".$sesion_id_empresa."' 
			and id_venta='".$id_venta."';";
			// echo "<br>".$sql;
			$resultado6=mysql_query($sql);
			$respuestas['detalle_ventas']= ($resultado6)?"Registro detalle de ventas  eliminado correctamente. ":"Error al eliminar los detalles de la venta.";
			$respuestas['mensajeUsuario'] .=($resultado6)?'':$respuestas['detalle_ventas'];

		}
		
		if($respuestas['mensajeUsuario']=='' )
		{
			$sql="delete FROM `ventas` where id_venta='".$id_venta."';";
			$resultado7=mysql_query($sql);
			$respuestas['ventas']= ($resultado7)?"Registro  de ventas  eliminado correctamente. ":"Error al eliminar el encabezado de la venta.";
			$respuestas['mensajeUsuario'] .=($resultado7)?'':$respuestas['ventas'];  
		}
		
		$sql="delete FROM `cuentas_por_cobrar` where id_empresa='".$sesion_id_empresa."' 
		and numero_factura='".$numero_cpra_vta."'   and tipo_documento='1' ;";
		$resultado8=mysql_query($sql);
		$respuestas['cuentas_por_cobrar']= ($resultado8)?"Registro de cuentas por cobrar eliminado correctamente. ":"Error al eliminar las cuentas por cobrar.";
		$respuestas['mensajeUsuario'] .=($resultado8)?'':$respuestas['cuentas_por_cobrar']; 
		
		$sqlCobrosPagos= "DELETE FROM `cobrospagos` WHERE `id_factura`=$id_venta and documento=0 ;";
		$resultCobrosPagos= mysql_query($sqlCobrosPagos);
		$respuestas['cobrosPagos']= ($resultCobrosPagos)?"Registro de cobrospagos eliminado correctamente. ":"Error al eliminar cobrospagos.";
		$respuestas['mensajeUsuario'] .=($resultCobrosPagos)?'':$respuestas['cobrosPagos'];

// 		echo "</br>resultado1".$resultado1;
// 		echo "</br>resultado2".$resultado2;
// 		echo "</br>resultado3".$resultado3;
// 		echo "</br>resultado4".$resultado4;
// 		echo "</br>resultado5".$resultado5;
// 		echo "</br>resultado6".$resultado6;
// 		echo "</br>resultado7".$resultado7;
// 		echo "</br>resultado8".$resultado8;
		
		if($respuestas['mensajeUsuario']=='')
		{
// 		    	$numero_cpra_vta_borrar=$_POST['numero_venta'];
// 	$documento=$_POST['documento'];
// 	$est=$_POST['est'];
// 	$emi=$_POST['emi'];
		    
		    
		      registroBitacora($sesion_id_usuario,'D',$sesion_id_empresa,'Ventas', $documento.$est.$emi.$numero_cpra_vta_borrar );
			?> <div class='alert alert-success'><p>Factura eliminados correctamente.</p></div> <?php
           
		}
		else
		{
			?> <div class='transparent_ajax_error'>
				<p> <?php echo  $respuestas['mensajeUsuario']; ?>;</p></div> <?php
			}
		}
	}


	if($accion == 9)
	{

			$sql = "SELECT id_venta as id_venta,emision.formato AS formato,ventas.tipo_documento as tipo_documento, emision.impresion from ventas INNER JOIN `emision` emision 
			ON emision.`id` = ventas.`codigo_lug` where id_empresa='".$sesion_id_empresa."' ORDER BY id_venta DESC LIMIT 1 ;";
			$resp = mysql_query($sql);
			$entro=0;
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$datos['id']=$row["id_venta"];
				$datos['formato']=$row["formato"];
				$datos['tipo_documento']=$row["tipo_documento"];
				$datos['impresion']=$row["impresion"];
			}
			echo json_encode($datos);
		
	}
	
	  if($accion == "10")
  {
	// GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
	try 
	{
        // VALIDACIONES PARA QUE LA CEDULA DEL CLIENTE NO SE REPITA POR EMPRESA
		//$numero_factura=$_POST['txtNumeroFacturaFVC'];
        //$cedula=$_POST['txtNumeroFacturaFVC'];
//		echo "estoy en sql, voy a grabar";
		if(isset ($_POST['txtNumeroFacturaFVC']))
		{
		        
		    $cmbEst=$_POST['cmbEst'];
			$cmbEmi=$_POST['cmbEmi'];
			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC'];
			$cedula = $_POST['txtNumeroFacturaFVC'];
			$sql = "SELECT numero_factura_venta from ventas where numero_factura_venta='".$cedula."' and id_empresa='".$sesion_id_empresa."' and codigo_pun ='".$cmbEst."' and codigo_lug='".$cmbEmi."' and tipo_documento ='".$cmbTipoDocumentoFVC."';";
			$resp = mysql_query($sql);
			$entro=0;
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$var=$row["numero_factura_venta"];
			}
			if($var==$cedula)
			{				   
				?><?php echo 2 ?>	<?php echo "".$ex ?>		<?php
			}
			else
			{ $entro=1; }
		}
	}
	catch(Exception $ex) 
	{
		?> <div class="alert alert-warning"><p>Error al verificar la factura 
			<?php echo "".$ex ?></p></div> 
		<?php 
	}
	if ($entro==1)
	{
		try
		{
			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
			$numero_factura=$_POST['txtNumeroFacturaFVC'];
			//	$fecha_venta= date("Y-m-d h:i:s");
		    $fecha_venta= $_POST['textFechaFVC'];
				
			$id_cliente=$_POST['textIdClienteFVC'];
			$estado = "Activo";
			$cmbIdVendedor=$_POST['cmbIdVendedorFVC'];
			
			$cmbEst=$_POST['cmbEst'];
			$cmbEmi=$_POST['cmbEmi'];
			

			$txtNombreFVC=$_POST['txtNombreFVC'];
			$idFormaPago=0;
			$txtCuotasTP=0;
			$total=$_POST['txtTotalFVC'];
			$sub_total=$_POST['txtSubtotalFVC'];
			
			$txtIva=$_POST['txtTotalIvaFVC'];
			
			$txtTotalIvaFVC=$_POST['txtTotalIvaFVC'];
			$txtDescripcion=$_POST['txtDescripcionFVC'];
			$txtContadorFilas=$_POST['txtContadorFilasFVC'];
// 			echo "FILAS===".$txtContadorFilas;

			$txtCambioFP=$_POST['txtCambioFP'];
	
			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
			if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
			{            
				$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
				$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
				<p>Error: '.mysql_error().' </p></div>  ');;
				$iva=0;
				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				{
					$iva=$rowIva1['iva'];
					$txtIdIva=$rowIva1['id_iva'];
					$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				}
			}

			if($id_cliente!="" && $numero_factura !="" && $txtCambioFP==0.0 )
			{       
			    
			    $sql="insert into ventas (   fecha_venta,      estado,        total,         sub_total, numero_factura_venta, fecha_anulacion, descripcion,              id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario) 
				values                ('".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$iva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."');";
// echo $sql;
				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
				$id_venta=mysql_insert_id();
			    
			    
				for($i=1; $i<=$txtContadorFilas; $i++)
				{
		
					if($_POST['txtIdServicio'.$i] >= 1)
					{ //verifica si en el campo esta agregada una cuenta
			
						$cantidad = $_POST['txtCantidadS'.$i];
						$idServicio = $_POST['txtIdServicio'.$i];
						$idKardex = $_POST['txtIdServicio'.$i];
						$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						$valorTotal = $_POST['txtValorTotalS'.$i];
						
						$id_tipoP = $_POST['txtTipoS'.$i];
			
						$idvalorPago=$_POST['txtValorS'.$i];
						
							//GUARDA EN EL DETALLE VENTAS
						$sql2 = "insert into detalle_ventas ( cantidad, estado, v_unitario, v_total, 	id_venta, id_servicio,id_kardex,tipo_venta, id_empresa) 
						values ('".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."'	, '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";
			
						$resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
						<p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');

						$id_detalle_venta=mysql_insert_id();
				    }
				}
			}
		}
		catch (Exception $e) 
		{
			// Error en algun momento.
			   ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
		}		
	}
}


if ($accion == "11") {
    
    $motivo_anul = $_POST['txtmotivoAnulacion'];
    $numero_cpra_vta = $_POST['txtNumeroFacturaFVC'];
    $cmbEst = $_POST['cmbEst'];
    $cmbEmi = $_POST['cmbEmi'];
   
    $sql = "SELECT id_venta, numero_factura_venta,estado,establecimientos.codigo,emision.codigo as ptoEmi
	 FROM `ventas`
	 INNER JOIN emision ON    emision.id=ventas.codigo_lug
	 INNER JOIN establecimientos ON establecimientos.id=ventas.codigo_pun
	  where ventas.id_empresa='" . $sesion_id_empresa . "' 
			and numero_factura_venta='" . $numero_cpra_vta . "' and codigo_pun='".$cmbEst."' and codigo_lug='".$cmbEmi."';";
         
            $result1 = mysql_query($sql);
            $numero_factura_venta = 0;
            $id_venta = '';
            while ($row = mysql_fetch_array($result1)) {  //permite ir de fila en fila de la tabla
                $id_venta = $row['id_venta'];
                $numero_factura_venta = $row['numero_factura_venta'];
                $estado = $row['estado'];
				$estab = $row['codigo'];
				$ptoEmi = $row['ptoEmi'];
				$secuencial = ceros($row['numero_factura_venta']);
				$n= $estab."-".$ptoEmi."-".$secuencial;
            }

    try {
        
     if ($estado=='Activo'){    

            if (isset($id_venta)) {
                
                $sqlCC= "SELECT `id_cuenta_por_cobrar`, `numero_factura`,  `id_venta`, `estado` FROM `cuentas_por_cobrar` WHERE id_venta='".$id_venta."' AND id_empresa='" . $sesion_id_empresa . "'  AND estado='Canceladas' AND tipo_documento=1";
            $resultCC= mysql_query($sqlCC);
			$numFilas= mysql_num_rows($resultCC);

			if($numFilas==0){
			
			$sqlEliminarCC= "DELETE FROM `cuentas_por_cobrar` WHERE `id_venta`='".$id_venta."' AND tipo_documento=1 AND `id_empresa`='" . $sesion_id_empresa . "' ";
			$resultCC = mysql_query($sqlEliminarCC);
			
            $sql4 = "update ventas set   MotivoNota='".$motivo_anul."', estado='Pasivo' WHERE id_venta='".$id_venta."' ;";
            $resp4 = mysql_query($sql4);
            
                if ($resp4) { echo "1";  

					 $sqlInfo = "SELECT `id_libro_diario`, `total_debe`, `total_haber`, `descripcion`, `tipo_comprobante`, `id_cliente`, `numero_pedido`, `tipo_mov`, `numero_cpra_vta`  FROM `libro_diario` WHERE numero_cpra_vta= $numero_factura_venta AND id_periodo_contable=$sesion_id_periodo_contable AND tipo_comprobante ='DIARIO' AND tipo_mov='F' ";
					$resultInfo =mysql_query($sqlInfo);
					while($rowInfo = mysql_fetch_array($resultInfo)){
						
						$fecha_venta= date('Y-m-d');
						$total_debe= $rowInfo['total_debe'];
						$total_haber= $rowInfo['total_haber'];
						$tipo_mov = $rowInfo['tipo_mov'];
						$tipoComprobante = $rowInfo['tipo_comprobante'];
						$idLibroDiarioPrincipal = $rowInfo['id_libro_diario'];

						$descripcion= 'Rerverso del asiento.\n Asiento de reverso por anulacion de venta: '.$n;

						$sqlMNA="SELECT
    						max(numero_asiento) AS max_numero_asiento
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

						$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipoComprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
    					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
    					$id_comprobante=mysql_insert_id();

						 $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
    					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
    					values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_haber."','".$total_debe."','".$descripcion."','".$numero_comprobante."','".$tipoComprobante."',
    					'".$id_comprobante."','".$tipo_mov."','".$numero_factura_venta."' )";
    					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    					$id_libro_diario=mysql_insert_id();

						$resultDetalles = '';
						$sqlDetalles = "SELECT `id_plan_cuenta`, `debe`, `haber` FROM `detalle_libro_diario` WHERE `id_libro_diario`=$idLibroDiarioPrincipal  ";
						$resultDetalles = mysql_query($sqlDetalles);
						while($rowDetalles = mysql_fetch_array($resultDetalles)){
							$total_debeD= $rowDetalles['debe'];
							$total_haberD= $rowDetalles['haber'];
							$id_plan_cuenta= $rowDetalles['id_plan_cuenta'];
							 $sqlDLD1 = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
    							('".$id_libro_diario."',		'".$id_plan_cuenta."','".$total_haberD."','".$total_debeD."',	'".$sesion_id_periodo_contable."');";
						$resultDLD1=  mysql_query($sqlDLD1);

						}


					}
					$fecha_actual=date('Y-m-d h:i:s');
				
					$sqlDetalleVentas="SELECT `id_detalle_venta`, `idBodega`, `idBodegaInventario`, `cantidad`, `estado`, `v_unitario`, `descuento`, `v_total`, `id_venta`, `id_servicio`, `detalle`, `id_kardex`, `tipo_venta`, `id_empresa`, `id_proyecto`, `tarifa_iva`, `total_iva` FROM `detalle_ventas` WHERE id_venta='".$id_venta."'";
					$resultDetalleVentas = mysql_query($sqlDetalleVentas);
					while($roeD = mysql_fetch_array($resultDetalleVentas) ){
					    $id_prod= $roeD['id_servicio'];
					    $cant =  $roeD['cantidad'];
					    $val =  $roeD['v_unitario'];
					    $sqlDevolucion = "INSERT INTO `devolucion`( `id_producto`, `tipo_devolucion`, `cantidad`, `fecha`, `detalle`, `id_empresa`, `valor_unitario`, `id_factura`) VALUES ('".$id_prod."','2','".$cant."','".$fecha_actual."','".$motivo_anul."','".$sesion_id_empresa."','".$val."','".$id_venta."')";
						$resultDevolucion = mysql_query($sqlDevolucion);
					}
                	$sqlKardex = "INSERT INTO `kardes`( `fecha`, `detalle`, `id_factura`, `id_empresa`) VALUES ('".$fecha_actual."','Devolucion Venta','".$id_venta."','".$sesion_id_empresa."')  ";
						$resultKardex = mysql_query($sqlKardex);
						
                } else { 
                    echo  "2"; 
                    echo mysql_error(); 
                } 
			    
			}else{
				echo '4';
			}
            
          
                
            }  else { 
                echo "No hay datos";  
            } 
         }else{
             echo "3";
         }
        
        }catch  (Exception $e) {  ?> <div class="transparent_ajax_error"><p>Error: <?php echo "" . $e ?></p></div> <?php }
                
    }


	
	if($accion == "15")
	{
		
	  $modoFacturacion=$_POST['modo']; 
	  
	  // GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php CON FORMA DE PAGO EFECTIVO
	  try 
	  {
		  
			  $sqlEmpresa="SELECT limiteFacturas from  `empresa`  WHERE id_empresa='$sesion_id_empresa' ";
			  $resultEmpresa=mysql_query($sqlEmpresa) or die();
			  while($rowLimite=mysql_fetch_array($resultEmpresa))//permite ir de fila en fila de la tabla
			  {
				  $limite=$rowLimite['limiteFacturas'];
			  }
			  
				  
			  if($limite>=0){    
				  $limite3 = '1';	
			  }else {	  
				  $limite3 = '2';	
			  }
		  
		  
			 
		  if(isset ($_POST['txtNumeroFacturaFVC']))
		  {
			  $cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
			  $cmbEst=$_POST['cmbEst'];
			  $cmbEmi=$_POST['cmbEmi'];
			  $cedula = $_POST['txtNumeroFacturaFVC'];
			  $sql = "SELECT numero_factura_venta from ventas where numero_factura_venta='".$cedula."' and id_empresa='".$sesion_id_empresa."'
			  and codigo_pun ='".$cmbEst."' and codigo_lug='".$cmbEmi."' and tipo_documento='".$cmbTipoDocumentoFVC."';";
			  $resp = mysql_query($sql);
			 
		  
			  $entro=0;
			  while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			  {
				  $var=$row["numero_factura_venta"];
			  }
			  if($var==$cedula)
			  {				   
				  ?><?php echo 2 ?>	<?php echo "".$ex ?>		<?php
			  }
			  else
			  { 
				  $entro=1; 
			  }
		  }
	  }
	  catch(Exception $ex) 
	  {
		  ?> <div class="alert alert-warning"><p>Error al verificar la factura 
			  <?php echo "".$ex ?></p></div> 
		  <?php 
	  }
	  
	  
	  if ($entro==1 && $limite3==1)
	  {
		  try
		  {
			  
			  
			  $cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
			  
			  $numero_factura=$_POST['txtNumeroFacturaFVC'];
			  $fecha_venta= $_POST['textFechaFVC'];		
			 //  $fecha_venta= date('Y-m-d h:i:s');	
			  $id_cliente=$_POST['textIdClienteFVC'];
			  $estado = "Activo";
			  
  // 			$cmbIdVendedor=$_POST['cmbIdVendedorFVC'];
			  $cmbIdVendedor=$_POST['chofer_id'];
			  
			  
			  $cmbEst=$_POST['cmbEst'];
			  $cmbEmi=$_POST['cmbEmi'];
			  
  
			  $txtNombreFVC=$_POST['txtNombreFVC'];
			  $idFormaPago=0;
			  $txtCuotasTP=0;
			  $total=$_POST['txtTotalFVC'];
			  $sub_total=$_POST['txtSubtotalFVC'];
			   $sub_total0		= isset($_POST['txtSubtotal0FVC'])? $_POST['txtSubtotal0FVC']: 0;
    			$sub_total12	= isset($_POST['txtSubtotal12FVC'])? $_POST['txtSubtotal12FVC'] :0;
			  $descuento=$_POST['txtDescuentoFVCNum'];
			  $propina=$_POST['txtPropinaFVC'];
		  
			 
			  $facAn=$_POST['facAn'];
			  
			  $motivo =$_POST['MotivoNota'] ;
			  $txtTotalIvaFVC=$_POST['txtTotalIvaFVC'];
			  $txtDescripcion=$_POST['txtDescripcionFVC'];
			  $txtContadorFilas=$_POST['txtContadorFilasFVC'];

			
			  $txtIva=$_POST['txtTotalIvaFVC'];
  
			  $txtCambioFP=0;
			//   $txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
			  
			  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
			  {            
				  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."' and iva >0";
				  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
				  <p>Error: '.mysql_error().' </p></div>  ');;
				  $iva=0;
				  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				  {
					  $iva=$rowIva1['iva'];
					  $txtIdIva=$rowIva1['id_iva'];
					  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				  }
				  
				  // 	echo "txtIdIva ==>".$txtIdIva;
			  }
			  
			  
			  
			  if ($cmbTipoDocumentoFVC==1)
			  
			  {
				  if($id_cliente!="" && $numero_factura !=""  )
				  {       
					  
					$vendedor_id = trim($_POST['vendedor_id']);
    			    $vendedor_id = ($vendedor_id=='')?0:$vendedor_id;
    			    
					  $sql="insert into ventas (fecha_venta,       estado,        total, sub_total,sub0,sub12,descuento,propina,numero_factura_venta, fecha_anulacion, descripcion, id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario,vendedor_id_tabla  ,total_iva) 
					  values (                '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$vendedor_id."','".$txtTotalIvaFVC."');";
  
					  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
					  $id_venta=mysql_insert_id();
  
					  if($limite==0){
						  $limite=0;
						  
					  }else if($limite==1){
						  $limite=$limite-2;
						  
					  }else{
						  
						  $limite=$limite-1;
					  }
					  	if($_POST['cmbTipoDocumentoFVC']=='1' or $_POST['cmbTipoDocumentoFVC']=='41'){
					  	      $sqlNumFac ="update emision set numFac='".$numero_factura."' where id ='".$cmbEmi."' ";
					  $result=mysql_query($sqlNumFac) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().'</p></div>  ');
					  	}
					  
					  
					  
					  $sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
					  $resultEmpresa2=mysql_query($sqlEmpresa2) or die();
					  
					  
	   if($_POST['idtipocliente'] =='08'){
                     $iva_exportador=0;
                     $sql_iva_exportador= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='". $sesion_id_empresa . "' AND iva=0 ";
                    $result_iva_exportador = mysql_query( $sql_iva_exportador );
                    while($row_iva_exportador = mysql_fetch_array($result_iva_exportador) ){
                        $iva_exportador= $row_iva_exportador['id_iva'];
                    }
                 }
					  
					  
				   
					  for($i=1; $i<=$txtContadorFilas; $i++)	
					  {
					  if($_POST['txtIdServicio'.$i] >= 1)
					  { //verifica si en el campo esta agregada una cuenta
			  
							  $cantidad = $_POST['txtCantidadS'.$i];
							  $idServicio = $_POST['txtIdServicio'.$i];
							  $idKardex = $_POST['txtIdServicio'.$i];
				  // 			echo "==>".$valorUnitario."</br>";
				  // 			$valorUnitario = $_POST['txtValorUnitarioS'.$i];
							  $valorUnitario = $_POST['txtValorUnitarioShidden'.$i];
							  
							  $valorTotal = $_POST['txtValorTotalS'.$i];
							  $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
							  $txtTipo11 = $_POST['txtTipo'.$i];
							  
							  $id_tipoP = $_POST['txtTipoS'.$i];
							  $cuenta = $_POST['cuenta'.$i];
							  $idBod = $_POST['idbod'.$i];
							  $idvalorPago=$_POST['txtValorS'.$i];
  //							$txtdesc=$_POST['txtdesc'.$i];
  //  echo "==>".$_POST['txtdescant'.$i]."</br>";
							  $txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];
							  $txtdetalle2=$_POST['txtdetalle2'.$i];
							   $bodegaCantidad=$_POST['bodegaCantidad'.$i];
				  // 			echo "BODEGA==>".$bodegaCantidad."</br>";
							  if($bodegaCantidad!=''){
								  $bodegaCantidad=$_POST['bodegaCantidad'.$i];
							  }else{
								  $bodegaCantidad='0';
							  }
							  $txtCodigoServicio=$_POST['txtCodigoServicio'.$i];
							  
						  
						  
				  // 			if ($id_tipoP == "P"  or $id_tipoP == "Inventario" or $id_tipoP == "I" or $id_tipoP == "1" ){
				  // 				$sql3="update productos set stock=stock-'".$cantidad."' where id_empresa='".$sesion_id_empresa."' and id_producto='".$idServicio."';";
				  // 				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender '.mysql_error().' </p></div>  ');
				  // 			}
							  
							  $txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
							  if ($id_tipoP == "2" && $txtDescripcionS!='') {
								  $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
								  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
								  // echo "UPDATE PRODUCTO ==>".$sql3;
							  }
							  
							   $sqlCentem = "SELECT establecimientos.centro_costo FROM `establecimientos` WHERE  id= '" . $cmbEst . "'";
    							    
    							    $resultCentm = mysql_query($sqlCentem);
    							    $numFilasCetm = mysql_num_rows($resultCentm);
    							    if($numFilasCetm>0){
    							        while($rowCetm = mysql_fetch_array($resultCentm) ){
    							            $id_proyecto= $rowCetm['centro_costo'];
    							        }
    							        
    							    }else{
    							        $id_proyecto=0;
    							    }
    							    
    							    $id_iva = $_POST['IVA120'.$i];
								$sql_iva= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='" . $sesion_id_empresa . "' AND id_iva='" . $id_iva . "' ";
								$result_iva = mysql_query( $sql_iva );
								while($row_iva = mysql_fetch_array($result_iva) ){
									$tarifa_iva= $row_iva['iva'];
								}
								

								$total_iva= floatval($valorTotal) * (floatval( $tarifa_iva )/100);   
					if($_POST['idtipocliente'] =='08'){
    			                    $id_iva=(trim($iva_exportador)=='')?0:$iva_exportador;
                                     $total_iva=0;
                                 }
                    
							  //GUARDA EN EL DETALLE VENTAS
				   $sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa,id_proyecto , `tarifa_iva`, `total_iva`) 
							  values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."','".$id_proyecto."','".$id_iva."', '".$total_iva."'  );";
							  $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
							  <p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
							  $id_detalle_venta=mysql_insert_id();
		   
					  if ($id_tipoP == "1") {
					      
        				    if($bodegaCantidad!=''){
        				    $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$bodegaCantidad."' and idProducto='".$txtCodigoServicio."' ";
        				   
									  $resultado = mysql_query($stockBodegas);
									  while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
										  {
											  $id=$rowBodegas1['id'];
											  $cantidadBodega=$rowBodegas1['cantidad'];
										  }
									  $cantidadBodega = $cantidadBodega-$cantidad;
									//  $sqlbodegas="UPDATE `cantBodegas` SET `cantidad`='".$cantidadBodega."' WHERE idProducto='".$txtCodigoServicio."' and id='".$id."'";
								   $sqlbodegas="UPDATE `cantBodegas` SET `cantidad`='".$cantidadBodega."' WHERE id='".$id."'";
									  $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
	  
								  }
						  }
					  }
				  }
		  
				  

				  // Crear el asiento		
  
				//   if ($sesion_tipo_empresa=="6")
				//   { // aqui2
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
  
  
					  try
					  {
						  $sqlm="Select max(id_libro_diario) From libro_diario";
						  $resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error : '.mysql_error().' </p></div>  ');
						  $id_libro_diario=0;
						  while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
						  {
							  $id_libro_diario=$rowm['max(id_libro_diario)'];
						  }
						  $id_libro_diario++;
  
					  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
				  
					  //Fin permite sacar el id maximo de libro_diario
		  
		  
		  $tipo_comprobante = "Diario"; 
				  
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
					  $descripcion = "Factura de venta #".$numero_factura." realizada a ".$txtNombreFVC;
					  
					  $debe = $total;
					  $debe2 = $descuento;
					  $total_debe = $debe + $debe2;
					  
					  $haber1 = $sub_total;
					  $haber2 = $_POST['txtTotalIvaFVC'];
					  
					  $total_haber = $haber1 + $haber2 + $propina;
					  
					  $tipo_mov="F";
	  
				  //GUARDA EN  COMPROBANTES
					  $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					  $id_comprobante=mysql_insert_id();
				  //GUARDA EN EL LIBRO DIARIO
					  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
					  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
					  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
					  $id_libro_diario=mysql_insert_id();
				  
					  $idPlanCuentas[1] = '';
					  $debeVector[1] = 0;  
					  $haberVector[1] = 0;

					  $lin_diario=0;

					  $valor[$lin_diario]=0;
					  $ident=0;
					  
					
						  
							  
							  
					$lin_diario=$lin_diario+1;
					
					$debeVector[$lin_diario]=$total;
					$haberVector[$lin_diario]=0; 
					

					 $sqlFormaPago = "SELECT
					formas_pago.`id_forma_pago` AS formas_pago_id,
					formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta
				FROM
					`formas_pago` formas_pago
				INNER JOIN `plan_cuentas` plan_cuentas ON
					formas_pago.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta` AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."'
				WHERE
				formas_pago.`id_tipo_movimiento`=1 LIMIT 1 ";
				$resultFormaPago = mysql_query($sqlFormaPago);
				$formaPago='';
				while($rowFP = mysql_fetch_array($resultFormaPago)){
					$formaPago = $rowFP['formas_pago_id'];

					$formaPagoId[$lin_diario]= $rowFP['formas_pago_id'];
					$idPlanCuentas[$lin_diario]=$rowFP['formas_pago_id_plan_cuenta'];
				}

								
					 $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) VALUES     ('".$formaPago."','0','".$id_venta."','".$sesion_id_empresa."','".$total."','1', NULL );";
									// echo $sqlforma;
					$respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
					if($respForma){
						$sql3="update ventas set id_forma_pago='01' where id_venta='".$id_venta."' ";
						$resp3 = mysql_query($sql3) or die(mysql_error());  
									}

					  $tot_ventas=0;
					  $tot_servicios=0;
					  $tot_costo=0;
					  $listaServicios=array();
					  for($i=1; $i<=$txtContadorFilas; $i++){
						  if($_POST['txtIdServicio'.$i] >= 1)
						  {
							  $idProducto=$_POST['txtIdServicio'.$i];
							  $id_tipoP = $_POST['txtTipoS'.$i];
							  $cuenta = $_POST['cuenta'.$i];
			  
							  if ($id_tipoP =="1"){
								  $tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
								  $costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
								  $tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
							  }
							    if ($id_tipoP == "2" ){
							        	$sqlPlanCuenta= "SELECT  
								productos.`id_producto` AS productos_id_producto, 
							 productos.`producto` AS productos_nombre,
							 productos.`grupo` AS productos_grupo, 
							 centro_costo.`id_centro_costo` AS centro_id, 
							 centro_costo.`descripcion` AS centro_descripcion, 
							 centro_costo.`id_cuenta` AS productos_id_cuenta,
							 plan_cuentas.codigo,
      						 plan_cuentas.nombre
							  
							 FROM  `productos` 
							 
							 INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
							 INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = centro_costo.id_cuenta
						 
							 
							 WHERE productos.id_producto=$idProducto ";
							 $resultPlanCuentas= mysql_query($sqlPlanCuenta);
							 $planCuentaServicio = 0;
							 while($rowPC = mysql_fetch_array($resultPlanCuentas)){
								$planCuentaServicio = $rowPC['productos_id_cuenta'];
							 }
							 if(array_key_exists($planCuentaServicio,$listaServicios)){
                                                $listaServicios[$planCuentaServicio]=$listaServicios[$planCuentaServicio]+ $_POST['txtValorTotalS'.$i]; 
                                              }else{
                                                   $listaServicios[$planCuentaServicio]=$_POST['txtValorTotalS'.$i]; 
                                              }
							 
								$tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
							}
						  }
					  }
 
				  try 
				  {
					  $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
					  WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
					  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
					  $resultMercaderia=mysql_query($sqlMercaderia);
					  $idcodigo_v=0;
					  while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
					  {
						  $idcodigo_v=$row['codigo_plan_cuentas'];
					  }
					  $idcodigo_v;
				  }
				  catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
				  
				  try 
				  {
					  $sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
					  WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
					  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
					  $resultServicio=mysql_query($sqlServicio);
					  $idcodigo_s=0;
					  while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
					  {
						  $idcodigo_s=$row['codigo_plan_cuentas'];
					  }
					  $idcodigo_s;
  
				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				
  
						  
					  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
							from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
							  ( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
	  
					  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
					  $plan_id_cuenta_vta=0;
					  $plan_id_cuenta_servicio=0;
					  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
					  {
						  if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
							  {
								  $plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
							  }
						  if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
							  {
								  $plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
							  }
  
							  
					  }
					  
					  
					
					  if ($tot_ventas!=0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$tot_ventas;
						  
  
					  }
					  
					  if ($tot_servicios!=0)
					  {
						foreach ($listaServicios as $key => $value) {
                                $lin_diario=$lin_diario+1;
                                $idPlanCuentas[$lin_diario]= $key;
                                $debeVector[$lin_diario]=0;
                                $haberVector[$lin_diario]=$value;
                            }
						  
  
					  }
					  
					  
					  try {
							  $sqlDescuento="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago WHERE `id_tipo_movimiento` =14 
							  and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
							  $resultDescuento=mysql_query($sqlDescuento);
							  $idcodigo_d=0;
							  while($row=mysql_fetch_array($resultDescuento))//permite ir de fila en fila de la tabla
							  {
								  $idcodigo_d=$row['codigo_plan_cuentas'];
							  }
							  $idcodigo_d;
		  
					  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
					  
					  
					  
					  if ($descuento!=0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $idcodigo_d;
						  $debeVector[$lin_diario]=$descuento;
						  $haberVector[$lin_diario]=0;
					  }
					  
					  
					  try {
							  $sqlPropina="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago 
							  WHERE `id_tipo_movimiento` =15 and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
							  $resultPropina=mysql_query($sqlPropina);
							  $idcodigo_p=0;
							  while($row=mysql_fetch_array($resultPropina))//permite ir de fila en fila de la tabla
							  {
								  $idcodigo_p=$row['codigo_plan_cuentas'];
							  }
							  $idcodigo_p;
		  
					  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
					  
					  
					  if ($propina!=0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $idcodigo_p;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$propina;
					  }
					  
					  if ($txtTotalIvaFVC!=0)
					  {
  
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$txtTotalIvaFVC;
  
					  }
					  
					  
					  
					  
					  for($i=1; $i<=$lin_diario; $i++)
					  {
						  
  
						  if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
						  {
						  //permite sacar el id maximo de detalle_libro_diario
  
						  //GUARDA EN EL DETALLE LIBRO DIARIO
							  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
							  ('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
					  
							  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							  $id_detalle_libro_diario=mysql_insert_id();		
				  // 			echo "DETALLE==".$sqlDLD."</br>";
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
  
									  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
									  $result6=mysql_query($sql6);
									  $id_mayorizacion=mysql_insert_id();
								  }
								  catch(Exception $ex) 
								  { ?> <div class="transparent_ajax_error">
									  <p>Error en la insercion de la tabla mayorizacion: 
									  <?php echo "".$ex ?></p></div> <?php }
								  // FIN DE MAYORIZACION
							  }
						  }
					  }
					  
					  
					  
  //ASIENTO DE COSTO
				//   	echo "	va a crear CostoS=".$tot_costo."|";
					  if ($tot_costo>0)
					  {
					  try
						  {
							  $numero_asiento++;
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
						  $descripcion="Asiento de costo de venta de la Factura No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
						  //$debe = $total_costo;
						  //$haber1 = $sub_total;
					  //  $haber2 = $_POST['txtTotalIvaFVC'];
						  $total_debe  = $tot_costo;
						  $total_haber = $tot_costo;
					  
		  /* 			echo "debe=";
					  echo $total_debe;
					  echo "haber=";
					  echo $total_haber;
		   */			
						  $tipo_mov="F";
	  
				  //GUARDA EN  COMPROBANTES
						  $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
						  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					  
				  //GUARDA EN EL LIBRO DIARIO
						  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
						  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
						  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
						  '".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
						  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
  
						  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
						  $id_libro_diario=mysql_insert_id();
			  try {
					  $sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
					  WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
					  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
					  $resultCosto=mysql_query($sqlCosto);
					  $idcodigo_v=0;
					  while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
					  {
						  $idcod_costo=$row['codigo_plan_cuentas'];
					  }
					  $idcod_costo;
  
			  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
		  //	echo 	$idcod_costo;	
					  
	  //		$idcod_costo     ="51001001";
	  
		  try {
					  $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas 
					  WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable
					  and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
					  $resultInventario=mysql_query($sqlInventario);
					  $idcodigo_v=0;
					  while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
					  {
						  $idcod_inventario=$row['codigo_plan_cuentas'];
					  }
					  $idcod_inventario;
  
			  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
		  
		  //	echo "</br>".	$idcod_inventario;	
			 
  //			$idcod_inventario="115001001";
  
											  
						  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
							  ( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
					  //echo $sql;
						  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
						  $plan_id_cuenta_costo=0;
						  $plan_id_cuenta_inventario=0;
						  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
						  {
							  if ($rowS['plan_cuentas_codigo']==$idcod_costo)
							  {
								  $plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
					  
							  }
							  if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
							  {
								  $plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
							  }
						  }
					  
  
					  
						  $lin_diario=0;
						  if ($tot_costo>0)
						  {
							  $lin_diario=$lin_diario+1;
							  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
							  $debeVector[$lin_diario]=$tot_costo;
							  $haberVector[$lin_diario]=0;	
  //echo $idPlanCuentas[$lin_diario];
							  $lin_diario=$lin_diario+1;
							  $idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
							  $debeVector[$lin_diario]=0;
							  $haberVector[$lin_diario]=$tot_costo;
  //echo $idPlanCuentas[$lin_diario];										
						  }
			  
				   //echo "numero de cuentas";
				   //echo $lin_diario;
						  for($i=1; $i<=$lin_diario; $i++)
						  {
							  //echo "entro a costo";
						  
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
								  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 
								  id_plan_cuenta,debe, haber, id_periodo_contable) values 
								  ('".$id_libro_diario."',
								  '".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
								  '".$sesion_id_periodo_contable."');";
			  
			  
								  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
								  $id_detalle_libro_diario=mysql_insert_id();
								  
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
  
										  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
										  $result6=mysql_query($sql6);
										  $id_mayorizacion=mysql_insert_id();
									  }
									  catch(Exception $ex) 
									  { ?> <div class="transparent_ajax_error">
										  <p>Error en la insercion de la tabla mayorizacion: 
										  <?php echo "".$ex ?></p></div> <?php }
								  // FIN DE MAYORIZACION
								  }  
							  }
						  }						
					  }
				//   } //aqui2 
  
			  
				// GUARDAR EN KARDEX
  
				  $sqlki="Select max(id_kardes) From kardes";
				  $resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
				  $id_kardes='0';
				  while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
				  {
					  $id_kardes=$rowki['max(id_kardes)'];
				  }
				  $id_kardes++;
				  $sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
				  ('".$fecha_venta."','Venta','".$id_venta."', '".$sesion_id_empresa."')";
				  $resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				  $id_kardes=mysql_insert_id();
				  if($result && $resp2)
				  {
					  echo "kardex";
				  }
				  else{
						  echo "kardex2";
					  }
					  

				  if($modoFacturacion=='200'){
					  
					  $emision_tipoEmision='F';
					  
				  }else{
					  
					  $emision_tipoEmision = $_SESSION['emision_tipoEmision'];
				  }
				  
						  if ($emision_tipoEmision === 'E'){
							  genXml($id_venta);
							  
											  $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
											  // echo $sqli;
											  $result=mysql_query($sqli);
											  
											  while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
											  {
												  $claveAcceso=$row['ClaveAcceso'];
											  }
											  if ($claveAcceso != ''){
												  echo "SI";
												  }else{
													  echo "NO";
												  }
							  
							  }
				  
				  
				  
				  }
				  else
				  {
				  if($txtCambioFP>0.0 )
				  {
				  ?> <div class='transparent_ajax_error'><p>Existe un saldo pendiente de cancelar <?php echo " ".mysql_error(); ?>;</p></div> <?php
					  
				  }
				  else
				  {
				  ?> <div class='transparent_ajax_error'><p>Error: Valor a cobrar incorrecto <?php echo " ".mysql_error(); ?>;</p></div> <?php
					  
				  }
			  }
  
		  }
		  
		  
		  
		  
		  
		  
			  if ($cmbTipoDocumentoFVC==4)
			  {
  
  
			  $txtIva=$_POST['txtTotalIvaFVC'];
						  
			  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
			  {            
				  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
				  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
				  <p>Error: '.mysql_error().' </p></div>  ');;
				  $iva=0;
				  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				  {
					  $iva=$rowIva1['iva'];
					  $txtIdIva=$rowIva1['id_iva'];
					  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				  }
				  
			  }
				  
				  // txtTotalIvaFVC		
						  
				  // $hoy = date("Y-m-d H:i:s"); 	
					  
				  $sql="insert into ventas (fecha_venta,      estado,       total,       sub_total,         sub0,               sub12,           descuento,        propina,      numero_factura_venta, fecha_anulacion, descripcion, id_iva, id_vendedor, id_cliente, id_empresa,	tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, RetIva,MotivoNota) 
				  values  (             '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."', '".$propina."',   '".$numero_factura."',    '0000-00-00 00:00:00'  ,'".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$facAn."','".$motivo."');";
	  
				  
				  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en Nota de Credito: '.mysql_error().' </p></div>  ');
				  $id_venta=mysql_insert_id();
					  
				  for($i=1; $i<=$txtContadorFilas; $i++)
				  {
					  if($_POST['txtIdServicio'.$i] >= 1)
					  { 
						  
						  $cantidad = $_POST['txtCantidadS'.$i];
						  $idServicio = $_POST['txtIdServicio'.$i];
						  $idKardex = $_POST['txtIdServicio'.$i];
						  $valorUnitario = $_POST['txtValorUnitarioS'.$i];
						  $valorTotal = $_POST['txtValorTotalS'.$i];
						  $txtdetalle2=$_POST['txtdetalle2'.$i];
						  $id_tipoP = $_POST['txtTipoS'.$i];
						  //echo "tipo".$id_tipoP;
						  $idBod = $_POST['idbod'.$i];
						  $idvalorPago=$_POST['txtValorS'.$i];
						  $bodegaCantidad=$_POST['bodegaCantidad'.$i];
							  if($bodegaCantidad!=''){
								  $bodegaCantidad=$_POST['bodegaCantidad'.$i];
							  }else{
								  $bodegaCantidad='0';
							  }
							  
									$txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];
  
						  
													  //GUARDA EN EL DETALLE VENTAS
  $sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa) 
  values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";
  
  $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
  $id_detalle_venta=mysql_insert_id();
						  
						  
  
					  }
				  }		
			  
				  $tot_costo=0;
				  try	
				  {
					$sqlMNA="SELECT max(numero_asiento) AS max_numero_asiento,
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
				  // Creacion del diario de la nota de credito
				  try
				  {
					  $sqlm="Select max(id_libro_diario) From libro_diario";
						  $resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error : '.mysql_error().' </p></div>  ');
						  $id_libro_diario=0;
					  while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
					  {
						  $id_libro_diario=$rowm['max(id_libro_diario)'];
					  }
					  $id_libro_diario++;
				  }
				  catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
				  
				  $tipo_comprobante = "Diario"; 
				  try
				  {
					  $tipoComprobante = $tipo_comprobante;
					  $consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
							   FROM `comprobantes` comprobantes
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
				  $debe = $total;
				  $haber1 = $sub_total;
				  $haber2 = $_POST['txtTotalIvaFVC'];
				  $total_debe = $debe;
				  $total_haber = $haber1 + $haber2;
				  
				  $tipo_mov="D";
	  
				  //GUARDA EN  COMPROBANTES
				  $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values 
				  ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
				  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
				  $id_comprobante=mysql_insert_id();
				  
				  //GUARDA EN EL LIBRO DIARIO
				  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
					  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."',
					  '".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
				  
				  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
				  $id_libro_diario=mysql_insert_id();
				  
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
										  
				  $tot_ventas=0;
				  $tot_servicios=0;
				  $tot_costo=0;
				  $txtContadorFilas=8;
					  
				  for($i=1; $i<=$txtContadorFilas; $i++)
				  {
					  if($_POST['txtIdServicio'.$i] >= 1)		
					  {										 //verifica si en el campo esta agregada una cuenta
						  $idProducto=$_POST['txtIdServicio'.$i];
						  $id_tipoP = $_POST['txtTipoS'.$i];
						  $sqlS = "SELECT productos.`id_producto` AS productos_id_servicio,
								  productos.`producto` AS productos_nombre, productos.`iva` AS productos_iva,
								  productos.`id_empresa` AS productos_id_empresa,	productos.`costo` AS productos_costo,                  
								  productos.`id_cuenta` AS productos_id_cuenta	
							  FROM
								  `productos` productos  Where productos.`id_empresa`='".$sesion_id_empresa."' and
									  productos.`id_producto` ='".$idProducto."'";
						  $resultS=mysql_query($sqlS) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;	
						  $productos_costo=0;								
						  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
						  {
							  $productos_id_cuenta=$rowS['productos_id_cuenta'];
							  //	$productos_costo=$rowS['productos_costo'];
						  }
					  //******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
							  
						  if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" or $id_tipoP="1" )
						  {
								  $tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
	  //						//	$tot_costo=$tot_costo+($productos_costo * $_POST['txtCantidadS'.$i]);
							  //	$costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa,1);
								  $costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
								  $tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
						  }
						  else
						  {
							  $tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
						  }
					  }
				  }
				  
				  try 
				  {
					  $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
					  WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
					  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
					  $resultMercaderia=mysql_query($sqlMercaderia);
					  $idcodigo_v=0;
					  while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
					  {
						  $idcodigo_v=$row['codigo_plan_cuentas'];
					  }
					  $idcodigo_v;
				  }
				  catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
				  try 
				  {
					  $sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
					  WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
					  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
					  $resultServicio=mysql_query($sqlServicio);
					  $idcodigo_s=0;
					  while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
					  {
						  $idcodigo_s=$row['codigo_plan_cuentas'];
					  }
					  $idcodigo_s;
  
				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				
  
						  
				  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
						from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
						  ( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
  
				  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
				  $plan_id_cuenta_vta=0;
				  $plan_id_cuenta_servicio=0;
				  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
				  {
					  if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
						  {
							  $plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
						  }
					  if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
						  {
							  $plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
						  }
				  }
  
				  $lin_diario=0;
				  // echo             "<==> total <==>".$total."<==>";
				  if ($tot_ventas!=0)
				  {
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
					  $debeVector[$lin_diario]=$tot_ventas;
					  $haberVector[$lin_diario]=0;
				  }
				  if ($tot_servicios!=0)
				  {
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_servicio;
					  $debeVector[$lin_diario]=$tot_servicios;
					  $haberVector[$lin_diario]=0;
				  }
				  //echo $impuestos_id_plan_cuenta;
				  $lin_diario=$lin_diario+1;
				  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
				  $debeVector[$lin_diario]=$txtTotalIvaFVC;
				  $haberVector[$lin_diario]=0;
				  
				  $sql = "SELECT id_plan_cuenta FROM formas_pago  
					  WHERE id_empresa = '" .$sesion_id_empresa. "' and id_tipo_movimiento=1"; 
				  //echo $sql;
				  $resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  
				  while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
				  {
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]=$row['id_plan_cuenta'];
					  $debeVector[$lin_diario]=0;
					  $haberVector[$lin_diario]=$total;	
				  }
				  
  // echo             "<==> valores <==>".$haberVector[$lin_diario]."<==>";
		  /* for($i=1; $i<=$lin_diario; $i++)
				  {
					  echo $idPlanCuentas[$i]."-";
					  echo $debeVector[$i];
					  echo $haberVector[$i];
					  echo "<br/>";
  
				  } */
			  
			  
				  for($i=1; $i<=$lin_diario; $i++)
				  {
					  if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
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
					  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
							  ('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
							  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							  $id_detalle_libro_diario=mysql_insert_id();		
							  
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
  
								  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
								  $result6=mysql_query($sql6);
								  $id_mayorizacion=mysql_insert_id();
							  }
							  catch(Exception $ex) 
								  { ?> <div class="transparent_ajax_error">
									  <p>Error en la insercion de la tabla mayorizacion: 
									  <?php echo "".$ex ?></p></div> <?php }
								  // FIN DE MAYORIZACION
						  }
					  }
				  }
			  
								  
			  
  //ASIENTO DE COSTO
				  //	echo "	va a crear CostoS=".$tot_costo;
				  if ($tot_costo>0)
				  {
					  try
					  {
							  $numero_asiento++;
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
							  $consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
							  FROM `comprobantes` comprobantes
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
					  }	
					  catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
								
				  //FIN SACA EL ID MAX DE COMPROBANTES
		  
					  $fecha= date("Y-m-d h:i:s");
						  //$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
					  $descripcion="Asiento de costo de nota de credito No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
					  $descripcion="Asiento de costo de nota de credito No.".$numero_documento." realizado por ".$sesion_empresa_nombre;
										  
					  $total_debe  = $tot_costo;
					  $total_haber = $tot_costo;
					  
		  /* 			echo "debe=";
					  echo $total_debe;
					  echo "haber=";
					  echo $total_haber;
		   */			
					  $tipo_mov="4";
	  
				  //GUARDA EN  COMPROBANTES
					  $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					  
				  //GUARDA EN EL LIBRO DIARIO
					  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
						  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
						  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
						  '".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
						  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
  
					  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
					  $id_libro_diario=mysql_insert_id();
					  try 
					  {
						  $sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
						  WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
						  $resultCosto=mysql_query($sqlCosto);
						  $idcodigo_v=0;
						  while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
						  {
							  $idcod_costo=$row['codigo_plan_cuentas'];
						  }
						  $idcod_costo;
  
					  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
		  //	echo 	$idcod_costo;	
					  
	  //		$idcod_costo     ="51001001";
	  
					  try 
					  {
						  $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas
						  WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
						  $resultInventario=mysql_query($sqlInventario);
						  $idcodigo_v=0;
						  while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
						  {
							  $idcod_inventario=$row['codigo_plan_cuentas'];
						  }
						  $idcod_inventario;
					  }
					  catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
		  
		  //	echo "</br>".	$idcod_inventario;	
			 
  //			$idcod_inventario="115001001"
						  
					  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
							  ( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
					  //echo $sql;
						  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
						  $plan_id_cuenta_costo=0;
						  $plan_id_cuenta_inventario=0;
					  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
					  {
						  if ($rowS['plan_cuentas_codigo']==$idcod_costo)
						  {
							  $plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
						  }
						  if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
						  {
							  $plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
						  }
					  }
					  
					  $lin_diario=0;
					  if ($tot_costo>0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
						  $debeVector[$lin_diario]=$tot_costo;
						  $haberVector[$lin_diario]=0;
							  
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$tot_costo;			
  //echo $idPlanCuentas[$lin_diario];
  //echo $idPlanCuentas[$lin_diario];										
					  }
		  
	  /* 	for($i=1; $i<=$lin_diario; $i++)
				  {
					  echo $idPlanCuentas[$i]."-";
					  echo $debeVector[$i];
					  echo $haberVector[$i];
					  echo "<br/>";
  
				  }
	   */	
		  
				   //echo "numero de cuentas";
				   //echo $lin_diario;
					  for($i=1; $i<=$lin_diario; $i++)
					  {
							  //echo "entro a costo";
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
							  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario,id_plan_cuenta,debe, haber,
									  id_periodo_contable) values 
								  ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$haberVector[$i]."','".$debeVector[$i]."',
								  '".$sesion_id_periodo_contable."');";
					  //	echo "<br>".$sqlDLD;
							  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							  $id_detalle_libro_diario=mysql_insert_id();
								  
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
  
									  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
									  $result6=mysql_query($sql6);
									  $id_mayorizacion=mysql_insert_id();
								  }
									  catch(Exception $ex) 
									  { ?> <div class="transparent_ajax_error">
										  <p>Error en la insercion de la tabla mayorizacion: 
										  <?php echo "".$ex ?></p></div> <?php }
								  // FIN DE MAYORIZACION
							  }  
						  }
					  }						
				  }
			  // crear anticipo a clientes
				  if ($total>0)
				  {
					  try
					  {
						  $numero_asiento++;
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
							  $consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
							  FROM `comprobantes` comprobantes
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
					  }	
					  catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
								
				  //FIN SACA EL ID MAX DE COMPROBANTES
		  
					  $fecha= date("Y-m-d h:i:s");
						  //$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
					  $descripcion="Asiento de costo de nota de credito No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
					  $descripcion="Asiento de costo de nota de credito No.".$numero_documento." realizado por ".$sesion_empresa_nombre;
															  
		  /* 			echo "debe=";
					  echo $total_debe;
					  echo "haber=";
					  echo $total_haber;
		   */			
					  $tipo_mov="D";
	  
				  //GUARDA EN  COMPROBANTES
					  $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					  
				  //GUARDA EN EL LIBRO DIARIO
					  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
						  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
						  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
						  '".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
						  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
  
					  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
					  $id_libro_diario=mysql_insert_id();
					  $lin_diario=0;
					  try 
					  {
						  $sql = "SELECT id_plan_cuenta FROM formas_pago  
							  WHERE id_empresa = '" .$sesion_id_empresa. "' and id_tipo_movimiento=1"; 
						  //echo $sql;
						  $resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  
						  while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
						  {
							  $lin_diario=$lin_diario+1;
							  $idPlanCuentas[$lin_diario]=$row['id_plan_cuenta'];
							  $debeVector[$lin_diario]=$tot_ventas+$tot_servicios;
				  // 			$debeVector[$lin_diario]=$total;
							  $haberVector[$lin_diario]=	0;
						  }
					  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
  
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
					  $debeVector[$lin_diario]=$txtTotalIvaFVC;
					  $haberVector[$lin_diario]=0;
					  
  
					  try 
					  {
						  $sql = "SELECT id_plan_cuenta FROM formas_pago  
							  WHERE id_empresa = '" .$sesion_id_empresa. "' and id_tipo_movimiento=14"; 
						  //echo $sql;
						  $resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  
						  while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
						  {
							  $lin_diario=$lin_diario+1;
							  $idPlanCuentas[$lin_diario]=$row['id_plan_cuenta'];
							  $debeVector[$lin_diario]=0;
							  $haberVector[$lin_diario]=$tot_ventas+$tot_servicios;
				  // 			$haberVector[$lin_diario]=$total;
						  }
					  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
  
	  
				   //echo "numero de cuentas";
				   //echo $lin_diario;
					  for($i=1; $i<=$lin_diario; $i++)
					  {
							  //echo "entro a costo";
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
							  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario,id_plan_cuenta,debe, haber,
									  id_periodo_contable) values 
								  ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
								  '".$sesion_id_periodo_contable."');";
				  // 		echo "<br>".$sqlDLD;
							  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							  $id_detalle_libro_diario=mysql_insert_id();
								  
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
  
									  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
									  $result6=mysql_query($sql6);
									  $id_mayorizacion=mysql_insert_id();
								  }
									  catch(Exception $ex) 
									  { ?> <div class="transparent_ajax_error">
										  <p>Error en la insercion de la tabla mayorizacion: 
										  <?php echo "".$ex ?></p></div> <?php }
								  // FIN DE MAYORIZACION
							  }  
						  }
					  }						
				  }
				  
				  if ($total>0)
				  {
					  $tipo_documentox="NotaCredito";
					  $referencia=$tipo_documentox."No.".documento_numero;
					  $estadoCC="Pendientes";
					  $sqlm2="Select max(id_cuenta_por_pagar) From cuentas_por_pagar;";
					  $resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					  $id_cuenta_por_pagar=0;
					  while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
					  {
						  $id_cuenta_por_pagar=$rowm2['max(id_cuenta_por_pagar)'];
					  }
					  $id_cuenta_por_pagar++;
  
					  $sql3 = "insert into cuentas_por_pagar (tipo_documento,numero_compra, referencia, valor, saldo,
						  numero_asiento, fecha_vencimiento, id_proveedor, id_cliente,id_plan_cuenta, id_empresa, 
						  id_compra, estado) 
						values ('".$tipo_documentox."','".$documento_numero."','".$referencia."','".$total."',
						'".$total."','".$id_libro_diario."','0000-00-00 00:00:00','0','".$id_cliente."','0','".$sesion_id_empresa."',
					  '".$id_venta."', '".$estadoCC."');";
  //echo $sql3;
				  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
				  $id_cuenta_por_pagar=mysql_insert_id();
							  
				  }
					  
				  $sqlki="Select max(id_kardes) From kardes";
				  $resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
				  $id_kardes='0';
				  while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
				  {
					  $id_kardes=$rowki['max(id_kardes)'];
				  }
				  $id_kardes++;
				  $sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
				  ('".$fecha_venta."','Nota Credito en Venta','".$id_venta."', '".$sesion_id_empresa."')";
				  $resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				  $id_kardes=mysql_insert_id();
				  if($result && $resp2)
				  {
					  echo "1";
				  }
				  else{
					  echo "3";
					  }
					  
					  
					  if ($emision_tipoEmision === 'E'){
							  genXmlnc($id_venta);
							  
											  $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
											  // echo $sqli;
											  $result=mysql_query($sqli);
											  
											  while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
											  {
												  $claveAcceso=$row['ClaveAcceso'];
											  }
											  if ($claveAcceso != ''){
												  echo "SI";
												  }else{
													  echo "NO";
												  }
											  
											  
							  
					  }
					  
					  
					  
					  
					  
			  }
			  
			  if ($cmbTipoDocumentoFVC==6){
				  
				//  echo "guia==>1";
					  
					  $txtIdIva2 = $_POST['txtTotalIvaFVC']; 
					  $FechaFin = $_POST['FechaFin']; 
					  $FechaInicio = $_POST['FechaInicio']; 
					  $motivoT = $_POST['MotivoTraslado']; 
					  $Vendedor_id = $_POST['chofer_id']; 
					  $DirDestino = $_POST['DirDestino']; 
					   $DirOrigen = $_POST['DirOrigen']; 
					  $txtIva=$_POST['txtTotalIvaFVC'];
						  
			  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
			  {            
				  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
				  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
				  <p>Error: '.mysql_error().' </p></div>  ');;
				  $iva=0;
				  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				  {
					  $iva=$rowIva1['iva'];
					  $txtIdIva=$rowIva1['id_iva'];
					  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				  }
				  
			  }
  
					  
					  $sql="insert into ventas (fecha_venta,       estado,        total, sub_total,sub0,sub12,descuento,propina,numero_factura_venta, fecha_anulacion, descripcion, id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, FechaInicio,FechaFin,DirDestino,DirOrigen,Vendedor_id,MotivoTraslado, RetIva) 
					  values (                '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$FechaInicio."','".$FechaFin."','".$DirDestino."','".$DirOrigen."','".$Vendedor_id."','".$motivoT."','".$facAn."');";
  
					  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
					  $id_venta=mysql_insert_id();
  
					  if($limite==0){
						  $limite=0;
					  }else if($limite==1){
						  $limite=$limite-2;
					  }else{
						  $limite=$limite-1;
					  }
					  
					  $sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
					  $resultEmpresa2=mysql_query($sqlEmpresa2) or die();
				  
			  if($sql){
							  for($i=1; $i<=$txtContadorFilas; $i++)
				  {
  
					  if($_POST['txtIdServicio'.$i] >= 1)
					  { //verifica si en el campo esta agregada una cuenta
			  
						  $cantidad = $_POST['txtCantidadS'.$i];
						  $idServicio = $_POST['txtIdServicio'.$i];
						  $idKardex = $_POST['txtIdServicio'.$i];
						  $valorUnitario = $_POST['txtValorUnitarioS'.$i];
						  $valorTotal = $_POST['txtValorTotalS'.$i];
						  
						  $id_tipoP = $_POST['txtTipoS'.$i];
						  $idBod = $_POST['idbod'.$i];
						  $idvalorPago=$_POST['txtValorS'.$i];
						  $txtdetalle2=$_POST['txtdetalle2'.$i];
									  //GUARDA EN EL DETALLE VENTAS
						  $sql2 = "insert into detalle_ventas ( cantidad, estado, v_unitario, v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa) 
						  values ('".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."'	, '".$txtdetalle2."' ,'".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";
  
						  $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
  
						  $id_detalle_venta=mysql_insert_id();
						  
						  if($sql2){
							//  echo "guia";
  
							  generarXMLGUIA($id_venta);
						   //  print_r($result);
							  
												  $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
												  // echo $sqli;
												  $result=mysql_query($sqli);
												  
												  while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
												  {
													  $claveAcceso=$row['ClaveAcceso'];
												  }
												  if ($claveAcceso != ''){
													  echo "SI";
													  }else{
														  echo "NO";
													  }
											  
											  
							  
		  // 			}
							  
						  }else{
							  echo "guiano";
						  }
  
  
			  
			  
					  }
					  
  
				  }
							  
						  }else{
							  echo "2";
						  }
				  
  
				 
					  
				  }
				  
				  
				  if ($cmbTipoDocumentoFVC==5)
			  {
				   $txtIva=$_POST['txtTotalIvaFVC'];
				  
						  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
			  {            
				  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
				  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				  // echo $sqlIva1;
				  $iva=0;
				  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				  {
					  $iva=$rowIva1['iva'];
					  $txtIdIva=$rowIva1['id_iva'];
					  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				  }
				  
			  }
				  if($id_cliente!="" && $numero_factura !="" && $sub_total>0  )
				  {       
					   $sql="insert into ventas (fecha_venta, estado, total, sub_total,sub0,sub12,                                     descuento,      propina,        numero_factura_venta, fecha_anulacion, descripcion,              id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario) 
					  values ('".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,            '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."');";
  // echo $sql;
					  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar proforma: '.mysql_error().' </p></div>  ');
					  $id_venta=mysql_insert_id();
					  
								  if($limite==0){
						  $limite=0;
					  }else if($limite==1){
						  $limite=$limite-2;
					  }else{
						  $limite=$limite-1;
					  }
					  
					  $sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
					  $resultEmpresa2=mysql_query($sqlEmpresa2) or die();
				  
				   
					  for($i=1; $i<=$txtContadorFilas; $i++)	
					  {
					  if($_POST['txtIdServicio'.$i] >= 1)
					  { //verifica si en el campo esta agregada una cuenta
			  
							  $cantidad = $_POST['txtCantidadS'.$i];
							  $idServicio = $_POST['txtIdServicio'.$i];
							  $idKardex = $_POST['txtIdServicio'.$i];
							  $valorUnitario = $_POST['txtValorUnitarioS'.$i];
							  $valorTotal = $_POST['txtValorTotalS'.$i];
							  $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
							  $txtTipo11 = $_POST['txtTipo'.$i];
							  
							  $id_tipoP = $_POST['txtTipoS'.$i];
							  $cuenta = $_POST['cuenta'.$i];
							  $idBod = $_POST['idbod'.$i];
							  $idvalorPago=$_POST['txtValorS'.$i];
						  
						  
							  $txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
							  if ($id_tipoP == "2" && $txtDescripcionS!='') {
								  $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
								  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
								  // echo "UPDATE PRODUCTO ==>".$sql3;
							  }
							  
							  if ($sql){
									  $sql2 = "insert into detalle_ventas ( idBodega,cantidad, estado, v_unitario, v_total, 	id_venta, id_servicio,id_kardex,tipo_venta, id_empresa) 
							  values ('".$idBod."','".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."'	, '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";
							  $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
							  <p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
							  $id_detalle_venta=mysql_insert_id();
							  if($sql2){
								  echo "1";
							  }else{
								  echo "3";
							  }
							  }else{
								  echo "2";
							  }
							  
							  //GUARDA EN EL DETALLE VENTAS
						  
						  }
					  }
								  
				  }
				  else
				  {
				  echo 'Datos vacios';
			  }
		  }
		  
			  
		  
		  
		  }
		  
			
		  
		  
		  catch (Exception $e) 
		  {
			  // Error en algun momento.
				 ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
		  }
		  
	  
	  }else{
		  echo "Limite de facturas alcanzado, consulta con tu proveedor";
	  }
	  
	  
	  
	  
	  
  }
  	
	
  if($accion == "16")
	{
	    if(isset($_POST['idCliente'])){
	         $cargarCliente = trim($_POST['idCliente']);
	    }else{
	         $cargarCliente = '';
	    }
   
    
    if($cargarCliente!=''){
        $sql="SELECT `id_cliente`, `nombre`, `apellido`, `direccion`, `cedula`, `telefono`FROM `clientes` WHERE `id_empresa`=$sesion_id_empresa and  id_cliente = '".$cargarCliente."' ";
    }else{
        $sql="SELECT `id_cliente`, `nombre`, `apellido`, `direccion`, `cedula`, `telefono`FROM `clientes` WHERE `id_empresa`=$sesion_id_empresa and  cedula = '9999999999999' ";
    }
		
		$result= mysql_query($sql);

		$numFilas = mysql_num_rows($result);
		$response = [];
		if($numFilas>0){
			while($row = mysql_fetch_array($result)){
				$response['nombre']= $row['nombre'];
				$response['apellido']=$row['apellido'];
				$response['direccion']= $row['direccion'];
				$response['cedula']= $row['cedula'];
				$response['telefono']= $row['telefono'];
				$response['id_cliente']= $row['id_cliente'];
			}
		}
		$response['numFilas']= $numFilas;

		echo json_encode($response);
	}
	
if($accion == "20")
{
	$id_venta = $_POST['idFactura'];
	$numero_factura=$_POST['txtNumeroFacturaFVC'];

	// primera validacion 
     $sqlValidar = "SELECT `id_venta`, `fecha_venta`, `estado`, `total`, `sub_total`, `sub0`, `sub12`, `descuento`, `propina`, `numero_factura_venta`, `fecha_anulacion`, `descripcion`, `id_iva`, `montoIce`, `id_vendedor`, `id_cliente`, `id_empresa`, `tipo_documento`, `codigo_pun`, `codigo_lug`, `id_forma_pago`, `id_usuario`, `Retfuente`, `Comentario`, `Comentario2`, `Autorizacion`, `FechaAutorizacion`, `ClaveAcceso`, `FechaInicio`, `FechaFin`, `DirDestino`, `MotivoTraslado`, `Retiva`, `Vendedor_id`, `MotivoNota`, `Otros`, `numero_cierre` FROM `ventas` WHERE id_venta =$id_venta";
	$resultValidar = mysql_query($sqlValidar);
	$autorizacion ='';
	while($rowVal = mysql_fetch_array($resultValidar)){
		$autorizacion = $rowVal['Autorizacion'];
	}
	if(trim($autorizacion)!=''){
		echo 'No se puede editar, la venta esta autorizada.';
		exit;
	}
    
    $validacion_tipo_documento = 'Factura No.';
    if( $_POST['cmbTipoDocumentoFVC'] ==100){
        $validacion_tipo_documento = 'Nota de venta No';
    }
	// segunda validacion 
 	 $sqlValidarCuentasPorPagar="SELECT id_cuenta_por_pagar, `tipo_documento`, `numero_compra`, `valor`, `saldo`, `numero_asiento`, `fecha_pago`, `id_empresa`, `id_compra`, `estado`, `id_empleado` FROM  `cuentas_por_pagar` WHERE `tipo_documento`='".$validacion_tipo_documento."' and `numero_compra`='".$numero_factura."' and id_empresa= '".$sesion_id_empresa."' and id_compra='".$id_venta."'  and valor != saldo ";
    $resulValidarCuentasPorPagar=mysql_query($sqlValidarCuentasPorPagar);
	$cantidadCuentasPorPagarCanceladas = mysql_num_rows($resulValidarCuentasPorPagar);

	if($cantidadCuentasPorPagarCanceladas>0){
		echo 'No se puede editar, existen cuentas por pagar de esta venta canceladas.';
		exit;
	}  
              
	// tercera validacion 
    $validar_tipo_documento=$_POST['cmbTipoDocumentoFVC'];

	 $sqlVerificarCuentasCobrar="SELECT`id_cuenta_por_cobrar`, `tipo_documento`, `numero_factura`, `referencia`, `valor`, `saldo`, `numero_asiento`, `fecha_vencimiento`, `fecha_pago`, `id_cliente`, `id_proveedor`, `id_empleado`, `id_lead`, `id_plan_cuenta`, `id_empresa`, `id_venta`, `estado`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `cuotaAdmin`, `motivoDescuento` FROM `cuentas_por_cobrar` where id_empresa='".$sesion_id_empresa."' 
	and numero_factura='".$numero_factura."'   and tipo_documento='".$validar_tipo_documento."' AND valor != saldo ;";
	$resultVerificarCuentasCobrar = mysql_query($sqlVerificarCuentasCobrar);
	$cantidadCuentasPorCobrarCanceladas = mysql_num_rows($resultVerificarCuentasCobrar);

	if($cantidadCuentasPorCobrarCanceladas>0){
		echo 'No se puede editar, existen cuentas por cobrar de esta venta canceladas.';
		exit;
	}
	
  $modoFacturacion=$_POST['modo']; 

      try
      {
        $id_venta = $_POST['idFactura'];
          
          $cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
          $numero_factura=$_POST['txtNumeroFacturaFVC'];
          $fecha_venta= $_POST['textFechaFVC'];		
          $id_cliente=$_POST['textIdClienteFVC'];
          $estado = "Activo";
          $cmbIdVendedor=$_POST['chofer_id'];
          $cmbEst=$_POST['cmbEst'];
          $cmbEmi=$_POST['cmbEmi'];
          $txtNombreFVC=$_POST['txtNombreFVC'];
          $idFormaPago=0;
          $txtCuotasTP=0;
          $total=$_POST['txtTotalFVC'];
          $sub_total=$_POST['txtSubtotalFVC'];
          $sub_total0		= isset($_POST['txtSubtotal0FVC'])? $_POST['txtSubtotal0FVC']: 0;
    			$sub_total12	= isset($_POST['txtSubtotal12FVC'])? $_POST['txtSubtotal12FVC'] :0;
          $descuento=$_POST['txtDescuentoFVCNum'];
          $propina=$_POST['txtPropinaFVC'];
          $txtIva=$_POST['txtIva1'];
          $facAn=$_POST['facAn'];
          $motivo =$_POST['MotivoNota'] ;
          $txtTotalIvaFVC=$_POST['txtTotalIvaFVC'];
          $txtDescripcion=$_POST['txtDescripcionFVC'];
          $txtContadorFilas=$_POST['txtContadorFilasFVC'];

          $txtCambioFP=$_POST['txtCambioFP'];
          $txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
    
    
            // if($sesion_id_empresa==41){
                for($g=1; $g<=$txtContadorFilas; $g++)	
                  {
                      if($_POST['txtIdServicio'.$g] >= 1 && trim($_POST['txtCantidadS'.$g])=='' )
                      { 
                    	echo 'Existe un producto sin cantidad ingresada, no se puede actualizar la venta.';
                		exit;
                      } 
                  } 
            // }
          if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
          {            
              $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
              $resultIva1=mysql_query($sqlIva1);
              $iva=0;
              while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
              {
                  $iva=$rowIva1['iva'];
                  $txtIdIva=$rowIva1['id_iva'];
                  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
              }
          }

          if ($cmbTipoDocumentoFVC==1 || $cmbTipoDocumentoFVC==100)		  
          {
              if($id_cliente!="" && $numero_factura !=""  )
              {   
                  $incoterm =isset($_POST['incoterm'])?$_POST['incoterm']:'0';
    			        $lugarIncoTerm =isset($_POST['lugarIncoTerm'])?$_POST['lugarIncoTerm']:'';
    			        
    			        $paisOrigen =isset($_POST['paisOrigen'])?$_POST['paisOrigen']:'';
    			        
    			        $puertoEmbarque =isset($_POST['puertoEmbarque'])?$_POST['puertoEmbarque']:'';
    			       
    			        $puertoDestino =isset($_POST['puertoDestino'])?$_POST['puertoDestino']:'';
    			        
    			         $paisDestino=isset($_POST['paisDestino'])?$_POST['paisDestino']:'';
    			         
    			        $paisAdquisicion =isset($_POST['paisAdquisicion'])?$_POST['paisAdquisicion']:'';
    			        
    			        $numeroDae =isset($_POST['numeroDae'])?$_POST['numeroDae']:'';
    			        
    			        $numeroTransporte =isset($_POST['numeroTransporte'])?$_POST['numeroTransporte']:'';
    			        
    			        $fleteInternacional =isset($_POST['fleteInternacional'])?$_POST['fleteInternacional']:'';
    			        
    			        $seguroInternacional =isset($_POST['seguroInternacional'])?$_POST['seguroInternacional']:'';
    			        
    			    $gastosAduaneros=0;
    			        if(isset($_POST['gastosAduaneros'])){
    			            if(trim($_POST['gastosAduaneros'])!=''){
    			                $gastosAduaneros =$_POST['gastosAduaneros'];
    			            }
    			        }
    			       $gastosTransporte=0;
    			       if(isset($_POST['gastosTransporte'])){
    			            if(trim($_POST['gastosTransporte'])!=''){
    			                $gastosTransporte =$_POST['gastosTransporte'];
    			            }
    			        }
    			        
    			        	$vendedor_id = trim($_POST['vendedor_id']);
    			    $vendedor_id = ($vendedor_id=='')?0:$vendedor_id;
    			    

    	    $sql="update ventas set fecha_venta='".$fecha_venta."', total='".$total."', sub_total='".$sub_total."' , sub0='".$sub_total0."' , sub12='".$sub_total12."', descuento='".$descuento."', propina='".$propina."', descripcion='".$txtDescripcion."', id_iva='".$txtIdIva."', id_vendedor='".$cmbIdVendedor."', id_cliente='".$id_cliente."', numero_factura_venta='".$numero_factura."', id_usuario='".$sesion_usuario."', id_forma_pago='".$idFormaPago."',`tipo_inco_term`='".$incoterm."',`lugar_inco_term`='".$lugarIncoTerm."',`pais_origen`='".$paisOrigen."',`puerto_embarque`='".$puertoEmbarque."',`puerto_destino`='".$puertoDestino."',`pais_destino`='".$paisDestino."',`pais_adquisicion`='".$paisAdquisicion."',`numero_dae`='".$numeroDae."',`numero_transporte`='".$numeroTransporte."',`flete_internacional`='".$fleteInternacional."',`seguro_internaiconal`='".$seguroInternacional."',`gastos_aduaneros`='".$gastosAduaneros."',`gastos_transporte`='".$gastosTransporte."', vendedor_id_tabla='".$vendedor_id."', total_iva ='".$txtTotalIvaFVC."'  WHERE id_venta='".$id_venta."' ;";
  
    	
                
                  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al actualizar la venta: '.mysql_error().' </p></div>  ');
                  

                 $sqlReestablecerStock = "SELECT `id_detalle_venta`, `idBodega`, `idBodegaInventario`, `cantidad`, `id_venta`, `id_servicio`, productos.codigo 
                FROM `detalle_ventas` 
                INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio 
                WHERE id_venta='".$id_venta."'  ";
                $resultReestablecerStock = mysql_query($sqlReestablecerStock)or die('<div class="transparent_ajax_error">
                <p>Error al selecionar los detalles de la venta: '.mysql_error().' </p></div>  ');
                while($rowReestStock = mysql_fetch_array($resultReestablecerStock)){
                    $detalle_cantidad = trim($rowReestStock['cantidad']);
                    $detalle_bodega = trim($rowReestStock['idBodegaInventario']);
                    $detalle_codigo = trim($rowReestStock['codigo']);
                    
                    if($detalle_cantidad!='' && $detalle_bodega!=''&& $detalle_codigo!=''){
   
                        $sqlbodegasReestablecer="UPDATE `cantBodegas` SET `cantidad`=cantidad+'".$rowReestStock['cantidad']."' WHERE idBodega ='".$rowReestStock['idBodegaInventario']."' AND  idProducto ='".$rowReestStock['codigo']."'  ";
                    $resultBodegasReestablecer=mysql_query($sqlbodegasReestablecer)or die('<div class="transparent_ajax_error">
                    <p>Error: al actualizar la cantidad de las bodegas'.mysql_error().$sqlbodegasReestablecer.' </p></div>  ') ;
                    }
                 
                }
            
                 $sqlBorrarDetallesVentas = "DELETE FROM `detalle_ventas` WHERE `id_venta` ='".$id_venta."'  ";
                $resultBorrarDetallesVentas=mysql_query($sqlBorrarDetallesVentas)or die('<div class="transparent_ajax_error">
                <p>Error al eliminar los detalles anteriores de las ventes: '.mysql_error().' </p></div>  ');

 if($_POST['idtipocliente'] =='08'){
                     $iva_exportador=0;
                     $sql_iva_exportador= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='". $sesion_id_empresa . "' AND iva=0 ";
                    $result_iva_exportador = mysql_query( $sql_iva_exportador );
                    while($row_iva_exportador = mysql_fetch_array($result_iva_exportador) ){
                        $iva_exportador= $row_iva_exportador['id_iva'];
                    }
                 }
                  for($i=1; $i<=$txtContadorFilas; $i++)	
                  {
                  if($_POST['txtIdServicio'.$i] >= 1)
                  { 
          
                          $cantidad = $_POST['txtCantidadS'.$i];
                          $idServicio = $_POST['txtIdServicio'.$i];
                          $idKardex = $_POST['txtIdServicio'.$i];
                          $valorUnitario = $_POST['txtValorUnitarioShidden'.$i];
                          $valorTotal = $_POST['txtValorTotalS'.$i];
                          $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
                          $txtTipo11 = $_POST['txtTipo'.$i];							  
                          $id_tipoP = $_POST['txtTipoS'.$i];
                          $cuenta = $_POST['cuenta'.$i];
                          $idBod = $_POST['idbod'.$i];
                          $idvalorPago=$_POST['txtValorS'.$i];
                          $txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];
                          $txtdetalle2=$_POST['txtdetalle2'.$i];
                           $bodegaCantidad=$_POST['bodegaCantidad'.$i];
                          if($bodegaCantidad!=''){
                              $bodegaCantidad=$_POST['bodegaCantidad'.$i];
                          }else{
                              $bodegaCantidad='0';
                          }
                          $txtCodigoServicio=$_POST['txtCodigoServicio'.$i];

                          
                          $txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
                          if ($id_tipoP == "2" && $txtDescripcionS!='') {
                              $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
                              $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
                              // echo "UPDATE PRODUCTO ==>".$sql3;
                          }
                          
						  $id_iva = $_POST['IVA120'.$i];
						  $sql_iva= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='" . $sesion_id_empresa . "' AND id_iva='" . $id_iva . "' ";
						  $result_iva = mysql_query( $sql_iva );
						  while($row_iva = mysql_fetch_array($result_iva) ){
							  $tarifa_iva= $row_iva['iva'];
						  }
						  

						  $total_iva= floatval($valorTotal) * (floatval( $tarifa_iva )/100);    
						  if($_POST['idtipocliente'] =='08'){
    			                    $id_iva=(trim($iva_exportador)=='')?0:$iva_exportador;
                                     $total_iva=0;
                                 }
                          //GUARDA EN EL DETALLE VENTAS
               $sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa, `tarifa_iva`, `total_iva`) 
                          values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."', '".$id_iva."','".$total_iva."' );";
                          $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
                          <p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
                          $id_detalle_venta=mysql_insert_id();
      
                  if ($id_tipoP == "1") {
                     
        				    if($bodegaCantidad!=''){
                              $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$bodegaCantidad."' and 
                                  idProducto='".$txtCodigoServicio."'  ";
                                  $resultado = mysql_query($stockBodegas);
                                  while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
                                      {
                                          $id=$rowBodegas1['id'];
                                          $cantidadBodega=$rowBodegas1['cantidad'];
                                      }
                                  $cantidadBodega = $cantidadBodega-$cantidad;
                                //  $sqlbodegas="UPDATE `cantBodegas` SET `cantidad`='".$cantidadBodega."' WHERE idProducto='".$txtCodigoServicio."' and id='".$id."'";
                               $sqlbodegas="UPDATE `cantBodegas` SET `cantidad`='".$cantidadBodega."' WHERE id='".$id."'";
                                  $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
  
                              }
                      }
                  }// fin verificar si esta llena la fila
              }//fin for 

            //   if ($sesion_tipo_empresa=="6")
            //   { // aqui3
                  //permite sacar el numero_asiento de libro_diario
                  $tot_costo=0;

      $tipo_comprobante = "Diario"; 

                  $fecha= date("Y-m-d h:i:s");
                  $descripcion = "Factura de venta #".$numero_factura." realizada a ".$txtNombreFVC;
                 
                   if( $cmbTipoDocumentoFVC==100 ){
    					     	$descripcion = "Nota de venta #".$numero_factura." realizada a ".$txtNombreFVC;
    					 }
                  $debe = $total;
                  $debe2 = $descuento;
                  $total_debe = $debe + $debe2;
                  
                  $haber1 = $sub_total;
                  $haber2 = $_POST['txtTotalIvaFVC'];
                  
                  $total_haber = $haber1 + $haber2 + $propina;
                  
                  $tipo_mov="F";
  
                  $id_libro_diario=0;	
                  $sqlBuscarId="SELECT `id_libro_diario`, `id_periodo_contable`, `numero_asiento`,  `numero_comprobante`, `tipo_comprobante`, `id_comprobante` FROM `libro_diario` WHERE id_periodo_contable='".$sesion_id_periodo_contable."'  and `tipo_mov`='F' and `numero_cpra_vta`='".$numero_factura."' and `tipo_comprobante`='Diario' AND descripcion= '".$descripcion."' ";
                  $resultBuscarId=mysql_query($sqlBuscarId)or die('<div class="transparent_ajax_error">
                  <p>Error al encontrar el id libro diario: '.mysql_error().' </p></div>  ');
                  while($rowBI=mysql_fetch_array($resultBuscarId))
                      {
                        $id_libro_diario= $rowBI['id_libro_diario'];
                      }
                    
                   $sqlLD = " UPDATE `libro_diario` SET `total_debe`='".$total_debe."',`total_haber`='".$total_haber."'  WHERE id_libro_diario  =$id_libro_diario";

                  $respD = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
            
                 
              
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
                  
                  
                  


                  $valor[$lin_diario]=0;
                  $ident=0;
                  

                 $sqlBorrarDetalleLibroDiario= "DELETE FROM `detalle_libro_diario` WHERE  `id_libro_diario`='".$id_libro_diario."'  ";
                  $respBorrarDetalleLibroDiario = mysql_query($sqlBorrarDetalleLibroDiario) ;
            

                $sqlBorrarCobrosPagos= "DELETE FROM `cobrospagos` WHERE  `id_empresa`='".$sesion_id_empresa."' and `id_factura`='".$id_venta."' and documento='0'  ";
                $respBorrarCobrosPagos = mysql_query($sqlBorrarCobrosPagos) ;
                

                  $sqlBorrarCuentasPorPagar="DELETE FROM `cuentas_por_pagar` WHERE `tipo_documento`='Factura No.' and `numero_compra`='".$numero_factura."' and id_empresa= '".$sesion_id_empresa."' and id_compra='".$id_venta."'  and estado= 'Pendientes'";
                $resulBorrarCuentasPorPagar=mysql_query($sqlBorrarCuentasPorPagar);
                
                
                 $sqlBorrarCuentasCobrar="DELETE FROM `cuentas_por_cobrar` where id_empresa='".$sesion_id_empresa."' 
                and numero_factura='".$numero_factura."'   and tipo_documento='1' ;";
                $resultBorrarCuentasCobrar=mysql_query($sqlBorrarCuentasCobrar);
                
                


                  for($i=1; $i<=$txtContadorFilas; $i++)				
                  {
                      if($_POST['txtCodigoS'.$i] >=1)
                      {	
                          
                          
                          $lin_diario=$lin_diario+1;
                          $idPlanCuentas[$lin_diario]=$_POST['txtCodigoS'.$i];
                          $debeVector[$lin_diario]=$_POST['txtValorS'.$i];
                          $haberVector[$lin_diario]=0; 
                          
                          $formaPagoId[$lin_diario]=$_POST['formaPagoId'.$i];
                          
                           $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) 
                          VALUES     ('".$formaPagoId[$i]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$i]."','".$_POST['txtTipo1'.$i]."', NULL );";
                          // echo $sqlforma;
                          $respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
                          if($respForma){
                              if($_POST['txtTipo1'.$i]==1 ){
                               $identificador="01";
                              }
                              else if($_POST['txtTipo1'.$i]==2){
                                $ident=1;
                                  $identificador="02";
                              }else if($_POST['txtTipo1'.$i]==16 && $_POST['txtTipo1'.$i]==17 ){
                              //   $ident=1;
                                  $identificador="19";
                              }
                              else{
                                  $identificador="03";
                              }
                                 if($ident==1){
                                     $identificador="02";
                                 } 
                                  $sql3="update ventas set id_forma_pago='".$identificador."' where id_venta='".$id_venta."' ";
                                  $resp3 = mysql_query($sql3) or die(mysql_error());
                                  
                              
                          }
                          
                          
                      }
                      
                      if ($_POST['txtTipo1'.$i]=='4')
                      {
                          $total=$_POST['txtValorS'.$i];
                          $txtCuotasTP=$_POST['txtCuotaS'.$i];
                          $formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];		
                      }
                    
                      if ($_POST['txtTipo1'.$i]=='13')
                      {
                          $estadoCC="Pendientes";
                          
                          $total_x_pagar=$_POST['txtValorS'.$i];
                          $formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
                          $cuentaxpagar="SI";
                          $sqlm2="Select max(id_cuenta_por_pagar) From cuentas_por_pagar;";
                          $resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                          $id_cuenta_por_pagar=0;
                          while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
                          {
                              $id_cuenta_por_pagar=$rowm2['max(id_cuenta_por_pagar)'];
                          }
                          $id_cuenta_por_pagar++;
                          $cmbTipoDocumentoFVC="Factura No.";
                           if(  $_POST['cmbTipoDocumentoFVC'] == 100 ){
    							     $cmbTipoDocumentoFVC="Nota de venta No.";
    					 }
                          $sql3 = "insert into cuentas_por_pagar (tipo_documento, numero_compra, referencia,  
                          valor, saldo,numero_asiento,fecha_vencimiento,  id_proveedor,id_cliente ,id_plan_cuenta, id_empresa,
                          id_compra, estado) " . "values 
                          ('".$cmbTipoDocumentoFVC."','".$numero_factura."','".
                          $txtNombreFVC.", ".$cmbTipoDocumentoFVC.",".$numero_factura."', '".
                          $total_x_pagar."','".$total_x_pagar."','','".$fecha_venta."',null,'".$id_cliente."','".
                          $formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."',
                          '".$id_venta."', '".$estadoCC."');";

                          $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
                          $id_cuenta_por_pagar=mysql_insert_id();
                      }
                      
                      if ($_POST['txtTipo1'.$i]=='17')
                      {
                        $sqlBorrarDetalleBancos = "DELETE FROM `detalle_bancos` WHERE `id_libro_diario`='".$id_libro_diario."'  ";
                        $respBorrarDetalleBancos = mysql_query($sqlBorrarDetalleBancos) ;
                        

                          echo "TRANSFERENCIA</br>";
                                        //permite sacar el id maximo de bancos
                              try{
                                  $sqlmaxb="Select max(id_bancos) From bancos;";
                                  $resultmaxb=mysql_query($sqlmaxb);
                                  $id_bancos=0;
                                  while($rowmaxb=mysql_fetch_array($resultmaxb))//permite ir de fila en fila de la tabla
                                  {
                                      $id_bancos=$rowmaxb['max(id_bancos)'];
                                  }
                                  $id_bancos++;
      
                              }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
      
                              try {
                                  //permite sacar el id maximo de la tabla detalle_bancos
                                  $sqlmaxdb="Select max(id_detalle_banco) From detalle_bancos;";
                                  $resultmaxdb=mysql_query($sqlmaxdb);
                                  $id_detalle_banco=0;
                                  while($rowmaxdb=mysql_fetch_array($resultmaxdb))//permite ir de fila en fila de la tabla
                                  {
                                      $id_detalle_banco=$rowmaxdb['max(id_detalle_banco)'];
                                  }
                                  $id_detalle_banco++;
      
                              }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
                                  
                                  
                                  
                                  $cmbTipoDocumento='Transferencia';
                                  $txtNumeroDocumento=$_POST['txtNumDocumento'];
                                  $txtDetalleDocumento="Transferencia de ".$txtNombreFVC ;
                                  $txtFechaEmision=$fecha_venta;
                                  $txtFechaVencimiento=$fecha_venta;
                                  $saldo_conciliado = 0;
                                  $valorConciliacion = $_POST['txtValorS'.$i];
                                  $estado = "No Conciliado";
                                  
                                  $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$_POST['txtCodigoS'.$i]."' 
                                  AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
                                  $resultb2=mysql_query($sqlb2);
                                  while($rowb2=mysql_fetch_array($resultb2))//permite ir de fila en fila de la tabla
                                  {
                                      $id_bancos2=$rowb2['id_bancos'];
                                  }    
                                  
                                  $numero_fil = mysql_num_rows($resultb2);
                                  
                                  if($numero_fil > 0){
                                    
                                     $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                      values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
                                      $resultDB=mysql_query($sqlDB);
                                      $id_detalle_banco=mysql_insert_id();
                                    //   echo "trans</br>".$sqlDB."</br>";
                                  }else {
                                    
                                      $sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values 
                                      ('".$_POST['txtCodigoS'.$i]."','".$saldo_conciliado."', '".$sesion_id_periodo_contable."');";
                                      $resultB=mysql_query($sqlB);
                                      $id_bancos=mysql_insert_id();
                                      $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                      values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
                                      $resultDB=mysql_query($sqlDB);
                                      $id_detalle_banco=mysql_insert_id();
                                      echo "bancos</br>".$sqlB."</br>";
                                      echo "detalle</br>".$sqlDB."</br>";
                                  }
  
                      }
                      
                  }

                  $tot_ventas=0;
                  $tot_servicios=0;
                  $tot_costo=0;
                $listaServicios=array();

                  for($i=1; $i<=$txtContadorFilas; $i++){
                      if($_POST['txtIdServicio'.$i] >= 1)
                      {
                          $idProducto=$_POST['txtIdServicio'.$i];
                         $id_tipoP = $_POST['txtTipoS'.$i];
                          $cuenta = $_POST['cuenta'.$i];
						
                          if ($id_tipoP =="1"){
                              $tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
                              $costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
                               $tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
                          }
                          
                          if ($id_tipoP == "2" ){
                              $sqlPlanCuenta= "SELECT  
								productos.`id_producto` AS productos_id_producto, 
							 productos.`producto` AS productos_nombre,
							 productos.`grupo` AS productos_grupo, 
							 centro_costo.`id_centro_costo` AS centro_id, 
							 centro_costo.`descripcion` AS centro_descripcion, 
							 centro_costo.`id_cuenta` AS productos_id_cuenta,
							 plan_cuentas.codigo,
      						 plan_cuentas.nombre
							  
							 FROM  `productos` 
							 
							 INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
							 INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = centro_costo.id_cuenta
						 
							 
							 WHERE productos.id_producto=$idProducto ";
							 $resultPlanCuentas= mysql_query($sqlPlanCuenta);
							 $planCuentaServicio = 0;
							 while($rowPC = mysql_fetch_array($resultPlanCuentas)){
								$planCuentaServicio = $rowPC['productos_id_cuenta'];
							 }
							 if(array_key_exists($planCuentaServicio,$listaServicios)){
                                                $listaServicios[$planCuentaServicio]=$listaServicios[$planCuentaServicio]+ $_POST['txtValorTotalS'.$i]; 
                                              }else{
                                                   $listaServicios[$planCuentaServicio]=$_POST['txtValorTotalS'.$i]; 
                                              }
                              $tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
                          }
						
                      }
                  }

              try 
              {
                  $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
                  WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
                  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                  $resultMercaderia=mysql_query($sqlMercaderia);
                  $idcodigo_v=0;
                  while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
                  {
                      $idcodigo_v=$row['codigo_plan_cuentas'];
                  }
                  $idcodigo_v;
              }
              catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
              
              try 
              {
                  $sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
                  WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
                  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                  $resultServicio=mysql_query($sqlServicio);
                  $idcodigo_s=0;
                  while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
                  {
                      $idcodigo_s=$row['codigo_plan_cuentas'];
                  }
                  $idcodigo_s;

              }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				

                      
                  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
                        from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
                          ( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
  
                  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
                  $plan_id_cuenta_vta=0;
                  $plan_id_cuenta_servicio=0;
                  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
                  {
                      if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
                          {
                              $plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
                          }
                      if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
                          {
                              $plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
                          }

                          
                  }
                  
                  
                
                  if ($tot_ventas!=0)
                  {
                      $lin_diario=$lin_diario+1;
                      $idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
                      $debeVector[$lin_diario]=0;
                      $haberVector[$lin_diario]=$tot_ventas;
                      

                  }
                  
                  if ($tot_servicios!=0)
                  {
                      foreach ($listaServicios as $key => $value) {
                        $lin_diario=$lin_diario+1;
                                   $idPlanCuentas[$lin_diario]= $key;
                                   $debeVector[$lin_diario]=$value;
                                   $haberVector[$lin_diario]=0;
                        }
                  }
                  
                  
                  try {
                          $sqlDescuento="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago WHERE `id_tipo_movimiento` =14 
                          and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                          $resultDescuento=mysql_query($sqlDescuento);
                          $idcodigo_d=0;
                          while($row=mysql_fetch_array($resultDescuento))//permite ir de fila en fila de la tabla
                          {
                              $idcodigo_d=$row['codigo_plan_cuentas'];
                          }
                          $idcodigo_d;
      
                  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
                  
                  
                  
                  if ($descuento!=0)
                  {
                      $lin_diario=$lin_diario+1;
                      $idPlanCuentas[$lin_diario]= $idcodigo_d;
                      $debeVector[$lin_diario]=$descuento;
                      $haberVector[$lin_diario]=0;
                  }
                  
                  
                  try {
                          $sqlPropina="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago 
                          WHERE `id_tipo_movimiento` =15 and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                          $resultPropina=mysql_query($sqlPropina);
                          $idcodigo_p=0;
                          while($row=mysql_fetch_array($resultPropina))//permite ir de fila en fila de la tabla
                          {
                              $idcodigo_p=$row['codigo_plan_cuentas'];
                          }
                          $idcodigo_p;
      
                  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
                  
                  
                  if ($propina!=0)
                  {
                      $lin_diario=$lin_diario+1;
                      $idPlanCuentas[$lin_diario]= $idcodigo_p;
                      $debeVector[$lin_diario]=0;
                      $haberVector[$lin_diario]=$propina;
                  }
                  
                  if ($txtTotalIvaFVC!=0)
                  {

                      $lin_diario=$lin_diario+1;
                      $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
                      $debeVector[$lin_diario]=0;
                      $haberVector[$lin_diario]=$txtTotalIvaFVC;

                  }
                  
                  
                  
                  
                  for($i=1; $i<=$lin_diario; $i++)
                  {
                      

                      if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
                      {
                      //permite sacar el id maximo de detalle_libro_diario

                      //GUARDA EN EL DETALLE LIBRO DIARIO
                          $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
                          ('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
                  
                          $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                          $id_detalle_libro_diario=mysql_insert_id();		
              // 			echo "DETALLE==".$sqlDLD."</br>";
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

                                  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                                  $result6=mysql_query($sql6);
                                  $id_mayorizacion=mysql_insert_id();
                              }
                              catch(Exception $ex) 
                              { ?> <div class="transparent_ajax_error">
                                  <p>Error en la insercion de la tabla mayorizacion: 
                                  <?php echo "".$ex ?></p></div> <?php }
                              // FIN DE MAYORIZACION
                          }
                      }
                  }
                  
                  
                
//ASIENTO DE COSTO
$respLD2=true;
              	// echo "	va a crear CostoS=".$tot_costo;
                  if ($tot_costo>0)
                  {
      
                      $tipo_comprobante = "Diario"; 

      
                      $fecha= date("Y-m-d h:i:s");
                      //$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
                      $descripcion="Asiento de costo de venta de la Factura No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
                    if(  $_POST['cmbTipoDocumentoFVC'] == 100 ){
    					$descripcion="Asiento de costo de venta de la Nota de Venta No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
    					 }
                      $total_debe  = $tot_costo;
                      $total_haber = $tot_costo;

                      $tipo_mov="F";
                       $sqlBuscarId2="SELECT `id_libro_diario`, `id_periodo_contable`, `numero_asiento`,  `numero_comprobante`, `tipo_comprobante`, `id_comprobante` FROM `libro_diario` WHERE id_periodo_contable='".$sesion_id_periodo_contable."'  and `tipo_mov`='F' and `numero_cpra_vta`='".$numero_factura."' and `tipo_comprobante`='Diario' AND descripcion= '".$descripcion."' ";
                          $resultBuscarId2=mysql_query($sqlBuscarId2);
                    while($rowBI2=mysql_fetch_array($resultBuscarId2))
                        {
                            $id_libro_diario= $rowBI2['id_libro_diario'];
                            $numero_asiento = $rowBI2['numero_asiento'];
                            if($id_libro_diario!=''){
                                
                          
                   $sqlBorrarDetalleLibroDiario2= "DELETE FROM `detalle_libro_diario` WHERE  `id_libro_diario`='".$id_libro_diario."'  ";
                  $respBorrarDetalleLibroDiario2 = mysql_query($sqlBorrarDetalleLibroDiario2) ;
                            }
                        }
                        $sqlLD2 = " UPDATE `libro_diario` SET `total_debe`='".$total_debe."',`total_haber`='".$total_haber."'  WHERE id_libro_diario  =$id_libro_diario";

                                $respLD2 = mysql_query($sqlLD2) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    
          try {
                  $sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
                  WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
                  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                  $resultCosto=mysql_query($sqlCosto);
                  $idcodigo_v=0;
                  while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
                  {
                      $idcod_costo=$row['codigo_plan_cuentas'];
                  }
                  $idcod_costo;

          }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
      //	echo 	$idcod_costo;	
                  
  //		$idcod_costo     ="51001001";
  
      try {
                  $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas 
                  WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable
                  and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
                  $resultInventario=mysql_query($sqlInventario);
                  $idcodigo_v=0;
                  while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
                  {
                      $idcod_inventario=$row['codigo_plan_cuentas'];
                  }
                  $idcod_inventario;

          }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
      
      //	echo "</br>".	$idcod_inventario;	
         
//			$idcod_inventario="115001001";

                                          
                      $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
                          ( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
                  //echo $sql;
                      $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
                      $plan_id_cuenta_costo=0;
                      $plan_id_cuenta_inventario=0;
                      while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
                      {
                          if ($rowS['plan_cuentas_codigo']==$idcod_costo)
                          {
                              $plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
                  
                          }
                          if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
                          {
                              $plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
                          }
                      }
                  

                  
                      $lin_diario=0;
                      if ($tot_costo>0)
                      {
                          $lin_diario=$lin_diario+1;
                          $idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
                          $debeVector[$lin_diario]=$tot_costo;
                          $haberVector[$lin_diario]=0;	
//echo $idPlanCuentas[$lin_diario];
                          $lin_diario=$lin_diario+1;
                          $idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
                          $debeVector[$lin_diario]=0;
                          $haberVector[$lin_diario]=$tot_costo;
//echo $idPlanCuentas[$lin_diario];										
                      }
          
               //echo "numero de cuentas";
               //echo $lin_diario;
                      for($i=1; $i<=$lin_diario; $i++)
                      {
                          //echo "entro a costo";
                      
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
                              $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 
                              id_plan_cuenta,debe, haber, id_periodo_contable) values 
                              ('".$id_libro_diario."',
                              '".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
                              '".$sesion_id_periodo_contable."');";
          
          
                              $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error detalle libro diario: '.mysql_error().' </p></div>  ');
                              $id_detalle_libro_diario=mysql_insert_id();
                              
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

                                      $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                                      $result6=mysql_query($sql6);
                                      $id_mayorizacion=mysql_insert_id();
                                  }
                                  catch(Exception $ex) 
                                  { ?> <div class="transparent_ajax_error">
                                      <p>Error en la insercion de la tabla mayorizacion: 
                                      <?php echo "".$ex ?></p></div> <?php }
                              // FIN DE MAYORIZACION
                              }  
                          }
                      }						
                  }
            //   } //aqui3

              date_default_timezone_set('America/Guayaquil');
              $fecha = date('Y-m-d H:i:s');
              $sql="INSERT INTO `bitacora`( `id_usuario`, `descripcion`, `fecha_accion`, `id_empresa`, `modulo`, `registro`) VALUES ('$sesion_id_usuario','U','$fecha','$sesion_id_empresa','Ventas', '".$_POST['cmbTipoDocumentoFVC'].$cmbEst.$cmbEmi.$numero_factura."')";
              $result = mysql_query($sql);

             $doc_kardes ='Venta';
			   if( $_POST['cmbTipoDocumentoFVC'] ==100){
			       $doc_kardes ='Nota de venta';
			   }
			   
               $sqlk="UPDATE kardes SET fecha='".$fecha_venta."' WHERE id_factura='".$id_venta."' AND detalle='".$doc_kardes."';";
    $resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());

    if($resultk && $respD && $respLD2){
        echo 'kardexSI';
    }else{
        echo 'error';
    }
                
      
                  
              /*  GUARDAR EN CUENTAS POR COBRAR */
              if ($total>0 and $txtCuotasTP>0)
              {
                  $txtContadorFilas=8;
                  
                  for($i=1; $i<=$txtContadorFilas; $i++)				
                  {
                      if ($_POST['txtTipo1'.$i]=='4')
                      {
                          $txtFechaTP=$_POST['txtFechaS'.$i];
                          $total=$_POST['txtValorS'.$i];
                          $txtCuotasTP=$_POST['txtCuotaS'.$i];
                          $formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
                      //	echo "fecha ===".$txtFechaTP;
                      }
                  }
              }

              if($total > 0 and $txtCuotasTP>0)
              {            
                  $cuotas = round(($total / $txtCuotasTP),2); 
                  //	$cuotas= round($cuotas_x * 100) / 100;
                      $aux=round(($cuotas * $txtCuotasTP),2); 
                      $dif=round(($total-$aux),2);
                      $cuota_final=$cuotas;
                      //echo $dif;
                  if ($dif != 0)
                  {
                          $cuota_final=$cuota_final + $dif;
                  }
      //	echo "cuotas";
  //	echo "fecha de Inicio";
  //	echo $txtFechaTP;			
                  $estadoCC = "Pendientes";                
                  for($i=1; $i<=$txtCuotasTP; $i++)
                  {
                      if ($i == $txtCuotasTP)
                      {
                          $cuotax=$cuota_final;
                      }
                      else
                      {
                          $cuotax=$cuotas;
                      } 
                      //	echo $cuotax;
                          //$date = date("d-m-Y");
                          //Incrementando meses
                          
                      $mod_date = strtotime($txtFechaTP."+ ".$i." months");
                  //	echo "fecha mod".$mod_date;
                      $fecha_nueva = date("Y-m-d",$mod_date);
                          
                      $sqlm2="Select max(id_cuenta_por_cobrar) From cuentas_por_cobrar;";
                      $resultm2=mysql_query($sqlm2) or die('<div class="alert alert-danger"><p>Error: '.mysql_error().' </p></div>  ');
                      $id_cuenta_por_cobrar=0;
                      while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
                      {
                              $id_cuenta_por_cobrar=$rowm2['max(id_cuenta_por_cobrar)'];
                      }
                          $id_cuenta_por_cobrar++;
            
                     $sql3 = "insert into cuentas_por_cobrar ( tipo_documento,         numero_factura,         referencia,        valor,          saldo,        numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor,id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
                              . "values                      ('".$cmbTipoDocumentoFVC."','".$numero_factura."','".$txtNombreFVC."' ,'".$cuotax."','".$cuotax."',          '',             '".$fecha_nueva."', null, null, '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";
              //		echo $sql3;							
                          $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
                      $id_cuenta_por_cobrar=mysql_insert_id();
                  }                
              
              
              }    

              }
              else
              {
              if($txtCambioFP>0.0 )
              {
              ?> <div class='transparent_ajax_error'><p>Existe un saldo pendiente de cancelar <?php echo " ".mysql_error(); ?>;</p></div> <?php
                  
              }
              else
              {
              ?> <div class='transparent_ajax_error'><p>Error: Valor a cobrar incorrecto <?php echo " ".mysql_error(); ?>;</p></div> <?php
                  
              }
          }
  
      }

      }catch (Exception $e) 
      {
          // Error en algun momento.
             ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
      }
      
}

	


if($accion == "21")
{
	// FACTURACION FISICA 
  $modoFacturacion=$_POST['modo']; 
  
   $sqlFactuFisica="SELECT establecimientos.id_empresa,id_est,emision.codigo,establecimientos.codigo,emision.id as emision_id ,emision.tipoFacturacion,emision.tipoEmision from emision,establecimientos where establecimientos.id_empresa=$sesion_id_empresa and emision.id_est=establecimientos.id AND emision.tipoEmision = 'F' LIMIT 1";
	$resultFactuFisica  = mysql_query($sqlFactuFisica);
	while ($rowFF= mysql_fetch_array($resultFactuFisica)){
		$cmbEst = $rowFF['id_est'];
		$cmbEmi = $rowFF['emision_id'];
		$sesion_punto = $rowFF['emision_id'];
	}


  // GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
  try 
  {
	  
		  $sqlEmpresa="SELECT limiteFacturas from  `empresa`  WHERE id_empresa='$sesion_id_empresa' ";
		  $resultEmpresa=mysql_query($sqlEmpresa) or die();
		  while($rowLimite=mysql_fetch_array($resultEmpresa))//permite ir de fila en fila de la tabla
		  {
			  $limite=$rowLimite['limiteFacturas'];
		  }
		  
			  
		  if($limite>=0){    
			  $limite3 = '1';	
		  }else {	  
			  $limite3 = '2';	
		  }

		  $cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
		 
		
		   $sql = "SELECT numero_factura_venta from ventas where id_empresa='".$sesion_id_empresa."'
		  and codigo_pun ='".$cmbEst."' and codigo_lug='".$cmbEmi."' and tipo_documento='".$cmbTipoDocumentoFVC."' ORDER BY numero_factura_venta DESC LIMIT 1 ;";
		  $resp = mysql_query($sql);
	 
		
		  while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
		  {
			$numero_factura=$row["numero_factura_venta"];
		  }
		   $numero_factura++;
		  
  }
  catch(Exception $ex) 
  {
	  ?> <div class="alert alert-warning"><p>Error al verificar la factura 
		  <?php echo "".$ex ?></p></div> 
	  <?php 
  }
  
  
  if ( $limite3==1)
  {
	  try
	  {
		  
		  
		  $cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
		  
		
		  $fecha_venta= $_POST['textFechaFVC'];		
		  $id_cliente=$_POST['textIdClienteFVC'];
		  $estado = "Activo";
		  
// 			$cmbIdVendedor=$_POST['cmbIdVendedorFVC'];
		  $cmbIdVendedor=$_POST['chofer_id'];
		  
		  
		//   $cmbEst=$_POST['cmbEst'];
		//   $cmbEmi=$_POST['cmbEmi'];
		  

		  $txtNombreFVC=$_POST['txtNombreFVC'];
		  $idFormaPago=0;
		  $txtCuotasTP=0;
		  $total=$_POST['txtTotalFVC'];
		  $sub_total=$_POST['txtSubtotalFVC'];
		  $sub_total0		= isset($_POST['txtSubtotal0FVC'])? $_POST['txtSubtotal0FVC']: 0;
    			$sub_total12	= isset($_POST['txtSubtotal12FVC'])? $_POST['txtSubtotal12FVC'] :0;
		  $descuento=$_POST['txtDescuentoFVCNum'];
		  $propina=$_POST['txtPropinaFVC'];
	  
		  $txtIva=$_POST['txtIva1'];
		  $facAn=$_POST['facAn'];
		  
		  $motivo =$_POST['MotivoNota'] ;
		  $txtTotalIvaFVC=$_POST['txtTotalIvaFVC'];
		  $txtDescripcion=$_POST['txtDescripcionFVC'];
		  $txtContadorFilas=$_POST['txtContadorFilasFVC'];

		  $txtCambioFP=$_POST['txtCambioFP'];
		  $txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
		  
		  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
		  {            
			  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
			  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
			  <p>Error: '.mysql_error().' </p></div>  ');;
			  $iva=0;
			  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
			  {
				  $iva=$rowIva1['iva'];
				  $txtIdIva=$rowIva1['id_iva'];
				  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
			  }
			  
			  // 	echo "txtIdIva ==>".$txtIdIva;
		  }
		  
		  
		  
		  if ($cmbTipoDocumentoFVC==1 || $cmbTipoDocumentoFVC==100)
		  
		  {
			  if($id_cliente!="" && $numero_factura !=""  )
			  {       
				  
			  
				  $sql="insert into ventas (fecha_venta,       estado,        total, sub_total,sub0,sub12,descuento,propina,numero_factura_venta, fecha_anulacion, descripcion, id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario,total_iva) 
				  values (                '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$txtTotalIvaFVC."');";

				  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
				  $id_venta=mysql_insert_id();

			  
				  
  
				  
				   
 if($_POST['idtipocliente'] =='08'){
                     $iva_exportador=0;
                     $sql_iva_exportador= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='". $sesion_id_empresa . "' AND iva=0 ";
                    $result_iva_exportador = mysql_query( $sql_iva_exportador );
                    while($row_iva_exportador = mysql_fetch_array($result_iva_exportador) ){
                        $iva_exportador= $row_iva_exportador['id_iva'];
                    }
                 }
			   
				  for($i=1; $i<=$txtContadorFilas; $i++)	
				  {
				  if($_POST['txtIdServicio'.$i] >= 1)
				  { //verifica si en el campo esta agregada una cuenta
		  
						  $cantidad = $_POST['txtCantidadS'.$i];
						  $idServicio = $_POST['txtIdServicio'.$i];
						  $idKardex = $_POST['txtIdServicio'.$i];
			  // 			echo "==>".$valorUnitario."</br>";
			  // 			$valorUnitario = $_POST['txtValorUnitarioS'.$i];
						  $valorUnitario = $_POST['txtValorUnitarioShidden'.$i];
						  
						  $valorTotal = $_POST['txtValorTotalS'.$i];
						  $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
						  $txtTipo11 = $_POST['txtTipo'.$i];
						  
						  $id_tipoP = $_POST['txtTipoS'.$i];
						  $cuenta = $_POST['cuenta'.$i];
						  $idBod = $_POST['idbod'.$i];
						  $idvalorPago=$_POST['txtValorS'.$i];
//							$txtdesc=$_POST['txtdesc'.$i];
//  echo "==>".$_POST['txtdescant'.$i]."</br>";
						  $txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];
						  $txtdetalle2=$_POST['txtdetalle2'.$i];
						   $bodegaCantidad=$_POST['bodegaCantidad'.$i];
			  // 			echo "BODEGA==>".$bodegaCantidad."</br>";
						  if($bodegaCantidad!=''){
							  $bodegaCantidad=$_POST['bodegaCantidad'.$i];
						  }else{
							  $bodegaCantidad='0';
						  }
						  $txtCodigoServicio=$_POST['txtCodigoServicio'.$i];
						  
					  
					  
			  // 			if ($id_tipoP == "P"  or $id_tipoP == "Inventario" or $id_tipoP == "I" or $id_tipoP == "1" ){
			  // 				$sql3="update productos set stock=stock-'".$cantidad."' where id_empresa='".$sesion_id_empresa."' and id_producto='".$idServicio."';";
			  // 				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender '.mysql_error().' </p></div>  ');
			  // 			}
						  
						  $txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
						  if ($id_tipoP == "2" && $txtDescripcionS!='') {
							  $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
							  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
							  // echo "UPDATE PRODUCTO ==>".$sql3;
						  }
						  
			        $id_iva = $_POST['IVA120'.$i];
								$sql_iva= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='" . $sesion_id_empresa . "' AND id_iva='" . $id_iva . "' ";
								$result_iva = mysql_query( $sql_iva );
								while($row_iva = mysql_fetch_array($result_iva) ){
									$tarifa_iva= $row_iva['iva'];
								}
								

								$total_iva= floatval($valorTotal) * (floatval( $tarifa_iva )/100);   

    			if($_POST['idtipocliente'] =='08'){
    			                    $id_iva=(trim($iva_exportador)=='')?0:$iva_exportador;
                                     $total_iva=0;
                                 }					  
						  //GUARDA EN EL DETALLE VENTAS
			   $sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa, `tarifa_iva`, `total_iva`) 
						  values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."','".$id_iva."', '".$total_iva."' );";
						  $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
						  <p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
						  $id_detalle_venta=mysql_insert_id();
	  
				  if ($id_tipoP == "1") {
							  if($bodegaCantidad!=''){
									  
							  $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$bodegaCantidad."' and 
								  idProducto='".$txtCodigoServicio."' ";
								  $resultado = mysql_query($stockBodegas);
								  while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
									  {
										  $id=$rowBodegas1['id'];
										  $cantidadBodega=$rowBodegas1['cantidad'];
									  }
								  $cantidadBodega = $cantidadBodega-$cantidad;
								//  $sqlbodegas="UPDATE `cantBodegas` SET `cantidad`='".$cantidadBodega."' WHERE idProducto='".$txtCodigoServicio."' and id='".$id."'";
							   $sqlbodegas="UPDATE `cantBodegas` SET `cantidad`='".$cantidadBodega."' WHERE id='".$id."'";
								  $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
  
							  }
					  }
				  }
				  
				  
				  
				  
				  
			  }
	  
	  
				  if($limite==0){
					  $limite=0;
					  
				  }else if($limite==1){
					  $limite=$limite-2;
					  
				  }else{
					  
					  $limite=$limite-1;
				  }
		if($_POST['cmbTipoDocumentoFVC']=='1' or $_POST['cmbTipoDocumentoFVC']=='41'){
		    $sqlNumFac ="update emision set numFac='".$numero_factura."' where id ='".$cmbEmi."' ";
				  $result=mysql_query($sqlNumFac) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().'</p></div>  ');
		}		  
					  
				  
				  $sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
				  $resultEmpresa2=mysql_query($sqlEmpresa2) or die();
				  
			  

			  // Crear el asiento		

			 // if ($sesion_tipo_empresa=="6")
			 // { //aqui4
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


				  try
				  {
					  $sqlm="Select max(id_libro_diario) From libro_diario";
					  $resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error : '.mysql_error().' </p></div>  ');
					  $id_libro_diario=0;
					  while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
					  {
						  $id_libro_diario=$rowm['max(id_libro_diario)'];
					  }
					  $id_libro_diario++;

				  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
			  
				  //Fin permite sacar el id maximo de libro_diario
	  
	  
	  $tipo_comprobante = "Diario"; 
			  
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
				  $descripcion = "Factura de venta #".$numero_factura." realizada a ".$txtNombreFVC;
				   if( $cmbTipoDocumentoFVC==100 ){
				       $descripcion = "Nota de venta #".$numero_factura." realizada a ".$txtNombreFVC;
				   }
				  $debe = $total;
				  $debe2 = $descuento;
				  $total_debe = $debe + $debe2;
				  
				  $haber1 = $sub_total;
				  $haber2 = $_POST['txtTotalIvaFVC'];
				  
				  $total_haber = $haber1 + $haber2 + $propina;
				  
				  $tipo_mov="F";
  
			  //GUARDA EN  COMPROBANTES
				  $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
				  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
				  $id_comprobante=mysql_insert_id();
			  //GUARDA EN EL LIBRO DIARIO
				  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
				  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
				  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
				  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
				  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
				  $id_libro_diario=mysql_insert_id();
			  
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
				  
				  
				  


				  $valor[$lin_diario]=0;
				  $ident=0;
				  
				  for($i=1; $i<=$txtContadorFilas; $i++)				
				  {
					  if($_POST['txtCodigoS'.$i] >=1)
					  {	
						  
						  
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$_POST['txtCodigoS'.$i];
						  $debeVector[$lin_diario]=$_POST['txtValorS'.$i];
						  $haberVector[$lin_diario]=0; 
						  
						  $formaPagoId[$lin_diario]=$_POST['formaPagoId'.$i];
						  
						  $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) 
						  VALUES     ('".$formaPagoId[$i]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$i]."','".$_POST['txtTipo1'.$i]."', NULL );";
						  // echo $sqlforma;
						  $respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
						  if($respForma){
							  if($_POST['txtTipo1'.$i]==1 ){
							   $identificador="01";
							  }
							  else if($_POST['txtTipo1'.$i]==2){
								$ident=1;
								  $identificador="02";
							  }else if($_POST['txtTipo1'.$i]==16 && $_POST['txtTipo1'.$i]==17 ){
							  //   $ident=1;
								  $identificador="19";
							  }
							  else{
								  $identificador="03";
							  }
								 if($ident==1){
									 $identificador="02";
								 } 
								  $sql3="update ventas set id_forma_pago='".$identificador."' where id_venta='".$id_venta."' ";
								  $resp3 = mysql_query($sql3) or die(mysql_error());
								  
							  
						  }
						  
						  
					  }
					  
					  if ($_POST['txtTipo1'.$i]=='4')
					  {
						  $total=$_POST['txtValorS'.$i];
						  $txtCuotasTP=$_POST['txtCuotaS'.$i];
						  $formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];		
					  }
					  
					  if ($_POST['txtTipo1'.$i]=='13')
					  {
						  $estadoCC="Pendientes";
						  
						  $total_x_pagar=$_POST['txtValorS'.$i];
						  $formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
						  $cuentaxpagar="SI";
						  $sqlm2="Select max(id_cuenta_por_pagar) From cuentas_por_pagar;";
						  $resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  $id_cuenta_por_pagar=0;
						  while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
						  {
							  $id_cuenta_por_pagar=$rowm2['max(id_cuenta_por_pagar)'];
						  }
						  $id_cuenta_por_pagar++;
						  $cmbTipoDocumentoFVC="Factura No.";
						   if( $_POST['cmbTipoDocumentoFVC'] == 100 ){
    							$cmbTipoDocumentoFVC="Nota de venta No.";
    					 }
						  $sql3 = "insert into cuentas_por_pagar (tipo_documento, numero_compra, referencia,  
						  valor, saldo,numero_asiento,fecha_vencimiento,  id_proveedor,id_cliente ,id_plan_cuenta, id_empresa,
						  id_compra, estado) " . "values 
						  ('".$cmbTipoDocumentoFVC."','".$numero_factura."','".
						  $txtNombreFVC.", ".$cmbTipoDocumentoFVC.",".$numero_factura."', '".
						  $total_x_pagar."','".$total_x_pagar."','','".$fecha_venta."',null,'".$id_cliente."','".
						  $formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."',
						  '".$id_venta."', '".$estadoCC."');";

						  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
						  $id_cuenta_por_pagar=mysql_insert_id();
					  }
					  
					  if ($_POST['txtTipo1'.$i]=='17')
					  {
						  
						  //echo "TRANSFERENCIA</br>";
										//permite sacar el id maximo de bancos
							  try{
								  $sqlmaxb="Select max(id_bancos) From bancos;";
								  $resultmaxb=mysql_query($sqlmaxb);
								  $id_bancos=0;
								  while($rowmaxb=mysql_fetch_array($resultmaxb))//permite ir de fila en fila de la tabla
								  {
									  $id_bancos=$rowmaxb['max(id_bancos)'];
								  }
								  $id_bancos++;
	  
							  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
	  
							  try {
								  //permite sacar el id maximo de la tabla detalle_bancos
								  $sqlmaxdb="Select max(id_detalle_banco) From detalle_bancos;";
								  $resultmaxdb=mysql_query($sqlmaxdb);
								  $id_detalle_banco=0;
								  while($rowmaxdb=mysql_fetch_array($resultmaxdb))//permite ir de fila en fila de la tabla
								  {
									  $id_detalle_banco=$rowmaxdb['max(id_detalle_banco)'];
								  }
								  $id_detalle_banco++;
	  
							  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
								  
								  
								  
								  $cmbTipoDocumento='Transferencia';
								  $txtNumeroDocumento=$_POST['txtNumDocumento'];
								  $txtDetalleDocumento="Transferencia de ".$txtNombreFVC ;
								  $txtFechaEmision=$fecha_venta;
								  $txtFechaVencimiento=$fecha_venta;
								  $saldo_conciliado = 0;
								  $valorConciliacion = $_POST['txtValorS'.$i];
								  $estado = "No Conciliado";
								  
								  $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$_POST['txtCodigoS'.$i]."' 
								  AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
								  $resultb2=mysql_query($sqlb2);
								  while($rowb2=mysql_fetch_array($resultb2))//permite ir de fila en fila de la tabla
								  {
									  $id_bancos2=$rowb2['id_bancos'];
								  }    
								  
								  $numero_fil = mysql_num_rows($resultb2);
								  
								  if($numero_fil > 0){
									  
									  $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
									  values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
									  $resultDB=mysql_query($sqlDB);
									  $id_detalle_banco=mysql_insert_id();
									  echo "trans</br>".$sqlDB."</br>";
								  }else {
									  
									  $sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values 
									  ('".$_POST['txtCodigoS'.$i]."','".$saldo_conciliado."', '".$sesion_id_periodo_contable."');";
									  $resultB=mysql_query($sqlB);
									  $id_bancos=mysql_insert_id();
									  $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
									  values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
									  $resultDB=mysql_query($sqlDB);
									  $id_detalle_banco=mysql_insert_id();
									  echo "bancos</br>".$sqlB."</br>";
									  echo "detalle</br>".$sqlDB."</br>";
								  }

						  
						  
					  }
					  
				  }
				  
				  
			  
				  
				  $tot_ventas=0;
				  $tot_servicios=0;
				  $tot_costo=0;
				  $listaServicios=array();
				  for($i=1; $i<=$txtContadorFilas; $i++){
					  if($_POST['txtIdServicio'.$i] >= 1)
					  {
						  $idProducto=$_POST['txtIdServicio'.$i];
						  $id_tipoP = $_POST['txtTipoS'.$i];
						  $cuenta = $_POST['cuenta'.$i];
		  
						  if ($id_tipoP =="1"){
							  $tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
							  $costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
							  $tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
						  }
						  
						  if ($id_tipoP == "2" ){
						      $sqlPlanCuenta= "SELECT  
								productos.`id_producto` AS productos_id_producto, 
							 productos.`producto` AS productos_nombre,
							 productos.`grupo` AS productos_grupo, 
							 centro_costo.`id_centro_costo` AS centro_id, 
							 centro_costo.`descripcion` AS centro_descripcion, 
							 centro_costo.`id_cuenta` AS productos_id_cuenta,
							 plan_cuentas.codigo,
      						 plan_cuentas.nombre
							  
							 FROM  `productos` 
							 
							 INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
							 INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = centro_costo.id_cuenta
						 
							 
							 WHERE productos.id_producto=$idProducto ";
							 $resultPlanCuentas= mysql_query($sqlPlanCuenta);
							 $planCuentaServicio = 0;
							 while($rowPC = mysql_fetch_array($resultPlanCuentas)){
								$planCuentaServicio = $rowPC['productos_id_cuenta'];
							 }
							 if(array_key_exists($planCuentaServicio,$listaServicios)){
                                                $listaServicios[$planCuentaServicio]=$listaServicios[$planCuentaServicio]+ $_POST['txtValorTotalS'.$i]; 
                                              }else{
                                                   $listaServicios[$planCuentaServicio]=$_POST['txtValorTotalS'.$i]; 
                                              }
							  $tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
						  }
						  
						  // $encontrado="NO";
						  
						  // if ($lin_diario>0) {
						  // 	for ($k=1;$k<=$lin_diario;$k++){
						  // 		if ($idPlanCuentas[$k] == $_POST['cuenta'.$i]) 
						  // 		{
						  // 			$valor[$k]  = $valor[$k]+$_POST['txtValorTotalS'.$i];
						  // 			$debeVector[$k] =0;
						  // 			$haberVector[$k] = $valor[$k] ;
						  // 			$encontrado="SI";
		  
			  // 					}
						  // 	}
						  // }
						  // if ($lin_diario==0 or $encontrado=="NO") {
						  // 	$lin_diario=$lin_diario+1;
						  // 	$idPlanCuentas[$lin_diario] = $_POST['cuenta'.$i];
						  // 	$valor[$lin_diario]         = $_POST['txtValorTotalS'.$i];
						  // 	$debeVector[$lin_diario]    = 0 ;
						  // 	$haberVector[$lin_diario] = $valor[$k] ;
						  // }
						  
							  
					  }
				  }
				  
				  
					  
				  
			  try 
			  {
				  $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
				  WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
				  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
				  $resultMercaderia=mysql_query($sqlMercaderia);
				  $idcodigo_v=0;
				  while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
				  {
					  $idcodigo_v=$row['codigo_plan_cuentas'];
				  }
				  $idcodigo_v;
			  }
			  catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
			  
			  try 
			  {
				  $sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
				  WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
				  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
				  $resultServicio=mysql_query($sqlServicio);
				  $idcodigo_s=0;
				  while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
				  {
					  $idcodigo_s=$row['codigo_plan_cuentas'];
				  }
				  $idcodigo_s;

			  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				

					  
				  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
						from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
						  ( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
  
				  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
				  $plan_id_cuenta_vta=0;
				  $plan_id_cuenta_servicio=0;
				  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
				  {
					  if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
						  {
							  $plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
						  }
					  if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
						  {
							  $plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
						  }

						  
				  }
				  
				  
				
				  if ($tot_ventas!=0)
				  {
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
					  $debeVector[$lin_diario]=0;
					  $haberVector[$lin_diario]=$tot_ventas;
					  

				  }
				  
				  if ($tot_servicios!=0)
				  {
				      foreach ($listaServicios as $key => $value) {
                        $lin_diario=$lin_diario+1;
                                   $idPlanCuentas[$lin_diario]= $key;
                                   $debeVector[$lin_diario]=0;
                                   $haberVector[$lin_diario]=$value;
                        }
				  }
				  
				  
				  try {
						  $sqlDescuento="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago WHERE `id_tipo_movimiento` =14 
						  and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
						  $resultDescuento=mysql_query($sqlDescuento);
						  $idcodigo_d=0;
						  while($row=mysql_fetch_array($resultDescuento))//permite ir de fila en fila de la tabla
						  {
							  $idcodigo_d=$row['codigo_plan_cuentas'];
						  }
						  $idcodigo_d;
	  
				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
				  
				  
				  
				  if ($descuento!=0)
				  {
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]= $idcodigo_d;
					  $debeVector[$lin_diario]=$descuento;
					  $haberVector[$lin_diario]=0;
				  }
				  
				  
				  try {
						  $sqlPropina="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago 
						  WHERE `id_tipo_movimiento` =15 and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
						  $resultPropina=mysql_query($sqlPropina);
						  $idcodigo_p=0;
						  while($row=mysql_fetch_array($resultPropina))//permite ir de fila en fila de la tabla
						  {
							  $idcodigo_p=$row['codigo_plan_cuentas'];
						  }
						  $idcodigo_p;
	  
				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
				  
				  
				  if ($propina!=0)
				  {
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]= $idcodigo_p;
					  $debeVector[$lin_diario]=0;
					  $haberVector[$lin_diario]=$propina;
				  }
				  
				  if ($txtTotalIvaFVC!=0)
				  {

					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
					  $debeVector[$lin_diario]=0;
					  $haberVector[$lin_diario]=$txtTotalIvaFVC;

				  }
				  
				  
				  
				  
				  for($i=1; $i<=$lin_diario; $i++)
				  {
					  

					  if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
					  {
					  //permite sacar el id maximo de detalle_libro_diario

					  //GUARDA EN EL DETALLE LIBRO DIARIO
						  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
						  ('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
				  
						  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  $id_detalle_libro_diario=mysql_insert_id();		
			  // 			echo "DETALLE==".$sqlDLD."</br>";
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

								  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
								  $result6=mysql_query($sql6);
								  $id_mayorizacion=mysql_insert_id();
							  }
							  catch(Exception $ex) 
							  { ?> <div class="transparent_ajax_error">
								  <p>Error en la insercion de la tabla mayorizacion: 
								  <?php echo "".$ex ?></p></div> <?php }
							  // FIN DE MAYORIZACION
						  }
					  }
				  }
				  
				  
				  
//ASIENTO DE COSTO
			  //	echo "	va a crear CostoS=".$tot_costo;
				  if ($tot_costo>0)
				  {
				  try
					  {
						  $numero_asiento++;
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
					  $descripcion="Asiento de costo de venta de la Factura No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
					  
					   if(  $_POST['cmbTipoDocumentoFVC'] == 100 ){
    							$descripcion="Asiento de costo de venta de la Nota de venta No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
    					 }
					  //$debe = $total_costo;
					  //$haber1 = $sub_total;
				  //  $haber2 = $_POST['txtTotalIvaFVC'];
					  $total_debe  = $tot_costo;
					  $total_haber = $tot_costo;
				  
	  /* 			echo "debe=";
				  echo $total_debe;
				  echo "haber=";
				  echo $total_haber;
	   */			
					  $tipo_mov="F";
  
			  //GUARDA EN  COMPROBANTES
					  $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
				  
			  //GUARDA EN EL LIBRO DIARIO
					  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
					  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
					  '".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";

					  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
					  $id_libro_diario=mysql_insert_id();
		  try {
				  $sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
				  WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
				  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
				  $resultCosto=mysql_query($sqlCosto);
				  $idcodigo_v=0;
				  while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
				  {
					  $idcod_costo=$row['codigo_plan_cuentas'];
				  }
				  $idcod_costo;

		  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
	  //	echo 	$idcod_costo;	
				  
  //		$idcod_costo     ="51001001";
  
	  try {
				  $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas 
				  WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable
				  and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
				  $resultInventario=mysql_query($sqlInventario);
				  $idcodigo_v=0;
				  while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
				  {
					  $idcod_inventario=$row['codigo_plan_cuentas'];
				  }
				  $idcod_inventario;

		  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
	  
	  //	echo "</br>".	$idcod_inventario;	
		 
//			$idcod_inventario="115001001";

										  
					  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
						  ( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
				  //echo $sql;
					  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
					  $plan_id_cuenta_costo=0;
					  $plan_id_cuenta_inventario=0;
					  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
					  {
						  if ($rowS['plan_cuentas_codigo']==$idcod_costo)
						  {
							  $plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
				  
						  }
						  if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
						  {
							  $plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
						  }
					  }
				  

				  
					  $lin_diario=0;
					  if ($tot_costo>0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
						  $debeVector[$lin_diario]=$tot_costo;
						  $haberVector[$lin_diario]=0;	
//echo $idPlanCuentas[$lin_diario];
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$tot_costo;
//echo $idPlanCuentas[$lin_diario];										
					  }
		  
			   //echo "numero de cuentas";
			   //echo $lin_diario;
					  for($i=1; $i<=$lin_diario; $i++)
					  {
						  //echo "entro a costo";
					  
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
							  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 
							  id_plan_cuenta,debe, haber, id_periodo_contable) values 
							  ('".$id_libro_diario."',
							  '".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
							  '".$sesion_id_periodo_contable."');";
		  
		  
							  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							  $id_detalle_libro_diario=mysql_insert_id();
							  
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

									  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
									  $result6=mysql_query($sql6);
									  $id_mayorizacion=mysql_insert_id();
								  }
								  catch(Exception $ex) 
								  { ?> <div class="transparent_ajax_error">
									  <p>Error en la insercion de la tabla mayorizacion: 
									  <?php echo "".$ex ?></p></div> <?php }
							  // FIN DE MAYORIZACION
							  }  
						  }
					  }						
				  }
			 // } // aqui4

		  
			// GUARDAR EN KARDEX

			  $sqlki="Select max(id_kardes) From kardes";
			  $resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
			  $id_kardes='0';
			  while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
			  {
				  $id_kardes=$rowki['max(id_kardes)'];
			  }
			  $id_kardes++;
			  $doc_kardes ='Venta';
			  if( $_POST['cmbTipoDocumentoFVC'] ==100){
			       $doc_kardes ='Nota de venta';
			  }
			   if( $_POST['cmbTipoDocumentoFVC'] !=100){
			      
            				        genXml($id_venta);
            				    }
			  $sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
			  ('".$fecha_venta."','".$doc_kardes."','".$id_venta."', '".$sesion_id_empresa."')";
			  $resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
			  $id_kardes=mysql_insert_id();
			  if($result && $resp2)
			  {
				  echo "kardex";
			  }
			  else{
					  echo "kardex2";
				  }
				  
	  
				  
			  /*  GUARDAR EN CUENTAS POR COBRAR */
			  if ($total>0 and $txtCuotasTP>0)
			  {
				  $txtContadorFilas=8;
				  
				  for($i=1; $i<=$txtContadorFilas; $i++)				
				  {
					  if ($_POST['txtTipo1'.$i]=='4')
					  {
						  $txtFechaTP=$_POST['txtFechaS'.$i];
						  $total=$_POST['txtValorS'.$i];
						  $txtCuotasTP=$_POST['txtCuotaS'.$i];
						  $formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
					  //	echo "fecha ===".$txtFechaTP;
					  }
				  }
			  }

			  if($total > 0 and $txtCuotasTP>0)
			  {            
				  $cuotas = round(($total / $txtCuotasTP),2); 
				  //	$cuotas= round($cuotas_x * 100) / 100;
					  $aux=round(($cuotas * $txtCuotasTP),2); 
					  $dif=round(($total-$aux),2);
					  $cuota_final=$cuotas;
					  //echo $dif;
				  if ($dif != 0)
				  {
						  $cuota_final=$cuota_final + $dif;
				  }
	  //	echo "cuotas";
  //	echo "fecha de Inicio";
  //	echo $txtFechaTP;			
				  $estadoCC = "Pendientes";                
				  for($i=1; $i<=$txtCuotasTP; $i++)
				  {
					  if ($i == $txtCuotasTP)
					  {
						  $cuotax=$cuota_final;
					  }
					  else
					  {
						  $cuotax=$cuotas;
					  } 
					  //	echo $cuotax;
						  //$date = date("d-m-Y");
						  //Incrementando meses
						  
					  $mod_date = strtotime($txtFechaTP."+ ".$i." months");
				  //	echo "fecha mod".$mod_date;
					  $fecha_nueva = date("Y-m-d",$mod_date);
						  
					  $sqlm2="Select max(id_cuenta_por_cobrar) From cuentas_por_cobrar;";
					  $resultm2=mysql_query($sqlm2) or die('<div class="alert alert-danger"><p>Error: '.mysql_error().' </p></div>  ');
					  $id_cuenta_por_cobrar=0;
					  while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
					  {
							  $id_cuenta_por_cobrar=$rowm2['max(id_cuenta_por_cobrar)'];
					  }
						  $id_cuenta_por_cobrar++;
						  //	echo "ide cta por cobrar";
						  //echo $id_cuenta_por_cobrar++;
						  //$sql3 = "insert into cuentas_por_cobrar (id_cuenta_por_cobrar, numero_factura, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
						  //	. "values ('".$id_cuenta_por_cobrar."','".$numero_factura."','".$txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura."','".$cuotax."','".$cuotax."','', '".$fecha_nueva."', '', '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";
					  $sql3 = "insert into cuentas_por_cobrar ( tipo_documento,         numero_factura,         referencia,        valor,          saldo,        numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor,id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
							  . "values                      ('".$cmbTipoDocumentoFVC."','".$numero_factura."','".$txtNombreFVC."' ,'".$cuotax."','".$cuotax."',          '',             '".$fecha_nueva."', null, null, '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";
			  //		echo $sql3;							
						  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
					  $id_cuenta_por_cobrar=mysql_insert_id();
				  }                
			  
			  
			  }    
			  
			  
		  
			  
			  if($modoFacturacion=='200'){
				  
				  $emision_tipoEmision='F';
				  
			  }else{
				  
				  $emision_tipoEmision = $_SESSION['emision_tipoEmision'];
			  }
			  
					  if ($emision_tipoEmision === 'E'){
					       if( $_POST['cmbTipoDocumentoFVC'] !=100){
            				        genXml($id_venta);
            				    }
						  
						  
										  $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
										  // echo $sqli;
										  $result=mysql_query($sqli);
										  
										  while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
										  {
											  $claveAcceso=$row['ClaveAcceso'];
										  }
										  if ($claveAcceso != ''){
											  echo "SI";
											  }else{
												  echo "NO";
											  }
						  
						  }
			  
			  
			  
			  }
			  else
			  {
			  if($txtCambioFP>0.0 )
			  {
			  ?> <div class='transparent_ajax_error'><p>Existe un saldo pendiente de cancelar <?php echo " ".mysql_error(); ?>;</p></div> <?php
				  
			  }
			  else
			  {
			  ?> <div class='transparent_ajax_error'><p>Error: Valor a cobrar incorrecto <?php echo " ".mysql_error(); ?>;</p></div> <?php
				  
			  }
		  }
					  
		  
		  
	  
		  
		  
		  
		  
	  }
	  
	  
	  
	  
	  
	  
		  if ($cmbTipoDocumentoFVC==4)
		  {


		  $txtIva=$_POST['txtTotalIvaFVC'];
					  
		  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
		  {            
			  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
			  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
			  <p>Error: '.mysql_error().' </p></div>  ');;
			  $iva=0;
			  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
			  {
				  $iva=$rowIva1['iva'];
				  $txtIdIva=$rowIva1['id_iva'];
				  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
			  }
			  
		  }
			  
			  // txtTotalIvaFVC		
					  
			  // $hoy = date("Y-m-d H:i:s"); 	
				  
			  $sql="insert into ventas (fecha_venta,      estado,       total,       sub_total,         sub0,               sub12,           descuento,        propina,      numero_factura_venta, fecha_anulacion, descripcion, id_iva, id_vendedor, id_cliente, id_empresa,	tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, RetIva,MotivoNota) 
			  values  (             '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."', '".$propina."',   '".$numero_factura."',    '0000-00-00 00:00:00'  ,'".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$facAn."','".$motivo."');";
  
			  
			  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en Nota de Credito: '.mysql_error().' </p></div>  ');
			  $id_venta=mysql_insert_id();
				  
			  for($i=1; $i<=$txtContadorFilas; $i++)
			  {
				  if($_POST['txtIdServicio'.$i] >= 1)
				  { 
					  
					  $cantidad = $_POST['txtCantidadS'.$i];
					  $idServicio = $_POST['txtIdServicio'.$i];
					  $idKardex = $_POST['txtIdServicio'.$i];
					  $valorUnitario = $_POST['txtValorUnitarioS'.$i];
					  $valorTotal = $_POST['txtValorTotalS'.$i];
					  $txtdetalle2=$_POST['txtdetalle2'.$i];
					  $id_tipoP = $_POST['txtTipoS'.$i];
					  //echo "tipo".$id_tipoP;
					  $idBod = $_POST['idbod'.$i];
					  $idvalorPago=$_POST['txtValorS'.$i];
					  $bodegaCantidad=$_POST['bodegaCantidad'.$i];
						  if($bodegaCantidad!=''){
							  $bodegaCantidad=$_POST['bodegaCantidad'.$i];
						  }else{
							  $bodegaCantidad='0';
						  }
						  
								$txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];

					  
												  //GUARDA EN EL DETALLE VENTAS
$sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa) 
values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";

$resp2 = mysql_query($sql2) or die('<div class="alert alert-danger"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
$id_detalle_venta=mysql_insert_id();
					  
					  

				  }
			  }		
		  
			  $tot_costo=0;
			  try	
			  {
				$sqlMNA="SELECT max(numero_asiento) AS max_numero_asiento,
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
			  // Creacion del diario de la nota de credito
			  try
			  {
				  $sqlm="Select max(id_libro_diario) From libro_diario";
					  $resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error : '.mysql_error().' </p></div>  ');
					  $id_libro_diario=0;
				  while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
				  {
					  $id_libro_diario=$rowm['max(id_libro_diario)'];
				  }
				  $id_libro_diario++;
			  }
			  catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
			  
			  $tipo_comprobante = "Diario"; 
			  try
			  {
				  $tipoComprobante = $tipo_comprobante;
				  $consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
						   FROM `comprobantes` comprobantes
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
			  $debe = $total;
			  $haber1 = $sub_total;
			  $haber2 = $_POST['txtTotalIvaFVC'];
			  $total_debe = $debe;
			  $total_haber = $haber1 + $haber2;
			  
			  $tipo_mov="D";
  
			  //GUARDA EN  COMPROBANTES
			  $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values 
			  ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
			  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
			  $id_comprobante=mysql_insert_id();
			  
			  //GUARDA EN EL LIBRO DIARIO
			  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
				  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
				  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."',
				  '".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
				  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
			  
			  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
			  $id_libro_diario=mysql_insert_id();
			  
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
									  
			  $tot_ventas=0;
			  $tot_servicios=0;
			  $tot_costo=0;
			  $txtContadorFilas=8;
				  
			  for($i=1; $i<=$txtContadorFilas; $i++)
			  {
				  if($_POST['txtIdServicio'.$i] >= 1)		
				  {										 //verifica si en el campo esta agregada una cuenta
					  $idProducto=$_POST['txtIdServicio'.$i];
					  $id_tipoP = $_POST['txtTipoS'.$i];
					  $sqlS = "SELECT productos.`id_producto` AS productos_id_servicio,
							  productos.`producto` AS productos_nombre, productos.`iva` AS productos_iva,
							  productos.`id_empresa` AS productos_id_empresa,	productos.`costo` AS productos_costo,                  
							  productos.`id_cuenta` AS productos_id_cuenta	
						  FROM
							  `productos` productos  Where productos.`id_empresa`='".$sesion_id_empresa."' and
								  productos.`id_producto` ='".$idProducto."'";
					  $resultS=mysql_query($sqlS) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;	
					  $productos_costo=0;								
					  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
					  {
						  $productos_id_cuenta=$rowS['productos_id_cuenta'];
						  //	$productos_costo=$rowS['productos_costo'];
					  }
				  //******************************    //GUARDA EN EL DETALLE LIBRO DIARIO      **********************
						  
					  if ($id_tipoP == "Inventario" or $id_tipoP="P" or $id_tipoP="I" or $id_tipoP="1" )
					  {
							  $tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
  //						//	$tot_costo=$tot_costo+($productos_costo * $_POST['txtCantidadS'.$i]);
						  //	$costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa,1);
							  $costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
							  $tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
					  }
					  else
					  {
						  $tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
					  }
				  }
			  }
			  
			  try 
			  {
				  $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
				  WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
				  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
				  $resultMercaderia=mysql_query($sqlMercaderia);
				  $idcodigo_v=0;
				  while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
				  {
					  $idcodigo_v=$row['codigo_plan_cuentas'];
				  }
				  $idcodigo_v;
			  }
			  catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
			  try 
			  {
				  $sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
				  WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
				  formas_pago.id_empresa='".$sesion_id_empresa."'  ";
				  $resultServicio=mysql_query($sqlServicio);
				  $idcodigo_s=0;
				  while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
				  {
					  $idcodigo_s=$row['codigo_plan_cuentas'];
				  }
				  $idcodigo_s;

			  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				

					  
			  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
					from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
					  ( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;

			  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
			  $plan_id_cuenta_vta=0;
			  $plan_id_cuenta_servicio=0;
			  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
			  {
				  if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
					  {
						  $plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
					  }
				  if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
					  {
						  $plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
					  }
			  }

			  $lin_diario=0;
			  // echo             "<==> total <==>".$total."<==>";
			  if ($tot_ventas!=0)
			  {
				  $lin_diario=$lin_diario+1;
				  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
				  $debeVector[$lin_diario]=$tot_ventas;
				  $haberVector[$lin_diario]=0;
			  }
			  if ($tot_servicios!=0)
			  {
				  $lin_diario=$lin_diario+1;
				  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_servicio;
				  $debeVector[$lin_diario]=$tot_servicios;
				  $haberVector[$lin_diario]=0;
			  }
			  //echo $impuestos_id_plan_cuenta;
			  $lin_diario=$lin_diario+1;
			  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
			  $debeVector[$lin_diario]=$txtTotalIvaFVC;
			  $haberVector[$lin_diario]=0;
			  
			  $sql = "SELECT id_plan_cuenta FROM formas_pago  
				  WHERE id_empresa = '" .$sesion_id_empresa. "' and id_tipo_movimiento=1"; 
			  //echo $sql;
			  $resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					  
			  while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
			  {
				  $lin_diario=$lin_diario+1;
				  $idPlanCuentas[$lin_diario]=$row['id_plan_cuenta'];
				  $debeVector[$lin_diario]=0;
				  $haberVector[$lin_diario]=$total;	
			  }
			  
// echo             "<==> valores <==>".$haberVector[$lin_diario]."<==>";
	  /* for($i=1; $i<=$lin_diario; $i++)
			  {
				  echo $idPlanCuentas[$i]."-";
				  echo $debeVector[$i];
				  echo $haberVector[$i];
				  echo "<br/>";

			  } */
		  
		  
			  for($i=1; $i<=$lin_diario; $i++)
			  {
				  if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
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
				  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
						  ('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
						  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  $id_detalle_libro_diario=mysql_insert_id();		
						  
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

							  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
							  $result6=mysql_query($sql6);
							  $id_mayorizacion=mysql_insert_id();
						  }
						  catch(Exception $ex) 
							  { ?> <div class="transparent_ajax_error">
								  <p>Error en la insercion de la tabla mayorizacion: 
								  <?php echo "".$ex ?></p></div> <?php }
							  // FIN DE MAYORIZACION
					  }
				  }
			  }
		  
							  
		  
//ASIENTO DE COSTO
			  //	echo "	va a crear CostoS=".$tot_costo;
			  if ($tot_costo>0)
			  {
				  try
				  {
						  $numero_asiento++;
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
						  $consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
						  FROM `comprobantes` comprobantes
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
				  }	
				  catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
							
			  //FIN SACA EL ID MAX DE COMPROBANTES
	  
				  $fecha= date("Y-m-d h:i:s");
					  //$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
				  $descripcion="Asiento de costo de nota de credito No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
				  $descripcion="Asiento de costo de nota de credito No.".$numero_documento." realizado por ".$sesion_empresa_nombre;
									  
				  $total_debe  = $tot_costo;
				  $total_haber = $tot_costo;
				  
	  /* 			echo "debe=";
				  echo $total_debe;
				  echo "haber=";
				  echo $total_haber;
	   */			
				  $tipo_mov="4";
  
			  //GUARDA EN  COMPROBANTES
				  $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
				  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
				  
			  //GUARDA EN EL LIBRO DIARIO
				  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
					  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
					  '".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";

				  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
				  $id_libro_diario=mysql_insert_id();
				  try 
				  {
					  $sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
					  WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
					  $resultCosto=mysql_query($sqlCosto);
					  $idcodigo_v=0;
					  while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
					  {
						  $idcod_costo=$row['codigo_plan_cuentas'];
					  }
					  $idcod_costo;

				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
	  //	echo 	$idcod_costo;	
				  
  //		$idcod_costo     ="51001001";
  
				  try 
				  {
					  $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas
					  WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
					  $resultInventario=mysql_query($sqlInventario);
					  $idcodigo_v=0;
					  while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
					  {
						  $idcod_inventario=$row['codigo_plan_cuentas'];
					  }
					  $idcod_inventario;
				  }
				  catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
	  
	  //	echo "</br>".	$idcod_inventario;	
		 
//			$idcod_inventario="115001001"
					  
				  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
						  ( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
				  //echo $sql;
					  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;					
					  $plan_id_cuenta_costo=0;
					  $plan_id_cuenta_inventario=0;
				  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
				  {
					  if ($rowS['plan_cuentas_codigo']==$idcod_costo)
					  {
						  $plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
					  }
					  if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
					  {
						  $plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
					  }
				  }
				  
				  $lin_diario=0;
				  if ($tot_costo>0)
				  {
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
					  $debeVector[$lin_diario]=$tot_costo;
					  $haberVector[$lin_diario]=0;
						  
					  $lin_diario=$lin_diario+1;
					  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
					  $debeVector[$lin_diario]=0;
					  $haberVector[$lin_diario]=$tot_costo;			
//echo $idPlanCuentas[$lin_diario];
//echo $idPlanCuentas[$lin_diario];										
				  }
	  
  /* 	for($i=1; $i<=$lin_diario; $i++)
			  {
				  echo $idPlanCuentas[$i]."-";
				  echo $debeVector[$i];
				  echo $haberVector[$i];
				  echo "<br/>";

			  }
   */	
	  
			   //echo "numero de cuentas";
			   //echo $lin_diario;
				  for($i=1; $i<=$lin_diario; $i++)
				  {
						  //echo "entro a costo";
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
						  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario,id_plan_cuenta,debe, haber,
								  id_periodo_contable) values 
							  ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$haberVector[$i]."','".$debeVector[$i]."',
							  '".$sesion_id_periodo_contable."');";
				  //	echo "<br>".$sqlDLD;
						  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  $id_detalle_libro_diario=mysql_insert_id();
							  
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

								  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
								  $result6=mysql_query($sql6);
								  $id_mayorizacion=mysql_insert_id();
							  }
								  catch(Exception $ex) 
								  { ?> <div class="transparent_ajax_error">
									  <p>Error en la insercion de la tabla mayorizacion: 
									  <?php echo "".$ex ?></p></div> <?php }
							  // FIN DE MAYORIZACION
						  }  
					  }
				  }						
			  }
		  // crear anticipo a clientes
			  if ($total>0)
			  {
				  try
				  {
					  $numero_asiento++;
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
						  $consulta7="SELECT max(numero_comprobante) AS max_numero_comprobante
						  FROM `comprobantes` comprobantes
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
				  }	
				  catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
							
			  //FIN SACA EL ID MAX DE COMPROBANTES
	  
				  $fecha= date("Y-m-d h:i:s");
					  //$descripcion = $txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura;
				  $descripcion="Asiento de costo de nota de credito No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
				  $descripcion="Asiento de costo de nota de credito No.".$numero_documento." realizado por ".$sesion_empresa_nombre;
														  
	  /* 			echo "debe=";
				  echo $total_debe;
				  echo "haber=";
				  echo $total_haber;
	   */			
				  $tipo_mov="D";
  
			  //GUARDA EN  COMPROBANTES
				  $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
				  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
				  
			  //GUARDA EN EL LIBRO DIARIO
				  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
					  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
					  '".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";

				  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
				  $id_libro_diario=mysql_insert_id();
				  $lin_diario=0;
				  try 
				  {
					  $sql = "SELECT id_plan_cuenta FROM formas_pago  
						  WHERE id_empresa = '" .$sesion_id_empresa. "' and id_tipo_movimiento=1"; 
					  //echo $sql;
					  $resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					  
					  while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$row['id_plan_cuenta'];
						  $debeVector[$lin_diario]=$tot_ventas+$tot_servicios;
			  // 			$debeVector[$lin_diario]=$total;
						  $haberVector[$lin_diario]=	0;
					  }
				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					

				  $lin_diario=$lin_diario+1;
				  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
				  $debeVector[$lin_diario]=$txtTotalIvaFVC;
				  $haberVector[$lin_diario]=0;
				  

				  try 
				  {
					  $sql = "SELECT id_plan_cuenta FROM formas_pago  
						  WHERE id_empresa = '" .$sesion_id_empresa. "' and id_tipo_movimiento=14"; 
					  //echo $sql;
					  $resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					  
					  while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$row['id_plan_cuenta'];
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$tot_ventas+$tot_servicios;
			  // 			$haberVector[$lin_diario]=$total;
					  }
				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					

  
			   //echo "numero de cuentas";
			   //echo $lin_diario;
				  for($i=1; $i<=$lin_diario; $i++)
				  {
						  //echo "entro a costo";
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
						  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario,id_plan_cuenta,debe, haber,
								  id_periodo_contable) values 
							  ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
							  '".$sesion_id_periodo_contable."');";
			  // 		echo "<br>".$sqlDLD;
						  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  $id_detalle_libro_diario=mysql_insert_id();
							  
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

								  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
								  $result6=mysql_query($sql6);
								  $id_mayorizacion=mysql_insert_id();
							  }
								  catch(Exception $ex) 
								  { ?> <div class="transparent_ajax_error">
									  <p>Error en la insercion de la tabla mayorizacion: 
									  <?php echo "".$ex ?></p></div> <?php }
							  // FIN DE MAYORIZACION
						  }  
					  }
				  }						
			  }
			  
			  if ($total>0)
			  {
				  $tipo_documentox="NotaCredito";
				  $referencia=$tipo_documentox."No.".documento_numero;
				  $estadoCC="Pendientes";
				  $sqlm2="Select max(id_cuenta_por_pagar) From cuentas_por_pagar;";
				  $resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				  $id_cuenta_por_pagar=0;
				  while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
				  {
					  $id_cuenta_por_pagar=$rowm2['max(id_cuenta_por_pagar)'];
				  }
				  $id_cuenta_por_pagar++;

				  $sql3 = "insert into cuentas_por_pagar (tipo_documento,numero_compra, referencia, valor, saldo,
					  numero_asiento, fecha_vencimiento, id_proveedor, id_cliente,id_plan_cuenta, id_empresa, 
					  id_compra, estado) 
					values ('".$tipo_documentox."','".$documento_numero."','".$referencia."','".$total."',
					'".$total."','".$id_libro_diario."','0000-00-00 00:00:00','0','".$id_cliente."','0','".$sesion_id_empresa."',
				  '".$id_venta."', '".$estadoCC."');";
//echo $sql3;
			  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
			  $id_cuenta_por_pagar=mysql_insert_id();
						  
			  }
				  
			  $sqlki="Select max(id_kardes) From kardes";
			  $resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
			  $id_kardes='0';
			  while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
			  {
				  $id_kardes=$rowki['max(id_kardes)'];
			  }
			  $id_kardes++;
			  $sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
			  ('".$fecha_venta."','Nota Credito en Venta','".$id_venta."', '".$sesion_id_empresa."')";
			  $resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
			  $id_kardes=mysql_insert_id();
			  if($result && $resp2)
			  {
				  echo "1";
			  }
			  else{
				  echo "3";
				  }
				  
				  
				  if ($emision_tipoEmision === 'E'){
						  genXmlnc($id_venta);
						  
										  $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
										  // echo $sqli;
										  $result=mysql_query($sqli);
										  
										  while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
										  {
											  $claveAcceso=$row['ClaveAcceso'];
										  }
										  if ($claveAcceso != ''){
											  echo "SI";
											  }else{
												  echo "NO";
											  }
										  
										  
						  
				  }
				  
				  
				  
				  
				  
		  }
		  
		  if ($cmbTipoDocumentoFVC==6){
			  
			//  echo "guia==>1";
				  
				  $txtIdIva2 = $_POST['txtTotalIvaFVC']; 
				  $FechaFin = $_POST['FechaFin']; 
				  $FechaInicio = $_POST['FechaInicio']; 
				  $motivoT = $_POST['MotivoTraslado']; 
				  $Vendedor_id = $_POST['chofer_id']; 
				  $DirDestino = $_POST['DirDestino']; 
				   $DirOrigen = $_POST['DirOrigen']; 
				  $txtIva=$_POST['txtTotalIvaFVC'];
					  
		  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
		  {            
			  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
			  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
			  <p>Error: '.mysql_error().' </p></div>  ');;
			  $iva=0;
			  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
			  {
				  $iva=$rowIva1['iva'];
				  $txtIdIva=$rowIva1['id_iva'];
				  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
			  }
			  
		  }

				  
				  $sql="insert into ventas (fecha_venta,       estado,        total, sub_total,sub0,sub12,descuento,propina,numero_factura_venta, fecha_anulacion, descripcion, id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, FechaInicio,FechaFin,DirDestino,DirOrigen,Vendedor_id,MotivoTraslado, RetIva) 
				  values (                '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."','".$FechaInicio."','".$FechaFin."','".$DirDestino."','".$DirOrigen."','".$Vendedor_id."','".$motivoT."','".$facAn."');";

				  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
				  $id_venta=mysql_insert_id();

				  if($limite==0){
					  $limite=0;
				  }else if($limite==1){
					  $limite=$limite-2;
				  }else{
					  $limite=$limite-1;
				  }
				  
				  $sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
				  $resultEmpresa2=mysql_query($sqlEmpresa2) or die();
			  
		  if($sql){
						  for($i=1; $i<=$txtContadorFilas; $i++)
			  {

				  if($_POST['txtIdServicio'.$i] >= 1)
				  { //verifica si en el campo esta agregada una cuenta
		  
					  $cantidad = $_POST['txtCantidadS'.$i];
					  $idServicio = $_POST['txtIdServicio'.$i];
					  $idKardex = $_POST['txtIdServicio'.$i];
					  $valorUnitario = $_POST['txtValorUnitarioS'.$i];
					  $valorTotal = $_POST['txtValorTotalS'.$i];
					  
					  $id_tipoP = $_POST['txtTipoS'.$i];
					  $idBod = $_POST['idbod'.$i];
					  $idvalorPago=$_POST['txtValorS'.$i];
					  $txtdetalle2=$_POST['txtdetalle2'.$i];
								  //GUARDA EN EL DETALLE VENTAS
					  $sql2 = "insert into detalle_ventas ( cantidad, estado, v_unitario, v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa) 
					  values ('".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."'	, '".$txtdetalle2."' ,'".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";

					  $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');

					  $id_detalle_venta=mysql_insert_id();
					  
					  if($sql2){
						//  echo "guia";

						  generarXMLGUIA($id_venta);
					   //  print_r($result);
						  
											  $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
											  // echo $sqli;
											  $result=mysql_query($sqli);
											  
											  while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
											  {
												  $claveAcceso=$row['ClaveAcceso'];
											  }
											  if ($claveAcceso != ''){
												  echo "SI";
												  }else{
													  echo "NO";
												  }
										  
										  
						  
	  // 			}
						  
					  }else{
						  echo "guiano";
					  }


		  
		  
				  }
				  

			  }
						  
					  }else{
						  echo "2";
					  }
			  

			 
				  
			  }
			  
			  
			  if ($cmbTipoDocumentoFVC==5)
		  {
			   $txtIva=$_POST['txtTotalIvaFVC'];
			  
					  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
		  {            
			  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
			  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
			  // echo $sqlIva1;
			  $iva=0;
			  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
			  {
				  $iva=$rowIva1['iva'];
				  $txtIdIva=$rowIva1['id_iva'];
				  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
			  }
			  
		  }
			  if($id_cliente!="" && $numero_factura !="" && $_POST['txtSubtotalFVC']>0  )
			  {       
				   $sql="insert into ventas (fecha_venta, estado, total, sub_total,sub0,sub12,                                     descuento,      propina,        numero_factura_venta, fecha_anulacion, descripcion,              id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario) 
				  values ('".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,            '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$sesion_usuario."');";
// echo $sql;
				  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar proforma: '.mysql_error().' </p></div>  ');
				  $id_venta=mysql_insert_id();
				  
							  if($limite==0){
					  $limite=0;
				  }else if($limite==1){
					  $limite=$limite-2;
				  }else{
					  $limite=$limite-1;
				  }
				  
				  $sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
				  $resultEmpresa2=mysql_query($sqlEmpresa2) or die();
			  
			   
				  for($i=1; $i<=$txtContadorFilas; $i++)	
				  {
				  if($_POST['txtIdServicio'.$i] >= 1)
				  { //verifica si en el campo esta agregada una cuenta
		  
						  $cantidad = $_POST['txtCantidadS'.$i];
						  $idServicio = $_POST['txtIdServicio'.$i];
						  $idKardex = $_POST['txtIdServicio'.$i];
						  $valorUnitario = $_POST['txtValorUnitarioS'.$i];
						  $valorTotal = $_POST['txtValorTotalS'.$i];
						  $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
						  $txtTipo11 = $_POST['txtTipo'.$i];
						  
						  $id_tipoP = $_POST['txtTipoS'.$i];
						  $cuenta = $_POST['cuenta'.$i];
						  $idBod = $_POST['idbod'.$i];
						  $idvalorPago=$_POST['txtValorS'.$i];
					  
					  
						  $txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
						  if ($id_tipoP == "2" && $txtDescripcionS!='') {
							  $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
							  $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
							  // echo "UPDATE PRODUCTO ==>".$sql3;
						  }
						  
						  if ($sql){
								  $sql2 = "insert into detalle_ventas ( idBodega,cantidad, estado, v_unitario, v_total, 	id_venta, id_servicio,id_kardex,tipo_venta, id_empresa) 
						  values ('".$idBod."','".$cantidad."','".$estado."','".$valorUnitario."','".$valorTotal."','".$id_venta."', '".$idServicio."'	, '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' );";
						  $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
						  <p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
						  $id_detalle_venta=mysql_insert_id();
						  if($sql2){
							  echo "1";
						  }else{
							  echo "3";
						  }
						  }else{
							  echo "2";
						  }
						  
						  //GUARDA EN EL DETALLE VENTAS
					  
					  }
				  }
							  
			  }
			  else
			  {
			  echo 'Datos vacios';
		  }
	  }
	  
		  
	  
	  
	  }
	  
		
	  
	  
	  catch (Exception $e) 
	  {
		  // Error en algun momento.
			 ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
	  }
	  
  
  }else{
	  echo "Limite de facturas alcanzado, consulta con tu proveedor";
  }
  
  
  
  
  
}


if($accion == "22")
{

	if(isset($_POST['queryString'])) {
		$queryString = $_POST['queryString'];
	
			// Is the string length greater than 0?
			if(strlen($queryString) >0) {

				
			$query = "Select *,establecimientos.codigo as est,emision.codigo as emi 
			From ventas,emision,establecimientos 
			where ventas.id_empresa='" . $sesion_id_empresa . "' and emision.id=ventas.codigo_lug and establecimientos.id=ventas.codigo_pun 
			and tipo_documento='1' and estado='Activo' AND numero_factura_venta LIKE '%$queryString%'  LIMIT 10; ";
	// echo $query;
			$result = mysql_query($query) or die(mysql_error());
			$numero_filas = mysql_num_rows($result); // obtenemos el número de filas
			if($result) {
				if($numero_filas == 0){
							echo "<div class=''> No hay resultados con el parámetro ingresado. </div>";
					 }else{
						echo "<table class='table table-condensed table-hover'>";
						echo "<thead>";
						echo "<tr>";
						echo "
							  <th style='padding-right: 5px;'>Numero Factura</th>  
							  <th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-default' aria-label='Left Align'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></a></th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						while ($row = mysql_fetch_array($result)) {
							echo '<tr style="cursor: pointer" title="Clic para seleccionar" onClick="fillFacAn(\''.$row["id_venta"].'\',\''.$row["est"].'\',\''.$row["emi"].'\',\''.$row["numero_factura_venta"].'\');" >';
							echo "<td>".$row['est']."-".$row['emi']."-".$row['numero_factura_venta']."</td>";
							echo "</tr>";
						}
						echo"</table> ";
					 }
					
				} else {
						echo 'ERROR: Hay un problema con la consulta.';
				}
			} else {
				echo 'La longitud no es la permitida.';
					// Dont do anything.
			} // There is a queryString.
		} else {
				echo 'No hay ningún acceso directo a este script!';
		}

}


if ($accion=="23")
{
	$fecha_desde =$_POST['fecha_desde'];
	$fecha_hasta =$_POST['fecha_hasta'];
	$sql="SELECT
    ventas.`id_venta`,
    ventas.`fecha_venta`,
    ventas.`estado`,
    ventas.`numero_factura_venta`,
    ventas.`fecha_anulacion`,
    ventas.`id_empresa`,
    ventas.`tipo_documento`,
    ventas.`codigo_pun`,
    ventas.`codigo_lug`,
    ventas.`Autorizacion`,
    ventas.`FechaAutorizacion`,
    ventas.`ClaveAcceso`,
    emision.tipoEmision
FROM
    `ventas`
INNER JOIN establecimientos ON establecimientos.id_empresa = ventas.id_empresa
INNER JOIN emision ON emision.id_est = establecimientos.id
WHERE
    ventas.id_empresa = $sesion_id_empresa AND ventas.Autorizacion IS NULL AND emision.tipoEmision = 'E' 
    AND ventas.tipo_documento = '1' AND ventas.estado='Activo' AND
    DATE_FORMAT(ventas.fecha_venta, '%Y-%m-%d') >= '".$fecha_desde."' AND DATE_FORMAT(ventas.fecha_venta, '%Y-%m-%d') <= '".$fecha_hasta."';";
	$result = mysql_query($sql);
	$response = array();
	while($row = mysql_fetch_array($result)){
		$response['id_venta'][]=$row['id_venta'];
		$response['numero_factura_venta'][]=$row['numero_factura_venta'];
	}
	//$response['consulta'][]=$sql;
 echo json_encode($response);
}



   if($accion == "24")
  {
	$response= [];
	
    $modoFacturacion=$_POST['modo']; 

            try
    		{
    		    
    		    
    			$cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
    			
    			$numero_factura=$_POST['txtNumeroFacturaFVC'];
    		    $fecha_venta= $_POST['textFechaFVC'];		
    			$id_cliente=$_POST['textIdClienteFVC'];
    			$estado = "Activo";
    			
    // 			$cmbIdVendedor=$_POST['cmbIdVendedorFVC'];
    	        $cmbIdVendedor=$_POST['chofer_id'];
                
    			
    			$cmbEst=$_POST['cmbEst'];
    			$cmbEmi=$_POST['cmbEmi'];
    			
    
    			$txtNombreFVC=$_POST['txtNombreFVC'];
    			$idFormaPago=0;
    			$txtCuotasTP=0;
    			$total=$_POST['txtTotalFVC'];
    			$sub_total=$_POST['txtSubtotalFVC'];
    			$sub_total0=$_POST['txtSubtotal0FVC'];
    			$sub_total12=$_POST['txtSubtotal12FVC'];
    			$descuento=$_POST['txtDescuentoFVCNum'];
    			$propina=$_POST['txtPropinaFVC'];
    		
    			$txtIva=$_POST['txtIva1'];
    			$facAn=trim($_POST['facAn']);
    			$facAn= ($facAn!='')?$facAn:0;
    			$motivo =$_POST['MotivoNota'] ;
    			$txtTotalIvaFVC=$_POST['txtTotalIvaFVC'];
    			$txtDescripcion=$_POST['txtDescripcionFVC'];
    			$txtContadorFilas=$_POST['txtContadorFilasFVC'];
    
    			$txtCambioFP=$_POST['txtCambioFP'];
    			$txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
    			$totalCobrar = $_POST['txtSubtotalVta']; 

				
				$totalAnticipo =0;
				
    			if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
    			{            
    				$sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
    				$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
    				<p>Error: '.mysql_error().' </p></div>  ');
					//$response['consulta']['sql'][]= $sqlIva1;
					//$response['consulta']['ejecucion'][]=  ($resultIva1)?'Si':'No';
    				$iva=0;
    				while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
    				{
    					$iva=$rowIva1['iva'];
    					$txtIdIva=$rowIva1['id_iva'];
    					$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
    				}
    				
    				// 	echo "txtIdIva ==>".$txtIdIva;
    			}
    			
    			
    			
    			if ($cmbTipoDocumentoFVC==1||$cmbTipoDocumentoFVC==41)
    			{
    				if($id_cliente!="" && $numero_factura !=""  )
    			    {       
    			       
    			
			
    					$id_venta=$_POST['idFactura'];
    					
    					$sqlVenta="SELECT `id_venta`, `id_empresa`,  `id_forma_pago` FROM `ventas` WHERE id_venta=$id_venta AND id_empresa=$sesion_id_empresa ";
    					$resultVenta = mysql_query($sqlVenta);
    					$idFormaPago=0;
    					while($rowVent = mysql_fetch_array($resultVenta) ){
    					    $idFormaPago = $rowVent['id_forma_pago'];
    					}
    					$idFormaPago = trim($idFormaPago)==''?0:$idFormaPago;
    					$response['forma_pago']= 'no';
    					if($idFormaPago>0){
    					    	$response['forma_pago']= 'si';
    					    	echo json_encode($response) ;
				                exit;
    					}
						$response['venta'][]= $id_venta;
   
    		
                    // Crear el asiento		
    
    				// if ($sesion_tipo_empresa=="6")
    				// {
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
							$response['num_asiento'][]= $numero_asiento;
    					}
    					 catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
    		
    		
    		            $tipo_comprobante = "Diario"; 
    				
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
								$response['num_comprobante'][]= $numero_comprobante;
    					}
    					catch (Exception $e) 
    					{
    						// Error en algun momento.
    					   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
    					}
    					
    					
    							  
    
    					$fecha= date("Y-m-d h:i:s");
    					$descripcion = "Factura de venta #".$numero_factura." realizada a ".$txtNombreFVC;
    					
    					$debe = $total;
    					$debe2 = $descuento;
    					$total_debe = $debe + $debe2;
    					
    					$haber1 = $sub_total;
    					$haber2 = $_POST['txtTotalIvaFVC'];
    					
    					$total_haber = $haber1 + $haber2 + $propina;
    					
    					$tipo_mov="F";
    	
    				//GUARDA EN  COMPROBANTES
    					$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
    					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
						//$response['consulta']['sql'][]= $sqlC;
						//$response['consulta']['ejecucion'][]=  ($respC)?'Si':'No';
    					$id_comprobante=mysql_insert_id();
    					$response['comprobante'][]= $id_comprobante;
    					
    				$sqlCentroCosto = "SELECT `id`, `id_empresa`, `codigo`, `direccion`, `centro_costo` FROM `establecimientos` WHERE id=$cmbEst ;";
    				$resultCentroCosto = mysql_query($sqlCentroCosto);
					//$response['consulta']['sql'][]= $sqlCentroCosto;
					//$response['consulta']['ejecucion'][]=   ($resultCentroCosto)?'Si':'No';
    				$centroCosto=0;
    				while($rowccc = mysql_fetch_array($resultCentroCosto) ){
    				    $centroCosto= $rowccc['centro_costo'];
    				}

    				//GUARDA EN EL LIBRO DIARIO
    					$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
    					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta,centroCosto) 
    					values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
    					'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' ,'".$centroCosto."')";
    					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    					$id_libro_diario=mysql_insert_id();
						//$response['consulta']['sql'][]= $sqlLD;
						//$response['consulta']['ejecucion'][]=  ($resp)?'Si':'No';
						$response['libro_diario'][]= $id_libro_diario;

    					$idPlanCuentas[1] = '';
    					$idPlanCuentas[2] = '';
    					$idPlanCuentas[3] = '';
    					$debeVector[1] =    0;
    					$debeVector[2] =    0;
    					$debeVector[3] =    0;
    					$haberVector[1] =   0;
    					$haberVector[2] =   0;
    					$haberVector[3] =   0;		

    					$lin_diario=0;
                        $valor[$lin_diario]=0;
    					$ident=0;
    					
    					for($i=1; $i<=$txtContadorFilas; $i++)				
    					{
    						if($_POST['txtCodigoS'.$i] >=1)
    						{	

    							$lin_diario=$lin_diario+1;
    							$idPlanCuentas[$lin_diario]=$_POST['txtCodigoS'.$i];
    							$debeVector[$lin_diario]=$_POST['txtValorS'.$i];
    							$haberVector[$lin_diario]=0; 
    							$formaPagoId[$lin_diario]=$_POST['formaPagoId'.$i];
    							
                                $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) 
                                VALUES     ('".$formaPagoId[$i]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$i]."','".$_POST['txtTipo1'.$i]."', NULL );";
    				            $respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
								//$response['consulta']['sql'][]= $sqlforma;
								//$response['consulta']['ejecucion'][]=  ($respForma)?'Si':'No';

    				            if($respForma){
    				                if($_POST['txtTipo1'.$i]==1 ){
    				                 $identificador="01";
    				                }
    				                else if($_POST['txtTipo1'.$i]==2){
    				                  $ident=1;
    				                    $identificador="02";
    				                }else if($_POST['txtTipo1'.$i]==16 && $_POST['txtTipo1'.$i]==17 ){
    				                //   $ident=1;
    				                    $identificador="19";
    				                }
    				                else{
    				                    $identificador="03";
    				                }
    				                   if($ident==1){
    				                       $identificador="02";
    				                   } 
    				                    $sql3="update ventas set id_forma_pago='".$identificador."' where id_venta='".$id_venta."' ";
    									$resp3 = mysql_query($sql3) or die(mysql_error());
										//$response['consulta']['sql'][]= $sql3;
										//$response['consulta']['ejecucion'][]=  ($resp3)?'Si':'No';
    				            }
    						}
    						
    								if($_POST['txtTipo1'.$i]=='18'){
    							
    							$sqlCtaPagar = "SELECT
    							cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
    							cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
    							cuentas_por_pagar.`id_cliente` AS cuentas_por_pagar_id_proveedor,
    							cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
    							cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
    					   FROM
    							`cuentas_por_pagar` cuentas_por_pagar 
    							 WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
    							 cuentas_por_pagar.`id_cliente`='".$id_cliente."' and saldo>0 
    							 order by cuentas_por_pagar.`fecha_vencimiento`"; 
    							$resultCtaPagar= mysql_query($sqlCtaPagar);
    							//$response['consulta']['sql'][]= $sqlCtaPagar;
								//$response['consulta']['ejecucion'][]= ($resultCtaPagar)?'Si':'No';

    							 $cantidadPagar = round(floatval($_POST['txtValorS'.$i]),2);
    							// echo '|';
    							while ($row = mysql_fetch_array($resultCtaPagar))
    							{ 
    								$id_cuenta_pagar= $row['ctas_x_pagar_id_cuenta_por_pagar'];
    								// echo '|saldo_pagar=>';	
    								 $saldo_pagar= round(floatval($row['cuentas_por_pagar_saldo']), 2);
    								// echo '|';	
    	
    								if($cantidadPagar>= $saldo_pagar){
    									$cantidadPagar  = $cantidadPagar - $saldo_pagar;
    									$saldo_actual = 0;
    									$text='Canceladas';
    								}else{
    									$saldo_actual = $saldo_pagar-$cantidadPagar;
    									$cantidadPagar=0;
    									$text='Pendientes';
    								}
    
    								// echo '|';
    								 $sqlUpdateCtaPagar="UPDATE `cuentas_por_pagar` SET `saldo`='$saldo_actual', estado='$text' WHERE id_cuenta_por_pagar=$id_cuenta_pagar AND id_empresa='".$sesion_id_empresa."' ";
    								// echo '|';
    
    								$resultUpdateCtaPagar= mysql_query($sqlUpdateCtaPagar);
									//$response['consulta']['sql'][]= $sqlUpdateCtaPagar;
									//$response['consulta']['ejecucion'][]= ($resultUpdateCtaPagar)?'Si':'No';
    								if($cantidadPagar==0){
    									break;
    								}
    							}
    						}
    						if ($_POST['txtTipo1'.$i]=='4')
    						{
    							$total=$_POST['txtValorS'.$i];
    							$txtCuotasTP=$_POST['txtCuotaS'.$i];
    							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];		
    						}
    						
    						if ($_POST['txtTipo1'.$i]=='13')
    						{
    							$estadoCC="Pendientes";
    							
    							$total_x_pagar=$_POST['txtValorS'.$i];
    							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
    							$cuentaxpagar="SI";
    						
    							$cmbTipoDocumentoFVC="Factura No.";
    							$sql3 = "insert into cuentas_por_pagar (tipo_documento, numero_compra, referencia,  
    							valor, saldo,numero_asiento,fecha_vencimiento,  id_proveedor,id_cliente ,id_plan_cuenta, id_empresa,
    							id_compra, estado) " . "values 
    							('".$cmbTipoDocumentoFVC."','".$numero_factura."','".
    							$txtNombreFVC.", ".$cmbTipoDocumentoFVC.",".$numero_factura."', '".
    							$total_x_pagar."','".$total_x_pagar."','','".$fecha_venta."',null,'".$id_cliente."','".
    							$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."',
    							'".$id_venta."', '".$estadoCC."');";
    
                				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
                				$id_cuenta_por_pagar=mysql_insert_id();
								//$response['consulta']['sql'][]= $sql3;
								//$response['consulta']['ejecucion'][]=  ($resp3)?'Si':'No';
								$response['cuenta_pagar'][]= $id_cuenta_por_pagar;
    						}
    						
    						if ($_POST['txtTipo1'.$i]=='17')
    						{
    						    // echo "TRANSFERENCIA</br>";

                                        $cmbTipoDocumento='Transferencia';
                                        $txtNumeroDocumento=$_POST['txtNumDocumento'];
                                        $txtDetalleDocumento="Transferencia de ".$txtNombreFVC ;
                                        $txtFechaEmision=$fecha_venta;
                                        $txtFechaVencimiento=$fecha_venta;
                                        $saldo_conciliado = 0;
                                        $valorConciliacion = $_POST['txtValorS'.$i];
                                        $estado = "No Conciliado";
                                        
                                        $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$_POST['txtCodigoS'.$i]."' 
                                        AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
                                        $resultb2=mysql_query($sqlb2);
										//$response['consulta']['sql'][]= $sqlb2;
										//$response['consulta']['ejecucion'][]= ($resultb2)?'Si':'No';
                                        while($rowb2=mysql_fetch_array($resultb2))//permite ir de fila en fila de la tabla
                                        {
                                            $id_bancos2=$rowb2['id_bancos'];
                                        }    
                                        
                                        $numero_fil = mysql_num_rows($resultb2);
                                        
                                        if($numero_fil > 0){
                                            
                                            $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                            values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";   
                                        }else {
                                            
                                            $sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values 
                                            ('".$_POST['txtCodigoS'.$i]."','".$saldo_conciliado."', '".$sesion_id_periodo_contable."');";
                                            $resultB=mysql_query($sqlB);
											//$response['consulta']['sql'][]= $sqlB;
											//$response['consulta']['ejecucion'][]=  ($resultB)?'Si':'No';
                							$id_bancos=mysql_insert_id();
                                            $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                            values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos."', '".$estado."', '".$id_libro_diario."');";
                                        }
										$resultDB=mysql_query($sqlDB);
                						$id_detalle_banco=mysql_insert_id();
										//$response['consulta']['sql'][]= $sqlDB;
										//$response['consulta']['ejecucion'][]=  ($resultDB)?'Si':'No';
    						}
    					}

    					$tot_ventas=0;
    					$tot_servicios=0;
    					$tot_costo=0;
    					
    					for($i=1; $i<=$txtContadorFilas; $i++){
    						if($_POST['txtIdServicio'.$i] >= 1)
    						{
    							$idProducto=$_POST['txtIdServicio'.$i];
    							$id_tipoP = $_POST['txtTipoS'.$i];
    							$cuenta = $_POST['cuenta'.$i];
    			
    							if ($id_tipoP =="1"){
    								$tot_ventas=$tot_ventas+$_POST['txtValorTotalS'.$i];
    								$costo_promedio=calcularPromedio($idProducto,$sesion_id_empresa);
    								$tot_costo=$tot_costo+($costo_promedio * $_POST['txtCantidadS'.$i]);
    							}
    							
    						    if ($id_tipoP == "2" ){
    						        $sqlPlanCuenta= "SELECT  
    								productos.`id_producto` AS productos_id_producto, 
    							 productos.`producto` AS productos_nombre,
    							 productos.`grupo` AS productos_grupo, 
    							 centro_costo.`id_centro_costo` AS centro_id, 
    							 centro_costo.`descripcion` AS centro_descripcion, 
    							 centro_costo.`id_cuenta` AS productos_id_cuenta,
    							 plan_cuentas.codigo,
          						 plan_cuentas.nombre
    							 FROM  `productos` 
    							 INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
    							 INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = centro_costo.id_cuenta
    							 WHERE productos.id_producto=$idProducto ";
    							 $resultPlanCuentas= mysql_query($sqlPlanCuenta);
								 //$response['consulta']['sql'][]= $sqlPlanCuenta;
								//$response['consulta']['ejecucion'][]=   ($resultPlanCuentas)?'Si':'No';
    							 $planCuentaServicio = 0;
    							 while($rowPC = mysql_fetch_array($resultPlanCuentas)){
    								$planCuentaServicio = $rowPC['productos_id_cuenta'];
    							 }
    								$tot_servicios=$tot_servicios+$_POST['txtValorTotalS'.$i];
    							}
    							
    						}
    					}
    					
    					
    			    try 
    				{
                        $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
    					WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
    					formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultMercaderia=mysql_query($sqlMercaderia);
						//$response['consulta']['sql'][]= $sqlMercaderia;
						//$response['consulta']['ejecucion'][]=  ($resultMercaderia)?'Si':'No';
                        $idcodigo_v=0;
                        while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
                        {
                            $idcodigo_v=$row['codigo_plan_cuentas'];
                        }
                        $idcodigo_v;
    				}
    				catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
    				
    				try 
    				{
    					$sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
    					WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
    					formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultServicio=mysql_query($sqlServicio);
						//$response['consulta']['sql'][]= $sqlServicio;
						//$response['consulta']['ejecucion'][]= ($resultServicio)?'Si':'No';
                        $idcodigo_s=0;
                        while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
                        {
                            $idcodigo_s=$row['codigo_plan_cuentas'];
                        }
                        $idcodigo_s;
    
    				}catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				
    
    						
        				$sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
              				from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
        						( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
        
        				$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						//$response['consulta']['sql'][]= $sql;
						//$response['consulta']['ejecucion'][]= ($resultS)?'Si':'No';			
        				$plan_id_cuenta_vta=0;
        				$plan_id_cuenta_servicio=0;
        				while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
        				{
        					if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
        						{
        							$plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
        						}
        					if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
        						{
        							$plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
        						}
    
        						
        				}
                        
                        
						$response['tot_ventas']= $tot_ventas;
        				if ($tot_ventas!=0)
        				{
        					$lin_diario=$lin_diario+1;
        					$idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
        					$debeVector[$lin_diario]=0;
        					$haberVector[$lin_diario]=$tot_ventas;
        					
    
        				}
        				
						$response['tot_servicios']= $tot_servicios;
        				if ($tot_servicios!=0)
        				{
        					$lin_diario=$lin_diario+1;
        				// 	$idPlanCuentas[$lin_diario]= $plan_id_cuenta_servicio;
        						$idPlanCuentas[$lin_diario]= $planCuentaServicio;
        						$debeVector[$lin_diario]=0;
        					$haberVector[$lin_diario]=$tot_servicios;
        					
    
        				}
                        
                        
                        try {
                                $sqlDescuento="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago WHERE `id_tipo_movimiento` =14 
                                and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                                $resultDescuento=mysql_query($sqlDescuento);
								//$response['consulta']['sql'][]= $sqlDescuento;
								//$response['consulta']['ejecucion'][]= ($resultDescuento)?'Si':'No';		
                                $idcodigo_d=0;
                                while($row=mysql_fetch_array($resultDescuento))//permite ir de fila en fila de la tabla
                                {
                                    $idcodigo_d=$row['codigo_plan_cuentas'];
                                }
                                $idcodigo_d;
            
                        }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
    					
    					
    					
    					if ($descuento!=0)
    					{
    						$lin_diario=$lin_diario+1;
    						$idPlanCuentas[$lin_diario]= $idcodigo_d;
    						$debeVector[$lin_diario]=$descuento;
    						$haberVector[$lin_diario]=0;
    					}
    					
    					
            			try {
                                $sqlPropina="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago 
                                WHERE `id_tipo_movimiento` =15 and formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                                $resultPropina=mysql_query($sqlPropina);
								//$response['consulta']['sql'][]= $sqlPropina;
								//$response['consulta']['ejecucion'][]= ($resultPropina)?'Si':'No';	
                                $idcodigo_p=0;
                                while($row=mysql_fetch_array($resultPropina))//permite ir de fila en fila de la tabla
                                {
                                    $idcodigo_p=$row['codigo_plan_cuentas'];
                                }
                                $idcodigo_p;
            
                        }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
    					
    					
    					if ($propina!=0)
    					{
    						$lin_diario=$lin_diario+1;
    						$idPlanCuentas[$lin_diario]= $idcodigo_p;
    						$debeVector[$lin_diario]=0;
    						$haberVector[$lin_diario]=$propina;
    					}
    					
    					if ($txtTotalIvaFVC!=0)
    					{
    
        					$lin_diario=$lin_diario+1;
        					$idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
        					$debeVector[$lin_diario]=0;
        					$haberVector[$lin_diario]=$txtTotalIvaFVC;
    
    					}
    					
    					
                        
                         
    					for($i=1; $i<=$lin_diario; $i++)
    					{
    					    
    
    						if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
    						{
    						    
    							$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
    							('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
    							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    							$id_detalle_libro_diario=mysql_insert_id();
								//$response['consulta']['sql'][]= $sqlDLD;
								//$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
								$response['detalle_libro_diario'][]= $id_detalle_libro_diario;
    							
    							$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
    							$result5=mysql_query($sql5);
								//$response['consulta']['sql'][]= $sql5;
								//$response['consulta']['ejecucion'][]= ($result5)?'Si':'No';
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
    							    
    								try 
    								{
    
    									$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
    									$result6=mysql_query($sql6);
    									$id_mayorizacion=mysql_insert_id();
										//$response['consulta']['sql'][]= $sql6;
										//$response['consulta']['ejecucion'][]= ($result6)?'Si':'No';
										$response['mayorizacion'][]= $id_mayorizacion;
    								}
    								catch(Exception $ex) 
    								{ ?> <div class="transparent_ajax_error">
    									<p>Error en la insercion de la tabla mayorizacion: 
    									<?php echo "".$ex ?></p></div> <?php }
    								// FIN DE MAYORIZACION
    							}
    						}
    					}
    					
    					$response['tot_costo']= $tot_costo;
    				    if ($tot_costo>0)
    					{
							$numero_asiento++;
    						$tipo_comprobante = "Diario"; 
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
    
    					
    				//FIN SACA EL ID MAX DE COMPROBANTES
    		
    						$fecha= date("Y-m-d h:i:s");
    						
    						$descripcion="Asiento de costo de venta de la Factura No.".$numero_factura." realizado por ".$sesion_empresa_nombre;
    
    					    $total_debe  = $tot_costo;
    					    $total_haber = $tot_costo;
    					    $tipo_mov="F";
    	
    				//GUARDA EN  COMPROBANTES
    						$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
    						$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
							$id_comprobante=mysql_insert_id();
							//$response['consulta']['sql'][]= $sqlC;
							//$response['consulta']['ejecucion'][]=  ($respC)?'Si':'No';
    				//GUARDA EN EL LIBRO DIARIO
    						$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
    						total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta,centroCosto) 
    						values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',
    						'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
    						'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' ,'".$centroCosto."')";
    
    						$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
    						$id_libro_diario=mysql_insert_id();
							//$response['consulta']['sql'][]= $sqlLD;
							//$response['consulta']['ejecucion'][]= ($resp)?'Si':'No';
							$response['libro_diario'][]= $id_libro_diario;
    			try {
                        $sqlCosto="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
                        WHERE `id_tipo_movimiento` = 7 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
                        formas_pago.id_empresa='".$sesion_id_empresa."'  ";
                        $resultCosto=mysql_query($sqlCosto);
						//$response['consulta']['sql'][]= $sqlCosto;
						//$response['consulta']['ejecucion'][]= ($resultCosto)?'Si':'No';
                        $idcodigo_v=0;
                        while($row=mysql_fetch_array($resultCosto))//permite ir de fila en fila de la tabla
                        {
                            $idcod_costo=$row['codigo_plan_cuentas'];
                        }
                        $idcod_costo;
    
                }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
    
    	
    		try {
                        $sqlInventario="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM enlaces_compras,plan_cuentas 
                        WHERE `tipo_cpra` = 5 and plan_cuentas.id_plan_cuenta=enlaces_compras.cuenta_contable
                        and enlaces_compras.id_empresa='".$sesion_id_empresa."'  ";
                        $resultInventario=mysql_query($sqlInventario);
						//$response['consulta']['sql'][]= $sqlInventario;
						//$response['consulta']['ejecucion'][]= ($resultInventario)?'Si':'No';
                        $idcodigo_v=0;
                        while($row=mysql_fetch_array($resultInventario))//permite ir de fila en fila de la tabla
                        {
                            $idcod_inventario=$row['codigo_plan_cuentas'];
                        }
                        $idcod_inventario;
    
                }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }					
				
    				$sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta, plan_cuentas.`codigo` AS plan_cuentas_codigo	from	`plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and
    							( plan_cuentas.`codigo` ='".$idcod_costo."' or  plan_cuentas.`codigo` ='".$idcod_inventario."')"  ;
    						$resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							//$response['consulta']['sql'][]= $sql;
							//$response['consulta']['ejecucion'][]= ($resultS)?'Si':'No';		
    						$plan_id_cuenta_costo=0;
    						$plan_id_cuenta_inventario=0;
    						while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
    						{
    							if ($rowS['plan_cuentas_codigo']==$idcod_costo)
    							{
    								$plan_id_cuenta_costo=$rowS['plan_cuentas_id_cuenta'];
    					
    							}
    							if ($rowS['plan_cuentas_codigo']==$idcod_inventario)
    							{
    								$plan_id_cuenta_inventario=$rowS['plan_cuentas_id_cuenta'];
    							}
    						}
    					
    
    					
    						$lin_diario=0;
    						if ($tot_costo>0)
    						{
    							$lin_diario=$lin_diario+1;
    							$idPlanCuentas[$lin_diario]= $plan_id_cuenta_costo;
    							$debeVector[$lin_diario]=$tot_costo;
    							$haberVector[$lin_diario]=0;	
    							$lin_diario=$lin_diario+1;
    							$idPlanCuentas[$lin_diario]=$plan_id_cuenta_inventario;
    							$debeVector[$lin_diario]=0;
    							$haberVector[$lin_diario]=$tot_costo;									
    						}

    						for($i=1; $i<=$lin_diario; $i++)
    						{
    							if ($idPlanCuentas[$i] !="")
    							{
    
    						//GUARDA EN EL DETALLE LIBRO DIARIO
    								$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 
    								id_plan_cuenta,debe, haber, id_periodo_contable) values 
    								('".$id_libro_diario."',
    								'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',
    								'".$sesion_id_periodo_contable."');";
    								$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    								$id_detalle_libro_diario=mysql_insert_id();
									//$response['consulta']['sql'][]= $sqlDLD;
									//$response['consulta']['ejecucion'][]= ($resp2)?'Si':'No';		
    								
    							// CONSULTAS PARA GENERAR LA MAYORIZACION
    								$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
    								$result5=mysql_query($sql5);
									//$response['consulta']['sql'][]= $sql5;
									//$response['consulta']['ejecucion'][]=  ($result5)?'Si':'No';
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
    										$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
    										$result6=mysql_query($sql6);
    										$id_mayorizacion=mysql_insert_id();
											//$response['consulta']['sql'][]= $sql6;
											//$response['consulta']['ejecucion'][]= ($result6)?'Si':'No';
											$response['mayorizacion'][]= $id_mayorizacion;
    									}
    									catch(Exception $ex) 
    									{ ?> <div class="transparent_ajax_error">
    										<p>Error en la insercion de la tabla mayorizacion: 
    										<?php echo "".$ex ?></p></div> <?php }
    								// FIN DE MAYORIZACION
    								}  
    							}
    						}						
    					}
    				// } //aqui finaliza
					
    			
    			  // GUARDAR EN KARDEX
    
    				$sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
    				('".$fecha_venta."','Venta','".$id_venta."', '".$sesion_id_empresa."')";
    				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
    				$id_kardes=mysql_insert_id();
					//$response['consulta']['sql'][]= $sqlk;
					//$response['consulta']['ejecucion'][]= ($resultk)?'Si':'No';
					$response['kardes'][]= $id_kardes;
					$response['guardo']= 0;
    				if($resultk )
    				{
						$response['guardo']=1;
    					// echo "kardex";
    					if($_POST['cmbTipoDocumentoFVC']==41){
							$txtContadorFilasReembolso = $_POST['txtContadorFilasReembolso'];
							for($re=1;$re<=$txtContadorFilasReembolso;$re++){
								$txtCedulaReembolso = $_POST['txtCedulaReembolso'.$re];
								$txtCodigoPais = $_POST['txtCodigoPais'.$re];
								$txtTipoProveedor = $_POST['txtTipoProveedor'.$re];
								$txtTipoDocumento = $_POST['txtTipoDocumento'.$re];
								$txtEstablecimientoReembolso = $_POST['txtEstablecimientoReembolso'.$re];
								$txtEmisionReembolso = $_POST['txtEmisionReembolso'.$re];
								$txtSecuencialReembolso = $_POST['txtSecuencialReembolso'.$re];
								$txtFechaReembolso = $_POST['txtFechaReembolso'.$re];
								$txtNumeroAutorizacion = $_POST['txtNumeroAutorizacion'.$re];
								$cantidadCaracteres=  strlen($txtCedulaReembolso);
								$tipo_identificacion_proveedor_reembolso='05';
								if($cantidadCaracteres==13){
									$tipo_identificacion_proveedor_reembolso='06';
								}
                                if(trim($txtCedulaReembolso)!=''){
                                    	 $sqlReembolso="INSERT INTO `reembolsos_gastos`( `tipo_identificacion_proveedor_reembolso`, `identificacion_proveedor_reembolso`, `cod_pais_proveedor_reembolso`, `tipo_proveedor_reembolso`, `cod_doc_reembolso`, `estab_doc_reembolso`, `pto_emi_doc_reembolso`, `fecha_emision_doc_reembolso`, `id_venta`,secuencial_doc_reembolso,numero_autorizacion_doc_reembolso) VALUES ('".$tipo_identificacion_proveedor_reembolso."','".$txtCedulaReembolso."','".$txtCodigoPais."','".$txtTipoProveedor."','".$txtTipoDocumento."','".$txtEstablecimientoReembolso."','".$txtEmisionReembolso."','".$txtFechaReembolso."','".$id_venta."','".$txtSecuencialReembolso."','".$txtNumeroAutorizacion."')";
								$resultReembolso = mysql_query($sqlReembolso);
								$id_reembolso = mysql_insert_id();
								//$response['consulta']['sql'][]= $sqlReembolso;
								//$response['consulta']['ejecucion'][]= ($resultReembolso)?'Si':'No';
								$response['reembolsos_gastos'][]= $id_reembolso;
								$txtContadorFilasCompensacion = $_POST['txtContadorFilasCompensacion'.$re];

								for($t=1;$t<=$txtContadorFilasCompensacion;$t++){
									// $txtCodigoCompensacion=$_POST['txtCodigoCompensacion'.$re.'_'.$t];
									$txtCodigoImpuestoCompensacion=$_POST['txtCodigoImpuestoCompensacion'.$re.'_'.$t];
									$txtPorcentajeCompensacion=$_POST['txtPorcentajeCompensacion'.$re.'_'.$t];
									$txtTarifaCompensacion=$_POST['txtTarifaCompensacion'.$re.'_'.$t];
									$txtBaseImponible=$_POST['txtBaseImponible'.$re.'_'.$t];
									$txtImpuestoCompensacion=$_POST['txtImpuestoCompensacion'.$re.'_'.$t];

									// echo $sqlCompensacion="INSERT INTO `compensaciones_reembolso`( `codigo`, `tarifa`, `valor`, `id_reembolso`) VALUES ('".$txtCodigoCompensacion."','".$txtTarifaCompensacion."','".$txtBaseImponible."','".$id_reembolso."')";
									// $resultCompensacion = mysql_query($sqlCompensacion);

									 $sqlImpuestos="INSERT INTO `impuestos_reembolso`( `codigo_impuesto`, `codigo_porcentaje`, `tarifa`, `base_imponible`, `impuesto`, `id_reembolsos`) VALUES ('".$txtCodigoImpuestoCompensacion."','".$txtPorcentajeCompensacion."','".$txtTarifaCompensacion."','".$txtBaseImponible."','".$txtImpuestoCompensacion."','".$id_reembolso."')";
									$resultImpuestos = mysql_query($sqlImpuestos);
									//$response['consulta']['sql'][]= $sqlImpuestos;
									//$response['consulta']['ejecucion'][]= ($resultImpuestos)?'Si':'No';

								}
                                }
							

							}
							
						}
    				}

    				/*  GUARDAR EN CUENTAS POR COBRAR */
    				if ($total>0 and $txtCuotasTP>0)
    				{
    					$txtContadorFilas=8;
    					
    					for($i=1; $i<=$txtContadorFilas; $i++)				
    					{
    						if ($_POST['txtTipo1'.$i]=='4')
    						{
    						    $txtFechaTP=$_POST['txtFechaS'.$i];
    							$total=$_POST['txtValorS'.$i];
    							$txtCuotasTP=$_POST['txtCuotaS'.$i];
    							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];
    						//	echo "fecha ===".$txtFechaTP;
    						}
    					}
    				}
    
    				if($total > 0 and $txtCuotasTP>0)
    				{            
    					$cuotas = round(($total / $txtCuotasTP),2); 
    					//	$cuotas= round($cuotas_x * 100) / 100;
    						$aux=round(($cuotas * $txtCuotasTP),2); 
    						$dif=round(($total-$aux),2);
    						$cuota_final=$cuotas;
    						//echo $dif;
    					if ($dif != 0)
    					{
    							$cuota_final=$cuota_final + $dif;
    					}
    		
    					$estadoCC = "Pendientes";                
    					for($i=1; $i<=$txtCuotasTP; $i++)
    					{
    						if ($i == $txtCuotasTP)
    					    {
    							$cuotax=$cuota_final;
    						}
    						else
    						{
    							$cuotax=$cuotas;
    						} 

    						$mod_date = strtotime($txtFechaTP."+ ".$i." months");
    						$fecha_nueva = date("Y-m-d",$mod_date);

    							//$sql3 = "insert into cuentas_por_cobrar (id_cuenta_por_cobrar, numero_factura, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
    							//	. "values ('".$id_cuenta_por_cobrar."','".$numero_factura."','".$txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura."','".$cuotax."','".$cuotax."','', '".$fecha_nueva."', '', '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";
    						$sql3 = "insert into cuentas_por_cobrar ( tipo_documento,         numero_factura,         referencia,        valor,          saldo,        numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor,id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
    								. "values                      ('".$cmbTipoDocumentoFVC."','".$numero_factura."','".$txtNombreFVC."' ,'".$cuotax."','".$cuotax."',          '',             '".$fecha_nueva."', null, null, '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";						
    						$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
    						$id_cuenta_por_cobrar=mysql_insert_id();
							//$response['consulta']['sql'][]= $sql3;
							//$response['consulta']['ejecucion'][]= ($resp3)?'Si':'No';
							$response['cuentas_por_cobrar'][]= $id_cuenta_por_cobrar;
    					}                
    				
    				
    				}    
    				
    				

    				$response['modoFacturacion']= $modoFacturacion;
    				// if($modoFacturacion=='200'){
    				    
    				//     $emision_tipoEmision='F';
    				    
    				// }else{
    				    
    				    $emision_tipoEmision = $_SESSION['emision_tipoEmision'];
    				// }
					$response['emision_tipoEmision']= $emision_tipoEmision;
    				
            				if ($emision_tipoEmision === 'E'){
            			        genXml($id_venta);
            			        
            			        	            $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
            									$result=mysql_query($sqli);
            									//$response['consulta']['sql'][]= $sqli;
												//$response['consulta']['ejecucion'][]= ($result)?'Si':'No';
            									while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            									{
            										$claveAcceso=$row['ClaveAcceso'];
            									}
            									if ($claveAcceso != ''){
													$response['claveAcceso']= "SI";
            									    // echo "SI";
            									    }else{
														$response['claveAcceso']= "NO";
            									        // echo "NO";
            									    }
            			        
            			        }
    				
								
    				
    			    }
    			    else
    			    {
    				if($txtCambioFP>0.0 )
    				{
    				?> <div class='transparent_ajax_error'><p>Existe un saldo pendiente de cancelar <?php echo " ".mysql_error(); ?>;</p></div> <?php
    					
    				}
    				else
    				{
    				?> <div class='transparent_ajax_error'><p>Error: Valor a cobrar incorrecto <?php echo " ".mysql_error(); ?>;</p></div> <?php
    					
    				}
    			}
		
				echo json_encode($response) ;
				exit;
    	}
    		
    		
    		}
    		catch (Exception $e) {
    			// Error en algun momento.
    			   ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
    		}
}


if($accion == "25")
{
	$id_venta = $_POST['idFactura'];
	$numero_factura=$_POST['txtNumeroFacturaFVC'];

	// primera validacion 
     $sqlValidar = "SELECT `id_venta`, `fecha_venta`, `estado`, `total`, `sub_total`, `sub0`, `sub12`, `descuento`, `propina`, `numero_factura_venta`, `fecha_anulacion`, `descripcion`, `id_iva`, `montoIce`, `id_vendedor`, `id_cliente`, `id_empresa`, `tipo_documento`, `codigo_pun`, `codigo_lug`, `id_forma_pago`, `id_usuario`, `Retfuente`, `Comentario`, `Comentario2`, `Autorizacion`, `FechaAutorizacion`, `ClaveAcceso`, `FechaInicio`, `FechaFin`, `DirDestino`, `MotivoTraslado`, `Retiva`, `Vendedor_id`, `MotivoNota`, `Otros`, `numero_cierre` FROM `ventas` WHERE id_venta =$id_venta";
	$resultValidar = mysql_query($sqlValidar);
	$autorizacion ='';
	while($rowVal = mysql_fetch_array($resultValidar)){
		$autorizacion = $rowVal['Autorizacion'];
	}
	if(trim($autorizacion)!=''){
		echo 'No se puede editar, la proforma esta autorizada.';
		exit;
	}

  $modoFacturacion=$_POST['modo']; 

      try
      {
        $id_venta = $_POST['idFactura'];
          
          $cmbTipoDocumentoFVC=$_POST['cmbTipoDocumentoFVC']; 
          $numero_factura=$_POST['txtNumeroFacturaFVC'];
          $fecha_venta= $_POST['textFechaFVC'];		
          $id_cliente=$_POST['textIdClienteFVC'];
          $estado = "Activo";
          $cmbIdVendedor=$_POST['chofer_id'];
          $cmbEst=$_POST['cmbEst'];
          $cmbEmi=$_POST['cmbEmi'];
          $txtNombreFVC=$_POST['txtNombreFVC'];
          $idFormaPago=0;
          $txtCuotasTP=0;
          $total=$_POST['txtTotalFVC'];
          $sub_total=$_POST['txtSubtotalFVC'];
          $sub_total0=$_POST['txtSubtotal0FVC'];
          $sub_total12=$_POST['txtSubtotal12FVC'];
          $descuento=$_POST['txtDescuentoFVCNum'];
          $propina=$_POST['txtPropinaFVC'];
          $txtIva=$_POST['txtIva1'];
          $facAn=$_POST['facAn'];
          $motivo =$_POST['MotivoNota'] ;
          $txtTotalIvaFVC=$_POST['txtTotalIvaFVC'];
          $txtDescripcion=$_POST['txtDescripcionFVC'];
          $txtContadorFilas=$_POST['txtContadorFilasFVC'];

          $txtCambioFP=$_POST['txtCambioFP'];
          $txtContadorAsientosAgregadosFVC=$_POST['txtContadorAsientosAgregadosFVC'];
 
          if($id_cliente!="" && $txtTotalIvaFVC!="" && $numero_factura !="" )
          {            
              $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
              $resultIva1=mysql_query($sqlIva1);
              $iva=0;
              while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
              {
                  $iva=$rowIva1['iva'];
                  $txtIdIva=$rowIva1['id_iva'];
                  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
              }
          }

          if ($cmbTipoDocumentoFVC==5)		  
          {
              if($id_cliente!="" && $numero_factura !=""  )
              {   
                  $incoterm =isset($_POST['incoterm'])?$_POST['incoterm']:'0';
    			        $lugarIncoTerm =isset($_POST['lugarIncoTerm'])?$_POST['lugarIncoTerm']:'';
    			        
    			        $paisOrigen =isset($_POST['paisOrigen'])?$_POST['paisOrigen']:'';
    			        
    			        $puertoEmbarque =isset($_POST['puertoEmbarque'])?$_POST['puertoEmbarque']:'';
    			       
    			        $puertoDestino =isset($_POST['puertoDestino'])?$_POST['puertoDestino']:'';
    			        
    			         $paisDestino=isset($_POST['paisDestino'])?$_POST['paisDestino']:'';
    			         
    			        $paisAdquisicion =isset($_POST['paisAdquisicion'])?$_POST['paisAdquisicion']:'';
    			        
    			        $numeroDae =isset($_POST['numeroDae'])?$_POST['numeroDae']:'';
    			        
    			        $numeroTransporte =isset($_POST['numeroTransporte'])?$_POST['numeroTransporte']:'';
    			        
    			        $fleteInternacional =isset($_POST['fleteInternacional'])?$_POST['fleteInternacional']:'';
    			        
    			        $seguroInternacional =isset($_POST['seguroInternacional'])?$_POST['seguroInternacional']:'';
    			        
    			    $gastosAduaneros=0;
    			        if(isset($_POST['gastosAduaneros'])){
    			            if(trim($_POST['gastosAduaneros'])!=''){
    			                $gastosAduaneros =$_POST['gastosAduaneros'];
    			            }
    			        }
    			       $gastosTransporte=0;
    			       if(isset($_POST['gastosTransporte'])){
    			            if(trim($_POST['gastosTransporte'])!=''){
    			                $gastosTransporte =$_POST['gastosTransporte'];
    			            }
    			        }
             $sub_total0		= isset($_POST['txtSubtotal0FVC'])? $_POST['txtSubtotal0FVC']: 0;
                			$sub_total12	= isset($_POST['txtSubtotal12FVC'])? $_POST['txtSubtotal12FVC'] :0;
    	    $sql="update ventas set fecha_venta='".$fecha_venta."', total='".$total."', sub_total='".$sub_total."' , sub0='".$sub_total0."' , sub12='".$sub_total12."', descuento='".$descuento."', propina='".$propina."', descripcion='".$txtDescripcion."', id_iva='".$txtIdIva."', id_vendedor='".$cmbIdVendedor."', id_cliente='".$id_cliente."', numero_factura_venta='".$numero_factura."', id_usuario='".$sesion_usuario."', id_forma_pago='".$idFormaPago."',`tipo_inco_term`='".$incoterm."',`lugar_inco_term`='".$lugarIncoTerm."',`pais_origen`='".$paisOrigen."',`puerto_embarque`='".$puertoEmbarque."',`puerto_destino`='".$puertoDestino."',`pais_destino`='".$paisDestino."',`pais_adquisicion`='".$paisAdquisicion."',`numero_dae`='".$numeroDae."',`numero_transporte`='".$numeroTransporte."',`flete_internacional`='".$fleteInternacional."',`seguro_internaiconal`='".$seguroInternacional."',`gastos_aduaneros`='".$gastosAduaneros."',`gastos_transporte`='".$gastosTransporte."', total_iva ='".$txtTotalIvaFVC."' WHERE id_venta='".$id_venta."' ;";

    	
                
             $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al actualizar la venta: '.mysql_error().' </p></div>  ');
                  

          
              
           

                 $sqlBorrarDetallesVentas = "DELETE FROM `detalle_ventas` WHERE `id_venta` ='".$id_venta."'  ";
                $resultBorrarDetallesVentas=mysql_query($sqlBorrarDetallesVentas)or die('<div class="transparent_ajax_error">
                <p>Error al eliminar los detalles anteriores de las ventes: '.mysql_error().' </p></div>  ');


                  for($i=1; $i<=$txtContadorFilas; $i++)	
                  {
                  if($_POST['txtIdServicio'.$i] >= 1)
                  { 
          
                          $cantidad = $_POST['txtCantidadS'.$i];
                          $idServicio = $_POST['txtIdServicio'.$i];
                          $idKardex = $_POST['txtIdServicio'.$i];
                          $valorUnitario = $_POST['txtValorUnitarioShidden'.$i];
                          $valorTotal = $_POST['txtValorTotalS'.$i];
                          $txtPorcentajeS = $_POST['txtPorcentajeS'.$i];
                          $txtTipo11 = $_POST['txtTipo'.$i];							  
                          $id_tipoP = $_POST['txtTipoS'.$i];
                          $cuenta = $_POST['cuenta'.$i];
                          $idBod = $_POST['idbod'.$i];
                          $idvalorPago=$_POST['txtValorS'.$i];
                          $txtdesc = ($_POST['txtdesc'.$i]=='')?0:$_POST['txtdesc'.$i];
                          $txtdetalle2=$_POST['txtdetalle2'.$i];
                           $bodegaCantidad=$_POST['bodegaCantidad'.$i];
                          if($bodegaCantidad!=''){
                              $bodegaCantidad=$_POST['bodegaCantidad'.$i];
                          }else{
                              $bodegaCantidad='0';
                          }
                          $txtCodigoServicio=$_POST['txtCodigoServicio'.$i];

                          
                          $txtDescripcionS = trim($_POST['txtDescripcionS'.$i]);
                          if ($id_tipoP == "2" && $txtDescripcionS!='') {
                              $sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $sesion_id_empresa . "' and id_producto='" . $idServicio . "';";
                              $resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No hay stock para vender ' . mysql_error() . ' </p></div>  ');
                              // echo "UPDATE PRODUCTO ==>".$sql3;
                          }
                          $id_iva = $_POST['IVA120'.$i];
						  $sql_iva= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='" . $sesion_id_empresa . "' AND id_iva='" . $id_iva . "' ";
						  $result_iva = mysql_query( $sql_iva );
						  while($row_iva = mysql_fetch_array($result_iva) ){
							  $tarifa_iva= $row_iva['iva'];
						  }         
                          	$total_iva= floatval($valorTotal) * (floatval( $tarifa_iva )/100);    
                          //GUARDA EN EL DETALLE VENTAS
               $sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa, `tarifa_iva`, `total_iva`) 
                          values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$sesion_id_empresa."' , '".$id_iva."','".$total_iva."' );";
                          $resp2 = mysql_query($sql2) or die('<div class="alert alert-danger">
                          <p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
                          $id_detalle_venta=mysql_insert_id();
      
                 
                  }// fin verificar si esta llena la fila
              }//fin for 
            
              if($result){
				echo '3';
			  }else{
				echo '4';
			  }

              date_default_timezone_set('America/Guayaquil');
              $fecha = date('Y-m-d H:i:s');
              $sql="INSERT INTO `bitacora`( `id_usuario`, `descripcion`, `fecha_accion`, `id_empresa`, `modulo`, `registro`) VALUES ('$sesion_id_usuario','U','$fecha','$sesion_id_empresa','Proforma', '".$_POST['cmbTipoDocumentoFVC'].$cmbEst.$cmbEmi.$numero_factura."')";
              $result = mysql_query($sql);

              }
              else
              {
              if($txtCambioFP>0.0 )
              {
              ?> <div class='transparent_ajax_error'><p>Existe un saldo pendiente de cancelar <?php echo " ".mysql_error(); ?>;</p></div> <?php
                  
              }
              else
              {
              ?> <div class='transparent_ajax_error'><p>Error: Valor a cobrar incorrecto <?php echo " ".mysql_error(); ?>;</p></div> <?php
                  
              }
          }
  
      }

      }catch (Exception $e) 
      {
          // Error en algun momento.
             ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
      }
      
}

if($accion == 26)
{
	$id_venta = $_POST['idFactura'];
		$sql = "SELECT id_venta as id_venta,emision.formato AS formato,ventas.tipo_documento as tipo_documento from ventas INNER JOIN `emision` emision 
		ON emision.`id` = ventas.`codigo_lug` where id_empresa='".$sesion_id_empresa."' and ventas.id_venta=$id_venta ;";
		$resp = mysql_query($sql);
		$entro=0;
		while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
		{
			$datos['id']=$row["id_venta"];
			$datos['formato']=$row["formato"];
			$datos['tipo_documento']=$row["tipo_documento"];
		}
		echo json_encode($datos);
	
}
if($accion == "30")
{

  $response= [];
  $response['kardes']='';
   $response['venta']='';
  $response['ruc_transportista']='';
  $response['productos_transportista']='';
  $response['ventas_transportista'] ='';
  $response['clientes_transportista'] = '';
  $cuenta='';
  $modoFacturacion=100;
  $ruc = $_POST['ruc']; 

  if( $ruc !='' ){
	$sqlTransportista ="SELECT `id_empresa`, `ruc`, `contribuyente`, `nombre`, `razonSocial`, `direccion`, `pais`, `provincia`, `id_ciudad`, `telefono1`, `telefono2`, `email`, `pag_web`, `imagen`, `fecha_inicio`, `informacion_general`, `perfil_empresa`, `descripcion`, `mision`, `vision`, `actividad_empresa`, `codigo_empresa`, `caracter_identificacion`, `autorizacion_sri`, `id_tipo_empresa`, `estado`, `Redondeo`, `Cliente_id`, `Ocontabilidad`, `FElectronica`, `clave`, `cod_aula`, `leyenda`, `leyenda2`, `leyenda3`, `leyenda4`, `limiteFacturas`, `distribuidor`, `URL`, `rimpe`, `agente`, `formaPago`, `condicionesPago`, `pago`, `observacion`, `planOriginal`, `nombreContador` FROM `empresa`   WHERE ruc=$ruc LIMIT 1"; 
	
	$resutTransportista = mysql_query($sqlTransportista);
	$numFilasTransportista = mysql_num_rows( $resutTransportista );
	$id_empresa_transportista = 0;
	
	if($numFilasTransportista==0){
		$response['ruc_transportista']= 'El transportista no tiene una empresa.';
		echo json_encode($response) ;
		exit;
	}else{
		while ($rowT = mysql_fetch_array($resutTransportista) ) {
			$id_empresa_transportista = $rowT['id_empresa'];
			
		}
		$sqlPeriodoContable = "SELECT `id_periodo_contable`, `fecha_desde`, `fecha_hasta`, `estado`, `ingresos`, `gastos`, `id_empresa` FROM `periodo_contable` WHERE id_empresa=$id_empresa_transportista";
		$resultPeriodoContable = mysql_query($sqlPeriodoContable);
		while( $rowPC = mysql_fetch_array($resultPeriodoContable) ){
			$sesion_id_periodo_contable_transportista = $rowPC['id_periodo_contable'];
		}
		$response['periodocontable_transportista']= $sesion_id_periodo_contable_transportista;
		
	}
	$response['id_empresa_transportista']=$id_empresa_transportista;

	if($id_empresa_transportista != 0){
		$sql_servicio_facturar = "
	SELECT
		productos.`id_producto` AS productos_id_producto,
		productos.`producto` AS productos_nombre,
		productos.`precio1` AS productos_precio1,
		productos.`precio2` AS productos_precio2,
		productos.`id_empresa` AS productos_id_empresa,
		productos.`iva` AS productos_iva,
		productos.`codigo` AS productos_codigo,
		productos.`tipos_compras` AS productos_tipos_compras,
		productos.`stock` AS productos_stock,
		productos.`id_cuenta` AS productos_id_cuenta,
		productos.`grupo` AS productos_grupo,
		productos.`codigo` AS productos_codigo,
		 productos.`proyecto` AS productos_proyecto,
		centro_costo.`id_centro_costo` AS centro_id,
		centro_costo.`descripcion` AS centro_descripcion,
		categorias.`id_categoria` AS categorias_id_categoria,
		categorias.`categoria` AS categorias_categoria,
		categorias.`id_empresa` AS categorias_id_empresa,
		productos.`id_empresa` AS productos_id_empresa,
		'' AS bodega_cantidad,
	   '' AS bodega_idBodega,
	   '' AS bodegas_id,
		'' AS bodega_detalle
	FROM
		`productos`
	INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
	INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
	
	WHERE
		  productos.id_empresa = '".$id_empresa_transportista."' AND productos.`tipos_compras` = '2' AND productos.codigo = '001'
	 GROUP BY  productos.codigo LIMIT 1;";
		$result_servicio_facturar = mysql_query($sql_servicio_facturar);
		$numFilasProductos = mysql_num_rows($result_servicio_facturar);
		if($numFilasProductos==0){
			$response['sql_productos_transportista']= $sql_servicio_facturar;
			$response['productos_transportista']= 'La empresa no tiene servicio para facturar.';
			echo json_encode($response) ;
			exit;
		}else{
			while ($rowPT = mysql_fetch_array($result_servicio_facturar) ) {
				$id_servicio_transportista = $rowPT['id_producto'];
				$id_iva = 0;
                $iva = 0;

				if ($rowPT["productos_iva"]=='Si'){
					$sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$id_empresa_transportista."';";
                    $result2 = mysql_query($sqliva);
					while ($row2 = mysql_fetch_array($result2)) {
						$id_iva = $row2["id_iva"];
						$iva = $row2["iva"];
					}					
				}
						
				$txtIdServicio = $rowPT["productos_id_producto"];
				$txtCodigoServicio = $rowPT["productos_codigo"];
				$txtDescripcionS = addslashes($rowPT["productos_nombre"]);
				$txtIdIvaS = $id_iva;
				$txtValorUnitarioS= $rowPT["productos_precio1"];

				$txtValorUnitarioS= $rowPT["productos_precio1"];
				$txtValorUnitarioShidden = $rowPT["productos_precio1"];
				$txtIvaS= $iva;
				$txtTipoProductoS=  $iva;
				$txtTipoS= $rowPT["productos_tipos_compras"];

				$iVA120= $rowPT["productos_iva"];
				$idbod= $rowPT["centro_id"];
				$cuenta= $rowPT["productos_id_cuenta"];
				$bod= $rowPT["centro_descripcion"];
				$bodegaCantidad= $rowPT["bodega_idBodega"];

				$cantidadEnBodega= $rowPT["bodega_cantidad"];
				$id_proyecto= $rowPT["productos_proyecto"];
			}
		}


		$sql_ventas_transportista ="SELECT `id_venta`, `fecha_venta`, `estado`, `total`, `sub_total`, `sub0`, `sub12`, `descuento`, `propina`, `numero_factura_venta`, `fecha_anulacion`, `descripcion`, `id_iva`, `montoIce`, `id_vendedor`, `id_cliente`, `id_empresa`, `tipo_documento`, `codigo_pun`, `codigo_lug`, `id_forma_pago`, `id_usuario`, `Retfuente`, `Comentario`, `Comentario2`, `Autorizacion`, `FechaAutorizacion`, `ClaveAcceso`, `FechaInicio`, `FechaFin`, `DirDestino`, `DirOrigen`, `MotivoTraslado`, `Retiva`, `Vendedor_id`, `MotivoNota`, `Otros`, `numero_cierre`, `id_estudiante`, `id_inmueble`, `tipo_inco_term`, `lugar_inco_term`, `pais_origen`, `puerto_embarque`, `puerto_destino`, `pais_destino`, `pais_adquisicion`, `numero_dae`, `numero_transporte`, `flete_internacional`, `seguro_internaiconal`, `gastos_aduaneros`, `gastos_transporte`, `xml`, `valorModificacion` FROM `ventas` WHERE id_empresa=$id_empresa_transportista 	and tipo_documento='1' ORDER BY  numero_factura_venta DESC LIMIT 1 ";
		$sql_result_transportista = mysql_query($sql_ventas_transportista);
		$numFilasVentas = mysql_num_rows($result_servicio_facturar);
		if($numFilasVentas==0){

			$sql_emision_transportista="SELECT
				establecimientos.`id` as establecimiento_id,
				establecimientos.`id_empresa`,
				establecimientos.`codigo` as establecimiento_codigo,
				establecimientos.`direccion`,
				emision.id AS emision_id,
				emision.codigo as emision_codigo,
				emision.numFac,
				emision.SOCIO
			FROM
				`establecimientos`
			INNER JOIN emision ON emision.id_est = establecimientos.id
			WHERE establecimientos.id_empresa=$id_empresa_transportista";
			$result_emision_transportista = mysql_query($sql_emision_transportista);
			$numFilasEmision = mysql_num_rows($result_emision_transportista);
			if($numFilasEmision==0){
				$response['emision_transportista']= 'La empresa no tiene punto de emision creado.';
				echo json_encode($response) ;
				exit;
			}else{
				while ($rowPE = mysql_fetch_array($result_emision_transportista) ) {
					$numero_factura_venta_transportista = $rowPE['numFac'];
					$codigo_pun_transportista = $rowPE['establecimiento_id'];
					$codigo_lug_transportista = $rowPE['emision_id'];
			       
				}
				$numero_factura_venta_transportista++;
			}

			
		}else{
			while ($rowVT = mysql_fetch_array($result_servicio_facturar) ) {
				$id_venta_transportista = $rowVT['id_venta'];
				$id_vendedor_transportista = $rowVT['id_vendedor'];
				$codigo_pun_transportista = $rowVT['codigo_pun'];
				$codigo_lug_transportista = $rowVT['codigo_lug'];
				$descripcion_transportista = $rowVT['descripcion'];
				$id_usuario_transportista = $rowVT['id_usuario'];
				$vendedor_id_tabla_transportista = $rowVT['id_usuario'];

				$numero_factura_venta_transportista = $rowVT["numero_factura_venta"];
			}
			$numero_factura_venta_transportista++;
			if( trim($codigo_pun_transportista)=='' || trim($codigo_lug_transportista)=='' ){
				$sql_emision_transportista="SELECT
				establecimientos.`id` as establecimiento_id,
				establecimientos.`id_empresa`,
				establecimientos.`codigo` as establecimiento_codigo,
				establecimientos.`direccion`,
				emision.id AS emision_id,
				emision.codigo as emision_codigo,
				emision.numFac,
				emision.SOCIO
			FROM
				`establecimientos`
			INNER JOIN emision ON emision.id_est = establecimientos.id
			WHERE establecimientos.id_empresa=$id_empresa_transportista";
			$result_emision_transportista = mysql_query($sql_emision_transportista);
			$numFilasEmision = mysql_num_rows($result_emision_transportista);
			if($numFilasEmision==0){
				$response['emision_transportista']= 'La empresa no tiene punto de emision creado.';
				echo json_encode($response) ;
				exit;
			}else{
				while ($rowPE = mysql_fetch_array($result_emision_transportista) ) {
					$numero_factura_venta_transportista = $rowPE['numFac'];
					$codigo_pun_transportista = $rowPE['establecimiento_id'];
					$codigo_lug_transportista = $rowPE['emision_id'];
		
				}
				$numero_factura_venta_transportista++;
			}
			}
			$sqlUsuario="SELECT

			usuarios.`id_usuario` AS usuarios_id_usuario,
			usuarios.`id_empleado` AS usuarios_id_empleado,
			usuarios.`login` AS usuarios_login,
			usuarios.`password` AS usuarios_password,
			usuarios.`tipo` AS usuarios_tipo,
			usuarios.`estado` AS usuarios_estado,
			usuarios.`fecha_registro` AS usuarios_fecha_registro,
			usuarios.`permisos` AS usuarios_permisos,
			usuarios.`id_punto` AS usuarios_id_punto,
			usuarios.`id_est` AS usuarios_id_est,
			
			usuarios.`id_permiso_asiento_contable` AS usuarios_id_permiso_asiento_contable,
			usuarios.`id_permiso_plan_cuenta` AS usuarios_id_permiso_plan_cuenta,
			usuarios.`reportes_contables` AS usuarios_reportes_contables,
			usuarios.`id_permisos_bancos` AS usuarios_id_permisos_bancos,
			
			emision.`id` AS emision_id,
			emision.`id_est` AS emision_id_est,
			emision.`ambiente` AS emision_ambiente,
			emision.`tipoFacturacion` AS emision_tipoFacturacion,
			emision.`tipoEmision` AS emision_tipoEmision,
			emision.`codigo` AS emision_codigo,
			emision.`SOCIO` AS emision_SOCIO,
			establecimientos.`id` AS establecimiento_id,
			establecimientos.`codigo` AS establecimiento_codigo
			
		   FROM
		   
		   `usuarios` usuarios 
		   INNER JOIN `emision` emision ON  usuarios.`id_punto`=emision.`id` 
		   INNER JOIN `establecimientos` establecimientos ON  establecimientos.`id`=emision.`id_est` 
	   
		   WHERE usuarios.`id_empresa`='".$id_empresa_transportista."'";
		   if($id_usuario_transportista!=''){
		         $sqlUsuario .="  AND  usuarios.`id_usuario`=$id_usuario_transportista  "; 
		   }
		
		   $result_usuario = mysql_query($sqlUsuario);
		   while($rowUsuario = mysql_fetch_array($result_usuario)  ){
				$emision_tipoEmision = $rowUsuario['emision_tipoEmision'];
				$id_usuario_transportista = $rowUsuario['usuarios_id_usuario'];
		   }
		}
		$ruc_empresa= $_SESSION['sesion_empresa_ruc'];
		$sql_clientes_transportista = "SELECT `id_cliente`, `nombre`, `apellido`, `direccion`, `cedula`, `telefono`, `movil`, `fecha_nacimiento`, `email`, `estado`, `id_ciudad`, `fecha_registro`, `numero_cargas`, `estado_civil`, `tipo`, `numero_casa`, `id_empresa`, `id_vendedor`, `caracter_identificacion`, `reponsable`, `limite_credito`, `descuento`, `dias_plazo`, `tipo_precio`, `observacion`, `tipo_cliente`, `prop_nombre`, `prop_telefono`, `prop_email`, `empresaCliente`, `nacionalidad`, `razonSocial`, `credito`, `contribuyente_especial`, `zona`, `codigo_interno`, `fecha_constitucion`, `ciudad2`, `direccion2`, `zona2`, `capital_suscrito`, `grupo_seciconal`, `delegado`, `oficial`, `id_membresia` FROM `clientes` WHERE cedula='".$ruc_empresa."' AND  id_empresa=$id_empresa_transportista";
		$result_clientes_transportista = mysql_query( $sql_clientes_transportista );
		$numFilas_clientes_transportista = mysql_num_rows( $result_clientes_transportista );
		if($numFilas_clientes_transportista==0){
			$response['clientes_transportista']= 'La empresa no tiene clientes con el ruc de la empresa para facturar.';
			echo json_encode($response) ;
			exit;
		}else{
			$id_cliente_transportista ='';
			$nombre_cliente_transportista = '';
			while ($rowCT = mysql_fetch_array($result_clientes_transportista) ) {
				$id_cliente_transportista = $rowCT['id_cliente'];
				$nombre_cliente_transportista = $rowCT['nombre'].' '.$rowCT['apellido'];
			}
		}
	}
  }
  
  // GUARDAR FACTURA VENTA PAGINA: nuevaFacturaVenta.php
  try 
  {
	  
		  $sqlEmpresa="SELECT limiteFacturas from  `empresa`  WHERE id_empresa='".$id_empresa_transportista."' ";
		  $resultEmpresa=mysql_query($sqlEmpresa) or die();
		  //$response['consulta']['sql'][]= $sqlEmpresa;
		  //$response['consulta']['ejecucion'][]= ($resultEmpresa)?'Si':'No';
		  
		  while($rowLimite=mysql_fetch_array($resultEmpresa))//permite ir de fila en fila de la tabla
		  {
			  $limite=$rowLimite['limiteFacturas'];
		  }
		  
			  
		  if($limite>=0){    
			  $limite3 = '1';	
		  }else {	  
			  $limite3 = '2';	
		  }
		  $cmbTipoDocumentoFVC='1';
		  $entro=1;
  }
  catch(Exception $ex) 
  {
	  ?> <div class="alert alert-warning"><p>Error al verificar la factura 
		  <?php echo "".$ex ?></p></div> 
	  <?php 
  }
  
  
  if ($entro == 1) {
 
	  if ($limite3 == 1) {
		  try
		  {
			  $cmbTipoDocumentoFVC='1';
			  
			  $numero_factura= $numero_factura_venta_transportista ;
			  $fecha_venta= $_POST['fecha'];		
			  $id_cliente= $id_cliente_transportista;
			  $estado = "Activo";
  // 			$cmbIdVendedor=$_POST['cmbIdVendedorFVC'];
			  $cmbIdVendedor=0;
			  $cmbEst = $codigo_pun_transportista;
			  $cmbEmi = $codigo_lug_transportista;
			  $txtNombreFVC=$nombre_cliente_transportista;
			  $idFormaPago=0;
			  $txtCuotasTP=0;

			  $total=floatval($txtValorUnitarioS)+ (floatval($txtValorUnitarioS)* ($iva/100) );
			  $sub_total=$txtValorUnitarioS;
			  $sub_total0=$txtValorUnitarioS;
			  $sub_total12=0;
			  $txtIva= (floatval($txtValorUnitarioS)* ($iva/100) );
			  $txtTotalIvaFVC=(floatval($txtValorUnitarioS)* ($iva/100) );
			  $totalCobrar = $total;

			  $descuento=0;
			  $propina=0;
			  $facAn=0;
			  $motivo ='';
			  $txtDescripcion= '';
			  $txtContadorFilas=1;
			  $txtCambioFP=0;
			  $txtContadorAsientosAgregadosFVC=1;
			$totalAnticipo =0;
			  
			  if($id_cliente!="" && $txtIva!="" && $numero_factura !="" )
			  {            
				  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$id_empresa_transportista."';";
				  $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error">
				  <p>Error: '.mysql_error().' </p></div>  ');
				  //$response['consulta']['sql'][]= $sqlIva1;
				  //$response['consulta']['ejecucion'][]=  ($resultIva1)?'Si':'No';
				  $iva=0;
				  while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
				  {
					  $iva=$rowIva1['iva'];
					  $txtIdIva=$rowIva1['id_iva'];
					  $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
				  }
			  }

			  if ($cmbTipoDocumentoFVC==1||$cmbTipoDocumentoFVC==41)
			  {
				  if($id_cliente!="" && $numero_factura !=""  )
				  {       
					  $vendedor_id = 0;
			
					  $sql="insert into ventas (fecha_venta,       estado,        total, sub_total,sub0,sub12,descuento,propina,numero_factura_venta, fecha_anulacion, descripcion, id_iva,        id_vendedor, id_cliente, id_empresa, tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario,vendedor_id_tabla) values ( '".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$sub_total0."','".$sub_total12."','".$descuento."','".$propina."','".$numero_factura."',NULL,         '".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$id_empresa_transportista."', '".$cmbTipoDocumentoFVC."', '".$cmbEst."', '".$cmbEmi."','".$idFormaPago."', '".$id_usuario_transportista."','0');";

					  $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
					  //$response['consulta']['sql'][]= $sql;
					  //$response['consulta']['ejecucion'][]=  ($result)?'Si':'No';
					  $id_venta=mysql_insert_id();
					  $response['venta']= $id_venta;

					$cantidad = 1;
					$idServicio = $txtIdServicio;
					$idKardex =	$txtIdServicio;
					$valorUnitario = $txtValorUnitarioShidden;
					$valorTotal = $txtValorUnitarioShidden;
							  
					$id_tipoP =$txtTipoS;
				
					$idBod = $idbod;
					$txtdesc = 0;
					$txtdetalle2='';
					$bodegaCantidad=trim($bodegaCantidad)==''?0:$bodegaCantidad;
					$txtCodigoServicio=$txtCodigoServicio;
					$txtDescripcionS = trim($txtDescripcionS);

					if ($id_tipoP == "2" && $txtDescripcionS!='') {
						$sql3 = "update productos set producto='".$txtDescripcionS."' where id_empresa='" . $id_empresa_transportista . "' and id_producto='" . $idServicio . "';";
						$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>No se actualizo el nombre del producto ' . mysql_error() . ' </p></div>  ');
						//$response['consulta']['sql'][]= $sql3;
						//$response['consulta']['ejecucion'][]= ($resp3)?'Si':'No';
					}
							  

					$sqlCentem = "SELECT establecimientos.centro_costo FROM `establecimientos` WHERE  id= '" . $cmbEst . "'";
					$resultCentm = mysql_query($sqlCentem);
					//$response['consulta']['sql'][]= $sqlCentem;
					//$response['consulta']['ejecucion'][]=  ($resultCentm)?'Si':'No';
					$numFilasCetm = mysql_num_rows($resultCentm);
					if($numFilasCetm>0){
						while($rowCetm = mysql_fetch_array($resultCentm) ){
							$id_proyecto= $rowCetm['centro_costo'];
						}
									  
					}else{
						$id_proyecto=0;
					}
								  
					$sql2 = "insert into detalle_ventas ( idBodega,idBodegaInventario,cantidad, estado, v_unitario,descuento ,v_total, 	id_venta, id_servicio,detalle,id_kardex,tipo_venta, id_empresa,id_proyecto) values ('".$idBod."','".$bodegaCantidad."','".$cantidad."','".$estado."','".$valorUnitario."','".$txtdesc."','".$valorTotal."','".$id_venta."', '".$idServicio."','".$txtdetalle2."', '".$idKardex."', '".$id_tipoP."','".$id_empresa_transportista."', '".$id_proyecto."' );";  
					$resp2 = mysql_query($sql2) or die('<div class="alert alert-danger"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
					//$response['consulta']['sql'][]= $sql2;
					//$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
					$id_detalle_venta=mysql_insert_id();
					$response['detalle_venta'][]= $id_detalle_venta;

					  if($limite==0){
						  $limite=0;
					  }else if($limite==1){
						  $limite=$limite-2;
					  }else{
						  $limite=$limite-1;
					  }

					  $sqlNumFac ="update emision set numFac='".$numero_factura."' where id ='".$cmbEmi."' ";
					  $result=mysql_query($sqlNumFac) or die('<div class="transparent_ajax_error"><p>Error al actualizar emision: '.mysql_error().'</p></div>  ');
					  //$response['consulta']['sql'][]= $sqlNumFac;
					  //$response['consulta']['ejecucion'][]=  ($result)?'Si':'No';

					  $sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$id_empresa_transportista' ";
					  $resultEmpresa2=mysql_query($sqlEmpresa2) or die();
					  //$response['consulta']['sql'][]= $sqlEmpresa2;
					  //$response['consulta']['ejecucion'][]=  ($resultEmpresa2)?'Si':'No';
					  

	
				  // Crear el asiento		
  
				  // if ($sesion_tipo_empresa=="6")
				  // {
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
						   WHERE periodo_contable.`id_empresa` ='".$id_empresa_transportista."' GROUP BY periodo_contable.`id_periodo_contable` ;";
						  $resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						  $numero_asiento=0;
						  while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
						  {
							  $numero_asiento=$rowMNA['max_numero_asiento'];
						  }
						  $numero_asiento++;
						  $response['num_asiento'][]= $numero_asiento;
					  }
					   catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
		  
		  
					  $tipo_comprobante = "Diario"; 
				  
					  try
					  {
						  $tipoComprobante = $tipo_comprobante;
						  $consulta7="SELECT
							  max(numero_comprobante) AS max_numero_comprobante
						  FROM
							  `comprobantes` comprobantes
							  WHERE comprobantes.`id_empresa` = '".$id_empresa_transportista."' AND  comprobantes.`tipo_comprobante` = '".$tipoComprobante."' ;";
								  $result7=mysql_query($consulta7) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
								  $numero_comprobante = 0;
							  while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
							  {
								  $numero_comprobante=$row7['max_numero_comprobante'];
							  }
							  $numero_comprobante ++;
							  $response['num_comprobante'][]= $numero_comprobante;
					  }
					  catch (Exception $e) 
					  {
						  // Error en algun momento.
						 ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
					  }

					  $fecha= date("Y-m-d h:i:s");
					  $descripcion = "Factura de venta #".$numero_factura." realizada a ".$txtNombreFVC;
					  
					  $debe = $total;
					  $debe2 = $descuento;
					  $total_debe = $debe + $debe2;
					  $haber1 = $sub_total;
					  $haber2 = $txtTotalIvaFVC;
					  $total_haber = $haber1 + $haber2 + $propina;
					  $tipo_mov="F";
	  
				  //GUARDA EN  COMPROBANTES
					  $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$id_empresa_transportista."')";
					  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					  //$response['consulta']['sql'][]= $sqlC;
					  //$response['consulta']['ejecucion'][]=  ($respC)?'Si':'No';
					  $id_comprobante=mysql_insert_id();
					  $response['comprobante'][]= $id_comprobante;
					  
				  $sqlCentroCosto = "SELECT `id`, `id_empresa`, `codigo`, `direccion`, `centro_costo` FROM `establecimientos` WHERE id=$cmbEst ;";
				  $resultCentroCosto = mysql_query($sqlCentroCosto);
				  //$response['consulta']['sql'][]= $sqlCentroCosto;
				  //$response['consulta']['ejecucion'][]=   ($resultCentroCosto)?'Si':'No';
				  $centroCosto=0;
				  while($rowccc = mysql_fetch_array($resultCentroCosto) ){
					  $centroCosto= $rowccc['centro_costo'];
				  }

				  //GUARDA EN EL LIBRO DIARIO
					  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
					  total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta,centroCosto) 
					  values ('".$sesion_id_periodo_contable_transportista."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					  '".$id_comprobante."','".$tipo_mov."','".$numero_factura."' ,'".$centroCosto."')";
					  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
					  $id_libro_diario=mysql_insert_id();
					  //$response['consulta']['sql'][]= $sqlLD;
					  //$response['consulta']['ejecucion'][]=  ($resp)?'Si':'No';
					  $response['libro_diario'][]= $id_libro_diario;

					  $idPlanCuentas[1] = '';
					  $debeVector[1] = 0;  
					  $haberVector[1] = 0;
					  $lin_diario=0;
					  $valor[$lin_diario]=0;
					  $ident=0;
					  $lin_diario=$lin_diario+1;
					  $debeVector[$lin_diario]=$total;
					  $haberVector[$lin_diario]=0; 	
					  $existeCuota='';
					  
					  $sqlFormaPago = "SELECT
					  formas_pago.`id_forma_pago` AS formas_pago_id,
					  formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta
				  FROM
					  `formas_pago` formas_pago
				  INNER JOIN `plan_cuentas` plan_cuentas ON
					  formas_pago.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta` AND plan_cuentas.`id_empresa` = '".$id_empresa_transportista."'
				  WHERE
				  formas_pago.`id_tipo_movimiento`=1 LIMIT 1 ";
				  $resultFormaPago = mysql_query($sqlFormaPago);
				  $formaPago='';
				  while($rowFP = mysql_fetch_array($resultFormaPago)){
					  $formaPago = $rowFP['formas_pago_id'];
  
					  $formaPagoId[$lin_diario]= $rowFP['formas_pago_id'];
					  $idPlanCuentas[$lin_diario]=$rowFP['formas_pago_id_plan_cuenta'];
				  }
				  $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) VALUES     ('".$formaPago."','0','".$id_venta."','".$id_empresa_transportista."','".$total."','1', NULL );";
				  // echo $sqlforma;
  				$respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
				  if($respForma){
					$sql3="update ventas set id_forma_pago='01' where id_venta='".$id_venta."' ";
					$resp3 = mysql_query($sql3) or die(mysql_error());  
								}
				$tot_ventas=0;
				$tot_servicios=0;
				$tot_costo=0;
					  
			
				$idProducto=$txtIdServicio;
							  
				if ($id_tipoP == "2" ){
					$sqlPlanCuenta= "SELECT  
					productos.`id_producto` AS productos_id_producto, 
					productos.`producto` AS productos_nombre,
					productos.`grupo` AS productos_grupo, 
					centro_costo.`id_centro_costo` AS centro_id, 
					centro_costo.`descripcion` AS centro_descripcion, 
					centro_costo.`id_cuenta` AS productos_id_cuenta,
					plan_cuentas.codigo,
					plan_cuentas.nombre
					FROM  `productos` 
					INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
					INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = centro_costo.id_cuenta
					WHERE productos.id_producto=$idProducto ";
					$resultPlanCuentas= mysql_query($sqlPlanCuenta);
					//$response['consulta']['sql'][]= $sqlPlanCuenta;
					//$response['consulta']['ejecucion'][]=   ($resultPlanCuentas)?'Si':'No';
					$planCuentaServicio = 0;
					while($rowPC = mysql_fetch_array($resultPlanCuentas)){
						$planCuentaServicio = $rowPC['productos_id_cuenta'];
					}
					$tot_servicios=$tot_servicios+ $txtValorUnitarioShidden;
					}

					  
				  try 
				  {
					  $sqlMercaderia="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas	
					  WHERE `id_tipo_movimiento` =10 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
					  formas_pago.id_empresa='".$id_empresa_transportista."'  ";
					  $resultMercaderia=mysql_query($sqlMercaderia);
					  //$response['consulta']['sql'][]= $sqlMercaderia;
					  //$response['consulta']['ejecucion'][]=  ($resultMercaderia)?'Si':'No';
					  $idcodigo_v=0;
					  while($row=mysql_fetch_array($resultMercaderia))//permite ir de fila en fila de la tabla
					  {
						  $idcodigo_v=$row['codigo_plan_cuentas'];
					  }
					  $idcodigo_v;
				  }
				  catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
				  
				  try 
				  {
					  $sqlServicio="SELECT plan_cuentas.codigo as codigo_plan_cuentas FROM formas_pago,plan_cuentas 
					  WHERE `id_tipo_movimiento` =11 and plan_cuentas.id_plan_cuenta=formas_pago.id_plan_cuenta and 
					  formas_pago.id_empresa='".$id_empresa_transportista."'  ";
					  $resultServicio=mysql_query($sqlServicio);
					  //$response['consulta']['sql'][]= $sqlServicio;
					  //$response['consulta']['ejecucion'][]= ($resultServicio)?'Si':'No';
					  $idcodigo_s=0;
					  while($row=mysql_fetch_array($resultServicio))//permite ir de fila en fila de la tabla
					  {
						  $idcodigo_s=$row['codigo_plan_cuentas'];
					  }
					  $idcodigo_s;
  
				  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }				
  
						  
					  $sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo
							from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$id_empresa_transportista."' and
							  ( plan_cuentas.`codigo` ='".$idcodigo_v."' or  plan_cuentas.`codigo` ='".$idcodigo_s."')"  ;
	  
					  $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
					  //$response['consulta']['sql'][]= $sql;
					  //$response['consulta']['ejecucion'][]= ($resultS)?'Si':'No';			
					  $plan_id_cuenta_vta=0;
					  $plan_id_cuenta_servicio=0;
					  while($rowS=mysql_fetch_array($resultS))//permite ir de fila en fila de la tabla
					  {
						  if ($rowS['plan_cuentas_codigo']==$idcodigo_v)
							  {
								  $plan_id_cuenta_vta=$rowS['plan_cuentas_id_cuenta'];
							  }
						  if ($rowS['plan_cuentas_codigo']==$idcodigo_s)
							  {
								  $plan_id_cuenta_servicio=$rowS['plan_cuentas_id_cuenta'];
							  }
  
							  
					  }
					  
					  
					  $response['tot_ventas']= $tot_ventas;
					  if ($tot_ventas!=0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $plan_id_cuenta_vta;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$tot_ventas;
						  
  
					  }
					  
					  $response['tot_servicios']= $tot_servicios;
					  if ($tot_servicios!=0)
					  {
						  $lin_diario=$lin_diario+1;
					  // 	$idPlanCuentas[$lin_diario]= $plan_id_cuenta_servicio;
							  $idPlanCuentas[$lin_diario]= $planCuentaServicio;
							  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$tot_servicios;
						  
  
					  }
					  
					  
					  try {
							  $sqlDescuento="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago WHERE `id_tipo_movimiento` =14 
							  and formas_pago.id_empresa='".$id_empresa_transportista."'  ";
							  $resultDescuento=mysql_query($sqlDescuento);
							  //$response['consulta']['sql'][]= $sqlDescuento;
							  //$response['consulta']['ejecucion'][]= ($resultDescuento)?'Si':'No';		
							  $idcodigo_d=0;
							  while($row=mysql_fetch_array($resultDescuento))//permite ir de fila en fila de la tabla
							  {
								  $idcodigo_d=$row['codigo_plan_cuentas'];
							  }
							  $idcodigo_d;
		  
					  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
					  
					  
					  
					  if ($descuento!=0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $idcodigo_d;
						  $debeVector[$lin_diario]=$descuento;
						  $haberVector[$lin_diario]=0;
					  }
					  
					  
					  try {
							  $sqlPropina="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago 
							  WHERE `id_tipo_movimiento` =15 and formas_pago.id_empresa='".$id_empresa_transportista."'  ";
							  $resultPropina=mysql_query($sqlPropina);
							  //$response['consulta']['sql'][]= $sqlPropina;
							  //$response['consulta']['ejecucion'][]= ($resultPropina)?'Si':'No';	
							  $idcodigo_p=0;
							  while($row=mysql_fetch_array($resultPropina))//permite ir de fila en fila de la tabla
							  {
								  $idcodigo_p=$row['codigo_plan_cuentas'];
							  }
							  $idcodigo_p;
		  
					  }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }	
					  
					  
					  if ($propina!=0)
					  {
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]= $idcodigo_p;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$propina;
					  }
					  
					  if ($txtTotalIvaFVC!=0)
					  {
  
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$txtTotalIvaFVC;
  
					  }

					  for($i=1; $i<=$lin_diario; $i++)
					  {
						  
  
						  if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
						  {
							  
							  $sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
							  ('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable_transportista."');";
							  $resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							  $id_detalle_libro_diario=mysql_insert_id();
							  //$response['consulta']['sql'][]= $sqlDLD;
							  //$response['consulta']['ejecucion'][]=  ($resp2)?'Si':'No';
							  $response['detalle_libro_diario'][]= $id_detalle_libro_diario;
							  
							  $sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable_transportista."';";
							  $result5=mysql_query($sql5);
							  //$response['consulta']['sql'][]= $sql5;
							  //$response['consulta']['ejecucion'][]= ($result5)?'Si':'No';
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
								  
								  try 
								  {
  
									  $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable_transportista."');";
									  $result6=mysql_query($sql6);
									  $id_mayorizacion=mysql_insert_id();
									  //$response['consulta']['sql'][]= $sql6;
									  //$response['consulta']['ejecucion'][]= ($result6)?'Si':'No';
									  $response['mayorizacion'][]= $id_mayorizacion;
								  }
								  catch(Exception $ex) 
								  { ?> <div class="transparent_ajax_error">
									  <p>Error en la insercion de la tabla mayorizacion: 
									  <?php echo "".$ex ?></p></div> <?php }
								  // FIN DE MAYORIZACION
							  }
						  }
					  }
					  
					  $response['tot_costo']= $tot_costo;
					
				  
			  
				// GUARDAR EN KARDEX
  
				  $sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
				  ('".$fecha_venta."','Venta','".$id_venta."', '".$id_empresa_transportista."')";
				  $resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				  $id_kardes=mysql_insert_id();
				  //$response['consulta']['sql'][]= $sqlk;
				  //$response['consulta']['ejecucion'][]= ($resultk)?'Si':'No';
				  $response['kardes']= $id_kardes;
				  $response['guardo']= 0;
				  if($result && $resp2)
				  {
					  $response['guardo']=1;
				
				  }

				  
				  

				  $response['modoFacturacion']= $modoFacturacion;
				  if($modoFacturacion=='200'){
					  
					  $emision_tipoEmision='F';
					  
				  }else{
					  
					  $emision_tipoEmision = $emision_tipoEmision;
				  }
				  $response['emision_tipoEmision']= $emision_tipoEmision;
				  
						  if ($emision_tipoEmision === 'E'){
							  genXml($id_venta);
							  
								  $sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
								  $result=mysql_query($sqli);
								  //$response['consulta']['sql'][]= $sqli;
								  //$response['consulta']['ejecucion'][]= ($result)?'Si':'No';
								  while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
								  {
									  $claveAcceso=$row['ClaveAcceso'];
								  }
								  if ($claveAcceso != ''){
									  $response['claveAcceso']= "SI";
									  // echo "SI";
									  }else{
										  $response['claveAcceso']= "NO";
										  // echo "NO";
									  }
							  
							  }
				  
							  
				  
				  }
				  else
				  {
				  if($txtCambioFP>0.0 )
				  {
				  ?> <div class='transparent_ajax_error'><p>Existe un saldo pendiente de cancelar <?php echo " ".mysql_error(); ?>;</p></div> <?php
					  
				  }
				  else
				  {
				  ?> <div class='transparent_ajax_error'><p>Error: Valor a cobrar incorrecto <?php echo " ".mysql_error(); ?>;</p></div> <?php
					  
				  }
			  }
	  
			  echo json_encode($response) ;
			  exit;
	  }
		  
		  }
		  catch (Exception $e) {
			  // Error en algun momento.
				 ?> <div class='transparent_ajax_error'><p>Error: <?php echo " ".$e; ?>;</p></div> <?php
		  }
	  } else {
		 echo "Limite de factura alcanzado, revise por favor con su proveedor";
	  }
  } else {
	  echo "La factura está repetida, revisa la numeración por favor";
  }


	  

  
  
  
}


if($accion == '99')
	{
$id_venta=$_POST['id_factura_venta'];
			$sql = "SELECT id_venta as id_venta,emision.formato AS formato,ventas.tipo_documento as tipo_documento from ventas INNER JOIN `emision` emision 
			ON emision.`id` = ventas.`codigo_lug` where id_empresa='".$sesion_id_empresa."' and ventas.id_venta=$id_venta ";
			$resp = mysql_query($sql);
			$entro=0;
			while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
			{
				$datos['id']=$row["id_venta"];
				$datos['formato']=$row["formato"];
				$datos['tipo_documento']=$row["tipo_documento"];
			}
			echo json_encode($datos);
		
	}
?>