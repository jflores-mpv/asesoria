<?php
error_reporting(0);
session_start();
function ceros($valor){
	$s='';
 for($i=1;$i<=9-strlen($valor);$i++)
	 $s.="0";
 return $s.$valor;
}
require_once('../conexion.php');
date_default_timezone_set("America/Guayaquil");
 $dominio = $_SERVER['SERVER_NAME'];
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
$anio = trim($_GET['anio']);
$anio_anterior = $anio -1;


	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=documento_exportado_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");

	$output ="<table > <thead>
	<tr ><th colspan='5' >CUADRO COMPARATIVO DE RECAUDACIONES</th>	</tr>
	<tr ><th colspan='5' >".strtoupper($sesion_empresa_nombre)."</th>	</tr>
	
 </thead>";

  $arraymeses = array('01','02','03','04','05','06','07','08','09','10','11','12' );
        $arraymesesLetras = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre' );
        
  $sqlVP1= "
    SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
     SUM(registros_cuentas_por_cobrar.`valor`-registros_cuentas_por_cobrar.`saldo`) as registros_abonos ,
    SUM(registros_cuentas_por_cobrar.`valor`) as pendiente ,
    SUM(registros_cuentas_por_cobrar.`saldo`) AS registros_saldos,
    registros_cuentas_por_cobrar.`fecha_vencimiento` 
    FROM `registros_cuentas_por_cobrar` 
    INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
    WHERE DATE_FORMAT( registros_cuentas_por_cobrar.fecha_vencimiento, '%Y' ) = '".$anio_anterior."' AND clientes.id_empresa=$sesion_id_empresa   ";
$resultVP1 = mysql_query($sqlVP1);
 while($rowVP1 = mysql_fetch_array($resultVP1) ) {
     $vp1= $rowVP1['registros_abonos'];
 }
//   $sqlVP2= "
//     SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
//      SUM(registros_cuentas_por_cobrar.`valor`-registros_cuentas_por_cobrar.`saldo`) as registros_abonos ,
//     SUM(registros_cuentas_por_cobrar.`valor`) as pendiente ,
//     SUM(registros_cuentas_por_cobrar.`saldo`) AS registros_saldos,
//     registros_cuentas_por_cobrar.`fecha_vencimiento` 
//     FROM `registros_cuentas_por_cobrar` 
//     INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
//     WHERE DATE_FORMAT( registros_cuentas_por_cobrar.fecha_vencimiento, '%Y' ) = '".$anio."' AND clientes.id_empresa=$sesion_id_empresa   ";
 $sqlVP2= "
    SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
     SUM(cuentas_por_cobrar.`valor`-cuentas_por_cobrar.`saldo`) as registros_abonos ,
    SUM(cuentas_por_cobrar.`valor`) as pendiente ,
    SUM(cuentas_por_cobrar.`saldo`) AS registros_saldos,
    cuentas_por_cobrar.`fecha_vencimiento` 
    FROM `cuentas_por_cobrar` 
    INNER JOIN clientes ON clientes.id_cliente = cuentas_por_cobrar.id_cliente 
    WHERE DATE_FORMAT( cuentas_por_cobrar.fecha_vencimiento, '%Y' ) <= '".$anio."' AND clientes.id_empresa=$sesion_id_empresa   ";
$resultVP2 = mysql_query($sqlVP2);
 while($rowVP2 = mysql_fetch_array($resultVP2) ) {
     $vp2= $rowVP2['registros_abonos'];
 }
			
	$output .="
	<tr ><th  >Detalle</th>	<th >A&Ntilde;O $anio_anterior</th> <th  >A&Ntilde;O $anio</th>	</tr>
	<tr ><th  >Valor Presupuestado</th>	<th >$".number_format($vp1, 2, '.', ' ')."</th><th  >$".number_format($vp2, 2, '.', ' ')."</th>	</tr>
		<tbody>";
	
