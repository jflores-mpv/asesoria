<?php 
// include "../conexion.php";
    session_start();
    include('../conexion2.php');
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
 $emision_ambiente = $_SESSION["emision_ambiente"];
    // echo "ESTADOS==".$estado;
    // echo $emision_ambiente;
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $tipoDoc= $_GET['tipoDoc'];
    $txtUsuarios= $_GET['txtUsuarios'];
    $estado= $_GET['estado'];
    
  
    // echo "sesion id empresa==>".$sesion_id_empresa."<==>";
    $result = $conexion->query('
        SELECT COUNT(id) as anexosId
             
        FROM
            anexosCreados
     
     where anexosCreados.`id_empresa`= '.$sesion_id_empresa.' ');
     
   

$row = $result->fetch_assoc();
$num_total_rows = $row['anexosId'];



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

    $sql2 ="SELECT *
     
     
FROM
     `anexosCreados` anexosCreados

     
     where anexosCreados.`id_empresa`='".$sesion_id_empresa."'  ";
    
    $sql2.= "ORDER BY anexosCreados.id DESC LIMIT ".$start." , ".NUM_ITEMS_BY_PAGE ;


    $result=mysqli_query($conexion,$sql2);
    

    if ($result->num_rows > 0) {
        $Contador ='0'; ?>
        
                <div class="row accordion pt-1 my-2 rounded <?php echo $bgestado?>">
                    <div class="col-lg-11">
                        <div class="row bg-white rounded m-2 p-2">
                                <div class="col-lg-1 ">#</div>
                                <div class="col-lg-1 ">Mes</div>
                                <div class="col-lg-1 " >Año</div>
                                <div class="col-lg-4 ">Fecha</div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-2"></div>
                            
                       
                        </div>
                    </div>
                </div>
        
        
    <?php    
        
        
        while ($row = $result->fetch_assoc()) {
            
               $mes = $row['mes'];
                $anio = $row['anio'];
            
            ?>
            
            
                <div class="row accordion pt-1 my-2 rounded <?php echo $bgestado?>">
                    <div class="col-lg-11">
                        <div class="row bg-white rounded m-2 p-2">
                                <div class="col-lg-1 border"><?=$Contador ?></div>
                                <div class="col-lg-1 border"><?=$row['mes']?></div>
                                <div class="col-lg-1 border"><?=$row['anio']?></div>
                                <div class="col-lg-4 border"><?=$row['fecha']?></div>
                                <div class="col-lg-2"><a  href='javascript: descargarXML("<?php echo 'sql/'.$row['url'].'.xml' ?>");' value="Generar pdf" class="btn btn-success btn-sm w-100">XML</a></div>
                                <div class="col-lg-2"><a  href='javascript: eliminarATS("<?php echo $row['url'].'.xml' ?>",2,"<?php echo $row['id'] ?>");' value="Generar pdf" class="btn btn-success btn-sm w-100">Eliminar</a></div>
                                <!--<div class="col-lg-2"><a  href='javascript: descargarArchivo("sql/<?php echo $row['url'].'.xml' ?>");' value="Generar pdf" class="btn btn-success btn-sm w-100">Eliminar</a></div>-->

                       
                        </div>
                    </div>
                </div>
            
            
            <?php    $Contador++ ; 
            
            
             }   }
    
    echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

    // echo '<nav>';
    // echo '<ul class="pagination">';
 
    // if ($total_pages > 1) {
    //     if ($page != 1) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    //     }
 
    //     for ($i=1;$i<=$total_pages;$i++) {
    //         if ($page == $i) {
    //             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
    //         } else {
    //             echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.$i.')" >'.$i.'</a></li>';
    //         }
    //     }
 
    //     if ($page != $total_pages) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    //     }
    // }
    // echo '</ul>';
    // echo '</nav>';
}else{ ?>
        
        <div class="row">
            <div class="col-lg-4 offset-lg-4  p-5 text-center bg-warning rounded border-0">
                <h5>No existen ats Realizadas</h5>
            </div>
        </div>
        
<?php    }



?>

<script>


        function descargarXML(miUrl) {
            console.log("url",miUrl);
            window.open(miUrl,'facturaVenta','width=600, height=600, scrollbars=NO, titlebar=no');
        } 

        


</script>
    