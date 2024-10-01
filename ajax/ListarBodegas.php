<?php 
// include "../conexion.php";
    session_start();
include('../conexion2.php');
  include "../clases/paginado_basico.php";
   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $criterio_usu_per= $_GET['criterio_usu_per'];
  $criterio_usu_tipo= $_GET['criterio_tipo'];
// echo "sesion id empresa==>".$sesion_id_empresa."<==>";

$result = $conexion->query('

SELECT COUNT(id) as ventas_id_venta
     
FROM
bodegas

WHERE `id_empresa`= "'.$sesion_id_empresa.'" '  );



$row = $result->fetch_assoc();
$num_total_rows = $row['ventas_id_venta'];

?>

    <table id="grillaBodegas" class="table table-hover table-bordered table-striped bg-white"  >
    <thead >
        <tr>
            <th><strong>Nombre Bodega</strong></th>
            <th><a href="javascript: agregarBodega();" title="Editar Producto"> 
            <button type="button" class="btn btn-default" aria-label="Left Align"><i class="fa fa-plus" aria-hidden="true"></i> 
            </th>
        </tr>
    </thead>


    <tbody>
    

<?php

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
        $start = ($page - 1) * NUM_ITEMS_BY_PAGE;
    }
    

    $total_pages = ceil($num_total_rows / NUM_ITEMS_BY_PAGE);
 

    $sql ="SELECT * FROM bodegas
     where `id_empresa`='".$sesion_id_empresa."' 
     ";

    $result=mysqli_query($conexion,$sql);

    
?>


    
<?php    
    if ($result->num_rows > 0) {
        $contadorBodegas=0;
        while ($row = $result->fetch_assoc()) {
            
            
            ?>
            
            
            
     <tr   class="bg-white">
            <td  ><input class="form-control" type="text" id="bodega<?php echo $contadorBodegas ?>" name="bodega<?php echo $contadorBodegas ?>" value="<?=$row['detalle']?>"></td>
            <td><a href="javascript: modificarBodega(<?=$row['id']?>,20,bodega<?php echo $contadorBodegas ?>.value);" title="Editar Producto"><span type="button" class="btn fa fa-save"></span></a></td>
              <td><a href="javascript: eliminarBodega('<?=$row['id']?>')" title="Eliminar Producto"><span type="button" class="btn fa fa-trash"></span></a></td>
    </tr>

             
    
            
            
            <?php
            $contadorBodegas++;
        }
        echo '</tbody>';
        
        ?>
    <tfoot>
        <tr>
            <td colspan="10"><strong >Cantidad: </strong><span  id="span_bodegas"></span> filas.</td>
        </tr>
    </tfoot>
        
        <?php
        
        
         echo '</table>';
    }
 
    echo '<nav>';
    echo '<ul class="pagination">';
 
    if ($total_pages > 1) {
        if ($page != 1) {
            echo '<li class="page-item"><a class="page-link" onClick="listar_productos('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
        }
 
        for ($i=1;$i<=$total_pages;$i++) {
            if ($page == $i) {
                echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
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
}



?>

