<?php

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
$cliente_desafiliacion = $_GET['cliente_desafiliacion'];

$estado_desafiliacion = $_GET['estado_desafiliacion'];
$anio_actual = date('Y');

	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=documento_exportado_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");

	$output ="<table > <thead>
	<tr ><th colspan='14' >CAMBIOS DE ESTADO DE CLIENTES A&Ntilde;O $anio </th>	</tr>
	<tr ><th colspan='14' >".strtoupper($sesion_empresa_nombre)."</th>	</tr>";

  $arraymeses = array('01','02','03','04','05','06','07','08','09','10','11','12' );
        $arraymesesLetras = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre' );
      $anio_actual = date('Y');    
    $sql= "
    SELECT
    historial_estados_cliente.`id_historial`,
    historial_estados_cliente.`estado`,
    historial_estados_cliente.`fecha_inicio`,
    historial_estados_cliente.`id_cliente`,
    historial_estados_cliente.`id_empresa`,
    historial_estados_cliente.`total_registros`,
    historial_estados_cliente.`total_cuentas_cobrar`,
    historial_estados_cliente.`total_pagado_registros_cobrar`,
    historial_estados_cliente.`total_pagado_cuentas_cobrar`,
     clientes.razonSocial,
     clientes.nombre,
     clientes.apellido,
      clientes.id_vendedor,
      clientes.primer_nombre_representante_legal,
       clientes.segundo_nombre_representante_legal,
     clientes.primer_apellido_representante_legal,
     clientes.segundo_apellido_representante_legal
FROM
    `historial_estados_cliente`
INNER JOIN clientes ON
    clientes.id_cliente = historial_estados_cliente.id_cliente
WHERE
   
    historial_estados_cliente.id_empresa =$sesion_id_empresa AND DATE_FORMAT(fecha_inicio,'%Y')='".$anio_actual."' ";
  $estado_desafiliacion = $_GET['estado_desafiliacion'];  
if($estado_desafiliacion!='0'){
     $sql .= " AND   historial_estados_cliente.`estado`= '".$estado_desafiliacion."' ";
}
if($cliente_desafiliacion!='0'){
     $sql .= " AND clientes.id_cliente = $cliente_desafiliacion ";
}
 $sql .= " GROUP BY  clientes.id_cliente ";
$sql.=" ORDER BY `fecha_inicio`   DESC ";
$result = mysql_query($sql);

 $numero_filas = mysql_num_rows($result);
			
	$output .='
        <tr>
            <th>RAZ&Oacute;N SOCIAL</th>
            <th>GERENTE</th>
            <th>OFICIAL DE SERVICIOS</th>
            <th>SALDO'.($anio_actual-1).'</th>
            <th>EMISION A&Ntilde;O ACTUAL</th>
            <th>PAGOS ATRASADOS</th>
            <th>PAGOS A&Ntilde;O ACTUAL</th>
            <th>SALDO A DICIEMBRE '.$anio_actual.' DE LA EMISI&Oacute;N </th>
            <th>ESTADO</th>
            <th>MOTIVO</th>
            <th>FECHA DE NOVEDAD</th>
            <th>MEDIO DE SOLICITUD</th>
        </tr>
         </thead>
		<tbody>';
	
$contadorFilas=0;
    $id_rol_pagos=0;
    
    $total_cuentas_cobrar =0;
    $total_registros = 0;
    $totalFinal = 0;
    $contador=1;
    $total_final_pagos_atrasados = 0;
