<?php


	session_start();
	//Include database connection details
	require_once('../conexion.php');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    
    
    

	
	
	function quitarCerosIzquierda($numero) {
    // Usa ltrim para quitar los ceros a la izquierda
    $numeroSinCeros = ltrim($numero, '0');

    // Si el resultado es una cadena vacía, significa que el número original era solo ceros
    // y ahora debe ser '0'
    return ($numeroSinCeros === '') ? '0' : $numeroSinCeros;
    }
    
    function obtenerDatoXml($xml, $tag) {
    $inicio = strpos($xml, "<" . $tag . ">") + strlen("<" . $tag . ">");
    $fin = strpos($xml, "</" . $tag . ">", $inicio);
    return substr($xml, $inicio, $fin - $inicio);
}
function obtenerPrimerosTres($cadena) {
    return substr($cadena, 0, 3);
}

function obtenerSiguientesTres($cadena) {
    return substr($cadena, 3, 3);
}

function obtenerUltimosNumeros($cadena) {
    return substr($cadena, -3);
}
	

// Asegúrate de que este código se ejecute al inicio de tu script.
if (isset($_POST["fila"])) {
    $claveAcceso = $_POST["fila"];
       echo "XML:\n" . $claveAcceso. "\n";
} else {
    // Manejar el error o la falta de clave de acceso adecuadamente.
    die("Clave de acceso no proporcionada.");
}



function generarXMLCDATA($data) {
    if (!$data) {
        return ''; // Retorna una cadena vacía si $data es null.
    }

    $estado = $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;
    $numeroAutorizacion = $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
    $fechaAutorizacion = $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
    $comprobante = $data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;

    $s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $s .= "<autorizacion>\n";
    $s .= "<estado>$estado</estado>\n";
    $s .= "<numeroAutorizacion>$numeroAutorizacion</numeroAutorizacion>\n";
    $s .= "<fechaAutorizacion>$fechaAutorizacion</fechaAutorizacion>\n";
    $s .= "<comprobante><![CDATA[$comprobante]]></comprobante>\n";
    $s .= "<mensajes/>\n";
    $s .= "</autorizacion>";

    return $s;
}


function consultarComprobante($ambiente, $clave) {
    $slAutorWs = $ambiente == 1 ? 
        "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl" : 
        "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";


    try {
        $options = ['encoding' => 'UTF-8', 'trace' => 1];
        $olClient = new SoapClient($slAutorWs, $options);
        $olResp = $olClient->autorizacionComprobante(['claveAccesoComprobante' => $clave]);
          // echo "Solicitud XML:\n" . $olClient->__getLastRequest() . "\n";
          //   echo "Respuesta XML:\n" . $olClient->__getLastResponse() . "\n";
        return $olResp; // Retorna la respuesta directamente.
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        return null; // Retorna null en caso de error.
    }
}




$respuesta = consultarComprobante(2, $claveAcceso);

