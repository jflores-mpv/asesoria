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
$pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
$pdf->setLineStyle(1,'square');
$pdf->setStrokeColor(0,0,0);

$pdf->ezText("\n", 10);
$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
 $sqlCentrosCostos="SELECT id_centro_costo ,descripcion FROM `centro_costo` WHERE `empresa`=$sesion_id_empresa";

$result = mysql_query($sqlCentrosCostos);
$cc=0;
while($rowCC = mysql_fetch_array($result)){
// echo $cc;
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
       while($row = mysql_fetch_array($result2)){ //for mayor    for($i=0; $i<$numero_filas; $i++)
            $numero ++;
                
            $sqlBodegas="SELECT bodegas.`id`, bodegas.`detalle`, bodegas.`id_empresa`, cantBodegas.idProducto, SUM(cantBodegas.cantidad) as total FROM `bodegas` INNER JOIN cantBodegas ON cantBodegas.idBodega= bodegas.id  WHERE `id_empresa`=$sesion_id_empresa and cantBodegas.idProducto='".$row['productos_codigo']."' GROUP BY bodegas.`id`  ";
            $resultB = mysql_query($sqlBodegas);
            $stockFinal=0;
            while($rowBod = mysql_fetch_array($resultB)){

                $stockFinal=  $stockFinal+ $rowBod['total'];
            }


                $data[$cc][$numeroProductos] = array(
                    '#'=>$numero,
                    'codigo'=>$row["productos_codigo"] ,
                    'producto'=>utf8_decode($row["productos_producto"]) ,
                    'stock'=> $stockFinal,
                    'Costo'=>number_format($row["productos_costo"], 2,',',''),
                    'precio'=> number_format($row["productos_precio1"], 2,',','') ,
                    // 'total'=>floatval($row["productos_costo"])* floatval($row["productos_stock"])
                    );
 
                    $sumatotal+=floatval($row["productos_costo"])* floatval($row["productos_stock"]);  
                    $numeroProductos++;  
            
           
            // echo 'asd';
            }
        
                $titles = array(
                    'codigo' => '<b>Codigo</b>',
                    'producto' => '<b>Producto</b>',
                    'Costo' => '<b>Costo</b>',
                    'precio' => '<b>Precio</b>',
                    'stock' => '<b>Stock</b>'
                    );

            
                $options = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>550,
                    'cols'=>array(
                        '#'=>array('justification'=>'center', 'width'=>50),
                        'producto'=>array('justification'=>'center', 'width'=>340),
                        'codigo'=>array('justification'=>'center', 'width'=>70),
                        'stock'=>array('justification'=>'center', 'width'=>40),
                        'Costo'=>array('justification'=>'center', 'width'=>50),
                        'Precio'=>array('justification'=>'center', 'width'=>50),
                        'total'=>array('justification'=>'center', 'width'=>60),
                        )
                );
 
           
        //$pdf->selectFont('Arial','B',14); // establece la fuente, le tipo ( 'B' para negrita, 'I' para itálica, '' para normal,...)

        // $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
        $txttit.= "";
        $pdf->ezText($txttit, 12);
        $pdf->ezText("<b>".$rowCC['descripcion']."</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("\n", 10);
        $pdf->ezTable($data[$cc], $titles, '', $options);
        // $pdf->ezText("<b>Total:</b>    $sumatotal", 12,array( 'justification' => 'rigth' ));
        $cc++;
    }
    
    //   var_dump($data[1]);
    //   exit;
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
        $pdf->Output('reporteLibroMayor.pdf', 'D');


        mysql_close();
        mysql_free_result($result);

?>