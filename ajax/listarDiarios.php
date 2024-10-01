<?php
// error_reporting(E_ALL);

// // Mostrar los errores en el navegador
// ini_set('display_errors', 1);

//Start session
session_start(); 


include('../conexion.php');
include('../conexion2.php');
include "../clases/paginado_basico.php";

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];


$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

$criterio_usu_per= $_GET['glosa'];
$criterio_ordenar_por = $_GET['criterio_ordenar_por'];
$criterio_tipo= $_GET['criterio_tipo']; 
$criterio_mostrar = $_GET['criterio_mostrar'];
$criterio_orden = $_GET['criterio_orden'];


$numeroRegistros= $_GET['criterio_mostrar'];
// define('NUM_ITEMS_BY_PAGE', 15);
// $sesion_id_empresa=41;



$sql ="SELECT libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario, 
libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
libro_diario.`descripcion` AS libro_diario_descripcion,
libro_diario.`total_debe` AS libro_diario_total_debe,
libro_diario.`total_haber` AS libro_diario_total_haber,
libro_diario.`fecha` AS libro_diario_fecha, libro_diario.`tipo_comprobante` AS libro_diario_tipo_comprobante
    FROM `libro_diario` libro_diario 

WHERE libro_diario.`id_periodo_contable`='" .$sesion_id_periodo_contable."' ";
  
 
   if ($criterio_usu_per!=''){
    $sql .= "  and libro_diario.`descripcion` like '%".$criterio_usu_per."%' "; 
}
 if ($criterio_usu_per!=''){
    $sql .= "  and libro_diario.`descripcion` like '%".$criterio_usu_per."%' "; 
}
if ($criterio_tipo!=''){
    $sql .= "   and libro_diario.`tipo_comprobante` = '".$criterio_tipo."' "; 
}
$result = mysql_query($sql);
$num_total_rows = mysql_num_rows($result); 

// echo $sql;


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
        
        // echo "start===>".$start;
        //calculo el total de paginas
        $total_pages = ceil($num_total_rows / $numeroRegistros);
     
    

        // if ($criterio_ordenar_por!=''){
        //     $sql .= "  order by  $criterio_ordenar_por $criterio_orden"; 
        // }
         
        //  $sql.= "  ORDER BY $criterio_ordenar_por $criterio_orden LIMIT ".$start." , ".$numeroRegistros ;
        
        $sql.= "  ORDER BY  libro_diario_numero_asiento $criterio_orden   LIMIT ".$start.", ".$numeroRegistros;
        // echo $sql;
        $result=mysqli_query($conexion,$sql);
        // $row_cnt = mysqli_num_rows($conexion,$sql);
        // echo "row_cnt==>".$row_cnt;
        

        
    ?>
    
        <table id="grilla" class="table table-hover table-bordered table-striped bg-white"  >
        <thead >
        <tr>
            <th><strong>#</strong></th>
            <!--<th><strong>Id Diario</strong></th>-->
            <th><strong>Fecha</strong></th>
			<th><strong>Asiento</strong></th>
            <th><strong>Descripci&oacute;n</strong></th>
            <th><strong>Debe</strong></th>
            <th><strong>Haber</strong></th>
			<th><strong>Editar</strong></th>       
			<th><strong>Eliminar</strong></th>        
        </tr>
        </thead>
    
    
        <tbody>
        
    <?php    
        if ($result->num_rows > 0) {
            $contador=1+($page*$numeroRegistros)-$numeroRegistros;
            while ($row = $result->fetch_assoc()) {
                
                
                ?>
        <tr id="tr_<?=$row['libro_diario_id_libro_diario']?>"  class="bg-white">
            <td  ><?php echo $contador;?></td>
            <!--<td  ><?=$row['libro_diario_id_libro_diario']?></td>-->
            <td><?=$row['libro_diario_fecha']?></td>
            <td  ><?=$row['libro_diario_numero_asiento']?></td>
			<td  ><?=$row['libro_diario_descripcion']?></td>
			
            <td><?=$row['libro_diario_total_debe']?></td>
            <td><?=$row['libro_diario_total_haber']?></td>
            
            <td>
            <?php            
            $cookie_tipo = $_COOKIE['tipo_cookie'];
            $sesion_tipo = $_SESSION["sesion_tipo"];
             if($cookie_tipo=="Administrador" || $sesion_tipo=="Administrador")
                 {					
			      ?><a href="asientosContables.php?numero_asientox=<?=$row['libro_diario_numero_asiento']?>"; title="Editar Diario1"><span type="button" class="btn  fa fa-edit"></a><?php
            	 }
             if($cookie_tipo=="Empleado" || $sesion_tipo=="Empleado")
                 {
                    ?>  <?php
                 }
             ?>
            </td>
          <td>
            <?php            
            $cookie_tipo = $_COOKIE['tipo_cookie'];
            $sesion_tipo = $_SESSION["sesion_tipo"];
     
	if($cookie_tipo=="Administrador" || $sesion_tipo=="Administrador")
                 {
                  ?><a href="javascript: eliminarAsiento(<?=$row['libro_diario_id_libro_diario']?>);" title="Eliminar Producto"><button type="button" class="btn btn-danger fa fa-trash text-white"></button></a><?php
                 }
    if($cookie_tipo=="Empleado" || $sesion_tipo=="Empleado")
                 {
                    ?>  <?php
                 }
             ?>
            </td>
            <!--<td><a href="javascript: bodegas(<?=$row['libro_diario_id_libro_diario']?>);" title="Eliminar Producto"><button type="button" class="btn btn-warning fa fa-list text-white"></button></a></td> -->
        </tr>
                
                
                <?php
                $contador++;
            }
            echo '</tbody>';
             echo '</table>';
        }



echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_diarios('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_diarios('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_diarios('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';


    }
    
    ?>
    