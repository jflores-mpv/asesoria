<?php

// Mostrar errores
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);


require_once('../conexion.php');

include "facturaXml.php";

// Verificar si los datos POST están establecidos
if (isset($_POST['correo']) && isset($_POST['tipo_comprobante']) && isset($_POST['id'])) {
    $correo = $_POST['correo'];
    $tipo_comprobante = $_POST['tipo_comprobante'];
    $idComprobante = $_POST['id'];


    if( $_POST['correo']==1){
	     
if($_POST['tipo_comprobante']!=7){

$sqlFactura="SELECT ventas.tipo_documento, establecimientos.codigo AS estcodigo,emision.codigo as emicodigo,ventas.numero_factura_venta, clientes.email,empresa.ruc

FROM ventas,establecimientos,emision,clientes,empresa

WHERE ventas.id_empresa=empresa.id_empresa and establecimientos.id=ventas.`codigo_pun` and emision.id=ventas.codigo_lug and clientes.id_cliente= ventas.id_cliente and 

ventas.id_venta='".$_POST['id']."' "; 
	
	
	$sqlFacturaResult = mysql_query ($sqlFactura);
	
	$rowFactura=mysql_fetch_array($sqlFacturaResult);//permite ir de fila en fila de la tabla
	
	switch($rowFactura['tipo_documento']){
		case 1:
			$doc="factura";
		break;
		case 4:
			$doc="notaCredito";
		break;
		case 6:
			$doc="guiaRemision";
		break;
		
	}
	
	sendMail("../xmls/autorizados/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_venta']).".xml",$rowFactura['email'],$_POST['id'],$doc);

    }

}
	 
if( $_POST['correo']==''){ 
	 
	
if(isset($_POST['tipo_comprobante']) && $_POST['tipo_comprobante'] == 1) {

    genXml($_POST['id']);

    // Asegúrate de sanitizar la entrada para prevenir inyecciones SQL
    $id = mysql_real_escape_string($_POST['id']);

    $sqli = "SELECT ClaveAcceso FROM ventas WHERE id_venta='" . $id . "'";
    $result = mysql_query($sqli);

    $claveAcceso = '';
    while($row = mysql_fetch_array($result)) {
        $claveAcceso = $row['ClaveAcceso'];
    }

    if ($claveAcceso != '') {
        echo "1";
    } else {
        echo "2";
    }
}

if( $_POST['tipo_comprobante']==7){
    
    if($sesion_id_empresa==41){
        genXmlRet20($_POST['id']);
    }else{
        genXmlRet($_POST['id']);
    }
	        
	        
    $sqli="Select ClaveAcceso From mcretencion where Id='".$_POST['id']."' " ;
	$result=mysql_query($sqli);
	
	while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
	{
		$claveAcceso=$row['ClaveAcceso'];
	}
	if ($claveAcceso != ''){
	    echo "1";
	}else{
	    echo "2";
	}
	    }
	    
	     if( $_POST['tipo_comprobante']==6){
	        generarXMLGUIA($_POST['id']);
	        
	                                    $sqli="Select ClaveAcceso From ventas where id_venta='".$_POST['id']."' " ;
									$result=mysql_query($sqli);
									
									while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
									{
										$claveAcceso=$row['ClaveAcceso'];
									}
									if ($claveAcceso != ''){
									    echo "1";
									}else{
									    echo "2";
									}
	    }
	    
	   if( $_POST['tipo_comprobante']==4){
	        genXmlnc($_POST['id']);
	        
	                                    $sqli="Select ClaveAcceso From ventas where id_venta='".$_POST['id']."' " ;
									$result=mysql_query($sqli);
									
									while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
									{
										$claveAcceso=$row['ClaveAcceso'];
									}
									if ($claveAcceso != ''){
									    echo "1";
									}else{
									    echo "2";
									}
	    }
	    
	      
	   if( $_POST['tipo_comprobante']==33){
	        genXmlLiquidacion($_POST['id']);
	        
	                                    $sqli="Select ClaveAcceso From ventas where id_venta='".$_POST['id']."' " ;
									$result=mysql_query($sqli);
									
									while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
									{
										$claveAcceso=$row['ClaveAcceso'];
									}
									if ($claveAcceso != ''){
									    echo "1";
									}else{
									    echo "2";
									}
	    }
	 
	 
	 }
	 
    if( $_POST['correo']== '2'){
     
    if( $_POST['tipo_comprobante']==7){
        

      	
    $doc="comprobanteRetencion";
 
    $result1=mysql_query( "select substring(Serie,1,3) as estcodigo,substring(Serie,5,3) as emicodigo,Numero as numero_factura_venta,empresa.ruc as ruc 
   
    from mcretencion,empresa,compras where mcretencion.Factura_id=compras.id_compra and compras.id_empresa=empresa.id_empresa
    
    and mcretencion.Id='".$_POST['id']."' ");   
    
 	$rowFactura=mysql_fetch_array($result1); 
 	
 	if($_POST['tipodoc']==1){
        $file="../xmls/generados/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_venta']).".xml";
        echo $file;
 	}else if($_POST['tipodoc']==2) {
 	    $file="../facturas/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_venta']).".pdf";
        echo $file;
 	}
 	
 	
 	
 }else if( $_POST['tipo_comprobante']==33){
     
    //     echo "ID===> ". $_POST['id']."</br>";
	   // echo "TIPO===>". $_POST['tipo_comprobante']."</br>";
	   // echo "CORREO===>". $_POST['correo']."</br>";
    //   	echo "TIPO DOC===>". $_POST['tipodoc']."</br>";
      	
    $doc="liquidacionCompra";
 
    $result1=mysql_query( "select numSerie as estcodigo,txtEmision as emicodigo,numero_factura_compra as numero_factura_compra,
    
    empresa.ruc as ruc ,txtNum as numeroSecuencial
   
    from compras,empresa where compras.id_empresa=empresa.id_empresa and compras.id_compra='".$_POST['id']."' ");  
    
 	$rowFactura=mysql_fetch_array($result1); 
 	
 	if($_POST['tipodoc']==1){
        $file="../xmls/generados/".$doc."_".$rowFactura['ruc']."-".$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_compra']).".xml";
        echo $file;
        
 	}else if($_POST['tipodoc']==2) {
 	    $file="../facturas/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numeroSecuencial']).".pdf";
        echo $file;
 	}else if($_POST['tipodoc']==3){
        $file="../xmls/autorizados/".$doc."_".$rowFactura['ruc']."-".$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_compra']).".xml";
        echo $file;
        
 	}
     
     
 } else{    
     
     
$sqlFactura="SELECT ventas.tipo_documento, establecimientos.codigo AS estcodigo,emision.codigo as emicodigo,ventas.numero_factura_venta, clientes.email,empresa.ruc

FROM ventas,establecimientos,emision,clientes,empresa

WHERE ventas.id_empresa=empresa.id_empresa and establecimientos.id=ventas.`codigo_pun` and emision.id=ventas.codigo_lug and clientes.id_cliente= ventas.id_cliente and 

ventas.id_venta='".$_POST['id']."' "; 
	
	
	$sqlFacturaResult = mysql_query ($sqlFactura);
	
	$rowFactura=mysql_fetch_array($sqlFacturaResult);//permite ir de fila en fila de la tabla
	
	switch($rowFactura['tipo_documento']){
		case 1:
			$doc="factura";
		break;
		case 4:
			$doc="notaCredito";
		break;
		case 6:
			$doc="guiaRemision";
		break;
	}
 }
 
         
 
         if($_POST['doc']==1){
             	$file="../facturas/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_venta']).".pdf";
        	    echo $file;
         }else if($_POST['doc']==2){
              if($_POST['tipodoc']==1){
                    $file="../xmls/generados/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_venta']).".xml";
                    echo $file;	 
              }else if($_POST['tipodoc']==6){
                    $file="../xmls/generados/"."guia"."_".$rowFactura['ruc'].$rowFactura['estcodigo']."-".$rowFactura['emicodigo']."-".$rowFactura['numero_factura_venta'].".xml";
                    echo $file;	 
              }else{
                $file="../xmls/generados/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_venta']).".xml";
                    echo $file;	 
              }
              
         }else if($_POST['doc']==3){
              if($_POST['tipodoc']==1){
                   $file="../xmls/autorizados/".$doc."_".$rowFactura['ruc'].$rowFactura['estcodigo'].$rowFactura['emicodigo'].ceros($rowFactura['numero_factura_venta']).".xml";
                    echo $file;	 
              }
         }
	

	 }
	 




} else {
    echo "Datos no recibidos correctamente.";
}





// echo "id: ""+idComprobante+"",tipo_comprobante: ""+Tipo+"",correo: ""+correo+"",correo: ""+correo+""";




	
 ?>	