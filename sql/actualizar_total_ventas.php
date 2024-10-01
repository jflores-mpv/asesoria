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
echo $sqlEmpresas="SELECT id_empresa  FROM `empresa` ORDER BY id_empresa ASC limit 500 OFFSET 2500  ;";
    //  echo $sqlEmpresas="SELECT id_empresa  FROM `empresa` LIMIT 500  ;";
    $resultEmpresas = mysql_query($sqlEmpresas);
    while($rowEmpre = mysql_fetch_array($resultEmpresas) ){
          $id_empre =$rowEmpre['id_empresa'];
            echo $sqlDetalleVentas = " SELECT detalle_ventas.`id_venta`, detalle_ventas.`v_total`, detalle_ventas.`id_servicio`, detalle_ventas.`id_empresa`, SUM(detalle_ventas.total_iva) as suma_iva FROM `detalle_ventas` INNER JOIN ventas ON ventas.id_venta =detalle_ventas.id_venta where ventas.id_empresa='".$id_empre."'  GROUP BY ventas.id_venta";
    echo '<br>';
    $resultVentas = mysql_query( $sqlDetalleVentas );

        while( $rowVentas = mysql_fetch_array($resultVentas) ){
    
            echo  $sqlUpdateVentas = "UPDATE `ventas` SET `total_iva`='".$rowVentas['suma_iva']."' WHERE id_venta = '".$rowVentas['id_venta']."'  ";
            $resultDetalleVentas = mysql_query( $sqlUpdateVentas );
            echo '<br>';
            echo '<br>';
    
        }
    }
  