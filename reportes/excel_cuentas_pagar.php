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

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
$numero = trim($_GET['txtNumeroCuentasCobrar']);
		 $tipo_anticipo = $_GET['tipo_anticipo'];
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
    $fecha_desde=$_GET['txtFechaDesde'];
    $fecha_hasta=$_GET['txtFechaHasta'];
    $cuentaTipo=$_GET['switch-four'];
    $estado=$_GET['switch-estado'];
       
          if($estado=='Canceladas'){
               $fehca='fecha_pago';
              
          }else{
             $fehca='fecha_vencimiento';
          }
          
   if($cuentaTipo==1){
$sql = "SELECT
     proveedores.id_proveedor AS proveedores_id_cliente,
     proveedores.`nombre_comercial` AS proveedores_nombre,
     proveedores.`direccion` AS proveedores_direccion,
     proveedores.`ruc` AS proveedores_cedula,
     proveedores.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero,
     cuentas_por_pagar.`tipo_anticipo` AS cuentas_por_pagar_tipo_anticipo
FROM
     `proveedores` proveedores 
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON proveedores.id_proveedor = cuentas_por_pagar.id_proveedor and proveedores.id_empresa=".$sesion_id_empresa." 
     LEFT JOIN compras	ON compras.id_compra = cuentas_por_pagar.id_compra";
  if($switch_tipo_fecha != 'Vencimiento'){
         $sql.="  and DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') 
            <= '".$fecha_hasta."' ";
    } 
    $sql .= " where cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  ";      

        if ($_GET['txtBuscarCuentasPagar']!=''){
		$sql .= " and proveedores.`nombre` like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
	
	 if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
    if($switch_tipo_fecha == 'Vencimiento'){
        if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
		     	     if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
        $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}

          
        }
    }
   
  
		 
        if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_pagar.`numero_compra`  ";
    } 
        
       $sql.=" ORDER BY  cuentas_por_pagar.`id_proveedor`  DESC  LIMIT ".$_GET['criterio_mostrar'] ;
      
}
if ($cuentaTipo=='2'){
    $sql = "SELECT
     clientes.`id_cliente` AS proveedores_id_cliente,
    CONCAT(clientes.`nombre`,' ',clientes.`apellido` ) AS proveedores_nombre,
     clientes.`direccion` AS proveedores_direccion,
     clientes.`cedula` AS proveedores_cedula,
     clientes.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero,
     cuentas_por_pagar.`tipo_anticipo` AS cuentas_por_pagar_tipo_anticipo
FROM
      `clientes` clientes
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON clientes.`id_cliente` = cuentas_por_pagar.`id_cliente`
     WHERE clientes.id_empresa=".$sesion_id_empresa." ";
    
        if ($_GET['txtBuscarCuentasPagar']!=''){
		$sql .= " and clientes.`nombre` like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
		
	     if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
	              if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
        $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
        
        if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
 $sql.=" ORDER BY  cuentas_por_pagar.`id_cliente`  DESC  LIMIT ".$_GET['criterio_mostrar'];
}
if ($cuentaTipo=='3'){
    $sql = "SELECT
      leads.`id` AS proveedores_id_cliente,
    CONCAT(leads.`name`,' ',leads.`apellido` ) AS proveedores_nombre,
     leads.`direccion` AS proveedores_direccion,
     leads.`identificacion` AS proveedores_cedula,
      leads.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero
FROM

     
     `leads` leads
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON leads.`id` = cuentas_por_pagar.`id_lead` 
     WHERE leads.id_empresa=".$sesion_id_empresa." ";
        if ($_GET['txtBuscarCuentasPagar']!=''){
		$sql .= " and  CONCAT(leads.`name`,leads.`apellido` ) like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
		
	     if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
	              if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
        $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
        
        if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
  $sql.=" ORDER BY  cuentas_por_pagar.`id_lead`  DESC  LIMIT ".$_GET['criterio_mostrar'];
}
if ($cuentaTipo=='4'){
    $sql = "SELECT
      empleados.`id_empleado` AS proveedores_id_cliente,
    CONCAT(empleados.`nombre`,' ',empleados.`apellido` ) AS proveedores_nombre,
     empleados.`direccion` AS proveedores_direccion,
     empleados.`cedula` AS proveedores_cedula,
      empleados.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero
FROM
     
     `empleados` empleados
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON  empleados.`id_empleado` = cuentas_por_pagar.`id_empleado`
     WHERE empleados.id_empresa=".$sesion_id_empresa." ";
        if (isset($_GET['txtBuscarCuentasPagar'])){
	$sql .= " and  empleados.`nombre`  like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
	
		
	     if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
	              if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
        $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
        
        if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
  	  $sql.=" ORDER BY  cuentas_por_pagar.`id_empleado`  DESC  LIMIT ".$_GET['criterio_mostrar'];
}
// echo $sql;exit;
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=documento_exportado_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");



	$output ="<table > <thead>
	<tr ><th colspan='5' >CUENTAS POR PAGAR</th>	</tr>
	<tr ><th colspan='5' >".strtoupper($sesion_empresa_nombre)."</th>	</tr>
	<tr >
	<th >Fecha desde: </th>
	<th>".$_GET['txtFechaDesde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['txtFechaHasta']."</th>
	<th></th>
	</tr>
 </thead></table><table><tr></tr></table>";
 
	 $numero = 0;
  $cambianteP='';
        $suma_valInicial=0;
        $sumaSaldo=0;
        
        $totalSaldoFecha=0;
        $totalValorInicialFecha=0;
	 $result2 = mysql_query($sql) or die(mysql_error());  
	 while($row = mysql_fetch_array($result2)) {
	       $numero ++;
            $cuentas_por_cobrar_id_proveedor =$row['proveedores_id_cliente'];
            $proveedores_nombre_apellido =  $row['proveedores_nombre'];
            $proveedores_cedula = $row['proveedores_cedula'];
            $proveedores_telefono = $row['proveedores_telefono'];
	  if($cuentaTipo==2){
                 $cuentas_por_cobrar_numero_factura =$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_pagar_numero_factura']);
            }else{
                 $cuentas_por_cobrar_numero_factura = $row['cuentas_por_pagar_numero_factura'];
            }
          $cuentas_por_cobrar_documento_numero = $row['cuentas_por_pagar_documento_numero'];
            $cuentas_por_cobrar_fecha_vencimiento = $row['cuentas_por_pagar_fecha_vencimiento'];
            $cuentas_por_cobrar_fecha_pago = $row['cuentas_por_pagar_fecha_pago'];
            $cuentas_por_cobrar_valor = $row['cuentas_por_pagar_valor'];
            $cuentas_por_cobrar_saldo = $row['cuentas_por_pagar_saldo'];
            
            if($numero==1){
                	$output .="
			<table  style='border-style: solid;'><thead>
			
		<tr>
		<td colspan='2'>".utf8_decode('Cédula :')."<strong>".$proveedores_cedula."</strong></td>
		<td colspan='2'>Nombre: <strong>".utf8_decode($proveedores_nombre_apellido)."</strong></td>
		<td>Tel: <strong>".($proveedores_telefono)."</strong></td>
		</tr>
		<tr>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Nro. Fac</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha ".utf8_decode('Emisión')."</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha Pago</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Valor Inicial</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Saldo</strong></td>
		</tr>
		</thead>";
            }
            
            if($cuentas_por_cobrar_id_proveedor != $cambianteP && $numero!=1){
                 $totalSaldoFecha=$totalSaldoFecha +$sumaSaldo;
        $totalValorInicialFecha=$totalValorInicialFecha+$suma_valInicial;
                	$output .="
              		<tfoot>
		<tr>
		<td colspan='3' style=' border-top: 1px solid #999;text-align: right;' ><strong>TOTALES: </strong></td>
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($suma_valInicial, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($sumaSaldo, 2, '.', ' ')."</strong></td>
		</tr>
		</tfoot></table><table><tr></tr></table>";
		
                	$output .="
			<table  style='border-style: solid;'><thead>
			
		<tr>
		<td colspan='2'>".utf8_decode('Cédula :')."<strong>".$proveedores_cedula."</strong></td>
		<td colspan='2'>Nombre: <strong>".utf8_decode($proveedores_nombre_apellido)."<strong></td>
		<td>Tel: <strong>".($proveedores_telefono)."</strong></td>
		</tr>
		<tr>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Nro. Fac</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha ".utf8_decode('Emisión')."</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha Pago</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Valor Inicial</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Saldo</strong></td>
		</tr>
		</thead>";
			$output .="
			<tbody>
		<tr>
		<td>".$cuentas_por_cobrar_numero_factura."</td>
		<td>".$cuentas_por_cobrar_fecha_vencimiento."</td>
		<td>".$cuentas_por_cobrar_fecha_pago."</td>	
		<td>$".number_format($cuentas_por_cobrar_valor, 2, '.', ' ')."</td>
	    <td>$".number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')."</td>
		</tr>
			</tbody>";
              
		

                         $suma_valInicial=0;
                            $sumaSaldo=0;
              }else{
                  	
                  	$output .="
			<tbody>
		<tr>
		<td>".$cuentas_por_cobrar_numero_factura."</td>
		<td>".$cuentas_por_cobrar_fecha_vencimiento."</td>
		<td>".$cuentas_por_cobrar_fecha_pago."</td>	
		<td style='text-align: right;'>$".number_format($cuentas_por_cobrar_valor, 2, '.', ' ')."</td>
	    <td>$".number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')."</td>
		</tr>
			</tbody>";
              
              }
              
                  
        
           
         $suma_valInicial =$suma_valInicial + $cuentas_por_cobrar_valor;
            $sumaSaldo= $sumaSaldo + $cuentas_por_cobrar_saldo;
	
		$sumaBase0  = $sumaBase0 + $vTotal0;
		$sumaBase12 = $sumaBase12 + $vTotal12;
		 $cambianteP = $cuentas_por_cobrar_id_proveedor;
	 }
	  $totalSaldoFecha=$totalSaldoFecha +$sumaSaldo;
        $totalValorInicialFecha=$totalValorInicialFecha+$suma_valInicial;
	 	$output .="
              		<tfoot>
		<tr>
		<td colspan='3' style=' border-top: 1px solid #999;text-align: right;'><strong>TOTALES: <strong></td>
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($suma_valInicial, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($sumaSaldo, 2, '.', ' ')."</strong></td>
		</tr>
		</tfoot></table>";
		
			$output .="<br><table style='border-style: solid;'>
              		<tbody>
		<tr>
		<td colspan='3' style=' border-top: 1px solid #999;text-align: right;'><strong>TOTAL FINAL: <strong></td>
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($totalValorInicialFecha, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($totalSaldoFecha, 2, '.', ' ')."</strong></td>
		</tr>
		</tbody></table>";
		echo $output;
	?>
