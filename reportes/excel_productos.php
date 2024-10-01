<?php
//ob_end_clean();
//Start session
error_reporting(0);
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
	$sesion_id_periodo_contable =$_GET['Periodo'];
	

	
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=inventario".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");

	
if($_GET['criterio_valor'] == 'Cantidades'){
    
    
    $sql = "SELECT
     categorias.`id_categoria` AS categorias_id_categoria,
     categorias.`categoria` AS categorias_categoria,
     productos.`id_producto` AS productos_id_producto,
     productos.`producto` AS productos_producto,
     productos.`existencia_minima` AS productos_existencia_minima,
     productos.`existencia_maxima` AS productos_existencia_maxima,
     productos.`stock` AS productos_stock,
     productos.`costo` AS productos_costo,
     productos.`id_categoria` AS productos_id_categoria,
     productos.`id_proveedor` AS productos_id_proveedor,
     productos.`precio1` AS productos_precio1,
     productos.`precio2` AS productos_precio2,
      impuestos.`iva` AS productos_iva,
      productos.`codigo` AS productos_codigo,
       productos.`tipos_compras` AS productos_tipos_compras,
       productos.`grupo` AS productos_grupo,
       centro_costo.descripcion as centro_descripcion
FROM
     `categorias` categorias 
     INNER JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria` 
     INNER JOIN `centro_costo` centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`";
      
       $sql .= "   LEFT JOIN impuestos ON impuestos.id_iva = productos.iva ";
       
   	$sql .= "  where  productos.`id_empresa`='".$sesion_id_empresa."' ";

       
        if (isset($_GET['criterio_usu_per'])){
		$sql .= " and productos.`producto`      like '%".$_GET['criterio_usu_per']."%' ";}       
	
	if (isset($_GET['criterio_tipo']) && $_GET['criterio_valor']!='Areas'){

        // $tipo= ($_GET['criterio_tipo']=='1')?'Inventario':'Servicios';
        $tipo= $_GET['criterio_tipo'];
		$sql .= " and productos.`tipos_compras` like '%".$tipo."%'  "; }	    
		
        $sql.=" GROUP BY productos.codigo ";

      if($_GET['criterio_valor']=='Areas') {
        $sql.=" , productos.grupo ";
        }

    if($_GET['criterio_valor']=='Areas') {
            $sql.=" order by productos.grupo  , productos.`codigo` ASC";
            } else{
                if (isset($_GET['criterio_ordenar_por']))
                $sql .= " order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']." ";
            else
                $sql .= " order by productos.`codigo` asc ";
            }
     
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
        $sumatotal= 0;
        $numeroProductos=0;
    
       $sqlBodegas="SELECT bodegas.`id`, bodegas.`detalle`, bodegas.`id_empresa`, cantBodegas.idProducto FROM `bodegas` INNER JOIN cantBodegas ON cantBodegas.idBodega= bodegas.id  WHERE `id_empresa`=$sesion_id_empresa  GROUP BY bodegas.`id`  ";
                $result2 = mysql_query($sqlBodegas);
                $cantidadBodegas = mysql_num_rows($result2);
                $stockFinal=0;
                $procesoFinal=0;
                $bodegas_titulo = '';
                  $bodegas_Subtitulo = '';
                  $listaBodegas = array();
                while($rowBod = mysql_fetch_array($result2)){
    $listaBodegas[] = $rowBod["id"];
    $bodegas_titulo .= '<th colspan="2">'.$rowBod["detalle"].'</th>';
    $bodegas_Subtitulo .= '<th>Stock</th><th>' . ($_SERVER['SERVER_NAME'] == 'jderp.cloud' || $_SERVER['SERVER_NAME'] == 'www.jderp.cloud' ? '<b>Comprometido</b>' : '<b>Proceso</b>') . '</th>';
}

                $columnas = 4+($cantidadBodegas*2);
                
$output .= "<table > <thead>
    <tr >
    <th colspan='".$columnas."' style='border-style: solid;' >".$sesion_empresa_nombre." </th></tr><tr>
    <th colspan='".$columnas."' style='border-style: solid;' >REPORTE DE INVENTARIO </th></tr>
    <tr>
    <th></th>
    <th></th>
    ".$bodegas_titulo."
    <th></th>
    <th></th>
    </tr>
    <tr>
    <th>Codigo</th>
    <th>Producto</th>".$bodegas_Subtitulo."
    <th>Stock Total</th>
    <th>".($_SERVER['SERVER_NAME'] == 'jderp.cloud' || $_SERVER['SERVER_NAME'] == 'www.jderp.cloud' ? '<b>Total Comprometido</b>' : '<b>Total Proceso</b>')."</th>
    </tr>
    </thead> <tbody>";


$totalStock=0;
$totalProceso=0;

      while($row = mysql_fetch_array($result)){ 
            $numero ++;
            
        $output .="
		<tr>
		<td>'".utf8_decode($row['productos_codigo'])."'</td>
		<td>".utf8_decode($row['productos_producto'])."</td>";
		
		$stockFinal=0;
        $procesoFinal=0;
            
		for($i=0; $i<$cantidadBodegas; $i++){
		    
		    $sqlBodegas="SELECT bodegas.`id`, bodegas.`detalle`, bodegas.`id_empresa`, cantBodegas.idProducto, SUM(cantBodegas.cantidad) as total, SUM(cantBodegas.proceso) as totalProceso FROM `bodegas` INNER JOIN cantBodegas ON cantBodegas.idBodega= bodegas.id  WHERE `id_empresa`=$sesion_id_empresa and cantBodegas.idProducto='".$row['productos_codigo']."' AND bodegas.id=".$listaBodegas[$i];
		    $result3='';
            $result3 = mysql_query($sqlBodegas);
            $columas = mysql_num_rows($result3);
            if($columas==0){
                 $output .="<td>0.0000</td><td>0.0000</td>";
            }else{
                $row3='';
                while($row3 = mysql_fetch_array($result3) ){
                    
                $output .="<td>".number_format($row3['total'],4,'.','')."</td>";
                $output .="<td>".number_format($row3['totalProceso'],4,'.','')."</td>";
                
                
                $procesoFinal=  $procesoFinal+ $row3['totalProceso'];
                $stockFinal=  $stockFinal+ $row3['total'];
            }
            
            }
            
        //  $output .="<td>".$sqlBodegas."</td>";
		}
	 
                
	 $output .="
		<td>".number_format($stockFinal,4,'.','')."</td>
	    <td>".number_format($procesoFinal,4,'.','')."</td>
	    
		</tr>";
	    
	    $totalStock =$totalStock + $stockFinal;
		$totalProceso = $totalProceso + $procesoFinal;
      }

	 $output .="
		<tr>
		<td colspan='".($columnas-2)."'>TOTAL</td>
		<td>".number_format($totalStock,4,'.','')."</td>
	    <td>".number_format($totalProceso,4,'.','')."</td>
		</tr>";
	

}else if($_GET['criterio_valor'] == 'Valorados'){
    
$sql = "SELECT
     categorias.`id_categoria` AS categorias_id_categoria,
     categorias.`categoria` AS categorias_categoria,
     productos.`id_producto` AS productos_id_producto,
     productos.`producto` AS productos_producto,
     productos.`existencia_minima` AS productos_existencia_minima,
     productos.`existencia_maxima` AS productos_existencia_maxima,
     productos.`stock` AS productos_stock,
     productos.`costo` AS productos_costo,
     productos.`id_categoria` AS productos_id_categoria,
     productos.`id_proveedor` AS productos_id_proveedor,
     productos.`precio1` AS productos_precio1,
     productos.`precio2` AS productos_precio2,
      impuestos.`iva` AS productos_iva,
      productos.`codigo` AS productos_codigo,
       productos.`tipos_compras` AS productos_tipos_compras,
       productos.`grupo` AS productos_grupo,
       centro_costo.descripcion as centro_descripcion
FROM
     `categorias` categorias 
     INNER JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria` 
     INNER JOIN `centro_costo` centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`";
       $sql .= "   LEFT JOIN impuestos ON impuestos.id_iva = productos.iva ";
       
    $sql .= " where  productos.`id_empresa`='".$sesion_id_empresa."' ";

        if (isset($_GET['criterio_usu_per'])){
		$sql .= " and productos.`producto`      like '%".$_GET['criterio_usu_per']."%' ";}       
		
    
    	
	if (isset($_GET['criterio_tipo']) && $_GET['criterio_valor']!='Areas'){

        // $tipo= ($_GET['criterio_tipo']=='1')?'Inventario':'Servicios';
        $tipo= $_GET['criterio_tipo'];
		$sql .= " and productos.`tipos_compras` like '%".$tipo."%'  "; }	    
		
        $sql.=" GROUP BY productos.codigo ";

   
        if (isset($_GET['criterio_ordenar_por']))
            $sql .= " order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']." ";
        else
                $sql .= " order by productos.`codigo` asc ";
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); 
       
       $sumaStockFinal =0;
       $sumaCostoFinal = 0;
       $sumaTotalCosto = 0;
       $sumaTotalPrecio = 0;
       $sumaTotales = 0;
        
        $output .="<table > <thead>
	<tr >
	<th colspan='9' style='border-style: solid;' >".$sesion_empresa_nombre." </th></tr><tr>
	<th colspan='9' style='border-style: solid;' >REPORTE DE INVENTARIO </th></tr>
	
		<tr>
	<th colspan='4'>Fecha: ".date('Y/m/d')."</th>
	<th></th>
	<th colspan='4'>Hora: ".date("H:i:s")."</th>
	</tr>
	
	<tr>
	<th>#</th>
	<th>Codigo</th>
	<th>Producto</th>
	<th>Stock</th>
	<th>Costo</th>
	<th>Total Costo</th>
	<th>IVA</th>
	<th>Precio</th>
    <th>Totales</th>
	</tr>
	</thead> <tbody>";
	$nfila=0;
    while($row = mysql_fetch_array($result)){ 
           $sqlCantidadTotalBodegas = "SELECT SUM(cantidad) as total from cantBodegas
                inner join bodegas on bodegas.id=cantBodegas.idBodega  where idProducto = '".$row['productos_codigo']."'  and   bodegas.id_empresa='".$sesion_id_empresa."' ";
          // echo $sqlCantidadTotalBodegas."</br>";
       
          $resultCantidadTotalBodegas=mysql_query($sqlCantidadTotalBodegas);
      $sumaStockBodegas=0;
       
      $nombreBodega='';
          while ($rowCantidadTotalBodega = mysql_fetch_array($resultCantidadTotalBodegas)) {
            $sumaStockBodegas=   $rowCantidadTotalBodega['total'];
          }
      $sumaStockBodegas= ($sumaStockBodegas=='')?0:$sumaStockBodegas;


        $nfila++;
          $output .="
		<tr>
		<td>".$nfila."</td>
		<td>'".utf8_decode($row['productos_codigo'])."'</td>
		<td>".utf8_decode($row['productos_producto'])."</td>
		<td>".$sumaStockBodegas."</td>
		<td>".number_format($row["productos_costo"], 2,',','')."</td>
		<td>".number_format($row["productos_costo"]*$sumaStockBodegas, 2,',','')."</td>
		<td>".$row["productos_iva"]."</td>
		<td>".number_format($row["productos_precio1"], 2,',','')."</td>
		<td>".number_format($row["productos_precio1"]*$sumaStockBodegas, 2,',','')."</td>
		";
		
	    $sumaStockFinal = $sumaStockFinal+ $sumaStockBodegas;
        $sumaCostoFinal = $sumaCostoFinal + $row["productos_costo"];
        $sumaTotalCosto = $sumaTotalCosto + ($row["productos_costo"] * $sumaStockBodegas);
        $sumaTotalPrecio = $sumaTotalPrecio + $row["productos_precio1"] ;
        $sumaTotales = $sumaTotales + ($row["productos_precio1"]*$sumaStockBodegas) ;
       
		 $sqlCantidadTotalBodegas2 = "SELECT SUM(cantidad) as total, detalle from cantBodegas
                inner join bodegas on bodegas.id=cantBodegas.idBodega  where idProducto = '".$row['productos_codigo']."'  and   bodegas.id_empresa='".$sesion_id_empresa."' GROUP BY cantBodegas.idBodega ";

        $resultCantidadTotalBodegas2=mysql_query($sqlCantidadTotalBodegas2);
        $nombreBodega='';
                
        while ($rowCantidadTotalBodega2 = mysql_fetch_array($resultCantidadTotalBodegas2)) {
            
            if($rowCantidadTotalBodega2['total']!=''){
                  
        $output .="
		<tr>
		<td></td>
		<td>".utf8_decode($rowCantidadTotalBodega2['detalle'])."</td>
		<td>".$rowCantidadTotalBodega2['total']."</td>
		</tr>";
                }
    }
    
    }   
    $output .="
		<tr>
		<td></td>
		<td></td>
		<td>TOTAL</td>
		<td>".number_format($sumaStockFinal, 2,',','')."</td>
		<td>".number_format($sumaCostoFinal, 2,',','')."</td>
		<td>".number_format($sumaTotalCosto, 2,',','')."</td>
		<td>".number_format($sumaTotalPrecio, 2,',','')."</td>
		<td>".number_format($sumaTotales, 2,',','')."</td>
		</tr>";
}else if($_GET['criterio_valor'] == 'Areas'){
    
    $sqlCentrosCostos="SELECT id_centro_costo ,descripcion FROM `centro_costo` WHERE `empresa`=$sesion_id_empresa";
    $result = mysql_query($sqlCentrosCostos);
    $cc=0;
    $sumaCosto=0;
    $sumaPrecio=0;
    $sumaStock=0;
     $output .="<table > <thead>
	<tr >
		<th colspan='5' style='border-style: solid;' >".$sesion_empresa_nombre." </th>
			</tr >
			<tr >
		<th colspan='5' style='border-style: solid;' >REPORTE DE INVENTARIOS </th></tr>
		<tr>
	<th colspan='2'>Fecha: ".date('Y/m/d')."</th>
	<th></th>
	<th colspan='2'>Hora: ".date("H:i:s")."</th>
	</tr>
	</thead></table>";
	
    while($rowCC = mysql_fetch_array($result)){
        
         $output .="<table > <thead>
	<tr ><th colspan='5' style='border-style: solid;' >".strtoupper($rowCC['descripcion'])." </th></tr>
	<tr>
	<th>Codigo</th>
	<th>Producto</th>
	<th>Costo</th>
	<th>Precio</th>
    <th>Stock</th>
	</tr>
	</thead> <tbody>";
	
        $sql = "SELECT
     categorias.`id_categoria` AS categorias_id_categoria,
     categorias.`categoria` AS categorias_categoria,
     productos.`id_producto` AS productos_id_producto,
     productos.`producto` AS productos_producto,
     productos.`existencia_minima` AS productos_existencia_minima,
     productos.`existencia_maxima` AS productos_existencia_maxima,
     productos.`stock` AS productos_stock,
     productos.`costo` AS productos_costo,
     productos.`id_categoria` AS productos_id_categoria,
     productos.`id_proveedor` AS productos_id_proveedor,
     productos.`precio1` AS productos_precio1,
     productos.`precio2` AS productos_precio2,
      productos.`iva` AS productos_iva,
      productos.`codigo` AS productos_codigo,
       productos.`tipos_compras` AS productos_tipos_compras,
       productos.`grupo` AS productos_grupo,
       centro_costo.descripcion as centro_descripcion
FROM
     `categorias` categorias 
     INNER JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria` 
     INNER JOIN `centro_costo` centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
     where  productos.`id_empresa`='".$sesion_id_empresa."' and centro_costo.`id_centro_costo`='".$rowCC['id_centro_costo']."' ";

        if (isset($_GET['criterio_usu_per'])){
		$sql .= " and productos.`producto`      like '%".$_GET['criterio_usu_per']."%' ";}       
	
	if (isset($_GET['criterio_tipo']) && $_GET['criterio_valor']!='Areas'){

        // $tipo= ($_GET['criterio_tipo']=='1')?'Inventario':'Servicios';
        $tipo= $_GET['criterio_tipo'];
		$sql .= " and productos.`tipos_compras` like '%".$tipo."%'  "; }	    
		
        $sql.=" GROUP BY productos.codigo ";

        $sql.=" , productos.grupo ";
         $result2 = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result2); // obtenemos el número de filas
        $numero = 0;
        $sumatotal= 0;
        $numeroProductos=0;
    //  echo $sql;

        // INVENTARIOS 
       while($row = mysql_fetch_array($result2)){ 
            $numero ++;
                
            $sqlBodegas="SELECT bodegas.`id`, bodegas.`detalle`, bodegas.`id_empresa`, cantBodegas.idProducto, SUM(cantBodegas.cantidad) as total FROM `bodegas` INNER JOIN cantBodegas ON cantBodegas.idBodega= bodegas.id  WHERE `id_empresa`=$sesion_id_empresa and cantBodegas.idProducto='".$row['productos_codigo']."' GROUP BY bodegas.`id`  ";
            $resultB = mysql_query($sqlBodegas);
            $stockFinal=0;
            while($rowBod = mysql_fetch_array($resultB)){

                $stockFinal=  $stockFinal+ $rowBod['total'];
            }
            $output .="
		<tr>
		<td>'".$row["productos_codigo"]."'</td>
		<td>".utf8_decode($row["productos_producto"])."</td>
		<td>".number_format($row["productos_costo"], 2,',','')."</td>
		<td>".number_format($row["productos_precio1"], 2,',','')."</td>
		<td>".$stockFinal."</td>
		</tr>";
        
        $sumaCosto = $sumaCosto + $row["productos_costo"];
        $sumaPrecio = $sumaPrecio + $row["productos_precio1"];
        $sumaStock = $sumaStock + $stockFinal;
        
                    $sumatotal+=floatval($row["productos_costo"])* floatval($row["productos_stock"]);  
                    $numeroProductos++;  
            }
        $output .="
		<tr>
		<td colspan='2'>TOTAL</td>
		<td>".number_format($sumaCosto, 2,',','')."</td>
		<td>".number_format($sumaPrecio, 2,',','')."</td>
		<td>".number_format($sumaStock, 2,',','')."</td>
		</tr></tbody></table>";
		$sumaStock=0;
		$sumaPrecio=0;
		$sumaCosto=0;
    }
}

	echo $output;
