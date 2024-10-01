<?php
//ob_end_clean();
//Start session
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('a4','Landscape');
$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$datacreador = array (
                    'Title'=>'Empelados',
                    'Subject'=>'Lista de Empelados',
                    'Author'=>'25 de junio',
                    'Producer'=>'Andres Anrrango'
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
     proveedores.`id_proveedor` AS proveedores_id_proveedor,
     proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
     proveedores.`nombre` AS proveedores_nombre,
     proveedores.`direccion` AS proveedores_direccion,
     proveedores.`ruc` AS proveedores_ruc,
     proveedores.`telefono` AS proveedores_telefono,
     proveedores.`movil` AS proveedores_movil,
     proveedores.`fax` AS proveedores_fax,
     proveedores.`email` AS proveedores_email,
     proveedores.`web` AS proveedores_web,
     proveedores.`observaciones` AS proveedores_observaciones,
     proveedores.`id_ciudad` AS proveedores_id_ciudad,
     detalles.`id_detalle` AS detalles_id_detalle,
     detalles.`color` AS detalles_color,
     detalles.`tamano` AS detalles_tamano,
     detalles.`marca` AS detalles_marca,
     detalles.`imagen` AS detalles_imagen,
     detalles.`descripcion` AS detalles_descripcion,
     detalles.`id_producto` AS detalles_id_producto,
     productos.`ganancia1` AS productos_ganancia1,
     productos.`ganancia2` AS productos_ganancia2,
     productos.`fecha_registro` AS productos_fecha_registro,
     productos.`ano` AS productos_ano,
     productos.`mes` AS productos_mes
FROM
     `categorias` categorias INNER JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria`
     INNER JOIN `proveedores` proveedores ON productos.`id_proveedor` = proveedores.`id_proveedor`
     INNER JOIN `detalles` detalles ON productos.`id_producto` = detalles.`id_producto` ";

    if (($_GET['txtIdProducto'])>=1 ){
        $sql .= " where productos.`id_producto`='".($_GET['txtIdProducto'])."'  ";
        if (($_GET['cmbAno'])!="Todos" ){
             $sql .= "  and productos.`ano`='".($_GET['cmbAno'])."' ";
             if (($_GET['cmbMes'])!="Todos" ){
                $sql .= "  and productos.`mes`='".($_GET['cmbMes'])."' ";
            }
        }

    }else{
        if (($_GET['cmbAno'])!="Todos" ){
            $sql .= " where productos.`ano`='".($_GET['cmbAno'])."'  ";
            if (($_GET['cmbMes'])!="Todos" ){
                $sql .= " and  productos.`mes`='".($_GET['cmbMes'])."' ";
            }
        }
    }

    if (isset($_GET['criterio_ordenar_por'])){
            $sql .= sprintf(" order by %s %s", ($_GET['criterio_ordenar_por']), ($_GET['criterio_orden'])); }
    else{
            $sql .= " order by productos.`producto` asc "; }
    if (isset($_GET['criterio_mostrar'])){
        $sql .= " LIMIT ".$_GET['criterio_mostrar'].";";
    }
 

        $result = mysql_query($sql) or die(mysql_error());
        $aux1=0;
      
        while($row = mysql_fetch_assoc($result)) {
            $aux1++;
            

            $data[] = array(
            'id' =>$aux1,
            'fecha'=>$row["productos_fecha_registro"],
            'categoria'=>$row["categorias_categoria"],
            'nombre'=>$row["productos_producto"],
            'detalles'=>$row["detalles_color"].", ".$row["detalles_tamano"].", ".$row["detalles_marca"],
            'costo'=>$row["productos_costo"],
            'precio1'=>$row["productos_precio1"],
            'precio2'=>$row["productos_precio2"],
            'ex_min'=>$row["productos_existencia_minima"],
            'ex_max'=>$row["productos_existencia_maxima"],
            'stock'=>$row["productos_stock"],
            'proveedor'=>$row["proveedores_nombre_comercial"]
            );
        }


        $titles = array(
            'id' => '<b>#</b>',
            'fecha' => '<b>Fecha</b>',
            'categoria' => '<b>Categoria</b>',
            'nombre' => '<b>Nombre</b>',
            'detalles' => '<b>Detalles</b>',
            'costo' => '<b>Costo</b>',
            'precio1' => '<b>Precio 1</b>',
            'precio2' => '<b>Precio 2</b>',
            'ex_min' => '<b>Ex. Min</b>',
            'ex_max' => '<b>Ex. Max</b>',
            'stock' => '<b>Stock</b>',
            'proveedor' => '<b>Proveedor</b>'
        );

        $options = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>800
                        );
        $options['fontSize']= 10;

        $pdf->ezImage('../images/encabezado_impresiones.jpg','','155','40','left','');
        $pdf->setLineStyle(1,'square');
        $pdf->setStrokeColor(0,0,0);
        $pdf->line('800', '510', '20', '510');
        $pdf->ezText("\n<b>INVENTARIO</b>", 18,array( 'justification' => 'center' ));
        $txttit = "\n<b>Listado de Productos</b>\n";
        $txttit.= "";
        $pdf->ezText($txttit, 12);
        $pdf->ezTable($data, $titles, '', $options);
        $pdf->ezText("\n\n\n", 10);
        $pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
        $pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
        $pdf->ezStartPageNumbers(550, 80, 10);
        $nombrearchivo = "reporteEmpleados.pdf";
        $pdf->ezStream();
        $pdf->ezOutput($nombrearchivo);

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));


        mysql_close();
        mysql_free_result($result);

?>

