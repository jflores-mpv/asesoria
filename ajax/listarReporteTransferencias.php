<?php 
error_reporting(0);
// include "../conexion.php";
    session_start();
include('../conexion2.php');
include('../conexion.php');
$emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
$emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
// echo $emision_tipoFacturacion;
   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $tipoDoc= $_GET['tipoDoc'];
  $txtUsuarios= $_GET['txtUsuarios'];
    $estado= $_GET['estado'];
    $numeroRegistros= $_GET['criterio_mostrar'];
    // $numeroRegistros=2;
    
  if($tipoDoc=='Ingresos'){
    $sql="SELECT
    
    ingresos.id_ingreso as id_transferencia,
    ingresos.estado  as  estado_transferencia,
    ingresos.fecha   as  fecha_transferencia,
    ingresos.total  as total_transferencia,
    ingresos.numero  as numero_transferencia,
    detalle_ingresos.id_detalle_ingreso as detalleingreso,
    ingresos.id_empresa,
    productos.producto,
    bodegas.detalle
FROM
    `ingresos`
INNER JOIN detalle_ingresos ON detalle_ingresos.id_ingreso = ingresos.id_ingreso
INNER JOIN productos ON productos.id_producto = detalle_ingresos.id_producto
INNER JOIN bodegas ON bodegas.id = detalle_ingresos.bodega
WHERE
    ingresos.id_empresa = $sesion_id_empresa";
    if($_GET['txtFechaDesde']!='' && $_GET['txtFechaHasta'] !=''){
        $sql .=" AND  DATE_FORMAT(ingresos.fecha,'%Y-%m-%d') >='".$_GET['txtFechaDesde']."' AND DATE_FORMAT(ingresos.fecha,'%Y-%m-%d') <='".$_GET['txtFechaHasta']."' ";
    }
    if($_GET['txtClientes']!='0' ){
        $sql .=" and productos.id_producto =".$_GET['txtClientes']." ";
    }
    if($_GET['bodegas']!='0' ){
        $sql .=" and bodegas.id =".$_GET['bodegas']." ";
    }
    $sql .=" GROUP BY   ingresos.id_ingreso ";
  }else if($tipoDoc=='Egresos'){
    $sql="SELECT
    egresos.id_egreso as id_transferencia,
    egresos.fecha as   fecha_transferencia,
    egresos.total    as total_transferencia,
    egresos.sub_total  as subtotal_transferencia,
    egresos.numero as numero_transferencia,
    detalle_egresos.cantidad as cantidad_transferencia,
    detalle_egresos.v_unitario as v_unitario_transferencia,
    detalle_egresos.v_total  as v_total_transferencia,
    productos.producto,
    bodegas.detalle
FROM
    egresos
INNER JOIN detalle_egresos ON detalle_egresos.id_egreso = egresos.id_egreso
INNER JOIN productos ON productos.id_producto = detalle_egresos.id_producto
INNER JOIN bodegas ON bodegas.id = detalle_egresos.bodega
WHERE
egresos.id_empresa = $sesion_id_empresa";
  if($_GET['txtFechaDesde'] !='' && $_GET['txtFechaHasta']!='' ){
    $sql .=" AND  DATE_FORMAT(egresos.fecha,'%Y-%m-%d') >='".$_GET['txtFechaDesde']."' AND DATE_FORMAT(egresos.fecha,'%Y-%m-%d') <='".$_GET['txtFechaHasta']."' ";
}
if($_GET['txtClientes']!='0' ){
    $sql .="  and productos.id_producto =".$_GET['txtClientes']." ";
}
if($_GET['bodegas']!='0' ){
    $sql .=" and bodegas.id =".$_GET['bodegas']." ";
}
$sql .=" GROUP BY    egresos.id_egreso";

  }else if($tipoDoc=='Transferencias'){
    $sql="SELECT 
    transferencias.id_transferencia as id_transferencia,
    transferencias.num_trans  as numero_transferencia,
    transferencias.id_ingreso as transferencias_idIngreso,
    transferencias.id_egreso as transferencias_idEgreso,
    transferencias.fecha_trans as transferencias_fecha,
    egresos.total as total_transferencia,
    detalle_egresos.id_detalle_egreso as idDetalle,
    detalle_egresos.cantidad as egreso_cantidad,
    detalle_egresos.bodega as egreso_bodega,
    detalle_egresos.v_unitario as egreso_vUnitario,
    detalle_egresos.v_total as egreso_vTotal,
    detalle_ingresos.cantidad as ingreso_cantidad,
    detalle_ingresos.bodega as ingreso_bodega,
    detalle_ingresos.v_unitario as ingreso_vUnitario,
    detalle_ingresos.v_total as ingreso_vTotal
FROM
    `transferencias`
INNER JOIN egresos ON egresos.id_egreso = transferencias.id_egreso
INNER JOIN ingresos ON ingresos.id_ingreso = transferencias.id_ingreso
INNER JOIN detalle_ingresos ON detalle_ingresos.id_ingreso = ingresos.id_ingreso
INNER JOIN detalle_egresos ON detalle_egresos.id_egreso = egresos.id_egreso
INNER JOIN productos ON productos.id_producto = detalle_egresos.id_producto where transferencias.id_empresa=$sesion_id_empresa ";
  if($_GET['txtFechaDesde'] !='' && $_GET['txtFechaHasta'] !=''){
    $sql .=" AND  DATE_FORMAT(transferencias.fecha_trans,'%Y-%m-%d') >='".$_GET['txtFechaDesde']."' AND DATE_FORMAT(transferencias.fecha_trans,'%Y-%m-%d') <='".$_GET['txtFechaHasta']."' ";
}
if($_GET['txtClientes']!='0' ){
    $sql .=" and productos.id_producto =".$_GET['txtClientes']." ";
}
if($_GET['bodegas']!='0' ){
    $sql .=" and (detalle_egresos.bodega =".$_GET['bodegas']." ||detalle_ingresos.bodega =".$_GET['bodegas']."  ) ";
}
$sql .=" GROUP BY    transferencias.id_transferencia ";
  }
