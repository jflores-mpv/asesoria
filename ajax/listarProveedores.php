<?php

	session_start();

$mostrar=$_GET['criterio_mostrar'];
    define('NUM_ITEMS_BY_PAGE20',$mostrar);
  $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	include "../conexion.php";
    include('../conexion2.php');
	include "../clases/paginado_basico.php";

        $cookie_permisos = $_COOKIE['permisos_cookie'];
        $sesion_permisos = $_SESSION["sesion_permisos"];


	$sql = "SELECT
     ciudades.`id_ciudad` AS ciudades_id_ciudad,
     ciudades.`ciudad` AS ciudades_ciudad,
     ciudades.`id_provincia` AS ciudades_id_provincia,
     paises.`id_pais` AS paises_id_pais,
     paises.`pais` AS paises_pais,
     proveedores.`id_proveedor` AS proveedores_id_proveedor,
     proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
     proveedores.`nombre` AS proveedores_nombre,
     proveedores.`direccion` AS proveedores_direccion,
     proveedores.`ruc` AS proveedores_ruc,
     proveedores.`telefono` AS proveedores_telefono,
     proveedores.`movil` AS proveedores_movil,
     proveedores.`fax` AS proveedores_fax,
     proveedores.`email` AS proveedores_email,
     proveedores.`web` AS proveedores_web,
     proveedores.`observaciones` AS proveedores_observaciones,
     proveedores.`id_ciudad` AS proveedores_id_ciudad,
     proveedores.`id_plan_cuenta` AS proveedores_id_plan_cuenta,
     provincias.`id_provincia` AS provincias_id_provincia,
     provincias.`provincia` AS provincias_provincia,
     provincias.`id_pais` AS provincias_id_pais
FROM
     `ciudades` ciudades INNER JOIN `proveedores` proveedores ON ciudades.`id_ciudad` = proveedores.`id_ciudad`
     INNER JOIN `provincias` provincias ON ciudades.`id_provincia` = provincias.`id_provincia`
     INNER JOIN `paises` paises ON provincias.`id_pais` = paises.`id_pais` and  proveedores.`id_empresa`='".$sesion_id_empresa."' ";
	if (isset($_GET['criterio_usu_per']))
		$sql .= " where proveedores.`nombre_comercial` like '%".$_GET['criterio_usu_per']."%' or  proveedores.`ruc` like '%".$_GET['criterio_usu_per']."%' ";
	              
   
	                
        if (isset($_GET['criterio_ordenar_por']))
		$sql .= sprintf(" order by %s %s", $_GET['criterio_ordenar_por'],$_GET['criterio_orden']);
	else
		$sql .= " order by proveedores.`nombre_comercial` asc";
       // echo $sql;
       $result = $conexion->query($sql);
       $num_total_rows =$result->num_rows;
       $total_pages=0;  $total_saldo = 0;
       $total_valor = 0;
      
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
              $start = ($page - 1) * NUM_ITEMS_BY_PAGE20;
          }
      
           $total_pages = ceil($num_total_rows / NUM_ITEMS_BY_PAGE20);
          $sql.=" LIMIT $start, ".NUM_ITEMS_BY_PAGE20;
          $result2 = $conexion->query($sql);
        
?>
<table id="grilla" class="table table-bordered table-striped" >
    <thead>
        <tr>

            <th><strong>Ruc</strong></th>
            <th><strong>Nombre</strong></th>
            <th><strong>Direcci&oacute;n</strong></th>
            <th><strong>T&eacute;lefono</strong></th>
            <th><strong>Ciudad</strong></th>
            <th colspan="2"><strong>Acciones</strong></th>

            <th>
                <?php
                if($cookie_permisos == "Lectura y Escritura" || $sesion_permisos == "Lectura y Escritura"){
                    ?><a href="javascript: nuevoProveedor();" title="Agregar nuevo Proveedor"><img alt="" src="images/add.png"></a><?php
                }else{
                    ?>  <?php
                }
                ?>
            </th>
            
        </tr>
    </thead>
<tbody>
    <?php
    while ($row = $result2->fetch_assoc()) {
    ?>
        <tr id="tr_<?= $row['proveedores_id_proveedor'] ?>">
            <td><?php echo $row['proveedores_ruc']; ?></td>
            <td><?php echo utf8_encode($row['proveedores_nombre']); ?></td>
            <td><?php echo utf8_encode($row['proveedores_direccion']); ?></td>
            <td><?php echo $row['proveedores_telefono']; ?></td>
            <td><?php echo utf8_encode($row['ciudades_ciudad']); ?></td>
            <td>
                <a href="nuevoProveedor.php?id_proveedor=<?php echo $row['proveedores_id_proveedor']; ?>" title="Editar Proveedor" class="btn btn-info text-white"><i class="fa fa-edit"></i></a>
            </td>
            <td>
          <a href="javascript: eliminarProveedor(<?php echo $row['proveedores_id_proveedor']; ?>, 3, <?php echo $row['proveedores_id_plan_cuenta']; ?>);" title="Eliminar Proveedor" class="btn btn-danger">
                <i class="fa fa-trash-o"></i>
            </a>

               
            </td>
        </tr>
    <?php } ?>
</tbody>

</table>


<?php
}
     echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_proveedores('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_proveedores('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_proveedores('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';
// echo '<nav>';
// echo '<ul class="pagination">';
// // echo 'total=>'.$total_pages;
// if ($total_pages > 1) {
//     if ($page != 1) {
//         echo '<li class="page-item"><a class="page-link" onClick="listar_proveedores('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
//     }

//     for ($i=1;$i<=$total_pages;$i++) {
//         if ($page == $i) {
//             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
//         } else {
//             echo '<li class="page-item"><a class="page-link" onClick="listar_proveedores('.$i.')" >'.$i.'</a></li>';
//         }
//     }

//     if ($page != $total_pages) {
//         echo '<li class="page-item"><a class="page-link" onClick="listar_proveedores('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
//     }
// }
// echo '</ul>';
// echo '</nav>';

?>