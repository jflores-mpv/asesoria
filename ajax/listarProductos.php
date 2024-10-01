<?php 
error_reporting(0);
// include "../conexion.php";
    session_start();
include ("../conexion.php");
include('../conexion2.php');
include "../clases/paginado_basico.php";

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$criterio_usu_per= $_GET['criterio_usu_per'];
$criterio_usu_tipo= $_GET['criterio_tipo'];
$criterio_usu_cod= $_GET['criterio_usu_cod'];
// echo "sesion id empresa==>".$sesion_id_empresa."<==>";
$numeroRegistros= $_GET['criterio_mostrar'];
$radio_agrupado_codigo= $_GET['radio_agrupado_codigo'];

 $sql ="SELECT
     categorias.`id_categoria` AS categorias_id_categoria,
     categorias.`categoria` AS categorias_categoria,
     productos.`id_producto` AS productos_id_producto,
     productos.`producto` AS productos_producto,
     productos.`existencia_minima` AS productos_existencia_minima,
     productos.`existencia_maxima` AS productos_existencia_maxima,
     productos.`stock` AS productos_stock2,
         impuestos.`iva` AS productos_iva,
	 productos.`proceso` AS productos_proceso,
     productos.`costo` AS productos_costo,
     productos.`id_categoria` AS productos_id_categoria,
     productos.`id_proveedor` AS productos_id_proveedor,
     productos.`precio1` AS productos_precio1,
     productos.`precio2` AS productos_precio2,
     productos.`codigo` AS productos_codigo,
     productos.`tipos_compras` AS productos_tipos_compras,
     productos.`id_empresa` AS productos_id_empresa,
     productos.`img` AS productos_img,
      productos.`codPrincipal` AS codPrincipal,
       productos.`mostrar` AS productos_mostrar,
       productos.`codAux` AS codAuxiliar,
      IF(cc.total IS NULL,0,cc.total ) AS productos_stock
FROM
     `categorias` categorias 
     