if ($respuesta && isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
    
    if ($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
        
        $xml = generarXMLCDATA($respuesta);
        try {
            $xml1 = new SimpleXMLElement($xml);


        $comprobante = $xml1->comprobante ;
        

 $xml = str_replace(array('<![CDATA[<?xml version="1.0" encoding="ISO-8859-1"?>'),'', $xml);
         $xml = str_replace(array('<![CDATA[ <?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>'),'', $xml);
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
        
        
         $xml = str_replace(array('<?xml version="1.0" encoding="UTF-8" ?>'),'', $xml);
         
        
       
         $xml = str_replace(array('<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>'),'', $xml);
       
         
        
        
        $xml1 = new SimpleXMLElement($xml);
	    $comprobante = $xml1->comprobante ;
        $codDoc=substr($xml,strpos($xml,"<codDoc>")+strlen("<codDoc>"),strpos($xml,"</codDoc>")-strlen("<codDoc>")-strpos($xml,"<codDoc>"));


if($codDoc=="01"){
	    

		$txtEmision=substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
		$numSerie=substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
        $secuencial=substr($xml,strpos($xml,"<secuencial>")+strlen("<secuencial>"),strpos($xml,"</secuencial>")-strlen("<secuencial>")-strpos($xml,"<secuencial>"));
        $txtRuc=substr($xml,strpos($xml,"<ruc>")+strlen("<ruc>"),strpos($xml,"</ruc>")-strlen("<ruc>")-strpos($xml,"<ruc>"));

		$impuestos_xmlB = '';
		if(isset($xml1->comprobante->factura->infoFactura->totalConImpuestos->totalImpuesto)){
		   
		   $impuestos_xmlB  = $xml1->comprobante->factura->infoFactura->totalConImpuestos->totalImpuesto;
	   }else if( isset($xml1->infoFactura->totalConImpuestos->totalImpuesto) ){
		   $impuestos_xmlB =$xml1->infoFactura->totalConImpuestos->totalImpuesto; 
	   }
	   
   $valorPorcentaje_impuesto=0;
	   foreach ( $impuestos_xmlB as $totalImpuestoB) 
	   {
		   $codigo_impuesto= $totalImpuestoB->codigo; 
		   $codigoPorcentaje_impuesto= $totalImpuestoB->codigoPorcentaje;
		   $valorPorcentaje_impuesto += floatval($totalImpuestoB->valor);
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
			and numSerie='".$numSerie."' and txtNum='".$secuencial."'";
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
	        //echo "</br>".$fecha;
		
		    $timestamp = strtotime(str_replace('/', '-', $fecha));
		    
		//	$timestamp = strtotime($fecha); 
			
			$newDate1 = date("Y-m-d h:i:s", $timestamp );
				
			//echo "</br>".$newDate1;
			
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
	
			$sql="INSERT INTO `compras`(`fecha_compra`, `total`, `sub_total`,         `subTotal0`,       `subTotal12`,`subTotalInvenarios`,`descuento` ,`exentoIVA`, `noObjetoIVA`, `id_iva`, `id_proveedor`,   `numero_factura_compra`, `id_empresa`, `numSerie`, `txtEmision`, `txtNum`, `autorizacion`, `caducidad`, `TipoComprobante`, `codSustento`,xml,total_iva) values  ('".$newDate1."','".$importeTotal."','".$sub_total."','".$subTotal0."','".$subTotal12."','".$sub_total."','".$totalDescuento."',0,0,'12','".$id_proveedor."','".$numero_factura_compra."','".$sesion_id_empresa."','".$numSerie."','".$txtEmision."','".$secuencial."','".$numeroAutorizacion."','".$newDate1."','".$codDoc."','01','1','".$valorPorcentaje_impuesto."')  ";
		

			$result=mysql_query($sql);

			if($result) { 
                $response['mensajes'][]= "Encabezado Compra Registrado con exito";
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
				

				$sqlCP="SELECT `id_codProd`, `codigo_producto`, `codigo_proveedor`, `id_empresa`, `id_proveedor` 
				FROM `codigosproductos` WHERE id_empresa= '".$sesion_id_empresa."' AND id_proveedor= '".$id_proveedor."' 
				AND codigo_producto ='".trim($codPrincipal)."' ";
				$resultCP= mysql_query($sqlCP);
				$numFilasCP = mysql_num_rows($resultCP);
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
							$tarifa_b[$j]= $imp->impuesto->tarifa;
							$tarifa=$tarifa_b[$j];
							$codigo = $imp->impuesto->codigo;
							$codigoPorcentaje =$imp->impuesto->codigoPorcentaje;
							if ($codigo == 2 ) 
							{
								$patron = "/^\d+$/";

								// if (preg_match($patron, $tarifa)) {
									
									$iva_p=$tarifa;
								// }
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
				if($numFilasCP>0){

					$sql1="select id_producto from productos where id_empresa= '".$sesion_id_empresa."' and codPrincipal='".trim($codPrincipal)."'";
					$id_producto=0;
				//  	$response['consulta'][]= $sql1;
				$result=mysql_query($sql1);
					while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
					{	  
						$id_producto=$row['id_producto'];
					}
					if ($id_producto!=0) 
					{
						$sql="UPDATE `productos` SET `producto`='".trim(addslashes($descripcion))."',`existencia_minima`=1,`existencia_maxima`=10000,`stock`=stock+'".$cantidad."',`costo`='".$precio."',`id_categoria`='".$id_categoria."',`id_proveedor`='".$id_proveedor."',`precio1`='".$precio."',`precio2`='0.00',`iva`='".$id_iva_impuestos."',`ICE`=0,fecha_registro='".$fecha_actual."',id_empresa='".$sesion_id_empresa."' ,codigo='".$codPrincipal."',codPrincipal='".$codPrincipal."',codAux='".$codAuxiliar."'  WHERE id_producto = $id_producto";
						
						$resultado=mysql_query($sql);
				//  		$response['consulta'][]= $sql;		
						if($resultado) {
						  //  	$response['sql'][]= $sql;
							$response['mensajes'][]= "Producto : '".$descripcion."' actualizado con exito";

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
					}

				}else{						
						$sql="INSERT INTO `productos` (`producto`,`existencia_minima`,`existencia_maxima`,`stock`,`costo`,`id_categoria`,`id_proveedor`,`precio1`,`precio2`,`iva`,`ice`,`fecha_registro`,`id_empresa`,codigo,`codPrincipal`,`codAux`,`tipos_compras`,`produccion`,`proceso`,`id_cuenta`,img) values ('".trim(addslashes($descripcion))."','1','10000','".$cantidad."','".$precio."','".$id_categoria."','".$id_proveedor."','".$precio."','0.00','".$id_iva_impuestos."','0','".$fecha_actual."','".$sesion_id_empresa."','".$codPrincipal."','".$codPrincipal."','".$codAuxiliar."','1','No','0','0',NULL)";

						$resultado=mysql_query($sql);	
				//  		$response['consulta'][]= $sql;	
						if($resultado) {
// 	$response['sql'][]= $sql;
				// 			$response['mensajes'][]= "Producto : '".$descripcion."' registrado con exito";

							$sqlCodProd = "INSERT INTO `codigosproductos`( `codigo_producto`, `codigo_proveedor`, `id_empresa`, `id_proveedor`) VALUES ('".trim($codPrincipal)."','".trim($codPrincipal)."','".$sesion_id_empresa."','".$id_proveedor."')";
							$resultCodProd = mysql_query($sqlCodProd);
				 			// $response['consulta'][]= $sqlCodProd;	

							}else{
								
								$response['errores'][]= "Error al guardar el producto: ".mysql_error(); 
								echo json_encode($response);
								exit;
							}   
					
				}
	
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
					$cantidad= $detalle->cantidad;
					$precio= $detalle->precioUnitario;
					$descuento= $detalle->descuento;
					$valorTotal= $detalle->precioTotalSinImpuesto;
						
					$sql1="select productos.id_producto,impuestos.iva from productos left join impuestos on impuestos.id_iva= productos.iva where productos.id_empresa= '".$sesion_id_empresa."' and productos.codigo='" . $codPrincipal."'";
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
					
					
					if ($id_producto>0) {
					    $total_iva_producto = floatval($valorTotal) * (floatval($iva_detalle)/100);
						$sql="INSERT INTO `detalle_compras` (idBodega,cantidad,valor_unitario,`des`,valor_total,id_compra,id_producto,id_empresa,`xml`,iva,total_iva)
						values ('".$id_bodega."','".$cantidad."','".$precio."','".$descuento."','".$valorTotal."','".$id_compra."','".$id_producto."','".$sesion_id_empresa."','1','".$iva_producto."','".$total_iva_producto."')";
//  $response['mensajes'][]= $sql;
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
    foreach ($xml1->reembolsos->reembolsoDetalle as $reembolso){
        
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

					$tarifa_d_impuesto = $d_impuestos->tarifa;

					$base_d_impuesto = $d_impuestos->baseImponibleReembolso;

					$impuesto_d_impuesto = $d_impuestos->impuestoReembolso;

					 $sql_d_impuesto = "INSERT INTO `impuestos_reembolso`(`codigo_impuesto`, `codigo_porcentaje`, `tarifa`, `base_imponible`, `impuesto`, `id_reembolsos`) VALUES ('".$codigo_d_impuesto."','".$codigo_porcentaje_d_impuesto."','".$tarifa_d_impuesto."','".$base_d_impuesto."','".$impuesto_d_impuesto."','".$id_reembolso."')";
					$result_impuesto = mysql_query($sql_d_impuesto);

				}
				foreach ($reembolso->compensacionesReembolso->compensacionReembolso as $compensacion) 
				{
					$codigo_compensacion = $compensacion->codigo;
					$tarifa_compensacion = $compensacion->tarifa;
					$valor_compensacion = $compensacion->valor;

					  $sql_compensacion = "INSERT INTO `compensaciones_reembolso`(`codigo`, `tarifa`, `valor`, `id_reembolso`) VALUES ('".$codigo_compensacion."','".$tarifa_compensacion."','".$valor_compensacion."','".$id_reembolso."')";
					$result_compensacion = mysql_query($sql_compensacion);

				}				
			}

            echo json_encode($response);
            
        }else{
            
             $txtEmision=substr($xml,strpos($xml,"<ptoEmi>")+strlen("<ptoEmi>"),strpos($xml,"</ptoEmi>")-strlen("<ptoEmi>")-strpos($xml,"<ptoEmi>"));
		$numSerie=substr($xml,strpos($xml,"<estab>")+strlen("<estab>"),strpos($xml,"</estab>")-strlen("<estab>")-strpos($xml,"<estab>"));
        $secuencia = quitarCerosIzquierda(substr($xml, strpos($xml, "<secuencial>") + strlen("<secuencial>"), strpos($xml, "</secuencial>") - strlen("<secuencial>") - strpos($xml, "<secuencial>")));
        $txtRuc=substr($xml,strpos($xml,"<ruc>")+strlen("<ruc>"),strpos($xml,"</ruc>")-strlen("<ruc>")-strpos($xml,"<ruc>"));
		$numeroAutorizacion=substr($xml,strpos($xml,"<numeroAutorizacion>")+strlen("<numeroAutorizacion>"),strpos($xml,"</numeroAutorizacion>")-strlen("<numeroAutorizacion>")-strpos($xml,"<numeroAutorizacion>"));
		$claveAccesoRet=substr($xml,strpos($xml,"<claveAcceso>")+strlen("<claveAcceso>"),strpos($xml,"</claveAcceso>")-strlen("<claveAcceso>")-strpos($xml,"<claveAcceso>"));
		
		$numDocSustento=substr($xml,strpos($xml,"<numDocSustento>")+strlen("<numDocSustento>"),strpos($xml,"</numDocSustento>")-strlen("<numDocSustento>")-strpos($xml,"<numDocSustento>"));
        
        $fecha=substr($xml,strpos($xml,"<fechaEmision>")+strlen("<fechaEmision>"),strpos($xml,"</fechaEmision>")-strlen("<fechaEmision>")-strpos($xml,"<fechaEmision>"));
        $fechaEmision = new DateTime($fecha);
        $fechaEmisionF = $fechaEmision->format('Y-m-d');
        
        
		$fechaAutorizacion=substr($xml,strpos($xml,"<fechaAutorizacion>")+strlen("<fechaAutorizacion>"),strpos($xml,"</fechaAutorizacion>")-strlen("<fechaAutorizacion>")-strpos($xml,"<fechaAutorizacion>"));
        $fechaAutorizacionObj = new DateTime($fechaAutorizacion);
        $fechaAutorizacionFormateada = $fechaAutorizacionObj->format('Y-m-d');
    
    
        $impuestos=substr($xml,strpos($xml,"<impuestos>")+strlen("<impuestos>"),strpos($xml,"</impuestos>")-strlen("<impuestos>")-strpos($xml,"<impuestos>"));



        $est = obtenerPrimerosTres($numDocSustento);
        $emision = obtenerSiguientesTres($numDocSustento);
        $numeracion = obtenerUltimosNumeros($numDocSustento);

        $sqlestablecimiento = "SELECT id FROM establecimientos WHERE codigo = '$est'  AND id_empresa = $sesion_id_empresa";
        $resultEstablecimiento = mysql_query($sqlestablecimiento);
        $idEstablecimiento = 0;
        
        if ($resultEstablecimiento) {
            
            $rowEstablecimiento= mysql_fetch_assoc($resultEstablecimiento);
            $idEstablecimiento = $rowEstablecimiento['id'];
            
            $sqlEmision = "SELECT id FROM emision WHERE id_est = '$idEstablecimiento' and  codigo='$emision' ";
            $resultEmision = mysql_query($sqlEmision);
            $idEmision = 0;
            
            if ($resultEmision) {
                
                $rowEmision= mysql_fetch_assoc($resultEmision);
                $idEmision = $rowEmision['id'];
                
                $sqlFactura = " SELECT numero_factura_venta,id_venta,codigo_pun,codigo_lug,fecha_venta FROM ventas WHERE numero_factura_venta = '$numeracion' and codigo_pun='$idEstablecimiento' 
                            and codigo_lug='$idEmision' AND id_empresa = $sesion_id_empresa";
                            
                $resultFactura = mysql_query($sqlFactura);
                $numeroFactura = 0;
            
                if ($resultFactura) {
                    
                    $rowFactura = mysql_fetch_assoc($resultFactura);
                    $numeroFactura = $rowFactura['id_venta'];
                    $numeroFacturaVenta = $rowFactura['numero_factura_venta'];
                    $fecha_venta = $rowFactura['fecha_venta'];
                    
                    
                    if ($numeroFactura == 0) {
                        
                            $response['errores'][] = "NO EXISTE LA VENTA " . mysql_error();
                            echo json_encode($response);
                            exit;
                        
                    }else{
                        
                        $numeroFactura = $numeroFactura;
                        
                        $response['mensajes'][] = "SI EXISTE";
                        
                        $sqlValidacion = "SELECT COUNT(*) as cantidad FROM `mvretencion` WHERE `Factura_id` = '$numeroFactura' AND `Numero` = '$numeroFacturaVenta'";
                        $resultValidacion = mysql_query($sqlValidacion);
                        
                        if ($resultValidacion) {
                            $rowValidacion = mysql_fetch_assoc($resultValidacion);
                            $cantidadExistente = $rowValidacion['cantidad'];
                        
                            if ($cantidadExistente > 0) {
                                // La retención ya existe, manejar la situación como desees
                                $response['errores'][] = "La retención ya existe para Factura_id: $numeroFactura y Numero: $numeroFacturaVenta";
                                echo json_encode($response);
                                exit;
                            } else {
                                // La retención no existe, puedes proceder con el insert
                                $sqlRetencion = "INSERT INTO `mvretencion` (`Factura_id`, `Numero`, `Fecha`,
                                    `TipoC`,
                                    `Autorizacion`,
                                    `Total`,
                                    `Total1`,
                                    `FechaAutorizacion`,
                                    `Retfuente`,
                                    `ClaveAcceso`,
                                    `Observaciones`,
                                    `Observaciones2`,
                                    `Serie`,
                                    `anulado`
                                ) VALUES (
                                    '$numeroFactura',
                                    '$secuencia',
                                    '$fechaEmisionF',
                                    '1',
                                    '$numeroAutorizacion',
                                    NULL,
                                    NULL,
                                    '$fechaAutorizacionFormateada',
                                    '8',
                                    '$claveAccesoRet',
                                    'Correo Enviado',
                                    'Correo Enviado',
                                    '$est-$emision',
                                    '0'
                                )";
                        
                                $resultRetencion = mysql_query($sqlRetencion);
                                $idMvRetencion = mysql_insert_id();
                        
                                if ($resultRetencion) {
                                  
                                    // $impuestos = simplexml_load_string($xml)->impuestos->impuesto;
                         $impuestos = $xml1->comprobante->comprobanteRetencion->impuestos;
                                   
                                    foreach ($impuestos->impuesto as $impuestox) {
                                        var_dump($impuestox);
                                        $codigo_actual=$impuestox->codigo;
                                        $codigoRetencion_actual=$impuestox->codigoRetencion;
                                        $baseImponible_actual=$impuestox->baseImponible;
                                        $porcentajeRetener_actual=$impuestox->porcentajeRetener;
                                        $valorRetenido_actual=$impuestox->valorRetenido;
                                        $codDocSustento_actual=$impuestox->codDocSustento;
                                        
                                         $numDocSustento_actual=$impuestox->numDocSustento;
                                          $fechaEmisionDocSustento_actual=$impuestox->fechaEmisionDocSustento;
                                          
                                        $sqlDV="INSERT INTO `dvretencion`( `Retencion_id`, `EjFiscal`, `BaseImp`, `TipoImp`, `CodImp`, `Porcentaje`) VALUES ('".$idMvRetencion."','".substr($fecha_venta,0,4)."','".$baseImponible_actual."','".$codigo_actual."','".$codigoRetencion_actual."','".$porcentajeRetener_actual."')";
                                        $resultDV = mysql_query($sqlDV);
                                       
                                        // Procesar cada impuesto...
                                        // $codigo = obtenerDatoXml($impuesto, "codigo");
                                        // Resto del procesamiento...
                                    }
                        
                                } else {
                                    // Manejo de errores
                                    $response['errores'][] = "Error al ejecutar la consulta de retención: " . mysql_error();
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        } else {
                            // Manejo de errores
                            $response['errores'][] = "Error al ejecutar la consulta de validación: " . mysql_error();
                            echo json_encode($response);
                            exit;
                        }
                    }
                    
                    
                } else {
                    
                    $response['errores'][] = "Error al ejecutar la consulta de la factura: " . mysql_error();
                    echo json_encode($response);
                    exit;
                
                }
                
            } else {
            
                $response['errores'][] = "Error al ejecutar la consulta de punto de emision: " . mysql_error();
                echo json_encode($response);
                exit;
        
            }
            

        } else {
            
                $response['errores'][] = "Error al ejecutar la consulta de establecimientos: " . mysql_error();
                echo json_encode($response);
                exit;
        
        }
            
            
            
        }
        

        } catch (Exception $e) {
            echo 'Excepción capturada al convertir a SimpleXMLElement: ',  $e->getMessage(), "\n";
        }
    } else {
        echo "La factura no está autorizada.";
    }
} else {
    echo "No se pudo obtener el estado de la autorización.";
}

        
        

        

	

?>

