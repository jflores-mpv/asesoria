<?php
//ob_end_clean();
//Start session
error_reporting(0);
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');

// $sesion_id_empresa = $_GET['empresa'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
//  $sesion_id_nombre = $_SESSION['sesion_id_empresa'];
 $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
$pdf =& new Cezpdf('a4');
$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$datacreador = array (
                    'Title'=>'Productos',
                    'Subject'=>'Productos',
                    'Author'=>'25 de junio',
                    'Producer'=>'Macarena LALAMA'
                    );
$pdf->addInfo($datacreador);

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
	
		if (isset($_GET['txtFechaCaducidad']) && trim($_GET['txtFechaCaducidad'])!=''){
        $sql .= " and lotes.`fecha_caducidad` = '".$_GET['txtFechaCaducidad']."' "; 
    }
    	if (isset($_GET['txtFechaElaboracion']) && trim($_GET['txtFechaElaboracion'])!=''){
        $sql .= " and lotes.`fecha_elaboracion` = '".$_GET['txtFechaElaboracion']."' "; 
    }
    	if (isset($_GET['lotes']) && trim($_GET['lotes'])!='0'){
        $sql .= " and lotes.`id_lote` = '".$_GET['lotes']."' "; 
    }
    
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
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
        $sumatotal= 0;
        $numeroProductos=0;
         $sumaStockTotal = 0;
        $sumaCostoTotal=0;
        $sumaPrecioTotal=0;
       while($row = mysql_fetch_array($result)){ //for mayor    for($i=0; $i<$numero_filas; $i++)
            $numero ++;
    
            //primera         


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


                $data[] = array(
                    '#'=>$numero,
                    'codigo'=>$row["productos_codigo"] ,
                    'producto'=>utf8_decode($row["productos_producto"]) ,
                    'stock'=>$sumaStockBodegas,
                    'Costo'=>number_format($row["productos_costo"], 2,',',''),
                    'Total Costo'=>number_format($row["productos_costo"]*$sumaStockBodegas, 2,',',''),
                    'IVA'=>$row["productos_iva"],
                    'Precio Venta'=>number_format($row["productos_precio1"], 2,',',''),
                    'Totales'=>number_format($row["productos_precio1"]*$sumaStockBodegas, 2,',','')
                    );

                $sqlCantidadTotalBodegas2 = "SELECT SUM(cantidad) as total, detalle from cantBodegas
                inner join bodegas on bodegas.id=cantBodegas.idBodega  where idProducto = '".$row['productos_codigo']."'  and   bodegas.id_empresa='".$sesion_id_empresa."' GROUP BY cantBodegas.idBodega ";

                $resultCantidadTotalBodegas2=mysql_query($sqlCantidadTotalBodegas2);
                $nombreBodega='';
                
                while ($rowCantidadTotalBodega2 = mysql_fetch_array($resultCantidadTotalBodegas2)) {

                    if($rowCantidadTotalBodega2['total']!=''){
                  
                    $data[] = array(
                    'codigo'=>utf8_decode($rowCantidadTotalBodega2["detalle"]) ,
                    'producto'=> $rowCantidadTotalBodega2["total"] 
                    );
                    }
                   
                }
        
            $sumaStockTotal = $sumaStockTotal+ $sumaStockBodegas;
            $sumaCostoTotal = $sumaCostoTotal+ number_format($row["productos_costo"], 2,'.','');
            $sumaCostoTotales = $sumaCostoTotales+ number_format($row["productos_costo"]*$sumaStockBodegas, 2,'.','');
      
            $sumaPrecioTotal = $sumaPrecioTotal+ number_format($row["productos_precio1"], 2,'.','');
            $sumaPrecioTotales = $sumaPrecioTotales+ number_format($row["productos_precio1"]*$sumaStockBodegas, 2,'.','');
        
            }
          
            $data[] = array(
                
                'producto'=> 'TOTAL' ,
                'stock'=>  number_format($sumaStockTotal, 2, '.', ',') ,
                'Costo'=> number_format($sumaCostoTotal, 2, '.', ',') ,
                'Total Costo'=>number_format($sumaCostoTotales, 2, '.', ','),
                'Precio Venta'=>number_format($sumaPrecioTotal, 2, '.', ','),
                'Totales'=>number_format($sumaPrecioTotal, 2, '.', ',')
            );
                
                $titles = array(
                    '#' => '<b>#</b>',
                    'codigo' => '<b>Codigos</b>',
                    'producto' => '<b>Producto</b>',
                    'stock' => '<b>Stock</b>',
                    
                    'Costo' => '<b>Costo</b>',
                    'Total Costo' =>'<b>Total Costo</b>',
                    'IVA' => '<b>IVA</b>',
                    'Precio Venta' => '<b>Precio</b>',
                    'Totales' => '<b>Totales</b>'
                    );
            

                $options = array(
                    
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>550,
                    'cols'=>array(
                     
                        'IVA'=>array('justification'=>'center', 'width'=>30),
                        '#'=>array('justification'=>'center', 'width'=>20),
                        'producto'=>array('justification'=>'left', 'width'=>160, 'font-style' => 'bold'),
                        'codigo'=>array('justification'=>'right', 'width'=>70),
                        'stock'=>array('justification'=>'right', 'width'=>60),
                        'total costo'=>array('justification'=>'right', 'width'=>70),
                        'Costo'=>array('justification'=>'right', 'width'=>50),
                        'total'=>array('justification'=>'right', 'width'=>60),
                        'Totales'=>array('justification'=>'right', 'width'=>60)
                        
                        )
                );
    
             
           
        //$pdf->selectFont('Arial','B',14); // establece la fuente, le tipo ( 'B' para negrita, 'I' para itálica, '' para normal,...)
        $pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
        $pdf->setLineStyle(1,'square');
        $pdf->setStrokeColor(0,0,0);
        
        // $pdf->ezText("<b>INVENTARIOS</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        // $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
        $txttit.= "";
        $pdf->ezText($txttit, 12);
        $pdf->ezTable($data, $titles, '', $options);
        // $pdf->ezText("<b>Total:</b>    $sumatotal", 12,array( 'justification' => 'rigth' ));
        // $pdf->ezText("<b>Total:</b>    $sumatotal", 10);
        $pdf->ezText("\n\n", 10);
        $pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
        $pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n", 10);
        // $pdf->ezText("<b>Total:</b>    $sumatotal", 10);
     
        $pdf->ezStartPageNumbers(550, 80, 10);
        //$nombrearchivo = "reporteLibroMayor.pdf";
        $pdf->ezStream();
        //$pdf->ezOutput($nombrearchivo);
        $pdf->Output('Productos_por_cantidades.pdf', 'D');


        mysql_close();
        mysql_free_result($result);

?>