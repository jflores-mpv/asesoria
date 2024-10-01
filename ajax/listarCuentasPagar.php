
<?php

        //Start session
	session_start();
    
	include "../conexion.php";
	  include('../conexion2.php');
	include "../clases/paginado_basico.php";
    $numeroRegistros= isset($_GET['criterio_mostrar'])?$_GET['criterio_mostrar']:10;
      $dominio = $_SERVER['SERVER_NAME'];
     $fecha_desde =  $_GET['txtFechaDesde'];
        $fecha_hasta = $_GET['txtFechaHasta'];
        ;
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $tipo_anticipo = $_GET['tipo_anticipo'];
    $cuentaTipo=$_GET['switch-four'];
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
     $switch_tipo_fecha = isset($_GET['switch_tipo_fecha']) ?$_GET['switch_tipo_fecha']:'Vencimiento'; 
     $estado=$_GET['switch-estado'];
     $fecha_estado='';
      if($estado=='Canceladas'){
            $fecha_estado='fecha_pago';
              
        }else{
            $fecha_estado='fecha_vencimiento';
        }
if ($cuentaTipo=='1'){
    
    $sql = "SELECT
        cuentas_por_pagar.`id_cuenta_por_pagar` AS cuentas_por_pagar_id_cuenta_por_pagar,
        cuentas_por_pagar.`numero_compra` AS cuentas_por_pagar_numero_compra,
        cuentas_por_pagar.`referencia` AS cuentas_por_pagar_referencia,
        cuentas_por_pagar.`tipo_documento` AS cuentas_por_pagar_tipo_documento,
        cuentas_por_pagar.`valor` AS cuentas_por_pagar_valor,
        cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
        cuentas_por_pagar.`numero_asiento` AS cuentas_por_pagar_numero_asiento,
        cuentas_por_pagar.`fecha_vencimiento` AS cuentas_por_pagar_fecha_vencimiento,
        cuentas_por_pagar.`fecha_pago` AS cuentas_por_pagar_fecha_pago,
        cuentas_por_pagar.`id_proveedor` AS cuentas_por_pagar_id_proveedor,
        cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
        cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
        cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra,
        cuentas_por_pagar.`tipo_anticipo` AS cuentas_por_pagar_tipo_anticipo,
        proveedores.`id_proveedor` AS proveedores_id_proveedor,
        proveedores.`nombre` AS proveedores_nombre,
        proveedores.`direccion` AS proveedores_direccion,
        proveedores.`ruc` AS proveedores_ruc,
        proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
        compras.subTotalInvenarios AS compras_subTotalInvenarios,
        compras.subTotalProveeduria AS compras_subTotalProveeduria,
        compras.subtotalServicios AS compras_subtotalServicios,
        compras.numSerie AS compras_numSerie,
        compras.txtEmision AS compras_txtEmision,
        compras.txtNum AS compras_txtNum
FROM
     `proveedores` proveedores 
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON proveedores.`id_proveedor` = cuentas_por_pagar.`id_proveedor`
      LEFT JOIN compras	ON compras.id_compra = cuentas_por_pagar.id_compra ";
       if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_pagar ON cuentas_por_pagar.id_cuenta_por_pagar = detalle_cuentas_por_pagar.id_cuenta_por_pagar ";
}

        $sql .= " where cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  ";       
            if($tipo_anticipo !='0' && ($dominio=='dcacorp.com.ec' || $dominio=='www.dcacorp.com.ec') ){
    $sql .=" AND cuentas_por_pagar.`tipo_anticipo` ='".$tipo_anticipo."'  ";
}
        if (isset($_GET['txtBuscarCuentasPagar'])){
		$sql .= " and proveedores.`nombre` like '%".$_GET['txtBuscarCuentasPagar']."%' "; }
		
		if($estado != 'Todos'){
    		if($estado == 'Pendientes'){
    		    $sql.=" and cuentas_por_pagar.`saldo`>0";
    		}else{
    		     $sql.=" and cuentas_por_pagar.`saldo`=0 ";
    		}
		}
	 if($switch_tipo_fecha == 'Vencimiento'){
             if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
    		     		     if($estado == 'Todos'){
         $sql .= " AND  cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
        
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
           $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') 
            <= '".$fecha_hasta."' ";
    }
           
            }
     }else{
          $sql.="  and DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') 
            <= '".$fecha_hasta."' ";
     }
		
     if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_pagar.`numero_compra`  ";
    }
      $sql.=" ORDER BY  cuentas_por_pagar.`id_proveedor`  DESC  ";
} else if ($cuentaTipo=='2'){
    
    $sql = "SELECT
     cuentas_por_pagar.`id_cuenta_por_pagar` AS cuentas_por_pagar_id_cuenta_por_pagar,
     cuentas_por_pagar.`numero_compra` AS cuentas_por_pagar_numero_compra,
     cuentas_por_pagar.`referencia` AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.`tipo_documento` AS cuentas_por_pagar_tipo_documento,
     cuentas_por_pagar.`valor` AS cuentas_por_pagar_valor,
     cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.`tipo_anticipo` AS cuentas_por_pagar_tipo_anticipo,
     cuentas_por_pagar.`numero_asiento` AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.`fecha_vencimiento` AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.`fecha_pago` AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.`id_cliente` AS cuentas_por_pagar_id_proveedor,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra,
     
     clientes.`id_cliente` AS clientes_id_cliente,
       clientes.`apellido` AS clientes_apellido,
     clientes.`nombre` AS proveedores_nombre_comercial
FROM
     `clientes` clientes
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON clientes.`id_cliente` = cuentas_por_pagar.`id_cliente` ";
        if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_pagar ON cuentas_por_pagar.id_cuenta_por_pagar = detalle_cuentas_por_pagar.id_cuenta_por_pagar ";
}
        $sql .= " where cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  "; 
        
      if($tipo_anticipo !='0' && ($dominio=='dcacorp.com.ec' || $dominio=='www.dcacorp.com.ec') ){
    $sql .=" AND cuentas_por_pagar.`tipo_anticipo` ='".$tipo_anticipo."'  ";
}

    if (isset($_GET['txtBuscarCuentasPagar'])){
	$sql .= " and clientes.`nombre` like '%".fn_filtro(substr($_GET['txtBuscarCuentasPagar'], 0, 16))."%' "; }
	
	if($estado != 'Todos'){
    		if($estado == 'Pendientes'){
    		    $sql.=" and cuentas_por_pagar.`saldo`>0";
    		}else{
    		     $sql.=" and cuentas_por_pagar.`saldo`=0 ";
    		}
		}

		
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
       $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') 
        <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
     if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_pagar.`numero_compra`  ";
    }
	  $sql.=" ORDER BY  cuentas_por_pagar.`id_cliente`  DESC  ";
} else if ($cuentaTipo=='3'){
    
    $sql = "SELECT
     cuentas_por_pagar.`id_cuenta_por_pagar` AS cuentas_por_pagar_id_cuenta_por_pagar,
     cuentas_por_pagar.`numero_compra` AS cuentas_por_pagar_numero_compra,
     cuentas_por_pagar.`referencia` AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.`tipo_documento` AS cuentas_por_pagar_tipo_documento,
     cuentas_por_pagar.`valor` AS cuentas_por_pagar_valor,
     cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
   
     cuentas_por_pagar.`numero_asiento` AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.`fecha_vencimiento` AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.`fecha_pago` AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.`id_lead` AS cuentas_por_pagar_id_proveedor,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra,
     
     leads.`id` AS  clientes_id_cliente,
     leads.`name` AS proveedores_nombre_comercial
FROM
     `leads` leads
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON leads.`id` = cuentas_por_pagar.`id_lead` ";
        if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_pagar ON cuentas_por_pagar.id_cuenta_por_pagar = detalle_cuentas_por_pagar.id_cuenta_por_pagar ";
}
        $sql .= " where cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  "; 
        
    if (isset($_GET['txtBuscarCuentasPagar'])){
	$sql .= " and leads.`name` like '%".$_GET['txtBuscarCuentasPagar']."%' "; }
	
	if($estado != 'Todos'){
    		if($estado == 'Pendientes'){
    		    $sql.=" and cuentas_por_pagar.`saldo`>0";
    		}else{
    		     $sql.=" and cuentas_por_pagar.`saldo`=0 ";
    		}
		}

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
       $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') 
        <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
     if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_pagar.`numero_compra`  ";
    }
		  $sql.=" ORDER BY  cuentas_por_pagar.`id_lead`  DESC  ";

}else if ($cuentaTipo=='4'){
    
    $sql = "SELECT
     cuentas_por_pagar.`id_cuenta_por_pagar` AS cuentas_por_pagar_id_cuenta_por_pagar,
     cuentas_por_pagar.`numero_compra` AS cuentas_por_pagar_numero_compra,
     cuentas_por_pagar.`referencia` AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.`tipo_documento` AS cuentas_por_pagar_tipo_documento,
     cuentas_por_pagar.`valor` AS cuentas_por_pagar_valor,
     cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
   
     cuentas_por_pagar.`numero_asiento` AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.`fecha_vencimiento` AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.`fecha_pago` AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.`id_empleado` AS cuentas_por_pagar_id_proveedor,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra,
     
        empleados.`id_empleado`AS  clientes_id_cliente,
     empleados.`nombre` AS proveedores_nombre_comercial
FROM
   `empleados` empleados
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON  empleados.`id_empleado` = cuentas_por_pagar.`id_empleado` ";
        if($_GET['listado_por']=='Facturas'){
    $sql.=" INNER JOIN detalle_cuentas_por_pagar ON cuentas_por_pagar.id_cuenta_por_pagar = detalle_cuentas_por_pagar.id_cuenta_por_pagar ";
}
        $sql .= " where cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  "; 
        
    if (isset($_GET['txtBuscarCuentasPagar'])){
	$sql .= " and  empleados.`nombre`  like '%".$_GET['txtBuscarCuentasPagar']."%' "; }
	
	if($estado != 'Todos'){
    		if($estado == 'Pendientes'){
    		    $sql.=" and cuentas_por_pagar.`saldo`>0";
    		}else{
    		     $sql.=" and cuentas_por_pagar.`saldo`=0 ";
    		}
		}

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
       $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fecha_estado.", '%Y-%m-%d') 
        <= '".$fecha_hasta."' ";
}

         
        }
     if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_pagar.`numero_compra`  ";
    }
		  $sql.=" ORDER BY  cuentas_por_pagar.`id_empleado`  DESC  ";

}



