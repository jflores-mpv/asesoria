<?php

        //Start session
	session_start();

	include "../conexion.php";
	include "../clases/paginado_basico.php";
	include "../clases/paginado_PHPPaging.lib.php";

	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	$paging = new PHPPaging;
	$sql = "SELECT
	costos_cabecera.`id_costo` AS id_costo,
	costos_cabecera.`numero` AS numero_receta,
	costos_cabecera.`producto` AS id_producto_receta,
	costos_cabecera.`total_cd` AS costo_receta,
	
	productos.`id_producto` AS id_producto,
	productos.`producto` AS nombre_receta
	
	
	
FROM
     `costos_cabecera` costos_cabecera INNER JOIN `productos` productos ON productos.`id_producto` = costos_cabecera.`producto` 
     where costos_cabecera.empresa='".$sesion_id_empresa."' ";


	$paging->agregarConsulta($sql); 
	$paging->div('div_listar_costos');
	$paging->modo('desarrollo'); 
	if (isset($_GET['criterio_mostrar']))
		$paging->porPagina(fn_filtro((int)$_GET['criterio_mostrar']));
	$paging->verPost(true);
	$paging->mantenerVar("criterio_usu_per", "criterio_ordenar_por", "criterio_orden", "criterio_mostrar");
	$paging->ejecutar();
        
       
?>

    <?php
        $contador = 0;
        while ($row = $paging->fetchResultado()){
        $contador++;
            
        if($contador%2==0){
    ?>
        
        <button class="accordion row ">
          
                <div class="col-lg-1"><?php echo $contador;?></div>
                <div class="col-lg-8"><h5><?=$row['nombre_receta']?></h5></div>
                <div class="col-lg-3"><?=$row['costo_receta']?></div>
            
        </button>  
        
        
<div class="row panel border-bottom" >
            <?php 
       	$sql2 = "SELECT
	costos_detalle.`id_detalle_costo` AS id_detalle_costo,
	costos_detalle.`idProdProduccion` AS 	idProdProduccion,
	costos_detalle.`id_producto` AS id_producto_receta,
	costos_detalle.`cantidad` AS cantidad,
	costos_detalle.`costo` AS costo,
	costos_detalle.`total` AS total,
	
	productos.`id_producto` AS id_producto,
	productos.`producto` AS nombre_producto
	
	
	
FROM
     `costos_detalle` costos_detalle INNER JOIN `productos` productos ON productos.`id_producto` = costos_detalle.`id_producto` 
     where costos_detalle.id_costo='".$row['id_costo']."' ";

     
        $resp2 = mysql_query($sql2);
        while($row2=mysql_fetch_array($resp2)){     ?>
        
       
            
                    <div class="col-lg-6 border-bottom py-3"><?=$row2['nombre_producto']?></div>
                    <div class="col-lg-2 border-left py-3"><?=$row2['cantidad']?></div> 
                    <div class="col-lg-2 border-left py-3"><?=$row2['costo']?></div>
                    <div class="col-lg-2 border-left py-3"><?=$row2['total']?></div>
            
          
         <?php }  }  ?>
         
         </div>
         <?php 
         if($contador%2==1){  ?>
       
        <button class="accordion row">
                <div class="col-lg-1"><?php echo $contador;?></div>
                <div class="col-lg-8"><h5><?=$row['nombre_receta']?></h5></div>
                <div class="col-lg-3"><?=$row['costo_receta']?></div>
        </button> 
       
       
    <div class="row panel border-bottom" >
            <?php 
       	$sql2 = "SELECT
	costos_detalle.`id_detalle_costo` AS id_detalle_costo,
	costos_detalle.`idProdProduccion` AS 	idProdProduccion,
	costos_detalle.`id_producto` AS id_producto_receta,
	costos_detalle.`cantidad` AS cantidad,
	costos_detalle.`costo` AS costo,
	costos_detalle.`total` AS total,
	
	productos.`id_producto` AS id_producto,
	productos.`producto` AS nombre_producto
	
	
	
FROM
     `costos_detalle` costos_detalle INNER JOIN `productos` productos ON productos.`id_producto` = costos_detalle.`id_producto` 
     where costos_detalle.id_costo='".$row['id_costo']."' ";

     
        $resp2 = mysql_query($sql2);
        while($row2=mysql_fetch_array($resp2)){     ?>
        
         
           
                    <div class="col-lg-6 py-3 "><?=$row2['nombre_producto']?></div>
                    <div class="col-lg-2 border-left  py-3"><?=$row2['cantidad']?></div> 
                    <div class="col-lg-2 border-left py-3"><?=$row2['costo']?></div>
                    <div class="col-lg-2 border-left py-3"><?=$row2['total']?></div>
            
         
        
        <?php } }    ?>
        </div>
        
        <?php }  ?>
        
 



<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "flex") {
      panel.style.display = "none";
    } else {
      panel.style.display = "flex";
    }
  });
}
    function xml(id){
        window.open('reportes/xmlRetencionCompra.php?id='+ id);
    }

    function abrirPdfFacturaCompra(id_compra){
    miUrl = "reportes/rptFacturaCompra.php?id_compra="+id_compra;
    window.open(miUrl,'facturaVenta','width=600, height=600, scrollbars=NO, titlebar=no');
}

</script>