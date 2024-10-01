<?php
//ob_end_clean();
//Start session
error_reporting(0);
session_start();
function ceros($valor){
	$s='';
 for($i=1;$i<=9-strlen($valor);$i++)
	 $s.="0";
 return $s.$valor;
}
//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

       $matrizador= $_GET['matrizador'];
       if(trim($matrizador)==''){
           $matrizador=0;
       }
       $numero_libro= $_GET['numero_libro'];
       if(trim($numero_libro)==''){
           $numero_libro=0;
       }
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

	$sesion_id_periodo_contable =$_GET['Periodo'];// $_SESSION["sesion_id_periodo_contable"];
	$id_compras = $_GET["id_compras"];
	$fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
	$fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
	$txtProveedor =  $_GET['txtProveedor'];

	if($txtProveedor=''){
		$txtProveedor =  '';
	} 
	$fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
	$fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
 $autorizacion= $_GET['autorizacion'];
    $estado= $_GET['estado'];
	$emision = $_GET['emision'];
	$ciudad= ($_GET['cbciudad']);
	$txtClientes = $_GET['txtClientes']; 
	$txtUsuarios= $_GET['txtUsuarios'];
	 $tipoDoc= ($_GET['tipoDoc']);
	 $tipoReporte = $_GET['txtProductos'];
	
	 $areas= $_GET['areas'];


    if ($estado =='Pasivo'){
        $titulo =" Anuladas";
    }else {
        $titulo='';
    }
    
	
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=documento_exportado_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");


	$sqlVentasSinDetalle="SELECT ventas.id_empresa, ventas.id_venta, ventas.numero_factura_venta, ventas.fecha_venta, detalle_ventas.id_detalle_venta, COUNT(detalle_ventas.id_detalle_venta) as total, ventas.fecha_anulacion 
	from ventas LEFT JOIN detalle_ventas on detalle_ventas.id_venta = ventas.id_venta WHERE detalle_ventas.id_detalle_venta is null and ventas.id_empresa=$sesion_id_empresa  AND
	 DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
	 DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."' GROUP by ventas.id_venta ORDER BY `ventas`.`fecha_venta` DESC";
	$resultVentasSinDetalle= mysql_query($sqlVentasSinDetalle);
	$numFilasSinDetalle = mysql_num_rows($resultVentasSinDetalle);
	$listaSD= '0';
	if($numFilasSinDetalle>0){
	    while($rowSD= mysql_fetch_array($resultVentasSinDetalle)){
	        $listaSD.= ','.$rowSD['id_venta'];
	    }
	}



