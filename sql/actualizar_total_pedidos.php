<?php
error_reporting(0);
	//require_once('../ver_sesion.php');

        date_default_timezone_set('America/Guayaquil');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

    $txtAccion = $_POST['txtAccion'];
    $est = $_POST['est'];
    $emi = $_POST['emi'];
    $documento = $_POST['documento'];

    $id_empresa_cookies = $_COOKIE["id_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

    echo $sqlDetalleVentas = "SELECT
    pedidos.pedido,
    pedidos.fecha_pedido,
    detalle_pedido.id_detalle_pedido,
    detalle_pedido.v_total,
    SUM(detalle_pedido.total_iva) as suma_iva ,
    detalle_pedido.id_servicio,
    detalle_pedido.id_empresa
FROM
    `detalle_pedido`
INNER JOIN pedidos ON pedidos.pedido = detalle_pedido.pedido
 GROUP BY pedidos.pedido";
    echo '<br>';
    $resultVentas = mysql_query( $sqlDetalleVentas );

    while( $rowVentas = mysql_fetch_array($resultVentas) ){

        echo  $sqlUpdateVentas = "UPDATE `pedidos` SET `total_iva`='".$rowVentas['suma_iva']."' WHERE pedido = '".$rowVentas['pedido']."'  ";
        $resultDetalleVentas = mysql_query( $sqlUpdateVentas );
        echo '<br>';
        echo '<br>';

    }