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
//   exit;
//  $sesion_id_nombre = $_SESSION['sesion_id_empresa'];
 $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
$pdf =& new Cezpdf('a4','landscape');
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
      productos.`iva` AS productos_iva,
      productos.`codigo` AS productos_codigo,
       productos.`tipos_compras` AS productos_tipos_compras,
       productos.`grupo` AS productos_grupo,
       centro_costo.descripcion as centro_descripcion
FROM
     `categorias` categorias 
     INNER JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria` 
     INNER JOIN `centro_costo` centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
     where  productos.`id_empresa`='".$sesion_id_empresa."' ";

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
        // if (isset($_GET['criterio_mostrar']))
		// $sql .= " LIMIT ".((int)$_GET['criterio_mostrar']);

// echo $sql;
// exit;
// $_GET['criterio_valor'];

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
        $sumatotal= 0;
        $numeroProductos=0;
       while($row = mysql_fetch_array($result)){ //for mayor    for($i=0; $i<$numero_filas; $i++)
            $numero ++;
    
            //primera         
                $data[$numeroProductos] = array(
                    '#'=>$numero,
                    'codigo'=>$row["productos_codigo"] ,
                    'producto'=>utf8_decode($row["productos_producto"]) ,
                    'stock'=>$row["productos_stock"] ,
                  
                  
                    );
               
                  
               $sqlBodegas="SELECT bodegas.`id`, bodegas.`detalle`, bodegas.`id_empresa`, cantBodegas.idProducto, SUM(cantBodegas.cantidad) as total, SUM(cantBodegas.proceso) as totalProceso FROM `bodegas` INNER JOIN cantBodegas ON cantBodegas.idBodega= bodegas.id  WHERE `id_empresa`=$sesion_id_empresa and cantBodegas.idProducto='".$row['productos_codigo']."' GROUP BY bodegas.`id`  ";
                $result2 = mysql_query($sqlBodegas);
                $stockFinal=0;
                $procesoFinal=0;
                while($rowBod = mysql_fetch_array($result2)){
                   
                        $columna= $rowBod['detalle'];
                        $total_actual= (trim($rowBod['total'])!='')?$rowBod['total']:0;
                        $proceso_actual = (trim($rowBod['totalProceso'])!='')?$rowBod['totalProceso']:0;
                        
                        $data[$numeroProductos] [$columna]=$total_actual;
                        $data[$numeroProductos] [$columna.'1']=$proceso_actual;
                    $procesoFinal=  $procesoFinal+ $proceso_actual;
                    $stockFinal=  $stockFinal+$total_actual;
                }
               
                $data[$numeroProductos] ['stockTotal']=$stockFinal;
                 $data[$numeroProductos] ['totalProceso']=$procesoFinal;
            //    echo $numeroProductos++;
                    $sumatotal+=floatval($row["productos_costo"])* floatval($row["productos_stock"]);  
                    $numeroProductos++;  

            }
            //    exit;

                $titles = array(
                  
                    'codigo' => '<b>Codigos</b>',
                    'producto' => '<b>Producto</b>'
                
                    );
                 $titles2 = array(
                  
                     'codigo' => '<b>Codigos</b>',
                    'producto' => '<b>Producto</b>'
                
                    );
                    $titlesT=array(
                  
                     'codigo' => '',
                    'producto' => ''
                
                    );

                    $cols=array(
                        'producto'=>array('justification'=>'center', 'width'=>250),
                        'codigo'=>array('justification'=>'center', 'width'=>80),
                        'stock'=>array('justification'=>'center', 'width'=>40),
                        'stockTotal'=>array('justification'=>'center', 'width'=>60),
                        'totalProceso'=>array('justification'=>'center', 'width'=>60),
                    );
                    
                    $cols2=array(
                        'producto'=>array('justification'=>'center', 'width'=>250),
                        'codigo'=>array('justification'=>'center', 'width'=>80),
                        'stock'=>array('justification'=>'center', 'width'=>40),
                        'stockTotal'=>array('justification'=>'center', 'width'=>60),
                        'totalProceso'=>array('justification'=>'center', 'width'=>60),
                    );
                
            $sqlBodegas2="SELECT bodegas.`id`, bodegas.`detalle`, bodegas.`id_empresa`, cantBodegas.idProducto, cantBodegas.cantidad FROM `bodegas` INNER JOIN cantBodegas ON cantBodegas.idBodega= bodegas.id  WHERE `id_empresa`=$sesion_id_empresa GROUP BY bodegas.id  ";
                $result2 = mysql_query($sqlBodegas2);
                $numeroBodegas = mysql_num_rows($result2);
                $ancho = 185/$numeroBodegas;
                $ancho2 = $ancho*2;
                while($rowBod2 = mysql_fetch_array($result2)){
                   
                        $columna= $rowBod2['detalle'];
                        $titles[$columna] =  $rowBod2['detalle'];
                        $titlesT[$columna] =  $rowBod2['detalle'];
                        
                         $titles2[$columna] = '<b>stock</b>';
                          $titles2[$columna.'1'] ='<b>Proceso:</b>';
                          
                        $cols+= [$rowBod2['detalle'] => array('justification'=>'center','width'=>$ancho ) ];
                        $cols+= [$rowBod2['detalle'].'1' => array('justification'=>'center','width'=>$ancho ) ];
                        
                        $cols2+= [$rowBod2['detalle'] => array('justification'=>'center','width'=>$ancho2 ) ];
                }
                
                $titlesT['stockTotal'] =  '';
                $titlesT['totalProceso'] =  '';
                $titles2['stockTotal'] =  '<b>Stock Total</b>';
$titles2['totalProceso'] = '<b>Proceso:</b>';
            //   exit;
       
    
                $options = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>750,
                    
                    'cols'=>$cols
                );
                 $optionsT = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>750,
                    'cols'=>$cols2
                );
    
             
           
        //$pdf->selectFont('Arial','B',14); // establece la fuente, le tipo ( 'B' para negrita, 'I' para itálica, '' para normal,...)
        $pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
        $pdf->setLineStyle(1,'square');
        $pdf->setStrokeColor(0,0,0);
        

        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        // $pdf->ezText("<b>INVENTARIOS</b>", 18,array( 'justification' => 'center' ));
        // $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
        $txttit.= "";
        $pdf->ezText($txttit, 12);
        $dataT = [];
        
         $pdf->ezTable($dataT, $titlesT, '', $optionsT);
        $pdf->ezTable($data, $titles2, '', $options);
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