$sumaValores1 = 0;
$sumaValores2 = 0;
 for($k=0;$k<12;$k++){
     
      $anio_mes_anterior = $anio_anterior."-".$arraymeses[$k];
       $anio_mes_actual = $anio."-".$arraymeses[$k];
       $output .="
	<tr ><td  >$arraymesesLetras[$k]</td>";

$sql= " SELECT 
	
    SUM(detalle_cuentas_por_cobrar.`valor`) as registros_abonos ,
    SUM(detalle_cuentas_por_cobrar.`saldo`) AS registros_saldos,

	 clientes.nombre as name, 
	 clientes.cedula as identificacion, empresa.imagen , emision.codigo as emision_codigo,
    establecimientos.codigo as establecimientos_codigo ,
    ventas.numero_factura_venta as numero_factura_venta
      FROM `clientes` clientes 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
       LEFT JOIN emision ON ventas.codigo_lug = emision.id
        LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
       where  cuentas_por_cobrar.id_empresa='".$sesion_id_empresa."'  AND DATE_FORMAT( detalle_cuentas_por_cobrar.fecha_pago, '%Y-%m' ) = '".$anio_mes_anterior."' AND clientes.id_empresa=$sesion_id_empresa   ";
       
// 	 $sqlDetalle= " SELECT 
// 	  SUM(cuentas_por_cobrar.`valor`-cuentas_por_cobrar.`saldo`) as registros_abonos ,
//     SUM(cuentas_por_cobrar.`valor`) as pendiente ,
//     SUM(cuentas_por_cobrar.`saldo`) AS registros_saldos,

// 	 cuentas_por_cobrar.* , 
// 	 clientes.nombre as name, 
// 	 clientes.cedula as identificacion, empresa.imagen , emision.codigo as emision_codigo,
//     establecimientos.codigo as establecimientos_codigo ,
//     ventas.numero_factura_venta as numero_factura_venta
//       FROM `clientes` clientes 
//     INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
//       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
//       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
//       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
//       LEFT JOIN emision ON ventas.codigo_lug = emision.id
//         LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
//       where  cuentas_por_cobrar.id_empresa='".$sesion_id_empresa."'  AND DATE_FORMAT( cuentas_por_cobrar.fecha_vencimiento, '%Y-%m' ) = '".$anio_mes_anterior."' AND clientes.id_empresa=$sesion_id_empresa   ";
       
    //  $sql= "
    // SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
    //  SUM(registros_cuentas_por_cobrar.`valor`-registros_cuentas_por_cobrar.`saldo`) as registros_abonos ,
    // SUM(registros_cuentas_por_cobrar.`valor`) as pendiente ,
    // SUM(registros_cuentas_por_cobrar.`saldo`) AS registros_saldos,
    // registros_cuentas_por_cobrar.`fecha_vencimiento` 
    // FROM `registros_cuentas_por_cobrar` 
    // INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
    // WHERE DATE_FORMAT( registros_cuentas_por_cobrar.fecha_vencimiento, '%Y-%m' ) = '".$anio_mes_anterior."' AND clientes.id_empresa=$sesion_id_empresa   ";
$result = mysql_query($sql);
 $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
 if($numero_filas>0){
     while($rowC = mysql_fetch_array($result) ) {
	   $valor_actual = trim($rowC['registros_abonos'])==''?0:$rowC['registros_abonos'];
        $output .="<td >$".number_format($valor_actual, 2, '.', ' ')."</td>";
         $sumaValores1 = $sumaValores1+$valor_actual;
	 }
 }else{
     $output .="<td >0</td>";
 }
//  $sql2= " SELECT 
// 	  SUM(cuentas_por_cobrar.`valor`-cuentas_por_cobrar.`saldo`) as registros_abonos ,
//     SUM(cuentas_por_cobrar.`valor`) as pendiente ,
//     SUM(cuentas_por_cobrar.`saldo`) AS registros_saldos,

// 	 cuentas_por_cobrar.* , 
// 	 clientes.nombre as name, 
// 	 clientes.cedula as identificacion, empresa.imagen , emision.codigo as emision_codigo,
//     establecimientos.codigo as establecimientos_codigo ,
//     ventas.numero_factura_venta as numero_factura_venta
//       FROM `clientes` clientes 
//     INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
//       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
//       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
//       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
//       LEFT JOIN emision ON ventas.codigo_lug = emision.id
//         LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
//       where  cuentas_por_cobrar.id_empresa='".$sesion_id_empresa."'  AND DATE_FORMAT( cuentas_por_cobrar.fecha_vencimiento, '%Y-%m' ) = '".$anio_mes_actual."' AND clientes.id_empresa=$sesion_id_empresa   ";
//   $sql2= "
//     SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
//       SUM(registros_cuentas_por_cobrar.`valor`-registros_cuentas_por_cobrar.`saldo`) as registros_abonos ,
//     SUM(registros_cuentas_por_cobrar.`valor`) as pendiente ,
//     SUM(registros_cuentas_por_cobrar.`saldo`) AS registros_saldos,
//     registros_cuentas_por_cobrar.`fecha_vencimiento` 
//     FROM `registros_cuentas_por_cobrar` 
//     INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
//     WHERE DATE_FORMAT( registros_cuentas_por_cobrar.fecha_vencimiento, '%Y-%m' ) = '".$anio_mes_actual."' AND clientes.id_empresa=$sesion_id_empresa   ";
 $sql2= " SELECT 

    SUM(detalle_cuentas_por_cobrar.`valor`) as registros_abonos ,
    SUM(detalle_cuentas_por_cobrar.`saldo`) AS registros_saldos,

	 clientes.nombre as name, 
	 clientes.cedula as identificacion, empresa.imagen , emision.codigo as emision_codigo,
    establecimientos.codigo as establecimientos_codigo ,
    ventas.numero_factura_venta as numero_factura_venta
      FROM `clientes` clientes 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
       INNER JOIN empresa ON empresa.id_empresa= cuentas_por_cobrar.id_empresa
       INNER JOIN detalle_cuentas_por_cobrar ON detalle_cuentas_por_cobrar.id_cuenta_por_cobrar = cuentas_por_cobrar.id_cuenta_por_cobrar
       LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
       LEFT JOIN emision ON ventas.codigo_lug = emision.id
        LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
       where  cuentas_por_cobrar.id_empresa='".$sesion_id_empresa."'  AND DATE_FORMAT( detalle_cuentas_por_cobrar.fecha_pago, '%Y-%m' ) = '".$anio_mes_actual."' AND clientes.id_empresa=$sesion_id_empresa   ";
$result2 = mysql_query($sql2);
 $numero_filas2 = mysql_num_rows($result2); // obtenemos el número de filas
 if($numero_filas2>0){
     while($rowC2 = mysql_fetch_array($result2) ) {
	   $valor_actual2 = trim($rowC2['registros_abonos'])==''?0:$rowC2['registros_abonos'];
	   $sumaValores2 = $sumaValores2+$valor_actual2;
        $output .="<td >$".number_format($valor_actual2, 2, '.', ' ')."</td>";
	 }
 }else{
     $output .="<td >0...</td>";
 }
    
 }
  $output .="</tbody>";
  	$output .="<tfoot>
		<tr>
		<td  style=' border-top: 1px solid #999;text-align: right;'><strong>SUMAN: <strong></td>
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($sumaValores1, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($sumaValores2, 2, '.', ' ')."</strong></td>
		</tr>
		
		<tr>
		<td  style=' border-top: 1px solid #999;text-align: right;'><strong>SALDO: <strong></td>
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($vp1-$sumaValores1, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($vp2-$sumaValores2, 2, '.', ' ')."</strong></td>
		</tr>
		
		<tr>
		<td  style=' border-top: 1px solid #999;text-align: right;'><strong>TOTALES: <strong></td>
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($vp1, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($vp2, 2, '.', ' ')."</strong></td>
		</tr>
		
		
		
		</tfoot></table>";

 echo $output;
exit;
  
	 
        
	 
	?>
