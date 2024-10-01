<?php

 include ("../conexion.php");
  include('../conexion2.php');
//  include_once("../sql/funcionesRolPagos.php");
 session_start();
 $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 error_reporting(1);
 function ceros($valor){
    $s='';
for($i=1;$i<=9-strlen($valor);$i++)
     $s.="0";
return $s.$valor;
}
 $id_cliente= $_GET['id_cliente'];

$fechaDesde = $_GET['txtFechaDesde'];
$fechaHasta = $_GET['txtFechaHasta'];
$criterio_mostrar = $_GET['criterio_mostrar'];
$criterio_ordenar_por = $_GET['criterio_ordenar_por'];
$criterio_orden = $_GET['criterio_orden'];
$estado= $_GET['estado'];

if($estado=='Canceladas'){
    $fehca='fecha_pago';
   
}else{
  $fehca='fecha_vencimiento';
}

  $numeroRegistros= $_GET['criterio_mostrar'];
    $sql= "SELECT 
   clientes.`id_cliente` AS clientes_id_cliente,
  clientes.`nombre` AS proveedores_nombre_comercial,
  clientes.`apellido` AS clientes_apellido,
  clientes.`direccion` AS clientes_direccion,
  clientes.`cedula` AS clientes_cedula,
  clientes.`prop_nombre` AS clientes_prop_nombre,
  clientes.`numero_casa` AS clientes_numero_casa,
  cuentas_por_cobrar.id_cuenta_por_cobrar,
  cuentas_por_cobrar.`numero_factura` AS cuentas_por_cobrar_numero_factura,
    cuentas_por_cobrar.`valor`,
    cuentas_por_cobrar.`saldo`,
    cuentas_por_cobrar.`fecha_vencimiento`,
    cuentas_por_cobrar.`fecha_pago`,
    emision.codigo as emision_codigo,
    establecimientos.codigo as establecimientos_codigo
      
    FROM  `clientes`
    INNER JOIN cuentas_por_cobrar ON clientes.id_cliente= cuentas_por_cobrar.id_cliente 
    LEFT JOIN ventas ON ventas.id_venta = cuentas_por_cobrar.id_venta
  LEFT JOIN emision ON ventas.codigo_lug = emision.id
LEFT JOIN establecimientos ON establecimientos.id= ventas.codigo_pun

    where  DATE_FORMAT(cuentas_por_cobrar.".$fehca." , '%Y-%m-%d')>='".$fechaDesde."' AND  DATE_FORMAT(cuentas_por_cobrar.".$fehca." , '%Y-%m-%d')<='".$fechaHasta."'";
      if( $_GET['id_cliente']!=''){
            
            $sql.=" AND clientes.id_cliente = ".$id_cliente;   
        }

        $sql.=" AND cuentas_por_cobrar.estado ='".$estado."' ";
        

        $sql.=" AND clientes.id_empresa=$sesion_id_empresa ";
// echo $sql;
$result = mysql_query($sql);
$numero_filas = mysql_num_rows($result); // obtenemos el número de filas
$result = $conexion->query($sql);
 $num_total_rows =$result->num_rows;
 $total_pages=0; 

$contadorTablas=0;// permite detectar cuando se crea una nueva tabla
if ($num_total_rows > 0) {
    $page = false;
    if (isset($_GET["page"])) {
      $page = $_GET["page"];
    }
 
    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * $numeroRegistros;
    }

     $total_pages = ceil($num_total_rows / $numeroRegistros);
    $sql.=" ORDER BY $criterio_ordenar_por  $criterio_orden LIMIT $start, ".$numeroRegistros;
// echo $sql;
    $result2 = $conexion->query($sql);   
      
  
    ?>
    <form id="frmCuentasCobrar" name="frmCuentasCobrar">
          <input  name="switch-four" id="switch-four" type="hidden"value="2" />

   
    <input  name="total_a_pagar" id="total_a_pagar" type="hidden" />
    
   
     <a  onClick="pagarCtaCobrar22((<?php echo $id_cliente; ?>),2,2)" class="btn btn-secondary">
                   <i class="fa fa-check"></i> Cobrar
                </a>
    <table class="table table-bordered m-0"  style="background-color: white;">
    <thead>
        <tr>
        <td><strong>Nro. Fac.</strong></td>
            <th>C&eacute;dula</th>
            <th>Nombre</th>
            <th>Valor</th>
            <th>Abonado</th>
            <th>Saldo</th>
            <th>Fecha Vencimiento</th>
            <th>Fecha Pago</th>
            <td><strong><i class="fa fa-check"></i></strong></td>
        </tr>
    </thead>
<tbody>
    <?php
        
    
    $contadorFilas=0;
    $id_rol_pagos=0;
    $saldo_detalle =0;
    $totalDetalleValor = 0;
    $totalAbonado = 0;
     while ($rs_per=$result2->fetch_assoc() ){
         $abonado= $rs_per['valor']-$rs_per['saldo'];
          $hoy= date('Y-m-d');
          
          

$fechaIngresada = strtotime($rs_per['fecha_vencimiento']);
$fechaActual = strtotime($hoy);

// Calcula la diferencia en segundos
$diferenciaEnSegundos = $fechaActual - $fechaIngresada;

// Convierte la diferencia en meses
$meses = $diferenciaEnSegundos / (30 * 24 * 60 * 60); 

if ($meses> 1 && (is_null($rs_per['fecha_pago'])||$rs_per['saldo']>0 ) ) {
    $estilos  ="background-color: orange;";
} else {
    $estilos  = '';
}

     ?>
     
       <tr style="<?php echo $estilos ?>">
       <td><?php echo $rs_per['establecimientos_codigo']."--".$rs_per['emision_codigo']."--".ceros($rs_per['cuentas_por_cobrar_numero_factura'])?></td>
        <td><?php  echo $rs_per['clientes_cedula'] ?>
        </td>
        <td><?php  echo $rs_per['proveedores_nombre_comercial'] ?></td>
        
        
        <td><?php echo   $rs_per['valor']; ?></td>  
        <td><?php echo    $abonado; ?></td>  
        <td><?php echo   $rs_per['saldo']; ?></td> 
        <td><?php echo   $rs_per['fecha_vencimiento']; ?></td> 
        <td><?php echo   $rs_per['fecha_pago']; ?></td> 
        <td>
            <input type="checkbox" name="checkCobrar[]" class="form-check-input" data-cliente="<?=$rs_per['clientes_id_cliente']?>" data-saldo="<?=$rs_per['saldo']?>" onClick="sumar(this)" onkeyup=" sumar(this)" value="<?=$rs_per['id_cuenta_por_cobrar']?>" />
        </td>
        </tr>
     
     <?php
 $saldo_detalle =$saldo_detalle + $rs_per['saldo'];
 $totalDetalleSaldo = $saldo_detalle;
$totalDetalleValor = $totalDetalleValor+ $rs_per['valor'];
$totalAbonado = $totalAbonado + $abonado;
}
echo "<tr>
<td><strong>TOTAL</strong></td>
<td></td>
<td><strong> $totalDetalleValor </strong></td>
<td><strong> $totalAbonado </strong></td>
<td><strong>$totalDetalleSaldo</strong></td>
</tr>   
</tbody>   
</table></form>";

}
echo '<nav>';
echo '<ul class="pagination">';
// echo 'total=>'.$total_pages;
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_cobrar('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    }

    for ($i=1;$i<=$total_pages;$i++) {
        if ($page == $i) {
            echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_cobrar('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_cuentas_cobrar('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

 

 ?>
 