$total_final_actual =0;
$total_final_saldo =0;
      $arraymesesLetras = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre' );
      
     while ($rs_per=mysql_fetch_array($result) ){
        $id_vendedor = $rs_per['id_vendedor'];
        $id_cliente = $rs_per['id_cliente'];
         $estado =  $rs_per['estado'];
         $fecha = $rs_per['fecha_inicio'];
       $mes1 = date('m', strtotime($fecha));
       $dia = date('d', strtotime($fecha));

  $mes= intval($mes1)-1;
  $anio_anterior = intval($anio_actual)-1;
  
  
$sqlVP2= "
    SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
     SUM(cuentas_por_cobrar.`valor`-cuentas_por_cobrar.`saldo`) as registros_abonos ,
    SUM(cuentas_por_cobrar.`valor`) as pendiente ,
    SUM(cuentas_por_cobrar.`saldo`) AS registros_saldos,
    cuentas_por_cobrar.`fecha_vencimiento` 
    FROM `cuentas_por_cobrar` 
    INNER JOIN clientes ON clientes.id_cliente = cuentas_por_cobrar.id_cliente 
    WHERE DATE_FORMAT( cuentas_por_cobrar.fecha_vencimiento, '%Y' ) <= '".$anio_anterior."' AND clientes.id_cliente=$id_cliente   ";
$resultVP2 = mysql_query($sqlVP2);
 while($rowVP2 = mysql_fetch_array($resultVP2) ) {
     $vp2= $rowVP2['registros_saldos'];
 }
$vp2 = trim($vp2)!=''?$vp2:0;


 
   $sqlVP1= "
    SELECT clientes.id_cliente, clientes.nombre, clientes.apellido, 
     SUM(registros_cuentas_por_cobrar.`valor`-registros_cuentas_por_cobrar.`saldo`) as registros_abonos ,
    SUM(registros_cuentas_por_cobrar.`valor`) as emitido ,
    SUM(registros_cuentas_por_cobrar.`saldo`) AS registros_saldos,
    registros_cuentas_por_cobrar.`fecha_vencimiento` 
    FROM `registros_cuentas_por_cobrar` 
    INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
    WHERE DATE_FORMAT( registros_cuentas_por_cobrar.fecha_vencimiento, '%Y' ) = '".$anio_actual."' AND clientes.id_cliente=$id_cliente   ";
$resultVP1 = mysql_query($sqlVP1);
 while($rowVP1 = mysql_fetch_array($resultVP1) ) {
     $vp1= $rowVP1['emitido'];
 }
 $vp1 = trim($vp1)!=''?$vp1:0;
  
$sqlPagosAtrasados="SELECT
    clientes.id_cliente,
    clientes.nombre,
    clientes.apellido,
    SUM(
        cuentas_por_cobrar.`valor` - cuentas_por_cobrar.`saldo`
    ) AS registros_abonos,
    SUM(cuentas_por_cobrar.`valor`) AS pendiente,
    SUM(cuentas_por_cobrar.`saldo`) AS registros_saldos,
    cuentas_por_cobrar.`fecha_vencimiento`,
     cuentas_por_cobrar.`fecha_pago`
FROM
    `cuentas_por_cobrar`
INNER JOIN clientes ON clientes.id_cliente = cuentas_por_cobrar.id_cliente
WHERE
    DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y'
    ) <= '".$anio_actual."' AND clientes.id_empresa = $sesion_id_empresa AND cuentas_por_cobrar.fecha_vencimiento< DATE_FORMAT( cuentas_por_cobrar.fecha_pago,'%Y-%m-%d') AND clientes.id_cliente=$id_cliente";
  $resultPagosAtrasados = mysql_query($sqlPagosAtrasados);
  $total_pagos_atrasados = 0;
  while($rowPat = mysql_fetch_array($resultPagosAtrasados) ){
      $total_pagos_atrasados = $rowPat['pendiente'];
  }
  
   $sqlPagosActual="SELECT
    clientes.id_cliente,
    clientes.nombre,
    clientes.apellido,
    SUM(
        cuentas_por_cobrar.`valor` - cuentas_por_cobrar.`saldo`
    ) AS registros_abonos,
    SUM(cuentas_por_cobrar.`valor`) AS pendiente,
    SUM(cuentas_por_cobrar.`saldo`) AS registros_saldos,
    cuentas_por_cobrar.`fecha_vencimiento`,
     cuentas_por_cobrar.`fecha_pago`
FROM
    `cuentas_por_cobrar`
INNER JOIN clientes ON clientes.id_cliente = cuentas_por_cobrar.id_cliente
WHERE
    DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y'
    ) <= '".$anio_actual."' AND clientes.id_cliente=$id_cliente ";
  $resultPagosActual = mysql_query($sqlPagosActual);
  $total_pagos_actual = 0;
  while($rowPat = mysql_fetch_array($resultPagosActual) ){
      $total_pagos_actual = $rowPat['registros_abonos'];
  }
  
    $sqlPagosAtrasados2="SELECT
    `id_cuenta_por_cobrar`,
   SUM(saldo) as saldo_anio_actual
   
FROM
    `cuentas_por_cobrar`
    WHERE `id_cliente`=$id_cliente AND `id_empresa`=$sesion_id_empresa AND DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y'
    ) <= '".$anio_actual."' AND clientes.id_cliente=$id_cliente ;";
  $resultPagosAtrasados2 = mysql_query($sqlPagosAtrasados2);
  $saldo_anio_actual = 0;
  while($rowPat2 = mysql_fetch_array($resultPagosAtrasados2) ){
      $saldo_anio_actual = $rowPat2['saldo_anio_actual'];
  }
  

  $sqlPagosActuales2="SELECT
    `id_cuenta_por_cobrar`,
   SUM(`valor`- saldo) as abonado
    SUM( saldo) as saldo
   
