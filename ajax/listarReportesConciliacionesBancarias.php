<?php 
// include "../conexion.php";
    session_start();
     include ("../conexion.php");
    include('../conexion2.php');
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $emision_ambiente = $_SESSION["emision_ambiente"];
      $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
  
     $emision_ambiente;
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $tipoDoc= $_GET['tipoDoc'];

    $estado= $_GET['estado'];
    $fecha_desde = $_GET['txtFechaDesde'];
    $fecha_hasta = $_GET['txtFechaHasta'];
 $criterio =$_GET['criterio_ordenar_por'];
 $orden = $_GET['criterio_orden'];
      $numeroRegistros= $_GET['criterio_mostrar'];
  $txtClientes= $_GET['txtClientes'];
  $banco  =isset( $_GET['cmbNombreCuentaCB'])?$_GET['cmbNombreCuentaCB'] : 0;
    // echo "sesion id empresa==>".$sesion_id_empresa."<==>";
     $sql2="SELECT
                 detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
                 detalle_bancos.`tipo_documento` AS detalle_bancos_tipo_documento,
                 detalle_bancos.`numero_documento` AS detalle_bancos_numero_documento,
                 detalle_bancos.`detalle` AS detalle_bancos_detalle,
                 detalle_bancos.`valor` AS detalle_bancos_valor,
                 detalle_bancos.`fecha_cobro` AS detalle_bancos_fecha_cobro,
                 detalle_bancos.`fecha_vencimiento` AS detalle_bancos_fecha_vencimiento,
                 detalle_bancos.`id_bancos` AS detalle_bancos_id_bancos,
                 detalle_bancos.`estado` AS detalle_bancos_estado,
                 detalle_bancos.`id_libro_diario` AS detalle_bancos_id_libro_diario,
                 bancos.`id_bancos` AS bancos_id_bancos,
                 bancos.`id_plan_cuenta` AS bancos_id_plan_cuenta,
                 bancos.`saldo_conciliado` AS bancos_saldo_conciliado,
                 bancos.`id_periodo_contable` AS bancos_id_periodo_contable,
                   plan_cuentas.nombre as nombre_Banco
            FROM
                 `bancos` bancos 
        INNER JOIN `detalle_bancos` detalle_bancos ON bancos.`id_bancos` = detalle_bancos.`id_bancos`
        INNER JOIN plan_cuentas ON
 plan_cuentas.id_plan_cuenta = bancos.id_plan_cuenta
        WHERE  bancos.`id_periodo_contable`='".$sesion_id_periodo_contable."'";
    
    
	
	if ($estado!='0'){
	    $estado = ($estado==1)?'Conciliado':'No Conciliado';
		$sql2.= " and  detalle_bancos.`estado` ='".$estado."' "; 
	} 
     
   
	
	if ($banco!='0'){
		$sql2.= " and  bancos.`id_bancos` ='".$banco."' "; 
	} 
	
	if ($fecha_desde !='' && $fecha_hasta !='' ){
		$sql2.= " and DATE_FORMAT( detalle_bancos.`fecha_cobro`,'%Y-%m-%d')>=DATE_FORMAT('".$fecha_desde."','%Y-%m-%d') AND DATE_FORMAT( detalle_bancos.`fecha_cobro`,'%Y-%m-%d')<=DATE_FORMAT('".$fecha_hasta."','%Y-%m-%d') "; 
	}
	

	

     
    $result = mysql_query($sql2);
        $numero_filas = mysql_num_rows($result); 


//   if($sesion_id_empresa==41){
        // echo $sql2;
//     }


if ($numero_filas > 0) {
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
    $total_pages = ceil($numero_filas / $numeroRegistros);
 
//   echo $estado."</br>";
   
	$criterio_orden = $_GET['criterio_orden'];
	$criterio_ordenar_por = $_GET['criterio_ordenar_por'];
	
	
    $sql2.= "ORDER BY $criterio $orden LIMIT ".$start." , ".$numeroRegistros ;
     
    // echo $sql2;
    
    // if($sesion_id_empresa==41){
        // echo $sql2;
    // }
    $result=mysqli_query($conexion,$sql2);
    

    if ($result->num_rows > 0) {
        
          
        
        // if ($row_cnt> 0) {
        
        // echo '<ul class="row bg-danger">';
        while ($row = $result->fetch_assoc()) {
            
               $ventas_estado_anulacion = $row['detalle_bancos_estado'];
            //  echo "estado".$ventas_estado_anulacion;
            if($ventas_estado_anulacion!='Conciliado'){
                $bgestado='bg-ligth border';
            }else {
                $bgestado='bg-white';
            }
            
            ?>
            
            
          <div class="row accordion pt-1 my-2 rounded <?php echo $bgestado?>">
            <div class="col-lg-8">
                  
                    <div class="row ">
                
                        <!--<div class="col-lg-1"><?php echo '#'.$row['detalle_bancos_numero_documento']?></div>-->
                        <div class="col-lg-3"><?=utf8_encode($row['detalle_bancos_tipo_documento'])?></div>
                        <div class="col-lg-1 text-center">$<?=$row['detalle_bancos_valor']?></div>
                        <div class="col-lg-2"><?=$row['detalle_bancos_fecha_cobro']?></div>
                        <div class="col-lg-2"><?=date('d-m-Y',strtotime($row['detalle_bancos_fecha_vencimiento']))?></div>	
             
                    	<div class="col-lg-3 "><?=$row['detalle_bancos_detalle']?></div>
                    
                </div>
              
                
                <div class="row">
                 <div class="col-lg-2">Estado:</div>
                        <div class="col-lg-2"><?=$row['detalle_bancos_estado']?></div>
                    	<div class="col-lg-3 "><?=$row['nombre_Banco']?></div>
                
              
                  </div> 
            </div>
          
            
       
        </div>
            
            
            <?php
            
        }
        // echo '</ul>';
    }
 
    echo '<nav>';
    echo '<ul class="pagination">';
 
    if ($total_pages > 1) {
        if ($page != 1) {
            echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_pedidos('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
        }
 
        for ($i=1;$i<=$total_pages;$i++) {
            if ($page == $i) {
                echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_pedidos('.$i.')" >'.$i.'</a></li>';
            }
        }
 
        if ($page != $total_pages) {
            echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_pedidos('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
        }
    }
    echo '</ul>';
    echo '</nav>';
}else{ ?>
        
        <div class="row">
            <div class="col-lg-4 offset-lg-4  p-5 text-center bg-warning rounded border-0">
                <h5>No existen Conciliaciones Bancarias Realizados</h5>
            </div>
        </div>
        
<?php    }



?>

<script>

        function pdfPedidos(idPedido){
        let formato = 'rptPedidos.php';
         miUrl = "reportes/"+formato+"?txtComprobanteNumero="+idPedido;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        }
        

</script>
    