if($tipoReporte == 'B'){ //REVISADO  PRODUCTOS RESUMEN OK 
    	$sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'";
	$resulImpuestos = mysql_query($sqlImpuestos);
	$listadoImpuestos = array();
	$existe12= false;
	
	while($rowIv = mysql_fetch_array($resulImpuestos) ){
	    
	    $listadoImpuestos['id_iva'][]= $rowIv['id_iva'];
	    $listadoImpuestos['iva'][]= $rowIv['iva'];
	    $idiva = $rowIv['id_iva'];
	     
	    $sumaBase[$idiva]=0;
	    if($rowIv['iva']=='12'){
	    	    $existe12=true;
	    	}
	}
	if($existe12==false){
	    $sqlImpuestos2 = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='0";
	    $resultImpuestos2 = mysql_query($sqlImpuestos2);
	    while($rowImp2 = mysql_fetch_array($resultImpuestos2) ){
	    	    $listadoImpuestos['id_iva'][]= $rowImp2['id_iva'];
	    $listadoImpuestos['iva'][]= $rowImp2['iva'];
	    $iva_ac = $rowImp2['id_iva'];
	    $sumaBase[$iva_ac]=0;
	    	}
	}
	 $cantidadImpuestos = count($listadoImpuestos['iva']);
	$output .="<table > <thead>
	<tr ><th colspan='".($cantidadImpuestos+3)."' style='border-style: solid;' >REPORTE DE VENTAS POR PRODUCTO RESUMIDO".$titulo."</th></tr>
	<tr >
	<th >Fecha desde: </th>
	<th>".$_GET['txtFechaDesde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['txtFechaHasta']."</th>
	<th></th>
	</tr>
	<tr>
	<th># PRODUCTO</th>
	<th>CANTIDAD</th>
	<th>VALOR UNITARIO</th>";
	
	for($e =0; $e<$cantidadImpuestos; $e++){
	    $output .="<th>TOTAL ".$listadoImpuestos['iva'][$e]."%</th>";
    }  
	
	
	
	$output .="</tr> </thead> <tbody>";
	
	$sql2 = "select SUM(detalle_ventas.cantidad) as total ,detalle_ventas.v_unitario, productos.id_producto, productos.producto, SUM(detalle_ventas.v_total) as sumatotal,
	        productos.iva as IVA, productos.codigo
	        from detalle_ventas 
	        INNER JOIN ventas ON ventas.id_venta = detalle_ventas.id_venta 
            INNER JOIN `clientes` clientes      ON ventas.`id_cliente` = clientes.`id_cliente` 
            INNER JOIN `emision` emision      ON ventas.`codigo_lug` = emision.`id` 
	        INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio 
	        where ventas.id_empresa='".$sesion_id_empresa."'  
	        and DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' 
	        and DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."' ";
    
    if ($emision !='0'&& $emision!='GLOBAL'){
        $sql2.= " and emision.`id` ='".$emision."' "; 
    }
    if ($ciudad!=0  && $ciudad!='' ){
        $sql2.= "and clientes.`id_ciudad`='".$ciudad."' "; 
    }
    if ($txtClientes!='0' ){
		$sql2.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
		}

    if ($txtUsuarios!='0'){
		$sql2.= " and ventas.`id_usuario`='".$txtUsuarios."' "; 
	}
	if ($tipoDoc!='0'){
		$sql2.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
	}
		if ($estado !='0'){
		$sql2.= " and ventas.`estado`='".$estado."' "; 
	}
		if ($autorizacion =='1'){
		$sql2.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    $sql2.= " and ventas.`Autorizacion` IS NULL "; 
	}
	
	 $sql2 .= "  and ventas.id_venta NOT IN (".$listaSD.")  GROUP BY productos.codigo, productos.iva ";
	 
	 $result2 = mysql_query($sql2) or die(mysql_error());  
	 while($row2 = mysql_fetch_array($result2)) {

		$output .="
		<tr>
		<td>".utf8_decode($row2['producto'])."</td>
		<td>".number_format($row2['total'],4,'.','')."</td>
		<td>$".number_format($row2['v_unitario'],4,'.','')."</td>";	
		
		 for($e =0; $e<$cantidadImpuestos; $e++){
                        $iva_ac =$listadoImpuestos['id_iva'][$e];
                        
                	    if($iva_ac== $row2['IVA']){
        $output.="<td>$".number_format($row2['sumatotal'],4,'.','')."</td>";	
                	       
                	        $sumaBase[$iva_ac]  = $sumaBase[$iva_ac] + $row2['sumatotal'];
                	    }else{
                	       $output.="<td>$".number_format(0,4,'.','')."</td>";
                	    }
                	    
                	     
                    }


		$output .="</tr>";
		$sumaBase0  = $sumaBase0 + $vTotal0;
		$sumaBase12 = $sumaBase12 + $vTotal12;
	 }
	 $output .="
		<tr>
		<td></td>
		<td></td>
		<td><strong>TOTAL</strong></td>";
		  for($e =0; $e<$cantidadImpuestos; $e++){
                       $iva_act= $listadoImpuestos['id_iva'][$e];
                       $output.="<td><strong>$".number_format($sumaBase[$iva_act],4,'.','')."</strong></td>";
                    }
	
	    
	$output.="</tr>";
		echo $output;
}else if( $tipoReporte !='A' && $tipoReporte !='B'){ //  revisado 0 PRODUCTOS DETALLADO

	$output .="<table > <thead>
	<tr ><th colspan='5' style='border-style: solid;' >REPORTE DE VENTAS POR PRODUCTO ".$titulo."</th></tr>
	<tr >
	<th >Fecha desde: </th>
	<th>".$_GET['txtFechaDesde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['txtFechaHasta']."</th>
	<th></th>
	</tr>
 </thead> <tbody>";


	$sql = " SELECT ventas.id_venta, detalle_ventas.id_servicio, productos.producto 
    FROM `ventas` 
    INNER JOIN detalle_ventas ON detalle_ventas.id_venta = ventas.id_venta 
    INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio
    
    WHERE ventas.id_empresa=$sesion_id_empresa AND
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."'  ";
		if($_GET['txtProductos']!=0 && $_GET['txtProductos']!='A' && $_GET['txtProductos']!='B'){
		    $sql .= "	and productos.id_producto ='".$_GET['txtProductos']."'  ";
		}
	$sql .= "	GROUP BY productos.id_producto";
    $result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {

		$output .="
		<tr>
		<th colspan='3'>".utf8_decode($row['producto'])."</th>
		<th></th>
		<th></th>
		<th></th>
		</tr>
			<tr>
		<th># VENTA</th>
		<th>FECHA</th>
		<th>CLIENTE</th>
		<th>CANTIDAD</th>
		<th>VALOR UNITARIO</th>
		<th>VALOR TOTAL</th>
		</tr>";


	$sql2 = "SELECT
    ventas.id_venta,
    ventas.fecha_venta,
    ventas.`numero_factura_venta` AS ventas_numero_factura_venta, 
    detalle_ventas.id_servicio,
    detalle_ventas.cantidad,
    detalle_ventas.v_unitario,
    detalle_ventas.descuento,
    detalle_ventas.v_total,
    productos.producto,
    clientes.id_cliente,
    CONCAT(clientes.nombre,' ' ,clientes.apellido)    as clientes_nombre,
     establecimientos.codigo as establecimientos_codigo, 
    emision.codigo as emision_codigo 
FROM
    `ventas`
INNER JOIN detalle_ventas ON detalle_ventas.id_venta = ventas.id_venta
INNER JOIN productos ON productos.id_producto = detalle_ventas.id_servicio
INNER JOIN clientes ON clientes.id_cliente = ventas.id_cliente
 INNER JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
      INNER JOIN emision ON emision.id=ventas.codigo_lug 
WHERE
    ventas.id_empresa = $sesion_id_empresa 
    AND DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >= '".$fecha_desde."' 
    AND DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') <= '".$fecha_hasta."' 
    and  detalle_ventas.id_servicio ='".$row['id_servicio']."' ";


    if ($ciudad!='0' && $ciudad!='' ){
		$sql2.= " and clientes.`id_ciudad`='".$ciudad."' "; 
	}
	if ($txtClientes!='0' ){
		$sql2.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
	}
	if ($tipoDoc!='0'){
		$sql2.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
	}

    if ($txtUsuarios!='0'){
		$sql2.= " and ventas.`id_usuario`='".$txtUsuarios."' "; 
	}
    if ($estado !='0'){
		$sql2.= " and ventas.`estado`='".$estado."' "; 
	}
	if ($emision !='0' && $emision!='GLOBAL'){
		$sql2.= " and emision.`id` ='".$emision."' "; 
	}

    if ($autorizacion =='1'){
		$sql2.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    	$sql2.= " and ventas.`Autorizacion` IS NULL "; 
	}
	$sumaBaseImp=0;
	$sumaBasePor=0;
    $result2 = mysql_query($sql2) or die(mysql_error()); 
	while($row2 = mysql_fetch_array($result2)) {
		$numeroFactura= $row2['ventas_numero_factura_venta'];
        $estCod= $row2['establecimientos_codigo'];
        $emiCod= $row2['emision_codigo'];
	    $numeroFactura= ceros($numeroFactura);
        $numeroVenta=  $estCod."-".$emiCod."-".$numeroFactura;

		$output .="
			<tr>
		<th>".$numeroVenta."</th>
		<th>".$row2['fecha_venta']."</th>
		<th>".utf8_decode($row2['clientes_nombre'])."</th>
		<th>".$row2['cantidad']."</th>
		<th>".$row2['v_unitario']."</th>
		<th>".$row2['v_total']."</th>
		</tr>";
		$sumaBaseImp= $sumaBaseImp + $row2['v_unitario'];
        $sumaBasePor= $sumaBasePor + $row2['v_total'];
	}
	$output .="
			<tr>
		<th></th>
		<th></th>
		<th></th>
		<th>TOTAL</th>
		<th>".$sumaBaseImp."</th>
		<th>".$sumaBasePor."</th>
		</tr>";

	}
	echo $output;
}else{

if($areas=='0'){ // areas detallado
	$output .="<table >
	<thead>
	
	<tr ><th colspan='12' style='border-style: solid;' >Reporte de Ventas".$titulo."</th></tr>
	<tr >
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	
	<th >Fecha desde: </th>
	<th>".$_GET['txtFechaDesde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['txtFechaHasta']."</th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	</thead>
	<tbody>
	";
	$sqlCentrosCostos="SELECT id_centro_costo ,descripcion FROM `centro_costo` WHERE `empresa`=$sesion_id_empresa";
	if($areas!='0'){
		$sqlCentrosCostos .=" and id_centro_costo=$areas";
	}
	$resultCentrosCostos = mysql_query($sqlCentrosCostos);
	$cc=0;
	$contadorfilas=0;
	$result = array();
	while($rowCC = mysql_fetch_array($resultCentrosCostos)){
		$sql='';
		$sql = "SELECT
		ventas.`id_empresa` AS ventas_id_empresa,
		ventas.`id_venta` AS ventas_id_venta,
		ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
		ventas.`id_cliente` AS ventas_id_cliente,
		ventas.`fecha_venta` AS ventas_fecha_venta,
		ventas.`sub_total` AS ventas_sub_total,
		ventas.`sub0` AS ventas_sub0,
		ventas.`sub12` AS ventas_sub12,
		ventas.`id_iva` AS ventas_id_iva,
	
		ventas.`codigo_pun` AS codigo_pun,
		ventas.`codigo_lug` AS codigo_lug,	
		ventas.`estado` AS estado,
		ventas.`tipo_documento` AS tipo_documento,

		SUM(detalle_ventas.`v_total`) AS  ventas_total,
		SUM(detalle_ventas.`total_iva`) AS  ventas_total_iva,
		detalle_ventas.`cantidad` AS  detalle_ventas_cantidad,
		detalle_ventas.`v_unitario` AS  detalle_ventas_vUnitario,
		detalle_ventas.`descuento` AS  detalle_ventas_descuento,
		
		establecimientos.`codigo` AS codigo_est,
		emision.`codigo` AS codigo_emi,
		
		ventas.`numero_factura_venta` as ventas_numero_factura_venta,
		ventas.`id_forma_pago` AS ventas_id_forma_pago,
		clientes.`id_cliente` AS clientes_id_cliente,
		clientes.`nombre` AS clientes_nombre,
		clientes.`apellido` AS clientes_apellido,
		clientes.`cedula` AS clientes_cedula,

		productos.producto,
		productos.iva as productos_iva,
	
		cc.descripcion as centro_nombre
	   FROM
	   `ventas` ventas 
	   INNER JOIN `clientes` clientes      ON ventas.`id_cliente` = clientes.`id_cliente` 
	   INNER JOIN `establecimientos` establecimientos      ON establecimientos.`id` = ventas.`codigo_pun` 
	   INNER JOIN `emision` emision      ON ventas.`codigo_lug` = emision.`id` 
	   INNER JOIN  detalle_ventas ON detalle_ventas.`id_venta` = ventas.`id_venta` 
	   INNER JOIN productos on productos.id_producto =  detalle_ventas.`id_servicio`
	   INNER JOIN (Select id_centro_costo, descripcion from centro_costo where empresa =$sesion_id_empresa) as cc ON
	   cc.id_centro_costo = detalle_ventas.idBodega
			";
		$sql .= " where ventas.`id_empresa`='".$sesion_id_empresa."' 
		AND
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
		DATE_FORMAT(ventas.`fecha_venta`, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."'  ";
		
		
	   if ($estado!='0'){
		   $sql.= "and ventas.`estado`='".$estado."' "; 
	   }  

	   if ($emision !='0' && $emision!='GLOBAL'){
		$sql.= " and emision.`id` ='".$emision."' "; 
		}

	   if ($txtClientes!='0' ){
		$sql.= " and clientes.`id_cliente` = '".$txtClientes."' "; 
		}
		
	   if ($ciudad!=0){
		   $sql.= "and clientes.`id_ciudad`='".$ciudad."' "; 
	   }
	   
	   if ($tipoDoc!=0){
		   $sql.= "and ventas.`tipo_documento`='".$tipoDoc."' "; 
	   }
	   
	   
	   if ($txtUsuarios!=0){
		   $sql.= "and ventas.`id_usuario`='".$txtUsuarios."' "; 
	   }
	   if ($autorizacion =='1'){
		$sql.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    	$sql.= " and ventas.`Autorizacion` IS NULL "; 
	}
   
	
		   $sql.= "and detalle_ventas.`idBodega`='".$rowCC['id_centro_costo']."' "; 
	  
	   
		   $sql.= " and ventas.id_venta NOT IN (".$listaSD.")   GROUP BY ventas.id_venta ORDER BY ventas.numero_factura_venta ".$_GET['orden']."  ;"; 
   

	   $result[$cc] = mysql_query($sql) or die(mysql_error());
	   $numeroFilasComsulta = mysql_num_rows( $result[$cc] );
	     if($numeroFilasComsulta>0){
	   $output .="<table >
	   <thead>
	   <tr ><th colspan='12' style='border-style: solid;' >".utf8_decode($rowCC['descripcion'])."</th></tr>
	   <tr>
	   <th>#</th>
	   <th>FECHA</th>
	   <th>NUMEROFACTURA</th>
	   <th>".utf8_decode('IDENTIFICACIÓN')."</th>
	   <th>CLIENTE</th>
	   <th>DETALLE</th>
	   <th>CANTIDAD</th>
	   <th>VALOR UNITARIO</th>
	   <th>DESCUENTO</th>
	   <th>SUBTOTAL</th>
	   <th>IVA</th>
	   <th>TOTAL NETO</th>
	   </tr>
	   </thead>
	   <tbody>
	   ";
	 
	   while($row = mysql_fetch_array($result[$cc])) //for mayor    for($i=0; $i<$numero_filas; $i++)
	   {
	        $ventas_total_iva = $row['ventas_total_iva'];
		$iva = $row['ventas_id_iva'];
		   $formasPago = $row["producto"];
		   $numeroFactura= $row['ventas_numero_factura_venta'];
		   $numeroFactura= ceros($numeroFactura);
		   $valorRF=0;
		   $valorRI=0;
   
		   $numero ++;
		$ventas_id_venta = $row["ventas_id_venta"];
		$sqlu="Select valor From cobrospagos where id_factura='".$ventas_id_venta."' and tipo='retencion-fuente';";
		///  echo $sqlu;
			 $resultu=mysql_query($sqlu);
			 while($rowu=mysql_fetch_array($resultu))
			  {   $valorRF = $rowu["valor"];}
			  
		$sqli="Select valor From cobrospagos where id_factura='".$ventas_id_venta."' and tipo='retencion-iva';";
		 // echo $sqlu;
			 $resulti=mysql_query($sqli);
			 while($rowi=mysql_fetch_array($resulti))
			  { $valorRI = $rowi["valor"];}
 
			  
 // INICIO cambios 12/10/2022
			  $sqlIva="SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta` FROM `impuestos`  WHERE id_iva='".$iva."' and impuestos.`id_empresa`='".$sesion_id_empresa."'  ;";
			  // echo $sqlu;
				  $resultIva=mysql_query($sqlIva);
				  while($rowI=mysql_fetch_array($resultIva))
				   { $valorIva= $rowI["iva"];}
		$subtotale = isset($row["ventas_total"])?$row["ventas_total"] :0;
		$calculoIva =$ventas_total_iva;
		$totale = $subtotale + $calculoIva;

		$sumaIvaFinal = $sumaIvaFinal +$calculoIva;
		$sumaDesuentos = $sumaDesuentos + $row["detalle_ventas_descuento"];
		$suma_base12 = $suma_base12 + $row["detalle_ventas_vUnitario"];//suma de base12
		$suma_base0 = $suma_base0 + $row["detalle_ventas_cantidad"];//suma de base0
		$subtotal_venta = $subtotal_venta + $subtotale;//suma de subtotal
		$total_venta = $total_venta + $totale; //suma de total

		$output .="
		<tr>
		<td>".$numero."</td>
		<td>".$row["ventas_fecha_venta"]."</td>
		<td>". $row['codigo_est']."-".$row['codigo_emi']."-".$numeroFactura."</td>
		<td>#".$row["clientes_cedula"]."</td>
		<td>".utf8_decode($row["clientes_nombre"]." ".$row["clientes_apellido"])."</td>
	    <td>".utf8_decode($formasPago)."</td>
	    <td>".$row["detalle_ventas_cantidad"]."</td>
		<td>".number_format($row["detalle_ventas_vUnitario"], 2, '.', ' ')."</td>	
		<td>".number_format($row["detalle_ventas_descuento"], 2, '.', ' ')."</td>	
		
		
		<td>".number_format($subtotale, 2, '.', ' ')."</td>
		<td>".number_format($calculoIva, 2, '.', ' ')."</td>
		<td>".number_format($totale, 2, '.',' ')."</td>
		</tr>
		";
		
	   }
	   $output .="
		<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	    <td></td>
	    <td></td>
		<td></td>	
		<td></td>	
		<td>".number_format($sumaDesuentos, 2, '.', ' ')."</td>	
		<td>".number_format($subtotal_venta, 2, '.', ' ')."</td>
		<td>".number_format($sumaIvaFinal, 2, '.', ' ')."</td>
		<td>".number_format($total_venta, 2, '.', ' ')."</td>
		</tr>
		";

		$total_venta=0;
		$suma_base0=0;
		$suma_base12=0;
		$sumaIvaFinal=0;
		$subtotal_venta=0;
		$sumaDesuentos=0;
	   }
		$cc++;
	}
	echo $output;
}else{
     if($areas!='0'&& $areas!='A' && $areas!='B'){
		$sqlArea="SELECT `id_centro_costo`, `descripcion` FROM `centro_costo` WHERE id_centro_costo='$areas' ";
		$resultArea = mysql_query($sqlArea) or die(mysql_error());
		while($rowArea = mysql_fetch_array($resultArea)){
			$nombreArea = $rowArea['descripcion'];
		}
		$nombreArea = strtoupper($nombreArea);
		$nombreArea = "<b>1REPORTE DE VENTAS DE ".utf8_decode($nombreArea);
		$titulo = $nombreArea;
	}else{
	$titulo = "REPORTE DE VENTAS";	
	}
    $sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'";
	$resulImpuestos = mysql_query($sqlImpuestos);
	$listadoImpuestos = array();
	$existe12= false;
	while($rowIv = mysql_fetch_array($resulImpuestos) ){
	    $listadoImpuestos['id_iva'][]= $rowIv['id_iva'];
	    $listadoImpuestos['iva'][]= $rowIv['iva'];
	     $listadoImpuestos['suma_total_iva'][]= 0;
	    $idiva = $rowIv['id_iva'];
	    
	    if($rowIv['iva']=='12'){
	    	    $existe12=true;
	    	}
	}
	if($existe12==false){
	    $sqlImpuestos2 = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='0";
	    $resultImpuestos2 = mysql_query($sqlImpuestos2);
	    while($rowImp2 = mysql_fetch_array($resultImpuestos2) ){
	    	    $listadoImpuestos['id_iva'][]= $rowImp2['id_iva'];
	    $listadoImpuestos['iva'][]= $rowImp2['iva'];
	     $listadoImpuestos['suma_total_iva'][]= 0;
	    	}
	}
	 $cantidadImpuestos = count($listadoImpuestos['iva']);
	 
	$output .="<table >
	<thead>
	
	<tr ><th colspan='12' style='border-style: solid;' >".$titulo."</th></tr>
	<tr >
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	
	<th >Fecha desde: </th>
	<th>".$_GET['txtFechaDesde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['txtFechaHasta']."</th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	<tr>
	<th>FECHA</th>
	<th>NUMERO FACTURA</th>
	<th>".utf8_decode('IDENTIFICACIÓN')."</th>
	<th>NOMBRE COMERCIAL</th>
    <th>RAZON SOCIAL</th>
    <th>FORMA DE PAGO</th>";
     for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	   	$output .=  '<th>Base '.$listadoImpuestos['iva'][$e].' %</th>';

        }

	$output .="<th>DESCUENTO</th>
	<th>SUBTOTAL</th>
	<th>IVA</th>
	<th>TOTAL NETO</th>";

			$output .="<th>VENDEDOR</th>";    
	


$output .="	</tr>
	</thead>
	<tbody>
	";
		$codigoDelProducto='';
		if($_GET['txtProductos'] !='A' && $_GET['txtProductos'] !='B' && $_GET['txtProductos'] !='0' ){
	$sqlCodigoProducto = "SELECT productos.producto, productos.id_producto, productos.codigo from productos WHERE productos.id_producto=".$_GET['txtProductos'];
	$resultCodigoProducto = mysql_query($sqlCodigoProducto);
	$numFilasProductos = mysql_num_rows($resultCodigoProducto);

	if($numFilasProductos>0){
	    while($rowP = mysql_fetch_array($resultCodigoProducto)){
	        $codigoDelProducto = $rowP['codigo'];
	    }
	}
	}
	
  
    
	$sql = "SELECT
	ventas.`id_venta` AS ventas_id_venta,

	ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
	ventas.`id_cliente` AS ventas_id_cliente,
	ventas.`fecha_venta` AS ventas_fecha_venta,
	ventas.`sub0` AS ventas_sub0,
	ventas.`sub12` AS ventas_sub12,
	ventas.`sub_total` AS ventas_sub_total,
	ventas.`id_iva` AS ventas_id_iva,
 ventas.`total_iva` AS ventas_total_iva,
	ventas.`tipo_documento` AS ventas_tipo_documento,
	ventas.`id_empresa` AS ventas_id_empresa,
	ventas.`id_forma_pago` AS ventas_id_forma_pago,
	ventas.`descuento` AS ventas_descuento,

	ventas.`codigo_pun` AS ventas_codigo_pun,
	ventas.`codigo_lug` AS ventas_codigo_lug,

	ventas.`comentario` AS ventas_estado,
	ventas.`estado` AS ventas_estado_anulacion,

	ventas.`ClaveAcceso` AS ventas_ClaveAcceso,
    
    SUM(detalle_ventas.`v_total`) AS  ventas_total,
	detalle_ventas.`cantidad` AS  detalle_ventas_cantidad,
	detalle_ventas.`v_unitario` AS  detalle_ventas_vUnitario,
	detalle_ventas.`descuento` AS  detalle_ventas_descuento,
		
	establecimientos.`codigo` AS establecimientos_codigo,
	emision.`codigo` AS emision_codigo,

	emision.`formato` AS emision_formato,

	clientes.`id_cliente` AS clientes_id_cliente,
	clientes.`nombre` AS clientes_nombre,
	clientes.`apellido` AS clientes_apellido,
	clientes.`direccion` AS clientes_direccion,
	clientes.`cedula` AS clientes_cedula,
	clientes.`numero_casa` AS clientes_numero_casa,
	 vendedores.nombre as vendedor_nombre,
       vendedores.apellidos as vendedor_apellido,
productos.iva as productos_iva,
	impuestos.iva as  iva,
	  detalle_ventas.`id_servicio`


	FROM
	`clientes` clientes
	INNER JOIN `ventas` ventas ON clientes.`id_cliente` = ventas.`id_cliente` 
	LEFT JOIN vendedores on vendedores.id_vendedor =  ventas.`vendedor_id_tabla`
	INNER JOIN `establecimientos` establecimientos ON establecimientos.`id` = ventas.`codigo_pun`
	INNER JOIN `emision` emision ON emision.`id` = ventas.`codigo_lug`
	LEFT JOIN impuestos ON impuestos.id_iva = ventas.`id_iva` 
    INNER JOIN  detalle_ventas ON detalle_ventas.`id_venta` = ventas.`id_venta` 
	INNER JOIN productos on productos.id_producto =  detalle_ventas.`id_servicio`";
	$areas= $_GET['areas'];
if($areas!='0' && $areas!='A' && $areas!='B'){
	$sql .= " INNER JOIN (Select id_centro_costo, descripcion from centro_costo where empresa ='".$sesion_id_empresa."') as cc ON cc.id_centro_costo = detalle_ventas.idBodega";

}
		
 
	$sql .= " where ventas.`id_empresa`='".$sesion_id_empresa."' ";
	
	
	if($_GET['txtFechaDesde']!='' && $_GET['txtFechaHasta']!=''){
		$sql .= " AND DATE_FORMAT(ventas.`fecha_venta`, '%Y %m %d') >= DATE_FORMAT('".$_GET['txtFechaDesde']."', '%Y %m %d') AND DATE_FORMAT(ventas.`fecha_venta`, '%Y %m %d') <= DATE_FORMAT('".$_GET['txtFechaHasta']."', '%Y %m %d') " ;
	} 
	if($_GET['cbciudad'] !='0' && $_GET['cbciudad'] !='' ){
		$sql .= " and clientes.id_ciudad  =".$_GET['cbciudad']; 
	}
	if($_GET['txtClientes']!='0'){
		$sql .= " and ventas.`id_cliente` =".$_GET['txtClientes']; 

	} 

	if($_GET['tipoDoc'] !='0' ){
		$sql .= " AND ventas.`tipo_documento`=".$_GET['tipoDoc'];
	}


	if($_GET['txtUsuarios'] != '0'){
		$sql .= " and ventas.`id_usuario` =".$_GET['txtUsuarios']; 
	}

	if ($estado !='0'&& $estado!='D'){
		$sql.= " and ventas.`estado`='".$estado."' "; 
	}
	if ($emision !='0' && $emision!='GLOBAL'){
		$sql.= " and emision.`id` ='".$emision."' "; 
	}

	if($codigoDelProducto !='' ){
		$sql .= " and  productos.codigo  ='".$codigoDelProducto."'"; 

	}
	
	if ($autorizacion =='1'){
		$sql.= " and ventas.`Autorizacion`!='' "; 
	}else if ($autorizacion =='2'){
	    	$sql.= " and ventas.`Autorizacion` IS NULL "; 
	}

	if($areas!='0' && $areas!='A' && $areas!='B'){
	  $sql.= " and detalle_ventas.`idBodega`='".$areas."' "; 
	   
	}
		$vendedor_id = trim($_GET['vendedor']);
if ($vendedor_id!=0 && $vendedor_id!=''){
       $sql.= " and ventas.`vendedor_id_tabla`='".$vendedor_id."' "; 
   }
   	   
		$sql.= "  and ventas.id_venta NOT IN (".$listaSD.") GROUP by ventas.id_venta ORDER BY ventas.numero_factura_venta ".$_GET['criterio_orden']." ;"; 


	$result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;

    // echo "Numero de filas    ".$numero_filas;

    $nombre_retencion="";
    $sumaIvaTotal=0;
    $sumaIvaTotal='0';
    $sumaSubtotal=0;
    $sumaDescuento=0;
    $sumaSub12=0;
    $sumaSub0=0;
    $ivaFinal=0;
    $numero=0;
	while($row2 = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
	{
	    $ventas_id_venta=  $row2['ventas_id_venta'];
	    $sqlSubTotalesVta="SELECT `id_detalle_venta`, `idBodega`, `idBodegaInventario`, `cantidad`, `estado`, `v_unitario`, `descuento`, SUM(`v_total`) as total_producto, `id_venta`, `id_servicio`, `detalle`, `id_kardex`, `tipo_venta`, `id_empresa`, `id_proyecto`, `tarifa_iva`, SUM(`total_iva`) as suma_iva FROM `detalle_ventas` WHERE id_venta = '".$ventas_id_venta."' GROUP BY tarifa_iva;";
	    $resultSubTotalesVta = mysql_query($sqlSubTotalesVta);
	    $listadoSubtotales = array();
	    $listadoIva = array();
	    while($rowSub = mysql_fetch_array($resultSubTotalesVta) ){
	        $id_actual = $rowSub['tarifa_iva'] ;
	        $listadoSubtotales[$id_actual] = $rowSub['total_producto'] ;
	    }
	    
	    $idVentaActual = $row2['ventas_id_venta'];
	     if($numero==0){
	          $codigoPun= $row2['emision_codigo'];
	    }
	    	$numero ++;
	   // $formasPago = ($row2["ventas_id_forma_pago"]==1)?'Efectivo':'Transferencia';
	   		$sqlFormaPago="SELECT
   cobrospagos.id,
   cobrospagos.documento,
   cobrospagos.id_factura,
     formas_pago.`id_forma_pago` AS formas_pago_id_forma_pago,
     formas_pago.`nombre` AS formas_pago_nombre,
     formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
     formas_pago.`id_empresa` AS formas_pago_id_empresa,
     formas_pago.`id_tipo_movimiento` AS formas_pago_id_tipo_movimiento,
     tipo_movimientos.`id_tipo_movimiento` AS tipo_movimientos_id_tipo_movimiento,
     tipo_movimientos.`nombre` AS tipo_movimientos_nombre,
     tipo_movimientos.`id_empresa` AS tipo_movimientos_id_empresa
  
FROM
    `formas_pago` 
INNER JOIN `tipo_movimientos` tipo_movimientos ON formas_pago.`id_tipo_movimiento` = tipo_movimientos.`id_tipo_movimiento`
INNER JOIN cobrospagos ON cobrospagos.id_forma = formas_pago.id_forma_pago
     
        where formas_pago.`id_empresa`='".$sesion_id_empresa."'  AND cobrospagos.documento=0   AND cobrospagos.id_factura='".$idVentaActual."'
ORDER BY `cobrospagos`.`id` ASC LIMIT 1;";
$resultFormaPago = mysql_query($sqlFormaPago);
$formasPago='';
while($rowFP = mysql_fetch_array($resultFormaPago) ){
    $formasPago = utf8_decode($rowFP['formas_pago_nombre']);
}
	    
		$numeroFactura= $row2["ventas_numero_factura_venta"];
		$numeroFactura=  $row2['establecimientos_codigo']."-".$row2['emision_codigo']."-".ceros($numeroFactura);
	
		
		$iva = $row2['ventas_id_iva'];
		
			 $sqlIva="SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta` FROM `impuestos`  WHERE id_iva='".$iva."' and impuestos.`id_empresa`='".$sesion_id_empresa."'  ;";
			 // echo $sqlu;
				 $resultIva=mysql_query($sqlIva);
				 $valorIva = 0;
				 while($rowI=mysql_fetch_array($resultIva))
				  { $valorIva= $rowI["iva"];}
				  $valorIva = is_null($valorIva)?0:$valorIva;
		if($row2["ventas_estado_anulacion"]=='Activo'){
		    $subtotale = isset($row2["ventas_sub_total"])?number_format($row2["ventas_sub_total"] , 2, '.', ' ') :0;
		    $valor_limpio = str_replace(array(' ', ','), '', $subtotale);
		}else{
		    $subtotale=0;
		    $valor_limpio=0;
		}		  
		
		
        $valor_decimal = floatval($valor_limpio);
// 		$calculoIva =($row2['productos_iva']=='Si' && $row2["ventas_estado_anulacion"]=='Activo' )?$valor_decimal *($valorIva/100):0;

if($valor_decimal==0){
   $calculoIva = 0; 
}else{
    $calculoIva = $row2['ventas_total_iva'];
}
		$totale = $valor_decimal + $calculoIva;
		  if($row2['emision_codigo']!=$codigoPun &&  $emision =='GLOBAL'){
		       $codigoPun= $row2['emision_codigo'];
		      	$output .="
	</tbody>
	<tfoot>
    <tr>
     <td></td>
      <td></td>
       <td></td>
        <td></td>
          <td></td>
    <td><strong>TOTAL</strong></td>";
    
    
    
  for($e =0; $e<$cantidadImpuestos; $e++){

    	    $output.=" <td>".number_format($listadoImpuestos['suma_total_iva'][$e], 2, '.', ',')."</td>";
    	        
    	    
        }
     $output.="  <td><strong>".number_format($sumaDescuento, 2, '.', ' ')."</strong></td>
        <td><strong>".number_format($sumaSubtotal, 2, '.', ' ')."</strong></td>
         <td><strong>".number_format($ivaFinal, 2, '.', ' ')."</strong></td>
    <td><strong>".number_format($sumaTotal, 2, '.', ' ')."</strong></td>
    </tr>
    </tfoot>
	</table>
	";
	$sumaSub0=0;
	$sumaSub12=0;
	$sumaDescuento=0;
	$sumaSubtotal=0;
	$ivaFinal=0;
	$sumaTotal=0;
	$vend='';

			$vend .="<th>VENDEDOR</th>";    
	
		      	$output .="<table >
	<thead>
	
	<tr ><th colspan='12' style='border-style: solid;' >".$titulo."</th></tr>
	<tr >
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	
	<th >Fecha desde: </th>
	<th>".$_GET['txtFechaDesde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['txtFechaHasta']."</th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	<tr>
	<th>FECHA</th>
	<th>NUMERO FACTURA</th>
	<th>".utf8_decode('IDENTIFICACIÓN')."</th>
	<th>NOMBRE COMERCIAL</th>
    <th>RAZON SOCIAL</th>
    <th>FORMA DE PAGO</th>";
     for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
    	   	$output .=  '<th>Base '.$listadoImpuestos['iva'][$e].' %</th>';

        }
    $output.="
	<th>DESCUENTO</th>
	<th>SUBTOTAL</th>
	<th>IVA</th>
	<th>TOTAL NETO</th>
	".$vend."


	</tr>
	</thead>
	<tbody>
	";
		  }
		  
		  
// 		$ivaFinal = $row2["ventas_sub12"]*($valorIva/100);
		$ivaFinal = $ivaFinal +floatval($calculoIva);
		$sumaTotal = $sumaTotal + 	$totale ;
		$sumaIvaTotal = $sumaIvaTotal + $ivaFinal;
		$sumaSubtotal= $sumaSubtotal + floatval($valor_decimal);
		$sumaDescuento = $sumaDescuento + $row2["detalle_ventas_descuento"];
		
		if($row2["ventas_estado_anulacion"]=='Activo'){
		    $sub0 = $row2["ventas_sub0"];
	        $sub12 = $row2["ventas_sub12"];
	        $sumaSub12 =($row2['productos_iva']=='Si')? $sumaSub12 + $row2["ventas_sub12"] : $sumaSub12+ 0;
	        $sumaSub0 = $sumaSub0 + floatval($row2["ventas_sub0"])  ;
	        $descuento = $row2["ventas_descuento"];
	        $totalVenta = $row2["ventas_total"];
		}else{
		    $sub0 = 0;
		    $sub12 = 0;
		    $descuento = 0;
		    $totalVenta=0;
		}
		
                    
                    
                    
		$output .="
		<tr>
		
<td>".$row2["ventas_fecha_venta"]."</td>
<td>".$numeroFactura."</td>
<td>#".$row2["clientes_cedula"]."</td>
<td>".utf8_decode($row2["clientes_nombre"])."</td>
<td>".utf8_decode($row2["clientes_apellido"])."</td>
<td>".$formasPago."</td>";
   for($e =0; $e<$cantidadImpuestos; $e++){
            $idiva = $listadoImpuestos['id_iva'][$e];
            
    	    if(isset($listadoSubtotales[$idiva]) && $row2["ventas_estado_anulacion"]=='Activo'){
    	       $output .="<td>".number_format($listadoSubtotales[$idiva], 2, '.', ',')."</td>";
    	         $listadoImpuestos['suma_total_iva'][$e]+=$listadoSubtotales[$idiva];
    	    }else{
    	       $output .="<td>".number_format(0, 2, '.', ',')."</td>";
    	        	
    	    }
        }
    
 
        

$output .="<td>".number_format($descuento, 2, '.', ',')."</td>
<td>".number_format($totalVenta, 2, '.', ',')."</td>
<td>".number_format($calculoIva, 2, '.', ',')."</td>
<td>".number_format($totale, 2, '.', ',')."</td>";



		 $output .="<td>".utf8_decode($row2["vendedor_nombre"].' '.$row2["vendedor_apellido"])."</td>";   
	

		$output .="	</tr>
		";

	}			

	$output .="
	</tbody>
	<tfoot>
    <tr>
     <td></td>
      <td></td>
       <td></td>
        <td></td>
          <td></td>
    <td><strong>TOTAL</strong></td>";
     for($e =0; $e<$cantidadImpuestos; $e++){

    	       $output.=" <td>".number_format($listadoImpuestos['suma_total_iva'][$e], 2, '.', ',')."</td>";
    	        
    	    
        }
$output.="<td><strong>".number_format($sumaDescuento, 2, '.', ',')."</strong></td>
<td><strong>".number_format($sumaSubtotal, 2, '.', ',')."</strong></td>
<td><strong>".number_format($ivaFinal, 2, '.', ',')."</strong></td>
<td><strong>".number_format($sumaTotal, 2, '.', ',')."</strong></td>
    </tr>
    </tfoot>
	</table>
	";
	echo $output;
}
}
	?>