FROM
    `cuentas_por_cobrar`
    WHERE `id_cliente`=$id_cliente AND `id_empresa`=$sesion_id_empresa ;";
  $resultPagosActuales2 = mysql_query($sqlPagosActuales2);
 $total_pagos_actuales2 = 0;
  $saldo_pagos_actuales2 = 0;
  
   while($rowPact2 = mysql_fetch_array($resultPagosActuales2) ){
       $total_pagos_actuales2 = $rowPact2['abonado'];
      $saldo_pagos_actuales2 = $rowPact2['saldo'];
  }
  $total_pagos_atrasados = trim($total_pagos_atrasados)==''?0:$total_pagos_atrasados;
  $total_pagos_actual = trim($total_pagos_actual)==''?0:$total_pagos_actual;
  $saldo_pagos_actuales = trim($saldo_pagos_actuales)==''?0:$saldo_pagos_actuales;
  
  $saldo_anio_actual= trim($saldo_anio_actual)==''?0:$saldo_anio_actual;
  
  $saldo_pagos_actuales2 = trim($saldo_pagos_actuales2)==''?0:$saldo_pagos_actuales2;
    $total_pagos_atrasados2 = trim($total_pagos_atrasados2)==''?0:$total_pagos_atrasados2;
  
$sqlVendedor = "SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email`, `tipo_vendedor` FROM `vendedores` WHERE id_vendedor=$id_vendedor";
$resultVendedor = mysql_query($sqlVendedor);
while($rowVendedor = mysql_fetch_array($resultVendedor) ){
    $nombreVendedor = $rowVendedor['nombre'].' '.$rowVendedor['apellidos'];
}

 $sqlNovedades="SELECT `id_novedades`, `observacion`, `id_cliente`, `fecha_novedad`, `tramite` FROM `novedades_afiliado` WHERE id_cliente and tramite=1 AND id_cliente=$id_cliente ORDER BY id_novedades DESC limit 1";
$resultNovedades = mysql_query($sqlNovedades);
$observacion="";
$fecha_novedad="";
while($rowNoved = mysql_fetch_array($resultNovedades) ){
    $observacion = $rowNoved["observacion"];
    $fecha_novedad = $rowNoved["fecha_novedad"];
}
$representante='';
if($rs_per['primer_nombre_representante_legal']!='Indefinido'){
         $representante=  utf8_decode($rs_per['primer_nombre_representante_legal'].' '.$rs_per['segundo_nombre_representante_legal'].' '.$rs_per['primer_apellido_representante_legal'].' '.$rs_per['segundo_apellido_representante_legal']);
         }
         $calculo =($vp2+$vp1)-($total_pagos_atrasados+$total_pagos_actual);
       $output .=  "<tr>
        <td>".utf8_decode($rs_per['nombre']." ".$rs_per['apellido'])."</td>
        <td>".$representante."</td>
        <td>".$nombreVendedor."</td>
        <td>".$vp2."</td>
        <td>".$vp1."</td>
        <td>".$total_pagos_atrasados."</td>
        <td>".$total_pagos_actual."</td>
        <td>".$calculo."</td>
        <td>".$estado."</td>
        <td>".$observacion."</td>
        <td>".$fecha_novedad."</td>
        <td>EMAIL-CARTA</td></tr>";

  
$total_saldo_anterior = $total_saldo_anterior +$vp2;
$total_emision_actual = $total_emision_actual +$vp1;
$total_final_pagos_atrasados = $total_final_pagos_atrasados +$total_pagos_atrasados;
$total_final_pagos_actual = $total_final_pagos_actual +$total_pagos_actual;
$total_final_saldo_actual = $total_final_saldo_actual +($vp2+$vp1)-($total_pagos_atrasados+$total_pagos_actual);
$contador++;
  
}
$output .= "<tr>
<td colspan='3'><strong>TOTAL</strong></td>

<td><strong> $total_saldo_anterior </strong></td>
<td><strong> $total_emision_actual </strong></td>
<td><strong>$total_pagos_atrasados</strong></td>
<td><strong> $total_final_pagos_actual </strong></td>
<td><strong> $total_final_saldo_actual </strong></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>   
</tbody>   
</table>";
 echo $output;
exit;
  
	 
        
	 
	?>
