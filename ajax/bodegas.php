<?php
//include "../conexion.php";
	
	require_once('../conexion.php');
    session_start();
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $id_producto=$_POST['id_producto'];

	$query = "SELECT
            productos.`id_producto` AS productos_id_producto,
            productos.`producto` AS productos_nombre,
            productos.`costo` AS productos_costo,
            productos.`id_empresa` AS productos_id_empresa,
            productos.`iva` AS productos_iva,
			productos.`codigo` AS productos_codigo,
			productos.`tipos_compras` AS productos_tipos_compras,
			productos.`id_empresa` AS productos_id_empresa,
			
			categorias.`id_categoria` AS categorias_id_categoria,
            categorias.`categoria` AS categorias_categoria,
            categorias.`id_empresa` AS categorias_id_empresa,
            
            cantBodegas.`idProducto` AS producto_cantidad,
            cantBodegas.`idBodega` AS bodega_cantidad,
            cantBodegas.`cantidad` AS cantidad_cantidad,
            
            centro_costo.`id_centro_costo` AS id_centro_costo,
            centro_costo.`descripcion` AS centro_nombre,
            centro_costo.`id_cuenta` AS centro_id_cuenta
        FROM
            `categorias`categorias 
            INNER JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria`
            INNER JOIN `cantBodegas` cantBodegas ON productos.`id_producto` = cantBodegas.`idProducto`
            INNER JOIN `centro_costo` centro_costo ON cantBodegas.`idBodega` = centro_costo.`id_centro_costo`
            
            WHERE productos.`id_empresa`='".$sesion_id_empresa."' and productos.`id_producto` ='".$id_producto."' ";


     	    $resp0 = mysql_query($query);

        while($row0=mysql_fetch_array($resp0))//permite ir de fila en fila de la tabla
        {
                $nombre=$row0['productos_nombre'];	
                $ubicacion=$row0['centro_nombre'];	
                $cantidad=$row0['cantidad_cantidad'];	
            
                echo "<div class='row'><div class='col-lg-6 border p-3'>".$ubicacion."</div><div class='col-lg-6 border p-3'>".$cantidad."</div></div>";
            
        }
?>


<div class="row">
    <div class="col-lg-12 text-right">
       <a href="javascript: fn_cerrar();" class="btn btn-lg fx-3 text-decoration-none " >Cerrar</a>
    </div>
</div>


