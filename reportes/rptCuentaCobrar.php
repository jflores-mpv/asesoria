<?php
//ob_end_clean();
//Start session

session_start();
 $dominio = $_SERVER['SERVER_NAME'];
//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('a4','Landscape');
$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$datacreador = array (
                    'Title'=>'Libro Mayor',
                    'Subject'=>'detalle del Libro Mayor',
                    'Author'=>'25 de junio',
                    'Producer'=>'Macarena Lalama'
                    );
$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));
 $tipo_anticipo = $_GET['tipo_anticipo'];
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $numero = trim($_GET['txtNumeroCuentasCobrar']);
     $switch_tipo_fecha = isset($_GET['switch_tipo_fecha']) ?$_GET['switch_tipo_fecha']:'Vencimiento'; 
     
 function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
// $id_cuenta_por_cobrar = $_GET["id_cuenta_por_cobrar"];
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
        $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
        $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
        $pdf->ezText("<b>CUENTAS POR COBRAR".$nombreVendedor."</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
      
        $fecha_desde=$_GET['txtFechaDesde'];
        $fecha_hasta=$_GET['txtFechaHasta'];
          $cuentaTipo=$_GET['switch-four'];
           $estado=$_GET['switch-estado'];
       
          if($estado=='Canceladas'){
               $fehca='fecha_pago';
              
          }else{
             $fehca='fecha_vencimiento';
          }

           $txttit='';
if ($cuentaTipo=='2'){
    
    $sql = "SELECT
    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS cuentas_por_cobrar_id_cuenta_por_cobrar,
    cuentas_por_cobrar.`numero_factura` AS cuentas_por_cobrar_numero_factura,
    cuentas_por_cobrar.`referencia` AS cuentas_por_cobrar_referencia,
    cuentas_por_cobrar.`valor` AS cuentas_por_cobrar_valor,
    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
    cuentas_por_cobrar.`numero_asiento` AS cuentas_por_cobrar_numero_asiento,
     DATE_FORMAT(
        cuentas_por_cobrar.`fecha_vencimiento`,
        '%Y-%m-%d'
    )
     AS cuentas_por_cobrar_fecha_vencimiento,
     DATE_FORMAT(
        cuentas_por_cobrar.`fecha_pago`,
        '%Y-%m-%d'
    ) AS cuentas_por_cobrar_fecha_pago,
    cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_proveedor,
    cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
    cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
      cuentas_por_cobrar.`tipo_anticipo` AS cuentas_por_cobrar_tipo_anticipo,
    clientes.`id_cliente` AS clientes_id_cliente,
    clientes.`nombre` AS proveedores_nombre_comercial,
    clientes.`apellido` AS clientes_apellido,
    clientes.`direccion` AS clientes_direccion,
    clientes.`cedula` AS proveedores_ruc,
    clientes.`prop_nombre` AS clientes_prop_nombre,
      clientes.`telefono` AS proveedores_telefono,
    clientes.`numero_casa` AS clientes_numero_casa,
    cuentas_por_cobrar.`documento_numero` AS cuentas_por_cobrar_documento_numero,
     DATE_FORMAT(
        ventas.fecha_venta,
        '%Y-%m-%d'
    ) AS ventas_fecha_venta,
     emision.codigo as emision_codigo,
       tipo_anticipo.nombre_anticipo AS tipo_anticipo,
    establecimientos.codigo as establecimientos_codigo
    
    FROM
    `clientes` clientes 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente` 
    LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
  LEFT JOIN emision ON ventas.codigo_lug = emision.id
   LEFT JOIN `tipo_anticipo` tipo_anticipo  ON tipo_anticipo.`id_tipo_anticipo` = cuentas_por_cobrar.`tipo_anticipo`
LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun ";
 if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_cobrar ON cuentas_por_cobrar.id_cuenta_por_cobrar = detalle_cuentas_por_cobrar.id_cuenta_por_cobrar ";
}
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
     DATE_FORMAT(
        cuentas_por_cobrar.`fecha_vencimiento`,
        '%Y-%m-%d'
    )
     AS cuentas_por_cobrar_fecha_vencimiento,
     DATE_FORMAT(
        cuentas_por_cobrar.`fecha_pago`,
        '%Y-%m-%d'
    ) AS cuentas_por_cobrar_fecha_pago,
    cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
    cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
      cuentas_por_cobrar.`tipo_anticipo` AS cuentas_por_cobrar_tipo_anticipo,
    cuentas_por_cobrar.`documento_numero` AS cuentas_por_cobrar_documento_numero,
    proveedores.`id_proveedor` AS clientes_id_cliente,
    proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
    proveedores.`telefono` AS proveedores_telefono,
    proveedores.`nombre` AS clientes_nombre,
    proveedores.`ruc` AS proveedores_ruc,
    '' AS tipo_anticipo,
    '' AS  enlace_nombre
    FROM
    `proveedores` proveedores 
    INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON proveedores.`id_proveedor` = cuentas_por_cobrar.`id_proveedor`
    LEFT JOIN `enlaces_compras` enlaces_compras ON enlaces_compras.`id` = cuentas_por_cobrar.`id_forma_pago` ";
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
    
      if($tipo_anticipo !='0' && ($dominio=='dcacorp.com.ec' || $dominio=='www.dcacorp.com.ec') ){
    $sql .=" AND cuentas_por_cobrar.`tipo_anticipo` ='".$tipo_anticipo."'  ";
}

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
     if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_cobrar.`numero_factura`  ";
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
       DATE_FORMAT(
        cuentas_por_cobrar.`fecha_vencimiento`,
        '%Y-%m-%d'
    )
     AS cuentas_por_cobrar_fecha_vencimiento,
     DATE_FORMAT(
        cuentas_por_cobrar.`fecha_pago`,
        '%Y-%m-%d'
    ) AS cuentas_por_cobrar_fecha_pago,
        cuentas_por_cobrar.`tipo_anticipo` AS cuentas_por_cobrar_tipo_anticipo,
     
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
    DATE_FORMAT(
        cuentas_por_cobrar.`fecha_vencimiento`,
        '%Y-%m-%d'
    )
     AS cuentas_por_cobrar_fecha_vencimiento,
     DATE_FORMAT(
        cuentas_por_cobrar.`fecha_pago`,
        '%Y-%m-%d'
    ) AS cuentas_por_cobrar_fecha_pago,
      cuentas_por_cobrar.`tipo_anticipo` AS cuentas_por_cobrar_tipo_anticipo,
   
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
  
 if($sesion_id_empresa==116){
    //  echo $sql;
 }

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
       
        // echo $sql."***    ".$numero_filas;
        // exit;
        $data2 = array(
        );
        
        $cambianteP='';
        $suma_valInicial=0;
        $sumaSaldo=0;
        
    while($row = mysql_fetch_array($result)){ 
             $fecha_emision_factura = $row['ventas_fecha_venta'];
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
            $cuentas_por_cobrar_tipo_anticipo = $row['tipo_anticipo'];
            if($cuentas_por_cobrar_id_proveedor != $cambianteP && $numero!=1){
                //   echo 'entro';
                //   echo '|';
                //   echo $cambianteP;
                //   echo '|';
                    $titles3 = array(
                        'numero_factura'=>'TOTALES: ',
                        'valor_inicial'=>'$ '.number_format($suma_valInicial, 2, '.', ' '),      
                        'saldo'=>'$ '.number_format($sumaSaldo, 2, '.', ' '),
                        );
                        $pdf->ezTable($data2, $titles2, '', $options2);
              
                        $pdf->ezText($txttit, 0.5);
                        $pdf->ezTable($data1, $titles1, '', $options1);
                        $pdf->ezText($txttit, 2);
                        
                        
                        $pdf->ezTable($data3, $titles3, '', $options3);
                          
                        //$pdf->ezTable($data3, $titles3, '', $options3);
                        $pdf->ezText("\n\n\n", 10);
                        unset($data1); // $foo is gone $foo = array();
                        $data1= array();
                         $suma_valInicial=0;
                            $sumaSaldo=0;
              }
              if($cuentas_por_cobrar_id_proveedor != $cambianteP){
                  
                $titles2 = array(
                    'cedula' => 'Cedula: '.$proveedores_cedula,
                    'nombre' => 'Nombre: '.utf8_decode($proveedores_nombre_apellido),
                    'telefono' => 'Tel: '.($proveedores_telefono)
    
                    );    
           }
           if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
                 if ($cuentaTipo=='2'){
                      $data1[] = array(
                    'numero_factura'=>$cuentas_por_cobrar_numero_factura,
                      'fecha_emision_factura'=>$fecha_emision_factura,
                    'fecha_emision'=>$cuentas_por_cobrar_fecha_vencimiento,
                    'fecha_pago'=>$cuentas_por_cobrar_fecha_pago,
                     'tipo_anticipo'=>$cuentas_por_cobrar_tipo_anticipo,
                    'valor_inicial'=>'$ '.number_format($cuentas_por_cobrar_valor, 2, '.', ' '),
                    'saldo'=>'$ '.number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')
                    );
                 }else{
                      $data1[] = array(
                    'numero_factura'=>$cuentas_por_cobrar_numero_factura,
                    'fecha_emision'=>$cuentas_por_cobrar_fecha_vencimiento,
                    'fecha_pago'=>$cuentas_por_cobrar_fecha_pago,
                     'tipo_anticipo'=>$cuentas_por_cobrar_tipo_anticipo,
                    'valor_inicial'=>'$ '.number_format($cuentas_por_cobrar_valor, 2, '.', ' '),
                    'saldo'=>'$ '.number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')
                    );
                 }
                     
                    
                  }else{
                      if ($cuentaTipo=='2'){
                          $data1[] = array(
                    'numero_factura'=>$cuentas_por_cobrar_numero_factura,
                    'fecha_emision_factura'=>$fecha_emision_factura,
                    'fecha_emision'=>$cuentas_por_cobrar_fecha_vencimiento,
                    'fecha_pago'=>$cuentas_por_cobrar_fecha_pago,
                    'valor_inicial'=>'$ '.number_format($cuentas_por_cobrar_valor, 2, '.', ' '),
                    'saldo'=>'$ '.number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')
                    );
                      }else{
                          $data1[] = array(
                    'numero_factura'=>$cuentas_por_cobrar_numero_factura,
                    
                    'fecha_emision'=>$cuentas_por_cobrar_fecha_vencimiento,
                    'fecha_pago'=>$cuentas_por_cobrar_fecha_pago,
                    'valor_inicial'=>'$ '.number_format($cuentas_por_cobrar_valor, 2, '.', ' '),
                    'saldo'=>'$ '.number_format($cuentas_por_cobrar_saldo, 2, '.', ' ')
                    );
                      }
                          
                  }
                      
                
 {
       if ($cuentaTipo=='2'){
            $titles1 = array(
                'numero_factura' => '<b>Nro. Fac</b>',
                'fecha_emision_factura' => '<b>'.utf8_decode("Emision factura").'</b>',             
                'fecha_emision' => '<b>'.utf8_decode('Fecha Vencimiento').'</b>',
                'fecha_pago' => '<b>'.utf8_decode('Fecha Pago').'</b>',
                'valor_inicial' => '<b>Val. Inicial</b>',
                'saldo' => '<b>Saldo</b>'
                );
       }else{
            $titles1 = array(
                'numero_factura' => '<b>Nro. Fac</b>',
                            
                'fecha_emision' => '<b>'.utf8_decode('Fecha Vencimiento').'</b>',
                'fecha_pago' => '<b>'.utf8_decode('Fecha Pago').'</b>',
                'valor_inicial' => '<b>Val. Inicial</b>',
                'saldo' => '<b>Saldo</b>'
                );
       }
     
  }
                
               

            
            $data3 = array(
            );

            $suma_valInicial =$suma_valInicial + $cuentas_por_cobrar_valor;
            $sumaSaldo= $sumaSaldo + $cuentas_por_cobrar_saldo;
            if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
                 if ($cuentaTipo=='2'){ 
                     $options1 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>750,
                        'cols'=>array(
                            'numero_factura'=>array('justification'=>'left','width'=>120),
                             'fecha_emision_factura'=>array('justification'=>'left','width'=>80,'fontSize'=>3),
                            'fecha_emision'=>array('justification'=>'left','width'=>80),
                            'fecha_pago'=>array('justification'=>'right','width'=>80),
                            'tipo_anticipo'=>array('justification'=>'right','width'=>130),
                            'valor_inicial'=>array('justification'=>'right','width'=>130),
                            'saldo'=>array('justification'=>'right','width'=>130)
                             )
                        );
                 }else{
                     $options1 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>750,
                        'cols'=>array(
                            'numero_factura'=>array('justification'=>'left','width'=>120),
                            
                            'fecha_emision'=>array('justification'=>'left','width'=>80),
                            'fecha_pago'=>array('justification'=>'right','width'=>80),
                            'tipo_anticipo'=>array('justification'=>'right','width'=>210),
                            'valor_inicial'=>array('justification'=>'right','width'=>130),
                            'saldo'=>array('justification'=>'right','width'=>130)
                             )
                        );
                 }
          
      }else{
           if ($cuentaTipo=='2'){
                    $options1 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                            'numero_factura'=>array('justification'=>'left','width'=>250),
                            'fecha_emision_factura'=>array('justification'=>'left','width'=>80),
                            'fecha_emision'=>array('justification'=>'left','width'=>80),
                            'fecha_pago'=>array('justification'=>'right','width'=>80),
                            'valor_inicial'=>array('justification'=>'right','width'=>130),
                            'saldo'=>array('justification'=>'right','width'=>130)
                             )
                        );
           }else{
                 $options1 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                            'numero_factura'=>array('justification'=>'left','width'=>330),
                          
                            'fecha_emision'=>array('justification'=>'left','width'=>80),
                            'fecha_pago'=>array('justification'=>'right','width'=>80),
                            'valor_inicial'=>array('justification'=>'right','width'=>130),
                            'saldo'=>array('justification'=>'right','width'=>130)
                             )
                        );   
           }
     
      }
            

            $options2 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>750,
                        'cols'=>array(
                                    'cedula'=>array('justification'=>'left','width'=>240),
                                    'nombre'=>array('justification'=>'left','width'=>260),
                                    'telefono'=>array('justification'=>'left','width'=>250),

                                     )
                        );
            $options3 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>750,
                        'cols'=>array(
                            'numero_factura'=>array('justification'=>'right','width'=>490),
                            'valor_inicial'=>array('justification'=>'right','width'=>130),                            
                            'saldo'=>array('justification'=>'right','width'=>130)

                                     )
                        );

                        $cambianteP = $cuentas_por_cobrar_id_proveedor;
                         $posy = $pdf->y;
     
        if ($posy<70){
           //  echo 'si debe ejecutar';
            $pdf->ezNewPage();
           //  $pdf->ezNewPage();
        }
        //  exit;
          
        $titles4 = array(

            'numero_factura'=>'TOTALES: ',
            'valor_inicial'=>'$ '.number_format($suma_valInicial, 2, '.', ' '),                
            'saldo'=>'$ '.number_format($sumaSaldo, 2, '.', ' '),

            );

       
        
        }else{
            $total_valor=0;
            $total_saldo=0;
            $total_abonado=0;
            $options2 = array(
                'shadeCol'=>array(0.9,0.9,0.9),
                'xOrientation'=>'center',
                'width'=>750,
                'cols'=>array(
                    'numfactura'=>array('justification'=>'left','width'=>750),
                )
            );
            $data2 = array();
           
     if($cuentaTipo==2){
         $options1 = array(
            'shadeCol'=>array(0.9,0.9,0.9),
            'xOrientation'=>'center',
            'width'=>750,
            'cols'=>array(
                'numero_factura'=>array('justification'=>'left','width'=>200),
                'fecha_emision_factura'=>array('justification'=>'left','width'=>100),
                'saldo'=>array('justification'=>'left','width'=>70),
                'abonos'=>array('justification'=>'right','width'=>70),
                'asientos'=>array('justification'=>'right','width'=>70),
                'pago'=>array('justification'=>'right','width'=>70),
                'valor'=>array('justification'=>'right','width'=>70)
                )
        );
         $titles1 = array(
                'numero_factura' => '<b>Nro. Fac</b>',
                'fecha_emision_factura' => '<b>Emision Factura</b>',
                'saldo' => '<b>Saldo</b>',
                'abonos' => '<b>Abonos</b>',
                'asientos' => '<b>Asientos</b>',
                'pago' => '<b>Pago</b>',
                'valor' => '<b>Valor</b>'
                );
     }else{
         $options1 = array(
            'shadeCol'=>array(0.9,0.9,0.9),
            'xOrientation'=>'center',
            'width'=>750,
            'cols'=>array(
                'numero_factura'=>array('justification'=>'left','width'=>300),
                'saldo'=>array('justification'=>'left','width'=>70),
                'abonos'=>array('justification'=>'right','width'=>70),
                'asientos'=>array('justification'=>'right','width'=>70),
                'pago'=>array('justification'=>'right','width'=>70),
                'valor'=>array('justification'=>'right','width'=>70)
                )
        );
         $titles1 = array(
                'numero_factura' => '<b>Nro. Fac</b>',
                'saldo' => '<b>Saldo</b>',
                'abonos' => '<b>Abonos</b>',
                'asientos' => '<b>Asientos</b>',
                'pago' => '<b>Pago</b>',
                'valor' => '<b>Valor</b>'
                );
     }
        
        $options3 = array(
            'shadeCol'=>array(0.9,0.9,0.9),
            'xOrientation'=>'center',
            'width'=>750,
            'cols'=>array(
                'total'=>array('justification'=>'left','width'=>300),
                'saldo'=>array('justification'=>'left','width'=>70),
                'abonos'=>array('justification'=>'right','width'=>70),
                'vacio'=>array('justification'=>'right','width'=>210)
                )
        );
        $data3 = array();
        $id_cuenta_por_pagar = $row['cuentas_por_cobrar_id_cuenta_por_cobrar'];
        $cuentas_por_pagar_valor = $row['cuentas_por_cobrar_valor'];
        $cuentas_por_pagar_saldo = $row['cuentas_por_cobrar_saldo'];
        $total_valor = $total_valor + $cuentas_por_pagar_valor;
        $total_saldo = $total_saldo + $cuentas_por_pagar_saldo;    
        $sqlDetalleCC="SELECT `id_detalle_cuentas_por_cobrar`,`valor`,  DATE_FORMAT(fecha_pago,'%Y-%m-%d') as fecha_pago,`id_cuenta_por_cobrar`,numero_asiento FROM `detalle_cuentas_por_cobrar` WHERE id_cuenta_por_cobrar=$id_cuenta_por_pagar ";
        $resultDetalleCC = mysql_query($sqlDetalleCC) ;
        $saldo_detalle= $row['cuentas_por_cobrar_valor'];
        $totalDetalleValor= 0;
        $totalDetalleSaldo=0;
         $data1='';
        while( $rowDetalle = mysql_fetch_array( $resultDetalleCC )){
            $saldo_detalle =$saldo_detalle - $rowDetalle['valor'];
            $totalDetalleSaldo = $saldo_detalle;
            $totalDetalleValor = $totalDetalleValor+ $rowDetalle['valor']; 
            $numFac='';
            if($cuentaTipo==2){
                $numFac=$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_cobrar_numero_factura']);
                 $data1[] = array(
                    'numero_factura'=>$numFac,
                    'fecha_emision_factura'=>$fecha_emision_factura,
                    'saldo'=>'$ '.number_format($saldo_detalle, 2, '.', ' '),
                    'abonos'=>'$ '.number_format($rowDetalle['valor'], 2, '.', ' '),
                    'asientos'=>$rowDetalle['numero_asiento'],
                    'pago'=>$rowDetalle['fecha_pago'],
                    'valor'=>'$ '.number_format($rowDetalle['valor'], 2, '.', ' ')
            );
            }else{
                $numFac=ceros($row['cuentas_por_cobrar_numero_factura']);
                 $data1[] = array(
                    'numero_factura'=>$numFac,
                    'saldo'=>'$ '.number_format($saldo_detalle, 2, '.', ' '),
                    'abonos'=>'$ '.number_format($rowDetalle['valor'], 2, '.', ' '),
                    'asientos'=>$rowDetalle['numero_asiento'],
                    'pago'=>$rowDetalle['fecha_pago'],
                    'valor'=>'$ '.number_format($rowDetalle['valor'], 2, '.', ' ')
            );
            }
           
        }
        $titles3 = array(
            'total'=>'TOTALES: ',
            'saldo'=>'$ '.number_format($totalDetalleSaldo, 2, '.', ' '),  
            'abonos'=>'$ '.number_format($totalDetalleValor, 2, '.', ' '),
            'vacio'=>''
            );
            $txttit='';
        $pdf->ezTable($data2, $titles2, '', $options2);
        $pdf->ezText($txttit, 0.5);
        $pdf->ezTable($data1, $titles1, '', $options1);
        $pdf->ezText($txttit, 2);
        $pdf->ezTable($data3, $titles3, '', $options3); 
         $txttit.= "";
        $pdf->ezText($txttit, 12);
        }
    }
    
        if($_GET['listado_por'] == 'Clientes' ){
             $pdf->ezTable($data2, $titles2, '', $options2);
        $pdf->ezText($txttit, 0.5);
        $pdf->ezTable($data1, $titles1, '', $options1);
        $pdf->ezText($txttit, 2);
            $pdf->ezTable($data3, $titles4, '', $options3);
        }
        
        
        

        $txttit.= "";
        $pdf->ezText($txttit, 12);

        $pdf->ezStartPageNumbers(550, 80, 10);

        $pdf->ezStream();

        $pdf->Output('cuentas_por_cobrar.pdf', 'D');

?>