//   if($sesion_id_empresa==41){
//       echo $sql;
//   }
       
   $result = $conexion->query($sql);
 $num_total_rows =$result->num_rows;
 $total_pages=0; 

$contadorTablas=0;// permite detectar cuando se crea una nueva tabla
if ($num_total_rows > 0) {
    $page = false;
    if (isset($_GET["page"])) {
      $page = $_GET["page"];
    }else{
         $page = 1;
    }
 
    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * $numeroRegistros;
    }

     $total_pages = ceil($num_total_rows / $numeroRegistros);
     
     
    $sql.=" LIMIT $start, ".$numeroRegistros;
    $result2 = $conexion->query($sql);
	
?>

<input  name="total_a_pagar" id="total_a_pagar" type="hidden" />



    
    <?php
    
    $total_saldo = 0;
    $total_valor = 0;
    $total_abonado=0;
    $sw1=0;
    $contador= 0 ;
    if ($result->num_rows > 0) {
    
      if($_GET['listado_por'] == 'Clientes' ){
         while ($row = $result2->fetch_assoc())
        {
        if($row['cuentas_por_pagar_tipo_documento']=='NotaCredito' && $cuentaTipo=='2' ){
            	  $sqlNc= "SELECT 
            	codigo_lug as codigo_lug,
                tipo_documento as codDoc,
                establecimientos.codigo as estab,
                emision.codigo as ptoEmi,
                numero_factura_venta as secuencial
            
                from ventas,emision,establecimientos 
                WHERE    id_venta='".$row['cuentas_por_pagar_id_compra']."' and emision.id=codigo_lug and establecimientos.id=ventas.codigo_pun and emision.id=ventas.codigo_lug";  

            $resultNc = mysql_query($sqlNc);
            $numSerie = '000';
            $numEmision = '000';
            $numfac = '000000000';
            while($rowNc = mysql_fetch_array($resultNc) ){
                $numSerie = $rowNc['estab'];
                $numEmision =  $rowNc['ptoEmi'];
                $numfac = $rowNc['secuencial'];
                
            }
            $numero_nota_credito = $numSerie.' - '.$numEmision.' - '.ceros($numfac);
            $sqlLd="SELECT `id_libro_diario`, `id_periodo_contable`, `numero_asiento`, `fecha`, `total_debe`, `total_haber`, `descripcion`, `numero_comprobante`, `tipo_comprobante`, `id_comprobante`, `id_cliente`, `codigo_pun`, `codigo_lug`, `estado_anticipo`, `numero_pedido`, `origen_asiento`, `tipo_mov`, `numero_cpra_vta`, `centroCosto` FROM `libro_diario` WHERE id_libro_diario='".$row['cuentas_por_pagar_numero_asiento']."'";
             $resultLd = mysql_query($sqlLd);
              $numeroAsientoLD = '000000000';
            while($rowLd = mysql_fetch_array($resultLd) ){
                $numeroAsientoLD = $rowLd['numero_asiento'];
            }
            
        }
        
    if ($sw1==0 or $id_Proveedor_ant<>$row['cuentas_por_pagar_id_proveedor'])   {    
        if($contador>0){
        ?>
        
  <tr>
            	<td colspan='2'><strong>TOTAL</strong></td>
                <td><strong><?php echo $total_valor; ?></strong></td>
                 <td><strong><?php echo $total_abonado; ?></strong></td>
                <td><strong><?php echo $total_saldo; ?></strong></td>
                </tr>
                
                <?php
        }
           if($cuentaTipo==1){
            $txtSubtotalInventarios = $row['compras_subTotalInvenarios'];
            $txtSubtotalProveeduria = $row['compras_subTotalProveeduria'] ;
            $txtSubtotalServicios = $row['compras_subtotalServicios'] ;
        }else{
            $txtSubtotalInventarios = 0;
            $txtSubtotalProveeduria = 0;
            $txtSubtotalServicios = 0;
        }
        
        $id_Proveedor_ant=$row['cuentas_por_pagar_id_proveedor'];
        $sw1=1;
        $total_saldo = 0;
        $total_valor = 0;
        $total_abonado=0;
        
?>
    <table class="table table-bordered table-condensed">          
        <thead>
        <tr>
            <td colspan="6">
                <h4><?=utf8_encode($row['proveedores_nombre_comercial']." ".$row['clientes_apellido'])?></h4>
                      <input type="hidden" id="txtSubtotalInventarios<?php echo  $row['cuentas_por_pagar_id_proveedor'] ?>" name="txtSubtotalInventarios" value="<?php echo $txtSubtotalInventarios  ?>">
                <input type="hidden" id="txtSubtotalProveeduria<?php echo  $row['cuentas_por_pagar_id_proveedor'] ?>" name="txtSubtotalProveeduria"  value="<?php echo $txtSubtotalProveeduria ?>" >
                <input type="hidden" id="txtSubtotalServicios<?php echo  $row['cuentas_por_pagar_id_proveedor'] ?>" name="txtSubtotalServicios"  value="<?php echo $txtSubtotalServicios ?>" >
            </td>
    
        <td>
            <?php 
                	if($estado == 'Pendientes' || $estado == 'Todos'){
		?>
            <a  onClick="pagarCtasPagar((<?php echo $row['cuentas_por_pagar_id_proveedor']; ?>),2,(<?php echo $cuentaTipo; ?>))" class="btn btn-secondary">
               <i class="fa fa-check"></i> Pagar
            </a>
            <?php
            }
		?>
        </td>
        
        </tr>
            
    </thead>    
    <tbody>
        <tr>
                <td><a onclick="javascript: listar_cuentas_por_pagar();" title="Actualizar"><i class="fa fa-repeat" aria-hidden="true"></i></a></td>
                <td><strong>Nro. Fac.</strong></td>
                <td><strong>Valor </strong></td>
                <td><strong>Abono </strong></td>
                <td><strong>Saldo</strong></td>
                <td><strong>Asiento</strong></td>
                <td><strong>Vencim.</strong></td>
                <td><strong>Pago.</strong></td>
                <td><strong><i class="fa fa-check"></i></strong></td>
                 
            </tr>
        
        <?php   }  
        
              $contador++;          
            $id_cuenta_por_pagar = $row['cuentas_por_pagar_id_cuenta_por_pagar'];           
            $cuentas_por_pagar_valor = $row['cuentas_por_pagar_valor'];
            $cuentas_por_pagar_saldo = $row['cuentas_por_pagar_saldo'];
            $total_valor = $total_valor + $cuentas_por_pagar_valor;
            $total_saldo = $total_saldo + $cuentas_por_pagar_saldo;
            $total_abonado=$total_abonado + ($cuentas_por_pagar_valor-$cuentas_por_pagar_saldo);
          ?>
         
                <tr>
                   
                    <td>
                         <?php  if( $sesion_tipo==='Administrador'){?>
                        <a onclick="javascript: eliminarCtasxpagar(<?=$row['cuentas_por_pagar_id_cuenta_por_pagar']?>,3);" title="Eliminar ctasxpagar"> <span class="btn fa fa-trash-alt"></span></a>
                     <?php  }?>
                     <?=$id_cuenta_por_pagar?>
                    </td>
                    
                    <?php
                     if($row['cuentas_por_pagar_tipo_documento']=='NotaCredito' && $cuentaTipo=='2' ){
                         ?>
                          <td><?=$numero_nota_credito?></td>
                         <?php
                     }else{
                         ?>
                          <td><?=$row['compras_numSerie'].'-'.$row['compras_txtEmision'].'-'.ceros($row['compras_txtNum'])?></td>
                         <?php
                     }
                     ?>
                   
    
              
                    <td><?=$row['cuentas_por_pagar_valor']?></td>
                    <td><?=$row['cuentas_por_pagar_valor']-$row['cuentas_por_pagar_saldo']?></td>
                    
                    <td><?=$row['cuentas_por_pagar_saldo']?></td> 
                     <?php
                     if($row['cuentas_por_pagar_tipo_documento']=='NotaCredito' && $cuentaTipo=='2' ){
                         ?>
                          <td>  <a href="asientosContables.php?numero_asientox=<?php echo $numeroAsientoLD?>" ><?=$numeroAsientoLD?> </a></td>
                     <?php
                     }else{
                         ?>
                          <td>  <a href="asientosContables.php?numero_asientox=<?php echo $row['cuentas_por_pagar_numero_asiento']?>" >
                        <?=$row['cuentas_por_pagar_numero_asiento']?> </a></td>
                         <?php
                     }
                     ?>
                   
                    <td><?=$row['cuentas_por_pagar_fecha_vencimiento']?></td>
                    <td><?=$row['cuentas_por_pagar_fecha_pago']?></td>
                    
                    <td>
                          <?php  if( $row['cuentas_por_pagar_saldo']>0){?>
                    <input type="checkbox" name="checkCobrar[]" class="form-check-input"
                     data-cliente="<?=$row['cuentas_por_pagar_id_proveedor']?>" data-saldo="<?=$row['cuentas_por_pagar_saldo']?>"
                     onClick="sumar(this)" onkeyup=" sumar(this)" value="<?=$row['cuentas_por_pagar_id_cuenta_por_pagar']?>" />
                      <?php  }?>
                    </td>
                </tr>
                
               <?php  if ($sw1==0 or $id_Proveedor_ant<>$row['cuentas_por_pagar_id_proveedor']) {  ?>  
                
                
                </tbody> </table>   <?php }   } 
          
      }else{
         while ($row = $result2->fetch_assoc())
        {
        $numserie = $row['compras_numSerie'];
        $txtEmision = $row['compras_txtEmision'];
        $txtNum = ceros($row['compras_txtNum']);
     
     
     
           if($cuentaTipo==1){
            $txtSubtotalInventarios = $row['compras_subTotalInvenarios'];
            $txtSubtotalProveeduria = $row['compras_subTotalProveeduria'] ;
            $txtSubtotalServicios = $row['compras_subtotalServicios'] ;
        }else{
            $txtSubtotalInventarios = 0;
            $txtSubtotalProveeduria = 0;
            $txtSubtotalServicios = 0;
        }
                $id_Proveedor_ant=$row['cuentas_por_pagar_id_proveedor'];
                $sw1=1;
                
                  $total_saldo = 0;
        $total_valor = 0;
        
                ?>
      <table class="table table-bordered table-condensed">          
    <thead>
        <tr>
            <td colspan="6">
                <h4><?=$numserie.'-'.$txtEmision.'-'.ceros($txtNum)?></h4>
                      <input type="hidden" id="txtSubtotalInventarios<?php echo  $row['cuentas_por_pagar_id_proveedor'] ?>" name="txtSubtotalInventarios" value="<?php echo $txtSubtotalInventarios  ?>">
                <input type="hidden" id="txtSubtotalProveeduria<?php echo  $row['cuentas_por_pagar_id_proveedor'] ?>" name="txtSubtotalProveeduria"  value="<?php echo $txtSubtotalProveeduria ?>" >
                <input type="hidden" id="txtSubtotalServicios<?php echo  $row['cuentas_por_pagar_id_proveedor'] ?>" name="txtSubtotalServicios"  value="<?php echo $txtSubtotalServicios ?>" >
            </td>

        </tr>
            
    </thead>    
    <tbody>
        <tr>
                <td><a onclick="javascript: listar_cuentas_por_pagar();" title="Actualizar"><i class="fa fa-repeat" aria-hidden="true"></i></a></td>
                <td><strong>Nro. Fac.</strong></td>
                <td><strong>Saldo</strong></td>
                <td><strong>Abono </strong></td>
                <td><strong>Asientos</strong></td>
                <td><strong>Pago.</strong></td>
                 <!--<td><strong>Valor</strong></td>-->

                 
            </tr>
        
        <?php   
        
              $contador++;          
            $id_cuenta_por_pagar = $row['cuentas_por_pagar_id_cuenta_por_pagar'];           
            $cuentas_por_pagar_valor = $row['cuentas_por_pagar_valor'];
            $cuentas_por_pagar_saldo = $row['cuentas_por_pagar_saldo'];
            $total_valor = $total_valor + $cuentas_por_pagar_valor;
            $total_saldo = $total_saldo + $cuentas_por_pagar_saldo;
            
            $sqlDetalleCC="SELECT  `id_detalle_cuenta_pagar`, `id_cuenta_por_pagar`, `valor`, `fecha`,numero_asiento FROM `detalle_cuentas_por_pagar` WHERE id_cuenta_por_pagar=$id_cuenta_por_pagar ";
                $resultDetalleCC = mysqli_query($conexion,$sqlDetalleCC) ;
                $saldo_detalle= $row['cuentas_por_pagar_valor'];
                $totalDetalleValor= 0;
                $totalDetalleSaldo=0;
                 $totalDetalleAbono=0;
                while( $rowDetalle = mysqli_fetch_array( $resultDetalleCC )){
                     $saldo_detalle =$saldo_detalle - $rowDetalle['valor'];
                    $totalDetalleSaldo = $totalDetalleSaldo+ $saldo_detalle;
                    $totalDetalleValor = $totalDetalleValor+ $rowDetalle['valor'];
                    $totalDetalleAbono = $totalDetalleAbono+ ($rowDetalle['valor']-$rowDetalle['saldo']);
          ?>
         
                <tr>
                   
                    <td>
                         <?php  if( $sesion_tipo==='Administrador'){?>
                        <a onclick="javascript: eliminarCtasxpagar(<?=$row['cuentas_por_pagar_id_cuenta_por_pagar']?>,3);" title="Eliminar ctasxpagar"> <span class="btn fa fa-trash-alt"></span></a>
                     <?php  }?>
                    </td>
                    <td>
                        <h4><?=$numserie.'-'.$txtEmision.'-'.ceros($row['compras_txtNum'])?></h4>
                    </td>
                     <td><?=$saldo_detalle?></td>
                      <td><?=$rowDetalle['valor']?></td> 
                 <td> 
                 <?php
                    if($rowDetalle['numero_asiento']==0){
                       
                    }else{
                        ?>
                         <a target="_blank" href="reportes/rptComprobanteDiario.php?txtAsientoNumero=<?php echo $rowDetalle['numero_asiento']?>" ><?=$rowDetalle['numero_asiento']?></a>
                        <?php
                    }
                 ?>
                
                 
                 </td> 
                      <td><?=$rowDetalle['fecha']?></td>
                       <!--<td><?php  $rowDetalle['valor']?></td>-->

                    </td>
                </tr>
                
               <?php  } 
               
         ?>  
         <tr>
                            <td colspan='2'><strong>TOTAL</strong></td>
                           
                            <td><strong><?php echo $totalDetalleSaldo; ?></strong></td>
                            <td><strong><?php echo $totalDetalleValor; ?></strong></td>
                             <td colspan='2'></td>
                              <!--<td><strong><?php  $totalDetalleValor; ?></strong></td>-->
                            </tr>   
                            </tbody>   
                            </table>    
                
                
                </tbody> </table>   <?php   }   
      } 
      
      }         
         ?>
                
              
                
        <?php  }  ?>
        
   <?php
 if($_GET['listado_por']!='Facturas'){
?> 
        <tr>
        	<td colspan='2'><strong>TOTAL</strong></td>
            <td><strong><?php echo $total_valor; ?></strong></td>
            <td><strong><?php echo $total_abonado; ?></strong></td>
            <td><strong><?php echo $total_saldo; ?></strong></td>
        </tr>
    </tbody>   
</table> 
        <?php
 }


     echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_pagar('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_pagar('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_pagar('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

// echo '<nav>';
// echo '<ul class="pagination">';
// // echo 'total=>'.$total_pages;
// if ($total_pages > 1) {
//     if ($page != 1) {
//         echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_pagar('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
//     }

//     for ($i=1;$i<=$total_pages;$i++) {
//         if ($page == $i) {
//             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
//         } else {
//             echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_pagar('.$i.')" >'.$i.'</a></li>';
//         }
//     }

//     if ($page != $total_pages) {
//         echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_por_pagar('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
//     }
// }
// echo '</ul>';
// echo '</nav>';

?>