<?php
//ob_end_clean();
//Start session

session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

	$sesion_id_periodo_contable =$_GET['Periodo'];// $_SESSION["sesion_id_periodo_contable"];
	$id_compras = $_GET["id_compras"];
	$fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
	$fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
	$txtProveedor =  $_GET['txtProveedor'];
// 	$fecha_desde='2022-04-01';
// 	$fecha_hasta='2022-04-30';

	$fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
	$fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
	
	function ceros($valor){
    $s='';
	for($i=1;$i<=9-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}
// 	header("Content-Type: application/xls");
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	
	header("Content-Disposition: attachment; filename=documento_exportado_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expires:0");

 $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);

$iva_sql = "SELECT `iva`, `id_iva`, `codigo` FROM `impuestos` WHERE `id_empresa` = $sesion_id_empresa";
$iva_result =mysql_query($iva_sql);
$numFila= mysql_num_rows($iva_result);
$ivas = [];
if ($numFila > 0) {
    while ($iva_row = mysql_fetch_array($iva_result) ) {
        $ivas[] = ['iva' => $iva_row['iva'], 'id_iva' => $iva_row['id_iva'], 'codigo' => $iva_row['codigo']];
    }
}

	 
	{$output .="<table class='table table-bordered'>
	<thead>
	<tr><th colspan='9'>".utf8_decode(strtoupper($Oempresa['nombre']))."</th></tr>
	<tr><th colspan='9'>".utf8_decode(strtoupper($Oempresa['razonSocial']))."</th></tr>
	<tr><th colspan='9'>REPORTE DE COMPRAS</th></tr>
    <tr>
    <th></th>
    <th></th>
	<th>FECHA DESDE:</th>
	<th>$fecha_desde</th>
	<th></th>
	<th>FECHA HASTA:</th>
	<th>$fecha_hasta</th>
	<th></th>
	<th></th>
    </tr>
    <tr></tr>
	<tr>
	<th># </th>
	<th>Fecha de compra</th>
	<th>Numero de compra</th>
	<th>Proveedor</th>
	<th>Subtotal</th>
	<th>IVA</th>
	<th>Valor total</th>
	<th>Descuento</th>
	
	";
	   foreach ($ivas as $iva) {
    	   	$output .='<th><b>IVA '.$iva['iva'].'% - '.$iva['codigo'].' </th>';
    	   	
    }
    
	
    $output .="</tr>
	</thead>
	<tbody>";
	  $criterio_orden =$_GET['orden'];
  $criterio_mostrar =$_GET['criterio_mostrar'];
	 $sql = "SELECT *, 
	  detalle_compras.total_iva as detalle_compras_total_iva,
   detalle_compras.iva as ivaproducto,
   productos.iva as iva_producto
        FROM `detalle_compras` 
        LEFT JOIN `compras` ON `compras`.`id_compra` = `detalle_compras`.`id_compra` 
        LEFT JOIN `proveedores` ON `proveedores`.`id_proveedor` = `compras`.`id_proveedor` 
        LEFT JOIN `productos` ON `productos`.`id_producto` = `detalle_compras`.`id_producto` 
        LEFT JOIN `impuestos` ON `impuestos`.`id_iva` = `detalle_compras`.`iva` 
        
        WHERE `detalle_compras`.`id_empresa` = $sesion_id_empresa 
        AND compras.fecha_compra >= '".$fecha_desde."' AND 
        compras.fecha_compra <= '".$fecha_hasta."'  ";
    

  if ($_GET['txtProveedor']!='0' && $_GET['txtProveedor']!=''){
    $sql .= " and  compras.`id_proveedor`='".$txtProveedor."' ";   
  }  

  $sql .= " ORDER BY  compras.fecha_compra $criterio_orden  "; 
  


        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
$subtotal = 0;
            $total_iva = 0;
            $totales_por_iva = array_fill_keys(array_column($ivas, 'id_iva'), 0);
        $prev_id_compra=null;
        $total_general = 0;
        $total_general_iva = 0;
        $total_por_iva_global = array_fill_keys(array_column($ivas, 'id_iva'), 0);
		 while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
     {
      

        $compras_id_compra = $row["id_compra"];

        if($prev_id_compra !== null && $prev_id_compra !== $row['id_compra']){
          	$output .="
				<tr>
				<td>".$numero.' - '.$compras_numero_factura_venta."</td>
				<td>".$fecha_compra."</td>
				<td>".$numSerie."-".$txtEmision."-".ceros($txtNum)."</td>		
				<td>".utf8_decode($nombre_comercial)."</td>
				<td>".number_format($subtotal, 2, '.', ' ')."</td>
				<td>".number_format($total_iva, 2, '.', ' ')."</td>
				<td>".number_format($subtotal + $total_iva, 2, '.', ' ')."</td>	
				<td>".number_format($descuento, 2, '.', ' ')."</td>
					";
		foreach ($ivas as $iva) {
            
                $ivaK= empty($ivaproducto) ?$iva_producto : $ivaproducto;
                
                if ($ivaK== $iva['id_iva']) {

    	        $output .="<td>".number_format($totales_por_iva[$iva['id_iva']], 2, '.', ' ')."</td>";
                } else {
                    $output .="<td>".number_format(0, 2, '.', ' ')."</td>";
                }
            }
            	 
        	$output .="</tr>";  
        
            $sumaDescuento = $sumaDescuento +$descuento;//suma de retencion iva
             $total_general = $total_general +$subtotal;
             $total_general_iva = $total_general_iva +$total_iva;
             foreach ($ivas as $iva) {
                    $total_por_iva_global[$iva['id_iva']] += $totales_por_iva[$iva['id_iva']];
                }
             $subtotal=0;
             $total_iva=0;
             $descuento=0;
              $totales_por_iva = array_fill_keys(array_column($ivas, 'id_iva'), 0);
        }
        $iva_producto = $row['iva_producto'];
        $ivaproducto = $row['ivaproducto'];
        $subtotal += $row['valor_total'];
        $total_iva = floatval($row['total_iva']);  
        $descuento =  number_format($row["descuento"], 2, '.', ' ');
        foreach ($ivas as $iva) {
                $ivaK= empty($row['ivaproducto']) ? $row['iva_producto'] : $row['ivaproducto'];
                if ($ivaK== $iva['id_iva']) {
         
                    $totales_por_iva[$iva['id_iva']] += floatval($row['valor_total']);
                }
            }
        $nombre_comercial =$row["nombre_comercial"];
        $numSerie=$row['numSerie'];
        $txtEmision=$row['txtEmision'];
        $txtNum=$row['txtNum'];
        $fecha_compra=$row["fecha_compra"];
        $compras_numero_factura_venta = $row["numero_factura_compra"];
             $prev_id_compra = $row['id_compra'];
           
 $numero ++;
          }
          
          	$output .="
				<tr>
				<td>".$numero.' - '.$compras_numero_factura_venta."</td>
				<td>".$fecha_compra."</td>
				<td>".$numSerie."-".$txtEmision."-".ceros($txtNum)."</td>		
				<td>".utf8_decode($nombre_comercial)."</td>
				<td>".number_format($subtotal, 2, '.', ' ')."</td>
				<td>".number_format($total_iva, 2, '.', ' ')."</td>
				<td>".number_format($subtotal + $total_iva, 2, '.', ' ')."</td>	
				<td>".number_format($descuento, 2, '.', ' ')."</td>
					";
		foreach ($ivas as $iva) {
            
                $ivaK= empty($ivaproducto) ?$iva_producto : $ivaproducto;
                
                if ($ivaK== $iva['id_iva']) {

    	        $output .="<td>".number_format($totales_por_iva[$iva['id_iva']], 2, '.', ' ')."</td>";
                } else {
                    $output .="<td>".number_format(0, 2, '.', ' ')."</td>";
                }
            }
            	 
        	$output .="</tr>";  
        
            $sumaDescuento = $sumaDescuento +$descuento;//suma de retencion iva
             $total_general = $total_general +$subtotal;
             $total_general_iva = $total_general_iva +$total_iva;
             foreach ($ivas as $iva) {
                    $total_por_iva_global[$iva['id_iva']] += $totales_por_iva[$iva['id_iva']];
                }
             $subtotal=0;
             $total_iva=0;
             $descuento=0;
              $totales_por_iva = array_fill_keys(array_column($ivas, 'id_iva'), 0);
        
         
	$output .="
				<tr>
				<td>$numero-1</td>		
				<td></td>
				<td></td>
				<td>TOTAL</td>	
	           
				<td>".number_format($total_general, 2, '.', ' ')."</td>	
				<td >".number_format($total_general_iva, 2, '.', ' ')."</td>
			    <td >".number_format($total_general + $total_general_iva, 2)."</td>
			 <td>".number_format($sumaDescuento, 2, '.', ' ')."</td>	
				";
		foreach ($ivas as $iva){
            $output .="<td>". number_format($total_por_iva_global[$iva['id_iva']], 2, '.', ' ')."</td>";
    	  
        }
			
		$output .="</tr>";
		$output .="
		</tbody>
		</table>
		";
		echo $output;
	}			

?>
