<?php
error_reporting(0);
	session_start();

	require_once('../conexion.php');	
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
//	echo $sesion_id_empresa;

try 
{
	// Is there a posted query string?
    if(isset($_POST['queryString'])) {
        
       
        $queryString = $_POST['queryString'];  
        	$tipos_compras="1";
        
  
        // Is the string length greater than 0?
        if(strlen($queryString) >0) {
            $sqliva = "SELECT * FROM impuestos;";
            $result = mysql_query($sqliva);
            while ($row = mysql_fetch_array($result))
			{
                $id_iva = $row["id_iva"];
                $iva = $row["iva"];
            }
     

        $query="SELECT
        categorias.`id_categoria` AS categorias_id_categoria,
        categorias.`categoria` AS categorias_categoria,
        productos.`id_producto` AS productos_id_producto,
        productos.`producto` AS productos_producto,
        productos.`existencia_minima` AS productos_existencia_minima,
        productos.`existencia_maxima` AS productos_existencia_maxima,
        productos.`stock` AS productos_stock1,
        productos.`costo` AS productos_costo,
        productos.`id_categoria` AS productos_id_categoria,
        productos.`id_proveedor` AS productos_id_proveedor,
        productos.`precio1` AS productos_precio1,
        productos.`precio2` AS productos_precio2,
        productos.`codigo` AS productos_codigo,
      cantidadBodegas.cantidadBodega as productos_stock
    FROM
        `categorias` categorias
    INNER JOIN `productos` productos ON
        categorias.`id_categoria` = productos.`id_categoria`
    INNER JOIN (
       SELECT SUM(cantidad) AS cantidadBodega, idProducto 
       FROM `cantBodegas` 
       INNER JOIN bodegas ON bodegas.id = cantBodegas.idBodega 
       WHERE bodegas.id_empresa= '".$sesion_id_empresa."' 
       GROUP BY idProducto
    ) AS cantidadBodegas ON cantidadBodegas.idProducto =  productos.codigo 
    
    WHERE
      productos.`id_empresa` = '".$sesion_id_empresa."' AND productos.`tipos_compras` = '".$tipos_compras."' AND productos.`producto` LIKE '%$queryString%'
        
    GROUP BY productos.codigo
    LIMIT 10";
    
//     if($sesion_id_empresa==41){
// echo $query;
//     }
                    // echo $query;
		$result = mysql_query($query) or die(mysql_error());
		$numero_filas = mysql_num_rows($result); // obtenemos el número de filas
		if($result) 
		{
			if($numero_filas == 0)
			{
                echo "<center><p><div style'font-size: 20px'class='alert alert-danger'><strong> No hay resultados con el parámetro ingresado. <strong></div></p></center>";
            }
			else
			{
                echo "<table class='table' border='0' ><tr style='border-bottom: 1px solid #CCC;'>
                    <th style='padding-left: 5px; padding-right: 5px;'>Codigo</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Nombre</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Stock</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Categoria</th></tr>";
                        
                while ($row = mysql_fetch_array($result)) 
				{                            
                    echo '<tr onClick="fill13(\''.$row["productos_id_producto"].'\',\''.$row["productos_producto"]." - ".$row["detalles_color"]." - ".$row["detalles_tamano"]." - ".$row["productos_stock"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
                    echo "<td>".$row["productos_codigo"]."</td>";
                    echo "<td>".$row["productos_producto"]."</td>";                           
                          //   echo "<td>".$row["detalles_color"]."</td>";
                          //   echo "<td>".$row["detalles_tamano"]."</td>";
                          //   echo "<td>".$row["detalles_marca"]."</td>";
                             
                    echo "<td>".$row["productos_stock"]."</td>";
                    echo "<td>".$row["categorias_categoria"]."</td>";
                             // echo "<td>".$row["proveedores_nombre_comercial"]."</td>";
                    echo "</tr>";                            
                }
                echo"</table>";
            }
        } 
		else 
		{
            echo 'ERROR: Hay un problema con la consulta.';
        }
    } else {
            echo 'La longitud no es la permitida.';
        }
    } else {
            echo 'No hay ningún acceso directo a este script!';
    }
}catch (Exception $e) { echo "".$e; }
?>