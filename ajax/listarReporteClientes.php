<?php 
error_reporting(0);
// include "../conexion.php";
    session_start();
include('../conexion.php');
  include "../clases/paginado_basico.php";
   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
   $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
     $dominio = $_SERVER['SERVER_NAME'];
   
 $criterio_usu_per= $_GET['txtBuscar'];
  $criterio_usu_tipo= $_GET['criterio_tipo'];
  $num_registros = $_GET['criterio_mostrar'];
$estado_cliente = $_GET['estado_cliente'];
$estado_general_cliente =$_GET['estado_general_cliente'];
$txtBuscarVendedor = trim($_GET['txtBuscarVendedor']);
$sql ="SELECT
     clientes.`id_cliente` AS clientes_id_cliente,
     clientes.`nombre` AS clientes_nombre,
     clientes.`apellido` AS clientes_apellido,
     clientes.`direccion` AS clientes_direccion,
     clientes.`cedula` AS clientes_cedula,
     clientes.`telefono` AS clientes_telefono,
     clientes.`movil` AS clientes_movil,
     clientes.`fecha_nacimiento` AS clientes_fecha_nacimiento,
     clientes.`email` AS clientes_email,
     clientes.`estado` AS clientes_estado,
     clientes.`id_ciudad` AS clientes_id_ciudad,
     clientes.`fecha_registro` AS clientes_fecha_registro,
     clientes.`numero_cargas` AS clientes_numero_cargas,
     clientes.`estado_civil` AS clientes_estado_civil,
     clientes.`tipo` AS clientes_tipo,
     clientes.`numero_casa` AS clientes_numero_casa,
     clientes.`id_empresa` AS clientes_id_empresa,
     clientes.`id_vendedor` AS clientes_id_vendedor,
     clientes.`caracter_identificacion` AS clientes_caracter_identificacion,
     clientes.`reponsable` AS clientes_reponsable,
     clientes.`limite_credito` AS clientes_limite_credito,
     clientes.`descuento` AS clientes_descuento,
     clientes.`dias_plazo` AS clientes_dias_plazo,
     clientes.`tipo_precio` AS clientes_tipo_precio,
      clientes.`fecha_afiliacion` AS clientes_fecha_afiliacion,
     clientes.`estado_afiliado`,
      clientes.numero_afiliado,
      vendedores.nombre as vendedor_nombre,
       vendedores.apellidos as vendedor_apellido
FROM
     `clientes` clientes
     LEFT JOIN ciudades ON  ciudades.id_ciudad = clientes.id_ciudad
     LEFT JOIN vendedores ON  vendedores.id_vendedor = clientes.id_vendedor
 where clientes.`id_empresa`='".$sesion_id_empresa."'
     ";  
      
     
   	if ($criterio_usu_per!=''){
        $sql .= " and CONCAT (clientes.nombre, ' ',clientes.apellido )  like '%".trim($criterio_usu_per)."%'   "; 
    }

        if ($txtBuscarVendedor!=''){
            $sql .= " and CONCAT (vendedores.nombre, ' ',vendedores.apellidos )  like '%".trim($txtBuscarVendedor)."%'   "; 
        }
    
      
   

    if($_GET["txtProvincias"]!='0'){
        $sql .= " and ciudades.`id_provincia` =".$_GET["txtProvincias"]." "; 

        if($_GET["txtCiudad"]!='0'){
            $sql .= " and ciudades.`id_ciudad` =".$_GET["txtCiudad"]." ";
        }
    }

    if(trim($_GET["txtCedulaCliente"])!=''){
        $sql .= " and clientes.`cedula` like '%".$_GET["txtCedulaCliente"]."%' "; 
    }
    
    if($estado_general_cliente!='0' && ($sesion_id_empresa==116 || $sesion_id_empresa==1827 ) ){
        
        if($estado_general_cliente == 'CLIENTES'){
            
            if($estado_cliente=='0'){
                $sql .= " AND  UPPER(clientes.`estado_afiliado`) = 'DESAFILIADO' "; 
            }else{
                $sql .= " and clientes.`estado` = '".$estado_cliente."' AND  UPPER(clientes.`estado_afiliado`) = 'DESAFILIADO' "; 
            }
            
        }
        
        if($estado_general_cliente == 'AFILIADOS'){
            if($estado_cliente=='0'){
                $sql .= " AND  UPPER(clientes.`estado_afiliado`) != 'DESAFILIADO' "; 
            }else{
                 $sql .= " and UPPER(clientes.`estado_afiliado`) = '".$estado_cliente."'  "; 
            }
             
        }
        
    }
    
//   if ($sesion_id_empresa==116  ){
//       echo $sql;

//   }

// echo $sql;

$result = mysql_query($sql);
$num_total_rows = mysql_num_rows($result);


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
        $start = ($page - 1) * $num_registros;
    }
    
    // echo "start===>".$start;
    //calculo el total de paginas
    $total_pages = ceil($num_total_rows / $num_registros);
 

    
     $sql.= " order by ".$_GET['criterio_ordenar_por']."  ".$_GET['criterio_orden']."   LIMIT ".$start." , ".$num_registros ;
    //  echo $sql;
    $result=mysql_query($sql);

?>

    <table id="grilla" class="table table-hover table-bordered table-striped bg-white table-condensed"  >
    <thead >
        <tr><th></th>
            <th><strong>Nombre</strong></th>
            <th><strong>Identificaci&oacute;n</strong></th>
            <th><strong>Direcci&oacute;n</strong></th>
            <th><strong>Tel&eacute;fono</strong></th>
            <th><strong>Email</strong></th>
            <th><strong>Vendedor</strong></th>
        </tr>
    </thead>


    <tbody>
    
<?php    
    if ($num_total_rows> 0) {
        
        while ($row = mysql_fetch_array($result)) {
            
            
            ?>
   
     	  <tr class="odd" id="tr_<?=$row['clientes_id_cliente']?>">
          <td><?=$row['clientes_caracter_identificacion']?></td>
            <td><?=utf8_encode($row['clientes_nombre'])?> <?=utf8_encode($row['clientes_apellido'])?></td>
     
            <td><?=$row['clientes_cedula']?></td>
            <td><?=utf8_encode($row['clientes_direccion'])?></td>
            <td><?=$row['clientes_telefono']?></td>
            <td ><?=$row['clientes_email']?></td>
         <td ><?=$row['vendedor_nombre'].' '.$row['vendedor_apellido']?></td>
          

  <td><a href="../reportes/crearPdfPerfil.php?id_cliente=<?=$row['clientes_id_cliente']?>" title="Reporte cliente"><button type="button" class="btn btn-default" aria-label="Left Align">
  <i class="fa fa-download" aria-hidden="true"></i></button></a></td>


    <td><a href="javascript: modificar_cliente(<?=$row['clientes_id_cliente']?>,7);" title="Editar cliente"><button type="button" class="btn btn-default" aria-label="Left Align"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a></td>



<td><a href="javascript: eliminarCliente(<?=$row['clientes_id_cliente']?>,8);" title="Eliminar cliente"><button type="button" class="btn btn-default" aria-label="Left Align">
<i class="fa fa-trash-o" aria-hidden="true"></i>

</button></a>
</td>

        </tr>
            
            
            <?php
            
        }
        echo '</tbody>';
         echo '</table>';
    }
 
      echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_clientes('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_clientes('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_clientes('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

}



?>