// echo $sql;
   
    $result = mysql_query($sql);
$num_total_rows =mysql_num_rows($result);




if ($num_total_rows > 0) {
    $page = false;
//  echo "PAGE==>".$_GET["page"];
    //examino la pagina a mostrar y el inicio del registro a mostrar
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

  

    if($tipoDoc=='Ingresos'){
        $sql.= " ORDER BY ingresos.id_ingreso ".  $_GET["criterio_orden"]." LIMIT ".$start." , ".$numeroRegistros ;
    }else if($tipoDoc=='Egresos'){
        $sql.= " ORDER BY  egresos.id_egreso ".  $_GET["criterio_orden"]." LIMIT ".$start." , ".$numeroRegistros ;
    }else if($tipoDoc=='Transferencias'){
        $sql.= "   ORDER BY  transferencias.id_transferencia ".  $_GET["criterio_orden"]." LIMIT ".$start." , ".$numeroRegistros  ;
    }
    // $sql.= " ORDER BY  ingresos.id_ingreso DESC LIMIT ".$start." , ".NUM_ITEMS_BY_PAGE ;

    
    $result=mysqli_query($conexion,$sql);
    

    if ($num_total_rows > 0) {
        
          
        
        // if ($row_cnt> 0) {
        
        // echo '<ul class="row bg-danger">';
        while ($row = $result->fetch_assoc()) {
            
               $ventas_estado_anulacion = 'Activo';
            //  echo "estado".$ventas_estado_anulacion;
            if($ventas_estado_anulacion!='Activo'){
                $bgestado='bg-ligth border';
            }else {
                $bgestado='bg-white';
            }
            if($tipoDoc=='Transferencias' ){
                ?>
            
            
                <div class="row accordion pt-1 my-2 rounded <?php echo $bgestado?>">
                  <div class="col-lg-8">
                        
                          <div class="row ">
                      
                              <!--<div class="col-lg-1"></div>-->
                              <div class="col-lg-4"><?php echo $tipoDoc?> # <?=utf8_encode($row['numero_transferencia'])?></div>
                              <div class="col-lg-3 text-center">Totales $<?=$row['id_transferencia'].$row['total_transferencia']?></div>
                    
                              <div class="col-lg-3"><?=date('d-m-Y',strtotime($row['transferencias_fecha']))?></div>	
                           
                
                      </div>
                      <?php
                $sqlBodegaOrigen=" SELECT bodegas.detalle FROM bodegas WHERE bodegas.id=".$row['egreso_bodega'];
                $resultBodegaOrigen=  mysql_query($sqlBodegaOrigen);
                $bodegaOrigen='';
                while($rowBo = mysql_fetch_array($resultBodegaOrigen)){
                    $bodegaOrigen = $rowBo['detalle'];
                }

                $sqlBodegaDestino=" SELECT bodegas.detalle FROM bodegas WHERE bodegas.id=".$row['ingreso_bodega'];
                $resultBodegaDestino=  mysql_query($sqlBodegaDestino);
                $bodegaDestino='';
                while($rowBd = mysql_fetch_array($resultBodegaDestino)){
                 $bodegaDestino= $rowBd['detalle'];
                }

?> 
                      <div class="row">
                              <div class="col-lg-3">Bodega Origen:</div>
                              <div class="col-lg-3"><strong><?=$bodegaOrigen?></strong></div>
                              <div class="col-lg-3">Bodega Destino:</div>
                              <div class="col-lg-3"><strong><?=$bodegaDestino?></strong></div>
                      </div>

                 
                  </div>
                  <div class="col-lg-2">
                      <div class="row">
                 
                          
                          
                  <div class="col-lg-12 mt-2"><a  value="Generar XML" class="btn btn-success btn-sm w-100" onclick="pdfTransferenciasIngresoEgresos('<?=$row['id_transferencia']?>','<?php echo $tipoDoc?>')">Comprobante</a></div> 
             
                     </div>
                  </div>
    <div class="col-lg-12 mt-2">              
          <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            
            if($dominio=='www.contaweb.ec' or $dominio=='contaweb.ec'){
    ?>   
        <button class="btn btn-warning mt-2" onClick="imprimirCodigos('<?php echo $row['id_transferencia']?>','detalle_ingresos','')"> Codigos</button>
     <?php    }   ?>    
     </div>
              </div>
                  
                  
                  <?php
            }else{
            ?>
            
            
          <div class="row accordion pt-1 my-2 rounded <?php echo $bgestado?>">
            <div class="col-lg-8">
                  
                    <div class="row ">
                
                    
                    <div class="col-lg-4"><?php echo $tipoDoc ?> # <strong> <?=utf8_encode($row['numero_transferencia'])?> </strong> </div>
                        <div class="col-lg-3 text-center">Totales $ <strong> <?=$row['total_transferencia']?> </strong> </div>
                        <div class="col-lg-3"><?=date('d-m-Y',strtotime($row['fecha_transferencia']))?></div>	
                
                </div>
                <div class="row">
                        <div class="col-lg-2">Bodega:</div>
                        <div class="col-lg-8"><strong> <?=$row['detalle']?> </strong></div>
                </div>
                
                
            </div>
            <div class="col-lg-2">
                <div class="row">
    
                    
                    
            <div class="col-lg-12 mt-2"><a  onclick="pdfTransferenciasIngresoEgresos('<?=$row['id_transferencia']?>','<?php echo $tipoDoc?>')" value="Generar XML" class="btn btn-success btn-sm w-100">Comprobante</a></div> 
           

               </div>
            </div>
                   </div>
    <div class="col-lg-12 mt-2">              
          <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            
            if($dominio=='www.contaweb.ec' or $dominio=='contaweb.ec'){
    ?>   
        <button class="btn btn-warning mt-2" onClick="imprimirCodigos('<?php echo $row['id_transferencia']?>','detalle_ingresos','')"> Codigos</button>
     <?php    }   ?>    
     </div>
              </div>
            
            <?php
            }
        }
        // echo '</ul>';
    }
 
      echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_transferencias('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_transferencias('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_transferencias('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

    // echo '<nav>';
    // echo '<ul class="pagination">';
 
    // if ($total_pages > 1) {
    //     if ($page != 1) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_transferencias('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    //     }
 
    //     for ($i=1;$i<=$total_pages;$i++) {
    //         if ($page == $i) {
    //             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
    //         } else {
    //             echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_transferencias('.$i.')" >'.$i.'</a></li>';
    //         }
    //     }
 
    //     if ($page != $total_pages) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_transferencias('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    //     }
    // }
    // echo '</ul>';
    // echo '</nav>';
}else{ ?>
        
        <div class="row">
            <div class="col-lg-4 offset-lg-4  p-5 text-center bg-warning rounded border-0">
                 <h5>No existen <?php  echo $tipoDoc ?> .</h5>
            </div>
        </div>
        
<?php    }



?>

<script>



function pdfTransferenciasIngresoEgresos(id,tipo){

         miUrl = "reportes/reporteIngresosEgresoTransferencias.php?txtComprobanteNumero="+id+"&tipo="+tipo;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        }

        
    


</script>
    