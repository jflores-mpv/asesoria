    <div id="loadingIcon" style="display: none;">
    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
<span class="sr-only">Cargando...</span>

</div>
<?php
// include "../conexion.php";
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    
    
		require_once('../conexion.php');
    session_start();
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	
	
    $id_producto = $_GET['txtIdProducto'];
    $bodega = trim($_GET['cmbBodegas']);
    
                    
                        if($bodega!=0){
            $sqlBodega="SELECT * from bodegas where id='".$bodega."'";
            $resp0 = mysql_query($sqlBodega);

            while($row0=mysql_fetch_array($resp0))//permite ir de fila en fila de la tabla
            {
                $sqlBodegaDetalle=$row0['detalle'];
            }
        }else{
            $sqlBodegaDetalle='Todos';
        }

        

// Definir la consulta SQL para seleccionar los códigos de producto
 $sql_codigo = "SELECT productos.`codigo` AS productos_codigo
               FROM `productos`
               WHERE productos.`id_empresa` = '".$sesion_id_empresa."' 
               AND productos.`id_producto` = '".$id_producto."';";

// Ejecutar la consulta
$resp_codigo = mysql_query($sql_codigo);

// Verificar si la consulta devuelve algún resultado
if ($resp_codigo) {
    while ($row_codigo = mysql_fetch_array($resp_codigo)) {
        $codigo = $row_codigo['productos_codigo'];
        echo "Código del producto: " . $codigo . "<br>";


                
    echo '    <table id="grilla" class="table table-bordered table-responsive table-striped" >
    <thead>
        <tr>
            <th rowspan="2"><strong>FECHA</strong></th>
            <th rowspan="2"><strong>DETALLE</strong></th>
            <th colspan="3"><center><strong>ENTRADA</strong></center></th>
            <th colspan="3"><center><strong>SALIDA</strong></center></th>
            <th colspan="3"><center><strong>SALDO</strong></center></th>            
        </tr>
        <tr>
            <th><strong>CANT</strong></th>
            <th><strong>V/UNIT</strong></th>
            <th><strong>V/TOTAL</strong></th>
            <th><strong>CANT</strong></th>
            <th><strong>V/UNIT</strong></th>
            <th><strong>V/TOTAL</strong></th>
            <th><strong>CANT</strong></th>
            <th><strong>V/UNIT</strong></th>
            <th><strong>V/TOTAL</strong></th>
            <th><strong></strong></th>
        </tr>
    </thead> 
    <tbody>
    
        

';       

        // Consulta secundaria para obtener todos los productos con el mismo código
        $sql0 = "SELECT
                     productos.`id_producto` AS productos_id_producto,
                     productos.`producto` AS productos_producto,
                     productos.`codigo` AS productos_codigo,
                     productos.`existencia_minima` AS productos_existencia_minima,
                     productos.`existencia_maxima` AS productos_existencia_maxima,
                     productos.`stock` AS productos_stock,
                     productos.`costo` AS productos_costo,
                     productos.`id_categoria` AS productos_id_categoria,
                     productos.`id_proveedor` AS productos_id_proveedor,
                     productos.`precio1` AS productos_precio1,
                     productos.`precio2` AS productos_precio2,
                     productos.`ganancia1` AS productos_ganancia1,
                     productos.`ganancia2` AS productos_ganancia2,
                     productos.`produccion` AS productos_produccion,
                     categorias.`id_categoria` AS categorias_id_categoria,
                     categorias.`categoria` AS categorias_categoria
                FROM
                     `categorias` categorias INNER JOIN `productos` productos 
                     ON categorias.`id_categoria` = productos.`id_categoria`
                WHERE productos.`id_empresa` = '".$sesion_id_empresa."' 
                AND productos.`codigo` = '".$codigo."';";

        $resp0 = mysql_query($sql0);

        // Verificar si la consulta devuelve algún resultado
        if ($resp0) {
            while ($row0 = mysql_fetch_array($resp0)) {
                // Obtener los datos del producto
                $nombre = $row0['productos_producto'];
                $categoria = $row0['categorias_categoria'];
                $proveedor = $row0['productos_id_proveedor'];
                $ex_min = $row0['productos_existencia_minima'];
                $ex_max = $row0['productos_existencia_maxima'];
                $cod = $row0['productos_id_producto'];
                $produccion = $row0['productos_produccion'];

                // Imprimir resultados
                echo "Nombre del producto: " . $nombre . "<br>";
                echo "Categoría: " . $categoria . "<br>";
                echo "codigo: " . $cod . "<br>";
                echo "<br>";

        
     
                
                
                    // Obtener y limpiar las variables GET
                $fechaDesde = $_GET['txtFechaIngreso'];
                $fechaHasta = $_GET['txtFechaSalida'];
                $bodega = trim($_GET['cmbBodegas']);
            
                // Verificar que las fechas no estén vacías
                if (empty($fechaDesde) || empty($fechaHasta)) {
                    die("Las fechas de ingreso y salida son obligatorias.");
                }    
                
                $horasdesde = "00:00:00";
                $horashasta = "23:59:59";
            
                // Construir la fecha y hora inicial
                $fechaHoraDesde = $fechaDesde . ' ' . $horasdesde;
            
                // Construir la fecha y hora final
                $fechaHoraHasta = $fechaHasta . ' ' . $horashasta;            
                            
                            
                 $sql = "SELECT * FROM kardes 
               
                        WHERE kardes.id_empresa = '$sesion_id_empresa' 
                 
                        AND kardes.fecha BETWEEN '$fechaHoraDesde' AND '$fechaHoraHasta' 
                     
                        ORDER BY kardes.fecha, kardes.id_kardes ASC";
            
                $resultK = mysql_query($sql);
                if (!$resultK) {
                    die("Error en la consulta SQL: " . mysql_error());
                }
            
                // Procesar los resultados
                while ($rowK = mysql_fetch_array($resultK)) {
                    $id_kardes=$rowK["id_kardes"];
            $detalle=$rowK["detalle"];
            $id_factura=$rowK["id_factura"];
            
             $fecha=$rowK['fecha'];

        if($detalle == "Ingreso" or  $detalle == "Saldo Inicial" ){
			     
                $sql2 = "SELECT
                ingresos.`id_ingreso` AS ingresos_id_ingreso,
                ingresos.`fecha` AS ingresos_fecha,
                ingresos.`total` AS ingresos_total,
                ingresos.`sub_total` AS ingresos_sub_total,
                ingresos.`id_iva` AS ingresos_id_iva,
                ingresos.`numero` AS ingresos_numero,
                detalle_ingresos.`id_detalle_ingreso` AS detalle_ingresos_id_detalle_ingreso,
                detalle_ingresos.`cantidad` AS detalle_ingresos_cantidad,
                detalle_ingresos.`v_unitario` AS detalle_ingresos_valor_unitario,
                detalle_ingresos.`v_total` AS detalle_ingresos_valor_total,
                detalle_ingresos.`id_ingreso` AS detalle_ingresos_id_ingreso,
                detalle_ingresos.`id_producto` AS detalle_ingresos_id_producto,
                detalle_ingresos.`bodega` AS bodega,
                bodegas.`detalle` AS bodega_detalle,
                bodegas.`id` AS id_bodega
            FROM `ingresos` ingresos 
            INNER JOIN `detalle_ingresos` detalle_ingresos  ON ingresos.`id_ingreso` = detalle_ingresos.`id_ingreso` 
            LEFT JOIN `bodegas` bodegas  ON detalle_ingresos.`bodega` = bodegas.`id` 
            INNER JOIN productos ON productos.id_producto= detalle_ingresos.id_producto
			WHERE ingresos.`id_empresa` = '".$sesion_id_empresa."'  and
		    detalle_ingresos.id_ingreso='".$id_factura."' and 
		    productos.id_producto='".$cod."' ";
		
// 		if($bodega!=0){
// 		    $sql2 .=" and bodega='".$bodega."'; ";
// 		}
		

                $contador2 = 0;
                $resp2 = mysql_query($sql2);
                $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
                while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
                {
                    $ingresos_id_ingreso = $row2['ingresos_id_ingreso'];
                    $detalle_ingresos_id_detalle_ingreso = $row2['detalle_ingresos_id_detalle_ingreso'];
                    $ingresos_numero = $row2['ingresos_numero'];
                    $detalleBodega=$row2['bodega_detalle'];
                    $detalle_ingresos_valor_unitario = $row2['detalle_ingresos_valor_unitario'];
                    $detalle_ingresos_valor_unitario = number_format($detalle_ingresos_valor_unitario, 2, '.', '');
                    $detalle_ingresos_cantidad = $row2['detalle_ingresos_cantidad'];
                    $valTotalCompra = $detalle_ingresos_cantidad * $detalle_ingresos_valor_unitario;
                    $valTotalCompra = number_format($valTotalCompra, 2, '.', '');
                    $cont++;
                    $contador2++;
                    // calculo saldo
                    $saldoCant = $saldoCant + $detalle_ingresos_cantidad;
                    $saldoVT = $saldoVT + $valTotalCompra;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');
                        
                     $sqlTransferenciaI= "SELECT transferencias.*,ingresos.id_ingreso, detalle_ingresos.bodega as bodegaIngreso, egresos.id_egreso, 
                     detalle_egresos.bodega as bodegaEgreso, (SELECT bodegas.detalle from bodegas where bodegas.id= detalle_ingresos.bodega) as
                     nombreBodegaIngreso,(SELECT bodegas.detalle from bodegas where bodegas.id= detalle_egresos.bodega) as nombreBodegaEgreso FROM `transferencias` inner join ingresos on ingresos.id_ingreso = transferencias.id_ingreso INNER JOIN egresos on egresos.id_egreso= transferencias.id_egreso INNER join detalle_ingresos on detalle_ingresos.id_ingreso = ingresos.id_ingreso INNER JOIN detalle_egresos on detalle_egresos.id_egreso = egresos.id_egreso  WHERE transferencias.`id_ingreso`='".$ingresos_id_ingreso."' ";
            
                     
                    $resultTransferenciaI = mysql_query($sqlTransferenciaI);
                 
                    $existeEnTransferenciaI = mysql_num_rows($resultTransferenciaI);
                    $numTrans=0;
                    $bodegaOrigen=0;
                    $bodegaDestino=0;
                    while($rowTI = mysql_fetch_array($resultTransferenciaI)){
                         $numTrans = $rowTI['num_trans'];
                         $bodegaOrigen = $rowTI['nombreBodegaEgreso'];
                         $bodegaDestino= $rowTI['nombreBodegaIngreso'];
                            $idBode= $rowTI['bodegas.id'];
                         }
                        
                              
                    ?>
                    
                    <tr id="tr_<?php echo $id_kardes; ?>">
                        <td><?php echo $fecha; ?></td>
                        <?php if ($existeEnTransferenciaI > 0) : ?>
                            <td><?php echo $detalle . " Seg&uacute;n transferencia Nro.$numTrans desde bodega $bodegaOrigen a la bodega $bodegaDestino " ?></td>
                        <?php else : ?>
                            <td><?php echo $detalle . " Seg&uacute;n Ingreso Nro. "; ?><a href="javascript: abrirPdfFacturaVenta(<?php echo $ingresos_id_ingreso; ?>);" title="Ver Factura Venta" style="color: blue; text-decoration: underline"><?php echo $ingresos_numero; ?></a></td>
                        <?php endif; ?>
                        <td><?php echo $detalle_ingresos_cantidad; ?></td>
                        <td><?php echo $detalle_ingresos_valor_unitario; ?></td>
                        <td><?php echo $valTotalCompra; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $saldoCant; ?></td>
                        <td><?php echo $saldoVU; ?></td>
                        <td><?php echo $saldoVT; ?></td>
                    </tr>

                    <?php 
                }
            }
            if($detalle == "Compra"){
                $sql2 = "SELECT
                compras.`id_compra` AS compras_id_compra,
                compras.`fecha_compra` AS compras_fecha_compra,
                compras.`total` AS compras_total,
                compras.`sub_total` AS compras_sub_total,
                compras.`id_iva` AS compras_id_iva,
                compras.`id_proveedor` AS compras_id_proveedor,
                compras.`numero_factura_compra` AS compras_numero_factura_compra,
                compras.`numSerie` AS compras_num_serie,
                compras.`txtEmision` AS compras_txtEmision,
                compras.`txtNum` AS compras_txtNum,
                detalle_compras.`id_detalle_compra` AS detalle_compras_id_detalle_compra,
                detalle_compras.`cantidad` AS detalle_compras_cantidad,
                detalle_compras.`valor_unitario` AS detalle_compras_valor_unitario,
                detalle_compras.`valor_total` AS detalle_compras_valor_total,
                detalle_compras.`id_compra` AS detalle_compras_id_compra,
                detalle_compras.`id_producto` AS detalle_compras_id_producto,
                detalle_compras.`idBodega` AS detalle_compras_id_bodega,
                bodegas.`detalle` AS bodega_detalle,
                bodegas.`id` AS id_bodega,
                proveedores.`nombre` AS nombre_proveedor
                
            FROM `compras` compras INNER JOIN `detalle_compras` detalle_compras 
			ON compras.`id_compra` = detalle_compras.`id_compra` 
			
			LEFT JOIN `bodegas` bodegas  ON detalle_compras.`idBodegaInventario` = bodegas.`id` 
			INNER JOIN `proveedores` proveedores  ON compras.`id_proveedor` = proveedores.`id_proveedor` 
            INNER JOIN productos ON
	            productos.id_producto = detalle_compras.id_producto
            INNER JOIN cantBodegas ON 
	            cantBodegas.idProducto = productos.codigo
			WHERE compras.`id_empresa` = '".$sesion_id_empresa."'  and
			detalle_compras.id_compra='".$id_factura."' and productos.id_producto='".$cod."' ";
			
// 			if($bodega !=0){
// 			    $sql2 .= " and detalle_compras.`idBodegaInventario`='".$bodega."' ";
// 			}

			$sql2 .= " GROUP BY  detalle_compras.`id_detalle_compra`; ";

            
                $contador2 = 0;
                $resp2 = mysql_query($sql2);
                $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
                while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
                {
                    $compras_id_compra = $row2['compras_id_compra'];
                    
                    $compras_num_serie = $row2['compras_num_serie'];
                    $compras_txtEmision = $row2['compras_txtEmision'];
                    $compras_txtNum = $row2['compras_txtNum'];
                    $detalleBodega=$row2['bodega_detalle'];
                    $nombre_proveedor = $row2['nombre_proveedor'];
                    $detalle_compras_id_detalle_compra = $row2['detalle_compras_id_detalle_compra'];
                    $compras_numero_factura_compra = $row2['compras_numero_factura_compra'];
                    $detalle_compras_valor_unitario = $row2['detalle_compras_valor_unitario'];
                    $detalle_compras_valor_unitario = number_format($detalle_compras_valor_unitario, 2, '.', '');
                    $detalle_compras_cantidad = $row2['detalle_compras_cantidad'];
                    
                    $valTotalCompra = $detalle_compras_cantidad * $detalle_compras_valor_unitario;
                    $valTotalCompra = number_format($valTotalCompra, 2, '.', '');
                    $cont++;
                    $contador2++;
                    // calculo saldo
                    $saldoCant = $saldoCant + $detalle_compras_cantidad;
                    $saldoVT = $saldoVT + $valTotalCompra;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', ''); ?>

                    <tr id="tr_<?php echo $id_kardes; ?>">
                        <td><?php echo $fecha; ?></td>
                        <td><?php echo $detalle . " Seg&uacute;n Registro de compra Nro. "; ?><?php echo $compras_numero_factura_compra; ?> a bodega <?php echo $detalleBodega; ?></td>
                        <td><?php echo $detalle_compras_cantidad; ?></td>
                        <td><?php echo $detalle_compras_valor_unitario; ?></td>
                        <td><?php echo $valTotalCompra; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $saldoCant; ?></td>
                        <td><?php echo $saldoVU; ?></td>
                        <td><?php echo $saldoVT; ?></td>
                    </tr>
                    
                <?php }

            }
            if($detalle == "Venta"){
             
                $sql3 = "SELECT
                 ventas.`id_venta` AS ventas_id_venta,
                 ventas.`fecha_venta` AS ventas_fecha_venta,
                 ventas.`estado` AS ventas_estado,
                 ventas.`total` AS ventas_total,
                 ventas.`sub_total` AS ventas_sub_total,
                 ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
                 ventas.`fecha_anulacion` AS ventas_fecha_anulacion,
                 ventas.`descripcion` AS ventas_descripcion,
                 ventas.`id_iva` AS ventas_id_iva,
                 ventas.`id_usuario` AS ventas_id_usuario,
                 ventas.`id_cliente` AS ventas_id_cliente,
                 detalle_ventas.`id_detalle_venta` AS detalle_ventas_id_detalle_venta,
                 detalle_ventas.`cantidad` AS detalle_ventas_cantidad,
                 detalle_ventas.`estado` AS detalle_ventas_estado,
                 detalle_ventas.`v_unitario` AS detalle_ventas_v_unitario,
                 detalle_ventas.`v_total` AS detalle_ventas_v_total,
                 detalle_ventas.`id_venta` AS detalle_ventas_id_venta,
                 detalle_ventas.`id_kardex` AS detalle_ventas_id_producto,
                 bodegas.`detalle` AS bodega_detalle,
                 bodegas.`id` AS id_bodega,
                 clientes.`nombre` AS nomre_cliente,
                 clientes.`apellido` AS apellido
                
            FROM
            
                 `ventas` ventas INNER JOIN `detalle_ventas` detalle_ventas 
				 ON ventas.`id_venta` = detalle_ventas.`id_venta` 
				  LEFT JOIN `bodegas` bodegas  ON detalle_ventas.`idBodegaInventario` = bodegas.`id`
				  INNER JOIN `clientes` clientes  ON clientes.`id_cliente` = ventas.`id_cliente`
                  INNER JOIN productos ON
	                productos.id_producto = detalle_ventas.id_servicio
                INNER JOIN cantBodegas ON 
	                cantBodegas.idProducto = productos.codigo
				 WHERE ventas.`id_empresa` = '".$sesion_id_empresa."' and ventas.estado='Activo' AND
				 detalle_ventas.id_venta='".$id_factura."' and productos.id_producto='".$cod."' ";
				 
				//  if($bodega!=0){
				//      $sql3 .=" and detalle_ventas.`idBodegaInventario`='".$bodega."' ";
				//  }

				 $sql3 .=" GROUP BY detalle_ventas.id_detalle_venta; ";
				//  	echo $sql3;
			
                $contador3 = 0;
                $resp3 = mysql_query($sql3);
                $numero_filas3 = mysql_num_rows($resp3); // obtenemos el número de filas
                while($row3=mysql_fetch_array($resp3))//permite ir de fila en fila de la tabla
                {
                    $ventas_id_venta = $row3['ventas_id_venta'];
                    $detalle_ventas_id_detalle_venta = $row3['detalle_ventas_id_detalle_venta'];
                    $ventas_numero_factura_venta = $row3['ventas_numero_factura_venta'];
                    $detalle_ventas_v_unitario = $row3['detalle_ventas_v_unitario'];
                    $detalle_ventas_v_unitario = number_format($detalle_ventas_v_unitario, 2, '.', '');
                    $detalle_ventas_cantidad = $row3['detalle_ventas_cantidad'];
                    $detalleBodega=$row3['bodega_detalle'];
                    
                    $nomre_cliente=$row3['nomre_cliente'];
                    $apellido=$row3['apellido'];
                    //$valTotalVenta = $detalle_ventas_cantidad * $detalle_ventas_v_unitario;
                    //$valTotalVenta = number_format($valTotalVenta, 2, '.', '');
                    $cont++;
                    $contador3++;
                     // calculo saldo
                    $detalle_ventas_v_unitario = $saldoVU;
                    $salidaVT = $detalle_ventas_cantidad * $detalle_ventas_v_unitario;
                    $salidaVT = number_format($salidaVT, 2, '.', '');
                    $saldoCant = $saldoCant - $detalle_ventas_cantidad;
                    $saldoVT = $saldoVT - $salidaVT;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');
                    
                  
                    ?>
                    <tr id="tr_<?php echo $id_kardes; ?>">

                        <td><?php echo $fecha; ?></td>
                        <td><?php echo $detalle . " Seg&uacute;n Fac. Nro. "; ?><?php echo $ventas_numero_factura_venta; ?> desde bodega <?php echo $detalleBodega; ?></td>
                        <td><?php  ?></td>
                        <td><?php  ?></td>
                        <td><?php  ?></td>
                        <td><?php echo $detalle_ventas_cantidad; ?></td>
                        <td><?php echo $detalle_ventas_v_unitario; ?></td>
                        <td><?php echo $salidaVT; ?></td>
                        <td><?php echo $saldoCant; ?></td>
                        <td><?php echo $saldoVU; ?></td>
                        <td><?php echo $saldoVT; ?></td>
                    </tr>

                    <?php 
                    
                }
            }
            
            	if($detalle == "Egreso"){
                $sql3 = "SELECT
                 egresos.`id_egreso` AS egresos_id_egreso,
                 egresos.`fecha` AS egresos_fecha,
                 egresos.`estado` AS egresos_estado,
                 egresos.`total` AS egresos_total,
                 egresos.`sub_total` AS egresos_sub_total,
                 egresos.`numero` AS egresos_numero,
                 egresos.`fecha_anulacion` AS egresos_fecha_anulacion,
                 egresos.`descripcion` AS egresos_descripcion,
                 egresos.`id_iva` AS egresos_id_iva,
                 detalle_egresos.`id_detalle_egreso` AS detalle_egresos_id_detalle_egreso,
                 detalle_egresos.`cantidad` AS detalle_egresos_cantidad,
                 detalle_egresos.`estado` AS detalle_egresos_estado,
                 detalle_egresos.`estado` AS detalle_egresos_estado,
                 detalle_egresos.`v_unitario` AS detalle_egresos_v_unitario,
                 detalle_egresos.`v_total` AS detalle_egresos_v_total,
                 detalle_egresos.`id_egreso` AS detalle_egresos_id_egreso,
                 detalle_egresos.`id_producto` AS detalle_egresos_id_producto
            FROM
                 `egresos` egresos 
                 INNER JOIN `detalle_egresos` detalle_egresos 
				 ON egresos.`id_egreso` = detalle_egresos.`id_egreso` 
				   INNER JOIN productos ON productos.id_producto = detalle_egresos.`id_producto`
				 WHERE egresos.`id_empresa` = '".$sesion_id_empresa."' and 
				 
				 detalle_egresos.id_egreso='".$id_factura."' ";
				 
				//  if($bodega!=0){
				//      $sql3 .= " and detalle_egresos.`bodega`='".$bodega."' ";
				//  }
				
				$sql3 .= "and productos.id_producto='".$cod."' ";

                $contador3 = 0;
                $resp3 = mysql_query($sql3);
          //      $numero_filas3 = mysql_num_rows($resp3); // obtenemos el número de filas
                while($row3=mysql_fetch_array($resp3))//permite ir de fila en fila de la tabla
                {
                    $egresos_id_egreso = $row3['egresos_id_egreso'];
                    $detalle_egresos_id_detalle_egreso = $row3['detalle_egresos_id_detalle_egreso'];
                    $egresos_numero = $row3['egresos_numero'];
                    $detalle_egresos_v_unitario = $row3['detalle_egresos_v_unitario'];
     //               $detalle_egresos_v_unitario = number_format($detalle_egresos_v_unitario, 2, '.', '');
	//				$detalle_ventas_v_unitario  = number_format($detalle_ventas_v_unitario,  2, '.', '');
				    $detalle_egresos_cantidad = $row3['detalle_egresos_cantidad'];
                    $cont++;
                    $contador3++;
                     // calculo saldo
                    $detalle_egresos_v_unitario = $saldoVU;
                    $salidaVT = $detalle_egresos_cantidad * $detalle_egresos_v_unitario;
                    $salidaVT = number_format($salidaVT, 2, '.', '');
                    $saldoCant = $saldoCant - $detalle_egresos_cantidad;
                    $saldoVT = $saldoVT - $salidaVT;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
                    if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
                    }else{
                        $saldoVU = floatval($saldoVT / $saldoCant);
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');
                    
                          
                     $sqlTransferencia= "SELECT transferencias.*,ingresos.id_ingreso, detalle_ingresos.bodega as bodegaIngreso, egresos.id_egreso, detalle_egresos.bodega as bodegaEgreso, (SELECT bodegas.detalle from bodegas where bodegas.id= detalle_ingresos.bodega) as nombreBodegaIngreso,(SELECT bodegas.detalle from bodegas where bodegas.id= detalle_egresos.bodega) as nombreBodegaEgreso FROM `transferencias` inner join ingresos on ingresos.id_ingreso = transferencias.id_ingreso INNER JOIN egresos on egresos.id_egreso= transferencias.id_egreso INNER join detalle_ingresos on detalle_ingresos.id_ingreso = ingresos.id_ingreso INNER JOIN detalle_egresos on detalle_egresos.id_egreso = egresos.id_egreso 
                     
                     WHERE transferencias.`id_egreso`='".$egresos_id_egreso."' ";
            
                     
                     $resultTransferencia = mysql_query($sqlTransferencia);
                    $existeEnTransferencia = mysql_num_rows($resultTransferencia);
                    $numTrans=0;
                    $bodegaOrigen=0;
                    $bodegaDestino=0;
                    while($rowT = mysql_fetch_array($resultTransferencia)){
                         $numTrans = $rowT['num_trans'];
                         $bodegaOrigen = $rowT['nombreBodegaEgreso'];
                         $bodegaDestino= $rowT['nombreBodegaIngreso'];
                    }
                    
                    
                    if($contador3%2==0){
                    ?>
                    <tr class="odd" id="tr_<?php echo $id_kardes;?>">
                
                        <td><?php echo $fecha;?></td>
                        <?php
                        if($existeEnTransferencia>0){
                            ?>
                               <td><?php echo $detalle." Seg&uacute;n transferencia Nro.$numTrans desde bodega $bodegaOrigen  a la bodega $bodegaDestino " ?></a></td>
                               <?php
                        }else{
                            ?>
                              <td><?php echo $detalle." Seg&uacute;n Egreso Nro. ";?><a href="javascript: abrirPdfFacturaVenta(<?php echo $egresos_id_egreso;?>);" title="Ver Factura Venta" style="color: blue; text-decoration: underline"><?php echo $egresos_numero; ?></a></td>
                        <?php
                        }  ?>  
                        
                        <td><?php  ?></td>
                        <td><?php  ?></td>
                        <td><?php  ?></td>
                        <td><?php echo $detalle_egresos_cantidad; ?></td>
                        <td><?php echo $detalle_egresos_v_unitario;?></td>
                        <td><?php echo $salidaVT; ?></td>
                        <td><?php echo $saldoCant; ?></td>
                        <td><?php echo $saldoVU; ?></td>
                        <td><?php echo $saldoVT; ?></td>
                    </tr>
                    <?php }

                    if($contador3%2==1){
                    ?>
                    <tr  class="even" id="tr_<?php echo $id_kardes; ?>">
                        <td><?php echo $fecha;?></td>
                        <?php
                        if($existeEnTransferencia>0){
                            ?>
                               <td><?php echo $detalle." Seg&uacute;n transferencia Nro.$numTrans desde bodega $bodegaOrigen  a la bodega $bodegaDestino " ?></a></td>
                               <?php
                        }else{
                            ?>
                              <td><?php echo $detalle." Seg&uacute;n Egreso Nro. ";?><a href="javascript: abrirPdfFacturaVenta(<?php echo $egresos_id_egreso;?>);" title="Ver Factura Venta" style="color: blue; text-decoration: underline"><?php echo $egresos_numero; ?></a></td>
                        <?php
                        }  ?>
                        <td><?php  ?></td>
                        <td><?php  ?></td>
                        <td><?php  ?></td>
                        <td><?php echo $detalle_egresos_cantidad; ?></td>
                        <td><?php echo $detalle_egresos_v_unitario;?></td>
                        <td><?php echo $salidaVT; ?></td>
                        <td><?php echo $saldoCant; ?></td>
                        <td><?php echo $saldoVU; ?></td>
                        <td><?php echo $saldoVT; ?></td>                  
                    </tr>
                    <?php }
                }
            }
            
            	if($detalle == "Devolucion Venta"){
				//echo "entro";
                $sql2 = "
				SELECT
					devolucion.`id_devolucion` AS devolucion_id_devolucion,
					devolucion.`fecha` AS devolucion_fecha,
					devolucion.`cantidad` AS devolucion_cantidad,
					devolucion.`valor_unitario` AS devolucion_valor_unitario	
                FROM `devolucion` devolucion 
                INNER JOIN productos ON productos.id_producto = devolucion.id_producto

				WHERE devolucion.`id_empresa` = '".$sesion_id_empresa."'  and
			          devolucion.id_factura='".$id_factura."' and
					  productos.id_producto='".$cod."'; ";
                    // echo("".$sql2."      ");
                   
                $contador2 = 0;
                $resp2 = mysql_query($sql2);
                $numero_filas2 = mysql_num_rows($resp2); // obtenemos el número de filas
                while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
                {
                    $devolucion_id_devolucion = $row2['devolucion_id_devolucion'];
					$devolucion_cantidad = $row2['devolucion_cantidad'];
					$devolucion_valor_unitario = $row2['devolucion_valor_unitario'];
					
					$cont++;
                    $contador2++;
                    
                    $ventas_numero_factura_venta='';
                     $sql3 = "SELECT
                 ventas.`id_venta` AS ventas_id_venta,
                 ventas.`numero_factura_venta` AS ventas_numero_factura_venta
                 FROM ventas WHERE id_venta= '".$id_factura."' ";
                 $result3 = mysql_query($sql3);
                 while($rV = mysql_fetch_array($result3) ){
                     $ventas_numero_factura_venta =$rV['ventas_numero_factura_venta'];
                 }
                    // calculo saldo
				//	$valTotalCompra = $detalle_compras_cantidad * $detalle_compras_valor_unitario;
                //  $valTotalCompra = number_format($valTotalCompra, 2, '.', '');
                  
				//	$devolucion_valor_unitario = $saldoVU;
                  //$devolucion_valor_unitario = $saldoVU;
					$valorTotalDevolucion=$devolucion_cantidad*$devolucion_valor_unitario;
                    $valorTotalDevolucion = number_format($valorTotalDevolucion, 2, '.', '');
                  
					$saldoCant = $saldoCant + $devolucion_cantidad;
                    $saldoVT = $saldoVT + $valorTotalDevolucion;
                    $saldoVT = number_format($saldoVT, 2, '.', '');
					
					if($saldoVT == 0 && $saldoCant == 0){
                        $saldoVU = 0;
						$saldoVT=0;
                    }
					else
					{
						IF ($saldoCant<>0)
						{
						$saldoVU = floatval($saldoVT / $saldoCant);	
						}
                        
                    }
                    $saldoVU = number_format($saldoVU, 2, '.', '');

                    if($contador2%2==0){                    
                    ?>
                    <tr class="odd" id="tr_<?php echo $id_kardes;?>">
                        <td><?php echo $fecha;?></td>
                        <td><?php echo $detalle. " Seg&uacute;n Fac. Nro. "; ?><?php echo $ventas_numero_factura_venta; ?></td>
                     	
						<td><?php echo $devolucion_cantidad; ?></td>
                        <td><?php echo $devolucion_valor_unitario; ?></td>
                        <td><?php echo $valTotalDevolucion; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $saldoCant; ?></td>
                        <td><?php echo $saldoVU; ?></td>
                        <td><?php echo $saldoVT; ?></td>
                        <td><a href="javascript: EliminarDevolucion(<?=$id_kardes?>,1);" title="Deshacer Devolución"><img src="images/delete.png" /></a></td>

                    </tr>
                    <?php }

                    if($contador2%2==1){
                    ?>
                    <tr  class="even" id="tr_<?php echo $id_kardes; ?>">
                        <td><?php echo $fecha;?></td>
						<td><?php echo $detalle. " Seg&uacute;n Fac. Nro. "; ?><?php echo $ventas_numero_factura_venta; ?></td>
                     	<td><?php echo $devolucion_cantidad; ?></td>
                        <td><?php echo $devolucion_valor_unitario; ?></td>
                        <td><?php echo $valorTotalDevolucion; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $saldoCant; ?></td>
                        <td><?php echo $saldoVU; ?></td>
                        <td><?php echo $saldoVT; ?></td>
                        <td><a href="javascript: EliminarDevolucion(<?=$id_kardes?>,1);" title="Deshacer Devolución"><img src="images/delete.png" /></a></td>
                                     
                    </tr>
                    <?php }
                    
                }

            }
     
		}        
                    
                    
                


                
            }
        } else {
            echo "Error en la consulta secundaria: " . mysql_error();
        }
   echo '</tbody></table>';  }
} else {
    echo "Error en la consulta principal: " . mysql_error();
}

        

        ?>
        

        


    
         
        
            
