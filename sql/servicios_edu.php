<?php

    require_once('../ver_sesion.php');
    //Start session
    session_start();
    //Include database connection details
    require_once('../conexion.php');
    $accion = $_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 	$sesion_punto = $_SESSION['userpunto'];
if($accion == '1'){
    // GUARDA SERVICIOS PAGINA: inventarios.php
    try
    {       
        $txtCodigoS=$_POST['txtCodigoS'];
        $txtNombreS=ucwords($_POST['txtNombreS']);
        $cmbCategoria=$_POST['cmbCategoria'];
        $cmbUnidad=$_POST['cmbUnidad'];
        $cmbTipoServicio=$_POST['cmbTipoServicio'];
        $cmbCuentaContable=$_POST['cmbCuentaContable'];
                       
        if($_POST['checkIva'] == true){
            $iva="Si";
            /*
            $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
            $resultIva1=mysql_query($sqlIva1);
            $iva=0;
            while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
            {
                $iva=$rowIva1['iva'];
                $id_iva=$rowIva1['id_iva'];
            }
            */
        }else{
            $iva = "No";
        }    
        $txtDescripcionS=$_POST['txtDescripcionS'];                
        $txtPrecioVenta1S=($_POST['txtPrecioVenta1S']);
        $txtPrecioVenta2S=($_POST['txtPrecioVenta2S']);
        $txtPrecioVenta3S=($_POST['txtPrecioVenta3S']);
        $txtPrecioVenta4S=($_POST['txtPrecioVenta4S']);
        $txtPrecioVenta5S=$_POST['txtPrecioVenta5S'];
        $txtPrecioVenta6S=$_POST['txtPrecioVenta6S'];

        if($txtCodigoS != "" && $txtNombreS != "" && $cmbCategoria != "" && $cmbUnidad != "" && $cmbTipoServicio != "" && $cmbCuentaContable){
          
           //permite sacar el id maximo de servicios
            try 
			{
                $sqlp="Select max(id_servicio) From servicios";
                $resultp=mysql_query($sqlp);
                $id_servicio=0;
                while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
                {
                    $id_servicio=$rowp['max(id_servicio)'];
                }
                $id_servicio++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql1 = "insert into servicios(id_servicio, codigo, nombre, observacion, id_categoria, id_unidad, id_tipo_servicio, precio_venta1, precio_venta2, precio_venta3, precio_venta4, precio_venta5, precio_venta6, iva, id_empresa, id_plan_cuenta)
                  values ('".$id_servicio."','".$txtCodigoS."','".$txtNombreS."','".$txtDescripcionS."','".$cmbCategoria."','".$cmbUnidad."','".$cmbTipoServicio."','".$txtPrecioVenta1S."','".$txtPrecioVenta2S."','".$txtPrecioVenta3S."', '".$txtPrecioVenta4S."', '".$txtPrecioVenta5S."', '".$txtPrecioVenta6S."', '".$iva."', '".$sesion_id_empresa."', '".$cmbCuentaContable."'); ";
          $result1 = mysql_query($sql1);

            if ($result1){
                ?><div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div><?php
            }else{
                ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla servicios: <?php echo mysql_error();?> </p></div> <?php
            }
        }else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
        }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
}

   
   if($accion == '2'){
       // GUARDAR MODIFICACION  PAGINA: productos.php
     try
     {
        $txtIdProducto = $_POST['txtIdProducto'];
        $cmbCategoria = $_POST['cmbCategoria'];
        $txtProducto = ucwords($_POST['txtProducto']);
        $txtExistenciaMinima = $_POST['txtExistenciaMinima'];
        $txtExistenciaMaxima = $_POST['txtExistenciaMaxima'];
        $cmbProveedor = $_POST['cmbProveedor'];
        $txtColor = ucwords($_POST['txtColor']);
        $txtTamano = ucwords($_POST['txtTamano']);
        $txtMarca = ucwords($_POST['txtMarca']);
        $img ="";
        $txtDescripcion = ucwords($_POST['txtDescripcion']);
        $txtIdDetalle = $_POST['txtIdDetalle'];
        $txtGanancia1 = $_POST['txtGanancia1'];
        $txtGanancia2 = $_POST['txtGanancia2'];

        if($txtIdProducto != "" && $cmbCategoria != "" && $txtProducto != "" && $txtColor != "" && $txtMarca != ""){                    

          $sql2 = "update  set producto='".$txtProducto."', existencia_minima='".$txtExistenciaMinima."', existencia_maxima='".$txtExistenciaMaxima."', id_categoria='".$cmbCategoria."', id_proveedor='".$cmbProveedor."', ganancia1='".$txtGanancia1."', ganancia2='".$txtGanancia2."' WHERE id_producto='".$txtIdProducto."'; ";
          $resp2 = mysql_query($sql2) or die("<div class='transparent_ajax_error'><p>Error al modificar el producto: ".mysql_error()."</p></div>");
          // consultas para cambiar el ESTADO de CARGOS a LIBRE
          if($resp2){
              $sql22 = "update detalles set color='".$txtColor."', tamano='".$txtTamano."', marca='".$txtMarca."', imagen='".$img."', descripcion='".$txtDescripcion."', id_producto='".$txtIdProducto."' WHERE id_detalle='".$txtIdDetalle."'; ";
              $resp22 = mysql_query($sql22);
              if ($resp22)
                    {
                        ?> <div class='transparent_ajax_correcto'><p>Registro modificado correctamente.</p></div> <?php
                    }else {
                        ?> <div class='transparent_ajax_error'><p>Error al modicar los detalles del producto: <?php echo mysql_error();?> </p></div> <?php }
          }else{
              ?> <div class='transparent_ajax_error'><p>Error al modificar el producto: <?php echo mysql_error();?> </p></div> <?php
          } 
          
      }
      else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos, <?php echo mysql_error();?></p></div> <?php
      }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }

    
    if($accion == "3"){
    // ELIMINA  PAGINA: productos.php
     try
     {
        if(isset ($_POST['id_producto'])){
          $id_producto = $_POST['id_producto'];

          //cambia el estado a libre y limpia el usuario y cedula
          $sql3 = "delete from  WHERE id_producto='".$id_producto."'; ";
          $result3=mysql_query($sql3);

           if($result3){
               echo "Registro eliminado correctamente.";
              }
         else{
             echo "Error al eliminar los datos: ".mysql_error();
             }          
         }else{
          echo "Fallo en el envio del Formulario: No hay datos, ".mysql_error();
      }

     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }

//  if($accion == "4")
// 	{

//     // Is there a posted query string?
// 		if(isset($_POST['queryString'])) {
// 			$queryString = $_POST['queryString'];
// 			$cont = $_POST['cont'];

//         // Is the string length greater than 0?
//         if(strlen($queryString) >0) 
// 		{          
		
// 		$query1 = "SELECT
//     productos.`id_producto` AS productos_id_producto,
//     productos.`producto` AS productos_nombre,
//     productos.`precio1` AS productos_precio1,
//     productos.`precio2` AS productos_precio2,
//     productos.`id_empresa` AS productos_id_empresa,
//     productos.`iva` AS productos_iva,
//     productos.`codigo` AS productos_codigo,
//     productos.`tipos_compras` AS productos_tipos_compras,
//     productos.`stock` AS productos_stock,
//     productos.`id_cuenta` AS productos_id_cuenta,
//     productos.`grupo` AS productos_grupo,
//     productos.`codigo` AS productos_codigo,
//     centro_costo.`id_centro_costo` AS centro_id,
//     centro_costo.`descripcion` AS centro_descripcion,
//     categorias.`id_categoria` AS categorias_id_categoria,
//     categorias.`categoria` AS categorias_categoria,
//     categorias.`id_empresa` AS categorias_id_empresa,
//     productos.`id_empresa` AS productos_id_empresa,
//     cantBodegas.`cantidad` AS bodega_cantidad,
//     cantBodegas.`idBodega` AS bodega_idBodega,
//     bodegas.`id` AS bodegas_id,
//     bodegas.`detalle` AS bodega_detalle
// FROM
//     `productos`
// INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
// INNER JOIN cantBodegas ON productos.codigo = cantBodegas.`idProducto`
// INNER JOIN bodegas ON bodegas.id = cantBodegas.`idBodega`
// INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
// WHERE
    
//     productos.id_empresa = '$sesion_id_empresa' AND productos.`tipos_compras` = '1' AND
       
//     (productos.codigo LIKE '%$queryString%' || productos.producto LIKE '%$queryString%') 
    
// and bodegas.id_empresa= '$sesion_id_empresa'
                
//                 GROUP BY bodegas.id, productos.codigo 

// UNION
// SELECT
//     productos.`id_producto` AS productos_id_producto,
//     productos.`producto` AS productos_nombre,
//     productos.`precio1` AS productos_precio1,
//     productos.`precio2` AS productos_precio2,
//     productos.`id_empresa` AS productos_id_empresa,
//     productos.`iva` AS productos_iva,
//     productos.`codigo` AS productos_codigo,
//     productos.`tipos_compras` AS productos_tipos_compras,
//     productos.`stock` AS productos_stock,
//     productos.`id_cuenta` AS productos_id_cuenta,
//     productos.`grupo` AS productos_grupo,
//     productos.`codigo` AS productos_codigo,
//     centro_costo.`id_centro_costo` AS centro_id,
//     centro_costo.`descripcion` AS centro_descripcion,
//     categorias.`id_categoria` AS categorias_id_categoria,
//     categorias.`categoria` AS categorias_categoria,
//     categorias.`id_empresa` AS categorias_id_empresa,
//     productos.`id_empresa` AS productos_id_empresa,
//      cantBodegas.`cantidad` AS bodega_cantidad,
//     cantBodegas.`idBodega` AS bodega_idBodega,
//     bodegas.`id` AS bodegas_id,
//     bodegas.`detalle` AS bodega_detalle
// FROM
//     `productos`
// INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
// INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
// LEFT JOIN cantBodegas ON productos.codigo = cantBodegas.`idProducto`
// LEFT JOIN bodegas ON bodegas.id = cantBodegas.`idBodega`

// WHERE
//       productos.id_empresa = '$sesion_id_empresa' AND productos.`tipos_compras` = '2' AND
       
//   (productos.codigo LIKE '%$queryString%' || productos.producto LIKE '%$queryString%') 
    
//  GROUP BY  productos.codigo LIMIT 20
//                 ";

// // 
//             // echo $query1;

            
//             $result = mysql_query($query1) or die(mysql_error());
//             $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
//             if($result) {
                
                
//                 if($numero_filas >0)
// 				{
				    
//                     echo "<table id='tblServicios".$cont."' class='table table-bordered table-condensed table-hover' border='0' >";
//                     echo "<thead>";
//                     echo "<tr>";
//                      echo "<th>Código</th><th>Nombre</th> <th>Stock</th><th>Tipo</th><th>Bodega</th> <th ><a href='javascript: fn_cerrar_div();'><span class='fa fa-close'></span></a></th>";                 
//                      echo "</tr>";
//                     echo "</thead>";
//                     echo "<tbody>";
                    
                    
                    
//                     while ($row = mysql_fetch_array($result))
// 					{
//                         $id_iva = 0;
//                         $iva = 0;

// 						if ($row["productos_iva"]=='Si')
// 						{
// 							$sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$sesion_id_empresa."';";
//                             $result2 = mysql_query($sqliva);
// 							while ($row2 = mysql_fetch_array($result2)) {
// 									$id_iva = $row2["id_iva"];
// 									$iva = $row2["iva"];
// 							}					
// 						}

// 						if ($row["productos_tipos_compras"]=='1' && $row["bodega_cantidad"]>0 ){
// 						  echo '<tr onClick="fill10_edu(\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["productos_codigo"]."*".$row["productos_nombre"]." "."*".$row["productos_precio1"]."*".$id_iva."*".$iva."*".$row["productos_tipos_compras"]."*".$row["productos_iva"]."*".$row["centro_id"]."*".$row["productos_id_cuenta"]."*".$row["centro_descripcion"]."*".$row["bodega_idBodega"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
			
			
// 			$sqlCantidadTotalBodegas = "SELECT SUM(cantidad) as total from cantBodegas,bodegas  where idProducto = '".$row['productos_codigo']."' 
//                 and bodegas.id=cantBodegas.idBodega and bodegas.id_empresa='".$sesion_id_empresa."'  ";
//                 // echo $sqlCantidadTotalBodegas."</br>";
//                 $resultCantidadTotalBodegas=mysql_query($sqlCantidadTotalBodegas);
//             $sumaStockBodegas=0;
//                 while ($rowCantidadTotalBodega = mysql_fetch_array($resultCantidadTotalBodegas)) {
//                     $sumaStockBodegas=   $rowCantidadTotalBodega['total'];
//                 }
//             $sumaStockBodegas= ($sumaStockBodegas=='')?0:$sumaStockBodegas;
            
            
// 						    echo "<td>".$row["productos_codigo"]."</td>";
//                             echo "<td>".$row["productos_nombre"]."</td>";
// 							echo "<td>".$sumaStockBodegas."</td>";
// 							echo "<td>".$row["productos_tipos_compras"]."</td>";
// 							echo "<td>".$row["bodega_detalle"]."</td>";
// 							echo "<td>".$row["bodega_cantidad"]."</td>";
							
							
// 	                        echo "</tr>";
						    
						    
						    
// 						}elseif ($row["productos_tipos_compras"]=='2' ){
// 						    $row["bodega_idBodega"]='0';
// 						  echo '<tr onClick="fill10_edu(\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["productos_codigo"]."*".$row["productos_nombre"]." "."*".$row["productos_precio1"]."*".$id_iva."*".$iva."*".$row["productos_tipos_compras"]."*".$row["productos_iva"]."*".$row["centro_id"]."*".$row["productos_id_cuenta"]."*".$row["centro_descripcion"]."*".$row["bodega_idBodega"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
						

            
// 						    echo "<td>".$row["productos_codigo"]."</td>";
//                             echo "<td>".$row["productos_nombre"]."</td>";
// 							echo "<td>0</td>";
// 							echo "<td>".$row["productos_tipos_compras"]."</td>";
// 							echo "<td></td>";
// 							echo "<td></td>";
							
							
// 	                        echo "</tr>";
						    
						    
// 						}
               
				
            
//                     }

//                         echo "</tbody>";
//                         echo"</table>";
                    
//                 }else{
                    
//                     echo "<th>No existe producto con ese codigo o descripci&oacute;n</th> <th ><a href='javascript: fn_cerrar_div();'><span class='fa fa-close'></span></a></th>";                 

//                 }
//             }
// 			else 
// 			{
// 				echo 'ERROR: Hay un problema con la consulta.';
//             }  
//         } 
// 		else
// 			{
// 				echo 'La longitud no es la permitida.';
// 			}
//     } 
// 	else 
// 		{
//             echo 'No hay ningún acceso directo a este script!';
// 		}
// }


if ($accion == "299") {
    // Obtiene el tiempo antes de ejecutar la consulta
    $inicio = microtime(true);

    // Validar y sanear entradas
    $cont = isset($_POST['cont']) ? intval($_POST['cont']) : 0;
    $queryString = isset($_POST['queryString']) ? mysql_real_escape_string($_POST['queryString']) : '';

    // Consulta a la base de datos
    $sqlLotes = "SELECT * FROM `lotes` WHERE id_empresa=$sesion_id_empresa";
    $resultLotes = mysql_query($sqlLotes);

    if ($resultLotes) {
        $response = []; // Inicializa el array de respuesta
        while ($rowLotes = mysql_fetch_assoc($resultLotes)) {
            $lotes = $rowLotes['numero_lote'];
            $idempresabodegas = $rowLotes['id_empresa'];

            $sqlBodegas = "SELECT * FROM `bodegas` WHERE id_empresa=$idempresabodegas";
            $resultBodegas = mysql_query($sqlBodegas);

            if ($resultBodegas) {
                while ($rowBodegas = mysql_fetch_assoc($resultBodegas)) {
                    $idBodega = $rowBodegas['id'];
                    $sqlBodegasCantidad = "SELECT * FROM `cantBodegas` WHERE idBodega=$idBodega AND idProducto LIKE '%$queryString%' and id_lote='$lotes'";
                    $resultBodegasCantidad = mysql_query($sqlBodegasCantidad);

                    if ($resultBodegasCantidad) {
                        while ($rowBodegasCantidad = mysql_fetch_assoc($resultBodegasCantidad)) {
                            $cantidadProductos = $rowBodegasCantidad['cantidad'];
                            $cantidadIdProductos = $rowBodegasCantidad['idProducto'];
                            $bodegaId = $rowBodegasCantidad['id'];
                            $detalle = $rowBodegasCantidad['detalle'];

                            $sql = "SELECT p.producto, p.id_empresa, p.codigo,
                                           p.tipos_compras as productos_tipos_compras, p.codigo AS productos_codigo,
                                           p.producto AS productos_nombre, p.iva as productos_iva,
                                           p.id_producto as productos_id_producto, p.precio1 as productos_precio1,
                                           p.iva as productos_iva, cc.id_centro_costo AS centro_id,
                                           p.id_cuenta as productos_id_cuenta, cc.descripcion AS centro_descripcion
                                    FROM productos p
                                    INNER JOIN centro_costo cc ON cc.id_centro_costo = p.grupo
                                    WHERE p.codigo='$cantidadIdProductos'";

                            $result = mysql_query($sql);
                            if (!$result) {
                                die('Consulta fallida: ' . mysql_error());
                            }

                            // Obtiene el tiempo después de ejecutar la consulta
                            $fin = microtime(true);

                            // Calcula la diferencia de tiempo
                            $tiempo_ejecucion = $fin - $inicio;

                            $tabla = "<table id='tblServicios$cont' class='table table-bordered table-condensed table-hover' border='0'>";
                            $tabla .= "<thead><tr><th>Código productos1</th><th>Nombre</th><th>Stock</th><th>Tipo</th><th>Bodega</th><th><a href='javascript: fn_cerrar_div();'><span class='fa fa-close'></span></a></th></tr></thead>";
                            $tabla .= "<tbody>";

                            if (mysql_num_rows($result) > 0) {
                                while ($row = mysql_fetch_assoc($result)) {
                                    $id_iva = 0;
                                    $iva = 0;

                                    $sqliva = "SELECT * FROM impuestos WHERE id_iva='" . $row["productos_iva"] . "'";
                                    $result2 = mysql_query($sqliva);
                                    while ($row2 = mysql_fetch_assoc($result2)) {
                                        $id_iva = $row2["id_iva"];
                                        $iva = $row2["iva"];
                                    }

                                    $numRegistro = 0;  // Debes inicializar esta variable correctamente
                                    $response['cont'][$numRegistro] = $cont;
                                    $response['productos_id_producto'][$numRegistro] = $row["productos_id_producto"];
                                    $response['productos_codigo'][$numRegistro] = trim($row["productos_codigo"]);
                                    $response['productos_nombre'][$numRegistro] = trim(addslashes($row["productos_nombre"]));
                                    $response['productos_precio1'][$numRegistro] = $row["productos_precio1"];
                                    $response['id_iva'][$numRegistro] = $id_iva;
                                    $response['iva'][$numRegistro] = $iva;
                                    $response['productos_tipos_compras'][$numRegistro] = $row["productos_tipos_compras"];
                                    $response['productos_iva'][$numRegistro] = $row["productos_iva"];
                                    $response['centro_id'][$numRegistro] = $row["centro_id"];
                                    $response['productos_id_cuenta'][$numRegistro] = $row["productos_id_cuenta"];
                                    $response['centro_descripcion'][$numRegistro] = $row["centro_descripcion"];
                                    $response['bodega_idBodega'][$numRegistro] = $bodegaId;
                                    $response['cantidadEnBodega'][$numRegistro] = $cantidadProductos;

                                    $tabla .= "<tr>";
                                    $tabla .= "<tr onClick=\"llenarProducto('$cont', '{$row["productos_id_producto"]}', '{$row["productos_tipos_compras"]}', '$numRegistro')\" style='cursor: pointer' title='Clic para seleccionar'>";
                                    $tabla .= "<td>{$row["productos_codigo"]}</td>";
                                    $tabla .= "<td>{$row["productos_nombre"]}</td>";
                                    $tabla .= "<td>{$row["productos_tipos_compras"]}</td>";
                                    $tabla .= "<td>$detalle</td>";
                                    $tabla .= "<td>$cantidadProductos</td>";
                                    $tabla .= "<td>$lotes</td>";
                                    $tabla .= "</tr>";
                                }

                                $tabla .= "</tbody></table>";
                                $response['tabla'] = $tabla;
                                $response['tiempo_ejecucion'] = $tiempo_ejecucion;
                            } else {
                                $response['tabla'] = 'No se encontraron productos.';
                                $response['tiempo_ejecucion'] = $tiempo_ejecucion;
                            }

                            echo json_encode($response);
                            // Detenemos la ejecución después de generar la respuesta JSON
                            exit;
                        }
                    } else {
                        die('Consulta fallida: ' . mysql_error());
                    }
                }
            } else {
                die('Consulta fallida: ' . mysql_error());
            }
        }
    } else {
        die('Consulta fallida: ' . mysql_error());
    }

    // Cerrar la conexión
    mysql_close($conexion);
}
if ($accion == "298") {
    // Obtiene el tiempo antes de ejecutar la consulta
    $inicio = microtime(true);

    // Validar y sanear entradas
    $cont = isset($_POST['cont']) ? intval($_POST['cont']) : 0;
    $queryString = isset($_POST['queryString']) ? mysql_real_escape_string($_POST['queryString']) : '';
     $response = [];
    $tabla = "<table id='tblServicios$cont' class='table table-bordered table-condensed table-hover' border='0'>";
                            $tabla .= "<thead><tr><th>Código productos1</th><th>Nombre</th><th>Stock</th><th>Tipo</th><th>Bodega</th><th><a href='javascript: fn_cerrar_div();'><span class='fa fa-close'></span></a></th></tr></thead>";
                            $tabla .= "<tbody>";
     
                           
    // Consulta a la base de datos
    $sqlLotes = "SELECT * FROM `lotes` WHERE id_empresa=$sesion_id_empresa";
    $resultLotes = mysql_query($sqlLotes);
    $numFilas = mysql_num_rows($resultLotes);
    if($numFilas>0){
        if ($resultLotes) {
       
        // Inicializa el array de respuesta
        while ($rowLotes = mysql_fetch_assoc($resultLotes)) {
             $id_lote = $rowLotes['id_lote'];
             $lotes = $rowLotes['numero_lote'];
            $idempresabodegas = $rowLotes['id_empresa'];

            $sqlBodegas = "SELECT * FROM `bodegas` WHERE id_empresa=$idempresabodegas";
            $resultBodegas = mysql_query($sqlBodegas);

            if ($resultBodegas) {
             
                while ($rowBodegas = mysql_fetch_assoc($resultBodegas)) {
                     
                    $idBodega = $rowBodegas['id'];
                     $sqlBodegasCantidad = "SELECT * FROM `cantBodegas` WHERE idBodega=$idBodega AND idProducto LIKE '%$queryString%' and id_lote='$id_lote'";
                    $resultBodegasCantidad = mysql_query($sqlBodegasCantidad);
                    
                    if ($resultBodegasCantidad) {
                        while ($rowBodegasCantidad = mysql_fetch_assoc($resultBodegasCantidad)) {
                            $cantidadProductos = $rowBodegasCantidad['cantidad'];
                            $cantidadIdProductos = $rowBodegasCantidad['idProducto'];
                            $bodegaId = $rowBodegasCantidad['id'];
                            $detalle = $rowBodegasCantidad['detalle'];

                            $sql = "SELECT p.producto, p.id_empresa, p.codigo,
                                           p.tipos_compras as productos_tipos_compras, p.codigo AS productos_codigo,
                                           p.producto AS productos_nombre, p.iva as productos_iva,
                                           p.id_producto as productos_id_producto, p.precio1 as productos_precio1,
                                           p.iva as productos_iva, cc.id_centro_costo AS centro_id,
                                           p.id_cuenta as productos_id_cuenta, cc.descripcion AS centro_descripcion
                                    FROM productos p
                                    INNER JOIN centro_costo cc ON cc.id_centro_costo = p.grupo
                                    WHERE p.codigo='$cantidadIdProductos'";

                            $result = mysql_query($sql);
                            if (!$result) {
                                die('Consulta fallida: ' . mysql_error());
                            }

                          

                            

                            if (mysql_num_rows($result) > 0) {
                                while ($row = mysql_fetch_assoc($result)) {
                                    $id_iva = 0;
                                    $iva = 0;

                                    $sqliva = "SELECT * FROM impuestos WHERE id_iva='" . $row["productos_iva"] . "'";
                                    $result2 = mysql_query($sqliva);
                                    while ($row2 = mysql_fetch_assoc($result2)) {
                                        $id_iva = $row2["id_iva"];
                                        $iva = $row2["iva"];
                                    }

                                    $numRegistro = 0;  // Debes inicializar esta variable correctamente
                                    $response['cont'][$numRegistro] = $cont;
                                    $response['productos_id_producto'][$numRegistro] = $row["productos_id_producto"];
                                    $response['productos_codigo'][$numRegistro] = trim($row["productos_codigo"]);
                                    $response['productos_nombre'][$numRegistro] = trim(addslashes($row["productos_nombre"]));
                                    $response['productos_precio1'][$numRegistro] = $row["productos_precio1"];
                                    $response['id_iva'][$numRegistro] = $id_iva;
                                    $response['iva'][$numRegistro] = $iva;
                                    $response['productos_tipos_compras'][$numRegistro] = $row["productos_tipos_compras"];
                                    $response['productos_iva'][$numRegistro] = $row["productos_iva"];
                                    $response['centro_id'][$numRegistro] = $row["centro_id"];
                                    $response['productos_id_cuenta'][$numRegistro] = $row["productos_id_cuenta"];
                                    $response['centro_descripcion'][$numRegistro] = $row["centro_descripcion"];
                                    $response['bodega_idBodega'][$numRegistro] = $bodegaId;
                                    $response['cantidadEnBodega'][$numRegistro] = $cantidadProductos;

                                    $tabla .= "<tr>";
                                    $tabla .= "<tr onClick=\"llenarProducto('$cont', '{$row["productos_id_producto"]}', '{$row["productos_tipos_compras"]}', '$numRegistro')\" style='cursor: pointer' title='Clic para seleccionar'>";
                                    $tabla .= "<td>{$row["productos_codigo"]}</td>";
                                    $tabla .= "<td>{$row["productos_nombre"]}</td>";
                                    $tabla .= "<td>{$row["productos_tipos_compras"]}</td>";
                                    $tabla .= "<td>$detalle</td>";
                                    $tabla .= "<td>$cantidadProductos</td>";
                                    $tabla .= "<td>$lotes</td>";
                                    $tabla .= "</tr>";
                                }

                                
                              
                            } else {
                               
                            }

                           
                        }
                    } else {
                        die('Consulta fallida: ' . mysql_error());
                    }
                }
            } 
        }
         
        } 
    }else{
          $sqlBodegas = "SELECT * FROM `bodegas` WHERE id_empresa=$sesion_id_empresa";
            $resultBodegas = mysql_query($sqlBodegas);

            if ($resultBodegas) {
             
                while ($rowBodegas = mysql_fetch_assoc($resultBodegas)) {
                     
                    $idBodega = $rowBodegas['id'];
                     $sqlBodegasCantidad = "SELECT * FROM `cantBodegas` WHERE idBodega=$idBodega AND idProducto LIKE '%$queryString%'";
                    $resultBodegasCantidad = mysql_query($sqlBodegasCantidad);
                    
                    if ($resultBodegasCantidad) {
                        while ($rowBodegasCantidad = mysql_fetch_assoc($resultBodegasCantidad)) {
                            $cantidadProductos = $rowBodegasCantidad['cantidad'];
                            $cantidadIdProductos = $rowBodegasCantidad['idProducto'];
                            $bodegaId = $rowBodegasCantidad['id'];
                            $detalle = $rowBodegasCantidad['detalle'];

                             $sql = "SELECT p.producto, p.id_empresa, p.codigo,
                                           p.tipos_compras as productos_tipos_compras, p.codigo AS productos_codigo,
                                           p.producto AS productos_nombre, p.iva as productos_iva,
                                           p.id_producto as productos_id_producto, p.precio1 as productos_precio1,
                                           p.iva as productos_iva, cc.id_centro_costo AS centro_id,
                                           p.id_cuenta as productos_id_cuenta, cc.descripcion AS centro_descripcion
                                    FROM productos p
                                    INNER JOIN centro_costo cc ON cc.id_centro_costo = p.grupo
                                    WHERE p.codigo='$cantidadIdProductos'";

                            $result = mysql_query($sql);
                            if (!$result) {
                                die('Consulta fallida: ' . mysql_error());
                            }

                          

                            

                            if (mysql_num_rows($result) > 0) {
                                while ($row = mysql_fetch_assoc($result)) {
                                    $id_iva = 0;
                                    $iva = 0;

                                    $sqliva = "SELECT * FROM impuestos WHERE id_iva='" . $row["productos_iva"] . "'";
                                    $result2 = mysql_query($sqliva);
                                    while ($row2 = mysql_fetch_assoc($result2)) {
                                        $id_iva = $row2["id_iva"];
                                        $iva = $row2["iva"];
                                    }

                                    $numRegistro = 0;  // Debes inicializar esta variable correctamente
                                    $response['cont'][$numRegistro] = $cont;
                                    $response['productos_id_producto'][$numRegistro] = $row["productos_id_producto"];
                                    $response['productos_codigo'][$numRegistro] = trim($row["productos_codigo"]);
                                    $response['productos_nombre'][$numRegistro] = trim(addslashes($row["productos_nombre"]));
                                    $response['productos_precio1'][$numRegistro] = $row["productos_precio1"];
                                    $response['id_iva'][$numRegistro] = $id_iva;
                                    $response['iva'][$numRegistro] = $iva;
                                    $response['productos_tipos_compras'][$numRegistro] = $row["productos_tipos_compras"];
                                    $response['productos_iva'][$numRegistro] = $row["productos_iva"];
                                    $response['centro_id'][$numRegistro] = $row["centro_id"];
                                    $response['productos_id_cuenta'][$numRegistro] = $row["productos_id_cuenta"];
                                    $response['centro_descripcion'][$numRegistro] = $row["centro_descripcion"];
                                    $response['bodega_idBodega'][$numRegistro] = $bodegaId;
                                    $response['cantidadEnBodega'][$numRegistro] = $cantidadProductos;

                                    $tabla .= "<tr>";
                                    $tabla .= "<tr onClick=\"llenarProducto('$cont', '{$row["productos_id_producto"]}', '{$row["productos_tipos_compras"]}', '$numRegistro')\" style='cursor: pointer' title='Clic para seleccionar'>";
                                    $tabla .= "<td>{$row["productos_codigo"]}</td>";
                                    $tabla .= "<td>{$row["productos_nombre"]}</td>";
                                    $tabla .= "<td>{$row["productos_tipos_compras"]}</td>";
                                    $tabla .= "<td>$detalle</td>";
                                    $tabla .= "<td>$cantidadProductos</td>";
                                    $tabla .= "<td>$lotes</td>";
                                    $tabla .= "</tr>";
                                }

                                
                              
                            } else {
                               
                            }

                           
                        }
                    } else {
                        die('Consulta fallida: ' . mysql_error());
                    }
                }
            } 
    }
 // Obtiene el tiempo después de ejecutar la consulta
                            $fin = microtime(true);

                            // Calcula la diferencia de tiempo
                            $tiempo_ejecucion = $fin - $inicio;
                            $response['tiempo_ejecucion'] = $tiempo_ejecucion;
                            $tabla .= "</tbody></table>";
                              $response['tabla'] = $tabla;
                              
                                 echo json_encode($response);
                            // Detenemos la ejecución después de generar la respuesta JSON
                            exit;

    // Cerrar la conexión
    mysql_close($conexion);
}



if($accion == '10'){
    $idProducto = $_POST['idProducto'];
    $campo = $_POST['tipoPrecio'];
    $sql="SELECT  $campo  FROM `productos` WHERE id_producto=$idProducto ";
    $result = mysql_query($sql);
    $precioSelecionado='';
    while($row = mysql_fetch_array($result)){
        $precioSelecionado= $row[$campo];
    }
    $precioSelecionado = is_null($precioSelecionado)?0:$precioSelecionado;

    echo $precioSelecionado;
}

    if($accion == "50")
	{

    // Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $_POST['queryString'];
			$cont = $_POST['cont'];

        // Is the string length greater than 0?
        if(strlen($queryString) >0) 
		{          
		
		$query1 = "SELECT
    productos.`id_producto` AS productos_id_producto,
    productos.`producto` AS productos_nombre,
    productos.`precio1` AS productos_precio1,
    productos.`precio2` AS productos_precio2,
    productos.`id_empresa` AS productos_id_empresa,
    productos.`iva` AS productos_iva,
    productos.`codigo` AS productos_codigo,
    productos.`tipos_compras` AS productos_tipos_compras,
    productos.`stock` AS productos_stock,
    productos.`id_cuenta` AS productos_id_cuenta,
    productos.`grupo` AS productos_grupo,
    productos.`codigo` AS productos_codigo,
    centro_costo.`id_centro_costo` AS centro_id,
    centro_costo.`descripcion` AS centro_descripcion,
    categorias.`id_categoria` AS categorias_id_categoria,
    categorias.`categoria` AS categorias_categoria,
    categorias.`id_empresa` AS categorias_id_empresa,
    productos.`id_empresa` AS productos_id_empresa,
    cantBodegas.`cantidad` AS bodega_cantidad,
    cantBodegas.`idBodega` AS bodega_idBodega,
    bodegas.`id` AS bodegas_id,
    bodegas.`detalle` AS bodega_detalle
FROM
    `productos`
INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
INNER JOIN cantBodegas ON productos.codigo = cantBodegas.`idProducto`
INNER JOIN bodegas ON bodegas.id = cantBodegas.`idBodega`
INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
WHERE
    
    productos.id_empresa = '$sesion_id_empresa' AND   productos.codigo='$queryString'
    
and bodegas.id_empresa= '$sesion_id_empresa'
                
                GROUP BY bodegas.id, productos.codigo 

LIMIT 1
                ";


            // echo $query1;

            
            $result = mysql_query($query1) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) {
                
                
                if($numero_filas >0)
				{

                    
                    
                    
                    while ($row = mysql_fetch_array($result))
					{
                        $id_iva = 0;
                        $iva = 0;

						if ($row["productos_iva"]=='Si')
						{
							$sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$sesion_id_empresa."';";
                            $result2 = mysql_query($sqliva);
							while ($row2 = mysql_fetch_array($result2)) {
									$id_iva = $row2["id_iva"];
									$iva = $row2["iva"];
							}					
						}

						if ($row["productos_tipos_compras"]=='1' && $row["bodega_cantidad"]>0 ){
// $variable1 = $cont;

// // Variable 2
// $variable2 = 

$variable3 =  $row["productos_id_producto"].",". $row["productos_codigo"].",".$row["productos_nombre"].",". $row["productos_precio1"].",".$id_iva.",". $iva.",".$row["productos_tipos_compras"].",".$row["productos_iva"].",".$row["centro_id"].",".$row["productos_id_cuenta"].",". $row["centro_descripcion"].",".$row["bodega_idBodega"]
;

$json = json_encode( $variable3);

// Imprimir el JSON
echo $json;
						}elseif ($row["productos_tipos_compras"]=='2' ){

// // echo '\''.$cont.'\','.$row["productos_id_producto"].',\''.$row["productos_codigo"]."*".$row["productos_nombre"]." ".",".$row["productos_precio1"].",".$id_iva.",".$iva.",".$row["productos_tipos_compras"].",".$row["productos_iva"].",".$row["centro_id"].",".$row["productos_id_cuenta"].",".$row["centro_descripcion"].",".$row["bodega_idBodega"].'\';';
// $variable1 = $cont;

// // Variable 2
// $variable2 = $row["productos_id_producto"];

// // Variable 3
$variable3 = 
    $row["productos_id_producto"].",". $row["productos_codigo"].",".$row["productos_nombre"].",". $row["productos_precio1"].",".$id_iva.",". $iva.",".$row["productos_tipos_compras"].",".$row["productos_iva"].",".$row["centro_id"].",".$row["productos_id_cuenta"].",". $row["centro_descripcion"].",".$row["bodega_idBodega"]
;


$json = json_encode($variable3);

// Imprimir el JSON
echo $json;

    					}
               
				
            
                    }

                    
                }else{
                    echo 'No existe producto con ese codigo o descripci&oacute;n';
                }
            }
			else 
			{
				echo 'ERROR: Hay un problema con la consulta.';
            }  
        } 
		else
			{
				echo 'La longitud no es la permitida.';
			}
    } 
	else 
		{
            echo 'No hay ningún acceso directo a este script!';
		}
}

  if($accion == "4")
	{  $inicio = microtime(true);
		if(isset($_POST['queryString'])) {
			$queryString = $_POST['queryString'];
			$cont = $_POST['cont'];

        if(strlen($queryString) >0) 
		{          
			$sesion_punto = $_SESSION['userpunto'];
			
		
		     $listado_impuesto = array();
		   $sqliva = "SELECT id_iva,iva FROM impuestos WHERE id_empresa='".$sesion_id_empresa."' ";
            $response['consultas'][]=$sqliva ;
            $result2 = mysql_query($sqliva);
            while ($rowimp = mysql_fetch_array($result2)) {
                $id_iva = $rowimp["id_iva"];
                $listado_impuesto[$id_iva]= $rowimp["iva"];
            }	
            $consultaBodegas1 = "SELECT
            SUM(cantBodegas.cantidad) as cantidad,
            cantBodegas.idBodega,
            cantBodegas.`idProducto`,
            bodegas.id,
            bodegas.detalle
            FROM
            bodegas 
            INNER JOIN cantBodegas ON cantBodegas.idBodega = bodegas.id
            WHERE bodegas.id_empresa='".$sesion_id_empresa."'  GROUP BY cantBodegas.`idProducto`";

            
           
                    $consultaBodegas = "SELECT
                cantBodegas.cantidad,
                cantBodegas.idBodega,
                cantBodegas.`idProducto`,
                bodegas.id,
                bodegas.detalle
                FROM
                bodegas 
                INNER JOIN cantBodegas ON cantBodegas.idBodega = bodegas.id
                WHERE bodegas.id_empresa='".$sesion_id_empresa."'"; 
            
   
           
                $query1 = "SELECT 
            productos.`id_producto` AS productos_id_producto,
            productos.`producto` AS productos_nombre,
            productos.`precio1` AS productos_precio1,
            productos.`precio2` AS productos_precio2,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
            productos.`codigo` AS productos_codigo,
            productos.`tipos_compras` AS productos_tipos_compras,
            productos.`stock` AS productos_stock,
            productos.`id_cuenta` AS productos_id_cuenta,
            productos.`grupo` AS productos_grupo,
            productos.`codigo` AS productos_codigo,
             productos.`proyecto` AS productos_proyecto,
            centro_costo.`id_centro_costo` AS centro_id,
            centro_costo.`descripcion` AS centro_descripcion,
            productos.`id_empresa` AS productos_id_empresa,
            cantidad_bodega.`cantidad` AS bodega_cantidad,
            cantidad_bodega.`idBodega` AS bodega_idBodega,
            cantidad_bodega.`id` AS bodegas_id,
            cantidad_bodega.`detalle` AS bodega_detalle,
             cantidad_total_bodegas.cantidad as total_por_producto
        FROM
            `productos`
        INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
        LEFT JOIN ($consultaBodegas) as cantidad_bodega ON cantidad_bodega.idProducto =  productos.codigo 
        LEFT JOIN ($consultaBodegas1) as cantidad_total_bodegas ON cantidad_total_bodegas.idProducto =  productos.codigo 
        WHERE
            productos.id_empresa = '".$sesion_id_empresa."'  AND productos.`mostrar` = '1' AND
            (productos.codigo LIKE '%".$queryString."%' || productos.producto LIKE '%".$queryString."%') 
        GROUP BY  cantidad_bodega.id, productos.codigo      
         LIMIT 15;";
 
            $result = mysql_query($query1) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            $response =[];
             $response ['sqlBuscador']=$query1;
            if($result) {

                if($numero_filas >0)
				{
				    
                    $tabla= "<table id='tblServicios".$cont."' class='table table-bordered table-condensed table-hover' border='0' >";
                    $tabla.= "<thead>";
                    $tabla.= "<tr>";
                    $tabla.= "<th>Código productos1</th><th>Nombre</th> <th>Stock</th><th>Tipo</th><th>Bodega</th><th>Cantidad en Bodega</th><th ><a href='javascript: fn_cerrar_div();'><span class='fa fa-close'></span></a></th>"; 
                   
                       
                  
                                    
                    $tabla.= "</tr>";
                    $tabla.= "</thead>";
                    $tabla.= "<tbody>";
                    
                    $numRegistro=0;
                    $contadorServicios=0;
                    while ($row = mysql_fetch_array($result))
					{
                        $id_iva = 0;
                        $iva = 0;
                         $id_iva = $row['productos_iva']; 
                        $iva = 0;
                        if( isset($listado_impuesto[$id_iva]) ){
                             $iva = $listado_impuesto[$id_iva];
                        }else{
                             $iva = 0;
                        }
					 
                        $bodega_detalle= '';
                        $bodega_cantidad='';
                        $bodega_id='';
                  
                        
                         $sumaStockBodegas=$row["total_por_producto"];
                        if ($row["productos_tipos_compras"]=='1' ){
                        
                         $bodega_detalle= $row["bodega_detalle"];
                         $bodega_cantidad = $row["bodega_cantidad"];
                         $bodega_id = $row["bodega_idBodega"];
                 
                        
                         if($bodega_cantidad==0){
                             continue;
                         }
                       
                        $tabla.= '<tr onClick="llenarProducto(\''.$cont.'\',\''.$row["productos_id_producto"].'\',\''.$row["productos_tipos_compras"].'\' ,\''.$numRegistro.'\')" style="cursor: pointer" title="Clic para seleccionar"> ';
                        
                         
                            $sumaStockBodegas= ($sumaStockBodegas=='')?0:$sumaStockBodegas;
                            $tabla.= "<td>".$row["productos_codigo"]."</td>";
                            $tabla.= "<td>".$row["productos_nombre"]."</td>";
                            $tabla.= "<td>".$sumaStockBodegas."</td>";
                            $tabla.= "<td>".$row["productos_tipos_compras"]."</td>";
                            $tabla.= "<td>".$bodega_detalle."</td>";
                            $tabla.= "<td>".$bodega_cantidad."</td>";
                            
                          
                           
                            $tabla.= "</tr>";
						}elseif ($row["productos_tipos_compras"]=='2' ){
                           $sumaStockBodegas=0;
                           $bodega_detalle='';
                            if($contadorServicios==0){
                                $tabla.= "SERVICIO";
                                $contadorServicios++;
                            }

                            $tabla.= '<tr onClick="llenarProducto(\''.$cont.'\',\''.$row["productos_id_producto"].'\',\''.$row["productos_tipos_compras"].'\' ,\''.$numRegistro.'\' )" style="cursor: pointer" title="Clic para seleccionar"> ';
                            $sumaStockBodegas= ($sumaStockBodegas=='')?0:$sumaStockBodegas;
                            $tabla.= "<td>".$row["productos_codigo"]."</td>";
                            $tabla.= "<td>".$row["productos_nombre"]."</td>";
                            $tabla.= "<td>".$sumaStockBodegas."</td>";
                            $tabla.= "<td>".$row["productos_tipos_compras"]."</td>";
                            $tabla.= "<td>".$bodega_detalle."</td>";
                            $tabla.= "<td>".$bodega_cantidad."</td>";
                          
                            $tabla.= "</tr>";
						}
                        $response['cont'][$numRegistro]= $cont;
                        $response['productos_id_producto'][$numRegistro]= $row["productos_id_producto"];
                        $response['productos_codigo'][$numRegistro]= trim($row["productos_codigo"]);//0
                        $response['productos_nombre'][$numRegistro]= trim(addslashes($row["productos_nombre"]));//1
                        $response['productos_precio1'][$numRegistro]= $row["productos_precio1"];//2
                        $response['id_iva'][$numRegistro]= $id_iva;//3
                        $response['iva'][$numRegistro]= $iva;//4
                        $response['productos_tipos_compras'][$numRegistro]= $row["productos_tipos_compras"];//5
                        $response['productos_iva'][$numRegistro]= $row["productos_iva"];//6
                        $response['centro_id'][$numRegistro]= $row["centro_id"];//7
                        $response['productos_id_cuenta'][$numRegistro]= $row["productos_id_cuenta"];//8
                        $response['centro_descripcion'][$numRegistro]= $row["centro_descripcion"];//9
                        $response['bodega_idBodega'][$numRegistro]= $bodega_id;//10
                        $response['cantidadEnBodega'][$numRegistro]= $bodega_cantidad;//11
                        $response['productos_proyecto'][$numRegistro]= $row["productos_proyecto"];//12
  
            $numRegistro++;
                    }

                    $tabla.= "</tbody>";
                    $tabla.="</table>";
                    $response['tabla']= $tabla;
                    $response['numFilas']= $numRegistro;
                     $fin = microtime(true);
              	$tiempo_ejecucion = $fin - $inicio;
                            $response['tiempo_ejecucion'] = $tiempo_ejecucion;
                    $response['consulta']= $query;
                    echo json_encode($response);
                }else{
                    echo 'No existe producto con ese codigo o descripci&oacute;n';
                }
            }
			else 
			{
				echo 'ERROR: Hay un problema con la consulta.';
            }
        } 
		else
			{
				echo 'La longitud no es la permitida.';
			}
		
    } 
	else 
		{
            echo 'No hay ningún acceso directo a este script!';
		}
}
if($accion == "40")
{
   
    if(isset($_POST['queryString'])) {
        $queryString = $_POST['queryString'];
        $cont = $_POST['cont'];

    if(strlen($queryString) >0) 
    {          
		
            $consultaBodegas = "SELECT
            cantBodegas.cantidad,
            cantBodegas.idBodega,
            cantBodegas.`idProducto`,
            bodegas.id,
            bodegas.detalle
            FROM
            bodegas INNER JOIN cantBodegas ON cantBodegas.idBodega = bodegas.id
            WHERE bodegas.id_empresa='".$sesion_id_empresa."'";
           
            $query1 = "SELECT
            productos.`id_producto` AS productos_id_producto,
            productos.`producto` AS productos_nombre,
            productos.`precio1` AS productos_precio1,
            productos.`precio2` AS productos_precio2,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
            productos.`codigo` AS productos_codigo,
            productos.`tipos_compras` AS productos_tipos_compras,
            productos.`stock` AS productos_stock,
            productos.`id_cuenta` AS productos_id_cuenta,
            productos.`grupo` AS productos_grupo,
            productos.`codigo` AS productos_codigo,
             productos.`proyecto` AS productos_proyecto,
            centro_costo.`id_centro_costo` AS centro_id,
            centro_costo.`descripcion` AS centro_descripcion,
            categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
            productos.`id_empresa` AS productos_id_empresa,
            cantidad_bodega.`cantidad` AS bodega_cantidad,
            cantidad_bodega.`idBodega` AS bodega_idBodega,
            cantidad_bodega.`id` AS bodegas_id,
            cantidad_bodega.`detalle` AS bodega_detalle
        FROM
            `productos`
        INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
        INNER JOIN ($consultaBodegas) as cantidad_bodega ON cantidad_bodega.idProducto =  productos.codigo 
        INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
        WHERE
            
            productos.id_empresa = '$sesion_id_empresa' AND productos.`tipos_compras` = '1' AND
               
            ( productos.producto LIKE '%$queryString%') 
            
      
                        
                        GROUP BY cantidad_bodega.id, productos.codigo 
        
        UNION
        SELECT
            productos.`id_producto` AS productos_id_producto,
            productos.`producto` AS productos_nombre,
            productos.`precio1` AS productos_precio1,
            productos.`precio2` AS productos_precio2,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
            productos.`codigo` AS productos_codigo,
            productos.`tipos_compras` AS productos_tipos_compras,
            productos.`stock` AS productos_stock,
            productos.`id_cuenta` AS productos_id_cuenta,
            productos.`grupo` AS productos_grupo,
            productos.`codigo` AS productos_codigo,
             productos.`proyecto` AS productos_proyecto,
            centro_costo.`id_centro_costo` AS centro_id,
            centro_costo.`descripcion` AS centro_descripcion,
            categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
            productos.`id_empresa` AS productos_id_empresa,
            '' AS bodega_cantidad,
            '' AS bodega_idBodega,
           '' AS bodegas_id,
           '' AS bodega_detalle
        FROM
            `productos`
        INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
        INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
  
        
        WHERE
              productos.id_empresa = '$sesion_id_empresa' AND productos.`tipos_compras` = '2' AND
               
           (productos.codigo LIKE '%$queryString%' || productos.producto LIKE '%$queryString%') 
            
         GROUP BY  productos.codigo LIMIT 20
                        ";
// echo $query;
        $result = mysql_query($query1) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $response =[];
        $response ['sqlBuscador']=$query1;
        if($result) {

            if($numero_filas >0)
            {
                
                $tabla= "<table id='tblServicios".$cont."' class='table table-bordered table-condensed table-hover' border='0' >";
                $tabla.= "<thead>";
                $tabla.= "<tr>";
                $tabla.= "<th>Código productos1</th><th>Nombre</th> <th>Stock</th><th>Tipo</th><th>Bodega</th> <th ><a href='javascript: fn_cerrar_div();'><span class='fa fa-close'></span></a></th>";                 
                $tabla.= "</tr>";
                $tabla.= "</thead>";
                $tabla.= "<tbody>";
                
                $numRegistro=0;
                $contadorServicios=0;
                while ($row = mysql_fetch_array($result))
                {
                    $id_iva = 0;
                    $iva = 0;

                        // echo "IVA EN EMPRESA";
                        $sqliva = "SELECT * FROM impuestos WHERE  id_iva='".$row["productos_iva"]."' ";
                        $result2 = mysql_query($sqliva);
                        while ($row2 = mysql_fetch_array($result2)) {
                            
                                $id_iva = $row2["id_iva"];
                                $iva = $row2["iva"];
                                
                        }	
                        

                    
                    
                    $response['cont'][$numRegistro]= $cont;
                    $response['productos_id_producto'][$numRegistro]= $row["productos_id_producto"];
                    $response['productos_codigo'][$numRegistro]= trim($row["productos_codigo"]);//0
                    $response['productos_nombre'][$numRegistro]= trim(addslashes($row["productos_nombre"]));//1
                    $response['productos_precio1'][$numRegistro]= $row["productos_precio1"];//2
                    $response['id_iva'][$numRegistro]= $id_iva;//3
                    $response['iva'][$numRegistro]= $iva;//4
                    $response['productos_tipos_compras'][$numRegistro]= $row["productos_tipos_compras"];//5
                    $response['productos_iva'][$numRegistro]= $row["productos_iva"];//6
                    $response['centro_id'][$numRegistro]= $row["centro_id"];//7
                    $response['productos_id_cuenta'][$numRegistro]= $row["productos_id_cuenta"];//8
                    $response['centro_descripcion'][$numRegistro]= $row["centro_descripcion"];//9
                    $response['bodega_idBodega'][$numRegistro]= $row["bodega_idBodega"];//10
                    $response['cantidadEnBodega'][$numRegistro]= $row["bodega_cantidad"];//11
                    $response['productos_proyecto'][$numRegistro]= $row["productos_proyecto"];//11
                    
                  
                    
                    
                    if ($row["productos_tipos_compras"]=='1' && $row["bodega_cantidad"]>0 ){

                    
                    $tabla.= '<tr onClick="llenarProducto(\''.$cont.'\',\''.$row["productos_id_producto"].'\',\''.$row["productos_tipos_compras"].'\' ,\''.$numRegistro.'\')" style="cursor: pointer" title="Clic para seleccionar"> ';

                    }elseif ($row["productos_tipos_compras"]=='2' ){
                      
                        if($contadorServicios==0){
                            $tabla.= "SERVICIO";
                            $contadorServicios++;
                        }

                        $tabla.= '<tr onClick="llenarProducto(\''.$cont.'\',\''.$row["productos_id_producto"].'\',\''.$row["productos_tipos_compras"].'\' ,\''.$numRegistro.'\' )" style="cursor: pointer" title="Clic para seleccionar"> ';

                    }

                 
            
                $sqlCantidadTotalBodegas = "SELECT SUM(cantidad) as total from cantBodegas,bodegas  where idProducto = '".$row['productos_codigo']."' 
                and bodegas.id=cantBodegas.idBodega and bodegas.id_empresa='".$sesion_id_empresa."'  ";
        
        
                $resultCantidadTotalBodegas=mysql_query($sqlCantidadTotalBodegas);
                $sumaStockBodegas=0;
            while ($rowCantidadTotalBodega = mysql_fetch_array($resultCantidadTotalBodegas)) {
                $sumaStockBodegas=   $rowCantidadTotalBodega['total'];
            }
                    $sumaStockBodegas= ($sumaStockBodegas=='')?0:$sumaStockBodegas;
                    $tabla.= "<td>".$row["productos_codigo"]."</td>";
                    $tabla.= "<td>".$row["productos_nombre"]."</td>";
                    $tabla.= "<td>".$sumaStockBodegas."</td>";
                    $tabla.= "<td>".$row["productos_tipos_compras"]."</td>";
                    $tabla.= "<td>".$row["bodega_detalle"]."</td>";
                    $tabla.= "<td>".$row["bodega_cantidad"]."</td>";
                                    
                                    
                    $tabla.= "</tr>";
                    $numRegistro++;
                }

                $tabla.= "</tbody>";
                $tabla.="</table>";
                
                $response['tabla']= $tabla;
                $response['numFilas']= $numRegistro;
          
                $response['consulta']= $query;
                echo json_encode($response);
                
            }else{
                $response['consulta']= 'No existe producto con ese codigo o descripci&oacute;n';
                echo json_encode($response);
               
            }
        }
        else 
        {
            echo 'ERROR: Hay un problema con la consulta.';
        }
          
    } 
    else
        {
            echo 'La longitud no es la permitida.';
        }
} 
else 
    {
        echo 'No hay ningún acceso directo a este script!';
    }
}

 if($accion == "444")
	{
		if(isset($_POST['queryString'])) {
			$queryString = $_POST['queryString'];
			$cont = $_POST['cont'];

        if(strlen($queryString) >0) 
		{          
		
            $consultaBodegas = "SELECT
            cantBodegas.cantidad,
            cantBodegas.idBodega,
            cantBodegas.`idProducto`,
            bodegas.id,
            bodegas.detalle
            FROM
            bodegas INNER JOIN cantBodegas ON cantBodegas.idBodega = bodegas.id
            WHERE bodegas.id_empresa='".$sesion_id_empresa."'";
               
   
            // if($sesion_id_empresa==41){
                $query1 = "SELECT 
            productos.`id_producto` AS productos_id_producto,
            productos.`producto` AS productos_nombre,
            productos.`precio1` AS productos_precio1,
            productos.`precio2` AS productos_precio2,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
            productos.`codigo` AS productos_codigo,
            productos.`tipos_compras` AS productos_tipos_compras,
            productos.`stock` AS productos_stock,
            productos.`id_cuenta` AS productos_id_cuenta,
            productos.`grupo` AS productos_grupo,
            productos.`codigo` AS productos_codigo,
             productos.`proyecto` AS productos_proyecto,
            centro_costo.`id_centro_costo` AS centro_id,
            centro_costo.`descripcion` AS centro_descripcion,
            categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
            productos.`id_empresa` AS productos_id_empresa,
            cantidad_bodega.`cantidad` AS bodega_cantidad,
            cantidad_bodega.`idBodega` AS bodega_idBodega,
            cantidad_bodega.`id` AS bodegas_id,
            cantidad_bodega.`detalle` AS bodega_detalle
        FROM
            `productos`
        INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
        
          INNER JOIN ($consultaBodegas) as cantidad_bodega ON cantidad_bodega.idProducto =  productos.codigo
        
        INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
        WHERE
            
            productos.id_empresa = '".$sesion_id_empresa."' AND productos.`tipos_compras` = '1' AND
               
            (productos.codigo LIKE '%".$queryString."%' || productos.producto LIKE '%".$queryString."%') 
            
      
                        
                        GROUP BY cantidad_bodega.id, productos.codigo 
        
        UNION ALL
        SELECT
            productos.`id_producto` AS productos_id_producto,
            productos.`producto` AS productos_nombre,
            productos.`precio1` AS productos_precio1,
            productos.`precio2` AS productos_precio2,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
            productos.`codigo` AS productos_codigo,
            productos.`tipos_compras` AS productos_tipos_compras,
            productos.`stock` AS productos_stock,
            productos.`id_cuenta` AS productos_id_cuenta,
            productos.`grupo` AS productos_grupo,
            productos.`codigo` AS productos_codigo,
             productos.`proyecto` AS productos_proyecto,
            centro_costo.`id_centro_costo` AS centro_id,
            centro_costo.`descripcion` AS centro_descripcion,
            categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
            productos.`id_empresa` AS productos_id_empresa,
            '' AS bodega_cantidad,
           '' AS bodega_idBodega,
           '' AS bodegas_id,
            '' AS bodega_detalle
        FROM
            `productos`
        INNER JOIN centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
        INNER JOIN categorias ON categorias.id_categoria = productos.`id_categoria`
        
        WHERE
              productos.id_empresa = '".$sesion_id_empresa."' AND productos.`tipos_compras` = '2' AND
               
           (productos.codigo LIKE '%".$queryString."%' || productos.producto LIKE '%".$queryString."%') 
            
         GROUP BY  productos.codigo LIMIT 20;";
            // }
// echo $query1;
            $result = mysql_query($query1) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            $response =[];
             $response ['sqlBuscador']=$query1;
            if($result) {

                if($numero_filas >0)
				{
				    
                    $tabla= "<table id='tblServicios".$cont."' class='table table-bordered table-condensed table-hover' border='0' >";
                    $tabla.= "<thead>";
                    $tabla.= "<tr>";
                    $tabla.= "<th>Código productos1</th><th>Nombre</th> <th>Stock</th><th>Tipo</th><th>Bodega</th> <th ><a href='javascript: fn_cerrar_div();'><span class='fa fa-close'></span></a></th>";                 
                    $tabla.= "</tr>";
                    $tabla.= "</thead>";
                    $tabla.= "<tbody>";
                    
                    $numRegistro=0;
                    $contadorServicios=0;
                    while ($row = mysql_fetch_array($result))
					{
                        $id_iva = 0;
                        $iva = 0;

					                        // echo "IVA EN EMPRESA";
                        $sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_iva='".$row["productos_iva"]."' ";
                         $response['consultas'][]=$sqliva ;
                        $result2 = mysql_query($sqliva);
                        while ($row2 = mysql_fetch_array($result2)) {
                            
                                $id_iva = $row2["id_iva"];
                                $iva = $row2["iva"];
                                
                        }	
                        
                        $response['cont'][$numRegistro]= $cont;
                        $response['productos_id_producto'][$numRegistro]= $row["productos_id_producto"];
                        $response['productos_codigo'][$numRegistro]= trim($row["productos_codigo"]);//0
                        $response['productos_nombre'][$numRegistro]= trim(addslashes($row["productos_nombre"]));//1
                        $response['productos_precio1'][$numRegistro]= $row["productos_precio1"];//2
                        $response['id_iva'][$numRegistro]= $id_iva;//3
                        $response['iva'][$numRegistro]= $iva;//4
                        $response['productos_tipos_compras'][$numRegistro]= $row["productos_tipos_compras"];//5
                        $response['productos_iva'][$numRegistro]= $row["productos_iva"];//6
                        $response['centro_id'][$numRegistro]= $row["centro_id"];//7
                        $response['productos_id_cuenta'][$numRegistro]= $row["productos_id_cuenta"];//8
                        $response['centro_descripcion'][$numRegistro]= $row["centro_descripcion"];//9
                        $response['bodega_idBodega'][$numRegistro]= $row["bodega_idBodega"];//10
                        $response['cantidadEnBodega'][$numRegistro]= $row["bodega_cantidad"];//11
                        $response['productos_proyecto'][$numRegistro]= $row["productos_proyecto"];//11
                        
                        
						if ($row["productos_tipos_compras"]=='1' && $row["bodega_cantidad"]>0 ){

                            $tabla.= '<tr onClick="llenarProducto(\''.$cont.'\',\''.$row["productos_id_producto"].'\',\''.$row["productos_tipos_compras"].'\' ,\''.$numRegistro.'\')" style="cursor: pointer" title="Clic para seleccionar"> ';

						}elseif ($row["productos_tipos_compras"]=='2' ){
                          
                            if($contadorServicios==0){
                                $tabla.= "SERVICIO";
                                $contadorServicios++;
                            }

                            $tabla.= '<tr onClick="llenarProducto(\''.$cont.'\',\''.$row["productos_id_producto"].'\',\''.$row["productos_tipos_compras"].'\' ,\''.$numRegistro.'\' )" style="cursor: pointer" title="Clic para seleccionar"> ';

						}

                     
				
            $sqlCantidadTotalBodegas = "SELECT SUM(cantidad) as total from cantBodegas,bodegas  where idProducto = '".$row['productos_codigo']."' 
                and bodegas.id=cantBodegas.idBodega and bodegas.id_empresa='".$sesion_id_empresa."'  ";
                // echo $sqlCantidadTotalBodegas."</br>";
                $resultCantidadTotalBodegas=mysql_query($sqlCantidadTotalBodegas);
            $sumaStockBodegas=0;
                while ($rowCantidadTotalBodega = mysql_fetch_array($resultCantidadTotalBodegas)) {
                    $sumaStockBodegas=   $rowCantidadTotalBodega['total'];
                }
            $sumaStockBodegas= ($sumaStockBodegas=='')?0:$sumaStockBodegas;
            $tabla.= "<td>".$row["productos_codigo"]."</td>";
            $tabla.= "<td>".$row["productos_nombre"]."</td>";
            $tabla.= "<td>".$sumaStockBodegas."</td>";
            $tabla.= "<td>".$row["productos_tipos_compras"]."</td>";
            $tabla.= "<td>".$row["bodega_detalle"]."</td>";
            $tabla.= "<td>".$row["bodega_cantidad"]."</td>";
							
							
            $tabla.= "</tr>";
            $numRegistro++;
                    }

                    $tabla.= "</tbody>";
                    $tabla.="</table>";
                    $response['tabla']= $tabla;
                    $response['numFilas']= $numRegistro;
              
                    $response['consulta']= $query;
                    echo json_encode($response);
                }else{
                    echo 'No existe producto con ese codigo o descripci&oacute;n';
                }
            }
			else 
			{
				echo 'ERROR: Hay un problema con la consulta.';
            }
        } 
		else
			{
				echo 'La longitud no es la permitida.';
			}
    } 
	else 
		{
            echo 'No hay ningún acceso directo a este script!';
		}
}  
?>