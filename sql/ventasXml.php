<?php

	session_start();
	include "../conexion.php";
	
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_usuario = $_SESSION['sesion_id_usuario'];
    $sesion_empresa_id_ciudad= $_SESSION["sesion_empresa_id_ciudad"];
	$modoFacturacion='200';
    date_default_timezone_set('America/Guayaquil');
    
    $response =[];
    $response['advertencias'][]= "";
    $response['mensajes'][]= "";
    $response['numero_factura_venta']='';
    $response['tipo_doc']='';
	if(isset($_FILES['files'])){
	    
		$numFiles = count($_FILES['files']['tmp_name']); 
		for ($u = 0; $u < $numFiles; $u++) {
			$nombreArchivo = $_FILES['files']['name'][$u];
			$archivoTemporal = $_FILES['files']['tmp_name'][$u];

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
			$response['advertencias'][]= "Se alcanzo el limite de facturas.";	
					echo json_encode($response);
					exit;
		}
			    
	    $xml=file_get_contents($_FILES['files']['tmp_name'][$u]);
	    

	  
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
	    
	    $xml1 = new SimpleXMLElement($xml);
	    
        $comprobante = $xml1->comprobante ;
     
        $codDoc=substr($xml,strpos($xml,"<codDoc>")+strlen("<codDoc>"),strpos($xml,"</codDoc>")-strlen("<codDoc>")-strpos($xml,"<codDoc>"));
        $response['tipo_doc']=intval($codDoc);

         if($codDoc=='04'){
            $tipo='notaCredito';
            $info='infoNotaCredito';
           
        $txtEmision=substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
		$numSerie=substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
        $secuencial=substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));

		$sqlEstablecimiento="SELECT `id`, `id_empresa`, `codigo`, `direccion` FROM `establecimientos` WHERE id_empresa=$sesion_id_empresa AND codigo='".$numSerie."' ";
		$resultEtablecimiento = mysql_query($sqlEstablecimiento);
		 $numFilasEstablecimiento = mysql_num_rows($resultEtablecimiento);
         $response['txtEmision']=$txtEmision;

		if($numFilasEstablecimiento >0){
		    
			while($rowEstab = mysql_fetch_array($resultEtablecimiento) ){
				$idEstablecimiento = $rowEstab['id'];

				$sqlEmision = "SELECT `id`, `id_est`, `codigo`, `numFac`, `tipoEmision`, `ambiente`, `tipoFacturacion`, `formato`, `SOCIO` FROM `emision` WHERE id_est='".$rowEstab['id']."' AND codigo='".$txtEmision."' ";
				$resultEmision = mysql_query($sqlEmision);
				$numFilasEmision = mysql_num_rows($resultEmision);
		
				if($numFilasEmision==0){
			
					$response['advertencias'][]= "El punto de emision '".$txtEmision."' del establecimiento '".$numSerie."' especificado en el xml  no existe. Se requiere crear un punto de emision para subir el XML.";
				
			 // 	var_dump($response);
					echo json_encode($response);
					exit;
				}else{

				    
					while($rowEmision = mysql_fetch_array($resultEmision) ){
						$idEmision = $rowEmision['id'];
					}
				}
			}
			 
		}else{
		  
			$response['advertencias'][]= "El punto de establecimiento '".$numSerie."' especificado en el xml  no existe. Se requiere crear un punto de establecimiento para subir el XML.";	
	
		
			echo json_encode($response);
		
			exit;
		}
			
        	$impuestos_xml = '';
		
			if(isset($xml1->comprobante->$tipo->$info->totalConImpuestos)){
			    
				$impuestos_xml =$xml1->comprobante->$tipo->$info->totalConImpuestos;
			}else if( isset($xml1->$info->totalConImpuestos) ){
				$impuestos_xml =$xml1->$info->totalConImpuestos; 
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
    				     $response['advertencias'][]= "NO SE PUEDE GUARDAR LA VENTA, NO ESTA CREADO EL IMPUESTO CON TARIFA ".$porcentaje_impuesto;
    				     echo json_encode($response);
                        exit;
    				}
				}
				
			}
				
		$sqlFP="SELECT `id_forma_pago`, `nombre`, `id_plan_cuenta`, `id_empresa`, `id_tipo_movimiento`, `diario`, `ingreso`, `egreso`, `pagar`, `tipo` FROM `formas_pago` WHERE id_empresa='".$sesion_id_empresa."' and id_tipo_movimiento='4' ";
		$resultFp =mysql_query($sqlFP);
		$numFilasFP = mysql_num_rows($resultFp);
		if($numFilasFP==0){
			$response['errores'][]="No existe una forma de pago tipo credito, es necesario crear una para continuar.".mysql_error();
			echo json_encode($response);
			exit;
		}
				while($rowFp = mysql_fetch_array($resultFp) ){
					$formaPagoId2= $rowFp['id_forma_pago'];
					$formaPagoTipo = $rowFp['tipo'];
					$id_plan_cuenta = $rowFp['id_plan_cuenta'];
				}

        
        $txtRuc=substr($xml,strpos($xml,"<identificacionComprador>")+strlen("<identificacionComprador>"),strpos($xml,"</identificacionComprador>")-strlen("<identificacionComprador>")-strpos($xml,"<identificacionComprador>"));
        
			try 
			{
				$sqlm="Select id_cliente From clientes where cedula='".$txtRuc."' and id_empresa='".$sesion_id_empresa."'";
                $resultCLiente=mysql_query($sqlm);
                $id_cliente=0;
                $numFilas = mysql_num_rows($resultCLiente);
                if($numFilas==0){
                    $response['errores'][]= "El cliente no existe, es necesario crearlo previamente.";  echo json_encode($response);exit;
                }
                if($numFilas>0){
                    while($rowC=mysql_fetch_array($resultCLiente))
    				{
                        $id_cliente=$rowC['id_cliente'];
                        
    				}
                }	
			}	
			
			catch(Exception $ex) {  $response['errores'][]= "Error en  clientes : ".$ex;  echo json_encode($response);exit;}
			
        $txtTipoIdentificacionComprador=substr($xml,strpos($xml,"<tipoIdentificacionComprador>")+strlen("<tipoIdentificacionComprador>"),strpos($xml,"</tipoIdentificacionComprador>")-strlen("<tipoIdentificacionComprador>")-strpos($xml,"<tipoIdentificacionComprador>"));
        $txtRazonSocialComprador=substr($xml,strpos($xml,"<razonSocialComprador>")+strlen("<razonSocialComprador>"),strpos($xml,"</razonSocialComprador>")-strlen("<razonSocialComprador>")-strpos($xml,"<razonSocialComprador>"));
        $txtDireccionComprador=substr($xml,strpos($xml,"<direccionComprador>")+strlen("<direccionComprador>"),strpos($xml,"</direccionComprador>")-strlen("<direccionComprador>")-strpos($xml,"<direccionComprador>"));
        $obligadoContabilidad=substr($xml,strpos($xml,"<obligadoContabilidad>")+strlen("<obligadoContabilidad>"),strpos($xml,"</obligadoContabilidad>")-strlen("<obligadoContabilidad>")-strpos($xml,"<obligadoContabilidad>")); 
        $numDocModificado=substr($xml,strpos($xml,"<numDocModificado>")+strlen("<numDocModificado>"),strpos($xml,"</numDocModificado>")-strlen("<numDocModificado>")-strpos($xml,"<numDocModificado>")); 

  
				
	$numDocModificadoValue = (string)$numDocModificado;

// Separar el valor en tres partes utilizando el guion (-)
list($parte1, $parte2, $parte3) = explode('-', $numDocModificadoValue);

// Consulta para obtener el id de establecimiento
$query = "SELECT id FROM establecimientos WHERE codigo = '$parte1' AND id_empresa = $sesion_id_empresa";

// Ejecutar la consulta
$resultado = mysql_query($query);

