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
    
    // OFFSET 500
  echo $sqlEmpresas="SELECT id_empresa  FROM `empresa` ORDER BY id_empresa limit 250 OFFSET 2250 ;";
    //  echo $sqlEmpresas="SELECT id_empresa  FROM `empresa` LIMIT 500  ;";
    $resultEmpresas = mysql_query($sqlEmpresas);
    while($rowEmpre = mysql_fetch_array($resultEmpresas) ){
        $id_empre =$rowEmpre['id_empresa'];
         $accion =2 ;
         echo "<br>EMPRESA Nº".$id_empre."<br>";
    // 1 fechas anteriores iva 12
    // 2 fecahs actuales iva 15
    $iva_asignado = 0;
    if($accion==1){
        $iva_asignado = 12;
        echo $sqlDetalleVentas = " SELECT ventas.fecha_venta, detalle_ventas.`id_detalle_venta`, detalle_ventas.`v_total`, detalle_ventas.`id_servicio`, detalle_ventas.`id_empresa`,    impuestos.iva FROM `detalle_ventas` INNER JOIN productos ON productos.id_producto =detalle_ventas.id_servicio INNER JOIN ventas on ventas.id_venta = detalle_ventas.id_venta INNER JOIN impuestos ON impuestos.id_iva = productos.iva WHERE DATE_FORMAT(ventas.fecha_venta,'%Y-%m-%d')<'2024-04-01' AND  detalle_ventas.tarifa_iva=0   AND detalle_ventas.`id_empresa`=$id_empre";
        echo '<br>';

    }else{
        $iva_asignado = 15;
        echo $sqlDetalleVentas = " SELECT ventas.fecha_venta, detalle_ventas.`id_detalle_venta`, detalle_ventas.`v_total`, detalle_ventas.`id_servicio`, detalle_ventas.`id_empresa`,    impuestos.iva FROM `detalle_ventas` INNER JOIN productos ON productos.id_producto =detalle_ventas.id_servicio INNER JOIN ventas on ventas.id_venta = detalle_ventas.id_venta INNER JOIN impuestos ON impuestos.id_iva = productos.iva WHERE DATE_FORMAT(ventas.fecha_venta,'%Y-%m-%d')>='2024-04-01'  AND  detalle_ventas.tarifa_iva=0 AND detalle_ventas.`id_empresa`=$id_empre";
         echo '<br>';

    }




   
    $resultVentas = mysql_query( $sqlDetalleVentas );
    $numFilas= mysql_num_rows( $resultVentas );
    
    while( $rowVentas = mysql_fetch_array($resultVentas) ){

        $valorTotal = $rowVentas['v_total'];
        $iva_p=0;
        $codigoPorcentaje= 0;
    if($rowVentas['iva']=='15'|| $rowVentas['iva']=='12'){
        $iva_p=$iva_asignado;
        $codigoPorcentaje= 2;

        if($accion==1){
            $sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE  (`id_empresa`='0' or `id_empresa`='".$rowVentas['id_empresa']."')  and iva=$iva_p  ORDER BY id_empresa DESC LIMIT 1;";
        }else{
            $sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE `id_empresa`='".$rowVentas['id_empresa']."' AND   iva=$iva_p";
        }
        
    }else{
        $sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE  `id_empresa`='".$rowVentas['id_empresa']."' and iva=0";
    }
        
    echo $sqlImpuesto;
    echo '<br>';
        $resultImpuesto = mysql_query($sqlImpuesto );
        $numFilasImp= mysql_num_rows( $resultImpuesto );
        $tarifa = 0;
        $id_iva_impuestos=0;
        if($numFilasImp >0){
            while( $rowProd = mysql_fetch_array( $resultImpuesto) ){
                $id_iva_impuestos =  $rowProd['id_iva'];
                $tarifa = $rowProd['iva'];
            }
        }
        
        // echo  $sqlUpdateProd = "UPDATE `productos` SET `iva`='".$id_iva_impuestos."'  WHERE id_producto = '".$rowVentas['id_servicio']."'  ";
        // $resultDetalleProd = mysql_query( $sqlUpdateProd );

        $total_iva_producto = floatval($valorTotal) * (floatval($tarifa)/100);
        echo  $sqlUpdateDetalle = "UPDATE `detalle_ventas` SET `tarifa_iva`='".$id_iva_impuestos."',`total_iva`='".$total_iva_producto."' WHERE id_detalle_venta = '".$rowVentas['id_detalle_venta']."'  ";
        $resultDetalleVentas = mysql_query( $sqlUpdateDetalle );

        echo '<br>';
        echo '<br>';
    }  
    }
 echo "TERMINO";