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
 $dominio = $_SERVER['SERVER_NAME'];
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
    $switch_tipo_fecha = isset($_GET['switch_tipo_fecha']) ?$_GET['switch_tipo_fecha']:'Vencimiento';      
          if($estado=='Canceladas'){
               $fehca='fecha_pago';
              
          }else{
             $fehca='fecha_vencimiento';
          }
$nombreVendedor = '';
if(isset($_GET['id_vendedor']) ){
    if($_GET['id_vendedor']!='' && $_GET['id_vendedor']!='0'){
          $sqlVendedor.=" SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email`, `tipo_vendedor` FROM `vendedores` WHERE id_vendedor = '".$_GET['id_vendedor']."' ";
          $resultVendedor= mysql_query($sqlVendedor);
          while($rowV = mysql_fetch_array($resultVendedor) ){
              $nombreVendedor = ' DEL VENDEDOR :'.strtoupper($rowV['nombre']).' '.strtoupper($rowV['apellidos']);
          }
    }
}          
    if ($cuentaTipo=='2'){
    
    $sql = "SELECT
    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS cuentas_por_cobrar_id_cuenta_por_cobrar,
    cuentas_por_cobrar.`numero_factura` AS cuentas_por_cobrar_numero_factura,
    cuentas_por_cobrar.`referencia` AS cuentas_por_cobrar_referencia,
    cuentas_por_cobrar.`valor` AS cuentas_por_cobrar_valor,
    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
    cuentas_por_cobrar.`numero_asiento` AS cuentas_por_cobrar_numero_asiento,
    cuentas_por_cobrar.`fecha_vencimiento` AS cuentas_por_cobrar_fecha_vencimiento,
    cuentas_por_cobrar.`fecha_pago` AS cuentas_por_cobrar_fecha_pago,
    cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_proveedor,
    cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
    cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
    clientes.`id_cliente` AS clientes_id_cliente,
    clientes.`nombre` AS proveedores_nombre_comercial,
    clientes.`apellido` AS clientes_apellido,
    clientes.`direccion` AS clientes_direccion,
    clientes.`cedula` AS proveedores_ruc,
      clientes.`telefono` AS proveedores_telefono,
    clientes.`prop_nombre` AS clientes_prop_nombre,
    clientes.`numero_casa` AS clientes_numero_casa,
    cuentas_por_cobrar.`documento_numero` AS cuentas_por_cobrar_documento_numero,
    ventas.fecha_venta AS ventas_fecha_venta,
     emision.codigo as emision_codigo,
    establecimientos.codigo as establecimientos_codigo
    
    FROM
    `clientes` clientes 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente` 
     LEFT JOIN `tipo_anticipo` tipo_anticipo  ON tipo_anticipo.`id_tipo_anticipo` = cuentas_por_cobrar.`tipo_anticipo`
    LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
  LEFT JOIN emision ON ventas.codigo_lug = emision.id
LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun ";
 if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_cobrar ON cuentas_por_cobrar.id_cuenta_por_cobrar = detalle_cuentas_por_cobrar.id_cuenta_por_cobrar ";
}
    // $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' and cuentas_por_cobrar.`estado` = '".$estado."' 
    // and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";     
if($switch_tipo_fecha == 'Vencimiento'){
             if($estado == 'Todos'){
             $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."'  AND(
            
            (DATE_FORMAT(
                cuentas_por_cobrar.fecha_pago,
                '%Y-%m-%d'
            ) >= '".$fecha_desde."' AND DATE_FORMAT(
                cuentas_por_cobrar.fecha_pago,
                '%Y-%m-%d'
            ) <= '".$fecha_hasta."' AND cuentas_por_cobrar.`saldo` = 0) or 
            (DATE_FORMAT(
                cuentas_por_cobrar.fecha_vencimiento,
                '%Y-%m-%d'
            ) >= '".$fecha_desde."' AND DATE_FORMAT(
                cuentas_por_cobrar.fecha_vencimiento,
                '%Y-%m-%d'
            ) <= '".$fecha_hasta."' ) ) "; 
        }else{
             $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' 
                and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') 
                <= '".$fecha_hasta."' "; 
        }
 }else{
     $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' 
                and DATE_FORMAT(ventas.fecha_venta, '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(ventas.fecha_venta, '%Y-%m-%d') 
                <= '".$fecha_hasta."' "; 
 }
      if($tipo_anticipo !='0' && ($dominio=='dcacorp.com.ec' || $dominio=='www.dcacorp.com.ec') ){
    $sql .=" AND cuentas_por_cobrar.`tipo_anticipo` ='".$tipo_anticipo."'  ";
}
    	if($numero!=''){
	     $sql.=" and cuentas_por_cobrar.`numero_factura`=$numero ";
	}
		if(isset($_GET['id_vendedor']) ){
    if($_GET['id_vendedor']!='' && $_GET['id_vendedor']!='0'){
          $sql.="   AND clientes.id_vendedor = '".$_GET['id_vendedor']."' ";
    }
}
	  if($estado != 'Todos'){
  	if($estado == 'Pendientes'){
		    $sql.=" and cuentas_por_cobrar.`saldo`>0";
		}else{
		     $sql.=" and cuentas_por_cobrar.`saldo`=0 ";
		}
  }		
	
	 if (strlen($_GET['txtBuscarCuentasCobrar'])>0){
		$sql .= " and CONCAT(clientes.`nombre`, ' ', clientes.`apellido`) like '%".$_GET['txtBuscarCuentasCobrar']."%' "; }
		
	 if($_GET['listado_por']=='Facturas'){
            $sql.=" GROUP BY  cuentas_por_cobrar.`numero_factura`  ";
        }	
         
  $sql.=" order by cuentas_por_cobrar.`id_cliente`,cuentas_por_cobrar.`numero_factura`, cuentas_por_cobrar.`fecha_vencimiento`  ";
      
  } else if ($cuentaTipo=='1'){
      
    $sql = "SELECT
    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS cuentas_por_cobrar_id_cuenta_por_cobrar,
    cuentas_por_cobrar.`numero_factura` AS cuentas_por_cobrar_numero_factura,
    cuentas_por_cobrar.`referencia` AS cuentas_por_cobrar_referencia,
    cuentas_por_cobrar.`valor` AS cuentas_por_cobrar_valor,
    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
    cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_proveedor,
    
    cuentas_por_cobrar.`numero_asiento` AS cuentas_por_cobrar_numero_asiento,
    cuentas_por_cobrar.`fecha_vencimiento` AS cuentas_por_cobrar_fecha_vencimiento,
    cuentas_por_cobrar.`fecha_pago` AS cuentas_por_cobrar_fecha_pago,
    cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
    cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
    cuentas_por_cobrar.`documento_numero` AS cuentas_por_cobrar_documento_numero,
    proveedores.`id_proveedor` AS clientes_id_cliente,
    proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
    proveedores.`telefono` AS proveedores_telefono,
    proveedores.`nombre` AS clientes_nombre,
    proveedores.`ruc` AS proveedores_ruc,
    enlaces_compras.`nombre` AS  enlace_nombre,
     '' as tipo_anticipo
    FROM
    `proveedores` proveedores 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON proveedores.`id_proveedor` = cuentas_por_cobrar.`id_proveedor`
    LEFT JOIN `enlaces_compras` enlaces_compras ON enlaces_compras.`id` = cuentas_por_cobrar.`id_forma_pago` ";
     if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_cobrar ON cuentas_por_cobrar.id_cuenta_por_cobrar = detalle_cuentas_por_cobrar.id_cuenta_por_cobrar ";
}
    // $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' and cuentas_por_cobrar.`estado` = '".$estado."' and cuentas_por_cobrar.`saldo`>0 
    // and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' "; 
    if($estado == 'Todos'){
     $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_cobrar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_cobrar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_cobrar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
     $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' 
        and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') 
        <= '".$fecha_hasta."' "; 
}
    
          if($tipo_anticipo !='0' && ($dominio=='dcacorp.com.ec' || $dominio=='www.dcacorp.com.ec') ){
    $sql .=" AND cuentas_por_cobrar.`tipo_anticipo` ='".$tipo_anticipo."'  ";
}
if (strlen($_GET['txtBuscarCuentasCobrar'])>0){
	$sql .= " and  CONCAT(proveedores.`nombre`, ' ', proveedores.`nombre_comercial`) like '%".$_GET['txtBuscarCuentasCobrar']."%' "; }
    	if($numero!=''){
	     $sql.=" and cuentas_por_cobrar.`numero_factura`=$numero ";
	}
if($estado != 'Todos'){
  	if($estado == 'Pendientes'){
		    $sql.=" and cuentas_por_cobrar.`saldo`>0";
		}else{
		     $sql.=" and cuentas_por_cobrar.`saldo`=0 ";
		}
  }	
      
       $sql.=" order by cuentas_por_cobrar.`id_proveedor`,cuentas_por_cobrar.`numero_factura`, cuentas_por_cobrar.`fecha_vencimiento`  ";
  } else if ($cuentaTipo=='3'){
      
      $sql = "SELECT
      cuentas_por_cobrar.`id_cuenta_por_cobrar` AS cuentas_por_cobrar_id_cuenta_por_cobrar,
      cuentas_por_cobrar.`numero_factura` AS cuentas_por_cobrar_numero_factura,
      cuentas_por_cobrar.`referencia` AS cuentas_por_cobrar_referencia,
      cuentas_por_cobrar.`valor` AS cuentas_por_cobrar_valor,
      cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
      cuentas_por_cobrar.`numero_asiento` AS cuentas_por_cobrar_numero_asiento,
      cuentas_por_cobrar.`fecha_vencimiento` AS cuentas_por_cobrar_fecha_vencimiento,
      cuentas_por_cobrar.`fecha_pago` AS cuentas_por_cobrar_fecha_pago,
      cuentas_por_cobrar.`id_lead` AS cuentas_por_cobrar_id_proveedor,
      cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
      cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
      cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
      cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
      cuentas_por_cobrar.`cuotaAdmin` AS cuentas_por_cobrar_cuotaAdmin,
      cuentas_por_cobrar.`documento_numero` AS cuentas_por_cobrar_documento_numero,
      
      leads.`id` AS  clientes_id_cliente,
     leads.`name` AS proveedores_nombre_comercial,
     leads.`identificacion` AS proveedores_ruc,
     leads.`telefono` AS proveedores_telefono
  FROM
         `leads` leads
       
       INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON leads.`id` = cuentas_por_cobrar.`id_lead` 
       
       ";
       
 if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_cobrar ON cuentas_por_cobrar.id_cuenta_por_cobrar = detalle_cuentas_por_cobrar.id_cuenta_por_cobrar ";
}
    // $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' and cuentas_por_cobrar.`estado` = '".$estado."'  
    //       and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' "; 
if($estado == 'Todos'){
     $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_cobrar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_cobrar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_cobrar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
     $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' 
        and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') 
        <= '".$fecha_hasta."' "; 
}
if($estado != 'Todos'){
  	if($estado == 'Pendientes'){
		    $sql.=" and cuentas_por_cobrar.`saldo`>0";
		}else{
		     $sql.=" and cuentas_por_cobrar.`saldo`=0 ";
		}
  }	
     if (strlen($_GET['txtBuscarCuentasCobrar'])>0){
	$sql .= " and  CONCAT(leads.`name`, ' ', leads.`apellido`)  like '%".$_GET['txtBuscarCuentasCobrar']."%' "; }
  
   if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_cobrar.`numero_factura`  ";
    }
$sql.=" order by cuentas_por_cobrar.`id_lead`,cuentas_por_cobrar.`numero_factura`, cuentas_por_cobrar.`fecha_vencimiento` ";
  } else if ($cuentaTipo=='4'){
    
    $sql = "SELECT
    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS cuentas_por_cobrar_id_cuenta_por_cobrar,
    cuentas_por_cobrar.`numero_factura` AS cuentas_por_cobrar_numero_factura,
    cuentas_por_cobrar.`referencia` AS cuentas_por_cobrar_referencia,
    cuentas_por_cobrar.`valor` AS cuentas_por_cobrar_valor,
    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
    cuentas_por_cobrar.`numero_asiento` AS cuentas_por_cobrar_numero_asiento,
    cuentas_por_cobrar.`fecha_vencimiento` AS cuentas_por_cobrar_fecha_vencimiento,
    cuentas_por_cobrar.`fecha_pago` AS cuentas_por_cobrar_fecha_pago,
    cuentas_por_cobrar.`id_empleado` AS cuentas_por_cobrar_id_proveedor,
    cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
    cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
    cuentas_por_cobrar.`cuotaAdmin` AS cuentas_por_cobrar_cuotaAdmin,
    
    empleados.`id_empleado` AS clientes_id_cliente,
    empleados.`nombre` AS proveedores_nombre_comercial,
     empleados.`cedula` AS clientes_cedula,
      empleados.`telefono` AS proveedores_telefono
FROM
`empleados` empleados
     
     INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON empleados.`id_empleado` = cuentas_por_cobrar.`id_empleado` 
     
     ";
if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_cobrar ON cuentas_por_cobrar.id_cuenta_por_cobrar = detalle_cuentas_por_cobrar.id_cuenta_por_cobrar ";
}
if($estado == 'Todos'){
     $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_cobrar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_cobrar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_cobrar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_cobrar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
     $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' 
        and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') 
        <= '".$fecha_hasta."' "; 
}
        // $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' 
        // and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' "; 
        	if($numero!=''){
	     $sql.=" and cuentas_por_cobrar.`numero_factura`=$numero ";
	}
	
if($estado != 'Todos'){
  	if($estado == 'Pendientes'){
		    $sql.=" and cuentas_por_cobrar.`saldo`>0";
		}else{
		     $sql.=" and cuentas_por_cobrar.`saldo`=0 ";
		}
  }	
if (strlen($_GET['txtBuscarCuentasCobrar'])>0){
	$sql .= " and CONCAT(empleados.`nombre`, ' ', empleados.`apellido`) like '%".$_GET['txtBuscarCuentasCobrar']."%' "; }
if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_cobrar.`numero_factura`  ";
    }
$sql.=" order by cuentas_por_cobrar.`id_empleado`,cuentas_por_cobrar.`numero_factura`, cuentas_por_cobrar.`fecha_vencimiento` ";

} 

	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=documento_exportado_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");



	$output ="<table > <thead>
	<tr ><th colspan='5' >CUENTAS POR COBRAR".$nombreVendedor."</th>	</tr>
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
	     $tipo_anticipo_nombre = $row['tipo_anticipo'];
	     $filaAnticipo="";
	     $filaAnticipo2="";
	     $filaAnticipo3="";
	     if($dominio=='dcacorp.com.ec' || $dominio=='www.dcacorp.com.ec'){
	        $filaAnticipo="<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Tipo de Anticipo</strong></td>";
            $filaAnticipo2="<td>".$tipo_anticipo_nombre."</td>";
            $filaAnticipo3="<td style=' border-top: 1px solid #999;text-align: right;' ></td>";
	     }
	     $ventas_fecha_venta = $row['ventas_fecha_venta'];
	     $filaFechaEmision="";
            $filaFechaEmision2="";
            $filaFechaEmision3="";
             if($cuentaTipo==2){
                 $filaFechaEmision="<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Emision Factura</strong></td>";
                 $filaFechaEmision2="<td>".$ventas_fecha_venta."</td>";
                   if($_GET['listado_por'] == 'Clientes' ){
                        $filaFechaEmision3="<td style=' border-top: 1px solid #999;text-align: right;' ></td>";
                   }else{
                        $filaFechaEmision3="<td ></td>";
                   }
                  
             }
             
	     if($_GET['listado_por'] == 'Clientes' ){
	         $numero ++;
            $cuentas_por_cobrar_id_proveedor =$row['clientes_id_cliente'];
            $proveedores_nombre_apellido =  $row['proveedores_nombre_comercial']." ".$row['clientes_apellido'];
            $proveedores_cedula = $row['proveedores_ruc'];
            $proveedores_telefono = $row['proveedores_telefono'];
	  if($cuentaTipo==2){
                 $cuentas_por_cobrar_numero_factura =$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_cobrar_numero_factura']);
            }else{
                 $cuentas_por_cobrar_numero_factura = $row['cuentas_por_cobrar_numero_factura'];
            }
          $cuentas_por_cobrar_documento_numero = $row['cuentas_por_cobrar_documento_numero'];
            $cuentas_por_cobrar_fecha_vencimiento = $row['cuentas_por_cobrar_fecha_vencimiento'];
            $cuentas_por_cobrar_fecha_pago = $row['cuentas_por_cobrar_fecha_pago'];
            $cuentas_por_cobrar_valor = $row['cuentas_por_cobrar_valor'];
            $cuentas_por_cobrar_saldo = $row['cuentas_por_cobrar_saldo'];
            
            if($numero==1){
                	$output .="
			<table  style='border-style: solid;'><thead>
			
		<tr>
		<td colspan='2'>".utf8_decode('C&eacute;dula :')."<strong>".$proveedores_cedula."</strong></td>
		<td colspan='2'>Nombre: <strong>".utf8_decode($proveedores_nombre_apellido)."</strong></td>
		<td>Tel: <strong>".($proveedores_telefono)."</strong></td>
		</tr>
		<tr>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Nro. Fac</strong></td>".$filaFechaEmision."
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha ".utf8_decode('Vencimiento')."</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha Pago</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Valor Inicial</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Saldo</strong></td>".$filaAnticipo."
		</tr>
		</thead>";
            }
            
            if($cuentas_por_cobrar_id_proveedor != $cambianteP && $numero!=1){
                   $totalSaldoFecha=$totalSaldoFecha +$sumaSaldo;
        $totalValorInicialFecha=$totalValorInicialFecha+$suma_valInicial;
        
                	$output .="
              		<tfoot>
		<tr>
		<td colspan='3' style=' border-top: 1px solid #999;text-align: right;' ><strong>TOTALES: </strong></td>".$filaFechaEmision3."
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($suma_valInicial, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($sumaSaldo, 2, '.', ' ')."</strong></td>".$filaAnticipo3."
		</tr>
		</tfoot></table><table><tr></tr></table>";
		
                	$output .="
			<table  style='border-style: solid;'><thead>
			
		<tr>
		<td colspan='2'>".utf8_decode('C&eacute;dula :')."<strong>".$proveedores_cedula."</strong></td>
		<td colspan='2'>Nombre: <strong>".utf8_decode($proveedores_nombre_apellido)."<strong></td>
		<td>Tel: <strong>".($proveedores_telefono)."</strong></td>
		</tr>
		<tr>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Nro. Fac</strong></td>".$filaFechaEmision."
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha ".utf8_decode('Vencimiento')."</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Fecha Pago</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Valor Inicial</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Saldo</strong></td>".$filaAnticipo."
		</tr>
		</thead>";
			$output .="
			<tbody>
		<tr>
		<td>".$cuentas_por_cobrar_numero_factura."</td>
		".$filaFechaEmision2."
		<td>".$cuentas_por_cobrar_fecha_vencimiento."</td>
		<td>".$cuentas_por_cobrar_fecha_pago."</td>	
		<td>$".number_format($cuentas_por_cobrar_valor, 2, '.', ' ')."</td>
	    <td>$".number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')."</td>
	    ".$filaAnticipo2."
		</tr>
			</tbody>";
              
		

                         $suma_valInicial=0;
                            $sumaSaldo=0;
              }else{
                  	
                  	$output .="
			<tbody>
		<tr>
		<td>".$cuentas_por_cobrar_numero_factura."</td>
			".$filaFechaEmision2."
		<td>".$cuentas_por_cobrar_fecha_vencimiento."</td>
		<td>".$cuentas_por_cobrar_fecha_pago."</td>	
		<td style='text-align: right;'>$".number_format($cuentas_por_cobrar_valor, 2, '.', ' ')."</td>
	    <td>$".number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')."</td>
	    ".$filaAnticipo2."
		</tr>
			</tbody>";
              
              }
              
                  
        
           
         $suma_valInicial =$suma_valInicial + $cuentas_por_cobrar_valor;
            $sumaSaldo= $sumaSaldo + $cuentas_por_cobrar_saldo;
	
		$sumaBase0  = $sumaBase0 + $vTotal0;
		$sumaBase12 = $sumaBase12 + $vTotal12;
		 $cambianteP = $cuentas_por_cobrar_id_proveedor;
	     }else{
	        $total_valor=0;
            $total_saldo=0;
            $total_abonado=0;
            $numero++;
            
                	$output .="<table></table>
			<table  style='border-style: solid;'><thead>
			
		<tr>
		<td colspan='6'><strong>".$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_cobrar_numero_factura'])."   ".$row['clientes_apellido']."</strong></td>
		</tr>
		<tr>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Nro. Fac</strong></td>".$filaFechaEmision."
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Saldo</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Abonos</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Asientos</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Pago</strong></td>
		<td style=' border-bottom: 1px solid #999;text-align: center;'><strong>Valor</strong></td>
		</tr>
		</thead>";
              
		$id_cuenta_por_pagar = $row['cuentas_por_cobrar_id_cuenta_por_cobrar'];           
                $cuentas_por_pagar_valor = $row['cuentas_por_cobrar_valor'];
                $cuentas_por_pagar_saldo = $row['cuentas_por_cobrar_saldo'];
                $total_valor = $total_valor + $cuentas_por_pagar_valor;
                $total_saldo = $total_saldo + $cuentas_por_pagar_saldo;
    $sqlDetalleCC="SELECT `id_detalle_cuentas_por_cobrar`,`valor`, `fecha_pago`,`id_cuenta_por_cobrar`,numero_asiento FROM `detalle_cuentas_por_cobrar` WHERE id_cuenta_por_cobrar=$id_cuenta_por_pagar ";
                $resultDetalleCC = mysql_query($sqlDetalleCC) ;
                $saldo_detalle= $row['cuentas_por_cobrar_valor'];
                $totalDetalleValor= 0;
                $totalDetalleSaldo=0;
                while( $rowDetalle = mysql_fetch_array( $resultDetalleCC )){
                   
                    $saldo_detalle =$saldo_detalle - $rowDetalle['valor'];
                    $totalDetalleSaldo = $saldo_detalle;
                    $totalDetalleValor = $totalDetalleValor+ $rowDetalle['valor'];
                    $numFac='';
                if($cuentaTipo==2){
                    $numFac=$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_cobrar_numero_factura']);
                }else{
                   $numFac= ceros($row['cuentas_por_cobrar_numero_factura']);
                }
                $output .="
        		
        		<tr>
        		<td>".$numFac."</td>".$filaFechaEmision2."
        		<td>$ ".number_format($saldo_detalle, 2, '.', ' ')."</td>
        		<td>$ ".number_format($rowDetalle['valor'], 2, '.', ' ')."</td>	
        		<td>".$rowDetalle['numero_asiento']."</td>
        		<td>".$rowDetalle['fecha_pago']."</td>	
        	    <td>$".number_format($rowDetalle['valor'], 2, '.', ' ')."</td>
        		</tr>
        			";    
                }
               $output .="
                <tr>
                    <td><strong>TOTAL</strong></td>
                  ".$filaFechaEmision3."
                    <td><strong>$ ".number_format($totalDetalleSaldo, 2, '.', ' ')."</strong></td>
                    <td><strong>$ ".number_format($totalDetalleValor, 2, '.', ' ')."</strong></td>
                      <td colspan='3'></td>
                </tr>  
                 
               "; 
            }
	     }
	       
	 
 if($_GET['listado_por']!='Facturas'){
 $totalSaldoFecha=$totalSaldoFecha +$sumaSaldo;
        $totalValorInicialFecha=$totalValorInicialFecha+$suma_valInicial;
        
	 	$output .="
              		<tfoot>
		<tr>
		<td colspan='3' style=' border-top: 1px solid #999;text-align: right;'><strong>TOTALES: <strong></td>
			".$filaFechaEmision3."
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($suma_valInicial, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($sumaSaldo, 2, '.', ' ')."</strong></td>
	    ".$filaAnticipo3."
		</tr>
		</tfoot></table>";
			$output .="<br><table style='border-style: solid;'>
              		<tbody>
		<tr>
		<td colspan='3' style=' border-top: 1px solid #999;text-align: right;'><strong>TOTAL FINAL: <strong></td>
			".$filaFechaEmision3."
		<td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($totalValorInicialFecha, 2, '.', ' ')."</strong></td>
	    <td style=' border-top: 1px solid #999;text-align: right;'><strong>$".number_format($totalSaldoFecha, 2, '.', ' ')."</strong></td>
	    ".$filaAnticipo3."
		</tr>
		</tbody></table>";  
}
 
	   
		echo $output;
	?>
