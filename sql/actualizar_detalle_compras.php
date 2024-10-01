<?php

error_reporting(0);
	//require_once('../ver_sesion.php');

        date_default_timezone_set('America/Guayaquil');

	//Start session
	session_start();
exit;
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
  echo $sqlEmpresas="SELECT id_empresa  FROM `empresa` ORDER BY id_empresa ASC LIMIT 250 OFFSET 2500";
    //  echo $sqlEmpresas="SELECT id_empresa  FROM `empresa`  ORDER BY id_empresa ASC LIMIT 250 ;";
    $resultEmpresas = mysql_query($sqlEmpresas);
      $accion =2 ;
          echo "<br>ACCION  Nº".$accion."<br>";
    while($rowEmpre = mysql_fetch_array($resultEmpresas) ){
        $id_empre =$rowEmpre['id_empresa'];
       
         echo "<br>EMPRESA Nº".$id_empre."<br>";
    // 1 fechas anteriores iva 12
    // 2 fecahs actuales iva 15
    $iva_asignado = 0;
    if($accion==1){
        $iva_asignado = 12;
        echo $sqlDetalleVentas = "SELECT
compras.fecha_compra,
    detalle_compras.`id_detalle_compra`,
    detalle_compras.`idBodega`,
    detalle_compras.`idBodegaInventario`,
    detalle_compras.`cantidad`,
    detalle_compras.`valor_unitario`,
    detalle_compras.`des`,
    detalle_compras.`valor_total`as v_total,
    detalle_compras.`id_compra`,
    detalle_compras.`id_producto`,
    detalle_compras.`id_empresa`,
    detalle_compras.`xml`,
    detalle_compras.`centro_costo_empresa`,
    detalle_compras.`item`,
    detalle_compras.`iva`,
    detalle_compras.`total_iva`,
    productos.iva as productos_iva,
     impuestos.iva 
FROM
    `detalle_compras`
INNER JOIN compras ON compras.id_compra = detalle_compras.id_compra
INNER JOIN productos ON productos.id_producto = detalle_compras.id_producto
LEFT JOIN impuestos ON impuestos.id_iva = productos.iva
WHERE
    DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') < '2024-04-01'  AND  detalle_compras.id_empresa =$id_empre";
        echo '<br>';

    }else{
        $iva_asignado = 15;
        echo $sqlDetalleVentas = "SELECT
compras.fecha_compra,
    detalle_compras.`id_detalle_compra`,
    detalle_compras.`idBodega`,
    detalle_compras.`idBodegaInventario`,
    detalle_compras.`cantidad`,
    detalle_compras.`valor_unitario`,
    detalle_compras.`des`,
    detalle_compras.`valor_total` as v_total,
    detalle_compras.`id_compra`,
    detalle_compras.`id_producto`,
    detalle_compras.`id_empresa`,
    detalle_compras.`xml`,
    detalle_compras.`centro_costo_empresa`,
    detalle_compras.`item`,
    detalle_compras.`total_iva`,
     productos.iva as productos_iva,
     impuestos.iva 
FROM
    `detalle_compras`
INNER JOIN compras ON compras.id_compra = detalle_compras.id_compra
INNER JOIN productos ON productos.id_producto = detalle_compras.id_producto
LEFT JOIN impuestos ON impuestos.id_iva = productos.iva
WHERE
    DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') >= '2024-04-01' AND detalle_compras.id_empresa =$id_empre";
         echo '<br>';

    }




   
    $resultVentas = mysql_query( $sqlDetalleVentas );
    $numFilas= mysql_num_rows( $resultVentas );
    
    while( $rowVentas = mysql_fetch_array($resultVentas) ){

        $valorTotal = $rowVentas['v_total'];
        $iva_p=0;
        $codigoPorcentaje= 0;
    if($rowVentas['iva']=='15'|| $rowVentas['iva']=='12'|| $rowVentas['productos_iva']=='SI' || $rowVentas['productos_iva']=='Si'|| $rowVentas['iva']=='5' || $rowVentas['iva']=='8'){
        $iva_p=$iva_asignado;
        $codigoPorcentaje= 2;
        
        if( $rowVentas['productos_iva']=='SI' || $rowVentas['productos_iva']=='Si'){
            $iva_p=$iva_asignado;
             
        }else{
             $iva_p=$rowVentas['iva'];
        }
        
        if($accion==1){
            $sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE  (`id_empresa`='0' or `id_empresa`='".$rowVentas['id_empresa']."')  and iva=$iva_p  ORDER BY id_empresa DESC LIMIT 1;";
        }else{
            $sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE (`id_empresa`='0' or `id_empresa`='".$rowVentas['id_empresa']."') AND   iva=$iva_p ORDER BY id_empresa DESC LIMIT 1 ";
        }
        
    }else{
        $sqlImpuesto = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE  (`id_empresa`='0' or `id_empresa`='".$rowVentas['id_empresa']."') and iva=0 ORDER BY id_empresa DESC LIMIT 1";
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
        echo ' <br>TARIFA =>'.$tarifa.'|<br>';
        echo ' VALOR TOTAL =>'.$valorTotal.'|<br>';
        
        $total_iva_producto = floatval($valorTotal) * (floatval($tarifa)/100);
        echo  $sqlUpdateDetalle = "UPDATE `detalle_compras` SET `iva`='".$id_iva_impuestos."',`total_iva`='".$total_iva_producto."' WHERE id_detalle_compra = '".$rowVentas['id_detalle_compra']."'  ";
        $resultDetalleVentas = mysql_query( $sqlUpdateDetalle );

        echo '<br>';
        echo '<br>';
    }  
    }
 echo "TERMINO";