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

	$output ="<table >";

        
  
	$output .="
	<tr >
	<th style='padding: 15px;' >OFICIAL</th>	
	<th style='padding: 15px;'>EMPRESAS </th> 
	<th  style='padding: 15px;'>SALDO ANTERIOR </th>
	<th  style='padding: 15px;' >EMISI&Oacute;N A&Ntilde;O ACTUAL $anio</th>
	</tr>
	<tbody>";
	
	$sqlOficiales ="SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email`, `tipo_vendedor` FROM `vendedores` WHERE id_empresa=$sesion_id_empresa and (tipo_vendedor='ambos' or tipo_vendedor='recaudador');";
	$resultOficiales = mysql_query($sqlOficiales);
	$empresas_total_final = 0;
	$saldo_total_final = 0;
	$emision_total_final = 0;
	while($rowOfi = mysql_fetch_array($resultOficiales) ){
	    $recaudador_actual = $rowOfi['id_vendedor'];
	    
	    $sqlClientes = "SELECT `id_cliente`, `id_recaudador` FROM `clientes` WHERE id_recaudador=".$recaudador_actual." and id_empresa=$sesion_id_empresa;";
	    $resultClientes = mysql_query($sqlClientes);
	    $numFilasClientes = mysql_num_rows($resultClientes);
	    
	     $sqlVP2= "
    SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
     SUM(cuentas_por_cobrar.`valor`-cuentas_por_cobrar.`saldo`) as registros_abonos ,
    SUM(cuentas_por_cobrar.`valor`) as pendiente ,
    SUM(cuentas_por_cobrar.`saldo`) AS registros_saldos,
    cuentas_por_cobrar.`fecha_vencimiento` 
    FROM `cuentas_por_cobrar` 
    INNER JOIN clientes ON clientes.id_cliente = cuentas_por_cobrar.id_cliente 
    WHERE DATE_FORMAT( cuentas_por_cobrar.fecha_vencimiento, '%Y' ) < '".$anio."' AND clientes.id_recaudador=$recaudador_actual   ";
    
$resultVP2 = mysql_query($sqlVP2);
$vp2=0;
 while($rowVP2 = mysql_fetch_array($resultVP2) ) {
     $vp2= $rowVP2['registros_saldos'];
 }
    
     $sqlEmision= "
    SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
    SUM(cuentas_por_cobrar.`valor`) as emitido ,
    cuentas_por_cobrar.`fecha_vencimiento` 
    FROM `cuentas_por_cobrar` 
    INNER JOIN clientes ON clientes.id_cliente = cuentas_por_cobrar.id_cliente 
    WHERE DATE_FORMAT( cuentas_por_cobrar.fecha_vencimiento, '%Y' ) = '".$anio."' AND clientes.id_recaudador=$recaudador_actual   ";
    
$resultEmision = mysql_query($sqlEmision);
$emision_anio_actual=0;
 while($rowVP1 = mysql_fetch_array($resultEmision) ) {
     $emision_anio_actual= $rowVP1['emitido'];
 }
    
	    	$output .="
	<tr >
	<td  >".$rowOfi['nombre']." ".$rowOfi['apellidos']."</td>	
	<td style='text-align: center;' >".$numFilasClientes."</td> 
	<td  style=' text-align: right;'>$".number_format($vp2, 2, '.', ' ')."</td>
	<td  style=' text-align: right;' >$".number_format($emision_anio_actual, 2, '.', ' ')."</td>
	</tr>
	<tbody>";
	$empresas_total_final = $empresas_total_final + $numFilasClientes;
	$saldo_total_final = $saldo_total_final + $vp2;
	$emision_total_final = $emision_total_final + $emision_anio_actual;
	}
	
  $output .="</tbody>";
  	$output .="<tfoot>
		<tr>
		<td  style=' border-top: 1px solid #999;text-align: center;'><strong>TOTALES: <strong></td>
		<td style=' border-top: 1px solid #999;text-align: center;'><strong>".$empresas_total_final."</strong></td>
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format(floatval($saldo_total_final), 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format(floatval($emision_total_final), 2, '.', ' ')."</strong></td>
		</tr>
		</tfoot></table>";

 echo $output;
exit;
  
	 
        
	 
	?>
