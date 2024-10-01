<?php
// error_reporting(0);
	session_start();
	//Include database connection details
	require_once('../conexion.php');

	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    $response =[];
    $response['numeroCompra']= "";	
	If(is_uploaded_file($_FILES['files']['tmp_name'])!=""){
		
		
				    
	    $xml=file_get_contents($_FILES['files']['tmp_name']);
	    

	   $xml = str_replace(array('<![CDATA[ <?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>'),'', $xml);
	   
	   $xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="ISO-8859-1"?>'),'', $xml);
	   
	      $xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="UTF-8"?>',']]>'), '', $xml);
	      
        $xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="UTF-8"?> ',']]>'), '', $xml);
        
		$xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="utf-8" standalone="no"?>',']]>'),'', $xml);
		$xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="utf-8" standalone="yes"?>',']]>'),'', $xml);
		
        $xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="no"?>',']]>'),'', $xml);
		$xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',']]>'),'', $xml);
		
		$xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="utf-8"?>',']]>'),'', $xml);
		
		$xml = str_replace(array('<![CDATA[',']]>'),'', $xml);
		
		$xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="us-ascii" standalone="no"?>'),'', $xml);
		
		$xml = str_replace(array('<?xml version="1.0" encoding="UTF-8"?>'),'', $xml);

        $xml = str_replace(array('<?xml version="1.0" encoding="UTF-8" standalone="no"?>'),'', $xml);
        $xml = str_replace(array('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'),'', $xml);
        
        
        $xml = str_replace(array('<?xml version="1.0"?>'),'', $xml);
       
         $xml = str_replace(array('<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>'),'', $xml);
        
         