INNER JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria` and productos.`id_empresa`='".$sesion_id_empresa."' 

     ";
     
       if (trim($_GET['criterio_usu_cod']) !=''){
        $sql .= " and productos.`codigo` like '%".$_GET['criterio_usu_cod']."%' "; }  
      if (trim($_GET['iva']) !='0'){
            $sql .= " and productos.`iva` = '".$_GET['iva']."' "; }  

   	if (trim($criterio_usu_per)!=''){
        $sql .= " and productos.`producto` like '%".$criterio_usu_per."%' "; 
    }
     
    if ($_GET['criterio_tipo']!=''){
        $sql .= " and productos.`tipos_compras` = '".$criterio_usu_tipo."' "; 
        
        if($_GET['criterio_area']!='0'){
            $sql .= " and productos.`grupo` = '".$_GET['criterio_area']."' "; 
        }
    }  
   
    
     
   $sql .= "   LEFT JOIN impuestos on productos.`iva` = impuestos.id_iva ";
$sql.= "LEFT JOIN(
    SELECT
        SUM(cantidad) AS total,
        cantBodegas.idProducto,
        bodegas.id
    FROM
        cantBodegas,
        bodegas
    WHERE
        bodegas.id = cantBodegas.idBodega AND bodegas.id_empresa = '".$sesion_id_empresa."' 
         GROUP BY cantBodegas.idProducto, cantBodegas.idBodega
) AS cc ON cc.idProducto = productos.codigo";

$sql .= " where productos.`id_empresa`='".$sesion_id_empresa."' ";
if(trim($_GET['bodega'])!='0'){
    $sql.=" and  cc.id='".$_GET['bodega']."'  ";
}

    
if($_GET['radio_agrupado_codigo']=='Agrupado'){
    $sql.= " GROUP BY productos.`codigo`  " ;
}else{
    $sql.= " GROUP BY  productos.`id_producto` " ;
}

 $result = mysql_query($sql);
$num_total_rows= mysql_num_rows($result);
     

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
 
    $sql.=" ORDER BY  ".$_GET["criterio_ordenar_por"]."   ".$_GET["criterio_orden"]." LIMIT ".$start." , ".$numeroRegistros ;
   

    
    $result=mysqli_query($conexion,$sql);

    
?>

<table id="grilla" class="table table-hover table-bordered table-striped bg-white">
<thead>
             <tr>
  
    <th><strong></strong></th>
  
    <th><strong>#</strong></th>
   
    
    <?= ($_GET['radio_agrupado_codigo'] == 'Agrupado') ? $row['productos_codigo'] : $row['productos_id_producto'] ?>
    
    <?php if ($_GET['radio_agrupado_codigo'] != 'Agrupado'): ?>
        <th><strong>ID</strong></th>
    <?php endif; ?>
    
   
    
    
    <th><strong>C&oacute;digo</strong></th>
    
    <th><strong>Nombre</strong></th>
    <th><strong>Precio Costo</strong></th>
    <th><strong>Precio Venta</strong></th>
    <!--<th><strong>Stock</strong></th>-->
    <th><strong>IVA</strong></th>
    <th colspan="4"><strong>Acciones</strong></th>
</tr>

                </thead>
    <tbody>
    <?php
    $contador = 1;
    while ($row = $result->fetch_assoc()) {
        $productos_mostrar = $row['productos_mostrar'];
        // $sqlCantidadTotalBodegas = "SELECT SUM(cantidad) as total FROM cantBodegas, bodegas WHERE idProducto = '".$row['productos_codigo']."' AND bodegas.id = cantBodegas.idBodega AND bodegas.id_empresa='".$sesion_id_empresa."'";
        // $resultCantidadTotalBodegas = mysqli_query($conexion, $sqlCantidadTotalBodegas);
        // $sumaStockBodegas = 0;
        // while ($rowCantidadTotalBodega = mysqli_fetch_array($resultCantidadTotalBodegas)) {
        //     $sumaStockBodegas = $rowCantidadTotalBodega['total'];
        // }
        // $sumaStockBodegas = ($sumaStockBodegas == '') ? 0 : $sumaStockBodegas;
        ?>
<tr id="tr_<?= $row['productos_id_producto'] ?>" class="bg-white">
 
        <td><input class="form-check-input" type="checkbox" name="check_producto" value="<?= $row['productos_codigo'] ?>"></td>
    
    <td><?= $contador ?></td>
    <td><?= ($_GET['radio_agrupado_codigo'] == 'Agrupado') ? $row['productos_codigo'] : $row['productos_id_producto'] ?></td>
    <?php if ($_GET['radio_agrupado_codigo'] != 'Agrupado'): ?>
        <td style="width:100%">
            <div class="input-group mb-3">
                <input class="form-control" name="codigo_actualizable<?= $row['productos_id_producto'] ?>" type="text" id="codigo_actualizable<?= $row['productos_id_producto'] ?>" placeholder="C&oacute;digo producto" title="Ingrese un c&oacute;digo para buscar" value="<?= $row['productos_codigo'] ?>">
                <span class="input-group-text">
                    <a title="Guardar" href="javascript: actualizarCodigoProducto('<?= $row['productos_id_producto'] ?>',codigo_actualizable<?= $row['productos_id_producto'] ?>.value,'<?= $row['productos_codigo'] ?>');">
                        <span class="fa fa-check" aria-hidden="true"></span>
                    </a>
                </span>
            </div>
        </td>
    <?php endif; ?>
    <td><?= utf8_encode($row['productos_producto']) ?></td>
    <td><?= $row['productos_costo'] ?></td>
    <td>
        <?php if($row['productos_iva'] > '0'): ?>
            <table class="table table-bordered">
                <tr>
                    <td class="text-right"><?= number_format(round($row['productos_precio1'], 6), 6, '.', ',') ?></td>
                    <td class="text-right"><?= number_format($row['productos_precio1'] + ($row['productos_precio1'] * (floatval($row['productos_iva'])/100)), 6, '.', ',') ?></td>
                </tr>
            </table>
        <?php else: ?>
            <table class="table table-bordered">
                <tr>
                    <td class="text-right"><?= number_format($row['productos_precio1'], 6, '.', ',') ?></td>
                    <td class="text-right"><?= number_format($row['productos_precio1'], 6, '.', ',') ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </td>
    <td><?= $row['productos_iva'] ?></td>
    <td><a href="javascript: fotoProducto('<?= $row['productos_codigo'] ?>');" title="foto Producto"><span type="button" class="btn  fa fa-image"></a></td> 
    
    <?php 
    if($row['productos_mostrar']==1){
         $icono ="thumbs-up-outline";
    }else{
         $icono ="thumbs-down-outline";
    }
   
   
    
    ?>
    <td><a href="javascript: ocultarProducto(<?= $row['productos_id_producto'] ?>,<?= $row['productos_mostrar'] ?>);" title="Activar Usuario" class="btn"><ion-icon name="<?php echo $icono ?>" role="img" class="md hydrated" aria-label="<?php echo $icono ?>"></ion-icon></a></td> 
    <td>
        <?php if($_COOKIE['tipo_cookie'] == 'Administrador' || $_SESSION["sesion_tipo"] == 'Administrador'): ?>
            <a href="javascript: modificarProducto(<?= $row['productos_id_producto'] ?>);" title="Editar Producto">
                <span type="button" class="btn  fa fa-edit"></span>
            </a>
        <?php endif; ?>
    </td>
    <td><a href="javascript: eliminarProducto(<?= $row['productos_id_producto'] ?>,3);" title="Editar Producto"><span type="button" class="btn  fa fa-trash"></a></td> 



</tr>
    <?php 

    $listadoBodega= array();
         $sqlBodegas = "SELECT *,bodegas.id as bodega_actual FROM cantBodegas,bodegas WHERE idProducto = '".$row['productos_codigo']."' AND bodegas.id = cantBodegas.idBodega AND bodegas.id_empresa =  '".$row['productos_id_empresa']."' ";
        if(trim($_GET['bodega']) != '0'){
            $sqlBodegas .= " AND bodegas.id='".$_GET['bodega']."'  ";
        }
        
        $qryBodegas = mysqli_query($conexion, $sqlBodegas);
        $contadorBod=0;
        
        while ($rowBodegass = mysqli_fetch_array($qryBodegas)) { 
          
            $bod_act = $rowBodegass['bodega_actual'];
            $listadoBodega[$bod_act] = $rowBodegass['detalle'];
        
            $contadorBod++;
        }
 
        if ($contadorBod>0) { 
        ?>
           
                  </table>      
              
                     <div class="subtable-container" style=" overflow-x: auto;">
                    <table class="table table-bordered" style="background-color: white;/* overflow: scroll; */;overflow-x: auto;white-space: nowrap;" >
                        <thead>
                            <tr>
                              
                                <th><strong>Bodega</strong></th>
                                <th><strong>Cantidad</strong></th>
                                <th><strong>Proceso:</strong></th>
                           
                             
                            </tr>
                        </thead>
                        <tbody>
                         
                <?php

                             $sqlBodegas = "SELECT *, SUM(cantBodegas.cantidad) as total_cantidad, SUM(cantBodegas.proceso) as total_proceso, bodegas.id as id_bodega FROM cantBodegas,bodegas WHERE idProducto = '".$row['productos_codigo']."' AND bodegas.id = cantBodegas.idBodega AND bodegas.id_empresa =  '".$row['productos_id_empresa']."' ";
                         
                            
                            if (trim($_GET['bodega']) != '0') {
                                $sqlBodegas .= " AND bodegas.id='".$_GET['bodega']."'  ";
                            }
                            $sqlBodegas .= "  GROUP BY bodegas.id  ";
                            $qryBodegas = mysqli_query($conexion, $sqlBodegas);
                            $totalBodegas = 0;
                            $totalProceso = 0;// Variable para almacenar la suma total de cantidades
                            while ($rowBodegas = mysqli_fetch_array($qryBodegas)) {
                           
                    
                                $di_bod= $rowBodegas['id_bodega']; 
                                $totalBodegas += $rowBodegas['total_cantidad']; 
                                $totalProceso += $rowBodegas['total_proceso']; // Sumar la cantidad de cada bodega
                            ?>
                                
                                   <tr>
                                   
                                    <td><strong><?= $rowBodegas['detalle'] ?></strong></td>
                                    <td><strong><?= $rowBodegas['total_cantidad'] ?></strong></td>
                                    <td><strong><?= $rowBodegas['total_proceso'] ?></strong></td>    

                             
                            <?php
                   
                         
                           
                            }
                            ?>
                             </tr>
                            <!-- Agregar una fila para mostrar el total -->
                            <tr>
                              
                                <td class="text-end"><strong>Total Stock:</strong></td>
                                <td><?= number_format($totalBodegas, 2, '.', ',') ?></td>
                                <td><?= number_format($totalProceso, 2, '.', ',') ?></td>
                            </tr>
                        </tbody>
                    </table>
               </div>
          <table class="table table-hover table-bordered table-striped bg-white" >      
            
        <?php 
        } 
        $contador++;
    } 
    echo '</tbody>';
    echo '</table>';
         
}
 
        echo '<nav>';
        echo '<ul class="pagination">';
         
        if ($total_pages > 1) {
            if ($page != 1) {
                echo '<li class="page-item"><a class="page-link" onClick="listar_productos('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
                    echo '<li class="page-item"><a class="page-link" onClick="listar_productos('.$i.')" >'.$i.'</a></li>';
                }
            }
        
            if ($page != $total_pages) {
                echo '<li class="page-item"><a class="page-link" onClick="listar_productos('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
            }
        }
        echo '</ul>';
        echo '</nav>';





?>
<script>


</script>