// Verificar si la consulta fue exitosa
if ($resultado) {
    // Obtener el resultado
    $fila = mysql_fetch_assoc($resultado);

    // Verificar si se encontraron resultados
    if ($fila) {
        $idEstablecimientoModificado = $fila['id'];

        // Consulta para obtener el id de emisión
        $queryEmision = "SELECT id FROM emision WHERE codigo = '$parte2' AND id_est = $idEstablecimientoModificado";

        // Ejecutar la consulta
        $resultadoEmision = mysql_query($queryEmision);

        // Verificar si la consulta fue exitosa
        if ($resultadoEmision) {
            // Obtener el resultado
            $filaEmision = mysql_fetch_assoc($resultadoEmision);

            // Verificar si se encontraron resultados
            if ($filaEmision) {
                $idEmisionModificado = $filaEmision['id'];
                // Puedes usar $idEstablecimientoModificado y $idEmisionModificado según tus necesidades
                // echo "El ID de establecimiento es: " . $idEstablecimientoModificado . "<br>";
                // echo "El ID de emisión es: " . $idEmisionModificado;
            } else {
                echo "No se encontraron resultados en la tabla 'emision'.";
            }

            // Liberar el resultado
            mysql_free_result($resultadoEmision);
        } else {
            // Si hay un error en la consulta, mostrar el mensaje de error
            echo "Error en la consulta 'emision': " . mysql_error();
        }
    } else {
        echo "No se encontraron resultados en la tabla 'establecimientos'.";
    }

    // Liberar el resultado
    mysql_free_result($resultado);
} else {
    // Si hay un error en la consulta, mostrar el mensaje de error
    echo "Error en la consulta 'establecimientos': " . mysql_error();
}





	
$parte3SinCeros = ltrim($parte3, '0');
	
    $sqlCliente="Select id_venta from ventas a 
			
			where a.id_empresa='".$sesion_id_empresa."' 
			and codigo_pun='".$idEstablecimientoModificado."' 
			and codigo_lug='".$idEmisionModificado."' 
			and numero_factura_venta='".$parte3SinCeros."'";
			
			$response['sql'][]= $sqlCliente;
	    $result=mysql_query($sqlCliente);
				
		$id_venta=0;
		while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
		{  
			$id_venta=$row['id_venta'];
		}
		if($id_venta > 0) 
		{
		    $sqlValidarNotaCredito="Select id_venta from ventas a 
			
			where a.id_empresa='".$sesion_id_empresa."' AND Retiva='".$id_venta."' ";
				$response['sql'][]= $sqlValidarNotaCredito;
			$resultValidarNotaCredito = mysql_query($sqlValidarNotaCredito);
			$numValidarNotaCredito = mysql_num_rows($resultValidarNotaCredito);
			if($numValidarNotaCredito!=0){
			     $response['advertencias'][]= "NOTA DE CREDITO YA REGISTRADA";	
			echo json_encode($response);exit;
			}
			$numeroAutorizacion='';
				if(strpos($xml,"<numeroAutorizacion>")>0){
				    	$numeroAutorizacion=substr($xml,strpos($xml,"<numeroAutorizacion>")+strlen("<numeroAutorizacion>"),strpos($xml,"</numeroAutorizacion>")-strlen("<numeroAutorizacion>")-strpos($xml,"<numeroAutorizacion>"));
				}
          
			$fecha=substr($xml,strpos($xml,"<fechaEmision>")+strlen("<fechaEmision>"),strpos($xml,"</fechaEmision>")-strlen("<fechaEmision>")-strpos($xml,"<fechaEmision>"));

		
		    $timestamp = strtotime(str_replace('/', '-', $fecha));

			
			$newDate1 = date("Y-m-d h:i:s", $timestamp );

			
			$sub_total=substr($xml,strpos($xml,"<totalSinImpuestos>")+strlen("<totalSinImpuestos>"),strpos($xml,"</totalSinImpuestos>")-strlen("<totalSinImpuestos>")-strpos($xml,"<totalSinImpuestos>"));
		   

				

	        $numero_factura_venta =	$secuencial=substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
	        	$numero_factura = $numero_factura_venta; 
            // $numero_factura_venta = ceros($numero_factura_venta);
			$caducidad=substr($xml,strpos($xml,"<fechaAutorizacion>")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion>"));
			
			 $caducidad=null;
				if(strpos($xml,"<fechaAutorizacion>")>0){
				    	$caducidad=substr($xml,strpos($xml,"<fechaAutorizacion>")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion>"));
				    	 $caducidad=substr(str_replace("T"," ",$caducidad),0,19);
				}
			$clave_de_acceso=null;
				if(strpos($xml,"<claveAcceso>")>0){
				    	$clave_de_acceso=substr($xml,strpos($xml,"<claveAcceso>")+strlen("<claveAcceso>"),strpos($xml,"</claveAcceso>")-strlen("<claveAcceso>")-strpos($xml,"<claveAcceso>"));
				}	
			$importeTotal=substr($xml,strpos($xml,"<importeTotal>")+strlen("<importeTotal>"),strpos($xml,"</importeTotal>")-strlen("<importeTotal>")-strpos($xml,"<importeTotal>"));
			$totalConImpuestos=substr($xml,strpos($xml,"<totalConImpuestos>")+strlen("<totalConImpuestos>"),strpos($xml,"</totalConImpuestos>")-strlen("<totalConImpuestos>")-strpos($xml,"<totalConImpuestos>"));
			
				if(strpos($xml,"<totalDescuento>")>0){
			    $totalDescuento=substr($xml,strpos($xml,"<totalDescuento>")+strlen("<totalDescuento>"),strpos($xml,"</totalDescuento>")-strlen("<totalDescuento>")-strpos($xml,"<totalDescuento>"));
			}else {
			    $totalDescuento=0;
			}
			
			$valorModificacion=0;
				if(strpos($xml,"<valorModificacion>")>0){
			    $valorModificacion=substr($xml,strpos($xml,"<valorModificacion>")+strlen("<valorModificacion>"),strpos($xml,"</valorModificacion>")-strlen("<valorModificacion>")-strpos($xml,"<valorModificacion>"));
			}
			
			$totalImpuesto=substr($xml,strpos($xml,"<totalImpuesto>")+strlen("<totalImpuesto>"),strpos($xml,"</totalImpuesto>")-strlen("<totalImpuesto>")-strpos($xml,"<totalImpuesto>"));
		    
		    $motivo=substr($xml,strpos($xml,"<motivo>")+strlen("<motivo>"),strpos($xml,"</motivo>")-strlen("<motivo>")-strpos($xml,"<motivo>"));
		    
		    $subTotal12=0;
		    $subTotal0=0;
		
			$i=0;	
			$totalImpuestos = 0;
			$impuestosExiste = '';
			if(isset($xml1->comprobante->$tipo->$info->totalConImpuestos)){
			    
				$impuestosExiste =$xml1->comprobante->$tipo->$info->totalConImpuestos;
			}else if( isset($xml1->$info->totalConImpuestos) ){
				$impuestosExiste =$xml1->$info->totalConImpuestos; 
			}
			
		

			foreach ($impuestosExiste->totalImpuesto as $totalImpuesto) 
			{
				$codigo_a[$i]= $totalImpuesto->codigo; 
				$codigoPorcentaje_a[$i]= $totalImpuesto->codigoPorcentaje;
				$descuentoAdicional_a[$i]= trim($totalImpuesto->descuentoAdicional)!=''?$totalImpuesto->descuentoAdicional:0;
				$baseImponible_a[$i]= $totalImpuesto->baseImponible;
				$valor_a[$i]= $totalImpuesto->valor;
				$totalImpuestos = $totalImpuestos+ floatval($totalImpuesto->valor);
				if ($codigoPorcentaje_a[$i]=='2') 
				{
					$subTotal12=$baseImponible_a[$i];  
					$iva= $valor_a[$i];
				}
				else
				{
					$subTotal0=$baseImponible_a[$i];
					$iva=0;
				}
			
				$i=$i+1;
			}	
			

          
	
			$retfuente=0;

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
			$montoIce=0;
			$id_vendedor=0;
			$id_proveedor=0;
			
			$calculo_iva = floatval($subTotal12) *($iva / 100);
			$response['iva_cantidad']=$iva;
			$response['iva_cantidad']=$iva;
			$response['calculo_cantidad']=$calculo_iva;
				 	
				 	if($valorModificacion==0){
				 	     $importeTotal = number_format((floatval($totalImpuestos) +  floatval($sub_total)), 2, '.', '');
				 	} 	else{
				 	     $importeTotal = number_format($valorModificacion, 2, '.', '');
				 	} 
			$txtNombreFVC=$txtRazonSocialComprador;
	        $total=$importeTotal;
	        	if(trim($caducidad)!=''){
	        	    	$sql="insert into ventas (fecha_venta,      estado,       total,       sub_total,         sub0,               sub12,           descuento,        propina,      numero_factura_venta, fecha_anulacion, descripcion, id_iva, id_vendedor, id_cliente, id_empresa,	tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, RetIva,MotivoNota,xml,valorModificacion,total_iva,Autorizacion, FechaAutorizacion,ClaveAcceso,Retfuente ) 
    				values  (             '".$newDate1."','Activo','".$importeTotal."','".$sub_total."','".$subTotal0."','".$subTotal12."','".$totalDescuento."', '0',   '".$numero_factura_venta."',    '0000-00-00 00:00:00'  ,'".$txtDescripcion."', '".$txtIdIva."', '".$id_vendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '4', '".$idEstablecimiento."', '".$idEmision."','0', '".$sesion_usuario."','".$id_venta."','".$motivo."',1,'".$valorModificacion."','".$totalImpuestos."','".$numeroAutorizacion."','".$caducidad."','".$clave_de_acceso."','0');";
	        	}else{
	        	    	$sql="insert into ventas (fecha_venta,      estado,       total,       sub_total,         sub0,               sub12,           descuento,        propina,      numero_factura_venta, fecha_anulacion, descripcion, id_iva, id_vendedor, id_cliente, id_empresa,	tipo_documento, codigo_pun, codigo_lug,id_forma_pago , id_usuario, RetIva,MotivoNota,xml,valorModificacion,total_iva) 
    				values  (             '".$newDate1."','Activo','".$importeTotal."','".$sub_total."','".$subTotal0."','".$subTotal12."','".$totalDescuento."', '0',   '".$numero_factura_venta."',    '0000-00-00 00:00:00'  ,'".$txtDescripcion."', '".$txtIdIva."', '".$id_vendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '4', '".$idEstablecimiento."', '".$idEmision."','0', '".$sesion_usuario."','".$id_venta."','".$motivo."',1,'".$valorModificacion."','".$totalImpuestos."');";
	        	}
		
    				
          
			$result=mysql_query($sql);
			$id_venta = mysql_insert_id();
			 $response['id_venta']=$id_venta;
			 $response['establecimiento']=$idEstablecimiento;
			 $response['emision']=$idEmision;
			  $response['numero_factura_venta']=$numero_factura_venta;
			if($result) { 
			    $noteCreditoExiste = '';
			if(isset($xml1->infoAdicional->campoAdicional)){
				$noteCreditoExiste =$xml1->infoAdicional->campoAdicional;
				
			}
			else if( isset($xml1->comprobante->$tipo->infoAdicional->campoAdicional) ){
				$noteCreditoExiste = $xml1->comprobante->$tipo->infoAdicional->campoAdicional; 
			}
		
			    	foreach ($noteCreditoExiste as $campoActual) {
					   
						$nombreAtributo = (string)$campoActual['nombre'];
				
						if($nombreAtributo!='Email Cliente' && $nombreAtributo!='TELÉFONO' && $nombreAtributo!='CELULAR'){
						    
							$sqlInfoAdicional = "INSERT INTO `info_adicional`( `campo`, `descripcion`, `id_venta`, `id_empresa`,xml) VALUES ('".$nombreAtributo."','".$campoActual."','".$id_venta."','".$sesion_id_empresa."',1)";
								 $response['verificar']=$sqlInfoAdicional;
						$resultInfoAdicional = mysql_query($sqlInfoAdicional);
						}
					
					}
               $response['mensajes'][]= "Encabezado Venta Registrado con exito";
				if($limite==0){
					$limite=0;
					
				}else if($limite==1){
					$limite=$limite-2;
					
				}else{
					
					$limite=$limite-1;
				}
				
			// 	$cmbEmi;
				$sqlNumFac ="update emision set numFac='".$numero_factura_venta."' where id ='".$idEmision."' ";
				$result=mysql_query($sqlNumFac) ;
				$response['sql'][]= $sqlNumFac;
				
				$sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
				$resultEmpresa2=mysql_query($sqlEmpresa2) ;
				$response['sql'][]= $sqlEmpresa2;
             }else{
                $response['errores'][]= "Error al guardar encabezado venta : ".mysql_error();
                echo json_encode($response);
                exit;
             }   
			 $detallaExiste = '';
			 if( isset($xml1->detalles) ){
				 $detallaExiste = $xml1->detalles;
				 
				
			 }
			 else if(isset($xml1->comprobante->$tipo->detalles)){
				 $detallaExiste = $xml1->comprobante->$tipo->detalles;
				
				
			 }
			 	

			  $sqlBod="select id_centro_costo,id_cuenta from centro_costo where empresa= '".$sesion_id_empresa."' AND tipo='2' ORDER BY predeterminado DESC LIMIT 1";
					$result=mysql_query($sqlBod);
					$id_bodega=0;
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_bodega=$row['id_centro_costo'];
						$id_cuenta_centro_costo = $row['id_cuenta'];
					}

			foreach ($detallaExiste->detalle as $detalle) 
			{
			     //$response['mensajes'][]= "ejecuta detalle productos";
				// $codPrincipal= $detalle->codigoPrincipal;
				$codPrincipal= trim($detalle->codigoAdicional);
				$codAuxiliar= $detalle->codigoAdicional;
				$descripcion= $detalle->descripcion;
				$cantidad= $detalle->cantidad;
				$precio= $detalle->precioUnitario;
				

				$sqlCP="SELECT `id_codProd`, `codigo_producto`, `codigo_proveedor`, `id_empresa`, `id_proveedor` 
				FROM `codigosproductos` WHERE id_empresa= '".$sesion_id_empresa."'  AND codigo_producto ='".trim($codPrincipal)."' ";
				$resultCP= mysql_query($sqlCP);
				$numFilasCP = mysql_num_rows($resultCP);
				 $response['consulta'][]= $sqlCP;
				$sql1="SELECT id_categoria from categorias where id_empresa= '".$sesion_id_empresa."' and categoria='SERVICIOS'";
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
					$tarifa_b[$j]= $imp->impuesto->tarifa;
					$tarifa= $imp->impuesto->tarifa;
					$codigo = $imp->impuesto->codigo;
					$codigoPorcentaje =$imp->impuesto->codigoPorcentaje;

					$iva_p=$tarifa;
				}
				$id_iva_impuestos=0;
				$sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa = '".$sesion_id_empresa."' AND iva= '".$iva_p."'";
						$resultImpuetos = mysql_query($sqlImpuesto);
						$numFilaImpuesto = mysql_num_rows($resultImpuetos);
						$sql_impuesto	='';
				if($numFilaImpuesto  >0){
					while( $rowImp = mysql_fetch_array($resultImpuetos) ){
								$id_iva_impuestos = $rowImp['id_iva'];
							}
				}
				
				$fecha_actual= date("Y-m-d");
				
				$response['filas'][]= $numFilasCP;
				// if(true){

					$sql1="select id_producto from productos where id_empresa= '".$sesion_id_empresa."' and codPrincipal='".trim($codPrincipal)."'";
					$id_producto=0;
				 	$response['consulta'][]= $sql1;
				    $result=mysql_query($sql1);
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_producto=$row['id_producto'];
					}
					if ($id_producto!=0) 
					{
						$sql="UPDATE `productos` SET `producto`='".trim(addslashes($descripcion))."',`existencia_minima`=1,`existencia_maxima`=10000,`stock`=stock+'".$cantidad."',`costo`='".$precio."',`id_categoria`='".$id_categoria."',`id_proveedor`='".$id_proveedor."',`precio1`='".$precio."',`precio2`='0.00',`iva`='".$id_iva_impuestos."',`ICE`=0,fecha_registro='".$fecha_actual."',id_empresa='".$sesion_id_empresa."' ,codigo='".$codPrincipal."',codPrincipal='".$codPrincipal."',codAux='".$codAuxiliar."', grupo='".$id_bodega."' ,id_cuenta='".$id_cuenta_centro_costo."'  WHERE id_producto = $id_producto";
						
						$resultado=mysql_query($sql);
				 		$response['consulta'][]= $sql;		
						if($resultado) {
						    	$response['sql'][]= $sql;
							$response['mensajes'][]= "Producto : '".$descripcion."' actualizado con exito";

							$sqlBuscarBodega="SELECT cantBodegas.`id`, cantBodegas.`idBodega`, cantBodegas.`idProducto`, cantBodegas.`cantidad`, cantBodegas.`proceso`, bodegas.id_empresa FROM `cantBodegas` INNER JOIN bodegas ON bodegas.id = cantBodegas.idBodega WHERE cantBodegas.idProducto='".$codPrincipal."'  AND bodegas.id_empresa='".$sesion_id_empresa."' ORDER BY cantidad DESC LIMIT 1; ";
							$resultBuscarBodega = mysql_query($sqlBuscarBodega);
							$idBodega=0;
							while($rowBB= mysql_fetch_array($resultBuscarBodega)){
								$idBodega = $rowBB['idBodega'];
							}

							$sqlActualizaBodega = " UPDATE `cantBodegas` SET `cantidad`=cantidad-$cantidad  WHERE idProducto='" . $codPrincipal."' AND `idBodega`=$idBodega ";
							$resultActualizaBodega =  mysql_query($sqlActualizaBodega);

							}else{
								
								$response['errores'][]= "Error al guardar el producto: ".mysql_error(); 
								echo json_encode($response);
								exit;
							}   
					}else{						
						$sql="INSERT INTO `productos` (`producto`,`existencia_minima`,`existencia_maxima`,`stock`,`costo`,`id_categoria`,`id_proveedor`,`precio1`,`precio2`,`iva`,`ice`,`fecha_registro`,`id_empresa`,codigo,`codPrincipal`,`codAux`,`tipos_compras`,`produccion`,`proceso`,`id_cuenta`,img,grupo) values ('".trim(addslashes($descripcion))."','1','10000','".$cantidad."','".$precio."','".$id_categoria."','".$id_proveedor."','".$precio."','0.00','".$id_iva_impuestos."','0','".$fecha_actual."','".$sesion_id_empresa."','".$codPrincipal."','".$codPrincipal."','".$codAuxiliar."','2','No','0','".$id_cuenta_centro_costo."',NULL,'".$id_bodega."')";

						$resultado=mysql_query($sql);	
				 		$response['consulta'][]= $sql;	
						if($resultado) {
	                    $response['sql'][]= $sql;
							$response['mensajes'][]= "Producto : '".$descripcion."' registrado con exito";

							$sqlCodProd = "INSERT INTO `codigosproductos`( `codigo_producto`, `codigo_proveedor`, `id_empresa`, `id_proveedor`) VALUES ('".trim($codPrincipal)."','".trim($codPrincipal)."','".$sesion_id_empresa."','".$id_proveedor."')";
							$resultCodProd = mysql_query($sqlCodProd);
				 			$response['consulta'][]= $sqlCodProd;	

							}else{
								
								$response['errores'][]= "Error al guardar el producto: ".mysql_error(); 
								echo json_encode($response);
								exit;
							}   
					
				}

				// }
	
			}
			

			if ($id_venta>0) 
			{
			     $tot_ventas=0;
					  $tot_servicios=0;
					  $tot_costo=0;
			$suma_iva_total =0;
			$listaServicios=array();
			foreach ($detallaExiste->detalle as $detalle) {

					$codPrincipal= trim($detalle->codigoAdicional);
					$codAuxiliar=$detalle->codigoAdicional;		
					$cantidad= floatval($detalle->cantidad);
					$precio= floatval($detalle->precioUnitario);
					$descuento= $detalle->descuento;
					$valorTotal= floatval($detalle->precioTotalSinImpuesto);
				// 	$tot_servicios =$tot_servicios + floatval($valorTotal);
				$sql1="select id_producto,productos.tipos_compras,impuestos.iva,impuestos.id_iva  from productos  left join impuestos on impuestos.id_iva= productos.iva where  productos.id_empresa= '".$sesion_id_empresa."' and productos.codigo='" . $codPrincipal."'";
//					echo "<br/>";
//					echo $sql1;
					$result=mysql_query($sql1);
					$id_producto=0;
					$iva_producto=0;
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_producto=$row['id_producto'];
						$iva_producto=$row['id_iva'];
						$tipos_compras_producto=$row['tipos_compras'];
					}
					
					
					

					if ($id_producto>0) {
					      if ($tipos_compras_producto =="1"){
								  $tot_ventas=$tot_ventas+$valorTotal;
								  $costo_promedio=calcularPromedio($id_producto,$sesion_id_empresa);
								  $tot_costo=$tot_costo+($costo_promedio * $cantidad);
							  }
							    if ($tipos_compras_producto == "2" ){
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
						 
							 
							 WHERE productos.id_producto=$id_producto ";
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

    					    												 
								$tot_servicios=$tot_servicios+$valorTotal;
							}
						
					$sqlBuscarBodega="SELECT cantBodegas.`id`, cantBodegas.`idBodega`, cantBodegas.`idProducto`, cantBodegas.`cantidad`, cantBodegas.`proceso`, bodegas.id_empresa FROM `cantBodegas` INNER JOIN bodegas ON bodegas.id = cantBodegas.idBodega WHERE cantBodegas.idProducto='".$codPrincipal."'  AND bodegas.id_empresa='".$sesion_id_empresa."' ORDER BY cantidad DESC LIMIT 1; ";
					$resultBuscarBodega = mysql_query($sqlBuscarBodega);
					
							$idBodega=0;
							
							while($rowcB= mysql_fetch_array($resultBuscarBodega)){
								$idBodega = $rowcB['idBodega'];
							}
		$iva_detalle=0;
							foreach ($detalle->impuestos as $imp) 
							{
								$tarifa= $imp->impuesto->tarifa;
							    try {
							        	$iva_detalle = floatval($tarifa);
                                } catch (Exception $e) {
                                	$iva_detalle =0;
                                }
								
							
							}
						$total_iva_producto = floatval($valorTotal) * (floatval($iva_detalle)/100);
						$suma_iva_total=$suma_iva_total+$total_iva_producto;
						$sql="INSERT INTO `detalle_ventas`( `idBodega`, `idBodegaInventario`, `cantidad`, `estado`, `v_unitario`, `descuento`, `v_total`, `id_venta`, `id_servicio`,tipo_venta, `id_kardex`, `id_empresa`, `tarifa_iva`, `total_iva`) VALUES ('".$id_bodega."','".$idBodega."','".$cantidad."','Activo','".$precio."','".$descuento."','".$valorTotal."','".$id_venta."','".$id_producto."','2','".$id_producto."','".$sesion_id_empresa."','".$iva_producto."','".$total_iva_producto."')";
						$response['sql'][]=$sql;
                            //  $response['mensajes'][]= $sql;
						$resultado=mysql_query($sql);
						if($resultado) {
                            $response['mensajes'][]= "Detalle de la venta Registrado con exito"; 

                            }else{
				// 			$response['consulta'][]= $sql;
                            $response['errores'][]="Error al guardar el detalle de la venta: ".mysql_error();
                            echo json_encode($response);
                            exit;
                            }   
					}
				}

					$response['tot_costo']= $tot_costo;
						$response['tot_servicios']= $tot_servicios;
                //   if ($sesion_tipo_empresa=="6")
				//   { // aqui2
					 
					 
					  try
					  {
						$sqlMNA="SELECT
						  max(numero_asiento) AS max_numero_asiento
						FROM
						   `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
						   WHERE periodo_contable.`id_empresa` ='".$sesion_id_empresa."' GROUP BY periodo_contable.`id_periodo_contable` ;";
						  $resultMNA=mysql_query($sqlMNA) ;
						  $numero_asiento=0;
						  while($rowMNA=mysql_fetch_array($resultMNA))
						  {
							  $numero_asiento=$rowMNA['max_numero_asiento'];
						  }
						  $numero_asiento++;
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
					  }
					  catch (Exception $e) 
					  {
						  // Error en algun momento.
						 ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
					  }
					  
					  $fecha= date("Y-m-d h:i:s");
					  $descripcion = "Nota de Credito #".$numero_factura." realizada a ".$txtNombreFVC;
					  
					  $debe = $total;
					  $debe2 = $descuento;
					  $total_debe = $debe + $debe2;
					  
					  $haber1 = $sub_total;
					  $haber2 = $suma_iva_total;
					  
					  $total_haber = $haber1 + $haber2 + $propina;
					  
					  $tipo_mov="F";
	  
				  //GUARDA EN  COMPROBANTES
					  $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					  $id_comprobante=mysql_insert_id();
					  
				  //GUARDA EN EL LIBRO DIARIO
				  $fecha_venta=$newDate1;
				  $numero_factura=$numero_factura_venta;
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
					
					$debeVector[$lin_diario]=0;
					$haberVector[$lin_diario]=$total; 
					

					 $sqlFormaPago = "SELECT
					formas_pago.`id_forma_pago` AS formas_pago_id,
					formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta
				FROM
					`formas_pago` formas_pago
				INNER JOIN `plan_cuentas` plan_cuentas ON
					formas_pago.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta` AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."'
				WHERE
				formas_pago.`id_tipo_movimiento`=13 LIMIT 1 ";
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
						$sql3="update ventas set id_forma_pago='04' where id_venta='".$id_venta."' ";
						$resp3 = mysql_query($sql3) or die(mysql_error());  
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
						  $debeVector[$lin_diario]=$tot_ventas;
						  $haberVector[$lin_diario]=0;
						  
  
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
						  $debeVector[$lin_diario]=0;
						  $haberVector[$lin_diario]=$descuento;
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
						  $debeVector[$lin_diario]=$propina;
						  $haberVector[$lin_diario]=0;
					  }
					  $txtTotalIvaFVC = $suma_iva_total;
					  if ($txtTotalIvaFVC!=0)
					  {
  
						  $lin_diario=$lin_diario+1;
						  $idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta;
						  $debeVector[$lin_diario]=$txtTotalIvaFVC;
						  $haberVector[$lin_diario]=0;
  
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
					  
					  
					  
 
				//   } //aqui2 

                	
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
    				      values ('".$tipo_documentox."','".$documento_numero."','".$referencia."','".$total."',
    					  '".$total."','".$id_libro_diario."','".$fecha_nueva."','0','".$id_cliente."','0','".$sesion_id_empresa."',
    					'".$id_venta."', '".$estadoCC."');";
    				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
    				$id_cuenta_por_pagar=mysql_insert_id();
    			
    				}
				$sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
						('".$newDate1."','Venta','".$id_venta."', '".$sesion_id_empresa."')";
				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				$id_kardes=mysql_insert_id();

				$response['sql'][]=$sqlk;
				// echo json_encode($response);


				if($modoFacturacion=='200'){
    				    
					$emision_tipoEmision='F';
					
				}else{
					
					$emision_tipoEmision = $_SESSION['emision_tipoEmision'];
				}
				
				if ($emision_tipoEmision === 'E'){
							genXml($id_venta);
							
					$sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
					$result=mysql_query($sqli);
					while($row=mysql_fetch_array($result)){
						$claveAcceso=$row['ClaveAcceso'];
					}
					if ($claveAcceso != ''){
						// echo "SI";
					}else{
						// echo "NO";
					}
							
				}
				
				
				
			}//if venta
		}
		else
		{
		      $response['advertencias'][]= "VENTA NO EXISTE";	
			echo json_encode($response);exit;

		
		}
            
        }else{
            $tipo='factura';
            $info='infoFactura';
            $txtEmision=substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
		$numSerie=substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
        $secuencial=substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));

		$sqlEstablecimiento="SELECT `id`, `id_empresa`, `codigo`, `direccion` FROM `establecimientos` WHERE id_empresa=$sesion_id_empresa AND codigo='".$numSerie."' ";
		$resultEtablecimiento = mysql_query($sqlEstablecimiento);
		$numFilasEstablecimiento = mysql_num_rows($resultEtablecimiento);
        $response['txtEmision']=$txtEmision;

		if($numFilasEstablecimiento >0){
		    
			while($rowEstab = mysql_fetch_array($resultEtablecimiento) ){
				$idEstablecimiento = $rowEstab['id'];

				$sqlEmision = "SELECT `id`, `id_est`, `codigo`, `numFac`, `tipoEmision`, `ambiente`, `tipoFacturacion`, `formato`, `SOCIO` FROM `emision` WHERE id_est='".$rowEstab['id']."' AND codigo='".$txtEmision."' ";
				$resultEmision = mysql_query($sqlEmision);
				$numFilasEmision = mysql_num_rows($resultEmision);
		
				if($numFilasEmision==0){
			
					$response['advertencias'][]= "El punto de emision '".$txtEmision."' del establecimiento '".$numSerie."' especificado en el xml  no existe. Se requiere crear un punto de emision para subir el XML.";
				
			 // 	var_dump($response);
					echo json_encode($response);
					exit;
				}else{

				    
					while($rowEmision = mysql_fetch_array($resultEmision) ){
						$idEmision = $rowEmision['id'];
					}
				}
			}
			 
		}else{
		  
			$response['advertencias'][]= "El punto de establecimiento '".$numSerie."' especificado en el xml  no existe. Se requiere crear un punto de establecimiento para subir el XML.";	
	
		
			echo json_encode($response);
		
			exit;
		}
		$impuestos_xml = '';
		
			if(isset($xml1->comprobante->$tipo->$info->totalConImpuestos)){
			    
				$impuestos_xml =$xml1->comprobante->factura->infoFactura->totalConImpuestos;
			}else if( isset($xml1->$info->totalConImpuestos) ){
				$impuestos_xml =$xml1->$info->totalConImpuestos; 
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
    				     $response['advertencias'][]= "NO SE PUEDE GUARDAR LA VENTA, NO ESTA CREADO EL IMPUESTO CON TARIFA ".$porcentaje_impuesto;
    				     echo json_encode($response);
                        exit;
    				}
				}
				
			}
			
        	
		$sqlFP="SELECT `id_forma_pago`, `nombre`, `id_plan_cuenta`, `id_empresa`, `id_tipo_movimiento`, `diario`, `ingreso`, `egreso`, `pagar`, `tipo` FROM `formas_pago` WHERE id_empresa='".$sesion_id_empresa."' and id_tipo_movimiento='4' ";
		$resultFp =mysql_query($sqlFP);
		$numFilasFP = mysql_num_rows($resultFp);
		if($numFilasFP==0){
			$response['errores'][]="No existe una forma de pago tipo credito, es necesario crear una para continuar.".mysql_error();
			echo json_encode($response);
			exit;
		}
		while($rowFp = mysql_fetch_array($resultFp) ){
			$formaPagoId2= $rowFp['id_forma_pago'];
			$formaPagoTipo = $rowFp['tipo'];
			$id_plan_cuenta = $rowFp['id_plan_cuenta'];
		}

        
        $txtRuc=substr($xml,strpos($xml,"<identificacionComprador>")+strlen("<identificacionComprador>"),strpos($xml,"</identificacionComprador>")-strlen("<identificacionComprador>")-strpos($xml,"<identificacionComprador>"));

        $txtTipoIdentificacionComprador=substr($xml,strpos($xml,"<tipoIdentificacionComprador>")+strlen("<tipoIdentificacionComprador>"),strpos($xml,"</tipoIdentificacionComprador>")-strlen("<tipoIdentificacionComprador>")-strpos($xml,"<tipoIdentificacionComprador>"));
        $txtRazonSocialComprador=substr($xml,strpos($xml,"<razonSocialComprador>")+strlen("<razonSocialComprador>"),strpos($xml,"</razonSocialComprador>")-strlen("<razonSocialComprador>")-strpos($xml,"<razonSocialComprador>"));
        $txtDireccionComprador='';
        if(strpos($xml,"<direccionComprador>")>0){
        	   $txtDireccionComprador=substr($xml,strpos($xml,"<direccionComprador>")+strlen("<direccionComprador>"),strpos($xml,"</direccionComprador>")-strlen("<direccionComprador>")-strpos($xml,"<direccionComprador>")); 
        	}
        
        $obligadoContabilidad=substr($xml,strpos($xml,"<obligadoContabilidad>")+strlen("<obligadoContabilidad>"),strpos($xml,"</obligadoContabilidad>")-strlen("<obligadoContabilidad>")-strpos($xml,"<obligadoContabilidad>")); 

	
         $sqlCliente="Select id_venta from ventas a inner join clientes b
			on a.id_cliente=b.id_cliente and a.id_empresa=b.id_empresa
			where a.id_empresa='".$sesion_id_empresa."' and b.cedula='".$txtRuc."' and codigo_pun='".$idEstablecimiento."' 
			and codigo_lug='".$idEmision."' and numero_factura_venta='".intval($secuencial)."'";
		
	    $result=mysql_query($sqlCliente);
			$response['validacion']=	$sqlCliente;
		$id_venta=0;
		while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
		{  
			$id_venta=$row['id_venta'];
		}
		if($id_venta > 0) 
		{
            $response['advertencias'][]= "VENTA YA FUE REGISTRADA";	
			echo json_encode($response);exit;
		}
		else
		{
            
            	$numeroAutorizacion='';
				if(strpos($xml,"<numeroAutorizacion>")>0){
				    	$numeroAutorizacion=substr($xml,strpos($xml,"<numeroAutorizacion>")+strlen("<numeroAutorizacion>"),strpos($xml,"</numeroAutorizacion>")-strlen("<numeroAutorizacion>")-strpos($xml,"<numeroAutorizacion>"));
				}
		 $response['numeroAutorizacion']=$numeroAutorizacion;
			$fecha=substr($xml,strpos($xml,"<fechaEmision>")+strlen("<fechaEmision>"),strpos($xml,"</fechaEmision>")-strlen("<fechaEmision>")-strpos($xml,"<fechaEmision>"));

		
		    $timestamp = strtotime(str_replace('/', '-', $fecha));

			
			$newDate1 = date("Y-m-d h:i:s", $timestamp );

			
			$sub_total=substr($xml,strpos($xml,"<totalSinImpuestos>")+strlen("<totalSinImpuestos>"),strpos($xml,"</totalSinImpuestos>")-strlen("<totalSinImpuestos>")-strpos($xml,"<totalSinImpuestos>"));
		   
// 			try {
// 				$sqlp = "Select max(numero_factura_venta) as numero_factura_venta From ventas where codigo_pun='".$idEstablecimiento."' and 
// 				codigo_lug='".$idEmision."'  and  tipo_documento ='1' AND   id_empresa='".$sesion_id_empresa."' ";
// 					$resultp=mysql_query($sqlp);
// 					$numero_factura_venta=0;
// 					while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
// 					{
// 					   $numero_factura_venta=$rowp['numero_factura_venta'];
// 					}
// 					$numero_factura_venta++;
// 				}
// 			catch(Exception $ex) 
// 				{  $response['errores'][]= "Error en el numero maximo de factura: ".$ex; echo json_encode($response);exit; }
				

	        $numero_factura_venta =	$secuencial=substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
            // $numero_factura_venta = ceros($numero_factura_venta);
            	$caducidad=null;
				if(strpos($xml,"<fechaAutorizacion>")>0){
				    	$caducidad=substr($xml,strpos($xml,"<fechaAutorizacion>")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion>"));
				    	 $caducidad=substr(str_replace("T"," ",$caducidad),0,19);
				}
			$clave_de_acceso=null;
				if(strpos($xml,"<claveAcceso>")>0){
				    	$clave_de_acceso=substr($xml,strpos($xml,"<claveAcceso>")+strlen("<claveAcceso>"),strpos($xml,"</claveAcceso>")-strlen("<claveAcceso>")-strpos($xml,"<claveAcceso>"));
				}
				
			$importeTotal=substr($xml,strpos($xml,"<importeTotal>")+strlen("<importeTotal>"),strpos($xml,"</importeTotal>")-strlen("<importeTotal>")-strpos($xml,"<importeTotal>"));
			$totalConImpuestos=substr($xml,strpos($xml,"<totalConImpuestos>")+strlen("<totalConImpuestos>"),strpos($xml,"</totalConImpuestos>")-strlen("<totalConImpuestos>")-strpos($xml,"<totalConImpuestos>"));
			if(strpos($xml,"<totalDescuento>")>0){
			    $totalDescuento=substr($xml,strpos($xml,"<totalDescuento>")+strlen("<totalDescuento>"),strpos($xml,"</totalDescuento>")-strlen("<totalDescuento>")-strpos($xml,"<totalDescuento>"));
			}else {
			    $totalDescuento=0;
			}
			

			$totalImpuesto=substr($xml,strpos($xml,"<totalImpuesto>")+strlen("<totalImpuesto>"),strpos($xml,"</totalImpuesto>")-strlen("<totalImpuesto>")-strpos($xml,"<totalImpuesto>"));
		    
		    
		    $subTotal12=0;
		    $subTotal0=0;
		
			$i=0;	
			$totalImpuestos = 0;
			$impuestosExiste = '';
			if(isset($xml1->comprobante->$tipo->$info->totalConImpuestos)){
			    
				$impuestosExiste =$xml1->comprobante->factura->infoFactura->totalConImpuestos;
			}else if( isset($xml1->$info->totalConImpuestos) ){
				$impuestosExiste =$xml1->$info->totalConImpuestos; 
			}
			
		

			foreach ($impuestosExiste->totalImpuesto as $totalImpuesto) 
			{
				$codigo_a[$i]= $totalImpuesto->codigo; 
				$codigoPorcentaje_a[$i]= $totalImpuesto->codigoPorcentaje;
				$descuentoAdicional_a[$i]= $totalImpuesto->descuentoAdicional;
				$baseImponible_a[$i]= $totalImpuesto->baseImponible;
				$valor_a[$i]= $totalImpuesto->valor;
				$totalImpuestos = $totalImpuestos+ floatval($totalImpuesto->valor);
				if ($codigoPorcentaje_a[$i]>'2') 
				{
					$subTotal12=$baseImponible_a[$i];  
					$iva= $valor_a[$i];
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
				$sqlm="Select id_cliente From clientes where cedula='".$txtRuc."' and id_empresa='".$sesion_id_empresa."'";
                $resultCLiente=mysql_query($sqlm);
                $id_cliente=0;
                $numFilas = mysql_num_rows($resultCLiente);
                if($numFilas>0){
                    while($rowC=mysql_fetch_array($resultCLiente))//permite ir de fila en fila de la tabla
				{
                    $id_cliente=$rowC['id_cliente'];
                    
				}
                }else{
                    $hoy=date('Y-m-d');
					if($obligadoContabilidad=='SI'){
						$oConta = 1;
					}else{
						$oConta = 0;
					}
					$palabras = explode(" ", $txtRazonSocialComprador);
					
					$nombre = $palabras[0].' '.$palabras[1];
					$apellido = $palabras[2].' '.$palabras[3];
					$email='';
					$movil='';
					$telefono='';
					 $response['ent']=0;
					 
					foreach ($xml1->infoAdicional->campoAdicional as $campoActual) {
					  
						$nombreAtributo = $campoActual['nombre'];
						// echo "Atributo 'nombre': " . $nombreAtributo . " valor:".$campoActual." <br>";
						if($nombreAtributo!='Email Cliente' && $nombreAtributo!='TELÉFONO' && $nombreAtributo!='CELULAR'){
				// 			$sqlInfoAdicional = "INSERT INTO `info_adicional`( `campo`, `descripcion`, `id_venta`, `id_empresa`,xml) VALUES ('".$nombreAtributo."','".$campoActual."','".$id_venta."','".$sesion_id_empresa."',1)";
				// 				 $response['verificar']=$sqlInfoAdicional;
				// 		$resultInfoAdicional = mysql_query($sqlInfoAdicional);
						}
						if($nombreAtributo =='TELÉFONO'){
							$telefono=$campoActual;
						}
						if($nombreAtributo =='Email Cliente'){
							$email=$campoActual;
						}
						if($nombreAtributo =='CELULAR'){
							$movil=$campoActual;
						}
					}
					$estado='Activo';
					 $nuevoCliente = "INSERT INTO `clientes`( `nombre`, `apellido`, `direccion`, `cedula`, `telefono`, `movil`, `estado`,id_ciudad , `fecha_registro`,numero_cargas,estado_civil, tipo,	numero_casa,`id_empresa`, `caracter_identificacion`, `prop_nombre`, `empresaCliente`, `razonSocial`, `contribuyente_especial`,tipo_cliente,email) VALUES ('".$nombre."','".$apellido."','".$txtDireccionComprador."','".$txtRuc."','".$telefono."','".$movil."','".$estado."','".$sesion_empresa_id_ciudad."','".$hoy."',0,'Indefinido','0','0','".$sesion_id_empresa."','".$txtTipoIdentificacionComprador."','','0','".$txtRazonSocialComprador."','".$oConta."',0,'".$email."')";
                    $resultNuevoCliente = mysql_query($nuevoCliente);
                    $id_cliente = mysql_insert_id();
					$response['sql'][]= $nuevoCliente;
					
                }	
			}	
			
			catch(Exception $ex) {  $response['errores'][]= "Error en  clientes : ".$ex;  echo json_encode($response);exit;}
	
			$retfuente=0;

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
			$montoIce=0;
			$id_vendedor=0;
			$id_proveedor=0;
			if(trim($caducidad)!=''){
			    $sql="INSERT INTO `ventas`( `fecha_venta`, `estado`, `total`, `sub_total`, `sub0`, `sub12`, `descuento`, `propina`, `numero_factura_venta`, `id_iva`, `montoIce`, `id_vendedor`, `id_cliente`, `id_empresa`, `tipo_documento`, `codigo_pun`, `codigo_lug`, `id_forma_pago`, `id_usuario`,xml,total_iva,Autorizacion, FechaAutorizacion,ClaveAcceso,Retfuente ) VALUES 
            ('".$newDate1."','Activo','".$importeTotal."','".$sub_total."','".$subTotal0."','".$subTotal12."','".$totalDescuento."','0','".$numero_factura_venta."','".$txtIdIva."','".$montoIce."','".$id_vendedor."','".$id_cliente."','".$sesion_id_empresa."',1,'".$idEstablecimiento."','".$idEmision."','0','".$sesion_usuario."',1,'".$totalImpuestos."','".$numeroAutorizacion."','".$caducidad."','".$clave_de_acceso."','0')";
			}else{
			    $sql="INSERT INTO `ventas`( `fecha_venta`, `estado`, `total`, `sub_total`, `sub0`, `sub12`, `descuento`, `propina`, `numero_factura_venta`, `id_iva`, `montoIce`, `id_vendedor`, `id_cliente`, `id_empresa`, `tipo_documento`, `codigo_pun`, `codigo_lug`, `id_forma_pago`, `id_usuario`,xml,total_iva ) VALUES 
            ('".$newDate1."','Activo','".$importeTotal."','".$sub_total."','".$subTotal0."','".$subTotal12."','".$totalDescuento."','0','".$numero_factura_venta."','".$txtIdIva."','".$montoIce."','".$id_vendedor."','".$id_cliente."','".$sesion_id_empresa."',1,'".$idEstablecimiento."','".$idEmision."','0','".$sesion_usuario."',1,'".$totalImpuestos."')";
			}
            
//   $response['sql'][]= $sql;
			$result=mysql_query($sql);
			$id_venta = mysql_insert_id();
			 $response['id_venta']=$id_venta;
			 $response['establecimiento']=$idEstablecimiento;
			 $response['emision']=$idEmision;
			  $response['numero_factura_venta']=$numero_factura_venta;
			if($result) { 
			    	foreach ($xml1->infoAdicional->campoAdicional as $campoActual) {
					   
						$nombreAtributo = (string)$campoActual['nombre'];
				
						if($nombreAtributo!='Email Cliente' && $nombreAtributo!='TELÉFONO' && $nombreAtributo!='CELULAR'){
						    
							$sqlInfoAdicional = "INSERT INTO `info_adicional`( `campo`, `descripcion`, `id_venta`, `id_empresa`,xml) VALUES ('".$nombreAtributo."','".$campoActual."','".$id_venta."','".$sesion_id_empresa."',1)";
								 $response['verificar']=$sqlInfoAdicional;
						$resultInfoAdicional = mysql_query($sqlInfoAdicional);
						}
					
					}
               $response['mensajes'][]= "Encabezado Venta Registrado con exito";
				if($limite==0){
					$limite=0;
					
				}else if($limite==1){
					$limite=$limite-2;
					
				}else{
					
					$limite=$limite-1;
				}
				
			// 	$cmbEmi;
				$sqlNumFac ="update emision set numFac='".$numero_factura_venta."' where id ='".$idEmision."' ";
				$result=mysql_query($sqlNumFac) ;
				$response['sql'][]= $sqlNumFac;
				
				$sqlEmpresa2="UPDATE `empresa` SET `limiteFacturas`='$limite' WHERE id_empresa='$sesion_id_empresa' ";
				$resultEmpresa2=mysql_query($sqlEmpresa2) ;
				$response['sql'][]= $sqlEmpresa2;
             }else{
                $response['errores'][]= "Error al guardar encabezado venta : ".mysql_error();
                echo json_encode($response);
                exit;
             }   
			 $detallaExiste = '';
			 if(isset($xml1->comprobante->factura->detalles)){
				 $detallaExiste = $xml1->comprobante->factura->detalles;
				
				
			 }else if( isset($xml1->detalles) ){
				 $detallaExiste = $xml1->detalles;
				
			 }
			 	

			  $sqlBod="select id_centro_costo,id_cuenta from centro_costo where empresa= '".$sesion_id_empresa."' AND tipo='2' ORDER BY predeterminado DESC LIMIT 1";
					$result=mysql_query($sqlBod);
					$id_bodega=0;
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_bodega=$row['id_centro_costo'];
						$id_cuenta_centro_costo = $row['id_cuenta'];
					}

			foreach ($detallaExiste->detalle as $detalle) 
			{
			     //$response['mensajes'][]= "ejecuta detalle productos";
			     //if(isset($detalle->codigoPrincipal) ){
			     //    	$codPrincipal= $detalle->codigoPrincipal;
			     //}else{
			     //    $codPrincipal= $detalle->codigoAuxiliar;
			     //}
			       $codPrincipal= $detalle->codigoAuxiliar;
				$codAuxiliar= $detalle->codigoAuxiliar;
				$descripcion= $detalle->descripcion;
				$cantidad= $detalle->cantidad;
				$precio= $detalle->precioUnitario;
				

			
				$sql1="SELECT id_categoria from categorias where id_empresa= '".$sesion_id_empresa."' and categoria='SERVICIOS'";
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
							$tarifa_b[$j]= $imp->impuesto->tarifa;
							$tarifa= $imp->impuesto->tarifa;
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
						$fecha_actual= date("Y-m-d");
						$response['filas'][]= $numFilasCP;
				// if(true){

					$sql1="select id_producto from productos where id_empresa= '".$sesion_id_empresa."' and codPrincipal='".trim($codPrincipal)."'";
					$id_producto=0;
				 	$response['consulta'][]= $sql1;
				$result=mysql_query($sql1);
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_producto=$row['id_producto'];
					}
					if ($id_producto!=0) 
					{
						$sql="UPDATE `productos` SET `producto`='".trim(addslashes($descripcion))."',`existencia_minima`=1,`existencia_maxima`=10000,`stock`=stock+'".$cantidad."',`costo`='".$precio."',`id_categoria`='".$id_categoria."',`id_proveedor`='".$id_proveedor."',`precio1`='".$precio."',`precio2`='0.00',`iva`='".$id_iva_impuestos."',`ICE`=0,fecha_registro='".$fecha_actual."',id_empresa='".$sesion_id_empresa."' ,codigo='".$codPrincipal."',codPrincipal='".$codPrincipal."',codAux='".$codAuxiliar."', grupo='".$id_bodega."' ,id_cuenta='".$id_cuenta_centro_costo."'  WHERE id_producto = $id_producto";
						
						$resultado=mysql_query($sql);
				 		$response['consulta'][]= $sql;		
						if($resultado) {
						    	$response['sql'][]= $sql;
							$response['mensajes'][]= "Producto : '".$descripcion."' actualizado con exito";

							$sqlBuscarBodega="SELECT cantBodegas.`id`, cantBodegas.`idBodega`, cantBodegas.`idProducto`, cantBodegas.`cantidad`, cantBodegas.`proceso`, bodegas.id_empresa FROM `cantBodegas` INNER JOIN bodegas ON bodegas.id = cantBodegas.idBodega WHERE cantBodegas.idProducto='".$codPrincipal."'  AND bodegas.id_empresa='".$sesion_id_empresa."' ORDER BY cantidad DESC LIMIT 1; ";
							$resultBuscarBodega = mysql_query($sqlBuscarBodega);
							$idBodega=0;
							while($rowBB= mysql_fetch_array($resultBuscarBodega)){
								$idBodega = $rowBB['idBodega'];
							}

							$sqlActualizaBodega = " UPDATE `cantBodegas` SET `cantidad`=cantidad-$cantidad  WHERE idProducto='" . $codPrincipal."' AND `idBodega`=$idBodega ";
							$resultActualizaBodega =  mysql_query($sqlActualizaBodega);

							}else{
								
								$response['errores'][]= "Error al guardar el producto: ".mysql_error(); 
								echo json_encode($response);
								exit;
							}   
					}else{						
						$sql="INSERT INTO `productos` (`producto`,`existencia_minima`,`existencia_maxima`,`stock`,`costo`,`id_categoria`,`id_proveedor`,`precio1`,`precio2`,`iva`,`ice`,`fecha_registro`,`id_empresa`,codigo,`codPrincipal`,`codAux`,`tipos_compras`,`produccion`,`proceso`,`id_cuenta`,img,grupo) values ('".trim(addslashes($descripcion))."','1','10000','".$cantidad."','".$precio."','".$id_categoria."','".$id_proveedor."','".$precio."','0.00','".$id_iva_impuestos."','0','".$fecha_actual."','".$sesion_id_empresa."','".$codPrincipal."','".$codPrincipal."','".$codAuxiliar."','2','No','0','".$id_cuenta_centro_costo."',NULL,'".$id_bodega."')";

						$resultado=mysql_query($sql);	
				 		$response['consulta'][]= $sql;	
						if($resultado) {
	$response['sql'][]= $sql;
							$response['mensajes'][]= "Producto : '".$descripcion."' registrado con exito";

							$sqlCodProd = "INSERT INTO `codigosproductos`( `codigo_producto`, `codigo_proveedor`, `id_empresa`, `id_proveedor`) VALUES ('".trim($codPrincipal)."','".trim($codPrincipal)."','".$sesion_id_empresa."','".$id_proveedor."')";
							$resultCodProd = mysql_query($sqlCodProd);
				 			$response['consulta'][]= $sqlCodProd;	

							}else{
								
								$response['errores'][]= "Error al guardar el producto: ".mysql_error(); 
								echo json_encode($response);
								exit;
							}   
					
				}

				// }
	
			}
			

			if ($id_venta>0) 
			{
				$tot_servicios=0;
			foreach ($detallaExiste->detalle as $detalle) {
  $codPrincipal= $detalle->codigoAuxiliar;
				// 	 if(isset($detalle->codigoPrincipal) ){
			 //        	$codPrincipal= $detalle->codigoPrincipal;
			 //    }else{
			 //        $codPrincipal= $detalle->codigoAuxiliar;
			 //    }
					$codAuxiliar=$detalle->codigoAuxiliar;		
					$cantidad= $detalle->cantidad;
					$precio= $detalle->precioUnitario;
					$descuento= $detalle->descuento;
					$valorTotal= $detalle->precioTotalSinImpuesto;
					$tot_servicios =$tot_servicios + floatval($valorTotal);
					
		$sql1="select id_producto,impuestos.iva, impuestos.id_iva from productos left join impuestos on impuestos.id_iva= productos.iva  where productos.id_empresa= '".$sesion_id_empresa."' and productos.codigo='" . $codPrincipal."'";
					$result=mysql_query($sql1);
					$id_producto=0;
					$iva_producto=0;
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_producto=$row['id_producto'];
						$iva_producto=$row['id_iva'];
					}
					
					
					

					if ($id_producto>0) {
						
					$sqlBuscarBodega="SELECT cantBodegas.`id`, cantBodegas.`idBodega`, cantBodegas.`idProducto`, cantBodegas.`cantidad`, cantBodegas.`proceso`, bodegas.id_empresa FROM `cantBodegas` INNER JOIN bodegas ON bodegas.id = cantBodegas.idBodega WHERE cantBodegas.idProducto='".$codPrincipal."'  AND bodegas.id_empresa='".$sesion_id_empresa."' ORDER BY cantidad DESC LIMIT 1; ";
					$resultBuscarBodega = mysql_query($sqlBuscarBodega);
					
							$idBodega=0;
							
							while($rowcB= mysql_fetch_array($resultBuscarBodega)){
								$idBodega = $rowcB['idBodega'];
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
									
								
							}
						$total_iva_producto = floatval($valorTotal) * (floatval($iva_detalle)/100);
						
						$sql="INSERT INTO `detalle_ventas`( `idBodega`, `idBodegaInventario`, `cantidad`, `estado`, `v_unitario`, `descuento`, `v_total`, `id_venta`, `id_servicio`,tipo_venta, `id_kardex`, `id_empresa`, `tarifa_iva`, `total_iva`) VALUES ('".$id_bodega."','".$idBodega."','".$cantidad."','Activo','".$precio."','".$descuento."','".$valorTotal."','".$id_venta."','".$id_producto."','2','".$id_producto."','".$sesion_id_empresa."','".$iva_producto."','".$total_iva_producto."' )";
						$response['sql'][]=$sql;
                            //  $response['mensajes'][]= $sql;
						$resultado=mysql_query($sql);
						if($resultado) {
                            $response['mensajes'][]= "Detalle de la venta Registrado con exito"; 

                            }else{
				// 			$response['consulta'][]= $sql;
                            $response['errores'][]="Error al guardar el detalle de la venta: ".mysql_error();
                            echo json_encode($response);
                            exit;
                            }   
					}
				}

			if($numeroAutorizacion!=''){
			     // COMIENZO proceso contable  
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
				 			$tipo_comprobante = "Diario";  
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
				 				$fecha= date("Y-m-d h:i:s"); 
				 				$descripcion = "Factura de venta #".$numero_factura_venta." realizada a ".$txtRazonSocialComprador; 
								
				 				$debe = $importeTotal; 
				 				$debe2 = $descuento; 
				 				$total_debe = $debe + $debe2; 
								
				 				$haber1 = $sub_total; 
				 				$haber2 = $totalImpuestos; 
								
				 				$total_haber = $haber1 + $haber2 ; 
								
				 				$tipo_mov="F"; 
								
				 				$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')"; 
				 				$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  '); 
				 				$id_comprobante=mysql_insert_id(); 
				 				$response['sql'][]=$sqlC; 
				 				$sqlCentroCosto = "SELECT `id`, `id_empresa`, `codigo`, `direccion`, `centro_costo` FROM `establecimientos` WHERE id=$idEstablecimiento ;"; 
				 				$resultCentroCosto = mysql_query($sqlCentroCosto); 
				 				$centroCosto=0; 
				 				while($rowccc = mysql_fetch_array($resultCentroCosto) ){ 
				 					$centroCosto= $rowccc['centro_costo']; 
				 				} 
				 				$fecha_venta = 	$newDate1; 
				 				$numero_factura = $numero_factura_venta; 
				 				$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe, 
				 				total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta,centroCosto)  
				 				values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$newDate1."',	'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."', 
				 				'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' ,'".$centroCosto."')"; 
				 				$response['sql'][]=$sqlLD; 
								
				 				$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  '); 
				 				$id_libro_diario=mysql_insert_id(); 
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

			

				foreach ($xml1->comprobante->factura->infoFactura->pagos->pago as $pagoActual){
					$valor = $pagoActual->total ;
					$lin_diario=$lin_diario+1;
    				$debeVector[$lin_diario]=$valor;
    				$haberVector[$lin_diario]=0;

					$idPlanCuentas[$lin_diario]=$id_plan_cuenta;
					$formaPagoId[$lin_diario]=$formaPagoId2;

					$cmbTipoDocumentoFVC="Factura No.";
					$estadoCC = "Pendientes";
					$fechaActual = new DateTime();
					$fechaActual->add(DateInterval::createFromDateString('1 month'));
					$fechaFormateada = $fechaActual->format('Y-m-d'); 
					 $sqlCuentaCobrar = "insert into cuentas_por_cobrar ( tipo_documento,         numero_factura,         referencia,        valor,          saldo,        numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor,id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
    								. "values                      ('".$cmbTipoDocumentoFVC."','".$numero_factura."','".$txtRazonSocialComprador."' ,'".$importeTotal."','".$importeTotal."',          '',             '".$fechaFormateada."', null, null, '".$id_cliente."', '".$id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";
					$resultCuentaCobrar = mysql_query($sqlCuentaCobrar);
                    	$response['sql'][]=$sqlCuentaCobrar; 


					$sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) VALUES     ('".$formaPagoId2."','0','".$id_venta."','".$sesion_id_empresa."','".$valor."','".$formaPagoTipo."', NULL );";
					$response['sql'][]=$sqlforma;
    				$respForma = mysql_query($sqlforma) ;
					if($respForma){
						if($formaPagoTipo==1 ){
						 $identificador="01";
						}
						else if($formaPagoTipo==2){
						  $ident=1;
							$identificador="02";
						}else if($formaPagoTipo==16 && $formaPagoTipo==17 ){
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
					if ($formaPagoTipo=='17')
					{
								$cmbTipoDocumento='Transferencia';
								$txtNumeroDocumento=$numero_factura;
								$txtDetalleDocumento="Transferencia de ".$txtRazonSocialComprador ;
								$txtFechaEmision=$fecha_venta;
								$txtFechaVencimiento=$fecha_venta;
								$saldo_conciliado = 0;
								$valorConciliacion = $valor;
								$estado = "No Conciliado";
								
								$sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$id_plan_cuenta."'
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
									  // echo "trans</br>".$sqlDB."</br>";  
								}else {
									
									$sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values
									('".$id_plan_cuenta."','".$saldo_conciliado."', '".$sesion_id_periodo_contable."');";
									$resultB=mysql_query($sqlB);
									$id_bancos=mysql_insert_id();
									$sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario)
									values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
									$resultDB=mysql_query($sqlDB);
									$id_detalle_banco=mysql_insert_id();
				 					// echo "bancos</br>".$sqlB."</br>"; 
				 					// echo "detalle</br>".$sqlDB."</br>"; 
								}		
					}

				 } 
				$tot_ventas=0;
    		
    			$tot_costo=0;
				
				

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
				 		$sql="select plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_cuenta,plan_cuentas.`codigo` AS plan_cuentas_codigo 
				 		from `plan_cuentas` plan_cuentas where plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and  plan_cuentas.`codigo` ='".$idcodigo_s."' "  ; 
  
				   $resultS=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');				 
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

			
				   if ($tot_servicios!=0) 
				   { 
				       
				 	  $lin_diario=$lin_diario+1; 
				   	// $idPlanCuentas[$lin_diario]= $plan_id_cuenta_servicio; 
				 		  $idPlanCuentas[$lin_diario]= $id_cuenta_centro_costo; 
				 		  $debeVector[$lin_diario]=0; 
				 	  $haberVector[$lin_diario]=$tot_servicios; 
					  

				   } 
				 //  echo  '$idPlanCuentas[$lin_diario]'.$id_cuenta_centro_costo; 

				 $sqlDescuento="SELECT id_plan_cuenta as codigo_plan_cuentas FROM formas_pago WHERE `id_tipo_movimiento` =14 and formas_pago.id_empresa='".$sesion_id_empresa."'  "; 
                 $resultDescuento=mysql_query($sqlDescuento); 
                 $idcodigo_d=0; 
                 while($row=mysql_fetch_array($resultDescuento)){ 
                     $idcodigo_d=$row['codigo_plan_cuentas']; 
                 } 
				 if ($descuento!=0) 
     					{ 
     						$lin_diario=$lin_diario+1; 
     						$idPlanCuentas[$lin_diario]= $idcodigo_d; 
     						$debeVector[$lin_diario]=$descuento; 
     						$haberVector[$lin_diario]=0; 
     					} 

				 		$txtTotalIvaFVC=	$totalImpuestos; 
				 if ($txtTotalIvaFVC!=0) 
     					{ 
    
         					$lin_diario=$lin_diario+1; 
         					$idPlanCuentas[$lin_diario]=$impuestos_id_plan_cuenta; 
         					$debeVector[$lin_diario]=0; 
         					$haberVector[$lin_diario]=$txtTotalIvaFVC; 
    
     					} 
				 for($i=1; $i<=$lin_diario; $i++) 
     					{ 
				 			$response['filaLD'][]=$i;  
   
     						if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 )) 
     						{ 
				 				$response['filaLD'][]=$i;  
     							$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values  
     							('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');"; 
				 				$response['sql'][]=$sqlDLD; 
     							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  '); 
     							$id_detalle_libro_diario=mysql_insert_id();		 
    							
     							$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';"; 
     							$result5=mysql_query($sql5); 
				 				$response['sql'][]=$sql5; 
     							while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla 
     							{ 
     								$id_mayorizacion=$row5['id_mayorizacion']; 
     							} 
     							$numero = mysql_num_rows($result5); // obtenemos el n煤mero de filas 
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
				 						$response['sql'][]=$sql6; 
     									$id_mayorizacion=mysql_insert_id(); 
     								} 
     								catch(Exception $ex)  
     								{ ?> <div class="transparent_ajax_error"> 
   									<p>Error en la insercion de la tabla mayorizacion: 
    									<?php echo "".$ex ?>
    									</p></div> 
    									<?php 
    								 	}
    							
    						}
    						}
    					}
			}	



				$sqlk="insert into kardes ( fecha, detalle,  id_factura,id_empresa) values
						('".$newDate1."','Venta','".$id_venta."', '".$sesion_id_empresa."')";
				$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
				$id_kardes=mysql_insert_id();

				$response['sql'][]=$sqlk;
				// echo json_encode($response);


				if($modoFacturacion=='200'){
    				    
					$emision_tipoEmision='F';
					
				}else{
					
					$emision_tipoEmision = $_SESSION['emision_tipoEmision'];
				}
				
				if ($emision_tipoEmision === 'E'){
							genXml($id_venta);
							
					$sqli="Select ClaveAcceso From ventas where id_venta='".$id_venta."' " ;
					$result=mysql_query($sqli);
					while($row=mysql_fetch_array($result)){
						$claveAcceso=$row['ClaveAcceso'];
					}
					if ($claveAcceso != ''){
						// echo "SI";
					}else{
						// echo "NO";
					}
							
				}
				
				
				
			}//if venta
		}
        }
		}
	} else  {
        $response['errores'][]= "No hay Archivo ";
        	}
	 			
            echo json_encode($response);


				 
?>