if($sesion_id_empresa==41){
    // echo $xml;
    // exit;
}
	    
	    $xml1 = new SimpleXMLElement($xml);
	    
        $comprobante = $xml1->comprobante ;

		$txtEmision=substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
		$numSerie=substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
        $secuencial=substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
        $txtRuc=substr($xml,strpos($xml,"<ruc>")+strlen("<ruc>"),strpos($xml,"</ruc>")-strlen("<ruc>")-strpos($xml,"<ruc>"));
        
        $impuestos_xml = '';
			 if(isset($xml1->comprobante->factura->infoFactura->totalConImpuestos->totalImpuesto)){
			    
				$impuestos_xml  = $xml1->comprobante->factura->infoFactura->totalConImpuestos->totalImpuesto;
			}else if( isset($xml1->infoFactura->totalConImpuestos->totalImpuesto) ){
				$impuestos_xml =$xml1->infoFactura->totalConImpuestos->totalImpuesto; 
			}
			
	    
			foreach ( $impuestos_xml as $totalImpuestoB) 
			{
			    $codigo_impuesto= $totalImpuestoB->codigo; 
				$codigoPorcentaje_impuesto= $totalImpuestoB->codigoPorcentaje;
				if($codigo_impuesto==2){
				    $sqlIm="SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa= '".$sesion_id_empresa."'  AND codigo='".$codigoPorcentaje_impuesto."' ";
    				$resultIm = mysql_query($sqlIm);
    				$numFilas= mysql_num_rows($resultIm);
    				if($numFilas==0){
    				    $porcentaje_impuesto = 0;
    				    if($codigoPorcentaje_impuesto==0){
    				        $porcentaje_impuesto = '0 %';
    				    }else if($codigoPorcentaje_impuesto==2){
    				        $porcentaje_impuesto = '12 %';
    				    }else if($codigoPorcentaje_impuesto==3){
    				        $porcentaje_impuesto = '14 %';
    				    }else if($codigoPorcentaje_impuesto==4){
    				        $porcentaje_impuesto = '15 %';
    				    }else if($codigoPorcentaje_impuesto==5){
    				        $porcentaje_impuesto = '5 %';
    				    }else if($codigoPorcentaje_impuesto==6){
    				        $porcentaje_impuesto = 'No Objeto de Impuesto';
    				    }else if($codigoPorcentaje_impuesto==7){
    				        $porcentaje_impuesto = 'Excento de IVA';
    				    }else if($codigoPorcentaje_impuesto==8){
    				        $porcentaje_impuesto = 'IVA diferenciado';
    				    }else if($codigoPorcentaje_impuesto==10){
    				        $porcentaje_impuesto = '13 %';
    				    }
    				     $response['advertencias'][]= "NO SE PUEDE GUARDAR LA COMPRA, NO ESTA CREADO EL IMPUESTO CON TARIFA ".$porcentaje_impuesto;
    				     echo json_encode($response);
                        exit;
    				}
				}
				
			}
	      
		$sqlProveedor="Select id_compra from compras a inner join proveedores b
			on a.id_proveedor=b.id_proveedor and a.id_empresa=b.id_empresa
			where a.id_empresa='".$sesion_id_empresa."' and b.ruc='".$txtRuc."' and txtEmision='".$txtEmision."' 
			and numSerie='".$numSerie."' and txtNum='".$secuencial."' and anulado='0'";
	    $result=mysql_query($sqlProveedor);
				
		$id_compra=0;
		while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
		{  
			$id_compra=$row['id_compra'];
		}
		if($id_compra > 0) 
		{
            $response['advertencias'][]= "COMPRA YA FUE REGISTRADA";	
		}
		else
		{
		    
			$txtNombreRepresentante=substr($xml,strpos($xml,"<razonSocial>")+strlen("<razonSocial>"),strpos($xml,"</razonSocial>")-strlen("<razonSocial>")-strpos($xml,"<razonSocial>"));
			
			$txtNombreComercial=substr($xml,strpos($xml,"<nombreComercial>")+strlen("<nombreComercial>"),strpos($xml,"</nombreComercial>")-strlen("<nombreComercial>")-strpos($xml,"<nombreComercial>"));
		
			$posInicio = strpos($xml, "<nombreComercial>");
			
			if ($posInicio !== false) {
                // Si la etiqueta existe, obtener su contenido
                $posFin = strpos($xml, "</nombreComercial>", $posInicio);
                // $txtNombreComercial = substr($xml, $posInicio + strlen("<nombreComercial>"), $posFin - $posInicio - strlen("<nombreComercial>"));
			    $txtNombreComercial=substr($xml,strpos($xml,"<nombreComercial>")+strlen("<nombreComercial>"),strpos($xml,"</nombreComercial>")-strlen("<nombreComercial>")-strpos($xml,"<nombreComercial>"));
                
            } else {
                
                $txtNombreComercial = $txtNombreRepresentante;              
            }

			$txtRuc=substr($xml,strpos($xml,"<ruc>")+strlen("<ruc>"),strpos($xml,"</ruc>")-strlen("<ruc>")-strpos($xml,"<ruc>"));
			$txtDireccion=substr($xml,strpos($xml,"<dirMatriz>")+strlen("<dirMatriz>"),strpos($xml,"</dirMatriz>")-strlen("<dirMatriz>")-strpos($xml,"<dirMatriz>"));
	    	$sqlProveedor="Select ruc from proveedores where ruc='".$txtRuc."' and id_empresa='".$sesion_id_empresa."'";
			$result=mysql_query($sqlProveedor);
			$fila=mysql_num_rows($result);
			if($fila>0) 
				{ 
				try 
		        {
					$sqlm="Select id_proveedor From proveedores where ruc='".$txtRuc."' and id_empresa='".$sesion_id_empresa."'";
					$result=mysql_query($sqlm);
					$id_proveedor=0;
                    while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {  
					$id_proveedor=$row['id_proveedor'];
					}
					$id_proveedor;
				}
				catch(Exception $ex) { 
                    $response['errores'][]= "Error al buscar proveedor: ".$ex; echo json_encode($response);exit; }
				} 
			else 
			{ 
				$sqlp="INSERT into proveedores ( nombre_comercial, nombre, direccion, ruc, telefono, movil, fax, email, web, observaciones, id_ciudad, id_plan_cuenta, autorizacion_sri, fecha_vencimiento, id_empresa, id_tipo_proveedor, tipo_pago, pasaporte, otro, tipo_sustento, tipo_comprobante, enlace_retencion_fuente, enlace_retencion_iva,rbCaracterIdentificacion,parteRel)
                    VALUES ('".$txtNombreComercial."','".$txtNombreRepresentante."','".$txtDireccion."','".$txtRuc."','12345', '1234','NULL', 'abcd@gmail.com', 'NULL', 'NULL','688','0', '0', '0000-00-00', '".$sesion_id_empresa."', '2', '1', '0', '0', '0', '0', '0', '0','04','NO'); ";            
// 			echo "proveedor----".$sqlp."</br>";
				$result1=mysql_query($sqlp) ;
				$id_proveedor=mysql_insert_id();
				if ($result1)
				{
                    $response['mensajes'][]=  "PROVEEDOR REGISTRADO";	
				}else{
                    $response['errores'][]=  "Error al guardar proveedor compra Fila 2: ".mysql_error();
                    echo json_encode($response);
                    exit;
                } 
			}
		
			$numeroAutorizacion=substr($xml,strpos($xml,"<numeroAutorizacion>")+strlen("<numeroAutorizacion>"),strpos($xml,"</numeroAutorizacion>")-strlen("<numeroAutorizacion>")-strpos($xml,"<numeroAutorizacion>"));
			$fecha=substr($xml,strpos($xml,"<fechaEmision>")+strlen("<fechaEmision>"),strpos($xml,"</fechaEmision>")-strlen("<fechaEmision>")-strpos($xml,"<fechaEmision>"));
		
		    $timestamp = strtotime(str_replace('/', '-', $fecha));
			
			$newDate1 = date("Y-m-d h:i:s", $timestamp );

			$sub_total=substr($xml,strpos($xml,"<totalSinImpuestos>")+strlen("<totalSinImpuestos>"),strpos($xml,"</totalSinImpuestos>")-strlen("<totalSinImpuestos>")-strpos($xml,"<totalSinImpuestos>"));
		   
			try {
					$sqlp="Select max(numero_factura_compra) From compras where id_empresa='".$sesion_id_empresa."' ";
					$resultp=mysql_query($sqlp);
					$numero_factura_compra=0;
					while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
					{
					   $numero_factura_compra=$rowp['max(numero_factura_compra)'];
					}
					$numero_factura_compra++;
				}
			catch(Exception $ex) 
				{  $response['errores'][]= "Error en el numero maximo de factura: ".$ex; echo json_encode($response);exit; }
				
			$txtEmision=substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
			$numSerie=substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
			$secuencial=substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
			$caducidad=substr($xml,strpos($xml,"<fechaAutorizacion>")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion>"));
				
			$codDoc=substr($xml,strpos($xml,"<codDoc>")+strlen("<codDoc>"),strpos($xml,"</codDoc>")-strlen("<codDoc>")-strpos($xml,"<codDoc>"));
			$importeTotal=substr($xml,strpos($xml,"<importeTotal>")+strlen("<importeTotal>"),strpos($xml,"</importeTotal>")-strlen("<importeTotal>")-strpos($xml,"<importeTotal>"));
			$totalConImpuestos=substr($xml,strpos($xml,"<totalConImpuestos>")+strlen("<totalConImpuestos>"),strpos($xml,"</totalConImpuestos>")-strlen("<totalConImpuestos>")-strpos($xml,"<totalConImpuestos>"));
			$totalDescuento=substr($xml,strpos($xml,"<totalDescuento>")+strlen("<totalDescuento>"),strpos($xml,"</totalDescuento>")-strlen("<totalDescuento>")-strpos($xml,"<totalDescuento>"));

			$totalImpuesto=substr($xml,strpos($xml,"<totalImpuesto>")+strlen("<totalImpuesto>"),strpos($xml,"</totalImpuesto>")-strlen("<totalImpuesto>")-strpos($xml,"<totalImpuesto>"));
		    
		    
		    $subTotal12=0;
		    $subTotal0=0;
		
			$i=0;	
			
			$impuestos_xml = '';
			 if(isset($xml1->comprobante->factura->infoFactura->totalConImpuestos->totalImpuesto)){
			    
				$impuestos_xml  = $xml1->comprobante->factura->infoFactura->totalConImpuestos->totalImpuesto;
			}else if( isset($xml1->infoFactura->totalConImpuestos->totalImpuesto) ){
				$impuestos_xml =$xml1->infoFactura->totalConImpuestos->totalImpuesto; 
			}
			
			$total_iva = 0;
			foreach ( $impuestos_xml as $totalImpuesto) 
			{
		
				$codigo_a[$i]= $totalImpuesto->codigo; 
				$codigoPorcentaje_a[$i]= $totalImpuesto->codigoPorcentaje;
				$descuentoAdicional_a[$i]= $totalImpuesto->descuentoAdicional;
				$baseImponible_a[$i]= $totalImpuesto->baseImponible;
				$valor_a[$i]= $totalImpuesto->valor;

			
				if ($codigo_a[$i]=='2') 
				{
					$total_iva += floatval( $valor_a[$i] ) ;  
					$iva= $valor_a[$i];

					if($codigoPorcentaje_a[$i]=='2'){
						$subTotal12= $baseImponible_a[$i];  
					}else{
						$subTotal12= 0;  
					}
					
				}
				else
				{
					$subTotal0=$baseImponible_a[$i];
					$iva=0;
				}
			
				$i=$i+1;
			}		

			try 
			{
				$sqlm="Select id_proveedor From proveedores where ruc='".$txtRuc."' and id_empresa='".$sesion_id_empresa."'";
                $result=mysql_query($sqlm);
                $id_proveedor=0;
				while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
				{
                    $id_proveedor=$row['id_proveedor'];
				}
				$id_proveedor;
			}	
			
			catch(Exception $ex) {  $response['errores'][]= "Error en  proveedores : ".$ex;  echo json_encode($response);exit;}
	
			$sql="INSERT INTO `compras`(`fecha_compra`, `total`, `sub_total`,         `subTotal0`,       `subTotal12`,`subTotalInvenarios`,`descuento` ,`exentoIVA`, `noObjetoIVA`, `id_iva`, `id_proveedor`,   `numero_factura_compra`, `id_empresa`, `numSerie`, `txtEmision`, `txtNum`, `autorizacion`, `caducidad`, `TipoComprobante`, `codSustento`,xml,total_iva) 
                       values  ('".$newDate1."','".$importeTotal."','".$sub_total."','".$subTotal0."','".$subTotal12."','".$sub_total."','".$totalDescuento."',0,0,'12','".$id_proveedor."','".$numero_factura_compra."','".$sesion_id_empresa."','".$numSerie."','".$txtEmision."','".$secuencial."','".$numeroAutorizacion."','".$newDate1."','".$codDoc."','01','1','".$total_iva."')  ";
		

			$result=mysql_query($sql);

			if($result) { 
                $response['mensajes'][]= "Encabezado Compra Registrado con exito";
                 $response['numeroCompra']= $numero_factura_compra;	
             }else{
                $response['errores'][]= "Error al guardar encabezado compra : ".mysql_error();
                echo json_encode($response);
                exit;
             }   


			 $detalles_xml = '';
			 if(isset($xml1->detalles->detalle)){
			    
				$detalles_xml  = $xml1->detalles->detalle;
			}else if( isset($xml1->comprobante->factura->detalles->detalle) ){
				$detalles_xml =$xml1->comprobante->factura->detalles->detalle; 
			}

			

			foreach ($detalles_xml  as $detalle) 
			{
			     //$response['mensajes'][]= "ejecuta detalle productos";
				$codPrincipal= $detalle->codigoPrincipal;
				$codAuxiliar= $detalle->codigoAuxiliar;
				$descripcion= $detalle->descripcion;
				$cantidad= $detalle->cantidad;
				$precio= $detalle->precioUnitario;
				

				// $sqlCP="SELECT `id_codProd`, `codigo_producto`, `codigo_proveedor`, `id_empresa`, `id_proveedor` 
				// FROM `codigosproductos` WHERE id_empresa= '".$sesion_id_empresa."' AND id_proveedor= '".$id_proveedor."' 
				// AND codigo_producto ='".trim($codPrincipal)."' ";
				// $resultCP= mysql_query($sqlCP);
				// $numFilasCP = mysql_num_rows($resultCP);
				//  $response['consulta'][]= $sqlCP;
				$sql1="SELECT id_categoria from categorias where id_empresa= '".$sesion_id_empresa."' and categoria='PRODUCTOS'";
				$id_categoria=0; 
				$result=mysql_query($sql1);
				while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
				{	  
					$id_categoria=$row['id_categoria'];
				}				

				$j=0;
				$tarifa=0;
				$iva_p="0";	
				foreach ($detalle->impuestos as $imp) 
						{
							$response['imp'][]= $imp;	
							$tarifa_b[$j]= $imp->impuesto->tarifa;
							$tarifa=$tarifa_b[$j];
							$codigo = $imp->impuesto->codigo;
							$codigoPorcentaje =$imp->impuesto->codigoPorcentaje;
							

							if ($codigo == 2  ) 
							{
							

									
									$iva_p=$tarifa;
							
								
							}
						}
					$sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa = '".$sesion_id_empresa."' AND iva= '".$iva_p."'";
					$resultImpuetos = mysql_query($sqlImpuesto);
					$numFilaImpuesto = mysql_num_rows($resultImpuetos);
					$sql_impuesto	='';
					if($numFilaImpuesto  >0){
						while( $rowImp = mysql_fetch_array($resultImpuetos) ){
							$id_iva_impuestos = $rowImp['id_iva'];
						}
					}
					
						$response['iva_impuesto'][]= $id_iva_impuestos;
					// else{
					// 	$sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta, codigo ) 
					// 	SELECT max(id_iva) + 1 AS id_iva, ".$iva_p." AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
					// 		   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
					// 		   WHERE plan_cuentas.codigo = '2010701013' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta ,".$codigoPorcentaje." AS codigo
					// 		   FROM impuestos";
					// 	$rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());
					// 	$id_iva_impuestos = mysql_insert_id();
					// }
					

						$fecha_actual= date("Y-m-d");
					
						$response['filas'][]= $numFilasCP;
				// if($numFilasCP>0){

					$sql1="select id_producto from productos where id_empresa= '".$sesion_id_empresa."' and codigo='".trim($codPrincipal)."'";
					$id_producto=0;
				// 	$response['consulta'][]= $sql1;
				$result=mysql_query($sql1);
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_producto=$row['id_producto'];
					}
					if ($id_producto!=0) 
					{
						$sql="UPDATE `productos` SET `producto`='".trim(addslashes($descripcion))."',`existencia_minima`=1,`existencia_maxima`=10000,`stock`=stock+'".$cantidad."',`costo`='".$precio."',`id_categoria`='".$id_categoria."',`id_proveedor`='".$id_proveedor."',`precio1`='".$precio."',`precio2`='0.00',`iva`='".$id_iva_impuestos."',`ICE`=0,fecha_registro='".$fecha_actual."',id_empresa='".$sesion_id_empresa."' ,codigo='".$codPrincipal."',codPrincipal='".$codPrincipal."',codAux='".$codAuxiliar."'  WHERE id_producto = $id_producto";
							$response['sql'][]= $sql;
						$resultado=mysql_query($sql);
				// 		$response['consulta'][]= $sql;		
						if($resultado) {
							$response['mensajes'][]= "Producto : '".$descripcion."' registrado con exito";

							$sqlBuscarBodega="SELECT `id`, `idBodega`, `idProducto`, `cantidad`, `proceso` FROM `cantBodegas` WHERE idProducto='" . $codPrincipal."' ORDER BY cantidad DESC LIMIT 1  ";
							$resultBuscarBodega = mysql_query($sqlBuscarBodega);
							$idBodega=0;
							while($rowBB= mysql_fetch_array($resultBuscarBodega)){
								$idBodega = $rowBB['idBodega'];
							}

							$sqlActualizaBodega = " UPDATE `cantBodegas` SET `cantidad`=cantidad+$cantidad  WHERE idProducto='" . $codPrincipal."' AND `idBodega`=$idBodega ";
							$resultActualizaBodega =  mysql_query($sqlActualizaBodega);

							}else{
								
								$response['errores'][]= "Error al guardar el producto: ".mysql_error(); 
								echo json_encode($response);
								exit;
							}   
					}else{						
						$sql="INSERT INTO `productos` (`producto`,`existencia_minima`,`existencia_maxima`,`stock`,`costo`,`id_categoria`,`id_proveedor`,`precio1`,`precio2`,`iva`,`ice`,`fecha_registro`,`id_empresa`,codigo,`codPrincipal`,`codAux`,`tipos_compras`,`produccion`,`proceso`,`id_cuenta`,img) values ('".trim(addslashes($descripcion))."','1','10000','".$cantidad."','".$precio."','".$id_categoria."','".$id_proveedor."','".$precio."','0.00','".$id_iva_impuestos."','0','".$fecha_actual."','".$sesion_id_empresa."','".$codPrincipal."','".$codPrincipal."','".$codAuxiliar."','1','No','0','0',NULL)";

						$resultado=mysql_query($sql);	
				// 		$response['consulta'][]= $sql;	
						if($resultado) {
	$response['sql'][]= $sql;
							$response['mensajes'][]= "Producto : '".$descripcion."' registrado con exito";

				// 			$sqlCodProd = "INSERT INTO `codigosproductos`( `codigo_producto`, `codigo_proveedor`, `id_empresa`, `id_proveedor`) VALUES ('".$codPrincipal."','".$codPrincipal."','".$sesion_id_empresa."','".$id_proveedor."')";
				// 			$resultCodProd = mysql_query($sqlCodProd);
				// 			$response['consulta'][]= $sqlCodProd;	

							}else{
								
								$response['errores'][]= "Error al guardar el producto: ".mysql_error(); 
								echo json_encode($response);
								exit;
							}   
					
				}

				// }
	
			}
			
			
			$sqlCompras="Select id_compra from compras a inner join proveedores b
			on a.id_proveedor=b.id_proveedor and a.id_empresa=b.id_empresa
			where a.id_empresa='".$sesion_id_empresa."' and b.ruc='".$txtRuc."' and txtEmision='".$txtEmision."' 
			and numSerie='".$numSerie."' and txtNum='".$secuencial."'";
			//echo $sqlCompras;
			$result=mysql_query($sqlCompras);
					
			$id_compra=0;
			while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
			{  
				$id_compra=$row['id_compra'];
			}
//			echo "id compra".$id_compra;
//  $response['mensajes'][]= $id_compra;
			if ($id_compra>0) 
			{
				$detalles_xml2 = '';
				if(isset( $xml1->detalles->detalle  )){
				   
				   $detalles_xml2  = $xml1->detalles->detalle;
			   }else if( isset($xml1->comprobante->factura->detalles->detalle) ){
				   $detalles_xml2 =$xml1->comprobante->factura->detalles->detalle; 
			   }

			
			foreach ($detalles_xml2 as $detalle) {

					$codPrincipal= $detalle->codigoPrincipal;
					$codAuxiliar= $detalle->codigoPrincipal;				
					$cantidad= floatval($detalle->cantidad);
					$precio= floatval($detalle->precioUnitario);
					$descuento= floatval($detalle->descuento);
					$valorTotal= floatval($detalle->precioTotalSinImpuesto);
						
					$sql1="select productos.id_producto,impuestos.iva from productos left join impuestos on impuestos.id_iva= productos.iva where productos.id_empresa= '".$sesion_id_empresa."' and productos.codigo='" . $codPrincipal."'";
//					echo "<br/>";
//					echo $sql1;
					$result=mysql_query($sql1);
					$id_producto=0;
					$iva_producto=0;
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_producto=$row['id_producto'];
						$iva_producto=$row['iva'];
					}
					
				// 	$sqlBod="select id_centro_costo from centro_costo where empresa= '".$sesion_id_empresa."' and id_bodega='1'";
					$sqlBod="select id_centro_costo from centro_costo where empresa= '".$sesion_id_empresa."' ORDER BY predeterminado DESC LIMIT 1";



					$result=mysql_query($sqlBod);
					$id_bodega=0;
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_bodega=$row['id_centro_costo'];
					}
					$iva_detalle=0;
					foreach ($detalle->impuestos as $imp) 
					{
						$tarifa= $imp->impuesto->tarifa;
					        try{
					            	$iva_detalle = floatval($tarifa);
					        }catch(Exception $e){
					            	$iva_detalle = 0;
					        }
						
					
						$sqlIva1="Select * From impuestos where id_empresa='".$sesion_id_empresa."'  AND iva = '".$tarifa."' ";
			$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
			$iva=0;
			$impuestos_id_plan_cuenta = 0;
			while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
										$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
										
									}
					}
						$response['iva_detalle'][]= $txtIdIva;
					if ($id_producto>0) {
					    
						$total_iva_producto = floatval($valorTotal) * (floatval($iva_detalle)/100);
						$sql="INSERT INTO `detalle_compras` (idBodega,cantidad,valor_unitario,`des`,valor_total,id_compra,id_producto,id_empresa,`xml`,iva,total_iva)
						values ('".$id_bodega."','".$cantidad."','".$precio."','".$descuento."','".$valorTotal."','".$id_compra."','".$id_producto."','".$sesion_id_empresa."','1','".$txtIdIva."','".$total_iva_producto."')";
 $response['consulta'][]= $sql;
						$resultado=mysql_query($sql);
						if($resultado) {
                            $response['mensajes'][]= "Detalle de la compra Registrado con exito"; 

                            }else{
				// 			$response['consulta'][]= $sql;
                            $response['errores'][]="Error al guardar el detalle de la compra: ".mysql_error();
                            echo json_encode($response);
                            exit;
                            }   
					}
				}
			
			}
		}	
		$reembolsos_xml='';
		if (isset($xml1->reembolsos->reembolsoDetalle) ){
			$reembolsos_xml = $xml1->reembolsos->reembolsoDetalle;
		}
    foreach ($reembolsos_xml as $reembolso) 
			{
				$tipoIdentificacionProveedorReembolso=$reembolso->tipoIdentificacionProveedorReembolso;

				$identificacionProveedorReembolso=$reembolso->identificacionProveedorReembolso;

				$codPaisPagoProveedorReembolso=$reembolso->codPaisPagoProveedorReembolso;

				$tipoProveedorReembolso= $reembolso->tipoProveedorReembolso;

				$codDocReembolso= $reembolso->codDocReembolso;

				$estabDocReembolso= $reembolso->estabDocReembolso;

				$ptoEmiDocReembolso= $reembolso->ptoEmiDocReembolso;

				$secuencialDocReembolso= $reembolso->secuencialDocReembolso;

				$fechaEmisionDocReembolso=$reembolso->fechaEmisionDocReembolso;

				$numeroautorizacionDocReemb=$reembolso->numeroautorizacionDocReemb;

$fecha = date_create_from_format('d/m/Y', $fechaEmisionDocReembolso);

// Formatear la fecha en el nuevo formato
$formatoDeseado = date_format($fecha, 'Y-m-d');
				  $sql="INSERT INTO `reembolsos_gastos`( `tipo_identificacion_proveedor_reembolso`, `identificacion_proveedor_reembolso`, `cod_pais_proveedor_reembolso`, `tipo_proveedor_reembolso`, `cod_doc_reembolso`, `estab_doc_reembolso`, `pto_emi_doc_reembolso`, `secuencial_doc_reembolso`, `fecha_emision_doc_reembolso`, `numero_autorizacion_doc_reembolso`, `id_compra`) VALUES ('".$tipoIdentificacionProveedorReembolso."','".$identificacionProveedorReembolso."','".$codPaisPagoProveedorReembolso."','".$tipoProveedorReembolso."','".$codDocReembolso."','".$estabDocReembolso."','".$ptoEmiDocReembolso."','".$secuencialDocReembolso."','".$formatoDeseado."','".$numeroautorizacionDocReemb."','".$id_compra."')";
				$result = mysql_query($sql);
				$id_reembolso = mysql_insert_id();
				foreach ($reembolso->detalleImpuestos->detalleImpuesto as $d_impuestos) 
				{
					$codigo_d_impuesto = $d_impuestos->codigo;

					$codigo_porcentaje_d_impuesto = $d_impuestos->codigoPorcentaje;

					$tarifa_d_impuesto = floatval($d_impuestos->tarifa);

					$base_d_impuesto = floatval($d_impuestos->baseImponibleReembolso);

					$impuesto_d_impuesto = $d_impuestos->impuestoReembolso;

					 $sql_d_impuesto = "INSERT INTO `impuestos_reembolso`(`codigo_impuesto`, `codigo_porcentaje`, `tarifa`, `base_imponible`, `impuesto`, `id_reembolsos`) VALUES ('".$codigo_d_impuesto."','".$codigo_porcentaje_d_impuesto."','".$tarifa_d_impuesto."','".$base_d_impuesto."','".$impuesto_d_impuesto."','".$id_reembolso."')";
					$result_impuesto = mysql_query($sql_d_impuesto);

				}
				foreach ($reembolso->compensacionesReembolso->compensacionReembolso as $compensacion) 
				{
					$codigo_compensacion = $compensacion->codigo;
					$tarifa_compensacion = floatval($compensacion->tarifa);
					$valor_compensacion = floatval($compensacion->valor);

					  $sql_compensacion = "INSERT INTO `compensaciones_reembolso`(`codigo`, `tarifa`, `valor`, `id_reembolso`) VALUES ('".$codigo_compensacion."','".$tarifa_compensacion."','".$valor_compensacion."','".$id_reembolso."')";
					$result_compensacion = mysql_query($sql_compensacion);

				}				
			}

        
        
        
	} else  {
        $response['errores'][]= "No hay Archivo ";
        	}
	 			
            echo json_encode($response);
?>
