<?php
	session_start();
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

try 
{
	require_once('../conexion.php');
	// Is there a posted query string?
    if(isset($_POST['queryString'])) 
	{
        $queryString = $_POST['queryString'];
		$tipoCompra=$_POST['tipoCompra'];
       // $idProveedor = $_POST['idProveedor'];
 
        // Is the string length greater than 0?
        if(strlen($queryString) >0) 
		{
			$id_iva_impuesto=0;
			$iva_impuesto=0;
			$sqliva = "SELECT * FROM impuestos WHERE estado='Activo';";
            $result = mysql_query($sqliva);
            while ($row = mysql_fetch_array($result)) {
                $id_iva_impuesto = $row["id_iva"];
                $iva_impuesto = $row["iva"];
            }
            $query = "SELECT
             productos.id_producto AS productos_id_producto,
             productos.producto AS productos_producto,
             productos.existencia_minima AS productos_existencia_minima,
             productos.existencia_maxima AS productos_existencia_maxima,
             productos.stock AS productos_stock,
             productos.costo AS productos_costo,
             productos.id_categoria AS productos_id_categoria,
             productos.id_proveedor AS productos_id_proveedor,
			 productos.id_cuenta AS productos_id_cuenta,
			 productos.iva AS productos_iva,
			 
             detalles.id_detalle AS detalles_id_detalle,
             detalles.color AS detalles_color,
             detalles.tamano AS detalles_tamano,
             detalles.marca AS detalles_marca,
             detalles.imagen AS detalles_imagen,
             detalles.descripcion AS detalles_descripcion,
             detalles.id_producto AS detalles_id_producto,
             proveedores.id_proveedor AS proveedores_id_proveedor,
             proveedores.nombre_comercial AS proveedores_nombre_comercial,
             proveedores.nombre AS proveedores_nombre,
             proveedores.direccion AS proveedores_direccion,
             proveedores.ruc AS proveedores_ruc,
             proveedores.telefono AS proveedores_telefono,
             proveedores.movil AS proveedores_movil,
             proveedores.fax AS proveedores_fax,
             proveedores.email AS proveedores_email,
             proveedores.web AS proveedores_web,
             proveedores.observaciones AS proveedores_observaciones,
             proveedores.id_ciudad AS proveedores_id_ciudad
        FROM
             productos productos INNER JOIN detalles detalles ON productos.id_producto = detalles.id_producto
             INNER JOIN proveedores proveedores ON productos.id_proveedor = proveedores.id_proveedor
			WHERE productos.`id_empresa`='".$sesion_id_empresa."' and productos.`tipos_compras`='".$tipoCompra."' 
			      and  productos.producto LIKE '%$queryString%' LIMIT 10; ";

            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
			$ivax="";
			$iva=0;
			$id_iva=0;
			while ($row = mysql_fetch_array($result)) 
			{
				$ivax=$row["productos_iva"];
            }        
			if ($ivax=="Si")
			{
				$iva=$iva_impuesto;
				$id_iva=$id_iva_impuesto;
			}
			$result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
			
            if($result) 
			{
              if($numero_filas == 0){
                         echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
                    }else{
                        echo "<table class='table-sm table-bordered'  >
                        
                    <tr style='border-bottom: 1px solid #CCC;'>
                        <th style='padding-left: 5px; padding-right: 5px;'>Nombre</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Proveedor</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Color</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Tamaño</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Marca</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Stock</th>
                        <th style='padding-left: 5px; padding-right: 5px;'>Cuenta</th>
                    </tr>";
                    
                         while ($row = mysql_fetch_array($result)) {                            
                             echo '<tr onClick="AgregarProducto(\''.$row["productos_id_producto"]."-".$row["productos_producto"]." ".$row["detalles_color"]." ".$row["detalles_tamano"]."-".$row["productos_costo"]."-".$row["productos_stock"]."-".$row["productos_existencia_maxima"]."-".$iva."-".$id_iva."-".$row["productos_id_cuenta"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
                             echo "<td>".$row["productos_producto"]."</td>";
                             echo "<td>".$row["proveedores_nombre_comercial"]."</td>";
                             echo "<td>".$row["detalles_color"]."</td>";
                             echo "<td>".$row["detalles_tamano"]."</td>";
                             echo "<td>".$row["detalles_marca"]."</td>";
                             echo "<td>".$row["productos_stock"]."</td>";
							 echo "<td>".$row["productos_id_cuenta"]."</td>";
							 echo "<td>".$iva."</td>";
							 echo "<td>".$id_iva."</td>";
							 
							 
							 
                            echo "</tr>";                            
                        }
                        echo"</table>";
                    }
                } else {
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