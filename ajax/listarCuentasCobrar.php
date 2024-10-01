<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

    $numeroRegistros= isset($_GET['criterio_mostrar'])?$_GET['criterio_mostrar']:10;
    
    //Start session
	session_start();
	include('../conexion2.php');
	include "../clases/paginado_basico.php";
	
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_tipo = $_SESSION["sesion_tipo"];
     $dominio = $_SERVER['SERVER_NAME'];
    $emision=$_SESSION["userpunto"];
    $colspan=4;
     
	$fecha_desde=$_GET['txtFechaDesde'];
    $fecha_hasta=$_GET['txtFechaHasta'];
    $cuentaTipo=$_GET['switch-four'];
    $estado=$_GET['switch-estado'];
    $numero = trim($_GET['txtNumeroCuentasCobrar']);
    
    $switch_tipo_fecha = isset($_GET['switch_tipo_fecha']) ?$_GET['switch_tipo_fecha']:'Vencimiento'; 
    // echo $cuentaTipo."</br>";
 
 $tipo_anticipo = $_GET['tipo_anticipo'];
 $sqlEmision="SELECT `id`, `id_est`, `tipoFacturacion`, `formato` FROM `emision` WHERE id=$emision";
 $resultEmision = mysql_query($sqlEmision);
 $formato='';
 while($rowE = mysql_fetch_array($resultEmision)){
     $formato = $rowE['formato'];
 }
 

  function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
    
      
    if($estado=='Canceladas'){
        $fehca='fecha_pago';
    }else{
        $fehca='fecha_vencimiento';
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
  cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
  cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
  cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
  cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado,
    cuentas_por_cobrar.`tipo_anticipo` AS cuentas_por_cobrar_tipo_anticipo,
  clientes.`id_cliente` AS clientes_id_cliente,
  clientes.`nombre` AS proveedores_nombre_comercial,
  clientes.`apellido` AS clientes_apellido,
  clientes.`direccion` AS clientes_direccion,
  clientes.`cedula` AS clientes_cedula,
  clientes.`prop_nombre` AS clientes_prop_nombre,
  clientes.`numero_casa` AS clientes_numero_casa,
  clientes.id_vendedor AS clientes_vendedor,
   tipo_anticipo.nombre_anticipo AS tipo_anticipo,
    ventas.fecha_venta AS ventas_fecha_venta,
     emision.codigo as emision_codigo,
    establecimientos.codigo as establecimientos_codigo
  
  FROM
  `clientes` clientes 
  INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente` 
     LEFT JOIN `tipo_anticipo` tipo_anticipo  ON tipo_anticipo.`id_tipo_anticipo` = cuentas_por_cobrar.`tipo_anticipo`
  LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
  LEFT JOIN emision ON ventas.codigo_lug = emision.id
LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun";

 if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_cobrar ON cuentas_por_cobrar.id_cuenta_por_cobrar = detalle_cuentas_por_cobrar.id_cuenta_por_cobrar ";
}

//   $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."'  
//   and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  
  
//   and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."'  "; 
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
// 		numero_factura
	if($numero!=''){
	     $sql.=" and cuentas_por_cobrar.`numero_factura`=$numero ";
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
  cuentas_por_cobrar.`tipo_anticipo` AS cuentas_por_cobrar_tipo_anticipo,
  proveedores.`id_proveedor` AS clientes_id_cliente,
  proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
  proveedores.`nombre` AS clientes_nombre,
  enlaces_compras.`nombre` AS  enlace_nombre,
  '' AS tipo_anticipo
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

//   $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."'
//   and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  
  
//   and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' "; 
  
    if($tipo_anticipo !='0' && ($dominio=='dcacorp.com.ec' || $dominio=='www.dcacorp.com.ec') ){
    $sql .=" AND cuentas_por_cobrar.`tipo_anticipo` ='".$tipo_anticipo."'  ";
}
        
    if (strlen($_GET['txtBuscarCuentasCobrar'])>0){
	$sql .= " and  CONCAT(proveedores.`nombre`, ' ', proveedores.`nombre_comercial`) like '%".$_GET['txtBuscarCuentasCobrar']."%' "; }
		 if($estado != 'Todos'){
  	if($estado == 'Pendientes'){
		    $sql.=" and cuentas_por_cobrar.`saldo`>0";
		}else{
		     $sql.=" and cuentas_por_cobrar.`saldo`=0 ";
		}
  }	
		if($numero!=''){
	     $sql.=" and cuentas_por_cobrar.`numero_factura`=$numero ";
	}
    if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_cobrar.`numero_factura`  ";
    }
    $sql.=" order by cuentas_por_cobrar.`id_proveedor`,cuentas_por_cobrar.`numero_factura`, cuentas_por_cobrar.`fecha_vencimiento`  ";
    
    // echo $sql;
    
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
    
    leads.`id` AS  clientes_id_cliente,
    leads.`name` AS proveedores_nombre_comercial
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
        // $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."'  
        // and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d')   <= '".$fecha_hasta."' "; 
        
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
	$sql .= " and  CONCAT(leads.`name`, ' ', leads.`apellido`)  like '%".$_GET['txtBuscarCuentasCobrar']."%' "; }

    if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_cobrar.`numero_factura`  ";
    }
$sql.=" order by cuentas_por_cobrar.`id_lead`,cuentas_por_cobrar.`numero_factura`, cuentas_por_cobrar.`fecha_vencimiento` ";

// echo $sql;
}else if ($cuentaTipo=='4'){
    
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
      empleados.`telefono` AS clientes_telefono
FROM
`empleados` empleados
     
     INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON empleados.`id_empleado` = cuentas_por_cobrar.`id_empleado` 
     
     ";
 if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_cobrar ON cuentas_por_cobrar.id_cuenta_por_cobrar = detalle_cuentas_por_cobrar.id_cuenta_por_cobrar ";
}
        // $sql .= " where cuentas_por_cobrar.`id_empresa`='".$sesion_id_empresa."' 
        // and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_cobrar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' "; 
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
        // echo $sql;
    }
	// echo $sql;
$result = $conexion->query($sql);
// $row = $result->fetch_assoc();
 $num_total_rows =$result->num_rows;
 $total_pages=0;  $total_saldo = 0;
 $total_valor = 0;
//  echo '|'.$num_total_rows.'|';
//  $num_total_rows = isset($num_total_rows)?:;
// echo 'page->'.$_GET["page"];
if ($num_total_rows > 0) {
    $page = false;
//  echo "PAGE==>".$_GET["page"];
    //examino la pagina a mostrar y el inicio del registro a mostrar
    if (isset($_GET["page"])) {
      $page = $_GET["page"];
    //   echo 'entro';
    }
 
    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * $numeroRegistros;
    }
    

    $total_pages = ceil($num_total_rows / $numeroRegistros);
    $sql.="  ASC LIMIT $start, ".$numeroRegistros;
    

    // echo $sql;
    $result2 = $conexion->query($sql);
	
?>


<input  name="total_a_pagar" id="total_a_pagar" type="hidden" />



    
    <?php
    
        $total_saldo = 0;
        $total_valor = 0;
        $total_abonado= 0 ;
        $sw1=0;
        
        $contadorTablas=0;// permite detectar cuando se crea una nueva tabla
        if ($result->num_rows > 0) {

            if($_GET['listado_por'] == 'Clientes' ){

          
        while ($row = $result2->fetch_assoc())
        {
        
        
    if ($sw1==0 or $id_Proveedor_ant<>$row['cuentas_por_cobrar_id_proveedor'])   {
  if($contadorTablas>0 ){
        ?>
  <tr>
            	<td colspan='2'><strong>TOTAL</strong></td>
            		<?php if($cuentaTipo==2){
                            ?>
                <td ><strong></strong></td>
                            <?php }
                            ?>
            	 <td class="text-end" >$ <strong><?php echo number_format($total_saldo, 2, '.', ','); ?></strong></td>
                <td class="text-end">$ <strong><?php echo number_format($total_abonado, 2, '.', ','); ?></strong></td>
                <td colspan='<?php echo $colspan; ?>'></td>
                <td class="text-end">$ <strong><?php echo number_format($total_valor, 2, '.', ','); ?></strong></td>
               
                </tr>
        <?php
  }
                $id_Proveedor_ant=$row['cuentas_por_cobrar_id_proveedor'];
                $sw1=1;
                $contadorTablas++;
                $total_abonado=0;
                $total_valor=0;
                $total_saldo=0;
                ?>
                
    <table class="table table-bordered table-condensed ">
         
         <thead >
         <tr >
            <td colspan="9">
                <h4><?=utf8_encode($row['proveedores_nombre_comercial']." ".$row['clientes_apellido'] )?></h4>
            
            </td>
            
            <td>
                <?php 
                	if($estado == 'Pendientes' || $estado == 'Todos'){
		   
		
		?>
                <a  onClick="pagarCtaCobrar((<?php echo $row['clientes_id_cliente']; ?>),2,(<?php echo $cuentaTipo; ?>))" class="btn btn-secondary">
                    
                   <i class="fa fa-check"></i> Cobrar
                   
                </a>
                <?php } ?>
            </td>
         </tr>
           
        </thead>  
    
    <tbody >
   
       
            <tr>
                <td><a onclick="javascript: listar_cuentas_por_cobrar();" title="Actualizar"><i class="fa fa-repeat" aria-hidden="true"></i></a></div>
                <td ><strong>Nro. Fac.</strong></td>
                <?php
                if ($cuentaTipo=='2'){
                    ?>
                     <td><strong>Fecha emisi&oacute;n factura</strong></td>
                    <?php
                }
                ?>
                <td><strong>Saldo</strong></td>
                <td><strong>Abonos</strong></td>
                <td><strong>Asiento</strong></td>
                <td><strong>Vencim.</strong></td>
                <td><strong>Pago.</strong></td>
                <!--<td><strong>Forma Pago</strong></td>-->
                <td><strong><i class="fa fa-check"></i></strong></td>
                <?php
                    if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
        ?>
                <td><strong>Comprobante Egreso</strong></td>
 <?php   }  ?>
                <td><strong>Valor Total </strong></td>
                
                  <?php
    if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
        ?>
                <td><strong>Tipo Anticipo</strong></td>
 <?php   }  ?>
                
            </tr>

            <?php   }  
        
                        
        $id_cuenta_por_pagar = $row['cuentas_por_cobrar_id_cuenta_por_cobrar'];           
        $cuentas_por_pagar_valor = $row['cuentas_por_cobrar_valor'];
        $cuentas_por_pagar_saldo = $row['cuentas_por_cobrar_saldo'];
        $total_valor = $total_valor + $cuentas_por_pagar_valor;
         $total_abonado= $total_abonado + ($row['cuentas_por_cobrar_valor']-$row['cuentas_por_cobrar_saldo']);
        $total_saldo = $total_saldo + $cuentas_por_pagar_saldo;

      ?>
       
         
                <tr>
                    
                    <td>
                     <?php  if( $sesion_tipo==='Administrador'){?>
                    <a onclick="javascript: eliminarCuentaPorCobrar(<?=$row['cuentas_por_cobrar_id_cuenta_por_cobrar']?>,3);" title="Eliminar cuenta por cobrar" class="btn fa fa-trash-alt"></a>
                    <?php  }?>
                  </td>
                        
                        <?php if($cuentaTipo==2){
                            ?>
                             <td><?=$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_cobrar_numero_factura'])?></td>
      
                            <?php
                        }else{
                            ?>
                            <td><?=ceros($row['cuentas_por_cobrar_numero_factura'])?></td>
       
                            <?php
                        }
                        ?>
                  <?php
                if ($cuentaTipo=='2'){
                    ?>
                       <td class="text-end"><?=$row['ventas_fecha_venta']?></td> 
                    <?php
                }
                ?>
              
                    <td class="text-end">$ <?=number_format($row['cuentas_por_cobrar_saldo'], 2, '.', ',')?></td> 
                    <td class="text-end">$ <?=number_format($row['cuentas_por_cobrar_valor']-$row['cuentas_por_cobrar_saldo'], 2, '.', ',')?></td>
                    
                    <td>
                                  <?php
    if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
        ?>
         <a onclick="imprimirAnticipo('<?=$row["cuentas_por_cobrar_numero_asiento"]?>');" >
                        <?=$row['cuentas_por_cobrar_numero_asiento']?> </a> 
        <?php
    }else{
        ?>
         <a href="asientosContables.php?numero_asientox=<?php echo $row['cuentas_por_cobrar_numero_asiento']?>" >
                        <?=$row['cuentas_por_cobrar_numero_asiento']?> </a> 
        <?php
    }
        ?>
                       
                    </td>
                    
                    
                    <td><?=$row['cuentas_por_cobrar_fecha_vencimiento']?></td>
                    <td><?=$row['cuentas_por_cobrar_fecha_pago']?></td>
                  
                    
                    
                    <td>
                            <?php 
                            $cuentas_por_cobrar_valor = $row['cuentas_por_cobrar_valor'];
                            if( $row['cuentas_por_cobrar_saldo']>0){?>
                    <input type="checkbox" name="checkCobrar[]" class="form-check-input" 
                      data-cliente="<?=$row['cuentas_por_cobrar_id_proveedor']?>" data-saldo="<?=$row['cuentas_por_cobrar_saldo']?>"
                     onClick="sumar(this)"
                    
                    onkeyup=" sumar(this)"
                
                    
                    value="<?=$row['cuentas_por_cobrar_id_cuenta_por_cobrar']?>" />
                     <?php  }?>
                    </td>
                           <?php
                    if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
                        if($row["cuentas_por_cobrar_numero_asiento"]!=''){
                            ?>
                             <td> <a onclick="imprimirAnticipo('<?=$row["cuentas_por_cobrar_numero_asiento"]?>');"  class="btn btn-info">
                    
                   <i class="fa fa-check" aria-hidden="true"></i> PDF
                   
                </a></td>
                            <?php
                        }else{
                            ?>
                             <td> </td>
                            <?php
                        }
        ?>
            
 <?php   }  ?>
                    <td class="text-end">$ <?=number_format($cuentas_por_cobrar_valor, 2, '.', ',')?></td>
                    <?php
                     
            
    if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
     
        ?>
                    <td onclick="imprimirAnticipo('<?=$row["cuentas_por_cobrar_numero_asiento"]?>');"><?=$row['tipo_anticipo']?></td>
         <?php } ?>
  
            
                </tr> 
                <?php  if ($sw1==0 or $id_Proveedor_ant<>$row['cuentas_por_cobrar_id_proveedor']) {  ?>   </tbody> </table>   <?php }   }   }else{
                
                while ($row = $result2->fetch_assoc())
                {
                        $total_valor=0;
                        $total_saldo=0;
                        $total_abonado=0;
                        ?>
            <table class="table table-bordered table-condensed">
                 <thead >
                 <tr >
                    <td colspan="6"><h4><?=$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_cobrar_numero_factura'])."   ".$row['clientes_apellido']?></h4></td>
                    <td></td>
                 </tr>
                </thead>  
            <tbody >
                    <tr>
                        <td><a onclick="javascript: listar_cuentas_por_cobrar();" title="Actualizar"><i class="fa fa-repeat" aria-hidden="true"></i></a></div>
                        <td><strong>Nro. Fac.</strong></td>
                         <?php if($cuentaTipo==2){
                             ?>
                              <td><strong> Emision Factura</strong></td>
                             <?php
                         }
                         ?>
                        <td><strong>Saldo</strong></td>
                        <td><strong>Abonos</strong></td>
                         <td><strong>Asientos</strong></td>
                        <td><strong>Pago.</strong></td>
                        <td><strong>Valor </strong></td>
                    </tr>
                    <?php        
                $id_cuenta_por_pagar = $row['cuentas_por_cobrar_id_cuenta_por_cobrar'];           
                $cuentas_por_pagar_valor = $row['cuentas_por_cobrar_valor'];
                $cuentas_por_pagar_saldo = $row['cuentas_por_cobrar_saldo'];
                $total_valor = $total_valor + $cuentas_por_pagar_valor;
                $total_saldo = $total_saldo + $cuentas_por_pagar_saldo;
                
                 $sqlDetalleCC="SELECT `id_detalle_cuentas_por_cobrar`,`valor`, `fecha_pago`,`id_cuenta_por_cobrar`,numero_asiento FROM `detalle_cuentas_por_cobrar` WHERE id_cuenta_por_cobrar=$id_cuenta_por_pagar ";
                $resultDetalleCC = mysqli_query($conexion,$sqlDetalleCC) ;
                $saldo_detalle= $row['cuentas_por_cobrar_valor'];
                $totalDetalleValor= 0;
                $totalDetalleSaldo=0;
                while( $rowDetalle = mysqli_fetch_array( $resultDetalleCC )){
                   
                    $saldo_detalle =$saldo_detalle - $rowDetalle['valor'];
                    $totalDetalleSaldo = $saldo_detalle;
                    $totalDetalleValor = $totalDetalleValor+ $rowDetalle['valor'];
                    ?>
                     <tr>
                            <td><?php  if( $sesion_tipo==='Administrador'){?>
                            <a onclick="javascript: eliminarCuentaPorCobrar(<?=$rowDetalle['id_cuenta_por_cobrar']?>,3);" title="Eliminar cuenta por cobrar"> <span class="btn fa fa-trash-alt"></span></a>
                            <?php  }?></td>
                                
                                <?php if($cuentaTipo==2){
                                    
                                    if($formato=='2'){
                                        $direccion="reportes/rptAbonosCuentasCobrar.php?id=".$row['clientes_id_cliente']."&switch-four=2&checkCobrar=".$row['cuentas_por_cobrar_id_cuenta_por_cobrar']."&idDetalles=".$rowDetalle['id_detalle_cuentas_por_cobrar']."&abonado=".$rowDetalle['valor'];
                                    
                                        
                                    }else{
                                        
                                         $direccion="reportes/rptAbonosCuentasCobrar_A4.php?id=".$row['clientes_id_cliente']."&switch-four=2&checkCobrar=".$row['cuentas_por_cobrar_id_cuenta_por_cobrar']."&idDetalles=".$rowDetalle['id_detalle_cuentas_por_cobrar']."&abonado=".$rowDetalle['valor'];
                                    
                                        
                                    }
                                  
                                    ?>
                                    
                                         <td><a href="<?php echo $direccion; ?>" target="_blank"><?=$row['establecimientos_codigo']."--".$row['emision_codigo']."--".ceros($row['cuentas_por_cobrar_numero_factura'])?> </a></td>
              
                                   
                                     <td class="text-end"><?=$row['ventas_fecha_venta']?></td>
                                    <?php
                                }else{
                                    ?>
                                    <td  > <?=ceros($row['cuentas_por_cobrar_numero_factura'])?>  </td>
               
                                    <?php
                                }
                                ?>
                            
                            <td><?=$saldo_detalle?></td>
                            <td><?=$rowDetalle['valor']?></td> 
                            <td> <a target="_blank" href="reportes/rptComprobanteDiario.php?txtAsientoNumero=<?php echo $rowDetalle['numero_asiento']?>" ><?=$rowDetalle['numero_asiento']?></a></td> 
                            <td><?=$rowDetalle['fecha_pago']?></td>
                            <td><?=$rowDetalle['valor']?></td>
                        </tr> 
                   
                          <?php }//fin while detalle 
                          ?>
                            <tr>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                             <?php if($cuentaTipo==2){
                             ?>
                              <td></td>
                             <?php
                         }
                         ?>
                            <td><strong><?php echo $totalDetalleSaldo; ?></strong></td>
                            <td><strong><?php echo $totalDetalleValor; ?></strong></td>
                            </tr>   
                            </tbody>   
                            </table>    
                          <?php
                }//fin while
                
             }//fin else  
            }
            }//fin total_num_rows ?>
 
 <?php
 if($_GET['listado_por']!='Facturas'){
?>
 <tr>
     
       	<td colspan='2'><strong>TOTAL</strong></td>
       	<?php if($cuentaTipo==2){
                            ?>
                            	<td ><strong></strong></td>
                            <?php }
                            ?>
            	 <td class="text-end">$ <strong><?php echo number_format($total_saldo, 2, '.', ','); ?></strong></td>
                <td class="text-end">$ <strong><?php echo number_format($total_abonado, 2, '.', ','); ?></strong></td>
                 <td colspan='<?php echo $colspan; ?>'></td>
                 <td class="text-end">$ <strong><?php echo number_format($total_valor, 2, '.', ','); ?></strong></td>
        </tr>   
        </tbody>   
        </table>    
<?php
 }
 ?>

       
<?php
     echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_cobrar('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    }
 
    $half = floor(15 / 2); // Mitad de los botones que deseas mostrar
    $start = max(1, $page - $half);
    $end = min($total_pages, $start + 15 - 1);

    if ($end - $start < 15) {
        $start = max(1, $end - 15 + 1);
    }

    for ($i = $start; $i <= $end; $i++) {
        if ($page == $i) {
            echo '<li class="page-item active"><a class="page-link" href="#">'.$i.'</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_cobrar('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_cobrar('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';


?>


