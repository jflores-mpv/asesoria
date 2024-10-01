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

    $sql="SELECT
    costos_cabecera.`id_costo`,
    costos_cabecera.`numero`,
    costos_cabecera.`fecha`,
    costos_cabecera.`total_cd`,
    costos_cabecera.`total_ci`,
    costos_cabecera.`descripcion`,
    costos_cabecera.`producto`,
    costos_cabecera.`estado`,
    costos_cabecera.`fecha_anulacion`,
    productos.id_producto,
    productos.producto
FROM
    `costos_cabecera`
INNER JOIN costos_detalle ON costos_detalle.id_costo =costos_cabecera.id_costo
INNER JOIN productos ON productos.id_producto = costos_cabecera.`producto`
WHERE
    costos_cabecera.empresa =$sesion_id_empresa";
    
    if($_GET['txtFechaDesde']!='' && $_GET['txtFechaHasta'] !=''){
        $sql .=" AND  DATE_FORMAT( costos_cabecera.`fecha` ,'%Y-%m-%d') >='".$_GET['txtFechaDesde']."' AND DATE_FORMAT(costos_cabecera.`fecha`,'%Y-%m-%d') <='".$_GET['txtFechaHasta']."' ";
    }
    if($_GET['txtClientes']!='0' ){
        $sql .=" and productos.id_producto =".$_GET['txtClientes']." ";
    }
   
    $sql .=" GROUP BY   costos_cabecera.`numero` ";

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

        $sql.= " ORDER BY costos_cabecera.`numero` ".  $_GET["criterio_orden"]." LIMIT ".$start." , ".$numeroRegistros ;
   

    
    $result=mysqli_query($conexion,$sql);
    

    if ($num_total_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            
               $ventas_estado_anulacion = 'Activo';
            //  echo "estado".$ventas_estado_anulacion;
            if($ventas_estado_anulacion!='Activo'){
                $bgestado='bg-ligth border';
            }else {
                $bgestado='bg-white';
            }
            ?>
            
          <div class="row accordion pt-1 my-2 rounded <?php echo $bgestado?>">
            <div class="col-lg-10">
                  
                    <div class="row ">
                    <div class="col-lg-4">Costos de producci&oacute;n # <strong> <?=utf8_encode($row['numero'])?> </strong> </div>
                   
                      <div class="col-lg-5 ">Receta: <strong> <?=$row['producto']?> </strong> </div>
                        
                         
                        <div class="col-lg-3">Fecha de creaci&oacute;n :<strong> <?=date('d-m-Y',strtotime($row['fecha']))?></strong></div>	
                
                </div>
         
            </div>
            <div class="col-lg-2">
                <div class="row">
 
            <div class="col-lg-12 mt-2"><a  onclick="generarPDF_costos('<?php echo $row['id_costo']; ?>')" value="Generar XML" class="btn btn-success btn-sm w-100">PDF</a></div> 
               </div>
            </div>
        </div>
            <?php
        }
    }
 
      echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_costos('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_costos('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_costos('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

    // echo '<nav>';
    // echo '<ul class="pagination">';
 
    // if ($total_pages > 1) {
    //     if ($page != 1) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_costos('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    //     }
 
    //     for ($i=1;$i<=$total_pages;$i++) {
    //         if ($page == $i) {
    //             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
    //         } else {
    //             echo '<li class="page-item"><a class="page-link" onClick="listar_costos('.$i.')" >'.$i.'</a></li>';
    //         }
    //     }
 
    //     if ($page != $total_pages) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_costos('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    //     }
    // }
    // echo '</ul>';
    // echo '</nav>';
}else{ ?>
        <div class="row">
            <div class="col-lg-4 offset-lg-4  p-5 text-center bg-warning rounded border-0">
                 <h5>No existe reportes de costos .</h5>
            </div>
        </div>
        
<?php    }
?>